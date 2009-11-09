<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2009 Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support:
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version 2.91 vom 01.08.2009
// -------------------------------------------------------

/**
 * This class handles collection and presentating of debugging data
 * 
 * 
 * RF: Currently also the Phenotype main class and the database class collects data, furthermore
 * the page class displays the debug info line. All those things should be integrated in this
 * class someday
 * 
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeDebuggerStandard
{
	
	/**
	 * stores array of all includes used during one request
	 * 
	 * (if the debugger gets notified about usage)
	 *
	 * @var array[integer]
	 */
	protected $_includes = Array();
	
	/**
	 * stores array of all content objects (respectively it's classes) used during one request
	 * 
	 * (if the debugger gets notified about usage)
	 *
	 * @var array[integer]
	 */
	protected $_contentclasses = Array();
	
	/**
	 * stores array of all page components used during one request
	 * 
	 * (if the debugger gets notified about usage)
	 *
	 * @var array[integer]
	 */
	protected $_pagecomponents = Array();

	public function start()
	{
		global $myTC;
		// Time check initialize
		$myTC = new TCheck();
		$myTC->start();
	}

	/**
	 * Inform debugger upon include usage
	 *
	 * @param integer $inc_id
	 */
	public function notifyIncludeUsage($inc_id)
	{
		$this->_includes[]=$inc_id;
	}

	/**
	 * Inform debugger upon content class usage
	 *
	 * @param integer $con_id
	 */
	public function notifyContentClassUsage($con_id)
	{
		$this->_contentclasses[]=$con_id;
	}
	
	/**
	 * Inform debugger upon component usage
	 *
	 * @param integer $com_id
	 */
	public function notifyPageComponentUsage($com_id)
	{
		$this->_pagecomponents[]=$com_id;
	}

	/**
	 * display debug info (html output)
	 *
	 */
	public function displayDebugInfo()
	{
		global $myDB;
		global $myRequest;
		global $myPT;

		$headline = "Phenotype DebugInfo";
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo PT_CHARSET?>" />
<title><?php echo codeH($headline)?></title>
<meta name="generator" content="Phenotype CMS" />
<style type="text/css">
body {
	background-color: #fff;
	font-family: Verdana, Arial;
	font-size: 12px;
}

em {
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
	font-variant: small-caps;
	border-bottom: 1px solid #CFCFCF;
	padding: 0px 1px 0px 1px;
	line-height: 40px;
	letter-spacing: 4px;
}

#main {
	background: #F7F7F7 none repeat scroll 0%;
	border-bottom: 1px solid #CFCFCF;
	border-top: 1px solid #CFCFCF;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
}

#footer {
	font-size: 10px;
	width: 780px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	text-align: right;
	height: 50px;
}

#header {
	color: #000;
}

#message {
	color: #f00;
	font-weight: bold;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 10px;
	margin-bottom: 0px;
}

.request {
	font-family: Courier;
	list-style: none;
	font-size: 11px;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 0px;
	margin-bottom: 20px;
	overflow: auto;
}

.param_key {
	display: block;
	width: 125px;
	float: left;
}

.param_value {
	color: #cfcfcf;
}

.filename {
	font-size: 9px;
	color: #cfcfcf;
	padding: 2px;
	line-height: 18px;
}

.exec_context {
	background-color: #cfcfcf;
	font-size: 9px;
	color: #fff;
	padding: 2px 5px 5px 10px;
	margin: 0px;
	line-height: 18px;
}

.source {
	font-family: Courier;
	list-style: none;
	font-size: 11px;
	background-color: #fff;
	padding: 7px; #
	margin: 5px;
	margin-top: 0px;
	margin-bottom: 20px;
	overflow: auto;
}

.current {
	background-color: #CFCFCF;
}

.query {
	background-color: #fff;
	font-family: Courier;
	color: #cfcfcf;
	font-weight: normal;
	font-size: 9px;
	width: 780px;
}

.querynr {
	color: #000;
	width: 60px;
}

.querydetails {
	width: 720px;
	font-size: 9px;
	font-family: Verdana, Arial;
}

.querydetails td,th {
	border: 1px solid #cfcfcf;
}
</style>
</head>
<body>
<div id="main">
<div id="header"><strong><?php echo codeH($headline)?></strong></div>
	<?php if (is_object($myRequest)){?> <em>Request:</em><br />
<div id="request">
<ul class="request">
<?php foreach ($myRequest->getParamsArray() AS $k => $v){?>
	<li><span class="param_key">#<?php echo codeH($k)?></span>: <span
		class="param_value"><?php echo codeH($v)?></span></li>
		<?php }?>
</ul>
</div>
		<?php }?>
<div id="database"><em>SQL Queries</em><br />
		<?php
		$c = count($myDB->_sql);
		$border = 0.0001; // meaning no border at the moment
		$context ="";
		for ($j=1;$j<=$c;$j++)
		{
			$i=$j-1;
			$zeit = sprintf("%0.4f",$myDB->_times[$i]);
			$querydetails ="";
			if ($zeit>$border)
			{
				$sql = "EXPLAIN ". $myDB->_sql[$i];
				$result = mysql_query ($sql, $myDB->dbhandle);
				if ($result)
				{
					$_keys = Array ("id","select_type","table","type","possible_keys","key","key_len","ref","rows","Extra");
					$html = '<table class="querydetails"><tr>';
					foreach ($_keys AS $key)
					{
						$html .= '<th>'.codeH($key).'</th>';
					}
					$html .= '</tr>';
					while ($row = mysql_fetch_assoc($result))
					{
						$html .='<tr>';
						foreach ($_keys AS $key)
						{
							$html .= '<td>'.codeH($row[$key]).'</td>';
						}
						$html .= '</tr>';
					}
					$html .= '</table>';
					$querydetails = $html;

				}
			}
			if ($myDB->_context[$i]!=$context)
			{
				$context = $myDB->_context[$i];
				if ($context !=""){
					?><span class="exec_context"><?php echo $context?></span><?php
				}
			}

			$sql_cut = $myDB->_sql[$i];

			if (mb_strlen($sql_cut) > 512)
			{
				$sql_cut = mb_substr($sql_cut,0,512)."...";
			}
?><span class="filename">[<?php echo $myPT->getFilenameOutOfPath($myDB->_files[$i])?>
in line <?php echo $myDB->_lines[$i]?>]</span><br />
<table class="query">
	<tr>
		<td rowspan="3" class="querynr" valign="top">#<?php echo sprintf('%04d',$i+1)?>:</td>
		<td><?php echo codeH($sql_cut)?></td>
	</tr>
	<tr>
		<td><?php echo $myDB->_results[$i]?> record(s) in <?php echo $zeit?>
		seconds</td>
	</tr>
	<?php if ($querydetails!=""){?>
	<tr>
		<td><?php echo $querydetails ?></td>
	</tr>
	<?php } ?>
</table>
<br />
<br />
	<?php }?></div>
	<?php if (count ($myPT->_debughints)!=0){?>
<div id="hints"><em>PHP Hints:</em><br />
	<?php foreach ($myPT->_debughints AS $_hint)
	{
		if (file_exists($_hint["file"]))
		{
			$_lines = file ($_hint["file"]);
			$line = $_hint["line"];
		}
		else
		{
			$_lines = Array();
			$line=0;
		}
		$c = count($_lines);


		$start = max(1,$line-2);
		$stop = min($c,$line+2);
		?> <span class="exec_context"><?php echo codeH($_hint["message"])?></span><span
	class="filename">[<?php echo $myPT->getFilenameOutOfPath($_hint["file"])?>]</span>
<ul class="source">
<?php for ($i=$start;$i<=$stop;$i++){?>
	<li <?php if ($i==$line){?> class="current" <?php }?>><span>#<?php echo sprintf('%04d',$i)?>:
	</span><?php echo $myPT->colorcodeHTML($_lines[$i-1])?></li>
	<?php }?>
</ul>
	<?php }?></div>

	<?php
	$myDao = new PhenotypeSystemDataObject("DebugLookUpTable");

	?>
<div id="lookup"><em>Quick Lookup</em><br />
<?php if (count($myDao->get("components"))!=0){?>
<span class="exec_context">components</span>
<ul class="source">
<?php foreach ($myDao->get("components") AS $k=>$v){?>
	<li><span>#<?php echo sprintf('%04d',$k)?>: </span>
	<?php 
	if (in_array($k,$this->_pagecomponents))
	{
		echo '<a href="'.ADMINFULLURL.'component_edit.php?id='.$k.'" target="_blank">'.$v.'</a>';
	}
	else 
	{
		echo $v;	
	}
	?></li>
	<?php }?>
</ul>
<?php }?>
<?php if (count($myDao->get("content"))!=0){?>
<span class="exec_context">content object classes</span>
<ul class="source">
<?php foreach ($myDao->get("content") AS $k=>$v){?>
	<li><span>#<?php echo sprintf('%04d',$k)?>: </span>
	<?php 
	if (in_array($k,$this->_contentclasses))
	{
		echo '<a href="'.ADMINFULLURL.'contentobject_edit.php?id='.$k.'" target="_blank">'.$v.'</a>';
	}
	else 
	{
		echo $v;	
	}
	?></li>
	<?php }?>
</ul>
<?php }?>
<?php if (count($myDao->get("includes"))!=0){?>
<span class="exec_context">includes</span>
<ul class="source">
<?php foreach ($myDao->get("includes") AS $k=>$v){?>
	<li><span>#<?php echo sprintf('%04d',$k)?>: </span>
	<?php 
	if (in_array($k,$this->_includes))
	{
		echo '<a href="'.ADMINFULLURL.'include_edit.php?id='.$k.'" target="_blank">'.$v.'</a>';
	}
	else 
	{
		echo $v;	
	}
	?></li>
	<?php }?>
</ul>
<?php }?>
</div>
	<?php }?></div>
<div id="footer"><?php echo date('d.m.Y H:i');?></div>

</body>
</html>
	<?php

	}


}