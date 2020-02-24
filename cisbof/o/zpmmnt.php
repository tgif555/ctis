<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');

$in_zpm_tmpsubmit = mssql_escape($_POST["in_zpm_tmpsubmit"]);
$in_zpt_sptm_nbr = mssql_escape($_POST["in_zpt_sptm_nbr"]);
$in_zpt_province = mssql_escape($_POST["in_zpt_province"]);
$in_zpt_sptm_customer = mssql_escape($_POST["in_zpt_sptm_customer"]);

$in_zpm_group_type = mssql_escape($_POST["in_zpm_group_type"]);

If ($in_zpm_tmpsubmit == "") {
	$in_zpm_tmpsubmit = $_COOKIE['in_zpm_tmpsubmit'];
	$in_zpt_sptm_nbr = $_COOKIE['in_zpt_sptm_nbr'];	
	$in_zpt_province = $_COOKIE['in_zpt_province'];	
	$in_zpt_sptm_customer = $_COOKIE['in_zpt_sptm_customer'];	
	$in_zpm_group_type = $_COOKIE['in_zpm_group_type'];
}
else {		
	setcookie("in_zpm_tmpsubmit","",0);
	setcookie("in_zpt_sptm_nbr","",0);
	setcookie("in_zpt_province","",0);
	setcookie("in_zpt_sptm_customer","",0);
	setcookie("in_zpm_group_type","",0);
}
if ($in_zpt_sptm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_nbr like '%$in_zpt_sptm_nbr%'";
}
setcookie("in_zpt_sptm_nbr", $in_zpt_sptm_nbr,0);
//
if ($in_zpt_province != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (sptm_customer_province like '%$in_zpt_province%' OR sptm_customer_amphur like '%$in_zpt_province%')";
}
setcookie("in_zpt_sptm_customer", $in_zpt_sptm_customer,0);
//
if ($in_zpt_sptm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_zpt_sptm_customer%' OR sptm_customer_dummy like '%$in_zpt_sptm_customer%')";
}
setcookie("in_zpt_sptm_customer", $in_zpt_sptm_customer,0);

//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_zpm_first_into == "1") { 
	$in_zpm_group_type = "A"; 
}
setcookie("spt_zpm_first_into", "0",0, "/");

setcookie("in_zpm_group_type", $in_zpm_group_type,0);

$can_zpm = false;
if (inlist($user_role,"CS")) {
	$can_zpm = true;
}

//
if ($criteria != "") { $criteria = " AND " . $criteria; }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
    <title><?php echo TITLE; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="../_images/smartxpense.ico" rel="shortcut icon" />
	<link href="../_libs/css/_webstyle.css" type="text/css" rel="stylesheet">

    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../_libs/datepicker/jquery-ui.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/datepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/css/sptm.css" rel="stylesheet">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script src="../_libs/js/bootbox.min.js"></script>		
	
	<script language="javascript">
		function chgzpmgroup(zpm_group_type) {
			document.frm_zpm.submit();
		}
		function printzone(dlvm_group,zpm_group_type) {
			var dlvm_nbr = "";
			var dlvm_nbr_name = "dlvm_nbr_"+dlvm_group+'_';
			//$('input[name^='+dlvm_nbr_name+']').each(function() {
			$('input[id*='+dlvm_nbr_name+']').each(function() {
				if (dlvm_nbr != "") { dlvm_nbr = dlvm_nbr + ","; }
				dlvm_nbr = dlvm_nbr + this.value;
			});
			document.frm_zpmform01.dlvm_nbr_list.value = dlvm_nbr;
			if (zpm_group_type == "A") { document.frm_zpmform01.action = "zpmform_a.php"; }
			if (zpm_group_type == "B") { document.frm_zpmform01.action = "zpmform_b.php"; }
			if (zpm_group_type == "C") { document.frm_zpmform01.action = "zpmform_c.php"; }
			if (zpm_group_type == "D") { document.frm_zpmform01.action = "zpmform_a.php"; }
			if (zpm_group_type == "E") { document.frm_zpmform01.action = "zpmform_a.php"; }
			if (zpm_group_type == "F") { document.frm_zpmform01.action = "zpmform_a.php"; }
			if (zpm_group_type == "G") { document.frm_zpmform01.action = "zpmform_g.php"; }
			
			document.frm_zpmform01.submit();
			setTimeout(function(){ window.location.replace("zpmmnt.php"); }, 1000);
			
		}
		function select_zonegroup(v) {
			$("input[id*='dlvm_nbr_"+v+"']").each(function() {
				if($(this).prop("checked") == true) {
					$(this).prop('checked',false);
					
				}
				else if($(this).prop("checked") == false) {
					$(this).prop('checked',true);
				}
			});
		}
		function printzonebyselected() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var dlvm_nbr_list = "";
			var dlvm_cnt = 0;
			$('input[id*=dlvm_nbr_zonegroup_]').each(function() {
				if (this.checked) {
					if (dlvm_nbr_list != "") { dlvm_nbr_list = dlvm_nbr_list + ","; }
					dlvm_nbr_list = dlvm_nbr_list + this.value;
					dlvm_cnt++;
				}
			});
			if (dlvm_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกรายการที่ต้องการก่อนค่ะ";
			}
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_zpmform01.dlvm_nbr_list.value = dlvm_nbr_list;
				document.frm_zpmform01.action = "zpmform_g.php";
				document.frm_zpmform01.submit();
				setTimeout(function(){ window.location.replace("zpmmnt.php"); }, 1000);
			}
		}
		function loadresult() {
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
		}		
</script>	
</head>
<body>		
	<div id="result"></div>
	<div>			
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>				
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0	width="100%" cellpadding="1" cellspacing="0">				
						<tr style="height:30px" bgcolor="lightgray">
							<td>
								<img src='../_images/delivery-icon.png' width=32><span style='font-size:11pt'><b>@พิมพ์ใบจัดโซน: </b></span>
								<?php if ($can_zpm) {?>
									<button type="button" class="btn btn-mini" onclick="printzonebyselected()" style='background:green;color:white;font-size:8pt'>>> พิมพ์ใบจัดโซนตามรายที่เลือก</button>
								<?php }?>
							</td>
							<td align=right>
								<table border="0" width=70% cellpadding="0" cellspacing="0" bgcolor="#cccccc">
									<form name="frm_zpm" method="post" autocomplete=OFF>
									<input type="hidden" name="in_zpm_tmpsubmit" value="search">
									<tr>
										<td valign=top style="width:110px;margin:auto"><b>หมายเลขใบเบิก:</b>
											<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_zpt_sptm_nbr" value="<?php echo $in_zpt_sptm_nbr?>">
										</td>
										<td valign=top style="width:110px;"><b>จังหวัด/อำเภอ:</b>
											<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_zpt_province" value="<?php echo $in_zpt_province?>">	
										</td>
										<td valign=top style="width:150px;"><b>ชื่อลูกค้า<font color=red><b>*</b></font>:
											<input name="in_zpt_sptm_customer" value="<?php echo $in_zpt_sptm_customer?>" class="inputtext_s" style='color:blue;width:100px'>
											<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
										</td>
										<td align=right><b>การจัดกลุ่มข้อมูล:</b><br>
											<select name="in_zpm_group_type" style="width:220px;margin:auto" onchange="chgzpmgroup(this.value)">
												<option value="A" <?php if ($in_zpm_group_type=='A') { echo 'selected';} ?>>(A)-ตาม ลูกค้า</option>
												<option value="B" <?php if ($in_zpm_group_type=='B') { echo 'selected';} ?>>(B)-ตาม วิธีการจัดส่ง</option>
												<option value="C" <?php if ($in_zpm_group_type=='C') { echo 'selected';} ?>>(C)-ตาม วิธีการจัดส่ง+ผู้ขอเบิก</option>
												<option value="D" <?php if ($in_zpm_group_type=='D') { echo 'selected';} ?>>(D)-ตาม ลูกค้า+จังหวัด</option>
												<option value="E" <?php if ($in_zpm_group_type=='E') { echo 'selected';} ?>>(E)-ตาม ลูกค้า+วิธีการจัดส่ง</option>
												<option value="F" <?php if ($in_zpm_group_type=='F') { echo 'selected';} ?>>(F)-ตาม ลูกค้า+หมายเหตุการจัดส่ง</option>
												<option value="G" <?php if ($in_zpm_group_type=='G') { echo 'selected';} ?>>(G)-ตาม จังหวัด</option>
											</select>
										</td>
									</tr>				
									</form>
								</table>
							</td>
						</tr>				
						<tr>
							<td width=100% colspan=2>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>
										<td align=center valign=top bgcolor="white">
											<fieldset style="background-color:white;border-radius:4px;width:98%">
												<table class="table table-bordered table-condensed" width="98%" border="0" cellspacing="1" cellpadding="4">
													<thead>
													<tr valign="top" style="background:#FFC300 " height="25" align="center">
														<th style="width:30px;text-align:center">No</th>
														<th style="width:100px;">Package No</th>
														<th style="width:200px;">ชื่อลูกค้า</th>
														<th style="width:150px;text-align:center">อำเภอ/จังหวัด</th>
														<th style="width:150px;text-align:center">วิธีการจัดส่ง </th>
														<th style="width:150px;text-align:center">ชื่อผู้ขอเบิก </th>
														<th style="width:150px;text-align:center">ชื่อผู้ขอรับ </th>
														<th style="width:70px;text-align:center">เบอร์โทรผู้รับ </th>
														<th style="width:70px;text-align:center">น้ำหนัก (KG)</th>
														<th style="text-align:center">หมายเหตุการจัดส่ง</th>
													</tr>
													</thead>   
													<tbody>
													<?php
													$n = 0;
													//$wpd_firstof_array = array();
													//$wpd_lastof_array =  array();
													
													$dlvm10_dlvm_nbr_array = array();
													$dlvm10_customer_name_array = array();
													$dlvm10_customer_amphur_array = array();
													$dlvm10_customer_province_array = array();
													$dlvm10_packing_weight_array = array();
													$dlvm10_receiver_name_array = array();
													$dlvm10_receiver_tel_array = array();
													$dlvm10_delivery_mth_name_array = array();
													$dlvm10_delivery_mth_desc_array = array();
													$dlvm10_req_by_array = array();
													$dlvm10_req_by_name_array = array();
													$dlvm10_group_array = array();
													$dlvm10_group2_array = array();
													$dlvm10_packing_location_array = array();
													
													$dlvm10_npd_brand_name_setno_array = array();
													
													$group_type = $in_zpm_group_type;
													
													if ($group_type == "A") {
														$group_by = "sptm_customer_number,sptm_customer_dummy";
													}
													if ($group_type == "B") {
														$group_by = "sptm_delivery_mth,sptm_req_by";
													}
													if ($group_type == "C") {
														$group_by = "sptm_delivery_mth,sptm_req_by";
													}
													if ($group_type == "D") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_customer_province";
													}
													if ($group_type == "E") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_delivery_mth";
													}
													if ($group_type == "F") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,cast(sptm_delivery_mth_desc as nvarchar(255))";
													}
													if ($group_type == "G") {
														$group_by = "sptm_customer_province";
													}
													$sql_dlvm10 = "SELECT * FROM dlvm_mstr" .
														" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
														" INNER JOIN customer ON customer_number = sptm_customer_number" .
														" WHERE dlvm_dlvs_step_code = '10' and dlvm_zone_printed = '0' and sptm_customer_number <> 'NPD_NOCUST' $criteria" .
														" ORDER BY $group_by";
													
													$result_dlvm10 = sqlsrv_query( $conn, $sql_dlvm10);
													while($r_dlvm10 = sqlsrv_fetch_array($result_dlvm10, SQLSRV_FETCH_ASSOC)) {
														$dlvm10_nbr = $r_dlvm10['dlvm_nbr'];
														$dlvm10_sptm_nbr = $r_dlvm10['dlvm_sptm_nbr'];
														$dlvm10_packing_weight = $r_dlvm10['dlvm_packing_weight'];
														$dlvm10_packing_location = $r_dlvm10['dlvm_packing_location'];
														
														//ดึงข้อมูลของใบเบิก
														$dlvm10_npd = false;
														$dlvm10_cust_amphur =  "";
														$dlvm10_cust_province = "";
														$dlvm10_cust_name = "";

														$dlvm10_npd = $r_dlvm10['sptm_npd'];
														$dlvm10_npd_type = $r_dlvm10['sptm_npd_type'];
														
														$dlvm10_npd_brand = html_quot($r_dlvm10['sptm_npd_brand']);
														$dlvm10_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$dlvm10_npd_brand,$conn);
														$dlvm10_npd_setno = substr($r_dlvm10['sptm_npd_setno'],strpos($r_dlvm10['sptm_npd_setno'],'@')+1,strlen($r_dlvm10['sptm_npd_setno']));
														
														$dlvm10_cust_code = $r_dlvm10['sptm_customer_number'];
														$dlvm10_cust_dummy = html_quot($r_dlvm10['sptm_customer_dummy']);
														$dlvm10_cust_type = $r_dlvm10['sptm_cust_type'];
														$dlvm10_cust_amphur =  html_quot($r_dlvm10['sptm_customer_amphur']);
														$dlvm10_cust_province = html_quot($r_dlvm10['sptm_customer_province']);
														$dlvm10_req_by = $r_dlvm10['sptm_req_by'];
														$dlvm10_delivery_mth = html_quot($r_dlvm10['sptm_delivery_mth']);
														$dlvm10_delivery_mth_name = findsqlval("delivery_mth","delivery_name","delivery_code",$dlvm10_delivery_mth,$conn);
														
														$dlvm10_receiver_name = $r_dlvm10['sptm_expect_receiver_name'];
														$dlvm10_receiver_tel = $r_dlvm10['sptm_expect_receiver_tel'];
														
														$dlvm10_delivery_mth_desc = $r_dlvm10['sptm_delivery_mth_desc'];
														$dlvm10_req_by = $r_dlvm10['sptm_req_by'];
														$dlvm10_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$dlvm10_req_by,$conn);

														if($dlvm10_cust_code != "DUMMY") {
															$dlvm10_cust_name = findsqlval("customer","customer_name1", "customer_number", $dlvm10_cust_code,$conn);
															if ($dlvm10_cust_name != "") {
																$dlvm10_cust_name = '['.$dlvm10_cust_code.'] ' . $dlvm10_cust_name;
															}
														}
														else {
															$dlvm10_cust_name = '<font color=red>[DUMMY]</font> ' .$dlvm10_cust_dummy;
														}
													
														if ($group_type == "A") {
															$group_data = $dlvm10_cust_name;
														}
														if ($group_type == "B") {
															$group_data = $dlvm10_delivery_mth_name;
														}
														if ($group_type == "C") {
															$group_data = $dlvm10_delivery_mth_name."-".$dlvm10_req_by_name;
														}
														if ($group_type == "D") {
															$group_data = $dlvm10_cust_name."-".$dlvm10_cust_province;
														}
														if ($group_type == "E") {
															$group_data = $dlvm10_cust_name."-".$dlvm10_delivery_mth_name;
														}
														if ($group_type == "F") {
															$group_data = $dlvm10_cust_name."-".$dlvm10_delivery_mth_desc;
														}
														if ($group_type == "G") {
															$group_data = $dlvm10_cust_province;
														}
														$dlvm10_group_array[$n] = $group_data;
														$dlvm10_dlvm_nbr_array[$n] = $dlvm10_nbr;
														$dlvm10_customer_name_array[$n] = $dlvm10_cust_name;
														$dlvm10_customer_amphur_array[$n] = $dlvm10_cust_amphur;
														$dlvm10_customer_province_array[$n] = $dlvm10_cust_province;
														$dlvm10_packing_weight_array[$n] = $dlvm10_packing_weight;
														$dlvm10_receiver_name_array[$n] = $dlvm10_receiver_name;
														$dlvm10_receiver_tel_array[$n] = $dlvm10_receiver_tel;
														$dlvm10_delivery_mth_name_array[$n] = $dlvm10_delivery_mth_name;
														$dlvm10_delivery_mth_desc_array[$n] = $dlvm10_delivery_mth_desc;
														$dlvm10_req_by_array[$n] = $dlvm10_req_by;
														$dlvm10_req_by_name_array[$n] = $dlvm10_req_by_name;
														$dlvm10_packing_location_array[$n] = $dlvm10_packing_location;
														
														if ($dlvm10_npd) {
															$dlvm10_npd_brand_name_setno_array[$n] = "<br><span style='color:red;font-size:7pt'>NPD: ".$dlvm10_npd_brand_name."<br>Lot: ".$dlvm10_npd_setno."</span>";
														} else  {
															$dlvm10_npd_brand_name_setno_array[$n] = "";
														}
														$n++;
													}
													//Copy Array เอาไว้สำหรับเปรียบเทียบเพื่อหาตัว Last Of
													$dlvm10_group2_array = $dlvm10_group_array;
													?>
													<?php
													$first_of_data = "";
													$first_of = false;
													$last_of = false;
													$dlvm10_group = 0;
													$dlvm10_zonegroup_no = "";
													?>
													<?php for($w = 0; $w < sizeof($dlvm10_dlvm_nbr_array);$w++) {?>
														<?php
														if ($dlvm10_group_array[$w] != $first_of_data) {
															$first_of = true;
															$first_of_data = $dlvm10_group_array[$w];
															$first_of_text = "Y";
															$dlvm10_group = $dlvm10_group + 1;
															$dlvm10_zonegroup_no = "zonegroup_".$dlvm10_group;
															$z=0;
															$total_weight = 0;
														}
														else {
															$first_of = false;
															$first_of_text = "N";
														}
														if ($dlvm10_group_array[$w] != $dlvm10_group2_array[$w+1]) {
															$last_of = true;
															$last_of_text = "Y";
														}
														else {
															$last_of = false;
															$last_of_text = "N";
														}
														$dlvm10_packing_weight_diff = $dlvm10_packing_weight_array[$w] - (int) $dlvm10_packing_weight_array[$w];
														if ($dlvm10_packing_weight_diff>0) {$dlvm10_packing_weight = number_format($dlvm10_packing_weight_array[$w],2);}
														else {$dlvm10_packing_weight = number_format($dlvm10_packing_weight_array[$w],0);}
														
														$dlvm10_packing_location_text = "";
														if ($dlvm10_packing_location_array[$w] != "") {
															$dlvm10_packing_location_text = "<br><span style='color:blue'>วางไว้ที่ " . $dlvm10_packing_location_array[$w]."</span>";
														}
		
		
														$total_weight = $total_weight + $dlvm10_packing_weight_array[$w];
														$z++;
														?>
														<?php if ($first_of) {?>	
															<tr><td colspan=10 style="background:#b3b3ff">
																<input type="checkbox" onclick="select_zonegroup(this.value);" name="<?php echo $dlvm10_zonegroup_no;?>" id="<?php echo $dlvm10_zonegroup_no;?>" value='<?php echo $dlvm10_zonegroup_no;?>'> 
																<b><?php echo $dlvm10_group_array[$w]?></b>
															</td>
															</tr>	
														<?php }?>
														<input type="hidden" name="dlvm_nbr_<?php echo $dlvm10_zonegroup_no.'_'.$dlvm10_dlvm_nbr_array[$w]?>" value="<?php echo $dlvm10_dlvm_nbr_array[$w];?>">
														<tr ONMOUSEOVER="this.style.backgroundColor =''" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $z;?></td>
															<td class="f_bk8" style="">
																<input type="checkbox" name="dlvm_nbr_<?php echo $dlvm10_zonegroup_no."_".$dlvm10_dlvm_nbr_array[$w]?>" id="dlvm_nbr_<?php echo $dlvm10_zonegroup_no."_".$dlvm10_dlvm_nbr_array[$w]?>" value="<?php echo $dlvm10_dlvm_nbr_array[$w]?>">
																<?php echo $dlvm10_dlvm_nbr_array[$w]; ?>
																<?php 
																	if($dlvm10_npd_brand_name_setno_array[$w]!="") {
																		echo $dlvm10_npd_brand_name_setno_array[$w];
																	}
																?>
															</td>
															<td class="f_bk8" style=""><?php echo $dlvm10_customer_name_array[$w].$dlvm10_packing_location_text?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_customer_amphur_array[$w]."/".$dlvm10_customer_province_array[$w]; ?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_delivery_mth_name_array[$w]?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_req_by_name_array[$w]?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_receiver_name_array[$w]?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_receiver_tel_array[$w]?></td>
															<td class="f_bk8" style="text-align:right;"><?php echo $dlvm10_packing_weight; ?></td>
															<td class="f_bk8" style=""><?php echo $dlvm10_delivery_mth_desc_array[$w]?></td>
														</tr>
														<?php if ($last_of) {?>
														<tr>
															<td colspan=9 style="text-align:right"><b><?php echo number_format($total_weight,2)?></b></td>
															<td style="text-align:right">
																<?php if ($can_zpm) {?>
																	<!--a href="javascript:void(0)" onclick='printzone("<?php echo $dlvm10_zonegroup_no?>","<?php echo $in_zpm_group_type?>")'>พิมพ์ใบจัดโซนตามกลุ่ม</a-->
																	<button type="button" class="btn btn-mini" onclick='printzone("<?php echo $dlvm10_zonegroup_no?>","<?php echo $in_zpm_group_type?>")' style='background:red;color:white;font-size:8pt'>>> พิมพ์ใบจัดโซนตามกลุ่ม</button>
																<?php }?>
															</td>
														</tr>
														<?php }?>
													<?php }?>
													</tbody>
												</table>
											</fieldset>
										</td>
									</tr>
								</table>
								
							</td>
						</tr>
					</table>
				</td>
			</tr>							
		</table>	
	</div>
	<form name="frm_zpmform01" target="_blank" method="POST">
		<input type="hidden" name="dlvm_nbr_list">
	</form>
	
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h5 id="msghead"></h5>
		</div>
		<center><div id="msgbody" class="modal-body"></div></center>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>			
		</div>
	</div>
	</body>
</html>
