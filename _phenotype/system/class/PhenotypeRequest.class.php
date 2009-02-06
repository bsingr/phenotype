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
class PhenotypeRequestStandard extends PhenotypeBase
{

	public $code404 = 0;

	private $_REQUEST = array();
	private $shifted = false;

	public function __construct()
	{
		$this->_REQUEST = $_REQUEST;
		$this->analyzeRequest();
	}

	public function analyzeRequest()
	{
		global $myDB;
		global $myApp;

		// Clear all set/get values
		$this->_props=Array();

		if (ini_get("magic_quotes_gpc")==1)
		{
			foreach ($this->_REQUEST AS $k=>$v)
			{
				$this->set($k,stripslashes($v));
			}
		}
		else
		{
			foreach ($this->_REQUEST AS $k=>$v)
			{
				$this->set($k,$v);
			}
		}

		// check for smartURL hit
		if (defined("PT_FRONTEND"))
		{
			if ($this->check("smartURL") AND !$this->check("id"))
			{
				$smartURL = $this->get("smartURL");

				// / am Anfang wegfiltern
				$patterns = "/^[\/]*/";
				$smartURL = preg_replace($patterns,"", $smartURL);
				// / am Ende wegfiltern
				$patterns = "/[\/]\$/";
				$smartURL = preg_replace($patterns,"", $smartURL);

				$sql = "SELECT pag_id, pag_url1, pag_url2, pag_url3, pag_url4 FROM page WHERE pag_url='". mysql_escape_string($smartURL)."' OR pag_url1='". mysql_escape_string($smartURL)."' OR pag_url2='". mysql_escape_string($smartURL)."' OR pag_url3='". mysql_escape_string($smartURL)."' OR pag_url4='". mysql_escape_string($smartURL)."'";
				$rs = $myDB->query($sql,"Request");
				if (mysql_num_rows($rs)==1)
				{
					$row = mysql_fetch_array($rs);
					$lng_id=1;
					for ($i=1;$i<=4;$i++)
					{
						if ($row["pag_url".$i]==$smartURL)
						{
							$lng_id=$i;
							break;
						}
					}
					$this->set("id",$row["pag_id"]);
					$this->set("lng_id",$lng_id);
				}
				else
				{
					// check for param rewrite


					$sql = "SELECT pag_id, LENGTH(pag_url1) AS L ,1 AS lng_id FROM page WHERE pag_url1 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url1,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url2) AS L ,2 AS lng_id FROM page WHERE pag_url2 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url2,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url3) AS L ,3 AS lng_id FROM page WHERE pag_url3 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url3,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url4) AS L ,4 AS lng_id FROM page WHERE pag_url4 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url4,'/'))=1";
					$sql .= " ORDER BY L DESC, lng_id ASC LIMIT 0,1";

					$rs = $myDB->query($sql);
					if (mysql_num_rows($rs)==1)
					{
						$row =mysql_fetch_array($rs);
						$this->set("id",$row["pag_id"]);
						$this->set("lng_id",$row["lng_id"]);

						$params = substr($smartURL,$row["L"]+1);
						$this->set("smartURLParams", $params);
						$this->set("smartPATH", substr($smartURL,0,$row["L"]));

						$_params = split('/',$params);
						$i=0;$n=0;
						foreach ($_params AS $v)
						{
							$n++;
							$this->set("smartParam".$n,$v);
							switch ($i)
							{
								case 0:
									$i=1;
									$key = $v;
									break;
								case 1:
									$this->set($key,$v);
									$i=0;
									break;
							}
						}
						if ($i==1)
						{
							$this->set("smartUID",$key);
							if ($key=="action")
							{
								$this->set("action","index");
							}
						}
					}
					else
					{
						// no unique hit
						global $myTC;
						$myTC->stop();
						$this->code404=1;
					}
				}
			}
		}
	}



	/**
   * This method is called, wenn Include Actions are enabled and action is selected on smartParam1
   * 
   * @TODO: Reduce redundant database queries produced by this method
   *
   * @param string $action
   */
	public function shiftParams4Action($action)
	{
		if (!$this->shifted)
		{
			$smartURL = $this->get("smartPATH")."/action/".$this->get("smartURLParams");
			$this->_REQUEST["smartURL"]=$smartURL;
			$this->analyzeRequest();
			$this->shifted=true;
		}
	}





	function printR()
	{
		echo "<pre>";
		print_r ($this->_props);
		echo "</pre></br>";
	}

	public function getParamsArray()
	{
		return ($this->_props);
	}

	/**
	 * creates the parameter hash used for the include cache
	 *
	 * @return String	containing all relevant keys and values of the request
	 */
	public function getParamHash ()
	{
		$hash="";
		$_ignore = Array("PHPSESSID","cache","id","smartURL","smartURLParams","smartParam1","smartParam2","smartParam3","smartParam4","smartParam5","smartParam6","smartParam7","smartParam8","smartParam9","smartParam10","smartPATH");
		foreach ($this->_props AS $k => $v)
		{
			if (!in_array($k,$_ignore))
			{
				if (substr($k,0,2)!="__") // Additionally ignore keys starting with "__" It's ver unlinkely to have them on purpose, but not ignoring them might diable the include cache when using Google Analytics
				{
					$hash  .= $k."#".$v."#";
				}
			}
		}
		return $hash;
	}

	public function getReloadUrl()
	{
		//ToDO: could be extended to forward POST parameter
		//currently fitting for most purposes
		return SERVERFULLURL ."reload.php?uri=".base64_encode($_SERVER["REQUEST_URI"]);
	}

	public function isPostRequest()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}

	public function isAjaxRequest()
	{
		return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}

	/**
   * @deprecated 
   *
   * @param unknown_type $k
   * @return unknown
   */
	function getPostParam($k)
	{
		if ($this->checkPost($k))
		{$v=@$_POST[$k];
		return $v;
		}
		return "";
	}

	/**
   * @deprecated 
   *
   * @param unknown_type $k
   * @return unknown
   */
	function checkPost($k)
	{
		if(isset($_POST[$k])){return true;}
		return false;
	}

	public function calcPager($key,$itemsperpage,$maxitem,$_options)
	{
		$_options = Array("first"=>true,"last"=>true,"before"=>2,"after"=>2);
		$nrofpages = ceil($maxitem/$itemsperpage);
		$_pager=Array("page"=>1,"nrofpages"=>$nrofpages);
		if($this->check($key))
		{
			$page = $this->getI($key);
			if ($page<1 OR $page>$nrofpages)
			{
				$page=1;
			}
			$_pager["page"]=$page;
		}
		else
		{
			$page=1;
		}
		$_pages = Array();
		for ($i=1;$i<=$nrofpages;$i++)
		{
			$show=false;
			if ($i==1 AND $_options["first"]==true){$show=true;}
			if ($i==$nrofpages AND $_options["first"]==true){$show=true;}
			if ($i==$page){$show=true;}
			if ($i<$page AND $i>=($page-$_options["before"])){$show=true;}
			if ($i>$page AND $i<=($page+$_options["after"])){$show=true;}


			if ($show)
			{
				$_pages[]=$i;
			}
		}
		$_pager["pages"]=$_pages;
		return $_pager;
	}

}
?>
