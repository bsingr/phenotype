<?php
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

/**
 * @package phenotype
 * @subpackage system
 *
 */

class PhenotypePeer_Standard
{
    public static function getRecords($con_id,$status=true,$order="dat_bez",$_filter=array())
    {
        global $myDB;
        $sql = "SELECT * FROM content_data WHERE con_id=".(int)$con_id;
        switch ($status)
        {
            case true:
                $sql .=" AND dat_status=1";
                break;
            case false:
                $sql .=" AND dat_status=0";
                break;
        }
        foreach ($_filter AS $filter)
        {
            $sql.= " AND ".$filter;
        }
        if ($order !="")
        {
            $sql .=" ORDER BY " . $order;
        }
        $rs = $myDB->query($sql);
        $_objects = Array();
        $cname = "PhenotypeContent_".(int)$con_id;
        while ($row = mysql_fetch_assoc($rs))
        {
            $myCO = new $cname;
            $myCO->init($row);
            $_objects[$myCO->id]=$myCO;
        }
        return ($_objects);
    }
}