<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

if (isset($_REQUEST["key"])) {
    $key = $_REQUEST["key"];
} else {
    $key = "";
}
$encoded_key = urlencode($key);

if (isset($_REQUEST["keyfield"])) {
    $keyfield = $_REQUEST["keyfield"];
} else {
    $keyfield = "";
}

$auth_check0 = (isset($_REQUEST["auth_check0"]) ? $_REQUEST["auth_check0"] : "");
$auth_check1 = (isset($_REQUEST["auth_check1"]) ? $_REQUEST["auth_check1"] : "");
if ($auth_check0 == "" && $auth_check1 == "") $auth_check0 = "1";


$query_pdo = "SELECT A.m_no,A.m_id,A.m_userno,A.m_level as a_level,A.m_orderlevel,ifnull(A.m_check,0), ifnull(A.m_delete,0), FROM_UNIXTIME(A.m_checkdate+8*3600),A.m_howcheck,A.m_ip,FROM_UNIXTIME(A.m_signdate + 8*3600), B.m_level as b_level,";
$query_pdo .= " A.m_file1, A.m_file2,B.m_name FROM $table_authorization A INNER JOIN $member B ON A.m_userno = B.m_userno and A.m_level >= 2";
if ($key != "") {
    $query_pdo .= " and " . $keyfield . " LIKE '%$key%' ";
}

err_log("auth_check auth_check0:$auth_check0, auth_check1:$auth_check1 ");
if ($auth_check0 != $auth_check1) {
    $check = is_empty($auth_check0) ?  "1" : "0";
    $query_pdo .= " and m_check=$check";
}
$query_pdo .= " WHERE IFNULL(A.m_delete,0) <> 1 ORDER BY A.m_signdate DESC";

$total_record_pdo = pdo_excute_count("select", $query_pdo, NULL);

if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];
} else {
    $page = 1;
}

$num_per_page = 20;
$page_per_block = 10;

if (isset($_REQUEST["IsNext"])) {
    $IsNext = $_REQUEST["IsNext"];
} else {
    $IsNext = 0;
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
$mode = "keyfield=$keyfield&key=$encoded_key&auth_check1=$auth_check1&auth_check0=$auth_check0";
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
                    <td class='td14' align="center">
                        <? if ($m_check_div == "3") { 
                            echo M_AUTH_NO.M_MEMBER;
                        } else { 
                            echo M_AUTH_YES.M_MEMBER;
                        } ?>
                    </td>
                    <td align="center">
                        <form name=dform action="./member_dis_excel.php" method=post target="_blank">
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

                        <td height="20" align="center">

                            &nbsp;&nbsp;
                            <select name="keyfield">
                                <option value="A.m_id" <? if ($keyfield == 'A.m_id') echo ("selected"); ?>><?=M_ID?></option>
                                <option value="B.m_name" <? if ($keyfield == 'B.m_name') echo ("selected"); ?>><?=M_NAME?></option>
                            </select>
                            <input type="text" name="key" value="<?= $key ?>" size="40" maxlength="40" class="adminbttn">
                            <input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
                            <input type="button" value="내역등록" class="adminbttn" onClick="javascript:location.href='member_write.php'" style="display:none;">
                        </td>
                        <td height="20" align="left">
                            <input type="checkbox" name="auth_check1" value="1" <?= (strpos($auth_check1, '1') !== false  ? "checked" : "") ?> onchange=go_search();><?=M_CONFIRM?>
                            <input type="checkbox" name="auth_check0" value="1" <?= (strpos($auth_check0, '1') !== false  ? "checked" : "") ?> onchange=go_search();><?=M_NOT_CONFIRM?>
                        </td>
                    </tr>
                </table>
                <table width="1000" border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td colspan=9 height=3 bgcolor='#ffffff'></td>
                    </tr>
                    <tr align="center" bgcolor='#ffffff' >
                        <td width="50" height="30"><?=M_NO?></td>
                        <td width="100" height="30"><?=M_ID?>(<?=M_LEVEL?>)</td>
                        <td width="100" height="30"><?=M_NAME?></td>
                        <td width="60" height="30"><?=M_REQUEST.M_LEVEL?></td>
                        <td width="130" height="30"><?=M_CONTENT?></td>
                        <td width="90" height="30"><?=M_FILE?></td>
                        <td width="90" height="30"><?=M_SIGN_DATE?></td>
                        <td width="90" height="30"><?=M_REGISTER?>IP</td>
                        <td width="90" height="30"><?=M_MANAGER.M_CONFIRM?></td>
                        <td width="90" height="30"><?=M_CONFIRM.M_DATE?></td>
                    </tr>
                    <tr>
                        <td colspan=9 height=2 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td colspan=9 height=3></td>
                    </tr>
                    <?
                    #####################################################################
                    $ii = 0;
                    $query_pdo = convert_page_query1($query_pdo, $num_per_page, $page);
                    $stmt = pdo_excute("select", $query_pdo, NULL);
                    while ($row = $stmt->fetch()) {

                        $m_no = $row[0];
                        $m_id = $row[1];
                        $m_userno = $row[2];
                        $m_level = $row[3];
                        $m_orderlevel = $row[4];
                        $m_check = $row[5];
                        $m_delete = $row[6];
                        $m_checkdate = $row[7];
                        $m_howcheck = $row[8];
                        $m_ip = $row[9];
                        $m_signdate = $row[10];
                        $m_currentlevel = $row[11];
                        $m_file1 = $row[12];
                        $m_file2 = $row[13];
                        $m_name = $row[14];

                        $m_file = (is_empty($m_file1) && is_empty($m_file2) ? "" : "<B>".M_EXIST."</B>");
                        err_log("no:$m_userno, id:$m_id, ");

                        if ($m_check == "0" || $m_check == "") {
                            $kk_bgcolor = "#F6F6F6";
                            $m_check = M_NOT_CONFIRM;
                        } else {
                            $kk_bgcolor = "#FFFFFF";
                            $m_check = M_CONFIRM;
                        }

                        $m_signdate1 = date("M. d, Y", strtotime($m_signdate));
                        $m_checkdate1 = date("M. d, Y", strtotime($m_checkdate));

                        // if ($m_checkdate == "1970-01-01") {
                        //     $m_checkdate = M_NOT_CONFIRM;
                        // }

                        ?>

                    <tr align="center" bgcolor="<?= $kk_bgcolor ?>">
                        <td height="30"><?= $article_num ?></td>
                        <td height="30">
                            <a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&m_no=<?= $m_no ?>"><?= $m_id ?></a></td>
                        <td height="30">
                            <a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&m_no=<?= $m_no ?>"><B class="notranslate"><?= $m_name ?></B></a>
                        </td>
                        <td height="30">
                            <a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&m_no=<?= $m_no ?>"><B><?= $m_level . "->" . $m_orderlevel ?></B></a>
                        </td>
                        <td height="30" align="center"><?= $m_howcheck ?>
                        </td>
                        <td height="30"><?= $m_file ?></td>
                        <td height="30"><?= $m_signdate1 ?></td>
                        <td height="30"><?= $m_ip ?></td>
                        <td height="30"><?= $m_check ?></td>
                        <td height="30"><?= $m_checkdate1 ?></td>
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
                #####################################################################
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