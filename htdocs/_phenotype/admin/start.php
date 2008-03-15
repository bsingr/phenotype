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

$myApp->onPress_Start();
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header("Start");
?>
<body>
<?php
$myAdm->menu("Start");
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$rechte = $mySUser->getRights();
//print_r($rechte);

if ($mySUser->checkRight("elm_page"))
{
	$url = "backend.php?page=Editor,Start";
	$myLayout->tab_addEntry("Seiten",$url,"b_site.gif");
	$myLayout->tab_draw("Seiten",$x=260,"1");

	$sql = "SELECT grp_id AS K, grp_bez AS V FROM pagegroup ORDER BY V";
	$html = "";
	$rs = $myDB->query($sql);
	$grp_id = (int)$_SESSION["grp_id"];
	while ($row = mysql_fetch_array($rs))
	{
		$selected ="";
		if ($row["K"] == $_SESSION["grp_id"])
		{
			$selected = "selected";
		}
		if (isset($rechte["access_grp_" . $row["K"]]))
		{
			if ($grp_id=="" OR $grp_id=="-1"){$grp_id=$row["K"];}
			$html .='<option value="'. $row["K"] .'" ' . $selected . '>' . $row["V"] . '</option>';
		}
	}

  ?>
      <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="padding10"><form action="pagegroup_select.php" method="post" name="formGrp">Gruppe:</td>
              <td><select name="grp_id" onChange="document.forms.formGrp.submit();" class="listmenu">
<?php
echo $html;
?>				 
</select></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></form></td>
        </tr>
      </table>
<?php
$top_id = (int)$rechte["pag_id_grp_" . $grp_id];
if ($top_id==0)
{
	$sql = "SELECT pag_id FROM page WHERE grp_id = ". $grp_id." AND pag_id_top = " . $top_id . " ORDER BY pag_pos";
	$rs = $myDB->query($sql);
	$row =mysql_fetch_array($rs);
	$pag_id = $row["pag_id"];
}
else
{
	$pag_id = $top_id;
}
// Fuer den Sonderfall noch keine Seite in der Seitengruppe
if ($pag_id!=""){$myAdm->showNavi($pag_id,$top_id,false);}
?>
        <table width="260" border="0" cellpadding="0" cellspacing="0">	
		<tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
	  <br><br>
<?php
}
// Content
?>	    
<?php
if ($mySUser->checkRight("elm_content"))
{
	$myLayout->tab_new();
	$url = "backend.php?page=Editor,Content";
	$myLayout->tab_addEntry("Content",$url,"b_content.gif");
	$myLayout->tab_draw("Content",$x=260,1);

	$myNav = new PhenotypeTree();
	$nav_id = $myNav->addNode("&Uuml;bersicht","backend.php?page=Editor,Content",0,"");
	$sql = "SELECT * FROM content ORDER BY con_pos, con_bez";
	$rs = $myDB->query($sql);
	while ($row = mysql_fetch_array($rs))
	{
		$access = 0;
		if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
		if ($access==1)
		{
			$myNav->addNode($row["con_bez"],"backend.php?page=Editor,Content,select&con_id=".$row["con_id"]."&c=akt",$nav_id,$row["con_id"]);
		}

	}
	$myLayout->displayTreeNavi($myNav,"-1");
?>
        <table width="260" border="0" cellpadding="0" cellspacing="0">	
		<tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
<br><br>
<?php
}
// Mediabase
?>	    
<?php
if ($mySUser->checkRight("elm_mediabase"))
{
	$myLayout->tab_new();
	$url = "backend.php?page=Editor,Media";
	$myLayout->tab_addEntry("Media",$url,"b_media.gif");
	$myLayout->tab_draw("Media",$x=260,1);
?>
<?php
$myNav = new PhenotypeTree();
$nav_id =   $myNav->addNode("&Uuml;bersicht","backend.php?page=Editor,Media",0,"-1");
global $myDB;
$myMB = new PhenotypeMediabase();
$_folder = $myMB->getLogicalRootFolder();
foreach ($_folder AS $k)
{
	$myNav->addNode($k,"backend.php?page=Editor,Media,browse&grp_id=0&folder=".$k,$nav_id,$k);
}
$myLayout->displayTreeNavi($myNav,"");
?>
        <table width="260" border="0" cellpadding="0" cellspacing="0">	
		<tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
<?php
}
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
            <td class="windowTitle">&Uuml;bersicht</td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=3" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
<?php
if ($mySUser->checkRight("elm_page"))
{
	$sql = "SELECT grp_id FROM pagegroup";
	$rs = $myDB->query($sql);
	$sql_where = "";
	while ($row = mysql_fetch_array($rs))
	{
		if (isset($rechte["access_grp_" . $row["grp_id"]]))
		{
			if ($sql_where =="")
			{
				$sql_where = "grp_id=" . $row["grp_id"];
			}
			else
			{
				$sql_where .= " OR grp_id=" . $row["grp_id"];
			}
		}
	}

	// avoid SQL errors when datbase is not consistent (after package operations)
	if ($sql_where ==""){$sql_where ='1=1';}
	
	$sql = "SELECT * FROM page WHERE " . $sql_where . " ORDER BY pag_date DESC LIMIT 0,6";
	
	


?>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Seiten</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="tableHead">ID</td>
		    <td class="tableHead">Bezeichnung</td>
      		<td width="120" class="tableHead">Benutzer</td>
            <td width="30" class="tableHead">Status</td>
            <td width="50" align="right" class="tableHead">Aktion</td>
            </tr>
		  <tr>
            <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  <?php
		  $rs_data = $myDB->query($sql);
		  while ($row_data=mysql_fetch_array($rs_data))
		  {
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["pag_id"] ?></td>
			

            <td class="tableBody"><?php echo $row_data["pag_bez"] ?></td>
            <td class="tableBody"><?php echo date('d.m.Y H:i',$row_data["pag_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td class="tableBody">
			<?php if ($row_data["pag_status"]==1){ ?>
			<img src="img/i_online.gif" alt="Status: online" width="30" height="22">
			<?php }else{ ?>
			<img src="img/i_offline.gif" alt="Status: offline" width="30" height="22">
			<?php } ?>
			</td>
            <td align="right" nowrap class="tableBody"><a href="page_edit.php?id=<?php echo $row_data["pag_id"] ?>"><img src="img/b_edit.gif" alt="Seite bearbeiten" width="22" height="22" border="0" align="absmiddle"></a></td>
            </tr>
          <tr>
            <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
		  }
?>			
          <tr>
            <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br>  
	<?php
}
// -- Seiten
?>	
<?php
// Content
if ($mySUser->checkRight("elm_content"))
{

	$table ="temp_" . uniqid("pt");


	$sql = "SELECT * FROM content ORDER BY con_pos, con_bez";
	$rs = $myDB->query($sql);
	$sql_union="";
	$create=0;
	$rs_temp=0;

	if (mysql_num_rows($rs)!=0)
	{
		while ($row = mysql_fetch_array($rs))
		{
			$access = 0;
			if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
			if ($access==1)
			{
				if ($create==0)
				{
					$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS " . $table;
					$create=1;
				}
				else
				{
					$sql = "INSERT INTO " . $table;
				}
				$cname = "PhenotypeContent_" . $row["con_id"];
				$myCO = new $cname;
				$filter = $myCO->getAccessFilter();
				if ($filter !="")
				{
					$filter = " AND " . $filter;
				}

				$sql .= " SELECT content_data.dat_bez, content_data.dat_id , content_data.dat_uid, content_data.usr_id,content_data.dat_date,content_data.dat_status, content_data.med_id_thumb, content.con_bearbeiten ,content.con_loeschen , content.con_bez FROM content_data LEFT JOIN content ON content_data.con_id = content.con_id WHERE content_data.con_id = " . $row["con_id"] . $filter . " ORDER BY dat_date DESC LIMIT 0,3";
				//echo $sql;
				$myDB->query($sql);
			}
		}

		$sql_union = "SELECT * FROM " . $table ." ORDER BY dat_date DESC LIMIT 0,10";
		/*
		$sql = "SELECT * FROM content ORDER BY con_pos, con_bez";
		$rs = $myDB->query($sql);
		$sql_union="";
		while ($row = mysql_fetch_array($rs))
		{
		$access = 0;
		if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
		if ($access==1)
		{
		$sql = "(SELECT content_data.dat_bez, content_data.dat_id,content_data.dat_id,content_data.usr_id,content_data.dat_date,content_data.dat_status, content_data.med_id_thumb, content.con_bearbeiten ,content.con_loeschen , content.con_bez FROM content_data LEFT JOIN content ON content_data.con_id = content.con_id WHERE content_data.con_id = " . $row["con_id"] . " ORDER BY dat_date DESC LIMIT 0,3)";
		if ($sql_union=="")
		{
		$sql_union = $sql;
		}
		else
		{
		$sql_union .=" UNION ".$sql;
		}
		}
		}
		$sql_union .= "ORDER BY dat_date DESC LIMIT 0,10";
		*/

?>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Content</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="tableHead">ID</td>
			<td width="70" class="tableHead">Abbildung</td>
            <td class="tableHead">Bezeichnung</td>
            <td class="tableHead">Typ</td>
			<td width="120" class="tableHead">Benutzer</td>
            <td width="30" class="tableHead">Status</td>
            <td width="50" align="right" class="tableHead">Aktion</td>
            </tr>
		  <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  <?php
		  $rs_data = $myDB->query($sql_union);
		  while ($row_data=mysql_fetch_array($rs_data))
		  {
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["dat_id"] ?></td>
			
            <td class="tableBody">
			<?php if ($row_data["con_bearbeiten"]==1){ ?><a href="backend.php?page=Editor,Content,edit&id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>"><?php } ?>
			<?php
			if ($row_data["med_id_thumb"]!=0)
			{

				$myImg = new PhenotypeImage($row_data["med_id_thumb"]);
				$myImg->display_ThumbX(60,$row_data["dat_bez"]);
			}
		  ?>
		  <?php if ($row_data["con_bearbeiten"]==1){ ?>
		  </a>
		  <?php } ?>
		  </td>
            <td class="tableBody"><?php echo $row_data["dat_bez"] ?></td>
			<td class="tableBody"><?php echo $row_data["con_bez"] ?></td>
            <td class="tableBody"><?php echo date('d.m.Y H:i',$row_data["dat_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td class="tableBody">
			<?php if ($row_data["dat_status"]==1){ ?>
			<img src="img/i_online.gif" alt="Status: online" width="30" height="22">
			<?php }else{ ?>
			<img src="img/i_offline.gif" alt="Status: offline" width="30" height="22">
			<?php } ?>
			</td>
            <td align="right" nowrap class="tableBody"><?php if ($row_data["con_bearbeiten"]==1){ ?><a href="backend.php?page=Editor,Content,edit&id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>"><img src="img/b_edit.gif" alt="Datensatz bearbeiten" width="22" height="22" border="0" align="absmiddle"></a> <?php } ?><?php if ($row_data["con_loeschen"]==1){ ?><a href="backend.php?page=Editor,Content,delete&id=<?php echo $row_data["dat_id"] ?>&c=<?php echo $_REQUEST["c"] ?>" onclick="return confirm('Den Datensatz wirklich l&ouml;schen?')"><img src="img/b_delete.gif" alt="Datensatz l&ouml;schen" width="22" height="22" border="0" align="absmiddle"></a><?php } ?></td>
            </tr>
          <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
		  }
?>			
          <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br>  
	<?php
	$sql = "DROP TABLE " . $table;
	$rs = $myDB->query($sql);
	}
} // -- Content
?>
<?php
// Media
if ($mySUser->checkRight("elm_mediabase"))
{
	$sql = "SELECT * FROM media ORDER BY med_date DESC LIMIT 0,6";


?>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Media</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="tableHead">ID</td>
			<td width="70" class="tableHead">Abbildung</td>
            <td class="tableHead">Bezeichnung</td>
     		<td width="120" class="tableHead">Benutzer</td>
			<td width="30" class="tableHead">&nbsp;</td>
            <td width="50" align="right" class="tableHead">Aktion</td>
            </tr>
		  <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  <?php
		  $rs_data = $myDB->query($sql);
		  while ($row_data=mysql_fetch_array($rs_data))
		  {
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["med_id"] ?></td>
			
            <td class="tableBody">
			<a href="backend.php?page=Editor,Media,edit&id=<?php echo $row_data["med_id"] ?>">
			<?php
			if ($row_data["med_type"]==1)
			{

				$myImg = new PhenotypeImage($row_data["med_id"]);
				$myImg->display_ThumbX(60,$row_data["med_bez"]);
			}
			else
			{
				$icon = "binary";
				switch (strtolower($row_data["med_subtype"]))
				{
					case "gif":
					case "jpg":
					case "bmp":
					case "psd":
					case "png":
					case "jpeg":
						$icon = "image";
						break;

					case "wav":
					case "mid":
					case "mp3":
						$icon = "audio";
						break;

					case "pdf":
						$icon = "pdf";
						break;

					case "xls":
						$icon = "excel";
						break;

					case "doc":
						$icon = "word";
						break;

					case "ppt":
						$icon = "powerpoint";
						break;

					case "sql":
					case "txt":
						$icon = "text";
						break;

				}

				echo "&lt;".$icon."&gt;";
			}
		  ?>
		  </a>
		   </td>
            <td class="tableBody"><?php echo $row_data["med_bez"] ?></td>
		    <td class="tableBody"><?php echo date('d.m.Y H:i',$row_data["med_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td>&nbsp;</td>
			<td align="right" nowrap class="tableBody"><a href="backend.php?page=Editor,Media,edit&id=<?php echo $row_data["med_id"] ?>"><img src="img/b_edit.gif" alt="Datensatz bearbeiten" width="22" height="22" border="0" align="absmiddle"></a></td>
            
            </tr>
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
		  }
?>			
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br>  
	<?php
}	 // -- Media
?>
<?php
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content}
// -------------------------------------
?>
<?php
$myAdm->mainTable($left,$content);
?>

</body>
</html>























