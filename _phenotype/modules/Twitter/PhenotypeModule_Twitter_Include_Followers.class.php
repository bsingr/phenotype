<?php
require_once("class/class.twitter.php");

class PhenotypeModule_Twitter_Include_Followers extends PhenotypeInclude
{
  public function display()
  {
    global $myDB, $myPage, $myRequest;
	
    echo microtime(). "<br/>";
    
    $t = new twitter;

    $myTwitter = new twitter();
    $myTwitter->type="json";
    //$_result = $myTwitter->socialGraphFollowedBy("xxx");
    //var_dump ($_result);
    $_results = $myTwitter->friends("yyy");
    foreach ($_results AS $_result)
    {
    	$src = $_result->profile_image_url;
    	$alt = $_result->screen_name . " (".$_result->name.")";
    	echo '<a href="http://www.twitter.com/'. $_result->screen_name.'"><img src="'.$src.'" alt="'.$alt.'" title="'.$alt.'" width="48"></a>';
    }

  }
}