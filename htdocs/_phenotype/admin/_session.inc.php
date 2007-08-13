<?
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
<?
if (PT_BACKEND!=1){exit();}

// Session auch ohne Cookies ermöglichen
ini_set("session.use_trans_sid",1);
@session_start();


// Usr-ID bei Richtextfehler retten  
if (@$_SESSION["usr_id"]!=@$_SESSION["usr_id_fallback"])
{
  $_SESSION["usr_id"]=$_SESSION["usr_id_fallback"];
  $_SESSION["status"]=1;
}
  

if (!isset($_SESSION["status"]))
{
  $url = "nosession.php";
  Header ("Location: " . $url);
  exit();
}

if ($_SESSION["status"]!=1)
{
  $url = "nosession.php";
  Header ("Location: " . $url);
  exit();
}
else
{
  if ($_SESSION["usr_id"]==0)
  {
    $url = "nosession.php";
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
Header ("Content-Type: text/html;charset=iso-8859-1");

?>