/*
 * Phenotype RTFEditor config file for fckEditor
 *
 * ATTENTION! for customizations please read the infos in the next lines
 *
 * if you like to have your own configs, it's recommended to copy the existing files to a subfolder of your htdocs
 * and create your own config area there to separate your changes from the system and to ease future system upgrades.
 *
 * this file is just used as fck custom config file, see fckEditor docs for more info on custom configurations
 *
 * if you want to use another toolbar in fckEditor in Phenotype just create a new configSet and modify the toolbarSet 'default' there
 */

FCKConfig.DefaultLanguage = 'de' ; 

FCKConfig.EnterMode = 'p' ;			// p | div | br
FCKConfig.ShiftEnterMode = 'br' ;	// p | div | br

FCKConfig.ToolbarSets["Default"] = [
['Bold','Italic','Underline','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-','OrderedList','UnorderedList','-','Link','Unlink'],
['Paste','PasteText','PasteWord','RemoveFormat'],
['Undo','Redo','-','Find','Replace'],
['Print','-','Source','-','FitWindow']
] ;

// Do not add, rename or remove styles here. Only apply definition changes.
FCKConfig.CoreStyles = 
{
	// Basic Inline Styles.
	'Bold'			: { Element : 'strong', Overrides : 'b' },
	'Italic'		: { Element : 'em', Overrides : 'i' },
	'Underline'		: { Element : 'u' },
	'StrikeThrough'	: { Element : 'strike' },
	'Subscript'		: { Element : 'sub' },
	'Superscript'	: { Element : 'sup' },
	
	// Basic Block Styles (Font Format Combo).
	'p'				: { Element : 'p' },
	'div'			: { Element : 'div' },
	'pre'			: { Element : 'pre' },
	'address'		: { Element : 'address' },
	'h1'			: { Element : 'h1' },
	'h2'			: { Element : 'h2' },
	'h3'			: { Element : 'h3' },
	'h4'			: { Element : 'h4' },
	'h5'			: { Element : 'h5' },
	'h6'			: { Element : 'h6' },
	
	// Other formatting features.
	'FontFace' : 
	{ 
		Element		: 'span', 
		Styles		: { 'font-family' : '#("Font")' }, 
		Overrides	: [ { Element : 'font', Attributes : { 'face' : null } } ]
	},
	
	'Size' :
	{ 
		Element		: 'span', 
		Styles		: { 'font-size' : '#("Size","fontSize")' }, 
		Overrides	: [ { Element : 'font', Attributes : { 'size' : null } } ]
	},
	
	'Color' :
	{ 
		Element		: 'span', 
		Styles		: { 'color' : '#("Color","color")' }, 
		Overrides	: [ { Element : 'font', Attributes : { 'color' : null } } ]
	},
	
	'BackColor'		: { Element : 'span', Styles : { 'background-color' : '#("Color","color")' } }
};

//FCKConfig.StylesXmlPath = '../../fckstyles.php'; 
