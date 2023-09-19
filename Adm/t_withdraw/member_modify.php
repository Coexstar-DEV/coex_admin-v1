<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../common/withdraw.php";
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


if (isset($_REQUEST["t_delete"])) {
	$t_delete = sqlfilter($_REQUEST["t_delete"]);
} else {
	$t_delete = "";
}
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_SESSION["admip"])) {
	$admip = $_SESSION["admip"];
} else {
	$admip = "";
}
if (isset($_REQUEST["t_no"])) {
	$t_no = sqlfilter($_REQUEST["t_no"]);
} else {
	$t_no = "";
}
if (isset($_POST["t_division"])) {
	$t_division = sqlfilter($_POST["t_division"]);
} else {
	$t_division = "";
}
if (isset($_POST["t_name"])) {
	$t_name = sqlfilter($_POST["t_name"]);
} else {
	$t_name = "";
}
if (isset($_POST["t_userno"])) {
	$t_userno = sqlfilter($_POST["t_userno"]);
} else {
	$t_userno = "";
}
if (isset($_POST["t_id"])) {
	$t_id = sqlfilter($_POST["t_id"]);
} else {
	$t_id = "";
}
if (isset($_POST["t_krw"])) {
	$t_krw = sqlfilter($_POST["t_krw"]);
} else {
	$t_krw = "";
}
if (isset($_POST["t_usekrw"])) {
	$t_usekrw = sqlfilter($_POST["t_usekrw"]);
} else {
	$t_usekrw = "";
}
if (isset($_POST["t_ordermost"])) {
	$t_ordermost = sqlfilter($_POST["t_ordermost"]);
} else {
	$t_ordermost = "";
}
if (isset($_POST["t_depositmost"])) {
	$t_depositmost = sqlfilter($_POST["t_depositmost"]);
} else {
	$t_depositmost = "";
}
if (isset($_POST["t_orderkrw"])) {
	$t_orderkrw = sqlfilter($_POST["t_orderkrw"]);
} else {
	$t_orderkrw = "";
}
if (isset($_POST["t_depositkrw"])) {
	$t_depositkrw = sqlfilter($_POST["t_depositkrw"]);
} else {
	$t_depositkrw = "";
}
if (isset($_POST["t_check"])) {
	$t_check = sqlfilter($_POST["t_check"]);
} else {
	$t_check = "";
}
if (isset($_POST["t_cont"])) {
	$t_cont = sqlfilter($_POST["t_cont"]);
} else {
	$t_cont = "";
}
if (isset($_POST["t_email"])) {
	$t_email = sqlfilter($_POST["t_email"]);
} else {
	$t_email = "";
}
if (isset($_POST["t_fees"])) {
	$t_fees = sqlfilter($_POST["t_fees"]);
} else {
	$t_fees = "";
}
if (isset($_POST["t_address"])) {
	$t_address = sqlfilter($_POST["t_address"]);
} else {
	$t_address = "";
}
if (isset($_POST["t_acount"])) {
	$t_acount = sqlfilter($_POST["t_acount"]);
} else {
	$t_acount = "";
}
if (isset($_POST["t_bankname"])) {
	$t_bankname = sqlfilter($_POST["t_bankname"]);
} else {
	$t_bankname = "";
}
if (isset($_POST["t_ordername"])) {
	$t_ordername = sqlfilter($_POST["t_ordername"]);
} else {
	$t_ordername = "";
}
if (isset($_REQUEST["t_check_old"])) {
	$t_check_old = sqlfilter($_REQUEST["t_check_old"]);
} else {
	$t_check_old = "";
}
if (isset($_REQUEST["krw"])) {
	$krw = sqlfilter($_REQUEST["krw"]);
} else {
	$krw = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}


$query_pdo = "SELECT t_no,t_division,t_name,t_userno,t_id,t_krw,t_usekrw,t_ordermost,t_depositmost,t_orderkrw,t_depositkrw,t_check,t_cont,t_email,t_fees,t_address,t_acount,t_bankname,t_ordername,t_reciveid,t_delete,t_signdate,t_ip,t_dest_tag FROM $table_withdraw WHERE t_no=? ";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($t_no));
$row = $stmt->fetch();

if (!$row) {
	error("QUERY_ERROR");
	exit;
}

$t_no = $row[0];
$t_division = $row[1];
$t_name = $row[2];
$t_userno = $row[3];
$t_id = $row[4];
$t_krw = $row[5];
$t_usekrw = $row[6];
$t_ordermost = $row[7];
$t_depositmost = $row[8];
$t_orderkrw = $row[9];
$t_depositkrw = $row[10];
$t_check = $row[11];
$t_cont = $row[12];
$t_email = $row[13];
$t_fees = $row[14];

err_log("===========>cont:$t_cont");
str_replace("\"", "'", $t_cont);

$t_address = $row[15];
$t_acount = $row[16];
$t_bankname = $row[17];
$t_ordername = $row[18];
$t_reciveid = $row[19];
$t_delete = $row[20];
$t_signdate = $row[21];
$t_ip = $row[22];
$t_dest_tag = $row[23];

$t_closemost = bcsub($t_ordermost, $t_fees, 8);

?>

<script language="javascript">
	function go_modify() {
		if (confirm('<?=M_CONFIRM_MSG2?>')) {
			document.form.action = "member_modify_ok.php";
			document.form.submit();
		}
	}

	function go_list() {
		document.form.action = "member_coin.php";
		document.form.submit();
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
							<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b> <?=M_TOTAL.M_WITHDRAW.M_HIS ?></b></td>
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
									<font size="2" face="돋움"><?=M_COIN?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								<select name="t_division">
									<?
									$query_pdo2 = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
									$query_pdo2 = $query_pdo2 . "where c_use='1' ORDER BY c_rank+0 asc";
									$stmt = $pdo->prepare($query_pdo2);
									$stmt->execute();
									$result_pdo = $stmt->fetch();

									if (!$result_pdo) {
										error("QUERY_ERROR");
										exit;
									}
									$total_record_coin_pdo = $stmt->rowCount();
									?>
									<? for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
										$stmt = $pdo->prepare($query_pdo2);
										$stmt->execute();
										$row = $stmt->fetchAll();
										$c_no = $row[$ki]["0"];
										$c_coin = $row[$ki]["1"];
										?>
									<option value=<?= $c_no ?> <? if ($t_division == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
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
									<font face="돋움" size="2"><?=M_NAME?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input readonly="readonly" type="text" maxlength=30 name="t_name" value="<?= $t_name ?>" size=30 class="adminbttn">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=105 height="30">
								<div align="center">
									<font face="돋움" size="2"><?=M_ID?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input readonly="readonly" type="text" maxlength=30 name="t_id" value="<?= $t_id ?>" size=30 class="adminbttn">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=105 height="30">
								<div align="center">
									<font face="돋움" size="2"><?=M_NO?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input readonly="readonly" type="text" maxlength=30 name="t_userno" value="<?= $t_userno ?>" size=30 class="adminbttn" readonly />
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=115 height="30">
								<div align="center">
									<font size="2" face="돋움"><?=M_ORDER_COST?></font>
								</div>
							</td>

							<td height="30" colspan="3" align="left">
								&nbsp;
								<input readonly="readonly" name="t_ordermost" value="<?= $t_ordermost ?>" size=30 class="adminbttn">
							</td>
			</td>
		</tr>

		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_CLOSE_COST?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input readonly="readonly" maxlength=50 name="t_depositmost" value="<?= numberformat($t_closemost, "money2", 8) ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDERER.M_EMAIL?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input readonly="readonly" maxlength=50 name="k_email" value="<?= $k_email ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_PAYMENT.M_STATUS?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input type="radio" name="t_check" value="0" <? if ($t_check == "0") { ?>checked<? } ?>><?=M_PAY_NO?>
					<input type="radio" name="t_check" value="1" <? if ($t_check == "1") { ?>checked<? } ?>><?=M_PAY_YES?>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_WITHDRAW.M_RESULT.M_MSG?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_cont" value="<?= $t_cont ?>" size="120" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_MAIL_AUTH?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input type="radio" name="t_email" value="0" <? if ($t_email == "0") { ?>checked<? } ?>><?=M_NOT_CONFIRM?>
					<input type="radio" name="t_email" value="1" <? if ($t_email == "0") { ?>checked<? } ?>><?=M_CONFIRM?>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td height="30" colspan="3" align="left">
				<div align="center">
					<font size="2" face="돋움"><?=M_WITHDRAW.M_FEE?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					<input readonly="readonly" maxlength=50 name="t_fees" value="<?= $t_fees ?>" size="120" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_WALLET.M_ADDRESS?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_address" value="<?= $t_address ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_RECV?> Tag</font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_dest_tag" value="<?= $t_dest_tag ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ACCOUNT?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_acount" value="<?= $t_acount ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_BANK?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_bankname" value="<?= $t_bankname ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDERER?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_ordername" value="<?= $t_ordername ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_DEL.M_STATUS?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input type="radio" name="t_delete" value="0" <? if ($t_delete == "0") { ?>checked<? } ?>><?=M_PENDING?>
					<input type="radio" name="t_delete" value="1" <? if ($t_delete == "1") { ?>checked<? } ?>><?=M_DEL?>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_RECVER?>ID</font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input readonly="readonly" maxlength=50 name="t_reciveid" value="<?= $t_reciveid ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>


	</table>
	</td>
	</tr>
	<? $krw = $_GET["krw"]; ?>
	<input type="hidden" name="krw" value="<?= $krw ?>">
	<input type="hidden" name="t_check_old" value="<?= $t_check ?>">
	<input type="hidden" name="t_id" value="<? echo ($t_id) ?>">
	<input type="hidden" name="t_no" value="<? echo ($t_no) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="center">
			<? if ($t_delete != "1" && check_manager_level2($adminlevel, ADMIN_LVL3)) { ?>
			<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">
			<? } ?>
			<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">
		</td>
	</tr>
</table>
<br><br>

<BR><BR>

<? include "../inc/down_menu.php"; ?>