<?php


// ------------------------
$WEB_API_URL = "127.0.0.1:7070";
$WALLET_API_URL = "127.0.0.1:8800";
$REDIS_HOST = "172.31.8.177";
$dburl="172.31.8.177";
$dbname="bitzet";
$dbid="root";
$dbpass="bitzet#1234";


$dbconn =mysqli_connect($dburl, $dbid, $dbpass);
mysqli_set_charset($dbconn,"utf8");
$status = mysqli_select_db($dbconn,$dbname);

//// PDO
$db_charset  = 'utf8';
$dsn = "mysql:host=$dburl;dbname=$dbname;charset=$db_charset";
try {
$pdo = new PDO($dsn, $dbid, $dbpass, NULL);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
// ---------------------


include_once "../../Adm/common/init_table.php";
//include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

$LOG_LEVEL = 1;
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

if ($c_type == "0") {
    fatal_log("[$c_coin] Not eth, yoc, token type: $c_type, name:$c_coin");
    exit;
}

$walletapi = WalletAPI::make(1, "ETH");

$query = "SELECT * FROM m_member where m_signdate > 1534973524 order by m_no asc";
$stmt = pdo_excute("member", $query, NULL);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $name = "ETH";  

    $userid = $row["m_id"];
    $phone = $row["m_handphone2"];
    //$phone1 = $row["m_handphone2"];

    $phone = substr($phone,-4);
    $password = $userid."@".$phone;

    $api = "http://127.0.0.1:8800/ETH/requestGasPrice?path=UTC--2018-12-17T05-35-56.766000000Z--5e769f99a275ad689a16becee5b25ed70bacbe98.json&password=$password";

    $ch = make_curl2($api, NULL);
    err_log("getDepositList :$name: curl '$api'");

    $results = curl_exec($ch);
    if ($results === false) {
        fatal_log("getDepositList:code:".curl_error($ch).">  curl '$api' ");
        $r_code = ERR_NETWORK;
    } else {
        $r_code = SUCCEED;
    }
    // eth, token 모두 가져 온다. 
    // 
    wallet_result_check($c_coin, $r_code);
    if ($r_code == ERR_INVALID_PARAM )  {
        fatal_log("$r_code: ERR_INVALID_PARAM:".__LINE__);
        exit;
    }
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

    $coin_name = $item["type"];
    $coin_type = ($coin_name == "ETH" || $coin_name == "YOC" ? "1" : "2" );

    $user_id = UserInfo::getIdFromWallet($coin_type, $coin_name, $item["wallet"]);

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

