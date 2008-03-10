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
class Database
{
  var $dbhandle;
  var $debug=0;
  var $myT;
  var $context = "";
  var $nextcontext = "";

  // Debug-Arrays

  public $_sql = Array();
  public $_times = Array();
  public $_results = Array();
  public $_files = Array();
  public $_lines = Array();
  public $_context = Array();

  function connect()
  {
    global $myPT;
    $myPT->suppressPHPWarnings();
    $this->dbhandle = mysql_pconnect ( DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD) or $myPT->displayErrorPage("DB Exception","Unable to connect to SQL server.");
    $myPT->respectPHPWarnings();
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
    global $myPT;
    $myPT->suppressPHPWarnings();
    mysql_select_db ($database, $this->dbhandle) or $myPT->displayErrorPage("DB Exception","Unable to select database.");
     $myPT->respectPHPWarnings();
  }

  function query($sql,$context="")
  {
    
    if ($this->debug==1)
    {
      if ($context!="")
      {
        $this->context =$context;
      }
      else
      {
        if ($this->nextcontext!="" AND ($this->context != $this->nextcontext))
        {
          $context = $this->nextcontext;
          $this->context = $context;
        }
      }
      $this->_sql[] = $sql;
      $this->_context[] = $context;
      $this->myT = new TCheck();
      $this->myT->start();
    }
    $result = mysql_query ($sql, $this->dbhandle);

    if (mysql_errno($this->dbhandle))
    {
      if (PT_DEBUG==1)
      {
        global $myPT;
        $_traces =	debug_backtrace();
        $myPT->displayErrorPage("SQL Error",mysql_errno($this->dbhandle)." - ".mysql_error($this->dbhandle),$_traces[0]["file"],$_traces[0]["line"],$sql);
        

        $zeile = $_traces[0]["line"];
        $file = $_traces[0]["file"];
        $p = strrpos($file,'\\');
        $file = substr($file,$p+1);
 	  		?>
 	  		<br clear="all">
		 	<div style="position:absolute;background-color:#FF0000;left:10px;top:500px;width:1000px;opacity:0.8;color:white;
filter:alpha(opacity=85);padding-left:5px">
		 	<table>
		 	<tr><td>SQL-Fehler:</td><td><strong><?=mysql_errno($this->dbhandle)?></strong></td></tr>
		 	<tr><td>Fehlermeldung:</td><td><strong><?=mysql_error($this->dbhandle)?></strong></td></tr>
		 	<tr><td>SQL:</td><td><strong><?=$sql?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?=$file?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?=$zeile?></strong></td></tr>
		 	</table>
		 	<br/>
		 	
		 	<?
		 	foreach ($_traces AS $_trace)
		 	{
		 		?>
		 		Aufruf: Zeile <strong><?=$_trace["line"]?></strong> in  <?=$_trace["file"]?><br/>
		 		Methode: <?=$_trace["function"]?><br/><br/>
		 		<?
		 	}
			?>
		 	</div>
 	 		<?
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
	 	<?

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
	 		
	 		<tr><td>Nummer:</td><td><strong><?=$i+1?></strong></td></tr>
	 		<tr><td>SQL:</td><td><strong><?=htmlentities($this->_sql[$i])?></strong></td></tr>
	 		<tr><td>Zeit:</td><td><strong><?=$zeit?></strong></td></tr>
	 		<tr><td>Zeilen:</td><td><strong><?=$this->_results[$i]?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?=$this->_files[$i]?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?=$this->_lines[$i]?></strong></td></tr>
		 	<?
		 	if ($result){
			?>
		 	<tr><td>Analyse:</td><td><div><table style="border:1px black solid;border-style:solid">
		 	<?
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
		 	<?
		 	}
			?>
		 	</table>
		 	<br/><br/>
	 		<?


	 	}
		?>
	 	</div>
	 	<?
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

  function setNextContext($context)
  {
    $this->nextcontext = $context;
  }
  
  function getQueries()
  {
    return $this->_sql;
  }

}
?>
