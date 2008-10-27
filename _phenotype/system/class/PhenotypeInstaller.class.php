<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------





//define("INDEXPHP_DEST", "../index.php");
//define("INDEXPHP_WORKING", "index_postinstall.php");

define("SQL_DUMP", "../phenotype.sql");

define("SAMPLE_CONFIG_FILE", "../_config.inc.sample.php");
define("CONFIG_FILE", "../_config.inc.php");

define("SAMPLE_HTACCESS_FILE", "../htaccess_smartURL_sample");
define("HTACCESS_FILE", ".htaccess");

define("SAMPLE_HOST_CONFIG", "../_phenotype/application/_host.config.sample.inc.php");
define("HOST_CONFIG", "../_phenotype/application/_host.config.inc.php");

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeInstaller
{
	private $error_globalfeedback = false;
	private $step = 1;

	public $database_status = "";

	public $database_server ="localhost";
	public $database_user ="root";
	public $database_password ="";
	public $database_name ="phenotype";

	public $superuser_login = "starter";
	public $superuser_password ="deleteme";

	public $path_status ="";

	public $path_basepath="";
	public $path_baseurl="";
	public $path_hostname="";
	public $path_fullurl="";

	public $app_backend_language = 1;
	public $app_package = 2;

	// radio buttons, positive default values must be set within the constructor
	public $app_debug_mode = 0;
	public $app_frontend_session = 0;


	public $installation_status ="";

	/**
	 * get configuration out of request
	 *
	 */
	public function __construct()
	{
		global $myRequest;

		if ($myRequest->check("btn_install"))
		{
			$this->step=2;
		}


		// determine pathes (default values)

		$dirname = dirname($_SERVER["SCRIPT_FILENAME"]);

		$myDirs = explode("/", $dirname);
		array_pop($myDirs);
		$this->path_basepath = implode("/", $myDirs) ."/";

		$urlprefix = dirname($_SERVER['PHP_SELF']);
		$myDirs = explode("/", $urlprefix);
		array_pop($myDirs);
		$urlprefix = implode("/", $myDirs);
		$this->path_baseurl = $urlprefix ."/";

		$this->path_hostname = $_SERVER["HTTP_HOST"];


		// default values for checkboxes
		if (!$this->isReload())
		{
			$this->app_debug_mode=1;
		}

		$_params = array("database_server","database_user","database_password","database_name","superuser_login","superuser_password","path_basepath","path_baseurl","path_hostname","app_backend_language","app_debug_mode","app_frontend_session","app_package");

		foreach ($_params AS $param)
		{
			if ($myRequest->check($param))
			{
				$this->$param = $myRequest->get($param);
			}
		}

		// determine full url
		$this->path_fullurl = "http://".$this->path_hostname. $this->path_baseurl;

		// superuser login and password cannot be empty

		if ($this->superuser_login==""){$this->superuser_login="starter";}
		if ($this->superuser_password==""){$this->superuser_password="deleteme";}


		switch ($this->step)
		{
			case 1:
				// do all checks for the first time, to find out the global status, afterwards the
				// checks are repeated to display details
				$this->checkPHP();
				$this->checkDB();
				$this->checkPathes();
				$this->checkRWPermissions();
				$this->checkApache();
				break;
			case 2:
				break;
		}

	}


	public function isReload()
	{
		global $myRequest;
		if ((int)$myRequest->get("reload")==1)
		{
			return true;
		}
		return false;
	}

	public function gotErrors()
	{
		return $this->error_globalfeedback;
	}

	public function getStep()
	{
		return $this->step;
	}

	public function checkDB()
	{
		$this->database_status = "Everything seems to be fine. Connection successful.";


		if (!$myDB= @mysql_connect($this->database_server, $this->database_user, $this->database_password) )
		{
			$this->database_status = "Database connection failed, please check the account data.";
			$this->error_globalfeedback=true;
			return false;
		}


		// Connection o.k.
		$sql = "SELECT VERSION()";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$v = (int)$row[0];
		if ($v<4)
		{
			$this->database_status = "This version of MySQL is too old: " . $row[0] . " Minimum 4.x required.";
			$this->error_globalfeedback_globalfeedback=true;
			return false;
		}

		// connection established, database availabe?

		if (!mysql_select_db($this->database_name, $myDB))
		{

			if(@mysql_query("CREATE DATABASE phenotype_db_create_test"))
			{
				mysql_query("DROP DATABASE phenotype_db_create_test");
				$this->database_status = "Connection successful, but database doesn't exist.<br/>The installer could create the database for you.";

			}
			else
			{
				$this->database_status = "Database can't be selected. user ".$username." doesn't have CREATE DATABASE permissions.<br/>You have to create the database (manually) prior to this installation step.";
				$this->error_globalfeedback=true;
				return false;
			}
		}



		// Is the database empty?
		$sql = "SHOW TABLES";
		$rs = mysql_query($sql);

		if ($rs)
		{
			if (mysql_num_rows($rs)!=0)
			{
				$this->database_status="Database not empty! Installation may fail, please make sure none of the existing tables conflicts with the phenotype initial sql import.";
				$this->error_globalfeedback=true;
				return false;
			}
		}


		return true;
	}

	public function checkPathes()
	{

		if (!$this->isReload() AND $_SERVER["SCRIPT_FILENAME"]=="")
		{
			$this->path_status = "Unfortunately we could not determine the base path of your installation.<br/> Please fill in the path to values manually.";
			return false;
		}

		if ($this->path_baseurl=="")
		{
			$this->path_status = "Base URL cannot be empty. It must at least contain a slash<br/> (e.g. hostname=localhost and baseURL =/).";
			$this->error_globalfeedback=true;
			return false;
		}


		if (!@file_get_contents($this->path_basepath."install.txt"))
		{
			$this->path_status ='Basepath seems to be wrong. It must point to the root folder of your installation and end with an slash.(e.g. C:\XAMPP\phenotype\ or /var/www/htdocs/phenotype/). If that\'s so and you still get an error here, check the file permission for that folder.';
			$this->error_globalfeedback=true;
			return false;
		}
		$fullurlcheck=@file_get_contents($this->path_fullurl."installcheck.txt");
		if ($fullurlcheck!="o.k.")
		{
			$this->path_status="Either base URL or hostname seems to be wrong. The Full URL (combination of both) must point to the main web folder, where the index.php resides.";
			$this->error_globalfeedback=true;
			return false;
		}


		$this->path_status = "Everything seems to be all right.";
		return true;
	}

	public function getOptionsAsHTML($type)
	{
		$html="";
		$_options=array();
		$value="";
		switch ($type)
		{
			case "app_backend_language":
				$_options=Array(1=>"english",2=>"german");
				$value=$this->app_backend_language;
				break;
			case "app_package":

				$_options = Array(1=>"PT_CORE (basic installation)",2=>"PT_DEMO (demonstration package)");
				$value=$this->app_package;
				break;
		}
		foreach ($_options AS $k=>$v)
		{
			$selected="";
			if ($k==$value)
			{
				$selected='selected=="selected"';
			}
			$html.='<option value="'.$k.'" '.$selected.'>'.htmlentities($v).'</option>';
		}
		return $html;
	}


	public function getCheckboxSelectionAsHTML($type)
	{
		$html = "";
		switch ($type)
		{
			case "app_frontend_session":
				if ($this->app_frontend_session==1)
				{
					$html='checked="checked"';
				}
				break;
			case "app_debug_mode":
				if ($this->app_debug_mode==1)
				{
					$html='checked="checked"';
				}
				break;
		}
		return $html;
	}

	public function checkPHP()
	{
		$_array = Array();


		if (phpVersion()>="5.0")
		{
			$_array[]=array("title"=>"PHP version","status"=>"o.k. (".phpVersion().")","class"=>"green","hint"=>"");
		}
		else
		{
			$_array[]=array("title"=>"PHP version","status"=>"too old (".phpVersion().")","class"=>"red","hint"=>"");
			$this->error_globalfeedback=true;
		}

		if (extension_loaded("gd"))
		{
			$_array[]=array("title"=>"gdlib php extension","status"=>"o.k.","class"=>"green","hint"=>"");
		}
		else
		{
			$_array[]=array("title"=>"gdlib php extension","status"=>"not found","class"=>"red","url"=>"http://www.php.net/gd");

			$this->error_globalfeedback=true;}

			if (extension_loaded("SimpleXML"))
			{
				$_array[]=array("title"=>"simpleXML php extension","status"=>"o.k.","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]=array("title"=>"simpleXML php extension","status"=>"not found","class"=>"red","url"=>"http://www.php.net/manual/en/book.simplexml.php");
				$this->error_globalfeedback=true;
			}

			if (extension_loaded("mysql"))
			{
				$_array[]=array("title"=>"MySQL php extension","status"=>"o.k.","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]=array("title"=>"MySQL php extension","status"=>"not found","class"=>"red","url"=>"http://www.php.net/manual/en/book.mysql.php");
				$this->error_globalfeedback=true;
			}

			$m = (int) ini_get("memory_limit");
			if ($m>=16)
			{
				$_array[]=array("title"=>"memory_limit","status"=>"o.k. (".$m." MB)","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]=array("title"=>"memory_limit","status"=>"low (".$m." MB)","class"=>"yellow","hint"=>"");
				$this->error_globalfeedback=true;
			}

			if (ini_get('register_globals')!=1)
			{
				$_array[]=array("title"=>"register_globals","status"=>"o.k. (inactive)","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]=array("title"=>"register_globals","status"=>"wrong setting (active)","class"=>"red","hint"=>"");
				$this->error_globalfeedback=true;
			}
			if (ini_get('safe_mode')!=1)
			{
				$_array[]=array("title"=>"safe_mode","status"=>"o.k. (off)","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]=array("title"=>"safe_mode","status"=>"o.k. (on)","class"=>"yellow","hint"=>"");
			}

			return ($_array);
	}

	public function checkApache()
	{
		if (function_exists("apache_get_modules"))
		{
			$_mods = apache_get_modules();

			while ($module = array_shift($_mods) )
			{
				if ($module == "mod_rewrite")
				{
					return array(array("title"=>"mod_rewrite","status"=>"o.k. (active)","class"=>"green","hint"=>""));
				}
			}

			// module not found
			$this->error_globalfeedback=true;
			return array(array("title"=>"mod_rewrite","status"=>"not found","class"=>"red","hint"=>""));
		}

		return array(array("title"=>"mod_rewrite","status"=>"unknown","class"=>"yellow","hint"=>""));
	}


	public function checkRWPermissions()
	{
		$_array = Array();

		// check for general file permission in basic folders

		if ($this->is_writable("../",false)==true)
		{
			$_array[]=array("title"=>"/ (root folder)","status"=>"o.k.","class"=>"green","hint"=>"");
		}
		else
		{
			$_array[]= array("title"=>"/ (root folder)","status"=>"insufficient","class"=>"red","hint"=>"");
			$this->error_globalfeedback=true;
		}

		if ($this->is_writable("../htdocs",false)==true)
		{
			$_array[]=array("title"=>"/htdocs","status"=>"o.k.","class"=>"green","hint"=>"");
		}
		else
		{
			$_array[]= array("title"=>"/htdocs","status"=>"insufficient","class"=>"red","hint"=>"");
			$this->error_globalfeedback=true;
		}

		// check for file permissions in runtime folders including subfolders

		$_folders = Array("/htdocs/media"=>"media","/_phenotype/cache"=>"../_phenotype/cache","/_phenotype/application"=>"../_phenotype/application","/_phenotype/temp"=>"../_phenotype/temp");
		foreach($_folders AS $title=>$path)
		{
			if ($this->is_writable($path,true)==true)
			{
				$_array[]=array("title"=>$title,"status"=>"o.k.","class"=>"green","hint"=>"");
			}
			else
			{
				$_array[]= array("title"=>$title,"status"=>"insufficient","class"=>"red","hint"=>"");
				$this->error_globalfeedback=true;
			}
		}
		return ($_array);
	}


	public function is_writable($path,$subfolders=false)
	{
		//will work in despite of Windows ACLs bug
		//NOTE: use a trailing slash for folders!!!
		//see http://bugs.php.net/bug.php?id=27609
		//see http://bugs.php.net/bug.php?id=30931

		if ($path{strlen($path)-1}=='/')
		{
			// recursively return a temporary file path
			return $this->is_writable($path.uniqid(mt_rand()).'.tmp',$subfolders);
		}
		else if (is_dir($path))
		{
			if ($subfolders==true)
			{
				if ($handle = opendir($path))
				{
					while (false !== ($file = readdir($handle)))
					{
						if ($file != "." && $file != "..")
						{
							if (is_dir($path."/".$file."/"))
							{
								$rc = $this->is_writable($path."/".$file."/",$subfolders);
								if ($rc===false)
								{
									return false;
								}
							}
						}
					}
					closedir($handle);
				}
				else
				{
					return false;
				}
			}
			return $this->is_writable($path.'/'.uniqid(mt_rand()).'.tmp',$subfolders);
		}
		// check tmp file for read/write capabilities
		$rm = file_exists($path);
		$f = @fopen($path, 'a');
		if ($f===false)
		return false;
		fclose($f);
		if (!$rm)
		unlink($path);
		return true;
	}

	function installDB()
	{
		$_logs = array();

		if (!$myDB= @mysql_connect($this->database_server, $this->database_user, $this->database_password) )
		{
			$this->installation_status = "Database connection failed, please check the account data.";
			$this->error_globalfeedback=true;

			return $_logs;
		}
		$_logs[]="Successfull connection to database ".$this->database_server . " with user ".$this->database_user;

		// connection established, database availabe?

		if (!mysql_select_db($this->database_name, $myDB))
		{

			if(!@mysql_query("CREATE DATABASE ".mysql_real_escape_string($this->database_name)))
			{
				$this->installation_status = "Connection successful, but I could not create a new database for you.<br/>Please create database manually and reload this page.";
				$this->error_globalfeedback=true;
				return $_logs;
			}
			$_logs[]="Creating database ".$this->database_name;

		}
		$_logs[]="Selecting database ".$this->database_name;


		$sql ="SET NAMES UTF8";
		$rs = mysql_query($sql);

		$_logs[]="Setting connection mode to UTF-8";

		$sql="SET @@session.sql_mode='NO_FIELD_OPTIONS'";
		$rs = mysql_query($sql);
		$_logs[]="Switching to MySQL 4 compatibility mode";

		$_logs[]="Reading initial sql dump";

		$sql = @file_get_contents(SQL_DUMP);
		if (!$sql)
		{
			$this->installation_status = "Could not open ".SQL_DUMP. " for import.";
			$this->error_globalfeedback=true;
			return $_logs;
		}

		$_sql = explode(";\n",$sql);
		if (count($_sql)==1)
		{
			$_sql = explode(";".chr(10),$sql);
		}
		if (count($_sql)==1)
		{
			$_sql = explode(";".chr(13),$sql);
		}
		if (count($_sql)==1)
		{
			$this->installation_status = "Problems parsing sql dump (".SQL_DUMP."). Please check carriage returns of that file an retransfer it binary";
			$this->error_globalfeedback=true;
			return $_logs;
		}
		$_logs[]="Now inserting ".count($_sql). " queries.";

		foreach ($_sql AS $sql)
		{
			$rs = mysql_query($sql);
			if (!$rs)
			{
				$this->installation_status = "Sorry, the initialization of the database was not successful.";
				$_logs[] = mysql_error();
				$this->error_globalfeedback=true;
				return $_logs;
			}
		}
		$_logs[] = "Queries executed";
		return $_logs;
	}


	/*
	* writes the config file with the collected and tested data
	*
	*/
	public function writeConfigFiles() {

		$_logs = array();

		$exps = Array();
		$subs = Array();

		// basepath
		$exps[] = '/^define \("BASEPATH",.+\);/';
		$subs[] = 'define ("BASEPATH","'. $this->path_basepath .'");';

		// server url
		$exps[] = '/^define \("SERVERURL",.+\);/';
		$subs[] = 'define ("SERVERURL","'. $this->path_baseurl .'");';

		// serverfullurl
		$exps[] = '/^define \("SERVERFULLURL",.+\);/';
		$subs[] = 'define ("SERVERFULLURL","http://'. $this->path_hostname .'". SERVERURL);';

		// db host
		$exps[] = '/^define \("DATABASE_SERVER",.+\);/';
		$subs[] = 'define ("DATABASE_SERVER","'. $this->database_server .'");';

		// db user
		$exps[] = '/^define \("DATABASE_USER",.+\);/';
		$subs[] = 'define ("DATABASE_USER","'. $this->database_user .'");';

		// db pass
		$exps[] = '/^define \("DATABASE_PASSWORD",.+\);/';
		$subs[] = 'define ("DATABASE_PASSWORD","'. $this->database_password .'");';

		// db name
		$exps[] = '/^define \("DATABASE_NAME",.+\);/';
		$subs[] = 'define ("DATABASE_NAME","'. $this->database_name .'");';

		// debug mode
		$exps[] = '/^define \("PT_DEBUG",.+\);/';
		$subs[] = 'define ("PT_DEBUG",'.(int)$this->app_debug_mode.');';
		
		// frontend session
		$exps[] = '/^define \("PT_FRONTENDSESSION",.+\);/';
		$subs[] = 'define ("PT_FRONTENDSESSION",'.(int)$this->app_frontend_session.');';
		
		// default backend language
		$_options = array(1=>"en",2=>"de");
		$exps[] = '/^define \("PT_LOCALE",.+\);/';
		$subs[] = 'define ("PT_LOCALE","'.$_options[(int)$this->app_backend_language].'");';

		
		$templateConfig = file(SAMPLE_CONFIG_FILE);

		
		$config = "";
		foreach ($templateConfig as $line) {
			$config .= preg_replace($exps, $subs, $line);
		}

		$_logs[] = "Writing _config.inc.php (main configuration file)";
		if (!@file_put_contents(CONFIG_FILE, $config))
		{
			$this->installation = "Could not write _config.inc.php. Check read/write permissions";
			$this->error_globalfeedback=true;
			return $_logs;
		}
		
		$_logs[] = "Writing _host.config.inc.php (possible host specific configuration file)";
		if (!@copy (SAMPLE_HOST_CONFIG, HOST_CONFIG))
		{
			$this->installation = "Could not write _host.config.inc.php. Check read/write permissions";
			$this->error_globalfeedback=true;
			return $_logs;
		}
		

		$templateHTAccess = file(SAMPLE_HTACCESS_FILE);

		$htaccess = "";
		foreach ($templateHTAccess as $line) {
			$exp = '/^\s*RewriteBase .+$/';
			$sub = "\tRewriteBase ". $this->path_baseurl;
			$htaccess .= preg_replace($exp, $sub, $line);
		}
		$_logs[] = "Writing .htaccess file";

		if (!@file_put_contents(HTACCESS_FILE, $htaccess))
		{
			$this->installation = "Could not write .htaccess file. Check read/write permissions";
			$this->error_globalfeedback=true;
			return $_logs;
		}
		return $_logs;
	}


}



