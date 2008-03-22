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


function __autoload($class_name) {

  // create inheritage of standard classes, if not inherited by application

  $_classes = Array("PhenotypeRequest","PhenotypeAdmin","PhenotypeComponent","PhenotypeContent","PhenotypeExtra","PhenotypeInclude","PhenotypePage","PhenotypeAction","PhenotypeTicket","PhenotypeBackend","PhenotypeUser","PhenotypeDataObject","PhenotypeMediabase","PhenotypeMediaObject","PhenotypeImage","PhenotypeDocument","PhenotypeLayout","PhenotypePackage","PhenotypeIncludeController","PhenotypeSystemDataObject","PhenotypeNavigationHelper","PhenotypeSmarty");

  if (in_array($class_name,$_classes))
  {
    $php = "class " . $class_name . " extends " .$class_name. "Standard {}";
    eval ($php);
    return;
  }

  // all standard classes are most likely in the classpath

  if (substr ($class_name,-8)=="Standard")
  {
    $file = CLASSPATH . substr($class_name,0,-8). ".class.php";
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
  }


  // deprecated, but still needed and ineritable

  if ($class_name=="PhenotypeAdminLayout")
  {
    eval ("class PhenotypeAdminLayout extends PhenotypeLayout {}");
    return;
  }

  // specific classes without standard inheritage

  $_classes = Array ("Smarty"=>SMARTYPATH . "Smarty.class.php","PhenotypeTree"=>CLASSPATH."PhenotypeTree.class.php");
  if (array_key_exists($class_name,$_classes))
  {
    $file = $_classes[$class_name];
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
  }

  if (substr($class_name,0,19)=="PhenotypeComponent_")
  {
    $file =  APPPATH . "components/". $class_name . '.class.php';
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
    else
    {
      // Even if a component is unknow, editing will continue !

      $php = "class " . $class_name . " extends PhenotypeComponent {}";
      eval ($php);
      return;
    }

  }
  if (substr($class_name,0,17)=="PhenotypeInclude_")
  {
    require_once  APPPATH . "includes/". $class_name . '.class.php';
    return;
  }
  if (substr($class_name,0,17)=="PhenotypeContent_")
  {
    require_once  APPPATH . "content/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,15)=="PhenotypeExtra_")
  {
    require_once  APPPATH . "extras/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,16)=="PhenotypeAction_")
  {
    require_once  APPPATH . "actions/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,17)=="PhenotypeBackend_")
  {
    $file = SYSTEMPATH . "backend/". $class_name . '.class.php';
    if (file_exists($file))
    {
      require_once($file);
      $file =  APPPATH . "backend/". $class_name . '.class.php';
      if (file_exists($file))
      {
        require_once($file);
      }
      else
      {
        $php = "class " . $class_name . " extends " . $class_name ."_Standard {}";
        eval ($php);
      }

      return;
    }
    else // keine Systembackendklasse, aber vielleich in der Applikation
    {
      $file =  APPPATH . "backend/". $class_name . '.class.php';
      if (file_exists($file))
      {
        require_once($file);
        return;
      }

    }



  }
  throw new Exception("Class autoloading failure. unknown class " .$class_name);
}

