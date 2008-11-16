<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
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