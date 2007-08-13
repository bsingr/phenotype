<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
class PhenotypeIncludeStandard
{
	var $id;

	//function PhenotypeInclude($id,$params="")

	public function __construct($p1="",$p2="")
	{
		if (get_class ($this)=="PhenotypeInclude")
		{
			// Abw�rtskompatibel PT 2.1
			$p1 = (int)$p1;
			$this->id = $p1;
			$this->params = $p2;
			// Abw�rtskompatibel PT 2.0
			$this->html = $p2;
		}
		else // ab PT 2.2
		{
			// Wir sind in einer abgeleiteten Klassen
			$this->params = $p1;
			// Abw�rtskompatibel PT 2.0
			$this->html = $p1;
		}
	}

	function initRendering()
	{
		global $myPT;
		$myPT->startbuffer();
	?> 
     $mySmarty = new Smarty();
	 global $myDB;
     global $myPT;
     global $myPage;
     
     $mySmarty->compile_dir = SMARTYCOMPILEPATH;		 
	 $mySmarty->clear_all_assign();
     $sql = "SELECT * FROM include_template WHERE inc_id = " . $this->id . " ORDER BY tpl_id";
     $rs = $myDB->query($sql);
     while ($row_itp=mysql_fetch_array($rs))
     {
	    $tpl = $row_itp["tpl_bez"];
	    $dateiname =  $myPT->getTemplateFileName(PT_CFG_INCLUDE, $this->id, $row_itp["tpl_id"]);
	    $$tpl = $dateiname;
	 }	 
	<?
	$code = $myPT->stopbuffer();
	return $code;
	}


	function execute()
	{
		if (get_class ($this)=="PhenotypeInclude")
		{
			// Abw�rtskomaptibel zu PT 2.0 - 2.1
			$cname = "PhenotypeInclude_".$this->id;
			$myInc = new $cname($this->params);
			$html = $myInc->execute();
		}
		else
		{
			global $myPT;
			$myPT->startBuffer();
			$this->display();
			$html = $myPT->stopBuffer();
			return $html;
		}
		return ($html);
	}

	function renderXML()
	{

		global $myPT;
		$myPT->startBuffer();
		$this->displayXML();
		$xml = $myPT->stopBuffer();
		$test = '<?xml version="1.0" encoding="iso-8859-1" ?>'.$xml;
		if (@simplexml_load_string($test))
		{
			return $xml;
		}
		else
		{
			return "<error>XML for include ".$this->id." not wellformed.</error>";
		}
	}

	function display()
	{
	}

	function displayXML()
	{
		global $myPT;
		global $myDB;
		$sql = "SELECT * FROM include WHERE inc_id=".$this->id;
		$rs=$myDB->query($sql);
		$row = mysql_fetch_array($rs);
  	?>
	<include inc_id="<?=$this->id?>" type="<?=$myPT->xmlencode($row["inc_bez"])?>">
	<content>
	</content>
  	</include>
  	<?
	}




	function rawXMLExport($inc_id=-1)
	{
		global $myDB;
		global $myPT;

		if ($inc_id==-1)
		{
			$inc_id = $this->id;
		}

		$sql = 'SELECT * FROM include WHERE inc_id='. $inc_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);


		$file= APPPATH ."includes/PhenotypeInclude_"  .$inc_id . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
		<inc_id>'.$myPT->getX($row['inc_id']).'</inc_id>
		<inc_bez>'.$myPT->getX($row['inc_bez']).'</inc_bez>		
		<inc_rubrik>'.$myPT->getX($row['inc_rubrik']).'</inc_rubrik>
		<inc_description>'.$myPT->getX($row['inc_description']).'</inc_description>
		<inc_usage_layout>'.$myPT->getX($row['inc_usage_layout']).'</inc_usage_layout>
		<inc_usage_includecomponent>'.$myPT->getX($row['inc_usage_includecomponent']).'</inc_usage_includecomponent>
		<inc_usage_page>'.$myPT->getX($row['inc_usage_page']).'</inc_usage_page>
	</meta>
	<script>'.$myPT->getX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM include_template WHERE inc_id = ' 	. $inc_id . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $row["tpl_id"]);
			$buffer = @file_get_contents($file);
			$xml .= '<template access="'.$myPT->getX($row['tpl_bez']).'">'.$myPT->getX($buffer).'</template>'."\n";
		}


		$xml.='   	</templates>
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
			$inc_id = (int)utf8_decode($_xml->meta->inc_id);

			// Zun�chst evtl. vorhanden alte Templates l�schen

			$sql = "SELECT * FROM include_template WHERE inc_id = " . $inc_id . " ORDER BY tpl_id";
			$rs = $myDB->query($sql);
			while ($row_ttp=mysql_fetch_array($rs))
			{
				$dateiname = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $row_ttp["tpl_id"]);
				@unlink($dateiname);
			}
			$sql = "DELETE FROM include_template WHERE inc_id = " . $inc_id;
			$myDB->query($sql);

			// Jetzt die eigentliche Klasse
			$dateiname = APPPATH . "includes/PhenotypeInclude_"  .$inc_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM include WHERE inc_id = " . $inc_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("inc_id",$inc_id,DB_NUMBER);
			$inc_bez = (string)utf8_decode($_xml->meta->inc_bez);
			$mySQL->addField("inc_bez",$inc_bez);
			$inc_description = (string)utf8_decode($_xml->meta->inc_description);
			$mySQL->addField("inc_description",$inc_description);
			$inc_rubrik = (string)utf8_decode($_xml->meta->inc_rubrik);
			$mySQL->addField("inc_rubrik",$inc_rubrik);

			$inc_usage = (int)utf8_decode($_xml->meta->inc_usage_layout);
			$mySQL->addField("inc_usage_layout",$inc_usage);

			$inc_usage = (int)utf8_decode($_xml->meta->inc_usage_includecomponent);
			$mySQL->addField("inc_usage_includecomponent",$inc_usage);

			$inc_usage = (int)utf8_decode($_xml->meta->inc_usage_page);
			$mySQL->addField("inc_usage_page",$inc_usage);

			$sql = $mySQL->insert("include");
			$myDB->query($sql);


			$script = (string)utf8_decode($_xml->script);

			$file = APPPATH . "includes/PhenotypeInclude_"  .$inc_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			// Templates anlegen

			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)utf8_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
				$mySQL->addField("inc_id",$inc_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("include_template");
				$myDB->query($sql);
				$tpl_id = mysql_insert_id();
				$html = (string)utf8_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_INCLUDE, $inc_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
			}


			
			return $inc_id;

		}
		else
		{
			return (false);
		}
	}

}
?>