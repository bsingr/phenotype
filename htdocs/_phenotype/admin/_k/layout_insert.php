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
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$mySQL = new SQLBuilder();
$mySQL->addField("lay_bez",locale("New layout"));
$sql = $mySQL->insert("layout");
$myDB->query($sql);
$id = mysql_insert_id();

$mySQL = new SQLBuilder();
$mySQL->addField("lay_blockbez",locale("Block 1"));
$mySQL->addField("lay_id",$id,DB_NUMBER);
$mySQL->addField("lay_blocknr",1,DB_NUMBER);
$mySQL->addField("cog_id",1,DB_NUMBER);
$mySQL->addField("lay_context",1,DB_NUMBER);

$sql = $mySQL->insert("layout_block");
$myDB->query($sql);

$url = "layout_edit.php?id=" . $id . "&b=0";
Header ("Location:" . $url."&".SID);
?>
