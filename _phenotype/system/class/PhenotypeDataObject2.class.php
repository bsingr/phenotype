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

	define ("DAO_TYPE_SYSTEM", "DO_SYSTEM");
	define ("DAO_TYPE_CONTENT", "DO_CONTENT");
	define ("DAO_TYPE_LEGACY", "DO_LEGACY");
	define ("DAO_TYPE_MISC", "DO_MISC");

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeDataObject2Standard
{

	private $id;
	public $props;
	public $daoType;
	public $daoDatType;
	public $daoDatId ;
	private $isLoaded;
	private $buildTS;
	private $expireTS;

	function __construct($daoType, $daoDatType=0, $daoDatId=0, $forceBuild=false) {
		
		$this->id = 0;
		$this->props = Array();
		$this->daoType = $daoType;
		$this->daoDatType = $daoDatType;
		$this->daoDatId = $daoDatId;
		$this->isLoaded = false;
		$this->buildTS = 0;
		$this->expireTS = 0;
		
		if (! $forceBuild) {
			$this->loadData();
		}
		
		if (! $this->isLoaded) {
			
			switch ($daoType) {
				
				case DAO_TYPE_SYSTEM:
					if ($daoDatType) {
						$this->buildSystemData();
					}
					break;
				
				case DAO_TYPE_CONTENT:
					if ($daoDatType) {
						$this->buildContentData();
					}
					break;
				
				case DAO_TYPE_LEGACY:
					// then doDatType holds the value of bez from the old constructor
					if ($daoDatType) {
						$this->buildLegacyData();
					}
					break;
				
				case DAO_TYPE_MISC:
					// do whatever the application needs
					$this->buildMiscData();
					break;
			}
			if ($this->isLoaded) {
				$this->store();
			}
		}
		
	}
	
	/* build system data objects
	 *
	 * @args: daoDatType(String) -> type of system data
	 *				daoDatId(String) -> key value for more exact specification of daoDatType (if needed)
	 *
	 * @return: void
	 */
	private function buildSystemData() {
		$this->buildSystemDataOld($this->daoDatType . $this->daoDatId);
	}
	
	/* build data objects of content objects
	 *
	 * @args: daoDatType(String) -> content id
	 *				daoDatId(String) -> content dat_id or 0 if data object is for the content type (not for single objects)
	 *
	 */
	private function buildContentData() {
		
		$cname= "PhenotypeContent_" . $this->daoDatType;
		if ($this->daoDatId) {
			$daoDatId = $this->daoDatId;
		} else {
			$daoDatId = -1; // cause with 0 PhenotypeContent will try to create an object :-(
		}
		$myCO = new $cname($daoDatId);
		list ($data, $ttl) = $myCO->getDaoData();
		$this->set("data", $data);
		
		$this->buildTS = time();
		$this->expireTS = $this->buildTS + $ttl;
		
		//echo("content dao build: expireTS: ". strftime("%T", $this->expireTS));
		
		$this->isLoaded = true;
	}
	
	/* function header for application specific extensions.
	 * just overwrite the function.
	 *
	 * please ensure that you have enough values to select your data
	 */ 
	private function buildMiscData($daoDatType, $daoDatId) {
	}
		
	
	/* loads data from an already precalculated data object out of DB
	 *
	 * works with object properties daoType, daoDatType and daoDatId
	 *
	 * set $this->isLoaded if load was successfull
	 */
	function loadData() {
		global $myDB;

		$sql = "SELECT * FROM dataobject2 WHERE dao_type='". mysql_escape_string($this->daoType) ."' AND dao_dat_type='". mysql_escape_string($this->daoDatType) ."' AND dao_dat_id = ". $this->daoDatId ." AND dao_expire_ts >= " . time() ."";
		$rs = $myDB->query($sql);
		
		if (mysql_num_rows($rs)) {
			$row = mysql_fetch_array($rs);
			$this->id = $row["id"];
			$this->props = unserialize($row["dao_props"]);
			$this->buildTS = $row["dao_expire_ts"];
			$this->expireTS = $row["dao_expire_ts"];
			$this->isLoaded = true;
		}
	}

	function store() {
		global $myDB;
		
		$mySQL = new SqlBuilder();
		$mySQL->addField("dao_type", $this->daoType);
		$mySQL->addField("dao_dat_type", $this->daoDatType);
		$mySQL->addField("dao_dat_id", $this->daoDatId, DB_NUMBER);
		$mySQL->addField("dao_props", serialize($this->props) );
		$mySQL->addField("dao_build_ts", $this->buildTS, DB_NUMBER);
		$mySQL->addField("dao_expire_ts", $this->expireTS, DB_NUMBER);
		$sql = $mySQL->insert("dataobject2");
		$rs = $myDB->query($sql);
		if ($rs) {
			$this->id = mysql_insert_id();
		}
	}
	
	public static function onUpdate($daoType, $daoDatType, $daoDatId) {
		global $myDB;
		
		switch ($daoType) {
			case DAO_TYPE_SYSTEM:
				PhenotypeDataObject2::onSystemUpdate($daoDatType, $daoDatId);
				break;
			
			case DAO_TYPE_CONTENT:
				PhenotypeDataObject2::onContentUpdate($datDatType, $daoDatId);
				break;
				
			case DAO_TYPE_MISC:
				PhenotypeDataObject2::onMiscUpdate($datDatType, $daoDatId);
				break;
			
			case DAO_TYPE_LEGACY:
				PhenotypeDataObject2::onLegacyUpdate($datDatType);
				break;
			
		}
	}
	
	/* deletes old system data objects
	 * is bloody stupid at the moment
	 * :TODO: make it more intelligent
	 *
	 * @args: $daoDatType(String) -> type of system object updated
	 *				$daoDatId(Int) -> id to specify the element
	 */
	private static function onSystemUpdate($daoDatType, $daoDatId) {
		global $myDB;
		
		$sql = "DELETE FROM dataobject2 WHERE dao_type='". DAO_TYPE_SYSTEM ."'";
		$rs = $myDB->query($sql);
		if (! $rs) {
			PhenotypeLog::log("problem deleting expired data objects", PT_LOGFACILITY_SYS, PT_LOGLVL_ERROR);
		}
	}
	
	/* deletes old content data objects
	 *
	 * @args: $daoDatType(String) -> type of content object updated (con_id)
	 *				$daoDatId(Int) -> id to specify the element (dat_id of content object)
	 *
	 * if you have more complex dependencies either overwrite this method in
	 * the application class or call more events at the storage of your content
	 *
	 */
	private static function onContentUpdate($daoDatType, $daoDatId) {
		global $myDB;
		
		$sql = "DELETE FROM dataobject2 WHERE dao_type='". DAO_TYPE_CONTENT ."' AND dao_dat_type='". mysql_escape_string($daoDatType) ."' AND ( dao_dat_id=$daoDatId OR dao_dat_id=0)";
		$rs = $myDB->query($sql);
		if (! $rs) {
			PhenotypeLog::log("problem deleting expired data objects", PT_LOGFACILITY_SYS, PT_LOGLVL_ERROR);
		}
	}
	
	/* deletes old misc data objects
	 *
	 * @args: $daoDatType(String) -> element name of the data object
	 *				$daoDatId(Int) -> id to specify the element
	 */
	private static function onMiscUpdate($daoDatType, $daoDatId) {}
	
	/* deletes old legacy data objects
	 *
	 * @args: $daoDatType(String) -> element name of the data object in legacy style
	 */
	private static function onLegacyUpdate($daoDatType) {
		global $myDB;
		
		$sql = "DELETE FROM dataobject2 WHERE dao_type='". DAO_TYPE_LEGACY ."' AND dao_dat_type='". mysql_escape_string($daoDatType) ."'";
		$rs = $myDB->query($sql);
		if (! $rs) {
			PhenotypeLog::log("problem deleting expired data objects", PT_LOGFACILITY_SYS, PT_LOGLVL_ERROR);
		}
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
	}



	/*
	 * deprecated, should be replaced by new code in buildSystemData
	 *
	 */
	function buildSystemDataOld($bez)
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