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
if (PT_CONFIGMODE!=1){exit();}
$myPT->loadTMX("Config");
?>
<?php
if (!$mySUser->checkRight("superuser"))
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
$myAdm->header(locale("Config"));
?>
<body>
<?php
$myAdm->menu(locale("Config"));
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Config"),locale("Content"));

$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?php
// -------------------------------------
// -- {$left}
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content}
// -------------------------------------
?>
<?php
$myPT->startBuffer();
?>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Configure content object classes");?> </td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=15" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3"></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>               
            <td width="25" class="tableHead"><?php echo localeH("No.");?></td>
              <td width="60" class="tableHead">&nbsp;</td>
			  <td width="100" class="tableHead"><?php echo localeH("Category");?></td>
              <td width="349" class="tableHead"><?php echo localeH("Name");?></td>
              <td width="50" class="tableHead"><?php echo localeH("Action");?></td>   
            </tr>
            <tr>
              <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
if ($_REQUEST["r"]==-1)
{
	$sql = "SELECT * FROM content ORDER BY con_id";
}
else
{
	$sql = "SELECT * FROM content WHERE con_rubrik='" .$_REQUEST["r"]."' ORDER BY con_bez";
}
$rs= $myDB->query($sql);
while ($row=mysql_fetch_array($rs))
{
?>		
			
            <tr>
              <td class="tableBody"><?php echo sprintf("%02d",$row["con_id"]) ?></td>
              <td class="tableBody"><span class="tableCellMedia"><a href="contentobject_edit.php?id=<?php echo $row["con_id"] ?>&b=0&r=<?php echo urlencode($row["con_rubrik"]) ?>"><img src="img/t_script.gif" alt="<?php echo localeH("Edit content object");?>" width="60" height="40" border="0"></a></span></td>
				<td class="tableBody"><?php echo $row["con_rubrik"] ?></td>
              <td class="tableBody"><?php echo $row["con_bez"] ?></td>
              <td align="right" nowrap class="tableBody"><a href="contentobject_edit.php?id=<?php echo $row["con_id"] ?>&b=0&r=<?php echo urlencode($row["con_rubrik"]) ?>"><img src="img/b_edit.gif" alt="<?php echo localeH("Edit content object");?>" width="22" height="22" border="0" align="absmiddle"></a>
<?php
$sql = "SELECT COUNT(*) AS C FROM content_data WHERE con_id = " . $row["con_id"];
$rs_check = $myDB->query($sql);
$row_check = mysql_fetch_array($rs_check);
if ($row_check["C"]==0)
{
?>   
<a href="contentobject_delete.php?id=<?php echo $row["con_id"] ?>&r=<?php echo urlencode($row["con_rubrik"]) ?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this content object?");?>')"> <img src="img/b_delete.gif" alt="<?php echo localeH("Delete");?>" width="22" height="22" border="0" align="absmiddle"></a>
<?php
}else
{
	   ?>
	   <img src="img/transparent.gif" width="22" height="22" alt="" border="0">
	   <?php
}
?>
</td>
            </tr>
            <tr>
              <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
}
?>			
        </table></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowFooterGrey2"><a href="contentobject_insert.php" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Create new content object class");?></a></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
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
























