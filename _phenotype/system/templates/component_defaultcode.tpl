{literal}<?php
class PhenotypeComponent_{/literal}{$id}{literal} extends PhenotypeComponent
{
  public $tool_type = {/literal}{$id}{literal};

  // Bezeichnung des Bausteins
  public $bez = "Neuer Baustein"; // erscheint im Arbeitsbereich im Redaktionsmodus


  function setDefaultProperties()
  {
	  $this->set("variable","Wert");
  }
  
  function edit($context)
  {
  ?>
    Hier HTML-Code eingeben oder
  <?php
    // mit den eingebauten Methoden eine Formularfeld zusammenbauen
	// Nachfolgend ein Beispiel fuer ein Textfeld:
    $this->form_textfield("Schlagzeile","bez",$this->get("bez"));
 
  }

  function update($context)
  {
    // Auswertung der Eingabemaske und Setzen der Properties des Tools
    $this->set("datum",time()); 
    $this->fset("bez","bez"); // Aus dem Formularfeld holen 
  }
  
 
  function render($context)
  {
    // Die Variable $context kommt aus dem Layoutblock
	// und kann genutzt werden, um die Bausteine in unterschiedlichen
	// Contexten anders reagieren zu lassen
	
	// Initialisieren des Smartyzugriffs
	eval ($this->initRendering());
	
	// Beispiel:
    $mySmarty->assign("bez",$this->getH("bez"));
    $html = $mySmarty->fetch($TPL_1);
    return $html;
  }
  
}
?>{/literal}