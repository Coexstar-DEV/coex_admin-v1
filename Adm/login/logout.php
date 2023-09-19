<?
/*
SetCookie("admin_id","",0,"/");
SetCookie("idok","",0,"/");
SetCookie("otpcode","",0,"/");
*/
session_unset();
session_destroy();
echo "<meta http-equiv='Refresh' content='0; URL=../login/login.php'>";
?>
