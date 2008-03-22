<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$title|escape}</title>
<meta name="author" content="Nils Hagemann"/>
<meta name="keywords" content="{$keywords|escape}"/>

{literal}
<style type="text/css">
body {color:#000000;margin:0px; padding:0px; text-align:left; font-family: Verdana, Arial, sans-serif;font-size: 11px; font-weight: normal;}
a {color:#003F96;text-decoration:none;}
a:visited {color: #003F96;text-decoration:none;} 
a:hover {text-decoration:underline;}
#header {padding-left: 200px;}
#header li {list-style:none;display:inline;padding-right:40px;font-size:25px;background-color:#EEEEEE;}
#header li.active {font-weight:bold}

#navigation li.level1 {font-size:20px;padding-left:5px;list-style:none}
#navigation li.level2 {font-size:16px;padding-left:15px;list-style:none}
#navigation li.level3 {font-size:14px;padding-left:20px;list-style:none}
#navigation li.level4 {font-size:12px;padding-left:25px;list-style:none}
#navigation li.active {font-weight:bold}

#footer {padding-left:150px}
</style>
{/literal}
</head>

<body>

<!--Header -->
<div id="header">
{$pt_include1}
</div>
<br clear="all"/>

<!--Content -->
<div id="navigation" style="position:absolute">
{$pt_include2}
</div>
<div id="content" style="padding-left:200px;width:250px">
{$pt_block1}
</div>
<br clear="all"/>
<!--Footer -->
<div id="footer" style="padding-left:200px;width:250px">
{$pt_include3}
</div>

</body>
</html> 