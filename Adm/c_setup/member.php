<?php
#####################################################################
session_start();

include "../common/user_function.php";
include "../common/trading.php";
include "../common/dbconn.php";
include_once "../common/" . ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

check_manager_level($adminlevel, ADMIN_LVL4);

$m_market = (is_empty($_REQUEST["m_market"]) ? $DEFINE_DEFAULT_NAME : $_REQUEST["m_market"]);

if (isset($_REQUEST["key"])) {
    $key = $_REQUEST["key"];
} else {
    $key = "";
}

if (isset($_REQUEST["keyfield"])) {
    $keyfield = $_REQUEST["keyfield"];
} else {
    $keyfield = "";
}

if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];
} else {
    $page = 1;
}

if (isset($_REQUEST["m_confirm"])) {
    $m_confirm = $_REQUEST["m_confirm"];
} else {
    $m_confirm = "0";
}

$coinInfo = new CoinInfo($m_market);

$m_confirm = ($m_confirm == "0" ? M_AUTH_NO : M_AUTH_YES);

$encoded_key = urlencode($key);

$query_pdo = "SELECT B.m_unit as unit, B.m_limit as min_limit,B.m_rank as m_rank, B.m_use as m_use,B.m_suspend_yn as m_suspend_yn, A.* FROM $table_setup A";
$query_pdo .= " LEFT JOIN m_setup B ON A.c_no=B.m_div";
$query_pdo .= " WHERE B.m_pay = '$m_market'";

if ($key != "" && $keyfield != "") {
    $query_pdo .= " and A.$keyfield LIKE '%$key%'";
}

$query_pdo .= " ORDER BY B.m_rank+0 asc";

try {
    $total_record_pdo = pdo_excute_count("select1", $query_pdo, null);
} catch (PDOException $e) {
    err_log("Fatal:" . $e->getMessage());
    throw new Exception("QUERY_ERROR", __LINE__);
}

$num_per_page = 20;
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
$mode = "keyfield=$keyfield&key=$encoded_key&m_market=$m_market";
?>

<script language="javascript">
	function go_search() {
		document.form.action = "member.php?dis=<?=$dis?>";
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
					<td class='td14' align="center"><?=M_EXCHANGE1 . M_ENV . M_SETTING?></td>
					<td align="right">
						<form name=dform action="./member_dis_excel.php" method=post target="_blank">
							<input type="hidden" name="level_l" value="<?=$level_l?>">
							<?php $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d"));?>
							<input type="hidden" name="file_name" value="<?=$file_name?>">
							<input type="hidden" name="dis" value="<?=$dis?>">
							<input type="hidden" name="member_count" value="<?=$member_count?>">
							<!-- 										<input type="submit" value="<?=$level_l?> 엑셀다운로드"> -->
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
				<table width="900" border="0" cellspacing="0" cellpadding="4">
					<tr>

						<td height="20" align="center">
							<select name="m_market" onchange="go_search();">
								<option value=""><?=M_EXCHANGE?></option>

								<?
foreach ($DEFINE_MARKET as $item) {
    $selected = ($m_market == $item ? "selected" : "");
    echo "<option value=$item $selected>$item</option>";
}
?>
							</select>

							&nbsp;&nbsp;
							<select name="keyfield">
								<option value="c_coin" selected><?=M_COIN?></option>
							</select>
							<input type="text" name="key" value="<?=$key?>" size="16" maxlength="16" class="adminbttn">

							<input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
							<input type="button" value="<?=M_OP_NEW?>" class="adminbttn" onClick="javascript:location.href='member_write.php'">
						</td>
					</tr>
				</table>
				<table width="900" border='0' cellspacing='0' cellpadding='0'>
					<tr align="center" bgcolor='#ffffff'>
						<td width="60" height="30"><?=M_NO?></td>
						<td width="150" height="30"><?=M_COIN?>/<?=M_MARKET?></td>
						<td width="200" height="30"><?=M_TRADING . M_FEE?></td>
						<td width="150" height="30"><?=M_MIN . M_ORDER?></td>
						<td width="150" height="30"><?=M_MAX . M_ORDER?></td>
						<td width="80" height="30"><?=M_ASKPRICE?></td>
						<td width="80" height="30"><?=M_RANK?></td>
						<td width="80" height="30"><?=M_APPLY?></td>
						<td width="150" height="30"><?=M_COIN?></td>
						<td width="150" height="30"><?=M_DATE?></td>
						<td width="150" height="30"><?=M_WITHDRAW . M_FEE?></td>
						<td width="150" height="30"><?=M_MIN . M_DEPOSIT?></td>
						<td width="150" height="30"><?=M_MIN . M_WITHDRAW?></td>
						<td width="150" height="30"><?=M_TYPE?></td>
						<td width="100" height="30"><?=M_TRADE_STATUS?></td>
						<td width="100" height="30"><?=M_FUND_STATUS?></td>

					</tr>
					<tr>
						<td colspan=16 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<?php

$ii = 0;
$query_pdo = convert_page_query1($query_pdo, $num_per_page, $page);
$stmt = pdo_excute("select", $query_pdo, null);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $c_no = $row["c_no"];
    $c_coin = $row["c_coin"];
    $c_wcommission = $row["c_wcommission"];
    $c_min_limit = $row["min_limit"];
    $c_limit = $row["c_limit"];
    $c_limit = ($c_min_limit == "0" || is_empty($c_min_limit) ? $row["c_limit"] : $c_min_limit);
    $c_asklimit = $row["c_asklimit"];
    $c_unit = $row["unit"];
    $c_use = $row["m_use"];
    $c_rank = $row["m_rank"];
    $c_img = $row["c_img"];
    $c_title = $row["c_title"];
    $c_signdate = $row["c_signdate"];
    $c_fees = $row["c_fees"];
    $c_type = $row["c_type"];
    $m_suspend_yn = $row["m_suspend_yn"];
    $c_suspend_yn = $row["c_suspend_yn"];
    $c_basecoin = $row["c_basecoin"];

    $c_limit_in = $row["c_limit_in"];
    $c_limit_out = $row["c_limit_out"];

	$c_signdate = date("m-d", $c_signdate);

	
	$u_bgcolor = "";
	$c_bgcolor = "";
	$m_bgcolor = "";

	if ($c_use == "0") {
		$u_bgcolor = "bgcolor='#FF0000'";
		$c_use = "N";
	} else {
		$c_use = "Y";
	}


	if ($c_suspend_yn == "1" || $c_suspend_yn == "3") {
		$c_bgcolor = "bgcolor='#FF0000'";
		$c_suspend_yn = "N";
	} else {
		$c_suspend_yn = "Y";
	}

	if ($m_suspend_yn == "2" || $m_suspend_yn == "3") {
		$m_bgcolor = "bgcolor='#FF0000'";
		$m_suspend_yn = "N";
	} else {
		$m_suspend_yn = "Y";
	}


    if ($c_type == "0") $c_type = M_ALTCOIN;
    if ($c_type == "1") $c_type = M_ETH;
    if ($c_type == "2") $c_type = M_TOKEN;
    if ($c_type == "3") $c_type = M_RIPPLE;
    if ($c_type == "4") $c_type = "EOS";
    if ($c_type == "5") $c_type = M_TRON;
    if ($c_type == "6") $c_type = M_ALTCOIN."2";
    if ($c_type == "7") $c_type = "CELO";

    if ($c_coin == $m_market) {
        continue;
    }

    if ($c_basecoin == "1") {
        continue;
    }

    if (($ii + 1) % 2 == 0) {
        $kk_bgcolor = "#FFFFFF";
    } else {
        $kk_bgcolor = "#F6F6F6";
    }
    #####################################################################
    ?>

					<tr align="center" bgcolor='#f8f8f8'>
						<td height="30">
							<a href="member_modify.php?<?=$mode?>&page=<?=$page?>&c_no=<?=$c_no?>&m_market=<?=$m_market?>"><?=$article_num?></td>
						<td height="30">
							<a href="member_modify.php?<?=$mode?>&page=<?=$page?>&c_no=<?=$c_no?>&m_market=<?=$m_market?>"><?=$c_coin . "/" . $coinInfo->name?></td>
						<td height="30"><?=$c_wcommission?></td>
						<td height="30"><?=$c_limit?></td>
						<td height="30"><?=$c_asklimit?></td>
						<td height="30"><?=$c_unit?></td>
						<td height="30"><?=$c_rank?></td>
						<td height="30" <?=$u_bgcolor?>><?=$c_use?></td>
						<td height="30"><?=$c_title?></td>
						<td height="30"><a href="member_modify.php?<?=$mode?>&page=<?=$page?>&c_no=<?=$c_no?>&m_market=<?=$m_market?>"><?=$c_signdate?></a></td>
						<td height="30"><?=$c_fees?></td>
						<td height="30"><?=$c_limit_in?></td>
						<td height="30"><?=$c_limit_out?></td>
						<td height="30"><?=$c_type?></td>
						<td height="30" <?=$m_bgcolor?>><?=$m_suspend_yn?></td>
						<td height="30" <?=$c_bgcolor?>><?=$c_suspend_yn?></td>
					</tr>
					<tr>
						<td colspan=16 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<?php
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
				<?php
#####################################################################
$total_block = ceil($total_page / $page_per_block);
$block = ceil($page / $page_per_block);
$first_page = ($block - 1) * $page_per_block;
$last_page = $block * $page_per_block;
if ($total_block <= $block) {
    $last_page = $total_page;
}

if ($page != '1') {
    echo "<a href=\"member.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">" . M_FIRST . "</a>&nbsp;";
}
if ($page > 1) {
    $page_num = $page - 1;
    echo "<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='" . M_PREVPAGE . "';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
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
    echo "&nbsp;<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='" . M_NEXTPAGE . "';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
}
if ($page != $total_page) {
    echo "<a href=\"member.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">" . M_LAST . "</a>";
}
?>
			</font>
		</td>
	</tr>
	<input type="hidden" name="chk_num" value="<?php echo ($chk_num) ?>">
	</form>
</table>
<br><br>
<?php include "../inc/down_menu.php";?>