<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Kr‰mer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


// Includes werden ¸ber Autoload geladen
function __autoload($class_name) {


	if (substr($class_name,0,19)=="PhenotypeComponent_")
	{
		$file =  APPPATH . "components/". $class_name . '.class.php';
		if (file_exists($file))
		{
			require_once ($file);
			return;
		}
		else
		{
			// Even if a component is unknow, editing will continue !

			$php = "class " . $class_name . " extends PhenotypeComponent {}";
			eval ($php);
			return;
		}

	}
	if (substr($class_name,0,17)=="PhenotypeInclude_")
	{
		require_once  APPPATH . "includes/". $class_name . '.class.php';
		return;
	}
	if (substr($class_name,0,17)=="PhenotypeContent_")
	{
		require_once  APPPATH . "content/". $class_name . '.class.php';
		return;
	}

	if (substr($class_name,0,15)=="PhenotypeExtra_")
	{
		require_once  APPPATH . "extras/". $class_name . '.class.php';
		return;
	}

	if (substr($class_name,0,16)=="PhenotypeAction_")
	{
		require_once  APPPATH . "actions/". $class_name . '.class.php';
		return;
	}

	if (substr($class_name,0,17)=="PhenotypeBackend_")
	{
		$file = SYSTEMPATH . "backend/". $class_name . '.class.php';
		if (file_exists($file))
		{
			require_once($file);
			$file =  APPPATH . "backend/". $class_name . '.class.php';
			if (file_exists($file))
			{
				require_once($file);
			}
			else
			{
				$php = "class " . $class_name . " extends " . $class_name ."_Standard {}";
				eval ($php);
			}

			return;
		}
		else // keine Systembackendklasse, aber vielleich in der Applikation
		{
			$file =  APPPATH . "backend/". $class_name . '.class.php';
			if (file_exists($file))
			{
				require_once($file);
				return;
			}

		}



	}
	if (PT_DEBUG==1)
	{
		die($class_name ." nicht vorhanden.");
	}
	else
	{
		die();
	}
}




/**
 * @package phenotype
 * @subpackage system
 *
 */
class Phenotype
{
	public $version  = "##!PT_VERSION!##";
	public $subversion = "##!BUILD_NO!##";


	private $_preferences = false;

	private $_phrases = Array();

	function __construct()
	{
		if (ini_get("register_globals")==1)
		{
			die("Bitte stellen Sie Register Globals aus !! Aus Sicherheitsgr¸nden l‰uft Phenotype nicht mit dieser Einstellung");
		}
	}

	function getPage($id=-1)
	{
		$myPage = new PhenotypePage();
		$myPage->init($id);
		return $myPage;
	}


	function startBuffer()
	{
		ob_start();
	}

	function stopBuffer()
	{
		$s=ob_get_contents();
		ob_end_clean();
		return $s;
	}


	function customizeToolkit ($cog_id)
	{
		global $myDB;
		$sql = "SELECT * FROM component_componentgroup INNER JOIN component ON component_componentgroup.com_id = component.com_id WHERE cog_id = " . $cog_id . " ORDER BY com_bez";
		$rs = $myDB->query ($sql);
		$html = "";
		while ($row = mysql_fetch_array($rs))
		{
			$html .='<option value="'. $row["com_id"].'">' .$row["com_bez"] .'</option>';
		}

		$dateiname = APPPATH . "components/toolkit" . $cog_id . ".inc.html";
		$fp = fopen ($dateiname,"w");
		fputs ($fp,$html);
		fclose ($fp);
		@chown ($dateiname,UMASK);
	}


	function displaySequence($pag_id,$ver_id,$dat_id_content,$block_nr,$editbuffer=0,$usr_id=0)
	{
		global $myDB;
		$html_block = "";
		$sql="";
		if ($pag_id!=0)
		{
			$sql = "SELECT * FROM sequence_data WHERE pag_id = " . $pag_id . " AND ver_id = ". $ver_id . " AND dat_blocknr=" . $block_nr;
		}
		if ($dat_id_content!=0)
		{
			$sql = "SELECT * FROM sequence_data WHERE dat_id_content = " . $dat_id_content . " AND dat_blocknr=" . $block_nr;
		}
		if ($sql==""){return false;}
		if ($editbuffer==1)
		{
			if ($usr_id==0)
			{
				$usr_id = $_SESSION["usr_id"];
			}
			$sql .= " AND dat_editbuffer=1 AND usr_id=".$usr_id;
		}
		else
		{
			$sql .= " AND dat_editbuffer=0";
		}

		$sql .= " AND dat_visible = 1 ORDER BY dat_pos";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$tname = "PhenotypeComponent_" . $row["com_id"];
			$myComponent = new $tname;
			$myComponent->init($row);
			$html_block .= $myComponent->render(1); // Defaultstyle
		}
		echo $html_block;
	}

	/* *****************
	* Allgemeine Funktionen
	* *****************/
	function cutLeft($s,$n)
	{
		$l = strlen($s);
		if ($l>=$n)
		{
			$s = substr($s,0,($n-2));
			$p = strrpos($s," ");
			$s = substr($s,0,$p);
			$s .="...";
		}
		return $s;
	}

	/* *****************
	* Datums- und Kalenderfunktionen
	* *****************/
	function nextWeekDay($datum,$mode=0)
	{

		while (date("w",$datum)==0 OR date("w",$datum)==6)
		{
			$datum = mktime ( 0, 0, 0, date('m',$datum), (date('d',$datum))+1, date('y',$datum));
		}
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function nextMonday($datum,$mode=0)
	{

		while (date("w",$datum)!=1)
		{
			$datum = mktime ( 0, 0, 0, date('m',$datum), (date('d',$datum))+1, date('y',$datum));
		}
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function nextFriday($datum,$mode=0)
	{

		while (date("w",$datum)!=5)
		{
			$datum = mktime ( 0, 0, 0, date('m',$datum), (date('d',$datum))+1, date('y',$datum));
		}
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function nextMonth($datum,$mode=0)
	{
		$monat = date("n",$datum);
		while (date("n",$datum)==$monat)
		{
			$datum = mktime ( 0, 0, 0, date('m',$datum), (date('d',$datum))+1, date('y',$datum));
		}
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function lastDayinMonth($datum,$mode=0)
	{
		$d = date("t",$datum);
		$datum = mktime ( 0, 0, 0, date('m',$datum), $d, date('y',$datum));
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function firstDayinMonth($datum,$mode=0)
	{
		$datum = mktime ( 0, 0, 0, date('m',$datum),1, date('y',$datum));
		if ($mode==1)
		{return (date("d.m.Y",$datum));}
		else
		{return ($datum);}
	}

	function german2Timestamp($date)
	{
		$array=Array();
		ereg ("([0-9]?[0-9]).([0-9]?[0-9]).([0-9]?[0-9]?[0-9]?[0-9])", $date, $array);
		if((@$array[0]=="")OR(@$array[2]=="")OR(@$array[3]=="")){return "";}
		if ($array[3]<100)
		{
			if ($array[3]>50)
			{
				$array[3]= $array[3]+1900;
			}
			else
			{
				$array[3]= $array[3]+2000;
			}
		}
		return @mktime (0,0,0, $array[2], $array[1], $array[3]);
	}

	function germanDT2Timestamp($date)
	{
		$p = strpos($date," ");
		if ($p!==false)
		{
			$s1= substr($date,0,$p);
			$t = $this->german2Timestamp($date);
			$s2 = substr($date,$p+1);
			$p = strpos($s2,":");
			$h = substr($s2,0,$p);
			$m = substr($s2,$p+1);
			$t = $t + ($h*60*60) + ($m*60);
			return $t;
		}
		else
		{
			return $this->german2Timestamp($date);
		}
	}

	function cutString ($s, $pos,$maxlen=0)
	{
		$border = $pos;
		$_s = split(" ",$s);
		if($maxlen!=0 AND count($_s)==1){$border=$maxlen;}
		if (strlen($s)<$border)
		{
			return $s;
		}
		$s =  substr ( $s, 0, $pos);
		$pos = strrpos ($s, " ");
		if ($pos!==false)
		{
			$s =  substr ( $s, 0, $pos);
		}
		if ($maxlen!=0)
		{

			$_s = split(" ",$s);
			$s="";
			for ($i=0;$i<count($_s);$i++)
			{
				if (strlen($_s[$i])<=$maxlen)
				{
					$s .= $_s[$i];
					if ($i<count($_s)){$s.=" ";}
				}
				else
				{
					$s .= substr($_s[$i],0,($maxlen-4));
					break;
				}
			}
			//print_r($_s);
		}
		$s = $s." ...";
		return $s;
	}


	function clearcache_page($id,$silent=1)
	{
		$id = (int)$id;
		$silent = (int)$silent;

		global $myDB;
		$sql = "UPDATE page SET pag_nextbuild1 = " . (time()-1) . ", pag_nextbuild2 = " . (time()-1) . ",pag_nextbuild3 = " . (time()-1) . ",pag_nextbuild4 = " . (time()-1) . ",pag_nextbuild5 = " . (time()-1) . ",pag_nextbuild6 = " . (time()-1) . " WHERE pag_id = " . $id;
		$myDB->query($sql);
		$sql = "SELECT * FROM page WHERE pag_id = " . $id;
		$rs = $myDB->query($sql);
		$row=mysql_fetch_array($rs);
		if ($silent!=1)
		{
			echo "- " . $row["pag_bez"] . "<br>";
		}

	}

	function clearcache_subpages($id,$silent=1)
	{
		if ($id==0)
		{
			global $myDB;
			$sql = "UPDATE page SET pag_nextbuild1 = " . (time()-1) . ", pag_nextbuild2 = " . (time()-1) . ",pag_nextbuild3 = " . (time()-1) . ",pag_nextbuild4 = " . (time()-1) . ",pag_nextbuild5 = " . (time()-1) . ",pag_nextbuild6 = " . (time()-1);
			$myDB->query($sql);
			return;
		}
		global $myDB;
		$sql = "SELECT * FROM page WHERE pag_id_top = " . $id;
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$id = $row["pag_id"];
			$this->clearcache_page($id,$silent);
			$this->clearcache_subpages($id,$silent);

		}
	}


	/* ********************
	* Kodierungsfunktionen
	* ********************/
	function strip_tags($s)
	{
		global $myApp;
		return ($myApp->richtext_strip_tags($s));
	}


	function printHTML($text)
	{
		return @htmlentities(stripslashes($text));
	}
	function printH($text)
	{
		return $this->printHTML($text);
	}


	function buildOptionsBySQL($sql,$selectedKey="")
	{
		// erwartet SQL mit "key" + "value"
		global $myDB;
		$html = "";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$selected ="";
			if ($row["K"] == $selectedKey)
			{
				$selected = "selected";
			}
			$html .='<option value="'. $row["K"] .'" ' . $selected . '>' . $row["V"] . '</option>';
		}
		return $html;
	}

	function buildOptionsByNamedArray($options,$selectedKey="")
	{
		global $myDB;
		$html = "";
		foreach ($options As $key => $value)
		{
			$selected ="";
			if ($key == $selectedKey)
			{
				$selected = "selected";
			}
			$html .='<option value="'. $key .'" ' . $selected . '>' . $value . '</option>';
		}
		return $html;
	}

	function displayCO($con_id,$dat_id,$skin,$count=0)
	{
		// zeigt die gecachten Contentdateien an, ohne DB-Zugriff
		$cname= "PhenotypeContent_" . $con_id;
		$myCO = new $cname();
		$myCO->id = $dat_id;
		$myCO->display($skin,$count);
	}

	function renderCO($con_id,$dat_id,$skin,$count=0)
	{
		$this->startbuffer();
		$this->displayCO($con_id,$dat_id,$skin,$count);
		return $this->stopBuffer();
	}

	function uid()
	{
		return md5 (uniqid (rand()));
	}

	// Funktion zum strukturierten Ausgeben von Arrays
	function debugPrint($_a)
	{
		echo "<pre>";
		print_r($_a);
		echo "</pre>";
	}

	// Funktion zur R¸ckgabe des Klassen-Namens
	function getContentClassName($con_id)
	{
		return "PhenotypeContent_".$con_id;
	}

	// Funktion zum Herausfinden des Klassen-Namens anhand einer Item ID
	function getContentClassNameByDatId($id)
	{
		global $myDB;
		$sql = "SELECT con_id FROM content_data WHERE dat_id =".$id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_arry($rs);

		$ret = "PhenotypeContent_".$row["con_id"];

		return $ret;
	}

	/**
	 * calcs the filename of a template
	 * 
	 * @param	type		use constants for type (see switch statement below)
	 * @param obj_id	the id of the include, contentobject, component
	 * @param tpl_id	the template id, or in case of page templates "normal" or "print"
	 * @param	base		the base path. if empty, absolute path is used
	 *
	 * @return	string	the absolute filename of the template file
	 */
	function getTemplateFileName($type, $obj_id, $tpl_id, $basePath = "-") {

		// base
		if ($basePath == "-")
		{
			$basePath = APPPATH . "templates/";
		}

		// default schema, like 0005_0003.tpl
		$schema = '%2$04.0f_%3$04.0f_%1$s.tpl';

		// folder and prefix
		switch($type) {
			case PT_CFG_LAYOUT:
				$folder = "page_templates/";
				$prefix = PT_CFG_LAYOUT;
				// extra ordinary, no template numbers!
				$schema = '%2$04.0f.%3$s.tpl';
				break;

			case PT_CFG_INCLUDE:
				$folder = "include_templates/";
				$prefix = PT_CFG_INCLUDE;
				break;

			case PT_CFG_COMPONENT:
				$folder = "component_templates/";
				$prefix = PT_CFG_COMPONENT;
				break;

			case PT_CFG_CONTENTCLASS:
				$folder = "content_templates/";
				$prefix = PT_CFG_CONTENTCLASS;
				break;

			case PT_CFG_EXTRA:
				$folder = "extra_templates/";
				$prefix = PT_CFG_EXTRA;
				break;
		}

		// file name
		$tplFile = $basePath . $folder . sprintf($schema, strtolower($prefix), $obj_id, $tpl_id);

		return $tplFile;
	}

	function xmlencode($s,$keepquotes=0)
	{
		//The following are the valid XML characters and character ranges (hex values) as defined by the W3C XML
		//language specifications 1.0: #x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF]

		// Eliminiert alle Zeichen zwischen #x01 und #x1F (HEX Schreibweise! in dezimal 01 - 31)
		// Diese Zeichen sind in XML nicht erlaubt, sind ASCII Steuerzeichen
		// Ausnahmen: #x9 | #xA | #xD
		$pat = "/[\x01-\x08]/";
		$s =  preg_replace($pat,"",$s);
		$pat = "/[\x0B-\x0C]/";
		$s =  preg_replace($pat,"",$s);
		$pat = "/[\x0E-\x1F]/";
		$s =  preg_replace($pat,"",$s);

		// Kodiert alle Zeichen zwischen ASCII 127 und 159 (dezimal)
		// auﬂerdem werden gematcht: "&'/<> (" -> #x22 in Hex Schreibweise)
		$pat = "/[\x7F-\x9F&<>\/'\\x22\\x00]/";
		$s =  preg_replace_callback($pat,"match2Entity",$s);

		return $s;


		// alter Ansatz: kann entfernt werden, wenn keine Probleme auftauchen

		// Kodiert alle Zeichen zwischen ASCII 1 und 31 sowie 127 und 159
		// auﬂerdem werden gematcht: &'"<>

		//$pat = "/[\1-\37\177-\237&<>'\42]/";
		//$s =  preg_replace_callback($pat,"match2Entity",$s);

		// nicht im Pattern enthalten:
		//$s = str_replace('/',"&#47;",$s);
		//$s = str_replace(chr(0),"&#0;",$s);

		// alter Ansatz: kann entfernt werden, wenn keine Probleme auftauchen
		/*
		$s = str_replace("&","&#38;",$s);
		$s = str_replace("<","&#60;",$s);
		$s = str_replace(">","&#62;",$s);
		$s = str_replace("'","&#39;",$s);
		$s = str_replace('"',"&#34;",$s);
		$s = str_replace('/',"&#47;",$s);
		*/
		return $s;
	}

	// depreceated
	function getX($s)
	{
		return ($this->xmlencode($s));
	}

	function codeX($s)
	{
		return ($this->xmlencode($s));
	}

	// depreceated
	function getH($s)
	{
		return @ htmlentities($s);
	}

	function codeH($s)
	{
		return @ htmlentities($s);
	}

	/**
	 * This functions is used for encoding text before displaying it in html pages. It does an entity encode
	 * but keeps following tags <b>, <strong>, <br>, <br/> and converts newlines to break
	 *
	 * @param unknown_type $s
	 * @return string
	 */
	function codeHKT($s) // HTML KEEP TAGS
	{
		$s = nl2br($s);
		$s = str_replace("<b>","###B###",$s);
		$s = str_replace("<strong>","###B###",$s);
		$s = str_replace("<br>","###BR###",$s);
		$s = str_replace("<br/>","###BR###",$s);
		$s = str_replace("<br />","###BR###",$s);
		$s = str_replace("</b>","###BB###",$s);
		$s = str_replace("</strong>","###BB###",$s);
		$s = @ htmlentities($s);
		$s = str_replace("###B###","<strong>",$s);
		$s = str_replace("###BB###","</strong>",$s);
		$s = str_replace("###BR###","<br/>",$s);
		return $s;
	}

	// depreceated
	function getI($s)
	{
		return (int)$s;
	}

	function codeI($s)
	{
		return (int)$s;
	}


	function codeSQL($s)
	{
		return mysql_escape_string($s);
	}

	function writefile($file,$buffer)
	{
		$fp = fopen ($file,"w");
		fputs ($fp,$buffer);
		fclose ($fp);
		@chmod ($file,UMASK);
	}

	function appendfile($file,$buffer)
	{
		$fp = fopen ($file,"a");
		fputs ($fp,$buffer);
		fclose ($fp);
		@chmod ($file,UMASK);
	}

	function createFolder($path)
	{
		if (!file_exists($path))
		{
			mkdir($path ,UMASK);
		}
	}


	function getPref($token)
	{
		if (!$this->_preferences)
		{
			$this->_preferences = Array();
			$file = APPPATH ."preferences.xml";
			$_xml = simplexml_load_file($file);
			foreach ($_xml->preferences->section AS $_xml_section)
			{
				$name = utf8_decode((string)$_xml_section["name"]);

				foreach ($_xml_section->children() AS $entry => $node)
				{
					$newtoken = $name .".".utf8_decode($entry);
					$v = utf8_decode((string)$node);
					$this->_preferences[$newtoken]=$v;
				}
			}
		}

		return @$this->_preferences[$token];

	}

	function getIPref($token)
	{
		return (int)$this->getPref($token);
	}


	function loadLanguageMap($mapfile,$prefix="")
	{
		$file = SYSTEMPATH . "languagemaps/".$mapfile;
		$_xml = simplexml_load_file($file);

		$language = $this->getPref("backend.language");

		foreach ($_xml->phrases->section AS $_xml_section)
		{
			$name = utf8_decode((string)$_xml_section["name"]);
			if ($prefix !=""){$name = $prefix .".".$name;}
			foreach ($_xml_section->phrase AS $_xml_phrase)
			{
				$token = $name .".".utf8_decode((string)$_xml_phrase["name"]);
				$v = utf8_decode((string)$_xml_phrase->$language);
				if ($v==""){$v = utf8_decode((string)$_xml_phrase->en);}
				$this->_phrases[$token]=$v;

			}

		}
	}


	function displayContentXML($con_id,$mode="")
	{
		global $myDB;

		header("Content-Type: application/xml; charset=iso-8859-1");

		$sql = "SELECT con_id FROM content WHERE con_id=".$con_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			return false;
		}

		$cname = "PhenotypeContent_".$con_id;
		$myCO = new $cname;
		switch ($mode)
		{
			case "rss20":
				$myCO->displayRSS("rss20");
				break;
		}
	}
}


?>
