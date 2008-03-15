<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
  $sql = "DELETE FROM user_ticketsubject WHERE sbj_id = " . $id;
  $myDB->query($sql);      
  
  $sql = "DELETE FROM ticket WHERE sbj_id = " . $id;
  $myDB->query($sql);      
  
  $sql = "DELETE FROM ticketsubject WHERE sbj_id = " . $id;
  $myDB->query($sql);      
  
   
  $url = "admin_subject.php";
  Header ("Location:" . $url."?".SID);
  exit();
  
}


$mySQL = new SQLBuilder();

$mySQL->addField("sbj_bez",$_REQUEST["bez"]);
$mySQL->addField("sbj_description",$_REQUEST["description"]);

$sql = $mySQL->update("ticketsubject","sbj_id =" . $id);
$myDB->query($sql);

$url = "admin_subject_edit.php?id=" . $id . "&b=1";
Header ("Location:" . $url."&".SID);
?>
