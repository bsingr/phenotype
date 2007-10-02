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

/**
 * @package phenotype
 * @subpackage backend
 *
 */
class PhenotypeBackend_Editor_Media_Standard extends PhenotypeBackend_Editor
{

	// Following variables determines the focus of the media browser, they should not get lost during
	// the work with the mediabase and are passed with every link. The parameter are named like
	// the variables except for pagenr (p) and itemcount (a).

	public $grp_id = 0;
	public $folder = "";
	public $type = 0;  // 1 = Bilder , 2 = Dokumente
	public $sortorder = 1;
	public $pagenr =1;
	public $itemcount = 10;

	public $_mediagroups = Array();


	function execute($scope,$action)
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;


		$this->checkRight("elm_mediabase",true);

		if ($action=="streamimage")
		{
			$this->streamImage();
		}

		// determine mediagroups of current user
		$sql = "SELECT * FROM mediagroup ORDER BY grp_bez";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			if ($mySUser->hasRight("access_mediagrp_".$row["grp_id"]))
			{
				$this->_mediagroups[$row["grp_id"]]=$row["grp_bez"];
			}
		}
		if (count($this->_mediagroups)==0)
		{
			$this->noaccess();
		}

		// media browsser setup

		if ($myRequest->check("grp_id")){$this->grp_id=$myRequest->getI("grp_id");}
		if ($myRequest->check("folder")){$this->folder=$myRequest->get("folder");}
		if ($myRequest->check("type")){$this->type=$myRequest->getI("type");}
		if ($myRequest->check("sortorder")){$this->sortorder=$myRequest->getI("sortorder");}
		if ($myRequest->check("p")){$this->pagenr=$myRequest->getI("p");}
		if ($this->pagenr<0){$this->pagenr=1;}
		if ($myRequest->check("a"))
		{
			$itemcount = $myRequest->getI("a");
			$itemcount = (int)($itemcount/5)*5;
			if ($itemcount<10){$itemcount=10;}
			$this->itemcount=$itemcount;
		}


		// check, if the selected grp_id is accessible

		if ($this->grp_id!=0)
		{
			if (!array_key_exists($this->grp_id,$this->_mediagroups)){$this->noaccess();}
		}

		// -- media browser setup


		$this->setPageTitle("Phenotype ".$myPT->version. " Media");

		$this->selectMenuItem(2);
		$this->selectLayout(1);

		$body_onload ="";


		$block_nr = $myRequest->getI("b");

		switch ($action)
		{
			case "edit":
				$this->fillContentArea1($this->renderEdit());
				// OID temporarly disabled
				//$body_onload="initoid();";
				break;
			case "update":
				$this->update($block_nr);
				break;
			case "preview":
				$this->fillContentArea1($this->renderPreview());
				$this->selectLayout(2);
				$this->displayPage();
				return;
				break;
			case "search":
				$this->fillContentArea1($this->renderSearch());
				break;
			case "upload":
				$this->fillContentArea1($this->renderUpload());
				break;
			case "upload2":
				$this->uploadMediaObject();
				break;

			case "import":
				if ($block_nr==99)
				{
					Header ("Content-type: application/x-javascript");
					$this->displayImport_Step1RadJS();
					exit();
				}
				// starting text for rad upload plus, shortcut to avoid rewrite hazzle
				if ($block_nr==98)
				{
					echo '<br\>&nbsp;Drag & Drop - Upload';
					exit();
				}
				if ($block_nr==1)
				{
					$this->fillContentArea1($this->renderImport_Step2());
				}
				else
				{
					$this->fillContentArea1($this->renderImport_Step1());
				}
				break;
			case "import2":
				$this->importMediaObjects();
				break;
			case "ddupload":
				$this->ddupload();
				break;

			case "rollback":
				$this->fillContentArea1($this->renderRollback());
				break;
			case "viewsnapshot":
				$this->viewSnapshot($myRequest->getI("id"));
				return;
				break;
			case "installsnapshot":
				$this->installSnapshot($myRequest->getI("id"),$myRequest->get("sna_type"));
				break;


			case "lightbox"; // Wird per Ajax aufgerufen
			$this->displayLightBox();
			return;
			break;
			case "streamPainterPreviewImage";
			$this->streamPainterPreviewImage();
			return;
			case "streamImportPreviewImage";
			$this->streamImportPreviewImage();
			return;
			default:
				$this->fillContentArea1($this->renderBrowser());
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
			$this->tab_addEntry("Seiten",$url,"b_site.gif");
		}
		if ($this->checkRight("elm_content"))
		{
			$url = "backend.php?page=Editor,Content";
			$this->tab_addEntry("Content",$url,"b_content.gif");
		}
		$url = "backend.php?page=Editor,Media,browse&grp_id=0&folder=&type=0&sortorder=1&p=1&a=10";

		$this->tab_addEntry("Media",$url,"b_media.gif");
		$this->tab_draw("Media",$x=260,1);

		$this->displayMediagroupSelector();

		$this->displayFolderTree();

		$this->displayNewMediaObjectsForm();

		$this->displaySearchForm();

		$this->displayLightBox();

		return $myPT->stopBuffer();
	}

	function displayMediagroupSelector()
	{
		global $myDB;
		if (count ($this->_mediagroups)>1)
		{
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
	        <tr>
	          <td class="windowHeaderGrey2"><table border="0" cellspacing="0" cellpadding="0">
	            <tr>
	              <td class="padding10">
	              <form action="backend.php" method="post" name="formGrp">
		          <input type="hidden" name="page" value ="Editor,Media,browse">
		  	      <input type="hidden" name="folder" value="">
				  <input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
				  <input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
				  <input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
				  <input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
	              Gruppe:</td>
	              <td>
	              <select name="grp_id" onChange="document.forms.formGrp.submit();" class="listmenu">
	              <option value="0">alle</a>
	              <?php
	              foreach ($this->_mediagroups AS $k=>$v)
	              echo $this->optionTag($k,$v,$this->grp_id);

	              /*$sql = "SELECT * FROM mediagroup ORDER BY grp_bez";
	              $rs = $myDB->query($sql);
	              while ($row=mysql_fetch_array($rs))
	              {
	              echo $this->optionTag($row["grp_id"],$row["grp_bez"],$this->grp_id);

	              }
	              */
				  ?>
	              </select>
	              </td>
	            </tr>
	          </table></td>
	          <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></form></td>
	        </tr>
     	</table>
		<?php
		}
	}

	function displayFolderTree()
	{


		$myMB = new PhenotypeMediaBase();

		// Umbau der Folder in ein Treeobjekt
		if ($this->grp_id!=0)
		{
			$_folder = $myMB->getFullLogicalFolder($this->grp_id);
		}
		else
		{
			$_folder = $myMB->getFullLogicalFolder($this->_mediagroups);
		}

		if (count($_folder)==0){return;}

		$_navids = Array();
		$_navids[]=0;
		$_nav_id_current=0;

		$myNav = new PhenotypeTree();


		$_ueberordner = Array();
		$s=$this->folder;
		$p = strrpos($s," /");
		$i=1;
		while ($p!==false AND $i<10)
		{
			$left = substr($s,0,$p);
			$_ueberordner[substr_count($left,"/")+1]=$left;
			$s=$left;
			$p = strrpos($s," /");
			$i++;
		}

		$_current_ebene=$i;
		foreach ($_folder AS $folder)
		{
			$_tree= (explode("/",$folder));
			$n=count($_tree);
			if ($n==1)
			{
				$left="";
				$right=trim($_tree[0]);
			}
			else
			{
				$left="";
				for ($i=0;$i<$n-1;$i++)
				{
					$left .= trim($_tree[$i]) ." / ";
				}
				$left = substr($left,0,-3);
				$right=trim($_tree[$n-1]);
			}

			$nav_id_top = (int)$_navids[$left];

			$url = "backend.php?page=Editor,Media,browse&grp_id=".$this->grp_id."&folder=".urlencode($folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;

			$ebene = substr_count($folder,"/")+1;
			$takeit=0;

			if ($ebene<=$_current_ebene)
			{
				if (strpos($folder,$_ueberordner[$ebene-1])===0)
				{
					$takeit=1;
				}
			}
			else
			{
				if ($ebene==$_current_ebene+1)
				{
					if ($this->folder!="")
					{
						if (strpos($folder,$this->folder)===0)
						{
							$takeit=1;
						}
					}
				}

			}
			if($ebene==1){$takeit=1;}
			if ($takeit)
			{
				$nav_id = $myNav->addNode($right,$url,$nav_id_top,$folder);
				$_navids[$folder]=$nav_id;
			}

		}

		$this->displayTreeNavi($myNav,$this->folder);
	}

	function displaySearchForm()
	{
		global $myPT;
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="windowFooterGrey2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="padding10"><strong>Suche Media nach:</strong></td>
              </tr>
              <tr>
                <td class="padding10"> Bezeichnung </td>
                <td>
	  			<form action="backend.php" method="post">
	            <input type="hidden" name="page" value ="Editor,Media,search">
				<input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">	
	            <input type="hidden" name="folder" value="<?php echo $myPT->codeH($this->folder) ?>">
			    <input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
			    <input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
			    <input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
				<input type="hidden" name="a" value="<?php echo $this->itemcount ?>">	
                <input type="text" name="s" style="width: 100px" class="input">
                </td>
              </tr>
              <tr>
                <td class="padding10"> ID </td>
                <td><input type="text" name="i" style="width: 100px" class="input"></td>
              </tr>
              <tr>
                <td class="padding10"> Volltext </td>
                <td><input type="text" name="v" style="width: 100px" class="input"></td>
              </tr>
              <tr>
                <td class="padding10">&nbsp;</td>
                <td><input name="Submit" type="submit" class="buttonGrey2" value="Senden" style="width:102px">
                </form>
                </td>
              </tr>
          </table></td>
          <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
        </tr>
		
        <tr>
          <td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
          <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
        </tr>
      </table>
		<?php
	}

	function displayNewMediaObjectsForm()
	{
		global $myPT;
		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
	      	<tr>
	        	<td class="windowMenu">
	  			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  			<form action="backend.php" method="post">
	            <input type="hidden" name="page" value ="Editor,Media,upload">
	            <tr>
	              <td class="windowTabTypeOnly"><strong>Neue Dateien: </strong></td>
	              <td class="windowTabTypeOnly">
				  <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">	
	              <input type="hidden" name="folder" value="<?php echo $myPT->codeH($this->folder) ?>">
			      <input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
			      <input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
			      <input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
				  <input type="hidden" name="a" value="<?php echo $this->itemcount ?>">	
				  <input name="upload" type="submit" class="buttonWhite" id="upload" style="width:102px" value="Hochladen"></td>
	            </tr>
				</form>
	            <tr>
	              <td class="windowTabTypeOnly">&nbsp;</td>
	              <td class="windowTabTypeOnly">
	              <form action="backend.php" method="post">
	              <input type="hidden" name="page" value ="Editor,Media,import">
				  <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">	
	              <input type="hidden" name="folder" value="<?php echo $myPT->codeH($this->folder) ?>">
			      <input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
			      <input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
			      <input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
				  <input type="hidden" name="a" value="<?php echo $this->itemcount ?>">		  
				  <input name="import" type="submit" class="buttonWhite" id="import2" style="width:102px" value="Importieren">	
				  </form>
				  </td>
	            </tr>
	          </table>
			  </td>
	        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
	      </tr>
	      <tr>
	      	<td class="windowBottomShadow" width="250"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
	        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
	      </tr>
	    </table> 		
		<?php
	}



	function displayLightBox()
	{
		global  $myPT;
		global $mySUser;
		global $myRequest;
		?>
		<div id="lightbox">
		<?php

		$myDO = new PhenotypeDataObject("system.lightbox_media_usr_id_".$mySUser->id);

		$_objects = $myDO->get("objects");

		if ($myRequest->check("med_id"))
		{
			$med_id = $myRequest->getI("med_id");

			if (in_array($med_id,$_objects))
			{
				unset($_objects[$med_id]);
			}
			else
			{
				$_objects[$med_id]=$med_id;
			}
			$myDO->set("objects",$_objects);
			$myDO->store();
		}

		if (count($_objects)!=0)
		{
			$this->tab_new();
			$url = "backend.php?page=Editor,Media,browse&grp_id=0&folder=&type=0&sortorder=1&p=1&a=10";
			$this->tab_addEntry("Sammelbox",$url,"b_pinadd.gif");
			$this->tab_draw("Sammelbox",$x=260,1);


		?>
		<table width="260" border="0" cellpadding="0" cellspacing="0">
      	<tr>
        	<td class="windowMenu">
			<table  border="0" cellspacing="8" cellpadding="0">
				<?php
				foreach ($_objects AS $object)
				{
					$url = "backend.php?page=Editor,Media,edit&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;
					$myImg = new PhenotypeImage($object);
					if ($myImg->loaded==1)
					{
					?>
					<tr>
	    	        	<td width="10"><input type="checkbox" checked="checked" onclick="lightbox_switch(<?php echo $myImg->id ?>,1)"></td>
	    	        	<td width="40">
	    	        	<a href="<?php echo $url ?>&id=<?php echo $myImg->id ?>"><?php echo $myImg->display_thumbX(45); ?></a></td>
	    	        	<td ><a href="<?php echo $url ?>&id=<?php echo $myImg->id ?>"><?php echo $myPT->codeH($myPT->cutString($myImg->bez,23)) ?></a></td>
	          	  	</tr>
	          	  	<?php
					}
					else
					{
						$myDoc = new PhenotypeDocument($object);
						if ($myDoc->loaded==1)
						{
					?>
					<tr>
	    	        	<td width="10"><input type="checkbox" checked="checked" onclick="lightbox_switch(<?php echo $myDoc->id ?>,1)"></td>
	    	        	<td colspan="2"><a href="<?php echo $url ?>&id=<?php echo $myDoc->id ?>"><?php echo $myPT->codeH($myDoc->bez) ?></a></td>
	          	  	</tr>
	          	  	<?php
						}
					}
				}
				?>
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
	}


	function displayJS_Lightbox()
	{
		global $myPT;
		?>
		<script type="text/javascript">
		function lightbox_switch(med_id,check)
		{

			if (check==1)
			{
				var obj = document.getElementById('lb'+med_id);
				if (obj!=null)
				{
					obj.checked=!obj.checked;
				}
			}

			var ajax = new sack();
			ajax.resetData();
			ajax.requestFile = "backend.php";
			ajax.method = "POST";
			ajax.element = 'lightbox';
			ajax.setVar("page","Editor,Media,lightbox");
			ajax.setVar("med_id",med_id);

			// following variables are necessary for correct edit links...

			ajax.setVar("grp_id",<?php echo $this->grp_id ?>);
			ajax.setVar("folder","<?php echo $myPT->codeH($this->folder) ?>");
			ajax.setVar("type",<?php echo $this->type ?>);
			ajax.setVar("sortorder",<?php echo $this->sortorder ?>);
			ajax.setVar("p",<?php echo $this->pagenr ?>);
			ajax.setVar("a",<?php echo $this->itemcount ?>);


			//ajax.onLoading = whenLoading;
			//ajax.onLoaded = whenLoaded;
			//ajax.onInteractive = whenInteractive;
			//ajax.onCompletion = whenCompleted;
			ajax.runAJAX();
		}
		</script>
		<?php
	}
	function renderBrowser()
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;


		$myDO = new PhenotypeDataObject("system.lightbox_media_usr_id_".$mySUser->id);
		$_objects = $myDO->get("objects");

		$myPT->startBuffer();

		$this->displayJS_Lightbox();

		$headline = "Übersicht";
		$this->displayHeadline($headline,"http://www.phenotype-cms.de/docs.php?v=23&t=4");

		$this->displayEAIF();

		$this->tab_new();
		$url = "backend.php?page=Editor,Media,browse&grp_id=".$this->grp_id."&folder=".$this->folder ."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;
		$this->tab_addEntry("Alle Dateien",$url ."&type=0","b_all_files.gif");
		$this->tab_addEntry("Bilder",$url ."&type=1","b_media.gif");
		$this->tab_addEntry("Dokumente",$url ."&type=2","b_content.gif");

		switch ($this->type)
		{
			case  1: $tab = "Bilder";
			break;
			case  2: $tab = "Dokumente";
			break;
			default: $tab = "Alle Dateien";
			break;
		}

		$this->tab_draw($tab);


		// SQL-String fuer Medienauswahl zusammenbauen

		$sql = "SELECT * FROM media WHERE 1=1 ";

		if ($this->folder!="")
		{
			$sql .=" AND (med_logical_folder1 LIKE '" . $myPT->codeSQL($this->folder) ."%' OR med_logical_folder2 LIKE '" . $myPT->codeSQL($this->folder) ."%' OR med_logical_folder3 LIKE '" . $myPT->codeSQL($this->folder) ."%')";
		}

		if ($this->type!=0)
		{
			$sql .=" AND med_type='" . $this->type ."'";
		}



		if ($this->grp_id!=0)
		{
			$sql .=" AND grp_id=" . $this->grp_id ;
		}
		else
		{

			$sqlgrp =" AND grp_id IN(";
			foreach ($this->_mediagroups AS $k=>$v)
			{
				$sqlgrp .= $k.",";
			}
			$sql .= substr($sqlgrp,0,-1) . ")";
		}

		switch ($this->sortorder)
		{
			case 1:
				$sql .=" ORDER BY med_date DESC";
				break;
			case 2:
				$sql .=" ORDER BY med_bez";
				break;
			case 3:
				$sql .=" ORDER BY med_id DESC";
				break;

		}

		$rs =$myDB->query($sql);

		// Seite und Anzahl bestimmen

		$anzahl = mysql_num_rows($rs);

		$p = $this->pagenr;

		// Wechsel der Anzahlsanzeige ??
		if ($myRequest->check("a2") AND ($this->itemcount !=$myRequest->getI("a2")))
		{
			$n= (($p-1)*$myRequest->getI("a2"))+1;
			$p= ceil($n/$this->itemcount);
		}

		// Seite größer als es die Anzahl erlaubt?
		$max=ceil($anzahl/$this->itemcount);
		if ($max==0){$max=1;}
		if ($p>$max){$p=$max;}


		$reihe = 5;

		$start = ($p-1)*($_REQUEST["a"]);
		$sql .=" LIMIT ". $start . "," . $this->itemcount;
		?>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowHeaderGrey2">
			<table border="0" cellspacing="0" cellpadding="0">
	            <tr>
	              <td class="padding10"><form action="backend.php" method="post" name="formsort">
	              <input type="hidden" name="page" value="Editor,Media,browse">
				  <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">	
	              <input type="hidden" name="folder" value="<?php echo $myPT->codeH($this->folder) ?>">
			      <input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
			      <input type="hidden" name="p" value="<?php echo $p ?>">		  
				  <input type="hidden" name="a" value="<?php echo $this->itemcount ?>">		              
		          <input name="sortorder" type="radio" value="1" <?php if ($this->sortorder==1){echo"checked";} ?> onclick="document.forms.formsort.submit();">Datum
				  <input name="sortorder" type="radio" value="2" <?php if ($this->sortorder==2){echo"checked";} ?> onclick="document.forms.formsort.submit();">Bezeichnung
				  <input name="sortorder" type="radio" value="3" <?php if ($this->sortorder==3){echo"checked";} ?> onclick="document.forms.formsort.submit();">ID</td>
	              <td class="padding10"><select name="a" class="listmenu" onchange="document.forms.formsort.submit();">
				    <?php for ($i=1;$i<=5;$i++){ ?>
	                <option value="<?php echo ($i*10) ?>" <?php if ($this->itemcount==($i*10)){echo "selected";} ?>><?php echo ($i*10) ?> Medien / Seite anzeigen</option>
					<?php } ?>
					<input type="hidden" name="a2" value="<?php echo $this->itemcount ?>">
					</select></form></td>
	            </tr>
	        </table></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	    </table>

		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowBlank">
	
			<table width="100%" border="0" cellpadding="0" cellspacing="3">
			<?php
			$rs =$myDB->query($sql);
			$i=0;$aktion=-1;
			while ($row=mysql_fetch_array($rs))
			{
				$i++;
				$aktion=$i%$reihe;

				$checked="";

				if (in_array($row["med_id"],$_objects))
				{
					$checked='checked="checked"';
				}

				if ($aktion==1) // Zeile oeffnen
				{
				?>
			  <tr valign="top">
				<?php
				}
				?>
	            <td class="tableCellMedia" width="136">
				
				<table width="115" height="105" border="0" cellpadding="0" cellspacing="0">
	                <tr>
	                  <td align="center" class="tableCellImage">
	                  <a href="backend.php?page=Editor,Media,edit&id=<?php echo $row["med_id"] ?>&grp_id=<?php echo $this->grp_id ?>&folder=<?php echo $myPT->codeH($this->folder) ?>&type=<?php echo $this->type ?>&sortorder=<?php echo $this->sortorder ?>&p=<?php echo $this->pagenr ?>&a=<?php echo $this->itemcount ?>">
					  <?php
					  if ($row["med_type"]==MB_IMAGE)
					  {
					  	$myIMG = new PhenoTypeImage($row["med_id"]);
					  	$myIMG->display_thumb();
					  	echo '</a><br/><input type="checkbox" onclick="lightbox_switch('.$row["med_id"].',0)" '.$checked.' id="lb'.$row["med_id"].'">';
					  }
					  else
					  {
					  	$icon = "binary";
					  	switch (strtolower($row["med_subtype"]))
					  	{
					  		case "gif":
					  		case "jpg":
					  		case "bmp":
					  		case "psd":
					  			//case "png":
					  		case "jpeg":
					  			$icon = "image";
					  			break;

					  		case "wav":
					  		case "mid":
					  		case "mp3":
					  			$icon = "audio";
					  			break;

					  		case "pdf":
					  			$icon = "pdf";
					  			break;

					  		case "xls":
					  			$icon = "excel";
					  			break;

					  		case "doc":
					  			$icon = "word";
					  			break;

					  		case "ppt":
					  			$icon = "powerpoint";
					  			break;

					  		case "sql":
					  		case "txt":
					  			$icon = "text";
					  			break;

					  	}

					  	echo "&lt;".$icon.'&gt;</a><br/><input type="checkbox" onclick="lightbox_switch('.$row["med_id"].',0)" '.$checked.' id="lb'.$row["med_id"].'">';
					  }
						?>
					  </td>
	                </tr>
	              </table>
	              
	                <a href="backend.php?page=Editor,Media,edit&id=<?php echo $row["med_id"] ?>&grp_id=<?php echo $this->grp_id ?>&folder=<?php echo $myPT->codeH($this->folder) ?>&type=<?php echo $this->type ?>&sortorder=<?php echo $this->sortorder ?>&p=<?php echo $this->pagenr ?>&a=<?php echo $this->itemcount ?>"><img src="img/b_edit.gif" width="22" height="22" border="0" align="absmiddle"> bearbeiten<br>
	                </a> Ordner: <?php echo $row["med_logical_folder1"] ?><br>
					<?php
					if ($row["med_type"]==MB_IMAGE)
					{
						echo "Gr&ouml;&szlig;e: (" . $row["med_x"] ." x " . $row["med_y"] .")<br>";
					}
					else
					{
						echo "<br>";
					}
				    ?>
	                <strong><?php echo $myPT->cutString($row["med_bez"],32,18); ?></strong>
					</td>
	
						<?php
						if ($aktion==0) // Zeile schliessen
						{
						?>
						</tr>
						<?php
						}
			}
			if ($aktion!=0)
			{
				$n= $reihe-($i%$reihe);
				for ($j=1;$j<=$n;$j++)
				{
						?>
						<td valign="top" class="tableCellMedia" width="136"></td>
						<?php
				}
					  ?>
					  </tr>
					  <?php
			}
					  ?>
	
	
	        </table>
	
			</td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	    </table>
	    <?php


	    $url = "backend.php?page=Editor,Media,browse&grp_id=".$this->grp_id . "&folder=" .$myPT->codeH($this->folder) ."&type=" .$this->type . "&sortorder=" . $this->sortorder . "&a=" . $this->itemcount."&p=";

	    echo $this->renderPageBrowser($p,$anzahl,$url,$this->itemcount,true);
	    return $myPT->stopBuffer();
	}

	/**
	 * rendering edit mask of a mediaobject
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
		$myPT->startBuffer();

		$this->displayJS_Lightbox();

		$myObj = new PhenotypeImage($_REQUEST["id"]);
		if ($myObj->id != 0)
		{
			// Bild
			$type = MB_IMAGE;
		} else
		{
			$type = MB_DOCUMENT;
			$myObj = new PhenoTypeDocument($_REQUEST["id"]);

			if ($myObj->id == 0)
			{
				$this->noAccess();
			}
		}

		$block_nr = $myRequest->getI("b");

		$blocknr_painter = -1; // unerreichbar ..
		$blocknr_versionen = -1; // unerreichbar ..
		$blocknr_oie = -1; // unerreichbar ...

		if ($myObj->hasVersions())
		{
			$blocknr_versionen =1;
		}
		if ($myObj->type == MB_IMAGE)
		{
			$blocknr_painter = 2;
		}

		if ($block_nr==1 AND $blocknr_versionen==-1){$block_nr=0;}// switch to properties tab if no versions exists

		?>
        <form action="backend.php" method="post" enctype="multipart/form-data" name="editform" <?php if ($block_nr==2){ ?>onsubmit="return false;"<?php } ?>>
        <input type="hidden" name="page" value="Editor,Media,update">
        <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">
        <input type="hidden" name="id" value="<?php echo $myObj->id ?>">
        <input type="hidden" name="ver_id" value="<?php echo $myRequest->getI("ver_id") ?>">
        <input type="hidden" name="folder" value="<?php echo $this->folder ?>">
		<input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
		<input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
		<input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
		<input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
		<input type="hidden" name="objecttype" value="<?php echo $type ?>">	
		<input type="hidden" name="b" value="<?php echo $block_nr ?>">	
        

		<?php	
		$this->displayIDLineMediaObject($myObj);

		$conflict = "";
		$sql = "SELECT usr_id,med_date FROM media WHERE med_id=".$myObj->id;
		$rs_conflict= $myDB->query($sql);
		$row_conflict = mysql_fetch_array($rs_conflict);
		$datum = $row_conflict["med_date"];
		$minuten = (int)((time()-$datum)/60);
		if ($minuten <10 AND $row_conflict["usr_id"]!=$mySUser->id)
		{
			$zustand="";
			switch ($minuten)
			{
				case 0:
					$zustand = "gerade";
					break;
				case 1:
					$zustand = "vor 1 Minute";
					break;
				default:
					$zustand = "vor " . $minuten . " Minuten ";
					break;

			}
			$myUser = new PhenotypeUser($row_conflict["usr_id"]);
			$conflict = "Dieser Datensatz wurde ".$zustand . " von " . $myUser->getName() . " verändert.";
		}

		if ($conflict)
		{
			$this->displayAlert($conflict);
		}


		$this->displayEAIF();

		$this->displayGlueTicketsForMediaobject($myObj->id);





		$this->tab_new();

		$url = "backend.php?page=Editor,Media,edit&id=".$myObj->id."&b=0&folder=".urlencode($_REQUEST["folder"])."&type=".$_REQUEST["type"]."&sortorder=".$_REQUEST["sortorder"]."&p=".$_REQUEST["p"]."&a=".$_REQUEST["a"];
		$this->tab_addEntry("Eigenschaften", $url, "b_konfig.gif");





		if ($blocknr_versionen==1)
		{
			$url = "backend.php?page=Editor,Media,edit&id=".$myObj->id."&b=1&folder=".urlencode($_REQUEST["folder"])."&type=".$_REQUEST["type"]."&sortorder=".$_REQUEST["sortorder"]."&p=".$_REQUEST["p"]."&a=".$_REQUEST["a"];
			$this->tab_addEntry("Versionen", $url, "b_version.gif");
			$blocknr_versionen = 1;
		}

		if ($blocknr_painter == 2)
		{



			$url = "backend.php?page=Editor,Media,edit&id=".$myObj->id."&b=2&folder=".urlencode($_REQUEST["folder"])."&type=".$_REQUEST["type"]."&sortorder=".$_REQUEST["sortorder"]."&p=".$_REQUEST["p"]."&a=".$_REQUEST["a"];
			$this->tab_addEntry("Bearbeiten", $url, "b_edit.gif");

		}





		/*
		$blocknr_oie???
		if ($type == MB_IMAGE)
		{
		$agent = $_SERVER["HTTP_USER_AGENT"];
		if ($agent == "")
		{
		$agent = $_ENV["HTTP_USER_AGENT"];
		}
		$agent = strtoupper($agent);
		if (strpos($agent, "MSIE"))
		{
		$url = "backend.php?page=Editor,Media,edit&id=".$myObj->id."&b=".$blocknr_oie."&folder=".urlencode($_REQUEST["folder"])."&type=".$_REQUEST["type"]."&sortorder=".$_REQUEST["sortorder"]."&p=".$_REQUEST["p"]."&a=".$_REQUEST["a"];
		$this->tab_addEntry("Bearbeiten", $url, "b_edit.gif");
		}
		}
		*/


		switch ($block_nr)
		{
			case $blocknr_versionen:
				$this->displayEdit_Versions($myObj);
				break;
			case $blocknr_painter:
				$this->displayEdit_Painter($myObj);
				break;
				//case $blocknr_oie:
				//	$this->displayEdit_OIE($myObj);
				//	break;
			case 0:
				$this->displayEdit_Properties($myObj);
				break;
		}








		return $myPT->stopBuffer();
	}


	function displayEdit_Properties($myObj)
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$this->tab_draw("Eigenschaften");

		$this->workarea_start_draw();
		$html = $this->workarea_form_text("Titel", "bez", $myObj->bez);
		$html .= $this->workarea_form_text("Alternate", "alt", $myObj->alt);
		$this->workarea_row_draw("Bezeichnung", $html);
		if ($myObj->type == MB_IMAGE)
		{
			$myPT->startBuffer();
			?>
  			<a href="<?php echo MEDIABASEURL .  $myObj->physical_folder ?>/<?php echo $myObj->filename ?> " target="_blank">
  			<?php
  			$myObj->display_maxX(500);
  			?></a><br><br><input name="userfile" type="file" class="input"><?php

  			$html = $myPT->stopBuffer();
  			$this->workarea_row_draw("Bild (".$myObj->x."x".$myObj->y.")", $html);
		}
		else
		{
			$myPT->startBuffer();
			?>
  			<a href="<?php echo MEDIABASEURL . $myObj->physical_folder ?>/<?php echo $myObj->filename ?> " target="_blank"><?php echo $myObj->filename ?></a>
			<br><br><input name="userfile" type="file" class="input">
  			<?php
  			$html = $myPT->stopBuffer();
  			$this->workarea_row_draw("Dokument", $html);
		}




		$myPT->startBuffer();
		$myMB = new PhenotypeMediaBase();
		$_folder = $myMB->getLogicalFolder();

		?>
		<select name="folder1" class="input" style="width:250px" onchange="document.forms.editform.folder1_new.value='';">
		<option value="">...</option>
		<?php
		foreach ($_folder AS $k)
		{
			$selected = "";
			if (strtolower($k) == strtolower($myObj->logical_folder1))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option <?php echo $selected ?>><?php echo $k ?></option>
		<?php
		}
		?>
		</select>&nbsp;<input name="folder1_new" type="text" class="input" value="" style="width:200px" onfocus="document.forms.editform.folder1.selectedIndex=0;"><br>
		<select name="folder2" class="input" style="width:250px" onchange="document.forms.editform.folder2_new.value='';">
		<option value="">...</option>
		<?php

		foreach ($_folder AS $k)
		{
			$selected = "";
			if (strtolower($k) == strtolower($myObj->logical_folder2))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option <?php echo $selected ?>><?php echo $k ?></option>
		<?php
		}
		?>
		</select>&nbsp;<input name="folder2_new" type="text" class="input" value="" style="width:200px" onfocus="document.forms.editform.folder2.selectedIndex=0;"><br>
		<select name="folder3" class="input" style="width:250px" onchange="document.forms.editform.folder3_new.value='';">
		<option value="">...</option>
		<?php

		foreach ($_folder AS $k)
		{
			$selected = "";
			if (strtolower($k) == strtolower($myObj->logical_folder3))
			{
				$selected = 'selected="selected"';
			}
		?>
		<option <?php echo $selected ?>><?php echo $k ?></option>
		<?php
		}
		?>
		</select>&nbsp;<input name="folder3_new" type="text" class="input" value="" style="width:200px" onfocus="document.forms.editform.folder3.selectedIndex=0;"><br>
		<?php
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Ordner", $html);

		$html = $this->workarea_form_textarea("", "keywords", $myObj->keywords);
		$this->workarea_row_draw("Keywords", $html);

		$html = $this->workarea_form_textarea("", "comment", $myObj->comment, 10);
		$this->workarea_row_draw("Kommentar", $html);

		$sql = "SELECT grp_bez,grp_type FROM mediagroup WHERE grp_id=".$myObj->grp_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$this->workarea_row_draw("Mediagruppe", $row["grp_bez"]);
		$grp_type = $row["grp_type"];
		$this->workarea_row_draw("Mimetype", $myObj->mimetype);

		if ($myObj->versioncount == 0 AND $grp_type == 2) // nur bei manueller Versionierung
		{
			$myPT->startBuffer();
			?><p>Bezeichnung</p><input name="ver_bez" type="text" class="input" value="" style="width:175px"><br/><br/><input name="versionfile" type="file" class="input"><br><br/><?php



			$html = $myPT->stopBuffer();
			$this->workarea_row_draw("Neue Version anlegen", $html);
		}
		else
		{
			if ($grp_type != 1 OR $myObj->versioncount > 0)
			{
				$this->workarea_row_draw("Versionen", $myObj->versioncount);
			}
		}

		$myPT->startBuffer();
		$myAdm->displayCreationStatus($myObj->usr_id_creator, $myObj->creationdate);
		echo "<br>";
		$myAdm->displayChangeStatus($myObj->usr_id, $myObj->cdate);
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Status", $html);
		?>
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite">	<input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:return confirm('Dieses <?php if ($type==MB_IMAGE){echo "Bild";}else{echo "Dokument";} ?> wirklich l&ouml;schen?')"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
		$this->workarea_stop_draw();
		?>
		</form>
   		<SCRIPT LANGUAGE=javascript>
   		function initoid()
   		{
     		// NOP
   		}
   		</SCRIPT>
		<?php
	}


	function displayEdit_Versions($myObj)
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$sql = "SELECT grp_bez,grp_type FROM mediagroup WHERE grp_id=".$myObj->grp_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$grp_type = $row["grp_type"];

		$this->tab_draw("Versionen");
		$this->workarea_start_draw();
		$sql = "SELECT * FROM mediaversion WHERE med_id = ".$myObj->id." ORDER BY ver_bez, ver_id DESC";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$myObj->initVersion($row);
			$bez = $row["ver_bez"];
			$myPT->startBuffer();
			?><table><tr><td width="475"><a href="<?php echo MEDIABASEURL .  $myObj->physical_folder ?>/<?php echo $myObj->filename ?> " target="_blank"><?php

			if ($myObj->type == MB_IMAGE)
			{
				$myObj->display_maxX(470);
			}
			else
			{
			?>
	 		<a href="<?php echo $myObj->url ?> " target="_blank"><?php echo $myObj->filename ?></a>
			<?php
			}
			?></a></td><td><?php if ($grp_type == 2 OR $mySUser->checkRight("superuser") OR $mySUser->checkRight("elm_admin")){ ?><input type="image" src="img/b_delete.gif" name="ver<?php echo $row["ver_id"] ?>_delete"><?php }else echo"&nbsp;"; ?></td></tr></table><br/><p>Bezeichnung</p><input name="ver<?php echo $row["ver_id"] ?>_bez" type="text" class="input" value="<?php echo htmlentities($bez) ?>" style="width:250px"><?php
			$html = $myPT->stopBuffer();
			if ($myObj->type == MB_IMAGE)
			{
				$bez .= "<br/>(".$row["ver_x"]."x".$row["ver_y"].")";
			}
			$this->workarea_row_draw($bez, $html);
		}
		if ($grp_type == 2) // manuelle Versionen
		{
			$myPT->startBuffer();
			?><p>Bezeichnung</p><input name="ver_bez1" type="text" class="input" value="" style="width:175px"><br/><br/><input name="versionfile1" type="file" class="input"><br><br/>
<p>Bezeichnung</p><input name="ver_bez2" type="text" class="input" value="" style="width:175px"><br/><br/><input name="versionfile2" type="file" class="input"><br><br/>
<p>Bezeichnung</p><input name="ver_bez3" type="text" class="input" value="" style="width:175px"><br/><br/><input name="versionfile3" type="file" class="input"><br><br/>
			<?php
			$html = $myPT->stopBuffer();

			$this->workarea_row_draw("Neue Versionen anlegen", $html);
		}
		?>
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite">
				<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
		$this->workarea_stop_draw();
	}


	function displayEdit_OIE($myObj)
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		// Block-NR = 1 ...
		$this->tab_draw("Bearbeiten");
		$this->workarea_start_draw();
?>
  <tr><td bgcolor="#FFFFFF"></form>




<SCRIPT LANGUAGE=javascript>
function Store()
{
  OIE.GetAsBase64('<?php echo $myObj->filename ?>'); // Nur zum Bestimmen des Typs
  document.oieform.imagedata64.value = OIE.EncodedData;
  return (OIE.EncodedData.length != 0);
}

function Load()
{
  OIE.SnapshotPaneVisible=false;
  OIE.BgColor='#FFFFFF';
  //OIE.SetFeature('ImageMenu',false);
  OIE.AddSizePreset(120,90);
  OIE.LoadFromURL('<?php echo MEDIABASEURL . $myObj->physical_folder."/".$myObj->filename; ?>');
}
</SCRIPT>
<SCRIPT LANGUAGE=javascript>
function initoid()
{
  Load(); 
}
</SCRIPT>

<CENTER>
  <OBJECT
      name="OIE" 
	  id="OIE"
	  classid="clsid:EC59EE59-B27D-4581-9E93-6AB6CB88E970"
	  codebase="OIE.cab#version=2,0,770,0"
	  width=664
	  height=600
	  align=center
	  hspace=0
	  vspace=0
   >
   <PARAM name="SnapshotPaneVisible" value="false">
   </OBJECT>

<BR>
<form action="backend.php" method="post" enctype="multipart/form-data" name="oieform">
<input type="hidden" name="page" value="Editor,Media,update">
<input type="hidden" name="b" value="<?php echo $blocknr_oie ?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
<input type="hidden" name="folder" value="<?php echo $_REQUEST["folder"] ?>">
<input type="hidden" name="type" value="<?php echo $_REQUEST["type"] ?>">				   				   
<input type="hidden" name="sortorder" value="<?php echo $_REQUEST["sortorder"] ?>">
<input type="hidden" name="p" value="<?php echo $_REQUEST["p"] ?>">	
<input type="hidden" name="a" value="<?php echo $_REQUEST["a"] ?>">
<input type="hidden" name="objecttype" value="<?php echo $type ?>">	
<input type="hidden" name="imagedata64">
</CENTER></td><td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
</tr>
    <tr>
            <td align="right" class="windowFooterWhite"><input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern" onclick="return Store();">&nbsp;&nbsp;</td>
<td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
</tr>
          <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>      
  </FORM>
  <?php

	}


	function displayEdit_Painter($myObj)
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;
		global $myApp;



		$_images =Array();
		$_images[0]["bez"] = "Original";
		$_images[0]["url"] = $myObj->url;
		$_images[0]["tag"] = $myObj->render_thumbX(60);
		$_images[0]["x"]=$myObj->x;
		$_images[0]["y"]=$myObj->y;
		$_images[0]["size"]=filesize($myObj->file);


		//$url_image = $myObj->url;
		// Stream-URL
		$url_image = "backend.php?page=Editor,Media,streamimage&img_id=".$myObj->id;


		$x = $myObj->x;
		$y = $myObj->y;



		$ver_id = $myRequest->getI("ver_id");

		$sql = "SELECT * FROM mediaversion WHERE med_id = ".$myObj->id." ORDER BY ver_bez, ver_id DESC";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$myObj->initVersion($row);
			$bez = $row["ver_bez"];

			$_images[$row["ver_id"]]["bez"] = $row["ver_bez"];
			$_images[$row["ver_id"]]["url"] = $myObj->url;
			$_images[$row["ver_id"]]["tag"] = $myObj->render_thumbX(60);
			$_images[$row["ver_id"]]["x"]=$myObj->x;
			$_images[$row["ver_id"]]["y"]=$myObj->y;
			$_images[$row["ver_id"]]["size"]=filesize($myObj->file);

			if ($row["ver_id"]==$ver_id)
			{
				//$url_image = $myObj->url;
				$url_image = "backend.php?page=Editor,Media,streamimage&img_id=".$myObj->id."&ver_id=".$ver_id;
				$x = $myObj->x;
				$y = $myObj->y;
			}

		}



		$maxX = 640;
		$x2=$x;
		$y2=$y;

		if ($x2>$maxX)
		{
			$x2=$maxX;
			$y2=(int)$y*($maxX/$x);
		}


		$url_versionselect = "backend.php?page=Editor,Media,edit&id=".$myObj->id."&b=2&folder=".$this->folder."&type=".$this->type."&sortorder=".$this->sortorder."&p=".$this->pagenr."&a=".$this->itemcount."&ver_id=";
		$this->tab_draw("Bearbeiten");

		?>
		<script type="text/javascript">
		/************************************************************************************************************
		(C) www.dhtmlgoodies.com, April 2006

		This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

		Terms of use:
		You are free to use this script as long as the copyright message is kept intact. However, you may not
		redistribute, sell or repost it without our permission.

		Thank you!

		www.dhtmlgoodies.com
		Alf Magne Kalleland

		************************************************************************************************************/
		var crop_script_server_file = 'crop_image.php';

		var cropToolBorderWidth = 1;	// Width of dotted border around crop rectangle
		var smallSquareWidth = 7;	// Size of small squares used to resize crop rectangle

		// Size of image shown in crop tool
		var crop_imageWidth = <?php echo $x2 ?>;
		var crop_imageHeight = <?php echo $y2 ?>;

		// Size of original image
		var crop_originalImageWidth = <?php echo $x ?>;
		var crop_originalImageHeight = <?php echo $y ?>;

		var crop_minimumPercent = 10;	// Minimum percent - resize
		var crop_maximumPercent = 200;	// Maximum percent -resize


		var crop_minimumWidthHeight = 10;	// Minimum width and height of crop area

		var updateFormValuesAsYouDrag = false; // Disable always
		if(!document.all)updateFormValuesAsYouDrag = false;	// Enable this feature only in IE


		</script>
		<script type="text/javascript" src="image-crop.js"></script>
		<script type="text/javascript">
		function painter_changeCropFormat()
		{
			f = document.forms.editform.size_format.value;
			switch(f)
			{
				case "0":
				document.forms.editform.size_method.value=1;
				break;

				<?php
				$_definitions = $myApp->getImageEditingFormatArray($myObj);
				$i=0;
				foreach ($_definitions AS $_def)
				{
					$i++;
					?>
					case "<?php echo $i ?>":
					document.forms.editform.size_method.value=<?php echo $_def["method"] ?>;
					document.forms.editform.size_x.value=<?php echo $_def["x"] ?>;
					document.forms.editform.size_y.value=<?php echo $_def["y"] ?>;
					document.forms.editform.size_sharpening.selectedIndex=<?php echo $_def["sharpening"] ?>;
					document.forms.editform.size_quality.value=<?php echo $_def["quality"] ?>;
					document.forms.editform.size_versionaction.selectedIndex=<?php echo $_def["versionaction"] ?>;
					document.forms.editform.size_newversion.value='<?php echo $myPT->codeH($_def["newversion"]) ?>';
					break;
					<?php
				}
				?>

			}
			painter_changeCropParameters(true);

		}

		function painter_changeCropParameters(format)
		{

			if (format==false)
			{
				document.forms.editform.size_format.selectedIndex=0;
			}

			if (smallSquare_tl.style.visibility == "hidden")
			{
				smallSquare_tl.style.visibility = "visible";
				smallSquare_tc.style.visibility = "visible";
				smallSquare_tr.style.visibility = "visible";
				smallSquare_bl.style.visibility = "visible";
				smallSquare_bc.style.visibility = "visible";
				smallSquare_br.style.visibility = "visible";
				smallSquare_lc.style.visibility = "visible";
				smallSquare_rc.style.visibility = "visible";
			}



			m = document.forms.editform.size_method.value;

			if (m==1 | m==5) // frei oder Zielrahmen
			{
				crop_script_alwaysPreserveAspectRatio = false;
				crop_script_fixedRatio=false;
				if (m==1)
				{
					document.forms.editform.size_x.value="";
					document.forms.editform.size_y.value="";
				}
				return;
			}


			// Ratio berechnen

			x = document.forms.editform.size_x.value;
			y = document.forms.editform.size_y.value;

			if (isNaN(x) | x=="")
			{
				x=50;
				document.forms.editform.size_x.value=50;
			}
			if (isNaN(y) | y=="")
			{
				y=50;
				document.forms.editform.size_y.value=50;
			}

			r = x/y;



			if (m==2) // feste Zielgröße
			{
				smallSquare_tl.style.visibility = "hidden";
				smallSquare_tc.style.visibility = "hidden";
				smallSquare_tr.style.visibility = "hidden";
				smallSquare_bl.style.visibility = "hidden";
				smallSquare_bc.style.visibility = "hidden";
				smallSquare_br.style.visibility = "hidden";
				smallSquare_lc.style.visibility = "hidden";
				smallSquare_rc.style.visibility = "hidden";

			}



			crop_script_alwaysPreserveAspectRatio = true;
			crop_script_fixedRatio=r;
			document.forms.editform.crop_x.value = 0;
			document.forms.editform.crop_y.value=0;
			document.forms.editform.crop_width.value = x;
			document.forms.editform.crop_height.value =y;
			cropScript_setCropSizeByInput();





		}


		function preview(img_id,ver_id)
		{
			url = 'backend.php?page=Editor,Media,preview';
			url += "&img_id=" + img_id +"&ver_id=" + ver_id;
			url += "&size_method=" + document.forms.editform.size_method.value;
			url += "&size_x=" + document.forms.editform.size_x.value;
			url += "&size_y=" + document.forms.editform.size_y.value;
			url += "&size_quality=" + document.forms.editform.size_quality.options[document.forms.editform.size_quality.selectedIndex].value;
			url += "&size_sharpening=" + document.forms.editform.size_sharpening.value;
			url += "&crop_x=" + document.forms.editform.crop_x.value;
			url += "&crop_y=" + document.forms.editform.crop_y.value;
			url += "&crop_width=" + document.forms.editform.crop_width.value;
			url += "&crop_height=" + document.forms.editform.crop_height.value;

			popup(url,'vorschau','scrollbars=yes,width=1024,height=768,resizable=yes,status=yes,location=no');

		}
		</script>
		<input type="hidden" name="deleteversion" value="0"/>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowHeaderGrey2">

			<table border="0" cellspacing="0" cellpadding="0">
	            <tr>

	              <td class="padding10">
	              Format: <select name="size_format" class="listmenu" onchange="painter_changeCropFormat();">
	                <option value="0" selected>...</option>
	                <?php
	                $_definitions = $myApp->getImageEditingFormatArray($myObj);
	                $i=0;
	                foreach ($_definitions AS $_def)
	                {
	                	$i++;
	                	echo '<option value="'.$i.'">'.$myPT->codeH($_def["name"]).'</option>';
	                }
					?>
				  </select>
				  
				  </td>
	              <td class="padding10">              
				  X
				  <input type="text" class="input" onchange="painter_changeCropParameters(false);" name="size_x" style="width:35px">
				  Y
				  <input type="text" class="input" onchange="painter_changeCropParameters(false);" name="size_y" style="width:35px">
				   &nbsp;<select name="size_method" class="listmenu" onchange="painter_changeCropParameters(false);">
	                <option value="1" selected>frei</option>
	                <option value="2" >fixer Ausschnitt</option>
	                <option value="3" >Verhältnis</option>
	                <option value="4" >fixe Zielgröße</option>
	                <option value="5" >Zielrahmen</option>
				  </select>
				  </td>
	              <td class="padding10"> 
				  Qualität: <select name="size_quality" class="listmenu">
				  <option value="100">100</option>
				  <option value="95">95</option>
				  <option value="90">90</option>
				  <option value="85">85</option>
				  <option selected="selected" value="80">80</option>
				  <option value="75">75</option>
				  <option value="70">70</option>
				  <option value="65">65</option>
				  <option value="60">60</option>
				  <option value="55">55</option>
				  <option value="50">50</option>
				  <option value="45">45</option>
				  <option value="40">40</option>
				  <option value="30">30</option>
				  <option value="20">20</option>
				  <option value="10">10</option>
				  </select>
	              &nbsp;Schärfen: <select name="size_sharpening" class="listmenu">
	                <option value="0" >ohne</option>
	                <option value="1" >leicht</option>
	                <option value="2" >normal</option>
	                <option value="3" >mittel</option>
	                <option value="4" >stark</option>
				  </select>
				  </td>					
	            </tr>
	        </table></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	    </table>
		<br/>
	    <?php


	    $this->tab_new();
	    foreach ($_images AS $k=>$_image)
	    {
	    	$url = $url_versionselect . $k;
	    	$this->tab_addEntry($_image["bez"], $url, "b_version.gif");
	    }
	    $this->tab_draw($_images[$ver_id]["bez"]);

		?>
	
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBlank">
       	<br/>    		
		<div id="imageContainer">
		<img src="<?php echo $url_image ?>" width="<?php echo $x2 ?>" height="<?php echo $y2 ?>">
		</div>
		<br/>
		<br/>
	     </td>
         <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
		<table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowHeaderGrey2">

			<table border="0" cellspacing="0" cellpadding="0">
	            <tr>

	              <td class="padding10">
	              Aktion:&nbsp;<select name="size_versionaction" class="listmenu">
	                <option value="0">Version überschreiben</option>
	                <option value="1" <?php if ($ver_id==0){echo 'selected="selected"';} ?>>neue Version:</option>
				  </select>
	  			  <input type="text" class="input" name="size_newversion" style="width:200px" >		  
				
	            </tr>
	        </table></td>
	        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
	      </tr>
	    </table>
	    

	   	<div style="visibility:hidden;position:absolute" >
		<input type="text" class="textInput" name="crop_x" id="input_crop_x" value="0">
		<input type="text" class="textInput" name="crop_y" id="input_crop_y" value="0">
		<input type="text" class="textInput" name="crop_width" id="input_crop_width" value="<?php echo $x ?>">
		<input type="text" class="textInput" name="crop_height" id="input_crop_height" value="<?php echo $y ?>">
		<input type="text" class="textInput" name="crop_percent_size" id="crop_percent_size" value="100">
		<span id="label_dimension"></span>
		</div>

	    
	    <table width="680" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	        <td class="windowBlank">
	    
<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>

            <td class="windowFooterWhite">
        <input name="vorschau" type="submit" style="width:102px"class="buttonWhite" value="Vorschau" onclick="preview(<?php echo $myObj->id ?>,<?php echo $ver_id ?>)">
            </td>
            <td align="right" class="windowFooterWhite"><?php if ($ver_id!=0){ ?><input name="delete" type="submit" class="buttonWhite" style="width:102px" value="Löschen" onclick="javascript:if (confirm('Diese Version wirklich l&ouml;schen?')){document.forms.editform.deleteversion.value=1;document.forms.editform.submit();}"><?php } ?> <input name="save" type="submit" class="buttonWhite" style="width:102px"value="Speichern" onclick="document.forms.editform.submit();">&nbsp;&nbsp;</td>
          </tr>
        </table>
      		</td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>

      </tr>
      <tr>
        <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table>
		<script type="text/javascript">
		init_imageCrop();
		cropDiv_selectionsize.innerHTML = '<span><?php echo $_images[$ver_id]["x"] ?>x<?php echo $_images[$ver_id]["y"] ?> (<?php echo (int)($_images[$ver_id]["size"]/1024) ?> kb)</span>';
		</script>
		<?php

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



		$_mediagroups = Array();
		$sql = "SELECT grp_id,grp_bez FROM mediagroup";
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$_mediagroups[$row["grp_id"]]=$row["grp_bez"];
		}

		$sql_cond = "";
		if ($myRequest->get("s")!="")
		{
			$sql_cond = " med_bez LIKE '%" . $myRequest->getS("s") ."%'";
		}
		if ($myRequest->get("v")!="")
		{
			$sql_cond2 = " (med_bez LIKE '%" . $myRequest->getS("v") ."%' OR med_keywords LIKE '%" . $myRequest->getS("v") ."%' OR med_comment LIKE '%" . $myRequest->getS("v") ."%')";
			if ($sql_cond!=""){$sql_cond.= " AND " .$sql_cond2;}else{$sql_cond=$sql_cond2;}
		}
		if ($myRequest->get("i")!="")
		{
			$sql_cond = " med_id=" . $myRequest->getI("i");
		}
		if ($sql_cond !=""){$sql_cond = "WHERE " . $sql_cond;}
		$sql = "SELECT * FROM media ". $sql_cond ." ORDER BY med_bez";
		$rs_data = $myDB->query($sql);
		$anzahl = mysql_num_rows($rs_data);
		$page=1;
		if ($myRequest->check("p")){$page = $myRequest->getI("p");}
		$sql = "SELECT * FROM media ". $sql_cond ." ORDER BY med_bez LIMIT ".(($page-1)*10).",10";
		$rs_data = $myDB->query($sql);
?>
    <table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowTabTypeOnly"><strong>Suche</strong></td>
        <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
      </tr>
    </table>
<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="window"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="tableHead">ID</td>
			<td width="70" class="tableHead">Abbildung</td>
            <td class="tableHead">Bezeichnung</td>
			<td width="80" class="tableHead">Gruppe/Ordner</td>
     		<td width="80" class="tableHead">Benutzer</td>
			<td width="10" class="tableHead">&nbsp;</td>
            <td width="20" align="right" class="tableHead">Aktion</td>
            </tr>
		  <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
		  <?php

		  while ($row_data=mysql_fetch_array($rs_data))
		  {
          ?>
          <tr>
            <td class="tableBody"><?php echo $row_data["med_id"] ?></td>
			
            <td class="tableBody">
			<a href="backend.php?page=Editor,Media,edit&id=<?php echo $row_data["med_id"] ?>&folder=<?php echo urlencode($row_data["med_logical_folder1"]) ?>&type=0&sortorder=1&p=1&a=10">
			<?php
			if ($row_data["med_type"]==1)
			{

				$myImg = new PhenoTypeImage($row_data["med_id"]);
				$myImg->display_ThumbX(60,$row_data["med_bez"]);
			}
			else
			{
				$icon = "binary";
				switch (strtolower($row_data["med_subtype"]))
				{
					case "gif":
					case "jpg":
					case "bmp":
					case "psd":
					case "png":
					case "jpeg":
						$icon = "image";
						break;

					case "wav":
					case "mid":
					case "mp3":
						$icon = "audio";
						break;

					case "pdf":
						$icon = "pdf";
						break;

					case "xls":
						$icon = "excel";
						break;

					case "doc":
						$icon = "word";
						break;

					case "ppt":
						$icon = "powerpoint";
						break;

					case "sql":
					case "txt":
						$icon = "text";
						break;

				}

				echo "&lt;".$icon."&gt;";
			}
		  ?>
		  </a>
		   </td>
            <td class="tableBody"><?php echo $row_data["med_bez"] ?></td>
			<td class="tableBody"><?php echo $_mediagroups[$row_data["grp_id"]] ?> / <?php echo $row_data["med_logical_folder1"] ?></td>
		    <td class="tableBody"><?php if($row_data["med_date"]!=0){echo date('d.m.Y H:i',$row_data["med_date"]);} ?><br><?php echo $myAdm->displayUser($row_data["usr_id"]); ?></td>
            <td>&nbsp;</td>
			<td align="right" nowrap class="tableBody"><a href="#" onclick="lightbox_switch(<?php echo $row_data["med_id"] ?>,0);return false;"><img src="img/b_pinadd.gif" alt="Objekt in Sammelbox legen / aus Sammelbox nehmen" title="Objekt in Sammelbox legen / aus Sammelbox nehmen" width="22" height="22" border="0" align="absmiddle"></a> <a href="backend.php?page=Editor,Media,edit&id=<?php echo $row_data["med_id"] ?>&folder=<?php echo urlencode($row_data["med_logical_folder1"]) ?>&type=0&sortorder=1&p=1&a=10"><img src="img/b_edit.gif" alt="Datensatz bearbeiten" width="22" height="22" border="0" align="absmiddle"></a></td>
            
            </tr>
          <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
            </tr>
<?php
		  }
?>			
          <tr>
            <td colspan="7" class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
          </tr>
        </table>        
        </td>
        <td width="10" valign="top" class="windowRightShadow">&nbsp;</td>
      </tr>
    </table>
	<?php

	$url = "backend.php?page=Editor,Media,search&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&a=" . $this->itemcount."&v=".$myRequest->getURL("v")."&s=".$myRequest->getURL("s")."&p=";

	echo $this->renderPageBrowser($page,$anzahl,$url);
	?>
	<table width="680" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="windowBottomShadow" width="670"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
        <td valign="top" class="windowRightShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></td>
      </tr>
    </table><br> 
	<?php

	return $myPT->stopBuffer();
	}

	function renderUpload()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$myPT->startBuffer();

		$this->displayJS_Lightbox();

		?>
		<form action="backend.php" method="post" enctype="multipart/form-data" name="editform" <?php if ($block_nr=="p"){ ?>onsubmit="return false;"<?php } ?>>
        <input type="hidden" name="page" value="Editor,Media,upload2">
        <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">
        <input type="hidden" name="folder" value="<?php echo $this->folder ?>">
		<input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
		<input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
		<input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
		<input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
		<input type="hidden" name="objecttype" value="<?php echo $type ?>">	
		<input type="hidden" name="b" value="1">
				   
		<?php
		$headline = "Mediaobjekte hochladen";
		$this->displayHeadline($headline);

		$this->tab_new();
		$url = "backend.php?page=Editor,Media,upload&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;
		$this->tab_addEntry("Eigenschaften", $url, "b_konfig.gif");
		$this->tab_draw("Eigenschaften");

		$this->workarea_start_draw();
		$html = $this->workarea_form_text("Titel","bez","");
		$html .= $this->workarea_form_text("Alternate","alt","");
		$this->workarea_row_draw("Bezeichnung",$html);

		$myPT->startBuffer();
  		?>
  		<input name="userfile" type="file" class="input"><br>
  		<input type="checkbox" value="1" name="documentonly"> Bilder als Dokumente handhaben    		
  		<?php
  		$html = $myPT->stopBuffer();
  		$this->workarea_row_draw("Bild / Dokument",$html);
  		$myPT->startBuffer();
		?>

		<select name="folder1" class="input" style="width:250px" onchange="document.forms.editform.folder1_new.value='';">
		<option value="_upload">...</option>
		<?php
		$myMB = new PhenotypeMediaBase();
		$_folder = $myMB->getLogicalFolder($this->_mediagroups);
		if (!in_array("_upload",$_folder))
		{
			$_folder[]="_upload";
			asort($_folder);
		}
		foreach ($_folder AS $k)
		{
			$selected="";
			if ($k=="_upload"){$selected='selected';}
		?>
		<option <?php echo $selected ?>><?php echo $k ?></option>
		<?php
		}
		?>
		</select>&nbsp;<input name="folder1_new" type="text" class="input" value="" style="width:200px" onfocus="document.forms.editform.folder1.selectedIndex=0;">
		<?php
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Ordner",$html);

		$html = $this->workarea_form_textarea("","keywords","",$r=4,$x=400,$br=1);
		$this->workarea_row_draw("Keywords",$html);

		$html = $this->workarea_form_textarea("","comment","",$r=4,$x=400,$br=1);
		$this->workarea_row_draw("Kommentar",$html);

		$myPT->startBuffer();
		?>
		<select name="grp_id_upload" class="input" style="width:120px">
		<?php
		if ($this->grp_id==0){$grp_id=2;}else{$grp_id=$this->grp_id;}
		foreach ($this->_mediagroups AS $k=>$v)
		{
			$selected="";
			if ($k == $grp_id){$selected='selected="selected"';} // Standard
		?>
		<option <?php echo $selected ?> value="<?php echo $k ?>"><?php echo $v ?></option>
		<?php
		}

		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Mediagruppe",$html);
		?>
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px"value="Hochladen">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
		$this->workarea_stop_draw();
		?>
		</form>				
		<?php
		return  $myPT->stopBuffer();
	}

	function renderImport_Step1()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$myPT->startBuffer();

		$this->displayJS_Lightbox();

		// should the upload box cleaned up?
		if ($myPT->getIPref("edit_media.auto_deleteimportbox"))
		{
			$save_path=MEDIABASEPATH. "/import/";
			if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
			$save_path.=$mySUser->id."/";
			if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
			$myAdm->removeDirComplete($save_path,1);
		}
		?>
		<form action="backend.php" method="post" enctype="multipart/form-data" name="editform" <?php if ($block_nr=="p"){ ?>onsubmit="return false;"<?php } ?>>
        <input type="hidden" name="page" value="Editor,Media,import">
        <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">
        <input type="hidden" name="folder" value="<?php echo $this->folder ?>">
		<input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
		<input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
		<input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
		<input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
		<input type="hidden" name="objecttype" value="<?php echo $type ?>">	
		<input type="hidden" name="b" value="1">
				   
		<?php
		$headline = "Mediaobjekte importieren";
		$this->displayHeadline($headline);

		$this->tab_new();
		$url = "backend.php?page=Editor,Media,import&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;
		$this->tab_addEntry("Upload", $url, "b_konfig.gif");
		$url = "backend.php?page=Editor,Media,import&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount."&b=1";
		$this->tab_addEntry("Import", $url, "b_konfig.gif");
		$this->tab_draw("Upload");

		$this->workarea_start_draw();



		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
             <td width="120" class="padding30" valign="top"><p><strong>&nbsp;</strong></p>
             </td>
             <td width="509" class="formarea">
             <?php
             if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
             {
			 ?>
			 <script type="text/javascript" src="backend.php?page=Editor,Media,import&b=99"></script>
			 <?php
             }else{
			 ?>
			 <script type="text/javascript"><?php echo $this->displayImport_Step1RadJS(); ?></script>
             <?php
             }
             ?>
			 </td>
        </tr>
        </table>
		
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px"value="Weiter">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
		$this->workarea_stop_draw();
		?>
		</form>				
		<?php		
		return $myPT->stopBuffer();
	}

	function displayImport_Step1RadJS()
	{

		global $myPT;
		global $mySUser;

		if ($myPT->getPref("backend.rad_upload")=="plus")
		{

			$width = 400;
			$height = 400;
			$id = "rad";
			$adUid = $_GET['adUid'];

			$pathToRad = ADMINFULLURL;
			$uploadUrl = ADMINFULLURL. "backend.php?page=Editor,Media,ddupload&usr_id=" . $mySUser->id ."&PHPSESSID=".session_id();
			$messageUrl = ADMINFULLURL. "backend.php?page=Editor,Media,import&b=98";
			$content.= 'var _info = navigator.userAgent;
        var ie = (_info.indexOf("MSIE") > 0);
        var win = (_info.indexOf("Win") > 0);
        if(win)
        {

            if(ie)
            {
		         document.writeln(\'<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"\');
		         document.writeln(\'      width= "'.$width.'" height= "'.$height.'" id="'.$id.'"\');
		         document.writeln(\'      codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">\');
            }
            else
            {
                document.writeln(\'<object type="application/x-java-applet;version=1.4.1"\');
                document.writeln(\'width= "'.$width.'" height= "'.$height.'"  id="'.$id.'" >\');
            }
            document.writeln(\'<param name="archive" value="'.$pathToRad.'dndplus.jar">\');
            document.writeln(\'<param name="code" value="com.radinks.dnd.DNDAppletPlus">\');
            document.writeln(\'<param name="name" value="Rad Upload Plus">\');

        }
        else
        {
            /* mac and linux */
            document.writeln(\'<applet \');
            document.writeln(\'         archive  = "'.$pathToRad.'dndplus.jar"\');
            document.writeln(\'         code     = "com.radinks.dnd.DNDAppletPlus"\');
            document.writeln(\'         name     = "Rad Upload Plus"\');
            document.writeln(\'         hspace   = "0"\');
            document.writeln(\'         vspace   = "0" MAYSCRIPT="yes"\');
            document.writeln(\'         width = "'.$width.'"\');
            document.writeln(\'         height = "'.$height.'"\');
            document.writeln(\'         align    = "middle" id="'.$id.'">\');
        }

/******    BEGIN APPLET CONFIGURATION PARAMETERS   ******/

	document.writeln(\'<param name="max_upload" value="0">\');
	document.writeln(\'<param name="message" value="'.$messageUrl.'">\');
	document.writeln(\'<param name="url" value="'.$uploadUrl.'" />\');
	//document.writeln(\'<param name="props_file" value="'.$pathToRad.'radupload_properties.php" />\');


/******    END APPLET CONFIGURATION PARAMETERS     ******/
       if(win)
	   {
		  document.writeln(\'</object>\');
	   }
	   else

	   {

		  document.writeln(\'</applet>\');
	   }

					'; // end $content =

			echo $content;


			return;
			
			// The following code is never executed.  Can be removed if no problem with the upper solution occurs
			if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
			{
            ?>
			document.write ('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "400" height= "400" cobase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">');
			<?php
			}
			else
			{
			?>
			document.write ('<object type="application/x-java-applet;version=1.4.1" width= "400" height= "400" >');
			<?php	
			}
			?>
			document.write ('<param name="archive" value="<?php echo ADMINFULLURL ?>dndplus.jar">');
			document.write ('<param name="code" value="com.radinks.dnd.DNDAppletPlus">');
			document.write ('<param name="name" value="Rad Upload Plus">');
		   	document.write ('<param name = "url" value = "<?php echo ADMINFULLURL ?>backend.php?page=Editor,Media,ddupload&usr_id=<?php echo $mySUser->id ?>&PHPSESSID=<?php echo session_id() ?>">'); 
		   	document.write ('<param name = "url" value = "<?php echo ADMINFULLURL ?>backend.php?page=Editor,Media,ddupload&usr_id=<?php echo $mySUser->id ?>&PHPSESSID=<?php echo session_id() ?>">'); 
   			document.write ('<param name = "message" value="<?php echo ADMINFULLURL ?>backend.php?page=Editor,Media,import&b=98">');
 		  	<?php
 		  	if (isset ($_SERVER['PHP_AUTH_USER']))
 		  	{
 		  		?>
 		  		document.write ('<?php echo printf('<param name="chap" value="%s">', base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'])); ?>');
 		  		<?php
 		  	}
			?>
			document.write ('</object>');
			<?php

		}
		else // DND Lite
		{
			if(strstr($_ENV["HTTP_USER_AGENT"],"MSIE") OR strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
			{
            ?>
			document.write ('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "400" height= "400" cobase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">');
			<?php
			}
			else
			{
			?>
			document.write ('<object type="application/x-java-applet;version=1.4.1" width= "400" height= "400" >');
			<?php	
			}
			?>
			document.write ('<param name="archive" value="<?php echo ADMINFULLURL ?>dndlite.jar">');
			document.write ('<param name="code" value="com.radinks.dnd.DNDAppletLite">');
			document.write ('<param name="name" value="Rad Upload Lite">');
		   	document.write ('<param name = "url" value = "<?php echo ADMINFULLURL ?>backend.php?page=Editor,Media,ddupload&usr_id=<?php echo $mySUser->id ?>&PHPSESSID=<?php echo session_id() ?>">'); 
   			document.write ('<param name = "message" value="<br\>&nbsp;Drag & Drop - Upload">');
 		  	<?php
 		  	if (isset ($_SERVER['PHP_AUTH_USER']))
 		  	{
 		  		?>
 		  		document.write ('<?php echo printf('<param name="chap" value="%s">', base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'])); ?>');
 		  		<?php
 		  	}
			?>
			document.write ('</object>');
			<?php
		}


	}

	function renderImport_Step2()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;


		$myMB = new PhenotypeMediabase();
		$myPT->startBuffer();

		$this->displayJS_Lightbox();


		?>
		<form action="backend.php" method="post" enctype="multipart/form-data" name="editform" <?php if ($block_nr=="p"){ ?>onsubmit="return false;"<?php } ?>>
        <input type="hidden" name="page" value="Editor,Media,import2">
        <input type="hidden" name="grp_id" value="<?php echo $this->grp_id ?>">
        <input type="hidden" name="folder" value="<?php echo $this->folder ?>">
		<input type="hidden" name="type" value="<?php echo $this->type ?>">				   				   
		<input type="hidden" name="sortorder" value="<?php echo $this->sortorder ?>">
		<input type="hidden" name="p" value="<?php echo $this->pagenr ?>">		  
		<input type="hidden" name="a" value="<?php echo $this->itemcount ?>">
		<input type="hidden" name="objecttype" value="<?php echo $type ?>">	
				   
		<?php
		$headline = "Mediaobjekte importieren";
		$this->displayHeadline($headline);

		$this->tab_new();
		$url = "backend.php?page=Editor,Media,import&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount;
		$this->tab_addEntry("Upload", $url, "b_konfig.gif");
		$url = "backend.php?page=Editor,Media,import&grp_id=".$this->grp_id."&folder=".urlencode($this->folder)."&type=".$this->type."&sortorder=" . $this->sortorder ."&p=1&a=" . $this->itemcount."&b=1";
		$this->tab_addEntry("Import", $url, "b_konfig.gif");
		$this->tab_draw("Import");
		$this->workarea_start_draw();


		$this->workarea_row_headline("Dateien");
		if (!file_exists(MEDIABASEPATH . "/import" ))
		{
			mkdir (MEDIABASEPATH . "/import" ,UMASK);
		}
		if (!file_exists(MEDIABASEPATH . "/import/" . $mySUser->id ))
		{
			mkdir (MEDIABASEPATH . "/import/" . $mySUser->id ,UMASK);
		}
		$path = MEDIABASEPATH . "/import/" . $mySUser->id;
		$fp=opendir($path);
		$html="";
		$n=0;
		while ($file = readdir ($fp))
		{
			if ($file != "." && $file != ".." && (!is_dir($path."/".$file)))
			{
				$n++;

				$titel = $file;
				$keywords ="";
				$comment = "";
				$ordner= "";
				getimagesize($path."/".$file,$info);
				if (isset ($info["APP13"]))
				{
					$iptc = iptcparse($info["APP13"]);

					$titel = @ $iptc["2#105"][0];
					$keywords = @ $iptc["2#020"][0];
					$comment = $myMB->iptc($info);
				}

				$html = '<strong>'.$myPT->codeH($file).'</strong><br/>';
				$name = "title_".sha1($file);
				$html .= $this->workarea_form_text("Titel",$name,$titel);



				$myPT->startBuffer();
				$name = "folder_".sha1($file);
				?>
				Ordner<br/>
				<select name="<?php echo $name ?>" class="input" style="width:200px" onchange="document.forms.editform.<?php echo $name ?>_new.value='';">
				<option value="" selected>...</option>
				<?php
				$_folder = $myMB->getLogicalFolder($this->_mediagroups);
				if (!in_array("_import",$_folder))
				{
					$_folder[]="_import";
					asort($_folder);
				}
				foreach ($_folder AS $k)
				{
				?>
				<option><?php echo $k ?></option>
				<?php
				}
				?>
				</select>&nbsp;<input name="<?php echo $name ?>_new" type="text" class="input" value="" style="width:250px" onfocus="document.forms.editform.<?php echo $name ?>.selectedIndex=0;"><br/>
				<?php
				$html .= $myPT->stopBuffer();


				$name = "keywords_".sha1($file);
				$html .= $this->workarea_form_text("Keywords",$name,$keywords);
				$name = "comment_".sha1($file);
				$html .= $this->workarea_form_textarea("Kommentar",$name,$comment,6);

				//$this->workarea_row_draw("",$html);
				?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
		          <tr>
		            <td valign="top" class="padding30" width="120">
		            <p><strong>
		            <?php
		            if (substr($file,-4)==".jpg")
		            {
		            ?>
		            <img src="backend.php?page=Editor,Media,streamImportPreviewImage&file=<?php echo urlencode($file) ?>">
		            <?php
		            }
					?>
		            </strong></p>
		            </td>
		            <td class="formarea"><p><?php echo $html ?></p></td>
		
		          </tr>
		          <tr>
		            <td colspan="2" nowrap class="tableHline"><img src="img/white_border.gif" width="3" height="3"></td>
		          </tr>
				</table>
				<?php
			}
		}
		if ($n==0)
		{
			$this->workarea_row_draw("","Keine Dateien zum Importieren vorhanden.");
		}



		$this->workarea_row_headline("Gemeinsame Eigenschaften",true);

		// Mediagroup

		$myPT->startBuffer();
		?>
		<select name="grp_id_import" class="input" style="width:120px">
		<?php
		if ($this->grp_id==0){$grp_id=2;}else{$grp_id=$this->grp_id;}
		foreach ($this->_mediagroups AS $k=>$v)
		{
			$selected="";
			if ($k == $grp_id){$selected='selected="selected"';} // Standard
		?>
		<option <?php echo $selected ?> value="<?php echo $k ?>"><?php echo $v ?></option>
		<?php
		}

		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Mediagruppe",$html);

		// handling of images/documents

		$myPT->startBuffer();
		?>
		<input type="checkbox" value="1" name="documentonly"> Bilder als Dokumente handhaben    		
		<?php
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Bild / Dokument",$html);

		$myPT->startBuffer();


		?>
		<select name="folder1" class="input" style="width:200px" onchange="document.forms.editform.folder1_new.value='';">
		<option value="_import">...</option>
		<?php
		$myMB = new PhenotypeMediaBase();
		$_folder = $myMB->getLogicalFolder($this->_mediagroups);
		if (!in_array("_import",$_folder))
		{
			$_folder[]="_import";
			asort($_folder);
		}
		foreach ($_folder AS $k)
		{
			$selected="";
			if ($k=="_import"){$selected='selected';}

		?>
		<option <?php echo $selected ?>><?php echo $k ?></option>
		<?php
		}
		?>
		</select>&nbsp;<input name="folder1_new" type="text" class="input" value="" style="width:250px" onfocus="document.forms.editform.folder1.selectedIndex=0;">
		<?php
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Ordner",$html);

		$html = $this->workarea_form_textarea("","keywords","",$r=4,$x=400,$br=1);
		$this->workarea_row_draw("Keywords",$html);

		$html = $this->workarea_form_textarea("","comment","",$r=4,$x=400,$br=1);
		$this->workarea_row_draw("Kommentar",$html);

		$myPT->startBuffer();
		?>
		<input type="checkbox" value="1" name="lightbox"> Medien in die Sammelbox aufnehmen    		
		<?php
		$html = $myPT->stopBuffer();
		$this->workarea_row_draw("Sammelbox",$html);

		?>
   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">
		    &nbsp;
			</td>
            <td align="right" class="windowFooterWhite"><input type="submit" class="buttonWhite" style="width:102px"value="Importieren">&nbsp;&nbsp;</td>
          </tr>
        </table>
		<?php
		$this->workarea_stop_draw();
		?>
		</form>				
		<?php
		return $myPT->stopBuffer();
	}

	function update($block_nr)
	{
		global $myRequest;
		global $myPT;
		global $myDB;
		global $mySUser;
		global $myLog;

		//$myRequest->printR();

		$img_id=$myRequest->getI("id");
		$ver_id=$myRequest->getI("ver_id");

		$_params = Array();
		$_params["id"]=$img_id;
		$_params["b"]=$block_nr;
		$_params["folder"]=$this->folder;
		$_params["type"]=$this->type;
		$_params["sortorder"]=$this->sortorder;
		$_params["p"]=$this->pagenr;
		$_params["a"]=$this->itemcount;
		$this->_params = $_params;

		$mySQL = new SQLBuilder();
		$mySQL->addField("med_date", time(), DB_NUMBER);
		$mySQL->addField("usr_id", $mySUser->id, DB_NUMBER);
		$sql = $mySQL->update("media", "med_id =".$img_id);
		$myDB->query($sql);

		switch ($block_nr)
		{
			case 0:
				$this->updateMediaObject();
				break;
			case 1:

				$this->updateMediaObjectVersions();
				break;
			case 2:

				$this->updatePainter($img_id,$ver_id);


				break;
		}

		$myLog->log("Mediaobjekt " . $img_id . " bearbeitet.",PT_LOGFACILITY_SYS);

		// Snapshot
		if ($myPT->getIPref("edit_media.build_snapshot")==1)
		{
			$myObj = new PhenotypeImage($img_id);
			if ($myObj->id != 0)
			{
				// Bild
				$type = MB_IMAGE;
			} else
			{
				$type = MB_DOCUMENT;
				$myObj = new PhenoTypeDocument($img_id);

				if ($myObj->id == 0)
				{
					$this->noAccess();
				}
			}
			$myObj->snapshot($mySUser->id);
		}
		$this->gotoPage("Editor","Media","edit",$this->_params);
	}

	function renderPreview()
	{
		global $myPT;
		global $myRequest;


		$myPT->startBuffer();

		$img_id = $myRequest->getI("img_id");
		$ver_id = $myRequest->getI("ver_id");
		?>
		<script type="text/javascript">
		self.focus();
		</script>
		<?php
		$image = $this->smallphotoshop($img_id,$ver_id);

		if ($image!=false)
		{
			$targetfile = TEMPPATH ."media/~preview_".$img_id .".jpg";
			$quality= $myRequest->getI("size_quality");
			ImageJPEG($image, $targetfile,$quality);
			@ chown($targetfile, UMASK);

			list($x, $y) = getimagesize($targetfile);
		  ?>
		  <div style="width:<?php echo $x ?>px;padding:15px">
		  <img src="backend.php?page=Editor,Media,streamPainterPreviewImage&img_id=<?php echo $img_id ?>" width="<?php echo $x ?>" height="<?php echo $y ?>" border="1" alt="Preview" title="Preview"><br/>
		  <span style="text-align:right"><p>
		  <?php echo $x ?> x <?php echo $y ?> (<?php echo (int)(filesize($targetfile)/1024) ?> kb)
		  </p>
		  </span>
		  </div>
		  <?php

		}

		return $myPT->stopBuffer();

	}

	function streamImage()
	{
		global $myRequest;
		global $mySUser;
		$img_id = $myRequest->getI("img_id");
		$ver_id = $myRequest->getI("ver_id");

		$myImg = new PhenotypeImage($img_id);
		$myImg->selectVersionID($ver_id);

		if ($myImg->loaded==false)
		{
			$myImg = new PhenotypeImage(4);
		}
		else
		{
			if (!$mySUser->hasRight("access_mediagrp_".$myImg->grp_id))
			{
				$myImg = new PhenotypeImage(4);
			}
		}

		$file = $myImg->file;
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: image/jpg");

		echo @file_get_contents($file);
		exit();
	}

	function streamPainterPreviewImage()
	{
		global $myRequest;
		$img_id = $myRequest->getI("img_id");

		$file = TEMPPATH ."media/~preview_".$img_id .".jpg";
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: image/jpg");

		echo @file_get_contents($file);
		@unlink ($file);
	}

	function streamImportPreviewImage()
	{
		global $mySUser;
		global $myRequest;

		$file = $myRequest->get("file");
		$file = MEDIABASEPATH . "import/".$mySUser->id . "/".$file;

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: image/jpg");

		$myMB = new PhenotypeMediabase();
		$image = $myMB->createThumbnailFromJpeg($file,"",90,0,60);
		echo $image;

	}

	function updateMediaObject()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;



		$id = $myRequest->getI("id");

		$myObj = new PhenotypeImage($id);
		if ($myObj->id != 0)
		{
			// Bild
			$type = MB_IMAGE;
		} else
		{
			$type = MB_DOCUMENT;
			$myObj = new PhenoTypeDocument($id);
		}

		if (!$myObj->loaded)
		{
			$this->noAccess();
		}

		if ($myRequest->check("delete"))
		{
			$myMB = new PhenotypeMediabase();
			$myMB->deleteMediaObject($id);
			$this->_params["info"]="Mediaobjekt gelöscht.";
			$this->gotoPage("Editor","Media","browse",$this->_params);
		}


		$this->_params["feedback"]="Änderungen gespeichert.";

		$sql = "SELECT grp_bez,grp_type FROM mediagroup WHERE grp_id=".$myObj->grp_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$grp_type = $row["grp_type"];


		$mySQL = new SQLBuilder();

		$mySQL->addField("med_bez", $myRequest->get("bez"));
		if ($_REQUEST["objecttype"] == MB_IMAGE)
		{
			$mySQL->addField("med_alt", $myRequest->get("alt"));
		}
		$mySQL->addField("med_keywords", $myRequest->get("keywords"));
		$mySQL->addField("med_comment", $myRequest->get("comment"));

		$myMB = new PhenotypeMediabase();
		$myMB->setMediaGroup($myObj->grp_id);
		$folder1_new = $myMB->rewriteFolder($myRequest->get("folder1_new"));
		$folder2_new = $myMB->rewriteFolder($myRequest->get("folder2_new"));
		$folder3_new = $myMB->rewriteFolder($myRequest->get("folder3_new"));

		if ($folder1_new != "")
		{
			$folder = $folder1_new;
		} else
		{
			$folder = $myRequest->get("folder1");
		}
		$mySQL->addField("med_logical_folder1", $folder);

		$redirect_folder = $folder;

		if ($folder2_new != "")
		{
			$folder = $folder2_new;
		} else
		{
			$folder = $myRequest->get("folder2");
		}

		$mySQL->addField("med_logical_folder2", $folder);

		if ($folder3_new != "")
		{
			$folder = $folder3_new;
		} else
		{
			$folder = $myRequest->get("folder3");
		}
		$mySQL->addField("med_logical_folder3", $folder);



		$sql = $mySQL->update("media", "med_id =".$id);

		$myDB->query($sql);


		$fname = "userfile";
		$size = $_FILES[$fname]["size"];


		if ($myRequest->get("objecttype")==MB_IMAGE AND $size !=0)
		{
			// check if the new uploaded file is an image
			$isize = getimagesize($_FILES[$fname]["tmp_name"]);
			if ($isize[2] < 1 OR $isize[2] > 3)
			{
				$this->_params["error"]="Datei wurde nicht ausgetauscht! Eine Bilddokument kann nur durch Bilder vom Typ JPG/GIF/PNG ersetzt werden.";
				$this->_params["feedback"]="";
				$size=0;
			}
		}
		if ($size != 0)
		{
			if ($grp_type == 3) // archivierend
			{
				if ($myRequest->get("objecttype") == MB_IMAGE)
				{
					$myMB->importImageVersionFromUrl($myObj->file,date ('Y/m/d H:i'), $id);
					$myMB->uploadImageOldID($fname, $id);
				} else
				{
					$myMB->importDocumentVersionFromURL($myObj->file,date ('Y/m/d H:i'), $id);
					$myMB->uploadDocumentOldID($fname, $id);
				}
			} else
			{
				$myMB = new PhenotypeMediaBase();
				$myMB->setMediaGroup($myObj->grp_id);
				if ($myRequest->get("objecttype") == MB_IMAGE)
				{
					$myMB->uploadImageOldID($fname, $id);
				} else
				{
					$myMB->uploadDocumentOldID($fname, $id);
				}
			}

		}


		$fname = "versionfile";
		$size = $_FILES[$fname]["size"];
		if ($size != 0)
		{
			$myMB = new PhenotypeMediaBase();
			$myMB->setMediaGroup($myObj->grp_id);


			$this->_params["info"]="Erste Version hinzugefügt.";
			$this->_params["feedback"]="";
			$this->_params["b"]=1;

			if ($_REQUEST["objecttype"] == MB_IMAGE)
			{
				$rc = $myMB->uploadImageVersion($fname, $myRequest->get("ver_bez"), $id);
				if ($rc == false)
				{
					$this->_params["alert"] = "Fehler beim Hochladen einer Bildversion. Achtung! Versionen eines Bildes müssen immer vom Typ JPEG, GIF oder PNG sein.";
					$this->_params["feedback"]="";
					$this->_params["info"]="";
				}
			} else
			{
				$myMB->uploadDocumentVersion($fname, $myRequest->get("ver_bez"), $id);
			}


		}


		//$this->gotoPage("Editor","Media","edit",$this->_params);

	}

	function updateMediaObjectVersions()
	{


		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$id = $myRequest->getI("id");

		$myObj = new PhenotypeImage($id);
		if ($myObj->id != 0)
		{
			// Bild
			$type = MB_IMAGE;
		} else
		{
			$type = MB_DOCUMENT;
			$myObj = new PhenoTypeDocument($id);
		}

		if (!$myObj->loaded)
		{
			$this->noAccess();
		}

		$this->_params["feedback"]="Änderungen gespeichert.";

		$sql = "SELECT * FROM mediaversion WHERE med_id = ".$id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$bez = trim($myRequest->get("ver".$row["ver_id"]."_bez"));

			if ($bez=="")
			{
				$this->_params["alert"] .= "Ungültiger Versionsname.<br/>";
				$this->_params["feedback"]="";
			}
			if ($bez != $row["ver_bez"] AND $bez!="")
			{
				// version name should be changed, check for duplicates


				$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE ver_bez='" . $myPT->codeSQL($bez)."' AND med_id=".$id;
				$rs2 = $myDB->query($sql);
				$row2 = mysql_fetch_array($rs2);
				if ($row2["C"]!=0)
				{
					$this->_params["alert"] .=  "Versionsbezeichnung <b>" . $row["ver_bez"] . "</b> kann nicht nach <b>" . $bez . "</b> geändert werden. Versionsname bereits vorhanden.\n";
					$this->_params["feedback"]="";
				}
				else
				{
					$mySQL = new SQLBuilder();
					$mySQL->addField("ver_bez", $bez);
					$sql = $mySQL->update("mediaversion", "ver_id=".$row["ver_id"]);
					$myDB->query($sql);
				}
			}
			$fname = "ver".$row["ver_id"]."_delete_x";
			if ($myRequest->check($fname))
			{
				$myMB = new PhenotypeMediaBase();
				$myMB->deleteMediaObjectVersion($id, $row["ver_id"]);
				$this->_params["info"]="Version gelöscht.";
				$this->_params["feedback"]="";
			}
		}

		// Wurden neue Dateien hochgeladen?
		for ($i = 1; $i <= 3; $i ++)
		{
			$fname = "versionfile".$i;
			$size = $_FILES[$fname]["size"];
			if ($size != 0)
			{
				$myMB = new PhenotypeMediaBase();
				$myMB->setMediaGroup($myObj->grp_id);
				if ($_REQUEST["objecttype"] == MB_IMAGE)
				{
					$rc = $myMB->uploadImageVersion($fname, $myRequest->get("ver_bez".$i), $id);
					if ($rc == false)
					{
						$this->_params["alert"] .= "Fehler beim Hochladen einer Bildversion. Achtung! Versionen eines Bildes müssen immer vom Typ JPEG, GIF oder PNG sein.";
						$this->_params["feedback"]="";
					}
				} else
				{
					$myMB->uploadDocumentVersion($fname, $myRequest->get("ver_bez".$i), $id);
				}
			}
		}
	}


	function updatePainter($img_id,$ver_id)
	{
		global $myRequest;
		global $myDB;
		global $myPT;



		$this->_params["ver_id"]=$ver_id;

		if ($myRequest->getI("deleteversion")==1)
		{
			$myMB = new PhenotypeMediabase();
			$myMB->deleteMediaObjectVersion($img_id,$ver_id);
			$this->_params["info"]="Version gelöscht";
			$this->_params["ver_id"]=0;
			return;
		}

		$image = $this->smallphotoshop($img_id,$ver_id);




		if ($image!=false)
		{
			$targetfile = TEMPPATH ."media/~build_".$img_id .".jpg";

			$quality= $myRequest->getI("size_quality");

			ImageJPEG($image, $targetfile,$quality);
			@ chown($targetfile, UMASK);


			$url = $targetfile;

			$myMB = new PhenotypeMediaBase();

			$action = $myRequest->get("size_versionaction");
			$ver_bez= trim($myRequest->get("size_newversion"));


			if ($action==0)
			{

				if ($ver_bez!="")
				{
					$sql = "SELECT ver_id FROM mediaversion WHERE med_id=" .$img_id." AND ver_bez='".$myPT->codeSQL($ver_bez)."'";

					$rs = $myDB->query($sql);
					if (mysql_num_rows($rs)!=0)
					{
						$row = mysql_fetch_array($rs);
						$ver_id = $row["ver_id"];
					}
					else
					{
						$ver_id=0;
					}
				}
				else
				{
					if ($ver_id!=0)
					{
						$sql = "SELECT * FROM mediaversion WHERE ver_id=" .$ver_id;
						$rs = $myDB->query($sql);
						if (mysql_num_rows($rs)!=0)
						{
							$row = mysql_fetch_array($rs);
							$ver_bez = $row["ver_bez"];
						}
						$this->_params["feedback"]="Änderungen gespeichert.";
					}
					else
					{
						$this->_params["alert"] = "Original kann nicht überschrieben werden. Es wird eine neue Version angelegt<br/>";
					}
				}
			}
			else
			{
				$ver_id=0;
			}

			//echo $ver_id . $ver_bez;
			//exit();

			$rc = $myMB->importImageVersionFromUrl($url, $ver_bez, $img_id,$ver_id);
			$this->_params["ver_id"]=$rc;

			@unlink($url);
		}
	}

	function smallphotoshop($img_id,$ver_id)
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;

		$_params = $this->_params;

		$myObj = new PhenotypeImage($img_id);
		if ($myObj->id != 0)
		{
			// Bild
			$type = MB_IMAGE;
		}

		if (!$myObj->loaded){$this->noAccess();}

		if ($myObj->selectVersionID($ver_id)===false){$this->noAccess();}

		$myMB = new PhenotypeMediaBase();
		$myMB->setMediaGroup($myObj->grp_id);


		$size = GetImageSize($myObj->file);

		if ($size==false)
		{
			$this->_params["error"] = "Probleme beim Öffnen der Ausgangsdatei. Bildformat wird nicht unterstützt.";
			return;
		}

		switch ($size["mime"])
		{
			case "image/gif":
				$sourceImage = imagecreatefromgif($myObj->file);
				break;
			case "image/jpeg":
				$sourceImage = imagecreatefromjpeg($myObj->file);
				break;
			case "image/png":
				$sourceImage = imagecreatefrompng($myObj->file);
				break;
			default:
				$this->_params["error"] = "Unbekannter Mimetype (".$size["mime"]."). Bildformat wird nicht unterstüzt. Operation abgebrochen.";
				return;
				break;
		}

		$sx = $myRequest->getI("crop_x");
		$sy = $myRequest->getI("crop_y");
		$sw = $myRequest->getI("crop_width");
		$sh = $myRequest->getI("crop_height");
		$tx = 0;
		$ty = 0;
		$tw = $myRequest->getI("crop_width");
		$th = $myRequest->getI("crop_height");


		$method = $myRequest->getI("size_method");

		switch ($method)
		{
			case 2: // fixer Ausschnitt
			case 4: // fixe Zielgröße
			$tw = $myRequest->getI("size_x");
			$th = $myRequest->getI("size_y");
			break;
			case 5: // Zielrahmen
			$zw = $myRequest->getI("size_x");
			$zh = $myRequest->getI("size_y");

			if ($zw==0 OR $zh==0)
			{
				$this->_params["alert"]="Fehlerhafte Formatwahl. Keine gültige Größe für Zielrahmen angegeben. Skalierung wurde nicht durchgeführt.";
				//$this->gotoPage("Editor","Media","edit",$_params);
			}
			else
			{
				$r = $zw/$sw;


				if ($sh*$r<=$zh) // Breite passt
				{
					$tw = $zw;
					$th = (int)($sh*$r);

				}
				else // Höhe
				{
					$r = $zh/$sh;
					$th = $zh;
					$tw = (int)($sw*$r);
				}
			}

			break;

		}




		$targetImage = imagecreatetruecolor($tw, $th);


		if (function_exists(imagecopyresampled))
		{
			imagecopyresampled($targetImage, $sourceImage, $tx, $ty, $sx, $sy, $tw, $th, $sw, $sh);
		} else
		{
			imagecopyresized($targetImage, $sourceImage, $tx, $ty, $sx, $sy, $tw, $th, $sw, $sh);
		}


		// Nachschärfen
		switch ($myRequest->get("size_sharpening"))
		{
			case 1:
				$targetImage = $myMB->unsharpMask($targetImage,40,0.5,3);
				break;
			case 2:
				$targetImage = $myMB->unsharpMask($targetImage,80,0.5,3);
				break;
			case 3:
				$targetImage = $myMB->unsharpMask($targetImage,140,0.5,3);
				break;
			case 4:
				$targetImage = $myMB->unsharpMask($targetImage,180,0.5,3);
				break;

		}

		return ($targetImage);

	}



	function updateOie()
	{
		// OIE
		$buffer = base64_decode($_REQUEST["imagedata64"]);
		//echo $buffer;
		$myImg = new PhenotypeImage($id);
		$dateiname = MEDIABASEPATH.$myImg->physical_folder."/".$myImg->filename;
		$fp = fopen($dateiname, "wb");
		fputs($fp, $buffer);
		fclose($fp);

		$size = GetImageSize($dateiname, $info);
		$mySQL = new SQLBuilder();
		$mySQL->addField("med_x", $size[0], DB_NUMBER);
		$mySQL->addField("med_y", $size[1], DB_NUMBER);

		// Thumbnail
		if ($size[2] == 2)
		{
			$dateiname_thumb = MEDIABASEPATH.$myImg->physical_folder."/".sprintf("%06.0f", $id)."_t.".$myImg->suffix;
			$myMB = new PhenotypeMediabase();
			$myMB->createThumbnailFromJPEG($dateiname, $dateiname_thumb);
			$mySQL->addField("med_thumb", 1, DB_NUMBER);
		} else
		{
			$mySQL->addField("med_thumb", 0, DB_NUMBER);
		}

		$mySQL->addField("med_date", time(), DB_NUMBER);
		$mySQL->addField("usr_id", $_SESSION["usr_id"], DB_NUMBER);
		$sql = $mySQL->update("media", "med_id=".$id);

		$redirect_folder = $myRequest->get("folder");
	}


	function uploadMediaobject()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;
		global $myApp;


		$_params = Array();
		$_params["folder"]=$this->folder;
		$_params["type"]=$this->type;
		$_params["sortorder"]=$this->sortorder;
		$_params["p"]=$this->pagenr;
		$_params["a"]=$this->itemcount;
		$this->_params = $_params;


		$fname = "userfile";

		$dateiname_original =  $_FILES[$fname]["name"];
		$suffix = strtolower(substr($dateiname_original,strrpos($dateiname_original,".")+1));

		$myMB = new PhenotypeMediabase();
		$myMB->setMediaGroup($myRequest->getI("grp_id_upload"));
		$folder1_new = $myMB->rewriteFolder($myRequest->get("folder1_new"));

		if ($folder1_new!="")
		{
			$folder = $folder1_new;
		}
		else
		{
			$folder= $myRequest->get("folder1");
		}


		$type = MB_DOCUMENT;
		$_suffix = Array("jpg","gif","jpeg","png");
		if (in_array($suffix,$_suffix))
		{
			$type = MB_IMAGE;
			if (isset($_REQUEST["documentonly"]))
			{
				$type = MB_DOCUMENT;
			}
		}

		$iptc="";
		if ($type== MB_IMAGE)
		{
			$id = $myMB->uploadImage($fname,$folder);
			if ($id)
			{
				$sql = "SELECT med_comment FROM media WHERE med_id = " . $id;
				$rs = $myDB->query($sql);
				$row=mysql_fetch_array($rs);
				$iptc = $row["med_comment"];
			}
		}
		else
		{
			$id = $myMB->uploadDocument($fname,$folder);
		}

		if (!$id) // Hochladen fehlgeschlagen
		{
			$this->_params["error"]="Upload fehlgeschlagen!";
			$this->gotoPage("Editor","Media","browse",$this->_params);
		}

		// EIGENSCHAFTEN


		$mySQL = new SQLBuilder();

		if ($_REQUEST["bez"]!="")
		{
			$mySQL->addField("med_bez",$_REQUEST["bez"]);
		}
		if ($type==MB_IMAGE)
		{
			$mySQL->addField("med_alt",$_REQUEST["alt"]);
		}
		$mySQL->addField("med_keywords",$_REQUEST["keywords"]);

		if ($_REQUEST["comment"] !="" AND $iptc !="")
		{
			$mySQL->addField("med_comment",$_REQUEST["comment"]."\n\n".$iptc);
		}
		else
		{
			$mySQL->addField("med_comment",$_REQUEST["comment"].$iptc);
		}

		$sql = $mySQL->update("media","med_id =" . $id);
		$myDB->query($sql);

		$myApp->onUploadMediaObject($id,$type);

		$_params = Array();
		$this->_params["folder"]=$folder;
		$this->_params["grp_id"]=$myRequest->getI("grp_id_upload");
		$this->_params["type"]=0;
		$this->_params["sortorder"]=1;
		$this->_params["p"]=1;
		$this->_params["info"]="Dokument hochgeladen.";
		$this->gotoPage("Editor","Media","browse",$this->_params);
	}

	function importMediaobjects()
	{
		global $myDB;
		global $myAdm;
		global $mySUser;
		global $myRequest;
		global $myPT;
		global $myApp;

		set_time_limit(0);

		$myMB = new PhenotypeMediabase();
		$myMB->setMediaGroup($myRequest->getI("grp_id_import"));

		$comment = $myRequest->get("comment");
		$keywords = $myRequest->get("keywords");


		// Determine selection of common folder. If it's "_import" ignore it at first ...

		$folder1_new = $myMB->rewriteFolder($myRequest->get("folder1_new"));
		if ($folder1_new!="")
		{
			$folder = $folder1_new;
		}
		else
		{
			$folder= $myRequest->get("folder1");
		}
		if ($folder=="_import"){$folder="";}
		$folder1 =$folder;

		$lightbox = $myRequest->getI("lightbox");
		if ($lightbox==1)
		{
			$myDO = new PhenotypeDataObject("system.lightbox_media_usr_id_".$mySUser->id);
			$_objects = $myDO->get("objects");
		}

		$fp=opendir(MEDIABASEPATH . "/import/" . $mySUser->id);

		while ($file = readdir ($fp))
		{
			if ($file != "." && $file != "..")
			{
				$suffix = strtolower(substr($file,strrpos($file,".")+1));
				$type = MB_DOCUMENT;
				if ($suffix == "jpg" OR $suffix == "gif" OR $suffix = "jpeg" OR $suffix =="png")
				{
					$type = MB_IMAGE;
				}

				if ($myRequest->check("documentonly"))
				{
					$id = $myMB->import($file,1);
					$type = MB_DOCUMENT;
				}
				else
				{
					$id = $myMB->import($file,0);
				}

				// EIGENSCHAFTEN
				$mySQL = new SQLBuilder();

				$mySQL->addField("med_bez",$myRequest->get("title_".sha1($file)));

				/*
				if ($type==MB_IMAGE)
				{
				$mySQL->addField("med_alt",$_REQUEST["alt"]);
				}
				*/

				$filekeywords = $myRequest->get("keywords_".sha1($file));

				if ($filekeywords=="")
				{
					$filekeywords = $keywords;
				}
				else
				{
					$filekeywords .=  " " . $keywords;
				}

				$mySQL->addField("med_keywords",$filekeywords);

				$filecomment = $myRequest->get("comment_".sha1($file));
				if ($comment !="")
				{
					if ($filecomment=="")
					{
						$filecomment = $comment;
					}
					else
					{
						$filecomment = $comment . "\n\n" . $filecomment;
					}
				}

				$mySQL->addField("med_comment",$filecomment);


				$folder2_new = $myMB->rewriteFolder($myRequest->get("folder_".sha1($file)."_new"));
				if ($folder2_new!="")
				{
					$folder2 = $folder2_new;
				}
				else
				{
					$folder2= $myRequest->get("folder_".sha1($file));
				}

				if ($folder2=="")
				{
					if ($folder1==""){$folder1="_import";}
				}
				else
				{
					if ($folder1 =="")
					{
						$folder1=$folder2;
						$folder2="";
					}
					if ($folder2==$folder1)
					{
						$folder2="";
					}
				}

				$mySQL->addField("med_logical_folder1",$folder1);
				$mySQL->addField("med_logical_folder2",$folder2);


				$sql = $mySQL->update("media","med_id =" . $id);
				$myDB->query($sql);
				$myApp->onUploadMediaObject($id,$type);

				// add mediaobject to the lightbox
				if ($lightbox==1)
				{
					$_objects[$id]=$id;
				}
			}
		}
		closedir($fp);

		if ($lightbox==1)
		{
			$myDO->set("objects",$_objects);
			$myDO->store();
		}

		//$url = "mediabase.php?folder=" . $_REQUEST["folder"] ."&type=" . $_REQUEST["type"] . "&sortorder=" .$_REQUEST["sortorder"] ."&p=" . $_REQUEST["p"] ."&a=" . $_REQUEST["a"];

		$_params = Array();
		$_params["grp_id"]=$myRequest->getI("grp_id_import");
		$this->gotoPage("Editor","Media","browse",$_params);

	}

	function ddupload()
	{
		global $myRequest;
		?>
		<table width="240" border="0" cellpadding="2" cellspacing="2">
		<?php
		$save_path=MEDIABASEPATH. "/import/";
		if(!file_exists($save_path)){mkdir ($save_path,UMASK);}
		$save_path.=$myRequest->getI("usr_id")."/";
		if(!file_exists($save_path)){mkdir ($save_path,UMASK);}

		$file = $_FILES['userfile'];
		$k = count($file['name']);

		$_newfiles = Array();
		$_listfiles = Array();

		for($i=0 ; $i < $k ; $i++)
		{
		?>
		<tr><td width="180"><strong><?php echo $file['name'][$i] ?></strong></td><td align="right"><?php echo $file['size'][$i] ?></td></tr>
		<?php

		if(isset($save_path) && $save_path!="")
		{
			$name = split('/',$file['name'][$i]);

			move_uploaded_file($file['tmp_name'][$i], $save_path . $name[count($name)-1]);
			$_newfiles[] = $file['name'][$i];
		}

		}
		$fp=opendir($save_path);
		while ($file = readdir ($fp))
		{
			if ($file != "." && $file != "..")
			{
				if (!in_array($file,$_newfiles))
				{
					$_listfiles[] = $file;
				}
			}
		}
		if (count($_listfiles)!=0)
		{
		?>
		<tr><td colspan="2">&nbsp;--------------------------------------------------------</td></tr>
		<?php
		}
		foreach ($_listfiles AS $file)
		{
		?>
		<tr><td width="180"><strong><?php echo $file ?></strong></td><td align="right"><?php echo filesize($save_path.$file) ?></td></tr>
		
		<?php
		}

		?>
		</table>
		<?php
		exit();
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

		$myObj = new PhenotypeImage($dat_id);
		if ($myObj->id != 0)
		{
			// Bild
			$type = MB_IMAGE;
		} else
		{
			$type = MB_DOCUMENT;
			$myObj = new PhenoTypeDocument($dat_id);

			if ($myObj->id == 0)
			{
				$this->noAccess();
			}
		}

		$myPT->startBuffer();

		$this->displayIDLineMediaObject($myObj);


		$this->tab_new();

		$url = "backend.php?page=Editor,Media,edit&id=" .$myObj->id . "&uid=". $myObj->uid."&b=0";
		$this->tab_addEntry("Bearbeiten",$url,"b_edit_b.gif");

		$url = "backend.php?page=Editor,Media,rollback&id=" .$myObj->id . "&uid=". $myObj->uid."&b=0";
		$this->tab_addEntry("Rollback",$url,"b_rollback.gif");
		$this->tab_draw("Rollback");

		$this->listSnapshots("MO",$dat_id,"Editor","Media");

		return $myPT->stopBuffer();
	}

	function installSnapshot($sna_id,$sna_type)
	{
		global $myDB;

		$_params = Array();

		$myObj = parent::installSnapshot($sna_id,$sna_type);
		if ($myObj)
		{
			$_params["id"]=$myObj->id;
			$_params["uid"]=$myObj->uid;
			$_params["b"]=0;
			$_params["info"]="Snapshot eingespielt.";
			$this->_params = $_params;
			$this->gotoPage("Editor","Media","edit",$this->_params);
		}
		else
		{
			$_params["error"]="Fehler beim Rollback.";
			$this->_params = $_params;
			$this->gotoPage("Editor","Media","",$this->_params);
		}





	}


}
?>