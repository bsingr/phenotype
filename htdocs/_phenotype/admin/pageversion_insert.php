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
$myPT->loadTMX("Editor_Pages");
?>
<?php
if (!$mySUser->checkRight("elm_page"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$id = $myRequest->getI("id");
$ver_id = (int)$_REQUEST["ver_id"];

$sql = "SELECT * FROM page WHERE pag_id = " . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);

$mySQL = new SQLBuilder();
$mySQL->addField("ver_nr",($row["pag_ver_nr_max"]+1),DB_NUMBER);
$mySQL->addField("pag_id",$id,DB_NUMBER);   
$mySQL->addField("ver_bez",locale("New version"));
$sql = $mySQL->insert("pageversion");	
$myDB->query($sql);

$ver_id_newversion = mysql_insert_id();

$mySQL = new SQLBuilder();
$mySQL->addField("pag_ver_nr_max",($row["pag_ver_nr_max"]+1),DB_NUMBER);		
$sql = $mySQL->update("page","pag_id=" . $id);	
$myDB->query($sql);   

// Jetzt die Bausteine der Ursprungsversion kopieren
// und die Version anpassen
$sql = "SELECT * FROM pageversion WHERE ver_id = " .$ver_id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);

$mySQL = new SQLBuilder();
$mySQL->addField("lay_id",$row["lay_id"],DB_NUMBER);  
$mySQL->addField("inc_id1",$row["inc_id1"],DB_NUMBER); 
$mySQL->addField("inc_id2",$row["inc_id2"],DB_NUMBER); 
$mySQL->addField("pag_exec_script",$row["pag_exec_script"],DB_NUMBER); 
$mySQL->addField("pag_fullsearch",$row["pag_fullsearch"]);
$mySQL->addField("ver_bez",locale("New version - Copy of") ." " . $row["ver_bez"]);
$sql = $mySQL->update("pageversion","ver_id=" . $ver_id_newversion);	
$myDB->query($sql);

 $sql = "INSERT INTO sequence_data(dat_id,pag_id,ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id) SELECT dat_id,pag_id,".$ver_id_newversion ." AS ver_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata,dat_fullsearch,dat_visible,lng_id FROM sequence_data WHERE pag_id = " . $id . " AND ver_id = " . $ver_id . " AND dat_editbuffer = 0";
  //echo $sql;
  $rs = $myDB->query($sql);
  

// Seitenskript kopieren
$source = APPPATH . "pagescripts/" .  sprintf("%04.0f", $id) . "_" . sprintf("%04.0f", $ver_id) . ".inc.php";
$target = APPPATH . "pagescripts/" .  sprintf("%04.0f", $id) . "_" . sprintf("%04.0f", $ver_id_newversion) . ".inc.php";
$code ="";
$fp = @fopen ($source,"r");
$buffer ="";
if($fp)
{
  while (!feof($fp)) 
  {
    $buffer .= fgets($fp, 4096);
  }
  $code = $buffer;
  fclose ($fp);
}

$fp = fopen ($target, "w");
fputs ($fp,$code);
fclose ($fp);
@chmod ($target,UMASK);

	
$url = "page_edit.php?id=" . $id . "&ver_id=" . $_REQUEST["ver_id"]. "&b=99";
Header ("Location:" . $url."&".SID);
?>