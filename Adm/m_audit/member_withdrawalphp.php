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
<table align="center" width="1400" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table align="center" width="1000" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center">PHP Withdrawal Audit Trail</td>
				</tr>
                </table>
        </td>
  </tr>
</table> 
<div class="col-sm-6 col-md-offset-3" style="padding-top:2%">
<div class="panel panel-warning ">
            <div class="panel-heading">Withdrawal Report</div>
            <div class="panel-body"> 
            <form id="generate-gg-form" role="form" target="_blank" action="member_withphp.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&c_coin=<?php echo 'PHP'; ?>&m_id=<?php echo $m_id; ?>&emp_status=<?php echo $emp_status; ?>&emp_range=<?php echo $emp_range; ?>&m_age=<?php echo $m_age; ?>">

<div class="row">

<div class="col-sm-6">
    <div class="form-group">
        <label class="control-label">From (required)</label>
        <input type="date" class="form-control" name="from" id="from" value="<?php if(isset($from)) echo $from; ?>" required>
    </div>
</div>


<div class="col-sm-6">
    <div class="form-group">
        <label class="control-label">To (required)</label>
        <input type="date" class="form-control" name="to" id="to" value="<?php if(isset($to)) echo $to; ?>" required>
    </div>
</div>

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label">COIN</label>
            <input type="text" name="g" class="form-control" id="g" value="PHP" disabled />
            <input type="hidden" name="c_coin" class="form-control" id="c_coin" value="PHP"  />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label">User ID</label>
            <input type="text" name="m_id" class="form-control" id="m_id" value="<?php if(isset($m_id)) echo $m_id; ?>"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4" id="emp_status">
        <div class="form-group">
            <label class="control-label">Employment Status</label>
            <select class="form-control" name="emp_status" id="emp_status">
            <option value="" <?php if(isset($emp_status) && $emp_status == '') { ?>selected <?php } ?>>--Select--</option>
                <option value="Employed" <?php if(isset($emp_status) && $emp_status == 'Employed') { ?>selected <?php } ?>>Employed</option>
                <option value="Self Employed" <?php if(isset($emp_status) && $emp_status == 'Self Employed') { ?>selected <?php } ?>>Self Employed</option>
                <option value="Unemployed" <?php if(isset($emp_status) && $emp_status == 'Unemployed') { ?>selected <?php } ?>>Unemployed</option>
                <option value="Retired" <?php if(isset($emp_status) && $emp_status == 'Retired') { ?>selected <?php } ?>>Retired</option>
                <option value="Student" <?php if(isset($emp_status) && $emp_status == 'Student') { ?>selected <?php } ?>>Student</option>
                <option value="Others" <?php if(isset($emp_status) && $emp_status == 'Others') { ?>selected <?php } ?>>Others</option>
                
            </select>
        </div>
    </div>

    <div class="col-sm-4" id="emp_range">
        <div class="form-group">
            <label class="control-label">Salary Range</label>
            <select class="form-control" name="emp_range">
            <option value="" <?php if(isset($emp_range) && $emp_range == '') { ?>selected <?php } ?>>--Select--</option>
                <option value="5000 - 10000" <?php if(isset($emp_range) && $emp_range == '5000 - 10000') { ?>selected <?php } ?>>5000-10000</option>
                <option value="10001 - 50000" <?php if(isset($emp_range) && $emp_range == '10001 - 50000') { ?>selected <?php } ?>>10001-50000</option>
                <option value="50001 - 100000" <?php if(isset($emp_range) && $emp_range == '50001 - 100000') { ?>selected <?php } ?>>50001-100000</option>
                <option value="100001 - 500000" <?php if(isset($emp_range) && $emp_range == '100001 - 500000') { ?>selected <?php } ?>>100001-500000</option>
                <option value="more than 500000" <?php if(isset($emp_range) && $emp_range == 'more than 500000') { ?>selected <?php } ?>>more than 500000</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4" id="m_age">
        <div class="form-group">
            <label class="control-label">Age</label>
            <select class="form-control" name="m_age">
            <option value="" <?php if(isset($m_age) && $m_age == '') { ?>selected <?php } ?>>--Select--</option>
                <option value="18-25" <?php if(isset($m_age) && $m_age == '18-25') { ?>selected <?php } ?>>18-25</option>
                <option value="26-35" <?php if(isset($m_age) && $m_age == '26-35') { ?>selected <?php } ?>>26-35</option>
                <option value="36-45" <?php if(isset($m_age) && $m_age == '36-45') { ?>selected <?php } ?>>36-45</option>
                <option value="46-55" <?php if(isset($m_age) && $m_age == '46-55') { ?>selected <?php } ?>>46-55</option>
                <option value="56-up" <?php if(isset($m_age) && $m_age == '56-up') { ?>selected <?php } ?>>56-up</option>
            </select>
        </div>
    </div>



</div>
<hr />

<div class="row generate-buttons" style="text-align:center">
    <div class="col-sm-12">
        <button type="submit" class="btn btn-default" style="color: #F9A602"  id="btn-generate">Generate</button>
        <button type="reset" class="btn btn-default" id="btn-clear">Clear</button>
    </div>
</div>

</form>

             </div>
             <br>
<br>
<br>
        </div>

</div>






<br><br>
<?php include "../inc/down_menu.php"; ?>
<script>
    $(document).ready(function(){
        $("#m_id").change(function(){
            if ($('#m_id').val() != "") {   
                $("#emp_status").hide("fast");
                $("#emp_range").hide("fast");
                $("#m_age").hide("fast");
            } 
            else {
                $("#emp_status").show("fast");
                $("#emp_range").show("fast");
                $("#m_age").show("fast");
            }
        });
    });


</script>