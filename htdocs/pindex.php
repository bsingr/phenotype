<?
require("../_config.inc.php");
?>
<?

?>
<?
if (isset($_REQUEST["id"]))
{
$id = $_REQUEST["id"];
}
else
{
$id= PAG_ID_STARTPAGE;
}
?>
<?

// Diese Datei ist ein Beispiel dafür, wie man mehrere
// Websites betreiben kann und über die Konstruktorparameter
// verhindert, dass der Benutzer durch URL-Manipulation 
// Seiten eines anderen Auftritts sieht

// Zeigt nur Seiten der Seitengruppe 1 (Struktur) an

$lng_id = $myRequest->getI("lng_id");
$myPage = new PhenotypePage($id,1);
$myPage->switchLanguage($lng_id);
$mySmarty = new Smarty;

$cache=PT_PAGECACHE;


if (isset($_REQUEST["cache"]))
{
  if ($_REQUEST["cache"]==0)
  {
    $cache=0;
  }
  else
  {
    $cache=1;
  }
}

$myPage->display($cache);
?>