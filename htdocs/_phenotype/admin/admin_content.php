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
$myAdm->explorer_prepare("Admin","Content");
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
<input type="hidden" name="action_id" value="6">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Datensätze löschen</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?
	$myLayout->workarea_start_draw();
	$myPT->startBuffer();
	?>
	
					     <select name="con_id" class="input" style="width:250px">
					 <?
					 $sql = "SELECT * FROM content ORDER BY con_bez";
					 $rs = $myDB->query($sql);
					 while ($row=mysql_fetch_array($rs))
					 {
					 ?>
					 <option value="<?=$row["con_id"]?>"><?=$row["con_bez"]?></option>
					 <?
					 }
					 ?><br><br>
					 
	<?

	$html = $myPT->stopBuffer(); 
	$myLayout->workarea_row_draw("Contentobjekt",$html);	
	?>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" onclick="javascript:return confirm('Datensätze unwiderruflich löschen?');" value="Ausf&uuml;hren"></td>
          </tr>
        </table>
		<?	$myLayout->workarea_stop_draw();
		?>
</form>	

<form action="admin_action.php" method="post">
<input type="hidden" name="action_id" value="3">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Contentbase neu indizieren</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?
	$myLayout->workarea_start_draw();
	$myPT->startBuffer();
	?>
	
					     <select name="con_id" class="input" style="width:250px">
					 <?
					 $sql = "SELECT * FROM content ORDER BY con_bez";
					 $rs = $myDB->query($sql);
					 while ($row=mysql_fetch_array($rs))
					 {
					 ?>
					 <option value="<?=$row["con_id"]?>"><?=$row["con_bez"]?></option>
					 <?
					 }
					 ?><br><br>
					 
	<?

	$html = $myPT->stopBuffer(); 
	$myLayout->workarea_row_draw("Contentobjekt",$html);	
	?>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" onclick="javascript:return confirm('Contentobjekte neu indizieren?');" value="Ausf&uuml;hren"></td>
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























