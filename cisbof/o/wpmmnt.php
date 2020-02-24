<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$max_invoice_line = 8;

$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
$wpm_nbr = decrypt(mssql_escape($_REQUEST['wpmnumber']), $key);

$in_dlvm20_tmpsubmit = mssql_escape($_POST["in_dlvm20_tmpsubmit"]);
$in_dlvm20_customer = mssql_escape($_POST["in_dlvm20_customer"]);
$in_dlvm20_transport_car_nbr = mssql_escape($_POST["in_dlvm20_transport_car_nbr"]);

If ($in_dlvm20_tmpsubmit == "") {
	$in_dlvm20_tmpsubmit = $_COOKIE['in_dlvm20_tmpsubmit'];	
	$in_dlvm20_customer = $_COOKIE['in_dlvm20_customer'];
	$in_dlvm20_transport_car_nbr = $_COOKIE['in_dlvm20_transport_car_nbr'];
}
else {		
	setcookie("in_dlvm20_tmpsubmit","",0);
	setcookie("in_dlvm20_customer","",0);
	setcookie("in_dlvm20_transport_car_nbr","",0);
}
//
if ($in_dlvm20_customer != "") {
	if ($criteria_dlvm20 != "") { $criteria_dlvm20 = $criteria_dlvm20 . " AND "; }
	$criteria_dlvm20 = $criteria_dlvm20 . " (customer_number like '%$in_dlvm20_customer%' OR customer_name1 like '%$in_dlvm20_customer%' OR sptm_customer_dummy like '%$in_dlvm20_customer%')";
}
setcookie("in_dlvm20_customer", $in_dlvm20_customer,0);	
//
//
if ($in_dlvm20_transport_car_nbr != "") {
	if ($criteria_dlvm20 != "") { $criteria_dlvm20 = $criteria_dlvm20 . " AND "; }
	$criteria_dlvm20 = $criteria_dlvm20 . " (dlvm_transport_car_nbr like '%$in_dlvm20_transport_car_nbr%')";
}
setcookie("in_dlvm20_customer", $in_dlvm20_customer,0);	
//
if ($criteria_dlvm20 != "") {
	$criteria_dlvm20 = "WHERE dlvm_dlvs_step_code = '20' AND " . $criteria_dlvm20;
}
else {
	$criteria_dlvm20 = "WHERE dlvm_dlvs_step_code = '20'";
}

$sql_wpm = "SELECT * from wpm_mstr where wpm_nbr = '$wpm_nbr' and wpm_is_delete = 0";
$result_wpm = sqlsrv_query($conn, $sql_wpm);	
$r_wpm = sqlsrv_fetch_array($result_wpm, SQLSRV_FETCH_ASSOC);		
if ($r_wpm) {	
	$wpm_remark = html_quot($r_wpm['wpm_remark']);
	$wpm_status_code = html_quot($r_wpm['wpm_status_code']);
	$wpm_group_type = $r_wpm['wpm_group_type'];
	$wpm_printed = $r_wpm['wpm_printed'];
	
	if ($wpm_group_type == "A") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ A-ตาม ลูกค้า+ทะเบียนรก]";
	}
	if ($wpm_group_type == "B") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ B-ตาม วิธีการจัดส่ง]";
	}
	if ($wpm_group_type == "C") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ C-ตาม วิธีการจัดส่ง+ผู้ขอเบิก]";
	}
	if ($wpm_group_type == "D") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ D-ตาม ลูกค้า+จังหวัด]";
	}
	if ($wpm_group_type == "E") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ E-ตาม ลูกค้า+วิธีการจัดส่ง]";
	}
	if ($wpm_group_type == "F") {
		$wpm_group_type_text = "[จัดกลุ่มแบบ F-ตาม ลูกค้า+หมายเหตุการจัดส่ง]";
	}
}
else {
	$path = "sptmauthorize.php?msg=เอกสารหมายเลข $wpm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
}	

$can_wpm = false;
if (inlist($user_role,"CS")) {
	$can_wpm = true;
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
				$("input[id*='dlvm20_nbr']:checkbox").prop('checked', $(this).prop('checked'));			
			});			
		});
		
	</script>
	<script language="javascript">
		function setvalue_edit(wpm_nbr,wpm_group_type,wpm_remark) {
			document.frm_wpm_edit.wpm_nbr.value = wpm_nbr;
			document.frm_wpm_edit.wpm_group_type.value = wpm_group_type;
			document.frm_wpm_edit.wpm_remark.value = wpm_remark;
		}
		function wpmpostform(formname) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var wpm_group_type = document.forms[formname].wpm_group_type.value;
			if (wpm_group_type == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกการจัดกลุ่มข้อมูล";
			}
			
			if (errorflag ) {			
				alert(errortxt);
			}
			else {				
				document.forms[formname].submit();									
			}			
		}
		function setvalue_edit_dlvm_car(dlvm_nbr,dlvm_transport_car_nbr,dlvm_transport_tspm_code,dlvm_transport_tspm_other,dlvm_transport_ref_no,dlvm_transport_driver_name,dlvm_transport_driver_tel) {
			$('#div_dlvm_nbr').html(dlvm_nbr);
			document.frm_edit_dlvm_car.dlvm_nbr.value = dlvm_nbr;
			document.frm_edit_dlvm_car.dlvm_transport_car_nbr.value = dlvm_transport_car_nbr;
			document.frm_edit_dlvm_car.dlvm_transport_tspm_code.value = dlvm_transport_tspm_code;
			document.frm_edit_dlvm_car.dlvm_transport_tspm_other.value = dlvm_transport_tspm_other;
			document.frm_edit_dlvm_car.dlvm_transport_ref_no.value = dlvm_transport_ref_no;
			document.frm_edit_dlvm_car.dlvm_transport_driver_name.value = dlvm_transport_driver_name;
			document.frm_edit_dlvm_car.dlvm_transport_driver_tel.value = dlvm_transport_driver_tel;
		}
		function edit_dlvm_car_post() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var dlvm_transport_car_nbr = document.frm_edit_dlvm_car.dlvm_transport_car_nbr.value;
			var dlvm_transport_tspm_code = document.frm_edit_dlvm_car.dlvm_transport_tspm_code.value;
			var dlvm_transport_tspm_other = document.frm_edit_dlvm_car.dlvm_transport_tspm_other.value;
			var dlvm_transport_ref_no = document.frm_edit_dlvm_car.dlvm_transport_ref_no.value;
			var dlvm_transport_driver_name = document.frm_edit_dlvm_car.dlvm_transport_driver_name.value;
			var dlvm_transport_driver_tel = document.frm_edit_dlvm_car.dlvm_transport_driver_tel.value;
			
			if (dlvm_transport_tspm_code=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือก บริษัทขนส่ง";					
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
			
			// if (dlvm_transport_driver_name=="") {
				// if (errortxt!="") {errortxt = errortxt + "<br>";}	
				// errorflag = true;					
				// errortxt = errortxt + "กรุณาระบุ ชื่อผู้ขับ";					
			// }		
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
			
			if (errorflag) {
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_edit_dlvm_car.submit();
			}
		}
		function createinvoicepost(wpd_group) {
			var wpd_id = "";
			var wpd_id_name = "wpd_id_"+wpd_group+'_';
		
			$('input[name^='+wpd_id_name+']').each(function() {
				if (wpd_id != "") { wpd_id = wpd_id + ","; }
				wpd_id = wpd_id + this.value;
			
			});
			var v;
			
			var wpd_result_all = true;
			var wpd_result = "";
			var wpd_id_array = wpd_id.split(",");
			for (i = 0; i < wpd_id_array.length; i++) {
				v = getRadioValue(document.getElementsByName("radio_wpd_"+wpd_group+"_"+wpd_id_array[i]));
				if (v == "") {
					var wpd_result_all = false;
				}
				if (wpd_result != "") { wpd_result = wpd_result + ","; }
				wpd_result = wpd_result + v;
			}
			if (!wpd_result_all) {
				alert("คุณต้องเลือกผลการขึ้นสินค้า");
			} else {
				document.frm_createinvoice.wpd_id.value = wpd_id;
				document.frm_createinvoice.wpd_result.value = wpd_result;
				document.frm_createinvoice.submit();
			}
		}
		
		function selecteddlvmpost() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var dlvm20_nbr_list = "";
			var dlvm20_cnt = 0;
			$('input[name^=dlvm20_nbr_]').each(function() {
				if (this.checked) {
					if (dlvm20_nbr_list != "") { dlvm20_nbr_list = dlvm20_nbr_list + ","; }
					dlvm20_nbr_list = dlvm20_nbr_list + this.value;
					dlvm20_cnt++;
				}
			});
			
			if (dlvm20_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกรายการที่ต้องการก่อนค่ะ";
			}
			
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {			
				document.frm_selected_dlvm20_wpm.dlvm20_nbr_list.value = dlvm20_nbr_list;
				document.frm_selected_dlvm20_wpm.submit();				
			}
		}
		function del_dlvm_selected(wpd_id,wpm_nbr,dlvm20_nbr) {
			document.frm_del_dlvm20_wpm.wpd_id.value = wpd_id;
			document.frm_del_dlvm20_wpm.wpm_nbr.value = wpm_nbr;
			document.frm_del_dlvm20_wpm.dlvm20_nbr.value = dlvm20_nbr;			
			document.frm_del_dlvm20_wpm.submit();
		}
		function add_dlvm_selected(dlvm20_nbr) {
			document.frm_add_dlvm20_wpm.dlvm20_nbr.value = dlvm20_nbr;			
			document.frm_add_dlvm20_wpm.submit();
		}
		
		function cancel_dlvm40_selected(wpd_id,dlvm40_nbr) {
			if(confirm('ท่านต้องการยกเลิก Delieery นี้ออกจากใบเตรียมขึ้นสินค้า ไช่หรือไม่ ?')) {	
				document.frm_cancel_dlvm40_wpm.wpd_id.value = wpd_id;
				document.frm_cancel_dlvm40_wpm.dlvm40_nbr.value = dlvm40_nbr;			
				document.frm_cancel_dlvm40_wpm.submit();
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
		
		function printform(url) {				
			window.open(url);
			setTimeout(function(){ window.location.replace("wpmall.php"); }, 1000);									
		}
		function printformonly(url) {				
			window.open(url);							
		}		
		function gotofocdpage(mypage) {					
			loadresult()
			document.frm_focd.dlvm20_pg.value=mypage;
			document.frm_focd.submit();
		}
		
		function gotopage(mypage) {					
			loadresult()
			document.frm_focm.pg.value=mypage;
			document.frm_focm.submit();
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
		$criteria_dlvm20;

	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	
	
	$dlvm20_pagesize = 15;
	$dlvm20_totalrow = $max;
	$dlvm20_totalpage = ($dlvm20_totalrow/$dlvm20_pagesize) - (int)($dlvm20_totalrow/$dlvm20_pagesize);
	if ($dlvm20_totalpage > 0) {
		$dlvm20_totalpage = ((int)($dlvm20_totalrow/$dlvm20_pagesize)) + 1;
	} else {
		$dlvm20_totalpage = (int)$dlvm20_totalrow/$dlvm20_pagesize;
	}					
	if ($_REQUEST["dlvm20_pg"]=="") {
		$dlvm20_currentpage = 1;	
		$dlvm20_end_row = ($dlvm20_currentpage * $dlvm20_pagesize) - 1;
		if ($dlvm20_end_row > ($dlvm20_totalrow - 1)) { $dlvm20_end_row = $dlvm20_totalrow - 1; }
		$dlvm20_start_row = 0;
	} else {
		$dlvm20_currentpage = $_REQUEST["dlvm20_pg"];
		if ((int)$dlvm20_currentpage < 1) { $dlvm20_currentpage = 1; }
		if ((int)$dlvm20_currentpage > (int)$dlvm20_totalpage) { $dlvm20_currentpage = $dlvm20_totalpage; }
		$dlvm20_end_row = ($dlvm20_currentpage * $dlvm20_pagesize) - 1;
		$dlvm20_start_row = $dlvm20_end_row - $dlvm20_pagesize + 1;
		if ($dlvm20_end_row > ($dlvm20_totalrow - 1)) { $dlvm20_end_row = $dlvm20_totalrow - 1; }					
	}

	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($dlvm20_totalpage) <= (int)($maxpage)) {
		$maxpage = $dlvm20_totalpage;
	}		
	if ($dlvm20_currentpage < $maxpage) {
		$dlvm20_start_page = 1;
		$dlvm20_end_page = $maxpage;	
	} else {		
		$dlvm20_start_page = $dlvm20_currentpage - $slidepage;
		$dlvm20_end_page = $dlvm20_currentpage + $slidepage;
		if ($dlvm20_start_page <= 1) {
			$dlvm20_start_page = 1;
			$dlvm20_end_page = $maxpage;
		} 
		if ($dlvm20_end_page >= $dlvm20_totalpage) {
			$dlvm20_start_page = $dlvm20_totalpage - $maxpage + 1;
			$dlvm20_end_page = $dlvm20_totalpage;
		}
	}
	//Page ของ focm_mstr
	$sql_cnt = "SELECT * FROM wpd_det WHERE wpd_wpm_nbr = '$wpm_nbr'";
	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	
	
	$pagesize = 15;
	$totalrow = $max;
	$totalpage = ($totalrow/$pagesize) - (int)($totalrow/$pagesize);
	if ($totalpage > 0) {
		$totalpage = ((int)($totalrow/$pagesize)) + 1;
	} else {
		$totalpage = (int)$totalrow/$pagesize;
	}					
	if ($_REQUEST["pg"]=="") {
		$currentpage = 1;	
		$end_row = ($currentPage * $pagesize) - 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }
		$start_row = 0;
	} else {
		$currentpage = $_REQUEST["pg"];
		if ((int)$currentpage < 1) { $currentpage = 1; }
		if ((int)$currentpage > (int)$totalpage) { $currentpage = $totalpage; }
		$end_row = ($currentpage * $pagesize) - 1;
		$start_row = $end_row - $pagesize + 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }					
	}
	
	
	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($totalpage) <= (int)($maxpage)) {
		$maxpage = $totalpage;
	}		
	if ($currentpage < $maxpage) {
		$start_page = 1;
		$end_page = $maxpage;	
	} else {		
		$start_page = $currentpage - $slidepage;
		$end_page = $currentpage + $slidepage;
		if ($start_page <= 1) {
			$start_page = 1;
			$end_page = $maxpage;
		} 
		if ($end_page >= $totalpage) {
			$start_page = $totalpage - $maxpage + 1;
			$end_page = $totalpage;
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
								<img src='../_images/foc-icon.png' width=32><span style='font-size:11pt'><b>ใบเตรียมขึ้นสินค้า: <?php echo $wpm_nbr . " <span style='color:red'>(" . $wpm_group_type . ")</span>"?> </b></span>
								<div class="btn btn-small btn-danger" style="margin-top:5px; margin-bottom:10px; width: 50px;" onclick="loadresult();window.location.href='wpmall.php?activeid=<?php echo encrypt($wpm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
									<i class="icon-white icon-hand-left"></i>
									<span>Black</span>													
								</div>
								<?php if($wpm_printed && $can_wpm) {?>
								<div class="btn btn-small btn-success" style="margin-top:5px; margin-bottom:10px; width: 60px;" onclick="printformonly('wpmform01.php?wpmnumber=<?php echo encrypt($wpm_nbr, $key);?>')">													
									<i class="icon-white icon-print"></i>
									<span>Re-Print</span>													
								</div>
								<?php }?>
							</td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% colspan=2>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>
										<?php if(inlist("10,20",$wpm_status_code)) {?>
										<td bgcolor="white" width=50% valign=top>
											<fieldset style="background-color:white;border-radius:4px;width:97%">
												<legend style="background-color:red;color:white;border-radius:4px;">Package ที่พร้อมสร้างใบเตรียม</legend>
												<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
													<form name="frm_focd" method="post" autocomplete=OFF>
													<input type="hidden" name="in_dlvm20_tmpsubmit" value="search">
													<input type="hidden" name="dlvm20_pg">
													<tr>
														<td width=25%>
															<?php if(inlist("10,20",$wpm_status_code) && $can_wpm) {?>
																<button type="button" class="btn btn-mini" onclick="selecteddlvmpost()" style='background:green;color:white;font-size:8pt'>>> เพิ่มรายการที่เลือก</button>														
															<?php }?>
														</td>
														<td valign=top align=right><b>ชื่อ/รหัสลูกค้า:</b> <input type='text' style='width: 80px;font-size:8pt;color:blue' name="in_dlvm20_customer" value="<?php echo $in_dlvm20_customer?>"></td>
														<td valign=top align=right style='width:210px'><b>ทะเบียนรถ:</b> <input type='text' style='width: 110px;font-size:8pt;color:blue' name="in_dlvm20_transport_car_nbr" value="<?php echo $in_dlvm20_transport_car_nbr?>">
															<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
														</td>
													</tr>
													<tr bgcolor="lightgray">
														<td colspan=3>
															(<font color=red><?php echo $dlvm20_totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $dlvm20_totalrow;?></font>&nbsp;items)								
															<b>Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotofocdpage(document.frm_focd.jumto.value)">
															&nbsp;
														</td>
	
														<td colspan=3 class="f_bk8" align=right>
															<?php
															if ($dlvm20_start_page > 1) {																				
																echo "<A href='javascript:gotofocdpage(1)' class='paging'>First</a>&nbsp;";
															}														
															for ($pg=$dlvm20_start_page; $pg<=$dlvm20_end_page; $pg++) {											
																if ((int)($dlvm20_currentpage) == (int)($pg)) {											
																	echo "<A href='javascript:gotofocdpage(" . $pg . ")' class='pageselected'><u><b>" . $pg . "</b></u></a>";
																} else {											
																	echo "<A href='javascript:gotofocdpage(" . $pg . ")' class='paging'>" . $pg . "</a>";
																}									
																if ($dlvm20_pg<>$dlvm20_totalpage) {
																	echo "&nbsp;";
																}
															}												
															if ($dlvm20_end_page < $dlvm20_totalpage) {										
																echo "<A href='javascript:gotofocdpage(" . $dlvm20_totalpage . ")' class='paging'>Last</a>";
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
															<?php if ($can_wpm) { ?>
																<input type="checkbox" id='chkall' name="chkall"> 
															<?php }?>
															Package No</td>
														<td style="width:90px;text-align:center">ทะเบียนรถ</td>
														<td style="width:240px;">ชื่อลูกค้า</td>
														<td style="width:220px;text-align:center">อำเภอ/จังหวัด</td>
														<td align=center style="width:20px;"> </td>
													</tr>
													</thead>   
													<tbody>
													<?php
													if (inlist("10,20",$wpm_status_code)) {
														$n = 0;	
														//$criteria_dlvm20 = "WHERE dlvm_dlvs_step_code = '20'";
														$sql_dlvm20 = "SELECT dlvm20.* FROM" .
														" (SELECT ROW_NUMBER() OVER(ORDER BY sptm_customer_number,dlvm_transport_car_nbr,sptm_customer_amphur,sptm_customer_province) AS rownumber,* FROM dlvm_mstr " .
														" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
														" INNER JOIN customer ON customer_number = sptm_customer_number" .
														" $criteria_dlvm20) as dlvm20" .	
														" WHERE dlvm20.rownumber > $dlvm20_start_row and dlvm20.rownumber <= $dlvm20_start_row+$dlvm20_pagesize";
														
														$result_dlvm20 = sqlsrv_query( $conn, $sql_dlvm20);
														while($r_dlvm20 = sqlsrv_fetch_array($result_dlvm20, SQLSRV_FETCH_ASSOC)) {	
															$dlvm20_nbr = $r_dlvm20['dlvm_nbr'];
															$dlvm20_sptm_nbr = $r_dlvm20['dlvm_sptm_nbr'];
															$dlvm20_transport_car_nbr = $r_dlvm20['dlvm_transport_car_nbr'];
															$dlvm20_transport_tspm_code = $r_dlvm20['dlvm_transport_tspm_code'];
															$dlvm20_transport_tspm_other = $r_dlvm20['dlvm_transport_tspm_other'];
															$dlvm20_transport_ref_no = $r_dlvm20['dlvm_transport_ref_no'];
															$dlvm20_transport_driver_name = $r_dlvm20['dlvm_transport_driver_name'];
															$dlvm20_transport_driver_tel = $r_dlvm20['dlvm_transport_driver_tel'];
															$dlvm_wh_status = $r_dlvm20['dlvm_wh_status'];
															if ($dlvm_wh_status == "N") {
																$dlvm_wh_status_text = "<br><span style='color:red;font-size:7pt'>*ขึ้นไม่ได้*</span>";
															} else {
																$dlvm_wh_status_text = "";
															}

															$dlvm20_npd = $r_dlvm20['sptm_npd'];
															$dlvm20_npd_type = $r_dlvm20['sptm_npd_type'];
															$dlvm20_cust_code = $r_dlvm20['sptm_customer_number'];
															$dlvm20_cust_dummy = html_quot($r_dlvm20['sptm_customer_dummy']);
															$dlvm20_cust_type = $r_dlvm20['sptm_cust_type'];
															$dlvm20_cust_amphur =  html_quot($r_dlvm20['sptm_customer_amphur']);
															$dlvm20_cust_province = html_quot($r_dlvm20['sptm_customer_province']);
															if($dlvm20_cust_code != "DUMMY") {
																$dlvm20_cust_name = findsqlval("customer","customer_name1", "customer_number", $dlvm20_cust_code,$conn);
																if ($dlvm20_cust_name != "") {
																	$dlvm20_cust_name = $dlvm20_cust_name;
																}
															}
															else {
																$dlvm20_cust_name = '<font color=red>[DUMMY]</font> ' .$dlvm20_cust_dummy;
															}
															$dlvm20_delivery_mth_desc = html_quot($r_dlvm20['sptm_delivery_mth_desc']);
															//ดึงข้อมูลของใบเบิก
															/*****
															$dlvm20_npd = false;
															$dlvm20_cust_amphur =  "";
															$dlvm20_cust_province = "";
															$dlvm20_cust_name = "";
															$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$dlvm20_sptm_nbr'";
															$result_sptm = sqlsrv_query($conn, $sql_sptm);	
															$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
															if ($r_sptm) {
																$dlvm20_npd = $r_sptm['sptm_npd'];
																$dlvm20_npd_type = $r_sptm['sptm_npd_type'];
																$dlvm20_cust_code = $r_sptm['sptm_customer_number'];
																$dlvm20_cust_dummy = html_quot($r_sptm['sptm_customer_dummy']);
																$dlvm20_cust_type = $r_sptm['sptm_cust_type'];
																$dlvm20_cust_amphur =  html_quot($r_sptm['sptm_customer_amphur']);
																$dlvm20_cust_province = html_quot($r_sptm['sptm_customer_province']);
																if($dlvm20_cust_code != "DUMMY") {
																	$dlvm20_cust_name = findsqlval("customer","customer_name1", "customer_number", $dlvm20_cust_code,$conn);
																	if ($dlvm20_cust_name != "") {
																		//$dlvm20_cust_name = '['.$dlvm20_cust_code.'] ' . $dlvm20_cust_name;
																		$dlvm20_cust_name = $dlvm20_cust_name;
																	}
																}
																else {
																	$dlvm20_cust_name = '<font color=red>[DUMMY]</font> ' .$dlvm20_cust_dummy;
																}
															}
															**/
															
															$n++;	
															?>													
															<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
																<td class="f_bk8" style="text-align:center;"><?php echo $n+($dlvm20_currentpage-1)*$dlvm20_pagesize; ?></td>
																<td class="f_bk8" style="">
																	<?php if ($can_wpm) { ?>
																	<input type="checkbox" name="dlvm20_nbr_<?php echo $dlvm20_nbr?>" id="dlvm20_nbr_<?php echo $dlvm20_nbr?>" value="<?php echo $dlvm20_nbr?>">
																	<?php }?>
																	<?php echo $dlvm20_nbr; ?><?php echo $dlvm_wh_status_text?>
																</td>
																<td class="f_bk8" style="">
																	<?php if ($can_wpm) { ?>
																		<a href="#edit_dlvm_car" 
																			onclick="setvalue_edit_dlvm_car(
																			'<?php echo $dlvm20_nbr?>',
																			'<?php echo $dlvm20_transport_car_nbr?>',
																			'<?php echo $dlvm20_transport_tspm_code?>',
																			'<?php echo $dlvm20_transport_tspm_other?>',
																			'<?php echo $dlvm20_transport_ref_no?>',
																			'<?php echo $dlvm20_transport_driver_name?>',
																			'<?php echo $dlvm20_transport_driver_tel?>'
																			)" role="button" data-toggle="modal">
																			<?php echo $dlvm20_transport_car_nbr; ?>
																		</a>
																	<?php } else {?>
																		<?php echo $dlvm20_transport_car_nbr; ?>
																	<?php }?>
																</td>
																<td class="f_bk8" style=""><?php echo $dlvm20_cust_name?></td>
																<td class="f_bk8" style="">
																	<?php echo $dlvm20_cust_amphur."/".$dlvm20_cust_province; ?>
																	<?php if ($dlvm20_delivery_mth_desc!="") { echo "<br><span style='color:red'>".str_replace("\n","<br />",$dlvm20_delivery_mth_desc) . "</span>"; }?>
																</td>
																
																<td>
																	<?php if(inlist("10,20",$wpm_status_code) && $can_wpm) {?>
																		<a href="javascript:void();" onclick='add_dlvm_selected("<?php echo $dlvm20_nbr?>")'><font color=green>>></font></a>
																	<?php }?>
																</td>
															</tr>
														<?php }?>	
													<?php }?>
													</tbody>
												</table>  
											</fieldset>
										</td>
										<?php }?>
										<td valign=top bgcolor="white" width=50% align=center>
											<fieldset style="background-color:white;border-radius:4px;width:95%">
												<?php if(inlist("10,20",$wpm_status_code)) {?>
												<legend style="background-color:blue;color:white;border-radius:4px;">
													Package ที่เลือกแล้ว
												</legend>
												
												<table width=100%>
												<tr bgcolor="lightgray">
													<td width=100%>
													<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
														<tr style="height:35px">
															<td>
																<?php if ($can_wpm) {?>
																<a href="#wpm_edit" onclick="setvalue_edit('<?php echo $wpm_nbr?>','<?php echo $wpm_group_type?>','<?php echo $wpm_remark?>')" role="button" style="text-decoration:none" data-toggle="modal">
																	<h5><span style="font-weight:bold">ใบเตรียมขึ้นสินค้า:</span> <?php echo $wpm_nbr;?><h5>
																</a>
																<?php } else {?>
																	<h5><span style="font-weight:bold">ใบเตรียมขึ้นสินค้า:</span> <?php echo $wpm_nbr;?><h5>
																<?php }?>
																<?php echo $wpm_group_type_text?>
															</td>
															<td align=right>
																<?php if(inlist("10,20",$wpm_status_code) && $can_wpm) {?>
																	<div class="btn btn-small btn-warning" style="width: 150px;" onclick="printform('wpmform01.php?wpmnumber=<?php echo encrypt($wpm_nbr, $key);?>')" style='background:green;color:white;font-size:8pt'>													
																		<i class="icon-white icon-print"></i>
																		<span> พิมพ์ใบเตรียมขึ้นสินค้า</span>													
																	</div>
																												
																<?php }?>
															</td>
														</tr>
													</table> 							
													</td>						
												</tr>
												</table>
												<?php }?>
												<?php 
												if(inlist("10,20",$wpm_status_code)) {
													$tb_style="background-color:green;color:white";
												}
												elseif($wpm_status_code == "30") {
													$tb_style="background-color:#ffff99;color:black";
												}
												else {
													$tb_style="background-color:green;color:white";
												}
												?>
												<table class="table table-bordered table-condensed" width="98%" border="0" cellspacing="1" cellpadding="4">
													<thead>
													<tr valign="top" style="<?php echo $tb_style?>" height="25" align="center">
														<td style="width:30px;text-align:center">No</td>
														<td style="width:100px;">Package No</td>
														<td style="width:90px;text-align:center">ทะเบียนรถ</td>
														<td style="width:240px;text-align:right">ชื่อลูกค้า</td>
														<td style="width:200px;text-align:center">อำเภอ/จังหวัด</td>
														<td style="width:130px;text-align:center">ใบส่งของเลขที่</td>
														<?php if(inlist("10,20",$wpm_status_code)) {?>
															<td align=center style="width:20px;">Action</td>
														<?php }?>
														<?php if($wpm_status_code >= "30") {?>
															<td align=center style="background:green;color:white;text-align:center;width:30px;">(OK)<br>ขึ้นได้</td>
															<td align=center style="background:red;color:white;text-align:center;width:30px;">(X)<br>ขึ้นไม่ได้</td>
														<?php }?>
													</tr>
													
													</thead>   
													<tbody>
													<?php
													$n = 0;
													$wpd_firstof_array = array();
													$wpd_lastof_array =  array();
													$wpd_id_array = array();
													$wpd_dlvm_nbr_array = array();
													$wpd_transport_car_nbr_array = array();
													$wpd_customer_name_array = array();
													$wpd_customer_amphur_array = array();
													$wpd_customer_province_array = array();
													$wpd_delivery_mth_desc_array = array();
													$wpd_ivm_nbr_array = array();
													$wpd_status_array = array();
													$wpd_group_array = array();
													$wpd_group2_array = array();
													
													$group_type = $wpm_group_type;
													if ($group_type == "A") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,dlvm_transport_car_nbr,sptm_customer_amphur,sptm_customer_province";
													}
													if ($group_type == "B") {
														$group_by = "sptm_delivery_mth,sptm_req_by";
													}
													if ($group_type == "C") {
														$group_by = "sptm_delivery_mth,sptm_req_by";
													}
													if ($group_type == "D") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_customer_province";
													}
													if ($group_type == "E") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_delivery_mth";
													}
													if ($group_type == "F") {
														$group_by = "sptm_customer_number,sptm_customer_dummy,cast(sptm_delivery_mth_desc as nvarchar(255))";
													}
													$sql_wpd = "SELECT * FROM wpd_det" .
														" INNER JOIN wpm_mstr ON wpm_nbr = wpd_wpm_nbr " .
														" INNER JOIN dlvm_mstr ON dlvm_nbr = wpd_dlvm_nbr " .
														" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
														" WHERE wpd_wpm_nbr = '$wpm_nbr'" .
														" ORDER BY $group_by";
													
													$result_wpd = sqlsrv_query( $conn, $sql_wpd);
													while($r_wpd = sqlsrv_fetch_array($result_wpd, SQLSRV_FETCH_ASSOC)) {
														$wpd_id = $r_wpd['wpd_id'];
														$wpd_dlvm_nbr = $r_wpd['wpd_dlvm_nbr'];
														$wpd_transport_car_nbr = $r_wpd['dlvm_transport_car_nbr'];
														$wpd_sptm_nbr = $r_wpd['dlvm_sptm_nbr'];
														$wpd_status = $r_wpd['wpd_status'];
														
														//ดึงข้อมูลของใบเบิก
														$wpd_cust_amphur =  "";
														$wpd_cust_province = "";
														$wpd_cust_name = "";
														$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$wpd_sptm_nbr'";
														$result_sptm = sqlsrv_query($conn, $sql_sptm);	
														$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
														if ($r_sptm) {
															$wpd_req_by = $r_sptm['sptm_req_by'];
															$wpd_cust_code = $r_sptm['sptm_customer_number'];
															$wpd_cust_dummy = html_quot($r_sptm['sptm_customer_dummy']);
															$wpd_cust_type = $r_sptm['sptm_cust_type'];
															$wpd_cust_amphur =  html_quot($r_sptm['sptm_customer_amphur']);
															$wpd_cust_province = html_quot($r_sptm['sptm_customer_province']);
															$wpd_delivery_mth = html_quot($r_sptm['sptm_delivery_mth']);
															$wpd_delivery_mth_name = findsqlval("delivery_mth","delivery_name","delivery_code",$wpd_delivery_mth,$conn);
															if($wpd_cust_code != "DUMMY") {
																$wpd_cust_name = findsqlval("customer","customer_name1", "customer_number", $wpd_cust_code,$conn);
																if ($wpd_cust_name != "") {
																	$wpd_cust_name = '['.$wpd_cust_code.'] ' . $wpd_cust_name;
																}
															}
															else {
																$wpd_cust_name = '<font color=red>[DUMMY]</font> ' .$wpd_cust_dummy;
															}
															$wpd_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
														}
														if ($group_type == "A") {
															$group_data = $wpd_cust_name."-".$wpd_transport_car_nbr;
															
														}
														if ($group_type == "B") {
															$group_data = $wpd_delivery_mth_name;
														}
														if ($group_type == "C") {
															$group_data = $wpd_delivery_mth_name."-".$wpd_req_by;
														}
														if ($group_type == "D") {
															$group_data = $wpd_cust_name."-".$wpd_cust_province;
														}
														if ($group_type == "E") {
															$group_data = $wpd_cust_name."-".$wpd_delivery_mth_name;
														}
														if ($group_type == "F") {
															$group_data = $wpd_cust_name."-".$wpd_delivery_mth_desc;
														}
														$wpd_group_array[$n] = $group_data;
														$wpd_id_array[$n] = $wpd_id;
														$wpd_dlvm_nbr_array[$n] = $wpd_dlvm_nbr;
														$wpd_transport_car_nbr_array[$n] = $wpd_transport_car_nbr;
														$wpd_customer_name_array[$n] = $wpd_cust_name;
														$wpd_customer_amphur_array[$n] = $wpd_cust_amphur;
														$wpd_customer_province_array[$n] = $wpd_cust_province;
														$wpd_delivery_mth_desc_array[$n] = $wpd_delivery_mth_desc;
														$wpd_ivm_nbr_array[$n] = $ivm_nbr;
														$wpd_status_array[$n] = $wpd_status;
														$n++;
													}
													//Copy Array เอาไว้สำหรับเปรียบเทียบเพื่อหาตัว Last Of
													$wpd_group2_array = $wpd_group_array;
													?>
													<?php
													$first_of_data = "";
													$first_of = false;
													$last_of = false;
													$can_create_invoice = true;
													$wpd_group = 0;
													?>
													<?php for($w = 0; $w < sizeof($wpd_id_array);$w++) {?>
														<?php
														if ($wpd_group_array[$w] != $first_of_data) {
															$first_of = true;
															$first_of_data = $wpd_group_array[$w];
															$first_of_text = "Y";
															$wpd_group = $wpd_group + 1;
															$wpd_n = 0;
															$can_create_invoice = true;
														}
														else {
															$first_of = false;
															$first_of_text = "N";
														}
														
														$wpd_n++;
														$wpd_status = findsqlval("wpd_det","wpd_status","wpd_id",$wpd_id_array[$w],$conn);
														$wpd_ivm_nbr = findsqlval("wpd_det","wpd_ivm_nbr","wpd_id",$wpd_id_array[$w],$conn);
														
														if ($wpd_group_array[$w] != $wpd_group2_array[$w+1]) {
															$last_of = true;
															$last_of_text = "Y";
															if ($wpd_n > $max_invoice_line) {
																$can_create_invoice = false;
															}
														}
														else {
															$last_of = false;
															$last_of_text = "N";
														}
														?>
														<?php if ($first_of) {?>
															<tr style="background:#b3b3ff"><td colspan=<?php if(inlist("10,20",$wpm_status_code)) {echo "6";} else {echo "7";}?>><?php echo $wpd_group_array[$w]?><td></tr>	
														<?php }?>
														<tr ONMOUSEOVER="this.style.backgroundColor =''" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $wpd_n; ?></td>
															<td class="f_bk8" style=""><?php echo $wpd_dlvm_nbr_array[$w]; ?></td>
															<td class="f_bk8" style=""><?php echo $wpd_transport_car_nbr_array[$w]; ?></td>
															<td class="f_bk8" style=""><?php echo $wpd_customer_name_array[$w]?></td>
															<td class="f_bk8" style="">
																<?php echo $wpd_customer_amphur_array[$w]."/".$wpd_customer_province_array[$w]; ?>
																<?php if ($wpd_delivery_mth_desc_array[$w]!="") { echo "<br><span style='color:red'>".str_replace("\n","<br />",$wpd_delivery_mth_desc_array[$w]) . "</span>"; }?>
															</td>
															
															<td style="text-align:center">
																<?php if($wpd_status == "Y") {?>
																	<?php echo $wpd_ivm_nbr;?>
																<?php }?>
																<?php if($wpd_status == "N") {?>
																	<span style="color:red">ขึ้นสินค้าไม่ได้</span>
																<?php }?>
																<?php if($wpm_status_code == "30" && $can_wpm && $wpd_status == "") {?>
																	<a href="javascript:void(0)" onclick='cancel_dlvm40_selected("<?php echo $wpd_id_array[$w]?>","<?php echo $wpd_dlvm_nbr_array[$w]?>")'>
																		<span style="color:red">(x) ยกเลิกรายการนี้</span>
																	</a>
																<?php }?>
															</td>
															<?php if(inlist("10,20",$wpm_status_code) && $can_wpm) {?>
																<td align=center>
																	<a href="javascript:void();" onclick='del_dlvm_selected("<?php echo $wpd_id_array[$w]?>","<?php echo $wpm_nbr?>","<?php echo $wpd_dlvm_nbr_array[$w]?>")'><font color=red><<</font></a>
																</td>
															<?php }?>
															<?php if($wpm_status_code >= "30" && $can_wpm) {?>
																<?php if($wpd_status == "") {?>
																	<input type="hidden" name="wpd_id_<?php echo $wpd_group."_".$wpd_id_array[$w]?>" value="<?php echo $wpd_id_array[$w];?>">
																<?php }?>
																<td align=center style="text-align:center;width:20px;">
																	<?php if($wpd_status_array[$w] == "" && $can_wpm) {?>
																		<input type="radio" name="radio_wpd_<?php echo $wpd_group."_".$wpd_id_array[$w]?>" value="Y" onclick="RadioHighLightColor(document.all.radio_wpd_<?php echo $wpd_group."_".$wpd_id_array[$w]?>,'green')">
																	<?php }?>
																	<?php if($wpd_status_array[$w] == "Y") {?>
																		<img src="../_images/collect.png" width=16>
																	<?php }?>
																</td>
																<td align=center style="text-align:center;width:20px;">
																	<?php if($wpd_status_array[$w] == "" && $can_wpm) {?>
																		<input type="radio" name="radio_wpd_<?php echo $wpd_group."_".$wpd_id_array[$w]?>" value="N" onclick="RadioHighLightColor(document.all.radio_wpd_<?php echo $wpd_group."_".$wpd_id_array[$w]?>,'red')">
																	<?php }?>
																	<?php if($wpd_status_array[$w] == "N") {?>
																		<img src="../_images/collect_red.png" width=16>
																	<?php }?>
																</td>	
															<?php }?>
														</tr>
														<?php if($wpm_status_code >= "30" && $last_of) {?>
															<tr>
																<td colspan=5><?php //echo $dlvm_nbr_ok?></td>
																<td colspan=3 style="text-align:right">
																	<?php if ($wpm_status_code < "90") {?>
																		<?php if (is_null($wpd_ivm_nbr)) {?>
																			<?php if ($can_wpm) {?>
																				<?php if ($can_create_invoice) {?>
																				<button type="button" id="btncreateinvoice" class="btn btn-small btn-success fileinput-button" onclick='createinvoicepost("<?php echo $wpd_group?>")'>
																					<i class="icon-check icon-white"></i>
																					<span>สร้างใบส่งของ</span>
																				</button>
																				<?php }?>
																			<?php }?>
																		<?php } else {?>
																			[สร้างใบส่งของแล้ว]
																		<?php }?>
																	<?php }?>
																</td>
															</tr>
														<?php }?>
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
	<form name="frm_selected_dlvm20_wpm" autocomplete=OFF method="post" action="../serverside/wpmmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('selected_dlvm_wpm'.$user_login)?>">
		<input type="hidden" name="dlvm20_nbr_list">
		<input type="hidden" name="wpm_nbr" value="<?php echo $wpm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $dlvm20_currentpage?>">
	</form>
	<form name="frm_add_dlvm20_wpm" autocomplete=OFF method="post" action="../serverside/wpmmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('add_dlvm_wpd'.$user_login)?>">
		<input type="hidden" name="dlvm20_nbr">
		<input type="hidden" name="wpm_nbr" value="<?php echo $wpm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $dlvm20_currentpage?>">
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
	<form name="frm_del_dlvm20_wpm" method="post" action="../serverside/wpmmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('del_dlvm_wpd'.$user_login)?>">			
		<input type="hidden" name="dlvm20_nbr">
		<input type="hidden" name="wpm_nbr">
		<input type="hidden" name="wpd_id">
		<input type="hidden" name="pg" value="<?php echo $currentpage?>">
	</form>	
	<form name="frm_cancel_dlvm40_wpm" method="post" action="../serverside/wpmmntpost.php">
		<input type="hidden" name="action" value="<?php echo md5('cancel_dlvm40_wpd'.$user_login)?>">			
		<input type="hidden" name="dlvm40_nbr">
		<input type="hidden" name="wpm_nbr" value="<?php echo $wpm_nbr?>">
		<input type="hidden" name="wpd_id">
		<input type="hidden" name="pg" value="<?php echo $currentpage?>">
	</form>	
	<form name="frm_createinvoice" method="post" action="../serverside/wpminvoicepost.php">
		<input type="hidden" name="action" value="<?php echo md5('create_invoice'.$user_login)?>">	
		<input type="hidden" name="wpm_nbr" value="<?php echo $wpm_nbr?>">
		<input type="hidden" name="wpd_id">
		<input type="hidden" name="wpd_result">
		<input type="hidden" name="pg" value="<?php echo $currentpage?>">
	</form>
	
	<div id="edit_dlvm_car" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_edit_dlvm_car" name="frm_edit_dlvm_car"  autocomplete=OFF method="post" action="../serverside/wpmmntpost.php">
			<input type="hidden" name="action" value="<?php echo md5('edit_dlvm_car'.$user_login)?>">
			<input type="hidden" name="wpm_nbr" value="<?php echo $wpm_nbr?>">
			<input type="hidden" name="dlvm_nbr">

			<input type="hidden" name="pg" value="<?php echo $pg?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%"> แก้ไขเที่ยวรถ <span id='div_dlvm_nbr'></span</h3>
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
					
					<tr>
						<td style="text-align:right;vertical-align: middle">หมายเลขอ้างอิง<br>[บริษัทขนส่ง]:</td>
						<td colspan=3><input name="dlvm_transport_ref_no" Placeholder="*Tracking Number" type="text" style="margin:auto;width: 250px;" maxlength="60"></td> 
					</tr>
					
					<tr>
						<td style="text-align:right;vertical-align: middle">ทะเบียนรถ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_car_nbr"  type="text" style="margin:auto;width: 150px;" maxlength="30"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">เบอร์ตืดต่อ:<font color=red>*</font></td>
						<td colspan=3><input name="dlvm_transport_driver_tel"  type="text" style="margin:auto;width: 150px;" maxlength="60"></td> 
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle">ชื่อผู้ขับรถ:</td>
						<td colspan=3><input name="dlvm_transport_driver_name"  type="text" style="margin:auto;width: 150px;" maxlength="100"></td> 
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button id="btn_save_edit_car" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" onclick="edit_dlvm_car_post()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>																																																			
	</div>
	<div id="wpm_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_wpm_edit" autocomplete=OFF method="post" action="../serverside/wpmpost.php">
			<input name="action" type="hidden" value="<?php echo md5('wpm_edit'.$user_login)?>">
			<input name="wpm_nbr" type="hidden">
			<input name="pg" type="hidden" value="<?php echo $pg?>">	
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">แก้ไขใบเตรียมขึ้นสินค้า ::</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-c	ondensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>จัดกลุ่มข้อมูล:</b></td>
						<td>
							<select name="wpm_group_type" style="width:250px">
								<option value="">--เลือก--</option>
								<option value="A">(A)-ตาม ลูกค้า+ทะเบียนรถ</option>
								<option value="B">(B)-ตาม วิธีการจัดส่ง</option>
								<option value="C">(C)-ตาม วิธีการจัดส่ง+ผู้ขอเบิก</option>
								<option value="D">(D)-ตาม ลูกค้า+จังหวัด</option>
								<option value="E">(E)-ตาม ลูกค้า+วิธีการจัดส่ง</option>
								<option value="F">(F)-ตาม ลูกค้า+หมายเหตุการจัดส่ง</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>หมายเหตุ:</b></td>
						<td>
							<input type="text" name="wpm_remark" class="inputtext_s" style="width: 250px;" maxlength="100">
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="wpmpostform('<?php echo "frm_wpm_edit";?>')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
		</form>																																																			
	</div>
	
	</body>
</html>
