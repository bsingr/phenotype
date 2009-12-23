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

require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor_Media");

$fname = "userfile";

$dateiname_original =  $_FILES[$fname]["name"];
$suffix = mb_strtolower(mb_substr($dateiname_original,strrpos($dateiname_original,".")+1));

$myMB = new PhenotypeMediabase();
$grp_id = $myRequest->getI("grp_id");

$myMB->setMediaGroup($grp_id);

$folder1_new = $myMB->rewriteFolder($myRequest->get("folder1_new"));
if ($folder1_new!="")
{
	$folder = $folder1_new;
}
else
{
	$folder= $myRequest->get("folder1");
}
if ($folder==-1)
{
	$folder = "_upload";
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

$msg=0; // no user message

if (!$id) // upload failed
{
	$msg=1;// Inform about upload failure
	$url = "selector_media.php?folder=" . urlencode($folder) . "&type=" . $myRequest->getI("type") . "&sortorder=" . $myRequest->getI("sortorder"). "&cf=" .  $myRequest->getI("cf") . "&x=" .  $myRequest->getI("x") . "&y=" .  $myRequest->getI("y")."&doc=" .  $myRequest->getA("doc",PT_ALPHANUMERICINT.",") ."&p=1&msg=".$msg;
	Header ("Location:" . $url);
	exit();
}

// Additional properties

$mySQL = new SQLBuilder();

if ($myRequest->get("bez")!="")
{
	$mySQL->addField("med_bez",$myRequest->get("bez"));
}

if ($type==MB_IMAGE)
{
	$mySQL->addField("med_alt",$myRequest->get("alt"));
}

$mySQL->addField("med_keywords",$myRequest->get("keywords"));

if ($myRequest->get("comment") !="" AND $iptc !="")
{
	$mySQL->addField("med_comment",$myRequest->get("comment")."\n\n".$iptc);
}
else
{
	$mySQL->addField("med_comment",$myRequest->get("comment").$iptc);
}

$sql = $mySQL->update("media","med_id =" . $id);
$myDB->query($sql);

// size check for images, delete uploads with wrong size or format
$x = $myRequest->getI("x");
$y = $myRequest->getI("y");
if ($type==MB_IMAGE)
{

	if ($x!=0 OR $y!=0)
	{
		$myImg = new PhenotypeImage($id);
		if ($myImg->isLoaded())
		{
			if ($x!=0 AND $myImg->x != $x)
			{
				$myMB->deleteMediaObject($id);
				$msg=2;
			}
			elseif ($y!=0 AND $myImg->y != $y)
			{
				$myMB->deleteMediaObject($id);
				$msg=2;
			}
		}
		else 
		{
			$msg=1;
		}
	}
}
if ($type==MB_DOCUMENT)
{
	if ($x!=0 OR $y!=0)
	{
		$myMB->deleteMediaObject($id);
		$msg=3;
	}
}	
$_docs = explode(",",$myRequest->getA("doc",PT_ALPHANUMERICINT.","));
$_docs = array_filter($_docs);

if (count($_docs)>0)
{

	if (!in_array($suffix,$_docs))
	{
		$myMB->deleteMediaObject($id);
		$msg=4;
	}
}


$url = "selector_media.php?folder=" . urlencode($folder) . "&type=" . $myRequest->getI("type") . "&sortorder=" . $myRequest->getI("sortorder"). "&cf=" .  $myRequest->getI("cf") . "&x=" .  $myRequest->getI("x") . "&y=" .  $myRequest->getI("y")."&doc=" .  $myRequest->getA("doc",PT_ALPHANUMERICINT.",") ."&p=1&msg=".$msg;


Header ("Location:" . $url);
