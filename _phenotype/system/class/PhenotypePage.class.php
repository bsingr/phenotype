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

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypePageStandard extends PhenotypeBase
{
	var $id=-1;
	var $pag_id_mimikry = -1;
	var $bez;
	var $titel;
	var $alttitel;
	var $lay_id;
	var $row;
	var $ver_id;
	var $ver_nr;
	var $ver_bez;
	var $grp_id;
	var $lng_id=1;
	var $pag_id_top;
	var $status = 0;
	var $pos;
	var $pag_url;
	var $pag_date;

	var $inc_id1;
	var $inc_id2;
	var $exec_script;

	var $pagemode;
	var $printmode;
	var $blocknr = 0;
	var $blockHMTL = Array();
	var $includeHMTL = Array();

	var $buildingcache;
	var $includenocache = 0;

	var $_inheritageprops = Array();

	var $mySmarty;

	var $nextbuild=0;
	var $printcache=0;
	var $xmlcache=0;

	public $statistic = false;

	public $loaded = 0;

	public $dat_id_sequence_data = 0;

	public $multilanguage=0;

	public $smarturl_schema = 1;

	//function PhenotypePage($id=0,$ver_id=0,$grp_id=0,$uid=0)
	public function __construct($id=0,$ver_id=0,$grp_id=0,$uid=0)
	{
		$id = (int)$id;
		$ver_id = (int)$ver_id;
		$grp_id = (int)$grp_id;
		$uid = (int)$uid;

		if ($id !=0)
		{
			$this->init($id,$ver_id,$grp_id,$uid);
		}
	}

	function isLoaded()
	{
		return $this->loaded;
	}

	/*
	function set($bez,$val)
	{
	$this->props[$bez] = $val;
	}
	*/

	function get ($key)
	{
		// local values beat inheritage values
		if ($this->check($key))
		{
			return parent::get($key);
		}
		if(array_key_exists($key,$this->_inheritageprops))
		{
			return $this->_inheritageprops[$key];
		}
		return "";


	}

	/*
	function getI ($bez)
	{
	return @(int)($this->props[$bez]);
	//return @stripslashes($this->props[$bez]);
	}

	function getD ($bez,$decimals)
	{
	return sprintf("%01.".$decimals."f",@($this->props[$bez]));
	}

	function getQ ($bez)
	{
	// veraltet
	return ereg_replace('"',"&quot;",stripslashes($this->props[$bez]));
	}

	function getHTML ($bez)
	{
	return @htmlentities(stripslashes($this->props[$bez]));
	}

	function getH ($bez)
	{
	return $this->getHTML($bez);
	}

	function getHBR ($bez)
	{
	$html = nl2br($this->getHTML($bez));
	// Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
	$html = str_replace (chr(10),"",$html);
	$html = str_replace (chr(13),"",$html);
	return ($html);
	}


	function getURL($bez)
	{
	return @urlencode($this->props[$bez]);
	}

	function getU($bez)
	{
	return @utf8_encode($this->props[$bez]);
	}

	function getS($bez)
	{
	return @addslashes($this->props[$bez]);
	}



	/*
	* returns the url to access the site
	* considers the link style
	*
	* @deprecated
	*/
	function getPageUrl($style = "", $dyn=false)
	{
		if (! strlen($style))
		{
			$style = PT_URL_STYLE;
		}

		if ( ($style == "smartURL") && strlen($this->pag_url) )
		{
			$linkUrl = $this->pag_url;
		}
		else
		{
			$linkUrl = "index.php?id=" . $this->id;
		}

		if ($dyn) {
			if (strpos(linkUrl, "?") === false) {
				$linkUrl .= "?";
			} elseif (strpos(linkUrl, "?") < (strlen(linkUrl)-1)) {
				$linkUrl .= "&";
			}
			$linkUrl .= "rand=" . time();
		}

		return ($linkUrl);

	}

	/*
	* @deprecated
	*
	* initializes an object by its url
	* used for cleanUrls
	*
	* :TODO: integrate better with init to prevent double sql select
	*/
	function urlinit($url, $ver_id=0, $grp_id=0)
	{
		global $myDB;

		$ver_id = (int)$ver_id;
		$grp_id = (int)$grp_id;

		$sql  ="SELECT page.*, pagegroup.grp_id, pagegroup.grp_statistic, pagegroup.grp_multilanguage FROM page, pagegroup WHERE pag_url = '". $url ."'";
		if ($grp_id!=0) {
			$sql .= " AND grp_id=".$grp_id;
		}

		$sql .= " AND page.grp_id = pagegroup.grp_id";

		$rs = $myDB->query($sql,"Page: resolving by smartUrl ".$url);

		//echo $sql;
		if (mysql_num_rows($rs)==0)
		{
			return false;
		}

		$row = mysql_fetch_array($rs);

		$this->initByRow($row, $ver_id);
	}

	function uidinit($uid,$ver_id=0,$grp_id=0)
	{
		$uid = (int)$uid;
		$ver_id = (int)$ver_id;
		$grp_id = (int)$grp_id;
		$this->init(0, $ver_id,$grp_id,$uid);
	}

	/*
	* standard init function, initializes by id
	*
	*/
	function init($id,$ver_id=0,$grp_id=0,$uid=0)
	{
		global $myDB;

		// SQL-Exploits ausschliessen
		$id = (int)$id;
		$ver_id = (int)$ver_id;
		$grp_id = (int)$grp_id;
		$uid = (int)$uid;

		$sql  ="SELECT page.*,pagegroup.grp_id, pagegroup.grp_statistic, pagegroup.grp_multilanguage, pagegroup.grp_smarturl_schema FROM page, pagegroup WHERE ";
		if ($id!=0)
		{
			$sql.= "pag_id = " . $id;
			if ($uid!=0)
			{
				$sql .=" AND pag_uid='".$uid."'";
			}
		}
		else
		{
			if ($uid!=0)
			{
				$sql .="pag_uid='".$uid."'";
			}
			else
			{
				$sql .="pag_id=0";
			}
		}

		if ($grp_id!=0)
		{
			$sql .= " AND grp_id=".$grp_id;
		}

		$sql .= " AND page.grp_id=pagegroup.grp_id";

		$rs = $myDB->query($sql,"Page: resolving by ID ". $id);

		if (mysql_num_rows($rs)==0)
		{
			return false;
		}

		$row = mysql_fetch_array($rs);

		$this->initByRow($row, $ver_id);

	}

	/*
	* initializes object with a row array from DB
	*
	*/
	function initByRow ($row, $ver_id=0)
	{
		// :TODO:
		// checken ob in $row was ordentliches drin steht

		// SQL-Exploits ausschliessen
		$ver_id = (int)$ver_id;

		global $myDB;

		// Für den Zugriff über UID
		$id = $row["pag_id"];
		$this->id = $id;
		$this->pag_id = $id;

		$this->uid = $row["pag_uid"];
		$this->bez = $row["pag_bez"];
		$this->titel = $row["pag_titel"];
		$this->alttitel = $row["pag_alttitel"];
		$this->pag_id_mimikry = $row["pag_id_mimikry"];
		$this->status = $row["pag_status"];
		$this->pos = $row["pag_pos"];
		$this->pag_url = $row["pag_url"];
		if ($ver_id==0)
		{
			$this->ver_id = $row["ver_id"];
		}
		else
		{
			$this->ver_id = $ver_id;
		}
		$this->row = $row;
		$this->pag_id_top = $row["pag_id_top"];
		$this->grp_id = $row["grp_id"];

		$this->_inheritageprops = array();
		// load the page properties out of the inheritation tree into props
		if ($row["pag_props_all"]!="")
		{
			$this->_inheritageprops = unserialize($row["pag_props_all"]);
		}
		// load the default properties

		if ($row["pag_props"]!="")
		{
			$this->_props = unserialize($row["pag_props"]);
		}


		$this->nextbuild = $row["pag_nextbuild".CACHENR];
		$this->printcache = $row["pag_printcache".CACHENR];
		$this->xmlcache = $row["pag_xmlcache".CACHENR];
		$this->pag_date = $row["pag_date"];


		$this->statistic = (boolean)$row["grp_statistic"];
		$this->multilanguage = (boolean)$row["grp_multilanguage"];
		$this->smarturl_schema = $row["grp_smarturl_schema"];

		$sql = "SELECT * FROM pageversion WHERE pag_id = " . $id . " AND ver_id=" . $this->ver_id;

		$rs = $myDB->query($sql,"Page ".$id.": initialization");
		if (mysql_num_rows($rs)==0)
		{
			return false;
		}
		$row2 = mysql_fetch_array($rs);
		$this->ver_nr = $row2["ver_nr"];
		$this->ver_bez = $row2["ver_bez"];
		$this->lay_id = $row2["lay_id"];
		$this->inc_id1 = $row2["inc_id1"];
		$this->inc_id2 = $row2["inc_id2"];
		$this->exec_script = $row2["pag_exec_script"];	#

		$this->loaded = true;
	}

	function switchLanguage($lng_id)
	{
		global $PTC_LANGUAGES;
		global $myDB;

		if (!array_key_exists ( $lng_id, $PTC_LANGUAGES))
		{
			return false;
		}
		$this->lng_id = $lng_id;

		if ($this->lng_id <>1)
		{
			$sql = "SELECT pag_titel,pag_nextbuild".CACHENR." AS pag_nextbuild, pag_printcache".CACHENR." AS pag_printcache, pag_xmlcache".CACHENR." AS pag_xmlache FROM page_language WHERE pag_id=".$this->id . " AND lng_id=".$this->lng_id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$this->titel = $row["pag_titel"];
			$this->nextbuild = $row["pag_nextbuild"];
			$this->printcache = $row["pag_printcache"];
			$this->xmlcache = $row["pag_xmlcache"];

		}
	}

	function hasChilds($status=0)
	{
		global $myDB;
		$sql = "SELECT COUNT(pag_id) AS C FROM page WHERE pag_id_top = " . $this->id;
		if ($status==1)
		{
			$sql .=" AND pag_status = 1";
		}
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		return $row["C"];
	}

	function getAllChildrenArray($pag_id,$_pages=Array())
	{
		global $myDB;
		$sql = "SELECT * FROM page WHERE pag_id_top = " .$pag_id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$_pages[] = $row["pag_id"];
			$_pages = $this->getAllChildrenArray($row["pag_id"],$_pages);
		}
		return ($_pages);
	}

	function newPage_FirstInGroup($grp_id,$usr_id=-1)
	{
		return $this->newPage($grp_id,0,1,$usr_id);
	}

	function newPage_RelatedToExisitingPage($page_id,$order,$usr_id=-1)
	{
		global $myDB;
		$sql = "SELECT * FROM page WHERE pag_id = " . $page_id;
		$rs_page = $myDB->query($sql);
		$row_page = mysql_fetch_array($rs_page);

		$pos = $row_page["pag_pos"];
		$top_id = $row_page["pag_id_top"];
		$grp_id = $row_page["grp_id"];

		switch ($order)
		{
			case 1:
				$sql = "UPDATE page SET pag_pos = pag_pos + 1 WHERE pag_id_top = " . $top_id . " AND pag_pos > " . $pos;
				$myDB->query($sql);
				$pos++;
				break;

			case 2:
				$sql = "UPDATE page SET pag_pos = pag_pos + 1 WHERE pag_id_top = " . $top_id . " AND pag_pos >= " . $pos;
				$myDB->query($sql);

				break;

			case 3:
				$pos=1;
				$top_id= $row_page["pag_id"];
				break;
		}

		return $this->newPage($grp_id,$top_id,$pos,$usr_id);
	}


	function newPage($grp_id,$top_id,$pos,$usr_id=-1)
	{
		global $myDB;
		global $myPT;
		global $_PT_HTTP_CONTENTTYPES;


		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_bez","Neue Seite");
		$mySQL->addField("pag_uid",$myPT->uid());
		$mySQL->addField("pag_pos",$pos,DB_NUMBER);
		$mySQL->addField("pag_id_top",$top_id,DB_NUMBER);
		//$mySQL->addField("pag_cache",24*60*60,DB_NUMBER);
		//Get the cache default time from the preferences XML-file | added 07/08/23 by Dominique Bös
		$aXML = $myPT->gaGetPreferencesArray();
		$mySQL->addField("pag_cache",$aXML["preferences"]["section_cache"]["default_cache_seconds"],DB_NUMBER);
		$mySQL->addField("pag_nextbuild1",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild2",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild3",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild4",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild5",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild6",time(),DB_NUMBER);
		$mySQL->addField("pag_lastbuild_time","1");
		$mySQL->addField("pag_lastcache_time","1");
		$mySQL->addField("pag_lastfetch",0,DB_NUMBER);
		$mySQL->addField("pag_date",time(),DB_NUMBER);
		if ($usr_id == -1)
		{
			$usr_id = $_SESSION["usr_id"];
		}
		$mySQL->addField("usr_id",$usr_id);
		$mySQL->addField("pag_creationdate",time(),DB_NUMBER);
		$mySQL->addField("usr_id_creator",$_SESSION["usr_id"]);
		$mySQL->addField("grp_id",$grp_id);

		$contenttype = array_search($myPT->getPref("backend.default_contenttype"), $_PT_HTTP_CONTENTTYPES);
		if (!$contenttype) $contenttype = 1;
		$mySQL->addField("pag_contenttype", $contenttype, DB_NUMBER);

		$sql = $mySQL->insert("page");
		$myDB->query($sql);

		$new_id = mysql_insert_id();

		// 1. Version anlegen
		$mySQL = new SQLBuilder();
		$mySQL->addField("ver_nr",1,DB_NUMBER);
		$mySQL->addField("ver_bez","Version 1");
		$mySQL->addField("pag_id",$new_id,DB_NUMBER);
		$sql = $mySQL->insert("pageversion");
		$myDB->query($sql);
		$ver_id = mysql_insert_id();

		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_id_mimikry",$new_id,DB_NUMBER);
		$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
		$mySQL->addField("ver_nr",1,DB_NUMBER);
		$mySQL->addField("pag_ver_nr_max",1,DB_NUMBER);
		$sql = $mySQL->update("page","pag_id=" . $new_id);
		$myDB->query($sql);

		return ($new_id);
	}

	function copyPage ($pag_id_source,$pag_id_anchor,$order)
	{
		global $myDB;
		// Erst wird eine "normale" neue Seite angelegt ..
		$pag_id_new = $this->newPage_RelatedToExisitingPage($pag_id_anchor,$order);


		$sql = "SELECT * FROM page WHERE pag_id = " .$pag_id_source;
		$rs = $myDB->query($sql);
		$row_page = mysql_fetch_array($rs);

		// Jetzt alle Versionen kopieren
		$sql = "DELETE FROM pageversion WHERE pag_id = " . $pag_id_new;
		$myDB->query($sql);

		$sql = "SELECT * FROM pageversion WHERE pag_id =" . $pag_id_source;
		$rs = $myDB->query($sql);
		$ver_id_start = 0;
		while ($row = mysql_fetch_array($rs))
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("ver_nr",$row["ver_nr"],DB_NUMBER);
			$mySQL->addField("pag_id",$pag_id_new,DB_NUMBER);
			$mySQL->addField("ver_bez",$row["ver_bez"]);
			$mySQL->addField("lay_id",$row["lay_id"],DB_NUMBER);
			$mySQL->addField("inc_id1",$row["inc_id1"],DB_NUMBER);
			$mySQL->addField("inc_id2",$row["inc_id2"],DB_NUMBER);
			$mySQL->addField("pag_exec_script",$row["pag_exec_script"],DB_NUMBER);
			$mySQL->addField("pag_fullsearch",$row["pag_fullsearch"]);
			$sql = $mySQL->insert("pageversion");
			$myDB->query($sql);

			$ver_id_newversion = mysql_insert_id();

			// Seitenskripte kopieren

			if ($row["pag_exec_script"]==1)
			{
				$source = APPPATH . "pagescripts/" .  sprintf("%04.0f", $pag_id_source) . "_" . sprintf("%04.0f", $row["ver_id"]) . ".inc.php";
				$target = APPPATH . "pagescripts/" .  sprintf("%04.0f", $pag_id_new) . "_" . sprintf("%04.0f", $ver_id_newversion) . ".inc.php";
				$code ="";
				$fp = fopen ($source,"r");
				$buffer ="";
				if($fp)
				{
					while (!feof($fp))
					{
						$buffer .= fgets($fp, 4096);
					}
					$code = $buffer;
				}
				fclose ($fp);

				$fp = fopen ($target, "w");
				fputs ($fp,$code);
				fclose ($fp);
				@chmod ($target,UMASK);
			}

			// Ist es die aktuelle Version ?
			if ($row["ver_id"]==$row_page["ver_id"])
			{
				$ver_id_start = $ver_id_newversion;
			}

			// Bausteine kopieren

			$sql = "INSERT INTO sequence_data(dat_id,pag_id,ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id) SELECT dat_id,".$pag_id_new." AS pag_id,".$ver_id_newversion ." AS ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id FROM sequence_data WHERE pag_id = " . $pag_id_source . " AND ver_id = " . $row["ver_id"] . " AND dat_editbuffer = 0";
			//echo $sql;
			$myDB->query($sql);

		}

		// Die eigentliche Seite anpassen
		$sql = "SELECT * FROM pageversion WHERE ver_id = " . $ver_id_start;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);

		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_bez","Neue Seite - Kopie von" . $row_page["pag_bez"]);
		$mySQL->addField("ver_id",$ver_id_start,DB_NUMBER);
		$mySQL->addField("ver_nr",$row["ver_nr"],DB_NUMBER);
		$mySQL->addField("pag_nextbuild1",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild2",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild3",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild4",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild5",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild6",time(),DB_NUMBER);
		$mySQL->addField("pag_fullsearch",$row["pag_fullsearch"]);
		$mySQL->addField("pag_props_locale",$row_page["pag_props_locale"]);
		$sql = $mySQL->update("page","pag_id=".$pag_id_new);
		$rs = $myDB->query($sql);

		$myPage = new PhenotypePage();
		$myPage->init($pag_id_new,1);
		$myPage->buildProps();

		return $pag_id_new;
	}

	function preview($editbuffer=1)
	{
		$myTC = new TCheck();
		$myTC->start();
		//$html = $this->render(HTML_FULL,$version,PAGE_BUFFER);
		$html = $this->renderPreview($this->ver_id,$editbuffer);
		$_search = Array("<?xml","<? xml","<?  xml");
		$html = str_replace($_search,'<?="<?xml"?>',$html);
		$filename_bak = TEMPPATH ."previewcache/~" . uniqid("") . ".tmp";
		$fp = fopen ($filename_bak,"w");
		fputs($fp,$html);
		fclose ($fp);
		@chmod ($filename_bak,UMASK);

		global $myDB;
		global $myPT;
		$myPage = &$this; // Zugriff fuer Includes und dergleichen

		$myPT->startBuffer();
		require ($filename_bak);
		$html = $myPT->stopBuffer();
		$myTC->stop();
		unlink ($filename_bak);

		$html=$this->doDisplayPostProcessing($html,$myTC,"R");
		echo $html;
	}

	function display($cache=1,$sendHeader=1)
	{
		global $myApp;
		global $myRequest;
		global $myTC;
		global $myPage;
		$myPage = &$this; // Zugriff fuer Includes und dergleichen
		if ($this->status==0)
		{
			$myApp->throw404($myRequest->getI("id"));
			exit();
		}

		global $myDB;
		global $_PT_HTTP_CONTENTTYPES;
		global $myPT;





		if (!file_exists(CACHEPATH . CACHENR ))
		{
			mkdir (CACHEPATH . CACHENR ,UMASK);
		}
		if (!file_exists(CACHEPATH . CACHENR . "/page"))
		{
			mkdir (CACHEPATH . CACHENR . "/page",UMASK);
		}

		$dateiname = CACHEPATH . CACHENR . "/page/page_" . sprintf("%04.0f",$this->id) .",".$this->lng_id. ".inc.php";

		// Content-Type-Header senden
		if ($sendHeader==1)
		{
			if ($this->row["pag_contenttype"]!=200)
			{
				Header ("Content-Type: ".$_PT_HTTP_CONTENTTYPES[$this->row["pag_contenttype"]]);
			}
		}
		$t = time();
		if ($t>$this->nextbuild OR $cache==0)
		{
			$info = "Rebuild";
			if ($this->row["pag_nextversionchange"]!=0 AND $this->row["pag_nextversionchange"] < time())
			{
				$this->versionCheck();
			}
			//$this->versionCheck();
			$html = $this->renderPage4Cache($this->ver_id);
			//$html = $this->render(HTML_INCLUDES_NOCACHE,$this->ver_id,PAGE_FREE);
			$_search = Array("<?xml","<? xml","<?  xml");
			$html = str_replace($_search,'<?="<?xml"?>',$html);
			$fp = fopen ($dateiname,"w");
			fputs ($fp,$html);
			fclose ($fp);
			@chmod ($dateiname,UMASK);
			$nextbuild = $t + $this->row["pag_cache"] -1;// -1 um Nocache bei Parallelzugriff zu garantieren
			// Gibt es vorher einen Versionswechsel?
			if ($nextbuild > $this->row["pag_nextversionchange"] AND
			$this->row["pag_nextversionchange"]!=0)
			{
				$nextbuild = $this->row["pag_nextversionchange"];
			}

			// kein Cache haelt ueber Nacht
			$date1 = date("d.m.Y",$t);
			$date2 = date("d.m.Y",$nextbuild);

			if ($date1 != $date2)
			{
				$nextbuild = mktime ( 0, 0, 0, date("m",$t) ,date("d",$t)+1, date("Y",$t));
			}


			if ($this->lng_id==1)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_nextbuild".CACHENR,$nextbuild,DB_NUMBER);
				// Der Printcache wird immer mitgeleert
				$mySQL->addfield("pag_printcache".CACHENR,0,DB_NUMBER);
				$mySQL->addfield("pag_xmlcache".CACHENR,0,DB_NUMBER);
				$sql = $mySQL->update("page","pag_id =".$this->id);
				$myDB->query($sql);
			}
			else
			{
				// Cache der Sprachvariante leeren
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_nextbuild".CACHENR,$nextbuild,DB_NUMBER);
				// Der Printcache wird immer mitgeleert
				$mySQL->addfield("pag_printcache".CACHENR,0,DB_NUMBER);
				$mySQL->addfield("pag_xmlcache".CACHENR,0,DB_NUMBER);
				$sql = $mySQL->update("page_language","pag_id =".$this->id." AND lng_id=".$this->lng_id);
				$myDB->query($sql);
			}

		}
		else
		{
			$info = "Cache";
		}


		$myPT->startBuffer();
		require ($dateiname);
		$html = $myPT->stopBuffer();
		$myTC->stop();


		$mySQL = new SQLBuilder();
		$mySQL->addfield("pag_lastfetch",time(),DB_NUMBER);
		if ($info=="Rebuild")
		{
			$mySQL->addfield("pag_lastbuild_time",sprintf("%0.4f",$myTC->getSeconds()));
			$mySQL->addfield("pag_lastcachenr",CACHENR,DB_NUMBER);
		}
		else
		{
			$mySQL->addfield("pag_lastcache_time",sprintf("%0.4f",$myTC->getSeconds()));
		}
		$sql = $mySQL->update("page","pag_id =".$this->id);
		$myDB->query($sql,"Page ".$this->id.": storing page generation data");


		// Jetzt die Statistik
		if ($this->statistic==true)
		{
			$datum = date("Ymd");
			$sql = "UPDATE page_statistics SET sta_pageview = sta_pageview+1 WHERE pag_id = " . $this->id . " AND sta_datum = " . $datum;
			$rs = $myDB->query($sql,"Page ".$this->id.": updating view statistics");
			$c = mysql_affected_rows();
			if ($c==0)
			{
				$mySQL=new SQLBuilder();
				$mySQL->addfield("pag_id",$this->id,DB_NUMBER);
				$mySQL->addfield("sta_datum",$datum,DB_NUMBER);
				$mySQL->addfield("sta_pageview",1,DB_NUMBER);
				$sql = $mySQL->insert("page_statistics");
				$myDB->query($sql);
				// Jetzt den unwahrscheinlichen Fall, dass eine Seite 2x angelegt wurde durch
				// Loeschen verhindern, auch wenn dann Views verloren gehen
				$sql = "SELECT COUNT(*) AS C FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
				$rs = $myDB->query($sql);
				$row = mysql_fetch_array($rs);
				if ($row["C"]>1)
				{
					$sql = "DELETE FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
					$myDB->query($sql);
				}
			}
		}

		echo $this->doDisplayPostProcessing($html,$myTC,$info);

	}

	function doDisplayPostProcessing($html,$myTC,$info)
	{
		global $myPT;
		global $myRequest;
		global $myDB;
		global $myTC;
		$myPage = &$this; // Zugriff fuer Includes und dergleichen


		$html = str_replace("#!#title#!#",$myPT->codeH($myPage->titel),$html);
		$html = str_replace("#!#canonical_url#!#",$myPT->codeH($this->getUrl($this->lng_id)),$html);
		$html = str_replace("#!#alttitle#!#",$myPT->codeH($myPage->alttitel),$html);
		$html = str_replace("#!#keywords#!#",$myPT->codeH($myPage->row["pag_searchtext"]),$html);

		$cookie = md5("on".PT_SECRETKEY);

		if (PT_DEBUG==1 AND $_COOKIE["pt_debug"]==$cookie)
		{
			$url_reload = $myRequest->getReloadUrl();
			$myPT->startBuffer();
	    ?>
	    <div id="pt_debug" style="margin:0px;opacity:0.65;filter:alpha(opacity=75);padding:0px;padding-left:3px;position:absolute;right:0px;top:0px;z-index:10000;background-color:#000;color:#fff;font-family:Arial;font-size:12px;vertical-align:top;height:22px;}">
	    <ul style="list-style-type:none;display:block;margin:0px;padding:0px">
	    <li style="display:inline;margin:0px;padding:0px"><a href="#" onclick="document.getElementById('pt_debug').style.display='none'; document.getElementById('pt_debug_cover').style.display='none';document.getElementById('pt_debug_details').style.display='none'; return false;"><img src="<?php echo ADMINFULLURL?>img/b_close_stat.gif" alt="close" title="close"  border="0"/></a></li>
	    <li style="display:inline;margin:0px;padding:0px"><a href="<?php echo ADMINFULLURL?>page_edit.php?id=<?php echo $this->id?>" target="_blank"><img src="<?php echo ADMINFULLURL?>img/b_edit.gif" alt="edit page" title="edit page" border="0"/></a></li>
	    <li style="display:inline;margin:0px;padding:0px"><a href="#" onclick="document.getElementById('pt_debug_cover').style.display='';document.getElementById('pt_debug_details').style.display=''; return false;"><img src="<?php echo ADMINFULLURL?>img/b_debug.gif" alt="display debug info" title="display debug info" border="0"/></a></li>
	    <li style="display:inline;margin:0px;padding:0px"><a href="<?php echo $myPT->codeH($url_reload)?>"><img src="<?php echo ADMINFULLURL?>img/b_aktivieren.gif" alt="clear cache and reload page" title="clear cache and reload page" border="0"/></a></li>
	    <li style="display:inline;margin:0px;padding:0px;line-height:22px;vertical-align:top">ID: <?php echo $this->id?>.<?php echo sprintf("%02d",$this->ver_id)?>#<?php echo $this->lng_id?> | E: <?php echo (int)($myTC->getSeconds()*1000);?> ms <?php echo count($myPT->_debughints)?>h [<?php echo $info[0]?>] | DB: <?php echo count($myDB->_files)?>q | H: <?php echo ceil(strlen($html)/1024)?>kb [L<?php echo (int)$myPage->lay_id?>]<?php if (function_exists('memory_get_usage')){?> | M: <?php echo sprintf("%0.2f",memory_get_usage()/1024/1024);?> MB<?php }?></li>
	    <li style="display:inline;margin:0px;padding:0px"><a href="http://www.phenotype-cms.com" target="_blank"><img src="<?php echo ADMINFULLURL?>img/b_doku.gif" alt="Phenotype Logo" title="Phenotype Logo" border="0"/></a></li>
	    </ul>
	    </div>
	    
	    <?php 
	    $myPT->startBuffer();
	    $myPT->displayDebugInfo();
	    $uri =  uniqid();
	    $myDao = new PhenotypeSystemDataObject("DebugInfo",array("uri"=>$uri));
	    $debuginfo = $myPT->stopBuffer();
	    $debuginfo = substr($debuginfo,0,800000);
	    $myDao->set("html",$debuginfo);
	    //$myDao->storeData("system.debuginfo_".$uri,60);
	    $myDao->store(60);
	    ?>
	    <div id="pt_debug_cover" style="display:none;background-color:#555555;left:0px;position:absolute;top:0px;right:0px;height:200%;z-index:9999;opacity:0.9">
	    </div>
	    <div id="pt_debug_details" style="display:none;position:absolute;top:50px;left:width:900px;height:85%;margin:0px;background-color: #fff;border:0px;padding:0px;z-index:10000;">
	    <div style="float:left"><a href="#" onclick="document.getElementById('pt_debug_cover').style.display='none';document.getElementById('pt_debug_details').style.display='none'; return false;"><img src="<?php echo ADMINFULLURL?>img/b_close_stat.gif" alt="close" title="close" style="margin:0px;padding:0px" border="0"/></a></div>
	    <iframe src="<?php echo SERVERFULLURL ?>debuginfo.php?uri=<?php echo $uri?>" style="width:900px;height:100%;border:0px;overflow: auto;"></iframe>
	    </div>
	    <?php

	    $html = str_replace("#!#pt_debug#!#",$myPT->stopBuffer(),$html);
		}
		else
		{
			$html = str_replace("#!#pt_debug#!#","",$html);
		}
		return $html;

	}

	function printview($cache=1)
	{
		global $myRequest;
		global $myApp;
		global $myPT;

		if ($this->status==0)
		{
			$myApp->throw404($myRequest->getI("id"));
			exit();
		}

		global $myDB;

		// Check for cache of normal view and render normal view first to enable caching of print view
		if ($this->row["pag_cache"]!=0 AND $_SERVER['REQUEST_METHOD']!="POST")
		{
			if (time()>$this->nextbuild)
			{
				$myPT->startBuffer();
				$this->display(1,0);
				$myPT->stopBuffer();
			}
		}

		$myTC = new TCheck();
		$myTC->start();
		//$dateiname = APPPATH . "cache/page/print_" . sprintf("%04.0f",$this->id) . ".inc.php";
		$dateiname = CACHEPATH . CACHENR . "/page/print_" . sprintf("%04.0f",$this->id).",".$this->lng_id . ".inc.php";

		$t = time();
		if ($this->printcache==0 OR $t>$this->nextbuild OR $cache==0)
		{
			$info = "Rebuild";

			// Achtung!, da der Printcache vom Seitencache abhaengig ist, kann eine
			// Printseite nicht gecached werden, wenn der Seitencache abgelaufen ist,
			// auch wenn ein evtl. Versionswechsel korrekt vollzogen wurde.

			if ($this->row["pag_nextversionchange"]!=0 AND $this->row["pag_nextversionchange"] < time())
			{
				$this->versionCheck();
			}
			$html = $this->renderPrint4Cache($this->ver_id);
			$_search = Array("<?xml","<? xml","<?  xml");
			$html = str_replace($_search,'<?="<?xml"?>',$html);

			$fp = fopen ($dateiname,"w");
			fputs ($fp,$html);
			fclose ($fp);
			@chmod ($dateiname,UMASK);

			if ($this->lng_id==1)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_printcache".CACHENR,1,DB_NUMBER);
				$sql = $mySQL->update("page","pag_id =".$this->id);
				$myDB->query($sql);
			}
			else
			{
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_printcache".CACHENR,1,DB_NUMBER);
				$sql = $mySQL->update("page_language","pag_id =".$this->id. " AND  lng_id=".$this->lng_id);
				$myDB->query($sql);
			}

		}
		else
		{
			$info = "Cache";
		}
		$myPT->startBuffer();
		require ($dateiname);
		$html = $myPT->stopBuffer();

		$myTC->stop();
		// Jetzt die Statistik
		if ($this->statistic==true)
		{
			$datum = date("Ymd");
			$sql = "UPDATE page_statistics SET sta_pageview = sta_pageview+1 WHERE pag_id = " . $this->id . " AND sta_datum = " . $datum;
			$rs = $myDB->query($sql);
			$c = mysql_affected_rows();
			if ($c==0)
			{
				$mySQL=new SQLBuilder();
				$mySQL->addfield("pag_id",$this->id,DB_NUMBER);
				$mySQL->addfield("sta_datum",$datum,DB_NUMBER);
				$mySQL->addfield("sta_pageview",1,DB_NUMBER);
				$sql = $mySQL->insert("page_statistics");
				$myDB->query($sql);
				// Jetzt den unwahrscheinlichen Fall, dass eine Seite 2x angelegt wurde durch
				// Loeschen verhindern, auch wenn dann Views verloren gehen
				$sql = "SELECT COUNT(*) AS C FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
				$rs = $myDB->query($sql);
				$row = mysql_fetch_array($rs);
				if ($row["C"]>1)
				{
					$sql = "DELETE FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
					$myDB->query($sql);
				}
			}
		}
		$html=$this->doDisplayPostProcessing($html,$myTC,$info);
		echo $html;
	}




	function displayXML($cache=1)
	{
		global $myDB;
		global $myPT;

		$myTC = new TCheck();
		$myTC->start();
		$dateiname = CACHEPATH . CACHENR . "/page/xml_" . sprintf("%04.0f",$this->id).",".$this->lng_id . ".inc.php";

		$t = time();
		if ($this->xmlcache==0 OR $t>$this->nextbuild OR $cache==0)
		{
			$info = "Rebuild";

			// Achtung!, da der XMLCache vom Seitencache abhaengig ist, kann eine
			// XMLseite nicht gecached werden, wenn der Seitencache abgelaufen ist,
			// auch wenn ein evtl. Versionswechsel korrekt vollzogen wurde.

			if ($this->row["pag_nextversionchange"]!=0 AND $this->row["pag_nextversionchange"] < time())
			{
				$this->versionCheck();
			}
			$html = $this->renderXML4Cache($this->ver_id);
			$_search = Array("<?xml","<? xml","<?  xml");
			$html = str_replace($_search,'<?="<?xml"?>',$html);

			$fp = fopen ($dateiname,"w");
			fputs ($fp,$html);
			fclose ($fp);
			@chmod ($dateiname,UMASK);

			if ($this->lng_id==1)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_xmlcache".CACHENR,1,DB_NUMBER);
				$sql = $mySQL->update("page","pag_id =".$this->id);
				$myDB->query($sql);
			}
			else
			{
				$mySQL = new SQLBuilder();
				$mySQL->addfield("pag_xmlcache".CACHENR,1,DB_NUMBER);
				$sql = $mySQL->update("page_language","pag_id =".$this->id. " AND  lng_id=".$this->lng_id);
				$myDB->query($sql);
			}
		}
		else
		{
			$info = "Cache";
		}

		header("Content-Type: application/xml; charset=iso-8859-1");
		echo '<?xml version="1.0" encoding="ISO-8859-1"?>';

  ?>
	<phenotype>
	<request>
	<session><?php echo session_id()?></session>
	<cookies>
	<?php
	foreach ($_COOKIE as $k => $v)
	{
		$k = $myPT->xmlencode($k);
		$v = $myPT->xmlencode($v);
	?>
	<cookie name="<?php echo $k?>" value="<?php echo $v?>"/>
	<?php
	}
	?>
	</cookies>
	<sessionvars>
	<?php
	foreach ($_SESSION as $k => $v)
	{
		$k = $myPT->xmlencode($k);
		$v = $myPT->xmlencode($v);
	?>
	<var name="<?php echo $k?>" value="<?php echo $v?>"/>
	<?php
	}
	?>
	</sessionvars>
	</request>
	<?php
	require ($dateiname);
	?>
	</phenotype>
	<?php
	$myTC->stop();
	// Jetzt die Statistik
	if ($this->statistic==true)
	{
		$datum = date("Ymd");
		$sql = "UPDATE page_statistics SET sta_pageview = sta_pageview+1 WHERE pag_id = " . $this->id . " AND sta_datum = " . $datum;
		$rs = $myDB->query($sql);
		$c = mysql_affected_rows();
		if ($c==0)
		{
			$mySQL=new SQLBuilder();
			$mySQL->addfield("pag_id",$this->id,DB_NUMBER);
			$mySQL->addfield("sta_datum",$datum,DB_NUMBER);
			$mySQL->addfield("sta_pageview",1,DB_NUMBER);
			$sql = $mySQL->insert("page_statistics");
			$myDB->query($sql);
			// Jetzt den unwahrscheinlichen Fall, dass eine Seite 2x angelegt wurde durch
			// Loeschen verhindern, auch wenn dann Views verloren gehen
			$sql = "SELECT COUNT(*) AS C FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			if ($row["C"]>1)
			{
				$sql = "DELETE FROM page_statistics WHERE pag_id=" . $this->id . " AND sta_datum=" . $datum;
				$myDB->query($sql);
			}
		}
	}
	}// -- function displayXML()


	/*
	function renderPrintFull($ver_id)
	{
	// Achtung! Diese Funktion wird momentan nicht genutzt.
	$this->pagemode=0;
	$this->printmode=1;
	return $this->render($ver_id,0);
	}

	function renderPageFull($ver_id)
	{
	// Achtung! Diese Funktion wird momentan nicht genutzt.
	$this->pagemode=1;
	$this->printmode=0;
	return $this->render($ver_id,0);
	}*/


	function renderPreview($ver_id,$editbuffer=1)
	{
		$this->pagemode=1;
		$this->printmode=0;
		return $this->render($ver_id,0,$editbuffer);
	}

	function renderPage4Cache($ver_id)
	{
		$this->pagemode=1;
		$this->printmode=0;
		$this->xmlmode=0;
		$html = $this->render($ver_id,1);
		return $html;
	}

	function renderPrint4Cache($ver_id)
	{
		$this->pagemode=0;
		$this->printmode=1;
		$this->xmlmode=0;
		return $this->render($ver_id,1);
	}


	function renderXML4Cache($ver_id)
	{
		$buildcache=1;
		$this->buildingcache = $buildcache;

		global $myDB;
		global $myPT;

		$this->pagemode=1;
		$this->printmode=0;
		$this->xmlmode=1;
		// Da die XML-Sicht ganz anders aufgebaut ist, als Standard- und Printdarstellung
		// wird nicht die generische Rendermethode aufgerufen
		$myPT->startBuffer();

	?>
	<page>
	<titel><?php echo $myPT->xmlencode($this->titel) ?></titel>
	<meta>
	<pag_id><?php echo $this->id ?></pag_id>
	<pag_uid><?php echo $this->uid ?></pag_uid>
	<pagename><?php echo $myPT->xmlencode($this->bez) ?></pagename>
	<status><?php echo $this->status ?></status>
	<pag_pos><?php echo $this->pos ?></pag_pos>
	<pag_id_mik><?php echo $this->pag_id_mimikry ?></pag_id_mik>
	<pag_id_top><?php echo $this->pag_id_top ?></pag_id_top>
	<titel><?php echo $myPT->xmlencode($this->titel) ?></titel>
	<alttitel><?php echo $myPT->xmlencode($this->alttitel) ?></alttitel>
	<version nr="<?php echo $this->ver_nr ?>" ver_id="<?php echo $this->ver_id ?>"><?php echo $myPT->xmlencode($this->ver_bez) ?></version>
	<pagegroup grp_id="<?php echo $this->grp_id ?>"><?php
	$sql = "SELECT * FROM pagegroup WHERE grp_id=" . $this->grp_id;
	$rs = $myDB->query($sql);
	$row = mysql_fetch_array($rs);
	echo $myPT->xmlencode($row["grp_bez"]);
	?></pagegroup>
	<lastchange><?php echo date('d.m.Y H:i',$this->row["pag_date"]) ?></lastchange>
	<lastuser usr_id="<?php echo $this->row["usr_id"] ?>"><?php
	$myUser = new PhenotypeUser($this->row["usr_id"]);
	echo $myPT->xmlencode($myUser->getName());
	?></lastuser>
	<lastfetch><?php echo date('d.m.Y H:i',$this->row["pag_lastfetch"]) ?></lastfetch>
	<nextrebuild><?php echo date('d.m.Y H:i',$this->row["pag_nextbuild".CACHENR]) ?></nextrebuild>
	<layout lay_id="2">
	<includes>
	<?php
	$sql = "SELECT include.inc_id, layout_include.lay_id, layout_include.lay_includenr,
	layout_include.lay_includecache, include.inc_rubrik, include.inc_bez, include.inc_description FROM
	layout_include,	include WHERE layout_include.inc_id = include.inc_id AND layout_include.lay_id = ". $this->lay_id . " ORDER BY lay_includenr";

	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{
	?>
	<include nr="<?php echo $row["lay_includenr"] ?>">
	<id><?php echo $row["inc_id"] ?></id>
	<title><?php echo $myPT->xmlencode($row["inc_bez"]) ?></title>
	<description><?php echo $myPT->xmlencode($row["inc_description"]) ?></description>
	</include>
	<?php
	}
	?>
	</includes>
	<blocks>
	<?php
	$sql = "SELECT * FROM layout_block WHERE lay_id = " . $this->lay_id . " ORDER BY lay_blocknr";
	$rs_block = $myDB->query($sql);
	while ($row_block = mysql_fetch_array($rs_block))
	{
	?>
	<block nr="<?php echo $row_block["lay_blocknr"] ?>" componentgroup="<?php echo $row_block["cog_id"] ?>" context="<?php echo $row_block["lay_context"] ?>"/>
	<?php
	}
	?>
	</blocks>
	</layout>
	<pagevars>
	<?php
	foreach ($this->_inheritageprops AS $k=>$v)
	{
	?>
	<var name="<?php echo $myPT->xmlencode($k) ?>" value="<?php echo $myPT->xmlencode($v) ?>"/>
	<?php
	}
	?>
	</pagevars>
	</meta>
	<components>

	<?php
	// Bloecke
	$sql = "SELECT * FROM layout_block WHERE lay_id = " . $this->lay_id . " ORDER BY lay_blocknr";
	$rs_block = $myDB->query($sql);
	while ($row_block = mysql_fetch_array($rs_block))
	{
	   ?>
	   <block nr="<?php echo $row_block["lay_blocknr"] ?>">
	   <?php    
	   $this->blocknr = $row_block["lay_blocknr"];
	   $html_block = "";
	   $sql = "SELECT * FROM sequence_data WHERE pag_id = " . $this->id . " AND ver_id = ". $ver_id . " AND dat_blocknr=" . $row_block["lay_blocknr"];
	   if ($editbuffer==1){$sql .= " AND dat_editbuffer=1";}else{$sql .= " AND dat_editbuffer=0";}
	   $sql .= " AND lng_id=".$this->lng_id." AND dat_visible = 1 ORDER BY dat_pos";
	   $rs = $myDB->query($sql);
	   while ($row = mysql_fetch_array($rs))
	   {
	   	$tname = "PhenotypeComponent_" . $row["com_id"];
	   	$myComponent = new $tname;
	   	$myComponent->init($row);
	   	$xml_block= $myComponent->renderXML($row_block["lay_context"]); // Defaultstyle

	   	echo $xml_block;
	   }
	   ?>
	   </block>
	   <?php
	}
	?>
	</components>
	<includes>
	<?php
	$sql = "SELECT * FROM layout_include WHERE lay_id = ". $this->lay_id . " ORDER BY lay_includenr";

	$rs = $myDB->query($sql);
	while ($row=mysql_fetch_array($rs))
	{

		if ($row["lay_includecache"]==1)
		{
			$cname = "PhenotypeInclude_" . $row["inc_id"];
			//$myInc = new PhenotypeInclude($row["inc_id"]);
			$myInc = new $cname();
			echo $myInc->renderXML();
		}
		else
		{
			$code = '<?php $myInc = new PhenotypeInclude_' . $row["inc_id"] . '();echo $myInc->renderXML(); ?>';
			echo $code;
		}
	}
	?>
	</includes>
	</page>
	<?php
	$xml = $myPT->stopBuffer();
	return ($xml);
	//return utf8_encode($xml);

	}


	function render($ver_id,$buildcache=0,$editbuffer=0)
	{
		$this->buildingcache = $buildcache;

		global $myDB;
		global $myPT;
		// lokale Smartyinstanz, da die Bausteine ja auch welche nutzen koennen
		$mySmarty = new PhenotypeSmarty();

		// Titel-Tags und Keywords setzen
		/*
		$mySmarty->assign("titel",$this->titel);
		$mySmarty->assign("title",$this->titel);
		$mySmarty->assign("alttitel",$this->alttitel);
		$mySmarty->assign("alttitle",$this->alttitel);
		$mySmarty->assign("keywords",$this->row["pag_searchtext"]);
		*/

		$mySmarty->assign("titel","#!#title#!#");
		$mySmarty->assign("title","#!#title#!#");
		$mySmarty->assign("alttitel","#!#alttitle#!#");
		$mySmarty->assign("alttitle","#!#alttitle#!#");
		$mySmarty->assign("keywords","#!#keywords#!#");
		$mySmarty->assign("canonical_url","#!#canonical_url#!#");

		if (PT_DEBUG==1)
		{
			$mySmarty->assign("pt_debug","#!#pt_debug#!#");
		}

		// Bloecke
		$sql = "SELECT * FROM layout_block WHERE lay_id = " . $this->lay_id . " ORDER BY lay_blocknr";
		$rs_block = $myDB->query($sql,"Page ".$this->id.": gathering layout information");
		while ($row_block = mysql_fetch_array($rs_block))
		{

			$this->blocknr = $row_block["lay_blocknr"];
			$html_block = "";
			$sql = "SELECT * FROM sequence_data WHERE pag_id = " . $this->id . " AND ver_id = ". $ver_id . " AND dat_blocknr=" . $row_block["lay_blocknr"];

			if ($editbuffer==1)
			{
				$sql .= " AND dat_editbuffer=1 AND usr_id=".$_SESSION["usr_id"];
			}
			else
			{
				$sql .= " AND dat_editbuffer=0";
			}

			$sql .= " AND dat_visible = 1 AND lng_id=". $this->lng_id." ORDER BY dat_pos";
			$rs = $myDB->query($sql,'Page '.$this->id.': rendering components of $pt_block' . $row_block["lay_blocknr"]);
			while ($row = mysql_fetch_array($rs))
			{
				$myDB->setNextContext("Component ".$row["com_id"].":");
				$tname = "PhenotypeComponent_" . $row["com_id"];
				$myComponent = new $tname;
				$myComponent->init($row);
				$this->dat_id_sequence_data = $myComponent->id;
				$html_block .= $myComponent->render($row_block["lay_context"]); // Defaultstyle
			}
			$bname = "pt_block" . intval($row_block["lay_blocknr"]);
			$mySmarty->assign($bname,$html_block);
			$this->blockHTML[$row_block["lay_blocknr"]] = $html_block;
		}
		// Includes

		$myPage = $this;
		$myPage->mySmarty = &$mySmarty;

		$sql = "SELECT * FROM layout_include WHERE lay_id = " . $this->lay_id . " AND inc_id <> 0 ORDER BY lay_includenr";
		$rs_inc = $myDB->query($sql,'Page '.$this->id.': rendering includes');
		while ($row_inc = mysql_fetch_array($rs_inc))
		{
			$myDB->setNextContext("Include ".$row_inc["inc_id"].":");
			if ($buildcache==0)
			{
				$cname = "PhenotypeInclude_" . $row_inc["inc_id"];
				//$myInc = new PhenotypeInclude($row["inc_id"]);
				$myInc = new $cname();
				$html_include = $myInc->execute();
			}
			else
			{
				if ($row_inc["lay_includecache"]==1)
				{
					$cname = "PhenotypeInclude_" . $row_inc["inc_id"];
					$myInc = new $cname();
					$html_include = $myInc->execute();
				}
				else
				{
					$html_include = '<?php $myPage->includenocache=1 ?>';// Notwendig fuer Content-Statistik
					$html_include .= '<?php $myInc = new PhenotypeInclude_' . $row_inc["inc_id"] .'();echo $myInc->execute() ?>';

					$html_include .= '<?php $myPage->includenocache=0 ?>';

				}
			}

			$mySmarty->assign("pt_include" . $row_inc["lay_includenr"],$html_include);
			$this->includeHTML[$row_inc["lay_includenr"]] = $html_include;
		}


		// Hier evtl. eingebetten Code ausfuehren
		// (kann nicht aus dem Cache rausgeloest werden, weil diese i.d.R. dazu
		//  da sind Manipulationen am layout vorzunehmen(
		$html = "";

		if ($this->inc_id1!=0)
		{
			$myDB->setNextContext("(Pre-)Include ".$this->inc_id1.":");
			$cname = "PhenotypeInclude_" . $this->inc_id1;
			//$myInc = new PhenotypeInclude($this->inc_id1);
			$myInc = new $cname();
			$html .= $myInc->execute();
		}
		/*
		if ($this->inc_id2!=0)
		{
		$myInc = new PhenotypeInclude($this->inc_id2);
		$html .= $myInc->execute();
		}
		*/

		if ($this->exec_script ==1)
		{
			$myDB->setNextContext("Pagescript:");
			$scriptname = APPPATH . "pagescripts/" .  sprintf("%04.0f", $this->id) . "_" . sprintf("%04.0f", $this->ver_id) . ".inc.php";
			$myPT->startbuffer();
			global $myPT, $myDB, $myPage, $myRequest, $myLog;
			require($scriptname);
			$html .= $myPT->stopbuffer();
		}

		$mySmarty->compile_dir = SMARTYCOMPILEPATH;

		if ($this->printmode==1)
		{
			$tplType = "print";
		}
		else
		{
			$tplType = "normal";
		}
		$template = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $this->lay_id, $tplType);

		if ($this->lay_id!=0) // Keine Anzeige, wenn kein Template
		{
			$mySmarty->assign("page",$this);
			$html .= $mySmarty->fetch($template);
		}

		// Postprocessing
		if ($this->inc_id2!=0)
		{
			$myDB->setNextContext("(Post-)Include ".$this->inc_id1.":");
			//$myInc = new PhenotypeInclude($this->inc_id2,$html);
			$cname = "PhenotypeInclude_" . $this->inc_id2;
			//$myInc = new PhenotypeInclude($this->inc_id2,$html);
			$myInc = new $cname($html);

			$html = $myInc->execute();
		}

		return $html;
	}


	function getBlockHTML($nr)
	{
		return @$this->blockHTML[$nr];
	}

	function getIncludeHTML($nr)
	{
		return @$this->includeHTML[$nr];
	}

	function activateVersion ($ver_id)
	{
		global $myDB;

		// Aktuelle Version in der page-Tabelle setzen
		$sql = "SELECT * FROM pageversion WHERE ver_id = " . $ver_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$mySQL = new SQLBuilder();
		$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
		// Hier war lange ver_id statt ver_nr Bug??
		$mySQL->addField("ver_nr",$row["ver_nr"],DB_NUMBER);
		$mySQL->addField("pag_nextbuild1",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild2",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild3",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild4",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild5",time(),DB_NUMBER);
		$mySQL->addField("pag_nextbuild6",time(),DB_NUMBER);
		$mySQL->addField("pag_fullsearch",$row["pag_fullsearch"]);
		$sql = $mySQL->update("page","pag_id=".$this->id);
		$rs = $myDB->query($sql);

		// Caches der Sprachvarianten leeren
		$sql = "UPDATE page_language SET pag_nextbuild1=0,pag_nextbuild2=0,pag_nextbuild3=0,pag_nextbuild4=0,pag_nextbuild5=0,pag_nextbuild6=0,pag_printcache1=0,pag_printcache2=0,pag_printcache3=0,pag_printcache4=0,pag_printcache5=0,pag_printcache6=0,pag_xmlcache1=0,pag_xmlcache2=0,pag_xmlcache3=0,pag_xmlcache4=0,pag_xmlcache5=0,pag_xmlcache6=0 WHERE pag_id=".$this->id;
		$myDB->query($sql);

		$this->init($this->id);
	}

	function versionCheck()
	{
		global $myDB;
		// Diese Funktion prueft, ob es aufgrund von
		// Autoaktivierungen zu neuen Versionswechseln
		// kommt, die im Cache noch nicht beruecksichtig wurden und
		// setzt die enstprechenden Parameter

		$versionchange=0;
		$sql = "SELECT * FROM  pageversion_autoactivate WHERE pageversion_autoactivate.pag_id = " . $this->id . " AND ver_date <= ". time() . " ORDER BY ver_date DESC";
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			// Es gibt abgelaufene Aktivierungen
			$row = mysql_fetch_array($rs);
			//echo "Abgelaufene Aktivierung";
			if ($row["ver_id"]!= $this->ver_id)
			{
				$this->activateVersion($row["ver_id"]);
				$versionchange=1;
			}

			// Alle abgelaufenen Aktivierungen loeschen
			$sql = "DELETE FROM  pageversion_autoactivate WHERE pageversion_autoactivate.pag_id = " . $this->id . " AND ver_date <= ". time();
			$rs = $myDB->query($sql);
		}

		// Check nach anstehenden Versionswechseln
		$sql = "SELECT * FROM  pageversion_autoactivate WHERE pageversion_autoactivate.pag_id = " . $this->id . " AND ver_date >= ". time() . " ORDER BY ver_date";
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			$row = mysql_fetch_array($rs);

			$mySQL = new SQLBuilder();
			$mySQL->addField("pag_nextversionchange",$row["ver_date"],DB_NUMBER);
			for ($i=1;$i<=CACHENR;$i++)
			{
				$nextbuild = $this->row["pag_nextbuild".$i];
				// Wenn die Version gerade aktiviert wurde, ist in der Row nicht mehr der
				// aktuelle Stand
				if ($versionchange==1){$nextbuild=0;}
				if ($row["ver_date"]<$nextbuild){$nextbuild = $row["ver_date"];}
				$mySQL->addField("pag_nextbuild".$i,$nextbuild,DB_NUMBER);
			}
			$sql = $mySQL->update("page","pag_id=" . $this->id);
			//echo $sql;
			$myDB->query($sql);
		}
		else
		{
			// Es steht kein Versionswechsel an
			$mySQL = new SQLBuilder();
			$mySQL->addField("pag_nextversionchange",0,DB_NUMBER);
			$sql = $mySQL->update("page","pag_id=" . $this->id);
			$myDB->query($sql);
		}
	}

	function contentCount($con_id,$dat_id)
	{
		global $myDB;
		//  Statistik
		$datum = date("Ymd");
		$sql = "UPDATE content_statistics SET sta_contentview = sta_contentview+1 WHERE dat_id = " . $dat_id . " AND sta_datum = " . $datum;
		$rs = $myDB->query($sql);
		$c = mysql_affected_rows();
		if ($c==0)
		{
			$mySQL=new SQLBuilder();
			$mySQL->addfield("dat_id",$dat_id,DB_NUMBER);
			$mySQL->addfield("sta_datum",$datum,DB_NUMBER);
			$mySQL->addfield("sta_contentview",1,DB_NUMBER);
			$sql = $mySQL->insert("content_statistics");
			$myDB->query($sql);
			// Jetzt den unwahrscheinlichen Fall, dass eine content 2x angelegt wurde durch
			// Loeschen verhindern, auch wenn dann Views verloren gehen
			$sql = "SELECT COUNT(*) AS C FROM content_statistics WHERE dat_id=" . $dat_id . " AND sta_datum=" . $datum;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			if ($row["C"]>1)
			{
				$sql = "DELETE FROM content_statistics WHERE dat_id=" . $dat_id . " AND sta_datum=" . $datum;
				$myDB->query($sql);
			}
		}
	}

	function displayCO($con_id,$dat_id,$skin,$count=0)
	{
		// zeigt die gecachten Contentdateien an, ohne DB-Zugriff
		global $myPT;
		$myPT->displayCO($con_id,$dat_id,$skin,$count);
	}

	function renderCO($con_id,$dat_id,$skin,$count=0)
	{
		global $myPT;
		return $myPT->renderCO($con_id,$dat_id,$skin,$count);
	}

	function buildProps()
	{
		global $myDB;
		$_props = Array();
		if ($this->pag_id_top !=0)
		{
			$sql = "SELECT pag_props_all FROM page WHERE pag_id = " .$this->pag_id_top;
			$myDB->query($sql);
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$_props = unserialize($row["pag_props_all"]);
		}
		$_props_locale = $this->row["pag_props_locale"];
		if ($_props_locale !="")
		{
			$_props_locale = unserialize($_props_locale);
			foreach ($_props_locale as $key => $val)
			{
				$_props[$key]=$val;
			}
		}
		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_props_all",serialize($_props));
		$sql = $mySQL->update("page","pag_id=".$this->id);
		$myDB->query($sql);
		$this->spreadProps($this->id,$_props);
	}

	function spreadProps($pag_id,$_props)
	{
		global $myDB;
		$sql = "SELECT * FROM page WHERE pag_id_top = " . $pag_id;
		$myDB->query($sql);
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$_newprops = $_props;
			if ($row["pag_props_locale"]!="")
			{
				foreach (unserialize($row["pag_props_locale"]) as $key => $val)
				{
					$_newprops[$key]=$val;
				}
			}
			$mySQL = new SQLBuilder();
			$mySQL->addField("pag_props_all",serialize($_newprops));
			$sql = $mySQL->update("page","pag_id=".$row["pag_id"]);
			$myDB->query($sql);
			$this->spreadProps($row["pag_id"],$_newprops);
		}
	}

	function spreadGroup($pag_id,$grp_id)
	{
		global $myDB;
		$sql = "SELECT * FROM page WHERE pag_id_top = " . $pag_id;
		$myDB->query($sql);
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("grp_id",$grp_id,DB_NUMBER);
			$sql = $mySQL->update("page","pag_id=".$row["pag_id"]);
			$myDB->query($sql);
			$this->spreadGroup($row["pag_id"],$grp_id);
		}
	}



	function move($pag_id_newtop,$insertorder)
	{
		global $myDB;
		global $myPT;

		//echo "NEU:" . $pag_id_newtop . "<br>";
		if ($pag_id_newtop==$this->id){return false;}

		// Zunächst sicherstellen, dass eine Seite nicht aus Versehen unterhalb von sich
		// selbst einghängt wird
		$_pages = $this->getAllChildrenArray($this->id);
		//print_r($_pages);
		if (in_array($pag_id_newtop,$_pages) )
		{
			return false;
		}

		$pos_current = $this->pos;
		// Seite ins Nirvana hängen ...
		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_id_top",-1);
		$mySQL->addField("grp_id",-1);
		$sql = $mySQL->update("page","pag_id=" .$this->id);
		//echo $sql;
		$myDB->query($sql);

		$sql = "UPDATE page SET pag_pos = pag_pos - 1 WHERE pag_id_top = " . $this->pag_id_top . " AND pag_pos >= " . $pos_current;
		//echo $sql;
		$myDB->query($sql);
		//$myDB->query($sql);

		// Seitengruppe und Position des neuen Ortes bestimmen
		$sql = "SELECT * FROM page WHERE pag_id = " . $pag_id_newtop;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$pos_anchor = $row["pag_pos"];
		$pag_id_top_anchor = $row["pag_id_top"];
		$grp_id_anchor = $row["grp_id"];
		switch ($insertorder)
		{
			case 1: // nach
			$sql = "UPDATE page SET pag_pos = pag_pos + 1 WHERE pag_id_top = " . $pag_id_top_anchor . " AND pag_pos > " . $pos_anchor;
			$newpos = $pos_anchor +1;
			$newtop = $pag_id_top_anchor;
			//echo $sql;
			$myDB->query($sql);
			break;
			case 2: // vor
			$sql = "UPDATE page SET pag_pos = pag_pos + 1 WHERE pag_id_top = " . $pag_id_top_anchor . " AND pag_pos >= " . $pos_anchor;
			//echo $sql;
			$myDB->query($sql);
			$newpos = $pos_anchor;
			$newtop = $pag_id_top_anchor;
			break;
			case 3: // unterhalb
			$sql = "SELECT MAX(pag_pos) AS M FROM page WHERE pag_id_top = " . $pag_id_top_anchor;
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs)==0)
			{
				$newpos=1;
			}
			else
			{
				$row = mysql_fetch_array($rs);
				$newpos = $row["M"]+1;
			}
			$newtop = $pag_id_newtop;
			break;
		}

		// Seite an neue Position hängen
		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_id_top",$newtop);
		$mySQL->addField("grp_id",$grp_id_anchor);
		$mySQL->addField("pag_pos",$newpos);
		$sql = $mySQL->update("page","pag_id=" .$this->id);
		$myDB->query($sql);

		// Bei alle abhängigen Seiten die Seitengruppe nachziehen
		$this->spreadGroup($this->id,$grp_id_anchor);

		// Seitenvariablen nachziehen
		//$this->init($this->id,$this->lng_id);
		$this->buildProps();

		// clear cache and rebuild all urls
		$myPT->clearCache();
		$sql = "SELECT pag_id FROM page";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$myPage = new PhenotypePage($row["pag_id"]);
			$myPage->rebuildURLs();
		}
		return true;
	}


	function rawXMLLayoutExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<layouts>';
		$sql = "SELECT * FROM layout ORDER BY lay_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
		<layout>
			<lay_id>'.$myPT->codeX($row["lay_id"]).'</lay_id>
			<lay_bez>'.$myPT->codeX($row["lay_bez"]).'</lay_bez>
			<lay_description>'.$myPT->codeX($row["lay_description"]).'</lay_description>
			<blocks>';
			$sql ="SELECT * FROM layout_block WHERE lay_id=".$row["lay_id"]." ORDER BY lay_blocknr";
			$rs2 = $myDB->query($sql);
			while ($row2=mysql_fetch_array($rs2))
			{
				$xml.='
				<block>
					<lay_blocknr>'.$myPT->codeX($row2["lay_blocknr"]).'</lay_blocknr>
					<lay_blockbez>'.$myPT->codeX($row2["lay_blockbez"]).'</lay_blockbez>
					<cog_id>'.$myPT->codeX($row2["cog_id"]).'</cog_id>
					<lay_context>'.$myPT->codeX($row2["lay_context"]).'</lay_context>
				</block>';
			}
			$xml.='
			</blocks>				
			<includes>';
			$sql ="SELECT * FROM layout_include WHERE lay_id=".$row["lay_id"]." ORDER BY lay_includenr";
			$rs2 = $myDB->query($sql);
			while ($row2=mysql_fetch_array($rs2))
			{
				$xml.='
				<include>
					<inc_id>'.$myPT->codeX($row2["inc_id"]).'</inc_id>
					<lay_includenr>'.$myPT->codeX($row2["lay_includenr"]).'</lay_includenr>
					<lay_includecache>'.$myPT->codeX($row2["lay_includecache"]).'</lay_includecache>
				</include>';
			}
			$xml.='
			</includes>';
			$file = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $row["lay_id"], "normal");
			$buffer1 = @file_get_contents($file);
			$file = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $row["lay_id"], "print");
			$buffer2 = @file_get_contents($file);


			$xml.='
			<templates>
				<page>'.$myPT->codeX($buffer1).'</page>
				<print>'.$myPT->codeX($buffer2).'</print>
			</templates>';
			$SQL = "SELECT * FROM layout_pagegroup WHERE lay_id=".$row["lay_id"]." ORDER BY grp_id";
			$rs2 = $myDB->query($sql);
			while ($row2=mysql_fetch_array($rs2))
			{
				$xml .='<pagegroup grp_id="'.$myPT->codeX($row2["grp_id"]).'"/>';
			}
			$xml.='
		</layout>';
		}
		$xml.='
	</layouts>
</phenotype>';
		return $xml;
	}


	/**
	 * export pagegroups in raw XML-format
	 *
	 * @return string
	 */

	function rawXMLPagegroupExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<pagegroups>';
		$sql = "SELECT * FROM pagegroup ORDER BY grp_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
		<group>
			<grp_id>'.$row["grp_id"].'</grp_id>
			<grp_bez>'.$myPT->codeX($row["grp_bez"]).'</grp_bez>
			<grp_description>'.$myPT->codeX($row["grp_description"]).'</grp_description>
			<grp_statistic>'.$myPT->codeX($row["grp_statistic"]).'</grp_statistic>
		  <grp_multilanguage>'.$myPT->codeX($row["grp_multilanguage"]).'</grp_multilanguage>
		  <grp_smarturl_schema>'.$myPT->codeX($row["grp_smarturl_schema"]).'</grp_smarturl_schema>
		</group>';
		}
		$xml.='
	</pagegroups>
</phenotype>';
		return $xml;
	}

	/**
	 * imports pagegroups
	 *
	 * @param string $buffer
	 */
	function rawXMLPagegroupImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			foreach ($_xml->pagegroups->group AS $_xml_group)
			{
				$grp_id = (int)utf8_decode($_xml_group->grp_id);
				$grp_bez = (string)utf8_decode($_xml_group->grp_bez);
				$grp_description = (string)utf8_decode($_xml_group->grp_description);
				$grp_statistic = (int)utf8_decode($_xml_group->grp_statistic);
				$grp_multilanguage = (int)utf8_decode($_xml_group->grp_multilanguage);
				$grp_smarturl_schema = (int)utf8_decode($_xml_group->grp_smarturl_schema);

				$sql  ="DELETE FROM pagegroup WHERE grp_id=".$grp_id;
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("grp_id",$grp_id,DB_NUMBER);
				$mySQL->addField("grp_statistic",$grp_statistic,DB_NUMBER);
				$mySQL->addField("grp_multilanguage",$grp_multilanguage,DB_NUMBER);
				$mySQL->addField("grp_smarturl_schema",$grp_smarturl_schema,DB_NUMBER);
				$mySQL->addField("grp_bez",$grp_bez);
				$mySQL->addField("grp_description",$grp_description);
				$sql = $mySQL->insert("pagegroup");
				$myDB->query($sql);

			}
		}
	}


	function rawXMLLayoutImport($buffer)
	{
		global $myDB;
		global $myPT;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			foreach ($_xml->layouts->layout AS $_xml_layout)
			{
				$lay_id = (int)utf8_decode($_xml_layout->lay_id);
				$lay_bez = (string)utf8_decode($_xml_layout->lay_bez);
				$lay_description = (string)utf8_decode($_xml_layout->lay_description);

				$sql  ="DELETE FROM layout WHERE lay_id=".$lay_id;
				$myDB->query($sql);

				$sql  ="DELETE FROM layout_block WHERE lay_id=".$lay_id;
				$myDB->query($sql);

				$sql  ="DELETE FROM layout_include WHERE lay_id=".$lay_id;
				$myDB->query($sql);

				$sql  ="DELETE FROM layout_pagegroup WHERE lay_id=".$lay_id;
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("lay_id",$lay_id,DB_NUMBER);
				$mySQL->addField("lay_bez",$lay_bez);
				$mySQL->addField("lay_description",$lay_description);
				$sql = $mySQL->insert("layout");
				$myDB->query($sql);

				foreach ($_xml_layout->blocks->block AS $_xml_block)
				{
					$mySQL = new SQLBuilder();
					$mySQL->addField("lay_id",$lay_id,DB_NUMBER);
					$mySQL->addField("lay_blocknr",(int)utf8_decode($_xml_block->lay_blocknr),DB_NUMBER);
					$mySQL->addField("lay_blockbez",(string)utf8_decode($_xml_block->lay_blockbez));
					$mySQL->addField("lay_context",(int)utf8_decode($_xml_block->lay_context),DB_NUMBER);
					$mySQL->addField("cog_id",(int)utf8_decode($_xml_block->cog_id),DB_NUMBER);
					$sql = $mySQL->insert("layout_block");
					$myDB->query($sql);
				}


				foreach ($_xml_layout->includes->include AS $_xml_include)
				{
					$mySQL = new SQLBuilder();
					$mySQL->addField("lay_id",$lay_id,DB_NUMBER);
					$mySQL->addField("inc_id",(int)utf8_decode($_xml_include->inc_id),DB_NUMBER);
					$mySQL->addField("lay_includenr",(int)utf8_decode($_xml_include->lay_includenr),DB_NUMBER);
					$mySQL->addField("lay_includecache",(int)utf8_decode($_xml_include->lay_includecache),DB_NUMBER);
					$sql = $mySQL->insert("layout_include");
					$myDB->query($sql);
				}

				foreach ($_xml_layout->pagegroup AS $_xml_group)
				{
					$grp_id = (int)utf8_decode($_xml_group->grp_id);
					if ($grp_id!=0) // behebt Bug, dass manche Pakete leere Pagegroups enthalten
					{
						$mySQL = new SQLBuilder();
						$mySQL->addField("lay_id",$lay_id,DB_NUMBER);
						$mySQL->addField("grp_id",$grp_id,DB_NUMBER);
						$sql = $mySQL->insert("layout_pagegroup");
						$myDB->query($sql);
					}
				}

				// Templates


				$buffer = (string)utf8_decode($_xml_layout->templates->page);
				$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $lay_id, "normal");
				$fp = fopen ($dateiname,"w");
				fputs ($fp,$buffer);
				fclose ($fp);
				@chmod ($file,UMASK);

				$buffer = (string)utf8_decode($_xml_layout->templates->print);
				$dateiname = $myPT->getTemplateFileName(PT_CFG_LAYOUT, $lay_id, "print");
				$fp = fopen ($dateiname,"w");
				fputs ($fp,$buffer);
				fclose ($fp);
				@chmod ($file,UMASK);

			}
		}
	}

	function rawXMLExport()
	{
		global $myDB;
		global $myPT;

		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>	
		<pag_id>'.$this->id.'</pag_id>
		<importmethod>overwrite</importmethod>
		<grp_id>'.$this->grp_id.'</grp_id>				
	</meta>
	<content>
	';
		$sql = "SELECT * FROM page WHERE pag_id=".$this->id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$_fields = Array("pag_uid","pag_bez","pag_titel","pag_alttitel","pag_comment","pag_quickfinder","pag_searchtext","pag_id_mimikry","pag_id_top","pag_pos","pag_cache","pag_status","usr_id_creator","pag_creationdate","usr_id","pag_date","pag_url","pag_url1","pag_url2","pag_url3","pag_url4");
		foreach ($_fields AS $k)
		{
			$xml.= '<'.$k.'>'.$myPT->codeX($row[$k]).'</'.$k.'>'."\n";
		}

		// urls stored in local properties
		/*
		$_fields = Array("pag_url1","pag_url2","pag_url3","pag_url4");
		foreach ($_fields AS $k)
		{
		if ($this->get($k))
		{
		$xml.= '<'.$k.'>'.$myPT->codeX($this->get($k)).'</'.$k.'>'."\n";
		if ($k=="pag_url1")
		{
		$xml.= '<pag_url>'.$myPT->codeX($this->get($k)).'</pag_url>'."\n";
		}
		}
		}
		*/
		$xml .= '<pag_props>'.base64_encode($row["pag_props"])."</pag_props>\n";
		$xml .= '<pag_props_locale>'.base64_encode($row["pag_props_locale"])."</pag_props_locale>\n";
		$xml.='
		<pageversions>';

		$sql = "SELECT * FROM pageversion WHERE pag_id=".$this->id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$status = 0;
			$buffer ="";
			if ($row["pag_exec_script"]==1)
			{
				$file = APPPATH . "pagescripts/".sprintf("%04d",$this->id) ."_" .sprintf("%04d", $row["ver_id"]) .".inc.php";
				$buffer = @file_get_contents($file);
			}
			if ($row["ver_id"]==$this->ver_id){$status=1;}
			$xml .='  			<version>
				<ver_id>'.$myPT->codeX($row["ver_id"]).'</ver_id>
				<ver_nr>'.$myPT->codeX($row["ver_nr"]).'</ver_nr>
				<lay_id>'.$myPT->codeX($row["lay_id"]).'</lay_id>
				<ver_bez>'.$myPT->codeX($row["ver_bez"]).'</ver_bez>
				<pag_fullsearch>'.$myPT->codeX($row["pag_fullsearch"]).'</pag_fullsearch>
				<script>'.$myPT->codeX($buffer).'</script>
				<inc_id1>'.$myPT->codeX($row["inc_id1"]).'</inc_id1>
				<inc_id2>'.$myPT->codeX($row["inc_id2"]).'</inc_id2>
				<ver_status>'.$status.'</ver_status>';

			$sql = "SELECT * FROM pageversion_autoactivate WHERE ver_id =".$row["ver_id"];
			$rs2 = $myDB->query($sql);
			while ($row2=mysql_fetch_array($rs2))
			{
				$xml .='<autoactivation date="'.$row2["ver_date"].'"/>';
			}
			$xml.='
			</version>
';
		}
		$xml.='
		</pageversions>
		<languages>';
		$sql = "SELECT lng_id,pag_titel FROM page_language WHERE pag_id = ".$this->id . " ORDER BY lng_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml.='			<language>
				<lng_id>'.$myPT->codeX($row["lng_id"]).'</lng_id>
				<pag_titel>'.$myPT->codeX($row["pag_titel"]).'</pag_titel>
			</language>';
		}
		$xml.='
		</languages>
		<sequence_data>';

		$sql = "SELECT * FROM sequence_data WHERE pag_id = ".$this->id . " AND dat_editbuffer=0 ORDER BY ver_id, dat_blocknr , dat_pos,lng_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
			<component>
				<com_id>'.$myPT->codeX($row["com_id"]).'</com_id>
				<ver_id>'.$myPT->codeX($row["ver_id"]).'</ver_id>
				<lng_id>'.$myPT->codeX($row["lng_id"]).'</lng_id>
				<dat_blocknr>'.$myPT->codeX($row["dat_blocknr"]).'</dat_blocknr>
				<dat_visible>'.$myPT->codeX($row["dat_visible"]).'</dat_visible>
				<dat_pos>'.$myPT->codeX($row["dat_pos"]).'</dat_pos>
				<dat_comdata>'.base64_encode($row["dat_comdata"]).'</dat_comdata>
				<dat_fullsearch>'.$myPT->codeX($row["dat_fullsearch"]).'</dat_fullsearch>
			</component>';
		}
		$xml.='
		</sequence_data>
	</content>
</phenotype>
';
		return $xml;
	}

	/**
	 * imports a page in PT-Raw-XML-Format
	 * 
	 * only importmethod "overwrite" is supported, no consistency checks !
	 *
	 * @param string $buffer
	 * @return int $pag_id
	 */

	function rawXMLImport($buffer)
	{
		global $myDB;
		global $myPT;

		$_versions = Array();

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			$ptversion = (string)utf8_decode($_xml->meta->ptversion);
			if ($ptversion=="##!PT_VERSION!##")
			{
				$ptversion = "9.0.0";
			}

			$pag_id = (int)utf8_decode($_xml->meta->pag_id);
			$grp_id = (int)utf8_decode($_xml->meta->grp_id);

			$action = "insert";
			$sql = "SELECT pag_id FROM page WHERE pag_id=".$pag_id;
			$rs=$myDB->query($sql);
			if (mysql_num_rows($rs)==1)
			{
				$action="update";
			}

			$mySQL = new SQLBuilder();
			$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
			$mySQL->addField("grp_id",$grp_id,DB_NUMBER);

			$_fields = Array("pag_uid","pag_bez","pag_titel","pag_alttitel","pag_comment","pag_quickfinder","pag_searchtext","pag_url","pag_url1","pag_url2","pag_url3","pag_url4");
			foreach ($_fields AS $k)
			{
				$mySQL->addField($k,(string)utf8_decode($_xml->content->$k));
			}

			if ($ptversion<"2.6.0")
			{
				$_props=(string)utf8_decode($_xml->content->pag_props);
				$mySQL->addField("pag_props_locale",base64_decode($_props));
				$_props = Array();
				$_props["pag_url1"]=(string)utf8_decode($_xml->content->pag_url);
				$mySQL->addField("pag_props",serialize($_props));
			}
			else
			{
				$_props=(string)utf8_decode($_xml->content->pag_props);
				$mySQL->addField("pag_props",base64_decode($_props));
				$_props=(string)utf8_decode($_xml->content->pag_props_locale);
				$mySQL->addField("pag_props_locale",base64_decode($_props));
			}
			$_fields = Array ("pag_id_mimikry","pag_id_top","pag_pos","pag_cache","pag_status","usr_id_creator","pag_creationdate","usr_id","pag_date");
			foreach ($_fields AS $k)
			{
				$mySQL->addField($k,(int)utf8_decode($_xml->content->$k),DB_NUMBER);
			}

			if ($action=="insert")
			{
				$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
				$sql = $mySQL->insert("page");
			}
			else
			{
				$sql = $mySQL->update("page","pag_id=".$pag_id);
			}
			$myDB->query($sql);


			// generate versions, delete old versions

			$sql ="SELECT * FROM pageversion WHERE pag_id =".$pag_id." AND pag_exec_script=1";
			$rs=$myDB->query($sql);
			while ($row=mysql_fetch_array($rs))
			{
				$file = APPPATH . "pagescripts/".sprintf("%04d",$pag_id) ."_" .sprintf("%04d", $row["ver_id"]) .".inc.php";
				@unlink($file);
			}

			$sql = "DELETE FROM pageversion WHERE pag_id =".$pag_id;
			$myDB->query($sql);

			$ver_id_activate =0;

			foreach ($_xml->content->pageversions->version AS $_xml_version)
			{
				$mySQL = new SqlBuilder();
				$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
				$mySQL->addField("ver_nr",(int)utf8_decode($_xml_version->ver_nr),DB_NUMBER);
				$mySQL->addField("lay_id",(int)utf8_decode($_xml_version->lay_id),DB_NUMBER);
				$mySQL->addField("ver_bez",(string)utf8_decode($_xml_version->ver_bez));
				$mySQL->addField("pag_fullsearch",(string)utf8_decode($_xml_version->pag_fullsearch));
				$mySQL->addField("inc_id1",(int)utf8_decode($_xml_version->inc_id1),DB_NUMBER);
				$mySQL->addField("inc_id2",(int)utf8_decode($_xml_version->inc_id2),DB_NUMBER);
				$sql = $mySQL->insert("pageversion");
				$myDB->query($sql);

				$ver_id = mysql_insert_id();

				// mapping old version_id to new version_id
				// no brutal overwrite so that users can continue working on pages, even if
				// a few pages are overwritten

				$_versions[(int)utf8_decode($_xml_version->ver_id)] = $ver_id;

				if ($ver_id_activate==0)
				{
					$ver_id_activate = $ver_id;
				}

				if ((int)utf8_decode($_xml_version->ver_status)==1)
				{
					$ver_id_activate = $ver_id;
				}

				$script = (string)utf8_decode($_xml_version->script);
				if ($script!="")
				{
					$file = APPPATH . "pagescripts/".sprintf("%04d",$pag_id) ."_" .sprintf("%04d", $ver_id) .".inc.php";
					$fp = fopen ($file,"w");
					fputs ($fp,$script);
					fclose ($fp);
					@chmod ($file,UMASK);
					$sql = "UPDATE pageversion SET pag_exec_script=1 WHERE ver_id=".$ver_id;
					$myDB->query($sql);
				}

				// Version changes
				$sql = "DELETE FROM pageversion_autoactivate WHERE pag_id=".$pag_id;
				$myDB->query($sql);

				foreach ($_xml_version->autoactivation AS $_xml_versionchange)
				{
					$mySQL = new SqlBuilder();
					$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
					$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
					$mySQL->addField("ver_date",$_xml_versionchange["date"],DB_NUMBER);
					$sql = $mySQL->insert("pageversion_autoactivate");
					$myDB->query($sql);
				}

			}

			$sql = "DELETE FROM page_language WHERE pag_id =".$pag_id;
			$myDB->query($sql);

			foreach ($_xml->content->languages AS $_xml_language)
			{
				$mySQL = new SqlBuilder();
				$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
				$mySQL->addField("lng_id",(int)utf8_decode($_xml_language->lng_id),DB_NUMBER);
				$mySQL->addField("pag_titel",(string)utf8_decode($_xml_language->pag_titel));
				$sql = $mySQL->insert("page_language");
				$myDB->query($sql);
			}

			// insert components

			$sql = "DELETE FROM sequence_data WHERE pag_id=".$pag_id;
			foreach ($_xml->content->sequence_data->component AS $_xml_component)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
				$ver_id = $_versions[(int)utf8_decode($_xml_component->ver_id)];
				$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
				$mySQL->addField("lng_id",(int)utf8_decode($_xml_component->lng_id),DB_NUMBER);
				$mySQL->addField("dat_visible",(int)utf8_decode($_xml_component->dat_visible),DB_NUMBER);
				$mySQL->addField("dat_blocknr",(int)utf8_decode($_xml_component->dat_blocknr),DB_NUMBER);
				$mySQL->addField("dat_pos",(int)utf8_decode($_xml_component->dat_pos),DB_NUMBER);
				$mySQL->addField("com_id",(int)utf8_decode($_xml_component->com_id),DB_NUMBER);
				$mySQL->addField("dat_comdata",base64_decode((string)utf8_decode($_xml_component->dat_comdata)));
				$mySQL->addField("dat_fullsearch",(string)utf8_decode($_xml_component->dat_fullsearch));
				$sql= $mySQL->insert("sequence_data");
				$myDB->query($sql);
			}





			$myPage = new PhenotypePage($pag_id);
			$myPage->activateVersion($ver_id_activate);
			// Properties für eine Einzelseite nach unten verteilen, reicht beim Vollimport nicht aus!
			$_props = Array();
			$myPage->spreadProps($pag_id,$_props);
			return $pag_id;

		}
		else
		{
			return (false);
		}
	}


	public function setTitle($s)
	{
		$this->titel = $s;
	}

	public function getTitle()
	{
		return ($this->titel);
	}
	public function setCanonicalUrl($s)
	{
		$this->canonical_url = $s;
	}

	public function getCanonicalUrl()
	{
		return ($this->canonical_url);
	}	
	/**
   * retrieves URL of a page using the DAO cache
   * 
   * You should not overwerite this method. If you want to change URL behaviour
   * stick to buildURL instead. (But keep in mind, that buildURL is only uses
   * when saving a page (on config tab) or chaning the pagegroup smartURL schema)
   *
   * @param integer $lng_id
   * @return string
   */
	public function getURL($lng_id)
	{
		global $myPT;
		return $myPT->url_for_page($this->id,null,$lng_id);
	}

	public function buildURL($lng_id=null)
	{
		global $PTC_LANGUAGES;

		if ($lng_id==null)
		{
			$lng_id=$this->lng_id;
		}
		else
		{
			$this->switchLanguage($lng_id);
		}
		if ($this->get("pag_url".$lng_id)!="")
		{
			$url =$this->get("pag_url".$lng_id);
		}
		else
		{
			// no url for startpage (only for language 1 to have a possibility to distinct them)
			if ($lng_id==1 AND $this->id == PAG_ID_STARTPAGE)
			{
				return "";
			}
			$schema = "fulltree";
			$multilanguage = $this->multilanguage;
			$url="";


			switch ($this->smarturl_schema)
			{
				case 1:  //default
				$schema = "fulltree";
				break;
				case 2:
					$schema = "fulltree";
					$multilanguage = 0;
					break;
				case 3:
					$schema = "subtree";
					break;
				case 4:
					$schema = "subtree";
					$multilanguage = 0;
					break;
				case 5:
					$schema = "flat";
					break;
				case 6:
					$schema = "flat";
					$multilanguage = 0;
					break;
				case 7:
					$schema = "php";
					$title = 0;
					break;
				case 8:
					$schema = "php";
					$multilanguage = 0;
					break;
			}

			if ($schema == "php")
			{
				$url = "index.php?id=".$this->id;
				if ($multilanguage)
				{
					$url .= "&lng_id=".$lng_id;
				}
				$url = $url;
				return $url;
			}



			$row = $this->row;
			$url = $this->urlencode($this->titel);
			if ($schema == "fulltree" OR $schema=="subtree")
			{


				while ($row["pag_id_top"]!=0)
				{
					$myNavPage = new PhenotypePage($row["pag_id_top"]);
					$myNavPage->switchLanguage($lng_id);
					$row = $myNavPage->row;
					if ($row["pag_id_top"]!=0 OR $schema=="fulltree")
					{
						$url = $this->urlencode($myNavPage->titel) ."/" .$url;
					}
				}
			}

			if ($multilanguage==1)
			{
				$url = $PTC_LANGUAGES[$lng_id]."/".$url;
			}



		}
		return $url;
	}

	/**
	 * rebuild smartURL of current page
	 *
	 * Notes:
	 * When changing the url of a page, the whole cache must get cleard ($myPT->clearcache());
	 * If the page has child pages, the url of all child pages must get rebuilt also! Then
	 * you have to set $recursive to true. It's false by default, since a common way to use this
	 * method is to rebuild the urls of all pages (and you don't want the recursion happe to often
	 * then). After that you have to clear the cache (which equally would happen to often, if it would
	 * be true by default)
	 * 
	 * 
	 * 
	 * @param boolean $recursive
	 * @param boolean $clearcache
	 */
	public function rebuildURLs($recursive=false,$clearcache=false)
	{
		global $myDB;
		global $PTC_LANGUAGES;
		global $myPT;

		$url_changed = false;

		$mySQL = new SQLBuilder();


		$url = $this->buildURL();
		$mySQL->addField("pag_url",$url);
		if ($url!=$this->row["pag_url"])
		{
			$url_changed = true;
		}


		foreach ($PTC_LANGUAGES AS $k =>$v)
		{
			//if ($this->multilanguage)
			//{
				$url = $this->buildURL($k);
			//}

			$mySQL->addField("pag_url".$k,$url);
			if ($url!=$this->row["pag_url".$k])
			{
				$url_changed = true;
			}
		}
		$sql = $mySQL->update("page","pag_id=".$this->id);
		$myDB->query($sql);

		// rebuild url of child pages, if any url has changed
		if ($url_changed==true AND $recursive=true)
		{
			$sql = "SELECT pag_id FROM page WHERE pag_id_top=".$this->id;
			$rs = $myDB->query($sql);
			while ($row=mysql_fetch_array($rs))
			{
				$myPage = new PhenotypePage($row["pag_id"]);
				$myPage->rebuildURLs(true);

			}
		}
		if ($clearcache==true)
		{
			$myPT->clearcache();
		}
	}

	/**
   * Returns the page description field ("page_bez" in DB page)
   * 
	 * added 2008/05/19 by Dominique Bös
   * @return string page description
   */
	public function getPageDescription()
	{
		return ($this->bez);
	}

}

?>
