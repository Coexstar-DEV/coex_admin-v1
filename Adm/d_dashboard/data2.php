<?php
  
include "../common/user_function.php";
include "../common/dbconn.php";

//   Generate JSON file per coin per market

//   Output all market payment
  $stmt2 = $pdo->prepare("SELECT c.c_no, m.m_pay FROM coexstar.m_setup AS m
                                            LEFT JOIN coexstar.c_setup AS c
                                                   ON c.c_no = m.m_div
                                                WHERE c.c_coin IS NOT NULL AND
                                                      c.c_use <> 0 AND
                                                      m.m_pay <> 'ETH' AND
                                                      m.m_pay = c.c_coin
                                            ORDER BY c.c_coin");
  $stmt2->execute();
  while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)):
    $c_no2 = $row2['c_no'];
    $m_pay2 = $row2['m_pay'];

    // Output all coins with their market payment
    $stmt = $pdo->prepare("SELECT c.c_coin, c.c_no, m.m_pay, '$c_no2' AS c_pay FROM coexstar.m_setup AS m
                                                                          LEFT JOIN coexstar.c_setup AS c
                                                                                 ON c.c_no = m.m_div
                                                                              WHERE c.c_coin IS NOT NULL AND
                                                                                    c.c_use <> 0 AND
                                                                                    m.m_pay <> 'ETH' AND
                                                                                    m.m_pay <> c.c_coin AND
                                                                                    c.c_basecoin <> 1 AND
                                                                                    m.m_pay = '$m_pay2'
                                                                           ORDER BY c.c_coin");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
      $c_coin = $row['c_coin'];
      $c_no = $row['c_no'];
      $m_pay = $row['m_pay'];
      $c_pay = $row['c_pay'];
    
        // Output date and sum of each coin based on market price
        $stmt1 = $pdo->prepare("SELECT '$payname' as payname,  CONCAT(monthname(FROM_UNIXTIME(c_signdate+8*3600)), ' ', YEAR(FROM_UNIXTIME(c_signdate+8*3600))) AS signdate, ROUND(sum(c_exchange+0), 6) as sumcoin, '$coinname' as coinname FROM  coexstar.coin_point 
        WHERE c_signdate > (1570406400 + 8*3600) and c_signdate < (unix_timestamp(NOW()) + 8*3600) and c_id <> 'coex@miner.net' and  c_pay = $cpay and c_div = $cdiv  and c_category = 'tradebuy'
        GROUP BY MONTH(FROM_UNIXTIME(c_signdate+8*3600))
        ORDER BY c_signdate ASC;");
        $stmt1->execute();
        $json = [];
        while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)):
          extract($row1);

          $milliseconds = 1000 * strtotime($row1['signdate']);

          $json[] = [$milliseconds, $row1['sumcoin']];
        endwhile;

        $alldata = json_encode($json, JSON_NUMERIC_CHECK);

        $filename = strtolower($row['c_coin']).'-'.strtolower($row['m_pay']).'-c'.'.json';

        if(file_put_contents($filename, $alldata))
        {
          echo $filename.' file created';
          echo '<br/>';
        }
    
    endwhile;

  endwhile;

?>