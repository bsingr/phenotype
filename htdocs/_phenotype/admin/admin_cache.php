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
$myAdm->explorer_prepare(locale("Admin"),locale("Cache"));
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
<form action="admin_action.php" method="post">
<input type="hidden" name="action_id" value="1">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("Clear page cache");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?php
	$myLayout->workarea_start_draw();
	$myPT->startBuffer();
	
	$sql1 = "SELECT * FROM pagegroup ORDER by grp_bez";
	$rs1 = $myDB->query($sql1);
	?>
	<?php
	
	while ($row1=mysql_fetch_array($rs1))
	{
	?>
	<?php echo $row1["grp_bez"] ?> :<br>
	<input type="checkbox" name="grp_id_<?php echo $row1["grp_id"] ?>" value="1" checked>&nbsp;
    <select name="pag_id_grp_id_<?php echo $row1["grp_id"] ?>" class="input" style="width:250px">
	<option value="0"><?php echo localeH("* all pages *");?></option>
	 <?php
	 $sql = "SELECT * FROM page WHERE grp_id=".$row1["grp_id"]." ORDER BY pag_bez";
	 $rs2 = $myDB->query($sql);
     while ($row_page = mysql_fetch_array($rs2))
	 {
	 ?>
	 <option value="<?php echo $row_page["pag_id"] ?>" ><?php echo $row_page["pag_bez"] ?></option>
	 <?php
	 }
	 ?>
	 </select><br><br>
					 
	<?php
	}
	$html = $myPT->stopBuffer(); 
	$myLayout->workarea_row_draw(locale("Pagegroups"),$html);	
	?>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" onclick="javascript:return confirm('<?php echo localeH("Clear page cache?");?>');" value="<?php echo localeH("Execute");?>"></td>
          </tr>
        </table>
		<?php	$myLayout->workarea_stop_draw();
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























