<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

if (isset($_REQUEST["key"])) {
	$key = $_REQUEST["key"];
} else {
	$key = "";
}
$encoded_key = urlencode($key);

$query_pdo = "SELECT m_division,m_userno,m_id,m_result,m_ip,m_signdate FROM $m_admlogin ";

if (isset($_REQUEST["keyfield"])) {
	$keyfield = $_REQUEST["keyfield"];
} else {
	$keyfield = "";
}

if ($key != "") {
	$query_pdo .= " where $keyfield LIKE '%$key%' ";
}
$query_pdo .= "ORDER BY m_signdate DESC";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute();
$result_pdo = $stmt->fetch();

if (!$result_pdo) {
	error("QUERY_ERROR");
	exit;
}

$total_record_pdo = $stmt->rowCount();

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
					<td class='td14' align="center"><?=M_MANAGER_LOG?></td>
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
				<table width="800" border="0" cellspacing="0" cellpadding="4">
					<tr>
						<td height="20" align="left">
							&nbsp;&nbsp;
							<select name="keyfield">
								<option value="m_id" <? if ($keyfield == 'm_id') echo ("selected"); ?>><?=M_ID?></option>
								<option value="m_name" <? if ($keyfield == 'm_name') echo ("selected"); ?>><?=M_NAME?></option>
								<option value="m_handphone" <? if ($keyfield == 'm_handphone') echo ("selected"); ?>><?=M_PHONE?></option>
								<option value="m_ip" <? if ($keyfield == 'm_ip') echo ("selected"); ?>><?=M_IP?></option>
							</select>
							<input type="text" name="key" value="<?= $key ?>" size="16" maxlength="16" class="adminbttn">

							<input type="button" value="검색" class="adminbttn" onClick="javascript:go_search()">
						</td>
					</tr>
				</table>
				<table width="900" border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td colspan=9 height=3 bgcolor='#ffffff'></td>
					</tr>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="100" height="30"><?=M_ID?>(<?=M_NAME?>)</td>
						<td width="150" height="30"><?=M_DIVISION?></td>
						<td width="50" height="30"><?=M_RESULT.M_CODE?></td>
						<td width="200" height="30"><?=M_RESULT.M_MSG?></td>
						<td width="90" height="30"><?=M_IP?></td>
						<td width="120" height="30"><?=M_DATE?></td>
					</tr>
					<tr>
						<td colspan=9 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td colspan=9 height=3></td>
					</tr>
					<?

					$ii = 0;
					for ($i = $first; $i <= $last; $i++) {

						$stmt = $pdo->prepare($query_pdo);
						$stmt->execute();
						$row = $stmt->fetchAll();

						$m_division = $row[$i][0];
						$m_userno = $row[$i][1];
						$m_id = $row[$i][2];
						$m_result = $row[$i][3];
						$m_ip = $row[$i][4];
						$m_signdate = $row[$i][5];

						if ($m_result == "1") {
							$m_resultmsg = M_LOGIN_OK;
						} else if ($m_result == "2") {
							$m_resultmsg = M_WRONG_ID;
						} else if ($m_result == "3") {
							$m_resultmsg = M_WRONG_PWD;
						} else if ($m_result == "4") {
							$m_resultmsg = M_NOT_VERIFIED;
						}

						$m_signdate = date("Y-m-d h:i:s", $m_signdate);

						if (($i + 1) % 2 == 0) {
							$kk_bgcolor = "#FFFFFF";
						} else {
							$kk_bgcolor = "#F6F6F6";
						}

						?>
					<tr align="center">
						<td height="30"><?= $article_num ?></td>
						<td height="30"><?= $m_id ?>(<?= $m_userno ?>) </td>
						<td height="30"><B><?= $m_division ?></B>

						</td>
						<td><?= $m_result ?></td>
						<td height="30" align="center"><?= $m_resultmsg ?></td>
						<td height="30"><?= $m_ip ?></td>
						<td height="30"><?= $m_signdate ?></td>
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