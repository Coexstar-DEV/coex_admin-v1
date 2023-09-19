<?
session_start();

if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = "Member_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls");  //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

$query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date, cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE cp.c_category <> 'reqorderrecv'";


$stmt = $pdo->prepare($query_pdo);
$stmt->execute();



?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="100%" border='1' cellspacing='0' cellpadding='0'>
    <tr align="center">
			<td width="50" height="30"><?=M_NO?></td>
			<td width="300" height="30">Date</td>
			<td width="150" height="30">Amount</td>
            <td width="150" height="30">Customer Name</td>
			<td width="150" height="30">Age</td>
            <td width="150" height="30">Email Address</td>
			<td width="100" height="30">Employment Status</td>
            <td width="100" height="30">Salary</td>
			<td width="100" height="30">Position</td>
            <td width="100" height="30">Level</td>
		</tr>


		<?

            $i = 1;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {


			?>
            <tr  align="center">
              <td width='50' height='30'><?= $i++ ?> </td>
              <td width='150' height='30'><?=  $row['c_date'] ?> </td>
              <td width='120' height='30'><?=  $row['c_return'] . " " . $row['c_coin'] ?></td>
              <td width='300' height='30'><?=  $row['m_name'] ?></td> 
              <td width='300' height='30'><?=  $row['m_age'] ?></td> 
              <td width='300' height='30'><?= $row['m_id'] ?></td>
              <td width='300' height='30'><?=  $row['m_empstatus'] ?></td>
              <td width='300' height='30'><?= $row['m_empsalary'] ?></td>
              <td width='300' height='30'><?=  $row['m_position'] ?></td>
              <td width='300' height='30'><?= $row['m_level'] ?></td>
            </tr>
        
		<?
		}
		?>
	</table>
</body>

</html>>