<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";
?>

<script language="javascript">
	function go_modify() {
		if (!document.form.c_coin.value) {
			document.form.c_coin.focus();
			alert('코인 구분을 입력하세요');
			return false;
		}
		if (!document.form.c_wcommission.value) {
			document.form.c_wcommission.focus();
			alert('대기주문수수료를 입력하세요');
			return false;
		}
		if (!document.form.c_limit.value) {
			document.form.c_limit.focus();
			alert('주문량을 입력하세요');
			return false;
		}
		if (!document.form.c_asklimit.value) {
			document.form.c_asklimit.focus();
			alert('최대 주문량을 입력하세요');
			return false;
		}
		if (!document.form.c_unit.value) {
			document.form.c_unit.focus();
			alert('호가를 입력하세요');
			return false;
		}
		if (!document.form.c_rank.value) {
			document.form.c_rank.focus();
			alert('정렬순위를 입력하세요');
			return false;
		}
		if (!document.form.c_title.value) {
			document.form.c_title.focus();
			alert('코인타이틀을 입력하세요');
			return false;
		}
		if (!document.form.c_limit_in.value) {
			document.form.c_limit_in.focus();
			alert('최소입금수량을 입력하세요');
			return false;
		}
		if (!document.form.c_limit_out.value) {
			document.form.c_limit_out.focus();
			alert('최소출금수량을 입력하세요');
			return false;
		}


		document.form.action = "member_ok.php";
		document.form.submit();
	}

	function go_list() {
		location = "member.php?K_dis=<?= $K_dis ?>";
	}

	function open_addr(url) {
		window.open(url, "window", "width=350,height=230,toolbar=no,location=no,directorys=no,status=no,menubar=no,scrollbars=yes,resizable=no,left=100,top=100")
	}

	//-->
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=30></td>
	</tr>

	<tr>
		<td>

			<table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post" enctype="multipart/form-data">
					<tr>
						<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_OP_EXCHANGE?></b></td>
					</tr>
					<tr>
						<td colspan=4 height=2 bgcolor='#88B7DA'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_COIN?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" class="adminbttn" name="c_coin" size="30" value="">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ORDER.M_FEE?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_wcommission" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_MIN.M_ORDER.M_AMOUNT?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_limit" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_MAX.M_ORDER.M_AMOUNT?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_asklimit" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ASKPRICE?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_unit" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_RANK?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_rank" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_COIN.M_NAME?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_title" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_WITHDRAW.M_FEE?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_fees" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_DEPOSIT.M_MIN.M_AMOUNT?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_limit_in" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_WITHDRAW.M_MIN.M_AMOUNT?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_limit_out" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ISSUE.M_YEAR?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_since" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_TOTAL.M_ISSUE.M_AMOUNT?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_quantity" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_SITE?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_site" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_WHITEPAPTER?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_wpaper" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
		
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_INTRO?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<textarea name="c_introduce" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"></textarea>
							</font>
						</td>
					</tr>		
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_STATUS?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="radio" name="c_suspend_yn" value="0" checked><?=M_NORMAL?></input>
								<input type="radio" name="c_suspend_yn" value="1"><?=M_DEPWITH.M_STOP?></input>
								<input type="radio" name="c_suspend_yn" value="2"><?=M_TRADING.M_STOP?></input>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_REASON?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_suspend_reason" value="" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_COIN.M_TYPE?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="radio" name="c_type" value="0" checked><?=M_ALTCOIN?></input>
								<input type="radio" name="c_type" value="1"><?=M_ETH?></input>
								<input type="radio" name="c_type" value="2"><?=M_TOKEN?></input>
								<input type="radio" name="c_type" value="3"><?=M_RIPPLE?></input>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_APPLY?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="radio" name="c_use" value="0"><?=M_APPLY_NO?></input>
								<input type="radio" name="c_use" value="1" checked><?=M_APPLY_YES?></input>
							</font>
						</td>
					</tr>
			</table>
		</td>
	</tr>

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
			<input type="button" value="<?=M_REGISTRATION?>" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>

<? include "../inc/down_menu.php"; ?>