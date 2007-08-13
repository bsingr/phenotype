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
$myAdm->explorer_prepare("Admin","Cache");
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
?>
<form action="admin_action.php" method="post">
<input type="hidden" name="action_id" value="1">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Seitencache zur&uuml;cksetzen </strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?
	$myLayout->workarea_start_draw();
	$myPT->startBuffer();
	
	$sql1 = "SELECT * FROM pagegroup ORDER by grp_bez";
	$rs1 = $myDB->query($sql1);
	?>
	<?
	
	while ($row1=mysql_fetch_array($rs1))
	{
	?>
	<?=$row1["grp_bez"]?> :<br>
	<input type="checkbox" name="grp_id_<?=$row1["grp_id"]?>" value="1" checked>&nbsp;
    <select name="pag_id_grp_id_<?=$row1["grp_id"]?>" class="input" style="width:250px">
	<option value="0">* alle Seiten *</option>
	 <?
	 $sql = "SELECT * FROM page WHERE grp_id=".$row1["grp_id"]." ORDER BY pag_bez";
	 $rs2 = $myDB->query($sql);
     while ($row_page = mysql_fetch_array($rs2))
	 {
	 ?>
	 <option value="<?=$row_page["pag_id"]?>" ><?=$row_page["pag_bez"]?></option>
	 <?
	 }
	 ?>
	 </select><br><br>
					 
	<?
	}
	$html = $myPT->stopBuffer(); 
	$myLayout->workarea_row_draw("Seitengruppen",$html);	
	?>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" onclick="javascript:return confirm('Seitencache zur&uuml;cksetzen?');" value="Ausf&uuml;hren"></td>
          </tr>
        </table>
		<?	$myLayout->workarea_stop_draw();
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























