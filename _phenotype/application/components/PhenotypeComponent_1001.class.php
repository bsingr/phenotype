<?php 
class PhenotypeComponent_1001 extends PhenotypeComponent
{
	var $tool_type = 1001;
	var $bez = "Richtextabsatz";

	function setDefaultProperties()
	{
		$this->set("headline","");
		$this->set("text","");
		$this->set("img_id",0);
		$this->set("alt","");
		$this->set("bildausrichtung","links");
		$this->set("linkbez","");
		$this->set("linkurl","");
		$this->set("linktarget","_self");
	}

	function edit()
	{
		global $myAdm;
		
		$_options = Array(0=>"Standard",2=>"Box");
        $options = $myAdm->buildOptionsByNamedArray($_options,$this->get("style"));
        $this->form_select("Style","style",$options,150);
        echo "<br>";
        
        
		$this->form_textfield("Überschrift","headline",$this->get("headline"));
		echo "<br>";
		$this->form_image("",$this->get("img_id"),-1,1,0,0,$this->get("alt"),$this->get("bildausrichtung"),2);


    ?> 
    <br> 
    <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground"> 
    <tr><td nowrap> 
    <?php
    $this->form_Richtext("text",$this->get("text"),80,16);
    ?> 
    </td></tr></table> <br> 
    <?php 
    $this->form_link("link",$this->get("linkbez"),$this->get("linkurl"),$this->get("linktarget"));
    ?>
    <br>Link-Anchor: #t<?php echo $this->id ?>
    <?php
	}

	function update()
	{
		global $myApp;

		$this->fset("style");
		$this->fset("headline");
		$text = $this->fget("text");
		// Nur erlaubte Tags !!
		$text = $myApp->richtext_strip_tags($text);
		$this->set("text",$text);
		$this->fset("img_id");
		$this->fset("bildausrichtung","img_align");
		if ($this->get("img_id")!=0)
		{
			$this->fset("alt","img_alt");
		}
		else
		{
			$this->set("alt","");
		}
		if ($this->get("bildausrichtung")=="")
		{
			$this->set("bildausrichtung","links");
		}
		$this->fset("linkbez","linkbez");
		$this->fset("linkurl","linkurl");
		$this->fset("linktarget","linktarget");
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

		if ($this->get("style")==2)
		{
			$mySmarty->assign("box",1);
		}
		
		switch ($this->get("bildausrichtung"))
		{
			case "links":
				$class = "imageleft";
				break;

			case "rechts":
				$class = "imageright";
				break;

			case "mittig":
				$template = $TPL_TOPIMAGE;
				$class = "imagecenter";
				break;
		}



		if ($this->get("img_id")!=0)
		{
			$alt = $this->get("alt");
			$myImg = new PhenotypeImage($this->get("img_id"));
			$myImg->class = $class;

			$mySmarty->assign("image",$myImg->render($alt));
		}

		$mySmarty->assign("headline",$this->get("headline"));
		$mySmarty->assign("text",$this->get("text"));
		$mySmarty->assign("id",$this->id);

		if ($this->get("linkurl")!="")
		{
			$link = '&nbsp;<a href="'.$this->get("linkurl").'" target="'.$this->get("linktarget").'">'.$this->get("linkbez").'</a>';
			$a= '<a href="'.$this->get("linkurl").'" target="'.$this->get("linktarget").'">';
			$aa = '</a>';
			$mySmarty->assign("a",$a);
			$mySmarty->assign("aa",$aa);
			$mySmarty->assign("link",$link);
		}

		return $mySmarty->fetch($template);

	}

}
?>