<?php
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
?>
<?php
require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Admin");
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
$myPT->clearCache();
?>
<?php
$id = $myRequest->getI("id");

$mySQL = new SQLBuilder();

$_rechte = Array();

// Elementarrechte

$_element = Array();
//$_element["Redaktion / Seiten - Allgemein"]="page";
$_element["Redaktion / Seiten - Erzeugen und Konfigurieren"]="pageconfig";
$_element["Redaktion / Seiten - KEINE Bausteine auswählen/verändern/löschen"]="pagenocomponent";
$_element["Redaktion / Seiten - Statistik"]="pagestatistic";
$_element["Redaktion / Content"]="content";
$_element["Redaktion / Media"]="mediabase";
$_element["Analysemodus"]="analyse";

$_element["Aufgaben"]="task";

$_element["Adminmodus"]="admin";
$_element["Rollback"]="rollback";

$html = "";
foreach ($_element AS $key => $val)
{
	$recht = "elm_" . $val;
	if (isset($_REQUEST[$recht]))
	{
		$_rechte[$recht]=1;
	}
}

// Content-Objekte
$contentzugriff=0;
$sql = "SELECT * FROM content";
$rs = $myDB->query($sql);
while ($row_content = mysql_fetch_array($rs))
{
	if (isset($_REQUEST["con_" . $row_content["con_id"]]))
	{
		if ($_REQUEST["con_" . $row_content["con_id"]] ==1)
		{
			$_rechte["con_" . $row_content["con_id"]]=1;
			$contentzugriff=1;
		}
	}
}

if ($contentzugriff==1){$_rechte["elm_redaktion"]=1;}else{$_rechte["elm_redaktion"]=0;}
if ($contentzugriff==1){$_rechte["elm_content"]=1;}else{$_rechte["elm_content"]=0;}


// Mediagruppen
$sql = "SELECT * FROM mediagroup";
$rs = $myDB->query($sql);
$mediazugriff=0;
while ($row_grp = mysql_fetch_array($rs))
{
	$fname_access = "access_mediagrp_" . $row_grp["grp_id"];
	if ($myRequest->check($fname_access))
	{

		if ($myRequest->get($fname_access) ==1)
		{
			$_rechte[$fname_access]=1;
			$mediazugriff=1;
		}
		else
		{
			$_rechte[$fname_access]=0;
		}
	}
}
if ($mediazugriff==1){$_rechte["elm_mediabase"]=1;}

// Extras
$extraszugriff=0;
$sql = "SELECT * FROM extra";
$rs = $myDB->query($sql);
while ($row_extra = mysql_fetch_array($rs))
{
	if (isset($_REQUEST["ext_" . $row_extra["ext_id"]]))
	{
		if ($_REQUEST["ext_" . $row_extra["ext_id"]] ==1)
		{
			$_rechte["ext_" . $row_extra["ext_id"]]=1;
			$extraszugriff=1;
		}
	}
}
if ($extraszugriff==1){$_rechte["elm_extras"]=1;}else{$_rechte["elm_extras"]=0;}


// Aufgaben

$sql = "SELECT * FROM ticketsubject";
$rs = $myDB->query($sql);
while ($row_subject = mysql_fetch_array($rs))
{
	if (isset($_REQUEST["sbj_" . $row_subject["sbj_id"]]))
	{
		if ($_REQUEST["sbj_" . $row_subject["sbj_id"]] ==1)
		{
			$_rechte["sbj_" . $row_subject["sbj_id"]]=1;
			$_rechte["elm_task"]=1;
		}
	}
}


// Seitengruppen
$pagezugriff=0;
$sql = "SELECT * FROM pagegroup";
$rs = $myDB->query($sql);
while ($row_grp = mysql_fetch_array($rs))
{
	$fname_access = "access_grp_" . $row_grp["grp_id"];
	$fname_pag_id = "pag_id_grp_" . $row_grp["grp_id"];
	if (isset($_REQUEST[$fname_access]))
	{

		if ($_REQUEST[$fname_access] ==1)
		{
			$_rechte[$fname_access]=1;
			$_rechte[$fname_pag_id]=$_REQUEST[$fname_pag_id];
			$pagezugriff=1;
		}
		else
		{
			$_rechte[$fname_access]=0;
			$_rechte[$fname_pag_id]=0;
		}
	}
}
// Bug?
$_rechte["elm_page"]=0; // Macht ohne Seitengruppen keinen Sinn
if ($pagezugriff==1){$_rechte["elm_redaktion"]=1;}
if ($pagezugriff==1){$_rechte["elm_page"]=1;}


if (isset($_REQUEST["delete"]))
{
	$sql = "DELETE FROM role WHERE rol_id = " .$id;
	$myDB->query($sql);
	// Jetzt die Rechte aller User, die diese Rolle haben neu builden

	$sql = "SELECT * FROM user";
	$rs = $myDB->query($sql);
	$myCUser = new PhenotypeUser();
	while ($row = mysql_fetch_array($rs))
	{
		$myCUser->init($row);
		if ($myCUser->checkRight("rol_".$id))
		{
			$myCUser->buildRights();
		}
	}

	$url = "admin_roles.php";
	Header ("Location:" . $url."?".SID);
	exit();

}

$mySQL = new SQLBuilder();
$mySQL->addField("rol_rights",serialize($_rechte));
$mySQL->addField("rol_bez",$_REQUEST["bez"]);
$mySQL->addField("rol_description",$_REQUEST["description"]);
$sql = $mySQL->update("role","rol_id=" . $_REQUEST["id"]);
$myDB->query($sql);

// Jetzt die Rechte aller User, die diese Rolle haben neu builden

$sql = "SELECT * FROM user";
$rs = $myDB->query($sql);
$myCUser = new PhenotypeUser();
while ($row = mysql_fetch_array($rs))
{
	$myCUser->init($row);
	if ($myCUser->checkRight("rol_".$id))
	{
		$myCUser->buildRights();
	}
}

$url = "admin_role_edit.php?id=" . $id . "&b=0";
Header ("Location:" . $url."&".SID);
?>
