<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
if (PT_CONFIGMODE!=1){exit();}
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
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
$myAdm->header("Admin");
?>
<body>
<?php
$myAdm->menu("Admin");
?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Admin","Layout");
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
            <td class="windowTitle">Layout  konfigurieren </td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=18" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
              <td width="25" class="tableHead">Nr.</td>
              <td width="60" class="tableHead">&nbsp;</td>
              <td width="449" class="tableHead">Bezeichnung</td>
              <td width="50" class="tableHead">Aktion</td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
 $sql = "SELECT * FROM layout ORDER BY lay_bez";
 $rs = $myDB->query($sql);
 $includes = Array();
 while ($row=mysql_fetch_array($rs))
 {
?>		
			
            <tr>
              <td class="tableBody"><?php echo sprintf("%02d",$row["lay_id"]) ?></td>
              <td class="tableBody"><span class="tableCellMedia"><a href="layout_edit.php?id=<?php echo $row["lay_id"] ?>&b=0"><img src="img/t_layout.gif" alt="Layout bearbeiten" width="60" height="40" border="0"></a></span></td>
              <td class="tableBody"><?php echo $row["lay_bez"] ?></td>
              <td align="right" nowrap class="tableBody"><a href="layout_edit.php?id=<?php echo $row["lay_id"] ?>&b=0"><img src="img/b_edit.gif" alt="Layout bearbeiten" width="22" height="22" border="0" align="absmiddle"></a>
<?php
       $sql = "SELECT COUNT(*) AS C FROM pageversion WHERE lay_id = "  . $row["lay_id"];
	   $rs_check = $myDB->query($sql);
	   $row_check = mysql_fetch_array($rs_check);
	   if ($row_check["C"]==0)
	   {
?>   
<a href="layout_delete.php?id=<?php echo $row["lay_id"] ?>" onclick="javascript:return confirm('Dieses Layout wirklich l&ouml;schen?')"> <img src="img/b_delete.gif" alt="Layout l&ouml;schen" width="22" height="22" border="0" align="absmiddle"></a>
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
        <td class="windowFooterGrey2"><a href="layout_insert.php" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> Neues Layout anlegen</a></td>
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
























