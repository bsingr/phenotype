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
$myAdm->explorer_prepare(locale("Config"),locale("Packages"));
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
            <td class="windowTitle"><?php echo localeH("Cleanup");?></td>
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
		<form name="cleanup_form" id="cleanup_form" action="package_cleanup2.php" method="post">
		<?php
		$html = "";
		$checkers = Array();
		$html .= $myLayout->workarea_form_checkbox("", "pages", 0,locale("delete all pages"));
		$checkers[] = "pages";
		$html .= $myLayout->workarea_form_checkbox("", "pagegroups", 0,locale("delete alle page groups"));
		$checkers[] = "pagegroups";
		$html .= $myLayout->workarea_form_checkbox("", "layouts", 0,locale("delete all layouts"));
		$checkers[] = "layouts";
		$myLayout->workarea_row_draw(locale("Pages"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "components", 0,locale("delete all components"));
		$checkers[] = "components";
		$html .= $myLayout->workarea_form_checkbox("", "componentgroups", 0,locale("delete all component groups"));
		$checkers[] = "componentgroups";
		$myLayout->workarea_row_draw(locale("Components"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "includes", 0,locale("delete all includes"));
		$checkers[] = "includes";
		$myLayout->workarea_row_draw(locale("Includes"), $html);

		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "content", 0,locale("delete all content object classes"));
		$checkers[] = "content";
		$html .= $myLayout->workarea_form_checkbox("", "contentdata", 0,locale("delete alle content object records"));
		$checkers[] = "contentdata";
		$html .= $myLayout->workarea_form_checkbox("", "contentcache", 0,locale("delete content cache files"));
		$checkers[] = "contentcache";
		$myLayout->workarea_row_draw(locale("Content"), $html);


		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "mediabase", 0,locale("clean mediabase totally"));
		$checkers[] = "mediabase";
		$myLayout->workarea_row_draw(locale("Media"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "roles", 0,locale("delete all roles"));
		$checkers[] = "roles";
		$html .= $myLayout->workarea_form_checkbox("", "user", 0,locale("delete all users (except the one currently logged in)"));
		$checkers[] = "user";
		$myLayout->workarea_row_draw(locale("User & Roles"), $html);
	
		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "extras", 0,locale("delete all extrase"));
		$checkers[] = "extras";
		$myLayout->workarea_row_draw(locale("Extras"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "ticketsubjects", 0,locale("delete all ticket subjects"));
		$checkers[] = "ticketsubjects";
		$html .= $myLayout->workarea_form_checkbox("", "tickets", 0,locale("delete all tickets"));
		$checkers[] = "tickets";
		$myLayout->workarea_row_draw(locale("Tasks"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "actions", 0,locale("delete all actions"));
		$checkers[] = "actions";
		$myLayout->workarea_row_draw(locale("Actions"), $html);

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "dircache", 0,locale("delete all cache files"));
		$checkers[] = "dircache";
		$html .= $myLayout->workarea_form_checkbox("", "dirtemp", 0,locale("remove temporary files and reset directory structure"));
		$checkers[] = "dirtemp";
		$html .= $myLayout->workarea_form_checkbox("", "htdocs", 0,locale("remove unknown files in webroot folder"));
		$checkers[] = "htdocs";
		$html .= $myLayout->workarea_form_checkbox("", "storage", 0,locale("delete all files in storage folder"));
		$checkers[] = "storage";
		$myLayout->workarea_row_draw(locale("Folders"), $html);		

		
		$html = "";
		$html .= $myLayout->workarea_form_checkbox("", "application", 0,locale("reset _application.inc.php and preferences.xml"));
		$html .= $myLayout->workarea_form_checkbox("", "hostconfig", 0,locale("clean _host.config.inc.php"));
		$html .= $myLayout->workarea_form_checkbox("", "backend", 0,locale("delete application specific backend classes"));
		//$html .= $myLayout->workarea_form_checkbox("", "languagemaps", 0,"Languagemaps löschen");
		$html .= $myLayout->workarea_form_checkbox("", "dataobject", 0,locale("delete dataobjects"));
		$html .= $myLayout->workarea_form_checkbox("", "snapshots", 0,locale("delete snapshots"));
		$myLayout->workarea_row_draw(locale("Application"), $html);
		
		$checkersCode = "'". implode("','", $checkers) ."'";
		?>
<script type="text/javascript">
	var allChecked = false;
	function checkAll() {
		if (allChecked == true) {
			allChecked = false;
		} else {
			allChecked = true;
		}
		for (var i = 0; i < document.forms['cleanup_form'].elements.length; i++) {
			document.forms['cleanup_form'].elements[i].checked = allChecked;
		}
	}
</script>
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><a onclick="checkAll();" style="margin-right:30px;"><?php echo localeH("Select/Deselect all");?></a>
			<input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Start");?>">&nbsp;&nbsp;
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
























