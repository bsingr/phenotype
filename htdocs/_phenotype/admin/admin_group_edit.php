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
$myAdm->header(locale("Admin"));
?>
<body>
<?php
$myAdm->menu(locale("Config"));
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Admin"),locale("Pagegroups"));
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
            <td class="windowTitle"><?php echo $id ?> <?php echo localeH("Pagegroup");?> / <?php echo $row["grp_bez"] ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=10" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a></td>
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
	$myLayout->tab_addEntry(locale("Config"),$url,"b_konfig.gif");
	$myLayout->tab_draw(locale("Config"));
	$myLayout->workarea_start_draw();
	$html = $myLayout->workarea_form_text("","bez",$row["grp_bez"]);
	$myLayout->workarea_row_draw(locale("Name"),$html);
	$html=  $myLayout->workarea_form_textarea("",locale("Description"),$row["grp_description"],8);
	$myLayout->workarea_row_draw(locale("Description"),$html);
	$checked="";
	if ($row["grp_multilanguage"]==1){$checked="checked";}
	$html='<input type="checkbox" value="1" name="multilanguage" '.$checked .'>&nbsp;<b>'.locale("yes").'</b><br>';
	$myLayout->workarea_row_draw(locale("Multi language"),$html);
	$myPT->startBuffer();
	?>
	<select name="grp_smarturl_schema" class="input" style="width:250px">
  <?php
  $_options = Array (
  1=>locale("Full path (possibly language tokens)"),
  2=>locale("Full path (no language tokens)"),
  3=>locale("Sub path (possibly language tokens)"),
  4=>locale("Sub path (no language tokens)"),
  5=>locale("Page titles (possibly language tokens)"),
  6=>locale("Page titles (no language tokens)"),
  7=>locale("index.php (possibly language tokens)"),
  8=>locale("index.php (no language tokens)")
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
	$myLayout->workarea_row_draw(locale("smartURL-Schema"),$html);


	$checked="";
	if ($row["grp_statistic"]==1){$checked="checked";}
	$html='<input type="checkbox" value="1" name="statistic" '.$checked .'>&nbsp;<b>'.locale("count page impressions").'</b><br>';
	$myLayout->workarea_row_draw(locale("Stats"),$html);

	// Abschlusszeile
	$sql = "SELECT COUNT(*) AS C FROM page WHERE grp_id = " . $id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?php if ($row["C"]==0){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="<?php echo localeH("Delete");?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this pagegroup?");?>')">&nbsp;&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save");?>">&nbsp;&nbsp;</td>
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
























