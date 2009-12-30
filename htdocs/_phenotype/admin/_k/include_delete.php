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
if (PT_CONFIGMODE!=1){exit();}
$myPT->loadTMX("Config");
?>
<?php
if (!$mySUser->checkRight("superuser"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$id = (int)$_REQUEST["id"];



$myAdm = new PhenotypeAdmin();
$myAdm->cfg_removeInclude($id);
  
  $sql = "SELECT COUNT(*) AS C FROM include WHERE inc_rubrik LIKE '" . mysql_real_escape_string($_REQUEST["r"]) ."'";
  $rs_check = $myDB->query($sql);
  
  
   $row = mysql_fetch_array($rs_check);
  if ($row["C"]==0)
  {
    $url = "includes.php?r=-1";
  }
  else
  {
    $url = "includes.php?r=" .urlencode($_REQUEST["r"]);  
  }
  Header ("Location:" . $url."&".SID);
  exit();
  
?>
