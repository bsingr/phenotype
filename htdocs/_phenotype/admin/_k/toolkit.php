<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr�mer.
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
$myAdm->explorer_prepare(locale("Config"),locale("Componentgroups"));
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
$myPT->startBuffer();
?>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Configure componentgroups");?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=21" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	<table width="680"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead"><?php echo localeH("No.");?></td>
              <td width="60" class="tableHead">&nbsp;</td>
              <td width="449" class="tableHead"><?php echo localeH("Name");?></td>
              <td width="50" class="tableHead"><?php echo localeH("Action");?></td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
 $sql = "SELECT * FROM componentgroup ORDER BY cog_bez";
 $rs = $myDB->query($sql);
 $includes = Array();
 while ($row=mysql_fetch_array($rs))
 {
?>		
			
            <tr>
              <td class="tableBody"><?php echo $row["cog_id"] ?></td>
              <td class="tableBody"><span class="tableCellMedia"><a href="toolkit_edit.php?id=<?php echo $row["cog_id"] ?>&b=0"><img src="img/t_bausteingruppe.gif" alt="Baustein anzeigen" width="60" height="40" border="0"></a></span></td>
              <td class="tableBody"><?php echo $row["cog_bez"] ?></td>
              <td align="right" nowrap class="tableBody"><a href="toolkit_edit.php?id=<?php echo $row["cog_id"] ?>&b=0"><img src="img/b_edit.gif" alt="Datensatz bearbeiten" width="22" height="22" border="0" align="absmiddle"></a>
<?php
       $sql = "SELECT COUNT(*) AS C FROM layout_block WHERE cog_id = " .  $row["cog_id"];
	   $rs_check = $myDB->query($sql);
	   $row_check = mysql_fetch_array($rs_check);
	   if ($row_check["C"]!=0)
	   {
?>   
<a href="toolkit_delete.php?id=<?php echo $row["cog_id"] ?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this component group?");?>')"> <img src="img/b_delete.gif" alt="<?php echo localeH("Delete component group");?>" width="22" height="22" border="0" align="absmiddle"></a>
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
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
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
        <td class="windowFooterGrey2"><a href="toolkit_insert.php" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new component group");?> </a></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
	</form>
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
























