<?

session_start();

include_once "../common/init_table.php";
include_once "../common/user_function.php";
include_once "../common/wallet.php";
include_once "../common/dbconn.php";
include "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_master.php";
include "../inc/adm_chk.php";
$LOG_LEVEL = 1;
$LOG_TAG = "MASTER:" . basename(__FILE__);
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
check_manager_level($adminlevel, ADMIN_LVL4);

$encoded_key = urlencode($key);
$query_pdo = "SELECT c_no,c_coin,c_type FROM $table_setup A LEFT JOIN m_setup B ON A.c_no=B.m_div WHERE B.m_pay = 'PHP' and c_no <> $DEFINE_DEFAULT_COIN and c_use =1 ";

if ($key != "") {
	$query_pdo .= " and $keyfield LIKE '%$key%' ";
}

$query_pdo .= " ORDER BY c_rank+0 asc";

$total_record_pdo = pdo_excute_count("select count:", $query_pdo, NULL);





if (isset($_REQUEST["page"])) {
	$page = $_REQUEST["page"];
} else {
	$page = 1;
}
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
$mode = "keyfield=$keyfield&key=$encoded_key&k_checkk=$k_checkk&page=$page";

?>

<script language="javascript">
	function go_move(form_nm) {
		ans = confirm('정말로 이동하시겠습니까?');
		if (ans == true) {
			document.forms[form_nm].action = "member_move.php";
			document.forms[form_nm].submit();
		}
	}
</script>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center"><?=M_MASTER_ACCOUNT?></td>
					<td align="right">
						<form name=dform action="./member_dis_excel.php" method=post target="_blank">
							<input type="hidden" name="level_l" value="<?= $level_l ?>">
							<? $file_name = mktime(date("H"), date("i"), date("s"), date("Y"), date("m"), date("d")); ?>
							<input type="hidden" name="file_name" value="<?= $file_name ?>">
							<input type="hidden" name="dis" value="<?= $dis ?>">
							<input type="hidden" name="member_count" value="<?= $member_count ?>">
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
		<tr>
			<td height=3></td>
		</tr>
		<tr>
			<td>
				<table width="1000" border="0" cellspacing="0" cellpadding="4"> </table>
				<table width="1000" border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td colspan=12 height=3 bgcolor='#ffffff'></td>
					</tr>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="100" height="30">코인종류</td>
						<td width="100" height="30">지갑주소</td>
						<td width="100" height="30">잔액</td>
						<td width="20" height="30">수량</td>
						<td width="100" height="30">COLD</td>
					</tr>
					<tr>
						<td colspan=12 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td colspan=12 height=3></td>
					</tr>

					<?
					$ii = 0;
					//$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
					$stmt = pdo_excute("select", $query_pdo, NULL);
					while ($row = $stmt->fetch()) {
						$c_no = $row[0];
						$c_coin = $row[1];
						$c_type = $row[2];

						$master_wallet = get_master_wallet($c_type, $c_coin);
						err_log($c_coin . ":get_master_wallet($c_no:$c_type)==>" . var_export($master_wallet, true));

						$cold_wallet = get_cold_wallet($c_type, $c_coin);
						err_log($c_coin . ":get_cold_wallet($c_no:$c_type)==>" . var_export($master_wallet, true));

						$is_cold = false;
						if (!is_empty($cold_wallet)) {
							$is_cold = true;
						}

						$walletapi = WalletAPI::make($c_type, $c_coin);
						if (is_empty($walletapi)) {
							fatal_log($c_coin . "failed to get master wallet: type:$c_type, name:$c_coin");
							exit;
						}

						$r_code = $walletapi->getBalance($c_coin, $master_wallet["account"]);

						if ($r_code != SUCCEED) {
							// 여기서 에러 나면 바로 exit 됨.
							//wallet_result_check($c_coin, $r_code);
							$master_address = "$c_no($c_type) account:" . $master_wallet["account"];
							$balance = "code:" . $r_code;
						} else {

							$master_address = $walletapi->getWalletFromAccount($master_wallet["account"]);
							$balance = $walletapi->getResult();

							
						}
						err_log($c_coin . ":balance:$balance");
						//$c_signdate = date("Y-m-d H:i:s", $c_signdate);



			

						?>

						<tr align="center">
								<td height="30"><?= $c_coin ?></td>
								<td height="30"><?= $master_address ?><br><? //=$master_wallet["account"] ?></td>
								<td height="30"><?= $balance ?></td>
								<td height="30">
									<form name="form_<?= $c_no ?>" action="./member_move.php" method="post">
									<input type="hidden" name="m_coin" value="<?= $c_coin ?>">
									<input type="hidden" name="coin_type" value="<?= $c_no ?>">
									<input type="hidden" name="m_addr" value="<?= $master_address ?>">
									<input type="hidden" name="m_balance" value="<?= $balance ?>">
									<input type="hidden" name="c_type" value="<?= $c_type ?>">
									<input type="text" name="m_ratio" value="0"> 
									</form>
								</td>
								<td height="30">
								<? if($is_cold) { ?>
									<input type="button" value="이동" class="adminbttn" onClick="javascript:go_move('form_<?= $c_no ?>')">
								<? } ?>
								</td>
						</tr>
						<tr>
							<td colspan=12 height=1 bgcolor='#D2DEE8'></td>
						</tr>

					<?
						$article_num--;
						$ii++;
						$bitbalance = "";
					}
					$al_addr = "";
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

			</font>
		</td>
	</tr>
	<input type="hidden" name="chk_num" value="<? echo ($chk_num) ?>">
</table>
<br><br>
<? include "../inc/down_menu.php"; ?>