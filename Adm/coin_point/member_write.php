<?
session_start();
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/top_menu.php";
include "../inc/left_menu_order.php";
?>

<script language="javascript">
	function go_modify() {
		document.form.action = "member_ok.php";
		document.form.submit();
	}
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post">
					<tr>
						<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b>포인트내역</b></td>
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
								<font size="2" face="돋움"><?=M_COIN.M_DIVISION?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<select name="c_div">
								<?
								$query_pdo = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
								$query_pdo .= " WHERE c_use='1' and c_basecoin = 0 ORDER BY c_rank+0 asc";
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
									$c_no = $row[$ki][0];
									$c_coin = $row[$ki][1];
									?>
								<option value=<?= $c_no ?> <? if ($c_div == $c_no) { ?> selected <? } ?>><?= $c_coin ?></option>
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
								<option value="formwallet" <? if ($c_category == "wait") { ?> checked <? } ?>><?=M_DEPOSIT?></option>
								<option value="reqorder" <? if ($c_category == "part") { ?> checked <? } ?>><?=M_WITHDRAW?></option>
								<option value="tradebuy" <? if ($c_category == "tradebuy") { ?> checked <? } ?>><?=M_BUY?></option>
								<option value="tradesell" <? if ($c_category == "tradesell") { ?> checked <? } ?>><?=M_SELL?></option>
								<option value="reqordersend" <? if ($c_category == "reqordersend") { ?> checked <? } ?>><?=M_TRANS?></option>
								<option value="reqorderrecv" <? if ($c_category == "reqorderrecv") { ?> checked <? } ?>><?=M_RECV?></option>
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
							<input type="text" maxlength=30 name="c_category2" value="<?= $c_category2 ?>" size=30 class="adminbttn">
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
								<font size="2" face="돋움">환전된금액</font>
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
				<font size="2" face="돋움">지불금액</font>
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
				<font size="2" face="돋움">환불</font>
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
				<font size="2" face="돋움">고유키</font>
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
			<input type="button" value="회원등록" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>

<? include "../inc/down_menu.php"; ?>