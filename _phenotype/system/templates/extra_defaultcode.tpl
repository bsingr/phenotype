{literal}<?
class PhenotypeExtra_{/literal}{$id}{literal} extends PhenotypeExtra
{

  // Bezeichnung des Extras
  public $id = {/literal}{$id}{literal};
  public $bez = "Neues Extra";
  public $configure_tab  =1;
  
  function displaySetup()
  {
  }

  function storeConfig()
  {
    $this->store();
  }
  
  function displayStart()
  {
  }

  function execute($myRequest)
  {
  }

}
?>{/literal}