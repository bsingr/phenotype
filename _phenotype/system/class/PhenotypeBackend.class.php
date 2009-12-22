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
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeBackendStandard extends PhenotypeLayout
{


	/**
	 * "ID" of the backend page. This id is available within the Javascript variable pt_bak_id all time
	 *
	 * @var unknown_type
	 */
	public $bak_id = "_";

	/**
   * Name of TMX-File to load for localization, e.g. "Phenotype".
   *
   * Please leave the default value empty, since PhenotypeBackend-Classes might be constructed elsewhere and you don't want to loose
   * your TMX scope then.
   * 
   * @var string
   */
	public $tmxfile = "";
	
	public $lay_nr = 1;
	public $menu_item = 0;

	public $title = "Phenotype";


	public $html_leftarea = "";
	public $html_contentarea1 = "";
	public $html_contentarea2 = "";


	var $props_tab = Array();
	var $props_iconbar = Array();


	public $_params = Array(); // parameter array for gotoPage actions

	// main menu entries
	
	const menu_start         = 1;
	const menu_editor        = 2;
	const menu_extras        = 3;
	const menu_analyze       = 4;
	const menu_ticket        = 5;
	const menu_admin         = 6;
	const menu_configuration = 7;
	const menu_info          = 8;
	
	// layout variants

	const layout_default     = 1; 		//  Explorer +Content
	const layout_blank       = 2;
		

	function __construct()
	{
	  global $myPT;
		if ($this->tmxfile!="")
		{
			$myPT->loadTMX($this->tmxfile, PT_LOCALE);
		}
	}
	
	
	function execute($scope,$action)
	{
		global $myRequest;
	}

	function gotoPage($page,$scope,$action,$_params = Array(),$marker="")
	{
		$url = "backend.php?page=" . $page .",".$scope.",".$action;
		foreach ($_params AS $k=>$v)
		{
			$url .="&".$k."=".urlencode($v);
		}
		if (SID)
		{
			$url .=SID;
		}
		if ($marker!="")
		{
			$url .="#".$marker;
		}

		Header ("Location:" . $url);
		exit();
	}

	function noAccess()
	{
		global $myPT;
		ob_get_clean();
		
		$img_id = $myPT->getIPref("backend.img_id_cover_error"); // 385 x 145
		if ($img_id==0)
		{
			$url = 'img/pt_cover_error.jpg';
		}
		else
		{
			$myImg = new PhenotypeImage($img_id);
			$url = $myImg->url;
		}
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phenotype <?php echo $myPT->version ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PT_CHARSET?>">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="320" class="top"><a href="http://www.phenotype-cms.com" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0"alt=""><img src="img/phenotype_typo.gif" width="97" height="27" border="0" alt=""></a></td>
  </tr>
  <tr>
    <td height="32" class="topShadow">&nbsp;</td>
  </tr>
</table>
<table width="640" height="480" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <form action="backend.php" method="post">
	  <input type="hidden" name="page" value="Session,Login"/>
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterGrey2">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="bottom">
                  <td height="145" colspan="2"  style="background:url(<?php echo $url ?>) no-repeat top left;"><div class="alert"><?php echo localeHBR("msg_no_access");?><br>
                  </div></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                  <td colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Username");?>:</td>
                  <td><input type="text" name="user" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Password");?>:</td>
                  <td><input type="password" name="pass" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("Login");?>" style="width:102px"></td>
                </tr>
            </table></td>
            <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
          </tr>
          <tr>
            <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
            <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
          </tr>
        </table>

</body>
</html>
<?php
exit();
	}

	function noSession($uri="")
	{
		global $myPT;

		$img_id = $myPT->getIPref("backend.img_id_cover_error"); // 385 x 145
		if ($img_id==0)
		{
			$url = 'img/pt_cover_error.jpg';
		}
		else
		{
			$myImg = new PhenotypeImage($img_id);
			$url = $myImg->url;
		}
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo $myPT->version ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PT_CHARSET?>">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="320" class="top"><a href="http://www.phenotype-cms.com" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0"><img src="img/phenotype_typo.gif" width="97" height="27" border="0"></a></td>
  </tr>
  <tr>
    <td height="32" class="topShadow">&nbsp;</td>
  </tr>
</table>
<table width="640" height="480" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <form action="backend.php" method="post">
	  <input type="hidden" name="page" value="Session,Login"/>
	  <input type="hidden" name="uri" value="<?php echo codeH($uri)?>"/>
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterGrey2">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="bottom">
                  <td height="145" colspan="2"  style="background:url(<?php echo $url ?>) no-repeat top left;"><div class="alert"><?php echo localeH("msg_session_timeout");?></div></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                  <td colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Username");?>:</td>
                  <td><input type="text" name="user" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Password");?>:</td>
                  <td><input type="password" name="pass" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("Login");?>" style="width:102px"></td>
                </tr>
            </table></td>
            <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
          </tr>
          <tr>
            <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
            <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
          </tr>
        </table>

</body>
</html>
<?php
exit();
	}

	function selectLayout($lay_nr)
	{
		$this->lay_nr = $lay_nr;
  }

	function selectMenuItem($nr)
	{
		$this->menu_item = $nr;

	}

	function setPageTitle($s)
	{
		$this->title = $s;
	}

	function checkRight($key,$noaccess=false)
	{
		global $mySUser;
		if ($noaccess)
		{
			if (!$mySUser->checkRight($key))
			{
				$this->noAccess();
			}
		}
		return ($mySUser->checkRight($key));
	}

	function getUserName()
	{
		global $mySUser;
		return ($mySUser->getName());
	}

	function xmlencode($s,$keepquotes=0)
	{
		$s = str_replace("&","&#38;",$s);
		$s = str_replace("<","&#60;",$s);
		$s = str_replace(">","&#62;",$s);
		$s = str_replace("'","&#39;",$s);
		$s = str_replace('"',"&#34;",$s);

		return $s;
	}

	function getX($s)
	{
		return ($this->xmlencode($s));
	}

	function getH($s)
	{
		return @ htmlentities($s,null,PT_CHARSET);
	}

	function getI($s)
	{
		return (int)$s;
	}


	function fillLeftArea($html,$add = false)
	{
		if ($add)
		{
			$this->html_leftarea .= $html;
		}
		else
		{
			$this->html_leftarea = $html;
		}
	}

	function fillContentArea1($html,$add = false)
	{
		if ($add)
		{
			$this->html_contentarea1 .= $html;
		}
		else
		{
			$this->html_contentarea1 = $html;
		}
	}

	function fillContentArea2($html,$add = false)
	{
		if ($add)
		{
			$this->html_contentarea2 .= $html;
		}
		else
		{
			$this->html_contentarea2 = $html;
		}
	}

	function displayPage($body_onload="")
	{
		global $myApp;

		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>' . $this->getH($this->title) .'</title>
<meta http-equiv="Content-Type" content="text/html; charset='.  PT_CHARSET.'">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<link href="site.css" rel="stylesheet" type="text/css">
<link href="task.css" rel="stylesheet" type="text/css">
<link href="image-crop.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
<link href="css/jqueryui/phenotype/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="backend.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/jquery-ui.js"></script>
<script type="text/javascript">var pt_bak_id="'.$this->bak_id.'";</script>
<script type="text/javascript" src="phenotype.js"></script>
';
		$myApp->displayBackendJavascript();

		echo '<script type="text/javascript" src="wz_dragdrop.js"></script>
		<script type="text/javascript" src="tw-sack.js"></script>
		<script type="text/javascript" src="backend.js"></script>
</head>';

		if ($body_onload!="")
		{
			echo '<body onload="WZDHTML_RESUME();'.$body_onload.'">';
		}
		else
		{
			echo '<body onload="WZDHTML_RESUME()">';
		}

		switch ($this->lay_nr)
		{
			default:
				$this->displayTopLine();
				echo '<table width="995" border="0" cellspacing="0" cellpadding="0"><tr><td width="30">&nbsp;</td><td width="270" valign="top">';

				echo $this->html_leftarea;

				echo '</td><td width="700" valign="top" id="content">';

				echo $this->html_contentarea1;

				echo '</td></tr></table>';

				break;
			case 2:
				echo $this->html_contentarea1;
				break;

		}

		echo '</body></html>';
	}


	function displayTopLine()
	{
		$_menu = Array();

		$_menu[1] = Array (locale("Start"),"backend.php?page=Editor,Start");

		if ($this->checkRight("elm_redaktion"))
		{
			$_menu[2] = Array (locale("Editor"),"backend.php?page=Editor");
		}

		
		if ($this->checkRight("elm_extras"))
		{
			$_menu[3] = Array (locale("Extras"),"backend.php?page=Extras");
		}

		if ($this->checkRight("elm_analyse"))
		{
			$_menu[4] = Array (locale("Analyze"),"backend.php?page=Analyze,Pages");
		}

		if ($this->checkRight("elm_task"))
		{
			$_menu[5] = Array (locale("Tasks"),"backend.php?page=Ticket,Assess");
		}

		$_menu[6] = Array (locale("Admin"),"backend.php?page=Admin");

		if ($this->checkRight("superuser"))
		{
			$_menu[7] = Array (locale("Config"),"backend.php?page=Config");
		}

		$_menu[8] = Array (locale("Info"),"backend.php?page=Info");

		echo '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="300" class="top"><a href="http://www.phenotype-cms.com" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0" alt=""><img src="img/phenotype_typo.gif" width="97" height="27" border="0" alt=""></a></td>
	<td width="430" class="top"><table  border="0" cellpadding="0" cellspacing="0">
	<tr>';


		foreach ($_menu as $k => $v)
		{
			if ($k==$this->menu_item)
			{
				echo '<td class="kopfmenu"><a href="'.$v[1].'" class="topmenuActive">' . $this->getH($v[0]).'</a></td>';
			}
			else
			{
				echo '<td class="kopfmenu"><a href="'.$v[1].'" class="topmenu">' . $this->getH($v[0]).'</a></td>';

			}
		}

		echo '</tr>
	</table></td>
	<td align="right" nowrap class="top">'.localeH("User").': '.$this->getUserName(). '<a href="backend.php?page=Session,Logout"><img src="img/topbuttonclose.gif" width="30" height="27" border="0" align="absmiddle" alt=""></a></td>
	</tr>
	<tr>
	<td height="32" colspan="3" class="topShadow">&nbsp;</td>
	</tr>
	</table>';

	}



	function displayEAIF()
	{
		global $myRequest;

		$text = $myRequest->get("feedback");
		if ($text!=""){$this->displayFeedback($text);}

		$text = $myRequest->get("error");
		if ($text!=""){$this->displayError($text);}

		$text = $myRequest->get("alert");
		if ($text!=""){$this->displayAlert($text);}

		$text = $myRequest->get("info");
		if ($text!=""){$this->displayInfo($text);}

	}

	function displayError($text)
	{
		global $myPT;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	            <tr>
	              <td class="windowError"><h1><?php echo localeH("Error");?></h1>
				    <p><?php echo $myPT->codeHKT($text) ?></p></td>
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

	function displayAlert($text)
	{
		global $myPT;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	            <tr>
	              <td class="windowAlert"><h1><?php echo localeH("Alert");?></h1>
				    <p><?php echo $myPT->codeHKT($text) ?></p></td>
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

	function displayInfo($text)
	{
		global $myPT;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	            <tr>
	              <td class="windowInfo"><h1><?php echo localeH("Info");?></h1>
				    <p><?php echo $myPT->codeHKT($text) ?></p></td>
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

	function displayFeedback($text)
	{
		global $myPT;
		if ($text==""){$text="Änderungen gespeichert.";}
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0" id="feedback">
	      <tr>
	        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	            <tr>
	              <td class="windowFeedback"><h1><?php echo localeH("Feedback");?></h1>
				    <p><?php echo $myPT->codeHKT($text) ?></p></td>
	              </tr>
	        </table></td>
	        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
	      </tr>
	      <tr>
	        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
	        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif"></td>
	      </tr>
	    </table>
	    <script>window.setTimeout('hide("feedback")',3*1000);</script>
		<?php	
	}



	// Neue Layoutfunktionen - müssen nach Migration runterkopiert werden


	function displayHeadline($title,$url_help="")
	{
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo $this->getH($title) ?></td>
            <?php if ($url_help!=""){ ?>
            <td align="right" class="windowTitle"><a href="<?php echo urlencode($url_help) ?>" target="_blank"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a></td>
            <?php } ?>
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

	function renderHeadline($title,$url_help="")
	{
		global $myPT;$myPT->startBuffer();$this->displayHeadline($tile,$url_help);return($myPT->stopBuffer());
	}

	function displayContentTableHead($_table)
	{
		?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3"></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <?php
            foreach ($_table AS $x => $title)
            {
            	echo '<td width="'.$x.'" class="tableHead">'.$this->getH($title).'</td>';
            }
            ?>
            </tr>
            <tr>
              <td colspan="<?php echo count($_table) ?>" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
		<?php
	}


	function displayContentTableRow($_row,$_html = Array(),$_align=Array(),$_nowrap=Array())
	{
		$n = count($_row);
		$i=0;
		echo '<tr>';
		foreach ($_row AS $v)
		{
			$align="";
			if ($_align[$i]!="")
			{
				$align=' align="'.$_align[$i].'"';
			}
			$nowrap="";
			if ((int)$_nowrap[$i]!=0)
			{
				$nowrap=' nowrap';
			}

			$i++;
			echo '<td class="tableBody"'.$align.$nowrap.' >';
			if (in_array($i,$_html))
			{
				echo $v;
			}
			else
			{
				echo $this->getH($v);
			}
			echo'</td>';
		}


		echo '</tr><tr><td colspan="'.$n.'" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td></tr>';


		return;

	}


	function displayContentTableFoot()
	{
		?>
		</table></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
		<?php
	}


	function displayContentTableButton($title,$url)
	{
		echo '<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowFooterGrey2"><a href="'.$this->getH($url).'" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> '.$this->getH($title).'</a></td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>';

	}

	function workarea_row_deletesave($confirmstring)
	{
		?>
	
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite"><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="<?php echo localeH("Delete");?>" onclick="javascript:return confirm('<?php echo $confirmstring ?>')">&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save");?>">&nbsp;&nbsp;</td>
          </tr>
        </table>
    <?php
	}


	function workarea_row_headline($s,$spacer=false)
	{
		global $myPT;

		if ($spacer==true)
		{
			$this->workarea_stop_draw();
			$this->workarea_start_draw();
		}
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php
		if ($spacer==true)
		{
		?>
			<tr>
          		<td class="tableHline" colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
        	</tr>
		<?php
		}
		?>
		<tr>
          <td class="tableHead" colspan="2"><?php echo $myPT->codeH($s) ?></td>
        </tr>
        <tr>
          <td class="tableHline" colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
        </tr>
        </table>
		<?php
	}

	function optionTag($key,$value,$selectedkey="")
	{
		global $myPT;
		$selected="";
		if ($selectedkey!="")
		{
			if ($key==$selectedkey)
			{
				$selected = 'selected="selected"';
			}
		}
		$html = '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		return $html;
	}


	function listSnapshots($sna_type,$key_id,$page,$scope)
	{
		global $myDB;
		global $mySUser;

		$sql = "SELECT sna_id,usr_id,sna_date FROM snapshot WHERE sna_type='".mysql_real_escape_string($sna_type)."' AND key_id=".$key_id . " ORDER BY sna_date DESC";
		$rs = $myDB->query($sql);
		?>
		
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          <tr>
	          <tr>
	            <td width="20" class="tableHead"><?php echo localeH("ID");?></td>
	            <td width="120" class="tableHead"><?php echo localeH("Date");?></td>
	            <td width="80" class="tableHead"><?php echo localeH("before");?></td>
	            <td width="*" class="tableHead"><?php echo localeH("User");?></td>
	            <td width="50" align="right" class="tableHead"><?php echo localeH("Action");?></td>
	            </tr>
			  <tr>
            </tr>
		  <tr>
            <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
          <?php
          while ($row=mysql_fetch_array($rs))
          {
	?>
		   <tr>
            <td class="tableBody"><?php echo $row["sna_id"] ?></td>
			<td class="tableBody"><?php echo date('d.m.Y - H:i:s',$row["sna_date"]) ?></td>
			<td width="50" class="tableBody"><?php
			$sekunden = time()-$row["sna_date"];
			$minuten = $sekunden / 60;
			if ($minuten>60)
			{
				$stunden = $minuten / 60;
				if ($stunden>24)
				{
					$tage = mktime(0,0,0) - mktime(0,0,0,date("m",$row["sna_date"]),date("d",$row["sna_date"]),date("Y",$row["sna_date"]));
					echo floor($tage / (3600*24)) . " Tag(e)";
				}
				else
				{
					echo floor($stunden). " ".locale("hours");
				}
			}
			else
			{
				echo floor($minuten). " ".locale("minutes");
			}

			?></td>
            <td class="tableBody"><?php echo $mySUser->getName($row["usr_id"]) ?></td>
			<td align="right" nowrap class="tableBody"><a href="backend.php?page=<?php echo $page ?>,<?php echo $scope ?>,viewsnapshot&id=<?php echo $row["sna_id"] ?>&sna_type=<?php echo $sna_type ?>" target="_blank"><img src="img/b_view.gif" alt="Snapshot ansehen" width="22" height="22" border="0" align="absmiddle"></a> <a href="backend.php?page=<?php echo $page ?>,<?php echo $scope ?>,installsnapshot&id=<?php echo $row["sna_id"] ?>&sna_type=<?php echo $sna_type ?>"><img src="img/b_rollback.gif" alt="Snapshot installieren" width="22" height="22" border="0" align="absmiddle"></a></td>
            
            </tr>
            <?php } ?>
            <tr>
            <td colspan="5" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
          </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
   		<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br> 
	</td></tr></table></body></html>
		
		
		
		
		
		

		<?php
	}

	function viewSnapshot($sna_id)
	{
		global $myDB;
		$this->checkRight("superuser",true);

		$sql ="SELECT * FROM snapshot WHERE sna_id=".$sna_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0){return;}
		$row = mysql_fetch_array($rs);
		$xml = $row["sna_xml"];
		if ($row["sna_zip"]==1)
		{
			$xml = gzuncompress($xml);
		}

		Header ("Content-type: text/xml");
		echo $xml;
	}

	function installSnapshot($sna_id,$sna_type)
	{
		global $myDB;
		global $myLog;
		
		$this->checkRight("superuser",true);

		$sql ="SELECT * FROM snapshot WHERE sna_id=".$sna_id . " AND sna_type='".mysql_real_escape_string($sna_type)."'";

		$rs = $myDB->query($sql);

		if (mysql_num_rows($rs)==0){return false;}
		
		$row = mysql_fetch_array($rs);
		$xml = $row["sna_xml"];
		
		if ($row["sna_zip"]==1)
		{
			$xml = gzuncompress($xml);
		}
	
		switch ($sna_type)
		{
			case "CO":
				$cname = "PhenotypeContent_".$row["sec_id"];
				$myCO = new $cname($row["key_id"]);
				$dat_id = $myCO->rawXMLDataImport($xml);
				if ($dat_id)
				{
					$myCO->load($dat_id);
					$myLog->log("Snapshot " . $sna_id . " des Contentobjekts " . $myCO->id . " eingespielt.",PT_LOGFACILITY_SYS);
	
					$myCO->snapshot();
					return ($myCO);
				}
				break;
			case "MO":
				$myObj = new PhenotypeMediaObject();
				$med_id = $myObj->rawXMLImport($xml);

				if ($med_id)
				{
					$myObj = new PhenotypeImage($med_id);
					if ($myObj->id != 0)
					{
						// Bild
						$type = MB_IMAGE;
					} else
					{
						$type = MB_DOCUMENT;
						$myObj = new PhenotypeDocument($med_id);

						if ($myObj->id == 0)
						{
							$this->noAccess();
						}
					}
					$myLog->log("Snapshot " . $sna_id . " des Mediaobjekts " . $myObj->id . " eingespielt.",PT_LOGFACILITY_SYS);
	
					$myObj->snapshot();
					return ($myObj);
				}
				break;

		}
		return false;

	}
	

	
}
?>
