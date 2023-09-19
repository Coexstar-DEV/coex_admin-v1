<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

$b_div = $_REQUEST["b_div"];
?>

<script language="javascript">
	function go_modify() {
		document.form.action = "member_ok.php";
		document.form.submit();
	}
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=60></td>
	</tr>

	<tr>
		<td>

			<table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post">
				<tr>
						<td colspan=2> <h1><b>Add <?=M_LEVEL. '' .M_SETTING?>레벨별입출설정</b></h1></td>
					</tr>
					<tr>
						<td colspan=4 height=2 bgcolor='#ffd600'></td>
					</tr>
				
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="left">
								<font size="2" face="돋움"><?=M_COIN.M_DIVISION?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<select name="c_coin">
								<?
								$query_pdo = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
								$query_pdo .= " ORDER BY c_rank+0 asc";

								$stmt = $pdo->prepare($query_pdo);
								$stmt->execute();
								
								$total_record_coin_pdo = $stmt->rowCount();
								for ($ki = 0; $ki < $total_record_coin_pdo; $ki++) {
									$stmt = $pdo->prepare($query_pdo);
									$stmt->execute();
									$result_coin_pdo = $stmt->fetchAll();
									$c_no = $result_coin_pdo[$ki][0];
									$c_coin = $result_coin_pdo[$ki][1];
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
						<td width=115 height="30">
							<div align="left">
								<font face="돋움" size="2"><?=M_LEVEL?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<select name="c_level">
									<? for ($i = 1; $i <= $DEFINE_USER_LEVEL; $i++) { ?>
									<option value=<?= $i ?>><?= $i ?></option>
									<? } ?>
								</select>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="left">
								<font size="2" face="돋움"><?=M_MAX.M_DEPOSIT.M_LIMIT.M_SETTING?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_deposit" value="<?= $c_deposit ?>" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="left">
								<font size="2" face="돋움"><?=M_MAX.M_WITHDRAW.M_LIMIT.M_SETTING?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_withdraw" value="<?= $c_withdraw ?>" size=30 class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="left">
								<font size="2" face="돋움"><?=M_MAX.M_DAYLY.M_LIMIT.M_SETTING?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="c_limit" value="<?= $c_limit ?>" size=30 class="adminbttn">
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
			<input type="button" value="등록" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>



<? include "../inc/down_menu.php"; ?>