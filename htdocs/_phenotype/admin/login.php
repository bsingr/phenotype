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
// :ToDO: Check, wether this this script is still necessary
require("_config.inc.php");
//require("_session.inc.php");
$myPT->loadTMX("Phenotype");
?>
<?php
@session_start();
$login = false;
if ($_REQUEST['user']!="")
{
  $sql = "SELECT * FROM user WHERE usr_login = LCASE('" . strtolower($_REQUEST['user']) ."') AND usr_status=1";
  $rs = $myDB->query($sql);
  if ($row=mysql_fetch_array($rs))
  {
    if ( $row["usr_pass"] ==  crypt(strtolower($_REQUEST['pass']),"phenotype"))
	{
	  $login = true;
	}
  }
}



if ($login == true)
{

  $_SESSION["usr_id"] = $row["usr_id"];
  $_SESSION["usr_id_fallback"] = $row["usr_id"];
  $myUser = new PhenotypeUser();
  $myUser->init($row);
  $_SESSION["status"]=1;  
  $_SESSION["grp_id"]= (int)$myPT->getPref("backend.grp_id_pagegroup_start");
  $_SESSION["pag_id"]="";
  $_SESSION["lng_id"]=1;
  $mySQL = new SQLBuilder();
  $mySQL->addField("usr_lastlogin",time());
  $sql = $mySQL->update("user","usr_id=".$row["usr_id"]);
  $myDB->query($sql);
  
  // Ueberfaellige Tickets neu kalkulieren

  $myTicket = new PhenotypeTicket();
  $datum = mktime (0,0,0);
  $sql  ="SELECT * FROM ticket WHERE tik_status = 1 AND tik_lastaction <" . $datum;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
    $myTicket->init($row);
    $myTicket->calculate_prio();
  }

  $url ="start.php?".SID;

  Header ("Location:" . $url);
  exit();
}
?>
<?php
//echo session_id();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php require(SYSTEMPATH . "templates/topline_empty.tpl"); ?>
<?php require(SYSTEMPATH . "templates/login_retry.tpl"); ?>
</body>
</html>