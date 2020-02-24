<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$max_invoice_line = 8;

$action = mssql_escape($_REQUEST['action']);
$in_dlvm10_tmpsubmit = mssql_escape($_POST["in_dlvm10_tmpsubmit"]);
$in_dlvm10_dlvm_nbr = mssql_escape($_POST["in_dlvm10_dlvm_nbr"]);
$in_dlvm10_sptm_nbr = mssql_escape($_POST["in_dlvm10_sptm_nbr"]);
$in_dlvm10_province = mssql_escape($_POST["in_dlvm10_province"]);
$in_dlvm10_customer = mssql_escape($_POST["in_dlvm10_customer"]);
$in_dlvm10_sptm_delivery_mth = mssql_escape($_POST["in_dlvm10_sptm_delivery_mth"]);

If ($in_dlvm10_tmpsubmit == "") {
	$in_dlvm10_tmpsubmit = $_COOKIE['in_dlvm10_tmpsubmit'];	
	$in_dlvm10_dlvm_nbr = $_COOKIE['in_dlvm10_dlvm_nbr'];
	$in_dlvm10_sptm_nbr = $_COOKIE['in_dlvm10_sptm_nbr'];
	$in_dlvm10_province = $_COOKIE['in_dlvm10_province'];
	$in_dlvm10_customer = $_COOKIE['in_dlvm10_customer'];
	$in_dlvm10_sptm_delivery_mth = $_COOKIE['in_dlvm10_sptm_delivery_mth'];
}
else {		
	setcookie("in_dlvm10_tmpsubmit","",0);
	setcookie("in_dlvm10_dlvm_nbr","",0);
	setcookie("in_dlvm10_sptm_nbr","",0);
	setcookie("in_dlvm10_province","",0);
	setcookie("in_dlvm10_customer","",0);
	setcookie("in_dlvm10_sptm_delivery_mth","",0);
}
	
//
if ($in_dlvm10_dlvm_nbr != "") {
	if ($criteria_dlvm10 != "") { $criteria_dlvm10 = $criteria_dlvm10 . " AND "; }
	$criteria_dlvm10 = $criteria_dlvm10 . " dlvm_nbr like '%$in_dlvm10_dlvm_nbr%'";
}
setcookie("in_dlvm10_dlvm_nbr", $in_dlvm10_dlvm_nbr,0);	
//
if ($in_dlvm10_sptm_nbr != "") {
	if ($criteria_dlvm10 != "") { $criteria_dlvm10 = $criteria_dlvm10 . " AND "; }
	$criteria_dlvm10 = $criteria_dlvm10 . " sptm_nbr like '%$in_dlvm10_sptm_nbr%'";
}
setcookie("in_dlvm10_sptm_nbr", $in_dlvm10_sptm_nbr,0);	
//
if ($in_dlvm10_province != "") {
	if ($criteria_dlvm10 != "") { $criteria_dlvm10 = $criteria_dlvm10 . " AND "; }
	$criteria_dlvm10 = $criteria_dlvm10 . " (sptm_customer_province like '%$in_dlvm10_province%' OR sptm_customer_amphur like '%$in_dlvm10_province%')";
}
setcookie("in_dlvm10_province", $in_dlvm10_province,0);	
//
if ($in_dlvm10_customer != "") {
	if ($criteria_dlvm10 != "") { $criteria_dlvm10 = $criteria_dlvm10 . " AND "; }
	$criteria_dlvm10 = $criteria_dlvm10 . " (customer_number like '%$in_dlvm10_customer%' OR customer_name1 like '%$in_dlvm10_customer%' OR sptm_customer_dummy like '%$in_dlvm10_customer%')";
}
setcookie("in_dlvm10_customer", $in_dlvm10_customer,0);	
//
if ($in_dlvm10_sptm_delivery_mth != "") {
	if ($criteria_dlvm10 != "") { $criteria_dlvm10 = $criteria_dlvm10 . " AND "; }
	$criteria_dlvm10 = $criteria_dlvm10 . " sptm_delivery_mth like '%$in_dlvm10_sptm_delivery_mth%'";
}
setcookie("in_dlvm10_sptm_delivery_mth", $in_dlvm10_sptm_delivery_mth,0);
//
if ($criteria_dlvm10 != "") {
	$criteria_dlvm10 = "WHERE dlvm_dlvs_step_code = '10' AND sptm_customer_number <> 'NPD_NOCUST' AND dlvm_nbr NOT IN (SELECT dlms_dlvm_nbr from dlms_det WHERE dlms_user_login = '$user_login') AND " . $criteria_dlvm10;
}
else {
	$criteria_dlvm10 = "WHERE dlvm_dlvs_step_code = '10' AND sptm_customer_number <> 'NPD_NOCUST' AND dlvm_nbr NOT IN (SELECT dlms_dlvm_nbr from dlms_det WHERE dlms_user_login = '$user_login')";
}

$can_dlms = false;
if (inlist($user_role,"CS")) {
	$can_dlms = true;
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
	<script type="text/javascript">
		$(window).load(function () {
			$("#chkall").click(function(){
				$("input[id*='dlvm10_nbr']:checkbox").prop('checked', $(this).prop('checked'));			
			});			
		});
		
	</script>
	<script language="javascript">
		function setvalue_refamt(dlms_id,dlms_dlvm_nbr,dlms_cust_name,dlms_delivery_mth,dlms_transport_ref_no,dlms_transport_amt) {
			$('#div_dlms_dlvm_nbr').html("<b>"+dlms_dlvm_nbr+"</b>");
			$('#div_dlms_cust_name').html("<b>"+dlms_cust_name+"</b>");
			$('#div_dlms_delivery_mth').html("<b>"+dlms_delivery_mth+"</b>");
			document.frm_dlvm_edit_refamt.dlms_id.value = dlms_id;
			document.frm_dlvm_edit_refamt.dlvm_transport_ref_no.value = dlms_transport_ref_no;
			document.frm_dlvm_edit_refamt.dlvm_transport_amt.value = dlms_transport_amt;
		}
		function dlvm_edit_refamt_post() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var dlvm_transport_amt = document.frm_dlvm_edit_refamt.dlvm_transport_amt.value;
			if (dlvm_transport_amt!="") {
				if (!isnumeric(dlvm_transport_amt)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ ค่าใช้จ่ายในการขนส่งเป็นตัวเลขเท่านั้น";
				}
			}
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {			
				document.frm_dlvm_edit_refamt.submit();				
			}
			
		}
		function selecteddlvm10post() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var dlvm10_nbr_list = "";
			var dlvm10_cnt = 0;
			$('input[name^=dlvm10_nbr_]').each(function() {
				if (this.checked) {
					if (dlvm10_nbr_list != "") { dlvm10_nbr_list = dlvm10_nbr_list + ","; }
					dlvm10_nbr_list = dlvm10_nbr_list + this.value;
					dlvm10_cnt++;
				}
			});
			
			if (dlvm10_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกรายการที่ต้องการก่อนค่ะ";
			}
			
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {			
				document.frm_selected_dlvm10.dlvm10_nbr_list.value = dlvm10_nbr_list;
				document.frm_selected_dlvm10.submit();				
			}
		}
		function del_dlvm10_selected(dlms_id) {
			document.frm_del_dlms.dlms_id.value = dlms_id;	
			document.frm_del_dlms.submit();
		}
		function del_all_dlms() {
			if(confirm('ท่านต้องการล้างข้อมูลหน้านี้ใช่หรือไม่ ?')) {	
				document.frm_del_all_dlms.submit();
			}	
		}
		function add_dlvm10_selected(dlvm10_nbr) {
			document.frm_add_dlvm10.dlvm10_nbr.value = dlvm10_nbr;				
			document.frm_add_dlvm10.submit();
		}
		//Shipment
		function dlvm_multishipmentpost() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			dlvm_transport_tspm_code = document.frm_dlvm_multishipment.dlvm_transport_tspm_code.value;
			dlvm_transport_tspm_other = document.frm_dlvm_multishipment.dlvm_transport_tspm_other.value;
			//dlvm_transport_ref_no = document.frm_dlvm_multishipment.dlvm_transport_ref_no.value;
			dlvm_transport_driver_name = document.frm_dlvm_multishipment.dlvm_transport_driver_name.value;
			//dlvm_transport_amt = document.frm_dlvm_multishipment.dlvm_transport_amt.value;
			dlvm_transport_car_nbr = document.frm_dlvm_multishipment.dlvm_transport_car_nbr.value;
			dlvm_transport_driver_tel = document.frm_dlvm_multishipment.dlvm_transport_driver_tel.value;
			
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
				/*
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
				*/
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
			// if (dlvm_transport_amt != "") {
				// if (!isnumeric(dlvm_transport_amt)) {
					// if (errortxt!="") {errortxt = errortxt + "<br>";}
					// errorflag = true;
					// errortxt = errortxt + "กรุณาระบุ ค่าใช้จ่ายในการขนส่งเป็นตัวเลขเท่านั้น";
				// }
			// }
			/*
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
			*/
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
					url: '../serverside/dlmsshipmentpost.php',
					data: $('#frm_dlvm_multishipment').serialize(),
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
									$(location).attr('href', 'dlmsmnt.php?pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'dlmsmnt.php?pg='+json.pg);
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
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
		}		
		function showdata() {			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {	
				loadresult()
				document.frm.submit();									
			}
		}
		
		function showfocmdata() {
			loadresult()
			document.frm_focm.submit();
		}
		
		function showfocddata() {
			loadresult()
			document.frm_focd.submit();
		}
		function gotopage(mypage) {					
			loadresult()
			document.frm_focd.dlvm10_pg.value=mypage;
			document.frm_focd.submit();
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
		function winpopup(prgname) {				
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
			var myWindow=window.open(prgname,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
		}
	
</script>	
</head>
<body>		
	<?php
	//Page ของ focd_det
	//$sql_cnt = "SELECT * FROM dlvm_mstr WHERE dlvm_dlvs_step_code = '20'";
	$sql_cnt = "SELECT * from dlvm_mstr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
		" INNER JOIN customer ON customer_number = sptm_customer_number " .
		$criteria_dlvm10;

	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	
	
	$dlvm10_pagesize = 15;
	$dlvm10_totalrow = $max;
	$dlvm10_totalpage = ($dlvm10_totalrow/$dlvm10_pagesize) - (int)($dlvm10_totalrow/$dlvm10_pagesize);
	if ($dlvm10_totalpage > 0) {
		$dlvm10_totalpage = ((int)($dlvm10_totalrow/$dlvm10_pagesize)) + 1;
	} else {
		$dlvm10_totalpage = (int)$dlvm10_totalrow/$dlvm10_pagesize;
	}					
	if ($_REQUEST["dlvm10_pg"]=="") {
		$dlvm10_currentpage = 1;	
		$dlvm10_end_row = ($dlvm10_currentpage * $dlvm10_pagesize) - 1;
		if ($dlvm10_end_row > ($dlvm10_totalrow - 1)) { $dlvm10_end_row = $dlvm10_totalrow - 1; }
		$dlvm10_start_row = 0;
	} else {
		$dlvm10_currentpage = $_REQUEST["dlvm10_pg"];
		if ((int)$dlvm10_currentpage < 1) { $dlvm10_currentpage = 1; }
		if ((int)$dlvm10_currentpage > (int)$dlvm10_totalpage) { $dlvm10_currentpage = $dlvm10_totalpage; }
		$dlvm10_end_row = ($dlvm10_currentpage * $dlvm10_pagesize) - 1;
		$dlvm10_start_row = $dlvm10_end_row - $dlvm10_pagesize + 1;
		if ($dlvm10_end_row > ($dlvm10_totalrow - 1)) { $dlvm10_end_row = $dlvm10_totalrow - 1; }					
	}

	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($dlvm10_totalpage) <= (int)($maxpage)) {
		$maxpage = $dlvm10_totalpage;
	}		
	if ($dlvm10_currentpage < $maxpage) {
		$dlvm10_start_page = 1;
		$dlvm10_end_page = $maxpage;	
	} else {		
		$dlvm10_start_page = $dlvm10_currentpage - $slidepage;
		$dlvm10_end_page = $dlvm10_currentpage + $slidepage;
		if ($dlvm10_start_page <= 1) {
			$dlvm10_start_page = 1;
			$dlvm10_end_page = $maxpage;
		} 
		if ($dlvm10_end_page >= $dlvm10_totalpage) {
			$dlvm10_start_page = $dlvm10_totalpage - $maxpage + 1;
			$dlvm10_end_page = $dlvm10_totalpage;
		}
	}
	
	?>	
	<div id="result"></div>
	<div>			
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>				
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0	width="100%" cellpadding="1" cellspacing="0">				
						<tr style="height:30px" bgcolor="lightgray">
							<td>
								<img src='../_images/delivery-icon.png' width=32><span style='font-size:11pt'><b>จัดรถตามทะเบียน:  </b></span>
							</td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% colspan=2>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>
										
										<td bgcolor="white" width=50% valign=top>
											<fieldset style="background-color:white;border-radius:4px;width:97%">
												<legend style="background-color:red;color:white;border-radius:4px;">Package ที่รอการจัดเที่ยว</legend>
												<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
													<form name="frm_focd" method="post" autocomplete=OFF>
													<input type="hidden" name="in_dlvm10_tmpsubmit" value="search">
													<input type="hidden" name="dlvm10_pg">
													<tr>
														<td colspan=5 align=right>
															<?php if ($can_dlms) { ?>
															<button type="button" class="btn btn-mini" onclick="selecteddlvm10post()" style='background:green;color:white;font-size:8pt;'>>> เพิ่มรายการที่เลือก</button>														
															<?php }?>
														</td>
													</tr>
													<tr>
														<td valign=top style="width:110px;margin:auto"><b>Package No:</b>
															<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_dlvm10_dlvm_nbr" value="<?php echo $in_dlvm10_dlvm_nbr?>">
														</td>
														<td valign=top style="width:110px;margin:auto"><b>หมายเลขใบเบิก:</b>
															<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_dlvm10_sptm_nbr" value="<?php echo $in_dlvm10_sptm_nbr?>">
														</td>
														<td valign=top style="width:110px;"><b>จังหวัด/อำเภอ:</b>
															<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_dlvm10_province" value="<?php echo $in_dlvm10_province?>">
															
														</td>
														<td valign=top style="width:110px;"><b>ชื่อ/รหัสลูกค้า:</b>
															<input type='text' style='width: 80px;font-size:8pt;color:blue;margin:auto;' name="in_dlvm10_customer" value="<?php echo $in_dlvm10_customer?>">
															
														</td>
														<td valign=top><b>วิธีการจัดส่ง:</b>
															<select name="in_dlvm10_sptm_delivery_mth" class="inputtext_s" style="width: 160px;" onchange="setdefaultampv(this.value)">
																<option value="">-เลือก-</option>
																<?php
																$sql_delivery = "SELECT delivery_code,delivery_name FROM delivery_mth WHERE delivery_active = 1 order by delivery_seq";
																$result_delivery_list = sqlsrv_query( $conn,$sql_delivery);																													
																while($r_delivery_list=sqlsrv_fetch_array($result_delivery_list, SQLSRV_FETCH_ASSOC)) {
																	$delivery_code = $r_delivery_list['delivery_code'];
																	$delivery_name = html_quot($r_delivery_list['delivery_name']);
																	?>
																	<option value="<?php echo $delivery_code;?>" <?php if ($in_dlvm10_sptm_delivery_mth == $delivery_code) {echo "selected";}?>><?php echo $delivery_name;?></option>
																	<?php
																}
																?>
															</select>
															<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
														</td>
													</tr>
													<tr bgcolor="lightgray">
														<td colspan=3>
															(<font color=red><?php echo $dlvm10_totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $dlvm10_totalrow;?></font>&nbsp;items)								
															<b>Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotopage(document.frm_focd.jumto.value)">
															&nbsp;
														</td>
	
														<td colspan=2 class="f_bk8" align=right>
															<?php
															if ($dlvm10_start_page > 1) {																				
																echo "<A href='javascript:gotopage(1)' class='paging'>First</a>&nbsp;";
															}														
															for ($pg=$dlvm10_start_page; $pg<=$dlvm10_end_page; $pg++) {											
																if ((int)($dlvm10_currentpage) == (int)($pg)) {											
																	echo "<A href='javascript:gotopage(" . $pg . ")' class='pageselected'><u><b>" . $pg . "</b></u></a>";
																} else {											
																	echo "<A href='javascript:gotopage(" . $pg . ")' class='paging'>" . $pg . "</a>";
																}									
																if ($dlvm10_pg<>$dlvm10_totalpage) {
																	echo "&nbsp;";
																}
															}												
															if ($dlvm10_end_page < $dlvm10_totalpage) {										
																echo "<A href='javascript:gotopage(" . $dlvm10_totalpage . ")' class='paging'>Last</a>";
															}
															?>	
														</td>
													</tr>
													</form>
												</table>
												<table class="table table-striped table-bordered table-condensed" width="98%" border="0" cellspacing="1" cellpadding="4">
													<thead>
													<tr valign="top" style="background-color:red;color:white" height="25" align="center">
														<td style="width:30px;text-align:center">No</td>
														<td style="width:110px;">
															<?php if ($can_dlms) { ?>
																<input type="checkbox" id='chkall' name="chkall"> 
															<?php }?>
															Package No</td>
														<td style="width:150px;">หมายเลขใบเบิก<br>วิธีการจัดส่ง</td>
														<td style="width:240px;">ชื่อลูกค้า</td>
														<td style="width:220px;text-align:center">อำเภอ/จังหวัด</td>
														<td align=center style="width:20px;"> </td>
													</tr>
													</thead>   
													<tbody>
													<?php
													$n = 0;	
													//$criteria_dlvm10 = "WHERE dlvm_dlvs_step_code = '20'";
													$sql_dlvm10 = "SELECT dlvm10.* FROM" .
														" (SELECT ROW_NUMBER() OVER(ORDER BY sptm_customer_number,dlvm_transport_car_nbr,sptm_customer_amphur,sptm_customer_province) AS rownumber,* FROM dlvm_mstr " .
														" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
														" INNER JOIN customer ON customer_number = sptm_customer_number" .
														" INNER JOIN delivery_mth ON delivery_code = sptm_delivery_mth" .
														" $criteria_dlvm10) as dlvm10" .	
														" WHERE dlvm10.rownumber > $dlvm10_start_row and dlvm10.rownumber <= $dlvm10_start_row+$dlvm10_pagesize";
														
													$result_dlvm10 = sqlsrv_query( $conn, $sql_dlvm10);
													while($r_dlvm10 = sqlsrv_fetch_array($result_dlvm10, SQLSRV_FETCH_ASSOC)) {	
														$dlvm10_nbr = $r_dlvm10['dlvm_nbr'];
														$dlvm10_sptm_nbr = $r_dlvm10['dlvm_sptm_nbr'];
														$dlvm10_npd = $r_dlvm10['sptm_npd'];
														$dlvm10_npd_type = $r_dlvm10['sptm_npd_type'];
														$dlvm10_cust_code = $r_dlvm10['sptm_customer_number'];
														$dlvm10_cust_dummy = html_quot($r_dlvm10['sptm_customer_dummy']);
														$dlvm10_cust_type = $r_dlvm10['sptm_cust_type'];
														$dlvm10_cust_amphur =  html_quot($r_dlvm10['sptm_customer_amphur']);
														$dlvm10_cust_province = html_quot($r_dlvm10['sptm_customer_province']);
														if($dlvm10_cust_code != "DUMMY") {
															$dlvm10_cust_name = findsqlval("customer","customer_name1", "customer_number", $dlvm10_cust_code,$conn);
															if ($dlvm10_cust_name != "") {
																$dlvm10_cust_name = $dlvm10_cust_name;
															}
														}
														else {
															$dlvm10_cust_name = '<font color=red>[DUMMY]</font> ' .$dlvm10_cust_dummy;
														}
														$dlvm10_delivery_mth_desc = html_quot($r_dlvm10['sptm_delivery_mth_desc']);
														$dlvm10_delivery_name = html_quot($r_dlvm10['delivery_name']);
														$n++;	
														?>													
														<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $n+($dlvm10_currentpage-1)*$dlvm10_pagesize; ?></td>
															<td class="f_bk8">
																<?php if ($can_dlms) { ?>
																<input type="checkbox" name="dlvm10_nbr_<?php echo $dlvm10_nbr?>" id="dlvm10_nbr_<?php echo $dlvm10_nbr?>" value="<?php echo $dlvm10_nbr?>">
																<?php }?>
																<?php echo $dlvm10_nbr; ?>
															</td>
															<td class="f_bk8"><?php echo $dlvm10_sptm_nbr?><br><span style="color:gray"><?php echo $dlvm10_delivery_name?></span></td>
															<td class="f_bk8"><?php echo $dlvm10_cust_name?></td>
															<td class="f_bk8">
																<?php echo $dlvm10_cust_amphur."/".$dlvm10_cust_province; ?>
																<?php if ($dlvm10_delivery_mth_desc!="") { echo "<br><span style='color:red'>".str_replace("\n","<br />",$dlvm10_delivery_mth_desc) . "</span>"; }?>
															</td>
															<td>
																<?php if ($can_dlms) { ?>
																<a href="javascript:void();" onclick='add_dlvm10_selected("<?php echo $dlvm10_nbr?>")'><font color=green>>></font></a>
																<?php }?>
															</td>
														</tr>
													<?php }?>	
													</tbody>
												</table>  
											</fieldset>
										</td>
										<td valign=top bgcolor="white" width=50% align=center>
											<fieldset style="background-color:white;border-radius:4px;width:95%">
												
												<legend style="background-color:blue;color:white;border-radius:4px;">
													Package ที่เลือกแล้ว
												</legend>
												
												<table width=100%>
												<tr bgcolor="lightgray">
													<td width=100%>
													<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
														<tr style="height:35px">
															<td>
																<?php if ($can_dlms) { ?>
																<a href="javascript:void(0)" onclick="del_all_dlms()" role="button" style="color:white; text-decoration:none;" data-toggle="modal">
																	<div class="btn btn-small btn-default" style="margin-top:5px; margin-bottom:10px; width: 120px;">
																		<img src="../_images/del.png" style="width:16px;height:16px">
																		<span style='color:red'>ล้างข้อมูลหน้านี้</span>													
																	</div>
																</a>
																<?php }?>
															</td>
															<td align=right>
																<?php if ($can_dlms) { ?>
																<a href="#dlvm_multishipment" role="button" style="color:white; text-decoration:none;" data-toggle="modal">
																	<div class="btn btn-small btn-default" style="margin-top:5px; margin-bottom:10px; width: 70px;">
																		<img src="../_images/schedule.jpg" style="width:16px;height:16px">
																		<span style='color:red' class='blinking'>จัดเที่ยวรถ</span>													
																	</div>
																</a>
																<?php }?>
															</td>
														</tr>
													</table> 							
													</td>						
												</tr>
												</table>
												
												
												<table class="table table-bordered table-condensed" width="98%" border="0" cellspacing="1" cellpadding="4">
													<thead>
													<tr valign="top" style="background:green;color:white" height="25" align="center">
														<td style="width:30px;text-align:center">No</td>
														<td style="width:100px;">Package No</td>
														<td style="width:100px;">หมายเลขใบเบิก</td>
														<td style="width:240px;text-align:right">ชื่อลูกค้า</td>
														<td style="width:200px;text-align:center">อำเภอ/จังหวัด</td>
														<td style="width:80px;text-align:center">หมายเลขอ้างอิง</td>
														<td style="width:80px;text-align:center">ค่าใช้จ่ายจัดส่ง</td>
														<td align=center style="width:20px;">Action</td>
													</tr>
													
													</thead>   
													<tbody>
													<?php
													$n_dlms = 0;
													
													$sql_dlms = "SELECT * FROM dlms_det" .
														" INNER JOIN dlvm_mstr ON dlvm_nbr = dlms_dlvm_nbr " .
														" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
														" INNER JOIN customer ON customer_number = sptm_customer_number" .
														" WHERE dlms_user_login = '$user_login'" .
														" ORDER BY dlms_dlvm_nbr";
														
													$result_dlms = sqlsrv_query( $conn, $sql_dlms);
													while($r_dlms = sqlsrv_fetch_array($result_dlms, SQLSRV_FETCH_ASSOC)) {
														$dlms_id = $r_dlms['dlms_id'];
														$dlms_user_login = $r_dlms['dlms_user_login'];
														$dlms_dlvm_nbr = $r_dlms['dlms_dlvm_nbr'];
														$dlms_sptm_nbr = $r_dlms['sptm_nbr'];
														$dlms_sptm_delivery_mth = $r_dlms['sptm_delivery_mth'];
														$dlms_transport_ref_no = $r_dlms['dlms_transport_ref_no'];
														$dlms_transport_amt = $r_dlms['dlms_transport_amt'];
														
														$dlms_npd = $r_dlms['sptm_npd'];
														$dlms_npd_type = $r_dlms['sptm_npd_type'];
														$dlms_cust_code = $r_dlms['sptm_customer_number'];
														$dlms_cust_dummy = html_quot($r_dlms['sptm_customer_dummy']);
														$dlms_cust_type = $r_dlms['sptm_cust_type'];
														$dlms_cust_amphur =  html_quot($r_dlms['sptm_customer_amphur']);
														$dlms_cust_province = html_quot($r_dlms['sptm_customer_province']);
														if($dlms_cust_code != "DUMMY") {
															$dlms_cust_name = findsqlval("customer","customer_name1", "customer_number", $dlms_cust_code,$conn);
															if ($dlms_cust_name != "") {
																$dlms_cust_name = $dlms_cust_name;
															}
														}
														else {
															$dlms_cust_name = '<font color=red>[DUMMY]</font> ' .$dlms_cust_dummy;
														}
														$dlms_delivery_mth_desc = html_quot($r_dlms['sptm_delivery_mth_desc']);
														$dlms_delivery_mth_name = findsqlval("delivery_mth","delivery_name","delivery_code",$dlms_sptm_delivery_mth,$conn);
														if (inlist("MMP,MCTL,M999",$dlms_sptm_delivery_mth)) {
															$styletranamt = "style='color:red;' class='blinking' title='กรุณาตรวจสอบว่าต้องระบุค่าใช้จ่ายในการขนส่งหรือไม่'";
														}
														else {
															$styletranamt = "style='color:gray'";
														}
														$n_dlms++;
														?>	
														<tr id="dlms_id_<?php echo $dlms_id?>" ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $n_dlms;?></td>
															<td class="f_bk8"><?php echo $dlms_dlvm_nbr; ?></td>
															<td class="f_bk8"><?php echo $dlms_sptm_nbr?><br><span <?php echo $styletranamt?>><?php echo $dlms_delivery_mth_name?></span></td>
															<td class="f_bk8"><?php echo $dlms_cust_name?></td>
															<td class="f_bk8">
																<?php echo $dlms_cust_amphur."/".$dlms_cust_province; ?>
																<?php if ($dlms_delivery_mth_desc!="") { echo "<br><span style='color:red'>".str_replace("\n","<br />",$dlms_delivery_mth_desc) . "</span>"; }?>
															</td>
															<td class="f_bk8"><?php echo $dlms_transport_ref_no?></td>
															<td class="f_bk8"><?php echo $dlms_transport_amt?></td>
															<td>
																<?php if ($can_dlms) { ?>
																	<a href="javascript:void();" onclick='del_dlvm10_selected("<?php echo $dlms_id?>")'><font color=red>Del</font></a><br>
																	<a href="#dlvm_edit_refamt" onclick="setvalue_refamt('<?php echo $dlms_id?>','<?php echo $dlms_dlvm_nbr?>','<?php echo html_quot($dlms_cust_name)?>','<?php echo html_quot($dlms_delivery_mth_name)?>','<?php echo $dlms_transport_ref_no?>','<?php echo $dlms_transport_amt?>')" role="button" style="color:white; text-decoration:none;" data-toggle="modal">
																		<font color=blue>Edit</font>
																	</a>
																<?php } ?>
															</td>
														</tr>
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
	<form name="frm_selected_dlvm10" autocomplete=OFF method="post" action="../serverside/dlmsmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('selected_dlvm10'.$user_login)?>">
		<input type="hidden" name="dlvm10_nbr_list">
		<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
	</form>
	
	<form name="frm_add_dlvm10" autocomplete=OFF method="post" action="../serverside/dlmsmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('add_dlvm10'.$user_login)?>">
		<input type="hidden" name="dlvm10_nbr">
		<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
	</form>	
	<form name="frm_del_dlms" method="post" action="../serverside/dlmsmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('del_dlms'.$user_login)?>">			
		<input type="hidden" name="dlms_id">
		<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
	</form>	
	<form name="frm_del_all_dlms" method="post" action="../serverside/dlmsmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('del_all_dlms'.$user_login)?>">			
		<input type="hidden" name="dlms_by" value="<?php echo $user_login?>">
		<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
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

	<div id="dlvm_multishipment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_dlvm_multishipment" name="frm_dlvm_multishipment"  autocomplete=OFF method="post" action="../serverside/dlmsmntpost.php">
			<input type="hidden" name="action" value="<?php echo md5('dlvm_multishipment'.$user_login)?>">
			<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> จัดเที่ยวรถแบบกลุ่ม </h3>
			</div>
			
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
					
					<!--tr>
						<td style="text-align:right;vertical-align: middle">หมายเลขอ้างอิง<br>[บริษัทขนส่ง]:</td>
						<td colspan=3><input name="dlvm_transport_ref_no" Placeholder="*Tracking Number" type="text" style="margin:auto;width: 250px;" maxlength="60"></td> 
					</tr-->
					
					<tr>
						<td style="text-align:right;vertical-align: middle">ทะเบียนรถ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_car_nbr"  type="text" style="margin:auto;width: 150px;" maxlength="30">
							<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup_car('../_help/getcar.php','frm_dlvm_multishipment','dlvm_transport_car_nbr','dlvm_transport_driver_name','dlvm_transport_driver_tel',document.frm_dlvm_multishipment.dlvm_transport_car_nbr.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">เบอร์ตืดต่อ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_driver_tel"  type="text" style="margin:auto;width: 150px;" maxlength="60"></td> 
					</tr>
					<!--tr>
						<td style="text-align:right;vertical-align: middle;color:red">**ค่าใช้จ่าย:</td>
						<td colspan=3><input name="dlvm_transport_amt"  type="text" style="margin:auto;width: 150px;" maxlength="30"></td> 
					</tr-->
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
				<button id="btn_save_shipment" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="dlvm_multishipmentpost()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>																																																			
	</div>
	<div id="dlvm_edit_refamt" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_dlvm_edit_refamt" name="frm_dlvm_edit_refamt"  autocomplete=OFF method="post" action="../serverside/dlmsmntpost.php">
			<input type="hidden" name="action" value="<?php echo md5('edit_refamt'.$user_login)?>">
			<input type="hidden" name="dlms_id">
			<input type="hidden" name="pg" value="<?php echo $dlvm10_currentpage?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> แก้ไขหมายเลขอ้างอิงและค่าใช้จ่าย </h3>
			</div>
			
			<div class="">
				<table border=0 class="table-condensed">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr>
						<td style="width:150px;text-align:right;vertical-align: middle">Package No:</td>
						<td><div id="div_dlms_dlvm_nbr"></div></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">Customer Name:</td>
						<td><div id="div_dlms_cust_name"></div></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">วิธีการจัดส่ง:</td>
						<td><div id="div_dlms_delivery_mth"></div></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">หมายเลขอ้างอิง<br>[บริษัทขนส่ง]:</td>
						<td><input name="dlvm_transport_ref_no" Placeholder="*Tracking Number" type="text" style="margin:auto;width: 250px;" maxlength="60"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle;color:red">**ค่าใช้จ่าย:</td>
						<td><input name="dlvm_transport_amt"  type="text" style="margin:auto;width: 150px;" maxlength="30"></td> 
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button id="btn_save_shipment" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="dlvm_edit_refamt_post()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>																																																			
	</div>
	</body>
</html>
