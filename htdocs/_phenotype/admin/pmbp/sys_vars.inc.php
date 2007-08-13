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

// system variables are documented in documents/SYSTEM_VARIABLES.txt

// set general system variables 
$update=FALSE;
if (!isset($PMBP_SYS_VAR['last_scheduled'])) {
    $PMBP_SYS_VAR['last_scheduled']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['this_login'])) {
    $PMBP_SYS_VAR['this_login']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['last_login'])) {
    $PMBP_SYS_VAR['last_login']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['security_key']) && $CONF['sql_passwd']) {
    $PMBP_SYS_VAR['security_key']=md5("phpMyBackupPro".$CONF['sql_passwd']);
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['dir_lists'])) {
    $PMBP_SYS_VAR['dir_lists']=2;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['memory_limit'])) {
    $PMBP_SYS_VAR['memory_limit']=9500000*4; // (less than) 4 mb
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['except_tables'])) {
    $PMBP_SYS_VAR['except_tables']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['scheduled_debug'])) {
    $PMBP_SYS_VAR['scheduled_debug']=0;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['schedule_all_dbs'])) {
     $PMBP_SYS_VAR['schedule_all_dbs']=0;
    $update=TRUE;
}

// update login sys vars
if (isset($_GET['login']) || isset($_POST['login'])) {
    $PMBP_SYS_VAR['last_login']=$PMBP_SYS_VAR['this_login'];
    $PMBP_SYS_VAR['this_login']=strftime($CONF['date'],time())." (IP: ".$_SERVER['REMOTE_ADDR'].")";
    $update=TRUE;
}

// functions.inc.php
if (!isset($PMBP_SYS_VAR['F_dbs'])) {
    $PMBP_SYS_VAR['F_dbs']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_comment'])) {
    $PMBP_SYS_VAR['F_comment']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_tables'])) {
    $PMBP_SYS_VAR['F_tables']=1;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_data'])) {
    $PMBP_SYS_VAR['F_data']=1;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_drop'])) {
    $PMBP_SYS_VAR['F_drop']=1;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_compression'])) {
    $PMBP_SYS_VAR['F_compression']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_ftp_dirs'])) {
    $PMBP_SYS_VAR['F_ftp_dirs']="";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['F_ftp_dirs_2'])) {
    $PMBP_SYS_VAR['F_ftp_dirs_2']="";
    $update=TRUE;
}

// scheduled.php
if (!isset($PMBP_SYS_VAR['EXS_scheduled_file'])) {
     $PMBP_SYS_VAR['EXS_scheduled_file']="???.php";
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['EXS_scheduled_dir'])) {
     $PMBP_SYS_VAR['EXS_scheduled_dir']=0;
    $update=TRUE;
}
if (!isset($PMBP_SYS_VAR['EXS_period'])) {
     $PMBP_SYS_VAR['EXS_period']="";
    $update=TRUE;
}

// save global_conf.php
if ($update) PMBP_save_global_conf();
?>