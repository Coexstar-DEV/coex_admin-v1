<?php

session_start();

include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";

include_once "../inc/left_menu_watchlist.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}
if (isset($_REQUEST["m_countryname"])) {
	$m_countryname = sqlfilter($_REQUEST["m_countryname"]);
} else {
	$m_countryname = "";
}

$encoded_key = urlencode($key);

$query_pdo = "SELECT m_id, m_adminid, m_module, m_type, m_modified, m_signdate FROM $admlogs ";

if ($key != "") {
	$query_pdo .= " where $keyfield LIKE '%$key%' ";

	if($m_country_name != "") {
		$query_pdo .= " and m_countryname = '".$m_countryname."' ";
	}
}
else if($m_country_name != "") {
	$query_pdo .= " where m_countryname = '".$m_countryname."' ";
}
$query_pdo .= "ORDER BY m_id DESC";

$total_record_pdo = pdo_excute_count("select count:", $query_pdo, NULL);

if (isset($_REQUEST["page"])) {
	$page = $_REQUEST["page"];
} else {
	$page = 1;
}
$num_per_page = 10;
$page_per_block = 10;

if (!$total_record_pdo) {
	$first = 1;
	$last = 0;
} else {
	$first = $num_per_page * ($page - 1);
	$last = $num_per_page * $page;

	$IsNext = $total_record_pdo - $last;

	if ($IsNext > 0) {
		$last -= 1;
	} else {
		$last = $total_record_pdo - 1;
	}
}

$total_page = ceil($total_record_pdo / $num_per_page);
$article_num = $total_record_pdo - $num_per_page * ($page - 1);
$mode = "keyfield=$keyfield&key=$encoded_key&m_countryname=$m_countryname";

$query_pdo1 = "SELECT * FROM $admin_member ";
?>

<script language="javascript">
	function go_excel() {
		document.dform.submit();
	}

	function go_search() {
		document.form.action = "member.php?dis=<?= $dis ?>";
		document.form.submit();
	}

    //You may use vanilla JS, I just chose jquery

$('.openmodale').click(function (e) {
         e.preventDefault();
         $('.modale').addClass('opened');
    });
$('.closemodale').click(function (e) {
         e.preventDefault();
         $('.modale').removeClass('opened');
    });
</script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
<table align="center" width="1400" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table align="center" width="1000" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center">Watchlist Management</td>
				</tr>
                </table>
        </td>
  </tr>

  

</table> 
<div class="col-sm-6 col-md-offset-3" style="padding-top:2%">
<div class="panel panel-warning ">
            <div class="panel-heading">Watchlist Report</div>
            <div class="panel-body"> 
                    <form id="generate-gg-form" role="form" target="_blank" action="member_watchpdf.php?m_name=<?php echo $m_name; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Member Name</label>
                                        <input type="text" name="m_name" class="form-control" id="m_name" value="<?php if(isset($m_name)) echo $m_name; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <hr />

                                <div class="row generate-buttons" style="text-align:center">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-default" id="btn-generate">Generate All Transactions</button>

                                        <button type="reset" class="btn btn-default" id="btn-clear">Clear</button>
                                    </div>
                                </div>

                    </form>
             </div>
        </div>

</div>

<br><br>
<?php include "../inc/down_menu.php"; ?>
