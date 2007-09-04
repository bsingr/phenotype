{literal}<?php
class PhenotypeAction_{/literal}{$id}{literal} extends PhenotypeAction
{

  // Bezeichnung der Aktion
  public $id = {/literal}{$id}{literal};
  public $bez = "Neue Aktion";
  public $retry =  600; // Run again after 10 minutes, if action fails
  

  function execute()
  {
  }

}
?>{/literal}