
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
    "format" => "A4",
];
$mpdf = new \Mpdf\Mpdf($config);


        if(isset($_GET['m_name']) ){


                    if(isset($_GET['m_birtday']) ){



                $m_name = strip_tags($_GET['m_name']);
               

                $sepname = explode(" ", $m_name);
                $fname = $sepname[0];
                $lname = $sepname[1];
                
                $m_birtday = strip_tags($_GET['m_birtday']);
    
      
         if(!$_GET['m_birtday'] && $_GET['m_name']){
            $query_pdo =  "SELECT * FROM $member WHERE (m_name LIKE '%$fname%') AND (m_name LIKE '%$lname%')";
         }else{
            $query_pdo =  "SELECT * FROM $member WHERE (m_name LIKE '%$fname%') AND (m_name LIKE '%$lname%') and m_birtday = '$m_birtday'";
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
                <p style='font-size: 20px; font-weight: bold;'>Possible Multiple Accounts</p>
                <p style='font-size: 13px;'><b>From: </b>" . date('F d, Y') . "</p>
                <table style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>#</th>
                    <th style='width: 50px; font-size: 12px;'>Name</th>
                    <th style='width: 120px; font-size: 12px;'>Email Address</th>
                    <th style='width: 120px; font-size: 12px;'>Birthday</th>
                    <th style='width: 120px; font-size: 12px;'>Phone Number</th>
                    <th style='width: 120px; font-size: 12px;'>Address</th>
                </tr>
            ";
            $i = 1;
            while ($row = $stmt->fetch()) {
    
            if (!$row) {
                error("QUERY_ERROR");
                exit;
            }          
            err_log("===>" . parse_array($row));
            $m_name = $row['m_name'];
            $m_id = $row['m_id'];
            $m_birtday = $row['m_birtday'];
            $m_handphone = $row['m_handphone'];    
            $m_address = $row['m_address'];                  
            $html .= "
            <tr>
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_name'] . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_id'] . "</td>" . "
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_birtday'] . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_handphone'] . "</td>" . " 
                <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_address'] . "</td>" . " 
            </tr>";        }
        
        $html .= "   
        </table></body></html>";
        
        $mpdf->WriteHTML($html);
        }
    }


$mpdf->Output();


$m_module = "Multiple Account Report";
$m_type = "Download";
$m_signdate = time(); 


$m_modified =  "Generated Multiple Account Report";

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