<?
#####################################################################

session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";

//k_no,k_orderprice,k_depositprice,k_sellprice,k_returnprice,k_depositname,k_payment,k_ordername,k_email,k_tel,k_ordermemo,k_check,k_duedate,k_checkdate,k_cardcancle,k_return,k_ip,k_delete,k_admmemo,k_modicont,k_signdate


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
if (isset($_REQUEST["real_pass"])) {
	$real_pass = sqlfilter($_REQUEST["real_pass"]);
} else {
	$real_pass = "";
}
if (isset($_REQUEST["k_checkk"])) {
	$k_checkk = sqlfilter($_REQUEST["k_checkk"]);
} else {
	$k_checkk = "";
}
if (isset($_REQUEST["ddate1"])) {
	$ddate1 = sqlfilter($_REQUEST["ddate1"]);
} else {
	$ddate1 = "";
}
if (isset($_REQUEST["mdate1"])) {
	$mdate1 = sqlfilter($_REQUEST["mdate1"]);
} else {
	$mdate1 = "";
}
if (isset($_REQUEST["ddate2"])) {
	$ddate2 = sqlfilter($_REQUEST["ddate2"]);
} else {
	$ddate2 = "";
}
if (isset($_REQUEST["mdate2"])) {
	$mdate2 = sqlfilter($_REQUEST["mdate2"]);
} else {
	$mdate2 = "";
}
if (isset($_REQUEST["ydate2"])) {
	$ydate2 = sqlfilter($_REQUEST["ydate2"]);
} else {
	$ydate2 = "";
}
if (isset($_REQUEST["k_no"])) {
	$k_no = sqlfilter($_REQUEST["k_no"]);
} else {
	$k_no = "";
}

$query_pdo = "SELECT k_no,k_orderprice,k_depositprice,k_sellprice,k_returnprice,k_depositname,k_payment,k_ordername,k_email,k_tel,k_ordermemo,k_check,k_duedate,k_checkdate,k_cardcancle,k_return,k_ip,k_delete,k_admmemo,k_modicont,k_signdate,k_userno,k_id FROM $table_k_deposit WHERE k_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($k_no));
$row = $stmt->fetch();

if (!$row) {
	error("QUERY_ERROR");
	exit;
}
$k_no = $row[0];
$k_orderprice = $row[1];
$k_depositprice = $row[2];
$k_sellprice = $row[3]; 
$k_returnprice = $row[4]; 
$k_depositname = $row[5];
$k_payment = $row[6]; 
$k_ordername = $row[7]; 
$k_email = $row[8]; 
$k_tel = $row[9]; 
$k_ordermemo = $row[10];  
$k_check = $row[11];
$k_duedate = $row[12]; 
$k_checkdate = $row[13];
$k_cardcancle = $row[14]; 
$k_return = $row[15]; 
$k_ip = $row[16];
$k_delete = $row[17];
$k_admmemo = $row[18]; 
$k_modicont = $row[19]; 
$k_signdate = $row[20]; 
$k_userno = $row[21];
$k_id = $row[22];

$k_signdate = date("Y-m-d H:i:s",$k_signdate);
if(!is_empty($k_duedate)) {
	$k_duedate = date("Y-m-d H:i:s",$k_duedate);
} 

?>
<script src="../js/jquery.number.min.js"></script>
<script language="javascript">
	function go_modify() {
		document.form.action = "member_modify_ok.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "member.php";
		document.form.submit();
	}
	$(document).ready(function() {
		//한개의 input 적용
		$("#number").val(function() {
			return $.number($(this).val());
		});

		$(".numberic").keyup(function() {
			$(this).val($.number($(this).val()));
		});
		//class로 묶어서 한거번에 적용

		$(".numberic").each(function() {
			$(this).val($.number($(this).val()));
		});

		//blur 적용
		$('#target').blur(function() {
			$(this).val($.number($(this).val()));
		});
	});
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
							<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_KRW.M_DEPOSIT.M_ORDER.M_HIS?></b></td>
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
									<font size="2" face="돋움"><?=M_PAY?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								<select name="k_payment">
									<option value="0" <? if ($k_payment == "0") { ?> checked <? } ?>><?=M_PAY_1?></option>
									<option value="1" <? if ($k_payment == "1") { ?> checked <? } ?>><?=M_PAY_2?></option>
									<option value="2" <? if ($k_payment == "2") { ?> checked <? } ?>><?=M_PAY_3?></option>
									<option value="3" <? if ($k_payment == "3") { ?> checked <? } ?>><?=M_PAY_4?></option>
								</select>
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
								<input type="text" maxlength=30 name="k_id" value="<?= $k_id ?>" size=30 class="adminbttn">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=105 height="30">
								<div align="center">
									<font face="돋움" size="2"><?=M_ORDER.M_PRICE?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input type="text" maxlength=30 name="k_orderprice" value="<?= $k_orderprice ?>" size=30 class="adminbttn numberic">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=105 height="30">
								<div align="center">
									<font size="2" face="돋움"><?=M_DEPOSIT.M_PRICE?></font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								&nbsp;
								<input type="text" maxlength=30 name="k_depositprice" value="<?= $k_depositprice ?>" size=30 class="adminbttn numberic">
							</td>
						</tr>
						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr>
							<td width=115 height="30">
								<div align="center">
									<font size="2" face="돋움"><?=M_REFUND?></font>
								</div>
							</td>

							<td height="30" colspan="3" align="left">
								&nbsp;
								<input name="k_returnprice" value="<?= $k_returnprice ?>" size=30 class="adminbttn">
							</td>
			</td>
		</tr>

		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_DEPOSITOR?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="k_depositname" value="<?= $k_depositname ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_PAYMENT?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input type="radio" name="k_check" value="0" <? if ($k_check == "0") { ?>checked<? } ?>><?=M_PAY_NO?><input type="radio" name="k_check" value="1" <? if ($k_check == "1") { ?>checked<? } ?>><?=M_PAY_YES?>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDERER.M_ID?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="k_ordername" value="<?= $k_ordername ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>

		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDERER.M_MAIL?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="k_email" value="<?= $k_email ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDERER.M_PHONE?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움">&nbsp;
					<input maxlength=50 name="k_tel" value="<?= $k_tel ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ORDER.M_MEMO?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input maxlength=50 name="k_ordermemo" value="<?= $k_ordermemo ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>

			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_PAYMENT.M_DATE?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input maxlength=50 name="k_duedate" value="<?= $k_duedate ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_DEL_STATUS?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input type="radio" name="k_delete" value="0" <? if ($k_delete == "0") { ?>checked<? } ?>><?=M_DEL_NO?>
					<input type="radio" name="k_delete" value="1" <? if ($k_delete == "1") { ?>checked<? } ?>><?=M_DEL_YES?>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_ADMIN_MEMO?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<input maxlength=50 name="k_admmemo" value="<?= $k_admmemo ?>" size="25" class="adminbttn">
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>
		<tr>
			<td width=105 height="30">
				<div align="center">
					<font size="2" face="돋움"><?=M_CONTENT?></font>
				</div>
			</td>
			<td height="30" colspan="3" align="left">
				<font size="2" face="돋움" align="left">
					&nbsp;
					<textarea name="k_modicont"><?= $k_modicont ?></textarea>
				</font>
			</td>
		</tr>
		<tr>
			<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
		</tr>

	</table>
	</td>
	</tr>
	<input type="hidden" name="k_delete_old" value="<?= $k_delete ?>">
	<input type="hidden" name="k_check_old" value="<?= $k_check ?>">
	<input type="hidden" name="k_no" value="<? echo ($k_no) ?>">
	<input type="hidden" name="k_checkk" value="<? echo ($k_checkk) ?>">
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
			<input type="button" value="정보변경" class="adminbttn" onClick="javascript:go_modify()">
			<? } ?>
			<input type="button" value="뒤로가기" class="adminbttn" onClick="javascript:go_list()">
			<? if (check_manager_level2($adminlevel, ADMIN_LVL3)) { ?>
			<? } ?>
		</td>
	</tr>
</table>
<br><br>

<BR><BR>

<? include "../inc/down_menu.php"; ?>