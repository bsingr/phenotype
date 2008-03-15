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
$myAdm->explorer_set("packagemode","cleanup");
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
            <td class="windowTitle">Cleanup</td>
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

    <?php
    $myLayout->workarea_start_draw();
		?>
		<form action="package_cleanup2.php" method="post">
		<?php
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "pages", 0,"alle Seiten löschen");
		$html .= $myLayout->workarea_form_checkbox("", "pagegroups", 0,"alle Seitengruppen löschen");
		$html .= $myLayout->workarea_form_checkbox("", "layouts", 0,"alle Layouts löschen");
		$myLayout->workarea_row_draw("Seiten", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "components", 0,"alle Bausteine löschen");
		$html .= $myLayout->workarea_form_checkbox("", "componentgroups", 0,"alle Bausteingruppen löschen");
		$myLayout->workarea_row_draw("Bausteine", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "includes", 0,"alle Includes löschen");
		$myLayout->workarea_row_draw("Includes", $html);

		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "content", 0,"alle Contentobjekte löschen");
		$html .= $myLayout->workarea_form_checkbox("", "contentdata", 0,"alle Content-Datensätze löschen");
		$html .= $myLayout->workarea_form_checkbox("", "contentcache", 0,"Contencache leeren");
		$myLayout->workarea_row_draw("Content", $html);


		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "mediabase", 0,"Mediabase komplett reinigen");
		$myLayout->workarea_row_draw("Media", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "roles", 0,"alle Rollen löschen");
		$html .= $myLayout->workarea_form_checkbox("", "user", 0,"alle User (außer dem Angemeldeten!) löschen");
		$myLayout->workarea_row_draw("Benutzer & Rechte", $html);
	
		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "extras", 0,"alle Extraobjekte löschen");
		$myLayout->workarea_row_draw("Extras", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "ticketsubjects", 0,"alle Aufgabenbereiche löschen");
		$html .= $myLayout->workarea_form_checkbox("", "tickets", 0,"alle Aufgaben löschen");
		$myLayout->workarea_row_draw("Aufgaben", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "actions", 0,"alle Aktionen löschen");
		$myLayout->workarea_row_draw("Aktionen", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "dircache", 0,"Cache komplett leeren");
		$html .= $myLayout->workarea_form_checkbox("", "dirtemp", 0,"Temporäre Dateien löschen und Struktur zurücksetzen.");
		$html .= $myLayout->workarea_form_checkbox("", "htdocs", 0,"unbekannte Dateien und Ordner aus dem Webroot entfernen.");
		$html .= $myLayout->workarea_form_checkbox("", "storage", 0,"Storage-Ordner komplett leeren");
		$myLayout->workarea_row_draw("Verzeichnisse", $html);		

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "application", 0,"_application.inc.php und preferences.xml zurücksetzen");
		$html .= $myLayout->workarea_form_checkbox("", "hostconfig", 0,"_host.config.inc.php leeren");
		$html .= $myLayout->workarea_form_checkbox("", "backend", 0,"Backendklassen löschen");
		$html .= $myLayout->workarea_form_checkbox("", "languagemaps", 0,"Languagemaps löschen");
		$html .= $myLayout->workarea_form_checkbox("", "dataobject", 0,"Dataobjects löschen");
		$html .= $myLayout->workarea_form_checkbox("", "snapshots", 0,"Snapshots löschen");
		$myLayout->workarea_row_draw("Anwendung", $html);
		?>
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Start">&nbsp;&nbsp;
            </td>
          </tr>
        </table>		
		</form>
		<?php


		$myLayout->workarea_stop_draw();

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
























