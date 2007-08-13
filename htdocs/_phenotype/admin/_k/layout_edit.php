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
if (!$mySUser->checkRight("elm_admin"))
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
$myAdm->header("Admin");
?>
<body>
<?
$myAdm->menu("Admin");
?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Admin","Layout");
$myAdm->explorer_set("lay_id",$id);
$myAdm->explorer_draw();
?>
<?
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
$sql = "SELECT * FROM layout WHERE lay_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="layout_update.php" method="post" name="editform">
	<input type="hidden" name="id" value="<?=$id?>">	
	<input type="hidden" name="b" value="<?=$_REQUEST["b"]?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?=$id?> Layout / <?=$row["lay_bez"]?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=18" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	 $url = "layout_edit.php?id=" .$id ."&b=0";	 
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $url = "layout_edit.php?id=" .$id ."&b=1";	 
	 $myLayout->tab_addEntry("Templates",$url,"b_template.gif");


	 $url = "layout_edit.php?id=" .$id ."&b=2";	 
	 $myLayout->tab_addEntry("Info",$url,"b_utilisation.gif");	
	 
	 switch ($_REQUEST["b"])
	 {
	   case 0: 
	     $myLayout->tab_draw("Konfiguration");
	     $myLayout->workarea_start_draw();
         $html = $myLayout->workarea_form_text("","bez",$row["lay_bez"]);
	     $myLayout->workarea_row_draw("Bezeichnung",$html);
	     $html=  $myLayout->workarea_form_textarea("","description",$row["lay_description"],8);
	     $myLayout->workarea_row_draw("Beschreibung",$html);
		 
		 // Platzhalter
		 $myPT->startbuffer();
		 
		 $sql = "SELECT * FROM componentgroup ORDER BY cog_pos";
		 $rs = $myDB->query($sql);
		 $toolkits = Array();
		 while ($row=mysql_fetch_array($rs))
		 {
	       $toolkits[$row["cog_id"]]=$row["cog_bez"];
		 }
		 
	     $sql = "SELECT * FROM layout_block WHERE lay_id = " . $id . " ORDER BY lay_blocknr";
		 $rs = $myDB->query($sql);
		 $c= mysql_num_rows($rs);
		 ?>
		 <table border="0" cellspacing="2" cellpadding="2">
		 <?
		 if ($c==0){
		 ?>			
		 <tr><td>
					 <input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="block_plus"> Platzhalter einf&uuml;gen</td></tr>
		 <?
		 }else{
		 ?>		 
		 
		 <tr><td>Smarty-Zugriff&nbsp;&nbsp;&nbsp;</td><td>Bezeichnung </td><td>Bausteingruppe&nbsp;</td><td>Context</td><td>&nbsp;</td></tr>
		 <?
		
		 while ($row_block=mysql_fetch_array($rs))
		 {
		   $identifier = $id . "_". $row_block["lay_blocknr"];
		 ?>
		 <tr>
		 <td><b>{$pt_block<?=$row_block["lay_blocknr"]?>}</b>&nbsp;</td>
		 <td>
		 <input name="<?=$identifier?>_bez" type="text" class="input" value="<?=$row_block["lay_blockbez"]?>" size="20">&nbsp;
         </td>
         <td>
		 <select name="<?=$identifier?>_toolkit" class="input" style="width:100px">
         <?
		  foreach ($toolkits as $key => $val)
          {
		    if ($key==$row_block["cog_id"])
			{
            ?>
              <option value="<?=$key?>" selected><?=$val?></option>
            <?					   
 		    }
 	        else
			{
            ?>
              <option value="<?=$key?>"><?=$val?></option>
            <?
		    }
           }
           ?>
                     </select>&nbsp;</td>
					 <td>
					 <select name="<?=$identifier?>_style" class="input" style="width:35px">
					 <?
					 $options = "";
					 for ($i=1;$i<=9;$i++)
					 {
					   $options[$i]=$i;
					 }
					 echo $myAdm->buildOptionsByNamedArray($options,$row_block["lay_context"]);
					 ?>
					 </select>
					 </td>
					 <td><input type="image" src="img/b_minus_b.gif" alt="Platzhalter l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?=$identifier?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?=$identifier?>_plus"></td>
					 </tr>
					 <?
					 }
					 ?>
					 
		 <?}
		 ?>
		 </table>
		 <?
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw("Platzhalter",$html);
		 
 		 // Includes
		 $myPT->startbuffer();
		 ?>
					 <?
					 $sql = "SELECT * FROM include WHERE inc_usage_layout = 1 ORDER BY inc_rubrik,inc_bez";
					 $rs = $myDB->query($sql);
					 $includes = Array();
					 $rubrik = "";
					 while ($row=mysql_fetch_array($rs))
					 {
					   if ($row["inc_rubrik"]!=$rubrik)
					   {
					     $rubrik = $row["inc_rubrik"];
						 $includes[$row["inc_id"] ."a"]="- - - - - - - - - - - - - - - - - - - - - - - -";
						 $includes[$row["inc_id"] ."b"]= $rubrik . ":";						 
					   }
					   $includes[$row["inc_id"]]= "- " . $row["inc_bez"];
					 }
					 ?>					 

					 <?
					 $sql = "SELECT * FROM layout_include WHERE lay_id = " . $id . " ORDER BY lay_includenr";
					 $rs = $myDB->query($sql);
					 $c= mysql_num_rows($rs);
					 ?>
					 <table border="0" cellspacing="2" cellpadding="2">
					 <?
					 if ($c==0){
					 ?>
					 <tr><td>
					 <input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="include_plus"> Include einf&uuml;gen</td></tr>
					 <?
					 }else{
					 ?>
					<table border="0" cellspacing="2" cellpadding="2"> <tr><td>Smarty-Zugriff&nbsp;&nbsp;&nbsp;</td><td>Bezeichnung</td><td>Cache</td><td>&nbsp;</td></tr>
					 <?}?>					 
					 <?
					 while ($row_inc=mysql_fetch_array($rs))
					 {
					   $identifier = $id . "_inc". $row_inc["lay_includenr"];
					 ?>
					 <tr>
					 <td><b>{$pt_include<?=$row_inc["lay_includenr"]?>}</b>&nbsp;</td>
                     <td><select name="<?=$identifier?>_include" class="input">
                     <?
					 foreach ($includes as $key => $val)
                     {
					   if ($key==$row_inc["inc_id"])
					   {
                       ?>
                       <option value="<?=$key?>" selected><?=$val?></option>
                       <?					   
					   }
					   else
					   {
                       ?>
                       <option value="<?=$key?>"><?=$val?></option>
                       <?
					   }
                     }
                     ?>
                     </select>&nbsp;</td>
					 <td>
					 <select name="<?=$identifier?>_cache" class="input">
					 <option value="1" <?if ($row_inc["lay_includecache"]==1){echo "selected";}?>>wie Seite</option>
					 <option value="0" <?if ($row_inc["lay_includecache"]==0){echo "selected";}?>>nie</option>
					 </select>
					 </td>
					 <td>
					 <input type="image" src="img/b_minus_b.gif" alt="Platzhalter l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?=$identifier?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?=$identifier?>_plus"></td>
					 </tr>
					 <?
					 }
					 ?>
					 </table>
		 
		 <?
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw("Includes",$html);		 
	     $sql = "SELECT * FROM layout_pagegroup WHERE lay_id = " . $id;
		 $rs = $myDB->query($sql);
		 $_groups = Array();
		 if (mysql_num_rows($rs)==0)
		 {
		   $html='<input type="radio" name="allgroups" value="1" onclick="selectallgroups();" checked> alle &nbsp;<input type="radio" name="allgroups" value="0" onclick="selectedgroups();"> selektiv:<br><br>';		   
		 }
		 else
		 {
		   $html='<input type="radio" name="allgroups" value="1" onclick="selectallgroups();"> alle &nbsp;<input type="radio" name="allgroups" value="0" onclick="selectedgroups();" checked > selektiv:<br><br>';		 
           while ($row=mysql_fetch_array($rs))
		   {
		     $_groups[]=$row["grp_id"];
		   }
		 }
		

 
		 $sql = "SELECT * FROM pagegroup ORDER BY grp_bez";
		 $rs = $myDB->query($sql);
		 $js1="";
		 while ($row=mysql_fetch_array($rs))
		 {
		   $checked="";
		   if (in_array($row["grp_id"],$_groups)){$checked="checked";}

		   
		   $html.='<input type="checkbox" name="grp_'.$row["grp_id"].'" value="1" '. $checked.' onclick="selectedgroups2()"> '.$row["grp_bez"]."<br>";
		   $js1.= "document.forms.editform.grp_".$row["grp_id"].".checked=false;";
		 }
		 $myPT->startBuffer();
		 ?>
		 <script language="JavaScript">
		 function selectallgroups()
		 {
		   <?=$js1?>
		 }
		 function selectedgroups()
		 {
		   <?=$js1?>
		 }
		 function selectedgroups2()
		 {
		   document.forms.editform.allgroups[0].checked=0;
		   document.forms.editform.allgroups[1].checked=1;
		 }		 
		 </script>
		 <?
		 $js = $myPT->stopBuffer();
         $html .=$js;
		 $myLayout->workarea_row_draw("Seitengruppen",$html);	
		 break;
		 
	   case 1:
	     $myLayout->tab_draw("Templates");
	     $myLayout->workarea_start_draw();
		 $scriptname = "page_templates/"  .sprintf("%04.0f", $id) . ".normal.tpl";
		 $scriptname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "normal", "templates/");
		 ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody">Normalansicht:<br><br>
			<strong><?=$scriptname;?></strong><br>
			<?
			$scriptname = APPPATH .$scriptname;
			echo $myLayout->form_HTMLTextArea("template_normal",$scriptname,80,20,"HTML");
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
		 <?
		 $scriptname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "print", "templates/");
		 ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody">Druckansicht:<br><br>
			<strong><?=$scriptname;?></strong><br>
			<?
			$scriptname = APPPATH . $scriptname;
			echo $myLayout->form_HTMLTextArea("template_print",$scriptname,80,20,"HTML");
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
		 <?		 
		 break;

		 case 2:
		    $myLayout->tab_draw("Info");
	        $myLayout->workarea_start_draw();			
			$myPT->startBuffer();
			?>
			 <?
					 $sql = "SELECT COUNT(*) AS C FROM pageversion WHERE lay_id = " . $id;
					 $rs = $myDB->query($sql);
					 $row = mysql_fetch_array($rs);
					 $c = $row["C"];
					 if ($c==0)
					 {
					 ?>
					 Das layout wird in keiner Seite genutzt.
					 <?
					 }else{
					 ?>
					 Das layout wird in <?=$row["C"]?> Seiten verwendet:
					 <br>
					 <br>
					 <?
					 $sql = "SELECT * FROM pageversion LEFT JOIN page ON pageversion.pag_id = page.pag_id WHERE lay_id = " . $id . " ORDER BY pag_bez";
 					 $rs = $myDB->query($sql);
					 while ($row = mysql_fetch_array($rs))
					 {
					   //print_r ($row);
					   echo "-> ". $row["pag_id"].".".$row["ver_nr"] .": ".$row["pag_bez"] ." (". $row["ver_bez"] . ")<br>";
					 }
					 ?>
                     <?}?>
			<?
		    $html = $myPT->stopBuffer();
		    $myLayout->workarea_row_draw("",$html);
		 break;		 
	  }
	 
	 ?>
	 
	 <?
	   // Abschlusszeile
       $sql = "SELECT COUNT(*) AS C FROM pageversion WHERE lay_id = " . $id;
	   $rs = $myDB->query($sql);
	   $row = mysql_fetch_array($rs);
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?if ($row["C"]==0){?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Dieses Layout wirklich l&ouml;schen?')">&nbsp;&nbsp;<?}?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























