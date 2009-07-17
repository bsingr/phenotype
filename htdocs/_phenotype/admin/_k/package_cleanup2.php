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

if (!$mySUser->checkRight("superuser"))
{
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
$myPT->clearCache();
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
$myMgr = new PhenotypeApplicationManager();
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
	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3"></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      
    </table>
    <?php
    $myLayout->workarea_start_draw();
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td class="tableBody">
    <?php
    if ($myRequest->getI("pages")){$myMgr->cleanupPages();}
    if ($myRequest->getI("pagegroups")){$myMgr->cleanupPagegroups();}
    if ($myRequest->getI("layouts")){$myMgr->cleanupLayouts();}
    if ($myRequest->getI("components")){$myMgr->cleanupComponents();}
    if ($myRequest->getI("componentgroups")){$myMgr->cleanupComponentgroups();}
    if ($myRequest->getI("includes")){$myMgr->cleanupIncludes();}
    if ($myRequest->getI("content")){$myMgr->cleanupContent();}
    if ($myRequest->getI("contentdata")){$myMgr->cleanupContentData();}
    if ($myRequest->getI("contentcache")){$myMgr->cleanupContentCache();}
    if ($myRequest->getI("mediabase")){$myMgr->cleanupMediabase();}
    if ($myRequest->getI("roles")){$myMgr->cleanupRoles();}
    if ($myRequest->getI("user")){$myMgr->cleanupUser();}
    if ($myRequest->getI("extras")){$myMgr->cleanupExtras();}
    if ($myRequest->getI("ticketsubjects")){$myMgr->cleanupTicketSubjects();}
    if ($myRequest->getI("tickets")){$myMgr->cleanupTickets();}
    if ($myRequest->getI("actions")){$myMgr->cleanupActions();}
    if ($myRequest->getI("dircache")){$myMgr->cleanupCache();}
    if ($myRequest->getI("application")){$myMgr->cleanupApplication();}
    if ($myRequest->getI("hostconfig")){$myMgr->cleanupHostConfig();}
    if ($myRequest->getI("backend")){$myMgr->cleanupBackend();}
    if ($myRequest->getI("languagemaps")){$myMgr->cleanupLanguageMaps();}
    if ($myRequest->getI("htdocs")){$myMgr->cleanupWebroot();}    
    if ($myRequest->getI("storage")){$myMgr->cleanupStorage();}  
    if ($myRequest->getI("dirtemp")){$myMgr->cleanupTemp();}    
    if ($myRequest->getI("dataobject")){$myMgr->cleanupDataObjects();} 
    if ($myRequest->getI("snapshots")){$myMgr->cleanupSnapshots();} 
	?>
	</td>
      </tr>
    </table>
	<?php
	$myLayout->workarea_whiteline();
	?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowFooterWhite">&nbsp;</td>
        <td align="right" class="windowFooterWhite">
		&nbsp;&nbsp;
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

























