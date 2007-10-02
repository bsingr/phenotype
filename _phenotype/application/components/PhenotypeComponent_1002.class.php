<?php 
class PhenotypeComponent_1002 extends PhenotypeComponent 
{ 
  var $tool_type = 1002; 
  var $bez = "HTML"; // erscheint im Arbeitsbereich 

  function setDefaultProperties() 
  { 
      $this->set("html",""); 
  } 
    
  function edit() 
  { 

    $this->form_HTML("html",$this->get("html"),80,15); 
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
    global $myAdm; 
    // Auswertung der Eingabemaske und Setzen der Properties des Tools 
    $html = $this->fget("html"); 
    //echo $html; 
    $html = $myAdm->decodeRequest_HTMLArea($html); 
    $this->set("html",$html); // Aus dem Formularfeld holen 
  } 
    

  function render($context) 
  { 
    // Die Variable $context kommt aus dem Layoutblock 
    // und kann genutzt werden, um die Bausteine in unterschiedlichen 
    // Contexten anders reagieren zu lassen 
    $html = $this->get("html");
    
	// PHP-Tags entferen, damit keiner Code einschleussen kann !!
	
	$html = ereg_replace("<\?[^>]*>","",$html); 
	$html = ereg_replace("<\%[^>]*>","",$html); // ASP-Style Tags

  
    return $html; 
  } 
    
   
  function displayXML()
  {
  	// <html>$this->getX("html")</html>
  	 ?>
  	<component com_id="1002" type="HTML">
  	<content>
  	<html>$this->getX("html")</html>
    </content>
    </component>
  	<?php 
  	return true;
  }     
} 
 ?>