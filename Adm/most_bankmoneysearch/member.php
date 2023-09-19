<?
session_start();

include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../common/trading.php";
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_member.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$_REQUEST = sql_escape($_REQUEST);

$m_div_div = isset($_REQUEST["m_div_div"]) ? $_REQUEST["m_div_div"] : "";
$key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
$keyfield = isset($_REQUEST["keyfield"]) ? $_REQUEST["keyfield"] : "";
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;


if (!is_empty($m_div_div)) {
    $coinInfo = new CoinInfo($m_div_div);
    $c_coinname = $coinInfo->name;
}

$encoded_key = urlencode($key);

if ($m_div_div == "") {
    $query_pdo = "SELECT m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate,m_no1,m_category,(m_category2+0),(m_fee+0),m_coin_no FROM $m_bankmoney ";

    if ($keyfield != "" && $key != "") {
        $query_pdo .= " where $keyfield LIKE '%$key%' ";
    }
    $query_pdo .= " ORDER BY m_no desc, m_signdate desc";
} else {

    if ($keyfield != "" && $key != "") {
        $search = " and $keyfield LIKE '%$key%' ";
    }

    $query_pdo = "(SELECT m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate,m_no1,m_category,(m_category2+0),(m_fee+0),m_coin_no FROM $m_bankmoney where m_div=? $search)";
    $query_pdo .= " UNION ALL";
    $query_pdo .= " (SELECT m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate,m_no1,m_category,(m_category2+0),(m_fee+0),m_coin_no FROM $m_bankmoney where m_div<>? and m_category like 'trade%' and m_coin_no=? and ";
    $query_pdo .= " m_no1 in (select m_no1 from $m_bankmoney where  m_div=? and m_no1 <> '' $search))";
    $query_pdo .= " ORDER BY m_no desc, m_signdate desc";
    $pdo_in = [$m_div_div, $m_div_div, $m_div_div, $m_div_div];
}

try {
    $total_record_pdo = pdo_excute_count("select_count", $query_pdo, $pdo_in);
} catch (PDOException $e) {
    err_log($e->getMessage());
    error("QUERY_ERROR");
    exit;
}

$coin_array = array();
$num_per_page = 10;
$page_per_block = 10;

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
$mode = "keyfield=$keyfield&key=$key&m_div_div=$m_div_div";
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
            <table width="100%" border=0 cellpadding=0 cellspacing=0>
                <tr>
                    <td class='td14' align="center"><?=M_MEMBER_TRACE?><?= $c_coinname ?></td>
                    <td align="right">
                        <form name=dform action="./member_dis_excel.php" method=post target="_blank">
                            <input type="hidden" name="level_l" value="<?= $level_l ?>">
                            <? $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d")); ?>
                            <input type="hidden" name="file_name" value="<?= $file_name ?>">
                            <input type="hidden" name="dis" value="<?= $dis ?>">
                            <input type="hidden" name="member_count" value="<?= $member_count ?>">
                            <!-- 										<input type="submit" value="<?= $level_l ?> 엑셀다운로드"> -->
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
                <table width="800" border="0" cellspacing="0" cellpadding="4">
                    <tr>

                        <td height="20" align="left">
                            <select name="m_div_div" onchange="go_search();">
                                <option value=""><?=M_COIN.M_TYPE?></option>

                                <?

                                $query_pdo_select = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
                                $query_pdo_select .= " ORDER BY c_rank+0 asc";

                                $stmt = pdo_excute("select1", $query_pdo_select, null);
                                while ($row_coin = $stmt->fetch()) {
                                    $c_no = $row_coin["0"];
                                    $c_coin = $row_coin["1"];
                                    $coin_array[$c_no] = $c_coin;
                                    ?>
                                <option value=<?= $c_no ?> <? if ($m_div_div == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
                                <? }
                                err_log("===>list :" . var_export($coin_array, true))
                                ?>

                            </select>
                            &nbsp;&nbsp;
                            <select name="keyfield">
                                <option value="m_id" <? if ($keyfield == 'm_id') {
                                                            echo ("selected");
                                                        } ?>><?=M_ID?></option>
                                <option value="m_name" <? if ($keyfield == 'm_name') {
                                                            echo ("selected");
                                                        } ?>><?=M_NAME?></option>
                                <option value="m_handphone" <? if ($keyfield == 'm_handphone') {
                                                                echo ("selected");
                                                            } ?>><?=M_PHONE?></option>
                            </select>
                            <input type="text" name="key" value="<?= $key ?>" size="40" maxlength="40" class="adminbttn">

                            <input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
                        </td>
                    </tr>
                </table>

                <table width="1300" border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td colspan=9 height=3 bgcolor='#ffffff'></td>
                    </tr>
                    <tr align="center" bgcolor='#ffffff' class="list_title">
                        <td width="50" height="30"><?=M_NO?></td>
                        <td width="80" height="30"><?=M_ID?>(<?=M_NO?>)</td>
                        <td width="150" height="30"><?=M_TOTAL?><?= $c_coinname ?></td>
                        <td width="150" height="30"><?=M_USING?><?= $c_coinname ?></td>
                        <td width="150" height="30"><?=M_REST?><?= $c_coinname ?></td>
                        <td width="90" height="30"><?=M_SIGN_DATE?></td>
                        <td width="50" height="30"><?=M_USAGE?>(<?=M_HIS?>)</td>
                        <td width="100" height="30"><?=M_VARIANCE?></td>
                    </tr>
                    <tr>
                        <td colspan=9 height=2 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td colspan=9 height=3></td>
                    </tr>
                    <?

                    $ii = 0;
                    $query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
                    $stmt = pdo_excute("select", $query_pdo, $pdo_in);
                    while ($row = $stmt->fetch()) {

                        $m_no = $row[0];
                        $m_div = $row[1];
                        $m_coin = $coin_array[$m_div];
                        $m_userno = $row[2];
                        $m_id = $row[3];
                        $m_cointotal = $row[4];
                        $m_coinuse = $row[5];
                        $m_restcoin = $row[6];
                        $m_signdate = $row[7];
                        $m_no1 = $row[8];
                        $m_category = $row[9];
                        $m_category2 = $row[10];
                        $m_fee = $row[11];
                        $m_coin_no = $row[12];

                        $m_signdate = date("Y-m-d H:i:s", $m_signdate);

                        $query1 = "SELECT m_name FROM m_member where m_userno = ?";
                        $stmt1 = pdo_excute("select", $query1, [$m_userno]);
                        $row1 = $stmt1->fetch();
                        $m_name = $row1[0];


                        if (($ii + 1) % 2 == 0) {
                            $kk_bgcolor = "#FFFFFF";
                        } else {
                            $kk_bgcolor = "#F6F6F6";
                        }
                        $m_restcoin = bcsub($m_cointotal, $m_coinuse, 8);

                        if ($m_category == "buycancel") {
                            $category = M_BUY.M_CANCEL;
                        } else if ($m_category == "buywait") {
                            $category = M_BUY.M_ORDER;
                        } else if ($m_category == "sellwait") {
                            $category = M_SELL.M_ORDER;
                        } else if ($m_category == "sellcancel") {
                            $category = M_SELL.M_CANCEL;
                        } else if ($m_category == "tradepay") {
                            $category = "<B>".M_CLOSED.M_ACQUIRE."</B>";
                        } else if ($m_category == "tradebuy") {
                            $category = "<B>".M_BUY.M_CLOSED."</B>";
                        } else if ($m_category == "tradesell") {
                            $category = "<B>".M_SELL.M_CLOSED."</B>";
                        } else {
                            $category = $m_category;
                        }

                        ?>


                    <tr align="center" <?= ($m_div_div == $m_div ? "bgcolor='#BDB76B'" : "") ?>>
                        <td height="30"><?= $m_no ?>(<?= $m_coin ?>)</td>
                        <td height="30"><?= $m_id ?>(<?= $m_name ?>) </td>
                        <td height="30"><?= numberformat($m_cointotal, "money3", 4) ?></td>
                        <td height="30"><?= numberformat($m_coinuse, "money3", 4) ?></td>
                        <td height="30"><?= numberformat($m_restcoin, "money3", 4) ?></td>
                        <td height="30"><?= $m_signdate ?></td>
                        <td height="30"><?= $category ?>
                            <?
                                if (
                                    strpos($m_category, 'trade') !== false ||
                                    strpos($m_category, 'req') !== false
                                ) {
                                    echo "(<a href=\"../coin_point/member.php?keyfield=c_no&key=$m_no1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$m_no1</font></a>)&nbsp;";
                                } else if (strpos($m_category, 'buy') !== false) {
                                    echo "(<a href=\"../b_coinorderbuy/member.php?b_div_div=$m_coin_no&keyfield=b_no&key=$m_no1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$m_no1</font></a>)&nbsp;";
                                } else if (strpos($m_category, 'sell') !== false) {
                                    echo "(<a href=\"../b_coinordersell/member.php?b_div_div=$m_coin_no&keyfield=b_no&key=$m_no1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$m_no1</font></a>)&nbsp;";
                                }
                                ?>

                        </td>
                        <td height="30" align="center"><?= numberformat($m_category2, "money3", 4) . $m_coin ?>(<?= numberformat($m_fee, "money3", 4) ?>)</td>
                    </tr>
                    <tr>
                        <td colspan=9 height=1 bgcolor='#D2DEE8'></td>
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
                    echo "<a href=\"member.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
                }
                if ($page > 1) {
                    $page_num = $page - 1;
                    echo "<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
                }

                for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
                    if ($page == $direct_page) {
                        echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
                    } else {
                        echo "&nbsp;<a href=\"member.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
                    }
                }

                if ($IsNext > 0) {
                    $page_num = $page + 1;
                    echo "&nbsp;<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
                }
                if ($page != $total_page) {
                    echo "<a href=\"member.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
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