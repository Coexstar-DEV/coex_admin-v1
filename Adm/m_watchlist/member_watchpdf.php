
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
$stmt = $pdo->prepare("SELECT * FROM $admin_member AS u
WHERE u.m_adminid = :admin_id");
$stmt->execute(array(":admin_id"=>$admin_id));
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

$config = [
    'mode' => '+aCJK', 
    // "allowCJKoverflow" => true, 
    "autoScriptToLang" => true,
    // "allow_charset_conversion" => false,
    "autoLangToFont" => true,
    "format" => "A4",

];
$mpdf = new \Mpdf\Mpdf($config);
$mpdf->useActiveForms = true;

$html = "<html>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }
        textarea
        {
            border:1px solid #e1e2e4;
            background-color: #e1e2e4;
            background-color:#e1e2e4;
            width: 100%;
        }
    </style>
</head>

<body>
    <p style='font-size: 20px; font-weight: bold;'>Possible Watchlist</p>
    <p style='font-size: 13px;'><b>Date Generated: </b>" . date('F d, Y') . "</p>
    <p style='font-size: 13px;'><b>By: </b>" . $userRow['m_adminname'] . "</p>";

        if(isset($_GET['m_name']))
        {
                $m_name = $_GET['m_name'];
                
                $sepname = explode(" ", $m_name);
                $fname = $sepname[0];
                $lname = $sepname[1];

            
            
                $query_pdo = "SELECT * FROM $watchlist WHERE m_name LIKE '%$fname%' OR m_name LIKE '%$lname%'";
                    
                

               
                
                $stmt = $pdo->prepare($query_pdo);
                
                $stmt->execute();

                $html .=    "
                <table style='width:100%; overflow: wrap;'>
                    <tr> <th> Watchlist Record </th> </tr>
                </table>
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
                $m_birthday = $row['m_birthday'];
                $m_handphone = $row['m_handphone'];    
                $m_address = $row['m_address'];                  
                $html .= "
                <tr>
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $i++ . "</td>" . " 
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_name'] . "</td>" . " 
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_id'] . "</td>" . "
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_birthday'] . "</td>" . " 
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_handphone'] . "</td>" . " 
                    <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row['m_address'] . "</td>" . " 
                </tr>";      
              }
               $html .= "</table>";


               $query_pdo1 =  "SELECT * FROM $member WHERE m_name LIKE '%$fname%' OR m_name LIKE '%$lname%'";
        
            
      


               $html .=    "
               <table style='width:100%; overflow: wrap;'>
                   <tr> <th> Member List Record </th> </tr>
               </table>
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
               $stmt = $pdo->prepare($query_pdo1);
               $stmt->execute();
               $a = 1;
               while ($row1 = $stmt->fetch()) {
       
               if (!$row1) {
                   error("QUERY_ERROR");
                   exit;
               }          
               err_log("===>" . parse_array($row1));
               $m_name = $row1['m_name'];
               $m_id = $row1['m_id'];
               $m_birtday = $row1['m_birtday'];
               $m_handphone = $row1['m_handphone'];    
               $m_address = $row1['m_address'];                  
               $html .= "
               <tr>
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $a++ . "</td>" . " 
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row1['m_name'] . "</td>" . " 
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row1['m_id'] . "</td>" . "
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row1['m_birtday'] . "</td>" . " 
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row1['m_handphone'] . "</td>" . " 
                   <td style='font-size: 12px; text-align: center; padding: 3px;'>" .  $row1['m_address'] . "</td>" . " 
               </tr>";       
            }
           
           $html .= "  </table>";
           $html .=    "
           <table style='width:100%; overflow: wrap;'>
               <tr> <th> REMARKS/FINDINGS </th> </tr>
           </table>
           <table style='width:100%; overflow: wrap;'>
               <tr style='background-color: #e1e2e4; height: 500px'>
               <td>
               <textarea style='border: transparent !important' id='rem' name='rem' rows='4' cols='50'>
              Enter your remarks here.
               </textarea> 
   
        
               </td>
          </tr>
          </table>";
    
          $html .=    "
          <table style='width:100%; overflow: wrap;'>
              <tr> <th> ACTION TAKEN </th> </tr>
          </table>
          <table style='width:100%; overflow: wrap;'>
              <tr style='background-color: #e1e2e4; height: 500px'>
              <td>
              <textarea style='border: transparent !important' id='act' name='act' rows='4' cols='50'>
              Enter your remarks here.
               </textarea>     
              </td>
         </tr>
         </table>";
   
         $html .=    "
         <table style='width:100%; overflow: wrap;'>
             <tr> <th> VERIFIED BY </th>
              <th> DATE VERIFIED </th> </tr>
   
             <tr style='background-color: #e1e2e4; height: 500px'>
             <td> Patwin Ng   </td>
             </tr>
        </table>";
           $html .= "</body></html>";
           
   
              $mpdf->WriteHTML($html);
              $html .= "</body></html>";
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