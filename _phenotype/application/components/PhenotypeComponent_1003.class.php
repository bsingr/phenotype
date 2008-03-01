<?php php 
class PhenotypeComponent_1003 extends PhenotypeComponent 
{ 
  var $tool_type = 1003; 
  var $bez="Include";


  function setDefaultProperties() 
  { 
      $this->set("inc_id","0"); 
	  $this->set("cache","1");
  } 
    
  function edit() 
  { 
    global $myDB; 

  ?>       <select name="<?php php php echo $this->formid ?>inc_id" class="input" style="width:300px"> 
      <option value="0">...</option> 
      <?php php 
      $sql = "SELECT * FROM include WHERE inc_usage_includecomponent = 1 ORDER BY inc_rubrik,inc_bez"; 
      $rs = $myDB->query($sql); 
      while ($row=mysql_fetch_array($rs)) 
      { 
        $selected = ""; 
        if ($row["inc_id"]==$this->get("inc_id")){$selected = "selected";} 
      ?> 
      <option <?php php php echo $selected ?> value="<?php php php echo $row["inc_id"] ?>"><?php php php echo $row["inc_rubrik"].": ".$row["inc_bez"] ?></option> 
      <?php php 
      } 
      ?> 
      </select><br>Cache<br>
	  <select name="<?php php php echo $this->formid ?>cache" class="input" style="width:80px"> 
      <option value="1" >wie Seite</option>
	  <option value="0" <?php php if ($this->get("cache")=="0"){echo "selected";} ?>>nie</option>
	  <?php php 
  } 

  function update() 
  { 
    // Auswertung der Eingabemaske und Setzen der Properties des Tools 
    $this->fset("inc_id"); 
	$this->fset("cache"); 
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
	    $html = $myInc->execute();
	  }
	  else
	  {
	    if ($this->get("cache")==1)
		{
		  $cname = "PhenotypeInclude_" . $inc_id;
		  $myInc = new $cname();
	      $html = $myInc->execute();
		}
		else
		{
		  $html = '<?$myPage->includenocache=1 ?>';// Notwendig fuer Content-Statistik
		  $html .= '<?php php $myInc = new PhenotypeInclude_' . $inc_id .'();echo $myInc->execute() ?>';
          $html .= '<?php php $myPage->includenocache=0 ?>';
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
  	 <?php php 
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
			   echo  '<?$myInc = new PhenotypeInclude_' . $inc_id .'();echo $myInc->renderXML() ?>';
  
			}
      } 
  	  

     }
     ?>
     </content>
     </component>
     <?php php 
     return true;
  }  
} 
 ?>