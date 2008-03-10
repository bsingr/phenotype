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
if (!$mySUser->checkRight("elm_page"))
{
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
?>
<?php
$id = $myRequest->getI("id");
if (isset($_REQUEST["ver_id"]))
{
	$ver_id = $myRequest->getI("ver_id");
}
else
{
	$sql = "SELECT * FROM page WHERE pag_id = " . $id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	$ver_id = $row["ver_id"];
}

$myPage = new PhenotypePage($id,$ver_id);

if (!$myPage->isLoaded())
{
	$myPage = new PhenotypePage($id);
}
if (!$myPage->isLoaded())
{
	exit();
}


$_SESSION["pag_id"]=$myPage->id;
$_SESSION["grp_id"]=$myPage->grp_id;

// Block-Nr bestimmen

if (isset($_REQUEST["b"]))
{
	$block_nr = $_REQUEST["b"];
}
else
{
	$sql = "SELECT * FROM layout_block WHERE lay_id = " . $myPage->lay_id . " AND lay_blocknr= 1";
	$rs = $myDB->query($sql);
	if (mysql_num_rows($rs)==0)
	{
		// Der uebergebene Block existiert nicht

		$block_nr=99; // Notfalls auf dem Versionentab starten
		if ($mySUser->checkRight("elm_pagestatistic")){$block_nr=77;}
		if ($mySUser->checkRight("elm_pageconfig")){$block_nr=0;}
	}
	else
	{
		$block_nr = 1;
	}
}
//$myPage = new PhenotypePage($id,$ver_id);
//$myPage->switchLanguage($lng_id);

$sql = "SELECT grp_multilanguage FROM pagegroup WHERE grp_id=" . $myPage->grp_id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$multilanguage=0;
$languagechange=0;
if ($row["grp_multilanguage"]==1)
{

	$multilanguage=1;
	$lng_id = $myRequest->getI("lng_id");
	if ($lng_id!=0)
	{
		if ($lng_id_session != $lng_id)
		{
			$languagechange=1;
			$_SESSION["lng_id"]=$lng_id;
		}
	}
}
else
{
	$_SESSION["lng_id"]=1;
}



// Befinden wir uns schon im Editbuffer-Modus?

if (!isset($_REQUEST["editbuffer"]) OR $languagechange == 1)
{
	$sql = "DELETE FROM  sequence_data WHERE pag_id = " . $id . " AND ver_id = ".$ver_id ." AND dat_editbuffer=1 AND lng_id=".$_SESSION["lng_id"]." AND usr_id=".$_SESSION["usr_id"];
	$myDB->query($sql);

	// Prüfung, ob es in der Zielsprache Bausteine gibt
	$sql = "SELECT COUNT(*) AS C FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = ".$ver_id ." AND dat_editbuffer=0 AND lng_id=".$_SESSION["lng_id"];
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	$language_copy=0;
	if ($row["C"]==0) // von Sprache 1 kopieren
	{
		if ($_SESSION["lng_id"]!=1)
		{
			if ($block_nr>0 AND $block_nr<77) // Kopierhinweis nur bei Bausteinblöcken und wenn nicht die Standardsprache gewählt wurde
			{
				$language_copy=1;
			}
		}
		$sql = "INSERT INTO sequence_data(dat_id,pag_id,ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id,usr_id) SELECT dat_id,pag_id, ver_id,1 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,".$_SESSION["lng_id"].",".$_SESSION["usr_id"]." FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " .$ver_id . " AND lng_id=1 AND dat_editbuffer=0";
		$myDB->query($sql);
	}
	else
	{
		$sql = "INSERT INTO sequence_data(dat_id,pag_id,ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id,usr_id) SELECT dat_id,pag_id, ver_id,1 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,".$_SESSION["lng_id"].",".$_SESSION["usr_id"]." FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " .$ver_id . " AND lng_id=".$_SESSION["lng_id"]." AND dat_editbuffer=0";
		$myDB->query($sql);
	}

}
?>
<?php
//$myPage = $myPT->getPage($id);


$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
?>
<?php
$myAdm->header("Redaktion");
?>
<?php


if ($block_nr>0 AND $block_nr<77)
{
	//
}
?>
<body>
  <script type="text/javascript" language="JavaScript">
  <?php
  if (isset($_REQUEST["preview"]))
  {
  	?>
  	previewPage(<?php echo $_REQUEST["id"] ?>,<?php echo $_REQUEST["ver_id"] ?>,<?php echo $_SESSION["lng_id"] ?>);
  	<?php
  }
  ?>
  </script>
<?php
$myAdm->menu("Redaktion");
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Redaktion","Seiten");
$myAdm->explorer_set("pag_id",$_REQUEST["id"]);
$myAdm->explorer_draw();
?>
<?php
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left}
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
//$myAdm->editBlock($myPage,$block_nr,$ver_id);
global $myDB;
global $myPT;
global $myLayout;

$myLayout->idline_page_draw($myPage);


// Mehrsprachigkeit
if ($multilanguage==1)
{
	?>
	<form action="page_edit.php" id="multilanguage">
	<input type="hidden" name="id" value="<?php echo $myPage->id ?>">
	<input type="hidden" name="ver_id" value="<?php echo $myPage->ver_id ?>">
	<input type="hidden" name="b" value="<?php echo $block_nr ?>">
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab" align="right">
			<select class="input" name="lng_id" onchange="document.forms.multilanguage.submit();">
			<?php
			foreach ($PTC_LANGUAGES AS $key => $val)
			{

				$selected ="";
				if ($_SESSION["lng_id"]==$key)
				{
					$selected="selected";
				}


			?>
			<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
			<?php
			}
			?></select>&nbsp;
			</td>
			<td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
    </form>
	<?php
}




$conflict = "";
$sql = "SELECT usr_id,pag_date FROM page WHERE pag_id=".$id;
$rs_conflict= $myDB->query($sql);
$row_conflict = mysql_fetch_array($rs_conflict);
$datum = $row_conflict["pag_date"];
$minuten = (int)((time()-$datum)/60);
if ($minuten <10 AND $row_conflict["usr_id"]!=$mySUser->id)
{
	$zustand="";
	switch ($minuten)
	{
		case 0:
			$zustand = "gerade";
			break;
		case 1:
			$zustand = "vor 1 Minute";
			break;
		default:
			$zustand = "vor " . $minuten . " Minuten ";
			break;

	}
	$myUser = new PhenotypeUser($row_conflict["usr_id"]);
	$conflict = "Diese Seite wurde ".$zustand . " von " . $myUser->getName() . " verändert.";
}

if ($conflict)
{
?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="windowAlert"><h1>Achtung!</h1>
			    <p><?php echo $conflict ?></p></td>
              </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
<?php	
}

if ($language_copy)
{
?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="windowInfo"><h1>Hinweis!</h1>
			    <p>Diese Seite wurde in der Sprache <strong><?php echo $PTC_LANGUAGES[$_SESSION["lng_id"]] ?></strong> bisher nicht bearbeitet. Es wurde eine aktuelle Kopie der Standardsprache erstellt.</p></td>
              </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
<?php	
}


// Ueberpruefung auf angehaengte Tickets
if ($mySUser->checkRight("elm_task"))
{
	// Meine Hinweise und Anfragen in temporaere Tabellen
	$sql_request = " SELECT tik_id,tik_request FROM ticketrequest WHERE usr_id=" . $_SESSION["usr_id"];

	$table_request ="temp_request_" . uniqid("pt");
	$sql = "CREATE TEMPORARY TABLE " . $table_request . $sql_request;
	$rs = $myDB->query($sql);

	$sql_markup = " SELECT tik_id,tik_markup FROM ticketmarkup WHERE usr_id=" . $_SESSION["usr_id"];

	$table_markup ="temp_markup_" . uniqid("pt");
	$sql = "CREATE TEMPORARY TABLE " . $table_markup . $sql_markup;
	$rs = $myDB->query($sql);

	// Grund-SQL fuer alle Detailabfragen

	$sql = "SELECT *, ticket.tik_id AS tik_id FROM ticket LEFT JOIN ticketsubject ON ticket.sbj_id = ticketsubject.sbj_id LEFT JOIN $table_request ON ticket.tik_id = $table_request.tik_id LEFT JOIN $table_markup ON ticket.tik_id = $table_markup.tik_id WHERE ((tik_status = 1 ) OR (tik_status = 0 AND tik_closingdate > ". (time()-(3600*12*14)) .")) AND pag_id=" . $id;
	$rs = $myDB->query($sql);
	if (mysql_num_rows($rs)!=0)
	{
	?>
		<br><table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Aufgaben</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTask">
	<?php $myLayout->listTickets($rs);
	?>
			</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
       <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?php
	}
	// Temptabellen wieder loeschen
	$sql = "DROP TEMPORARY TABLE " . $table_markup;
	$rs = $myDB->query($sql);
	$sql = "DROP TEMPORARY TABLE " . $table_request;
	$rs = $myDB->query($sql);

}

// Initalisierung des fuer alle Tabs gleichermassen gueltigen Formulars
    ?>
    <form enctype="multipart/form-data" name="editform" method="post" action="page_update.php">
    <input type="hidden" value="1" name="editbuffer">
    <input type="hidden" name="id" value="<?php echo $myPage->id ?>">
    <input type="hidden" name="ver_id" value="<?php echo $myPage->ver_id ?>">
    <input type="hidden" name="block_nr" value="<?php echo $block_nr ?>">
    <input type="hidden" name="newtool_id" value="">
    <input type="hidden" name="newtool_type" value="">
     <?php
     $myLayout->tab_new();
     $sql = "SELECT * FROM layout_block WHERE lay_id = " . $myPage->lay_id . " ORDER BY lay_blocknr";
     $rs = $myDB->query($sql);
     while ($row = mysql_fetch_array($rs))
     {
     	$url = "page_edit.php?id=" .$myPage->id ."&b=". $row["lay_blocknr"]."&ver_id=" . $ver_id;
     	$myLayout->tab_addEntry($row["lay_blockbez"],$url,"b_items.gif");
     }
     $url = "page_edit.php?id=" .$myPage->id ."&b=0&ver_id=" . $ver_id;
     if ($mySUser->checkRight("elm_pageconfig"))
     {
     	$myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
     }

     // Versionentab
     $sql = "SELECT pag_id FROM pageversion WHERE pag_id = " . $myPage->id;
     $rs = $myDB->query($sql);

     if ((mysql_num_rows($rs)>1) OR ($block_nr==99))
     {
     	$url = "page_edit.php?id=" .$myPage->id ."&b=99&ver_id=" . $ver_id;
     	$myLayout->tab_addEntry("Versionen",$url,"b_version.gif");
     }
     //if ($mySUser->checkRight("elm_pageconfig"))
     if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser"))
     {
     	$url = "page_edit.php?id=" .$myPage->id ."&b=88&ver_id=" . $ver_id;
     	$myLayout->tab_addEntry("Skript",$url,"b_skript.gif");
     }
     if ($mySUser->checkRight("elm_pagestatistic"))
     {
     	if ($myPage->statistic==true)
     	{
     		$url = "page_edit.php?id=" .$myPage->id ."&b=77&ver_id=" . $ver_id;
     		$myLayout->tab_addEntry("Statistik",$url,"b_statistic.gif");
     	}
     }
     ?>


    <?php
    // Konfigurationstab
    if ($block_nr==0 AND $mySUser->checkRight("elm_pageconfig"))
    {
    	$myLayout->tab_draw("Konfiguration");
    	$myLayout->workarea_start_draw();

    	// Eigenschaften
    	//$html = $myLayout->workarea_form_text("Titel","titel",$myPage->titel);
			$html = $myLayout->workarea_form_text("Titel","titel",stripslashes($myPage->titel)); // Changed by Dominique Bös - 2007/08/19	

    	if ($myPT->getPref("edit_pages.show_alternative_title")==1)
    	{
    		$html.= $myLayout->workarea_form_text("Alternativtitel","alttitel",$myPage->alttitel);
    	}
    	else
    	{
    		$html.= $myLayout->workarea_form_hidden("alttitel",$myPage->alttitel);
    	}

    	// Bestimmen welche Layouts genutzt werden dürfen
    	$_layout_usable=Array();
    	$sql = "SELECT * FROM layout_pagegroup WHERE grp_id=" .$myPage->grp_id;
    	$rs = $myDB->query($sql);
    	while ($row=mysql_fetch_array($rs))
    	{
    		$_layout_usable[]=$row["lay_id"];
    	}
    	$_layout_protected=Array();
    	$sql = "SELECT DISTINCT(lay_id) FROM layout_pagegroup";
    	$rs = $myDB->query($sql);
    	while ($row=mysql_fetch_array($rs))
    	{
    		$_layout_protected[]=$row["lay_id"];
    	}
    	$_layout_deny = array_diff($_layout_protected,$_layout_usable);

    	$sql = "SELECT lay_id AS K, lay_bez AS V FROM layout ORDER BY lay_bez";
    	$rs = $myDB->query($sql);
    	$_options[0]="kein Template";
    	while ($row=mysql_fetch_array($rs))
    	{
    		if (!in_array($row["K"],$_layout_deny))
    		{
    			$_options[$row["K"]]=$row["V"];
    		}
    	}
    	$_options = $myAdm->buildOptionsByNamedArray($_options,$myPage->lay_id);
    	$html.=$myLayout->workarea_form_select("Layout","template_id",$_options);

    	// Jetzt ausgewählte Seitenvariablen
    	if (count($myApp->getEditablePageVars())!=0)
    	{
    		$html .="<br/>";
    	}
    	foreach ($myApp->getEditablePageVars() as $var => $desc)
    	{
    		$html.= $myLayout->workarea_form_text($desc,$var,$myPage->get($var));
    	}

    	$myLayout->workarea_row_draw("Eigenschaften",$html);

    	// Mehrsprachigkeit der Titel
    	if ($multilanguage==1)
    	{

    		$html = "";
    		foreach ($PTC_LANGUAGES AS $k =>$v)
    		{
    			if ($k<>1)
    			{
    				$value="";
    				$sql = "SELECT pag_titel FROM page_language WHERE pag_id=".$myPage->id . " AND lng_id=" . $k;
    				$rs = $myDB->query($sql);
    				if (mysql_num_rows($rs)==0)
    				{
    					$mySQL = new SQLBuilder();
    					$mySQL->addField("pag_id",$myPage->id,DB_NUMBER);
    					$mySQL->addField("lng_id",$k,DB_NUMBER);
    					$sql=$mySQL->insert("page_language");
    					$myDB->query($sql);
    				}
    				else
    				{
    					$row = mysql_fetch_array($rs);
    					$value = $row["pag_titel"];
    				}
    				$html.= $myLayout->workarea_form_text($v,"lng_titel".$k,$value);
    			}
    		}

    		$myLayout->workarea_row_draw("Titel<br/>(mehrsprachig)",$html);
    	}

    	// Meta

    	//$html = $myLayout->workarea_form_text("Seitenbezeichnung","bez",$myPage->bez);
    	$html = $myLayout->workarea_form_text("Seitenbezeichnung","bez",stripslashes($myPage->bez)); // Changed by Dominique Bös - 2007/08/19
    	$html.= $myLayout->workarea_form_text("Name der Version","ver_bez",$myPage->ver_bez);
    	//$html.=   $myLayout->workarea_form_textarea("Kommentar","comment",$myPage->row["pag_comment"]);
    	$html.=   $myLayout->workarea_form_textarea("Kommentar","comment",stripslashes($myPage->row["pag_comment"])); // Changed by Dominique Bös - 2007/08/19
    	$myLayout->workarea_row_draw("Meta",$html);

    	// Navigation
    	if ($myPT->getPref("edit_pages.show_pageurl")==1)
    	{
    		$html = $myLayout->workarea_form_text("Direktzugriff-URL","url",$myPage->row["pag_url"]);
    	}
    	else
    	{
    		$html = $myLayout->workarea_form_hidden("url",$myPage->row["pag_url"]);
    	}
    	$myPT->startbuffer();
     ?>
     Verhalten:<br>
     <select name="pag_id_mimikry" style="width: 200px" class="listmenu">
     <option value="<?php echo $myPage->pag_id ?>">Standard</option>
     <option value="<?php echo $myPage->pag_id ?>">- - - - - - - - - - - - - - - - - - -</option>
     <!--<option value="-1">Unsichtbar</option>
     <option value="<?php echo $myPage->pag_id ?>">- - - - - - - - - - - - - - - - - - -</option>-->
     <?php
     $sql = "SELECT * FROM page WHERE pag_id <> 0 AND pag_id_mimikry = pag_id ORDER BY grp_id, pag_bez";
     $rs = $myDB->query($sql);
     $grp_id =0;
     while ($row_page = mysql_fetch_array($rs))
     {
     	if ($grp_id!=$row_page["grp_id"])
     	{
     		if ($grp_id !=0)
     		{
     			?><option value="<?php echo $myPage->pag_id ?>">- - - - - - - - - - - - - - - - - - -</option><?php
     		}
     		$grp_id = $row_page["grp_id"];
     	}
     	$selected ="";
     	if (($row_page["pag_id"]== $myPage->pag_id_mimikry) AND ($myPage->id != $myPage->pag_id_mimikry))
     	{
     		$selected = "selected";
     	}
     	?><option value="<?php echo $row_page["pag_id"] ?>" <?php echo $selected ?>>Mimikry -> <?php echo $row_page["pag_bez"] ?></option><?php
     }
     ?>
     </select>
     <?php
     $html.= $myPT->stopBuffer();
     $myLayout->workarea_row_draw("Navigation",$html);

     //Cache
		 //$options = Array(0=>"kein Cache",15=>"15 Sekunden",30=>"30 Sekunden",45=>"45 Sekunden",60=>"1 Minute",120=>"2 Minuten",300=>"5 Minuten",600=>"10 Minuten",3600=>"60 Minuten",86400=>"24 Stunden");
		 //Get the cache times from the preferences XML-file | added 07/08/23 by Dominique Bös
		 $aXML = $myPT->gaGetPreferencesArray("preferences.section_cache.cachetime");
		 foreach($aXML as $sItem => $sValue) {
			 $options[$sValue["seconds"]] = $sValue["name"];
		 }

     $options=$myAdm->buildOptionsByNamedArray($options,$myPage->row["pag_cache"]);
     $html=$myLayout->workarea_form_select("","cache",$options,100);
     $myLayout->workarea_row_draw("Cache",$html);

     // Suche
     $html="";
     if ($myPT->getPref("edit_pages.show_quickfinder")==1)
     {
     	$myPT->startBuffer();
     ?>
     <input name="usequickfinder" type="checkbox" value="1" <?php if ($myPage->row["pag_quickfinder"]!="") echo"checked"; ?>>
                    Seite unter:
                    <input name="quickfinder" type="text" class="feld" value="<?php echo $myPage->row["pag_quickfinder"] ?>" size="30" />
                    im Quickfinder anlegen. <br><br>
     <?php
     $html = $myPT->stopBuffer();
     }
     $html.=   $myLayout->workarea_form_textarea("Suchbegriffe","searchtext",$myPage->row["pag_searchtext"]);
     $myLayout->workarea_row_draw("Suche",$html);

     if ($mySUser->checkRight("superuser"))
     {
     	$myLayout->workarea_row_draw("UID",$myPage->uid."<br>");
     }
     // Status
     $myPT->startBuffer();
     ?>
     <input name="status" type="checkbox" value="1" <?php if ($myPage->row["pag_status"]=="1") echo"checked"; ?>> online.
     <?php
     $myAdm->displayCreationStatus($myPage->row["usr_id_creator"],$myPage->row["pag_creationdate"]);
     echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
     $myAdm->displayChangeStatus($myPage->row["usr_id"],$myPage->row["pag_date"]);
     $html = $myPT->stopBuffer() . "<br><br>";

     $myLayout->workarea_row_draw("Status",$html);
     // Abschlusszeile
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
        <input name="vorschau" type="submit" style="width:102px"class="buttonWhite" value="Vorschau">
            </td>
            <td align="right" class="windowFooterWhite">    <?php if ($myPage->hasChilds()==0){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diese Seite wirklich l&ouml;schen?')">&nbsp;&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>

     <?php
     $myLayout->workarea_stop_draw();
     echo "</form>";

    }

    if ($block_nr==77 AND $mySUser->checkRight("elm_pagestatistic")) // Statistik
    {
    	$myLayout->tab_draw("Statistik");
    	$myLayout->workarea_start_draw();

    	$datum = mktime( 12 ,00,00,date('m'),date('d')-14,date('Y'));
    	$datum = date('Ymd',$datum);

    	$max=0;
    	$sum=0;
    	for ($i=0;$i<=14;$i++)
    	{
    		$datum = mktime( 12 ,00,00,date('m'),date('d')-$i,date('Y'));
    		$sqldatum = date('Ymd',$datum);

    		$sql = "SELECT sta_pageview FROM page_statistics WHERE pag_id=" .$_REQUEST["id"] . " AND sta_datum=" . $sqldatum;
    		$rs = $myDB->query($sql);
    		$row=mysql_fetch_array($rs);
    		if (mysql_num_rows($rs)==0)
    		{
    			$pi =0;
    		}
    		else
    		{
    			$pi = $row["sta_pageview"];
    			$sum += $pi;
    			if ($pi>$max){$max=$pi;}
    		}
    		$_pi[$i]=$pi;
    	}

    	$avg = $sum/14;
    	$pix = 480;
    	if ($max!=0){$avg = ceil($avg/$max*$pix);}else{$avg=0;}
      ?>
      <style>
      /* Dynamisierung der x-Position für den Mittelwert */
.tableMarker {
    padding: 5px 10px 5px 10px;
    background:  url(img/i_stat_marker.gif) no-repeat <?php echo $avg+8 ?>px 0px;
    }
    </style>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableHead">Datum</td>
            <td align="center" class="tableHead">Abrufe</td>
            <td width="500" class="tableHead">Diagramm ( <img src="img/i_stat_legend.gif" width="5" height="8" align="absmiddle"> zeigt den Mittelwert an)</td>
            </tr>
          <tr>
      <?php
      $color="red";
      $tag="<strong>heute</strong>";
      for ($i=0;$i<=14;$i++)
      {
      	$datum = mktime( 12 ,00,00,date('m'),date('d')-$i,date('Y'));
      	if ($i!=0)
      	{
      		$tag = date('d.m.Y',$datum);
      		$color="blue";
      	}
      	$pi=$_pi[$i];
      	if ($max>0){$x = ceil($pi/$max*$pix);}else{$x=0;}
    ?>

            <td colspan="3" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
          <tr>
            <td class="tableBody"><?php echo $tag ?></td>
            <td align="center" class="tableBody"><?php echo $pi ?></td>
            <td class="tableMarker"><img src="img/i_stat_<?php echo $color ?>.gif" width="<?php echo $x ?>" height="6"></td>
            </tr>
          <tr>
      <?php
      }
      ?>
           <tr>
            <td colspan="3" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>

        <tr>
          <td colspan="3" class="windowFooterWhite">&nbsp;</td>
        </tr>
        </table>
    <?php
    $myLayout->workarea_stop_draw();
    }
    // Beginn Block Skript

    if ($block_nr==88 AND $mySUser->checkRight("elm_pageconfig"))
    {
    	//
    	$myLayout->tab_draw("Skript");
    	$myLayout->workarea_start_draw();

    	if ($mySUser->checkRight("superuser")==1)
    	{
    		$scriptname = "pagescripts/" .  sprintf("%04.0f", $myPage->id) . "_" . sprintf("%04.0f", $myPage->ver_id) . ".inc.php";

    ?><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><strong><?php echo $scriptname; ?></strong><br>
            <?php
            $scriptname = APPPATH . $scriptname;
            echo $myLayout->form_HTMLTextArea("skript",$scriptname,80,20,"PHP");
            ?>
            </td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
            </table>
		<?php
    	}
    	else
    	{
    		$scriptname = "pagescripts/" .  sprintf("%04.0f", $myPage->id) . "_" . sprintf("%04.0f", $myPage->ver_id) . ".inc.php";
    		if (file_exists(APPPATH.$scriptname))
    		{
    			$html = "Dieser Seite ist das Skript " . $scriptname . " zugeordnet.";
    		}
    		else
    		{
    			$html = "Diese Seite enth&auml;lt kein Skript.";
    		}
    		$myLayout->workarea_row_draw("Seitenskript",$html);
    	}
		?>	
             <?php
             $sql = "SELECT * FROM include WHERE inc_usage_page = 1 ORDER BY inc_rubrik,inc_bez";
             $rs = $myDB->query($sql);
             $_includes = Array();
             $_includes[0] = "kein Include";
             $rubrik = "";
             while ($row=mysql_fetch_array($rs))
             {
             	if ($row["inc_rubrik"]!=$rubrik)
             	{
             		$rubrik = $row["inc_rubrik"];
             		$_includes[$row["inc_id"] ."a"]="- - - - - - - - - - - - - - - - - - - - - - - -";
             		$_includes[$row["inc_id"] ."b"]= $rubrik . ":";
             	}
             	$_includes[$row["inc_id"]]= "- " . $row["inc_bez"];
             }
             $options = $myAdm->buildOptionsbyNamedArray($_includes,$myPage->inc_id1);
             $html = $myLayout->workarea_form_select("","inc_id1",$options);
             $options = $myAdm->buildOptionsbyNamedArray($_includes,$myPage->inc_id2);
             $html.=  $myLayout->workarea_form_select("","inc_id2",$options);
             $myLayout->workarea_row_draw("Includes<br>(Pre/Post)",$html);
          ?>
          <?php
          $html = '<table border="0" cellspacing="0" cellpadding="0">';
          $_props = $myPage->row["pag_props_all"];
          $_props_top =Array();
          if ($myPage->pag_id_top !=0)
          {
          	$sql = "SELECT pag_props_all FROM page WHERE pag_id = " .$myPage->pag_id_top;
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

          $n=0;
          if ($_props!="")
          {
          	$_props = unserialize  ($_props);
          	if (!is_array($_props)){$_props = Array();}
          	foreach ($_props as $key => $val)
          	{
          		$n++;
          		$class="tableVarNew";
          		if (array_key_exists($key,$_props_top))
          		{
          			$class = "tableVarParent";
          			if ($_props_top[$key]!=$_props[$key])
          			{
          				$class = "tableVarChange";
          			}
          		}
          		$html .='<tr class="'.$class.'"><td><input type="text" name="var'.$n.'" style="width: 120px" class="input" value="'.htmlentities($key).'"></td><td><strong>:</strong></td><td><input type="text" name="val'.$n.'" style="width: 250px" class="input" value="'.htmlentities($val).'">&nbsp;&nbsp;</td></tr>';
          	}
          }

          for ($i=1;$i<=3;$i++)
          {
          	$n++;
          	$html .='<tr class="tableVarNew"><td><input type="text" name="var'.$n.'" style="width: 120px" class="input" value=""></td><td><strong>:</strong></td><td><input type="text" name="val'.$n.'" style="width: 250px" class="input" value=""></td></tr>';
          }

          $html .= '</table><input type="hidden" name="varanzahl" value="'.($n).'"';
          $myLayout->workarea_row_draw("Variablen",$html);

          // HTTP-Header
          $options = $myAdm->buildOptionsbyNamedArray($_PT_HTTP_CONTENTTYPES,$myPage->row["pag_contenttype"]);
          $html=  $myLayout->workarea_form_select("","contenttype",$options);
          $myLayout->workarea_row_draw("HTTP-Header",$html);

          $html ="Letzter Seitabruf:<br>";
          if ($myPage->row["pag_lastfetch"]==0)
          {
          	$html .= "noch nie<br>";
          }
          else
          {
          	$html .= date("d.m H:i",$myPage->row["pag_lastfetch"]) . "<br>";
          }

          // Nur 1 Cache
          if (CACHECOUNT==1)
          {
          	$datum =$myPage->row["pag_nextbuild1"];
          	if ($datum<time())
          	{
          		$html .="Seite wird beim n&auml;chsten Abruf neu gerendert.<br>";
          	}
          	else
          	{
          		$html .="Seite g&uuml;ltig bis:<br>" . date("d.m H:i",$datum) . "<br>";
          	}
          }

          if ($myPage->statistic==true)
          {

          	$sql = "SELECT SUM(sta_pageview) AS views FROM page_statistics WHERE pag_id =" . $myPage->id;
          	$rs = $myDB->query($sql);
          	$row = mysql_fetch_array($rs);
          	$views_gesamt = $row["views"];
          	if ($views_gesamt==""){$views_gesamt=0;}
          	$sql = "SELECT SUM(sta_pageview) AS views FROM page_statistics WHERE pag_id =" . $myPage->id . " AND sta_datum >" . date('Ym') ."00" . " AND sta_datum <" . date('Ym') . "99";
          	$rs = $myDB->query($sql);
          	$row = mysql_fetch_array($rs);
          	$views_monat = $row["views"];
          	if ($views_monat==""){$views_monat=0;}
          	$sql = "SELECT SUM(sta_pageview) AS views FROM page_statistics WHERE pag_id =" . $myPage->id . " AND sta_datum =" . date('Ymd');
          	$rs = $myDB->query($sql);
          	$row = mysql_fetch_array($rs);
          	$views_heute = $row["views"];
          	if ($views_heute==""){$views_heute=0;}
          	$html .="Statistik: (Tag/Monat/Gesamt)<br> " . $views_heute . " / " . $views_monat . " / " . $views_gesamt ." <br>";
          }


          $build = $myPage->row["pag_lastbuild_time"];
          $cache = $myPage->row["pag_lastcache_time"];
          if ($build!=1 OR $cache!=1)
          {
          	$html .= "Zugriffzeit:<br>";
          }
          if ($build!=1)
          {
          	$html.="Build " . $build . " sec (Cache ".$myPage->row["pag_lastcachenr"].")<br>";
          }
          if ($cache!=1)
          {
          	$html.="Cache " . $cache . " sec<br>";
          }

          if (CACHECOUNT >1)
          {
          	$html .="Cache-Status:<br>";
          	for ($i=1;$i<=CACHECOUNT;$i++)
          	{
          		$datum =$myPage->row["pag_nextbuild".$i];
          		$html .= $i . ": ";
          		if ($datum<time())
          		{
          			$html .="Seite wird beim n&auml;chsten Abruf neu gerendert.<br>";
          		}
          		else
          		{
          			$html .="Seite g&uuml;ltig bis " . date("d.m H:i",$datum) . "<br>";
          		}
          	}
          }

          $myLayout->workarea_row_draw("Monitor",$html);
          ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
        <input name="savescript_preview" type="submit" style="width:102px"class="buttonWhite" value="Vorschau">
            </td>
            <td align="right" class="windowFooterWhite"><input name="savescript" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table
        <?php
        $myLayout->workarea_stop_draw();
        echo "</form>";
    }
    // Ende Block Skript
    if ($block_nr==99)
    {
    	$myLayout->tab_draw("Versionen");
    ?>
          <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
      </tr>
    </table>
 <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="windowTitle">Übersicht </td>
                </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
        </tr>
      </table>
      <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowTab"><img src="img/white_border.gif" width="1" height="1"></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
        </tr>
      </table>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="40" valign="top" class="tableHead">ID</td>
            <td width="40" valign="top" class="tableHead">Nr.</td>
            <td class="tableHead">Bezeichnung</td>
            <td width="40" class="tableHead">Status</td>
            <td width="100" class="tableHead">Aktion</td>
            </tr>
           <tr>
            <td colspan="5" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>

<?php
$sql = "SELECT ver_id FROM page WHERE pag_id = ". $myPage->id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$ver_id_currentactive = $row["ver_id"];

$sql = "SELECT * FROM pageversion WHERE pag_id =" . $myPage->id . " ORDER BY ver_nr";
$rs = $myDB->query($sql);

while ($row = mysql_fetch_array($rs))
{



	if ($row["ver_id"]==$ver_id_currentactive)
	{
?>          <tr>
            <td class="tableBody"><?php echo $row["ver_id"] ?></td>
            <td class="tableBody"><p class="blue"><strong><?php echo $row["ver_nr"] ?></strong></p></td>
            <td class="tableBody"><p class="blue"><strong>
<?php echo $row["ver_bez"] ?></strong></p></td>
            <td class="tableBody"><img src="img/i_online.gif" width="30" height="22"></td>
            <td align="left" nowrap class="tableBody"><a href="page_edit.php?id=<?php echo $myPage->id ?>&b=0&ver_id=<?php echo $row["ver_id"] ?>"><img src="img/b_edit.gif" alt="bearbeiten" width="22" height="22" border="0" align="absmiddle"></a><a href="javascript:pageversion_autoactivation(<?php echo $myPage->id ?>,<?php echo $row["ver_id"] ?>,<?php echo $_REQUEST["ver_id"] ?>);"> <img src="img/b_einstellen.gif" alt="Versionswechsel einstellen" width="22" height="22" border="0" align="absmiddle"></a></td>
            </tr>
           <tr>
            <td colspan="5" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
<?php   }else{
    ?>
         <tr>
            <td class="tableBody"><?php echo $row["ver_id"] ?></td>
            <td class="tableBody"><p><?php echo $row["ver_nr"] ?></p></td>
            <td class="tableBody"><p><?php echo $row["ver_bez"] ?></p></td>
            <td class="tableBody"><img src="img/i_offline.gif" width="30" height="22"></td>
            <td align="left" nowrap class="tableBody"><a href="page_edit.php?id=<?php echo $myPage->id ?>&b=0&ver_id=<?php echo $row["ver_id"] ?>"><img src="img/b_edit.gif" alt="bearbeiten" width="22" height="22" border="0" align="absmiddle"></a> <a href="javascript:pageversion_autoactivation(<?php echo $myPage->id ?>,<?php echo $row["ver_id"] ?>,<?php echo $_REQUEST["ver_id"] ?>);"> <img src="img/b_einstellen.gif" alt="Versionswechsel einstellen" width="22" height="22" border="0" align="absmiddle"></a> <a href="pageversion_delete.php?id=<?php echo $myPage->id ?>&b=0&ver_id=<?php echo $row["ver_id"] ?>&ver_id_editing=<?php echo $_REQUEST["ver_id"] ?>"><img src="img/b_delete.gif" alt="l&ouml;schen" width="22" height="22" border="0" align="absmiddle"></a> <a href="pageversion_activate.php?id=<?php echo $myPage->id ?>&b=0&ver_id=<?php echo $row["ver_id"] ?>"><img src="img/b_aktivieren.gif" alt="aktivieren" width="22" height="22" border="0" align="absmiddle"></a></td>
            </tr>
           <tr>
            <td colspan="5" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
    <?php }
}
?>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
      <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><a href="pageversion_insert.php?id=<?php echo $myPage->id ?>&ver_id=<?php echo $_REQUEST["ver_id"] ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> Neue Version hinzuf&uuml;gen </a>
</td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
        </tr>
      </table>

      <br>

 <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="windowTitle">Liste der automatischen Versionswechsel </td>
                </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
        </tr>
      </table>

      <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowTab"><img src="img/white_border.gif" width="1" height="1"></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
        </tr>
      </table>
      <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="60" class="tableHead">Datum</td>
                <td width="40" class="tableHead">Uhrzeit</td>
                <td class="tableHead">Bezeichnung</td>
                <td width="100" class="tableHead">Aktion</td>
              </tr>
 <?php
 $sql = "SELECT * FROM  pageversion_autoactivate LEFT JOIN pageversion ON pageversion_autoactivate.ver_id = pageversion.ver_id WHERE pageversion_autoactivate.pag_id = " . $myPage->id . " ORDER BY ver_date";
 $rs = $myDB->query($sql);

 while ($row=mysql_fetch_array($rs))
 {
      ?>
              <tr>
                <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
                </tr>
              <tr>
                <td class="tableBody"><?php echo date("d.m.Y",$row["ver_date"]) ?></td>
                <td class="tableBody"><?php echo date("H:i",$row["ver_date"]) ?></td>
                <td class="tableBody"><?php echo $row["ver_bez"] ?></td>
                <td align="right" class="tableBody"><a href="pageversion_deleteautoactivation.php?id=<?php echo $myPage->id ?>&ver_id=<?php echo $_REQUEST["ver_id"] ?>&auv_id=<?php echo $row["auv_id"] ?>"><img src="img/b_delete.gif" alt="l&ouml;schen" width="22" height="22" border="0" align="absmiddle"></a></td>
              </tr>
       <?php
 }
       ?>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
      </table>
      <table width="680" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><a href="javascript:pageversion_autoactivation(<?php echo $myPage->id ?>,0,<?php echo $_REQUEST["ver_id"] ?>);" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> Automatischen Versionswechsel eintragen</a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
        </tr>
      </table>

      </td>
     </tr>
     </table>
     </td>
     </tr>
    <?php
    }
    // Bausteinbloecke
    if ($block_nr>0 AND $block_nr<77)
    {
    	$sql = "SELECT * FROM layout_block WHERE lay_id = " . $myPage->lay_id . " AND lay_blocknr=" . $block_nr;
    	$rs = $myDB->query($sql);
    	$row = mysql_fetch_array($rs);
    	$myLayout->tab_draw($row["lay_blockbez"]);
    	$toolkit = $row["cog_id"];
    	$context = $row["lay_context"];
    	$myLayout->workarea_start_draw();
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <?php
    // Das erste Bausteinpulldown
    if (!$mySUser->checkRight("elm_pagenocomponent"))
    {
    	$myLayout->workarea_componentselector_draw($toolkit,0);
    }
    ?>
    <?php
    $sql = "SELECT * FROM sequence_data WHERE pag_id = " . $myPage->id . " AND ver_id = " . $myPage->ver_id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer=1 AND lng_id=".$_SESSION["lng_id"]." AND usr_id=".$_SESSION["usr_id"]." ORDER BY dat_pos";
    //echo $sql;
    $rs = $myDB->query($sql);
    $n= mysql_num_rows($rs);
    $i=0;
    while ($row = mysql_fetch_array($rs))
    {
    	$i++;
    	$myLayout->component_count = $i;
    	$tname = "PhenotypeComponent_" . $row["com_id"];
    	$myComponent = new $tname;

    	if (!myComponent){die("schrott");}
    	$myComponent->init($row);
      ?>
      <tr>
            <td class="padding30"><strong><?php echo $myComponent->bez ?></strong><br><input name="<?php echo $row["dat_id"] ?>_visible" type="checkbox" value="checkbox" <?php if ($myComponent->visible){echo "checked";} ?>>sichtbar
            </td>
            <td>&nbsp;</td>
            <td class="formarea">
      <?php
      $myComponent->edit($context);
      ?>
            </td>
            <td align="center">
            <?php if (!$mySUser->checkRight("elm_pagenocomponent")){ ?>
			<?php
			if ($i>1)
			{
			?>
			<input type="image" src="img/b_up.gif" alt="Baustein nach oben verschieben" width="18" height="18" border="0" name="<?php echo $row["dat_id"] ?>_moveup"><br>
			<?php
			}
			?>
                <input type="image" src="img/b_delete.gif" alt="Baustein l&ouml;schen" width="22" height="22" border="0"  name="<?php echo $row["dat_id"] ?>_delete">
			<?php
			if ($mySUser->checkRight("superuser"))
			{
			?>
			<br><a href="component_debug.php?id=<?php echo $row["dat_id"] ?>" target="_blank"><img src="img/b_debug_grey.gif" border="0"></a>
			<?php
			}
			?>
              <?php
              if ($i<$n)
              {
			  ?>
              <br><input type="image" src="img/b_down.gif" alt="Baustein nach unten verschieben" width="18" height="18" border="0" name="<?php echo $row["dat_id"] ?>_movedown">
			  <?php
              }
			  ?>
			  <?php }else{ ?>&nbsp;<?php } ?>
              </td>        
          </tr>
      <?php
      if (!$mySUser->checkRight("elm_pagenocomponent"))
      {
      	$myLayout->workarea_componentselector_draw($toolkit,$row["dat_id"]);
      }
      else
      {
      	if ($i<$n)
      	{
      	?>
		<tr><td nowrap width="160" class="narrowingRight" colspan="4">&nbsp;</td></tr>
		<?php
      	}
      }
    }
    // Abschlusszeile
?>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
        <input name="vorschau" type="submit" style="width:102px"class="buttonWhite" value="Vorschau">
            </td>
            <td align="right" class="windowFooterWhite">    <?php if ($myPage->hasChilds()==0 AND $mySUser->checkRight("elm_pageconfig")){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diese Seite wirklich l&ouml;schen?')">&nbsp;&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
    <?php
    $myLayout->workarea_stop_draw();
    echo "</form>";
    }
?>
<?php
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content}
// -------------------------------------
?>
<?php
$myAdm->mainTable($left,$content);
?>
<?php

?>
</body>
</html>






















