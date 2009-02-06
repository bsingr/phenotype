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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

set_time_limit(0);

require ("../_phenotype/system/class/PhenotypeBase.class.php");
require ("../_phenotype/system/class/PhenotypeInstaller.class.php");
require ("../_phenotype/system/class/PhenotypeRequest.class.php");

class PhenotypeRequest extends PhenotypeRequestStandard {};

$myRequest = new PhenotypeRequest();
$myInstaller = new PhenotypeInstaller();


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=iso-8859-1" />
<title>Phenotype Installer</title>
<meta name="generator" content="Phenotype CMS" />
<style type="text/css">
body {
	background-color: #fff;
	font-family: Verdana, Arial;
	font-size: 12px;
}

em {
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
	font-variant: small-caps;
	border-bottom: 1px solid #CFCFCF;
	padding: 0px 1px 0px 1px;
	line-height: 40px;
	letter-spacing: 4px;
}

#main {
	background: #F7F7F7 none repeat scroll 0%;
	border-bottom: 1px solid #CFCFCF;
	border-top: 1px solid #CFCFCF;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
}

#logo {
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	height: 50px;
	text-align: right;
}

#footer {
	font-size: 10px;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	text-align: right;
	height: 50px;
}

#header {
	color: #000;
}

#message {
	color: #f00;
	font-weight: bold;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 10px;
	margin-bottom: 0px;
}

#intro {
	color: #000;
	font-weight: normal;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 10px;
	margin-bottom: 0px;
}



.source {
	font-family: Courier;
	list-style: none;
	font-size: 11px;
	background-color: #fff;
	padding: 7px;

	margin: 5px;
	margin-top: 0px;
	margin-bottom: 0px;
	overflow: auto;
}

.title {
display:block;
width:230px;
float:left;
}

.current {
	background-color: #CFCFCF;
}


.green
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#0F0;
  font-weight: bold;
}

.red
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#F00;
  font-weight: bold;
}


.yellow
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#FB2;
  font-weight: bold;
}

.update
{
margin-top:5px;
margin-bottom:5px;
display:table;
}

.longinput
{
width: 350px;
}


#output {
	font-family: Courier;
	font-size: 11px;
	height: 300px;
	width: auto;
	overflow: auto;
	border: 1px solid #CFCFCF;
	background-color: #fff;
	padding: 8px;
	margin-bottom: 10px;
}
</style>
</head>
<body>
<div id="logo"><a href="http://www.phenotype.de/"><img	src="_phenotype/admin/img/phenotypelogo.gif" alt="Phenotype" style="border:0px"/></a></div>
<div id="main">
<?php if($myInstaller->getStep()==1):?>
<form action="install.php" method="post">
<input type="hidden" name="reload" value="1"/>
<div id="header"><strong>Phenotype Installer - Step 1/2 (Checking requirements, basic configuration ...)</strong>
<?php if ($myInstaller->isReload()):?>
<?php if ($myInstaller->gotErrors()):?>
<div id="message">
Some errors occured. Scroll down for details.<br/><br/> If you're an phenotype expert you may continue with the installation<br/>and fix things afterwards. We do not recommend that.
<?php else:?>
<div id="intro">
<span class="green">Everything seems to be fine so far. Please press "install" button to start installation.</span>
<?php endif?>
<br/><br/>
<input type="submit" value="Install Phenotype" name="btn_install"/>
<?php else:?>
<div id="intro">
<br/>
Welcome to the Phenotype CMS Installer (##!PT_VERSION!## - ##!BUILD_DATE!##)<br/>
<br/>
Please provide your database settings, check wether your environment meets all requirements,<br/> fix noted issues and press "continue".
<br/><br/><br/>
<input type="submit" value="Continue"/>
<?php endif?>
</div>



<em>Database Settings:</em><br />


<ul class="source">

<li ><span class="title">MySQL server:</span><code><span><input type="text" name="database_server" value="<?php echo htmlentities($myInstaller->database_server)?>"/></span></code></li>
<li ><span class="title">MySQL user:</span><code><span><input type="text" name="database_user" value="<?php echo htmlentities($myInstaller->database_user)?>"/></span></code></li>
<li ><span class="title">MySQL password:</span><code><span><input type="text" name="database_password" value="<?php echo htmlentities($myInstaller->database_password)?>"/></span></code></li>
<li ><span class="title">MySQL database:</span><code><span><input type="text" name="database_name" value="<?php echo htmlentities($myInstaller->database_name)?>"/></span></code></li>
<?php if ($myInstaller->checkDB()):?>
<li><span class="title">&nbsp;</span><span class="green update"><?php echo $myInstaller->database_status?></span></li>
<?php else:?>
<li><span class="title">&nbsp;</span><span class="red update"><?php echo $myInstaller->database_status?></span></li>
<?php endif?>
<li><span class="title">&nbsp;</span><span class="update"><input type="submit" value="Update!"/></span></li>
</ul>

<br/>
<br/>

<em>Account Settings:</em><br />


<ul class="source">

<li ><span class="title">Superuser login:</span><code><span><input type="text" name="superuser_login" value="<?php echo htmlentities($myInstaller->superuser_login)?>"/></span></code></li>
<li ><span class="title">Superuser password:</span><code><span><input type="text" name="superuser_password" value="<?php echo htmlentities($myInstaller->superuser_password)?>"/></span></code></li>
<li><span class="title">&nbsp;</span><span class="update"><input type="submit" value="Update!"/></span></li>
</ul>

<br/>
<br/>

<em>Path configuration:</em><br />


<ul class="source">

<li><span class="title">Basepath:</span><code><span><input type="text" name="path_basepath" class="longinput" value="<?php echo htmlentities($myInstaller->path_basepath)?>"/></span></code></li>
<li><span class="title">Base URL:</span><code><span><input type="text" name="path_baseurl" class="longinput" value="<?php echo htmlentities($myInstaller->path_baseurl)?>"/></span></code></li>
<li><span class="title">Hostname:</span><code><span><input type="text" name="path_hostname" class="longinput" value="<?php echo htmlentities($myInstaller->path_hostname)?>"/></span></code></li>
<li><span class="title">Full URL:</span><code><span><?php echo htmlentities($myInstaller->path_fullurl)?></span></code></li>
<?php if ($myInstaller->checkPathes()):?>
<li><span class="title">&nbsp;</span><span class="green update"><?php echo $myInstaller->path_status?></span></li>
<?php else:?>
<li><span class="title">&nbsp;</span><div class="red update" style="display:table"><?php echo $myInstaller->path_status?></></li>
<?php endif?>
<li><span class="title">&nbsp;</span><span class="update"><input type="submit" value="Update!"/></span></li>
</ul>

<br/>
<br/>


<em>PHP Settings:</em><br />


<ul class="source">
<?php foreach ($myInstaller->checkPHP() AS $line):?>
<li ><span class="title">#<?php echo $line["title"]?>:</span><code><span class="<?php echo $line["class"]?>"><?php echo $line["status"]?></span></code></li>
<?php endforeach;?>		
</ul>

<br/>
<br/>

<em>Apache Settings:</em><br />


<ul class="source">
<?php foreach ($myInstaller->checkApache() AS $line):?>
<li ><span class="title">#<?php echo $line["title"]?>:</span><code><span class="<?php echo $line["class"]?>"><?php echo $line["status"]?></span></code></li>
<?php endforeach;?>		
</ul>

<br/>
<br/>

<em>File Permissions:</em><br />

<ul class="source">
<?php foreach ($myInstaller->checkRWPermissions() AS $line):?>
<li ><span class="title"><?php echo $line["title"]?>:</span><code><span class="<?php echo $line["class"]?>"><?php echo $line["status"]?></span></code></li>
<?php endforeach;?>		
</ul>
	
<br/>
<br/>	

<em>Application Configuration:</em><br />


<ul class="source">

<li ><span class="title">Default backend language:</span><code><span><select name="app_backend_language"><?php echo $myInstaller->getOptionsAsHTML("app_backend_language")?></select></span></code></li>
<li ><span class="title">Debug mode:</span><code><span><input type="checkbox" value="1" name="app_debug_mode" <?php echo $myInstaller->getCheckboxSelectionAsHTML("app_debug_mode")?>/>activated</span></code></li>
<li ><span class="title">Frontend session:</span><code><span><input type="checkbox" value="1" name="app_frontend_session" <?php echo $myInstaller->getCheckboxSelectionAsHTML("app_frontend_session")?>/>activated</span></code></li>
<li ><span class="title">Inital Setup:</span><code><span><select name="app_package"><?php echo $myInstaller->getOptionsAsHTML("app_package")?></select></span></code></li>
<li><span class="title">&nbsp;</span><span class="green update">Don't worry, you must not (yet) understand this settings for a successfull install.</span></li>
<li><span class="title">&nbsp;</span><span class="update"><input type="submit" value="Update!"/></span></li>
</ul>

<br/>
<br/>
<?php /* -not yet
<em>Web Access Security (HTTP Authentification):</em><br />

<ul class="source">

<li ><span class="title">Frontend login:</span><code><span><input type="text" name="frontend_login" value="<?php echo htmlentities($myInstaller->frontend_login)?>"/></span></code></li>
<li ><span class="title">Frontend password:</span><code><span><input type="text" name="frontend_password" value="<?php echo htmlentities($myInstaller->frontend_password)?>"/></span></code></li>
<li ><span class="title">Backend login:</span><code><span><input type="text" name="backend_login" value="<?php echo htmlentities($myInstaller->backend_login)?>"/></span></code></li>
<li ><span class="title">Backend password:</span><code><span><input type="text" name="backend_password" value="<?php echo htmlentities($myInstaller->backend_password)?>"/></span></code></li>
<?php if ($myInstaller->checkWebaccess()):?>
<li><span class="title">&nbsp;</span><span class="green update"><?php echo $myInstaller->webaccess_status?></span></li>
<?php else:?>
<li><span class="title">&nbsp;</span><div class="red update" style="display:table"><?php echo $myInstaller->webaccess_status?></></li>
<?php endif?>
<li><span class="title">&nbsp;</span><span class="update"><input type="submit" value="Update!"/></span></li>
</ul>

<br/>
<br/>
*/?>
</div>
</form>
<?php endif?>
<?php if ($myInstaller->getStep()==2):?>
<div id="header"><strong>Phenotype Installer - Step 2/2 (Writing configuration files, importing database ...)</strong>
<div id="intro">
Installation in process ...<br/>
</div>

<em>Database Operations:</em><br />
<ul class="source">
<?php foreach ($myInstaller->installDB() AS $line):?>
<li ><code>#<?php echo $line?></code></li>
<?php endforeach;?>		
<?php if ($myInstaller->gotErrors() AND $myInstaller->installation_status!=""):?>
<li><div class="red update" style="display:table"><?php echo $myInstaller->installation_status?></></li>
<?php endif?>
</ul>


<em>Writing config files:</em><br />
<ul class="source">
<?php foreach ($myInstaller->writeConfigFiles() AS $line):?>
<li ><code>#<?php echo $line?></code></li>
<?php endforeach;?>		
<?php if ($myInstaller->gotErrors() AND $myInstaller->installation_status!=""):?>
<li><div class="red update" style="display:table"><?php echo $myInstaller->installation_status?></></li>
<?php endif?>
</ul>

<em>Installing packages:</em><br />
<ul class="source">
<?php foreach ($myInstaller->installPackages() AS $line):?>
<li ><code>#<?php echo $line?></code></li>
<?php endforeach;?>		
<?php if ($myInstaller->gotErrors() AND $myInstaller->installation_status!=""):?>
<li><div class="red update" style="display:table"><?php echo $myInstaller->installation_status?></></li>
<?php endif?>
</ul>

<em>Finalize installation:</em><br />
<ul class="source">
<?php foreach ($myInstaller->finalizeInstallation() AS $line):?>
<li ><code>#<?php echo $line?></code></li>
<?php endforeach;?>		
<?php if ($myInstaller->gotErrors() AND $myInstaller->installation_status!=""):?>
<li><div class="red update" style="display:table"><?php echo $myInstaller->installation_status?></></li>
<?php endif?>
</ul>
<?php if ($myInstaller->gotErrors()):?>
<div id="message">
Installation failure.<br/><br/>
We are sorry, something went wrong. Please carefully read the given error messages.<br/><br/>
Most probably some of the requirements were not (really) met, especially write permissions and our installer unfortunatetly did not detect this. :(
<br/><br/>
If you retry by reloading and continue to get strange error messages, please delete the whole installation folder and restart from scratch.
<br/><br/>
If you're new to phenotype we recommend to try a local installation prior installing and configuring phenotype in a maybe somehow limited shared hosting environment.<br/>
</div>
<?php else:?>
<div id="outro">
<span class="green">
<br/>
Installation complete.<br/><br/>
You may now login into the <a href="_phenotype/admin">backend</a> or just browse the new installed <a href="index.php">web application</a>.
<br/><br/>
Thank you for using Phenotype.
<br/>
<br/>
</span>
</div>
<?php endif?>
</div>
<?php endif?>
</div>
<div id="footer"><?php echo date('d.m.Y H:i')?></div>
</body>
</html>
	