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
?>
<?
require("_config.inc.php");
require("_session.inc.php");
if (PT_CONFIGMODE!=1){exit();}
?>
<?
if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
$mySQL = new SQLBuilder();
$mySQL->addField("cog_bez","Neue Bausteingruppe");
// ID unter 1000 ermitteln
$sql = "SELECT MAX(cog_id) AS ID FROM componentgroup WHERE cog_id<1000";
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
if ($row["ID"]<999)
{
	$mySQL->addField("cog_id",$row["ID"]+1,DB_NUMBER);
}
// -- ID unter 1000
$sql = $mySQL->insert("componentgroup");
$myDB->query($sql);
$id = mysql_insert_id();
?>
<?
$url = "toolkit_edit.php?id=" . $id;
Header ("Location:" . $url."&".SID);
?>
