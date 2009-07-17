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
$myPT->loadTMX("Extras");
?>
<?php
if (!$mySUser->checkRight("elm_extras"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header(locale("Extras"));
?>
<body>
<?php
$myAdm->menu(locale("Extras"));
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$url = "extras.php";
$myLayout->tab_addEntry(locale("Extras"),$url,"b_script.gif");
$myLayout->tab_draw(locale("Extras"),$x=260,1);
?>
<?php
$myNav = new PhenotypeTree();
$nav_id = $myNav->addNode(locale("Overview"),"extras.php",0,"");
$sql = "SELECT * FROM extra ORDER BY ext_bez";
$rs = $myDB->query($sql);
while ($row = mysql_fetch_array($rs))
{
  $access = 0;

  if ($mySUser->checkRight("ext_".$row["ext_id"])){$access=1;}
  if ($access==1)
  {
    $myNav->addNode($row["ext_bez"],"extra_start.php?id=".$row["ext_id"],$nav_id,$row["ext_id"]);
  }
  
}
$myLayout->displayTreeNavi($myNav,$myRequest->getI("id"));

?>
<table width="260" border="0" cellpadding="0" cellspacing="0">
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>

<?php
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
$id = $myRequest->getI("id");
$cname = "PhenotypeExtra_" . $id;
$myExtra = new $cname();
?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $myExtra->bez ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=5" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>	
<?php
	 $myLayout->tab_new();
	 $url = "extra_start.php?id=" .$id;	 
	 $myLayout->tab_addEntry(locale("Start"),$url,"b_script.gif");
	if ($myExtra->configure_tab==1)
	{
		$url = "extra_setup.php?id=" .$id;
		$myLayout->tab_addEntry(locale("Config"),$url,"b_konfig.gif");
	}
	 $url = "extra_info.php?id=" .$id;	 
	 $myLayout->tab_addEntry(locale("Info"),$url,"b_utilisation.gif");	 
     $myLayout->tab_draw(locale("Start"));
	 
	 $myExtra->displayStart();
?>	
<?php
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?php
$myAdm->mainTable($left,$content);
?>
<?php

?>
</body>
</html>
























