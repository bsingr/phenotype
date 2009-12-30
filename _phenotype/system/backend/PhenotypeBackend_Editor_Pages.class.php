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
 * @subpackage backend
 *
 */
class PhenotypeBackend_Editor_Pages_Standard extends PhenotypeBackend_Editor
{
  public $tmxfile = "Editor_Pages";
  
	public function execute($scope,$action)
	{
		global $myPT;
		global $mySUser;
		global $myRequest;
		global $myDB;
		
		
		switch ($action)
		{
			case "treesort":
				$this->displayTreeSort();
				break;
			default:
				Header("Location: pagegroup_select.php");
			break;
		}
		
	}
	
	
	protected function  displayTreeSort()
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>SimpleTree Drag&Drop</title>
<style>
body
{
	font: normal 12px arial, tahoma, helvetica, sans-serif;
	margin:0;
	padding:20px;
}
.simpleTree
{
	
	margin:0;
	padding:0;
	/*
	overflow:auto;
	width: 250px;
	height:350px;
	overflow:auto;
	border: 1px solid #444444;
	*/
}
.simpleTree li
{
	list-style: none;
	margin:0;
	padding:0 0 0 34px;
	line-height: 14px;
}
.simpleTree li span
{
	display:inline;
	clear: left;
	white-space: nowrap;
}
.simpleTree ul
{
	margin:0; 
	padding:0;
}
.simpleTree .root
{
	margin-left:-16px;
	background: url(img/treesort/root.gif) no-repeat 16px 0 #ffffff;
}
.simpleTree .line
{
	margin:0 0 0 -16px;
	padding:0;
	line-height: 3px;
	height:3px;
	font-size:3px;
	background: url(img/treesort/line_bg.gif) 0 0 no-repeat transparent;
}
.simpleTree .line-last
{
	margin:0 0 0 -16px;
	padding:0;
	line-height: 3px;
	height:3px;
	font-size:3px;
	background: url(img/treesort/spacer.gif) 0 0 no-repeat transparent;
}
.simpleTree .line-over
{
	margin:0 0 0 -16px;
	padding:0;
	line-height: 3px;
	height:3px;
	font-size:3px;
	background: url(img/treesort/line_bg_over.gif) 0 0 no-repeat transparent;
}
.simpleTree .line-over-last
{
	margin:0 0 0 -16px;
	padding:0;
	line-height: 3px;
	height:3px;
	font-size:3px;
	background: url(img/treesort/line_bg_over_last.gif) 0 0 no-repeat transparent;
}
.simpleTree .folder-open
{
	margin-left:-16px;
	background: url(img/treesort/collapsable.gif) 0 -2px no-repeat #fff;
}
.simpleTree .folder-open-last
{
	margin-left:-16px;
	background: url(img/treesort/collapsable-last.gif) 0 -2px no-repeat #fff;
}
.simpleTree .folder-close
{
	margin-left:-16px;
	background: url(img/treesort/expandable.gif) 0 -2px no-repeat #fff;
}
.simpleTree .folder-close-last
{
	margin-left:-16px;
	background: url(img/treesort/expandable-last.gif) 0 -2px no-repeat #fff;
}
.simpleTree .doc
{
	margin-left:-16px;
	background: url(img/treesort/leaf.gif) 0 -1px no-repeat #fff;
}
.simpleTree .doc-last
{
	margin-left:-16px;
	background: url(img/treesort/leaf-last.gif) 0 -1px no-repeat #fff;
}
.simpleTree .ajax
{
	background: url(img/treesort/spinner.gif) no-repeat 0 0 #ffffff;
	height: 16px;
	display:none;
}
.simpleTree .ajax li
{
	display:none;
	margin:0; 
	padding:0;
}
.simpleTree .trigger
{
	display:inline;
	margin-left:-32px;
	width: 28px;
	height: 11px;
	cursor:pointer;
}
.simpleTree .text
{
	cursor: default;
}
.simpleTree .active
{
	cursor: default;
	background-color:#F7BE77;
	padding:0px 2px;
	border: 1px dashed #444;
}
#drag_container
{
	background:#ffffff;
	color:#000;
	font: normal 11px arial, tahoma, helvetica, sans-serif;
	border: 1px dashed #767676;
}
#drag_container ul
{
	list-style: none;
	padding:0;
	margin:0;
}

#drag_container li
{
	list-style: none;
	background-color:#ffffff;
	line-height:18px;
	white-space: nowrap;
	padding:1px 1px 0px 16px;
	margin:0;
}
#drag_container li span
{
	padding:0;
}

#drag_container li.doc, #drag_container li.doc-last
{
	background: url(img/treesort/leaf.gif) no-repeat -17px 0 #ffffff;
}
#drag_container .folder-close, #drag_container .folder-close-last
{
	background: url(img/treesort/expandable.gif) no-repeat -17px 0 #ffffff;
}

#drag_container .folder-open, #drag_container .folder-open-last
{
	background: url(img/treesort/collapsable.gif) no-repeat -17px 0 #ffffff;
}
.contextMenu
{
	display:none;
}
</style>
<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="jQuery.simpleTree.js"></script>
<script type="text/javascript">
var simpleTreeCollection;
$(document).ready(function(){
	simpleTreeCollection = $('.simpleTree').simpleTree({
		autoclose: false,
		afterClick:function(node){
			//alert("text-"+$('span:first',node).text());
		},
		afterDblClick:function(node){
			//alert("text-"+$('span:first',node).text());
		},
		afterMove:function(destination, source, pos){
			//alert("destination-"+destination.attr('id')+" source-"+source.attr('id')+" pos-"+pos);
		},
		afterAjax:function()
		{
			//alert('Loaded');
		},
		animate:true,
		speed:100,
		docToFolderConvert:true
	});
});
</script>
</head>

<body>
<div class="contextMenu" id="myMenu1">
	<ul>
		<li id="add"><img src="img/treesort/folder_add.png" /> Add child</li>
		<li id="reload"><img src="img/treesort/arrow_refresh.png" /> Reload</li>
		<li id="edit"><img src="img/treesort/folder_edit.png" /> Edit</li>
		<li id="delete"><img src="img/treesort/folder_delete.png" /> Delete</li>
	</ul>
</div>
<div class="contextMenu" id="myMenu2">
	<ul>
		<li id="edit"><img src="img/treesort/page_edit.png" /> Edit</li>
		<li id="delete"><img src="img/treesort/page_delete.png" /> Delete</li>
	</ul>
</div>
<ul class="simpleTree">
<?php
global $myDB;
$myNH = new PhenotypeNavigationHelper();
$sql = "SELECT * FROM pagegroup ORDER BY grp_id";
$rs = $myDB->query($sql);

?>
<li class="root"><span>Tree Root 1</span>
		<ul>
<?
while ($row=mysql_fetch_array($rs))
{
?>
	<li id="g<?php echo $row["grp_id"]?>"><span><?php echo $row["grp_bez"]?></span>
	<?php
	$_pages = $myNH->getSubPages(0,false,$row["grp_id"]);
	$this->listPages($_pages);
	?>
	</li>
	<?	
}


?>
</ul>
</li>
</ul>


</body>

</html>
<?
	}
	
	public function listPages($_pages)
	{
		$myNH = new PhenotypeNavigationHelper();
		echo "<ul>";
		foreach ($_pages AS $pag_id)
		{
		?>
		<li id='<?php echo $pag_id?>'><span><?php echo title_of_page($pag_id)?></span>
		<?php
		$_pages = $myNH->getSubPages($pag_id,false);
		if (count($_pages)>0)
		{
			$this->listPages($_pages);
		}
		?>
		</li>
		<?
		}
		echo "</ul>";
	}
}
