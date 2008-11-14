<?php
/**
 * HTML component
 *
 */
class PhenotypeComponent_1002 extends PhenotypeComponent
{
	var $tool_type = 1002;
	var $bez = "HTML";

	function setDefaultProperties()
	{
		$this->set("html","");
	}

	function edit()
	{

		$this->form_html("html",$this->get("html"),80,15);
	}


	function setFullSearch()
	{
		$s = $this->get("html");
		// Alle Tags entfernen
		$s = ereg_replace("<[^>]*>","",$s);
		return ($s);
	}

	function update()
	{
		// First do the default property update
		parent::update();
		global $myAdm;

		$html = $this->get("html");
		$html = $myAdm->decodeRequest_HTMLArea($html);
		$this->set("html",$html);
	}


	function render($context)
	{
		$html = $this->get("html");

		// Remove PHP-Tags so nobody can insert code

		$html = ereg_replace("<\?[^>]*>","",$html); // matching normal and short opening tags
		$html = ereg_replace("<\%[^>]*>","",$html); // matching ASP-style tags


		return $html;
	}


	function displayXML()
	{
  	?>
  	<component com_id="1002" type="HTML">
  	<content>
  	<html><?php echo $this->getX("html");?></html>
    </content>
    </component>
  	<?php 
  	return true;
	}
}
 ?>