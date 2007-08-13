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
$id = $myRequest->getI("id");
?>
<?
$myAdm->header("Admin");
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
$myAdm->explorer_prepare("Admin","Seitengruppen");
$myAdm->explorer_set("grp_id",$id);
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
$sql = "SELECT * FROM pagegroup WHERE grp_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="admin_group_update.php" method="post">
	<input type="hidden" name="id" value="<?=$id?>">	
	<input type="hidden" name="b" value="<?=$_REQUEST["b"]?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?=$id?> Seitengruppe / <?=$row["grp_bez"]?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=10" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	 $url = "toolkit_edit.php?id=" .$id ."&b=0";	 
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $myLayout->tab_draw("Konfiguration");
     $myLayout->workarea_start_draw();
     $html = $myLayout->workarea_form_text("","bez",$row["grp_bez"]);
	 $myLayout->workarea_row_draw("Bezeichnung",$html);		
     $html=  $myLayout->workarea_form_textarea("","description",$row["grp_description"],8);
     $myLayout->workarea_row_draw("Beschreibung",$html);
     $checked="";
	 if ($row["grp_multilanguage"]==1){$checked="checked";}
     $html='<input type="checkbox" value="1" name="multilanguage" '.$checked .'>&nbsp;<b>ja</b><br>';
	 $myLayout->workarea_row_draw("Mehrsprachigkeit",$html);	  
     $checked="";
	 if ($row["grp_statistic"]==1){$checked="checked";}
     $html='<input type="checkbox" value="1" name="statistic" '.$checked .'>&nbsp;<b>Seitenaufrufe z&auml;hlen</b><br>';
	 $myLayout->workarea_row_draw("Statistik",$html);
	   // Abschlusszeile
        $sql = "SELECT COUNT(*) AS C FROM page WHERE grp_id = " . $id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?if ($row["C"]==0){?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diese Seitengruppe wirklich l&ouml;schen?')">&nbsp;&nbsp;<?}?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























