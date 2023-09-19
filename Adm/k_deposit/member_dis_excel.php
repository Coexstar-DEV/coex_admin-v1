<?
if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = M_KRW.M_DEPOSIT.M_ORDER.M_HIS."_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls");  //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";

//$query = "SELECT no,id,signdate,ip FROM $member_cnt ";
//$krw = $_POST["krw"];
//$t_check = $_POST["t_check"];

//$wdate2 = $_POST["wdate2"];
//$wdate1 = $_POST["wdate1"];

if (isset($_REQUEST["wdate2"])) {
	$wdate2 = sqlfilter($_REQUEST["wdate2"]);
} else {
	$wdate2 = "";
}
if (isset($_REQUEST["wdate1"])) {
	$wdate1 = sqlfilter($_REQUEST["wdate1"]);
} else {
	$wdate1 = "";
}
if (isset($_REQUEST["wdate2"])) {
	$wdate2 = sqlfilter($_REQUEST["wdate2"]);
} else {
	$wdate2 = "";
}
if (isset($_REQUEST["k_checkk"])) {
	$k_checkk = sqlfilter($_REQUEST["k_checkk"]);
} else {
	$k_checkk = "";
}
if (isset($_REQUEST["level_l"])) {
	$level_l = sqlfilter($_REQUEST["level_l"]);
} else {
	$level_l = "";
}
if (isset($_REQUEST["dis"])) {
	$dis = sqlfilter($_REQUEST["dis"]);
} else {
	$dis = "";
}
if (isset($_REQUEST["member_count"])) {
	$member_count = sqlfilter($_REQUEST["member_count"]);
} else {
	$member_count = "";
}


if ($k_checkk == "") {
	$query = "SELECT k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id FROM $table_k_deposit where k_signdate > '$wdate1' and k_signdate < '$wdate2'";
	$query_pdo = "SELECT k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id FROM $table_k_deposit where k_signdate > ? and k_signdate < ? ";

	if ($key != "") {
		$query = $query . " and $keyfield LIKE '%$key%' ";
		$query_pdo .= " and $keyfield LIKE '%$key%' ";
	}
} else {
	if ($k_checkk == "12") {
		$k_check = "1";
	} else {
		$k_check = "0";
	}
	$query = "SELECT k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id FROM $table_k_deposit  where k_check='$k_check' and  k_signdate > '$wdate1' and k_signdate < '$wdate2'  ";
	$query_pdo = "SELECT k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id FROM $table_k_deposit  where k_check=? and  k_signdate > ? and k_signdate < ?  ";

	if ($key != "") {
		$query = $query . "and $keyfield LIKE '%$key%'  ";
		$query_pdo .= "and $keyfield LIKE '%$key%'  ";
	}
}
$query = $query . "ORDER BY k_signdate DESC";
$query_pdo .= "ORDER BY k_signdate DESC";
//pdo
if ($k_checkk == "") {
	$stmt = $pdo->prepare($query_pdo);
	$stmt->execute(array($wdate1, $wdate2));
	$result_pdo = $stmt->fetch();
} else {
	$stmt = $pdo->prepare($query_pdo);
	$stmt->execute(array($k_check, $wdate1, $wdate2));
	$result_pdo = $stmt->fetch();
}
//pdo end

//pdo
$total_record_pdo = $stmt->rowCount();
//pdo end

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="100%" border='1' cellspacing='0' cellpadding='0'>
		<tr align="center">
			<td width="50" height="30"><?=M_NO?></td>
			<td width="300" height="30"><?=M_ID?>(<?=M_NO?>)</td>
			<td width="150" height="30"><?=M_ORDER.M_PRICE?></td>
			<td width="150" height="30"><?=M_DEPOSIT.M_PRICE?></td>
			<td width="150" height="30"><?=M_REFUND.M_PRICE?></td>
			<td width="200" height="30"><?=M_DEPOSITOR?></td>
			<td width="120" height="30"><?=M_PAY?></td>
			<td width="100" height="30">확인</td>
			<td width="150" height="30"><?=M_ORDER.M_DATE?></td>
		</tr>
		<?
		#####################################################################

		$ii = 0;
		for ($i = 0; $i < $total_record_pdo; $i++) {

			if ($k_checkk == "") {
				$stmt = $pdo->prepare($query_pdo);
				$stmt->execute(array($wdate1, $wdate2));
				$row = $stmt->fetchAll();
			} else {
				$stmt = $pdo->prepare($query_pdo);
				$stmt->execute(array($k_check, $wdate1, $wdate2));
				$row = $stmt->fetchAll();
			}
			//pdo end

			$k_no = $row[$i][0];
			$k_orderprice = $row[$i][1];
			$k_depositprice = $row[$i][2];
			$k_returnprice = $row[$i][3];
			$k_depositname = $row[$i][4];
			$k_payment = $row[$i][5];
			$k_check = $row[$i][6];
			$k_signdate = $row[$i][7];
			$k_userno = $row[$i][8];
			$k_id = $row[$i][9];

			if ($k_payment == "1") {
				$k_payment = "카드";
			} else if ($k_payment == "2") {
				$k_payment = "모바일";
			} else if ($k_payment == "3") {
				$k_payment = "가상계좌";
			} else {
				$k_payment = "무통장";
			}
			if ($k_check == "0") {
				$k_check = "X";
			} else {
				$k_check = "Y";
			}
			$k_signdate = date("Y-m-d H:i:s", $k_signdate);

			if ($k_check == "Y") {
				$kk_bgcolor = "#FFFFFF";
			} else {
				$kk_bgcolor = "#f8f8f8";
			}

			#####################################################################
			?>

		<tr align="center" bgcolor="<?= $kk_bgcolor ?>">
			<td height="30"><?= $k_no ?></td>
			<td height="30"><?= $k_id ?>(<?= $k_userno ?>)</td>
			<td height="30"><?= number_format($k_orderprice) ?></td>
			<td height="30"><B><?= number_format($k_depositprice) ?></B> </td>
			<td height="30" align="center"><?= $k_returnprice ?></td>
			<td height="30" align="center"><?= $k_depositname ?></td>
			<td height="30"><?= $k_payment ?></td>
			<td height="30"><?= $k_check ?></td>
			<td height="30"><?= $k_signdate ?></td>
		</tr>

		<?
			$article_num--;
			$ii++;
		}
		$chk_num = $last - $first + 1;
		?>
	</table>
	</td>
	</tr>
	</table>

</body>

</html>