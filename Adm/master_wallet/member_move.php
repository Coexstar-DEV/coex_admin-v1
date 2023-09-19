<meta charset="utf-8">
<?
include_once "../common/init_table.php";
include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../inc/adm_chk.php";
include_once "../common/wallet.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL4);

$admin_id = $_SESSION["admin_id"];

if (isset($_REQUEST["m_coin"])) {
	$c_coin = sqlfilter($_REQUEST["m_coin"]);
} else {
	$c_coin = "";
}

if (isset($_REQUEST["coin_type"])) {
	$coin_type = sqlfilter($_REQUEST["coin_type"]);
} else {
	$coin_type = "";
}

if (isset($_REQUEST["c_type"])) {
	$c_type = sqlfilter($_REQUEST["c_type"]);
} else {
	$c_type = "";
}

if (isset($_REQUEST["m_addr"])) {
	$master_address = sqlfilter($_REQUEST["m_addr"]);
} else {
	$master_address = "";
}

if (isset($_REQUEST["m_ratio"])) {
	$m_ratio = sqlfilter($_REQUEST["m_ratio"]);
} else {
	$m_ratio = "";
}

if (isset($_REQUEST["m_balance"])) {
	$m_balance = sqlfilter($_REQUEST["m_balance"]);
} else {
	$m_balance = "";
}

$master_wallet = get_master_wallet($c_type, $c_coin);
if (is_empty($master_wallet)) {
	popup_msg("[$c_coin] Failed to find master wallet.");
	exit;
}

$cold_wallet = get_cold_wallet($c_type, $c_coin);
if (is_empty($cold_wallet)) {
	popup_msg("[$c_coin] Failed to find cold wallet.");
	exit;
}

$walletapi = WalletAPI::make($c_type, $c_coin);
if (is_empty($walletapi)) {
	fatal_log($c_coin . "failed to get wallet api: type:$c_type, name:$c_coin");
	popup_msg("[$c_coin:] Failed to get wallet api.");
	exit;
}
//$amount = bcdiv(bcmul($m_balance, $m_ratio, 8) , 100, 8);
$amount = $m_ratio; //수량으로 변경. 
$tag = "";
$r_code = $walletapi->sendTo($c_coin, $master_wallet["account"], $master_wallet["pwd"], $cold_wallet["account"], $amount, $tag);

if ($r_code != SUCCEED) {
	// 여기서 에러 나면 바로 exit 됨.
	//wallet_result_check($c_coin, $r_code);
	popup_msg("[$c_coin] Fail to send coin");
	//$master_address = "$coin_type($c_type) account:".$master_wallet["account"];
	//$balance = "code:".$r_code;
	exit;
} else {
	popup_msg("[$c_coin] Succeed to send coin");
	exit;
	//$master_address = $walletapi->getWalletFromAccount($master_wallet["account"]);
	//$balance = $walletapi->getResult();
}

$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
?>