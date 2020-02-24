<?php 
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	
	$curdate = date('Y-m-d');
	$sptm_nbr = decrypt($_REQUEST['sptmnumber'], $key);
	$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
	
	$sql = "SELECT * from sptm_mstr where sptm_nbr = '$sptm_nbr' and sptm_is_delete = 0";
	$result = sqlsrv_query($conn, $sql);	
	$r_sptm = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($r_sptm) {
		$sptm_nbr = $r_sptm['sptm_nbr'];																	
		$sptm_customer_number = html_quot($r_sptm['sptm_customer_number']);
		$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
		$sptm_customer_type = $r_sptm['sptm_customer_type'];
		$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
		$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
		$sptm_reason_code = $r_sptm['sptm_reason_code'];
		$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);
		$sptm_expect_receipt_date = $r_sptm['sptm_expect_receipt_date'];
		$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
		$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
		$sptm_delivery_mth = html_quot($r_sptm['sptm_delivery_mth']);
		$sptm_delivery_mth_name = findsqlval("delivery_mth","'['+delivery_code+']'+ ' '+delivery_name", "delivery_code", $sptm_delivery_mth,$conn);
		$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
		$sptm_req_by = $r_sptm['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_by_sec = findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_date = $r_sptm['sptm_req_date'];
		$sptm_req_year = $r_sptm['sptm_req_year'];
		$sptm_req_month = $r_sptm['sptm_req_month'];
		$sptm_submit_date = $r_sptm['sptm_submit_date '];
		$sptm_approve_by = $r_sptm['sptm_approve_by'];
		$sptm_approve_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_approve_by,$conn);
		$sptm_approve_date = $r_sptm['sptm_approve_date'];
		$sptm_approve_cmmt = html_quot($r_sptm['sptm_approve_cmmt']);
		$sptm_receive_status = $r_sptm['sptm_receive_status'];
		$sptm_step_code = $r_sptm['sptm_step_code'];
		$sptm_step_by = $r_sptm['sptm_step_by'];
		$sptm_step_name = findsqlval("step_mstr","step_name", "step_code", $sptm_step_code,$conn);
		$sptm_step_date = $r_sptm['sptm_step_date'];
		$sptm_step_cmmt = html_quot($r_sptm['sptm_step_cmmt']);	
		$sptm_remark = html_quot($r_sptm['sptm_remark']);
		$sptm_print_cnt = $r_sptm['sptm_print_cnt'];
		$sptm_input_type = $r_sptm['sptm_input_type'];
		$sptm_whocanread = $r_sptm['sptm_whocanread'];
		$sptm_curprocessor = $r_sptm['sptm_curprocessor'];																									
		$sptm_create_by = $r_sptm['sptm_create_by'];	
		$sptm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_create_by,$conn);
		
		if($sptm_customer_number != "DUMMY") {
			$sptm_customer_name = findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn);
			if ($sptm_customer_name != "") { 
				$sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name; 
			}
			else { $sptm_customer_name = $sptm_customer_dummy; }
		}
		else {
			$sptm_customer_name = '<font color=red>[DUMMY]</font> ' .$sptm_customer_dummy;
		}										
	}
	else {
		$path = "sptmauthorize.php?msg=เอกสารหมายเลข $sptm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	
	$sptm_receive_status_name = "";
	if ($sptm_receive_status == "P") {
		$sptm_receive_status_name = "<span style='background:green;border-radius:4px;color:white'> ** ลูกค้ารับสินค้าบางส่วนแล้ว ** </span>";
	}
	elseif ($sptm_receive_status == "C") {
		$sptm_receive_status_name = "<span style='background:green;border-radius:4px;color:white'> ** ลูกค้ารับสินค้าครบแล้ว ** </span>";
	}	
	
	$can_editing = false;
	if (inlist($expm_curprocessor,$user_login)) {
		$can_editing = true;
	}	
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
	<link href="../_libs/prettyPhoto_3.1.6/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script src="../_libs/js/bootbox.min.js"></script>	
	<script src="../_libs/prettyPhoto_3.1.6/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$(document).ready(function () {  
			$("a[rel^='prettyPhoto']").prettyPhoto();	
		});		
	</script>
	
	<script language="javascript">	
		function helppopup(prgname,formname,opennerfield_code,opennerfield_code2,txtsearch) {
			var w = 600;
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
		
		function seteditvalue(sptnpd_id,sptnpd_customer_number,sptnpd_customer_name,sptnpd_customer_amphur,sptnpd_customer_province,sptnpd_expect_receiver_name,sptnpd_expect_receiver_tel) {
			
			
			document.frm_edit_sptnpdcust.sptnpd_id.value = sptnpd_id;
			
			document.frm_edit_sptnpdcust.sptnpd_customer_number.value = sptnpd_customer_number;
			
			document.frm_edit_sptnpdcust.sptnpd_customer_name.value = sptnpd_customer_name;
			document.frm_edit_sptnpdcust.sptnpd_customer_amphur.value = sptnpd_customer_amphur;
			document.frm_edit_sptnpdcust.sptnpd_customer_province.value = sptnpd_customer_province;
			document.frm_edit_sptnpdcust.sptnpd_expect_receiver_name.value = sptnpd_expect_receiver_name;
			document.frm_edit_sptnpdcust.sptnpd_expect_receiver_tel.value = sptnpd_expect_receiver_tel;
			
			$("#edit_sptnpdcust").modal("show");
			
	
		}
		function sptnpdcustpost(formname) {	
			
			var errorflag = false;
			var errortxt = "";
			var sptnpd_sptm_nbr = document.forms[formname].sptm_nbr.value;
			var sptnpd_customer_number = document.forms[formname].sptnpd_customer_number.value;
			
			var sptnpd_customer_amphur = document.forms[formname].sptnpd_customer_amphur.value;
			var sptnpd_customer_province = document.forms[formname].sptnpd_customer_province.value;
			
			var sptnpd_except_receiver_name = document.forms[formname].sptnpd_expect_receiver_name.value;
			
			var sptnpd_expect_receiver_tel  = document.forms[formname].sptnpd_expect_receiver_tel.value;
			
			
			if (sptnpd_customer_number=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ - [รหัสลูกค้า]";
			}
			else {
				if (sptnpd_customer_number != "NPD" && sptnpd_customer_number!= "DUMMY") {
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {								
						if (xhttp.readyState == 4 && xhttp.status == 200) {								
							if (xhttp.responseText == false) {						
								if (errortxt!="") {errortxt = errortxt + "<br>";}
								errorflag = true;
								errortxt = errortxt + "ไม่พบรหัสลูกค้าในระบบ";
							}																	
						}
					}
					xhttp.open("POST", "../_chk/chkcustcodeexist.php",false);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp.setRequestHeader("Pragma", "no-cache");
					xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
					xhttp.send("customer_number="+sptnpd_customer_number);
				}
				else {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ระบบไม่อนุญาติให้ใช้รหัสลูกค้า NPD หรือ DUMMY";
				}
				if (formname == "frm_add_sptnpdcust") {
					var xhttp1 = new XMLHttpRequest();
					xhttp1.onreadystatechange = function() {								
						if (xhttp1.readyState == 4 && xhttp1.status == 200) {
							if (xhttp1.responseText == true) {						
								if (errortxt!="") {errortxt = errortxt + "<br>";}
								errorflag = true;
								errortxt = errortxt + "รหัสลูกค้าที่เลือกมีอยู่แล้ว";
							}																	
						}
					}
					xhttp1.open("POST", "../_chk/chkcustcodedupnpd.php",false);
					xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp1.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp1.setRequestHeader("Pragma", "no-cache");
					xhttp1.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
					xhttp1.send("sptnpd_sptm_nbr="+sptnpd_sptm_nbr+"&customer_number="+sptnpd_customer_number);
					
				}
			}
			
			if (sptnpd_customer_amphur=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ - [อำเภอ]";
			}
			if (sptnpd_customer_province=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ - [จังหวัด]";
			}
			if (sptnpd_except_receiver_name=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ - [ชื่อผู้รับสินค้า]";
			}
			
			if (sptnpd_expect_receiver_tel=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ - [เบอร์โทรผู้รับสินค้า]";
			}
			
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";									
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {											
				document.forms[formname].submit();							
			}
		}	
		function upload_sptnpdcust_postform() {
			var filename = document.frm_upload_sptnpdcust.fileupload_sptnpdcust.value;
			var ext = filename.substr(filename.lastIndexOf('.') + 1);
			if (filename == "" || ext != 'xls') {
				alert('System allow for excel 2003 (*.xls) file format only!!');
				return;
			}
			if(confirm('คุณต้องการ Upload ใช่หรือไม่ ?')) {			
				//document.frm_upload_sptd_product.submit();
				var result_text="";
				$.ajaxSetup({
					cache: false,
					contentType: false,
					processData: false
				}); 
				var formObj = $('#frm_upload_sptnpdcust')[0];
				var formData = new FormData(formObj);
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: "POST",
					url: '../serverside/sptnpdcustpost.php',
					data: formData,
					timeout: 600000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(data) {
						//console.log(data);
						
						var json = $.parseJSON(data);
						if (json.res == '0') {
							showmsg(json.err);
						}
						else {
							result_text += "<span style='color:green'><h3>[ทำรายการสำเร็จค่ะ]</h3>";
							if (json.err!="") {
								result_text +="\n"+json.err;
							}
							if (json.err!="") {
								bootbox.alert(result_text, function(){
									$(location).attr('href', 'sptnpdcust.php?sptmnumber='+json.nbr+'&pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'sptnpdcust.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							}
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		
		function delsptnpdcust(sptnpd_id) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {				
				document.frmdelete.sptnpd_id.value = sptnpd_id;			
				document.frmdelete.submit();
			}
		}
		function loadresult() {
			$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}
		function clearloadresult() {
			$('#result').html("");
		}
		function showmsg(msg) {
			$("#msghead").html("พบข้อผิดผลาดในการบันทึกข้อมูล");
			$("#msgbody").html(msg);
			$("#myModal").modal("show");
		}
	</script>	
	
</head>
<body style="background-color:#e6e6ff">
	<div id="result"></div>
	<div style="background-color:white"><?php include("../menu.php"); ?></div>	
	<div>
	<TABLE width=100% height=100% align=center cellpadding=0 cellspacing=0 border=0>			
		<tr>			
			<td>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">					
					<tr>						
						<td>
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr bgcolor="orange">
									<td style="padding-left:5px;"><h3>ใบเบิกกระเบื้องตัวอย่าง</h3></td>
								</tr>	
								<tr><td height="2px"></td><tr>
								<tr>
									<td>
										<fieldset style="border-radius:4px;width:98%">
										<legend style="align:left"><b>ข้อมูลลูกค้า:</b></legend>
										<center>
										<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเบิก:</b></td>
												<td width=25% class="text_s_disable"><h3><?php echo $sptm_nbr?></h3></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอเบิก:</b></td>
												<td class="text_s_disable"><?php echo dmytx($sptm_req_date)?></td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_req_by_name;?></b></td>
														
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>สังกัดผู้ขอเบิก:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_req_by_sec;?></b></td>
											</tr>													
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>รหัสลูกค้า:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_customer_name?></b></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วัตถุประสงค์เพื่อ:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_reason_name?></b></td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>อำเภอ:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_customer_amphur?></b></td>
								
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>จังหวัด:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_customer_province?></b></td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอรับสินค้า:</b></td>
												<td class="text_s_disable"><?php echo dmytx($sptm_expect_receipt_date)?></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้รับสินค้า:</td>
												<td class="text_s_disable">
													<?php echo $sptm_expect_receiver_name?>
												</td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วิธีการจัดส่ง:</b></td>
												<td class="text_s_disable"><?php echo $sptm_delivery_mth_name?></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>เบอร์โทรผู้รับสินค้า:</td>
												<td class="text_s_disable">
													<?php echo $sptm_expect_receiver_tel?>
												</td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเหตุการจัดส่ง:</b></td>
												<td class="text_s_disable"><?php echo $sptm_delivery_mth_desc?></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>Staus:</td>
												<td style="font-size:11pt;font-weight:bold;color:red">
													<?php echo "** ".$sptm_step_name." ".$sptm_receive_status_name . " **"?>
												</td>
											</tr>
											<?php if ($sptm_attach_link != "") {?>
												<?php $sptm_attach = $sptm_attach_link;?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>Attach File:</b></td>
													<td colspan=2 bgcolor=#ffe6e6><?php echo $sptm_attach;?></td>
													<td></td>
													
												</tr>												
											<?php }?>											
											<tr>
												<td></td>
												<td>
													<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
														<i class="icon-white icon-hand-left"></i>
														<span>Black</span>													
													</div>
												</td>
												<td></td>												
												<td align=right>																																						
													<?php if ($can_editing && $sptm_step_code <= 30) { ?>
													
													<a href="#upload_sptnpdcust" role="button" style="color:gold; text-decoration:none;" data-toggle="modal">
														<div class="btn btn-small btn-warning paddingleftandright10" style="margin-top:5px; margin-bottom:10px;">
															<i class="icon-hand-up icon-white"></i>														
															Upload ลูกค้ารับตัวอย่าง
														</div>
													</a>  
													
													<a href="#add_sptnpdcust" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
														<div class="btn btn-small btn-primary paddingleftandright10" style="margin-top:5px; margin-bottom:10px;">
															<i class="icon-plus icon-white"></i>														
															เพิ่มลูกค้ารับตัวอย่าง
														</div>
													</a>
													<?php }?>
													<div style="width:20px"></div>													
												</td>
											</tr>
										</table>
										</center>
										</fieldset>
									</td>
								</tr>
								<tr><td height="2px"></td></tr>
								<tr>
									<td>
										<fieldset style="border-radius:4px;width:98%">
											<legend style="align:left"><b>Customer List:</b></legend>
											<center>
												<table class="table table-striped table-bordered" width="100%">
													<tr>
														<th style="background:paleturquoise;text-align:center;">No</th>			
														<th style="background:paleturquoise;text-align:center;">รหัสลูกค้า</th>
														<th style="background:paleturquoise;text-align:center;">ชื่อลูกค้า</th>
														<th style="background:paleturquoise;text-align:center;">อำเภอ</th>
														<th style="background:paleturquoise;text-align:center;">จังหวัด</th>		
														<th style="background:paleturquoise;text-align:center;">ชื่อผู้รับสินค้า</th>
														<th style="background:paleturquoise;text-align:center;">เบอร์โทรผู้รับสินค้า</th>	
														<th style="background:paleturquoise;text-align:center;">Action</th>
														<th style="background:paleturquoise;text-align:center;"> </th>
													</tr>                        
													<?php										
													$n = 0;																																										
													$sql = "SELECT * FROM sptnpd_cust where sptnpd_sptm_nbr = '$sptm_nbr' order by sptnpd_id";
													$result = sqlsrv_query( $conn, $sql );											
													while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {																																	
														$sptnpd_id = $r['sptnpd_id'];
														$sptnpd_sptm_nbr = $r['sptnpd_sptm_nbr'];																							
														$sptnpd_customer_number = $r['sptnpd_customer_number'];
														$sptnpd_customer_name = findsqlval("customer","customer_name1","customer_number",$sptnpd_customer_number,$conn);
														$sptnpd_customer_amphur = $r['sptnpd_customer_amphur'];
														$sptnpd_customer_province = $r['sptnpd_customer_province'];
														$sptnpd_expect_receiver_name = $r['sptnpd_expect_receiver_name'];
														$sptnpd_expect_receiver_tel = $r['sptnpd_expect_receiver_tel'];
														
														$n++;																			
														?>
														<tr>
															<td style="width:50px;text-align:center;background:white;"><?php echo $n;?></td>                                            
															<td style="width:80px;background:white;"><?php echo $sptnpd_customer_number; ?></td>
															<td style="width:250px;background:white;"><?php echo $sptnpd_customer_name; ?></td>
															<td style="width:100px;background:white;"><?php echo $sptnpd_customer_amphur; ?></td>
															<td style="width:100px;background:white;"><?php echo $sptnpd_customer_province; ?></td>
															<td style="width:100px;background:white;"><?php echo $sptnpd_expect_receiver_name; ?></td>
															<td style="width:100px;background:white;"><?php echo $sptnpd_expect_receiver_tel; ?></td>
																												
															<td style="background:white;padding-right:20px;">																																											
															<?php if ($can_editing) {?>
																<button type="button" class="btn btn-mini btn-warning paddingleftandright10 pull-right" style="margin-right:10px;" onclick="seteditvalue('<?php echo $sptnpd_id;?>','<?php echo $sptnpd_customer_number;?>','<?php echo $sptnpd_customer_name;?>','<?php echo $sptnpd_customer_amphur;?>','<?php echo $sptnpd_customer_province;?>','<?php echo $sptnpd_expect_receiver_name;?>','<?php echo $sptnpd_expect_receiver_tel;?>')">
																	<i class="icon-white icon-trash"></i>
																	<span>Edit</span>
																</button>
																<button type="button" class="btn btn-mini btn-danger paddingleftandright10 pull-right" style="margin-right:10px;" onclick="delsptnpdcust('<?php echo $sptnpd_id;?>')">
																	<i class="icon-white icon-trash"></i>
																	<span>Del</span>
																</button>			
															<?php }?>
															</td>
															<td style="text-align:center">
																<?php if($activeid==$sptnpd_id) {echo "<img src='../_images/active-id.png'>";}?>
															</td>																																	
														</tr>												
													<?php } ?>																											
												</table> 
											</center>
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
	<div id="add_sptnpdcust" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_add_sptnpdcust" autocomplete=OFF method="post" action="../serverside/sptnpdcustpost.php" enctype="multipart/form-data">		
			<input type="hidden" name="action" value="<?php echo md5('sptnpdcust_add'.$user_login)?>">																		
			<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">															
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">[เพิ่มลูกค้ารับตัวอย่าง]</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-condensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="width:130px;text-align:right;"><b>รหัสลูกค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" maxlength="30" name="sptnpd_customer_number">							
							<button type="button" name="sptnpd_customer_help" id="sptnpd_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" OnClick="helppopup('../_help/getcustampvnpd.php','frm_add_sptnpdcust','','',document.frm_add_sptnpdcust.sptnpd_customer_number.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 350px;" name="sptnpd_customer_name" readonly>							
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>อำเภอ:</b></td>
						<td>
							<input type="text" readonly class="span5" style="width: 150px;" name="sptnpd_customer_amphur">
							<!--button type="button" name="sptnpd_amphur_help" id="sptnpd_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getamphur.php','frm_add_sptnpdcust','sptnpd_customer_amphur','sptnpd_customer_province',document.frm_add_sptnpdcust.sptnpd_customer_amphur.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button-->
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>จังหวัด:</b></td>
						<td>
							<input type="text" readonly class="span5" style="width: 150px;" name="sptnpd_customer_province">							
							<!--button type="button" name="sptnpd_province_help" id="sptnpd_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getprovince.php','frm_add_sptnpdcust','sptnpd_customer_province','',document.frm_add_sptnpdcust.sptm_customer_province.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button-->
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>ชื่อผู้รับสินค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" name="sptnpd_expect_receiver_name" maxlength="255">							
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>เบอร์โทรผู้รับสินค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" name="sptnpd_expect_receiver_tel" maxlength="60">							
						</td>
					</tr>																																				
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="sptnpdcustpost('frm_add_sptnpdcust')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
		</form>																																																			
	</div>
	<div id="edit_sptnpdcust" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_edit_sptnpdcust" autocomplete=OFF method="post" action="../serverside/sptnpdcustpost.php" enctype="multipart/form-data">		
			<input type="hidden" name="action" value="<?php echo md5('sptnpdcust_edit'.$user_login)?>">																		
			<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
			<input type="hidden" name="sptnpd_id">			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">[แก้ไขลูกค้ารับตัวอย่าง]</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-condensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="width:130px;text-align:right;"><b>รหัสลูกค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" maxlength="30" name="sptnpd_customer_number">							
							<button type="button" name="sptnpd_customer_help" id="sptnpd_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" OnClick="helppopup('../_help/getcustampvnpd.php','frm_edit_sptnpdcust','','',document.frm_edit_sptnpdcust.sptnpd_customer_number.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 350px;" name="sptnpd_customer_name" readonly>							
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>อำเภอ:</b></td>
						<td>
							<input type="text" readonly class="span5" style="width: 150px;" name="sptnpd_customer_amphur">
							<!--button type="button" name="sptnpd_amphur_help" id="sptnpd_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getamphur.php','frm_add_sptnpdcust','sptnpd_customer_amphur','sptnpd_customer_province',document.frm_add_sptnpdcust.sptnpd_customer_amphur.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button-->
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>จังหวัด:</b></td>
						<td>
							<input type="text" readonly class="span5" style="width: 150px;" name="sptnpd_customer_province">							
							<!--button type="button" name="sptnpd_province_help" id="sptnpd_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getprovince.php','frm_add_sptnpdcust','sptnpd_customer_province','',document.frm_add_sptnpdcust.sptm_customer_province.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button-->
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>ชื่อผู้รับสินค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" name="sptnpd_expect_receiver_name" maxlength="255">							
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>เบอร์โทรผู้รับสินค้า:</b></td>
						<td>
							<input type="text" class="span5" style="width: 150px;" name="sptnpd_expect_receiver_tel" maxlength="60">							
						</td>
					</tr>																																				
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="sptnpdcustpost('frm_edit_sptnpdcust')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
		</form>																																																			
	</div>
	<div id="upload_sptnpdcust" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form id="frm_upload_sptnpdcust" name="frm_upload_sptnpdcust" autocomplete=OFF method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo md5('sptnpdcust_upload'.$user_login)?>">																		
			<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
			<input type="hidden" name="pg" value="<?php echo $pg;?>">															
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">นำรายการลูกค้าเข้าจาก Excel File :: <?php echo $sptm_nbr?></h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table border=1 class="table table-condensed table-responsive">	
				<tbody bgcolor=#f5f5ef>																	
					<tr>
						<td style="width:180px;text-align:right; vertical-align: middle;"><b>File Excel (รายการลูกค้า):</b></td>
						<td><input type="file"  name="fileupload_sptnpdcust" class="inputtext_s" style="width: 400px;"></td>	 
					</tr>
				</tbody>
				</table>					
			</div>
			<?php if ($can_editing) {?>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="upload_sptnpdcust_postform()">
					<i class="icon-check icon-white"></i>
					<span>Start Upload</span>
				</button>											
			</div>	
			<?php }?>
		</form>																																																			
	</div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <!--h4 class="modal-title">Message</h4-->
				  <h5 id="msghead"></h5>
				</div>
				<div id='msgbody' class="modal-body" style='color:red'>
				  <p></p>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<form name="frmdelete" method="post" action="../serverside/sptnpdcustpost.php">
		<input type="hidden" name="action" value="<?php echo md5('sptnpdcust_del'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
		<input type="hidden" name="sptnpd_id">		
	</form>	
	</body>
</html>