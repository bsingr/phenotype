<?php
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

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeBase
{

	public $charset="";


	public $_props = Array ();
	public $changed = false;

	function __construct()
	{
		$this->charset = PT_CHARSET;
	}

	function check($k)
	{
		if(array_key_exists($k,$this->_props)){return true;}
		return false;
	}



	function set($property, $value)
	{
		$this->_props[$property] = $value;
		$this->changed = true;
	}


	function clear($property)
	{
		if ($this->check($property))
		{
			unset ($this->_props[$property]);
		}
	}


	function get($property)
	{
		if ($this->check($property))
		{
			return ($this->_props[$property]);
		}
		else
		{
			return "";
		}
	}

	function getI($property)
	{
		return  (int) ($this->get($property));
	}

	function getD($property, $decimals)
	{
		return sprintf("%01.".$decimals."f", ($this->get($property)));
	}



	function getQ($property)
	{
		throw new Exception("Deprecated call of function getQ");
	}

	function getHTML($property)
	{
		return @ htmlentities(($this->get($property)),null,$this->charset);
	}

	function getH($property)
	{
		return $this->getHTML($property);
	}

	function getHBR($property)
	{
		$html = nl2br($this->getHTML($property));
		// Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
		$html = str_replace(chr(10), "", $html);
		$html = str_replace(chr(13), "", $html);
		return ($html);
	}




	function getU($property)
	{
		throw new Exception("Deprecated call of function getU");
		return @ utf8_encode($this->_props[$property]);
	}


	function getS($property)
	{
		throw new Exception("Deprecated call of function getS");
		//return (string) @ addslashes($this->_props[$property]);
	}


	function getA($property)
	{
		throw new Exception("Deprecated call of function getA");
		//$v = @$this->_props[$property];

		//$patterns = "/[^a-z0-9A-Z]*/";
		//$v = preg_replace($patterns, "", $v);

		return $v;
	}



	function getX($property)
	{
		global $myPT;
		return ($this->codeX(($this->get($property))));
	}





	function codeX($s,$utf8=false)
	{
		return ($this->xmlencode($s,$utf8));
	}


	function xmlencode($s,$utf8=false)
	{

		//The following are the valid XML characters and character ranges (hex values) as defined by the W3C XML
		//language specifications 1.0: #x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF]

		// Eliminiert alle Zeichen zwischen #x01 und #x1F (HEX Schreibweise! in dezimal 01 - 31)
		// Diese Zeichen sind in XML nicht erlaubt, sind ASCII Steuerzeichen
		// Ausnahmen: #x9 | #xA | #xD
		if ($utf8==true)
		{
			// :TODO: currently only temporary solution for tmx file generation, must be optimized
			$pat = "/[\x01-\x08]/";
			$s =  mb_ereg_replace($pat,"",$s);
			$pat = "/[\x0B-\x0C]/";
			$s =  mb_ereg_replace($pat,"",$s);
			$pat = "/[\x0E-\x1F]/";
			$s =  mb_ereg_replace($pat,"",$s);
			$s = mb_ereg_replace("&","&amp;",$s);
			return $s;
		}

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
	}

	function urlencode($url)
	{
		$url = str_replace(" ","-",$url);
		$url = str_replace(array(" ","/","_","&","?","---","--"),"-",$url);
		$url = str_replace("‰","ae",$url);
		$url = str_replace("ˆ","oe",$url);
		$url = str_replace("¸","ue",$url);
		$url = str_replace("ƒ","Ae",$url);
		$url = str_replace("÷","Oe",$url);
		$url = str_replace("‹","Ue",$url);
		$url = str_replace("ﬂ","ss",$url);

		// Alle Sonderzeichen, die nicht URL-typisch sind rausfiltern
		$patterns = "/[^-a-z0-9A-Z_,.\/]*/";
		$url = preg_replace($patterns,"",$url);
		return $url;

	}

	function codeH($s)
	{
		return @ htmlentities($s,null,$this->charset);
	}

	function codeHBR($s)
	{
		$html =  @ nl2br(htmlentities($s,null,$this->charset));
		// Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
		$html = str_replace(chr(10), "", $html);
		$html = str_replace(chr(13), "", $html);
		return ($html);
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
		$s = str_replace("&nbsp;","###NBSP###",$s);
		$s = @ htmlentities($s,null,$this->charset);
		$s = str_replace("###B###","<strong>",$s);
		$s = str_replace("###BB###","</strong>",$s);
		$s = str_replace("###BR###","<br/>",$s);
		$s = str_replace("###NBSP###","&nbsp;",$s);
		return $s;
	}


	/**
   * return a given value encoded as Integer
   *
   * quite useless, but who knows for what reason it will be good one day ;)
   * 
   * @param string value
   * @return integer
   */
	function codeI($s)
	{
		return (int)$s;
	}


	function codeSQL($s)
	{
		return mysql_escape_string($s);
	}
}
?>