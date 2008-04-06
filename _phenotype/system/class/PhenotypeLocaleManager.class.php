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
 * @subpackage system
 *
 */

class PhenotypeLocaleManagerStandard
{
  // additional locales (not en)
  public $_locales = Array("de","xx");

  public function __construct()
  {
    $_token = Array();

    // Common words, available everywhere

    $_token["Phenotype"][]="Start";
    $_token["Phenotype"][]="Editor";
    $_token["Phenotype"][]="Extras";
    $_token["Phenotype"][]="Analyze";
    $_token["Phenotype"][]="Tasks";
    $_token["Phenotype"][]="Admin";
    $_token["Phenotype"][]="Info";    
    $_token["Phenotype"][]="Config";
    $_token["Phenotype"][]="Error";
    $_token["Phenotype"][]="Alert";
    $_token["Phenotype"][]="Feedback";
    $_token["Phenotype"][]="Delete";
    $_token["Phenotype"][]="Save";
    $_token["Phenotype"][]="ID";
    $_token["Phenotype"][]="Date";
    $_token["Phenotype"][]="before";
    $_token["Phenotype"][]="User";
    $_token["Phenotype"][]="Action";
    $_token["Phenotype"][]="day(s)";
    $_token["Phenotype"][]="hours";
    $_token["Phenotype"][]="minutes";
    $_token["Phenotype"][]="Group";
    $_token["Phenotype"][]="edit";
    $_token["Phenotype"][]="Edit";
    $_token["Phenotype"][]="Document";
    $_token["Phenotype"][]="Image";
    $_token["Phenotype"][]="Properties";
    $_token["Phenotype"][]="Search";
    $_token["Phenotype"][]="Overview";
    $_token["Phenotype"][]="online";
    $_token["Phenotype"][]="offline";
    $_token["Phenotype"][]="Changes saved.";
    $_token["Phenotype"][]="Copy of";
    $_token["Phenotype"][]="December";
    $_token["Phenotype"][]="January";
    $_token["Phenotype"][]="February";
    $_token["Phenotype"][]="March";
    $_token["Phenotype"][]="April";
    $_token["Phenotype"][]="May";
    $_token["Phenotype"][]="June";
    $_token["Phenotype"][]="July";
    $_token["Phenotype"][]="August";
    $_token["Phenotype"][]="September";
    $_token["Phenotype"][]="Oktober";
    $_token["Phenotype"][]="November";
    $_token["Phenotype"][]="day_short_monday";
    $_token["Phenotype"][]="day_short_tuesday";
    $_token["Phenotype"][]="day_short_wednesday";
    $_token["Phenotype"][]="day_short_thursday";
    $_token["Phenotype"][]="day_short_friday";
    $_token["Phenotype"][]="day_short_saturday";
    $_token["Phenotype"][]="day_short_sunday";
    $_token["Phenotype"][]="next month";
    $_token["Phenotype"][]="last month";
    $_token["Phenotype"][]="all";
    $_token["Phenotype"][]="Images";
    $_token["Phenotype"][]="Documents";
    $_token["Phenotype"][]="Title";
    $_token["Phenotype"][]="Folder";
    $_token["Phenotype"][]="Keywords";
    $_token["Phenotype"][]="Comment";
    $_token["Phenotype"][]="User";
    $_token["Phenotype"][]="Changes saved";

    // sessions, rights & login

    $_token["Phenotype"][]="Username";
    $_token["Phenotype"][]="Password";
    $_token["Phenotype"][]="msg_login_error";
    $_token["Phenotype"][]="msg_no_access";
    $_token["Phenotype"][]="msg_session_timeout";
    $_token["Phenotype"][]="Login";

    // snapshot & rollback
    
    $_token["Phenotype"][]="Install snapshot";
    $_token["Phenotype"][]="install snapshot";
    $_token["Phenotype"][]="msg_snapshot_contentobject";
    $_token["Phenotype"][]="msg_snapshot_mediaobject";
    $_token["Phenotype"][]="Rollback";
    $_token["Phenotype"][]="snapshot installed";
    $_token["Phenotype"][]="Error during rollback.";
    
    $_token["Editor"][]="msg_recordchange_0";
    $_token["Editor"][]="msg_recordchange_1";
    $_token["Editor"][]="msg_recordchange_n";
    $_token["Editor"][]="tasks";
    $_token["Editor"][]="ID";
    $_token["Editor"][]="Thumb";
    $_token["Editor"][]="Name";
    $_token["Editor"][]="User";
    $_token["Editor"][]="State";
    $_token["Editor"][]="Action";
    $_token["Editor"][]="Edit record";
    $_token["Editor"][]="Really delete record?";
    $_token["Editor"][]="Delete record";
    $_token["Editor"][]="Create new task";
    $_token["Editor"][]="Copy record";
    $_token["Editor"][]="Display debug skin";
    $_token["Editor"][]="Help";
    $_token["Editor"][]="Put into / Take out of lightbox";
    $_token["Editor"][]="Content";
    $_token["Editor"][]="Pages";
    $_token["Editor"][]="Media";
    $_token["Editor"][]="Search Content";
    $_token["Editor"][]="for";
    $_token["Editor"][]="ID";
    $_token["Editor"][]="Fulltext";
    $_token["Editor"][]="Current";
    $_token["Editor"][]="Add new record";
    $_token["Editor"][]="in titles";
    $_token["Editor"][]="fulltext";
    $_token["Editor"][]="for ID";
    $_token["Editor"][]="Mediabase";
    $_token["Editor"][]="Date";
    $_token["Editor"][]="Thumb";

    // Editor / Content

    $_token["Editor_Content"][]="Really delete this record?";
    $_token["Editor_Content"][]="Record deleted.";
    $_token["Editor_Content"][]="Record not found.";
    $_token["Editor_Content"][]="Record copied.";

    // => Editor / Media
    $_token["Editor_Media"][]="Drag & Drop - Upload";
    $_token["Editor_Media"][]="Search Media";
    $_token["Editor_Media"][]="New Files";
    $_token["Editor_Media"][]="Lightbox";
    $_token["Editor_Media"][]="All Files";
    $_token["Editor_Media"][]="objects/page";
    $_token["Editor_Media"][]="Folder";
    $_token["Editor_Media"][]="Size";
    $_token["Editor_Media"][]="Versions";
    $_token["Editor_Media"][]="Alternate";
    $_token["Editor_Media"][]="Image (%1x%2)";
    $_token["Editor_Media"][]="Mediagroup";
    $_token["Editor_Media"][]="Mimetype";
    $_token["Editor_Media"][]="Create new versions";
    $_token["Editor_Media"][]="Create new version";
    $_token["Editor_Media"][]="Versions";
    $_token["Editor_Media"][]="Really delete this image?";
    $_token["Editor_Media"][]="Really delete this document?";
    $_token["Editor_Media"][]="Original";
    $_token["Editor_Media"][]="free";
    $_token["Editor_Media"][]="fixed selection";
    $_token["Editor_Media"][]="fixed ratio";
    $_token["Editor_Media"][]="fixed size";
    $_token["Editor_Media"][]="target frame";
    $_token["Editor_Media"][]="none";
    $_token["Editor_Media"][]="light";
    $_token["Editor_Media"][]="normal";
    $_token["Editor_Media"][]="strong";
    $_token["Editor_Media"][]="very strong";
    $_token["Editor_Media"][]="Action";
    $_token["Editor_Media"][]="Overwrite version";
    $_token["Editor_Media"][]="New version";
    $_token["Editor_Media"][]="Group/Folder";
    $_token["Editor_Media"][]="Upload files";
    $_token["Editor_Media"][]="handle images like documents";
    $_token["Editor_Media"][]="Image / Document";
    $_token["Editor_Media"][]="Upload";
    $_token["Editor_Media"][]="Import files";
    $_token["Editor_Media"][]="Import";
    $_token["Editor_Media"][]="Upload";
    $_token["Editor_Media"][]="Files";
    $_token["Editor_Media"][]="Nothing to import.";
    $_token["Editor_Media"][]="Shared properties";
    $_token["Editor_Media"][]="Mediagroup";
    $_token["Editor_Media"][]="add objects to lightbox";
    $_token["Editor_Media"][]="Object %1 edited.";
    $_token["Editor_Media"][]="Object deleted.";
    $_token["Editor_Media"][]="msg_object_not_saved";
    $_token["Editor_Media"][]="First version added";
    $_token["Editor_Media"][]="msg_error_imageversionupload";
    $_token["Editor_Media"][]="illegal versionname";
    $_token["Editor_Media"][]="msg_error_versionnamechange";
    $_token["Editor_Media"][]="Version deleted.";
    $_token["Editor_Media"][]="msg_error_overwrite_original";
    $_token["Editor_Media"][]="msg_error_readimage";
    $_token["Editor_Media"][]="msg_error_unknown_mimetype";
    $_token["Editor_Media"][]="msg_error_wrongformat";
    $_token["Editor_Media"][]="Upload failed!";
    $_token["Editor_Media"][]="Document upload succesful";
    $_token["Editor_Media"][]="Sharpen";
    $_token["Editor_Media"][]="Really delete this version?";


    // Admin

    $_token["Admin_Users"][]="Admin";
    $_token["Admin_Users"][]="value_newuser_surname";
    $_token["Admin_Users"][]="value_newuser_lastname";
    $_token["Admin_Users"][]="User";
    $_token["Admin_Users"][]="Config";
    $_token["Admin_Users"][]="Surname";
    $_token["Admin_Users"][]="Lastname";
    $_token["Admin_Users"][]="Email";
    $_token["Admin_Users"][]="headline_name";
    $_token["Admin_Users"][]="Username";
    $_token["Admin_Users"][]="Password (for change enter 2 times)";
    $_token["Admin_Users"][]="msg_password_change_failure";
    $_token["Admin_Users"][]="msg_password_change_success";
    $_token["Admin_Users"][]="headline_login";
    $_token["Admin_Users"][]="Photo";
    $_token["Admin_Users"][]="Preferences";
    $_token["Admin_Users"][]="Ticket-Preferences";
    $_token["Admin_Users"][]="created at";
    $_token["Admin_Users"][]="Never logged in.";
    $_token["Admin_Users"][]="Last login on";
    $_token["Admin_Users"][]="State";
    $_token["Admin_Users"][]="Rights";
    $_token["Admin_Users"][]="Superuser";
    $_token["Admin_Users"][]="Roles";
    $_token["Admin_Users"][]="value_allpages";
    $_token["Admin_Users"][]="pagegroups ";
    $_token["Admin_Users"][]="contentobjects";
    $_token["Admin_Users"][]="mediagroups";
    $_token["Admin_Users"][]="extras";
    $_token["Admin_Users"][]="task subjects";
    $_token["Admin_Users"][]="Really delete this user?";
    $_token["Admin_Users"][]="headline_users";
    $_token["Admin_Users"][]="ID";
    $_token["Admin_Users"][]="Name";
    $_token["Admin_Users"][]="Action";
    $_token["Admin_Users"][]="view user";
    $_token["Admin_Users"][]="edit user";
    $_token["Admin_Users"][]="Create new user";


    // Info

    $_token["Info"][]="headline_copyright";
    $_token["Info"][]="msg_copyright";
    $_token["Info"][]="headline_info";
    $_token["Info"][]="headline_systemreqs";
    $_token["Info"][]="headline_version";
    $_token["Info"][]="msg_version";
    $_token["Info"][]="headline_tools";
    $_token["Info"][]="msg_radinks";


    $this->_token = $_token;

  }

  public function rebuildTMXFiles($insert="",$_englishinsert=array(),$_germaninsert=array())
  {
    foreach ($this->_token AS $name => $_token)
    {

      $file = SYSTEMPATH . "tmx/". $name ."_en.tmx";

      $_english = $this->readTMX($file,"en");
      if ($name==$insert)
      {
        $_english = array_merge($_english,$_englishinsert);
      }
      $this->writeTMX($file,$_token,$_english);
      foreach ($this->_locales AS $locale)
      {
        $file = SYSTEMPATH . "tmx/". $name ."_".$locale.".tmx";
        $_translation = $this->readTMX($file,$locale);
        if ($name==$insert AND $locale == "de")
        {
          $_translation = array_merge($_translation,$_germaninsert);
        }
        $this->writeTMX($file,$_token,$_english,$locale,$_translation);
      }
    }
  }


  public function readTMX($file,$locale)
  {
    $_content =array();
    if (file_exists($file))
    {
      $xml = file_get_contents($file);
      $xml = str_replace("<tmx:","<",$xml);
      $xml = str_replace("/tmx:","/",$xml);
      $xml = str_replace("xml:lang","lang",$xml);

      //echo htmlentities($xml);
      $_xml = simplexml_load_string($xml);

      if (!$_xml)
      {
        throw new Exception ("Error parsing ".$file ."\n\nStopping tmx file generation. Please fix and rerun.");
      }


      foreach ($_xml->body->tu AS $_xml_tu)
      {
        //echo "tu";
        $key= (string)$_xml_tu["tuid"];
        $val ="";
        foreach ($_xml_tu->tuv AS $_xml_tuv)
        {
          //echo(string)$_xml_tuv["lang"];
          if ((string)$_xml_tuv["lang"]==$locale)
          {
            $val = (string)$_xml_tuv->seg;
            //echo "TEST".$val;
            break;
          }
        }
        $_content[$key]=$val;
      }
    }


    return $_content;
  }

  public function writeTMX($file,$_token,$_english,$locale = false, $_translation =array())
  {
    $xml ='<?xml version="1.0" encoding="UTF-8"?>
<tmx:tmx version="2.0" xmlns:tmx="http://www.lisa.org/tmx20">
  <tmx:header adminlang="en" creationtool="Phenotype" creationtoolversion="##!PT_VERSION!##" o-tmf="unknown" segtype="block" srclang="*all*"/>
  <tmx:body>';
    foreach ($_token AS $token)
    {
      $val = $_english[$token];
      //if ($val==""){$val='-';}
      $val = codeX($val,true);
      $xml .='<tmx:tu tuid="'.codeX($token,true).'">
      <tmx:tuv xml:lang="en">
        <tmx:seg>'.$val.'</tmx:seg>
      </tmx:tuv>';
      if ($locale != false)
      {
        $val = $_translation[$token];
        //if ($val==""){$val='-';}
        $val = codeX($val,true);
        $xml .='
      <tmx:tuv xml:lang="'.$locale.'">
        <tmx:seg>'.$val.'</tmx:seg>
      </tmx:tuv>';
      }
      $xml .= '</tmx:tu>
			';
    }
    $xml .='  </tmx:body>
</tmx:tmx>';

    //echo htmlentities($xml);
    file_put_contents($file,$xml);
  }

  public function addTokens($myDAO,$file,$locale)
  {
    //echo "<strong>".$file."</strong>";
    $xml = file_get_contents($file);
    $xml = str_replace("<tmx:","<",$xml);
    $xml = str_replace("/tmx:","/",$xml);
    $xml = str_replace("xml:lang","lang",$xml);
    $_xml = simplexml_load_string($xml);

    foreach ($_xml->body->tu AS $_xml_tu)
    {
      $key= (string)$_xml_tu["tuid"];
      $val="";
      $val_en="";
      foreach ($_xml_tu->tuv AS $_xml_tuv)
      {
        if ((string)$_xml_tuv["lang"]=="en")
        {
          $val_en = (string)$_xml_tuv->seg;
        }
        if ((string)$_xml_tuv["lang"]==$locale)
        {
          $val = (string)$_xml_tuv->seg;
          break;
        }
      }
      if ($val==""){$val=$val_en;}

      //echo $key ." => ".$val."<br>";
      $myDAO->set($key,$val);
    }

  }


  public function helpMe()
  {
    echo '<table>';
    $heading="";
    foreach ($this->_token AS $k => $section)
    {
      foreach ($section as  $v)
      {
        if ($k!=$heading)
        {
          $heading=$k;
          echo '<tr><td colspan="2"><strong>'.$heading.'<strong></td></tr>';
        }
        echo '<tr><td>'.htmlentities('<?php echo localeH("'.$v.'");?>').'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; locale("'.$v.'")'.'</td></tr>';
      }
    }
    echo '</table>';
  }

}