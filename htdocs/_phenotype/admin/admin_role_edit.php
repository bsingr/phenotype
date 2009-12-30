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
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
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
$id = $myRequest->getI("id");
?>
<?php
$myAdm->header(locale("Admin"));
?>
<body>
<?php
$myAdm->menu(locale("Admin"));
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Admin"),locale("Roles"));
$myAdm->explorer_set("rol_id",$id);
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

// Rechte:
// con_xx = Zugriff auf Contentonjekt xx
// elm_xx = Elementarrecht xx
// access_grp_xx = Zugriff auf Seitengruppe xx
// pag_id_grp_xx = Startseite der Seitengruppe xx
// rol_xx = Mitglied der Rolle xx

$myPT->startBuffer();
$sql = "SELECT * FROM role WHERE rol_id =" . $id;
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$rechte = Array();
if ($row["rol_rights"]!=""){$rechte = unserialize($row["rol_rights"]);}
?> 
 <form action="admin_role_update.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id ?>">	
	<input type="hidden" name="b" value="<?php echo (int)$_REQUEST["b"] ?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> <?php echo localeH("Role");?> / <?php echo $row["rol_bez"] ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=12" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	$myLayout->tab_new();
	$url = "admin_user_edit.php?id=" .$id ."&b=0";
	$myLayout->tab_addEntry(locale("Config"),$url,"b_konfig.gif");

	switch ((int)$_REQUEST["b"])
	{
		case 0:
			$myLayout->tab_draw(locale("Config"));
			$myLayout->workarea_start_draw();
			$html = $myLayout->workarea_form_text("","bez",$row["rol_bez"]);
			$myLayout->workarea_row_draw(locale("Name"),$html);
			$html=  $myLayout->workarea_form_textarea("","description",$row["rol_description"],8);
			$myLayout->workarea_row_draw(locale("Description"),$html);

			$_element = Array();
			//$_element["Redaktion / Seiten - Allgemein"]="page";
			$_element[locale("Editor / Pages - Create and configure pages")]="pageconfig";
			$_element[locale("Editor / Pages - CANNOT insert/remove/change components")]="pagenocomponent";
			$_element[locale("Editor / Pages - Stats")]="pagestatistic";
			$_element[locale("Editor / Content")]="content";
			$_element[locale("Editor / Mediabase")]="mediabase";
			$_element[locale("Analyze")]="analyse";

			$_element[locale("Tasks")]="task";

			$_element[locale("Admin rights")]="admin";
			$_element[locale("Rollback")]="rollback";
			$_element[locale("Lightbox")]="lightbox";

			$html = "";
			foreach ($_element AS $key => $val)
			{
				$checked = "";
				if (isset($rechte["elm_" . $val]))
				{
					if ($rechte["elm_" . $val] ==1)
					{
						$checked = "checked";
					}
				}
				$html .='<input name="elm_'.$val. '" type="checkbox" value="1"  '.$checked .'> '.$key .'<br>';
			}

			$myLayout->workarea_row_draw(locale("Elementary rights"),$html);
			$myPT->startbuffer();
		 ?>
		 <table border="0" cellspacing="0" cellpadding="0">
		 <?php
		 $sql = "SELECT * FROM pagegroup ORDER by grp_bez";
		 $rs = $myDB->query($sql);
		 while ($row_grp = mysql_fetch_array($rs))
		 {
		 	$checked = "";
		 	if (isset($rechte["access_grp_" . $row_grp["grp_id"]]))
		 	{
		 		if ($rechte["access_grp_" . $row_grp["grp_id"]] ==1)
		 		{
		 			$checked = "checked";
		 		}
		 	}
		 ?>
         <tr><td>     
		 <input name="access_grp_<?php echo $row_grp["grp_id"] ?>" type="checkbox" value="1" <?php echo $checked ?>> <?php echo $row_grp["grp_bez"] ?>&nbsp;&nbsp;
         </td><td>
		 <select name="pag_id_grp_<?php echo $row_grp["grp_id"] ?>" class="input" style="width:250px">
		 <option value="0"><?php echo localeH("* all pages *");?></option>
		 <?php
		 $sql = "SELECT pag_id AS K, pag_bez AS V FROM page WHERE grp_id = " . $row_grp["grp_id"] . " ORDER BY V";
		 echo $myAdm->buildOptionsBySQL($sql,$rechte["pag_id_grp_" . $row_grp["grp_id"]]);
		 ?>
		 </select></td></tr>
		 <?php
		 }
		 ?>
		 </table>
		 <?php
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw(locale("Pagegroups"),$html);

		 $sql = "SELECT * FROM content ORDER by con_pos, con_bez";
		 $rs = $myDB->query($sql);
		 $html = "";
		 while ($row_content = mysql_fetch_array($rs))
		 {
		 	$checked = "";
		 	if (isset($rechte["con_" . $row_content["con_id"]]))
		 	{
		 		if ($rechte["con_" . $row_content["con_id"]] ==1)
		 		{
		 			$checked = "checked";
		 		}
		 	}
		 	$html .='<input name="con_'.$row_content["con_id"]. '" type="checkbox" value="1"  '.$checked .'> '.$row_content["con_bez"] .'<br>';

		 }

		 $myLayout->workarea_row_draw(locale("Content objects"),$html);


	 	// Mediagruppen
		 $myPT->startbuffer();
		 ?>
		 <table border="0" cellspacing="0" cellpadding="0">
		 <?php
		 $sql = "SELECT * FROM mediagroup ORDER by grp_bez";
		 $rs = $myDB->query($sql);
		 while ($row_grp = mysql_fetch_array($rs))
		 {
		 	$checked = "";
		 	if (isset($rechte["access_mediagrp_" . $row_grp["grp_id"]]))
		 	{
		 		if ($rechte["access_mediagrp_" . $row_grp["grp_id"]] ==1)
		 		{
		 			$checked = "checked";
		 		}
		 	}
		 ?>
         <tr><td>     
		 <input name="access_mediagrp_<?php echo $row_grp["grp_id"] ?>" type="checkbox" value="1" <?php echo $checked ?>> <?php echo $row_grp["grp_bez"] ?>&nbsp;&nbsp;
         </td><td>
		 <?php
		 }
		 ?>
		 </table>
		 <?php
		 $html = $myPT->stopBuffer();
		 $myLayout->workarea_row_draw(locale("Mediagroups"),$html);
		 
			
		 
		 $sql = "SELECT * FROM extra ORDER by ext_bez";
		 $rs = $myDB->query($sql);
		 $html = "";
		 while ($row_extra = mysql_fetch_array($rs))
		 {
		 	$checked = "";
		 	if (isset($rechte["ext_" . $row_extra["ext_id"]]))
		 	{
		 		if ($rechte["ext_" . $row_extra["ext_id"]] ==1)
		 		{
		 			$checked = "checked";
		 		}
		 	}
		 	$html .='<input name="ext_'.$row_extra["ext_id"]. '" type="checkbox" value="1"  '.$checked .'> '.$row_extra["ext_bez"] .'<br>';

		 }

		 $myLayout->workarea_row_draw(locale("Extras"),$html);


		 $sql = "SELECT * FROM ticketsubject ORDER by sbj_bez";
		 $rs = $myDB->query($sql);
		 $html = "";
		 while ($row_subject = mysql_fetch_array($rs))
		 {
		 	$checked = "";
		 	if (isset($rechte["sbj_" . $row_subject["sbj_id"]]))
		 	{
		 		if ($rechte["sbj_" . $row_subject["sbj_id"]] ==1)
		 		{
		 			$checked = "checked";
		 		}
		 	}
		 	$html .='<input name="sbj_'.$row_subject["sbj_id"]. '" type="checkbox" value="1"  '.$checked .'> '.$row_subject["sbj_bez"] .'<br>';
		 }
		 $myLayout->workarea_row_draw(locale("Task subjects"),$html);

		 break;
	}

	 ?>
	 
	 <?php
	 // Abschlusszeile
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="<?php echo localeH("Delete");?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this role?");?>')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save");?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
	 <?php
	 $myLayout->workarea_stop_draw();
	?>
	</form>	
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
























