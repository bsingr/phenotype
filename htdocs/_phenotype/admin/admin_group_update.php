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
$myAdm = new PhenotypeAdmin();
$id = $myRequest->getI("id");

if (isset($_REQUEST["delete"]))
{
  $sql = "DELETE FROM pagegroup WHERE grp_id = " . $id;
  $myDB->query($sql);      
  
  $url = "admin_groups.php";
  Header ("Location:" . $url."?".SID);
  exit();
  
}


$mySQL = new SQLBuilder();

$mySQL->addField("grp_bez",$_REQUEST["bez"]);
$mySQL->addField("grp_description",$_REQUEST["description"]);
if (isset($_REQUEST["statistic"]))
{
  $mySQL->addField("grp_statistic",1,DB_NUMBER);
}
else
{
  $mySQL->addField("grp_statistic",0,DB_NUMBER);
}
if (isset($_REQUEST["multilanguage"]))
{
  $mySQL->addField("grp_multilanguage",1,DB_NUMBER);
}
else
{
  $mySQL->addField("grp_multilanguage",0,DB_NUMBER);
}
$sql = $mySQL->update("pagegroup","grp_id =" . $id);
$myDB->query($sql);

$url = "admin_group_edit.php?id=" . $id . "&b=1";
Header ("Location:" . $url."&".SID);
?>
