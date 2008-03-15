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
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
$id = $myRequest->getI("id");
?>
<?php
if (!$mySUser->checkRight("elm_admin") AND $id != $mySUser->id)
{
	$url = "noaccess.php";
	Header ("Location:" . $url);
	exit();
}
?>
<?php
$myAdm->header("Admin");
?>
<body>
<?php
$myAdm->menu("Admin");
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare("Admin","Benutzer");
$myAdm->explorer_set("usr_id",$id);
if (!$mySUser->checkRight("elm_admin"))
{
	$myAdm->explorer_set("littleadmin",1);
}
else
{
	$myAdm->explorer_set("littleadmin",0);
}
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
$sql = "SELECT * FROM user WHERE usr_id =" . $id . " AND usr_status = 1";
$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$rechte = Array();
$allerechte = Array();
if ($row["usr_rights"]!=""){$rechte = unserialize($row["usr_rights"]);}
if ($row["usr_allrights"]!=""){$allerechte = unserialize($row["usr_allrights"]);}
$preferences = Array();
if ($row["usr_preferences"]!=""){$preferences = unserialize($row["usr_preferences"]);}
?> 
 <form action="admin_user_update.php" method="post" name="editform">
	<input type="hidden" name="id" value="<?php echo $id ?>">	
	<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">		
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $id ?> Benutzer / <?php echo $row["usr_vorname"] ?> <?php echo $row["usr_nachname"] ?></td>
            <td align="right" class="windowTitle"><a href="http://www.phenotype-cms.de/docs.php?v=23&t=8" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
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
	$myLayout->tab_addEntry("Konfiguration",$url,"b_konfig.gif");


	$b=0;
	if ($mySUser->checkRight("elm_admin"))
	{
		$url = "admin_user_edit.php?id=" .$id ."&b=1";
		$myLayout->tab_addEntry("Rechte",$url,"b_utilisation.gif");
		$b=$_REQUEST["b"];
	}

	switch ($b)
	{
		case 0:
			$myLayout->tab_draw("Konfiguration");
			$myLayout->workarea_start_draw();
			$html = $myLayout->workarea_form_text("Vorname","vorname",$row["usr_vorname"]);
			$html .= $myLayout->workarea_form_text("Nachname","nachname",$row["usr_nachname"]);
			$myLayout->workarea_row_draw("Name",$html);
			$html = $myLayout->workarea_form_text("Benutzerkennung","login",$row["usr_login"]);
			$html .= 'Passwort (Zum &Auml;ndern 2x eingeben)<br><input name="pass1" type="password" class="input" value="pass" size="10">&nbsp;<input name="pass2" type="password" class="input" value="pass" size="10">';
			if (isset($_REQUEST["ps"]))
			{
				switch ($_REQUEST["ps"])
				{
					case 1: $html.= "<br><b>Passwort&auml;nderung fehlgeschlagen.</b><br>";
					break;

					case 2: $html.=  "<br><b>Das Passwort wurde ge&auml;ndert.</b><br>";
					break;
				}
			}

			$myLayout->workarea_row_draw("Login",$html);

			$html = $myLayout->workarea_form_image("userbild",$row["med_id_thumb"],"_users",0);
			//$html = $myLayout->workarea_form_image("userbild",$row["med_id_thumb"]);
			$myLayout->workarea_row_draw("Foto",$html);

			$html = $myLayout->workarea_form_text("Email","email",$row["usr_email"]);


			if (isset($allerechte["elm_task"]) )
			{
				$myPT->startBuffer();
		   ?>
		   <?php $checked="";if (isset($preferences["pref_ticket_markup"])){$checked="checked";} ?>
		 <input name="pref_ticket_markup" type="checkbox" value="1" <?php echo $checked ?>> Auf neue Aufgaben und Veränderungen per Mail hinweisen.<br>
         <?php $checked="";if (isset($preferences["pref_ticket_overview"])){$checked="checked";} ?>
         <input name="pref_ticket_overview" type="checkbox" value="1" <?php echo $checked ?>> Zu Wochenbeginn Aufgabenübersicht verschicken.<br>
   		   <?php
   		   $html .= $myPT->stopBuffer();
			}

			$myLayout->workarea_row_draw("Email",$html);
			$html=  "Angelegt am " . date("d.m.Y",$row["usr_createdate"]) . "<br>";
			if ($row["usr_lastlogin"]==0)
			{
				$html .= "Noch nie angemeldet.";
			}
			else
			{
				$html.=  "Letzter Login am " . date("d.m.Y H:i",$row["usr_lastlogin"]);
			}
			$myLayout->workarea_row_draw("Status",$html);

			break;

		case 1:
			$myLayout->tab_draw("Rechte");
			$myLayout->workarea_start_draw();

			$sql = "SELECT * FROM role ORDER by rol_bez";
			$rs = $myDB->query($sql);
			$html = "";
			while ($row_role = mysql_fetch_array($rs))
			{
				$checked = "";
				if (isset($rechte["rol_" . $row_role["rol_id"]]))
				{
					if ($rechte["rol_" . $row_role["rol_id"]] ==1)
					{
						$checked = "checked";
					}
				}
				$html .='<input name="rol_'.$row_role["rol_id"]. '" type="checkbox" value="1"  '.$checked .'> '.$row_role["rol_bez"] .'<br>';

			}

			if ($row["usr_su"]==1)
			{
				$html .='<input type="checkbox" value="" checked disabled> <b>SuperUser</b>';
			}

			$myLayout->workarea_row_draw("Rollen",$html);
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
		 <option value="0">* alle Seiten * </option>
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
		 $myLayout->workarea_row_draw("Seitengruppen",$html);

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
		 $myLayout->workarea_row_draw("Contentobjekte",$html);

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

		 $myLayout->workarea_row_draw("Extras",$html);


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
		 $myLayout->workarea_row_draw("Aufgabenbereiche",$html);

		 break;
	}

	 ?>
	 
	 <?php
	 // Abschlusszeile
	 ?>
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Diesen Benutzer wirklich l&ouml;schen?')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
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
























