<meta charset="utf-8">
<?
$admip = $_SESSION["admip"];


include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL3);

if (isset($_POST["k_no"])) {
	$k_no = sqlfilter($_POST["k_no"]);
} else {
	$k_no = "";
}
if (isset($_POST["k_orderprice"])) {
	$k_orderprice = sqlfilter($_POST["k_orderprice"]);
} else {
	$k_orderprice = "";
}
if (isset($_POST["k_depositprice"])) {
	$k_depositprice = sqlfilter($_POST["k_depositprice"]);
} else {
	$k_depositprice = "";
}
if (isset($_POST["k_sellprice"])) {
	$k_sellprice = sqlfilter($_POST["k_sellprice"]);
} else {
	$k_sellprice = "";
}
if (isset($_POST["k_returnprice"])) {
	$k_returnprice = sqlfilter($_POST["k_returnprice"]);
} else {
	$k_returnprice = "";
}
if (isset($_POST["k_depositname"])) {
	$k_depositname = sqlfilter($_POST["k_depositname"]);
} else {
	$k_depositname = "";
}
if (isset($_POST["k_payment"])) {
	$k_payment = sqlfilter($_POST["k_payment"]);
} else {
	$k_payment = "";
}
if (isset($_POST["k_ordername"])) {
	$k_ordername = sqlfilter($_POST["k_ordername"]);
} else {
	$k_ordername = "";
}
if (isset($_POST["k_email"])) {
	$k_email = sqlfilter($_POST["k_email"]);
} else {
	$k_email = "";
}
if (isset($_POST["k_tel"])) {
	$k_tel = sqlfilter($_POST["k_tel"]);
} else {
	$k_tel = "";
}
if (isset($_POST["k_ordermemo"])) {
	$k_ordermemo = sqlfilter($_POST["k_ordermemo"]);
} else {
	$k_ordermemo = "";
}
if (isset($_POST["k_check"])) {
	$k_check = sqlfilter($_POST["k_check"]);
} else {
	$k_check = "";
}
if (isset($_POST["k_duedate"])) {
	$k_duedate = sqlfilter($_POST["k_duedate"]);
} else {
	$k_duedate = "";
}
if (isset($_POST["k_checkdate"])) {
	$k_checkdate = sqlfilter($_POST["k_checkdate"]);
} else {
	$k_checkdate = "";
}
if (isset($_POST["k_cardcancle"])) {
	$k_cardcancle = sqlfilter($_POST["k_cardcancle"]);
} else {
	$k_cardcancle = "";
}
if (isset($_POST["k_return"])) {
	$k_return = sqlfilter($_POST["k_return"]);
} else {
	$k_return = "";
}
if (isset($_POST["k_ip"])) {
	$k_ip = sqlfilter($_POST["k_ip"]);
} else {
	$k_ip = "";
}
if (isset($_POST["k_delete"])) {
	$k_delete = sqlfilter($_POST["k_delete"]);
} else {
	$k_delete = "";
}
if (isset($_POST["k_admmemo"])) {
	$k_admmemo = sqlfilter($_POST["k_admmemo"]);
} else {
	$k_admmemo = "";
}
if (isset($_POST["k_modicont"])) {
	$k_modicont = sqlfilter($_POST["k_modicont"]);
} else {
	$k_modicont = "";
}
if (isset($_POST["k_signdate"])) {
	$k_signdate = sqlfilter($_POST["k_signdate"]);
} else {
	$k_signdate = "";
}
if (isset($_POST["k_userno"])) {
	$k_userno = sqlfilter($_POST["k_userno"]);
} else {
	$k_userno = "";
}
if (isset($_POST["k_id"])) {
	$k_id = sqlfilter($_POST["k_id"]);
} else {
	$k_id = "";
}

if (isset($_REQUEST["k_check_old"])) {
	$k_check_old = sqlfilter($_REQUEST["k_check_old"]);
} else {
	$k_check_old = "";
}
if (isset($_REQUEST["k_delete_old"])) {
	$k_delete_old = sqlfilter($_REQUEST["k_delete_old"]);
} else {
	$k_delete_old = "";
}
if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}

$k_checkdate = $admin_id . "/" . $admip;
$k_orderprice = str_replace(",", "", $k_orderprice);

$query_pdo = "select m_userno from $member where m_id = ? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($k_id));
$row = $stmt->fetch();

$k_userno = $row[0];
if ($k_duedate == "") {

	$query_pdo = "SELECT k_duedate FROM $table_k_deposit WHERE k_no=? ";
	$stmt = $pdo->prepare($query_pdo);
	$stmt->execute(array($k_no));
	$row  = $stmt->fetch();

	$k_duedate_chk = $row[0];

	if ($k_duedate_chk != "") {
		echo "<script>alert('다른 관리자가 수정하였습니다.');location.href='member_modify.php?k_no=$k_no';</script>";
		exit;
	}
}
if ($k_check == "1") {
	$k_duedate = time();
	$k_depositprice = $k_orderprice;
}
if ($k_userno == "") {
	echo "<script>alert('존재하지 않는 아이디입니다.');history.back();</script>";
	exit;
}
if ($k_check == $k_check_old) {
	$query_pdo_1 = "UPDATE $table_k_deposit SET";
	$query_pdo_1 = $query_pdo_1 . " k_orderprice=:k_orderprice,k_depositprice=:k_depositprice,k_sellprice=:k_sellprice,k_returnprice=:k_returnprice,k_depositname=:k_depositname,k_payment=:k_payment,k_ordername=:k_ordername,k_email=:k_email,k_tel=:k_tel,k_ordermemo=:k_ordermemo,k_check=:k_check,k_duedate=:k_duedate,k_cardcancle=:k_cardcancle,k_return=:k_return,k_delete=:k_delete,k_admmemo=:k_admmemo,k_modicont=:k_modicont,k_userno=:k_userno,k_id=:k_id,k_checkdate=:k_checkdate";
	$query_pdo_1 = $query_pdo_1 . " WHERE k_no = :k_no";

	$stmt = $pdo->prepare($query_pdo_1);
	$stmt->bindValue(":k_orderprice", $k_orderprice);
	$stmt->bindValue(":k_depositprice", $k_depositprice);
	$stmt->bindValue(":k_sellprice", $k_sellprice);
	$stmt->bindValue(":k_returnprice", $k_returnprice);
	$stmt->bindValue(":k_depositname", $k_depositname);
	$stmt->bindValue(":k_payment", $k_payment);
	$stmt->bindValue(":k_ordername", $k_ordername);
	$stmt->bindValue(":k_email", $k_email);
	$stmt->bindValue(":k_tel", $k_tel);
	$stmt->bindValue(":k_ordermemo", $k_ordermemo);
	$stmt->bindValue(":k_check", $k_check);
	$stmt->bindValue(":k_duedate", $k_duedate);
	$stmt->bindValue(":k_cardcancle", $k_cardcancle);
	$stmt->bindValue(":k_return", $k_return);
	$stmt->bindValue(":k_delete", $k_delete);
	$stmt->bindValue(":k_admmemo", $k_admmemo);
	$stmt->bindValue(":k_modicont", $k_modicont);
	$stmt->bindValue(":k_userno", $k_userno);
	$stmt->bindValue(":k_id", $k_id);
	$stmt->bindValue(":k_checkdate", $k_checkdate);
	$stmt->bindValue(":k_no", $k_no);
	$updated = $stmt->execute();

	if ($k_delete == "1") {

		if ($k_delete_old != $k_delete) {
			$query_pdo = "SELECT m_cointotal,m_coinuse FROM $m_bankmoney WHERE m_id=? and m_div='0' order by m_no desc,m_signdate desc ";
			$stmt = $pdo->prepare($query_pdo);
			$stmt->execute(array($k_id));
			$row = $stmt->fetch();

			$m_krwtotal = $row[0];
			$m_krwuse = $row[1];

			$m_krwtotal1 = $m_krwtotal - $k_depositprice;
			$m_restkrw = $m_krwtotal1 - $m_krwuse;

			$c_signdate = time();
			// 리스트 출력화면으로 이동한다
			$m_div = "0";

			$query2_pdo = "INSERT INTO $m_bankmoney ";
			$query2_pdo .= "(";
			$query2_pdo .= "m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate";
			$query2_pdo .= ")";
			$query2_pdo .= " VALUES ";
			$query2_pdo .= "(";
			$query2_pdo .= "'',:m_div,:k_userno,:k_id,:m_cointotal,:m_coinuse,:m_restcoin,:c_signdate";
			$query2_pdo .= ")";

			$stmt = $pdo->prepare($query2_pdo);
			$stmt->bindValue(":m_div", $m_div);
			$stmt->bindValue(":k_userno", $k_userno);
			$stmt->bindValue(":k_id", $k_id);
			$stmt->bindValue(":m_cointotal", $m_krwtotal1);
			$stmt->bindValue(":m_coinuse", $m_krwuse);
			$stmt->bindValue(":m_restcoin", $m_restkrw);
			$stmt->bindValue(":c_signdate", $c_signdate);

			$updated2 = $stmt->execute();

		}
	}
	echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
} else {

	//데이터베이스에 입력값을 삽입한다
	if (isset($_REQUEST["key"])) {
		$key = $_REQUEST["key"];
	} else {
		$key = "";
	}

	$query_pdo_2 = "UPDATE $table_k_deposit SET";
	$query_pdo_2 .= " k_orderprice=:k_orderprice,k_depositprice=:k_depositprice,k_sellprice=:k_sellprice,k_returnprice=:k_returnprice,k_depositname=:k_depositname,k_payment=:k_payment,k_ordername=:k_ordername,k_email=:k_email,k_tel=:k_tel,k_ordermemo=:k_ordermemo,k_check=:k_check,k_duedate=:k_duedate,k_checkdate=:k_checkdate,k_cardcancle=:k_cardcancle,k_return=:k_return,k_delete=:k_delete,k_admmemo=:k_admmemo,k_modicont=:k_modicont,k_userno=:k_userno,k_id=:k_id";
	$query_pdo_2 .= " WHERE k_no = :k_no";

	$stmt = $pdo->prepare($query_pdo_2);
	$stmt->bindValue(":k_orderprice", $k_orderprice);
	$stmt->bindValue(":k_depositprice", $k_depositprice);
	$stmt->bindValue(":k_sellprice", $k_sellprice);
	$stmt->bindValue(":k_returnprice", $k_returnprice);
	$stmt->bindValue(":k_depositname", $k_depositname);
	$stmt->bindValue(":k_payment", $k_payment);
	$stmt->bindValue(":k_ordername", $k_ordername);
	$stmt->bindValue(":k_email", $k_email);
	$stmt->bindValue(":k_tel", $k_tel);
	$stmt->bindValue(":k_ordermemo", $k_ordermemo);
	$stmt->bindValue(":k_check", $k_check);
	$stmt->bindValue(":k_duedate", $k_duedate);
	$stmt->bindValue(":k_checkdate", $k_checkdate);
	$stmt->bindValue(":k_cardcancle", $k_cardcancle);
	$stmt->bindValue(":k_return", $k_return);
	$stmt->bindValue(":k_delete", $k_delete);
	$stmt->bindValue(":k_admmemo", $k_admmemo);
	$stmt->bindValue(":k_modicont", $k_modicont);
	$stmt->bindValue(":k_userno", $k_userno);
	$stmt->bindValue(":k_id", $k_id);
	$stmt->bindValue(":k_no", $k_no);
	$update_2 = $stmt->execute();

	if ($update_2) {

		// 리스트 출력화면으로 이동한다
		$encoded_key = urlencode($key);

		if ($k_check == "1") {
			$c_category = "formwallet";
			$c_payment = "입금";
			$c_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$c_signdate = time();

			$query_pdo_3 = "INSERT INTO $table_krwpoint";
			$query_pdo_3 .= "(";
			$query_pdo_3 .= "c_no,c_userno,c_id,c_exchange,c_payment,c_category,c_ip,c_return,c_no1,c_signdate,c_category2";
			$query_pdo_3 .= ")";
			$query_pdo_3 .= "VALUES";
			$query_pdo_3 .= "(";
			$query_pdo_3 .= ":c_no,:k_userno,:k_id,:k_depositprice,:c_payment,:c_category,:c_ip,:k_return,:k_no";
			$query_pdo_3 .= ",:c_signdate,:c_category2";
			$query_pdo_3 .= ")";

			$stmt = $pdo->prepare($query_pdo_3);
			$c_no = "";
			$stmt->bindValue(":c_no", $c_no);
			$stmt->bindValue(":k_userno", $k_userno);
			$stmt->bindValue(":k_id", $k_id);
			$stmt->bindValue(":k_depositprice", $k_depositprice);
			$stmt->bindValue(":c_payment", $c_payment);
			$stmt->bindValue(":c_category", $c_category);
			$stmt->bindValue(":c_ip", $c_ip);
			$stmt->bindValue(":k_return", $k_return);
			$stmt->bindValue(":k_no", $k_no);
			$stmt->bindValue(":c_signdate", $c_signdate);
			$stmt->bindValue(":c_category2", $c_category2);
			$update_3 = $stmt->execute();

			if ($update_3) {

				$query_pdo = "SELECT ifnull(m_cointotal,0) m_cointotal,ifnull(m_coinuse,0) m_coinuse,ifnull(m_restcoin,0) m_restcoin FROM $m_bankmoney WHERE m_id=? and m_div = '0' order by m_no desc,m_signdate desc ";
				$stmt = $pdo->prepare($query_pdo);
				$stmt->execute(array($k_id));
				$row = $stmt->fetch();

				$m_cointotal = $row[0];
				$m_coinuse = $row[1];
				$m_restcoin = $row[2];

				$m_cointotal = $m_cointotal + $k_depositprice;
				$m_restcoin = $m_cointotal - $m_coinuse;

				$m_no1 = $k_no;

				$m_div = "0";
				$m_category = "deposit";
				$m_category2 = $k_depositprice."KRW";
				
				$query_pdo_4 = "INSERT INTO $m_bankmoney ";
				$query_pdo_4 .= "(";
				$query_pdo_4 .= "m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_coin_no,m_signdate,m_no1,m_category,m_category2";
				$query_pdo_4 .= ")";
				$query_pdo_4 .= " VALUES ";
				$query_pdo_4 .= "(";
				$query_pdo_4 .= "'',:m_div,:k_userno,:k_id,:m_cointotal,:m_coinuse,:m_restcoin,:m_coin_no,:c_signdate,:m_no1,:m_category,:m_category2";
				$query_pdo_4 .= ")";

				$stmt = $pdo->prepare($query_pdo_4);
				$stmt->bindValue(":m_div", $m_div);
				$stmt->bindValue(":k_userno", $k_userno);
				$stmt->bindValue(":k_id", $k_id);
				$stmt->bindValue(":m_cointotal", $m_cointotal);
				$stmt->bindValue(":m_coinuse", $m_coinuse);
				$stmt->bindValue(":m_restcoin", $m_restcoin);
				$stmt->bindValue(":m_coin_no", $m_div);
				$stmt->bindValue(":c_signdate", $c_signdate);
				$stmt->bindValue(":m_no1", $m_no1);
				$stmt->bindValue(":m_category", $m_category);
				$stmt->bindValue(":m_category2", $m_category2);
				$update_4 = $stmt->execute();
				//pdo end

				######################계좌잔고추적#################################
			}
		}

		echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
	} else {
		error("QUERY_ERROR");
		exit;
	}
}

		$m_module = "PHP Deposit";
		$m_type = "Update";
		$m_signdate = time(); 


		$m_modified =  "Updated Deposit " . " " . "transaction of" . " " . $k_email ;



		$query_pdo3 = "INSERT INTO $admlogs";
		$query_pdo3 .= "(";
		$query_pdo3 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate";
		$query_pdo3 .= ")";
		$query_pdo3 .= "VALUES";
		$query_pdo3 .= "(";
		$query_pdo3 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate";
		$query_pdo3 .= ")";

		$stmt = $pdo->prepare($query_pdo3);
		$stmt->bindValue(":m_adminid", $admin_id);
		$stmt->bindValue(":m_module", $m_module);
		$stmt->bindValue(":m_type", $m_type);
		$stmt->bindValue(":m_modified", $m_modified);
		$stmt->bindValue(":m_signdate", $m_signdate);
		$inserted2 = $stmt->execute();
	
?>
