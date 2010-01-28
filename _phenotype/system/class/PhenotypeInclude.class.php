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
class PhenotypeIncludeStandard extends PhenotypeBase
{
	public $id;


	public $params;
	public $html;
	public $context = 0;

	/**
	 * enable/disable smartActions
	 *
	 * @var boolean
	 */
	protected $smartActions = false;

	/**
	 * enable/disable magic properties
	 *
	 * @var unknown_type
	 */
	protected $magicProperties = false;

	/**
	 * reserved for future usage
	 *
	 * If set to true page layout rendering is canceled, when executing this include
     * 
     * Attention: That also means, that the whole rendering process is stopped after execution of this Include, so be sure, to place
   	 * it in the right order
	 * @var unknown_type
	 */
	protected $disableLayout = false;

	/**
	 * When smartActions are enabled the request object tries to guess the selected action. If no action parameter is
	 * given, the first smartParam will become the action. That results in a "shift" of all other params. This shift 
	 * may interfere with other includes. Therefore the request object is cloned by default. You may turn this protection
	 * off (might help you when debugging smartActions).
	 * 
	 * @var boolean
	 */
	protected $protectGlobalRequestObject = true;






	protected $view_success = "Success";
	protected $view_error = "Error";
	protected $view_ajax = "Ajax";
	protected $view_post = "Post";
	protected $view_none = false;









	public function __construct($p1="",$p2="")
	{
		global $myDebug;
		if (get_class ($this)=="PhenotypeInclude")
		{
			// Abw�rtskompatibel PT 2.1
			$p1 = (int)$p1;
			$this->id = $p1;
			$this->params = $p2;
			// Abw�rtskompatibel PT 2.0
			$this->html = $p2;
		}
		else // ab PT 2.2
		{
			// Wir sind in einer abgeleiteten Klassen
			$this->params = $p1;
			// Abw�rtskompatibel PT 2.0
			$this->html = $p1;
		}
		$myDebug->notifyIncludeUsage($this->id);
	}

	function initRendering()
	{
		global $myPT;
		$myPT->startbuffer();
	?> 
     $mySmarty = new PhenotypeSmarty();
	 global $myDB;
     global $myPT;
     global $myPage;
     
     $mySmarty->compile_dir = SMARTYCOMPILEPATH;		 
	 $mySmarty->clear_all_assign();
     $sql = "SELECT * FROM include_template WHERE inc_id = " . $this->id . " ORDER BY tpl_id";
     $rs = $myDB->query($sql);
     while ($row_itp=mysql_fetch_array($rs))
     {
	    $tpl = $row_itp["tpl_bez"];
	    $dateiname =  $myPT->getTemplateFileName(PT_CFG_INCLUDE, $this->id, $row_itp["tpl_id"]);
	    $$tpl = $dateiname;
	 }	 
	 $mySmarty->assign("include",$this);
	<?php
	$code = $myPT->stopbuffer();
	return $code;
	}


	function execute()
	{
		global $myPT;
		if ($this->smartActions==true)
		{
			global $myPT;
			$myPT->startBuffer();
			$this->executeSmartAction();
			$html = $myPT->stopBuffer();
			return $html;
		}

		//$buffer_preexecution = $myPT->stopBuffer();
		//$myPT->setBuffer($buffer_preexecution);

		if (get_class ($this)=="PhenotypeInclude")
		{
			// Abw�rtskomaptibel zu PT 2.0 - 2.1
			$cname = "PhenotypeInclude_".$this->id;
			$myInc = new $cname($this->params);
			$html = $myInc->execute();
		}
		else
		{
			global $myPT;
			$myPT->startBuffer();
			$this->display();
			$html = $myPT->stopBuffer();
		}
		/*
		if ($this->disableLayout)
		{
		echo $html;
		die();
		}
		*/

		return ($html);
	}


	function renderXML()
	{

		global $myPT;
		$myPT->startBuffer();
		$this->displayXML();
		$xml = $myPT->stopBuffer();
		$test = '<?xml version="1.0" encoding="'.PT_CHARSET.'" ?>'.$xml;
		if (@simplexml_load_string($test))
		{
			return $xml;
		}
		else
		{
			return "<error>XML for include ".$this->id." not wellformed.</error>";
		}
	}

	/**
	 * Please implement this method for normale includes
	 * 
	 * You don't need it when using smartActions
	 *
	 */
	function display()
	{
	}

	function displayXML()
	{
		global $myPT;
		global $myDB;
		$sql = "SELECT * FROM include WHERE inc_id=".$this->id;
		$rs=$myDB->query($sql);
		$row = mysql_fetch_array($rs);
  	?>
	<include inc_id="<?php echo $this->id ?>" type="<?php echo $myPT->xmlencode($row["inc_bez"]) ?>">
	<content>
	</content>
  	</include>
  	<?php
	}




	function rawXMLExport($inc_id=-1)
	{
		global $myDB;
		global $myPT;

		if ($inc_id==-1)
		{
			$inc_id = $this->id;
		}

		$sql = 'SELECT * FROM include WHERE inc_id='. $inc_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);


		$file= APPPATH ."includes/PhenotypeInclude_"  .$inc_id . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="'.PT_CHARSET.'" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
		<inc_id>'.$myPT->codeX($row['inc_id']).'</inc_id>
		<inc_bez>'.$myPT->codeX($row['inc_bez']).'</inc_bez>		
		<inc_rubrik>'.$myPT->codeX($row['inc_rubrik']).'</inc_rubrik>
		<inc_description>'.$myPT->codeX($row['inc_description']).'</inc_description>
		<inc_usage_layout>'.$myPT->codeX($row['inc_usage_layout']).'</inc_usage_layout>
		<inc_usage_includecomponent>'.$myPT->codeX($row['inc_usage_includecomponent']).'</inc_usage_includecomponent>
		<inc_usage_page>'.$myPT->codeX($row['inc_usage_page']).'</inc_usage_page>
	</meta>
	<script>'.$myPT->codeX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM include_template WHERE inc_id = ' 	. $inc_id . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $row["tpl_id"]);
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
			$inc_id = (int)pt_package_xml_decode($_xml->meta->inc_id);

			// Zun�chst evtl. vorhanden alte Templates l�schen

			$sql = "SELECT * FROM include_template WHERE inc_id = " . $inc_id . " ORDER BY tpl_id";
			$rs = $myDB->query($sql);
			while ($row_ttp=mysql_fetch_array($rs))
			{
				$dateiname = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $row_ttp["tpl_id"]);
				@unlink($dateiname);
			}
			$sql = "DELETE FROM include_template WHERE inc_id = " . $inc_id;
			$myDB->query($sql);

			// Jetzt die eigentliche Klasse
			$dateiname = APPPATH . "includes/PhenotypeInclude_"  .$inc_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM include WHERE inc_id = " . $inc_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("inc_id",$inc_id,DB_NUMBER);
			$inc_bez = (string)pt_package_xml_decode($_xml->meta->inc_bez);
			$mySQL->addField("inc_bez",$inc_bez);
			$inc_description = (string)pt_package_xml_decode($_xml->meta->inc_description);
			$mySQL->addField("inc_description",$inc_description);
			$inc_rubrik = (string)pt_package_xml_decode($_xml->meta->inc_rubrik);
			$mySQL->addField("inc_rubrik",$inc_rubrik);

			$inc_usage = (int)pt_package_xml_decode($_xml->meta->inc_usage_layout);
			$mySQL->addField("inc_usage_layout",$inc_usage);

			$inc_usage = (int)pt_package_xml_decode($_xml->meta->inc_usage_includecomponent);
			$mySQL->addField("inc_usage_includecomponent",$inc_usage);

			$inc_usage = (int)pt_package_xml_decode($_xml->meta->inc_usage_page);
			$mySQL->addField("inc_usage_page",$inc_usage);

			$sql = $mySQL->insert("include");
			$myDB->query($sql);


			$script = (string)pt_package_xml_decode($_xml->script);

			$file = APPPATH . "includes/PhenotypeInclude_"  .$inc_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			// Templates anlegen

			$tpl_id = 1;
			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)pt_package_xml_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
				$mySQL->addField("tpl_id",$tpl_id,DB_NUMBER);
				$mySQL->addField("inc_id",$inc_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("include_template");
				$myDB->query($sql);
				$html = (string)pt_package_xml_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
				$tpl_id++;
			}



			return $inc_id;

		}
		else
		{
			return (false);
		}
	}

	public function __call($methodname,$params)
	{
		throw new Exception("There's no method ".$methodname."() in PhenotypeInclude_".sprintf('%02d',$this->id) .".");
	}




	/**
	 * action/view dispatcher if smartActions are enabled
	 *
	 */
	protected function executeSmartAction ()
	{
		global $myRequest;
		global $myPT;
		global $myApp;
		global $myPage;
		
		// We clone the request object to be able to shift params, in case the url did not containt the action parameter,
		// but an action value!

		if ($this->protectGlobalRequestObject===true)
		{
			$myRequestClone = clone($myRequest);
		}
		else
		{
			$myRequestClone = $myRequest;
		}

		$action = "";
		$ajax = false;
		$json = false;
		$post = false;

		if ($myRequestClone->check("action"))
		{
			$action =$myRequestClone->getA("action",PT_ALPHA,"index");
		}
		else
		{
			$action =$myRequestClone->getA("smartParam1",PT_ALPHA);
			if ($action!="")
			{
				$myRequestClone->shiftParams4Action($action);
			}
			else
			{
				$action="Index";
			}
		}

		$action = mb_strtoupper($action[0]) . mb_substr ($action,1);

		if ($myRequestClone->isPostRequest())
		{
			$post = true;
			// Currently only phenotype naming convention, others might follow

			// postXYZAction => indexXYZAction => Error
			$methodname = "post" . $action."Action";
			if (!method_exists($this,$methodname))
			{
				$methodname = "execute" . $action."Action";
			}

		}
		else
		{
			if ($myRequestClone->isAjaxRequest())
			{
				$ajax = true;
				// Currently only phenotype naming convention, others might follow

				// jsonXYZAction => ajaxXYZAction => indexXYZAction => Error
				$methodname = "json" . $action."Action";
				if (!method_exists($this,$methodname))
				{
					$methodname = "ajax" . $action."Action";
					if (!method_exists($this,$methodname))
					{
						$methodname = "execute" . $action."Action";
					}
				}
				else
				{
					$json = true;
				}

			}
			else
			{

				$methodname = "execute" . $action."Action";

			}
		}
	



		



		if (!method_exists($this,$methodname))
		{

			if (!method_exists($this,"executeUnknownAction"))
			{
				$cookie = md5("on".PT_SECRETKEY);
		
				if (($_COOKIE["pt_debug"]!=$cookie OR PT_DEBUG==0) AND PT_VERBOSE_UNTIL<time() )
				{
					ob_clean();

					$myApp->throw404($myPage->id);
					exit();
				}
				throw new Exception("Include ".$this->id.": Undefined action method ".$methodname.' ($myRequest)');
			}
			else
			{
				$action = "Unknown";
				$methodname = "executeUnknownAction";
			}
		}
		$view = call_user_method($methodname,$this,$myRequestClone);

		if ($json==true)
		{
			ob_get_clean();
			echo json_encode($view);
			exit();
		}
		if (is_array($view) OR is_object($view))
		{
			throw new Exception("Include ".$this->id.": Wrong return value for action method ".$methodname.' ($myRequestClone). Arrays and objects are not supported.');
		}
		if ($view!==false)
		{
			eval ($this->initRendering());
			if ($this->magicProperties==true)
			{
				foreach ($this->_props AS $k => $v)
				{
					$mySmarty->assign($k,$v);
				}
			}
			if (is_null($view))
			{
				$view=$this->view_success;
				if ($ajax==1)
				{
					$template = $action ."Ajax";
					if ($$template!="")
					{
						$view=$this->view_ajax;
					}
				}
				if ($post==1)
				{
					$template = $action ."Post";
					if ($$template!="")
					{
						$view=$this->view_post;
					}
				}
			}

			$template = $action . $view;
			if ($$template=="")
			{
				$cookie = md5("on".PT_SECRETKEY);
		
				if (($_COOKIE["pt_debug"]!=$cookie OR PT_DEBUG==0) AND PT_VERBOSE_UNTIL<time() )
				{
					ob_get_clean();

					$myApp->throw404($myPage->id);
					exit();
				}
				throw new Exception("Include ".$this->id.": Missing template ".$template.". (return false, if no template should be selected!)");
			}
			$mySmarty->display ($$template);
		}
		
	
	}

	/**
	 * reserved for future usage
	 * 
	 * currently there's a problem with the output buffer, if we don't know from beginning, that the layout should be disabled
	 *
	 */
	public function disableLayout()
	{
		$this->disableLayout = true;
	}



	public function __set($k,$v)
	{
		if ($this->magicProperties==true)
		{
			$this->set($k,$v);
		}
	}
	public function __get($k)
	{
		if ($this->magicProperties==true)
		{
			return $this->get($k);
		}

	}

	/**
	 * Jump to another action (via http-redirect)
	 * 
	 * We don't have a forward function, you may call executeActionXY manually 
	 *
	 * @param unknown_type $action
	 * @param unknown_type $_params
	 */
	public function redirect($action=index,$_params)
	{
		global $myRequest;
		if ($myRequest->check("smartPATH")) // We won't have smartPATH on POST requests
		{
			$url = $myRequest->get("smartPATH");
		}
		else
		{
			$url = $myRequest->get("smartURL");
		}
		$url .="/".$action;
		foreach ($_params AS $k=>$v)
		{
			$url .="/".$k."/".$v;
		}
		Header ("Location:" .$url);
		exit();
	}

}
?>