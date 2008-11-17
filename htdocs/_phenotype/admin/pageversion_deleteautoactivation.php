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
$myPT->loadTMX("Editor_Pages");
?>
<?php
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$id = (int)$_REQUEST["id"];
$ver_id = (int)$_REQUEST["ver_id"];
$auv_id = (int)$_REQUEST["auv_id"];

$sql = "DELETE FROM pageversion_autoactivate WHERE auv_id = " .$auv_id;
$myDB->query($sql);  

$myPage = new PhenotypePage($id);
$myPage->versionCheck();


$url = "page_edit.php?id=" . $id . "&ver_id=" . $_REQUEST["ver_id"]. "&b=99";
Header ("Location:" . $url."&".SID);
?>