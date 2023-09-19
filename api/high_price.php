<?
   include "../include/top_session.php";
   include "../BitzetaDmin2018/common/dbconn.php";
   include "../BitzetaDmin2018/common/user_function.php";
	$c_no = $_REQUEST["c_no"];
	$queryprice = "select b_orderprice from $table_orderbuy where b_state='wait' and b_delete ='0' and b_div='2' order by b_orderprice+0 desc limit 1 ";
	$resultprice = mysql_query($queryprice,$dbconn);
	$rowprice = mysql_fetch_row($resultprice);
	$high_prcie = $rowprice['0'];					
	$result = array( "high_prcie"=>$high_prcie);
	echo json_encode($result);
?>