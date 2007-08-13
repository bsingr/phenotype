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
require("_session.inc.php");

?>
<?
$id = (int)$_REQUEST["id"];
$block_nr = (int)$_REQUEST["block_nr"];
$toolkit = (int)$_REQUEST["t"];

  $sql = "SELECT * FROM sequence_data WHERE con_id = " . $id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer = 1 ORDER BY dat_pos";
  $rs = $myDB->query($sql);
  while ($row = mysql_fetch_array($rs))
  {
	  $tname = "PhenotypeComponent_" . $row["com_id"];
	  $myComponent = new $tname;
	  $myComponent->init($row);
	  $myComponent->update();
	  $myComponent->store();
      // Wurde ein Tool geloescht	  
	  if (isset($_REQUEST[$row["dat_id"]."_delete_x"]))
	  {
	    $myComponent->delete();
	    //$del_tool_id = $row["dat_id"];
	  }
  }	
   
  // Wurde ein neues Tools eingefuegt?   
  $new_tool_id = $_REQUEST["newtool_id"];
  if ($new_tool_id !="")
  {
    $tname = "PhenotypeComponent_" . $_REQUEST["newtool_type"];
    $myComponent = new $tname;
	//echo $tname;
	$myComponent->addNew(0,0,$id,$block_nr,$new_tool_id);
  } 
  
  $close=0;
  if (isset($_REQUEST["save"])){$close=1;}
  
$url = "sequence_edit.php?id=" . $id . "&b=" . $block_nr . "&editbuffer=1&t=" . $toolkit . "&bez=" . urlencode ($_REQUEST["bez"]) . "&close=" . $close;
Header ("Location:" . $url."&".SID);  
?>