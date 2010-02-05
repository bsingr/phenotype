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
// www.phenotype-cms.com - offical homepage
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
	public $bak_id = "Editor_Content";
  	public $tmxfile = "Editor_Content";


  // Following variables determines the focus of the content browser

  public $category;
  public $con_id;
  public $pagenr =1;
  public $itemcount = 10;

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

	if ($myRequest->check("p")){$this->pagenr=$myRequest->getI("p");}
	if ($this->pagenr<0){$this->pagenr=1;}
	if ($myRequest->check("a"))
	{
		$itemcount = $myRequest->getI("a");
		$itemcount = (int)($itemcount/5)*5;
		if ($itemcount<10){$itemcount=10;}
		$this->itemcount=$itemcount;
	}

    switch ($action)
    {
      case "showPreview": // Iframe call
        $this->showPreview();
        break;
      case "preview": // Preview button pressed
		$this->update();
		break;
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
			case "lightbox"; // Wird per Ajax aufgerufen

				$this->displayLightBox(true);

			return;
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
      case "autocomplete":
      	$this->displayAutoCompleteMatches();
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
    if ($mySUser->checkRight("elm_lightbox"))
	{
		$this->displayLightBox();
	}
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

	function displayLightBox($ajax=false)
	{
		global  $myPT;
		global $mySUser;
		global $myRequest;
		global $myLog;
		// No lightbox on overview page
		if ($this->con_id==0 AND $ajax==false)
		{
			return;
		}
		$displayLightbox=false;
		$myPT->startBuffer();
		?>
		<div id="lightbox" rel="<?php echo $this->con_id?>">
		<?php
		$reload=false;
		$myDO = new PhenotypeSystemDataObject("ContentLightbox",array("usr_id"=>$mySUser->id,"con_id"=>$this->con_id));
		$_objects = $myDO->get("objects");
		if ($myRequest->check("dat_id") AND $ajax==true)
		{
			$dat_id = $myRequest->getA("dat_id","-0123456789,");
			$cname = "PhenotypeContent_".$this->con_id;
			// Special Cases ...
			switch ($dat_id)
			{
				case -1:
					$_objects=Array();
					break;
				case -99;
				foreach ($_objects AS $dat_id)
				{
					$myCO = new $cname($dat_id);
					if ($myCO->loaded)
					{
						$myCO->delete();
					}
				}
				$reload=true;
				$_objects=Array();
				break;
				case -2://online
				foreach ($_objects AS $dat_id)
				{
					$myCO = new $cname($dat_id);
					if ($myCO->loaded)
					{
						$myCO->setStatus(true);
						$myCO->changeUserStatus($mySUser->id);
						$myCO->store();
					}
				}
				$reload=true;
				break;
				case -3://offline
				foreach ($_objects AS $dat_id)
				{
					$myCO = new $cname($dat_id);
					if ($myCO->loaded)
					{
						$myCO->setStatus(false);
						$myCO->changeUserStatus($mySUser->id);
						$myCO->store();
					}
				}
				$reload=true;
				break;
				default:
					// med_id can be one value or a comma separated list
					$_dat_id = mb_split(",",$dat_id);
					foreach ($_dat_id AS $dat_id)
					{
						if (in_array($dat_id,$_objects))
						{
							unset($_objects[$dat_id]);
						}
						else
						{
							$_objects[$dat_id]=$dat_id;
						}
					}
					break;
			}
			$myDO->set("objects",$_objects);
			$myDO->store();
		}
		if (count($_objects)!=0)
		{
			$this->tab_new();
			$url = "backend.php?page=Editor,Content,select&con_id=".$this->con_id."&c=akt";
			$this->tab_addEntry(locale("Lightbox"),$url,"b_pinadd.gif");
			$this->tab_draw(locale("Lightbox"),$x=260,1);
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
      	<tr>
        	<td class="windowMenu">
			<table  border="0" cellspacing="8" cellpadding="0">
				<?php
				foreach ($_objects AS $object)
				{
					$cname = "PhenotypeContent_".$this->con_id;
					$myCO = new $cname($object);
					$url = "backend.php?page=Editor,Content,edit";
					if ($myCO->loaded==1)
					{
						$displayLightbox=true;
						$myImg = new PhenotypeImage($myCO->img_id);
					?>
					<tr>
	    	        	<td width="10"><input type="checkbox" checked="checked" onclick="lightbox_switch(<?php echo $myCO->id ?>,<?php echo $myCO->content_type?>,1)" rel="<?php echo $myCO->id ?>"></td>
	    	        	<td width="40">
	    	        	<a href="<?php echo $url ?>&amp;id=<?php echo $myCO->id ?>&amp;uid=<?php echo $myCO->uid?>"><?php echo $myImg->display_thumbX(45,$myCO->bez); ?></a></td>
	    	        	<td ><a href="<?php echo $url ?>&amp;id=<?php echo $myCO->id ?>&amp;uid=<?php echo $myCO->uid?>"><?php echo $myPT->codeH($myPT->cutString($myCO->bez,23)) ?></a></td>
	          	  	</tr>
	          	  	<?php
					}
				}
				?>
				<tr>
				<td colspan="3">
				<form action="#" id="lightboxselect">
				<select class="listmenu" style="float:left" name="select">
				<option>
				<option value="1"><?php echo $myPT->localeH('Clear lightbox')?></option>
				<option value="2"><?php echo $myPT->localeH('Delete records permanently')?></option>
				<option value="3"><?php echo $myPT->localeH('Set status online')?></option>
				<option value="4"><?php echo $myPT->localeH('Set status offline')?></option>
				</select>
				<div style="width:30px;float:left;padding-left:5px"><a id="btn_lightbox_content" class="tabmenuType" href="#"><?php echo $myPT->localeH('Go!')?></a></div>
				</form>
				</td>
				</tr>
        	</table>
			</td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
    	  </tr>
        <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
    	</table>
    	<?php
		}
    	?>
    	</div>
		<?php
		$html= $myPT->stopBuffer();
		if ($displayLightbox==true)
		{
			echo $html;
		}
		else
		{
			echo '<div id="lightbox" rel="'.$this->con_id.'"/>';
		}
		if ($reload==true)
		{
		?>
		<div id="redirect" style="display:none"><?=ADMINFULLURL?>backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id."&c=akt&r=".rand(100000000,999999999)?></div>
		<?
		}
	}
	function displayJS_Lightbox()
	{
		global $myPT;
		?>
		<script type="text/javascript">
		function lightbox_checkup()
		{
			$('#content input:checkbox:checked').each(function()
			{
			 	$(this).removeAttr('checked');
			 	$('#lightbox input:checkbox:checked').each(function()
			 	{
			 		var nr = $(this).attr("rel");
			 		$('#lb'+nr).attr('checked','checked');
			 		//console.log(nr);
			 	});
			});
		}
		function lightbox_switch(dat_id,con_id,check)
		{
			if (dat_id==-99)
			{
				rc = confirm ('<?php echo locale('Really delete this records permanently');?>');
				if (rc==false)
				{
					return;
				}
			}
			var ajax = new sack();
			ajax.resetData();
			ajax.requestFile = "backend.php";
			ajax.method = "POST";
			ajax.element = 'lightbox';
			ajax.setVar("page","Editor,Content,lightbox");
			ajax.setVar("dat_id",dat_id);
			ajax.setVar("con_id",con_id);
			// following variables are necessary for correct edit links...
			/*ajax.setVar("grp_id",<?php echo $this->grp_id ?>);
			ajax.setVar("folder","<?php echo $myPT->codeH($this->folder) ?>");
			ajax.setVar("type",<?php echo $this->type ?>);
			ajax.setVar("sortorder",<?php echo $this->sortorder ?>);
			ajax.setVar("p",<?php echo $this->pagenr ?>);
			ajax.setVar("a",<?php echo $this->itemcount ?>);
			*/
			//ajax.onLoading = whenLoading;
			ajax.onCompletion = function()
			{
				$('#redirect').each(function()
				{
					document.location.href = ($(this).text());
					return;
				});
				$('#btn_lightbox_content').click(function()
				{
					var option = $('#lightboxselect select[name=select]').val();
					var con_id = $('#lightbox').attr('rel');
					if (option==1) // clear lightbox
					{
						lightbox_switch(-1,con_id);
					}
					if (option==2) // delete content records
					{
						lightbox_switch(-99,con_id);
					}
					if (option==3) // status online
					{
						lightbox_switch(-2,con_id);
					}
					if (option==4) // status offline
					{
						lightbox_switch(-3,con_id);
					}
					return false;
				});
				lightbox_checkup();
				//if (check==1) // remove check from all elements, then activate them one by one out of the lightbox
				//{
				/*
				var obj = document.getElementById('lb'+med_id);
				if (obj!=null)
				{
					obj.checked=!obj.checked;
				}*/
			}
			//ajax.onInteractive = whenInteractive;
			//ajax.onCompletion = whenCompleted;
			ajax.runAJAX();
		}
		</script>
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
		$this->displayJS_Lightbox();
    $headline = locale("Overview");
    if ($this->category!=""){$headline=$this->category;}
    $this->displayHeadline($headline,"http://www.phenotype-cms.de/docs.php?v=23&con_id=2");



    if ($this->category=="")
    {
      $sql = "SELECT * FROM content ORDER BY con_bez";
    }
    else
    {
      $sql = "SELECT * FROM content WHERE con_rubrik='" .mysql_real_escape_string($this->category)."' ORDER BY con_bez";
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

		$myDO = new PhenotypeSystemDataObject("ContentLightbox",array("usr_id"=>$mySUser->id,"con_id"=>$this->con_id));
		$_objects = $myDO->get("objects");
    $myPT->startBuffer();

		$this->displayJS_Lightbox();
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
    $category = $myRequest->get("r");

		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td class="windowTabTypeOnly"><table border="0" cellpadding="0" cellspacing="1">
            <tr>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=akt" class="tabmenuType<?php if($order=="akt"){echo"Active";} ?>"><?php echo localeH("Current");?></a></td>
				<?php
				// Individuelle Tabs
				foreach ($myCO->_extratabs AS $k => $v)
				{
				  $titel = $v[0];
				?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=etab_<?php echo $k ?>" class="tabmenuType<?php if($order=="etab_".$k){echo"Active";} ?>"><?php echo $titel ?></a></td>
				<?php
				}
				if ($myCO->tab_az)
				{
				?>
			  	<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=num" class="tabmenuType<?php if($order=="num"){echo"Active";} ?>">0-9</a></td>
              		<?php
              		for ($i=1;$i<=26;$i++)
              		{
              		  $c = chr(64+$i);
			  		?>
			  		<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=<?php echo $c ?>" class="tabmenuType<?php if($order==$c){echo"Active";} ?>"><?php echo $c ?></a></td>
				  	<?php
              		}
				}
				if ($myCO->tab_shortaz)
				{
				  $_az = Array("ABC","DEF","GHIJ","KLMN","OPQR","STU","VWXYZ");
		  			?>
		  	 		<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=num" class="tabmenuType<?php if($order=="num"){echo"Active";} ?>">0-9</a></td>
       	  			<?php
       	  			foreach ($_az AS $k)
       	  			{
		  				?>
		  				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=<?php echo mb_strtolower($k) ?>" class="tabmenuType<?php if($order==mb_strtolower($k)){echo"Active";} ?>"><?php echo $k ?></a></td>
				  		<?php
       	  			}
				}
				if ($myCO->tab_alle)
				{
		      	?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&con_id=<?php echo $this->con_id ?>&r=<?php echo codeH($category)?>&c=alle" class="tabmenuType<?php if($order=="alle"){echo"Active";} ?>"><?php echo locale("All") ?></a></td><?php
				}
				if ($myCO->tab_id)
				{
			  	?>
				<td align="center"><a href="backend.php?page=Editor,Content,select&r=<?php echo codeH($category)?>&con_id=<?php echo $this->con_id ?>&c=id" class="tabmenuType<?php if($order=="id"){echo"Active";} ?>">ID</a></td><?php
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
		    $sql .= " AND dat_id = ". (int)$_REQUEST["i"];
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
		    $sql .= " AND dat_bez LIKE '". mysql_real_escape_string($order) ."%'";
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
		if (mb_substr($order,0,5)=="etab_")
		{
		  $sql = "FROM content_data WHERE con_id = " . $this->con_id;
		  if ($filter !="")
		  {
		    $sql .= " AND " . $filter;
		  }
		  $etab_key = mb_substr($order,5);

		  if (!is_array($myCO->_extratabs) OR !array_key_exists($etab_key,$myCO->_extratabs))
		  {
		  	$this->noAccess();
		  }
		  $extratab  = $myCO->_extratabs[$etab_key];
		  if (!is_array($extratab) OR $extratab[1]=="")
		  {
		  	throw new Exception ("Wrong extratab configuration.");
		  }
		  $sql .= " AND " .$extratab[1];

		}


		$sql = "SELECT * " . $sql;
		$rs = $myDB->query($sql);

		// Seite und Anzahl bestimmen
		$anzahl = mysql_num_rows($rs);

		$p = $this->pagenr;

		// Wechsel der Anzahlsanzeige ??
		if ($myRequest->check("a2") AND ($this->itemcount !=$myRequest->getI("a2")))
		{
			$n= (($p-1)*$myRequest->getI("a2"))+1;
			$p= ceil($n/$this->itemcount);
		}

		// Seite gr��er als es die Anzahl erlaubt?
		$max=ceil($anzahl/$this->itemcount);
		if ($max==0){$max=1;}
		if ($p>$max){$p=$max;}

		$start = ($p-1)*($_REQUEST["a"]);
		$sql .=" LIMIT ". $start . "," . $this->itemcount;
		/*
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowHeaderGrey2">
			<table border="0" cellspacing="0" cellpadding="0">
	            <tr>
	              <td class="padding10"><form action="backend.php" method="post" name="formsort">
	              <input type="hidden" name="page" value="Editor,Content,select">
			      <input type="hidden" name="con_id" value="<?php echo $this->con_id ?>">
				  <input type="hidden" name="r" value="<?php echo $this->category ?>">
				  <input type="hidden" name="c" value="<?php echo $order ?>">
			      <input type="hidden" name="p" value="<?php echo $p ?>">
				  <input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
	              <td class="padding10"><select name="a" class="listmenu" onchange="document.forms.formsort.submit();">
				    <?php for ($i=1;$i<=10;$i++){ ?>
	                <option value="<?php echo ($i*10) ?>" <?php if ($this->itemcount==($i*10)){echo "selected";} ?>><?php echo ($i*10) ?> <?php echo localeH("objects/page");?></option>
					<?php } ?>
					<input type="hidden" name="a2" value="<?php echo $this->itemcount ?>">
					</select></form></td>
	            </tr>
	        </table></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	    </table>
	    <?php
	    */

		$url = "backend.php?page=Editor,Content,select&con_id=".$this->con_id."&r=".$this->category."&b=0&c=".$order."&a=".$this->itemcount."&p=";
		$selectallbutton=false;
	    if ($mySUser->checkRight("elm_lightbox"))
		{
			$selectallbutton=true;
		}
//		echo $this->renderPageBrowser($p,$anzahl,$url,$this->itemcount,false,$selectallbutton);

		$rs = $myDB->query($sql);
		$this->displayContentRecords($rs,true,false,$_objects);
		//$this->overview_content_draw($sql2,$this->con_id,2);

		echo $this->renderPageBrowser($p,$anzahl,$url,10,false,$selectallbutton);

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

		$this->displayJS_Lightbox();
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
      $pagingUrlExt .= "&v=". urlencode($myRequest->get("v"));
    }
    if ($myRequest->getI("i")!=0)
    {
      $sql .= " AND dat_id = ". $myRequest->getI("i");
      $headline .= locale("for ID") . " ". $myRequest->getI("i");
      $pagingUrlExt .= "&i=". urlencode($myRequest->get("i"));
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

    // Extract User Info to merge with editbuffer, if necessary
	$row_userinfo = Array("usr_id_creator"=>$row["usr_id_creator"],"usr_id"=>$row["usr_id"],"dat_creationdate"=>$row["dat_creationdate"],"dat_date"=>$row["dat_date"]);

		//preview mode
    if ($myCO->previewmode==true AND $row["dat_altered"]==1)
    {
    	$sql = "SELECT * FROM content_data_editbuffer WHERE dat_id=".$dat_id . " AND usr_id=" . (int)$mySUser->id;
    	$rs = $myDB->query($sql);

    	if (mysql_num_rows($rs)==1)
    	{
    		$row = mysql_fetch_array($rs);
				$row["dat_altered"]=1;
			$row = array_merge($row,$row_userinfo);
    	}
    }

    //publish mode
		if ($myCO->publishmode==true AND $row["dat_altered"]==1)
    {
    	$sql = "SELECT * FROM content_data_editbuffer WHERE dat_id=".$dat_id . " AND usr_id=0";
    	$rs = $myDB->query($sql);

    	if (mysql_num_rows($rs)==1)
    	{
    		$row = mysql_fetch_array($rs);
				$row["dat_altered"]=1;
			$row = array_merge($row,$row_userinfo);
    	}
    }

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
		$this->displayJS_Lightbox();

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
		<?php
			if($myCO->previewmode == true AND $myRequest->check("preview"))
			{
			?>
			<script type="text/javascript">
			$(document).ready(function(){
				previewContent("backend.php?page=Editor,Content,showPreview&id=<?=$dat_id?>",<? echo $myPT->getPref("preview_dialog.dialog_width",800) ?>,<?php echo $myPT->getPref("preview_dialog.dialog_height",500)?>, "<?php echo localeH("Preview");?>");
			});
			</script>
			<?
			}
			?>
 	   <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;&nbsp;
		    <?php
		    if($myCO->previewmode == true) {
			?>
			<input name="preview" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Preview");?>" tabindex="1" accesskey="p" onclick="this.form.page.value = 'Editor,Content,preview';">
			<?php
		    }
			?>
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
		if($myCO->publishmode == true) {
		?>
		<input name="publish" type="submit" class="buttonWhite" style="width:102px"value="<?php echo localeH("Publish");?>" tabindex="1" accesskey="p">
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

	/**
  * delete rows in editbuffer table (content_data_editbuffer) method
  *
  * @return null
  */
  function deleteEditbufferTable() {
    global $myDB;
    global $myRequest;

    $dat_id = $myRequest->getI("id");
    $block_nr = $myRequest->getI("b");

    $myCO = $this->buildCO($dat_id,$block_nr);

		$sql = "DELETE FROM content_data_editbuffer WHERE dat_id=".$myCO->id. " AND con_id=".$myCO->content_type;
		$myDB->query($sql);
		$mySQL = new SqlBuilder();
		$mySQL->addField("dat_altered",0);
		$sql = $mySQL->update("content_data", "dat_id=".$myCO->id);
		$myDB->query($sql);

	}

	/**
  * update method
  *
  * @return null
  */
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
    if ($myCO->publishmode==true)
    {
    	if ($myRequest->check("publish"))
    	{
    		$myCO->store();
				$this->deleteEditbufferTable();
    	}
    	else
    	{
				if($myRequest->check("preview")) {
		    	$myCO->store($mySUser->id);
				} else {
		    	$myCO->store(0);
				}
    	}
    }
    else
    {
			if($myRequest->check("preview")) {
				$myCO->store($mySUser->id);
			} else {
				$myCO->store();
				$this->deleteEditbufferTable();
			}
    }
    // :TODO: localize (no token)
    $myLog->log("Datensatz " . $myCO->id . " (Content-Type " . $myCO->content_type .") bearbeitet.",PT_LOGFACILITY_SYS);

    // Update der Userinfo + Status
    if(!$myRequest->check("preview"))
    {
    $mySQL = new SQLBuilder();
    $mySQL->addField("dat_date",time(),DB_NUMBER);
    $mySQL->addField("usr_id",$mySUser->id);

    $sql = $mySQL->update("content_data","dat_id=".$dat_id);
    $myDB->query($sql);
    }

    // Snapshot
    if ($myPT->getIPref("edit_content.build_snapshot")==1)
    {
      $myCO->snapshot($mySUser->id);
    }


		if($myRequest->check("preview") || ($myRequest->check("save") && $myCO->publishmode==true)) {
			if($myRequest->check("preview")) {
					$this->_params["preview"]="1";
			}
			$action = "edit";
		} else {
			$action = "select";
		}

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

	/**
  * debug method show the properties from the content object
  *
  * @return null
  */
  function debug()
  {
    global $myRequest;
    global $myDB;

    $this->checkRight("superuser",true);
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

		<html>
		<head>
			<title>Debug property view - record nr. <?php echo $myRequest->getI("id") ?></title>
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

	/**
  * showPreview method show the preview properties from the content object
  *
  * @return null
  */
  function showPreview()
  {
    global $myRequest;
    global $myDB;
    global $mySUser;

    $dat_id = $myRequest->getI("id");
    $block_nr = $myRequest->getI("b");

    $myCO = $this->buildCO($dat_id,$block_nr);

	$myCO->preview($block_nr);

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

		$this->displayJS_Lightbox();
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


  function displayAutoCompleteMatches()
  {
  	global $myRequest;
  	global $myLog;
  	$myRequest->log();
  	$myPH = new PhenotypeSystemDataObject("ParameterHolder",array("id"=>$myRequest->get("hash")));

  	$con_id = (int)$myPH->get("con_id");
  	$status = null;
  	if ($myPH->get("statuscheck")==true)
  	{
  		$status = true;
  	}
  	$_filter = Array();
  	if ($myPH->getB("use_fulltext")==true)
  	{
  		$_filter[]="(dat_bez LIKE '%".mysql_real_escape_string($myRequest->get("query"))."%' OR dat_fullsearch LIKE '%".mysql_real_escape_string($myRequest->get("query"))."%')";
  	}
  	else
  	{
  		$_filter[]="dat_bez LIKE '%".mysql_real_escape_string($myRequest->get("query"))."%'";
  	}
  	if ($myPH->get("sql_where")!="")
  	{
  		$_filter[]=$myPH->get("sql_where");
  	}

  	$_objects = PhenotypePeer::getRecords($con_id,$status,"dat_bez",$_filter,"0,250");
  	$_suggestions = Array();
  	$_data = Array();
  	foreach ($_objects AS $myCO)
  	{
  		$_suggestions[] = "'".addslashes($myCO->bez)."'";
  		$_data[] = $myCO->id;
  	}
  	?>
	{
	 query:'<?php echo $myRequest->getH("query")?>',
	 suggestions:[<?php echo join(",",$_suggestions);?>],
	 data:[<?php echo join(",",$_data);?>]
	}
  	<?php
  }
}
?>