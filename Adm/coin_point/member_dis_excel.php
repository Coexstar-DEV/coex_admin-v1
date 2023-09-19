<?
session_start();

if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = "TradeHistory_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls");  //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

$key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
$keyfield = isset($_REQUEST["keyfield"]) ? $_REQUEST["keyfield"] : "";
if (isset($_REQUEST["c_div_div"])) {
    $c_div_div = sqlfilter($_REQUEST["c_div_div"]);
} else {
    $c_div_div = "";
}

$encoded_key = urlencode($key);

if ($c_div_div == "") {
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate FROM $table_point where c_userno <> 0 ";

    if ($key != "") {
        $query_pdo .= " and $keyfield LIKE '%$key%' ";
    }
    $pdo_in = null;
} else {
    $query_pdo = "SELECT c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_category2,c_ip,c_return,c_no1,c_no2,c_signdate FROM $table_point  where c_div=? and c_userno <> 0 ";
    if ($key != "") {
        $query_pdo .= " and $keyfield LIKE '%$key%' ";
    }
    $pdo_in = [$c_div_div];
}
$query_pdo .= "ORDER BY c_no+0 DESC";

err_log("===>" . $query_pdo);

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="1300" border='0' cellspacing='0' cellpadding='0'>
			<tr>
				<td colspan=9 height=3 bgcolor='#ffffff'></td>
			</tr>
			<tr align="center" bgcolor='#ffffff' class="list_title">
				<td width="150" height="30"><?=M_DATE1?></td>
				<td width="100" height="30"><?=M_NO?></td>
				<td width="120" height="30"><?=M_ID?>(<?=M_NO?>)</td>
				<td width="150" height="30"><?=M_CONTENT?></td>
				<td width="120" height="30"><?=M_ACQUIRE.M_PRICE1?></td>
				<td width="120" height="30"><?=M_PAYMENT?></td>
				<td width="120" height="30"><?=M_CLOSED.M_PRICE1?></td>
				<td width="120" height="30"><?=M_BUY.M_NO?></td>
				<td width="90" height="30"><?=M_SELL.M_NO?></td>
				<td width="90" height="30"><?=M_DIVISION?></td>
				<td width="90" height="30"><?=M_IP?></td>
			</tr>
			<tr>
				<td colspan=12 height=2 bgcolor='#D2DEE8'></td>
			</tr>
			<?

			$ii = 0;
			$stmt = pdo_excute("select", $query_pdo, $pdo_in);
			while ($row = $stmt->fetch()) {

				$c_no = $row[0];
				$c_div = $row[1];
				$c_userno = $row[2];
				$c_id = $row[3];
				$c_exchange = $row[4];
				$c_payment = $row[5];
				$c_category = $row[6];
				$c_category2 = $row[7];
				$c_ip = $row[8];
				$c_return = $row[9];
				$c_no1 = $row[10];
				$c_no2 = $row[11];
				$c_signdate = $row[12];

				$c_signdate = date("Y-m-d H:i:s", $c_signdate);

				if (($ii + 1) % 2 == 0) {
					$kk_bgcolor = "#FFFFFF";
				} else {
					$kk_bgcolor = "#F6F6F6";
				}

				#####################################################################
				?>

			<tr align="center">
				<td height="40"><?= $c_signdate ?></td>
				<td height="40"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>"><?= $c_no ?></td>
				<td height="40"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&c_no=<?= $c_no ?>"><B><?= $c_id ?>(<?= $c_userno ?>)</B>
					</a>
				</td>
				<td height="40" align="center"><?= $c_category2 ?></td>
				<td height="40" align="center"><?= $c_exchange ?></td>
				<td height="40"><?= $c_payment ?></td>
				<td height="40"><?= numberformat($c_return, "money3", 8) ?></td>
				<td height="40"><?= $c_no1 ?></td>
				<td height="40"><?= $c_no2 ?></td>
				<td height="40"><?= $c_category ?></td>
				<td height="40"><?= $c_ip ?></td>

			</tr>
			<tr>
				<td colspan=12 height=1 bgcolor='#D2DEE8'></td>
			</tr>

			<?
				$article_num--;
				$ii++;
			}
			$chk_num = $last - $first + 1;
			?>
		</table>
</body>

</html>