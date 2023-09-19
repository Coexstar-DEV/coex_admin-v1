<?

session_start();

include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../common/trading.php";
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_order.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$b_div_div = is_empty($_REQUEST["b_div_div"]) ? "" : $_REQUEST["b_div_div"];
$div = is_empty($_REQUEST["div"]) ? "" : $_REQUEST["div"];
$table_ordersell = $div == "1" ? $table_ordersell : $table_ordersell;

$key = is_empty($_REQUEST["key"]) ? "" : $_REQUEST["key"];
$b_statee = is_empty($_REQUEST["b_statee"]) ? "" : $_REQUEST["b_statee"];
$keyfield = is_empty($_REQUEST["keyfield"]) ? "" : $_REQUEST["keyfield"];
$page = is_empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
$IsNext = is_empty($_REQUEST["IsNext"]) ? "" : $_REQUEST["IsNext"];

$pay_type = is_empty($_REQUEST["pay_type"]) ? 999 : $_REQUEST["pay_type"];

if ($pay_type != 999)
	$payInfo = new CoinInfo($pay_type);

if ($b_div_div == "") {

	$query_pdo = "SELECT c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
	$query_pdo .= "where c_use='1' and c_basecoin = 0 ORDER BY c_rank+0 asc limit 1 ";
	$stmt = $pdo->prepare($query_pdo);
	$stmt->execute();
	$row_div = $stmt->fetch();

	if (!$row_div) {
		err_log("------------" . __LINE__);
		error("QUERY_ERROR");
		exit;
	}

	$b_div_div = $row_div[0];
	$c_coinname = $row_div[1];
} else {
	$query_div_pdo = "SELECT c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup WHERE c_no=? ";
	$stmt = $pdo->prepare($query_div_pdo);
	$stmt->execute(array($b_div_div));
	$row_div = $stmt->fetch();
	$b_div_div = $row_div[0];
	$c_coinname = $row_div[1];
}

$query_pay = "SELECT c_no, c_coin, c_basecoin FROM $table_setup WHERE c_use = '1' and c_no <> '" . $b_div_div . "' ORDER BY c_no ASC";
$stmt_pay = pdo_excute($LOG_TAG, $query_pay, "");
$coins = array();
while ($row_pay = $stmt_pay->fetch()) {
	$coins[] = $row_pay;
}
####코인 확인##########
$encoded_key = urlencode($key);

$query_pdo = "SELECT b_no,b_state,b_ordermost,b_closecost,b_closefees,b_orderprice,b_pricetotal,b_closeprice,b_closetotal,b_userno,b_id,b_signdate,b_ip,b_delete,b_orderfees,b_closedate,b_pay,b_market   FROM $table_ordersell where b_div= ? ";

if ($b_statee != "") {
	$query_pdo .= " and b_state<>'wait'";
}

if ($keyfield != "" && $key != "") {
	$query_pdo .= " and $keyfield LIKE '%$key%' ";
}
if ($pay_type != "" && $pay_type != "999") {
	$query_pdo .= " and b_pay = '" . $payInfo->type . "'";
}

$query_pdo .= "ORDER BY b_no DESC";
$total_record_pdo = pdo_excute_count($LOG_TAG, $query_pdo, [$b_div_div]);

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
$mode = "keyfield=$keyfield&key=$encoded_key&b_statee=$b_statee&b_div_div=$b_div_div&div=$div&pay_type=$pay_type";

#####################################################################
?>

<script language="javascript">

	function go_search() {
		document.form.action = "member.php?b_statee=<?= $b_statee ?>";
		document.form.submit();
	}

</script>
<div style="background-color:#eee; height:25px; width:100%; line-height: 2;text-align:left;padding-left:10%;">
	<a href='../b_coinorderbuy/member.php?b_div_div=<?= $b_div_div ?>' style="margin-right:10px;"><?= $c_coinname ?><?=M_BUY.M_ORDER.M_HIS?></a>
	<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
	<a href='../b_coinordersell/member.php?b_div_div=<?= $b_div_div ?>'' style="margin-right:10px;"><?= $c_coinname ?><?=M_SELL.M_ORDER.M_HIS?></a>
<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
<a href=' ../b_coinorderbuy/member.php?b_statee=com&b_div_div=<?= $b_div_div ?>''><?= $c_coinname ?><?=M_BUY.M_DONE.M_HIS?> </a> <span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
		<a href='../b_coinordersell/member.php?b_statee=com&b_div_div=<?= $b_div_div ?>''><?= $c_coinname ?><?=M_SELL.M_DONE.M_HIS?> </a>
</div>
				<table width="1400" align="center" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

					<tr><td height=30></td></tr>
					<tr><td>
							<table width="100%" border=0 cellpadding=0 cellspacing=0>
								<tr>
									<td class=' td14' align="center"><?= $c_coinname ?><? if ($b_statee != "com") { ?><?=M_SELL.M_ORDER.M_HIS?><? } else { ?><?=M_SELL.M_DONE.M_HIS?><? } ?></td>
			<td align="right">
				<form name=dform action="./member_dis_excel.php" method=post target="_blank">
					<input type="hidden" name="level_l" value="<?= $level_l ?>">
					<? $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d")); ?>
					<input type="hidden" name="file_name" value="<?= $file_name ?>">
					<input type="hidden" name="dis" value="<?= $dis ?>">
					<input type="hidden" name="member_count" value="<?= $member_count ?>">
					<input type="hidden" name="b_div_div" value="<?= $b_div_div ?>">
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
						<table width="1200" border="0" cellspacing="0" cellpadding="4">
							<tr>

								<td height="20" align="left">
									<select name="pay_type">
										<option value="999"><?=M_ALL?></option>
										<?
										foreach ($DEFINE_MARKET as  $item) {
											$selected = ($payInfo && $payInfo->name == $item ? "selected" : "");
											echo "<option value=$item $selected>$item</option>";
										}

										/*
										foreach ($coins as $coin) {
											if ($coin[0] > 4) continue;
											?>
										<option value="<? echo $coin[0] ?>" <? if ($pay_type == $coin[0]) {
																					echo "selected";
																				} ?>><? echo $coin[1]; ?></option>
										<?
										}
										*/
										?>
									</select>
									&nbsp;
									<select name="keyfield">
										<option value="b_id" <? if ($keyfield == 'b_id') {
																	echo ("selected");
																}
																?>><?=M_ID?></option>
										<option value="b_orderprice" <? if ($keyfield == 'b_orderprice') {
																			echo ("selected");
																		}
																		?>><?=M_ORDER.M_PRICE1?></option>
									</select>
									<input type="text" name="key" value="<?= $key ?>" size="16" maxlength="16" class="adminbttn">
									<input type="hidden" name="b_div_div" value="<?= $b_div_div ?>">
									<input type="button" value="검색" class="adminbttn" onClick="javascript:go_search()">
								</td>
							</tr>
						</table>
						<table width="1200" border='0' align="center" cellspacing='0' cellpadding='0' class="sub_title">
							<tr>
								<td colspan=14 height=3 bgcolor='#ffffff'></td>
							</tr>
							<tr align="center" bgcolor='#ffffff' >
								<td width="50" height="30"><?=M_NO?></td>
								<td width="30" height="30"><?=M_STATUS?></td>
								<td width="100" height="30"><?=M_PAYMENT?></td>
								<td width="100" height="30"><?=M_ORDER.M_PRICE1?></td>
								<td width="150" height="30"><?=M_ORDER.M_TOTAL.M_PRICE?>(<?=M_FEE?>) </td>
								<td width="120" height="30"><?=M_ID?>(<?=M_NO?>)</td>
								<td width="100" height="30"><?=M_ORDER_COST?></td>
								<td width="90" height="30"><?=M_CLOSED.M_PRICE1?></td>
								<td width="90" height="30"><?=M_CLOSED.M_AMOUNT?></td>
								<td width="90" height="30"><?=M_CLOSED.M_TOTAL.M_PRICE?></td>
								<td width="50" height="30"><?=M_FEE?></td>
								<td width="90" height="30"><?=M_SIGN_DATE?></td>
								<td width="90" height="30"><?=M_IP?></td>
								<td width="90" height="30"><?=M_CLOSED.M_TIME?></td>
							</tr>
							<tr>
								<td colspan=14 height=1 bgcolor='#D2DEE8'></td>
							</tr>
							<tr>
								<td colspan=14 height=3></td>
							</tr>
							<?
							#####################################################################
							$i = 0;
							$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
							$stmt = pdo_excute("select", $query_pdo, [$b_div_div]);
							$digit = 8;
							while ($row = $stmt->fetch()) {

								$b_no = $row[0];
								$b_state = $row[1];
								$b_ordermost = numberformat($row[2], "money3", $digit);
								$b_closecost = numberformat($row[3], "money3", $digit);
								$b_closefees = numberformat($row[4], "money3", $digit);
								$b_orderprice = numberformat($row[5], "money3", $digit);
								$b_pricetotal = numberformat($row[6], "money3", $digit);
								$b_closeprice = numberformat($row[7], "money3", $digit);
								$b_closetotal = numberformat($row[8], "money3", $digit);
								$b_userno = $row[9];
								$b_id = $row[10];
								$b_signdate = $row[11];
								$b_ip = $row[12];
								$b_delete = $row[13];
								$b_orderfees = numberformat($row[14], "money3", $digit);
								$b_closedate = $row[15];
								$b_pay = $row[16];
								$b_market = $row[17];

								if ($b_market == "2") {
									$b_orderprice = $b_closeprice;
									$b_pricetotal = $b_closetotal;

									$close_cost = $row[3] + 0;
									if ($close_cost  == 0) continue;
								}
								//$payCoin = new CoinInfo($b_pay);
								foreach ($coins as $coin) {
									if ($coin[0] == $b_pay) {
										$pay_name = $coin[1];
										break;
									}
								}

								if ($b_state == "wait") {
									$b_state = M_WAIT;
								} else if ($b_state == "com") {
									$b_state = M_CLOSED;
								} else {
									$b_state = M_PART;
								}
								if ($m_block == "0") {
									$m_block = M_APPLY_NO;
								} else {
									$m_block = M_APPLY_YES;
								}
								$b_signdate = date("Y-m-d H:i:s", $b_signdate);
								if ($b_closedate == "0") $b_closedate = "";
								else $b_closedate = date("Y-m-d H:i:s", $b_closedate);

								if (($i + 1) % 2 == 0) {
									$kk_bgcolor = "#FFFFFF";
								} else {
									$kk_bgcolor = "#F6F6F6";
								}

								if ($b_delete == "1") {
									$kk_bgcolor2 = "#e2e2e2";
									if ($b_closetotal != "0")
										$b_state = M_PART;
									else {
										$b_state = $b_market == "2" ? M_TRADE1 : M_DEL;
									}
								} else {
									$kk_bgcolor2 = "#ffffff";
								}

								#####################################################################
								?>
							<tr align="center" bgcolor="<?= $kk_bgcolor2 ?>">
								<td height="30"><?= $b_no ?></td>
								<td height="30" style="text-decoration:underline;"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&b_no=<?= $b_no ?>"><?= $b_state ?></a></td>
								<td height="30"><?= $pay_name ?></td>
								<td height="30" style="text-decoration:underline;"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&b_no=<?= $b_no ?>"><B><?= ($b_market == "2" ? M_TRADE1 : $b_orderprice) ?></B></a></td>
								<td height="30" align="center"><?= $b_pricetotal ?>(<?= $b_orderfees ?>)</td>
								<td height="30" align="center"><?= $b_id ?>(<?= $b_userno ?>)</td>
								<td height="30"><?= $b_ordermost ?></td>
								<td height="30"><?= $b_closeprice ?></td>
								<td height="30"><?= $b_closecost ?></td>
								<td height="30"><?= $b_closetotal ?></td>
								<td><?= $b_closefees ?></td>
								<td><?= $b_signdate ?></td>
								<td><?= $b_ip ?></td>
								<td><?= $b_closedate ?></td>
							</tr>
							<tr>
								<td colspan=14 height=1 bgcolor='#D2DEE8'></td>
							</tr>

							<?
								$article_num--;
								$i++;
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