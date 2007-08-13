<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<?
$myAdm->header("Admin");
?>
<body>
<?
$action_id = $myRequest->getI("action_id");
$submodul="";
$header = "Protokoll";
switch ($action_id)
{
  case 1: $submodul="Cache";
  break;
  case 2: $submodul="Media";
  break;
  case 3: $submodul="Content"; // Neu Indizieren
  break;
  case 4: $submodul="Seiten";
  break;    
  case 5: $submodul="Media"; // Drag & Drop Upload
          $header = "Drag & Drop - Upload";
  break;      
  case 6: $submodul="Content"; // L�schen
  break;  
}

$myAdm->menu("Admin");

?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?
$myAdm->explorer_prepare("Admin",$submodul);
$myAdm->explorer_draw();


?>
<?
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?=$header?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=9" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>


      <tr>
        <td class="window">

		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top" class="padding30" width="120"><p><strong></strong></p></td>
            <td class="formarea">
			<p>
			
			
	
<?
if ($action_id==1)
{
 echo "<strong>Nachfolgende Seiten werden beim n&auml;chsten Aufruf neu gerendert:</strong>";
 $sql = "SELECT * FROM pagegroup ORDER by grp_bez";
 $rs = $myDB->query($sql);
 while ($row=mysql_fetch_array($rs))
 {
   if (isset($_REQUEST["grp_id_".$row["grp_id"]]))
   {
     echo "<br><br>Seitengruppe " . $row["grp_bez"] . ":<br>";
	 $id = $_REQUEST["pag_id_grp_id_".$row["grp_id"]];
	 if ($id==0)
	 {
	   $sql = "SELECT pag_id FROM page WHERE grp_id=" . $row["grp_id"] . " AND pag_id_top=0";
	   $rs2 = $myDB->query($sql);
       while ($row2=mysql_fetch_array($rs2))
	   {
	     $id = $row2["pag_id"];
 	     $myPT->clearcache_page($id,0);
         $myPT->clearcache_subpages($id,0);
	   }
	 }
	 else
	 {
	   $myPT->clearcache_page($id,0);
       $myPT->clearcache_subpages($id,0);
	 }
   }
 }


}

if ($action_id==2)
{
  echo "<strong>Nachfolgende Mediaobjekte werden gel&ouml;scht:</strong><br><br>";
  set_time_limit(0);
  $folder = $_REQUEST["folder"];
  //$sql = "SELECT * FROM media WHERE med_logical_folder ='" . $folder . "'";
  $sql = "SELECT * FROM media WHERE med_logical_folder1 LIKE'" . $folder . "%'";
  $rs = $myDB->query($sql);
  $myMB = new PhenotypeMediabase();
  while ($row=mysql_fetch_array($rs))
  {
    echo "- " . $row["med_id"] . ": " . $row["med_bez"] . "<br>";
	$myMB->deleteMediaObject($row["med_id"]);
  }
}


if ($action_id==3)
{
  echo "<strong>Nachfolgende Contentobjekt-Datens�tze werden neu indiziert:</strong><br><br>";	
  $sql = "SELECT * FROM content_data WHERE con_id=" . $_REQUEST["con_id"];
  $rs = $myDB->query($sql);
  $cname =  "PhenotypeContent_" . $_REQUEST["con_id"];
  $myCO = new $cname;
  //echo $cname;
  while ($row=mysql_fetch_array($rs))
  {
  	$myCO->init($row);
  	echo $myCO->bez . "<br>";
  	$myCO->store();
  }
}


if ($action_id==4)
{
  echo "<strong>Zombie-Versionen nachfolgender Seiten werden gel&ouml;scht:</strong><br><br>";
// Versionen bereinigen
$sql = "SELECT DISTINCT (pag_id) FROM pageversion";
$rs = $myDB->query($sql);
while ($row=mysql_fetch_array($rs))
{
  $sql = "SELECT * FROM page WHERE pag_id = " . $row["pag_id"];
  $rs_check = $myDB->query($sql);
  if (mysql_num_rows($rs_check)==0)
  {
    echo "- ". $row["pag_id"] .": " . $row["pag_bez"] . "<br>";
	$sql = "DELETE FROM pageversion WHERE pag_id  =" . $row["pag_id"];
	$myDB->query($sql);
  }
}
}
?>

<?
if ($action_id==5)
{
;
?>
<?		if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE")) { ?>
			<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
				width= "250" height= "250"  
				codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">
<?		} else { ?>
			<object type="application/x-java-applet;version=1.4.1"
				width= "250" height= "250"  >
	<?	} ?>
				<param name="archive" value="<?=ADMINFULLURL?>dndlite.jar">
				<param name="code" value="com.radinks.dnd.DNDAppletLite">
				<param name="name" value="Rad Upload Lite">
		   		<param name = "url" value = "<?=ADMINFULLURL?>admin_ddupload.php"> 
   				<param name = "message" value="<br\>&nbsp;Drag & Drop - Upload">


   		<?
			if(isset($_SERVER['PHP_AUTH_USER']))
			{
				printf('<param name="chap" value="%s">',
					base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']));
			}
		?>	
   		</object>

		<p>Ziehen Sie per Drag & Drop die hochzuladenden<br>Dateien in den Kasten.</p>
<?
}
?>
<?
if ($action_id==6)
{
  echo "<strong>Nachfolgende Contentobjekt-Datens�tze werden gel&ouml;scht:</strong><br><br>";	
  $sql = "SELECT * FROM content_data WHERE con_id=" . $_REQUEST["con_id"];
  $rs = $myDB->query($sql);
  $cname =  "PhenotypeContent_" . $_REQUEST["con_id"];
  $myCO = new $cname;
  while ($row=mysql_fetch_array($rs))
  {
  	$myCO->init($row);
  	echo $myCO->bez . "<br>";
  	$myCO->delete();
  }
}
?>


</p></td>
          </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  </table><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">&nbsp;</td>
          </tr>
        </table>

       		</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
<?
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?
$myAdm->mainTable($left,$content);
?>
<?

?>
</body>
</html>
