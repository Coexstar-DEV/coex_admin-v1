<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";
if (isset($_GET["m_adminid"])) {
	$m_adminid = $_GET["m_adminid"];
} else {
	$m_adminid = "";
}
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
if (isset($_REQUEST["k_checkk"])) {
	$k_checkk = sqlfilter($_REQUEST["k_checkk"]);
} else {
	$k_checkk = "";
}
if (isset($_REQUEST["c_div_div"])) {
	$c_div_div = sqlfilter($_REQUEST["c_div_div"]);
} else {
	$c_div_div = "";
}
if (isset($_REQUEST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
}

if (isset($_REQUEST["c_id"])) {
	$c_id = sqlfilter($_REQUEST["c_id"]);
} else {
	$c_id = "";
}


$query_pdo = "SELECT c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate FROM $table_point WHERE c_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($c_no));
$row = $stmt->fetch();

$c_no = $row[0];
$c_div = $row[1];
$c_userno = $row[2];
$c_id = $row[3];
$c_exchange = $row[4];
$c_payment = $row[5];
$c_category = $row[6];
$c_category2 = $row[7];
$c_ip = $row[8];
$c_return = $row[9];
$c_no1 = $row[10];
$c_no2 = $row[11];
$c_signdate = $row[12];

?>

<script language="javascript">
	function go_modify() {
		document.form.action = "member_modify_ok.php?c_category=<? $c_category ?>";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "member2.php";
		document.form.submit();
	}

	function go_del() {
		if (confirm("<?=M_CONFIRM_MSG1?>")) {
			document.form.action = "member_del.php?c_id=<?= $c_id ?>&c_category=<? $c_category ?>";
			document.form.submit();
		}
	}
</script>

<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=30></td>
	</tr>
	<table width="1100" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height=30></td>
		</tr>
		<tr>
			<td>
				<table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
					<form name="form" method="post">
						<tr>
							<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_TRADING.M_HIS?></b></td>
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
									<font size="2" face="돋움"><?=M_COIN?> NO</font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								<select name="c_div">
									<?

									$query_pdo = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
									$query_pdo .= "where c_use='1' and c_no<>'1' ORDER BY c_rank+0 asc";
									$stmt = $pdo->prepare($query_pdo);
									$stmt->execute();
									$result_coin_pdo = $stmt->fetch();
									if (!$result_coin_pdo) {
										error("QUERY_ERROR");
										exit;
									}
									$total_record_coin_pdo = $stmt->rowCount();
									?>
									<? for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
										$stmt = $pdo->prepare($query_pdo);
										$stmt->execute();
										$row = $stmt->fetchAll();
										$c_no2323 = $row[$ki][0];
										$c_coin = $row[$ki][0];
										?>
									<option value=<?= $c_no2323 ?> <? if ($c_div == $c_no2323) { ?> selected <? } ?>><?= $c_coin ?></option>
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
									<font size="2" face="돋움"><?=M_PAY?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">

								<select name="c_category">
									<option value="formwallet" <? if ($c_category == "formwallet") { ?> selected <? } ?>><?=M_DEPOSIT?></option>
									<option value="reqorder" <? if ($c_category == "reqorder") { ?> selected <? } ?>><?=M_WITHDRAW?></option>
									<option value="tradebuy" <? if ($c_category == "tradebuy") { ?> selected <? } ?>><?=M_BUY?></option>
									<option value="tradesell" <? if ($c_category == "tradesell") { ?> selected <? } ?>><?=M_SELL?></option>
									<option value="reqordersend" <? if ($c_category == "reqordersend") { ?> selected <? } ?>><?=M_TRANS?></option>
									<option value="reqorderrecv" <? if ($c_category == "reqorderrecv") { ?> selected <? } ?>><?=M_DEPOSIT?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=105 height="30">
								<div align="center">
									<font face="돋움" size="2"><?=M_CONTENT?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input type="text" maxlength=30 name="c_category2" value="<?= $c_category2 ?>" size=30 class="adminbttn" readonly disabled style="opacity:.7">
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
								&nbsp;
								<input type="text" maxlength=30 name="c_userno" value="<?= $c_userno ?>" size=30 class="adminbttn">
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
								&nbsp;
								<input name="c_id" value="<?= $c_id ?>" size=30 class="adminbttn">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=115 height="30">
								<div align="center">
									<font size="2" face="돋움"><?=M_EXCHANGE.M_PRICE?></font>
								</div>
							</td>

							<td height="30" colspan="3" align="left">
								&nbsp;
								<input name="c_exchange" value="<?= $c_exchange ?>" size=30 class="adminbttn">
							</td>
			</td>
		</tr>

		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_PAYMENT.M_PRICE?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="c_payment" value="<?= $c_payment ?>" size="25" class="adminbttn">
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
					<input type="text" name="c_return" value="<?= $c_return ?>">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_BUY.M_NO?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="c_no1" value="<?= $c_no1 ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_SELL.M_NO?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="c_no2" value="<?= $c_no2 ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
	</table>
	</td>
	</tr>
	<input type="hidden" name="c_no" value="<? echo ($c_no) ?>">
	<input type="hidden" name="real_pass" value="<? echo ($real_pass) ?>">
	<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	<input type="hidden" name="page" value="<? echo ($page) ?>">
	</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="center">
			<? if (check_manager_level2($adminlevel, ADMIN_LVL3)) { ?>
			<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">&nbsp;
			<? } ?>
			<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">&nbsp;
			<? if (check_manager_level2($adminlevel, ADMIN_LVL3)) { ?>
			<input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="go_del();">
			<? } ?>
		</td>
	</tr>
</table>
<br><br>
<BR><BR>

<? include "../inc/down_menu.php"; ?>