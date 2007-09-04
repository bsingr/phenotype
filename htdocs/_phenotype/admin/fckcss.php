<?php
require("_config.inc.php");
/*
header('Pragma: cache');
header('Expires: ' . gmdate('D, d M Y H:i:s',date + 1000) . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: private');
header("Content-Type: text/css; charset=iso-8859-1");
*/
header("Content-Type: text/css;");
global $myApp;
$myApp->fckCSS();
exit();
?>