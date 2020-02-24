<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
$activeid = mssql_escape($_REQUEST['activeid']);
$pg = mssql_escape($_REQUEST['pg']);
$curdate = date('d/m/Y');
clearstatcache();
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
					data: $('#frm_sptmadd').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {	
						//console.log(result);
						
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
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
		function helppopup_customer(prgname,formname,opennerfield_code,opennerfield_code2,txtsearch) {
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
		function radiohighlight(radioObj) {
			var radioLength = radioObj.length;				
			for(var i=0; i<radioLength; i++){
				if(radioObj[i].checked) { 
					radioObj[i].style.backgroundColor='red'; 
					if(i==0) {
						if(radioObj[i].name == 'sptm_customer_type') {
							frm_sptmadd.sptm_customer_number.disabled=false;
							frm_sptmadd.sptm_customer_help.style.visibility='visible';;
							frm_sptmadd.sptm_customer_dummy.value='';
							frm_sptmadd.sptm_customer_dummy.disabled=true;
						}
					}else if(i==1) {
						if(radioObj[i].name == 'sptm_customer_type') {
							frm_sptmadd.sptm_customer_dummy.disabled=false;
							frm_sptmadd.sptm_customer_number.value='';
							frm_sptmadd.sptm_customer_name.value='';
							frm_sptmadd.sptm_reason_code.value='';
							frm_sptmadd.sptm_customer_amphur.value='';
							frm_sptmadd.sptm_customer_province.value='';
							frm_sptmadd.sptm_expect_receiver_name.value='';
							frm_sptmadd.sptm_expect_receiver_tel.value='';
							frm_sptmadd.sptm_customer_number.disabled=true;
							frm_sptmadd.sptm_customer_help.style.visibility='hidden';
							//ResetRadio(frm_sptmadd.sptm_delivery_mth);
						}
					}
				}
				else {
					radioObj[i].style.backgroundColor="";
				}
			}		
		}
		function setdefaultampv(v) {
			if (v == 'M002' || v == 'M005') {
				frm_sptmadd.sptm_customer_amphur.value='บางซื่อ';
				frm_sptmadd.sptm_customer_province.value='กรุงเทพมหานคร';
			}
			else {
				var customer_number = frm_sptmadd.sptm_customer_number.value;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {								
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						var res = xhttp.responseText.split("|")	;
						frm_sptmadd.sptm_customer_amphur.value = res[0];
						frm_sptmadd.sptm_customer_province.value = res[1];								
					}
				}
				xhttp.open("POST", "../_chk/getampvbycust.php",false);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
				xhttp.setRequestHeader("Pragma", "no-cache");
				xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
				xhttp.send("customer_number="+customer_number);
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
								<span style='font-size:11pt'><b>@สร้างใบเบิกใหม่</b></span>
							</td>
						</tr>
						<tr>
							<td>
								<FORM id="frm_sptmadd" name="frm_sptmadd" autocomplete=OFF method="post">
									<input type=hidden name="action" value="<?php echo md5('add'.$user_login)?>">
									<input type=hidden name="sptm_npd">
									<input type=hidden name="pg" value="<?php echo $pg?>">
									<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเบิก:</b></td>
														<td width=25%><h3>SYYMM@@@@@</h3></td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอเบิก:</b></td>
														<td>
															<input type="text" name="sptm_req_date" class="inputtext_s" style="width: 120px;" readonly value="<?php echo $curdate;?>">
														</td>
													</tr>	
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
														<td class='txtdata'><b><?php echo $user_fullname;?></b></td>
																
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>สังกัดผู้ขอเบิก:</b></td>
														<td class='txtdata'><b><?php echo $user_org_name;?></b></td>
													</tr>													
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;">
															<b>รหัสลูกค้า:</b>&nbsp;<input type="radio" class="inputtext_s" name="sptm_customer_type" id="customer_number" value="customer_number" onclick="radiohighlight(sptm_customer_type)">&nbsp;
														</td>
														<td>
															<input type="text" name="sptm_customer_number" id="sptm_customer_number" class="inputtext_s" style="width: 150px" maxlength="20">														
															<button type="button" name="sptm_customer_help" id="sptm_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
																OnClick="helppopup_customer('../_help/getcustampv.php','','','',document.all.sptm_customer_number.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>															
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
														<td><input type="text"  name="sptm_customer_name" class="inputtext_s" style="width: 400px;" readonly></td>
													</tr>	
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อลูกค้าอื่นๆ:</b>&nbsp;<input type="radio" name="sptm_customer_type" id="customer_dummy" value="customer_dummy" class="inputtext_s" onclick="radiohighlight(sptm_customer_type)">&nbsp;</td>
														<td>
															<input type="text"  name="sptm_customer_dummy" id="sptm_customer_dummy" class="inputtext_s" style="width: 250px;" placeholder="* สำหรับลูกค้าที่ยังไม่ได้เปิดรหัสกับบริษัทฯ" maxlength="255">&nbsp;
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
																	<option  value="<?php echo $r_reason_list['reason_code'];?>" title="<?php echo $r_reason_list['reason_name'];?>">[<?php echo $r_reason_list['reason_code'];?>]&nbsp;<?php echo html_quot($r_reason_list['reason_name']);?></option> 
																<?php } ?>
															</select>
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>อำเภอ:</b></td>
														<td>
															<input type="text" name="sptm_customer_amphur" id="sptm_customer_amphur" class="inputtext_s" style='width:210px' maxlength=255>
															<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
																OnClick="helppopup('../_help/getamphur.php','frm_sptmadd','sptm_customer_amphur','sptm_customer_province',document.all.sptm_customer_amphur.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>จังหวัด:</b></td>
														<td>
															<input type="text" name="sptm_customer_province" id="sptm_customer_province" class="inputtext_s"  style='width:200px' maxlength=255>
															<button type="button" name="sptm_province_help" id="sptm_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
																OnClick="helppopup('../_help/getprovince.php','frm_sptmadd','sptm_customer_province','',document.all.sptm_customer_province.value)" data-dismiss="modal">
																<span class="icon icon-search" aria-hidden="true"></span>
															</button>
														</td>
													</tr>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอรับสินค้า:</b></td>
														<td>
															<input type="text" name="sptm_expect_receipt_date" id="sptm_expect_receipt_date" class="inputtext_s" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="วว/ดด/ปปปป" value="<?php echo $curdate;?>">													
														</td>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้รับสินค้า:</b></td>
														<td>
															<input type="text" name="sptm_expect_receiver_name" id="sptm_expect_receiver_name" class="inputtext_s" style="width:240px" maxlength=255>
														</td>
													</tr>
													
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วิธีการจัดส่ง:</b></td>
														<td>
															<select name="sptm_delivery_mth" class="inputtext_s" style="width: 260px;" onchange="setdefaultampv(this.value)">
																<option value="">-เลือก-</option>
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
														<td style="text-align:right; width: 150px; vertical-align: top;"><b>เบอร์โทรผู้รับสินค้า:</b></td>
														<td style="vertical-align: top;">
															<input type="text" name="sptm_expect_receiver_tel" id="sptm_expect_receiver_tel" class="inputtext_s" style="width:240px" maxlength=60>
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
																	<option value="<?php echo $r_com_list['com_code'];?>">
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
																	<option value="<?php echo $r_brand_list['brand_code'];?>">
																		<?php echo html_quot($r_brand_list['brand_name']);?>
																	</option> 
																<?php } ?>
															</select>															
														</td>
														<!--td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Set No:</b></td>
														<td>
															<input type="text" name="sptm_npd_setno" class="inputtext_s" style="width:100px" maxlength=30>
														</td-->
													</tr>
													<?php }?>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเหตุการจัดส่ง:</b></td>
														<td>
															<textarea name="sptm_delivery_mth_desc" rows=4 style='width:250px;color:blue' class="inputtext_s" maxlength=255 placeholder="* ระบุหมายเหตุการจัดส่ง"></textarea>
														</td>								
														<td></td>
													</tr>
													<tr>
														<td></td>
														<td>														
															<div id="btnsave" class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 50px;">
																<i class="icon-white icon-ok"></i>
																<span>SAVE</span>														
															</div>
															<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 60px;" onclick="window.location.href='sptmall.php?pg=<?php echo $pg;?>'">
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
