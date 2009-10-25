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
?>
<?php
require("_config.inc.php");
require("_session.inc.php");
if (PT_CONFIGMODE!=1){exit();}
$myPT->loadTMX("Config");
?>
<?php
if (!$mySUser->checkRight("superuser"))
{
	$url = "noaccess.php";
	Header ("Location:" . $url."?".SID);
	exit();
}
$myPT->clearCache();
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header(locale("Config"));
?>
<body>
<?php
$myAdm->menu(locale("Config"));
?>
<?php
// -------------------------------------
// {$left}
// -------------------------------------
$myPT->startBuffer();
?>
<?php
$myAdm->explorer_prepare(locale("Config"),locale("Packages"));
$myAdm->explorer_set("packagemode","export");
$myAdm->explorer_draw();

$left = $myPT->stopBuffer();
?>
<?php
// -------------------------------------
// -- {$left}
// -------------------------------------
?>
<?php
// -------------------------------------
// {$content}
// -------------------------------------
$myPT->startBuffer();
?>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Export package");?></td>
            <td align="right" class="windowTitle"><!--<a href="http://www.phenotype-cms.de/docs.php?v=23&t=21" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>
	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3"></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      
    </table>
    <?php
    $myLayout->workarea_start_draw();

    $myAdm->cfg_rebuildTempPackageStructure();

    set_time_limit(0);

    $fcounter=0;

    $dir = TEMPPATH ."package/";

    if ($myRequest->check("pagegroups"))
    {
    	$file = $dir ."config/page/pagegroups.xml";
    	$myPage = new PhenotypePage();
    	$xml = $myPage->rawXMLPagegroupExport();

    	$myPT->writefile($file,$xml);
    	echo "generating " . $file . "<br/>";
    }

    if ($myRequest->check("layouts"))
    {
    	$file = $dir ."config/page/layouts.xml";
    	$myPage = new PhenotypePage();
    	$xml = $myPage->rawXMLLayoutExport();

    	$myPT->writefile($file,$xml);
    	echo "generating " . $file . "<br/>";
    }

    $sql = "SELECT * FROM component ORDER BY com_rubrik, com_bez, com_id";
    $rs = $myDB->query ($sql);
    $html="";
    $myCom = new PhenotypeComponent();
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["com_id"];
    	if ($myRequest->check("com_id".$id))
    	{
    		$file = $dir ."config/components/component_".sprintf("%06d",$id).".xml";

    		$xml = $myCom->rawXMLExport($id);

    		$myPT->writefile($file,$xml);
    		echo "generating " . $file . "<br/>";
    	}
    }

    $sql = "SELECT * FROM include ORDER BY inc_rubrik, inc_bez, inc_id";
    $rs = $myDB->query ($sql);
    $html="";
    $myInc = new PhenotypeInclude();
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["inc_id"];
    	if ($myRequest->check("inc_id".$id))
    	{
    		$file = $dir ."config/includes/include_".sprintf("%06d",$id).".xml";

    		$xml = $myInc->rawXMLExport($id);

    		$myPT->writefile($file,$xml);
    		echo "generating " . $file . "<br/>";
    	}
    }

    $sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez, con_id";
    $rs = $myDB->query ($sql);
    $html="";
    $myCO = new PhenotypeContent();
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["con_id"];
    	if ($myRequest->check("con_id".$id))
    	{
    		$file = $dir ."config/content/content_".sprintf("%06d",$id).".xml";

    		$xml = $myCO->rawXMLExport($id);

    		$_xml = @simplexml_load_string($xml);
    		if ($_xml)
    		{
    			$myPT->writefile($file,$xml);
    			echo "generating " . $file . "<br/>";
    		}
    		else
    		{
    			echo '<font color="red">skipping ' . $file . ' XML-Error</font><br/>';
    			if (PT_DEBUG==1)
    			{
    				$file = "data/content/_debug_data_" . sprintf("%06d",$dat_id) . "_".$importmethod.".xml";
    				$myPT->writefile($file,$xml);
    			}
    		}
    	}
    }

    if ($myRequest->check("mediabase"))
    {
    	$file = $dir ."config/mediabase/mediagroups.xml";
    	$myMB = new PhenotypeMediabase();
    	$xml = $myMB->rawXMLExport();

    	$myPT->writefile($file,$xml);
    	echo "generating " . $file . "<br/>";
    }

    $sql = "SELECT * FROM extra ORDER BY ext_rubrik, ext_bez, ext_id";
    $rs = $myDB->query ($sql);
    $html="";
    $myExtra = new PhenotypeExtra();
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["ext_id"];
    	if ($myRequest->check("ext_id".$id))
    	{
    		$file = $dir ."config/extras/extra_".sprintf("%06d",$id).".xml";

    		$xml = $myExtra->rawXMLExport($id);

    		$myPT->writefile($file,$xml);
    		echo "generating " . $file . "<br/>";
    	}
    }

    $sql = "SELECT * FROM action ORDER BY act_bez, act_id";
    $rs = $myDB->query ($sql);
    $myAction= new PhenotypeAction();
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["act_id"];
    	if ($myRequest->check("act_id".$id))
    	{
    		$file = $dir ."config/actions/action_".sprintf("%06d",$id).".xml";
    		$xml = $myAction->rawXMLExport($id);

    		$myPT->writefile($file,$xml);
    		echo "generating " . $file . "<br/>";
    	}
    }

    if ($myRequest->check("ticketsubjects"))
    {
    	$file = $dir ."config/ticketsubjects/ticketsubjects.xml";
    	$myTicket = new PhenotypeTicket();
    	$xml = $myTicket->rawXMLSubjectsExport();

    	$myPT->writefile($file,$xml);
    	echo "generating " . $file . "<br/>";
    }

    if ($myRequest->check("roles"))
    {
    	$file = $dir ."config/roles/roles.xml";
    	$myUser = new PhenotypeUser();
    	$xml = $myUser->rawXMLRolesExport();

    	$myPT->writefile($file,$xml);
    	echo "generating " . $file . "<br/>";
    }

    if ($myRequest->check("application"))
    {
    	$file1 = APPPATH . "_application.inc.php";
    	$file2 = $dir ."_application.inc.php";

    	copy($file1,$file2);
    	@chmod ($file,UMASK);
    	echo "generating " . $file2 . "<br/>";
    }

    if ($myRequest->check("host"))
    {
    	$file1 = APPPATH . "_host.config.inc.php";
    	$file2 = $dir ."_host.config.inc.php";

    	copy($file1,$file2);
    	@chmod ($file,UMASK);
    	echo "generating " . $file2 . "<br/>";
    }    

    if ($myRequest->check("preferences"))
    {
    	$file1 = APPPATH . "preferences.xml";
    	$file2 = $dir ."preferences.xml";

    	copy($file1,$file2);
    	@chmod ($file,UMASK);
    	echo "generating " . $file2 . "<br/>";
    }

    if ($myRequest->check("htdocs"))
    {
    	$_files = Array (".svn","CVS",".htaccess","php.ini","_phenotype","media","index.php","pindex.php","preview.php","print.php","xmlpage.php","xmlcontent.php","404.php","reload.php","debuginfo.php","install.php","install.4build.php","installcheck.txt","deploy.php");

    	$directory = SERVERPATH;
    	$fp = @opendir($directory);
    	if ($fp)
    	{
    		while (false !== ($file = readdir($fp)))
    		{
    			if ($file != "." && $file != ".." && $file != ".svn")
    			{
    				if (!in_array($file,$_files))
    				{
    					if (is_dir($directory . $file))
    					{
    						echo "Copy Folder " . $file . "<br/>";
    						$myAdm->copyDirComplete($directory . $file,$dir."htdocs/".$file);
    					}
    					else
    					{
    						echo "Copy File " . $file . "<br/>";
    						copy ($directory . $file,$dir."htdocs/". $file);
    						chmod ($dir."htdocs/". $file,UMASK);
    					}
    				}
    			}
    		}
    	}
    }


    if ($myRequest->check("storage"))
    {

    	$directory = APPPATH . "storage/";
    	$fp = @opendir($directory);
    	if ($fp)
    	{
    		while (false !== ($file = readdir($fp)))
    		{
    			if ($file != "." && $file != ".." && $file != ".svn")
    			{

    				if (is_dir($directory . $file))
    				{
    					echo "Copy Folder " . $file . "<br/>";
    					$myAdm->copyDirComplete($directory . $file,$dir."storage/".$file);
    				}
    				else
    				{
    					echo "Copy File " . $file . "<br/>";
    					copy ($directory . $file,$dir."storage/". $file);
    					chmod ($dir."storage/". $file,UMASK);
    				}

    			}
    		}
    	}
    }

    // Backendklassen

    $directory = APPPATH . "backend/";
    $fp = @opendir($directory);


    if ($fp)
    {
    	while (false !== ($file = readdir($fp)))
    	{
    		if ($file != "." && $file != "..")
    		{
    			$filecheck = str_replace('.',"_",$file);
    			if ($myRequest->check("backend_".$filecheck))
    			{
    				echo "Copy backend class " . $file . "<br/>";
    				copy ($directory . $file,$dir."config/backend/". $file);
    				chmod ($dir."config/backend/". $file,UMASK);
    			}

    		}
    	}
    }


    // Languagemaps
	/*
    $directory = APPPATH . "languagemaps/";
    $fp = @opendir($directory);


    if ($fp)
    {
    	while (false !== ($file = readdir($fp)))
    	{
    		if ($file != "." && $file != "..")
    		{
    			$filecheck = str_replace('.',"_",$file);
    			if ($myRequest->check("lmap_".$filecheck))
    			{
    				echo "Copy languagemap " . $file . "<br/>";
    				copy ($directory . $file,$dir."config/languagemaps/". $file);
    				chmod ($dir."config/languagemaps/". $file,UMASK);
    			}

    		}
    	}
    }
	*/




    $sql = "SELECT * FROM pagegroup ORDER BY grp_id";
    $rs = $myDB->query ($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["grp_id"];
    	if ($myRequest->check("grp_id".$id))
    	{
    		if ($myRequest->getI("dataajax")==1)
    		{
    			$fcounter++;
					?><br/>
					<strong><?php echo localeH("Pages from page group");?> <?php echo $id ?></strong><br/>
					<iframe src="backend.php?page=Config,Packages,ajaxexport&type=pages&grp_id=<?php echo $id ?>&fcounter=<?php echo $fcounter ?>" width="495" height="100" frameborder="0"></iframe>
					<br/>
					<?php
    		}
    		else
    		{
    			$sql = "SELECT pag_id FROM page WHERE grp_id = " . $id;
    			$rs2 = $myDB->query ($sql);
    			while ($row2 = mysql_fetch_array($rs2))
    			{
    				$myPage = new PhenotypePage($row2["pag_id"]);
    				$xml = $myPage->rawXMLExport();

    				$file = $dir ."data/pages/page_" . sprintf("%06d",$row2["pag_id"]) . ".xml";

    				$myPT->writefile($file,$xml);
    				echo "generating " . $file . "<br/>";
    			}
    		}
    	}
    }

    $sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez, con_id";
    $rs = $myDB->query ($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["con_id"];
    	if ($myRequest->check("data_con_id".$id))
    	{
    		$importmethod = $myRequest->get("data_con_id".$id."_importmethod");
    		if ($myRequest->getI("dataajax")==1)
    		{
    			$fcounter++;
					?><br/>
					<strong><?php echo localeH("Records from content class");?> <?php echo $id ?></strong><br/>
					<iframe src="backend.php?page=Config,Packages,ajaxexport&type=content&con_id=<?php echo $id ?>&importmethod=<?php echo $importmethod ?>&fcounter=<?php echo $fcounter ?>" width="495" height="100" frameborder="0"></iframe>
					<br/>
					<?php
    		}
    		else
    		{
    			$sql = "SELECT * FROM content_data WHERE con_id = " . $id;
    			$rs2 = $myDB->query ($sql);
    			$fname = "PhenotypeContent_".$id;
    			$myCO = new $fname;
    			while ($row2 = mysql_fetch_array($rs2))
    			{
    				$myCO->init($row2);
    				$xml = $myCO->rawXMLDataExport($importmethod);

    				$file = $dir ."data/content/data_" . sprintf("%06d",$row2["dat_id"]) . "_".$importmethod.".xml";
    				if ($importmethod=="overwrite")
    				{
    					$file = $dir ."data/content/data__" . sprintf("%06d",$row2["dat_id"]) . "_".$importmethod.".xml";
    				}
    				$myPT->writefile($file,$xml);
    				echo "generating " . $file . "<br/>";
    			}
    		}

    	}
    }


    $sql = "SELECT * FROM mediagroup ORDER BY grp_id";
    $rs = $myDB->query ($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["grp_id"];
    	if ($myRequest->check("mgrp_id".$id))
    	{
    		$importmethod = $myRequest->get("mgrp_id".$id."_importmethod");

    		if ($myRequest->getI("dataajax")==1)
    		{
    			$fcounter++;
    			?><br/>
					<strong><?php echo localeH("Mediaobjects from media group");?> <?php echo $id ?></strong><br/>
					<iframe src="backend.php?page=Config,Packages,ajaxexport&type=media&grp_id=<?php echo $id ?>&importmethod=<?php echo $importmethod ?>&fcounter=<?php echo $fcounter ?>" width="495" height="100" frameborder="0"></iframe>
					<br/>
					<?php
    		}
    		else
    		{
    			$sql = "SELECT med_id,med_type FROM media WHERE grp_id = " . $id;
    			$rs2 = $myDB->query ($sql);
    			while ($row2 = mysql_fetch_array($rs2))
    			{
    				if ($row2["med_type"]==MB_IMAGE)
    				{
    					$myMO = new PhenotypeImage($row2["med_id"]);
    				}
    				else
    				{
    					$myMO = new PhenotypeDocument($row2["med_id"]);
    				}
    				$xml = $myMO->rawXMLExport($importmethod);

    				$file = $dir ."data/media/media_" . sprintf("%06d",$row2["med_id"]) . "_".$importmethod.".xml";
    				if ($importmethod=="overwrite")
    				{
    					$file = $dir ."data/media/media__" . sprintf("%06d",$row2["med_id"]) . "_".$importmethod.".xml";
    				}
    				$myPT->writefile($file,$xml);
    				echo "generating " . $file . "<br/>";
    			}
    		}
    	}
    }


    $sql = "SELECT * FROM ticketsubject ORDER BY sbj_id";
    $rs = $myDB->query ($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["sbj_id"];
    	if ($myRequest->check("sbj_id".$id))
    	{
    		$importmethod = $myRequest->get("sbj_id".$id."_importmethod");

    		if ($myRequest->getI("dataajax")==1)
    		{
    			$fcounter++;
					?><br/>
					<strong><?php echo localeH("Tasks from ticket subject");?> <?php echo $id ?></strong><br/>
					<iframe src="backend.php?page=Config,Packages,ajaxexport&type=tickets&sbj_id=<?php echo $id ?>&fcounter=<?php echo $fcounter ?>" width="495" height="100" frameborder="0"></iframe>
					<br/>
					<?php
    		}
    		else
    		{
    			$sql = "SELECT * FROM ticket WHERE sbj_id = " . $id;
    			$rs2 = $myDB->query ($sql);
    			$myTicket = new PhenotypeTicket();
    			while ($row2 = mysql_fetch_array($rs2))
    			{

    				$myTicket->init($row2);
    				$xml = $myTicket->rawXMLExport($importmethod);

    				$file = $dir ."data/tickets/ticket_" . sprintf("%06d",$row2["tik_id"]) . "_".$importmethod.".xml";
    				if ($importmethod=="overwrite")
    				{
    					$file = $dir ."data/tickets/ticket__" . sprintf("%06d",$row2["tik_id"]) . "_".$importmethod.".xml";
    				}
    				$myPT->writefile($file,$xml);
    				echo "generating " . $file . "<br/>";
    			}
    		}
    	}
    }




    $sql = "SELECT * FROM user ORDER BY usr_id";
    $rs = $myDB->query ($sql);
    $html="";

    while ($row = mysql_fetch_array($rs))
    {
    	$id = $row["usr_id"];
    	if ($myRequest->check("usr_id".$id))
    	{
    		$myUser = new PhenotypeUser($id);
    		$file = $dir ."data/users/user_".sprintf("%06d",$id).".xml";

    		$xml = $myUser->rawXMLExport();

    		$myPT->writefile($file,$xml);
    		echo "generating " . $file . "<br/>";
    	}
    }
    
    // create the package class file
		$script = '<?php
	class PhenotypePackage extends PhenotypePackageStandard
	{
		public $bez = "'.$myRequest->getA("title").'";
		public $packagefolder = "'.$myRequest->getA("folder", PT_ALPHANUMERIC . "-_").'";
		
		function getDescription()
		{
			return ("'.$myRequest->getA("desc",PT_ALPHAPLUS.PHP_EOL).'");
		}
	}
	?>';

		$dateiname = $dir ."PhenotypePackage.class.php";
		$fp = fopen ($dateiname,"w");
		fputs ($fp,$script);
		fclose ($fp);
		@chmod ($dateiname,UMASK);

    // Zielordner

    //$targetfolder = $myRequest->get("folder");
		$targetfolder = $myRequest->getA("folder", PT_ALPHANUMERIC . "-_"); //changed 09\05\06 by Dominique Bös
    if ($myRequest->getI("dataajax")==1)
    {
    	?>
       	<br/>
		<iframe src="" width="495" height="100" frameborder="0" id="ajaxcopy"></iframe>
		<br/>
		<?php
    }
    else
    {
    	if ($targetfolder=="")
    	{
    		echo '<font color="red">'.locale("No target folder specified. Package not copied.").'</font><br/>';
    	}
    	else
    	{
    		$targetfolder = PACKAGEPATH . $targetfolder."/";
    		if (file_exists($targetfolder) && (!$myRequest->check("forceCopy")))
    		{
    			echo '<font color="red">'.locale("Target folder already existing. Package not copied.").'</font><br/>';
    		}
    		else
    		{
    			copyPackageDir($dir, $targetfolder);
    		}
    	}
    }

/*
 * copies the package dir non-destructive
 *
 * leaves directories in target path untouched that are not relevant for the package, like .svn
 *
 */
function copyPackageDir($fromDir, $toDir) {
	global $myAdm;
	
	if (file_exists($toDir))
	{
		// delete all phenotype files
		cleanupPackageDir($toDir);
	}
	else 
	{
		mkdir ($toDir);
		chmod ($toDir,UMASK);
	}
	
	$fp = @opendir($fromDir);
	if ($fp)
	{
		while (false !== ($file = readdir($fp)))
		{
			if ($file != "." && $file != ".." && $file != ".svn")
			{
				if (is_dir($fromDir . $file))
				{
					echo "Copy Folder " . $file . "<br/>";
					$myAdm->copyDirComplete($fromDir . $file, $toDir . $file);
				}
				else
				{
					echo "Copy File " . $file . "<br/>";
					copy ($fromDir. $file, $toDir . $file);
					chmod ($toDir . $file,UMASK);
				}
			}
		}
	}
}

function cleanupPackageDir($baseDir, $dir = "", $ignoreDotFiles = true) {
	if ($baseDir{strlen($baseDir)-1} != "/") {
		$baseDir .= "/";
	}
	if ( (strlen($dir)) && ($dir{strlen($dir)-1} != "/") ) {
		$dir .= "/";
	}
	
	if (strlen($dir) ) {
		$dirEmpty = true;
	} else {
		$dirEmpty = false;
	}

	
	$fp = @opendir($baseDir . $dir);
	echo("cleaning up dir $baseDir + $dir, mode: $ignoreDotFiles<BR />\n");
	while ( ($file = readdir($fp)) !== false ) {
	
		echo("file: $file ");
		if ( ($file == ".") || ($file == "..") ) {
			echo("...omitting<BR />");
			continue;
		}
		
		if ($ignoreDotFiles && ($file{0} == ".") ) {
			$dirEmpty = false; // there is a regular hidden file here
			echo("...omitting<BR />");
			continue;
		} elseif ( ($file == ".svn") || ($file == "CVS") ) {
			$dirEmpty = false;	// there is a regular svn/cvs file here
			echo("...omitting<BR />");
			continue;
		}
		
		if (is_dir($baseDir . $dir . $file)) {
			echo("...is dir<BR />\n");
			if ( ($file == "htdocs") || ($file == "storage") ) { // in these folders dot-files are allowed, so we delete them here
				$ignoreMode = false;
			} else {
				$ignoreMode = true;
			}
			if (! cleanupPackageDir($baseDir, $dir . $file, $ignoreMode) ) {
				$dirEmpty = false; // subDir left
			}
		} else {
			echo("...deleting<BR />\n");
			unlink($baseDir . $dir . $file);
		}
	}
	$ds = $dirEmpty?"yes":"no";
	echo("dir $dir empty?: ". $ds ."<BR />\n");
	if ($dirEmpty) {
		$dirEmpty = rmdir($baseDir . $dir);
	}
	return $dirEmpty;
}

	?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			&nbsp;&nbsp;
            </td>
          </tr>
        </table>
        <script type="text/javascript">
        var feedback = new Array();
        var fcounter = <?php echo $fcounter ?>;




        function ajaxfeedback(nr)
        {
        	feedback[nr]=1;
        	c=0;
        	for (i=1;i<=fcounter;i++)
        	{
        		if (feedback[i]===1){c=c+1;}
        	}
        	if (c==fcounter)
        	{
        		var obj = document.getElementById("ajaxcopy");
        		obj.src ="backend.php?page=Config,Packages,ajaxexport&type=package&folder=<?php echo $myRequest->getURL("folder"); ?>&title=<?php echo $myRequest->getURL("title"); ?>&desc=<?php echo $myRequest->getURL("desc"); ?>";
        	}
        }
		ajaxfeedback(0);
</script>

<?php 

$myLayout->workarea_stop_draw();
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content}
// -------------------------------------
?>
<?php
$myAdm->mainTable($left,$content);
?>
<?php

?>
</body>
</html>























