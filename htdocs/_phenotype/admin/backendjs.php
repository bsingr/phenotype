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
  exit();
}

if ($_SESSION["status"]!=1)
{
  exit();
}
else
{
  if ($_SESSION["usr_id"]==0)
  {

    exit();
  }
}


if (file_exists(APPPATH."backend/backend.js"))
{
	echo file_get_contents(APPPATH."backend/backend.js");
}