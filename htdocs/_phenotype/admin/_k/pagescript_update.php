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
if (PT_CONFIGMODE!=1){exit();}
?>
<?php
if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$id = $_REQUEST["id"];
$ver_id = $_REQUEST["ver_id"];
$ver_nr = $_REQUEST["ver_nr"];

  $myAdm = new PhenotypeAdmin();
  if ($myAdm->browserOK_HTMLArea())
  {
    $code = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));
  }
  else
  {
    $code = $myAdm->decodeRequest_TextArea($myRequest->get("skript"));
  }
	 $scriptname = APPPATH . "pagescripts/".sprintf("%04d",$id) ."_" .sprintf("%04d", $_REQUEST["ver_id"]) .".inc.php";

  $mySQL = new SQLBuilder();
  $delete=0;
  if (trim($code)=="" OR (isset($_REQUEST["delete"])))
  {
    // Das Skriptfeld ist leer
	unlink ($scriptname);
    $mySQL->addField("pag_exec_script",0);	
	$delete=1;
  }
  else
  {
    $fp = fopen ($scriptname, "w");
    fputs ($fp,$code);
    fclose ($fp);
	@chmod ($scriptname,UMASK);
    $mySQL->addField("pag_exec_script",1);	
  }

  $sql = $mySQL->update("pageversion","pag_id=".$id . " AND ver_id=".$ver_id);
  $myDB->query($sql);  
  

$url = "pagescript_edit.php?id=" . $id . "&ver_nr=" . $ver_nr . "&ver_id=" . $ver_id;
if ($delete==1)
{
  $url = "pagescripts.php";
}  

Header ("Location:" . $url."?".SID);
?>