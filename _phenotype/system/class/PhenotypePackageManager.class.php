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
class PhenotypePackageManager
{
	private $package_file = false;
	
	private $myPak = false;
	
	public function selectPackage($name)
	{
		$name =codeA($name,PT_ALPHANUMERIC."_-");
		
		$file = PACKAGEPATH.$name."/PhenotypePackage.class.php";

		if (file_exists($file))
		{
			$this->package_file = $file;
		}
		else 
		{
			if (defined("PT_SOAPCONTEXT") AND PT_SOAPCONTEXT==1)
			{
			throw new SoapFault("Server","Package file ".$file." not found");
			}
			else 
			{
				throw new Exception("Package file ".$file." not found");
			}
		}
	}
	
	public function getPackage()
	{
		if($this->myPak!=false)
		{
			return $this->myPak;
		}
		
		if ($this->package_file==false)
		{
			throw new Exception("No package selected. Must call selectPackage() first.");
		}
		require_once ($this->package_file);
		$myPak = new PhenotypePackage();
		return $myPak;
	}
	
	public function installPackage($name)
	{
		$this->selectPackage($name);
		global $myPT;
		$myPT->clearCache();
		
		$myPak = $this->getPackage();
		$myPak->globalInstallStructure(false);
		$myPak->globalInstallData();
		return true;
		
	}
	
	public function globalInstallStructure($hostconfig=false)
	{
		$myPak = $this->getPackage();
		return $myPak->globalInstallStructure($hostconfig);
	}
	
	public function globalInstallData()
	{
		$myPak = $this->getPackage();
		return $myPak->globalInstallData();
	}
}