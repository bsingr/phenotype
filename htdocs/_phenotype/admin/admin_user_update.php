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
if (!$mySUser->checkRight("elm_admin") AND $id != $mySUser->id)
{
  $url = "noaccess.php";
  Header ("Location:" . $url);
  exit();
}

$b=$_REQUEST["b"];  
if (!$mySUser->checkRight("elm_admin"))
{
  if ($b!=0) // Dann wurde versucht zu mainpulieren
  {
    $url = "noaccess.php";
    Header ("Location:" . $url."?".SID);
    exit();
  }
}
?>
<?php
$id = $myRequest->getI("id");

$mySQL = new SQLBuilder();


// Konfiguration
$pass_status=0;
if ($myRequest->get("b")==0)
{

  $mySQL->addField("usr_login",$myRequest->get("login"));
  $mySQL->addField("usr_vorname",$myRequest->get("vorname"));
  $mySQL->addField("usr_nachname",$myRequest->get("nachname"));
  $mySQL->addField("usr_email",$myRequest->get("email"));
  $mySQL->addField("med_id_thumb",$myRequest->get("userbildimg_id"));
  
 
  if (($myRequest->get("pass1")!="pass") OR ($myRequest->get("pass2")!="pass"))
  { 
    $pass_status = 1;
    if (strtolower($myRequest->get("pass1"))==strtolower($myRequest->get("pass2")) AND ($myRequest->get("pass1")!=""))
    {
	  $newpass = crypt(strtolower($myRequest->get("pass1")),"phenotype");
      $mySQL->addField("usr_pass",$newpass);
	  $pass_status = 2;
    }
  }
  // Preferences
  $_preferences = Array();
  if ($myRequest->check("pref_ticket_markup")){$_preferences["pref_ticket_markup"]=1;}
  if ($myRequest->check("pref_ticket_overview")){$_preferences["pref_ticket_overview"]=1;}
  $mySQL->addField("usr_preferences",serialize($_preferences));
  
  $sql = $mySQL->update("user","usr_id=".$id);
  $myDB->query($sql);
}

// Rechte
if ($myRequest->get("b")==1)
{
  $_rechte = Array();

  // Contentobjekte

  $sql = "SELECT * FROM content";
  $rs = $myDB->query($sql);
  $contentzugriff=0;
  while ($row_content = mysql_fetch_array($rs))
  {
    if ($myRequest->check("con_" . $row_content["con_id"]))
    {
      if ($myRequest->get("con_" . $row_content["con_id"]) ==1)
  	  {
	    $_rechte["con_" . $row_content["con_id"]]=1;
		$contentzugriff=1;
	  }
    }
  }
  
  if ($contentzugriff==1){$_rechte["elm_redaktion"]=1;}else{$_rechte["elm_redaktion"]=0;}
  if ($contentzugriff==1){$_rechte["elm_content"]=1;}else{$_rechte["elm_content"]=0;}
  
    // Extras
  $extraszugriff=0;
  $sql = "SELECT * FROM extra";
  $rs = $myDB->query($sql);
  while ($row_extra = mysql_fetch_array($rs))
  {
    if (isset($_REQUEST["ext_" . $row_extra["ext_id"]]))
    {
      if ($_REQUEST["ext_" . $row_extra["ext_id"]] ==1)
  	  {
	    $_rechte["ext_" . $row_extra["ext_id"]]=1;
		$extraszugriff=1;
	  }
    }
  }
  if ($extraszugriff==1){$_rechte["elm_extras"]=1;}else{$_rechte["elm_extras"]=0;}
  
  // Aufgaben

    $sql = "SELECT * FROM ticketsubject";
    $rs = $myDB->query($sql);
    while ($row_subject = mysql_fetch_array($rs))
    {
      if ($myRequest->check("sbj_" . $row_subject["sbj_id"]))
      {
        if ($myRequest->get("sbj_" . $row_subject["sbj_id"]) ==1)
  	    {
	      $_rechte["sbj_" . $row_subject["sbj_id"]]=1;
	    }
      }
    }

  
    
  // Seitengruppen
  $sql = "SELECT * FROM pagegroup";
  $rs = $myDB->query($sql);
  $pagezugriff=0;
  while ($row_grp = mysql_fetch_array($rs))
  {
	$fname_access = "access_grp_" . $row_grp["grp_id"];
	$fname_pag_id = "pag_id_grp_" . $row_grp["grp_id"];
    if ($myRequest->check($fname_access))
    {

      if ($myRequest->get($fname_access) ==1)
  	  {
	    $_rechte[$fname_access]=1;
		$_rechte[$fname_pag_id]=$myRequest->get($fname_pag_id);
		$pagezugriff=1;
	  }
	  else
	  {
	    $_rechte[$fname_access]=0;
		$_rechte[$fname_pag_id]=0;
	  }
    }
  }  
  if ($pagezugriff==1){$_rechte["elm_redaktion"]=1;}
  if ($pagezugriff==1){$_rechte["elm_page"]=1;}
 


  $sql = "SELECT * FROM role";
  $rs = $myDB->query($sql);
  while ($row_role = mysql_fetch_array($rs))
  {
    if ($myRequest->check("rol_" . $row_role["rol_id"]))
    {
      if ($myRequest->get("rol_" . $row_role["rol_id"]) ==1)
  	  {
	    $_rechte["rol_" . $row_role["rol_id"]]=1;
      }
    }
  }
  
 
  //$_allerechte = $_rechte;
    // Rollen
  /* $sql = "SELECT * FROM role";
  $rs = $myDB->query($sql);
  while ($row_role = mysql_fetch_array($rs))
  {
    if (isset($myRequest["rol_" . $row_role["rol_id"]]))
    {
      if ($myRequest["rol_" . $row_role["rol_id"]] ==1)
  	  {
	    $_rechte["rol_" . $row_role["rol_id"]]=1;
		$_allerechte["rol_" . $row_role["rol_id"]]=1;
		$_rollenrechte = Array();
		if ($row_role["rol_rights"]!=""){$_rollenrechte = unserialize($row_role["rol_rights"]);}
		foreach ($_rollenrechte AS $key => $val)
		{
		  if (!array_key_exists($key,$_allerechte))
		  {
		    $_allerechte[$key]=$val;
		  }
		}
	  }
    }
  }
  */
  $mySQL->addField("usr_rights",serialize($_rechte));
    
  //$mySQL->addField("usr_allrights",serialize($_allerechte));

  $sql = $mySQL->update("user","usr_id=".$id);
  $myDB->query($sql);
  
  // Mit den aktuellen Rollenrechten auffüllen, dabei werden auch die Aufgabenbereiche
  // in der DB nachgezogen
  $mySUser->buildRights($id);
  
 
  
}

if ($myRequest->check("delete"))
{
  $mySQL = new SQLBuilder();
  $mySQL->addField("usr_status",0,DB_NUMBER);
  $sql = $mySQL->update("user","usr_id=".$id);
  $myDB->query($sql);
  $url = "admin_users.php";
  Header ("Location:" . $url."?".SID);
  exit();
  
}

$url = "admin_user_edit.php?id=" . $id . "&ps=" . $pass_status . "&b=" . $myRequest->get("b");
Header ("Location:" . $url."&".SID);
?>
