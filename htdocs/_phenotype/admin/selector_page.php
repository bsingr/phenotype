<?php
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
<?php
require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor_Pages");
?>
<?php
if ($_REQUEST["cop"]==1)
{
  $titel = "Seite kopieren";
}
else
{
  $titel = "Seite umhängen";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?php echo PT_VERSION ?></title>
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
            <td class="windowTitle"><?php echo $titel ?></td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
$url = "selector_page.php?b=0&id=".$_REQUEST["id"];
$myLayout->tab_addEntry("Seiten",$url,"b_site.gif");
$url = "selector_page.php?b=1&id=".$_REQUEST["id"];
//$myLayout->tab_addEntry("Content",$url,"b_content.gif");
//$url = "selector_page.php?b=2";
//$myLayout->tab_addEntry("WWW",$url,"b_extern.gif");
$myLayout->tab_draw("Seiten",350,0,0)
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
              <td class="padding10">Gruppe:</td>
              <td><form action="selector_page.php" method="post" name="formGrp">
			  <input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
			  <input type="hidden" name="cop" value="<?php echo $_REQUEST["cop"] ?>">
			  <select name="grp_id" onChange="document.forms.formGrp.submit();" class="listmenu">

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
    $pag_id=$_REQUEST["pag_id"];
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
  
  $myNav =$myAdm->showNavi($pag_id,$top_id,2);
  $_nodes = $myNav->_flat;
  $_newnodes = Array();
  
  foreach ($_nodes AS $nav_id => $node)
  {
    $node["url"]=str_replace("page_edit.php?id=","selector_page.php?b=0&id=".$_REQUEST["id"]."&cop=". $_REQUEST["cop"]."&pag_id=",$node["url"]);
	//Bei dieser Anzeige sollte eigentlich verhindert werden, dass eine Seiter unterhalb sich selbst eingeordnet wird. 
	//if ($node["ext_id"]== $_REQUEST["id"]){$node["url"]="";}
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
    <td class="windowFooterGrey2"><a href="selector_page2.php?id=<?php echo $_REQUEST["id"] ?>&cop=<?php echo $_REQUEST["cop"] ?>&id2=<?php echo $row["pag_id"] ?>"><img src="img/b_teaserlink2.gif" width="22" height="22" border="0" align="absmiddle"> Seite auswählen </a></td>
  </tr>
</table>
</form>
</body>
</html>
