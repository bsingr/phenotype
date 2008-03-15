<?php
function url_for_page($pag_id,$_params=array(),$lng_id=null)
{
  global $myPT;
	return $myPT->url_for_page($pag_id,$_params,$lng_id);
}

/*
function url_for_po($myPage,$params=array())
{
  return PhenotypeHelper::url_for_po($myPage,$params);
}

function url_for_content($con_id,$dat_id,$action="show")
{
	return PhenotypeHelper::url_for_content($con_id,$dat_id,$action);
}

function url_for_co($myCO,$action="show")
{
	return PhenotypeHelper::url_for_co($myCO,$action);
}

function url_for_symbol($symbol,$params = Array())
{
  return PhenotypeHelper::url_for_symbol($symbol,$params);
}
*/