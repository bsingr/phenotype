<table width="640" height="480" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <form action="login.php" method="post">
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="windowFooterGrey2">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="bottom">
                  <td height="120" colspan="2" class="alert"><?php echo localeH("msg_login_error");?></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                  <td colspan="2"><img src="img/white_border.gif" width="3" height="3"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Username");?>:</td>
                  <td><input type="text" name="user" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td class="padding20"><?php echo localeH("Password");?></td>
                  <td><input type="password" name="pass" style="width: 200px" class="input"></td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td><input name="Submit" type="submit" class="buttonGrey2" value="<?php echo localeH("login");?>" style="width:102px"></td>
                </tr>
            </table></td>
            <td width="10" valign="top" class="windowRightShadow"><img src="img/win_sh_ri_to.gif" width="10" height="10"></td>
          </tr>
          <tr>
            <td class="windowBottomShadow"><img src="img/win_sh_bo_le.gif" width="10" height="10"></td>
            <td valign="top" class="windowRightShadow"><span class="windowBottomShadow"><img src="img/win_sh_bo_ri.gif" width="10" height="10"></span></td>
          </tr>
        </table>
