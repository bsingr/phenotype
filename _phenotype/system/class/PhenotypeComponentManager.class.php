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
class PhenotypeComponentManager
{
	/**
	 * Create new component object class
	 *
	 * @param string $name
	 * @param int $com_id
	 * @param string $extendedClass
	 * @return int ID (com_id) of new component object class
	 */
	public static function createComponentClass($name, $com_id = 0, $extendedClass = null)
	{
		global $myDB;

		$mySQL = new SQLBuilder ( );
		$mySQL->addField ("com_bez", $name );

		if ($com_id == 0)
		{
			// ID unter 1000 ermitteln
			$sql = "SELECT MAX(com_id) AS ID FROM component WHERE com_id<1000";
			$rs = $myDB->query ( $sql );
			$row = mysql_fetch_array ( $rs );
			if ($row ["ID"] < 999)
			{
				$mySQL->addField ( "com_id", $row ["ID"] + 1, DB_NUMBER );
			}
			// -- ID unter 1000
		} else
		{
			$mySQL->addField ( "com_id", $com_id, DB_NUMBER );
		}



		$sql = $mySQL->insert("component");
		$myDB->query($sql);
		$id = mysql_insert_id();

		$mySmarty = new PhenotypeSmarty();

		$mySmarty->template_dir = SYSTEMPATH . "templates/";
		$mySmarty->compile_dir = SMARTYCOMPILEPATH;
		$mySmarty->assign("id", $id);

		$s = $mySmarty->fetch("component_defaultcode.tpl");

		if ($extendedClass != null)
		{
			$s = str_replace("extends PhenotypeContent","extends ".$extendedClass,$s);
		}
		
		$dateiname = APPPATH . "components/PhenotypeComponent_"  .$id . ".class.php";

		$fp = fopen ($dateiname,"w");
		fputs ($fp,$s);
		fclose ($fp);
		@chmod ($dateiname,UMASK);


		return $id;
	}

	public function storeScript($com_id,$s)
	{
		$dateiname = APPPATH . "content/PhenotypeContent_" . $com_id . ".class.php";

		$fp = fopen ( $dateiname, "w" );
		fputs ( $fp, $s );
		fclose ( $fp );
		@chmod ( $dateiname, UMASK );
	}
}
