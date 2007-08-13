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

// standard debug function
function PMBP_debug($object) {
    echo "<pre>";
    print_r($object);
    echo "</pre>";
}


// prints the basis html header in the $lang language with $scriptname scriptname
function PMBP_print_header($scriptname) {
    global $CONF;
    global $_POST;
    
    if (!isset($CONF['stylesheet'])) $CONF['stylesheet']="standard";
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C/
    /DTD HTML 4.01//EN\"
   \"http://www.w3.org/TR/html4/loose.dtd\">
<html".ARABIC_HTML.">
<head>
<title>phpMyBackupPro ".PMBP_VERSION."</title>
<meta http-equiv=\"Content-Type\" content=\"text/html;charset=".BD_CHARSET_HTML."\">
<meta name=\"robots\" content=\"noindex\">
<meta name=\"robots\" content=\"nofollow\">
<link href=\"images/favicon.png\" type=\"image/png\" rel=\"icon\">
<link rel=\"stylesheet\" href=\"".PMBP_STYLESHEET_DIR.$CONF['stylesheet'].".css\" type=\"text/css\">
";
    readfile(PMBP_JAVASCRIPTS);
    // define menue
    $menu=array("index.php"=>F_START,"config.php"=>F_CONFIG,"import.php"=>F_IMPORT,"backup.php"=>F_BACKUP,"scheduled.php"=>F_SCHEDULE,"db_info.php"=>F_DB_INFO,"sql_query.php"=>F_SQL_QUERY);
    $accesskeys=array("index.php"=>"m","config.php"=>"c","import.php"=>"i","backup.php"=>"b","scheduled.php"=>"s","db_info.php"=>"d","sql_query.php"=>"q","logout"=>"l","help"=>"h");
    $simple_width=140;
    $width=count($menu)*$simple_width;
    
    echo "</head>

<body>
<table width=\"".$width."\">
 <colgroup>
  <col span=\"".count($menu)."\" width=\"".$simple_width."\">
 </colgroup>
 <tr>
  <th colspan=\"".count($menu)."\" class=\"active\" id=\"menu\">\n";
  // print titel
  echo "<div id=\"logo\">\n";
  echo PMBP_image_tag("logo.png","phpMyBackupPro","phpMyBackupPro Homepage",PMBP_WEBSITE);
  echo "&nbsp;&nbsp;".PMBP_VERSION."\n";
  echo "</div>\n<div id=\"help\">\n";
    // generate popup link for proper help file
    if (!file_exists("./".PMBP_LANGUAGE_DIR.$CONF['lang']."_help.php")) echo PMBP_pop_up("./".PMBP_LANGUAGE_DIR."english_help.php?script=".$scriptname,PMBP_image_tag("help.gif","",F_HELP).F_HELP,"help");
        else echo  PMBP_pop_up("./".PMBP_LANGUAGE_DIR.$CONF['lang']."_help.php?script=".$scriptname,PMBP_image_tag("help.gif","",F_HELP).F_HELP,"help");

    echo "\n</div>\n<div id=\"logout\">\n";
    // print logout link if function is not disabled
    if (!($CONF['no_login']=="1" && $CONF['login']=="0")) {
        echo "<a href=\"login.php?logout=TRUE\" accesskey=\"l\">";
        echo PMBP_image_tag("login.gif","",F_LOGOUT);
        echo F_LOGOUT."</a>\n";
    }
    echo "\n</div>\n";
    echo "  </th>\n";

// print selection for several sql servers
if (count($CONF['sql_passwd_s']) && basename($_SERVER['SCRIPT_NAME'])!=="config.php" && !isset($_POST['period'])) {
    echo " </tr>
 <tr>
  <th colspan=\"".count($menu)."\">
  <form action=\"".basename($_SERVER['SCRIPT_NAME'])."\" method=\"POST\">
  <span class=\"bold_left\">Select working SQL server:</span>
  <select name=\"mysql_host\" onchange=\"submit()\">\n";
    if ($CONF['sql_host']==$_SESSION['sql_host_org'] && $CONF['sql_user']==$_SESSION['sql_user_org']) echo "<option value=\"-1\" selected>".$_SESSION['sql_host_org']." (".$_SESSION['sql_user_org'].")</option>\n";
        else echo "<option value=\"-1\">".$_SESSION['sql_host_org']." (".$_SESSION['sql_user_org'].")</option>\n";
    for($i=0;$i<count($CONF['sql_passwd_s']);$i++) {

        if (isset($CONF['sql_host_s'])) {
            if ($CONF['sql_host']==$CONF['sql_host_s'][$i] && $CONF['sql_user']==$CONF['sql_user_s'][$i]) echo "<option value=\"".$i."\" selected>".$CONF['sql_host_s'][$i]." (".$CONF['sql_user_s'][$i].")</option>\n";
                else echo "<option value=\"".$i."\">".$CONF['sql_host_s'][$i]." (".$CONF['sql_user_s'][$i].")</option>\n";
        } else {
            echo "<option value=\"".$i."\">".$CONF['sql_host_s'][$i]." (".$CONF['sql_user_s'][$i].")</option>\n";
        }
    }
    echo "  </select></form>
  </th>\n";
}

echo " </tr>
 <!-- MENU -->
 <tr>\n";

    // generate menu
    foreach($menu as $filename=>$title) {

        // print active link
        if ($filename==$scriptname && $filename!="login.php?logout=TRUE" && $filename!="HELP") {
            echo "  <th class=\"active\">\n   <a href=\"".$filename."\" accesskey=\"".$accesskeys[$filename]."\">".PMBP_image_tag(substr($filename,0,strpos($filename,".")).".gif","",$title).$title."</a>\n  </th>\n";

        // print lasting menu
        } elseif ($filename!="login.php?logout=TRUE" && $filename!="HELP") {
            echo "  <th>\n   <a href=\"".$filename."\" accesskey=\"".$accesskeys[$filename]."\">".PMBP_image_tag(substr($filename,0,strpos($filename,".")).".gif","",$title).$title."</a>\n  </th>\n";
        }
        
    }

    echo " </tr>
</table>
<table width=\"".$width."\">
 <colgroup>
  <col width=\"20\">
  <col width=\"*\">
  <col width=\"20\">
 </colgroup>
 <tr>
  <td>
    &nbsp;
  </td>
  <td class=\"main\">
<!-- HEADER END -->
";
}


// print basis html footer
function PMBP_print_footer() {
    echo "\n<!-- FOOTER -->
  </td>
  <td>
    &nbsp;
  </td>
 </tr>
</table>
<table width=\"980\">
 <tr>
  <th colspan=\"7\" class=\"active\">\n";
   printf(F_FOOTER,"<a href=\"".PMBP_WEBSITE."\">","</a>");
   echo "\n</th>\n </tr>\n";

    // set to 0 if you don't want to check for updates any more
    if (1) {
        // do this only once per session
        if (!isset($_SESSION['PMBP_VERSION'])) {
            $_SESSION['PMBP_VERSION']=FALSE;
            // ping command depends on server OS
            if (strpos($_SERVER['SERVER_SOFTWARE'],"Win")) $ping="ping -n 1 -w 500 phpmybackup.sourceforge.net";
                else $ping="ping -c 1 -w 1 phpmybackup.sourceforge.net";
            // check if there is a good internet connection. Then look for a newer version of phpMyBackupPro
            @exec($ping,$dontcare=array(),$ping_res);
            if (!$ping_res) {
                @set_time_limit("2");
                $last_vers=@file("http://phpmybackup.sourceforge.net/vers.php?v=".PMBP_VERSION);
                if ($last_vers[0]!=PMBP_VERSION) $_SESSION['PMBP_VERSION']=TRUE;
            }
        }
    
        // new version found, print hint
        if ($_SESSION['PMBP_VERSION']) {
            echo "\n <tr>
      <td class=\"red\">
        ";
            printf(F_NOW_AVAILABLE,"<a href=\"".PMBP_WEBSITE."\">","</a>");
            echo " !!!
      </td>
     </tr>\n";
        }
    }

    // set to 0 if you don't want to see the PHP version hint any more
    if (1) {    
        // check PHP version
        $tmp=phpversion();
        $phpvers=$tmp[0].$tmp[1].$tmp[2];
        if ($phpvers<4.3) echo "<tr><td>PHP ".$tmp." detected. It is not recommended to use phpMyBackupPro with PHP < PHP 4.3. You can disable this message if you want in functions.inc.php line ".__LINE__.".</td></tr>";
    }
    
    // set to 0 if you don't want to see the Firefox add any more
    if (1)
    echo "
 <tr>
  <td class=\"red\";>

  </td>
 </tr>
 <tr>
  <td>   
   <div id=\"ffadd\" style=\"display:none\" class=\"red\">
    We see that you are using MS Internet Explorer. We recommend to install Mozilla Firefox for faster and safer surfing. Get it here:
    <a href=\"http://www.getfirefox.com\">
    <img alt=\"Get Firefox!\" title=\"Get Firefox!\" src=\"http://sfx-images.mozilla.org/affiliates/Banners/468x60/trust.png\"/>
    </a>
   </div>
  </td>
 </tr>";

echo "
</table>
</body>
</html>
";
}


// prints html export form used on several pages
function PMBP_print_export_form($dirs1=FALSE) {
    global $CONF;
    global $PMBP_SYS_VAR;
    
    echo "\n<table width=\"940\">\n";
    echo "<tr>\n<td>\n";
    echo F_SELECT_DB.":\n";
    echo "</td>\n<td>&nbsp;</td>\n<td>";
    echo F_COMMENTS.":";
    echo "</td>\n</tr><tr>\n<td>\n";
    echo "<select name=\"db[]\" multiple=\"multiple\" size=\"10\">\n";
    if (!$con=@mysql_connect($CONF['sql_host'],$CONF['sql_user'],$CONF['sql_passwd']));

    // find the availabe compression methods and set which are disabled and which is selected
    if (!@function_exists("gzopen") || !@function_exists("gzcompress")) $disable_gzip=" disabled"; else $disable_gzip="";

    $last_dbs=explode("|",$PMBP_SYS_VAR['F_dbs']);
    if (count(PMBP_get_db_list())>0) {
        foreach(PMBP_get_db_list() as $db) {
            if(in_array($db, $last_dbs)) {
                echo "<option value=\"".$db."\" selected>".$db."</option>\n";
            } else {
                echo "<option value=\"".$db."\">".$db."</option>\n";
            }
        }
    } else {
        echo "<option></option>\n";
    }
    echo "</select>\n<br>";
    echo PMBP_set_select("backup","db[]","[".F_SELECT_ALL."]");
    echo "\n</td>\n<td>&nbsp;</td>\n<td>\n";
    echo "<textarea name=\"comments\" rows=\"9\" cols=\"80\">".$PMBP_SYS_VAR['F_comment']."</textarea>\n<br>";
    if($PMBP_SYS_VAR['F_tables']) $checked="checked"; else $checked="";
    echo "<input type=\"checkbox\" name=\"tables\" ".$checked.">".F_EX_TABLES." | ";
    if($PMBP_SYS_VAR['F_data']) $checked="checked"; else $checked="";    
    echo "<input type=\"checkbox\" name=\"data\" ".$checked.">".F_EX_DATA." | ";
    if($PMBP_SYS_VAR['F_drop']) $checked="checked"; else $checked="";    
    echo "<input type=\"checkbox\" name=\"drop\" ".$checked.">".F_EX_DROP." | ";

    $comp_off=$comp_gzip=$comp_zip="";
    if($PMBP_SYS_VAR['F_compression']=="gzip" && !$disable_gzip) $comp_gzip=" selected";
        elseif($PMBP_SYS_VAR['F_compression']=="zip") $comp_zip=" selected";
            else $comp_off=" selected";
            
    echo F_EX_COMP."
<select name=\"zip\">
<option".$comp_off." value=\"\">".F_EX_OFF."</option>
<option ".$comp_gzip." ".$disable_gzip." value=\"gzip\">".F_EX_GZIP."</option>
<option".$comp_zip." value=\"zip\">".F_EX_ZIP."</option>
</select>\n</td>\n</tr>\n</table>\n<p></p>\n";

    // show directory backup form
    if ($CONF['dir_backup']) {
        if (!is_array($dirs1) && $PMBP_SYS_VAR['dir_lists']>=1) $dirs1=PMBP_get_dirs("../");
        
        $last_dirs=explode("|",$PMBP_SYS_VAR['F_ftp_dirs']);

        echo "\n\n<table width=\"940\">\n";
        echo "<tr>\n<td>\n";
        echo EX_DIRS.":<br>(<a href=\"scheduled.php?update_dir_list=TRUE\">".PMBP_EXS_UPDATE_DIRS."</a>)<br>\n";
        echo "</td>\n<td>&nbsp;</td>\n<td>\n";
        echo EX_DIRS_MAN.":<br>\n";
        echo "</td>\n</tr><tr>\n<td>";
        echo "<select name='dirs[]' multiple=\"multiple\" size=\"9\">";
        foreach($dirs1 as $value) {
            if (in_array("../".$value, $last_dirs)) {            
                echo "<option value=\""."../".$value."\" selected>"."../".$value."</option>\n";
            } else {
                echo "<option value=\""."../".$value."\">"."../".$value."</option>\n";            
            }
        }
        echo "</select>\n";
        echo "\n</td>\n<td>&nbsp;</td>\n<td>\n";
        echo "<textarea rows=\"8\" cols=\"63\" name=\"man_dirs\">".$PMBP_SYS_VAR['F_ftp_dirs_2']."</textarea>";
        echo "</td>\n</tr>\n</table>\n<p></p>\n";
    }
}


// checks if settings on the export form where made and saves them
function PMBP_save_export_settings() {
    global $PMBP_SYS_VAR;

    // check if any settings have changed
    if ($PMBP_SYS_VAR['F_data']!=$_POST['data'] OR $PMBP_SYS_VAR['F_tables']!=$_POST['tables'] OR
    $PMBP_SYS_VAR['F_compression']!=$_POST['zip'] OR $PMBP_SYS_VAR['F_drop']!=$_POST['drop']) {            
        $PMBP_SYS_VAR['F_data']=$_POST['data'];
        $PMBP_SYS_VAR['F_tables']=$_POST['tables'];
        $PMBP_SYS_VAR['F_compression']=$_POST['zip'];
        $PMBP_SYS_VAR['F_drop']=$_POST['drop'];
    }

    if (isset($_POST['db'])) {
        if (is_array($_POST['db'])) {
             if ($PMBP_SYS_VAR['F_dbs']!=implode("|",$_POST['db'])) {
                 $PMBP_SYS_VAR['F_dbs']=implode("|",$_POST['db']);
             }
        } else {
            $PMBP_SYS_VAR['F_dbs']="";
        }
    } else {
        $PMBP_SYS_VAR['F_dbs']="";
    }
            
     if ($PMBP_SYS_VAR['F_comment']!=$_POST['comments']) {
        $PMBP_SYS_VAR['F_comment']=$_POST['comments'];
    }

    if (isset($_POST['dirs'])) {
         if ($PMBP_SYS_VAR['F_ftp_dirs']!=implode("|",$_POST['dirs'])) {
             $PMBP_SYS_VAR['F_ftp_dirs']=implode("|",$_POST['dirs']);
         }
    } else {
        $PMBP_SYS_VAR['F_ftp_dirs']="";
    }

    if ($PMBP_SYS_VAR['F_ftp_dirs_2']!=$_POST['man_dirs']) {
        $PMBP_SYS_VAR['F_ftp_dirs_2']=$_POST['man_dirs'];
    }
    
    // update global_conf.php
    PMBP_save_global_conf();
}


// generates image tag
function PMBP_image_tag($image,$alt="",$title="",$link=""){
    if (strpos($image,"/")==0) {
        $image=PMBP_IMAGE_DIR.$image;
        $size=getimagesize($image);
    } else {
        $size=getimagesize(PMBP_IMAGE_DIR.basename($image));
    }
    if ($link)
        return "<a href=\"".$link."\"><img src=\"".$image."\" alt=\"".$alt."\" title=\"".$title."\" ".$size[3]."></a>";
    else
        return "<img src=\"".$image."\" alt=\"".$alt."\" title=\"".$title."\" ".$size[3].">";
}


// generates javascript 'select all in input select' link
function PMBP_set_select($form,$select,$link){
    return "<a href=\"\" onclick=\"setSelect('".$form."','".$select."'); return false;\">".$link."</a>";
}


// generates javascript PMBP_pop_up link
function PMBP_pop_up($path,$link,$type){
    return "<a href='javascript:popUp(\"".$path."\",\"".$type."\")'>".$link."</a>";
}


// generates event hanlders to change the border color in a td.list list
function PMBP_change_color($color1,$color2){
    return "onmouseout=\"changeColor(this, '".$color1."');\" onmouseover=\"changeColor(this, '".$color2."');\"";
}


// generates javascript confirm dialog
function PMBP_confirm($text,$path,$link){
    global $CONF;
    switch ($CONF['confirm']) {
        case 0: return "<a href='javascript:confirmClick(\"".$text."\",\"".$path."\")'>".$link."</a>";
        case 1: {
            if (strstr($path,"all") || strstr($path,"ALL")) return "<a href='javascript:confirmClick(\"".$text."\",\"".$path."\")'>".$link."</a>";
                else return "<a href=\"".$path."\">".$link."</a>";
        }
        case 2: {
            if (strstr($path,"ALL")) return "<a href='javascript:confirmClick(\"".$text."\",\"".$path."\")'>".$link."</a>";
                else return "<a href=\"".$path."\">".$link."</a>";
        }
        case 3: return "<a href=\"".$path."\">".$link."</a>";
    }
}


// generates a dump of $db database
// $tables and $data set whether tables or data to backup. $comment sets the commment text
// $drop and $zip tell if to include the drop table statement or dry to pack
function PMBP_dump($db,$tables,$data,$drop,$zip,$comment) {
    global $CONF;
    global $PMBP_SYS_VAR;
    $error=FALSE;
    
    // set max string size before writing to file
    if (@ini_get("memory_limit")) $max_size=900000*ini_get("memory_limit");
        else $max_size=$PMBP_SYS_VAR['memory_limit'];
    
    // set backupfile name
    if ($zip=="gzip") $backupfile=$db.".".$time=time().".sql.gz";
        elseif($zip=="zip") $backupfile=$db.".".($time=time()).".sql.zip";
            else $backupfile=$db.".".$time=time().".sql";
    $backupfile=PMBP_EXPORT_DIR.$backupfile;
                    
    if ($con=@mysql_connect($CONF['sql_host'],$CONF['sql_user'],$CONF['sql_passwd'])) {

        //create comment
        $out="# MySQL dump of database '".$db."' on host '".$CONF['sql_host']."'\n";
        $out.="# backup date and time: ".strftime($CONF['date'],$time)."\n";
        $out.="# built by phpMyBackupPro ".PMBP_VERSION."\n";
        $out.="# ".PMBP_WEBSITE."\n\n";

        // write users comment
        if ($comment) {
            $out.="# comment:\n";
            $comment=preg_replace("'\n'","\n# ","# ".$comment);
            foreach(explode("\n",$comment) as $line) $out.=$line."\n";
            $out.="\n";
        }

        // print "use database" but not if more than one db are available
        if (count(PMBP_get_db_list())>1) {
            $out.="CREATE DATABASE IF NOT EXISTS `".$db."`;\n\n";
            $out.="USE `".$db."`;\n";
        }
        
        // select db
        @mysql_select_db($db);        
        
        // get auto_increment values and names of all tables
        $res=mysql_query("show table status");
        $all_tables=array();
        while($row=mysql_fetch_array($res)) $all_tables[]=$row;

        // get table structures
        foreach ($all_tables as $table) {
            $res1=mysql_query("SHOW CREATE TABLE `".$table['Name']."`");
            $tmp=mysql_fetch_array($res1);
            $table_sql[$table['Name']]=$tmp["Create Table"];
        }

        // find foreign keys
        $fks=array();
        if (isset($table_sql)) {
            foreach($table_sql as $tablenme=>$table) {
                $tmp_table=$table;
                 // save all tables, needed for creating this table in $fks
                while (($ref_pos=strpos($tmp_table," REFERENCES "))>0) {
                    $tmp_table=substr($tmp_table,$ref_pos+12);
                    $ref_pos=strpos($tmp_table,"(");
                    $fks[$tablenme][]=substr($tmp_table,0,$ref_pos);
                }
            }
        }

        // order $all_tables
        $all_tables=PMBP_order_sql_tables($all_tables,$fks);

        // as long as no error occurred
        if (!$error) {
            foreach ($all_tables as $row) {
                $tablename=$row['Name'];
                $auto_incr[$tablename]=$row['Auto_increment'];

                // don't backup tables in $PMBP_SYS_VAR['except_tables']
                if (in_array($tablename,explode(",",$PMBP_SYS_VAR['except_tables'])))
                    continue;
                
                $out.="\n\n";
                // export tables
                if ($tables) {
                    $out.="### structure of table `".$tablename."` ###\n\n";
                    if ($drop) $out.="DROP TABLE IF EXISTS `".$tablename."`;\n\n";
                    $out.=$table_sql[$tablename];

                    // add auto_increment value
                    if ($auto_incr[$tablename]) {
                        $out.=" AUTO_INCREMENT=".$auto_incr[$tablename];
                    }
                    $out.=" ;";
                }
                $out.="\n\n\n";

                // export data
                if ($data && !$error) {
                    $out.="### data of table `".$tablename."` ###\n\n";

                    // check if field types are NULL or NOT NULL
                    $res3=mysql_query("show columns from `".$tablename."`");

                    $res2=mysql_query("select * from `".$tablename."`");
                    for ($j=0;$j<mysql_num_rows($res2);$j++){
                        $out .= "insert into `".$tablename."` values (";
                        $row2=mysql_fetch_row($res2);
                        // run through each field
                        for ($k=0;$k<$nf=mysql_num_fields($res2);$k++) {
                            // identify null values and save them as null instead of ''
                            if (is_null($row2[$k])) $out .="null"; else $out .="'".mysql_escape_string($row2[$k])."'";
                            if ($k<($nf-1)) $out .=", ";
                        }
                        $out .=");\n";

                        // if saving is successful, then empty $out, else set error flag
                        if (strlen($out)>$max_size && $zip!="zip") {
                            if ($out=PMBP_save_to_file($backupfile,$zip,$out,"a")) $out=""; else $error=TRUE;
                        }
                    }

                // an error occurred! Try to delete file and return error status
                } elseif ($error) {
                    @unlink("./".PMBP_EXPORT_DIR.$backupfile);
                    return FALSE;
                }

                // if saving is successful, then empty $out, else set error flag
                if (strlen($out)>$max_size && $zip!="zip") {
                    if ($out=PMBP_save_to_file($backupfile,$zip,$out,"a")) $out=""; else $error=TRUE;
                }
            }
            
        // an error occurred! Try to delete file and return error status
        } else {
            @unlink("./".$backupfile);
            return FALSE;
        }

        if ($zip=="zip") $zip="zip".$time;
        if ($backupfile=PMBP_save_to_file($backupfile,$zip,$out,"a")) {
            return basename($backupfile);
        } else {
            @unlink("./".$backupfile);
            return FALSE;
        }
    } else {
        return "DB_ERROR";
    }
}


// orders the tables in $tables according to the constraints in $fks
// $fks musst be filled like this: $fks[tablename][0]=needed_table1; $fks[tablename][1]=needed_table2; ...
function PMBP_order_sql_tables($tables,$fks) {
    // do not order if no contraints exist
    if (!count($fks)) return $tables;

    // order
    $new_tables=array();
    $existing=array();
    $modified=TRUE;
    while(count($tables) && $modified==TRUE) {
        $modified=FALSE;
        foreach($tables as $key=>$row) {
            // delete from $tables and add to $new_tables
            if (isset($fks[$row['Name']])) {
                foreach($fks[$row['Name']] as $needed) {
                    // go to next table if not all needed tables exist in $existing
                    if(!in_array($needed,$existing)) continue 2;
                }
            }
            
            // delete from $tables and add to $new_tables
            $existing[]=$row['Name'];
            $new_tables[]=$row;
            prev($tables);
            unset($tables[$key]);
            $modified=TRUE;
        }
    }

    if (count($tables)) {
        // probably there are 'circles' in the constraints, because of that no proper backups can be created
        // This will be fixed sometime later through using 'alter table' commands to add the constraints after generating the tables.
        // Until now I just add the lasting tables to $new_tables, return them and print a warning
        foreach($tables as $row) $new_tables[]=$row;
        echo "<div class=\"red_left\">THIS DATABASE SEEMS TO CONTAIN 'RING CONSTRAINTS'. pMBP DOES NOT SUPPORT THEM. PROBABLY THE FOLLOWING BACKUP IS BROKEN!</div>";
    }
    return $new_tables;
}


// saves the string in $fileData to the file $backupfile as gz file or not ($zip)
// returns backup file name if name has changed (zip), else TRUE. If saving failed, return value is FALSE
function PMBP_save_to_file($backupfile,$zip,$fileData,$mode) {
    if ($zip=="gzip") {
        if ($zp=@gzopen("./".$backupfile,$mode."9")) {
            @gzwrite($zp,$fileData);
            @gzclose($zp);
            return $backupfile;
        } else {
            return FALSE;
        }
        
    // $zip contains the timestamp
    } elseif (substr($zip,0,3)=="zip") {
        $file_path=dirname($backupfile);
        $backupfile=basename($backupfile);
        
        // based on zip.lib.php 2.2 from phpMyBackupAdmin
        // offical zip format: http://www.pkware.com/appnote.txt
        
        // End of central directory record
        $eof_ctrl_dir="\x50\x4b\x05\x06\x00\x00\x00\x00";

        // "local file header" segment
        $unc_len=strlen($fileData);
        $crc=crc32($fileData);
        $zdata=gzcompress($fileData);

        // string needed for decoding (because of crc bug)
        $name_suffix=substr($zdata,-4,4);
        $name_suffix2="_";
        for($i=0;$i<4;$i++) $name_suffix2.=sprintf("%03d",ord($name_suffix[$i]));
        $backupfile=substr($backupfile,0,strlen($backupfile)-8).$name_suffix2.".sql.zip";
        $name=substr($backupfile,0,strlen($backupfile)-4);

        // fix crc bug
        $zdata=substr(substr($zdata,0,strlen($zdata)-4),2);
        $c_len=strlen($zdata);

        // dos time
        $timearray=getdate(substr($zip,3));
        $dostime=(($timearray['year']-1980)<<25)|($timearray['mon']<<21)|($timearray['mday']<<16)|
            ($timearray['hours']<<11)|($timearray['minutes']<<5)|($timearray['seconds']>>1);
        $dtime=dechex($dostime);
        $hexdtime="\x".$dtime[6].$dtime[7]."\x".$dtime[4].$dtime[5]."\x".$dtime[2].$dtime[3]."\x".$dtime[0].$dtime[1];
        eval('$hexdtime="'.$hexdtime.'";');

        // ver needed to extract, gen purpose bit flag, compression method, last mod time and date
        $sub1="\x14\x00"."\x00\x00"."\x08\x00".$hexdtime;

        // crc32, compressed filesize, uncompressed filesize
        $sub2=pack('V',$crc).pack('V',$c_len).pack('V',$unc_len);
        
        $fr="\x50\x4b\x03\x04".$sub1.$sub2;
        
        // length of filename, extra field length
        $fr.=pack('v',strlen($name)).pack('v',0);
        $fr.=$name;

        // "file data" segment and "data descriptor" segment (optional but necessary if archive is not served as file)
        $fr.=$zdata.$sub2;

        // now add to central directory record
        $cdrec="\x50\x4b\x01\x02";
        $cdrec.="\x00\x00";                // version made by
        $cdrec.=$sub1.$sub2;
        
         // length of filename, extra field length, file comment length, disk number start, internal file attributes, external file attributes - 'archive' bit set, offset
        $cdrec.=pack('v',strlen($name)).pack('v',0).pack('v',0).pack('v',0).pack('v',0).pack('V',32).pack('V',0);
        $cdrec.=$name;

        // combine data
        $fileData=$fr.$cdrec.$eof_ctrl_dir;
        
        // total # of entries "on this disk", total # of entries overall, size of central dir, offset to start of central dir, .zip file comment length
        $fileData.=pack('v',1).pack('v',1).pack('V',strlen($cdrec)).pack('V',strlen($fr))."\x00\x00";
                    
        if ($zp=@fopen("./".$file_path."/".$backupfile,"w")) {
            @fwrite($zp,$fileData);
            @fclose($zp);
            return $backupfile;
        } else {
            return FALSE;
        }
        
    // uncompressed
    } else {
        if ($zp=@fopen("./".$backupfile,$mode)) {
            @fwrite($zp,$fileData);
            @fclose($zp);
            return $backupfile;
        } else {
            return FALSE;
        }
    }
}


// updates the content in global_conf.php
function PMBP_save_global_conf() {
    global $CONF;
    global $PMBP_SYS_VAR;

    // to ensure that all configuration settings are saved
    @ignore_user_abort(TRUE);
    
    // create content for global.conf
    $file="<?php\n\n// This file is automatically generated and modified by phpMyBackupPro ".PMBP_VERSION."\n\n";
    foreach($CONF as $item=>$conf) {
        // don't save multi server settings to gloabl_conf.php
        if ($item=="sql_host_s" || $item=="sql_user_s" || $item=="sql_passwd_s" || $item=="sql_db_s") continue;

        // update $_SESSION['sql_host_org'] etc. if new sql data were entered on the config page
        if (basename($_SERVER['SCRIPT_NAME'])=="config.php") {
            $_SESSION['sql_host_org']=$CONF['sql_host'];
            $_SESSION['sql_user_org']=$CONF['sql_user'];
            $_SESSION['sql_passwd_org']=$CONF['sql_passwd'];
            $_SESSION['sql_db_org']=$CONF['sql_db'];
        }    
        
        // save current $CONF['sql_...'] values only if we use the multi server mode
        if ($item=="sql_host" && count($CONF['sql_host_s'])) {
            $file.="\$CONF['".$item."']=\"".$_SESSION['sql_host_org']."\";\n";
        } elseif ($item=="sql_user" && count($CONF['sql_host_s'])) {
            $file.="\$CONF['".$item."']=\"".$_SESSION['sql_user_org']."\";\n";
        } elseif ($item=="sql_passwd" && count($CONF['sql_host_s'])) {
            $file.="\$CONF['".$item."']=\"".$_SESSION['sql_passwd_org']."\";\n";
        } elseif ($item=="sql_db" && count($CONF['sql_host_s'])) {
            $file.="\$CONF['".$item."']=\"".$_SESSION['sql_db_org']."\";\n";
        } else {
            // save the current values for all other settings
            $file.="\$CONF['".$item."']=\"".$conf."\";\n";
        }
    }

    // unset 'last_scheduled_' values in sys vars which no longer belong to an account
    foreach($PMBP_SYS_VAR as $key=>$value) {
        if (substr($key,0,15)=="last_scheduled_" && substr($key,15)>=count($CONF['sql_host_s'])) unset($PMBP_SYS_VAR[$key]);
    }
    
    // add system variables    
    $file.="\n";
    foreach($PMBP_SYS_VAR as $item=>$sys_var) $file.="\$PMBP_SYS_VAR['".$item."']=\"".$sys_var."\";\n";
    
    $file.="\n?>";
        
    // save to file
    return PMBP_save_to_file(PMBP_GLOBAL_CONF,FALSE,$file,"w");
}


// saves $files backup files on $server ftp server in $path path using $user username and $pass password
function PMBP_ftp_store($files) {
    global $CONF;
    $out=FALSE;
    
    // try to connect to server using username and passwort
    if (!$CONF['ftp_server']) {
        $out.="<div class=\"red\">".C_WRONG_FTP."!</div>";
    } elseif (!$conn_id=@ftp_connect($CONF['ftp_server'],$CONF['ftp_port'])) {
        $out.="<div class=\"red\">".F_FTP_1." '".$CONF['ftp_server']."'!</div>";
    } else {
        if (!$login_result=@ftp_login($conn_id,$CONF['ftp_user'],$CONF['ftp_passwd'])) {
            $out.="<div class=\"red\">".F_FTP_2." '".$CONF['ftp_user']."'.</div>";
        } else {

            // succesfully connected
            if ($CONF['ftp_pasv']) ftp_pasv($conn_id,TRUE); else ftp_pasv($conn_id,FALSE);
            if (!$CONF['ftp_path']) $path="."; else $path=$CONF['ftp_path'];
            
            // upload the files
            foreach($files as $filename) {
                $dest_file=$path."/".$filename;
                $source_file="./".PMBP_EXPORT_DIR.$filename;

                // try three times to upload
                $check=FALSE;
                for($i=0;$i<3;$i++)
                    if (!$check) $check=@ftp_put($conn_id,$dest_file,$source_file,FTP_BINARY);
                if (!$check) $out.="<div class=\"red\">".F_FTP_3.": '".$source_file."' -> '".$dest_file."'.</div>\n";
                    else $out.="<div class=\"green\">".F_FTP_4." '".$dest_file."'.</div>\n";
            }

            // close the FTP connection
            if (@function_exists("ftp_close")) @ftp_close($conn_id);
        }
    }
    return $out;
}


// send email with $attachments backup files to $email email using $sitename for sender and subject
function PMBP_email_store($attachments,$backup_info) {
    global $CONF;
    $out=FALSE;
    $lb="\n";
    $all_emails=explode(",",$CONF['email']);
 
    // new mail script (since v.1.4)
    $mailtext=F_MAIL_2." '".$CONF['sitename']."'.".$lb;
    if ($backup_info['comp']=="gzip") $mailtext.=INF_COMP.": gzip".$lb;
        elseif ($backup_info['comp']=="zip") $mailtext.=INF_COMP.": zip".$lb;
            else $mailtext.=INF_COMP.": ".F_NO.$lb;
    if ($backup_info['drop']) $mailtext.=INF_DROP.": ".F_YES.$lb; else $mailtext.=INF_DROP.": ".F_NO.$lb;
    if ($backup_info['tables']) $mailtext.=INF_TABLES.": ".F_YES.$lb; else $mailtext.=INF_TABLES.": ".F_NO.$lb;
    if ($backup_info['data']) $mailtext.=INF_DATA.": ".F_YES.$lb; else $mailtext.=INF_DATA.": ".F_NO.$lb;
    $mailtext.=INF_COMMENT.":".$lb.$backup_info['comments'];
    srand((double)microtime()*1000000);
    $boundary="=_".md5(uniqid(rand()).microtime());
    $parts[-1]="Content-Type: text/plain; charset=\"".BD_CHARSET_EMAIL."\"".$lb.$lb.$mailtext.$lb;
    for ($i=0;$i<count($attachments);$i++) {
        foreach (file(PMBP_EXPORT_DIR.$attachments[$i]) as $val) $bodies[$i].=$val;
        $bodies[$i]=rtrim(chunk_split(base64_encode($bodies[$i]), 76, $lb)).$lb;
        $parts[$i]="Content-Type: application/zip; name=\"".$attachments[$i].
        "\"".$lb."Content-Transfer-Encoding: base64".$lb."Content-Disposition: attachment; filename=\"".$attachments[$i]."\"".$lb.$lb.$bodies[$i].$lb;
    }

    $encoded['body']="--".$boundary.$lb.implode("--".$boundary.$lb,$parts)."--".$boundary."--".$lb.$lb;
    $headers="From: phpMyBackupPro on ".$CONF['sitename']." <".$all_emails[0].">".$lb."Mime-Version: 1.0".$lb."Content-Type: multipart/mixed;".$lb."\tboundary=\"".$boundary."\"";

    // send to all every addresses
    foreach($all_emails as $email) {
        // verify email
        if (!eregi("^\ *[‰ˆ¸ƒ÷‹a-zA-Z0-9_-]+(\.[‰ˆ¸ƒ÷‹a-zA-Z0-9\._-]+)*@([‰ˆ¸ƒ÷‹a-zA-Z0-9-]+\.)+([a-z]{2,4})$",$email)) {
            $out.="<div class=\"red\">".F_MAIL_1."</div>\n";
            continue;
        }
    }
    
    // create subject
    if (count($CONF['sql_host_s'])) {
        $subject=F_MAIL_4." ".$CONF['sitename']." (".$CONF['sql_host'].", ".$CONF['sql_user'].")";
    } else {
        $subject=F_MAIL_4." ".$CONF['sitename'];
    }

    // send mail
    if (!@mail($CONF['email'],$subject,$encoded['body'],$headers)) $out.="<div class=\"red\">".F_MAIL_5.".</div>\n";
        else $out.="<div class=\"green\">".F_MAIL_6." ".$CONF['email'].".</div>\n";
    
    return $out;
}


// returns present local backup files after deleting backups files 
function PMBP_get_backup_files() {
    global $CONF;
    $delete_files=FALSE;
    $all_files=FALSE;
    $result_files=FALSE;
    $handle=@opendir("./".PMBP_EXPORT_DIR);
    $remove_time=time()-($CONF['del_time']*86400);
    while ($file=@readdir($handle)) {
        if ($file!="." && $file!=".." && preg_match("'\.sql|\.sql\.gz|\.sql\.zip'",$file)) {
            
            // don't delete if del_time is false
            if ($CONF['del_time']) {
                if (PMBP_file_info("time",$file)<$remove_time) $delete_files[]=$file; else $all_files[]=$file;
            } else {
                $all_files[]=$file;
            }
        }
    }
    
    // sort descending
    if (is_array($all_files)) rsort($all_files);

    // delete oldest backup files if there are to many for one db
    if (is_array($all_files)) {
        foreach($all_files as $file) {
            if (!isset($counter[$db=PMBP_file_info("db","./".PMBP_EXPORT_DIR.$file)])) $counter[$db]=1; else $counter[$db]++;
            if ($counter[$db]>$CONF['del_number']) $delete_files[]=$file; else $result_files[]=$file;
        }
    }

    // now delete the files
    if ($delete_files) PMBP_delete_backup_files($delete_files);

    // sort ascending
    if (is_array($result_files)) sort($result_files);
    return $result_files;
}


// delete the file(s) in mixed $files from local export dir and remote ftp server
function PMBP_delete_backup_files($files) {
    global $CONF;
    $out=FALSE;
    if(!is_array($files)) $files=array($files);
    foreach($files as $file) @unlink("./".PMBP_EXPORT_DIR.$file);
    // find and delete all old files from the ftp server
    if ($CONF['ftp_use'] && $CONF['ftp_del']) $out=PMBP_ftp_del();
    return $out;
}


// deletes $files backup files from $server ftp server in $path path using $user username and $pass password
function PMBP_ftp_del() {
    global $CONF;
    $out=FALSE;

    // try to connect to server using username and passwort
    if (!$CONF['ftp_server']) {
        $out.="<div class=\"red\">".C_WRONG_FTP."</div>";
    } elseif (!$conn_id=@ftp_connect($CONF['ftp_server'],$CONF['ftp_port'])) {
        $out.="<div class=\"red\">".F_FTP_1." '".$CONF['ftp_server']."'!</div>";
    } else {
        if (!$login_result=@ftp_login($conn_id,$CONF['ftp_user'],$CONF['ftp_passwd'])) {
            $out.="<div class=\"red\">".F_FTP_2." '".$CONF['ftp_user']."'.</div>";
        } else {

            // succesfully connected
            if ($CONF['ftp_pasv']) ftp_pasv($conn_id,TRUE); else ftp_pasv($conn_id,FALSE);

            // get files in remote directory
            if (!$CONF['ftp_path']) $path="."; else $path=$CONF['ftp_path'];
            $remote_files=ftp_nlist($conn_id,$path);
            
            if (is_array($remote_files)) {
                // separate filename
                for($i=0;$i<count($remote_files);$i++)
                    if (strrchr($remote_files[$i],"/")) $remote_files[$i]=substr(strrchr($remote_files[$i],"/"),1);
            
                // don't delete if del_time is false
                if ($CONF['del_time']) {
                    foreach($remote_files as $remote_file) {
                        if (PMBP_file_info("time",$remote_file)<$remove_time) $delete_files[]=$remote_file; else $all_files[]=$remote_file;
                    }
                } else {
                    $all_files=$remote_files;
                }
                
                // sort descending
                if (is_array($all_files)) rsort($all_files);
                
                // delete oldest backup files if there are to many for one db
                if (is_array($all_files)) {
                    foreach($all_files as $file) {
                        $db=PMBP_file_info("db",$file);
                        if (!isset($counter[$db])) $counter[$db]=1; else $counter[$db]++;
                        if ($counter[$db]>$CONF['del_number']) $delete_files[]=$file; else $result_files[]=$file;
                    }
                }        
                        
                // delete the files in $delete_files
                if (is_array($delete_files)) {
                    foreach($delete_files as $filename) {
                        $dest_file=$path."/".$filename;
    
                        // try three times to delete
                        $check=FALSE;
                        for($i=0;$i<3;$i++) {
                            if (!$check) $check=@ftp_delete($conn_id,$dest_file);
                        }
                        if (!$check) $out.="<div class=\"red\">".sprintf(F_FTP_5."</div>\n",$dest_file);
                            else $out.="<div class=\"green\">".sprintf(F_FTP_6."</div>\n",$dest_file);
                    }
                }
            }

            // close the FTP connection
            if (@function_exists("ftp_close")) @ftp_close($conn_id);
        }
    }
    return $out;
}


// returns list of databases on $host host using $user user and $passwd password
function PMBP_get_db_list() {
    global $CONF;

    // if there is given the name of a single database
    if ($CONF['sql_db']) {
        @mysql_connect($CONF['sql_host'],$CONF['sql_user'],$CONF['sql_passwd']);
        if (@mysql_select_db($CONF['sql_db'])) $dbs=array($CONF['sql_db']);
            else $dbs=array();
        return $dbs;
    }
    
    // else try to get a list of all available databases on the server
    $list=array();
    @mysql_connect($CONF['sql_host'],$CONF['sql_user'],$CONF['sql_passwd']);
    $db_list=@mysql_list_dbs();
    while ($row=@mysql_fetch_array($db_list))
        if (@mysql_select_db($row['Database'])) $list[]=$row['Database'];
    return $list;
}


// in dependency on $mode different modes can be selected (see below)
function PMBP_file_info($mode,$path) {
    $filename=ereg_replace(".*/","",$path);
    $parts=explode(".",$filename);
    switch($mode) {
    
        // returns the name of the database a $path backup file belongs to
        case "db":
            return $parts[0];
        break;

        // returns the creation timestamp $path backup file
        case "time":
            return $parts[1];
        break;
        
        // returns "gz" if $path backup file is gziped
        case "gzip":
            if (isset($parts[3])) if ($parts[3]=="gz") return $parts[3];
        break;
        
        // returns "zip" if $path backup file is ziped
        case "zip":
            if (isset($parts[3])) if ($parts[3]=="zip") return $parts[3];
        break;
        
        // returns type of compression of $path backup file or no
        case "comp":
            if (PMBP_file_info("gzip",$path)) return "gzip"; elseif (PMBP_file_info("zip",$path)) return "zip"; else return F_NO;
        break;

        // returns the size of $path backup file
        case "size":
            return filesize($path);
        break;

        // returns yes if the backup file contains 'drop table if exists' or no if not
        case "drop":
            if (($comp=PMBP_file_info("comp",$path))=="gzip") $lines=PMBP_ungzip("lines",$path);
                elseif ($comp=="zip") $lines=PMBP_unzip("lines",$path);
                    else $lines=file($path);
            foreach($lines as $line){
                $line=trim($line);
                if (strtolower(substr($line,0,20))=="drop table if exists") return F_YES; else $drop=F_NO;
            }
            return $drop;

        break;
        
        // returns yes if the $path backup files contains tables or no if not
        case "tables":
            if (($comp=PMBP_file_info("comp",$path))=="gzip") $lines=PMBP_ungzip("lines",$path);
                elseif ($comp=="zip") $lines=PMBP_unzip("lines",$path);
                    else $lines=file($path);
            foreach($lines as $line){
                $line=trim($line);
                if (strtolower(substr($line,0,12))=="create table") return F_YES; else $table=F_NO;
            }
            return $table;
        break;

        // returns yes if the $path backup files contains data or no if not
        case "data":
            if (($comp=PMBP_file_info("comp",$path))=="gzip") $lines=PMBP_ungzip("lines",$path);
                elseif ($comp=="zip") $lines=PMBP_unzip("lines",$path);
                    else $lines=file($path);
            foreach($lines as $line){
                $line=trim($line);
                if (strtolower(substr($line,0,6))=="insert") return F_YES; else $data=F_NO;
            }
            return $data;
        break;
        
        // returns the comment stored to the backup file
        case "comment":
            if (($comp=PMBP_file_info("comp",$path))=="gzip") $lines=PMBP_ungzip("lines",$path);
                elseif ($comp=="zip") $lines=PMBP_unzip("lines",$path);
                    else $lines=file($path);
            foreach($lines as $line){
                $line=trim($line);
                if (isset($comment) && substr($line,0,1)=="#") $comment.=substr($line,2)."<br>";
                    elseif(isset($comment) && substr($line,0,1)!="#") return $comment;
                if ($line=="# comment:") $comment=FALSE;
            }
            if (isset($comment)) return $comment; else return FALSE;
        break;
    }
}


// returns the content of the gziped $path backup file. use of $mode see below
function PMBP_ungzip($mode,$path) {
    $file_data=gzfile($path);
    // returns one string or an array of lines
    if ($mode!="lines") return implode("",$file_data); else return $file_data;
}


// returns the content of the gziped $path backup file line by line
function PMBP_getln($path) {
    if (!isset($GLOBALS['lnFile'])) $GLOBALS['lnFile']=null;
        
    // gz file
    if(PMBP_file_info("gzip",$path)=="gz") {            
    
        if ($GLOBALS['lnFile']==null) {
            $GLOBALS['lnFile']=gzopen($path, "r");
        }
        
        if (!gzeof($GLOBALS['lnFile'])) {
           return gzgets($GLOBALS['lnFile']);
        } else {
            gzclose($GLOBALS['lnFile']);
            $GLOBALS['lnFile']=null;
            return null;
        }

    // sql file
    } else {
        if ($GLOBALS['lnFile']==null) {
            $GLOBALS['lnFile']=fopen($path, "r");
        }
        
        if (!feof($GLOBALS['lnFile'])) {
           return fgets($GLOBALS['lnFile']);
        } else {
            fclose($GLOBALS['lnFile']);
            $GLOBALS['lnFile']=null;
            return null;
        }    
    }
}


// returns the content of the ziped $path backup file. use of $mode see below
function PMBP_unzip($mode,$path,$org_name="") {
    $all=FALSE;
    $all=@implode("",@file($path));

    // set original name
    if (!$org_name) $org_name=$path;

    // convert path to name of ziped file
    $filename=basename($org_name);
    $filename=substr($filename,0,strlen($filename)-4);
    
    // compare filname in zip and filename from $_GET
    if(substr($all,30,strlen($filename))!=$filename) {

        // exit if names differ
        echo F_WRONG_FILE.".";
        exit;
    } else {

        // get the suffix of the filename in hex
        $crc_bugfix=substr(substr($filename,0,strlen($filename)-4),strlen($filename)-12-4);
        $suffix="";

        // convert hex to ascii
        for($i=0;$i<12;) $suffix.=chr($crc_bugfix[$i++].$crc_bugfix[$i++].$crc_bugfix[$i++]);
        
        // remove central directory information (there is always just one ziped file)
        $comp=substr($all,-(strlen($all)-30-strlen($filename)));
        $comp=substr($comp,0,(strlen($comp)-80-strlen($filename)));

        // fix the crc bugfix (see function PMBP_save_to_file)
        $comp="xú".$comp.$suffix;
        $file_data=gzuncompress($comp);
    }

    // returns one string or an array of lines
    if ($mode!="lines") return $file_data; else return explode("\n",$file_data);
}


// determines the best size type for filesize $size and returns array('value'=xxx,'type'=yyy)
function PMBP_size_type($size) {
    $types=array("B","KB","MB","GB");
    for ($i=0; $size>1000; $i++,$size/=1024);
    $result['value']=round($size,2);
    $result['type']=$types[$i];
    return $result;
}


// get recursive directory list
function PMBP_get_dirs($dir,$renew=FALSE) {
    $dirs=FALSE;
    
    // renew date if the 'renew' link was clicked
    if(isset($_GET['update_dir_list'])) $renew=true;
    
    // return existing data
    if($renew) unset($_SESSION['file_system'][$dir]);    
    if(isset($_SESSION['file_system'][$dir])) return $_SESSION['file_system'][$dir];    

    // create directory list
    $dir_handle=@opendir($dir);
    while ($file=@readdir ($dir_handle)) {
        if ($file!="." && $file!="..") {
            if (@is_dir($dir.$file)) {
                $dirs[]=$file."/";
                $tmp=PMBP_get_dirs($dir.$file."/",TRUE);
                if (is_array($tmp)) foreach($tmp as $value) $dirs[]=$file."/".$value;
            }
        }
    }
    $_SESSION['file_system'][$dir]=$dirs;
    return $dirs;
}


// get list of all files in directory
function PMBP_get_files($dir) {
    global $CONF;
    
    $dirs=array();
    $dir=trim($dir);
    if ($dir_handle=@opendir($dir)) {
        while (FALSE!==($file=readdir($dir_handle))) {
        if ($file!="." && $file!="..") {
                if (!is_dir($dir.$file)) {
                $dirs[]=$dir.$file;
                // recursive listing of files
                } elseif($CONF['dir_rec']) {
                    $tmp=PMBP_get_files($dir.$file."/");
                    if (is_array($tmp)) foreach($tmp as $value) $dirs[]=$value;
                }
            }
        }
        @closedir($dir_handle);
    }
    return $dirs;
}


// transfer files $files to FPT servers dirs and create missing folders
function PMBP_save_FTP($files) {
    global $CONF;
    $out=FALSE;

    // try to connect to server using username and passwort
    if (!$CONF['ftp_server']) {
        $out.="<div class=\"red\">".C_WRONG_FTP."</div>";
    } elseif (!$conn_id=@ftp_connect($CONF['ftp_server'],$CONF['ftp_port'])) {
        $out.="<div class=\"red\">".F_FTP_1." '".$CONF['ftp_server']."'!</div>";
    } else {
        if (!$login_result=@ftp_login($conn_id,$CONF['ftp_user'],$CONF['ftp_passwd'])) {
            $out.="<div class=\"red\">".F_FTP_2." '".$CONF['ftp_user']."'.</div>";
        } else {

            // succesfully connected -> set passive and change to the right path
            if ($CONF['ftp_pasv']) ftp_pasv($conn_id,TRUE); else ftp_pasv($conn_id,FALSE);
            if (!$CONF['ftp_path']) $path="."; else $path=$CONF['ftp_path'];
            @ftp_chdir($conn_id,$path);
            
            // create all missing folders
            foreach($files as $filepath) {
                if (trim($filepath)) {
                    $filepath=trim($filepath);
                    $folders=explode("/",$filepath);
                    $filename=array_pop($folders);
                    $deep=0;
                    $all_folders="";
                    foreach($folders as $folder) {
                        if ($folder != "." && $folder != "..") {
                            if (! @ftp_chdir($conn_id,$folder)) {
                                @ftp_mkdir($conn_id,$folder);
                                @ftp_chdir($conn_id,$folder);
                            }
                            $all_folders.=$folder."/";
                            $deep++;
                        }
                    }
                    
                    // change back to $path
                    $rel_path="";
                    for ($i=0;$i<$deep;$i++) $rel_path.="../";
                    @ftp_chdir($conn_id,$rel_path);
                
                    // define the source and destination pathes
                    $dest_file=$all_folders.$filename;
                    $source_file="./".$filepath;

                    // try three times to upload
                    $check=FALSE;
                    for($i=0;$i<3;$i++) if (!$check) $check=@ftp_put($conn_id,$dest_file,$source_file,FTP_BINARY);
                    if (!$check) $out.="<div class=\"red\">".F_FTP_3.": '".$source_file."' -> '".$dest_file."'.</div>\n";
                        else $out.="<div class=\"green\">".F_FTP_4." '".$dest_file."'.</div>\n";
                }
            }

            // close the FTP connection
            if (@function_exists("ftp_close")) @ftp_close($conn_id);
        }
    }
    return $out;
}


// login module
function PMBP_auth () {
    header("WWW-Authenticate: Basic realm=\"phpMyBackupPro\"");
    header("HTTP/1.0 401 Unauthorized");
    echo LI_MSG."\n";
}
?>
