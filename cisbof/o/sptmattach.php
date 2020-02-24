<?php 
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	
	$curdate = date('Y-m-d');
	$filepath = "../_fileuploads/at/";
	$sptm_nbr = decrypt($_REQUEST['sptmnumber'], $key);
	
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
		function sptmattachpost() {	
			var errorflag = false;
			var errortxt = "";
			var sptat_file = document.frm_add_sptmattach.sptat_file.value;
			if (sptat_file=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุเลือก file attachment";				
			}		
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";									
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {											
				document.frm_add_sptmattach.submit();							
			}
		}	
		function delsptmattach(sptat_id) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {				
				document.frmdelete.sptat_id.value = sptat_id;			
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
												<td>																																						
													<?php if ($can_editing && $sptm_step_code <= 30) { ?>
													<a href="#add_sptmattach" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
														<div class="btn btn-small btn-primary pull-right paddingleftandright10" style="margin-top:5px; margin-bottom:10px;">
															<i class="icon-plus icon-white"></i>														
															Attach File
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
											<legend style="align:left"><b>File List:</b></legend>
											<center>
												<table class="table table-striped table-bordered" width="100%">
													<tr>
														<th style="background:paleturquoise;text-align:center;">No</th>			
														<th style="background:paleturquoise;text-align:center;">File Name</th>
														<th style="background:paleturquoise;text-align:center;">File Description</th>
														<th style="background:paleturquoise;text-align:center;">Size</th>
														<th style="background:paleturquoise;text-align:center;">Date</th>												
														<th style="background:paleturquoise;text-align:center;">Action</th>
														<th style="background:paleturquoise;text-align:center;"> </th>
													</tr>                        
													<?php										
													$n = 0;																																										
													$sql = "SELECT * FROM sptat_attach where sptat_sptm_nbr = '$sptm_nbr' order by sptat_id";
													$result = sqlsrv_query( $conn, $sql );											
													while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {																																	
														$sptat_id = $r['sptat_id'];
														$sptat_sptm_nbr = $r['sptat_sptm_nbr'];																							
														$sptat_description = $r['sptat_description'];
														$sptat_o_file = $r['sptat_o_file'];
														$sptat_s_file = $r['sptat_s_file'];
														$sptat_size = $r['sptat_size'];
														$sptat_create_by = $r['sptat_create_by'];
														$sptat_create_date = $r['sptat_create_date'];	
														$sptat_fileext = strtoupper(explode(".",$sptat_s_file)[1]);
														if (inlist("JPG,PNG,BMP",$sptat_fileext)) { $showstyle = "rel='prettyPhoto'"; }
														else {$showstyle = "target='_blank'";}
														$n++;																			
														?>
														<tr>
															<td style="width:50px;text-align:center;background:white;"><?php echo $n;?></td>                                            
															<td style="width:350px;background:white;">
																<?php if ($can_editing) {?>
																	<a href="<?php echo $filepath.$sptat_s_file; ?>" <?php echo $showstyle?> title="<?php echo "<b>".$sptat_description; ?>">
																		<?php echo $sptat_o_file; ?>
																	</a>
																<?php } else {?>
																	<?php echo $sptat_o_file; ?>
																<?php }?>
															</td>
															<td style="width:350px;background:white;"><?php echo $sptat_description; ?></td>
															<td style="width:80px;background:white;"><?php echo $sptat_size; ?></td>
															<td style="width:80px;background:white;"><?php echo date_format($sptat_create_date,"d/m/Y"); ?></td>													
															<td style="background:white;padding-right:20px;">																																											
															<?php if ($can_editing && $sptat_description!= "*NPD*") {?>
																<button type="button" class="btn btn-mini btn-danger paddingleftandright10 pull-right" style="margin-right:10px;" onclick="delsptmattach('<?php echo $sptat_id;?>')">
																	<i class="icon-white icon-trash"></i>
																	<span>Del</span>
																</button>			
															<?php }?>
															</td>
															<td style="width:10px;background:white;"></td>																																			
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
<div id="add_sptmattach" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_add_sptmattach" autocomplete=OFF method="post" action="../serverside/sptmattachpost.php" enctype="multipart/form-data">		
			<input type="hidden" name="action" value="<?php echo md5('attach'.$user_login)?>">																		
			<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">															
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Add Attach File</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-c	ondensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>File Attach:</b></td>
						<td colspan=3>
							<input name="sptat_file" type="file">
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Description:</b></td>
						<td colspan=5>
							<input type="text" class="span5" style="width: 350px;" maxlength="100" name="sptat_description">							
						</td>
					</tr>
																																										
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="sptmattachpost()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
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
	<form name="frmdelete" method="post" action="../serverside/sptmattachpost.php">
		<input type="hidden" name="action" value="<?php echo md5('delete'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
		<input type="hidden" name="sptat_id">		
	</form>	
	</body>
</html>