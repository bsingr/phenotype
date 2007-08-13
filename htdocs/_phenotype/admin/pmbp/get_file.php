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

// set the timelimit
@set_time_limit($CONF['timelimit']);

// show the requested file
if (isset($_GET['view'])) {
    if (isset($_GET['download'])) {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".basename($_GET['view']));
        readfile(PMBP_EXPORT_DIR.$_GET['view']);
    } else {
    echo "<pre>";
    if (PMBP_file_info("zip",$_GET['view'])) {
        echo htmlentities(PMBP_unzip("all",$_GET['view']));
    } elseif(!PMBP_file_info("gzip",$_GET['view'])) {
        $lines=file($_GET['view']);
        foreach($lines as $line) echo htmlentities($line);
    } else {
        echo htmlentities(PMBP_ungzip("all",$_GET['view']));
    }
    echo "</pre>";
  }
}
?>
