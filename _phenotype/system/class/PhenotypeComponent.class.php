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
class PhenotypeComponentStandard
{
	var $tool_type;

	var $id; // Datensatz-ID

	var $pag_id;
	var $block_nr;
	var $pos;
	var $bez;
	var $visible;

	var $props = Array();

	var $myLayout = -1; // Layoutobjekt muss on Demand initalisiert werden

	var $formid;

	var $loaded = 0;


	function setDefaultProperties()
	{
		// macht in der Superklasse keinen Sinn
	}

	function init($row)
	{
		$this->id =$row["dat_id"];
		$this->formid = "com_" . $row["dat_id"] . "_";
		$this->pag_id =$row["pag_id"];
		$this->ver_id =$row["ver_id"];
		$this->dat_id =$row["dat_id"];
		$this->block_nr =$row["dat_blocknr"];
		$this->pos =$row["dat_pos"];
		$this->visible =$row["dat_visible"];
		if ($row["dat_comdata"]!="")
		{
			$this->props = unserialize($row["dat_comdata"]);
		}
		else
		{
			$this->setDefaultProperties();
		}
	}


	public function __construct($id = -1)
	{
		$id = (int) $id;
		if ($id != -1)
		{
			$this->load($id);
		}
	}


	function load($id)
	{
		$id = (int) $id;
		global $myDB;
		$sql = "SELECT * FROM sequence_data WHERE dat_id = ".$id." AND com_id = ".$this->tool_type;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs) != 0)
		{
			$row = mysql_fetch_array($rs);
			$this->init($row);
		} else
		{
			$this->setDefaultProperties();
			$this->loaded = 0;
		}
	}

	function set($bez,$val)
	{
		$this->props[$bez] = $val;
		//print_r ($this->props);
	}

	function clear($bez)
	{
		unset($this->props[$bez]);
	}

	// Setzt den Wert aus dem Formular
	function fset($bez,$val="")
	{
		if ($val==""){$val=$bez;}
		$this->props[$bez] = @stripslashes($_REQUEST[$this->formid . $val]);
		//print_r ($this->props);
	}


	function get ($bez)
	{
		return @($this->props[$bez]);
		//return @stripslashes($this->props[$bez]);
	}


	function fget($val)
	{
		return @stripslashes($_REQUEST[$this->formid . $val]);
		//print_r ($this->props);
	}

	// veraltet
	function getQ ($bez)
	{
		return ereg_replace('"',"&quot;",stripslashes($this->props[$bez]));
	}

	function getHTML ($bez)
	{
		return @htmlentities(stripslashes($this->props[$bez]));
	}

	function getH ($bez)
	{
		return $this->getHTML($bez);
	}

	function getHBR ($bez)
	{
		$html = nl2br($this->getHTML($bez));
		// Falsch fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
		$html = str_replace (chr(10),"",$html);
		$html = str_replace (chr(13),"",$html);
		return ($html);
	}

	function getI ($bez)
	{
		return @(int)($this->props[$bez]);
		//return @stripslashes($this->props[$bez]);
	}

	function getD ($bez,$decimals)
	{
		return sprintf("%01.".$decimals."f",@($this->props[$bez]));
	}


	function getURL($bez)
	{
		return @ urlencode($this->props[$bez]);
	}

	function getU($bez)
	{
		return @ utf8_encode($this->props[$bez]);
	}

	function getX($bez)
	{
		global $myPT;
		$s = @ $this->props[$bez];
		return ($myPT->codeX($s));
		/*
		$s = str_replace("&","&#38;",$s);
		$s = str_replace("<","&#60;",$s);
		$s = str_replace(">","&#62;",$s);
		$s = str_replace("'","&#39;",$s);
		$s = str_replace('"',"&#34;",$s);
		$s = str_replace('/',"&#47;",$s);

		return $s;
		*/
	}

	// Nur aus dem Backend heraus verwendbar
	function addnew($pag_id,$ver_id,$dat_id_content,$block_nr,$dat_id=0)
	{
		global $myDB;
		$lng_id = $_SESSION["lng_id"];
		$usr_id = $_SESSION["usr_id"];

		$this->setDefaultProperties();

		$this->pag_id = $pag_id;
		$this->ver_id = $ver_id;
		$this->dat_id_content = $dat_id_content;

		$this->block_nr = $block_nr;

		if ($dat_id==0)
		{
			$sql = "UPDATE sequence_data SET dat_pos = dat_pos + 1 WHERE  pag_id = " . $pag_id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
			$myDB->query($sql);
			$this->pos = 1;
		}
		else
		{
			$sql = "SELECT dat_pos FROM sequence_data WHERE dat_id=" . $dat_id . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$pos = $row["dat_pos"] +1;
			$this->pos = $pos;
			$sql = "UPDATE sequence_data SET dat_pos = dat_pos + 1 WHERE  pag_id = " . $pag_id . " AND dat_blocknr=" . $block_nr . " AND dat_pos >=" . $pos . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
			$myDB->query($sql);
		}

		$this->visible = 1;

		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_id",$this->pag_id);
		$mySQL->addField("ver_id",$this->ver_id);
		$mySQL->addField("lng_id",$lng_id);
		$mySQL->addField("usr_id",$usr_id);
		$mySQL->addField("dat_id_content",$this->dat_id_content);
		$mySQL->addField("dat_blocknr",$this->block_nr);
		$mySQL->addField("dat_pos",$this->pos);
		$mySQL->addField("dat_editbuffer",1);
		$mySQL->addField("dat_visible",$this->visible);
		$mySQL->addField("com_id",$this->tool_type);
		$s = serialize($this->props);
		$mySQL->addField("dat_comdata",$s);
		$sql = $mySQL->insert("sequence_data");
		$myDB->query($sql);
		$this->id = mysql_insert_id();

		return $this->pos;
	}

	// Nur aus dem Backend heraus verwendbar
	function moveup()
	{
		global $myDB;

		$lng_id = $_SESSION["lng_id"];
		$usr_id = $_SESSION["usr_id"];

		$sql = "UPDATE sequence_data SET dat_pos = " . ($this->pos) ." WHERE  pag_id = " . $this->pag_id . " AND dat_blocknr=" . $this->block_nr . " AND dat_pos =" . ($this->pos-1) . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);
		$sql = "UPDATE sequence_data SET dat_pos = " . ($this->pos-1) ." WHERE  dat_id = " . $this->id . " AND  dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);

		$this->pos = $this->pos -1;

	}

	// Nur aus dem Backend heraus verwendbar
	function movedown()
	{
		global $myDB;

		$lng_id = $_SESSION["lng_id"];
		$usr_id = $_SESSION["usr_id"];

		$sql = "UPDATE sequence_data SET dat_pos = " . ($this->pos) ." WHERE  pag_id = " . $this->pag_id . " AND dat_blocknr=" . $this->block_nr . " AND dat_pos =" . ($this->pos+1) . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);
		$sql = "UPDATE sequence_data SET dat_pos = " . ($this->pos+1) ." WHERE  dat_id = " . $this->id . " AND  dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);

		$this->pos = $this->pos +1;
	}

	// Nur aus dem Backend heraus verwendbar
	function delete()
	{
		global $myDB;

		$lng_id = $_SESSION["lng_id"];
		$usr_id = $_SESSION["usr_id"];

		$sql = "DELETE FROM sequence_data WHERE dat_id = " . $this->id . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);
		$sql = "UPDATE sequence_data SET dat_pos = dat_pos - 1 WHERE  pag_id = " . $this->pag_id . " AND dat_blocknr=" . $this->block_nr . " AND dat_pos >" . $this->pos . " AND dat_editbuffer=1 AND lng_id=" . $lng_id . " AND usr_id=".$usr_id;
		$myDB->query($sql);
	}

	function edit()
	{
		// macht in der Superklasse keinen Sinn
  ?>
  <?php
	}

	function update()
	{
		// macht in der Superklasse keinen Sinn
	}

	function renderXML()
	{
		global $myPT;
		$myPT->startBuffer();
		$noxmlcheck = $this->displayXML();
		$xml = $myPT->stopBuffer();
		if ($noxmlcheck) // Rückgabe soll ungeprüft als "richtig" betrachtet
		{
			return $xml;
		}
		$test = '<?xml version="1.0" encoding="iso-8859-1" ?>'.$xml;
		if (@simplexml_load_string($test))
		{
			return $xml;
		}
		else
		{
			return "<error>XML for component ".$this->tool_type." not wellformed.</error>";
		}
	}

	function displayXML()
	{
		global $myPT;
  	?>
	<component com_id="<?php echo $this->tool_type ?>" type="<?php echo $myPT->xmlencode($this->bez) ?>">
	<content>
	<?php
	foreach ($this->props AS $k=>$v)
	{
		$k = $myPT->xmlencode($k);
		$v = $myPT->xmlencode($v);
	?>
	<var name="<?php echo $k ?>" value="<?php echo $v ?>"/>
	<?php
	}
	?>
	</content>
  	</component>
  	<?php
  	return true;
	}


	function setFullSearch()
	{
		return "";
	}

	function store()
	{
		global $myDB;
		$s = serialize($this->props);
		$mySQL = new SQLBuilder();
		$mySQL->addField("dat_comdata",$s);
		$mySQL->addField("dat_fullsearch",$this->setFullSearch());
		$mySQL->addField("dat_visible",$this->visible,DB_NUMBER);
		$sql = $mySQL->update("sequence_data","dat_id=" . $this->id . " AND dat_editbuffer=1");
		$myDB->query($sql);
		return $s;
	}

	function initRendering()
	{
		global $myPT;
		$myPT->startbuffer();
	?> 
     $mySmarty = new Smarty();
		 global $myDB;
		 global $myPT;
		 
     $mySmarty->compile_dir = SMARTYCOMPILEPATH;		 
	 $mySmarty->clear_all_assign();
     $sql = "SELECT * FROM component_template WHERE com_id = " . $this->tool_type;
     $rs = $myDB->query($sql);
     while ($row_ttp=mysql_fetch_array($rs))
     {
	    $tpl = $row_ttp["tpl_bez"];
	    $dateiname =  $myPT->getTemplateFileName(PT_CFG_COMPONENT, $this->tool_type, $row_ttp["tpl_id"]);
	    $$tpl = $dateiname;
		 }	 
	<?php
	$code = $myPT->stopbuffer();

	return $code;
	}

	function form_textfield($bez,$name,$val,$x=300)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_text($bez,$name,$val,$x);
	}

	function form_textarea($bez,$name,$val,$r=6,$x=395)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_textarea($bez,$name,$val,$r,$x);
	}

	function form_select($bez,$name,$options,$x=200,$br=1)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_select($bez,$name,$options,$x,$br);
	}


	function iconbar_new()
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$this->myLayout->iconbar_new();
	}

	function iconbar_addEntry($url1,$url2,$val,$alt="")
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$this->myLayout->iconbar_addEntry($url1,$url2,$val,$alt);
	}

	function form_iconbar($name,$val)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		$this->myLayout->iconbar_draw($name,$val,"editform");
	}


	function form_genericselector($name,$text_add,$text_reset,$text_select,$status,$js_add,$js_select,$js_reset,$html_hidden,$html_form,$html_start="",$html_select="",$html_reset="")
	{
		// Status 0 = leer
		// Status 1 = gefüllt
		// Derzeit nicht aus der Layoutklasse
  	?>
  	
  	<?php if ($status==0){ ?>
  	<table width="408" border="0" cellpadding="0" cellspacing="0" >
    <tr>
    <td nowrap>
    <a class="bausteineLink" href="javascript:<?php echo $js_add ?>"><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"><?php echo $text_add ?></a>
    </td>
    </tr>
    </table>
    <?php } ?>
    <table id="<?php echo $this->formid ?><?php echo $name ?>select" width="408" border="0" cellpadding="0" cellspacing="0" <?php if($status==0){ ?>style="visibility: hidden;display:none"<?php } ?>>
    <tr>
    <td nowrap>
    <a class="bausteineLink" href="javascript:<?php echo $js_select ?>"><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"><?php echo $text_select ?></a>
    </td>
    </tr>
    </table>
    <?php if ($status==1){ ?>
    <table id="<?php echo $this->formid ?><?php echo $name ?>reset" width="408" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td nowrap>
    <a href="javascript:<?php echo $js_reset ?>" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"><?php echo $text_reset ?></a>
    </td>
    </tr>
    </table>    
    <?php } ?>
    <table id="<?php echo $this->formid ?><?php echo $name ?>panel_form" <?php if($html_form==""){ ?>style="visibility: hidden;display:none"<?php } ?> width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
    <tr >
    <td>
    <?php echo $html_form ?>
    </td>
    </tr>
    </table>    
    <table id="<?php echo $this->formid ?><?php echo $name ?>panel_start" <?php if($html_start==""){ ?>style="visibility: hidden;display:none"<?php } ?> width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
    <tr >
    <td >
    <?php echo $html_start ?>
    </td>
    </tr>
    </table>
    <table id="<?php echo $this->formid ?><?php echo $name ?>panel_select" style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
    <tr >
    <td>
    <?php echo $html_select ?>
    </td>
    </tr>
    </table>
    <table id="<?php echo $this->formid ?><?php echo $name ?>panel_reset" style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
    <tr >
    <td>
    <?php echo $html_reset ?>
    </td>
    </tr>
    </table>    
    </br>
    <table style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
    <tr >
    <td>
    <?php echo $html_hidden ?>
    </td>
    </tr>
    </table><br clear="all">
    
  	<?php
	}

	function form_download($name,$med_id)
	{
		$this->form_document($name,$med_id);
	}

	function form_document($name,$med_id)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_document($name,$med_id);
	}




	function form_documentupload($name,$med_id)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_document($name,$med_id);
	}



	function form_image($name,$img_id,$folder="-1",$changefolder=1,$x=0,$y=0,$alt="",$align="links",$mode=2)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_image($name,$img_id,$folder,$changefolder,$x,$y,$alt,$align,$mode);
	}

	function form_imageupload($name,$img_id,$alt,$align)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_imageupload($name,$img_id,$alt,$align);
	}



	function form_link($name,$bez,$url,$target)
	{
		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$name = $this->formid . $name;
		echo $this->myLayout->workarea_form_link($name,$bez,$url,$target);
	}



	function form_Richtext($name,$val,$cols=80,$rows=10)
	{
		global $myLayout;
		global $myApp;
		$name = $this->formid . $name;
		$myLayout->form_Richtext($name,$myApp->richtext_prefilter($val),$cols,$rows);
	}

	function form_FullRichtext($name,$val,$cols=80,$rows=10)
	{
		global $myLayout;
		$name = $this->formid . $name;
		$myLayout->form_FullRichtext($name,$val,$cols,$rows);
	}

	function form_HTML($name,$val,$cols=80,$rows=10)
	{
		global $myLayout;
		$name = $this->formid . $name;
		$filename_bak = TEMPPATH ."htmlarea/~" . uniqid("") . ".tmp";
		$fp = fopen ($filename_bak,"w");
		fputs($fp,$val);
		fclose ($fp);
		@chown ($filename_bak,UMASK);
		$myLayout->form_HTMLTextarea($name,$filename_bak,$cols,$rows,$mode="HTML",410);
		unlink ($filename_bak);
	}


	function form_ddpositioner($name,$val, $quantity, $methodname,$background=1)
	{
		?>
		<p>
		 <?php
		 $anzahl = $quantity;
		 $token = $this->formid.$name;
		 $kette = "";
		 for ($j = 1; $j <= $anzahl; $j ++)
		 {
		 	$kette .= ",".$j;
		 }
		 if (strpos($val,","))
		 {
		 	$_position = explode(",",$val);
		 }
		 else 
		 {
		 	$_position = Array();
		 }

		 	if (count($_position)!=$anzahl) // Array an die neue Anzahl anpassen
		 	{
		 		$_newposition = Array();
		 		foreach ($_position AS $k => $v)
		 		{
		 			if ($v<=$anzahl)
		 			{
		 				$_newposition[]=$v;
		 			}
		 		}
		 		for ($j = 1; $j <= $anzahl; $j ++)
		 		{
		 			if (!in_array($j,$_newposition))
		 			{
		 				$_newposition[] = $j;
		 			}
		 		}
		 		$_position = $_newposition;
		 	}
		 
			 ?>
			 
			 <input type="hidden" name="<?php echo $token ?>" value="<?php echo implode(",",$_position) ?>"/>
			 <input type="hidden" name="<?php echo $token ?>_posstart" value="<?php echo implode(",",$_position) ?>"/>
			 <?php
			 $this->displayDHtmlWZJavascript($token, $anzahl,16);

			 $mname = $methodname;
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 ?>
			 <div id="<?php echo $token.$j ?>" style="width:404px;position:relative;background:url(img/moveit.gif) top left no-repeat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:<?php echo $token ?>movedown(<?php echo $j ?>)"><img src="img/b_down2.gif" width="18" height="18" border="0"></a>
        	 <a href="javascript:<?php echo $token ?>moveup(<?php echo $j ?>)"><img src="img/b_up2.gif" width="18" height="18" border="0"></a><br/>
        	 <?php if ($background==1)
        	 {
        	 ?>
        	 <div style="background-color:#D1D6DB;padding:2px;width:404px;"><p><?php echo $this->$mname($_position[$j-1]); ?></p></div></div><br/>
        	 <?php
        	 }else{
			 ?>
        	 <p><?php echo $this->$mname($_position[$j-1]); ?></p></div><br/>
        	 <?php
        	 }
        	 ?>
			 <script type="text/javascript">ADD_DHTML("<?php echo $token ?><?php echo $j ?>"+VERTICAL+TRANSPARENT);</script>
			 <?php
			 }
			 ?>
			 <script type="text/javascript">
			 <?php
			 for ($j = 1; $j <= $anzahl; $j ++)
			 {
			 	?>
			 	dd.elements.<?php echo $token ?><?php echo $j ?>.setDropFunc(<?php echo $token ?>dropTopListItem);
			 	<?php
			 }
			 ?>
			 </script>
			 </p>
			 <?php
	}


	function displayDHtmlWZJavascript($token, $anzahl,$spacer)
	{
		global $myLayout;
		
		if ($myLayout->dhtmlwz_init == 0)
		{
?>
			 <script type="text/javascript">
			 SET_DHTML(CURSOR_MOVE);
			 </script>
			 <?php


			 $myLayout->dhtmlwz_init = 1;
		}
?>
  	<script type="text/javascript">
  	function <?php echo $token ?>moveup(item)
  	{

  		order = <?php echo $token ?>determineOrder();
  		currentpos = item;
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			if(order[i]==item)
  			{
  				currentpos =i+1;
  			}
  		}
  		if (currentpos>1)
  		{
  			// Item an der übergeordneten Position bestimmen
  			changepos = currentpos-1;
  			changeitem = order[changepos-1];

  			citem ="<?php echo $token ?>" + item;
  			y = dd.elements[citem].y;
  			x = dd.elements[citem].x;

  			citem2 ="<?php echo $token ?>" + changeitem;
  			y2 = dd.elements[citem2].y;
  			x2 = dd.elements[citem2].x;

  			dd.elements[citem].moveTo(x2,y2);
  			dd.elements[citem2].moveTo(x,y);
  		}

  		<?php echo $token ?>dropTopListItem();
  	}

  	function <?php echo $token ?>movedown(item)
  	{
  		order = <?php echo $token ?>determineOrder();
  		currentpos = item;
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			if(order[i]==item)
  			{
  				currentpos =i+1;
  			}
  		}
  		if (currentpos<<?php echo $anzahl ?>)
  		{
  			// Item an der übergeordneten Position bestimmen
  			changepos = currentpos+1;
  			changeitem = order[changepos-1];

  			citem ="<?php echo $token ?>" + item;
  			y = dd.elements[citem].y;
  			x = dd.elements[citem].x;

  			citem2 ="<?php echo $token ?>" + changeitem;
  			y2 = dd.elements[citem2].y;
  			x2 = dd.elements[citem2].x;

  			dd.elements[citem].moveTo(x2,y2);
  			dd.elements[citem2].moveTo(x,y);
  		}
  		<?php echo $token ?>dropTopListItem();
  	}

  	function <?php echo $token ?>determineOrder()
  	{
  		order = new Array();

  		for (i=1;i<=<?php echo $anzahl ?>;i++)
  		{
  			citem ='<?php echo $token ?>' + i;
  			y = dd.elements[citem].y;
  			order[i-1]=y + "#" + i;

  		}

  		order.sort
  		(
  		function(a,b)
  		{
  			return (parseInt(a)-parseInt(b));
  		}
  		);

  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			p = order[i].indexOf("#")
  			order[i]=order[i].substr(p+1);
  		}
  		return (order);
  	}

  	function <?php echo $token ?>storeOrder()
  	{
  		posstart = document.forms.editform.<?php echo $token ?>_posstart.value;
  		startorder = posstart.split(",");

  		
  		order = <?php echo $token ?>determineOrder();
  		
  		s="";
  		for (i=0;i<<?php echo $anzahl ?>;i++)
  		{
  			s = s+ "," + startorder[order[i]-1];
  		}
  	
  		document.forms.editform.<?php echo $token ?>.value=s.substr(1);
    }
  	


  	function  <?php echo $token ?>dropTopListItem()
  	{

  		order = <?php echo $token ?>determineOrder();

  		citem ="<?php echo $token ?>1";
  		y = dd.elements[citem].defy;
  		for (i=1;i<=<?php echo $anzahl ?>;i++)
  		{
  			citem ="<?php echo $token ?>" + order[i-1];
  			x = dd.elements[citem].x;
  			h = dd.elements[citem].h;
   			dd.elements[citem].moveTo(x,y);
  			y=y+h+<?php echo $spacer ?>;
  		}
  		<?php echo $token ?>storeOrder();

  	}


  	//-->

    </script>
    <?php


	}

	function rawXMLExport($tool_type=-1)
	{
		global $myDB;
		global $myPT;

		if ($tool_type==-1)
		{
			$tool_type = $this->tool_type;
		}

		$sql = 'SELECT * FROM component WHERE com_id='. $tool_type;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);


		$file= APPPATH ."components/PhenotypeComponent_"  .$tool_type . ".class.php";

		$buffer = @file_get_contents($file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>	
		<com_id>'.$myPT->getX($row['com_id']).'</com_id>
		<com_bez>'.$myPT->getX($row['com_bez']).'</com_bez>		
		<com_rubrik>'.$myPT->getX($row['com_rubrik']).'</com_rubrik>
		<com_description>'.$myPT->getX($row['com_description']).'</com_description>
	</meta>
	<script>'.$myPT->getX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM component_template WHERE com_id = ' 	. $tool_type . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $tool_type, $row["tpl_id"]);
			$buffer = @file_get_contents($file);
			$xml .= '<template access="'.$myPT->getX($row['tpl_bez']).'">'.$myPT->getX($buffer).'</template>'."\n";
		}


		$xml.='   	</templates>
	<componentgroups>';

		$sql = 'SELECT * FROM  componentgroup LEFT JOIN component_componentgroup ON componentgroup.cog_id = component_componentgroup.cog_id WHERE com_id='.$tool_type;
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml.='<group>
			<cog_id>'.$myPT->getX($row['cog_id']).'</cog_id>
			<cog_bez>'.$myPT->getX($row['cog_bez']).'</cog_bez>
			<cog_description>'.$myPT->getX($row['cog_description']).'</cog_description>
			<cog_pos>'.$myPT->getX($row['cog_pos']).'</cog_pos>
		  </group>';

		}
		$xml .='</componentgroups>
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
			$com_id = (int)utf8_decode($_xml->meta->com_id);

			// Zunächst den evtl. vorhandenen alten Baustein löschen

			$sql = "SELECT * FROM component_template WHERE com_id = " . $com_id . " ORDER BY tpl_id";
			$rs = $myDB->query($sql);
			while ($row_ttp=mysql_fetch_array($rs))
			{
				$dateiname = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $com_id, $row_ttp["tpl_id"]);
				@unlink($dateiname);
			}
			$sql = "DELETE FROM component_template WHERE com_id = " . $com_id;
			$myDB->query($sql);

			// Jetzt die eigentliche Komponente
			$dateiname = APPPATH . "components/PhenotypeComponent_"  .$com_id . ".class.php";
			@unlink($dateiname);

			$sql = "DELETE FROM component WHERE com_id = " . $com_id;
			$myDB->query($sql);

			// Und wieder bzw. neu anlegen

			$mySQL = new SQLBuilder();
			$mySQL->addField("com_id",$com_id,DB_NUMBER);
			$com_bez = (string)utf8_decode($_xml->meta->com_bez);
			$mySQL->addField("com_bez",$com_bez);
			$com_description = (string)utf8_decode($_xml->meta->com_description);
			$mySQL->addField("com_description",$com_description);
			$com_rubrik = (string)utf8_decode($_xml->meta->com_rubrik);
			$mySQL->addField("com_rubrik",$com_rubrik);

			$sql = $mySQL->insert("component");
			$myDB->query($sql);


			$script = (string)utf8_decode($_xml->script);

			$file = APPPATH . "components/PhenotypeComponent_"  .$com_id . ".class.php";

			$fp = fopen ($file,"w");
			fputs ($fp,$script);
			fclose ($fp);
			@chmod ($file,UMASK);

			// Templates anlegen

			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)utf8_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
				$mySQL->addField("com_id",$com_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("component_template");
				$myDB->query($sql);
				$tpl_id = mysql_insert_id();
				$html = (string)utf8_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $com_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
			}

			// Bausteingruppen anlegen
			foreach ($_xml->componentgroups->group AS $_xml_group)
			{
				$cog_id = (int)utf8_decode($_xml_group->cog_id);
				$cog_bez = (string)utf8_decode($_xml_group->cog_bez);
				$cog_description = (string)utf8_decode($_xml_group->cog_description);
				$cog_pos = (int)utf8_decode($_xml_group->cog_pos);

				$sql = "DELETE FROM componentgroup WHERE cog_id=".$cog_id;
				$myDB->query($sql);

				$sql = "DELETE FROM component_componentgroup WHERE cog_id=".$cog_id . " AND com_id=".$com_id;
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("cog_id",$cog_id,DB_NUMBER);
				$mySQL->addField("cog_bez",$cog_bez);
				$mySQL->addField("cog_description",$cog_description);
				$mySQL->addField("cog_pos",$cog_pos,DB_NUMBER);
				$sql = $mySQL->insert("componentgroup");
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("cog_id",$cog_id,DB_NUMBER);
				$mySQL->addField("com_id",$com_id,DB_NUMBER);
				$sql = $mySQL->insert("component_componentgroup");
				$myDB->query($sql);
				$myPT->customizeToolkit($cog_id);
			}


			return $com_id;

		}
		else
		{
			return (false);
		}
	}

	function render($context)
	{

	}
}
?>