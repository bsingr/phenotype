<?php
/**
 * HTML component
 * 
 * @package phenotype
 * @subpackage application
 *
 */
class PhenotypeComponent_1002 extends PhenotypeComponent
{
	public $com_id = 1002;
	public $name = "HTML";

	public function setDefaultProperties()
	{
		$this->set("_revision",1);
	}
	
  	public function initForm($context)
  	{
 		// Customize input form with form_xy-methods 
 	
    	$this->form_html("","html",405,15);
  	}
	
	public function render($context)
	{
		$html = $this->get("html");

		// Remove PHP-Tags so nobody can insert code
		$html = mb_ereg_replace("<\?[^>]*>","",$html); // matching normal and short opening tags
		$html = mb_ereg_replace("<\%[^>]*>","",$html); // matching ASP-style tags

		return $html;
	}

	public function setFullSearch()
	{
		$s = $this->get("html");
		// Remove all tags
		$s = mb_ereg_replace("<[^>]*>","",$s);
		return ($s);
	}	
	
	public function displayXML()
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