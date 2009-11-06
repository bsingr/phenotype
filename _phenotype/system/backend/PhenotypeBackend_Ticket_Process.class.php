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


/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Ticket_Process_Standard extends PhenotypeBackend_Ticket
{

    public $tmxfile = "Ticket";
    
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

		$this->setPageTitle("Phenotype ".$myPT->version. " ".locale("Tasks"));

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
		$myLayout->tab_addEntry(locale("Edit"),$url,"b_edit_w.gif");
		$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=2". $params;
		$myLayout->tab_addEntry(locale("Planning"),$url,"b_konfig.gif");
		if ($myTicket->row["usr_id_owner"]==$_SESSION["usr_id"] OR $myPT->getIPref("tickets.show_notices_to_all_users")==1)
		{
			$notizen =locale("Notices");
			if ($myTicket->row["tik_notice"]!=""){$notizen="Notizen !";}
			$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=7". $params;
			$myLayout->tab_addEntry($notizen,$url,"b_noticecomment.gif");
		}
		$url = "backend.php?page=Ticket,Process,edit&id=" .$tik_id . "&b=3". $params;
		$myLayout->tab_addEntry(locale("Log"),$url,"b_viewtrans.gif");


		if ($block_nr==1 OR $block_nr==5 OR $block_nr==6)
		{
			$myLayout->tab_draw(locale("Edit"));
		}
		if ($block_nr==2)
		{
			$myLayout->tab_draw(locale("Planning"));
		}

		if ($block_nr==3)
		{
			$myLayout->tab_draw(locale("Log"));
		}

		if ($block_nr==7)
		{
			$myLayout->tab_draw($notizen);
		}

		// --------------------------------------------------------------------------------------------

		$myLayout->workarea_start_draw();

		?>
		<form enctype="multipart/form-data" name="editform" method="post" action="backend.php">	
		<input type="hidden" name="id" value="<?php echo $tik_id ?>">
		<input type="hidden" name="b" value="<?php echo $block_nr ?>">
		<input type="hidden" name="sbj_id" value="<?php echo $sbj_id ?>">
		<input type="hidden" name="dat_id_2ndorder" value="<?php echo $dat_id ?>">
		<input type="hidden" name="focus" value="<?php echo $focus ?>">
		<input type="hidden" name="sortorder" value="<?php echo $sortorder ?>">
		<?php

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

	<?php
	$html = '<input type="radio" name="step" value="1" checked> '. localeH("Comment/Documentation");
	$html .= ' <input type="radio" name="step" value="2"> '.localeH("Information");
	$html .= ' <input type="radio" name="step" value="3"> '.locale("Question");
	$myLayout->workarea_row_draw(locale("Action"),$html);

	$html="";
	$options=Array();
	for ($i=0;$i<=100;$i=$i+10)
	{
		$options[$i]=$i . "%";
		//$options .='<option value="'.$i.'" >'.$i.'%</option>';
	}
	$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_percentage"]);
	if ($myTicket->row["tik_complexity"]!=6)
	{ // Kein Fortschritt bei Daueraufgaben
		$html = $myLayout->workarea_form_select(locale("Progress"),"progress",$options,50) . "<br>";
	}
	else
	{
	?>
	<input type="hidden" name="progress" value="<?php echo $myTicket->row["tik_percentage"] ?>">
	<?php
	}
	$options='<option value="0"></option><option value="5">'.localeH("5 minutes").'</option><option value="10">'.localeH("10 minutes").'</option><option value="15">'.localeH("15 minutes").'</option><option value="20">'.localeH("20 minutes").'</option><option value="30">'.localeH("30 minutes").'</option><option value="45">'.localeH("45 minutes").'</option><option value="60">'.localeH("1 hour").'</option><option value="90">'.localeH("1,5 hours").'</option><option value="120">'.localeH("2 hours").'</option><option value="180">'.localeH("3 hours").'</option><option value="240">'.localeH("4 hours").'</option><option value="300">'.localeH("5 hours").'</option><option value="360">'.localeH("6 hours").'</option><option value="420">'.localeH("7 hours").'</option><option value="480">'.localeH("8 hours").'</option><option value="540">'.localeH("9 hours").'</option><option value="600">'.localeH("10 hours").'</option>';
	$html .= $myLayout->workarea_form_select(locale("Duration"),"duration",$options,100);
	$myLayout->workarea_row_draw(locale("Log"),$html);
	$myPT->startbuffer();
	?>
		<script language="JavaScript">
		function taketime1()
		{
			t = document.forms.editform.timeframe1.selectedIndex;
			document.forms.editform.datum1.value = document.forms.editform.timeframe1[document.forms.editform.timeframe1.selectedIndex].value;
		}
</script>
	<input type="text" name="datum1" size="10" value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);} ?>" class="input">&nbsp;&nbsp;
<select name="timeframe1" onchange="javascript:taketime1();" class="input">
<option value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);} ?>"><?php echo localeH("set target")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
</select>
	<?php
	$html = $myPT->stopbuffer();

	$myLayout->workarea_row_draw(locale("Deferral"),$html);
	$html ='<input name="userfile" type="file" class="input">';
	$myLayout->workarea_row_draw(locale("Attachment"),$html);

	$html =   $myLayout->workarea_form_textarea("","comment","",8);


	if ($myTicket->row["tik_status"]==0)
	{
		$html .='<input type="checkbox" name="reopen" value="1"> '.localeH("reactivate ticket");
	}
	else
	{
		if ($myTicket->row["usr_id_owner"]==$_SESSION["usr_id"])
		{
			$checked ="checked";
			if ($myTicket->row["tik_accepted"]==1)
			{
				$html .='<input type="checkbox" name="reject" value="1" onclick="document.forms.editform.close.checked=0;"> '.localeH("give back ticket").' &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.reject.checked=0;"> '.locale("close ticket");
			}
			else
			{
				$html .='<input type="checkbox" name="accept" value="1" checked onclick="document.forms.editform.reject.checked=0;document.forms.editform.close.checked=0;"> '.localeH("take over ticket") .' &nbsp;<input type="checkbox" name="reject" value="1" onclick="document.forms.editform.accept.checked=0;document.forms.editform.close.checked=0;"> '.localeH("give back ticket").' &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.reject.checked=0;document.forms.editform.accept.checked=0;"> '.locale("close ticket");
			}
		}
		else
		{
			$html .='<input type="checkbox" name="accept" value="1" onclick="document.forms.editform.close.checked=0;"> '.localeH("take over ticket") .' &nbsp;<input type="checkbox" name="close" value="1" onclick="document.forms.editform.accept.checked=0;"> '.locale("close ticket");
			
		}
	}
	$myLayout->workarea_row_draw(locale("Comment"),$html);
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><?php if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser") ){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Delete")?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this ticket?")?>');">&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save")?>">&nbsp;&nbsp;</td>
          </tr>
        </table>

	<?php
	
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
		<?php
		$html = $myLayout->workarea_form_text(locale("title"),"bez",$myTicket->row["tik_bez"]);
		$sql ="SELECT ticketsubject.sbj_id AS K, sbj_bez AS V FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $_SESSION["usr_id"] . " ORDER BY sbj_bez";
		$options = $myAdm->buildOptionsBySQL($sql,$myTicket->row["sbj_id"]);
		$html .= $myLayout->workarea_form_select(locale("Realm"),"sbj_id_ticket",$options);
		if ($myPT->getIPref("tickets.con_id_2ndorder")!=0)
		{

			$bez = $myPT->getPref("tickets.bez_2ndorder");

			$dat_id= $myTicket->row["dat_id_2ndorder"];
			$_options = $this->build2ndOrderOptionsArray($dat_id);
			$_options = Array(0=>"...")+$_options;


			$html.=$myLayout->workarea_form_select2($bez,"dat_id_2ndorder",$dat_id,$_options);
		}

		$myLayout->workarea_row_draw(locale("Meta"),$html);
		$options = Array (1=>localeH("++ Highest Priority"),2=>localeH("+ Preferential"),3=>localeH("o Standard"),4=>localeH("- Subordinate"));
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_prio"]);
		$html = $myLayout->workarea_form_select(locale("Priority"),"priority",$options);
		$myPT->startbuffer();
		?>
		<br><?php echo localeH("Deferral")?><br>
		<input type="text" name="datum3" size="10" value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);} ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe3" onchange="javascript:taketime3();" class="input">
		<option value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);}?>"><?php echo localeH("set target")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		<?php
		$html .= $myPT->stopbuffer();
		$myLayout->workarea_row_draw(locale("Priority"),$html);

		$sql = "SELECT CONCAT(usr_nachname,', ',usr_vorname) AS V, usr_id AS K FROM user WHERE usr_status = 1  ORDER BY usr_nachname,usr_vorname";
		$options = $myAdm->buildOptionsBySQL($sql,$myTicket->row["usr_id_owner"]);
		$options = '<option value="0">'.localeH('n/a').'</option>' . $options;
		$html = $myLayout->workarea_form_select("","usr_id",$options);
		$myLayout->workarea_row_draw(locale("Processor"),$html);

		$myPT->startbuffer();
		global $mySUser;
		$ticketdatum = mktime(0,0,0,date('m',$myTicket->row["tik_creationdate"]),date('d',$myTicket->row["tik_creationdate"]),date('Y',$myTicket->row["tik_creationdate"]));
		$tage = time() - $ticketdatum;
		$tage = floor($tage/(60*60*24))+1;

		echo localeH("msg_ticket_created",array(localeFullTime($myTicket->row["tik_startdate"]),$mySUser->getName($myTicket->row["usr_id_creator"]),$tage));
		
		if ($myTicket->row["tik_complexity"]!=6)
		{
		?>
		<br><br>
		<?php echo localeH("Limit")?>:<br>
		<input type="text" name="datum1" size="10" value="<?php echo date("d.m.Y",$myTicket->row["tik_enddate"]); ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe1" onchange="javascript:taketime1();" class="input">
		<option value="<?php echo date("d.m.Y",$myTicket->row["tik_enddate"]); ?>"><?php echo localeH("set target")?></option>

<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		
		</select>
		<br>
		<?php echo localeH("Target")?>:<br>
		<input type="text" name="datum2" size="10" value="<?php if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);} ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe2" onchange="javascript:taketime2();" class="input">
		<option value="<?php if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);} ?>"><?echo localeH("set target")?></option>
		<option value="-1"><?php echo localeH("Limit")?></option>
		<option value="<?php echo date("d.m.Y"); ?>"><?php echo localeH("today")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		</select>
		<?php
		}
		else
		{
		?>
		<input type="hidden" name="datum1" value="<?php echo date("d.m.Y",$myTicket->row["tik_enddate"]); ?>">
		<input type="hidden" name="datum2" size="10" value="<?php if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);} ?>" class="input">
		<?php
		}

		$html = $myPT->stopbuffer();
		$myLayout->workarea_row_draw(locale("Time scheduling"),$html);
		$options = Array (0=>locale("no estimation"),1=>locale("hour"),2=>locale("day"),3=>locale("few days"),4=>locale("week"),5=>locale("month"),6=>locale("permanent task"));
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_complexity"]);
		$html = $myLayout->workarea_form_select(locale("Complexity"),"complexity",$options,150);
		$options = Array (0=>locale("not specified"),1=>locale("positive"),2=>locale("negative"));
		$options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_tendency"]);
		$html .= $myLayout->workarea_form_select(locale("Trend"),"tendency",$options,150);

		if ($myTicket->row["tik_complexity"]!=6){
			$html.= "<br>". localeH("msg_ticket_progress",$myTicket->row["tik_percentage"]);
		}
		if ($myTicket->row["tik_duration"]!=0)
		{
			if ($myTicket->row["tik_duration"]<=90)
			{
				$html.="<br/>". localeH("msg_ticket_duration_minutes",$myTicket->row["tik_duration"]);
			
			}
			else
			{
				$stunden = floor($myTicket->row["tik_duration"]/30)/2;
				$stunden = str_replace(".",",",$stunden);
				$html.= "<br/>". localeH("msg_ticket_duration_hours",$stunden);
				
			}

		}


		$myLayout->workarea_row_draw(locale("Capability Planning"),$html);

		$html =   $myLayout->workarea_form_textarea("","Comment","",8);
		$myLayout->workarea_row_draw(locale("Comment"),$html);
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><?php if ($mySUser->checkRight("elm_admin") OR $mySUser->checkRight("superuser") )
	{?><input name="delete" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Delete")?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this ticket?")?>')">&nbsp;<?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save")?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?php
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
		$html = "&nbsp;".localeH("msg_selectusers_comment")."<br><br>".$user."<br><br><br><br><br><br><br><br>";
		$myLayout->workarea_row_draw(localeH("Users"),$html);
  		?>
   		<input type="hidden" name="comment" value="<?php echo $myRequest->getH("comment") ?>">
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Execute")?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?php
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
		$html = "&nbsp;".localeH("msg_selectusers_question")."<br/><br/>".$user."<br/><br/><br/><br/><br/><br/><br/><br/>";
		$myLayout->workarea_row_draw(localeH("Users"),$html);
		?>
    <input type="hidden" name="comment" value="<?php echo $myRequest->getH("comment") ?>">
   	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Execute")?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?php
	}

	function displayNoticesMask($myTicket,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myLayout;
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody"><br>
			<?php
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
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save")?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
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
		<title>phenotype <?php echo $myPT->version ?></title>
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
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<input type="hidden" name="page" value="Ticket,Process,removemarkup"/>
		<input type="hidden" name="popup" value="1"/>
		<script language="JavaScript">
		self.focus();
		</script>
		<table width="<?php echo $table_x ?>" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTitle"><?php echo localeH("Ticket log")?></td>
		        <td align="right" class="windowTitle"></td>
		      </tr>
		    </table></td>
		  </tr>
		</table>
		<?php
		}
		$_prio = Array (1=>localeH("++ Highest Priority"),2=>localeH("+ Preferential"),3=>localeH("o Standard"),4=>localeH("- Subordinate"));
		$_komplex = Array (0=>locale("no estimation"),1=>locale("hour"),2=>locale("day"),3=>locale("few days"),4=>locale("week"),5=>locale("month"),6=>locale("permanent task"));
		$_tendency =Array (0=>locale("not specified"),1=>locale("positive"),2=>locale("negative"));
		$_tag = Array(locale("day_short_sunday"),locale("day_short_monday"),locale("day_short_tuesday"),locale("day_short_wednesday"),locale("day_short_thursday"),locale("day_short_friday"),locale("day_short_saturday"),locale("day_short_sunday"));
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
				<table width="<?php echo $table_x ?>" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
				    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
				      <tr>
				        <td class="windowTitle"><?php echo localeH("msg_calenderweek",array(date("W",$montag),date("Y",$sonntag),localeShortDate($montag),localeShortDate($sonntag)))?></td>
				        <td align="right" class="windowTitle">&nbsp;</td>
				      </tr>
				    </table></td>
				  </tr>
				</table>
  				<?php

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



<table width="<?php echo $table_x ?>" border="0" cellpadding="0" cellspacing="0" align="center" class="window">
<tr>
    <td width="50" valign="top" class="taskTopCorner">
	<!-- Ticketstatus zu Beginn des Arbeitsschrittes -->
	<?php
	if ($zeigegrafik1==1)
	{
	?>
	<img src="img/<?php echo $grafik1 ?>" alt="" width="48" height="36" vspace="6" border="0">
	<?php
	}
	?>
	</td>
	<td width="60"  valign="top" class="taskTopCorner" rowspan="2">
	<p align="center"><img src="img/transparent.gif" width="3" height="9"><br>
	<?php
	if ($zeigedatum==1)
	{

		$tage = $datum - $ticketdatum;
		$tage = floor($tage/(60*60*24))+1;
	?>
	<strong><?php echo $_tag[$wochentag] ?> <?php echo date('d.m.',$datum) ?></strong><br>(Tag <?php echo $tage ?>)
	<?php
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
	<?php
	if ($zeigeuhrzeit==1)
	{
	?>
	<tr>
	<td width="55" valign="top" class="taskTopCorner"><p><?php echo date("H:i",$uhrzeit) ?></p></td>
	<td  class="taskTopCorner"><?php echo $mySUser->getName($row["usr_id"]) ?></td>
	</tr>
	<?php
	}
	?>
	<!-- Aktion -->
	<?php
	switch ($row["act_type"])
	{
		// Anlage
		case 1:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_newtask2.gif" alt="" width="17" height="17"  vspace="1" hspace="2" border="0"></td>
	<td valign="top" ><?php echo localeH("Ticket opened.")?></td>
	</tr>
	<tr>
	<td align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >
	<?php echo localeH("Title")?>: <?php echo $myPT->codeH($_details["bez"]) ?><br>
	<?php echo localeH("Realm")?>:  <?php echo $myPT->codeH($_details["subject"]) ?><br>
	<?php echo localeH("Target Date")?>: <?php echo date('d.m.Y',$_details["enddate"]) ?><br>
	<?php echo localeH("Priority")?>: <?php echo $_prio[$_details["prio"]] ?><br>
	<?php echo localeH("Processor")?>: <?php echo $myPT->codeH($_details["owner"]) ?><br>
	</td>
	</tr>	
	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>
	<?php
	break;
	?>
	<?php
	// Delegation
		case 2:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_myjob.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket delegated to %1",$myPT->codeH($_details["newowner"]))?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>
	<?php
	break;
	?>	
	<?php
	// Ticket akzeptiert
		case 3:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/i_working.gif" width="25" height="18"></td>
	<td valign="top" ><?php echo localeH("Ticket accepted.")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td width="55"  align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>
	<?php
	break;
	?>
	<?php
	// Ticket bearbeitet
		case 4:
	?>
<tr>
		  <td  width="55" align="center" valign="top" height="25">
		   <img src="img/transparent.gif" width="3" height="5"><br>
		 <?php if ($_details["complexity"]!=6){ ?>
               <table width="50" border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<?php
						$w = floor($_details["percentage"]*48/100);
						?>
                          <td align="left" class="taskProgress"><img src="img/task_progressline.gif" width="<?php echo $w ?>" height="3" alt=" <?php echo $_details["percentage"] ?> %" title=" <?php echo $_details["percentage"] ?> %"></td>
                        </tr>
                    </table>
				  <?php } ?>
		 
		 </td>
		  <td valign="top" >
		  <?php
		 
		  if ($_details["minutes"]>0 AND $_details["percentage"]!=$_details["oldpercentage"])
		  {
			echo localeH("Ticket processed for %1 minutes. %2% completed.",array($_details["minutes"],$_details["percentage"]));
		  }
		  elseif ($_details["minutes"]>0 OR $_details["percentage"]!=$_details["oldpercentage"])
		  {
		  	if ($_details["minutes"]>0)
		  	{
		  		echo localeH("Ticket processed for %1 minutes.",$_details["minutes"]);
		  	}
		  	else 
		  	{
		  		echo localeH("Ticket processed. %1% completed.",$_details["percentage"]);
		  	}
		  }
		  else 
		  {
		  	echo localeH("Ticket processed.");
		  }
		  ?>
		  </td>
		  </tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>
	<?php
	break;
	?>
	<?php
	// Ticket geschlossen
		case 5:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><?php if ($_details["percentage"]!=100){ ?><img src="img/t_closed0.gif" width="17" height="17" hspace="1"><?php }else{ ?><img src="img/t_closed100.gif" width="17" height="17" hspace="1"><?php } ?><img src="img/t_closed.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket closed.")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>	
	<?php
	// Ticket angesehen
		case 6:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_view.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket viewed.")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>	
	<?php
	// Ticket kommentiert
		case 7:
	?>
	<?php
	if ($row["act_comment"]!="")
	{
		if ($row["usr_id"]==$_details["usr_id_owner"])
		{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_edit.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
		}else{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
		}
	}
	break;
	?>		
	<?php
	// Ticket zur&uuml;ckgewiesen
		case 8:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_myjob.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket rejected.")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;

		case 9:
	?>
	<tr>
	<td width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" >
	<?php
	if ($_details["newsubject"]!=""){
	?>
	<?php echo localeH("Title")?>: <?php echo $myPT->codeH($_details["newsubject"]) ?><br>
	<?php
	}
	?>
	<?php
	if ($_details["newenddate"]!=""){
	?>
	<?php echo localeH("Limit")?>: <?php echo localeDate($_details["newenddate"]) ?><br>
	<?php
	}
	?>
	<?php
	if ($_details["newestimationdate"]!=""){
	?>
	<?php echo localeH("Target Date")?>: <?php
	if ($_details["newestimationdate"]==0){echo localeH("not specified");}else{echo localeDate($_details["newestimationdate"]);}
	?><br>
	<?php
	}
	?>	
	<?php
	if ($_details["newsleepdate"]!=""){
	?>
	<?php echo localeH("Deferral")?>: <?php echo localeDate($_details["newsleepdate"]) ?><br>
	<?php
	}
	?>	
	<?php
	if ($_details["newpriority"]!=""){
	?>
	<?php echo localeH("Priority")?>: <?php echo $_prio[$_details["newpriority"]] ?><br>
	<?php
	}
	?>
	<?php
	if ($_details["newcomplexity"]!=""){
	?>
	<?php echo localeH("Complexity")?>: <?php echo $_komplex[$_details["newcomplexity"]] ?><br>
	<?php
	}
	?>
	<?php
	if ($_details["newtendency"]!=""){
	?>
	<?php echo localeH("Trend")?>: <?php echo $_tendency[$_details["newtendency"]] ?><br>
	<?php
	}
	?>	
			

	</td>
	</tr>	
	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>
	<?php
	break;
	?>
	<?php
	// Notizen ge&auml;andert
		case 10:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_noticecomment.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Notices edited.")?></td>
	</tr>
	<?php
	break;
	?>	
	<?php
	// Bereich gewechselt
		case 11:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Realm")?>: <?php echo $myPT->codeH($_details["newsubject"]) ?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>
	<?php
	// Ticket widerbelebt
		case 12:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_newtask2.gif" width="17" height="17" vspace="1" hspace="2"></td>
	<td valign="top" ><?php echo localeH("Ticked reopened")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>	
<?php
// Hinweis
		case 13:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_notice.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Hint for %1",$_details["aim"]) ?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25" >&nbsp;</td>
    <td valign="top" class="taskTopCorner"><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;

	// Frage
		case 14:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_request.gif" width="22" height="22"></td>
	<td valign="top"><?php echo localeH("Question for %1",$_details["aim"]) ?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25" >&nbsp;</td>
    <td valign="top" class="taskTopCorner"><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	?>			
	<?php
	break;
	?>	
	<?php
	// Ticket zurueckgestellt
		case 15: //
		//print_r ($_details);
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket deferred until %1.",$_tag[date('w',$_details["sleepdate"])] ." ".localeDate($_details["sleepdate"]))?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>	
	<?php
	// Ticket-Rueckstellung aufgehoben
		case 16:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Ticket deferral cleared.")?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>	
	<?php
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
					$myDoc = new PhenotypeImage($row_att["med_id"]);
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
				$attachment =localeH("Document cannot be found in mediabase.");
			}

	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_attach.gif" width="17" height="17" vspace="1" hspace="2"></td>
	<td valign="top" ><?php echo locale("Document %1 attached.",'<a href="'.$myDoc->url.'" target="_blank">'. $myDoc->bez .'</a>');?></br>
	<br><a href="<?php echo $myDoc->url ?>" target="_blank"><?php echo $attachment ?></a>
	</td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;
	?>		
		<?php
		// Bereich gewechselt
		case 18:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_konfig.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo $myPT->getPref("tickets.bez_2ndorder") ?>: <?php echo $myPT->codeH($_details["new2ndorder"]) ?></td>
	</tr>

	<?php
	if ($row["act_comment"]!="")
	{
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_comment.gif" width="22" height="22"></td>
    <td valign="top" ><?php echo nl2br(htmlentities($row["act_comment"],null,PT_CHARSET)) ?></td>
    </tr>	
	<?php
	}
	break;

		case 19:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_notice.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Notice marker deleted.")?></td>
	</tr>
	
	<?php
	break;
		case 20:
	?>
	<tr>
	<td  width="55" align="center" valign="top" height="25"><img src="img/b_request.gif" width="22" height="22"></td>
	<td valign="top" ><?php echo localeH("Question marker deleted.")?></td>
	</tr>
	<?php
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
<?php
if ($zeigegrafik2==1)
{
?>
<img src="img/<?php echo $grafik2 ?>" alt="" width="48" height="36" vspace="6" border="0">
<?php
}
?>
</td>
</tr>
</table>
<?php
		}

		echo "<br/><br/>";
		$html_button1="";
		$html_button2="";
		if ($popup==0)
		{
			$html_button1 ='<input name="overview" type="submit" class="buttonWhite" style="width:102px"value="'.localeH("Back to overview").'">';

		}
		if ($myPT->getIPref("tickets.active_markup_removal")==1 OR  $myPT->getIPref("tickets.active_request_removal")==1)
		{
			if ($myTicket->hasMarkup($mySUser->id) OR $myTicket->hasRequest($mySUser->id))
			{

				$html_button2 ='<input name="save" type="submit" class="buttonWhite" style="width:102px"value="'.localeH("Remove Marker").'">';
			}
		}
		?>
	 	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite" align="left">&nbsp;&nbsp<?php echo $html_button1 ?>&nbsp;</td>
            <td align="right" class="windowFooterWhite"><?php echo $html_button2 ?>&nbsp;&nbsp;</td>
          </tr>
        </table>
        <?php

        if ($popup==1)
        {
        	?>
        	<form></body></html>
        	<?php
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
		<title>Phenotype <?php echo $myPT->version ?></title>
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
				alert ('<?php echo localeH("Please choose a title for this ticket")?>');
				return false;
			}

			if (document.forms.form1.datum.value=="")
			{
				alert ('<?php echo localeH("Please chosse a limit for this ticket")?>');
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
		        <td class="windowTitle"><?php echo localeH("Create new ticket")?></td>
		        <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		<br/>
		<?php
		$url = 'backend.php?page=Ticket,Process,insert&pag_id=' .$pag_id. '&ver_id=' .$ver_id. '&dat_id=' . $dat_id . '&med_id=' . $med_id .'&sbj_id=' .$sbj_id. "&dat_id_2ndorder=".$dat_id_2ndorder."&express=0";

		$this->tab_addEntry(locale("Standard Ticket"),$url,"b_job.gif");
		
		$url = 'backend.php?page=Ticket,Process,insert&pag_id=' .$pag_id. '&ver_id=' .$ver_id. '&dat_id=' . $dat_id . '&med_id=' . $med_id .'&sbj_id=' .$sbj_id. "&dat_id_2ndorder=".$dat_id_2ndorder."&express=1";
		$this->tab_addEntry(locale("Express Ticket"),$url,"b_myjob.gif");
		if ($express==1)
		{
			$this->tab_draw(locale("Express Ticket"),$x=450,0);
		}
		else
		{
			$this->tab_draw(locale("Standard Ticket"),$x=450,0);
		}
		?>
		<form action="backend.php" method="post" enctype="multipart/form-data" name="form1"  onsubmit="return checkForm();">
		<input type="hidden" name="page" value="Ticket,Process,insert"/>
		<input type="hidden" name="step" value="2"/>
		<input type="hidden" name="express" value="<?php echo $express ?>"/>

			<table width="100%" border=0" cellpadding="0" cellspacing="0" align="center" class="window">
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><?php echo localeH("%bbTitle%bs for task:")?> <br>
		              <input name="bez" type="text" class="input" style="width: 300px" value="<?php echo localeH("New Task")?>">
		          </td>
		          </tr>
		        <tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><strong><?php echo localeH("Classification")?></strong><br>
				  
		<table cellspacing="0" cellpadding="0">
		<tr>
		<td width="100"><?php echo localeH("Realm")?>:</td>
		<td>
		<select name="sbj_id" class="listmenu" style="width: 200px" >
		<?php

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
			<option value="<?php echo $row["sbj_id"] ?>" <?php echo $selected ?>><?php echo $myPT->codeH($row["sbj_bez"]) ?></option>
			<?php
		}

		?>
		</select>
		</td>
		</tr>
		<?php
		if ($myPT->getIPref("tickets.con_id_2ndorder")!=0 AND $express==0)
		{
		?>
		<tr>
		<td width="100"><br><?php echo $myPT->codeH($myPT->getPref("tickets.bez_2ndorder")) ?>:</td>
		<td><br>
		<select name="dat_id_2ndorder" class="listmenu" style="width: 200px" >
		<option value="0">...</option>
		<?php

		$sql ="SELECT dat_id,dat_bez FROM content_data WHERE con_id=" . $myPT->getIPref("tickets.con_id_2ndorder") . " AND dat_status=1 ORDER BY dat_bez";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$selected ="";
			if ($dat_id_2ndorder==$row["dat_id"]){$selected="selected";}
			?>
			<option value="<?php echo $row["dat_id"] ?>" <?php echo $selected ?>><?php echo $myPT->codeH($row["dat_bez"]) ?></option>
			<?php
		}
		?>
		</select>
		</td>
		</tr>
		<?php
		}

		if ($pag_id!=0)
		{
			$bez = $myAdm->getPageName($pag_id,$ver_id);
			?>
			<input type="hidden" name="pag_id" value="<?php echo $pag_id ?>">
			<input type="hidden" name="ver_id" value="<?php echo $ver_id ?>">
			<tr>
			<td width="100">
			<br><?php echo localeH("Page")?>:
			</td>
			<td ><br><p class="input"><?php echo $myPT->codeH($bez) ?></p></td>
			</tr>
			<?php
		}

		if ($dat_id!=0)
		{
			$bez = $myAdm->getContentName($dat_id);
			?>
			<input type="hidden" name="dat_id" value="<?php echo $dat_id ?>">
			<tr>
			<td>
			<br><?php echo localeH("Content Record")?>:
			</td>
			<td ><br><p class="input"><?php echo $myPT->codeH($bez) ?></p></td>
			</tr>
		<?php
		}
		if ($med_id!=0)
		{
			$myMB = new PhenotypeMediabase;
			$bez = $myMB->getMediaObjectName($med_id);
			?>
			<input type="hidden" name="med_id" value="<?php echo $med_id ?>">
			<tr>
			<td>
			<br><?php echo localeH("Media object")?>:
			</td>
			<td ><br><p class="input"><?php echo $myPT->codeH($bez) ?></p></td>
			</tr>
			<?php
		}
		?>
		<tr>
		<td valign="top"><br><?php echo localeH("Priority")?>:</td>
		<td  colspan="3" ><br>
		<?php
		if ($express==1)
		{
		?>
		<select name="priority" style="width: 200px" class="listmenu" ><option value="3" selected><?php echo localeH("o Standard")?></option></select>
		<?php
		}
		else
		{
		?>
		<select name="priority" style="width: 200px" class="listmenu" ><option value="1" ><?php echo localeH("++ Highest Priority")?></option><option value="2" ><?php echo localeH("+ Preferential")?></option><option value="3" selected><?php echo localeH("o Standard")?></option><option value="4" ><?php echo localeH("- Subordinate")?></option></select>
		<?php
		}
		?>
		<br><br>
		</td>
		</tr>
		<tr>
		<td><?php echo localeH("Limit")?>:</td>
		<td  colspan="3">
		<?php
		if ($express==1)
		{
		?>
		<select name="timeframe" class="listmenu">
		<option value="<?php echo date("d.m.Y"); ?>"><?php echo localeH("today")?></option>
		</select>&nbsp;&nbsp;<input type="hidden" name="datum" size="10" value="<?php echo date("d.m.Y"); ?>" class="input" /><?php echo date("d.m.Y"); ?>
		<?php
		}
		else
		{
		?>
		<select name="timeframe" onchange="javascript:taketime()" class="listmenu">
		<option value=""></option>
		<option value="<?php echo date("d.m.Y"); ?>">heute</option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
		<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
		<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		</select>&nbsp;&nbsp;<input type="text" name="datum" size="10" value="" class="input">
		<?php
		}
		?>
		<br><br>
		</td>
		</tr>
		<tr>
		<td><?php echo localeH("for")?>:</td>
		<td  colspan="3">
		<select name="usr_id" class="listmenu" style="width: 200px" >
		<?php
		$sql = "SELECT * FROM user WHERE usr_status = 1 ORDER by usr_vorname, usr_nachname";
		
		if ($express==1)
		{
			$sql = "SELECT * FROM user WHERE usr_id=".$mySUser->id;
			
		}
		else 
		{
			echo '<option value="0">'.localeH("n/a").'</option>';	
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
		  <?php
		  if ($express==1)
		  {
		  	?>
	
		  <tr>
		      <td colspan="5" valign="top" class="tableBody">
		      	<table cellspacing="0" cellpadding="0">
		        	<tr>
						<td width="100"><strong><?php echo localeH("Duration")?>:</strong></td>
						<td>
						<select name="duration" class="input">
						<?php $options='<option value="0"></option><option value="5">'.localeH("5 minutes").'</option><option value="10">'.localeH("10 minutes").'</option><option value="15">'.localeH("15 minutes").'</option><option value="20">'.localeH("20 minutes").'</option><option value="30">'.localeH("30 minutes").'</option><option value="45">'.localeH("45 minutes").'</option><option value="60">'.localeH("1 hour").'</option><option value="90">'.localeH("1,5 hours").'</option><option value="120">'.localeH("2 hours").'</option><option value="180">'.localeH("3 hours").'</option><option value="240">'.localeH("4 hours").'</option><option value="300">'.localeH("5 hours").'</option><option value="360">'.localeH("6 hours").'</option><option value="420">'.localeH("7 hours").'</option><option value="480">'.localeH("8 hours").'</option><option value="540">'.localeH("9 hours").'</option><option value="600">'.localeH("10 hours").'</option>';
						echo $options;	
						?>
						</td>
					</tr>
				</table>
		      </td>
		  </tr>
		  <tr>
		  	<td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		  </tr>
		  <?php
		  }
		  ?>        
		        <tr>
		          <td colspan="5" valign="top" class="tableBody"><table cellspacing="0" cellpadding="0">
		
		          
		         <tr>
		<td width="100"><strong><?php echo localeH("Attachment")?></strong></td>
		<td><input name="userfile" type="file" class="input"></td></tr></table>
		          </td>
		          </tr>
		        
		        <tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>				
		        <tr>
		          <td colspan="5" valign="top" class="tableBody">1. <strong><?php echo localeH("Comment")?></strong><br>
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
		        <td align="right" class="windowTitle"><input name="Submit" type="submit" class="buttonWhite" value="<?php echo localeH("Save")?>" style="width:102px"></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		</form>
		</body>
		</html>
		<?php	
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
		<title>Phenotype <?php echo $myPT->version?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript">
		top.opener.location = "<?php echo $url ?>";
		self.close();
		</script>
		</head>
		<body>
		</body>
		</html>
		<?php
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
		<title>phenotype <?php echo $myPT->version?></title>
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
		<input type="hidden" name="tik_id" value="<?php echo $tik_id ?>">
		<input type="hidden" name="sbj_id" value="<?php echo $sbj_id ?>">
		<input type="hidden" name="dat_id_2ndorder" value="<?php echo $dat_id_2ndorder ?>">
		<input type="hidden" name="page" value="Ticket,Process,insert"/>
		<input type="hidden" name="step" value="3"/>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTitle"><?php echo localeH("Manage new task")?></td>
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
		          <td valign="top" width="110"><br><strong><?php echo locale("Time scheduling")?></strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br>
				  		
				<?php echo localeH("Limit")?>:<br>
		<input type="text" name="datum1" size="10" value="<?php echo date("d.m.Y",$myTicket->row["tik_enddate"]); ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe1" onchange="javascript:taketime1();" class="input">
		

		

		
		<option value="<?php echo date("d.m.Y",$myTicket->row["tik_enddate"]); ?>"><?echo localeH("set target")?></option>
		<option value="<?php echo date("d.m.Y"); ?>"><?php echo localeH("today")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		</select>
		<br>
		<?php echo localeH("Target")?>:<br>
		<input type="text" name="datum2" size="10" value="<?php if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);} ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe2" onchange="javascript:taketime2();" class="input">
		<option value="<?php if ($myTicket->row["tik_targetdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_targetdate"]);} ?>"><?echo localeH("set target")?></option>
		<option value="-1"><?php echo localeH("Limit")?></option>
		<option value="<?php echo date("d.m.Y"); ?>"><?php echo localeH("today")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		</select>
			<br><?php echo localeH("Deferral")?>:<br>
			<input type="text" name="datum3" size="10" value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);} ?>" class="input">&nbsp;&nbsp;
		<select name="timeframe3" onchange="javascript:taketime3();" class="input">
		<option value="<?php if ($myTicket->row["tik_sleepdate"]!=0){echo date("d.m.Y",$myTicket->row["tik_sleepdate"]);} ?>"><?echo localeH("set target")?></option>
		<option value="<?php echo date("d.m.Y"); ?>"><?php echo localeH("today")?></option>
		<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+1, date('y'))); ?>"><?php echo localeH("tomorrow")?></option>
<option value="<?php echo $myPT->nextFriday(time(),1) ?>"><?php echo localeH("end of week")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+7, date('y'))); ?>"><?php echo localeH("next week")?></option>
<option value="<?php echo $myPT->nextMonday(time(),1) ?>"><?php echo localeH("next monday")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+14, date('y'))); ?>"><?php echo localeH("in two weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d'))+28, date('y'))); ?>"><?php echo localeH("in four weeks")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+2, (date('d')), date('y'))); ?>"><?php echo localeH("in two month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+3, (date('d')), date('y'))); ?>"><?php echo localeH("in three month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m')+6, (date('d')), date('y'))); ?>"><?php echo localeH("in 6 month")?></option>
<option value="<?php echo date("d.m.Y",mktime ( 0, 0, 0, date('m'), (date('d')), date('y')+1)); ?>"><?php echo localeH("in one year")?></option>
		</select><br><br>
				  </td>
				</tr>		
				<tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>	
		        <tr>
				  <td width="10">&nbsp;</td>
		          <td valign="top" width="110"><br><strong><?php localeH("Capability Planning")?></strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br><?php
				  $options = Array (0=>locale("no estimation"),1=>locale("hour"),2=>locale("day"),3=>locale("few days"),4=>locale("week"),5=>locale("month"),6=>locale("permanent task"));
				  $options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_complexity"]);
				  $html = $myLayout->workarea_form_select(locale("Complexity"),"complexity",$options,150);
				  $options = Array (0=>locale("not specified"),1=>locale("positive"),2=>locale("negative"));
				  $options = $myAdm->buildOptionsByNamedArray($options,$myTicket->row["tik_tendency"]);
				  $html .= $myLayout->workarea_form_select(locale("Trend"),"tendency",$options,150);
			echo $html;?><br>
				  </td>
				</tr>		
				<tr>
		          <td colspan="5" valign="top" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
		        <tr>		
		        <tr>
				  <td width="10">&nbsp;</td>
		          <td valign="top" width="110"><br><strong><?echo localeH("Comment")?></strong></td>
		          <td width="10">&nbsp;</td>
				  <td><br><textarea name="comment" rows="6" style="width: 250px" class="input" wrap="physical"></textarea>
				  
				  		<br><input type="checkbox" value="1" name="accept" checked> <?php echo localeH("take over ticket")?><br/><br/>
				  </td>
				</tr>		
					
		         
		</table>		
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowFooterWhite"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td align="right" class="windowTitle"><input name="close" type="submit" class="buttonWhite" value="<?php echo localeH("Close")?>" style="width:102px">&nbsp;<input name="save" type="submit" class="buttonWhite" value="<?php echo localeH("Save")?>" style="width:102px"></td>
		      </tr>
		    </table></td>
		    </tr>
		</table>
		</form>
		</body>
		</html>
		<?php
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
			case 5: // Hinweis
			$block_nr_neu = $this->updateMarkupMask($myTicket);
			break;
			case 6: // Frage
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