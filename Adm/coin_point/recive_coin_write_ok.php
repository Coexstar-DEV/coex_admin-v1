<?php

include "../common/init_table.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../common/user_function.php";
include "../common/trading.php";
include "../common/wallet.php";
include "../common/deposit.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$kk2 = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk1 = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));


$user_id = $_REQUEST["user_id"];
if (is_empty($user_id)) {
    popup_msg(M_INPUT_ID);
    exit;
}

$c_coin = $_REQUEST["c_coin"];
$allkeys = array();
$allkeys = [$user_id];

$coinInfo = new CoinInfo($c_coin);

$num_user = count($allkeys);
$signdate = time();

err_log("========= start $coinInfo->name deposit , count($num_user), suspend:$coinInfo->suspend_yn");
for ($i = 0; $i < $num_user; $i++) {
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
        if($cnt > 0) {
            popup_msg($cnt."건 반영 하였습니다.");
        }
        else {
            popup_msg("지갑API를 찾을 수 없습니다.");
        }
        exit;
    }

    $r_code = $walletapi->getBalance($c_coin, $userInfo->getAccount($c_type, $c_coin));

    // 여기서 에러 나면 바로 exit 됨.
    if ($r_code < 0) {
        if ($r_code == ERR_INSUFFICIENT) {
            popup_msg($c_coin.' 코인수량이 부족 합니다.');
        }
        else if ($r_code == ERR_ALREADY_SEND) {
            popup_msg($c_coin.' 코인수량이 부족 합니다.');
        }
        else if ($r_code == ERR_INVALID_PARAM) {
            popup_msg($c_coin.' 코인수량이 부족 합니다.');
        }
        else if ($r_code == ERR_NETWORK) {
            popup_msg($c_coin.' 코인수량이 부족 합니다.');
        }
        else  {
            popup_msg($c_coin.' 전송실패 하였습니다.');
        }
        exit;
    }

    $rtn = wallet_result_check2($c_coin, $r_code);
    if($rtn != "") {
        if($cnt > 0) {
            popup_msg($cnt."건 반영 하였습니다.");
        }
        else {
            popup_msg($rtn);
        }
        exit;
    }
    

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

    err_log($c_coin.":----------deposit process($userid:$balance) ------------");

    $master_wallet = get_master_wallet($coin_type, $c_coin);
    if (is_empty($master_wallet)){
        fatal_log("failed to find master wallet - $c_coin");
        if($cnt > 0) {
            popup_msg($cnt."건 반영 하였습니다.");
        }
        else {
            popup_msg("마스터 지갑을 찾을 수 없습니다.");
        }
        exit;
    }

    if ($c_type == "0" || $c_type == "1") { 
        $walletapi = WalletAPI::make($c_type, $c_coin);
        $r_code = $walletapi->moveTo($c_coin, $userInfo->getAccount($c_type, $c_coin), $userInfo->getPassword($coin_type), $master_wallet["account"], "-1");  // send all 
        
        $rtn2 = wallet_result_check2($c_coin, $r_code);
        if($rtn2 != "") {
            if($cnt > 0) {
                popup_msg($cnt."건 반영 하였습니다.");
            }
            else {
                popup_msg($rtn2);
            }
            exit;
        }

        if ($c_type == "0")
            $cause = $walletapi->getWalletFromAccount($userInfo->getAccount($c_type, $c_coin));
        else
            $cause =  $walletapi->getResult();
    } else if ($c_type == "2" || $c_type == "7") {
        $cause = $userInfo->getAccount($c_type, $c_coin);
    } 


    // 입금 처리. 
    $pdo->beginTransaction();
    try {
        $deposit = new Deposit($userInfo, $coinInfo);
        $deposit->deposit2($balance, $cause, "");
        $pdo->commit();
        $cnt++;
    } catch (PDOException $e) {
        $s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
        fatal_log($c_coin.":$s");
        $pdo->rollBack();
    }

    err_log($c_coin.":----------deposit done($userid:$balance) ------------");
    $LOG_LEVEL = 0;
} 

if($cnt > 0) {
    popup_msg($cnt."건 반영 하였습니다.");
}
else {
    popup_msg("등록할 입금내역이 없습니다.");
}
echo ("<meta http-equiv='Refresh' content='0; URL=recive_coin_write.php?c_div=$c_coin'>");

err_log("========= end $coinInfo->name deposit , count($num_user)");
?>

