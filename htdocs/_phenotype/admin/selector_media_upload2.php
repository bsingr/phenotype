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
$fname = "userfile";

$dateiname_original =  $_FILES[$fname]["name"];
$suffix = strtolower(substr($dateiname_original,strrpos($dateiname_original,".")+1));

$myMB = new PhenotypeMediabase();
$grp_id = $myRequest->getI("grp_id");

$myMB->setMediaGroup($grp_id);

    $folder1_new = $myMB->rewriteFolder($myRequest->get("folder1_new"));
	//$folder2_new = $myMB->rewriteFolder($myRequest["folder2_new"]);
	//$folder3_new = $myMB->rewriteFolder($myRequest["folder3_new"]);
    if ($folder1_new!="")
    {
      $folder = $folder1_new;
    }
    else
    {
      $folder= $myRequest->get("folder1");
    }


$type = MB_DOCUMENT;
$_suffix = Array("jpg","gif","jpeg","png");
if (in_array($suffix,$_suffix))
{
  $type = MB_IMAGE;
  if (isset($_REQUEST["documentonly"]))
  {
    $type = MB_DOCUMENT;
  }
}

$iptc="";
if ($type== MB_IMAGE)
{
  $id = $myMB->uploadImage($fname,$folder);
  if ($id)
  {
    $sql = "SELECT med_comment FROM media WHERE med_id = " . $id;
    $rs = $myDB->query($sql);
    $row=mysql_fetch_array($rs);
    $iptc = $row["med_comment"];
  }
}
else
{
  $id = $myMB->uploadDocument($fname,$folder);
}

if (!$id) // Hochladen fehlgeschlagen
{
 $url = "selector_media.php?folder=" . urlencode($folder) . "&type=" . $_REQUEST["type"] . "&sortorder=" . $_REQUEST["sortorder"]. "&cf=" .  $_REQUEST["cf"] . "&x=" .  $_REQUEST["x"] . "&y=" .  $_REQUEST["y"] ."&p=1";
  Header ("Location:" . $url."&".SID);
  exit();
}

// EIGENSCHAFTEN


$mySQL = new SQLBuilder();

if ($_REQUEST["bez"]!="")
{
   $mySQL->addField("med_bez",$_REQUEST["bez"]);
}
if ($type==MB_IMAGE)
{
  $mySQL->addField("med_alt",$_REQUEST["alt"]);
}
$mySQL->addField("med_keywords",$_REQUEST["keywords"]);

if ($_REQUEST["comment"] !="" AND $iptc !="")
{
  $mySQL->addField("med_comment",$_REQUEST["comment"]."\n\n".$iptc);
}
else
{
  $mySQL->addField("med_comment",$_REQUEST["comment"].$iptc);
}

$sql = $mySQL->update("media","med_id =" . $id);
$myDB->query($sql);

 $url = "selector_media.php?folder=" . urlencode($folder) . "&type=" . $_REQUEST["type"] . "&sortorder=" . $_REQUEST["sortorder"]. "&cf=" .  $_REQUEST["cf"] . "&x=" .  $_REQUEST["x"] . "&y=" .  $_REQUEST["y"]."&p=1";


Header ("Location:" . $url."&".SID);
?>
