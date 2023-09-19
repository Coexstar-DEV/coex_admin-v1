<?php

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


$c_coin = $_REQUEST["c_coin"];
if (is_empty($c_coin)) {
    fatal_log("invalid param : c_coin is empty");
    echo "invalid param : c_coin is empty";
    return;
}

$coinInfo = new CoinInfo($c_coin);
$signdate = time();

$c_type = $coinInfo->c_type;
//1. wallet 서버로  getDepositList() 를 호출
//2. account list 를 받으면 , 해당 user 에게 recive_coin 호출. 

if ($c_type != "5" ) {
    fatal_log("[$c_coin] Not eth, yoc, token type: $c_type, name:$c_coin");
    exit;
}

$lasttx = is_empty($coinInfo->lasttx) ? "0" : $coinInfo->lasttx;

$api = "http://$WALLET_API_URL/$c_coin/v1/trx/txInfo?blocknumber=$lasttx&depth=20";

$ch = make_curl2($api, NULL);
err_log("getDepositList :$c_coin: curl '$api'");

$results = curl_exec($ch);
if ($results === false) {
    fatal_log("getDepositList:code:".curl_error($ch).">  curl '$api' ");
    $r_code = ERR_NETWORK;
} else {
    $r_code = SUCCEED;
}
wallet_result_check($c_coin, $r_code);

if ($r_code == ERR_INVALID_PARAM )  {
    fatal_log("$r_code: ERR_INVALID_PARAM:".__LINE__);
    exit;
}

err_log("[$c_coin]=>result:$results");
$array_result = json_decode($results, true);
//$accoutList = [$dbname."_plutok@hanmail.net@111111", $dbname."_passion0470@naver.com@2"];
//$accoutList = array_reverse($accoutList);

var_export($array_result, true);


$accoutList = $array_result["list"];

// accountList 에 아이디가 여러번 중복되어 있을때 처리? 

/*******************/
$checked_account = array();
foreach($accoutList as $item) {
    $LOG_LEVEL = 1;

    $coin_name = $item["type"]; // TRX

    $user_id = UserInfo::getIdFromWallet($c_type, $coin_name, $item["wallet"]);

    err_log($item["type"]."=>DEPOSITV2 id:$user_id, wallet:".$item["wallet"].", hash:".$item["txid"]);
    if (!is_empty($user_id)) {
        $userInfo = UserInfo::makeWithId($user_id, "127.0.0.1");

        $suspend_yn = $coinInfo->suspend_yn;
        if ($suspend_yn != "0" && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }

        $api = "http://127.0.0.1/Adm/deposit/recive_coin.php?c_coin=$coin_name&user_id=$user_id";
        $param = NULL;
        $ch = WalletAPI::make_curl($api, $param);
        err_log("[$coin_name] DEPOSITV2: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log("recive_coin : ".curl_errno($ch).":".curl_error($ch));
            return;
        }

    } else {
        err_log("[$coin_name] =>skip -parse warning: coin:$coin_name, userid:empty, wallet:".$item["wallet"]);
    }
    $LOG_LEVEL = 0;
}
/*******************/

$lasttx = $array_result["blocknum"];
$coinInfo->setLastTx($lasttx);

$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

