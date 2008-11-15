<?php
	class PhenotypePackage extends PhenotypePackageStandard
	{
		public $bez = "PT Demonstration";
		public $packagefolder = "1200_PT_Demo";
		
		function getDescription()
		{
			return ("Phenotype demonstration Website. This package installs a simple webiste with richtext and table components, gallery and navigation examples.<br/><br/>Version ##!PT_VERSION!## vom ##!BUILD_DATE!##");
		}
	}
	?>