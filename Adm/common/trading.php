<?php

//$DEFINE_DEFAULT_COIN = "0";
function conver_to_float($number) {
    $ret = sprintf('%.8f', $number);
    $ret = rtrim($ret, 0);
    $ret= rtrim($ret, '.');
    return $ret;
}

function check_order_validate($market_type, $orderCoin, $order_most, $order_price)
{
    $most = $order_most;

    if ( $market_type == "1" && $order_price <= 0) {
        fatal_log("INVALID_ORDER_INFO: limit price => order_price:$order_price, most:$order_most");
        throw new Exception("INVALID_ORDER_INFO", __LINE__);
    }

    if ($order_most <= 0) {
        fatal_log("INVALID_ORDER_INFO: $order_price, most:$order_most");
        throw new Exception("INVALID_ORDER_INFO", __LINE__);
    }


    if ($orderCoin->max_limit > 0 && $most > $orderCoin->max_limit) {
        //throw new Exception("최대거래수량 초과[" . $most . ">" . $orderCoin->max_limit . "]", __LINE__);
        err_log("MAXIMUM_QUANTITY_OVER[" . $most . ">" . $orderCoin->max_limit . "]\n");
        throw new Exception("MAXIMUM_QUANTITY_OVER", __LINE__);
    }

    if ($most < $orderCoin->min_limit) {
        //throw new Exception("최소거래수량 미달[" . $most . "<" . $orderCoin->min_limit . "]", __LINE__);
        err_log("MINIMUM_QUANTITY_UNDER[" . $most . "<" . $orderCoin->min_limit . "]\n");
        throw new Exception("MINIMUM_QUANTITY_UNDER", __LINE__);
    }

}

function get_coin_unit($c_no, $c_pay) {
    global $table_setup;

    $query = "SELECT B.m_unit as unit FROM $table_setup A";
    $query .= " LEFT JOIN m_setup B ON A.c_no=B.m_div";
    $query .= " WHERE B.m_pay = ? and  A.c_no = ?";
    $pdo_in = [$c_pay, $c_no];
    $stmt = pdo_excute("select1", $query, $pdo_in);

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row["unit"];
}
function get_market_info($c_no, $c_pay) {
    global $table_setup;

    $query = "SELECT B.m_unit as unit, B.m_limit as c_limit,B.m_rank,B.m_use  FROM $table_setup A";
    $query .= " LEFT JOIN m_setup B ON A.c_no=B.m_div";
    $query .= " WHERE B.m_pay = ? and  A.c_no = ?";
    $pdo_in = [$c_pay, $c_no];
    $stmt = pdo_excute("market_info", $query, $pdo_in);

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function isvalid_orderprice_unit($unit, $order_price) {
    $mod = bcmod($order_price, $unit, 8);
    err_log("check orderp:$order_price, unit:$unit");
    return $mod == 0 ? true : false;
}

function miner_ask_range($order_type, $pay_type) {

    $query = "(SELECT 'sell', b_orderprice  FROM b_coinordersell";
    $query .= " WHERE b_div = ? and b_pay = ? and b_state <> 'com' and b_delete = '0' and b_market <> '2'";
    $query .= " order by b_orderprice asc,b_no asc limit 1)";
    $query .= " UNION ALL ";
    $query .= "(SELECT 'buy', b_orderprice  FROM b_coinorderbuy";
    $query .= " WHERE b_div = ? and b_pay = ? and b_state <> 'com' and b_delete = '0' and b_market <> '2' ";
    $query .= " order by b_orderprice desc,b_no asc limit 1)";
    $pdo_in = [$order_type, $pay_type, $order_type, $pay_type];

    err_log(__FUNCTION__.":order:$order_type, pay:$pay_type");

    try {
        $stmt = pdo_excute(__FUNCTION__, $query, $pdo_in);
    } catch (PDOException $e) {
        err_log($e->getMessage()) ;
    }
    $price = array("sell" => 0, "buy" => 0);
    while ($row = $stmt->fetch()){
        if (!is_empty($row[0])) {
            $price[$row[0]] = (is_empty($row[1]) ? 0 : $row[1]);
        }
    }
    //$price = array("high" => $pricehigh_, "low" => $pricelow_, "last1" => $pricelast_, "last2" =>  $price2last_, "volume" => $price_vol);

    err_log(__FUNCTION__.":".var_export($price, true));
    return $price;

}
function miner_amount_range_random($order_type, $pay_type) {

    if ($order_type == "1") return rand(2, 40)/100; // BTC  
    if ($order_type == "2") return rand(1, 40) / 10; // ETH  
    if ($order_type == "7") return rand(3, 100) / 10; // LTC
    if ($order_type == "13") return rand(1, 50) / 10; // BCH  
    if ($order_type == "14") return rand(1, 30);  // BTG
    if ($order_type == "36") return rand(100, 1000); // XRP  
    if ($order_type == "39") return rand(5, 100);  /// QTUM
    if ($order_type == "41") return rand(50, 1000);  // RVN 
    if ($order_type == "43") return rand(5, 100);  // EOS
    if ($order_type == "44") return rand(50, 1000);  /// ENJ
    if ($order_type == "45") return rand(500, 10000);  /// TRON
    if ($order_type == "49") return rand(10, 1000);  /// CXST
    if ($order_type == "51") return rand(200, 10000);  /// IPSC
    if ($order_type == "53") return rand(100, 5000);  /// WWW
    if ($order_type == "54") return rand(100, 5000);  /// AF1

    if ($pay_type == "0") { 
    } else if ($pay_type == "1") { 
    } else if ($pay_type == "2") { 
    } else if ($pay_type == "3") { 
    } else if ($pay_type == "4") { 
    }
    return null;
}

function miner_amount_range($order_type, $pay_type) {

    $amount = array("min" => 0, "max" => 0);

    if ($order_type == "9" && $pay_type == "3") {
        $amount["min"] = 3000;
        $amount["max"] = 30000;
    } else if ($order_type == "35" && $pay_type == "3") {
        $amount["min"] = 1000;
        $amount["max"] = 20000;
    } else if ($order_type == "36" && $pay_type == "3") {
        $amount["min"] = 50;
        $amount["max"] = 500;
    } else if ($order_type == "13" && $pay_type == "3") {
        $amount["min"] = 0.1;
        $amount["max"] = 3;
    } else  {
        return null;
    }

    return $amount;

}
function suspend_yn_state($coin_type, $pay_type) {
    global $m_setup_tb;

    $q = "SELECT m_suspend_yn FROM $m_setup_tb WHERE m_div = $coin_type and m_pay = '$pay_type'";
    $stmt = pdo_excute("suspend_state", $q, null);
    $state = $stmt->fetch()[0];
    return $state;
}
function coin_getlast($order_type, $pay_type) {
    global $table_point;

    $today = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); // 현재날짜 현재시간

    $q = " (SELECT 'last1' as r_no, c_return FROM $table_point";
    $q .= " WHERE c_category = 'tradebuy' AND c_div = ? and c_pay=?";
    $q .= " and c_signdate < ? order by c_no desc limit 1)"; // 1day last
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $today;

    $q .= " order by r_no asc";  // volume

    err_log("change24_get:order:$order_type, pay:$pay_type");

    try {
        $stmt = pdo_excute(__FUNCTION__, $q, $pdo_in);
    } catch (PDOException $e) {
        err_log($e->getMessage()) ;
    }
    $price = array("high" => 0, "low" => 0, "last1" => 0, "last2" =>  0, "volume" => 0);
    while ($row = $stmt->fetch()){
        if (!is_empty($row[0])) {
            $price[$row[0]] = (is_empty($row[1]) ? 0 : $row[1]);
        }
    }
    //$price = array("high" => $pricehigh_, "low" => $pricelow_, "last1" => $pricelast_, "last2" =>  $price2last_, "volume" => $price_vol);

    err_log("change24_getlast:".var_export($price, true));
    return $price;

}

function change24_get_last($order_type, $pay_type)
{
    global $table_point;

    $today = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); // 현재날짜 현재시간
    $yesterday = mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 1, date("Y")); // 어제날짜 현재시간

    $pdo_in = array();
    $q = " (SELECT 'last1' as r_no, c_return FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' )  AND c_div = ? and c_pay=?";
    $q .= " and c_signdate < ? order by c_no desc limit 1)"; // 1day last
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $today;

    $q .= " union all";

    $q .= " (SELECT 'last2' as r_no, c_return FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' ) AND c_div = ? and c_pay=?";
    $q .= " and c_signdate < ? order by c_no desc limit 1)";  // 2day last
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $yesterday;

    $q .= " order by r_no asc";  // volume

    err_log(__FUNCTION__.":order:$order_type, pay:$pay_type");

    try {
        $stmt = pdo_excute(__FUNCTION__, $q, $pdo_in);
    } catch (PDOException $e) {
        err_log($e->getMessage()) ;
    }
    $price = array("high" => 0, "low" => 0, "last1" => 0, "last2" =>  0, "volume" => 0);
    while ($row = $stmt->fetch()){
        if (!is_empty($row[0])) {
            $price[$row[0]] = (is_empty($row[1]) ? 0 : $row[1]);
        }
    }
    //$price = array("high" => $pricehigh_, "low" => $pricelow_, "last1" => $pricelast_, "last2" =>  $price2last_, "volume" => $price_vol);

    err_log(__FUNCTION__.":".var_export($price, true));
    return $price;
}

function change24_get($order_type, $pay_type)
{
    global $dbconn;
    global $table_point;

    $today = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); // 현재날짜 현재시간
    $yesterday = mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 1, date("Y")); // 어제날짜 현재시간
    $day2ago = mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 2, date("Y")); // 이틀전   현재날짜

    $pdo_in = array();
    $q = "(SELECT 'high' as r_no, MAX(c_return+0) FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' ) AND c_div=? and c_pay=?";
    $q .= " and c_signdate > ? and c_signdate < ?)";  // max
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $yesterday; $pdo_in[] = $today;

    $q .= " union all";

    $q .= " ( SELECT 'low' as r_no, MIN(c_return+0) FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' ) AND c_div = ? and c_pay=?";
    $q .= " and c_signdate > ? and c_signdate < ?)"; // min
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $yesterday; $pdo_in[] = $today;

    $q .= " union all";

    $q .= " (SELECT 'last1' as r_no, c_return FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' ) AND c_div = ? and c_pay=?";
    $q .= " and c_signdate < ? order by c_no desc limit 1)"; // 1day last
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $today;

    $q .= " union all";

    $q .= " (SELECT 'last2' as r_no, c_return FROM $table_point";
    $q .= " WHERE (c_category = 'tradebuy' or c_category = 'tradesell' ) AND c_div = ? and c_pay=?";
    $q .= " and c_signdate < ? order by c_no desc limit 1)";  // 2day last
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $yesterday;

    $q .= " union all";

    $q .= " (SELECT 'volume' as r_no, -1*SUM(c_payment+0)*2 as c_return  FROM $table_point";
    $q .= " WHERE c_category = 'tradebuy' AND c_div = ? and c_pay=?";
    $q .= " and c_signdate > ? and c_signdate < ?)"; // volume 은 매수/매도 양쪽으로 포함하므로 2배로 처리  
    $pdo_in[] = $order_type; $pdo_in[] = $pay_type; $pdo_in[] = $yesterday; $pdo_in[] = $today;

    $q .= " order by r_no asc";  // volume

    err_log("change24_get:order:$order_type, pay:$pay_type");

    try {
        $stmt = pdo_excute(__FUNCTION__, $q, $pdo_in);
    } catch (PDOException $e) {
        err_log($e->getMessage()) ;
    }
    $price = array("high" => 0, "low" => 0, "last1" => 0, "last2" =>  0, "volume" => 0);
    while ($row = $stmt->fetch()){
        if (!is_empty($row[0])) {
            $price[$row[0]] = (is_empty($row[1]) ? 0 : $row[1]);
        }
    }
    //$price = array("high" => $pricehigh_, "low" => $pricelow_, "last1" => $pricelast_, "last2" =>  $price2last_, "volume" => $price_vol);

    err_log("change24_get:".var_export($price, true));
    return $price;
}


class UserInfo
{
    public $no;
    public $id;
    public $ip;
    public $row;

    private function __construct() { }

    public static function makeWithNo($userno, $ip)
    {
        $instance = new UserInfo();
        $instance->id = "";
        $instance->no = $userno;
        $instance->ip = $ip;
        $result = $instance->get_userinfo($instance->no, $instance->id);
        return ($result < 0 ? "" : $instance);
    }

    public static function makeWithId($userid, $ip)
    {
        $instance = new UserInfo();
        $instance->id = $userid;
        $instance->no = "";
        $instance->ip = $ip;
        $result = $instance->get_userinfo($instance->no, $instance->id);

        return ($result < 0 ? "" : $instance);
    }

    public function getPassword($coin_name) {
        global $dbname;
        //$phone = substr($this->row["m_handphone"], -4);
        //if ($this->id == "kfcs2002@gmail.com" || $this->id == "plutok@hanmail.net") { return "b_it".$this->id.$this->no; }
        return $dbname."_".$this->id."@".$this->no;
    }
    public function getAccount($c_type, $coin_name) {
        if ($c_type == "1") { // ether
            if ($coin_name == "ETH")
                return trim($this->row["m_ethfile"]);
            else if ($coin_name == "CELO")
                return trim($this->row["m_celofile"]);
            else {
                 throw new Exception("NOT_FOUND_COIN", __LINE__);
            }

        } else if ( $c_type == "2") {  // token
            return trim($this->row["m_tokenfile"]);

        } else if ( $c_type == "5") {  // tron
            return trim($this->row["m_trxaddr"]);

        } else if ( $c_type == "7") {  // celo token
            return trim($this->row["m_celofile"]);

        } else { // alt coin
            global $dbname;
            return $dbname."_".$this->id."@".$this->no;
        }
    }
    public static function getIdFromWallet($c_type, $c_coin, $wallet) {
        if ($c_type == "1") { // 
            $field = ($c_coin == "ETH" ? "m_ethfile" : "m_celofile");
            $q = "SELECT m_id from m_member WHERE $field = '$wallet'";
            $stmt = pdo_excute("getIdFromWallet", $q, NULL);
            $row = $stmt->fetch();
            return $row[0];

        } else if ($c_type == "2") { 
            $q = "SELECT m_id from m_member WHERE m_tokenfile = '$wallet'";
            $stmt = pdo_excute("getIdFromWallet", $q, NULL);
            $row = $stmt->fetch();
            return $row[0];

        } else if ($c_type == "5") { 
            $q = "SELECT m_id from m_member WHERE m_trxaddr = '$wallet'";
            $stmt = pdo_excute("getIdFromWallet", $q, NULL);
            $row = $stmt->fetch();
            return $row[0];

        } else if ($c_type == "7") { 
            $q = "SELECT m_id from m_member WHERE m_celofile = '$wallet'";
            $stmt = pdo_excute("getIdFromWallet", $q, NULL);
            $row = $stmt->fetch();
            return $row[0];
        }
        return "";
    }
    public static function getIdFromAccount($c_type, $account) {
        global $dbname;
        if ($c_type == "0" || $c_type == "6") { //  Alt, Alt2
	        $match = array();
            preg_match("/^".$dbname."_(.*?)@\d+$/", $account, $match);
            err_log("match:".var_export($match, true));
            $id = $match[1];
            return $id;
        }
        return "";
    }
    
    public function getCommission($userBank, $coin_name, $pay_name) {
        $m_feetype = $userBank->userInfo->row["m_feetype"];
        if($m_feetype == "1") {
            return array(99,0.0);
        }
        
        $m_contury = $userBank->userInfo->row["m_contury"];
        if (strpos($m_contury, '81') !== false) {
            if ($coin_name == "BTC" || $coin_name == "ETH" || $pay_name == "BTC" || $pay_name == "ETH" ) {
                return array(0, 0.03);
            }
        }

        $balance = $userBank->get_user_bank("BCS");
        if ($balance->total >= 100000) {
            return array(8, 0.0005);
        } else if ($balance->total >= 50000) {
            return array(7, 0.0006);
        } else if ($balance->total >= 30000) {
            return array(6, 0.0007);
        } else if ($balance->total >= 20000) {
            return array(5, 0.0008);
        } else if ($balance->total >= 10000) {
            return array(4, 0.0009);
        } else if ($balance->total >= 5000) {
            return array(3, 0.001);
        } else if ($balance->total >= 2000) {
            return array(2, 0.0015);
        } else {
            return array(1, 0.0025);
        }
    }

    private function get_userinfo($userno, $userid)
    {
        global $member;

        if (is_empty($userno) && is_empty($userid)) {
            //throw new Exception(__CLASS__ . ":failed to get userinfo[no:$userno, id:$userid]", __LINE__);
            return -1;
        }

        if (is_empty($userno)) {
            $query = "SELECT * FROM $member WHERE m_id=?";
            $pdo_in[] = $userid;
        } else if (is_empty($userid)) {
            $query = "SELECT * FROM $member WHERE m_userno=?";
            $pdo_in[] = $userno;
        } else {
            //throw new Exception(__CLASS__ . ":get_userinfo param assert[" . $query . "]", __LINE__);
            return -2;
        }


        try {
            $stmt = pdo_excute(__FUNCTION__, $query, $pdo_in);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row["m_id"];
            $this->no = $row["m_userno"];

            foreach($row as $key => $value) {
                $this->row[$key] = $value;
            }
            //err_log("===> get:".var_export($this->columns, true));
            return 0;
        } catch (PDOException $e) {
            err_log(__FUNCTION__.":".$e->getMessage());
            return -2;
        }
    }


}

class Balance
{
    public $type;
    public $total;
    public $use;
    public $rest;
    public function __construct()
    {
        $this->type = "0";
        $this->total = "0";
        $this->use = "0";
        $this->rest = "0";
    }
}

class UserBank
{
    public $userInfo; // UserInfo
    //public $balance;        // Balance

    public function __construct($userInfo)
    {
        if (is_empty($userInfo)) {
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param [ id:$userInfo]", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param [ id:$userInfo]:\n");
            throw new Exception("FAIL_FIND_USER", __LINE__);
        }
        $this->userInfo = $userInfo;

        //$this->balance = $this->get_user_currency($userInfo->id);
    }

    public static function insert_bankmoney($userInfo, $c_balance, $pay_type, $m_no1, $cat1, $cat2, $fee)
    {
        // k = k_balance, c = c_balance, m_no1 : coin_point no,
        global $dbconn;
        global $m_bankmoney;
        //$m_bankmoney = "m_bankmoney1";
        global $signdate;


        $c_type = $c_balance->type;
        $c_total = conver_to_float($c_balance->total);
        $c_use = conver_to_float($c_balance->use);
        $c_rest = conver_to_float($c_balance->rest);

        $query = "INSERT INTO $m_bankmoney";
        $query .= "(m_div, m_userno, m_id, m_cointotal, m_coinuse, m_restcoin, m_signdate, m_coin_no, m_no1, m_category, m_category2, m_fee) ";
        $query .= "VALUES ";
        $query .= "(?,?,?,?,?,?,?,?,?,?,?,?);";
        $pdo_in = [$c_type, $userInfo->no, $userInfo->id, $c_total, $c_use, $c_rest, $signdate, $pay_type, $m_no1, $cat1, $cat2, $fee] ;

        try {
            $stmt = pdo_excute(__FUNCTION__, $query, $pdo_in);
        } catch (PDOException $e) {
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":지갑정보 저장 실패[" . $query . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        return "0";
    }

    public static function get_user_bank1($userid, $coin_type)
    {
        if (is_empty($coin_type)) {
            //throw new Exception(__CLASS__.__FUNCTION__ . ":Inpuat param [ coin_type:$coin_type]", __LINE__);
            fatal_log(__CLASS__.__FUNCTION__ . ":Inpuat param [ coin_type:$coin_type]\n");
            throw new Exception("FAIL_FIND_COIN", __LINE__);
        }

        global $m_bankmoney;

        // coin info
        if (is_numeric($coin_type)) {
            $q = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id=? and m_div=? order by m_no desc limit 1";
            $pdo_id = [$userid, $coin_type];
        } else  {
            $q = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id=? and m_div=(SELECT c_no FROM c_setup WHERE c_coin = ?) order by m_no desc limit 1";
            $pdo_id = [$userid, $coin_type];
        }
        err_log(__CLASS__ . "." . __FUNCTION__ . ":\n$q\n");

        try {
            $stmt = pdo_excute(__CLASS__.__FUNCTION__, $q, $pdo_id);
        }catch (PDOException $e) {
            err_log("지갑정보(코인) 조회 실패[" . $q . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        $row = $stmt->fetch();

        $a = new Balance();
        $a->type = $coin_type;
        if (is_empty($row[0])) {
            $a->total = "0";
            $a->use = "0";
            $a->rest = "0";
        } else {
            $a->total = $row[0];
            $a->use = $row[1];
            $a->rest = bcsub($a->total, $a->use, 8);
        }

        return $a;
    }

    public function get_user_bank($coin_type)
    {
        if (is_empty($coin_type)) {
            //throw new Exception(__CLASS__.__FUNCTION__ . ":Inpuat param [ coin_type:$coin_type]", __LINE__);
            fatal_log(__CLASS__.__FUNCTION__ . ":Inpuat param [ coin_type:$coin_type]\n");
            throw new Exception("FAIL_FIND_COIN", __LINE__);
        }

        global $m_bankmoney;

        $userid = $this->userInfo->id;
        // coin info
        if (is_numeric($coin_type)) {
            $q = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id=? and m_div=? order by m_no desc limit 1";
            $pdo_id = [$userid, $coin_type];
        } else  {
            $q = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id=? and m_div=(SELECT c_no FROM c_setup WHERE c_coin = ?) order by m_no desc limit 1";
            $pdo_id = [$userid, $coin_type];
        }
        err_log(__CLASS__ . "." . __FUNCTION__ . ":\n$q\n");

        try {
            $stmt = pdo_excute(__CLASS__.__FUNCTION__, $q, $pdo_id);
        }catch (PDOException $e) {
            err_log("지갑정보(코인) 조회 실패[" . $q . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        $row = $stmt->fetch();

        $a = new Balance();
        $a->type = $coin_type;
        if (is_empty($row[0])) {
            $a->total = "0";
            $a->use = "0";
            $a->rest = "0";
        } else {
            $a->total = $row[0];
            $a->use = $row[1];
            $a->rest = bcsub($a->total, $a->use, 8);
        }

        return $a;
    }

}

class CoinInfo
{
    public $type;
    public $name;
    public $status;
    public $wcommision;
    public $min_limit;
    public $max_limit;
    public $unit;
    public $title;
    public $since;
    public $quantity;
    public $site;
    public $wpaper;
    public $introduce;
    public $use;
    public $suspend_yn;
    public $suspend_reason;
    public $c_type;
    public $limit_in;
    public $limit_out;
    public $row;
    public $img;
    public $fees;
    public $lasttx;
    public $owner;

    public function __construct($coin_type, $pay_type = '') // c_no , or c_coin , name
    {
        if (is_empty($coin_type)) {
            fatal_log(__CLASS__.__FUNCTION__ . ":FAIL_FIND_COIN:".var_export($coin_type, true));
            //throw new Exception(__CLASS__.__FUNCTION__ . ":invalid inpuat param[coin_type:$coin_type]", __LINE__);
            throw new Exception("FAIL_FIND_COIN", __LINE__);
        }
        $this->get_coininfo($coin_type, $pay_type);
    }

    protected function get_coininfo($coin_type, $pay_type)
    {
        global $table_setup;
        global $dbconn;

        if (is_empty($pay_type)) {
            if (is_numeric($coin_type)) {
                $query = "SELECT c_coin, c_wcommission, c_limit, c_asklimit,c_no, c_unit, c_title,";
                $query .= " c_since, c_quantity, c_site, c_wpaper, c_introduce,";
                $query .= " c_use, c_suspend_yn, c_suspend_reason,c_type, c_limit_in, c_limit_out, c_img,c_fees,c_lasttx,c_owner FROM $table_setup";
                $query .= " WHERE c_no = ?";
            } else {
                $query = "SELECT c_coin, c_wcommission, c_limit, c_asklimit,c_no, c_unit, c_title,";
                $query .= " c_since, c_quantity, c_site, c_wpaper, c_introduce,";
                $query .= " c_use, c_suspend_yn, c_suspend_reason,c_type,c_limit_in, c_limit_out, c_img,c_fees,c_lasttx,c_owner FROM $table_setup";
                $query .= " WHERE c_coin = ?";
            }
            $pdo_id = [$coin_type];
        } else {
            if (is_numeric($coin_type)) {
                $query = "SELECT c_coin, c_wcommission, c_limit, c_asklimit,c_no, c_unit, c_title,";
                $query .= " c_since, c_quantity, c_site, c_wpaper, c_introduce,";
                $query .= " c_use, c_suspend_yn, c_suspend_reason,c_type, c_limit_in, c_limit_out, c_img,c_fees,c_lasttx,c_owner,m_use,m_unit,m_limit FROM $table_setup A";
                $query .= " JOIN m_setup B on A.c_no = B.m_div";
                $query .= " WHERE c_no = ? and m_pay = ?";
            } else {
                $query = "SELECT c_coin, c_wcommission, c_limit, c_asklimit,c_no, c_unit, c_title,";
                $query .= " c_since, c_quantity, c_site, c_wpaper, c_introduce,";
                $query .= " c_use, c_suspend_yn, c_suspend_reason,c_type, c_limit_in, c_limit_out, c_img,c_fees,c_lasttx,c_owner,m_use,m_unit,m_limit FROM $table_setup A";
                $query .= " JOIN m_setup B on A.c_no = B.m_div";
                $query .= " WHERE c_coin = ? and m_pay = ?";
            }
            $pdo_id = [$coin_type, $pay_type];
        }



        try {
            $stmt = pdo_excute(__FUNCTION__, $query, $pdo_id);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                fatal_log("FAIL_FIND_COIN:$coin_type, pay:$pay_type");
                throw new Exception("FAIL_FIND_COIN", __LINE__);
            }
            //foreach($row as $key => $value) { $this->row[$key] = $value; }
        }catch (PDOException $e) {
            fatal_log("Failed to find coin.");
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        $this->name = $row["c_coin"]; // 코인이름(약자)
        $this->wcommision = $row["c_wcommission"]; // 수수료율
        $this->min_limit = is_empty($pay_type) ? $row["c_limit"] : $row["m_limit"]; // 최소거래수량
        $this->max_limit = $row["c_asklimit"]; // 최대거래수량
        $this->type = $row["c_no"]; //  타입 
        $this->unit = is_empty($pay_type) ? $row["c_unit"] :  $row["m_unit"];
        $this->title = $row["c_title"]; //  
        $this->since = $row["c_since"]; //  
        $this->quantity = $row["c_quantity"]; //  
        $this->site = $row["c_site"]; //  
        $this->wpaper = $row["c_wpaper"]; //  
        $this->introduce = $row["c_introduce"]; // 
        $this->use = is_empty($pay_type) ? $row["c_use"] :  $row["m_use"]; //  
        $this->suspend_yn = $row["c_suspend_yn"]; //  
        $this->suspend_reason = $row["c_suspend_reason"]; //  
        $this->c_type = $row["c_type"]; //  
        $this->limit_in = $row["c_limit_in"]; //  
        $this->limit_out = $row["c_limit_out"]; //  
        $this->img = $row["c_img"];
        $this->fees = $row["c_fees"];
        $this->lasttx = $row["c_lasttx"];
        $this->owner = $row["c_owner"];
    }

    public function setLastTx($txid) {
        global $table_setup;

        $query = "UPDATE $table_setup SET c_lasttx = '$txid' WHERE c_no = $this->type";
        $stmt = pdo_excute(__FUNCTION__, $query, NULL);
    }

}
