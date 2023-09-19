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



  $stmt = $pdo->prepare("SELECT * FROM $admin_member AS u
  WHERE u.m_adminid = :admin_id");
  $stmt->execute(array(":admin_id"=>$admin_id));
  $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

  $config = [
    'mode' => '+aCJK', 
    "autoScriptToLang" => true,
    "autoLangToFont" => true,
    "format" => "A4",
  ];

  $mpdf = new \Mpdf\Mpdf($config);
  $mpdf->useActiveForms = true;
   
  if(isset($_GET['email'])) 
  {

    $m_id = $_GET['email'];
     
    // $query_pdo = "SELECT * FROM $table_authorization WHERE m_id = '$m_id' LIMIT 1"; 
    $query_pdo = "SELECT A.m_id, B.m_name, A.m_banknum, A.m_bankname FROM $table_authorization A INNER JOIN $member B ON A.m_userno = B.m_userno WHERE A.m_id = '$m_id' LIMIT 1";          
    $stmt2 = $pdo->prepare($query_pdo);
    $stmt2->execute();
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);




             $html = "<html>
                <head>
                    <style>
                        .table {
                            border: 1px solid black;
                            border-collapse: collapse;
                        }
                        th, td{
                            border: 1px solid black;
                            border-collapse: collapse;
                        }
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
                    <p style='font-size: 20px; font-weight: bold;'>Deposit Form</p>
                    <p style='font-size: 13px;'><b>Date Generated: </b>" . date('F d, Y') . "</p>
                        <table class= 'table' style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 50px; font-size: 12px;'>Bank Details</th>

                        </tr>
                    ";

                            $html .= "
                                <tr>
                                    <td style='font-size: 12px; float: left;  padding: 3px;'>
                                    <b>Bank Name </b>: " . $row['m_bankname']. " <br>" .
                                " <b>Account Holder</b>: " . $row['m_name']. "<br>" .
                                " <b>Account Number</b>: " . $row['m_banknum']. "<br>
                                    </td>" . " 
                                </tr>";

                $html .= "</table>";
                $html .=" 
                <table class= 'table' style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>ATTACHMENT/S</th>

                </tr>
                <tr>
                <td style='font-size: 12px; float: left;  padding: 3px;'>
                            <table style='width:100%; '>
                            <tr >
                            <td style='font-size: 12px;'> <input type='checkbox' id='validid' name='validid' value='bank3'> Valid ID <br> </td>
                            <td style='font-size: 12px;'> <input type='checkbox' id='selfie' name='selfie' value='bank4'> Selfie <br> </td>
                            </tr>
                            
                            <tr >
                            <td style='font-size: 12px;'> <input type='checkbox' id='bankbook' name='bankbook' value='bank5'> Copy of Bank Book <br> </td>
                            <td style='font-size: 12px;'> <input type='checkbox' id='others' name='others' value='bank6'> Others &nbsp;&nbsp;<textarea style='border: transparent !important; font-size: 12px;' id='rem4' name='rem4' rows='1' cols='30'>&nbsp;</textarea> <br> </td>
                            </tr>
                            </table>
                                
                </td>
                
                </tr>
                </table>
                ";
                $html .=" 
                <table class= 'table' style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>REMARKS</th>

                </tr>
                <tr>
                <td style='font-size: 12px; height:50px; float: left;  padding: 3px;'>
                    <textarea style='border: transparent !important' id='rem1' name='rem1' rows='3' cols='100'>&nbsp;</textarea>      
                </td>
                
                </tr>
                </table>
                ";
                $html .= "<table class= 'table' style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 40px; font-size: 12px;'>Recommendation</th>
                            <th style='width: 50px; font-size: 12px;'>Prepared By:</th>

                        </tr>

                    <tr>
                        <td style='font-size: 12px; float: left;  padding: 3px;'>
                        <input type='radio' id='bank6' name='bank6' value='111'> Approved 
                        <br>
                        <input type='radio' id='bank6' name='bank6' value='222'> Declined
                        <br>
                        <input type='radio' id='bank6' name='bank6' value='333'> For Further Investigation
                        <br>
                        <br>
                        <b> Amount </b> <textarea style='border: transparent !important; font-size: 12px;' id='rem3' name='rem3' rows='1' cols='30'>&nbsp;</textarea>
                        </td> 
                        <td style='font-size: 12px;  float: left;  padding: 3px;'>
                        <br>
                        <b> SIGNED BY </b><br/><br/><br/>
                        <table>
                            <tr>
                                <th style='font-size: 12px; border: 0px; margin-right: 30px; text-transform: uppercase; text-decoration: underline;'>&nbsp;&nbsp;&nbsp;&nbsp;".$userRow['m_adminname']."&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th style='font-size: 12px; border: 0px; text-decoration: underline;'>&nbsp;&nbsp;&nbsp;&nbsp;".date('F d, Y')."&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            <tr style='text-align: center;'>
                                <td style='border: 0px;'><font style='font-size: 12px; text-align: center; margin-right: 30px;'><center>Name and Signature</center> </font></td>
                                <td style='border: 0px;'><font style='font-size: 12px; text-align: center;'><center> Date </center></font></td>
                            </tr>
                        </table>
                       
                        </td> 
                    </tr>
                    </table>";


                $html .= "
                <table  style='width:100%; overflow: wrap;'>
                <tr style='background-color: #e1e2e4'>
                    <th style='width: 40px; font-size: 12px;'>FOR OPERATIONS DIRECTOR USE</th>
                </tr>
                </table>
                <table style='width:100%; overflow: wrap;'>
                 <tr>
                <td style='font-size: 12px; width:40px; float: left;  padding: 3px;'>
                <b> REMARKS </b> <br> <br>
                <input type='radio' id='approval' name='approval' value='bank'> Approved 
                <br>
                <input type='radio' id='approval' name='approval' value='cheque'> Disapproved
                <br>
                </td> 
                <td style='font-size: 12px; width:300px; float: left;  padding: 3px;'>
                <br>
                <b> SIGNED BY </b><br/><br/><br/>
                <table>
                    <tr>
                        <th style='font-size: 12px; border: 0px; text-transform: uppercase;'>&nbsp;</th>
                        <th style='font-size: 12px; border: 0px;'>&nbsp;</th>
                    </tr>
                    <tr style='text-align: center;'>
                        <td style='border: 0px;'><font style='font-size: 12px; text-align: center; text-decoration: overline; text-underline-position: over;'><center>&nbsp;&nbsp;Name and Signature&nbsp;&nbsp;</center> </font></td>
                        <td style='border: 0px;'><font style='font-size: 12px; text-align: center; text-decoration: overline; text-underline-position: over;'><center> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </center></font></td>
                    </tr>
                </table>
                </td> 
            </tr>
            </table>";

            $html .= "
            <table  style='width:100%; overflow: wrap;'>
            <tr style='background-color: #e1e2e4'>
                <th style='width: 40px; width:40px; font-size: 12px;'>FOR COMPLIANCE OFFICER USE ONLY</th>
            </tr>
            </table>
            <table style='width:100%; overflow: wrap;'>
             <tr>
            <td style='font-size: 12px; width:50px;  float: left;  padding: 3px;'>
            <b> REMARKS </b>
            <textarea style='border: transparent !important; font-size: 12px;' id='rem' name='rem' rows='5' cols='50'>&nbsp;</textarea> 
            </td> 
            <td style='font-size: 12px; float: left; width:300px; padding: 3px;'>
            <br>
            <b> SIGNED BY </b>
            <br><br><br>
            <table>
                    <tr>
                        <th style='font-size: 12px; border: 0px; margin-right: 30px; text-transform: uppercase;'>&nbsp;</th>
                        <th style='font-size: 12px; border: 0px;'>&nbsp;</th>
                    </tr>
                    <tr style='text-align: center;'>
                        <td style='border: 0px;'><font style='font-size: 12px; text-align: center; text-decoration: overline; text-underline-position: over;'><center>&nbsp;&nbsp;Name and Signature&nbsp;&nbsp;</center> </font></td>
                        <td style='border: 0px;'><font style='font-size: 12px; text-align: center; text-decoration: overline; text-underline-position: over;'><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></font></td>
                    </tr>
                </table>
            </td> 
        </tr>
        </table>";
        $html.=" </body></html>";

            $mpdf->WriteHTML($html);

    }
$mpdf->Output();

$m_module = "Deposit PDF Form";
$m_type = "Download";
$m_signdate = time(); 


$m_modified =  "Generated deposit form";

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
?>
<script>
    document.getElementByClassName('payment').checked = false;

</script>