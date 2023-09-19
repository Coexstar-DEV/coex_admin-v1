<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

$c_no = sqlfilter($_GET["c_no"]);
$c_bank = sqlfilter($_POST["c_bank"]);
$c_banknum = sqlfilter($_POST["c_banknum"]);
$c_account = sqlfilter($_POST["c_account"]);
$c_use = sqlfilter($_POST["c_use"]);
$c_signdate = sqlfilter($_POST["c_signdate"]);

$keyfield = sqlfilter($_REQUEST["keyfield"]);
$key = sqlfilter($_REQUEST["key"]);
$page = sqlfilter($_REQUEST["page"]);
$c_no = sqlfilter($_REQUEST["c_no"]);

$query_pdo = "SELECT c_bank,c_banknum,c_account,c_use FROM $table_bank WHERE c_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($c_no));

$row = $stmt->fetch();

if (!$row) {
    error("QUERY_ERROR");
    exit;
}

$c_bank = $row[0];
$c_banknum = $row[1];
$c_account = $row[2];
$c_use = $row[3];

?>

<script language="javascript">
    function go_modify() {
        var bank = document.form.c_bank.value;
        var banknum = document.form.c_banknum.value;
        var account = document.form.c_account.value;

        if (bank == "" || banknum == "" || account == "") {
            alert("계좌정보를 입력하세요.");
            return;
        }

        document.form.action = "member_modify_ok.php";
        document.form.submit();
    }

    function go_list() {
        document.form.action = "member.php";
        document.form.submit();
    }
</script>

<table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height=30></td>
    </tr>
    <tr>
        <td>
            <table border=0 cellpadding=0 cellspacing=0>
                <tr>
                    <td width=60 align=center><img src="../image/icon2.gif" width=45 height=35 border=0></td>
                    <td class='td14'><b><?=M_ACCOUNT.M_MANAGEMENT?></b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height=3></td>
    </tr>
    <tr>
        <td>
            <table width="600" border='0' cellspacing='0' cellpadding='0'>
                <form name="form" method="post">
                    <tr>
                        <td colspan=4 height=2 bgcolor='#88B7DA'></td>
                    </tr>
                    <tr>
                        <td colspan=4 height=5></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_BANK?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font face="돋움" size="2">&nbsp;
                                <input type="text" maxlength=30 name="c_bank" value="<?= $c_bank ?>" size=30 class="adminbttn">
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font face="돋움" size="2"><?=M_ACCOUNT?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=30 name="c_banknum" value="<?= $c_banknum ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_ACCOUNT_OWNER?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=200 name="c_account" value="<?= $c_account ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_USE?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="radio" name="c_use" value="0" <? if ($c_use == "0") { ?>checked <?
                                                                                                                } ?> class="adminbttn"><?=M_USE_NO?>
                                <input type="radio" name="c_use" value="1" <? if ($c_use == "1") { ?>checked <?
                                                                                                                } ?> class="adminbttn"><?=M_USE_YES?></font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" name="c_no" value="<? echo ($c_no) ?>">
    <input type="hidden" name="real_pass" value="<? echo ($real_pass) ?>">
    <input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
    <input type="hidden" name="key" value="<? echo ($key) ?>">
    <input type="hidden" name="page" value="<? echo ($page) ?>">
    </form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
    <tr>
        <td height="30"></td>
    </tr>
    <tr>
        <td height="20" align="center">
            <input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">
            <input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">
            <input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="javascript:location.href='member_del.php?c_no=<?= $c_no ?>'">
        </td>
    </tr>
</table>
<br><br>

<BR><BR>

<? include "../inc/down_menu.php"; ?>