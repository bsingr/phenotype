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
$myPT->loadTMX("Admin");
?>
<?php
if (!$mySUser->checkRight("elm_admin"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header(locale("Admin"));
?>
<body>
<?php
$action_id = $myRequest->getI("action_id");
$submodul="";
$header = "Protokoll";
switch ($action_id)
{
  case 1: $submodul=locale("Cache");
  break;
  case 2: $submodul=locale("Media");
  break;
  case 3: $submodul=locale("Content"); // Neu Indizieren
  break;
  case 4: $submodul=locale("Pages");
  break;    
  case 5: $submodul=locale("Media"); // Drag & Drop Upload
          $header = locale("Drag & Drop upload");
  break;      
  case 6: $submodul=locale("Content"); // Löschen
  break;  
}

$myAdm->menu(locale("Admin"));

?>
<?php
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Admin"),$submodul);
$myAdm->explorer_draw();


?>
<?php
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $header ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=9" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a></td>
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
			
			
	
<?php
if ($action_id==1)
{
 echo "<strong>".locale("Following pages will be rendered upon next page impression:")."</strong>";
 $sql = "SELECT * FROM pagegroup ORDER by grp_bez";
 $rs = $myDB->query($sql);
 while ($row=mysql_fetch_array($rs))
 {
   if (isset($_REQUEST["grp_id_".$row["grp_id"]]))
   {
     echo "<br><br>" .locale("Pagegroup"). " ". $row["grp_bez"] . ":<br>";
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
  echo "<strong>".locale("Following media objects will be deleted:")."</strong><br><br>";
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
  echo "<strong>".locale("The index of following content object records is regenerated:")."</strong><br><br>";	
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


if ($action_id==5)
{
;
?>
<?php 		if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE")) { ?>
			<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
				width= "250" height= "250"  
				codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">
<?php 		} else { ?>
			<object type="application/x-java-applet;version=1.4.1"
				width= "250" height= "250"  >
	<?php 	} ?>
				<param name="archive" value="<?php echo ADMINFULLURL ?>dndlite.jar">
				<param name="code" value="com.radinks.dnd.DNDAppletLite">
				<param name="name" value="Rad Upload Lite">
		   		<param name = "url" value = "<?php echo ADMINFULLURL ?>admin_ddupload.php"> 
   				<param name = "message" value="<br\>&nbsp;Drag & Drop - Upload">


   		<?php
			if(isset($_SERVER['PHP_AUTH_USER']))
			{
				printf('<param name="chap" value="%s">',
					base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']));
			}
		?>	
   		</object>

		<p><?php echo localeH("Drag & drop your files into the box.");?>.</p>
<?php
}
?>
<?php
if ($action_id==6)
{
  echo "<strong>".locale("Following content object records are deleted:")."</strong><br><br>";	
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
<?php
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?php
$myAdm->mainTable($left,$content);
?>
<?php

?>
</body>
</html>
