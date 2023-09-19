<?
session_start();

if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = "Withdrawal_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls");  //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

$query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM coexstar.t_withdraw AS t 
INNER JOIN coexstar.m_member AS m ON m.m_id = t.t_id INNER JOIN coexstar.c_setup AS c ON c.c_no = t.t_division ORDER BY t.t_signdate";


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
            <td width="150" height="30">Status</td>
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

                if($row['t_check'] == 1) { $tcheck = "Deleted"; }
                else { $tcheck = "Pending"; }
			?>
            <tr  align="center">
              <td width='50' height='30'><?= $i++ ?> </td>
              <td width='150' height='30'><?=   $row['t_date'] ?> </td>
              <td width='120' height='30'><?=   $row['t_ordermost'] . " " . $row['c_coin'] ?></td>
              <td width='300' height='30'><?=  $tcheck ?></td> 
              <td width='300' height='30'><?=  $row['m_name'] ?></td> 
              <td width='300' height='30'><?= $row['m_age'] ?></td>
              <td width='300' height='30'><?=  $row['m_id']  ?></td>
              <td width='300' height='30'><?= $row['m_empstatus'] ?></td>
              <td width='300' height='30'><?= $row['m_empsalary']?></td>
              <td width='300' height='30'><?= $row['m_position'] ?></td>
              <td width='300' height='30'><?= $row['m_level'] ?></td>
            </tr>
        
		<?
		}
		?>
	</table>
</body>

</html>