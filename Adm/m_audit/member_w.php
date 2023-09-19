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

$stmt = $pdo->prepare("SELECT * FROM $admin_member AS u WHERE u.m_adminid = :admin_id");
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
   
    if(isset($_GET['m_id'])) 
    {
        $m_module = "Audit Trail";
        $m_type = "Download";
        $m_modified =  "Downloaded Withdrawal PDF";
        $m_signdate = time();
                
        $m_id = strip_tags($_GET['m_id']);
     

        $query_pdo = "SELECT ma.m_banknum, ma.m_bankname, me.m_name, me.m_id FROM $table_authorization as ma INNER JOIN $member as me ON ma.m_userno = me.m_userno WHERE me.m_id = '$m_id' LIMIT 1";           
        $stmt = $pdo->prepare($query_pdo);
        $stmt->execute();

        $query_pdo3 = "INSERT INTO $admlogs";
        $query_pdo3 .= "(";
        $query_pdo3 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate";
        $query_pdo3 .= ")";
        $query_pdo3 .= "VALUES";
        $query_pdo3 .= "(";
        $query_pdo3 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate";
        $query_pdo3 .= ")";
                
        $stmt1 = $pdo->prepare($query_pdo3);
        $stmt1->bindValue(":m_adminid", $admin_id);
        $stmt1->bindValue(":m_module", $m_module);
        $stmt1->bindValue(":m_type", $m_type);
        $stmt1->bindValue(":m_modified", $m_modified);
        $stmt1->bindValue(":m_signdate", $m_signdate);
        $stmt1->execute();


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
                    <p style='font-size: 20px; font-weight: bold;'>Withdrawal History</p>
                    <p style='font-size: 13px;'><b>Date Generated: </b>" . date('F d, Y') . "</p>
                    <p style='font-size: 13px;'><b>By: </b>" . $userRow['m_adminname'] ."</p> 
                        <table class= 'table' style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 40px; font-size: 12px;'>Form of Payment</th>
                            <th style='width: 50px; font-size: 12px;'>Bank Details</th>

                        </tr>
                    ";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                    {
                        if (!$row) {
                            error("QUERY_ERROR");
                            exit;
                        } 
                        err_log("===>" . parse_array($row));

                        $html .= "
                            <tr>
                                <td style='font-size: 12px; float: left;  padding: 3px;'>
                                <input type='checkbox' id='bank' name='bank' value='bank' checked='checked'> Bank Transfer 
                                <br>
                                <input type='checkbox' id='cheque' name='cheque' value='cheque'> Cheque Payment 
                                </td>" . " 
                                <td style='font-size: 12px; float: left;  padding: 3px;'>
                                <b>Bank Name </b>: " . $row['m_bankname']. " <br>" .
                               " <b>Account Holder</b>: " . $row['m_name']. " <br>" .
                               " <b>Account Number</b>: " . $row['m_banknum']. "<br>
                                </td>" . " 
                            </tr>";
                    }

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
                            <td > <input type='checkbox' id='bank' name='bank' value='bank' checked='checked'> Valid ID <br> </td>
                            <td > <input type='checkbox' id='bank' name='bank' value='bank'> Selfie <br> </td>
                            </tr>
                            
                            <tr >
                            <td > <input type='checkbox' id='bank' name='bank' value='bank'> Copy of Bank Book <br> </td>
                            <td > <input type='checkbox' id='bank' name='bank' value='bank'> Others _____________________ <br> </td>
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
                                 
                </td>
                
                </tr>
                </table>
                ";
                $html .= "<table class= 'table' style='width:100%; overflow: wrap;'>
                        <tr style='background-color: #e1e2e4'>
                            <th style='width: 40px; font-size: 12px;'>Form of Payment</th>
                            <th style='width: 50px; font-size: 12px;'>Prepared By:</th>

                        </tr>

                    <tr>
                        <td style='font-size: 12px; float: left;  padding: 3px;'>
                        <input type='checkbox' id='bank' name='bank' value='bank' checked='checked'> Approved 
                        <br>
                        <input type='checkbox' id='cheque' name='cheque' value='cheque'> Declined
                        <br>
                        <input type='checkbox' id='bank' name='bank' value='bank'> For Further Investigation
                        <br>
                        <br>
                        <b> Amount </b> _________________________________
                        </td> 
                        <td style='font-size: 12px;  float: left;  padding: 3px;'>
                        <br>
                        <b> SIGNED BY </b>
                        <br><br><br>
                        <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp; Name and Signature &nbsp;&nbsp;&nbsp; </font> &nbsp;&nbsp;&nbsp;
                        <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                       
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
                <input type='checkbox' id='bank' name='bank' value='bank' checked='checked'> Approved 
                <br>
                <input type='checkbox' id='cheque' name='cheque' value='cheque'> Disapproved
                <br>
                </td> 
                <td style='font-size: 12px; width:300px; float: left;  padding: 3px;'>
                <br>
                <b> SIGNED BY </b>
                <br><br><br>
                <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp; Name and Signature &nbsp;&nbsp;&nbsp; </font> &nbsp;&nbsp;&nbsp;
                <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
               
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
            <textarea style='border: transparent !important' id='rem' name='rem' rows='4' cols='50'>
            Enter your remarks here.
             </textarea> 
            </td> 
            <td style='font-size: 12px; float: left; width:300px; padding: 3px;'>
            <br>
            <b> SIGNED BY </b>
            <br><br><br>
            <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp; Name and Signature &nbsp;&nbsp;&nbsp; </font> &nbsp;&nbsp;&nbsp;
            <font style=' text-decoration: overline; margin-right: 20px;'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
           
            </td> 
        </tr>
        </table>";
        $html.=" </body></html>";

            $mpdf->WriteHTML($html);

    }
$mpdf->Output();