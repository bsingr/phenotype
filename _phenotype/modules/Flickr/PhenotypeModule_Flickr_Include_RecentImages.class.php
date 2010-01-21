<?php

class PhenotypeModule_Flickr_Include_RecentImages extends PhenotypeInclude
{

	public $id = 1;

	public function display()
	{
		$_result = PhenotypeModule_Flickr::getLatestImages('rwe');
		foreach ($_result AS $_image)
		{
			echo '<a href="'.$_image["url"].'"><img src="'.$_image["src"].'" width="75" height="75"/></a>';
		}
	}

}