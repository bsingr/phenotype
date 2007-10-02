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
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
$id = $myRequest->getI("id");
?>
<?php
$myAdm->header("Admin");
?>
<body>
<?php
$myAdm->menu("Admin");
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Admin","Aktionen");
$myAdm->explorer_set("act_id",$id);
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
$sql = "SELECT * FROM action WHERE act_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="admin_action_update.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id ?>">	
	<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> Aktion / <?php echo $row["act_bez"] ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=9" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	 $url = "admin_action_edit.php?id=" .$id ."&b=0";	 
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $myLayout->tab_draw("Konfiguration");
     $myLayout->workarea_start_draw();
     $html = $myLayout->workarea_form_text("","bez",$row["act_bez"]);
	 $myLayout->workarea_row_draw("Bezeichnung",$html);		
     $html=  $myLayout->workarea_form_textarea("","description",$row["act_description"],8);
     $myLayout->workarea_row_draw("Beschreibung",$html);	  
     /* Moegliche Erweiterung
	 $myPT->startBuffer();
	 ?>
	 Datum:<br>
	 <input type="radio" name="datum" value="1">
	 <?php
	 $_schedule = Array();
	 if ($row["act_schedule"]!=""){$_schedule = unserialize($row["act_schedule"]);}
	 $_days = Array("","Mo","Di","Mi","Do","Fr","Sa","So");
	 for ($i=1;$i<=7;$i++)
	 {
	 ?>
	 <input type="checkbox" value="<?php echo $i ?>" name="<?php echo $_days[$i] ?>"><?php echo $_days[$i] ?>&nbsp;
	 <?php
	 }
	 ?>
	 <input type="radio" name="datum" value="2">
	 <select>
	 <option value="0"></option>
	 <option value="1">t&ouml;glich</option>
	 <option value="2">werkt&auml;glich</option>
	 <option value="3">gerade Tage</option>
	 <option value="4">ungerade Tage</option>
	 <option value="5">Wochenanfang</option>
	 <option value="6">Montasanfang</option>
	 <br>Uhrzeit:<br>
	 Stunde <select><option>jede</option>
	 <?php
	 for ($i=0;$i<=23;$i++)
	 {
	 ?>
	 <option><?php echo $i ?></option>
	 <?php
	 }
	 ?>
	 </select>&nbsp; Minute <select><option>jede</option>
	  <?php
	 for ($i=0;$i<=59;$i++)
	 {
	 ?>
	 <option><?php echo $i ?></option>
	 <?php
	 }
	 ?>
	 </select>
	 <?php
	 $html = $myPT->stopBuffer();
	 $myLayout->workarea_row_draw("Zeitschema",$html);	
	 */
	 $scriptname = "actions/PhenotypeAction_"  .$id . ".class.php";	
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
			</table>	 
	<?php
	 // Status
	 $myLayout->workarea_whiteline();
	 $myPT->startBuffer();
	 ?>
	 <input name="status" type="checkbox" value="1" <?php if ($row["act_status"]=="1") echo"checked"; ?>> online. <br>
	 <br><?php if ($row["act_lastrun"]!=0){ ?>Letzter Lauf: <?php echo date('d.m.Y H:i',$row["act_lastrun"]) ?><br><?php } ?>
	 <?php if ($row["act_nextrun"]!=0){ ?>N&auml;chster Lauf: <?php echo date('d.m.Y H:i',$row["act_nextrun"]) ?><?php }else{ ?>N&auml;chster Lauf: sofort<?php } ?>
     <?php
	 $html = $myPT->stopBuffer();
	 $myLayout->workarea_row_draw("Status",$html);		
	 ?>	
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="reset" type="submit" class="buttonWhite" style="width:102px" value="Reset" onclick="javascript:return confirm('Diese Aktion wirklich zurücksetzen?')">&nbsp;&nbsp;<input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diese Aktion wirklich l&ouml;schen?')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























