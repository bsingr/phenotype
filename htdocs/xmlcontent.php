<?
require("../_config.inc.php");

$pw = $myRequest->get("pw");
if ($pw!= PT_XMLACCESS)
{
	die();
}

$con_id = $myRequest->getI("con_id");


$myPT->displayContentXML($con_id,"rss20");
?>