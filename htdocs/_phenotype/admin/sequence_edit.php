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
if (isset($_REQUEST["close"]))
{
  if ($_REQUEST["close"]==1)
  {
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>phenotype <?= PT_VERSION ?> - Redaktion</title>
</head>

<body>
<script language="JavaScript">
top.opener.seq_<?=$_REQUEST["id"]?>_<?=$_REQUEST["b"]?>.location.reload();
self.close();
</script>
</body>
</html>
  <?
    exit();
  }
}
?>
<?
require("_config.inc.php");
require("_session.inc.php");


?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?= PT_VERSION ?> - Redaktion</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="task.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="phenotype.js"></script>
</head>

<body onUnload="return unload();">
<script language="JavaScript">
self.focus();

// Funktioniert nicht, evtl. Warnhinweis?
function unload()
{
  document.forms.form.editform.submit();
}
</script>
<?
$id = (int)$_REQUEST["id"];
$block_nr = (int)$_REQUEST["b"];
$toolkit = (int)$_REQUEST["t"];

$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout

// Befinden wir uns schon im Editbuffer-Modus?

?>
	
	
	<form enctype="multipart/form-data" name="editform" method="post" action="sequence_update.php">
	<input type="hidden" value="1" name="editbuffer">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="block_nr" value="<?=$block_nr?>">	
	<input type="hidden" name="newtool_id" value="">	
	<input type="hidden" name="newtool_type" value="">		
	<input type="hidden" name="t" value="<?=$toolkit?>">	
	<input type="hidden" name="bez" value="<?=$_REQUEST["bez"]?>">
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?=$_REQUEST["bez"]?> </td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>		
	
	<?
      $myLayout->workarea_start_draw();
    ?>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <?
    // Das erste Bausteinpulldown
    $myLayout->workarea_componentselector_draw($toolkit,0)
    ?>
    <?
    $sql = "SELECT * FROM sequence_data WHERE con_id = " . $id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer=1 ORDER BY dat_pos";
    $rs = $myDB->query($sql);
    while ($row = mysql_fetch_array($rs))
    {
      $tname = "PhenotypeComponent_" . $row["com_id"];
      $myComponent = new $tname;
      $myComponent->init($row);
      ?>
      <tr>
            <td class="padding30"><strong><?=$myComponent->bez?></strong> <br>
                <!--<input name="checkbox" type="checkbox" value="checkbox" checked>sichtbar-->
            </td>
            <td>&nbsp;</td>
            <td class="formarea">
      <?
      $myComponent->edit();
      ?>
            </td>
            <td align="center"><!--<img src="img/b_up.gif" alt="Baustein nach oben verschieben" width="18" height="18" border="0"><br>-->
                <input type="image" src="img/b_delete.gif" alt="Baustein l&ouml;schen" width="22" height="22" border="0"  name="<?=$row["dat_id"]?>_delete"><br>
              <!--<img src="img/b_down.gif" alt="Baustein nach unten verschieben" width="18" height="18" border="0">--></td>
          </tr>
      <?
      $myLayout->workarea_componentselector_draw($toolkit,$row["dat_id"]);
    }
         // Abschlusszeile
?>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
        &nbsp;
            </td>
            <td align="right" class="windowFooterWhite"> <input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
    <?
     $myLayout->workarea_stop_draw();
	 ?>

</form>
</body>
</html>
