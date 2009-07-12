<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr�mer.
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
 * @subpackage system
 * 
 * the main wrapper for displaying a page in phenotype backend
 */
class PhenotypeLayoutStandard
{
	// :TODO: necessary any longer?
	//var $props_topline = Array();

	var $props_tab = Array();
	var $props_iconbar = Array();
	var $props_tree = Array();

	public $component_count = 0;

	public $dhtmlwz_init = 0;

	/*
	* @var Array	shows the state of rtf editor setup.
	*
	* if an editor is included in the page, in this array is stored the following:
	* $editorInit[editorName] = 1;
	*/
	private $editorInit = Array();

	/*
	* @var	Array	contains the state of the config setup for the rtfEditor
	*
	* if an editor config is included in the page, in this array is stored the following:
	* $rtfEditorConfigs[configSet] = 1;
	*/
	private $rtfEditorConfigs = Array();

	/*
	* @var	Array	contains the state of the config setup for the codeEditor
	*
	* if an editor config is included in the page, in this array is stored the following:
	* $codeEditorConfigs[configSet] = 1;
	*/
	private $codeEditorConfigs = Array();


	/*
	* :TODO: necessary anyl longer?
	function topline_addEntry($bez,$url)
	{
	$_entry["url"]=$url;
	$_entry["bez"]=$bez;
	$this->props_topline[] = $_entry;
	}
	*/

	/**
	 * adds a entry in the tab bar of the Layout
	 *
	 * usually used system internally
	 * applications should check the tab options in ContentObjects and Components
	 *
	 * @param	string $bez	The name that is shown in the tab bar
	 * @param	string $url	The url that is linked from the tab entry
	 * @param	string $icon	The filename of the icon that is shown (e.g. 'b_konfig.gif')
	 */
	function tab_addEntry($bez, $url, $icon)
	{
		$_entry["url"]=$url;
		$_entry["bez"]=$bez;
		$_entry["icon"]=$icon;
		$this->props_tab[] = $_entry;
	}

	/**
	 * adds a  entry in the icon bar of the Layout
	 *
	 * :TODO: what is the icon bar? 
	 *
	 */
	function iconbar_addEntry($url1, $url2, $val, $alt)
	{
		$_entry["url_active"]=$url2;
		$_entry["url_inactive"]=$url1;
		$_entry["alt"]=$alt;
		$_entry["value"]=$val;
		$this->props_iconbar[] = $_entry;
	}


	/**
	 * initilizes the tab bar
	 *
	 * usually used system internally
	 */
	function tab_new()
	{
		$this->props_tab = Array();
	}

	/**
	 * initilizes the icon bar
	 *
	 * usually used system internally
	 */
	function iconbar_new()
	{
		$this->props_iconbar = Array();
	}


	/**
	 * draws the html head area of the layout
	 *
	 * @param string $modul	name of the modul
	 * @deprecated
	 */
	function header_draw($modul)
	{
		global $myAdm;
		global $myApp;
		// spaeter media nur bei redaktion media
		// site nur bei redaktion seiten
		// task nur dort, wo tickets eingebunden werden koennen
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?> - <?php echo $modul ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PT_CHARSET?>">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="task.css" rel="stylesheet" type="text/css">
<link href="css/jqueryui/phenotype/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="phenotype.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/jquery-ui.js"></script>
<?php echo $myApp->displayBackendJavascript() ?>
<script type="text/javascript" src="wz_dragdrop.js"></script> 
</head>
<?php
	}

	/**
	 * draws the top row with the backend main navi of the layout
	 *
	 * @param string $modul	name of the modul
	 * @deprecated
	 */
	function topline_draw($modul)
	{
		global $mySUser;
	?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="300" class="top"><a href="http://www.phenotype.de" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0"><img src="img/phenotype_typo.gif" width="97" height="27" border="0"></a></td>
	<td width="430" class="top"><table height="27"  border="0" cellpadding="0" cellspacing="0">
	<tr>
	<?php
	foreach ($this->props_topline as $_entry)
	{
		if ($_entry["bez"]==$modul)
		{
			?>
			<td class="kopfmenu"><a href="<?php echo $_entry["url"] ?>" class="topmenuActive"><?php echo $_entry["bez"] ?></a></td>
			<?php
		}
		else
		{
			?>
			<td class="kopfmenu"><a href="<?php echo $_entry["url"] ?>" class="topmenu"><?php echo $_entry["bez"] ?></a></td>
			<?php
		}
	}
	?>
	</tr>
	</table></td>
	<td align="right" nowrap class="top"><?php echo localeH("User");?>: <?php echo $mySUser->getName() ?><a href="logout.php"><img src="img/topbuttonclose.gif" width="30" height="27" border="0" align="absmiddle"></a></td>
	</tr>
	<tr>
	<td height="32" colspan="3" class="topShadow">&nbsp;</td>
	</tr>
	</table>
	<?php
	}


	// ab hier aktuell

	/**
	 * draws the tabs set in the layout
	 *
	 * usually used system internally in PhenotypeBackend classes
	 *
	 * @param	string $item	name of the currently active item, must refer to bez of the active entry
	 * @param	string $x	width of the content area
	 * @param	boolean $shadow_unten	show a shadow under this row?
	 * @param	boolean $shadow_rechts	show a shadow right of this row?
	 */
	function tab_draw($item,$x=680,$shadow_unten=0,$shadow_rechts=1)
	{
	?>
	<table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td class="windowTab"><table border="0" cellpadding="0" cellspacing="0">
	<tr>
	<?php
	foreach ($this->props_tab as $_entry)
	{
		if ($_entry["bez"]==$item)
		{
			?>
			<td><a href="<?php echo $_entry["url"] ?>" class="tabmenuActive"><img src="img/<?php echo $_entry["icon"] ?>" width="22" height="22" border="0" align="absmiddle"> <?php echo $_entry["bez"] ?></a></td>
			<td width="3"><img src="img/tab_vline.gif" width="3" height="22"></td>
			<?php
		}
		else
		{
			?>
			<td><a href="<?php echo $_entry["url"] ?>" class="tabmenu"><img src="img/<?php echo $_entry["icon"] ?>" width="22" height="22" border="0" align="absmiddle"> <?php echo $_entry["bez"] ?></a></td>
			<td width="3"><img src="img/tab_vline.gif" width="3" height="22"></td>
			<?php
		}

	}
	?>
	</tr>
	</table></td>
	<?php if ($shadow_rechts==1){ ?>
	<td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td><?php } ?>
	</tr>
	<?php if ($shadow_unten==1){ ?>
	<tr>
	<td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
	<td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
	</tr>
	<?php } ?>
	</table>
	<?php
	}

	/**
	 * draws the iconbar
	 *
	 * @param	string $name
	 * @param	string $val
	 * @param	string $formname
	 */
	function iconbar_draw($name,$val,$formname="form1")
	{
	?>
	<input type="hidden" name="<?php echo $name ?>" value="<?php echo $val ?>">

	<?php
	$i=0;
	foreach ($this->props_iconbar as $_entry)
	{
		$url = $_entry["url_inactive"];
		$alt = $_entry["alt"];
		$value = $_entry["value"];
		if ($value==$val)
		{
			$url =$_entry["url_active"];
		}
		?><a href="javascript:switch_<?php echo $name ?>('<?php echo $value ?>',<?php echo $i ?>,'<?php echo $_entry["url_active"] ?>');"><img src="img/<?php echo $url ?>" alt="<?php echo $alt ?>" width="22" height="22" border="0" align="absmiddle" name="<?php echo $name. "_img_".$i ?>"></a><?php
		$i++;
	}
	?>
	<script language="JavaScript">
	function switch_<?php echo $name ?>(v,i,url)
	{
		document.forms.<?php echo $formname ?>.<?php echo $name ?>.value=v;
		<?php
		for ($j=0;$j<$i;$j++)
		{
			?>
			document.forms.<?php echo $formname ?>.<?php echo $name ?>_img_<?php echo $j ?>.src='img/<?php echo $this->props_iconbar[$j]["url_inactive"]; ?>';
			<?php
		}
		?>
		fname = '<?php echo $name ?>_img_'+i;
		document.forms.<?php echo $formname ?>[fname].src='img/'+url;
	}

	</script>
	<?php
	}

	/**
	 * draws the head line of the content area with ID of current page, online state etc..
	 * this function is specific to page editing mode I guess
	 *
	 * @param	PhenotypePage $myPage
	 */
	function idline_page_draw($myPage)
	{
  ?>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="18">
			<?php
			if ($myPage->status ==1)
			{
			?>
			<img src="img/i_site_on.gif" width="24" height="18">
			<?php
			}else{
			?>
			<img src="img/i_site_off.gif" width="24" height="18">
			<?php
			}
			?>
			</td>
            <td class="windowTitle"><?php echo $myPage->id ?>.<?php echo sprintf("%02d",$myPage->ver_nr) ?> <?php echo $myPage->bez ?><?php if($myPage->ver_bez!=""){ ?> (<?php echo $myPage->ver_bez ?>)<?php } ?></td>
            <td align="right" nowrap class="windowTitle">
			<?php
			global $mySUser;
			if ($mySUser->checkRight("elm_task"))
			{
			?>
			<a href="javascript:ticketWizard(<?php echo $myPage->id ?>,<?php echo $myPage->ver_id ?>,0,0,0,0)"><img src="img/b_newtask.gif" alt="<?php echo localeH("create new task") ?>" title="<?php echo localeH("create new task") ?>" width="22" height="22" border="0"></a>
			<?php
			}
			?>
			<?php
			if ($mySUser->checkRight("elm_pageconfig"))
			{
			?>
<a href="javascript:page_copy(<?php echo $myPage->id ?>)"><img src="img/b_copy.gif" alt="<?php echo localeH("copy page") ?>" title="<?php echo localeH("copy page") ?>" width="22" height="22" border="0"></a> 
<a href="javascript:page_move(<?php echo $myPage->id ?>)"><img src="img/b_reasign.gif" alt="<?php echo localeH("move page") ?>" title="<?php echo localeH("move page") ?>" width="22" height="22" border="0"></a>
            <?php
			}
			?>
<a href="pageversion_insert.php?id=<?php echo $myPage->id ?>&ver_id=<?php echo $myPage->ver_id ?>"><img src="img/b_newversion.gif" alt="<?php echo localeH("Add New Version") ?>" title="<?php echo localeH("Add New Version") ?>" width="22" height="22" border="0"></a> 
<a href="http://www.phenotype-cms.de/docs.php?v=23&t=1" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help") ?>" title="<?php echo localeH("Help") ?>" width="22" height="22" border="0"></a></td>
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

	/**
	 * draws the head line of the content area with ID of current content object, online state etc..
	 * this function is specific to content object editing mode I guess
	 *
	 * deprecated content objects are now rendered via separate classes in PhenotypeBackend section
	 * @param	PhenotypeContent $myCO
	 */
	// :TODO: Remove outdated
	function idline_conobject_draw($myCO)
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
			?>
			<img src="img/i_site_on.gif" width="24" height="18">
			<?php
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
			<a href="javascript:ticketWizard(0,0,<?php echo $myCO->id ?>,0,0,0)"><img src="img/b_newtask.gif" alt="Neue Aufgabe einstellen" title="Neue Aufgabe einstellen" width="22" height="22" border="0"></a>
			<?php
            }
			?>
			<a href="content_copy.php?id=<?php echo $myCO->id ?>"><img src="img/b_copy.gif" alt="Datensatz kopieren" title="Datensatz kopieren" width="22" height="22" border="0"></a>
			
			<?php
			$tausend = floor($myCO->id /1000);
			//$url = CACHEDEBUGURL . CACHENR . "/content/". $myCO->content_type."/".$tausend."/content_" . sprintf("%04.0f",$myCO->content_type) . "_" . sprintf("%04.0f",$myCO->id) ."_skin_debug.inc.php";
			$url = "content_debug.php?id=" . $myCO->id;
			if ($mySUser->checkRight("superuser")){
	   		?>
			<a href="<?php echo $url ?>" target="_blank"><img src="img/b_debug.gif" alt="Debug-Skin anzeigen" title="Debug-Skin anzeigen" width="22" height="22" border="0"></a>
			<?php
			}
			?>
			<a href="http://www.phenotype-cms.de/docs.php?v=23&t=2" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>
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

	/**
	 * draws the head line of the content area with ID of current media object, online state etc..
	 * this function is specific to media object editing mode I guess
	 *
	 * deprecated media objects are now rendered via separate classes in PhenotypeBackend section
	 * @param	PhenotypeMediaObject $myObj
	 */
	// ToDO: Remove, outdatetd
	function idline_mediaobject_draw($myObj)
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
			<a href="javascript:ticketWizard(0,0,0,<?php echo $myObj->id ?>,0,0)"><img src="img/b_newtask.gif" alt="neue Aufgabe einstellen" width="22" height="22" border="0"></a>&nbsp;
			<?php
			}
			?><a href="http://www.phenotype-cms.de/docs.php?v=23&t=4" target="_blank"><img src="img/b_help.gif" alt="<?php echo localeH("Help");?>" width="22" height="22" border="0"></a>
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

	/**
	 * draws the html code for the start of the workarea in backend
	 *
	 * @param	integer $x	width of the workarea
	 */
	function workarea_start_draw($x=680)
	{
  ?>
  <table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window">
  <?php
	}

	/**
	 * draws the html code for the end of the workarea in backend
	 *
	 */
	function workarea_stop_draw()
	{
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

	/**
	 * draws a row in the workarea and sorrounds the content with the necessary html code
	 *
	 * @param	string $bez	the label that goes in the left column
	 * @param	string $content	all the stuff that goes into the big column on the right
	 */
	function workarea_row_draw($bez,$content)
	{
		global $mySmarty;
		$mySmarty->template_dir = SYSTEMPATH  . "templates/";
		$mySmarty->compile_dir = SMARTYCOMPILEPATH;
		$mySmarty->assign("bez",$bez);
		$mySmarty->assign("content",$content);
		$mySmarty->display("workarea_row.tpl");
	}

	/**
	 * draws a spacer row in the workarea
	 *
	 */
	function workarea_whiteline()
	{
  ?>
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr>
       <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
     </tr>
	 </table>
  <?php
	}

	/**
	 * draws a selector for inserting new components
	 * usually used between 2 components and at start/end of the page
	 *
	 * @param	integer $toolkit	number of the component toolkit (bausteingruppe)
	 * @param	integer $pos	unique number of this selector, necessary for JS code
	 */
	function workarea_componentselector_draw($toolkit,$pos)
	{
	?>
  <tr>
            <td nowrap width="160" class="narrowingLeft"><a name="pos<?php echo $this->component_count ?>"></a><img src="img/i_add_item.gif" width="22" height="22" align="absbottom">
                <select name="addtool_<?php echo $pos ?>" onchange="addnew(<?php echo $pos ?>)" class="listmenu" style="width:130px">
                  <option selected><?php echo localeH("Insert component");?></option>
				  <?php @readfile(APPPATH . "components/toolkit" . $toolkit . ".inc.html"); ?>
              </select></td>
            <td width="18"><img src="img/narrowing.gif" width="18" height="26" align="absbottom"></td>
            <td width="*" class="narrowingRight">&nbsp;</td>
            <td width="45" class="narrowingRight">&nbsp;</td>
          </tr>
	<?php
	}

	/**
	 * renders a textfield in the workarea
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	string $val	value of the field content
	 * @param integer $x	width of the field in px
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_text($bez,$name,$val,$x=300,$br=1)
	{
		$html="";
		if($bez!=""){$html = $bez.'<br>';}
		//$html .= '<input type="text" name="'.$name .'" style="width: '.$x.'px" class="input" value="'.htmlentities($val,null,PT_CHARSET).'">';
		$html .= '<input type="text" name="'.$name .'" style="width: '.$x.'px" class="input" value="'.htmlentities(stripcslashes($val),ENT_COMPAT,PT_CHARSET).'">'; //added 08/05/27 by Dominique B�s
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * renders a passwordfield in the workarea
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	string $val	value of the field content
	 * @param integer $x	width of the field in px
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_password($bez,$name,$val,$x=300,$br=1)
	{
		$html="";
		if($bez!=""){$html = $bez.'<br>';}
		$html .= '<input type="password" name="'.$name .'" style="width: '.$x.'px" class="input" value="'.htmlentities($val,null,PT_CHARSET).'">';
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * renders a hidden form field in the workarea
	 *
	 * does not directly display the element
	 *
	 * @param string $name	name of the field in html form
	 * @param	string $val	value of the field content
	 *
	 * @return string		html code output
	 */
	function workarea_form_hidden($name,$val)
	{
		$html="";
		$html .= '<input type="hidden" name="'.$name .'"  value="'.htmlentities($val).'">';
		return $html;
	}

	/**
	 * renders a textarea in the workarea
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	string $val	value of the field content
	 * @param integer $r	number of rows the field shows
	 * @param integer $x	width of the field in px
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_textarea($bez,$name,$val,$r=4,$x=400,$br=1)
	{
		$html="";
		if($bez!=""){$html = $bez.'<br>';}
		// Hier war eben noch hard
		$html .= '<textarea name="'.$name .'" rows="'.$r.'"style="width: '.$x.'px" class="input" wrap="physical">'.$val.'</textarea>';
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * renders a select box in the workarea from options html code
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	string $options	html code for the options of the select field
	 * @param integer $x	width of the field in px
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_select($bez,$name,$options,$x=200,$br=1)
	{
		$html="";
		if($bez!=""){$html = $bez.'<br>';}
		$html .='<select name="'.$name .'" style="width: '.$x.'px" class="listmenu" >'.$options.'</select>';
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * renders a select box in the workarea from an array of options
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	string $value	key of the selected option 
	 * @param	string $_options	named array with the options, key is the option key, value the display name of the option
	 * @param integer $x	width of the field in px
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_select2($bez,$name,$value,$_options,$x=200,$br=1)
	{
		$html="";
		$options="";
		if($bez!=""){$html = $bez.'<br>';}
		foreach ($_options AS $k=>$v)
		{
			$selected ="";
			if ($value==$k)
			{
				$selected = 'selected="selected"';
			}
			$options.='<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		}

		$html .='<select name="'.$name .'" style="width: '.$x.'px" class="listmenu" >'.$options.'</select>';
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * renders a checkbox in the workarea
	 *
	 * does not directly display the element
	 *
	 * @param	string $bez	label for the field
	 * @param string $name	name of the field in html form
	 * @param	boolean	$val	should the checkbox be checked?
	 * @param	string $text	text displayed with the checkbox
	 * @param boolean $br	should there be an <br> tag after the field?
	 *
	 * @return string		html code output
	 */
	function workarea_form_checkbox($bez,$name,$val,$text,$br=1)
	{
		$html="";
		if($bez!=""){$html = $bez.'<br>';}
		$checked="";
		if ($val==1){$checked="checked";}
		$html .= '<input type="checkbox" name="'.$name .'" id="'.$name .'" value="1" '. $checked.'> <label for="'.$name .'">'.$text.'</label>';
		if ($br==1){$html.="<br>";}
		return $html;
	}

	/**
	 * initializes the javascript editor for RichText or HTML
	 *
	 * @param int $mode	PT_EDITOR_RTF or PT_EDITOR_CODE. only use these constants
	 * @param	string $configSet	refers to the configset used for this editor instance
	 *
	 */
	function init_js_editor($mode, $configSet)
	{
		global $myPT;
		global $myLog;

		$key = '';
		if ($mode == PT_EDITOR_RTF)
		{
			$key = "backend.rtf_editor";
			$js_var = "pt_rtf_opts";
			$configArray = $this->rtfEditorConfigs;
		} elseif ($mode == PT_EDITOR_CODE)
		{
			$key = "backend.code_editor";
			$js_var = "pt_code_opts";
			$configArray = $this->codeEditorConfigs;
		} else
		{
			$myLog->log("initJSEditor: method call without mode not valid!", PT_LOGFACILITY_SYS, PT_LOGLVL_ERROR);
			return false;
		}

		// ** load the configured editor if necessary
		if (! (array_key_exists($myPT->getPref($key), $this->editorInit) && ($this->editorInit[$myPT->getPref($key)] == 1)) )
		{
			if ($myPT->getPref($key) == PT_RTF_EDITOR_TINYMCE)
			{ // TinyMCE
?>
	<!-- TinyMCE -->
	<script type="text/javascript" src="<?php echo(ADMINURL); ?>lib/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<!-- /TinyMCE -->
<?php
			} elseif ($myPT->getPref($key) == PT_RTF_EDITOR_FCKEDITOR)
			{ // FCKEditor
?>
	<!-- FCKEditor -->
	<script type="text/javascript" src="<?php echo(ADMINURL); ?>lib/fckeditor/fckeditor/fckeditor.js"></script>
	<!-- /FCKEditor -->
<?php
			} else
			{
?>
<!-- RTF-Editor <?php echo($myPT->getPref($key)); ?> is not defined -->
<?php
			}
			$this->editorInit[$myPT->getPref($key)] = 1;

		}

		// now setup the configurations array in JS
		if (count($configArray) == 0)
		{
?>
<script type="text/javascript">
var <?php echo($js_var); ?> = Object();
</script>
<?php
		}

		// ** get config for tinyMCE, fckEditor uses external config files directly
		if (! array_key_exists($configSet, $configArray) && ($myPT->getPref($key) == PT_RTF_EDITOR_TINYMCE))
		{
?>
	<script type="text/javascript" src="<?php echo(SERVERURL . $myPT->getPref($key .'_config_path') . $configSet .'.js'); ?>"></script>
<?php
$configArray[$configSet] = 1;
		}
	}

	/**
	 * displays a textarea for code input in the workarea
	 *
	 * @param string $name	name of the field in html form
	 * @param	string $filename	filename to display in the editor
	 * @param	integer $cols	number of characters per line the field shows
	 * @param integer $rows	number of rows the field shows
	 * @param string $mode	mode for the editor
	 * @param boolean $x	the width of the field in px
	 *
	 */
	function form_HTMLTextarea($name, $filename, $cols, $rows, $mode="PHP", $x=640)
	{
		global $myAdm;
		global $myPT;

		$configSet = 'default'; // could be used to have different setups for code editors. currently not used and no argument for that, but build into code.

		$content = $myAdm->get_filecontents_highlighted($filename);

		$this->init_js_editor(PT_EDITOR_CODE, $configSet);

		// ** now render and setup the particular editor field
?>
	<textarea cols="<?php echo $cols ?>" rows="<?php echo $rows ?>" wrap="physical" name="<?php echo $name ?>" id="<?php echo $name ?>" style="width: <?php echo $x ?>px" class="input RichText"><?php echo $content ?></textarea>
<?php
if ($myPT->getPref("backend.code_editor") == PT_RTF_EDITOR_TINYMCE)
{
?>
	<script type="text/javascript">
	var myOpts = pt_code_opts["<?php echo $configSet ?>"];
	myOpts.mode = "exact";
	myOpts.theme = "advanced";
	myOpts.elements = "<?php echo $name ?>";
	tinyMCE.init(myOpts);
	</script>
<?php
} elseif ($myPT->getPref("backend.code_editor") == PT_RTF_EDITOR_FCKEDITOR)
{
?>
	<script type="text/javascript">
	var oFCKeditor = new FCKeditor( '<?php echo $name ?>' ) ;
	oFCKeditor.BasePath	= '<?php echo ADMINURL ?>lib/fckeditor/fckeditor/' ;
	oFCKeditor.Width = <?php echo $x ?>;
	oFCKeditor.Height = <?php echo $rows*17 ?> ;
	oFCKeditor.Config["CustomConfigurationsPath"] = "<?php echo(SERVERURL . $myPT->getPref('backend.code_editor_config_path') . $configSet .'.js'); ?>";
	oFCKeditor.ReplaceTextarea() ;
	</script>
<?php
}
	}


	/**
	 * displays a textarea for richtext input in the workarea
	 *
	 * @param string $name	name of the field in html form
	 * @param	string $val	value of the 
	 * @param	integer $cols	number of characters per line the field shows
	 * @param integer $rows	number of rows the field shows
	 * @param boolean $x	the width of the field in px
	 * @param string $configSet	refers a predefined configSet for the editor. The string works as part of a filename of a js file with editor opitons
	 *
	 */
	function form_Richtext($name, $val, $cols=80, $rows=10, $x=410, $configSet="default")
	{
		global $myPT;

		$val = htmlentities($val);

		$this->init_js_editor(PT_EDITOR_RTF, $configSet);


		// ** now render and setup the particular editor field
?>
	<textarea cols="<?php echo $cols ?>" rows="<?php echo $rows ?>" wrap="physical" name="<?php echo $name ?>" id="<?php echo $name ?>" style="width: <?php echo $x ?>px" class="input RichText"><?php echo $val ?></textarea>
<?php
if ($myPT->getPref("backend.rtf_editor") == PT_RTF_EDITOR_TINYMCE)
{
?>
	<script type="text/javascript">
	var myOpts = pt_rtf_opts["<?php echo $configSet ?>"];
	myOpts.mode = "exact";
	myOpts.theme = "advanced";
	myOpts.elements = "<?php echo $name ?>";
	tinyMCE.init(myOpts);
	</script>
<?php
} elseif ($myPT->getPref("backend.rtf_editor") == PT_RTF_EDITOR_FCKEDITOR)
{
?>
	<script type="text/javascript">
	var oFCKeditor = new FCKeditor( '<?php echo $name ?>' ) ;
	oFCKeditor.BasePath	= '<?php echo ADMINURL ?>lib/fckeditor/fckeditor/' ;
	oFCKeditor.Width = <?php echo $x ?>;
	oFCKeditor.Height = <?php echo $rows*17 ?> ;
	oFCKeditor.Config["CustomConfigurationsPath"] = "<?php echo(SERVERURL . $myPT->getPref('backend.rtf_editor_config_path') . $configSet .'.js'); ?>";
	oFCKeditor.ReplaceTextarea() ;
	</script>
<?php
}
	}

	/**
	 * :TODO: remove
	 *
	 * @deprecated
	 *
	 */
	function overview_content_draw($sql,$con_id,$mode=1,$html="")
	{
		global $myDB;
		global $myAdm;
		if ($con_id!=-1)
		{
			$sql_con = "SELECT * FROM content WHERE con_id = " .$con_id;
			$rs = $myDB->query($sql_con);
			$row = mysql_fetch_array($rs);
		}
  ?>

	<table width="680" border="0" cellpadding="0" cellspacing="0">
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

		  $rs_data = $myDB->query($sql);
		  while ($row_data=mysql_fetch_array($rs_data))
		  {
		  	if ($con_id==-1)
		  	{
		  		$sql_con = "SELECT * FROM content WHERE con_id = " .$row_data["con_id"];
		  		$rs = $myDB->query($sql_con);
		  		$row = mysql_fetch_array($rs);
		  	}
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["dat_id"] ?></td>
            <td class="tableBody">
			<?php if ($row["con_bearbeiten"]==1){ ?><a href="content_edit.php?id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>"><?php } ?>
			<?php
			if ($row_data["med_id_thumb"]!=0)
			{

				$myImg = new PhenotypeImage($row_data["med_id_thumb"]);
				$myImg->display_ThumbX(60,$row_data["dat_bez"]);
			}
		  ?>
		  <?php if ($row["con_bearbeiten"]==1){ ?>
		  </a>
		  <?php } ?>
		  </td>
            <td class="tableBody"><?php echo $row_data["dat_bez"] ?>
			<?php
			if ($con_id==-1)
			{
			?>
			<br>(<?php echo $row["con_bez"] ?>)
			<?php
			}
			?>
			</td>
            <td class="tableBody"><?php echo date('d.m.Y H:i',$row_data["dat_date"]) ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td class="tableBody">
			<?php if ($row_data["dat_status"]==1){ ?>
			<img src="img/i_online.gif" alt="Status: online" width="30" height="22">
			<?php }else{ ?>
			<img src="img/i_offline.gif" alt="Status: offline" width="30" height="22">
			<?php } ?>
			</td>
            <td align="right" nowrap class="tableBody"><?php if ($row["con_bearbeiten"]==1){ ?><a href="content_edit.php?id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>"><img src="img/b_edit.gif" alt="<?php echo localeH("Edit record");?>" width="22" height="22" border="0" align="absmiddle"></a> <?php } ?><?php if ($row["con_loeschen"]==1){ ?><a href="content_delete.php?id=<?php echo $row_data["dat_id"] ?>&uid=<?php echo $row_data["dat_uid"] ?>&c=<?php echo $_REQUEST["c"] ?>" onclick="return confirm('<?php echo localeH("Really delete record?");?>')"><img src="img/b_delete.gif" alt="<?php echo localeH("Delete record");?>" width="22" height="22" border="0" align="absmiddle"></a><?php } ?></td>
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
	<table width="680" border="0" cellpadding="0" cellspacing="0">
	 <?php echo $html ?>
	 <?php if ($row["con_anlegen"]==1){ ?>
      <tr>
        <td class="windowFooterGrey2"><a href="content_insert.php?id=<?php echo $con_id ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new record");?></a></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
	  <?php } ?>
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br>
	<?php
	}


	function displayTreeNavi($myTree,$ext_id=-1,$x=260)
	{
		$myTree->buildtree();
  ?>
<table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowMenu">
		<?php
		for ($i=0;$i<count($myTree->_flattree);$i++)
		{
			$_node = $myTree->_flattree[$i];
			if ($_node["next"]==1){$open="_open";}else{$open="";}
		?>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="level0<?php echo $_node["ebene"] ?>">
			<?php if ($_node["ext_id"]==$ext_id){ ?>
			<img src="img/b_arrow_passive<?php echo $open ?>.gif" width="10" height="9">
			<a href="<?php echo $_node["url"] ?>"><strong class="blue"><?php echo $_node["bez"] ?></strong></a>
            <?php }else{ ?>
			<img src="img/b_arrow_passive<?php echo $open ?>.gif" width="10" height="9">
            <a href="<?php echo $_node["url"] ?>"><?php echo $_node["bez"] ?></a>
            <?php } ?>
            </td>
          </tr>
        </table>
		<?php
		}
		?>
</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>      
			
        
  <?php
	}

	function displayTreeNaviNoShadow($myTree,$ext_id=-1,$x=260)
	{
		$myTree->buildtree();
  ?>
<table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowMenu">
		<?php
		for ($i=0;$i<count($myTree->_flattree);$i++)
		{
			$_node = $myTree->_flattree[$i];
			if ($_node["next"]==1){$open="_open";}else{$open="";}
		?>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="level0<?php echo $_node["ebene"] ?>">
			<?php if ($_node["ext_id"]==$ext_id){ ?>
			<img src="img/b_arrow_passive<?php echo $open ?>.gif" width="10" height="9">
			<a href="<?php echo $_node["url"] ?>"><strong class="blue"><?php echo $_node["bez"] ?></strong></a>
            <?php }else{ ?>
			<img src="img/b_arrow_passive<?php echo $open ?>.gif" width="10" height="9">
            <a href="<?php echo $_node["url"] ?>"><?php echo $_node["bez"] ?></a>
            <?php } ?>
            </td>
          </tr>
        </table>
		<?php
		}
		?>
</td>
      </tr>
    </table>      
			
        
  <?php
	}


	function workarea_form_image($name,$img_id,$folder="-1",$changefolder=1,$x=0,$y=0,$alt="",$align="left",$mode=1,$version=false)
	{
		global $myDB;
		global $myPT;
		// Den �bergebenen Folder normalisieren
		$myMB = new PhenotypeMediabase();
		$folder = $myMB->rewriteFolder($folder);

		switch ($align)
		{
			case "":
				$align="left";
				break;
			case "links":
				$align="left";
				break;
			case "rechts":
				$align="right";
				break;
			case "mittig":
				$align="center";
				break;
		}
		$myPT->startBuffer();
		if ($img_id==0)
		{

			$style='style="visibility: hidden;display:none"'
	 ?>
	   <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap><a class="bausteineLink" href='javascript:selector_image(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,<?php echo $x ?>,<?php echo $y ?>)'><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Assign Image");?></a></td>
</tr>
</table>
	 <?php
		}
		else
		{
			$style="";
      ?>
<table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_image(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,<?php echo $x ?>,<?php echo $y ?>)'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Change Image");?></a></td>
                  </tr>
                </table>
       <?php
		}
	   ?>				
       <div id="<?php echo $name ?>panel" <?php echo $style ?>>
	   <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_image('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Image");?></a></td>
                  </tr>
                </table>
     <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
       <?php
       $myImg = new PhenotypeImage($img_id);
       $myImg->fname=$name . "img_id_image";
	   ?>
	   <a href="<?php echo MEDIABASEURL . $myImg->physical_folder ?>/<?php echo $myImg->filename ?>" target="_blank" id="<?php echo $name ."link_image" ?>">
	   <?php
	   $myImg->style="float:left;vertical-align:middle";
	   $myImg->display_thumb($alt);
       ?></a><br/> <a href="backend.php?page=Editor,Media,edit&id=<?php echo $myImg->id ?>" id="<?php echo $name ."editlink_image" ?>"><img src="img/b_edit_b.gif" alt="" style="padding-top:5px;padding-left:5px" border="0"/></a>
     </td></tr></table>
<?php if ($mode==2){ ?>
 <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
				<?php echo localeH("Alternate");?>:<br>
				<input type="text" name="<?php echo $name ?>img_alt" style="width:200px" class="input" value="<?php echo htmlentities($alt) ?>"><br>
     <?php
     echo localeH("Alignment").":<br>";
     $this->iconbar_new();
     $this->iconbar_addentry("b_textpic_left.gif","b_textpic_left_activ.gif","left",locale("msg_align_left"));
     $this->iconbar_addentry("b_picture_center.gif","b_picture_center_active.gif","center",locale("msg_align_center"));
     $this->iconbar_addentry("b_textpic_right.gif","b_textpic_right_active.gif","right",locale("msg_align_right"));
     $this->iconbar_draw($name."img_align",$align,"editform");
     //$this->workarea_form_iconbar($name_org."bildausrichtung",$align);
     echo "<br>";
     if ($version !== false) {
     echo locale("Version")?>:<br/>
    <select name="<?php echo $name ?>version" class="listmenu">
    <?php
    $html = '<option value="0" >Original</option>';
    $sql = "SELECT * FROM mediaversion WHERE med_id = ".$myImg->id." ORDER BY ver_bez, ver_id DESC";
    $rs = $myDB->query($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$selected ="";
    	if ($row["ver_id"] == $version)
    	{
    		$selected = "selected";
    	}
    	$html .='<option value="'. $row["ver_id"] .'" ' . $selected . '>' . $row["ver_bez"] . '</option>';
    }
    echo $html;
     ?>				 
    </select>
    <?php } ?>
      </td></tr></table>
 <?php }else{ 
 	if ($version !== false) {
 	// $mode=1 ?>
  	<table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
				<?php echo locale("Version")?>:<br/>
    <select name="<?php echo $name ?>version" class="listmenu">
    <?php
    $html = '<option value="0" >Original</option>';
    $sql = "SELECT * FROM mediaversion WHERE med_id = ".$myImg->id." ORDER BY ver_bez, ver_id DESC";
    $rs = $myDB->query($sql);
    while ($row = mysql_fetch_array($rs))
    {
    	$selected ="";
    	if ($row["ver_id"] == $version)
    	{
    		$selected = "selected";
    	}
    	$html .='<option value="'. $row["ver_id"] .'" ' . $selected . '>' . $row["ver_bez"] . '</option>';
    }
    echo $html. '</td></tr></table>';
 	}
 	?>
 	<?php }?>
 </div>

<input name="<?php echo $name ?>img_id" type="hidden" value="<?php echo $img_id ?>">
<input name="<?php echo $name ?>med_id" type="hidden" value="<?php echo $img_id ?>">
<?php
return $myPT->stopBuffer();
	}

	function workarea_form_imageupload($name,$img_id,$alt,$align,$mode=2)
	{
		global $myDB;
		global $myPT;
		$myPT->startBuffer();
     ?>			
   	<?php if ($img_id==0)
   	{

   		$style='style="visibility: hidden;display:none"';
   	}
   	else
   	{
   		$style="";
   	}
	   ?>
       <div id="<?php echo $name ?>panel" <?php echo $style ?>>
	   <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_image('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Image");?></a></td>
                  </tr>
                </table>
     <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
       <?php
       $myImg = new PhenotypeImage($img_id);
       $myImg->fname=$name . "img_id_image";
	   ?>
	   <a href="<?php echo MEDIABASEURL . $myImg->physical_folder ?>/<?php echo $myImg->filename ?>" target="_blank">
	   <?php
	   $myImg->display_thumb($alt);
       ?></a>
     </td></tr></table>
<?php if ($mode==2){ ?>
 <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
				<?php echo localeH("Alternate");?>:<br>
				<input type="text" name="<?php echo $name ?>img_alt" style="width:200px" class="input" value="<?php echo htmlentities($alt) ?>"><br>
     <?php
     echo localeH("Alignment").":<br>";
     $this->iconbar_new();
     $this->iconbar_addentry("b_textpic_left.gif","b_textpic_left_activ.gif","left",locale("msg_align_left"));
     $this->iconbar_addentry("b_picture_center.gif","b_picture_center_active.gif","center",locale("msg_align_center"));
     $this->iconbar_addentry("b_textpic_right.gif","b_textpic_right_active.gif","right",locale("msg_align_right"));
     $this->iconbar_draw($name."align",$align,"editform");
     echo "<br>";
?>
 </td></tr></table>
 <?php } ?>
 </div>
  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap>
  <?php if ($img_id<>""){echo"<br>";} ?>
  <input name="<?php echo $name ?>userfile" type="file" class="input">
  </td>
</tr>
</table>	   	

<input name="<?php echo $name ?>img_id" type="hidden" value="<?php echo $img_id ?>">
<?php
return $myPT->stopBuffer();
	}

	function workarea_form_document($name,$med_id)
	{

		global $myDB;
		global $myPT;
		$myPT->startBuffer();

		if ($med_id!=0)
		{

      ?>
<table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_document(
"editform","<?php echo $name ?>","-1",1,"")'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> Dokument &auml;ndern</a></td>
                  </tr>
                </table>
                <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_document('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Document");?></a></td>
                  </tr>
                </table>
     <table id="<?php echo $name ?>panel" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
                <?php
                $myDoc = new PhenotypeDocument($med_id);
                if ($myDoc->id!=0)
                {
                	echo '<a href="'.$myDoc->url.'" target="_blank">Dokument Nr. ' . $myDoc->id . " - " . $myDoc->bez."</a>";
                	echo '<a href="backend.php?page=Editor,Media,edit&id='.$myDoc->id .'"><img src="img/b_edit_b.gif" alt="" style="padding-top:0px;padding-left:5px;vertical-align:bottom" border="0"/></a>';
                }
                else
                {
                	echo localeH("msg_selected_image_not_found",Array($med_id));
                }
                ?>
     </td></tr></table>

    <?php }else{ ?>

  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap><a class="bausteineLink" href='javascript:selector_document(
"editform","<?php echo $name ?>","-1",1,"")'><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Assign Document");?></a></td>
</tr>
</table>
     <table id="<?php echo $name ?>panel" style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
               <?php echo localeH("Document assigned.");?>
     </td></tr></table>
<?php
    }
?>
<input name="<?php echo $name ?>med_id" type="hidden" value="<?php echo $med_id ?>">
<input name="<?php echo $name ?>img_id" type="hidden" value="0">
<?php
return $myPT->stopBuffer();
	}

	function workarea_form_document2($name,$med_id,$folder,$changefolder,$doctype)
	{

		// Den �bergebenen Folder normalisieren
		$myMB = new PhenotypeMediabase();
		$folder = $myMB->rewriteFolder($folder);

		if (is_array($doctype))
		{
			$doctype2="";
			foreach($doctype AS $k)
			{
				$doctype2 .=$k.",";
			}
		}
		else
		{
			$doctype2="";
		}
		global $myDB;
		global $myPT;
		$myPT->startBuffer();

		if ($med_id!=0)
		{

      ?>
<table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_document(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,"<?php echo $doctype2 ?>")'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Change Document");?></a></td>
                  </tr>
                </table>
                <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_document('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Document");?></a></td>
                  </tr>
                </table>
     <table id="<?php echo $name ?>panel" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
                <?php
                $myDoc = new PhenotypeDocument($med_id);
                if ($myDoc->id!=0)
                {
                	echo '<a href="'.$myDoc->url.'" target="_blank">Dokument Nr. ' . $myDoc->id . " - " . $myDoc->bez."</a>";
                	echo '<a href="backend.php?page=Editor,Media,edit&id='.$myDoc->id .'"><img src="img/b_edit_b.gif" alt="" style="padding-top:0px;padding-left:5px;vertical-align:bottom" border="0"/></a>';
                }
                else
                {
                	echo localeH("msg_selected_image_not_found",Array($med_id));
                }
                ?>
     </td></tr></table>

    <?php }else{ ?>

  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap><a class="bausteineLink" href='javascript:selector_document(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,"<?php echo $doctype2 ?>")'><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Assign Document");?></a></td>
</tr>
</table>
     <table id="<?php echo $name ?>panel" style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
               <?php echo localeH("Document assigned.");?>
     </td></tr></table>
<?php
    }
?>
<input name="<?php echo $name ?>med_id" type="hidden" value="<?php echo $med_id ?>">
<input name="<?php echo $name ?>img_id" type="hidden" value="0">
<?php
return $myPT->stopBuffer();
	}


	function workarea_form_media($name,$med_id,$folder,$changefolder,$doctype)
	{

		// Den �bergebenen Folder normalisieren
		$myMB = new PhenotypeMediabase();
		$folder = $myMB->rewriteFolder($folder);
		$med_id = (int)$med_id;

		if (is_array($doctype))
		{
			$doctype2="";
			foreach($doctype AS $k)
			{
				$doctype2 .=$k.",";
			}
		}
		else
		{
			$doctype2="";
		}
		global $myDB;
		global $myPT;
		$myPT->startBuffer();
		$doc_id=0;
		if ($med_id!=0)
		{

      ?>
<table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_media(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,"<?php echo $doctype2 ?>")'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Change Document/Image");?></a></td>
                  </tr>
                </table>
                <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_media('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Document/Image");?></a></td>
                  </tr>
                </table>
                <?php
                $myDoc = new PhenotypeDocument($med_id);
                $doc_id = $myDoc->id;
                if ($doc_id!=0) // Dokument und kein Bild
                {
                ?>
     <table id="<?php echo $name ?>panel" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
                <?php
                $myDoc = new PhenotypeDocument($med_id);
                echo '<a href="'.$myDoc->url.'" target="_blank">'. localeH("Document No.") .' ' . $myDoc->id . " - " . $myDoc->bez."</a>";
                ?>
     </td></tr></table>
				<?php
                }
                else
                { // Erst mal nur zu Debugzwecken
                	?>
                	<table id="<?php echo $name ?>panel" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
                	</table>
    
                	<?php
                }
				?>
    <?php }else{ ?>

  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap><a class="bausteineLink" href='javascript:selector_media(
"editform","<?php echo $name ?>","<?php echo $folder ?>",<?php echo $changefolder ?>,"<?php echo $doctype2 ?>")'><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Assign Document/Image");?></a></td>
</tr>
</table>
     <table id="<?php echo $name ?>panel" style="visibility: hidden;display:none" width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
               <?php echo localeH("Document/Image assigned.");?>
     </td></tr></table>
<?php
    }
?>

     <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
       <?php
       $myImg = new PhenotypeImage($med_id);
       $img_id = $myImg->id;
       $myImg->fname=$name . "img_id_image";
	   ?>
	   <a href="<?php echo MEDIABASEURL . $myImg->physical_folder ?>/<?php echo $myImg->filename ?>" target="_blank">
	   <?php
	   $myImg->display_thumb($alt);
       ?></a>
     </td></tr></table>   
<input name="<?php echo $name ?>med_id" type="hidden" value="<?php echo $doc_id ?>">
<input name="<?php echo $name ?>img_id" type="hidden" value="<?php echo $img_id ?>">
<?php
return $myPT->stopBuffer();
	}


	function workarea_form_documentupload($name,$med_id)
	{
		global $myDB;
		global $myPT;
		$myPT->startBuffer();
     ?>			
   	<?php if ($med_id!=0)
   	{
?>
       <div id="<?php echo $name ?>panel">
	   <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_document('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Remove Document");?></a></td>
                  </tr>
                </table>
     <table width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground">
              <tr>
                <td nowrap>
     <?php
     $myDoc = new PhenotypeDocument($med_id);
     echo localeH("Document No.") . " ". $myDoc->id . " - " . $myDoc->bez;
                ?>
 </td></tr></table>
 </div>
<?php
   	}
	   ?>

  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap>
  <?php if ($med_id<>""){echo"<br>";} ?>
  <input name="<?php echo $name ?>userfile" type="file" class="input">
  </td>
</tr>
</table>	   	

<input name="<?php echo $name ?>med_id" type="hidden" value="<?php echo $med_id ?>">
<?php
return $myPT->stopBuffer();
	}

	function workarea_form_link($name,$bez,$url,$target,$linktext=false,$linksource=false,$popup_x=false,$popup_y=false,$linktype=false,$linktype_options=false,$pageselector = true)
	{
		global $myDB;
		global $myPT;
		$myPT->startBuffer();

		if ($bez!="" OR $url !="" OR $linktext !="") // we have some info
		{
			$style="";
      ?>
<?php if ($pageselector==true){ ?>
<table width="408" border="0" cellpadding="0" cellspacing="0" id="<?php echo $name ?>select">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_link(
"editform","<?php echo $name ?>")'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Select Link");?></a></td>
                  </tr>
                </table>
                <?php }else{ ?>
                <table width="408" border="0" cellpadding="0" cellspacing="0" style="visibility: hidden;display:none" id="<?php echo $name ?>select"></table>
                <?php } ?>
                <table width="408" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td nowrap><a href="javascript:reset_link('editform','<?php echo $name ?>');" class="bausteineLink"><img src="img/b_minus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Reset Link");?></a></td>
                  </tr>
                </table>
    <?php }
    else // No info entered yet
    {

    	$style='style="visibility: hidden;display:none"';
	?>

  <table width="408" border="0" cellpadding="0" cellspacing="0" >
  <tr>
  <td nowrap><a class="bausteineLink" href='javascript:addlink(
"editform","<?php echo $name ?>")'><img src="img/b_plus_tr.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Add Link");?></a></td>
</tr>
</table>
<?php if ($pageselector==true){ ?>
<table width="408" border="0" cellpadding="0" cellspacing="0" style="visibility: hidden;display:none" id="<?php echo $name ?>select">
                  <tr>
                    <td nowrap><a class="bausteineLink" href='javascript:selector_link(
"editform","<?php echo $name ?>")'><img src="img/b_edit_s.gif" width="18" height="18" border="0" align="absmiddle"> <?php echo localeH("Select Link");?></a></td>
                  </tr>
                </table>
                <?php }else{ ?>
                <table width="408" border="0" cellpadding="0" cellspacing="0" style="visibility: hidden;display:none" id="<?php echo $name ?>select"></table>
                <?php } ?>
<?php
    }
?>
 <table  id="<?php echo $name ?>panel" <?php echo $style ?> width="408" border="0" cellpadding="0" cellspacing="0" class="tableBausteineBackground" >
              <tr >
                <td nowrap>
     <?php
     if ($linktype!==false)
     {
     	echo $this->workarea_form_select2(locale("Link type"),$name."type",$linktype,$linktype_options,100);
     }
     else
     {
     	echo $this->workarea_form_hidden($name."type",0);
     }
     if ($bez!==false)
     {
     	echo $this->workarea_form_text(locale("Linkname"),$name."bez",$bez);
     }
     else
     {
     	echo $this->workarea_form_hidden($name."bez","");
     }
     if ($linktext!==false)
     {
     	echo $this->workarea_form_textarea(locale("Link text"),$name."text",$linktext,3,300);
     }
     else
     {
     	echo $this->workarea_form_hidden($name."text","");
     }
     if ($linksource!==false)
     {
     	echo $this->workarea_form_text(locale("Source"),$name."source",$linksource,100);
     }
     else
     {
     	echo $this->workarea_form_hidden($name."source","");
     }
     echo $this->workarea_form_text(locale("URL"),$name."url",$url);
     ?>
     <table cellpadding="0" cellspacing="0" border="0" style="padding-top:4px">
     <tr>
     <?php
     if ($target!==false)
     {
     	if($target==""){$target="_self";}
     	$this->iconbar_new();
     	$this->iconbar_addentry("b_link_target_self.gif","b_link_target_self_activ.gif","_self","im gleichen Fenster");
     	$this->iconbar_addentry("b_link_target_blank.gif","b_link_target_blank_activ.gif","_blank","in neuem Fenster");
     	echo "<td>";
     	$this->iconbar_draw($name."target",$target,"editform");
     	echo "</td>";
     }
     else
     {
     	echo $this->workarea_form_hidden($name."target","");
     }
     if ($popup_x!==false)
     {
     	echo "<td>&nbsp;X".$this->workarea_form_text("",$name."x",$popup_x,25,0) ." Y";
     	echo $this->workarea_form_text("",$name."y",$popup_y,25,0)."</td>";
     }
     else
     {
     	echo $this->workarea_form_hidden($name."x","");
     	echo $this->workarea_form_hidden($name."y","");
     }
	?>
	 </tr>
	 </table>
 </td></tr></table>

<?php
return $myPT->stopBuffer();
	}

	function explorer_redaktion_seiten_draw()
	{
		global $myDB;
		global $myPage;
		global $myAdm;
		global $mySUser;

		$rechte =   $mySUser->getRights();

		$id = $myAdm->explorer_get("pag_id");
		if ($id==-1)// Keine Seite in der Gruppe
		{
			$grp_id=$myAdm->explorer_get("grp_id");
		}
		else
		{
			$grp_id= $myPage->grp_id;
		}

		$url = "pagegroup_select.php";
		if ($mySUser->checkRight("elm_page"))
		{
			$this->tab_addEntry(locale("Pages"),$url,"b_site.gif");
		}
		if ($mySUser->checkRight("elm_content"))
		{
			$url = "backend.php?page=Editor,Content";
			$this->tab_addEntry(locale("Content"),$url,"b_content.gif");
		}
		if ($mySUser->checkRight("elm_mediabase"))
		{
			$url = "backend.php?page=Editor,Media";
			$this->tab_addEntry(locale("Media"),$url,"b_media.gif");
		}
		$this->tab_draw(locale("Pages"),$x=260,1)
?>
      <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="padding10"><form action="pagegroup_select.php" method="post" name="formGrp"><?php echo localeH("Group");?>:</td>
              <td><select name="grp_id" onChange="document.forms.formGrp.submit();" class="listmenu">
<?php
$sql = "SELECT grp_id AS K, grp_bez AS V FROM pagegroup ORDER BY V";
$html = "";
$rs = $myDB->query($sql);
while ($row = mysql_fetch_array($rs))
{
	$selected ="";
	if ($row["K"] == $grp_id)
	{
		$selected = "selected";
	}
	if ($rechte["access_grp_" . $row["K"]]==1)
	{
		$html .='<option value="'. $row["K"] .'" ' . $selected . '>' . $row["V"] . '</option>';
	}
}
echo $html;
?>				 
</select></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></form></td>
        </tr>
      </table>
	  
<?php
if ($mySUser->checkRight("elm_pageconfig"))
{

	if ($id!=-1)
	{
		$top_id = $rechte["pag_id_grp_" . $grp_id];
		// Begrenzer der hoechstmoeglichen Seite ermitteln

		$myAdm->showNavi($id,$top_id);


?><table width="260" border="0" cellpadding="0" cellspacing="0">
       

        <tr>
          <td class="windowFooterGrey2"><a href="javascript:pageWizard(<?php echo $_REQUEST["id"] ?>,<?php echo $myPage->hasChilds() ?>)" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new page");?> </a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
<?php }else{ ?>
<table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><a href="pagegroup_insertfirstpage.php?grp_id=<?php echo $grp_id ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add first page in group");?></a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
<?php } ?>	
		
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>  
	  <?php }
	  else
	  {
	  	// Kein Recht Seiten hinzuzufuegen
	  	$top_id = $rechte["pag_id_grp_" . $grp_id];
	  	$myAdm->showNavi($id,$top_id);
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
		  <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow" ><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table> 
		<?php
	  }
	  ?>
      <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="padding10"><strong><?php echo localeH("Search Pages");?>:</strong></td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("Page name");?> </td>
                <td>
                <form action="page_search.php" method="post">
 	  		    <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="text" name="s" style="width: 100px" class="input">
                </td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("ID");?> </td>
                <td><input type="text" name="i" style="width: 100px" class="input">
                </td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("Fulltext");?> </td>
                <td><input type="text" name="v" style="width: 100px" class="input">
                </td>
              </tr>
              <tr>
                <td class="padding10">&nbsp;</td>
                <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("send");?>" style="width:102px">
                </form>
                </td>
              </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		
        <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>
	  <?php

	}

	/**
	 * :TODO: remove
	 *
	 * @deprecated
	 *
	 */
	function explorer_redaktion_content_draw()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$url = "pagegroup_select.php";
		if ($mySUser->checkRight("elm_page"))
		{
			$this->tab_addEntry(locale("Pages"),$url,"b_site.gif");
		}
		$url = "content.php?r=-1";
		$this->tab_addEntry(locale("Content"),$url,"b_content.gif");
		if ($mySUser->checkRight("elm_mediabase"))
		{
			$url = "mediabase.php?folder=-1&type=-1&sortorder=1&p=1&a=10";
			$this->tab_addEntry(locale("Media"),$url,"b_media.gif");
		}
		$this->tab_draw(locale("Content"),$x=260,1);


		$myNav = new PhenotypeTree();
		$nav_id_start = $myNav->addNode(locale("Overview"),"content.php?r=-1",0,"");
		$sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez";
		if ($myPT->getPref("edit_content.flat_tree")==1)
		{
			$sql = "SELECT * FROM content ORDER BY con_bez";
		}
		$rs = $myDB->query($sql);



		$rubrik = "";
		$nav_id_rubrik = $nav_id_start;
		while ($row=mysql_fetch_array($rs))
		{
			$access = 0;
			if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
			if ($access==1)
			{
				if ($myPT->getPref("edit_content.flat_tree")==1)
				{
					$myNav->addNode($row["con_bez"],"content_select.php?t=".$row["con_id"]."&c=akt",$nav_id_start,$row["con_id"]);
				}
				else
				{
					if ($row["con_rubrik"]!=$rubrik)
					{
						$rubrik = $row["con_rubrik"];
						$nav_id_rubrik = $myNav->addNode($rubrik,"content.php?r=".urlencode($rubrik),$nav_id_start,"r_" . $row["con_rubrik"]);
					}
					if ($myAdm->explorer_get("rubrik")==$rubrik)
					{
						$myNav->addNode($row["con_bez"],"content_select.php?t=".$row["con_id"] . "&r=".$rubrik."&b=0&c=akt",$nav_id_rubrik,$row["con_id"]);
					}
				}
			}
		}
		$content_type = $myAdm->explorer_get("con_id");
		if ($content_type!="")
		{
			$token = $content_type;
		}
		else
		{
			$token = "r_".$myAdm->explorer_get("rubrik");
		}
		$this->displayTreeNavi($myNav,$token);

		if ($myAdm->explorer_get("con_id")!="")
		{
			$sql_con = "SELECT con_bez FROM content WHERE con_id = " .$myAdm->explorer_get("con_id");
			$rs = $myDB->query($sql_con);
			$row = mysql_fetch_array($rs);
			$contenttype= " / " . $row["con_bez"];
		}
		else
		{
			if ($myAdm->explorer_get("rubrik")!="")
			{
				$contenttype= " / " . $myAdm->explorer_get("rubrik");
			}
		}
	?>
	 <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" class="padding10"><strong><?php echo localeH("Search Content");?> <?php echo $contenttype ?> <?php echo localeH("for");?>:</strong></td>
            </tr>
            <tr>
              <td class="padding10"><?php echo localeH("Name");?></td>
              <td>
			  <form action="content_select.php" method="post">
 	  		  <input type="hidden" name="t" value="<?php echo $myAdm->explorer_get("con_id") ?>">
			  <input type="hidden" name="r" value="<?php echo htmlentities($myAdm->explorer_get("rubrik")) ?>">
		    <input type="hidden" name="c" value="search">
			  <input type="text" name="s" style="width: 100
			  px" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> <?php echo localeH("ID");?> </td>
              <td><input type="text" style="width: 100
			  px" name="i" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> <?php echo localeH("Fulltext");?> </td>
              <td><input type="text" style="width: 100
			  px" name="v" class="input"></td>
            </tr>
            <tr>
              <td class="padding10">&nbsp;</td>
              <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("send");?>" style="width:102px"></form></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		<?php
		if ($myAdm->explorer_get("con_id")!="")
		{
			$sql = "SELECT con_anlegen FROM content WHERE con_id=".$myAdm->explorer_get("con_id");
			$rs= $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			if ($row["con_anlegen"]==1)
			{
		?>
		<tr>
          <td class="windowFooterGrey2"><a href="content_insert.php?id=<?php echo $myAdm->explorer_get("con_id") ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new record");?> </a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		<?php
			}
		}
		?>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>
	  <?php
	}


	/**
	 * :TODO: remove
	 *
	 * @deprecated
	 *
	 */
	function explorer_redaktion_media_draw()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		$url = "pagegroup_select.php";
		if ($mySUser->checkRight("elm_page"))
		{
			$this->tab_addEntry(locale("Pages"),$url,"b_site.gif");
		}
		if ($mySUser->checkRight("elm_content"))
		{
			$url = "content.php?r=-1";
			$this->tab_addEntry(locale("Content"),$url,"b_content.gif");
		}
		$url = "mediabase.php?folder=-1&type=-1&sortorder=1&p=1&a=10";
		$this->tab_addEntry(locale("Media"),$url,"b_media.gif");
		$this->tab_draw(locale("Media"),$x=260,1);
?>
<?php
$myNav = new PhenotypeTree();
$nav_id_top = $myNav->addNode(locale("Overview"),"mediabase.php?folder=-1&type=-1&sortorder=1&p=1&a=20",0,"-1");
global $myDB;
$myMB = new PhenotypeMediabase();
$_folder = $myMB->getFullLogicalFolder();

// Umbau der Folder in ein Treeobjekt
$_folder = $myMB->getFullLogicalFolder();
//print_r ($_folder);
$_navids = Array();
$_navids[]=0;
$_nav_id_current=0;
$myNav = new PhenotypeTree();

$_ueberordner = Array();
$s=$_REQUEST["folder"];
$p = strrpos($s," /");
$i=1;
while ($p!==false AND $i<10)
{
	$left = substr($s,0,$p);
	$_ueberordner[substr_count($left,"/")+1]=$left;
	$s=$left;
	$p = strrpos($s," /");
	$i++;
}
$_current_ebene=$i;
foreach ($_folder AS $folder)
{
	$_tree= (explode("/",$folder));
	$n=count($_tree);
	if ($n==1)
	{
		$left="";
		$right=trim($_tree[0]);
	}
	else
	{
		$left="";
		for ($i=0;$i<$n-1;$i++)
		{
			$left .= trim($_tree[$i]) ." / ";
		}
		$left = substr($left,0,-3);
		$right=trim($_tree[$n-1]);
	}
	/*
	$p = strrpos($folder,' /');
	if ($p===false)
	{
	$left ="";
	$right =$folder;
	}
	else
	{
	$left = substr($folder,0,$p);
	$right = substr($folder,$p+3);
	}
	*/

	//echo $left.":".$right.";<br>";
	$nav_id_top = (int)$_navids[$left];

	$url = "mediabase.php?folder=".urlencode($folder)."&type=".$_REQUEST["type"]."&sortorder=" . $_REQUEST["sortorder"] ."&p=1&a=" . $_REQUEST["a"];

	$ebene = substr_count($folder,"/")+1;
	$takeit=0;
	//echo "Current: " . $_current_ebene . "<br><br>";
	//echo $folder . " - " .$ebene . "<br>";
	if ($ebene<=$_current_ebene)
	{
		//echo "kleiner:" . $folder . "<br>";
		//echo "Vergleich mit:" . $_ueberordner[$ebene-1] . "<br>";
		if (strpos($folder,$_ueberordner[$ebene-1])===0)
		{
			$takeit=1;
		}
	}
	else
	{
		if ($ebene==$_current_ebene+1)
		{
			//echo "Vergleich $folder mit:" .$_REQUEST["folder"] . "<br>";
			if (strpos($folder,$_REQUEST["folder"])===0)
			{
				$takeit=1;
				//echo "ja:" .$folder . "<br>";
			}
		}
		//else
		//{
		//	//echo "nein:" .$folder . "<br>";
		//}
	}
	if($ebene==1){$takeit=1;}
	if ($takeit)
	{
		$nav_id = $myNav->addNode($right,$url,$nav_id_top,$folder);
		$_navids[$folder]=$nav_id;
	}
	//if ($_REQUEST["folder"]==$folder)
	//{
	//	$nav_id_current = $nav_id;
	//}
}

//if ($nav_id_current!=0)
//{
//	$myNav->shrink($nav_id_current);
//}
//print_r ($_navids);




/*
$_top = Array();;

// Ersten Ast des aktuellen Folders feststellen
if ($myRequest["folder"]!=-1)
{
$current_branch = explode (" / ",$myRequest["folder"]);
}
else
{
$current_branch = Array("");
}


foreach ($_folder AS $k)
{
// Flach:
$navtree = explode (" / ",$k);
//print_r ($navtree);
$folder = "";
$nav_id = $nav_id_top;

// Nur den aktuellen Zweig ausklappen, den Rest zu
if ($navtree[0]!=$current_branch[0]){$navtree = Array($navtree[0]);}
// hier spater evtl noch eine feinere Logik, weil etwas zu viel aufgeklappt wird


foreach ($navtree AS $branch)
{
$branch = trim($branch);
if ($folder=="")
{
$folder=$branch;
}
else
{
$folder .=" / " . $branch;
}
if (!isset($_top[$folder]))
{
$nav_id = $myNav->addNode($branch,"mediabase.php?folder=".urlencode($folder)."&type=".$_REQUEST["type"]."&sortorder=" . $_REQUEST["sortorder"] ."&p=1&a=" . $_REQUEST["a"],$nav_id,$folder);
$_top[$folder]=$nav_id;
}
else
{
$nav_id =$_top[$folder];
}
}

//$myNav->addNode($k,"mediabase.php?folder=".$k."&type=".$_REQUEST["type"]."&sortorder=" . $_REQUEST["sortorder"] ."&p=1&a=" . $_REQUEST["a"],$nav_id,$k);

//$myNav->addNode($k,"mediabase.php?folder=".$k."&type=".$_REQUEST["type"]."&sortorder=" . $_REQUEST["sortorder"] ."&p=1&a=" . $_REQUEST["a"],$nav_id,$k);
}
*/
$this->displayTreeNavi($myNav,$_REQUEST["folder"]);
?>
      <table width="260" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowMenu">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <form action="mediabase_upload.php" method="post">
            <tr>
              <td class="windowTabTypeOnly"><strong><?php echo localeH("New Files");?>: </strong></td>
              <td class="windowTabTypeOnly">
			    
      <input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
      <input type="hidden" name="folder" value="<?php echo $_REQUEST["folder"] ?>">
      <input type="hidden" name="type" value="<?php echo $_REQUEST["type"] ?>">				   				   
      <input type="hidden" name="sortorder" value="<?php echo $_REQUEST["sortorder"] ?>">
      <input type="hidden" name="p" value="<?php echo $_REQUEST["p"] ?>">		  
	  <input type="hidden" name="a" value="<?php echo $_REQUEST["a"] ?>">	
	  <input name="upload" type="submit" class="buttonWhite" id="upload" style="width:102px" value="<?php echo localeH("Upload");?>"></td>
            </tr>
			</form>
            <tr>
              <td class="windowTabTypeOnly">&nbsp;</td>
              <td class="windowTabTypeOnly"><form action="mediabase_import.php" method="post">
      <input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
      <input type="hidden" name="folder" value="<?php echo $_REQUEST["folder"] ?>">
      <input type="hidden" name="type" value="<?php echo $_REQUEST["type"] ?>">				   				   
      <input type="hidden" name="sortorder" value="<?php echo $_REQUEST["sortorder"] ?>">
      <input type="hidden" name="p" value="<?php echo $_REQUEST["p"] ?>">	
	  <input type="hidden" name="a" value="<?php echo $_REQUEST["a"] ?>">	  
	  <input name="import" type="submit" class="buttonWhite" id="import2" style="width:102px" value="<?php echo localeH("Import");?>"></form></td>
            </tr>
          </table>
		  </td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
              <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
    </table>   
	 
      <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="padding10"><strong><?php echo localeH("Search Media");?>:</strong></td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("Name");?> </td>
                <td>
                <form action="media_search.php" method="post">
 	  		    <input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
      			<input type="hidden" name="folder" value="<?php echo $_REQUEST["folder"] ?>">
      			<input type="hidden" name="type" value="<?php echo $_REQUEST["type"] ?>">				   				   
      			<input type="hidden" name="sortorder" value="<?php echo $_REQUEST["sortorder"] ?>">
      			<input type="hidden" name="p" value="<?php echo $_REQUEST["p"] ?>">	
	  			<input type="hidden" name="a" value="<?php echo $_REQUEST["a"] ?>">
                <input type="text" name="s" style="width: 100px" class="input">
                </td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("ID");?> </td>
                <td><input type="text" name="i" style="width: 100px" class="input"></td>
              </tr>
              <tr>
                <td class="padding10"> <?php echo localeH("Fulltext");?> </td>
                <td><input type="text" name="v" style="width: 100px" class="input"></td>
              </tr>
              <tr>
                <td class="padding10">&nbsp;</td>
                <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("send");?>" style="width:102px">
                </form>
                </td>
              </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		
        <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>
	  <?php
	}

	function explorer_konfiguration_draw($submodul)
	{
		global $myDB;
		global $myPage;
		global $myAdm;

		$url = "config.php";
		$this->tab_addEntry(localeH("Pages"),$url,"b_site.gif");
		$url = "config_content.php?r=-1";
		$this->tab_addEntry(localeH("Content"),$url,"b_content.gif");
		$url = "config_extras.php";
		$this->tab_addEntry(localeH("Extras"),$url,"b_konfig.gif");

		// Reserved for future upgrades
		//$url = "config_tools.php";
		//$this->tab_addEntry("T",$url,"b_konfig.gif");

		switch ($submodul)
		{
			case locale("Content"):

				$this->tab_draw("Content",$x=260,1);
				$myNav = new PhenotypeTree();


				$sql = "SELECT * FROM content ORDER BY con_rubrik,con_bez";
				$rs = $myDB->query($sql);
				$rubrik = "";
				$nav_id_rubrik = 0;

				if (mysql_num_rows($rs)!=0)
				{
					while ($row=mysql_fetch_array($rs))
					{
						if ($row["con_rubrik"]!=$rubrik)
						{
							$rubrik = $row["con_rubrik"];
							$nav_id_rubrik = $myNav->addNode($rubrik,"config_content.php?r=".$rubrik,0,"r_" . $row["con_rubrik"]);
						}
						if ($_REQUEST["r"]==$rubrik)
						{
							$myNav->addNode($row["con_bez"],"contentobject_edit.php?id=".$row["con_id"] . "&r=".$rubrik."&b=0",$nav_id_rubrik,$row["con_id"]);
						}
					}
					if ($myAdm->explorer_get("con_id")!="")
					{
						$this->displayTreeNavi($myNav,$myAdm->explorer_get("con_id"));
					}
					else
					{
						if ($_REQUEST["r"]!=-1)
						{
							$this->displayTreeNavi($myNav,"r_" . $_REQUEST["r"]);
						}
						else
						{
							$this->displayTreeNavi($myNav,$submodul);
						}
					}
				}

				/*$sql = "SELECT * FROM content ORDER BY con_bez";
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
				$myNav->addNode(sprintf("%02d",$row["con_id"]) . "&nbsp;&nbsp;&nbsp;&nbsp;" .$row["con_bez"],"contentobject_edit.php?id=".$row["con_id"]."&b=0",0,$row["con_id"]);
				}
				$this->displayTreeNavi($myNav,$myAdm->explorer_get("con_id"));
				*/
				break;
			case locale("Tools"):
				$this->tab_draw(locale("Tools"),$x=260,1);
				$myNav = new PhenotypeTree();
				$nav_id_layout    = $myNav->addNode(locale("Console"),"config_console.php",0,locale("Console"));
				$this->displayTreeNavi($myNav,$myAdm->explorer_get("too_id"));
				break;
			case locale("Extras"):
				$this->tab_draw(locale("Extras"),$x=260,1);
				$myNav = new PhenotypeTree();
				$sql = "SELECT * FROM extra ORDER BY ext_bez";
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode(sprintf("%02d",$row["ext_id"]) . "&nbsp;&nbsp;&nbsp;&nbsp;" .$row["ext_bez"],"extra_edit.php?id=".$row["ext_id"]."&b=0",0,$row["ext_id"]);
				}
				if (mysql_num_rows($rs)!=0)
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("ext_id"));
				}
				break;


			default:
				$this->tab_draw(locale("Pages"),$x=260,1);


				$myNav = new PhenotypeTree();
				//$nav_id_layout    = $myNav->addNode("Layout","layout.php",0,"Layout");
				$nav_id_bausteine = $myNav->addNode(locale("Components"),"components.php",0,"Bausteine");
				$nav_id_bgruppen  = $myNav->addNode(locale("Componentgroups"),"toolkit.php",0,"Bausteingruppen");
				$nav_id_includes  = $myNav->addNode(locale("Includes"),"includes.php?r=-1",0,"Includes");
				$nav_id_skripte   = $myNav->addNode(locale("Pagescripts"),"pagescripts.php",0,"Seitenskripte");

				switch ($submodul)
				{
					case "Layout":
						$sql = "SELECT * FROM layout ORDER BY lay_bez";
						$rs = $myDB->query($sql);
						while ($row=mysql_fetch_array($rs))
						{
							$myNav->addNode($row["lay_bez"],"layout_edit.php?id=".$row["lay_id"]."&b=0",$nav_id_layout,$row["lay_id"]);

						}
						if ($myAdm->explorer_get("lay_id")!="")
						{
							$this->displayTreeNavi($myNav,$myAdm->explorer_get("lay_id"));
						}
						else
						{
							$this->displayTreeNavi($myNav,$submodul);
						}
						break;
					case locale("Components"):
						$sql = "SELECT * FROM component ORDER BY com_rubrik,com_bez";
						$rs = $myDB->query($sql);
						$rubrik = "";
						$nav_id_rubrik = $nav_id_bausteine;
						while ($row=mysql_fetch_array($rs))
						{
							if ($row["com_rubrik"]!=$rubrik)
							{
								$rubrik = $row["com_rubrik"];
								$nav_id_rubrik = $myNav->addNode($rubrik,"components.php?r=".$rubrik,$nav_id_bausteine,"r_" . $row["com_rubrik"]);
							}
							if ($_REQUEST["r"]==$rubrik)
							{
								$myNav->addNode($row["com_bez"],"component_edit.php?id=".$row["com_id"] . "&r=".$rubrik."&b=0",$nav_id_rubrik,$row["com_id"]);
							}
						}
						if ($myAdm->explorer_get("com_id")!="")
						{
							$this->displayTreeNavi($myNav,$myAdm->explorer_get("com_id"));
						}
						else
						{
							if ($_REQUEST["r"]!="")
							{
								$this->displayTreeNavi($myNav,"r_" . $_REQUEST["r"]);
							}
							else
							{
								$this->displayTreeNavi($myNav,$submodul);
							}
						}
						/*
						$sql = "SELECT * FROM component ORDER BY com_bez";
						$rs = $myDB->query($sql);
						while ($row=mysql_fetch_array($rs))
						{
						$myNav->addNode(sprintf("%02d",$row["com_id"]) . "&nbsp;&nbsp;&nbsp;&nbsp;" .$row["com_bez"],"component_edit.php?id=".$row["com_id"]."&b=0",$nav_id_bausteine,$row["com_id"]);

						}
						if ($myAdm->explorer_get("com_id")!="")
						{
						$this->displayTreeNavi($myNav,$myAdm->explorer_get("com_id"));
						}
						else
						{
						$this->displayTreeNavi($myNav,$submodul);
						}
						break;
						*/
						break;
					case locale("Componentgroups"):
						$sql = "SELECT * FROM componentgroup ORDER BY cog_bez";
						$rs = $myDB->query($sql);
						while ($row=mysql_fetch_array($rs))
						{
							$myNav->addNode($row["cog_bez"],"toolkit_edit.php?id=".$row["cog_id"]."&b=0",$nav_id_bgruppen,$row["cog_id"]);
						}
						if ($myAdm->explorer_get("cog_id")!="")
						{
							$this->displayTreeNavi($myNav,$myAdm->explorer_get("cog_id"));
						}
						else
						{
							$this->displayTreeNavi($myNav,$submodul);
						}

						break;
					case locale("Includes"):

						$sql = "SELECT * FROM include ORDER BY inc_rubrik,inc_bez";
						$rs = $myDB->query($sql);
						$includes = Array();
						$rubrik = "";
						$nav_id_rubrik = $nav_id_includes;
						while ($row=mysql_fetch_array($rs))
						{
							if ($row["inc_rubrik"]!=$rubrik)
							{
								$rubrik = $row["inc_rubrik"];
								$nav_id_rubrik = $myNav->addNode($rubrik,"includes.php?r=".$rubrik,$nav_id_includes,"r_" . $row["inc_rubrik"]);
							}
							if ($_REQUEST["r"]==$rubrik)
							{
								$myNav->addNode($row["inc_bez"],"include_edit.php?id=".$row["inc_id"] . "&r=".$rubrik."&b=0",$nav_id_rubrik,$row["inc_id"]);
							}
						}
						if ($myAdm->explorer_get("inc_id")!="")
						{
							$this->displayTreeNavi($myNav,$myAdm->explorer_get("inc_id"));
						}
						else
						{
							if ($_REQUEST["r"]!=-1)
							{
								$this->displayTreeNavi($myNav,"r_" . $_REQUEST["r"]);
							}
							else
							{
								$this->displayTreeNavi($myNav,$submodul);
							}
						}
						break;
					case locale("Pagescripts"):
						$sql = "SELECT * FROM page LEFT JOIN pageversion ON page.pag_id = pageversion.pag_id WHERE pag_exec_script=1 ORDER BY grp_id,page.pag_id,pageversion.ver_id";
						$rs = $myDB->query($sql);
						while ($row=mysql_fetch_array($rs))
						{
							$nr = sprintf("%03d",$row["pag_id"]) . ".".sprintf("%02d",$row["ver_nr"]);
							if ($row["ver_bez"]!="")
							{
								$bez= $row["pag_bez"]. " (".$row["ver_bez"].")";
							}
							else
							{
								$bez= $row["pag_bez"];
							}
							$myNav->addNode($nr."&nbsp;&nbsp;&nbsp;&nbsp;".$bez,"pagescript_edit.php?id=".$row["pag_id"]."&ver_nr=".$row["ver_nr"]."&ver_id=".$row["ver_id"]."&b=0",$nav_id_skripte,$nr);

						}
						if ($myAdm->explorer_get("pagescript_nr")!="")
						{
							$this->displayTreeNavi($myNav,$myAdm->explorer_get("pagescript_nr"));
						}
						else
						{
							$this->displayTreeNavi($myNav,$submodul);
						}
						break;

					default:
						$this->displayTreeNavi($myNav,$submodul);
						break;
				}
		}
		?>
	<table width="260" border="0" cellpadding="0" cellspacing="0">
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>
		<br/>
		<br/>
	<?php

	// Packages Explorer

	$this->tab_new();
	$url = "packages.php";
	$this->tab_addEntry(locale("Packages"),$url,"b_items.gif");
	$this->tab_draw(locale("Packages"),$x=260,1);

	$myNav = new PhenotypeTree();
	$myNav->addNode(locale("Install Package"),"packages.php",0,"install");
	$myNav->addNode(locale("Export Package"),"package_export.php",0,"export");
	$myNav->addNode(locale("Cleanup"),"package_cleanup.php",0,"cleanup");

	$this->displayTreeNavi($myNav,$myAdm->explorer_get("packagemode"));
	?>
	<table width="260" border="0" cellpadding="0" cellspacing="0">
	<tr>
      <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
      <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
    </tr>
	</table>
	<?php
	}



	function explorer_admin_draw($submodul)
	{
		global $myDB;
		global $myPage;
		global $myAdm;
		global $mySUser;

		$url = "admin.php";
		$this->tab_addEntry(locale("Admin"),$url,"b_konfig.gif");

		$this->tab_draw(locale("Admin"),$x=260,1);


		$myNav = new PhenotypeTree();
		$nav_id_users    = $myNav->addNode(locale("Users"),"backend.php?page=Admin,Users,view",0,locale("User"));
		if ($myAdm->explorer_get("littleadmin")!=1)
		{
			$nav_id_roles    = $myNav->addNode(locale("Roles"),"admin_roles.php",0,locale("Roles"));
			$nav_id_cache    = $myNav->addNode(locale("Cache"),"admin_cache.php",0,locale("Cache"));
			$nav_id_layout    = $myNav->addNode(locale("Layout"),"layout.php",0,locale("Layout"));
			//$nav_id_pages    = $myNav->addNode("Seiten","admin_pages.php",0,"Seiten");
			$nav_id_groups   = $myNav->addNode(locale("Pagegroups"),"admin_groups.php",0,locale("Pagegroups"));
			$nav_id_content  = $myNav->addNode(locale("Content"),"admin_content.php",0,locale("Content"));
			$nav_id_media    = $myNav->addNode(locale("Media"),"admin_media.php",0,locale("Media"));
			$nav_id_mediagroups   = $myNav->addNode(locale("Mediagroups"),"admin_mediagroups.php",0,locale("Mediagroups"));


			$nav_id_subject   = $myNav->addNode(locale("Task subjects"),"admin_subject.php",0,locale("Task subjects"));

			$nav_id_action   = $myNav->addNode(locale("Actions"),"admin_actions.php",0,locale("Actions"));
			//$nav_id_export   = $myNav->addNode("Export","admin_export.php",0,"Export");
			//$nav_id_cron     = $myNav->addNode("Wartung","admin_cron.php",0,"Wartung");
		}

		switch ($submodul)
		{
			case locale("Users"):
				$sql = "SELECT * FROM user WHERE usr_status = 1 ORDER BY usr_nachname";

				// Im eingeschr�nkten Modus nur den angemeldeten Benutzer zeigen
				if ($myAdm->explorer_get("littleadmin")==1)
				{
					$sql = "SELECT * FROM user WHERE usr_status = 1 AND usr_id = " . $mySUser->id;
				}
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["usr_vorname"] . " " . $row["usr_nachname"],"backend.php?page=Admin,Users,edit/id=".$row["usr_id"]."&b=0",$nav_id_users,$row["usr_id"]);

				}
				if ($myAdm->explorer_get("usr_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("usr_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;
			case locale("Roles"):
				$sql = "SELECT * FROM role ORDER BY rol_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["rol_bez"],"admin_role_edit.php?id=".$row["rol_id"]."&b=0",$nav_id_roles,$row["rol_id"]);

				}
				if ($myAdm->explorer_get("rol_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("rol_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;
			case locale("Layout"):
				$sql = "SELECT * FROM layout ORDER BY lay_bez";
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["lay_bez"],"layout_edit.php?id=".$row["lay_id"]."&b=0",$nav_id_layout,$row["lay_id"]);

				}
				if ($myAdm->explorer_get("lay_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("lay_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			case locale("Pagegroups"):
				$sql = "SELECT * FROM pagegroup ORDER BY grp_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["grp_bez"],"admin_group_edit.php?id=".$row["grp_id"]."&b=0",$nav_id_groups,$row["grp_id"]);
				}
				if ($myAdm->explorer_get("grp_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("grp_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			case locale("Task subjects"):
				$sql = "SELECT * FROM ticketsubject ORDER BY sbj_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["sbj_bez"],"admin_subject_edit.php?id=".$row["sbj_id"]."&b=0",$nav_id_subject,$row["sbj_id"]);
				}
				if ($myAdm->explorer_get("sbj_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("sbj_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;


			case locale("Actions"):
				$sql = "SELECT * FROM action ORDER BY act_bez";

				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myNav->addNode($row["act_bez"],"admin_action_edit.php?id=".$row["act_id"]."&b=0",$nav_id_action,$row["act_id"]);
				}
				if ($myAdm->explorer_get("act_id")!="")
				{
					$this->displayTreeNavi($myNav,$myAdm->explorer_get("act_id"));
				}
				else
				{
					$this->displayTreeNavi($myNav,$submodul);
				}
				break;

			default:
				$this->displayTreeNavi($myNav,$submodul);
				break;
		}
	?>
	<table width="260" border="0" cellpadding="0" cellspacing="0">
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>
	<?php
	}

	/**
   * :TODO: Check if used anywhere
   *
   */
	function explorer_aufgaben_draw()
	{
		global $myDB;
		global $myPage;
		global $myAdm;

		$url = "tickets.php";
		$this->tab_addEntry("Aufgaben",$url,"b_job.gif");
		$this->tab_draw("Aufgaben",$x=260,1);

		$block_nr = 0;
		if (isset($_REQUEST["focus"])){$block_nr = $_REQUEST["focus"];}

		$sortorder=1;
		if (isset($_REQUEST["sortorder"])){$sortorder = $_REQUEST["sortorder"];}

		$myNav = new PhenotypeTree();
		$nav_id  = $myNav->addNode("Alle Bereiche","tickets.php?sbj_id=-1&focus=".$block_nr . "&sortorder=".$sortorder,0,"Alle Bereiche");

		$sql ="SELECT * FROM ticketsubject LEFT JOIN user_ticketsubject ON ticketsubject.sbj_id = user_ticketsubject.sbj_id WHERE usr_id = " . $_SESSION["usr_id"] . " ORDER BY sbj_bez";
		$rs = $myDB->query($sql);
		$sbj_bez ="";
		$sbj_id=$myAdm->explorer_get("sbj_id");
		while ($row=mysql_fetch_array($rs))
		{
			$myNav->addNode($row["sbj_bez"],"tickets.php?sbj_id=".$row["sbj_id"]."&focus=".$block_nr . "&sortorder=".$sortorder,$nav_id,$row["sbj_id"]);
			if ($sbj_id==$row["sbj_id"]){$sbj_bez = "/ " . $row["sbj_bez"];}
		}
		if ($sbj_id!="")
		{
			$this->displayTreeNavi($myNav,$sbj_id);
			$sbj_id2 = $sbj_id;
		}
		else
		{
			$this->displayTreeNavi($myNav,"Alle Bereiche");
			$sbj_id2=0;
			$sbj_id=-1;
		}

	?>
	<table width="260" border="0" cellpadding="0" cellspacing="0">
	        <tr>
          <td class="windowFooterGrey2"><a href="javascript:ticketWizard(0,0,0,0,<?php echo $sbj_id2 ?>,0)" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> Neue Aufgabe einstellen </a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
	 <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
		</table>
 <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" class="padding10"><strong>Suche Aufgaben <?php echo $sbj_bez ?> nach:</strong></td>
            </tr>
            <tr>
              <td class="padding10">Bezeichnung</td>
              <td>
			  <form action="tickets.php" method="post">
			  <input type="hidden" name="suche" value="1">
 	  		  <input type="hidden" name="sbj_id" value="<?php echo $sbj_id ?>">
			  <input type="hidden" name="focus" value="<?php echo $block_nr ?>">
			  <input type="hidden" name="sortorder" value="<?php echo $sortorder ?>">
	    	  <input type="text" name="s" style="width: 100
			  px" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> ID </td>
              <td><input type="text" style="width: 100
			  px" name="i" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> Volltext </td>
              <td><input type="text" style="width: 100
			  px" name="v" class="input"></td>
            </tr>
            <tr>
              <td class="padding10">&nbsp;</td>
              <td><input name="Submit" type="submit" class="buttonGrey2" value="Senden" style="width:102px"></form></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>		
	<?php
	}

	function displayStatsPanel_Page($bez,$nr,$mode,$datum,$scope,$anzahl,$grp_id)
	{
		global $myDB;
		global $myPT;
  ?>
  <input type="hidden" name="p<?php echo $nr ?>_newscope" value="<?php echo $scope ?>">
  <input type="hidden" name="p<?php echo $nr ?>_mode" value="<?php echo $mode ?>">
  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="windowTitle"><?php echo $bez ?></td>
                <td align="right" class="windowTitle">
                <a href="http://www.phenotype-cms.de/docs.php?v=23&t=6" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>
                <!--<a href="#"><img src="img/b_print.gif" alt="Statistik ausdrucken" width="22" height="22" border="0"></a> <a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
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
	  $this->tab_new();
	  $url = "javascript:document.forms.form1.p" .$nr . "_mode.value=1;document.forms.form1.submit();";
	  $this->tab_addEntry(locale("PIs"),$url,"b_clicks.gif");
	  $url = "javascript:document.forms.form1.p" .$nr . "_mode.value=2;document.forms.form1.submit();";
	  $this->tab_addEntry(locale("Trend"),$url,"b_tracking.gif");
	  if ($mode==1)
	  {
	  	$this->tab_draw(locale("PIs"),$x=400);
	  }
	  else
	  {
	  	$this->tab_draw(locale("Trend"),$x=400);
	  }
	  ?>
	  <?php
	  // Pruefung, welche Monate sich in der Statistik befinden
	  $_monat = Array(locale("January"),locale("February"),locale("March"),locale("April"),locale("May"),locale("June"),locale("July"),locale("August"),locale("September"),locale("October"),locale("November"),locale("December"));
	  $sql = "SELECT MIN(sta_datum) AS start FROM page_statistics";
	  $rs = $myDB->query($sql);
	  $row = mysql_fetch_array($rs);
	  $start = $row["start"];
	  $sql = "SELECT MAX(sta_datum) AS stop FROM page_statistics";
	  $rs = $myDB->query($sql);
	  $row = mysql_fetch_array($rs);
	  $stop = $row["stop"];

	  if ($start==""){$start=date("Ymd");}
	  if ($stop==""){$stop=date("Ymd");}

	  $j1 = substr($start,0,4);
	  $m1 = substr($start,4,2);
	  $j2 = substr($stop,0,4);
	  $m2 = substr($stop,4,2);

	  $_options = Array();
	  //for ($j=$j1;$j<=$j2;$j++)
	  for ($j=$j2;$j>=$j1;$j--)
	  {
	  	if ($j==$j1){$mstart=$m1;}else{$mstart=1;}
	  	if ($j==$j2){$mstop=$m2;}else{$mstop=12;}
	  	//for ($m=$mstart;$m<=$mstop;$m++)
	  	for ($m=$mstop;$m>=$mstart;$m--)
	  	{
	  		$v = $j.sprintf("%02.0d",$m);
	  		$_options[$v]= $_monat[$m-1] . " " . $j;
	  	}
	  }
      ?>
	   
			  <?php
			  // ####################################################
			  // Abrufe
			  if ($mode==1)
			  {
?>
 <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap class="padding10">
                  <?php echo localeH("Day view");?><br>
				  <?php
				  $checked="";
				  if ($scope=="2"){$checked="checked";}
				  ?>
                  <input type="radio" name="p<?php echo $nr ?>_scope" value="2" <?php echo $checked; ?> onclick="document.forms.form1.p<?php echo $nr ?>_newscope.value=2;document.forms.form1.submit();">
                  <input name="p<?php echo $nr ?>_datum" type="text" class="input" style="width: 45px" value="<?php echo date("d.m.y",$datum) ?>"><input type="image" src="img/transparent.gif" alt="" width="1" height="1" border="0" align="absmiddle" name="p<?php echo $nr ?>_dtfocus"><input type="image" src="img/b_minus.gif" alt="ein Tag zur&uuml;ck" width="18" height="18" border="0" align="absmiddle" name="p<?php echo $nr ?>_minus"><input type="image" src="img/b_plus.gif" alt="ein Tag vor" width="18" height="18" border="0" align="absmiddle" name="p<?php echo $nr ?>_plus"></td>
                <td nowrap class="padding10"><?php echo localeH("Month view");?><br>
				  <?php
				  $checked="";
				  if ($scope=="1"){$checked="checked";}
				  ?>				
                    <input type="radio" name="p<?php echo $nr ?>_scope" value="1" <?php echo $checked; ?> onclick="document.forms.form1.p<?php echo $nr ?>_newscope.value=1;document.forms.form1.submit();">                                         
                    <select name="p<?php echo $nr ?>_monat" class="listmenu" onChange="document.forms.form1.p<?php echo $nr ?>_newscope.value=1;document.forms.form1.submit();">
                       <?php
                       $aktuell = date("Ym",$datum);
                       foreach ($_options AS $K => $V)
                       {
                       	$selected="";
                       	if ($K == $aktuell)
                       	{
                       		$selected="selected";
                       	}
					   ?>
					   <option value="<?php echo $K ?>" <?php echo $selected ?>><?php echo $V ?></option>
					   <?php
                       }
					   ?>
                    </select></td>
                <td nowrap class="padding10">
				<?php echo localeH("Listing");?><br><select name="p<?php echo $nr ?>_anzahl" class="listmenu" onChange="document.forms.form1.submit();">
                    <?php
                    for ($i=10;$i<=50;$i=$i+10)
                    {
                    	$selected ="";
                    	if ($i==$anzahl){$selected="selected";}
					?>
                    <option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?> <?php echo localeH("Rows") ?></option>
					<?php
                    }
					?>  
                    <?php
                    for ($i=100;$i<=500;$i=$i+100)
                    {
                    	$selected ="";
                    	if ($i==$anzahl){$selected="selected";}
					?>
                    <option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?> <?php echo localeH("Rows") ?></option>
					<?php
                    }
					?>  					                                                                                                   
                    </select>
					</td>
                </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		
      </table>
<?php

if ($scope==1) // Monatsansicht
{
	$zeitraum = date("Ym",$datum);
	//$sql = "CREATE TEMPORARY TABLE " . $table . " SELECT pag_id, SUM(sta_pageview) AS sum FROM page_statistics WHERE sta_datum >" . $zeitraum ."00" . " AND sta_datum <" . $zeitraum . "99 GROUP BY pag_id";
	$subquery = "(SELECT page.pag_id, SUM(sta_pageview) AS sum FROM page_statistics LEFT JOIN page ON page_statistics.pag_id = page.pag_id WHERE sta_datum >" . $zeitraum ."00" . " AND sta_datum <" . $zeitraum . "99";
	if ($grp_id!=-1)
	{
		$subquery.= " AND page.grp_id = " . $grp_id;
	}
	$subquery.= " GROUP BY page.pag_id) AS SubQuery";


}
else
{
	$subquery = "(SELECT page.pag_id, sta_pageview AS sum FROM page_statistics LEFT JOIN page ON page_statistics.pag_id = page.pag_id WHERE sta_datum =". date ("Ymd",$datum);
	if ($grp_id!=-1)
	{
		$sql.= " AND page.grp_id = " . $grp_id;
	}
	$subquery.= ") AS SubQuery";
}

$rs = $myDB->query($sql);
$sql = "SELECT COUNT(*) AS C FROM " . $subquery;

$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$c = $row["C"];

$sql = "SELECT MAX(sum) AS M, SUM(sum) AS S FROM " . $subquery;

$rs = $myDB->query($sql);
$row = mysql_fetch_array($rs);
$max = $row["M"];
$sum = $row["S"];

$avg=0;
if ($max!=0){$avg = $sum/$c;}
$pix = 200; // 170, wenn Aktion dabei ist
if ($max!=0){$avg = ceil($avg/$max*$pix);}else{$avg=0;}
	          ?>
	  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" class="tableHead"><?php echo localeH("PIs");?></td>
                <td class="tableHead"><?php echo localeH("Page");?></td>
                <td width="200" class="tableHead"><?php echo localeH("Chart");?> ( <img src="img/i_stat_legend.gif" width="5" height="8" align="absmiddle"> <?php echo localeH("Average value");?> )</td>
                </tr>			  
			  	  <style>
	  /* Dynamisierung der x-Position f�r den Mittelwert */
.tableMarker<?php echo $nr ?> {
	padding: 5px 10px 5px 10px;
	background:  url(img/i_stat_marker.gif) no-repeat <?php echo $avg+8 ?>px 0px;
	}
	</style>
			  <?php
			  $sql = "SELECT * FROM " . $subquery . " LEFT JOIN page ON SubQuery.pag_id = page.pag_id ORDER BY sum DESC LIMIT 0," . $anzahl;
			  $rs = $myDB->query($sql);
			  while ($row = mysql_fetch_array($rs))
			  {
			  	$pi = $row["sum"];
			  	if ($max>0){$x = ceil($pi/$max*$pix);}else{$x=0;}
			  	$color="blue";
			  	if ($scope==1)
			  	{
			  		if (date('m')==date('m',$datum)){$color="red";}
			  	}
			  	else
			  	{
			  		if (date('dmy')==date('dmy',$datum)){$color="red";}
			  	}
			  ?>
              <tr>
                <td colspan="3" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
              </tr>
              <tr>
                <td align="center" class="tableBody"><?php echo $row["sum"] ?></td>
                <td class="tableBody"><?php echo $myPT->cutString($row["pag_bez"],30,14); ?></td>
                <td class="tableMarker<?php echo $nr ?>"><img src="img/i_stat_<?php echo $color ?>.gif" width="<?php echo $x ?>" height="6"></td>
                
				<?php
				/*
				?>
				<td align="right" class="tableBody"><a href="#<?php echo $row["pag_id"] ?>" class="tableBody" onClick="MM_openBrWindow('statistics_pagedetail.php?id=<?php echo $row["pag_id"] ?>','','scrollbars=yes,resizable=yes,width=570,height=300')"><img src="img/b_view.gif" width="22" height="22" border="0" align="absmiddle"></a></td>
				<?php
				*/
				?>
              </tr>
			  <?php
			  }
?>
              <tr>
                <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
              </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
      </table>
	  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterWhite">&nbsp;</td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
        </tr>
      </table>
<?php
			  }
			  // -- Abrufe
			  // ####################################################
			  if ($mode==2)
			  {
?>
 <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap class="padding10">
				  <?php
				  $checked="";
				  if ($scope=="1"){$checked="checked";}
				  ?>				
                    <input type="radio" name="p<?php echo $nr ?>_scope" value="1" <?php echo $checked; ?> onclick="document.forms.form1.p<?php echo $nr ?>_newscope.value=1;document.forms.form1.submit();">                                         
                    <select name="p<?php echo $nr ?>_monat" class="listmenu" onChange="document.forms.form1.p<?php echo $nr ?>_newscope.value=1;document.forms.form1.submit();">
                       <?php
                       $aktuell = date("Ym",$datum);
                       foreach ($_options AS $K => $V)
                       {
                       	$selected="";
                       	if ($K == $aktuell)
                       	{
                       		$selected="selected";
                       	}
					   ?>
					   <option value="<?php echo $K ?>" <?php echo $selected ?>><?php echo $V ?></option>
					   <?php
                       }
					   ?>
                    </select></td>
                <td nowrap class="padding10">
                  <?php
                  $checked="";
                  if ($scope=="3"){$checked="checked";}
				  ?>
                  <input type="radio" name="p<?php echo $nr ?>_scope" value="3" <?php echo $checked; ?> onclick="document.forms.form1.p<?php echo $nr ?>_newscope.value=3;document.forms.form1.submit();"><strong><?php echo localeH("Overview");?></strong>
                  </td>					
                <td nowrap class="padding10">
<input name="p<?php echo $nr ?>_datum" type="hidden" class="input" style="width: 50px" value="<?php echo date("d.m.y",$datum) ?>">				
<input type="hidden" name="p<?php echo $nr ?>_anzahl" value="<?php echo $anzahl ?>">
					</td>
                </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
      </table>

<?php
if ($scope==1)
{
?>
					
	  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" class="tableHead"><?php echo localeH("Date");?></td>
                <td class="tableHead"><?php echo localeH("PIs");?></td>
                <td width="200" class="tableHead"><?php echo localeH("Chart");?> ( <img src="img/i_stat_legend.gif" width="5" height="8" align="absmiddle"> <?php echo localeH("Average value");?> )</td>
                <td class="tableHead">&nbsp;</td>
              </tr>			
<?php
$max=0;
$sum=0;
$startdatum = $myPT->lastDayinMonth($datum);
if ($startdatum > time()){$startdatum=time();}
$i=0;
$monat = date('m',$startdatum);
$vdatum = $startdatum;
while (date('m',$vdatum)==$monat)
{
	$sqldatum = date('Ymd',$vdatum);

	$sql = "SELECT SUM(sta_pageview) AS S FROM page_statistics LEFT JOIN page ON page_statistics.pag_id = page.pag_id WHERE sta_datum=" . $sqldatum;
	if ($grp_id!=-1)
	{
		$sql.= " AND page.grp_id = " . $grp_id;
	}

	$rs = $myDB->query($sql);
	$row=mysql_fetch_array($rs);
	$pi = $row["S"];
	if ($pi==""){$pi=0;}
	$sum += $pi;
	if ($pi>$max){$max=$pi;}

	$_pi[$i]=$pi;
	$i++;
	$vdatum = mktime( 0,0,0,date('m',$startdatum),date('d',$startdatum)-$i,date('Y',$startdatum));
}
$c_entry = $i-1;

if ($c_entry==0)
{
	$avg = $sum;
}
else
{
	$avg = $sum/$c_entry;
}
$pix = 200;
if ($max!=0){$avg = ceil($avg/$max*$pix);}else{$avg=0;}
?>			    
			  	  <style>
	  /* Dynamisierung der x-Position f�r den Mittelwert */
.tableMarker {
	padding: 5px 10px 5px 10px;
	background:  url(img/i_stat_marker.gif) no-repeat <?php echo $avg+8 ?>px 0px;
	}
	</style>
 <?php
 $color="red";
 $tag="<strong>".locale("Today")."</strong>";
 for ($i=0;$i<=$c_entry;$i++)
 {
 	$vdatum = mktime( 0,0,0,date('m',$startdatum),date('d',$startdatum)-$i,date('Y',$startdatum));
 	$tag = date('d.m.Y',$vdatum);
 	$color="blue";
 	if ($tag==date('d.m.Y'))
 	{
 		$color="red";
 		$tag="<strong>".locale("Today")."</strong>";
 	}

 	$pi=$_pi[$i];
 	if ($max>0){$x = ceil($pi/$max*$pix);}else{$x=0;}
	?>
              <tr>
                <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
              </tr>
              <tr>
                <td align="left" class="tableBody"><?php echo $tag ?></td>
                <td align="right" class="tableBody"><?php echo $pi ?></td>
                <td class="tableMarker"><img src="img/i_stat_<?php echo $color ?>.gif" width="<?php echo $x ?>" height="6"></td>
                <td>&nbsp;</td>
				</tr>
<?php
 }
} // -- Ende scope1
?>					
<?php
if ($scope==3)
{
?>
					
	  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" class="tableHead"><?php echo localeH("Month");?></td>
                <td class="tableHead"><?php echo localeH("PIs");?></td>
                <td width="185" class="tableHead"><?php echo localeH("Chart");?> ( <img src="img/i_stat_legend.gif" width="5" height="8" align="absmiddle"> <?php echo localeH("Average value");?> )</td>
                <td class="tableHead">&nbsp;</td>
              </tr>			
<?php
$max=0;
$sum=0;
$i=0;
// Das MonateArray von oben
foreach ($_options AS $K => $V)
{
	$sql = "SELECT SUM(sta_pageview) AS S FROM page_statistics LEFT JOIN page ON page_statistics.pag_id = page.pag_id WHERE sta_datum >" . $K ."00" . " AND sta_datum <" . $K . "99";
	if ($grp_id!=-1)
	{
		$sql.= " AND page.grp_id = " . $grp_id;
	}
	//echo $sql;
	$rs = $myDB->query($sql);
	$row=mysql_fetch_array($rs);
	$pi = $row["S"];
	if ($pi==""){$pi=0;}
	$sum += $pi;
	if ($pi>$max){$max=$pi;}

	$_pi[$i]=$pi;
	$i++;
}
$c_entry = $i-1;

if ($c_entry==0)
{
	$avg = $sum;
}
else
{
	$avg = $sum/$c_entry;
}
$pix = 185; // Damit der September ausgeschrieben werden kann
if ($max!=0){$avg = ceil($avg/$max*$pix);}else{$avg=0;}


?>			    
			  	  <style>
	  /* Dynamisierung der x-Position f�r den Mittelwert */
.tableMarker<?php echo $nr ?> {
	padding: 5px 10px 5px 10px;
	background:  url(img/i_stat_marker.gif) no-repeat <?php echo $avg+8 ?>px 0px;
	}
	</style>
 <?php  
 for ($i=0;$i<=$c_entry;$i++)
 {
 	$vdatum = mktime( 0,0,0,(date('m')-$i),1,date('Y'));
 	$monat = $_monat[date("n",$vdatum)-1] . "&nbsp;" . date("y",$vdatum);
 	$color="blue";
 	if ($i==0)
 	{
 		$color="red";
 		$monat="<strong>" . $monat . "</strong>";
 	}

 	//$pi=$_pi[$c_entry-$i];
 	$pi=$_pi[$i];
 	if ($max>0){$x = ceil($pi/$max*$pix);}else{$x=0;}
	?>
              <tr>
                <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
              </tr>
              <tr>
                <td align="left" class="tableBody"><?php echo $monat ?></td>
                <td align="right" class="tableBody"><?php echo $pi ?></td>
                <td class="tableMarker<?php echo $nr ?>"><img src="img/i_stat_<?php echo $color ?>.gif" width="<?php echo $x ?>" height="6"></td>
                <td>&nbsp;</td>
				</tr>
<?php
 }
} // -- Ende scope3
?>			

	
              <tr>
                <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
              </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
      </table>
	  <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterWhite">&nbsp;</td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
        </tr>
      </table>	
<?php
			  }
?>
  <?php
	}

	function renderPageBrowser($currentpage,$entries,$url,$itemcount=10,$forcedisplay=false)
	{
		global $myPT;
		$p=$currentpage;
		$anzahl=$entries;
		$myPT->startBuffer();
  	?>
  	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right" class="windowFooterWhite"><table border="0" cellpadding="0" cellspacing="1">
          <tr>
            <td align="center"><?php echo localeH("Page") ?>: </td>
			<?php
			$max=ceil($anzahl/$itemcount);
			$start = $p-3;
			$stop = $p+3;
			if ($start<1){$start=1;}
			if ($stop>$max){$stop=$max;}
			if ($p>1)
			{
			?>
            <td align="center"><a href="<?php echo $url.($p-1) ?>" class="tabmenuType"><?php echo localeH("prev") ?></a></td>
	        <td align="center">&nbsp;</td>
			<?php
			}

			for ($i=$start;$i<=$stop;$i++)
			{
				$active="";
				if ($p==$i){$active="Active";}

			?>
			<td align="center"><a href="<?php echo $url.($i) ?>" class="tabmenuType<?php echo $active ?>"><?php echo $i ?></a></td>
			<?php
			}
			if ($p!=$max)
			{
			?>
	        <td align="center">&nbsp;</td>
            <td align="center"><a href="<?php echo $url.($p+1) ?>" class="tabmenuType"><?php echo localeH("next") ?></a></td>
			<?php
			}
			?>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
	  <!--      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>-->
	  </table>

	<?php
	$html = $myPT->stopBuffer();

	// Seitenbl�ttern, nur wenn notwendig
	if ($anzahl<=$itemcount AND $forcedisplay==false){$html="";}
	return $html;

	}


	function ticketBox($title,$rs,$mode,$scope="Assess",$sbj_id=0,$dat_id=0,$focus=0,$sortorder=0,$block_nr=1)
	{

		global $myAdm;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTabTypeOnly"><strong><?php echo $this->getH($title) ?></strong></td>
	        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
	      </tr>
	    </table>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTask">
			<?php
			$this->listTickets($rs,$mode,$expand,$scope,$sbj_id,$dat_id,$focus,$sortorder,$block_nr);
		    ?>
			</td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	       <tr>
	        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
	        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
	      </tr>
	    </table>
		<br>
		<?php
	}

	function listTickets($rs,$mode=1,$expand=0,$scope="",$sbj_id=0,$dat_id=0,$focus=0,$sortorder=0,$block_nr=0)
	{

		if (mysql_num_rows($rs)==0){return false;}

		global $myPT;
		global $myRequest;
		global $myDB;


		$params ="&sbj_id=".$sbj_id."&dat_id=".$dat_id."&focus=".$focus."&sortorder=".$sortorder;


		$x=644;
		if ($expand==1)
		{
			$x=680;
		}

		$view_projects = (boolean)$myPT->getIPref("tickets.con_id_2ndorder");

		$_projects = false;
		if ($view_projects)
		{
			$_projects = Array();
			$con_id =$myPT->getIPref("tickets.con_id_2ndorder");
			$sql = "SELECT dat_id,dat_bez FROM content_data WHERE con_id=".$con_id;
			$rs2 = $myDB->query($sql);
			while ($row2=mysql_fetch_array($rs2))
			{
				$_projects[$row2["dat_id"]]=$row2["dat_bez"];
			}
		}

		switch ($mode)
		{
			case 2:
				$colspan=6;
				?>
				<table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
		          <tr>
		            <td><img src="img/transparent.gif" width="25" height="3"></td>
					<td><img src="img/transparent.gif" width="30" height="3"></td>
		            <td><img src="img/transparent.gif" width="*" height="3"></td>
		            <td><img src="img/transparent.gif" width="125" height="3"></td>
		            <td><img src="img/transparent.gif" width="112" height="3"></td>
		            <td><img src="img/transparent.gif" width="78" height="3"></td>
		            <td><img src="img/transparent.gif" width="10" height="3"></td>
		          </tr>
		          <tr>
		            <td valign="top" class="taskTopCorner"><img src="img/task_topcorner.gif" width="20" height="20"></td>
					<td class="taskHeaderGrey" align="right"><?php echo localeH("ID")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Title")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Realm")?><?php if ($view_projects){echo"/".$myPT->getPref("tickets.bez_2ndorder");} ?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Processors")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Action")?></td>
		            <td valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
		          </tr>
				<?php				
				break;
			default:
				$colspan=7;
				?>
				<table width="<?php echo $x ?>" border="0" cellpadding="0" cellspacing="0">
		          <tr>
		            <td><img src="img/transparent.gif" width="48" height="3"></td>
		            <td><img src="img/transparent.gif" width="90" height="3"></td>
					<td><img src="img/transparent.gif" width="30" height="3"></td>
		            <td><img src="img/transparent.gif" width="*" height="3"></td>
		            <td><img src="img/transparent.gif" width="125" height="3"></td>
		            <td><img src="img/transparent.gif" width="112" height="3"></td>
		            <td><img src="img/transparent.gif" width="78" height="3"></td>
		            <td><img src="img/transparent.gif" width="10" height="3"></td>
		          </tr>
		          <tr>
		            <td valign="top" class="taskTopCorner"><img src="img/task_topcorner.gif" width="20" height="20"></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Status")?></td>
					<td class="taskHeaderGrey" align="right"><?php echo localeH("ID")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Title")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Realm")?><?php if ($view_projects){echo"/".$myPT->getPref("tickets.bez_2ndorder");} ?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Processors/Creator")?></td>
		            <td class="taskHeaderGrey"><?php echo localeH("Action")?></td>
		            <td valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
		          </tr>
				<?php
				break;

		}

		while ($row = mysql_fetch_array($rs))
		{
			if ($mode==2)
			{
				$this->listTicketCompact($row,$scope,$params,$_projects,$block_nr);
			}
			else
			{
				$this->listTicketStandard($row,$scope,$params,$_projects,$block_nr);
			}
		}
		  ?>
      	  <tr>
        	<td colspan="<?php echo $colspan ?>" class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
        	<td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
      	  </tr>
		</table>
		<?php
	}


	function listTicketStandard($row,$scope,$params,$_projects,$block_nr)
	{
		$myUser = new PhenotypeUser();
		global $myPT;


		switch ($scope)
		{
			case "Process":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_process");
				break;
			case "Assess":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_assess");
				break;
			case "":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_editmode");
				break;
		}

		// Dann kommen wir aus dem Redaktionsmodus, beim Pinnen soll in Process gewechselt werden
		if ($scope==""){$scope="Process";}


		$letter = strtolower($row["tik_eisenhower"]);

		// Wenn ein Ticket noch nicht kalkuliert wurde, seit die Rueckstellung geendet hat
		if ($letter>"d" AND $row["tik_sleepdate"]<time() AND $row["tik_status"]==1)
		{
			$letter =  $eisenhower=chr(ord($letter)-4);
		}

		$color = "blue";
		if ($row["tik_lastaction"] < time()-(3600*24*7*2)) //  keine Aktivitaet in den letzten 2 Wochen
		{
			$color="grey";
		}

		$tage = time() - $row["tik_creationdate"];
		$tage = ceil($tage/(60*60*24));
		if ($tage==1){$color="orange";}
		if ($row["tik_complexity"]!=6)
		{ // Daueraufgaben k�nnen keinen Status Rot erhalten
			if ($row["tik_enddate"]<time()){$color="red";}
		}

		echo '<tr>';

		if ($row["tik_status"]==1)
		{
			echo '<td class="taskTopCorner" height="50">';
			echo '<a name="tik'.$row["tik_id"].'"></a>';
			if ($row["tik_sleepdate"]<time())
			{
				echo '<img src="img/t_'.$letter.'_'.$color.'.gif" width="48" height="36">';
			}
			else
			{
				echo'&nbsp;';
			}


			echo '</td><td class="taskData">';

			if ($row["tik_tendency"]==1)
			{
				echo'<img src="img/t_tendenz_plus.gif" alt="" width="17" height="17" vspace="1" border="0">';
			}

			if ($row["tik_tendency"]==2)
			{
				echo'<img src="img/t_tendenz_minus.gif" alt="" width="17" height="17" vspace="1" border="0">';
			}

			if ($row["tik_accepted"]==1)
			{
				echo '<img src="img/i_working.gif" width="25" height="18">';
			}

			if ($row["pag_id"]!=0)
			{
				echo '<a href="page_edit.php?id='.$row["pag_id"].'"><img src="img/t_attached.gif" alt="' . localeH("Task is connected to a page.").'" width="22" height="22" border="0"></a>';
			}

			if ($row["dat_id_content"]!=0)
			{
				echo'<a href="backend.php?page=Editor,Content,edit&id='.$row["dat_id_content"].';"><img src="img/t_attached_c.gif" alt="' . localeH("Task is connected to a content record.").'" width="22" height="22" border="0"></a>';
			}

			if ($row["med_id"]!=0)
			{
				echo '<a href="backend.php?page=Editor,Media,edit&id='.$row["med_id"].';&folder=-1&type=-1&sortorder=1&p=1&a=10"><img src="img/t_attached_m.gif" alt="' . localeH("Task is connected to a media object").'" width="22" height="22" border="0"></a>';
			}

			if ($row["tik_markup"]==1)
			{
				echo '<img src="img/t_notice.gif" alt="' . localeH("Ticket Notice").'" width="12" height="22" border="0">';
			}

			if ($row["tik_request"]==1)
			{
				echo '<img src="img/t_question.gif" alt="' . localeH("Pending question").'" width="12" height="22" border="0">';
			}

			echo '<br>';
			if ($row["tik_complexity"]!=6)
			{
				$w = floor($row["tik_percentage"]*48/100);
				?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                    	<table width="50" border="0" cellspacing="0" cellpadding="0">
                        	<tr>
	                          <td class="taskProgress"><img src="img/task_progressline.gif" width="<?php echo $w ?>" height="3" alt=" <?php echo $row["tik_percentage"] ?> %" title=" <?php echo $row["tik_percentage"] ?> %"></td>
    	                    </tr>
        	            </table>
				    </td>
                  </tr>
              </table>
			<?php
			}
			echo '</td>';

		}
		else
		{
			$color="black";
			?>
    		<td class="taskTopCorner" height="45">&nbsp;</td>
 	  		<td align="center" class="taskData">
 	  		<?php
 	  		if ($row["tik_percentage"]!=100)
 	  		{
 	  			echo'<img src="img/t_closed0.gif" width="17" height="17" hspace="1">';
 	  		}
 	  		else
 	  		{
 	  			echo '<img src="img/t_closed100.gif" width="17" height="17" hspace="1">';
 	  		}
			?>
 	  		<img src="img/t_closed.gif" alt="' . localeH("Ticket closed").'" width="22" height="22" border="0">
 	  		</td>
 	  	<?php
		}
		?>
			<td valign="top" class="taskData" align="right"><?php echo $row["tik_id"] ?></td>
            <td valign="top" class="taskData"><?php echo $row["tik_bez"] ?></td>
            <td valign="top" class="taskData">
            <?php
            echo $row["sbj_bez"];
            if ($_projects)
            {
            	echo "<br/>".$_projects[$row["dat_id_2ndorder"]];
            }
            ?>
            </td>
			<td valign="top" class="taskData"><strong class="<?php echo $color ?>">
			<?php echo $myUser->getName($row["usr_id_owner"]) ?></strong><br>
     		<?php echo $myUser->getName($row["usr_id_creator"]) ?>
     		</td>
            <td align="center" class="taskDataLastCell">
            <?php

            $url_pin = "backend.php?page=Ticket,".$scope.",pin&id=" . $row["tik_id"]. "&b=".$block_nr. $params;
            //."#tik".$row["tik_id"];
            if ($row["tik_pin"]==0 AND $row["tik_status"]==1)
            {
            	echo '<a href="'.$url_pin.'"><img src="img/b_pinadd.gif" title = "' . localeH("Add ticket to watch list.").'" alt="' . localeH("Add ticket to watch list.").'" width="22" height="22" border="0"></a>';
            }
            if ($row["tik_pin"]==1)
            {
            	echo '<a href="'.$url_pin.'"><img src="img/b_pinremove.gif" title = "' . localeH("Remove ticket from watch list.").'" alt="' . localeH("Remove ticket from watch list.").'" width="22" height="22" border="0"></a>';
            }




            if ($popup)
            {
            	echo '<a href="#" onclick="ticketLog('.$row["tik_id"].');return false();"><img src="img/b_view.gif" alt="Verlauf sichten" width="22" height="22" border="0"></a>';
            }
            else
            {
            	$url_view = "backend.php?page=Ticket,Process,edit&id=".$row["tik_id"]. "&b=3" . $params;
            	echo '<a href="'.$url_view.'"><img src="img/b_view.gif" alt="' . localeH("View ticket log").'" width="22" height="22" border="0"></a>';
            }
            $url_edit = "backend.php?page=Ticket,Process,edit&id=".$row["tik_id"]. "&b=1".  $params;
            echo '<a href="'.$url_edit.'"><img src="img/b_edit.gif" alt="' . localeH("Process task.").'" width="22" height="22" border="0"></a>';
			?>
            </td>
            <td width="10" class="windowRightShadow">&nbsp;</td>
          </tr>
		<?php
	}

	function listTicketCompact($row,$scope,$params,$_projects,$block_nr)
	{
		global $myPT;
		$myUser = new PhenotypeUser();

		$letter = strtolower($row["tik_eisenhower"]);

		switch ($scope)
		{
			case "Process":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_process");
				break;
			case "Assess":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_assess");
				break;
			case "":
				$popup = $myPT->getIPref("tickets.popup_ticketaction_editmode");
				break;
		}


		// Dann kommen wir aus dem Redaktionsmodus, beim Pinnen soll in Process gewechselt werden
		if ($scope==""){$scope="Process";}

		// Wenn ein Ticket noch nicht kalkuliert wurde, seit die Rueckstellung geendet hat
		if ($letter>"d" AND $row["tik_sleepdate"]<time() AND $row["tik_status"]==1)
		{
			$letter =  $eisenhower=chr(ord($letter)-4);
		}

		$color = "blue";
		if ($row["tik_lastaction"] < time()-(3600*24*7*2)) //  keine Aktivitaet in den letzten 2 Wochen
		{
			$color="grey";
		}

		$tage = time() - $row["tik_creationdate"];
		$tage = ceil($tage/(60*60*24));
		if ($tage==1){$color="orange";}
		if ($row["tik_complexity"]!=6)
		{ // Daueraufgaben k�nnen keinen Status Rot erhalten
			if ($row["tik_enddate"]<time()){$color="red";}
		}

		echo '<tr>';

		if ($row["tik_status"]==1)
		{
			echo '<td class="taskTopCorner" height="26">';
			echo '<a name="tik'.$row["tik_id"].'"></a>';

			if ($row["tik_sleepdate"]<time())
			{
				echo '<img src="img/t_'.$letter.'_'.$color.'.gif" width="24" height="18" hspace="1">';
			}
			else
			{
				echo'&nbsp;';
			}


			echo '</td>';

		}
		else
		{
			$color="black";

			echo '<td class="taskTopCorner" height="26">';
			echo '<a name="tik'.$row["tik_id"].'"></a>';
			echo '<img src="img/t_closed_white.gif" alt="' . localeH("Ticket closed").'" width="25" height="22" border="0"></td>';

		}
		?>
			<td valign="top" class="taskData" align="right"><?php echo $row["tik_id"] ?></td>
            <td valign="top" class="taskData"><?php echo $row["tik_bez"] ?></td>
            <td valign="top" class="taskData">
            <?php
            echo $row["sbj_bez"];
            if ($_projects)
            {
            	echo "<br/>".$_projects[$row["dat_id_2ndorder"]];
            }
            ?>
            </td>
			<td valign="top" class="taskData"><strong class="<?php echo $color ?>">
			<?php echo $myUser->getName($row["usr_id_owner"]) ?></strong>
     		</td>
            <td align="center" class="taskDataLastCell">
            <?php

            $url_pin = "backend.php?page=Ticket,".$scope.",pin&id=" . $row["tik_id"]. "&b=".$block_nr. $params;
            //."#tik".$row["tik_id"];
            if ($row["tik_pin"]==0 AND $row["tik_status"]==1)
            {
            	echo '<a href="'.$url_pin.'"><img src="img/b_pinadd.gif" title = "'.localeH("Add ticket to watch list.").'" alt="'.localeH("Add ticket to watch list.").'" width="22" height="22" border="0"></a>';
            }
            if ($row["tik_pin"]==1)
            {
            	echo '<a href="'.$url_pin.'"><img src="img/b_pinremove.gif" title = "'.localeH("Remove ticket from watch list.").'" alt="'.localeH("Remove ticket from watch list.").'" width="22" height="22" border="0"></a>';
            }




            if ($popup)
            {
            	echo '<a href="#" onclick="ticketLog('.$row["tik_id"].');return false();"><img src="img/b_view.gif" alt="Verlauf sichten" width="22" height="22" border="0"></a>';
            }
            else
            {
            	$url_view = "backend.php?page=Ticket,Process,edit&id=".$row["tik_id"]. "&b=3" . $params;
            	echo '<a href="'.$url_view.'"><img src="img/b_view.gif" alt="'.localeH("View ticket log").'" width="22" height="22" border="0"></a>';
            }
            $url_edit = "backend.php?page=Ticket,Process,edit&id=".$row["tik_id"]. "&b=1".  $params;
            echo '<a href="'.$url_edit.'"><img src="img/b_edit.gif" alt="'.localeH("Process task").'" width="22" height="22" border="0"></a>';
			?>       </td>
            <td width="10" class="windowRightShadow">&nbsp;</td>
          </tr>
		<?php
	}



	/**
	 * render Drag & Drop Zone
	 *
	 * @param integer $width
	 * @param integer $height
	 * @param string $target_url
	 * @param string Message to be displayed or url to be loaded
	 * @return string
	 */
	public function workarea_form_ddupload($width,$height,$target_url,$message="Drag & Drop - Upload",$md5hash="")
	{
		global $myPT;

		$target_url = $target_url ."&md5=".$md5hash;
		
		if ($myPT->getPref("backend.rad_upload")=="plus")
		{
			$html = $this->workarea_form_ddupload_radplus($width,$height,$target_url,$message,$md5hash);
		}
		else
		{
			$html = $this->workarea_form_ddupload_radlite($width,$height,$target_url,$message,$md5hash);
		}
		return $html;
	}

	protected function workarea_form_ddupload_radplus($width,$height,$target_url,$message,$md5hash)
	{
		$id = "rad";
		$pathToRad = ADMINFULLURL;

		$html = '
		<script type="text/javascript">
		var _info = navigator.userAgent;
        var ie = (_info.indexOf("MSIE") > 0);
        var win = (_info.indexOf("Win") > 0);
        if(win)
        {

            if(ie)
            {
		         document.writeln(\'<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"\');
		         document.writeln(\'      width= "'.$width.'" height= "'.$height.'" id="'.$id.'"\');
		         document.writeln(\'      codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">\');
            }
            else
            {
                document.writeln(\'<object type="application/x-java-applet;version=1.4.1"\');
                document.writeln(\'width= "'.$width.'" height= "'.$height.'"  id="'.$id.'" >\');
            }
            document.writeln(\'<param name="archive" value="'.$pathToRad.'dndplus.jar">\');
            document.writeln(\'<param name="code" value="com.radinks.dnd.DNDAppletPlus">\');
            document.writeln(\'<param name="name" value="Rad Upload Plus">\');

        }
        else
        {
            /* mac and linux */
            document.writeln(\'<applet \');
            document.writeln(\'         archive  = "'.$pathToRad.'dndplus.jar"\');
            document.writeln(\'         code     = "com.radinks.dnd.DNDAppletPlus"\');
            document.writeln(\'         name     = "Rad Upload Plus"\');
            document.writeln(\'         hspace   = "0"\');
            document.writeln(\'         vspace   = "0" MAYSCRIPT="yes"\');
            document.writeln(\'         width = "'.$width.'"\');
            document.writeln(\'         height = "'.$height.'"\');
            document.writeln(\'         align    = "middle" id="'.$id.'">\');
        }

		/******    BEGIN APPLET CONFIGURATION PARAMETERS   ******/

		document.writeln(\'<param name="max_upload" value="0">\');
		document.writeln(\'<param name="message" value="<br\>&nbsp;'.codeH($message).'">\');
		document.writeln(\'<param name="url" value="'.$target_url.'" />\');
		//document.writeln(\'<param name="props_file" value="'.$pathToRad.'radupload_properties.php" />\');

		/******    END APPLET CONFIGURATION PARAMETERS     ******/
	    if(win)
		{
			document.writeln(\'</object>\');
		}
		else
		{
		  document.writeln(\'</applet>\');
		}
		</script>';
		return $html;

	}

	protected function workarea_form_ddupload_radlite($width,$height,$target_url,$message,$md5hash)
	{
		global $myPT;
		$myPT->startBuffer();

		if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
		{
		?>
		<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
			width= "<?php echo $width ?>" height= "<?php echo $height ?>"  
			codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">
		<?php 		
		} 
		else
		{
		?>
		<object type="application/x-java-applet;version=1.4.1"
			width= "<?php echo $width ?>" height= "<?php echo $height ?>"  
		<?php 	
		}
		?>
		<param name="archive" value="<?php echo ADMINFULLURL ?>dndlite.jar">
		<param name="code" value="com.radinks.dnd.DNDAppletLite">
		<param name="name" value="Rad Upload Lite">
   		<param name = "url" value = "<?php echo $target_url?>"> 
		<param name = "message" value="<br\>&nbsp;<?php echo codeH($message)?>">
   		<?php
   		if (isset ($_SERVER['PHP_AUTH_USER']))
   		{
   			printf('<param name="chap" value="%s">', base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']));
   		}
   		?>	
   		</object>
   		<?php
   		$html = $myPT->stopBuffer();
   		return $html;
	}
}



