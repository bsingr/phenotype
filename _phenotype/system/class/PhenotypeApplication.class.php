<?
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeApplicationStandard
{
	function throw404($pag_id)
	{
		Header("HTTP/1.0 404 Not Found");
?>
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?= $_SERVER["REQUEST_URI"] ?> was not found on this server.<P>
<HR>
<ADDRESS>Phenotype CMS/<?= PT_VERSION ?> at <?= $_SERVER["SERVER_NAME"] ?> Port <?= $_SERVER["SERVER_PORT"]?></ADDRESS>
</BODY></HTML>
<?
		exit();
	}


	/**
	 * This function is used from the login screen. You should overwrite
	 * it in your application to personalize the information text
	 *
	 * @return string
	 */
	function getLoginInfoText()
	{
		global $myDB;

		$s = "";

		$sql = "SELECT usr_id FROM user WHERE usr_id = 13 AND usr_login ='starter' AND usr_pass='ph1c2fSo4Tg/2' AND usr_status=1";
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==1)
		{
			$s = '<p style="color:red"><blink>Please remember to delete the user "starter". And I will stop blinking ;)<blink></p>';
		}

		return ($s);
	}

	function displayBackendInfo()
	{
	}


	function displayBackendJavascript()
	{
		$js = '<script type="text/javascript"></script>';
		echo $js;
	}



	function getEditablePageVars()
	{
		return (Array());
	}


	function getUserPrefList()
	{
		return (Array());
	}

	function getUserPrefListforTickets()
	{
		return (Array());
	}

	/**
	 * Get format definitions for image editing mask in the mediabase
	 *
	 * An array of arrays will be returned. 
	 * 
	 * @param PhenotypeMediaObject
	 * @return array Array with format definitions 
	 */
	function getImageEditingFormatArray($myObj)
	{
		$_definitions = Array();


		$_definitions[] = Array(
		"name"=> "10x15 quer",
		"method"=>3,
		"x"=>150,
		"y"=>100,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"10x15"
		);
		$_definitions[] = Array(
		"name"=> "10x15 hoch",
		"method"=>3,
		"x"=>100,
		"y"=>150,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"10x15"
		);
		$_definitions[] = Array(
		"name"=> "13x18 quer",
		"method"=>3,
		"x"=>180,
		"y"=>130,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"13x18"
		);
		$_definitions[] = Array(
		"name"=> "13x18 hoch",
		"method"=>3,
		"x"=>130,
		"y"=>180,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"13x18"
		);
		$_definitions[] = Array(
		"name"=> "15x20 quer",
		"method"=>3,
		"x"=>150,
		"y"=>200,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"15x20"
		);
		$_definitions[] = Array(
		"name"=> "15x20 hoch",
		"method"=>3,
		"x"=>200,
		"y"=>150,
		"sharpening"=>0,
		"quality"=>85,
		"versionaction"=>0,
		"newversion"=>"15x20"
		);
		return ($_definitions);
	}

	function richtext_strip_tags($text)
	{
		$text = str_replace(chr(160)," ",$text);

		// Wir wollen keine Div-Tags
		$text = str_replace('<div> </div>',"",$text);
		$text = str_replace('<div',"<p",$text);
		$text = str_replace('</div',"</p",$text);


		$allowed = array(
		'br' => array(),
		'p' => Array("align"=>1,"target"=>1),
		'b' => array(),
		'strong' => array(),
		'i' => array(),
		'em' => array(),
		'a' => Array("href"=>1,"align"=>1,"target"=>1),
		'ol' => array(),
		'ul' => array(),
		'li' => array(),
		//'span' => array(),
		'div' => Array("align"=>1),
		'u' => array()
		);

		$text = kses($text, $allowed);

		// Typischer Wordschmutz weg
		$text = str_replace('<p>&nbsp;</p>',"",$text);
		$text = str_replace('<p>&nbsp; </p>',"",$text);
		$text = str_replace('<p>&nbsp;  </p>',"",$text);
		$text = str_replace('<p></p>',"",$text);
		$text = str_replace('<p> </p>',"",$text);
		$text = str_replace('<P><STRONG>&nbsp;</STRONG></P>',"",$text);
		$text = str_replace('<p class="MsoNormal" style="MARGIN: 0cm 0cm 0pt"><font size="3"> <p></p></font></p>',"",$text);

		// Echte Returns im IE
		$text = str_replace("<p></p>","<br/>",$text);
		$text = str_replace('<p align="left"></p>',"<br/>",$text);
		$text = str_replace('<p align="right"></p>',"<br/>",$text);

		$text = str_replace('###GT###',"&gt;",$text);
		$text = str_replace('###LT###',"&lt;",$text);

		return $this->richtext_postfilter($text);
	}

	function richtext_prefilter($text)
	{
		return $text;
	}

	function richtext_postfilter($text)
	{
		return $text;
	}

	function fckConfig()
	{
		header("Content-Type: text/javascript; charset=iso-8859-1");
		?>
		FCKConfig.CustomConfigurationsPath = '' ;

		// FCKConfig.EditorAreaCSS = '<?=ADMINURL?>fckcss.php';
		// Unfortunately targeting the php-file doesn't work properly since neweset release of FCK
		// (probably something with the expected HTTP-Header?)
		// So this feature ist deactived by default
		
		//FCKConfig.EditorAreaCSS = '<?=ADMINURL?>fck_editorarea.css';
		
		FCKConfig.ToolbarComboPreviewCSS = '' ;
		
		FCKConfig.DocType = '' ;
		
		FCKConfig.BaseHref = '' ;
		
		FCKConfig.FullPage = false ;
		
		FCKConfig.Debug = false ;
		FCKConfig.AllowQueryStringDebug = true ;
		
		FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/default/' ;
		FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ] ;
		
		FCKConfig.PluginsPath = FCKConfig.BasePath + 'plugins/' ;
		
		// FCKConfig.Plugins.Add( 'autogrow' ) ;
		FCKConfig.AutoGrowMax = 400 ;
		
		// FCKConfig.ProtectedSource.Add( /<\?[\s\S]*?\?>/g ) ;	// PHP style server side code
		// FCKConfig.ProtectedSource.Add( /(<asp:[^\>]+>[\s|\S]*?<\/asp:[^\>]+>)|(<asp:[^\>]+\/>)/gi ) ;	// ASP.Net style tags <asp:control>
		
		FCKConfig.AutoDetectLanguage	= true ;
		FCKConfig.DefaultLanguage = 'de' ; 

		FCKConfig.ContentLangDirection	= 'ltr' ;
		
		FCKConfig.ProcessHTMLEntities	= true ;
		FCKConfig.IncludeLatinEntities	= true ;
		FCKConfig.IncludeGreekEntities	= true ;
		
		FCKConfig.ProcessNumericEntities = false ;
		
		FCKConfig.AdditionalNumericEntities = ''  ;		// Single Quote: "'"
		
		FCKConfig.FillEmptyBlocks	= true ;
		
		FCKConfig.FormatSource		= true ;
		FCKConfig.FormatOutput		= true ;
		FCKConfig.FormatIndentator	= '    ' ;
		
		FCKConfig.ForceStrongEm = true ;
		FCKConfig.GeckoUseSPAN	= false ;
		FCKConfig.StartupFocus	= false ;
		FCKConfig.ForcePasteAsPlainText	= false ;
		FCKConfig.AutoDetectPasteFromWord = true ;	// IE only.
		FCKConfig.ForceSimpleAmpersand	= false ;
		FCKConfig.TabSpaces		= 0 ;
		FCKConfig.ShowBorders	= true ;
		FCKConfig.SourcePopup	= false ;
		FCKConfig.UseBROnCarriageReturn = true ; 

		FCKConfig.ToolbarStartExpanded	= true ;
		FCKConfig.ToolbarCanCollapse	= true ;
		FCKConfig.IgnoreEmptyParagraphValue = true ;
		FCKConfig.PreserveSessionOnFileBrowser = false ;
		FCKConfig.FloatingPanelsZIndex = 10000 ;
		
		FCKConfig.TemplateReplaceAll = true ;
		FCKConfig.TemplateReplaceCheckbox = true ;
		
		FCKConfig.ToolbarLocation = 'In' ;
		
		FCKConfig.ToolbarSets["Default"] = [
		['Bold','Italic','Underline','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-','OrderedList','UnorderedList','-','Link','Unlink'],
		['Paste','PasteText','PasteWord','RemoveFormat'],
		['Undo','Redo','-','Find','Replace'],
		['Print','-','Source','-','FitWindow']
		] ;

		FCKConfig.ToolbarSets["Coding"] = [
		['Undo','Redo'],['Find','Replace'],['RemoveFormat'],
		['Print','-','FitWindow']
		] ;
		
		FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','Table','Form'] ;
		
		FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;
		
		FCKConfig.FontNames		= 'Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
		FCKConfig.FontSizes		= '1/xx-small;2/x-small;3/small;4/medium;5/large;6/x-large;7/xx-large' ;
		FCKConfig.FontFormats	= 'p;div;pre;address;h1;h2;h3;h4;h5;h6' ;
		
		//FCKConfig.StylesXmlPath		= FCKConfig.EditorPath + 'fckstyles.xml' ;
		FCKConfig.StylesXmlPath = '../../fckstyles.php'; 
		FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml' ;
		
		FCKConfig.SpellChecker			= 'ieSpell' ;	// 'ieSpell' | 'SpellerPages'
		FCKConfig.IeSpellDownloadUrl	= 'http://wcarchive.cdrom.com/pub/simtelnet/handheld/webbrow1/ieSpellSetup240428.exe' ;
		
		FCKConfig.MaxUndoLevels = 15 ;
		
		FCKConfig.DisableObjectResizing = false ;
		FCKConfig.DisableFFTableHandles = true ;
		
		FCKConfig.LinkDlgHideTarget		= false ;
		FCKConfig.LinkDlgHideAdvanced	= false ;
		
		FCKConfig.ImageDlgHideLink		= false ;
		FCKConfig.ImageDlgHideAdvanced	= false ;
		
		FCKConfig.FlashDlgHideAdvanced	= false ;
		
		// The following value defines which File Browser connector and Quick Upload 
		// "uploader" to use. It is valid for the default implementaion and it is here
		// just to make this configuration file cleaner. 
		// It is not possible to change this value using an external file or even 
		// inline when creating the editor instance. In that cases you must set the 
		// values of LinkBrowserURL, ImageBrowserURL and so on.
		// Custom implementations should just ignore it.
		var _FileBrowserLanguage	= 'asp' ;	// asp | aspx | cfm | lasso | perl | php | py
		var _QuickUploadLanguage	= 'asp' ;	// asp | aspx | cfm | lasso | php
		
		// Don't care about the following line. It just calculates the correct connector 
		// extension to use for the default File Browser (Perl uses "cgi").
		var _FileBrowserExtension = _FileBrowserLanguage == 'perl' ? 'cgi' : _FileBrowserLanguage ;
		
		FCKConfig.LinkBrowser = false ;
		FCKConfig.LinkBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
		FCKConfig.LinkBrowserWindowWidth	= FCKConfig.ScreenWidth * 0.7 ;		// 70%
		FCKConfig.LinkBrowserWindowHeight	= FCKConfig.ScreenHeight * 0.7 ;	// 70%
		
		FCKConfig.ImageBrowser = false ;
		FCKConfig.ImageBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Image&Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
		FCKConfig.ImageBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	// 70% ;
		FCKConfig.ImageBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	// 70% ;
		
		FCKConfig.FlashBrowser = false ;
		FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
		FCKConfig.FlashBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	//70% ;
		FCKConfig.FlashBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	//70% ;
		
		FCKConfig.LinkUpload = false ;
		FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage ;
		FCKConfig.LinkUploadAllowedExtensions	= "" ;			// empty for all
		FCKConfig.LinkUploadDeniedExtensions	= ".(php|php3|php5|phtml|asp|aspx|ascx|jsp|cfm|cfc|pl|bat|exe|dll|reg|cgi)$" ;	// empty for no one
		
		FCKConfig.ImageUpload = false ;
		FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage + '?Type=Image' ;
		FCKConfig.ImageUploadAllowedExtensions	= ".(jpg|gif|jpeg|png)$" ;		// empty for all
		FCKConfig.ImageUploadDeniedExtensions	= "" ;							// empty for no one
		
		FCKConfig.FlashUpload = false ;
		FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage + '?Type=Flash' ;
		FCKConfig.FlashUploadAllowedExtensions	= ".(swf|fla)$" ;		// empty for all
		FCKConfig.FlashUploadDeniedExtensions	= "" ;					// empty for no one
		
		FCKConfig.SmileyPath	= FCKConfig.BasePath + 'images/smiley/msn/' ;
		FCKConfig.SmileyImages	= ['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'] ;
		FCKConfig.SmileyColumns = 8 ;
		FCKConfig.SmileyWindowWidth		= 320 ;
		FCKConfig.SmileyWindowHeight	= 240 ;
		<?
}

function fckCSS() // doesn't work properly at the moment, so it's not used by default!!
{
	header("Content-Type: text/css; charset=iso-8859-1");
	?>
	/*
	* FCKeditor - The text editor for internet
	* Copyright (C) 2003-2006 Frederico Caldeira Knabben
	*
	* Licensed under the terms of the GNU Lesser General Public License:
	* 		http://www.opensource.org/licenses/lgpl-license.php
	*
	* For further information visit:
	* 		http://www.fckeditor.net/
	*
	* "Support Open Source software. What about a donation today?"
	*
	* File Name: fck_editorarea.css
	* 	This is the default CSS file used by the editor area. It defines the
	* 	initial font of the editor and background color.
	*
	* 	A user can configure the editor to use another CSS file. Just change
	* 	the value of the FCKConfig.EditorAreaCSS key in the configuration
	* 	file.
	*
	* File Authors:
	* 		Frederico Caldeira Knabben (fredck@fckeditor.net)
	*/

	/*
	The "body" styles should match your editor web site, mainly regarding
	background color and font family and size.
	*/

	body
	{
		background-color: #ffffff;
		padding: 5px 5px 5px 5px;
		margin: 0px;
	}

	body, td
	{
		font-family: Arial, Verdana, Sans-Serif;
		font-size: 12px;
	}

	a
	{
		color: #0000FF !important;	/* For Firefox... mark as important, otherwise it becomes black */
	}

	/*
	Just uncomment the following block if you want to avoid spaces between
	paragraphs. Remember to apply the same style in your output front end page.
	*/

	/*
	P, UL, LI
	{
	margin-top: 0px;
	margin-bottom: 0px;
	}
	*/
	<?
}

function fckStyles()
{

	header("Content-Type: application/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8" ?>';
	?>
	<Styles >
	<Style name="Beispiel1" element="font">
	<Attribute name="class" value="example1" />
	</Style>
	<Style name="Beispiel2" element="font">
	<Attribute name="class" value="example2" />
	</Style>
	</Styles>
	<?
}


function buildRewriteRules($pag_id,$url)
{
	global $myPT;
	global $myRequest;
	global $myDB;

	$url = str_replace(" ","_",$url);
	// Alle Sonderzeichen, die nicht URL-typisch sind rausfiltern
	$patterns = "/[^a-z0-9A-Z_,.-\/]*/";
	$url = preg_replace($patterns,"", $url);
	// / am Anfang wegfiltern
	$patterns = "/^[\/]*/";
	$url = preg_replace($patterns,"", $url);

	if ($url=="" AND $myPT->getPref("edit_pages.auto_pageurl") ==1)
	{
		$url = $myRequest->get("titel").".html";
		$url = str_replace(" ","_",$url);
		// Alle Sonderzeichen, die nicht URL-typisch sind rausfiltern
		$patterns = "/[^a-z0-9A-Z_,.-\/]*/";
		$url = preg_replace($patterns,"", $url);
		// / am Anfang wegfiltern
		$patterns = "/^[\/]*/";
		$url = preg_replace($patterns,"", $url);
	}

	$sql = "SELECT pag_url FROM page WHERE pag_id<>" . $pag_id . " AND pag_url='".addslashes($url)."'";
	$rs = $myDB->query($sql);
	if (mysql_num_rows($rs)!=0)
	{
		$p=strrpos($url,".");
		if ($p===false)
		{
			$url .= "," .time() .".html";
		}
		else
		{
			$url = substr($url,0,$p) . "," . time() . substr($url,$p);
		}
	}

	$mySQL = new SqlBuilder();
	$mySQL->addField("pag_url",$url);
	$sql = $mySQL->update("page","pag_id=".$pag_id);
	$myDB->query($sql);


	$myPT->clearcache_subpages(0);



	// REWRITE-Regeln schreiben
	if (APACHE_PTWRITEHTACCESS==1)
	{
		$filename = APACHE_REWRITE_PATH . APACHE_REWRITE_FILE;
		$code  =APACHE_HTACCESSHEADER."\n";
		$code .="RewriteBase ".APACHE_REWRITEBASE."\n";
		$sql = "SELECT pag_id,pag_url FROM page WHERE pag_status = 1 AND pag_url <>'' GROUP BY pag_url";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$code .="RewriteRule ^" . $row["pag_url"] . " index.php?id=". $row["pag_id"] . "\n";
		}

		// last generic rule, which always leads to index.php, if a _ followed by any number is within the url

		$code .= "RewriteRule _([0-9]+).html index.php?id=$1 [QSA]\n\n";

		$code .= "# check if requested file exists\n";
		$code .= "	RewriteCond %{REQUEST_FILENAME} !-d\n";
		$code .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$code .= "# if not, pass it to 404.php\n";
		$code .= "RewriteRule ^(.*) 404.php [QSA]  # QSA preserves the query \n";

		$fp = fopen ($filename, "w");
		fputs ($fp,$code);
		fclose ($fp);

		@chmod ($filename,UMASK);
	}

	return ($url);
}



// Events


function onPress_Start()
{

}

function onUploadMediaObject($med_id,$type)
{
	
}

// Ticket-Events

function onTicket_createTicket($tik_id,$usr_id_creator,$usr_id_owner)
{

}

function onTicket_delegateTicket($tik_id,$usr_id_lastowner,$usr_id_newowner)
{

}

function onTicket_acceptTicket($tik_id,$usr_id)
{

}

function onTicket_progressTicket($tik_id,$usr_id,$minuten,$progress,$progress_last)
{

}

function onTicket_moveTicket($tik_id,$sbj_id,$sbj_id_last)
{

}

function onTicket_prioritizeTicket($tik_id,$tik_prio,$tik_prio_last)
{

}

function onTicket_closeTicket($tik_id,$progress)
{

}

}