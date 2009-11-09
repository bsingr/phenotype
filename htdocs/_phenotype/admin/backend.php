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

require("_config.inc.php");


if (PT_BACKEND!=1){exit();}
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();


$page = $myRequest->get("page");

if ($page=="")
{
	$page="Editor,Start";
}
$patterns = "/[^a-z0-9A-Z,_-]*/";
$page = preg_replace($patterns,"", $page);

$_page = mb_split(",",$page);

$page = $_page[0];
$scope = $_page[1];
$action = $_page[2];

$cname = "PhenotypeBackend_" . $page;

if ($scope!=""){$cname.="_".$scope;}

if ($page=="" AND $scope=="")
{
	$cname = "PhenotypeBackend";
}

if ($page!="Session")
{
	require("_session.inc.php");
}

if (!class_exists($cname,true))
{
	// This line will never be reached, since phenotype class load will throw an exception before
	exit();
}


$method = "execute".$action;
$myBP = new $cname;
if (method_exists($myBP,$method))
{
	$myBP->$method();
}
else 
{
	$myBP->execute($scope,$action);
}
?>