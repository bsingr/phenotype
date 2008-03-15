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
class PhenotypeExtraStandard
{

	public $id;

	protected $props = Array ();

	protected $myLayout = -1; // Layoutobjekt muss on Demand initalisiert werden

	public $configure_tab  =1;

	function set($bez, $val)
	{
		$this->props[$bez] = $val;
	}

	function clear($bez)
	{
		unset ($this->props[$bez]);
	}

	// Setzt den Wert aus dem Formular
	function fset($bez, $val = "")
	{
		global $myRequest;
		if ($val == "")
		{
			$val = $bez;
		}
		$this->props[$bez] = $myRequest->get($this->formid.$val);
	}

	function get($bez)
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
		return @ htmlentities(stripslashes($this->props[$bez]));
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
		return @ addslashes($this->props[$bez]);
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
	function __construct()
	{
		global $myDB;

		if ((int)$this->id !=0)
		{

			$sql = "SELECT ext_props FROM extra WHERE ext_id=".$this->id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			if ($row["ext_props"] != "")
			{
				$this->props = unserialize($row["ext_props"]);
			}
		}
	}

	function store()
	{
		global $myDB;
		$s = serialize($this->props);
		$mySQL = new SQLBuilder();
		$mySQL->addField("ext_props", $s);
		$sql = $mySQL->update("extra", "ext_id=".$this->id);
		$myDB->query($sql);
		return $s;
	}

	function form_textfield($input, $bez, $size = 300)
	{
		$val = $this->get($bez);
		$name = "ext_".$bez;

		if ($this->myLayout == -1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$html = $this->myLayout->workarea_form_text("", $name, $val, $size);
		$this->myLayout->workarea_row_draw($input, $html);
	}

	function form_textarea($input, $bez, $x = 395, $rows = 6)
	{
		$val = $this->get($bez);
		$name = "ext_".$bez;

		if ($this->myLayout == -1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$html = $this->myLayout->workarea_form_textarea("", $name, $val, $rows, $x);
		$this->myLayout->workarea_row_draw($input, $html);
	}

	function form_selectbox($input, $bez, $_options, $x = 100)
	{
		$val = $this->get($bez);
		$name = "ext_".$bez;

		if ($this->myLayout == -1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}

		global $myPT;
		$options = $myPT->buildOptionsByNamedArray($_options, $val);

		$html = $this->myLayout->workarea_form_select("", $name, $options, $x);
		$this->myLayout->workarea_row_draw($input, $html);
	}

	function form_multiselectbox($input, $bez, $_options, $x, $y)
	{
		// ToDO
	}

	function form_link($input, $bez)
	{
		$linkname = $this->get($bez."bez");
		$url = $this->get($bez."url");
		$target = $this->get($bez."target");

		$name = "ext_".$bez;

		if ($this->myLayout == -1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$html = $this->myLayout->workarea_form_link($name, $linkname, $url, $target);
		$this->myLayout->workarea_row_draw($input, $html);
	}

	function form_checkbox($input, $bez, $text)
	{
		$val = $this->get($bez);
		$name = "ext_".$bez;

		if ($this->myLayout == -1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}

		$html = $this->myLayout->workarea_form_checkbox("", $name, $val, $text);
		$html .= '<input type="hidden" name="extcb_'.$bez.'" value="1">';
		$this->myLayout->workarea_row_draw($input, $html);
	}



	function fetchSetupForm()
	{
		global $myRequest;
		foreach ($_REQUEST AS $k => $v)
		{
			if (strpos($k, "ext_") === 0)
			{
				$pkey = substr($k,4);
				$this->set($pkey, $myRequest->get($k));
			}
		}
		// Zweite Schleife für Checkboxen, um die deaktivierten zu ermitteln
		foreach ($_REQUEST AS $k => $v)
		{
			if (strpos($k, "extcb_") === 0)
			{
				$pkey = substr($k,6);
				if (!$myRequest->check("ext_".$pkey))
				{
					$this->set($pkey,0);
				}
			}
		}
	}

	function storeConfig()
	{
		$this->store();
	}

	function displayStart()
	{
	}

	function displaySetup()
	{
	}

	function displayInfo()
	{
	}

	function execute($myRequest)
	{
	}


	function rawXMLExport($ext_id=-1)
	{
		global $myDB;
		global $myPT;

		if ($ext_id==-1)
		{
			$ext_id=$this->id;
		}

		$sql = 'SELECT * FROM extra WHERE ext_id='. $ext_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);

		$props = $row['ext_props'];

		$file= APPPATH ."extras/PhenotypeExtra_"  .$ext_id . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ext_id>'.$myPT->codeX($row['ext_id']).'</ext_id>
		<ext_bez>'.$myPT->codeX($row['ext_bez']).'</ext_bez>		
		<ext_rubrik>'.$myPT->codeX($row['ext_rubrik']).'</ext_rubrik>
		<ext_description>'.$myPT->codeX($row['ext_description']).'</ext_description>
	</meta>
	<script>'.$myPT->codeX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM extra_template WHERE ext_id = ' 	. $ext_id . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_EXTRA, $ext_id, $row["tpl_id"]);
			$buffer = @file_get_contents($file);
			$xml .= '<template access="'.$myPT->codeX($row['tpl_bez']).'">'.$myPT->codeX($buffer).'</template>'."\n";
		}


		$xml.='   	</templates>
		<ext_props>'.base64_encode($props).'</ext_props>
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
			$ext_id = (int)utf8_decode($_xml->meta->ext_id);

			// Zunächst evtl. vorhanden alte Templates löschen

			$sql = "SELECT * FROM extra_template WHERE ext_id = " . $ext_id . " ORDER BY tpl_id";
			$rs = $myDB->query($sql);
			while ($row_ttp=mysql_fetch_array($rs))
			{
				$dateiname = $myPT->getTemplateFileName(PT_CFG_EXTRA, $ext_id, $row_ttp["tpl_id"]);
				@unlink($dateiname);
			}
			$sql = "DELETE FROM extra_template WHERE ext_id = " . $ext_id;
			$myDB->query($sql);

			// Jetzt die eigentliche Klasse
			$dateiname = APPPATH . "extras/PhenotypeExtra_"  .$ext_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM extra WHERE ext_id = " . $ext_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("ext_id",$ext_id,DB_NUMBER);
			$ext_bez = (string)utf8_decode($_xml->meta->ext_bez);
			$mySQL->addField("ext_bez",$ext_bez);
			$ext_description = (string)utf8_decode($_xml->meta->ext_description);
			$mySQL->addField("ext_description",$ext_description);
			$ext_rubrik = (string)utf8_decode($_xml->meta->ext_rubrik);
			$mySQL->addField("ext_rubrik",$ext_rubrik);
			$ext_props = (string)utf8_decode($_xml->ext_props);
			$mySQL->addField("ext_props",base64_decode($ext_props));


			$sql = $mySQL->insert("extra");
			$myDB->query($sql);


			$script = (string)utf8_decode($_xml->script);

			$file = APPPATH . "extras/PhenotypeExtra_"  .$ext_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			// Templates anlegen

			$tpl_id = 1;
			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)utf8_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
		        $mySQL->addField("tpl_id",$tpl_id,DB_NUMBER);
				$mySQL->addField("ext_id",$ext_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("extra_template");
				$myDB->query($sql);
				$html = (string)utf8_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_EXTRA, $ext_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
				$tpl_id++;
			}


			return $ext_id;

		}
		else
		{
			return (false);
		}
	}

}
?>