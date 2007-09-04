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
if (PT_CONFIGMODE!=1){exit();}
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$mySQL = new SQLBuilder();
$mySQL->addField("lay_bez","Neues Layout");
$sql = $mySQL->insert("layout");
$myDB->query($sql);
$id = mysql_insert_id();

$mySQL = new SQLBuilder();
$mySQL->addField("lay_blockbez","Block 1");
$mySQL->addField("lay_id",$id,DB_NUMBER);
$mySQL->addField("lay_blocknr",1,DB_NUMBER);
$mySQL->addField("cog_id",1,DB_NUMBER);
$mySQL->addField("lay_context",1,DB_NUMBER);

$sql = $mySQL->insert("layout_block");
$myDB->query($sql);

$url = "layout_edit.php?id=" . $id . "&b=0";
Header ("Location:" . $url."&".SID);
?>
