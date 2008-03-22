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

class PhenotypeInclude extends PhenotypeIncludeStandard
{
  public function getMimikry_pag_id()
  {
    global $myRequest;
    // We expect urls like xxxx/imageA,B.html
    // We want B
    $_smartUID = split(",",$myRequest->get("smartUID"));
    $patterns = "/[^0-9]*/";
    $pag_id = (int)preg_replace($patterns,"",$_smartUID[1]);
    return $pag_id;
  }
}

// -----------------------------------------------------------------------------------------
// [BLOCKSTOP_INHERITANCE]
// -----------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------
// [BLOCKSTART_MYAPPLICATION]
// -----------------------------------------------------------------------------------------

class PhenotypeApplication extends PhenotypeApplicationStandard
{
  // Please define all fixed IDs here as variables like "const $rol_id_xyz = 1" or "const $pag_id_news = 1" ...

  const pag_id_galleryview = 44;
  const con_id_gallery = 1601;

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