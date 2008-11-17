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
$myAdm = new PhenotypeAdmin();
?>
<?php
if (!$mySUser->checkRight("elm_pageconfig"))
{
  $url = "noaccess.php";
  Header ("Location:" . $url."?".SID);
  exit();
}
?>
<?php
  $pag_id = (int)$_REQUEST["id"];
  $pag_id_newtop = (int)$_REQUEST["id2"];
  $insertorder = (int)$_REQUEST["insertorder"];
  
  $myPage = new PhenotypePage();
  $myPage->init($pag_id);
  $rc = $myPage->move($pag_id_newtop,$insertorder);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Phenotype <?php echo PT_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="phenotype.css" rel="stylesheet" type="text/css">
<link href="navigation.css" rel="stylesheet" type="text/css">
<link href="media.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-top: 2px;
	margin-bottom: 2px;
}
-->
</style>
<?php if ($rc){ ?>

<script language="JavaScript">
top.opener.location ="page_edit.php?id=<?php echo $_REQUEST["id"] ?>";
self.close();
</script>
<?php } ?>
</head>

<body>
<?php if (!$rc){ ?>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTab"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="windowError"><h1><?php echo localeH("Error");?></h1>
			    <p><br><br><?php echo localeH("A page cannot get reallocated below itself.");?><br><br><br></p></td>
              </tr>
        </table></td>
            </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_mi_le.gif"></td>
      </tr>
    </table>	
<?php
}
?>
</body>
</html>
