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

/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Admin_Users_Standard extends PhenotypeBackend_Admin
{

	public $pwstatus = 0; // shows wether the user changed his password or not

	function execute($scope,$action)
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;

		$this->setPageTitle("Phenotype ".$myPT->version. " Administration");

		$this->selectMenuItem(6);


		$this->selectLayout(1);



		$usr_id = $myRequest->getI("id");


		if ($myRequest->check("delete"))
		{
			$action="delete";
		}

		if ($action =="view")
		{
			if (!$this->checkRight("elm_admin"))
			{
				$_params = Array();
				$_params["id"]=$mySUser->id;
				$_params["b"]=0;
				$this->gotoPage("Admin","Users","edit",$_params);
			}

			$this->fillLeftArea($this->renderExplorer($scope,$usr_id));
			$this->fillContentArea1($this->renderList());
			$this->displayPage();
			return;
		}

		// if no user is selected and we are not on top level we have a security breach ...

		if (!$this->checkRight("elm_admin") AND $usr_id != $mySUser->id)
		{
			$this->noAccess();
		}


		if ($action=="insert" AND $this->checkRight("elm_admin"))
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("usr_vorname","Neuer");
			$mySQL->addField("usr_nachname","Benutzer");
			$mySQL->addField("usr_status",1,DB_NUMBER);
			$mySQL->addField("usr_createdate",time());
			$sql = $mySQL->insert("user");
			$myDB->query($sql);

			$usr_id = mysql_insert_id();
		}

		if ($usr_id==0){$this->noAccess();}


		if ($action=="update")
		{
			if (!$this->checkRight("elm_admin") AND $myRequest->getI("b")!=0)
			{
				// Manipulationsversuch über den Block-Parameter
				$this->noAccess();
			}

			$this->update($usr_id);
		}


		if ($action=="delete")
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("usr_status",0,DB_NUMBER);
			$sql = $mySQL->update("user","usr_id=".$usr_id);
			$myDB->query($sql);
			$this->gotoPage("Admin","Users","view");
		}


		// Darstellung des ausgewählten Benutzers

		$this->fillLeftArea($this->renderExplorer($scope,$usr_id));
		$this->fillContentArea1($this->renderEdit($usr_id));
		$this->displayPage();

	}

	function renderEdit($usr_id)
	{
		global $myPT;
		$myPT->startBuffer();

		global $myDB;
		global $myRequest;
		global $myAdm;
		global $myApp;



		$sql = "SELECT * FROM user WHERE usr_id =" . $usr_id . " AND usr_status = 1";
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$rechte = Array();
		$allerechte = Array();
		if ($row["usr_rights"]!=""){$rechte = unserialize($row["usr_rights"]);}
		if ($row["usr_allrights"]!=""){$allerechte = unserialize($row["usr_allrights"]);}
		$preferences = Array();
		if ($row["usr_preferences"]!=""){$preferences = unserialize($row["usr_preferences"]);}

		?>
		<form action="backend.php" method="post" name="editform">
		<input type="hidden" name="page" value="Admin,Users,update">
		<input type="hidden" name="id" value="<?php echo $usr_id ?>">	
		<input type="hidden" name="b" value="<?php echo $_REQUEST["b"] ?>">	
		<?php

		$this->displayHeadline($usr_id." Benutzer / ". $row["usr_vorname"] . " " . $row["usr_nachname"],"http://www.phenotype-cms.de/docs.php?v=23&t=8");




		$this->tab_new();
		$url = "backend.php?page=Admin,Users,edit&amp;id=" .$usr_id ."&b=0";
		$this->tab_addEntry("Konfiguration",$url,"b_konfig.gif");


		$b=0;
		if ($this->checkRight("elm_admin"))
		{
			$url = "backend.php?page=Admin,Users,edit&amp;id=" .$usr_id ."&b=1";
			$this->tab_addEntry("Rechte",$url,"b_utilisation.gif");
			$b=$_REQUEST["b"];
		}

		switch ($b)
		{
			case 0:
				$this->tab_draw("Konfiguration");
				$this->workarea_start_draw();
				$html = $this->workarea_form_text("Vorname","vorname",$row["usr_vorname"]);
				$html .= $this->workarea_form_text("Nachname","nachname",$row["usr_nachname"]);
				$html .= $this->workarea_form_text("Email","email",$row["usr_email"]);
				$this->workarea_row_draw("Name",$html);
				$html = $this->workarea_form_text("Benutzerkennung","login",$row["usr_login"]);
				$html .= 'Passwort (Zum &Auml;ndern 2x eingeben)<br><input name="pass1" type="password" class="input" value="pass" size="10">&nbsp;<input name="pass2" type="password" class="input" value="pass" size="10">';

				switch ($this->pwstatus)
				{
					case 1: $html.= "<br><b>Passwort&auml;nderung fehlgeschlagen.</b><br>";
					break;

					case 2: $html.=  "<br><b>Das Passwort wurde ge&auml;ndert.</b><br>";
					break;
				}


				$this->workarea_row_draw("Login",$html);

				$html = $this->workarea_form_image("userbild",$row["med_id_thumb"],"_users",1);

				$this->workarea_row_draw("Foto",$html);


				$_prefs = $myApp->getUserPrefList();
				$html="";
				foreach ($_prefs AS $k=>$v)
				{
					$checked="";if ($preferences[$k]==1){$checked='checked="checked"';}
					$html .= '<input type="checkbox" name="'.$k.'" value="1" '.$checked.'/>'.$this->getH($v)."<br/>";
				}
				if ($html!=""){$this->workarea_row_draw("Präferenzen",$html);}

				if (isset($allerechte["elm_task"]) )
				{
					$html = "";

					$_prefs = $myApp->getUserPrefListforTickets();

					foreach ($_prefs AS $k=>$v)
					{
						$checked="";if ($preferences[$k]==1){$checked='checked="checked"';}
						$html .= '<input type="checkbox" name="'.$k.'" value="1" '.$checked.'/> '.$this->getH($v)."<br/>";
					}
					$this->workarea_row_draw("Ticket-Einstellungen",$html);

				}


				$html=  "Angelegt am " . date("d.m.Y",$row["usr_createdate"]) . "<br>";
				if ($row["usr_lastlogin"]==0)
				{
					$html .= "Noch nie angemeldet.";
				}
				else
				{
					$html.=  "Letzter Login am " . date("d.m.Y H:i",$row["usr_lastlogin"]);
				}
				$this->workarea_row_draw("Status",$html);

				break;

			case 1:
				$this->tab_draw("Rechte");
				$this->workarea_start_draw();

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

				$this->workarea_row_draw("Rollen",$html);
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
		 $this->workarea_row_draw("Seitengruppen",$html);

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
		 $this->workarea_row_draw("Contentobjekte",$html);


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
		 $this->workarea_row_draw("Mediagruppen",$html);

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

		 $this->workarea_row_draw("Extras",$html);


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
		 $this->workarea_row_draw("Aufgabenbereiche",$html);

		 break;
		}

		// Abschlusszeile
		$this->workarea_row_deletesave("Diesen Benutzer wirklich l&ouml;schen?");


		$this->workarea_stop_draw();
	?>
	</form>	
	<?php
	return ($myPT->stopBuffer());
	}

	function renderList()
	{
		global $myPT;
		$myPT->startBuffer();


		global $myDB;

		$this->displayHeadline("Übersicht Benutzer","http://www.phenotype-cms.de/docs.php?v=23&t=8");

		$_table = array(
		25 =>"ID",
		60 =>" ",
		449=>"Bezeichnung",
		50=>"Aktion"
		);

		$this->displayContentTableHead($_table);

		$sql = "SELECT * FROM user WHERE usr_status = 1 ORDER BY usr_nachname";
		$rs = $myDB->query($sql);

		// Welche Spalten werden als HTML übergeben
		$_html = Array(2,4);

		$_align = Array ("","","","right");
		$_nowrap = Array(0,0,0,1);

		while ($row=mysql_fetch_array($rs))
		{

			$image_thumb = '<span class="tableCellMedia"><a href="backend.php?page=Admin,Users,edit&amp;id='.$row["usr_id"].'&amp;b=0">';

			if ($row["med_id_thumb"]!=0)
			{
				$myImg = new PhenoTypeImage($row["med_id_thumb"]);
				$image_thumb .= $myImg->render_maxX(60,"Benutzer anzeigen");
			}
			else
			{
				$image_thumb .= '<img src="img/t_user.gif" alt="Benutzer anzeigen" width="60" height="40" border="0"/>';
			}
			$image_thumb .= '</a></span>';
			$html_link ='';

			$_row = Array(
			$row["usr_id"],
			$image_thumb,
			$row["usr_vorname"]. " ". $row["usr_nachname"],
			$html_link = '<a href="backend.php?page=Admin,Users,edit&amp;id='.$row["usr_id"].'&amp;b=0"><img src="img/b_edit.gif" alt="Datensatz bearbeiten" width="22" height="22" border="0" align="absmiddle"></a>',
			);

			$this->displayContentTableRow($_row,$_html,$_align);

		}

		$this->displayContentTableFoot();

		$this->displayContentTableButton("Neuen Benutzer anlegen","backend.php?page=Admin,Users,insert");

		return $myPT->stopBuffer();
	}


	function update($usr_id)
	{
		global $myDB;
		global $myRequest;
		global $mySUser;
		global $myApp;

		$mySQL = new SQLBuilder();


		// Konfiguration
		$this->pwstatus=0;
		if ($myRequest->get("b")==0)
		{

			$mySQL->addField("usr_login",$myRequest->get("login"));
			$mySQL->addField("usr_vorname",$myRequest->get("vorname"));
			$mySQL->addField("usr_nachname",$myRequest->get("nachname"));
			$mySQL->addField("usr_email",$myRequest->get("email"));
			$mySQL->addField("med_id_thumb",$myRequest->get("userbildimg_id"));


			if (($myRequest->get("pass1")!="pass") OR ($myRequest->get("pass2")!="pass"))
			{
				$this->pwstatus = 1;
				if (strtolower($myRequest->get("pass1"))==strtolower($myRequest->get("pass2")) AND ($myRequest->get("pass1")!=""))
				{
					$newpass = crypt(strtolower($myRequest->get("pass1")),"phenotype");
					$mySQL->addField("usr_pass",$newpass);
					$this->pwstatus = 2;
				}
			}
			// Preferences
			$_preferences = Array();
			$_prefs = $myApp->getUserPrefList();
			foreach ($_prefs AS $k=>$v)
			{
				if ($myRequest->check($k))
				{
					$_preferences[$k]=1;
				}
				else
				{
					$_preferences[$k]=0;
				}
			}
			$_prefs = $myApp->getUserPrefListforTickets();
			foreach ($_prefs AS $k=>$v)
			{
				if ($myRequest->check($k))
				{
					$_preferences[$k]=1;
				}
				else
				{
					$_preferences[$k]=0;
				}
			}
			$mySQL->addField("usr_preferences",serialize($_preferences));

			$sql = $mySQL->update("user","usr_id=".$usr_id);
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
						$_rechte["elm_task"]=1;
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

			// Mediagruppen
			$sql = "SELECT * FROM mediagroup";
			$rs = $myDB->query($sql);
			$mediazugriff=0;
			while ($row_grp = mysql_fetch_array($rs))
			{
				$fname_access = "access_mediagrp_" . $row_grp["grp_id"];
				if ($myRequest->check($fname_access))
				{

					if ($myRequest->get($fname_access) ==1)
					{
						$_rechte[$fname_access]=1;
						$mediazugriff=1;
					}
					else
					{
						$_rechte[$fname_access]=0;
					}
				}
			}
			if ($mediazugriff==1){$_rechte["elm_mediabase"]=1;}
				

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


			$mySQL->addField("usr_rights",serialize($_rechte));


			$sql = $mySQL->update("user","usr_id=".$usr_id);
			$myDB->query($sql);

			// Mit den aktuellen Rollenrechten auffüllen, dabei werden auch die Aufgabenbereiche
			// in der DB nachgezogen
			$mySUser->buildRights($usr_id);
		}
	}

}
?>
