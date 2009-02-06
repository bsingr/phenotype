<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2008 Nils Hagemann, Paul Sellinger,
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
// Version 2.7 vom 17.11.2008
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeIncludeControllerStandard extends PhenotypeInclude
{
	const naming_convention_phenotype = 0;
	const naming_convention_symfony = 1;
	const naming_convention_zend = 2;

	public $naming_convention = self::naming_convention_phenotype;

	protected $view_success = "Success";
	protected $view_error = "Error";
	protected $view_ajax = "Ajax";
	protected $view_post = "Post";
	protected $view_none = false;


	private $smartActions = true;
	// TODO: magicProperties ! ...
	private $magicProperties = false;

	public $props = Array ();


	/**
   * If set to true page layout rendering is canceled, when executing this include
   * 
   * Attention: That also means, that the whole rendering process is stopped after execution of this Include, so be sure, to place
   * it in the right order
   *
   * @var boolean
   */
	private $disableLayout = false;

	private $protectGlobalRequestObject = false;


	public function execute ()
	{
		global $myRequest;
		global $myPT;
		global $myApp;
		global $myPage;


		// We clone the request object to be able to shift params, in case the url did not containt the action parameter,
		// but an action value!

		if (!$this->protectGlobalRequestObject)
		{
			$myRequestClone = clone($myRequest);
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

		$action = strtoupper($action[0]) . substr ($action,1);

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
		$buffer_preexecution = $myPT->stopBuffer();

		$myPT->startBuffer();



		if (!method_exists($this,$methodname))
		{
			
			if (!method_exists($this,"executeUnknownAction"))
			{
				if (!PT_DEBUG==1 OR !isset($_COOKIE["pt_debug"]))
				{
					ob_clean();
					
					$myApp->throw404($myPage->id);
					exit();
				}
				throw new Exception("Undefined action method ".$methodname.' ($myRequestClone)');
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
			$myPT->stopBuffer();
			echo json_encode($view);
			exit();
		}
		if (is_array($view) OR is_object($view))
		{
			throw new Exception("Wrong return value for action method ".$methodname.' ($myRequestClone). Arrays and objects are not supported.');
		}
		if ($view!==false)
		{
			eval ($this->initRendering());
			foreach ($this->props AS $k => $v)
			{
				$mySmarty->assign($k,$v);
			}
			if (is_null($view))
			{
				$view="Success";
				if ($ajax==1)
				{
					$template = $action ."Ajax";
					if ($$template!="")
					{
						$view="Ajax";
					}
				}
				if ($post==1)
				{
					$template = $action ."Post";
					if ($$template!="")
					{
						$view="Post";
					}
				}
			}

			$template = $action . $view;
			if ($$template=="")
			{
				if (!PT_DEBUG==1 OR !isset($_COOKIE["pt_debug"]))
				{
					ob_clean();
					
					$myApp->throw404($myPage->id);
					exit();
				}
				throw new Exception("Missing template ".$template.". (return false, if no template should be selected!)");
			}
			$mySmarty->display ($$template);
		}
		if ($this->disableLayout)
		{
			echo $myPT->stopBuffer();
			die();
		}
		$buffer_execution =$myPT->stopBuffer();
		echo $buffer_preexecution;
		echo $buffer_execution;

	}

	public function disableLayout()
	{
		$this->disableLayout = true;
	}



	public function __set($k,$v)
	{
		
		$this->_props[$k] = $v;
	}

	public function __get($k)
	{
		return $this->_props[$k];
	}

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