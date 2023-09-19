<?php
	require_once   '../mpdf/vendor/autoload.php';
	include_once "../common/user_function.php";
	include_once "../common/dbconn.php";
	include "../inc/adm_chk.php";

  $config = [
      'mode' => '+aCJK', 
      "autoScriptToLang" => true,
      "autoLangToFont" => true,
      "format" => "Legal",
      "orientation" => "L"
  ];

  $mpdf = new \Mpdf\Mpdf($config);
    
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
                      </tr>";
                      
                      $query_pdo = "SELECT m_name, m_id, m_empstatus, m_position, m_empsalary, m_signdate, m_level, FROM_UNIXTIME(cp.c_signdate+8*3600) as c_date, cp.c_pay, TIMESTAMPDIFF(YEAR, m_birtday, CURDATE()) as m_age, c_return, FROM_UNIXTIME(cp.c_signdate), cs.c_coin FROM $member mm INNER JOIN $table_point as cp on mm.m_id = cp.c_id INNER JOIN $table_setup AS cs on cp.c_div = cs.c_no WHERE cp.c_category <> 'reqorderrecv' LIMIT 850"; 

                      $stmt = $pdo->prepare($query_pdo);
                      $stmt->execute();

                      $i = 1;
                      while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                      {
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
              $mpdf->Output();

?>