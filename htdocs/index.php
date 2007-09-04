<?php
require("../_config.inc.php");


if ($myRequest->check("id")) // id is more important than clean urls ...
{
	$id = $myRequest->getI("id");
	$myPage = new PhenotypePage($id);
}
elseif ($myRequest->check("smartURL"))
{
	$myPage = new PhenotypePage();
	$myPage->urlinit($myRequest->get("smartURL"));
} 
else 
{
	$id = PAG_ID_STARTPAGE;
	$myPage = new PhenotypePage($id);
}


?>
<?php

if (PT_FRONTENDSESSION==1)
{
	ini_set ("session.use_trans_sid",1);
	session_start();
}

$lng_id = $myRequest->getI("lng_id");

$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;

$cache=PT_PAGECACHE;

if (isset($_REQUEST["cache"]))
{
  if ($_REQUEST["cache"]==0)
  {
    $cache=0;
  }
  else
  {
    $cache=1;
  }
}


$myPage->display($cache);

// Für Debugzwecke auf speicherlimitierten Systemen
// echo (memory_get_usage());
?>