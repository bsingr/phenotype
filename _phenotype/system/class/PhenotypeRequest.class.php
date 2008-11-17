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
 * @subpackage system
 *
 */
class PhenotypeRequestStandard
{
  // holds the request array

  public $_REQUEST;
  
  public $code404 = 0;
  
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

        $sql = "SELECT pag_id, pag_url1, pag_url2, pag_url3, pag_url4 FROM page WHERE pag_url='". mysql_escape_string($smartURL)."' OR pag_url1='". mysql_escape_string($smartURL)."' OR pag_url2='". mysql_escape_string($smartURL)."' OR pag_url3='". mysql_escape_string($smartURL)."' OR pag_url4='". mysql_escape_string($smartURL)."'";
        $rs = $myDB->query($sql,"Request");
        if (mysql_num_rows($rs)==1)
        {
          $row = mysql_fetch_array($rs);
          $lng_id=1;
          for ($i=1;$i<=4;$i++)
          {
            if ($row["pag_url".$i]==$smartURL)
            {
              $lng_id=$i;
              break;
            }
          }
          $this->set("id",$row["pag_id"]);
          $this->set("lng_id",$lng_id);
        }
        else
        {
          // check for param rewrite

         
          $sql = "SELECT pag_id, LENGTH(pag_url1) AS L ,1 AS lng_id FROM page WHERE pag_url1 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url1,'/'))=1";
          $sql .= " UNION SELECT pag_id, LENGTH(pag_url2) AS L ,2 AS lng_id FROM page WHERE pag_url2 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url2,'/'))=1";
          $sql .= " UNION SELECT pag_id, LENGTH(pag_url3) AS L ,3 AS lng_id FROM page WHERE pag_url3 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url3,'/'))=1";
          $sql .= " UNION SELECT pag_id, LENGTH(pag_url4) AS L ,4 AS lng_id FROM page WHERE pag_url4 !='' AND INSTR('".mysql_escape_string($smartURL)."',CONCAT(pag_url4,'/'))=1";
          $sql .= " ORDER BY L DESC, lng_id ASC LIMIT 0,1";

          $rs = $myDB->query($sql);
          if (mysql_num_rows($rs)==1)
          {
            $row =mysql_fetch_array($rs);
            $this->set("id",$row["pag_id"]);
            $this->set("lng_id",$row["lng_id"]);
            
            $params = substr($smartURL,$row["L"]+1);
            $this->set("smartURLParams", $params);
            //echo $params;
            $_params = split('/',$params);
            $i=0;$n=0;
            foreach ($_params AS $v)
            {
              $n++;
              $this->set("smartParam".$n,$v);
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
            global $myTC;
            $myTC->stop();
            $this->code404=1;
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

  /**
	 * creates the parameter hash used for the include cache
	 *
	 * @return String	containing all relevant keys and values of the request
	 */
  public function getParamHash ()
  {
    $hash="";
    $_ignore = Array("PHPSESSID","cache","id","smartURL","smartURLParams","smartParam1","smartParam2","smartParam3","smartParam4","smartParam5","smartParam6","smartParam7","smartParam8","smartParam9","smartParam10");
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

  public function isPostRequest()
  {
  	return ($_SERVER['REQUEST_METHOD'] == 'POST');
  }

  function getPostParam($k)
  {
    if ($this->checkPost($k))
    {$v=@$_POST[$k];
    return $v;
    }
    return "";
  }

  function checkPost($k)
  {
    if(isset($_POST[$k])){return true;}
    return false;
  }

}
?>
