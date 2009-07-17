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
 * @subpackage system
 *
 */
set_time_limit(0);
require("_config.inc.php");


if (!defined("PT_SOAP") OR PT_SOAP==0)
{
	exit();
}

define ("PT_SOAPCONTEXT",1);

// We must have the same conditions like a logged in backend user
$myPT->loadTMX("Config");
ini_set("session.use_trans_sid",0);
@session_start();
$_SESSION["usr_id"]=1;
$_SESSION["status"]=1;
$mySUser = new PhenotypeUser();
$mySUser->load($_SESSION["usr_id"]);
$mySmarty = new PhenotypeSmarty();
$myAdm = new PhenotypeAdmin();


ini_set("soap.wsdl_cache_enabled", "0");

$server = new SoapServer("phenotype.wsdl"); 

$server->setClass("PhenotypeSoapServer"); 
$server->handle();