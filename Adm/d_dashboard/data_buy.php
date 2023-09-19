<?php
session_start();
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

$payname = $_SESSION['m_pay'];
$cpay = $_SESSION['c_div'];
$c =  $_SESSION['new'];
if(($_SESSION['new'])) {
    if($payname == 'PHP'){
     
          if(!isset( $_SESSION['coinname']) && !isset($_SESSION['coinno'])){
            $c =  $_SESSION['new'];
            $coinname = 'BTC';
            $cdiv = 1;  
            $cpay = $_SESSION['c_div'];
          }else {
            $c =  $_SESSION['new'];
            $coinname = $_SESSION['coinname'];
            $cdiv = $_SESSION['coinno'];
            $cpay = $_SESSION['c_div'];
            
          }
        
      }else if ($payname == 'BTC'){

        if(!isset($_SESSION['coinname']) && !isset($_SESSION['coinno'])){
            $c =  $_SESSION['new'];
          $coinname = 'ETH';
          $cdiv = 2;
          $cpay = $_SESSION['c_div'];
        }else{
            $c =  $_SESSION['new'];
          $coinname = $_SESSION['coinname'];
          $cdiv = $_SESSION['coinno'];
          $cpay = $_SESSION['c_div'];

        }
  }else if ($payname == 'KRWC'){
 
        if(!isset( $_SESSION['coinname']) && !isset($_SESSION['coinno'])){
            $c =  $_SESSION['new'];
          $coinname = 'BTC';
          $cdiv = 1;
          $cpay = $_SESSION['c_div'];
        }else{
            $c = $_SESSION['new'];
          $coinname = $_SESSION['coinname'];
          $cdiv = $_SESSION['coinno'];
          $cpay = $_SESSION['c_div'];
        }
  }else if ($payname == 'USDT'){
    
        if(!isset( $_SESSION['coinname']) && !isset($_SESSION['coinno'])){
            $c = $_SESSION['new'];
          $coinname = 'BTC';
          $cdiv = 1;
          $cpay = 4;
        }else{  
            $b = $_SESSION['new'];
          $coinname = $_SESSION['coinname'];
          $cdiv = $_SESSION['coinno'];
          $cpay = 4;
        }
  }
  $aa = $_SESSION['new'];
  if($aa == 'Daily'){
    $stmt=$pdo->prepare("SELECT '$payname' as payname, DATE(FROM_UNIXTIME(c_signdate+8*3600)) AS signdate, ROUND(sum(c_exchange+0), 6) as sumcoin, '$coinname' as coinname FROM  coexstar.coin_point 
    WHERE c_signdate > (1570406400 + 8*3600) and c_signdate < (unix_timestamp(NOW()) + 8*3600) and c_id <> 'coex@miner.net' and  c_pay = $cpay and c_div = $cdiv  and c_category = 'tradebuy'
    GROUP BY DATE(FROM_UNIXTIME(c_signdate+8*3600))
    ORDER BY signdate ASC;");
     $stmt->execute();
     $json = [];
  
     while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      extract($row);
  
  
        $json[] = [$signdate, $sumcoin]; 
             
  }
  echo json_encode($json, JSON_NUMERIC_CHECK);
  }else if($aa == 'Monthly'){
    
    $stmt=$pdo->prepare("SELECT '$payname' as payname,  CONCAT(monthname(FROM_UNIXTIME(c_signdate+8*3600)), ' ', YEAR(FROM_UNIXTIME(c_signdate+8*3600))) AS signdate, ROUND(sum(c_exchange+0), 6) as sumcoin, '$coinname' as coinname FROM  coexstar.coin_point 
    WHERE c_signdate > (1570406400 + 8*3600) and c_signdate < (unix_timestamp(NOW()) + 8*3600) and c_id <> 'coex@miner.net' and  c_pay = $cpay and c_div = $cdiv  and c_category = 'tradebuy'
    GROUP BY MONTH(FROM_UNIXTIME(c_signdate+8*3600))
    ORDER BY c_signdate ASC;");
   $stmt->execute();
   $json = [];

   
  while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);


      $json[] = [$signdate, $sumcoin]; 
           
}
echo json_encode($json, JSON_NUMERIC_CHECK);
  }
 

  

}


?>