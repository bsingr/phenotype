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
$myPT->loadTMX("Admin");
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
