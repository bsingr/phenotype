<?
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
<?
require("_config.inc.php");
require("_session.inc.php");
$myAdm = new PhenotypeAdmin();
?>
<?
if (!$mySUser->checkRight("elm_pageconfig"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
if ($_REQUEST["cop"]==1)
{
  $titel = "Seite kopieren";
}
else
{
  $titel = "Seite umh�ngen";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?= PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-top: 2px;
	margin-bottom: 2px;
}
-->
</style>
<script language="JavaScript">self.focus();</script>
</head>

<body>
<? if ($_REQUEST["cop"]==1){?>
<form action="selector_page4.php" method="post" name="form1" id="form1" target="_parent">
<?}else{?>
<form action="selector_page3.php" method="post" name="form1" id="form1" target="_parent">
<?}?>
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
<input type="hidden" name="id2" value="<?=$_REQUEST["id2"]?>">
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTitle"><?=$titel?></td>
        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
      </tr>
    </table></td>
    </tr>
</table>
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableBody"><p><strong>Wie</strong> soll die
              Seite im Navigationsbaum eingeordnet werden?</p>
          </td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="1" checked>
                  <img src="img/i_classification_bottom.gif" alt="unter der aktivierten Seite" width="25" height="35" align="absmiddle">&nbsp; Nach
                  ausgew�hlter Seite, gleiche Ebene</td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="2">
                  <img src="img/i_classification_top.gif" alt="&uuml;ber der aktivierten Seite" width="25" height="35" align="absmiddle"> &nbsp;Vor
                  ausgew�hlter Seite, gleiche Ebene</td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
		<?if ($_REQUEST["c"]==0){?>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="3">
                  <img src="img/i_classification_sub.gif" alt="&uuml;ber der aktivierten Seite" width="30" height="35" align="absmiddle"> Unterhalb
                  der Seite, eine Ebene tiefer</td>
        </tr>
		<?}?>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        
      </table></td>
    </tr>
</table>
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowFooterWhite"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right" class="windowTitle"><input name="Submit" type="submit" class="buttonWhite" value="Speichern" style="width:102px"></td>
      </tr>
    </table></td>
    </tr>
</table>
</form>
</body>
</html>

