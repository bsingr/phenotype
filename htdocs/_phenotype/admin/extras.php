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
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header("Extras");
?>
<body>
<?php
$myAdm->menu("Extras");
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$url = "extras.php";
$myLayout->tab_addEntry("Extras",$url,"b_script.gif");
$myLayout->tab_draw("Extras",$x=260,1);
?>
<?php
$myNav = new PhenotypeTree();
$nav_id = $myNav->addNode("&Uuml;bersicht","extras.php",0,"");
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
$myLayout->displayTreeNavi($myNav,"");

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
























