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
/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeRequest
{
  function get($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return $v;
  }
  
  function getHTML($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
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
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return (int)$v;  
  }
  
  function getD($k,$decimals)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
	$v=sprintf("%01.".$decimals."f",$v);
    return $v;  
  }  
  
  function getURL($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return urlencode($v);  
  }
  
  function getU($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return utf8_encode($v);  
  }  
  
  function getS($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return addslashes($v);  
  }    
  
  function getA($k)
  {
    $v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
	$patterns = "/[^a-z0-9A-ZöäüÖÄÜß]*/";
    $v = preg_replace($patterns,"", $v);
    return $v;  
  }
  
  function getSQL($k)
  {
  	global $myPT;
  	$v=$_REQUEST[$k];
    if (ini_get("magic_quotes_gpc")==1){$v=stripslashes($v);}
    return $myPT->codeSQL($v);  
  }
  
  
  
  function check($k)
  {
    if(isset($_REQUEST[$k])){return true;}
	return false;
  }
  
  function printR()
  {
  	echo "<pre>";
  	print_r ($_REQUEST);
  	echo "</pre></br>";
  }
}
?>