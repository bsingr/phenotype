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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?php
require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor_Pages");
$myAdm = new PhenotypeAdmin();
?>
<?php
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
<title>Phenotype <?php echo PT_VERSION ?></title>
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
<input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTitle"><?php echo localeH("Add new page");?></td>
        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>--></td>
      </tr>
    </table></td>
    </tr>
</table>
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="5" valign="top" class="tableBody"><?php echo localeHBR("How to name the new page?");?> <br>
              <input name="bez" type="text" class="input" style="width: 300px" value="<?php echo localeH("New Page");?>">
          </td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableBody"><p><?php echo localeHBR("Where to locate the new page within the page tree?");?></p>
          </td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="1" checked>
                  <img src="img/i_classification_bottom.gif" alt="<?php echo localeH("After current page, same level");?>" width="25" height="35" align="absmiddle">&nbsp; <?php echo localeH("After current page, same level");?></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="2">
                  <img src="img/i_classification_top.gif" alt="<?php echo localeH("Before current page, same level");?>" width="25" height="35" align="absmiddle"> &nbsp;<?php echo localeH("Before current page, same level");?></td>
        </tr>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
		<?php if ($_REQUEST["c"]==0){ ?>
        <tr>
          <td colspan="5" valign="top" class="tableCellMedia"><input name="insertorder" type="radio" value="3">
                  <img src="img/i_classification_sub.gif" alt="<?php echo localeH("Under current page, lower level");?>" width="30" height="35" align="absmiddle"> <?php echo localeH("Under current page, lower level");?></td>
        </tr>
		<?php } ?>
        <tr>
          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        <tr>
          <td colspan="5" valign="top" class="tableBody"><?php echo localeHBR("Which template should be used?");?></td>
          </tr>
		<tr>
		<td colspan="5" valign="top" class="tableBody">
		<?php
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
		 $_options[0]= localeH("No Template");
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
        <td align="right" class="windowTitle"><input name="Submit" type="submit" class="buttonWhite" value="<?php echo localeH("Save");?>" style="width:102px"></td>
      </tr>
    </table></td>
    </tr>
</table>
</form>
</body>
</html>

