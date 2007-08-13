<?
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
<?
require("_config.inc.php");
require("_session.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?= $myPT->version ?></title>
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
<?
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
<script language="JavaScript" src="phenotype.js"></script>
<script type="text/javascript" language="JavaScript">
self.focus();
</script>
<form action="selector_media.php" name="form1">

<table width="495" border="0" cellpadding="0" cellspacing="0"  align="center">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle">Medienauswahl</td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<?if ($_REQUEST["cf"]==1){?>
<table width="495" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="padding10">Ordner:</td>
          <td>          <select name="folder" class="feld" onchange="document.forms.form1.submit();">
          <option value="-1">(alle)</option>
          <?
          $myMB = new PhenotypeMediaBase();
                          // Hot fix - check groups
                  

          $_folder = $myMB->getFullLogicalFolder($_mediagroups);
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
         <option <?=$selected?>><?=$k?></option>
          <?
          }
          ?>
          </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?}if ($_REQUEST["cf"]==1){?>
<input type="hidden" name="cf" value="1">
<?}else{?>
<input type="hidden" name="folder" value="<?=$_REQUEST["folder"]?>">
<input type="hidden" name="cf" value="0">
<?}?>
<input type="hidden" name="x" value="<?=$_REQUEST["x"]?>">
<input type="hidden" name="y" value="<?=$_REQUEST["y"]?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?=$_REQUEST["sortorder"]?>">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">


<?

	    		  
// SQL-String fuer Medienauswahl zusammenbauen
          global $myDB;

                  $sql = "SELECT * FROM media WHERE grp_id IN (";
                  
                  // Gruppen des Users berücksichtigen
                  $sql .= implode(",",$_mediagroups).") ";
                  
  				  
	    
                  
                  if ($_REQUEST["folder"]!=-1 AND $_REQUEST["folder"]!="")
                  {
                    $sql .=" AND( med_logical_folder1 LIKE'" . $_REQUEST["folder"] ."%' OR med_logical_folder2 LIKE'" . $_REQUEST["folder"] ."%' OR med_logical_folder3 LIKE'" . $_REQUEST["folder"] ."%')";
                  }

                  if ($_REQUEST["type"]!=-1)
                  {
                    $sql .=" AND med_type='" . $_REQUEST["type"] ."'";
                  }

                  if ($_REQUEST["x"]!=0)
                  {
                     $sql .=" AND med_x = " . $_REQUEST["x"];
                  }

                  if ($_REQUEST["y"]!=0)
                  {
                     $sql .=" AND med_y = " . $_REQUEST["y"];
                  }
				  
				  if ($_REQUEST["doc"]!="")
				  {
				    $sql.= " AND (";
				    $_docs = explode(",",$_REQUEST["doc"]);
					foreach($_docs AS $k)
					{
					  if ($k!="")
					  {
					    $sql.="med_subtype='".$k."' OR ";
					  }
					  else
					  {
					    $sql = substr($sql,0,strlen($sql)-4);
					  }
					}
					$sql .=")";
				  }
				  
                  if ($_REQUEST["sortorder"]==1)
                  {
                    $sql .=" ORDER BY med_date DESC";
                  }
                  if ($_REQUEST["sortorder"]==2)
                  {
                     $sql .=" ORDER BY med_bez";
                  }
                  if ($_REQUEST["sortorder"]==3)
                  {
                    $sql .=" ORDER BY med_id DESC";
                  }
				  



                  $rs =$myDB->query($sql);

                  $anzahl = mysql_num_rows($rs);
                  $p = $_REQUEST["p"];
                  $reihe = 4;

                  $start = ($p-1)*(12);
                  $sql .=" LIMIT ". $start . "," . 12;
?>

    <table width="495" border="0" cellpadding="0" cellspacing="0" align="center" height="400">
      <tr>
        <td class="windowBlank" valign="top">

        <table width="100%" border="0" cellpadding="0" cellspacing="3">
        <?
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
        <?
        }
        ?>
            <td class="tableCellMedia" width="136">

            <table width="100" height="100" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" class="tableCellImage">
                  <?
                    if ($row["med_type"]==MB_IMAGE)
                    {
                      $myIMG = new PhenoTypeImage($row["med_id"]);

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
                      <a href="javascript:select_image(<?=$row["med_id"]?>,'<?=$myIMG->thumburl?>','<?=$myIMG->url?>',<?=$tx?>,<?=$ty?>);self.close();"><?
                      $myIMG->display_thumb();
                      ?>
                      </a>
                      <?
                    }
                    else
                    {
                      $icon = "binary";
                      switch (strtolower($row["med_subtype"]))
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
                        $icon = "audio";
                        break;

                        case "pdf":
                        $icon = "pdf";
                        break;

                        case "xls":
                        $icon = "excel";
                        break;

                        case "doc":
                        $icon = "word";
                        break;

                        case "ppt":
                        $icon = "powerpoint";
                        break;

                        case "sql":
                        case "txt":
                        $icon = "text";
                        break;

                      }

                      echo '<a href="javascript:select_document('.$row["med_id"].');self.close()" title="'.addslashes($row["med_bez"]).'">'.$icon."</a>";
                    }
                    ?>
                  </td>
                </tr>
              </table>
               </a><!-- Ordner: <?=$row["med_logical_folder1"]?><br>-->
                <?
                if ($row["med_type"]==MB_IMAGE)
                    {
                      echo "Gr&ouml;&szlig;e: (" . $row["med_x"] ." x " . $row["med_y"] .")<br>";
                    }
                    else
                    {
                      echo "<br>";
                    }
                ?>
                <strong><?=$myPT->cutString($row["med_bez"],32,17);?></strong><br>
                <?
                if ($row["med_type"]==MB_IMAGE){
                ?>
                <a href="javascript:select_image(<?=$row["med_id"]?>,'<?=$myIMG->thumburl?>',<?=$tx?>,<?=$ty?>);self.close();"><img src="img/b_media.gif" alt="Bild &uuml;bernehmen" width="22" height="22" border="0" align="absmiddle"> &uuml;bernehmen</a>
                <?
                }else{
                ?>
                 <a href="javascript:select_document(<?=$row["med_id"]?>);self.close();"><img src="img/b_media.gif" alt="Dokument &uuml;bernehmen" width="22" height="22" border="0" align="absmiddle"> &uuml;bernehmen</a>
                <?
                }?>
                </td>

                    <?
                    if ($aktion==0) // Zeile schliessen
                    {
                    ?>
                    </tr>
                    <?
                    }
                  }
                  if ($aktion!=0)
                  {
                    $n= $reihe-($i%$reihe);
                    for ($j=1;$j<=$n;$j++)
                    {
                    ?>
                    <td valign="top" class="tableCellMedia" width="136"></td>
                    <?
                    }
                  ?>
                  </tr>
                  <?
                  }
                  ?>


        </table>

        </td>
      </tr>
    </table>

<table width="495" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
	  </form><form action="selector_media_upload.php" method="post">
	  <input type="hidden" name="cf" value="<?=$_REQUEST["cf"]?>">
<input type="hidden" name="folder" value="<?=$_REQUEST["folder"]?>">
<input type="hidden" name="x" value="<?=$_REQUEST["x"]?>">
<input type="hidden" name="y" value="<?=$_REQUEST["y"]?>">
<input type="hidden" name="p" value="1">
<input type="hidden" name="sortorder" value="<?=$_REQUEST["sortorder"]?>">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">
<td class="windowFooterWhite"><input type="submit" class="buttonWhite" value="Hochladen" style="width:102px"></td></form>
        <td align="right" class="windowFooterWhite"><table border="0" cellpadding="0" cellspacing="1">
          <tr>
            <td align="center">Seite: </td>
            <?
            $max=ceil($anzahl/(12));
            //echo $anzahl;
            $start = $p-3;
            $stop = $p+3;
            if ($start<1){$start=1;}
            if ($stop>$max){$stop=$max;}

            $url = "?folder=" . urlencode($_REQUEST["folder"]) . "&type=" . $_REQUEST["type"] . "&sortorder=" . $_REQUEST["sortorder"]. "&cf=" .  $_REQUEST["cf"] . "&x=" .  $_REQUEST["x"] . "&y=" .  $_REQUEST["y"];

            if ($p>1)
            {
            ?>
            <td align="center"><a href="selector_media.php<?=$url?>&p=<?=$p-1?>&a=12" class="tabmenuType">zur&uuml;ck</a></td>
            <td align="center">&nbsp;</td>
            <?
            }

            for ($i=$start;$i<=$stop;$i++)
            {
              $active="";
              if ($p==$i){$active="Active";}

            ?>
            <td align="center"><a href="selector_media.php<?=$url?>&p=<?=$i?>&a=12" class="tabmenuType<?=$active?>"><?=$i?></a></td>
            <?
            }
            if ($p!=$max)
            {
            ?>
            <td align="center">&nbsp;</td>
            <td align="center"><a href="selector_media.php<?=$url?>&p=<?=$p+1?>&a=12" class="tabmenuType">vor</a></td>
            <?
            }
            ?>
          </tr>
        </table></td>
      </tr>
    </table>
</body>
</html>
