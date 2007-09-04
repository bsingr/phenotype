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
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
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