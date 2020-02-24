<?php 
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	clearstatcache();
	
	$activeid = decrypt($_REQUEST['activeid'], $key);
	$dlvm_nbr = decrypt($_REQUEST['dlvmnumber'], $key);
	
	$pg = $_REQUEST['pg'];
	
	$curdate = date('Y-m-d');
	$filepath = "../_fileuploads/";

	//TEMP VARIABLE
	$can_edit = true;
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
		$dlvm_packing_weight = html_quot($r_dlvm['dlvm_packing_weight']);
		$dlvm_packing_location = html_quot($r_dlvm['dlvm_packing_location']);
		if ($dlvm_packing_location!="") {
			$dlvm_packing_location = "<span style='color:red;font-size:12pt;'><b>สถานที่วาง: ".$dlvm_packing_location."</b></span>";
		}
		$dlvm_printed = $r_dlvm['dlvm_printed'];
		$dlvm_print_cnt = $r_dlvm['dlvm_print_cnt'];
		$dlvm_zone_printed = $r_dlvm['dlvm_zone_printed'];
		
		$dlvm_transport_tspm_code = $r_dlvm['dlvm_transport_tspm_code'];
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
		$sptm_customer_amphur = html_quot($r_dlvm['sptm_customer_amphur']);
		$sptm_customer_province = html_quot($r_dlvm['sptm_customer_province']);
		$sptm_expect_receiver_name = html_quot($r_dlvm['sptm_expect_receiver_name']);
		$sptm_expect_receiver_tel = html_quot($r_dlvm['sptm_expect_receiver_tel']);
		
		$sptm_reason_code = $r_dlvm['sptm_reason_code'];
		$sptm_reason_name = html_quot(findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn));
		$sptm_expect_receipt_date = $r_dlvm['sptm_expect_receipt_date'];
		$sptm_delivery_mth = $r_dlvm['sptm_delivery_mth'];
		$sptm_delivery_mth_name = html_quot(findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn));
		$sptm_delivery_mth_desc = html_quot($r_dlvm['sptm_delivery_mth_desc']);
		$sptm_req_by = $r_dlvm['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_by_sec = html_quot(findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn));
		$sptm_req_date = $r_dlvm['sptm_req_date'];
		$sptm_tel_contact = findsqlval("emp_mstr","emp_tel_contact","emp_user_id",$sptm_req_by,$conn);
		if ($sptm_tel_contact != "") {
			$sptm_tel_contact = " <span style='color:red'>(".$sptm_tel_contact.")</span>";
		}
		//NPD
		$sptm_npd = $r_dlvm['sptm_npd'];
		$sptm_npd_com = $r_dlvm['sptm_npd_com'];
		$sptm_npd_type = $r_dlvm['sptm_npd_type'];
		$sptm_npd_brand = $r_dlvm['sptm_npd_brand'];
		$sptm_npd_setno = $r_dlvm['sptm_npd_setno'];
		$sptm_npd_setno_name = "";
		$sptm_npd_brand_name = "";
		$sptm_npd_customer_total = $r_dlvm['sptm_npd_customer_total'];
		if ($sptm_npd) {
			$sptm_npd_text = " [*NPD*]";
			$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$sptm_npd_brand,$conn);
			$sptm_npd_setno_name = substr($sptm_npd_setno,strpos($sptm_npd_setno,'@')+1,strlen($sptm_npd_setno));
		}
		//
		$sptm_copy_refer = $r_dlvm['sptm_copy_refer'];
		$sptm_copy_refer_text = "";
		if ($sptm_copy_refer != "") {
			$sptm_copy_refer_text = "<span style='color:red;font-size:8pt;'>[Ref: $sptm_copy_refer] $sptm_npd_text</span>";
		}
		
		
		if ($dlvm_receive_by!="") {
			$dlvm_receive_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$dlvm_receive_by,$conn);
			$dlvm_receive_date = dmytx($dlvm_receive_date);
		}
		

		$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
		if ($sptm_customer_number != "DUMMY") {
			$sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name;
		}
		else {
			$sptm_customer_name = $sptm_customer_dummy;
		}			
	}
	else {
		$path = "sptmauthorize.php?msg=เอกสารหมายเลข $dlvm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ1"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	

	$iscurrentprocessor = false;
	$can_shipment = false;
	$can_receive = false;
	
	//Assign Authorize for CurrentProcessor
	if (inlist($dlvm_curprocessor,$user_login)) {
		//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
		//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
		$iscurrentprocessor = true;
	}
	else {
		//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
		//เช่นกรณี Role WH,CS
		$dlvm_curprocessor_role_access = "";
		$dlvm_curprocessor_role_array = explode(",",$user_role);																										
		for ($c=0;$c<count($dlvm_curprocessor_role_array);$c++) {
			if (inlist($dlvm_curprocessor,$dlvm_curprocessor_role_array[$c])) {
				$iscurrentprocessor = true;
				break;
			}
		}
	}
	/** ยกเลิก logic นี้ให้หน้า PC เราจะให้คนสามารถทำได้ตาม Role เท่านั้น
	if ($iscurrentprocessor && inlist('10',$dlvm_dlvs_step_code)) {
		$can_shipment = true;
	}
	if ($iscurrentprocessor && inlist('20',$dlvm_dlvs_step_code)) {
		$can_receive = true;
	}
	*/
	if (inlist($user_role,"CS") && inlist('10',$dlvm_dlvs_step_code)) {
		$can_shipment = true;
	}
	if (inlist($user_role,"CS") && inlist('20',$dlvm_dlvs_step_code)) {
		$can_receive = true;
	}
	if (inlist($user_role,"CS") && inlist('10',$dlvm_dlvs_step_code)) {
		$can_cancel_delivery = true;
	}
	if (inlist($user_role,"CS") && inlist('20',$dlvm_dlvs_step_code)) {
		$can_cancel_shipment = true;
	}
	if (inlist($user_role,"CS_ADMIN") && inlist('90',$dlvm_dlvs_step_code)) {
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
	
	<script type="text/javascript" src="../_libs/js/sptm.js?v=2019111701"></script>		
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script src="../_libs/js/bootbox.min.js"></script>	
	
	<script type="text/javascript">
		$(document).ready(function () {     				                         				
			$("#dlvm_receive_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});			
		});		
	</script>
	
	<script language="javascript">
	
	/*
	function helppopup(prgname,formname,opennerfield_code,opennerfield_name,txtsearch) {				
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
	*/
	function cancel_zone_print(dlvm_nbr) {
		if(confirm('ท่านต้องการยกเลิกการพิมพ์ใบจัดโซน ไช่หรือไม่ ?')) {
			document.frm_dlvm_cancel_zone_printed.dlvm_nbr.value = dlvm_nbr;
			document.frm_dlvm_cancel_zone_printed.submit();
		}
	}
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
	function helppopup_car(prgname,formname,opennerfield_code,opennerfield_code2,opennerfield_code3,txtsearch) {
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
			
			var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&opennerfield_code='+opennerfield_code+'&opennerfield_code2='+opennerfield_code2+'&opennerfield_code3='+opennerfield_code3,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
		}	
		
	function printform(url) {				
		window.open(url);
		setTimeout(function(){ window.location.reload(true); }, 3000);									
	}	
	
	
	
	//Shipment
	function dlvm_shipmentpost(sptm_customer_number) {
		
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		if (sptm_customer_number == "NPD_NOCUST") {
			new_customer_number = document.frm_dlvm_shipment.sptm_customer_number.value;
			new_customer_amphur = document.frm_dlvm_shipment.sptm_customer_amphur.value;
			new_customer_province = document.frm_dlvm_shipment.sptm_customer_province.value;
			new_expect_receiver_name = document.frm_dlvm_shipment.sptm_expect_receiver_name.value;
			new_expect_receiver_tel = document.frm_dlvm_shipment.sptm_expect_receiver_tel.value;
			if (new_customer_number=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ รหัสลูกค้า";					
			}
			else {
				if (new_customer_number=="NPD" || new_customer_number=="NPD_NOCUST") {
					if (errortxt!="") {errortxt = errortxt + "<br>";}	
					errorflag = true;					
					errortxt = errortxt + "กรุณาระบุ รหัสลูกค้าที่ไม่ใช่ NPD และ NPD_NOCUST";
				}
			}
			if (new_customer_amphur=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ อำเภอ";					
			}
			if (new_customer_province=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ จังหวัด";					
			}
			if (new_expect_receiver_name=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ ชื่อผู้รับสินค้า";					
			}
			if (new_expect_receiver_tel=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาระบุ เบอร์โทรผู้รับสินค้า";					
			}
		}
		dlvm_transport_tspm_code = document.frm_dlvm_shipment.dlvm_transport_tspm_code.value;
		dlvm_transport_tspm_other = document.frm_dlvm_shipment.dlvm_transport_tspm_other.value;
		dlvm_transport_ref_no = document.frm_dlvm_shipment.dlvm_transport_ref_no.value;
		dlvm_transport_driver_name = document.frm_dlvm_shipment.dlvm_transport_driver_name.value;
		dlvm_transport_amt = document.frm_dlvm_shipment.dlvm_transport_amt.value;
		dlvm_transport_car_nbr = document.frm_dlvm_shipment.dlvm_transport_car_nbr.value;
		dlvm_transport_driver_tel = document.frm_dlvm_shipment.dlvm_transport_driver_tel.value;
		
		if (dlvm_transport_tspm_code=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}	
			errorflag = true;					
			errortxt = errortxt + "กรุณาระบุ บริษัทขนส่ง";					
		}
		else {
			if (dlvm_transport_tspm_code=="OTHER") {
				if (dlvm_transport_tspm_other == "") {
					if (errortxt!="") {errortxt = errortxt + "<br>";}	
					errorflag = true;					
					errortxt = errortxt + "กรุณาระบุ ชื่อบริษัทขนส่งอื่นๆ";		
				}
			}
			
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {								
				if (xhttp.readyState == 4 && xhttp.status == 200) {								
					if (xhttp.responseText == 1) {
						if (dlvm_transport_ref_no=="") {
							if (errortxt!="") {errortxt = errortxt + "<br>";}	
							errorflag = true;					
							errortxt = errortxt + "กรุณาระบุ หมายเลขอ้างอิง[บริษัทขนส่ง]";					
						}	
					}									
				}
			}
			xhttp.open("POST", "../_chk/chkrequirereference.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("tspm_code="+dlvm_transport_tspm_code);
		}
			
		if (dlvm_transport_car_nbr=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}	
			errorflag = true;					
			errortxt = errortxt + "กรุณาระบุ ทะเบียนรถ";					
		}
		if (dlvm_transport_driver_tel=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}	
			errorflag = true;					
			errortxt = errortxt + "กรุณาระบุ เบอร์โทรที่ติดต่อได้";					
		}
		
		var xhttp2 = new XMLHttpRequest();
		xhttp2.onreadystatechange = function() {								
			if (xhttp2.readyState == 4 && xhttp2.status == 200) {
				if (xhttp2.responseText == 1) {
					if (!isnumeric(dlvm_transport_amt)) {
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "กรุณาระบุ ค่าใช้จ่ายในการขนส่งเป็นตัวเลขเท่านั้น";
					}
				}
				else {
					if (dlvm_transport_amt != "") {
						if (!isnumeric(dlvm_transport_amt)) {
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "กรุณาระบุ ค่าใช้จ่ายในการขนส่งเป็นตัวเลขเท่านั้น";
						}
					}
				}
			}
		}
		xhttp2.open("POST", "../_chk/chkrequiretransportamt.php",false);
		xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp2.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
		xhttp2.setRequestHeader("Pragma", "no-cache");
		xhttp2.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
		xhttp2.send("tspm_code="+dlvm_transport_tspm_code);
		
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {	
			var result_text="";
			$.ajax({
				beforeSend: function () {
					$('#btn_save_shipment').attr('disabled','disabled');
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/dlvmshipmentpost.php',
				data: $('#frm_dlvm_shipment').serialize(),
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
								$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
					
				}
			});
		}
	}
	
	function setvalue_dlvm_cancelpost(action,action_text) {
		document.frm_dlvm_cancel.action.value = action;
		$('#cancel_label').html(action_text);
	}
	function dlvm_cancelpost() {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		dlvm_cancel_cmmt = document.frm_dlvm_cancel.dlvm_cancel_cmmt.value;
		
		if (dlvm_cancel_cmmt=="") {
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
				url: '../serverside/dlvmcancelpost.php',
				data: $('#frm_dlvm_cancel').serialize(),
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
								$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'dlvmall.php?dlvmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
					
				}
			});
		}
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
										<td style="padding-left:5px;"><h3>Package Data</h3></td>
									</tr>	
									<tr><td height="2px"></td><tr>
									<tr>
										<td>
											<fieldset style="border-radius:4px;width:98%">
											
											<legend style="background-color:red;text-align:left;color:white;border-radius:4px;"><b>&nbsp;&nbsp;ข้อมูลลูกค้า:&nbsp;&nbsp;</b></legend>
											<center>
											<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>Package No:</b></td>
													<td width=25% class="text_s_disable"><h3><?php echo $dlvm_nbr?></h3></td>
													<td></td>
													<td><?php echo $dlvm_packing_location?></td>
												</tr>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเบิก:</b></td>
													<td width=25% class="text_s_disable"><?php echo $dlvm_sptm_nbr?> <?php echo $sptm_copy_refer_text?></td>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอเบิก:</b></td>
													<td class="text_s_disable"><?php echo dmytx($sptm_req_date)?></td>
												</tr>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
													<td class="text_s_disable"><b><?php echo $sptm_req_by_name . " " . $sptm_tel_contact;?></b></td>
															
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
												<?php if ($sptm_npd) {?>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Company:</b></td>
												<td class="text_s_disable"><?php echo $sptm_npd_com?></td>
												<?php if($sptm_customer_number == "NPD_NOCUST") {?>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] จำนวนลูกค้า:</td>
													<td class="text_s_disable"><?php echo $sptm_npd_customer_total?> ร้าน.</td>
												<?php } else { ?>
													<td></td>
													<td class="text_s_disable"></td>
												<?php }?>
												
												
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Brand:</b></td>
												<td class="text_s_disable"><?php echo $sptm_npd_brand_name?></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>[NPD] Set No:</b></td>
												<td class="text_s_disable"><?php echo $sptm_npd_setno_name?></td>
											</tr>
											<?php }?>
												<tr>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเหตุการจัดส่ง:</b></td>
													<td class="text_s_disable"><?php echo str_replace("\n","<br />",$sptm_delivery_mth_desc)?></td>
													<td style="text-align:right; width: 150px; vertical-align: middle;"><b>Staus:</td>
													<td>
														<?php echo $dlvm_step_name?>
														<?php if ($can_cancel_delivery && $dlvm_dlvs_step_code == "10") {?>
															<a href="#dlvm_cancel" style='color:red' onclick="setvalue_dlvm_cancelpost('cancel_delivery','ยกเลิก PACKAGE')" data-toggle="modal">(ยกเลิก PACKAGE นี้)</a>
														<?php }?>
														<?php if ($can_cancel_shipment && $dlvm_dlvs_step_code == "20") {?>
															<a href="#dlvm_cancel" style='color:red' onclick="setvalue_dlvm_cancelpost('cancel_shipment','ยกเลิกการจัดเที่ยว')" data-toggle="modal">(ยกเลิกการจัดเที่ยว)</a>
														<?php }?>
														<?php if ($can_cancel_receive && $dlvm_dlvs_step_code == "90") {?>
															<a href="#dlvm_cancel" style='color:red' onclick="setvalue_dlvm_cancelpost('cancel_receive','ยกเลิกการรับสินค้า')" data-toggle="modal">(ยกเลิกการรับสินค้า)</a>
														<?php }?>
														<?php if($dlvm_receive_by!="") {?>
															<br><span style='color:green;font-weight:bold'><?php echo "ผู้บันทึกรับสินค้า :: คุณ$dlvm_receive_by_name, $dlvm_receive_date"?>
														<?php }?>
														
													</td>
												</tr>
												<?php if ($dlvm_attach_link != "") {?>
													<?php 
													if ($can_read) { 
														$dlvm_attach = $dlvm_attach_link; 
													} else {
														$dlvm_attach = $dlvm_attach_notlink; 
													}
													?>
													<tr>
														<td style="text-align:right; width: 150px; vertical-align: middle;"><b>Attach File:</b></td>
														<td colspan=2 bgcolor=#ffe6e6><?php echo $dlvm_attach;?></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>												
												<?php }?>											
												<tr>
													<td></td>
													<td>
														<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='dlvmall.php?activeid=<?php echo encrypt($dlvm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
															<i class="icon-white icon-hand-left"></i>
															<span>Black</span>													
														</div>
														<!--ยกเลิกการพิมพ์ที่หน้านี้และย้ายการพิมพ์ไปที่ใบส่งของ-->
														<?php //if(($can_shipment || $can_receive) && $dlvm_step <= 20) {?>
															<!--div class="btn btn-small btn-info" style="margin-top:5px; margin-bottom:10px; width: 100px;" onclick="javascript:printform('sptmformde.php?dlvmnumber=<?php echo encrypt($dlvm_nbr, $key); ?>')">
																<i class="icon-white icon-print"></i>														
																<?php //if ($dlvm_print_cnt == 0) {?>
																	<span>Print</span>		
																<?php //} else { ?>
																	<span>Re-Print</span>
																<?php //}?>														
															</div-->		
														<?php //}?>													
													</td>
													<td></td>												
													<td>
														<?php if ($can_shipment) { ?>
															<a href="#dlvm_shipment"  role="button" style="color:white; text-decoration:none;" data-toggle="modal">
																<div class="btn btn-small btn-default" style="margin-top:5px; margin-bottom:10px; width: 70px;">
																	<img src="../_images/schedule.jpg" style="width:16px;height:16px">
																	<span style='color:red' class='blinking'>จัดเที่ยวรถ</span>													
																</div>
															</a>
														<?php } ?>
														<!--ยกเลิกการบันทึกรับที่หน้านี้ แต่โปรแกรมการรับตรงนี้ยังใช้งานได้อยู่ ย้าย logic นี้ไปที่หน้ารับสินค้าจากใบส่งของแทย-->
														<?php //if ($can_receive) { ?>
															<!--a href="dlvmrct.php?d=<?php //echo encrypt($dlvm_nbr, $key);?>">
																<div class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 70px;">
																	<span>บันทึกรับสินค้า</span>													
																</div>
															</a-->
														<?php //} ?>
														<div class="btn btn-small btn-default" style="color:red;margin-top:5px; margin-bottom:10px; width: 150px;">
															<span><?php echo "(น้ำหนักสุทธิ: " . $dlvm_packing_weight . " KG.)"?></span>													
														</div>
														<?php if ($dlvm_zone_printed && $dlvm_dlvs_step_code == '10') {?>
															<div class="btn btn-small btn-default" onclick="cancel_zone_print('<?php echo $dlvm_nbr?>')" style="margin-top:5px; margin-bottom:10px; width: 120px;">
																<img src="../_images/zone.png" style="width:16px;height:16px">
																<span style='color:red' title="ยกเลิกเพื่อให้สามารถพิมพ์ใหม่ได้อีกครั้ง">ยกเลิกพิมพ์ใบจัดโซน</span>													
															</div>
														<?php }?>
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
											<div class="container" style="width:100%">
												<div class="tab-content">
													<div id="product_section">
														<!--กระเบื้องแผ่น-->
														<fieldset style="background-color:white;border-radius:4px;width:98%">
														<legend style="background-color:red;text-align:left;color:white;border-radius:4px;"><b>&nbsp;&nbsp;รายการสินค้า:&nbsp;&nbsp;</b></legend>
														<center>
															<?php include("dlvdmnt_detail.php");?>
														</center>
														</fieldset>
													</div>
												</div>
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
	
	<div id="dlvm_shipment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_dlvm_shipment" name="frm_dlvm_shipment"  autocomplete=OFF method="post">
			<input type="hidden" name="action" value="<?php echo md5('dlvm_shipment'.$user_login)?>">
			<input type="hidden" name="dlvm_nbr" value="<?php echo $dlvm_nbr?>">
			<input type="hidden" name="sptm_nbr" value="<?php echo $dlvm_sptm_nbr?>">
			
			<input type="hidden" name="pg" value="<?php echo $pg?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> จัดเที่ยวรถ <?php echo $dlvm_nbr?></h3>
			</div>
			<?php if ($sptm_customer_number == "NPD_NOCUST") {?>
			<div class="">
				<table border="0" class="table-condensed">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr>
						<td style="width:80px;text-align:right;">รหัสลูกค้า:</td>
						<td style="width:160px">
							<input type="text" name="sptm_customer_number" id="sptm_customer_number" class="inputtext_s" style="width: 110px" maxlength="20">														
							<button type="button" name="sptm_customer_help" id="sptm_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getcustshipmentampv.php','','','',document.frm_dlvm_shipment.sptm_customer_number.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>				
						</td>
						<td style="text-align:right; width: 60px; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
						<td><input type="text"  name="sptm_customer_name" class="inputtext_s" style="width: 200px;" readonly></td>
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle;"><b>อำเภอ:</b></td>
						<td>
							<input type="text" name="sptm_customer_amphur" id="sptm_customer_amphur" class="inputtext_s" style='width:110px' maxlength=255>
							<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getamphur.php','frm_dlvm_shipment','sptm_customer_amphur','sptm_customer_province',document.frm_dlvm_shipment.sptm_customer_amphur.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
						<td style="text-align:right;vertical-align: middle;"><b>จังหวัด:</b></td>
						<td>
							<input type="text" name="sptm_customer_province" id="sptm_customer_province" class="inputtext_s"  style='width:100px' maxlength=255>
							<button type="button" name="sptm_province_help" id="sptm_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getprovince.php','frm_dlvm_shipment','sptm_customer_province','',document.frm_dlvm_shipment.sptm_customer_province.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle;"><b>ชื่อผู้รับ:</b></td>
						<td>
							<input type="text" name="sptm_expect_receiver_name" id="sptm_expect_receiver_name" class="inputtext_s" style="width:130px" maxlength=255>
						</td>
						<td style="text-align:right;vertical-align: top;"><b>โทรผู้รับ:</b></td>
						<td style="vertical-align: top;">
							<input type="text" name="sptm_expect_receiver_tel" id="sptm_expect_receiver_tel" class="inputtext_s" style="width:130px" maxlength=60>
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<?php }?>
			<div class="">
				<table border=0 class="table-condensed">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr>
						<td style="width:80px;text-align:right;">บริษัทขนส่ง:<font color=red>*</font></td>
						<td style="width:150px">
							<select name="dlvm_transport_tspm_code" style="margin:auto;width: 150px;" >
								<option value="">--เลือก--</option>
								<?php 
								$sql_tspm = "SELECT tspm_code,tspm_name FROM tspm_mstr order by tspm_seq";
								$result_tspm_list = sqlsrv_query( $conn,$sql_tspm);																													
								while($r_tspm_list=sqlsrv_fetch_array($result_tspm_list, SQLSRV_FETCH_ASSOC)) {
								?>
									<option  style="color:black" value="<?php echo $r_tspm_list['tspm_code'];?>"><?php echo $r_tspm_list['tspm_name'];?></option> 
								<?php } ?>
							</select>					
						</td>
						<td style="text-align:right;color:red">**บริษัท(อื่นๆ):</td>
						<td><input name="dlvm_transport_tspm_other" Placeholder="*ชื่อบริษัทขนส่งอื่นๆที่ใช้ขนส่ง" type="text" style="margin:auto;width: 180px;" maxlength="100"></td> 
					</tr>
					
					<tr>
						<td style="text-align:right;vertical-align: middle">หมายเลขอ้างอิง<br>[บริษัทขนส่ง]:</td>
						<td colspan=3><input name="dlvm_transport_ref_no" Placeholder="*Tracking Number" type="text" style="margin:auto;width: 250px;" maxlength="60"></td> 
					</tr>
					
					<tr>
						<td style="text-align:right;vertical-align: middle">ทะเบียนรถ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_car_nbr"  type="text" style="margin:auto;width: 150px;" maxlength="30">
						<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup_car('../_help/getcar.php','frm_dlvm_shipment','dlvm_transport_car_nbr','dlvm_transport_driver_name','dlvm_transport_driver_tel',document.frm_dlvm_shipment.dlvm_transport_car_nbr.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">เบอร์ตืดต่อ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_driver_tel"  type="text" style="margin:auto;width: 150px;" maxlength="60"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle;color:red">**ค่าใช้จ่าย:</td>
						<td colspan=3><input name="dlvm_transport_amt"  type="text" style="margin:auto;width: 150px;" maxlength="30"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">ชื่อผู้ขับรถ:</td>
						<td colspan=3><input name="dlvm_transport_driver_name"  type="text" style="margin:auto;width: 150px;" maxlength="100"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">หมายเหตุ:</td>
						<td colspan=3> 
							<input type="text" name="dlvm_transport_cmmt" style="width: 250px;" maxlength="100">
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button id="btn_save_shipment" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="dlvm_shipmentpost('<?php echo $sptm_customer_number?>')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>																																																			
	</div>
	
	<div id="dlvm_cancel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form id="frm_dlvm_cancel" autocomplete="OFF" name="frm_dlvm_cancel" method="post">
			<input type="hidden" name="action">
			<input type="hidden" name="action_confirm" value="<?php echo md5($user_login)?>">
			<input type="hidden" name="dlvm_nbr" value="<?php echo $dlvm_nbr?>">
			<input type="hidden" name="sptm_nbr" value="<?php echo $dlvm_sptm_nbr?>">
			<input type="hidden" name="sptm_delivery_mth" value="<?php echo $sptm_delivery_mth?>">
			<input type="hidden" name="sptm_customer_name" value="<?php echo $sptm_customer_name?>">
			<input type="hidden" name="pg" value="<?php echo $pg?>">
			<div class="modal-header" style="background-color:red">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> :: <span id="cancel_label" style='color:white'><span></h3>
			</div>
			<div class="">
				<table border=0 class="table table-condensed table-responsive">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr><td><b>Comment::</b></td></td>
					<tr>
						<td>
							<textarea name="dlvm_cancel_cmmt" rows=3 class="inputtext_s form-control" style="min-width: 80%" maxlength="255"></textarea>
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button id="btn_save_cancel" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="dlvm_cancelpost()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>	
	</div>

	<form id="frm_dlvm_cancel_zone_printed" name="frm_dlvm_cancel_zone_printed" method="post" action="../serverside/dlvdmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5("cancel_zone_print".$user_login)?>">
		<input type="hidden" name="dlvm_nbr" value="<?php echo $dlvm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg?>">
	</form>	
	
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
