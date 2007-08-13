{literal}<?
class PhenotypeContent_{/literal}{$id}{literal} extends PhenotypeContent
{
  // Bezeichnung des Contentobjektes
  var $content_type = {/literal}{$id}{literal};
  var $skins = Array(); // erlaubte Skins
  
  // Mehrere Tabs
  //var $_blocks = Array("Konfiguration","Bausteine");
  //var $_icons = Array("b_konfig.gif","b_items.gif");
  
  function setDefaultProperties()
  {
	  $this->set("bez","Neuer Datensatz");
	  $this->set("datum",time());	
  }
  
  function init($row,$block_nr=0) 
  { 
    parent::init($row,$block_nr); 
	
	// Hier das Formular und damit auch die Updatefunktion initialisieren
	
	
	/*switch ($block_nr)
	{ 
	  case 0:
	  break;
	}*/
	 
	$this->form_textfield("Bezeichnung","bez",200);

	
  }
  
  function attachKeyFields()
  {
    $this->setKey1($this->get("datum"));
  }
  

}
?>{/literal}