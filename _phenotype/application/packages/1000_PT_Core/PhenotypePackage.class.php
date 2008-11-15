<?php
class PhenotypePackage extends PhenotypePackageStandard
{
	public $bez = "PT Core";
	public $packagefolder = "1000_PT_Core";
	
	function getDescription()
	{
		return ("Basic elements - If you start a new application, you should make a cleanup and install this package.<br/><br/>Version ##!PT_VERSION!## vom ##!BUILD_DATE!##");
	}
}
?>