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
<?php
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeRequestStandard
{
  // holds the request array

  public $_REQUEST;

  public function __construct()
  {
    global $myDB;
    global $myApp;

    $this->_REQUEST = $_REQUEST;
    if (ini_get("magic_quotes_gpc")==1)
    {
      foreach ($this->_REQUEST AS $k=>$v)
      {
        $this->set($k,stripslashes($v));
      }
    }

    // check for smartURL hit
    if (defined("PT_FRONTEND"))
    {
      if ($this->check("smartURL") AND !$this->check("id"))
      {
        $smartURL = $this->get("smartURL");

          // / am Anfang wegfiltern
        $patterns = "/^[\/]*/";
        $smartURL = preg_replace($patterns,"", $smartURL);
        // / am Ende wegfiltern
        $patterns = "/[\/]\$/";
        $smartURL = preg_replace($patterns,"", $smartURL);

        $sql = "SELECT pag_id FROM page WHERE pag_url='". mysql_escape_string($smartURL)."'";
        $rs = $myDB->query($sql,"Request");

        if (mysql_num_rows($rs)==1)
        {
          $row =mysql_fetch_array($rs);
          $this->set("id",$row["pag_id"]);
        }
        else
        {
          // check for param rewrite
          $sql = "SELECT pag_id, LENGTH(pag_url) AS l FROM page WHERE pag_url !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url,'/')) ORDER BY l LIMIT 0,1";
          $rs = $myDB->query($sql);
          if (mysql_num_rows($rs)==1)
          {
            $row =mysql_fetch_array($rs);
            $this->set("id",$row["pag_id"]);
            $params = substr($smartURL,$row["l"]+1);
            //echo $params;
            $_params = split('/',$params);
            $i=0;
            foreach ($_params AS $v)
            {
              switch ($i)
              {
                case 0:
                  $i=1;
                  $key = $v;
                  break;
                case 1:
                  $this->set($key,$v);
                  $i=0;
                  break;
              }
            }
            if ($i==1)
            {
              $this->set("smartUID",$key);
              if ($key=="action")
              {
                $this->set("action","index");
              }
            }
          }
          else 
          {
            // no unique hit
            $myApp->throw404();
          }
        }
      }
    }
  }
  function set($k,$v)
  {
    $this->_REQUEST[$k]=$v;
  }

  function get($k)
  {
    if ($this->check($k))
    {$v=@$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return $v;
    }
    return "";
  }

  function getHTML($k)
  {
    $v=@$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    // ToDO:Fehlt hier ein stripslashes??
    return @htmlentities($v);

  }

  function getH ($k)
  {
    return $this->getHTML($k);
  }


  function getHBR($k)
  {
    $html = nl2br($this->getHTML($k));
    // Falls fehlerhafte Returns/Linefeeds enthalten sind, werden diese eliminiert
    $html = str_replace (chr(10),"",$html);
    $html = str_replace (chr(13),"",$html);
    return $html;

  }

  function getI($k)
  {
    global $myPT;
    return $myPT->codeI($this->get($k));
    //$v=$this->_REQUEST[$k];

    // if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    //return (int)$v;

  }

  function getD($k,$decimals)
  {

    $v=$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    $v=sprintf("%01.".$decimals."f",$v);
    return $v;

  }

  function getURL($k)
  {


    $v=$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return urlencode($v);

  }

  function getU($k)
  {


    $v=$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return utf8_encode($v);

  }

  function getS($k)
  {

    $v=$this->_REQUEST[$k];
    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return addslashes($v);

  }

  function getA($k)
  {

    $v=$this->_REQUEST[$k];

    //if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    $patterns = "/[^a-z0-9A-ZöäüÖÄÜß]*/";
    $v = preg_replace($patterns,"", $v);
    return $v;

  }

  function getSQL($k)
  {

    global $myPT;
    $v=$this->_REQUEST[$k];
    // if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return $myPT->codeSQL($v);

  }



  function check($k)
  {
    if(isset($this->_REQUEST[$k])){return true;}
    return false;
  }

  function printR()
  {
    echo "<pre>";
    print_r ($this->_REQUEST);
    echo "</pre></br>";
  }
  
  public function getParamsArray()
  {
    return ($this->_REQUEST);
  }

  public function getParamHash ()
  {
    $_ignore = Array("PHPSESSID","cache","id","smartURL");
    foreach ($this->_REQUEST AS $k => $v)
    {
      if (!in_array($k,$_ignore))
      {
        $hash  .= $k."#".$v."#";
      }
    }
    return $hash;
  }

  public function getReloadUrl()
  {
    //ToDO: could be extended to forward POST parameter
    //currently fitting for most purposes
    return SERVERFULLURL ."reload.php?uri=".base64_encode($_SERVER["REQUEST_URI"]);
  }
}
?>
