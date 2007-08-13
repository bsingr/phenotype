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
require("_config.inc.php");
require("_session.inc.php");
?>
<?
// ToDO: richtiges Recht?
if (!$mySUser->checkRight("elm_pageconfig"))
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
$myAdm->header("Redaktion");
?>
<body>
<?
$myAdm->menu("Redaktion");
?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Redaktion","Seiten");
$myAdm->explorer_set("pag_id",-1);
$myAdm->explorer_set("grp_id",$_REQUEST["grp_id"]);
$myAdm->explorer_draw();
?>
<?
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
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
























