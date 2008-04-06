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
class PhenotypeBackend_Admin_Standard extends PhenotypeBackend
{
	
  public $tmxfile = "Admin";
  
	function execute()
	{
		Header("Location: admin.php");
	}
	
	function renderExplorer($scope,$detail="")
	{
		global $myPT;
		global $mySUser;
		global $myDB;


		$myPT->startBuffer();

		$url = "admin.php";
		$this->tab_addEntry("Administration",$url,"b_konfig.gif");
		$this->tab_draw("Administration",$x=260,1);


		$myNav = new PhenotypeTree();
		$nav_id_users    = $myNav->addNode("Benutzer","backend.php?page=Admin,Users,view",0,"users");

		if ($this->checkRight("elm_admin"))
		{
			$nav_id_roles    = $myNav->addNode("Rollen","admin_roles.php",0,"Rollen");
			$nav_id_cache    = $myNav->addNode("Cache","admin_cache.php",0,"Cache");
			$nav_id_layout    = $myNav->addNode("Layout","layout.php",0,"Layout");
			$nav_id_pages    = $myNav->addNode("Seiten","admin_pages.php",0,"Seiten");
			$nav_id_groups   = $myNav->addNode("Seitengruppen","admin_groups.php",0,"Seitengruppen");
			$nav_id_content  = $myNav->addNode("Content","admin_content.php",0,"Content");
			$nav_id_media    = $myNav->addNode("Media","admin_media.php",0,"Media");
			$nav_id_mediagroups   = $myNav->addNode("Mediagruppen","admin_mediagroups.php",0,"Mediagruppen");
			$nav_id_subject   = $myNav->addNode("Aufgabenbereiche","admin_subject.php",0,"Aufgabenbereiche");
			$nav_id_action   = $myNav->addNode("Aktionen","admin_actions.php",0,"Aktionen");
		}

		switch ($scope)
		{
			case "Users":
				$sql = "SELECT * FROM user WHERE usr_status = 1 ORDER BY usr_nachname";

				// Im eingeschränkten Modus nur den angemeldeten Benutzer zeigen
				if (!$this->checkRight("elm_admin"))
				{
					$sql = "SELECT * FROM user WHERE usr_status = 1 AND usr_id = " . $mySUser->id;
				}
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["usr_vorname"] . " " . $row["usr_nachname"],"backend.php?page=Admin,Users,edit&id=".$row["usr_id"]."&b=0",$nav_id_users,$row["usr_id"]);

				}
				if ($detail!=0)
				{
					$this->displayTreeNavi($myNav,$detail);
				}
				else
				{
					$this->displayTreeNavi($myNav,$scope);
				}
				break;
			case "Rollen":
				$sql = "SELECT * FROM role ORDER BY rol_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["rol_bez"],"admin_role_edit.php?id=".$row["rol_id"]."&b=0",$nav_id_roles,$row["rol_id"]);

				}
				if ($myAdm->explorer_get("rol_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("rol_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;
			case "Layout":
				$sql = "SELECT * FROM layout ORDER BY lay_bez";
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["lay_bez"],"layout_edit.php?id=".$row["lay_id"]."&b=0",$nav_id_layout,$row["lay_id"]);

				}
				if ($myAdm->explorer_get("lay_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("lay_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			case "Seitengruppen":
				$sql = "SELECT * FROM pagegroup ORDER BY grp_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["grp_bez"],"admin_group_edit.php?id=".$row["grp_id"]."&b=0",$nav_id_groups,$row["grp_id"]);
				}
				if ($myAdm->explorer_get("grp_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("grp_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			case "Aufgabenbereiche":
				$sql = "SELECT * FROM ticketsubject ORDER BY sbj_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["sbj_bez"],"admin_subject_edit.php?id=".$row["sbj_id"]."&b=0",$nav_id_subject,$row["sbj_id"]);
				}
				if ($myAdm->explorer_get("sbj_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("sbj_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;


			case "Aktionen":
				$sql = "SELECT * FROM action ORDER BY act_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["act_bez"],"admin_action_edit.php?id=".$row["act_id"]."&b=0",$nav_id_action,$row["act_id"]);
				}
				if ($myAdm->explorer_get("act_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("act_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			default:
				$this->displayTreeNavi($myNav,$submodul);
				break;
		}
	?>
	<table width="260" border="0" cellpadding="0" cellspacing="0">
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>
	<?php
	return ($myPT->stopBuffer());
	}


	
}
?>