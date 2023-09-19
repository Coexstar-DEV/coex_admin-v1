<meta charset="utf-8">

<?php // CASTLE - KISA Web Attack Defense Tool
define("__CASTLE_PHP_VERSION_BASE_DIR__", "../castle-php");
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");





?>


<?php
session_start();

if($_SESSION[userid] !=""){
	$userid = htmlspecialchars($_SESSION[userid]);
}

include "../api/core_api.php";

?>

