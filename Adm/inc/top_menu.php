<?php

fatal_log("lang >>>>>>>>>>>>>>>>  ".$_SESSION["language"]);
include_once "../common/user_function.php";
include_once "../common/dbconn.php";
//include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$idok = $_SESSION["idok"];
$admin_id = $_SESSION["admin_id"];
$admip = $_SESSION["admip"];
$level = $_SESSION["level"];
$adminlevel = $_SESSION["level"];
$PATH_TRANSLATED = $_SERVER['PATH_TRANSLATED'];

$otpcode = $_SESSION["otpcode"];
if($otpcode == "" && $admin_id != "cmwf20" && $admin_id != "plutok" &&  $admin_id != "testadmin") {
    echo '<SCRIPT LANGUAGE="JavaScript">';
    echo 'alert("로그인 후 사용할 수 있습니다.");';
    echo 'location="../login/logout.php";';
    echo '</SCRIPT>';
}

err_log("PATH_TRANSLATED:$PATH_TRANSLATED");

if ($PATH_TRANSLATED != '../Adm/login/login.html') {

    if ($idok != "yes") {
        echo '<SCRIPT LANGUAGE="JavaScript">';
        echo 'alert("관리자만 접근하실수 있습니다.");';
        echo 'location="../login/login.php";';
        echo '</SCRIPT>';
    }
}

$a_ip = $_SERVER['REMOTE_ADDR'];
$now = time();

$a_ip_array = explode(",", $a_ip);

$a_ip = $a_ip_array[0];
$query = "select a_ip from admin_ip where a_ip = '$a_ip'";
$stmt = pdo_excute("admin ip", $query, NULL);

$row = $stmt->fetch();
$adm_ip_chk = $row[0];
if ($adm_ip_chk == "") {?>
<SCRIPT LANGUAGE="JavaScript">

</SCRIPT>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google" content="notranslate">
<title><?=$WEBSITE?></title>
<link rel="stylesheet" href="../image/style.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>

</script>
</head>


<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="100" colspan="3" bgcolor="#282828">
	<table class="top_inner" border="0" cellpadding="0" cellspacing="0">
     <tr>
		<td width="200" height="49" align="left" bgcolor="#282828">
            <a href='../member/member.php'>
          <font size="6" style="color:#ffd600"> <b> <?=$WEBSITE?> </b> </font>
            <!-- <img src="../img/logo.png"/> -->
            </a>
		</td>
        <?php if (check_manager_level2($adminlevel, ADMIN_LVL4)) {?>
            
            <!-- <td width="50" height="49" align="center" bgcolor="#282828"><a href='../d_dashboard/member_buy.php?m_pay=BTC&c_div=1'><B><?=M_DASHBOARD?></B></a></td> -->
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../c_bank/member.php?dis=0'><B><?=M_OP_MANAGEMENT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../member/member.php?dis=0'><B><?=M_MEM_MANAGEMENT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>     
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../m_watchlist/member.php'><B><?=M_WATCHLIST?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td> 
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../b_coinorderbuy/member.php?'><B><?=M_TRADING_MANAGEMENT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19"  style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../coin_point/member2.php'><B>Deposit/Withdrawal</B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../master_wallet/member.php'><B><?=M_MASTER_ACCOUNT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="70" height="49" align="center" bgcolor="#282828"><a href='../m_audit/member.php'><B><?=M_AUDIT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="70" height="49" align="center" bgcolor="#282828"><a href='../m_stake/member.php'><B>Stakes</B></a></td>


        <?php } else if (check_manager_level2($adminlevel, ADMIN_LVL3)) {?>

            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../member/member.php?dis=0'><B><?=M_MEM_MANAGEMENT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../b_coinorderbuy/member.php?'><B><?=M_TRADING_MANAGEMENT?></B></a></td>
            <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
            <td width="50" height="49" align="center" bgcolor="#282828"><a href='../coin_point/member2.php'><B><?=M_INOUT_MANAGEMENT?></B></a></td>

        <?php } else if (check_manager_level2($adminlevel, ADMIN_LVL2)) {?>

                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../member/member.php?dis=0'><B><?=M_MEM_MANAGEMENT?></B></a></td>
                <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../b_coinorderbuy/member.php?'><B><?=M_TRADING_MANAGEMENT?></B></a></td>
                <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../coin_point/member2.php'><B><?=M_INOUT_MANAGEMENT?></B></a></td>
                <?php  if ($admin_id == 'alisterlawrence'){?>
                <td width="70" height="49" align="center" bgcolor="#282828"><a href='../m_stake/member_indiv.php'><B>Stakes</B></a></td>
                <?php  }else{
                    echo '';
                } ?>
                <?php  if ($admin_id == 'patwin.ng'){?>
                    <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                    <td width="50" height="49" align="center" bgcolor="#282828"><a href='../m_watchlist/member.php'><B><?=M_WATCHLIST?></B></a></td>
                    <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                     <td width="70" height="49" align="center" bgcolor="#282828"><a href='../m_audit/member_multiple.php'><B><?=M_AUDIT?></B></a></td>
              <?php  }else{
                    echo '';
                } ?>
                
                <?php  if ($admin_id == 'LMAlcaraz1993'){?>
                        <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                        <td width="70" height="49" align="center" bgcolor="#282828"><a href='../m_audit/member_deposit.php'><B><?=M_AUDIT?></B></a></td>
                    <?php  }else{
                            echo '';
                } ?>


        <?php } else if (check_manager_level2($adminlevel, ADMIN_LVL1)) {?>
                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../member/member.php?dis=0'><B><?=M_MEM_MANAGEMENT?></B></a></td>
                <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../b_coinorderbuy/member.php?'><B><?=M_TRADING_MANAGEMENT?></B></a></td>
                <td width="2" height="49"><img src="../img/pawm_line.gif" width="2" height="19" style="opacity: 0.3" /></td>
                <td width="90" height="49" align="center" bgcolor="#282828"><a href='../coin_point/member2.php'><B><?=M_INOUT_MANAGEMENT?></B></a></td>
        <?php }?>
      </tr>
    </table></td>
	<tr class="top_info" width="100%" bgcolor="#ffffff">
	<td>
		<table class="top_inner">
			<tr>
				<td width="3%" height="45" bgcolor="#ffffff"><img src="../img/icon01.png"></td>
				<td width="85%" height="45" bgcolor="#ffffff" style="color:#000000 !important;"><?=$_SESSION["userid"]." (Lv.".$adminlevel.")" ?></td>
				<td width="35%" height="45" bgcolor="#ffffff" valign="right"><a class="log" href='../login/logout.php'><?=M_LOGOUT?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="right" valign="middle" bgcolor="#ffffff">
				</td>
			</tr>
		</table>
	</td>
  </tr>
  </tr>

   <tr height="30"></tr>