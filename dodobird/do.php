
<?

#####################################################################

include "../Adm/common/dbconn.php";
include "../Adm/common/user_function.php";

$a_signdate = time();
/*
$a_ip = $_SERVER['REMOTE_ADDR'];
$a_ip_array = explode(",",$a_ip);
$a_ip=$a_ip_array[0];
*/
$a_ip=get_real_ip();
$cokey = $_GET["cokey"];
$chkkey =  hash('sha256',"coexstar_admin");

if($cokey==$chkkey){
	$query2="INSERT INTO admin_ip ";
	$query2=$query2."(";
	$query2=$query2."a_no,a_ip,a_signdate";
	$query2=$query2.")";
	$query2=$query2." VALUES ";
	$query2=$query2."(";
	$query2=$query2."'','$a_ip','$a_signdate'";
	$query2=$query2.")";
	$result2 = mysqli_query($dbconn,$query2);
	if($result2){
		echo "su";
	}else{
		echo "fal";
	}
}else{
	echo "50";
}
//56f6207a99c03a03cca83425a958df69b8437531061ecf573bf6955c25159b47
?>
