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
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$id = $myRequest->getI("id");
if (isset($_REQUEST["ver_id"]))
{
  $ver_id = (int)$_REQUEST["ver_id"];
}
else
{
  $sql = "SELECT * FROM page WHERE pag_id = " . $id;
  $rs = $myDB->query($sql);
  $row = mysql_fetch_array($rs);
  $ver_id = $row["ver_id"];
}

$myPage = new PhenotypePage($id,$ver_id);
$_SESSION["pag_id"]=$myPage->id;
$_SESSION["grp_id"]=$myPage->grp_id;

$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin(); // Damit implizit auch $myLayout
?>
<?php
$myAdm->header(locale("Editor"));
?>

<body>
<?php
$myAdm->menu(locale("Editor"));
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Editor"),locale("Pages"));
$myAdm->explorer_set("pag_id",$_REQUEST["id"]);
$myAdm->explorer_draw();
?>
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
if ($mySUser->checkRight("elm_page"))
{
  $_pagegroups = Array();
  $sql = "SELECT grp_id,grp_bez FROM pagegroup";
  $rs = $myDB->query($sql);
  $sql_where = "";
  while ($row = mysql_fetch_array($rs))
  {
    $_pagegroups[$row["grp_id"]]=$row["grp_bez"];
	
    if ($mySUser->checkRight("access_grp_" . $row["grp_id"]))
	{
	  if ($sql_where =="")
	  {
	    $sql_where = "grp_id=" . $row["grp_id"];
	  }
	  else
	  {
	    $sql_where .= " OR grp_id=" . $row["grp_id"];
	  }
	}
  }
  
  $sql_cond = "";
  if ($myRequest->get("s")!="")
  {
    $sql_cond = " AND pag_bez LIKE '%" . $myRequest->getS("s") ."%'";
  }  
  if ($myRequest->get("v")!="")
  {
    $sql_cond .= " AND pag_fullsearch LIKE '%" . $myRequest->getS("v") ."%'";
  }    
  if ($myRequest->get("i")!="")
  {
    $sql_cond = " AND pag_id=" . $myRequest->getI("i");
  }
  
  $sql = "SELECT * FROM page WHERE (" . $sql_where .") ". $sql_cond ." ORDER BY pag_bez ";
  $rs_data = $myDB->query($sql);
  $anzahl = mysql_num_rows($rs_data);
  $page=1;
  if ($myRequest->check("p")){$page = $myRequest->getI("p");}
  $sql = "SELECT * FROM page WHERE (" . $sql_where .") ". $sql_cond ." ORDER BY pag_bez LIMIT ".(($page-1)*10).",10";
  $rs_data = $myDB->query($sql);
?>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("Pages");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="tableHead"><?php echo localeH("ID");?></td>
		    <td class="tableHead"><?php echo localeH("Name");?></td>
			<td width="50" class="tableHead"><?php echo localeH("Group");?></td>
      		<td width="120" class="tableHead"><?php echo localeH("User");?></td>
            <td width="30" class="tableHead"><?php echo localeH("State");?></td>
            <td width="50" align="right" class="tableHead"><?php echo localeH("Action");?></td>
            </tr>
		  <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  <?php

while ($row_data=mysql_fetch_array($rs_data))
{
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["pag_id"] ?></td>
			

            <td class="tableBody"><?php echo $row_data["pag_bez"] ?></td>
			<td class="tableBody"><?php echo $_pagegroups[$row_data["grp_id"]] ?></td>
            <td class="tableBody"><?php echo localeFullTime($row_data["pag_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td class="tableBody">
			<?php if ($row_data["pag_status"]==1){ ?>
			<img src="img/i_online.gif" alt="Status: online" width="30" height="22">
			<?php }else{ ?>
			<img src="img/i_offline.gif" alt="Status: offline" width="30" height="22">
			<?php } ?>
			</td>
            <td align="right" nowrap class="tableBody"><a href="page_edit.php?id=<?php echo $row_data["pag_id"] ?>"><img src="img/b_edit.gif" alt="Seite bearbeiten" width="22" height="22" border="0" align="absmiddle"></a></td>
            </tr>
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
}
?>			
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<?php
	$url="page_search.php?id=".$id."&v=".$myRequest->getURL("v")."&s=".$myRequest->getURL("s")."&p=";
	echo $myLayout->renderPageBrowser($page,$anzahl,$url);
	?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br> 
	<?php
}	
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






















