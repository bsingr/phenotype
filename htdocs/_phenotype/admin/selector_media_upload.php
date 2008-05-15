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
$myPT->loadTMX("Editor_Media");

$mySmarty = new PhenotypeSmarty;
$myAdm = new PhenotypeAdmin();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
    margin-top: 3px;
    margin-bottom: 3px;
}
-->
</style>
</head>

<body>
<script language="JavaScript" src="phenotype.js"></script>
<script type="text/javascript" language="JavaScript">
self.focus();
</script>

	 <form action="selector_media_upload2.php" method="post" enctype="multipart/form-data" name="form1">
	  <input type="hidden" name="cf" value="<?php echo $_REQUEST["cf"] ?>">
<input type="hidden" name="x" value="<?php echo $_REQUEST["x"] ?>">
<input type="hidden" name="y" value="<?php echo $_REQUEST["y"] ?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?php echo $_REQUEST["sortorder"] ?>">
<input type="hidden" name="type" value="<?php echo $_REQUEST["type"] ?>">				   
				   
<table width="495" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo localeH("Upload mediaobject");?></td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>	
				
<?php				
$myLayout->tab_new();
$url = "mediabase_upload.php";
$myLayout->tab_addEntry(locale("Properties"),$url,"b_konfig.gif");
$myLayout->tab_draw(locale("Properties"),495,0,1);

$myLayout->workarea_start_draw(495);
$html = $myLayout->workarea_form_text(locale("Title"),"bez","");
$html .= $myLayout->workarea_form_text(locale("Alternate"),"alt","");
$myLayout->workarea_row_draw(locale("Name"),$html); 
$myPT->startBuffer();
  ?>
  <input name="userfile" type="file" class="input"><br>
  <input type="checkbox" value="1" name="documentonly"> <?php echo localeH("treat images like documents");?>    		
  <?php
  $html = $myPT->stopBuffer();
  $myLayout->workarea_row_draw(locale("Image / Document"),$html); 


$myPT->startBuffer();

if ($_REQUEST["cf"]==1)
{
?>
<select name="folder1" class="input" style="width:240px">
<?php
$myMB = new PhenotypeMediabase();
$_folder = $myMB->getLogicalFolder();
if (!in_array("_upload",$_folder))
		{
			$_folder[]="_upload";
			asort($_folder);
		}
		if (!in_array($myRequest->get("folder"),$_folder))
		{
			$_folder[]=$myRequest->get("folder");
			asort($_folder);
		}
		  foreach ($_folder AS $k)
          {
            $selected ="";
			if ($_REQUEST["folder"]==$k){$selected="selected";}

          ?>
         <option <?php echo $selected ?>><?php echo $myPT->codeH($k) ?></option>
          <?php
          }
          ?>
</select><br><br><input name="folder1_new" type="text" class="input" value="" style="width:230px">
<?php
}
else
{
?>
<input type="hidden" name="folder1" value="<?php echo $_REQUEST["folder"] ?>">
<input type="hidden" name="folder1_new" value="<?php echo $_REQUEST["folder"] ?>">
<?php echo $_REQUEST["folder"] ?>
<?php
}

$html = $myPT->stopBuffer();
$myLayout->workarea_row_draw(locale("Folder"),$html); 

$html = $myLayout->workarea_form_textarea("","keywords","",$r=5,$x=300,$br=1);
$myLayout->workarea_row_draw(locale("Keywords"),$html); 

$html = $myLayout->workarea_form_textarea("","comment","",$r=5,$x=300,$br=1);
$myLayout->workarea_row_draw(locale("Comment"),$html); 

// determine mediagroups of current user
$_mediagroups = Array();
$sql = "SELECT * FROM mediagroup ORDER BY grp_bez";
$rs = $myDB->query($sql);
while ($row=mysql_fetch_array($rs))
{
	if ($mySUser->hasRight("access_mediagrp_".$row["grp_id"]))	
	{
		$_mediagroups[$row["grp_id"]]=$row["grp_bez"];
	}
}
	
$myPT->startBuffer();
?>
<select name="grp_id" class="input" style="width:120px">
<?php
$grp_id=2;
foreach ($_mediagroups AS $k=>$v)
{
	$selected="";
	if ($k == $grp_id){$selected='selected="selected"';} // Standard
?>
<option <?php echo $selected ?> value="<?php echo $k ?>"><?php echo $v ?></option>
<?php
}

$html = $myPT->stopBuffer();
$myLayout->workarea_row_draw(locale("Mediagroup"),$html);
?>
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Upload");?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
<?php
$myLayout->workarea_stop_draw();
 
?>
</form>				
				
</body>
</html>
