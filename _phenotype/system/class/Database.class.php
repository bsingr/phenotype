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
class PhenotypeDatabase
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
    if (is_object($myPT))
    {
      $myPT->suppressPHPWarnings();
    }
    $this->dbhandle = mysql_pconnect ( DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD) or $myPT->displayErrorPage("DB Exception","Unable to connect to SQL server.");
    if (is_object($myPT))
    {
      $myPT->respectPHPWarnings();
    }
    $this->selectDatabase();

    if (PT_DEBUG==1)
    {
      $this->debug=1;
    }

    // for more information about MySQL modes look http://dev.mysql.com/doc/refman/5.0/en/server-sql-mode.html
    $sql = "SET @@session.sql_mode='NO_FIELD_OPTIONS';";
    $this->query($sql,"Setting MySQL operation mode.");
    if (PT_CHARSET=='utf-8')
    {
      $sql = "SET NAMES 'utf8'";
    }
    else
    {
      $sql = "SET NAMES 'latin1'";
    }
    $this->query($sql);

    $sql = "DELETE FROM dataobject WHERE dao_ttl <" . time() ." AND dao_ttl <>0";
    $this->query($sql);
    
    
  }

  function selectDatabase($database="")
  {
    if ($database =="")
    {
      $database = DATABASE_NAME;
    }
    global $myPT;
    if (is_object($myPT))
    {
      $myPT->suppressPHPWarnings();
    }
    mysql_select_db ($database, $this->dbhandle) or $myPT->displayErrorPage("DB Exception","Unable to select database.");
    if (is_object($myPT))
    {
      $myPT->respectPHPWarnings();
    }
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
        $sql_cut = $sql;
        if (strlen($sql)>512)
        {
        	$sql_cut = substr($sql,0,512)."...";
        }
 	  		?>
 	  		<br clear="all">
		 	<div style="position:absolute;background-color:#FF0000;left:10px;top:500px;width:1000px;opacity:0.8;color:white;
filter:alpha(opacity=85);padding-left:5px">
		 	<table>
		 	<tr><td>SQL-Fehler:</td><td><strong><?php echo mysql_errno($this->dbhandle)?></strong></td></tr>
		 	<tr><td>Fehlermeldung:</td><td><strong><?php echo mysql_error($this->dbhandle)?></strong></td></tr>
		 	<tr><td>SQL:</td><td><strong><?php echo $sql_cut?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?php echo $file?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?php echo $zeile?></strong></td></tr>
		 	</table>
		 	<br/>
		 	
		 	<?php
		 	foreach ($_traces AS $_trace)
		 	{
		 		?>
		 		Aufruf: Zeile <strong><?php echo $_trace["line"]?></strong> in  <?php echo $_trace["file"]?><br/>
		 		Methode: <?php echo $_trace["function"]?><br/><br/>
		 		<?php
		 	}
			?>
		 	</div>
 	 		<?php
      }
      else 
      {
        global $myApp;
        if (is_object($myApp))
        {
          ob_clean();
          $myApp->throw500();
        }
       
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
	 		
	 		<tr><td>Nummer:</td><td><strong><?php echo $i+1?></strong></td></tr>
	 		<tr><td>SQL:</td><td><strong><?php echo htmlentities($this->_sql[$i])?></strong></td></tr>
	 		<tr><td>Zeit:</td><td><strong><?php echo $zeit?></strong></td></tr>
	 		<tr><td>Zeilen:</td><td><strong><?php echo $this->_results[$i]?></strong></td></tr>
		 	<tr><td>Datei:</td><td><strong><?php echo $this->_files[$i]?></strong></td></tr>
		 	<tr><td>Zeile:</td><td><strong><?php echo $this->_lines[$i]?></strong></td></tr>
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
