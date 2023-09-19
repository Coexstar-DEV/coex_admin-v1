<?
session_start();

include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../common/trading.php";
include_once "../common/withdraw.php";
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_inout.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$adminlevel = $_SESSION["level"];
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$opt_check0 = (isset($_REQUEST["opt_check0"]) ? $_REQUEST["opt_check0"] : "");
$opt_check1 = (isset($_REQUEST["opt_check1"]) ? $_REQUEST["opt_check1"] : "");

$ydate1 = (isset($_REQUEST["ydate1"]) ? $_REQUEST["ydate1"] : date('Y'));
$mdate1 = (isset($_REQUEST["mdate1"]) ? $_REQUEST["mdate1"] : date('m'));
$ddate1 = (isset($_REQUEST["ddate1"]) ? $_REQUEST["ddate1"] : date('d') - 2);

$ydate2 = (isset($_REQUEST["ydate2"]) ? $_REQUEST["ydate2"] : date('Y'));
$mdate2 = (isset($_REQUEST["mdate2"]) ? $_REQUEST["mdate2"] : date('m'));
$ddate2 = (isset($_REQUEST["ddate2"]) ? $_REQUEST["ddate2"] : date('d'));


$wdate1 = mktime(0, 0, 0, $mdate1, $ddate1, $ydate1);
$wdate2 = mktime(23, 59, 59, $mdate2, $ddate2, $ydate2);


if ($mdate1 != '' || $ddate1 != '' || $ydate1 != '') {
	$where_date1 = " where t_signdate > '$wdate1'";
} else {
	$where_date1 = "";
}

if ($mdate2 != '' || $ddate2 != '' || $ydate2 != '') {
	if ($where_date1 == '') {
		$where_date2 = " where t_signdate < '$wdate2'";
	} else {
		$where_date2 = " and t_signdate < '$wdate2'";
	}
} else {
	$where_date2 = "";
}

$key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");
$encoded_key = urlencode($key);

$krw = (isset($_REQUEST["krw"]) ? $_REQUEST["krw"] : "");
$t_check = (isset($_REQUEST["t_check"]) ? $_REQUEST["t_check"] : "");
$t_division = (isset($_REQUEST["t_division"]) ? $_REQUEST["t_division"] : "");
$keyfield = (isset($_REQUEST["keyfield"]) ? $_REQUEST["keyfield"] : "t_no");

if (isset($_REQUEST["key"])) {
	$keyfield = $_REQUEST["keyfield"];
}

$page = is_empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
//err_log("================set page:$page");

$num_per_page = 10;
$page_per_block = 10;

$IsNext = is_empty($_REQUEST["IsNext"]) ? "" : $_REQUEST["IsNext"];

// ==========================================
$Board_Title = ($t_division == "0" ? $DEFINE_DEFAULT_NAME : M_TOTAL) . " ". M_WITHDRAW.M_HIS;

$query_pdo = "SELECT t_division,c_coin,c_title,t_no,t_check,t_userno,t_id,t_email,t_ordermost,t_orderkrw,t_depositmost,t_depositkrw,t_fees,t_signdate,t_name,t_acount,t_bankname,t_ip,t_delete,t_pending FROM $table_withdraw";
$query_pdo .= " LEFT JOIN c_setup ON t_division = c_no";
$query_pdo .= " WHERE t_signdate > ? and t_signdate < ?";
if (!is_empty($t_division)) {
	$query_pdo .= " and t_division=$t_division";
}

err_log("opt_check opt_check0:$opt_check0, opt_check1:$opt_check1 ");
if ($opt_check0 != $opt_check1) {
	$check = is_empty($opt_check0) ?  "1" : "0";
	$query_pdo .= " and t_check=$check";
}

if (!is_empty($keyfield) && !is_empty($key)) {
	$query_pdo .= " and $keyfield LIKE '%$key%'";
}
$query_pdo .= " ORDER BY t_no DESC, t_signdate DESC";
$pdo_in = [$wdate1, $wdate2];

try {
	$total_record_pdo = pdo_excute_count("withdrawList", $query_pdo, $pdo_in);
} catch (PDOException $e) {
	err_log($e->getMessage());
	exit;
}

if ($total_record_pdo == 0) {
	$first = 1;
	$last = 0;
	//err_log("=>1 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");
} else {
	$first = $num_per_page * ($page - 1);
	$last = $num_per_page * $page;

	$IsNext = $total_record_pdo - $last;
	//err_log("=>2 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");
	if ($IsNext > 0) {
		$last -= 1;
	} else {
		$last = $total_record_pdo - 1;
	}
}
//err_log("=>3 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");

$total_page = ceil($total_record_pdo / $num_per_page);
$article_num = $total_record_pdo - $num_per_page * ($page - 1);
$mode = "keyfield=$keyfield&key=$encoded_key&t_check=$t_check&krw=$krw&ddate1=$ddate1&mdate1=$mdate1&ydate1=$ydate1&ddate2=$ddate2&mdate2=$mdate2&ydate2=$ydate2&t_division=$t_division&opt_check0=$opt_check0&opt_check1=$opt_check1";
?>

<script language="javascript">
	function go_search() {
		document.form.action = "member_coin.php?dis=<?= $dis ?>";
		document.form.submit();
	}

	function go_excel() {
		document.dform.submit();
	}

	function go_highpass(t_no) {
		if (confirm('<?=M_CONFIRM_MSG2?>')) {
			document.form.action = "high_pass.php?t_no=" + t_no;
			document.form.submit();
		}
	}
</script>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table width="800" align="center" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center"><?= $Board_Title ?></td>
				</tr>
				<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'LMAlcaraz1993' ){ ?>
				<tr>
					<td align="right">
						<form name=dform action="./member_dis_excel3.php" method=post target="_blank">
							<input type="hidden" name="wdate1" value="<?= $wdate1 ?>">
							<input type="hidden" name="wdate2" value="<?= $wdate2 ?>">
							<input type="hidden" name="level_l" value="<?= $level_l ?>">
							<? $file_name = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); ?>
							<input type="hidden" name="file_name" value="<?= $file_name ?>">
							<input type="hidden" name="t_check" value=<?= $t_check ?>>
							<input type="hidden" name="krw" value=<?= $krw ?>>
							<input type="hidden" name="t_division" value=<?= $t_division ?>>
							<input type="button" value="<?= @$level_l ?> <?=M_EXELDOWN?>" onclick="go_excel()" class="exBt">
						</form>
					</td>
				</tr>
				<?php } else { ?>
				  &nbsp;
				<?php } ?>
			</table>
		</td>
	</tr>
	<form name="form" method="post">

		<tr>
			<td height=3></td>
		</tr>

		<tr>
			<td>
				<table style="width:100%;" align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>
						<td>
							<select name="t_division" onchange="go_search();">
								<option value="">COIN</option>
								<?

								$query_pdo_2 = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
								$query_pdo_2 = $query_pdo_2 . "where c_use='1' ORDER BY c_rank+0 asc";
								$stmt = $pdo->prepare($query_pdo_2);
								$stmt->execute();
								$total_record_coin_pdo = $stmt->rowCount();

								?>
								<? for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
									$stmt = $pdo->prepare($query_pdo_2);
									$stmt->execute();
									$result_pdo = $stmt->fetchAll();
									$c_no = $result_pdo[$ki][0];
									$c_coin = $result_pdo[$ki][1];
									?>
								<option value=<?= $c_no ?> <? if ($t_division == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
								<? } ?>
							</select>
						</td>
						<td>
							<select name="ydate1" class="formbox3">
								<? for ($i = 2013; $i <= Date("Y") + 2; $i++) { ?>
								<option value="<?= $i ?>" <? if ($ydate1 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_YEAR?></span>&nbsp;

							<select name="mdate1" class="formbox3">
								<? for ($i = 1; $i < 13; $i++) { ?>
								<option value="<?= $i ?>" <? if ($mdate1 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_MONTH?></span>&nbsp;

							<select name="ddate1" class="formbox3">
								<? for ($i = 1; $i < 32; $i++) { ?>
								<option value="<?= $i ?>" <? if ($ddate1 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_DAY?></span>&nbsp;
							~
							<select name="ydate2" class="formbox3">
								<? for ($i = 2013; $i <= Date("Y") + 2; $i++) { ?>
								<option value="<?= $i ?>" <? if ($ydate2 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_YEAR?></span>&nbsp;

							<select name="mdate2" class="formbox3">
								<? for ($i = 1; $i < 13; $i++) { ?>
								<option value="<?= $i ?>" <? if ($mdate2 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_MONTH?></span>&nbsp;

							<select name="ddate2" class="formbox3">
								<? for ($i = 1; $i < 32; $i++) { ?>
								<option value="<?= $i ?>" <? if ($ddate2 == $i) { ?>selected<? } ?>><?= $i ?></option>
								<? } ?>
							</select><span class="text2"><?=M_DAY?></span>&nbsp;

						</td>
						<td height="20" align="left">
							<input type="checkbox" name="opt_check1" value="1" <?= (strpos($opt_check1, '1') !== false  ? "checked" : "") ?> onchange=go_search();><?=M_CONFIRM?>
							<input type="checkbox" name="opt_check0" value="1" <?= (strpos($opt_check0, '1') !== false  ? "checked" : "") ?> onchange=go_search();><?=M_NOT_CONFIRM?>
						</td>

						<td height="20" align="left">
							&nbsp;&nbsp;
							<select name="keyfield">
								<option value="t_id" <? if ($keyfield == 't_id') echo ("selected"); ?>><?=M_ID?></option>
								<option value="t_name" <? if ($keyfield == 't_name') echo ("selected"); ?>><?=M_NAME?></option>
								<option value="t_division" <? if ($keyfield == 't_division') echo ("selected"); ?>><?=M_DIVISION?></option>
								<option value="t_ordername" <? if ($keyfield == 't_ordername') echo ("selected"); ?>><?=M_ORDERER?></option>
								<option value="t_userno" <? if ($keyfield == 't_userno') echo ("selected"); ?>><?=M_MEMBER.M_NO?></option>
							</select>
							<input type="text" name="key" value="<?= $key ?>" size="16" maxlength="16" class="adminbttn">
							<input type="hidden" name="t_check" value=<?= $t_check ?>>
							<input type="hidden" name="krw" value=<?= $krw ?>>
							<input type="hidden" name="page" value=<?= $page ?>>

							<input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
						</td>

					</tr>
				</table>

				<table width="1200" border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td colspan=16 height=3 bgcolor='#ffffff'></td>
					</tr>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="50" height="30"><?=M_DIVISION?></td>
						<td width="50" height="30"><?=M_STATUS?></td>
						<td width="100" height="30"><?=M_ID?>(<?=M_NO?>)</td>
						<td width="100" height="30"><?=M_NAME?></td>
						<td width="100" height="30"><?=M_ORDER_COST?></td>
						<td width="120" height="30"><?=M_CLOSE_COST?></td>

						<td width="90" height="30"><?=M_SIGN_DATE?></td>
						<td width="90" height="30"><?=M_IP?></td>
					</tr>
					<tr>
						<td colspan=16 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<?
					$ii = 0;
					$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
					$stmt = pdo_excute("select", $query_pdo, $pdo_in);
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

						$t_division = $row["t_division"];
						$t_no = $row["t_no"];
						$t_check = $row["t_check"];
						$t_userno = $row["t_userno"];
						$t_id = $row["t_id"];
						$t_email = $row["t_email"];
						$t_ordermost = $row["t_ordermost"];
						$t_orderkrw = $row["t_orderkrw"];
						$t_depositmost = $row["t_depositmost"];
						$t_depositkrw = $row["t_depositkrw"];
						$t_fees = $row["t_fees"];
						$t_signdate = $row["t_signdate"];
						$t_name = $row["t_name"];
						$t_acount = $row["t_acount"];
						$t_bankname = $row["t_bankname"];
						$t_ip = $row["t_ip"];
						$t_delete = $row["t_delete"];
						$t_pending = $row["t_pending"];
						$c_coin = $row["c_coin"];
						$c_title = $row["c_title"];

						$t_closemost = bcsub($t_ordermost, $t_fees, 8);
						if ($t_check == "0") {
							$t_check = "X";
							$kk_bgcolor = "#feeee0";
							if ($t_pending > "0") {
								$t_check = "Pending" . $t_pending;
								$kk_bgcolor = "#feeeff";
							}
						} else {
							if ($t_delete == "1") {
								$t_check = "Cancel";
								$kk_bgcolor = "#eeeeee";
							} else {
								$t_check = "O";
								$kk_bgcolor = "#FFFFFF";
								//$t_closemost = $t_ordermost;
							}
						}
						$t_signdate = date("Y-m-d H:i:s", $t_signdate);
						?>


					<tr align="center" bgcolor="<?= $kk_bgcolor ?>">
						<td height="30"><?= $t_no ?></td>
						<td height="30" align="center"><?= $c_coin ?> (<?= $c_title ?>)</td>
						<td height="30" align="center"><?= $t_check ?></td>
						<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&t_no=<?= $t_no ?>"><?= $t_id ?>(<?= $t_userno ?>)</td></a>
						<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&t_no=<?= $t_no ?>"><?= $t_name ?></td>
						<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&t_no=<?= $t_no ?>"><B><?= numberformat($t_ordermost, "money2", 8) ?></B> </a> </td>
						<td height="30" align="center"><?= numberformat($t_closemost, "money2", 8) ?></td>

						<td height="30"><?= $t_signdate ?></td>
						<!-- <td height="30"><? if ($t_check == "X") { ?><a onclick="go_highpass(<?= $t_no ?>);" style="cursor:pointer">지급<? } else { ?>지급완료<? } ?></td> -->
						<td height="30"><?= $t_ip ?></td>
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
				#####################################################################
				$total_block = ceil($total_page / $page_per_block);
				$block = ceil($page / $page_per_block);
				$first_page = ($block - 1) * $page_per_block;
				$last_page = $block * $page_per_block;
				if ($total_block <= $block) {
					$last_page = $total_page;
				}

				if ($page != '1') {
					echo "<a href=\"member_coin.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
				}
				if ($page > 1) {
					$page_num = $page - 1;
					echo "<a href=\"member_coin.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
				}

				for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
					if ($page == $direct_page) {
						echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
					} else {
						echo "&nbsp;<a href=\"member_coin.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
					}
				}

				if ($IsNext > 0) {
					$page_num = $page + 1;
					echo "&nbsp;<a href=\"member_coin.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
				}
				if ($page != $total_page) {
					echo "<a href=\"member_coin.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
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