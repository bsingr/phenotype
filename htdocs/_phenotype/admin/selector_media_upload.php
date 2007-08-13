<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
<?
require("_config.inc.php");
require("_session.inc.php");

$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?= PT_VERSION ?></title>
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
	  <input type="hidden" name="cf" value="<?=$_REQUEST["cf"]?>">
<input type="hidden" name="x" value="<?=$_REQUEST["x"]?>">
<input type="hidden" name="y" value="<?=$_REQUEST["y"]?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?=$_REQUEST["sortorder"]?>">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">				   
				   
<table width="495" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle">Mediaobjekt hochladen</td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      </tr>
    </table>	
				
<?				
$myLayout->tab_new();
$url = "mediabase_upload.php";
$myLayout->tab_addEntry("Eigenschaften",$url,"b_konfig.gif");
$myLayout->tab_draw("Eigenschaften",495,0,1);

$myLayout->workarea_start_draw(495);
$html = $myLayout->workarea_form_text("Titel","bez","");
$html .= $myLayout->workarea_form_text("Alternate","alt","");
$myLayout->workarea_row_draw("Bezeichnung",$html); 
$myPT->startBuffer();
  ?>
  <input name="userfile" type="file" class="input"><br>
  <input type="checkbox" value="1" name="documentonly"> Bilder als Dokumente handhaben    		
  <?
  $html = $myPT->stopBuffer();
  $myLayout->workarea_row_draw("Bild / Dokument",$html); 


$myPT->startBuffer();

if ($_REQUEST["cf"]==1)
{
?>
<select name="folder1" class="input" style="width:240px">
<?
$myMB = new PhenotypeMediaBase();
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
         <option <?=$selected?>><?=$myPT->codeH($k)?></option>
          <?
          }
          ?>
</select><br><br><input name="folder1_new" type="text" class="input" value="" style="width:230px">
<?
}
else
{
?>
<input type="hidden" name="folder1" value="<?=$_REQUEST["folder"]?>">
<input type="hidden" name="folder1_new" value="<?=$_REQUEST["folder"]?>">
<?=$_REQUEST["folder"]?>
<?
}

$html = $myPT->stopBuffer();
$myLayout->workarea_row_draw("Ordner",$html); 

$html = $myLayout->workarea_form_textarea("","keywords","",$r=5,$x=300,$br=1);
$myLayout->workarea_row_draw("Keywords",$html); 

$html = $myLayout->workarea_form_textarea("","comment","",$r=5,$x=300,$br=1);
$myLayout->workarea_row_draw("Kommentar",$html); 

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
<?
$grp_id=2;
foreach ($_mediagroups AS $k=>$v)
{
	$selected="";
	if ($k == $grp_id){$selected='selected="selected"';} // Standard
?>
<option <?=$selected?> value="<?=$k?>"><?=$v?></option>
<?
}

$html = $myPT->stopBuffer();
$myLayout->workarea_row_draw("Mediagruppe",$html);
?>
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px"value="Hochladen">&nbsp;&nbsp;</td>
          </tr>
        </table>
<?
$myLayout->workarea_stop_draw();
 
?>
</form>				
				
</body>
</html>
