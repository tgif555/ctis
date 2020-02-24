<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
define('PROJECT_ROOT', dirname(dirname(__FILE__)));			
$f = $_REQUEST['f'];
$can_download = "NO";
if ($f != "") {
	$fp=PROJECT_ROOT."\_filedownloads/".$f;
	if(file_exists("$fp")) { $can_download = "OK"; }	
}

set_time_limit(0);
$curdate = date('Ymd');

$begdate = date('Ym01');
$curdate1 = date('Ymd');
set_time_limit(0);
$req_dt1 = dmytx($begdate);
$req_dt2 = dmytx($curdate1);
$apv_dt1 = dmytx($begdate);
$apv_dt2 = dmytx($curdate1);
//ตรวจสอบสิทธิถ้าไม่มี Role ก็ให้ Return ไปหน้า Error
$allow_report = false;
if (!inlist($user_role,"CS") && !inlist($user_role,"SPT_ROOM") && !inlist($user_role,"ADMIN")) {
	$path = "sptmauthorize.php"; 
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
}
else {
	$allow_report = true;
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
	<script src="../_libs/js/CalendarPopup.js"></script>	
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script src="../_libs/js/bootbox.min.js"></script>	
	<script type="text/javascript">
		$(document).ready(function () {    
			$("#in_sptmrpt_req_date1").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});
			$("#in_sptmrpt_req_date2").datepicker({
					dateFormat: 'dd/mm/yy',
					numberOfMonths: 1		
			});
			$("#in_sptmrpt_apv_date1").datepicker({
					dateFormat: 'dd/mm/yy',
					numberOfMonths: 1		
			});
			$("#in_sptmrpt_apv_date2").datepicker({
					dateFormat: 'dd/mm/yy',
					numberOfMonths: 1		
			});
		});	
	</script>
	
	<script language="javascript">		
		function loadresult() {
			
			//document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
			//document.all.result.innerHTML = "<p>test</p>";
			$("#result").html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}		
		function clearresult() {
			$("#result").html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
			//document.all.result.innerHTML = "";
			//document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading.gif' width=80 height=80><center>";			
		}				
		function runreport() {
			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var in_sptmrpt_req_date1 = document.forms["frm_report"].in_sptmrpt_req_date1.value;
			var in_sptmrpt_req_date2 = document.forms["frm_report"].in_sptmrpt_req_date2.value;
			var in_sptmrpt_apv_date1 = document.forms["frm_report"].in_sptmrpt_apv_date1.value;
			var in_sptmrpt_apv_date2 = document.forms["frm_report"].in_sptmrpt_apv_date1.value;
			
			if (isDate(in_sptmrpt_req_date1,"dd/MM/yyyy")==false || in_sptmrpt_req_date1=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุวันที่่ขอเบิกให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";									
			}
			
			if (isDate(in_sptmrpt_req_date2,"dd/MM/yyyy")==false || in_sptmrpt_req_date2=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุวันที่ขอเบิกให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";									
			}
			
			if (in_sptmrpt_apv_date1 != "") {
				if (isDate(in_sptmrpt_apv_date1,"dd/MM/yyyy")==false) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;					
					errortxt = errortxt + "กรุณาระบุวันที่อนุมัติให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";									
				}					
			}
			if (in_sptmrpt_apv_date2 != "") {
				if (isDate(in_sptmrpt_apv_date2,"dd/MM/yyyy")==false) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;					
					errortxt = errortxt + "กรุณาระบุวันที่อนุมัติให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";									
				}					
			}
			
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {	
				//clearresult()
				document.frm_report.action.value="REPORT";	
				//document.frm_report.action = "sptmrpt01a.php";				
				//document.frm_report.submit();	

				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: 'sptmrpt01a.php',
					data: $('#frm_report').serialize(),
					timeout: 600000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {	
						//console.log(result);
						
						var json = $.parseJSON(result);	
						if (json.res == '0') {
							showmsg(json.err);
						}
						else {
							result_text += "<span style='color:green'><h3>[ทำรายการสำเร็จค่ะ]</h3>";
							if (json.err!="") {
								result_text +="\n"+json.err;
							}
							bootbox.alert(result_text, function(){ 
								$(location).attr('href', 'dwfile.php?f='+json.fileoutput);
									
							});
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
						
					}
				});

				
			}	
		}		
	</script>	
	<style>		
		#progress{ 		
			position: absolute;
			top: 50%;
			left: 50%;
			margin-top: -50px;
			margin-left: -50px;
			width: 100px;
			height: 100px;
			z-index: 3;
		}
	</style>
</head>
<body>			
	<div id="result"></div>
	<div>			
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>				
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0	width="100%" cellpadding="1" cellspacing="0">				
						<tr bgcolor="lightgray">
							<td><img src='../_images/sample-icon.png' width=32>
								<span style='font-size:11pt'><b>@รายงาน (กระเบื้องตัวอย่าง)</b></span>
							</td>
							<td>
								
							</td>
						</tr>				
						<tr>
							<td width=75% valign=top>
								<table width="80%" border="0" bgcolor="DarkKhaki">
									<form id="frm_report" name="frm_report"  method="post" autocomplete=OFF>			
									<input name="action" type="hidden" >											
									<tr>
										<td align=center>
											<table>
												<tr><td colspan=4><hr></td></tr>
												<tr>
													<td align=right><b>หมายเลขใบเบิก:</b></td>
													<td width=20%><input id="in_sptmrpt_nbr1" name="in_sptmrpt_nbr1" class="span5" style="margin: auto;width: 120px;" type="text"></td>
													<td align=right><b>ถึง:</b></td>
													<td><input id="in_sptmrpt_nbr2" name="in_sptmrpt_nbr2" class="span5" style="margin: auto;width: 120px;" type="text"></td>
												</tr>
												<tr>
													<td align=right><b>วันที่ขอเบิก:</b></td>
													<td width=20%><input id="in_sptmrpt_req_date1" name="in_sptmrpt_req_date1" value="<?php echo $req_dt1?>" class="span5" style="margin: auto;width: 120px;" pattern="\d{1,2}/\d{1,2}/\d{4}" maxlength=10 type="text"></td>
													<td align=right><b>ถึง:</b></td>
													<td><input id="in_sptmrpt_req_date2" name="in_sptmrpt_req_date2" value="<?php echo $req_dt2?>" class="span5" style="margin: auto;width: 120px;" pattern="\d{1,2}/\d{1,2}/\d{4}" maxlength=10 type="text"></td>
												</tr>
												<tr>
													<td align=right><b>วันที่อนุมัติ:</b></td>
													<td><input id="in_sptmrpt_apv_date1" name="in_sptmrpt_apv_date1" value="<?php echo $apv_dt1?>" class="span5" style="margin: auto;width: 120px;" pattern="\d{1,2}/\d{1,2}/\d{4}" maxlength=10 type="text"></td>
													<td align=right><b>ถึง:</b></td>
													<td><input id="in_sptmrpt_apv_date2" name="in_sptmrpt_apv_date2" value="<?php echo $apv_dt2?>" class="span5" style="margin: auto;width: 120px;" pattern="\d{1,2}/\d{1,2}/\d{4}" maxlength=10 type="text"></td>
												</tr>
												
												<tr>
													<td style="width:80px;text-align:right" class="f_bk8b">สถาน:</td>
													<td style="width:160px">
														<select name="in_sptmrpt_step_code" class="f_bl8" style="width: 130px;margin: auto" >
															<option value="">-- ทั้งหมด --</option>
															<?php 
															$sql_step = "SELECT step_code,step_name FROM step_mstr WHERE step_code IN ('30','990') order by step_seq";												
															$result_step_list = sqlsrv_query( $conn,$sql_step);																													
															while($r_step_list=sqlsrv_fetch_array($result_step_list, SQLSRV_FETCH_ASSOC)) {
															?>
																<option  value="<?php echo $r_step_list['step_code'];?>"
																<?php if (trim($r_step_list['step_code']) == "$in_sptmrpt_step_code") { echo "selected"; } ?>>
																<?php echo html_quot($r_step_list['step_name']);?></option> 
															<?php } ?>
														</select>
													</td>
													<td></td>
													<td>
														<input name="in_sptmrpt_shownpd" type="checkbox" <?php if ($in_sptmrpt_shownpd=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>แสดงเฉพาะ NPD</b></span>											
													</td>
												</tr>
												<tr>
													<td style="text-align:right" class="f_bk8b">ชื่อลูกค้า<font color=red><b>*</b></font>:</td>
													<td style=""><input name="in_sptmrpt_customer" style='width:240px; color:blue;margin:auto'></td>
												</tr>
												<tr>
													<td style="text-align:right; width: 150px;"><b>วิธีการจัดส่ง:</b></td>
													<td>
														<select name="in_sptmrpt_delivery_mth" class="f_bl8" style="width: 250px;margin: auto" >>
															<option value="">-เลือกทั้งหมด-</option>
															<?php
															$sql_delivery = "SELECT delivery_code,delivery_name FROM delivery_mth WHERE delivery_active = 1 order by delivery_seq";
															$result_delivery_list = sqlsrv_query( $conn,$sql_delivery);																													
															while($r_delivery_list=sqlsrv_fetch_array($result_delivery_list, SQLSRV_FETCH_ASSOC)) {
																$delivery_code = $r_delivery_list['delivery_code'];
																$delivery_name = html_quot($r_delivery_list['delivery_name']);
																?>
																<option value="<?php echo $delivery_code;?>"><?php echo $delivery_name;?></option>
																<?php
															}
															?>
														</select>
													</td>
													
												</tr>
												<tr>
													<td style="text-align:right; width: 150px;"><b>วัตถุประสงค์เพื่อ:</b></td>
													<td>
														<select name="in_sptmrpt_reason_code" class="f_bl8" style="width: 250px;margin: auto" >>
															<option value="">-เลือกทั้งหมด-</option>
															<?php 
															$sql_reason = "SELECT reason_code,reason_name FROM reason_mstr WHERE reason_active = 1 order by reason_seq";
															$result_reason_list = sqlsrv_query( $conn,$sql_reason);																													
															while($r_reason_list=sqlsrv_fetch_array($result_reason_list, SQLSRV_FETCH_ASSOC)) {
															?>
																<option  value="<?php echo $r_reason_list['reason_code'];?>" title="<?php echo $r_reason_list['reason_name'];?>">[<?php echo $r_reason_list['reason_code'];?>]&nbsp;<?php echo html_quot($r_reason_list['reason_name']);?></option> 
															<?php } ?>
														</select>
													</td>
												</tr>
												
												<tr><td colspan=4><hr></td></tr>
												
											</table>
										</td>							
									</tr>	
									<tr><td colspan=4><div id="result"></div></td></tr>
									</form>								
								</table>
							</td>
							<td valign=middle align=right>
								<table cellpadding=5 cellspacing=5>
									<tr>
										<td>
											<?php if ($allow_report) {?>
											<a href='javascript:void(0)' style="text-decoration: none" onclick="runreport()">
											<div style='background:red;width:200px;height:50px;border-radius:4px;font-size:12pt;color:white;text-align:center'>
											แสดง<br>รายงาน
											</div>
											</a>
											<?php }?>
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
