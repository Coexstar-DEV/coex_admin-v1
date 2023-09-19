<?php

session_start();

include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";

include_once "../inc/left_menu_audit.php";

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
<table align="center" width="1000" border="0" cellspacing="0" cellpadding="0" class="left_margin30" height="100%">

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table align="center" width="1000" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center">Employee Audit Trail</td>
				</tr>
                </table>
        </td>
  </tr>
</table> 
<div class="col-sm-6 col-md-offset-3" style="padding-top:2%">
<div class="panel panel-warning ">
            <div class="panel-heading">Activity Report</div>
            <div class="panel-body"> 
			<form id="generate-report-form" role="form" target="_blank" action="./member_emppdf.php/?from=<?php echo $from; ?>&to=<?php echo $to; ?>&m_adminid=<?php echo $m_adminid; ?>">

<div class="row">

<div class="col-sm-6">
	<div class="form-group">
	<label class="control-label">From &nbsp; </label><font style="color:#FF0000" ><b>*</b></font>
	<input type="date" class="form-control"   name="from" id="from" value="" required>
	</div>
</div>

<div class="col-sm-6">
	<div class="form-group">
	<label class="control-label">To &nbsp;</label><font style="color:#FF0000" ><b>*</b></font>
	<input type="date" class="form-control" name="to" id="to" value="" required>
	</div>
</div>

</div>

<div class="row">

<div class="col-sm-12">
	<div class="form-group">
	<label class="control-label">User ID &nbsp;</label><font style="color:#FF0000" ><b>*</b></font>

		<input list="browsers" name="m_adminid" value="<?= $m_adminid; ?>" id="m_adminid" class="form-control" required >
		<datalist id="browsers">
		<?php
							$stmt = pdo_excute("selectw", $query_pdo1, NULL);
							while ($row1 = $stmt->fetch()) {
		
								$m_adminid = $row1[1];

		?>
					<option name="m_adminid" value="<?= $m_adminid; ?>"><?= $m_adminid; ?></option>
		<?php } ?>
		</datalist>
	</div>
</div>


</div>
<hr />

<div class="row generate-buttons" style="text-align:center">
<div class="col-sm-12">
	<button type="submit" class="btn btn-default" style="color: #F9A602" id="btn-generate">Generate</button>
	<button type="reset" class="btn btn-default" id="btn-clear">Clear</button>

</div>
</div>

</form>

             </div>
        </div>

</div>


<br><br>
<?php include "../inc/down_menu.php"; ?>