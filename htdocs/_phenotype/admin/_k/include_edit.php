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
if (PT_CONFIGMODE!=1){exit();}
?>
<?
if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];
?>
<?
$myAdm->header("Konfiguration");
?>
<body>
<?
$myAdm->menu("Konfiguration");
?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Konfiguration","Includes");
$myAdm->explorer_set("inc_id",$id);
$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?
$myPT->startBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
$sql = "SELECT * FROM include WHERE inc_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="include_update.php" method="post">
	<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">	
	<input type="hidden" name="b" value="<?=$_REQUEST["b"]?>">		
    <input type="hidden" name="r" value="<?=$_REQUEST["r"]?>">	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?=$id?> Includes / <?=$row["inc_bez"]?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=17" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>	
	<?
	 $myLayout->tab_new();
	 $url = "include_edit.php?id=" .$id ."&b=0&r=" . $_REQUEST["r"];	 
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $url = "include_edit.php?id=" .$id ."&b=1&r=" . $_REQUEST["r"];	  
	 $myLayout->tab_addEntry("Skript",$url,"b_script.gif");
	 $url = "include_edit.php?id=" .$id ."&b=2&r=" . $_REQUEST["r"];	  
	 $sql = "SELECT * FROM include_template WHERE inc_id = " . $id . " ORDER BY tpl_bez";
	 $rs = $myDB->query($sql);
	 $c= mysql_num_rows($rs);
	 if ($c>0)
	 {
	   $myLayout->tab_addEntry("Templates",$url,"b_template.gif");
	 }
	 $url = "include_edit.php?id=" .$id ."&b=3&r=" . $_REQUEST["r"];	 
	 $myLayout->tab_addEntry("Info",$url,"b_utilisation.gif");		
	 
	 // switch which tab to draw
	 switch ($_REQUEST["b"])
	 {
	   case 0:
	   	 // default tab
	     $myLayout->tab_draw("Konfiguration");
	     $myLayout->workarea_start_draw();
         $html = $myLayout->workarea_form_text("","bez",$row["inc_bez"]);
	     $myLayout->workarea_row_draw("Bezeichnung",$html);
         $html = $myLayout->workarea_form_text("","rubrik",$row["inc_rubrik"]);
	     $myLayout->workarea_row_draw("Rubrik",$html);		 
	     $html=  $myLayout->workarea_form_textarea("","description",$row["inc_description"],8);
	     $myLayout->workarea_row_draw("Beschreibung",$html);
		 
			 $sql = "SELECT * FROM include_template WHERE inc_id = " . $id . " ORDER BY tpl_bez";
			 $rs = $myDB->query($sql);
			 $c= mysql_num_rows($rs);
			 $myPT->startBuffer();
			 ?>
			 <input name="usage_template" type="checkbox" value="1" <?if ($row["inc_usage_layout"]=="1") echo"checked";?>> kann in einem Layout genutzt werden.<br>
				     <input name="usage_tool" type="checkbox" value="1" <?if ($row["inc_usage_includecomponent"]=="1") echo"checked";?>> kann mit dem "Includes"-Baustein platziert werden<br>
<input name="usage_page" type="checkbox" value="1" <?if ($row["inc_usage_page"]=="1") echo"checked";?>> kann einer Seite zugeordnet werden.<br>
         <?
			 $html =$myPT->stopBuffer();
			 $myLayout->workarea_row_draw("Anwendung",$html);
		 
			 $myPT->startBuffer();
			 if ($c==0)
			 {
         ?>
			 <input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="ttp_plus"> Template einf&uuml;gen<br>
			 <?
			 }
			 while ($row_ttp=mysql_fetch_array($rs))
			 {
			   $identifier = "ttp_". $row_ttp["tpl_id"];
				 ?>
				 <strong>$</strong><input name="<?=$identifier?>_bez" type="text" class="input" style="width: 150px" value="<?=$row_ttp["tpl_bez"]?>" size="30">&nbsp;
<input type="image" src="img/b_minus_b.gif" alt="Template-Zugriff l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?=$identifier?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?=$identifier?>_plus"><br>
				 <?
			 }
			 $html = $myPT->stopBuffer();
			 $myLayout->workarea_row_draw("Templates",$html);
			 break;
		 
	   case 1:
	   	 // script tab
	     $myLayout->tab_draw("Skript");
	     $myLayout->workarea_start_draw();
		 //$scriptname = "includes/"  .sprintf("%04.0f", $id) . ".inc.php";	
		 $scriptname = "includes/PhenotypeInclude_". $id . ".class.php";	
		 ?>
			 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><strong><?=$scriptname;?></strong><br>
			<?
			$scriptname = APPPATH . $scriptname;
			echo $myLayout->form_HTMLTextArea("skript",$scriptname,80,30,"PHP");
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
			<input type="hidden" name="rubrik" value="<?=$row["inc_rubrik"]?>">
		 <?
		 break;		 
		 
		 case 2:
		    $myLayout->tab_draw("Templates");
	       $myLayout->workarea_start_draw();

					 $sql = "SELECT * FROM include_template WHERE inc_id = " . $id . " ORDER BY tpl_bez";
					 $rs = $myDB->query($sql);
					 $c= mysql_num_rows($rs);
					 ?>
					 <?

					 while ($row_ttp=mysql_fetch_array($rs))
					 {
					   $identifier = "ttp_". $row_ttp["tpl_id"];
					 ?>
					 <br>
					 <table width="660" border="0" cellpadding="0" cellspacing="0">
                      <?
					  $scriptname = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $id, $row_ttp["tpl_id"], "templates/"); 
                      ?>
					  <tr>
					  <td width="10">&nbsp;</td>
					  <td  width="300"><strong><?=$scriptname;?></strong></td>
					  <td width="335" align="right" >
					  <strong>$</strong><input name="<?=$identifier?>_bez" type="text" class="input" style="width: 150px" value="<?=$row_ttp["tpl_bez"]?>" size="30">&nbsp;
<input type="image" src="img/b_minus_b.gif" alt="Template-Zugriff l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?=$identifier?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?=$identifier?>_plus">
                      </td>
					  <td width="15">&nbsp;</td>
					  </tr>
          <tr>
		    <td>&nbsp;</td>
            <td colspan="3">
					  <?
					  $scriptname =  APPPATH .$scriptname;
					  $myAdm->buildHTMLTextArea($identifier. "_template",$scriptname,80,15,"HTML");
                      ?>
					  </td>
					  </tr>
					  </table>
					  <input type="hidden" name="rubrik" value="<?=$row["inc_rubrik"]?>">
                      <br><br>
					  <?
					  }
		 break;		 
		 
		 case 3:
		    $myLayout->tab_draw("Info");
	        $myLayout->workarea_start_draw();			
			?>
			<?
			$sql ="SELECT * FROM `layout_include` LEFT JOIN layout ON layout.lay_id = layout_include.lay_id  WHERE inc_id = " . $id . " ORDER BY lay_bez";
 			$rs = $myDB->query($sql);
			$html="";
			while ($row = mysql_fetch_array($rs))
			{
			   $html  .= "- " .$row["lay_bez"] . "<br>";
			}
		    $myLayout->workarea_row_draw("Layout",$html);
			// Verwendung im Includes-Baustein
			$sql ="SELECT * FROM sequence_data WHERE com_id = 12 AND dat_editbuffer = 0 AND pag_id!=0";
			$rs = $myDB->query($sql);
			$html="";
			while ($row = mysql_fetch_array($rs))
			{
			   $_props = unserialize($row["dat_comdata"]);
			   if ($_props["inc_id"]==$id)
			   {
			   		$html  .= "- Seite " .$row["pag_id"] . "<br>";
			   }
			}
			$myLayout->workarea_row_draw("Includes-Baustein",$html);

			$myLayout->workarea_row_draw("Seiten","");
		 break;				 
	}
	?>

		 <?
	   // Abschlusszeile
	   // Seiten + Baustein fehlt noch !!
       $sql = "SELECT COUNT(*) AS C FROM layout_include WHERE inc_id = " . $id;
	   $rs = $myDB->query($sql);
	   $row = mysql_fetch_array($rs);
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?if ($row["C"]==0){?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Dieses Include wirklich l&ouml;schen?')">&nbsp;&nbsp;<?}?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
	 <?
	 $myLayout->workarea_stop_draw();
	?>
	</form>
	
<?
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?
$myAdm->mainTable($left,$content);
?>
<?

?>
</body>
</html>
























