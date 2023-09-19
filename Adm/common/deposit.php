<?php

class Deposit
{
    public $userInfo;
    public $coinInfo;

    public function __construct($userInfo, $coinInfo) { 
        $this->userInfo = $userInfo;
        $this->coinInfo = $coinInfo;
    }

    public function deposit($ordermost, $cause, $txid) {

        //$amount = $ordermost + 0;
        $amount = conver_to_float($ordermost);

        // coin point update.
        $c_no = $this->update_coinpoint($amount, $cause, $txid);


        // bankmoney update.
        $c_type =$this->coinInfo->type;

        $userBank =new UserBank($this->userInfo);
        $balance = $userBank->get_user_bank($c_type);

        $balance->total = bcadd($balance->total, $amount, 8);
        $balance->rest = bcsub($balance->total, $balance->use, 8);

        $balance = UserBank::insert_bankmoney($this->userInfo, $balance, $c_type, $c_no, "deposit", $amount, 0);

    }

    private function update_coinpoint($amount, $cause, $txid) {

        global $signdate;

        $table_point = "coin_point";

        $c_div = $this->coinInfo->type;
        $c_userno = $this->userInfo->no;
        $c_id = $this->userInfo->id;
        $c_ip = $this->userInfo->ip;
        $t_no = "0";

        $amount = conver_to_float($amount);
        $c_name = $this->coinInfo->name;
        $c_exchange = $amount.$c_name;
        $c_payment = "0".$c_name;
        $c_category = "reqorderrecv";
        $c_category2 = is_empty($txid) ? $cause : $txid.",".$cause;


        $query = "INSERT INTO $table_point";
        $query .= "(c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate)";
        $query .= "VALUES";
        $query .= "('',?,?,?,?,?,?,?,?,?,?,?,?)";
        $pdo_in = [$c_div,$c_userno,$c_id,$c_exchange, $c_payment,
                    $c_category,$c_category2,$c_ip,$amount,$t_no,$t_no,$signdate];

        pdo_excute("insert_coinpoint", $query, $pdo_in);

        $query = "SELECT LAST_INSERT_ID()";
        $stmt = pdo_excute(__FUNCTION__, $query, NULL);
        $row = $stmt->fetch();
        if (!$row) {
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        return $row[0];
    }

    public function deposit2($ordermost, $cause) {

        //$amount = $ordermost + 0;
        $amount = conver_to_float($ordermost);

        // coin point update.
        $c_no = $this->update_coinpoint2($amount, $cause);


        // bankmoney update.
        $c_type =$this->coinInfo->type;

        $userBank =new UserBank($this->userInfo);
        $balance = $userBank->get_user_bank($c_type);

        $balance->total = bcadd($balance->total, $amount, 8);
        $balance->rest = bcsub($balance->total, $balance->use, 8);

        $balance = UserBank::insert_bankmoney($this->userInfo, $balance, $c_type, $c_no, "deposit", $amount, 0);

    }

    private function update_coinpoint2($amount, $cause) {

        global $signdate;

        $table_point = "coin_point";

        $c_div = $this->coinInfo->type;
        $c_userno = $this->userInfo->no;
        $c_id = $this->userInfo->id;
        $c_ip = $this->userInfo->ip;
        $t_no = "0";

        $amount = conver_to_float($amount);
        $c_name = $this->coinInfo->name;
        $c_exchange = $amount.$c_name;
        $c_payment = "0".$c_name;
        $c_category = "reqorderrecv";
        $c_category2 = $cause;


        $query = "INSERT INTO $table_point";
        $query .= "(c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate,c_manual)";
        $query .= "VALUES";
        $query .= "('',?,?,?,?,?,?,?,?,?,?,?,?,1)";
        $pdo_in = [$c_div,$c_userno,$c_id,$c_exchange, $c_payment,
                    $c_category,$c_category2,$c_ip,$amount,$t_no,$t_no,$signdate];

        pdo_excute("insert_coinpoint", $query, $pdo_in);

        $query = "SELECT LAST_INSERT_ID()";
        $stmt = pdo_excute(__FUNCTION__, $query, NULL);
        $row = $stmt->fetch();
        if (!$row) {
            throw new Exception("QUERY_ERROR", __LINE__);
        }
        return $row[0];
    }

}

?>