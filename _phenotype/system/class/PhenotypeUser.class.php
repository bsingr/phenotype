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
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeUserStandard
{
	var $id =0;
	var $rights = Array();
	var $prefs = Array();
	var $name = "N.N.";
	var $email = "";

	function __construct($usr_id=-1)
	{
		$usr_id = (int)$usr_id;
		if ($usr_id <> -1)
		{
			$this->load($usr_id);
		}
	}

	function load ($usr_id)
	{
		$usr_id = (int)$usr_id;

		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$this->id = $row["usr_id"];
		$rechte = Array();
		if ($row["usr_allrights"]!=""){$rechte = unserialize($row["usr_allrights"]);}
		$this->rights = $rechte;
		if ($row["usr_preferences"]!=""){$prefs = unserialize($row["usr_preferences"]);}
		if (!is_array($prefs)){$prefs=Array();}
		
		$this->prefs = $prefs;
		$this->name = $row["usr_vorname"] . " ".$row["usr_nachname"];
		$this->firstname = $row["usr_vorname"];
		$this->lastname = $row["usr_nachname"];
		if ($row["usr_su"]==1){$this->rights["superuser"]=1;}
		$this->email = $row["usr_email"];
	}


	function init ($row)
	{
		$this->id = $row["usr_id"];
		$rechte = Array();
		if ($row["usr_allrights"]!=""){$rechte = unserialize($row["usr_allrights"]);}
		$this->rights = $rechte;
		if ($row["usr_preferences"]!=""){$prefs = unserialize($row["usr_preferences"]);}
		$this->prefs = $prefs;
		$this->name = $row["usr_vorname"] . " ".$row["usr_nachname"];
		$this->firstname = $row["usr_vorname"];
		$this->lastname = $row["usr_nachname"];
		if ($row["usr_su"]==1){$this->rights["superuser"]=1;}
		$this->email = $row["usr_email"];
	}

	function getName($usr_id = - 1)
	{
		$usr_id = (int)$usr_id;
		if ($usr_id == -1)
		{
			$usr_id = $this->id;
		}
		if ($usr_id==0)
		{
			return ("N.N.");
			exit();
		}
		if ($usr_id == $this->id)
		{
			return $this->name;
			exit();
		}
		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		return $row["usr_vorname"] . " ".$row["usr_nachname"];
	}
	
	function getFirstName($usr_id = - 1)
	{
		$usr_id = (int)$usr_id;
		if ($usr_id == -1)
		{
			$usr_id = $this->id;
		}
		if ($usr_id==0)
		{
			return ("N.N.");
			exit();
		}
		if ($usr_id == $this->id)
		{
			return $this->firstname;
			exit();
		}
		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		return $row["usr_vorname"];
	}	
	
	function getLastName($usr_id = - 1)
	{
		$usr_id = (int)$usr_id;
		if ($usr_id == -1)
		{
			$usr_id = $this->id;
		}
		if ($usr_id==0)
		{
			return ("N.N.");
			exit();
		}
		if ($usr_id == $this->id)
		{
			return $this->lastname;
			exit();
		}
		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		return $row["usr_nachname"];
	}	

	function getRights($usr_id = -1)
	{
		$usr_id = (int)$usr_id;
		if ($usr_id == -1)
		{
			$usr_id = $this->id;
		}

		$rechte = Array();
		if ($usr_id==0)
		{
			return $rechte;
			exit();
		}
		if ($usr_id == $this->id)
		{
			return $this->rights;
			exit();
		}
		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id = " . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if ($row["usr_allrights"]!=""){$rechte = unserialize($row["usr_allrights"]);}
		return $rechte;
	}

	function buildRights($usr_id=-1)
	{
		// Achtung! Beim Ausbilden der Rechte ist die Tabelle user_ticketsubject
		// nur eine Index-Tabelle, deren aktuelle Inhalte sind bedeutungslos !
		
		$usr_id = (int)$usr_id;
		if ($usr_id == -1)
		{
			$usr_id = $this->id;
		}
		if ($usr_id==0)
		{
			return false;
			echo "Kein User angegeben";
			exit();
		}

		global $myDB;
		$sql = "SELECT * FROM user WHERE usr_id=" . $usr_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$_rechte = unserialize($row["usr_rights"]);

		if (!is_array($_rechte))
		{
			$_rechte = Array();
		}

		$_allerechte = $_rechte;

		// Rollen
		$sql = "SELECT * FROM role";
		$rs = $myDB->query($sql);
		while ($row_role = mysql_fetch_array($rs))
		{
			if (array_key_exists("rol_".$row_role["rol_id"],$_allerechte))//Hat der User die Rolle
			{
				$_rechte["rol_" . $row_role["rol_id"]]=1;
				$_rollenrechte = Array();
				if ($row_role["rol_rights"]!=""){$_rollenrechte = unserialize($row_role["rol_rights"]);}
				foreach ($_rollenrechte AS $key => $val)
				{
					if (!array_key_exists($key,$_allerechte))
					{
						$_allerechte[$key]=$val;
					}
					// Überschreiben von allen Userrechten, die in der Rolle auf true stehen
					// Ignorieren der Start-IDs von Seitenbereichen
					if ($_allerechte[$key]==0 AND $val !=0 AND !strstr($key,"pag_id_grp"))
					{
						$_allerechte[$key]=$val;
					}
				}
			}
		}
		$mySQL=new SQLBuilder();
		$mySQL->addField("usr_allrights",serialize($_allerechte));
		$sql = $mySQL->update("user","usr_id=".$usr_id);
		$myDB->query($sql);
		if ($usr_id==$this->id){$this->rights=$_allerechte;}


		$sql = "DELETE FROM user_ticketsubject WHERE usr_id = " . $usr_id;
		$myDB->query($sql);
		$sql = "SELECT * FROM ticketsubject ORDER by sbj_bez";
		$rs = $myDB->query($sql);
		while ($row_subject = mysql_fetch_array($rs))
		{
			if (isset($_allerechte["sbj_" . $row_subject["sbj_id"]]))
			{
				if ($_allerechte["sbj_" . $row_subject["sbj_id"]] ==1)
				{
					// Recht in die Datenbank eintragen
					$mySQL = new SQLBuilder();
					$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
					$mySQL->addField("sbj_id",$row_subject["sbj_id"],DB_NUMBER);
					$sql = $mySQL->insert("user_ticketsubject");
					$myDB->query($sql);
				}
			}
		}


	}

	function checkRight($key)
	{
		if (array_key_exists($key,$this->rights))
		{
			return ($this->rights[$key]);
		}
		else
		{
			return false;
		}
	}

	function hasRight($key)
	{
		return $this->checkRight($key);
	}

	function hasRole($nr)
	{
		return $this->checkRight("rol_".$nr);
	}

	function getPref($key)
	{


		if (array_key_exists($key,$this->prefs))
		{
			return ($this->prefs[$key]);
		}
		else
		{
			return false;
		}
	}


	function rawXMLRolesExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<roles>';
		$sql = "SELECT * FROM role ORDER BY rol_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
		<role>
			<rol_id>'.$row["rol_id"].'</rol_id>
			<rol_bez>'.$myPT->codeX($row["rol_bez"]).'</rol_bez>
			<rol_description>'.$myPT->codeX($row["rol_desc"]).'</rol_description>
			<rol_rights>'.base64_encode($row["rol_rights"]).'</rol_rights>
		</role>';
		}
		$xml.='
	</roles>
</phenotype>';
		return $xml;
	}

	function rawXMLRolesImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			foreach ($_xml->roles->role AS $_xml_role)
			{
				$rol_id = (int)utf8_decode($_xml_role->rol_id);
				$rol_bez = (string)utf8_decode($_xml_role->rol_bez);
				$rol_description = (string)utf8_decode($_xml_role->rol_description);
				$rol_rights = (string)utf8_decode($_xml_role->rol_rights);

				$sql  ="DELETE FROM role WHERE rol_id=".$rol_id;
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("rol_id",$rol_id,DB_NUMBER);
				$mySQL->addField("rol_rights",base64_decode($rol_rights));
				$mySQL->addField("rol_bez",$rol_bez);
				$mySQL->addField("rol_description",$rol_description);
				$sql = $mySQL->insert("role");
				$myDB->query($sql);

			}
		}
	}

	function rawXMLExport()
	{
		global $myPT;
		global $myDB;

		$sql ="SELECT * FROM user WHERE usr_id=".$this->id;
		$rs =$myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
		<usr_id>'.$this->id.'</usr_id>
		<importmethod>overwrite</importmethod>		
	</meta>
	<content>
	';
		$_felder = Array("usr_vorname","usr_nachname","usr_login","usr_pass","usr_email","usr_createdate","usr_lastlogin","usr_su","usr_status","med_id_thumb");
		foreach ($_felder AS $k)
		{
			$xml.= '<'.$k.'>'.$myPT->codeX($row[$k]).'</'.$k.'>'."\n";
		}

		$xml .='<usr_rights>'.base64_encode($row["usr_rights"]).'</usr_rights>';
		$xml .='<usr_preferences>'.base64_encode($row["usr_preferences"]).'</usr_preferences>';

		$sql = "SELECT * FROM user_ticketsubject WHERE usr_id=".$this->id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$xml .='<ticketsubject sbj_id="'.$row["sbj_id"].'"/>';
		}


		$xml.='
	</content>
</phenotype>';
		return $xml;
	}


	function rawXMLImport($buffer)
	{
		global $myPT;
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			$usr_id = (int)utf8_decode($_xml->meta->usr_id);
			$sql  ="DELETE FROM user WHERE usr_id=".$usr_id;
			$myDB->query($sql);
			$sql  ="DELETE FROM user_ticketsubject WHERE usr_id=".$usr_id;
			$myDB->query($sql);

			$mySQL = new SqlBuilder();
			$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
			$_felder = Array("usr_vorname","usr_nachname","usr_login","usr_pass","usr_email","usr_createdate","usr_lastlogin","usr_su","usr_status","med_id_thumb");
			foreach ($_felder AS $k)
			{
				$mySQL->addField($k,(string)utf8_decode($_xml->content->$k));
			}

			$_usr_rights = unserialize(base64_decode((string)utf8_decode($_xml->content->usr_rights)));
			
			if (!is_array($_usr_rights)){$_usr_rights = Array();}
			foreach ($_xml->content->ticketsubject AS $_xml_ticketsubject)
			{
				$sbj_id= (int)utf8_decode($_xml_ticketsubject["sbj_id"]);
				$_usr_rights["sbj_".$sbj_id]=1;
			}
			
			$mySQL->addField("usr_rights",serialize($_usr_rights));
			$mySQL->addField("usr_preferences",base64_decode((string)utf8_decode($_xml->content->usr_preferences)));

			$sql = $mySQL->insert("user");
			$myDB->query($sql);


			
			$myUser = new PhenotypeUser();
			$myUser->buildRights($usr_id);

			return ($usr_id);
		}
	}
}
?>