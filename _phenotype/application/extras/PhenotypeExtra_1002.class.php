<?
class PhenotypeExtra_1002 extends PhenotypeExtra
{

  // Konsole
  public $id = 1002;
  public $bez = "Konsole";
  
  function displaySetup()
  {
	  
	  //$this->form_textfield("Test","var",200);
	  //$this->form_textarea("test2","text");
	  //$this->form_link("Eijo","link1","zur ARD","http://www.ard.de","_self");
	  //$_options = Array("Mo","Di","Mi");
	  //$this->form_selectbox("Tag","tag",$_options);
	  $this->form_checkbox("Color Coding","color","aktivieren");
  }

  function storeConfig()
  {
	$this->store();
  }
  
  function displayStart()
  {
	 global $myLayout;
  	 $myLayout->workarea_start_draw();
 	 $scriptname = "console.inc.php";
		 ?>
		 <form action="extra_execute.php" method="post">
		 <input type="hidden" name="id" value="1002">
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tableBody">
			<?
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
	<?
	 // Status
	 $myLayout->workarea_whiteline();
	 ?>	
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			<input name="execute" type="submit" class="buttonWhite" style="width:102px" value="Ausführen" onclick="javascript:return confirm('Dieses Skript ausführen?')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;
            </td>
          </tr>
        </table>
	 <?
	 $myLayout->workarea_stop_draw();
	?>
	</form>	 
  <?
  }

  function execute($myRequest)
  {
    global $myAdm;
	
    if ($myRequest->check("save"))
	{
	  if ($myAdm->browserOK_HTMLArea() AND $this->get("color")==1)
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
	<?
	 // Status
	 $myLayout->workarea_whiteline();
	 ?>	
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			<input name="continue" type="submit" class="buttonWhite" style="width:102px"value="Weiter">&nbsp;&nbsp;
            </td>
          </tr>
        </table>
	 <?
	 $myLayout->workarea_stop_draw();
	?>
	</form>	 
	<?
  }

}
?>