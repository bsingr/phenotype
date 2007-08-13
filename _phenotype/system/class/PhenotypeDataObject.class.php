<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeDataObjectStandard
{

	public $props = Array ();
	public $bez="";


	function __construct($bez ="",$forceBuild=false)
	{
		if ($bez!="")
		{
			$this->loadData($bez,$forceBuild);
		}
	}

	function loadData($bez,$forceBuild=false)
	{
		global $myDB;

		if ($forceBuild)
		{
			$this->clearData($bez);
		}

		$sql = "DELETE FROM dataobject WHERE dao_ttl <" . time() ." AND dao_ttl <>0";
		$myDB->query($sql);

		$sql = "SELECT * FROM dataobject WHERE dao_bez='". mysql_escape_string($bez)."'";
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			$p = strpos($bez,"system.");
			if ($p===0)
			{
				return $this->buildSystemData(substr($bez,7));
			}
			else
			{
				return $this->buildData($bez);
			}
		}
		else
		{
			$row = mysql_fetch_array($rs);
			$this->props = unserialize($row["dao_props"]);
		}
		$this->bez = $bez;
	}

	function buildData($bez)
	{
		switch ($bez)
		{
			default:
				return false;
				break;
		}

		$this->storeData($bez);
		return true;
	}


	function clearData ($bez)
	{
		global $myDB;

		$sql = "DELETE FROM dataobject WHERE dao_bez='". mysql_escape_string($bez)."'";
		$myDB->query($sql);
	}

	function storeData ($bez,$seconds = 0)
	{
		global $myDB;
		$mySQL = new SqlBuilder();
		$mySQL->addField("dao_bez",$bez);
		$mySQL->addField("dao_props",serialize($this->props));
		if ($seconds!=0)
		{
			$seconds = time()+$seconds;
		}
		$mySQL->addField("dao_ttl",$seconds,DB_NUMBER);
		$mySQL->addField("dao_date",time(),DB_NUMBER);
		$sql = $mySQL->insert("dataobject");
		$this->clearData($bez);
		$myDB->query($sql);
	}


	function store($seconds=0)
	{
		if ($this->bez=="")
		{
			echo "Kein Dataobjekt geladen";
			return;
		}
		$this->storeData($this->bez,$seconds);
	}

	function set($bez, $val)
	{
		$this->props[$bez] = $val;
	}

	function clear($bez)
	{
		unset ($this->props[$bez]);
	}

	function get($bez="")
	{
		return @ ($this->props[$bez]);
	}

	function getI($bez)
	{
		return @ (int) ($this->props[$bez]);
	}

	function getD($bez, $decimals)
	{
		return sprintf("%01.".$decimals."f", @ ($this->props[$bez]));
	}
	function getHTML($bez)
	{
		//return @htmlentities(stripslashes($this->props[$bez]));
		return @ htmlentities($this->props[$bez]);
	}

	function getH($bez)
	{
		return $this->getHTML($bez);
	}

	function getHBR($bez)
	{
		$html = nl2br($this->getHTML($bez));
		// Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
		$html = str_replace(chr(10), "", $html);
		$html = str_replace(chr(13), "", $html);
		return ($html);
	}

	function getURL($bez)
	{
		return @ urlencode($this->props[$bez]);
	}

	function getU($bez)
	{
		return @ utf8_encode($this->props[$bez]);
	}

	function getS($bez)
	{
		return (string) @ addslashes($this->props[$bez]);
	}

	function getA($bez)
	{
		$v = @ $this->props[$bez];
		if (ini_get("magic_quotes_gpc") == 1)
		{
			$v = stripslashes($v);
		}
		$patterns = "/[^a-z0-9A-Z]*/";
		$v = preg_replace($patterns, "", $v);
		return $v;
	}


	function getX($bez)
	{
		global $myPT;
		$s = @ $this->props[$bez];
		return ($myPT->codeX($s));
		
		/*
		$s = @ $this->props[$bez];
		$s = str_replace("&","&#38;",$s);
		$s = str_replace("<","&#60;",$s);
		$s = str_replace(">","&#62;",$s);
		$s = str_replace("'","&#39;",$s);
		$s = str_replace('"',"&#34;",$s);
		$s = str_replace('/',"&#47;",$s);

		return $s;
		*/
	}




	function buildSystemData($bez)
	{
		global $myDB;

		if (substr($bez,0,22)=="lightbox_media_usr_id_")
		{
			$usr_id = substr($bez,22);
			$bez="lightbox_media";
		}

		if (substr($bez,0,22)=="export_content_con_id_")
		{
			$con_id = substr($bez,22);
			$bez="export_content";
		}
		if (substr($bez,0,20)=="export_media_grp_id_")
		{
			$grp_id = substr($bez,20);
			$bez="export_media";
		}

		if (substr($bez,0,20)=="export_pages_grp_id_")
		{
			$grp_id = substr($bez,20);
			$bez="export_pages";
		}
		
		if (substr($bez,0,22)=="export_tickets_sbj_id_")
		{
			$sbj_id = substr($bez,22);
			$bez="export_tickets";
		}		

		switch ($bez)
		{
			case "lightbox_media":
				$bez="lightbox_media_usr_id_".$usr_id;
				$props = Array("objects"=>Array());
				break;
			case "export_pages":
				$bez="export_pages_grp_id_".$grp_id;
				$_ids = Array();
				$sql = "SELECT pag_id FROM page WHERE grp_id = " . $grp_id;
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$_ids[] = $row["pag_id"];
				}
				$props = Array("objects"=>$_ids);
				break;
			case "export_content":
				$bez="export_content_con_id_".$con_id;
				$_ids = Array();
				$sql = "SELECT dat_id FROM content_data WHERE con_id = " . $con_id;
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$_ids[] = $row["dat_id"];
				}
				$props = Array("objects"=>$_ids);
				break;
			case "export_media":
				$bez="export_media_grp_id_".$grp_id;
				$_ids = Array();
				$sql = "SELECT med_id FROM media WHERE grp_id = " . $grp_id;
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$_ids[] = $row["med_id"];
				}
				$props = Array("objects"=>$_ids);
				break;
			case "export_tickets":
				$bez="export_tickets_sbj_id_".$sbj_id;
				$_ids = Array();
				$sql = "SELECT tik_id FROM ticket WHERE sbj_id = " . $sbj_id;
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$_ids[] = $row["tik_id"];
				}
				$props = Array("objects"=>$_ids);
				break;
			default:
				return false;
				break;
		}

		$this->props = $props;
		$this->storeData("system.".$bez);
		return true;
	}


}