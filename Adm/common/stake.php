<?php

class Stake
{
    public $columns;

    private function __construct() { 
        $this->columns = array();    
    }

    public static function makeWithNo($t_no)
    {
        $instance = new Stake();
        $instance->get_withdraw($t_no);
        return $instance;
    }
    public static function makeWithRow($row)
    {
        $instance = new Stake();
        $instance->columns = $row;
        return $instance;
    }

    public static function makeWithRequest($arrays)
    {
        $instance = new Stake();

        $req = array();
        foreach($arrays as $value) { 
            $req[$value] = isset($_REQUEST[$value]) ? $_REQUEST[$value] : ""; 
        }
        $instance->columns = $req;
        return $instance;
    }
    private function get_withdraw($t_no)
    {
        global $table_withdraw;

        if (is_empty($t_no)) {
            err_log(__FUNCTION__.": empty id");
            return -1;
        }

        $query = "SELECT * FROM $table_withdraw WHERE t_no=?";
        $pdo_in[] = $t_no;

        try {
            $stmt = pdo_excute(__FUNCTION__, $query, $pdo_in);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach($row as $key => $value) {
                $this->columns[$key] = $row[$key];
            }
            //err_log("===> get:".var_export($this->columns, true));
            return 0;
        } catch (PDOException $e) {
            err_log(__FUNCTION__.":".$e->getMessage());
            return -2;
        }
    }

    public function cancel($userInfo, $c_coin) {
        $t_division = $this->columns["t_division"];
        $t_no = $this->columns["t_no"];
        $t_ordermost = $this->columns["t_ordermost"];
        //$t_fees = $this->columns["t_fees"].$c_coin;
        $t_fees = "0";

		$userBank =new UserBank($userInfo);
		$balance = $userBank->get_user_bank($t_division);

        //$amount = bcadd($t_ordermost, $t_fees, 8);
        $amount = $t_ordermost;
		$increase = "-".$amount.$c_coin;
		$balance->use = bcsub($balance->use, $amount, 8);
		$balance->rest = bcsub($balance->total, $balance->use, 8);

		$balance = UserBank::insert_bankmoney($userInfo, $balance, $t_division, $t_no, "withcacel", $increase, $t_fees);

        $this->update();
    }
    public function transmit($userInfo, $t_ordermost, $t_fees, $c_name, $status, $sdate, $enddate, $s_mno) {

        global $signdate;

        $table_point = "coin_point";

        $t_division = $this->columns["t_division"];
        $t_userno = $this->columns["t_userno"];
        $t_id = $this->columns["t_id"];
        $t_no = $this->columns["t_no"];
        $c_ip = $userInfo->ip;

        $amount = $t_ordermost + 0;
        $c_exchange = "0".$c_name;
        $c_payment = "-".$amount.$c_name;
        $t_fees = "-".$t_fees.$c_name;
        $c_category = "reqorder";
        $c_category2 = $c_name." WithDraw";


        $query = "INSERT INTO $table_point";
        $query .= "(c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate)";
        $query .= "VALUES";
        $query .= "('',?,?,?,?,?,?,?,?,?,?,?,?)";
        $pdo_in = [$t_division,$t_userno,$t_id,$c_exchange, $c_payment,
                    $c_category,$c_category2,$c_ip,$amount,$t_no, $t_no,$signdate];

        pdo_excute("insert_coinpoint", $query, $pdo_in);

        // bankmoney 정산. 
        $userBank =new UserBank($userInfo);
        $balance = $userBank->get_user_bank($t_division);

        $balance->total = bcsub($balance->total, $amount, 8);
        $balance->total = $balance->total < 0 ? 0 : $balance->total;
        $balance->use = bcsub($balance->use, $amount, 8);
        $balance->use= $balance->use < 0 ? 0 : $balance->use;
        $balance->rest = bcsub($balance->total, $balance->use, 8);

        $balance = UserBank::insert_bankmoney($userInfo, $balance, $t_division, $t_no, "withdraw", $c_payment, $t_fees);

        $this->updateStake($status, $sdate, $enddate, $s_mno);
        $this->update();
        
    }
    public function update() {

        global $table_withdraw;

        $query = "UPDATE $table_withdraw SET ";

        $pk = "t_no";
        $ts = "t_stake";
        $tf = "t_fees";
        $num = count($this->columns);
        foreach($this->columns as $key => $value) { 
            //err_log("$key===>".$value);
            if ($key == $pk) {
                continue;
            }
            if($key == $tf) {
                $value = "0";
            }
            $query .= "$key = '$value'";
            $num--;
            if ($num > 1) {
                $query .= ",";
            }
        }
        $query .= " WHERE t_no=?";

        $t_no = $this->columns[$pk];
        pdo_excute(__FUNCTION__, $query, [$t_no]);
    }

    public function updateStake($status, $signdate, $enddate, $mno)
    {
        global $stake;

        $qry = "UPDATE $stake SET ";
        $qry .= "m_status = ?, ";
        $qry .= "m_startdate = ?, ";
        $qry .= "m_enddate = ? ";
        $qry .= "WHERE m_no = ? ";

        $pdo_in1 = [$status, $signdate, $enddate, $mno];

        try {
            $stmt = pdo_excute("updatestake", $qry, $pdo_in1);
        } catch (PDOException $e) {
            err_log($e->getMessage());
            throw new Exception("QUERY_ERROR", __LINE__);
        }
    }
}

?>