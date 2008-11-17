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


require ("_config.inc.php");
require ("_session.inc.php");
$myPT->loadTMX("Extras");
?>
<?php


if (!$mySUser->checkRight("elm_extras"))
{
	$url = "noaccess.php";
	Header("Location:".$url."?".SID);
	exit ();
}
?>
<?php 


$id = $myRequest->getI("id");
$cname = "PhenotypeExtra_".$id;
$myExtra = new $cname ();

$myExtra->execute($myRequest);

?>
