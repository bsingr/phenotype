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
class PhenotypeBackend_Config_Packages_Standard extends PhenotypeBackend_Config
{
	function execute($scope,$action)
	{
		if ($action=="ajaxexport")
		{
			$this->ajaxexport();
		}
	}
	
	function ajaxexport()
	{
		global $myRequest;
		global $myPT;
		global $myDB;
		
		$type  = $myRequest->get("type");
		$count = $myRequest->getI("count");
		$filenr = $myRequest->getI("filenr");
		
		$dir = TEMPPATH ."package/";
		
		set_time_limit(0);

		switch ($type)
		{
			case "pages":
				$log = TEMPPATH ."install/pages.log";
				$logstart = "-- export Pages<br/><br/>";
				$grp_id = $myRequest->getI("grp_id");
				$forceBuild=false;
				if ($filenr==0){$forceBuild=true;}
				$myDO = new PhenotypeSystemDataObject("PackageExportHelper",array("type"=>"pages","grp_id"=>$grp_id),$forceBuild,true);
				//"system.export_pages_grp_id_".$grp_id,$forceBuild);
				$_ids = $myDO->get("objects");
				$count = count($_ids);
			break;			
			case "content":
				$log = TEMPPATH ."install/econtent.log";
				$logstart = "-- export Content<br/><br/>";
				$con_id = $myRequest->getI("con_id");
				$forceBuild=false;
				if ($filenr==0){$forceBuild=true;}
				$myDO = new PhenotypeSystemDataObject("PackageExportHelper",array("type"=>"content","con_id"=>$con_id),$forceBuild,true);
				//$myDO = new PhenotypeDataObject("system.export_content_con_id_".$con_id,$forceBuild);
				$_ids = $myDO->get("objects");
				$count = count($_ids);
			break;
			
			case "media":
				$log = TEMPPATH ."install/emedia.log";
				$logstart = "-- export Media<br/><br/>";
				$grp_id = $myRequest->getI("grp_id");
				$forceBuild=false;
				if ($filenr==0){$forceBuild=true;}
				$myDO = new PhenotypeSystemDataObject("PackageExportHelper",array("type"=>"media","grp_id"=>$grp_id),$forceBuild,true);
				//$myDO = new PhenotypeDataObject("system.export_media_grp_id_".$grp_id,$forceBuild);
				$_ids = $myDO->get("objects");
				$count = count($_ids);
			break;	
			case "tickets":
				$log = TEMPPATH ."install/etickets.log";
				$logstart = "-- export Tickets<br/><br/>";
				$sbj_id = $myRequest->getI("sbj_id");
				$forceBuild=false;
				if ($filenr==0){$forceBuild=true;}
				//$myDO = new PhenotypeDataObject("system.export_tickets_sbj_id_".$sbj_id,$forceBuild);
				$myDO = new PhenotypeSystemDataObject("PackageExportHelper",array("type"=>"tickets","sbj_id"=>$sbj_id),$forceBuild,true);
				$_ids = $myDO->get("objects");
				$count = count($_ids);
			break;			
			case "package":
				$this->copyPackage();
				return;
				break;		
			
			default:
				return;
			break;
				
		}
		
		if ($filenr==0)
		{
			$myPT->writefile($log,$logstart);
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			<title>phenotype <?php echo $myPT->version ?></title>
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

			var filenr = 1; 
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
						top.ajaxfeedback(<?php echo $myRequest->getI("fcounter") ?>);
					}
					else
					{
						//alert(filenr);
						doit(filenr);
					}
				}
				else
				{
					alert ("AJAX-Fehler, OK für Wiederholung (" +filenr+")");
					doit(filenr);
				}
			}


			function doit(nr){
				ajax.resetData();
				ajax.requestFile = "backend.php";
				ajax.method = "GET";
				ajax.element = 'statusbar';
				ajax.setVar("page","Config,Packages,ajaxexport");
				ajax.setVar("type","<?php echo $type ?>");
				ajax.setVar("filenr",nr);
				ajax.setVar("count",count);
				ajax.setVar("con_id","<?php echo $myRequest->getI("con_id") ?>");
				ajax.setVar("grp_id","<?php echo $myRequest->getI("grp_id") ?>");
				ajax.setVar("sbj_id","<?php echo $myRequest->getI("sbj_id") ?>");
				ajax.setVar("fcounter","<?php echo $myRequest->getI("fcounter") ?>");
				//ajax.onLoading = whenLoading;
				//ajax.onLoaded = whenLoaded;
				//ajax.onInteractive = whenInteractive;
				ajax.onCompletion = whenCompleted;
				ajax.runAJAX();
			}
			</script>
			<div id="statusbar" style="width:400px;height:50px;font-size:10px"><?php echo $count ?> Datensätze ermittelt.</div>
				
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
				case "pages":
					$pag_id = $_ids[$filenr-1];
					$myPage = new PhenotypePage($pag_id);
	    			$xml = $myPage->rawXMLExport();
	
	    			$file = $dir ."data/pages/page_" . sprintf("%06d",$pag_id) . ".xml";
	
	    			$myPT->writefile($file,$xml);
	    			echo "generating " . $file . "<br/>";
	
					break;
				case "media":
					$med_id = $_ids[$filenr-1];
					$sql = "SELECT med_id,med_type FROM media WHERE med_id = " . $med_id;
	    			$rs= $myDB->query ($sql);
	    			$row = mysql_fetch_array($rs);
	    	
	    			if ($row["med_type"]==MB_IMAGE)
	    			{
	    				$myMO = new PhenotypeImage($med_id);
	    			}
	    			else
	    			{
	    				$myMO = new PhenotypeDocument($med_id);
	    			}
	    			$xml = $myMO->rawXMLExport($importmethod);
	
	    			$file = $dir ."data/media/media_" . sprintf("%06d",$med_id) . "_".$importmethod.".xml";
	    			if ($importmethod=="overwrite")
	    			{
	    				$file = $dir ."data/media/media__" . sprintf("%06d",$med_id) . "_".$importmethod.".xml";
	    			}
	    			$myPT->writefile($file,$xml);
	    			echo "generating " . $file . "<br/>";
					break;
				case "content":
					$fname = "PhenotypeContent_".$con_id;
    				$myCO = new $fname;
    				$dat_id = $_ids[$filenr-1];
    				$myCO->load($dat_id);
    				$xml = $myCO->rawXMLDataExport($importmethod);

    				$file = $dir ."data/content/data_" . sprintf("%06d",$dat_id) . "_".$importmethod.".xml";
    				if ($importmethod=="overwrite")
    				{
    					$file = $dir ."data/content/data__" . sprintf("%06d",$dat_id) . "_".$importmethod.".xml";
    				}
    				$_xml = @simplexml_load_string($xml);
					if ($_xml)
					{
    					$myPT->writefile($file,$xml);
    					echo "generating " . $file . "<br/>";
					}
					else 
					{
						echo '<font color="red">skipping ' . $file . ' XML-Fehler</font><br/>';
						if (PT_DEBUG==1)
						{
							$file = "data/content/_debug_data_" . sprintf("%06d",$dat_id) . "_".$importmethod.".xml";
							$myPT->writefile($file,$xml);
						}
					}
    				
					break;
				case "tickets":
					$tik_id = $_ids[$filenr-1];
					$myTicket = new PhenotypeTicket($tik_id);
	    			$xml = $myTicket->rawXMLExport($importmethod);
	
	    			$file = $dir ."data/tickets/ticket_" . sprintf("%06d",$tik_id) . "_".$importmethod.".xml";
	    			if ($importmethod=="overwrite")
	    			{
	    				$file = $dir ."data/tickets/ticket__" . sprintf("%06d",$tik_id) . "_".$importmethod.".xml";
	    			}
	    			$myPT->writefile($file,$xml);
	    			echo "generating " . $file . "<br/>";
					break;

			}
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
				echo "Keine Daten zum Exportieren vorhanden.";
			}
		}
	}
	
	function copyPackage()
	{
		global $myRequest;
		global $myPT;
		global $myAdm;
		
		$dir = TEMPPATH ."package/";
		
		$targetfolder = $myRequest->get("folder");
		
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<title>phenotype <?php echo $myPT->version ?></title>
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
		<body>
		<?php
		 
		if ($targetfolder=="")
	    {
	    	echo '<font color="red">Kein Zielordner angegeben. Paket nicht kopiert..</font><br/>';
	    }
	    else
	    {
	    	$targetfolder = PACKAGEPATH . $targetfolder."/";
	    	if (file_exists($targetfolder))
	    	{
	    		echo '<font color="red">Zielordner existiert bereits. Paket nicht kopiert.</font><br/>';
	    	}
	    	else
	    	{
	    		mkdir ($targetfolder);
	    		chmod ($targetfolder,UMASK);
	
	
	
	    		$script = '<?php
	class PhenotypePackage extends PhenotypePackageStandard
	{
		public $bez = "'.$myRequest->getS("title").'";
		public $packagefolder = "'.$myRequest->getS("folder").'";
		
		function getDescription()
		{
			return ("'.$myRequest->getS("desc").'");
		}
	}
	?>';
	
	    		$dateiname = $dir ."PhenotypePackage.class.php";
	    		$fp = fopen ($dateiname,"w");
	    		fputs ($fp,$script);
	    		fclose ($fp);
	    		@chmod ($dateiname,UMASK);
	
	
	    		$fp = @opendir($dir);
	    		if ($fp)
	    		{
	    			while (false !== ($file = readdir($fp)))
	    			{
	    				if ($file != "." && $file != ".." && $file != ".svn")
	    				{
	    					if (is_dir($dir . $file))
	    					{
	    						echo "Kopiere Verzeichnis " . $file . "<br/>";
	    						$myAdm->copyDirComplete($dir . $file,$targetfolder.$file);
	    					}
	    					else
	    					{
	    						echo "Kopiere Datei " . $file . "<br/>";
	    						copy ($dir. $file,$targetfolder . $file);
	    						chmod ($targetfolder . $file,UMASK);
	    					}
	    				}
	    			}
	    		}
	
	    	}
	    }
	    ?>
	    </body>
	    </html>
	    <?php
	}
}
?>
	
	