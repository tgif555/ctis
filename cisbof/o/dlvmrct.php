<?php
	$takephoto = 'YES';
	if (isset($_COOKIE['spt_user_login']))  {
		include("../_incs/chksession.php");
		$takephoto = 'NO';
	}
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	clearstatcache();
	
	$activeid = decrypt($_REQUEST['activeid'], $key);
	$dlvm_nbr = decrypt($_REQUEST['d'], $key);

	$pg = $_REQUEST['pg'];
	$today = date("d/m/Y");
	$curdate = date('Y-m-d');
	$filepath = "../_fileuploads/rct/";

	//
	$sql = "SELECT * from dlvm_mstr INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr where dlvm_nbr = '$dlvm_nbr'";
	$result = sqlsrv_query($conn, $sql);	
	$r_dlvm = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($r_dlvm) {
		$dlvm_nbr = $r_dlvm['dlvm_nbr'];
		$dlvm_sptm_nbr = $r_dlvm['dlvm_sptm_nbr'];
		$dlvm_postdlv_date = $r_dlvm['dlvm_postdlv_date'];
		$dlvm_postdlv_by = $r_dlvm['dlvm_postdlv_by'];
		$dlvm_postdlv_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $dlvm_postdlv_by,$conn));
		$dlvm_postdlv_cmmt = html_quot($r_dlvm['dlvm_postdlv_cmmt']);
		$dlvm_printed = $r_dlvm['dlvm_printed'];
		$dlvm_print_cnt = $r_dlvm['dlvm_print_cnt'];
		
		$dlvm_transport_tspm_code = html_quot($r_dlvm['dlvm_transport_tspm_code']);
		$dlvm_transport_ref_no = html_quot($r_dlvm['dlvm_transport_ref_no']);
		$dlvm_transport_driver_name = html_quot($r_dlvm['dlvm_transport_driver_name']);
		$dlvm_transport_driver_tel = html_quot($r_dlvm['dlvm_transport_driver_tel']);
		$dlvm_transport_car_nbr = html_quot($r_dlvm['dlvm_transport_car_nbr']);
		$dlvm_transport_cmmt = html_quot($r_dlvm['dlvm_transport_cmmt']);
		$dlvm_receive_by = $r_dlvm['dlvm_receive_by'];
		$dlvm_receive_date = $r_dlvm['dlvm_receive_date'];
		$dlvm_receive_cmmt = html_quot($r_dlvm['dlvm_receive_cmmt']);
		$dlvm_receive_s_filename = $r_dlvm['dlvm_receive_s_filename'];
		$dlvm_receive_o_filename = $r_dlvm['dlvm_receive_o_filename'];
		$dlvm_receive_lat = html_quot($r_dlvm['dlvm_receive_lat']);
		$dlvm_receive_lon = html_quot($r_dlvm['dlvm_receive_lon']);
		$dlvm_dlvs_step_code = $r_dlvm['dlvm_dlvs_step_code'];
		$dlvm_step_name = findsqlval("dlvs_mstr","dlvs_step_name", "dlvs_step_code", $dlvm_dlvs_step_code,$conn);
		$dlvm_step_by = $r_dlvm['dlvm_step_by'];
		$dlvm_step_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $dlvm_step_by,$conn);
		$dlvm_step_date = $r_dlvm['dlvm_step_date'];
		$dlvm_step_cmmt = html_quot($r_dlvm['dlvm_step_cmmt']);
		$dlvm_curprocessor = $r_dlvm['dlvm_curprocessor'];
													
		$sptm_customer_number = $r_dlvm['sptm_customer_number'];
		$sptm_customer_dummy = $r_dlvm['sptm_customer_dummy'];
		$sptm_reason_code = $r_dlvm['sptm_reason_code'];
		$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);
		$sptm_expect_receipt_date = $r_dlvm['sptm_expect_receipt_date'];
		$sptm_delivery_mth = $r_dlvm['sptm_delivery_mth'];
		$sptm_delivery_mth_name = findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn);
		$sptm_delivery_mth_desc = html_quot($r_dlvm['sptm_delivery_mth_desc']);
		$sptm_req_by = $r_dlvm['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_by_sec = findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_date = $r_dlvm['sptm_req_date'];

		$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
		if ($sptm_customer_name != "") {
			$sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name;
		}
		else {
			$sptm_customer_name = $sptm_customer_dummy; 
		}
		if ($dlvm_dlvs_step_code != '20') {
			if ($user_login != "") {
				$path = "dlvmauthorize.php?msg=เอกสารหมายเลข $dlvm_nbr<br>ไม่อยู่ในสถานะรอรับสินค้าแล้วค่ะ";
			}
			else {
				$path = "dlvmmsg.php?msg=<font color=red>ต้องขออภัย<br>เอกสารหมายเลข $dlvm_nbr<br>** ไม่อยู่ในสถานะรอรับสินค้าแล้วค่ะ **</font>";
			}
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
		}
	}
	else {
		$path = "dlvmmsg.php?m=เอกสารหมายเลข $dlvm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	
	
	
	
	// $iscurrentprocessor = false;
	$can_receive = false;
	
	//Assign Authorize for CurrentProcessor
	if (inlist($dlvm_curprocessor,$user_login)) {
		//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
		//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
		$iscurrentprocessor = true;
	}
	else {
		//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
		//เช่นกรณี Role WH,DE
		$dlvm_curprocessor_role_access = "";
		$dlvm_curprocessor_role_array = explode(",",$user_role);																										
		for ($c=0;$c<count($dlvm_curprocessor_role_array);$c++) {
			if (inlist($dlvm_curprocessor,$dlvm_curprocessor_role_array[$c])) {
				$iscurrentprocessor = true;
				break;
			}
		}
	}
	if ($iscurrentprocessor && inlist('20',$dlvm_dlvs_step_code)) {
		$can_receive = true;
	}
	if (inlist($user_role,"CS") && inlist('90',$dlvm_dlvs_step_code)) {
		$can_cancel_receive = true;
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
		$(function () {
			$(document).ready(function () { 
				$('#securepopup').dialog({
					title: "ระบุรหัสผู้รับ*",
					width: 350,
					height: 200,
					modal: true
				});	
			});
		})
		
		$(document).ready(function () {     				                         				
			$("#dlvm_receive_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});			
		});		
	</script>
	
	<script language="javascript">
	function rctstepinform_close() {
		$('#rctstepinform').dialog(close);
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
	function helppopup(prgname,formname,opennerfield_code,opennerfield_name,txtsearch) {				
		//var help_program = prgname;
		//var help_search = txtsearch;		

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
		var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&opennerfield_code='+opennerfield_code+'&opennerfield_name='+opennerfield_name,'windowhelp',settings);		
		if (!myWindow.opener) myWindow.opener = self;
		
	}
	function printform(url) {				
		window.open(url);
		setTimeout(function(){ window.location.reload(true); }, 3000);									
	}	
	
	//Good Received
	function dlvm_receivepost(takephoto) {
		//alert(takephoto);
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		dlvm_receive_date = document.all.dlvm_receive_date.value;
		dlvm_file = document.all.dlvm_file.value;
		dlvm_receive_cmmt = document.all.dlvm_receive_cmmt.value;
		
		if (isDate(dlvm_receive_date,"dd/MM/yyyy")==false || dlvm_receive_date=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;					
			errortxt = errortxt + "กรุณาระบุ - [ วันที่รับสินค้า ] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";									
		}
		if (takephoto == 'YES') {
			if (dlvm_file=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณา แนบรูปถ่ายการรับสินค้า";					
			}
		}
		
		var dlvd_id = "";
		$('input[name^=dlvd_id_]').each(function() {
			if (dlvd_id != "") { dlvd_id = dlvd_id + ","; }
			dlvd_id = dlvd_id + this.value;
			
		});
		// var d1 = "D1907000040020001";
		// var d2 = "D1907000040020002";	
		// alert(getRadioValue(document.getElementsByName("radio_rec_"+d1)));
		// alert(getRadioValue(document.getElementsByName("radio_rec_"+d2)));
		var v;
		var dlvd_receive_all = true;
		var dlvd_receive = "";
		var dlvd_id_array = dlvd_id.split(",");
		for (i = 0; i < dlvd_id_array.length; i++) {
			v = getRadioValue(document.getElementsByName("radio_rec_"+dlvd_id_array[i]));
			if (v == 'N') { dlvd_receive_all = false;}
			if (dlvd_receive != "") { dlvd_receive = dlvd_receive + ","; }
			dlvd_receive = dlvd_receive + v;
		}
		if (!dlvd_receive_all) {
			if (dlvm_receive_cmmt == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ หมายเหตุของรายการที่ไม่รับ";	
			}
		}
		
		if (errorflag) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
			
		}
		else {		
			document.frm_dlvm_receive.dlvd_receive_id.value = dlvd_id;
			document.frm_dlvm_receive.dlvd_receive_result.value = dlvd_receive;
			
			//$("#btnreceive").prop("disabled", true);
			//$('#result').html("<center><img id='progress1' src='../_images/loading7.gif'></center>");
			var result_text="";
			
			$.ajaxSetup({
				cache: false,
				contentType: false,
				processData: false
			}); 
			var formObj = $('#frm_dlvm_receive')[0];
			var formData = new FormData(formObj);
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: "POST",
				url: '../serverside/dlvmreceivepost.php',
				data: formData,
				timeout: 600000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(data) {
					//console.log(data);
					var json = $.parseJSON(data);
					if (json.res == '0') {
						//clearloadresult();
						showmsg(json.err);
					}
					else {
						//clearloadresult();
						result_text += "<span style='color:green'><h3>[ทำรายการสำเร็จค่ะ]</h3>";
						if (json.err!="") {
							result_text +="\n"+json.err;
						}
						if (json.err!="") {
							bootbox.alert(result_text, function(){
								if (json.rt == 'web-login') {
									$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
								}
								else {
									$(location).attr('href', 'dlvmmsg.php?msg=<font color=green>** ทำการบันทึกรับสำเร็จค่ะ **</font>');
								}
							});
						}
						else {
							if (json.rt == 'web-login') {
								$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
							}
							else {
								$(location).attr('href', 'dlvmmsg.php?msg=<font color=green>เอกสารหมายเลข'+json.nbr+'<br>** ทำการบันทึกรับสำเร็จค่ะ **</font>');
							}
						}
						
						//$("#btnreceive").prop("disabled", false);
					}
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
	}
	
	function chk_securecode() {
		var receivercode = document.frm_securecheck.receivercode.value;
		var dlvm_nbr = document.frm_securecheck.dlvm_nbr.value;

		//ตรวจสอบว่า รหัสการรับสินค้าที่ใส่มามีสิทธิ์รับของรายการนี้หรือไม่
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {								
			if (xhttp.readyState == 4 && xhttp.status == 200) {								
				if (xhttp.responseText == 0) {							
					//$('#securepopup').dialog('close');
					window.location.href = "dlvmmsg.php?msg=<font color=red>** คุณไม่มีสิทธิ์ในการรับสินค้าจากเอกสารฉบับนี้ค่ะ **</font>";
				}
				else {
					//มีสิทธ์ในการรับสินค้า
					$('#securepopup').dialog('close');
					//open popup receive step inform
					
					$('#rctstepinform').dialog({
						title: "ขั้นตอนการรับสินค้า",
						width: 350,
						height: 140,
						modal: true,
					});	
					
					setTimeout(function() {
						$('#rctstepinform').dialog('close')
					}, 5000);
						
					//เอาค่า securecode ไปใส่ใน form ด้านหลัง
					document.frm_dlvm_receive.dlvm_receiver_code.value = receivercode;
				}
			}
		}
		xhttp.open("POST", "../_chk/chkreceivercode.php",false);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
		xhttp.setRequestHeader("Pragma", "no-cache");
		xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
		xhttp.send("dlvm_nbr="+dlvm_nbr+"&receivercode="+receivercode);

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
	<?php if ($_COOKIE['spt_user_login'] == "")  {?>
	<center>
	<div id="securepopup" style="display:none;">
		<form name="frm_securecheck" autocomplete="OFF" method="post">
			<input type="hidden" name="dlvm_nbr" value="<?php echo $dlvm_nbr?>">
			<table width=100% bgcolor="#A1A2A0" style="border-radius:4px">
				<tr><td align=center style="color:red;font-size:12pt"><b>** ระบุรหัสผู้รับสินค้า **</b></td></tr>
				<tr><td align=center><input name="receivercode" style='font-size:12pt;text-align:center' type="text"></td></tr>
				<tr><td height="10px"></td></tr>
				<tr>
					<td align=center>
						<input type='button' name="btnclose" style='width:70px' class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" value='OK' onclick="chk_securecode()">
						<input type='button' name="btncancel" style='width:70px' class="btn btn-danger fileinput-button paddingleftandright10 margintop20 marginleft20" value='Cancel' onclick="window.location.href='dlvmmsg.php?msg=<font color=red>** คุณไม่ได้ทำรายการ **</font>'">
					</td>
				</tr>
				<tr><td height="10px"></td></tr>
			</table>
		</form>
	</div>
	</center>
	<center>
	<div id="rctstepinform" style="display:none;">
		<center>
		<p><span style='color:blue'><b>** ขั้นตอนการรับสินค้า **</b><br>1. กดปุ่ม Chose File (เพื่อถ่ายรูป)<br>2. กดบันทึกรับสินค้า <br><font color='red'>หน้าต่างนี้จะปิดใน 5 วินาทีค่ะ</font></span></p>
		</center>			
	</div>
	</center>
	<?php }?>
	
	<div id="result"></div>
	<?php if ($_COOKIE['spt_user_login'] != "")  {?>
		<div style="background-color:white"><?php include("../menu.php"); ?></div>
	<?php }?>
	<div style=''>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr bgcolor="blue"><td height="20px"></td></tr>
			<tr bgcolor="blue">
				<td style="text-align:center;color:white"><h3>บันทึกรับสินค้า</h3></td>
			</tr>	
			<tr bgcolor="blue"><td height="10px"></td></tr>
			<tr><td height="10px"></td></tr>
			<tr>
				<td>						
					<fieldset style="border-radius:4px;width:98%">					
						<legend class='btn btn-success' style="text-align:left;color:white;border-radius:4px;font-size:12pt"><b>&nbsp;&nbsp;ข้อมูลลูกค้า:&nbsp;&nbsp;</b></legend>
						<center>
							<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
								<tr>
									<td><b>ใบส่งของ:</b></td>
									<td style='background:white'><?php echo $dlvm_nbr?></td>
								</tr>
								<tr>
									<td><b>ชื่อผู้ขอเบิก:</b></td>
									<td style='background:white'><b><?php echo $sptm_req_by_name;?></b></td>
								</tr>													
								<tr>
									<td><b>รหัสลูกค้า:</b></td>
									<td style='background:white'><b><?php echo $sptm_customer_name?></b></td>
								</tr>
								<tr>
									<td><b>วัตถุประสงค์เพื่อ:</b></td>
									<td style='background:white'><b><?php echo $sptm_reason_name?></b></td>
								</tr>									
							</table>
						</center>
					</fieldset>
				</td>
			</tr>
			<tr><td height="2px"></td></tr>
			<tr>
				<td>
					<fieldset style="background-color:white;border-radius:4px;width:98%">
						<legend class='btn btn-danger' style="text-align:left;color:white;border-radius:4px;font-size:12pt"><b>&nbsp;&nbsp;รายการสินค้า:&nbsp;&nbsp;</b></legend>
						<center>
							<?php include("dlvmrct_detail.php");?>
						</center>
					</fieldset>
				</td>
			</tr>	
			<tr>
				<td>
					<fieldset style="background-color:white;border-radius:4px;width:98%">
					<form id="frm_dlvm_receive" name="frm_dlvm_receive"  autocomplete=OFF method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" value="dlvm_receive">
						<input type="hidden" name="dlvm_nbr" value="<?php echo $dlvm_nbr?>">
						<input type="hidden" name="sptm_nbr" value="<?php echo $dlvm_sptm_nbr?>">
						<input type="hidden" name="dlvm_receiver_code">
						<input type="hidden" name="dlvd_receive_id">
						<input type="hidden" name="dlvd_receive_result">
						<table width=100% cellpadding=2 cellspacing=2>
							<tr><td style="height:5px"></td></tr>
							<tr>
								<td style="background:orange;width:80px;text-align:right"><b>วันที่รับสินค้า:</b></td>
								<td><input name="dlvm_receive_date" id="dlvm_receive_date" value="<?php echo $today?>" style="width:90px;color:red;font-weight: bold;text-align:center" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="วว/ดด/ปปปป"></td>
							</tr>
							<tr><td style="height:5px"></td></tr>
							<tr>
								<td style="background:orange;text-align:right"><b>รูปภาพ:</b></td>
								<td><input type="file"  name="dlvm_file"></td>
							</tr>
							<tr><td style="height:5px"></td></tr>
							<tr><td style="background:red;color:white;text-align:center"><b>หมายเหตุ:</b></td></tr>
							<tr>
								<td colspan=2><textarea rows=2 name="dlvm_receive_cmmt" class="form-control" style="min-width: 80%"></textarea></td>
							</tr>
						</table>
					</form>
					</fieldset>
				</td>
			</tr>
		</table>
	</div> 
	<div class="modal-footer" style='text-align:center'>
		<?php if ($user_login != "") {?>
		<a href="dlvdmnt.php?dlvmnumber=<?php echo encrypt($dlvm_nbr, $key);?>"  role="button" style="color:white; text-decoration:none;" data-toggle="modal">
			<div class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20">
				<i class="icon-check icon-white"></i>
				<span>ย้อนกลับ</span>												
			</div>
		</a>
		<?php }?>
		<button type="submit" id="btnreceive" class="btn btn-success fileinput-button" onclick='dlvm_receivepost("<?php echo $takephoto;?>")'>
			<i class="icon-check icon-white"></i>
			<span>บันทึกรับสินค้า</span>
		</button>	
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
</body>
</html>
