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
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
$id = (int)$_REQUEST["id"];
$ver_id = (int)$_REQUEST["ver_id"];
$ver_id2 = (int)$_REQUEST["ver_id_2bactivated"];

// Datum ermitteln
$datum = $myPT->germanDT2Timestamp($_REQUEST["datum"]);
//echo date("d.m.Y H:i",$datum);
  
// Autoaktivierung muss in der Zukunft liegen
if ($datum>time())
{
  $mySQL = new SQLBuilder();
  $mySQL->addField("pag_id",$id,DB_NUMBER);   
  $mySQL->addField("ver_id",$ver_id2,DB_NUMBER);     
  $mySQL->addField("ver_date",$datum,DB_NUMBER);   
  $sql = $mySQL->insert("pageversion_autoactivate");	
  $myDB->query($sql);  
  

}
$myPage = new PhenotypePage($id);
$myPage->versionCheck();






//$url = "page_edit.php?id=" . $id . "&ver_id=" . $_REQUEST["ver_id"]. "&b=99";
//Header ("Location:" . $url."&".SID);

?>
<script language="javascript">
top.opener.location = "page_edit.php?id=<?=$id?>&b=99&ver_id=<?=$ver_id?>";
self.close();
</script>