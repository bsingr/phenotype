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
$id = $myRequest->getI("id");
$ver_id = (int)$_REQUEST["ver_id"];

$sql = "DELETE FROM pageversion_autoactivate WHERE ver_id = " . $ver_id;
$myDB->query($sql);
$sql = "DELETE FROM pageversion WHERE ver_id = " . $ver_id;
$myDB->query($sql);

$scriptname = APPPATH . "pagescripts/" .  sprintf("%04.0f", $id) . "_" . sprintf("%04.0f", $ver_id) . ".inc.php";
@unlink ($scriptname);

if ($_REQUEST["ver_id_editing"]!= $ver_id)
{
  $url = "page_edit.php?id=" . $id .  "&b=99&ver_id=" . $_REQUEST["ver_id_editing"];
}
else
{
  $url = "page_edit.php?id=" . $id .  "&b=99";
}  
Header ("Location:" . $url."&".SID);
?>