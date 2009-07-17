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
 * @subpackage backend
 *
 */
class PhenotypeBackend_Session_Logout_Standard extends PhenotypeBackend_Session
{
  
  // PhenotypeBackend_Session-Classes don't have their own localization file. It's because some session/login/rights related functions
  // are located in the PhenotypeBackendStandard class.
  
  public $tmxfile = "Phenotype";
  
	function execute()
	{
		global $myLog;
		session_start();
		$myUser = new PhenotypeUser($_SESSION["usr_id"]);
		$myLog->log("Benutzer ".$myUser->id . " - " . $myUser->getName() ." abgemeldet",PT_LOGFACILITY_SYS);
		session_destroy();
		setcookie("pt_debug","",0,"/");
		$this->displayLogin();

	}
}
?>