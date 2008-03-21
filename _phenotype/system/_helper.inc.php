<?php
function codeH($value)
{
  global $myPT;
	return $myPT->codeH($value);
}

function codeI($value)
{
  global $myPT;
	return $myPT->codeI($value);
}

function codeHBR($value)
{
  global $myPT;
	return $myPT->codeHBR($value);
}

function url_for_page($pag_id,$_params=array(),$lng_id=null,$smartUID="")
{
  global $myPT;
	return $myPT->url_for_page($pag_id,$_params,$lng_id,$smartUID);
}

function title_of_page($pag_id,$lng_id=null)
{
  global $myPT;
	return $myPT->title_of_page($pag_id,$lng_id);
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