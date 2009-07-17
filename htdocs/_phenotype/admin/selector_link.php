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
$myPT->loadTMX("Editor");
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
<link href="media.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
    margin-top: 2px;
    margin-bottom: 2px;
}
-->
</style>
</head>

<body>
<script language="JavaScript" src="phenotype.js"></script>
<script type="text/javascript" language="JavaScript">
self.focus();
</script>

<table width="350" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Link selection");?></td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
$url = "selector_link.php?b=0";
$myLayout->tab_addEntry(locale("Pages"),$url,"b_site.gif");
$url = "selector_link.php?b=1";
//$myLayout->tab_addEntry("Content",$url,"b_content.gif");
//$url = "selector_link.php?b=2";
//$myLayout->tab_addEntry("WWW",$url,"b_extern.gif");
$myLayout->tab_draw(locale("Pages"),350,0,0)
?>
<?php
$rechte = $mySUser->getRights();
$sql = "SELECT grp_id AS K, grp_bez AS V FROM pagegroup ORDER BY V";
  $html = "";
  $rs = $myDB->query($sql);
  if (isset($_REQUEST["grp_id"]))
  {
    $grp_id=$_REQUEST["grp_id"];
  }
  else
  {
    $grp_id =$_SESSION["grp_id"];
  }
  while ($row = mysql_fetch_array($rs))
  {
    $selected ="";
	if ($row["K"] == $grp_id)
	{
	  $selected = "selected";
    }
	if (isset($rechte["access_grp_" . $row["K"]]))
	{
	  if ($grp_id==""){$grp_id=$row["K"];}
      $html .='<option value="'. $row["K"] .'" ' . $selected . '>' . $row["V"] . '</option>';
    }
  }
  
  ?>
      
	  <table width="350" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="padding10"><?php echo localeH("Group");?>:</td>
              <td><form action="selector_link.php" method="post" name="formGrp"><select name="grp_id" onChange="document.forms.formGrp.submit();" class="listmenu">
<?php
  echo $html;
?>				 
</select></td>
            </tr>
          </table></td>
        </tr>
      </table>
<?php
$top_id = $rechte["pag_id_grp_" . $grp_id];
  if (isset($_REQUEST["pag_id"]))
  {
    $pag_id=(int)$_REQUEST["pag_id"];
  }
  else
  {
  if ($top_id==0)
  {
    $sql = "SELECT pag_id FROM page WHERE grp_id = ". $grp_id." AND pag_id_top = " . $top_id . " ORDER BY pag_pos";
	$rs = $myDB->query($sql);
	$row =mysql_fetch_array($rs);
	$pag_id = $row["pag_id"];
   }
  else
  {
    $pag_id = $top_id;
  }
  }
  
  if ($pag_id!="") // Nur, wenn eine korrekte pag_id ermittelt wurde, z.B. dann nicht, wenn die Seitengruppe leer ist
  {

  
  $myNav =$myAdm->showNavi($pag_id,$top_id,2);
  $_nodes = $myNav->_flat;
  $_newnodes = Array();
  
  foreach ($_nodes AS $nav_id => $node)
  {
    $node["url"]=str_replace("page_edit.php?id=","selector_link.php?b=0&pag_id=",$node["url"]);
	$_newnodes[$nav_id]=$node;
  }
  $myNav->_flat=$_newnodes;
  $myLayout->displayTreeNaviNoShadow($myNav,$pag_id,350);

  $sql = "SELECT * FROM page WHERE pag_id=" . $pag_id;
  $rs = $myDB->query($sql);
  $row = mysql_fetch_array($rs);
  
?>	  
<table width="350" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowFooterGrey2"><a href="javascript:select_link('<?php echo $row["pag_titel"] ?>','index.php?id=<?php echo $row["pag_id"] ?>','_self');self.close();"><img src="img/b_teaserlink2.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Select link");?> </a></td>
  </tr>
</table>
<?php
  }
?>
</form>
</body>
</html>
