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
class PhenotypeContentManager
{
	/**
	 * Create new content object class
	 *
	 * @param string $name
	 * @param int $con_id
	 * @param string $extendedClass
	 * @return int ID (con_id) of new content object class
	 */
	public function createContentClass($name, $con_id = 0, $extendedClass = null)
	{
		// ToDO: Check against exisiting content class

		global $myDB;
		
		$mySQL = new SQLBuilder ( );
		$mySQL->addField ( "con_bez", $name );
		
		if ($con_id == 0)
		{
			// ID unter 1000 ermitteln
			$sql = "SELECT MAX(con_id) AS ID FROM content WHERE con_id<1000";
			$rs = $myDB->query ( $sql );
			$row = mysql_fetch_array ( $rs );
			if ($row ["ID"] < 999)
			{
				$mySQL->addField ( "con_id", $row ["ID"] + 1, DB_NUMBER );
			}
			// -- ID unter 1000
		} else
		{
			$mySQL->addField ( "con_id", $con_id, DB_NUMBER );
		}
		
		$sql = $mySQL->insert ( "content" );
		$myDB->query ( $sql );
		$con_id = mysql_insert_id ();
		
		$mySmarty = new PhenotypeSmarty ( );
		
		$mySmarty->template_dir = SYSTEMPATH . "templates/";
		$mySmarty->compile_dir = SMARTYCOMPILEPATH;
		$mySmarty->assign ( "id", $con_id );
		
		$s = $mySmarty->fetch ( "contentobject_defaultcode.tpl" );
		
		if ($extendedClass != null)
		{
			$s = str_replace("extends PhenotypeContent","extends ".$extendedClass,$s);
		}
		
		$dateiname = APPPATH . "content/PhenotypeContent_" . $con_id . ".class.php";
		
		$fp = fopen ( $dateiname, "w" );
		fputs ( $fp, $s );
		fclose ( $fp );
		@chmod ( $dateiname, UMASK );
		
		// Cacheordner anlegen
		

		$dir = CACHEPATH . CACHENR . "/content/" . $con_id;
		@mkdir ( $dir );
		@chmod ( $dir, UMASK );
		
		return $con_id;
	}
	
	public function storeScript($con_id,$s)
	{
		$dateiname = APPPATH . "content/PhenotypeContent_" . $con_id . ".class.php";
    
    $fp = fopen ( $dateiname, "w" );
    fputs ( $fp, $s );
    fclose ( $fp );
    @chmod ( $dateiname, UMASK );
	}
}
?>