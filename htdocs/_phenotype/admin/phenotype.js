// Hinzufuegen eines Contentelementes

  function addnew(p)
  {
    fname = "addtool_" + p;

    v = document.forms.editform[fname].options[document.forms.editform[fname].selectedIndex].value;
    if (v != 0)
    {
      document.forms.editform.newtool_id.value = p;
      document.forms.editform.newtool_type.value = v;
      document.forms.editform.submit();  
    }
  }
  
// Pruefung auf auswahl eines gueltigen Datensatzes
   
  function checkExtraSelection(s)
  {
    fname = "extraform_" + s;
    i= document.forms[fname].id.selectedIndex;
	if (i==0)
	{
	  alert ('Bitte wählen Sie einen gültigen Datensatz aus.');
	  return false;
	}
  }
  
function gotoUrl(url)
{
	document.location = url;
}


function popup(url,name,options)
{
  window.open (url,name,options);
}

  
function showDoc(nr)
{
   popup('showdoc.php?nr=' + nr,'documentation','scrollbars=yes,width=400,height=600');
}

function previewPage(id,ver_id,lng_id, dialogWidth, dialogHeight, sHeadline)
{
	$("body").after('<div id="pt-dialog"><iframe style="width:100%;height:100%;border:0px" border="0" frameborder="0" src="preview.php?id=' + id + '&ver_id=' + ver_id +'&lng_id=' + lng_id+ '"></iframe></div>');
	$("#pt-dialog").dialog({
	closeOnEscape: true,
	height:dialogHeight,
	width:dialogWidth,
	title:sHeadline,
	modal: true
	});
	//popup('preview.php?id=' + id + '&ver_id=' + ver_id +'&lng_id=' + lng_id,'vorschau','scrollbars=yes,width=1024,height=768,resizable=yes,status=yes,location=yes');
}

function previewContent(url, dialogWidth, dialogHeight, sHeadline)
{
	$("body").after('<div id="pt-dialog"><iframe style="width:100%;height:100%;border:0px" border="0" frameborder="0" src="' + url + '"></iframe></div>');
	$("#pt-dialog").dialog({
	closeOnEscape: true,
	height:dialogHeight,
	width:dialogWidth,
	title:sHeadline,
	modal: true
	});
}

function pageWizard(id,hasChilds)
{
   popup('page_addwizard.php?id=' + id+'&c=' + hasChilds ,'pagewizard','scrollbars=no,width=354,height=350');
}

function pageversion_autoactivation(pag_id,ver_id,ver_id_editing)
{
  popup('pageversion_autoactivation.php?id=' + pag_id+'&ver_id=' + ver_id +'&ver_id_editing=' + ver_id_editing  ,'pageversion','scrollbars=no,width=250,height=200');
}

function calendar(formname,formelement,date)
{
   popup('calendar.php?f=' + formname+'&e=' + formelement + "&d=" + date,'calendar','scrollbars=no,width=208,height=275');
}


function ticketWizard(pag_id,ver_id,dat_id,med_id,sbj_id,dat_id_2ndorder)
{
	
   popup('backend.php?page=Ticket,Process,insert&pag_id=' + pag_id+'&ver_id=' + ver_id+'&dat_id=' + dat_id +'&med_id=' + med_id+'&sbj_id=' + sbj_id+"&dat_id_2ndorder="+dat_id_2ndorder,'ticketwizard','scrollbars=no,width=435,height=600');
}



function ticketLog(tik_id)
{
  popup('backend.php?page=Ticket,Process,actionpopup&id=' + tik_id,'ticketLog'+tik_id,'scrollbars=yes,width=540,height=500,resizable=yes');
}

// Bildzuordnungsfunktionen

function selector_image(formname,formelement,folder,changefolder,x,y)
{
	/*
	url = 'selector_media.php?folder=' + folder + '&cf=' + changefolder + '&x=' + x + "&y=" + y + '&sortorder=1&p=1&type=1';
	$("body").after('<div id="pt-dialog"><iframe style="width:100%;height:100%;border:0px" src="' + url + '"></iframe></div>');
	$("#pt-dialog").dialog({
	closeOnEscape: true,
	height:755,
	width:550,
	title:'Medienauswahl',
	modal: true
	});
	*/
	
  popup('selector_media.php?folder=' + folder + '&cf=' + changefolder + '&x=' + x + "&y=" + y + '&sortorder=1&p=1&type=1','selector_image','scrollbars=no,width=501,height=680');

  document.formname = formname;
  document.formelement = formelement;
}

function selector_document(formname,formelement,folder,changefolder,doctype)
{
  
  popup('selector_media.php?folder=' + folder + '&cf=' + changefolder + '&doc=' + doctype + '&sortorder=1&p=1&type=2','selector_image','scrollbars=no,width=501,height=680');

  document.formname = formname;
  document.formelement = formelement;
}

function selector_media(formname,formelement,folder,changefolder,doctype)
{
  
  popup('selector_media.php?folder=' + folder + '&cf=' + changefolder + '&doc=' + doctype + '&sortorder=1&p=1&type=-1','selector_image','scrollbars=no,width=501,height=680');

  document.formname = formname;
  document.formelement = formelement;
}

function addlink(formname,formelement)
{
  elm = formelement + "select";
  show(elm);
  elm = formelement + "panel";
  show(elm);
}


function selector_link(formname,formelement)
{
  panel = formelement + "panel";
  show(panel);
  popup('selector_link.php','selector_link','scrollbars=yes,width=370,height=400');

  document.formname = formname;
  document.formelement = formelement;
}

function select_image(id,src,src2,x,y)
{
  doc = top.opener.document;
  //doc = top.document;
  formname = doc.formname;
  img_id = doc.formelement + "img_id";
  doc.forms[formname][img_id].value=id;
  med_id = doc.formelement + "med_id";
  doc.forms[formname][med_id].value=0;
  image = doc.formelement + "img_id_image";
  doc.forms[formname][image].src=src;
  doc.forms[formname][image].width=x;
  doc.forms[formname][image].height=y;
  
  link = doc.getElementById(doc.formelement + "link_image");
  link.href=src2;
  link = doc.getElementById(doc.formelement + "editlink_image");
  link.href="backend.php?page=Editor,Media,edit&id="+id;
  panel = doc.formelement + "panel";
  parentshow(panel);    
}

function select_document(id)
{
  doc = top.opener.document;
  formname = doc.formname;
  med_id = doc.formelement + "med_id";
  doc.forms[formname][med_id].value=id;
  img_id = doc.formelement + "img_id";
  doc.forms[formname][img_id].value=0;
  panel = doc.formelement + "panel";
  parentshow(panel);  
}

function select_link(bez,url,target)
{
  doc = top.opener.document;
  formname = doc.formname;
  formbez = doc.formelement + "bez";
  doc.forms[formname][formbez].value=bez;
  formurl = doc.formelement + "url";
  doc.forms[formname][formurl].value=url;
  formtarget = doc.formelement + "target";
  doc.forms[formname][formtarget].value=target;    
  formimg1 = doc.formelement + "target_img_0";
  formimg2 = doc.formelement + "target_img_1";
  doc.forms[formname][formimg1].src='img/b_link_target_self.gif';
  doc.forms[formname][formimg2].src='img/b_link_target_blank.gif';
  if (target=="_self")
  {
  doc.forms[formname][formimg1].src='img/b_link_target_self_activ.gif';
  }
  if (target=="_blank")
  {
  doc.forms[formname][formimg2].src='img/b_link_target_blank_activ.gif';
  }
}

function reset_image(formname,formelement)
{
  img_id = formelement + "img_id";
  document.forms[formname][img_id].value=0;
  image = formelement + "img_id_image";
  document.forms[formname][image].src="img/transparent.gif";
  document.forms[formname][image].width=1;
  document.forms[formname][image].height=1;
  panel = formelement + "panel";
  hide(panel);  
}

function reset_document(formname,formelement)
{
  med_id = formelement + "med_id";
  document.forms[formname][med_id].value=0;
  panel = formelement + "panel";
  hide(panel);
}

function reset_media(formname,formelement)
{
  img_id = formelement + "img_id";
  document.forms[formname][img_id].value=0;
  med_id = formelement + "med_id";
  document.forms[formname][med_id].value=0;
  image = formelement + "img_id_image";
  document.forms[formname][image].src="img/transparent.gif";
  document.forms[formname][image].width=1;
  document.forms[formname][image].height=1;
  panel = formelement + "panel";
  hide(panel);  
}

function reset_link(formname,formelement)
{
  formbez = formelement + "bez";
  document.forms[formname][formbez].value="";
  formurl = formelement + "url";
  document.forms[formname][formurl].value="";
  formtarget = formelement + "target";
  document.forms[formname][formtarget].value="_self";      
  formtarget = formelement + "x";
  document.forms[formname][formtarget].value="";     
  formtarget = formelement + "y";
  document.forms[formname][formtarget].value="";     
  formtarget = formelement + "source";
  document.forms[formname][formtarget].value="";   
  formtarget = formelement + "text";
  document.forms[formname][formtarget].value="";  
  formtarget = formelement + "type";
  document.forms[formname][formtarget].value=0;   
}

function page_move(pag_id)
{
  popup('selector_page.php?id=' + pag_id +"&cop=0",'selector_page','scrollbars=yes,width=370,height=400');
}

function page_copy(pag_id)
{
  popup('selector_page.php?id=' + pag_id +'&cop=1','selector_page','scrollbars=yes,width=370,height=400');
}


ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false
dom = (document.getElementById)?true:false;

function show(id) {
	if (ns4) document.layers[id].visibility = "show"
	else if (ie4) document.all[id].style.visibility = "visible"
	else if (dom) document.getElementById(id).style.visibility = "visible";
	
	if (ie4) document.all[id].style.display = ""
	else if (ie4) document.all[id].style.display = ""
	else if (dom) document.getElementById(id).style.display = "";
}

function hide(id) {
	if (ns4) document.layers[id].visibility = "hide"
	else if (ie4) document.all[id].style.visibility = "hidden"
	else if (dom) document.getElementById(id).style.visibility = "hidden";
	
	if (ie4) document.all[id].style.display = "none"
	else if (ie4) document.all[id].style.display = "none"
	else if (dom) document.getElementById(id).style.display = "none";	
}

function flip(id)
{
	var showit = 1;
	if (ns4){if(document.layers[id].visibility == "show"){showit=0;}}
	else if (ie4){ if (document.all[id].style.visibility == "visible"){showit=0;}}
	else if (dom){ if (document.getElementById(id).style.visibility == "visible"){showit=0;}}
	if (showit==1){show(id);}else{hide(id);}
}

function parentshow(id) {
    doc = top.opener.document;
    //doc = top.document;
	if (ns4) doc.layers[id].visibility = "show"
	else if (ie4) doc.all[id].style.visibility = "visible"
	else if (dom) doc.getElementById(id).style.visibility = "visible";
	
	if (ie4) doc.all[id].style.display = ""
	else if (ie4) doc.all[id].style.display = ""
	else if (dom) doc.getElementById(id).style.display = "";
}

function parenthide(id) {
    doc = top.opener.document;
	if (ns4) doc.layers[id].visibility = "hide"
	else if (ie4) doc.all[id].style.visibility = "hidden"
	else if (dom) doc.getElementById(id).style.visibility = "hidden";
	
	if (ie4) doc.all[id].style.display = "none"
	else if (ie4) doc.all[id].style.display = "none"
	else if (dom) doc.getElementById(id).style.display = "none";	
}




//document.form1.elements["checkbox[]"].length
var checked = "false";
function checkall(formname,checkname)
{
	var theCheckboxes=eval("document.forms."+formname+".elements['"+checkname+"']")
	
	if(checked == "false") 
	{
		for (i=0; i < theCheckboxes.length; i++)
		{
			if (theCheckboxes[i].type = "checkbox")
			{
				theCheckboxes[i].checked=true
			}
		}
		checked = "true";
		return "deselect all!";
	}
	else
	{
		for (i=0; i < theCheckboxes.length; i++)
		{
			if (theCheckboxes[i].type = "checkbox")
			{
				theCheckboxes[i].checked=false
			}
		}
		checked = "false";
		return "select all!";
	}
} // end function checkall

// Alle Checkboxen einer Gruppe setzen (Formular, Gruppenname, Index f. Merk-Array zum Zurücksetzen)
checkMemory = new Array();
checkValue = true;
function selectAll(f,n,a) {
	if( checkValue == true ){
		checkMemory[a] = new Array();
		for (i = 0; i < f.elements.length; i++) {
			checkMemory[a][i] = f.elements[i].checked;
		}
	}
	for (i = 0; i < f.elements.length; i++) {
		var chk = ( checkValue == true ? true : checkMemory[a][i] );
		if( f.elements[i].type == 'checkbox' && f.elements[i].name == n ) {
			f.elements[i].checked = chk;
		}
	}
	checkValue = !checkValue;
} // /selectAll

function match_rect(ax1,ay1,ax2,ay2,bx1,by1,bx2,by2)
{
  // a in b
  if (match_point (bx1,by1,ax1,ay1,ax2,ay2)){return true;}
  if (match_point (bx1,by2,ax1,ay1,ax2,ay2)){return true;}
  if (match_point (bx2,by1,ax1,ay1,ax2,ay2)){return true;}
  if (match_point (bx2,by2,ax1,ay1,ax2,ay2)){return true;}
  // b in a
  if (match_point (ax1,ay1,bx1,by1,bx2,by2)){return true;}  
  if (match_point (ax1,ay2,bx1,by1,bx2,by2)){return true;}
  if (match_point (ax2,ay1,bx1,by1,bx2,by2)){return true;}
  if (match_point (ax2,ay2,bx1,by1,bx2,by2)){return true;}      
  return false;
}

function match_point(x,y,rx1,ry1,rx2,ry2)
{
  if (x>rx1 & x<rx2 & y>ry1 & y<ry2){return true;}
  return false
}

/**
 * arrayFunctions.js
 *
 * This file contains a collection of array functions for javascript.
 * Most of them are inspired by their PHP equivalent.
 *
 * This source file is subject to version 2.1 of the GNU Lesser
 * General Public License (LPGL), found in the file LICENSE that is
 * included with this package, and is also available at
 * http://www.gnu.org/copyleft/lesser.html.
 * @package     Javascript
 *
 * @author      Dieter Raber <dieter@dieterraber.net>
 * @copyright   2004-12-27
 * @version     1.0
 * @license     http://www.gnu.org/copyleft/lesser.html
 *
 */

/**
 * TOC
 * 
 * - arrayUnique
 * - inArray
 */ 
 
/**
 * arrayUnique
 *
 * Removes duplicate values from an array. It takes input array and 
 * returns a new array without duplicate values. The original keys
 * are preserved
 *
 * object array
 * return array
 *
 * example:
 *   test = new Array ('foo', 'bar', 'foo')
 *   test.arrayUnique()  // returns the array ('foo', 'bar')
 *
 */
Array.prototype.arrayUnique = function()
{
  var uniqueArr = new Array();
  for (var origKey in this)
  {
    valueExists = false;
    for(var uniqueKey in uniqueArr)
    {
      if(uniqueArr[uniqueKey] == this[origKey])
      {
        valueExists = true;
      }
    }
    if(!valueExists)
    {
      uniqueArr[origKey] = this[origKey];
    }
  }
  return uniqueArr;
}


/**
 * inArray
 *
 * Checks if a value exists in an array 
 * Searches an array for a given value needle and returns TRUE 
 * if it is found in the array, FALSE otherwise.
 *
 * object array
 * param  mixed
 * return boolean
 *
 * example:
 *   testArray  = new Array ('foo', 'bar', 'baz')
 *   testNeedle = 'foo'
 *   testArray.inArray(testNeedle) //returns true 
 *
 */

Array.prototype.inArray = function(needle)
{
  for(var key in this)
  {
    if(this[key] === needle)
    {
      return true;
    }
  }
  return false;
}


		

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}



// get the true offset of anything on NS4, IE4/5 & NS6, even if it's in a table!
function getAbsX(elt) { return (elt.x) ? elt.x : getAbsPos(elt,"Left"); }
function getAbsY(elt) { return (elt.y) ? elt.y : getAbsPos(elt,"Top"); }
function getAbsPos(elt,which) {
 iPos = 0;
 while (elt != null) {
  iPos += elt["offset" + which];
  elt = elt.offsetParent;
 }
 return iPos;
}


/* =========================================================================================
/ following lines offers additional functions for using WZ Drag & Drop
/ 
/ sometimes when adding DIV elements before loading ot the pag is totally completed the browser 
// doesn't know the right dimension of that element. Then you must delay the adding with
// the following functions
//
*/

// holds IDs of divs which are activated for drag & drop within onload-function
WZDHTML_ARRAY_ADDS_DELAYED = new Array();
// holds ddelement names of those elements who will get a dropfunction after loading of the html page
WZDHTML_ARRAY_DROPFUNC_ELEMENT_DELAYED = new Array();
// holds dropfunction for those ddelements who will get a dropfunction after loading of the html page
WZDHTML_ARRAY_DROPFUNC_METHODNAME_DELAYED = new Array();


// add a DIV layer for drag & drop operations after loading of the page
function ADD_DHTML_DELAYED(s)
{
	l = WZDHTML_ARRAY_ADDS_DELAYED.length;
	WZDHTML_ARRAY_ADDS_DELAYED[l] = s;
}

// set drag & drop functions for ddelements, which are initalized after loading of the page.
// attention methodname must be an object, not a string
function SET_DHTML_DROPFUNC_DELAYED(element,methodname)
{
	l = WZDHTML_ARRAY_DROPFUNC_ELEMENT_DELAYED.length;
	WZDHTML_ARRAY_DROPFUNC_ELEMENT_DELAYED[l] = element;
	WZDHTML_ARRAY_DROPFUNC_METHODNAME_DELAYED[l]= methodname;
}

// this method is called everytime after loading a backend page
function WZDHTML_RESUME()
{
	for (i = 0; i <  WZDHTML_ARRAY_ADDS_DELAYED.length; i++)
	{
		ADD_DHTML (WZDHTML_ARRAY_ADDS_DELAYED[i]);
	}
	for (i = 0; i <  WZDHTML_ARRAY_DROPFUNC_ELEMENT_DELAYED.length; i++)
	{
		element = 	WZDHTML_ARRAY_DROPFUNC_ELEMENT_DELAYED[i];
		
		method = 	WZDHTML_ARRAY_DROPFUNC_METHODNAME_DELAYED[i];
		dd.elements[element].setDropFunc(method);

	}
}




$(document).ready(function()
{
	if (pt_bak_id=="Editor_Media")
	{
	$('#btn_select_deselect').click(function()
	{
	 	
		n = $('#content input:checkbox').length;
		i = $('#content input:checkbox:checked').length;
		if (n==i)
		{
			var csv="";
			//deactivate all
			$('#content :checkbox').each(function()
			{
				$(this).removeAttr("checked");	
				var id = $(this).attr("id").substr(2);
				csv += id+",";
			});
			csv = csv.substr(0,csv.length-1);
			lightbox_switch(csv,0);
			
		}
		else //activate missing
		{
			var csv="";
			$('#content :checkbox').each(function()
			{
				if ($(this).attr("checked")!=true)
				{
					$(this).attr("checked","checked");
					var id = $(this).attr("id").substr(2);
					csv += id+","
				}
			});
			csv = csv.substr(0,csv.length-1);
			lightbox_switch(csv,0);
		}

	});
	
	$('#btn_lightbox_mediabase').click(function()
	{
		var option = $('#lightboxselect select[name=select]').val();
		if (option==1) // clear lightbox
		{
			lightbox_switch(-1);
		}
		if (option==2) // delete lightbox objects in mediabase
		{
			lightbox_switch(-99);
		}
	});
	} // end of pt_bak == "Editor_Media"
	if (pt_bak_id=="Editor_Content")
	{
	$('#btn_select_deselect').click(function()
	{
	 	var con_id = $('#lightbox').attr('rel');
		n = $('#content input:checkbox').length;
		i = $('#content input:checkbox:checked').length;
		if (n==i)
		{
			var csv="";
			//deactivate all
			$('#content :checkbox').each(function()
			{
				$(this).removeAttr("checked");	
				var id = $(this).attr("id").substr(2);
				csv += id+",";
			});
			csv = csv.substr(0,csv.length-1);
			lightbox_switch(csv,con_id,0);
		}
		else //activate missing
		{
			var csv="";
			$('#content :checkbox').each(function()
			{
				if ($(this).attr("checked")!=true)
				{
					$(this).attr("checked","checked");
					var id = $(this).attr("id").substr(2);
					csv += id+","
				}
			});
			csv = csv.substr(0,csv.length-1);
			lightbox_switch(csv,con_id,0);
		}
	});
	
	$('#btn_lightbox_content').click(function()
	{
		var option = $('#lightboxselect select[name=select]').val();
		var con_id = $('#lightbox').attr('rel');
		if (option==1) // clear lightbox
		{
			lightbox_switch(-1,con_id);
		}
		if (option==2) // delete lightbox objects in mediabase
		{
			lightbox_switch(-99,con_id);
		}
		if (option==3) // status online
		{
			lightbox_switch(-2,con_id);
		}
		if (option==4) // status offline
		{
			lightbox_switch(-3,con_id);
		}
		return false;
	});
	} // end of pt_bak == "Editor_Content"	
	
});
