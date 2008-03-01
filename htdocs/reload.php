<?php
require("../_config.inc.php");

$sql = "DELETE FROM dataobject WHERE dao_bez LIKE 'include#%'";
$rs = $myDB->query($sql);

$myPT->clearcache_subpages(0);

$url = base64_decode($_REQUEST["uri"]);
Header ("Location:".$url);
exit();