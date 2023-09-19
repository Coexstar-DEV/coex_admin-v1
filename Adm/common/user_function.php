<?php

$DEFINE_DEFAULT_COIN = "0";
$DEFINE_DEFAULT_NAME = "PHP";
$DEFINE_USER_LEVEL = 5;
$DEFINE_MARKET = [$DEFINE_DEFAULT_NAME, "BTC", "KRWC", "USDT"];

define("ADMIN_LVL1", 1);
define("ADMIN_LVL2", 2);
define("ADMIN_LVL3", 3);
define("ADMIN_LVL4", 4);
define("ADMIN_LVL5", 5);

define("SUCCEED", 0);
define("ERR_INSUFFICIENT", -100);
define("ERR_WRONG_ADDRESS", -200);
define("ERR_INVALID_PARAM", -300);
define("ERR_NETWORK", -980);
define("ERR_ALREADY_SEND", -970);
define("ERR_NOT_DEFINED", -990);

define('CONST_KEY', '0123456abcdefghijklmnopqrstuvxyz'); //상수정의 키
define('KEY_128', substr(CONST_KEY, 0, 128 / 8)); //128bit (16자리)
define('KEY_256', substr(CONST_KEY, 0, 256 / 8)); //256bit (32자리)
 
//$str4 = openssl_encrypt($str1, 'AES-256-CBC', KEY_256, 0, KEY_128);
//$str5 = openssl_decrypt($str4, 'AES-256-CBC', KEY_256, 0, KEY_128);

define('AES_KEY', "COEX-ENCRYPTION-KEY-USED-ONLY-16-OR-32-BYTES"); //128bit (16자리)
define('AES_IV', "COEX-INITIAL-VECTOR-USED-ONLY-16-BYTES"); //256bit (32자리)


function fixIv($ivIn) {
	$ivOut = null;
	if (!is_null($ivIn)) {
		if (strlen($ivIn) < 16) {
			$ivOut = str_pad($ivIn, 16, '\0');
		} else {
			$ivOut = substr($ivIn, 0, 16);
		}
	} else {
		$ivOut = str_pad("", 16, '\0');
	}
	return $ivOut;
}
function aesEncrypt($data) {
    $secret_key = AES_KEY;
    $secret_iv = AES_IV;
    $cipherBit = 256;
	$cipher = "aes-$cipherBit-cbc";
	$encrypted = openssl_encrypt($data, $cipher, $secret_key, OPENSSL_RAW_DATA, fixIv($secret_iv));
	return base64_encode($encrypted);
}
function aesDecrypt($data) {
    $secret_key = AES_KEY;
    $secret_iv = AES_IV;
    $cipherBit = 256;
	$cipher = "aes-$cipherBit-cbc";
    $b64Decoded = base64_decode($data);

    return openssl_decrypt($b64Decoded, $cipher, $secret_key, OPENSSL_RAW_DATA, fixIv($secret_iv));
}

function check_manager_level($lvl, $avail) {
    if ($lvl >= $avail) {
    //if($lvl >= 0) {
        // bypass
    } else {
        fatal_log("===>NOT verified ADMIN --------------admin:$lvl, avail:$avail");
        popup_msg("Adminitrator level is not available.");
        exit;
    }
}

function check_manager_level2($lvl, $avail) {
    if ($lvl >= $avail) {
        return true;
    } else {
        return false;
    }
}

function register_airdrop($coinInfo, $member_no, $member_id, $category, $qty)
{
	global $m_bankmoney;
	$signdate = time();

	$coin_total = $qty;
	$coin_use = 0;
	$coin_rest = bcsub($coin_total, $coin_use, 8);
	$m_category = $category;
	$m_category2 = $coin_total.$coinInfo->name;

	$query = "SELECT m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin FROM $m_bankmoney WHERE m_id = ? and m_div = ? ORDER BY m_no desc limit 1";
	try {
		$stmt = pdo_excute("reg_air", $query, [$member_id,$coinInfo->type]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) { // 기존 가입 되어서 bankmoney 이력이 있는경우. 
			$coin_total = $row["m_cointotal"] + $qty;
			$coin_use = $row["m_coinuse"];
			$coin_rest = bcsub($coin_total, $coin_use, 8);
		}
	} catch (PDOException $e) {
		fatal_log($coinInfo->name." 에어드롭 실패[" . $query . "]\n");
		throw new Exception("QUERY_ERROR", __LINE__);
	}

	$query = "INSERT INTO $m_bankmoney ";
	$query .= "(";
	$query .= "m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate,m_category,m_category2";
	$query .= ")";
	$query .= "VALUES";
	$query .= " (";
	$query .= "?,?,?,?,?,?,?,?,?";
	$query .= ")";
	$pdo_in_3 = [$coinInfo->type, $member_no, $member_id, $coin_total, $coin_use, $coin_rest, $signdate, $m_category, $m_category2];

	$stmt3 = pdo_excute("bankmoney ins", $query, $pdo_in_3);
	if (!$stmt3) {
		fatal_log($coinInfo->name." 에어드롭 실패[" . $query . "]\n");
		throw new Exception("QUERY_ERROR", __LINE__);
	}
}

function make_curl2($api, $param) {
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

function get_real_ip(){  
    if(!empty($_SERVER['HTTP_CLIENT_IP']) && getenv('HTTP_CLIENT_IP')){  
        return $_SERVER['HTTP_CLIENT_IP'];  
    } 
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && getenv('HTTP_X_FORWARDED_FOR')){  
        return $_SERVER['HTTP_X_FORWARDED_FOR'];  
    } 
    elseif(!empty($_SERVER['REMOTE_HOST']) && getenv('REMOTE_HOST')){  
        return $_SERVER['REMOTE_HOST'];  
    } 
    elseif(!empty($_SERVER['REMOTE_ADDR']) && getenv('REMOTE_ADDR')){  
        return $_SERVER['REMOTE_ADDR'];  
    }  
    return false;  
} 


function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function print_pdo($query, $array)
{
    global $LOG_LEVEL;

    if (is_empty($LOG_LEVEL) || !is_array($array)) {
        return $query;
    }
    $len = count($array);
    for ($i = 0; $i < $len; $i++) {
        $query = preg_replace('/\?/', "'" . $array[$i] . "'", $query, 1);
    }
    return $query;
}

function convert_page_query($query, $page_block, $page) {
	$match = array();
	preg_match("/SELECT(.*?)FROM/", $query, $match);
	//err_log(var_export($match, true));

	$offset = ($page-1)*$page_block;
	$query_ex = preg_replace('/SELECT.*?FROM/', 'SELECT * FROM', $query);
	$query_ex = "SELECT $match[1] FROM ($query_ex LIMIT $offset,$page_block) R1 ";
	//err_log("get_page:".$query_ex);
	return $query_ex;
}

function convert_page_query1($query, $page_block, $page) {
	$offset = ($page-1)*$page_block;
	$query_ex = "$query LIMIT $offset,$page_block";
	//err_log("get_page:".$query_ex);
	return $query_ex;
}

function pdo_excute($tag, $query, $pdo_in)
{
    global $pdo;

    err_log("[$tag]: " . print_pdo($query, $pdo_in));
    try {
        $stmt = $pdo->prepare($query);
        if (is_array($pdo_in)) {
            $result = $stmt->execute($pdo_in);
        } else {
            $result = $stmt->execute();
        }
        if (!$result) {
            throw new Exception("QUERY_ERROR", __LINE__);
        }
    } catch (PDOException $e) {
        err_log("[$tag]:" . $e->getMessage());
        throw new Exception("QUERY_ERROR", __LINE__);

    }
    return $stmt;
}
function pdo_excute_count($tag, $q_arg, $pdo_in) {
    global $pdo;

    $query = "SELECT COUNT(*) FROM ($q_arg) R1";
    err_log("[$tag]: " . print_pdo($query, $pdo_in));
    try {
        $stmt = $pdo->prepare($query);
        if (is_array($pdo_in)) {
            $result = $stmt->execute($pdo_in);
        } else {
            $result = $stmt->execute();
        }
        if (!$result) {
            throw new Exception("QUERY_ERROR", __LINE__);
        }
    } catch (PDOException $e) {
        global $LOG_LEVEL;
        $LOG_LEVEL = 1;
        err_log("[$tag]:" . $e->getMessage());
        throw new Exception("QUERY_ERROR", __LINE__);

    }
    return $stmt->fetchColumn();
}

function parse_array($array)
{
    $req = "";
    foreach ($array as $key => $value) {
        $req .= "$key=$value&";
    }
    return $req;
}

function is_empty($var)
{
    if ($var == "" || $var == null || !isset($var) || empty((array) $var)) {
        return true;
    }
    return false;
}

function get_user_id($key_value)
{
    if (is_empty($key_value)) {
        return "";
    }

    // global $secret_key;
    // global $secret_iv;

    // $key_value = explode("/", Decrypt($key_value, $secret_key, $secret_iv));
    // $key_value = $key_value[1];
    $redis_host = "172.31.5.126";
    $redis_port = 6379;

    try {
        $redis = new Redis();
        $redis->connect($redis_host, $redis_port, 1000);
    } catch (Exception $e) {
        return "";
    }
    $userid = $redis->get("$key_value");

    return $userid;
}

function numberformat($number, $format, $decimals)
{
    if (!is_numeric($number)) {
        return $number;
    }
    $numbers = explode(".", $number, 8);

    $num1 = $numbers[0];
    $num2 = count($numbers) == 2 ? $numbers[1] : "";

    $len1 = strlen($num1);
    $len2 = strlen($num2);

    if (!is_numeric($decimals)) {
        $decimals = 4;
    }

    if ($format == "money") {
        err_log("number:$number, dec:$decimals, num2:$num2, len2:$len2, ");
        if ($len2 >= $decimals) {
            $number = number_format($number, $len2, '.', '');
        } else {
            $number = number_format($number, $decimals, '.', '');
        }
    } else if ($format == "integer") {

        $number = number_format($number, $decimals, '.', '');
        $number = rtrim($number, 0);
        $number = rtrim($number, '.');

    } else if ($format == "money2") {

        $small_dicimal = 1;
        /*
        if ($len1 >= 10) { // 1 000 000 000
            $number = number_format($number / 1000000000, $small_dicimal, '.', ',');
            $number = rtrim($number, 0); $number= rtrim($number, '.');
            $number .= "B";
        } else if (10 > $len1 && $len1 >= 7) { // 1 000 000  백만 -> one million
            $number = number_format($number / 1000000, $small_dicimal, '.', ',');
            $number = rtrim($number, 0); $number= rtrim($number, '.');
            $number .= "M";
        } else if (7 > $len1 && $len1 > 1) { // 100자리 이상이면, 500000000.02 =소수점 버림.
            $number = number_format($number, $small_dicimal, '.', ',');
            $number = rtrim($number, 0);
            $number = rtrim($number, '.');
            //if (substr($number, -1) == ".") { $number .= "0"; }
        */
        if ($len1 > 1) { // 100자리 이상이면, 500000000.02 =소수점 버림.
            $number = number_format($number, $small_dicimal, '.', ',');
            $number = rtrim($number, 0);
            $number = rtrim($number, '.');
            //if (substr($number, -1) == ".") { $number .= "0"; }
        } else if ($len1 == 1) {
            $number = number_format($number, $decimals, '.', ',');
            if ($decimals != 0) {
                $number = rtrim($number, 0);
                $number = rtrim($number, '.');
            }
            //if (substr($number, -1) == ".") { $number .= "0"; }
        }
        //err_log("my_numberformat:money2:$len1, num:$number");

    } else if ($format == "money3") {
        $number = number_format($number, $decimals, '.', ',');
        if ($decimals != 0) {
            $number = rtrim($number, 0);
            $number = rtrim($number, '.');
        }
    } else if ($format == "money4") {
        $number = number_format($number, $decimals, '.', ',');
    } else {
        if ($len1 <= 2) {
            $number = number_format($number, 2);
        } else {
            $number = number_format($number, 0, '.', '') + 0;
        }
    }
    return $number;
}

function err_log($log_msg)
{
    global $LOG_LEVEL;
    global $LOG_TAG;
    if ($LOG_LEVEL == 2) {
    	error_reporting(E_ALL);
        echo "[$LOG_TAG] $log_msg\n";
    } else if ($LOG_LEVEL == 1) {
    	error_reporting(E_ALL);
        error_log("[$LOG_TAG] " . $log_msg);
    } else if ($LOG_LEVEL == 0) {
    	error_reporting(0);
    }
}

function fatal_log($log_msg)
{
    global $LOG_LEVEL;
    global $LOG_TAG;
    $LOG_LEVEL = 1;
    error_log("[Fatal:$LOG_TAG] " . $log_msg);
}


function exten_appr($ext)
{
    if (!strcasecmp($ext, "gif") || !strcasecmp($ext, "jpg") || !strcasecmp($ext, "jpeg") || !strcasecmp($ext, "html") ||
        !strcasecmp($ext, "htm") || !strcasecmp($ext, "txt") || !strcasecmp($ext, "js") || !strcasecmp($ext, "mid") ||
        !strcasecmp($ext, "swf") || !strcasecmp($ext, "hwp") || !strcasecmp($ext, "doc") || !strcasecmp($ext, "ppt") ||
        !strcasecmp($ext, "rar") || !strcasecmp($ext, "arj") || !strcasecmp($ext, "pdf") || !strcasecmp($ext, "cla") ||
        !strcasecmp($ext, "zip")) {} else {
        echo "<script>alert(\"허용되지않는 이름의 확장자를 사용하였읍니다.\");</script>\n";
        echo "<script>history.back();</script>\n";
        exit;
    }
}

function print_title_image($code)
{
    $img_title = $code;
    echo ("<center><img src=\"$img_title\" border=0></center><p>");
}

function print_htmltag_yesno($allow_html)
{
    if ($allow_html) {
        echo ("<font size=2>(태그사용 <font color=red>가능</font>)</font>");
    } else {
        echo ("<font size=2>(태그사용 <font color=red>불가</font>)</font>");
    }
}

function popup_msg($msg)
{
    echo ("<script language=\"javascript\">
   alert('$msg');
   history.back();
   </script>");
}

function is_base64($s) {
    $s = strstr($s, '._-', '+/=');
    return ! (base64_decode($s, true) === false);
}

function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/=', '._-');
    //return base64_encode($input);
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '._-', '+/='));
    //return base64_decode($input);
}

function error($errcode)
{
    switch ($errcode) {
        case ("INVALID_NAME"):
            popup_msg("입력하신 이름은 허용되지 않는 문자열입니다.\\n\\n올바른 이름을 입력하여 주십시오.");
            break;

        case ("INVALID_SUBJECT"):
            popup_msg("입력하신 제목은 허용되지 않는 문자열입니다. \\n\\n올바른 제목을 입력하여 주십시오.");
            break;

        case ("INVALID_EMAIL"):
            popup_msg("입력하신 주소는 올바른 전자우편주소가 아닙니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("INVALID_HOMEPAGE"):
            popup_msg("입력하신 주소는 올바른 홈페이지주소가 아닙니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("INVALID_PASSWD"):
            popup_msg("암호는 최소 6자이상의 영문자 또는 숫자여야 합니다. \\n\\n다시 입력하여 주십시오.");
            break;
        case ("INVALID_ID"):
            popup_msg("아이디는 최소 6자이상의 영문자 또는 숫자여야 합니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("INVALID_FILE"):
            popup_msg("등록할 파일을 선택하지 않으셨습니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("INVALID_COMMENT"):
            popup_msg("본문을 입력하지 않으셨습니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("QUERY_ERROR"):
            global $dbconn;
            $err_no = mysqli_errno($dbconn);
            $err_msg = mysqli_error($dbconn);
            $error_msg = "ERROR CODE " . $err_no . " : $err_msg";
            $error_msg = addslashes($error_msg);
            fatal_log($error_msg);
            popup_msg($error_msg);
            break;

        case ("DB_ERROR"):
            global $dbconn;
            $err_no = mysqli_errno($dbconn);
            $err_msg = mysqli_error($dbconn);
            $error_msg = "ERROR CODE " . $err_no . " : $err_msg";
            echo ("$error_msg");
            break;

        case ("NO_ACCESS_UPLOAD"):
            popup_msg("해당파일은 업로드가 허용되지 않는 파일입니다");
            break;

        case ("SAME_FILE_EXIST"):
            popup_msg("동일한 이름의 파일이 이미 등록되어 있습니다. \\n\\n다른 이름으로 업로드하여 주십시오.");
            break;

        case ("UPLOAD_COPY_FAILURE"):
            popup_msg("업로드 과정중 오류가 발생하였습니다. \\n\\n파일이 저장될 디렉토리가 없거나 디렉토리의 퍼미션 제한으로 인한 오류일 가능성이 있습니다.");
            break;

        case ("UPLOAD_DELETE_FAILURE"):
            popup_msg("업로드 과정중 오류가 발생하였습니다. \\n\\n관리자에게 문의하여 주십시오.");
            break;

        case ("FILE_DELETE_FAILURE"):
            popup_msg("파일이 삭제되지 않았습니다. \\n\\n관리자에게 문의하여 주십시오.");
            break;

        case ("NO_ACCESS_MODIFY"):
            popup_msg("입력하신 암호와 일치하지 않으므로 수정할 수 없습니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("NO_ACCESS_DELETE"):
            popup_msg("입력하신 암호와 일치하지 않으므로 삭제할 수 없습니다. \\n\\n다시 입력하여 주십시오.");
            break;

        case ("NO_DELETE"):
            echo ("<script language=\"javascript\">
   	<!--
   	 alert(\"삭제할 권한이 없습니다. \\n\\n다시 입력하여 주십시오.\");
   	//-->
   	</script>");
            break;

        case ("FILE_SIZE_OVERFLOW"):
            popup_msg("할당된 용량이 초과하였읍니다. \\n\\n다른 파일을 삭제후 추가하십시오. ");
            break;

        case ("FILE_ONLY_COPY"):
            popup_msg("디렉토리 복사는 허용이 안됩니다. \\n\\n파일을 선택후 복사하십시오. ");
            break;

        default:
    }
}

function Encrypt($str, $secret_key = 'secret key', $secret_iv = 'secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32);

    return str_replace("=", "", base64_encode(
        openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
    );
}

function Decrypt($str, $secret_key = 'secret key', $secret_iv = 'secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32);

    return openssl_decrypt(
        base64_decode($str), "AES-256-CBC", $key, 0, $iv
    );
}

$secret_key = "dodobird";
$secret_iv = "#@$%^&*()_+=-";

function sqlfilter($str)
{
    $str = addslashes($str);
    $strdata = "'&\&\"&\&(&)&#&>&<&*/&/*&\&;&|&--&=&[&]&,";
    $search = explode("&", $strdata);
    for ($i = 0; $i < count($search); $i++) {
        $str = str_replace($search[$i], "", $str);
    }
    return $str;
    //        안 걸러지는 문자 : `~!@$^*-_ }{:/?.
}

function sqlfilterId($str)
{
    $str = addslashes($str);
    $strdata = "'&\&\"&\&(&)&#&>&<&=&*/&/*&+&\&%&;&|&--&=&[&]&,";
    $search = explode("&", $strdata);
    for ($i = 0; $i < count($search); $i++) {
        $str = str_replace($search[$i], "", $str);
    }
    return $str;
    //        안 걸러지는 문자 : `~!@$%^*-_ }{:/?.

}

function generatePrivateKey($length = 30)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}


 function getDeposits($m_id)
{

}
