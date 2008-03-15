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
$mySQL = new SQLBuilder();
$mySQL->addField("inc_bez","Neues Include");
$mySQL->addField("inc_rubrik",$_REQUEST["r"]);

// ID unter 1000 ermitteln
$sql = "SELECT MAX(inc_id) AS ID FROM include WHERE inc_id<1000";
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
if ($row["ID"]<999)
{
	$mySQL->addField("inc_id",$row["ID"]+1,DB_NUMBER);
}
// -- ID unter 1000

$sql = $mySQL->insert("include");
$myDB->query($sql);
$id = mysql_insert_id();

$mySmarty = new PhenotypeSmarty();

	
$mySmarty->template_dir = SYSTEMPATH  . "templates/";
$mySmarty->compile_dir = SMARTYCOMPILEPATH;	
$mySmarty->assign("id", $id);

$html = $mySmarty->fetch("include_defaultcode.tpl");

$dateiname = APPPATH . "includes/PhenotypeInclude_"  .$id . ".class.php";	

$fp = fopen ($dateiname,"w");
fputs ($fp,$html);
fclose ($fp);
@chmod ($dateiname,UMASK);


$url = "include_edit.php?id=" . $id . "&b=0&r=" . urlencode($_REQUEST["r"]);
Header ("Location:" . $url."&".SID);
?>
