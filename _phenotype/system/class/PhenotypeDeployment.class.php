<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support:
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


/**
 * @package phenotype
 * @subpackage system
 *
 * TODO: 
 * - remove third party files from token replace process
 * - skip smarty temp files
 */
class PhenotypeDeployment
{
	public $debug = false;
	
	public $version = "2.92";

	public $revision = 389;

	public $base_path="";

	public $build_path= "srv_build";

	public $_tokens = Array();

	public $_dirs = Array();

	public function execute()
	{
		$this->base_path = BASEPATH;

		$this->build_path = str_replace("srv_sf",$this->build_path,$this->base_path);


		echo "base_path: ". $this->base_path ."<br/>";
		echo "build_path: ". $this->build_path ."<br/>";

		if ($this->base_path==$this->build_path)
		{
			echo '<br/><span class="red">Please check path setup.</span>';
			return;
		}

		$this->_tokens["##!PT_VERSION!##"]  =$this->version;
		$this->_tokens["##!BUILD_DATE!##"]  = date ('d.m.Y');
		$this->_tokens["##!BUILD_YEAR!##"]  = date ('Y');
		$this->_tokens["##!BUILD_NO!##"]  = "r".$this->revision;
		$this->_tokens["##!BUILD_ID!##"]  = "Phenotype ".$this->version.", build r".$this->revision." PHP".phpversion()."@".$_ENV["OS"]."(".$_ENV["COMPUTERNAME"].")";

		$this->cleanUpBuildDir();
		$this->build();
	}

	
	public function cleanUpBuildDir()
	{
		@mkdir($this->build_path);
		if (!file_exists($this->build_path))
		{
			echo '<br/><span class="red">Couldn\'t find build folder</span>';
			die();
		}
		echo "<br/>cleaning up build folder (deleting all content):<br/><br/>";
		if ($this->debug==false)
		{
			PhenotypeAdmin::removeDirComplete($this->build_path,1,false);
		}
	}
	public function build()
	{
		echo "<br/>Tokens:<br/>";
		foreach ($this->_tokens AS $token => $v)
		{
			echo $token ." => ".$v."<br/>";
		}

		// List of files, that should never be copied
		
		$_skip = Array("php.ini","install.4build.php",".htaccess",".htpasswd","dndplus.jar","phperror.log","phpids_log.txt","deploy.php");
				
		$_files = Array();
		$_files[]="index.html";
		$_files[]="_config.inc.sample.php";
		$_files[]="htaccess_smartURL_sample";
		$_files[]="phenotype_install.sql";
		$_files[]="install.txt";
		$_files[]="setrights.sh";
		$_files[]="buildinfo.inc.php";

		
		echo "<br/>copy example files:<br/><br/>";
		foreach ($_files AS $file)
		{
			$source= $this->base_path.$file;
			$target = $this->build_path.$file;
			echo "copy " .$file."<br/>";
			$this->copyFile($source,$target,true);
		}
	
		
		
		echo "<br/>copy (almost) empty mirror folders, to provide necessary folder structure:<br/><br/>";

		$this->resetDirScanner();
		$_files = $this->scanDir($this->base_path."_mirror",$_skip);
		// we don't want to have the _mirror folder in our target
		$_dirs=Array();
		foreach ($this->_dirs AS $dir)
		{
			$dir = str_replace("_mirror/","",$dir);
			if (trim($dir)!="_mirror")
			{
				$_dirs[] = trim($dir);
			}
		}
		$this->_dirs=$_dirs;
		$this->createDirectories($this->build_path);
		
		foreach ($_files AS $file)
		{
			$source= $this->base_path.$file;
			
			echo "copy " .$file."<br/>";
			$file = str_replace("_mirror/","",$file);
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;-- move to ".$file."<br/>";
			$target = $this->build_path.$file;
			$this->copyFile($source,$target,true);
		}
		
		echo "<br/>copy system folder (=_phenotype):<br/><br/>";

		$this->resetDirScanner();
		$_files = $this->scanDir($this->base_path."_phenotype",$_skip);
		$this->createDirectories($this->build_path);
		
		foreach ($_files AS $file)
		{
			$source= $this->base_path.$file;
			$target = $this->build_path.$file;
			echo "copy " .$file."<br/>";
			$this->copyFile($source,$target,true);
		}
		
		echo "<br/>copy htdocs folder (=whole admin backend and PT_CORE snippets):<br/><br/>";
		$this->resetDirScanner();
		$_files = $this->scanDir($this->base_path."htdocs",$_skip);
		$this->createDirectories($this->build_path);
		
		foreach ($_files AS $file)
		{
			$source= $this->base_path.$file;
			$target = $this->build_path.$file;
			echo "copy " .$file."<br/>";
			$this->copyFile($source,$target,true);
		}
		
	
		// special file install.4build.php (gets renamed to install.php)
		$_files = Array();
		$_files[]="install.4build.php";
		echo "<br/>copy installer file:<br/><br/>";
		foreach ($_files AS $file)
		{
			$source= $this->base_path."htdocs/".$file;
			$target = $this->build_path."htdocs/".$file;
			echo "copy " .$file."<br/>";
			$this->copyFile($source,$target,true);
		}		
		rename ($target,$this->build_path."htdocs/install.php");
		echo "&nbsp;&nbsp;&nbsp;&nbsp;-- rename to install.php<br/>";
	}

	/**
	 * Collect file names of the given folder, skipping those provided in the skip array
	 * 
	 * .svn .tmp and .bak are ignored everytime
	 *
	 * @param unknown_type $dir
	 * @param unknown_type $_skip
	 * @return unknown
	 */
	public function scanDir($dir,$_skip=Array())
	{
		// store folder
		$folder = str_replace($this->base_path,"",$dir);
		$this->_dirs[]=$folder;
		
		$_files=Array();
		if ($fp= opendir($dir))
		{
			while (($file = readdir($fp)) !== false)
			{
				if (($file == ".") || ($file == "..") || ($file == ".svn") || ($file == ".bak") || ($file == ".tmp"))
				{
					continue;
				}
				if (is_dir($dir . '/' . $file))
				{
					$_subfiles=self::scanDir($dir . '/' . $file,$_skip);
					if (is_array($_subfiles))
					{
						$_files = array_merge($_files,$_subfiles);
					}
				}
				else
				{
					if (!in_array($file,$_skip))
					{
						$_files[] = str_replace($this->base_path,"",$dir .'/'.$file);
					}
				}
			}
		}
		return ($_files);
	}
	
	public function resetDirScanner()
	{
		$this->_dirs=Array();
	}
	
	public function createDirectories($build_path)
	{
		echo "creating directories: (on top of ".$build_path.")<br/><br/>";
		foreach ($this->_dirs AS $dir)
		{
			echo "mkdir ".$dir."<br/>";
			if ($this->debug==false)
			{
				mkdir($build_path.$dir);
			}
		}
		echo "<br/>";
	}
	
	public function copyFile($source,$target,$use_tokens=true)
	{
		if ($this->debug==false)
		{
			if ($use_tokens==true)
			{
				$suffix=mb_substr(trim($source),-4,4);
				if ($suffix==".php")
				{
					echo "&nbsp;&nbsp;&nbsp;&nbsp;-- insert tokens<br/>";
					$s = file_get_contents($source);
					foreach ($this->_tokens AS $token => $v)
					{
						$s=str_replace($token,$v,$s);
					}
					file_put_contents($target,$s);
				}
				else 
				{
					copy ($source,$target);
				}
				
			}
			else 
			{
				copy ($source,$target);
			}
		}
	}
}