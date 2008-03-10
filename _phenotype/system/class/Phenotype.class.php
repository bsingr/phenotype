<?php
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



/**
 * @package phenotype
 * @subpackage system
 *
 */
class Phenotype
{
  public $version  = "##!PT_VERSION!##";
  public $subversion = "##!BUILD_NO!##";


  /**
   * indicates if PHP warnings are to be caught
   * 
   * (only relevant if PT_DEBUG ==1)
   *
   * @var unknown_type
   */
  public $phpwarnings = true;

  private $_preferences = false;

  /**
	* @var array $aPreferences This array implements the Preferences XML-file 
	* added 07/08/23 by Dominique B�s
	*/
  private $aPreferences = false;

  private $_phrases = Array();

  function __construct()
  {
    if (ini_get("register_globals")==1)
    {
      die("Bitte stellen Sie Register Globals aus !! Aus Sicherheitsgr�nden l�uft Phenotype nicht mit dieser Einstellung");
    }
    $this->startBuffer();
  }


  public function executeFrontend()
  {
    global $myPage;
    global $myTC;

    // Time check initialize
    $myTC = new TCheck();
    $myTC->start();

    if (PT_FRONTENDSESSION==1)
    {
      ini_set ("session.use_trans_sid",1);
      session_start();
    }

    global $myRequest;

    // retrieve page id out of request

    $pag_id = $myRequest->getI("id");

    if ($pag_id==0)
    {
      $pag_id = PAG_ID_STARTPAGE;
      $myRequest->set("id",$pag_id);
    }

    // initialize page

    $myPage = new PhenotypePage($pag_id);

    // get language, initalize language

    $lng_id = $myRequest->getI("lng_id");
    $myPage->switchLanguage($lng_id);

    $cache=PT_PAGECACHE;

    if ($myRequest->check("cache"))
    {
      $cache = $myRequest->getI("cache");
    }
    $myPage->display($cache);
    return $myPage;
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


  public function getFilenameOutOfPath($s)
  {
    $p = strrpos($s,'\\');
    return substr($s,$p+1);
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

  // Funktion zur R�ckgabe des Klassen-Namens
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
    // au�erdem werden gematcht: "&'/<> (" -> #x22 in Hex Schreibweise)
    $pat = "/[\x7F-\x9F&<>\/'\\x22\\x00]/";
    $s =  preg_replace_callback($pat,"match2Entity",$s);

    return $s;


    // alter Ansatz: kann entfernt werden, wenn keine Probleme auftauchen

    // Kodiert alle Zeichen zwischen ASCII 1 und 31 sowie 127 und 159
    // au�erdem werden gematcht: &'"<>

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

  /**
	* Build Preferences Array
	* added 07/08/23 by Dominique B�s
	* 
	* @return null
	*/
  public function gBuildPreferencesArray() {
    if($this->aPreferences == false) {
      $sXML = simplexml_load_file(APPPATH ."preferences.xml");
      $this->aPreferences = $this->gaXMLToArray($sXML);
    }
  }

  /**
	* Get Preferences Array
	* added 07/08/23 by Dominique B�s
	* 
	* @param string $sSearchKey String width array keys, separator is "." Example: "preferences.section_cache"
	* @return null
	*/
  public function gaGetPreferencesArray($sSearchKey="") {
    $oReturn = "";
    $bFoundKey = false;

    if($this->aPreferences == false) {
      $this->gBuildPreferencesArray();
    }

    if($sSearchKey != "") {
      $aArrayKeys = explode(".", $sSearchKey);
      $aTemp = $this->aPreferences;
      foreach($aArrayKeys as $sKey => $sValue) {
        $aTemp = $aTemp[$sValue];
      }

      //Check if is not empty
      if(!empty($aTemp)) {
        $bFoundKey = true;
        $oReturn = $aTemp;
      }
    }

    if($bFoundKey == false) {
      $oReturn = $this->aPreferences;
    }

    return $oReturn;
  }

  /**
	* Write XML childs into an array
	* added 07/08/23 by Dominique B�s
	* 
	* @param string $sXML XML-file
	* @return null
	*/
  public function gaXMLToArray($sXML) {
    $oReturn = "";
    foreach($sXML as $sKey=>$sValue)
    {
      //$k = ($parent == "") ? (string)$sKey : $parent . "." . (string)$sKey;

      $oTempValue = $this->gaXMLToArray($sValue);
      if($oTempValue == "") {
        $oReturn[$sKey] = (string)$sValue;
      } else {
        $sKeyName = $sKey;
        $aAttributes = array();

        //Show for attributes
        foreach($sValue->attributes() as $sKey2 => $sValue2) {
          //Check the attribute "name"
          if($sKey2 == "name") {
            $sKeyName .= "_" . $sValue2;
          } else {
            $aAttributes[$sKey2] = $sValue2;
          }

        }
        if(count($aAttributes) > 0) {
          $oReturn[$sKeyName]["aAttributes"] = $aAttributes;
        }
        $oReturn[$sKeyName] = $oTempValue;
      }
    }
    return $oReturn;
  }


  public function colorcode ($html)
  {
    if (trim($html)=="")
    {
      return "";
    }

    $html = "<?php _CHEAT_SHOW_SOURCE_" . $html;
    $html = str_replace(chr(92),"_CHR_ASCII_92_",$html);
    $this->startBuffer();
    highlight_string($html);
    $html = $this->stopBuffer();
    $html = str_replace('_CHR_ASCII_92_',chr(92),$html);
    $html = str_replace('&lt;?php&nbsp;_CHEAT_SHOW_SOURCE_',"",$html);
    $html = str_replace('&lt;?php _CHEAT_SHOW_SOURCE_',"",$html);
    return $html;
  }

  /**
   * Enter description here...
   *
   */
  public function handleError($errno, $errstr, $errfile, $errline)
  {
    // currently only E_WARNING
    // maybe more functionality (like collection for debug console)
    // for E_NOTICE AND E_STRICT in future

    if ($errno==E_WARNING)
    {
      // this method is executed via error and/or exception handler
      // we are not in the phenotype class object context und therefore must use the global object
      global $myPT;
      if (is_object($myPT) AND $myPT->phpwarnings == true )
      {
        $myPT->displayErrorPage("PHP Warning",$errstr,$errfile,$errline);
        exit();
      }
    }
    return;
  }


  public function suppressPHPWarnings()
  {
    $this->phpwarnings = false;
  }

  public function respectPHPWarnings()
  {
    $this->phpwarnings = true;
  }


  /**
   * Enter description here...
   *
   * @param unknown_type $e
   */
  public function handleException($e)
  {

    /*
    final function getMessage();                // Mitteilung der Ausnahme
    final function getCode();                   // Code der Ausnahme
    final function getFile();                   // Quelldateiname
    final function getLine();                   // Quelldateizeile
    final function getTrace();                  // Array mit Ablaufverfolgung
    final function getTraceAsString();  */

    // this method is executed via error and/or exception handler
    // we are not in the phenotype class object context und therefore must use the global object
    global $myPT;
    $myPT->displayErrorPage("PHP Exception",$e->getMessage(),$e->getFile(),$e->getLine());

  }

  public function displayErrorPage($headline,$message,$file="",$line=0,$sql="")
  {
    global $myRequest;
    global $myDB;


    // first get the current output
    $html = $this->stopBuffer();
    // stop output buffering again to color code the fetched buffer
    $html = $this->colorcode($html);

    // get source code
    if ($file!="")
    {
      $_lines = file ($file);
      $c = count($_lines);

      $start = max(1,$line-8);
      $stop = min($c,$line+7);
    }

    ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title><?=$this->codeH($headline)?></title>
  <meta name="generator" content="Phenotype CMS" />
  <style type="text/css">
  body 
  {
    background-color: #fff;
    font-family:Verdana,Arial;
    font-size:12px;
  }
  em
  {
    font-family:Verdana,Arial;
    font-size:12px;
    font-style:normal;
    font-variant:small-caps;
    border-bottom:1px solid #CFCFCF;
    padding: 0px 1px 0px 1px;
    line-height:40px;
    letter-spacing:4px;

  }
  #main
  {
  
    background:#F7F7F7 none repeat scroll 0%;
    border-bottom:1px solid #CFCFCF;
    border-top:1px solid #CFCFCF;

    width: 780px;
    padding:10px;
    
    margin-left:auto;
    margin-right:auto;

  }
  #logo
  {
    background-image: url ('/img/logo.png');
    width: 780px;
    padding:10px;
    margin-left:auto;
    margin-right:auto;
    height:50px;
    text-align:right;
    
  }
  #footer
  {
    font-size:10px;
    width: 780px;
    padding:10px;
    margin-left:auto;
    margin-right:auto;
    text-align:right;
    height:50px;
  }
  #header
  {
    color:#000;

  }
  #message
  {
    color: #f00;
    font-weight: bold;
    background-color: #fff;
    padding: 7px;
    #margin: 5px;
    margin-top: 10px;
    margin-bottom: 0px;
  }

  .request
  {
    font-family:Courier;  
    list-style:none;
    font-size:11px;
    background-color: #fff;
    padding: 7px;
    #margin: 5px;
    margin-top: 0px;
    margin-bottom: 20px;
    overflow:auto;
  }
  
  .param_key
  {
  display:block;
  width:80px;
  float:left;
  }
  .param_value
  {
  color: #cfcfcf;
  }
  
  .filename
  {
    font-size:9px;
  color: #cfcfcf;
  padding: 2px;
  line-height: 18px;

  }
  .exec_context
  {
  background-color: #cfcfcf;
  font-size:9px;
  color: #fff;
  padding: 2px 5px 5px 10px;
  margin: 0px;
  line-height: 18px;
  }  
  .source
  {
    font-family:Courier;  
    list-style:none;
    font-size:11px;
    background-color: #fff;
    padding: 7px;
    #margin: 5px;
    margin-top: 0px;
    margin-bottom: 20px;
    overflow:auto;
  }
  .current
  {
  background-color: #CFCFCF;
  }  
  .query
  {
  color: #cfcfcf;
  }
  #output 
  {
    font-family:Courier;
    font-size:11px;
    height: 300px;
    width: auto;
    overflow: auto;
    border: 1px solid #CFCFCF;
    background-color: #fff;
    padding: 8px;
    margin-bottom: 10px;
  }
  </style> 
</head>
<body>
<div id="logo"><img src="<?php echo ADMINFULLURL ?>img/phenotypelogo.gif" alt="Phenotype"/></div>
<div id="main">
<div id="header">
<strong><?=$this->codeH($headline)?></strong>
<div id="message"><?php echo $this->codeH($message)?></div>
</div>
<em>Request:</em><br/>
<div id="request">
<ul class="request">
<?php foreach ($myRequest->getParamsArray() AS $k => $v){?>
<li><span class="param_key">#<?php echo $this->codeH($k)?></span>: <span class="param_value"><?php echo $this->codeH($v)?></span></li>
<?php }?>
</ul>
</div>
<?php if ($file!=""){?>
<div id="details">
<em>Source:</em><br/>
<span class="filename">[<?php echo $this->codeH($this->getFilenameOutOfPath($file))?>]</span>
<ul class="source">
<?php for ($i=$start;$i<=$stop;$i++){?>
<li <?php if ($i==$line){?>class="current"<?php }?>><span>#<?php echo sprintf('%04d',$i)?>: </span><?php echo $this->colorcode($_lines[$i-1])?></li>
<?php }?>
</ul>
</div>
<?php }?>
<?php if ($html!=""){?>
<em>Output:</em><br/>
<div id="output">
<?php echo $html?></div>
<?php }?>
<em>Backtrace:</em><br/>
<div id="traces">
<?php
$_traces =	debug_backtrace();
// remove the first entry of the backtrace (i.e. this method)
array_shift($_traces);
foreach ($_traces AS $_trace)
{
  $_lines = file ($_trace["file"]);
  $line = $_trace["line"];
  $c = count($_lines);

  $start = max(1,$line-2);
  $stop = min($c,$line+2);

  $type = $_trace["type"];
  $_args = array();
  foreach ($_trace["args"] AS $k=>$v)
  {
    if (is_numeric($v))
    {
      $_args[]=$v;
    }
    else
    {
      if (is_object($v))
      {

        $_args[]=get_class($v);
      }
      else
      {
        $_args[]='"'.$v.'"';
      }
    }
  }
  $args = implode($_args,",");
  switch ($type)
  {
    case "->";
    $context = $_trace["class"]."->".$_trace["function"]." (".$args.")";
    break;
    case "::";
    $context = $_trace["class"]."->".$_trace["function"]." (".$args.")";
    break;
    case "":
    //ToDO: Check next line! only copy & paste
    $context = $_trace["function"]." (".$args.")";
    break;
  }
?>
<span class="exec_context"><?php echo $this->codeH($context)?></span><span class="filename">[<?php echo $this->getFilenameOutOfPath($_trace["file"])?>]</span>
<ul class="source">
<?php for ($i=$start;$i<=$stop;$i++){?>
<li <?php if ($i==$line){?>class="current"<?php }?>><span>#<?php echo sprintf('%04d',$i)?>: </span><?php echo $this->colorcode($_lines[$i-1])?></li>
<?php }?>
</ul>
<?}?>
</div>
<?php
$_sql = $myDB->getQueries();
$stop = count ($_sql);
$start = max(1,$stop-8);
if ($stop!=0){?>
<div id="database">
<em>SQL Backlog</em>
<ul class="source">
<?php
for ($i=$stop;$i>=$start;$i--){?>
<li><span>#<?php echo sprintf('%04d',$i)?>: </span><span class="query"><?php echo $this->codeH($_sql[$i-1])?></span></li>
<?php }?>
</ul>
</div>
<?php }?>
</div>
<div id="footer">
<?php echo date('d.m.Y H:i');?>
</div>
</body>
</html>
    <?
    exit();
  }

}


?>
