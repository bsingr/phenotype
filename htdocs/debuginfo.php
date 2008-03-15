<?php
require("../_config.inc.php");
if (PT_DEBUG==1)
{
  $myDAO = new PhenotypeSystemDataObject("DebugInfo",array("uri"=>$myRequest->get("uri")));
  echo $myDAO->get("html");
}