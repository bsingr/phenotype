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
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];

?>
<?php
$myAdm->header("Konfiguration");
?>
<body>
<?php
$myAdm->menu("Konfiguration");
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Konfiguration","Extras");
$myAdm->explorer_set("ext_id",$id);
$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?php
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
$sql = "SELECT * FROM extra WHERE ext_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="extra_update.php" method="post">
	<input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">	
	<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">		
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> <?php echo $row["ext_bez"] ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=16" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	 $url = "extra_edit.php?id=" .$id ."&b=0";	 
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $url = "extra_edit.php?id=" .$id ."&b=1";	  
	 $myLayout->tab_addEntry("Skript",$url,"b_script.gif");
	 $url = "extra_edit.php?id=" .$id ."&b=2";	  
	 $sql = "SELECT * FROM extra_template WHERE ext_id = " . $id . " ORDER BY tpl_bez";
	 $rs = $myDB->query($sql);
	 $c= mysql_num_rows($rs);
	 if ($c>0)
	 {
	   $myLayout->tab_addEntry("Templates",$url,"b_template.gif");
	 }
	 $url = "extra_edit.php?id=" .$id ."&b=3";	 
	 //$myLayout->tab_addEntry("Info",$url,"b_utilisation.gif");		
	 
	 switch ($_REQUEST["b"])
	 {
	   case 0: 
	     $myLayout->tab_draw("Konfiguration");
	     $myLayout->workarea_start_draw();
         $html = $myLayout->workarea_form_text("","bez",$row["ext_bez"]);
	     $myLayout->workarea_row_draw("Bezeichnung",$html);
		 $html = $myLayout->workarea_form_text("","rubrik",$row["ext_rubrik"]);
	     $myLayout->workarea_row_draw("Rubrik",$html);
	     $html=  $myLayout->workarea_form_textarea("","description",$row["ext_description"],8);
	     $myLayout->workarea_row_draw("Beschreibung",$html);
		 
		 $sql = "SELECT * FROM extra_template WHERE ext_id = " . $id . " ORDER BY tpl_bez";
		 $rs = $myDB->query($sql);
		 $c= mysql_num_rows($rs);
 
		 $myPT->startBuffer();
		 if ($c==0)
		 {
		 
         ?>
			 <input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="ttp_plus"> Template einf&uuml;gen<br>
		 <?php
		 }
		 while ($row_ttp=mysql_fetch_array($rs))
		 {
		   $identifier = "ttp_". $row_ttp["tpl_id"];
		 ?>
		 <strong>$</strong><input name="<?php echo $identifier ?>_bez" type="text" class="input" style="width: 150px" value="<?php echo $row_ttp["tpl_bez"] ?>" size="30">&nbsp;
<input type="image" src="img/b_minus_b.gif" alt="Template-Zugriff l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?php echo $identifier ?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?php echo $identifier ?>_plus"><br>
		 <?php
		 }
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw("Templates",$html);
		 break;
		 
	   case 1:
	     $myLayout->tab_draw("Skript");
	     $myLayout->workarea_start_draw();
		 $scriptname = "extras/PhenotypeExtra_"  . $id . ".class.php";	
		 ?>
			 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><strong><?php echo $scriptname; ?></strong><br>
			<?php
			$scriptname = APPPATH . $scriptname;
			echo $myLayout->form_HTMLTextArea("skript",$scriptname,80,30,"PHP");
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
		    $myLayout->tab_draw("Templates");
	       $myLayout->workarea_start_draw();

					 $sql = "SELECT * FROM extra_template WHERE ext_id = " . $id . " ORDER BY tpl_bez";
					 $rs = $myDB->query($sql);
					 $c= mysql_num_rows($rs);
					 ?>
					 <?php

					 while ($row_ttp=mysql_fetch_array($rs))
					 {
					   $identifier = "ttp_". $row_ttp["tpl_id"];
					 ?>
					 <br>
					 <table width="660" border="0" cellpadding="0" cellspacing="0">
                      <?php
					  $scriptname = $myPT->getTemplateFileName(PT_CFG_EXTRA, $id, $row_ttp["tpl_id"],"templates/");
                      ?>
					  <tr>
					  <td width="10">&nbsp;</td>
					  <td  width="300"><strong><?php echo $scriptname; ?></strong></td>
					  <td width="335" align="right" >
					  <strong>$</strong><input name="<?php echo $identifier ?>_bez" type="text" class="input" style="width: 150px" value="<?php echo $row_ttp["tpl_bez"] ?>" size="30">&nbsp;
<input type="image" src="img/b_minus_b.gif" alt="Template-Zugriff l&ouml;schen" width="18" height="18" border="0" align="absmiddle" value="-" name="<?php echo $identifier ?>_minus"><input type="image" src="img/b_plus_b.gif" alt="Template-Zugriff hinzuf&uuml;gen" width="18" height="18" border="0" align="absmiddle" value="+" name="<?php echo $identifier ?>_plus">
                      </td>
					  <td width="15">&nbsp;</td>
					  </tr>
          <tr>
		    <td>&nbsp;</td>
            <td colspan="3">
					  <?php
					  $scriptname =  APPPATH .$scriptname;
					  $myAdm->buildHTMLTextArea($identifier. "_template",$scriptname,80,15,"HTML");
                      ?>
					  </td>
					  </tr>
					  </table><br><br>
					  <?php
					  }
		 break;		 
		 
		 case 3:
		    $myLayout->tab_draw("Info");
	        $myLayout->workarea_start_draw();			
			?>
			<?php
            // Kommt noch
		 break;				 
	}
	?>

		 <?php
	   // Abschlusszeile
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Dieses Extra wirklich l&ouml;schen?')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























