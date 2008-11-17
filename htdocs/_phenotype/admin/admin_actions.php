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
$myPT->loadTMX("Admin");
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url);
  exit();
}
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header(locale("Admin"));
?>
<body>
<?php
$myAdm->menu(locale("Admin"));
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Admin"),locale("Actions"));
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
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Overview Actions");?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=9" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
              <td width="419" class="tableHead"><?php echo localeH("Name");?></td>
			  <td width="30" class="tableHead"><?php echo localeH("Status");?></td>
              <td width="50" class="tableHead"><?php echo localeH("Action");?></td>
            </tr>
            <tr>
              <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
 $sql = "SELECT * FROM action ORDER BY act_bez";
 $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
 {
?>		
			
            <tr>
              <td class="tableBody"><?php echo sprintf("%02d",$row["act_id"]) ?></td>
              <td class="tableBody"><span class="tableCellMedia"><a href="admin_action_edit.php?id=<?php echo $row["act_id"] ?>&b=0"><img src="img/t_timer.gif" alt="Aktion anzeigen" width="60" height="40" border="0"></a></span></td>
              <td class="tableBody"><?php echo $row["act_bez"] ?></td>
			              <td class="tableBody">
			<?php if ($row["act_status"]==1){ ?>
			<img src="img/i_online.gif" alt="<?php echo localeH("Status: online");?>" width="30" height="22">
			<?php }else{ ?>
			<img src="img/i_offline.gif" alt="<?php echo localeH("Status: offline");?>" width="30" height="22">
			<?php } ?>
			</td>
              <td align="right" nowrap class="tableBody"><a href="admin_action_edit.php?id=<?php echo $row["act_id"] ?>&b=0"><img src="img/b_edit.gif" alt="<?php echo localeH("View Action");?>" width="22" height="22" border="0" align="absmiddle"></a>

<a href="admin_action_delete.php?id=<?php echo $row["act_id"] ?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this action?");?>')"> <img src="img/b_delete.gif" alt="<?php echo localeH("Delete Action");?>" width="22" height="22" border="0" align="absmiddle"></a>
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
        <td class="windowFooterGrey2"><a href="admin_action_insert.php" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new action");?></a></td>
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























