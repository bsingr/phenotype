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
$myPT->clearCache();
?>
<?php
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];

if (isset($_REQUEST["delete"]))
{
  $myAdm->cfg_removeExtra($id);
 	   
  $url = "config_extras.php";  

  Header ("Location:" . $url."?".SID);
  exit();
  
}


$mySQL = new SQLBuilder();

// Konfiguration
if ($_REQUEST["b"]==0)
{

  $mySQL->addField("ext_bez",$myRequest->get("bez"));
  $mySQL->addField("ext_rubrik",$myRequest->get("rubrik"));
  $mySQL->addField("ext_description",$myRequest->get("description"));  



  $sql = $mySQL->update("extra","ext_id =" . $id);
  $myDB->query($sql);
}

// SKRIPT
if ($_REQUEST["b"]==1)
{
  if ($myAdm->browserOK_HTMLArea())
  {$skript = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));}
  else
  {$skript = $myAdm->decodeRequest_TextArea($myRequest->get("skript"));}

  $dateiname = APPPATH . "extras/PhenotypeExtra_"  .$id . ".class.php";	
					
  $fp = fopen ($dateiname,"w");
  fputs ($fp,$skript);
  fclose ($fp);
  @chmod ($dateiname,UMASK);
}

// TEMPLATES
if ($_REQUEST["b"]==0 OR $_REQUEST["b"]==2)
{
  $sql = "SELECT * FROM extra_template WHERE ext_id = " . $id . " ORDER BY tpl_id";
  $rs = $myDB->query($sql);
  $c= mysql_num_rows($rs);
  $plus = "";$minus = "";
  $anzahl_templates=0;
  while ($row_ttp=mysql_fetch_array($rs))
  {
    $anzahl_templates++;
    $identifier = "ttp_". $row_ttp["tpl_id"]."_";
    $mySQL = new SQLBuilder();
    $mySQL->addField("tpl_bez",$_REQUEST[$identifier . "bez"]);
    $sql = $mySQL->update("extra_template","tpl_id =" . $row_ttp["tpl_id"] . " AND ext_id=".$id);
    $myDB->query($sql);
    
	// Templates nur im Block 3
	if ($_REQUEST["b"]==2)
	{ 
      if ($myAdm->browserOK_HTMLArea())
      {$html = $myAdm->decodeRequest_HTMLArea($myRequest->get($identifier . "template"));}
      else
      {$html = $myAdm->decodeRequest_TextArea($myRequest->get($identifier . "template"));}
  
      $dateiname = $myPT->getTemplateFileName(PT_CFG_EXTRA, $id, $row_ttp["tpl_id"]);
      $fp = fopen ($dateiname,"w");
      fputs ($fp,$html);
      fclose ($fp);
	  @chmod ($dateiname,UMASK);
	}
  
    if (isset($_REQUEST[$identifier . "minus_x"])){$minus = $row_ttp["tpl_id"];}
    if (isset($_REQUEST[$identifier . "plus_x"])){$plus = $row_ttp["tpl_id"];}
  }

  if ($minus !="")
  {
    $sql = "DELETE FROM extra_template WHERE tpl_id = " . $minus;
    $myDB->query($sql);
	$anzahl_templates--;
  }

  if ($plus !="" || isset($_REQUEST["ttp_plus_x"]))
  {
		$sql = "SELECT MAX(tpl_id) AS new_id FROM extra_template WHERE ext_id = $id";
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$newId = $row['new_id'] + 1;
		
    $mySQL = new SQLBuilder();
		$mySQL->addField("tpl_id",$newId,DB_NUMBER);
    $mySQL->addField("ext_id",$id,DB_NUMBER);    
		$mySQL->addField("tpl_bez","TPL_". $newId);    
    $sql = $mySQL->insert("extra_template");
    $myDB->query($sql);  
  }
}

$b = $_REQUEST["b"];

if ($b==2)
{
  if ($anzahl_templates == 0){$b=0;}
}

$url = "extra_edit.php?id=" . $id . "&b=" . $b;
Header ("Location:" . $url."&".SID);


?>
