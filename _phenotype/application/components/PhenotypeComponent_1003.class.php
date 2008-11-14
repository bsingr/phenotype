<? 
/**
 * Include component
 * 
 * This component enables an editor to insert (include) functionalities within pages.
 * 
 * The editor also determines the caching behaviour of the selected include.
 *
 *
 */
class PhenotypeComponent_1003 extends PhenotypeComponent
{

	var $tool_type = 1003;

	var $bez="Include (Function)";


	function setDefaultProperties()
	{
		$this->set("inc_id","0");
		$this->set("cache","1");
	}

	function edit()
	{
		global $myDB;
    ?><select name="<?=$this->formid?>inc_id" class="input" style="width:300px"> 
      <option value="0">...</option> 
      <? 
      $sql = "SELECT * FROM include WHERE inc_usage_includecomponent = 1 ORDER BY inc_rubrik,inc_bez";
      $rs = $myDB->query($sql);
      while ($row=mysql_fetch_array($rs))
      {
      	$selected = "";
      	if ($row["inc_id"]==$this->get("inc_id")){$selected = "selected";}
      ?> 
      <option <?=$selected?> value="<?=$row["inc_id"]?>"><?=$row["inc_rubrik"].": ".$row["inc_bez"]?></option> 
      <? 
      }
      ?> 
      </select><br>Cache<br>
      <select name="<?=$this->formid?>cache" class="input" style="width:140px"> 
      <option value="1" >with page</option>
      <option value="0" <?if ($this->get("cache")=="0"){echo "selected";}?>>never</option>
      <option value="2" <?if ($this->get("cache")=="2"){echo "selected";}?>>Request parameter hash</option>     <?
	}

	function render($context)
	{
		// Notwendig, um die Smartyengine richtig zu initialisieren
		eval ($this->initRendering());

		global $myPage;

		$html = "";
		$inc_id = $this->get("inc_id");
		if ($inc_id!="0")
		{
			if ($myPage->buildingcache==0)
			{
				$cname = "PhenotypeInclude_" . $inc_id;
				$myInc = new $cname();
				$myInc->context = $context;
				$html = $myInc->execute();
			}
			else
			{
				$cache = $this->get("cache");
				if ($cache==2 AND PT_PAGECACHE ==0)
				{
					$cache=1;
				}
				switch ($cache)
				{
					case 1:
						$cname = "PhenotypeInclude_" . $inc_id;
						$myInc = new $cname();
						$myInc->context = $context;
						$html = $myInc->execute();
						break;
					case 2:
						$html = '<?php $myPage->includenocache=1?>';// Notwendig fuer Content-Statistik
						$html .= '<?php $myDB->setNextContext("Include '.$inc_id.':");?>';
						$html .= '<?php $myPT->executeInclude('.$inc_id.',true,$context);?>';
						$html .= '<?php $myPage->includenocache=0?>';
						break;
					default:
						$html .= '<?php $myDB->setNextContext("Include '.$inc_id.':");?>';
						$html = '<?php $myPage->includenocache=1?>';// Notwendig fuer Content-Statistik
						$html .= '<?php $myInc = new PhenotypeInclude_' . $inc_id .'();echo $myInc->execute()?>';
						$html .= '<?php $myPage->includenocache=0?>';
						break;
				}
			}
		}
		return $html;
	}

	function displayXML($style=1)
	{
		global $myPage;
       ?>
       <component com_id="1003" type="Include">
   	   <content>
       <?
       $inc_id = $this->get("inc_id");
       if ($inc_id!="0")
       {
       	if ($myPage->buildingcache==0)
       	{
       		//$myInc = new PhenotypeInclude($inc_id);
       		$cname = "PhenotypeInclude_" . $inc_id;
       		$myInc = new $cname();
       		$xml = $myInc->renderXML();
       		echo $xml;
       	}
       	else
       	{
       		if ($this->get("cache")==1)
       		{
       			$cname = "PhenotypeInclude_" . $inc_id;
       			$myInc = new $cname();
       			$xml = $myInc->renderXML();
       			echo $xml;
       		}
       		else
       		{
       			echo  '<?$myInc = new PhenotypeInclude_' . $inc_id .'();echo $myInc->renderXML()?>';

       		}
       	}


       }
     ?>
     </content>
     </component>
     <?
     return true;
	}
}
?>