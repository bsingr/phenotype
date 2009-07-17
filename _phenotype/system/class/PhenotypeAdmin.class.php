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

define ("NAV_TREE", 1);
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeAdminStandard
{
	var $props_explorer = Array();

	//function PhenotypeAdmin ()
	public function __construct()
	{
		global $myLayout;
		$myLayout = new PhenotypeAdminLayout();
	}

	function header($modul)
	{
		global $myLayout;
		$myLayout->header_draw($modul);
	}

	function menu($modul)
	{
		$myBP = new PhenotypeBackend();

		$nr=0;

		switch ($modul)
		{
			case locale("Start"):	
				$nr=1;
			break;
			case locale("Editor"):	
				$nr=2;
			break;	
			case locale("Extras"):	
				$nr=3;
			break;	
			case locale("Analyze"):	
				$nr=4;
			break;	
			case locale("Tasks"):	
				$nr=5;
			break;	
			case locale("Admin"):	
				$nr=6;
			break;	
			case locale("Config"):	
				$nr=7;
			break;													
			case locale("Info"):	
				$nr=8;
			break;
		}
		
		// 1 = Start
		// 2 = Redaktion
		// 3 = Extras
		// 4 = Analyse
		// 5 = Aufgaben
		// 6 = Admin
		// 7 = Konfiguration
		// 8 = Info
		
		$myBP->selectMenuItem($nr);
		
		$myBP->displayTopLine();
		return;
		
		/*
		global $myLayout;
		global $mySUser;

		$myLayout->topline_addEntry("Start","start.php");

		if ($mySUser->checkRight("elm_redaktion"))
		{
			$myLayout->topline_addEntry("Redaktion","pagegroup_select.php");
		}

		if ($mySUser->checkRight("elm_extras"))
		{
			$myLayout->topline_addEntry("Extras","extras.php");
		}

		if ($mySUser->checkRight("elm_analyse"))
		{
			$myLayout->topline_addEntry("Analyse","statistics.php?grp_id=-1");
		}

		if ($mySUser->checkRight("elm_task") )
		{
			$myLayout->topline_addEntry("Aufgaben","backend.php?page=Ticket,Assess");
		}


		//if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser") )
		//{
		$myLayout->topline_addEntry("Admin","admin.php");
		//}

		if ($mySUser->checkRight("superuser"))
		{
			$myLayout->topline_addEntry("Konfiguration","config.php");
		}
		$myLayout->topline_addEntry("Info","info.php");

		$myLayout->topline_draw($modul);
		*/
	}


	function mainTable($left,$content)
	{
		global $mySmarty;
		$mySmarty->template_dir = SYSTEMPATH . "templates/";
		$mySmarty->compile_dir = SMARTYCOMPILEPATH;
		$mySmarty->assign("left",$left);
		$mySmarty->assign("content",$content);
		$mySmarty->display("pageframe_standard.tpl");
	}

	function statsTable($left,$content1,$content2)
	{
		global $mySmarty;
		$mySmarty->template_dir = SYSTEMPATH  . "templates/";
		$mySmarty->compile_dir = SMARTYCOMPILEPATH;
		$mySmarty->assign("left",$left);
		$mySmarty->assign("content1",$content1);
		$mySmarty->assign("content2",$content2);
		$mySmarty->display("pageframe_statistic.tpl");
	}



	// Funktionen zu Menueanzeige

	function showNavi($pag_id,$pag_id_border=0,$mode=1)
	{
		global $myDB;
		global $myLayout;

		if ($pag_id_border!=0)
		{
			$sql = "SELECT pag_id_top FROM page WHERE pag_id = " . $pag_id_border;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$pag_id_border = $row["pag_id_top"];
			$alleseiten=0;
		}
		else
		{
			$alleseiten=1;
		}

		$sql = "SELECT * FROM page WHERE pag_id = " . $pag_id;
		$rs_page = $myDB->query($sql);
		$row_page = mysql_fetch_array($rs_page);

		$myNav = new PhenotypeTree();
		$pages_id = Array();
		$pages_bez =  Array();

		$pag_id_top = $row_page["pag_id_top"];
		$pag_id_next = $pag_id_top;
		$grp_id = $row_page["grp_id"];


		// Zunaechst alle Seiten oberhalb der aktuellen Seite ermitteln
		while ($pag_id_next!=$pag_id_border)
		{
			$sql = "SELECT * FROM page WHERE pag_id = " . $pag_id_next . " AND grp_id=" . $grp_id;
			$rs_page1 = $myDB->query($sql);
			$row_page1 = mysql_fetch_array($rs_page1);
			$pag_id_next = $row_page1["pag_id_top"];
			$pages_id[] = $row_page1["pag_id"];
			$pages_bez[] = $row_page1["pag_bez"];
		}
		$nav_id_top=0;
		// Jetzt die Seiten aneinanderreihen und aufstocken

		$pag_id_tree = $pag_id_border;
		for ($i=(count($pages_id)-1);$i>=0;$i--)
		{
			$sql = "SELECT * FROM page WHERE pag_id_top =" . $pag_id_tree . " AND grp_id=" . $grp_id . " ORDER BY pag_pos";

			$rs = $myDB->query($sql);
			while ($row = mysql_fetch_array($rs))
			{
				$url = "page_edit.php?id=" . $row["pag_id"];
				if ($pages_id[$i] == $row["pag_id"])
				{
					$nav_id_newtop =$myNav->addNode($row["pag_bez"],$url,$nav_id_top,$row["pag_id"]);
				}
				else
				{
					if ($pag_id_tree!=$pag_id_border OR $alleseiten==1)
					{
						$nav_id = $myNav->addNode($row["pag_bez"],$url,$nav_id_top,$row["pag_id"]);
					}
				}
			}
			$nav_id_top = $nav_id_newtop;
			$pag_id_tree = $pages_id[$i];
		}

		// Jetzt alle Seiten auf gleicher Ebene
		$sql = "SELECT * FROM page WHERE pag_id_top = " . $pag_id_top  . " AND grp_id=" . $grp_id . " ORDER BY pag_pos";
		// Wenn wir uns am Knotenpunkt eines eingeschraenkten Baumes befinden, dann
		if (($pag_id_top == $pag_id_border)AND($alleseiten==0)){$sql = "SELECT * FROM page WHERE pag_id=". $pag_id;}

		$rs_page1 = $myDB->query($sql);
		while ($row_page1 = mysql_fetch_array($rs_page1))
		{
			$url = "page_edit.php?id=" . $row_page1["pag_id"];
			$nav_id = $myNav->addNode($row_page1["pag_bez"],$url,$nav_id_top,$row_page1["pag_id"]);
			// Check auf untergeordnete Seite der aktuelle Seite
			if ($row_page1["pag_id"]==$pag_id)
			{
				$sql = "SELECT * FROM page WHERE pag_id_top = " . $pag_id . " ORDER BY pag_pos";
				$rs_page2 = $myDB->query($sql);
				while ($row_page2 = mysql_fetch_array($rs_page2))
				{
					$url = "page_edit.php?id=" . $row_page2["pag_id"];
					$myNav->addNode($row_page2["pag_bez"],$url,$nav_id,$row_page2["pag_id"]);
				}
			}
		}
		//
		if ($mode==1) // Seite highlighten
		{
			$myLayout->displayTreeNavi($myNav,$pag_id);
		}
		if ($mode==0) // Seite nicht highligten
		{
			$myLayout->displayTreeNavi($myNav,"");
		}
		if ($mode==2) // Baum zurueckgeben
		{
			return $myNav;
		}
		//$myNav->displayFlat();

	}


	
	function getPageName($pag_id,$ver_id=0)
	{
		global $myDB;
		if ($ver_id==0)
		{
			$sql = "SELECT * FROM page WHERE pag_id = " . $id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$ver_id = $row["ver_id"];
		}
		$sql = "SELECT * FROM page LEFT JOIN pageversion ON page.pag_id = pageversion.pag_id WHERE page.pag_id = " .$pag_id . " AND pageversion.ver_id = " . $ver_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$ver_nr = $row["ver_nr"];
		$bez = $row["pag_bez"];
		if ($row["ver_bez"]!=""){$bez .= " (" . $row["ver_bez"] . ")";}
		//$bez = "#" . sprintf("%03d", $pag_id) . "." . $ver_nr . " " . $bez;
		$bez = $pag_id . "." . sprintf("%02d", $ver_nr) . " " . $bez;
		return $bez;
	}

	function getContentName($dat_id)
	{
		global $myDB;
		$sql = "SELECT * FROM content_data WHERE content_data.dat_id = " .$dat_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$bez = $row["dat_bez"];
		$bez =  $dat_id . " ". $bez;
		return $bez;
	}

	function buildOptionsBySQL($sql,$selectedKey="")
	{
		global $myPT;
		return $myPT->buildOptionsBySQL($sql,$selectedKey);
	}

	function buildOptionsByNamedArray($options,$selectedKey="")
	{
		global $myPT;
		return $myPT->buildOptionsByNamedArray($options,$selectedKey);
	}

	function displayUser($usr_id)
	{
		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		echo $row["usr_vorname"] . " ".$row["usr_nachname"];
	}

	function displayChangeStatus($usr_id,$datum)
	{
		global $myDB;
		
		
		$sql = "SELECT * FROM user WHERE usr_id = " . (int)$usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if (mysql_num_rows($rs)!=0)
		{
		  $user = $row["usr_vorname"]. " " . $row["usr_nachname"];
		  echo localeH("msg_last_change_by_user",array(localeFullTime($datum),$user));
		}
		else 
		{
		  echo localeH("msg_last_change_anonymous",array(localeFullTime($datum)));
		}
	}

	function displayCreationStatus($usr_id,$datum)
	{
		global $myDB;
		if ($datum==0){return;} // Datensätze vor 2.2
		
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if (mysql_num_rows($rs)!=0)
		{
		  $user = $row["usr_vorname"]. " " . $row["usr_nachname"];
		  echo localeH("msg_creation_date_by_user",array(localeFullTime($datum),$user));
		}
		else 
		{
		  echo localeH("msg_creation_date_anonymous",array(localeFullTime($datum)));
		}
	}

	// Funktionen fuer Code-Editing mit HTML-Area

	function buildHTMLTextArea($name,$filename,$cols,$rows,$mode="PHP",$x=640)
	{
		global $myLayout;
		$myLayout->form_HTMLTextarea($name,$filename,$cols,$rows,$mode,$x);
	}

	function get_filecontents_highlighted($filename)
	{
		global $myPT;
		$fp = fopen ($filename,"r");
		$buffer ="";
		if($fp)
		{
			while (!feof($fp))
			{
				$buffer .= fgets($fp, 4096);
			}
		}
		fclose ($fp);
		if ($buffer=="")
		{
			return('<font size="3"><pre></pre></font>');
		}
		
		// backslash
		$buffer = str_replace(chr(92),"_CHR_ASCII_92_",$buffer);
		
		// highlight the source
		// first, add the cheat php tag to activate the damn php highlighting function
		$buffer = "<?php _CHEAT_SHOW_SOURCE_". $buffer ."_CHEAT_SHOW_SOURCE_ ?>";
		// highlight the stuff
		$buffer = highlight_string($buffer, true);
		
		// remove the fake tags added above
		$buffer = str_replace('&lt;?php&nbsp;_CHEAT_SHOW_SOURCE_','',$buffer);
		$buffer = str_replace('_CHEAT_SHOW_SOURCE_&nbsp;?&gt;','',$buffer);
		
		// replace the backslashes again
		$buffer = str_replace('_CHR_ASCII_92_',chr(92),$buffer);

		return ('<font size="3">' .htmlentities($buffer) . '</font>');
	}
	
	function decodeRequest_HTMLArea($code)
	{
		// Erst alle echten Returns raus ..
		$code = str_replace(chr(10),'',$code);
		$code = str_replace(chr(13),'',$code);

		// Störende Spaces austauschen
		$code = str_replace(chr(160),chr(32),$code);

		// Und dann wieder aus dem HTML erzeugen
		$return = chr(10);
		$code = str_replace('<BR>',$return,$code);
		$code = str_replace('<br />',$return,$code);
		$code = str_replace('<br/>',$return,$code);
		$code = str_replace('</p>',$return,$code);

		$code = str_replace('&nbsp;',chr(32),$code);

		$code = strip_tags($code);
		$code = html_entity_decode($code);



		// Irgendwie haengt nach dem 1. Speichern ein Leerzeichen zuviel dran
		// und wird abgeschnitten
		$l = strlen($code);
		$c = substr($code,$l-1);
		if (ord($c)==32)
		{
			$code = substr($code,0,$l-1);
		}


		// Returns am Anfang sind nicht erlaubt ...
		$l = strlen($code);
		if ($l>32){ $l==32;}
		for ($i=0;$i<$l;$i++)
		{
			if (ord($code[$i])!=10 AND ord($code[$i])!=13)
			{
				$code = substr($code,$i);
				break;
			}
		}

		// Returns am Ende sind nicht erlaubt ...
		$l = strlen($code);
		for ($i=1;$i<=32;$i++)
		{
			if ((ord($code[$l-$i])!=10) AND (ord($code[$l-$i])!=13))
			{
				$code = substr($code,0,$l-$i+1);
				break;
			}
		}

		$s="";
		for ($i=0;$i<=strlen($code);$i++)
		{
			$s.= $code[$i] . ":" . ord($code[$i]) . "\n";
		}
		//$code = $s;
		// Ergänzung seit 2.3
		$code =trim($code);
		return ($code);
	}

	function decodeRequest_TextArea($code)
	{
		// Nichts zu tun ...
		//$code = stripslashes($code);
		return ($code);
	}

	function explorer_prepare($modul,$submodul)
	{
		$this->props_explorer = Array();
		$this->explorer_set("modul",$modul);
		$this->explorer_set("submodul",$submodul);
	}

	function explorer_set($key,$val)
	{
		$this->props_explorer[$key]=$val;
	}

	function explorer_get($key)
	{
		return @$this->props_explorer[$key];
	}

	function explorer_draw()
	{
		global $myLayout;
		if ($this->explorer_get("modul")==locale("Editor"))
		{
			switch ($this->explorer_get("submodul"))
			{
				case locale("Content");
				$myLayout->explorer_redaktion_content_draw();
				break;

				case locale("Media"):
					$myLayout->explorer_redaktion_media_draw();
					break;

				default:
					$myLayout->explorer_redaktion_seiten_draw();
					break;
			}
		}
		if ($this->explorer_get("modul")==locale("Config"))
		{
			$myLayout->explorer_konfiguration_draw($this->explorer_get("submodul"));
		}
		if ($this->explorer_get("modul")==locale("Admin"))
		{
			$myLayout->explorer_admin_draw($this->explorer_get("submodul"));
		}
		if ($this->explorer_get("modul")==locale("Tasks"))
		{
			$myLayout->explorer_aufgaben_draw();
		}

	}


	
	function readDirComplete($dir)
	{
		if ($fp= @opendir($dir))
		{
			while (($file = readdir($fp)) !== false)
			{
				if (($file == ".") || ($file == ".."))
				{
					continue;
				}
				if (is_dir($dir . '/' . $file))
				{
					$_subfiles=$this->readDirComplete($dir . '/' . $file);
					if (is_array($_subfiles))
					{
						$_files = array_merge($_files,$_subfiles);
					}
				}
				else
				{
					$_files[] = $dir .'/'.$file;
				}
			}
		}
		return ($_files);
	}

	function removeDirComplete ($dir,$keep=0,$debug=0)
	{
		if ($fp= @opendir($dir))
		{
			while (($file = readdir($fp)) !== false)
			{
				if (($file == ".") || ($file == ".."))
				{
					continue;
				}
				if (is_dir($dir . '/' . $file))
				{
					$this->removeDirComplete($dir . '/' . $file,0,$debug);
				}
				else
				{
					if ($debug==1)
					{
						echo ($dir . '/' . $file);
					}
					else 
					{
						unlink($dir . '/' . $file);
					}
				}
			}
			@closedir($fp);
			if ($keep==0 AND $debug==0){rmdir ($dir);}
		}
	}


	function copyDirComplete ($dir_source,$dir_target,$copysvn=0)
	{
		if (!file_exists($dir_target))
		{
			mkdir($dir_target ,UMASK);
		}
		if ($fp= @opendir($dir_source))
		{
			while (($file = readdir($fp)) !== false)
			{
				if (($file == ".") || ($file == ".."))
				{
					continue;
				}
				if ($copysvn==0 && $file ==".svn")
				{
					continue;
				}
				if (is_dir($dir_source . '/' . $file))
				{
					$this->copyDirComplete($dir_source . '/' . $file,$dir_target . '/' . $file,$copysvn);
				}
				else
				{
					copy ($dir_source . '/' . $file,$dir_target . '/' . $file);
					chmod ($dir_target . '/' . $file,UMASK);

				}
			}
			@closedir($fp);
		}
	}
	
	
	/* ************************************	
	 * Ab hier Funktionen zum Konfigurieren von Phenotypeanwendungen
	 * ************************************/

	function cfg_removeComponent($id)
	{
		global $myPT;
		global $myDB;

		// Erst die Templates weg

		$sql = "SELECT * FROM component_template WHERE com_id = " . $id . " ORDER BY tpl_id";
		$rs = $myDB->query($sql);
		while ($row_ttp=mysql_fetch_array($rs))
		{
			$dateiname = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $id, $row_ttp["tpl_id"]);
			@unlink($dateiname);
		}
		$sql = "DELETE FROM component_template WHERE com_id = " . $id;
		$myDB->query($sql);

		// Jetzt die eigentliche Komponente
		$dateiname = APPPATH . "components/PhenotypeComponent_"  .$id . ".class.php";
		@unlink($dateiname);

		$sql = "DELETE FROM component WHERE com_id = " . $id;
		$myDB->query($sql);

		$sql = "DELETE FROM sequence_data WHERE com_id = " . $id;
		$myDB->query($sql);

		$sql = "DELETE FROM component_componentgroup WHERE com_id = " . $id;
		$myDB->query($sql);

	}

	function cfg_removeComponentgroup($id)
	{
		global $myPT;
		global $myDB;

		$sql = "DELETE FROM component_componentgroup WHERE cog_id = " . $id;
		$myDB->query($sql);

		$sql = "DELETE FROM componentgroup WHERE cog_id = " . $id;
		$myDB->query($sql);

		$dateiname = APPPATH . "components/toolkit" . $id . ".inc.html";
		@unlink($dateiname);
	}

	function cfg_removeInclude($id)
	{
		// Hinweis:
		// Falls Includes Layouts, Seiten oder dem Includebaustein zugeordnet sind, dann
		// werden diese Zuordnungen nicht entfernt!
		// Das ermöglicht ein Reimportieren eines Includes ohne Datenverlust

		global $myPT;
		global $myDB;

		// Erst die Templates weg

		$sql = "SELECT * FROM include_template WHERE inc_id = " . $id . " ORDER BY tpl_id";
		$rs = $myDB->query($sql);
		while ($row_ttp=mysql_fetch_array($rs))
		{
			$dateiname = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $id, $row_ttp["tpl_id"]);
			@unlink($dateiname);
		}
		$sql = "DELETE FROM include_template WHERE inc_id = " . $id;
		$myDB->query($sql);

		// Jetzt die eigentliche Komponente
		$dateiname = APPPATH . "includes/PhenotypeInclude_"  .$id . ".class.php";
		@unlink($dateiname);

		$sql = "DELETE FROM include WHERE inc_id = " . $id;
		$myDB->query($sql);



	}

	function cfg_removeContent($id)
	{
		// Hinweis:
		// Falls Datensätze vorhanden sind oder irgendwo auf Datensätze referenziert wird,
		// werden diese nicht gelöscht bzw. die Referenzen aufgelöst
		// Das ermöglicht ein Reimportieren der Contentklassen ohne Datenverlust

		global $myPT;
		global $myDB;

		// Erst die Templates weg

		$sql = "SELECT * FROM content_template WHERE con_id = " . $id . " ORDER BY tpl_id";
		$rs = $myDB->query($sql);
		while ($row_ttp=mysql_fetch_array($rs))
		{
			$dateiname = $myPT->getTemplateFileName(PT_CFG_CONTENTCLASS, $id, $row_ttp["tpl_id"]);
			@unlink($dateiname);
		}
		$sql = "DELETE FROM content_template WHERE con_id = " . $id;
		$myDB->query($sql);

		// Jetzt die eigentliche Contentklasse
		$dateiname = APPPATH . "content/PhenotypeContent_"  .$id . ".class.php";
		@unlink($dateiname);

		$sql = "DELETE FROM content WHERE con_id = " . $id;
		$myDB->query($sql);


	}
	
	
	function cfg_removeLayout($id)
	{
		global $myDB;
		global $myPT;
			$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "normal");
  		@unlink ($dateiname);
			$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $id, "print");
  		@unlink ($dateiname);
  
  		$sql = "DELETE FROM layout_block WHERE lay_id = " . $id;
  		$myDB->query($sql);  
  		$sql = "DELETE FROM layout_include WHERE lay_id = " . $id;
  		$myDB->query($sql);    
  		$sql = "DELETE FROM layout WHERE lay_id = " . $id;
  		$myDB->query($sql);      
	}

	function cfg_removeExtra($id)
	{
		global $myPT;
		global $myDB;

		// Erst die Templates weg

		$sql = "SELECT * FROM extra_template WHERE ext_id = " . $id . " ORDER BY tpl_id";
		$rs = $myDB->query($sql);
		while ($row_ttp=mysql_fetch_array($rs))
		{
			$dateiname = $myPT->getTemplateFileName(PT_CFG_EXTRA, $id, $row_ttp["tpl_id"]);
			@unlink($dateiname);
		}
		$sql = "DELETE FROM extra_template WHERE ext_id = " . $id;
		$myDB->query($sql);

		// Jetzt die eigentliche Extraklasse
		$dateiname = APPPATH . "extras/PhenotypeExtra_"  .$id . ".class.php";
		@unlink($dateiname);

		$sql = "DELETE FROM extra WHERE ext_id = " . $id;
		$myDB->query($sql);


	}
	
	
	function cfg_removeAction($id)
	{
		global $myPT;
		global $myDB;

		// Jetzt die eigentliche Extraklasse
		$dateiname = APPPATH . "actions/PhenotypeAction_"  .$id . ".class.php";
		@unlink($dateiname);

		$sql = "DELETE FROM action WHERE act_id = " . $id;
		$myDB->query($sql);


	}	


	function cfg_cleanupTemp()
	{
		$dir = TEMPPATH;
		$this->removeDirComplete($dir,1);

		$_dirs = Array("application","backup","console","contentupload","htmlarea","install","logs","media","package","previewcache","smarty","snapshot");
		foreach ($_dirs AS $k)
		{
			mkdir ($dir.$k);
			@chmod ($dir.$k,UMASK);
		}

		$script = '<?php
// Phenotype Script Console   
//   
// For simple php test actions to avoid the usally forgotten test.php files on the server ...   

phpinfo();   
    
?>';	
		$dateiname = $dir ."console/console.inc.php";
		$fp = fopen ($dateiname,"w");
		fputs ($fp,$script);
		fclose ($fp);
		@chmod ($dateiname,UMASK);
	}

	function cfg_rebuildTempPackageStructure()
	{

		$dir = TEMPPATH;
		$this->removeDirComplete($dir."package",1);

		mkdir ($dir."package/config/");
		@chmod ($dir."package/config/",UMASK);

		mkdir ($dir."package/data/");
		@chmod ($dir."package/data/",UMASK);

		mkdir ($dir."package/htdocs/");
		@chmod ($dir."package/htdocs/",UMASK);

		mkdir ($dir."package/storage/");
		@chmod ($dir."package/storage/",UMASK);

		$_dirs = Array("actions","backend","components","content","extras","includes","languagemaps","mediabase","page","roles","ticketsubjects");
		foreach ($_dirs AS $k)
		{
			mkdir ($dir."package/config/".$k);
			@chmod ($dir."package/config/".$k,UMASK);
		}
		$_dirs = Array("content","media","pages","users","tickets");
		foreach ($_dirs AS $k)
		{
			mkdir ($dir."package/data/".$k);
			@chmod ($dir."package/data/".$k,UMASK);
		}

		$script = '<?php
class PhenotypePackage extends PhenotypePackageStandard
{
	public $bez = "New Package";
	public $packagefolder = "0000_XXXXXXX";
	
	function getDescription()
	{
		return ("");
	}
}
?>';

		$dateiname = $dir ."package/PhenotypePackage.class.php";
		$fp = fopen ($dateiname,"w");
		fputs ($fp,$script);
		fclose ($fp);
		@chmod ($dateiname,UMASK);
	}

}




?>
<?php
// Hilfsfunktionen einladen
require (ADMINPATH . "kses/kses.php");
if (!defined("UMASK"))
{
	define ("UMASK",0775);
}
?>