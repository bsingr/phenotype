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
class PhenotypeBackend_Info_Standard extends PhenotypeBackend
{
	public $tmxfile = "Info";
	
	function execute()
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;
			  
		$this->setPageTitle("Phenotype ".$myPT->version. " Info");

		$this->selectMenuItem(self::menu_info);
		$this->selectLayout(self::layout_default);
		
		$this->fillContentArea1($this->renderInfo());
		$this->displayPage();
		
	}
	
	public function renderInfo()
	{
	  global $myPT;
	  global $myApp;
	  $myPT->startBuffer();
	  ?>
	  <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle"><?php echo locale("headline_info")?></td>
            <td align="right" class="windowTitle"><!--<a href="#"><img src="img/b_help.gif" alt="Hilfe aufrufen" width="22" height="22" border="0"></a>--></td>
          </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10" alt=""></td>
      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif" alt=""></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_mi_ri.gif" alt=""></td>
      </tr>
    </table>

    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10" alt=""></td>
      </tr>
    </table>
    <table width="680"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" class="window">
       <?php
	   $myApp->displayBackendInfo();
	   ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead"><?php echo locale("headline_version")?></td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
            <tr>
              <td class="tableBody"><?php echo localeHBR("msg_version",array('##!PT_VERSION!##','##!BUILD_DATE!##','##!BUILD_ID!##'))?></td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead"><?php echo locale("headline_copyright")?></td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
            <tr>
              <td class="tableBody">
              <?php echo localeHBR("msg_copyright")?>
              </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
        </table>
		
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead"><?php echo locale("headline_systemreqs")?></td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
            <tr>
              <td class="tableBody">
			  <table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="150" valign="top"><a href="http://www.mysql.com" target="_blank">MySQL 4+<br><img src="img/poweredbymysql-88.png" border="0" width="88" height="31" alt=""></a></td>
    <td width="150" valign="top"><a href="http://www.php.net" target="_blank">PHP 5+<br><img src="img/php-power-black.gif" border="0" width="88" height="31" alt=""></a></td>
    <td valign="bottom">&nbsp;</td>
</tr>
</table><br>
			  </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
        </table>
		
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead"><?php echo locale("headline_tools")?></td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
            <tr>
              <td class="tableBody">
			  <table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="150" valign="top"><a href="http://smarty.php.net" target="_blank">Smarty Template Engine<br><img src="img/smarty_icon.gif" border="0" width="88" height="31" alt="Smarty Template Engine"></a></td>
    <td width="150" valign="top"><a href="http://www.fckeditor.net/" target="_blank">FCKeditor*<br><img src="img/htmlarea.gif" border="0" width="88" alt=""></a><br><br></td>
			   <td valign="top">* IE 5.5+, Firefox 1.0+, Mozilla 1.3+ and Netscape 7+. </td>
</tr>
<tr>
    <td width="150" valign="top"><a href="http://www.onlineimageeditor.nu/" target="_blank">Indis Online Image Editor</a><br></td>
    <td width="150" valign="top"><a href="http://www.radinks.com/upload/" target="_blank">Radinks Drag & Drop File Upload*</a></td>
			   <td valign="bottom"><?php echo locale("msg_radinks")?></td>
</tr>
</table><br>
			  </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3" alt=""></td>
            </tr>
        </table>	
	  
 
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>

    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10" alt=""></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10" alt=""></td>
      </tr>
    </table>


      <br>
	  <?php
	  return $myPT->stopBuffer();
	}
}