/*
 * Phenotype RTFEditor config file for fckEditor
 *
 * ATTENTION! for customizations please read the infos in the next lines
 *
 * if you like to have your own configs, it's recommended to copy the existing files to a subfolder of your htdocs
 * and create your own config area there to separate your changes from the system and to ease future system upgrades.
 *
 * this file is used as fck custom config file, see fckEditor docs for more info on custom configurations
 *
 * if you want to use another toolbar in fckEditor in Phenotype just create a new configSet and modify the toolbarSet 'default' there
 */

FCKConfig.DefaultLanguage = 'de' ; 

FCKConfig.EnterMode = 'p' ;			// p | div | br
FCKConfig.ShiftEnterMode = 'br' ;	// p | div | br

FCKConfig.TabSpaces = 0 ; // 0 means no conversion tab to spaces

FCKConfig.ToolbarSets["Default"] = [
['Undo','Redo'],['Find','Replace'],
['Print','-','FitWindow']
] ;
