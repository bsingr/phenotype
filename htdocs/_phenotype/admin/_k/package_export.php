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
?>
<?php
$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<?php
$myAdm->header("Konfiguration");
?>
<body>
<?php
$myAdm->menu("Konfiguration");
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
            <td class="windowTitle"><?php echo localeH("Export Package");?></td>
            <td align="right" class="windowTitle"><!--<a href="http://www.phenotype-cms.de/docs.php?v=23&t=21" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0">--></a></td>
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
        <td class="windowTabTypeOnly"><strong><?php echo localeH("Structures");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
    <?php
    $myLayout->workarea_start_draw();
		?>

		<form action="package_export2.php" method="post">
		
		<?php
		$html = $myLayout->workarea_form_checkbox("", "pagegroups", 1,locale("Pagegroups"));
		$myLayout->workarea_row_draw(locale("Pages"), $html);

		$html = $myLayout->workarea_form_checkbox("", "layouts", 1,locale("Layouts"));
		$myLayout->workarea_row_draw("", $html);

		$sql = "SELECT * FROM component ORDER BY com_rubrik, com_bez, com_id";
		$rs = $myDB->query ($sql);
		$html="";
		while ($row = mysql_fetch_array($rs))
		{
			$html .= $myLayout->workarea_form_checkbox("", "com_id".$row["com_id"], 1,$row["com_id"] ." - ". $row["com_bez"]. " (".$row["com_rubrik"].")");
		}
		$myLayout->workarea_row_draw(locale("Components"), $html);


		$sql = "SELECT * FROM include ORDER BY inc_rubrik, inc_bez, inc_id";
		$rs = $myDB->query ($sql);
		$html="";
		while ($row = mysql_fetch_array($rs))
		{
			$html .= $myLayout->workarea_form_checkbox("", "inc_id".$row["inc_id"], 1,$row["inc_id"] ." - ". $row["inc_bez"]. " (".$row["inc_rubrik"].")");
		}
		$myLayout->workarea_row_draw(locale("Includes"), $html);


		$sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez, con_id";
		$rs = $myDB->query ($sql);
		$html="";
		while ($row = mysql_fetch_array($rs))
		{
			$html .= $myLayout->workarea_form_checkbox("", "con_id".$row["con_id"], 1,$row["con_id"] ." - ". $row["con_bez"]. " (".$row["con_rubrik"].")");
		}
		$myLayout->workarea_row_draw(locale("Contentobjects"), $html);


		$html = $myLayout->workarea_form_checkbox("", "mediabase", 1,locale("Mediagroups"));
		$myLayout->workarea_row_draw(locale("Mediabase"), $html);

		$sql = "SELECT * FROM extra ORDER BY ext_rubrik, ext_bez, ext_id";
		$rs = $myDB->query ($sql);
		$html="";
		while ($row = mysql_fetch_array($rs))
		{
			$html .= $myLayout->workarea_form_checkbox("", "ext_id".$row["ext_id"], 1,$row["ext_id"] ." - ". $row["ext_bez"]. " (".$row["ext_rubrik"].")");
		}
		$myLayout->workarea_row_draw(locale("Extras"), $html);


		$sql = "SELECT * FROM action ORDER BY act_bez, act_id";
		$rs = $myDB->query ($sql);
		$html="";
		while ($row = mysql_fetch_array($rs))
		{
			$html .= $myLayout->workarea_form_checkbox("", "act_id".$row["act_id"], 1,$row["act_id"] ." - ". $row["act_bez"]);
		}
		$myLayout->workarea_row_draw(locale("Actions"), $html);


		$html = $myLayout->workarea_form_checkbox("", "ticketsubjects", 1,locale("Task subjects"));
		$myLayout->workarea_row_draw(locale("Tasks"), $html);

		$html = $myLayout->workarea_form_checkbox("", "roles", 1,locale("Roles"));
		$myLayout->workarea_row_draw(locale("Rights"), $html);

		$html = $myLayout->workarea_form_checkbox("", "application", 1,"_application.inc.php");
		$html .= $myLayout->workarea_form_checkbox("", "host", 0,"_host.config.inc.php");
		$html .= $myLayout->workarea_form_checkbox("", "preferences", 1,"preferences.xml");
		$html .= $myLayout->workarea_form_checkbox("", "htdocs", 1,locale("Files within webroot"));
		$html .= $myLayout->workarea_form_checkbox("", "storage", 1,locale("Storage folder"));
		$myLayout->workarea_row_draw(locale("Application"), $html);
		
		$html = "";
		$directory = APPPATH . "backend/";
		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ($file != "." && $file != ".." && $file != ".svn")
				{
					$file = str_replace('.',"_",$file);
					$html .= $myLayout->workarea_form_checkbox("", "backend_".$file, 1,$file);

				}
			}
		}
		$myLayout->workarea_row_draw(locale("Backend classes"), $html);
		
		/*
		$html = "";
		$directory = APPPATH . "languagemaps/";
		$fp = @opendir($directory);
		if ($fp)
		{
			while (false !== ($file = readdir($fp)))
			{
				if ($file != "." && $file != ".." && $file != ".svn")
				{
					$file = str_replace('.',"_",$file);
					$html = $myLayout->workarea_form_checkbox("", "lmap_".$file, 1,$file);

				}
			}
		}
		$myLayout->workarea_row_draw(locale("Language maps"), $html);
		*/	
		$myLayout->workarea_stop_draw();
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("Data");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
    <?php
    $myLayout->workarea_start_draw();
    $sql = "SELECT * FROM pagegroup ORDER BY grp_id";
    $rs = $myDB->query ($sql);
    $html="";
    while ($row = mysql_fetch_array($rs))
    {
    	$html .= $myLayout->workarea_form_checkbox("", "grp_id".$row["grp_id"], 0,$row["grp_id"] ." - ". $row["grp_bez"]);
    }
    $myLayout->workarea_row_draw(locale("Pages"), $html);

    $sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez, con_id";
    $rs = $myDB->query ($sql);
    $html="";
    while ($row = mysql_fetch_array($rs))
    {
    	$radio = '&nbsp;&nbsp;&nbsp;[<input type="radio" name="data_con_id'.$row["con_id"].'_importmethod" value="overwrite" checked="checked"/> '.locale("overwrite").' <input type="radio" name="data_con_id'.$row["con_id"].'_importmethod" value="append" /> '.locale("append").' ]';
    	$html .= $myLayout->workarea_form_checkbox("", "data_con_id".$row["con_id"], 0,$row["con_id"] ." - ". $row["con_bez"]. " (".$row["con_rubrik"].") " . $radio);
    }
    $myLayout->workarea_row_draw(locale("Records"), $html);


    $sql = "SELECT * FROM mediagroup ORDER BY grp_bez";
    $rs = $myDB->query ($sql);
    $html="";
    while ($row = mysql_fetch_array($rs))
    {
    	$radio = '&nbsp;&nbsp;&nbsp;[<input type="radio" name="mgrp_id'.$row["grp_id"].'_importmethod" value="overwrite" checked="checked"/> '.locale("overwrite").' <input type="radio" name="mgrp_id'.$row["grp_id"].'_importmethod" value="append" /> '.locale("append").' ]';
    	$html .= $myLayout->workarea_form_checkbox("", "mgrp_id".$row["grp_id"], 0,$row["grp_id"] ." - ". $row["grp_bez"]. " " . $radio);
    }
    $myLayout->workarea_row_draw(locale("Media objects"), $html);


    $sql = "SELECT * FROM ticketsubject ORDER BY sbj_bez";
    $rs = $myDB->query ($sql);
    $html="";
    while ($row = mysql_fetch_array($rs))
    {
    	$radio = '&nbsp;&nbsp;&nbsp;[<input type="radio" name="sbj_id'.$row["sbj_id"].'_importmethod" value="overwrite" checked="checked"/> '.locale("overwrite").' <input type="radio" name="sbj_id'.$row["sbj_id"].'_importmethod" value="append" /> '.locale("append").' ]';
    	
    	// vorerst keine Append-Methode
    	$radio = '<input type="hidden" name="sbj_id'.$row["sbj_id"].'_importmethod" value="overwrite">';
    	$html .= $myLayout->workarea_form_checkbox("", "sbj_id".$row["sbj_id"], 0,$row["sbj_id"] ." - ". $row["sbj_bez"]. " " . $radio);
    }
    $myLayout->workarea_row_draw(locale("Tasks"), $html);


    $sql = "SELECT * FROM user ORDER BY usr_id";
    $rs = $myDB->query ($sql);
    $html="";
    while ($row = mysql_fetch_array($rs))
    {
    	$html .= $myLayout->workarea_form_checkbox("", "usr_id".$row["usr_id"], 0,$row["usr_id"] ." - ". $row["usr_vorname"] . " " . $row["usr_nachname"]);
    }

    
    $myLayout->workarea_row_draw(locale("User"), $html);    
    

		$myLayout->workarea_stop_draw();
	?>
	
	
	    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("Meta")?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
    <?php
    $myLayout->workarea_start_draw();
    
    $html = $myLayout->workarea_form_text("","title","");
 		$myLayout->workarea_row_draw(locale("Name"), $html);
 	
	 	$html = $myLayout->workarea_form_textarea("","desc",date('d.m.y'));
 		$myLayout->workarea_row_draw(locale("Description"), $html);
 		
		$html = $myLayout->workarea_form_text("","folder","package_");
 		$myLayout->workarea_row_draw(locale("Folder"), $html);
    
    $html = $myLayout->workarea_form_checkbox("", "dataajax", 0,locale("use AJAX exporter"));    
    // currently deactived because it uses unix commands (doesn't work in an windows environment, shouldn't be in an offical release)
    //$html .= $myLayout->workarea_form_checkbox("", "forceCopy", 0,"Vorhandenes Package mit gleichem Namen überschreiben.");
    $myLayout->workarea_row_draw(locale("Method"), $html);
    
	?>
    
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Start">&nbsp;&nbsp;
            </td>
          </tr>
        </table>		
		</form>
		<?php


		$myLayout->workarea_stop_draw();
		?>


<?php
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
























