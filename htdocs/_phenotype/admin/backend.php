<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

require("_config.inc.php");


if (PT_BACKEND!=1){exit();}
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();


$page = $myRequest->get("page");

$patterns = "/[^a-z0-9A-Z,_]*/";
$page = preg_replace($patterns,"", $page);

$_page = split(",",$page);

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

$myBP = new $cname;
$myBP->execute($scope,$action);

?>
