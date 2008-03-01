<?php
// ------------------------------------------------------
// Runmode
// ------------------------------------------------------
define ("PT_DEBUG",1);
define ("PT_BACKEND",1);
define ("PT_CONFIGMODE",1);
define ("PT_FRONTENDSESSION",0);
define ("PT_PAGECACHE",1);
define ("PT_PAGECACHE_CLEARONCONTENTUPDATE",1);
// Bestimmt, ob Contentskins gecached von der Festplatte geholt werden dürfen
define ("PT_CONTENTCACHE",0);
// PW für XML-Abruf
define ("PT_XMLACCESS","changeit");

// ------------------------------------------------------
// PFADE und URLs - Teil 1
// ------------------------------------------------------

define ("BASEPATH","C:/Programme/xampp/htdocs/srv_basis/");
define ("SERVERURL","/srv_basis/htdocs/");
define ("SERVERFULLURL","http://localhost" . SERVERURL);

// smartURL uses free configurable URLs for every page instead of index.php?id=xx
// to use it copy the htaccess file ../htaccess_smartURL_sample to .htaccess and disable APACHE_PTWRITEHTACCESS
define ("PT_URL_STYLE", "smartURL");

// ------------------------------------------------------
// Datenbank-Setup
// ------------------------------------------------------

define ("DATABASE_SERVER", 		"localhost");
define ("DATABASE_USER", 		"root");
define ("DATABASE_PASSWORD", 	"");	
define ("DATABASE_NAME",		"phenotype");	

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

require (SYSTEMPATH . "_init.inc.php");
require (BASEPATH . "buildinfo.inc.php");
require (CLASSPATH . "Phenotype.class.php");
require (CLASSPATH . "PhenotypePage.class.php");
require (CLASSPATH . "Database.class.php");
require (CLASSPATH . "SqlBuilder.class.php");
require (CLASSPATH . "PhenotypeApplication.class.php");
require (CLASSPATH . "PhenotypeRequest.class.php");
require (CLASSPATH . "TCheck.class.php");
require (CLASSPATH . "PhenotypeLog.class.php");

// ------------------------------------------------------
// REWRITE RULES
// ------------------------------------------------------

// may(!) conflict with smartURL feature, see above
define ("APACHE_PTWRITEHTACCESS", 0);

define ("APACHE_HTACCESSHEADER","RewriteEngine on\n");

define ("APACHE_REWRITE_PATH",SERVERPATH);
define ("APACHE_REWRITE_FILE",".htaccess");
define ("APACHE_REWRITEBASE",SERVERURL);

// Maybe you need one of the following commands in your environment
// to run Phenotype:
//
// - AddType application/x-httpd-php5 .php .php4 .php3 .php5
// - php_flag register_globals off

// ------------------------------------------------------
// Logging
// ------------------------------------------------------

define("PT_LOG_METHOD", PT_LOGMTH_FILE);
define("PT_LOG_LOGFILE", TEMPPATH ."/logs/phenotype.log");
define("PT_LOG_TIMEFORMAT", "d/M/Y H:i:s O");
define("PT_LOG_CLIENTINFO_HEADER", '');
define("PT_LOG_CLIENTINFO_SERVER", 'REMOTE_ADDR');

// ------------------------------------------------------
// Data Objects
// ------------------------------------------------------

define("PT_DATAOBJECTS_ENABLED", 0);
// set this to 1 to enable the integration of PhenotypeDataObjects2

// ------------------------------------------------------
// Einbindung der anwendungsspezifischen Hostkonfiguration
// ------------------------------------------------------

require (APPPATH . "_host.config.inc.php");


// ------------------------------------------------------
// Einbindung der Applikationsklasse
// ------------------------------------------------------

require (APPPATH . "_application.inc.php");

$myApp = new PhenotypeApplication();

// ------------------------------------------------------
// Grundinitalisierung
// ------------------------------------------------------

$myDB = new Database();
$myDB->connect();
$myPT = new Phenotype();
$myLog = new PhenotypeLog();

// ------------------------------------------------------
// Alternativer Request-Zugriff
// ------------------------------------------------------

$myRequest = new PhenotypeRequest();



?>
