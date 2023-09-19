<?php
require_once   '../mpdf/vendor/autoload.php';
include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_SESSION["admip"])) {
    $admip = $_SESSION["admip"];
} else {
    $admip = "";
}
$config = [
    'mode' => '+aCJK', 
    // "allowCJKoverflow" => true, 
    "autoScriptToLang" => true,
    // "allow_charset_conversion" => false,
    "autoLangToFont" => true,
    "format" => "Legal",
    "orientation" => "L"
];

$mpdf = new \Mpdf\Mpdf($config);


    if(isset($_GET['from'])) { 

            
        if(isset($_GET['to'])) { 
        
                
            if(isset($_GET['m_id'])) {
        
                if(isset($_GET['emp_status']) ){

                    if(isset($_GET['emp_range']) ){
    
                        if(isset($_GET['m_age']) ){
                            
                            if(isset($_GET['c_coin']) ){

                                $m_id = strip_tags($_GET['m_id']);
                            
                                $c_coin = strip_tags($_GET['c_coin']);
                                $m_age = strip_tags($_GET['m_age']);
                                $agerange = explode("-", $m_age);
                                $agerange1 = $agerange[0];
                                $agerange2 = $agerange[1];
                                
                                $emp_status = strip_tags($_GET['emp_status']);
                                $from = strip_tags($_GET['from']). ' ' . '00:00:00';
                                $to = strip_tags($_GET['to']) . ' ' . '23:59:59';
   


                                if($_GET['from'] && $_GET['to'] && $_GET['c_coin'] ){

                                    if ( $_GET['m_id'] ){
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE m_id = '$m_id' and c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') ORDER BY t.t_signdate DESC  LIMIT 800 "; 
                            
                                    }
                                    else if ($_GET['emp_status'] && $_GET['emp_range'] && $_GET['m_age']){
                                        $emp_range = strip_tags($_GET['emp_range']);
                                        $emp_status = strip_tags($_GET['emp_status']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and m.m_empstatus = '$emp_status' and  m.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY t.t_signdate DESC  LIMIT 800 "; 
                                    }
                                    else if ($_GET['emp_status'] && $_GET['emp_range']){
                                        $emp_range = strip_tags($_GET['emp_range']);
                                        $emp_status = strip_tags($_GET['emp_status']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and m.m_empstatus = '$emp_status' and  m.m_empsalary = '$emp_range' aORDER BY t.t_signdate DESC  LIMIT 800 "; 
                                    }
                                    else if ($_GET['emp_status'] && $_GET['m_age']){
                                        $emp_status = strip_tags($_GET['emp_status']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and m.m_empstatus = '$emp_status'  and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY t.t_signdate DESC  LIMIT 800 "; 
                                    }
                                    else if ($_GET['emp_range'] && $_GET['m_age']){
                                        $emp_range = strip_tags($_GET['emp_range']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE  c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and m.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY t.t_signdate DESC  LIMIT 800 "; 
                                    }
                                    else if ($_GET['emp_status']){
                                        $emp_status = strip_tags($_GET['emp_status']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and m.m_empstatus = '$emp_status' ORDER BY t.t_signdate DESC   LIMIT 800"; 
                                    }
                                    else if ($_GET['emp_range']){
                                        $emp_range = strip_tags($_GET['emp_range']);
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') and  m.m_empsalary = '$emp_range' ORDER BY t.t_signdate DESC   LIMIT 800"; 
                                    }
                                    else if ($_GET['m_age']){
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to')  and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY t.t_signdate DESC   LIMIT 800"; 
                                    }
                                    else{
                                        $query_pdo = "SELECT m.m_id, m.m_name, m.m_empstatus, m.m_position, m.m_empsalary, m.m_level, FROM_UNIXTIME(t.t_signdate+8*3600) AS t_date, t.t_fees, t.t_delete, t.t_pending,  c.c_coin, t.t_ordermost, t.t_check, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $table_withdraw AS t 
                                        INNER JOIN $member AS m ON m.m_id = t.t_id INNER JOIN $table_setup AS c ON c.c_no = t.t_division WHERE c_coin = '$c_coin' AND t.t_signdate BETWEEN unix_timestamp('$from') AND unix_timestamp('$to') ORDER BY t.t_signdate DESC  LIMIT 800";  
                                    }
                                }
                                     

                $stmt = $pdo->prepare($query_pdo);
                $stmt->execute();


    $html = "<html>
                <head>
                    <style>
                        table, th, td {
                            border: 1px solid black;
                            border-collapse: collapse;
                            text-align: center;
                        }
                    </style>
                </head>

                <body>
                    <p style='font-size: 20px; font-weight: bold;'>Withdrawal History</p>
                    <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y', strtotime($from)) . "</p>
                    <p style='font-size: 13px;'><b>To: </b>" . date('F d, Y', strtotime($to)) . "</p>


                        <table style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 40px; font-size: 12px;'>#</th>
                            <th style='width: 50px; font-size: 12px;'>Date</th>
                            <th style='width: 120px; font-size: 12px;'>Amount</th>
                            <th style='width: 80px; font-size: 12px;'>Status</th>
                            <th style='width: 230px; font-size: 12px;'>Customer Name</th>
                            <th style='width: 35px; font-size: 12px;'>Age</th>
                            <th style='width: 280px; font-size: 12px;'>Email Address</th>
                            <th style='width: 100px; font-size: 12px;'>Employment Status</th>
                            <th style='width: 120px; font-size: 12px;'>Salary</th>
                            <th style='width: 100px; font-size: 12px;'>Position</th>
                            <th style='width: 45px; font-size: 12px;'>Level</th>
                        </tr>
                    ";
                    $i = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                    {
                        if (!$row) {
                            error("QUERY_ERROR");
                            exit;
                        }          
                        err_log("===>" . parse_array($row));
                        
                        if ($row['t_check'] == "0") {
							$tcheck = "Pending";
							if ($row['t_check'] > "1") {
								$tcheck = "Approved";
							}
						} else {
							if ($row['t_delete'] == "1") {
								$tcheck = "Cancelled";
							} else {
								$tcheck = "Approved";
							}
						}
       
                        $html .= "
                            <tr>
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['t_date'] . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  ($row['t_ordermost'] - $row['t_fees']) . " " . $row['c_coin'] . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $tcheck . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_name'] . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_age'] . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_id'] . "</td>" . "
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_empstatus'] . "</td>" ."
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_empsalary'] . "</td>" ."
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_position'] . "</td>" ."
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_level'] . "</td>
                            </tr>";
                    }

                $html .= "</table></body></html>";

            $mpdf->WriteHTML($html);

                    }
                }
            }
        }
    }
}
    }

$mpdf->Output();

$m_module = "All Withdrawal Report";
$m_type = "Download";
$m_signdate = time(); 


$m_modified =  "Generated All Withdrawal Report";

$query_pdo3 = "INSERT INTO $admlogs";
$query_pdo3 .= "(";
$query_pdo3 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate,m_ip";
$query_pdo3 .= ")";
$query_pdo3 .= "VALUES";
$query_pdo3 .= "(";
$query_pdo3 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate, :m_ip";
$query_pdo3 .= ")";

$stmt = $pdo->prepare($query_pdo3);
$stmt->bindValue(":m_adminid", $admin_id);
$stmt->bindValue(":m_module", $m_module);
$stmt->bindValue(":m_type", $m_type);
$stmt->bindValue(":m_modified", $m_modified);
$stmt->bindValue(":m_signdate", $m_signdate);
$stmt->bindValue(":m_ip", $admip);
$inserted2 = $stmt->execute();