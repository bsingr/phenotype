<?php
class PhenotypeModule_Flickr extends PhenotypeModule
{
	protected $_mandatory_php_modules = Array();
	
	protected static $api_key = "xxx";
	
	public function getLatestImages($search,$limit=10)
	{
		$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".self::$api_key."&text=".urlencode($search);
		$_images = Array();
		$xml = file_get_contents($url);
		if ($xml)
		{
			$_x = simplexml_load_string($xml);
			if ($_x)
			{
				
				$i=0;
				foreach ($_x->photos->photo AS $x_photo)
				{
					
					$photo_id = (string)$x_photo["id"];
					echo $id."<br/>";
					$_result = self::getThumb75x75($photo_id);
					if ($_result)
					{
						$i++;
						$_image = Array();
						$_image["src"]=(string)$_result["source"];
						$_image["url"]=(string)$_result["url"];
						$_images[]=$_image;
					}
					if ($i==10)
					{
						break;
					}
				}
			}
		}
		return $_images;
	}
	
	public function getThumb75x75($photo_id)
	{
		$x_sizes = self::getSizes($photo_id);
		foreach ($x_sizes->size AS $x_size)
		{
			if ((string)$x_size["label"]=="Square")
			{
				return $x_size;
			}
		}
		return false;
	}
	
	public function getSizes($photo_id)
	{
		$url = "http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=".self::$api_key."&photo_id=".$photo_id;
		$xml = file_get_contents($url);
		if ($xml)
		{
			$_x = simplexml_load_string($xml);
			if ($_x)
			{
				return $_x->sizes;
			}
		}
		return false;
	}
	
}