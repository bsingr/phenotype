<?php
require("../_config.inc.php");

if (PT_DEBUG==1)
{
  $name = "system.debuginfo_".$_REQUEST["uri"];
  $myDao = new PhenotypeDataObject($name);
  echo $myDao->get("html");
}