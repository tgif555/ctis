<?php 
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
clearstatcache();

$sptm_nbr = decrypt(mssql_escape($_REQUEST['sptmnumber']), $key);
$pg=mssql_escape($_REQUEST['pg']);

$curdate = date('Y-m-d');
$filepath = "../_fileuploads/at/";
$can_edit = true;
//
$sql = "SELECT * from sptm_mstr where sptm_nbr = '$sptm_nbr' and sptm_is_delete = 0";
$result = sqlsrv_query($conn, $sql);	
$r_sptm = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
if ($r_sptm) {
	$sptm_nbr = $r_sptm['sptm_nbr'];																	
	$sptm_customer_number = $r_sptm['sptm_customer_number'];
	if ($sptm_customer_number == "DUMMY") { $sptm_customer_number=""; }
	$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
	$sptm_customer_type = $r_sptm['sptm_customer_type'];
	$sptm_reason_code = $r_sptm['sptm_reason_code'];
	$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);
	$sptm_expect_receipt_date = $r_sptm['sptm_expect_receipt_date'];
	$sptm_delivery_mth = $r_sptm['sptm_delivery_mth'];
	$sptm_delivery_mth_name = findsqlval("delivery_mth","'['+delivery_code+']'+ ' '+delivery_name", "delivery_code", $sptm_delivery_mth,$conn);
	$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
	$sptm_req_by = $r_sptm['sptm_req_by'];
	$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
	$sptm_req_by_sec = findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn);
	$sptm_req_date = $r_sptm['sptm_req_date'];
	$sptm_req_year = $r_sptm['sptm_req_year'];
	$sptm_req_month = $r_sptm['sptm_req_month'];
	$sptm_submit_date = $r_sptm['sptm_submit_date'];
	$sptm_apv_by = $r_sptm['sptm_apv_by'];
	$sptm_apv_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_apv_by,$conn);
	$sptm_apv_date = $r_sptm['sptm_apv_date'];
	$sptm_apv_cmmt = html_quot($r_sptm['sptm_apv_cmmt']);
	$sptm_step_code = $r_sptm['sptm_step_code'];
	$sptm_step_by = $r_sptm['sptm_step_by'];
	$sptm_step_by_name = findsqlval("step_mstr","step_name", "step_code", $sptm_step,$conn);
	$sptm_step_date = $r_sptm['sptm_step_date'];
	$sptm_step_cmmt = html_quot($r_sptm['sptm_step_cmmt']);	
	$sptm_remark = html_quot($r_sptm['sptm_remark']);
	$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
	$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
	$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
	$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
	
	$sptm_input_type = $r_sptm['sptm_input_type'];
	$sptm_whocanread = $r_sptm['sptm_whocanread'];
	$sptm_curprocessor = $r_sptm['sptm_curprocessor'];
	$sptm_create_by = $r_sptm['sptm_create_by'];	
	$sptm_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_create_by,$conn);
	$sptm_customer_name = findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn);		
	$sptm_npd = $r_sptm['sptm_npd'];
	$sptm_npd_com = $r_sptm['sptm_npd_com'];
	$sptm_npd_type = $r_sptm['sptm_npd_type'];
	$sptm_npd_brand = $r_sptm['sptm_npd_brand'];
	$sptm_npd_setno = $r_sptm['sptm_npd_setno'];
}
else {
	$path = "sptmauthorize.php?msg=เอกสารหมายเลข $sptm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
}	
$iscurrentprocessor = false;
$can_editing = false;
//Assign Authorize for CurrentProcessor
if (inlist($sptm_curprocessor,$user_login)) {
	//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
	//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
	$iscurrentprocessor = true;
}
else {
	//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
	//เช่นกรณี Role WH,DE
	$sptm_curprocessor_role_access = "";
	$sptm_curprocessor_role_array = explode(",",$user_role);																										
	for ($c=0;$c<count($sptm_curprocessor_role_array);$c++) {
		if (inlist($sptm_curprocessor,$sptm_curprocessor_role_array[$c])) {
			$iscurrentprocessor = true;
			break;
		}
	}
}
if ($iscurrentprocessor && inlist('0,10',$sptm_step_code)) {
	$can_editing = true;
}
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
	<link href="../_images/sampletiles.ico" rel="shortcut icon" />
	<link href="../_libs/css/_webstyle.css" type="text/css" rel="stylesheet">
    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	
	<link href="../_libs/datepicker/jquery-ui.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/datepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/css/sptm.css" rel="stylesheet">
	
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>	
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {
			$("#sptm_expect_receipt_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});	
			
			$("#btnsave").click(function() {
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/sptmpost.php',
					data: $('#frm_sptmedit').serialize(),
					timeout: 5000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {	
						//console.log(result);
						
						var json = $.parseJSON(result);
						if (json.r == '0') {
							showmsg(json.e);
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nb+'&pg='+json.pg)
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});
		});		
	</script>		
	<script language="javascript">	
		function helppopup(prgname,formname,opennerfield_code,opennerfield_code2,txtsearch) {		
			var w = 500;
			var h = 550;
			var winl = (screen.width-w)/2;
			var wint = (screen.height-h)/2;
			var settings ='height='+h+',';
			settings +='width='+w+',';
			settings +='top='+wint+',';
			settings +='left='+winl+',';
			settings +='scrollbars=no,';
			settings +='toolbar=no,';
			settings +='location=no,'; 
			settings +='resizable=yes';
			
			var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&opennerfield_code='+opennerfield_code+'&opennerfield_code2='+opennerfield_code2,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
		}
		function radiohighlight(radioObj) {
			var radioLength = radioObj.length;				
			for(var i=0; i<radioLength; i++){
				if(radioObj[i].checked) { 
					radioObj[i].style.backgroundColor='red'; 
					if(i==0) {
						if(radioObj[i].name == 'sptm_customer_type') {
							frm_sptmedit.sptm_customer_number.disabled=false;
							frm_sptmedit.sptm_customer_help.style.visibility='visible';;
							frm_sptmedit.sptm_customer_dummy.value='';
							frm_sptmedit.sptm_customer_dummy.disabled=true;
						}
					}else if(i==1) {
						if(radioObj[i].name == 'sptm_customer_type') {
							frm_sptmedit.sptm_customer_dummy.disabled=false;
							frm_sptmedit.sptm_customer_number.value='';
							frm_sptmedit.sptm_customer_name.value='';
							frm_sptmedit.sptm_reason_code.value='';
							frm_sptmedit.sptm_customer_amphur.value='';
							frm_sptmedit.sptm_customer_province.value='';
							frm_sptmedit.sptm_expect_receiver_name.value='';
							frm_sptmedit.sptm_expect_receiver_tel.value='';
							frm_sptmedit.sptm_customer_number.disabled=true;
							frm_sptmedit.sptm_customer_help.style.visibility='hidden';
							ResetRadio(frm_sptmedit.sptm_delivery_mth);
						}
					}
				}
				else {
					radioObj[i].style.backgroundColor="";
				}
			}		
		}
		function loadresult() {
			$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}
		function clearloadresult() {
			$('#div_result').html("");
		}
		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal("show");
		}
	</script>
	<style>
	.txtdata {
		color:blue;
	}
	.button{
		font-size:11px;
		font-family: Tahoma,arial,sans-serif;
	}
	input[type=radio] {
		background: white;
		color: yellow;
		-webkit-appearance: none;
		width: 16px;
		height: 16px;
		border: 1px solid black;
	}
</style>
</head>
<body>	
	<div id="div_result"></div>
	<div>
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0	width="100%" cellpadding="1" cellspacing="0">
						<tr bgcolor="lightgray">
							<td><img src='../_images/sample-icon.png' width=32>
								<span style='font-size:11pt'><b>@แก้ไขใบเบิก</b></span>
							</td>
						</tr>
						<tr>		
							<td>
								<FORM id="frm_sptmedit" name="frm_sptmedit" autocomplete=OFF method="post">
									<input name="action" type=hidden value="<?php echo md5('edit'.$user_login)?>">
									<input name="sptm_nbr" type=hidden value="<?php echo $sptm_nbr?>">
									<input name="pg" type=hidden value="<?php echo $pg?>">
									<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเบิก:</b></td>
														<td width=25% class="txtdata"><h3><?php echo $sptm_nbr?></h3></td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอเบิก:</b></td>
														<td class="txtdata"><?php echo dmytx($sptm_req_date)?></td>
													</tr>	
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
														<td class="txtdata"><b><?php echo $user_fullname;?></b></td>
																
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>สังกัดผู้ขอเบิก:</b></td>
														<td class="txtdata"><b><?php echo $user_org_name;?></b></td>
													</tr>													
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;">
															<b>รหัสลูกค้า:</b>&nbsp;<input type="radio" class="inputtext_s" name="sptm_customer_type" value="customer_number"
															<?php if ($sptm_customer_type == 'customer_number') { echo "checked " . "style='background-color:red'";}?>
															onclick="radiohighlight(sptm_customer_type)">&nbsp;
														</td>
														<td>
															<input type="text" name="sptm_customer_number" id="sptm_customer_number" value="<?php echo $sptm_customer_number?>" class="inputtext_s" style="width: 150px;" maxlength="20">														
															<button type="button" name="sptm_customer_help" id="sptm_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" OnClick="helppopup('../_help/getcustampv.php','','','',document.all.sptm_customer_number.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>															
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
														<td><input type="text"  name="sptm_customer_name" value="<?php echo $sptm_customer_name?>" class="inputtext_s" style="width: 400px;" readonly></td>
													</tr>	
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;">
															<b>ชื่อลูกค้าอื่นๆ:</b>&nbsp;<input type="radio" name="sptm_customer_type" value="customer_dummy" class="inputtext_s"
															<?php if ($sptm_customer_type == 'customer_dummy')  { echo "checked " . "style='background-color:red'";}?>
															onclick="radiohighlight(sptm_customer_type)">&nbsp;</td>
														<td>
															<input type="text"  name="sptm_customer_dummy" id="sptm_customer_dummy" value="<?php echo $sptm_customer_dummy?>" class="inputtext_s" style="width: 290px;" placeholder="* สำหรับลูกค้าที่ยังไม่ได้เปิดรหัสกับบริษัทฯ" maxlength="255">&nbsp;
															<font class="f_red8"></font>
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วัตถุประสงค์เพื่อ:</b></td>
														<td>
															<select name="sptm_reason_code" class="inputtext_s" style="width: 250px;" >
																<option value=""></option>
																<?php 
																$sql_reason = "SELECT reason_code,reason_name FROM reason_mstr WHERE reason_active = 1 order by reason_seq";
																$result_reason_list = sqlsrv_query( $conn,$sql_reason);																													
																while($r_reason_list=sqlsrv_fetch_array($result_reason_list, SQLSRV_FETCH_ASSOC)) {
																?>
																	<option 
																	<?php if ($sptm_reason_code == $r_reason_list['reason_code']) { echo "selected";}?> 
																	value="<?php echo $r_reason_list['reason_code'];?>" title="<?php echo $r_reason_list['reason_name'];?>">[<?php echo $r_reason_list['reason_code'];?>]&nbsp;<?php echo $r_reason_list['reason_name'];?></option> 
																<?php } ?>
															</select>
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>อำเภอ:</b></td>
														<td>
															<input type="text" name="sptm_customer_amphur" id="sptm_customer_amphur" class="inputtext_s" style='width:210px' maxlength=255 value="<?php echo $sptm_customer_amphur?>">
															<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
																OnClick="helppopup('../_help/getamphur.php','frm_sptmedit','sptm_customer_amphur','sptm_customer_province',document.all.sptm_customer_amphur.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>จังหวัด:</b></td>
														<td>
															<input type="text" name="sptm_customer_province" id="sptm_customer_province" class="inputtext_s"  style='width:200px' maxlength=255 value="<?php echo $sptm_customer_province?>">
															<button type="button" name="sptm_province_help" id="sptm_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
																OnClick="helppopup('../_help/getprovince.php','frm_sptmedit','sptm_customer_province','',document.all.sptm_customer_province.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอรับสินค้า:</b></td>
														<td>
															<input type="text" name="sptm_expect_receipt_date" id="sptm_expect_receipt_date" value="<?php echo dmytx($sptm_expect_receipt_date)?>" class="inputtext_s" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="วว/ดด/ปปปป" value="<?php echo $curdate;?>">													
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้รับสินค้า:</b></td>
														<td>
															<input type="text" name="sptm_expect_receiver_name" id="sptm_expect_receiver_name" class="inputtext_s" style="width:240px" maxlength=255 value="<?php echo $sptm_expect_receiver_name?>">
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วิธีการจัดส่ง:</b></td>
														<td>
															<table border=0>
																<?php
																$col=0;
																$newrow=true;
																$sql_delivery = "SELECT delivery_code,delivery_name FROM delivery_mth WHERE delivery_active = 1 order by delivery_seq";
																$result_delivery_list = sqlsrv_query( $conn,$sql_delivery);																													
																while($r_delivery_list=sqlsrv_fetch_array($result_delivery_list, SQLSRV_FETCH_ASSOC)) {
																	$delivery_code = $r_delivery_list['delivery_code'];
																	$delivery_name = html_quot($r_delivery_list['delivery_name']);
																	if ($newrow) { echo "<tr>"; $newrow = false;}?>
																	<td><input type="radio" name="sptm_delivery_mth" 
																	<?php if ($sptm_delivery_mth == $delivery_code) { echo "checked " . "style='background-color:red'";}?>
																	class="inputtext_s" value="<?php echo $delivery_code;?>" 
																	onclick="radiohighlight(sptm_delivery_mth)">&nbsp;<?php echo $delivery_name;?></td>
																	<?php
																	$col++;
																	if ($col==2) {echo "</tr>"; $newrow=true; $col=0;}
																}
																?>
															</table>
														</td>
														<td style="text-align:right; width: 150px; vertical-align: top;"><b>เบอร์โทรผู้รับสินค้า:</b></td>
														<td style="vertical-align: top;">
															<input type="text" name="sptm_expect_receiver_tel" id="sptm_expect_receiver_tel" class="inputtext_s" style="width:240px" maxlength=60 value="<?php echo $sptm_expect_receiver_tel?>">
														</td>
													</tr>
													<?php if (inlist($user_role,"NPD")) {?>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Company:</b></td>
														<td>
															<select name="sptm_npd_com" class="inputtext_s" style="width: 120px;" >
																<option value="">--เลือก--</option>
																<?php 
																$sql_com = "SELECT com_code,com_name FROM com_mstr WHERE com_active = 1 order by com_seq";
																$result_com_list = sqlsrv_query( $conn,$sql_com);																													
																while($r_com_list=sqlsrv_fetch_array($result_com_list, SQLSRV_FETCH_ASSOC)) {
																?>
																	<option value="<?php echo $r_com_list['com_code'];?>" <?php if ($sptm_npd_com == $r_com_list['com_code']) {echo "selected";}?>>
																		<?php echo html_quot($r_com_list['com_name']);?>
																	</option> 
																<?php } ?>
															</select>															
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Brand:</b></td>
														<td>
															<select name="sptm_npd_brand" class="inputtext_s" style="width: 120px;" >
																<option value="">--เลือก--</option>
																<?php 
																$sql_brand = "SELECT brand_code,brand_name FROM brand_mstr WHERE brand_active = 1 order by brand_seq";
																$result_brand_list = sqlsrv_query( $conn,$sql_brand);																													
																while($r_brand_list=sqlsrv_fetch_array($result_brand_list, SQLSRV_FETCH_ASSOC)) {
																?>
																	<option value="<?php echo $r_brand_list['brand_code'];?>" <?php if ($sptm_npd_brand == $r_brand_list['brand_code']) {echo "selected";}?>>
																		<?php echo html_quot($r_brand_list['brand_name']);?>
																	</option> 
																<?php } ?>
															</select>															
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Set No:</b></td>
														<td>
															<input type="text" name="sptm_npd_setno" class="inputtext_s" style="width:100px" maxlength=30 value="<?php echo $sptm_npd_setno?>">
														</td>
													</tr>
													<?php }?>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเหตุการจัดส่ง:</b></td>
														<td><input type="text" name="sptm_delivery_mth_desc" id="sptm_delivery_mth_desc" value="<?php echo $sptm_delivery_mth_desc;?>" class="inputtext_s" placeholder="* ระบุหมายเหตุการจัดส่ง" style="width: 290px;" maxlength="255"></td>										
														<td></td>
													</tr>
													<tr>
														<td></td>
														<td>
															<?php if ($can_editing) {?>
															<div id="btnsave" class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 50px;">
																<i class="icon-white icon-ok"></i>
																<span>SAVE</span>														
															</div>																	
															<?php }?>
															<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 60px;" onclick="window.location.href='sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&pg=<?php echo $pg?>'">
																<i class="icon-white icon-remove"></i>
																<span>CANCEL</span>															
															</div>																									
														</td>
													</tr>
												</table>
											</td>
										</tr>																
									</table>
								</FORM>
							</td>						
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>																			
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Message</h4>
				</div>
				<div id='modal-body' class="modal-body" style='color:red'>
				  <p></p>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>
