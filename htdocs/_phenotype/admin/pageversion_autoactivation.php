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
$myPT->loadTMX("Editor_Pages");
?>
<?php
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
     $sql = "SELECT * FROM pageversion WHERE pag_id =" . $_REQUEST["id"] . " ORDER BY ver_nr";
     $rs = $myDB->query($sql);
     $versionen = Array();
     while ($row = mysql_fetch_array($rs))
     {
     $versionen[$row["ver_id"]] = $row["ver_nr"] . ".: " .$row["ver_bez"];
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
<link href="site.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
    margin-top: 2px;
    margin-bottom: 2px;
}
-->
</style>
<script language="Javascript">self.focus();</script>
</head>
<body>
<form action="pageversion_insertautoactivation.php">
<table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTitle"><?php echo localeH("Automatic version change");?> </td>
        <td align="right" class="windowTitle"></td>
      </tr>
    </table>
<table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" valign="top" class="tableBody"><?php echo localeH("Version");?>:<br>
                <input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
      <input type="hidden" name="ver_id" value="<?php echo $_REQUEST["ver_id_editing"] ?>">
      <select name="ver_id_2bactivated" class="input" style="width: 170px">
      <?php
      echo $myAdm->buildOptionsByNamedArray($versionen,$_REQUEST["ver_id"]);
      ?>
            </select><br></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="tableBody">
          <?php echo localeH("Switching time");?>:<br>
                                                                                                                                   <input type="text" value="<?php echo date('d.m.Y H:i') ?>" name="datum" class="input" style="width: 120px"><br>
          </td>
        </tr>
    </table></td>
  </tr>
</table>
<table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowFooterWhite"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right" class="windowTitle"><input name="Submit" type="submit" class="buttonWhite" value="<?php echo localeH("Insert");?>" style="width:102px"></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>