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

// ToDO:
// Geschlossen-Tab zeigt immer die Anzahl aller geschlossene Tickets an, besser nur die des aktiven Zeitraumes !?
// Kompaktsicht durchziehen
// In der Suche werden eigene Tickets aus nichtzugeordneten Bereichen nicht gefunden, in der Übersicht schon

/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Ticket_Assess_Standard extends PhenotypeBackend_Ticket
{



	function execute($scope,$action)
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;


		$this->checkRight("elm_task",true);

		$this->setPageTitle("Phenotype ".$myPT->version. " Aufgaben");

		$this->selectMenuItem(5);
		$this->selectLayout(1);

		
		// Pin?
		
		if ($action=="pin")
		{
			$tik_id = $myRequest->getI("id");
			$this->pin($tik_id);
		}
		
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
		$sbj_id = $myRequest->getI("sbj_id");
		$dat_id = $myRequest->getI("dat_id");



		$this->fillLeftArea($this->renderExplorer($scope,$sbj_id,$dat_id,$focus,$sortorder));
		$this->fillContentArea1($this->renderOverview($sbj_id,$dat_id,$focus,$sortorder,$scope,$action));


		$this->displayPage();

	}






	function renderOverview($sbj_id,$dat_id,$focus,$sortorder,$scope,$action)
	{
		global $myPT;
		$myPT->startBuffer();

		//echo $action;

		global $myRequest;
		global $myDB;
		global $myAdm;
		global $mySUser;

		// Meine Hinweise und Anfragen in temporaere Tabellen
		$this->storeRequestsTemporary();
		$this->storeMarkupsTemporary();
		$this->storePinsTemporary();


		// Darstellungsvariante bestimmen
		$mode=1;
		if ($myPT->getIPref("tickets.default_compactmode")==1)
		{
			$mode = 2;
		}

		// Gibt es eine User-Präferenz?
		if ($mySUser->getPref("ticket_compact")!==false)
		{
			$mode = 1;
			if ($mySUser->getPref("ticket_compact")==1)
			{
				$mode=2;
			}
		}

		// Grund-SQL fuer alle Detailabfragen
		$sql_join = $this->getBaseSelectSQL();
		$sql_join_user = $this->getBaseUserSelectSQL();



		$headline = "Aufgabenübersicht";


		// --------------------------------------------------------------------------------------------
		// Suche ??

		$sql_suche = "";
		$suchparameter = "";
		$idhit=0;

		if ($action=="search")
		{
			$headline = "Suchergebnis - ";

			$suchparameter ="&suche=1&s=" . urlencode($myRequest->get("s"))."&v=" . urlencode($myRequest->get("v"))."&i=" . urlencode($myRequest->get("i"));
			if ($_REQUEST["s"]!="")
			{
				$headline .=" Suche nach " . $myRequest->get("s");
				$sql_suche = " AND tik_bez LIKE '%".addslashes($myRequest->get("s"))."%'";
			}
			if ($_REQUEST["v"]!="")
			{
				$headline .=" Volltextsuche nach " . $myRequest->get("v");
				$sql_suche = " AND tik_fulltext LIKE '%".addslashes($myRequest->get("v"))."%'";
			}
			if ($_REQUEST["i"]!="")
			{
				$headline .=" Suche nach Ticket ID " . $myRequest->get("id");
				$sql_suche = " AND ticket.tik_id =" . (int)$_REQUEST["i"];
			}
		}


		// --------------------------------------------------------------------------------------------

		?>
		<form action="backend.php" enctype="multipart/form-data" name="formsort" id="formsort">
		<input type="hidden" name="page" value="Ticket,Assess,<?=$action?>">
		<input type="hidden" name="sbj_id" value="<?=$sbj_id?>"?>
		<input type="hidden" name="dat_id" value="<?=$dat_id?>"?>
		<input type="hidden" name="s" value="<?=addslashes($myRequest->get("s"))?>">
		<input type="hidden" name="v" value="<?=addslashes($myRequest->get("v"))?>">
		<input type="hidden" name="i" value="<?=addslashes($myRequest->get("i"))?>">
		<?

		$this->displayHeadline($headline,"http://www.phenotype-cms.de/docs.php?v=23&t=7");


		// --------------------------------------------------------------------------------------------

		$this->tab_new();

		$nrofdays_listing_closedtickets = $myPT->getIPref("tickets.nrofdays_listing_closedtickets");

		// ToDo Suche auf eigene Aufgabenbereiche einschränken
		if ($sbj_id==0)
		{

			$sql_2ndorder ="";

			if ($dat_id!=0)
			{
				$sql_2ndorder =" AND (dat_id_2ndorder=" . $dat_id.")";
			}

			$sql = "SELECT COUNT(*) AS C FROM ticket WHERE tik_status=1" . $sql_suche . $sql_2ndorder;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$block0="Alle (" . $row["C"] .")";

			$sql = "SELECT COUNT(*) AS C FROM ticket WHERE tik_status=1 AND (ticket.usr_id_owner = " .$mySUser->id . ")" . $sql_suche  . $sql_2ndorder;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$block1="Meine (" . $row["C"] .")";

			$sql = $sql_join . "WHERE (tik_status = 1 OR (tik_status = 0 AND  tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")) AND tik_request =1" . $sql_suche  . $sql_2ndorder;
			$rs = $myDB->query($sql);
			$block2="Fragen (" . mysql_num_rows($rs) .")";
			$n2 = mysql_num_rows($rs);

			$sql = $sql_join . "WHERE (tik_status = 1 OR (tik_status = 0 AND  tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")) AND (tik_markup =1 OR tik_request=1)" . $sql_suche  . $sql_2ndorder;
			$rs = $myDB->query($sql);
			$block3="Hinweise (" . mysql_num_rows($rs) .")";
			$n3 = mysql_num_rows($rs);

			if ($myPT->getIPref("tickets.tab_closedtickets")==1)
			{
				$sql = $sql_join . "WHERE (tik_status = 0)" . $sql_suche  . $sql_2ndorder;
				$rs = $myDB->query($sql);
				$block4="Geschlossen (" . mysql_num_rows($rs) .")";
				$n4 = mysql_num_rows($rs);
			}
			
			$sql = $sql_join . "WHERE (tik_status = 1 OR (tik_status = 0 AND  tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")) AND (tik_pin =1 )" . $sql_suche  . $sql_2ndorder;
			$rs = $myDB->query($sql);
			$block5="Gemerkt (" . mysql_num_rows($rs) .")";
			$n5 = mysql_num_rows($rs);			
		}
		else
		{
			$sql = "SELECT COUNT(*) AS C FROM ticket WHERE sbj_id = " . $sbj_id . " AND tik_status=1" . $sql_suche;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$block0="Alle Aufgaben (" . $row["C"] .")";

			$sql = "SELECT COUNT(*) AS C FROM ticket WHERE sbj_id = " . $sbj_id . " AND tik_status=1 AND (ticket.usr_id_owner = " .$mySUser->id . ")" . $sql_suche;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$block1="Meine Aufgaben (" . $row["C"] .")";

			// Hinweise und Fragen von geschlossenen Tickets bleiben in der Detailsicht immer stehen

			$sql = $sql_join . "WHERE tik_status = 1 AND ticket.sbj_id = ". $sbj_id ." AND tik_request =1" . $sql_suche;
			$rs = $myDB->query($sql);
			$block2="Fragen (" . mysql_num_rows($rs) .")";
			$n2 = mysql_num_rows($rs);

			$sql = $sql_join . "WHERE tik_status = 1 AND ticket.sbj_id = ". $sbj_id ." AND (tik_markup =1 OR tik_request=1)" . $sql_suche;
			$rs = $myDB->query($sql);
			$block3="Hinweise (" . mysql_num_rows($rs) .")";
			$n3 = mysql_num_rows($rs);
			
			if ($myPT->getPref("tickets.tab_closedtickets")==1)
			{
				$sql = $sql_join . "WHERE tik_status = 0 AND ticket.sbj_id = ". $sbj_id . $sql_suche;
				$rs = $myDB->query($sql);
				$block4="Geschlossen (" . mysql_num_rows($rs) .")";
				$n4 = mysql_num_rows($rs);
			}
			
			$sql = $sql_join . "WHERE tik_status = 1 AND ticket.sbj_id = ". $sbj_id ." AND (tik_pin =1)" . $sql_suche;
			$rs = $myDB->query($sql);
			$block5="Gemerkt (" . mysql_num_rows($rs) .")";
			$n5 = mysql_num_rows($rs);			
			
		}

		if ($focus==2 AND $n2==0){$focus=3;} // Fragen->Hinweise

		// --------------------------------------------------------------------------------------------

		$params = "&sortorder=" . $sortorder ."&sbj_id=" . $sbj_id."&dat_id=".$dat_id.$suchparameter;

		$this->tab_addEntry($block0,"backend.php?page=Ticket,Assess,".$action."&focus=0".$params,"b_job.gif");
		$this->tab_addEntry($block1,"backend.php?page=Ticket,Assess,".$action."&focus=1".$params,"b_myjob.gif");
		$this->tab_addEntry($block3,"backend.php?page=Ticket,Assess,".$action."&focus=3".$params,"b_notice.gif");
		if ($n2>0) // Sind Fragen vorhanden
		{
			$this->tab_addEntry($block2,"backend.php?page=Ticket,Assess,".$action."&focus=2".$params,"b_request.gif");
		}
		if ($myPT->getPref("tickets.tab_closedtickets")==1)
		{
			$this->tab_addEntry($block4,"backend.php?page=Ticket,Assess,".$action."&focus=4".$params,"b_closed.gif");

		}
		$this->tab_addEntry($block5,"backend.php?page=Ticket,Assess,".$action."&focus=5".$params,"b_pinadd.gif");

		$vname = 'block'.$focus;
		$this->tab_draw($$vname);


		// --------------------------------------------------------------------------------------------


		// Default-Sortierung bei geschlossenen Ticket nach Einstellungsdatum
		?>
		<input type="hidden" name="focus" value="<?=$focus?>"?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
			<tr>
	        <td class="windowHeaderGrey2">
	        	<table border="0" cellspacing="0" cellpadding="0"> 	            
	        		<tr>
	              		<td class="padding10">
	              		<?
	              		if ($focus ==4)
	              		{
	              			if ($myRequest->check("adatum"))
	              			{
	              				$adatum = $myRequest->get("adatum");
	              			}
	              			else
	              			{
	              				$adatum = date ('d.m.Y',time()-24*14*3600);
	              			}
	              			if ($myRequest->check("edatum"))
	              			{
	              				$edatum = $myRequest->get("edatum");
	              			}
	              			else
	              			{
	              				$edatum = date ('d.m.Y',time());
	              			}
	        				?>
	            		zeige Tickets, die zwischen <input type="text" name="adatum" value="<?=$this->getH($adatum)?>" class="input" style="width:60px" onchange="document.forms.formsort.submit();" onblur="document.forms.formsort.submit();"/> und <input type="text" name="edatum" value="<?=$this->getH($edatum)?>" class="input" style="width:60px" onchange="document.forms.formsort.submit();"/> geschlossen wurden. <input type="image" src="img/transparent.gif"/>
						<?
	              		}
	              		else
	              		{
	          			?>
	                   	<input name="sortorder" type="radio" value="1" <?if($sortorder==1){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                ABCD
		                <input type="radio" name="sortorder" value="2" <?if($sortorder==2){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                Wichtigkeit
		                <input type="radio" name="sortorder" value="3" <?if($sortorder==3){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                Dringlichkeit
		                <input type="radio" name="sortorder" value="4" <?if($sortorder==4){echo"checked";}?> onclick="document.forms.formsort.submit();"> 
		                Datum
		                <input type="radio" name="sortorder" value="5" <?if($sortorder==5){echo"checked";}?> onclick="document.forms.formsort.submit();"> 
		                letzte &Auml;nderung
		                <input type="radio" name="sortorder" value="6" <?if($sortorder==6){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                Bezeichnung
		                <input type="radio" name="sortorder" value="7" <?if($sortorder==7){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                Bearbeiter		
		                <input type="radio" name="sortorder" value="8" <?if($sortorder==8){echo"checked";}?> onclick="document.forms.formsort.submit();">
		                Einsteller			
	           	   <?
	              		}
				?>
	        	</td>
	              </tr></table></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	      <tr>
	        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
	        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
	      </tr>
	    </table>
		</form>
		<?


		switch ($focus)
		{
			case "1":
				$sql_focus = " AND (ticket.usr_id_owner = " .$_SESSION["usr_id"].")";
				break;
			case "2":
				$sql_focus = " AND tik_request =1";
				break;
			case "3":
				$sql_focus = " AND (tik_markup =1 OR tik_request=1)";
				break;
			case "4":
				$sql_focus = " AND tik_status=0";
				break;
			case "5":
				$sql_focus = " AND tik_pin =1";
				break;				
			default:
				$sql_focus = "";
				break;

		}

		$sql_2ndorder ="";

		if ($dat_id!=0)
		{
			$sql_2ndorder =" AND (dat_id_2ndorder=" . $dat_id.")";
		}

		$sql = $sql_join;

		switch ($sortorder)
		{
			case "1":
				$sql_order = "ORDER BY tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "2":
				$sql_order = "ORDER BY tik_prio, tik_eisenhower, tik_enddate";
				break;

			case "3":
				$sql_order = "ORDER BY tik_enddate, tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "4":
				$sql_order = "ORDER BY tik_creationdate DESC, tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "5":
				$sql_order = "ORDER BY tik_lastaction DESC, tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "6":
				$sql_order = "ORDER BY tik_bez, tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "7":
				$sql = $sql_join_user;
				$sql_order = "ORDER BY owner.usr_nachname, owner.usr_vorname, tik_eisenhower, tik_prio, tik_enddate";
				break;

			case "8":
				$sql = $sql_join_user;
				$sql_order = "ORDER BY creator.usr_nachname, creator.usr_vorname, tik_eisenhower, tik_prio, tik_enddate";
				break;

			default:
				$sql_order = "ORDER BY tik_eisenhower, tik_prio, tik_enddate";
				break;
		}



		// --------------------------------------------------------------------------------------------

		// determine subjects

		$_subjects = Array();

		if ($sbj_id != 0) // Bestimmter Bereich
		{
			$sql_ticketsubject ="SELECT * FROM ticketsubject WHERE ticketsubject.sbj_id=". $sbj_id;
		}
		else
		{
			// Alle Bereiche

			$sql_ticketsubject ="SELECT * FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $mySUser->id;
		}
		$rs_ticketsubject = $myDB->query($sql_ticketsubject);
		while ($row_ticketsubject = mysql_fetch_array($rs_ticketsubject))
		{
			$_subjects[$row_ticketsubject["sbj_id"]]=$row_ticketsubject["sbj_bez"];
		}




		// --------------------------------------------------------------------------------------------

		if ($action=="search")
		{
			// Zunächst pro Bereich suchen
			foreach ($_subjects AS $k => $v)
			{
				$sql_subject = "WHERE ((tik_status = 1 AND ticket.sbj_id = ". $k. ") OR (tik_status = 0 AND ticket.sbj_id = ". $k ." AND tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")) ";

				if ($focus==4)
				{
					$sql_subject = "WHERE (tik_status = 0 AND ticket.sbj_id = ". $k. ")";
				}

				$sql_doit = $sql . $sql_subject .  $sql_focus . $sql_2ndorder . $sql_suche ." " . $sql_order;
				$rs = $myDB->query($sql_doit);
				if (mysql_num_rows($rs)!=0)
				{
					$this->ticketBox($v,$rs,$mode,$scope,$sbj_id,$dat_id,$focus,$sortorder);
				}
			}

			// Dann die Archivsuche, wird nicht angezeigt, wenn generell geschlossene Tickets abgerufen werden können

			if ($idhit==0 AND $myPT->getPref("tickets.tab_closedtickets")!=1) // Nur, wenn nicht nach ID gesucht wurde und in das Ticket in den aktiven gefunden wurde
			{
				if ($sbj_id != 0) // Bestimmter Bereich
				{
					$sql_subject = "AND ticket.sbj_id = ". $sbj_id;
				}
				else
				{
					// Alle Bereiche
					$sql_subject =" AND ticket.sbj_id IN(".implode(",",array_keys($_subjects)).")";

					// Hier noch ein Fehler mit Tickets aus fremden Bereichen
					// Da in $rs_ticketsubject nur die Bereiche drin sind, auf die ein User Zugriff hat, werden Tickets aus Bereichen
					// auf die ein User kein Zugriff hat, bei denen er aber Owner/Creator ist, in der Suche nicht gefunden

				}

				$sql_doit = $sql .   $sql_focus . " WHERE tik_status=0 " . $sql_2ndorder . $sql_subject . $sql_suche ." " . $sql_order . " LIMIT 0,50";

				//echo $sql_doit;
				$rs = $myDB->query($sql_doit);

				if (mysql_num_rows($rs)!=0)
				{
					$this->ticketBox("Archiv (maximal 50 Treffer)",$rs,$mode,$scope,$sbj_id,$dat_id,$focus,$sortorder);

				}
			}

			// Temptabellen wieder loeschen
			$this->removeTemporaryTables();

			return($myPT->stopBuffer());
		}

		// --------------------------------------------------------------------------------------------

		// Geschlossene Tickets werden wochenweise angezeigt
		if ($focus==4)
		{
			if ($sbj_id != 0) // Bestimmter Bereich
			{
				$sql_subject = "WHERE ticket.sbj_id=". $sbj_id;
				$headline = $_subjects[$sbj_id];
			}
			else
			{
				$headline = "Alle Bereiche";
				$sql_subject = "WHERE ticket.sbj_id IN (" . implode(",",array_keys($_subjects)) . ")";
			}

			$adatum = (int)$myPT->german2Timestamp($adatum);
			$edatum = ((int)$myPT->german2Timestamp($edatum)+24*3600);


			$sql_timeframe = " AND tik_closingdate> " . $adatum . " AND tik_closingdate< " . $edatum;

			$sql_order = "ORDER BY tik_closingdate DESC";

			$sql_doit = $sql . $sql_subject .  $sql_focus . $sql_2ndorder . $sql_timeframe.  " " . $sql_order;

			$rs = $myDB->query($sql_doit);

			if (mysql_num_rows($rs)!=0)
			{
				$kw = "";
				while ($row=mysql_fetch_array($rs))
				{
					$kwakt = date('WY',$row["tik_closingdate"]);
					if ($kwakt!=$kw)
					{
						$aktwoche = $row["tik_closingdate"];

						$wochentag = date("w",$aktwoche);
						if ($wochentag==0){$wochentag=7;}
						$montag = mktime(0,0,0, date("m",$aktwoche),date("d",$aktwoche)-$wochentag+1,date("Y",$aktwoche));
						$sonntag = mktime(0,0,0, date("m",$aktwoche),date("d",$aktwoche)+(7-$wochentag),date("Y",$aktwoche));
						$montag2 = mktime(0,0,0, date("m",$sonntag),date("d",$sonntag)+1,date("Y",$sonntag));


						$headline = "KW " . date("W",$aktwoche). "/" . date('y',$aktwoche) . " - vom " . date('d.m',$montag) . "-" . date('d.m.Y',$sonntag);

						$sql_timeframe = " AND tik_closingdate>= " . $montag . " AND tik_closingdate< " . $montag2;
						$sql_doit = $sql . $sql_subject .  $sql_focus .  $sql_2ndorder . $sql_timeframe.  " " . $sql_order;
						$rs2 = $myDB->query($sql_doit);
						$this->ticketBox($headline,$rs2,$mode,$scope,$sbj_id,$dat_id,$focus,$sortorder);
						$kw = $kwakt;
					}
				}

			}
			// Temptabellen wieder loeschen
			$this->removeTemporaryTables();

			return($myPT->stopBuffer());
		}


		// --------------------------------------------------------------------------------------------
		// ab hier die "normale" Ansicht


		if ($sbj_id != 0) // Bestimmter Bereich
		{
			$sql_subject = "WHERE ((tik_status = 1 AND ticket.sbj_id=". $sbj_id .") OR (tik_status = 0 AND ticket.sbj_id=". $sbj_id ." AND tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")) ";

			$headline = $_subjects[$sbj_id];
		}
		else
		{
			$headline = "Alle Bereiche";

			if ($focus==0) // Alle
			{
				$sql_subject = "WHERE
				((tik_status = 1 AND ticket.sbj_id IN (" . implode(",",array_keys($_subjects)) . ")) OR (tik_status = 0 AND ticket.sbj_id IN (" . implode(",",array_keys($_subjects)) . ") AND tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) .")";

				// Man sieht auch eigene Tickets aus Bereichen, die man eigentlich nicht hat
				$sql_subject .= "OR ((ticket.usr_id_owner = " .$mySUser->id . " OR ticket.usr_id_creator = " .$mySUser->id .") AND tik_status=1)";
				$sql_subject .= "OR ((ticket.usr_id_owner = " .$mySUser->id . " OR ticket.usr_id_creator = " .$mySUser->id .") AND tik_status=0 AND tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) . "))".$sql_2ndorder ;
			}
			else // Meine, Fragen, Hinweise, Pins
			{
				$sql_subject = " WHERE ((tik_status=0 AND tik_closingdate > ". (time()-(3600*12*$nrofdays_listing_closedtickets)) . ")OR(tik_status=1))". $sql_2ndorder."";
			}
		}



		$sql_doit = $sql . $sql_subject .  $sql_focus  ." " . $sql_order;

		//echo $sql_doit;

		$rs = $myDB->query($sql_doit);

		if (mysql_num_rows($rs)!=0)
		{
			$this->ticketBox($headline,$rs,$mode,$scope,$sbj_id,$dat_id,$focus,$sortorder);
		}



		// Temptabellen wieder loeschen
		$this->removeTemporaryTables();

		return($myPT->stopBuffer());
	}



}
?>