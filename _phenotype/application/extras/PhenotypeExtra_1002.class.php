<?php 
class PhenotypeExtra_1002 extends PhenotypeExtra
{

	public $id = 1002;
	public $bez = "Console";

	function displaySetup()
	{
		$this->form_checkbox("Color Coding","color","activate");
	}

	function storeConfig()
	{
		$this->store();
	}

	function displayStart()
	{
		global $myLayout;
		global $mySUser;

		if (PT_CONFIGMODE==0 OR !$mySUser->hasRight("superuser"))
		{
			$myLayout->workarea_start_draw();
			echo "This extra needs super user privileges. Also config mode must be activated.";
			$myLayout->workarea_whiteline();
			$myLayout->workarea_stop_draw();
			return;
		}

		$myLayout->workarea_start_draw();
		$scriptname = "console.inc.php";
		 ?>
		 <form action="extra_execute.php" method="post">
		 <input type="hidden" name="id" value="1002">
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody">
			<?php 
			$scriptname = TEMPPATH ."console/" . $scriptname;
			if ($this->get("color")==1)
			{
				echo $myLayout->form_HTMLTextArea("skript",$scriptname,80,30,"PHP");
			}
			else
			{
				$s=file_get_contents($scriptname);
				$s = htmlentities($s);
				echo $myLayout->workarea_form_textarea("","skript",$s,40,630);
			}
			 ?>
			</td>
            </tr>
			</table>	 
	<?php 
	// Status
	$myLayout->workarea_whiteline();
	 ?>	
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			<input name="execute" type="submit" class="buttonWhite" style="width:102px" value="Execute" onclick="javascript:return confirm('Really execute this script?')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Save changes">&nbsp;&nbsp;
            </td>
          </tr>
        </table>
	 <?php 
	 $myLayout->workarea_stop_draw();
	 ?>
	</form>	 
  <?php 
	}

	function execute($myRequest)
	{
		global $myAdm;
		global $myLayout;
		global $mySUser;

		if (PT_CONFIGMODE==0 OR !$mySUser->hasRight("superuser"))
		{
			$myLayout->workarea_start_draw();
			echo "This extra needs super user privileges. Also config mode must be activated.";
			$myLayout->workarea_whiteline();
			$myLayout->workarea_stop_draw();
			return;
		}

		if ($myRequest->check("save"))
		{
			if ($this->get("color")==1)
			{
				$code = $myAdm->decodeRequest_HTMLArea($myRequest->get("skript"));
			}
			else
			{
				$code = $myAdm->decodeRequest_TextArea($myRequest->get("skript"));
			}

			$scriptname = "console.inc.php";
			$scriptname = TEMPPATH ."console/" . $scriptname;

			$fp = fopen ($scriptname, "w");
			fputs ($fp,$code);
			fclose ($fp);
			@chmod ($scriptname,UMASK);

			$this->displayStart();
			return;
		}

		// Das Konsolenskript soll ausgeführt werden
		global $myLayout;
		$myLayout->workarea_start_draw();

		 ?>
		 <form action="extra_start.php" method="post">
		 <input type="hidden" name="id" value="1002">
			 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody">
			<iframe src="config_console_execute.php" width="640" height="600" frameborder="0"></iframe>
			</td>
            </tr>
			</table>	 
	<?php 
	// Status
	$myLayout->workarea_whiteline();
	 ?>	
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			<input name="continue" type="submit" class="buttonWhite" style="width:102px"value="Continue">&nbsp;&nbsp;
            </td>
          </tr>
        </table>
	 <?php 
	 $myLayout->workarea_stop_draw();
	 ?>
	</form>	 
	<?php 
	}

}
?>