<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
$encoded_key = urlencode($key);

$query_pdo = "SELECT c_no,c_coin,c_level,c_deposit,c_withdraw,c_limit,c_signdate, IFNULL(c_delete,0) FROM $table_level ";

if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}

if ($key != "") {
	$query_pdo .= " where $keyfield LIKE '%$key%' AND IFNULL(c_delete,0) <> 1 ";
}
$query_pdo .= "ORDER BY c_coin+0 asc,c_level DESC,c_coin desc ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute();
$result_pdo = $stmt->fetch();

if (!$result_pdo) {
	error("QUERY_ERROR");
	exit;
}

$total_record_pdo = $stmt->rowCount();

if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = 1;
}

$num_per_page = 50;
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
$mode = "keyfield=$keyfield&key=$encoded_key";

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
					<td class='td14' align="center"><?=M_OP_LEVEL_LIMIT?></td>
					<td align="right">
						<form name=dform action="./member_dis_excel.php" method=post target="_blank">
							<input type="hidden" name="level_l" value="<?= $level_l ?>">
							<? $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d")); ?>
							<input type="hidden" name="file_name" value="<?= $file_name ?>">
							<input type="hidden" name="dis" value="<?= $dis ?>">
							<input type="hidden" name="member_count" value="<?= $member_count ?>">
							<!-- <input type="submit" value="<?= $level_l ?> 엑셀다운로드"> -->
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
							<input type="button" value="<?=M_OP_NEW?>" class="adminbttn" onClick="javascript:location.href='member_write.php'">
						</td>
					</tr>
				</table>
				<table width="900" border='0' cellspacing='0' cellpadding='0'>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="100" height="30"><?=M_DIVISION?></td>
						<td width="100" height="30"><?=M_LEVEL?></td>
						<td width="150" height="30"><?=M_MAX.M_DEPOSIT.M_LIMIT?></td>
						<td width="150" height="30"><?=M_MAX.M_WITHDRAW.M_LIMIT?></td>
						<td width="150" height="30"><?=M_MAX.M_DAYLY.M_LIMIT?></td>
						<td width="150" height="30"><?=M_SIGN_DATE?></td>
					</tr>
					<tr>
						<td colspan=9 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<?
					$ii = 0;
					for ($i = $first; $i <= $last; $i++) {
						$stmt = $pdo->prepare($query_pdo);
						$stmt->execute();
						$row = $stmt->fetchAll();

						$c_no = $row[$i][0];
						$c_coin = $row[$i][1];
						$c_level = $row[$i][2];
						$c_deposit = $row[$i][3];
						$c_withdraw = $row[$i][4];
						$c_limit = $row[$i][5];
						$c_signdate = $row[$i][6];
						$c_delete = $row[$i][7];

						$query_pdo2 = "SELECT c_title,c_wcommission,c_limit,c_asklimit,c_use,c_signdate, c_coin FROM $table_setup WHERE c_no=? ";

						$stmt2 = $pdo->prepare($query_pdo2);
						$stmt2->execute(array($c_coin));
						$row2 = $stmt2->fetch();

						$c_wcommission = $row2[1];
						$c_asklimit = $row2[3];
						$c_use = $row2[4];
						$c_signdate = $row2[5];
						$c_coin_title = $row2[6];

						if (isset($_REQUEST["m_confirm"])) {
							$m_confirm = sqlfilter($_REQUEST["m_confirm"]);
						} else {
							$m_confirm = "0";
						}

						if ($m_confirm == "0") {
							$m_confirm = M_AUTH_NO;
						} else {
							$m_confirm = M_AUTH_YES;
						}
						if ($c_use == "0") {
							$c_use = "미적용";
						} else {
							$c_use = "적용";
						}
						$c_signdate = date("Y-m-d", $c_signdate);

						if (($i + 1) % 2 == 0) {
							$kk_bgcolor = "#FFFFFF";
						} else {
							$kk_bgcolor = "#F6F6F6";
						}

						?>

					<tr align="center" bgcolor='#f8f8f8'>
						<td height="30"><?= $article_num ?>(<?= $c_coin ?>)</td>
						<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>&c_coin=<?= $c_coin ?>&c_coin2=<?= $c_coin_title ?>&c_level=<?= $c_level ?>"><?= $c_coin_title ?></td>
						<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>&c_coin_title=<? $c_title ?>"><B><?= $c_level ?></B>
							</a>
						</td>
						<td height="30"><?= $c_deposit ?></td>
						<td height="30"><?= $c_withdraw ?></td>
						<td height="30"><?= $c_limit ?></td>
						<td height="30"><?= $c_signdate ?></td>
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
					echo "<a href=\"member.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">처음</a>&nbsp;";
				}
				if ($page > 1) {
					$page_num = $page - 1;
					echo "<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='이전페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
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
					echo "&nbsp;<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='다음페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
				}
				if ($page != $total_page) {
					echo "<a href=\"member.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">마지막</a>";
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