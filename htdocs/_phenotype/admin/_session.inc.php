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

if (PT_BACKEND!=1){exit();}

$myPT->suppressPHPWarnings();

// cookies must be enabled
ini_set("session.use_trans_sid",0);
@session_start();


// Usr-ID bei Richtextfehler retten  
if (@$_SESSION["usr_id"]!=@$_SESSION["usr_id_fallback"])
{
  $_SESSION["usr_id"]=$_SESSION["usr_id_fallback"];
  $_SESSION["status"]=1;
}
  


$uri = $_SERVER["REQUEST_URI"];

if (!isset($_SESSION["status"]))
{
  $url = "nosession.php?uri=".urlencode($uri);
  Header ("Location: " . $url);
  exit();
}

if ($_SESSION["status"]!=1)
{
  $url = "nosession.php?uri=".urlencode($uri);
  Header ("Location: " . $url);
  exit();
}
else
{
  if ($_SESSION["usr_id"]==0)
  {
    $url = "nosession.php?uri=".urlencode($uri);
    Header ("Location: " . $url);
    exit();
  }
}
$mySUser = new PhenotypeUser();
$mySUser->load($_SESSION["usr_id"]);


header('Pragma: no-cache');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache,must-revalidate');
//Header ("Content-Type: text/html;charset=iso-8859-1");
Header ("Content-Type: text/html;charset=".PT_CHARSET);
?>
