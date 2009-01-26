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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeRolesManager
{
	public function createRole($name)
	{
		global $myDB;
		$mySQL = new SQLBuilder ( );
		$mySQL->addField ( "rol_bez", $name );
		$mySQL->addField ( "rol_rights", serialize ( array () ) );
		$sql = $mySQL->insert ( "role" );
		$myDB->query ( $sql );
		
		$rol_id = mysql_insert_id ();
		return $rol_id;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $rol_id
	 * @param unknown_type $_options
	 */
	public function setBasicRights($rol_id, $pageconfig = null, $pagenocomponent = null, $pagestatistic = null, $analyse = null, $admin = null, $rollback = null)
	{
		global $myDB;
		$_rights = self::_getRightsArray ( $rol_id );
		$_keys = array ("pageconfig", "pagenocomponent", "pagestatistic", "analyse", "admin", "rollback" );
		foreach ( $_keys as $key )
		{
			
			if ($$key !== null)
			{
				if (true == ( boolean ) $$key)
				{
					$_rights ["elm_" . $key] = 1;
				} else
				{
					$_rights ["elm_" . $key] = 0;
				}
			}
		}
		
		$this->_spreadRights ( $rol_id, $_rights );
	}
	
	public function setContentAccessRights($rol_id, $_ids, $reset = false)
	{
		$rol_id = codeI ( $rol_id );
		
		$_rights = self::_getRightsArray ( $rol_id );
		if ($reset)
		{
			$_rights ["elm_redaktion"] = 0;
			$_rights ["elm_content"] = 0;
			foreach ( $_rights as $k => $v )
			{
				if (substr ( $k, 0, 4 ) == "con_")
				{
					unset ( $_rights [$k] );
				}
			}
		}
		foreach ( $_ids as $con_id )
		{
			$_rights ["con_" . $con_id] = 1;
			$_rights ["elm_redaktion"] = 1;
			$_rights ["elm_content"] = 1;
		}
		$this->_spreadRights ( $rol_id, $_rights );
	}
	
	public function setMediaGroupAccessRights($rol_id, $_ids, $reset = false)
	{
		$rol_id = codeI ( $rol_id );
		
		$_rights = self::_getRightsArray ( $rol_id );
		if ($reset)
		{
			$_rights ["elm_mediabase"] = 0;
			foreach ( $_rights as $k => $v )
			{
				if (substr ( $k, 0, 16 ) == "access_mediagrp_")
				{
					unset ( $_rights [$k] );
				}
			}
		}
		foreach ( $_ids as $grp_id )
		{
			$_rights ["access_mediagrp_" . $grp_id] = 1;
			$_rights ["elm_mediabase"] = 1;
		}
		$this->_spreadRights ( $rol_id, $_rights );
	}
	
	public function setTaskRealmAccessRights($rol_id, $_ids, $reset = false)
	{
		$rol_id = codeI ( $rol_id );
		
		$_rights = self::_getRightsArray ( $rol_id );
		if ($reset)
		{
			$_rights ["elm_task"] = 0;
			foreach ( $_rights as $k => $v )
			{
				if (substr ( $k, 0, 4 ) == "sbj_")
				{
					unset ( $_rights [$k] );
				}
			}
		}
		foreach ( $_ids as $sbj_id )
		{
			$_rights ["sbj_" . $sbj_id] = 1;
			$_rights ["elm_task"] = 1;
		}
		$this->_spreadRights ( $rol_id, $_rights );
	}
	
	private function _getRightsArray($rol_id)
	{
		global $myDB;
		
		$rol_id = ( int ) $rol_id;
		
		$sql = "SELECT * FROM role WHERE rol_id=" . $rol_id;
		$rs = $myDB->query ( $sql );
		if (mysql_num_rows ( $rs ) != 1)
		{
			throw new Exception ( "Role " . $rol_id . " not found." );
		}
		$row = mysql_fetch_array ( $rs );
		if ($row ["rol_rights"] != "")
		{
			$_rights = unserialize ( $row ["rol_rights"] );
		} else
		{
			$_rights = Array ();
		}
		return $_rights;
	}
	
	private function _spreadRights($rol_id, $_rights)
	{
		global $myDB;
		
		$rol_id = codeI($rol_id);
		$mySQL = new SQLBuilder ( );
		$mySQL->addField ( "rol_rights", serialize ( $_rights ) );
		$sql = $mySQL->update ( "role", "rol_id=" . $rol_id );
		$myDB->query ( $sql );
		
		$sql = "SELECT * FROM user";
		$rs = $myDB->query ( $sql );
		$myCUser = new PhenotypeUser ( );
		while ( $row = mysql_fetch_array ( $rs ) )
		{
			$myCUser->init ( $row );
			if ($myCUser->checkRight ( "rol_" . $rol_id ))
			{
				$myCUser->buildRights ();
			}
		}
	}
}
?>