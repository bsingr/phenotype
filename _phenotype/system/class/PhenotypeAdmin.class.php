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
			case "Start":	
				$nr=1;
			break;
			case "Redaktion":	
				$nr=2;
			break;	
			case "Extras":	
				$nr=3;
			break;	
			case "Analyse":	
				$nr=4;
			break;	
			case "Aufgaben":	
				$nr=5;
			break;	
			case "Admin":	
				$nr=6;
			break;	
			case "Konfiguration":	
				$nr=7;
			break;													
			case "Info":	
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
		if ($mode==0) // Seite nicht highliten
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
		?>Letzte &Auml;nderung am <?php echo date("d.m.Y H:i",$datum) ?><?php
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if (mysql_num_rows($rs)!=0)
		{
	?>
	 durch <?php echo $row["usr_vorname"] ?><?php if ($row["usr_vorname"]!="" AND $row["usr_nachname"]!=""){echo" ";} ?><?php echo $row["usr_nachname"] ?>.<?php
		}
	}

	function displayCreationStatus($usr_id,$datum)
	{
		global $myDB;
		if ($datum==0){return;} // Datensätze vor 2.2
		?>Angelegt am <?php echo date("d.m.Y H:i",$datum) ?><?php
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if (mysql_num_rows($rs)!=0)
		{
	?>
	 durch <?php echo $row["usr_vorname"] ?><?php if ($row["usr_vorname"]!="" AND $row["usr_nachname"]!=""){echo" ";} ?><?php echo $row["usr_nachname"] ?>.<?php
		}
	}

	// Funktionen fuer Code-Editing mit HTML-Area

	function buildHTMLTextArea($name,$filename,$cols,$rows,$mode="PHP",$x=640)
	{

		if ($this->browserOK_HTMLArea())
		{
	?>
	<textarea cols="<?php echo $cols ?>" rows="<?php echo $rows ?>" wrap="physical" name="<?php echo $name ?>"  id="<?php echo $name ?>" class="input"  style="width: <?php echo $x ?>px"><?php
	if (file_exists($filename))
	{
		switch ($mode)
		{
			case "PHP":
				echo $this->readPHP_HTMLArea($filename);
				break;
			default:
				echo $this->readHTML_HTMLArea($filename);
				break;
		}
		/*if ($mode=="PHP")
		{
		echo $this->readPHP_HTMLArea($filename);
		}
		else
		{
		echo $this->readHTML_HTMLArea($filename);
		}*/
	}
	else
	{
		echo'<font size="3"><pre></pre></font>';
	}
	?></textarea>
	<?php
	if ($this->whichHTMLArea()==3)
	{
	?>
	<script language="JavaScript1.2">
	var config = new HTMLArea.Config();
	config.width = '<?php echo $x ?>px';
	config.height = '<?php echo $rows*25 ?>px';
	config.pageStyle = 'body { background-color: white} ';
	config.toolbar = [['popupeditor']];
	HTMLArea.replace('<?php echo $name ?>', config);
	</script>
    <?php
	}
	?>
	<?php
	if ($this->whichHTMLArea()==2)
	{
	?>
	<script language="JavaScript1.2">
	var config = new Object();
	config.toolbar = [['popupeditor']];
	editor_generate('<?php echo $name ?>',config);
	</script>
    <?php
	}
	?>	
	<?php
	if ($this->whichHTMLArea()==4)
	{
	?>
	<script language="JavaScript1.2">
	var oFCKeditor = new FCKeditor( '<?php echo $name ?>' ) ;
	oFCKeditor.BasePath	= '<?php echo ADMINURL ?>/fckeditor/' ;
	oFCKeditor.Height = <?php echo $rows*20 ?> ;
	oFCKeditor.Width = <?php echo $x ?>;
	oFCKeditor.ToolbarSet = "Coding" ;
	oFCKeditor.Config["CustomConfigurationsPath"] = "<?php echo ADMINURL ?>/fckconfig.php";
	oFCKeditor.ReplaceTextarea() ;
	</script>

    <?php
	}
	?>		
	<?php
		}
		else
		{
	?>
	<textarea cols="<?php echo $cols ?>" rows="<?php echo $rows ?>" wrap="physical" name="<?php echo $name ?>"  style="width:<?php echo $x ?>px" class="input"><?php
	if (file_exists($filename))
	{
		if ($mode=="PHP")
		{
			echo $this->readPHP_TextArea($filename);
		}
		else
		{
			echo $this->readHTML_TextArea($filename);
		}
	}
	?></textarea>		
    <?php
		}
	}

	function readPHP_HTMLArea($filename)
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
		if ($buffer==""){return('<font size="3"><pre></pre></font>');}
		$buffer = str_replace(chr(92),"_CHR_ASCII_92_",$buffer);
		$filename_bak = TEMPPATH ."htmlarea/~" . time() . ".tmp";


		$fp = fopen ($filename_bak,"w");
		fputs($fp,$buffer);
		fclose ($fp);
		@chown ($filename_bak,UMASK);
		$myPT->startbuffer();
		show_source($filename_bak);
		$buffer = $myPT->stopbuffer();
		$buffer = str_replace('_CHR_ASCII_92_',chr(92),$buffer);
		unlink($filename_bak);

		return ('<font size="3">' .htmlentities($buffer) . '</font>');
	}

	function readHTML_HTMLArea($filename)
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
		if ($buffer=="")return('<font size="3"><pre></pre></font>');
		$buffer = "<?php _CHEAT_SHOW_SOURCE_" . $buffer . "_CHEAT_SHOW_SOURCE_ ?>";
		$buffer = str_replace(chr(92),"_CHR_ASCII_92_",$buffer);
		$filename_bak = TEMPPATH ."htmlarea/~" . uniqid("") . ".tmp";
		$fp = fopen ($filename_bak,"w");
		fputs($fp,$buffer);
		fclose ($fp);
		@chown ($filename_bak,UMASK);
		$myPT->startbuffer();
		show_source($filename_bak);
		$buffer = $myPT->stopbuffer();
		$buffer = str_replace('_CHR_ASCII_92_',chr(92),$buffer);
		$buffer = str_replace('&lt;?php _CHEAT_SHOW_SOURCE_',"",$buffer);
		$buffer = str_replace('_CHEAT_SHOW_SOURCE_?&gt;',"",$buffer);
		// Spaeter andere Farbkodierung nachruesten
		//$buffer = str_replace("font","bond",$buffer);
		//$buffer = str_replace('#0000BB',"000000",$buffer);

		unlink($filename_bak);
		return ('<font size="3">' .htmlentities($buffer) . '</font>');
	}

	function readPHP_TextArea($filename)
	{
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
		return htmlspecialchars($buffer);
	}

	function readHTML_TextArea($filename)
	{
		return $this->readPHP_TextArea($filename);
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

	function browserOK_HTMLArea()
	{

		return true;
		/*
		// Achtung Funktion falsch, da eigentlich nur MSIE 5.5 +
		$browser = $_SERVER["HTTP_USER_AGENT"];
		//$browser = $_ENV["HTTP_USER_AGENT"];

		if( eregi("(msie) ([0-9]{1,2}.[0-9]{1,3})",$browser,$regs) )
		{
		return true;
		}
		else
		{
		return false;
		}*/
	}


	function whichHTMLArea()
	{
		$version=0;
		$agent = $_SERVER["HTTP_USER_AGENT"];
		if ($agent==""){$agent=$_ENV["HTTP_USER_AGENT"];}
		$agent = strtoupper($agent);
		if (strpos($agent,"MSIE"))
		{
			$version = 2;
		}
		if (strpos($agent,"NETSCAPE"))
		{
			$version = 3;
		}
		if (strpos($agent,"FIREFOX"))
		{
			$version = 3;
		}

		// Override durch FCKEditor
		$version = 4;

		return $version;
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
		if ($this->explorer_get("modul")=="Redaktion")
		{
			switch ($this->explorer_get("submodul"))
			{
				case "Content";
				$myLayout->explorer_redaktion_content_draw();
				break;

				case "Media":
					$myLayout->explorer_redaktion_media_draw();
					break;

				default:
					$myLayout->explorer_redaktion_seiten_draw();
					break;
			}
		}
		if ($this->explorer_get("modul")=="Konfiguration")
		{
			$myLayout->explorer_konfiguration_draw($this->explorer_get("submodul"));
		}
		if ($this->explorer_get("modul")=="Admin")
		{
			$myLayout->explorer_admin_draw($this->explorer_get("submodul"));
		}
		if ($this->explorer_get("modul")=="Aufgaben")
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
// Phenotype Skript Konsole   
//   
// Für das Skript zwischendurch, um die üblichen test.php-Dateien auf dem   
// Server zu vermeiden ...   

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