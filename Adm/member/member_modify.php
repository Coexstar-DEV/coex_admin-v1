<?
session_start();
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

$LOG_LEVEL = 1;
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
if (isset($_REQUEST["m_country_name"])) {
	$m_country_name = sqlfilter($_REQUEST["m_country_name"]);
} else {
	$m_country_name = "";
}
if (isset($_REQUEST["m_id"])) {
	$m_id = sqlfilter($_REQUEST["m_id"]);
} else {
	$m_id = "";
}

$query_pdo = "SELECT * FROM $member WHERE m_id=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($m_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//pdo end
if (!$row) {
	error("QUERY_ERROR");
	exit;
}

err_log("===>" . parse_array($row));
$m_passwd = $row["m_passwd"];
$m_name = $row["m_name"];
$m_level = $row["m_level"];
$m_confirm = $row["m_confirm"];
$m_signdate = $row["m_signdate"];
$m_block = $row["m_block"];
$m_key = $row["m_key"];
$m_email = $row["m_email"];
$m_handphone = $row["m_handphone"];
$m_webinfo = $row["m_webinfo"];
$m_contury = trim($row["m_contury"]);
$m_conturyname = trim($row["m_conturyname"]);
$m_ip = $row["m_ip"];
$m_updatedate = $row["m_updatedate"];
$m_measge = $row["m_measge"];
$m_admmemo = $row["m_admmemo"];
$m_device = $row["m_device"];
$m_userno = $row["m_userno"];
$m_otpcheck = $row["m_otpcheck"];
$m_secretkey = $row["m_secretkey"];
$m_banknum = $row["m_banknum"];
$m_bankname = $row["m_bankname"];
$m_birtday = $row["m_birtday"];
$m_address = $row["m_address"];
$m_admin_no = $row["m_admin_no"];
$m_ethfile = $row["m_ethfile"];
$m_celofile = $row["m_celofile"];
$m_tokenfile = $row["m_tokenfile"];
$m_encrypt = $row["m_encrypt"];
$m_feetype = $row["m_feetype"];
$m_referral = $row["m_referral"];
$m_private_user = $row["m_private_user"];
$m_private_key = $row["m_private_key"];

$m_gender = $row['m_gender'];
$m_citizenship = $row['m_citizenship'];
$m_empstatus = $row['m_empstatus'];
$m_empsalary = $row['m_empsalary'];
$m_employername = $row['m_employername'];
$m_position = $row['m_position'];
$m_fundsource = $row['m_fundsource'];
$m_validid = $row['m_validid'];
$m_idnumber = $row['m_idnumber'];
$m_validimg = $row['m_validimg'];
$m_validimg1 = $row['m_validimg1'];
$m_regcheck = $row['m_regcheck'];
$m_verified = $row['m_verified'];
$m_riskprof = $row['m_riskprof'];
if ($m_admin_no == "0") $m_admin_no = "";

?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.js"></script>
<!-- Latest compiled and minified Locales -->
<script src="https://unpkg.com/bootstrap-table@1.18.2/dist/locale/bootstrap-table-zh-CN.min.js"></script>
<script language="javascript">
	function go_modify() {
		if (document.form.m_passwd.value != "") {
			if (document.form.m_passwd.value.length < 4) {
				alert('<?=M_INPUT_PWD?>');
				document.form.m_passwd.focus();
				return;
			}
			if (!document.form.m_passwd2.value) {
				alert('새 비밀번호 확인를 입력하세요!');
				document.form.m_passwd2.focus();
				return;
			}
			if (document.form.m_passwd.value != document.form.m_passwd2.value) {
				alert('새 비밀번호와 새 비밀번호확인이 일치하지 않습니다.');
				document.form.m_passwd2.focus();
				return;
			}
		}
		document.form.action = "member_modify_ok.php";
		document.form.submit();
	}

	function insert(){

	}

	function go_del() {
		if (confirm("정말 삭제하시겠습니까?")) {
			location.href = "member_del.php?m_userno=<?= $m_userno ?>";
		} else {
			alert('취소하셨습니다.');
		}

	}

	function go_list() {
		document.form.action = "member.php";
		document.form.submit();
	}

</script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<style>
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
    background-color: #ffc107 !important;
}

</style>

		<ul class="nav nav-tabs" id="myTab" role="tablist" >
		<li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Information</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="employment-tab" data-toggle="tab" href="#employment" role="tab" aria-controls="employment" aria-selected="false">Employment</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="wallet-tab" data-toggle="tab" href="#wallet" role="tab" aria-controls="wallet" aria-selected="false">Wallet</a>
		</li>
		</ul>
		<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" >
		<form name="form" method="post" enctype="multipart/form-data">
		<div class="row " >
		<div class="col-sm-4" style="text-align:left !important; padding: 40px 40px 60px 40px;">
		<img width="200" height="200" src="<?= $IMG_URL . $m_validimg ?>" style="padding-bottom: 2%">
		<br>
				<div class="form-group" style="padding-bottom:10px;padding-top:10px">
					<label class=""><b>Email</b></label><br>
					
				<?= $m_id ?>
				</div>
				<div class="form-group">
						<label class=""><b>Full Name</b></label><br>
						<input type="text" name="m_name" size="20" value="<?= $m_name ?>" class="adminbttn">
				</div>

				<div class="form-group">
					<label class=""><b>Phone</b></label><br>
					<input type="text" name="m_name" size="20" value="<?= $m_handphone ?>" class="adminbttn">
				</div>

				<div class="form-group">
						<label class="control-label"><b>Birthday</b> (yyyymmdd)</label><br>
						<input type="text" maxlength=50 name="m_birtday" value="<?= $m_birtday ?>"  class="adminbttn">
				</div>
				<div class="form-group">
						<label class="control-label"><b>Gender</b></label><br>
						<input type="radio" name="m_gender" value="Male" <? if ($m_gender == "Male") { ?> checked <? } ?>> Male
						<input type="radio" name="m_gender" value="Female" <? if ($m_gender == "Female") { ?> checked <? } ?>> Female
				</div>
		</div>
		<div class="col-sm-4" style="text-align:left !important; padding: 40px 40px 60px 40px;">
				<div class="form-group">
						<label class="control-label"><b>Country Code</b></label><br>
						<select name="m_contury" class="adminbttn">
									<option value="+82@@Korea" <? if ($m_contury == "+82" || $m_contury == "82") { ?>selected<? } ?>>Korea (+82)</option>
									<option value="+65@@Singapore" <? if ($m_contury == "+65" || $m_contury == "65") { ?>selected<? } ?>>Singapore (+65)</option>
									<option value="+86@@China" <? if ($m_contury == "+86" || $m_contury == "86") { ?>selected<? } ?>>China (+86)</option>
									<option value="+81@@Japan" <? if ($m_contury == "+81" || $m_contury == "81") { ?>selected<? } ?>>Japan (+81)</option>
									<option value="+84@@Vietnam" <? if ($m_contury == "+84" || $m_contury == "84") { ?>selected<? } ?>>Vietnam (+84)</option>
									<option value="+852@@Hongkong" <? if ($m_contury == "+852" || $m_contury == "852") { ?>selected<? } ?>>Hongkong (+852)</option>
									<option value="+62@@Indonesia" <? if ($m_contury == "+62" || $m_contury == "62") { ?>selected<? } ?>>Indonesia (+62)</option>
									<option value="+63@@Philippines" <? if ($m_contury == "+63" || $m_contury == "63") { ?>selected<? } ?>>Philippines (+63)</option>
									<option value="+91@@India" <? if ($m_contury == "+91" || $m_contury == "91") { ?>selected<? } ?>>India (+91)</option>
									<option value="+1@@Usa" <? if (($m_contury == "+1" || $m_contury == "1") && strtoupper($m_conturyname) == "USA") { ?>selected<? } ?>>USA (+1)</option>
									<option value="+1@@Canada" <? if (($m_contury == "+1" || $m_contury == "1") && $m_conturyname == "Canada") { ?>selected<? } ?>>Canada (+1)</option>
									<option value="+61@@Australia" <? if ($m_contury == "+61" || $m_contury == "61") { ?>selected<? } ?>>Australia (+61)</option>
									<option value="+43@@Austria" <? if ($m_contury == "+43" || $m_contury == "43") { ?>selected<? } ?>>Austria (+43)</option>
									<option value="+55@@Brazil" <? if ($m_contury == "+55" || $m_contury == "55") { ?>selected<? } ?>>Brazil (+55)</option>
									<option value="+855@@Cambodia" <? if ($m_contury == "+855" || $m_contury == "855") { ?>selected<? } ?>>Cambodia (+855)</option>
									<option value="+358@@Finland" <? if ($m_contury == "+358" || $m_contury == "358") { ?>selected<? } ?>>Finland (+358)</option>
									<option value="+33@@France" <? if ($m_contury == "+33" || $m_contury == "33") { ?>selected<? } ?>>France (+33)</option>
									<option value="+44@@Great Britain" <? if ($m_contury == "+44" || $m_contury == "44") { ?>selected<? } ?>>Great Britain (+44)</option>
									<option value="+30@@Greece" <? if ($m_contury == "+30" || $m_contury == "30") { ?>selected<? } ?>>Greece (+30)</option>
									<option value="+1671@@Guam" <? if ($m_contury == "+1671" || $m_contury == "1671") { ?>selected<? } ?>>Guam (+1671)</option>
									<option value="+972@@Israel" <? if ($m_contury == "+972" || $m_contury == "972") { ?>selected<? } ?>>Israel (+972)</option>
									<option value="+39@@Italy" <? if ($m_contury == "+39" || $m_contury == "39") { ?>selected<? } ?>>Italy (+39)</option>
									<option value="+965@@Kuwait" <? if ($m_contury == "+965" || $m_contury == "965") { ?>selected<? } ?>>Kuwait (+965)</option>
									<option value="+856@@Laos" <? if ($m_contury == "+856" || $m_contury == "856") { ?>selected<? } ?>>Laos (+856)</option>
									<option value="+853@@Macau" <? if ($m_contury == "+853" || $m_contury == "853") { ?>selected<? } ?>>Macau (+853)</option>
									<option value="+60@@Malaysia" <? if ($m_contury == "+60" || $m_contury == "60") { ?>selected<? } ?>>Malaysia (+60)</option>
									<option value="+31@@Netherlands" <? if ($m_contury == "+31" || $m_contury == "31") { ?>selected<? } ?>>Netherlands (+31)</option>
									<option value="+48@@Poland" <? if ($m_contury == "+48" || $m_contury == "48") { ?>selected<? } ?>>Poland (+48)</option>
									<option value="+351@@Portugal" <? if ($m_contury == "+351" || $m_contury == "351") { ?>selected<? } ?>>Portugal (+351)</option>
									<option value="+34@@Spain" <? if ($m_contury == "+34" || $m_contury == "34") { ?>selected<? } ?>>Spain (+34)</option>
									<option value="+94@@Sri Lanka" <? if ($m_contury == "+94" || $m_contury == "94") { ?>selected<? } ?>>Sri Lanka (+94)</option>
									<option value="+46@@Sweden" <? if ($m_contury == "+46" || $m_contury == "46") { ?>selected<? } ?>>Sweden (+46)</option>
									<option value="+41@@Switzerland" <? if ($m_contury == "+41" || $m_contury == "41") { ?>selected<? } ?>>Switzerland (+41)</option>
									<option value="+886@@Taiwan" <? if ($m_contury == "+886" || $m_contury == "886") { ?>selected<? } ?>>Taiwan (+886)</option>
									<option value="+66@@Thailand" <? if ($m_contury == "+66" || $m_contury == "66") { ?>selected<? } ?>>Thailand (+66)</option>
									<option value="+90@@Turkey" <? if ($m_contury == "+90" || $m_contury == "90") { ?>selected<? } ?>>Turkey (+90)</option>
								</select>
				</div>
				<div class="form-group">
						<label class="control-label"><b>Address</b></label><br>
						<textarea name="m_name" value="<?= $m_address ?>" class="adminbttn"><?= $m_address ?></textarea>
				</div>
				<div class="form-group">
						<label class="control-label"><b>Citizenship</b></label><br>
						<input type="text" maxlength=50 name="m_citizenship" value="<?= $m_citizenship ?>" class="adminbttn">
				</div>
				<div class="form-group">
							<label class="control-label"><b>Bank Account</b></label><br>
							<input type="text" maxlength=50 name="m_banknum" value="<?= $m_banknum ?>"  class="adminbttn">
				</div>
				<div class="form-group">
							<label class="control-label"><b>Bank Name</b></label><br>
							<input name="m_name" value="<?= $m_bankname ?>" type="text" class="adminbttn">
				</div>
		</div>
        <div class="col-sm-4" style=" padding: 40px 40px 60px 40px;">
					
					<i style="color:#28a745!important;float:right;font-size:18px;padding-left:5px;">Level <?= $m_level ?> </i>  <i style="color:#28a745!important;float:right;font-size:18px;padding-left:5px;"> </i>
					<? 
						if($m_verified == 0) {
					?>
					<i class="fas fa-minus-circle" style="color:#ff9614!important;float:right;font-size:20px"></i>
					<? 
						}else if($m_verified == 1 && $m_block == 0 ) {
					?>	
							<i class="fas fa-check-circle" aria-hidden="true" style="color:#28a745!important;float:right;font-size:20px"></i>
					<?
						}else if($m_block == 1) {
					?>
						<i class="fas fa-user-times" aria-hidden="true" style="color:#de1f1f!important;float:right;font-size:20px"></i>
					<?
						}
					?>					
		</div>
         </div>
		</form>
		</div>
		<div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
		<div class="row " >		
				<div class="col-sm-4" style="text-align:left !important; padding: 40px 40px 60px 40px;">
					<div class="form-group">
						<label class=""><b>Employment Status</b></label><br>
						<select name="m_empstatus" class="adminbttn" style="width:200px">
									<option value="" <? if ($m_empstatus == "") { ?>selected<? } ?>>--Select--</option>
									<option value="Employed" <? if ($m_empstatus == "Employed") { ?>selected<? } ?>>Employed</option>
									<option value="Self Employed" <? if ($m_empstatus == "Self Employed") { ?>selected<? } ?>>Self Employed</option>
									<option value="Unemployed" <? if ($m_empstatus == "Unemployed") { ?>selected<? } ?>>Unemployed</option>
									<option value="Retired" <? if ($m_empstatus == "Retired") { ?>selected<? } ?>>Retired</option>
									<option value="Student" <? if ($m_empstatus == "Student") { ?>selected<? } ?>>Student</option>
								</select>
					</div>
					<div class="form-group">
						<label class=""><b>Salary Range</b></label><br>
						<select name="m_empsalary" class="adminbttn" style="width:200px">
									<option value="" <? if ($m_empsalary == "") { ?>selected<? } ?>>--Select--</option>
									<option value="5000 - 10000" <? if ($m_empsalary == "5000 - 10000") { ?>selected<? } ?>>5000 - 10000</option>
									<option value="10001 - 50000" <? if ($m_empsalary == "10001 - 50000") { ?>selected<? } ?>>10001 - 50000</option>
									<option value="50001 - 100000" <? if ($m_empsalary == "50001 - 100000") { ?>selected<? } ?>>50001 - 100000</option>
									<option value="100001 - 500000" <? if ($m_empsalary == "100001 - 500000") { ?>selected<? } ?>>100001 - 500000</option>
									<option value="more than 500000" <? if ($m_empsalary == "more than 500000") { ?>selected<? } ?>>more than 500000</option>
					</select></div>
					<div class="form-group">
						<label class=""><b>Employer Name</b></label><br>
						<input type="text" maxlength=50 name="m_employername" value="<?= $m_employername ?>" class="adminbttn" style="width:200px">
					</div>
					<div class="form-group">
						<label class=""><b>Employee Position</b></label><br>
						<select name="m_position" class="adminbttn" style="width:200px">
									<option value="" <? if ($m_position == "") { ?>selected<? } ?>>--Select--</option>
									<option value="Staff Level" <? if ($m_position == "Staff Level") { ?>selected<? } ?>>Staff Level</option>
									<option value="Supervisor" <? if ($m_position == "Supervisor") { ?>selected<? } ?>>Supervisor</option>
									<option value="Manager" <? if ($m_position == "Manager") { ?>selected<? } ?>>Manager</option>
									<option value="Executive" <? if ($m_position == "Executive") { ?>selected<? } ?>>Executive</option>
									<option value="Businessman" <? if ($m_position == "Businessman") { ?>selected<? } ?>>Businessman</option>
									<option value="Freelance" <? if ($m_position == "Freelance") { ?>selected<? } ?>>Freelance</option>
									<option value="Not Applicable" <? if ($m_position == "Not Applicable") { ?>selected<? } ?>>Not Applicable</option>
						</select>
					</div>
					<div class="form-group">
						<label class=""><b>Source of Funds</b></label><br>
						<input type="text" maxlength=50 name="m_fundsource" value="<?= $m_fundsource ?>"  class="adminbttn" style="width:200px">
					</div>
					<div class="form-group">
						<label class=""><b>Valid ID</b></label><br>
					
						<select name="m_validid" class="adminbttn" style="width:200px">
									
									<option value="" <? if ($m_validid == "") { ?>selected<? } ?>>--Select--</option>
									<option disabled>---- GENERAL ID ----</option>
									<option value="Passport" <? if ($m_validid == "Passport") { ?>selected<? } ?>>Passport</option>
									<option value="National ID" <? if ($m_validid == "National ID") { ?>selected<? } ?>>National ID</option>	
									<option value="Drivers License" <? if ($m_validid == "Drivers License") { ?>selected<? } ?>>Driver's License</option>
									<option disabled>---- PHILIPPINE ID ----</option>
									<option value="Professional Regulation Commission PRC ID" <? if ($m_validid == "Professional Regulation Commission PRC ID") { ?>selected<? } ?>>Professional Regulation Commission (PRC) ID</option>
									<option value="National Bureau of Investigation NBI Clearance" <? if ($m_validid == "National Bureau of Investigation NBI Clearance") { ?>selected<? } ?>>National Bureau of Investigation (NBI) Clearance</option>
									<option value="Police Clearance" <? if ($m_validid == "Police Clearance") { ?>selected<? } ?>>Police Clearance</option>
									<option value="Postal ID" <? if ($m_validid == "Postal ID") { ?>selected<? } ?>>Postal ID</option>
									<option value="COMELEC Voters ID or Voters Certificate" <? if ($m_validid == "COMELEC Voters ID or Voters Certificate") { ?>selected<? } ?>>COMELEC Voter’s ID or Voter’s Certificate</option>
									<option value="Barangay Certificate of Residency" <? if ($m_validid == "Barangay Certificate of Residency") { ?>selected<? } ?>>Barangay Certificate of Residency</option>
									<option value="Government Service Insurance System GSIS e-Card" <? if ($m_validid == "Government Service Insurance System GSIS e-Card") { ?>selected<? } ?>>Government Service Insurance System (GSIS)e-Card</option>
									<option value="Social Security System SSS Card" <? if ($m_validid == "Social Security System SSS Card") { ?>selected<? } ?>>Social Security System (SSS) Card</option>
									<option value="Unified Multi-Purpose ID UMID" <? if ($m_validid == "Unified Multi-Purpose ID UMID") { ?>selected<? } ?>>Unified Multi-Purpose ID (UMID)</option>
									<option value="Senior Citizen Card" <? if ($m_validid == "Senior Citizen Card") { ?>selected<? } ?>>Senior Citizen Card</option>
									<option value="Overseas Workers Welfare Administration OWWA ID" <? if ($m_validid == "Overseas Workers Welfare Administration OWWA ID") { ?>selected<? } ?>>Overseas Workers Welfare Administration (OWWA) ID</option>
									<option value="OFW ID" <? if ($m_validid == "OFW ID") { ?>selected<? } ?>>OFW ID</option>
									<option value="Seamans Book" <? if ($m_validid == "Seamans Book") { ?>selected<? } ?>>Seaman’s Book</option>
									<option value="Alien Certification of Registration/Immigrant Certificate of Registration" <? if ($m_validid == "Alien Certification of Registration/Immigrant Certificate of Registration") { ?>selected<? } ?>>Alien Certification of Registration/Immigrant Certificate of Registration</option>
									<option value="Government Office and GOCC ID eg Armed forces of the Philippines AFP ID" <? if ($m_validid == "Government Office and GOCC ID eg Armed forces of the Philippines AFP ID") { ?>selected<? } ?>>Government Office and GOCC ID, e.g. Armed forces of the Philippines (AFP ID)</option>
									<option value="Home Development Mutual Fund HDMF ID" <? if ($m_validid == "Home Development Mutual Fund HDMF ID") { ?>selected<? } ?>>Home Development Mutual Fund (HDMF ID)</option>
									<option value="Certification from the National Council for the Welfare of Disabled Persons NCWDP" <? if ($m_validid == "Certification from the National Council for the Welfare of Disabled Persons NCWDP") { ?>selected<? } ?>>Certification from the National Council for the Welfare of Disabled Persons (NCWDP)</option>
									<option value="Department of Social Welfare and Development DSWD Certification of Indigency" <? if ($m_validid == "Department of Social Welfare and Development DSWD Certification of Indigency") { ?>selected<? } ?>>Department of Social Welfare and Development (DSWD) Certification of Indigency</option>
									<option value="Integrated Bar of the Philippines IBP ID" <? if ($m_validid == "Integrated Bar of the Philippines IBP ID") { ?>selected<? } ?>>Integrated Bar of the Philippines (IBP) ID</option>
									<option disabled>---- KOREAN ID ----</option>
									<option value="여권" <? if ($m_validid == "여권") { ?>selected<? } ?>>여권</option>
									<option value="주민등록증" <? if ($m_validid == "주민등록증") { ?>selected<? } ?>>주민등록증</option>
									<option value="공무원증" <? if ($m_validid == "공무원증") { ?>selected<? } ?>>공무원증</option>
									<option value="운전면허증" <? if ($m_validid == "운전면허증") { ?>selected<? } ?>>운전면허증</option>
									<option value="외국인등록증" <? if ($m_validid == "외국인등록증") { ?>selected<? } ?>>외국인등록증</option>
									<option disabled>---- CHINESE ID ----</option>
									<option value="护照" <? if ($m_validid == "护照") { ?>selected<? } ?>>护照</option>
									<option value="国家身份证" <? if ($m_validid == "国家身份证") { ?>selected<? } ?>>国家身份证</option>
									<option value="驾驶执照" <? if ($m_validid == "驾驶执照") { ?>selected<? } ?>>驾驶执照</option>	
									<option disabled>---- JAPANESE ID ----</option>
									<option value="パスポート" <? if ($m_validid == "パスポート") { ?>selected<? } ?>>パスポート</option>
									<option value="外国人登録証明書" <? if ($m_validid == "外国人登録証明書") { ?>selected<? } ?>>外国人登録証明書</option>
									<option value="運転免許" <? if ($m_validid == "運転免許") { ?>selected<? } ?>>運転免許</option>									
								</select>					</div>
				</div>
				<div class="col-sm-8" style="text-align:left !important; padding: 40px 40px 60px 40px;">
				<div class="form-group">
						<label class="control-label"><b>Front Image</b></label><br>
						<img width="500" height="500" src="<?= $IMG_URL . $m_validimg ?>">
				</div>
				<div class="form-group">
						<label class="control-label"><b>Back Image</b></label><br>
						<img width="500" height="500" src="<?= $IMG_URL . $m_validimg ?>">
					</div> 
				</div>
		</div>		
		</div>
		<div class="tab-pane fade" id="wallet" role="tabpanel" aria-labelledby="wallet-tab">
			<div class="row ">
				<div class="col-sm-3" style="text-align:left !important; padding: 40px 40px 60px 40px;">
				<div class="form-group">
						<label class=""><b>ETH</b></label><br>
						<input type="text" maxlength=50 name="m_ethfile" value="<?= $m_ethfile ?>" size="80" class="adminbttn" readonly>
				</div>
				<div class="form-group">
						<label class=""><b>TOKEN</b></label><br>
						<input type="text" maxlength=50 name="m_ethfile" value="<?= $m_ethfile ?>" size="80" class="adminbttn" readonly>
				</div>
				<div class="form-group">
						<label class=""><b>CELO</b></label><br>
						<input type="text" maxlength=50 name="m_ethfile" value="<?= $m_ethfile ?>" size="80" class="adminbttn" readonly>
				</div>
				</div>
			</div>
		</div>
		</div>	

		<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="deposit-tab" data-toggle="tab" href="#deposit" role="tab" aria-controls="deposit" aria-selected="true">Deposits</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="withdrawal-tab" data-toggle="tab" href="#withdrawal" role="tab" aria-controls="withdrawal" aria-selected="false">Withdrawals</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Trade History</a>
		</li>
		</ul>
		<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">
					<?
					
					$stmt = $this->conn->prepare("SELECT c_exchange,c_signdate FROM $table_point WHERE c_id= '$m_id' and c_category = 'reqorderrecv'");
					$stmt->bindparam(":m_id", $m_id);   
					$stmt->execute();
				  
					$listing_arr = array();
				  
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
					  $lid = $row['listing_id'];
					  $ctk = $row['coin_name'];
					  $file = $row['listing_file'];
					  $date = $row['listing_datesubmitted'];
					  $listing_arr[] = array("Listing ID" => $lid, "Coin or Token Name" => $ctk, "File" => $file, "Date" => $date);
					}
				  
					$json_string = json_encode($listing_arr, JSON_PRETTY_PRINT);
				  
					$file = '../json/listing.json';
					file_put_contents($file, $json_string);
					?>	

			   <table id="table" data-toggle="table" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar" data-click-to-select="true" data-pagination="true" data-search="true" data-show-export="true"  data-show-fullscreen="true" data-page-list="[10, 25, 50, 100, 200, All]" data-url="../json/listing.json">
                        <thead>
                          <tr>
                           <th data-field="Listing ID" data-sortable="true" >M_ID</th>
                            <th data-field="Coin or Token Name" data-sortable="true" >Name</th>
                            <th data-field="File" data-sortable="true" data-formatter="linkFormatter">Amount</th>
                            <th data-field="Date" data-sortable="true" >Date Deposited</th>
                          </tr>
              </thead>
      
      
    </table>
		</div>
		<div class="tab-pane fade" id="withdrawal" role="tabpanel" aria-labelledby="withdrawal-tab">
				<?
						$query_pdo3 = "SELECT t_ordermost,t_fees,t_signdate FROM $table_withdraw WHERE t_id= '$m_id'";
						$stmt = $pdo->prepare($query_pdo3);
						$stmt->execute();   
						$withdraw = $stmt->rowCount();  
						if($withdraw > 0) {
						while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)): 
								$t_ordermost = $row2['t_ordermost'];
								$t_fees = $row2['t_fees'];
								$t_signdate = $row2['t_signdate'];

							endwhile;
						}else{
							echo '<p>No transactions yet.</p>';
						}
					?>
				
		</div>
		<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="trade-tab">
		<?
						$query_pdo3 = "SELECT t_ordermost,t_fees,t_signdate FROM $table_withdraw WHERE t_id= '$m_id'";
						$stmt = $pdo->prepare($query_pdo3);
						$stmt->execute();   
						$withdraw = $stmt->rowCount();  
						if($withdraw > 0) {
						while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)): 
								$t_ordermost = $row2['t_ordermost'];
								$t_fees = $row2['t_fees'];
								$t_signdate = $row2['t_signdate'];

							endwhile;
						}else{
							echo '<p>No transactions yet.</p>';
						}
					?>		
		</div>
		</div>	



<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="center">
			<? if (check_manager_level2($adminlevel, ADMIN_LVL2)) { ?>
			<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">&nbsp;
			<? } ?>
			<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">&nbsp;
			<? if (check_manager_level2($adminlevel, ADMIN_LVL2)) { ?>
			<input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="javascript:go_del()">
			<? } ?>
		</td>
	</tr>
</table>
<br><br>

<BR><BR>

<script type="text/javascript">
	function getPrivateKey() {
		var privateUserSelected = document.querySelector('input[name="m_private_user"]:checked').value;
		var privateKeyEmpty = "";
		if(privateUserSelected == "1") {
			document.getElementById("m_private_key").value = document.getElementById("generate_key").value;
		}
		if(privateUserSelected == "0") {
			document.getElementById("m_private_key").value = privateKeyEmpty;
		}
	}
</script>

<? include "../inc/down_menu.php"; ?>

<script type="text/javascript">
        // Customer List Table
          var $table = $('#table')

        $(function() {
          $table.bootstrapTable('destroy').bootstrapTable({
            exportDataType: $(this).val(),
            exportTypes: ['csv', 'excel', 'pdf']
          })
        })

        var data = $.getJSON('../includes/json/listing.json');

        function linkFormatter(value, row) {
          return "<a class='back' href='../uploads/accomplished/"+row.File+"' target='_blank'>" + row.File + "</a>";
        }


        $(function() {
          $('#table').bootstrapTable({
            data: data,
            locale: 'ko-KR'
          });
        });

</script>
