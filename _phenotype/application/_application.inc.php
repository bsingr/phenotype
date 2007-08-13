<?
// -----------------------------------------------------------------------------------------
// [BLOCKSTART_INHERITANCE]
// -----------------------------------------------------------------------------------------
// inheritance of all main phenotype classe for application specific overrides

class PhenotypeAdmin extends PhenotypeAdminStandard {}

class PhenotypeComponent extends PhenotypeComponentStandard {}

class PhenotypeContent extends PhenotypeContentStandard {}

class PhenotypeExtra extends PhenotypeExtraStandard {}

class PhenotypeInclude extends PhenotypeIncludeStandard {}

class PhenotypePage extends PhenotypePageStandard {}

class PhenotypeAction extends PhenotypeActionStandard {}

class PhenotypeTicket extends PhenotypeTicketStandard {}

class PhenotypeBackend extends PhenotypeBackendStandard {}

class PhenotypeUser extends PhenotypeUserStandard  {}

class PhenotypeDataObject extends PhenotypeDataObjectStandard {}

// Diese Klasse wird mit dem vollständigen Umbau des Backends überflüssig !
class PhenotypeAdminLayout extends PhenotypeLayout {}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_INHERITANCE]
// -----------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------
// [BLOCKSTART_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

class PhenotypeApplication extends PhenotypeApplicationStandard 
{
	// Please define all fixed IDs here as variables like "public $rol_id_xyz = 1" or "public $pag_id_news = 1" ...

}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_MYAPPLICATION] 
// -----------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------
// [BLOCKSTART_CLASSALIAS] 
// -----------------------------------------------------------------------------------------

// Definition of class aliases for contenobjects, components and so on to
// improve readability of the code


// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_CLASSALIAS] 
// -----------------------------------------------------------------------------------------




?>