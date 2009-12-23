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

require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor_Media");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo $myPT->version ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PT_CHARSET?>">
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
<?php
// determine mediagroups of current user
$sql = "SELECT * FROM mediagroup ORDER BY grp_bez";
$rs = $myDB->query($sql);
$_mediagroups = Array();
while ($row=mysql_fetch_array($rs))
{
	if ($mySUser->hasRight("access_mediagrp_".$row["grp_id"]))	
	{
		$_mediagroups[$row["grp_id"]]=$row["grp_id"];
	}
}

if (count($_mediagroups)==0){$_mediagroups=Array(-1);}
?>
<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/jquery-ui.js"></script>
<script type="text/javascript">var pt_bak_id="";</script>
<script language="JavaScript" src="phenotype.js"></script>
<form action="selector_media.php" name="form1">

<table width="495" border="0" cellpadding="0" cellspacing="0"  align="center">
<tr>
	<td class="windowTab">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowTitle"><?php echo localeH("Media selection");?></td>
		    <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
		  </tr>
		</table>
	</td>
</tr>
</table>
<?if ($myRequest->getI("msg")!=0):?>
<table width="495" border="0" cellpadding="0" cellspacing="0"  align="center">
<tr>
	<td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
</tr>
<tr>
	<td class="windowTab">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
		    <td class="windowError"><h1><?php echo localeH("Error")?></h1>
		    <?php
		    $message="";
		    switch ($myRequest->getI("msg"))
		    {
		    	case 1:
		    		$message = locale("Upload failed!");
		    		break;
		    	case 2:
		    		$message = locale("Upload canceled! Wrong image size.");
		    		break;
	    		case 3:
		    		$message = locale("Upload canceled! Unknown image format / not an image.");
		    		break;	
	    		case 4:
	    			$message = locale("Upload canceled! Wrong file type.");
	    			break;	    		
		    }
		    ?>
			<p><?php echo codeH($message)?></p>
			</td>
		  </tr>
		</table>
	</td>
</tr>
<tr>
	<td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
</tr>
</table>
<?endif?>

<table width="495" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="padding10"><?php echo localeH("Folder");?>:</td>
          <td>
          <?php if ($myRequest->getI("cf")==1){ ?>          
          <select name="folder" class="feld" onchange="document.forms.form1.submit();">
          <option value="-1">(<?php echo localeH("all");?>)</option>
          <?php
          $myMB = new PhenotypeMediabase();
                 
          $_folder = $myMB->getFullLogicalFolder($_mediagroups);
       	  if (!in_array($myRequest->get("folder"),$_folder) AND $myRequest->get("folder")!="-1")
		  {
			 $_folder[]=$myRequest->get("folder");
			 asort($_folder);
		  }
		    foreach ($_folder AS $k)
          {
            $selected ="";
			if ($myRequest->get("folder")==$k){$selected="selected";}

          ?>
         <option <?php echo $selected ?>><?php echo codeH($k) ?></option>
          <?php
          }
          ?>
          </select>
          <?php 
          } 
          else 
          {
          	if ($myRequest->get("folder")!=-1)
          	{
 	      		echo codeH($myRequest->get("folder"));
          	}
          }?>
          </td>
        </tr>
        <?php if ($myRequest->getI("x")!=0 OR $myRequest->getI("y")!=0):?>
        <tr>
          <td class="padding10"><?php echo localeH("Size");?>:</td>
          <td>
          <?php
          $x = $myRequest->getI("x");
          if ($x==0){$x="?";}
          $y = $myRequest->getI("y");
          if ($y==0){$y="?";}
          echo $x." x ".$y;
          ?>
          </td>
        </tr>
        <?php endif // sizes?>
        <?php if ($myRequest->get("doc")!=""):?>
        <tr>
          <td class="padding10"><?php echo localeH("File Types");?>:</td>
          <td>
		  <?php 
		  $_docs = explode(",",$myRequest->getA("doc",PT_ALPHANUMERICINT.","));
		  $_docs = array_filter($_docs);
		  echo codeH(join(", ",$_docs))?>	
          </td>
        </tr>
        <?php endif // doc?>
      </table>
    </td>
  </tr>
</table>


	    

<?php if ($myRequest->getI("cf")==1){ ?>
<input type="hidden" name="cf" value="1">
<?php }else{ ?>
<input type="hidden" name="folder" value="<?php echo $myRequest->getH("folder")?>">
<input type="hidden" name="cf" value="0">
<?php } ?>
<input type="hidden" name="x" value="<?php $myRequest->getI("x") ?>">
<input type="hidden" name="y" value="<?php $myRequest->getI("y") ?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?php echo $myRequest->getI("sortorder")?>">
<input type="hidden" name="type" value="<?php echo $myRequest->getI("type") ?>">
<input type="hidden" name="doc" value="<?php echo $myRequest->getA("doc",PT_ALPHANUMERICINT.",")?>">
<?php

$sql = "SELECT * FROM media WHERE grp_id IN (";

// only objects of mediagroups the user can access
$sql .= implode(",",$_mediagroups).") ";


if ($myRequest->get("folder")!=-1 AND $myRequest->get("folder")!="")
{
	$sql .=" AND( med_logical_folder1 LIKE'" .$myRequest->getH("folder") ."%' OR med_logical_folder2 LIKE'" . $myRequest->getH("folder") ."%' OR med_logical_folder3 LIKE'" . $myRequest->getH("folder") ."%')";
}

if ($myRequest->get("type")!=-1)
{
	$sql .=" AND med_type='" . $myRequest->getI("type") ."'";
}

if ($myRequest->getI("x")!=0)
{
 	$sql .=" AND med_x = " . $myRequest->getI("x");
}

if ($myRequest->getI("y")!=0)
{
 	$sql .=" AND med_y = " . $myRequest->getI("y");
}

if ($myRequest->getA("doc",PT_ALPHANUMERICINT.",")!="")
{
	
	$_docs = explode(",",$myRequest->getA("doc",PT_ALPHANUMERICINT.","));
	$_docs = array_filter($_docs);
	$_filteredocs = Array();
	foreach ($_docs AS $doc)
	{
		$_filtereddocs[] = "'".mysql_real_escape_string($doc)."'";
	}
	$sql .= " AND med_subtype IN (".join(",",$_filtereddocs).")";
}

switch ($myRequest->getI("sortorder"))
{
	case 1:
		$sql .=" ORDER BY med_date DESC";
		break;
	case 2:
		 $sql .=" ORDER BY med_bez";
		break;
	case 3:
		$sql .=" ORDER BY med_id DESC";
		break;
		
}

$rs =$myDB->query($sql);
$anzahl = mysql_num_rows($rs);
if ($anzahl>0)
{
	$p = $myRequest->getI("p");
	$reihe = 4;
	$start = ($p-1)*(12);
	$sql .=" LIMIT ". $start . "," . 12;
	?>
    <table width="495" border="0" cellpadding="0" cellspacing="0" align="center" height="400">
      <tr>
        <td class="windowBlank" valign="top">

        <table width="100%" border="0" cellpadding="0" cellspacing="3">
        <?php
	    $rs =$myDB->query($sql);
	    $i=0;$aktion=-1;
	    while ($row=mysql_fetch_array($rs))
	    {
	        $i++;
	        $aktion=$i%$reihe;
	
	        if ($aktion==1) // Zeile oeffnen
	        {
        	?>
        	<tr valign="top">
        	<?php
        	}
        	?>
            <td class="tableCellMedia" width="136">

            <table width="100" height="100" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" class="tableCellImage">
                  <?php
                    if ($row["med_type"]==MB_IMAGE)
                    {
                      $myIMG = new PhenotypeImage($row["med_id"]);

                      $sx=$myIMG->x;
                      $sy=$myIMG->y;
                      if ($sx!=0 AND $sy!=0)
                      {
	                      if ($sx>$sy)
	                      {
	                        $tx=90;
	                        $ty=round(90*($sy/$sx));
	                      }
	                      else
	                      {
	                        $tx=round(90*($sx/$sy));
	                        $ty=90;
	                      }
                      }
                      else
                      {
                      	// Nur bei kaputten Bildern
                      	$tx=0;$ty=0;
                      }
                      ?>
                      <a href="javascript:select_image(<?php echo $row["med_id"] ?>,'<?php echo $myIMG->thumburl ?>','<?php echo $myIMG->url ?>',<?php echo $tx ?>,<?php echo $ty ?>);self.close();"><?php
                      $myIMG->display_thumb();
                      ?>
                      </a>
                      <?php
                    }
                    else
                    {
                      $icon = "binary";
                      switch (mb_strtolower($row["med_subtype"]))
                      {
                        case "gif":
                        case "jpg":
                        case "bmp":
                        case "psd":
                        case "png":
                        case "jpeg":
                        	$icon = "image";
                        break;

                        case "wav":
                        case "mid":
                        case "mp3":
                        case "ogg":
                        	$icon = "audio";
                        break;

                        case "pdf":
                        	$icon = "pdf";
                        break;

                        case "xls":
                        case "xlsx":
                        	$icon = "excel";
                        break;

                        case "doc":
                        case "docx":
                        	$icon = "word";
                        break;

                        case "ppt":
                        case "pptx":	
                        	$icon = "powerpoint";
                        break;

                        case "sql":
                        case "txt":
                        	$icon = "text";
                        break;
                        case "flv":
                        case "wma":
                        case "mp4":                        		
                        	$icon = "video";
                        break;

                      }

                      echo '<a href="javascript:select_document('.$row["med_id"].');self.close()" title="'.codeH($row["med_bez"]).'">'.$icon."</a>";
                    }
                    ?>
                  </td>
                </tr>
              </table>
              </a>
                <?php
                if ($row["med_type"]==MB_IMAGE)
                {
                  echo locale("Size").": (" . $row["med_x"] ." x " . $row["med_y"] .")<br>";
                }
                else
                {
                  echo "<br>";
                }
                ?>
                <strong><?php echo $myPT->cutString($row["med_bez"],32,17); ?></strong><br>
                <?php
                if ($row["med_type"]==MB_IMAGE){
                ?>
                <a href="javascript:select_image(<?php echo $row["med_id"] ?>,'<?php echo $myIMG->thumburl ?>','<?php echo $myIMG->url ?>',<?php echo $tx ?>,<?php echo $ty ?>);self.close();"><img src="img/b_media.gif" alt="<?php echo localeH("Select image");?>" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("select");?></a>
                <?php
                }else{
                ?>
                 <a href="javascript:select_document(<?php echo $row["med_id"] ?>);self.close();"><img src="img/b_media.gif" alt="<?php echo localeH("Select document");?>" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Select document");?></a>
                <?php
                }?>
                </td>

                    <?php
                    if ($aktion==0) // Zeile schliessen
                    {
                    ?>
                    </tr>
                    <?php
                    }
                  }
                  if ($aktion!=0)
                  {
                    $n= $reihe-($i%$reihe);
                    for ($j=1;$j<=$n;$j++)
                    {
                    ?>
                    <td valign="top" class="tableCellMedia" width="136"></td>
                    <?php
                    }
                  ?>
                  </tr>
                  <?php
                  }
                  ?>


        </table>

        </td>
      </tr>
    </table>
<?php }else{?>
<table width="495" border="0" cellpadding="0" cellspacing="0" align="center" height="50">
	<tr>
    	<td class="windowBlank" style="padding-left:5px">    
		<p><?php echo localeH("No fitting assets found.")?></p>
		</td>
	</tr>
</table>
<?}?>
<table width="495" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
</form>
<form action="selector_media_upload.php" method="post">
<input type="hidden" name="cf" value="<?php echo $myRequest->getI("cf")?>">
<input type="hidden" name="folder" value="<?php echo $myRequest->getH("folder")?>">
<input type="hidden" name="x" value="<?php echo $myRequest->getI("x")?>">
<input type="hidden" name="y" value="<?php echo $myRequest->getI("y")?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?php echo $myRequest->getI("sortorder")?>">
<input type="hidden" name="type" value="<?php echo $myRequest->getI("type")?>">
<input type="hidden" name="doc" value="<?php echo $myRequest->getA("doc",PT_ALPHANUMERICINT.",")?>">
<td class="windowFooterWhite"><input type="submit" class="buttonWhite" value="<?php echo localeH("Upload");?>" style="width:102px"></td>
</form>
        <td align="right" class="windowFooterWhite"><table border="0" cellpadding="0" cellspacing="1">
          <tr>
            <td align="center">
            <?php 
            $max=ceil($anzahl/(12));
            $start = $p-3;
            $stop = $p+3;
            if ($start<1){$start=1;}
            if ($stop>$max){$stop=$max;}
            
            if ($max>1){?>
            <?php echo localeH("Page");?>: </td>
            <?php


            $url = "?folder=" . urlencode($myRequest->get("folder")) . "&type=" . $myRequest->getI("type") . "&sortorder=" . $myRequest->getI("sortorder"). "&cf=" .  $myRequest->getI("cf") . "&x=" .  $myRequest->getI("x") . "&y=" .  $myRequest->getI("y")."&doc=".$myRequest->getA("doc",PT_ALPHANUMERICINT.",");

            if ($p>1)
            {
            ?>
            <td align="center"><a href="selector_media.php<?php echo $url ?>&p=<?php echo $p-1 ?>&a=12" class="tabmenuType"><?php echo localeH("back");?></a></td>
            <td align="center">&nbsp;</td>
            <?php
            }

            for ($i=$start;$i<=$stop;$i++)
            {
              $active="";
              if ($p==$i){$active="Active";}

            ?>
            <td align="center"><a href="selector_media.php<?php echo $url ?>&p=<?php echo $i ?>&a=12" class="tabmenuType<?php echo $active ?>"><?php echo $i ?></a></td>
            <?php
            }
            if ($p!=$max)
            {
            ?>
            <td align="center">&nbsp;</td>
            <td align="center"><a href="selector_media.php<?php echo $url ?>&p=<?php echo $p+1 ?>&a=12" class="tabmenuType"><?php echo localeH("next");?></a></td>
            <?php
            }
            ?>
          </tr>
        </table>
        <?php }?>
        </td>
      </tr>
    </table>
    <script type="text/javascript" language="JavaScript">
	self.focus();
	</script>
</body>
</html>
