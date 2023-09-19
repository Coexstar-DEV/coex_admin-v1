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

if ($c_type != "6" ) {  // 4 eos 
    fatal_log("[$c_coin] Not Alt2 type: $c_type, name:$c_coin");
    exit;
}

$lasttx = is_empty($coinInfo->lasttx) ? "0" : $coinInfo->lasttx;


$walletapi = WalletAPI::make($c_type, $c_coin);
if (is_empty($walletapi)) {
    fatal_log("[$c_coin] invalid wallet type: $c_type, name:$c_coin");
    exit;
}

if ($c_type != "6") {
    fatal_log("[$c_coin] Not ALT2 type: $c_type, name:$c_coin");
    exit;
}

$r_code = $walletapi->getDepositList($c_coin, $lasttx);

// 여기서 에러 나면 바로 exit 됨.
if ($r_code == ERR_INVALID_PARAM)  {
    fatal_log("[$c_coin] ERR_INVALID_PARAM:".__LINE__);
    exit;
}

// test code ---------------------------- 
// [{"account":"test_master","txid":"29641d03c828fc09e81bf95c9da3052ec6a538087d0013d99a0ce04e7431d356","time":1577371566}]
// test code ---------------------------- 

wallet_result_check($c_coin, $r_code);

if ($r_code == ERR_INVALID_PARAM )  {
    fatal_log("$r_code: ERR_INVALID_PARAM:".__LINE__);
    exit;
}

$results = $walletapi->getResult();
err_log("[$c_coin]=>result:$results");

$accoutList = json_decode($results, true);
//$accoutList = [$dbname."_plutok@hanmail.net@111111", $dbname."_passion0470@naver.com@2"];
$accoutList = array_reverse($accoutList);

// accountList 에 아이디가 여러번 중복되어 있을때 처리? 

/*******************/
$recv_seq = 0;
foreach($accoutList as $item) {
    $LOG_LEVEL = 1;
    err_log("[$c_coin]=>account:".$item["account"].", txid:".$item["txid"].", time:". date("Y-m-d h:i:s", $item["time"]));

    if ($item["account"] == "NONE") break;

    $user_id = UserInfo::getIdFromAccount($c_type, $item["account"]);
    $lasttx = $item["txid"];
    $balance = $item["amount"];

    $userInfo = UserInfo::makeWithId($user_id, "127.0.0.1");
    if (!is_empty($userInfo)) {

        $suspend_yn = $coinInfo->suspend_yn;
        if ($suspend_yn != "0" && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }

        $q = "SELECT COUNT(*) as dcount FROM coin_point WHERE c_id = '$user_id' and c_div = '$coinInfo->type' and c_category2 = '$lasttx'";
        $stmt = pdo_excute("count", $q, null);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['dcount'] > 0) {
           fatal_log("already deposited CHECK !!!!!!!!: user:$user_id, category2:$lasttx, seq:$seq");

        } else {

            // move to master
            $master_wallet = get_master_wallet($c_type, $c_coin);
            $r_code = $walletapi->moveTo($c_coin, $userInfo->getAccount($c_type, $c_coin), "", $master_wallet["account"], $balance);  // send all 
            err_log("$c_coin::walletapi->moveTo , id:$userInfo->id, amount:$balance, ret:".$r_code);
            wallet_result_check($c_coin, $r_code);

            $cause =  $walletapi->getResult();


            $api = "http://127.0.0.1/Adm/deposit/recive_coin_manual.php?c_coin=$c_coin&user_id=$user_id&balance=$balance&cause=$lasttx";
            $param = NULL;
            $ch = WalletAPI::make_curl($api, $param);
            err_log("[$c_coin] DEPOSITV2: curl '$api'");
            $result = curl_exec($ch);
            if ($result === false) {
                fatal_log("recive_coin : ".curl_errno($ch).":".curl_error($ch));
                return;
            }
        }

    } else {
        err_log("[$c_coin] =>skip -parse warning: coin:$c_coin, userInfo:empty, memo:$user_no, txid:".$lasttx);
    }
    $LOG_LEVEL = 0;
}
/*******************/
$coinInfo->setLastTx($lasttx);

$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

