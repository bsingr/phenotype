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
 * @subpackage system
 *
 */
class PhenotypePackageStandard
{
	public $bez;
	public $packagefolder = "0000_NoPackage";

	public $_content_data = Array();
	public $_media_data = Array();

	function __construct()
	{
		$this->directory = PACKAGEPATH. $this->packagefolder . "/";
	}

	function getDescription()
	{
		return ("");
	}

	function install()
	{
		$this->globalInstallStructure();
		$this->globalInstallData();
		$this->finishInstallation();
	}

	function globalInstallStructure($hostconfig=false)
	{
		set_time_limit(0);

		$this->installApplicationFiles($hostconfig);

		$this->installBackendClasses();
		//$this->installLanguageMaps();

		$this->configureMediabase();
		$this->configureComponents();
		$this->configureIncludes();
		$this->configurePages();
		$this->configureLayouts();
		$this->configureContent();

		$this->configureTicketSubjects();

		$this->configureExtras();
		$this->configureActions();
		$this->configureRoles();
		$this->installHtdocs();
		$this->installStorage();

	}

	function globalInstallData()
	{

		set_time_limit(0);

		echo "<br/>--installUsers<br/>";
		$this->installUsers();
		echo "<br/>--installMedia<br/>";
		$this->installMedia();
		echo "<br/>--installPages<br/>";
		$this->installPages();
		echo "<br/>--installContent<br/>";
		$this->installContent();
		echo "<br/>--installTickets<br/>";
		$this->installTickets();
	}

	function finishInstallation()
	{
	}

	function configureMediabase()
	{
		echo "<br/>--configureMediabase<br/>";
		$directory = $this->directory ."config/mediabase/";


		$file = $directory . "mediagroups.xml";
		if (file_exists($file))
		{

			$buffer =@file_get_contents($file);




			if ($buffer)
			{
				$_xml = @simplexml_load_string($buffer);

				if ($_xml)
				{
					$myMB = new PhenotypeMediabase();
					$myMB->rawXMLImport($buffer);
					echo "Building media groups.<br/>";
				}
				else
				{
					echo "XML processing error while parsing ".$file . "<br/>";
				}
			}
			else
			{
				echo "I/O error reading ".$file . "<br/>";
			}
		}
	}

	function configureComponents()
	{
		echo "<br/>--configureComponents<br/>";
		$directory = $this->directory ."config/components/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$buffer =@file_get_contents($directory.$file);
					if ($buffer)
					{
						$_xml = @simplexml_load_string($buffer);
						if ($_xml)
						{
							$myCom = new PhenotypeComponent();
							$com_id = $myCom->rawXMLImport($buffer);
							if ($com_id)
							{
								echo "Component ". $com_id. " created (".$file.") <br/>";
							}
							else
							{
								echo "Import failure while processing " .$file . "<br/>";
							}
						}
						else
						{
							echo "XML processing error while parsing ".$file . "<br/>";
						}
					}
					else
					{
						echo "I/O error reading ".$file . "<br/>";
					}
				}
			}
		}

	}

	function configureIncludes()
	{
		echo "<br/>--configureIncludes<br/>";
		$directory = $this->directory ."config/includes/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$buffer =@file_get_contents($directory.$file);
					if ($buffer)
					{
						$_xml = @simplexml_load_string($buffer);
						if ($_xml)
						{
							$myInc = new PhenotypeInclude();
							$inc_id = $myInc->rawXMLImport($buffer);
							if ($inc_id)
							{
								echo "Include ". $inc_id. " created (".$file.") <br/>";
							}
							else
							{
								echo "Import failure while processing " .$file . "<br/>";
							}
						}
						else
						{
							echo "XML processing error while parsing ".$file . "<br/>";
						}
					}
					else
					{
						echo "I/O error reading ".$file . "<br/>";
					}
				}
			}
		}

	}

	function configurePages()
	{
		echo "<br/>--configurePages<br/>";
		$directory = $this->directory ."config/page/";

		$file = $directory . "pagegroups.xml";
		if (file_exists($file))
		{
			$buffer =@file_get_contents($file);
			if ($buffer)
			{
				$_xml = @simplexml_load_string($buffer);
				if ($_xml)
				{
					$myPage = new PhenotypePage();
					$myPage->rawXMLPagegroupImport($buffer);
					echo "Building page groups.<br/>";
				}
				else
				{
					echo "XML processing error while parsing ".$file . "<br/>";
				}
			}
			else
			{
				echo "I/O error reading ".$file . "<br/>";
			}
		}

	}


	function configureLayouts()
	{
		echo "<br/>--configurePages<br/>";
		$directory = $this->directory ."config/page/";

		$file = $directory . "layouts.xml";
		if (file_exists($file))
		{
			$buffer =@file_get_contents($file);
			if ($buffer)
			{
				$_xml = @simplexml_load_string($buffer);
				if ($_xml)
				{
					$myPage = new PhenotypePage();
					$myPage->rawXMLLayoutimport($buffer);
					echo "Building layouts.<br/>";
				}
				else
				{
					echo "XML processing error while parsing ".$file . "<br/>";
				}
			}
			else
			{
				echo "I/O error reading ".$file . "<br/>";
			}
		}
	}

	function configureContent()
	{
		echo "<br/>--configureContent<br/>";
		$directory = $this->directory ."config/content/";
		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$buffer =@file_get_contents($directory.$file);
					if ($buffer)
					{
						$_xml = @simplexml_load_string($buffer);
						if ($_xml)
						{
							$myCO = new PhenotypeContent();
							$con_id = $myCO->rawXMLImport($buffer);
							if ($con_id)
							{
								echo "Content class ". $con_id. " created (".$file.") <br/>";
							}
							else
							{
								echo "Import failure while processing " .$file . "<br/>";
							}
						}
						else
						{
							echo "XML processing error while parsing ".$file . "<br/>";
						}
					}
					else
					{
						echo "I/O error reading ".$file . "<br/>";
					}
				}
			}
		}

	}


	function configureTicketSubjects()
	{
		echo "<br/>--configureTicketSubjects<br/>";
		$directory = $this->directory ."config/ticketsubjects/";

		$file = $directory . "ticketsubjects.xml";
		if (file_exists($file))
		{
			$buffer =@file_get_contents($file);
			if ($buffer)
			{
				$_xml = @simplexml_load_string($buffer);
				if ($_xml)
				{
					$myTicket = new PhenotypeTicket();
					$myTicket->rawXMLSubjectsImport($buffer);
					echo "Building task realms.<br/>";
				}
				else
				{
					echo "XML processing error while parsing ".$file . "<br/>";
				}
			}
			else
			{
				echo "I/O error reading ".$file . "<br/>";
			}
		}
	}

	function configureExtras()
	{
		echo "<br/>--configureExtras<br/>";
		$directory = $this->directory ."config/extras/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$buffer =@file_get_contents($directory.$file);
					if ($buffer)
					{
						$_xml = @simplexml_load_string($buffer);
						if ($_xml)
						{
							$myExt = new PhenotypeExtra();
							$ext_id = $myExt->rawXMLImport($buffer);
							if ($ext_id)
							{
								echo "Extra ". $ext_id. " created (".$file.") <br/>";
							}
							else
							{
								echo "Import failure while processing " .$file . "<br/>";
							}
						}
						else
						{
							echo "XML processing error while parsing ".$file . "<br/>";
						}
					}
					else
					{
						echo "I/O error reading ".$file . "<br/>";
					}
				}
			}
		}

	}

	function configureActions()
	{
		echo "<br/>--configureActions<br/>";
		$directory = $this->directory ."config/actions/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$buffer =@file_get_contents($directory.$file);
					if ($buffer)
					{
						$_xml = @simplexml_load_string($buffer);
						if ($_xml)
						{
							$myAction = new PhenotypeAction();
							$act_id = $myAction->rawXMLImport($buffer);
							if ($act_id)
							{
								echo "Action ". $act_id. " created (".$file.") <br/>";
							}
							else
							{
								echo "Import failure while processing " .$file . "<br/>";
							}
						}
						else
						{
							echo "XML processing error while parsing ".$file . "<br/>";
						}
					}
					else
					{
						echo "I/O error reading ".$file . "<br/>";
					}
				}
			}
		}

	}


	function configureRoles()
	{

		echo "<br/>--configureRoles<br/>";
		$directory = $this->directory ."config/roles/";

		$file = $directory . "roles.xml";
		if (file_exists($file))
		{
			$buffer =@file_get_contents($file);
			if ($buffer)
			{
				$_xml = @simplexml_load_string($buffer);
				if ($_xml)
				{
					$myUser = new PhenotypeUser();
					$myUser->rawXMLRolesImport($buffer);
					echo "Building roles.<br/>";
				}
				else
				{
					echo "XML processing error while parsing ".$file . "<br/>";
				}
			}
			else
			{
				echo "I/O error reading ".$file . "<br/>";
			}
		}

	}


	function ajaxInstall($type,$filenr,$count)
	{
		global $myRequest;
		global $myPT;


		set_time_limit(0);

		switch ($type)
		{
			case "media":
				$directory = $this->directory ."data/media/";
				$log = TEMPPATH ."install/media.log";
				$logstart = "-- install Media<br/><br/>";
				break;
			case "users":
				$directory = $this->directory ."data/users/";
				$log = TEMPPATH ."install/users.log";
				$logstart = "--installUsers<br/><br/>";
				break;
			case "pages":
				$directory = $this->directory ."data/pages/";
				$log = TEMPPATH ."install/pages.log";
				$logstart = "--installPages<br/><br/>";
				break;
			case "content":
				$directory = $this->directory ."data/content/";
				$log = TEMPPATH ."install/content.log";
				$logstart = "--installContent<br/><br/>";
				break;
			case "tickets":
				$directory = $this->directory ."data/tickets/";
				$log = TEMPPATH ."install/tickets.log";
				$logstart = "--installTickets<br/><br/>";
				break;
		}

		if ($filenr==0)
		{
			// Zunächst bestimmen, wieviele Dateien im Ordner sind

			if ($type=="pages") // what the hell is this IF for? the 2 blocks below are identical, aren't they?
			{
				$fp = @opendir($directory);
				if ($fp)
				{
					while (false !== ($file = readdir($fp)))
					{
						if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
						{
							$count++;
						}
					}
				}
			}
			else
			{
				$fp = @opendir($directory);
				if ($fp)
				{
					while (false !== ($file = readdir($fp)))
					{
						if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
						{
							$count++;
						}
					}
				}
			}
			$myPT->writefile($log,$logstart);
			?>
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			<title>Phenotype <?php echo $myPT->version ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<link href="phenotype.css" rel="stylesheet" type="text/css">
			<link href="navigation.css" rel="stylesheet" type="text/css">
			<link href="task.css" rel="stylesheet" type="text/css">
			<style type="text/css">
			body {
				margin-top: 2px;
				margin-bottom: 2px;
			}
			</style>
			</head>
			<body onload="doit(1);">
			<script type="text/javascript" src="tw-sack.js"></script>
			<script type="text/javascript">
			var ajax = new sack();

			var filenr = 1; // Sonst geht's net ??
			var count = <?php echo $count ?>;
			var error=0;

			function whenCompleted(){
				error =0;
				if (ajax.responseStatus)
				{
					if (ajax.responseStatus[0]!=200)
					{
						error = 1;
					}
				}
				else
				{
					error = 1;
				}

				if (error==0)
				{
					filenr = filenr +1;
					if (filenr>count)
					{
						// Abgeschlossen - Evtl. Log nachladen
					}
					else
					{
						//alert(filenr);
						doit(filenr);
					}
				}
				else
				{
					alert ("AJAX Error, OK to retry (" +filenr+")");
					doit(filenr);
				}
			}


			function doit(nr){
				ajax.resetData();
				ajax.requestFile = "package_install2.php";
				ajax.method = "GET";
				ajax.element = 'statusbar';
				ajax.setVar("type","<?php echo $type ?>");
				ajax.setVar("filenr",nr);
				ajax.setVar("count",count);
				ajax.setVar("id","<?php echo $myRequest->get("id") ?>");
				//ajax.onLoading = whenLoading;
				//ajax.onLoaded = whenLoaded;
				//ajax.onInteractive = whenInteractive;
				ajax.onCompletion = whenCompleted;
				ajax.runAJAX();
			}
			</script>
			<div id="statusbar" style="width:400px;height:50px;font-size:10px"><?php echo $count ?> processing XML files</div>
			
			</body>
			</html>
			<?php
		}
		else
		{
			if ($count!=0)
			{

				$p = (int)($filenr/$count*100);
				$w = (int)($filenr/$count*450);
			?>
			<div style="width:450px;height:18px;border:1px solid black;float:left">
			<img src="img/progress.gif" width="<?php echo $w ?>" height="18">
			</div>
			<div>
			<?php
			echo $p.'% ('.$filenr.'/'.$count.')<br/><br clear="all"/></div>';


			$myPT->startBuffer();
			switch ($type)
			{
				case "media":
					$this->installMedia($filenr);
					break;
				case "users":
					$this->installUsers($filenr);
					break;
				case "pages":
					$this->installPages($filenr);
					break;
				case "content":
					$this->installContent($filenr);
					break;
				case "tickets":
					$this->installTickets($filenr);
					break;

			}
			//echo "<pre>";
			//print_r ($_REQUEST);
			//echo "</pre>";
			$text = $myPT->stopBuffer();
			$myPT->appendfile($log,$text);
			if ($filenr == $count)
			{
				echo file_get_contents($log);
			}
			else
			{
				echo $text;

			}
			// wait for 0.05 secondes
			usleep(50000);
			}
			else
			{
				echo "No XM Files found.";
			}
		}
	}


	function installUsers($filenr=0)
	{
		global $myDB;

		$j=0;

		$directory = $this->directory ."data/users/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$j++;
					if ($filenr ==0 OR $filenr==$j)
					{
						$buffer =@file_get_contents($directory.$file);
						if ($buffer)
						{
							$_xml = @simplexml_load_string($buffer);
							if ($_xml)
							{
								$myUser = new PhenotypeUser();

								$usr_id = $myUser->rawXMLImport($buffer);
								if ($usr_id)
								{
									echo "User ". $usr_id. " installed (".$file.") <br/>";
								}
								else
								{
									echo "Import failure while processing " .$file . "<br/>";
								}

							}
							else
							{
								echo "XML processing error while parsing  ".$file . "<br/>";
							}
						}
						else
						{
							echo "I/O error reading ".$file . "<br/>";
						}
					}
				}
			}
		}
	}

	function installMedia($filenr=0)
	{
		global $myDB;


		$j=0;
		$directory = $this->directory ."data/media/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$j++;
					if ($filenr ==0 OR $filenr==$j)
					{
						$buffer="";
						$buffer =@file_get_contents($directory.$file);
						if ($buffer)
						{
							$_xml = @simplexml_load_string($buffer);
							if ($_xml)
							{

								$med_id_local = (int)utf8_decode($_xml->meta->med_id_local);
								$myMO = new PhenotypeMediaObject();

								$med_id = $myMO->rawXMLImport($buffer);
								if ($med_id)
								{
									echo "Media object ". $med_id. " installed (".$file." with local ID ".$med_id_local.") <br/>";
									$this->_media_data[$med_id_local]=$med_id;
								}
								else
								{
									echo "Import failure while processing " .$file . "<br/>";
								}

							}
							else
							{
								echo "XML processing error while parsing ".$file . "<br/>";
							}
						}
						else
						{
							echo "I/O error reading ".$file . "<br/>";
						}
					}
				}
			}
		}
	}




	function installContent($filenr=0)
	{
		global $myDB;

		$j=0;

		$_contentobjects = Array();
		$sql = "SELECT con_id FROM content";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$_contentobjects[] = $row["con_id"];
		}

		$directory = $this->directory ."data/content/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{

					$j++;
					if ($filenr ==0 OR $filenr==$j)
					{
						$buffer =@file_get_contents($directory.$file);
						if ($buffer)
						{
							$_xml = @simplexml_load_string($buffer);
							if ($_xml)
							{
								$con_id = (int)utf8_decode($_xml->meta->con_id);
								if (in_array($con_id,$_contentobjects))
								{
									$dat_id_local = (int)utf8_decode($_xml->meta->dat_id_local);
									$fname = "PhenotypeContent_".$con_id;
									$myCO = new $fname();
									$dat_id = $myCO->rawXMLDataImport($buffer);
									if ($dat_id)
									{
										echo "Content record ". $dat_id. " installed (".$file." with local ID ".$dat_id_local.") <br/>";
										$this->_content_data[$dat_id_local]=$dat_id;
									}
									else
									{
										echo "Import failure while processing " .$file . "<br/>";
									}
								}
								else
								{
									echo "Content type PhenotypeContent_". $con_id . " unknow. Skipping " .$file . "<br/>";
								}
							}
							else
							{
								echo "XML processing error while parsing ".$file . "<br/>";
							}
						}
						else
						{
							echo "I/O error reading ".$file . "<br/>";
						}
					}
				}
			}
		}
	}

	function installPages($filenr=0)
	{
		$j=0;

		global $myDB;

		$_pagegroups = Array();
		$sql = "SELECT grp_id FROM pagegroup";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$_pagegroups[] = $row["grp_id"];
		}

		$directory = $this->directory ."data/pages/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					$j++;
					if ($filenr ==0 OR $filenr==$j)
					{
						$buffer =@file_get_contents($directory.$file);
						if ($buffer)
						{
							$_xml = @simplexml_load_string($buffer);
							if ($_xml)
							{
								$grp_id = (int)utf8_decode($_xml->meta->grp_id);
								if (in_array($grp_id,$_pagegroups))
								{
									$pag_id_local = (int)utf8_decode($_xml->meta->pag_id_local);
									$myPage = new PhenotypePage();
									$pag_id = $myPage->rawXMLImport($buffer);
									if ($pag_id)
									{
										echo "Page ". $pag_id. " installed (".$file." with local ID ".$pag_id_local.") <br/>";
										$this->_pages[$pag_id_local]=$pag_id;
									}
									else
									{
										echo "Import failure while processing " .$file . "<br/>";
									}
								}
								else
								{
									echo "Page group ". $pag_id . " not available. Skipping " .$file . "<br/>";
								}
							}
							else
							{
								echo "XML processing error while parsing ".$file . "<br/>";
							}
						}
						else
						{
							echo "I/O error reading ".$file . "<br/>";
						}
					}
				}
			}

			// Am Ende ausgehend von Seite 0 die Properties verteilen

			$myPage = new PhenotypePage();
			$_props = Array();
			$myPage->spreadProps(0,$_props);
		}
	}



	function installTickets($filenr=0)
	{
		global $myDB;

		$j=0;

		$_ticketsubjects = Array();
		$sql = "SELECT sbj_id FROM ticketsubject";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$_ticketsubjects[] = $row["sbj_id"];
		}

		$directory = $this->directory ."data/tickets/";

		$myTicket = new PhenotypeTicket();
		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{

					$j++;
					if ($filenr ==0 OR $filenr==$j)
					{
						$buffer =@file_get_contents($directory.$file);

						if ($buffer)
						{
							$_xml = simplexml_load_string($buffer);
							if ($_xml)
							{
								$sbj_id = (int)utf8_decode($_xml->content->sbj_id);
								if (in_array($sbj_id,$_ticketsubjects))
								{

									$tik_id = $myTicket->rawXMLImport($buffer);
									if ($tik_id)
									{
										echo "Ticket ". $tik_id. " installed (".$file.") <br/>";

									}
									else
									{
										echo "Import failure while processing " .$file . "<br/>";
									}
								}
								else
								{
									echo "Task realm ". $sbj_id . " unknown. Skipping " .$file . "<br/>";
								}
							}
							else
							{
								echo "XML processing error while parsing ".$file . "<br/>";
							}
						}
						else
						{
							echo "I/O error reading ".$file . "<br/>";
						}
					}
				}
			}
		}
	}


	function installHtdocs()
	{
		global $myDB;
		global $myAdm;
		echo "<br/>--installHtdocs<br/>";

		$directory = $this->directory ."htdocs/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ($file != "." && $file != ".." && $file != ".svn" && $file != "CVS")
				{
					if (is_dir($directory . $file))
					{
						echo "Coyp folder " . $file . "<br/>";
						$myAdm->copyDirComplete($directory . $file,SERVERPATH.$file);
					}
					else
					{
						echo "Copy file " . $file . "<br/>";
						copy ($directory . $file,SERVERPATH . $file);
						chmod (SERVERPATH . $file,UMASK);
					}
				}
			}
		}
	}

	function installStorage()
	{
		global $myDB;
		global $myAdm;
		echo "<br/>--installStorage<br/>";

		$directory = $this->directory ."storage/";

		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ($file != "." && $file != ".." && $file != ".svn" && $file != "CVS")
				{
					if (is_dir($directory . $file))
					{
						echo "Copy Folder " . $file . "<br/>";
						$myAdm->copyDirComplete($directory . $file, APPPATH."storage/".$file);
					}
					else
					{
						echo "Copy file " . $file . "<br/>";
						copy ($directory . $file,APPPATH."storage/". $file);
						chmod (APPPATH."storage/". $file,UMASK);
					}
				}
			}
		}
	}	

	function installApplicationFiles($hostconfig)
	{
		echo "<br/>--installApplicationFiles<br/>";

		
		$directory = $this->directory;

		$file = "_application.inc.php";

		if (file_exists($directory . $file))
		{
			echo "Copy file " . $file . "<br/>";
			copy ($directory . $file,APPPATH . $file);
			chmod (APPPATH . $file,UMASK);
		}

		$file = "preferences.xml";

		if (file_exists($directory . $file))
		{
			echo "Copy file " . $file . "<br/>";
			copy ($directory . $file,APPPATH . $file);
			chmod (APPPATH . $file,UMASK);
		}
		
		$file = "_host.config.inc.php";

		if (file_exists($directory . $file) AND $hostconfig==1)
		{
			echo "Copy file " . $file . "<br/>";
			copy ($directory . $file,APPPATH . $file);
			chmod (APPPATH . $file,UMASK);
		}		
	}

	function installBackendClasses()
	{
		echo "<br/>--installBackendClasses<br/>";

		$directory = $this->directory . "config/backend/";
		$fp = @opendir($directory);

		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					echo "Copy backend class " . $file . "<br/>";
					copy ($directory . $file,APPPATH . "backend/". $file);
					chmod (APPPATH . "backend/". $file,UMASK);
				}
			}
		}
	}

	/*
	function installLanguageMaps()
	{
		echo "<br/>--installLanguageMaps<br/>";

		$directory = $this->directory . "config/languagemaps/";
		$fp = @opendir($directory);

		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ( ($file{0} != ".") && ($file != "CVS") ) // ignore hidden files and CVS
				{
					echo "Kopiere Languagemap " . $file . "<br/>";
					copy ($directory . $file,APPPATH . "languagemaps/". $file);
					chmod (APPPATH . "languagemaps/". $file,UMASK);
				}
			}
		}
	}
	*/
}
?>
