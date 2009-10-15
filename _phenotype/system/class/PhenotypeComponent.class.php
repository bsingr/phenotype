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
class PhenotypeComponentStandard extends PhenotypeBase
{
	/**
	 * Unique Page Component Identifier
	 *
	 */
	public $com_id = 0;

	/**
	 * Page Component Name
	 *
	 */
	public $name="";

	/**
	 * old component identifier, please use com_id instead
	 *
	 * 
	 * @var integer
	 */
	var $tool_type;

	var $id; // Datensatz-ID

	var $pag_id;
	var $block_nr;
	var $pos;
	var $bez;
	var $visible;

	var $myLayout = -1; // Layoutobjekt muss on Demand initalisiert werden

	var $formid;

	var $loaded = 0;


	/**
	 * Array with information about the editing form
	 *
	 * @var Array
	 */
	protected $_form = Array ();


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
			$this->_props = unserialize($row["dat_comdata"]);
		}
		else
		{
			$this->setDefaultProperties();
		}
	}

	/**
	 * to be implemented by application classes
	 *
	 * @param integer $context
	 */
	public function initForm($context)
	{

	}

	public function __construct($id = -1)
	{
		// internally we use tool_type and bez instead of com_id and name
		// TODO: accomplish new variable names
		if ((int)($this->com_id)!=0)
		{
			$this->tool_type = (int)($this->com_id);
		}
		else 
		{
			$this->com_id = $this->tool_type;
		}
		if ($this->name!="")
		{
			$this->bez = $this->name;
		}
		else 
		{
			$this->name = $this->bez;;
		}
		// -- //

		$id = (int) $id;
		if ($id != -1)
		{
			$this->load($id);
		}
		global $myDebug;
		$myDebug->notifyPageComponentUsage($this->com_id);
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


	/**
	 * 

	 *
	 * @param unknown_type $bez
	 * @param unknown_type $val
	 */
	function fset($property)
	{
		global $myRequest;
		$formname = $this->formid . $property;
		echo $formname;
		$this->set($property,$myRequest->get($formname));
	}



	/**
	 * 
	 *
	 * @param string name of the form field to be fetched (without formid)
	 * @return mixed
	 */
	function fget($property)
	{
		global $myRequest;
		$formname = $this->formid . $property;
		return $myRequest->get($formname);
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
		$s = serialize($this->_props);
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


	function edit($context)
	{
		$this->displayEditForm($context);
	}


	function update($context)
	{
		$this->fetchEditForm($context);
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
	foreach ($this->_props AS $k=>$v)
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
		$s = serialize($this->_props);
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
     $mySmarty = new PhenotypeSmarty();
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
		 $mySmarty->assign("component",$this);
	<?php
	$code = $myPT->stopbuffer();

	return $code;
	}

	function displayEditForm($context=0)
	{
		$this->initForm();
		for ($i = 0; $i < count($this->_form); $i ++)
		{
			$_fconfig = $this->_form[$i];
			if (array_key_exists("form_method",$_fconfig))
			{
				$methodname = "_".$_fconfig["form_method"]."_display";
				if (method_exists($this,$methodname))
				{
					$this->$methodname($_fconfig);
				}
				else
				{
					throw new Exception($_fconfig["form_method"] ." implemented imperfectly. Missing display method: ".$methodname);
				}
			}
		}
	}

	function fetchEditForm($context=0)
	{
		global $myPT;
		$this->initForm($context);
		for ($i = 0; $i < count($this->_form); $i ++)
		{
			$_fconfig = $this->_form[$i];
			if (array_key_exists("form_method",$_fconfig))
			{
				$methodname = "_".$_fconfig["form_method"]."_fetch";
				if (method_exists($this,$methodname))
				{
					$this->$methodname($_fconfig);
				}
				else
				{
					throw new Exception($_fconfig["form_method"] ." implemented imperfectly. Missing update method: ".$methodname);
				}
			}
		}
	}

	public function form_textfield($title, $property, $width)
	{
		$this->_form[] = array(
		"form_method" =>"form_textfield",
		"property" =>$property,
		"title" =>$title,
		"width" =>$width
		);
	}

	protected function _form_textfield_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$width = $_fconfig["width"];

		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;
		echo $this->myLayout->workarea_form_text($title,$formname,$this->get($property),$width);
	}

	protected function _form_textfield_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$this->set($property,$this->fget($property));
	}
	
	public function form_headline($title,$margin_top = true)
	{
		$this->_form[] = array(
		"form_method" =>"form_headline",
		"title" =>$title,
		"margin_top" =>$margin_top
		);
	}

	protected function _form_headline_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$margin_top = $_fconfig["margin_top"];
		if ($margin_top)
		{
			echo '<div style="margin-bottom:5px;margin-top:20px"><b>'. $title . "</b></div>";
		}
		else 
		{
			echo '<div style="margin-bottom:5px;"><b>'. $title . "</b></div>";
		}
	}

	protected function _form_headline_fetch($_fconfing)
	{
	}
	public function form_textarea($title, $property, $width, $rows)
	{
		$this->_form[] = array(
		"form_method" =>"form_textarea",
		"property" =>$property,
		"title" =>$title,
		"width" =>$width,
		"rows" =>$rows
		);
	}

	protected function _form_textarea_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$width = $_fconfig["width"];
		$rows = $_fconfig["rows"];

		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;
		echo $this->myLayout->workarea_form_textarea($title,$formname,$this->get($property),$rows,$width);
	}

	protected function _form_textarea_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$this->set($property,$this->fget($property));
	}


	public function form_enumeration($title, $property, $start = 3, $max=99,$rows=1)
	{
		$this->_form[] = array(
		"form_method" =>"form_enumeration",
		"property" =>$property,
		"title" =>$title,
		"start" =>$start,
		"max" =>$max,
		"rows"=>$rows
		);
	}

	protected function _form_enumeration_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$start = (int)$_fconfig["start"];
		$max = (int)$_fconfig["max"];
		$rows= (int)$_fconfig["rows"];


		$formname = $this->formid . $property;

		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		?>
		<input type="hidden" name="<?php echo $formname ?>_count" value="<?php echo $this->getI($property."_count",$start)?>"/>
		<table width="408" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td nowrap class="tableBausteineBackground">
		<?php
		for ($i=1;$i<=$this->getI($property."_count",$start);$i++)
		{
			if ($rows==1)
			{
		    ?> 
			<input name="<?php echo $formname ?>_item<?php echo $i ?>" type="text" class="input" style="width: 355px" value="<?php echo $this->getH($property."_item".$i) ?>">&nbsp;
		    <?php if ($this->getI($property."_count",$start)>1){ ?><input type="image" src="img/b_minus.gif" alt="<?php echo localeH("Remove Bullet Point")?>" width="18" height="18" border="0" align="absmiddle" name="<?php echo $formname ?>_minus_r<?php echo $i ?>"><?php } ?> <input type="image" src="img/b_plus.gif" alt="<?php echo localeH("Add Bullet Point")?>" width="18" height="18" border="0" align="absmiddle" name="<?php echo $formname ?>_plus_r<?php echo $i ?>"><br> 
      		<?php 
			}
			else 
			{
				?> 
				<textarea name="<?php echo $formname ?>_item<?php echo $i ?>" class="input" style="width: 355px" rows="<?php echo $rows-1?>"><?php echo $this->getH($property."_item".$i) ?></textarea>&nbsp;
			    <?php if ($this->getI($property."_count",$start)>1){ ?><input type="image" src="img/b_minus.gif" alt="<?php echo localeH("Remove Bullet Point")?>" width="18" height="18" border="0" align="absmiddle" name="<?php echo $formname ?>_minus_r<?php echo $i ?>"><?php } ?> <input type="image" src="img/b_plus.gif" alt="<?php echo localeH("Add Bullet Point")?>" width="18" height="18" border="0" align="absmiddle" name="<?php echo $formname ?>_plus_r<?php echo $i ?>"><br> 
	      		<?php 
			}
		}
      	?> 
      	</td> 
      	</tr> 
      	</table> 
      	<?php 
	}


	protected function _form_enumeration_fetch($_fconfig)
	{
		global $myRequest;
		$property = $_fconfig["property"];
		$c = $this->fget($property."_count");
		$this->set($property."_count",$c);
		$put=1;
		for ($i=1;$i<=$c;$i++)
		{
			$this->set($property."_item".$put,$this->fget($property."_item".$i));
			$put++;

			// New bullet point?
			$fname = $this->formid .$property. "_plus_r" . $i . "_x";

			if ($myRequest->check($fname))
			{
				$this->set($property."_count",$c+1);
				$this->set($property."_item".$put,"");
				$put++;
			}
			// -- New bullet point?

			// Bullet point removal ?
			$fname = $this->formid .$property. "_minus_r" . $i . "_x";
			if ($myRequest->check($fname))
			{
				$this->clear($property."_item".$c);
				$this->set($property."_count",$c-1);
				$put--;

			}
			// -- Bullet point removal ?
		}
	}

	public function form_selectbox($title, $property, $_options, $addzerodots=true)
	{
		$this->_form[] = array(
		"form_method" =>"form_selectbox",
		"property" =>$property,
		"title" =>$title,
		"options"=>$_options,
		"addzerodots" =>(boolean)$addzerodots
		);
	}

	protected function _form_selectbox_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$addzerodots = $_fconfig["addzerodots"];
		$_options = $_fconfig["options"];

		$options="";
		if ($addzerodots==true){$options ='<option value="0">...</option>';}
		foreach ($_options as $key => $val)
		{
			$selected = "";
			if ($key == $this->get($property))
			{
				$selected = ' selected="selected"';
			}
			$options .='<option value="'.codeH($key).'"'. $selected .'>'.codeH($val).'</option>';
		}

		$formname = $this->formid . $property;
		echo codeH($title).'<br/><select name="'.$formname.'" class="listmenu">'.$options."</select><br/>";
	}


	protected function _form_selectbox_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$this->set($property,$this->fget($property));
	}

	/**
     * selectbox for selection of content object records
	 *
	 * @param string $title
	 * @param string $property
	 * @param integer|array(integer) $con_id
	 * @param boolean $addzerodots
	 * @param boolean $statuscheck
	 * @param string $sql_where
	 */
	public function form_content_selectbox($title, $property, $con_id, $addzerodots=true, $statuscheck=true,$sql_where="")
	{
		// realized a special editon of form_selectbox
		// just populate the _options-Array

		global $myDB;

		if (is_array($con_id))
		{
			$sql  = "SELECT dat_id,dat_bez FROM content_data WHERE con_id IN (".join(",",$con_id).")";
		}
		else 
		{
		$con_id = (int)$con_id;

		$sql  = "SELECT dat_id,dat_bez FROM content_data WHERE con_id=".$con_id;
		}
		if ($statuscheck==true)
		{
			$sql .=" AND dat_status=1";
		}

		if ($sql_where !="")
		{
			$sql .=" AND " . $a[6];
		}

		$sql .= " ORDER BY dat_bez";

		$rs = $myDB->query($sql);

		$_options = Array();

		while ($row=mysql_fetch_assoc($rs))
		{
			$_options[$row["dat_id"]]=$row["dat_bez"];
		}

		$this->_form[] = array(
		"form_method" =>"form_selectbox",
		"property" =>$property,
		"title" =>$title,
		"options"=>$_options,
		"addzerodots" =>(boolean)$addzerodots
		);
	}


	public function form_link($title, $property, $link_name=true, $link_target=true, $link_pageselector=false, $link_text=false, $link_popup=false, $link_source=false, $link_type=false, $link_type_options=Array())
	{

		$_options = Array(
		"link_name"=>$link_name,
		"link_target"=>$link_target,
		"link_pageselector"=>$link_pageselector,
		"link_text"=>$link_text,
		"link_popup"=>$link_popup,
		"link_source"=>$link_source,
		"link_type"=>$link_type,
		"link_type_options"=>$link_type_options);

		$this->_form[] = array(
		"form_method" =>"form_link",
		"property" =>$property,
		"title" =>$title,
		"options"=>$_options
		);
	}

	protected function _form_link_display($_fconfig)
	{

		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$addzerodots = $_fconfig["addzerodots"];
		$_options = $_fconfig["options"];


		if ($_options["link_name"]!==false){$linkname = $this->get($property."_name");}else{$linkname=false;}
		if ($_options["link_target"]!==false){$target = $this->get($property."_target");}else{$target=false;}
		if ($_options["link_text"]!==false){$linktext = $this->get($property."_text");}else{$linktext=false;}
		if ($_options["link_popup"]!==false){$popup_x = $this->get($property."_x");$popup_y = $this->get($property."_y");}else{$popup_x=false;$popup_y=false;}
		if ($_options["link_source"]!==false){$linksource = $this->get($property."_source");}else{$linksource=false;}
		if ($_options["link_type"]!==false){$linktype = $this->get($property."_linktype");$linktype_options=$_options["link_type_options"];}else{$linktype=false;$linktype_options=false;}
		$pageselector = $_options["link_pageselector"];



		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;

		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';

		
		echo $this->myLayout->workarea_form_link($formname,$linkname, $this->get($property."_url"), $target,$linktext,$linksource,$popup_x,$popup_y,$linktype,$linktype_options,$pageselector);

	}

	protected function _form_link_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$_options = $_fconfig["options"];
		$formname = $this->formid . $property;




		if ($_options["link_popup"]!==false){$popup_x = $this->get($property."_x");$popup_y = $this->get($property."_y");}else{$popup_x=false;$popup_y=false;}
		if ($_options["link_source"]!==false){$linksource = $this->get($property."_source");}else{$linksource=false;}
		if ($_options["link_type"]!==false){$linktype = $this->get($property."_type");$linktype_options=$_options["link_type_options"];}else{$linktype=false;$linktype_options=false;}
		$pageselector = $_options["link_pageselector"];
		
		if ($_options["link_name"]!==false)
		{
			$this->set($property."_name",$this->fget($property."bez"));
		}
		
		$this->set($property."_url",$this->fget($property."url"));
		
		if ($_options["link_target"]!==false)
		{
			$this->set($property."_target",$this->fget($property."target"));
		}
		
		if ($_options["link_text"]!==false)
		{
			$this->set($property."_text",$this->fget($property."text"));
		}	
		
		if ($_options["link_popup"]!==false)
		{
			$this->set($property."_x",$this->fget($property."x"));
			$this->set($property."_y",$this->fget($property."y"));
		}
		else
		{
			$this->clear($property."_x");$this->clear($property."_y");
		}
			
		if ($_options["link_source"]!==false)
		{
			$this->set($property."_source",$this->fget($property."source"));
		}		
		if ($_options["link_type"]!==false)
		{
			$this->set($property."_linktype",$this->fget($property."type"));
		}	
	}


	public function form_richtext($title, $property, $width, $rows, $filter = 1)
	{
		$this->_form[] = array(
		"form_method" =>"form_richtext",
		"property" =>$property,
		"title" =>$title,
		"width" =>(int)$width,
		"rows" =>(int)$rows,
		"filter"=>(boolean)$filter,
		);
	}

	protected function _form_richtext_display($_fconfig)
	{
		global $myApp;

		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$width = $_fconfig["width"];
		$rows = $_fconfig["rows"];
		$filter = $_fconfig["filter"];


		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;
		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		$this->myLayout->form_Richtext($formname,$myApp->richtext_prefilter($this->get($property),$this),$width,$rows);
		echo '<br/>';

	}

	protected function _form_richtext_fetch($_fconfig)
	{
		global $myApp;
		$property = $_fconfig["property"];
		$filter = $_fconfig["filter"];
		$richtext = $this->fget($property);
		if ($filter==true)
		{
			$richtext = $myApp->richtext_strip_tags($richtext);
		}
		$richtext = $myApp->richtext_postfilter($richtext,$this);
		$this->set($property,$richtext);
	}

	/*
	function form_richtext($name,$val,$cols=80,$rows=10)
	{
	global $myLayout;
	global $myApp;
	$name = $this->formid . $name;
	$myLayout->form_Richtext($name,$myApp->richtext_prefilter($val),$cols,$rows);
	}*/

	public function form_html($title, $property, $width, $rows)
	{
		$this->_form[] = array(
		"form_method" =>"form_html",
		"property" =>$property,
		"title" =>$title,
		"width" =>(int)$width,
		"rows" =>(int)$rows
		);
	}

	protected function _form_html_display($_fconfig)
	{
		global $myApp;

		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$width = $_fconfig["width"];
		$rows = $_fconfig["rows"];

		$filename_bak = TEMPPATH ."htmlarea/~" . uniqid("") . ".tmp";
		$fp = fopen ($filename_bak,"w");
		fputs($fp,$this->get($property));
		fclose ($fp);
		@chown ($filename_bak,UMASK);

		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;
		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		$this->myLayout->form_HTMLTextarea($formname,$filename_bak,80,$rows,$mode="HTML",$width);
		echo '<br/>';
		unlink ($filename_bak);
	}

	protected function _form_html_fetch($_fconfig)
	{
		global $myAdm;

		$property = $_fconfig["property"];
		$html = $this->fget($property);
		$html = $myAdm->decodeRequest_HTMLArea($html);
		$this->set($property,$html);
	}

	public function form_imageupload($title,$property,$path="_upload",$grp_id=2)
	{
		$this->_form[] = array(
		"form_method" =>"form_imageupload",
		"property" =>$property,
		"title" =>$title,
		"path"=>$path,
		"grp_id"=>(int)$grp_id
		);
	}

	protected function _form_imageupload_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$img_id= $this->getI($property."_img_id");
		$alt= $this->get($property."_alt");
		$align= $this->get($property."_align");


		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;
		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		echo $this->myLayout->workarea_form_imageupload($formname,$img_id,$alt,$align);
	}

	protected function _form_imageupload_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$path = $_fconfig["path"];
		$grp_id = $_fconfig["grp_id"];
		$formname = $this->formid . $property;


		// this "conversion" is necessary, since the old Phenotye workarea-functions doesnt't have stringent form field namings

		$this->set($property."_img_id",$this->fget($property."img_id"));
		$this->set($property."_alt",$this->fget($property."img_alt"));
		$this->set($property."_align",$this->fget($property."align"));

		if (isset($_FILES[$formname."userfile"]))
		{
			$_file = $_FILES[$formname."userfile"];
			if ($_file["error"]!=UPLOAD_ERR_NO_FILE)
			{
				$myMB = new PhenotypeMediabase();
				$myMB->setMediaGroup($grp_id);
				$img_id = $myMB->importImageFromUrl($path,$_file["tmp_name"],$_file["type"],-1,$_file["name"]);
				if ($img_id!=false)
				{
					$this->set($property."_img_id",(int)$img_id);
				}
				else
				{
					$this->set($property."_img_id",0);
				}
				@unlink ($_file["tmp_name"]);
			}

		}



	}


	public function form_wrap($methodname,$_params = Array ())
	{
		$this->_form[] = array(
		"form_method" =>"form_wrap",
		"method" =>$methodname,
		"params"=>$_params
		);
	}

	protected function _form_wrap_display($_fconfig)
	{
		$mname = $_fconfig["method"];
		$_params = $_fconfig["params"];
		$this->$mname ($_params);
	}

	protected function _form_wrap_fetch($_fconfig)
	{
		// Nothing to do here
	}

	/*
	protected function form_html_display($_fconfig)
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
	*/


	/*	function form_textfield($bez,$name,$val,$x=300)
	{
	if ($this->myLayout==-1)
	{
	$this->myLayout = new PhenotypeAdminLayout();
	}
	$name = $this->formid . $name;
	echo $this->myLayout->workarea_form_text($bez,$name,$val,$x);
	}*/
	/*
	function form_textarea($bez,$name,$val,$r=6,$x=395)
	{
	if ($this->myLayout==-1)
	{
	$this->myLayout = new PhenotypeAdminLayout();
	}
	$name = $this->formid . $name;
	echo $this->myLayout->workarea_form_textarea($bez,$name,$val,$r,$x);
	}
	*/
	/*
	function form_select($bez,$name,$options,$x=200,$br=1)
	{
	if ($this->myLayout==-1)
	{
	$this->myLayout = new PhenotypeAdminLayout();
	}
	$name = $this->formid . $name;
	echo $this->myLayout->workarea_form_select($bez,$name,$options,$x,$br);
	}*/


	/*
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
	*/


	public function form_image_selector($title, $property, $folder="", $changefolder = true, $x = 0, $y = 0,$grp_id=0,$_options=Array())
	{
		$folder=trim($folder);
		if ($folder==""){$folder=-1;}
		$_defaultoptions = Array(
		"versionselect"=>false,
		"altandalign"=>false
		);
		$_options = array_merge($_defaultoptions,$_options);
		$this->_form[] = array(
		"form_method" =>"form_image_selector",
		"property" =>$property,
		"title" =>$title,
		"folder"=>$folder,
		"changefolder"=>(boolean)$changefolder,
		"x"=>(int)$x,
		"y"=>(int)$y,
		"grp_id"=>(int)$grp_id,
		"options"=>$_options,
		);

		if ($grp_id!=0)
		{
			throw new Exception('Sorry parameter $grp_id not yet supported :(. Stick to 0');
		}
	}

	protected function _form_image_selector_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];
		$_options = $_fconfig["options"];

		$img_id= $this->getI($property."_img_id");
		$alt= $this->get($property."_alt");
		$align= $this->get($property."_align");

		$versionselect = (boolean)$_options["versionselect"];

		// There seems to be something wrong. Versionselect deactivated for the moment
		$versionselect = false;
		switch ((boolean)$_options["altandalign"])
		{
			case true:
				$mode=2;
				break;
			default:
				$mode=1;
				break;
		}


		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;

		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		echo $this->myLayout->workarea_form_image($formname, $img_id, $_fconfig["folder"],$_fconfig["changefolder"],$_fconfig["x"],$_fconfig["y"],$alt,$align,$mode,$versionselect);
	}


	protected function _form_image_selector_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$_options = $_fconfig["options"];
		$formname = $this->formid . $property;

		$this->set($property."_img_id",$this->fget($property."img_id"));
		$this->set($property."_med_id",$this->fget($property."img_id"));

		if ((boolean)$_options["altandalign"]==true)
		{
			$this->set($property."_alt",$this->fget($property."img_alt"));
			$this->set($property."_align",$this->fget($property."img_align"));
		}
		if ((boolean)$_options["versionselect"]==true)
		{
			$this->set($property."_ver_id",$this->fget($property."version"));
		}

	}

	public function form_document_selector($title, $property, $folder="", $changefolder = true, $infoline = true, $type_filter = "",$grp_id=0)
	{
		$folder=trim($folder);
		if ($folder==""){$folder=-1;}
		$this->_form[] = array(
		"form_method" =>"form_document_selector",
		"property" =>$property,
		"title" =>$title,
		"folder"=>$folder,
		"changefolder"=>(boolean)$changefolder,
		"infoline"=>(boolean)$infoline,
		"type_filter"=>$type_filter,
		"grp_id"=>(int)$grp_id
		);

		if ($grp_id!=0)
		{
			throw new Exception('Sorry parameter $grp_id not yet supported :(. Stick to 0');
		}
	}

	protected function _form_document_selector_display($_fconfig)
	{
		$title = $_fconfig["title"];
		$property = $_fconfig["property"];


		$doc_id= $this->getI($property."_doc_id");

		if ($this->myLayout==-1)
		{
			$this->myLayout = new PhenotypeAdminLayout();
		}
		$formname = $this->formid . $property;

		if ($title!="")
		{
			echo codeH($title);
		}
		echo '<br/>';
		//echo $this->myLayout->workarea_form_image($formname, $img_id, $_fconfig["folder"],$_fconfig["changefolder"],$_fconfig["x"],$_fconfig["y"],$alt,$align,$mode,$versionselect);

		echo $this->myLayout->workarea_form_document2($formname, $doc_id, $_fconfig["folder"],$_fconfig["changefolder"],$_fconfig["type_filter"]);
		$info = $this->get($property."_info");
		$formname = $this->formid . $property."_info";
		$width = 405;
		echo $this->myLayout->workarea_form_text(locale("info line"),$formname,$info,$width);
	}

	protected function _form_document_selector_fetch($_fconfig)
	{
		$property = $_fconfig["property"];
		$formname = $this->formid . $property;

		$this->set($property."_doc_id",$this->fget($property."med_id"));
		$this->set($property."_med_id",$this->fget($property."med_id"));
		$this->set($property."_info",$this->fget($property."_info"));


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
		<com_id>'.$myPT->codeX($row['com_id']).'</com_id>
		<com_bez>'.$myPT->codeX($row['com_bez']).'</com_bez>		
		<com_rubrik>'.$myPT->codeX($row['com_rubrik']).'</com_rubrik>
		<com_description>'.$myPT->codeX($row['com_description']).'</com_description>
	</meta>
	<script>'.$myPT->codeX($buffer).'</script>
	<templates>'."\n";

		$sql = 'SELECT * FROM component_template WHERE com_id = ' 	. $tool_type . ' ORDER BY tpl_bez';
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$file = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $tool_type, $row["tpl_id"]);
			$buffer = @file_get_contents($file);
			$xml .= '<template access="'.$myPT->codeX($row['tpl_bez']).'">'.$myPT->codeX($buffer).'</template>'."\n";
		}


		$xml.='   	</templates>
	<componentgroups>';

		$sql = 'SELECT * FROM  componentgroup LEFT JOIN component_componentgroup ON componentgroup.cog_id = component_componentgroup.cog_id WHERE com_id='.$tool_type;
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml.='<group>
			<cog_id>'.$myPT->codeX($row['cog_id']).'</cog_id>
			<cog_bez>'.$myPT->codeX($row['cog_bez']).'</cog_bez>
			<cog_description>'.$myPT->codeX($row['cog_description']).'</cog_description>
			<cog_pos>'.$myPT->codeX($row['cog_pos']).'</cog_pos>
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

			$tpl_id = 1;
			foreach ($_xml->templates->template AS $_xml_template)
			{
				$access = (string)utf8_decode($_xml_template["access"]);
				$mySQL = new SQLBuilder();
				$mySQL->addField("tpl_id",$tpl_id,DB_NUMBER);
				$mySQL->addField("com_id",$com_id,DB_NUMBER);
				$mySQL->addField("tpl_bez",$access);
				$sql = $mySQL->insert("component_template");
				$myDB->query($sql);
				$html = (string)utf8_decode($_xml_template);
				$file = $myPT->getTemplateFileName(PT_CFG_COMPONENT, $com_id, $tpl_id);
				$fp = fopen ($file,"w");
				fputs ($fp,$html);
				fclose ($fp);
				@chmod ($dateiname,UMASK);
				$tpl_id++;
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

	/**
	 * Everytime a component gets displayed this method is called
	 * 
	 * So you have to implement it for your application page components
	 *
	 * @param int $context (as stated in Layout configuration)
	 */
	function render($context)
	{
		echo 'Please implement method render for PhenotypeComponent_'.$this->tool_type;
	}

	/**
	 * internal debugging method, intended for core development usage only ...
	 *
	 * @param string $property
	 */
	protected function _debug_print_form_xy_fetch($property)
	{
		$formname = $this->formid . $property;
		$l=strlen($formname);
		foreach ($_REQUEST AS $k=>$v)
		{
			if (substr($k,0,$l)==$formname)
			{
				echo substr($k,$l).': '.$v.'<br/>';
			}
		}
		exit();
	}

	public function getEditLabel()
	{
		return $this->name;
	}

	public function __call($methodname,$params)
	{
		throw new Exception("There's no method ".$methodname."() in PhenotypeComponent_".sprintf('%02d',$this->com_id) .".");
	}
}
?>