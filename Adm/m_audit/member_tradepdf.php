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
    
               
                $m_id = strip_tags($_GET['m_id']);
               

                $m_age = strip_tags($_GET['m_age']);
                $agerange = explode("-", $m_age);
                $agerange1 = $agerange[0];
                $agerange2 = $agerange[1];
                
                $emp_status = strip_tags($_GET['emp_status']);
                $from = strip_tags($_GET['from']). ' ' . '00:00:00';
                $to = strip_tags($_GET['to']) . ' ' . '23:59:59';
    


                
                if(!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_status'] == 'All' &&  $_GET['m_age'] == 'All'  &&  $_GET['emp_range'] == 'All'){
                    echo "Supply atleast one field.";
                }  
                //Only Age
                else if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_status'] == 'All' && $_GET['emp_range'] == 'All'){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level,FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
               
                //Only Salary Range
                }  else if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_status'] == 'All' && $_GET['m_age'] == 'All'){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $emp_range = strip_tags($_GET['emp_range']);
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empsalary = '$emp_range' and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }
                //Only Employment Status
                 else if(!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_range'] == 'All' && $_GET['m_age'] == 'All'){
                    $emp_status = strip_tags($_GET['emp_status']);
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empstatus = '$emp_status' and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }
                //Both Employment Status and Salary Range
                else  if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['m_age'] == 'All' && $_GET['emp_status'] != 'All'  && $_GET['emp_range'] != 'All'){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $emp_status = strip_tags($_GET['emp_status']);
                    $emp_range = strip_tags($_GET['emp_range']);
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empstatus = '$emp_status' and m_empsalary = '$emp_range' and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }
                //Both Employment and Age
                else if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_range'] == 'All' &&  $_GET['emp_status'] != 'All'  &&  $_GET['m_age'] != 'All'){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $emp_status = strip_tags($_GET['emp_status']);
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empstatus = '$emp_status' and (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }
                //Both Age and Salary Range
                else if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_status'] == 'All' &&  $_GET['m_age'] != 'All'  &&  $_GET['emp_range'] != 'All' ){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $emp_range = strip_tags($_GET['emp_range']);
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empsalary = '$emp_range' and (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }  
                else if (!$_GET['from'] && !$_GET['to'] && !$_GET['m_id'] && $_GET['emp_status'] != 'All' &&  $_GET['m_age'] != 'All'  &&  $_GET['emp_range'] != 'All' ){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $emp_status = strip_tags($_GET['emp_status']);
                    $emp_range = strip_tags($_GET['emp_range']);
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_empstatus = '$emp_status' and  m_empsalary = '$emp_range' and (TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) BETWEEN '$agerange1' and '$agerange2') and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";
                }  
                //No From and To dates

                else if(!$_GET['from'] && !$_GET['to'] ){
                    $from2 = '2019-10-07 00:00:00';
                    $to2 = date("Y-m-d h:i:s");
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600)  as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_id = '$m_id' and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from2') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to2') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;"; 
                }  
                //User ID and From-To Dates     
                  
                else{
                    $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date,  cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE m_id = '$m_id' and cp.c_category <> 'reqorderrecv' and cp.c_signdate > (unix_timestamp('$from') + 8 *3600) AND cp.c_signdate < (unix_timestamp('$to') + 8 *3600) ORDER BY cp.c_signdate DESC LIMIT 800;";

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
                    <p style='font-size: 20px; font-weight: bold;'>Trading History</p>
                    <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y', strtotime($from)) . "</p>
                    <p style='font-size: 13px;'><b>To: </b>" . date('F d, Y', strtotime($to)) . "</p>


                        <table style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 40px; font-size: 12px;'>#</th>
                            <th style='width: 50px; font-size: 12px;'>Date</th>
                            <th style='width: 120px; font-size: 12px;'>Amount</th>
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
                        

                                    
                        $html .= "
                            <tr>
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['c_date'] . "</td>" . " 
                                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['c_return'] . " " . $row['c_coin'] . "</td>" . " 
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

$mpdf->Output();