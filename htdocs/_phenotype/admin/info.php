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
<?
$mySmarty = new Smarty;
$myAdm = new PhenotypeAdmin();
?>
<?
$myAdm->header("Info");
?>
<body>
<?
$myAdm->menu("Info");
?>
<?
// -------------------------------------
// {$left} 
// -------------------------------------
$myPT->startBuffer();

?>
<?
$left = $myPT->stopBuffer();
// -------------------------------------
// -- {$left} 
// -------------------------------------
?>
<?
// -------------------------------------
// {$content} 
// -------------------------------------
$myPT->startBuffer();
?>

<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowTitle">Info </td>
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

    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><img src="img/white_border.gif" width="3" height="3"></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
    <table width="680"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" class="window">
       <?
	   $myApp->displayBackendInfo();
	   ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead">Version</td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
            <tr>
              <td class="tableBody">
              Version ##!PT_VERSION!## vom ##!BUILD_DATE!##<br/>
			  (##!BUILD_ID!##)
              </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead">Copyright</td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
            <tr>
              <td class="tableBody">
              Phenotype ist eine Gemeinschaftsentwicklung von Nils Hagemann, Paul Sellinger, Peter Sellinger und Michael Krämer.<br/><br/>
                
              Phenotype ist unter der GNU General Public License lizensiert. Evtl. abweichende Nutzungs- und Verwertungsrechte <br/>(insbesondere für die mit Phenotype erstellte Applikation) gem&auml;&szlig; separater Lizenzvereinbarung.<br>
              <br>
              PhenoType CMS ist eine eingetragene Marke, eingetragen unter Nr. 303 28 492 beim<br>
              Deutschen Patent- und Markenamt, München.<br>
              
              </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
        </table>
		
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead">Systemvoraussetzungen</td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
            <tr>
              <td class="tableBody">
			  <table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="150" valign="top"><a href="http://www.mysql.com" target="_blank">MySQL 4.0.15*<br><img src="img/poweredbymysql-88.png" border="0" width="88" height="31"></a></td>
    <td width="150" valign="top"><a href="http://www.php.net" target="_blank">PHP 5.0.4*<br><img src="img/php-power-black.gif" border="0" width="88" height="31"></a></td>
    <td valign="bottom"><br>
              * oder 100% kompatible Versionen. <br/>Für den Einsatz von MySQL kann je nach Einsatzart eine zusätzliche Lizenz notwendig werden.</td>
</tr>
</table><br>
			  </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
        </table>
		
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tableHead">Systemtools</td>
            </tr>
            <tr>
              <td class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
            <tr>
              <td class="tableBody">
			  <table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="150" valign="top"><a href="http://smarty.php.net" target="_blank">Smarty Template Engine<br><img src="img/smarty_icon.gif" border="0" width="88" height="31" alt="Smarty Template Engine"></a></td>
    <td width="150" valign="top"><a href="http://www.fckeditor.net/" target="_blank">FCKeditor*<br><img src="img/htmlarea.gif" border="0" width="88"></a><br><br></td>
			   <td valign="top">* IE 5.5+, Firefox 1.0+, Mozilla 1.3+ and Netscape 7+. </td>
</tr>
<tr>
    <td width="150" valign="top"><a href="http://www.onlineimageeditor.nu/" target="_blank">Indis Online Image Editor</a><br></td>
    <td width="150" valign="top"><a href="http://www.radinks.com/upload/" target="_blank">Radinks Drag & Drop File Upload*</a></td>
			   <td valign="bottom">* zum Umgehen der Größenbeschränkung muss eine Einzellizenz erworben werden.</td>
</tr>
</table><br>
			  </td>
            </tr>
            <tr>
              <td colspan="4" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
        </table>	
	  
 
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>

    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>


      <br>


<?
$content = $myPT->stopBuffer();
// -------------------------------------
// -- {$content} 
// -------------------------------------
?>
<?
$myAdm->mainTable($left,$content);
?>
<?

?>
</body>
</html>























