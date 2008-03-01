<?php
// -----------------------------------------------------------------------------------------
// [BLOCKSTART_INHERITANCE]
// -----------------------------------------------------------------------------------------

// you may inherit every PhenotypeXYZStandard class like this:
//
// class PhenotypeContent extends PhenotypeContentStandard {}
//
// So you're free to customize Phenotype like you want or to try something new before
// you commit your changes to our svn repository.

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_INHERITANCE]
// -----------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------
// [BLOCKSTART_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

class PhenotypeApplication extends PhenotypeApplicationStandard 
{
	// Please define all fixed IDs here as variables like "public $rol_id_xyz = 1" or "public $pag_id_news = 1" ...

	public $pag_id_galleryview = 44;
	public $con_id_gallery = 1601;
	
	public $contact_email_to = "";
	public $contact_email_from = "";
}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------
// [BLOCKSTART_CLASSALIAS] 
// -----------------------------------------------------------------------------------------

// Definition of class aliases for contenobjects, components and so on to
// improve readability of the code

class CO_Gallery extends PhenotypeContent_1601 {}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_CLASSALIAS] 
// -----------------------------------------------------------------------------------------




?>