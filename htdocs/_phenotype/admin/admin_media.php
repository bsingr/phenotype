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
$myAdm->explorer_prepare("Admin","Media");
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
<input type="hidden" name="action_id" value="2">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Mediabase l&ouml;schen </strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?
	$myLayout->workarea_start_draw();
	$myPT->startBuffer();
	?>
	
					     <select name="folder" class="input" style="width:250px">
					 <?
					 $myMB = new PhenotypeMediaBase();
					 $_folder = $myMB->getFullLogicalFolder();
					 foreach ($_folder AS $k)
					 {
					 ?>
					 <option><?=$k?></option>
					 <?
					 }
					 ?><br><br>
					 
	<?

	$html = $myPT->stopBuffer(); 
	$myLayout->workarea_row_draw("Ordner",$html);	
	?>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" onclick="javascript:return confirm('Den Ordner mit allen Bilder und Dokumenten unwiderruflich l&ouml;schen?');" value="Ausf&uuml;hren"></td>
          </tr>
        </table>
		<?	$myLayout->workarea_stop_draw();
		?>
</form>		
<br><br>
<!--
<form action="admin_action.php" method="post">
<input type="hidden" name="action_id" value="5">
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Drag & Drop - Upload ins Importverzeichnis</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?
	$myLayout->workarea_start_draw();
	$myLayout->workarea_row_draw("<br>","");	
	?>
			 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px" value="Ausf&uuml;hren"></td>
          </tr>
        </table>
		<?	$myLayout->workarea_stop_draw();
		?>
</form>-->		
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























