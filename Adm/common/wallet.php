<?php


function wallet_result_check($c_coin, $result)
{
    if ($result < 0) {
        if ($result == ERR_INSUFFICIENT) {
            fatal_log("$c_coin::wallet_result_check::ERR_INSUFFICIENT => exit");
            echo "<script>alert('$c_coin 코인수량이 부족 합니다.');</script>";
            echo "<script>history.back();</script>";
            exit;
        } else if ($result == ERR_ALREADY_SEND) {
            fatal_log("$c_coin::wallet_result_check::ERR_ALREADY_SEND => exit");
            echo "<script>alert('$c_coin 이미 전송중입니다.');</script>";
            echo "<script>history.back();</script>";
            exit;
        } else if ($result == ERR_INVALID_PARAM) {
            fatal_log("$c_coin::wallet_result_check::ERR_INVALID_PARAM => exit");
            echo "<script>alert('$c_coin 파라메터가 부족합니다.');</script>";
            echo "<script>history.back();</script>";
            exit;
        } else if ($result == ERR_NETWORK) {
            fatal_log("$c_coin::wallet_result_check::ERR_NETWORK => exit");
            echo "<script>alert('$c_coin 네트워크 에러 입니다.');</script>";
            echo "<script>history.back();</script>";
            exit;
        } else {
            fatal_log("$c_coin::wallet_result_check:: ?? $result => exit");
            echo "<script>alert('$c_coin 전송실패 하였습니다.');</script>";
            echo "<script>history.back();</script>";
            exit;
        }
    }

}
function wallet_result_check2($c_coin, $result)
{
    $rtn_msg = "";
    if ($result < 0) {
        if ($result == ERR_INSUFFICIENT) {
            $rtn_msg = '$c_coin 코인수량이 부족 합니다.';
        } else if ($result == ERR_ALREADY_SEND) {
            $rtn_msg = '$c_coin 이미 전송중입니다.';
        } else if ($result == ERR_INVALID_PARAM) {
            $rtn_msg = '$c_coin 파라메터가 부족합니다.';
        } else if ($result == ERR_NETWORK) {
            $rtn_msg = '$c_coin 네트워크 에러 입니다.';
        } else {
            $rtn_msg = '$c_coin 전송실패 하였습니다.';
        }
    }

    return $rtn_msg;
}
abstract class WalletAPI
{
    public $result;
    public $c_coin;
    public $c_type;

    public function __construct($c_type, $c_coin) {
        $this->c_coin = $c_coin;
        $this->c_type = $c_type;
    }

    public static function make($c_type, $c_coin){
        if ($c_type == "0") {
            return new AltCoin($c_type, $c_coin);
        } else if ($c_type == "1") {
            return new EtherCoin($c_type, $c_coin);
        } else if ($c_type == "2") {
            return new TokenCoin($c_type, $c_coin);
        } else if ($c_type == "3") {
            return new RippleCoin($c_type, $c_coin);
        } else if ($c_type == "4") {
            return new EosCoin($c_type, $c_coin);
        } else if ($c_type == "5") {
            return new TrxCoin($c_type, $c_coin);
        } else if ($c_type == "6") {
            return new Alt2Coin($c_type, $c_coin);
        } else if ($c_type == "7") {
            return new TokenCoin($c_type, $c_coin);
        }
        return NULL;
    }

    abstract public function createWallet($password);
    abstract public function getWallet($userInfo);
    abstract public function getWalletFromAccount($account);
    abstract public function getBalance($coin_title, $address);
    abstract public function getTxList($coin_title, $address);
    abstract public function getDepositList($coin_title, $lasttx);
    abstract public function sendTo($coin_title, $from, $password, $to, $amount, $tag);
    abstract public function moveTo($coin_title, $from, $password, $to, $amount);
    abstract public function getResult();
    abstract public function isValidAddress($address);

    public static function make_curl($api, $param) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        if ($param == NULL) {
            curl_setopt($ch, CURLOPT_POST, 0);
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return $ch;
    }

}

class AltCoin extends WalletAPI {
    public function isValidAddress($address) {
        err_log(__FUNCTION__.": $address");
        if ($this->c_coin == "BCH") { // bch 코인 주소는 alt 주소와 다르다. 
            // bitcoincash:qp4fdylp4s8a066hn5jfrpt2jf3av95uaqy969dh77
            $address = str_replace("bitcoincash:", "", $address); // 문제 있음.
            return "1";
        }
        $decoded = decodeBase58($address);

        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true); 

        if(substr_compare($decoded, $d2, 21, 4)){
            return "0";
        }
        return "1";
    }
    public function createWallet($account) {
        // no need to implement.
    }
    public function getWallet($userInfo) {
        $account = $userInfo->getAccount($this->c_type, $this->c_coin);
        return $this->getWalletFromAccount($account);
    }
    public function getWalletFromAccount($account) {
        global $WALLET_API_URL;

        $coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo";
        $param = "deId=afahfdg&bitAccount=$account";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        return $arr["bitaddress"];
    }

    public function getBalance($coin_title, $address) {
        global $WALLET_API_URL;

        if (is_empty($address)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }
    
        $coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo";
        $param = "deId=afahfdg&bitAccount=$address";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        $this->result = $arr["bitbalance"] + 0;
        return SUCCEED;
    }

    public function getTxList($coin_title, $address) {
        global $WALLET_API_URL;

        if (is_empty($address)) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }
    
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/lists";
        $param = "deId=afahfdg&bitAccount=$address";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $this->result = $result;
        return SUCCEED;
    }

    public function getDepositList($coin_title, $lasttx) {
        global $WALLET_API_URL;

        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/receiveAccounts";
        $param = "deId=afahfdg&bitAccount=*&lasttx=$lasttx";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $this->result = $result;
        return SUCCEED;
    }

    public function moveTo($coin_title, $from, $password, $to, $amount) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($to) ) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM from:$from, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        //$coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo";
        $param = "deId=afahfdg&bitAccount=$from";
        $ch = parent::make_curl($api, $param);
        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result == false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        $bitbalance = $arr["bitbalance"];

        if ($amount == "-1") {
            $amount = $bitbalance;
        }

        err_log(__FUNCTION__." <=> $result");
        if ($bitbalance >= $amount) {

            $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/move";
            $param = "deId=afahfdg&bitAccount=$from&sendAccount=$to&sendBtc=$amount";
            $ch = parent::make_curl($api, $param);
            err_log(__FUNCTION__.": curl '$api' -d '$param'");
            $result = curl_exec($ch);
            if ($result === false) {
                fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
                return ERR_NETWORK;
            }

            $move_send = json_decode($result, true);
            $arr_send = $move_send['result'];
            if ($arr_send != "1") {
                fatal_log(__CLASS__.":sendTo: not enough coin, wrong address");
                return ERR_WRONG_ADDRESS;
            }
            err_log(__CLASS__.":sendTo - done");
            return SUCCEED;
        }

        err_log(__CLASS__.":not enough coin");
        return ERR_INSUFFICIENT;

    }

    public function sendTo($coin_title, $from, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($to) ) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM from:$from, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        //$coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo";
        $param = "deId=afahfdg&bitAccount=$from";
        $ch = parent::make_curl($api, $param);
        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        $bitbalance = $arr["bitbalance"];
        
        if ($amount == "-1") {
            $amount = $bitbalance;
        }

        err_log(__FUNCTION__." <=> $result");
        if ($bitbalance >= $amount) {

            $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/sendFrom";
            $param = "deId=afahfdg&bitAccount=$from&sendAddress=$to&sendBtc=$amount";
            $ch = parent::make_curl($api, $param);
            err_log(__FUNCTION__.": curl '$api' -d '$param'");
            $result = curl_exec($ch);
            if ($result === false) {
                fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
                return ERR_NETWORK;
            }

            $move_send = json_decode($result, true);
            $arr_send = $move_send['result'];
            if ($arr_send != "1") {
                //echo "<script>alert(코인수량이 부족하거나 지갑주소가 잘못되었습니다.);</script>";
                //echo "<script>history.back();</script>";
                fatal_log(__CLASS__.":sendTo: no enough coin, or wrong address");
                return ERR_WRONG_ADDRESS;
            }
            err_log(__CLASS__.":sendTo - done");
            return SUCCEED;
        }

        fatal_log(__CLASS__.":not enough coin");
        return ERR_INSUFFICIENT;
    }

    public function getResult()
    {
        return $this->result;
    }
}

class Alt2Coin extends WalletAPI {
    public function isValidAddress($address) {
        err_log(__FUNCTION__.": $address");
        $decoded = decodeBase58($address);

        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true); 

        if(substr_compare($decoded, $d2, 21, 4)){
            return "0";
        }
        return "1";
    }

    public function createWallet($account) {
        // no need to implement.
    }
    public function getWallet($userInfo) {
        $account = $userInfo->getAccount($this->c_type, $this->c_coin);
        return $this->getWalletFromAccount($account);
    }
    public function getWalletFromAccount($account) {
        global $WALLET_API_URL;

        $coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo2";
        $param = "deId=afahfdg&bitAccount=$account";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        return $arr["bitaddress"];
    }

    public function getBalance($coin_title, $address) {
        global $WALLET_API_URL;

        if (is_empty($address)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }
    

        $coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo2";
        $param = "deId=afahfdg&bitAccount=$address";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        $this->result = $arr["bitbalance"] + 0;
        return SUCCEED;
    }

    public function getTxList($coin_title, $address) { 
        global $WALLET_API_URL;

        if (is_empty($address)) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }
    
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/lists";
        $param = "deId=afahfdg&bitAccount=$address&lasttx=0";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $this->result = $result;
        return SUCCEED;
    }

    public function getDepositList($coin_title, $lasttx) {
        global $WALLET_API_URL;

        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/getTx";
        $param = "deId=afahfdg&bitAccount=*&lasttx=$lasttx";
        $ch = parent::make_curl($api, $param);

        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $this->result = $result;
        return SUCCEED;
    }

    public function moveTo($coin_title, $from, $password, $to, $amount) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($to) ) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM from:$from, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/move2";
        $param = "deId=afahfdg&bitAccount=$from&sendAccount=$to&sendBtc=$amount";
        $ch = parent::make_curl($api, $param);
        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $move_send = json_decode($result, true);
        $arr_send = $move_send['result'];
        if ($arr_send != "1") {
            fatal_log(__CLASS__.":moveTo: not enough coin, wrong address");
            return ERR_WRONG_ADDRESS;
        }
        err_log(__CLASS__.":sendTo - done");
        return SUCCEED;


    }

    public function sendTo($coin_title, $from, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($to) ) {
            fatal_log(__FUNCTION__.":ERR_INVALID_PARAM from:$from, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        //$coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/accountInfo2";
        $param = "deId=afahfdg&bitAccount=$from";
        $ch = parent::make_curl($api, $param);
        err_log(__FUNCTION__.": curl '$api' -d '$param'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
            return ERR_NETWORK;
        }

        $json_o = json_decode($result, true);
        $arr = $json_o['bitcoin'];
        $bitbalance = $arr["bitbalance"];
        
        if ($amount == "-1") {
            $amount = $bitbalance;
        }

        err_log(__FUNCTION__." <=> $result");
        if ($bitbalance >= $amount) {

            $api = "http://$WALLET_API_URL/$coin_title/Mobile/Wallet/sendTo";
            $param = "deId=afahfdg&bitAccount=$from&sendAddress=$to&sendBtc=$amount";
            $ch = parent::make_curl($api, $param);
            err_log(__FUNCTION__.": curl '$api' -d '$param'");
            $result = curl_exec($ch);
            if ($result === false) {
                fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' -d '$param'");
                return ERR_NETWORK;
            }

            $move_send = json_decode($result, true);
            $arr_send = $move_send['result'];
            if ($arr_send != "1") {
                //echo "<script>alert(코인수량이 부족하거나 지갑주소가 잘못되었습니다.);</script>";
                //echo "<script>history.back();</script>";
                fatal_log(__CLASS__.":sendTo: no enough coin, wrong address");
                return ERR_WRONG_ADDRESS;
            }
            err_log(__CLASS__.":sendTo - done");
            return SUCCEED;
        }

        fatal_log(__CLASS__.":not enough coin");
        return ERR_INSUFFICIENT;
    }

    public function getResult()
    {
        return $this->result;
    }
}


class EtherCoin extends WalletAPI {
    public function isValidAddress($address) {
        if (!preg_match('/^(0x)?[0-9a-f]{40}$/i',strtolower($address))) {
            return "0";
        } else {
            return "1";
        }
    }

    public function createWallet($password) {
        global $WALLET_API_URL;

        $name = $this->c_coin; 
        $api = "http://$WALLET_API_URL/$name/createWallet?password=$password";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$name===>$result");
        $res = json_decode($result, true);
        $this->result = $res;
        return SUCCEED;
    }
    public function getWallet($userInfo) {
        $address_file = $userInfo->getAccount($this->c_type, $this->c_coin);
        if (is_empty($address_file)) {
            $r_code = $this->createWallet($userInfo->getPassword($this->c_coin));
            wallet_result_check($this->c_coin, $r_code);

            $res = $this->getResult();
            $address_file = $res["file"];
            if ($this->c_coin == "ETH")
                $query = "UPDATE m_member SET m_ethfile = '$address_file' WHERE m_id = '$userInfo->id'";
            else if ($this->c_coin == "CELO")
                $query = "UPDATE m_member SET m_celofile = '$address_file' WHERE m_id = '$userInfo->id'";

            pdo_excute(__FUNCTION__.":update ethfile", $query, NULL);
        }

        return $this->getWalletFromAccount($address_file);
    }

    public function getWalletFromAccount($address_file) {
        $rets = explode("--",$address_file);
        $address = "0x".str_replace(".json", "", $rets[2]);
        return $address;
    }

    public function getTxList($coin_title, $address) {
    }

    public function getDepositList($coin_title, $lasttx) { 
        global $WALLET_API_URL;
        $name = $this->c_coin;

        $api = "http://$WALLET_API_URL/$name/txInfo?hash=$lasttx";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");

        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        // eth, token 모두 가져 온다. 
        // 

        $this->result = $result;
        return SUCCEED;
    }

    public function getBalance($coin_title, $address_file) {
        global $WALLET_API_URL;

        if (is_empty($address_file)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address_file");
            return ERR_INVALID_PARAM;
        }

        $rets = explode("--",$address_file);
        $address = "0x".str_replace(".json", "", $rets[2]);
        $name = $this->c_coin;
        $api = "http://$WALLET_API_URL/$name/accountInfo?address=$address";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$coin_title===>$result");
        $res = json_decode($result, true);
        $this->result = $res["result"] / 1000000000000000000;
        return SUCCEED;
    }
    public function moveTo($coin_title, $from_file, $password, $to, $amount) {
        return $this->sendTo($coin_title, $from_file, $password, $to, $amount, "");
    }
    
    public function sendTo($coin_title, $from_file, $password, $to, $amount, $tag) {

        global $WALLET_API_URL;
        global $table_withdraw;
        global $pdo;

        if (is_empty($from_file) || is_empty($password) || is_empty($to) ) {
            fatal_log("===> ERR_INVALID_PARAM sendTo: from:$from_file, pwd:$password, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        //$from = "UTC--2018-09-06T07-41-45.175000000Z--8ace55c23dacd6f8045c8d0e0cbd6051a0da1c0b.json"; // master
        if (startsWith($to, "UTC")) {
            $rets = explode("--",$to);
            $toaddress = "0x".str_replace(".json", "", $rets[2]);
        } else {
            $toaddress = $to;
        }

        $name = $this->c_coin;
        if ($amount == "-1") {
            $api = "http://$WALLET_API_URL/$name/sendAll?path=$from_file&password=$password&toaddress=$toaddress&amount=0&gas=130";
        } else {
            $api = "http://$WALLET_API_URL/$name/sendETH?path=$from_file&password=$password&toaddress=$toaddress&amount=$amount&gas=130";
        }
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.": curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log("sendETH:$coin_title===>$result");

        // {"gas":"20",
        //  "from":"0x9cc43d962a2151fe389b9429d459c6807bfd9b2d",
        //  "to":"0x8ace55c23dacd6f8045c8d0e0cbd6051a0da1c0b",
        //  "txhash":"0xde8b17e678804bf924a1280b7f43eda86ec6047a0eabba16314557aaed8be43e",
        //  "status":"0xde8b17e678804bf924a1280b7f43eda86ec6047a0eabba16314557aaed8be43e"}

        $res = json_decode($result, true);
        $txhash = $res['txhash'];
        $status = $res['status'];
        if ($res['txhash'] == "0") {
            fatal_log("sendTo:$name - faile to send *$result*");
            if ($status == "insufficent") {
                return ERR_INSUFFICIENT;
            } else {
                return ERR_NOT_DEFINED;
            }
        }
        // txhash 중복인지 확인 필요.$t
        // 출금 테이블 확인 
        $name = $this->c_coin;
        $query = "SELECT COUNT(*) as cnt FROM $table_withdraw WHERE t_division = (select c_no from c_setup where c_coin = '$name') and t_cont LIKE '%$txhash%' ";
        $stmt = pdo_excute("coinpoint",$query, NULL);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['cnt'];
        if ($count > 0) {
            fatal_log(__FUNCTION__.":ETH - alread send *$result*");
            return ERR_ALREADY_SEND;
        }

        // 입금 테이블 확인 
        $query = "SELECT COUNT(*) as cnt FROM coin_point WHERE c_div = (select c_no from c_setup where c_coin = '$name') and c_category2 LIKE '%$txhash%' ";
        $stmt = pdo_excute("coinpoint",$query, NULL);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['cnt'];
        if ($count > 0) {
            fatal_log(__FUNCTION__.":ETH - alread send *$result*");
            return ERR_ALREADY_SEND;
        }


        $this->result = $txhash;

        return SUCCEED;
    }

    public function getResult()
    {
        return $this->result;
    }
}

class TokenCoin extends WalletAPI {
    public function isValidAddress($address) {
        if (!preg_match('/^(0x)?[0-9a-f]{40}$/i',strtolower($address))) {
            return "0";
        } else {
            return "1";
        }
    }

    public function createWallet($password) {
        global $WALLET_API_URL;

        $ETH = $this->c_type == 7 ? "CELO" : "ETH";
        $api = "http://$WALLET_API_URL/$ETH/createWallet?password=$password";

        $ch = parent::make_curl($api, NULL);

        err_log(__FUNCTION__.":ETH: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__."ETH===>$result");
        $res = json_decode($result, true);
        $this->result = $res;
        return SUCCEED;
    }
    public function getWallet($userInfo) {
        $address_file = $userInfo->getAccount($this->c_type, $this->c_coin);
        if (is_empty($address_file)) {
            $r_code = $this->createWallet($userInfo->getPassword($this->c_coin));
            wallet_result_check($this->c_coin, $r_code);

            $res = $this->getResult();
            $address_file = $res["file"];

            if ($this->c_type == 7) { // celo token
                $query = "UPDATE m_member SET m_celotoken = '$address_file' WHERE m_id = '$userInfo->id'";
            } else {
                $query = "UPDATE m_member SET m_tokenfile = '$address_file' WHERE m_id = '$userInfo->id'";
            }

            pdo_excute(__FUNCTION__.":update ethfile", $query, NULL);
        }

        return $this->getWalletFromAccount($address_file);
    }

    public function getWalletFromAccount($address_file) {
        $rets = explode("--",$address_file);
        $address = "0x".str_replace(".json", "", $rets[2]);
        return $address;
    }
    public function getTxList($coin_title, $address) { }
    public function getDepositList($coin_title, $lasttx) { }

    public function getBalance($coin_title, $address_file) {
        global $WALLET_API_URL;

        if (is_empty($address_file)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address_file");
            return ERR_INVALID_PARAM;
        }

        $rets = explode("--",$address_file);
        $address = "0x".str_replace(".json", "", $rets[2]);


        $ETH = $this->c_type == 7 ? "CELO" : "ETH";
        $api = "http://$WALLET_API_URL/$ETH/token_get_balance?token=$coin_title&address=$address";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.": curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api'");
            return ERR_NETWORK;
        }
        err_log("token_get_balance:$coin_title===>$result");
        $res = json_decode($result, true);

        $this->result = $res["balance"];
        return SUCCEED;
    }

    public function moveTo($coin_title, $from_file, $password, $to, $amount) {
        return $this->sendTo($coin_title, $from_file, $password, $to, $amount, "");
    }

    public function sendTo($coin_title, $from_file, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;
        global $table_withdraw;
        global $pdo;

        if (is_empty($from_file) || is_empty($password) || is_empty($to) || is_empty($amount) ) {
            fatal_log("===> sendTo: from:$from_file, pwd:$password, to:$to, amount:$amount");
            return ERR_INVALID_PARAM;
        }

        if (startsWith($to, "UTC")) {
            $rets = explode("--",$to);
            $toaddress = "0x".str_replace(".json", "", $rets[2]);
        } else {
            $toaddress = $to;
        }

        $ETH = $this->c_type == 7 ? "CELO" : "ETH";

        $api = "http://$WALLET_API_URL/$ETH/token_transfer?token=$coin_title&path=$from_file&password=$password&toaddress=$toaddress&amount=$amount";
        $ch = parent::make_curl($api, NULL);
        err_log("sendTo: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api'");
            return ERR_NETWORK;
        }

        err_log("token_transfer:$coin_title===>$result");
        // {"gas":"20",
        //  "from":"0x9cc43d962a2151fe389b9429d459c6807bfd9b2d",
        //  "to":"0x8ace55c23dacd6f8045c8d0e0cbd6051a0da1c0b",
        //  "txhash":"0xde8b17e678804bf924a1280b7f43eda86ec6047a0eabba16314557aaed8be43e",
        //  "status":"0xde8b17e678804bf924a1280b7f43eda86ec6047a0eabba16314557aaed8be43e"}

        $res = json_decode($result, true);
        $txhash = $res['txhash'];
        $status = $res['status'];
        if ($res['txhash'] == "0") {
            fatal_log("sendTo:$coin_title - faile to send *$result*");
            if ($status == "insufficent") {
                return ERR_INSUFFICIENT;
            } else {
                return ERR_NOT_DEFINED;
            }
        }
        // txhash 중복인지 확인 필요.$t

        $query = "SELECT COUNT(*) FROM $table_withdraw WHERE t_cont LIKE '%$txhash%' ";
        $stmt = $pdo->prepare($query);
        $ret = $stmt->execute();
        if (!$ret) {
            throw new Exception("QUERY_ERROR", __LINE__);
        }

        $count = $stmt->fetchColumn();
        if ($count > 0) {
            fatal_log("sendTo:$coin_title - alread send *$result*");
            return ERR_ALREADY_SEND;
        }

        $this->result = $txhash;

        return SUCCEED;

    }

    public function getResult() {
        return $this->result;
    }
}

class RippleCoin extends WalletAPI {

    public function isValidAddress($address) {
        return ERR_NOT_DEFINED;
    }
    public function createWallet($password) {
        return ERR_NOT_DEFINED;
    }
    public function getWallet($userInfo) {
        $wallet = get_master_wallet("3", "XRP");
        return $wallet["account"];
    }

    public function getWalletFromAccount($address) {
        return $address;
    }
    public function getTxList($coin_title, $address) { }
    public function getDepositList($coin_title, $lasttx) { }

    public function getBalance($coin_title, $address) {

        global $WALLET_API_URL;

        if (is_empty($address)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }

        $name = $this->c_coin;
        $api = "http://$WALLET_API_URL/XRP/api/getInfo?account=$address";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$coin_title===>$result");
        $res = json_decode($result, true);
        $this->result = $res["balance_xrp"];
        return SUCCEED;
    }

    public function moveTo($coin_title, $from_file, $password, $to, $amount) {
        fatal_log("NOT_IMPLEMENT");
        return ERR_NOT_DEFINED;
    }

    public function sendTo($coin_title, $from, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($password) || is_empty($to) || is_empty($amount) ) {
            fatal_log("===> sendTo: from:$from, pwd:$password, to:$to, amount:$amount, tag:$tag");
            return ERR_INVALID_PARAM;
        }

        $ret = $this->getBalance($coin_title, $from);
        if ($ret !== SUCCEED) {
            fatal_log("===> sendTo:getBalance from:$from, to:$to, amount:$amount, tag:$tag");
            return ERR_INVALID_PARAM;
        }
        $balance = $this->getResult();
        if ($balance <= $amount) {  // equal : fee 가 추가로 들어가야 하므로 같으면 안됨. 
            fatal_log("===> sendTo:lack balance from:$from, to:$to, balance:$balance amount:$amount, tag:$tag");
            return ERR_INSUFFICIENT;
        }

        /// ===========
        if (is_empty($tag)) $tag = "429496729";  // 5 2357308073

        $api = "http://$WALLET_API_URL/XRP/api/sendTo?toAccount=$to&destinationTag=$tag&balance=$amount&fee=35&fromAccount=$from&fromSecret=$password";
        $ch = parent::make_curl($api, NULL);
        err_log("sendTo: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api'");
            return ERR_NETWORK;
        }

        err_log("token_transfer:$coin_title===>$result");
        /*
        {
            "tx_json": {
              "Account": "rEkPFbff7uyA3LqCuKt3rAJ2Z5pUy2KQo9",
              "Destination": "rEkPFbff7uyA3LqCuKt3rAJ2Z5pUy2KQo9",
              "TransactionType": "Payment",
              "TxnSignature": "30440220222EB8F03BBD962FD2E6886E462C9C8325B2C8023A5451A1F54B8C6AA4911C0802200731C6BE6098AF7ED8EE425FC7861E271568F2DFAFE45B5A78B60B07DCFC42D2",
              "SigningPubKey": "02CC4BEBAA000E114126E1F4B0500DB44046E525ADC2C4919647E7502D26331A8E",
              "Amount": "20000000",
              "Fee": "15",
              "Flags": 2147483648,
              "Sequence": 1,
              "DestinationTag": 12345678,
              "hash": "B824D8A326DEA1987E23C91D1D23B7219B55E64DA9D7DCEA3B71DC9FA71C2131"
            },
            "engine_result": "temREDUNDANT",
            "status": "success"
          }
        */

        $res = json_decode($result, true);
        $status = $res['status'];
        $e_ret = $res['engine_result'];
        if ($status != "success") {
            //tesSUCCESS : 전송 성공 
            //tecNO_DST_INSUF_XRP : 리플 지갑이 개설 안됨 
            //tefPAST_SEQ : 이중지불 오류
            //telINSUF_FEE_P : 수수료 부족
            $this->result = $e_ret;
            fatal_log("failed to send : ". $e_ret);
            return ERR_NOT_DEFINED;

        }
        $txjson = $res['tx_json'];
        $txhash = $txjson['hash'];
        $this->result = $txhash;

        return SUCCEED;

    }

    public function getResult() {
        return $this->result;
    }
}

class EosCoin extends WalletAPI {

    public function isValidAddress($address) {
        return ERR_NOT_DEFINED;
    }
    public function createWallet($password) {
        return ERR_NOT_DEFINED;
    }
    public function getWallet($userInfo) {
        $wallet = get_master_wallet("3", $this->c_coin);
        return $wallet["account"];
    }

    public function getWalletFromAccount($address) {
        return $address;
    }
    public function getTxList($coin_title, $address) { }
    public function getDepositList($coin_title, $lasttx) { }

    public function getBalance($coin_title, $address) {

        global $WALLET_API_URL;

        if (is_empty($address)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }

        $name = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/v1/eos/getInfo?account=$address";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$coin_title===>$result");
        $res = json_decode($result, true);
        $this->result = $res["balance"];
        return SUCCEED;
    }

    public function moveTo($coin_title, $from_file, $password, $to, $amount) {
        fatal_log("NOT_IMPLEMENT");
        return ERR_NOT_DEFINED;
    }

    public function sendTo($coin_title, $from, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($password) || is_empty($to) || is_empty($amount) ) {
            fatal_log("===> sendTo: from:$from, pwd:$password, to:$to, amount:$amount, tag:$tag");
            return ERR_INVALID_PARAM;
        }

        if (is_empty($tag)) $tag = "429496729";  // 5 2357308073

        $api = "http://$WALLET_API_URL/$coin_title/v1/eos/sendTo?to=$to&memo=$tag&amount=$amount&from=$from&fromSecret=$password";
        $ch = parent::make_curl($api, NULL);
        err_log("sendTo: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api'");
            return ERR_NETWORK;
        }

        err_log("token_transfer:$coin_title===>$result");
        $res = json_decode($result, true);
        $status = $res['status'];
        $e_ret = $res['engine_result'];
        if ($status != "success") {
            //tesSUCCESS : 전송 성공 
            //tecNO_DST_INSUF_XRP : 리플 지갑이 개설 안됨 
            //tefPAST_SEQ : 이중지불 오류
            //telINSUF_FEE_P : 수수료 부족
            $this->result = $e_ret;
            fatal_log("failed to send : ". $e_ret);
            return ERR_NOT_DEFINED;

        }
        $txhash = $res['tx'];
        $this->result = $txhash;

        return SUCCEED;

    }

    public function getResult() {
        return $this->result;
    }
}


class TrxCoin extends WalletAPI {

    public function isValidAddress($address) {
        return ERR_NOT_DEFINED;
    }
    public function createWallet($account) {
        global $WALLET_API_URL;

        $coin_title = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/v1/trx/getWallet?account=$account";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$coin_title : curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$coin_title===>$result");
        $res = json_decode($result, true);
        $this->result = $res;
        return SUCCEED;
    }

    public function getWallet($userInfo) {
        $address_file = $userInfo->getAccount($this->c_type, $this->c_coin);
        $address = "";
        if (is_empty($address_file)) {
            $r_code = $this->createWallet($userInfo->id);
            wallet_result_check($this->c_coin, $r_code);

            $res = $this->getResult();
            $address = $res["address"];
            $pwd = $res["privateKey"];
            $addr = $address;
            $query = "UPDATE m_member SET m_trxaddr = '$addr' WHERE m_id = '$userInfo->id'";

            pdo_excute(__FUNCTION__.":update trxaddr", $query, NULL);
        } else {
            $address = $address_file;
            //$addrs = explode("@", $address_file);
            //$address = $addrs[0];
        }

        return $this->getWalletFromAccount($address);
    }

    public function getWalletFromAccount($address) {
        return $address;
    }
    public function getTxList($coin_title, $address) { }
    public function getDepositList($coin_title, $lasttx) { }

    public function getBalance($coin_title, $address) {

        global $WALLET_API_URL;

        if (is_empty($address)) {
            err_log(__FUNCTION__.":ERR_INVALID_PARAM address:$address");
            return ERR_INVALID_PARAM;
        }

        $name = $this->c_coin;
        $api = "http://$WALLET_API_URL/$coin_title/v1/trx/getInfo?address=$address";
        $ch = parent::make_curl($api, NULL);
        err_log(__FUNCTION__.":$name: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api' ");
            return ERR_NETWORK;
        }
        err_log(__FUNCTION__.":$coin_title===>$result");
        $res = json_decode($result, true);
        $this->result = $res["balance"];
        return SUCCEED;
    }

    public function moveTo($coin_title, $from, $password, $to, $amount) {
        if ($amount == "-1") { // sendAll
            $ret = $this->getBalance($coin_title, $from);
            if ($ret == SUCCEED) {
                $amount = $this->getResult();
                return $this->sendTo($coin_title, $from, $password, $to, $amount, "");
            } else {
                return $ret;
            }
        } else {
            return $this->sendTo($coin_title, $from, $password, $to, $amount, "");
        }
    }

    public function sendTo($coin_title, $from, $password, $to, $amount, $tag) {
        global $WALLET_API_URL;

        if (is_empty($from) || is_empty($to) || is_empty($amount) ) {
            fatal_log("===> sendTo: from:$from, pwd:$password, to:$to, amount:$amount, tag:$tag");
            return ERR_INVALID_PARAM;
        }

        $api = "http://$WALLET_API_URL/$coin_title/v1/trx/sendTo?to=$to&amount=$amount&from=$from";
        if (!is_empty($password)) {
            $api .= "&privateKey=$password";
        }
        $ch = parent::make_curl($api, NULL);
        err_log("sendTo: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log(__FUNCTION__.":code:".curl_error($ch).">  curl '$api'");
            return ERR_NETWORK;
        }

        err_log("token_transfer:$coin_title===>$result");
        $res = json_decode($result, true);
        $status = $res['result'];
        $e_ret = $res['txid'];
        if ($status != "success") {
            //tesSUCCESS : 전송 성공 
            //tecNO_DST_INSUF_XRP : 리플 지갑이 개설 안됨 
            //tefPAST_SEQ : 이중지불 오류
            //telINSUF_FEE_P : 수수료 부족
            $this->result = $e_ret;
            fatal_log("failed to send : ". $e_ret);
            return ERR_NOT_DEFINED;

        }
        $txhash = $res['txid'];
        $this->result = $txhash;

        return SUCCEED;

    }

    public function getResult() {
        return $this->result;
    }
}
