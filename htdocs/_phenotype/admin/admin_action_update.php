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
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
	$myAdm = new PhenotypeAdmin();
  $code = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));
 
	$id = $myRequest->getI("id");
	
  $scriptname = "actions/PhenotypeAction_"  .$id . ".class.php";	
  $scriptname = APPPATH . $scriptname;

  $delete=0;
  if (trim($code)=="" OR (isset($_REQUEST["delete"])))
  {
    // Das Skriptfeld ist leer
	@unlink ($scriptname);
 	$delete=1;
  }
  else
  {
    $fp = fopen ($scriptname, "w");
    fputs ($fp,$code);
    fclose ($fp);
	@chmod ($scriptname,UMASK);

  }

if (isset($_REQUEST["delete"]))
{
  @unlink ($scriptname);
  $sql = "DELETE FROM action WHERE act_id = " . $id;
  $myDB->query($sql);      
   
  $url = "admin_actions.php";
  Header ("Location:" . $url."?".SID);
  exit();
  
}


$mySQL = new SQLBuilder();

if (isset($_REQUEST["reset"]))
{
  $mySQL->addField("act_lastrun",0,DB_NUMBER);
  $mySQL->addField("act_nextrun",0,DB_NUMBER);
}

$mySQL->addField("act_bez",$_REQUEST["bez"]);
$mySQL->addField("act_description",$_REQUEST["description"]);
$mySQL->addField("act_status",$_REQUEST["status"]);
$sql = $mySQL->update("action","act_id =" . $id);
$myDB->query($sql);

$url = "admin_action_edit.php?id=" . $id . "&b=1";
Header ("Location:" . $url."&".SID);
?>
