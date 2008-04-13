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
$myPT->loadTMX("Config");
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];
?>
<?php
$myAdm->header(locale("Admin"));
?>
<body>
<?php
$myAdm->menu(locale("Admin"));
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Admin"),locale("Layout"));
$myAdm->explorer_set("lay_id",$id);
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
$sql = "SELECT * FROM layout WHERE lay_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="layout_update.php" method="post" name="editform">
	<input type="hidden" name="id" value="<?php echo $id ?>">	
	<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> <?php echo localeH("Layout");?> / <?php echo $row["lay_bez"] ?></td>
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
	<?php
	 $myLayout->tab_new();
	 $url = "layout_edit.php?id=" .$id ."&b=0";	 
	 $myLayout->tab_addEntry(locale("Config"),$url,"b_konfig.gif");
	 $url = "layout_edit.php?id=" .$id ."&b=1";	 
	 $myLayout->tab_addEntry(locale("Templates"),$url,"b_template.gif");


	 $url = "layout_edit.php?id=" .$id ."&b=2";	 
	 $myLayout->tab_addEntry(locale("Usage"),$url,"b_utilisation.gif");	
	 
	 switch ($_REQUEST["b"])
	 {
	   case 0: 
	     $myLayout->tab_draw(locale("Config"));
	     $myLayout->workarea_start_draw();
         $html = $myLayout->workarea_form_text("","bez",$row["lay_bez"]);
	     $myLayout->workarea_row_draw(locale("Name"),$html);
	     $html=  $myLayout->workarea_form_textarea("","description",$row["lay_description"],8);
	     $myLayout->workarea_row_draw(locale("Description"),$html);
		 
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
		 <?php
		 if ($c==0){
		 ?>			
		 <tr><td>
					 <input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="block_plus"> <?php echo localeH("Add placeholder");?></td></tr>
		 <?php
		 }else{
		 ?>		 
		 
		 <tr><td><?php echo localeH("Smarty-Access");?>&nbsp;&nbsp;&nbsp;</td><td><?php echo localeH("Name");?> </td><td><?php echo localeH("Component group");?>&nbsp;</td><td>Context</td><td>&nbsp;</td></tr>
		 <?php
		
		 while ($row_block=mysql_fetch_array($rs))
		 {
		   $identifier = $id . "_". $row_block["lay_blocknr"];
		 ?>
		 <tr>
		 <td><b>{$pt_block<?php echo $row_block["lay_blocknr"] ?>}</b>&nbsp;</td>
		 <td>
		 <input name="<?php echo $identifier ?>_bez" type="text" class="input" value="<?php echo $row_block["lay_blockbez"] ?>" size="20">&nbsp;
         </td>
         <td>
		 <select name="<?php echo $identifier ?>_toolkit" class="input" style="width:100px">
         <?php
		  foreach ($toolkits as $key => $val)
          {
		    if ($key==$row_block["cog_id"])
			{
            ?>
              <option value="<?php echo $key ?>" selected><?php echo $val ?></option>
            <?php					   
 		    }
 	        else
			{
            ?>
              <option value="<?php echo $key ?>"><?php echo $val ?></option>
            <?php
		    }
           }
           ?>
                     </select>&nbsp;</td>
					 <td>
					 <select name="<?php echo $identifier ?>_style" class="input" style="width:35px">
					 <?php
					 $options = "";
					 for ($i=1;$i<=9;$i++)
					 {
					   $options[$i]=$i;
					 }
					 echo $myAdm->buildOptionsByNamedArray($options,$row_block["lay_context"]);
					 ?>
					 </select>
					 </td>
					 <td><input type="image" src="img/b_minus_b.gif" alt="<?php echo localeH("Remove placeholder");?>" width="18" height="18" border="0" align="absmiddle" value="-" name="<?php echo $identifier ?>_minus"><input type="image" src="img/b_plus_b.gif" alt="<?php echo localeH("Remove placeholder");?>" width="18" height="18" border="0" align="absmiddle" value="+" name="<?php echo $identifier ?>_plus"></td>
					 </tr>
					 <?php
					 }
					 ?>
					 
		 <?php }
		 ?>
		 </table>
		 <?php
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw(locale("Placeholder"),$html);
		 
 		 // Includes
		 $myPT->startbuffer();
		 ?>
					 <?php
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

					 <?php
					 $sql = "SELECT * FROM layout_include WHERE lay_id = " . $id . " ORDER BY lay_includenr";
					 $rs = $myDB->query($sql);
					 $c= mysql_num_rows($rs);
					 ?>
					 <table border="0" cellspacing="2" cellpadding="2">
					 <?php
					 if ($c==0){
					 ?>
					 <tr><td>
					 <input type="image" src="img/b_plus_b.gif" alt="Platzhalter hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="include_plus"> <?php echo localeH("Add inlcude");?></td></tr>
					 <?php
					 }else{
					 ?>
					<table border="0" cellspacing="2" cellpadding="2"> <tr><td><?php echo localeH("Smarty-Access");?>&nbsp;&nbsp;&nbsp;</td><td><?php echo localeH("Name");?></td><td><?php echo localeH("Cache");?></td><td>&nbsp;</td></tr>
					 <?php } ?>					 
					 <?php
					 while ($row_inc=mysql_fetch_array($rs))
					 {
					   $identifier = $id . "_inc". $row_inc["lay_includenr"];
					 ?>
					 <tr>
					 <td><b>{$pt_include<?php echo $row_inc["lay_includenr"] ?>}</b>&nbsp;</td>
                     <td><select name="<?php echo $identifier ?>_include" class="input">
                     <?php
					 foreach ($includes as $key => $val)
                     {
					   if ($key==$row_inc["inc_id"])
					   {
                       ?>
                       <option value="<?php echo $key ?>" selected><?php echo $val ?></option>
                       <?php					   
					   }
					   else
					   {
                       ?>
                       <option value="<?php echo $key ?>"><?php echo $val ?></option>
                       <?php
					   }
                     }
                     ?>
                     </select>&nbsp;</td>
					 <td>
					 <select name="<?php echo $identifier ?>_cache" class="input">
					 <option value="1" <?php if ($row_inc["lay_includecache"]==1){echo "selected";} ?>><?php echo localeH("same like page");?></option>
					 <option value="0" <?php if ($row_inc["lay_includecache"]==0){echo "selected";} ?>><?php echo localeH("never");?></option>
					 </select>
					 </td>
					 <td>
					 <input type="image" src="img/b_minus_b.gif" alt="<?php echo localeH("Remove include");?>" width="18" height="18" border="0" align="absmiddle" value="-" name="<?php echo $identifier ?>_minus"><input type="image" src="img/b_plus_b.gif" alt="<?php echo localeH("Remove include");?>" width="18" height="18" border="0" align="absmiddle" value="+" name="<?php echo $identifier ?>_plus"></td>
					 </tr>
					 <?php
					 }
					 ?>
					 </table>
		 
		 <?php
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw("Includes",$html);		 
	     $sql = "SELECT * FROM layout_pagegroup WHERE lay_id = " . $id;
		 $rs = $myDB->query($sql);
		 $_groups = Array();
		 if (mysql_num_rows($rs)==0)
		 {
		   $html='<input type="radio" name="allgroups" value="1" onclick="selectallgroups();" checked> '.localeH("all").' &nbsp;<input type="radio" name="allgroups" value="0" onclick="selectedgroups();"> '.localeH("selective").':<br><br>';		   
		 }
		 else
		 {
		   $html='<input type="radio" name="allgroups" value="1" onclick="selectallgroups();"> '.localeH("all").' &nbsp;<input type="radio" name="allgroups" value="0" onclick="selectedgroups();" checked > '.localeH("selective").':<br><br>';		 
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
		   <?php echo $js1 ?>
		 }
		 function selectedgroups()
		 {
		   <?php echo $js1 ?>
		 }
		 function selectedgroups2()
		 {
		   document.forms.editform.allgroups[0].checked=0;
		   document.forms.editform.allgroups[1].checked=1;
		 }		 
		 </script>
		 <?php
		 $js = $myPT->stopBuffer();
         $html .=$js;
		 $myLayout->workarea_row_draw(locale("Pagegroups"),$html);	
		 break;
		 
	   case 1:
	     $myLayout->tab_draw(locale("Templates"));
	     $myLayout->workarea_start_draw();
		 $scriptname = "page_templates/"  .sprintf("%04.0f", $id) . ".normal.tpl";
		 $scriptname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "normal", "templates/");
		 ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><?php echo localeH("Default view");?>:<br><br>
			<strong><?php echo $scriptname; ?></strong><br>
			<?php
			$scriptname = APPPATH .$scriptname;
			echo $myLayout->form_HTMLTextArea("template_normal",$scriptname,80,20,"HTML");
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
		 <?php
		 $scriptname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "print", "templates/");
		 ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><?php echo localeH("Print view");?>:<br><br>
			<strong><?php echo $scriptname; ?></strong><br>
			<?php
			$scriptname = APPPATH . $scriptname;
			echo $myLayout->form_HTMLTextArea("template_print",$scriptname,80,20,"HTML");
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
		 <?php		 
		 break;

		 case 2:
		    $myLayout->tab_draw(locale("Usage"));
	        $myLayout->workarea_start_draw();			
			$myPT->startBuffer();
			?>
			 <?php
					 $sql = "SELECT COUNT(*) AS C FROM pageversion WHERE lay_id = " . $id;
					 $rs = $myDB->query($sql);
					 $row = mysql_fetch_array($rs);
					 $c = $row["C"];
					 if ($c==0)
					 {
					 ?>
					 <?php echo localeH("This Layout is not used in any page.");?>
					 <?php
					 }else{
					 ?>
					 <?php echo localeH("This layout is used in %1 pages",array($row["C"]));?>:
					 <br>
					 <br>
					 <?php
					 $sql = "SELECT * FROM pageversion LEFT JOIN page ON pageversion.pag_id = page.pag_id WHERE lay_id = " . $id . " ORDER BY pag_bez";
 					 $rs = $myDB->query($sql);
					 while ($row = mysql_fetch_array($rs))
					 {
					   //print_r ($row);
					   echo "-> ". $row["pag_id"].".".$row["ver_nr"] .": ".$row["pag_bez"] ." (". $row["ver_bez"] . ")<br>";
					 }
					 ?>
                     <?php } ?>
			<?php
		    $html = $myPT->stopBuffer();
		    $myLayout->workarea_row_draw("",$html);
		 break;		 
	  }
	 
	 ?>
	 
	 <?php
	   // Abschlusszeile
       $sql = "SELECT COUNT(*) AS C FROM pageversion WHERE lay_id = " . $id;
	   $rs = $myDB->query($sql);
	   $row = mysql_fetch_array($rs);
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?php if ($row["C"]==0){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="<?php echo localeH("Delete");?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this layout?");?>')">&nbsp;&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save");?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
	 <?php
	 $myLayout->workarea_stop_draw();
	?>
	</form>

	
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
























