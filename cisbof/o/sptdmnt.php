<?php 
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	clearstatcache();
	
	$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
	$sptm_nbr = decrypt(mssql_escape($_REQUEST['sptmnumber']), $key);
	$pg = $_REQUEST['pg'];
	
	//$curdate = date('Y-m-d');
	$curdate = date('d/m/Y');
	$filepath_at = "../_fileuploads/at/";
	
	//Get Worker :: display on popup create package
	$worker_by = "<option value=''>--เลือก--</option>";
	$sql_worker = "SELECT worker_code,worker_name FROM worker_mstr WHERE worker_active = '1' order by worker_name";
	$result_worker_list = sqlsrv_query( $conn,$sql_worker);																													
	while($r_worker_list=sqlsrv_fetch_array($result_worker_list, SQLSRV_FETCH_ASSOC)) {
		$worker_by .= "<option value=".$r_worker_list['worker_code'].">".$r_worker_list['worker_name']."</option>";													
	}
	//TEMP VARIABLE
	$can_edit = true;
	//
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
		$sptm_req_channel = $r_sptm['sptm_req_channel'];
		$sptm_req_channel_name = findsqlval("channel_mstr","channel_name","channel_code",$sptm_req_channel,$conn);
		$sptm_tel_contact = findsqlval("emp_mstr","emp_tel_contact","emp_user_id",$sptm_req_by,$conn);
		if ($sptm_tel_contact != "") {
			$sptm_tel_contact = " <span style='color:red'>(".$sptm_tel_contact.")</span>";
		}
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
		$sptm_force_close = $r_sptm['sptm_force_close'];	
		$sptm_remark = html_quot($r_sptm['sptm_remark']);
		$sptm_print_cnt = $r_sptm['sptm_print_cnt'];
		$sptm_input_type = $r_sptm['sptm_input_type'];
		$sptm_whocanread = $r_sptm['sptm_whocanread'];
		$sptm_curprocessor = $r_sptm['sptm_curprocessor'];																									
		$sptm_create_by = $r_sptm['sptm_create_by'];	
		$sptm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_create_by,$conn);
		$sptm_oper_note = html_quot($r_sptm['sptm_oper_note']);
		
		
		$sptm_control_stock = $r_sptm['sptm_control_stock'];
		$sptm_npd_control_stock = $r_sptm['sptm_npd_control_stock'];
		//NPD
		$sptm_npd = $r_sptm['sptm_npd'];
		$sptm_npd_com = $r_sptm['sptm_npd_com'];
		$sptm_npd_type = $r_sptm['sptm_npd_type'];
		$sptm_npd_brand = $r_sptm['sptm_npd_brand'];
		$sptm_npd_setno = $r_sptm['sptm_npd_setno'];
		$sptm_npd_customer_total = $r_sptm['sptm_npd_customer_total'];
		$sptm_npd_setno_name = "";
		$sptm_npd_brand_name = "";
		if ($sptm_npd) {
			$sptm_npd_text = " [*NPD*]";
			$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$sptm_npd_brand,$conn);
			$sptm_npd_setno_name = substr($sptm_npd_setno,strpos($sptm_npd_setno,'@')+1,strlen($sptm_npd_setno));
		}
		
		//
		$sptm_copy_refer = $r_sptm['sptm_copy_refer'];
		$sptm_copy_refer_text = "";
		if ($sptm_copy_refer != "") {
			$sptm_copy_refer_text = "<span style='color:red;font-size:8pt;'>[Ref: $sptm_copy_refer] $sptm_npd_text</span>";
		}
		$sptm_req_channel_submit = "";
		if ($sptm_req_channel != "") {
			$sptm_req_channel_submit = " <span style='color:red'>[Channel Submit: ".$sptm_req_channel."]";
		}
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
		if ($sptm_force_close) {
			$sptm_receive_status_name = "<span style='background:red;border-radius:4px;color:white'> ** ห้องตัวอย่างปิดเอกสาร ** </span>";
		}
		else {
			$sptm_receive_status_name = "<span style='background:green;border-radius:4px;color:white'> ** ลูกค้ารับสินค้าครบแล้ว ** </span>";
		}
	}
	
	//Get Attach File
	$sptm_attach_link = "";
	$sql = "SELECT * FROM sptat_attach where sptat_sptm_nbr = '$sptm_nbr' order by sptat_id";	
	$result = sqlsrv_query( $conn, $sql );											
	while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$sptat_s_file = $r['sptat_s_file'];
		if ($sptm_attach_link != "") { 
			$sptm_attach_link = $sptm_attach_link . " | "; 
		}
		$sptm_attach_ext = strtoupper(explode(".",$sptat_s_file)[1]);
		if (inlist("JPG,PNG,BMP",$sptm_attach_ext)) {
			$sptm_attach_link = $sptm_attach_link . "<a href='$filepath_at$sptat_s_file' rel='prettyPhoto'>" . $r['sptat_o_file'] . "</a>";	
		}
		else {
			$sptm_attach_link = $sptm_attach_link . "<a href='$filepath_at$sptat_s_file' target='_blank'>" . $r['sptat_o_file'] . "</a>";	
		}
	}	

	$iscurrentprocessor = false;
	$can_editing = false;
	$can_submit = false;
	$can_request_editing = false;
	$can_request_cancel = false;
	$can_approve = false;
	$can_packing = false;
	$can_print_rq = false;
	$can_print_wo = false;
	$can_create_delivery = false;
	$can_delivery = false;
	$can_force_close = false;
	$can_force_close_reopen = false;
	$can_npd = false;
	$can_send_manual_foc = false;
	$can_save_dn = false;
	$can_sptd_oper_note = false;
	$can_npd_copy = false;
	
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
	if ($iscurrentprocessor && inlist('0,10',$sptm_step_code) && inlist($user_role,'NPD') && $sptm_npd) {
		$can_npd = true;
	}
	if ($iscurrentprocessor && inlist('0,10',$sptm_step_code)) {
		$can_editing = true;
	}
	if ($iscurrentprocessor && inlist('0,10',$sptm_step_code)) {
		$can_submit = true;
	}
	
	if (($sptm_create_by == $user_login) && inlist('20',$sptm_step_code)) {
		$can_request_editing = true;
		$can_request_cancel = true;
	}
	
	if ($iscurrentprocessor && inlist('20',$sptm_step_code)) {
		$can_approve = true;
	}
	if ($sptm_create_by == $user_login) {
		$can_print_rq = true;
	}
	if ((int)$sptm_step_code >= 30 && inlist($user_role,'SPT_ROOM')) {
		$can_print_wo = true;
		$can_send_manual_foc = true;
		$can_save_dn = true;
		$can_sptd_oper_note = true;
	}
	if ($iscurrentprocessor && inlist('30',$sptm_step_code)) {
		$can_packing = true;
		$can_delivery = true;
		//$can_send_manual_foc = true;
		//$can_save_dn = true;
		//$can_sptd_oper_note = true;
	}
	if ($can_delivery && $sptm_npd && $sptm_nbr != $sptm_copy_refer && $sptm_copy_refer != "") {
		$can_npd_copy = true;
	}
	if (inlist($user_role,"SPT_ROOM") && $sptm_step_code == "30") {
		$can_force_close = true;
	}
	if (inlist($user_role,"SPT_ROOM") && $sptm_step_code == "990" && $sptm_force_close) {
		$can_force_close_reopen = true;
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
	<link href="../_libs/prettyPhoto_3.1.6/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
	<link href="../_libs/css/sptm.css"  rel="stylesheet" media="all" type="text/css" />
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
			$("#sptm_expect_receipt_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});
		});		
	</script>
	
	<script language="javascript">
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
	function dlvmpopup(url) {				
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
		settings +='location=no,'; 
		settings +='resizable=yes';		
		var myWindow=window.open(url,'dlvmpopup',settings);		
		if (!myWindow.opener) myWindow.opener = self;
		
	}
	function printform(url) {				
		window.open(url);
		setTimeout(function(){ window.location.reload(true); }, 3000);									
	}	
	function npd_nocust_customer_total_post(custnpd_brand) {
		
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptm_npd_customer_total = document.frm_npd_nocust_customer_total.sptm_npd_customer_total.value;
		if (sptm_npd_customer_total == "") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนลูกค้าที่ต้องการสินค้า NPD";
		}
		else {
			if (!isnumeric(sptm_npd_customer_total)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนลูกค้าที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
			else {
				if (sptm_npd_customer_total < 0) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "จำนวนลูกค้าที่ระบุต้องมากกว่า 0";
				}
				else {
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {								
						if (xhttp.readyState == 4 && xhttp.status == 200) {								
							if (xhttp.responseText == 0) {							
								if (errortxt!="") {errortxt = errortxt + "<br>";}
								errorflag = true;
								errortxt = errortxt + "ไม่พบลูกค้าของสินค้า Brand นี้ในนระบบ";
							}
							else {
								if (parseInt(xhttp.responseText) < parseInt(sptm_npd_customer_total)) {
									if (errortxt!="") {errortxt = errortxt + "<br>";}
									errorflag = true;
									errortxt = errortxt + "ลูกค้าที่อยู่ใน Master ของ Brand มี = "+ xhttp.responseText+ " ซึ่งน้อยกว่าจำนวนที่ระบุ";
								}
							}
						}
					}
					xhttp.open("POST", "../_chk/gettotalcustbybrand.php",false);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp.setRequestHeader("Pragma", "no-cache");
					xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
					xhttp.send("custnpd_brand="+custnpd_brand);
				}
			}
		}
		if (errorflag) {
			//document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			//$("#myModal").modal("show");
			alert(errortxt);
		}
		else {				
			document.frm_npd_nocust_customer_total.submit();
		}
	}
	//SPTD PRODUCT
	function sptd_product_postform(formname) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptd_mat_code = document.forms[formname].sptd_mat_code.value;
		var sptd_qty_order = document.forms[formname].sptd_qty_order.value;
		var sptd_unit_code = document.forms[formname].sptd_unit_code.value;
	
		if (sptd_mat_code=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุ รหัสกระเบื้อง";				
		}
		else {									
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {								
				if (xhttp.readyState == 4 && xhttp.status == 200) {								
					if (xhttp.responseText == 0) {							
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "รหัสกระเบื้องที่ระบุไม่มีในระบบ";
					}									
				}
			}
			xhttp.open("POST", "../_chk/chkmatcodeexist.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("mat_code="+sptd_mat_code);
			//
			var xhttp1 = new XMLHttpRequest();
			xhttp1.onreadystatechange = function() {								
				if (xhttp1.readyState == 4 && xhttp1.status == 200) {
					if (xhttp1.responseText != 'MT') {						
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "ท่านต้องเลือกกลุ่มที่เป็น Material เท่านั้น";
					}									
				}
			}
			xhttp1.open("POST", "../_chk/getmatgroup.php",false);
			xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp1.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp1.setRequestHeader("Pragma", "no-cache");
			xhttp1.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp1.send("mat_code="+sptd_mat_code);
		}	
			
		if (sptd_qty_order=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_order)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
		}
		if (sptd_unit_code=="") {			
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุหน่วย";		
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.forms[formname].submit();
		}					
	}

	function del_sptd_product(sptd_id) {
		if(confirm('ท่านต้องการลบสินค้านี้ ไช่หรือไม่ ?')) {			
			document.frm_del_sptd_product.sptd_id.value = sptd_id;		
			document.frm_del_sptd_product.submit();
		}
	}
	function packing_sptd_product(sptd_id) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptd_qty_packing = document.frm_packing_sptd_product.sptd_qty_packing.value;
		
		if (sptd_qty_packing=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_packing)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
			else {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {								
					if (xhttp.readyState == 4 && xhttp.status == 200) {								
						if (parseInt(sptd_qty_packing) > parseInt(xhttp.responseText)) {						
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "ท่านสามารถระบุจำนวน Packing ได้มากที่สุดเพียง " + xhttp.responseText + " ชิ้นเท่านั้น";
						}									
					}
				}
				xhttp.open("POST", "../_chk/chkpackingbal.php",false);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
				xhttp.setRequestHeader("Pragma", "no-cache");
				xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
				xhttp.send("sptd_id="+sptd_id);
			}
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.frm_packing_sptd_product.submit();
		}
	}
	
	function upload_sptd_product_postform() {
		var filename = document.frm_upload_sptd_product.fileupload_sptd_product.value;
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
			var formObj = $('#frm_upload_sptd_product')[0];
			var formData = new FormData(formObj);
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: "POST",
				url: '../serverside/sptdmnt_product_post.php',
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
								$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
	}
	//END SPTD PRODUCT
	
	//BOARD STANDARD
	function board_bs_postform(formname) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		var sptd_mat_code = document.forms[formname].sptd_mat_code.value;
		var sptd_qty_order = document.forms[formname].sptd_qty_order.value;
		var sptd_remark = document.forms[formname].sptd_remark.value;
		//Accept Mat Group BS Only
	
		if (sptd_mat_code=="") {			
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาเลือกบอร์ดมาตรฐานที่ต้องการ";		
		} else {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {								
				if (xhttp.readyState == 4 && xhttp.status == 200) {								
					if (xhttp.responseText == 0) {							
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "รหัสกระเบื้องที่ระบุไม่มีในระบบ";
					}									
				}
			}
			xhttp.open("POST", "../_chk/chkmatcodeexist.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("mat_code="+sptd_mat_code);
			//
			var xhttp1 = new XMLHttpRequest();
			xhttp1.onreadystatechange = function() {								
				if (xhttp1.readyState == 4 && xhttp1.status == 200) {
					if (xhttp1.responseText != 'BS') {						
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "ท่านต้องเลือก Board Stand หรือกลุ่มที่ไม่ใช่ Material เท่านั้น";
					}									
				}
			}
			xhttp1.open("POST", "../_chk/getmatgroup.php",false);
			xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp1.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp1.setRequestHeader("Pragma", "no-cache");
			xhttp1.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp1.send("mat_code="+sptd_mat_code);
		}
		if (sptd_qty_order=="") {			
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_order)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.forms[formname].submit();
		}	
	}
	function del_board_bs(sptd_id) {
		document.frm_del_board_bs.sptd_id.value = sptd_id;
		if(confirm('ท่านต้องการลบ  [บอร์ดมาตรฐาน  นี้ ไช่หรือไม่ ?')) {			
			document.frm_del_board_bs.submit();
		}
	}
	function packing_board_bs(sptd_id) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptd_qty_packing = document.frm_packing_board_bs.sptd_qty_packing.value;
		
		if (sptd_qty_packing=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_packing)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
			else {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {								
					if (xhttp.readyState == 4 && xhttp.status == 200) {								
						if (parseInt(sptd_qty_packing) > parseInt(xhttp.responseText)) {
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "ท่านสามารถระบุจำนวน Packing ได้มากที่สุดเพียง " + xhttp.responseText + " ชิ้นเท่านั้น";
						}									
					}
				}
				xhttp.open("POST", "../_chk/chkpackingbal.php",false);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
				xhttp.setRequestHeader("Pragma", "no-cache");
				xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
				xhttp.send("sptd_id="+sptd_id);
			}
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.frm_packing_board_bs.submit();
		}
	}
	//END BOARD STANDARD
	
	//BOARD CUSTOM
	function board_bc_postform(formname) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		var sptd_remark = document.forms[formname].sptd_remark.value;
		var sptd_qty_order = document.forms[formname].sptd_qty_order.value;
		
		if (sptd_remark=="") {			
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุชื่อบอร์ดปรับแต่ง";		
		}
		
		if (sptd_qty_order=="") {			
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_order)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.forms[formname].submit();
		}	
	}
	function del_board_bc(sptd_id) {
		document.frm_del_board_bc.sptd_id.value = sptd_id;
		if(confirm('ท่านต้องการลบ  [บอร์ดปรับแต่ง]  นี้ ไช่หรือไม่ ?')) {			
			document.frm_del_board_bc.submit();
		}
	}
	function upload_board_bc_product_postform() {
		var filename = document.frm_upload_board_bc_product.fileupload_board_bc_product.value;
		var ext = filename.substr(filename.lastIndexOf('.') + 1);
		if (filename == "" || ext != 'xls') {
			alert('System allow for excel 2003 (*.xls) file format only!!');
			return;
		}
		if(confirm('คุณต้องการ Upload ใช่หรือไม่ ?')) {			
			//document.frm_upload_board_bc_product.submit();
			var result_text="";
			$.ajaxSetup({
				cache: false,
				contentType: false,
				processData: false
			}); 
			var formObj = $('#frm_upload_board_bc_product')[0];
			var formData = new FormData(formObj);
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: "POST",
				url: '../serverside/sptdmnt_bc_post.php',
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
								$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
	}
	//BOARD CUSTOM PRODUCT

	function setvalue_board_bc_product(formname,sptd_id) {
		document.forms[formname].sptd_id.value = sptd_id;
	}
	function setvalue_upload_board_bc_product(formname,sptd_id) {
		document.forms[formname].sptd_id.value = sptd_id;
	}
	function board_bc_product_postform(formname) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptbc_mat_code = document.forms[formname].sptbc_mat_code.value;

		if (sptbc_mat_code=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุ รหัสกระเบื้อง";				
		}
		else {									
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {								
				if (xhttp.readyState == 4 && xhttp.status == 200) {								
					if (xhttp.responseText == 0) {							
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "รหัสกระเบื้องที่ระบุไม่มีในระบบ";
					}									
				}
			}
			xhttp.open("POST", "../_chk/chkmatcodeexist.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("mat_code="+sptbc_mat_code);
			//
			var xhttp1 = new XMLHttpRequest();
			xhttp1.onreadystatechange = function() {								
				if (xhttp1.readyState == 4 && xhttp1.status == 200) {
					if (xhttp1.responseText != 'MT') {						
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "ท่านต้องเลือกกลุ่มที่เป็น Material เท่านั้น";
					}									
				}
			}
			xhttp1.open("POST", "../_chk/getmatgroup.php",false);
			xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp1.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp1.setRequestHeader("Pragma", "no-cache");
			xhttp1.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp1.send("mat_code="+sptbc_mat_code);
		}
		
		
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.forms[formname].submit();
		}
	}

	function del_board_bc_product(sptd_id,sptbc_id) {
		document.frm_del_board_bc_product.sptd_id.value = sptd_id;
		document.frm_del_board_bc_product.sptbc_id.value = sptbc_id;
		if(confirm('ท่านต้องการลบสินค้านี้ ไช่หรือไม่ ?')) {			
			document.frm_del_board_bc_product.submit();
		}
	}
	function del_board_bc_image(sptd_id) {
		document.frm_del_board_bc_image.sptd_id.value = sptd_id;
		if(confirm('ท่านต้องการลบ File ที่เป็นรูปแบบของบอร์ดนี้ ไช่หรือไม่ ?')) {			
			document.frm_del_board_bc_image.submit();
		}
	}
	function packing_board_bc(sptd_id) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptd_qty_packing = document.frm_packing_board_bc.sptd_qty_packing.value;
		
		if (sptd_qty_packing=="") {
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาระบุจำนวนที่ต้องการ";		
		}
		else {
			if (!isnumeric(sptd_qty_packing)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "จำนวนที่ระบุต้องเป็นตัวเลขเท่านั้น";
			}
			else {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {								
					if (xhttp.readyState == 4 && xhttp.status == 200) {								
						if (parseInt(sptd_qty_packing) > parseInt(xhttp.responseText)) {
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "ท่านสามารถระบุจำนวน Packing ได้มากที่สุดเพียง " + xhttp.responseText + " ชิ้นเท่านั้น";
						}									
					}
				}
				xhttp.open("POST", "../_chk/chkpackingbal.php",false);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
				xhttp.setRequestHeader("Pragma", "no-cache");
				xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
				xhttp.send("sptd_id="+sptd_id);
			}
		}
		if (errorflag) {
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {				
			document.frm_packing_board_bc.submit();
		}
	}
	//BOARD CUSTOM END
	
	function submitpost() {		
		var errorflag = false;
		var errortxt = "";
		var sptm_nbr = document.forms["frm_submit"].sptm_nbr.value;
		
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";		
		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {								
			if (xhttp.readyState == 4 && xhttp.status == 200) {					
				if (xhttp.responseText.substring(0,2) != "OK") {
					if (errortxt != "") {errortxt = errortxt + "<br>";}
					errorflag = true;					
					errortxt = errortxt + xhttp.responseText;
				}				
			}			
		}
		xhttp.open("POST", "../_chk/chkformuser.php",false);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
		xhttp.setRequestHeader("Pragma", "no-cache");
		xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
		xhttp.send("sptmnumber="+sptm_nbr);	
		
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {								
			
			if(confirm('ท่านต้องการส่งเอกสารไปยังผู้อนุมัติ ไช่หรือไม่ ?')) {
				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/sptmsubmitpost.php',
					data: $('#frm_submit').serialize(),
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
								bootbox.alert({
									message: result_text,
									size: 'small',
									callback: function () {
										$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
									}
								});
							}
							else {
								$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
							}
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
			else {
				return;			
			}										
		}
	}

	function requesteditpost(sptm_nbr) {
		var errorflag = false;
		var errortxt = "";
		var sptm_nbr = document.forms["frm_requestedit"].sptm_nbr.value;
		
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";		
		
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {								
			if(confirm('ท่านต้องการดึงเอกสารที่ส่งไปขออนุมัติกลับมาแก้ไข ไช่หรือไม่ ?')) {
				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/sptmrequestpost.php',
					data: $('#frm_requestedit').serialize(),
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
								bootbox.alert({
									message: result_text,
									size: 'small',
									callback: function () {
										$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
									}
								});
							}
							else {
								$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							}
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
			else {
				return;			
			}										
		}
	}
	function requestcancelpost(sptm_nbr) {
		var errorflag = false;
		var errortxt = "";
		var sptm_nbr = document.forms["frm_requestcancel"].sptm_nbr.value;
		
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";		
		
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {								
			if(confirm('ท่านต้องการยกเลิกเอกสารที่ส่งไปขออนุมัติ ไช่หรือไม่ ?')) {
				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/sptmrequestpost.php',
					data: $('#frm_requestcancel').serialize(),
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
								bootbox.alert({
									message: result_text,
									size: 'small',
									callback: function () {
										$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
									}
								});
							}
							else {
								$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
							}
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
			else {
				return;			
			}										
		}
	}
	function approvepostform() {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		if (!RadioIsCheck(document.frmapprove.sptm_approve_select)) {	
			if (errortxt!="") {errortxt = errortxt + "<br>";}
			errorflag = true;
			errortxt = errortxt + "กรุณาเลือกผลการอนุมัติ";
		}
		//
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {								
			if(confirm('ท่านต้องการทำรายการนี้ต่อ ไช่หรือไม่ ?')) {
				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/sptmapprovepost.php',
					data: $('#frmapprove').serialize(),
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
									$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
							}
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
			else {
				return;			
			}										
		}
	}
	function allpackingpost(sptm_nbr) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		if(confirm('ท่านต้องการทำ Packing ให้กับสินค้าทุกรายการใช่หรือไม่ ?')) {
			var result_text="";
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/sptmallpackingpost.php',
				data: $('#frmallpacking').serialize(),
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
								$(location).attr('href', 'sptdmmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							});
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		else {
			return;			
		}								
	}
	function deliverypost(sptm_nbr,control_stock,isnpd,npd_control_stock,accept_neg_stock) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		var total_packing = "";
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {								
			if (xhttp.readyState == 4 && xhttp.status == 200) {	
				if (xhttp.responseText <= 0) {
					if (errortxt != "") {errortxt = errortxt + "<br>";}
					errorflag = true;					
					errortxt = errortxt + "ไม่สามารถสร้าง Package ได้เนื่องจากคุณยังไม่ได้สร้างข้อมูล Packing ในระบบค่ะ";
				}	
				else {
					total_packing = xhttp.responseText;
				}
			}			
		}
		xhttp.open("POST", "../_chk/chkpackingsel.php",false);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
		xhttp.setRequestHeader("Pragma", "no-cache");
		xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
		xhttp.send("sptmnumber="+sptm_nbr);

		if (control_stock) {
			var impack_stock = true;
			if (isnpd == true && npd_control_stock == false) {
				impack_stock = false;
			}
			if (impack_stock) {
				if (!accept_neg_stock) {
					var xhttp1 = new XMLHttpRequest();
					xhttp1.onreadystatechange = function() {								
						if (xhttp1.readyState == 4 && xhttp1.status == 200) {	
							if (xhttp1.responseText != "") {
								if (errortxt != "") {errortxt = errortxt + "<br>";}
								errorflag = true;					
								errortxt = errortxt + "ไม่สามารถสร้าง Package No ได้เนื่องจากมีสินค้า<br>" + xhttp1.responseText + "<br>เมื่อทำ Package แล้ว Stock จะติดลบค่ะ";
							}	
						}			
					}
					xhttp1.open("POST", "../_chk/chkdlvnegstock.php",false);
					xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp1.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp1.setRequestHeader("Pragma", "no-cache");
					xhttp1.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
					xhttp1.send("sptmnumber="+sptm_nbr);
				}
			}
		}
		
		//
		if (errorflag ) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {
			var confirm_info = "<table align=center>" +
					  "<tr><td><font color=blue><h4>กระเบื้องตัวอย่าง:  "+ total_packing + " รายการ จะถูกสร้างเป็น Package</h4></font></td></tr>" +
					  "<tr><td align=center><b>Package Weight (KG)</b></td></tr>" +
					  "<tr><td align=center><input autocomplete='OFF' type='text' id='dlvm_packing_weight' style='text-align:center' placeholder='* ระบุน้ำหนัก (KG) *'></td></tr>" +
					  "<tr><td align=center><b>Packing By</b></td></tr>" +
					  "<tr><td align=center>" +
					  "<select id='dlvm_packing_by'>" +
					  "<?php echo $worker_by?>" +
					  "</select></tr>" +
					  "<tr><td align=center><b>สถานที่วาง Package</b></td></tr>" +
					  "<tr><td align=center><input autocomplete='OFF' type='text' id='dlvm_packing_location' maxlength=30 style='text-align:center'></td></tr>" +
					  "<tr><td align=center><font color=red>ท่านต้องการทำรายการนี้ต่อ ไช่หรือไม่ ?</font></td></tr>"
			   
			bootbox.confirm({							
				message: "<center>" + confirm_info + "</center>",
				buttons: {
					confirm: {
						label: 'Yes',
						className: 'btn-success'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result) {
						var errorflag2 = false;
						var errortxt2 = "";
						dlvm_packing_weight = document.getElementById('dlvm_packing_weight').value
						dlvm_packing_by = document.getElementById('dlvm_packing_by').value
						dlvm_packing_location = document.getElementById('dlvm_packing_location').value
						if (dlvm_packing_weight == "" || !isnumeric(dlvm_packing_weight)) {
							if (errortxt2 != "") {errortxt2 = errortxt2 + "\n";}
							errorflag2 = true;					
							errortxt2 = errortxt2 + "กรุณาระบุ นำหนัก เป็นตัวเลขค่ะ";
						}
						if (dlvm_packing_by == "") {
							if (errortxt2 != "") {errortxt2 = errortxt2 + "\n";}
							errorflag2 = true;					
							errortxt2 = errortxt2 + "กรุณาระบุ ผู้ทำการเตียม ค่ะ";
						}
						if (dlvm_packing_location == "") {
							if (errortxt2 != "") {errortxt2 = errortxt2 + "\n";}
							errorflag2 = true;					
							errortxt2 = errortxt2 + "กรุณาระบุ ที่วาง Package ค่ะ";
						}
						if (errorflag2) {
							alert(errortxt2);
							return;
						}
						else {
							frm_dlvm_delivery.dlvm_packing_weight.value = dlvm_packing_weight;
							frm_dlvm_delivery.dlvm_packing_by.value = dlvm_packing_by;
							frm_dlvm_delivery.dlvm_packing_location.value = dlvm_packing_location;
							var result_text="";
							$.ajax({
								beforeSend: function () {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show();/*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/dlvmdeliverypost.php',
								data: $('#frm_dlvm_delivery').serialize(),
								timeout: 50000,
								error: function(xhr, error){
									showmsg('['+xhr+'] '+ error);
								},
								success: function(result) {	
									var json = $.parseJSON(result);
									if (json.res == '0') {
										showmsg(json.err);
									}
									else {
										result_text += "<span style='color:green'><h3>[ทำรายการสำเร็จค่ะ]</h3>";
										if (json.err!="") {
											result_text +="\n"+json.err;
											bootbox.alert(result_text, function(){ 
												//$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
												$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
											});
										}
										else {
											//$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
											$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
										}
									}
								},
								complete: function () {
									$("#requestOverlay").remove();/*Remove overlay*/
								}
							});
						}
					} else {
						return;	
					}										
				}
			});								
		}
	}
	function forceclosepost($sptm_nbr) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {								
			if (xhttp.readyState == 4 && xhttp.status == 200) {	
				if (xhttp.responseText != "OK") {
					if (errortxt != "") {errortxt = errortxt + "<br>";}
					errorflag = true;					
					errortxt = errortxt + "ไม่สามารถปิดใบเบิกใบนี้ได้เนื่องจากยังมี Package ที่เกี่ยวข้องที่กำลังดำเนินการอยู่ค่ะ";
				}	
			}			
		}
		xhttp.open("POST", "../_chk/chkdlvmstatus.php",false);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
		xhttp.setRequestHeader("Pragma", "no-cache");
		xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
		xhttp.send("sptmnumber="+$sptm_nbr);
		
		if (errorflag) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {
			
			var confirm_info = "<table align=center>" +
				"<tr><td style='text-align:center;background:red;color:white;border-radius:4px'><h4>** ปิดใบเบิกตัวอย่างกรณีไม่มีสินค้าในคลัง **</td></tr>" +
				"<tr><td align=center><font color=blue><h4>ระบบจะแจ้งข้อมูล <font color=red>ไม่มีสินค้า</font> ไปยังผู้ขอเบิก</h4></font></td></tr>" +
				"<tr><td align=center><font color=red><b>CLOSE COMMENT</b></font></td></tr>" +
				"<tr><td align=center><textarea id='sptm_close_cmmt' cols=50 rows=3></textarea></td></tr>" +
				"<tr><td align=center><font color=red>ท่านต้องการทำรายการนี้ต่อ ไช่หรือไม่ ?</font></td></tr>"
			   
			bootbox.confirm({							
				message: "<center>" + confirm_info + "</center>",
				buttons: {
					confirm: {
						label: 'Yes',
						className: 'btn-success'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result) {
						frm_sptm_force_close.sptm_force_close_cmmt.value = document.getElementById('sptm_close_cmmt').value;
						var result_text="";
						$.ajax({
							beforeSend: function () {
								$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
								$("#requestOverlay").show();
							},
							type: 'POST',
							url: '../serverside/sptmforceclosepost.php',
							data: $('#frm_sptm_force_close').serialize(),
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
										bootbox.alert(result_text, function(){ 
											$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
										});
									}
									else {
										$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
									}
								}
							},
							complete: function () {
								$("#requestOverlay").remove();
							}
						});
					} else {
						return;	
					}										
				}
			});	
			
		}
	}
	function forceclosereopenpost($sptm_nbr) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		if (errorflag) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {
			var confirm_info = "<table align=center>" +
				"<tr><td style='text-align:center;background:green;color:white;border-radius:4px'><h4>** เปิดใบเบิกตัวอย่างอีกครั้ง **</td></tr>" +
				"<tr><td align=center><font color=green><b>REOPEN COMMENT</b></font></td></tr>" +
				"<tr><td align=center><textarea id='sptm_reopen_cmmt' cols=50 rows=3></textarea></td></tr>" +
				"<tr><td align=center><font color=red>ท่านต้องการทำรายการนี้ต่อ ไช่หรือไม่ ?</font></td></tr>"
			   
			bootbox.confirm({							
				message: "<center>" + confirm_info + "</center>",
				buttons: {
					confirm: {
						label: 'Yes',
						className: 'btn-success'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result) {
						frm_sptm_force_close_reopen.sptm_force_close_reopen_cmmt.value = document.getElementById('sptm_reopen_cmmt').value;
						var result_text="";
						$.ajax({
							beforeSend: function () {
								$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
								$("#requestOverlay").show();
							},
							type: 'POST',
							url: '../serverside/sptmforceclosereopenpost.php',
							data: $('#frm_sptm_force_close_reopen').serialize(),
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
										bootbox.alert(result_text, function(){ 
											$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
										});
									}
									else {
										$(location).attr('href', 'sptmall.php?activeid='+json.nbr+'&pg='+json.pg);
									}
								}
								
							},
							complete: function () {
								$("#requestOverlay").remove();
							}
						});
					} else {
						return;	
					}										
				}
			});
		}
	}
	function createmanualfoc(sptd_mat_code,sptd_id,sptbc_id,sptd_qty_await,sptd_unit_code) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		if(confirm('ท่านต้องการส่งรายการนี้ไปสร้าง FOC ใช่หรือไม่ ?')) {
			document.frm_create_manual_foc.sptd_mat_code.value = sptd_mat_code;
			document.frm_create_manual_foc.sptd_id.value = sptd_id;
			document.frm_create_manual_foc.sptbc_id.value = sptbc_id;
			document.frm_create_manual_foc.sptd_qty_await.value = sptd_qty_await;
			document.frm_create_manual_foc.sptd_unit_code.value = sptd_unit_code;
			var result_text="";
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/focpost.php',
				data: $('#frm_create_manual_foc').serialize(),
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
								$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
								location.reload();
							});
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							location.reload();
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		else {
			return;			
		}	
	}
	function sptd_save_dn() {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		var sptd_list_id = "";	
		var sptd_list_dn = "";
		var sptd_list_note = "";
		var sptd_list_dn_status = "";
		var dn_cnt = 0;
		$('input[name^=sptd_id_dn]').each(function() {
			if (sptd_list_id != "") { sptd_list_id = sptd_list_id + ","; }
			sptd_list_id = sptd_list_id + this.value;
		});
		$('input[name^=sptd_dn]').each(function() {
			if (sptd_list_dn != "") { sptd_list_dn = sptd_list_dn + ","; }
			if (this.value.trim() == "") {
				sptd_list_dn = sptd_list_dn + "blank";
			}
			else {
				sptd_list_dn = sptd_list_dn + this.value;
			}
			dn_cnt++;
		});
		$('input[name^=sptd_oper_note]').each(function() {
			if (sptd_list_note != "") { sptd_list_note = sptd_list_note + "*^*"; }
			if (this.value.trim() == "") {
				sptd_list_note = sptd_list_note + "blank";
			}
			else {
				sptd_list_note = sptd_list_note + this.value;
			}
		});
		$('select[name^=sptd_dn_status]').each(function() {
			if (sptd_list_dn_status != "") { sptd_list_dn_status = sptd_list_dn_status + ","; }
			if (this.value.trim() == "") {
				sptd_list_dn_status = sptd_list_dn_status + "blank";
			}
			else {
				sptd_list_dn_status = sptd_list_dn_status + this.value;
			}
		});
		
		//sptbc_det
		var sptbc_sptd_list_id = "";	
		var sptbc_list_id = "";	
		var sptbc_list_dn = "";
		var sptbc_list_note = "";
		var sptbc_list_dn_status = "";
		var sptbc_dn_cnt = 0;
		$('input[name^=sptbc_sptd_id_dn]').each(function() {
			if (sptbc_sptd_list_id != "") { sptbc_sptd_list_id = sptbc_sptd_list_id + ","; }
			sptbc_sptd_list_id = sptbc_sptd_list_id + this.value;
		});
		$('input[name^=sptbc_id_dn]').each(function() {
			if (sptbc_list_id != "") { sptbc_list_id = sptbc_list_id + ","; }
			sptbc_list_id = sptbc_list_id + this.value;
		});
		$('input[name^=sptbc_dn]').each(function() {
			if (sptbc_list_dn != "") { sptbc_list_dn = sptbc_list_dn + ","; }
			if (this.value.trim() == "") {
				sptbc_list_dn = sptbc_list_dn + "blank";
			}
			else {
				sptbc_list_dn = sptbc_list_dn + this.value;
			}
			sptbc_dn_cnt++;
		});
		$('input[name^=sptbc_oper_note]').each(function() {
			if (sptbc_list_note != "") { sptbc_list_note = sptbc_list_note + "*^*"; }
			if (this.value.trim() == "") {
				sptbc_list_note = sptbc_list_note + "blank";
			}
			else {
				sptbc_list_note = sptbc_list_note + this.value;
			}
		});
		$('select[name^=sptbc_dn_status]').each(function() {
			if (sptbc_list_dn_status != "") { sptbc_list_dn_status = sptbc_list_dn_status + ","; }
			if (this.value.trim() == "") {
				sptbc_list_dn_status = sptbc_list_dn_status + "blank";
			}
			else {
				sptbc_list_dn_status = sptbc_list_dn_status + this.value;
			}
		});
		//
		
		if (errorflag) {			
			document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
			$("#myModal").modal("show");
		}
		else {			
			//alert(sptd_list_note);
			document.frm_save_dn.sptd_list_id.value = sptd_list_id;
			document.frm_save_dn.sptd_list_dn.value = sptd_list_dn;
			document.frm_save_dn.sptd_list_note.value = sptd_list_note;
			document.frm_save_dn.sptd_list_dn_status.value = sptd_list_dn_status;
			//sptbc_det
			document.frm_save_dn.sptbc_sptd_list_id.value = sptbc_sptd_list_id;
			document.frm_save_dn.sptbc_list_id.value = sptbc_list_id;
			document.frm_save_dn.sptbc_list_dn.value = sptbc_list_dn;
			document.frm_save_dn.sptbc_list_note.value = sptbc_list_note;
			document.frm_save_dn.sptbc_list_dn_status.value = sptbc_list_dn_status;
			//
			document.frm_save_dn.sptm_oper_note.value = $('#sptm_oper_note').val();
			document.frm_save_dn.submit();					
		}
	}
	function copypackagepost(sptm_src,sptm_desc) {
		var errorflag = false;
		var errortxt = "";
		document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
		
		if(confirm('ท่านต้องการ Copy Package จากใบเบิกหมายเลข  ' + sptm_src + ' ใช่หรือไม่ ?')) {
			document.frm_copy_package.src_sptm_nbr.value = sptm_src;
			document.frm_copy_package.desc_sptm_nbr.value = sptm_desc;
			var result_text="";
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/sptnpdcopypackagepost.php',
				data: $('#frm_copy_package').serialize(),
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
								$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
								location.reload();
							});
						}
						else {
							$(location).attr('href', 'sptdmnt.php?sptmnumber='+json.nbr+'&pg='+json.pg);
							location.reload();
						}
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		else {
			return;			
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
	function toggleDisplay(divId) {
	  var div = document.getElementById(divId);	  
	  div.style.display = (div.style.display=="block" ? "none" : "block");
	}
	</script>
	<style>
		.modal {		
			z-index: 1050;
			width: 800px;
			margin-left: -400px; /* Half the width */
			margin-top: -300px; /*Half the height */
			overflow: auto;
		}
		.active a {
		  background: red !important;
		  color: white !important;
		  font-weight: bold !important;
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
								<tr bgcolor="orange">
									<td style="padding-left:5px;"><h3>ใบเบิกกระเบื้องตัวอย่าง</h3></td>
									<td align=right>
									<?php if ($can_force_close) { ?>
										<div class="btn btn-small btn-danger" style="color:black;margin-top:5px; margin-bottom:10px; width: 150px;" onclick='javascript:forceclosepost("<?php echo $sptm_nbr?>");'>
											<span style="color:white">ปิดใบเบิก (กรณีไม่มีสินค้าในคลัง)</span>													
										</div>
									<?php }?>
									<?php if ($can_force_close_reopen) { ?>
										<div class="btn btn-small btn-success" style="color:black;margin-top:5px; margin-bottom:10px; width: 180px;" onclick='javascript:forceclosereopenpost("<?php echo $sptm_nbr?>");'>
											<span style="color:white">เปิดใบเบิกอีกครั้ง (ที่ห้องตัวอย่างปิดไป)</span>													
										</div>
									<?php }?>
									</td>
								</tr>	
								<tr><td height="2px" colspan=2></td><tr>
								<tr>
									<td colspan=2>
										<fieldset style="border-radius:4px;width:98%">
										<legend style="align:left"><b>ข้อมูลลูกค้า:</b></legend>
										<center>
										<table class="table-bordered" border=0 width="100%" cellpadding=3 cellspacing=0>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>หมายเลขใบเบิก:</b></td>
												<td width=25% class="text_s_disable"><h3><?php echo $sptm_nbr . " " . $sptm_copy_refer_text?></h3></td>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>วันที่ขอเบิก:</b></td>
												<td class="text_s_disable"><?php echo dmytx($sptm_req_date)?></td>
											</tr>
											<tr>
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>ชื่อผู้ขอเบิก:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_req_by_name . " " . $sptm_tel_contact;?></b></td>	
												<td style="text-align:right; width: 150px; vertical-align: middle;"><b>สังกัดผู้ขอเบิก:</b></td>
												<td class="text_s_disable"><b><?php echo $sptm_req_by_sec . $sptm_req_channel_submit?></b></td>
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
												<td colspan=2>
													<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='sptmall.php?activeid=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
														<i class="icon-white icon-hand-left"></i>
														<span>Black</span>													
													</div>
													<?php if($can_editing) {?>
													<div class="btn btn-small btn-warning" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='sptmedit.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $pg;?>'">
														<i class="icon-white icon-edit"></i>
														<span>Edit</span>													
													</div>
													<!--การ Attach File สามารถใช้งานได้แต่ยังไม่ให้ใช้ถ้าต้องการใช้ให้เอา remark ที่ div ออก-->
													<div class="btn btn-small btn-warning" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='sptmattach.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $pg;?>'">
														<i class="icon-white icon-picture"></i>
														<span>Attach</span>													
													</div>
													<?php } ?>
													
													<?php if($can_npd) {?>
														<?php if($sptm_customer_number == "NPD") {?>
														<div class="btn btn-small btn-default" style="color:red;margin-top:5px; margin-bottom:10px; width: 130px;" onclick="loadresult();window.location.href='sptnpdcust.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $pg;?>'">
															<i class="icon icon-edit"></i>
															<span>Customer NPD <span class='blinking' style='font-weight:bold'>(<?php echo sumnpdcust($sptm_nbr,$conn)?>)</span></span>													
														</div>
														<?php }?>
														<?php if($sptm_customer_number == "NPD_NOCUST") {?>
														<div class="btn btn-small btn-default" style="color:red;margin-top:5px; margin-bottom:10px; width: 130px;">														
															<i class="icon icon-edit"></i>
															<a href="#npd_nocust_customer_total" role="button" data-toggle="modal">
																<span>Customer NPD <span class='blinking' style='font-weight:bold'>(<?php echo $sptm_npd_customer_total?>)</span></span>
															</a>
														</div>
														<?php }?>
													<?php }?>
													
													<?php if ($can_print_rq) {?>
														<div class="btn btn-small btn-info" style="margin-top:5px; margin-bottom:10px; width: 60px;" onclick="printform('sptmformrq.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>')">
															
															<i class="icon-white icon-print"></i>														
															<?php if ($sptm_print_cnt == 0) {?>
																<span>Print</span>		
															<?php } else { ?>
																<span>Re-Print</span>
															<?php }?>														
														</div>
													<?php }?>
													<?php if ($can_print_wo) {?>
														<div class="btn btn-small" style="background:yellow;margin-top:5px; margin-bottom:10px; width: 150px;" onclick="printform('sptmformwo.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&mg=ALL')">
															<i class="icon icon-print"></i>														
															<?php if ($sptm_print_cnt == 0) {?>
																<span>พิมพ์ใบสั่งงานทั้งหมด</span>		
															<?php } else { ?>
																<span>พิมพ์ใบสั่งงานทั้งหมดซ้ำ</span>
															<?php }?>														
														</div>
													<?php }?>
													
												</td>
												<!--td></td-->												
												<td align=right>
													
													<?php if ($can_request_editing) { ?>
													<div class="btn btn-small btn-success" style="color:black;background:orange;margin-top:5px; margin-bottom:10px; width: 170px;" onclick='javascript:requesteditpost("<?php echo encrypt($sptm_nbr,$key)?>");'>
														<i class="icon icon-chevron-left"></i>
														<span>ดึงเอกสารที่ขออนุมัติกลับมาแก้ไข</span>													
													</div>
													<div class="btn btn-small btn-success" style="background:red;margin-top:5px; margin-bottom:10px; width: 130px;" onclick='javascript:requestcancelpost("<?php echo encrypt($sptm_nbr,$key)?>");'>
														<i class="icon-white icon-remove-circle"></i>
														<span>ยกเลิกเอกสารที่ขออนุมัติ</span>													
													</div>
													<?php } ?>
													<?php if ($can_submit) { ?>
													<div class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 60px;" onclick='javascript:submitpost("<?php echo encrypt($sptm_nbr,$key)?>");'>
														<i class="icon-white icon-share"></i>
														<span>Submit</span>													
													</div>
													<?php } ?>
													
													<?php if ($can_npd_copy) { ?>
													<div class="btn btn-small" style="background:red;margin-top:5px; margin-bottom:10px; width: 170px;color:white" onclick="javascript:copypackagepost('<?php echo $sptm_copy_refer?>','<?php echo $sptm_nbr;?>');"> 
														<i class="icon icon-thumbs-up"></i>
														<span><b>Copy Package from Master</b></span>													
													</div>
			
													<?php }?>
													
													<?php if ($can_delivery) { ?>
													<div class="btn btn-small" style="background:yellow;margin-top:5px; margin-bottom:10px; width: 100px;" onclick='javascript:allpackingpost("<?php echo $sptm_nbr;?>");'> 
														<i class="icon icon-thumbs-up"></i>
														<span><b>All Packing</b></span>													
													</div>
													<div class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 100px;" onclick='javascript:deliverypost("<?php echo $sptm_nbr;?>","<?php echo $sptm_npd_control_stock?>","<?php echo $sptm_npd?>","<?php echo $sptm_control_stock?>","<?php echo $accept_neg_stock?>");'> 
														<i class="icon-white icon-share"></i>
														<span>Create Package</span>													
													</div>
													<?php } ?>
													</span>
												</td>
											</tr>
											<?php
											$d=0;
											$sql_dlvm = "SELECT * FROM dlvm_mstr" .
												" INNER JOIN dlvs_mstr ON dlvs_step_code = dlvm_dlvs_step_code ".
												" WHERE dlvm_sptm_nbr = '$sptm_nbr'";
											$result_dlvm = sqlsrv_query( $conn, $sql_dlvm);
											if(sqlsrv_has_rows($result_dlvm)) {
											?>
												<tr style="background: white">
													<td colspan=4>
														<table border=0>
															<tr>
																<td colspan=8>
																	<span style="background:green;border-radius:4px;height:20px;color:white"><b> Package Information: </b></span>
																</td>
															</tr>
															<tr bgcolor="blue" style="font-weight: bold;color: white">
																<td style="text-align:center;width:30px;">ลำดัล</td>
																<td style="text-align:center;width:100px;">Package No</td>
																<td style="text-align:center;width:80px;">Pack By</td>
																<td style="text-align:center;width:50px;">รับ</td>
																<td style="text-align:center;width:50px;">ไม่รับ</td>
																<td style="text-align:center;width:60px;">จำนวนที่ขน</td>
																<td style="text-align:center;width:100px;">สถานะ</td>
																<td style="text-align:center;width:60px;">วันทีสร้าง</td>
																<td style="text-align:center;width:80px;">ทะเบียนรถ</td>
																<td style="text-align:center;width:80px;">เบอร์ติดต่อ</td>
																<td style="text-align:center;width:60px;">วันทีขนส่ง</td>
																<td style="text-align:center;width:60px;">วันที่รับ</td>
															</tr>
															<?php
															$dlvd_qty_total_delivery = 0;
															// $dlvd_qty_total_cancel = 0;
															while($r_dlvm = sqlsrv_fetch_array($result_dlvm, SQLSRV_FETCH_ASSOC)) {
																$dlvm_nbr = $r_dlvm['dlvm_nbr'];
																$dlvm_dlvs_step_code = $r_dlvm['dlvm_dlvs_step_code'];
																$dlvm_postdlv_date = dmydb($r_dlvm['dlvm_postdlv_date'],'y');
																$dlvm_transport_tspm_code = html_quot($r_dlvm['dlvm_transport_tspm_code']);
																$dlvm_transport_car_nbr = html_quot($r_dlvm['dlvm_transport_car_nbr']);
																$dlvm_transport_date = dmydb($r_dlvm['dlvm_transport_date'],'y');
																$dlvm_transport_driver_tel = html_quot($r_dlvm['dlvm_transport_driver_tel']);
																$dlvm_ivm_print_date  = dmydb($r_dlvm['dlvm_ivm_print_date'],'y');
																$dlvm_receive_date = dmytx($r_dlvm['dlvm_receive_date']);
																$dlvs_step_name = $r_dlvm['dlvs_step_name'];
																$dlvm_tspm_name = findsqlval("tspm_mstr","tspm_name","tspm_code",$dlvm_transport_tspm_code,$conn);
																$dlvm_packing_by = $r_dlvm['dlvm_packing_by'];
																$dlvm_packing_by_name = findsqlval("worker_mstr","worker_name","worker_code",$dlvm_packing_by,$conn);
																//
																
																$dlvd_qty_delivery = 0;
																$dlvd_qty_received = 0;
																$dlvd_qty_not_received = 0;
																
																$sql_dlvd = "SELECT * FROM dlvd_det WHERE dlvd_dlvm_nbr = '$dlvm_nbr'";
																$result_dlvd = sqlsrv_query( $conn, $sql_dlvd );											
																while($r_dlvd = sqlsrv_fetch_array($result_dlvd, SQLSRV_FETCH_ASSOC)) {	
																	$dlvd_qty = $r_dlvd['dlvd_qty'];
																	$dlvd_receive_status = $r_dlvd['dlvd_receive_status'];
																	if ($dlvd_receive_status == 'Y') { 
																		$dlvd_qty_received = $dlvd_qty_received + $dlvd_qty; 
																	}
																	if ($dlvd_receive_status == 'N') { 
																		$dlvd_qty_not_received = $dlvd_qty_not_received + $dlvd_qty; 
																	}
																	$dlvd_qty_delivery = $dlvd_qty_delivery + $dlvd_qty;
																}
																//
																if ($dlvm_dlvs_step_code != '80') {
																	$dlvd_qty_total_delivery = $dlvd_qty_total_delivery + $dlvd_qty_delivery;
																} 
																// else {
																	// $dlvd_qty_total_cancel = $dlvd_qty_total_cancel + $dlvd_qty_delivery;
																// }
																$d++;
																?>
																<tr style="background-color: #DAF7A6;">
																	<td style="text-align:center"><?php echo $d;?></td>
																	<td style="text-align:center">
																		<a href='javascript:void(0)' OnClick="dlvmpopup('../sampletile/dlvmdet.php?dlvm_nbr=<?php echo $dlvm_nbr?>','','','','')">
																			<?php echo $dlvm_nbr;?>
																		</a>
																	</td>
																	<td><?php echo $dlvm_packing_by_name;?></td>
																	<td style="text-align:center">
																		<?php if ($dlvd_qty_received > 0) { echo "<span class='bubbletext' style='background:green;color:white'>$dlvd_qty_received</span>"; } else { echo '-';}?>
																	</td>
																	<td style="text-align:center">
																		<?php if ($dlvd_qty_not_received > 0) { echo "<span class='bubbletext' style='background:red;color:white'>$dlvd_qty_not_received</span>"; } else { echo '-';}?>
																	</td>
																	<td style="text-align:center;background:white;<?php if($dlvm_dlvs_step_code == '80') {echo 'color:red;text-decoration: line-through';}?>" class='bubbletext'><?php echo $dlvd_qty_delivery;?></td>
																	<td style="<?php if ($dlvm_dlvs_step_code == '80') {echo 'color:red;';}?>"><?php echo $dlvs_step_name;?></td>
																	<td style="text-align:center"><?php echo $dlvm_postdlv_date;?></td>
																	<td><?php echo $dlvm_transport_car_nbr;?></td>
																	<td><?php echo $dlvm_transport_driver_tel;?></td>
																	<td style="text-align:center"><?php echo $dlvm_ivm_print_date;?></td>
																	<td style="text-align:center"><?php echo $dlvm_receive_date;?></td>
																</tr>
															<?php }?>
															<?php
															$sum_qty_order = sumsptdqty($sptm_nbr,'sptd_qty_order',$conn);
															$sum_qty_nogood = sumsptdqty($sptm_nbr,'sptd_qty_nogood',$conn);
															//if ($sum_qty_order - $dlvd_qty_total_delivery - $dlvd_qty_total_cancel - $sum_qty_nogood > 0) {
															if ($sum_qty_order - $dlvd_qty_total_delivery - $sum_qty_nogood > 0) {
															?>
															<tr style="background-color: #DAF7A6;">
																<td style="text-align:center"><?php echo ++$d;?></td>
																<td style="text-align:center;color:red"><?php echo "Pending ...";?></td>
																<td style="text-align:center"></td>
																<td style="text-align:center"></td>
																<td style="text-align:center"></td>
																<td style="text-align:center;color:red" class='bubbletext'><?php echo ($sum_qty_order - $dlvd_qty_total_delivery);?></td>
																<td style="color:red">** รอทำ Package **</td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tr>
															<?php }?>
															<?php
															if ($sum_qty_nogood > 0) {
															?>
															<tr style="background-color: #DAF7A6;">
																<td style="text-align:center"><?php echo ++$d;?></td>
																<td style="text-align:center;color:red"><?php echo "ไม่มีสินค้า";?></td>
																<td style="text-align:center"></td>
																<td style="text-align:center"></td>
																<td style="text-align:center">-</td>
																<td style="text-align:center;color:red" class='bubbletext'><?php echo ($sum_qty_nogood);?></td>
																<td style="color:red">**ไม่มีสินค้า **</td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tr>
															<?php }?>
														</table>
													</td>
												</tr>
											<?php }?>
										</table>
										</center>
										</fieldset>
									</td>
								</tr>
								<tr><td height="2px" colspan=2></td></tr>
								<tr>
									<td colspan=2>
										<div class="container" style="width:100%">
											<?php if ($can_save_dn) {?>
											<div>
												<table border="0" width="100%" cellspacing="0" cellpadding="4" class="table table-bordered table-condensed">			
													<thead>
													<tr>
														<td colspan=17>
															<div class="btn btn-small" style="float: left;background:blue;color:white;margin-top:5px; margin-bottom:10px; width: 180px;" onclick='javascript:sptd_save_dn();'> 
															<i class="icon-white icon-pencil"></i>
															<span><b>Save DN & NOTE & REMARK</b></span>
															</div>
														</td>
													</tr>
													<tr>
														<td colspan=17>
															<div>
															<b>Remark:</b><br>
															<textarea id="sptm_oper_note" name="sptm_oper_note" rows=3 style="width:500px;color:red"><?php echo $sptm_oper_note?></textarea>
															</div>
														</td>	
													</tr>
												</table>
											</div>
											<?php }?>
											<div>
												<?php if (sumsptddet($sptm_nbr,'MT',$conn) > 0 || inlist("0,10",$sptm_step_code)) {?>
													<div id="product_section">
														<!--กระเบื้องแผ่น-->
														<fieldset style="background-color:white;border-radius:4px;width:98%">
															<legend style="background-color:red;text-align:right;color:white;border-radius:4px;"><b>
															<?php if ($can_print_wo) {?>
																<a href="javascript:void(0)" onclick="printform('sptmformwo.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key); ?>&mg=MT')" style="color:yellow; text-decoration:none;"> ( ** พิมพ์ใบสั่งงาน **)</a>
															<?php }?>
															&nbsp;&nbsp;กระเบื้องแผ่น:</b>
															<?php if ($can_editing) {?>
																<a href="#upload_sptd_product" role="button" style="color:gold; text-decoration:none;" data-toggle="modal"> ( ** Upload **)</a> | 
																<a href="#add_sptd_product" onclick="add_product('','MT')" role="button" style="color:white; text-decoration:none;" data-toggle="modal"> ( ** เพิ่มกระเบื้อง **)</a>
															<?php }?>
															&nbsp;&nbsp;
															</legend>
															<center>
																<?php include("sptdmnt_product.php");?>
															</center>
														</fieldset>
													</div>
													<div style="height:5px">&nbsp;</div>
												<?php }?>
												<?php if (sumsptddet($sptm_nbr,'BS',$conn) > 0 || inlist("0,10",$sptm_step_code)) {?>
													<!--Board Stansard-->
													<div id="bs_section">
														<fieldset style="background-color:white;border-radius:4px;width:98%">
															<legend style="background-color:blue;align:left;color:white;border-radius:4px;"><b>
															<?php if ($can_print_wo) {?>
																<a href="javascript:void(0)" onclick="printform('sptmformwo.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key); ?>&mg=BS')" style="color:yellow; text-decoration:none;"> ( ** พิมพ์ใบสั่งงาน **)</a>
															<?php }?>
															&nbsp;&nbsp;บอร์ดมาตรฐาน:</b>
															<?php if ($can_editing) {?>
																<a href="#add_board_bs"  style="color:white; text-decoration:none;" data-toggle="modal">  ( ** เพิ่มบอร์ด **)</a>
															<?php }?>
															&nbsp;&nbsp;
															</legend>
															<center>
																<?php include("sptdmnt_bs.php");?>
															</center>
														</fieldset>
													</div>
													<div style="height:5px">&nbsp;</div>
												<?php }?>
												<?php if (sumsptddet($sptm_nbr,'BC',$conn) > 0 || inlist("0,10",$sptm_step_code)) {?>
													<!--Board Customize-->
													<div id="bc_section">
														<fieldset style="background-color:white;border-radius:4px;width:98%">
															<legend style="background-color:blue;align:left;color:white;border-radius:4px;"><b>
																<?php if ($can_print_wo) {?>
																	<a href="javascript:void(0)" onclick="printform('sptmformwo.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&mg=BC')" style="color:yellow; text-decoration:none;"> ( ** พิมพ์ใบสั่งงาน **)</a>
																<?php }?>
																&nbsp;&nbsp;บอร์ดปรับแต่ง:</b>
																<?php if ($can_editing) {?>
																	<a href="#add_board_bc" role="button" style="color:white; text-decoration:none;" data-toggle="modal">   ( ** เพิ่มบอร์ด **)</a>
																<?php }?>
																&nbsp;&nbsp;
															</legend>
															<center>
															<?php include("sptdmnt_bc.php");?>
															</center>
														</fieldset>
													</div>
												<?php }?>
											</div>
											<?php if($can_approve) {?>
											<div>					
												<FORM id="frmapprove" name="frmapprove" autocomplete="OFF" method="post">
													<input type="hidden" name="action" value="<?php echo md5('sptmsaveapprove'.$user_login)?>">
													<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
													<input type=hidden name="pg" value="<?php echo $pg?>">
													
													<p style="height:1px"></p>
													<fieldset style="margin-left:2px;border-radius:4px;width:20%;">
													<table border=0>
														<tr>
															<td style="font-size:10pt;background-color:green;color:white;text-align:center;border-radius:4px"><b>Approval</b></td>
														</tr>
														<tr>
															<td class="f_bk8" width="10%" align=center>
																<input type="radio" name="sptm_approve_select" value="30" onclick="RadioHighLightColor(sptm_approve_select,'#00ff00')">&nbsp;<font color=green><b>อนุมัติ</b></font>&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="sptm_approve_select" value="890" onclick="RadioHighLightColor(sptm_approve_select,'red')">&nbsp;<font color=red><b>ไม่อนุมัติ</b></font>&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="sptm_approve_select" value="10" onclick="RadioHighLightColor(sptm_approve_select,'blue')">&nbsp;<font color=blue><b>แก้ไขใหม่</b></font>&nbsp;&nbsp;&nbsp;&nbsp;
															</td>
														</tr>
														<tr>													
															<td class="f_bk8b" align=center>Comment:<br/>
																<textarea class="f_bk9"rows=3 class="inputtext_s form-control" style="min-width: 50%" name="sptm_approve_cmmt"></textarea>
															</td>
														</tr>
														<tr>
															<td align=center>
																<input name="butsave" value="Save >>" type="button" class="f_bk8b" style="width:50pt;color:green;"													 
																OnClick="approvepostform()">
																<input name="butcancel" value="<< Back" type="button" class="f_red8b" style="width:50pt"
																OnClick="javascript:window.location='sptmall.php?activeid=<?php echo $sptm_nbr?>&page=<?php echo $page?>'">
															</td>
														</tr>
													</table>
													</fieldset>
												</FORM>
											</div>
											<?php }?>
										</div>
									</td>
								</tr>
								<tr>
									<td width=100% colspan=2><img src="../_images/action.png" align=absmiddle width=32 height=32>&nbsp;<a href="javascript:toggleDisplay('cmmt')" class="l_bk8"><u>Historical</u></a>
									</td>
								</tr>
								<tr>
									<td width=100% colspan=2>
										<div id=cmmt style="display:none">
										<?php include("sptmstephist.php"); ?>								
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
	
	<div id="npd_nocust_customer_total" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_npd_nocust_customer_total" name="frm_npd_nocust_customer_total" method="post" action="../serverside/npdnocusttotalpost.php">
			<input type="hidden" name="action" value="<?php echo md5('npd_nocust_customer_total'.$user_login)?>">
			<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%">Customer NPD</h3>
			</div>
			<div class="">
				<table width=100%>
					<tr>
						<td align=center><b>จำนวนลูกค้าที่ต้องการรับสินค้า NPD:</b></td>
					</tr>
					<tr>
						<td align=center>
							<input type="text" style="color:red;text-align:center;font-size:12pt;font-weight:bold" name="sptm_npd_customer_total" value="<?php echo $sptm_npd_customer_total?>">
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button id="btn_save_shipment" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="npd_nocust_customer_total_post('<?php echo $sptm_npd_brand?>')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>
	</div>
	
	<form id="frm_submit" name="frm_submit" method="post">
		<input type="hidden" name="action" value="<?php echo md5('submit'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_requestedit" name="frm_requestedit" method="post">
		<input type="hidden" name="action" value="<?php echo md5('requestedit'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_requestcancel" name="frm_requestcancel" method="post">
		<input type="hidden" name="action" value="<?php echo md5('requestcancel'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frmallpacking" name="frmallpacking" method="post">
		<input type="hidden" name="action" value="<?php echo md5('dlvm_allpacking'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_dlvm_delivery" name="frm_dlvm_delivery" method="post">
		<input type="hidden" name="action" value="<?php echo md5('dlvm_delivery'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="dlvm_packing_weight">
		<input type="hidden" name="dlvm_packing_by">
		<input type="hidden" name="dlvm_packing_location">
		<!--input type="hidden" name="dlvm_postdlv_cmmt"-->
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_sptm_force_close" name="frm_sptm_force_close" method="post">
		<input type="hidden" name="action" value="<?php echo md5('sptm_force_close'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="sptm_force_close_cmmt">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_sptm_force_close_reopen" name="frm_sptm_force_close_reopen" method="post">
		<input type="hidden" name="action" value="<?php echo md5('sptm_force_close_reopen'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="sptm_force_close_reopen_cmmt">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_create_manual_foc" name="frm_create_manual_foc" method="post">
		<input type="hidden" name="action" value="<?php echo md5('create_manual_foc'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="sptm_expect_receipt_date" value="<?php echo $sptm_expect_receipt_date?>">
		<input type="hidden" name="sptd_mat_code">
		<input type="hidden" name="sptd_id">
		<input type="hidden" name="sptbc_id">
		<input type="hidden" name="sptd_qty_await">
		<input type="hidden" name="sptd_unit_code">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form name="frm_save_dn" autocomplete=OFF method="post" action="../serverside/sptdsavednpost.php">
		<input type="hidden" name="action" value="<?php echo md5('save_dn'.$user_login)?>">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr?>">
		<input type="hidden" name="sptd_list_id">
		<input type="hidden" name="sptd_list_dn">
		<input type="hidden" name="sptd_list_note">
		<input type="hidden" name="sptd_list_dn_status">
		
		<input type="hidden" name="sptbc_sptd_list_id">
		<input type="hidden" name="sptbc_list_id">
		<input type="hidden" name="sptbc_list_dn">
		<input type="hidden" name="sptbc_list_note">
		<input type="hidden" name="sptbc_list_dn_status">
		
		<input type="hidden" name="sptm_oper_note">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_copy_package" name="frm_copy_package" autocomplete=OFF method="post">
		<input type="hidden" name="action" value="<?php echo md5('npd_copy_package'.$user_login)?>">
		<input type="hidden" name="src_sptm_nbr">
		<input type="hidden" name="desc_sptm_nbr">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	</body>
</html>
