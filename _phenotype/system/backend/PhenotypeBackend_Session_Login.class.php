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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
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
			$sql = "SELECT * FROM user WHERE usr_login = '" . $myRequest->getSQL("user") ."' AND usr_status=1";
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

			$myLog->log("User ".$myUser->id . " - " . $myUser->getName() ." successfully logged in",PT_LOGFACILITY_SYS);

			// set debug cookie to allow displayment of debug console
			$p = strpos(".",$domain);
			if ($p)
			{
				$p = strpos($_SERVER[HTTP_HOST],".");
				$domain = substr($_SERVER[HTTP_HOST],$p);
			}
			else // if we don't have a dot in our domain name, we're probably in an local environment and don't set a domain cookie
			{
				$domain="";
			}
			setcookie("pt_debug",md5("on".PT_SECRETKEY),time()+(60*60*24*3),"/",$domain);
			if ($myRequest->check("uri"))
			{
				Header ("Location: ".$myRequest->get("uri"));
				exit();
			}
			$this->gotoPage("Editor","Start","");
		}
		$myLog->log("Login failed - ".$myRequest->get("user"),PT_LOGFACILITY_SYS);

		$this->displayLoginRetry();

	}
}
?>
