<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr?mer.
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
class PhenotypeRequestStandard extends PhenotypeBase
{

	public $code404 = 0;

	private $_REQUEST = array();
	private $shifted = false;

	public function __construct()
	{
		/**
		 * make sure only POST AND GET parameters are considered
		 * just in case the ini_set isn't working
		 * 
		 **/	
		$this->_REQUEST = array_merge($_GET,$_POST);
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
				if (is_array($v))
				{
					$strippedValue = Array();
					foreach ($v as $key=>$rawValue)
					{
						$strippedValue[$key] = stripslashes($rawValue);
					}
					$this->set($k,$strippedValue);
				} else {
					$this->set($k,stripslashes($v));
				}
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
			// check for intrusions
			if (PT_PHPIDS ==1)
			{
				$_excludes = mb_split(',',PT_PHPIDS_EXCLUDES);
				$this->phpIDS($_excludes);
			}
			if ($this->check("smartURL") AND !$this->check("id"))
			{
				$smartURL = $this->get("smartURL");
				$uniqueURL = true;

				// / am Anfang wegfiltern
				$patterns = "/^[\/]*/";
				$smartURL = preg_replace($patterns,"", $smartURL);
				// / am Ende wegfiltern
				$patterns = "/[\/]\$/";
				$smartURL = preg_replace($patterns,"", $smartURL);

				$sql = "SELECT pag_id, pag_url1, pag_url2, pag_url3, pag_url4 FROM page WHERE pag_url='". mysql_real_escape_string($smartURL)."' OR pag_url1='". mysql_real_escape_string($smartURL)."' OR pag_url2='". mysql_real_escape_string($smartURL)."' OR pag_url3='". mysql_real_escape_string($smartURL)."' OR pag_url4='". mysql_real_escape_string($smartURL)."'";
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
					
					if (mysql_num_rows($rs)>1)
					{
						$uniqueURL=false;
					}
					// check for param rewrite


					$sql = "SELECT pag_id, LENGTH(pag_url1) AS L ,1 AS lng_id FROM page WHERE pag_url1 !='' AND INSTR('".mysql_real_escape_string($smartURL)."',CONCAT(pag_url1,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url2) AS L ,2 AS lng_id FROM page WHERE pag_url2 !='' AND INSTR('".mysql_real_escape_string($smartURL)."',CONCAT(pag_url2,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url3) AS L ,3 AS lng_id FROM page WHERE pag_url3 !='' AND INSTR('".mysql_real_escape_string($smartURL)."',CONCAT(pag_url3,'/'))=1";
					$sql .= " UNION SELECT pag_id, LENGTH(pag_url4) AS L ,4 AS lng_id FROM page WHERE pag_url4 !='' AND INSTR('".mysql_real_escape_string($smartURL)."',CONCAT(pag_url4,'/'))=1";
					$sql .= " ORDER BY L DESC, lng_id ASC LIMIT 0,1";

					$rs = $myDB->query($sql);
					if (mysql_num_rows($rs)==1)
					{
						$row =mysql_fetch_array($rs);
						$this->set("id",$row["pag_id"]);
						$this->set("lng_id",$row["lng_id"]);

						$params = mb_substr($smartURL,$row["L"]+1);
						$this->set("smartURLParams", $params);
						$this->set("smartPATH", mb_substr($smartURL,0,$row["L"]));

						$_params = mb_split('/',$params);
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
						
						global $myTC;
						$myTC->stop();
						if (mysql_num_rows($rs)>1 OR $uniqueURL==false)// no unique hit
						{
							$cookie = md5("on".PT_SECRETKEY);
							if ((PT_DEBUG==1 AND  $_COOKIE["pt_debug"]==$cookie) OR PT_VERBOSE_UNTIL>time())
							{
								throw new Exception("smartURL '".$smartURL."' not unique.\nCheck your smartURL setup. Probably you have to activate 'full path' for some pagegroups.");
							}
						}
						$this->code404=1;
					}
				}
			}
		}
		else // we're in the backend
		{
			if (PT_PHPIDS ==1)
			{
				$_excludes = mb_split(',',PT_PHPIDS_EXCLUDES);
				// exclude params used to transfer scripts (e.g. of an Include)
				$_excludes[]="skript";
				$_excludes[]="script";
				foreach ($this->_props AS $param => $value)
				{
					// anything entered by the user, when editing page components
					if (mb_substr($param,0,4)=="com_")
					{
						$_excludes[]=$param;
					}
					// anything entered by the user, when editing content records
					if (mb_substr($param,0,4)=="con_")
					{
						$_excludes[]=$param;
					}
					// component templates
					if (mb_substr($param,0,4)=="ttp_")
					{
						$_excludes[]=$param;
					}
				}
				// the comment & description fields, as often used on admin and config pages (just to avoid annoying disturbances)
				$_excludes[]="comment";
				$_excludes[]="description";

				// the layout templates
				$_excludes[]="template_normal";
				$_excludes[]="template_print";
				
				// to be checked ...
				$_excludes[]="http_referer";

				$this->phpIDS($_excludes);
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
				if (mb_substr($k,0,2)!="__") // Additionally ignore keys starting with "__" It's ver unlinkely to have them on purpose, but not ignoring them might diable the include cache when using Google Analytics
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
		// TODO:Check
		if (in_array('HTTP_X_REQUESTED_WITH',$_SERVER))
		{
			return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
		}
		else
		{
			return false;
		}
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

	public function phpIDS($_excludes=Array())
	{
		
		if (phpversion()<"5.1.6")
		{
			throw new Exception("Security Warning. PHPIDS needs at least PHP version 5.1.6.\nYou may deactivate PHPIDS by putting define(\"PT_PHPIDS\",0); into your _config.inc.php file.");
		}
		
		set_include_path(
		get_include_path()
		. PATH_SEPARATOR
		. SYSTEMPATH.'phpids/lib/'
		);

		require_once 'IDS/Init.php';

		$request = $this->_props;
		foreach ($_excludes as $exclude)
		{
			unset($request[$exclude]);
		}
		$init = IDS_Init::init(dirname(__FILE__) . '/../phpids/lib/IDS/Config/Config.ini.php');


		$init->config['General']['base_path'] = dirname(__FILE__) . '/../phpids/lib/IDS/';
		$init->config['General']['use_base_path'] = false;
		$init->config['General']['filter_path']= dirname(__FILE__) . '/../phpids/lib/IDS/default_filter.xml';
		$init->config['General']['tmp_path'] = TEMPPATH."phpids";
		
		$init->config['Caching']['caching'] = 'file';
		$init->config['Logging']['path']=TEMPPATH."logs/phpids_log.txt";
		$init->config['Caching']['path']=TEMPPATH."phpids/default_filter.cache";
		
		$ids = new IDS_Monitor($request, $init);
		$result = $ids->run();
		if (!$result->isEmpty())
		{
			$this->set("smartIDSImpact",$result->getImpact());
			if ($result->getImpact()>PT_PHPIDS_MAXIMPACT)
			{
				if(!file_exists($init->config['Logging']['path']))
				{
					touch($init->config['Logging']['path']);
				}
				require_once dirname(__FILE__) . '/../phpids/lib/IDS/Log/File.php';
		        require_once dirname(__FILE__) . '/../phpids/lib/IDS/Log/Composite.php';
				$compositeLog = new IDS_Log_Composite();
		        $compositeLog->addLogger(IDS_Log_File::getInstance($init));
		        $compositeLog->execute($result);
				throw new Exception("PHP Intrusion Detection\n\n".strip_tags(html_entity_decode($result,null,PT_CHARSET)));
			}
		}

		//echo $result;
	}
	


	/**
	 * Get (valid) client IP, considering proxies
	 *
	 * @return string IP
	 */
	public function getIP()
	{

		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$key="HTTP_X_FORWARDED_FOR";
		}
		else
		{
			$key="REMOTE_ADDR";
		}
		$_ips = explode(",",$_SERVER[$key]);
		$valid_ip = long2ip(ip2long(array_pop($_ips)));
		return $valid_ip;
	}

	/**
	 * debug print current request values
	 *
	 */
	function printR()
	{
		echo "<pre>";
		print_r ($this->_props);
		echo "</pre></br>";
	}	
	/**
	 * Logs current request values
	 *
	 */
	public function log()
	{
		global $myLog;
		if (is_object($myLog))
		{
			$myLog->log("Request values:",PT_LOGFACILITY_APP,PT_LOGLVL_DEBU);
			foreach($this->_props AS $k=>$v)
			{
				$myLog->log($k.": ".print_r($v,true),PT_LOGFACILITY_APP,PT_LOGLVL_DEBUG);
			}
		}
	}
}
?>
