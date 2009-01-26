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
?>
<?php
require("_config.inc.php");
require("_session.inc.php");
if (PT_CONFIGMODE!=1){exit();}
$myPT->loadTMX("Config");

?>
<?php
if (!$mySUser->checkRight("superuser"))
{
	exit();
}
$myPT->clearCache();
?>
<?php
$myAdm = new PhenotypeAdmin();
$id =$myRequest->get("id");

$myMgr = new PhenotypePackageManager();
$myMgr->selectPackage($id);
$myPak = $myMgr->getPackage();


$type = $myRequest->get("type");
$filenr = $myRequest->getI("filenr");
$count = $myRequest->getI("count");


switch ($type)
{

	case "users":
		$myPak->ajaxInstall("users",$filenr,$count);
		break;
	case "media":
		$myPak->ajaxInstall("media",$filenr,$count);
		break;
	case "pages":
		$myPak->ajaxInstall("pages",$filenr,$count);
		break;
	case "content":
		$myPak->ajaxInstall("content",$filenr,$count);
		break;
	case "tickets":
		$myPak->ajaxInstall("tickets",$filenr,$count);
		break;

	case "ajax":
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>Phenotype <?php echo $myPT->version ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="task.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		body {
			margin-top: 2px;
			margin-left: 2px;
			margin-bottom: 2px;
		}
		</style>
		</head>
		<body>
		<table width="490" cellpadding="0" cellspacing="0" border="0">
		</tr>
		<td class="formarea" width="490">
		<strong><?php echo localeH("Users");?></strong><br/>
		<iframe src="package_install2.php?type=users&id=<?php echo $id ?>" width="495" height="100" frameborder="0"></iframe>
		<br/><br/>
		<strong><?php echo localeH("Media");?></strong><br/>
		<iframe src="package_install2.php?type=media&id=<?php echo $id ?>" width="495" height="100" frameborder="0"></iframe>
		<br/><br/>
		<strong><?php echo localeH("Pages");?></strong><br/>
		<iframe src="package_install2.php?type=pages&id=<?php echo $id ?>" width="495" height="100" frameborder="0"></iframe>
		<br/><br/>
		<strong><?php echo localeH("Content");?></strong><br/>
		<iframe src="package_install2.php?type=content&id=<?php echo $id ?>" width="495" height="100" frameborder="0"></iframe>
		<br/><br/>
		<strong><?php echo localeH("Tickets");?></strong><br/>
		<iframe src="package_install2.php?type=tickets&id=<?php echo $id ?>" width="495" height="100" frameborder="0"></iframe>
		<br/>
		</td></tr>
		</table>
		</body>
		</html>
		<?php

		break;
	case "":
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>Phenotype <?php echo $myPT->version ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="task.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		body {
			margin-top: 2px;
			margin-left: 2px;
			margin-bottom: 2px;
		}
		</style>
		</head>
		<body>
		<table width="490" cellpadding="0" cellspacing="0" border="0">
		</tr>
		<td class="formarea" width="490">
		<?php
		// Alte Methode alles auf einen Rutsch zu installieren
		$myMgr->globalInstallData();
		?>
		<br/>
		</td></tr>
		</table>
		</body>
		</html>
		<?php
		break;

}

?>























