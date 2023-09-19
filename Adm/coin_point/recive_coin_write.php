<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";

if (isset($_REQUEST["c_div"])) {
	$c_div = sqlfilter($_REQUEST["c_div"]);
} else {
	$c_div = "";
}
?>

<script language="javascript">
	function go_modify() {
		document.form.action = "recive_coin_write_ok.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "recive_coin_list.php";
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
						<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_MANUAL." ".M_DEPOSIT?></b></td>
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
							&nbsp;
							<select name="c_coin">
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
								<font size="2" face="돋움"><?=M_ID?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							&nbsp;
							<input name="user_id" value="" size=30 class="adminbttn">
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
			<input type="button" value="<?=M_DEPOSIT." ".M_PROCESS?>" class="adminbttn" onClick="javascript:go_modify()">&nbsp;
			<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">
		</td>
	</tr>
</table>
<br>
<br>

<? include "../inc/down_menu.php"; ?>