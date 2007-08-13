<?
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
function cleanupPages()
{
	global $myDB;
	global $myAdm;

	$sql = "TRUNCATE table page";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table page_statistics";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table page_language";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table pageversion";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table pageversion_autoactivate";
	$rs = $myDB->query($sql);

	$sql = "DELETE FROM sequence_data WHERE pag_id<>0";
	$rs = $myDB->query($sql);

	$sql = "SELECT dat_id FROM sequence_data";
	$rs = $myDB->query($sql);
	if (mysql_num_rows($rs)==0)
	{
		$sql = "TRUNCATE table sequence_data";
		$rs = $myDB->query($sql);
	}


	$directory = CACHEPATH.CACHENR ."/page/";
	$myAdm->removeDirComplete($directory);


	$directory = APPPATH . "pagescripts/";
	$fp = @opendir($directory);

	if ($fp)
	{
		while (false !== ($file = readdir($fp)))
		{
			if ($file != "." && $file != ".." && $file != ".svn")
			{
				echo "Entferne Seitenskript ".$file . "<br/>";
				unlink ($directory . $file);
			}
		}
	}
	
	$_SESSION["pag_id"] = "";
	$_SESSION["grp_id"] = "";
}


function cleanupPagegroups()
{
	global $myDB;
	$sql = "TRUNCATE table pagegroup";
	$myDB->query($sql);

	$_SESSION["pag_id"] = "";
	$_SESSION["grp_id"] = "";

}

function cleanupLayouts()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM layout ORDER BY lay_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Layout " . $row["lay_id"] . "<br/>";
		$myAdm->cfg_removeLayout($row["lay_id"]);

	}

	$sql = "TRUNCATE table layout";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table layout_block";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table layout_include";
	$rs = $myDB->query($sql);

	$sql = "TRUNCATE table layout_pagegroup";
	$rs = $myDB->query($sql);


}

function cleanupComponents()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM component ORDER BY com_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Baustein " . $row["com_id"] . "<br/>";
		$myAdm->cfg_removeComponent($row["com_id"]);

	}

	$sql = "TRUNCATE table component_template";
	$rs = $myDB->query($sql);
	
	$sql = "TRUNCATE table component_componentgroup";
	$rs = $myDB->query($sql);	
}


function cleanupComponentgroups()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM componentgroup ORDER BY cog_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Bausteingruppe " . $row["cog_id"] . "<br/>";
		$myAdm->cfg_removeComponentgroup($row["cog_id"]);

	}
	
	$sql = "TRUNCATE table component_componentgroup";
	$rs = $myDB->query($sql);	
}

function cleanupIncludes()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM include ORDER BY inc_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Include " . $row["inc_id"] . "<br/>";
		$myAdm->cfg_removeInclude($row["inc_id"]);

	}

	$sql = "TRUNCATE table include_template";
	$rs = $myDB->query($sql);
}

function cleanupContent()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM content ORDER BY con_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Contentobject " . $row["con_id"] . "<br/>";
		$myAdm->cfg_removeContent($row["con_id"]);

	}

	$sql = "DELETE FROM sequence_data WHERE dat_id_content<>0";
	$rs = $myDB->query($sql);
	
	$sql = "TRUNCATE table include_template";
	$rs = $myDB->query($sql);
}

function cleanupActions()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM action ORDER BY act_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Aktion " . $row["act_id"] . "<br/>";
		$myAdm->cfg_removeAction($row["act_id"]);

	}

}

function cleanupContentData()
{
	global $myDB;
	global $myAdm;

	$sql = "TRUNCATE  content_data";
	$rs = $myDB->query($sql);

	$sql = "DELETE FROM content_statistics";
	$rs = $myDB->query($sql);

	cleanupContentCache();
}

function cleanupContentCache()
{
	global $myDB;
	global $myAdm;

	$sql = "UPDATE content_data SET dat_cache1 = 0, dat_cache2 = 0, dat_cache3 = 0, dat_cache4 = 0, dat_cache5 = 0, dat_cache6 = 0";
	$rs = $myDB->query($sql);

	$dir = CACHEPATH.CACHENR ."/content/";
	$myAdm->removeDirComplete($dir);
}


function cleanupRoles()
{

	global $myDB;
	global $myAdm;

	$sql ="DELETE FROM role";
	$myDB->query($sql);
}

function cleanupUser()
{

	global $myDB;
	global $myAdm;
	global $mySUser;

	$sql = "DELETE FROM user WHERE usr_id<>" . $mySUser->id;
	$myDB->query($sql);

	$sql = "ALTER TABLE user AUTO_INCREMENT = " . ($mySUser->id+1);
	$myDB->query($sql);

	$sql = "TRUNCATE table user_ticketsubject";
	$myDB->query($sql);

}

function cleanupExtras()
{
	global $myDB;
	global $myAdm;

	$sql = "SELECT * FROM extra ORDER BY ext_id";
	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
		echo "Entferne Extraobjekt " . $row["ext_id"] . "<br/>";
		$myAdm->cfg_removeExtra($row["ext_id"]);
	}

	$sql = "TRUNCATE table extra_template";
	$rs = $myDB->query($sql);
}

function cleanupTicketSubjects()
{

	global $myDB;

	$sql = "TRUNCATE table ticketsubject";
	$myDB->query($sql);

	$sql = "TRUNCATE table user_ticketsubject";
	$myDB->query($sql);
}

function cleanupTickets()
{
	global $myDB;

	$sql = "TRUNCATE table ticket";
	$myDB->query($sql);

	$sql = "TRUNCATE table ticketmarkup";
	$myDB->query($sql);

	$sql = "TRUNCATE table ticketrequest";
	$myDB->query($sql);

	$sql = "TRUNCATE table ticketaction";
	$myDB->query($sql);
	
	$sql = "TRUNCATE table ticketpin";
	$myDB->query($sql);	
}

function cleanupCache()
{
	global $myAdm;
	$dir = CACHEPATH;
	$myAdm->removeDirComplete($dir,1);
}

function cleanupTemp()
{
	global $myAdm;

	$myAdm->cfg_cleanupTemp();
	$myAdm->cfg_rebuildTempPackageStructure();
}

function cleanupMediabase()
{
	global $myDB;
	global $myAdm;

	$sql = "TRUNCATE table media";
	$myDB->query($sql);
	$sql = "TRUNCATE table mediaversion";
	$myDB->query($sql);
	$sql = "TRUNCATE table mediagroup";
	$myDB->query($sql);

	$dir = MEDIABASEPATH;

	$myAdm->removeDirComplete($dir,1);

	mkdir ($dir."import");
	@chmod ($dir."import",UMASK);



}



function cleanupWebroot()
{
	global $myAdm;
	$_files = Array (".svn",".htaccess","php.ini","_phenotype","media","index.php","pindex.php","preview.php","print.php","xmlpage.php","xmlcontent.php","404.php");

	$dir = SERVERPATH;

	if ($fp= @opendir($dir))
	{
		while (($file = readdir($fp)) !== false)
		{
			if (($file == ".") || ($file == ".."))
			{
				continue;
			}

			if (in_array($file,$_files))
			{
				echo '<p style="color:green">'. $file . ' o.k. </p>';
			}
			else
			{
				if (is_dir($dir . '/' . $file))
				{
					echo  '<p style="color:red">'.$file . ' - Verzeichnis wird gelöscht</p>';
					$myAdm->removeDirComplete($dir . '/' . $file,0);
				}
				else
				{
					echo  '<p style="color:red">'. $file . ' - Datei wird gelöscht</p>';					unlink ($dir . '/' . $file);
				}
			}
		}
		@closedir($fp);
	}
}


function cleanupStorage()
{
	global $myAdm;

	$dir = APPPATH . "storage/";

	if ($fp= @opendir($dir))
	{
		while (($file = readdir($fp)) !== false)
		{
			if (($file == ".") || ($file == "..") || ($file == ".svn"))
			{
				continue;
			}

			if (is_dir($dir . '/' . $file))
			{
				if ($file != ".svn")
				{
					echo  '<p style="color:red">'.$file . ' - Verzeichnis wird gelöscht</p>';
					$myAdm->removeDirComplete($dir . '/' . $file,0);
				}
			}
			else
			{
				echo  '<p style="color:red">'. $file . ' - Datei wird gelöscht</p>';					
				unlink ($dir . '/' . $file);
			}
			
		}
		@closedir($fp);
	}
}


function cleanupApplication()
{
	global $myPT;

	$buffer = '<?
// -----------------------------------------------------------------------------------------
// [BLOCKSTART_INHERITANCE]
// -----------------------------------------------------------------------------------------
// inheritance of all main phenotype classe for application specific overrides

class PhenotypeAdmin extends PhenotypeAdminStandard {}

class PhenotypeComponent extends PhenotypeComponentStandard {}

class PhenotypeContent extends PhenotypeContentStandard {}

class PhenotypeExtra extends PhenotypeExtraStandard {}

class PhenotypeInclude extends PhenotypeIncludeStandard {}

class PhenotypePage extends PhenotypePageStandard {}

class PhenotypeAction extends PhenotypeActionStandard {}

class PhenotypeTicket extends PhenotypeTicketStandard {}

class PhenotypeBackend extends PhenotypeBackendStandard {}

class PhenotypeUser extends PhenotypeUserStandard  {}

class PhenotypeDataObject extends PhenotypeDataObjectStandard {}

// Diese Klasse wird mit dem vollständigen Umbau des Backends überflüssig !
class PhenotypeAdminLayout extends PhenotypeLayout {}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_INHERITANCE]
// -----------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------
// [BLOCKSTART_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

class PhenotypeApplication extends PhenotypeApplicationStandard 
{
	// Please define all fixed IDs here as variables like "public $rol_id_xyz = 1" or "public $pag_id_news = 1" ...

}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------
// [BLOCKSTART_CLASSALIAS] 
// -----------------------------------------------------------------------------------------

// Definition of class aliases for contenobjects, components and so on to
// improve readability of the code


// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_CLASSALIAS] 
// -----------------------------------------------------------------------------------------




?>';
	$file = APPPATH . "_application.inc.php";
	$myPT->writefile($file,$buffer);


	$buffer = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<preferences>
		<section name="backend">
			<language>de</language>
			<img_id_cover>0</img_id_cover>
			<img_id_cover_error>0</img_id_cover_error>
			<rad_upload>lite</rad_upload>
			<grp_id_pagegroup_start>1</grp_id_pagegroup_start>
		</section>
		<section name="edit_pages">
			<show_alternative_title>0</show_alternative_title>
			<show_quickfinder>1</show_quickfinder>
			<show_pageurl>1</show_pageurl>
			<auto_pageurl>1</auto_pageurl>
		</section>
		<section name="edit_content">
			<flat_tree>1</flat_tree>
			<build_snapshot>1</build_snapshot>
		</section>
		<section name="edit_media">
			<auto_deleteimportbox>1</auto_deleteimportbox>
			<build_snapshot>1</build_snapshot>
		</section>
		<section name="config_pages">
		</section>
		<section name="config_content">
		
		</section>
		<section name="tickets">
			<default_compactmode>0</default_compactmode>
			<tab_closedtickets>1</tab_closedtickets>
		    <nrofdays_listing_closedtickets>28</nrofdays_listing_closedtickets>
		    <show_notices_to_all_users>0</show_notices_to_all_users>
			<popup_ticketaction_assess>0</popup_ticketaction_assess>
			<popup_ticketaction_process>1</popup_ticketaction_process>
			<popup_ticketaction_editmode>1</popup_ticketaction_editmode>
			<active_markup_removal>0</active_markup_removal>
			<active_request_removal>0</active_request_removal>
			<sbj_id_expressticket>1</sbj_id_expressticket>
		    <!-- 
			Possibility to add a second organizational category to your tickets, based upon datasets of any contentobject
			-->
			<con_id_2ndorder>0</con_id_2ndorder>
			<bez_2ndorder>Projekt</bez_2ndorder>
			<tab_2ndorder>Projekte</tab_2ndorder>
		</section>
		<section name="frontend">
		</section>
	</preferences>
</phenotype>';


	$file = APPPATH . "preferences.xml";
	$myPT->writefile($file,$buffer);

}

function cleanupHostConfig()
{
	global $myPT;

	$buffer = '<?
/* 
 * place to be for your host specific application configs
 */

// example: smtp host 
//define ("APP_SMTP_HOST", "smtp.netcologne.de");
?>';
	$file = APPPATH . "_host.config.inc.php";
	$myPT->writefile($file,$buffer);
}

function cleanupBackend()
{
	// Backendklassen

	$directory = APPPATH . "backend/";
	$fp = @opendir($directory);


	if ($fp)
	{
		while (false !== ($file = readdir($fp)))
		{
			if ($file != "." && $file != ".." && $file != ".svn")
			{
				echo "Entferne Backendklasse ".$file . "<br/>";
				unlink ($directory . $file);
			}
		}
	}
}

function cleanupLanguageMaps()
{
	$directory = APPPATH . "languagemaps/";
	$fp = @opendir($directory);


	if ($fp)
	{
		while (false !== ($file = readdir($fp)))
		{
			if ($file != "." && $file != ".." && $file != ".svn")
			{
				echo "Entferne Languagemap ".$file . "<br/>";
				unlink ($directory . $file);
			}
		}
	}
	
}

function cleanupDataObject()
{
	global $myDB;
	$sql ="TRUNCATE TABLE dataobject";
	$myDB->query($sql);
}

function cleanupSnapshots()
{
	global $myDB;
	$sql ="TRUNCATE TABLE snapshot";
	$myDB->query($sql);
}
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
    <?
    $myLayout->workarea_start_draw();
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td class="tableBody">
    <?
    if ($myRequest->getI("pages")){cleanupPages();}
    if ($myRequest->getI("pagegroups")){cleanupPagegroups();}
    if ($myRequest->getI("layouts")){cleanupLayouts();}
    if ($myRequest->getI("components")){cleanupComponents();}
    if ($myRequest->getI("componentgroups")){cleanupComponentgroups();}
    if ($myRequest->getI("includes")){cleanupIncludes();}
    if ($myRequest->getI("content")){cleanupContent();}
    if ($myRequest->getI("contentdata")){cleanupContentData();}
    if ($myRequest->getI("contentcache")){cleanupContentCache();}
    if ($myRequest->getI("mediabase")){cleanupMediabase();}
    if ($myRequest->getI("roles")){cleanupRoles();}
    if ($myRequest->getI("user")){cleanupUser();}
    if ($myRequest->getI("extras")){cleanupExtras();}
    if ($myRequest->getI("ticketsubjects")){cleanupTicketSubjects();}
    if ($myRequest->getI("tickets")){cleanupTickets();}
    if ($myRequest->getI("actions")){cleanupActions();}
    if ($myRequest->getI("dircache")){cleanupCache();}
    if ($myRequest->getI("application")){cleanupApplication();}
    if ($myRequest->getI("hostconfig")){cleanupHostConfig();}
    if ($myRequest->getI("backend")){cleanupBackend();}
    if ($myRequest->getI("languagemaps")){cleanupLanguageMaps();}
    if ($myRequest->getI("htdocs")){cleanupWebroot();}    
    if ($myRequest->getI("storage")){cleanupStorage();}  
    if ($myRequest->getI("dirtemp")){cleanupTemp();}    
    if ($myRequest->getI("dataobject")){cleanupDataObject();} 
    if ($myRequest->getI("snapshots")){cleanupSnapshots();} 
	?>
	</td>
      </tr>
    </table>
	<?
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

























