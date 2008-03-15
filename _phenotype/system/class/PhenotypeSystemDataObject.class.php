<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeSystemDataObjectStandard extends PhenotypeDataObject
{
  public $dao_type = self::dao_system;

  /**
   * build lookup table for contentobjects, includes and components for debug display
   *
   */
  public function buildDataDebugLookUpTable($_params=array())
  {
    global $myDB;
    $sql = "SELECT * FROM include ORDER BY inc_id";
    $rs = $myDB->query($sql);
    $_includes = array();
    while ($row=mysql_fetch_array($rs))
    {
      $_includes[$row["inc_id"]]=$row["inc_bez"];
    }

    $this->set("includes",$_includes);
    $sql = "SELECT * FROM component ORDER BY com_id";
    $rs = $myDB->query($sql);
    $_includes = array();
    while ($row=mysql_fetch_array($rs))
    {
      $_includes[$row["com_id"]]=$row["com_bez"];
    }

    $this->set("components",$_includes);
    $sql = "SELECT * FROM content ORDER BY con_id";
    $rs = $myDB->query($sql);
    $_includes = array();
    while ($row=mysql_fetch_array($rs))
    {
      $_includes[$row["con_id"]]=$row["con_bez"];
    }

    $this->set("content",$_includes);
    $this->store(5*60);
  }

  /**
   * Provide an empty lightbox on first call
   *
   * @param array() usr_id => id of the user who owns the lightbox
   */
  public function buildDataMediaLightbox($_params = array())
  {
    $this->set("objects",array());
    $this->store(0);
  }

  /**
   * provide a temporay list of application objects (pages, contentobjects, media and tickets)
   * during AJAX export to reduce DB traffic.
   *
   * @param array $_params
   */
  public function buildDataPackageExportHelper($_params = array())
  {
    global $myDB;
    $type= $_params["type"];
    $_ids = Array();
    switch ($type)
    {
      case "pages":
        $sql = "SELECT pag_id FROM page WHERE grp_id = " . (int)$_params["grp_id"];
        $rs = $myDB->query($sql);
        while ($row=mysql_fetch_array($rs))
        {
          $_ids[] = $row["pag_id"];
        }
        break;
      case "content":
        $sql = "SELECT dat_id FROM content_data WHERE con_id = " . (int)$_params["con_id"];
        $rs = $myDB->query($sql);
        while ($row=mysql_fetch_array($rs))
        {
          $_ids[] = $row["dat_id"];
        }
        break;
      case "media":
        $sql = "SELECT med_id FROM media WHERE grp_id = " . (int)$_params["grp_id"];
        $rs = $myDB->query($sql);
        while ($row=mysql_fetch_array($rs))
        {
          $_ids[] = $row["med_id"];
        }
        break;
      case "tickets":
        $sql = "SELECT tik_id FROM ticket WHERE sbj_id = " . (int)$_params["sbj_id"];
        $rs = $myDB->query($sql);
        while ($row=mysql_fetch_array($rs))
        {
          $_ids[] = $row["tik_id"];
        }
        break;
    }
    $this->set("objects",$_ids);
  }


}


