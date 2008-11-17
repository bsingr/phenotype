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
require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Admin");
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$myAdm = new PhenotypeAdmin();
$id = $myRequest->getI("id");

if (isset($_REQUEST["delete"]))
{
  $sql = "DELETE FROM pagegroup WHERE grp_id = " . $id;
  $myDB->query($sql);      
  
  $url = "admin_groups.php";
  Header ("Location:" . $url."?".SID);
  exit();
  
}

$sql = "SELECT * FROM pagegroup WHERE grp_id=".$id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);

$mySQL = new SQLBuilder();

$mySQL->addField("grp_bez",$_REQUEST["bez"]);
$mySQL->addField("grp_description",$_REQUEST["description"]);
$mySQL->addField("grp_smarturl_schema",$myRequest->getI("grp_smarturl_schema"));
if (isset($_REQUEST["statistic"]))
{
  $mySQL->addField("grp_statistic",1,DB_NUMBER);
}
else
{
  $mySQL->addField("grp_statistic",0,DB_NUMBER);
}
if (isset($_REQUEST["multilanguage"]))
{
  $mySQL->addField("grp_multilanguage",1,DB_NUMBER);
}
else
{
  $mySQL->addField("grp_multilanguage",0,DB_NUMBER);
}
$sql = $mySQL->update("pagegroup","grp_id =" . $id);
$myDB->query($sql);

if ($row["grp_smarturl_schema"]!=$myRequest->getI("grp_smarturl_schema") OR $row["grp_multilanguage"] !=$myRequest->getI("grp_multilanguage"))
{
  // change of smarturl schema! a rebuild is necessary
  $sql = "SELECT pag_id FROM page WHERE grp_id=".$id;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
    $myPage = new PhenotypePage($row["pag_id"]);
    //echo $myPage->smarturl_schema;
    $myPage->rebuildURLs(); 
  }
}


$url = "admin_group_edit.php?id=" . $id . "&b=1";
Header ("Location:" . $url."&".SID);
?>
