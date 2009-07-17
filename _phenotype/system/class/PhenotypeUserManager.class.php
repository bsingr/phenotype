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
class PhenotypeUserManager
{
	public function grantRole($rol_id, $usr_id)
	{
		$rol_id = codeI ( $rol_id );
		$usr_id = codeI ( $usr_id );
		$_rights = self::_getRightsArray ( $usr_id );
		$_rights ["rol_" . $rol_id] = 1;
		$this->_buildRights($usr_id,$_rights);
	}
	
	private function _getRightsArray($usr_id)
	{
		global $myDB;
		
		$usr_id = ( int ) $usr_id;
		
		$sql = "SELECT * FROM user WHERE usr_id=" . $usr_id . " AND usr_status=1";
		$rs = $myDB->query ( $sql );
		if (mysql_num_rows ( $rs ) != 1)
		{
			throw new Exception ( "User " . $usr_id . " not found." );
		}
		$row = mysql_fetch_array ( $rs );
		if ($row ["usr_rights"] != "")
		{
			$_rights = unserialize ( $row ["usr_rights"] );
		} else
		{
			$_rights = Array ();
		}
		return $_rights;
	}
	
	private function _buildRights($usr_id, $_rights)
	{
		global $myDB;
		global $mySUser;
		$mySQL = new SQLBuilder ( );
		$mySQL->addField ( "usr_rights", serialize ( $_rights ) );
		
		$sql = $mySQL->update ( "user", "usr_id=" . $usr_id );
		$myDB->query ( $sql );
    //TODO: Move methode from User to here
		$mySUser->buildRights ( $usr_id );
	}
}
?>