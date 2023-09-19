<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
$keyfield = isset($_REQUEST["keyfield"]) ? $_REQUEST["keyfield"] : "";
if (isset($_REQUEST["c_div_div"])) {
    $c_div_div = sqlfilter($_REQUEST["c_div_div"]);
} else {
    $c_div_div = "";
}

$encoded_key = urlencode($key);

if ($c_div_div == "") {
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,(c_exchange+0) c_exchange, c_signdate, (select c_coin from c_setup where c_no = c_div) c_coin FROM $table_point WHERE c_category = 'reqorderrecv' AND c_manual = 1 ";

    if ($key != "") {
        $query_pdo .= " AND $keyfield LIKE '%$key%' ";
    }
    $pdo_in = null;
} else {
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,(c_exchange+0) c_exchange, c_signdate, (select c_coin from c_setup where c_no = c_div) c_coin FROM $table_point  WHERE c_div=? AND c_category = 'reqorderrecv' AND c_manual = 1 ";
    if ($key != "") {
        $query_pdo .= " AND $keyfield LIKE '%$key%' ";
    }
    $pdo_in = [$c_div_div];
}
$query_pdo .= "ORDER BY c_no+0 DESC";

err_log("===>" . $query_pdo);
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
@$mode = "keyfield=$keyfield&key=$encoded_key&k_checkk=$k_checkk&page=$page&c_div_div=$c_div_div";

?>

<script language="javascript">
    function go_search() {
        document.form.action = "recive_coin_list.php?dis=<?= $dis ?>";
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
                    <td class='td14' align="center"><?=M_MANUAL.M_DEPOSIT." ".M_HIS?></td>
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
                                <option value=""><?=M_OPTION?></option>
                                <?
                                $query_pdo2 = "SELECT 	c_no,c_coin FROM $table_setup ";
                                $query_pdo2 .= " where c_use='1' and c_basecoin = 0 ORDER BY c_rank+0 asc";

                                $stmt = pdo_excute("select3", $query_pdo2, null);

                                $total_record_coin_pdo = $stmt->rowCount();
                                while ($row = $stmt->fetch()) {
                                    $c_no = $row[0];
                                    $c_coin = $row[1];
                                    ?>
                                <option value=<?= $c_no ?> <? if ($c_div_div == $c_no) echo "selected"; ?>> <?= $c_coin ?></option>
                                <?
                                } ?>
                            </select>
                            &nbsp;&nbsp;
                            <select name="keyfield">
                                <option value="c_id" <? if ($keyfield == 'c_id') { echo ("selected"); } ?>><?=M_ID?></option>
                            </select>
                            <input type="text" name="key" value="<?= $key ?>" size="16" maxlength="16" class="adminbttn">

                            <input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
                            <input type="button" value="<?=M_REGISTRATION?>" class="adminbttn" onClick="javascript:location.href='recive_coin_write.php'">
                        </td>
                    </tr>
                </table>
                <table width="1000" border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td colspan=6 height=3 bgcolor='#ffffff'></td>
                    </tr>
                    <tr align="center" bgcolor='#ffffff' class="list_title">
                        <td width="100" height="30"><?=M_NO?></td>
                        <td width="120" height="30"><?=M_DIVISION?></td>
                        <td width="200" height="30"><?=M_ID."(".M_MEMBER." ".M_NO.")"?></td>
                        <td width="120" height="30"><?=M_AMOUNT?></td>
                        <td width="150" height="30"><?=M_DATE1?></td>
                    </tr>
                    <tr>
                        <td colspan=6 height=2 bgcolor='#D2DEE8'></td>
                    </tr>
                    <?

                    $ii = 0;
                    $query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
                    $stmt = pdo_excute("select", $query_pdo, $pdo_in);
                    while ($row = $stmt->fetch()) {

                        $c_no = $row[0];
                        $c_div = $row[1];
                        $c_userno = $row[2];
                        $c_id = $row[3];
                        $c_exchange = $row[4];
                        $c_signdate = $row[5];
                        $c_coin2 = $row[6];

                        $c_signdate = date("Y-m-d H:i:s", $c_signdate);

                        if (($ii + 1) % 2 == 0) {
                            $kk_bgcolor = "#FFFFFF";
                        } else {
                            $kk_bgcolor = "#F6F6F6";
                        }
                    ?>

                    <tr align="center">
                        <td height="40"><?= $c_no ?></td>
                        <td height="40"><?= $c_coin2 ?></td>
                        <td height="40"><?= $c_id ?>(<?= $c_userno ?>)</td>
                        <td height="40" align="center"><?= $c_exchange ?></td>
                        <td height="40"><?= $c_signdate ?></td>
                    </tr>
                    <tr>
                        <td colspan=6 height=1 bgcolor='#D2DEE8'></td>
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
                #####################################################################
                $total_block = ceil($total_page / $page_per_block);
                $block = ceil($page / $page_per_block);
                $first_page = ($block - 1) * $page_per_block;
                $last_page = $block * $page_per_block;
                if ($total_block <= $block) {
                    $last_page = $total_page;
                }

                if ($page != '1') {
                    echo "<a href=\"recive_coin_list.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">처음</a>&nbsp;";
                }
                if ($page > 1) {
                    $page_num = $page - 1;
                    echo "<a href=\"recive_coin_list.php?$mode&page=$page_num\" onMouseOver=\"status='이전페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
                }

                for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
                    if ($page == $direct_page) {
                        echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
                    } else {
                        echo "&nbsp;<a href=\"recive_coin_list.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
                    }
                }

                if ($IsNext > 0) {
                    $page_num = $page + 1;
                    echo "&nbsp;<a href=\"recive_coin_list.php?$mode&page=$page_num\" onMouseOver=\"status='다음페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
                }
                if ($page != $total_page) {
                    echo "<a href=\"recive_coin_list.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">마지막</a>";
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