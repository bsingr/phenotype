<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

set_time_limit(0);

require "../../buildinfo.inc.php";

$reqs = Array();
$reqs[] = array('type' => "phpVersion", 'pattern' => "5", 'importance' => "required", 'message' => "php version");
$reqs[] = array('type' => "phpExt", 'pattern' => "gd", 'importance' => "required", 'message' => "gdlib php extension");
$reqs[] = array('type' => "phpExt", 'pattern' => "SimpleXML", 'importance' => "required", 'message' => "simpleXML php extension");
$reqs[] = array('type' => "phpExt", 'pattern' => "mysql", 'importance' => "required", 'message' => "MySQL extension");
$reqs[] = array('type' => "phpSetting", 'pattern' => "memory_limit", 'importance' => "recommended", 'message' => "Memory Limit >= 16 MB");
$reqs[] = array('type' => "phpSetting", 'pattern' => "register_globals", 'importance' => "required", 'message' => "Register Globals = Off");
$reqs[] = array('type' => "phpSetting", 'pattern' => "safe_mode", 'importance' => "recommended", 'message' => "Safe Mode = Off");
$reqs[] = array('type' => "filePermission", 'pattern' => "", 'mode' => "rw", 'importance' => "required", 'message' => "read/write permissions in root (needed for installer to write config file)");
$reqs[] = array('type' => "filePermission", 'pattern' => "htdocs", 'mode' => "rw", 'importance' => "recommended", 'message' => "read/write permissions in htdocs");
$reqs[] = array('type' => "filePermission", 'pattern' => "htdocs/media", 'mode' => "rw", 'importance' => "required", 'message' => "read/write permissions in htdocs/media");
$reqs[] = array('type' => "filePermission", 'pattern' => "_phenotype", 'mode' => "rw", 'importance' => "required", 'message' => "read/write permissions in _phenotype");
$reqs[] = array('type' => "filePermission", 'pattern' => "_phenotype/system", 'mode' => "r", 'importance' => "required", 'message' => "read permissions in _phenotype/system");
$reqs[] = array('type' => "filePermission", 'pattern' => "_phenotype/temp", 'mode' => "wr", 'importance' => "required", 'message' => "read/write permissions in _phenotype/temp");


define("SAMPLE_CONFIG_FILE", "../../_config.inc.sample.php");
define("CONFIG_FILE", "../../_config.inc.php");
define("SAMPLE_HOST_CONFIG", "../../_phenotype/application/_host.config.sample.inc.php");
define("HOST_CONFIG", "../../_phenotype/application/_host.config.inc.php");
define("SAMPLE_HTACCESS_FILE", "../../htaccess_smartURL_sample");
define("HTACCESS_FILE", "../.htaccess");
define("INDEXPHP_DEST", "../index.php");
define("INDEXPHP_WORKING", "index_postinstall.php");
define("SQL_DUMP", "phenotype.sql");

/*
* renders page header
*
*/
function pageHeader() {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>Phenotype Installer</title>
	<link rel="stylesheet" href="css/installer.css" type="text/css">
</head>
<body>

<div id="header">
	<!-- Kontakt & Impressum -->
	<div id="kopfNaviContainer">
		<div id="kopfNavi">
			
			<div id="entry"><!--Version ##!PT_VERSION!## vom ##!BUILD_DATE!##--></div>
		</div><!-- // kopfNavi -->
	</div><!-- // kopfNaviContainer -->
  <div id="logo"><a href="http://www.phenotype.de"><img src="img/phenotype_cms_logo.gif" border="0" alt="" /></a>
  </div><!-- // logo -->
  <div id="topnavicontainer">
		<div id="topnavi">
		</div>
	</div><!-- // topnavi -->
</div><!-- // header -->
<div id="searchlineContainer">
	<div id="searchline">
	</div><!-- // searchline -->
</div><!-- // searchlineContainer -->

	<div id="MainFrameBox">

		<div id="ContentFrameBox">
<?
}


/*
* renders page footer
*
*/
function pageFooter() {
?>
		</div>
	</div>
</body>
</html>
<?php

}


/*
* controls what to do
*
*/
function doAction($action) {

	switch ($action)
	{
		case "install":
			doActionInstall();
			break;
		default:
			doActionCheck();
			break;

	}

	?>
	<br><br/>
	This is the Phenotype installer. Please be aware of the fact that <strong>the software is available only in German</strong> at the moment, even if the installer is already in English.<br /><br />
	<strong>Problems?</strong> Please post your questions and problems in the forum on the <a href="http://www.phenotype.de/forum/" target="_blank">Phenotype forum</a>.<br /><br /><br /><br />
	<?
}


/*
* the system checks
*
* checks all dependencies and requirements for the target system
*
*/
function doActionCheck() {
	global $reqs;

?>
			<H2>Phenotype Installer</H2>
			<p>Version <?=PT_VERSION?></p>
			<p>Checking the system...</p>
			<p>
<?			
$errors = 0;
$warnings = 0;

foreach ($reqs as $myReq) {
	$actionError = 0;

	$type = $myReq["type"];
	switch ($type) {
		case "phpVersion":
			$statement = 'return (version_compare(phpVersion(), "'. $myReq["pattern"] .'", ">="));';
			break;

		case "phpExt":
			$statement = 'return(extension_loaded("'. $myReq["pattern"] .'"));';
			break;

		case "filePermission":
			if ($myReq['mode'] == "w") {
				$statement = 'return(is_writable("../../'. $myReq["pattern"] .'"));';
			} elseif ($myReq['mode'] == "r") {
				$statement = 'return(is_readable("../../'. $myReq["pattern"] .'"));';
			} elseif ($myReq['mode'] == "rw" || $myReq['mode'] == "wr") {
				$statement = 'return(is_readable("../../'. $myReq["pattern"] .'") && is_writable("../../'. $myReq["pattern"] .'"));';
			}
			break;

		case "generic":
			$statement = 'return('. $myReq["pattern"] .');';
			break;

		case "phpSetting":
			$statement='return(isSettingOk("'. $myReq["pattern"] .'"));';
			break;

		default:
			$statement = "";
			$actionError = 1;
			break;
	}

	if (! $actionError) {

		?>checking for <?=$myReq["message"]?>...<?
		//echo("statement:&nbsp;&nbsp;$statement<BR />");
		if (eval($statement)) {
			// ok
			echo ("&nbsp;<span class='good'>OK</span><BR />");
		} else {
			// not ok, see importance
			if ($myReq["importance"] == "required") {
				echo ("&nbsp;<span class='bad'>NOT OK -> ERROR</span><BR />");
				$errors++;
			} elseif ($myReq["importance"] == "recommended") {
				echo ("&nbsp;<span class='bad'>NOT OK -> WARNING</span><BR />");
				$warnings++;
			} else {
				echo ("&nbsp;<span class='bad'>NOT OK -> ERROR cause of unknown importance</span><BR />");
				$errors++;
			}
		}
	}
	else
	{
		echo ("checking for ".$myReq["message"]."... Requirement can't be verified.&nbsp;<span class='bad'>NOT OK -> ERROR</span><BR />");
		$errors++;
	}
}

?>			</p><?

if ($errors) {
	$msg = "check NOT OK, please correct the errors shown above ($errors errors)";
?>			<p><?=$msg?></p>
			<p>If you are sure to meet all requirements you can go on without passing the check,<br />
					even if <strong>THIS IS NOT RECOMMENDED</strong> - Step 2: <a href="install.php?action=install">install Phenotype</a></p>	
			<br/>
			<br/>
<?
} else {
	$msg = "check OK";
	if ($warnings) {
		$msg .= ", but there were warnings. Please check the warnings shown above that might lead to malfunctions ($warnings warnings)";
	}
?>			<p><?=$msg?></p>
			<p>Go on with 2: <a href="install.php?action=install">install Phenotype</a></p>	
			<br/>
			<br/>
			
<?
}



}


/*
* the installation process
*
*/
function doActionInstall() {
	global $data;
	global $errors;

	$data = Array();
	$errors = Array();
	$missing = 0;
	$trouble = 0;

	// check the inputs
	if (isset($_REQUEST['installSubmit'])) {
		// get the submitted data

		// start with database
		if (checkDBData()) {
			//echo("everything s fine with the DB");
		} else {
			//echo("problem with the DB");
			$trouble++;
		}

		if (checkPathData()) {
			//echo("path stuff is all fine");
		} else {
			//echo("problems with the path stuff");
			$trouble++;
		}

		if ($trouble) {
			renderInstallForm();
		} else {

			// go further with installation
			echo("<p>now setting up stuff...</p>");

			if (strlen($data['db_truncate'])) {
				// init the DB
				initDB();
			}
			writeConfigFile();

			copyIndexPHP();
			
			if (cget("demo")) {
				installDemoPackage();
			}

			echo("<p>done</p><br/><br/>");

			?>
			<form method="post" action="install.php">
			<input type="hidden" name="action" value="removeandlogin"/>
			<table>
			<tr><td class="headline" colspan="2">Remove installer and login to backend</td></tr>
			<tr><td><input type="submit" value="go" /> user: <strong>starter</strong> password: <strong>deleteme</strong></td></tr>
			<tr><td class="headline" colspan="2">Remove installer and login to backend</td></tr>
			</table>
			</form>
			<?
		}

	} else {
		// not submitted yet, initialize form without error messages
		$data['db_host'] = "localhost";
		$data['db_user'] = "";
		$data['db_pass'] = "";
		$data['db_name'] = "";

		$dirname = dirname($_SERVER["SCRIPT_FILENAME"]);
		if (! strlen($dirname)) {
			$erros['basepath'] = "Unfortunately we could not determine the base path of your installation. Please fill in the path values manually.";
		} else {
			$myDirs = explode("/", $dirname);
			array_pop($myDirs);
			array_pop($myDirs);
			$data['basepath'] = implode("/", $myDirs) ."/";
			
			$urlprefix = dirname($_SERVER['PHP_SELF']);
			$myDirs = explode("/", $urlprefix);
			array_pop($myDirs);
			//array_pop($myDirs);
			$urlprefix = implode("/", $myDirs);
			$data['serverurl'] = $urlprefix ."/";
	
	
			$data['fqdn'] = $_SERVER["HTTP_HOST"];
		}

		renderInstallForm();
	}

}


/*
* initializes the DB
*
*/
function initDB() {
	global $data;

	echo("<p>initializing database...</p>");
	$myDB = mysql_connect(dget('db_host'), dget('db_user'), dget('db_pass'));
	mysql_select_db(dget('db_name'), $myDB);

	$sql ="SET NAMES UTF8";
	$rs = mysql_query($sql);

	$sql = "SHOW TABLES";
	$rs = mysql_query($sql);

	if (!$rs) {
		echo "DB Error, could not list tables\n";
		echo 'MySQL Error: ' . mysql_error();
		return;
	}

	while ($row = mysql_fetch_row($rs)) {
		$sql = "DROP TABLE " .$row[0];
		$rs2 = mysql_query($sql);
		if (!$rs2) {
			echo("<p><span class='bad'>Can not remove existing table ' .".$row[0].".</span></p>");
			echo mysql_error();
			return;
		}
		echo ('<p><span class="good">Removing existing table '.$row[0].'.</span>');
	}


	echo("<br/><br/>Now inserting Queries: <br/><br/>");
	$sql = file_get_contents(SQL_DUMP);
	$_sql = explode(";\n",$sql);
	foreach ($_sql AS $sql)
	{
		echo " ok ";
		$rs = mysql_query($sql);
		if (!$rs) {
			echo("<br/><p><span class='bad'>Sorry, the initialization of the database was not successful.</span></p><br/>");
			echo (utf8_decode(nl2br($sql)))."<br/><br/>";
			echo mysql_error();
			return;
		}
	}

	echo("<p><span class='good'>The database has been initialized successful.</span></p>");


}


/*
* writes the config file with the collected and tested data
*
*/
function writeConfigFile() {

	// write the htaccess file if mod_rewrite available
	$modRewriteAvailable = checkForApacheModule("mod_rewrite");
	if ( $modRewriteAvailable ) {
		$templateHTAccess = file(SAMPLE_HTACCESS_FILE);

		echo ("<p>writing htaccess file...</p>");
		$htaccess = "";
		foreach ($templateHTAccess as $line) {
			$exp = '/^RewriteBase .+$/';
			$sub = 'RewriteBase '. dget('serverurl');
			$htaccess .= preg_replace($exp, $sub, $line);
		}
		file_put_contents(HTACCESS_FILE, $htaccess);
	} else {
		echo ("<p>NOT writing htaccess file because mod_rewrite is missing in your apache configuration. Because of this, the phenotype smartURL feature will be disabled. If you like to use this feature, please have a look into the file htaccess_smartURL_sample in the Phenotype root.</p>");
	}
	
	$exps = Array();
	$subs = Array();

	// basepath
	$exps[] = '/^define \("BASEPATH",.+\);/';
	$subs[] = 'define ("BASEPATH","'. dget('basepath') .'");';

	// server url
	$exps[] = '/^define \("SERVERURL",.+\);/';
	$subs[] = 'define ("SERVERURL","'. dget('serverurl') .'");';

	// serverfullurl
	$exps[] = '/^define \("SERVERFULLURL",.+\);/';
	$subs[] = 'define ("SERVERFULLURL","http://'. dget('fqdn') .'". SERVERURL);';

	// db host
	$exps[] = '/^define \("DATABASE_SERVER",.+\);/';
	$subs[] = 'define ("DATABASE_SERVER","'. dget('db_host') .'");';

	// db user
	$exps[] = '/^define \("DATABASE_USER",.+\);/';
	$subs[] = 'define ("DATABASE_USER","'. dget('db_user') .'");';

	// db pass
	$exps[] = '/^define \("DATABASE_PASSWORD",.+\);/';
	$subs[] = 'define ("DATABASE_PASSWORD","'. dget('db_pass') .'");';

	// db name
	$exps[] = '/^define \("DATABASE_NAME",.+\);/';
	$subs[] = 'define ("DATABASE_NAME","'. dget('db_name') .'");';
	
	if (! $modRewriteAvailable) {
		// configure for ids instead of smartURLs
		// db name
		$exps[] = '/^define \("PT_URL_STYLE",.+\);/';
		$subs[] = 'define ("PT_URL_STYLE","idURL");';
	}

	$templateConfig = file(SAMPLE_CONFIG_FILE);

	echo ("<p>writing config file...</p>");
	$config = "";
	foreach ($templateConfig as $line) {
		$config .= preg_replace($exps, $subs, $line);
	}

	file_put_contents(CONFIG_FILE, $config);

	copy (SAMPLE_HOST_CONFIG, HOST_CONFIG);

}


function checkForApacheModule($mod_name) {
	if (function_exists("apache_get_modules")) {
		$myMods = apache_get_modules();
		
		while ($curMod = array_shift($myMods) ) {
			if ($curMod == $mod_name) {
				return true;
			}
		}
	}
	
	return false;
}

function copyIndexPHP()
{
	echo ("<p>writing index.php...</p>");
	copy (INDEXPHP_WORKING, INDEXPHP_DEST);
	@chown (INDEXPHP_DEST, 0775);
}

function removeInstaller() {
	$file= ($_SERVER["SCRIPT_FILENAME"]);
	if ($file=="")
	{
		echo "The installer could not be removed from your installation. This is strictly recommended for a production system. Please do this manually!";
		exit();
	}
	$path= dirname($file);
	removeDirComplete($path,0,0);
}

/*
*
* @return: boolean (if data is ok or not)
*
*/
function checkPathData() {
	global $data;
	global $errors;

	$missing = 0;
	$trouble = 0;

	if (isset($_REQUEST['basepath']) && strlen($_REQUEST['basepath'])) {
		$data['basepath'] = $_REQUEST['basepath'];

		// add slash at end if needed
		if (substr($data['basepath'], -1, 1) != "/") {
			$data['basepath'] .= "/";
		}

		if (!file_exists($data['basepath'])) {
			$errors['basepath'] = "base path doesn't exist.";
			$trouble++;
		} elseif (!is_readable($data['basepath'])) {
			$errors['basepath'] = "no read permissions for base path";
			$trouble++;
		}
	} else {
		$data['basepath'] = "";
		$errors['basepath'] = "no base path entered";
		$missing++;
	}

	if (isset($_REQUEST['serverurl']) && strlen($_REQUEST['serverurl'])) {
		$data['serverurl'] = $_REQUEST['serverurl'];
		if (substr($data['serverurl'], 0, 1) != "/") {
			$errors['serverurl'] = "server URL must begin with / ";
			$trouble++;
		}

	} else {
		$data['serverurl'] = "";
		$errors['serverurl'] = "no server URL entered. If Phenotype is used directly as the document root please enter /";
		$missing++;
	}

	if (isset($_REQUEST['fqdn']) && strlen($_REQUEST['fqdn'])) {
		$data['fqdn'] = $_REQUEST['fqdn'];
		$exp ="^([a-z0-9._-]+)+$";
		if (! eregi($exp, $data['fqdn'])) {
			$errors['fqdn'] = "not a valid hostname";
			$trouble++;
		}
	} else {
		$data['fqdn'] = "";
		$errors['fqdn'] = "no hostname given";
		$missing++;
	}

	if ($trouble || $missing) {
		return(false);
	} else {
		return (true);
	}
}


/*
*
* @return: boolean (if data is ok or not)
*
*/
function checkDBData() {
	global $data;
	global $errors;

	$missing = 0;
	$trouble = 0;

	// host
	if (isset($_REQUEST['db_host']) && strlen($_REQUEST['db_host'])) {
		$data['db_host'] = $_REQUEST['db_host'];
	} else {
		$data['db_host'] = "localhost";
		$errors['db_host'] = "no db server given";
		$missing++;
	}

	// user
	if (isset($_REQUEST['db_user']) && strlen($_REQUEST['db_user'])) {
		$data['db_user'] = $_REQUEST['db_user'];
	} else {
		$data['db_user'] = "";
		$errors['db_user'] = "no db user given";
		$missing++;
	}

	// password
	if (isset($_REQUEST['db_pass'])) {
		$data['db_pass'] = $_REQUEST['db_pass'];
	} else {
		$data['db_pass'] = "";
		$errors['db_pass'] = "no password entered";
		$missing++;
	}

	// db-name
	if (isset($_REQUEST['db_name']) && strlen($_REQUEST['db_name'])) {
		$data['db_name'] = $_REQUEST['db_name'];
	} else {
		$data['db_name'] = "";
		$errors['db_name'] = "no database entered";
		$missing++;
	}
	if (isset($_REQUEST['db_truncate']) && strlen($_REQUEST['db_truncate'])) {
		$data['db_truncate'] = " checked";
	} else {
		$data['db_truncate'] = "";
	}

	if (! $missing) {
		// try the db data
		if (! $myDB = mysql_connect(dget('db_host'), dget('db_user'), dget('db_pass'))) {
			$errors['db'] = "db connection failed, please check the account data";
			$trouble++;
		} else {
			// connection established
			if (!mysql_select_db(dget('db_name'), $myDB)) {
				$errors['db_name'] = "<br/>The given database can't be selected.<br/>You have to create the database (manually) prior to this installation step.";
				$trouble++;
			}
			else
			{
				// Connection o.k.
				$sql = "SELECT VERSION()";
				$rs = mysql_query($sql);
				$row = mysql_fetch_array($rs);
				$v = (int)$row[0];
				if ($v<4)
				{
					$errors['db_name'] = "<br/>This version of MySQL is too old: " . $row[0] . " Minimum 4.x required.";
					$trouble++;
				}
				else
				{
					$sql = "CREATE TEMPORARY TABLE `temptest` (`test` INT NOT NULL);";
					$rs1 = mysql_query($sql);
					$sql = "DROP TEMPORARY TABLE temptest;";
					$rs2 = mysql_query($sql);
					if (!$rs1 OR !$rs2)
					{
						$errors['db_name'] = "<br/>The db user has not the privilege to create temporary tables.";
						$trouble++;
					}
				}
			}
		}

		if ($trouble) {
			return(false);
		} else {
			return (true);
		}
	} else {
		return(false);
	}
}


function isSettingOK($setting)
{
	$trouble = false;
	switch ($setting)
	{
		case "memory_limit":
			$m = (int) ini_get("memory_limit");
			if ($m!=0 AND $m<16){$trouble=true;}
			break;
		case "register_globals":
			$g = (int) ini_get('register_globals');
			if ($g==1){$trouble=true;}
			break;
		case "safe_mode":
			$g = (int) ini_get('safe_mode');
			if ($g==1){$trouble=true;}
			break;
	}
	if ($trouble) {
		return(false);
	} else {
		return (true);
	}
}

/*
* display install form
*
*/
function renderInstallForm() {

?>
	<form action="install.php" method="get">
	<input type="hidden" name="installSubmit" value="1" />
	<input type="hidden" name="action" value="install" />
	<input type="hidden" name="checkbox" value="1" />
	<table>
		<tr><td class="headline" colspan="2">Database Setup (ATTENTION, this database will be truncated if necessary!)</td></tr>
		<tr><td><br/></td></tr>
		<tr><td class="key">DB Host</td><td class="value"><input type="text" name="db_host" value="<?=dget('db_host')?>" />&nbsp;<span class="bad"><?=eget('db_host')?></span></td></tr>
		<tr><td class="key">DB User</td><td class="value"><input type="text" name="db_user" value="<?=dget('db_user')?>" />&nbsp;<span class="bad"><?=eget('db_user')?></span></td></tr>
		<tr><td class="key">DB Passwort</td><td class="value"><input type="text" name="db_pass" value="<?=dget('db_pass')?>" />&nbsp;<span class="bad"><?=eget('db_pass')?></span></td></tr>
		<tr><td class="key">DB Name</td><td class="value"><input type="text" name="db_name" value="<?=dget('db_name')?>" />&nbsp;<span class="bad"><?=eget('db_name')?></span></td></tr>
		<tr><td class="key">truncate DB and initialize?*</td><td class="value"><input type="checkbox" name="db_truncate" value="1" <?=cget("db_truncate")?>/>&nbsp;<br/></td></tr>
		<tr><td><br/></td></tr>
		<tr><td class="headline" colspan="2">The following line usually dont have to be customized!</td></tr>
<?
if (strlen(eget('db'))) {
?>
		<tr><td class="headline" colspan="2"><span class="bad"><?=eget('db')?></span></td></tr>
<?
}
?>
		<tr><td><br/></td></tr>
		<tr><td class="key">Phenotype Basepath</td><td class="value"><input type="text" name="basepath" value="<?=dget('basepath')?>"  size="50"/>&nbsp;<span class="bad"><?=eget('basepath')?></span></td></tr>
		<tr><td class="key">Phenotype BaseURL**</td><td class="value"><input type="text" name="serverurl" value="<?=dget('serverurl')?>" size="50"/>&nbsp;<span class="bad"><?=eget('serverurl')?></span></td></tr>
		<tr><td class="key">Webserver Hostname (FQDN)</td><td class="value"><input type="text" name="fqdn" value="<?=dget('fqdn')?>" size="50" />&nbsp;<span class="bad"><?=eget('fqdn')?></span></td></tr>
		<tr><td><br/></td></tr>
		<tr><td class="key"><img src="img/demo.jpg" style="border: 1px solid blue"/></td><td class="value"><input type="checkbox" name="demo" value="1" <?=cget("demo")?>/> Install Demo Package?</td></tr>
		<tr><td class="headline" colspan="2"><input type="submit" value="go" /></td></tr>
	</table>
	<br/><br/>* If you dont check the box the installer will only save the account data to access the db, the db itself wont be modified.
	<br/><br/>** Is always the relative path from the webserver docroot to the htdocs folder of Phenotype. Usually, you should configure your Webserver to use the Phenotype htdocs folder as docroot, then you simply have to enter / here.
	</form>
<?
}


function installDemoPackage()
{
	
	global $myDB;
	global $myPT;
	global $myApp;
	global $myAdm;
	global $myRequest;
	
	require (CONFIG_FILE);
	$myAdm = new PhenotypeAdmin();
	require (PACKAGEPATH."1200_PT_Demo/PhenotypePackage.class.php");
	$myPak = new PhenotypePackage();
	$myPT->startBuffer();
	$myPak->globalInstallStructure(0);
	$html = $myPT->stopBuffer();
	?>
	Installation of Demo package (Structure)<br/>
	<table width="600">
	<tr><td class="headline" colspan="2"><pre><?=$html?></pre></td></tr>
	</table><br/>
	<?	
	$myPT->startBuffer();
	$myPak->globalInstallData();
	$html = $myPT->stopBuffer();
	?>
	Installation of Demo package (Structure)<br/>
	<table width="600">
	<tr><td class="headline" colspan="2"><pre><?=$html?></pre></td></tr>
	</table>	
	<?
}

function removeDirComplete ($dir,$keep=0,$debug=0)
{
	if ($fp= @opendir($dir))
	{
		while (($file = readdir($fp)) !== false)
		{
			if (($file == ".") || ($file == ".."))
			{
				continue;
			}
			if (is_dir($dir . '/' . $file))
			{
				removeDirComplete($dir . '/' . $file,0,$debug);
			}
			else
			{
				if ($debug==1)
				{
					echo ($dir . '/' . $file);
				}
				else
				{
					unlink($dir . '/' . $file);
				}
			}
		}
		@closedir($fp);
		if ($keep==0 AND $debug==0){rmdir ($dir);}
	}
}

/*
* get value from _REQUEST
*/
function rget($key) {
	if (isset($_REQUEST["$key"])) {
		return ($_REQUEST["$key"]);
	} else {
		return ("");
	}
}

/*
* get value from $data
*/
function dget($key) {
	global $data;
	if (isset($data["$key"])) {
		return ($data["$key"]);
	} else {
		return ("");
	}
}


function cget($key)
{
	if ($_REQUEST[$key]==1)
	{
			return "checked";
	}
	if ($_REQUEST["checkbox"]!="1")
	{
		return "checked";
	}
}

/*
* get value from $errors
*/
function eget($key) {
	global $errors;
	if (isset($errors["$key"])) {
		return ($errors["$key"]);
	} else {
		return ("");
	}
}




// get submitted data for process controlling
if (isset($_REQUEST["action"])) {
	$action = $_REQUEST["action"];
} else {
	$action = "overview";
}

if ($action=="removeandlogin")
{
	removeInstaller();
	Header("Location: ../_phenotype/admin/index.php");
	exit();
}

// do the page
pageHeader();

doAction($action);

pageFooter();