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
?>
<?php
require("_config.inc.php");
require("_session.inc.php");
$myPT->loadTMX("Editor_Pages");
?>
<?php
if (!$mySUser->checkRight("elm_pageconfig"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
$myPT->clearCache();
?>
<?php
$id = $myRequest->getI("id");
$insertorder = (int)$_REQUEST["insertorder"];

$myPage = new PhenotypePage();
$id = $myPage->newPage_RelatedToExisitingPage($id,$insertorder);

$myPage->init($id);



$mySQL = new SQLBuilder();
$mySQL->addField("pag_bez",$_REQUEST["bez"]);
$mySQL->addField("pag_titel",$_REQUEST["bez"]);
$sql = $mySQL->update("page","pag_id=".$myPage->id);
$myDB->query($sql);

$mySQL = new SQLBuilder();
$mySQL->addField("lay_id",$_REQUEST["lay_id"]);
$sql = $mySQL->update("pageversion","ver_id=".$myPage->ver_id);
$myDB->query($sql);

$myPage->buildProps();

$url = "page_edit.php?id=" . $id;
?>
<?php
$myAdm = new PhenotypeAdmin();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
top.opener.location = "<?php echo $url ?>";
self.close();
</script>
</head>
<body>
</body>
</html>
