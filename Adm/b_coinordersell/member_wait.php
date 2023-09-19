<?php
@session_start();
include_once "../common/user_function.php";
include_once "../common/trading.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_order.php";

if (isset($_REQUEST["b_div_div"])) {
	$b_div_div = $_REQUEST["b_div_div"];
} else {
	$b_div_div = "";
}

if (isset($_REQUEST["b_market"])) {
	$b_market = $_REQUEST["b_market"];
} else {
	$b_market = "";
}


if (isset($_REQUEST["b_statee"])) {
	$b_statee = $_REQUEST["b_statee"];
} else {
	$b_statee = "";
}

if (isset($_REQUEST["key"])) {
	$key = trim($_REQUEST["key"]);
} else {
	$key = "";
}

if (isset($_REQUEST["keyfield"])) {
	$keyfield = $_REQUEST["keyfield"];
} else {
	$keyfield = "";
}


$pdo_in = [];

// 코인 이름 
$c_coinname = "";
if (!is_empty($b_div_div)) {
	$coinInfo = new CoinInfo($b_div_div);
	$c_coinname = $coinInfo->name;
	$cond_div = " and b_div = ?";
	$pdo_in[] = $b_div_div;
	err_log("b_div_div==> $b_div_div, name:$coinInfo->name");
}

if (!is_empty($b_market)) {
	$payInfo = new CoinInfo($b_market);
	$c_market = $payInfo->name;
	$cond_pay = " and b_pay = ?";
	$pdo_in[] = $payInfo->type;
	err_log("b_pay==> $payInfo->type, name:$payInfo->name");
}

$encoded_key = urlencode($key);


$query_pdo = "SELECT b_no,b_state,b_ordermost,b_closecost,b_closefees,b_orderprice,b_pricetotal,b_closeprice,b_closetotal,b_userno,b_id,b_signdate,b_ip,b_delete,b_closedate,b_orderfees,b_div,b_pay  FROM $table_ordersell where b_state <> 'com' and b_delete <> '1' $cond_div $cond_pay ";
if ($key != "") {
	$query_pdo .= " and $keyfield LIKE '%$key%' ";
}

$query_pdo .= " ORDER BY b_signdate DESC";

$total_record_pdo = pdo_excute_count("count", $query_pdo, $pdo_in);

if ($_REQUEST["page"] != "") {
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
$mode = "keyfield=$keyfield&key=$encoded_key&b_statee=$b_statee&b_div_div=$b_div_div&b_market=$b_market";

#####################################################################
?>

<script language="javascript">
	<!--
	function go_cancel_all() {
		ans = confirm('정말로 일괄취소 하시겠습니까?');
		if (ans == true) {
			document.form.action = "member_cancel_all.php";
			document.form.submit();
		}
	}

	function go_del() {
		ans = confirm('정말로 삭제하시겠습니까?');
		if (ans == true) {
			document.form.action = "member_del.php";
			document.form.submit();
		}
	}

	function go_status(kk) {
		ans = confirm('정말로 변경하시겠습니까?');
		if (ans == true) {
			document.form.action = kk;
			document.form.submit();
		}
	}

	function go_search() {
		document.form.action = "member_wait.php?b_statee=<?= $b_statee ?>";
		document.form.submit();
	}

	function go_mail(tmp_mail) {
		document.location = "mailing.php?to_name=" + tmp_mail;
	}
	//
	-->
</script>
<div style="background-color:#eeeeee; height:25px;line-height: 2;text-align:left;padding-left:10%;">
	<a href='../b_coinorderbuy/member_wait.php?b_div_div=<?= $b_div_div ?>&b_market=<?= $b_market ?>' style="margin-right:10px;"><?=M_BUY.M_ORDER_WAIT?></a>
	<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
	<a href='../b_coinordersell/member_wait.php?b_div_div=<?= $b_div_div ?>&b_market=<?= $b_market ?>' style="margin-right:10px;"><?=M_SELL.M_ORDER_WAIT?></a>
<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
</div>
				<table width="1200" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

					<tr><td height=30></td></tr>
					<tr><td>
							<table width="100%" border=0 cellpadding=0 cellspacing=0>
								<tr>
									<td class=' td14' align="center">
		<? if ($b_statee != "com") { ?><?=M_SELL.M_ORDER_WAIT?>
		<? } else { ?><?=M_BUY.M_DONE.M_HIS?>
		<? } ?>
		</td>
		<td align="right">
			<form name=dform action="./member_dis_excel.php" method=post target="_blank">
				<input type="hidden" name="dis" value="<?= $dis ?>">
				<? $file_name = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); ?>
				<input type="hidden" name="file_name" value="<?= $file_name ?>">
				<input type="hidden" name="bb_statee" value="<?= $b_statee ?>">
				<input type="hidden" name="coin_name" value="<?= $c_coinname ?>">
				<input type="hidden" name="m_key" value="<?= $key ?>">
			</form>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<form name="form" method="post">
			<td height=3></td>
			</tr>
			<tr>
				<td>
					<table width="1370" border="0" cellspacing="0" cellpadding="4">
						<tr>

							<td height="20" align="left">
								<select name="b_div_div" onchange="go_search();">
									<option value="">COIN</option>

									<?

									$query_pdo_select = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
									$query_pdo_select .= " WHERE c_coin not in ('PHP','KRWC','USDT') ";
									$query_pdo_select .= " ORDER BY c_rank+0 asc";

									$stmt = pdo_excute("select1", $query_pdo_select, null);
									while ($row_coin = $stmt->fetch()) {
										$c_no = $row_coin["0"];
										$c_coin = $row_coin["1"];
										$coin_array[$c_no] = $c_coin;
										?>
									<option value=<?= $c_no ?> <? if ($b_div_div == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
									<? }
									err_log("===>list :" . var_export($coin_array, true))
									?>

								</select>
								&nbsp;&nbsp;
								<select name="b_market" onchange="go_search();">
									<option value="">마켓 선택</option>

									<?
									foreach( $DEFINE_MARKET as $market)  {
									?>
									<option value=<?= $market ?> <? if ($market == $b_market) { ?> selected <? } ?>><?= $market ?></option>
									<? }
									err_log("===>list :" . var_export($DEFINE_MARKET, true))
									?>

								</select>
								&nbsp;&nbsp;
								<select name="keyfield">
									<option value="b_id" <? if ($keyfield == 'b_id') {
																echo ("selected");
															} ?>><?=M_ID?></option>
								</select>
								<input type="text" name="key" value="<?= $key ?>" size="30" maxlength="30" class="adminbttn">
								<input type="button" value="검색" class="adminbttn" onClick="javascript:go_search()">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" value="일괄취소" class="adminbttn" onClick="javascript:go_cancel_all()">
								<!-- 											<input type="button"  value="주문등록" class="adminbttn" onClick="javscript:location.href='member_write.php'"> -->
							</td>
						</tr>
					</table>
					<table width="1370" border='0' cellspacing='0' cellpadding='0' class="sub_title">
						<tr>
							<td colspan=13 height=3 bgcolor='#ffffff'></td>
						</tr>
						<tr align="center" bgcolor='#ffffff'>
							<td width="40" height="30"><?=M_NO?></td>
							<td width="50" height="30"><?=M_STATUS?></td>
							<td width="50" height="30"><?=M_COIN?></td>
							<td width="100" height="30"><b><?=M_ORDER.M_PRICE1?></b></td>
							<td width="50" height="30"><?=M_COIN.M_AMOUNT?></td>
							<td width="200" height="30"><?=M_ORDER.M_TOTAL.M_PRICE?></td>
							<td width="120" height="30"><b><?=M_ID?>(<?=M_NO?>)</b></td>
							<td width="90" height="30"><?=M_CLOSED.M_PRICE1?></td>
							<td width="90" height="30"><?=M_CLOSED.M_AMOUNT?></td>
							<td width="90" height="30"><?=M_CLOSED.M_TOTAL.M_PRICE?></td>
							<td width="90" height="30"><?=M_FEE?></td>
							<td width="100" height="30"><?=M_SIGN_DATE?></td>
							<td width="90" height="30"><?=M_IP?></td>
							<td width="100" height="30"><?=M_CLOSED.M_TIME?></td>
						</tr>
						<tr>
							<td colspan=14 height=2 bgcolor='#D2DEE8'></td>
						</tr>
			</tr>
			<tr>
				<td colspan=14 height=3></td>
			</tr>
			<?
			#####################################################################
			$coin_list = array();
			$query_div_pdo = "SELECT c_no, c_coin FROM $table_setup";
			$stmt = pdo_excute("c_setup", $query_div_pdo, NULL);
			while ($row = $stmt->fetch()) {
				$coin_list[$row[0]] = $row[1];
			}

			$ii = 0;
			$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
			$stmt = pdo_excute("select", $query_pdo, $pdo_in);
			while ($row = $stmt->fetch()) {

				$b_no = $row[0];
				$b_state = $row[1];
				$b_ordermost = $row[2];
				$b_closecost = $row[3];
				$b_closefees = $row[4];
				$b_orderprice = $row[5];
				$b_pricetotal = $row[6];
				$b_closeprice = $row[7];
				$b_closetotal = $row[8];
				$b_userno = $row[9];
				$b_id = $row[10];
				$b_signdate = $row[11];
				$b_ip = $row[12];
				$b_delete = $row[13];
				$b_closedate = $row[14];
				$b_orderfees = $row[15];
				$b_div = $row[16];
				$b_pay = $row[17];

				$coinname = $coin_list[$b_div] . "/" . $coin_list[$b_pay];

				if ($b_state == "wait") {
					$b_state = M_WAIT;
					$b_closedate = "";
				} else if ($b_state == "com") {
					$b_state = M_CLOSED;
					if ($b_delete == "1")
						$b_state = M_PART;
				} else if ($b_state == "part") {
					$b_state = M_PART;
					if ($b_delete == "1")
						$b_state = M_PART;
				}

				$b_signdate = date("Y-m-d H:i:s", $b_signdate);
				if (!is_empty($b_closedate)) {
					$b_closedate = date("Y-m-d H:i:s", $b_closedate);
				};

				if (($ii + 1) % 2 == 0) {
					$kk_bgcolor = "#FFFFFF";
				} else {
					$kk_bgcolor = "#F6F6F6";
				}

				if ($b_delete == "1") {
					$kk_bgcolor2 = "#e2e2e2";
					if ($b_closecost == "0")
						$b_state = "삭제";
				} else {
					$kk_bgcolor2 = "#ffffff";
				}
				?>
			<tr align="center" bgcolor="<?= $kk_bgcolor2 ?>">
				<td height="30"><?= $article_num ?></td>
				<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&b_no=<?= $b_no ?>"><?= $b_state ?></td>
				<td height="30"><?= $coinname ?></td>
				<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&b_no=<?= $b_no ?>"><B><?= numberformat($b_orderprice, "money2", 8) ?></B> </a> </td>
				<td height="30"><?= numberformat(round($b_ordermost, 4), "money2", 8) ?></td>
				<td height="30" align="center"><?= numberformat($b_pricetotal, "money2", 8) ?>(<?= numberformat($b_orderfees, "money2", 8) ?>)</td>
				<td height="30" align="center"><?= $b_id ?>(<?= $b_userno ?>)</td>
				<td height="30"><?= numberformat($b_closeprice, "money2", 8) ?></td>
				<td height="30"><?= numberformat(round($b_closecost, 4), "money2", 8) ?></td>
				<td height="30"><?= numberformat(round($b_closetotal, 4), "money2", 8) ?></td>
				<td><?= numberformat($b_closefees, "money2", 8) ?></td>
				<td><?= $b_signdate ?></td>
				<td><?= $b_ip ?></td>
				<td><?= $b_closedate ?></td>
			</tr>
			<tr>
				<td colspan=14 height=1 bgcolor='#D2DEE8'></td>
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
								echo "<a href=\"member_wait.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">처음</a>&nbsp;";
							}
							if ($page > 1) {
								$page_num = $page - 1;
								echo "<a href=\"member_wait.php?$mode&page=$page_num\" onMouseOver=\"status='이전페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
							}

							for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
								if ($page == $direct_page) {
									echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
								} else {
									echo "&nbsp;<a href=\"member_wait.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
								}
							}


							if ($IsNext > 0) {
								$page_num = $page + 1;
								echo "&nbsp;<a href=\"member_wait.php?$mode&page=$page_num\" onMouseOver=\"status='다음페이지';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
							}
							if ($page != $total_page) {
								echo "<a href=\"member_wait.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">마지막</a>";
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