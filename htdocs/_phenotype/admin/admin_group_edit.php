<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
$myPT->loadTMX("Admin");
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
$id = $myRequest->getI("id");
?>
<?php
$myAdm->header("Admin");
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
$myAdm->explorer_prepare("Admin","Seitengruppen");
$myAdm->explorer_set("grp_id",$id);
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
$sql = "SELECT * FROM pagegroup WHERE grp_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
?>
    <form action="admin_group_update.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id ?>">	
	<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> Seitengruppe / <?php echo $row["grp_bez"] ?></td>
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
	<?php
	$myLayout->tab_new();
	$url = "admin_group_edit.php?id=" .$id ."&b=0";
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
	$myPT->startBuffer();
	?>
	<select name="grp_smarturl_schema" class="input" style="width:250px">
  <?php
  $_options = Array (
  1=>"Voller Pfad  (ggf. Sprachkennzeichnung)",
  2=>"Voller Pfad  (keine Sprachkennzeichnung)",
  3=>"Subpfad  (ggf. Sprachkennzeichnung)",
  4=>"Subpfad  (keine Sprachkennzeichnung)",
  5=>"Seitentitel  (ggf. Sprachkennzeichnung)",
  6=>"Seitentitel  (keine Sprachkennzeichnung)",
  7=>"index.php  (ggf. Sprachkennzeichnung)",
  8=>"index.php  (keine Sprachkennzeichnung)"
  );
  
  foreach ($_options AS $k => $v)
  {
    $selected='';
    if ($row["grp_smarturl_schema"]==$k)
    {
      $selected = 'selected="selected"';
    }
  ?>
  <option value="<?php echo $k ?>" <?php echo $selected?>><?php echo codeH($v) ?></option>
  <?php
  }
  ?><br><br>
	<?php

	$html = $myPT->stopBuffer();
	$myLayout->workarea_row_draw("smartURL-Schema",$html);


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
            <td align="right" class="windowFooterWhite"><?php if ($row["C"]==0){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="L�schen" onclick="javascript:return confirm('Diese Seitengruppe wirklich l&ouml;schen?')">&nbsp;&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























