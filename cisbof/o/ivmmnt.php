<?php 
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	clearstatcache();
	
	$activeid = decrypt($_REQUEST['activeid'], $key);
	$ivm_nbr = decrypt($_REQUEST['ivmnumber'], $key);
	
	$pg = $_REQUEST['pg'];
	
	$curdate = date('Y-m-d');
	$filepath = "../_fileuploads/";

	//TEMP VARIABLE
	$can_edit = true;
	//
	$sql = "SELECT * from ivm_mstr" .
		" INNER JOIN wpm_mstr ON wpm_nbr = ivm_wpm_nbr" .
		" WHERE ivm_nbr = '$ivm_nbr'";

	$result = sqlsrv_query($conn, $sql);	
	$r_ivm = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($r_ivm) {
		$ivm_nbr = $r_ivm['ivm_nbr'];
		$ivm_date = $r_ivm['ivm_date'];
		$ivm_group_type  = $r_ivm['ivm_group_type'];
		$ivm_wpm_nbr = $r_ivm['ivm_wpm_nbr'];
		$ivm_wpm_date = $r_ivm['wpm_date'];
		$ivm_customer_number = $r_ivm['ivm_customer_number'];
		$ivm_customer_dummy = $r_ivm['ivm_customer_dummy'];
		$ivm_customer_type = $r_ivm['ivm_customer_type'];
		$ivm_customer_amphur = $r_ivm['ivm_customer_amphur'];
		$ivm_customer_province = $r_ivm['ivm_customer_province'];
		$ivm_transport_car_nbr = $r_ivm['ivm_transport_car_nbr'];
		$ivm_transport_tspm_code = $r_ivm['ivm_transport_tspm_code'];
		$ivm_transport_tspm_other = $r_ivm['ivm_transport_tspm_other'];
		if ($ivm_transport_tspm_code=="OTHER") {
			$ivm_transport_tspm_name = $ivm_transport_tspm_other;
		}
		else {
			$ivm_transport_tspm_name = findsqlval("tspm_mstr","tspm_name","tspm_code",$ivm_transport_tspm_code,$conn);
		}
		$ivm_transport_ref_no = $r_ivm['ivm_transport_ref_no'];
		$ivm_transport_driver_name = $r_ivm['ivm_transport_driver_name'];
		$ivm_transport_driver_tel = $r_ivm['ivm_transport_driver_tel'];
		$ivm_transport_cmmt = $r_ivm['ivm_transport_cmmt'];
		$ivm_printed = $r_ivm['ivm_printed'];
		$ivm_print_by = $r_ivm['ivm_print_by'];
		$ivm_print_date = $r_ivm['ivm_print_date'];
		$ivm_print_cnt = $r_ivm['ivm_print_cnt'];
		$ivm_status_code = $r_ivm['ivm_status_code'];
		$ivm_create_by = $r_ivm['ivm_create_by'];
		$ivm_create_date = $r_ivm['ivm_create_date'];
														
		$ivm_print_by_name = findsqlval("emp_mstr","emp_th_firstname+ ' '+ emp_th_lastname","emp_user_id",$ivm_print_by,$conn);
														
		if($ivm_customer_number != "DUMMY") {
			$ivm_customer_name = findsqlval("customer","customer_name1", "customer_number", $ivm_customer_number,$conn);
			if ($ivm_customer_name != "") {
				$ivm_customer_name = '['.$ivm_customer_number.'] ' . $ivm_customer_name;
			}
		}
		else {
			$ivm_customer_name = '<font color=red>[DUMMY]</font> ' .$ivm_customer_dummy;
		}			
	}
	else {
		$path = "sptmauthorize.php?msg=เอกสารหมายเลข $ivm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}
	//ดึงข้อมูลวิธีการจัดส่ง
	if(inlist("B,C",$ivm_group_type)) {
		$sql = "SELECT TOP 1 * from ivd_det WHERE ivd_ivm_nbr = '$ivm_nbr'";
		$result = sqlsrv_query($conn, $sql);	
		$r_ivd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if ($r_ivd) {
			$ivd_sptm_nbr = $r_ivd['ivd_sptm_nbr'];
			
			$sptm_delivery_mth = findsqlval("sptm_mstr","sptm_delivery_mth","sptm_nbr",$ivd_sptm_nbr,$conn);
			$sptm_delivery_mth_name = findsqlval("delivery_mth","delivery_name","delivery_code",$sptm_delivery_mth,$conn);
			if ($ivm_group_type == "C") {
				$sptm_req_by = findsqlval("sptm_mstr","sptm_req_by","sptm_nbr",$ivd_sptm_nbr,$conn);
				$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$sptm_req_by,$conn);
			}
		}
	}
	//
	
	$can_receive = false;
	$can_print_ivm = false;
	$can_cancel = false;
	
	if (inlist($user_role,"CS") && inlist('10,20',$ivm_status_code)) {
		$can_print_ivm = true;
		$can_cancel = true;
	}
	if (inlist($user_role,"CS") && inlist('20',$ivm_status_code)) {
		$can_receive = true;
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
			$("#ivm_receive_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
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
	
	function ivm_cancelpost() {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		ivm_cancel_cmmt = document.frm_ivm_cancel.ivm_cancel_cmmt.value;
		
		if (ivm_cancel_cmmt=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}	
			errorflag = true;					
			errortxt = errortxt + "กรุณาระบุหมายเหตุของการยกเลิก";					
		}
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
			
		}
		else {
			var result_text="";
			$.ajax({
				beforeSend: function () {
					$('#btn_save_cancel').attr('disabled','disabled');
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/ivmcancelpost.php',
				data: $('#frm_ivm_cancel').serialize(),
				timeout: 50000,
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
						if (json.err!="") {
							bootbox.alert(result_text, function(){ 
								$(location).attr('href', 'ivmall.php?ivmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'ivmall.php?ivmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
					
				}
			});
		}
	}	
		
	function printform(url) {				
		window.open(url);
		setTimeout(function(){ window.location.replace("ivmall.php"); }, 3000);									
	}	
	
	function loadresult() {
		$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
	}
	function clearloadresult() {
		$('#result').html("");
	}
	function showmsg(msg) {
		$("#msgbody").html(msg);
		$("#myModal").modal("show");
	}
	</script>
	<style>
	.modal {		
		z-index: 1050;
		overflow: auto;
	}
	</style>
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
									<tr bgcolor="#DAF7A6">
										<td style="padding-left:5px;"><h3>ข้อมูลใบส่งของ</h3></td>
									</tr>	
									<tr><td height="2px"></td><tr>
									<tr>
										<td>
											<fieldset style="border-radius:4px;width:98%">
											
											<legend style="background-color:red;text-align:left;color:white;border-radius:4px;"><b>&nbsp;&nbsp;ข้อมูลลูกค้า:&nbsp;&nbsp;</b></legend>
											<center>
											<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบส่งสินค้า:</b></td>
													<td width=25% class="text_s_disable"><h3><?php echo $ivm_nbr?></h3></td>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วิธีการจัดกลุ่ม:</b></td>
													<td class="text_s_disable"><h3><?php echo $ivm_group_type;?></h3></td>
												</tr>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเตรียม:</b></td>
													<td width=25% class="text_s_disable"><?php echo $ivm_wpm_nbr?></td>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขึ้นสินค้า:</b></td>
													<td class="text_s_disable"><?php echo dmytx($ivm_wpm_date)?></td>
												</tr>
												<?php //if ($ivm_group_type == "A") {?>
												<?php if (inlist("A,D,E,F",$ivm_group_type)) {?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
													<td class="text_s_disable"><b><?php echo $ivm_customer_name;?></b></td>
															
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b></b></td>
													<td class="text_s_disable"><b></b></td>
												</tr>				
												<?php }?>
												<?php if (inlist("B,C",$ivm_group_type)) {?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วิธีการจัดส่ง:</b></td>
													<td class="text_s_disable"><b><?php echo $sptm_delivery_mth_name;?></b></td>
													<?php if ($ivm_group_type == "B") {?>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b></b></td>
														<td class="text_s_disable"><b></b></td>
													<?php }?>
													<?php if ($ivm_group_type == "C") {?>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
														<td class="text_s_disable"><b><?php echo $sptm_req_by_name;?></b></td>
													<?php }?>
												</tr>				
												<?php }?>
												
												<?php //if ($ivm_group_type == "A") {?>
												<?php if (inlist("A,D,E,F",$ivm_group_type)) {?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>อำเภอ</b></td>
													<td class="text_s_disable"><b><?php echo $ivm_customer_amphur?></b></td>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>จังหวัด:</b></td>
													<td class="text_s_disable"><b><?php echo $ivm_customer_province?></b></td>
												</tr>
												<?php }?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>บริษัทขนส่ง:</b></td>
													<td class="text_s_disable"><b><?php echo $ivm_transport_tspm_name?></b></td>
									
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ทะเบียนรถ (เบอร์ติดต่อ):</b></td>
													<td class="text_s_disable"><b><?php echo $ivm_transport_car_nbr." (".$ivm_transport_driver_tel.")"?></b></td>
												</tr>
																	
												<tr>
													<td></td>
													<td>
														<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='ivmall.php?activeid=<?php echo encrypt($ivm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
															<i class="icon-white icon-hand-left"></i>
															<span>Black</span>													
														</div>
														<?php if($can_print_ivm) {?>
															<?php //if($ivm_group_type == "A") {?>
															<?php if (inlist("A,D,E,F",$ivm_group_type)) {?>
															<div class="btn btn-small btn-info" style="margin-top:5px; margin-bottom:10px; width: 100px;" onclick="printform('ivmform01_a.php?ivmnumber=<?php echo encrypt($ivm_nbr, $key);?>')">
															<?php }?>
															<?php if($ivm_group_type == "B") {?>
															<div class="btn btn-small btn-info" style="margin-top:5px; margin-bottom:10px; width: 100px;" onclick="printform('ivmform01_b.php?ivmnumber=<?php echo encrypt($ivm_nbr, $key);?>')">
															<?php }?>
															<?php if($ivm_group_type == "C") {?>
															<div class="btn btn-small btn-info" style="margin-top:5px; margin-bottom:10px; width: 100px;" onclick="printform('ivmform01_c.php?ivmnumber=<?php echo encrypt($ivm_nbr, $key);?>')">
															<?php }?>
																<i class="icon-white icon-print"></i>														
																<?php if ($ivm_print_cnt == 0) {?>
																	<span>พิมพ์ใบส่งของ</span>		
																<?php } else { ?>
																	<span>Re-Print</span>
																<?php }?>														
															</div>		
														<?php }?>													
													</td>
													<td></td>												
													<td>
														<?php if ($can_cancel) { ?>
															<a href="#ivm_cancel" data-toggle="modal">
																<div class="btn btn-small btn-danger" style="background:red;color:white;margin-top:5px; margin-bottom:10px; width: 90px;">
																	<i class="icon-white icon-remove"></i>
																	<span>ยกเลิกใบส่งของ</span>													
																</div>
															</a>
															
														<?php } ?>
														<?php if ($can_receive && $ivm_status_code == "20") { ?>
															<a href="ivmrct.php?d=<?php echo encrypt($ivm_nbr, $key);?>">
																<div class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 70px;">
																	<span>บันทึกรับสินค้า</span>													
																</div>
															</a>
															
														<?php } ?>
													</td>
												</tr>
											</table>
											</center>
											</fieldset>
										</td>
									</tr>
									<tr><td height="2px"></td></tr>
									<tr>
										<td width=50%>
											<div class="container" style="width:100%">
												<fieldset style="background-color:white;border-radius:4px;width:98%">
													<legend style="background-color:red;text-align:left;color:white;border-radius:4px;"><b>&nbsp;&nbsp;รายการ Delivery:&nbsp;&nbsp;</b></legend>
													<center>
														<?php include("ivmmnt_detail.php");?>
													</center>
												</fieldset>
											</div>
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
	<div id="ivm_cancel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form id="frm_ivm_cancel" autocomplete="OFF" name="frm_ivm_cancel" method="post">
			<input type="hidden" name="action" value="<?php echo md5("cancel_ivm".$user_login)?>">
			<input type="hidden" name="ivm_nbr" value="<?php echo $ivm_nbr?>">
			<input type="hidden" name="ivm_wpm_nbr" value="<?php echo $ivm_wpm_nbr?>">
			<input type="hidden" name="pg" value="<?php echo $pg?>">
			<div class="modal-header" style="background-color:red">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> :: <span style='color:white'>ยกเลิกใบส่งของ<span></h3>
			</div>
			<div class="">
				<table border=0 class="table table-condensed table-responsive">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr><td><b>Comment::</b></td></td>
					<tr>
						<td>
							<textarea name="ivm_cancel_cmmt" rows=3 class="inputtext_s form-control" style="min-width: 80%" maxlength="255"></textarea>
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button id="btn_save_cancel" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="ivm_cancelpost()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>	
	</div>
</body>
</html>
