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
require("_session.inc.php");
?>
<?php
// Elementarrechte

if (!$mySUser->checkRight("elm_redaktion"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
if($mySUser->checkRight("elm_content") AND !$mySUser->checkRight("elm_page"))
{
  $url = "backend.php?page=Editor,Content";
  Header ("Location:" . $url."?".SID);
  exit();  
}
if($mySUser->checkRight("elm_mediabase") AND !$mySUser->checkRight("elm_page"))
{
  $url = "backend.php?page=Editor,Media";
  Header ("Location:" . $url."&".SID);
  exit();  
}
?>
<?php
if ($_SESSION["grp_id"]!="" AND $_SESSION["pag_id"]!="" AND (!isset($_REQUEST["grp_id"])))
{
  $url = "page_edit.php?id=" . $_SESSION["pag_id"];
  Header ("Location: " . $url."&".SID);
  exit();
}



$rechte = $mySUser->getRights();
$grp_id="";

if (isset($_REQUEST["grp_id"]))
{
  $grp_id = (int)$_REQUEST["grp_id"];
  $_SESSION["pag_id"]="";
}

if ($grp_id=="")
{
  // Hat der User das Recht auf die Defaultgruppe zuzugreifen ?
  if (isset($rechte["access_grp_" . (int)$myPT->getPref("backend.grp_id_pagegroup_start")]))
  {
	  if ($rechte["access_grp_" .  (int)$myPT->getPref("backend.grp_id_pagegroup_start")] ==1)
	  {
	    $grp_id =  (int)$myPT->getPref("backend.grp_id_pagegroup_start");
      }
  }
}


if ($grp_id=="")
{
  // Wenn keine Gruppe uebergeben wurde, die erste des Users feststellen
  $sql = "SELECT * FROM pagegroup ORDER by grp_bez";
  $rs = $myDB->query($sql);
  while ($row_grp = mysql_fetch_array($rs))
  {
    if (isset($rechte["access_grp_" . $row_grp["grp_id"]]))
	{
	  if ($rechte["access_grp_" . $row_grp["grp_id"]] ==1)
	  {
	    $grp_id = $row_grp["grp_id"];
		break ;
	  }
	  echo "da";
    }
  }
}

if ($grp_id =="")
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}


$top_id =0;
if (isset($rechte["pag_id_grp_" .  $grp_id]))
{
  $top_id= ($rechte["pag_id_grp_" .  $grp_id]);
}  


if ($top_id!=0)
{
  $url = "page_edit.php?id=" . $top_id;
  Header ("Location:" . $url."&".SID);
  exit();
}

$sql = "SELECT * FROM page WHERE pag_id_top = ".$top_id." AND grp_id = " . $grp_id . " ORDER BY pag_pos";

$rs = $myDB->query($sql);


if (mysql_num_rows($rs)==0)
{
 $url = "pagegroup_firstpage.php?grp_id=" . $grp_id;
}
else
{
  $row = mysql_fetch_array($rs);
  $url = "page_edit.php?id=" . $row["pag_id"];
}

Header ("Location:" . $url."&".SID);
?>