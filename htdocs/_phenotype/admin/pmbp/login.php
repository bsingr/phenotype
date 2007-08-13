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

@session_start();

// get password and username
require_once("definitions.php");

// login with http authentification
if ($CONF['login']) {
    if (!isset($_SERVER['PHP_AUTH_USER'])
    || (isset($_GET["login"]) && !($_SERVER['PHP_AUTH_USER']==$CONF['sql_user'] && $_SERVER['PHP_AUTH_PW']==$CONF['sql_passwd']))
    || (isset($_GET["logout"]) && $_SERVER['PHP_AUTH_PW']==$CONF['sql_passwd'])) {
        header("WWW-Authenticate: Basic realm=\"phpMyBackupPro\"");
        header("HTTP/1.0 401 Unauthorized");
        echo LI_NOT_LOGED_OUT;
        echo ": <a href=\"index.php?logout=TRUE\">".F_LOGOUT."</a><br>";
        echo LI_MSG.": <a href=\"index.php?login=TRUE\">".LI_LOGIN."</a>";
        exit;
    } else if ($_SERVER['PHP_AUTH_PW']!=$CONF['sql_passwd']) {
        echo LI_LOGED_OUT."<br>\n".LI_MSG;
        echo ": <a href=\"index.php?login=TRUE\">".LI_LOGIN."</a>";
        unset($_SESSION['PMBP_VERSION']);
        unset($_SESSION['LOGGED_IN']);
        unset($_SESSION['sql_host_org']);
        unset($_SESSION['sql_user_org']);
        unset($_SESSION['sql_passwd_org']);
        @session_destroy();
        exit;
    }
    
    // login with html authentification
} else {

    // disable login functions if $CONF['no_login'] is true
    if ($CONF['no_login']!="1") {
        if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN']=FALSE;    

        // not logged in
        if (!$_SESSION['LOGGED_IN']) {
            if (!isset($_POST['password'])) $_POST['password']=FALSE;        

            // login failed
            if ($CONF['sql_user'] AND $CONF['sql_passwd'] AND ($_POST['password']!=$CONF['sql_passwd'] OR $_POST['username']!=$CONF['sql_user'])){

                // print login form
                ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html<?php echo ARABIC_HTML;?>>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo BD_CHARSET_HTML;?>">
<link rel="stylesheet" href="<?php echo PMBP_STYLESHEET_DIR.$CONF['stylesheet'];?>.css" type="text/css">
<title>phpMyBackupPro</title>
</head>
<body onLoad="document.login.username.focus()">
<form name="login" action="" method="POST">
<table width="400">
  <tr>
    <th colspan="2" class="active">
<?php
echo PMBP_image_tag("logo.png","phpMyBackupPro","http://www.phpMyBackupPro.net","http://www.phpMyBackupPro.net");
?>
    </th>
  </tr>
  <tr>
    <td colspan="2"><?php echo LI_MSG; ?>:</td>
  </tr>
  <tr>
    <td><?php echo LI_USER; ?>:</td>
    <td><input type="text" name="username"></td>
  </tr>
  <tr>
    <td><?php echo LI_PASSWD; ?>:</td>
    <td><input type="password" name="password"></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="login" value="<?php echo LI_LOGIN; ?>" class="button"></td>
  </tr>
</table>
</form>
</body>
</html>
            <?php
            $CONF="";
            
            // break loading page if not logged in
            exit;
        
        // save username in session if logged in
        } else {
            $_SESSION['LOGGED_IN']=TRUE;
            $_GET['login']=TRUE;
        }
    }

    if (isset($_GET['logout'])) {
        @session_start();
        unset($_SESSION['PMBP_VERSION']);
        unset($_SESSION['LOGGED_IN']);
        unset($_SESSION['sql_host_org']);
        unset($_SESSION['sql_user_org']);
        unset($_SESSION['sql_passwd_org']);
        header("Location: index.php");
        exit;
    }

    // when login functions is disabled
    } else {
        $_SESSION['LOGGED_IN']="Login deactivated!";
    }
} // end type of auth
?>
