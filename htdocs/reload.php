<?php
require("../_config.inc.php");

$myPT->clearCache();

$url = base64_decode($_REQUEST["uri"]);
Header ("Location:".$url);
exit();