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

$myPT->clearCache();
?>
<?php
$id = $myRequest->getI("id");
$status = $myRequest->get("status");

$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout

$mySQL = new SQLBuilder();
if ($status == "online")
{
  $mySQL->addField("pag_status",1,DB_NUMBER);
}
else
{
  $mySQL->addField("pag_status",0,DB_NUMBER);
}


  // Update der Userinfo

  $mySQL->addField("pag_date",time(),DB_NUMBER);
  $mySQL->addField("usr_id",$_SESSION["usr_id"]);

  // Update der Cachezeiten

  $mySQL->addField("pag_nextbuild1",0,DB_NUMBER);
  $mySQL->addField("pag_nextbuild2",0,DB_NUMBER);
  $mySQL->addField("pag_nextbuild3",0,DB_NUMBER);
  $mySQL->addField("pag_nextbuild4",0,DB_NUMBER);
  $mySQL->addField("pag_nextbuild5",0,DB_NUMBER);
  $mySQL->addField("pag_nextbuild6",0,DB_NUMBER);

  $sql = $mySQL->update("page","pag_id=".$id);
  $myDB->query($sql);


$url = "start.php";
Header ("Location:" . $url);
exit();


?>
