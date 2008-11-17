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
<?php
if ($_REQUEST["cop"]==1)
{
  $titel = locale("Copy page");
}
else
{
  $titel = locale("Reallocate page");
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
<?php if ($_REQUEST["cop"]==1){ ?>
<form action="selector_page4.php" method="post" name="form1" id="form1" target="_parent">
<?php }else{ ?>
<form action="selector_page3.php" method="post" name="form1" id="form1" target="_parent">
<?php } ?>
<input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
<input type="hidden" name="id2" value="<?php echo $_REQUEST["id2"] ?>">
<table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTitle"><?php echo $titel ?></td>
        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>--></td>
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

