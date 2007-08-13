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
