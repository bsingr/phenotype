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

require_once("login.php");

// needed to divide between zip files and other files
// zip files are read at once, other files line by line
function helpfkt($lines,$import_file,$lineCounter) {
    if ($lines=="") {
        return PMBP_getln("./".PMBP_EXPORT_DIR.$import_file);
    } else {
        if ($lineCounter>=count($lines)) return null;
        if ($lines[$lineCounter]=="") return " "; else return $lines[$lineCounter];
    }
}
            
PMBP_print_header(ereg_replace(".*/","",$_SERVER['SCRIPT_NAME']));

// used variables
if (!isset($_GET['import'])) $_GET['import']=FALSE;
if (!isset($_GET['imp_ALL'])) $_GET['imp_ALL']=FALSE;
if (!isset($_GET['del'])) $_GET['del']=FALSE;
if (!isset($_GET['del_ALL'])) $_GET['del_ALL']=FALSE;
if (!isset($_GET['empty_ALL'])) $_GET['empty_ALL']=FALSE;
if (!isset($_GET['del_all'])) $_GET['del_all']=FALSE;
if (!isset($_GET['empty_all'])) $_GET['empty_all']=FALSE;

// if first use or no db-connection possible
if (!$con=@mysql_connect($CONF['sql_host'],$CONF['sql_user'],$CONF['sql_passwd'])) echo "<div class=\"red\">".I_SQL_ERROR."</div>";

// if importing sql
if ($_GET['import'] || $_GET['imp_ALL']) {

    // get start time to calculate duration
    if (function_exists("microtime")) {
        $microtime=explode(" ",microtime());
        $starttime=($microtime[0]+$microtime[1]);
    } else {
        $starttime=time();
    }

    if ($_GET['import']) {
        $import_files[]=$_GET['import'];
    } else {
        $all_files=PMBP_get_backup_files();
        foreach($all_files as $file) {
            $db=PMBP_file_info("db","./".PMBP_EXPORT_DIR.$file);
            $time=PMBP_file_info("time","./".PMBP_EXPORT_DIR.$file);

            // define variable and set time
            if (!isset($last_files[$db])) $last_files[$db][1]=-1;
            
            // update time
            if ($time>$last_files[$db][1]) {
                   $last_files[$db][0]=$file;
                   $last_files[$db][1]=$time;
            }
        }
        if (isset($last_files))
            foreach($last_files as $last_file) $import_files[]=$last_file[0];
    }

    if (isset($import_files)) {
    
        // set php timelimit
        @set_time_limit($CONF['timelimit']);
        @ignore_user_abort(TRUE);
        
        // import each file
        foreach($import_files as $import_file) {
        
            // check if a db with the name of the backup file still exists and select it
            $db=mysql_select_db($dbname=PMBP_file_info("db","./".PMBP_EXPORT_DIR.$import_file));
            if (!$db) {
                printf(".<div class=\"red\">".PMBP_EX_NO_AVAILABLE."</div>\n",$dbname);
            } else {
        
                $data_queries=array();
                $table_queries=array();
                $table_count=0;
                $table=FALSE;
                $lineCounter=0;
                
                // uncompress zip backup files
                if (PMBP_file_info("zip","./".PMBP_EXPORT_DIR.$import_file)=="zip") {
                    $lines=PMBP_unzip("lines","./".PMBP_EXPORT_DIR.$import_file);
                } else {
                    $lines="";            
                }
        
                // divide insert and create sql queries
                // $table is set to TRUE if the next line belongs to a 'create' sql query
                while($line=helpfkt($lines,$import_file,$lineCounter++)) { // helpfkt is defined above
                    // if line is not empty
                    $line=trim($line);                
                    if ($line) {
                                    
                        // the last line did not belong to a 'create' sql query
                        if (!$table) {          
        
                            // this line does not, too
                            if (strtolower(substr($line,0,6))=="insert") {
                                $data_queries[]=substr($line,0,strlen($line)-1);
            
                            // this line does!
                            } elseif (strtolower(substr($line,0,12))=="create table") {
                                $table=TRUE;
                                $table_count++;
                                $table_queries[]=$line."\n";
            
                            // this line does not (it is a comment)
                            } elseif (strtolower(substr($line,0,1))=="#" || substr($line,0,2)=="--") {
                                continue;                    
                            
                            // this line does not, too (it is something like "use table" or "drop table")
                            } else {
                                $table_queries[]=substr($line,0,strlen($line)-1);
                            }                
            
                        // the current line belongs to a create sql query
                        } else {
            
                            // create sql query ending in this line
                            if (strtolower(substr($line,0,1))==")") $table=FALSE;
                            $table_queries[count($table_queries)-1] .= $line."\n";
                        }
                    }            
                }
        
                $table_errors=0;
                $data_errors=0;
                
                // execute create tables queries
                if (is_array($table_queries)) {
                    foreach($table_queries as $table_query) {
                        if (!mysql_query($table_query)) {
                            $table_errors++;
                            if ($CONF['import_error']) echo nl2br(htmlentities($table_query))."\n<div class=\"bold_left\">".htmlentities(mysql_error())."</div><br>\n";
                        }
                    }
                }
        
                // execute insert data queries
                if (is_array($data_queries)) {
                    foreach($data_queries as $data_query) {
                        if (!mysql_query($data_query)){
                            $data_errors++;
                            if ($CONF['import_error']) echo htmlentities($data_query)."\n<div class=\"bold_left\">".htmlentities(mysql_error())."</div><br>\n";
                        }
                    }
                }
        
                // show number successful executed querys or if an error did occur
                if ($data_errors+$table_errors) echo "<div class=\"red\">".sprintf(IM_ERROR,($data_errors+$table_errors))."</div>\n";
                echo "<div class=\"green\">".IM_SUCCESS." ".($table_count-$table_errors)." ".IM_TABLES." ".(count($data_queries)-$data_errors)." ".IM_ROWS." (".$import_file.")</div>\n";
            }        
        }
    }
    // show execution duration
    if (function_exists("microtime")) {
        $microtime=explode(" ",microtime());
        $endtime=($microtime[0]+$microtime[1]);
    } else {
        $endtime=time();
    }    
    echo "<div class=\"bold\">".F_DURATION.": ".number_format($endtime-$starttime,3)." ".F_SECONDS."</div><br>\n";
}

// remove old backup files and get list of backup files
$all_files=PMBP_get_backup_files();

// delete ALL backup files if the link was clicked
if ($_GET['del_ALL']) {
    if (is_array($all_files))
    foreach($all_files as $filename) {
        PMBP_delete_backup_files($filename);
    }
    echo "<div class=\"green\">".B_DELETED_ALL.".</div>\n";
}

// empty ALL db if the link was clicked
if ($_GET['empty_ALL']) {
    $all_db=PMBP_get_db_list();
    foreach($all_db as $dbname) {
        $db=mysql_select_db($dbname,$con);
        $res=mysql_list_tables($dbname,$con);
        for ($i=0;$i<mysql_num_rows($res);$i++) {
            $row=mysql_fetch_row($res);
            $tablename=$row[0];
            mysql_query("drop table `".$tablename."`",$con);
        }
    }

    $error=mysql_error();
    if ($error) echo $error;
        else echo "<div class=\"green\">".B_EMPTIED_ALL.".</div>\n";
}

// empty db if the link was clicked
if ($_GET['empty_all']) {
    $db=mysql_select_db($_GET['empty_all']);
    if (!$db) {
        printf(".<div class=\"red\">".PMBP_EX_NO_AVAILABLE."</div>\n",$_GET['empty_all']);
    } else {    
        $res=mysql_list_tables($_GET['empty_all']);
        for ($i=0;$i<mysql_num_rows($res);$i++) {
            $row=mysql_fetch_row($res);
            $tablename=$row[0];
            mysql_query("drop table `".$tablename."`");
        }
        $error=mysql_error();
        if ($error) echo $error;
            else echo "<div class=\"green\">".B_EMPTIED.".</div>\n";
    }
}

// delete all backup files of selected db if the link was clicked
if ($_GET['del_all']) {
    if (is_array($all_files))
    foreach($all_files as $filename) {
        if ($_GET['del_all']==PMBP_file_info("db","./".PMBP_EXPORT_DIR.$filename)) {
            PMBP_delete_backup_files($filename);
        }
    }
    echo "<div class=\"green\">".B_DELETED_ALL.".</div>\n";
}

// delete selected backup file if the link was clicked
if ($_GET['del']) {
    echo $out=PMBP_delete_backup_files($_GET['del']);
    echo "<div class=\"green\">".B_DELETED.".</div>\n";
}

// get new list of backup files
$all_files=PMBP_get_backup_files();

echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"0\" width=\"100%\">\n";

// list all backup files
if (is_array($all_files)) {
    $last_backup=0;
    $size_sum=0;

    // print html table
    foreach($all_files as $filename) {
        $file="./".PMBP_EXPORT_DIR.$filename;
        
        // generate one row for the db name
        if (!isset($printed_title[$db=PMBP_file_info("db",$file)])) {
            $printed_title[$db]=TRUE;
            echo "<tr><th colspan=\"8\" class=\"active\">".$db." <span class=\"standard\">".PMBP_confirm(B_CONF_EMPTY_DB,"import.php?empty_all=".$db,"[".B_EMPTY_DB."]");
            echo "&nbsp;".PMBP_confirm(B_CONF_DEL_ALL,"import.php?del_all=".$db,"[".B_DELETE_ALL."]")."</span></th></tr>\n";
        }

        echo "<tr ".PMBP_change_color("#FFFFFF","#000000").">\n<td class=\"list\">\n".$filename."</td>\n";
        echo "<td class=\"list\">".strftime($CONF['date'],$time=PMBP_file_info("time",$file))."</td>\n";
        if ($time>$last_backup) $last_backup=$time;
        $size_sum+=$size=PMBP_file_info("size",$file);
        $size=PMBP_size_type($size);
        echo "<td class=\"list\">".$size['value']." ".$size['type']."</td>\n";
        echo "<td class=\"list\">".PMBP_pop_up("file_info.php?file=".$filename,B_INFO,"info")."</td>\n";
        echo "<td class=\"list\">".PMBP_pop_up("get_file.php?view=".$file,B_VIEW,"view")."</td>\n";
        echo "<td class=\"list\"><a href=\"get_file.php?download=true&view=".$filename."\">".B_DOWNLOAD."</a></td>\n";
        if ($con) echo "<td class=\"list\">".PMBP_confirm(B_CONF_IMP,"import.php?import=".$filename,B_IMPORT,$CONF['confirm'])."</td>\n";
            else echo "<td class=\"list\">".B_IMPORT."</td>\n";
        echo "<td class=\"list\">".PMBP_confirm(B_CONF_DEL,"import.php?del=".$filename,B_DELETE,$CONF['confirm'])."</td>\n</tr>\n";
    }
    
    // print delete ALL backups and empty ALL dbs links if backup files are available
    $size_sum=PMBP_size_type($size_sum);
    echo "<tr><td colspan=\"7\"><br><div class=\"bold\">".B_SIZE_SUM.": ".$size_sum['value']." ".$size_sum['type']." - ".B_LAST_BACKUP.": ".strftime($CONF['date'],$last_backup)."</div></td></tr>\n";
    echo "<tr><td colspan=\"7\"><div class=\"bold\">".PMBP_confirm(B_CONF_EMPT_ALL,"import.php?empty_ALL=TRUE","[".B_EMPTY_ALL."]",$CONF['confirm']);
    echo "&nbsp;".PMBP_confirm(B_CONF_IMP_ALL,"import.php?imp_ALL=TRUE","[".B_IMPORT_ALL."]",$CONF['confirm'])."\n";
    echo "&nbsp;".PMBP_confirm(B_CONF_DEL_ALL_2,"import.php?del_ALL=TRUE","[".B_DELETE_ALL_2."]",$CONF['confirm'])."<br>&nbsp;</div></td>\n</tr>\n";
} else {

    // if there are no backup files
    echo "<tr>\n<td><div class=\"bold\">".B_NO_FILES.".</div>\n</td>\n</tr>\n";
}

echo "</table>\n";
PMBP_print_footer();
?>
