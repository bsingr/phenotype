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
<form action="page_insert.php" method="post" name="form1" id="form1" target="_parent">
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTitle">Neue Seite hinzuf&uuml;gen</td>
        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
      </tr>
    </table></td>
    </tr>
</table>
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="5" valign="top" class="tableBody"><strong>Wie</strong> soll die Seite
            benannt werden? <br>
              <input name="bez" type="text" class="input" style="width: 300px" value="Neue Seite">
          </td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableBody"><p><strong>Wo</strong> soll die
              neue Seite im Navigationsbaum eingeordnet werden?</p>
          </td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="1" checked>
                  <img src="img/i_classification_bottom.gif" alt="unter der aktivierten Seite" width="25" height="35" align="absmiddle">&nbsp; Nach
                  aktiver Seite, gleiche Ebene</td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="2">
                  <img src="img/i_classification_top.gif" alt="&uuml;ber der aktivierten Seite" width="25" height="35" align="absmiddle"> &nbsp;Vor
                  aktiver Seite, gleiche Ebene</td>
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
          <td colspan="5" valign="top" class="tableBody"><strong>Welches</strong> Template
            soll verwendet werden?</td>
          </tr>
		<tr>
		<td colspan="5" valign="top" class="tableBody">
		<?
		$myPage = new PhenotypePage();
		$myPage->init($_REQUEST["id"]);
		 // Bestimmen welche Layouts genutzt werden dürfen
		 $_layout_usable=Array();
		 $sql = "SELECT * FROM layout_pagegroup WHERE grp_id=" .$myPage->grp_id;
		 $rs = $myDB->query($sql);
		 while ($row=mysql_fetch_array($rs))
		 {
		   $_layout_usable[]=$row["lay_id"];
		 }
		 $_layout_protected=Array();
		 $sql = "SELECT DISTINCT(lay_id) FROM layout_pagegroup";
		 $rs = $myDB->query($sql);
		 while ($row=mysql_fetch_array($rs))
		 {
		   $_layout_protected[]=$row["lay_id"];
		 }
		 $_layout_deny = array_diff($_layout_protected,$_layout_usable);
		 
		 $sql = "SELECT lay_id AS K, lay_bez AS V FROM layout ORDER BY lay_bez";
		 $rs = $myDB->query($sql);
		 $_options[0]="kein Template";
		 while ($row=mysql_fetch_array($rs))
		 {
		 	if (!in_array($row["K"],$_layout_deny))
		 	{
		 	  $_options[$row["K"]]=$row["V"];
		 	}
		 }
		 $_options = $myAdm->buildOptionsByNamedArray($_options,$myPage->lay_id);

	    echo $myLayout->workarea_form_select("","lay_id",$_options,250); 
	    ?>
		</td>
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

