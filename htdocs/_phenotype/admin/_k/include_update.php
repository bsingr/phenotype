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

if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];

if (isset($_REQUEST["delete"]))
{
  $myAdm->cfg_removeInclude($id);

  // :TODO: what should this first sql statement do?
  $sql = "SELECT COUNT (*) AS 'C' FROM include WHERE inc_rubrik = '" . $_REQUEST["r"] ."'";
  $sql = "SELECT COUNT(*) AS C FROM sequence_data WHERE com_id = " . $id;
  $rs_check = $myDB->query($sql);
  $row = mysql_fetch_array($rs_check);
  if ($row["C"]==0)
  {
    $url = "includes.php?r=-1";
  }
  else
  {
    $url = "includes.php?r=" .$_REQUEST["r"];
  }
  Header ("Location:" . $url."&".SID);
  exit();

}


$mySQL = new SQLBuilder();

// Konfiguration
$rubrik = $_REQUEST["rubrik"];
if ($rubrik==""){$rubrik="Neue Rubrik";}
if ($_REQUEST["b"]==0)
{

  $mySQL->addField("inc_bez",$_REQUEST["bez"]);
  $mySQL->addField("inc_description",$_REQUEST["description"]);
  $mySQL->addField("inc_rubrik",$rubrik);

  if (isset($_REQUEST["usage_template"]))
  {$mySQL->addField("inc_usage_layout",1,DB_NUMBER);}
  else
  {$mySQL->addField("inc_usage_layout",0,DB_NUMBER);}

  if (isset($_REQUEST["usage_tool"]))
  {$mySQL->addField("inc_usage_includecomponent",1,DB_NUMBER);}
  else
  {$mySQL->addField("inc_usage_includecomponent",0,DB_NUMBER);}

  if (isset($_REQUEST["usage_page"]))
  {$mySQL->addField("inc_usage_page",1,DB_NUMBER);}
  else
  {$mySQL->addField("inc_usage_page",0,DB_NUMBER);}

  $sql = $mySQL->update("include","inc_id =" . $id);
  $myDB->query($sql);
}

// SKRIPT
if ($_REQUEST["b"]==1)
{
  if ($myAdm->browserOK_HTMLArea())
  {$skript = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));}
  else
  {$skript = $myAdm->decodeRequest_TextArea($myRequest->get("skript"));}

  //$dateiname = APPPATH . "includes/"  .sprintf("%04.0f", $id) . ".inc.php";
  $dateiname = APPPATH . "includes/PhenotypeInclude_". $id . ".class.php";

  $fp = fopen ($dateiname,"w");
  fputs ($fp,$skript);
  fclose ($fp);
  @chmod ($dateiname,UMASK);
}

// TEMPLATES
if ($_REQUEST["b"]==0 OR $_REQUEST["b"]==2)
{
  $sql = "SELECT * FROM include_template WHERE inc_id = " . $id . " ORDER BY tpl_id";
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
    $sql = $mySQL->update("include_template","tpl_id =" . $row_ttp["tpl_id"] . " AND inc_id=". $id);
    $myDB->query($sql);

    // Templates nur im Block 3
    if ($_REQUEST["b"]==2)
    {
      if ($myAdm->browserOK_HTMLArea())
      {$html = $myAdm->decodeRequest_HTMLArea($myRequest->get($identifier . "template"));}
      else
      {$html = $myAdm->decodeRequest_TextArea($myRequest->get($identifier . "template"));}

      $dateiname = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $id, $row_ttp["tpl_id"]);
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
    $sql = "DELETE FROM include_template WHERE tpl_id = " . $minus;
    $myDB->query($sql);
    $anzahl_templates--;
  }

  if ($plus !="" || isset($_REQUEST["ttp_plus_x"]))
  {
    $sql = "SELECT MAX(tpl_id) AS new_id FROM include_template WHERE inc_id = $id";
    $rs = $myDB->query($sql);
    $row = mysql_fetch_array($rs);
    $newId = $row['new_id'] + 1;

    $mySQL = new SQLBuilder();
    $mySQL->addField("tpl_id",$newId,DB_NUMBER);
    $mySQL->addField("inc_id",$id,DB_NUMBER);
    $mySQL->addField("tpl_bez","TPL_". $newId);
    $sql = $mySQL->insert("include_template");
    $myDB->query($sql);
  }
}

$b = $_REQUEST["b"];

if ($b==2)
{
  if ($anzahl_templates == 0){$b=0;}
}

$url = "include_edit.php?id=" . $id . "&b=" . $b . "&r=" . urlencode($rubrik);
Header ("Location:" . $url."&".SID);


?>
