<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?php
require("_config.inc.php");
//require("_session.inc.php");
$myPT->loadTMX("Admin");
?>
<html>
<head>
<title>phenotype <?php echo PT_VERSION ?></title>
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
    margin-top: 3px;
    margin-bottom: 3px;
}
-->
</style>
</head>

<body>
 <table width="240" border="0" cellpadding="2" cellspacing="2">
<?php


$save_path=MEDIABASEPATH . "/import/";    
//echo $save_path;

$file = $_FILES['userfile'];
$k = count($file['name']);

for($i=0 ; $i < $k ; $i++)
{
?>
<tr><td width="180"><strong><?php echo $file['name'][$i] ?></strong></td><td align="right"><?php echo $file['size'][$i] ?></td></tr>
<?php

	if(isset($save_path) && $save_path!="")
	{
		$name = split('/',$file['name'][$i]);
		
		move_uploaded_file($file['tmp_name'][$i], $save_path . $name[count($name)-1]);
	}
	
}

?>
</table>
</body>
</html>