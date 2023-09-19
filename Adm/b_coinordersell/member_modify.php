<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_order.php";

if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}
if (isset($_REQUEST["statee"])) {
	$statee = sqlfilter($_REQUEST["statee"]);
} else {
	$statee = "";
}
if (isset($_REQUEST["b_no"])) {
	$b_no = sqlfilter($_REQUEST["b_no"]);
} else {
	$b_no = "";
}
if (isset($_REQUEST["real_pass"])) {
	$real_pass = sqlfilter($_REQUEST["real_pass"]);
} else {
	$real_pass = "";
}
if (isset($_REQUEST["b_div_div"])) {
	$b_div_div = sqlfilter($_REQUEST["b_div_div"]);
} else {
	$b_div_div = "";
}
if (isset($_REQUEST["b_market"])) {
	$b_market = sqlfilter($_REQUEST["b_market"]);
} else {
	$b_market = "";
}
if (isset($_REQUEST["div"])) {
	$div = $_REQUEST["div"];
} else {
	$div = "";
}
if ($div == "1") {
	$table_ordersell = $table_ordersell;
} else if ($div == "2") {
	$table_ordersell = $table_ordersell_mal;
}

$query_pdo = "SELECT b_no,b_state,b_ordermost,b_orderfees,b_closecost,b_closefees,b_orderprice,b_pricetotal,b_closeprice,b_closetotal,b_no1,b_userno,b_id,b_closedate,b_delete,b_ip,b_signdate,b_div,b_pay FROM $table_ordersell WHERE b_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($b_no));
$row = $stmt->fetch();

if (!$row) {
	error("QUERY_ERROR");
	exit;
}

$b_no = $row[0];
$b_state = $row[1];
$b_ordermost = $row[2];
$b_orderfees = $row[3];
$b_closecost = (is_empty($row[4]) ? "0" : $row[4]);
$b_closefees = $row[5];
$b_orderprice = $row[6];
$b_pricetotal = $row[7];
$b_closeprice = $row[8];
$b_closetotal = $row[9];
$b_no1 = $row[10];
$b_userno = $row[11];
$b_id = $row[12];
$b_closedate = $row[13];
$b_delete = $row[14];
$b_ip = $row[15];
$b_signdate = $row[16];
$b_div = $row[17];
$b_pay = $row[18];


####코인 확인###########
$b_div_div = sqlfilter($_REQUEST["b_div_div"]);
$query_div_pdo = "SELECT c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup WHERE c_no=? ";
$stmt = $pdo->prepare($query_div_pdo);
$stmt->execute(array($b_div_div));
$row_div = $stmt->fetch();

$c_coinname = $row_div["1"];

?>

<script language="javascript">
	function go_modify() {
		document.form.action = "member_modify_ok.php";
		document.form.submit();
	}

	function go_cancel() {
		document.form.action = "member_cancel.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "member.php";
		document.form.submit();
	}
</script>
<div style="background-color:#eee; height:25px; width:100%; line-height: 2;text-align:left;padding-left:10%;">
	<a href='../b_coinorderbuy/member.php?b_div_div=<?= $b_div_div ?>' style="margin-right:10px;"><?= $c_coinname ?><?=M_BUY.M_ORDER.M_HIS?></a>
	<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
	<a href='../b_coinordersell/member.php?b_div_div=<?= $b_div_div ?>'' style="margin-right:10px;"><?= $c_coinname ?><?=M_SELL.M_ORDER.M_HIS?></a>
<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
<a href=' ../b_coinorderbuy/member.php?b_statee=com&b_div_div=<?= $b_div_div ?>''><?= $c_coinname ?><?=M_BUY.M_DONE.M_HIS?> </a> <span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
		<a href='../b_coinordersell/member.php?b_statee=com&b_div_div=<?= $b_div_div ?>''><?= $c_coinname ?><?=M_BUY.M_DONE.M_HIS?></a>
</div>
				<table width="700" border="0" cellspacing="0" cellpadding="0">
					<tr><td height=30></td></tr>
<table width="1100"  border="0" cellspacing="0" cellpadding="0">
					<tr><td height=30></td></tr>
					<tr>
						<td>
							<table width="1000" align=center border=' 0' cellspacing='0' cellpadding='0'>
			<form name="form" method="post">
				<tr>
					<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_SELL.M_ORDER.M_HIS?>(<?= $b_no ?>)</b></td>
				</tr>
				<tr>
					<td colspan=4 height=2 bgcolor='#88B7DA'></td>
				</tr>
				<tr>
					<td colspan=4 height=5></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_STATUS?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<select name="b_state">
							<option value="wait" <? if ($b_state == "wait") { ?> selected <? } ?>><?=M_WAIT?></option>
							<option value="part" <? if ($b_state == "part") { ?> selected <? } ?>><?=M_PART?></option>
							<option value="com" <? if ($b_state == "com") { ?> selected <? } ?>><?=M_CLOSED?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_COIN.M_DIVISION?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<select name="b_div">
							<?
							$query_pdo2 = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
							$query_pdo2 = $query_pdo2 . "where c_use='1' ORDER BY c_rank+0 asc";
							$stmt = $pdo->prepare($query_pdo2);
							$stmt->execute();
							$result_coin_pdo = $stmt->fetch();
							$total_record_coin_pdo = $stmt->rowCount();
							?>
							<? for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
								$stmt = $pdo->prepare($query_pdo2);
								$stmt->execute();
								$row = $stmt->fetchAll();
								$c_no = $row[$ki][0];
								$c_coin = $row[$ki][1];
								?>
							<option value=<?= $c_no ?> <? if ($b_div == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_SELL.M_COIN?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<select name="b_pay">
							<?
							$query_pdo9 = "SELECT c_no,c_coin FROM $table_setup where c_use='1' AND c_no in (0,1,2,3) AND c_no <> '" . $b_div . "' ORDER BY c_no asc";
							$stmt9 = $pdo->prepare($query_pdo9);
							$stmt9->execute();
							while ($row9 = $stmt9->fetch()) {
								$c_no = $row9[0];
								$c_coin = $row9[1];
								?>
							<option value=<?= $c_no ?> <? if ($b_pay == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font face="돋움" size="2"><?= $c_coinname ?><?=M_ORDER.M_AMOUNT?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						&nbsp;
						<input type="text" maxlength=30 name="b_ordermost" value="<?= $b_ordermost ?>" size=30 class="adminbttn">
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_ORDER.M_FEE?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						&nbsp;
						<input type="text" maxlength=30 name="b_orderfees" value="<?= $b_orderfees ?>" size=30 class="adminbttn">
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_CLOSED.M_AMOUNT?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						&nbsp;
						<input name="b_closecost" value="<?= $b_closecost ?>" size=30 class="adminbttn">
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=115 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_FEE?></font>
						</div>
					</td>

					<td height="30" colspan="3" align="left">
						&nbsp;
						<input name="b_closefees" value="<?= $b_closefees ?>" size=30 class="adminbttn">
					</td>
					</td>
				</tr>

				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_ORDER.M_PRICE1?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<input maxlength=50 name="b_orderprice" value="<?= $b_orderprice ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_ORDER.M_TOTAL.M_PRICE?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<input maxlength=50 name="b_pricetotal" value="<?= $b_pricetotal ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_CLOSED.M_PRICE1?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<input maxlength=50 name="b_closeprice" value="<?= $b_closeprice ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_CLOSED.M_TOTAL.M_PRICE?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<input maxlength=50 name="b_closetotal" value="<?= $b_closetotal ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_CLOSED.M_NO?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<input maxlength=50 name="b_no1" value="<?= $b_no1 ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_MEMBER.M_NO?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움" align="left">
							&nbsp;
							<input maxlength=50 name="b_userno" value="<?= $b_userno ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_ID?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움" align="left">
							&nbsp;
							<input maxlength=50 name="b_id" value="<?= $b_id ?>" size="25" class="adminbttn">
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>
				<tr>
					<td width=105 height="30">
						<div align="center">
							<font size="2" face="돋움"><?=M_DEL?></font>
						</div>
					</td>
					<td height="30" colspan="3" align="left">
						<font size="2" face="돋움" align="left">
							&nbsp;
							<input type="radio" name="b_delete" <? if ($b_delete == "0") { ?>checked<? } ?> value="0">N
							<input type="radio" name="b_delete" <? if ($b_delete == "1") { ?>checked<? } ?> value="1">Y
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
				</tr>

				</table>
				</td>
				</tr>
				<input type="hidden" name="b_no" value="<?=$b_no ?>">
				<input type="hidden" name="real_pass" value="<?=$real_pass?>">
				<input type="hidden" name="keyfield" value="<?=$keyfield ?>">
				<input type="hidden" name="key" value="<?=$key ?>">
				<input type="hidden" name="page" value="<?=$page?>">
				<input type="hidden" name="b_market" value="<?=$b_market?>">
				<input type="hidden" name="b_div_div" value="<?=$b_div_div?>">
			</form>
			</table>
			<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td height="20" align="center">
						<? if (check_manager_level2($adminlevel, ADMIN_LVL4)) { ?>
						<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">&nbsp;
						<? } ?>
						<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">&nbsp;
						<? if (check_manager_level2($adminlevel, ADMIN_LVL4)) { ?>
						<input type="button" value="<?=M_ORDER.M_CANCEL?>" class="adminbttn" onClick="javascript:go_cancel()">
						<? } ?>
					</td>
				</tr>
			</table>
			<br><br>

			<BR><BR>

			<? include "../inc/down_menu.php"; ?>