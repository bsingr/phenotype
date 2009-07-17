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
 * TODO: getImage, getImages, getDocument, getDocuments, getUser, getUsers, getPage, getPages, getComponents ?, getInclude?
 */
class PhenotypePeerStandard
{

	public static function getRecords($con_id,$status=true,$order="dat_bez",$_filter=array(),$limit)
	{
		global $myDB;
		$con_id=(int)$con_id;

		$sql = "SELECT * FROM content_data WHERE con_id=".$con_id;
		switch ($status)
		{
			case true:
				$sql .=" AND dat_status=1";
				break;
			case false:
				$sql .=" AND dat_status=0";
				break;
		}
		foreach ($_filter AS $filter)
		{
			$sql.= " AND ".$filter;
		}
		if ($order !="")
		{
			$sql .=" ORDER BY " . $order;
		}
		if ($limit!="")
		{
			$sql .=" LIMIT ".$limit;
		}
		$rs = $myDB->query($sql);
		$_objects = Array();
		$cname = "PhenotypeContent_".$con_id;
		while ($row = mysql_fetch_assoc($rs))
		{
			$myCO = new $cname;
			$myCO->init($row);
			$_objects[$myCO->id]=$myCO;
		}
		return ($_objects);
	}


	public static function getByPermalink($con_id,$permalink,$status=true,$lng_id=1)
	{
		if (trim($permalink)=="")
		{
			return false;
		}

		global $myDB;
		global $myDB;
		$lng_id=(int)$lng_id;
		$con_id=(int)$con_id;

		$sql = "SELECT * FROM content_data WHERE con_id=".$con_id;
		$sql .= " AND dat_permalink".$lng_id."='".mysql_real_escape_string($permalink)."'";
		switch ($status)
		{
			case true:
				$sql .=" AND dat_status=1";
				break;
			case false:
				$sql .=" AND dat_status=0";
				break;
		}
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=1)
		{
			return false;
		}
		$cname = "PhenotypeContent_".(int)$con_id;
		$row = mysql_fetch_assoc($rs);
		$myCO = new $cname;
		$myCO->init($row);
		return $myCO;

	}

	public static function getRecordsPaged($con_id,$page,$itemsperpage,$status=true,$order="dat_bez",$_filter=array())
	{
		$limit = ($page-1)*$itemsperpage.",".$itemsperpage;
		return self::getRecords($con_id,$status=true,$order,$_filter,$limit);
	}

	public function countRecords($con_id,$status=null,$where="")
	{
		global $myDB;
		$con_id = (int)$con_id;
		$sql = "SELECT COUNT(*) AS C FROM content_data WHERE con_id=".$con_id;
		switch ($status)
		{
			case null:
				break;
			case 1:
				$sql .=" AND dat_status=1";
				break;
			case 0:
				$sql .=" AND dat_status=0";
				break;
		}
		if ($where!=0)
		{
			$sql .= " AND ";
		}
		$rs=$myDB->query($sql);
		if ($rs)
		{
			$row = mysql_fetch_assoc($rs);
			return $row["C"];
		}
		throw new Exception("Error while counting records");

	}
}