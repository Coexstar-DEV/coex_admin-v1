<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";
include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

if (isset($_REQUEST["keyfield"])) {
    $keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
    $keyfield = "";
}
if (isset($_REQUEST["key"])) {
    $key = sqlfilter($_REQUEST["key"]);
} else {
    $key = "";
}
if (isset($_REQUEST["page"])) {
    $page = sqlfilter($_REQUEST["page"]);
} else {
    $page = "";
}
if (isset($_REQUEST["c_no"])) {
    $c_no = sqlfilter($_REQUEST["c_no"]);
} else {
    $c_no = "";
}
if (isset($_REQUEST["c_coin2"])) {
    $c_coin_title = sqlfilter($_REQUEST["c_coin2"]);
} else {
    $c_coin_title = "";
}
if (isset($_REQUEST["c_level"])) {
    $c_level = sqlfilter($_REQUEST["c_level"]);
} else {
    $c_level = "";
}
$query_pdo = "SELECT c_no,c_coin,c_level,c_deposit,c_withdraw,c_limit,c_signdate FROM $table_level WHERE c_no=? ";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($c_no));
$row = $stmt->fetch();

if (!$row) {
    error("QUERY_ERROR");
    exit;
}

$c_no = $row[0];
$c_coin = $row[1];
$c_level = $row[2];
$c_deposit = $row[3];
$c_withdraw = $row[4];
$c_limit = $row[5];

?>

<script language="javascript">
    function go_modify() {
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
                    <td class='td14'><b><?=M_OP_LEVEL_LIMIT?></b></td>
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
                        <td width=105 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_COIN.M_DIVISION?></font>
                            </div>
                        </td>
                        <td height="30" colspan="3" align="left">
                            <select name="c_coin">
                                <?
                                $query_pdo2 = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
                                $query_pdo2 = $query_pdo2 . " ORDER BY c_rank+0 asc";
                                $stmt = $pdo->prepare($query_pdo2);
                                $stmt->execute();
                                $result_pdo = $stmt->fetch();

                                if (!$result_pdo) {
                                    error("QUERY_ERROR");
                                    exit;
                                }
                                $total_record_coin_pdo = $stmt->rowCount();
                                ?>
                                <? for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
                                    $stmt = $pdo->prepare($query_pdo2);
                                    $stmt->execute();
                                    $row = $stmt->fetchAll();
                                    $c_no22 = $row[$ki]["0"];
                                    $c_coin2 = $row[$ki]["1"];
                                    ?>
                                <option value=<?= $c_no22 ?> <? if ($c_coin == $c_no22) { ?> selected <? } ?>><?= $c_coin2 ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font face="돋움" size="2"><?=M_LEVEL?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <select name="c_level">
                                    <? for ($i = 1; $i <= 5; $i++) { ?>
                                    <option value="<?= $i ?>" <? if ($c_level == $i) { ?> selected<? } ?>><?= $i ?></option>
                                    <? } ?>
                                </select>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_MAX.M_DEPOSIT.M_LIMIT.M_SETTING?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=30 name="c_deposit" value="<?= $c_deposit ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_MAX.M_WITHDRAW.M_LIMIT.M_SETTING?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=30 name="c_withdraw" value="<?= $c_withdraw ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="center">
                                <font size="2" face="돋움"><?=M_MAX.M_DAYLY.M_LIMIT.M_SETTING?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=30 name="c_limit" value="<?= $c_limit ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" name="c_no" value="<? echo ($c_no) ?>">
    <input type="hidden" name="c_coin2" value="<? echo ($c_coin_title) ?>">
    <input type="hidden" name="real_pass" value="<? echo ($real_pass) ?>">
    <input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
    <input type="hidden" name="key" value="<? echo ($key) ?>">
    <input type="hidden" name="page" value="<? echo ($page) ?>">
    <input type="hidden" name="c_coin" value="<? echo ($c_coin) ?>">
    <input type="hidden" name="c_level" value="<? echo ($c_level) ?>">
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
            <input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="javascript:location.href='member_del.php?c_no=<?= $c_no ?>&c_coin=<?= $c_coin ?>&c_level=<?= $c_level?>'">
        </td>
    </tr>
</table>
<br><br>

<BR><BR>

<? include "../inc/down_menu.php"; ?>