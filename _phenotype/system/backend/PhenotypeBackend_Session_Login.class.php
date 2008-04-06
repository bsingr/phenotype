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
class PhenotypeBackend_Session_Login_Standard extends PhenotypeBackend_Session
{
    // PhenotypeBackend_Session-Classes don't have their own localization file. It's because some session/login/rights related functions
  // are located in the PhenotypeBackendStandard class.
  
  public $tmxfile = "Phenotype";
  
	function execute()
	{
		global $myDB;
		global $myRequest;
		global $myPT;
		global $myLog;
		

		@session_start();
		$login = false;
		if ($_REQUEST['user']!="")
		{
			$sql = "SELECT * FROM user WHERE usr_login = LCASE('" . strtolower($myRequest->getSQL("user")) ."') AND usr_status=1";
			$rs = $myDB->query($sql);
			if ($row=mysql_fetch_array($rs))
			{
				if ( $row["usr_pass"] ==  crypt(strtolower($myRequest->get("pass")),"phenotype"))
				{
					$login = true;
				}
			}
		}


		if ($login == true)
		{

			$_SESSION["usr_id"] = $row["usr_id"];
			$_SESSION["usr_id_fallback"] = $row["usr_id"];
			$myUser = new PhenotypeUser();
			$myUser->init($row);
			$_SESSION["status"]=1;
			$_SESSION["grp_id"]= (int)$myPT->getPref("backend.grp_id_pagegroup_start");
			$_SESSION["pag_id"]="";
			$_SESSION["lng_id"]=1;
			$mySQL = new SQLBuilder();
			$mySQL->addField("usr_lastlogin",time());
			$sql = $mySQL->update("user","usr_id=".$row["usr_id"]);
			$myDB->query($sql);

			// Ueberfaellige Tickets neu kalkulieren

			$myTicket = new PhenotypeTicket();
			$datum = mktime (0,0,0);
			$sql  ="SELECT * FROM ticket WHERE tik_status = 1 AND tik_lastaction <" . $datum;
			$rs = $myDB->query($sql);
			while ($row=mysql_fetch_array($rs))
			{
				$myTicket->init($row);
				$myTicket->calculate_prio();
			}

			$myLog->log("Benutzer ".$myUser->id . " - " . $myUser->getName() ." erfolgreich angemeldet",PT_LOGFACILITY_SYS);
			$this->gotoPage("Editor","Start","");
		}
		$myLog->log("Anmeldung fehlgeschlagen - ".$myRequest->get("user"),PT_LOGFACILITY_SYS);
		
		$this->displayLoginRetry();

	}
}
?>