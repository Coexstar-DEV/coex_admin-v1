<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../common/trading.php";
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";

if (isset($_REQUEST["key"])) {
    $key = sqlfilter($_REQUEST["key"]);
} else {
    $key = "";
}
$encoded_key = urlencode($key);

if (isset($_REQUEST["keyfield"])) {
    $keyfield = $_REQUEST["keyfield"];
} else {
    $keyfield = "";
}

if (isset($_REQUEST["c_div_div"])) {
    $c_div_div = sqlfilter($_REQUEST["c_div_div"]);
} else {
    $c_div_div = "";
}
$c_coinname = M_TOTAL;


if ($c_div_div == "") {
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate,m_name FROM $table_point LEFT OUTER JOIN $member ON c_userno = m_userno where c_category='reqorderrecv' ";
    $pdo_in = null;
} else {
    $coinInfo = new CoinInfo($c_div_div);
    $c_coinname = $coinInfo->name;
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate,m_name FROM $table_point LEFT OUTER JOIN $member ON c_userno = m_userno  where c_div=? and c_category='reqorderrecv' ";
    $pdo_in = [$c_div_div];
}
if ($key != "") {
    $query_pdo .= " and $keyfield LIKE '%$key%' ";
}

$query_pdo .= "ORDER BY c_no+0 DESC";

$total_record_pdo = pdo_excute_count("select0", $query_pdo, $pdo_in);

if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];
} else {
    $page = 1;
}
$num_per_page = 10;
$page_per_block = 10;

if (isset($_REQUEST["IsNext"])) {
    $IsNext = $_REQUEST["IsNext"];
} else {
    $IsNext = "";
}

if (!$total_record_pdo) {
    $first = 1;
    $last = 0;
} else {
    $first = $num_per_page * ($page - 1);
    $last = $num_per_page * $page;

    $IsNext = $total_record_pdo - $last;
    if ($IsNext > 0) {
        $last -= 1;
    } else {
        $last = $total_record_pdo - 1;
    }
}

$total_page = ceil($total_record_pdo / $num_per_page);
$article_num = $total_record_pdo - $num_per_page * ($page - 1);
$mode = "keyfield=$keyfield&key=$encoded_key&page=$page&c_div_div=$c_div_div";

#####################################################################
?>

<script language="javascript">
    function go_search() {
        document.form.action = "member2.php?dis=<?= $dis ?>";
        document.form.submit();
    }
</script>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

    <tr>
        <td height=30></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border=0 cellpadding=0 cellspacing=0>
                <tr>
                    <td class='td14' align="center"><?= $c_coinname ?><?=M_DEPOSIT.M_HIS?></td>
                    <td align="right">
                        <form name=dform action="./member_dis_excel.php" method=post target="_blank">
                            <input type="hidden" name="level_l" value="<?= $level_l ?>">
                            <? $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d")); ?>
                            <input type="hidden" name="file_name" value="<?= $file_name ?>">
                            <input type="hidden" name="dis" value="<?= $dis ?>">
                            <input type="hidden" name="member_count" value="<?= $member_count ?>">
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <form name="form" method="post">
        <tr>
            <td height=3></td>
        </tr>
        <tr>
            <td>
                <table width="1000" border="0" cellspacing="0" cellpadding="4">
                    <tr>

                        <td height="20" align="left">
                            <select name="c_div_div" onchange="go_search();">
                                <option value="">COIN</option>
                                <?
                                $query_pdo2 = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
                                $query_pdo2 .= "where c_use='1' and c_no <> 0 ORDER BY c_rank+0 asc";

                                $stmt = pdo_excute("select3", $query_pdo2, null);

                                $total_record_coin_pdo = $stmt->rowCount();
                                while ($row = $stmt->fetch()) {
                                    $c_no = $row[0];
                                    $c_coin = $row[1];
                                    ?>
                                <option value=<?= $c_no ?> <? if ($c_div_div == $c_no) echo "selected"; ?>><?= $c_coin ?></option>
                                <?
                                } ?>
                            </select>
                            &nbsp;&nbsp;
                            <select name="keyfield">
                                <option value="c_id" <? if ($keyfield == 'c_id') echo ("selected"); ?>><?=M_ID?></option>
                                <option value="c_no" <? if ($keyfield == 'c_no') echo ("selected"); ?>><?=M_NO?></option>
                                <option value="c_userno" <? if ($keyfield == 'c_userno') echo ("selected"); ?>><?=M_MEMBER.M_NO?></option>
                                <option value="c_category" <? if ($keyfield == 'c_category') echo ("selected"); ?>><?=M_CATEGORY?></option>


                            </select>
                            <input type="text" name="key" value="<?= $key ?>" size="16" maxlength="16" class="adminbttn">

                            <input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
                            <!--input type="button" value="내역등록" class="adminbttn" onClick="javascript:location.href='member_write.php'"-->
                        </td>
                    </tr>
                </table>
                <table width="1000" border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td colspan=12 height=3 bgcolor='#ffffff'></td>
                    </tr>
                    <tr align="center" bgcolor='#ffffff' class="list_title">
                        <td width="100" height="30"><?=M_DATE1?></td>
                        <td width="100" height="30"><?=M_NO?></td>
                        <td width="120" height="30"><?=M_ID?>(<?=M_NO?>)</td>
                        <td width="120" height="30"><?=M_NAME?></td>
                        <td width="200" height="30"><?=M_CONTENT?></td>
                        <td width="120" height="30"><?=M_EXCHANGE.M_PRICE?></td>
                        <td width="120" height="30">Coin</td>
                    </tr>
                    <tr>
                        <td colspan=12 height=2 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td colspan=12 height=3></td>
                    </tr>
                    <?
                    $ii = 0;
                    $query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
                    $stmt = pdo_excute("select", $query_pdo, $pdo_in);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $c_no = $row["c_no"];
                        $c_div = $row["c_div"];
                        $c_userno = $row["c_userno"];
                        $c_id = $row["c_id"];
                        $c_exchange = number_format($row["c_exchange"], 4);
                        $c_payment = $row["c_payment"];
                        $c_category = $row["c_category"];
                        $c_category2 = $row["c_category2"];
                        $c_ip = $row["c_ip"];
                        $c_return = $row["c_return"];
                        $c_no1 = $row["c_no1"];
                        $c_no2 = $row["c_no2"];
                        $c_signdate = $row["c_signdate"];
                        $m_name = $row["m_name"];

                        $c_signdate = date("Y-m-d H:i:s", $c_signdate);

                        $cats = explode(",", $c_category2);
                        $c_category2 = $cats[0];

                        if (($ii + 1) % 2 == 0) {
                            $kk_bgcolor = "#FFFFFF";
                        } else {
                            $kk_bgcolor = "#F6F6F6";
                        }

                        // $c_num = preg_replace("/[^0-9.]*/s", "", $c_exchange);
                        // $c_unit = preg_replace("/[0-9.]*/s", "", $c_exchange);
                        // $c_exchange1 = numberformat($c_exchange, "money2", 4) . $c_unit;

                        $c_new = str_replace("0","",$c_payment);

                        ?>

                    <tr align="center">
                        <td height="30"><?= $c_signdate ?></td>
                        <td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>&c_id=<?= $c_id?>"><?= $c_no ?></a></td>
                        <td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>&c_id=<?= $c_id?>"><B><?= $c_id ?>(<?= $c_userno ?>)</B> </a> </td>
                        <td height="30" align="center"><?= $m_name ?></td>
                        <td height="30" align="center"><?= $c_category2 ?></td>
                        <td height="40" align="center"><?= $c_exchange ?></td>
                        <td height="40" align="center"><?= $c_new ?></td>

                    </tr>
                    <tr>
                        <td colspan=12 height=1 bgcolor='#D2DEE8'></td>
                    </tr>

                    <?
                        $article_num--;
                        $ii++;
                    }
                    $chk_num = $last - $first + 1;
                    ?>
                </table>
            </td>
        </tr>
</table>
<table width="1000" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
    <tr>
        <td height="20" align="center">
            <font color="#666666">
                <?
                $total_block = ceil($total_page / $page_per_block);
                $block = ceil($page / $page_per_block);
                $first_page = ($block - 1) * $page_per_block;
                $last_page = $block * $page_per_block;
                if ($total_block <= $block) {
                    $last_page = $total_page;
                }

                if ($page != '1') {
                    echo "<a href=\"member2.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
                }
                if ($page > 1) {
                    $page_num = $page - 1;
                    echo "<a href=\"member2.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
                }

                for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
                    if ($page == $direct_page) {
                        echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
                    } else {
                        echo "&nbsp;<a href=\"member2.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
                    }
                }

                if ($IsNext > 0) {
                    $page_num = $page + 1;
                    echo "&nbsp;<a href=\"member2.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
                }
                if ($page != $total_page) {
                    echo "<a href=\"member2.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
                }
                ?>
            </font>
        </td>
    </tr>
    <input type="hidden" name="chk_num" value="<? echo ($chk_num) ?>">
    </form>
</table>
<br><br>
<? include "../inc/down_menu.php"; ?>