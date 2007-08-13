<?
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
<?
require("_config.inc.php");
require("_session.inc.php");
if (PT_CONFIGMODE!=1){exit();}
?>
<?
if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<?
$myAdm->header("Konfiguration");
?>
<body>
<?
$myAdm->menu("Konfiguration");
?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Konfiguration","Tools");

$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
?>
<?
$myPT->startBuffer();
?>

<?
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?
$myAdm->mainTable($left,$content);
?>
<?

?>
</body>
</html>
























