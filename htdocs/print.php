<?php
require("../_config.inc.php");
?>
<?php

?>
<?php
$id = $_REQUEST["id"];
?>
<?php

$lng_id = $myRequest->getI("lng_id");

$myPage = new PhenotypePage($id);
$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;
$myPage->printview();
?>