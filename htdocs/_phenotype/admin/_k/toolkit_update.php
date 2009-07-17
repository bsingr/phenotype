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
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
$myPT->clearCache();
?>
<?php
$id = $_REQUEST["id"];

$sql = "DELETE FROM component_componentgroup WHERE cog_id = " . $id;
$myDB->query($sql);

if (isset($_REQUEST["delete"]))
{
	$myAdm = new PhenotypeAdmin();
	$myAdm->cfg_removeComponentgroup($id);

	$url = "toolkit.php";
	Header ("Location:" . $url."?".SID);
	exit();
}



$mySQL = new SQLBuilder();

// BEZEICHNUNG
$mySQL->addField("cog_bez",$_REQUEST["bez"]);
$mySQL->addField("cog_description",$_REQUEST["description"]);

$sql= $mySQL->update("componentgroup","cog_id=".$id);

$myDB->query($sql);



$sql = "SELECT * FROM component";
$rs = $myDB->query ($sql);
while ($row = mysql_fetch_array($rs))
{
	$fname = "com_" . $row["com_id"];
	if (isset($_REQUEST[$fname]))
	{
		$mySQL = new SQLBuilder();
		$mySQL->addField("cog_id",$id,DB_NUMBER);
		$mySQL->addField("com_id",$row["com_id"],DB_NUMBER);
		$sql = $mySQL->insert("component_componentgroup");
		$myDB->query ($sql);
	}
}

$myPT->customizeToolkit($id);
$url = "toolkit_edit.php?id=" . $id;
Header ("Location:" . $url."&".SID);
?>
