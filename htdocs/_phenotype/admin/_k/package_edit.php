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
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
$id = $_REQUEST["id"];
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


require (PACKAGEPATH.$myRequest->get("id")."/PhenotypePackage.class.php");

$myPak = new PhenotypePackage();

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
	 $myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");
	 $myLayout->tab_draw("Konfiguration");
     $myLayout->workarea_start_draw();
     $myLayout->workarea_row_draw("Bezeichnung",$myPak->bez);	 
	 $myLayout->workarea_row_draw("Beschreibung",$myPak->getDescription());	  
	 $html .= $myLayout->workarea_form_checkbox("", "structure", 1,"Strukturdateien installieren<br/><br/>(Bausteine, Includes, Seitengruppen, Mediagruppen, Contentobjekte, Aufgabenbereiche, Aktionen, Extras, Rollen, Applikationsdateien, Backendklassen, Languagemaps, Webroot, Storage)<br/>");
	 
	 $file = PACKAGEPATH.$myRequest->get("id")."/_host.config.inc.php";
	
	 if (file_exists($file))
	 {
	 	$html .= $myLayout->workarea_form_checkbox("", "hostconfig", 0,"_host.config.inc.php überschreiben<br/>");
	 }
	 $html .="<br/>";
	 $html .= $myLayout->workarea_form_checkbox("", "data", 1,"Daten installieren");
	 $html .= $myLayout->workarea_form_checkbox("", "dataajax", 0,"Ajax-Installer verwenden.<br/><br/>(Seiten, Contentdatensätze, Mediaobjekte, Aufgaben, Benutzer)<br/><br/>");
	$myLayout->workarea_row_draw("Optionen", $html);
 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Installieren" onclick="javascript:return confirm('Dieses Paket wirklich installieren?')">&nbsp;&nbsp;</td>
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
























