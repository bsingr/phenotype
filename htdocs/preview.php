<?
require("../_config.inc.php");
?>
<?
require(ADMINPATH . "_session.inc.php");

?>
<?
$id = $_REQUEST["id"];
if (isset($_REQUEST["ver_id"]))
{
$ver_id = $_REQUEST["ver_id"];
}
else
{
 $ver_id = 0;
}
?>
<?

$lng_id = $myRequest->getI("lng_id");

$myPage = new PhenoTypePage($id,$ver_id);
$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;
$editbuffer=1;
if (isset($_REQUEST["editbuffer"]))
{
	$editbuffer = $_REQUEST["editbuffer"];
}
$myPage->preview($editbuffer);
?>