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
class PhenotypeDataObjectStandard extends PhenotypeBase
{
	const dao_application = 0;
	const dao_system = 1;

	public $dao_type = self::dao_application;

	public $bez="";
	public $paramhash="";
	public $clearOnEdit = 0;

	public $stored = false;
	public $loaded = false;
	public $changed= false;

	function __construct($bez,$params=array(), $forceBuild=false,$clearOnEdit = false)
	{
		global $myDB;

		if ($bez=="")
		{
			throw new Exception ("Cannot create unnamed dataobject");
		}
		$this->bez = $bez;

		$paramhash="";
		foreach ($params AS $k => $v)
		{
			$paramhash  .="#".$k."#".$v;
		}
		$this->paramhash = $paramhash;


		if ($clearOnEdit)
		{
			// this information is stored in the database and utilized elsewhere
			$this->clearOnEdit = 1;
		}

		if (!$forceBuild)
		{
			$sql = "SELECT * FROM dataobject WHERE dao_bez='". mysql_real_escape_string($bez)."' AND dao_params ='".mysql_real_escape_string($paramhash)."' AND dao_type=".
			$this->dao_type;
			$rs = $myDB->query($sql,"DAO \"".$bez."\": initialization");

			if (mysql_num_rows($rs)==0)
			{
				$forceBuild = true;
			}
			else
			{
				$row = mysql_fetch_array($rs);
				$this->_props = unserialize($row["dao_props"]);
				$this->loaded = true;
			}
			$this->bez = $bez;
		}

		if ($forceBuild)
		{
			$method = "buildData" .$bez;
			if (method_exists($this,$method))
			{
				call_user_func(array($this,$method),$params);
			}
		}

	}


	function isLoaded()
	{
		return (boolean)$this->loaded;
	}

	function hasChanged()
	{
		return (boolean)$this->changed;
	}

	function __destruct()
	{
		if ($this->changed == true AND $this->stored==false)
		{
			trigger_error("Dataobject " . $this->bez. " (".$this->paramhash.")  changed, but not stored.",E_USER_NOTICE);
		}
	}


	function storeIfChanged($seconds=0,$clearOnEdit=null)
	{
		if ($this->hasChanged())
		{
			$this->store($seconds,$clearOnEdit);
		}
	}

	function store($seconds=0,$clearOnEdit=null)
	{
		global $myDB;

		/**
     * Since the Phenotype destructor can access data objects, it is possible that the global database object already has been destroyed
     * If so, the database must be reconnected.
     */
		if (!is_object($myDB))
		{
			$myDB = new PhenotypeDatabase();
			$myDB->connect();
		}

		$context="DAO \"".$this->bez."\": stored.";

		if ($clearOnEdit!==null)
		{
			$this->clearOnEdit = 1;
		}

		if ($seconds!=0)
		{
			$seconds = time()+$seconds;
			$context = $context ." Valid until ". (date('d.m.Y H:i',$seconds));
		}

		$sql = "DELETE FROM dataobject WHERE dao_bez='". mysql_real_escape_string($this->bez)."' AND dao_params ='".$this->paramhash."'";
		$myDB->query($sql,$context);

		$mySQL = new SqlBuilder();
		$mySQL->addField("dao_bez",$this->bez);
		$mySQL->addField("dao_params",$this->paramhash);
		$mySQL->addField("dao_type",$this->dao_type);
		$mySQL->addField("dao_props",serialize($this->_props));

		$mySQL->addField("dao_ttl",$seconds,DB_NUMBER);
		$mySQL->addField("dao_date",time(),DB_NUMBER);
		$mySQL->addField("dao_clearonedit",$this->clearOnEdit,DB_NUMBER);
		$sql = $mySQL->insert("dataobject");
		$myDB->query($sql);
		$this->stored = true;
	}

	function clearData()
	{
		global $myDB;

		$sql = "DELETE FROM dataobject WHERE dao_bez='". mysql_real_escape_string($this->bez)."'";
		$rs = $myDB->query($sql,"DAO \"".$bez."\": deleting all data");
	}

}
