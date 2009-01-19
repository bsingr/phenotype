<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr‰mer.
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
class PhenotypeBase
{

	public $charset="";


	public $_props = Array ();
	private $changed = false;

	public function __construct()
	{
		$this->charset = PT_CHARSET;
	}
	
	public function check($k)
	{
		if(array_key_exists($k,$this->_props)){return true;}
		return false;
	}


	public function set($property, $value)
	{
		$this->_props[$property] = $value;
		$this->changed = true;
	}


	public function clear($property)
	{
		if ($this->check($property))
		{
			unset ($this->_props[$property]);
		}
	}




	public function get($property,$default=null)
	{
		if ($default == null OR $this->check($property))
		{
			return ($this->_props[$property]);
		}
		else
		{
			return $default;
		}
	}

	public function getI($property,$default=null,$min=null,$max=null)
	{
		$int = (int) ($this->get($property,$default));
		if ($min!=null)
		{
			if ($int<$min)
			{
				$int=$min;
			}
		}
		if ($max!=null)
		{
			if ($int>$max)
			{
				$int=$max;
			}
		}
		return ($int);
	}

	public function getD($property, $decimals,$default)
	{
		return sprintf("%01.".$decimals."f", ($this->get($property,$default)));
	}





	public function getHTML($property,$default=null)
	{
		return @ htmlentities(($this->get($property,$default)),null,$this->charset);
	}

	public function getH($property,$default=null)
	{
		return $this->getHTML($property,$default);
	}

	public function getHBR($property,$default=null)
	{
		$html = nl2br($this->getHTML($property,$default));
		// Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
		$html = str_replace(chr(10), "", $html);
		$html = str_replace(chr(13), "", $html);
		return ($html);
	}


	public function getURL($property,$default=null)
	{
		return @ urlencode($this->get($property,$default));
	}

	
	public function getSQL($property,$default=null)
	{
		return $this->codeSQL($this->get($property,$default));
	}
	
	public function getX($property,$default=null)
	{
		global $myPT;
		return ($this->codeX(($this->get($property,$default))));
	}

	
	public function getA($property,$allowedchars=PT_ALPHA,$default)
	{
		$val = $this->get($property);
		$patterns = "/[^".$allowedchars."]*/";
		return preg_replace($patterns, "", $val);
	}
	
	
	public	function getQ($property)
	{
		throw new Exception("Deprecated call of function getQ");
	}

	public function getU($property)
	{
		throw new Exception("Deprecated call of function getU");
		return @ utf8_encode($this->_props[$property]);
	}


	public function getS($property)
	{
		throw new Exception("Deprecated call of function getS");
		//return (string) @ addslashes($this->_props[$property]);
	}







	public function codeX($s,$utf8=false)
	{
		return ($this->xmlencode($s,$utf8));
	}


	private function xmlencode($s,$utf8=false)
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

	public function urlencode($url)
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

	public function codeH($s)
	{
		return @ htmlentities($s,null,$this->charset);
	}

	public function codeHBR($s)
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
	public function codeHKT($s) // HTML KEEP TAGS
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
	public function codeI($s)
	{
		return (int)$s;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $s
	 * @return unknown
	 */
	public function codeSQL($s)
	{
		return mysql_real_escape_string($s);
	}
	
	public function isValidProperty($property)
	{
		
		$this->setNoValidationError();

		if (!$this->check($property))
		{
			$this->setValidationError('1','property not set');
			return false;
		}
		return true;
	}
	
	
	public function setValidationError($number,$string)
	{
		global $myPT;
		$myPT-> setValidationError($number,$string);
	}
		

	public function getValidationError()
	{
		global $myPT;
		return $myPT->getValidationError();
	}
	
	public function setNoValidationError()
	{
		global $myPT;
		$myPT->setNoValidationError();
	}
	
	public function isValidInteger($property,$strict=false,$min=null,$max=null)
	{
		global $myPT;
		if ($strict AND !$this->isValidProperty($property))
		{
			return false;
		}
		return $myPT->isValidInteger($this->get($property),$min,$max);
	}
	
	public function isValidSelection($property,$_options,$strict=false)
	{
		global $myPT;
		if ($strict AND !$this->isValidProperty($property))
		{
			return false;
		}
		return $myPT->isValidSelection($this->get($property),$_options);
	}	
	
	public function isValidString($property,$allowedchars=PT_ALPHA,$strict=false)
	{
		global $myPT;
		if ($strict AND !$this->isValidProperty($property))
		{
			return false;
		}
		return $myPT->isValidString($this->get($property),$allowedchars);
	}	
	
	public function isValidEmail($property,$strict=false)
	{
		global $myPT;
		if ($strict AND !$this->isValidProperty($property))
		{
			return false;
		}
		return $myPT->isValidEmail($this->get($property));
	}	
}
?>