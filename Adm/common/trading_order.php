<?php

class Order
{
    public $market_type; // 현재 매매 하려는 가격
    public $order_most; // 현재 매매 하려는 수량.
    public $order_price; // 현재 매매 하려는 가격
    public $order_total; // 현재 매매 하려는 가격
    public $order_fees; // 주문 수수료.
    public $order_remain; // 매매 하려는 수량 남은것.
}

abstract class OrderInfo
{
    public $no; //  order 넘버
    public $state; //   매매 상태 (com, wait, part)
    public $delete; //  삭제 상태
    public $market_type; // 매매 방식

    public $userInfo; // UserInfo
    public $userBank; // UserBank

    public $orderCoin; // CoinInfo 정보.
    public $payCoin; // CoinInfo 정보

    //public $order; // Order;

    public $order_most; // 현재 주문 하려는 수량.
    public $order_price; // 현재 주문 하려는 가격
    public $order_total; // 현재 주문 하려는 총합.
    public $order_fees; // 현재 주문 수수료.
    public $order_remain; // 매매 하려는 수량 남은것.
    public $close_most; // 이미 거래된 수량.

    //public $pay_most; // 지불 해야하는수량

    abstract public function registCoinOrder($market_type, $ordermost, $orderprice);
    abstract public function tradeCoinOrder($most);

    protected function __construct($userInfo, $userBank, $orderCoin, $payCoin)
    {
        if (is_empty($userInfo) || is_empty($userBank) || is_empty($orderCoin) || is_empty($payCoin)) {
            //err_log(var_export($userInfo, true));
            //err_log(var_export($userBank, true));
            //err_log(var_export($orderCoin, true));
            //err_log(var_export($payCoin, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param\n");
            throw new Exception("INVALID_TRADE_INFO", __LINE__);
        }

        $this->userInfo = $userInfo;
        $this->userBank = $userBank;
        $this->orderCoin = $orderCoin;
        $this->payCoin = $payCoin;
    }

    protected function check_validate($order_most)
    {
        $orderCoin = $this->orderCoin;

        $most = $order_most;
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

    protected function insert_coinorder($order_type, $orderInfo)
    {
        global $dbconn;
        global $table_orderbuy;
        global $table_ordersell;
        global $signdate;



        $userInfo = $orderInfo->userInfo;
        $orderCoin = $orderInfo->orderCoin;
        $payCoin = $orderInfo->payCoin;

        // check parameter.
        if (is_empty($userInfo) || is_empty($orderCoin) || is_empty($payCoin)) {
            //err_log(var_export($userInfo, true));
            //err_log(var_export($orderCoin, true));
            //err_log(var_export($payCoin, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param:", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param:\n");
            throw new Exception("INVALID_TRADE_INFO", __LINE__);
        }
        if (is_empty($orderInfo->order_most) || is_empty($orderInfo->order_fees) || is_empty($orderInfo->order_price)) {
            //err_log(var_export($orderInfo, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param:", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":Inpuat param:\n");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }

        $b_no1 = "";
        $b_state = "wait";
        $table_order = ($order_type == "sell" ? $table_ordersell : $table_orderbuy);

        $order_most = conver_to_float($orderInfo->order_most);
        $order_fees = conver_to_float($orderInfo->order_fees);
        $order_price = conver_to_float($orderInfo->order_price);
        $order_total = conver_to_float($orderInfo->order_total);

        $query = "INSERT INTO $table_order";
        $query .= "(b_div, b_pay, b_state, b_ordermost, b_orderfees, b_closecost, b_closefees, b_orderprice";
        $query .= ", b_pricetotal, b_closeprice, b_closetotal, b_no1";
        $query .= ", b_userno, b_id, b_closedate, b_delete, b_ip, b_signdate, b_market) ";
        $query .= "VALUES ";
        $query .= "(?,?,?,?,?,'','',?";
        $query .= ",?,'','',?,?,?,'','0',?,?,?);";

        $pdo_in = [$orderCoin->type, $payCoin->type, $b_state, $order_most, $order_fees, $order_price, $order_total,
            $b_no1, $userInfo->no, $userInfo->id, $userInfo->ip, $signdate, $orderInfo->market_type];

        try {
            $stmt = pdo_excute(__FUNCTION__, $query, $pdo_in);

            $query = "SELECT LAST_INSERT_ID()";
            $stmt = pdo_excute(__FUNCTION__, $query, "");
            $row = $stmt->fetch();
            if (!$row) {
                throw new Exception("QUERY_ERROR", __LINE__);
            }
            $this->no = $row[0];
        } catch (PDOException $e) {
            err_log("주문정보 ID 조회 실패[" . $query . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        return $this->no;
    }

    protected function modify_coinorder($order_type, $no, $state, $most, $price, $total, $fees, $coinpoint_no)
    {
        global $table_orderbuy;
        global $table_ordersell;
        global $signdate;

        $table_order = ($order_type == "sell" ? $table_ordersell : $table_orderbuy);

        $q = "UPDATE $table_order SET ";
        $q .= "b_state = ?,";
        $q .= "b_closecost = b_closecost + '$most',";
        $q .= "b_closefees = b_closefees + '$fees',";
        $q .= "b_closeprice = ?,";
        $q .= "b_closetotal = b_closetotal + '$total',";
        $q .= "b_no1 = CONCAT('$coinpoint_no,',b_no1 ),";
        $q .= "b_closedate = ? ";
        $q .= "WHERE b_no = ?";

        $pdo_in = [$state, $price, $signdate, $no];

        try {
            $stmt = pdo_excute("update_coin_point", $q, $pdo_in);
        } catch (PDOException $e) {
            err_log($e->getMessage());
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        return "0";
    }

    public function register_coinpoint($orderInfo, $state, $orderbuy_no, $exchage_most, $close_price, $ordersell_no, $pay_most, $category, $c_commission)
    {

        $userInfo = $orderInfo->userInfo;
        $orderCoin = $orderInfo->orderCoin;
        $payCoin = $orderInfo->payCoin;

        $c_payment = "0";
        $pay_type = "0";
        $c_category = $category; // 카테고리
        $c_category2 = "";
        $c_no1 = $orderbuy_no;
        $c_no2 = $ordersell_no;

        if ($category == "tradebuy") {
            $cat = ($state == "com" ? " Buy" : " Buy(PART)");
            $c_exchange = conver_to_float($exchage_most + 0) . $orderCoin->name; // 거래 수량 (받은거)
            $c_category2 = $orderCoin->name . $cat; // 카테고리2
            $c_return = $close_price; // 체결가
            $c_payment = "-" . conver_to_float($pay_most + 0) . $payCoin->name; // 지불정보 (준거)   //
            $pay_type = $payCoin->type;

        } else if ($category == "tradesell") {
            $cat = ($state == "com" ? " Sell" : " Sell(PART)");
            $c_exchange = conver_to_float($exchage_most + 0) . $payCoin->name; // 거래 수량 (받은거)
            $c_category2 = $orderCoin->name . $cat; // 카테고리2
            $c_return = $close_price; // 체결가
            $c_payment = "-" . conver_to_float($pay_most + 0) . $orderCoin->name; // 지불정보 (준거)
            $pay_type = $payCoin->type;

        } else {

            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . "- category assert: $category]", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . "- category assert: $category]\n");
            throw new Exception("INVALID_TRADE_INFO", __LINE__);

        }

        // -----------------------------
        global $table_point;
        global $dbconn;
        global $signdate;

        $q = "INSERT INTO $table_point ";
        $q .= "(c_div, c_pay, c_userno, c_id, c_exchange, c_payment, c_commission, c_category, c_category2, c_ip, c_return, c_no1, c_no2, c_signdate) ";
        $q .= "VALUES ";
        //$q .= "('$orderCoin->type','$pay_type','$userInfo->no','$userInfo->id','$c_exchange','$c_payment','$c_category','$c_category2'";
        //$q .= ",'$userInfo->ip','$c_return','$c_no1','$c_no2','$signdate');";
        $q .= "(?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $pdo_in = [$orderCoin->type, $pay_type, $userInfo->no, $userInfo->id, $c_exchange, $c_payment, $c_commission, $c_category, $c_category2,
            $userInfo->ip, $c_return, $c_no1, $c_no2, $signdate];
        try {
            $stmt = pdo_excute(__FUNCTION__, $q, $pdo_in);

            $que_sel_lastid = "SELECT LAST_INSERT_ID();";
            $stmt = pdo_excute(__FUNCTION__, $que_sel_lastid, "");
            $row = $stmt->fetch();
            if (!$row) {
                throw new Exception("QUERY_ERROR", __LINE__);
            }
            $lastid = $row[0];
        } catch (PDOException $e) {
            err_log("구매내역 저장 실패[" . $q . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        return $lastid; // coinpoint_no
    }

    public function update_bankmoney($orderInfo, $m_no1, $exchange_most, $pay_most, $fees, $cat1)
    {
        $userBank = $orderInfo->userBank;
        $userInfo = $orderInfo->userInfo;
        $orderCoin = $orderInfo->orderCoin;
        $payCoin = $orderInfo->payCoin;

        if ($cat1 == "buywait") {
            // BUY - payCoin 은 줄어든다.
            $balance = $userBank->get_user_bank($payCoin->type);
            $balance->use = bcadd($balance->use, $pay_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = "-" . conver_to_float($pay_most + 0) . $payCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $orderCoin->type, $m_no1, "buywait", $increase, 0);

        } else if ($cat1 == "buycancel") {
            // BUY - payCoin 은 늘어 난다.
            $balance = $userBank->get_user_bank($payCoin->type);
            $balance->use = bcsub($balance->use, $pay_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = conver_to_float($pay_most + 0) . $payCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $orderCoin->type, $m_no1, "buycancel", $increase, 0);

        } else if ($cat1 == "tradebuy") {

            // orderCoin 늘어나고,
            $exchange_total = bcsub($exchange_most, $fees, 8);
            $balance = $userBank->get_user_bank($orderCoin->type);
            $balance->total = bcadd($balance->total, $exchange_total, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = conver_to_float($exchange_most + 0) . $orderCoin->name;
            $fees = "-" . conver_to_float($fees + 0) . $orderCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $payCoin->type, $m_no1, "tradebuy", $increase, $fees);

            // payCoin 은 줄어들고.
            $use_most = bcmul($exchange_most, $orderInfo->order_price, 8);
            $balance = $userBank->get_user_bank($payCoin->type);
            $balance->total = bcsub($balance->total, $pay_most, 8);
            $balance->use = bcsub($balance->use, $use_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = "-" . conver_to_float($pay_most + 0) . $payCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $orderCoin->type, $m_no1, "tradepay", $increase, 0);

        } else if ($cat1 == "sellwait") {

            // orderCoin 은 줄어든다.
            $balance = $userBank->get_user_bank($orderCoin->type);
            $balance->use = bcadd($balance->use, $exchange_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = "-" . conver_to_float($exchange_most + 0) . $orderCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $payCoin->type, $m_no1, $cat1, $increase, 0);

        } else if ($cat1 == "sellcancel") {
            // orderCoin 은 늘어 난다.
            $balance = $userBank->get_user_bank($orderCoin->type);
            $balance->use = bcsub($balance->use, $exchange_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = conver_to_float($exchange_most + 0) . $orderCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $payCoin->type, $m_no1, $cat1, $increase, 0);

        } else if ($cat1 == "tradesell") {
            // orderCoin 줄어들고
            //$use_most = bcmul($exchange_most, $orderInfo->order_price, 8);
            $balance = $userBank->get_user_bank($orderCoin->type);
            $balance->total = bcsub($balance->total, $exchange_most, 8);
            $balance->use = bcsub($balance->use, $exchange_most, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = "-" . conver_to_float($exchange_most + 0) . $orderCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $payCoin->type, $m_no1, $cat1, $increase, 0);

            // payCoin 은 늘어나고,
            $pay_total = bcsub($pay_most, $fees, 8);
            $balance = $userBank->get_user_bank($payCoin->type);
            $balance->total = bcadd($balance->total, $pay_total, 8);
            $balance->rest = bcsub($balance->total, $balance->use, 8);
            $increase = ($pay_most + 0) . $payCoin->name;
            $fees = "-" . conver_to_float($fees + 0) . $payCoin->name;
            UserBank::insert_bankmoney($userInfo, $balance, $orderCoin->type, $m_no1, "tradepay", $increase, $fees);

        } else {

            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . "- category assert: $cat1]", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . "- category assert: $cat1]\n");
            throw new Exception("INVALID_TRADE_INFO", __LINE__);
        }
    }

    protected function delete_coinorder($order_type, $no)
    {
        global $dbconn;
        global $table_orderbuy;
        global $table_ordersell;
        global $signdate;

        $table_order = ($order_type == "sell" ? $table_ordersell : $table_orderbuy);

        $q = "UPDATE $table_order SET";
        $q .= " b_delete='1', b_closedate=?";
        $q .= " WHERE b_no = ?";
        $pdo_in = [$signdate, $no];

        try {
            $stmt = pdo_excute(basename(__FILE__), $q, $pdo_in);
        } catch (PDOException $e) {
            err_log("주문 정보 변경 실패[" . $e->getMessage() . "]\n");
            throw new Exception("QUERY_ERROR", 1);
        }

        return "0";
    }

    protected function select_coinorder($order_type, $b_no, $userid)
    {
        global $dbconn;
        global $table_orderbuy;
        global $table_ordersell;

        $table_order = ($order_type == "sell" ? $table_ordersell : $table_orderbuy);

        $query2 = "SELECT b_ordermost,b_orderfees,b_closecost,b_closefees,b_orderprice,b_pricetotal,b_closeprice,b_closetotal,b_div,b_id,b_state, b_delete FROM $table_order";
        $query2 .= " where b_no = ? and b_id = ?";

        $pdo_in = [$b_no, $userid];

        try {
            $stmt = pdo_excute(basename(__FILE__), $query2, $pdo_in);

            $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            err_log("주문 정보 조회 실패[" . $e->getMessage() . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        $this->no = $b_no;
        return $row2;
    }

}

class BuyInfo extends OrderInfo
{
    public static function makeFromNo($no)
    {
        global $table_orderbuy;
        global $dbconn;

        $q = "SELECT b_no, b_div, b_pay, b_userno, b_id, b_ordermost, b_no1, ";
        $q .= "b_closecost, b_pricetotal, b_ip, b_orderprice, b_market, b_state, b_delete FROM $table_orderbuy";
        $q .= " WHERE b_no = ? and b_delete = '0'";
        $pdo_in = [$no];

        try {
            $stmt = pdo_excute(basename(__FILE__), $q, $pdo_in);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $instance = BuyInfo::makeFromTable($row);
            return $instance;
        } catch (PDOException $e) {
            fatal_log("get_orderinfo[" . $e->getMessage() . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        return "";
    }

    public static function makeFromInfo($userInfo, $userBank, $orderCoin, $payCoin)
    {
        $instance = new self($userInfo, $userBank, $orderCoin, $payCoin);
        return $instance;
    }

    // $row = mysql_fetch_assoc($result)
    public static function makeFromTable($row)
    {
        if (is_empty($row)) {
            err_log(var_export($row, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param", __LINE__);
            fatal_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param\n");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }

        $id = $row["b_id"];
        $ip = $row["b_ip"];
        $div = $row["b_div"]; //  coin type
        $pay = $row["b_pay"]; //  pay type
        $userInfo = UserInfo::makeWithId($id, $ip);
        $userBank = new UserBank($userInfo);
        $orderCoin = new CoinInfo($div);
        $payCoin = new CoinInfo($pay);

        if (is_empty($userInfo->no)) {
            fatal_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid userid:$id".__LINE__);
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }
        $instance = new self($userInfo, $userBank, $orderCoin, $payCoin);
        //$instance->make_type = "trade";
        $instance->no = $row["b_no"]; // 인덱스
        $instance->state = $row["b_state"]; // 인덱스
        $instance->delete = $row["b_delete"]; // 인덱스
        $instance->userno = $row["b_userno"]; // 유저no
        $instance->id = $row["b_id"]; // 유저id
        $instance->order_most = $row["b_ordermost"]; // 주문수량
        $instance->no1 = $row["b_no1"]; // 거래내역no
        $instance->close_most = $row["b_closecost"]; // 거래된 수량.
        $instance->ip = $row["b_ip"]; // IP
        $instance->order_price = $row["b_orderprice"]; // 주문 희망 가격
        $instance->order_remain = bcsub($instance->order_most, $instance->close_most, 8); // 남은 수량.
        err_log(__FUNCTION__.":order_remain:".$instance->order_remain);

        return $instance;
    }

    public function registCoinOrder($market_type, $ordermost, $orderprice)
    {
        err_log(__CLASS__ . __FUNCTION__ . ":market:$market_type, most:$ordermost, price:$orderprice");
        $this->market_type = $market_type;
        $this->order_most = $ordermost;
        $this->order_price = $orderprice;
        $this->order_total = bcmul($this->order_most, $this->order_price, 8);
        $this->order_remain = $this->order_most;
        $this->order_fees = bcmul($this->order_most, $this->orderCoin->wcommision, 8);
        //$this->order_fees = bcmul($this->order_total, $this->orderCoin->wcommision, 8);




        if ($orderprice <= 0 || $ordermost <= 0) {
            err_log("INVALID_ORDER_INFO: order_price:$orderprice, most:$ordermost");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }

        $this->check_validate($ordermost);

        $no = parent::insert_coinorder("buy", $this);

        $pay_most = $this->order_total;
        parent::update_bankmoney($this, $this->no, $ordermost, $pay_most, 0, "buywait");

        return $no;
    }

    public function tradeCoinOrder($sellOrder)
    {
        if (is_empty($sellOrder)) {
            //err_log(var_export($sellOrder, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param\n");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }

        //err_log(var_export($sellOrder, true));
        //$this->check_validate($this->orderCoin, $order_most); // 마지막 코인에 대해 order_most 업데이트 ;
        $exchage_most = 0;
        $pay_most = 0;
        $close_price = 0;
        $b_state = "com";
        $s_state = "com";

        err_log(__CLASS__ . __FUNCTION__ . " b_remain:" . $this->order_remain . ", s_remain:$sellOrder->order_remain");
        if ($this->order_remain == $sellOrder->order_remain) {
            $exchage_most = $this->order_remain;
            $close_price = $sellOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8); // 총액.
            $b_state = "com";
            $s_state = "com";
        } else if ($this->order_remain < $sellOrder->order_remain) {
            $exchage_most = $this->order_remain;
            $close_price = $sellOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8); // 총액.
            $b_state = "com";
            $s_state = "part";
        } else if ($this->order_remain > $sellOrder->order_remain) {
            $exchage_most = $sellOrder->order_remain;
            $close_price = $sellOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8); // 총액.
            $b_state = "part";
            $s_state = "com";
        }

        //$buy_fees = bcmul($exchage_most, $this->orderCoin->wcommision, 8);
        //$sel_fees = bcmul($pay_most, $sellOrder->orderCoin->wcommision, 8);

        $buy_commission = ($this->userInfo->getCommission($this->userBank))[1];
        $sel_commission = ($sellOrder->userInfo->getCommission($sellOrder->userBank))[1];
        $buy_fees = bcmul($exchage_most, $buy_commission, 8);
        $sel_fees = bcmul($pay_most, $sel_commission, 8);


        // BUY- coin point 에 orderCoin 매수 내역 추가.
        err_log("buy::register_coinpoint====================");
        $no = parent::register_coinpoint($this, $b_state, $this->no, $exchage_most, $close_price, $sellOrder->no, $pay_most, "tradebuy", $buy_commission);

        // BUY - 주문  내역 업데이트
        err_log("buy::modify_coinorder====================");
        parent::modify_coinorder("buy", $this->no, $b_state, $exchage_most, $close_price, $pay_most, $buy_fees, $no);

        // BUY- bankmoney  orderCoin  코인 증가, payCoin 코인 감소, owner 지갑 증가.
        err_log("buy::update_bankmoney====================");
        parent::update_bankmoney($this, $no, $exchage_most, $pay_most, $buy_fees, "tradebuy");

        // SELL - coin point 에 orderCoin 매도 내역 추가.
        err_log("sell::register_coinpoint====================");
        $no = parent::register_coinpoint($sellOrder, $s_state, $this->no, $pay_most, $close_price, $sellOrder->no, $exchage_most, "tradesell", $sel_commission);

        // SELL 주문  내역 업데이트
        err_log("sell::modify_coinorder====================");
        parent::modify_coinorder("sell", $sellOrder->no, $s_state, $exchage_most, $close_price, $pay_most, $sel_fees, $no);

        // SELL - bankmoney  orderCoin  코인 감소, payCoin 코인 증가
        err_log("sell::update_bankmoney====================");
        parent::update_bankmoney($sellOrder, $no, $exchage_most, $pay_most, $sel_fees, "tradesell");

        $sellOrder->order_remain = bcsub($sellOrder->order_remain, $exchage_most, 8);
        $this->order_remain = bcsub($this->order_remain, $exchage_most, 8);
        return $this->order_remain;
    }

    public function refundCoinOrder()
    {
        // check : 리펀드 가능한 order 상태인지 체크
        // wait or part 상태인가 , delete 가 아닌가 , order_most, close_most 같지 않은가?

        // 주문 처리되고 남은 양으로 pay_most 로 넘긴다.
        $pay_price = bcmul($this->order_remain, $this->order_price, 8);
        //$pay_fees = bcmul($pay_price, $this->orderCoin->wcommision, 8);
        parent::update_bankmoney($this, $this->no, 0, $pay_price, 0, "buycancel");
        parent::delete_coinorder("buy", $this->no);
    }

    protected function check_validate($order_most)
    {
        parent::check_validate($order_most);

        $userBank = $this->userBank;
        $payCoin = $this->payCoin;
        $pay_most = bcmul($this->order_most, $this->order_price, 8);
        $payBalance = $userBank->get_user_bank($payCoin->type);
        if ($payBalance->rest < $pay_most) {
            err_log($s = "보유코인부족[" . $pay_most . ">" . $payBalance->rest . "]" . __LINE__);
            //throw new Exception("FAIL_CHECK_VALIDATE", __LINE__);
            throw new Exception("NOT_ENOUGH_COIN", __LINE__);
        }
    }

}

class SellInfo extends OrderInfo
{
    public static function makeFromInfo($userInfo, $userBank, $orderCoin, $payCoin)
    {
        $instance = new self($userInfo, $userBank, $orderCoin, $payCoin);
        return $instance;
    }

    public static function makeFromNo($no)
    {
        global $table_ordersell;
        global $dbconn;

        $q = "SELECT b_no, b_div, b_pay, b_userno, b_id, b_ordermost, b_no1, ";
        $q .= "b_closecost, b_pricetotal, b_ip, b_orderprice, b_market, b_state, b_delete FROM $table_ordersell";
        $q .= " WHERE b_no = ? and b_delete = '0'";
        $pdo_in = [$no];

        try {
            $stmt = pdo_excute(basename(__FILE__), $q, $pdo_in);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $instance = SellInfo::makeFromTable($row);
            return $instance;
        } catch (PDOException $e) {
            err_log("get_orderinfo[" . $e->getMessage() . "]\n");
            throw new Exception("QUERY_ERROR", __LINE__);
        }

    }

    // $row = mysql_fetch_assoc($result)
    public static function makeFromTable($row)
    {
        if (is_empty($row)) {
            err_log(var_export($row, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param\n");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }
        err_log(__CLASS__ . "." . __FUNCTION__);

        $div = $row["b_div"]; //  coin type
        $pay = $row["b_pay"]; //  pay type
        $id = $row["b_id"]; // 유저id
        $ip = $row["b_ip"]; // IP
        $userInfo = UserInfo::makeWithId($id, $ip);

        if (is_empty($userInfo->no)) {
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }
        $userBank = new UserBank($userInfo);
        $orderCoin = new CoinInfo($div);
        $payCoin = new CoinInfo($pay);

        $instance = new self($userInfo, $userBank, $orderCoin, $payCoin);

        //$instance->no = $row["b_no"]; // 인덱스
        //$instance->userno = $row["b_userno"]; // 유저no
        //$instance->no1 = $row["b_no1"]; // 거래내역no
        $instance->no = $row["b_no"];
        $instance->market_type = $row["b_market"];
        $instance->order_most = $row["b_ordermost"]; // 주문수량
        $instance->order_price = $row["b_orderprice"]; // 주문 희망 가격
        $instance->order_total = $row["b_pricetotal"];
        //$instance->order_fees = $row["b_closefees"];
        $instance->close_most = $row["b_closecost"]; // 거래된 수량.
        $instance->order_remain = bcsub($instance->order_most, $instance->close_most, 8); // 남은 수량.

        //$instance->make_type = "trade";

        return $instance;
    }

    public function registCoinOrder($market_type, $ordermost, $orderprice)
    {
        err_log(__CLASS__ . __FUNCTION__ . ":market:$market_type, most:$ordermost, price:$orderprice");
        $this->market_type = $market_type;
        $this->order_most = $ordermost;
        $this->order_price = $orderprice;
        $this->order_total = bcmul($this->order_most, $this->order_price, 8);
        $this->order_fees = bcmul($this->order_total, $this->orderCoin->wcommision, 8);
        $this->order_remain = $this->order_most;

        if ($orderprice <= 0 || $ordermost <= 0) {
            err_log("INVALID_ORDER_INFO: order_price:$orderprice, most:$ordermost");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }
        $this->check_validate($ordermost);

        $no = parent::insert_coinorder("sell", $this);

        $pay_most = $this->order_total;
        parent::update_bankmoney($this, $this->no, $ordermost, $pay_most, 0, "sellwait");

        return $no;
    }

    public function tradeCoinOrder($buyOrder)
    {
        if (is_empty($buyOrder)) {
            //err_log(var_export($buyOrder, true));
            //throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param", __LINE__);
            err_log(__CLASS__ . ":" . __FUNCTION__ . ":invalid inpuat param\n");
            throw new Exception("INVALID_ORDER_INFO", __LINE__);
        }
        err_log(__CLASS__ . __FUNCTION__);

        //$this->check_validate($this->orderCoin, $order_most); // 마지막 코인에 대해 order_most 업데이트 ;
        $exchage_most = 0;
        $pay_most = 0;
        $b_state = "com";
        $s_state = "com";
        if ($this->order_remain < $buyOrder->order_remain) {
            $exchage_most = $this->order_remain;
            $close_price = $buyOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8);
            $s_state = "com";
            $b_state = "part";
        } else if ($this->order_remain == $buyOrder->order_remain) {
            $exchage_most = $this->order_remain;
            $close_price = $buyOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8);
            $s_state = "com";
            $b_state = "com";
        } else if ($this->order_remain > $buyOrder->order_remain) {
            $exchage_most = $buyOrder->order_remain;
            $close_price = $buyOrder->order_price; // 체결가.
            $pay_most = bcmul($exchage_most, $close_price, 8);
            $s_state = "part";
            $b_state = "com";
        }
        //$sel_fees = bcmul($pay_most, $this->orderCoin->wcommision, 8);
        //$buy_fees = bcmul($exchage_most, $buyOrder->orderCoin->wcommision, 8);

        $sel_commission = ($this->userInfo->getCommission($this->userBank))[1];
        $buy_commission = ($buyOrder->userInfo->getCommission($buyOrder->userBank))[1];
        $sel_fees = bcmul($pay_most, $sel_commission, 8);
        $buy_fees = bcmul($exchage_most, $buy_commission, 8);


        // SELL - coin point 에 orderCoin 매도 내역 추가.
        err_log("sell::register_coinpoint====================");
        $no = parent::register_coinpoint($this, $s_state, $buyOrder->no, $pay_most, $close_price, $this->no, $exchage_most, "tradesell", $sel_commission);

        // SELL - 주문  내역 업데이트
        err_log("sell::modify_coinorder====================");
        parent::modify_coinorder("sell", $this->no, $s_state, $exchage_most, $close_price, $pay_most, $sel_fees, $no);

        // SELL - bankmoney  orderCoin  코인 감소, payCoin 코인 증가
        err_log("sell::update_bankmoney====================");
        parent::update_bankmoney($this, $no, $exchage_most, $pay_most, $sel_fees, "tradesell");

        // BUY - coin point 에 orderCoin 매도 내역 추가.
        err_log("buy::register_coinpoint====================");
        $no = parent::register_coinpoint($buyOrder, $b_state, $buyOrder->no, $exchage_most, $close_price, $this->no, $pay_most, "tradebuy", $buy_commission);

        // BUY - 주문  내역 업데이트
        err_log("buy::modify_coinorder====================");
        parent::modify_coinorder("buy", $buyOrder->no, $b_state, $exchage_most, $close_price, $pay_most, $buy_fees, $no);

        // BUY - bankmoney  orderCoin  코인 증가, payCoin 코인 감소
        err_log("buy::update_bankmoney====================");
        parent::update_bankmoney($buyOrder, $no, $exchage_most, $pay_most, $buy_fees, "tradebuy");

        $buyOrder->order_remain = bcsub($buyOrder->order_remain, $exchage_most, 8);
        $this->order_remain = bcsub($this->order_remain, $exchage_most, 8);
        return $this->order_remain;
    }

    public function refundCoinOrder()
    {
        // check : 리펀드 가능한 order 상태인지 체크
        // wait or part 상태인가 , delete 가 아닌가 , order_most, close_most 같지 않은가?

        // 주문 처리되고 남은 양으로 pay_most 로 넘긴다.
        parent::update_bankmoney($this, $this->no, $this->order_remain, 0, 0, "sellcancel");
        parent::delete_coinorder("sell", $this->no);
    }

    protected function check_validate($order_most)
    {
        parent::check_validate($order_most);

        $userBank = $this->userBank;
        $orderCoin = $this->orderCoin;
        $payCoin = $this->payCoin;
        $order_most = $this->order_most;
        $balance = $userBank->get_user_bank($orderCoin->type);
        if ($balance->rest < $order_most) {
            //global $LOG_LEVEL; $LOG_LEVEL =1;
            err_log($s = "보유코인부족[" . $order_most . ">" . $balance->rest . "]" . __LINE__);
            //throw new Exception("FAIL_CHECK_VALIDATE", __LINE__);
            throw new Exception("NOT_ENOUGH_COIN", __LINE__);
        }

    }

}
