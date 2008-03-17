<?php
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
The requested URL <?php echo $_SERVER["REQUEST_URI"] ?> was not found on this server.<P>
<HR>
<ADDRESS>Phenotype CMS/<?php echo PT_VERSION ?> at <?php echo $_SERVER["SERVER_NAME"] ?> Port <?php echo $_SERVER["SERVER_PORT"] ?></ADDRESS>
</BODY></HTML>
<?php
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


function buildRewriteRules($pag_id,$url)
{
	global $myPT;
	global $myRequest;
	global $myDB;

	//$url = str_replace(" ","_",$url);
	$url = $this->msAmendUmlautsToURLConform($url); //Changed 07/08/23 by Dominique Bös
	// Alle Sonderzeichen, die nicht URL-typisch sind rausfiltern
	$patterns = "/[^a-z0-9A-Z_,.-\/]*/";
	$url = preg_replace($patterns,"", $url);
	// / am Anfang wegfiltern
	$patterns = "/^[\/]*/";
	$url = preg_replace($patterns,"", $url);

	if ($url=="" AND $myPT->getPref("edit_pages.auto_pageurl") ==1)
	{
		$url = $myRequest->get("titel").".html";
		//$url = str_replace(" ","_",$url);
		$url = $this->msAmendUmlautsToURLConform($url); //Changed 07/08/23 by Dominique Bös
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

	/**
	* Amend the German umlauts URL conform
	* Ü -> Ue
	* Ö -> Oe
	* Ä -> Ae
	* ü -> ue
	* ö -> oe
	* ä -> ae
	* ß -> ss
	* " " -> _
	* added 07/08/23 by Dominique Bös
	
	* @param string $sText This is the text to be changed
	* @return string Return the new String
	*/
	private function msAmendUmlautsToURLConform($sText="") {
		$sText = str_replace(" ","_",$sText);
		$sText = str_replace("ü","ue",$sText);
		$sText = str_replace("ä","ae",$sText);
		$sText = str_replace("ö","oe",$sText);
		$sText = str_replace("ß","ss",$sText);
		$sText = str_replace("Ö","Oe",$sText);
		$sText = str_replace("Ä","Ae",$sText);
		$sText = str_replace("Ü","Ue",$sText); 
		
		return $sText;
	}
	
}