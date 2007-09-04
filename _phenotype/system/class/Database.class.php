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
?>
<?php
/**
 * @package phenotype
 * @subpackage system
 *
 */
class Database
{
	var $dbhandle;
	var $debug=0;
	var $myT;

	// Debug-Arrays

	public $_sql = Array();
	public $_times = Array();
	public $_results = Array();
	public $_files = Array();
	public $_lines = Array();

	function connect()
	{
		$this->dbhandle = mysql_pconnect ( DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD) or die ("<p><font color=\"#FF0000\">Unable to connect to SQL server</font></p>");
		$this->selectDatabase();
		if (PT_DEBUG==1)
		{
			$this->debug=1;
		}


	}

	function selectDatabase($database="")
	{
		if ($database =="")
		{
			$database = DATABASE_NAME;
		}
		mysql_select_db ($database, $this->dbhandle) or die("<p><font color=#FF0000>Unable to select database</font></p>");
	}

	function query($sql)
	{
		if ($this->debug==1)
		{
			$this->_sql[] = $sql;
			$this->myT = new TCheck();
			$this->myT->start();
		}
		$result = mysql_query ($sql, $this->dbhandle);

		if (mysql_errno($this->dbhandle))
		{
			if (PT_DEBUG==1)
			{

				$_traces =	debug_backtrace();

				$zeile = $_traces[0]["line"];
				$file = $_traces[0]["file"];
				$p = strrpos($file,'\\');
				$file = substr($file,$p+1);
 	  		?>
 	  		<br clear="all">
		 	<div style="position:absolute;background-color:#FF0000;left:10px;top:500px;width:1000px;opacity:0.8;color:white;
filter:alpha(opacity=85);padding-left:5px">
		 	<table>
		 	<tr><td>SQL-Fehler:</td><td><strong><?php echo mysql_errno($this->dbhandle) ?></strong></td></tr>
		 	<tr><td>Fehlermeldung:</td><td><strong><?php echo mysql_error($this->dbhandle) ?></strong></td></tr>
		 	<tr><td>SQL:</td><td><strong><?php echo $sql ?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?php echo $file ?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?php echo $zeile ?></strong></td></tr>
		 	</table>
		 	<br/>
		 	
		 	<?php
		 	foreach ($_traces AS $_trace)
		 	{
		 		?>
		 		Aufruf: Zeile <strong><?php echo $_trace["line"] ?></strong> in  <?php echo $_trace["file"] ?><br/>
		 		Methode: <?php echo $_trace["function"] ?><br/><br/>
		 		<?php
		 	}
			?>
		 	</div>
 	 		<?php
			}
			die();
		}
		else
		{
			if ($this->debug==1)
			{
				$this->myT->stop();
				$this->_times[] = $this->myT->getSeconds();
				$this->_results[] = mysql_affected_rows();

				$_traces =	debug_backtrace();
				$this->_files[] = $_traces[0]["file"];
				$this->_lines[] = $_traces[0]["line"];
			}
			return $result;
		}
	}


	function debug($switch=1)
	{
		$this->debug = (int)$switch;
	}

	function review($border = 0.01)
	{
		?>
		<br clear="all">
	 	<div style="position:absolute;background-color:#FFFF00;left:10px;top:500px;width:1000px;opacity:0.57;color:black;
filter:alpha(opacity=95);padding-left:10px;">
	 	<?php

	 	$c = count($this->_sql);

	 	for ($j=$c;$j>0;$j--)
	 	{
	 		$i=$j-1;
	 		$sql = "EXPLAIN ". $this->_sql[$i];
	 		$result = mysql_query ($sql, $this->dbhandle);
	 		if ($result)
	 		{
	 			$row = mysql_fetch_assoc($result);
	 		}

	 		$zeit = sprintf("%0.4f",$this->_times[$i]);
	 		if ($zeit>$border)
	 		{
	 			echo '<table style="color:red">';
	 		}
	 		else 
	 		{
	 			echo '<table>';
	 		}
	 		?>
	 		
	 		<tr><td>Nummer:</td><td><strong><?php echo $i+1 ?></strong></td></tr>
	 		<tr><td>SQL:</td><td><strong><?php echo htmlentities($this->_sql[$i]) ?></strong></td></tr>
	 		<tr><td>Zeit:</td><td><strong><?php echo $zeit ?></strong></td></tr>
	 		<tr><td>Zeilen:</td><td><strong><?php echo $this->_results[$i] ?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?php echo $this->_files[$i] ?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?php echo $this->_lines[$i] ?></strong></td></tr>
		 	<?php
			if ($result){
			?>
		 	<tr><td>Analyse:</td><td><div><table style="border:1px black solid;border-style:solid">
		 	<?php
		 	$tr1='<tr>';
		 	$tr2='<tr>';
		 	foreach ($row AS $k=>$v)
		 	{
		 		$tr1 .= '<td style="border:1px black solid;border-style:solid"><strong>'.$k.'</strong></td>';
		 		$tr2 .= '<td>'.$v.'</td>';
		 	}
		 	echo $tr1 . "</tr>";
		 	echo $tr2 . "</tr>";
		 	?></table></div></td></tr>
		 	<?php
			}
			?>
		 	</table>
		 	<br/><br/>
	 		<?php


	 	}
		?>
	 	</div>
	 	<?php
	}

	function startTransaction()
	{
	}

	function stopTransaction()
	{
	}

	function startT()
	{
		$this->startTransaction();
	}

	function stopT()
	{
		$this->stopTransaction();
	}
}
?>
