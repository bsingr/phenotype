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
if (PT_CONFIGMODE!=1){exit();}

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
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
$id =$myRequest->get("id");

require (PACKAGEPATH .$id."/PhenotypePackage.class.php");

$myPak = new PhenotypePackage();


?>
<?php
$myAdm->header("Konfiguration");
?>
<body>
<?php
$myAdm->menu("Konfiguration");
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Konfiguration","Packages");
$myAdm->explorer_set("id",$myRequest->get("id"));
$myAdm->explorer_set("packagemode","install");
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
    <form action="package_install.php" method="post">
	<input type="hidden" name="id" value="<?php echo urlencode($myRequest->get("id")) ?>">	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle">Package <?php echo $id ?></td>
            <td align="right" class="windowTitle"><!--<a href="http://www.phenotype-cms.de/docs.php?v=23&t=21" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
	<form action="toolkit_update.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<?php
	$myLayout->tab_new();
	$url = "package_edit.php?id=" .$id ."&b=0";
	$myLayout->tab_addEntry("Protokoll",$url,"b_konfig.gif");
	$myLayout->tab_draw("Protokoll");
	$myLayout->workarea_start_draw();
	if ($myRequest->getI("structure")==1)
	{

		$myPT->startBuffer();
		$myPak->globalInstallStructure($myRequest->getI("hostconfig"));
		$html = $myPT->stopBuffer();
		$myLayout->workarea_row_draw("Struktur",$html);
	}
	if ($myRequest->getI("data")==1)
	{
		if ($myRequest->getI("dataajax")==1)
		{
			$html = '<iframe src="package_install2.php?id='.$id.'&amp;type=ajax" width="500" height="650" frameborder="0"></iframe>';
		}
		else
		{
			$html = '<iframe src="package_install2.php?id='.$id.'" width="500" height="650" frameborder="0"></iframe>';
		}
		$myLayout->workarea_row_draw("Daten",$html);
	}
 ?>
 	 
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">&nbsp;&nbsp;</td>
          </tr>
        </table>
	 <?php
	 $myLayout->workarea_stop_draw();
	?>
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
























