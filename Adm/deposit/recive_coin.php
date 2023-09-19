<?php

//session_start();

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$kk2 = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk1 = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));

$allkeys = array();
// for only one user;
if (isset($_REQUEST["user_id"])) {
    $user_id = $_REQUEST["user_id"];
    $allkeys = [$user_id];
} else {

    $redis_host = $REDIS_HOST;
    $redis_port = 6379;
    $redis = new Redis();
    $redis->connect($redis_host, $redis_port, 1000);
    $allkeys = $redis->keys("LOGIN:$WEBSITE:*");

}

$c_coin = $_REQUEST["c_coin"];
if (is_empty($c_coin)) {
    fatal_log("invalid param : c_coin is empty");
    echo "invalid param : c_coin is empty";
    return;
}

$txid = isset($_REQUEST["txid"]) ? $_REQUEST["txid"] : "";

$coinInfo = new CoinInfo($c_coin);

$num_user = count($allkeys);
$signdate = time();

if ($coinInfo->c_type == "2" || $coinInfo->c_type == "7") {
    // 입금 테이블 확인 
    $query = "SELECT COUNT(*) as cnt FROM coin_point WHERE c_div = ".$coinInfo->type." and c_category2 LIKE '%$txid%' ";
    $stmt = pdo_excute("coinpoint",$query, NULL);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['cnt'];
    err_log("ETH - count:$count <--------- $txid");
    if ($count > 0) {
        fatal_log(__FUNCTION__.":ETH - alread send *$result*");
        exit;
    }
}

err_log("========= start $coinInfo->name deposit , count($num_user), suspend:$coinInfo->suspend_yn");
for ($i = 0; $i < $num_user; $i++) {
    //$userid = $allkeys[$i];
    $userid = (isset($_REQUEST["user_id"]) ? $user_id : $redis->get($allkeys[$i]));
    $userInfo = UserInfo::makeWithId($userid, "127.0.0.1");

    $c_coin = $coinInfo->name;
    $c_title = $coinInfo->title;
    $coin_type = $coinInfo->type;
    $c_type = $coinInfo->c_type;
    $suspend_yn = $coinInfo->suspend_yn;
    if (($suspend_yn == "1" || $suspend_yn == "3") && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            continue;
    }



    $walletapi = WalletAPI::make($c_type, $c_coin);
    if (is_empty($walletapi)) {
        fatal_log($c_coin."invalid wallet type: $c_type, name:$c_coin");
        exit;
    }

    $r_code = $walletapi->getBalance($c_coin, $userInfo->getAccount($c_type, $c_coin));

    // 여기서 에러 나면 바로 exit 됨.
    if ($r_code == ERR_INVALID_PARAM)  {
        continue;
    }
    wallet_result_check($c_coin, $r_code);
    $balance = $walletapi->getResult();
    if ($c_type == "2" || $c_type == "7") { // case token
        $query = "SELECT sum(c_exchange+0) FROM $table_point WHERE c_div='$coin_type' and  c_category='reqorderrecv' and c_id='$userid' and c_dis='0'";
        $stmt = pdo_excute("select legacy", $query, NULL);
        $row = $stmt->fetch();
        $legacy = number_format($row[0], 8, '.', '');
        $balance = bcsub($balance, $legacy, 8);
    }
    //err_log($c_coin.":balance:$balance, legacy:$legacy limit_in:$coinInfo->limit_in");

    if ($balance <= 0 || $balance < $coinInfo->limit_in) {
        if ($balance > 0)
            err_log("========= DEPOSIT_LIMIT coin:$coinInfo->name , count($num_user), user:$userid balance($balance:".$coinInfo->limit_in.")");
        continue;
    }

    // 체크 순서 바꿈. 1.balance 가 0 이상일때, 2. 1시간 입금 처리 금지 
    /*
    $query = "SELECT c_no FROM $table_point where c_div= ? and c_signdate+0 > ? and c_signdate+0 < ? and  c_category='reqorderrecv' and c_id=? limit 1 ";
    $pdo_in = [$coin_type, $kk1, $kk2, $userid];

    $stmt = pdo_excute("select1", $query, $pdo_in);

    $row = $stmt->fetch();
    if (!is_empty($row[0])) { 
        fatal_log($c_coin.": 1 hour limit----deposit process($userid:$balance) ------------");
        continue; 
    } // 1 시간 이내 입금 처리 금지 
    */


    $LOG_LEVEL = 1;
    err_log($c_coin.":----------deposit process($userid:$balance) ------------");

    //$phone = substr($phone, -4);
    //$password = $userid . "@" . $phone;
    $master_wallet = get_master_wallet($coin_type, $c_coin);
    //err_log($c_coin.":master==>".var_export($master_wallet, true));
    if (is_empty($master_wallet)){
        fatal_log("failed to find master wallet - $c_coin");
        exit;
    }

    if ($c_type == "0" || $c_type == "1") { 
        $walletapi = WalletAPI::make($c_type, $c_coin);
        $r_code = $walletapi->moveTo($c_coin, $userInfo->getAccount($c_type, $c_coin), $userInfo->getPassword($coin_type), $master_wallet["account"], "-1");  // send all 
        err_log("$c_coin::walletapi->moveTo , id:$userInfo->id, amount:$balance, ret:".$r_code);
        // 여기서 에러 나면 바로 exit 됨. 
        wallet_result_check($c_coin, $r_code);

        if ($c_type == "0")
            $cause  = $walletapi->getWalletFromAccount($userInfo->getAccount($c_type, $c_coin));
        else
            $cause =  $walletapi->getResult();
    } else if ($c_type == "2" || $c_type == "7") {
        $cause  = $userInfo->getAccount($c_type, $c_coin);

    } else if ($c_type == "5") {
        $walletapi = WalletAPI::make($c_type, $c_coin);
        $r_code = $walletapi->moveTo($c_coin, $userInfo->getAccount($c_type, $c_coin), "", $master_wallet["account"], "-1");  // send all 
        //err_log("walletapi->moveTo :".$r_code);
        // 여기서 에러 나면 바로 exit 됨. 
        wallet_result_check($c_coin, $r_code);

        $cause =  $walletapi->getResult();
    } 


    // 입금 처리. 
    $pdo->beginTransaction();
    try {
        $deposit = new Deposit($userInfo, $coinInfo);
        $deposit->deposit($balance, $cause, $txid);
        $pdo->commit();
    } catch (PDOException $e) {
        $s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
        fatal_log($c_coin.":$s");
        $pdo->rollBack();
    }

    err_log($c_coin.":----------deposit done($userid:$balance) ------------");
    $LOG_LEVEL = 0;
} 

err_log("========= end $coinInfo->name deposit , count($num_user)");
?>

