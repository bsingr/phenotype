<?
require("../_config.inc.php");

$pw = $myRequest->get("pw");
if ($pw!= PT_XMLACCESS)
{
	die();
}

if (isset($_REQUEST["id"]))
{
$id = $_REQUEST["id"];
}
else
{
$id= PAG_ID_STARTPAGE;
}
?>
<?

if (PT_FRONTENDSESSION==1)
{
	ini_set ("session.use_trans_sid",1);
	ini_set ("session.use_cookies",0);
	session_start();
}

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

$lng_id = $myRequest->getI("lng_id");

$myPage = new PhenotypePage($id);
$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;
$myPage->displayXML($cache);
?>