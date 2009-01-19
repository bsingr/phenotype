<?php
/**
 * Include component
 * 
 * This component enables an editor to insert (include) functionalities within pages.
 * 
 * The editor also determines the caching behaviour of the selected include.
 * 
 * @package phenotype
 * @subpackage application
 *
 */
class PhenotypeComponent_1003 extends PhenotypeComponent
{

	var $com_id = 1003;
	var $name = "Include (Function)";


	public function setDefaultProperties()
	{
		$this->set("_revision",1);
		$this->set("cache",1);
	}

	
	public function initForm($context)
  	{
		global $myDB;
		
   	   	$sql = "SELECT inc_id, inc_rubrik, inc_bez FROM include WHERE inc_usage_includecomponent = 1 ORDER BY inc_rubrik,inc_bez";
      	$rs = $myDB->query($sql);
      	$_options = Array();
      	while ($row=mysql_fetch_assoc($rs))
     	{
      		$_options[$row["inc_id"]]=$row["inc_rubrik"].": ".$row["inc_bez"];
      	}
      	$this->form_selectbox("","inc_id",$_options);
      	$_options = Array(1=>"With Page",0=>"Never",2=>"Request Parameter Hash");
      	$this->form_selectbox("Cache","cache",$_options,false);
  	}

	public function render($context)
	{
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
						$html .= '<?php $myPT->executeInclude('.$inc_id.',true,'.$context.');?>';
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

	public function displayXML($style=1)
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