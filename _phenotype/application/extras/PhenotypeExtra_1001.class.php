<?php 
class PhenotypeExtra_1001 extends PhenotypeExtra
{
	public $id = 1001;
	public $bez = "Pagewizard";
	public $configure_tab  =0;

	function displaySetup()
	{
	}

	function storeConfig()
	{
		$this->store();
	}

	function displayInfo()
	{
		global $myLayout;
		 ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td class="tableBody">
        The pagewizard is a mighty helper extra for creating pages.<br/>
        You can create whole page trees very simple by writing them down.<br/>
        
        Just write down page titles in a list, one line for each page.<br/>
        With spaces you can determine the level of a page, i.e. you can create child pages.<br/>
        <br/>
        Example: <br/>
        Home<br/>
        &nbsp;Page 1<br/>
        &nbsp;Page 2<br/>
        &nbsp;&nbsp;Page 2.1<br/>
        &nbsp;Page 3<br/>
        <br/>
        <br/>
        You must determine layout and online status for all pages. But you can override your global settings for single pages:<br/>
 		<br/>	
        Example: <br/>
        Page 1 || 6 0<br/>
        Page 2 || 4 1<br/>
        Page 3<br/>
        Page 4<br/>
        <br/>
         In this example "Page 1" gets the layout with id 6, "Page 2" gets layout with id 4.<br/>
        "Page 1" is offline after creation, "Page 2" online. Page 3 and 4 gets selected default values.<br/>
        <br/>
        </td>
        </tr>
        </table>
        <?php 

	}
	function displayStart()
	{
		global $myLayout;
		global $myPT;
		global $myDB;

		$myLayout->workarea_start_draw();
		 ?>
		<form action="extra_execute.php" method="post">
		<input type="hidden" name="id" value="<?php echo $this->id ?>">
		<?php 

		$sql = "SELECT * FROM pagegroup ORDER BY grp_bez";
		$rs = $myDB->query($sql);
		$options = "";
		$options .= '<option value="0">--------------------------------</option>';
		while ($row = mysql_fetch_array($rs))
		{
			$options .= '<option value="g'.$row["grp_id"].'">Group: '.$row["grp_bez"].'</option>';
		}
		$sql = "SELECT * FROM page ORDER BY grp_id,pag_bez";
		$rs = $myDB->query($sql);
		$grp_id = 0;
		while ($row = mysql_fetch_array($rs))
		{
			if ($row["grp_id"] != $grp_id)
			{
				$grp_id = $row["grp_id"];
				$options .= '<option value="0">--------------------------------</option>';
			}
			$options .= '<option value="'.$row["pag_id"].'">'.$row["pag_bez"].'</option>';
		}
		$options .= '<option value="0">--------------------------------</option>';

		$html = $myLayout->workarea_form_select("", "pag_id", $options, $x);
		$myLayout->workarea_row_draw("Reference page", $html);

		$_options = Array (1 => "after selected page, same level", 2 => "before selected page, same level", 3 => "below selected page, one level deeper (new child page)");
		$options = $myPT->buildOptionsByNamedArray($_options, "");
		$html = $myLayout->workarea_form_select("", "insertorder", $options, $x);
		$myLayout->workarea_row_draw("Settings", $html);

		$sql = "SELECT * FROM layout ORDER BY lay_bez";
		$rs = $myDB->query($sql);
		$_options = Array ();
		$_options[] = "no template";
		while ($row = mysql_fetch_array($rs))
		{
			$_options[$row["lay_id"]] = $row["lay_id"].": ".$row["lay_bez"];
		}
		$options = $myPT->buildOptionsByNamedArray($_options, "");
		$html = $myLayout->workarea_form_select("", "lay_id", $options, $x);
		$myLayout->workarea_row_draw("Layout", $html);

		$html = $myLayout->workarea_form_checkbox("", "online", 1, online);
		$myLayout->workarea_row_draw("Status", $html);

		$html = $myLayout->workarea_form_textarea("", "tree", "", 20, 395);
		$myLayout->workarea_row_draw("Page tree", $html);

		$myLayout->workarea_whiteline();
 ?>
			 <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterWhite">&nbsp;</td>
            <td align="right" class="windowFooterWhite">
			&nbsp;&nbsp;<input name="save" type="submit" class="buttonWhite" style="width:102px"value="Start">&nbsp;&nbsp;
            </td>
          </tr>
        </table>
        </form>
		<?php 


		$myLayout->workarea_stop_draw();
	}

	function execute($myRequest)
	{
		global $myLayout;
		$myLayout->workarea_start_draw();
	    ?>
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td class="tableBody">
        <?php 
        $this->buildPages();
		 ?>
		</td>
          </tr>
        </table>
		<?php 
		$myLayout->workarea_whiteline();
		$myLayout->workarea_stop_draw();
	}

	function buildPages()
	{
		global $myRequest;
		global $myDB;
		global $myPT;

		$pag_id = $myRequest->get("pag_id");

		// Array aufbauen

		$lastlevel=0;

		$s = $myRequest->get("tree");
		$_tree= Array();
		$s =explode("\n",$s);
		$i=0;
		foreach ($s as $k)
		{
			$i++;
			$lay_id = $myRequest->getI("lay_id");
			$status = $myRequest->getI("online");
			$k = str_replace (chr(10),"",$k);
			$k = str_replace (chr(13),"",$k);
			$level = 0;
			while (mb_substr($k,$level,1)==" ")
			{
				$level++;
			}
			$k = trim($k);
			$p = mb_strpos($k,"||");
			if ($p!==false)
			{
				$params = mb_substr($k,$p+2);
				$params = trim($params);
				$k = mb_substr($k,0,$p);

				$p = mb_strpos($params," ");
				if ($p!==false)
				{
					$lay_id = (int)mb_substr($params,0,$p);
					$status = (int)mb_substr($params,$p+1);
				}
				else
				{
					$lay_id = (int)$params;
				}
			}
			if ($k!="")
			{

				// Korrigiert unmögliche Baumstrukturen
				if ($level>$lastlevel+1)
				{
					$level=$lastlevel+1;
				}
				if ($i==1)
				{
					$level=0;
				}
				// -- Unmögliche Baumstrukturen

				$_tree[] = Array($level,$k,$lay_id,$status);
				$lastlevel=$level;
			}
		}



		$_parents = Array();
		$lastlevel=0;
		$push=0;
		$n=0;
		$c=count($_tree);
		for ($i=0;$i<$c;$i++)
		{
			$_page = $_tree[$i];
			$level = $_page[0];
			$bez = $_page[1];
			$lay_id = $_page[2];
			$status = $_page[3];
			$insertorder = 1; // nach der letzten Seite

			// Erste Seite ermitteln
			if ($i==0)
			{
				if (mb_strpos($pag_id,"g")===0)
				{
					$grp_id = (int)mb_substr($pag_id,1);

					// Check, ob in der Gruppe schon Seiten sind !!
					$sql = "SELECT * FROM page WHERE grp_id = " . $grp_id . " AND pag_id_top=0 ORDER BY pag_pos DESC";
					$rs = $myDB->query($sql);
					if (mysql_num_rows($rs)==0)
					{
						$_parents[] = 0;
						$insertorder = 3;
					}
					else
					{
						$row = mysql_fetch_array($rs);
						$_parents[] = $row["pag_id"];
						$insertorder = 1;
					}
				}
				else
				{
					// Abfangen pag_id=0;
					if ($myRequest->getI("pag_id")==0)
					{
						echo "You must choos either a page or a pagegroup.";
						return false;
					}

					$grp_id =NULL;
					$_parents[] = $myRequest->getI("pag_id");
					$insertorder = $myRequest->getI("insertorder");
				}
			}

			// -- Erste Seite ermitteln
			if ($level>$lastlevel)
			{
				$insertorder = 3;
			}
			$downgrade=0;
			if ($level<$lastlevel)
			{
				$n=$i;
				while ($n>0)
				{
					$n--;
					$backlevel = $_tree[$n][0];
					if ($backlevel==$level)
					{
						break;
					}
					$downgrade++;
				}
			}

			$c2 = count($_parents);
			$pag_id_parent = $_parents[$c2-1-$downgrade];

			echo "Page <strong>" . $bez . "</strong> created.<br/>";

			$myPage = new PhenotypePage();
			if ($pag_id_parent==0)
			{
				$id = $myPage->newPage_FirstInGroup($grp_id);
			}
			else
			{
				$id = $myPage->newPage_RelatedToExisitingPage($pag_id_parent,$insertorder);
			}

			$_parents[] = $id;
			$lastlevel=$level;

			$myPage->init($id);

			$mySQL = new SQLBuilder();
			$mySQL->addField("pag_bez",$bez);
			$mySQL->addField("pag_titel",$bez);
			$mySQL->addField("pag_status",$status);

			$sql = $mySQL->update("page","pag_id=".$myPage->id);
			$myDB->query($sql);

			$mySQL = new SQLBuilder();
			$mySQL->addField("lay_id",$lay_id);
			$sql = $mySQL->update("pageversion","ver_id=".$myPage->ver_id);
			$myDB->query($sql);

			$myPage->buildProps();


		}

		// Rebuild urls of all pages
		$sql = "SELECT pag_id FROM page";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$myPage = new PhenotypePage($row["pag_id"]);
			//echo $myPage->smarturl_schema;
			$myPage->rebuildURLs($false,$true);
		}
	}

}
 ?>