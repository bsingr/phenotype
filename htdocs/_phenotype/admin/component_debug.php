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
// www.phenotype-cms.com - offical homepage
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
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
$id = $myRequest->getI("id");
$sql = "SELECT com_id,dat_comdata FROM sequence_data WHERE dat_id = " . $id . " AND dat_editbuffer=1";
$rs = $myDB->query($sql);
if (mysql_num_rows($rs)==0)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
		<title>Phenotype component debug view - dat_id <?php echo $id?> - <?php echo localeH("Record not found.")?></title>
</head>

<body>
<pre>
<?php



  echo("<br><br>".localeH("Record not found."));
}
else
{
$row = mysql_fetch_array($rs);
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
		<title>Phenotype component debug view - dat_id = <?php echo $id?> com_id = <?php echo $row["com_id"]?></title>
	</head>
	<body>
	<pre>
	<?php


$_props = Array();
if ($row["dat_comdata"] != "")
{
	$_props = unserialize($row["dat_comdata"]);
}	
ksort($_props);
$myPT->startBuffer();
print_r($_props);
$html = $myPT->stopBuffer();
echo codeH($html);
}
?>
</pre>
</body>
</html>