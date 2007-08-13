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
class PhenotypeBackend_Ticket_Standard extends PhenotypeBackend
{

	public $temptable_request;
	public $temptable_markup;
	public $temptable_pin;

	function storeRequestsTemporary()
	{
		global $myDB;

		$sql_request = " SELECT tik_id,tik_request FROM ticketrequest WHERE usr_id=" . $_SESSION["usr_id"];
		$this->temptable_request ="temp_request_" . uniqid("pt");
		$sql = "CREATE TEMPORARY TABLE " . $this->temptable_request . $sql_request;

		$rs = $myDB->query($sql);

	}


	function storeMarkupsTemporary()
	{
		global $myDB;

		$sql_markup = " SELECT tik_id,tik_markup FROM ticketmarkup WHERE usr_id=" . $_SESSION["usr_id"];

		$this->temptable_markup ="temp_markup_" . uniqid("pt");
		$sql = "CREATE TEMPORARY TABLE " . $this->temptable_markup . $sql_markup;
		$rs = $myDB->query($sql);

	}
	
	function storePinsTemporary()
	{
		global $myDB;

		$sql_pin = " SELECT tik_id,tik_pin FROM ticketpin WHERE usr_id=" . $_SESSION["usr_id"];
		$this->temptable_pin ="temp_pin_" . uniqid("pt");
		$sql = "CREATE TEMPORARY TABLE " . $this->temptable_pin . $sql_pin;

		$rs = $myDB->query($sql);

	}	

	function removeTemporaryTables()
	{
		global $myDB;

		$sql = "DROP TEMPORARY TABLE " . $this->temptable_markup;
		$rs = $myDB->query($sql);
		$sql = "DROP TEMPORARY TABLE " . $this->temptable_request;
		$rs = $myDB->query($sql);
				$sql = "DROP TEMPORARY TABLE " . $this->temptable_pin;
		$rs = $myDB->query($sql);
	}

	function getBaseSelectSQL()
	{
		$sql = "SELECT *, ticket.tik_id AS tik_id FROM ticket LEFT JOIN ticketsubject ON ticket.sbj_id = ticketsubject.sbj_id LEFT JOIN $this->temptable_request ON ticket.tik_id = $this->temptable_request.tik_id LEFT JOIN $this->temptable_markup ON ticket.tik_id = $this->temptable_markup.tik_id LEFT JOIN $this->temptable_pin ON ticket.tik_id = $this->temptable_pin.tik_id ";
		return ($sql);
	}

	function getBaseUserSelectSQL()
	{

		$sql = $this->getBaseSelectSQL(). "LEFT JOIN user AS owner ON ticket.usr_id_owner = owner.usr_id LEFT JOIN user AS creator ON ticket.usr_id_creator = creator.usr_id ";
		return ($sql);
	}

	function build2ndOrderOptionsArray($dat_id)
	{
		global $myPT;
		global $myDB;

		$_options = Array();

		$sql ="SELECT dat_bez FROM content_data WHERE dat_id=" . (int)$dat_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if (mysql_num_rows($rs)!=0)
		{
			$_options[$dat_id]=$row["bez"];
		}

		$sql ="SELECT dat_id,dat_bez FROM content_data WHERE con_id=" . $myPT->getIPref("tickets.con_id_2ndorder") . " AND dat_status=1 ORDER BY dat_bez";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$_options[$row["dat_id"]]=$row["dat_bez"];

		}
		return ($_options);
	}

	function pin($tik_id)
	{
		global $mySUser;
		global $myDB;
		
		$sql = "SELECT * FROM ticketpin WHERE tik_id=".$tik_id ." AND usr_id=".$mySUser->id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			$sql = "DELETE FROM ticketpin WHERE tik_id=".$tik_id ." AND usr_id=".$mySUser->id;
			$rs = $myDB->query($sql);
		}
		else 
		{
			$mySQL = new SQLBuilder();
			$mySQL->addField("tik_id",$tik_id,DB_NUMBER);
			$mySQL->addField("usr_id",$mySUser->id,DB_NUMBER);
			$sql = $mySQL->insert("ticketpin");
			$rs = $myDB->query($sql);
		}

	}
	
	function renderExplorer($scope,$sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myPT;
		$myPT->startBuffer();

		$search_term = $this->displayAssessTree($sbj_id,$dat_id,$focus,$sortorder);
		$this->displayNewTaskButton($sbj_id,$dat_id,$focus,$sortorder);
		$this->displaySearchForm($sbj_id,$dat_id,$focus,$sortorder,$search_term);

		return ($myPT->stopBuffer());
	}

	function displayAssessTree($sbj_id,$dat_id,$focus,$sortorder)
	{
		global $myDB;
		global $myPT;

		$url = "backend.php?page=Ticket,Assess&focus=".$focus."&sortorder=".$sortorder."&dat_id=0&sbj_id=0";
		$this->tab_addEntry("Aufgaben",$url,"b_job.gif");

		if ($myPT->getIPref("tickets.con_id_2ndorder")!=0)
		{
			$_projects = $this->build2ndOrderOptionsArray(0);

			$_project = each($_projects);
			$url = "backend.php?page=Ticket,Assess&dat_id=".$_project[0]."&focus=".$focus."&sortorder=".$sortorder."&sbj_id=0";
			$this->tab_addEntry($myPT->getPref("tickets.tab_2ndorder"),$url,"b_job.gif");
		}

		if ($dat_id==0)
		{
			$param = "&focus=".$focus."&sortorder=".$sortorder."&dat_id=0";
			
			$this->tab_draw("Aufgaben",$x=260,1);
			$myNav = new PhenotypeTree();
			$nav_id  = $myNav->addNode("Alle Bereiche","backend.php?page=Ticket,Assess&amp;sbj_id=0".$param,0,"Alle Bereiche");

			$sql ="SELECT * FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $_SESSION["usr_id"] . " ORDER BY sbj_bez";
			$rs = $myDB->query($sql);
			$sbj_bez ="";

			while ($row=mysql_fetch_array($rs))
			{
				$myNav->addNode($row["sbj_bez"],"backend.php?page=Ticket,Assess&amp;sbj_id=".$row["sbj_id"].$param,$nav_id,$row["sbj_id"]);
				if ($sbj_id==$row["sbj_id"]){$sbj_bez = "/ " . $row["sbj_bez"];}
			}


			if ($sbj_id==0)
			{
				$this->displayTreeNavi($myNav,"Alle Bereiche");
			}
			else
			{
				$this->displayTreeNavi($myNav,$sbj_id);
			}
			return ($sbj_bez);
		}
		else
		{
			$dat_bez = "";
			$this->tab_draw($myPT->getPref("tickets.tab_2ndorder"),$x=260,1);
			$myNav = new PhenotypeTree();
			
			$param = "&focus=".$focus."&sortorder=".$sortorder."&sbj_id=0";
			
			foreach ($_projects AS $k => $v)
			{
				$myNav->addNode($v,"backend.php?page=Ticket,Assess&amp;dat_id=".$k.$param,0,$k);
				if ($dat_id==$k){$dat_bez = "/ " . $v;}
			}
			
			$this->displayTreeNavi($myNav,$dat_id);
			return ($dat_bez);
		}




	}

	function displayNewTaskButton($sbj_id,$dat_id_2ndorder,$focus,$sortorder)
	{
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
	        <tr>
          <td class="windowFooterGrey2"><a href="javascript:ticketWizard(0,0,0,0,<?=$sbj_id?>,<?=$dat_id_2ndorder?>)" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> Neue Aufgabe einstellen </a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>
		<?
	}

	function displaySearchForm($sbj_id,$dat_id,$focus,$sortorder,$search_term="")
	{
		?>
		<form action="backend.php" method="post">
		<input type="hidden" name="page" value="Ticket,Assess,search">
 <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" class="padding10"><strong>Suche Aufgaben <?=$search_term?> nach:</strong></td>
            </tr>
            <tr>
              <td class="padding10">Bezeichnung</td>
              <td>
 	  		  <input type="hidden" name="sbj_id" value="<?=$sbj_id?>">
 	  		  <input type="hidden" name="dat_id" value="<?=$dat_id?>">
			  <input type="hidden" name="focus" value="<?=$focus?>">
			  <input type="hidden" name="sortorder" value="<?=$sortorder?>">
	    	  <input type="text" name="s" style="width: 100
			  px" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> ID </td>
              <td><input type="text" style="width: 100
			  px" name="i" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> Volltext </td>
              <td><input type="text" style="width: 100
			  px" name="v" class="input"></td>
            </tr>
            <tr>
              <td class="padding10">&nbsp;</td>
              <td><input name="Submit" type="submit" class="buttonGrey2" value="Senden" style="width:102px"></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table></form>	
		<?
	}

}
?>