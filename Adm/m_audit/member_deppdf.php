<meta charset="utf-8">
<?php
// Require composer autoload
date_default_timezone_set('UTC');
require_once   '../mpdf/vendor/autoload.php';
include_once "../common/user_function.php";
include_once "../common/dbconn.php";
// Create an instance of the class:
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

if(isset($_GET['from']))
{
  if(isset($_GET['to']))
  {
        if(isset($_GET['m_id']) ){

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
                $from = strip_tags($_GET['from']).' '.'00:00:00';
                $to = strip_tags($_GET['to']).' '.'23:59:59';

        //with FROM and TO - ALL
      

        if($_GET['from'] && $_GET['to'] && $_GET['c_coin'] == 'PHP' ){

            if ( $_GET['m_id'] ){
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_id = '$m_id' ORDER BY kk.k_signdate DESC LIMIT 800"; 
    
            }
            else if ($_GET['emp_status'] && $_GET['emp_range'] && $_GET['m_age']){
                $emp_range = strip_tags($_GET['emp_range']);
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_empstatus = '$emp_status' and  mm.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['emp_status'] && $_GET['emp_range']){
                $emp_range = strip_tags($_GET['emp_range']);
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_empstatus = '$emp_status' and  mm.m_empsalary = '$emp_range' ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['emp_status'] && $_GET['m_age']){
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_empstatus = '$emp_status' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['emp_range'] && $_GET['m_age']){
                $emp_range = strip_tags($_GET['emp_range']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and  mm.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['emp_status']){
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_empstatus = '$emp_status' ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['emp_range']){
                $emp_range = strip_tags($_GET['emp_range']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and mm.m_empsalary = '$emp_range' ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else if ($_GET['m_age']){
                $emp_range = strip_tags($_GET['emp_range']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') AND kk.k_signdate < unix_timestamp('$to') and (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
            else   {
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, k_payment, k_orderprice, FROM_UNIXTIME(kk.k_signdate+8*3600) as c_sign, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, ifnull(k_check,0) as k_check FROM $member as mm INNER JOIN $table_k_deposit as kk ON mm.m_userno = kk.k_userno WHERE kk.k_signdate > unix_timestamp('$from') ORDER BY kk.k_signdate DESC LIMIT 800"; 
            }
        }else{


            if ( $_GET['m_id'] ){
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_id = '$m_id' and c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to') ORDER BY cp.c_signdate DESC LIMIT 800";
    
            }
            else if ($_GET['emp_status'] && $_GET['emp_range'] && $_GET['m_age']){
                $emp_range = strip_tags($_GET['emp_range']);
                $emp_status = strip_tags($_GET['emp_status']);

                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE  c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and mm.m_empstatus = '$emp_status' and  mm.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY cp.c_signdate DESC LIMIT 800";


            }
            else if ($_GET['emp_status'] && $_GET['emp_range']){
                $emp_range = strip_tags($_GET['emp_range']);
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and mm.m_empstatus = '$emp_status' and  mm.m_empsalary = '$emp_range' ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else if ($_GET['emp_status'] && $_GET['m_age']){
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and mm.m_empstatus = '$emp_status' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else if ($_GET['emp_range'] && $_GET['m_age']){
                $emp_range = strip_tags($_GET['emp_range']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and  mm.m_empsalary = '$emp_range' and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else if ($_GET['emp_status']){
                $emp_status = strip_tags($_GET['emp_status']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE  c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and mm.m_empstatus = '$emp_status'  ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else if ($_GET['emp_range']){
                $emp_range = strip_tags($_GET['emp_range']);
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')   and  mm.m_empsalary = '$emp_range' ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else if ($_GET['m_age']){
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE  c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to')  and  (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') ORDER BY cp.c_signdate DESC LIMIT 800";
            }
            else   {
                $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, c_return, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_sign, cs.c_coin, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age FROM $member as mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE c_coin = '$c_coin' and  cp.c_category = 'reqorderrecv' and cp.c_signdate > unix_timestamp('$from') AND cp.c_signdate < unix_timestamp('$to') ORDER BY cp.c_signdate DESC LIMIT 800";
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
                <p style='font-size: 20px; font-weight: bold;'>Deposit History</p>
                <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y', strtotime($from)) . "</p>
                <p style='font-size: 13px;'><b>To: </b>" . date('F d, Y', strtotime($to)) . "</p>

                <table style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>#</th>
                    <th style='width: 50px; font-size: 12px;'>Date</th>
                    <th style='width: 120px; font-size: 12px;'>Amount</th>
                    <th style='width: 45px; font-size: 12px;'>Status</th>
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
            while ($row = $stmt->fetch()) {
    
            if (!$row) {
                error("QUERY_ERROR");
                exit;
            }          
            err_log("===>" . parse_array($row));
            $c_sign = $row['c_sign'];
            $m_name = $row['m_name'];
            $m_id = $row['m_id'];
            $c_return = $row['c_return'];
            $m_age = $row['m_age'];
            $m_position = $row['m_position'];
            $m_empsalary = $row['m_empsalary'];
            $m_level = $row['m_level'];
            $c_coin = $row['c_coin'];

            if($row['k_check'] == "1") {
                $kstat = "O";
            }
            else {
                $kstat = "X";
            }

            if ($row['k_payment'] == "0") {
                $kpay = "No Passbook";
            } 
            else if($row['k_payment'] == "1") {
                $kpay = "Card";
            }
            else if($row['k_payment'] == "2") {
                $kpay = "Mobile";
            }
             else if($row['k_payment'] == "3") {
                $kpay = "Virtual Account";
            }
            else{
                $kpay = "Remittance without bank book";
            }

            $html .= "
            <tr>
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['c_sign'] . "</td> . " ;

                if (is_null($row['c_return'])) {
                    $html .="<td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['k_orderprice'] . " PHP" . "<br><br>" . $kpay . "</td> ";
                }  else{
                     $html .="<td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['c_return'] . " " . $row['c_coin'] . "</td> ";
                }
                

            $html .="    
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $kstat . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_name'] . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_age'] . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_id'] . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_empstatus'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_empsalary'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_position'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_level'] . "</td>
            </tr> .";     }
        
        $html .= "   
        </table></body></html>";
        
        $mpdf->WriteHTML($html);
        }
    }
        }
}
}}
}
// Output a PDF file directly to the browser
$mpdf->Output();


// $html = "<table>
//         <thead>
//             <tr> 
//                 <th>Date and Time</th> 
//                 <th> Employee Name </th> 
//                 <th>Activity </th> 
//                 <th>Module</th>
//                 <th>Modification/s </th>
//             </<tr>
//         </thead><tbody><tr>" . "<tr>" . $m_signdate . "</tr><tr>" . $m_adminid . "</tr><tr>" . $m_type . "</tr><tr>" . $m_module . "</tr><tr>" . $m_modified . "</tr><tr>
//         </tr></tbody></table>";
// $mpdf->WriteHTML($html);


$m_module = "All Deposit Report";
$m_type = "Download";
$m_signdate = time(); 


$m_modified =  "Generated All Deposit Report";

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