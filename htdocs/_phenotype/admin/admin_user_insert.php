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
$myPT->loadTMX("Extras");
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
$mySQL->addField("usr_vorname","Neuer");
$mySQL->addField("usr_nachname","Benutzer");
$mySQL->addField("usr_status",1,DB_NUMBER);
$mySQL->addField("usr_createdate",time());
$sql = $mySQL->insert("user");
$myDB->query($sql);

$id = mysql_insert_id();

$url = "admin_user_edit.php?id=" . $id . "&b=0";
Header ("Location:" . $url."&".SID);
?>
