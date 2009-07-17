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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeContentStandard extends PhenotypeBase
{
	public $id =null;
	public $content_type;
	public $uid;
	public $formid;
	public $status = 0;
	public $bez;
	public $date;
	public $img_id = 0; // Thumbnail

	public $block_nr = -1;

	public $loaded = 0;


	public $form = Array ();
	public $configform = Array ();
	public $formmode = 1;
	public $row;
	public $mySQL; // haelt temporaer eine Instanz vom SQLBuilder
	public $skins = Array ();
	public $fullsearch;

	public $update_url;



	public $showstatus = 1; // Anzeige des Statusflags  0 = nicht, 1 = normal, 2 = ohne Bearbeitungsmöglichkeit
	public $nostatus = 0; // Status immer online ?

	// Konfiguration der Reiter in der Editmaske
	// über:
	//  var $_blocks = Array("");
	//  var $_icons = Array("");
	//	var $_showblocks = Array(1,1,1 ....)
	// darf in der Superklasse nicht auskommentiert werden, damit
	// die reiterlosen Formulare funktionieren


	/**
	 * flag, wether publishing option is available
	 *
	 * @var boolean
	 */
	public $publishmode = false;

	/**
	 * flag, wether preview option is available
	 *
	 * @var boolean
	 */
	public $previewmode = false;

	// Konfiguration der Reiter in der Contentueberischt
	public $tab_alle = 1;
	public $tab_id = 1;
	public $tab_az = 1;
	public $tab_shortaz = 0;
	public $_extratabs = Array ();

	// Fehler und Infotexte
	public $errorText;
	public $alertText;
	public $infoText;

	public $showfeedback = 1;

	public $displayfeedback = true;

	protected $dhtmlwz_init = 0;



	// data tables

	protected $use_datatable = false;

	protected $datatable_name = '';

	// define the fields of the data_table
	// named array field_name => field_type
	// field_type can be all types of mysql, but the notation must include the field length, e.g. VARCHAR(30) or TINYINT(4) OR INT(11)


	protected $datatable_fieldlist = array();
	protected $datatable_mapping = array();
	protected $datatable_canupdate = false;

	protected $datatable_followstatus = false;


	protected $dat_id_min = false;
	protected $dat_id_max = false;

	function setStatus($status)
	{
		if ($this->noStatus == 1)
		{
			return;
		}
		$this->status = $status;
	}


	// Holt eine Property aus dem Formular
	/**
   * @deprecated 
   *
   * @param unknown_type $value
   * @return unknown
   */
	function fget($value)
	{
		return @ stripslashes($_REQUEST[$this->formid."_".$value]);
		//print_r ($this->_props);
	}

	function delete()
	{
		global $myDB;
		$sql = "DELETE FROM content_data WHERE dat_id = ".$this->id;
		$myDB->query($sql);

		if ($this->use_datatable)
		{
			$table = $this->datatable_name;
			if ($table=='')
			{
				$table = 'content_data'.$this->content_type;
			}

			$sql = "DELETE FROM " . $table . " WHERE dat_id=".$this->id;
			$rs = $myDB->query($sql);
		}
	}

	function edit()
	{
		return $this->displayEditForm($this);
	}

	function update()
	{
		$this->fetchEditForm($this);
	}

	function store($usr_id_editbuffer = false)
	{

		if ($this->id == null)
		{
			throw new Exception("No record at hand. Cannot store a PhenotypeContent object without a designated record.\nSee ->init(), ->load() and/or ->addNew().");	
		}
		
		global $myDB;
		$s = serialize($this->_props);

		$mySQL = new SQLBuilder();
		$mySQL->addField("dat_props", $s);
		$mySQL->addField("dat_bez", $this->get("bez"));
		$mySQL->addField("dat_status", $this->status, DB_NUMBER);

		$this->mySQL = $mySQL;
		$this->attachKeyFields();

		global $PTC_LANGUAGES;

		foreach ($PTC_LANGUAGES AS $k=>$v)
		{
			$url= $this->buildURL("show",$k);
			// / am Anfang wegfiltern
			$patterns = "/^[\/]*/";
			$url = preg_replace($patterns,"", $url);
			// / am Ende wegfiltern
			$patterns = "/[\/]\$/";
			$url = preg_replace($patterns,"", $url);

			$this->mySQL->addField("dat_permalink".$k,$url);
		}


		$fulltext = $this->buildFullText();

		$this->mySQL->addField("dat_fullsearch", $fulltext);

		$mySQL = $this->mySQL;

		if ($usr_id_editbuffer===false)
		{


			$sql = $mySQL->update("content_data", "dat_id=".$this->id);
			//echo $sql;
			$myDB->query($sql);
			$p = $this->updatePosition();
			if ($p)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("dat_pos", $p, DB_NUMBER);
				$sql = $mySQL->update("content_data", "dat_id=".$this->id);
				$myDB->query($sql);
			}
			$this->buildCache();

			// datatables?
			if ($this->use_datatable){$this->storeDataTable();}
		}
		else
		{

			$sql = "SELECT COUNT(*) AS C FROM content_data_editbuffer WHERE usr_id=".(int)$usr_id_editbuffer." AND dat_id=".$this->id. " AND con_id=".$this->content_type;

			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);

			if ($row["C"]==0)
			{
				$mySQL->addField("usr_id",(int)$usr_id_editbuffer,DB_NUMBER);
				$mySQL->addField("dat_id",$this->id,DB_NUMBER);
				$mySQL->addField("con_id",$this->content_type,DB_NUMBER);
				$sql = $mySQL->insert("content_data_editbuffer");
			}
			else
			{
				$sql = $mySQL->update("content_data_editbuffer", "dat_id=".$this->id." AND usr_id=".(int)$usr_id_editbuffer);
			}
			$myDB->query($sql);
			$mySQL = new SqlBuilder();
			$mySQL->addField("dat_altered",1);
			$sql = $mySQL->update("content_data", "dat_id=".$this->id);
			$myDB->query($sql);

		}
		return $s;
	}

	protected function storeDataTable()
	{
		global $myDB;

		// build the tablename
		$table = $this->datatable_name;
		if ($table=='')
		{
			$table = 'content_data'.$this->content_type;
		}

		// do the introsepction if in debug mode, otherwise run into the error :)
		if (PT_DEBUG){$this->buildDataTable($table);}

		if ($this->datatable_followstatus AND $this->status ==0 )
		{
			$sql = "DELETE FROM " . $table . " WHERE dat_id=".$this->id;
			$rs = $myDB->query($sql);
			return;
		}

		$mySQL = new SQLBuilder();
		foreach ($this->datatable_mapping AS $f => $p)
		{
			$mySQL->addField($f,$this->get($p));
		}

		$sql ="SELECT dat_id FROM ".$table." WHERE dat_id=".$this->id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			$mySQL->addField('dat_id',$this->id,DB_NUMBER);
			$sql = $mySQL->insert($table);
			$rs = $myDB->query($sql);
		}
		else
		{
			$sql = $mySQL->update($table,'dat_id='.$this->id);
			$rs = $myDB->query($sql);
		}
	}

	protected function buildDataTable($table)
	{
		global $myDB;
		global $myLog;



		$sql = "SELECT COLUMN_NAME, COLUMN_TYPE  FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $table."' AND table_schema = '" . DATABASE_NAME . "' ORDER BY ORDINAL_POSITION ASC";
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			// table does not exist
			$myLog->log('creating data_table '.$table,PT_LOGFACILITY_SYS);
			$sql = "CREATE TABLE `". $table. "` (dat_id int(11)";
			foreach ($this->datatable_fieldlist AS $name => $def)
			{
				$sql .= ", `".$name."` " .$def ." NULL";
			}
			$sql .= ",INDEX (dat_id))";
			$myDB->query($sql);
		}
		else
		{
			// table exists, but are all field defintions correct?
			$_fields = array();
			while ($row = mysql_fetch_array($rs))
			{
				$name = $row["COLUMN_NAME"];
				$def = $row["COLUMN_TYPE"];
				$_fields[$name] = $def;
				if (array_key_exists($name,$this->datatable_fieldlist))
				{
					// table field is in definition fieldlist
					$fielddef = trim(strtolower($this->datatable_fieldlist[$name]));

					if ($fielddef!=$def) // but it it's defined different
					{
						$sql = "ALTER TABLE `" . $table . "` CHANGE `" . $name . "` `" . $name . "` " . $fielddef . " NULL";
						$myDB->query($sql);
					}
				}
				else
				{
					// drop field, if it's not in fieldlist
					if ($name!='dat_id')
					{
						$sql = "ALTER TABLE `" . $table . "` DROP `" . $name."`";
						$myDB->query($sql);
					}
				}
				//ALTER TABLE `gallery` ADD `bla` VARCHAR( 12 ) NOT NULL ;


			}
			// check for new fields
			$_newfields = array_diff_key($this->datatable_fieldlist,$_fields);
			foreach ($_newfields AS $name => $def)
			{
				$sql = "ALTER TABLE `" . $table . "` ADD `" . $name . "` " . $def . " NULL";
				$myDB->query($sql);
			}
		}



	}

	protected function updateFieldsByDataTable()
	{
		global $myDB;


		// build the tablename
		$table = $this->datatable_name;
		if ($table=='')
		{
			$table = 'content_data'.$this->id;
		}

		// do the introsepction if in debug mode, otherwise run into the error :)
		if (PT_DEBUG){$this->buildDataTable($table);}

		if ($this->datatable_followstatus AND $this->status ==0 )
		{
			$sql = "DELETE FROM " . $table . " WHERE dat_id=".$this->id;
			$rs = $myDB->query($sql);
			return;
		}

		$sql = "SELECT * FROM " . $table . " WHERE dat_id=".$this->id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			$row = mysql_fetch_array($rs);
			foreach ($this->datatable_mapping AS $f => $p)
			{
				$this->set($p,$row[$f]);
			}
		}
	}

	function updatePosition()
	{
		// Falls nach dem Speichern die Positionen neu errechnet werden sollen
		return false;
	}

	function addnew($usr_id = -1)
	{
		global $myDB;
		global $myPT;

		$this->setDefaultProperties();

		$mySQL = new SQLBuilder();
		$mySQL->addField("con_id", $this->content_type, DB_NUMBER);
		$uid = $myPT->uid();
		$mySQL->addField("dat_uid", $uid);

		$mySQL->addField("dat_bez", $this->get("bez"));

		$s = serialize($this->_props);
		$mySQL->addField("dat_props", $s);

		if ($this->nostatus)
		{
			$mySQL->addField("dat_status", 1, DB_NUMBER);
		} else
		{
			$mySQL->addField("dat_status", 0, DB_NUMBER);
		}
		$mySQL->addField("dat_date", time(), DB_NUMBER);
		$mySQL->addField("dat_creationdate", time(), DB_NUMBER);
		if ($usr_id == -1)
		{
			$usr_id = $_SESSION["usr_id"];
		}
		$mySQL->addField("usr_id", $usr_id);
		$mySQL->addField("usr_id_creator", $usr_id);

		$this->mySQL = $mySQL;
		$this->attachKeyFields();
		if (!in_array("med_id_thumb", $this->mySQL->felder))
		{
			$this->mySQL->addField("med_id_thumb", 7); // Default-Icon
		}
		$mySQL = $this->mySQL;

		// check if id range is specified
		if ($this->dat_id_min != false)
		{
			$sql = "SELECT MAX(dat_id) AS M FROM content_data WHERE dat_id>=".(int)$this->dat_id_min." AND dat_id <=".(int)$this->dat_id_max;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_assoc($rs);
			$max = $row["M"];
			if ($max==null) // might be the first record
			{
				$sql = "SELECT COUNT(*) AS C FROM content_data WHERE dat_id>=".(int)$this->dat_id_min." AND dat_id <=".(int)$this->dat_id_max;
				$rs = $myDB->query($sql);
				$row = mysql_fetch_assoc($rs);
				$c = $row["C"];
				if ($c==0) // first record in range, take min value
				{
					$mySQL->addField("dat_id",(int)$this->dat_id_min);
				}
			}
			else // we do have records in range 
			{
				if ($max!=$this->dat_id_max) // don't use date range, if max dat_id alread is taken
				{
					// take next free number
					$mySQL->addField("dat_id",$max+1);
				}
			}
		}
		// -- end of id range check

		$sql = $mySQL->insert("content_data");
		$myDB->query($sql);
		$this->id = mysql_insert_id();
		$this->uid = $uid;
		return ($this->id);
	}

	function attachKeyFields()
	{
		// Falls es nicht implentiert wird
	}

	function setDefaultProperties()
	{
		// macht in der Superklasse keinen Sinn
	}

	function getAccessFilter()
	{
		// Falls es nicht implentiert wird
	}

	function init($row, $block_nr = 0)
	{
		$this->row = $row;
		$this->block_nr = $block_nr;

		$this->id = $row["dat_id"];
		$this->uid = $row["dat_uid"];
		$this->formid = "con_".$row["dat_id"];
		if ($this->nostatus == 1)
		{
			$this->status = 1;
		} else
		{
			$this->status = $row["dat_status"];
		}
		$this->bez = $row["dat_bez"];
		$this->date = $row["dat_date"];
		$this->usr_id = $row["usr_id"];
		$this->creationdate = $row["dat_creationdate"];
		$this->usr_id_creator = $row["usr_id_creator"];
		$this->img_id = $row["med_id_thumb"];

		if ($row["dat_props"] != "")
		{
			//print_r ($row["dat_props"]);
			$this->_props = unserialize($row["dat_props"]);
			//print_r ($this->_props);
		} else
		{
			$this->setDefaultProperties();
		}

		$this->loaded = 1;
		$this->pos = $row["dat_pos"];

		if ($this->use_datatable AND $this->datatable_canupdate)
		{
			$this->updateFieldsByDataTable();
		}
	}

	public function __construct($id = -1)
	{
		$id = (int) $id;
		if ($id != -1)
		{
			$this->load($id);
		}
	}

	function load($id)
	{
		$id = (int) $id;
		global $myDB;
		$sql = "SELECT * FROM content_data WHERE dat_id = ".$id." AND con_id = ".$this->content_type;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs) != 0)
		{
			$row = mysql_fetch_array($rs);
			$this->init($row);
		} else
		{
			$this->setDefaultProperties();
			$this->loaded = 0;
		}
	}

	function initById($id) {
		$this->load($id);
	}

	/**
	 * sets key field1 in the data table. There is a VARCHAR key1 and a INT key1, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey1($val, $type=0)
	{
		$this->setKey(1, $val, $type);
	}

	/**
	 * sets key field2 in the data table. There is a VARCHAR key2 and a INT key2, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey2($val, $type=0)
	{
		$this->setKey(2, $val, $type);
	}

	/**
	 * sets key field3 in the data table. There is a VARCHAR key3 and a INT key3, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey3($val, $type=0)
	{
		$this->setKey(3, $val, $type);
	}

	/**
	 * sets key field4 in the data table. There is a VARCHAR key4 and a INT key4, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey4($val, $type=0)
	{
		$this->setKey(4, $val, $type);
	}

	/**
	 * sets key field5 in the data table. There is a VARCHAR key5 and a INT key5, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey5($val, $type=0)
	{
		$this->setKey(5, $val, $type);
	}

	/**
	 * sets key field6 in the data table. There is a VARCHAR key6 and a INT key6, you can choose to set a specific one or both
	 *
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	function setKey6($val, $type=0)
	{
		$this->setKey(6, $val, $type);
	}

	/**
	 * sets a key field in the data table. There is a VARCHAR field and a separate INT field for every key in the DB, you can choose to set a specific one or both
	 *
	 * @param int $num	number of the key field
	 * @param mixed	$value	value of the key. can be any scalar variable, will be casted to string or int
	 * @param const int $type optional param. shows if to set the varchar key (with DB_STRING), the int ikey (with DB_NUMBER) or both. omit param to set both keys
	 */
	private function setKey($num, $value, $type=0)
	{
		if ($type == 0 || $type == DB_STRING)
		{
			$this->mySQL->addField("dat_key". $num, (string)$value);
		}
		if ($type == 0 || $type == DB_NUMBER)
		{
			$this->mySQL->addField("dat_ikey". $num, (int)$value);
		}
	}

	function buildFullText()
	{
		$fulltext = $this->_props["bez"];

		foreach ($this->_props AS $k =>$v)
		{
			$fulltext .= " | " . $v;
		}
		$fulltext = strip_tags($fulltext);
		return $fulltext;
	}

	function setThumbnail($med_id)
	{
		$this->mySQL->addField("med_id_thumb", $med_id);
	}

	function setAlternateUpdateUrl($s)
	{
		$this->update_url = $s;
	}

	// Alles rund um Cache und Skin

	function initRendering()
	{
		global $myPT;
		$myPT->startbuffer();
?> 
     $mySmarty = new PhenotypeSmarty();
	 global $myDB;
	 global $myPT;
	 
     $mySmarty->compile_dir = SMARTYCOMPILEPATH;		 
	 $mySmarty->clear_all_assign();
     $sql = "SELECT * FROM content_template WHERE con_id = " . $this->content_type;
     $rs = $myDB->query($sql);
     while ($row_etp=mysql_fetch_array($rs))
     {
	    $tpl = $row_etp["tpl_bez"];
	    $dateiname =  $myPT->getTemplateFileName(PT_CFG_CONTENTCLASS, $this->content_type, $row_etp["tpl_id"]);
	    $$tpl = $dateiname;
	 }	 
	<?php


	$code = $myPT->stopbuffer();

	return $code;
	}

	// Diese Funktion ist für das Vorberechnen der Skins
	function preRender($skin = "")
	{
		global $myPT;
		$myPT->startbuffer();
		$this->printProperties();	
		$html = $myPT->stopbuffer();
		return ($html);	
	}

	


	
	// Diese Funktion wird in Includes und Pagescripts aufgerufen
	function render($skin = "debug", $count = 0)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display($skin, $count);
		$html = $myPT->stopbuffer();
		return ($html);
	}

	function display($skin = "debug", $count = 0)
	{
		if ($this->id == "")
		{
			return (false);
		}

		if (PT_CONTENTCACHE == 0)
		{
			// Contentcache ist global deaktiviert
			if (!$this->isLoaded())
			{
				$this->load($this->id);
			}
			echo $this->preRender($skin);

		} else // Contentcache ist aktiviert
		{
			$tobuild = 0;
			if ($this->isLoaded())
			{
				// Objekt ist geladen, damit haben wir auch den Cachestatus
				if ($this->row["dat_cache".CACHENR] == 0)
				{
					$tobuild = 1;
				}
			} else
			{
				// Objekt ist nicht geladen
				global $myDB;
				$sql = "SELECT dat_cache".CACHENR." AS cache FROM content_data WHERE dat_id=".$this->id;
				$rs = $myDB->query($sql);
				$row = mysql_fetch_array($rs);
				if ($row["cache"] == 0)
				{
					$tobuild = 1;
				}
			}

			if ($tobuild)
			{
				// Cache erzeugen durch externes Contentobjekt, damit evtl. vorher ausgeführte
				// Set-Methoden ohne Store nicht die Variablen verfälschen
				$cname = "PhenotypeContent_".$this->content_type;
				$myCO = new $cname ($this->id);
				$myCO->store();
			}

			global $myPage;
			$skin = strtolower($skin);
			$tausend = floor($this->id / 1000);
			$dateiname = CACHEPATH.CACHENR."/content/".$this->content_type."/".$tausend."/content_".sprintf("%04.0f", $this->content_type)."_".sprintf("%04.0f", $this->id)."_skin_".$skin.".inc.php";
			@ include ($dateiname);
		}

		// Statistik
		global $myPage;
		if ($count == 1)
		{
			if ($myPage->buildingcache == 1 AND $myPage->includenocache == 0)
			{
				echo '<?php $myPage->contentCount('.$this->content_type.",".$this->id."); ?>";
			} else
			{
				$myPage->contentCount($this->content_type, $this->id);
			}
		}

	}

	function buildCache()
	{
		if (PT_CONTENTCACHE == 0)
		{
			return false;
		}

		if (!file_exists(CACHEPATH.CACHENR))
		{
			mkdir(CACHEPATH.CACHENR, UMASK);
		}
		if (!file_exists(CACHEPATH.CACHENR."/content"))
		{
			mkdir(CACHEPATH.CACHENR."/content", UMASK);
		}

		if (!file_exists(CACHEPATH.CACHENR."/content/".$this->content_type))
		{
			mkdir(CACHEPATH.CACHENR."/content/".$this->content_type, UMASK);
		}

		$tausend = floor($this->id / 1000);

		if (!file_exists(CACHEPATH.CACHENR."/content/".$this->content_type."/".$tausend))
		{
			mkdir(CACHEPATH.CACHENR."/content/".$this->content_type."/".$tausend, UMASK);
		}

		/*
		// Auskommentiert, weil die Notwendig automatisch Debugskins anzulegen nicht mehr
		// besteht
		if (!in_array("debug", $this->skins))
		{
		$this->skins[] = "debug";
		}
		*/
		for ($i = 0; $i < count($this->skins); $i ++)
		{
			$skin = strtolower($this->skins[$i]);

			$html = $this->preRender($skin);

			$dateiname = CACHEPATH.CACHENR."/content/".$this->content_type."/".$tausend."/content_".sprintf("%04.0f", $this->content_type)."_".sprintf("%04.0f", $this->id)."_skin_".$skin.".inc.php";
			$fp = fopen($dateiname, "w");
			fputs($fp, $html);
			fclose($fp);
			@ chmod($dateiname, UMASK);
		}

		global $myDB;
		$mySQL = new SQLBuilder();
		for ($i = 1; $i <= CACHECOUNT; $i ++)
		{
			$cache = 0;
			if ($i == CACHENR)
			{
				$cache = time();
			}
			$mySQL->addField("dat_cache".$i, $cache, DB_NUMBER);
		}
		$sql = $mySQL->update("content_data", "dat_id=".$this->id);
		$myDB->query($sql);
	}

	// -- Ende der Funktionen, die von den einzelnen Contentobjekten gebraucht werden

	function addRecord($type)
	{
		// Content-Datensatz anlegen

		$ename = "PhenotypeContent_".$type;
		$myCO = new $ename;
		$myCO->addNew();

		return $myCO->id;
	}

	// Inzwischen 40 Formelemente

	function form_headline($headline,$space=false)
	{
		$a = Array (PT_CON_FORM_HEADLINE, $headline,$space);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_textfield($input, $bez, $size)
	{
		$a = Array (PT_CON_FORM_TEXTFIELD, $input, $bez, $size);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_password($input, $bez, $size)
	{
		$a = Array (PT_CON_FORM_PASSWORD, $input, $bez, $size);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_number($input, $bez, $size, $decimals = 2)
	{
		$a = Array (PT_CON_FORM_NUMBER, $input, $bez, $size, $decimals);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_textfield_cluster($input, $bez, $size, $n)
	{
		$a = Array (PT_CON_FORM_TEXTFIELDCLUSTER, $input, $bez, $size, $n);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_ddtextfield_cluster($input, $bez, $size, $n, $sort)
	{
		$a = Array (PT_CON_FORM_DDTEXTFIELDCLUSTER, $input, $bez, $size, $n,$sort);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_textarea($input, $bez, $x, $rows)
	{
		$a = Array (PT_CON_FORM_TEXTAREA, $input, $bez, $x, $rows);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_date($input, $bez)
	{
		$a = Array (PT_CON_FORM_DATE, $input, $bez);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	/**
   * Currently not working!
   *
   * @param unknown_type $input
   * @param unknown_type $bez
   */
	function form_datetime($input, $bez)
	{
		$a = Array (PT_CON_FORM_DATETIME, $input, $bez);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_html($input, $bez, $x, $rows)
	{
		$a = Array (PT_CON_FORM_HTML, $input, $bez, $x, $rows);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_selectbox($input, $bez, $_options, $addzerodots=true)
	{
		$a = Array (PT_CON_FORM_SELECTBOX, $input, $bez, $_options, $addzerodots);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_content_selectbox($input, $bez, $con_id, $addzerodots=true, $statuscheck=true,$sql_where="")
	{
		$a = Array (PT_CON_FORM_CONTENTSELECTBOX, $input, $bez, $addzerodots, $con_id, $statuscheck, $sql_where);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_doubleselectbox($input, $bez, $_options, $text, $bez2, $_options2, $text2)
	{
		$a = Array (PT_CON_FORM_DOUBLESELECTBOX, $input, $bez, $_options, $text, $bez2, $_options2, $text2);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_multiselectbox($input, $bez, $_options, $x, $y)
	{
		$a = Array (PT_CON_FORM_MULTISELECTBOX, $input, $bez, $_options, $x, $y);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_content_multiselectbox($input, $bez, $x, $y,$con_id,  $statuscheck=true,$sql_where="")
	{
		$a = Array (PT_CON_FORM_CONTENTMULTISELECTBOX, $input, $bez, $x, $y, $con_id, $statuscheck,$sql_where);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_expandinglist($input, $bez, $dat_id_expandinglist)
	{
		$a = Array (PT_CON_FORM_EXLIST, $input, $bez, $dat_id_expandinglist);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_newline()
	{
		$a = Array (PT_CON_FORM_NEWLINE);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_sequence($cog_id, $blocknr = 1)
	{
		$a = Array (PT_CON_FORM_SEQUENCE, $cog_id, $blocknr);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_image_selector($input, $bez, $folder, $changefolder = 1, $x = 0, $y = 0)
	{
		$a = Array (PT_CON_FORM_IMAGESELECTOR, $input, $bez, $folder, $changefolder, $x, $y);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_image_extern($input, $bez)
	{
		$a = Array (PT_CON_FORM_IMAGEEXTERN, $input, $bez);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_richtext($input, $bez, $x, $rows, $filter = 1)
	{
		$a = Array (PT_CON_FORM_RICHTEXT, $input, $bez, $x, $rows, $filter);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_doubletextfield($input, $bez, $size, $bez2, $size2)
	{
		$a = Array (PT_CON_FORM_DOUBLETEXTFIELD, $input, $bez, $size, $bez2, $size2);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_checkbox($input, $bez, $text)
	{
		$a = Array (PT_CON_FORM_CHECKBOX, $input, $bez, $text);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_link($input, $bez, $link_title=true, $link_target=true, $link_pageselector=false, $link_text=false, $link_popup=false, $link_source=false, $link_type=false, $link_type_options=Array())
	{
		$a = Array (PT_CON_FORM_LINK, $input, $bez,$link_title,$link_target,$link_text,$link_popup,$link_source,$link_type,$link_type_options,$link_pageselector);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_upload($input, $bez,$folder="_upload",$grp_id=2,$imageasdocument=0)
	{
		$a = Array (PT_CON_FORM_UPLOAD, $input, $bez,$folder,$grp_id,$imageasdocument);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_document($input, $bez, $infozeile = 0)
	{
		$a = Array (PT_CON_FORM_DOCUMENT, $input, $bez, $infozeile);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_document_selector($input, $bez, $folder, $changefolder = 1, $infozeile = 0, $doctype = "")
	{
		$a = Array (PT_CON_FORM_DOCUMENTSELECTOR, $input, $bez, $folder, $changefolder, $infozeile, $doctype);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_media_selector($input, $bez, $folder, $changefolder = 1, $infozeile = 0, $doctype = "")
	{
		$a = Array (PT_CON_FORM_MEDIASELECTOR, $input, $bez, $folder, $changefolder, $infozeile, $doctype);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_script($input, $bez, $x, $rows, $filename)
	{
		$a = Array (PT_CON_FORM_SCRIPT, $input, $bez, $x, $rows, $filename);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_comment($input, $text)
	{
		$a = Array (PT_CON_FORM_COMMENT, $input, $text);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_editlink($input, $text, $url, $target = "_self")
	{
		$a = Array (PT_CON_FORM_EDITLINK, $input, $text, $url, $target);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_button($input, $name, $formname, $url = "", $target = "")
	{
		$a = Array (PT_CON_FORM_BUTTON, $input, $name, $formname, $url, $target);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_wrap($input, $methodname, $params = Array (), $colspan = 0)
	{
		$a = Array (PT_CON_FORM_WRAP, $input, $methodname, $colspan, $params);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_ajax($input, $token="ajax",$height=50,$colspan = 0)
	{
		$a = Array (PT_CON_FORM_AJAX, $input, $token,$colspan,$height);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	/**
	 * Provides a Drag & Drop Upload Zone
	 *
	 * @param string label name
	 * @param string target folder for uploaded files (on top of PT_TEMPPATH/contentupload/con_id/usr_id)
	 * @param integer width
	 * @param integer height
	 * 
	 * 
	 * If you use this form_xy method you must overwrite the update-method, 
	 * look into the upload folder and process the uploads somehow ...
	 * 
	 * Usally the parameter $folder is best initialized with "$this->id", so you get the uploaded
	 * file related to your record. If you have more than one form_ddupload method on your content entry form
	 * you must specify different folder names. (Sub folders are not possible here.)
	 * 
	 */
	function form_ddupload($input, $folder="", $x=405, $y=100)
	{
		$a = Array (PT_CON_FORM_DDUPLOAD, $input, $folder, $x, $y);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_ddpositioner($input, $bez, $quantity, $methodname)
	{
		$a = Array (PT_CON_FORM_DDPOSITIONER, $input, $bez, $quantity, $methodname);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}

	function form_table($input, $size, $_table, $column_status=0,$colum_edit =0,$_align="",$_width="")
	{
		$a = Array (PT_CON_FORM_TABLE, $input, $size, $_table, $column_status,$colum_edit,$_align,$_width);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}


	function form_hidden($bez, $value)
	{
		$a = Array (PT_CON_FORM_HIDDEN, $bez, $value);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}


	}


	function form_pager($bez, $count, $p="")
	{
		if ($p=="")
		{
			global $myRequest;
			$p = $myRequest->getI("p");
			if ($p==0){$p=1;}
		}
		$a = Array(PT_CON_FORM_PAGER, $bez, $count, $p);
		if ($this->formmode == 1)
		{
			$this->form[] = $a;
		} else
		{
			$this->configform[] = $a;
		}
	}


	function form_javascript_onload($js)
	{
		$a = Array(PT_CON_FORM_JAVASCRIPTONLOAD, $js);
		$this->form[] = $a;
	}

	function form_user($title,$property,$null=true)
	{
		$this->form[] = array(
		"form_method" =>"form_user",
		"property" =>$property,
		"title" =>$title,
		"null" => $null
		);
	}

	function displayEditForm($myCO)
	{
		$form = $myCO->form;
		$id = $myCO->id;
		return $this->displayForm($myCO, $form, $id);
	}

	function displayForm($myCO, $form, $id)
	{
		// Bei Erweiterungen dran denken, dass $i belegt ist !!!

		global $myLayout;
		global $myDB;
		global $mySUser;
		global $myPT;
		global $myRequest;
		global $myApp;
?>
<input type="hidden" name="http_referer" value ="<?php echo $_SERVER["HTTP_REFERER"] ?>">   
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<?php

	// Array for Javascript-Function which must be displayed at the same time like the edit form
	$_jsarray = Array();

	for ($i = 0; $i < count($form); $i ++)
	{
		$a = $form[$i];

		if (array_key_exists("form_method",$a))
		{
			$mname = "display_".$a["form_method"];
			if (method_exists($this,$mname))
			{
				$myCO->$mname($a);
			}
			else
			{
				die($a["form_method"] ." implemented imperfectly. Missing display method: ".$mname);
			}
		}
		else
		{

			switch ($a[0])
			{
				// ######## Headline
				case PT_CON_FORM_HEADLINE:

					if ($a[2]==true)
					{
			?>
			</table>		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  </table> 
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite" tabindex="99">
            <input type="image" src="transparentpixel.gif" width="1" height="1" onclick="return false;"/>
            <input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diesen Datensatz wirklich l&ouml;schen?')"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern" tabindex="1" accesskey="s">&nbsp;&nbsp;</td>
          </tr>
        </table>

	   		</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
		  <br/>
			 <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      		
			<?php	
					}
			?>
            <tr>
              <td class="tableHline" colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
	        <tr>
              <td class="tableHead" colspan="2"><?php echo $a[1] ?></td>
            </tr>
            <tr>
              <td class="tableHline" colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
		<?php


		break;

		// ######## Textbox
				case PT_CON_FORM_TEXTFIELD :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $val = $myCO->get($a[2]);
             echo $myLayout->workarea_form_text("", $name, $val, $a[3], 0);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Password
				case PT_CON_FORM_PASSWORD :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $val = $myCO->get($a[2]);
             echo $myLayout->workarea_form_password("", $name, $val, $a[3], 0);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Zahl
				case PT_CON_FORM_NUMBER :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $val = $myCO->get($a[2]);
             $val = sprintf("%01.".$a[4]."f", $val);
             echo $myLayout->workarea_form_text("", $name, $val, $a[3], 0);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Textbox-Cluster
				case PT_CON_FORM_TEXTFIELDCLUSTER :
?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $n = $a[4];

             for ($j = 1; $j <= $n; $j ++)
             {
             	$name = $myCO->formid."_".$a[2]."_".$j;
             	$val = $myCO->get($a[2]."_".$j);
             	echo $myLayout->workarea_form_text("", $name, $val, $a[3], 1);
             }
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Doppelte Textbox (z.B. Strasse/Hausnummer)
				case PT_CON_FORM_DOUBLETEXTFIELD :
			?>
            <tr>
            <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
            </td>
            <td width="509" class="formarea"><p>
            <?php


            $name = $myCO->formid."_".$a[2];
            $val = $myCO->get($a[2]);
            echo $myLayout->workarea_form_text("", $name, $val, $a[3], 0);

            $name = $myCO->formid."_".$a[4];
            $val = $myCO->get($a[4]);
            echo $myLayout->workarea_form_text("", $name, $val, $a[5], 0);
			?>
            </p>
            </td>
            </tr>
			<?php
			break;

			// ######## Textarea
				case PT_CON_FORM_TEXTAREA :
			?>
            <tr>
            <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
            </td>
            <td width="509" class="formarea"><p>
            <?php
            $name = $myCO->formid."_".$a[2];
            $val = $myCO->get($a[2]);
            echo $myLayout->workarea_form_textarea("", $name, $val, $a[4], $a[3]);
			?>
            </p>
            </td>
            </tr>
		<?php


		break;

		// ######## Datumsbox
				case PT_CON_FORM_DATE :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
 			 <?php
 			 $name = $myCO->formid."_".$a[2];
 			 $val = $myCO->get($a[2]);
 			 if ($val != "")
 			 {
 			 	$datum = @ date("d.m.Y", $val);
 			 } else
 			 {
 			 	$val = time();
 			 	$datum = "";
 			 }
			 ?>
			 <input type="text" name="<?php echo $name ?>" value="<?php echo $datum ?>" size="9" class="input" onchange="ajax_<?php echo $a[2] ?>_doit(this.value,0)"><a href="javascript:flip('divajax_<?php echo $a[2] ?>')"><img src="img/b_kalender_tr.gif" width="18" height="18" border="0" align="absmiddle"></a>
			 <div id="divajax_<?php echo $a[2] ?>" style="position:absolute;visibility:hidden"></div>
			 <script type="text/javascript">
			 var ajax_<?php echo $a[2] ?> = new sack();

			 var error=0;
			 function ajax_<?php echo $a[2] ?>_doit(date,timestamp){
			 	ajax_<?php echo $a[2] ?>.resetData();
			 	ajax_<?php echo $a[2] ?>.requestFile = "backend.php";
			 	ajax_<?php echo $a[2] ?>.method = "GET";
			 	ajax_<?php echo $a[2] ?>.element = 'divajax_<?php echo $a[2] ?>';
			 	ajax_<?php echo $a[2] ?>.setVar("page","Editor,Content,selector_date");
			 	ajax_<?php echo $a[2] ?>.setVar("d",date);
			 	ajax_<?php echo $a[2] ?>.setVar("t",timestamp);
			 	ajax_<?php echo $a[2] ?>.setVar("e",'<?php echo $a[2] ?>');
			 	ajax_<?php echo $a[2] ?>.runAJAX();
			 }
			 function setDate_<?php echo $a[2] ?>(date)
			 {
			 	document.forms.editform.<?php echo $name ?>.value=date;
			 	hide('divajax_<?php echo $a[2] ?>');
			 	ajax_<?php echo $a[2] ?>_doit(date,0);
			 }
			</script>
			<script type="text/javascript">ajax_<?php echo $a[2] ?>_doit(<?php echo $val ?>,1)</script>
			 </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Datumsbox mit Uhrzeit
				case PT_CON_FORM_DATETIME :

  ?>
              <tr>
              <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
              </td>
              <td width="509" class="formarea"><p>
               <?php


               $name = $myCO->formid."_".$a[2];
               $val = $myCO->get($a[2]);
               if ($val != "")
               {
               	$datum = @ date("d.m.Y H:i", $val);
               } else
               {
               	$val == time();
               	$datum = "";
               }
 ?>
  			 <input type="text" name="<?php echo $name ?>" value="<?php echo $datum ?>" size="14" class="input" ><a href="javascript:flip('divajax_<?php echo $a[2] ?>')"><img src="img/b_kalender_tr.gif" width="18" height="18" border="0" align="absmiddle"></a>
  			 <div id="divajax_<?php echo $a[2] ?>" style="position:absolute;visibility:hidden"></div>
  			 <script type="text/javascript">
  			 var ajax_<?php echo $a[2] ?> = new sack();

  			 var error=0;
  			 function ajax_<?php echo $a[2] ?>_doit(date,timestamp){
  			 	ajax_<?php echo $a[2] ?>.resetData();
  			 	ajax_<?php echo $a[2] ?>.requestFile = "backend.php";
  			 	ajax_<?php echo $a[2] ?>.method = "GET";
  			 	ajax_<?php echo $a[2] ?>.element = 'divajax_<?php echo $a[2] ?>';
  			 	ajax_<?php echo $a[2] ?>.setVar("page","Editor,Content,selector_date");
  			 	ajax_<?php echo $a[2] ?>.setVar("d",date);
  			 	ajax_<?php echo $a[2] ?>.setVar("t",timestamp);
  			 	ajax_<?php echo $a[2] ?>.setVar("e",'<?php echo $a[2] ?>');
  			 	ajax_<?php echo $a[2] ?>.runAJAX();
  			 }
  			 function setDate_<?php echo $a[2] ?>(date)
  			 {
  			 	document.forms.editform.<?php echo $name ?>.value=date;
  			 	hide('divajax_<?php echo $a[2] ?>');
  			 }
 			</script>
  			<script type="text/javascript">ajax_<?php echo $a[2] ?>_doit(<?php echo (int)$val ?>, 1)</script>
               </p>
               </td>
               </tr>
  		<?php


  		break;
  		// ######## HTML
				case PT_CON_FORM_HTML :
?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
             <?php


             $name = $myCO->formid."_".$a[2];
             $buffer = $myCO->get($a[2]);
             $filename_tmp = TEMPPATH."htmlarea/~".$name."_".uniqid("").".tmp";
             $fp = fopen($filename_tmp, "w");
             fputs($fp, $buffer);
             fclose($fp);
             @ chmod($filename_tmp, UMASK);
             $myLayout->form_HTMLTextarea($name, $filename_tmp, 80, $a[4], "HTML", $a[3]);
             unlink($filename_tmp);
             //echo $myLayout->workarea_form_textarea("",$name,$val,$a[4],$a[3]);
?>
             </td>
             </tr>
		<?php


		break;

		// ######## Selectbox
				case PT_CON_FORM_SELECTBOX :
		?>
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>" class="input">
		<?php 
		if ($a[4]==true){
		?>
		<option value="0">...</option>
		<?php
		}

		foreach ($a[3] as $key => $val)
		{
			$selected = "";
			if ($key == $myCO->get($a[2]))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
		<?php


		}
		?>
        </select>
        </p>
        </td>
        </tr>
		<?php
		break;

		// ######## Selectbox auf Contentdatensätze
				case PT_CON_FORM_CONTENTSELECTBOX :
		?>
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>" class="listmenu">
		<?php 
		if ($a[3]==true){
		?>
		<option value="0">...</option>
		<?php
		}
		$sql  = "SELECT dat_id,dat_bez FROM content_data WHERE con_id=".$a[4];
		if ($a[5]==true)
		{
			$sql .=" AND dat_status=1";
		}
		if ($a[6] !="")
		{
			$sql .=" AND " . $a[6];
		}
		$sql .= " ORDER BY dat_bez";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$selected = "";
			if ($row["dat_id"] == $myCO->get($a[2]))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option value="<?php echo $row["dat_id"] ?>" <?php echo $selected ?>><?php echo $row["dat_bez"] ?></option>
		<?php


		}
		?>
        </select>
        </p>
        </td>
        </tr>
		<?php
		break;

		// ######## Doppelte Selectbox
				case PT_CON_FORM_DOUBLESELECTBOX :
?>
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<?php echo $a[4] ?> <select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>" class="input">
		<option value="0">...</option>
		<?php


		foreach ($a[3] as $key => $val)
		{
			$selected = "";
			if ($key == $myCO->get($a[2]))
			{
				$selected = "selected";
			}
?>
		<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
		<?php


		}
?>
        </select>&nbsp;
        <?php echo $a[7] ?>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[5] ?>" class="input">
		<option value="0">...</option>
		<?php


		foreach ($a[6] as $key => $val)
		{
			$selected = "";
			if ($key == $myCO->get($a[5]))
			{
				$selected = "selected";
			}
?>
		<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
		<?php


		}
?>
        </select>		
        </p>
        </td>
        </tr>
		<?php


		break;

		// ######## Multiple Selectbox
				case PT_CON_FORM_MULTISELECTBOX :
		?>	
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>[]" class="input_multiselectbox" multiple style="width: <?php echo $a[4] ?>px; height: <?php echo $a[5] ?>px">
		<?php


		foreach ($a[3] as $key => $val)
		{
			$selected = "";
			if (in_array($key, $myCO->get($a[2])))
			{
				$selected = "selected";
			}
		?>
		<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
		<?php
		}
		?>
        </select>
        </p>
        </td>
        </tr>
		<?php
		break;

		// ######## Multiple Selectbox auf Basis von Contentdatensätzen
				case PT_CON_FORM_CONTENTMULTISELECTBOX :
		?>	
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>[]" class="input_multiselectbox" multiple style="width: <?php echo $a[3] ?>px; height: <?php echo $a[4] ?>px">
		<?php


		$sql  = "SELECT dat_id,dat_bez FROM content_data WHERE con_id=".$a[5];
		if ($a[6]==true)
		{
			$sql .=" AND dat_status=1";
		}
		if ($a[7] !="")
		{
			$sql .=" AND " . $a[7];
		}
		$sql .= " ORDER BY dat_bez";
		//echo $sql;
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$selected = "";
			if (in_array($row["dat_id"], $myCO->get($a[2])))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option value="<?php echo $row["dat_id"] ?>" <?php echo $selected ?>><?php echo $row["dat_bez"] ?></option>
		<?php
		}
		?>
        </select>
        </p>
        </td>
        </tr>
		<?php		


		break;

		// ######## Expanding List
				case PT_CON_FORM_EXLIST :
		?>
		<tr>
        <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
		<select name="<?php echo $myCO->formid ?>_<?php echo $a[2] ?>" class="input">
		<option value="">...</option>
		<?php


		$myList = new PhenotypeExpandingList($a[3]);
		$match = 0;
		foreach ($myList->get("liste") as $val)
		{
			$selected = "";
			if ($val == $myCO->get($a[2]))
			{
				$selected = "selected";
				$match = 1;
			}
?>
		<option value="<?php echo $val ?>" <?php echo $selected ?>><?php echo $val ?></option>
		<?php


		}
?>
        </select>
		<?php


		$val = "";
		if ($match == 0)
		{
			$val = $myCO->get($a[2]);
		}
		echo $myLayout->workarea_form_text("", $myCO->formid."_".$a[2]."_new", $val, 150, 0)
?>
        </p>
        </td>
        </tr>
		<?php

		break;

		// ######## Newline
				case PT_CON_FORM_NEWLINE :
?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<?php


		break;

		// ######## Sequenz
				case PT_CON_FORM_SEQUENCE :

					$block_nr = $a[2];
					$cog_id = $a[1];

					if (!$myRequest->check("editbuffer"))
					{
						$sql = "DELETE FROM  sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr = ".$block_nr ." AND dat_editbuffer=1 AND usr_id=".(int)$_SESSION["usr_id"];
						$myDB->query($sql);

						$sql = "INSERT INTO sequence_data(dat_id,dat_id_content,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,usr_id) SELECT dat_id, dat_id_content, 1 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,".(int)$_SESSION["usr_id"] ." AS usr_id  FROM sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr = " .$block_nr." AND dat_editbuffer=0";
						$myDB->query($sql);
						//echo $sql;
					}

			?>
		<!-- sequence start here -->
		<tr>
			<td colspan="2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
      			<td colspan="4">&nbsp;</td>
    		</tr>
            <input type="hidden" value="1" name="editbuffer">
			<input type="hidden" name="block_nr" value="<?php echo $block_nr ?>">	
			<input type="hidden" name="newtool_id" value="">	
			<input type="hidden" name="newtool_type" value="">
			<?php
			// Das erste Bausteinpulldown
			$myLayout->workarea_componentselector_draw($cog_id,0);

			$sql = "SELECT * FROM sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer=1 AND usr_id=".(int)$_SESSION["usr_id"] . " ORDER BY dat_pos";
			$rs = $myDB->query($sql);

			$n= mysql_num_rows($rs);
			$j=0;
			while ($row = mysql_fetch_array($rs))
			{

				$j++;
				$tname = "PhenotypeComponent_" . $row["com_id"];
				$myComponent = new $tname;
				$myComponent->init($row);

				// ND-Erweiterung, Info über den Context
				$myComponent->set("content_type",$this->content_type);
				$myComponent->set("dat_id_content",$this->id);

				?>
 				<tr>
            		<td class="padding30"><strong><?php echo $myComponent->bez ?></strong><br><input name="<?php echo $row["dat_id"] ?>_visible" type="checkbox" value="checkbox" <?php if ($myComponent->visible){echo "checked";} ?> >sichtbar
            	</td>
            	<td>&nbsp;</td>
            	<td class="formarea">
      			<?php
      			$myComponent->edit();
      			?>
            	</td>
            	<td align="center">
            	<?php
            	if ($j>1)
            	{
				?>
				<input type="image" src="img/b_up.gif" alt="Baustein nach oben verschieben" width="18" height="18" border="0" name="<?php echo $row["dat_id"] ?>_moveup"><br>
				<?php
            	}
				?>
                <input type="image" src="img/b_delete.gif" alt="Baustein l&ouml;schen" width="22" height="22" border="0"  name="<?php echo $row["dat_id"] ?>_delete">
				<?php
				if ($mySUser->checkRight("superuser"))
				{
				?>
				<br><a href="component_debug.php?id=<?php echo $row["dat_id"] ?>" target="_blank"><img src="img/b_debug_grey.gif" border="0"></a>
				<?php
				}
				if ($j<$n)
				{
			  	?>
              	<br><input type="image" src="img/b_down.gif" alt="Baustein nach unten verschieben" width="18" height="18" border="0" name="<?php echo $row["dat_id"] ?>_movedown">
			  	<?php
				}
			  	?>
			  	</td>        
          		</tr>
       			<?php
       			$myLayout->workarea_componentselector_draw($cog_id,$row["dat_id"]);
			}
			?>
			<tr>
      			<td colspan="4">&nbsp;</td>
    		</tr>
    		</table>
				</td>
			</tr>    		
			<?php

			// Ende Sequenz
			break;

			// ######## Bild
				case PT_CON_FORM_IMAGESELECTOR :
?>
		     <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
			 <?php


			 $fname = $this->formid."_".$a[2];
			 $val = $myCO->get($a[2]);
			 $img_id = $myCO->get($a[2]."_img_id");
			 if ($img_id == "")
			 {
			 	$img_id = 0;
			 }
			 $folder = $a[3];

			 echo $myLayout->workarea_form_image($fname, $img_id, $folder, $a[4], $a[5], $a[6]);
?>
			 </p>
			 </td></tr>
			 
		<?php


		break;

		// ######## Externes Bild
				case PT_CON_FORM_IMAGEEXTERN :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
			 <?php


			 $val = $myCO->get($a[2]);
			 if ($val != "")
			 {
?>
			 <img src="<?php echo $val ?>"><br clear="all">
			 <?php


			 }
?>
			 <p>
             <?php


             $name = $myCO->formid."_".$a[2];
             echo $myLayout->workarea_form_text("", $name, $val, 250, 0);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Richtext
				case PT_CON_FORM_RICHTEXT :
?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
             <?php


             $name = $myCO->formid."_".$a[2];
             $val = $myCO->get($a[2]);
             $val = $myApp->richtext_prefilter($val,$myCO);
             echo $myLayout->form_Richtext($name, $val, 80, $a[4], $a[3]);
?>
             
             </td>
             </tr>
		<?php


		break;

		// ######## Checkbox
				case PT_CON_FORM_CHECKBOX :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $val = $myCO->get($a[2]);
             echo $myLayout->workarea_form_checkbox("", $name, $val, $a[3]);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Link
				case PT_CON_FORM_LINK :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];

             // $a[3] $link_title=true
             // $a[4] $link_target=true
             // $a[5] $link_text=false
             // $a[6] $link_popup=false
             // $a[7] $link_source=false
             // $a[8] $link_type=false
             // $a[9] $link_type_options
             // $a[10] $link_pageselector

             if ($a[3]!==false){$bez = $myCO->get($a[2]."_bez");}else{$bez=false;}
             $url = $myCO->get($a[2]."_url");
             if ($a[4]!==false){$target = $myCO->get($a[2]."_target");}else{$target=false;}
             if ($a[5]!==false){$linktext = $myCO->get($a[2]."_text");}else{$linktext=false;}
             if ($a[6]!==false){$popup_x = $myCO->get($a[2]."_x");$popup_y = $myCO->get($a[2]."_y");}else{$popup_x=false;$popup_y=false;}
             if ($a[7]!==false){$linksource = $myCO->get($a[2]."_source");}else{$linksource=false;}
             if ($a[8]!==false){$linktype = $myCO->get($a[2]."_type");$linktype_options=$a[9];}else{$linktype=false;$linktype_options=false;}
             $pageselector = $a[10];



             echo $myLayout->workarea_form_link($name, $bez, $url, $target,$linktext,$linksource,$popup_x,$popup_y,$linktype,$linktype_options,$pageselector);
?>
             </p>
             </td>
             </tr>
		<?php


		break;

		// ######## Dokument
				case PT_CON_FORM_DOCUMENT :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $med_id = $myCO->get($a[2]."_med_id");

             if ($a[3])
             {
?>
			 Infozeile<br>
			 <?php


			 $bez = $myCO->get($a[2]."_bez");

			 echo $myLayout->workarea_form_text("", $name."bez", $bez, 300, 1);
			 echo "<br>";
             }
             echo $myLayout->workarea_form_document($name, $med_id);
?>
             </p>
             </td>
             </tr>
		<?php

		break;

		// ######## Documentselector
				case PT_CON_FORM_DOCUMENTSELECTOR :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $med_id = $myCO->get($a[2]."_med_id");

             if ($a[5]==1)
             {
?>
			 Infozeile<br>
			 <?php


			 $bez = $myCO->get($a[2]."_bez");

			 echo $myLayout->workarea_form_text("", $name."bez", $bez, 300, 1);
			 echo "<br>";
             }
             $folder = $a[3];
             echo $myLayout->workarea_form_document2($name, $med_id, $a[3], $a[4], $a[6]);
?>
             </p>
             </td>
             </tr>		     
		
			 
		<?php


		break;


		// ######## Upload
				case PT_CON_FORM_UPLOAD :

					$name = $myCO->formid."_".$a[2];
			 ?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <input type="file" name="<?php echo $name ?>_userfile" class="input"/>
             <br/>
             <br/>
             <?php

             $med_id = $myCO->get($a[2]."_med_id");

             $myDoc = new PhenotypeDocument($med_id);
             $doc_id = $myDoc->id;
             if ($doc_id!=0) // Dokument und kein Bild
             {
                ?>
     			<table id="<?php echo $name ?>panel" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              	<tr>
                <td nowrap>
                <?php
                $myDoc = new PhenotypeDocument($med_id);
                echo '<a href="'.$myDoc->url.'" target="_blank">Dokument Nr. ' . $myDoc->id . " - " . $myDoc->bez."</a>";
                ?>
     			</td></tr>
     			</table>
				<?php
             }
             $myImg = new PhenotypeImage($med_id);
             $img_id = $myImg->id;
             if ($img_id!=0) // Dokument und kein Bild
             {
			 ?>
			  <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
				   <a href="<?php echo MEDIABASEURL . $myImg->physical_folder ?>/<?php echo $myImg->filename ?>" target="_blank">
				   <?php
				   $myImg->display_thumb($alt);
			       ?></a>
     			</td>
     		  </tr>
     		  </table> 
     		 <?php
             }
			 ?>
             </p>
             </td>
             </tr>		     
		
			 
		<?php


		break;

		// ######## Mediaselector (wie Dokument kann aber auch Bilder zuordnen)
				case PT_CON_FORM_MEDIASELECTOR :
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
             <?php


             $name = $myCO->formid."_".$a[2];
             $med_id = $myCO->get($a[2]."_med_id");

             if ($a[5])
             {
?>
			 Infozeile<br>
			 <?php


			 $bez = $myCO->get($a[2]."_bez");

			 echo $myLayout->workarea_form_text("", $name."bez", $bez, 300, 1);
			 echo "<br>";
             }
             $folder = $a[3];
             echo $myLayout->workarea_form_media($name, $med_id, $a[3], $a[4], $a[6]);
?>
             </p>
             </td>
             </tr>		     
		
			 
		<?php


		break;


		// ######## Skript
				case PT_CON_FORM_SCRIPT :
?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
			 <b><?php echo $a[5] ?></b><br>
             <?php


             $filename = $a[5];
             $name = $myCO->formid."_".$a[2];
             $myLayout->form_HTMLTextarea($name, $filename, 80, $a[4], "PHP", $a[3]);
             //echo $myLayout->workarea_form_textarea("",$name,$val,$a[4],$a[3]);
?>
             </td>
             </tr>
		<?php


		break;


		// ######## Kommentar
				case PT_CON_FORM_COMMENT :
?>
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
             <div style="width:460px">
             <p>
             <?php echo $myPT->codeHKT($a[2]) ?> </p></div>
             </td>
             </tr>
		<?php


		break;


		// ######## Editlink
				case PT_CON_FORM_EDITLINK :
?>
             <tr>
             <td width="120" class="padding30" valign="middle"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
             <table cellspacing="0" cellpadding="0" border="0"></tr><td>
             <p><?php echo $a[2] ?></p>
             </td><td width="10">&nbsp;</td><td>
             <a href="<?php echo $a[3] ?>" target="<?php echo $a[4] ?>"><img src="img/b_edit.gif" border="0"></a>
             </td></tr></table>
                </td>
             </tr>
		<?php


		break;


		// ######## Button
				case PT_CON_FORM_BUTTON :
					//2do Targets
					if ($a[4] == "")
					{
?>
             <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p><input type="submit" value="<?php echo $a[2] ?>" class="buttonWhite" style="width:102px" name="<?php echo $a[3] ?>"></p>
             </td>
             </tr>
		<?php


					} else // Button mit URL
					{
?>
		 <tr>
             <td width="120" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p><input type="button" value="<?php echo $a[2] ?>" class="buttonWhite" style="width:102px" name="<?php echo $a[3] ?>" onclick="self.location.href='<?php echo $a[4] ?>'"></p>
             </td>
             </tr>
		<?php


					}
					break;


					// ######## wrap
				case PT_CON_FORM_WRAP :

					if ($a[3] == 0)
					{
?>
             <tr>
             <td width="120" valign="top" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea"><p>
			 <?php


			 $mname = $a[2];
			 $this-> $mname ($a[4])
?></p>
             </td>
             </tr>
		<?php }else{ ?>
	 
			 <tr><td colspan="2" class="padding30"><p>
			 <?php

			 $mname = $a[2];
			 $this-> $mname ($a[4])
?></p></td></tr>
		<?php
		}
		break;


		// ######## ajax
				case PT_CON_FORM_AJAX :

					if ($a[3] == 0)
					{
?>
             <tr>
             <td width="120" valign="top" class="padding30"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
			 <script type="text/javascript">
			 var ajax_<?php echo $a[2] ?> = new sack();


			 var error=0;

			 function ajax_<?php echo $a[2] ?>_whenCompleted(){

			 	s = ajax_<?php echo $a[2] ?>.response;
			 	p = s.indexOf("###");
			 	step = s.substring(4, p);

			 	error =0;
			 	if (ajax_<?php echo $a[2] ?>.responseStatus)
			 	{
			 		if (ajax_<?php echo $a[2] ?>.responseStatus[0]!=200)
			 		{
			 			error = 1;
			 		}
			 	}
			 	else
			 	{
			 		error = 1;
			 	}

			 	if (error==0)
			 	{
			 		if (step=='stop')
			 		{
			 			// Abgeschlossen
			 		}
			 		else
			 		{
			 			//alert(filenr);
			 			ajax_<?php echo $a[2] ?>_doit(step);
			 		}
			 	}
			 	else
			 	{
			 		alert ("AJAX-Fehler, OK für Wiederholung ("+ step+")");
			 		ajax_<?php echo $a[2] ?>_doit(step);
			 	}
			 }


			 function ajax_<?php echo $a[2] ?>_doit(step){
			 	ajax_<?php echo $a[2] ?>.resetData();
			 	ajax_<?php echo $a[2] ?>.requestFile = "backend.php";
			 	ajax_<?php echo $a[2] ?>.method = "GET";
			 	ajax_<?php echo $a[2] ?>.element = 'divajax_<?php echo $a[2] ?>';
			 	ajax_<?php echo $a[2] ?>.setVar("page","Editor,Content,form_ajax");
			 	ajax_<?php echo $a[2] ?>.setVar("step",step);
			 	ajax_<?php echo $a[2] ?>.setVar("token","<?php echo $a[2] ?>");
			 	ajax_<?php echo $a[2] ?>.setVar("con_id","<?php echo $this->content_type ?>");
			 	ajax_<?php echo $a[2] ?>.setVar("dat_id","<?php echo $this->id ?>");
			 	ajax_<?php echo $a[2] ?>.setVar("usr_id","<?php echo $mySUser->id ?>");
			 	//ajax.onLoading = whenLoading;
			 	//ajax.onLoaded = whenLoaded;
			 	//ajax.onInteractive = whenInteractive;
			 	ajax_<?php echo $a[2] ?>.onCompletion = ajax_<?php echo $a[2] ?>_whenCompleted;
			 	ajax_<?php echo $a[2] ?>.runAJAX();
			 }
			</script>
			<div id="divajax_<?php echo $a[2] ?>" style="width:400px;height:<?php echo $a[4] ?>px;"></div>
			<script type="text/javascript">ajax_<?php echo $a[2] ?>_doit('start')</script>
             </td>
             </tr>
		<?php }else{ ?>
	 
			 <tr><td colspan="2" class="padding30"><p>
			 <?php
			 test
?></p></td></tr>
		<?php		

		}
		break;


		// ######## DD-Upload
				case PT_CON_FORM_DDUPLOAD :
					$target_url = ADMINFULLURL ."admin_ddcontentupload.php?con_id=".$this->content_type ."&userid=".$mySUser->id."&savepath=".$a[2]; 
					$md5hash = md5("con_id".$this->content_type."userid".$mySUser->id."savepath".$a[2].PT_SECRETKEY);
					?>
	
             <tr>
             <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
             </td>
             <td width="509" class="formarea">
             <?php echo $myLayout->workarea_form_ddupload((int)$a[3],(int)$a[4],$target_url,"Drag & Drop - Upload",$md5hash);?>
             </td>
             </tr>
		<?php


		break;


		// ######## Positioner
				case PT_CON_FORM_DDPOSITIONER :
			 ?>
	         <tr>
	         <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
	         </td>
	         <td width="509" class="formarea">
			 <p>
			 <?php
			 $anzahl = $a[3];
			 $token = $this->formid."_".$a[2]."_ddp_";
			 $kette = "";
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 	$kette .= ",".$j;
			 }
			 $_position = $this->get($a[2]);
			 if (!is_array($_position))
			 {
			 	$_position = Array ();
			 	for ($j = 1; $j <= $anzahl; $j ++)
			 	{
			 		$_position[] = $j;
			 	}
			 }
			 else
			 {
			 	if (count($_position)!=$anzahl) // Array an die neue Anzahl anpassen
			 	{
			 		$_newposition = Array();
			 		foreach ($_position AS $k => $v)
			 		{
			 			if ($v<=$anzahl)
			 			{
			 				$_newposition[]=$v;
			 			}
			 		}
			 		for ($j = 1; $j <= $anzahl; $j ++)
			 		{
			 			if (!in_array($j,$_newposition))
			 			{
			 				$_newposition[] = $j;
			 			}
			 		}
			 		$_position = $_newposition;
			 	}
			 }
			 ?>
			 <input type="hidden" name="<?php echo $token ?>_poschange" value="<?php echo $kette ?>"/>
<input type="hidden" name="<?php echo $token ?>_posstart" value="<?php echo implode(",",$_position) ?>"/>
			 <?php
			 $this->displayDHtmlWZJavascript($token, $anzahl,16);

			 $mname = $a[4];
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 ?>
			 <div id="<?php echo $token.$j ?>" style="width:404px;position:relative;background:url(img/moveit.gif) top left no-repeat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:<?php echo $token ?>movedown(<?php echo $j ?>)"><img src="img/b_down2.gif" width="18" height="18" border="0"></a>
        	 <a href="javascript:<?php echo $token ?>moveup(<?php echo $j ?>)"><img src="img/b_up2.gif" width="18" height="18" border="0"></a><br/><div style="background-color:#D1D6DB;padding:2px;width:404px;"><p><?php echo $this->$mname($_position[$j-1]); ?></p></div></div><br/>
			 <script type="text/javascript">ADD_DHTML_DELAYED("<?php echo $token ?><?php echo $j ?>"+VERTICAL+TRANSPARENT);</script>
			 <?php
			 }
			 ?>
			 <script type="text/javascript">
			 <?php
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 	?>
			 	SET_DHTML_DROPFUNC_DELAYED('<?php echo $token ?><?php echo $j ?>',<?php echo $token ?>dropTopListItem);
			 	//dd.elements.<?php echo $token ?><?php echo $j ?>.setDropFunc(<?php echo $token ?>dropTopListItem);
			 	<?php
			 }
			 ?>
			 </script>
			 </p>
             </td>
             </tr>
			 <?php
			 break;


			 // ######## DD-Textbox-Cluster
				case PT_CON_FORM_DDTEXTFIELDCLUSTER :
 ?>
	         <tr>
	         <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
	         </td>
	         <td width="509" class="formarea">
			 <p>
			 <?php
			 $anzahl = $a[4];
			 $token = $this->formid."_".$a[2]."_ddp_";
			 $kette = "";
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 	$kette .= ",".$j;
			 }
			 $_position = $this->get($a[2]."_pos");
			 if (!is_array($_position))
			 {
			 	$_position = Array ();
			 	for ($j = 1; $j <= $anzahl; $j ++)
			 	{
			 		$_position[] = $j;
			 	}
			 }
			 if (count($_position)!=$anzahl) // Array an die neue Anzahl anpassen
			 {
			 	$_newposition = Array();
			 	foreach ($_position AS $k => $v)
			 	{
			 		if ($v<=$anzahl)
			 		{
			 			$_newposition[]=$v;
			 		}
			 	}
			 	for ($j = 1; $j <= $anzahl; $j ++)
			 	{
			 		if (!in_array($j,$_newposition))
			 		{
			 			$_newposition[] = $j;
			 		}
			 	}
			 	$_position = $_newposition;
			 }
			 ?>
			 <input type="hidden" name="<?php echo $token ?>_poschange" value="<?php echo $kette ?>"/>
<input type="hidden" name="<?php echo $token ?>_posstart" value="<?php echo implode(",",$_position) ?>"/>
			 <?php
			 $this->displayDHtmlWZJavascript($token, $anzahl,16);

			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 ?>
			 <div id="<?php echo $token.$j ?>" style="padding:0px;width:480px;position:relative;background:url(img/moveitonerow.gif) top left no-repeat"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:<?php echo $token ?>movedown(<?php echo $j ?>)"><img src="img/b_down2.gif" width="18" height="18" border="0"></a>
        	 <a href="javascript:<?php echo $token ?>moveup(<?php echo $j ?>)"><img src="img/b_up2.gif" width="18" height="18" border="0"></a>
        	 <?php
        	 $name = $myCO->formid."_".$a[2]."_".$_position[$j-1];
        	 $val = $myCO->get($a[2]."_".$_position[$j-1]);
        	 echo $myLayout->workarea_form_text("", $name, $val, $a[3], 1);
        	 ?>
        	 </p></div><br/>
			 <script type="text/javascript">ADD_DHTML_DELAYED("<?php echo $token ?><?php echo $j ?>"+VERTICAL+TRANSPARENT);</script>
			 <?php
			 }
			 ?>
			 <script type="text/javascript">
			 <?php
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 	?>
			 	SET_DHTML_DROPFUNC_DELAYED('<?php echo $token ?><?php echo $j ?>',<?php echo $token ?>dropTopListItem);
			 	//dd.elements.<?php echo $token ?><?php echo $j ?>.setDropFunc(<?php echo $token ?>dropTopListItem);
			 	<?php
			 }
			 ?>
			 </script>
			 </p>
             </td>
             </tr>
			 <?php				
			 break;


			 // ########
				case PT_CON_FORM_TABLE:
					$_table = $a[3];

					$n = count ($_table[0]);

					$col_edit  = $n;
					$col_status = $n;
					if ($a[5]==1)
					{
						$col_edit--;
					}
					if ($a[4]==1)
					{
						$col_status = $col_edit-1;
					}
			?>
			<tr>
	        <td width="120" class="padding30" valign="top"><p><strong><?php echo $a[1] ?></strong></p>
	        </td>
	        <td width="509" class="formarea">
			<p>
			<table width="<?php echo $a[1] ?>" border="0" cellpadding="0" cellspacing="0">
      		<tr>
        	<td valign="top" class="window">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="<?php echo $n ?>" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
            <tr>
            <?php
            for ($j=0;$j<$n;$j++)
            {
            	$width="left";
            	if (is_array($a[7]))
            	{
            		$width = ';width:'.$a[7][$j].'px';
            	}
			?>
			  <td width="*" class="tableHead" style="text-align:left<?php echo $width ?>" valign="top"><?php echo $_table[0][$j] ?></td>
			<?php
            }
			?>
            </tr>
            <tr>
              <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
             <?php
             $m = count($_table);
             for ($l=1;$l<$m;$l++)
             {
				?>
				<tr>
				<?php
				for ($j=0;$j<$n;$j++)
				{
					$align="left";
					if (is_array($a[6]))
					{
						$align = $a[6][$j];
					}
					switch ($j)
					{
						case $col_status:
							if ((int)($_table[$l][$j])==1)
							{
						?>
						<td  class="tableBody"><img src="img/i_online.gif"></td>
						<?php	
							}
							else
							{
						?>
						<td  class="tableBody"><img src="img/i_offline.gif"></td>
						<?php
							}
							break;
						case $col_edit:
					?>
					<td  class="tableBody"><a href="<?php echo urlencode($_table[$l][$j]) ?>"><img src="img/b_edit.gif" border="0"></a></td>
					<?php						
					break;
						default:
					?>
					<td  class="tableBody" style="text-align:<?php echo $align ?>" valign="top"><?php echo $_table[$l][$j] ?></td>
					<?php
					break;
					}
				}
			?>
			</tr>
			<?php
             }
			?>
    				<tr>	
              <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
			
        </table>
        </td>
        </tr>
        </table>
        			 </p>
             </td>
             </tr>
			<?php
			break;


			// ######## form_hidden
				case PT_CON_FORM_HIDDEN:


					$name = $myCO->formid."_".$a[1];
					$val = $a[2];
			?>
			<tr><td colspan="3"><p><strong><input type="hidden" name="<?php echo $name ?>" value="<?php echo $val ?>"/></td></tr>
            <?php
            break;


            // ######## form_pager
				case PT_CON_FORM_PAGER:
			?>


</table>		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  </table> 
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite padding30"   width="120" valign="top">
		    <p><strong><?php echo $a[1] ?></strong></p>
		    <input type="hidden" name="p" value="<?php echo $a[3] ?>"/>
			</td>
            <td align="left" class="windowFooterWhite" width="509">
            <!-- -->
            <table cellspacing="1" cellpading="0">
             <tr>
             <?php
             for ($j=1;$j<=$a[2];$j++)
             {
             	$class="tabmenuType";
             	if ($j==$a[3])
             	{
             		$class ="tabmenuTypeActive";
             	}
             	$url = "id=" . $this->id . "&amp;uid=".$this->uid."&amp;b=".$this->block_nr."&amp;p=".$j
             	?>
             	<td align="center"><a href="backend.php?page=Editor,Content,edit&<?php echo $url ?>" class="<?php echo $class ?>"><?php echo $j ?></a></td>			
             	<?php
             }
             	?>
		     </tr>
             </table>
            <!-- -->
            </td>
          </tr>
			<?php
			break;


			// ######## form_javascript_onload
				case PT_CON_FORM_JAVASCRIPTONLOAD:

					$_jsarray["body_onload"]=$a[1];
					break;

			}
		}
	}
?>
</table>
	<?php
	return ($_jsarray);

	}


	function display_form_user($_params)
	{
		global $myDB;
  	?>
    <tr>
        <td width="120" class="padding30"><p><strong><?php echo $_params["title"] ?></strong></p>
        </td>
        <td width="509" class="formarea"><p>
    <select name="<?php echo $this->formid ?>_<?php echo $_params["property"] ?>" class="input">
    <?php 
    if ($_params["null"]==true){
    ?>
    <option value="0">...</option>
    <?php
    }
    $sql = "SELECT usr_nachname, usr_vorname, usr_id  FROM user WHERE usr_status = 1  ORDER BY usr_nachname,usr_vorname";
    $rs = $myDB->query($sql);
    while ($row = mysql_fetch_assoc($rs))
    {
    	if ($row["usr_nachname"]!="" AND $row["usr_vorname"]!="")
    	{
    		$name = $row["usr_nachname"].", ".$row["usr_vorname"];
    	}
    	else
    	{
    		$name =$row["usr_nachname"].$row["usr_vorname"];
    	}
    	$name = trim($name);
    	if ($row["usr_id"] == $this->getI($_params["property"]))
    	{
    		$selected = 'selected="selected"';
    	}
    ?>
    <option value="<?php echo $row["usr_id"] ?>" <?php echo $selected ?>><?php echo $name ?></option>
    <?php 
    }
	}

	function displayDHtmlWZJavascript($token, $anzahl,$spacer)
	{
?>
  				 <?php


  				 if ($this->dhtmlwz_init == 0)
  				 {
?>
			 <script type="text/javascript">
			 SET_DHTML(CURSOR_MOVE);
			 </script>
			 <?php


			 $this->dhtmlwz_init = 1;
  				 }
?>
  	<script type="text/javascript">
  	function <?php echo $token ?>moveup(item)
  	{

  		order = <?php echo $token ?>determineOrder();
  		currentpos = item;
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			if(order[i]==item)
  			{
  				currentpos =i+1;
  			}
  		}
  		if (currentpos>1)
  		{
  			// Item an der übergeordneten Position bestimmen
  			changepos = currentpos-1;
  			changeitem = order[changepos-1];

  			citem ="<?php echo $token ?>" + item;
  			y = dd.elements[citem].y;
  			x = dd.elements[citem].x;

  			citem2 ="<?php echo $token ?>" + changeitem;
  			y2 = dd.elements[citem2].y;
  			x2 = dd.elements[citem2].x;

  			dd.elements[citem].moveTo(x2,y2);
  			dd.elements[citem2].moveTo(x,y);
  		}

  		<?php echo $token ?>dropTopListItem();
  	}

  	function <?php echo $token ?>movedown(item)
  	{
  		order = <?php echo $token ?>determineOrder();
  		currentpos = item;
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			if(order[i]==item)
  			{
  				currentpos =i+1;
  			}
  		}
  		if (currentpos<<?php echo $anzahl ?>)
  		{
  			// Item an der übergeordneten Position bestimmen
  			changepos = currentpos+1;
  			changeitem = order[changepos-1];

  			citem ="<?php echo $token ?>" + item;
  			y = dd.elements[citem].y;
  			x = dd.elements[citem].x;

  			citem2 ="<?php echo $token ?>" + changeitem;
  			y2 = dd.elements[citem2].y;
  			x2 = dd.elements[citem2].x;

  			dd.elements[citem].moveTo(x2,y2);
  			dd.elements[citem2].moveTo(x,y);
  		}
  		<?php echo $token ?>dropTopListItem();
  	}

  	function <?php echo $token ?>determineOrder()
  	{
  		order = new Array();

  		for (i=1;i<=<?php echo $anzahl ?>;i++)
  		{
  			citem ='<?php echo $token ?>' + i;
  			y = dd.elements[citem].y;
  			order[i-1]=y + "#" + i;

  		}

  		order.sort
  		(
  		function(a,b)
  		{
  			return (parseInt(a)-parseInt(b));
  		}
  		);

  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			p = order[i].indexOf("#")
  			order[i]=order[i].substr(p+1);
  		}
  		return (order);
  	}

  	function <?php echo $token ?>storeOrder()
  	{
  		order = <?php echo $token ?>determineOrder();
  		s="";
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			s = s+ "," + order[i];
  		}
  		document.forms.editform.<?php echo $token ?>_poschange.value=s;
  	}

  	function  <?php echo $token ?>dropTopListItem()
  	{

  		order = <?php echo $token ?>determineOrder();

  		citem ="<?php echo $token ?>1";
  		y = dd.elements[citem].defy;
  		for (i=1;i<=<?php echo $anzahl ?>;i++)
  		{
  			citem ="<?php echo $token ?>" + order[i-1];
  			x = dd.elements[citem].x;
  			h = dd.elements[citem].h;
  			dd.elements[citem].moveTo(x,y);
  			y=y+h+<?php echo $spacer ?>;
  		}
  		<?php echo $token ?>storeOrder();

  	}


  	//-->

    </script>
    <?php


	}

	function fetchEditForm($myCO)
	{
		$form = $myCO->form;
		$id = $myCO->formid;
		$this->fetchForm($myCO, $form, $id);
	}

	function fetchForm($myCO, $form, $id)
	{
		// Bei Erweiterungen dran denken, dass $i belegt ist !!!

		global $myDB;
		global $myAdm;
		global $myPT;
		global $myRequest;
		global $myApp;

		for ($i = 0; $i < count($form); $i ++)
		{
			$a = $form[$i];
			if (array_key_exists("form_method",$a))
			{
				$mname = "fetch_".$a["form_method"];
				if (method_exists($this,$mname))
				{
					$myCO->$mname($a);
				}
				else
				{
					die($a["form_method"] ." implemented imperfectly. Missing update method: ".$mname);
				}
			}
			else
			{
				switch ($a[0])
				{
					case PT_CON_FORM_TEXTFIELD : // Textfeld
					$fname = $myCO->formid."_".$a[2];
					$myCO->set($a[2], $myRequest->get($fname));
					$this->fullsearch .= $myRequest->get($fname)." | ";
					break;

					case PT_CON_FORM_NUMBER : // Number
					$fname = $myCO->formid."_".$a[2];
					$v = $myRequest->get($fname);
					$v = str_replace(",", ".", $v);
					$v = sprintf("%01.".$a[4]."f", $v);
					$myCO->set($a[2], $v);
					$this->fullsearch .= $myRequest->get($fname)." | ";
					break;

					case PT_CON_FORM_TEXTFIELDCLUSTER : // Textfeld Cluster
					$n = $a[4];
					for ($j = 1; $j <= $n; $j ++)
					{
						$fname = $myCO->formid."_".$a[2]."_".$j;
						$myCO->set($a[2]."_".$j, $myRequest->get($fname));
						$this->fullsearch .= $myRequest->get($fname)." | ";
					}

					break;

					case PT_CON_FORM_TEXTAREA :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						$this->fullsearch .= $myRequest->get($fname)." | ";
						break;

					case PT_CON_FORM_DATE :
						$fname = $myCO->formid."_".$a[2];
						$data = $myRequest->get($fname);
						$data = $myPT->german2Timestamp($data);
						$myCO->set($a[2], $data);
						break;

					case PT_CON_FORM_DATETIME :
						$fname = $myCO->formid."_".$a[2];
						$data = $myRequest->get($fname);
						$data = $myPT->germanDT2Timestamp($data);
						$myCO->set($a[2], $data);
						break;

					case PT_CON_FORM_HTML :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myAdm->decodeRequest_HTMLArea($myRequest->get($fname)));
						break;

						// form_selectbox
					case PT_CON_FORM_SELECTBOX :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						$myCO->set($a[2]."_value", @ $a[3][$myRequest->get($fname)]);
						break;

					case PT_CON_FORM_CONTENTSELECTBOX :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						//$myCO->set($a[2]."_value", @ $a[3][$myRequest->get($fname)]);
						break;

						// form_doubleselectbox
					case PT_CON_FORM_DOUBLESELECTBOX :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						$myCO->set($a[2]."_value", @ $a[3][$myRequest->get($fname)]);
						$fname = $myCO->formid."_".$a[5];
						$myCO->set($a[5], $myRequest->get($fname));
						$myCO->set($a[5]."_value", @ $a[6][$myRequest->get($fname)]);
						break;

						// form_multiselectbox
					case PT_CON_FORM_MULTISELECTBOX :
						$fname = $myCO->formid."_".$a[2];

						$_selections = $_REQUEST[$fname];
						if (!is_array($_selections))
						{
							$_selections = Array ();
						}

						$myCO->set($a[2], $_selections);
						break;


						// form_content_multiselectbox
					case PT_CON_FORM_CONTENTMULTISELECTBOX :
						$fname = $myCO->formid."_".$a[2];

						$_selections = $_REQUEST[$fname];
						if (!is_array($_selections))
						{
							$_selections = Array ();
						}

						$myCO->set($a[2], $_selections);
						break;

						// form_expandinglist
					case PT_CON_FORM_EXLIST :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						$fname = $myCO->formid."_".$a[2]."_new";
						if ($myRequest->get($fname) != "")
						{
							$neu = $myRequest->get($fname);
							$myList = new PhenotypeExpandingList($a[3]);
							$myList->addItem($neu);
							$myList->store();
							$myCO->set($a[2], $neu);
						}
						break;

					case PT_CON_FORM_SEQUENCE : // Sequenz

					$block_nr = $a[2];
					$cog_id = $a[1];


					$sql = "SELECT * FROM sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer = 1 AND usr_id = ". (int)$_SESSION["usr_id"] ." ORDER BY dat_pos";
					$rs = $myDB->query($sql);

					while ($row = mysql_fetch_array($rs))
					{
						$i++;
						$tname = "PhenotypeComponent_" . $row["com_id"];
						$myComponent = new $tname;
						$myComponent->init($row);
						if (isset($_REQUEST[$row["dat_id"]."_visible"]))
						{
							$myComponent->visible =1;
						}
						else
						{
							$myComponent->visible =0;
						}

						$myComponent->update();
						$myComponent->store();
						// has a component been deleted?
						if (isset($_REQUEST[$row["dat_id"]."_delete_x"]))
						{
							$myComponent->delete();
							$pos=($i-1);
							//$del_tool_id = $row["dat_id"];
						}
						// move component up
						if (isset($_REQUEST[$row["dat_id"]."_moveup_x"]))
						{
							$myComponent->moveup();
							$pos=$i;
						}
						// move component downs
						if (isset($_REQUEST[$row["dat_id"]."_movedown_x"]))
						{
							$myComponent->movedown();
							$pos=$i;
						}
					}

					// new component inserted?
					$new_tool_id = $_REQUEST["newtool_id"];
					if ($new_tool_id !="")
					{
						$tname = "PhenotypeComponent_" . $_REQUEST["newtool_type"];
						$myComponent = new $tname;
						$myComponent->addNew(0,0,$this->id,$block_nr,$new_tool_id);
					}

					if ($myRequest->check("save"))
					{
						// save button pressed
						$sql = "DELETE FROM  sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr = ".$block_nr ." AND dat_editbuffer=0";
						$myDB->query($sql);

						$sql = "INSERT INTO sequence_data(dat_id,dat_id_content,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,usr_id) SELECT dat_id, dat_id_content, 0 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible, usr_id  FROM sequence_data WHERE dat_id_content = " . $this->id . " AND dat_blocknr = " .$block_nr." AND dat_editbuffer=1 AND usr_id = " .(int)$_SESSION["usr_id"];
						$myDB->query($sql);
						//echo $sql;

					}

					// end sequence
					break;

					case PT_CON_FORM_IMAGESELECTOR :
						$fname = $myCO->formid."_".$a[2]."img_id";
						$myCO->set($a[2]."_img_id", $myRequest->get($fname));

						break;

					case PT_CON_FORM_IMAGEEXTERN :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						break;

					case PT_CON_FORM_RICHTEXT : // Richtext
					$fname = $myCO->formid."_".$a[2];
					$s = $myRequest->get($fname);
					if ($a[5] == 1)
					{
						$s = $myApp->richtext_strip_tags($s);
					}
					$s = $myApp->richtext_postfilter($s,$myCO);
					$myCO->set($a[2], $s);
					$this->fullsearch .= strip_tags($s)." | ";
					break;

					case PT_CON_FORM_DOUBLETEXTFIELD :
						$fname = $myCO->formid."_".$a[2];
						$myCO->set($a[2], $myRequest->get($fname));
						$this->fullsearch .= $myRequest->get($fname)." | ";
						$fname = $myCO->formid."_".$a[4];
						$myCO->set($a[4], $myRequest->get($fname));
						$this->fullsearch .= $myRequest->get($fname)." | ";
						break;

					case PT_CON_FORM_CHECKBOX : // Checkbox
					$fname = $myCO->formid."_".$a[2];
					if ($myRequest->check($fname))
					{
						$myCO->set($a[2], 1);
					} else
					{
						$myCO->set($a[2], 0);
					}
					break;

					case PT_CON_FORM_LINK : // Link
					// $a[3] $link_title=true
					// $a[4] $link_target=true
					// $a[5] $link_text=false
					// $a[6] $link_popup=false
					// $a[7] $link_source=false
					// $a[8] $link_type=false
					// $a[9] $link_type_options

					$fname = $myCO->formid."_".$a[2];
					$myCO->set($a[2]."_bez", $myRequest->get($fname."bez"));
					$this->fullsearch .= $myRequest->get($fname."bez")." | ";
					$myCO->set($a[2]."_url", $myRequest->get($fname."url"));
					$myCO->set($a[2]."_target", $myRequest->get($fname."target"));
					$myCO->set($a[2]."_type", $myRequest->get($fname."type"));
					$myCO->set($a[2]."_text", $myRequest->get($fname."text"));
					$myCO->set($a[2]."_source", $myRequest->get($fname."source"));
					$myCO->set($a[2]."_x", $myRequest->get($fname."x"));
					$myCO->set($a[2]."_y", $myRequest->get($fname."y"));
					if ($myCO->get($a[2]."_target")=="_self"){$myCO->set($a[2]."_x","");$myCO->set($a[2]."_y","");}
					break;

					case PT_CON_FORM_UPLOAD:
						$fname = $myCO->formid."_".$a[2]."_userfile";

						$dateiname_original =  $_FILES[$fname]["name"];
						$suffix = strtolower(substr($dateiname_original,strrpos($dateiname_original,".")+1));

						$myMB = new PhenotypeMediabase();
						$grp_id = $a[4];

						$myMB->setMediaGroup($grp_id);

						$folder = $a[3];

						$type = MB_DOCUMENT;
						$_suffix = Array("jpg","gif","jpeg","png");
						if (in_array($suffix,$_suffix))
						{
							$type = MB_IMAGE;
							if ($a[5]==1)
							{
								$type = MB_DOCUMENT;
							}
						}

						if ($type== MB_IMAGE)
						{
							$id = $myMB->uploadImage($fname,$folder);
						}
						else
						{
							$id = $myMB->uploadDocument($fname,$folder);
						}

						if ($id) // Hochladen erfolgreich
						{
							$this->set($a[2]."_med_id",$id);
						}
						break;

					case PT_CON_FORM_DOCUMENT : // Dokument
					$fname = $myCO->formid."_".$a[2];
					$myCO->set($a[2]."_med_id", $myRequest->get($fname."med_id"));
					if ($a[3])
					{
						$myCO->set($a[2]."_bez", $myRequest->get($fname."bez"));
					} else
					{
						$myCO->set($a[2]."_bez", "");
					}
					break;

					case PT_CON_FORM_DOCUMENTSELECTOR : // Dokument2
					$fname = $myCO->formid."_".$a[2];
					$myCO->set($a[2]."_med_id", $myRequest->get($fname."med_id"));
					if ($a[3])
					{
						$myCO->set($a[2]."_bez", $myRequest->get($fname."bez"));
					} else
					{
						$myCO->set($a[2]."_bez", "");
					}
					break;

					case PT_CON_FORM_MEDIASELECTOR : // Mediaselector
					$fname = $myCO->formid."_".$a[2];
					$img_id = $myRequest->get($fname."img_id");
					$med_id = 0;
					$type = 0;
					if ($img_id <> 0)
					{
						$med_id = $img_id;
						$type = MB_IMAGE;
					} else
					{
						$med_id = $myRequest->get($fname."med_id");
						if ($med_id <> 0)
						{
							$type = MB_DOCUMENT;
						}
					}
					$myCO->set($a[2]."_med_id", $med_id);
					$myCO->set($a[2]."_med_type", $type);
					$mimetype = "";
					if ($med_id != 0)
					{
						$sql = "SELECT med_mimetype FROM media WHERE med_id=".$med_id;
						$rs = $myDB->query($sql);
						$row = mysql_fetch_array($rs);
						$mimetype = $row["med_mimetype"];
					}
					$myCO->set($a[2]."_mimetype", $mimetype);
					if ($a[3])
					{
						$myCO->set($a[2]."_bez", $myRequest->get($fname."bez"));
					} else
					{
						$myCO->set($a[2]."_bez", "");
					}
					break;

					case PT_CON_FORM_SCRIPT :
						$fname = $myCO->formid."_".$a[2];
						$buffer = $myAdm->decodeRequest_HTMLArea($myRequest->get($fname));
						$myCO->set($a[2], $buffer);
						$filename = $a[5];
						$fp = fopen($filename, "w");
						fputs($fp, $buffer);
						fclose($fp);
						@ chmod($filename, UMASK);
						break;

					case PT_CON_FORM_DDPOSITIONER : // Positioner
					$fname = $myCO->formid."_".$a[2]."_ddp_";

					$posstart = $myRequest->get($fname."_posstart");
					$poschange = $myRequest->get($fname."_poschange");
					$_posstart = explode(",", $posstart);
					$_poschange = explode(",", $poschange);
					$anzahl = $a[3];
					$_posstore = Array ();

					for ($j = 1; $j <= $anzahl; $j ++)
					{
						$tpos = $_poschange[$j] - 1;
						$_posstore[] = $_posstart[$tpos];
					}

					$myCO->set($a[2], $_posstore);
					break;

					case PT_CON_FORM_DDTEXTFIELDCLUSTER : // DD-Textfieldcluster
					$fname = $myCO->formid."_".$a[2]."_ddp_";

					$posstart = $myRequest->get($fname."_posstart");
					$poschange = $myRequest->get($fname."_poschange");
					$_posstart = explode(",", $posstart);
					$_poschange = explode(",", $poschange);
					$anzahl = $a[4];
					$_posstore = Array ();

					for ($j = 1; $j <= $anzahl; $j ++)
					{
						$tpos = $_poschange[$j] - 1;
						$_posstore[] = $_posstart[$tpos];

						$fname = $myCO->formid."_".$a[2]."_".$j;
						$myCO->set($a[2]."_".$j, $myRequest->get($fname));
						$this->fullsearch .= $myRequest->get($fname)." | ";
					}
					if ($a[5]==1)
					{
						// Umsortieren
						$_valtemp = Array();
						for ($j = 1; $j <= $anzahl; $j ++)
						{
							$_valtemp[$j] = $myCO->get($a[2]."_".$_posstore[$j-1]);
						}
						$_posstore = Array();
						for ($j = 1; $j <= $anzahl; $j ++)
						{
							$_posstore[] = $j;
							$myCO->set($a[2]."_".$j,$_valtemp[$j]);
						}
					}

					$myCO->set($a[2]."_pos", $_posstore);
					break;

				}

			}
		}
	}


	function fetch_form_user($_params)
	{
		global $myRequest;
		$fname = $this->formid."_".$_params["property"];
		$val = $myRequest->getI($fname);
		$this->set($_params["property"],$val);
	}

	function setErrorText($s)
	{
		$this->errorText = $s;
	}

	function setInfoText($s)
	{
		$this->infoText = $s;
	}

	function setAlertText($s)
	{
		$this->alertText = $s;
	}

	function getErrorText()
	{
		return $this->errorText;
	}

	function getInfoText()
	{
		return $this->infoText;
	}

	function getAlertText()
	{
		return $this->alertText;
	}

	function changeUserStatus($usr_id, $time = "")
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		if ($time == "")
		{
			$time = time();
		}
		$mySQL->addField("dat_date", $time, DB_NUMBER);
		$mySQL->addField("usr_id", $usr_id, DB_NUMBER);
		$sql = $mySQL->update("content_data", "dat_id=".$this->id);
		$myDB->query($sql);
	}

	function isLoaded()
	{
		return $this->loaded;
	}

	function buttonClicked($button)
	{
		if (isset ($_REQUEST[$button]))
		{
			return true;
		} else
		{
			return false;
		}
	}

	function rawXMLDataImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			$con_id = (int)utf8_decode($_xml->meta->con_id);
			$importmethod = (string)utf8_decode($_xml->meta->importmethod);
			$keepid = (int)utf8_decode($_xml->meta->keepid);

			$dat_id = (int)utf8_decode($_xml->meta->dat_id);

			$sql ="SELECT dat_id, con_id FROM content_data WHERE dat_id=".$dat_id;
			$rs = $myDB->query($sql);

			if (mysql_num_rows($rs)==0 OR $dat_id==0)
			{
				$action ="insert";
			}
			else
			{
				$action="update";
			}

			if ($importmethod=="append")
			{
				$dat_id=0;
				$action ="insert";
			}

			$buildindex = (int)utf8_decode($_xml->meta->buildindex);

			$mySQL = new SQLBuilder();
			$mySQL->addField("con_id",$con_id,DB_NUMBER);

			$dat_uid = (string)utf8_decode($_xml->content->dat_uid);
			$mySQL->addField("dat_uid",$dat_uid);
			$dat_bez = (string)utf8_decode($_xml->content->dat_bez);
			$mySQL->addField("dat_bez",$dat_bez);
			$dat_props = (string)utf8_decode($_xml->content->dat_props);
			$mySQL->addField("dat_props",base64_decode($dat_props));
			$dat_fullsearch = (string)utf8_decode($_xml->content->dat_fullsearch);
			$mySQL->addField("dat_fullsearch",$dat_fullsearch);

			// Default-Properties

			$_default = Array();

			$_default["usr_id_creator"]=2; // User Importer
			$_default["dat_creationdate"]=time();;
			$_default["usr_id"]=2; // User Importer
			$_default["dat_date"]=time();
			$_default["dat_pos"]=0;
			$_default["dat_status"]=1;
			$_default["med_id_thumb"]=7;

			foreach ($_default AS $k => $v)
			{
				$p = (string)utf8_decode($_xml->content->$k);
				if ($p!="")
				{
					$v = (int)$p;
				}
				$mySQL->addField($k,$v,DB_NUMBER);
			}



			if ($action=="insert")
			{
				if ($dat_id!=0)
				{
					$mySQL->addField("dat_id",$dat_id,DB_NUMBER);
				}
				$sql = $mySQL->insert("content_data");
				$myDB->query($sql);
				$dat_id = mysql_insert_id();
			}
			else
			{
				$mySQL->addField("dat_id",$dat_id,DB_NUMBER);
				$sql = $mySQL->update("content_data","dat_id=".$dat_id);
				$myDB->query($sql);
			}

			// Bausteine
			$sql = "DELETE FROM sequence_data WHERE dat_id_content=".$dat_id;
			foreach ($_xml->content->sequence_data->component AS $_xml_component)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("dat_id_content",$dat_id,DB_NUMBER);
				$mySQL->addField("dat_visible",(int)utf8_decode($_xml_component->dat_visible),DB_NUMBER);
				$mySQL->addField("dat_blocknr",(int)utf8_decode($_xml_component->dat_blocknr),DB_NUMBER);
				$mySQL->addField("dat_pos",(int)utf8_decode($_xml_component->dat_pos),DB_NUMBER);
				$mySQL->addField("com_id",(int)utf8_decode($_xml_component->com_id),DB_NUMBER);
				$mySQL->addField("dat_comdata",base64_decode((string)utf8_decode($_xml_component->dat_comdata)));
				$mySQL->addField("dat_fullsearch",(string)utf8_decode($_xml_component->dat_fullsearch));
				$sql= $mySQL->insert("sequence_data");
				$myDB->query($sql);
			}


			if ($buildindex==1)
			{
				$fname = "PhenotypeContent_".$con_id;
				$myCO = new $fname;
				$myCO->load($dat_id);
				$myCO->store();
			}

			return $dat_id;
		}
		else
		{
			return (false);
		}
	}


	function rawXMLDataExport($importmethod="overwrite")
	{

		global $myPT;
		global $myDB;

		$sql ="SELECT * FROM content_data WHERE dat_id=".$this->id;
		$rs =$myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
		<con_id>'.$this->content_type.'</con_id>
		<dat_id>'.$this->id.'</dat_id>
		<dat_id_local>'.$this->id.'</dat_id_local>
		<importmethod>'.$myPT->codeX($importmethod).'</importmethod>
		<buildindex>1</buildindex>		
	</meta>
	<content>
	';
		$_felder = Array("dat_uid","dat_bez","usr_id_creator","dat_creationdate","usr_id","dat_date","dat_pos","dat_status","med_id_thumb","dat_fullsearch");
		foreach ($_felder AS $k)
		{
			$xml.= '<'.$k.'>'.$myPT->codeX($row[$k]).'</'.$k.'>'."\n";
		}

		$xml.='
		<dat_props>'.base64_encode($row["dat_props"]).'</dat_props>
		<sequence_data>';

		$sql = "SELECT * FROM sequence_data WHERE dat_id_content=". $this->id. " AND dat_editbuffer=0 ORDER BY dat_blocknr , dat_pos";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
			<component>
				<com_id>'.$myPT->codeX($row["com_id"]).'</com_id>
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
</phenotype>';		
		return ($xml);
	}



	function rawXMLExport($content_type=-1)
	{
		global $myDB;
		global $myPT;

		if ($content_type==-1)
		{
			$conten_type = $this->content_type;
		}

		$sql = 'SELECT * FROM content WHERE con_id='. $content_type;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);


		$file= APPPATH ."content/PhenotypeContent_"  .$content_type . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
		<con_id>'.$myPT->codeX($row['con_id']).'</con_id>
		<con_bez>'.$myPT->codeX($row['con_bez']).'</con_bez>		
		<con_rubrik>'.$myPT->codeX($row['con_rubrik']).'</con_rubrik>
		<con_description>'.$myPT->codeX($row['con_description']).'</con_description>
		<con_anlegen>'.$myPT->codeX($row['con_anlegen']).'</con_anlegen>
		<con_bearbeiten>'.$myPT->codeX($row['con_bearbeiten']).'</con_bearbeiten>
		<con_loeschen>'.$myPT->codeX($row['con_loeschen']).'</con_loeschen>
	</meta>
	<script>'.$myPT->codeX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM content_template WHERE con_id = ' 	. $content_type . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_CONTENTCLASS, $content_type, $row["tpl_id"]);
			$buffer = @file_get_contents($file);
			$xml .= '<template access="'.$myPT->codeX($row['tpl_bez']).'">'.$myPT->codeX($buffer).'</template>'."\n";
		}


		$xml.='   	</templates>
</phenotype>';

		return $xml;
	}

	function rawXMLImport($buffer)
	{
		global $myDB;
		global $myPT;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			$con_id = (int)utf8_decode($_xml->meta->con_id);

			// Zunächst evtl. vorhanden alte Templates löschen

			$sql = "SELECT * FROM content_template WHERE con_id = " . $con_id . " ORDER BY tpl_id";
			$rs = $myDB->query($sql);
			while ($row_ttp=mysql_fetch_array($rs))
			{
				$dateiname = $myPT->getTemplateFileName(PT_CFG_CONTENTCLASS, $con_id, $row_ttp["tpl_id"]);
				@unlink($dateiname);
			}
			$sql = "DELETE FROM content_template WHERE con_id = " . $con_id;
			$myDB->query($sql);

			// Jetzt die eigentliche Klasse
			$dateiname = APPPATH . "includes/PhenotypeContent_"  .$con_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM content WHERE con_id = " . $con_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("con_id",$con_id,DB_NUMBER);
			$con_bez = (string)utf8_decode($_xml->meta->con_bez);
			$mySQL->addField("con_bez",$con_bez);
			$con_description = (string)utf8_decode($_xml->meta->con_description);
			$mySQL->addField("con_description",$con_description);
			$con_rubrik = (string)utf8_decode($_xml->meta->con_rubrik);
			$mySQL->addField("con_rubrik",$con_rubrik);

			$con_usage = (int)utf8_decode($_xml->meta->con_anlegen);
			$mySQL->addField("con_anlegen",$con_usage);

			$con_usage = (int)utf8_decode($_xml->meta->con_bearbeiten);
			$mySQL->addField("con_bearbeiten",$con_usage);

			$con_usage = (int)utf8_decode($_xml->meta->con_loeschen);
			$mySQL->addField("con_loeschen",$con_usage);


			$sql = $mySQL->insert("content");
			$myDB->query($sql);


			$script = (string)utf8_decode($_xml->script);

			$file = APPPATH . "content/PhenotypeContent_"  .$con_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			// Templates anlegen

			$tpl_id = 1;
			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)utf8_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
				$mySQL->addField("tpl_id",$tpl_id,DB_NUMBER);
				$mySQL->addField("con_id",$con_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("content_template");
				$myDB->query($sql);
				$html = (string)utf8_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_CONTENTCLASS, $con_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
				$tpl_id++;
			}

			// war nur ein Test, wird umgebaut ...
			//$fname = "PhenotypeContent_".$con_id;
			//$myCO = new $fname;
			//$myCO->snapshot(3,"config");


			return $con_id;

		}
		else
		{
			return (false);
		}
	}


	/**
	 * writes an actual snapshot of the contentobject data or configuration into the snapshot table and
	 * returns the snapshot-xml
	 *
	 * @param int $usr_id
	 * @param string $mode
	 * @return string
	 */

	function snapshot($usr_id=1,$mode="data")
	{
		if ($mode=="data")
		{
			return $this->snapshotData($usr_id);
		}
		else // config
		{
			return $this->snapshotConfiguration($usr_id);
		}
	}

	/**
	 * writes an actual snapshot of the contentobject data into the snapshot table and
	 * returns the snapshot-xml
	 *
	 * @param int $usr_id
	 * @return string
	 */

	function snapshotData($usr_id)
	{
		global $myDB;
		global $myLog;
		$xml = $this->rawXMLDataExport();

		$mySQL = new SQLBuilder();
		$mySQL->addField("sna_type","CO");
		$mySQL->addField("key_id",$this->id,DB_NUMBER);
		$mySQL->addField("sec_id",$this->content_type,DB_NUMBER);
		$mySQL->addField("sna_date",time(),DB_NUMBER);
		$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
		$mySQL->addField("sna_zip",0,DB_NUMBER);
		// too many problems with gzcompress, maybe in a later version
		//$mySQL->addField("sna_xml",gzcompress($xml));
		$mySQL->addField("sna_xml",$xml);
		$mySQL->addField("sna_sync",0,DB_NUMBER);
		$sql = $mySQL->insert("snapshot");
		$myDB->query($sql);


		$myLog->log("Snapshot " . mysql_insert_id() . " zu Datensatz " . $this->id . " (Content-Type " . $this->content_type .") erstellt.",PT_LOGFACILITY_SYS);

		return $xml;
	}


	/**
	 * writes an actual snapshot of the contentclass configuration into the snapshot table and
	 * returns the snapshot-xml
	 *
	 * @param int $usr_id
	 * @return string
	 */

	function snapshotConfiguration($usr_id)
	{
		global $myDB;
		$xml = $this->rawXMLExport();

		// erst ab 2.5 aktiv
		/*
		$mySQL = new SQLBuilder();
		$mySQL->addField("sna_date",time(),DB_NUMBER);
		$mySQL->addField("con_id",$this->content_type,DB_NUMBER);
		$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
		$mySQL->addField("sna_xml",$xml);
		$sql = $mySQL->insert("snapshot_content");
		$myDB->query($sql);
		*/
		return $xml;
	}


	function getRSSHeader($mode)
	{
		// check details at http://www.rssboard.org/rss-2-0

		$_rss = Array(
		"title"=>"Phenotype Contentfeed for " . $this->bez,
		"link"=>"http://www.phenotype.de",
		"description"=>"This feed lists all recently changed items of content-type " .$this->content_type . " - " .$this->bez,
		"pubDate"=>date("r")
		);

		return ($_rss);
	}

	function getRSSItem($mode)
	{

		$_rss = Array(
		"title"=>$this->id . " - " . $this->get("bez"),
		"link"=>ADMINFULLURL . "backend.php?page=Editor,Content,edit&id=".$this->id."&uid=".$this->uid,
		"description"=>"",
		"author"=>"", // nobody@phenotype.de
		"date"=> $this->date
		);

		return ($_rss);
	}

	function getRSSHeaderImage($mode)
	{
		$_rss = Array(
		"url"=>ADMINFULLURL ."img/rsslogo.gif",
		"title"=>"Phenotype RSS Feed",
		"link"=>"http://www.phenotype.de"
		);

		return ($_rss);
	}

	function displayRSS($mode,$items=25,$days=7)
	{
		global $myPT;

		$xml= '<?xml version="1.0" encoding="ISO-8859-1"?>
		<rss version="2.0">
  			<channel>';

		$_rss = $this->getRSSHeader($mode);
		foreach ($_rss AS $k => $v)
		{
			$xml .='<'.$k.'>'.$myPT->codeX($v) .'</'.$k.'>'."\n";
		}


		$_rss=$this->getRSSHeaderImage($mode);

		if (is_array($_rss))
		{
			$xml .="<image>";
			foreach ($_rss AS $k => $v)
			{
				$xml .='<'.$k.'>'.$myPT->codeX($v) .'</'.$k.'>'."\n";
			}
			$xml .="</image>";
		}

		$xml .=$this->renderRSSItems($mode,$items,$days);

		$xml .="</channel></rss>";
		echo $xml;
	}



	function renderRSSItems($mode,$items,$days)
	{
		global $myDB;
		$sql = "SELECT * FROM content_data WHERE con_id = " .$this->content_type . " AND dat_status = 1 ORDER BY dat_date DESC";

		$rs = $myDB->query($sql);
		$xml ="";
		$cname ="PhenotypeContent_".$this->content_type;
		$myCO = new $cname;
		while ($row=mysql_fetch_array($rs))
		{
			$myCO->init($row);
			$xml .= $myCO->renderRSSItem($mode);
		}
		return ($xml);
	}

	function renderRSSItem($mode)
	{
		global $myPT;

		$_rss = $this->getRSSItem($mode);

		if ($link=="")
		{
			$link =ADMINFULLURL . "backend.php?page=Editor,Content,edit&id=".$this->id."&uid=".$this->uid;
		}
		$xml ='<item>
	  <pubDate>'.date("r",$_rss["date"]).'</pubDate>	
      <title>'.$myPT->codeX($_rss["title"]).'</title>
      <description>'.$myPT->codeX($_rss["description"]).'</description>
      <link>'.$myPT->codeX($_rss["link"]).'</link>
      <author>'.$myPT->codeX($_rss["author"]).'</author>
    </item>';
		return $xml;
	}

	// ToDO: Suchmethode, die den term nach und/oder segmentiert und ein Query auf den
	// Volltextindex absetzt (analog EVOI-Include 19)

	/**
	 * Enter description here...
	 *
	 * @return recordset
	 */
	function search($term,$limit)
	{

		return $rs;
	}


	function execute_form_ajax($token,$step)
	{
		$nextstep="stop";
		echo "TOKEN:". $token . " STEP:".$step;
		sleep(1);
		return $nextstep;
	}


	function getAjaxJSLink($token,$step)
	{
		return ("ajax_".$token."_doit('".$step."');");
	}


	/**
   * retrieves URL of a page using the DAO cache
   * 
   * You should not overwerite this method. If you want to change URL behaviour
   * stick to buildURL instead.
   *
   * @param integer $lng_id
   * @return string
   */

	public function getURL($action="show",$lng_id=null)
	{
		return url_for_co($this,$action,$lng_id,array());

		//return $this->buildURL($action,$lng_id);
	}

	public function getHUrl($action="show",$lng_id=null)
	{
		global $myPT;
		return $myPT->codeH($this->getUrl($action,$lng_id));
	}

	public function buildURL($action="show",$lng_id=null)
	{
		return "undefined";
	}

	public function __call($methodname,$params)
	{
		throw new Exception("There's no method ".$methodname."() in PhenotypeContent_".sprintf('%02d',$this->id) .".");
	}

	/**
  * preview method render the preview properties from the content object
  *
  * @param integer $block_nr number of selected tab while editing the content record
   * @return string return the preview HTML code
  */
	public function preview($block_nr)
	{
		$this->printProperties();

	}

	/**
  * preview method render the preview properties from the content object
  *
   * @return boolean return the database col dat_altered (1=true / 0=false)
  */
	public function isAltered()
	{
		return (boolean) $this->row["dat_altered"];

	}
}
?>
