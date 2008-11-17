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

require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?> - <?php echo localeH("Editor");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="task.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="phenotype.js"></script>
</head>


<body>
<script language="JavaScript">self.focus();</script>
<?php
$id = $myRequest->getI("id");
$myPT->displaySequence(0,0,$id,(int)$_REQUEST["b"],1);
?>

</body>
</html>
