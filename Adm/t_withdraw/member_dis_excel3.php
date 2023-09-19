<?


session_start();
#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = "코인출금내역_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls"); //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

if (isset($_REQUEST["ydate1"])) {
	$ydate1 = sqlfilter($_REQUEST["ydate1"]);
} else {
	$ydate1 = date('Y');
}
if (isset($_REQUEST["mdate1"])) {
	$mdate1 = sqlfilter($_REQUEST["mdate1"]);
} else {
	$mdate1 = date('m');
}
if (isset($_REQUEST["ddate1"])) {
	$ddate1 = sqlfilter($_REQUEST["ddate1"]);
} else {
	$ddate1 = date('d') - 2;
}
if (isset($_REQUEST["ydate2"])) {
	$ydate2 = sqlfilter($_REQUEST["ydate2"]);
} else {
	$ydate2 = date('Y');
}
if (isset($_REQUEST["mdate2"])) {
	$mdate2 = sqlfilter($_REQUEST["mdate2"]);
} else {
	$mdate2 = date('m');
}
if (isset($_REQUEST["ddate2"])) {
	$ddate2 = sqlfilter($_REQUEST["ddate2"]);
} else {
	$ddate2 = date('d');
}
if (isset($_REQUEST["wdate1"])) {
	$wdate1 = sqlfilter($_REQUEST["wdate1"]);
} else {
	$wdate1 = date('d');
}
if (isset($_REQUEST["wdate2"])) {
	$wdate2 = sqlfilter($_REQUEST["wdate2"]);
} else {
	$wdate2 = date('d');
}

if ($mdate1 != '' || $ddate1 != '' || $ydate1 != '') {
	$where_date1 = " where t_signdate > '$wdate1'";
} else {
	$where_date1 = "";
}

if ($mdate2 != '' || $ddate2 != '' || $ydate2 != '') {
	if ($where_date1 == '') {
		$where_date2 = " where t_signdate < '$wdate2'";
	} else {
		$where_date2 = " and t_signdate < '$wdate2'";
	}
} else {
	$where_date2 = "";
}

if (isset($_REQUEST["key"])) {
	$encoded_key = urlencode($key);
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["krw"])) {
	$krw = sqlfilter($_REQUEST["krw"]);
} else {
	$krw = "";
}
if (isset($_REQUEST["t_check"])) {
	$t_check = sqlfilter($_REQUEST["t_check"]);
} else {
	$t_check = "";
}
if (isset($_REQUEST["t_division"])) {
	$t_division = sqlfilter($_REQUEST["t_division"]);
} else {
	$t_division = "";
}
if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "t_no";
}
if (isset($_REQUEST["key"])) {
	$keyfield = $_REQUEST["keyfield"];
}

if ($t_check == "") {
	if ($krw == "") {
		if ($t_division != "") {
			$query_pdo = "SELECT t_division,t_no,t_check,t_userno,t_id,t_email,t_ordermost,t_orderkrw,t_depositmost,t_depositkrw,t_fees,t_signdate,t_name,t_acount,t_bankname,t_ip FROM $table_withdraw where t_signdate > ? and t_signdate < ? and t_division=? ";
		} else {
			$query_pdo = "SELECT t_division,t_no,t_check,t_userno,t_id,t_email,t_ordermost,t_orderkrw,t_depositmost,t_depositkrw,t_fees,t_signdate,t_name,t_acount,t_bankname,t_ip FROM $table_withdraw where t_signdate > ? and t_signdate < ? and t_division<>'1' ";
		}
		$query_pdo .= " and t_delete = 0  and $keyfield LIKE '%$key%' ORDER BY t_signdate DESC";
		$Board_Title = "코인출금내역";
	}
}
/*
*/
//pdo
if ($t_check == "") {
	if ($krw == "") {
		if ($t_division != "") {
			$stmt = $pdo->prepare($query_pdo);
			$stmt->execute(array($wdate1, $wdate2, $t_division));
		} else {
			$stmt = $pdo->prepare($query_pdo);
			$stmt->execute(array($wdate1, $wdate2));
		}
	}
}
//pdo end

//pdo
$total_record_pdo = $stmt->rowCount();
//pdo end

#####################################################################
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="100%" border='1' cellspacing='0' cellpadding='0'>
		<tr align="center">
			<td width="50" height="30"><?=M_NO?></td>
			<td width="80" height="30"><?=M_DIVISION?></td>
			<td width="50" height="30">완료</td>
			<td width="200" height="30"><?=M_ID?>(번호)</td>
			<td width="150" height="30"><?=M_NAME?></td>
			<td width="120" height="30"><?=M_ORDER_COST?></td>
			<td width="120" height="30"><?=M_CLOSE_COST?></td>
			<td width="150" height="30"><?=M_SIGN_DATE?></td>
			<td width="100" height="30"><?=M_IP?></td>
		</tr>
		</tr>
		<?
		#####################################################################

		$ii = 0;
		for ($i = 0; $i < $total_record_pdo; $i++) {

			//pdo
			if ($t_check == "") {
				if ($krw == "") {
					if ($t_division != "") {
						$stmt = $pdo->prepare($query_pdo);
						$stmt->execute(array($wdate1, $wdate2, $t_division));
						$row = $stmt->fetchAll();
					} else {
						$stmt = $pdo->prepare($query_pdo);
						$stmt->execute(array($wdate1, $wdate2));
						$row = $stmt->fetchAll();
					}
				}
			}
			//pdo end


			$t_division = $row[$i][0];
			$t_no = $row[$i][1];
			$t_check = $row[$i][2];
			$t_userno = $row[$i][3];
			$t_id = $row[$i][4];
			$t_email = $row[$i][5];
			$t_ordermost = $row[$i][6];
			$t_orderkrw = $row[$i][7];
			$t_depositmost = $row[$i][8];
			$t_depositkrw = $row[$i][9];
			$t_fees = $row[$i][10];
			$t_signdate = $row[$i][11];
			$t_name = $row[$i][12];
			$t_acount = $row[$i][13];
			$t_bankname = $row[$i][14];
			$t_ip = $row[$i][15];

			if ($t_check == "0") {
				$t_check = "X";
				$kk_bgcolor = "#feeee0";
			} else {
				$t_check = "Y";
				$kk_bgcolor = "#FFFFFF";
			}
			$t_signdate = date("Y-m-d H:i:s", $t_signdate);

			$query_pdo2 = "SELECT c_title,c_wcommission,c_limit,c_asklimit,c_use,c_signdate FROM $table_setup WHERE c_no=? and c_use='1'";
			$stmt = $pdo->prepare($query_pdo2);
			$stmt->execute(array($t_division));
			$row2 = $stmt->fetch();

			$t_division = $row2[0];
			$c_wcommission = $row2[1];
			$c_limit = $row2[2];
			$c_asklimit = $row2[3];
			$c_use = $row2[4];
			$c_signdate = $row2[5];


			#####################################################################
			?>
		<tr align="center" bgcolor="<?= $kk_bgcolor ?>">
			<td height="30"><?= $t_no ?></td>
			<td height="30" align="center"><?= $t_division ?></td>
			<td height="30" align="center"><?= $t_check ?></td>
			<td height="30"><?= $t_id ?>(<?= $t_userno ?>)</td>
			<td height="30"><?= $t_name ?></td>
			<td height="30"><B><?= number_format($t_ordermost) ?></B>
			</td>
			<td height="30" align="center"><?= number_format($t_depositmost) ?></td>
			<td height="30"><?= $t_signdate ?></td>
			<td height="30"><?= $t_ip ?></td>
		</tr>
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