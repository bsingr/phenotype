<?php

// -------------------------------------------------------
// Copyright (c) 2003-2005 Phenotype Hagemann & Sellinger
// -------------------------------------------------------
// Kontakt: 
// phenotype@nilshagemann.de
// Nils Hagemann, Lothringer Str. 31, 65195 Wiesbaden
// -------------------------------------------------------
// Das Phenotype CMS Framework unterliegt dem Urheberrecht
// und ist Eigentum von Hagemann & Sellinger. Ohne schriftliche 
// Genehmigung darf kein Teil dieser Software reproduziert, ver-
// vielf�ltigt oder verbreitet werden.
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?php


require ("_config.inc.php");
require ("_session.inc.php");
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
