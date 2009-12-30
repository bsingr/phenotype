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
if (PT_CONFIGMODE!=1){exit();}
$myPT->loadTMX("Config");
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
$id = (int)$_REQUEST["id"];
$myAdm = new PhenotypeAdmin();

if (isset($_REQUEST["delete"]))
{

	$myAdm->cfg_removeLayout($id);
	$url = "layout.php";
	Header ("Location:" . $url."?".SID);
	exit();

}


$mySQL = new SQLBuilder();

// Konfiguration
if ($_REQUEST["b"]==0)
{
	$mySQL->addField("lay_bez",$_REQUEST["bez"]);
	$mySQL->addField("lay_description",$_REQUEST["description"]);
	$sql = $mySQL->update("layout","lay_id =" . (int)$id);
	$myDB->query($sql);

	// BLOECKE
	$sql = "SELECT * FROM layout_block WHERE lay_id = " . $id . " ORDER BY lay_blocknr";
	$rs = $myDB->query($sql);
	$plus = "";$minus = "";
	while ($row_block=mysql_fetch_array($rs))
	{
		$identifier = $id . "_". $row_block["lay_blocknr"] . "_";
		$mySQL = new SQLBuilder();
		$mySQL->addField("lay_blockbez",$_REQUEST[$identifier . "bez"]);
		$mySQL->addField("lay_context",$_REQUEST[$identifier . "style"]);
		$mySQL->addField("cog_id",$_REQUEST[$identifier . "toolkit"]);
		$sql = $mySQL->update("layout_block","lay_id =" . $id . " AND lay_blocknr=" . $row_block["lay_blocknr"]);
		$myDB->query($sql);
		if (isset($_REQUEST[$identifier . "minus_x"])){$minus = $row_block["lay_blocknr"];}
		if (isset($_REQUEST[$identifier . "plus_x"])){$plus = $row_block["lay_blocknr"];}
	}

	if ($minus !="")
	{
		$sql = "DELETE FROM layout_block WHERE lay_id = " . $id . " AND lay_blocknr = " . $minus;
		$myDB->query($sql);
		$sql = "UPDATE layout_block SET lay_blocknr = lay_blocknr - 1 WHERE lay_id = " . $id . " AND lay_blocknr > " . $minus;
		$myDB->query($sql);
	}
	if ($plus !="")
	{
		$sql = "UPDATE layout_block SET lay_blocknr = lay_blocknr + 1 WHERE lay_id = " . $id . " AND lay_blocknr > " . $plus;
		$myDB->query($sql);
		$mySQL = new SQLBuilder();
		$mySQL->addField("lay_id",$id,DB_NUMBER);
		$mySQL->addField("lay_blockbez","Neuer Block");
		$mySQL->addField("cog_id",1,DB_NUMBER);
		$mySQL->addField("lay_context",1,DB_NUMBER);
		$mySQL->addField("lay_blocknr",$plus+1,DB_NUMBER);
		$sql = $mySQL->insert("layout_block");
		$myDB->query($sql);

	}

	if (isset($_REQUEST["block_plus_x"]))
	{
		// 1. Block
		$mySQL = new SQLBuilder();
		$mySQL->addField("lay_id",$id,DB_NUMBER);
		$mySQL->addField("lay_blockbez","Neuer Block");
		$mySQL->addField("cog_id",1,DB_NUMBER);
		$mySQL->addField("lay_context",1,DB_NUMBER);
		$mySQL->addField("lay_blocknr",1,DB_NUMBER);
		$sql = $mySQL->insert("layout_block");
		$myDB->query($sql);
	}

	// INCLUDES
	$sql = "SELECT * FROM layout_include WHERE lay_id = " . $id . " ORDER BY lay_includenr";
	$rs = $myDB->query($sql);
	$plus = "";$minus = "";
	while ($row_inc=mysql_fetch_array($rs))
	{
		$identifier = $id . "_inc". $row_inc["lay_includenr"] . "_";
		$mySQL = new SQLBuilder();
		$mySQL->addField("inc_id",$_REQUEST[$identifier . "include"],DB_NUMBER);
		$mySQL->addField("lay_includecache",$_REQUEST[$identifier . "cache"]);
		$sql = $mySQL->update("layout_include","lay_id =" . $id . " AND lay_includenr=" . $row_inc["lay_includenr"]);
		$myDB->query($sql);
		if (isset($_REQUEST[$identifier . "minus_x"])){$minus = $row_inc["lay_includenr"];}
		if (isset($_REQUEST[$identifier . "plus_x"])){$plus = $row_inc["lay_includenr"];}
	}

	if ($minus !="")
	{
		$sql = "DELETE FROM layout_include WHERE lay_id = " . $id . " AND lay_includenr = " . $minus;
		$myDB->query($sql);
		$sql = "UPDATE layout_include SET lay_includenr = lay_includenr - 1 WHERE lay_id = " . $id . " AND lay_includenr > " . $minus;
		$myDB->query($sql);
	}

	if ($plus !="")
	{
		$sql = "UPDATE layout_include SET lay_includenr = lay_includenr + 1 WHERE lay_id = " . $id . " AND lay_includenr > " . $plus;
		$myDB->query($sql);
		$mySQL = new SQLBuilder();
		$mySQL->addField("lay_id",$id,DB_NUMBER);
		$mySQL->addField("inc_id",0,DB_NUMBER);
		$mySQL->addField("lay_includenr",$plus+1,DB_NUMBER);
		$mySQL->addField("lay_includecache",1,DB_NUMBER);
		$sql = $mySQL->insert("layout_include");
		$myDB->query($sql);

	}

	if (isset($_REQUEST["include_plus_x"]))
	{
		// 1. Include
		$mySQL = new SQLBuilder();
		$mySQL->addField("lay_id",$id,DB_NUMBER);
		$mySQL->addField("inc_id",0,DB_NUMBER);
		$mySQL->addField("lay_includenr",1,DB_NUMBER);
		$mySQL->addField("lay_includecache",1,DB_NUMBER);
		$sql = $mySQL->insert("layout_include");
		$myDB->query($sql);
	}

	// Seitengruppen

	$sql = "DELETE FROM layout_pagegroup WHERE lay_id = " . $id;
	$myDB->query($sql);
	if ($_REQUEST["allgroups"]!=1)
	{
		$sql = "SELECT * FROM pagegroup ORDER BY grp_bez";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			if ($_REQUEST["grp_" . $row["grp_id"]]==1)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("lay_id",$id,DB_NUMBER);
				$mySQL->addField("grp_id",$row["grp_id"],DB_NUMBER);
				$sql = $mySQL->insert("layout_pagegroup");
				$myDB->query($sql);
			}
		}
	}
}

if ($_REQUEST["b"]==1)
{
	// TEMPLATES
	$html = $myAdm->decodeRequest_HTMLArea($myRequest->get("template_normal"));

	$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "normal");
	$fp = fopen ($dateiname,"w");
	fputs ($fp,$html);
	fclose ($fp);
	@chmod ($dateiname,UMASK);
	//$html = ereg_replace('"',"&quot;",$html);
	//$html = ereg_replace('&nbsp;',"&amp;nbsp;",$html);

	//$mySQL->addField("lay_html_normal",$html);

	$html = $myAdm->decodeRequest_HTMLArea($myRequest->get("template_print"));

	$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "print");
	$fp = fopen ($dateiname,"w");
	fputs ($fp,$html);
	fclose ($fp);
	@chmod ($dateiname,UMASK);

	//$sql = $mySQL->update("layout","lay_id=".$id);
	//$myDB->query($sql);
}


$url = "layout_edit.php?id=" . $id . "&b=" . (int)$_REQUEST["b"];
Header ("Location:" . $url."&".SID);
?>
