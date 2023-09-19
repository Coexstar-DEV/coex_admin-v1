<meta charset="utf-8">
<?php
date_default_timezone_set('UTC');
// date_default_timezone_set('Asia/Manila');


// Require composer autoload
require_once   '../mpdf/vendor/autoload.php';
include_once "../common/user_function.php";
include_once "../common/dbconn.php";
// Create an instance of the class:


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


                $from = strip_tags($_GET['from']).' '.'00:00:00';
                $to = strip_tags($_GET['to']).' '.'22:59:59';
    
            $query_pdo = "SELECT *, FROM_UNIXTIME(m_signdate+8*3600) as m_sign FROM $member WHERE m_signdate > unix_timestamp('$from')  AND m_signdate < unix_timestamp('$to') ORDER BY m_signdate DESC LIMIT 800 "; 




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
                <p style='font-size: 20px; font-weight: bold;'>Member Registration</p>
                <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y', strtotime($from)) . "</p>
                <p style='font-size: 13px;'><b>To: </b>" . date('F d, Y', strtotime($to)) . "</p>

                <table style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>#</th>
                    <th style='width: 50px; font-size: 12px;'>Registered Date</th>
                    <th style='width: 50px; font-size: 12px;'>Level</th>
                    <th style='width: 120px; font-size: 12px;'>Customer Name</th>
                    <th style='width: 230px; font-size: 12px;'>Email Address</th>
                    <th style='width: 35px; font-size: 12px;'>Birthday</th>
                    <th style='width: 280px; font-size: 12px;'>Address</th>
                    <th style='width: 100px; font-size: 12px;'>Phone Number</th>
                    <th style='width: 120px; font-size: 12px;'>Employment Status</th>
                </tr>
            ";
            $i = 1;
            while ($row = $stmt->fetch()) {
    
            if (!$row) {
                error("QUERY_ERROR");
                exit;
            }          
            err_log("===>" . parse_array($row));
            $m_id = $row['m_id'];
            $m_name = $row['m_name'];
            $m_level = $row['m_level'];
            $m_signdate = $row['m_signdate'];
            $m_handphone = $row['m_handphone'];
            $m_contury = $row['m_contury'];
            $m_conturyname = $row['m_conturyname'];
            $m_birtday = $row['m_birtday'];
            $m_empstatus = $row['m_empstatus'];
            $m_address = $row['m_address'];


            $html .= "
            <tr>
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_sign'] . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_level'] . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_name'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_id'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_birtday'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_address'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_contury'] . ' ' . $row['m_handphone'] . "</td>" ."
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_empstatus'] . "</td>" ."
            </tr> .";     }
        
        $html .= "   
        </table></body></html>";
        
        $mpdf->WriteHTML($html);
}
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