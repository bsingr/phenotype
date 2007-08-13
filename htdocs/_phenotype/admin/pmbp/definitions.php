<?php
/*
 +--------------------------------------------------------------------------+
 | phpMyBackupPro                                                           |
 +--------------------------------------------------------------------------+
 | Copyright (c) 2004-2006 by Dirk Randhahn                                 |                               
 | http://www.phpMyBackupPro.net                                            |
 | version information can be found in definitions.php.                     |
 |                                                                          |
 | This program is free software; you can redistribute it and/or            |
 | modify it under the terms of the GNU General Public License              |
 | as published by the Free Software Foundation; either version 2           |
 | of the License, or (at your option) any later version.                   |
 |                                                                          |
 | This program is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 | GNU General Public License for more details.                             |
 |                                                                          |
 | You should have received a copy of the GNU General Public License        |
 | along with this program; if not, write to the Free Software              |
 | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,USA.|
 +--------------------------------------------------------------------------+
*/

// ---- adjust these two lines to your file system. The pathes must be relative to this file! ----- //

define('PMBP_GLOBAL_CONF',"global_conf.php");    // example: define('PMBP_GLOBAL_CONF',"../../files/global_conf.php");

// Attention! Will only work, if Phenotype is installed within it's default directory structure, i.e. TEMPPATH ist not changed.

$_PMBP_EXPORT_DIR="../../../../_phenotype/temp/backup/";                     // example: $_PMBP_EXPORT_DIR="../../files/export/";

// ---- adjust this line, only if you are going to backup from several database servers or if you have to use different accounts ---- //

define('PMBP_GLOBAL_CONF_SQL',"global_conf_sql.php");    // example: define('PMBP_GLOBAL_CONF',"../../files/global_conf_sql.php");

// ---- no need to modify anything more! ---- //

// definitions
define('PMBP_VERSION',"v.1.8");  // This is the version of this phpMyBackupPro release

define('PMBP_MAIN_INC',"./functions.inc.php");
define('PMBP_JAVASCRIPTS',"javascripts.js");
define('PMBP_STYLESHEET_DIR',"stylesheets/");
define('PMBP_LANGUAGE_DIR',"language/");
define('PMBP_IMAGE_DIR',"images/");
define('PMBP_WEBSITE',"http://www.phpMyBackupPro.net");

// includes
if (!@include_once(PMBP_GLOBAL_CONF)) {
    echo "global_conf.php is missing.<br>Please read INSTALL.txt and specify the global_conf.php path in definitions.php.";
    exit;
} else {
    if (!isset($CONF['login'])) {
        echo "global_conf.php is incomplete. Please update it with a valid copy of the global_conf.php file.";
        exit;
    }
}

if (!function_exists("mysql_connect")) {
    echo "The MySQL module for PHP seems not to be installed correctly.<br>
    You can configure the MySQL module in php.ini. Read the HTTP servers (eg. Apache) log files for more infomation.";
    exit;    
}

// define arrays fro several server mode
$CONF['sql_host_s']=array();
$CONF['sql_user_s']=array();
$CONF['sql_passwd_s']=array();
$CONF['sql_db_s']=array();
require_once(PMBP_MAIN_INC);
@include_once(PMBP_GLOBAL_CONF_SQL);    

// set working sql server
if (count($CONF['sql_host_s'])) {
    // set working server and register session vars
    if (!isset($_SESSION['sql_host_org'])) $_SESSION['sql_host_org']=$CONF['sql_host'];
    if (!isset($_SESSION['sql_user_org'])) $_SESSION['sql_user_org']=$CONF['sql_user'];
    if (!isset($_SESSION['sql_passwd_org'])) $_SESSION['sql_passwd_org']=$CONF['sql_passwd'];
    if (!isset($_SESSION['sql_db_org'])) $_SESSION['sql_db_org']=$CONF['sql_db'];    
    if (!isset($_SESSION['wss'])) $_SESSION['wss']=-1;

    if(isset($_POST['mysql_host'])) $_SESSION['wss']=$_POST['mysql_host'];

    // load setting from $_SESSION['wss'] as long we are not on the config page and if the host data are still in global_conf_sql.php
    // otherwise set to original host
    if ($_SESSION['wss']<0 || basename($_SERVER['SCRIPT_NAME'])=="config.php" || !isset($CONF['sql_host_s'][$_SESSION['wss']]) ) {
        $CONF['sql_host']=$_SESSION['sql_host_org'];
        $CONF['sql_user']=$_SESSION['sql_user_org'];
        $CONF['sql_passwd']=$_SESSION['sql_passwd_org'];
        $CONF['sql_db']=$_SESSION['sql_db_org'];
    } else {
        $CONF['sql_host']=$CONF['sql_host_s'][$_SESSION['wss']];
        $CONF['sql_user']=$CONF['sql_user_s'][$_SESSION['wss']];
        $CONF['sql_passwd']=$CONF['sql_passwd_s'][$_SESSION['wss']];
        $CONF['sql_db']=$CONF['sql_db_s'][$_SESSION['wss']];        
    }
}

// try to create export sub directories
if (count($CONF['sql_host_s']) && basename($_SERVER['SCRIPT_NAME'])!=="config.php") {
    // multi db mode
    if ($_SESSION['wss']<0) {
        // main account from global_conf.php
        define('PMBP_EXPORT_DIR',$_PMBP_EXPORT_DIR);    
    } else {
        // other accounts        
        define('PMBP_EXPORT_DIR',$_PMBP_EXPORT_DIR.$CONF['sql_host']."_".$CONF['sql_user']."/");            
    }
} else {
    // single db mode
    define('PMBP_EXPORT_DIR',$_PMBP_EXPORT_DIR);
}
@umask(0000);
@mkdir(PMBP_EXPORT_DIR,0777);                

// check if language was just changed in config.php
if (isset($_POST['lang']) && ereg_replace(".*/","",$_SERVER['PHP_SELF'])=="config.php") $CONF['lang']=$_POST['lang'];

// include language.inc.php
if (!isset($CONF['lang'])) $CONF['lang']="english";
if (!file_exists("./".PMBP_LANGUAGE_DIR.$CONF['lang'].".inc.php")) include_once("./".PMBP_LANGUAGE_DIR."english.inc.php"); else include("./".PMBP_LANGUAGE_DIR.$CONF['lang'].".inc.php");

// set local time to defined or environment variable value
if (function_exists("phpversion")) {
    $tmp=@phpversion();
    $phpvers=$tmp[0].$tmp[1].$tmp[2];
} else {
    $phpvers="0";
}
if (defined("BD_LANG_SHORTCUT") AND $phpvers>=4.3) setlocale(LC_TIME,BD_LANG_SHORTCUT,BD_LANG_SHORTCUT."_".strtoupper('BD_LANG_SHORTCUT')); else setlocale(LC_TIME,"");

// special part for arabic language
if ($CONF['lang']=="arabic") define('ARABIC_HTML'," dir=\"rtl\""); else define('ARABIC_HTML',"");

// update the system variables
include("sys_vars.inc.php");
?>
