<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support: 
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeDeployment
{
	public $version = "2.92";
	
	public $revision = 285;
	
	public $base_path="";
	
	public $build_path= "C:\\xampp\\htdocs\\srv_build\\";	
	
	public $_tokens = Array();
	
	public $_copy = Array();
	public $_copymove = Array();
	public $_copyreplace = Array();
	
	public $_dirs = Array();
	
	public function execute()
	{
		$this->base_path = str_replace("_phenotype\system\class","",dirname(__FILE__));
	
		echo "base_path: ". $this->base_path ."<br/>";
		echo "build_path: ". $this->build_path ."<br/>";
		
		$this->_tokens["##!PT_VERSION!##"]  =$this->version;
		$this->_tokens["##!BUILD_DATE!##"]  = date ('d.m.Y');
		$this->_tokens["##!BUILD_NO!##"]  = "r".$this->revision;
		$this->_tokens["##!BUILD_ID!##"]  = "Phenotype ".$this->version.", build r".$this->revision."383 PHP".phpversion()."@".$_ENV["OS"]."(".$_ENV["COMPUTERNAME"].")";

		echo "<br/>Tokens:<br/>";
		foreach ($this->_tokens AS $token => $v)
		{
			echo $token ." => ".$v."<br/>";
		}
		
		$this->_copy[]="index.html";
		$this->_copy[]="_config.inc.sample.php";
		$this->_copy[]="htaccess_smartURL_sample";
		$this->_copy[]="phenotype_install.sql";
		
		$_skip = Array("php.ini","install.4build.php",".htaccess",".htpasswd","dndplus.jar");
		$_files = $this->scanDir($this->base_path."htdocs");
		
		$this->_copy = array_merge($this->_copy,$_files);

		
		echo "<br/>Files<br/>";
		foreach ($this->_copy as $file)
		{
			$source= $this->base_path.$file;
			$target = $this->build_path.$file;
			
			echo $source." => ".$target."<br/>";
		}
	}
	
	public function scanDir($dir,$_skip=Array())
	{
		$_files=Array();
		if ($fp= opendir($dir))
		{
			while (($file = readdir($fp)) !== false)
			{
				if (($file == ".") || ($file == "..") || ($file == ".svn"))
				{
					continue;
				}
				if (is_dir($dir . '/' . $file))
				{
					$_subfiles=self::scanDir($dir . '/' . $file,$_skip);
					if (is_array($_subfiles))
					{
						$_files = array_merge($_files,$_subfiles);
					}
				}
				else
				{
					$_files[] = $dir .'/'.$file;
				}
			}
		}
		return ($_files);
	}
}