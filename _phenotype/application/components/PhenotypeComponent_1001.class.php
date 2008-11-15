<?php 
/**
 * Richtext component
 *
 */
class PhenotypeComponent_1001 extends PhenotypeComponent
{
	var $tool_type = 1001;

	var $bez = "Richtext";


	/**
	 * With this flag you can turn on and off wether a the editor can distinct between versions of an image
	 * 
	 * @var boolean
	 */
	public $selectionOfImageVersions = true;


	function setDefaultProperties()
	{
		// Setting some default values
		$this->set("img_id",0);
		$this->set("img_alignment","links"); // unfortunately still in german, meaning "left" ...
		$this->set("linktarget","_self");
		$this->set("version",0);
	}

	function edit()
	{
		$this->form_textfield("Headline","headline",$this->get("headline"));
		echo "<br/>";
		if ($this->selectionOfImageVersions)
		{
			$this->form_image("",$this->getI("img_id"),-1,1,0,0,$this->get("img_alt"),$this->get("img_align"),2, $this->get("version"));
		}
		else
		{
			$this->form_image("",$this->getI("img_id"),-1,1,0,0,$this->get("img_alt"),$this->get("img_align"),2);
		}
	    ?> 
    	<br/> 
    	<table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground"> 
    	<tr><td nowrap> 
    	<?php 
    	$this->form_richtext("text",$this->get("text"),80,16);
    	?> 
    	</td></tr></table> <br> 
    	<?php 
    	$this->form_link("link",$this->get("linkbez"),$this->get("linkurl"),$this->get("linktarget"));
    	?>
    	<br/>Link-Anchor: #t<?php echo $this->id ?>
    	<?php 
	}

	function update()
	{
		// First do the default property update
		parent::update();
		global $myApp;

		// Then get the property text, which contains the richtext and filter it
		$richtext = $this->get("text");
		$richtext = $myApp->richtext_strip_tags($richtext);
		$this->set("text",$richtext);

		// You can change the filtering by inheriting the method richtext_strip_tags in
		// your PhenotypeApplication.class (located in _application.inc.php)

		if ($this->getI("img_id")==0)
		{

		}

	}


	function setFullSearch()
	{
		$s = $this->get("headline") . "|" . $this->get("text");
		return ($s);
	}

	function render($context)
	{

		// Notwendig, um die Smartyengine richtig zu initialisieren
		eval ($this->initRendering());

		$template = $TPL_DEFAULT;

		switch ($this->get("img_align"))
		{
			case "links":
				$style = "float:left";
				break;

			case "rechts":
				$style = "float:right";
				break;

			case "mittig":
				$style = "";
				break;
		}



		if ($this->get("img_id")!=0)
		{
			$alt = $this->get("alt");
			$myImg = new PhenotypeImage($this->get("img_id"));
			$myImg->style = $style;

			$mySmarty->assign("image",$myImg->render($alt, $this->get("version")));
		}

		$mySmarty->assign("headline",$this->get("headline"));
		$mySmarty->assign("text",$this->get("text"));
		$mySmarty->assign("id",$this->id);

		if ($this->get("linkurl")!="")
		{
			$link = '&nbsp;<a href="'.$this->get("linkurl").'" target="'.$this->get("linktarget").'">'.$this->get("linkbez").'</a>';
			$a= '<a href="'.$this->get("linkurl").'" target="'.$this->get("linktarget").'">';
			$aa = '</a>';
		}
		else
		{
			$link="";
			$a="";
			$aa="";
		}
		$mySmarty->assign("a",$a);
		$mySmarty->assign("aa",$aa);
		$mySmarty->assign("link",$link);

		return $mySmarty->fetch($template);

	}

}
 ?>