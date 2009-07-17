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
$myPT->loadTMX("Editor_Pages");
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
top.opener.location = "page_edit.php?id=<?php echo $id ?>&b=99&ver_id=<?php echo $ver_id ?>";
self.close();
</script>