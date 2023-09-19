<?php
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

check_manager_level($adminlevel, ADMIN_LVL4);

if (isset($_REQUEST["ydate1"])) {
    $ydate1 = sqlfilter($_REQUEST["ydate1"]);
} else {
    $ydate1 = date('Y');
}
if (isset($_REQUEST["mdate1"])) {
    $mdate1 = sqlfilter($_REQUEST["mdate1"]);
} else {
    $mdate1 = date('m');
}
if (isset($_REQUEST["ddate1"])) {
    $ddate1 = sqlfilter($_REQUEST["ddate1"]);
} else {
    $ddate1 = date('d');
}
if (isset($_REQUEST["ydate2"])) {
    $ydate2 = sqlfilter($_REQUEST["ydate2"]);
} else {
    $ydate2 = date('Y');
}
if (isset($_REQUEST["mdate2"])) {
    $mdate2 = sqlfilter($_REQUEST["mdate2"]);
} else {
    $mdate2 = date('m');
}
if (isset($_REQUEST["ddate2"])) {
    $ddate2 = sqlfilter($_REQUEST["ddate2"]);
} else {
    $ddate2 = date('d');
}

$wdate1 = mktime(0, 0, 0, $mdate1, $ddate1, $ydate1);
$wdate2 = mktime(23, 59, 59, $mdate2, $ddate2, $ydate2);

if ($mdate1 != '' || $ddate1 != '' || $ydate1 != '') {
    $where_date1 = " where b_closedate >= '$wdate1'";
} else {
    $where_date1 = "";
}

if ($mdate2 != '' || $ddate2 != '' || $ydate2 != '') {
    if ($where_date1 == '') {
        $where_date2 = " where b_closedate <= '$wdate2'";
    } else {
        $where_date2 = " and b_closedate <= '$wdate2'";
    }
} else {
    $where_date2 = "";
}
if (isset($_REQUEST["keyfield"])) {
    $keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
    $keyfield = "";
}
$keyfield = sqlfilter($_REQUEST["keyfield"]);

?>

<script language="javascript">
    function go_search() {
        document.form.action = "member.php?dis=<?= $dis ?>";
        document.form.submit();
    }
</script>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

    <tr>
        <td height=30></td>
    </tr>
    <tr>
        <td>
            <table width="1000" align="center" border=0 cellpadding=0 cellspacing=0>
                <tr>
                    <td class='td14' align="center"><?=M_FEE.M_SEARCH?></td>
                </tr>
            </table>
        </td>
    </tr>
    <form name="form" method="post">
        <tr>
            <td height=3></td>
        </tr>
        <table width="1000" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td>
                    <select name="ydate1" class="formbox3">
                        <? for ($i = 2013; $i <= Date("Y") + 2; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($ydate1 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">년</span>&nbsp;

                    <select name="mdate1" class="formbox3">
                        <? for ($i = 1; $i < 13; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($mdate1 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">월</span>&nbsp;

                    <select name="ddate1" class="formbox3">
                        <? for ($i = 1; $i < 32; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($ddate1 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">일</span>&nbsp;
                    ~
                    <select name="ydate2" class="formbox3">
                        <? for ($i = 2013; $i <= Date("Y") + 2; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($ydate2 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">년</span>&nbsp;

                    <select name="mdate2" class="formbox3">
                        <? for ($i = 1; $i < 13; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($mdate2 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">월</span>&nbsp;

                    <select name="ddate2" class="formbox3">
                        <? for ($i = 1; $i < 32; $i++) { ?>
                        <option value="<?= $i ?>" <? if ($ddate2 == $i) { ?>selected <?
                                                                                            } ?>><?= $i ?></option>
                        <?
                        } ?>
                    </select><span class="text2">일</span>&nbsp;
                </td>
                <td height="20" align="left">
                    <select name="keyfield">
                        <option value="such_coin" <? if ($keyfield == 'such_coin') echo ("selected"); ?>>매매 수수료</option>
                        <option value="such_draw" <? if ($keyfield == 'such_draw') echo ("selected"); ?>>출금 수수료</option>
                    </select>
                    <input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
                </td>
            </tr>
        </table>
        <hr><br>

        <?
        if ($keyfield == 'such_coin') { ?>
        <table width='500' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td colspan=12 height=3 bgcolor='#ffffff'></td>
            </tr>
            <tr align="center" bgcolor='#ffffff' class="list_title">
                <td height="50" align="center" style=font-size:15px>coin 명 &nbsp;&nbsp;</td>
                <td height="50" align="right" style=font-size:15px> 매매 수수료</td>
            </tr>
            <tr>
                <td colspan=12 height=2 bgcolor='#D2DEE8'></td>
            </tr>
            <tr>
                <td colspan=12 height=3></td>
            </tr>
            <?

                $query_pdo = "SELECT c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
                $query_pdo .= " where c_use='1' ORDER BY c_rank+0 asc";

                $stmt = pdo_excute("select", $query_pdo, NULL);
                while ($row = $stmt->fetch()) {
                    $c_no = $row[0];
                    $c_coin = $row[1];

                    /* 로직이 맞지 않는다. orderbuy, ordersell 에서 할경우, 해당 날짜에 대해 조회가 아니라, 주문에 대한 총 fee 가 구해진다. 
                    $sql = "SELECT ifnull(sum(b_closefees),0) FROM $table_ordersell where b_pay=$c_no and b_state <>'wait' and b_closedate>='$wdate1' and b_closedate<='$wdate2'";

                    $stmt = pdo_excute("select_sell", $sql, NULL);
                    $dat = $stmt->fetch();
                    $sell_sum = $dat[0];

                    $sql = "SELECT ifnull(sum(b_closefees),0) FROM $table_orderbuy where b_div=$c_no and b_state <>'wait' and b_closedate>='$wdate1' and b_closedate<='$wdate2'";
                    $stmt = pdo_excute("select_buy", $sql, NULL);
                    $dat = $stmt->fetch();
                    $buy_sum = $dat[0];


                    $all_sum = numberformat($sell_sum + $buy_sum, "money", 4);
                    $sell_sum = numberformat($sell_sum, "money", 4);
                    $buy_sum = numberformat($buy_sum, "money", 4);
                    */

                    // bankmoney 쪽과 비교 테스트 
                    $sql = "SELECT ifnull(sum(m_fee+0),0) FROM m_bankmoney where m_div = ? and m_category like 'trade%' and m_signdate >= ? and m_signdate <= ?";
                    $stmt2 = pdo_excute("select_fee", $sql, [$c_no, $wdate1, $wdate2]);
                    $dat = $stmt2->fetch();
                    $bank_sum = $dat[0] * -1;
                    $bank_sum = numberformat($bank_sum, "money", 8);
                    #####################################################################
                    ?>

            <tr align="center">
                <td height="30" align="center" style=font-size:15px><?= $c_coin ?> &nbsp;&nbsp;</td>
                <td height="30" align="right" style=font-size:15px> <?= $bank_sum ?>
                </td>
            <tr>
                <td colspan=12 height=1 bgcolor='#D2DEE8'></td>
            </tr>
            </tr>
            <?
                }
            } ?>
            <?
            if ($keyfield == 'such_draw') {
                ?>

            <table width='500' border='0' cellspacing='0' cellpadding='0'>
                <tr>
                    <td colspan=12 height=3 bgcolor='#ffffff'></td>
                </tr>
                <tr align="center" bgcolor='#ffffff' class="list_title">
                    <td height="50" align="center" style=font-size:15px>coin 명 &nbsp;&nbsp;</td>
                    <td height="50" align="right" style=font-size:15px> 출금 수수료</td>
                </tr>
                <tr>
                    <td colspan=12 height=2 bgcolor='#D2DEE8'></td>
                </tr>
                <tr>
                    <td colspan=12 height=3></td>
                </tr>

                <tr>
                    <td colspan=12 height=3 bgcolor='#ffffff'></td>
                </tr>
                <?

                    $query_pdo = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
                    $query_pdo .= " where c_use='1' ORDER BY c_rank+0 asc";

                    $stmt = pdo_excute("select", $query_pdo, NULL);
                    while ($row = $stmt->fetch()) {
                        $c_no = $row[0];
                        $c_coin = $row[1];

                        $sql = "SELECT ifnull(sum(t_fees),0) FROM $table_withdraw where t_division= ? and t_delete='0' and t_duedate>= ? and t_duedate<= ?";
                        $stmt2 = pdo_excute("select_fee", $sql, [$c_no, $wdate1, $wdate2]);
                        $dat = $stmt2->fetch();

                        ?>

                <tr align="center" bgcolor="<?= $kk_bgcolor ?>">
                    <td height="30" align="center" style=font-size:15px><?= $c_coin ?> &nbsp;&nbsp;</td>
                    <td height="30" align="right" style=font-size:15px> <?= $dat[0] ?></td>
                </tr>
                <tr>
                    <td colspan=12 height=1 bgcolor='#D2DEE8'></td>
                </tr>
                <?
                    }
                } ?>
                <tr>
                    <td colspan=12 height=1 bgcolor='#D2DEE8'></td>
                </tr>
            </table>
            <br><br>

            <? include "../inc/down_menu.php"; ?>