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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Editor_Standard extends PhenotypeBackend
{
	
	public $tmxfile = "Editor";

	function execute()
	{
		Header("Location: pagegroup_select.php");
	}

	// ToDo - ungetestet !!
	function displayGlueTicketsForMediaobject($med_id)
	{
		global $mySUser;
		global $myDB;

		// TICKETTOOL
		if ($mySUser->checkRight("elm_task"))
		{
			// Meine Hinweise und Anfragen in temporaere Tabellen
			
			 $sql =  "SELECT ticket.*,ticketrequest.*,ticketmarkup.* FROM ticket LEFT JOIN ticketsubject ON ticket.sbj_id = ticketsubject.sbj_id ";
  $sql .= "LEFT JOIN (SELECT * FROM ticketrequest WHERE ticketrequest.usr_id= " .(int)$_SESSION["usr_id"].") AS ticketrequest ON ticket.tik_id = ticketrequest.tik_id ";
  $sql .= "LEFT JOIN (SELECT * FROM ticketmarkup WHERE ticketmarkup.usr_id= " .(int)$_SESSION["usr_id"].") AS ticketmarkup ON ticket.tik_id = ticketmarkup.tik_id ";
  
  $sql .= "WHERE ((tik_status = 1 ) OR (tik_status = 0 AND tik_closingdate > ". (time() - (3600 * 12 * 14)).")) AND med_id=".$med_id;


  
			
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs) != 0)
			{
?>
		<br><table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("tasks");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTask">
	<?php $this->listTickets($rs);
	?>
			</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
       <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?php



			}
		}

		// -- TICKETTOOL

	}

	// ToDo - ungetestet !!
	function displayGlueTicketsForContentRecordset($dat_id)
	{
		global $mySUser;
		global $myDB;
		// TICKETTOOL
		if ($mySUser->checkRight("elm_task"))
		{

			$sql =  "SELECT ticket.*,ticketrequest.*,ticketmarkup.* FROM ticket LEFT JOIN ticketsubject ON ticket.sbj_id = ticketsubject.sbj_id ";
  $sql .= "LEFT JOIN (SELECT * FROM ticketrequest WHERE ticketrequest.usr_id= " .(int)$_SESSION["usr_id"].") AS ticketrequest ON ticket.tik_id = ticketrequest.tik_id ";
  $sql .= "LEFT JOIN (SELECT * FROM ticketmarkup WHERE ticketmarkup.usr_id= " .(int)$_SESSION["usr_id"].") AS ticketmarkup ON ticket.tik_id = ticketmarkup.tik_id ";
  
  $sql .= "WHERE ((tik_status = 1 ) OR (tik_status = 0 AND tik_closingdate > ". (time()-(3600*12*14)) .")) AND dat_id_content=" . $dat_id;

			
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs)!=0)
			{
	?>
		<br><table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong><?php echo localeH("tasks");?></strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTask">
	<?php $this->listTickets($rs);
	?>
			</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
       <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
	<?php
			}

		}


		// -- TICKETTOOL
	}


	function displayContentRecords($rs,$caption=true,$display_content_type=false)
	{
		global $myDB;
		global $myAdm;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
    	<?php
    	if($caption==true)
    	{
		?>
			<tr>
	        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	          <tr>
	            <td width="20" class="tableHead"><?php echo localeH("ID");?></td>
	            <td width="70" class="tableHead"><?php echo localeH("Thumb");?></td>
	            <td class="tableHead"><?php echo localeH("Name");?></td>
	            <td width="120" class="tableHead"><?php echo localeH("User");?></td>
	            <td width="30" class="tableHead"><?php echo localeH("State");?></td>
	            <td width="50" align="right" class="tableHead"><?php echo localeH("Action");?></td>
	            </tr>
			  <tr>
	            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
	          </tr>
		<?php
    	}

    	$con_id=0;
    	while ($row_data=mysql_fetch_array($rs))
    	{
				$cname = "PhenotypeContent_".$row_data["con_id"];
				$myCO = new $cname;
				$myCO->init($row_data);

    		if ($con_id==0)
    		{
    			$sql_con = "SELECT * FROM content WHERE con_id = " .$row_data["con_id"];
    			$rs2 = $myDB->query($sql_con);
    			$row = mysql_fetch_array($rs2);
    		}
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["dat_id"] ?></td>
            <td class="tableBody"><?php
			if ($row["con_bearbeiten"]==1)
			{
				echo '<a href="backend.php?page=Editor,Content,edit&id='.$row_data["dat_id"].'&uid='.$row_data["dat_uid"].'">';
			}
			if ($row_data["med_id_thumb"]!=0)
			{

				$myImg = new PhenotypeImage($row_data["med_id_thumb"]);
				$myImg->display_ThumbX(60,$row_data["dat_bez"]);
			}
		  	if ($row["con_bearbeiten"]==1)
		  	{
		  		echo '</a>';
		    }
		    ?>
		    </td>
            <td class="tableBody"><?php echo $row_data["dat_bez"] ?>
			<?php
			if ($display_content_type==true)
			{
			?>
			<br>(<?php echo $row["con_bez"] ?>)
			<?php
			}
			?>
			</td>
            <td class="tableBody"><?php echo date('d.m.Y H:i',$row_data["dat_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td class="tableBody">
			<?php if ($row_data["dat_status"]==1){ 
							if($myCO->publishmode == true && $myCO->isAltered()) {
								?>
								<img src="img/i_changed.gif" alt="Status: online" width="30" height="22">
								<?php
							} else {
				?>
						

			<img src="img/i_online.gif" alt="Status: online" width="30" height="22">
			<?php 
							}
				}else{ ?>
			<img src="img/i_offline.gif" alt="Status: offline" width="30" height="22">
			<?php } ?>
			</td>
            <td align="right" nowrap class="tableBody"><?php if ($row["con_bearbeiten"]==1){ ?><a href="backend.php?page=Editor,Content,edit&id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>"><img src="img/b_edit.gif" alt="<?php echo localeH("Edit record");?>" width="22" height="22" border="0" align="absmiddle"></a> <?php } ?><?php if ($row["con_loeschen"]==1){ ?><a href="backend.php?page=Editor,Content,delete&id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>&c=<?php echo $_REQUEST["c"] ?>" onclick="return confirm('<?php echo localeH("Really delete record?");?>')"><img src="img/b_delete.gif" alt="<?php echo localeH("Delete record");?>" width="22" height="22" border="0" align="absmiddle"></a><?php } ?></td>
            </tr>
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
    	}
?>			
          <tr>
            <td colspan="6" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<?php
	}

	function displayIDLineContentRecord($myCO)
	{
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	          <tr>
	            <td width="18">
				<?php
				if ($myCO->status ==1)
				{
					if($myCO->publishmode == true && $myCO->isAltered()) {
						?>
						<img src="img/i_site_changed.gif" width="24" height="18">
						<?php
					} else {
				?>
				<img src="img/i_site_on.gif" width="24" height="18">
				<?php
					}
				}else{
				?>
				<img src="img/i_site_off.gif" width="24" height="18">
				<?php
				}
				?>
				</td>
	            <td class="windowTitle"><?php echo $myCO->id ?> <?php echo $myCO->bez ?></td>
	            <td align="right" nowrap class="windowTitle">&nbsp;
	            <?php
	            global $mySUser;
	            if ($mySUser->checkRight("elm_task"))
	            {
				?>
				<a href="javascript:ticketWizard(0,0,<?php echo $myCO->id ?>,0,0,0)"><img src="img/b_newtask.gif" alt="<?php echo localeH("Create new task");?>" title="<?php echo localeH("Create new task");?>" width="22" height="22" border="0"></a>
				<?php
	            }
				?>
				<a href="backend.php?page=Editor,Content,copy&id=<?php echo $myCO->id ?>"><img src="img/b_copy.gif" alt="<?php echo localeH("Copy record");?>" title="<?php echo localeH("Copy record");?>" width="22" height="22" border="0"></a>
				
				<?php
				$tausend = floor($myCO->id /1000);
				//$url = CACHEDEBUGURL . CACHENR . "/content/". $myCO->content_type."/".$tausend."/content_" . sprintf("%04.0f",$myCO->content_type) . "_" . sprintf("%04.0f",$myCO->id) ."_skin_debug.inc.php";
				$url = "backend.php?page=Editor,Content,debug&id=" . $myCO->id;
				if ($mySUser->checkRight("superuser")){
		   		?>
				<a href="<?php echo $url ?>" target="_blank"><img src="img/b_debug.gif" alt="<?php echo localeH("Display debug skin");?>" title="<?php echo localeH("Display debug skin");?>" width="22" height="22" border="0"></a>
				<?php
				}
				$url = "backend.php?page=Editor,Content,rollback&id=" . $myCO->id;
				if ($mySUser->checkRight("superuser") OR $mySUser->checkRight("elm_admin") OR $mySUser->checkRight("elm_rollback")){
		   		?>
				<a href="<?php echo $url ?>" ><img src="img/b_rollback.gif" alt="<?php echo localeH("Install snapshot");?>" title="<?php echo localeH("Install snapshot");?>" width="22" height="22" border="0"></a>
				<?php
				}
				?>
				<a href="http://www.phenotype-cms.de/docs.php?v=23&t=2" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>
				</td>
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
	}
	
	function displayIDLineMediaObject($myObj)
	{
		global $myPT;
  ?>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="18">
			<img src="img/i_site_on.gif" width="24" height="18">
			</td>
            <td class="windowTitle"><?php echo $myObj->id ?> <?php echo $myPT->cutString($myObj->bez,45,45); ?></td>
			<?php
			$n=strlen($myObj->bez);
			if($n>45){$n=48;}
			?>
			<td align="right" nowrap >[<?php echo $myPT->cutString($myObj->physical_folder."/".$myObj->filename,(65-$n),(65-$n)); ?>]</td>
			<td align="right" width="55" nowrap class="windowTitle"><?php
			global $mySUser;
			if ($mySUser->checkRight("elm_task"))
			{
			?>
			<a href="javascript:ticketWizard(0,0,0,<?php echo $myObj->id ?>,0,0)"><img src="img/b_newtask.gif" alt="<?php echo localeH("Create new task");?>" width="22" height="22" border="0"></a>&nbsp;
			<?php
			}
			$url = "backend.php?page=Editor,Media,rollback&id=" . $myObj->id;
				
				if ($mySUser->checkRight("superuser") OR $mySUser->checkRight("elm_admin") OR $mySUser->checkRight("elm_rollback")){
		   		?>
				<a href="<?php echo $url ?>" ><img src="img/b_rollback.gif" alt="<?php echo localeH("Install snapshot");?>" title="<?php echo localeH("Install snapshot");?>" width="22" height="22" border="0"></a>
				<?php
				}
				?><a href="#" onclick="lightbox_switch(<?php echo $myObj->id ?>,0);return false;"><img src="img/b_pinadd.gif" alt="<?php echo localeH("Put into / Take out of lightbox");?>" title="<?php echo localeH("Put into / Take out of lightbox");?>" width="22" height="22" border="0" ></a> <a href="http://www.phenotype-cms.de/docs.php?v=23&t=4" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" title="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>
			</td>
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
	}
	
	
}
?>