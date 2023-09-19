<?
#####################################################################
include_once "../../include/init_table.php";
include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../common/trading.php";
include_once "../common/withdraw.php";
include_once "../common/wallet.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$signdate = time();


//========================

// 원화 출금 제외 , pending = 0 , check = 0
$wdate1=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-3,date("Y"));
$query_list = "SELECT t_no, t_division, t_name, t_userno, t_id, t_krw, t_usekrw, t_ordermost, t_depositmost, t_orderkrw, t_depositkrw, ";
$query_list .= "t_check, t_cont, t_email, t_fees, TRIM(t_address) t_address, t_acount, t_bankname, t_ordername, t_reciveid, t_delete, t_signdate, t_ip, t_duedate, t_dest_tag, t_pending ";
$query_list .= "FROM $table_withdraw WHERE t_pending = 0 and t_check = 0 and t_signdate > $wdate1 order by t_no asc";
$stmt = pdo_excute("select list", $query_list, NULL);

$withdraw_count = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $withdraw = WithDraw::makeWithRow($row);
    $c_no = $withdraw->columns["t_division"];
    $t_id = $withdraw->columns["t_id"];
    $t_ip = $withdraw->columns["t_ip"];
    $t_address = $withdraw->columns["t_address"];
    $t_ordermost = $withdraw->columns["t_ordermost"];
    $withdraw_count[] = $withdraw->columns["t_no"];

    $coinInfo = new CoinInfo($c_no);
    $userInfo = UserInfo::makeWithId($t_id, $t_ip);
    $c_coin = $coinInfo->name;
    $t_fees = $coinInfo->fees;

    /*********************************************
    Pending1 : HOLD에서 KRWC 입금 후 BTC, ETH 출금 시 72시간 pending “pending 1-Stop Chack” ==> 점검사항 : 무조건 72시간 보류, 
    Pending2 :  코인 입금 후(모든코인입금) 그 중 코인 출금이 -1시간 이내일 경우 점검사항 : ==> ip체크(지난번 출금시에도 동일하게 사용한 IP인가?)
    Pending3 : 24시간 동안 모든유저들이 같은 코인 지갑 주소로 24시간 이내에 3번째 출금  ==> 점검사항 : ip체크(지난번에도 동일하게 사용한 IP인가? )
    Pending4 :  모든유저들이 동일 코인이 24시간 이내 20건 출금 이후부터 ==> 점검사항 : ip체크(동일 ip출금 일경우 보류)
    Pending5 :  코인출금 시점  출금하는 그 코인의 거래횟수가 5회 이하 (제외 KRWC)   ==>  점검사항 : 아이피체크
    ***********************************************/

    //Pending1 : HOLD에서 KRWC 입금 후 BTC, ETH 출금 시 72시간 pending “pending 1-Stop Chack” => 점검사항 : 무조건 72시간 보류, 
    $wdate1=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-3,date("Y"));
    if ($coinInfo->name == "BTC" || $coinInfo->name == "ETH") {
        $krwcInfo = new CoinInfo("KRWC");
        $query = "SELECT count(*) FROM coin_point WHERE c_id = '$t_id' and c_div='$krwcInfo->type' and c_category = 'reqorderrecv' and c_signdate > $wdate1";
        $stmt1 = pdo_excute("CHK_1:", $query, NULL);
        $count = $stmt1->fetch()[0];
        if ($count > 0) {
            $withdraw->columns["t_pending"] = 1;
            $withdraw->update();
            continue;
        } else {
            //err_log("--------- pass CHK_1");
        }
    }


    //Pending2 :  코인 입금 후(모든코인입금) 그 중 코인 출금이 -1시간 이내일 경우 점검사항 : ==> ip체크(지난번 출금시에도 동일하게 사용한 IP인가?)
    $wdate1=mktime(date("H")-1,date("i"),date("s"),date("m"),date("d"),date("Y"));
    $query = "SELECT count(*) FROM coin_point WHERE c_id = '$t_id' and c_category = 'reqorderrecv' and c_signdate > $wdate1";
    $stmt1 = pdo_excute("CHK_2:", $query, NULL);
    $count = $stmt1->fetch()[0];
    if ($count > 0) {
        $withdraw->columns["t_pending"] = 2;
        $withdraw->update();
        continue;
    } else {
        //err_log("--------- pass CHK_2");
    }

    //Pending3 : 24시간 동안 모든유저들이 같은 코인 지갑 주소로 24시간 이내에 3번째 출금  ==> 점검사항 : ip체크(지난번에도 동일하게 사용한 IP인가? )
    $pending3 = false;
    $wdate1=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
    $query = "SELECT TRIM(t_address) t_address FROM $table_withdraw WHERE t_id = '$t_id' and t_division = '$c_no' and t_signdate > $wdate1 group by t_address";
    $stmt1 = pdo_excute("CHK_3:", $query, NULL);
    while( $row = $stmt1->fetch()) {
        $query = "SELECT count(*) FROM $table_withdraw WHERE t_id = '$t_id' and t_division = '$c_no' and t_signdate > $wdate1 and t_address = '$row[0]'";
        $stmt2 = pdo_excute("CHK_3_1:", $query, NULL);
        $count = $stmt2->fetch()[0];
        $with_addr = $withdraw->columns["t_address"];
        if ($row[0] == $with_addr && $count > 2) {
            $withdraw->columns["t_pending"] = 3;
            $withdraw->update();
            $pending3 = true;
            break;
        }
    }
    if ($pending3) 
        continue;
    else {
        //err_log("--------- pass CHK_3");
    }



    //Pending4 :  모든유저들이 동일 코인이 24시간 이내 20건 출금 이후부터 ==> 점검사항 : ip체크(동일 ip출금 일경우 보류)
    $wdate1=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
    $query = "SELECT count(*) FROM $table_withdraw WHERE t_id = '$t_id' and t_division = '$c_no' and t_signdate > $wdate1";
    $stmt1 = pdo_excute("CHK_4:", $query, NULL);
    $count = $stmt1->fetch()[0];
    if ($count >= 20) {
        $withdraw->columns["t_pending"] = 4;
        $withdraw->update();
        continue;
    }

    //Pending5 :  코인출금 시점  출금하는 그 코인의 거래횟수가 5회 이하 (제외 KRWC)   ==>  점검사항 : 아이피체크
    if ($c_coin != "KRWC") {
        $query = "SELECT count(*) FROM coin_point WHERE c_id = '$t_id' and c_div = '$c_no' and c_category in ('tradesell', 'tradebuy')";
        $stmt1 = pdo_excute("CHK_5:", $query, NULL);
        $count = $stmt1->fetch()[0];
        if ($count < 6) {
            $withdraw->columns["t_pending"] = 5;
            $withdraw->update();
            continue;
        } else {
            //err_log("--------- pass CHK_5");
        }
    }


    /*************************************************
    * 
    *  이하 코인 출금 로직 
    **************************************************
    $walletapi = WalletAPI::make($coinInfo->c_type, $coinInfo->name);
    if (is_empty($walletapi)) {
        $withdraw->columns["t_pending"] = 1;
        $withdraw->update();
        continue;
    } 

    $master_wallet = get_master_wallet($coinInfo->type, $coinInfo->name);

    err_log("wallet ==========>count:$count to :$t_address");
    $amount = bcsub($t_ordermost, $t_fees, 8) + 0;
    $r_code = $walletapi->sendTo($coinInfo->name, $master_wallet["account"], $master_wallet["pwd"], $t_address, $amount, "");
    // 에러 일경우 바로 exit 처리됨. ;
    wallet_result_check($coinInfo->name, $r_code);

    $pdo->beginTransaction();
    try {
        // withdraw 업데이트 

        $withdraw->columns["t_check"] = "1";
        $withdraw->columns["t_cont"] = $walletapi->getResult();
        $withdraw->columns["t_duedate"] = $signdate;
        $withdraw->columns["t_fees"] = $t_fees;
        $withdraw->transmit($userInfo, $t_ordermost, $t_fees, $coinInfo->name);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        fatal_log($e->getMessage());
    }
    **************************************************/

}

// 리스트 출력화면으로 이동한다
$LOG_LEVEL = 1;

err_log("============= WITHDRAW: ".var_export($withdraw_count, true));
?>
