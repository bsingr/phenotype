<?
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
<?
require("_config.inc.php");
require("_session.inc.php");
if (PT_CONFIGMODE!=1){exit();}
?>
<?
if (!$mySUser->checkRight("superuser"))
{
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
?>
<?
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<?
$myAdm->header("Konfiguration");
?>
<body>
<?
$myAdm->menu("Konfiguration");
?>
<?
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Konfiguration","Packages");
$myAdm->explorer_set("packagemode","cleanup");
$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?
// -------------------------------------
// -- {$left}
// -------------------------------------
?>
<?
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

    <?
    $myLayout->workarea_start_draw();
		?>
		<form action="package_cleanup2.php" method="post">
		<?
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "pages", 0,"alle Seiten l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "pagegroups", 0,"alle Seitengruppen l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "layouts", 0,"alle Layouts l�schen");
		$myLayout->workarea_row_draw("Seiten", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "components", 0,"alle Bausteine l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "componentgroups", 0,"alle Bausteingruppen l�schen");
		$myLayout->workarea_row_draw("Bausteine", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "includes", 0,"alle Includes l�schen");
		$myLayout->workarea_row_draw("Includes", $html);

		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "content", 0,"alle Contentobjekte l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "contentdata", 0,"alle Content-Datens�tze l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "contentcache", 0,"Contencache leeren");
		$myLayout->workarea_row_draw("Content", $html);


		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "mediabase", 0,"Mediabase komplett reinigen");
		$myLayout->workarea_row_draw("Media", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "roles", 0,"alle Rollen l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "user", 0,"alle User (au�er dem Angemeldeten!) l�schen");
		$myLayout->workarea_row_draw("Benutzer & Rechte", $html);
	
		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "extras", 0,"alle Extraobjekte l�schen");
		$myLayout->workarea_row_draw("Extras", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "ticketsubjects", 0,"alle Aufgabenbereiche l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "tickets", 0,"alle Aufgaben l�schen");
		$myLayout->workarea_row_draw("Aufgaben", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "actions", 0,"alle Aktionen l�schen");
		$myLayout->workarea_row_draw("Aktionen", $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "dircache", 0,"Cache komplett leeren");
		$html .= $myLayout->workarea_form_checkbox("", "dirtemp", 0,"Tempor�re Dateien l�schen und Struktur zur�cksetzen.");
		$html .= $myLayout->workarea_form_checkbox("", "htdocs", 0,"unbekannte Dateien und Ordner aus dem Webroot entfernen.");
		$html .= $myLayout->workarea_form_checkbox("", "storage", 0,"Storage-Ordner komplett leeren");
		$myLayout->workarea_row_draw("Verzeichnisse", $html);		

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "application", 0,"_application.inc.php und preferences.xml zur�cksetzen");
		$html .= $myLayout->workarea_form_checkbox("", "hostconfig", 0,"_host.config.inc.php leeren");
		$html .= $myLayout->workarea_form_checkbox("", "backend", 0,"Backendklassen l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "languagemaps", 0,"Languagemaps l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "dataobject", 0,"Dataobjects l�schen");
		$html .= $myLayout->workarea_form_checkbox("", "snapshots", 0,"Snapshots l�schen");
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
		<?


		$myLayout->workarea_stop_draw();

		?>


<?
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content}
// -------------------------------------
?>
<?
$myAdm->mainTable($left,$content);
?>
<?

?>
</body>
</html>
























