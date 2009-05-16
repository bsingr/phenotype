<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr�mer.
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
 * @subpackage backend
 *
 */
class PhenotypeBackend_Editor_Content_Standard extends PhenotypeBackend_Editor
{

  public $tmxfile = "Editor_Content";


  // Following variables determines the focus of the content browser

  public $category;
  public $con_id;

  public $_jsarray = Array(); // This array holds Javscript-Functions when displaying the content edit form

  function execute($scope,$action)
  {
    global $myPT;
    global $mySUser;
    global $myRequest;
    global $myDB;


    $this->checkRight("elm_content",true);

    $this->setPageTitle("Phenotype ".$myPT->version. " " . locale("Content"));

    $this->selectMenuItem(2);
    $this->selectLayout(1);

    $body_onload ="";

    $block_nr = $myRequest->getI("b");

    $this->con_id = $myRequest->getI("con_id");
    $this->category = $myRequest->get("r");

    switch ($action)
    {
      case "edit":
        $this->fillContentArea1($this->renderEdit());
        $body_onload = $this->_jsarray["body_onload"];
        break;
      case "update":
        $myPT->clearCache();
        $this->update();
        break;
      case "debug":
        $this->debug();
        break;
      case "copy":
        $this->copy();
        break;
      case "insert":
        $myPT->clearCache();
        $this->insert();
        break;
      case "delete":
        $myPT->clearCache();
        $this->delete();
        break;
      case "select":
        $this->fillContentArea1($this->renderSelect());
        break;
      case "search":
        $this->fillContentArea1($this->renderSearch());
        break;
      case "rollback":
        $myPT->clearCache();
        $this->fillContentArea1($this->renderRollback());
        break;
      case "viewsnapshot":
        $this->viewSnapshot($myRequest->getI("id"));
        return;
        break;
      case "installsnapshot":
        $myPT->clearCache();
        $this->installSnapshot($myRequest->getI("id"),$myRequest->get("sna_type"));
        break;
      case "form_ajax":
        $this->execute_form_ajax();
        break;
        // the action selector_date expects three parameters
        // e = name of the element which hold the date (for update)
        // t = flag, if the date ist given as a timestamp (1) or in a local format (currently german only)
        // d = date
      case "selector_date":
        $timestamp = $myRequest->getI("t");
        $element=$myRequest->get("e");
        if ($timestamp==1)
        {
          $date = $myRequest->getI("d");
        }
        else
        {
          $date = $myPT->germanDT2Timestamp($myRequest->get("d"));
        }
        $this->displayDateSelector($date,$element);
        return;
        break;
      default:
        $this->fillContentArea1($this->renderOverview());
        break;
    }

    $this->fillLeftArea($this->renderExplorer());
    $this->displayPage($body_onload);
  }


  function renderExplorer()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $myPT->startBuffer();

    $this->tab_new();


    $url = "backend.php?page=Editor,Pages";
    if ($this->checkRight("elm_page"))
    {
      $this->tab_addEntry(locale("Pages"),$url,"b_site.gif");
    }

    $url = "backend.php?page=Editor,Content";
    $this->tab_addEntry(locale("Content"),$url,"b_content.gif");

    if ($this->checkRight("elm_mediabase"))
    {
      $url = "backend.php?page=Editor,Media,browse&grp_id=0&folder=&type=0&sortorder=1&p=1&a=10";
      $this->tab_addEntry(locale("Media"),$url,"b_media.gif");
    }

    $this->tab_draw(locale("Content"),$x=260,1);




    $this->displayClassTree();

    $this->displaySearchForm();

    return $myPT->stopBuffer();
  }

  function displayClassTree()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $myNav = new PhenotypeTree();
    $nav_id_start = $myNav->addNode(localeH("Overview"),"backend.php?page=Editor,Content",0,"r_");
    $sql = "SELECT * FROM content ORDER BY con_rubrik, con_bez";
    if ($myPT->getPref("edit_content.flat_tree")==1)
    {
      $sql = "SELECT * FROM content ORDER BY con_bez";
    }
    $rs = $myDB->query($sql);


    $rubrik = "";
    $nav_id_rubrik = $nav_id_start;
    while ($row=mysql_fetch_array($rs))
    {
      $access = 0;
      if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
      if ($access==1)
      {
        if ($myPT->getPref("edit_content.flat_tree")==1)
        {
          $myNav->addNode($row["con_bez"],"backend.php?page=Editor,Content,select&con_id=".$row["con_id"]."&c=akt",$nav_id_start,$row["con_id"]);
        }
        else
        {
          if ($row["con_rubrik"]!=$rubrik)
          {
            $rubrik = $row["con_rubrik"];
            if ($rubrik!="")
            {
              $nav_id_rubrik = $myNav->addNode($rubrik,"backend.php?page=Editor,Content&r=".urlencode($rubrik),$nav_id_start,"r_" . $row["con_rubrik"]);
            }
          }
          if ($this->category==$rubrik)
          {
            if ($rubrik!="")
            {
              $myNav->addNode($row["con_bez"],"backend.php?page=Editor,Content,select&con_id=".$row["con_id"] . "&r=".$rubrik."&b=0&c=akt",$nav_id_rubrik,$row["con_id"]);
            }
            else
            {
              $myNav->addNode($row["con_bez"],"backend.php?page=Editor,Content,select&con_id=".$row["con_id"] . "&r=".$rubrik."&b=0&c=akt",$nav_id_start,$row["con_id"]);
            }
          }
          else
          {
            if ($rubrik=="")
            {
              $myNav->addNode($row["con_bez"],"backend.php?page=Editor,Content,select&con_id=".$row["con_id"] . "&r=".$rubrik."&b=0&c=akt",$nav_id_start,$row["con_id"]);
            }
          }
        }
      }
    }

    if ($this->con_id!=0)
    {
      $token =  $this->con_id;
    }
    else
    {
      $token = "r_".$this->category;
    }
    $this->displayTreeNavi($myNav,$token);


  }

  function displaySearchForm()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    if ($this->con_id !=0)
    {
      $sql_con = "SELECT con_bez FROM content WHERE con_id = " .$this->con_id;
      $rs = $myDB->query($sql_con);
      $row = mysql_fetch_array($rs);
      $contenttype= " / " . $row["con_bez"];
    }
    else
    {
      if ($this->category!="")
      {
        $contenttype= " / " . $this->category;
      }
    }

		?>		
	 <table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" class="padding10"><strong><?php echo localeH("Search Content");?> <?php echo $myPT->codeH($contenttype) ?> <?php echo localeH("for");?>:</strong></td>
            </tr>
            <tr>
              <td class="padding10"><?php echo localeH("Name");?></td>
              <td>
			  <form action="backend.php" method="post">
			  <input type="hidden" name="page" value="Editor,Content,search">
 	  		  <input type="hidden" name="con_id" value="<?php echo $this->con_id ?>">
			  <input type="hidden" name="r" value="<?php echo $myPT->codeH($this->category) ?>">
		      <input type="hidden" name="c" value="search">
			  <input type="text" name="s" style="width: 100
			  px" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> <?php echo localeH("ID");?> </td>
              <td><input type="text" style="width: 100
			  px" name="i" class="input"></td>
            </tr>
            <tr>
              <td class="padding10"> <?php echo localeH("Fulltext");?> </td>
              <td><input type="text" style="width: 100
			  px" name="v" class="input"></td>
            </tr>
            <tr>
              <td class="padding10">&nbsp;</td>
              <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("Send")?>" style="width:102px"></form></td>
            </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		<?php
		if ($this->con_id !=0)
		{
		  $sql = "SELECT con_anlegen FROM content WHERE con_id=".$this->con_id;
		  $rs= $myDB->query($sql);
		  $row = mysql_fetch_array($rs);
		  if ($row["con_anlegen"]==1)
		  {
		?>
		<tr>
          <td class="windowFooterGrey2"><a href="backend.php?page=Editor,Content,insert&con_id=<?php echo $this->con_id ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" heighcon_id="22" border="0" align="absmiddle"> <?php echo localeH("Add new record");?> </a></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		<?php
		  }
		}
		?>
        <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" heighcon_id="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" heighcon_id="10"></td>
        </tr>
      </table>
	  <?php
  }

  /**
	 * Display few items of every content type, when no content type is selected.
	 * Accounts category, if submitted
	 *
	 */
  function renderOverview()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $myPT->startBuffer();
    $headline = locale("Overview");
    if ($this->category!=""){$headline=$this->category;}
    $this->displayHeadline($headline,"http://www.phenotype-cms.de/docs.php?v=23&con_id=2");



    if ($this->category=="")
    {
      $sql = "SELECT * FROM content ORDER BY con_bez";
    }
    else
    {
      $sql = "SELECT * FROM content WHERE con_rubrik='" .mysql_escape_string($this->category)."' ORDER BY con_bez";
    }

    $rs = $myDB->query($sql);
    while ($row = mysql_fetch_array($rs))
    {
      $access = 0;
      if ($mySUser->checkRight("con_".$row["con_id"])){$access=1;}
      if ($access==1)
      {
    		?>
			<table width="680" border="0" cellpadding="0" cellspacing="0">
		      <tr>
		        <td class="windowTabTypeOnly"><strong><?php echo $row["con_bez"] ?></strong></td>
		        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" heighcon_id="10"></td>
		      </tr>
		    </table>
			<?php
			$sql = "SELECT * FROM content_data WHERE con_id = " . $row["con_id"];
			$cname = "PhenotypeContent_" . $row["con_id"];
			$myCO = new $cname;
			$filter = $myCO->getAccessFilter();
			if ($filter !="")
			{
			  $sql .= " AND (" . $filter .")";
			}
			$sql .= " ORDER BY dat_date DESC LIMIT 0,2";
			$rs2 = $myDB->query($sql);

			$this->displayContentRecords($rs2);

			?>
			<table width="680" border="0" cellpadding="0" cellspacing="0">
		 	<?php
		 	if ($row["con_anlegen"]==1)
		 	{
		 	?>
		      <tr>
		        <td class="windowFooterGrey2"><a href="backend.php?page=Editor,Content,insert&con_id=<?php echo $row["con_id"] ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new record");?></a></td>
		        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
		      </tr>
			<?php 
		 	}
		 	?>
		    <tr>
		      <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
		      <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
		    </tr>
		    </table><br>
	    	<?php
      }
    }
    return $myPT->stopBuffer();
  }

  function renderSelect()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $myPT->startBuffer();

    $sql = "FROM content_data WHERE con_id = " . $this->con_id;
    $cname = "PhenotypeContent_" . $this->con_id;
    $myCO = new $cname;
    $filter = $myCO->getAccessFilter();
    if ($filter !="")
    {
      $sql .= " AND (" . $filter. ")";
    }

    $sql_con = "SELECT con_bez FROM content WHERE con_id = " .$this->con_id;
    $rs = $myDB->query($sql_con);
    $row = mysql_fetch_array($rs);
    $headline= locale("Content")." / " . $row["con_bez"];

    $this->displayHeadline($headline,"http://www.phenotype-cms.de/docs.php?v=23&con_id=2");

    $this->displayEAIF();

    $order = $myRequest->get("c");
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td class="windowTabTypeOnly"><table border="0" cellpadding="0" cellspacing="1">
            <tr>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=akt" class="tabmenuType<?php if($order=="akt"){echo"Active";} ?>"><?php echo localeH("Current");?></a></td>
				<?php
				// Individuelle Tabs
				foreach ($myCO->_extratabs AS $k => $v)
				{
				  $titel = $v[0];
				?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=etab_<?php echo $k ?>" class="tabmenuType<?php if($order=="etab_".$k){echo"Active";} ?>"><?php echo $titel ?></a></td>
				<?php
				}
				if ($myCO->tab_az)
				{
				?>
			  	<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=num" class="tabmenuType<?php if($order=="num"){echo"Active";} ?>">0-9</a></td>
              		<?php
              		for ($i=1;$i<=26;$i++)
              		{
              		  $c = chr(64+$i);
			  		?>
			  		<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=<?php echo $c ?>" class="tabmenuType<?php if($order==$c){echo"Active";} ?>"><?php echo $c ?></a></td>
				  	<?php
              		}
				}
				if ($myCO->tab_shortaz)
				{
				  $_az = Array("ABC","DEF","GHIJ","KLMN","OPQR","STU","VWXYZ");
		  			?>
		  	 		<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=num" class="tabmenuType<?php if($order=="num"){echo"Active";} ?>">0-9</a></td>
       	  			<?php
       	  			foreach ($_az AS $k)
       	  			{
		  				?>
		  				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=<?php echo strtolower($k) ?>" class="tabmenuType<?php if($order==strtolower($k)){echo"Active";} ?>"><?php echo $k ?></a></td>
				  		<?php
       	  			}
				}
				if ($myCO->tab_alle)
				{
		      	?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=alle" class="tabmenuType<?php if($order=="alle"){echo"Active";} ?>"><?php echo locale("All") ?></a></td><?php
				}
				if ($myCO->tab_id)
				{
			  	?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&c=id" class="tabmenuType<?php if($order=="id"){echo"Active";} ?>">ID</a></td><?php
			  }?>
            </tr>
        </table></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" heighcon_id="10"></td>
      </tr>
    </table>
		<?php


		switch ($order)
		{
		  case "alle":
		    break;
		  case "id";
		  break;
		  case "num":;
		  $sql .= " AND (ASCII(dat_bez) <65 OR ASCII(dat_bez) > 90  AND ASCII(dat_bez) < 97 OR  ASCII(dat_bez) > 122) ";
		  break;
		  case "search";
		  if ($myRequest->get("s")!="")
		  {
		    $sql .= " AND dat_bez LIKE '%". $myRequest->getSQL("s")."%'";
		  }
		  if ($myRequest->get("v")!="")
		  {
		    $sql .= " AND dat_fullsearch LIKE '%". $myRequest->getSQL("v")."%'";
		  }
		  if ($myRequest->getI("i")!=0)
		  {
		    $sql .= " AND dat_id = ". $_REQUEST["i"];
		  }
		  break;
		  case "abc":
		    $sql .= " AND LEFT(dat_bez,1) >='a' AND LEFT(dat_bez,1) <='c'";
		    break;
		  case "def":
		    $sql .= " AND LEFT(dat_bez,1) >='d' AND LEFT(dat_bez,1) <='f'";
		    break;
		  case "ghij":
		    $sql .= " AND LEFT(dat_bez,1) >='g' AND LEFT(dat_bez,1) <='j'";
		    break;
		  case "klmn":
		    $sql .= " AND LEFT(dat_bez,1) >='k' AND LEFT(dat_bez,1) <='n'";
		    break;
		  case "opqr":
		    $sql .= " AND LEFT(dat_bez,1) >='o' AND LEFT(dat_bez,1) <='r'";
		    break;
		  case "stu":
		    $sql .= " AND LEFT(dat_bez,1) >='s' AND LEFT(dat_bez,1) <='u'";
		    break;
		  case "vwxyz":
		    $sql .= " AND LEFT(dat_bez,1) >='v' AND LEFT(dat_bez,1) <='z'";
		    break;
		  default:
		    $sql .= " AND dat_bez LIKE '". mysql_escape_string($order) ."%'";
		    break;
		}

		switch ($order)
		{
		  case "id":
		    $sql .= " ORDER BY dat_id";
		    break;
		  case "akt":
		    $sql ="FROM content_data WHERE con_id = " . $this->con_id;
		    if ($filter !="")
		    {
		      $sql .= " AND " . $filter;
		    }
		    $sql .= " ORDER BY dat_date DESC";

		    break;
		  default:
		    $sql .= " ORDER BY dat_bez";
		    break;
		}

		// Check, ob es sich um ein speziell konfigurierten Reiter handelt
		if (substr($order,0,5)=="etab_")
		{
		  $sql = "FROM content_data WHERE con_id = " . $this->con_id;
		  if ($filter !="")
		  {
		    $sql .= " AND " . $filter;
		  }
		  $extratab  = $myCO->_extratabs[substr($order,5)];
		  $sql .= " AND " .$extratab[1];
		}


		// count records

		$sql1 = "SELECT COUNT(*) AS C " . $sql;
		$rs = $myDB->query($sql1);
		$row = mysql_fetch_array($rs);
		$anzahl = $row["C"];

		//echo "DEBUG: " . $sql;

		// determine page

		$p = $myRequest->getI("p");
		if ($p<1){$p=1;}
		$sql2 = "SELECT * " . $sql;
		$start = ($p-1)*(10);
		$sql2 .=" LIMIT ". $start . ",10";

		$rs = $myDB->query($sql2);

		$this->displayContentRecords($rs);
		//$this->overview_content_draw($sql2,$this->con_id,2);

		$url = "backend.php?page=Editor,Content,select&con_id=".$this->con_id."&r=".$this->category."&b=0&c=".$order."&p=";
		echo $this->renderPageBrowser($p,$anzahl,$url);

		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	 	<?php
	 	$sql = "SELECT * FROM content WHERE con_id=".$this->con_id;
	 	$rs = $myDB->query($sql);
	 	$row = mysql_fetch_array($rs);
	 	if ($row["con_anlegen"]==1)
	 	{
	 	?>
	      <tr>
	        <td class="windowFooterGrey2"><a href="backend.php?page=Editor,Content,insert&con_id=<?php echo $this->con_id ?>" class="tabmenu"><img src="img/b_add_page.gif" width="22" height="22" border="0" align="absmiddle"> <?php echo localeH("Add new record");?></a></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
		<?php 
	 	}
	 	?>
	    <tr>
	      <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
	      <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
	    </tr>
	    </table><br>
    	<?php

    	return $myPT->stopBuffer();

  }


  function renderSearch()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $myPT->startBuffer();

    if ($this->con_id!=0)
    {
      $sql = "FROM content_data WHERE con_id = " . $this->con_id;
      $cname = "PhenotypeContent_" . $this->con_id;
      $myCO = new $cname;
      $filter = $myCO->getAccessFilter();
      if ($filter !="")
      {
        $sql .= " AND (" . $filter. ")";
      }

      $sql_con = "SELECT con_bez FROM content WHERE con_id = " .$this->con_id;
      $rs = $myDB->query($sql_con);
      $row = mysql_fetch_array($rs);
      $headline= locale("Search Content")." / " . $row["con_bez"] ." ";
      $display_content_type=false;
    }
    else
    {
      $sql = "FROM content_data WHERE (";
      if ($this->category!="")
      {
        $sql_rubrik = "SELECT con_id,con_rubrik FROM content WHERE con_rubrik = '" . $myPT->codeSQL($this->category)."'";
      }
      else
      {
        $sql_rubrik = "SELECT con_id,con_rubrik FROM content";
      }
      $rs = $myDB->query($sql_rubrik);
      $f=0;
      while ($row =mysql_fetch_array($rs))
      {
        if (!$mySUser->checkRight("con_".$row["con_id"]))
        {
          if ($f==0)
          {
            $sql_rubrik .= " WHERE con_id != " . $row["con_id"];
            $f=1;
          }
          else
          {
            $sql_rubrik .= " AND con_id != " . $row["con_id"];
          }
        }
      }
      $rs = $myDB->query($sql_rubrik);
      $c= mysql_num_rows($rs);
      $i=0;
      while ($row =mysql_fetch_array($rs))
      {
        $i++;

        $sql.= "(con_id=". $row["con_id"];
        $cname = "PhenotypeContent_" . $row["con_id"];
        $myCO = new $cname;
        $filter = $myCO->getAccessFilter();
        if ($filter !="")
        {
          $sql .= " AND (" . $filter .")";
        }
        if ($i<$c)
        {
          $sql .=") OR ";
        }

      }
      $sql .=")) ";

      $headline = localeH("Search") ." ";
      $display_content_type=true;
    }


    $pagingUrlExt = "";
    if ($myRequest->get("s")!="")
    {
      $sql .= " AND dat_bez LIKE '%". $myRequest->getSQL("s")."%'";
      $headline .= locale("in titles") . " ". $myRequest->get("s");
      $pagingUrlExt .= "&s=". urlencode($myRequest->get("s"));
    }
    if ($myRequest->get("v")!="")
    {
      $sql .= " AND dat_fullsearch LIKE '%". $myRequest->getSQL("v")."%'";
      $headline .= locale("fulltext") . " ". $myRequest->get("v");
      $pagingUrlExt .= "&s=". urlencode($myRequest->get("v"));
    }
    if ($myRequest->getI("i")!=0)
    {
      $sql .= " AND dat_id = ". $myRequest->getI("i");
      $headline .= locale("for ID") . " ". $myRequest->getI("i");
      $pagingUrlExt .= "&s=". urlencode($myRequest->get("i"));
    }

		?>

		<table width="680" border="0" cellpadding="0" cellspacing="0">
      	<tr>
        	<td class="windowTabTypeOnly"><strong><?php echo $myPT->codeH($headline) ?></strong></td>
        	<td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      	</tr>
    	</table>
		<?php







		// count records

		$sql1 = "SELECT COUNT(*) AS C " . $sql;
		$rs = $myDB->query($sql1);
		$row = mysql_fetch_array($rs);
		$anzahl = $row["C"];

		//echo "DEBUG: " . $sql;

		// determine page

		$p = $myRequest->getI("p");
		if ($p<1){$p=1;}
		$sql2 = "SELECT * " . $sql;
		$start = ($p-1)*(10);
		$sql2 .=" LIMIT ". $start . ",10";

		$rs = $myDB->query($sql2);

		$this->displayContentRecords($rs,true,$display_content_type);

		$url = "backend.php?page=Editor,Content,search&con_id=".$this->con_id."&r=".$this->category."&b=0&c=".$order.$pagingUrlExt."&p=";
		echo $this->renderPageBrowser($p,$anzahl,$url);

		return $myPT->stopBuffer();

  }


  function buildCO($dat_id,$block_nr)
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    if ($dat_id==0)
    {
      $this->noaccess();
    }

    $sql = "SELECT con_id FROM content_data WHERE dat_id = " . $dat_id;
    $rs = $myDB->query($sql);
    if (mysql_num_rows($rs)==0)
    {
      $this->noaccess();
    }

    $row = mysql_fetch_array($rs);

    $this->checkRight("con_".$row["con_id"],true);


    $cname = "PhenotypeContent_" . $row["con_id"];
    $myCO = new $cname;

    $sql = "SELECT * FROM content_data WHERE dat_id = " . $dat_id;
    $filter = $myCO->getAccessFilter();
    if ($filter !="")
    {
      $sql .= " AND " . $filter;
    }
    $rs = $myDB->query($sql);
    if (mysql_num_rows($rs)==0)
    {
      $this->noaccess();
    }

    $row = mysql_fetch_array($rs);
    $myCO->init($row,$block_nr);
    return ($myCO);
  }

  /**
	 * rendering edit mask of a contentobject
	 *
	 * @return html
	 */

  function renderEdit()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $dat_id = $myRequest->getI("id");
    $block_nr = $myRequest->getI("b");

    $myCO = $this->buildCO($dat_id,$block_nr);

    $this->con_id = $myCO->content_type;
    $sql_con = "SELECT con_rubrik FROM content WHERE con_id = " .$myCO->content_type;
    $rs = $myDB->query($sql_con);
    $row = mysql_fetch_array($rs);
    $this->category=$row["con_rubrik"];



    $myPT->startBuffer();


    $this->displayIDLineContentRecord($myCO);


    $conflict = "";
    $sql = "SELECT usr_id,dat_date FROM content_data WHERE dat_id=".$dat_id;
    $rs_conflict= $myDB->query($sql);
    $row_conflict = mysql_fetch_array($rs_conflict);
    $datum = $row_conflict["dat_date"];
    $minuten = (int)((time()-$datum)/60);
    if ($minuten <10 AND $row_conflict["usr_id"]!=$mySUser->id)
    {
      $zustand="";
      $myUser = new PhenotypeUser($row_conflict["usr_id"]);
      switch ($minuten)
      {
        case 0:
          $conflict = locale("msg_recordchange_0",array($myUser->getName()));
          break;
        case 1:
          $conflict = locale("msg_recordchange_1",array($myUser->getName()));
          break;
        default:
          $conflict = locale("msg_recordchange_n",array($myUser->getName(),$minuten));
          break;

      }
    }

    if ($conflict)
    {
      $this->displayAlert($conflict);
    }

    $this->displayEAIF();

    $this->displayGlueTicketsForContentRecordset($myCO->id);



    $this->tab_new();
    if (isset($myCO->_blocks))
    {
      $n=0;
      $aktiv = "";
      foreach ($myCO->_blocks as $k)
      {
        if (!(isset($myCO->_showblocks)) OR  $myCO->_showblocks[$n]==1)
        {
          $url = "backend.php?page=Editor,Content,edit&id=" .$myCO->id . "&uid=". $myCO->uid. "&b=" . $n;
          $this->tab_addEntry($myCO->_blocks[$n],$url,$myCO->_icons[$n]);
          if ($n==$block_nr){$aktiv=$myCO->_blocks[$n];}
        }
        $n++;
      }
      $this->tab_draw($aktiv);
    }
    else
    {
      $url = "backend.php?page=Editor,Content,edit&id=" .$myCO->id . "&uid=". $myCO->uid."&b=0";
      $this->tab_addEntry(localeH("Properties"),$url,"b_konfig.gif");
      $this->tab_draw(localeH("Properties"));
    }
    $this->workarea_start_draw();
		?>
 		<form action="backend.php" method="post" enctype="multipart/form-data" name="editform">
 		<input type="hidden" name="page" value="Editor,Content,update">
		<input type="hidden" name="id" value="<?php echo $dat_id ?>">
		<input type="hidden" name="b" value="<?php echo $block_nr ?>">
		<?php
		$_jsarray = $myCO->edit();
		$this->_jsarray = $_jsarray;

		// Status
		$this->workarea_whiteline();
		if ($mySUser->checkRight("superuser"))
		{
		  $this->workarea_row_draw("UID",$myCO->uid."<br>");
		}
		if ($myCO->showstatus==1)
		{
		  $myPT->startBuffer();

		  if ($myCO->nostatus==0)
		  {
     		?>
	 		<input name="status" id="status" type="checkbox" value="1" <?php if ($myCO->row["dat_status"]=="1") echo"checked"; ?>> <label for="status"><?php echo localeH("online");?></label>. 
     		<?php
		  }
		  $myAdm->displayCreationStatus($myCO->row["usr_id_creator"],$myCO->row["dat_creationdate"]);
		  echo "<br>";
		  $myAdm->displayChangeStatus($myCO->row["usr_id"],$myCO->row["dat_date"]);
		  $html = $myPT->stopBuffer() . "<br><br>";
		  $this->workarea_row_draw(locale("State"),$html);
		}

		// Kompletter Status, aber keine Ver�nderungsm�glichkeit
		if ($myCO->showstatus==2 AND $myCO->nostatus==0)
		{
		  $myPT->startBuffer();
		  if ($myCO->row["dat_status"]=="1") {echo localeH("online"). " ";}else{echo localeH("offline")." ";}
		  $myAdm->displayCreationStatus($myCO->row["usr_id_creator"],$myCO->row["dat_creationdate"]);
		  echo "<br>";
		  $myAdm->displayChangeStatus($myCO->row["usr_id"],$myCO->row["dat_date"]);
		  $html = $myPT->stopBuffer() . "<br><br>";
		  $this->workarea_row_draw(locale("State"),$html);
		}
		?>
 	   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite" tabindex="99">
            <input type="image" src="transparentpixel.gif" width="1" height="1" onclick="return false;"/>
            <?php
            $sql = "SELECT con_loeschen FROM content WHERE con_id=".$myCO->content_type;
            $rs= $myDB->query($sql);
            $row = mysql_fetch_array($rs);
            if ($row["con_loeschen"]==1)
			{?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="<?php echo localeH("Delete");?>" onclick="javascript:return confirm('<?php echo localeH("Really delete this record?");?>')"><?php } ?><input name="save" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Save");?>" tabindex="1" accesskey="s">
		<?
		if($myPT->getPref("edit_content.show_PublishButton") == "1") {
		?>
		<input name="publish" type="button" class="buttonWhite" style="width:102px"value="<?php echo localeH("Publish");?>" tabindex="1" accesskey="s">
		<?
		}
		?>
		&nbsp;&nbsp;</td>
          </tr>
    	</table>
		 <?php
		 $this->workarea_stop_draw();
	 	?>
		</form>	 
		<?php
		return $myPT->stopBuffer();
  }

  function update()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;
    global $myLog;



    $dat_id = $myRequest->getI("id");
    $block_nr = $myRequest->getI("b");

    $myCO = $this->buildCO($dat_id,$block_nr);


    $_params = Array();
    $_params["id"]=$myCO->id;
    $_params["uid"]=$myCO->uid;
    $_params["con_id"]=$myCO->content_type;
    $_params["b"]=$block_nr;
    $_params["c"]="akt";
    $_params["p"]=$myRequest->getI("p"); // needed for form_pager

    $this->_params = $_params;


    if ($myRequest->check("delete"))
    {
      $myCO->delete();
      $this->_params["info"]=locale("Record deleted.");
      $this->gotoPage("Editor","Content","select",$this->_params);
    }

    if ($myCO->nostatus==0)
    {
      if (isset($_REQUEST["status"]))
      {
        $myCO->status = 1;
      }
      else
      {
        $myCO->status = 0;
      }
    }

    $myCO->update();
    $myCO->store();
    // :TODO: localize (no token)
    $myLog->log("Datensatz " . $myCO->id . " (Content-Type " . $myCO->content_type .") bearbeitet.",PT_LOGFACILITY_SYS);

    // Update der Userinfo + Status

    $mySQL = new SQLBuilder();
    $mySQL->addField("dat_date",time(),DB_NUMBER);
    $mySQL->addField("usr_id",$mySUser->id);

    $sql = $mySQL->update("content_data","dat_id=".$dat_id);
    $myDB->query($sql);


    // Snapshot
    if ($myPT->getIPref("edit_content.build_snapshot")==1)
    {
      $myCO->snapshot($mySUser->id);
    }

    $action = "select";
    $feedback=1;

    if (isset($myCO->_blocks) OR ($myCO->getErrorText()!="" OR $myCO->getInfoText() !="" OR $myCO->getAlertText() !=""))
    {
      $action="edit";
    }

    // ToDO: Save old style links ...
    if ($myCO->update_url !=""){$url=$myCO->update_url;}

    if ($myCO->getErrorText()!="")
    {
      $this->_params["error"]=$myCO->getErrorText();
      $feedback=0;
    }

    if ($myCO->getInfoText()!="")
    {
      $this->_params["info"]=$myCO->getInfoText();
      $feedback=0;
    }

    if ($myCO->getAlertText()!="")
    {
      $this->_params["alert"]=$myCO->getAlertText();
      $feedback=0;
    }

    if ($myRequest->check("editbuffer"))
    {
      $this->_params["editbuffer"]=1;
      $feedback=0;
    }

    if ($action=="edit")
    {
      if ($feedback==1 AND $myCO->showfeedback==1)
      {
        $this->_params["feedback"]=locale("Changes saved.");
      }
      $this->gotoPage("Editor","Content","edit",$this->_params);
    }
    else
    {
      $this->gotoPage("Editor","Content","select",$this->_params);
    }
  }


  function delete()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;



    $dat_id = $myRequest->getI("id");
    $block_nr = $myRequest->getI("b");

    $myCO = $this->buildCO($dat_id,$block_nr);
    $myCO->delete();

    $_params = Array();
    $_params["con_id"]=$myCO->content_type;
    $_params["c"]="akt";
    $this->_params["info"]=locale("Record deleted.");
    $this->_params = $_params;


    $this->gotoPage("Editor","Content","select",$this->_params);
  }

  function debug()
  {
    global $myRequest;
    global $myDB;

    $this->checkRight("superuser",true);
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

		<html>
		<head>
			<title>Debug property view - record nrr. <?php echo $myRequest->getI("id") ?></title>
		</head>

		<body>
		<pre>
		<?php
		$sql = "SELECT dat_props FROM content_data WHERE dat_id = " . $myRequest->getI("id");
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)==0)
		{
		  echo("<br><br>".localeH("Record not found."));
		}
		else
		{
		  $row = mysql_fetch_array($rs);


		  $_props = Array();
		  if ($row["dat_props"] != "")
		  {
		    $_props = unserialize($row["dat_props"]);
		  }
		  ksort($_props);
		  print_r($_props);

		}
		?>
		</pre>
		</body>
		</html>
		<?php
		exit();
  }

  function copy()
  {
    global $myRequest;
    $myCO_Source = $this->buildCO($myRequest->getI("id"),0);

    $cname = "PhenotypeContent_" . $myCO_Source->content_type;
    $myCO_Target = new $cname;
    $id = $myCO_Target->addNew();
    $myCO_Target->_props = $myCO_Source->_props;
    $myCO_Target->set("bez",localeH("Copy of")." " . $myCO_Source->get("bez"));
    $myCO_Target->set("dat_id_copyparent",$myCO_Source->id);
    $myCO_Target->store();

    $_params = Array();
    $_params["id"]=$myCO_Target->id;
    $_params["uid"]=$myCO_Target->uid;
    $_params["con_id"]=$myCO->content_type;
    $_params["b"]=0;
    $_params["c"]="akt";
    $_params["feedback"] =localeH("Record copied.");

    $this->_params = $_params;

    $this->gotoPage("Editor","Content","edit",$this->_params);
  }


  function insert()
  {
    global $myRequest;

    $con_id = $myRequest->getI("con_id");

    $this->checkRight("con_".$con_id,true);

    $cname = "PhenotypeContent_" . $con_id;
    $myCO = new $cname;
    $id = $myCO->addNew();

    $_params = Array();
    $_params["id"]=$myCO->id;
    $_params["uid"]=$myCO->uid;
    $_params["con_id"]=$myCO->content_type;
    $_params["b"]=0;
    $_params["c"]="akt";

    $this->_params = $_params;

    $this->gotoPage("Editor","Content","edit",$this->_params);
  }


  function renderRollback()
  {
    global $myDB;
    global $myAdm;
    global $mySUser;
    global $myRequest;
    global $myPT;

    $dat_id = $myRequest->getI("id");
    $this->checkRight("superuser",true);

    $myCO = $this->buildCO($dat_id,0);

    $this->con_id = $myCO->content_type;
    $sql_con = "SELECT con_rubrik FROM content WHERE con_id = " .$myCO->content_type;
    $rs = $myDB->query($sql_con);
    $row = mysql_fetch_array($rs);
    $this->category=$row["con_rubrik"];

    $myPT->startBuffer();

    $this->displayIDLineContentRecord($myCO);


    $this->tab_new();

    $url = "backend.php?page=Editor,Content,edit&id=" .$myCO->id . "&uid=". $myCO->uid."&b=0";
    $this->tab_addEntry("Bearbeiten",$url,"b_edit_b.gif");

    $url = "backend.php?page=Editor,Content,rollback&id=" .$myCO->id . "&uid=". $myCO->uid."&b=0";
    $this->tab_addEntry(locale("Rollback"),$url,"b_rollback.gif");
    $this->tab_draw(locale("Rollback"));

    $this->listSnapshots("CO",$dat_id,"Editor","Content");

    return $myPT->stopBuffer();
  }

  function installSnapshot($sna_id,$sna_type)
  {
    global $myDB;

    $_params = Array();

    $myCO = parent::installSnapshot($sna_id,$sna_type);
    if ($myCO)
    {
      $_params["id"]=$myCO->id;
      $_params["uid"]=$myCO->uid;
      $_params["con_id"]=$myCO->content_type;
      $_params["b"]=0;
      $_params["c"]="akt";
      $_params["info"]=locale("snapshot installed");
      $this->_params = $_params;
      $this->gotoPage("Editor","Content","edit",$this->_params);
    }
    else
    {
      $_params["error"]=locale("Error during rollback.");
      $this->gotoPage("Editor","Content","",$this->_params);
    }





  }


  function execute_form_ajax()
  {
    global $myRequest;
    global $myPT;

    $con_id = $myRequest->getI("con_id");
    $cname = "PhenotypeContent_".$con_id;
    $dat_id = $myRequest->getI("dat_id");
    $myCO = new $cname($dat_id);
    $token = $myRequest->get("token");
    $step = $myRequest->get("step");
    $usr_id = $myRequest->getI("usr_id");
    $myPT->startBuffer();
    $step = $myCO->execute_form_ajax($token,$step,$usr_id);
    $html = $myPT->stopBuffer();
    ?><!--<?php echo $step ?>###--><?php
    echo utf8_encode($html);
    exit();
  }


  function displayDateSelector($date,$ename)
  {


    $_month = Array(localeH("December"),localeH("January"),localeH("February"),localeH("March"),localeH("April"),localeH("May"),localeH("June"),localeH("July"),localeH("August"),localeH("September"),localeH("October"),localeH("November"),localeH("December"),localeH("January"));

    $start = @mktime (0,0,0,date("m",$date),1,date("Y",$date));



    if ($start == -1 OR $date == -1)
    {
      $date = time();
      $start = @mktime (0,0,0,date("m",$date),1,date("Y",$date));
    }
    $highlight = @mktime(0,0,0, date("m",$date),date("d",$date),date("Y",$date));

		?>
		<table width="205" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td class="windowMenu">
		<table width="199" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" class="windowHeaderGrey2"><strong><?php echo $_month[date('n',$highlight)] ?> <?php echo date('Y',$highlight) ?></strong></td>
  </tr>
</table>
<table width="199" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="windowBlank"><table width="100%" border="0" cellpadding="0" cellspacing="3">
        <tr>
          <td class="tableKalenderTage"><?php echo localeH("day_short_monday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_tuesday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_wednesday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_thursday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_friday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_saturday");?></td>
          <td class="tableKalenderTage"><?php echo localeH("day_short_sunday");?></td>
        </tr>
   	    <?php 
   	    $wochentag = date("w",$start);
   	    if ($wochentag==0){$wochentag=7;}
   	    $montag = mktime(0,0,0, date("m",$start),date("d",$start)-$wochentag+1,date("Y",$start));
   	    $datum = $montag;
   	    $weiter=1;
   	    $j=0;
   	    while ($weiter==1){
        ?> 
        <tr> 
        <?php 
        $j++;
        for ($i=1;$i<=7;$i++)
        {
          $class="tableKalenderWerktag";
          $class2="";
          if ($i>=6){$class='tableKalenderWochenende';}
          if ($datum==$highlight){$class2='tableKalenderAktiv';$class="";}
          $tag = date('j',$datum);
          if (date("m",$datum)!=date("m",$highlight)){$tag="&nbsp;";$class="tableKalenderLeer";}
          if ($class!=""){$class='class="'.$class.'"';}
          if ($class2!=""){$class2='class="'.$class2.'"';}
        ?> 
        <td <?php echo $class ?>><a href="javascript:setDate_<?php echo $ename ?>('<?php echo date('d.m.Y',$datum) ?>')" <?php echo $class2 ?>><?php echo $tag ?></a></td> 
        <?php 
        $datum = mktime(0,0,0, date("m",$datum),date("j",$datum)+1,date("Y",$datum));
        }
        ?> 
        </tr> 
        <?php 
        if (date("m",$datum)!=date("m",$highlight)){$weiter=0;}
   	    }
   	    if ($j==4){?><tr><td class="tableKalenderWhite" colspan="7">&nbsp;</td></tr><?php }
   	    if ($j<=5){?><tr><td class="tableKalenderWhite" colspan="7">&nbsp;</td></tr><?php }
        ?> 
      </table>
    </td>
  </tr>
</table>
<?php
$vormonat = mktime(0,0,0, date("m",$highlight)-1,date("j",$highlight),date("Y",$highlight));
if (date("m",$vormonat)==date("m",$highlight)){$vormonat = mktime(0,0,0, date("m",$highlight)-1,1,date("Y",$highlight));}
$nachmonat = mktime(0,0,0, date("m",$highlight)+1,date("j",$highlight),date("Y",$highlight));
if (date("m",$nachmonat)==date("m",$highlight)){$nachmonat =mktime(0,0,0, date("m",$highlight)+1,1,date("Y",$highlight));}

?>
<table width="199" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" class="windowFooterWhite"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><a href="javascript:ajax_<?php echo $ename ?>_doit(<?php echo $vormonat ?>,1)" class="bausteineLink"> <img src="img/b_zurueck_tr.gif" alt="<?php echo localeH("last month");?>" width="18" height="18" border="0" align="absmiddle"> <?php echo $_month[date('n',$vormonat)] ?></a></td>
      </tr>
    </table></td>
    <td width="50%" class="windowFooterWhite"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="right"><a href="javascript:ajax_<?php echo $ename ?>_doit(<?php echo $nachmonat ?>,1)" class="bausteineLink"><?php echo $_month[date('n',$nachmonat)] ?> <img src="img/b_vor_tr.gif" alt="<?php echo localeH("next month");?>" width="18" height="18" border="0" align="absmiddle"></a></td>
      </tr>
    </table></td>
  </tr>
</table>
		</td>
		<td width="2" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    <tr>
          <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="2" ></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="5" ></td>
        </tr>
      </table>
		

		<?php
  }
}
?>