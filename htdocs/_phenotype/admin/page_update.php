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
$id = $myRequest->getI("id");
$ver_id = (int)$_REQUEST["ver_id"];
$block_nr = (int)$_REQUEST["block_nr"];
$context=0;
if ($block_nr>0 AND $block_nr<77)
{
	$sql = "SELECT lay_context FROM layout_block WHERE lay_blocknr=".$block_nr;
	$rs = $myDB->query($sql);
	if (mysql_num_rows($rs)==0)
	{
		exit();
	}
	$row=mysql_fetch_array($rs);
	$context = $row["lay_context"];
}
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
?>
<?



$pos=0;

if ($block_nr==0)
{
	$mySQL = new SQLBuilder();
	$mySQL2 = new SQLBuilder();


	// fuer alle Versionen
	$mySQL->addField("pag_cache",$_REQUEST["cache"],DB_NUMBER);
	if ($myPT->getPref("edit_pages.show_quickfinder")==1)
	{
		if (isset($_REQUEST["usequickfinder"]))
		{
			$mySQL->addField("pag_quickfinder",$_REQUEST["quickfinder"]);
		}
		else
		{
			$mySQL->addField("pag_quickfinder","");
		}
	}

	if (isset($_REQUEST["pag_id_mimikry"]))
	{
		$mySQL->addField("pag_id_mimikry",$_REQUEST["pag_id_mimikry"],DB_NUMBER);
	}else
	{
		$mySQL->addField("pag_id_mimikry",$id,DB_NUMBER);
	}

	$mySQL->addField("pag_searchtext",$myRequest->get("searchtext"));

	// nur fuer die aktuelle Version
	$mySQL2->addField("lay_id",$_REQUEST["template_id"],DB_NUMBER);

	//$mySQL2->addField("inc_id1",$_REQUEST["inc_id1"],DB_NUMBER);
	//$mySQL2->addField("inc_id2",$_REQUEST["inc_id2"],DB_NUMBER);


	// fuer alle Versionen
	$mySQL->addField("pag_bez",$_REQUEST["bez"]);
	$mySQL->addField("pag_titel",$_REQUEST["titel"]);
	$mySQL->addField("pag_alttitel",$_REQUEST["alttitel"]);

	$url = $_REQUEST["url"];

	$url = $myApp->buildRewriteRules($id,$myRequest->get("url"));


	$mySQL->addField("pag_comment",$_REQUEST["comment"]);
	if (isset($_REQUEST["status"]))
	{
		$mySQL->addField("pag_status",1,DB_NUMBER);
	}
	else
	{
		$mySQL->addField("pag_status",0,DB_NUMBER);
	}

	// nur fuer die aktuelle Version
	$mySQL2->addField("ver_bez",$_REQUEST["ver_bez"]);

	$sql = $mySQL->update("page","pag_id=".$id);
	$myDB->query($sql);
	$sql = $mySQL2->update("pageversion","pag_id=".$id . " AND ver_id=".$ver_id);
	$myDB->query($sql);
	//echo $sql;


	// Editierbare Seitenvariablen speichern

	$sql = "SELECT pag_id_top,pag_props_locale FROM page WHERE pag_id = " . $id;
	$myDB->query($sql);
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	$top_id = $row["pag_id_top"];

	$_props_top = Array();
	$_props = Array();
	if ($row["pag_props_locale"]!="")
	{
		$_props = unserialize($row["pag_props_locale"]);
	}
	if ($top_id !=0)
	{
		$sql = "SELECT pag_props_all, pag_props_locale FROM page WHERE pag_id = " .$top_id;
		$myDB->query($sql);
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if ($row["pag_props_all"]!="")
		{
			$_props_top = unserialize($row["pag_props_all"]);
		}
	}
	foreach ($myApp->getEditablePageVars() as $var => $desc)
	{
		$val = $myRequest->get($var);
		if (array_key_exists($var,$_props_top))
		{
			// Unveraendert geerbte Variablen solle nicht gespeichert werden
			$compval = $_props_top[$var];
			if ($compval != $val)
			{
				$_props[$var]= $val;
			}
		}
		else
		{
			$_props[$var]= $val;
		}
	}

	$mySQL->addField("pag_props_locale",serialize($_props));
	$sql = $mySQL->update("page","pag_id=".$id);
	$myDB->query($sql);

	$myPage = new PhenoTypePage($id,$ver_id);
	$myPage->buildProps();

	// -- Editierbare Seitenvariablen speichern




	// Mehrsprachige Seitentitel speichern
	$sql = "SELECT grp_multilanguage FROM page LEFT JOIN pagegroup ON page.grp_id = pagegroup.grp_id WHERE pag_id=" . $id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	if ($row["grp_multilanguage"]==1)
	{
		foreach ($PTC_LANGUAGES AS $k =>$v)
		{
			if ($k<>1)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("pag_titel",$myRequest->get("lng_titel".$k));
				$sql=$mySQL->update("page_language","pag_id=".$id." AND lng_id=".$k);
				$myDB->query($sql);
			}
		}
	}

}
else
{

	$sql = "SELECT * FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " . $ver_id." AND dat_blocknr=" . $block_nr . " AND dat_editbuffer = 1 AND lng_id=".$_SESSION["lng_id"]." AND usr_id=". $_SESSION["usr_id"]." ORDER BY dat_pos";
	$rs = $myDB->query($sql);
	$i=0;
	while ($row = mysql_fetch_array($rs))
	{
		$i++;
		$tname = "PhenotypeComponent_" . $row["com_id"];
		$myComponent = new $tname;
		$myComponent->init($row);
		if (isset($_REQUEST[$row["dat_id"]."_visible"]))
		{
			$myComponent->visible =1;
		}
		else
		{
			$myComponent->visible =0;
		}

		$myComponent->update($context);
		$myComponent->store();
		// Wurde ein Tool geloescht
		if (isset($_REQUEST[$row["dat_id"]."_delete_x"]))
		{
			$myComponent->delete();
			$pos=$i;
			//$del_tool_id = $row["dat_id"];
		}
		// Soll Baustein nach oben verschoben werden
		if (isset($_REQUEST[$row["dat_id"]."_moveup_x"]))
		{
			$myComponent->moveup();
			$pos=$i-1;
		}
		// Soll Baustein nach unten verschoben werden
		if (isset($_REQUEST[$row["dat_id"]."_movedown_x"]))
		{
			$myComponent->movedown();
			$pos=$i+1;
		}
	}

	// Wurde ein neues Tools eingefuegt?
	$new_tool_id = $_REQUEST["newtool_id"];
	if ($new_tool_id !="")
	{
		$tname = "PhenotypeComponent_" . $_REQUEST["newtool_type"];
		$myComponent = new $tname;
		$pos = $myComponent->addNew($id,$ver_id,0,$block_nr,$new_tool_id);
	}
}

if (isset($_REQUEST["save"]))
{

	$sql = "DELETE FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " . $ver_id. " AND lng_id=".$_SESSION["lng_id"]." AND dat_editbuffer=0";
	$myDB->query($sql);
	$table ="temp_" . time();
	$sql = "CREATE TEMPORARY TABLE " . $table . " SELECT * FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " . $ver_id. " AND dat_editbuffer=1 AND lng_id=".$_SESSION["lng_id"]. " AND usr_id=".$_SESSION["usr_id"];
	$rs = $myDB->query($sql);
	$sql = "INSERT INTO sequence_data(dat_id,pag_id,ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id) SELECT dat_id,pag_id,ver_id, 0 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,  ".$_SESSION["lng_id"]." AS lng_id FROM " .$table;
	$rs = $myDB->query($sql);
	$sql = "DROP TABLE " . $table;
	$rs = $myDB->query($sql);

	// Volltext neu bauen
	$sql = "SELECT dat_fullsearch FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " . $ver_id. " AND dat_editbuffer=0";
	$rs = $myDB->query($sql);
	$s="";
	while ($row=mysql_fetch_array($rs))
	{
		$s .= $row["dat_fullsearch"]."|";
	}

	// Den Searchtext anhaengen
	$sql = "SELECT ver_id, pag_titel, pag_alttitel, pag_searchtext FROM page WHERE pag_id=".$id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);

	$s = $row["pag_titel"] . "|" .$row["pag_titel"] . "|" .$row["pag_titel"] . "|" . $row["pag_alttitel"] . "|" . $row["pag_searchtext"] . "|" . $row["pag_searchtext"] . "|" . $s;

	// ist die bearbeitete Version die aktuelle ??
	if ($row["ver_id"]==$ver_id)
	{
		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_fullsearch",$s);
		$sql = $mySQL->update("page","pag_id=".$id);
		$myDB->query($sql);
	}
	$mySQL = new SQLBuilder();
	$mySQL->addField("pag_fullsearch",$s);
	$sql = $mySQL->update("pageversion","pag_id=".$id . " AND ver_id=".$ver_id);
	$myDB->query($sql);


	// Update der Userinfo

	$mySQL = new SQLBuilder();
	$mySQL->addField("pag_date",time(),DB_NUMBER);
	$mySQL->addField("usr_id",$_SESSION["usr_id"]);

	// Update der Cachezeiten

	$mySQL->addField("pag_nextbuild1",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild2",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild3",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild4",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild5",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild6",0,DB_NUMBER);

	// Multilanguage-Flag
	$sql = "SELECT COUNT(*) AS C FROM sequence_data WHERE pag_id=" .$id . " AND dat_editbuffer=0 AND lng_id <>1";
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	if ($row["C"]==0)
	{
		$mySQL->addField("pag_multilanguage",0,DB_NUMBER);
		// Temporäre Daten für diese Seite des angemeldeten Benutzers löschen (warum??)
		// $sql = "DELETE * FROM sequence_data WHERE pag_id=" .$id . " AND dat_editbuffer=0 AND lng_id <>1 AND usr_id=".$_SESSION["usr_id"];
	}
	else
	{
		$mySQL->addField("pag_multilanguage",1,DB_NUMBER);

		// Gibt es einen Sprachdatensatz mit Cacheinformationen
		$sql = "SELECT COUNT(*) AS C FROM page_language WHERE pag_id=" . $id . " AND lng_id=". $_SESSION["lng_id"];
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if ($row["C"]==0)
		{
			$mySQL2= new SQLBuilder();
			$mySQL2->addField("pag_id",$id,DB_NUMBER);
			$mySQL2->addField("lng_id",$_SESSION["lng_id"],DB_NUMBER);
			$sql = $mySQL2->insert("page_language");
			$myDB->query($sql);
		}
	}

	$sql = $mySQL->update("page","pag_id=".$id);
	$myDB->query($sql);
}

if ((isset($_REQUEST["savescript"]) OR isset($_REQUEST["savescript_preview"])) AND( $mySUser->checkRight("superuser")))
{
	$myAdm = new PhenotypeAdmin();
	if ($myAdm->browserOK_HTMLArea())
	{
		$code = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));
	}
	else
	{
		$code = $myAdm->decodeRequest_TextArea($myRequest->get("skript"));
	}
	$scriptname = APPPATH . "pagescripts/" .  sprintf("%04.0f", $id) . "_" . sprintf("%04.0f", $ver_id) . ".inc.php";

	$mySQL = new SQLBuilder();

	if (trim($code)=="")
	{
		// Das Skriptfeld ist leer
		@unlink ($scriptname);
		$mySQL->addField("pag_exec_script",0);
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

}

if ((isset($_REQUEST["savescript"]) OR isset($_REQUEST["savescript_preview"])))
{
	$mySQL = new SQLBuilder();
	$mySQL->addField("inc_id1",$_REQUEST["inc_id1"],DB_NUMBER);
	$mySQL->addField("inc_id2",$_REQUEST["inc_id2"],DB_NUMBER);


	$sql = $mySQL->update("pageversion","pag_id=".$id . " AND ver_id=".$ver_id);
	$myDB->query($sql);

	$mySQL = new SQLBuilder();
	$mySQL->addField("pag_date",time(),DB_NUMBER);
	$mySQL->addField("usr_id",$_SESSION["usr_id"]);
	$mySQL->addField("pag_nextbuild1",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild2",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild3",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild4",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild5",0,DB_NUMBER);
	$mySQL->addField("pag_nextbuild6",0,DB_NUMBER);

	/*
	$mySQL->addField("pag_printcache1",0,DB_NUMBER);
	$mySQL->addField("pag_printcache2",0,DB_NUMBER);
	$mySQL->addField("pag_printcache3",0,DB_NUMBER);
	$mySQL->addField("pag_printcache4",0,DB_NUMBER);
	$mySQL->addField("pag_printcache5",0,DB_NUMBER);
	$mySQL->addField("pag_printcache6",0,DB_NUMBER);

	$mySQL->addField("pag_xmlcache1",0,DB_NUMBER);
	$mySQL->addField("pag_xmlcache2",0,DB_NUMBER);
	$mySQL->addField("pag_xmlcache3",0,DB_NUMBER);
	$mySQL->addField("pag_xmlcache4",0,DB_NUMBER);
	$mySQL->addField("pag_xmlcache5",0,DB_NUMBER);
	$mySQL->addField("pag_xmlcache6",0,DB_NUMBER);
	*/


	$mySQL->addField("pag_contenttype",$_REQUEST["contenttype"],DB_NUMBER);

	// Variablen updaten
	$sql = "SELECT pag_id_top FROM page WHERE pag_id = " . $id;
	$myDB->query($sql);
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	$top_id = $row["pag_id_top"];

	$_props_top = Array();
	if ($top_id !=0)
	{
		$sql = "SELECT pag_props_all FROM page WHERE pag_id = " .$top_id;
		$myDB->query($sql);
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if ($row["pag_props_all"]!="")
		{
			$_props_top = unserialize($row["pag_props_all"]);
		}
		else
		{
			$_props_top = Array();
		}
	}
	$_props = Array();
	for ($i=1;$i<=$_REQUEST["varanzahl"];$i++)
	{
		if ($_REQUEST["var".$i]!="")
		{
			$var = stripslashes($_REQUEST["var".$i]);
			$val = stripslashes($_REQUEST["val".$i]);
			if (array_key_exists($var,$_props_top))
			{
				// Unveraendert geerbte Variablen solle nicht gespeichert werden
				$compval = $_props_top[$var];
				if ($compval != $val)
				{
					$_props[$var]= $val;
				}
			}
			else
			{
				$_props[$var]= $val;
			}
		}
	}

	//print_r($_props);

	$mySQL->addField("pag_props_locale",serialize($_props));
	$sql = $mySQL->update("page","pag_id=".$id);
	$myDB->query($sql);

	// Caches der Sprachvarianten leeren
	$sql = "UPDATE page_language SET pag_nextbuild1=0,pag_nextbuild2=0,pag_nextbuild3=0,pag_nextbuild4=0,pag_nextbuild5=0,pag_nextbuild6=0,pag_printcache1=0,pag_printcache2=0,pag_printcache3=0,pag_printcache4=0,pag_printcache5=0,pag_printcache6=0,pag_xmlcache1=0,pag_xmlcache2=0,pag_xmlcache3=0,pag_xmlcache4=0,pag_xmlcache5=0,pag_xmlcache6=0 WHERE pag_id=".$id;
	$myDB->query($sql);

	$myPage = new PhenoTypePage($id,$ver_id);
	$myPage->buildProps();
}

if (isset($_REQUEST["delete"]))
{
	$sql  ="SELECT pag_id_top,grp_id FROM page WHERE pag_id = " . $id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	$top_id = $row["pag_id_top"];

	$sql = "DELETE FROM sequence_data WHERE pag_id = " . $id;
	$myDB->query($sql);
	$sql = "DELETE FROM page_statistics WHERE pag_id = " . $id;
	$myDB->query($sql);
	$sql = "SELECT * FROM pageversion WHERE pag_id = " . $id;
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		$scriptname = APPPATH . "pagescripts/" .  sprintf("%04.0f", $id) . "_" . sprintf("%04.0f", $row["ver_id"]) . ".inc.php";
		@unlink ($scriptname);
	}
	$sql = "DELETE FROM pageversion WHERE pag_id = " . $id;
	$myDB->query($sql);
	$sql = "DELETE FROM page WHERE pag_id = " . $id;
	$myDB->query($sql);
	$id  =$top_id;
	if ($id==0)
	{
		$url = "pagegroup_select.php?grp_id=" . $row["grp_id"];
		//echo $url;
	}
	else
	{
		$url = "page_edit.php?id=" . $id;
	}
	Header ("Location:" . $url."&".SID);
	exit();
}

if (isset($_REQUEST["vorschau"])  OR isset($_REQUEST["savescript_preview"]))
{
	$url = "page_edit.php?id=" . $id . "&b=" . $block_nr . "&ver_id=" . $ver_id . "&editbuffer=1&preview=1";
}
else
{
	$url = "page_edit.php?id=" . $id . "&b=" . $block_nr . "&ver_id=" . $ver_id . "&editbuffer=1";
}
Header ("Location:" . $url."&".SID."#pos".($pos-1));
?>
