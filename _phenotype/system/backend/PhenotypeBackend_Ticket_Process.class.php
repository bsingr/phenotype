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


/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Ticket_Process_Standard extends PhenotypeBackend_Ticket
{


	function execute($scope,$action)
	{
			
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;

		$this->checkRight("elm_task",true);

		$tik_id = $myRequest->getI("id");

		// Explorer

		if ($myRequest->check("focus"))
		{
			$focus = $myRequest->getI("focus");
		}
		else
		{
			$focus = 3; // Auf den Hinweisen starten
		}

		$sortorder =  $myRequest->getI("sortorder");
		if ($sortorder==0){$sortorder=1;}

		$block_nr = $myRequest->getI("b");
		if ($block_nr==0){$block_nr=1;}

		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id = $myRequest->getI("dat_id");


		if ($action=="insert")
		{
			$step = $myRequest->getI("step");
			switch ($step)
			{
				case 3:
					$this->processPlanningWizard();
					break;
				case 2:
					$this->processInsertWizard();
					break;
				default:
					$this->displayInsertWizard();
					break;
			}
			return;
		}

		if ($myRequest->check("delete"))
		{

			$sql = "DELETE FROM ticketmarkup WHERE tik_id = " . $tik_id;
			$myDB->query($sql);
			$sql = "DELETE FROM ticketrequest WHERE tik_id = " . $tik_id;
			$myDB->query($sql);
			$sql = "DELETE FROM ticketaction WHERE tik_id = " . $tik_id;
			$myDB->query($sql);
			$sql = "DELETE FROM ticket WHERE tik_id = " . $tik_id;
			$myDB->query($sql);

			$_params = Array();
			$_params["sbj_id"]=$sbj_id;
			$_params["focus"]=$focus;
			$_params["sortorder"]=$sortorder;


			$this->gotoPage("Ticket","Assess","",$_params);
			exit();
		}

		if ($action=="removemarkup")
		{
			//print_R ($_REQUEST);
			if ($myRequest->check("save"))
			{
				$myTicket = new PhenotypeTicket();
				$myTicket->loadById($tik_id);
				$myTicket->removeMarkups();
			}
			
			if ($myRequest->check("overview"))
			{
				$_params = Array();
				$_params["sbj_id"]=$myRequest->getI("sbj_id");
				$_params["focus"]=$myRequest->getI("focus");
				$_params["sortorder"]=$myRequest->getI("sortorder");
				$_params["dat_id_2ndorder"]=$myRequest->getI("dat_id_2ndorder");
				$marker = "tik".$tik_id;
				$this->gotoPage("Ticket","Assess","",$_params,$marker);
			}
			
			if ($myRequest->getI("popup")==1)
			{
				$action="actionpopup";
			}
		}

		if ($action=="actionpopup")
		{
			$myTicket = new PhenotypeTicket();
			$myTicket->loadById($tik_id);
			$this->displayActionLog($myTicket,0,0,0,0,1);
			return;
		}

		$this->setPageTitle("Phenotype ".$myPT->version. " Aufgaben");

		$this->selectMenuItem(5);
		$this->selectLayout(1);








		// Pin?
		if ($action=="pin")
		{
			$this->pin($tik_id);
		}

		if ($action=="update")
		{
			$block_nr = $this->update($tik_id,$block_nr,$sbj_id,$dat_id,$focus,$sortorder);
		}

		$this->fillLeftArea($this->renderExplorer($scope,$sbj_id,$dat_id,$focus,$sortorder));

		$this->fillContentArea1($this->renderEdit($tik_id,$block_nr,$sbj_id,$dat_id,$focus,$sortorder,$block_nr));


		$this->displayPage();

	}

	function renderEdit($tik_id,$block_nr,$sbj_id,$dat_id,$focus,$sortorder,$block_nr)
	{
		global $myPT;
		$myPT->startBuffer();

		global $myDB;
		global $myLayout;


		// Meine Hinweise und Anfragen in temporaere Tabellen
		$this->storeRequestsTemporary();
		$this->storeMarkupsTemporary();
		$this->storePinsTemporary();

		// Grund-SQL fuer alle Detailabfragen
		$sql_join = $this->getBaseSelectSQL();

		$sql = $sql_join . " WHERE ticket.tik_id = " .$tik_id;


		$rs = $myDB->query($sql);
		$this->listTickets($rs,1,1,"Process",$sbj_id,$dat_id,$focus,$sortorder,$block_nr);

		$myTicket = new PhenotypeTicket();
		$myTicket->loadById($tik_id);



		echo "<br/>";

		// --------------------------------------------------------------------------------------------
		// Reiter initialisieren
		// --------------------------------------------------------------------------------------------

		$params = "&focus=".$focus."&sortorder=".$sortorder."&sbj_id=".$sbj_id."&dat_id=".$dat_id;
		
		$myLayout->tab_new();
		$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=1" . $params;
		$myLayout->tab_addEntry("Bearbeiten",$url,"b_edit_w.gif");
		$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=2". $params;
		$myLayout->tab_addEntry("Planung",$url,"b_konfig.gif");
		if ($myTicket->row["usr_id_owner"]==$_SESSION["usr_id"] OR $myPT->getIPref("tickets.show_notices_to_all_users")==1)
		{
			$notizen ="Notizen";
			if ($myTicket->row["tik_notice"]!=""){$notizen="Notizen !";}
			$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=7". $params;
			$myLayout->tab_addEntry($notizen,$url,"b_noticecomment.gif");
		}
		$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=3". $params;
		$myLayout->tab_addEntry("Verlauf",$url,"b_viewtrans.gif");


		if ($block_nr==1 OR $block_nr==5 OR $block_nr==6)
		{
			$myLayout->tab_draw("Bearbeiten");
		}
		if ($block_nr==2)
		{
			$myLayout->tab_draw("Planung");
		}

		if ($block_nr==3)
		{
			$myLayout->tab_draw("Verlauf");
		}

		if ($block_nr==7)
		{
			$myLayout->tab_draw($notizen);
		}

		// --------------------------------------------------------------------------------------------

		$myLayout->workarea_start_draw();

		?>
		<form enctype="multipart/form-data" name="editform" method="post" action="backend.php">	
		<input type="hidden" name="id" value="<?=$tik_id?>">
		<input type="hidden" name="b" value="<?=$block_nr?>">
		<input type="hidden" name="sbj_id" value="<?=$sbj_id?>">
		<input type="hidden" name="dat_id_2ndorder" value="<?=$dat_id?>">
		<input type="hidden" name="focus" value="<?=$focus?>">
		<input type="hidden" name="sortorder" value="<?=$sortorder?>">
		<?

		switch ($block_nr)
		{
			case 1: // Bearbeiten
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			$this->displayDetailMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder);

			break;
			case 2: // Planen
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			$this->displayPlanningMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder);

			break;
			case 3:
				echo '<input type="hidden" name="page" value="Ticket,Process,removemarkup">';
				$this->displayActionLog($myTicket,$sbj_id,$dat_id,$focus,$sortorder);

				break;
			case 5: // Hinweis
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			$this->displayMarkupMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder);

			break;
			case 6: // Frage
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			$this->displayRequestMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder);

			break;
			case 7: // Notizen
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			$this->displayNoticesMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder);
			echo '<input type="hidden" name="page" value="Ticket,Process,update">';
			break;

		}
		echo "</form>";
		$myLayout->workarea_stop_draw();

		// Temptabellen wieder loeschen
		$this->removeTemporaryTables();
		return ($myPT->stopBuffer());
	}

	// --------------------------------------------------------------------------------------------

	function displayDetailMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{

		global $mySmarty;
		global $myDB;
		global $myAdm;
		global $myLayout;
		global $myPT;
		global $mySUser;
	?>

	<?
	$html = '<input type="radio" name="step" value="1" checked> Kommentar/Dokumentation ';
	$html .= '<input type="radio" name="step" value="2"> Anfrage ';
	$html .= '<input type="radio" name="step" value="3"> Hinweis ';
	$myLayout->workarea_row_draw("Aktion",$html);

	$html="";
	$options=Array();;
	for ($i=0;$i<=100;$i=$i+10)
	{
		$options[$i]=$i . "%";
		//$options .='<option value="'.$i.'" >'.$i.'%</option>';
	}
	$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_percentage"]);
	if ($myTicket->row["tik_complexity"]!=6)
	{ // Kein Fortschritt bei Daueraufgaben
		$html = $myLayout->workarea_form_select("Fortschritt","progress",$options,50) . "<br>";
	}
	else
	{
	?>
	<input type="hidden" name="progress" value="<?=$myTicket->row["tik_percentage"]?>">
	<?
	}
	$options='<option value="0"></option><option value="5">5 Minuten</option><option value="10">10 Minuten</option><option value="15">15 Minuten</option><option value="20">20 Minuten</option><option value="30">30 Minuten</option><option value="45">45 Minuten</option><option value="60">1 Stunde</option><option value="90">1,5 Stunden</option><option value="120">2 Stunden</option><option value="180">3 Stunden</option><option value="240">4 Stunden</option><option value="300">5 Stunden</option><option value="360">6 Stunden</option><option value="420">7 Stunden</option><option value="480">8 Stunden</option><option value="540">9 Stunden</option><option value="600">10 Stunden</option>';
	$html .= $myLayout->workarea_form_select("Dauer","duration",$options,100);
	$myLayout->workarea_row_draw("Verlauf",$html);
	$myPT->startbuffer();
	?>
		<script language="JavaScript">
		function taketime1()
		{
			t = document.forms.editform.timeframe1.selectedIndex;
			document.forms.editform.datum1.value = document.forms.editform.timeframe1[document.forms.editform.timeframe1.selectedIndex].value;
		}
</script>
	<input type="text" name="datum1" size="10" value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>" class="input">&nbsp;&nbsp;
<select name="timeframe1" onchange="javascript:taketime1();" class="input">
<option value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>">Vorgabe</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
</select>
	<?
	$html = $myPT->stopbuffer();

	$myLayout->workarea_row_draw("R&uuml;ckstellung",$html);
	$html ='<input name="userfile" type="file" class="input">';
	$myLayout->workarea_row_draw("Anhang",$html);

	$html =   $myLayout->workarea_form_textarea("","comment","",8);


	if ($myTicket->row["tik_status"]==0)
	{
		$html .='<input type="checkbox" name="reopen" value="1"> Ticket reaktivieren.';
	}
	else
	{
		if ($myTicket->row["usr_id_owner"]==$_SESSION["usr_id"])
		{
			$checked ="checked";
			if ($myTicket->row["tik_accepted"]==1)
			{
				$html .='<input type="checkbox" name="reject" value="1" onclick="document.forms.editform.close.checked=0;"> Aufgabe abgeben &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.reject.checked=0;"> Ticket schliessen';
			}
			else
			{
				$html .='<input type="checkbox" name="accept" value="1" checked onclick="document.forms.editform.reject.checked=0;document.forms.editform.close.checked=0;"> Aufgabe &uuml;bernehmen &nbsp;<input type="checkbox" name="reject" value="1" onclick="document.forms.editform.accept.checked=0;document.forms.editform.close.checked=0;"> Aufgabe abgeben &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.reject.checked=0;document.forms.editform.accept.checked=0;"> Ticket schliessen';
			}
		}
		else
		{
			$html .='<input type="checkbox" name="accept" value="1" onclick="document.forms.editform.close.checked=0;"> Aufgabe &uuml;bernehmen &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.accept.checked=0;"> Ticket schliessen';
		}
	}
	$myLayout->workarea_row_draw("Kommentar",$html);
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><?if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser") ){?><input name="delete" type="submit" class="buttonWhite" style="width:102px"value="L&ouml;schen" onclick="javascript:return confirm('Dieses Ticket wirklich l&ouml;schen?')">&nbsp;<?}?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>

	<?
	
	}

	// --------------------------------------------------------------------------------------------

	function displayPlanningMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myDB;
		global $myAdm;
		global $myLayout;
		global $myPT;
		global $mySUser;
		?>
		<script language="JavaScript">
		function taketime1()
		{
			t = document.forms.editform.timeframe1.selectedIndex;
			document.forms.editform.datum1.value = document.forms.editform.timeframe1[document.forms.editform.timeframe1.selectedIndex].value;
		}
		function taketime2()
		{
			t = document.forms.editform.timeframe2.selectedIndex;
			v = document.forms.editform.timeframe2[document.forms.editform.timeframe2.selectedIndex].value;
			if (v==-1){v=document.forms.editform.datum1.value;}
			document.forms.editform.datum2.value =v;
		}
		function taketime3()
		{
			t = document.forms.editform.timeframe3.selectedIndex;
			document.forms.editform.datum3.value = document.forms.editform.timeframe3[document.forms.editform.timeframe3.selectedIndex].value;
		}
</script>
		<?
		$html = $myLayout->workarea_form_text("Bezeichnung","bez",$myTicket->row["tik_bez"]);
		$sql ="SELECT ticketsubject.sbj_id AS K, sbj_bez AS V FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $_SESSION["usr_id"] . " ORDER BY sbj_bez";
		$options = $myAdm->buildOptionsBySQL($sql,$myTicket->row["sbj_id"]);
		$html .= $myLayout->workarea_form_select("Bereich","sbj_id_ticket",$options);
		if ($myPT->getIPref("tickets.con_id_2ndorder")!=0)
		{

			$bez = $myPT->getPref("tickets.bez_2ndorder");

			$dat_id= $myTicket->row["dat_id_2ndorder"];
			$_options = $this->build2ndOrderOptionsArray($dat_id);
			$_options = Array(0=>"...")+$_options;


			$html.=$myLayout->workarea_form_select2($bez,"dat_id_2ndorder",$dat_id,$_options);
		}

		$myLayout->workarea_row_draw("Meta",$html);
		$options = Array (1=>"++ H&ouml;chste Priorit&auml;t",2=>"+&nbsp; vorrangig",3=>"o&nbsp; Standard",4=>"-&nbsp; nachrangig");
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_prio"]);
		$html = $myLayout->workarea_form_select("","priority",$options);
		$myPT->startbuffer();
		?>
		<br>R&uuml;ckstellung:<br>
		<input type="text" name="datum3" size="10" value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>" class="input">&nbsp;&nbsp;
		<select name="timeframe3" onchange="javascript:taketime3();" class="input">
		<option value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>">Vorgabe</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>
		<?
		$html .= $myPT->stopbuffer();
		$myLayout->workarea_row_draw("Priorit&auml;t",$html);

		$sql = "SELECT CONCAT(usr_nachname,', ',usr_vorname) AS V, usr_id AS K FROM user WHERE usr_status = 1  ORDER BY usr_nachname,usr_vorname";
		$options = $myAdm->buildOptionsBySQL($sql,$myTicket->row["usr_id_owner"]);
		$options = '<option value="0">N.N.</option>' . $options;
		$html = $myLayout->workarea_form_select("","usr_id",$options);
		$myLayout->workarea_row_draw("Bearbeiter",$html);

		$myPT->startbuffer();
		global $mySUser;
		$ticketdatum = mktime(0,0,0,date('m',$myTicket->row["tik_creationdate"]),date('d',$myTicket->row["tik_creationdate"]),date('Y',$myTicket->row["tik_creationdate"]));
		$tage = time() - $ticketdatum;
		$tage = floor($tage/(60*60*24))+1;

   		?>
		eingestellt am <?=date('d.m.Y H:i',$myTicket->row["tik_startdate"])?> von <?=$mySUser->getName($myTicket->row["usr_id_creator"])?>. (Tag <?=$tage?>)
		<?
		if ($myTicket->row["tik_complexity"]!=6)
		{
		?>
		<br><br>
		Limit:<br>
		<input type="text" name="datum1" size="10" value="<?=date("d.m.Y",$myTicket->row["tik_enddate"]);?>" class="input">&nbsp;&nbsp;
		<select name="timeframe1" onchange="javascript:taketime1();" class="input">
		<option value="<?=date("d.m.Y",$myTicket->row["tik_enddate"]);?>">Vorgabe</option>
		<option value="<?=date("d.m.Y");?>">heute</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>
		<br>
		Ziel:<br>
		<input type="text" name="datum2" size="10" value="<?if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);}?>" class="input">&nbsp;&nbsp;
		<select name="timeframe2" onchange="javascript:taketime2();" class="input">
		<option value="<?if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);}?>">Vorgabe</option>
		<option value="-1">Limit</option>
		<option value="<?=date("d.m.Y");?>">heute</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>
		<?
		}
		else
		{
		?>
		<input type="hidden" name="datum1" value="<?=date("d.m.Y",$myTicket->row["tik_enddate"]);?>">
		<input type="hidden" name="datum2" size="10" value="<?if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);}?>" class="input">
		<?
		}

		$html = $myPT->stopbuffer();
		$myLayout->workarea_row_draw("Terminplanung",$html);
		$options = Array (0=>"ohne Schätzung",1=>"Stunde",2=>"Tag",3=>"Wenige Tage",4=>"Woche",5=>"Monat",6=>"Daueraufgabe");
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_complexity"]);
		$html = $myLayout->workarea_form_select("Komplexität:","complexity",$options,150);
		$options = Array (0=>"keine Angabe",1=>"positiv","negativ");
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_tendency"]);
		$html .= $myLayout->workarea_form_select("Tendenz:","tendency",$options,150);

		if ($myTicket->row["tik_complexity"]!=6){
			$html.= "<br>Die Aufgabe ist zu " . $myTicket->row["tik_percentage"] . "% abgeschlossen.";
		}
		if ($myTicket->row["tik_duration"]!=0)
		{
			if ($myTicket->row["tik_duration"]<=90)
			{
				$html.="<br>Bisheriger dokumentierter Aufwand " .$myTicket->row["tik_duration"]. " Minuten.<br>";
			}
			else
			{
				$stunden = floor($myTicket->row["tik_duration"]/30)/2;
				$stunden = str_replace(".",",",$stunden);
				$html.="Bisheriger dokumentierter Aufwand " .$stunden. " Stunden.<br>";
			}

		}


		$myLayout->workarea_row_draw("Resourcenplanung",$html);

		$html =   $myLayout->workarea_form_textarea("","comment","",8);
		$myLayout->workarea_row_draw("Kommentar",$html);
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><?if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser") )
	{?><input name="delete" type="submit" class="buttonWhite" style="width:102px"value="L&ouml;schen" onclick="javascript:return confirm('Dieses Ticket wirklich l&ouml;schen?')">&nbsp;<?}?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?
	}

	// --------------------------------------------------------------------------------------------

	function displayMarkupMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myLayout;
		global $myDB;
		global $myRequest;

		$sql = "SELECT * FROM user_ticketsubject LEFT JOIN user ON user_ticketsubject.usr_id = user.usr_id WHERE sbj_id = " . $myTicket->row["sbj_id"] . " AND usr_status = 1 AND user.usr_id !=" .$_SESSION["usr_id"] . " ORDER BY usr_nachname, usr_vorname";
		$rs = $myDB->query($sql);
		$user = "";
		while ($row = mysql_fetch_array($rs))
		{
			$user .= '<input type="checkbox" value="1" name="usr_id_'. $row["usr_id"].'"> ' . $row["usr_nachname"];
			if ($row["usr_vorname"] !="" AND $row["usr_nachname"]!=""){$user.=", ";}
			$user .= $row["usr_vorname"] ."<br/>";
		}
		$html = "&nbsp;W&auml;hlen Sie jetzt die Benutzer aus, die Sie auf Ihren Kommentar hinweisen m&ouml;chten:<br><br>".$user."<br><br><br><br><br><br><br><br>";
		$myLayout->workarea_row_draw("Benutzer",$html);
  		?>
   		<input type="hidden" name="comment" value="<?=htmlentities($myRequest->get("comment"))?>">
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Ausf&uuml;hren">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?
	}

	// --------------------------------------------------------------------------------------------

	function displayRequestMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myLayout;
		global $myDB;
		global $myRequest;

		$sql = "SELECT * FROM user_ticketsubject LEFT JOIN user ON user_ticketsubject.usr_id = user.usr_id WHERE sbj_id = " . $myTicket->row["sbj_id"] . " AND usr_status = 1 ORDER BY usr_nachname, usr_vorname";
		$rs = $myDB->query($sql);
		$user = "";
		while ($row = mysql_fetch_array($rs))
		{
			$user .= '<input type="checkbox" value="1" name="usr_id_'. $row["usr_id"].'"> ' . $row["usr_nachname"];
			if ($row["usr_vorname"] !="" AND $row["usr_nachname"]!=""){$user.=", ";}
			$user .= $row["usr_vorname"] ."<br/>";
		}
		$html = "&nbsp;W&auml;hlen Sie jetzt die Benutzer aus, die auf Ihre Frage mit einem Kommentar antworten sollen:<br/><br/>".$user."<br/><br/><br/><br/><br/><br/><br/><br/>";
		$myLayout->workarea_row_draw("Benutzer",$html);
		?>
    <input type="hidden" name="comment" value="<?=htmlentities($myRequest->get("comment"))?>">
   	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Ausf&uuml;hren">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?
	}

	function displayNoticesMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myLayout;
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><br>
			<?
			$myLayout->form_Richtext("description",$myTicket->row["tik_notice"],80,25,640)
			?>
			</td>
            </tr>
          <tr>
            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
			</table>
			
	 	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?
	}


	function displayActionLog($myTicket,$sbj_id,$dat_id,$focus,$sortorder,$popup=0)
	{
		global $myDB;
		global $myPT;
		global $mySUser;

		if ($popup==1)
		{
			$table_x = "98%";
		}
		else
		{
			$table_x = 640;
		}
		$id = $myTicket->id;

		if ($myPT->getIPref("tickets.active_markup_removal")==0)
		{
			// Check, ob ein Markup vorliegt
			$sql = "SELECT * FROM ticketmarkup WHERE tik_id = " . $id. " AND usr_id = " . $_SESSION["usr_id"];
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs)!=0)
			{
				$myTicket = new PhenotypeTicket();
				$myTicket->loadById($_REQUEST["id"]);
				$act_id =  $myTicket->logAction(6);
				$sql = "DELETE FROM ticketmarkup WHERE tik_id = " . $id . " AND usr_id = " . $_SESSION["usr_id"];
				$myDB->query($sql);
			}
		}

		$ticketdatum = mktime(0,0,0,date('m',$myTicket->row["tik_creationdate"]),date('d',$myTicket->row["tik_creationdate"]),date('Y',$myTicket->row["tik_creationdate"]));
		if ($popup==1)
		{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>phenotype <?=$myPT->version?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="task.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		<!--
		body {
			margin-top: 2px;
			margin-bottom: 2px;
		}
		-->
		</style>
		</head>
		<body>
		<form enctype="multipart/form-data" name="editform" method="post" action="backend.php">	
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="page" value="Ticket,Process,removemarkup"/>
		<input type="hidden" name="popup" value="1"/>
		<script language="JavaScript">
		self.focus();
		</script>
		<table width="<?=$table_x?>" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTitle">Ticketverlauf</td>
		        <td align="right" class="windowTitle"></td>
		      </tr>
		    </table></td>
		  </tr>
		</table>
		<?
		}
		$_prio = Array(1=>"++ H&ouml;chste Priorit&auml;t",2=>"vorrangig",3=>"Standard",4=>"nachrangig");
		$_komplex = Array("ohne Schätzung","Stunde","Tag","Wenige Tage","Woche","Monat","Daueraufgabe");
		$_tendency = Array("keine Angabe","positiv","negativ");
		$_tag = Array("So","Mo","Di","Mi","Do","Fr","Sa","So");
		$_details = Array();


		$woche = "";
		$datum = "";
		$uhrzeit ="";
		$user = "";
		$grafik = "";
		$sql = "SELECT * FROM ticketaction WHERE tik_id = " . $_REQUEST["id"] . " ORDER BY act_date DESC,act_id";
		$rs = $myDB->query($sql);


		while ($row = mysql_fetch_array($rs))
		{
			// Details des aktuellen Arbeitsschrittes holen
			$_details = unserialize ($row["act_details"]);

			// Wochenwechsel ?
			if ($myPT->nextMonday($row["act_date"])!=$woche)
			{
				if ($woche =="")
				{
					$startdatum = $myPT->nextMonday($row["act_date"])-1;
					$zieldatum = $myPT->nextMonday($row["act_date"]);
				}
				else
				{
					$startdatum = $myPT->nextMonday($woche);// Der letzten Woche
					$zieldatum = $myPT->nextMonday($row["act_date"]);
				}

				$aktwoche = $startdatum;
				$woche = $aktwoche;

				while ($aktwoche < $zieldatum)
				{


					$wochentag = date("w",$aktwoche);
					if ($wochentag==0){$wochentag=7;}
					$montag = mktime(0,0,0, date("m",$aktwoche),date("d",$aktwoche)-$wochentag+1,date("Y",$aktwoche));
					$sonntag = mktime(0,0,0, date("m",$aktwoche),date("d",$aktwoche)+(7-$wochentag),date("Y",$aktwoche));

  				?>
				<br>
				<table width="<?=$table_x?>" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
				    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
				      <tr>
				        <td class="windowTitle">Woche <?=date("W",$montag)?>/<?=date("Y",$sonntag)?> vom <?=date("d.m.y",$montag)?> - <?=date("d.m.y",$sonntag)?></td>
				        <td align="right" class="windowTitle">&nbsp;</td>
				      </tr>
				    </table></td>
				  </tr>
				</table>
  				<?

  				$aktwoche = $myPT->nextMonday($aktwoche+60*60*24);
  				$woche = $aktwoche;
				}
			}
			// -- Wochenwechsel

			// Datumswechsel
			$zeigedatum = 0;
			$rowdatum = mktime(0,0,0,date('m',$row["act_date"]),date('d',$row["act_date"]),date('Y',$row["act_date"]));
			if ($datum != $rowdatum)
			{
				$zeigedatum=1;
				$datum = $rowdatum;
			}
			// -- Datumswechsel

			// Uhrzeitwechsel
			$zeigeuhrzeit = 0;
			$rowuhrzeit = mktime (date('H',$row["act_date"]),date('i',$row["act_date"]),0,date('m',$row["act_date"]),date('d',$row["act_date"]),date('Y',$row["act_date"]));
			if ($uhrzeit != $rowuhrzeit)
			{
				$zeigeuhrzeit=1;
				$uhrzeit = $rowuhrzeit;
			}
			// Uhrzeitwechsel

			// Userwechsel
			if ($user!=$row["usr_id"])
			{
				$user = $row["usr_id"];
				$zeigehurzeit=1;
			}
			// -- Userwechsel
			$wochentag = date("w",$row["act_date"]);

			if ($wochentag==0){$wochentag=7;}


			if (isset($_details["startgrafik"]))
			{
				$zeigegrafik1=0;
				$zeigegrafik2=0;
				if ($grafik!=$_details["startgrafik"])
				{
					$zeigegrafik1=1; // Achtung war vorher genau anders rum, aber warum ??
					$grafik1=$_details["startgrafik"];
					$grafik = $_details["startgrafik"];
				}

				if ($grafik!=$_details["grafik"])
				{
					$zeigegrafik2=1;
					$grafik2=$_details["grafik"];

				}



				$grafik=$_details["grafik"];
			}
			else
			{
				// Ticketaktion hat nur einen Grafikstatus (keine Aenderung oder migriertes Ticket)
				$zeigegrafik1=0;
				$zeigegrafik2=0;
				if ($grafik != $_details["grafik"]){$zeigegrafik1=1;}

				$grafik1=$_details["grafik"];
				$grafik=$_details["grafik"];
			}


			?>



<table width="<?=$table_x?>" border="0" cellpadding="0" cellspacing="0" align="center" class="window">
<tr>
    <td width="50" valign="top" class="taskTopCorner">
	<!-- Ticketstatus zu Beginn des Arbeitsschrittes -->
	<?
	if ($zeigegrafik1==1)
	{
	?>
	<img src="img/<?=$grafik1?>" alt="" width="48" height="36" vspace="6" border="0">
	<?
	}
	?>
	</td>
	<td width="60"  valign="top" class="taskTopCorner" rowspan="2">
	<p align="center"><img src="img/transparent.gif" width="3" height="9"><br>
	<?
	if ($zeigedatum==1)
	{

		$tage = $datum - $ticketdatum;
		$tage = floor($tage/(60*60*24))+1;
	?>
	<strong><?=$_tag[$wochentag]?> <?=date('d.m.',$datum)?></strong><br>(Tag <?=$tage?>)
	<?
	}
	?>
	</p>
	</td>
    <td class="taskData" rowspan="2" >
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
    <tr>
    <td class="textarea">
	<table width="98%" border="0" cellpadding="2" cellspacing="0" align="center">
	<!-- Wer und Wann  -->
	<?
	if ($zeigeuhrzeit==1)
	{
	?>
	<tr>
	<td width="55" valign="top" class="taskTopCorner"><p><?=date("H:i",$uhrzeit)?></p></td>
	<td  class="taskTopCorner"><?=$mySUser->getName($row["usr_id"])?></td>
	</tr>
	<?
	}
	?>
	<!-- Aktion -->
	<?
	switch ($row["act_type"])
	{
		// Anlage
		case 1:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_newtask2.gif" alt="" width="17" height="17"  vspace="1" hspace="2" border="0"></td>
	<td valign="top" >Ticket erstellt.</td>
	</tr>
	<tr>
	<td align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >
	Bezeichnung: <?=$myPT->getH($_details["bez"])?><br>
	Bereich:  <?=$myPT->getH($_details["subject"])?><br>
	Zieldatum: <?=date('d.m.Y',$_details["enddate"])?><br>
	Priorit&auml;t: <?=$_prio[$_details["prio"]]?><br>
	Bearbeiter: <?=$myPT->getH($_details["owner"])?><br>
	</td>
	</tr>	
	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>
	<?
	break;
	?>
	<?
	// Delegation
		case 2:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_myjob.gif" width="22" height="22"></td>
	<td valign="top" >Ticket an <?=$myPT->getH($_details["newowner"])?> delegiert.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>
	<?
	break;
	?>	
	<?
	// Ticket akzeptiert
		case 3:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/i_working.gif" width="25" height="18"></td>
	<td valign="top" >Ticket akzeptiert.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td width="55"  align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>
	<?
	break;
	?>
	<?
	// Ticket bearbeitet
		case 4:
	?>
<tr>
		  <td  width="55" align="center" valign="top" height="25">
		   <img src="img/transparent.gif" width="3" height="5"><br>
		 <? if ($_details["complexity"]!=6){?>
               <table width="50" border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<?
						$w = floor($_details["percentage"]*48/100);
						?>
                          <td align="left" class="taskProgress"><img src="img/task_progressline.gif" width="<?=$w?>" height="3" alt=" <?=$_details["percentage"]?> %" title=" <?=$_details["percentage"]?> %"></td>
                        </tr>
                    </table>
				  <?}?>
		 
		 </td>
		  <td valign="top" >
		  <?
		  if ($_details["minutes"]>0)
		  {
		  ?>
		  <?=$_details["minutes"]?> Minuten 
		  <?
		  }
		  else
		  {
		  ?>
		  Ticket
		  <?
		  }
		  ?>
		  bearbeitet
		  <?
		  if ($_details["percentage"]!=$_details["oldpercentage"])
		  {
		  ?>
		  , Fertigstellungsgrad <?=$_details["percentage"]?>%.
		  <?
		  }else{
		  ?>
		  .
		  <?
		  }
		  ?>
		  </td>
		  </tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>
	<?
	break;
	?>
	<?
	// Ticket geschlossen
		case 5:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><?if ($_details["percentage"]!=100){?><img src="img/t_closed0.gif" width="17" height="17" hspace="1"><?}else{?><img src="img/t_closed100.gif" width="17" height="17" hspace="1"><?}?><img src="img/t_closed.gif" width="22" height="22"></td>
	<td valign="top" >Ticket geschlossen. </td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>	
	<?
	// Ticket angesehen
		case 6:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_view.gif" width="22" height="22"></td>
	<td valign="top" >Ticket angesehen.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>	
	<?
	// Ticket kommentiert
		case 7:
	?>
	<?
	if ($row["act_comment"]!="")
	{
		if ($row["usr_id"]==$_details["usr_id_owner"])
		{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_edit.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
		}else{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
		}
	}
	break;
	?>		
	<?
	// Ticket zur&uuml;ckgewiesen
		case 8:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_myjob.gif" width="22" height="22"></td>
	<td valign="top" >Ticket zur&uuml;ckgewiesen.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;

		case 9:
	?>
	<tr>
	<td width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >
	<?
	if ($_details["newsubject"]!=""){
	?>
	Bezeichnung: <?=$myPT->getH($_details["newsubject"])?><br>
	<?
	}
	?>
	<?
	if ($_details["newenddate"]!=""){
	?>
	Enddatum: <?=date('d.m.Y',$_details["newenddate"])?><br>
	<?
	}
	?>
	<?
	if ($_details["newestimationdate"]!=""){
	?>
	Zieldatum: <?
	if ($_details["newestimationdate"]==0){echo"keine Festlegung";}else{echo date('d.m.Y',$_details["newestimationdate"]);}
	?><br>
	<?
	}
	?>	
	<?
	if ($_details["newsleepdate"]!=""){
	?>
	R&uuml;ckstellung: <?=date('d.m.Y',$_details["newsleepdate"])?><br>
	<?
	}
	?>	
	<?
	if ($_details["newpriority"]!=""){
	?>
	Priorit&auml;t: <?=$_prio[$_details["newpriority"]]?><br>
	<?
	}
	?>
	<?
	if ($_details["newcomplexity"]!=""){
	?>
	Komplexit&auml;t: <?=$_komplex[$_details["newcomplexity"]]?><br>
	<?
	}
	?>
	<?
	if ($_details["newtendency"]!=""){
	?>
	Tendenz: <?=$_tendency[$_details["newtendency"]]?><br>
	<?
	}
	?>	
			

	</td>
	</tr>	
	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>
	<?
	break;
	?>
	<?
	// Notizen ge&auml;andert
		case 10:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_noticecomment.gif" width="22" height="22"></td>
	<td valign="top" >Ticketnotizen bearbeitet.</td>
	</tr>
	<?
	break;
	?>	
	<?
	// Bereich gewechselt
		case 11:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >Bereich: <?=$myPT->getH($_details["newsubject"])?></td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>
	<?
	// Ticket widerbelebt
		case 12:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_newtask2.gif" width="17" height="17" vspace="1" hspace="2"></td>
	<td valign="top" >Ticket erneut ge&ouml;ffnet.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>	
<?
// Hinweis
		case 13:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_notice.gif" width="22" height="22"></td>
	<td valign="top" >Hinweis f&uuml;r <?=$myPT->getH($_details["aim"])?></td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25" >&nbsp;</td>
    <td valign="top" class="taskTopCorner"><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;

	// Frage
		case 14:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_request.gif" width="22" height="22"></td>
	<td valign="top" >Anfrage f&uuml;r <?=$myPT->getH($_details["aim"])?></td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25" >&nbsp;</td>
    <td valign="top" class="taskTopCorner"><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	?>			
	<?
	break;
	?>	
	<?
	// Ticket zurueckgestellt
		case 15: //
		//print_r ($_details);
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >Ticket bis <?=$_tag[date('w',$_details["sleepdate"])]?> <?=date('d.m.y',$_details["sleepdate"])?> zur&uuml;ckgestellt.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>	
	<?
	// Ticket-Rueckstellung aufgehoben
		case 16:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >Ticket-R&uuml;ckstellung aufgehoben.</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>	
	<?
	// Dokument angeh&auml;ngt
		case 17:
			$med_id = $_details["med_id"];
			$sql = "SELECT * FROM media WHERE med_id = " . $med_id;
			$rs_att = $myDB->query($sql);
			if (mysql_num_rows($rs_att)!=0)
			{
				$row_att = mysql_fetch_array($rs_att);
				if ($row_att["med_type"]==MB_IMAGE)
				{
					$myDoc = new PhenoTypeImage($row_att["med_id"]);
					{
						$attachment = $myDoc->render_fixX(280);
					}
				}
				else
				{
					$myDoc = new PhenotypeDocument($row_att["med_id"]);
					$attachment = $row_att["med_bez"];
				}
			}
			else
			{
				$attachment ="Dokument ist nicht mehr in der Mediabase enthalten.";
			}

	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_attach.gif" width="17" height="17" vspace="1" hspace="2"></td>
	<td valign="top" >Dokument <a href="<?=$myDoc->url?>" target="_blank"><?=$myDoc->bez?></a> angeh&auml;ngt.<br>
	<br><a href="<?=$myDoc->url?>" target="_blank"><?=$attachment?></a>
	</td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;
	?>		
		<?
		// Bereich gewechselt
		case 18:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" ><?=$myPT->getPref("tickets.bez_2ndorder")?>: <?=$myPT->getH($_details["new2ndorder"])?></td>
	</tr>

	<?
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?=nl2br(htmlentities($row["act_comment"]))?></td>
    </tr>	
	<?
	}
	break;

		case 19:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_notice.gif" width="22" height="22"></td>
	<td valign="top" >Hinweismerker gelöscht.</td>
	</tr>
	
	<?
	break;
		case 20:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_request.gif" width="22" height="22"></td>
	<td valign="top" >Anfragemerker gelöscht.</td>
	</tr>
	<?
	break;
	// Ende switch case
	}
	?>	
	</table>
    </td>
	</tr>
	</table>
	</td>
</tr>
<tr>
<td  class="taskTopCorner">
<!-- Ticketstatus zu Ende des Arbeitsschrittes -->
<?
if ($zeigegrafik2==1)
{
?>
<img src="img/<?=$grafik2?>" alt="" width="48" height="36" vspace="6" border="0">
<?
}
?>
</td>
</tr>
</table>
<?
		}

		echo "<br/><br/>";
		$html_button1="";
		$html_button2="";
		if ($popup==0)
		{
			$html_button1 ='<input name="overview" type="submit" class="buttonWhite" style="width:102px"value="zur Übersicht">';

		}
		if ($myPT->getIPref("tickets.active_markup_removal")==1 OR  $myPT->getIPref("tickets.active_request_removal")==1)
		{
			if ($myTicket->hasMarkup($mySUser->id) OR $myTicket->hasRequest($mySUser->id))
			{

				$html_button2 ='<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Hinweis entfernen">';
			}
		}
		?>
	 	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite" align="left">&nbsp;&nbsp<?=$html_button1?>&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?=$html_button2?>&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?

        if ($popup==1)
        {
        	?>
        	<form></body></html>
        	<?
        }
	}


	function displayInsertWizard()
	{
		global $myRequest;
		global $myAdm;
		global $myPT;
		global $myDB;
		global $mySUser;

		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id = $myRequest->getI("dat_id");
		$med_id = $myRequest->getI("med_id");
		$pag_id = $myRequest->getI("pag_id");
		$ver_id = $myRequest->getI("ver_id");
		$dat_id_2ndorder = $myRequest->getI("dat_id_2ndorder");

		$express = $myRequest->getI("express");
		
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>phenotype <?=$myPT->version?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="media.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		<!--
		body {
			margin-top: 2px;
			margin-bottom: 2px;
			padding-left: 0px;
		}
		-->
		</style>
		<script language="JavaScript">self.focus();</script>
		<script language="JavaScript">
		function taketime()
		{
			t = document.forms.form1.timeframe.selectedIndex;
			document.forms.form1.datum.value = document.forms.form1.timeframe[document.forms.form1.timeframe.selectedIndex].value;
		}
		function checkForm()
		{
			if (document.forms.form1.bez.value=="" | document.forms.form1.bez.value=="Neue Aufgabe")
			{
				alert ('Bitte wählen Sie eine Bezeichung für diese Aufgabe.');
				return false;
			}

			if (document.forms.form1.datum.value=="")
			{
				alert ('Bitte wählen Sie ein Zeitlimit für diese Aufgabe.');
				return false;
			}
		}
		</script>
		</head>
		
		<body>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTitle">Neue Aufgabe hinzuf&uuml;gen</td>
		        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		<br/>
		<?
		$url = 'backend.php?page=Ticket,Process,insert&pag_id=' .$pag_id. '&ver_id=' .$ver_id. '&dat_id=' . $dat_id . '&med_id=' . $med_id .'&sbj_id=' .$sbj_id. "&dat_id_2ndorder=".$dat_id_2ndorder."&express=0";

		$this->tab_addEntry("Standard-Ticket",$url,"b_job.gif");
		
		$url = 'backend.php?page=Ticket,Process,insert&pag_id=' .$pag_id. '&ver_id=' .$ver_id. '&dat_id=' . $dat_id . '&med_id=' . $med_id .'&sbj_id=' .$sbj_id. "&dat_id_2ndorder=".$dat_id_2ndorder."&express=1";
		$this->tab_addEntry("Express-Ticket",$url,"b_myjob.gif");
		if ($express==1)
		{
			$this->tab_draw("Express-Ticket",$x=450,0);
		}
		else
		{
			$this->tab_draw("Standard-Ticket",$x=450,0);
		}
		?>
		<form action="backend.php" method="post" enctype="multipart/form-data" name="form1"  onsubmit="return checkForm();">
		<input type="hidden" name="page" value="Ticket,Process,insert"/>
		<input type="hidden" name="step" value="2"/>
		<input type="hidden" name="express" value="<?=$express?>"/>

			<table width="100%" border=0" cellpadding="0" cellspacing="0" align="center" class="window">
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><strong>Bezeichnung</strong> der Aufgabe: <br>
		              <input name="bez" type="text" class="input" style="width: 300px" value="Neue Aufgabe">
		          </td>
		          </tr>
		        <tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><strong>Einordnung</strong><br>
				  
		<table cellspacing="0" cellpadding="0">
		<tr>
		<td width="100">Bereich:</td>
		<td>
		<select name="sbj_id" class="listmenu" style="width: 200px" >
		<?

		$sql ="SELECT * FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $_SESSION["usr_id"] . " ORDER BY sbj_bez";

		if ($express==1)
		{
			$sbj_id = $myPT->getIPref("tickets.sbj_id_expressticket");
		}

		$rs = $myDB->query($sql);

		while ($row = mysql_fetch_array($rs))
		{
			$selected ="";
			if ($sbj_id==$row["sbj_id"]){$selected="selected";}
			?>
			<option value="<?=$row["sbj_id"]?>" <?=$selected?>><?=$myPT->getH($row["sbj_bez"])?></option>
			<?
		}

		?>
		</select>
		</td>
		</tr>
		<?
		if ($myPT->getIPref("tickets.con_id_2ndorder")!=0 AND $express==0)
		{
		?>
		<tr>
		<td width="100"><br><?=$myPT->getH($myPT->getPref("tickets.bez_2ndorder"))?>:</td>
		<td><br>
		<select name="dat_id_2ndorder" class="listmenu" style="width: 200px" >
		<option value="0">...</option>
		<?

		$sql ="SELECT dat_id,dat_bez FROM content_data WHERE con_id=" . $myPT->getIPref("tickets.con_id_2ndorder") . " AND dat_status=1 ORDER BY dat_bez";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$selected ="";
			if ($dat_id_2ndorder==$row["dat_id"]){$selected="selected";}
			?>
			<option value="<?=$row["dat_id"]?>" <?=$selected?>><?=$myPT->getH($row["dat_bez"])?></option>
			<?
		}
		?>
		</select>
		</td>
		</tr>
		<?
		}

		if ($pag_id!=0)
		{
			$bez = $myAdm->getPageName($pag_id,$ver_id);
			?>
			<input type="hidden" name="pag_id" value="<?=$pag_id?>">
			<input type="hidden" name="ver_id" value="<?=$ver_id?>">
			<tr>
			<td width="100">
			<br>Seite:
			</td>
			<td ><br><p class="input"><?=$myPT->getH($bez)?></p></td>
			</tr>
			<?
		}

		if ($dat_id!=0)
		{
			$bez = $myAdm->getContentName($dat_id);
			?>
			<input type="hidden" name="dat_id" value="<?=$dat_id?>">
			<tr>
			<td>
			<br>Content-Datensatz:
			</td>
			<td ><br><p class="input"><?=$myPT->getH($bez)?></p></td>
			</tr>
		<?
		}
		if ($med_id!=0)
		{
			$myMB = new PhenotypeMediabase;
			$bez = $myMB->getMediaObjectName($med_id);
			?>
			<input type="hidden" name="med_id" value="<?=$med_id?>">
			<tr>
			<td>
			<br>Mediaobjekt:
			</td>
			<td ><br><p class="input"><?=$myPT->getH($bez)?></p></td>
			</tr>
			<?
		}
		?>
		<tr>
		<td valign="top"><br>Priorit&auml;t:</td>
		<td  colspan="3" ><br>
		<?
		if ($express==1)
		{
		?>
		<select name="priority" style="width: 200px" class="listmenu" ><option value="3" selected>o&nbsp; Standard</option></select>
		<?
		}
		else
		{
		?>
		<select name="priority" style="width: 200px" class="listmenu" ><option value="1" >++ H&ouml;chste Priorit&auml;t</option><option value="2" >+&nbsp; vorrangig</option><option value="3" selected>o&nbsp; Standard</option><option value="4" >-&nbsp; nachrangig</option></select>
		<?
		}
		?>
		<br><br>
		</td>
		</tr>
		<tr>
		<td>Zeitraum:</td>
		<td  colspan="3">
		<?
		if ($express==1)
		{
		?>
		<select name="timeframe" class="listmenu">
		<option value="<?=date("d.m.Y");?>">heute</option>
		</select>&nbsp;&nbsp;<input type="hidden" name="datum" size="10" value="<?=date("d.m.Y");?>" class="input" /><?=date("d.m.Y");?>
		<?
		}
		else
		{
		?>
		<select name="timeframe" onchange="javascript:taketime()" class="listmenu">
		<option value=""></option>
		<option value="<?=date("d.m.Y");?>">heute</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>&nbsp;&nbsp;<input type="text" name="datum" size="10" value="" class="input">
		<?
		}
		?>
		<br><br>
		</td>
		</tr>
		<tr>
		<td>f&uuml;r:</td>
		<td  colspan="3">
		<select name="usr_id" class="listmenu" style="width: 200px" >
		<?
		$sql = "SELECT * FROM user WHERE usr_status = 1 ORDER by usr_vorname, usr_nachname";
		
		if ($express==1)
		{
			$sql = "SELECT * FROM user WHERE usr_id=".$mySUser->id;
			
		}
		else 
		{
			echo '<option value="0">N.N.</option>';	
		}
		$rs = $myDB->query($sql);
		$options="";
		while ($row = mysql_fetch_array($rs))
		{
			$selected="";
			if ($row["usr_id"]==$_SESSION["usr_id"]){$selected="selected";}
			$options .='<option value="'. $row["usr_id"] .'" '. $selected.'>'. $row["usr_vorname"]." " . $row["usr_nachname"] .'</option>';
		}
		echo $options;
		?>
		</select><br><br>
		</td>
		</tr>
		</table>		  
				
		          </td>
		          </tr>
		        <tr>
		           <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		  <?
		  if ($express==1)
		  {
		  	?>
	
		  <tr>
		      <td colspan="5" valign="top" class="tableBody">
		      	<table cellspacing="0" cellpadding="0">
		        	<tr>
						<td width="100"><strong>Dauer</strong></td>
						<td>
						<select name="duration" class="input"><option value="0"></option><option value="5">5 Minuten</option><option value="10">10 Minuten</option><option value="15">15 Minuten</option><option value="20">20 Minuten</option><option value="30">30 Minuten</option><option value="45">45 Minuten</option><option value="60">1 Stunde</option><option value="90">1,5 Stunden</option><option value="120">2 Stunden</option><option value="180">3 Stunden</option><option value="240">4 Stunden</option><option value="300">5 Stunden</option><option value="360">6 Stunden</option><option value="420">7 Stunden</option><option value="480">8 Stunden</option><option value="540">9 Stunden</option><option value="600">10 Stunden</option></duration>
						</td>
					</tr>
				</table>
		      </td>
		  </tr>
		  <tr>
		  	<td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		  </tr>
		  <?
		  }
		  ?>        
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><table cellspacing="0" cellpadding="0">
		
		          
		         <tr>
		<td width="100"><strong>Anhang</strong></td>
		<td><input name="userfile" type="file" class="input"></td></tr></table>
		          </td>
		          </tr>
		        
		        <tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>				
		        <tr>
		          <td colspan="5" valign="top" class="tableBody">1. <strong>Kommentar</strong><br>
		              <textarea name="comment" rows="8"style="width: 390px" class="input" wrap="physical"></textarea><br>
		          </td>
		          </tr>
		        <tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>		
		</table>		
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowFooterWhite"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td align="right" class="windowTitle"><input name="Submit" type="submit" class="buttonWhite" value="Speichern" style="width:102px"></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		</form>
		</body>
		</html>
		<?	
	}

	function processInsertWizard()
	{
		// Request-Parameter haben andere Bedeutung als bei den anderen Assess-Methoden
		global $myRequest;
		global $mySUser;
		global $myPT;

		$myTicket = new PhenotypeTicket();
		$titel = $myRequest->get("bez");

		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id = $myRequest->getI("dat_id");
		$med_id = $myRequest->getI("med_id");
		$pag_id = $myRequest->getI("pag_id");
		$ver_id = $myRequest->getI("ver_id");
		$dat_id_2ndorder = $myRequest->getI("dat_id_2ndorder");
		
		$express = $myRequest->getI("express");
		
		$usr_id_creator = $mySUser->id;
		$usr_id_owner = $myRequest->get("usr_id");
		$priority = $myRequest->get("priority");

		// Das Enddatum wird errechnet
		$enddate = $myPT->german2timestamp($myRequest->get("datum"));


		$comment = $myRequest->get("comment");


		$act_id = $myTicket->create($titel,$comment,$sbj_id,$usr_id_creator,$usr_id_owner,$enddate,$priority,$dat_id_2ndorder);

		if ($pag_id!=0)
		{
			$myTicket->gluePage($pag_id,$ver_id);
		}
		if ($dat_id !=0)
		{
			$myTicket->glueContent($dat_id);
		}
		if ($med_id !=0)
		{
			$myTicket->glueMedia($med_id);
		}

		if($express==1)
		{
			$duration = $myRequest->getI("duration");
			$myTicket->workon("",$mySUser->id,$duration,100);
			$myTicket->close("",$mySUser->id,100);
	
		}
		
		// Anhang
		$fname = "userfile";
		$size =  $_FILES[$fname]["size"];
		if ($size !=0)
		{
			$myTicket->attach_document("",$fname);

		}

		if ($mySUser->id != $usr_id_owner OR $express==1)
		{
			$this->displayRedirToTicket($myTicket->id,$sbj_id,$dat_id_2ndorder);
		}

		$this->displayPlanningWizard($myTicket);
	}



	function processPlanningWizard()
	{
		global $myRequest;
		global $mySUser;
		global $myPT;

		$tik_id = $myRequest->getI("tik_id");

		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id_2ndorder = $myRequest->getI("dat_id_2ndorder");


		$myTicket = new PhenotypeTicket();
		$myTicket->loadByID($tik_id);

		if (isset($_REQUEST["save"])) // Nur bei Speichern
		{
			if (isset($_REQUEST["accept"]))
			{
				$myTicket->accept("",$mySUser->id);
			}

			$comment = $myRequest->get("comment");

			$prio = $myTicket->row["tik_prio"];
			$bez = $myTicket->row["tik_bez"];
			$sbj_id = $myTicket->row["sbj_id"];

			$date = $myPT->german2timestamp($myRequest->get("datum1"));
			$date2 = $myPT->german2timestamp($myRequest->get("datum2"));
			if ($date2==""){$date2=0;}
			$date3 = $myPT->german2timestamp($myRequest->get("datum3"));
			if ($date3==""){$date3=0;}
			$myTicket->adjust($comment,$bez,$prio,$myRequest->getI("complexity"),$myRequest->getI("tendency"),$date,$date2,$date3,$sbj_id,$dat_id_2ndorder);
		}

		$this->displayRedirToTicket($tik_id,$sbj_id,$dat_id_2ndorder);
	}

	function displayRedirToTicket($tik_id,$sbj_id,$dat_id)
	{

		$url = "backend.php?page=Ticket,Process,edit&id=".$tik_id . "&sbj_id=" . $sbj_id ."&dat_id_2ndorder=".$dat_id."&b=1";

		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>phenotype 2.2</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript">
		top.opener.location = "<?=$url?>";
		self.close();
		</script>
		</head>
		<body>
		</body>
		</html>
		<?
	}

	function displayPlanningWizard($myTicket)
	{
		global $myPT;
		global $myAdm;
		global $myLayout;

		global $myRequest;

		$tik_id = $myTicket->id;

		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id_2ndorder = $myRequest->getI("dat_id_2ndorder");
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>phenotype 2.2</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="media.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		<!--
		body {
			margin-top: 2px;
			margin-bottom: 2px;
		}
		-->
		</style>
		<script language="JavaScript">self.focus();</script>
			<script language="JavaScript">
			function taketime1()
			{
				t = document.forms.editform.timeframe1.selectedIndex;
				document.forms.editform.datum1.value = document.forms.editform.timeframe1[document.forms.editform.timeframe1.selectedIndex].value;
			}
			function taketime2()
			{
				t = document.forms.editform.timeframe2.selectedIndex;
				v = document.forms.editform.timeframe2[document.forms.editform.timeframe2.selectedIndex].value;
				if (v==-1){v=document.forms.editform.datum1.value;}
				document.forms.editform.datum2.value =v;
			}
			function taketime3()
			{
				t = document.forms.editform.timeframe3.selectedIndex;
				document.forms.editform.datum3.value = document.forms.editform.timeframe3[document.forms.editform.timeframe3.selectedIndex].value;
			}
		</script>
		</head>
		
		<body>
		
		<form action="backend.php" method="post" enctype="multipart/form-data" name="editform"  onsubmit="return checkForm();">
		<input type="hidden" name="tik_id" value="<?=$tik_id?>">
		<input type="hidden" name="sbj_id" value="<?=$sbj_id?>">
		<input type="hidden" name="dat_id_2ndorder" value="<?=$dat_id_2ndorder?>">
		<input type="hidden" name="page" value="Ticket,Process,insert"/>
		<input type="hidden" name="step" value="3"/>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTitle">Neue Aufgabe planen</td>
		        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
			<table width="100%" border=0" cellpadding="0" cellspacing="0" align="center" class="window">
				<tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>
		        <tr>
				  <td width="10">&nbsp;</td>
		          <td valign="top" width="110"><br><strong>Terminplanung</strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br>
				  		
						Limit:<br>
		<input type="text" name="datum1" size="10" value="<?=date("d.m.Y",$myTicket->row["tik_enddate"]);?>" class="input">&nbsp;&nbsp;
		<select name="timeframe1" onchange="javascript:taketime1();" class="input">
		<option value="<?=date("d.m.Y",$myTicket->row["tik_enddate"]);?>">Vorgabe</option>
		<option value="<?=date("d.m.Y");?>">heute</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>
		<br>
		Ziel:<br>
		<input type="text" name="datum2" size="10" value="<?if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);}?>" class="input">&nbsp;&nbsp;
		<select name="timeframe2" onchange="javascript:taketime2();" class="input">
		<option value="<?if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);}?>">Vorgabe</option>
		<option value="-1">Limit</option>
		<option value="<?=date("d.m.Y");?>">heute</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select>
			<br>R&uuml;ckstellung:<br>
			<input type="text" name="datum3" size="10" value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>" class="input">&nbsp;&nbsp;
		<select name="timeframe3" onchange="javascript:taketime3();" class="input">
		<option value="<?if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>">Vorgabe</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y')));?>">morgen</option>
		<option value="<?=$myPT->nextFriday(time(),1)?>">Ende der Woche</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y')));?>">n&auml;chste Woche</option>
		<option value="<?=$myPT->nextMonday(time(),1)?>">n&auml;chsten Montag</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y')));?>">in zwei Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y')));?>">in vier Wochen</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y')));?>">in 2 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y')));?>">in 3 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y')));?>">in 6 Monaten</option>
		<option value="<?=date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1));?>">in 1 Jahr</option>
		</select><br><br>
				  </td>
				</tr>		
				<tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>	
		        <tr>
				  <td width="10">&nbsp;</td>
		          <td valign="top" width="110"><br><strong>Resourcenplanung</strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br><?
				  $options = Array (0=>"ohne Schätzung",1=>"Stunde",2=>"Tag",3=>"Wenige Tage",4=>"Woche",5=>"Monat",6=>"Daueraufgabe");
				  $options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_complexity"]);
				  $html = $myLayout->workarea_form_select("Komplexität:","complexity",$options,150);
				  $options = Array (0=>"keine Angabe",1=>"positiv","negativ");
				  $options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_tendency"]);
				  $html .= $myLayout->workarea_form_select("Tendenz:","tendency",$options,150);
			echo $html;?><br>
				  </td>
				</tr>		
				<tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>		
		        <tr>
				  <td width="10">&nbsp;</td>
		          <td valign="top" width="110"><br><strong>Kommentar</strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br><textarea name="comment" rows="6" style="width: 250px" class="input" wrap="physical"></textarea>
				  
				  		<br><input type="checkbox" value="1" name="accept" checked> Aufgabe &uuml;bernehmen<br><br>
				  </td>
				</tr>		
					
		         
		</table>		
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowFooterWhite"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td align="right" class="windowTitle"><input name="close" type="submit" class="buttonWhite" value="Schliessen" style="width:102px">&nbsp;<input name="save" type="submit" class="buttonWhite" value="Speichern" style="width:102px"></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		</form>
		</body>
		</html>
		<?
	}


	function update($tik_id,$block_nr,$sbj_id,$dat_id,$focus,$sortorder)
	{
		$myTicket = new PhenotypeTicket();
		$myTicket->loadByID($tik_id);



		switch ($block_nr)
		{
			case 1: // Bearbeiten
			$block_nr_neu = $this->updateDetailMask($myTicket);
			break;
			case 2: // Planen
			$block_nr_neu = $this->updatePlanningMask($myTicket);
			break;
			case 6: // Hinweis
			$block_nr_neu = $this->updateMarkupMask($myTicket);
			break;
			case 5: // Frage
			$block_nr_neu = $this->updateRequestMask($myTicket);
			break;
			case 7: // Notizen
			$block_nr_neu = $this->updateNoticesMask($myTicket);
			break;

		}

		return $block_nr_neu;
	}


	function updateDetailMask($myTicket)
	{
		global $myRequest;
		global $myPT;

		$block_nr_neu=2;

		$comment = $myRequest->get("comment");

		if ($_REQUEST["step"]==2 OR $_REQUEST["step"]==3)
		{
			$comment2= $comment;
			$comment="";
		}

		if (isset($_REQUEST["accept"]))
		{
			$myTicket->accept($comment,$_SESSION["usr_id"]);
			$comment="";
		}


		if (isset($_REQUEST["reopen"]))
		{
			$myTicket->reopen($comment);
			$comment="";
		}


		$date = $myPT->german2timestamp($_REQUEST["datum1"]);
		if ($date==""){$date=0;}
		if ($date!=$myTicket->row["tik_sleepdate"])
		{
			if ($date > time())
			{
				$myTicket->hide($date,$comment);
				$comment="";
			}
			else
			{
				$myTicket->show($comment);
				$comment="";
			}
		}

		$minuten = $_REQUEST["duration"];
		if (!($minuten==0 AND $myTicket->row["tik_percentage"]==$_REQUEST["progress"]))
		{
			$myTicket->workon($comment,$_SESSION["usr_id"],$minuten,$_REQUEST["progress"]);
			$comment="";
		}


		if (isset($_REQUEST["close"]))
		{
			$myTicket->close($comment,$_SESSION["usr_id"],$_REQUEST["progress"]);
			$comment="";
		}

		if (isset($_REQUEST["reject"]))
		{
			$myTicket->reject($comment,$_SESSION["usr_id"]);
			$comment="";
		}

		// Anhang
		$fname = "userfile";
		$size =  $_FILES[$fname]["size"];
		if ($size !=0)
		{
			$myTicket->attach_document($comment,$fname);
			$comment="";
		}


		// Nur ein Kommentar
		if ($comment!="")
		{
			$myTicket->comment($comment);
			$comment="";
		}

		if ($_REQUEST["step"]==2)
		{
			$block_nr_neu= 5;
		}
		if ($_REQUEST["step"]==3)
		{
			$block_nr_neu= 6;
		}

		return ($block_nr_neu);
	}

	function updatePlanningMask($myTicket)
	{
		global $myRequest;
		global $myPT;

		$block_nr_neu=1;
		$comment = $myRequest->get("comment");

		$sbj_id=$_REQUEST["sbj_id_ticket"];
		$dat_id_2ndorder = $myRequest->getI("dat_id_2ndorder");

		$date = $myPT->german2timestamp($_REQUEST["datum1"]);
		if ($date==""){$date=$myTicket->row["tik_enddate"];}
		$date2 = $myPT->german2timestamp($_REQUEST["datum2"]);
		if ($date2==""){$date2=0;}
		$date3 = $myPT->german2timestamp($_REQUEST["datum3"]);
		if ($date3==""){$date3=0;}

		$change = $myTicket->adjust($comment,$myRequest->get("bez"),$_REQUEST["priority"],$_REQUEST["complexity"],$_REQUEST["tendency"],$date,$date2,$date3,$sbj_id,$dat_id_2ndorder);
		if ($change){$comment="";}

		if ($myTicket->row["usr_id_owner"]!=$_REQUEST["usr_id"])
		{
			$myTicket->delegate($comment,$_REQUEST["usr_id"]);
			$comment="";
		}

		// Nur ein Kommentar
		if ($comment!="")
		{
			$myTicket->comment($comment);
			$comment="";
		}
		return ($block_nr_neu);
	}

	function updateMarkupMask($myTicket)
	{
		global $myRequest;
		global $myPT;
		global $myDB;

		$comment = $myRequest->get("comment");

		$_users = Array();

		$sql = "SELECT * FROM user_ticketsubject LEFT JOIN user ON user_ticketsubject.usr_id = user.usr_id WHERE sbj_id = " . $myTicket->row["sbj_id"] . " AND usr_status = 1";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$fname = "usr_id_" . $row["usr_id"];
			if (isset($_REQUEST[$fname]))
			{
				$_users[] = $row["usr_id"];
			}
		}

		if (count($_users)!=0)
		{
			$myTicket->markup($comment,$_users);
		}

		return (1);
	}

	function updateRequestMask($myTicket)
	{
		global $myRequest;
		global $myPT;
		global $myDB;


		$comment = $myRequest->get("comment");

		$_users = Array();

		$sql = "SELECT * FROM user_ticketsubject LEFT JOIN user ON user_ticketsubject.usr_id = user.usr_id WHERE sbj_id = " . $myTicket->row["sbj_id"] . " AND usr_status = 1";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$fname = "usr_id_" . $row["usr_id"];
			if (isset($_REQUEST[$fname]))
			{
				$_users[] = $row["usr_id"];
			}
		}

		if (count($_users)!=0)
		{
			$myTicket->request($comment,$_users);
		}
		return (1);
	}

	function updateNoticesMask($myTicket)
	{
		global $myRequest;
		global $myPT;
		$text = $myRequest->get("description");
		$text = $myPT->strip_tags($text);
		$myTicket->saveDescription($text);
		return (7);
	}

}