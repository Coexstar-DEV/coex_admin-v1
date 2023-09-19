<meta charset="utf-8">
<?
include "../common/user_function.php";
include "../common/dbconn.php";


//setlocale(LC_CTYPE, 'ko_KR.eucKR'); //CSV데이타 추출시 한글깨짐방지

 
$csv_file_name="m_bankmoney.csv";
IF($csv_file_name){

//폴더내에 동일한 파일이 있는지 검사하고 있으면 삭제
if(file_exists("./data/$csv_file_name") ){
unlink("./data/$csv_file_name");
}
if(!$csv_file) {

}
if( strlen($csv_file_size) < 7 ) {
$filesize = sprintf("%0.2f KB", $csv_file_size/1000);
}else{
$filesize = sprintf("%0.2f MB", $csv_file_size/1000000);
}
if(!copy($csv_file, "./data/$csv_file_name")){

}
if(!unlink($csv_file)){

}
}

$csvLoad = file("./data/$csv_file_name");
$csvArray = split("\n",implode($csvLoad));

$c_signdate=time();
$k=0;
$coin_type="5";

for($i=1;$i<count($csvArray);$i++){
//각 행을 콤마를 기준으로 각 필드에 나누고 DB입력시 에러가 없게 하기위해서 addslashes함수를 이용해 \를 붙인다

$field=split(",",$csvArray[$k]);
$field= str_replace( "\"","", $field); 
//나누어진 각 필드에 앞뒤에 공백을 뺸뒤 ''따옴표를 붙이고 ,콤마로 나눠서 한줄로 만든다.
//$value = trim(implode("','",$field));
// php쿼리문을 이용해서 입력한다.
$c_school=$field[0];

$c_name=$field[1];
$m_userno=$field[2];
$b_id=$field[3];
$m_krwtotal2=$field[4];
$m_krwuse2=$field[5];
$m_cointotal=$field[6];
$m_coinuse=$field[7];


$b_id2=$b_id."*";




####구매자 원화#############
$query_krw = "SELECT m_krwtotal,m_krwuse,m_no FROM $m_bankmoney WHERE m_id='$b_id' order by m_no desc,m_signdate desc ";
$result_krw = mysql_query($query_krw,$dbconn);
$row_krw = mysql_fetch_row($result_krw);
$m_krwtotal = $row_krw[0]; $m_krwuse = $row_krw[1]; 
$m_no = $row_krw[2];
################################################
if($m_no== ""){
	$m_krwtotal  = $m_krwtotal2;
	$m_krwuse  = $m_krwuse2;
}

### 계좌잔고추적(구매자)입력 ##########################################
$m_signdate = time();
$m_krwuse = floor($m_krwuse*100) / 100;
$m_krwtotal = floor($m_krwtotal*100) / 100;
$m_cointotal = floor($m_cointotal*100000000) / 100000000;
$m_coinuse = floor($m_coinuse*100000000) / 100000000;
$m_restkrw = $m_krwtotal - $m_krwuse;
$m_restcoin = $m_cointotal - $m_coinuse;
$m_restkrw = floor($m_restkrw*100) / 100;
$m_restcoin = floor($m_restcoin*100000000) / 100000000;
// 리스트 출력화면으로 이동한다

$query2="INSERT INTO $m_bankmoney ";
$query2=$query2."(";
$query2=$query2."m_no,m_div,m_userno,m_id,m_krwtotal,m_krwuse,m_cointotal,m_coinuse,m_restkrw,m_restcoin,m_signdate";
$query2=$query2.")";
$query2=$query2." VALUES ";
$query2=$query2."(";
$query2=$query2."'','$coin_type','$m_userno','$b_id','$m_krwtotal',$m_krwuse,'$m_cointotal','$m_coinuse','$m_restkrw','$m_restcoin','$m_signdate'";
$query2=$query2.")";
$result2 = mysql_query($query2,$dbconn);




$k++;
}
//입력이 된후 업로드된 파일을 삭제한다
exit;



?>