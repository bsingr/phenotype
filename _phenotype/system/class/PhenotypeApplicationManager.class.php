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

/**
 * This class offers method to cleanup and repair the application as a whole, e.g. removing temp files, removing users and so on
 * 
 * It is ment to be used in config mode or for remote calls, so instanciation of this object throws a 500 if used otherwise
 * 
 * @package phenotype
 * @subpackage system
 */
class PhenotypeApplicationManager
{/*
	public function __construct()
	{
		if (PT_CONFIGMODE==0)
		{
			if (defined("PT_REMOTECALL") AND PT_REMOTECALL==1)
			{
				return;
			}
			global $myApp;
			$myApp->throw500();
		}
	}*/

	public function cleanupAll()
	{
		self::cleanupPages();
		self::cleanupPagegroups();
		self::cleanupLayouts();
		self::cleanupComponents();
		self::cleanupComponentgroups();
		self::cleanupIncludes();
		self::cleanupContent();
		self::cleanupContentData();
		self::cleanupContentCache();
		self::cleanupMediabase();
		self::cleanupRoles();
		self::cleanupUser();
		self::cleanupExtras();
		self::cleanupTicketSubjects();
		self::cleanupTickets();
		self::cleanupActions();
		self::cleanupCache();
		self::cleanupApplication();
		self::cleanupHostConfig();
		self::cleanupBackend();
		self::cleanupWebroot();
		self::cleanupStorage();
		self::cleanupTemp();
		self::cleanupDataObjects();
		self::cleanupSnapshots();
		return true;
	}

	public function cleanupPages()
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
					echo locale("Remove pagescript %1",array($file))."<br/>";
					unlink ($directory . $file);
				}
			}
		}

		$_SESSION["pag_id"] = "";
		$_SESSION["grp_id"] = "";
	}


	public function cleanupPagegroups()
	{
		global $myDB;
		$sql = "TRUNCATE table pagegroup";
		$myDB->query($sql);

		$_SESSION["pag_id"] = "";
		$_SESSION["grp_id"] = "";

	}

	public function cleanupLayouts()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM layout ORDER BY lay_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove layout %1",array($row["lay_id"]))."<br/>";
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

	public function cleanupComponents()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM component ORDER BY com_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove component %1",array($row["com_id"]))."<br/>";
			$myAdm->cfg_removeComponent($row["com_id"]);

		}

		$sql = "TRUNCATE table component_template";
		$rs = $myDB->query($sql);

		$sql = "TRUNCATE table component_componentgroup";
		$rs = $myDB->query($sql);
	}


	public function cleanupComponentgroups()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM componentgroup ORDER BY cog_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove component group %1",array($row["cog_id"]))."<br/>";
			$myAdm->cfg_removeComponentgroup($row["cog_id"]);

		}

		$sql = "TRUNCATE table component_componentgroup";
		$rs = $myDB->query($sql);
	}

	public function cleanupIncludes()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM include ORDER BY inc_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove include %1",array($row["inc_id"]))."<br/>";
			$myAdm->cfg_removeInclude($row["inc_id"]);
		}

		$sql = "TRUNCATE table include_template";
		$rs = $myDB->query($sql);
	}

	public function cleanupContent()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM content ORDER BY con_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove contentobject %1",array($row["con_id"])) . "<br/>";
			$myAdm->cfg_removeContent($row["con_id"]);

		}


		$sql = "TRUNCATE table content_template";
		$rs = $myDB->query($sql);
	}

	public function cleanupActions()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM action ORDER BY act_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove action %1",array($row["act_id"])) . "<br/>";
			$myAdm->cfg_removeAction($row["act_id"]);

		}

	}

	public function cleanupContentData()
	{
		global $myDB;
		global $myAdm;

		$sql = "TRUNCATE  content_data";
		$rs = $myDB->query($sql);
		
		$sql = "TRUNCATE  content_data_editbuffer";
		$rs = $myDB->query($sql);
		
		
		$sql = "DELETE FROM sequence_data WHERE dat_id_content<>0";
		$rs = $myDB->query($sql);		

		$sql = "DELETE FROM content_statistics";
		$rs = $myDB->query($sql);

		self::cleanupContentCache();
	}

	public function cleanupContentCache()
	{
		global $myDB;
		global $myAdm;

		$sql = "UPDATE content_data SET dat_cache1 = 0, dat_cache2 = 0, dat_cache3 = 0, dat_cache4 = 0, dat_cache5 = 0, dat_cache6 = 0";
		$rs = $myDB->query($sql);

		$dir = CACHEPATH.CACHENR ."/content/";
		$myAdm->removeDirComplete($dir);
	}


	public function cleanupRoles()
	{

		global $myDB;
		global $myAdm;

		$sql ="DELETE FROM role";
		$myDB->query($sql);
	}

	public function cleanupUser()
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

	public function cleanupExtras()
	{
		global $myDB;
		global $myAdm;

		$sql = "SELECT * FROM extra ORDER BY ext_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo locale("Remove extra %1",array($row["ext_id"])) . "<br/>";
			$myAdm->cfg_removeExtra($row["ext_id"]);
		}

		$sql = "TRUNCATE table extra_template";
		$rs = $myDB->query($sql);
	}

	public function cleanupTicketSubjects()
	{

		global $myDB;

		$sql = "TRUNCATE table ticketsubject";
		$myDB->query($sql);

		$sql = "TRUNCATE table user_ticketsubject";
		$myDB->query($sql);
	}

	public function cleanupTickets()
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

	public function cleanupCache()
	{
		global $myAdm;
		$dir = CACHEPATH;
		$myAdm->removeDirComplete($dir,1);
	}

	public function cleanupTemp()
	{
		global $myAdm;

		$myAdm->cfg_cleanupTemp();
		$myAdm->cfg_rebuildTempPackageStructure();
	}

	public function cleanupMediabase()
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



	public function cleanupWebroot()
	{
		global $myAdm;
		$_files = Array (".svn",".htaccess","php.ini","_phenotype","media","index.php","pindex.php","preview.php","print.php","xmlpage.php","xmlcontent.php","404.php","reload.php","debuginfo.php","install.php","install.4build.php","installcheck.txt","favicon.ico","deploy.php");

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
						echo  '<p style="color:red">'.$file . ' - '.locale("Folder gets deleted").'</p>';
						$myAdm->removeDirComplete($dir . '/' . $file,0);
					}
					else
					{
						echo  '<p style="color:red">'. $file . ' - '.locale("File gets deleted").'</p>';					unlink ($dir . '/' . $file);
					}
				}
			}
			@closedir($fp);
		}
	}


	public function cleanupStorage()
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
						echo  '<p style="color:red">'.$file . ' - '.locale("Folder gets deleted").'</p>';
						$myAdm->removeDirComplete($dir . '/' . $file,0);
					}
				}
				else
				{
					echo  '<p style="color:red">'. $file . ' - '.locale("File gets deleted").'</p>';
					unlink ($dir . '/' . $file);
				}

			}
			@closedir($fp);
		}
	}


	public function cleanupApplication()
	{
		global $myPT;

		$buffer = '<?php
// -----------------------------------------------------------------------------------------
// [BLOCKSTART_INHERITANCE]
// -----------------------------------------------------------------------------------------

// you may inherit every PhenotypeXYZStandard class like this:
//
// class PhenotypeContent extends PhenotypeContentStandard {}
//
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
// -----------------------------------------------------------------------------------------';
		$file = APPPATH . "_application.inc.php";
		$myPT->writefile($file,$buffer);


		$buffer = '<?xml version="1.0" encoding="'.PT_CHARSET.'" ?>
<phenotype>
	<preferences>
		<section name="backend">
			<language>de</language>
			<img_id_cover>0</img_id_cover>
			<img_id_cover_error>0</img_id_cover_error>
			<rad_upload>lite</rad_upload>
			<grp_id_pagegroup_start>1</grp_id_pagegroup_start>
			<rtf_editor>fckEditor</rtf_editor><!-- possible values are tinyMCE or fckEditor -->
			<rtf_editor_config_path>_phenotype/admin/lib/fckeditor/conf_rtf/</rtf_editor_config_path><!-- change this to use a config file you created custom, should in this case be in the docroot (htdocs) -->
			<code_editor>fckEditor</code_editor><!-- possible values are tinyMCE or fckEditor -->
			<code_editor_config_path>_phenotype/admin/lib/fckeditor/conf_code/</code_editor_config_path><!-- change this to use a config file you created custom, should in this case be in the docroot (htdocs) -->
		</section>
		<section name="edit_pages">
			<show_alternative_title>0</show_alternative_title>
			<show_quickfinder>1</show_quickfinder>
			<show_pageurl>1</show_pageurl>
			<auto_pageurl>1</auto_pageurl>
		    <show_ButtonBarOnTop>0</show_ButtonBarOnTop>
		</section>
		<section name="edit_content">
			<flat_tree>1</flat_tree>
			<build_snapshot>1</build_snapshot>
		</section>
		<section name="edit_media">
			<auto_deleteimportbox>1</auto_deleteimportbox>
			<build_snapshot>0</build_snapshot>
		</section>
		<section name="preview_dialog">
			<dialog_width>800</dialog_width>
			<dialog_heigth>500</dialog_heigth>
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
			<bez_2ndorder>Project</bez_2ndorder>
			<tab_2ndorder>Projects</tab_2ndorder>
		</section>
		<section name="frontend">
		</section>
    <section name="cache">
    <default_cache_seconds>86400</default_cache_seconds> 
    <cachetime>
    <cache_item1>
    <name>no cache</name> 
    <seconds>0</seconds> 
    </cache_item1>
    <cache_item2>
    <name>15 seconds</name> 
    <seconds>15</seconds> 
    </cache_item2>
    <cache_item3>
    <name>30 seconds</name> 
    <seconds>30</seconds> 
    </cache_item3>
    <cache_item4>
    <name>45 seconds</name> 
    <seconds>45</seconds> 
    </cache_item4>
    <cache_item5>
    <name>1 Minute</name> 
    <seconds>60</seconds> 
    </cache_item5>
    <cache_item6>
    <name>2 minutes</name> 
    <seconds>120</seconds> 
    </cache_item6>
    <cache_item7>
    <name>5 minutes</name> 
    <seconds>300</seconds> 
    </cache_item7>
    <cache_item8>
    <name>10 minutes</name> 
    <seconds>600</seconds> 
    </cache_item8>
    <cache_item9>
    <name>60 minutes</name> 
    <seconds>3600</seconds> 
    </cache_item9>
    <cache_item10>
    <name>24 hours</name> 
    <seconds>86400</seconds> 
    </cache_item10>
    </cachetime>
    </section>		
	</preferences>
</phenotype>';


		$file = APPPATH . "preferences.xml";
		$myPT->writefile($file,$buffer);

	}

	public function cleanupHostConfig()
	{
		global $myPT;

		$buffer = '<?php
/* 
 * place to be for your host specific application configs
 */

// example: smtp host 
//define ("APP_SMTP_HOST", "smtp.netcologne.de");
?>';
		$file = APPPATH . "_host.config.inc.php";
		$myPT->writefile($file,$buffer);
	}

	public function cleanupBackend()
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
					echo locale("Remove backend class %1",array($file)). "<br/>";
					unlink ($directory . $file);
				}
			}
		}
	}


	public function cleanupDataObjects()
	{
		global $myDB;
		$sql ="TRUNCATE TABLE dataobject";
		$myDB->query($sql);
	}

	public function cleanupSnapshots()
	{
		global $myDB;
		$sql ="TRUNCATE TABLE snapshot";
		$myDB->query($sql);
	}
}