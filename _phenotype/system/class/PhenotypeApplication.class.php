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


  function throw500($pag_id)
  {
    Header("HTTP/1.0 500 Internal Server Error");
?>
<HTML><HEAD>
<TITLE>500 Internal Server Error</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?php echo $_SERVER["REQUEST_URI"] ?> could not be processed on this server.<P>
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
      $s = '<p style="color:red"><blink>Please remember to delete the user "starter".<blink></p>';
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
	 * The fields in the array have the following meanings:
	 * name
	 *	Name der Version, die im Editor angezeigt wird
	 * size_method
	 *	1 => frei - v�llig freie Wahl des Bildausschnitts bzgl Position und Gr��e, keine Skalierung des Bildmaterials
	 *	2 => fixer Ausschnitt - Nur Auswahl des Ausschnitts, keine Skalierung m�glich.
	 *	3 => Verh�ltnis - Seitenverh�ltnis vorgegeben, Rest kann frei gew�hlt werden, keine Skalierung.
	 *	4 => fixe Zielgr��e - Ziel X,Y vorgegeben, Ausschnitt verschiebbar und proportional skalierbar
	 *	5 => Zielrahmen - vorgegebener Zielrahmen. Freie Wahl des Ausschnitts, dann Skalierung auf maximale Gr��e die noch in den Rahmen passt.
	 * x/y
	 *	Breite und H�he des Bildes, hieraus errechnet sich auch die AspectRatio Angabe z.B. f�r mode 3
	 * sharpening
	 *	St�rke mit der das Bild gesch�rft wird
	 * quality
	 *	JPG Qualit�t f�r die Version
	 * versionaction
	 *	0 => vorhandene Version mit gleichen Namen �berschreiben
	 *	1 => neue Version anlegen
	 * newversion
	 *	Name der anzulegenden Version
	 *
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
	* � -> Ue
	* � -> Oe
	* � -> Ae
	* � -> ue
	* � -> oe
	* � -> ae
	* � -> ss
	* " " -> _
	* added 07/08/23 by Dominique B�s
	
	* @param string $sText This is the text to be changed
	* @return string Return the new String
	*/
  /*
  private function msAmendUmlautsToURLConform($sText="") {
  $sText = str_replace(" ","_",$sText);
  $sText = str_replace("�","ue",$sText);
  $sText = str_replace("�","ae",$sText);
  $sText = str_replace("�","oe",$sText);
  $sText = str_replace("�","ss",$sText);
  $sText = str_replace("�","Oe",$sText);
  $sText = str_replace("�","Ae",$sText);
  $sText = str_replace("�","Ue",$sText);

  return $sText;
  }
  */




}