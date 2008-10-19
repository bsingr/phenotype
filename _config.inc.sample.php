<?php
// ------------------------------------------------------
// Runmode
// ------------------------------------------------------
define ("PT_DEBUG",1);
define ("PT_BACKEND",1);
define ("PT_CONFIGMODE",1);
define ("PT_FRONTENDSESSION",0);
define ("PT_PAGECACHE",1);
define ("PT_INCLUDECACHE",1);
// PW für XML-Abruf
define ("PT_XMLACCESS","changeit");

// ------------------------------------------------------
// PFADE und URLs - Teil 1
// ------------------------------------------------------

define ("BASEPATH","/Users/www/default/phenotype-svn/trunk/");
define ("SERVERURL","/phenotype-svn/trunk/htdocs/");
define ("SERVERFULLURL","http://localhost" . SERVERURL);

// smartURL uses free configurable URLs for every page instead of index.php?id=xx
// to use it copy the htaccess file ../htaccess_smartURL_sample to .htaccess and disable APACHE_PTWRITEHTACCESS
define ("PT_URL_STYLE", "smartURL");

// ------------------------------------------------------
// Datenbank-Setup
// ------------------------------------------------------

define ("DATABASE_SERVER", 		"localhost");
define ("DATABASE_USER", 		"root");
define ("DATABASE_PASSWORD", 	"root01");	
define ("DATABASE_NAME",		"phenotype-svn");	

// ------------------------------------------------------
// Sprach-Setup
// ------------------------------------------------------

$PTC_LANGUAGES = Array(1=>"Deutsch",2=>"Englisch",3=>"Spanisch",4=>"Französisch");

// ------------------------------------------------------
// Default pag_id, wenn keine übergeben wird
// ------------------------------------------------------

define ("PAG_ID_STARTPAGE",1);

// ------------------------------------------------------
// PFADE und URLs - Teil 2
// ------------------------------------------------------
// muss nur angepasst werden,wenn nicht die
// Standardverzeichnisstruktur genommen wird.
// ------------------------------------------------------

define ("ADMINPATH", BASEPATH ."htdocs/_phenotype/admin/");
define ("ADMINURL", SERVERURL . "_phenotype/admin/");
define ("ADMINFULLURL", SERVERFULLURL . "_phenotype/admin/");

define ("SERVERPATH",BASEPATH ."htdocs/");
define ("TEMPPATH", BASEPATH . "_phenotype/temp/");
define ("SMARTYCOMPILEPATH", TEMPPATH . "smarty/");

define ("APPPATH", BASEPATH . "_phenotype/application/");

define ("PACKAGEPATH",APPPATH ."packages/");

define ("MEDIABASEPATH", SERVERPATH . "media/");
define ("MEDIABASEURL", SERVERURL . "media/");

// Pfad zu externen Libaries, Tools, die in der Applikation genutzt werden

define ("THIRDPARTYPATH", APPPATH . "3rdparty/");

define ("SYSTEMPATH", BASEPATH . "_phenotype/system/");
define ("SMARTYPATH", SYSTEMPATH . "smarty/libs/");
define ("CLASSPATH", SYSTEMPATH . "class/");

// ------------------------------------------------------
// Cache
// ------------------------------------------------------

define ("CACHEPATH", BASEPATH . "_phenotype/cache/");
define ("CACHENR", 1);
define ("CACHECOUNT",1);

// ------------------------------------------------------
// UMASK für Dateizugriffe
// ------------------------------------------------------

define ("UMASK",0775);


// ------------------------------------------------------
// Einbindung der Grundklassen
// ------------------------------------------------------

require (BASEPATH . "buildinfo.inc.php");
require (SYSTEMPATH . "_constants.inc.php");
require (CLASSPATH . "PhenotypeBase.class.php");
require (CLASSPATH . "Phenotype.class.php");
require (SYSTEMPATH . "_autoloader.inc.php");
require (SYSTEMPATH . "_helper.inc.php");

require (CLASSPATH . "PhenotypePage.class.php");
require (CLASSPATH . "Database.class.php");
require (CLASSPATH . "SqlBuilder.class.php");
require (CLASSPATH . "PhenotypeApplication.class.php");
require (CLASSPATH . "PhenotypeRequest.class.php");
require (CLASSPATH . "TCheck.class.php");
require (CLASSPATH . "PhenotypeLog.class.php");


// ------------------------------------------------------
// Logging
// ------------------------------------------------------

define("PT_LOG_METHOD", PT_LOGMTH_FILE);
define("PT_LOG_LOGFILE", TEMPPATH ."/logs/phenotype.log");
define("PT_LOG_TIMEFORMAT", "d/M/Y H:i:s O");
define("PT_LOG_CLIENTINFO_HEADER", '');
define("PT_LOG_CLIENTINFO_SERVER", 'REMOTE_ADDR');


// ------------------------------------------------------
// Einbindung der anwendungsspezifischen Hostkonfiguration
// ------------------------------------------------------

require (APPPATH . "_host.config.inc.php");

// ------------------------------------------------------
// Grundinitialisierung
// ------------------------------------------------------

// Time check initialize
$myTC = new TCheck();
$myTC->start();

//date_default_timezone_set('Etc/GMT-1');
require (APPPATH . "_application.inc.php");
$myPT = new Phenotype();
$myDB = new PhenotypeDatabase();
$myDB->connect();
$myApp = new PhenotypeApplication();
require (SYSTEMPATH . "_init.inc.php");
$myLog = new PhenotypeLog();

$myRequest = new PhenotypeRequest();
if ($myRequest->code404){$myApp->throw404();}