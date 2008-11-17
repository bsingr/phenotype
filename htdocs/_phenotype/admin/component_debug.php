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
require("_session.inc.php");
$myPT->loadTMX("Editor");
?>
<?php
if (!$mySUser->checkRight("superuser"))
{
  die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Phenotype component debug view</title>
</head>

<body>
<pre>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();

$id = $myRequest->getI("id");
?>
<?php


$sql = "SELECT dat_comdata FROM sequence_data WHERE dat_id = " . $id;
$rs = $myDB->query($sql);
if (mysql_num_rows($rs)==0)
{
  echo("<br><br>".localeH("Record not found."));
}
else
{
$row = mysql_fetch_array($rs);


$_props = Array();
if ($row["dat_comdata"] != "")
{
	$_props = unserialize($row["dat_comdata"]);
}	
ksort($_props);
print_r($_props);

}
?>
</pre>
</body>
</html>