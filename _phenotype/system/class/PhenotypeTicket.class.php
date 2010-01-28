<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr�mer.
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
 * @subpackage system
 *
 */
class PhenotypeTicketStandard
{
	var $row;
	var $id;
	var $bez;

	var $props = Array();
	var $actionprops = Array();

	function __construct($tik_id = -1)
	{
		if ($tik_id!=-1)
		{
			$this->loadById($tik_id);
		}
	}

	function set($bez,$val)
	{
		$this->props[$bez] = $val;
	}

	function get ($bez)
	{
		return @($this->props[$bez]);
		//return @stripslashes($this->props[$bez]);
	}

	function actionset($bez,$val)
	{
		$this->actionprops[$bez] = $val;
	}

	function actionget ($bez)
	{
		return @($this->actionprops[$bez]);
		//return @stripslashes($this->props[$bez]);
	}

	function init($row)
	{
		$this->row = $row;
		$this->id = $row["tik_id"];
		$this->bez = $row["tik_bez"];
	}

	function loadById($id)
	{
		global $myDB;

		$id = (int)$id;

		$sql = "SELECT * FROM ticket WHERE tik_id = " . $id;
		$rs = $myDB->query($sql);

		if (mysql_num_rows($rs)==0)
		{
			return false;
		}

		$this->row = mysql_fetch_array($rs);
		$this->id = $this->row["tik_id"];
		$this->bez = $this->row["tik_bez"];

		$sql = "SELECT sbj_bez FROM ticketsubject WHERE sbj_id = " . $this->row["sbj_id"];
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$this->set("subject",$row["sbj_bez"]);

		$_array = Array("bez","prio","percentage","complexity","tendency","eisenhower","duration","enddate");
		foreach ($_array as $key)
		{
			$this->set($key,$this->row["tik_".$key]);
		}
		$this->set("usr_id_owner",$this->row["usr_id_owner"]);

		// Aktuelle Ticketfarbe bestimmen (Achtung! passiert auch in den Funktionen listTicket der PhenotypeAdminclass
		$color = "blue";
		if ($this->row["tik_lastaction"] < time()-(3600*24*7*2)) //  keine Aktivitaet in den letzten 2 Wochen
		{
			$color="grey";
		}
		$tage = time() - $this->row["tik_creationdate"];
		$tage = ceil($tage/(60*60*24));
		if ($tage<=1){$color="orange";}
		if ($this->row["tik_complexity"]!=6)
		{ // Daueraufgaben k�nnen keinen Status Rot erhalten
			if ($this->row["tik_enddate"]<time()){$color="red";}
		}

		$grafik = "t_". mb_strtolower($this->row["tik_eisenhower"])."_".$color.".gif";

		if ($this->row["tik_status"]==0)
		{
			$color="black";
			$grafik = "transparent.gif";
		}
		if ($this->row["tik_sleepdate"]>time())
		{
			$grafik = "transparent.gif";
		}
		if ($this->row["tik_eisenhower"]>"D")
		{
			$grafik = "transparent.gif";
		}
		// -- Aktuelle Ticketfarbe bestimmen

		$this->set("color",$color);
		$this->set("grafik",$grafik);
		//echo "LOAD:";
		//var_dump($this);

	}

	function create ($titel,$comment,$sbj_id,$usr_id_creator,$usr_id_owner,$enddate,$priority,$dat_id_2ndorder)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		// zunaechst die uebergebenen Felder

		$mySQL->addField("tik_bez",$titel);
		$mySQL->addField("sbj_id",$sbj_id,DB_NUMBER);
		$mySQL->addField("dat_id_2ndorder",$dat_id_2ndorder,DB_NUMBER);
		$mySQL->addField("usr_id_creator",$usr_id_creator,DB_NUMBER);
		$mySQL->addField("usr_id_owner",$usr_id_owner,DB_NUMBER);
		$mySQL->addField("tik_enddate",$enddate,DB_NUMBER);
		$mySQL->addField("tik_prio",$priority,DB_NUMBER);

		// Default-Werte
		$mySQL->addField("tik_accepted",0,DB_NUMBER); // Erst mal net ...
		$mySQL->addField("tik_status",1,DB_NUMBER);
		$mySQL->addField("tik_startdate",time(),DB_NUMBER);
		$mySQL->addField("tik_creationdate",time(),DB_NUMBER);
		$mySQL->addField("tik_percentage",0,DB_NUMBER);
		$mySQL->addField("tik_lastaction",time(),DB_NUMBER);
		$sql = $mySQL->insert("ticket");
		//echo $sql;

		$myDB->query($sql);

		// Prio kalkulieren
		$id = mysql_insert_id();
		//echo $id;

		$this->loadById($id); // Laden und kalkulieren
		$this->calculate_prio();
		$this->set("startgrafik",$this->get("grafik"));

		// Einstellung loggen
		$act_id =  $this->logAction(1,$comment);

		// Markup setzen
		if ($usr_id_owner!=intval($_SESSION["usr_id"])){$this->markup_usr($usr_id_owner);}


		global $myApp;
		$myApp->onTicket_createTicket($id,$usr_id_creator,$usr_id_owner);

		// Es wird die Aktivitaets-ID und nicht die Ticket-ID uebergeben,
		// da diese aus dem Objekt extrahiert werden kann.
		return ($act_id);
	}

	function gluePage($pag_id,$ver_id)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("pag_id",$pag_id,DB_NUMBER);
		$mySQL->addField("ver_id",$ver_id,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);
	}

	function glueContent($dat_id)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("dat_id_content",$dat_id,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);
	}

	function glueMedia($med_id)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("med_id",$med_id,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);
	}

	function hide($date,$comment)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_sleepdate",$date,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);
		$this->calculate_prio();
		$act_id =  $this->logAction(15,$comment,$date);
	}

	function show($comment)
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_sleepdate","0",DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);
		$this->calculate_prio();
		$act_id =  $this->logAction(16,$comment);
		$this->loadById($this->id);
	}

	function adjust($comment,$bez,$priority,$complexity,$tendency,$date,$date2,$date3,$sbj_id,$dat_id_2ndorder)
	{

		global $myApp;
		global $myDB;

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_bez",$bez);
		$mySQL->addField("tik_enddate",$date,DB_NUMBER);
		$mySQL->addField("tik_targetdate",$date2,DB_NUMBER);
		$mySQL->addField("tik_prio",$priority,DB_NUMBER);
		$mySQL->addField("tik_complexity",$complexity,DB_NUMBER);
		$mySQL->addField("tik_tendency",$tendency,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$change=0;
		$actionprops = Array();
		if ($this->row["tik_bez"]!=$bez)
		{
			$change=1;
			$actionprops["newsubject"] = $bez;
		}
		if ($this->row["tik_enddate"]!=$date)
		{
			$actionprops["newenddate"] = $date;
			$change=1;
		}
		if ($this->row["tik_targetdate"]!=$date2)
		{
			$actionprops["newestimationdate"] = $date2;
			$change=1;
		}

		if ($this->row["tik_prio"]!=$priority)
		{
			$actionprops["newpriority"] = $priority;
			$myApp->onTicket_prioritizeTicket($this->id,$priority,$this->row["tik_prio"]);
			$change=1;
		}
		if ($this->row["tik_complexity"]!=$complexity)
		{
			$actionprops["newcomplexity"] = $complexity;
			$change=1;
		}
		if ($this->row["tik_tendency"]!=$tendency)
		{
			$actionprops["newtendency"] = $tendency;
			$change=1;
		}

		if ($change==1)
		{
			$this->loadById($this->id);
			$this->calculate_prio();

			$act_id =  $this->logAction(9,$comment,$actionprops);
			$comment="";
			$this->loadById($this->id);

		}

		if ($this->row["sbj_id"]!=$sbj_id)
		{
			$this->changeSubject($sbj_id,$comment);
			$change=1;
			$comment = "";
		}

		if ($this->row["dat_id_2ndorder"]!=$dat_id_2ndorder)
		{
			$this->change2ndOrder($dat_id_2ndorder,$comment);
			$change=1;
			$comment = "";
		}

		// Nachgelagert die R�ckstellung
		if ($this->row["tik_sleepdate"]!=$date3)
		{
			if ($date3<time())
			{
				$this->show($comment);
				$change=1;

			}
			else
			{
				$this->hide($date3,$comment);
				$change=1;
			}
			$comment="";
		}

		//if($change_showhide==1){$change=1;}// Sicherstellen, dass kein Kommentar doppelt geloggt wird

		if ($change==1)
		{
			// Ticketerzeuger informieren
			if ($this->row["usr_id_creator"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_creator"]);}
			// Ticketbesitzer informieren
			if ($this->row["usr_id_owner"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_owner"]);}
		}
		return ($change);
	}

	function comment ($comment)
	{
		global $myDB;
		$act_id =  $this->logAction(7,$comment);

		$this->checkAutomaticRequestRemoval();

		// Ticketerzeuger informieren
		if ($this->row["usr_id_creator"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_creator"]);}
		// Ticketbesitzer informieren
		if ($this->row["usr_id_owner"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_owner"]);}
	}


	function saveDescription ($description)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$mySQL->addField("tik_notice",$description);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$act_id =  $this->logAction(10);
	}

	function markup ($comment,$users)
	{


		$this->checkAutomaticRequestRemoval();
		$text  = "";
		for ($i=0;$i<count($users);$i++)
		{
			$myUser = new PhenotypeUser();
			$text .= $myUser->getName($users[$i]);
			if ($i==count($users)-1)
			{
				$text .=".";
			}
			else
			{
				$text .=", ";
			}
			$this->markup_usr($users[$i]);
		}
		$act_id =  $this->logAction(13,$comment,$text);


	}

	function request ($comment,$users)
	{
		$this->checkAutomaticRequestRemoval();
		$text  = "";
		for ($i=0;$i<count($users);$i++)
		{
			$myUser = new PhenotypeUser();
			$text .= $myUser->getName($users[$i]);
			if ($i==count($users)-1)
			{
				$text .=".";
			}
			else
			{
				$text .=", ";
			}
			$this->request_usr($users[$i]);
		}
		$act_id =  $this->logAction(14,$comment,$text);
	}


	function changeSubject ($sbj_id,$comment)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$sbj_id_last = $this->row["sbj_id"];

		$mySQL->addField("sbj_id",$sbj_id,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$sql ="SELECT * FROM ticketsubject WHERE sbj_id = " . $sbj_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);

		$act_id =  $this->logAction(11,$comment,$row["sbj_bez"]);
		$this->loadById($this->id);

		global $myApp;
		$myApp->onTicket_moveTicket($this->id,$sbj_id,$sbj_id_last);
	}

	function change2ndOrder ($dat_id,$comment)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$mySQL->addField("dat_id_2ndorder",$dat_id,DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$sql ="SELECT dat_bez FROM content_data WHERE dat_id = " . $dat_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);

		$act_id =  $this->logAction(18,$comment,$row["dat_bez"]);
		$this->loadById($this->id);
	}

	function delegate ($comment,$usr_id)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$usr_id_lastowner = $this->row["usr_id_owner"];

		$mySQL->addField("tik_accepted",0,DB_NUMBER);
		$mySQL->addField("usr_id_owner",$usr_id);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$act_id =  $this->logAction(2,$comment,$usr_id);
		if ($usr_id!=intval($_SESSION["usr_id"])){$this->markup_usr($usr_id);}
		$this->loadById($this->id);

		global $myApp;
		$myApp->onTicket_delegateTicket($this->id,$usr_id_lastowner,$usr_id);
	}

	function accept($comment,$usr_id)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$mySQL->addField("tik_accepted",1,DB_NUMBER);
		$mySQL->addField("usr_id_owner",$usr_id);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$act_id =  $this->logAction(3,$comment);
		$this->loadById($this->id);

		global $myApp;
		$myApp->onTicket_acceptTicket($this->id,$usr_id);
	}

	function reject($comment)
	{
		global $myDB;

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_accepted",0,DB_NUMBER);
		$mySQL->addField("usr_id_owner",0);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);


		$act_id =  $this->logAction(8,$comment,0);
		if ($this->row["usr_id_creator"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_creator"]);}
		$this->loadById($this->id);
	}

	function workon($comment,$usr_id,$minuten,$progress)
	{
		global $myDB;

		$progress_last = $this->row["tik_percentage"];

		$this->calculate_prio();
		$act_id =  $this->logAction(4,$comment,$minuten,$progress);

		$this->checkAutomaticRequestRemoval();

		// Ticketerzeuger informieren
		if ($this->row["usr_id_creator"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_creator"]);}
		// Ticketbesitzer informieren
		if ($this->row["usr_id_owner"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_owner"]);}

		global $myApp;
		$myApp->onTicket_progressTicket($this->id,$usr_id,$minuten,$progress,$progress_last);
		
		return $act_id;
	}

	function close($comment,$usr_id,$progress)
	{
		global $myDB;

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_status",0,DB_NUMBER);
		$mySQL->addField("tik_closingdate",time(),DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		$this->loadById($this->id);
		$this->calculate_prio();
		$act_id =  $this->logAction(5,$comment,0,$progress);
		// Ticketerzeuger informieren
		if ($this->row["usr_id_creator"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_creator"]);}
		// Ticketbesitzer informieren
		if ($this->row["usr_id_owner"]!=intval($_SESSION["usr_id"])){$this->markup_usr($this->row["usr_id_owner"]);}

		global $myApp;
		$myApp->onTicket_closeTicket($this->id,$progress);
	}







	function reopen ($comment)
	{
		global $myDB;
		$mySQL = new SQLBuilder();

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_status",1,DB_NUMBER);
		//$mySQL->addField("tik_closingdate",time(),DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);
		$this->loadById($this->id);

		$this->calculate_prio();
		$act_id =  $this->logAction(12,$comment);




	}


	function removeMarkups ()
	{
		global $myDB;
		global $mySUser;

		$usr_id = $mySUser->id;

		$mySQL = new SQLBuilder();

		if ($this->hasMarkup($usr_id))
		{
			$sql = "DELETE FROM ticketmarkup WHERE tik_id=".$this->id ." AND usr_id=".$usr_id;
			$myDB->query($sql);
			$act_id =  $this->logAction(19,"");
		}

		if ($this->hasRequest($usr_id))
		{
			$sql = "DELETE FROM ticketrequest WHERE tik_id=".$this->id ." AND usr_id=".$usr_id;
			$myDB->query($sql);
			$act_id =  $this->logAction(20,"");
		}




	}


	function calculate_prio()
	{
		global $myDB;

		$altegrafik = $this->get("grafik");
		$this->set("startgrafik",$altegrafik);
		$x = $this->row["tik_complexity"];
		$p = $this->row["tik_prio"];

		// Tickets, die keine Sch�tzung haben wie WenigeTage-Tickets handhaben
		if ($x==0){$x=3;}

		$resttage1 = ($this->row["tik_enddate"] - time())/(24*3600);

		if ($this->row["tik_targetdate"]!=0)
		{
			// Ziel und Maximum werden gleichermassen ber�cksichtigt
			$resttage2 = ($this->row["tik_targetdate"] - time())/(24*3600);
			if ($resttage2 < $resttage1) // geplant frueher fertig zu sein
			{
				$resttage = floor(($resttage1*0.25)+($resttage2*0.75));
			}
			else // Man ist bereits ueber dem Ziel
			{
				$resttage = floor(($resttage1*0.75)+($resttage2*0.25));
			}
		}
		else
		{
			$resttage = floor($resttage1);
		}
		if ($x!=6)
		{
			if ($p==1)
			{
				$_grenzen = Array("",3,4,14,20,40);
				if ($resttage<=$_grenzen[$x])
				{
					$eisenhower ="A";
				}
				else
				{
					$eisenhower = "B";
				}
			}
			if ($p==2)
			{
				$_grenzen = Array("",2,3,12,16,35);
				if ($resttage<=$_grenzen[$x])
				{
					$eisenhower ="A";
				}
				else
				{
					$eisenhower = "B";
				}
			}
			if ($p==3)
			{
				$_grenzen = Array("",1,2,8,12,30);
				if ($resttage<=$_grenzen[$x])
				{
					$eisenhower ="C";
				}
				else
				{
					$eisenhower = "D";
				}
			}
			if ($p==4)
			{
				$_grenzen = Array("",1,2,5,7,25);
				if ($resttage<=$_grenzen[$x])
				{
					$eisenhower ="C";
				}
				else
				{
					$eisenhower = "D";
				}
			}

		}
		else // Daueraufgaben
		{
			if ($p<=2)
			{
				$eisenhower ="B";
			}
			else
			{
				$eisenhower ="D";
			}
		}

		// Ziemlich egal, wenn ein Ticket zur&uuml;ckgestellt ist
		$sleepdate = $this->row["tik_sleepdate"];
		if ($sleepdate>time())
		{
			$eisenhower=chr(ord($eisenhower)+4);;
		}
		else
		{
			$sleepdate = 0;
		}

		// Alles egal, wenn das Ticket geschlossen ist
		if ($this->row["tik_status"]==0){$eisenhower="Z";}

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_eisenhower",$eisenhower);
		$mySQL->addField("tik_sleepdate",$sleepdate);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		//echo $sql;
		$rs = $myDB->query($sql);
		$this->loadById($this->id);

		//echo "CALC:";
		//var_dump($this);

	}


	function build_Fulltext()
	{
		global $myDB;
		$myUser = new PhenotypeUser();


		$fulltext ="";

		$fulltext .= $this->row["tik_bez"] . " ";
		$fulltext .= $myUser->getName($this->row["usr_id_owner"])  . " ";
		$fulltext .= $myUser->getName($this->row["usr_id_creator"])  . " ";

		$sql = "SELECT * FROM ticketaction WHERE tik_id = " . $this->id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$fulltext .= $row["act_comment"];
		}

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_fulltext",$fulltext);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		//echo $fulltext;
	}

	function logAction($type,$comment="",$p1="",$p2="")
	{
		global $myDB;
		$mySQL = new SQLBuilder();
		$myUser = new PhenotypeUser();

		$mySQL->addField ("tik_id",$this->id,DB_NUMBER);
		$mySQL->addField ("act_type",$type,DB_NUMBER);
		$mySQL->addField ("act_date",time(),DB_NUMBER);
		$mySQL->addField ("usr_id",$_SESSION["usr_id"],DB_NUMBER);
		$mySQL->addField ("act_comment",$comment);


		$percentage = $this->row["tik_percentage"];
		$duration = $this->row["tik_duration"];
		switch ($type)
		{
			case 1: // erzeugt
			$this->actionset("creator",$myUser->getName($_SESSION["usr_id"]));
			$this->actionset("owner",$myUser->getName($this->row["usr_id_owner"]));
			break;
			case 2: // delegiert
			$this->actionset("newowner",$myUser->getName($p1));
			break;
			case 3: // akzeptiert
			break;
			case 4: // bearbeitet
			$this->actionset("minutes",$p1);
			$this->actionset("oldpercentage",$percentage);
			$duration += $p1;
			$percentage = $p2;
			break;
			case 5: // geschlossen
			$percentage = $p2;
			break;
			case 6: // angesehen
			$log = "Ticketverlauf von " . $myUser->getName($_SESSION["usr_id"]) . " angesehen.";

			break;
			case 7: // kommentiert
			break;
			case 8: // zurueckgewiesen
			break;
			case 9: // konfiguriert
			foreach ($p1 AS $key => $val)
			{
				$this->actionset($key,$val);
			}
			break;
			case 10: // Notizen ge�ndert

			break;
			case 11: // Bereich gewechselt
			$this->actionset("newsubject",$p1);

			break;
			case 12: // erneut geoeffnet

			break;
			case 13: // Hinweis eingestellt
			$this->actionset("aim",$p1);
			break;
			case 14: // Frage eingestellt
			$this->actionset("aim",$p1);
			break;
			case 15: // Ticket zur&uuml;ckgestellt
			$this->actionset("sleepdate",$p1);
			break;
			case 16: // Ticket-Rueckstellung aufgehoben
			break;
			case 17: // Dokument angehaengt
			$this->actionset("med_id",$p1);
			break;
			case 18: // Bereich gewechselt
			$this->actionset("new2ndorder",$p1);

			break;
			case 19: // Lesehinweis entfernt
			break;
			case 20: // Fragehinweis entfernt
			break;

		}
		$this->set("percentage",$percentage);
		$this->set("duration",$duration);
		$props = $this->props;
		foreach ($this->actionprops as $key => $val)
		{
			$props[$key] = $val;
		}

		$mySQL->addField ("act_details",serialize($props));
		$sql = $mySQL->insert("ticketaction");
		$rs = $myDB->query($sql);

		$act_id = mysql_insert_id();

		// Ticket mit der aktuellen Prozentzahl und Bearbeitungszeit versehen
		//$sql = "SELECT SUM(act_duration) AS S FROM ticketaction WHERE tik_id = " . $this->id;
		//$rs = $myDB->query($sql);
		//$row = mysql_fetch_array($rs);

		$mySQL = new SQLBuilder();
		$mySQL->addField("tik_percentage",$percentage,DB_NUMBER);
		$mySQL->addField("tik_duration",$duration,DB_NUMBER);
		$mySQL->addField("tik_lastaction",time(),DB_NUMBER);
		$sql = $mySQL->update("ticket","tik_id=" . $this->id);
		$rs = $myDB->query($sql);

		// Volltextindex aktualisieren
		$this->build_Fulltext();

		return ($act_id);
	}

	function attach_document($comment,$fname)
	{
		global $myDB;

		$dateiname_original =  $_FILES[$fname]["name"];
		$suffix = mb_strtolower(mb_substr($dateiname_original,mb_strrpos($dateiname_original,".")+1));

		$myMB = new PhenotypeMediabase();
		$myMB->setMediaGroup(3);

		$folder = "_ticket / " . sprintf("%05d",$this->id);

		$type = MB_DOCUMENT;
		$_suffix = Array("jpg","gif","jpeg");
		if (in_array($suffix,$_suffix))
		{
			$type = MB_IMAGE;
		}

		if ($type== MB_IMAGE)
		{
			$id = $myMB->uploadImage($fname,$folder);
		}
		else
		{
			$id = $myMB->uploadDocument($fname,$folder);
		}
		if ($id)
		{

			$act_id =  $this->logAction(17,$comment,$id);
		}
		// ------------------------------------------------------
	}

	function markup_usr($usr_id)
	{
		global $myDB;
		$sql = "SELECT * FROM ticketmarkup WHERE tik_id = " . $this->id . " AND usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
			$mySQL->addField("tik_id",$this->id,DB_NUMBER);
			$sql = $mySQL->insert("ticketmarkup");
			//echo $sql;
			$myDB->query($sql);
		}
	}

	function request_usr($usr_id)
	{
		global $myDB;
		$sql = "SELECT * FROM ticketrequest WHERE tik_id = " . $this->id . " AND usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
			$mySQL->addField("tik_id",$this->id,DB_NUMBER);
			$sql = $mySQL->insert("ticketrequest");
			//echo $sql;
			$myDB->query($sql);
		}
	}


	function checkAutomaticRequestRemoval()
	{
		global $myPT;
		global $myDB;

		if ($myPT->getIPref("tickets.active_request_removal")==0)
		{
			// F�r den Fall, dass auf eine Frage mit einer Gegenfrage oder einem Gegenhinweis reagiert wurde
			// Check, ob ein Markup vorliegt, wenn nein Fragezeichen loeschen

			$sql = "SELECT * FROM ticketmarkup WHERE tik_id = " . $this->id . " AND usr_id = " . $_SESSION["usr_id"];
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs)==0)
			{
				$sql = "DELETE FROM ticketrequest WHERE tik_id = " . $this->id . " AND usr_id = " . $_SESSION["usr_id"];
				$myDB->query($sql);
			}
		}
		// ---------------------------------------------------------------------------------------------
	}


	function hasMarkup($usr_id)
	{
		global $myDB;
		$sql = "SELECT * FROM ticketmarkup WHERE tik_id=".$this->id." AND usr_id=".$usr_id;
		$rs=$myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			return false;
		}
		return true;
	}

	function hasRequest($usr_id)
	{
		global $myDB;
		$sql = "SELECT * FROM ticketrequest WHERE tik_id=".$this->id." AND usr_id=".$usr_id;
		$rs=$myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
			return false;
		}
		return true;
	}

	function rawXMLExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="'.PT_CHARSET.'" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<content>';

		$_felder = Array("tik_id","tik_bez","sbj_id","dat_id_2ndorder","tik_eisenhower","pag_id","ver_id","med_id","dat_id_content","usr_id_creator","usr_id_owner","tik_accepted","tik_status","tik_startdate","tik_enddate","tik_creationdate","tik_closingdate","tik_targetdate","tik_sleepdate","tik_percentage","tik_duration","tik_prio","tik_complexity","tik_tendency","tik_lastaction","tik_fulltext","tik_notice");

		foreach ($_felder AS $k)
		{
			$xml.= '<'.$k.'>'.$myPT->codeX($this->row[$k]).'</'.$k.'>'."\n";
		}

		$xml.='
		<tik_props>'.base64_encode($row["tik_props"]).'</tik_props>
		<ticketaction>';

		$sql = "SELECT * FROM ticketaction WHERE tik_id = " . $this->id . " ORDER BY act_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml.='<action>';

			$_felder = Array ("act_id","act_type","act_date","usr_id","act_comment");
			foreach ($_felder AS $k)
			{
				$xml.= '<'.$k.'>'.$myPT->codeX($row[$k]).'</'.$k.'>'."\n";
			}
			$xml .='<act_details>'.base64_encode($row["act_details"]).'</act_details></action>';
		}
		$xml .='</ticketaction>';

		// Hinweise
		$xml .= '<ticketmarkup>';
		$sql = "SELECT * FROM ticketmarkup WHERE tik_id=".$this->id . " ORDER BY usr_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .= '<user usr_id="'.$row["usr_id"].'"/>';
		}
		$xml .= '</ticketmarkup>';

		// Fragen
		$xml .= '<ticketrequest>';
		$sql = "SELECT * FROM ticketrequest WHERE tik_id=".$this->id. " ORDER BY usr_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .= '<user usr_id="'.$row["usr_id"].'"/>';
		}
		$xml .= '</ticketrequest>';

		// Pins
		$xml .= '<ticketpin>';
		$sql = "SELECT * FROM ticketpin WHERE tik_id=".$this->id. " ORDER BY usr_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .= '<user usr_id="'.$row["usr_id"].'"/>';
		}
		$xml .= '</ticketpin>';

		$xml .='
	</content>
</phenotype>';
		return $xml;
	}


	function rawXMLSubjectsExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="'.PT_CHARSET.'" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<ticketsubject>';
		$sql = "SELECT * FROM ticketsubject ORDER BY sbj_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
		<subject>
			<sbj_id>'.$row["sbj_id"].'</sbj_id>
			<sbj_bez>'.$myPT->codeX($row["sbj_bez"]).'</sbj_bez>
			<sbj_description>'.$myPT->codeX($row["sbj_description"]).'</sbj_description>
		 </subject>';
		}
		$xml.='
	</ticketsubject>';


		$xml .='</phenotype>';
		return $xml;
	}


	function rawXMLSubjectsImport($buffer)
	{
		global $myPT;
		global $myDB;
		
		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			foreach ($_xml->ticketsubject->subject AS $_xml_subject)
			{
				$sbj_id = (int)pt_package_xml_decode($_xml_subject->sbj_id);
				$sql = "DELETE FROM ticketsubject WHERE sbj_id=".$sbj_id;
				$rs = $myDB->query($sql);
				$mySQL = new SQLBuilder();
				$mySQL->addField("sbj_id",$sbj_id,DB_NUMBER);
				$_felder = Array ("sbj_bez","sbj_description");
				foreach ($_felder AS $k)
				{
					$mySQL->addField($k,(string)pt_package_xml_decode($_xml_subject->$k));
				}
				$sql = $mySQL->insert("ticketsubject");
				$myDB->query($sql);
			}
		}
		else
		{
			return (false);
		}
	}
	
	function rawXMLImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			//$importmethod = (string)pt_package_xml_decode($_xml->meta->importmethod);

			$tik_id = (int)pt_package_xml_decode($_xml->content->tik_id);

			$sql  ="DELETE FROM ticketmarkup WHERE tik_id=".$tik_id;
			$myDB->query($sql);

			$sql  ="DELETE FROM ticketrequest WHERE tik_id=".$tik_id;
			$myDB->query($sql);

			$sql  ="DELETE FROM ticketpin WHERE tik_id=".$tik_id;
			$myDB->query($sql);

			$sql  ="DELETE FROM ticketaction WHERE tik_id=".$tik_id;
			$myDB->query($sql);

			$sql  ="DELETE FROM ticket WHERE tik_id=".$tik_id;
			$myDB->query($sql);

			$mySQL = new SQLBuilder();
			$_felder = Array("tik_id","tik_bez","sbj_id","dat_id_2ndorder","tik_eisenhower","pag_id","ver_id","med_id","dat_id_content","usr_id_creator","usr_id_owner","tik_accepted","tik_status","tik_startdate","tik_enddate","tik_creationdate","tik_closingdate","tik_targetdate","tik_sleepdate","tik_percentage","tik_duration","tik_prio","tik_complexity","tik_tendency","tik_lastaction","tik_fulltext","tik_notice");
			foreach ($_felder AS $k)
			{
				$mySQL->addField($k,(string)pt_package_xml_decode($_xml->content->$k));
			}

			$tik_props = (string)pt_package_xml_decode($_xml->content->tik_props);

			$mySQL->addField("tik_props",base64_decode($tik_props));
			
			
			$sql = $mySQL->insert("ticket");
			$myDB->query($sql);

			$_felder = Array ("act_id","act_type","act_date","usr_id","act_comment");

			foreach ($_xml->content->ticketaction->action AS $_xml_action)
			{
				$mySQL = new SQLBuilder();
				foreach ($_felder AS $k)
				{
					$mySQL->addField($k,(string)pt_package_xml_decode($_xml_action->$k));
				}
				$mySQL->addField("tik_id",$tik_id,DB_NUMBER);
				$act_details = (string)pt_package_xml_decode($_xml_action->act_details);

				$mySQL->addField("act_details",base64_decode($act_details));
				$sql = $mySQL->insert("ticketaction");
				$myDB->query($sql);
			}

			foreach ($_xml->content->ticketmarkup->user AS $_xml_user)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("usr_id",(int)pt_package_xml_decode($_xml_user["usr_id"]));
				$mySQL->addField("tik_id",$tik_id,DB_NUMBER);
				$sql = $mySQL->insert("ticketmarkup");
				$myDB->query($sql);
			}

			foreach ($_xml->content->ticketrequest->user AS $_xml_user)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("usr_id",(int)pt_package_xml_decode($_xml_user["usr_id"]));
				$mySQL->addField("tik_id",$tik_id,DB_NUMBER);
				$sql = $mySQL->insert("ticketrequest");
				$myDB->query($sql);
			}

			foreach ($_xml->content->ticketpin->user AS $_xml_user)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("usr_id",(int)pt_package_xml_decode($_xml_user["usr_id"]));
				$mySQL->addField("tik_id",$tik_id,DB_NUMBER);
				$sql = $mySQL->insert("ticketpin");
				$myDB->query($sql);
			}

			return $tik_id;
		}
		else
		{
			return (false);
		}
	}


}
?>