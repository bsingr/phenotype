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

/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Session_Standard extends PhenotypeBackend
{

  // PhenotypeBackend_Session-Classes don't have their own localization file. It's because some session/login/rights related functions
  // are located in the PhenotypeBackendStandard class.
  
  public $tmxfile = "Phenotype";
  
	function execute($scope,$action)
	{
		if ($scope=="" AND $action=="cover")
		{
			$buffer = file_get_contents("img/pt_cover_error.jpg");
			Header("Content-Type: image/jpeg");
			echo $buffer;
			exit();

		}
		session_start();
		session_destroy();
		$this->displayLogin();
	}

	function displayLogin()
	{
		global $myApp;
		global $myPT;

		$img_id = $myPT->getIPref("backend.img_id_cover"); // 385 x 145
		if ($img_id==0)
		{
			$url = 'img/pt_cover.jpg';
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
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="phenotype.css" rel="stylesheet" type="text/css">
		<link href="navigation.css" rel="stylesheet" type="text/css">
		<link href="content.css" rel="stylesheet" type="text/css">
		</head>
		<body>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="320" class="top"><a href="http://www.phenotype.de" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0"><img src="img/phenotype_typo.gif" width="97" height="27" border="0"></a></td>
		  </tr>
		  <tr>
		    <td height="32" class="topShadow">&nbsp;</td>
		  </tr>
		</table>
		<table width="640" height="480" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td>
			<form action="backend.php" method="post" name="login">
			<input type="hidden" name="page" value="Session,Login"/>
			  <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="windowFooterGrey2">
		            <table width="100%" border="0" cellspacing="0" cellpadding="0">
		              <tr valign="bottom">
		                <td height="145" colspan="2" style="background:url(<?php echo $url ?>) no-repeat top left;"><div class="login"><?php
		                echo $myApp->getLoginInfoText();
						?></div></td>
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
		      
		      <script type="text/javascript">
		      <!--
		      document.forms.login.user.focus();
		      -->
		      </script>
		      
			  </form></td>
		  </tr>
		</table>
		</body>
		</html>
		<?php
	}

	function displayLoginRetry()
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="content.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="320" class="top"><a href="http://www.phenotype.de" target="_blank"><img src="img/phenotype_ani_logo.gif" width="27" height="27" border="0"><img src="img/phenotype_typo.gif" width="97" height="27" border="0"></a></td>
  </tr>
  <tr>
    <td height="32" class="topShadow">&nbsp;</td>
  </tr>
</table>
<table width="640" height="480" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <form action="backend.php" method="post" name="login">
	  <input type="hidden" name="page" value="Session,Login"/>
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterGrey2">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="bottom">
                  <td height="145" colspan="2" style="background:url(<?php echo $url ?>) no-repeat top left;"><div class="alert"><?php echo localeHBR("msg_login_error");?></div></td>
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

		      <script type="text/javascript">
		      <!--
		      document.forms.login.user.focus();
		      -->
		      </script>
		      
			  </form></td>
		  </tr>
		</table>
		</body>
		</html>
<?php
	}
}
?>