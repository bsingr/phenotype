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
if (!$mySUser->checkRight("elm_analyse"))
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
$myAdm->header(locale("Analyze"));
?>
<body>
<?php
$myAdm->menu(locale("Analyze"));
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$url = "statistics.php?grp_id=-1"; 
$myLayout->tab_addEntry(locale("Pages"),$url,"b_site.gif");
$myLayout->tab_draw(locale("Pages"),$x=180,"1");
?>
<?php
$curl="";
foreach($_REQUEST AS $K => $V)
{
  if (!(in_array($K,Array("grp_id","con_id","PHPSESSID"))))
  {
    $curl .= "&".$K."=".$V;
  }
}


$sql = "SELECT * FROM pagegroup WHERE grp_statistic = 1 ORDER BY grp_bez";
$rs = $myDB->query($sql);

$myNav = new PhenotypeTree();
$nav_id  = $myNav->addNode(locale("Overview"),"statistics.php?grp_id=-1".$curl,0,"-1");
while ($row = mysql_fetch_array($rs))
{
  $myNav->addNode($row["grp_bez"],"statistics.php?grp_id=".$row["grp_id"].$curl,$nav_id,$row["grp_id"]);
}
  if (isset($_REQUEST["grp_id"]))
  {
  $grp_id = $_REQUEST["grp_id"];
  }
  else
  {
    $grp_id = "";
  }
  $myLayout->displayTreeNavi($myNav,$grp_id,180);
?>
        <table width="180" border="0" cellpadding="0" cellspacing="0">	
		<tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
	  <br><br>
<?php

$sql = "SELECT * FROM content WHERE con_statistik = 1 ORDER BY con_pos, con_bez";
$rs = $myDB->query($sql);
if (mysql_num_rows($rs)!=0)
{
  $myLayout->tab_new();
  $url = "#";
  $myLayout->tab_addEntry(locale("Content"),$url,"b_content.gif");
  // $myLayout->tab_draw("Content",$x=140,1);
  $myNav = new PhenotypeTree();

  while ($row = mysql_fetch_array($rs))
  {
    $access = 0;
  if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
    if ($access==1)
    {
     $myNav->addNode($row["con_bez"],"statistics.php?con_id=".$row["con_id"],0,$row["con_id"]);
    }
  }
  if (isset($_REQUEST["con_id"]))
  {
  $con_id = $_REQUEST["con_id"];
  }
  else
  {
    $con_id = "";
  }
  //$myLayout->displayTreeNavi($myNav,$con_id,140);
  /*
?>
        <table width="180" border="0" cellpadding="0" cellspacing="0">	
		<tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
	  <br><br>  
<?php
*/
} // -- Check, ob es Contentobjekte mit Statistik gibt
?>
<?php
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content1} 
// -------------------------------------
$myPT->startBuffer();
?>
<form action="statistics.php" method="post" name="form1" id="form1">
<?php if (isset($_REQUEST["grp_id"])){ ?>
<input type="hidden" name="grp_id" value="<?php echo $_REQUEST["grp_id"] ?>"?>
<?php } ?>
<?php if (isset($_REQUEST["con_id"])){ ?>
<input type="hidden" name="con_id" value="<?php echo $_REQUEST["con_id"] ?>"?>
<?php } ?>
<?php
$nr=1;

displayPanel($nr);
?>
<?php
$content1 = $myPT->stopBuffer();
// -------------------------------------
// -- {$content2} 
// -------------------------------------
?>
</form>
<?php
// -------------------------------------
// {$content2} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$nr=2;

displayPanel($nr);
?>
<?php
$content2 = $myPT->stopBuffer();
// -------------------------------------
// -- {$content2} 
// -------------------------------------
?>
<?php
$myAdm->statsTable($left,$content1,$content2);
?>
<?php

?>
</body>
</html>

<?php
function displaypanel($nr)
{
  global $myPT;
  global $myLayout;
  if (isset($_REQUEST["p".$nr."_newscope"]))
{
  $scope = $_REQUEST["p".$nr."_newscope"];
  if ((isset($_REQUEST["p".$nr."_minus_x"])) OR (isset($_REQUEST["p".$nr."_plus_x"])) OR (isset($_REQUEST["p".$nr."_dtfocus_x"])))
  {
    $scope=2;
  }
  if ($scope==1)
  {
    $datum = $myPT->german2Timestamp($_REQUEST["p".$nr."_datum"]); 
    if ($_REQUEST["p".$nr."_monat"]!=date('Ym',$datum))
	{
	  $monat = substr($_REQUEST["p".$nr."_monat"],4,2);
      $jahr = substr($_REQUEST["p".$nr."_monat"],0,4);
      $datum = mktime(0,0,0,$monat,1,$jahr); 
	  if ($_REQUEST["p".$nr."_monat"]==date('Ym')){$datum=time();}
	}
  }
  else
  {
    $datum = $myPT->german2Timestamp($_REQUEST["p".$nr."_datum"]);
	if (isset($_REQUEST["p".$nr."_minus_x"]))
	{
	 $datum = mktime(0,0,0,date('m',$datum),(date('d',$datum)-1),date('Y',$datum)); 
	}
	if (isset($_REQUEST["p".$nr."_plus_x"]))
	{
	 $datum = mktime(0,0,0,date('m',$datum),(date('d',$datum)+1),date('Y',$datum)); 
	}	
  }
  $anzahl = $_REQUEST["p".$nr."_anzahl"];
  $mode = $_REQUEST["p".$nr."_mode"];  
  if (($mode==2)AND ($scope==2)){$scope=1;}
  if (($mode==1)AND ($scope==3)){$scope=1;}  
}
else
{
  $datum=time();
  if ($nr==1){$scope=2;}else{$scope=1;}
  $anzahl=10;
  $mode=1;
}
if ($datum > time()){$datum=time();}
if ($datum < 0){$datum=time();}
if (isset($_REQUEST["grp_id"]))
{
  $grp_id = $_REQUEST["grp_id"];
  $myLayout->displayStatsPanel_page("Panel ".$nr,$nr,$mode,$datum,$scope,$anzahl,$grp_id);
}
}
?>






















