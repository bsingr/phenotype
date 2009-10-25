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

set_time_limit(0);
require ("../_config.inc.php");
require ("../_phenotype/system/class/PhenotypeDeployment.class.php");

$myDeployment = new PhenotypeDeployment();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=iso-8859-1" />
<title>Phenotype Deployment</title>
<meta name="generator" content="Phenotype CMS" />
<style type="text/css">
body {
	background-color: #fff;
	font-family: Verdana, Arial;
	font-size: 12px;
}

em {
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
	font-variant: small-caps;
	border-bottom: 1px solid #CFCFCF;
	padding: 0px 1px 0px 1px;
	line-height: 40px;
	letter-spacing: 4px;
}

#main {
	background: #F7F7F7 none repeat scroll 0%;
	border-bottom: 1px solid #CFCFCF;
	border-top: 1px solid #CFCFCF;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
}

#logo {
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	height: 50px;
	text-align: right;
}

#footer {
	font-size: 10px;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	text-align: right;
	height: 50px;
}

#header {
	color: #000;
}

#message {
	color: #f00;
	font-weight: bold;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 10px;
	margin-bottom: 0px;
}

#intro {
	color: #000;
	font-weight: normal;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 10px;
	margin-bottom: 0px;
}



.source {
	font-family: Courier;
	list-style: none;
	font-size: 11px;
	background-color: #fff;
	padding: 7px;

	margin: 5px;
	margin-top: 0px;
	margin-bottom: 0px;
	overflow: auto;
}

.title {
display:block;
width:230px;
float:left;
}

.current {
	background-color: #CFCFCF;
}


.green
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#0F0;
  font-weight: bold;
}

.red
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#F00;
  font-weight: bold;
}


.yellow
{
font-family:Verdana,Arial;
font-size: 12px;
  color:#FB2;
  font-weight: bold;
}

.update
{
margin-top:5px;
margin-bottom:5px;
display:table;
}

.longinput
{
width: 350px;
}


#output {
	font-family: Courier;
	font-size: 11px;
	height: 300px;
	width: auto;
	overflow: auto;
	border: 1px solid #CFCFCF;
	background-color: #fff;
	padding: 8px;
	margin-bottom: 10px;
}
</style>
</head>
<body>
<div id="logo"><a href="http://www.phenotype-cms.com/"><img	src="_phenotype/admin/img/phenotypelogo.gif" alt="Phenotype" style="border:0px"/></a></div>
<div id="main">
<?php
$myDeployment->execute();
?>
</div>
</body>
</html>