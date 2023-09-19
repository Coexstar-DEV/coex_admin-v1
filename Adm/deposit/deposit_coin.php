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

$walletapi = WalletAPI::make($c_type, $c_coin);
if (is_empty($walletapi)) {
    fatal_log("[$c_coin] invalid wallet type: $c_type, name:$c_coin");
    exit;
}

if ($c_type != "0") {
    fatal_log("[$c_coin] Not altcoin type: $c_type, name:$c_coin");
    exit;
}

$r_code = $walletapi->getDepositList($c_coin, $coinInfo->lasttx);

// 여기서 에러 나면 바로 exit 됨.
if ($r_code == ERR_INVALID_PARAM)  {
    fatal_log("[$c_coin] ERR_INVALID_PARAM:".__LINE__);
    exit;
}

wallet_result_check($c_coin, $r_code);
$results = $walletapi->getResult();
err_log("[$c_coin]=>result:$results");
$accoutList = json_decode($results, true);
//$accoutList = [$dbname."_plutok@hanmail.net@111111", $dbname."_passion0470@naver.com@2"];
$accoutList = array_reverse($accoutList);

// accountList 에 아이디가 여러번 중복되어 있을때 처리? 
$checked_account = array();
foreach($accoutList as $item) {
    err_log("[$c_coin]=>account:".$item["account"].", txid:".$item["txid"].", time:". date("Y-m-d h:i:s", $item["time"]));
    if ($item["account"] == "NONE") break;

    $user_id = UserInfo::getIdFromAccount($c_type, $item["account"]);
    $check = $checked_account[$item["account"]];
    $checked_account[$item["account"]] = $item["txid"];
    $lasttx = $item["txid"];

    //foreach($checked_account as $key => $value) { err_log("============>$key:$value"); }

    if (!is_empty($check)) { // already checked.
        err_log("aleady checked:".$item["account"]);
        continue;
    }
    //err_log("[$c_coin]=>id:$user_id");

    if (!is_empty($user_id)) {
        $userInfo = UserInfo::makeWithId($user_id, "127.0.0.1");

        $suspend_yn = $coinInfo->suspend_yn;
        if (($suspend_yn == "1" || $suspend_yn == "3") && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }


        $api = "http://127.0.0.1/Adm/deposit/recive_coin.php?c_coin=$c_coin&user_id=$user_id";
        $param = NULL;
        $ch = WalletAPI::make_curl($api, $param);
        err_log("[$c_coin] DEPOSIT: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log("recive_coin : ".curl_errno($ch).":".curl_error($ch));
            return;
        }

        $coinInfo->setLastTx($lasttx);

    } else {
        err_log("[$c_coin] =>skip :::parse warning: coin:$c_coin, account:".$item["account"]);
    }
}

$LOG_LEVEL = 1;
$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

