<?php
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
<?php
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeActionStandard extends Phenotype
{
	public $id;
	public $retry = 3600; // Nach einer Stunde resetten
	public $row;

	function __construct()
	{
		global $myDB;

		if ((int)$this->id!=0)
		{
			$sql= "SELECT * FROM action WHERE act_id = " . $this->id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);

			$this->row = $row;
		}
	}

	function execute()
	{

	}

	function runAction()
	{
		global $myPT;
		global $myDB;

		// Feststellen, ob die Aktion laufen soll
		if ($this->row["runstatus"]==1)
		{
			if ($this->row["act_laststart"]> (time() - $this->retry))
			{
				return;
			}
		}

		if ($this->row["act_nextrun"]>time())
		{
			return;
		}

		// Starten
		$mySQL = new SQLBuilder();
		$mySQL->addField("act_runstatus",1,DB_NUMBER);
		$mySQL->addField("act_laststart",time(),DB_NUMBER);
		$sql = $mySQL->update("action","act_id=" . $this->id);
		$myDB->query($sql);

		$this->execute();

		// Erfolgreich gelaufen
		$mySQL = new SQLBuilder();
		$mySQL->addField("act_runstatus",0,DB_NUMBER);
		$mySQL->addField("act_lastrun",time(),DB_NUMBER);
		$sql = $mySQL->update("action","act_id=" . $this->id);
		$myDB->query($sql);
	}

	function nextRunAddTime($s)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("act_nextrun",time()+$s,DB_NUMBER);
		$sql = $mySQL->update("action","act_id=" . $this->id);
		$myDB->query($sql);
	}

	function nextRunSetTime($t)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("act_nextrun",$t,DB_NUMBER);
		$sql = $mySQL->update("action","act_id=" . $this->id);
		$myDB->query($sql);
	}


	function rawXMLExport($act_id=-1)
	{
		global $myDB;
		global $myPT;

		if ($act_id==-1)
		{
			$act_id=$this->id;
		}

		$sql = 'SELECT * FROM action WHERE act_id='. $act_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);



		$file= APPPATH ."actions/PhenotypeAction_"  .$act_id . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<act_id>'.$myPT->getX($row['act_id']).'</act_id>
		<act_bez>'.$myPT->getX($row['act_bez']).'</act_bez>		
		<act_description>'.$myPT->getX($row['act_description']).'</act_description>
		<act_status>'.$myPT->getX($row['act_status']).'</act_status>	
	</meta>
	<script>'.$myPT->getX($buffer).'</script>
</phenotype>';

		return $xml;
	}
	
	function rawXMLImport($buffer)
	{
		global $myDB;
		global $myPT;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			$act_id = (int)utf8_decode($_xml->meta->act_id);

			// Die alte Klassendatei löschen
			$dateiname = APPPATH . "actions/PhenotypeAction_"  .$act_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM action WHERE act_id = " . $act_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("act_id",$act_id,DB_NUMBER);
			$act_bez = (string)utf8_decode($_xml->meta->act_bez);
			$mySQL->addField("act_bez",$act_bez);
			$act_description = (string)utf8_decode($_xml->meta->act_description);
			$mySQL->addField("act_description",$act_description);
			$act_status = (int)utf8_decode($_xml->meta->act_status);
			$mySQL->addField("act_status",$act_status,DB_NUMBER);			
		
			$sql = $mySQL->insert("action");
			$myDB->query($sql);


			$script = (string)utf8_decode($_xml->script);

			$file = APPPATH . "actions/PhenotypeAction_"  .$act_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			

			return $act_id;

		}
		else
		{
			return (false);
		}
	}
}
?>