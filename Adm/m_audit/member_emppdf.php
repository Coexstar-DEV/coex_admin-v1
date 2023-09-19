<?php
// Require composer autoload
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
    if(isset($_GET['m_adminid']))
    {

        $from = strip_tags($_GET['from']). ' ' . '00:00:00';
        $to = strip_tags($_GET['to']) . ' ' . '23:59:59';
        $m_adminid = strip_tags($_GET['m_adminid']);
   
        $query_pdo = "SELECT m_id, m_adminid, m_module, m_type, m_modified, FROM_UNIXTIME(m_signdate+8*3600) as m_date  FROM $admlogs WHERE m_adminid = '$m_adminid' AND m_signdate > unix_timestamp('$from') AND m_signdate < unix_timestamp('$to') ORDER BY m_signdate DESC LIMIT 800";
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
            <p style='font-size: 20px; font-weight: bold;'>Employee Activity</p>
            <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y', strtotime($from)) . "</p>
            <p style='font-size: 13px;'><b>To: </b>" . date('F d, Y', strtotime($to)) . "</p>       
            <table style='width:100%; overflow: wrap;'>
            <tr style='background-color: #e1e2e4'>
            <th>Date and Time</th>
            <th>Admin ID</th>
            <th>Activity Method</th>
            <th>Module</th>
            <th>Modification</th>
            </tr>
            ";
        while ($row = $stmt->fetch()) {
    
            if (!$row) {
                error("QUERY_ERROR");
                exit;
            }          
            err_log("===>" . parse_array($row));
            $m_id = $row['m_id'];
            $m_adminid = $row['m_adminid'];
            $m_module = $row['m_module'];
            $m_type = $row['m_type'];
            $m_modified = $row['m_modified']; 
            $m_signdate = $row['m_signdate'];
        
            $html .= "<tr ><td  style='font-size: 12px; text-align: center; padding: 3px;'>" . $row['m_date']. "</td>" . " <td  style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_adminid'] . "</td>" . " <td  style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_type'] . "</td>" . " <td  style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_module'] . "</td>";
            $html .=  "<td  style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_modified']  . "</tr>";
        }
        
 $html .= "   
 </table></body></html>";

$mpdf->WriteHTML($html);


    }
    
    }
}
// Output a PDF file directly to the browser
$mpdf->Output();


$html = "<table>
        <thead>
            <tr> 
                <th>Date and Time</th> 
                <th> Employee Name </th> 
                <th>Activity </th> 
                <th>Module</th>
                <th>Modification/s </th>
            </<tr>
        </thead><tbody><tr>" . "<tr>" . $m_signdate . "</tr><tr>" . $m_adminid . "</tr><tr>" . $m_type . "</tr><tr>" . $m_module . "</tr><tr>" . $m_modified . "</tr><tr>
        </tr></tbody></table>";
$mpdf->WriteHTML($html);


$m_module = "Employee Report";
$m_type = "Download";
$m_signdate = time(); 


$m_modified =  "Generated Employee Report";

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