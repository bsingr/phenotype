<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>{$title|escape}</title>
  <meta name="author" content="Nils Hagemann"/>
  <meta name="keywords" content="{$keywords|escape}"/>
  <meta name="generator" content="Phenotype CMS 2.6" />
  <base href="{pt_constant value="SERVERFULLURL"}"/>
  <link href="{pt_constant value="SERVERFULLURL"}style.css" rel="stylesheet" type="text/css" /> 
</head>

<body>
<!-- Header -->
<div id="header">
   <div id="logo"><a href="index.php"><img src="{pt_constant value="SERVERURL"}img/logo.png" alt="Logo" /></a></div>
</div> 
<div id="menu">
  <div class="container">
  {$pt_include1}
  </div>
</div>
<!-- // Header -->

<!--Phenotype-Label -->
<div id="phenotype"><a href="{url_for_page pag_id=1|escape}"><img src="{pt_constant value="SERVERURL"}img/phenotype-label.png" alt="home" border="0" /></a></div>


<!--Content-div -->
<div id="container1">
  <!--Inhalte -->
    <div id="container2">
    
        <div id="navigation">
        {$pt_include2}
        </div>
               
        <div id="content">
        {$pt_block1}
        <div class="clear"></div>
                       {$pt_include3}
        </div>
    </div>
    
<div class="clear"></div>
{$pt_debug}</div>


<!--Footer -->
<div id="footer">Copyright 2003-2008. &nbsp; Nils Hagemann, Paul Sellinger, Peter Sellinger, Michael Kr&auml;mer - <a href="{url_for_page pag_id=43|escape}">Impressum</a></div>

</body>
</html>