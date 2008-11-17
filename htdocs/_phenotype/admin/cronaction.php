<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support: 
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?php
require("_config.inc.php");
$myPT->loadTMX("Phenotype");

?>
<?php
$sql = "SELECT * FROM action WHERE act_status = 1 ORDER BY act_nextrun";
if (isset($_REQUEST["act_id"]))
{
	$act_id = (int)$_REQUEST["act_id"];
} else if (isset($argv[1])) {
	if (substr($argv[1],0,7) == 'act_id=') {
		$act_id = substr($argv[1], 7);
	}
}

if (isset($act_id)) {
	$sql = "SELECT * FROM action WHERE act_status = 1 AND act_id=".$act_id." ORDER BY act_nextrun";
}

$rs = $myDB->query($sql);
while ($row=mysql_fetch_array($rs))
{
  $fname = "PhenotypeAction_".$row["act_id"];
  $myAction = new $fname();
  $myAction->runAction();
}
?>
