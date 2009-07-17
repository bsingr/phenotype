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
class PhenotypeSoapServerStandard
{

	
	public function __construct()
	{
		define("PT_REMOTECALL",1);
	}


	
	/**
	 * Internal check for correct hashing values
	 *
	 * @param string_type $params
	 * @param string $hash
	 * @return boolean
	 * @throws SoapFault
	 */
	private function checkHash($params,$hash)
	{
		if ($hash == md5(PT_SECRETKEY.$params))
		{
			return true;
		}
		$backtrace = debug_backtrace();
		$method = $backtrace[1]['function'];
		throw new SoapFault("Sender","Wrong hash code for method ".$method.".");
	}
	
	/**
	 * Clean up (Delete all components, temp files and so on) whole application
	 *
	 * Example:
	 * 
	 * $params="method=executeCleanUp";
     * $hash = md5(PT_REMOTE_SECRETKEY.$params.$random);
     * $client->executeCleanUp($hash,$random);
     * 
	 * @param string $hash
	 * @return boolean
	 * @throws SoapFault
	 */
	public function executeCleanUp($hash,$random)
	{
		$this->checkHash("method=executeCleanUp".$random,$hash);
		$myMgr = new PhenotypeApplicationManager();
		return $myMgr->cleanupAll();
	}

	/**
	 * Install remote package
	 *
	 * Example:
	 * 
	 * $params="method=installPackage&name=".(string)$name;
     * $hash = md5(PT_REMOTE_SECRETKEY.$params.$random);
     * $client->installPackage($name,$hash,$random);
	 * 
	 * @param string $name Name of remote package (e.g. PT_DEMO)
	 * @param string $hash
	 * @return boolean
	 * @throws SoapFault
	 */
	public function installPackage($name,$hash,$random)
	{
		$this->checkHash("method=installPackage&name=".(string)$name.$random,$hash);
		$myMgr = new PhenotypePackageManager();
		return $myMgr->installPackage($name);
	}
}