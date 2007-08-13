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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html>
<head>
	<title>Unbenannt</title>
	<link href="format.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" language="JavaScript">
  function addnew(p)
  {
    fname = "addtool_" + p;

    v = document.forms.editform[fname].options[document.forms.editform[fname].selectedIndex].value;
    if (v != 0)
    {
      document.forms.editform.newtool_id.value = p;
      document.forms.editform.newtool_type.value = v;
      document.forms.editform.submit();  
    }
  }
  
  function MM_openBrWindow(theURL,winName,features) 
  { 
    window.open(theURL,winName,features);
  } 
   
 
</script>   	
</head>

<body bgcolor="#EEEEEE">
<?
$id = $_REQUEST["id"];
$block_nr = $_REQUEST["b"];
$toolkit = $_REQUEST["t"];

// Befinden wir uns schon im Editbuffer-Modus?

if (!isset($_REQUEST["editbuffer"]))
{
 // echo "Buffer anlegen";
  // Hier spaeter auch Versionierung beachten !!
  $sql = "DELETE FROM sequence_data WHERE con_id = " . $id . " AND dat_editbuffer=1 AND dat_blocknr = ". $block_nr;
  $myDB->query($sql);
  $table ="temp_" . time();
  $sql = "CREATE TEMPORARY TABLE " . $table . " SELECT * FROM sequence_data WHERE con_id = " . $id . " AND dat_blocknr = ". $block_nr ." AND dat_editbuffer=0";
  $rs = $myDB->query($sql);
  $sql = "INSERT INTO sequence_data(dat_id,con_id,dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata) SELECT dat_id,con_id, 1 AS dat_editbuffer,dat_blocknr,dat_pos,com_id,dat_comdata FROM " .$table;
  //echo $sql;
  $rs = $myDB->query($sql);  
  $sql = "DROP TABLE " . $table;
  $rs = $myDB->query($sql);  
}
?>
	<form enctype="multipart/form-data" name="editform" method="post" action="sequence_update.php">
	<input type="hidden" value="1" name="editbuffer">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="block_nr" value="<?=$block_nr?>">	
	<input type="hidden" name="newtool_id" value="">	
	<input type="hidden" name="newtool_type" value="">		
	<input type="hidden" name="t" value="<?=$toolkit?>">	
 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#EEEEEE">
			  <?
			  // -------------------------------------------------------------
			  // -- componentgroup 1
			  // -------------------------------------------------------------
			  ?>
                <tr> 
                  <td align="center" class="maskeEinf" >+</td>
                  <td class="maskeEinf" >
				      <select name="addtool_0" class="listeEinf" onchange="addnew(0)">
            		<option value="0" selected>Einf&uuml;gen</option>					  
				      <?require APPPATH . "components/toolkit" . $toolkit . ".inc.html";?>
                    </select><br></td>
                </tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>    
			  <?
			  // -------------------------------------------------------------
			  // -- componentgroup 1
			  // -------------------------------------------------------------
			  ?>				    
		
	<?
	$sql = "SELECT * FROM sequence_data WHERE con_id = " . $id . " AND dat_blocknr=" . $block_nr . " AND dat_editbuffer=1 ORDER BY dat_pos";
    $rs = $myDB->query($sql);
	while ($row = mysql_fetch_array($rs))
	{
	  $tname = "PhenotypeComponent_" . $row["com_id"];
	  $myComponent = new $tname;
	  $myComponent->init($row);
	  ?>
	  <tr><td>&nbsp;</td><td><p> 
	  <?
	  $myComponent->edit();
	  ?>
	  &nbsp;<input name="<?=$row["dat_id"]?>_delete" type="submit" class="button" value="Entf."></p></td></tr>
	  <?
	  //$myComponent->props = unserialize($s);
	  //echo $myComponent->get("bez");
	  //$test = unserialize($obj);

	  // -------------------------------------------------------------
	  // -- componentgroup 1
	  // -------------------------------------------------------------
	  ?>
      <tr> 
         <td align="center" class="maskeEinf" >+</td>
         <td class="maskeEinf" > <select name="addtool_<?=$row["dat_id"]?>" class="listeEinf" onchange="addnew(<?=$row["dat_id"]?>)">
			<option value="0" selected>Einf&uuml;gen</option>
	      <?require APPPATH . "/components/toolkit" . $toolkit . ".inc.html";?>         
		  </select><br></td>
      </tr>
	  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>    
	  <?
	  // -------------------------------------------------------------
	  // -- componentgroup 1
	  // -------------------------------------------------------------

	}
	?>
    </table>
	<input type="submit" value="&Uuml;bernehmen" class="button"><br><br><br><br>
</form>
</body>
</html>
