<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="background-color:#fff;" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <link href="../image/style.css" rel="stylesheet" type="text/css" />

  <?php
  $_SESSION = array();
  include "../common/dbconn.php";
  include "../common/user_function.php";
  //$a_ip = $_SERVER["REMOTE_ADDR"];
  $a_ip_array = explode(",", $a_ip);
  $a_ip = $a_ip_array[0];

  $a_ip = get_real_ip();
  $now = time();

  $query = "select a_ip from admin_ip where a_ip = '$a_ip'";
  $stmt = pdo_excute("admin ip", $query, NULL);
  $row = $stmt->fetch();
  $adm_ip_chk = $row[0];

  if ($adm_ip_chk == "") {
    echo "<SCRIPT LANGUAGE='JavaScript'>";
    echo "location='/Adminstar';";
    echo "</SCRIPT>";
    exit;
  }
  ?>

  <script language="JavaScript">
    function loginP(j) {
      if (j == "i") {
        if (!document.form.m_adminid.value) {
          alert('<?=M_INPUT_ID?>');
          document.form.m_adminid.focus();
          return;
        }
        if (!document.form.m_adminpass.value) {
          alert('패스워드를 입력하세요!');
          document.form.m_adminpass.focus();
          return;
        }
        document.form.action = "login_do.php";
      } else {
        document.form.action = "logout.php";
      }
      document.form.submit();
    }

    function EnterCheck(i) {
      if (event.keyCode == 13 && i == 1) {
        document.form.m_adminpass.focus();
      }
      if (event.keyCode == 13 && i == 2) {
        document.form.action = "login_do.php?j=i";
        document.form.submit();
      }
    }
  </script>
</head>

<body>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="151" colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td width="142" height="89">&nbsp;</td>
      <!--     <td><img src="../img/paxmlogo_03.gif" width="157" height="89" border="0" /></td> -->
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
        <table width="504" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" height="151" colspan="3">
              <font size="7"><?= $WEBSITE ?></font>
              <!--img src="../img/b_logo.png"-->
            </td>
          </tr>

          <form name="form" method="post">
            <tr>
              <td></td>
              <td colspan="2" align="center" height="40"> 
                <select name="language">
                  <option value="kr" selected>Korean</option>
                  <option value="en" >English</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2" align="center"><label for="ID"></label>
                <input type='text' name="m_adminid" size="45" maxlength="20" tabindex='1' OnKeyDown="EnterCheck(1);" placeholder="ID" class="formbox"></td>
            </tr>
            <tr>
              <td></td>
              <td height="7" colspan="2"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2" align="center"><input type="password" name="m_adminpass" tabindex='2' size="45" maxlength="20" OnKeyDown="EnterCheck(2);" placeholder="PW" class="formbox"></td>
            </tr>
            <tr>
              <td></td>
              <td height="40" colspan="2"> </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td></td>
              <td align="center"><a class="login_bt" style="color:#ffd600;" href="javascript:loginP('i')" onmouseover="status=''; return true;">LOGIN</a></td>
            </tr>
          </form>
          <tr>
            <td>&nbsp;</td>
            <td height="47" colspan="2" align="right" class="text2"></td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
</body>

</html>