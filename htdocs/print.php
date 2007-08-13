<?
require("../_config.inc.php");
?>
<?

?>
<?
$id = $_REQUEST["id"];
?>
<?

$lng_id = $myRequest->getI("lng_id");

$myPage = new PhenotypePage($id);
$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;
$myPage->printview();
?>