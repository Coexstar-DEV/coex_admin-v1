<meta charset="utf-8">
<?
include_once "../common/init_table.php";
include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../inc/adm_chk.php";
include_once "../common/trading.php";
include_once "../common/withdraw.php";
include_once "../common/wallet.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL3);

$keyfield = isset($_REQUEST["keyfield"]) ? ($_REQUEST["keyfield"]) : "";
$key = isset($_REQUEST["key"]) ? ($_REQUEST["key"]) : "";
$page = isset($_REQUEST["page"]) ? ($_REQUEST["page"]) : 1;
$krw = isset($_REQUEST["krw"]) ? ($_REQUEST["krw"]) : "";

$encoded_key = urlencode($key);

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
err_log("============== address:".$_POST["t_address"]);
$t_address = trim(sqlfilter($_POST["t_address"]));
$t_dest_tag = sqlfilter($_POST["t_dest_tag"]);

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

$signdate = time();
$c_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

$userInfo = UserInfo::makeWithId($t_id, $c_ip);

$query_pdo = "SELECT c_fees,c_coin,c_type FROM $table_setup WHERE c_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($t_division));
$row = $stmt->fetch();
$t_fees = $row["0"];
$c_coin = $row["1"];
$c_type = $row["2"];

$t_reciveid = $admin_id . "/" . $admip . "/" . date("Y-m-d");
$m_signdate = $signdate;
$t_orderkrw = str_replace(",", "", $t_orderkrw);

if ($t_delete == "1") { // 삭제 여부 

    $pdo->beginTransaction();

	try {

		$withdraw = WithDraw::makeWithNo($t_no);
		$withdraw->columns["t_orderkrw"] = $t_orderkrw;
		$withdraw->columns["t_reciveid"] = $t_reciveid;
		$withdraw->columns["t_fees"] = $t_fees;
		$withdraw->columns["t_delete"] = $t_delete;
		$withdraw->columns["t_check"] = "1";

		$withdraw->cancel($userInfo, $c_coin);
		$pdo->commit();
		err_log("cancel ---------- done");
	} catch (PDOException $e) {
		$LOG_LEVEL = 1;
		err_log("cancel ---------- failed");
		err_log($e->getMessage());
		$pdo->rollBack();
	}
	echo("<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page'>"); 
	exit;
}

if ($t_check == $t_check_old || $t_check == "0") {
	// 지급 완료가 미지급 일 경우 리턴 
	echo ("<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
	exit;
}


$withdraw = WithDraw::makeWithNo($t_no);
if ($withdraw->columns["t_check"] == "1") {
    err_log("WARN - already withdraw : exit");
    popup_msg("WARN - 이미 출금 되었습니다.");
    echo "<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
    exit;
}

/*************************************************
* 
*  이하 코인 출금 로직 
*
**************************************************/
$t_duedate = time();
$t_depositmost = $t_ordermost;
$t_depositkrw = $t_orderkrw;


$walletapi = WalletAPI::make($c_type, $c_coin);
if (is_empty($walletapi) || $c_coin == "PHP" ) {

} else {
    $master_wallet = get_master_wallet($c_type, $c_coin);

    err_log("wallet ==========> to :$t_address");
    $amount = bcsub($t_ordermost, $t_fees, 8) + 0;

    if ($amount < 0 ) {
        popup_msg("WARN - 출금액이 0보다 작습니다.($amount)");
        echo "<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
        exit;
    }

    if ($amount > 0) {
        $r_code = $walletapi->sendTo($c_coin, $master_wallet["account"], $master_wallet["pwd"], $t_address, $amount, $t_dest_tag);

        // 에러 일경우 바로 exit 처리됨. ;
        wallet_result_check($c_coin, $r_code);
        $wallet_result = $walletapi->getResult();
    } else {
        $wallet_result = "amount0";
    }

}


$pdo->beginTransaction();
try {
	// withdraw 업데이트 

	$withdraw->columns["t_check"] = $t_check;
	$withdraw->columns["t_cont"] = $wallet_result;
	$withdraw->columns["t_duedate"] = $signdate;
	$withdraw->columns["t_fees"] = $t_fees;
	$withdraw->transmit($userInfo, $t_ordermost, $t_fees, $c_coin);

	$pdo->commit();
	//$pdo->rollBack();
} catch (PDOException $e) {
	fatal_log($e->getMessage());
	$pdo->rollBack();
}

// 리스트 출력화면으로 이동한다
err_log("meta====> keyfield=$keyfield&key=$encoded_key&page=$page&krw=$krw");
echo ("<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page&krw=$krw'>");
?>
