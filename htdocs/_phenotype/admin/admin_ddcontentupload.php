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
?>
<?php
require("_config.inc.php");
//require("_session.inc.php");
//DEFINE ("MEDIABASEPATH","D:\WWW\_\www_evo\phenotype\application\mediabase");
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


$save_path=TEMPPATH. "/contentupload/";    
if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
$save_path.=$_REQUEST["con_id"]."/";
if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
$save_path.=$_REQUEST["userid"]."/";
if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
$save_path.=$_REQUEST["savepath"]."/";
if(!file_exists($save_path)){mkdir ($save_path,UMASK);}

$file = $_FILES['userfile'];
$k = count($file['name']);

$_newfiles = Array();
$_listfiles = Array();

for($i=0 ; $i < $k ; $i++)
{
?>
<tr><td width="180"><strong><?php echo $file['name'][$i] ?></strong></td><td align="right"><?php echo $file['size'][$i] ?></td></tr>
<?php

	if(isset($save_path) && $save_path!="")
	{
		$name = split('/',$file['name'][$i]);
		
		move_uploaded_file($file['tmp_name'][$i], $save_path . $name[count($name)-1]);
		$_newfiles[] = $file['name'][$i];
	}
	
}
$fp=opendir($save_path); 
while ($file = readdir ($fp)) 
{ 
   if ($file != "." && $file != "..") 
   { 
       if (!in_array($file,$_newfiles))
	   {
	     $_listfiles[] = $file;
	   }
   } 
}
if (count($_listfiles)!=0)
{
?>
<tr><td colspan="2">&nbsp;--------------------------------------------------------</td></tr>
<?php
}
foreach ($_listfiles AS $file)
{
?>
<tr><td width="180"><strong><?php echo $file ?></strong></td><td align="right"><?php echo filesize($save_path.$file) ?></td></tr>

<?php
}

?>
</table>
</body>
</html>