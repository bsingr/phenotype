<?php
// ------------------------------------------------------
// runmode
// ------------------------------------------------------
define ("PT_DEBUG",1);
define ("PT_BACKEND",1);
define ("PT_CONFIGMODE",1);
define ("PT_FRONTENDSESSION",0);
define ("PT_PAGECACHE",1);
define ("PT_INCLUDECACHE",1);
define ("PT_URL_STYLE", "smartURL");

// ------------------------------------------------------
// pathes and urls - part 1
// ------------------------------------------------------

define ("BASEPATH","/var/www/htdocs/phenotype/");
define ("SERVERURL","/");
define ("SERVERFULLURL","http://localhost" . SERVERURL);

// ------------------------------------------------------
// database setup
// ------------------------------------------------------

define ("DATABASE_SERVER", 		"localhost");
define ("DATABASE_USER", 		"root");
define ("DATABASE_PASSWORD", 	"");	
define ("DATABASE_NAME",		"phenotype");	

// ------------------------------------------------------
// language setup
// ------------------------------------------------------

define ("PT_LOCALE","en");
$PTC_LANGUAGES = Array(1=>"Deutsch",2=>"Englisch",3=>"Spanisch",4=>"Franz�sisch");

// ------------------------------------------------------
// default start page
// ------------------------------------------------------

define ("PAG_ID_STARTPAGE",1);

// ------------------------------------------------------
// security settings (only relevant for some advanced features)
// ------------------------------------------------------

define ("PT_XMLACCESS","changeit");
define ("PT_SECRETKEY","changeit");

// ------------------------------------------------------
// pathes and urls - part 2
// ------------------------------------------------------
// normally no changes are necessary. Only if you decide
// to change the normal folder structure of phenotype.
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

define ("THIRDPARTYPATH", APPPATH . "3rdparty/");

define ("SYSTEMPATH", BASEPATH . "_phenotype/system/");
define ("SMARTYPATH", SYSTEMPATH . "smarty/libs/");
define ("CLASSPATH", SYSTEMPATH . "class/");

// ------------------------------------------------------
// cache settings (only change if you use on phenotype 
// installation in a cluster environment)
// ------------------------------------------------------

define ("CACHEPATH", BASEPATH . "_phenotype/cache/");
define ("CACHENR", 1);
define ("CACHECOUNT",1);

// ------------------------------------------------------
// umask for file access
// ------------------------------------------------------

define ("UMASK",0775);

// ------------------------------------------------------
// initalize system, require all core classes
// ------------------------------------------------------

require_once (BASEPATH . "buildinfo.inc.php");
require_once (SYSTEMPATH . "_constants.inc.php");
require_once (CLASSPATH . "PhenotypeBase.class.php");
require_once (CLASSPATH . "Phenotype.class.php");
require_once (SYSTEMPATH . "_autoloader.inc.php");
require_once (SYSTEMPATH . "_helper.inc.php");

require_once (CLASSPATH . "PhenotypePage.class.php");
require_once (CLASSPATH . "Database.class.php");
require_once (CLASSPATH . "SqlBuilder.class.php");
require_once (CLASSPATH . "PhenotypeApplication.class.php");
require_once (CLASSPATH . "PhenotypeRequest.class.php");
require_once (CLASSPATH . "TCheck.class.php");
require_once (CLASSPATH . "PhenotypeLog.class.php");


// ------------------------------------------------------
// logging
// ------------------------------------------------------

define("PT_LOG_METHOD", PT_LOGMTH_FILE);
define("PT_LOG_LOGFILE", TEMPPATH ."/logs/phenotype.log");
define("PT_LOG_TIMEFORMAT", "d/M/Y H:i:s O");
define("PT_LOG_CLIENTINFO_HEADER", '');
define("PT_LOG_CLIENTINFO_SERVER", 'REMOTE_ADDR');


// ------------------------------------------------------
// get host config (if necessary)
// ------------------------------------------------------

require_once (APPPATH . "_host.config.inc.php");

// ------------------------------------------------------
// start engine
// ------------------------------------------------------

// Time check initialize
$myTC = new TCheck();
$myTC->start();

//date_default_timezone_set('Etc/GMT-1');
require_once (APPPATH . "_application.inc.php");
$myPT = new Phenotype();
$myDB = new PhenotypeDatabase();
$myDB->connect();
$myApp = new PhenotypeApplication();
require_once (SYSTEMPATH . "_init.inc.php");
$myLog = new PhenotypeLog();

$myRequest = new PhenotypeRequest();
if ($myRequest->code404){$myApp->throw404();}