<?
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
<?
require("_config.inc.php");
//require("_session.inc.php");
?>
<?
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
  $myUser = new PhenoTypeUser();
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
<?
//echo session_id();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?= PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
</head>
<body>
<?require(SYSTEMPATH . "templates/topline_empty.tpl");?>
<?require(SYSTEMPATH . "templates/login_retry.tpl");?>
</body>
</html>