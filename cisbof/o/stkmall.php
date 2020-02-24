<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_stkm_tmpsubmit = mssql_escape($_POST["in_stkm_tmpsubmit"]);
$in_stkm_mat_code = mssql_escape($_POST["in_stkm_mat_code"]);
$in_stkm_location = mssql_escape($_POST["in_stkm_location"]);


If ($in_stkm_tmpsubmit == "") {
	$in_stkm_tmpsubmit = $_COOKIE['in_stkm_tmpsubmit'];	
	$in_stkm_mat_code = $_COOKIE['in_stkm_mat_code'];
	$in_stkm_location = $_COOKIE['in_stkm_location'];
}
else {		
	setcookie("in_stkm_tmpsubmit","",0);
	setcookie("in_stkm_mat_code","",0);
	setcookie("in_stkm_location","",0);
}
//
if ($in_stkm_mat_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " stkm_mat_code like '%$in_stkm_mat_code%' OR mat_th_name like '%$in_stkm_mat_code%' OR mat_en_name like '%$in_stkm_mat_code%'";
}
setcookie("in_stkm_mat_code", $in_stkm_mat_code,0);	
//
if ($in_stkm_location != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " stkm_location like '%$in_stkm_location%'";
}
setcookie("in_stkm_location", $in_stkm_location,0);	
//
if ($criteria != "") { $criteria = " WHERE " . $criteria; }

$can_mgmt_stock = false;
if (inlist($user_role,'SPT_ROOM')) {
	$can_mgmt_stock = true;
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
	<script>
	  document.addEventListener('keydown', function(event) {
		if(event.keyCode == 17 || event.keyCode == 74 )
		  event.preventDefault();
	  });
	</script>	
	<script type="text/javascript">
		$(document).ready(function () {     				                         				
			$("#in_stkm_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});			
		});		
	</script>
	
	<script language="javascript">	
		function helppopup(prgname,formname,opennerfield_code,txtsearch) {				
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
			var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&opennerfield_code='+opennerfield_code,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
			
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
		function isnumeric(num){
		  return !isNaN(num)
		}
		
		function gotopage(mypage) {						
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
		
		function iss_setvalstkm(stkm_mat_code,stkm_mat_name_x,stkm_qty_oh,pg) {
			var stkm_mat_name = replaceAll(stkm_mat_name_x, '+*+', '"');
			$('#iss_myModalLabel').html("<img src='../_images/minus-icon.png' width=32px> <span style='color:white'>ตัดสต๊อคเออกจากระบบ</span>");
			$('#div_iss_stkm_mat_code').html("<span style='font-size:10pt'>"+stkm_mat_code+"</span>");
			$('#div_iss_stkm_mat_name').html("<span style='font-size:8pt'>"+stkm_mat_name+"</span>");
			$('#div_iss_stkm_qty_oh').html("<span style='font-size:12pt;font-weight:bold;color:red'>"+stkm_qty_oh+"</span>");
			document.frm_stkm_iss.stkm_mat_code.value = stkm_mat_code;
			document.frm_stkm_iss.stkm_qty_oh.value = stkm_qty_oh;
			document.frm_stkm_iss.pg.value = pg;
		}
		function rct_setvalstkm(stkm_mat_code,stkm_mat_name_x,stkm_qty_oh,stkm_unit_code,pcs_per_box,stkm_location,pg) {
			//Reset Control
			var stkm_mat_name = replaceAll(stkm_mat_name_x, '+*+', '"');
			document.frm_stkm_rct.stkm_rct_unit.disabled = false;
			document.frm_stkm_rct.stkm_p_per_box.readOnly = false;
			document.frm_stkm_rct.stkm_rct_unit.value = "";
			document.frm_stkm_rct.stkm_location.value = "";
			//
			$('#myModalLabel').html("<img src='../_images/plus-icon.png' width=32px> <span style='color:white'>รับสต๊อคเข้าระบบ </span>");
			$('#div_stkm_mat_code').html("<span style='font-size:10pt'>"+stkm_mat_code+"</span>");
			$('#div_stkm_mat_name').html("<span style='font-size:8pt'>"+stkm_mat_name+"</span>");
			$('#div_stkm_qty_oh').html("<span style='font-size:12pt;font-weight:bold;color:red'>"+stkm_qty_oh+"</span>");
			if (stkm_unit_code == 'B') {
				document.frm_stkm_rct.stkm_rct_unit.value = "BOD";
				document.frm_stkm_rct.stkm_p_per_box.value = 1;
				document.frm_stkm_rct.stkm_rct_unit.disabled = true;
				document.frm_stkm_rct.stkm_p_per_box.readOnly = true;
			} else {
				document.frm_stkm_rct.stkm_p_per_box.value = pcs_per_box;
			}
			document.frm_stkm_rct.stkm_p_per_box_hidden.value = pcs_per_box;
		
			document.frm_stkm_rct.stkm_mat_code.value = stkm_mat_code;
			document.frm_stkm_rct.stkm_location.value = stkm_location;
			document.frm_stkm_rct.pg.value = pg;
		}
		function stkm_rct_unit_change(v) {
			if (v == 'PAN' || v == 'BOD') {
				document.frm_stkm_rct.stkm_p_per_box.value = 1;
				document.frm_stkm_rct.stkm_p_per_box.readOnly = true;
			} else {
				document.frm_stkm_rct.stkm_p_per_box.value = document.frm_stkm_rct.stkm_p_per_box_hidden.value;
				document.frm_stkm_rct.stkm_p_per_box.readOnly = false;
			}
		}
		function stkmrctpostform() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var stkm_rct_type = document.frm_stkm_rct.stkm_rct_type.value;
			var stkm_rct_remark = document.frm_stkm_rct.stkm_rct_remark.value;
			var stkm_rct_unit = document.frm_stkm_rct.stkm_rct_unit.value;
			var stkm_p_per_box = document.frm_stkm_rct.stkm_p_per_box.value;
			var stkm_qty = document.frm_stkm_rct.stkm_qty.value;
			
			if (stkm_rct_type == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกประเภทการรับ";
			}
			else {
				if (stkm_rct_type=='RCT-UNP' && stkm_rct_remark == "") {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "กรุณระบุหมายเหตุการรับแบบ RCT-UNP";
				}
			}
			if (stkm_rct_unit == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกหน่วยการรับ";
			}
			else {
				if (stkm_rct_unit=='BOX') {
					if (stkm_p_per_box.trim()=="") {
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "กรุณาระบุจำนวนแผ่นต่อกล่อง";		
					}
					else {
						if (!isnumeric(stkm_p_per_box)) {
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "กรุณาระบุจำนวนแผ่นต่อกล่องเป็นตัวเลขเท่านั้น";
						}
						else {
							if (stkm_p_per_box <= 0) {
								if (errortxt!="") {errortxt = errortxt + "<br>";}
								errorflag = true;
								errortxt = errortxt + "กรุณาระบุจำนวนแผ่นต่อกล่องเให้มากกว่า 0";
							}
						}
					}
				}
			}
			if (stkm_qty.trim()=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุจำนวนรับ";		
			}
			else {
				if (!isnumeric(stkm_qty)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุจำนวนรับเป็นตัวเลขเท่านั้น";
				}
				else {
					if (stkm_qty <= 0) {
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "กรุณาระบุจำนวนรับให้มากกว่า 0";
					}
				}
			}
			if (errorflag) {
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_stkm_rct.submit();
			}
		}
		function stkmisspostform() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var stkm_mat_code = document.frm_stkm_iss.stkm_mat_code.value;
			var accept_neg_stock = document.frm_stkm_iss.accept_neg_stock.value;
			var stkm_iss_remark = document.frm_stkm_iss.stkm_iss_remark.value;
			var stkm_qty_oh = document.frm_stkm_iss.stkm_qty_oh.value;
			var stkm_qty = document.frm_stkm_iss.stkm_qty.value;
			
			if (stkm_iss_remark.trim()=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุเหตุผลการตัด Stock ค่ะ";		
			}
			
			if (stkm_qty.trim()=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุจำนวนตัด";		
			}
			else {
				if (!isnumeric(stkm_qty)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุจำนวนตัดเป็นตัวเลขเท่านั้น";
				}
				else {
					if (stkm_qty <= 0) {
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "กรุณาระบุจำนวนตัดให้มากกว่า 0";
					} else {
						if (parseInt(stkm_qty) > parseInt(stkm_qty_oh)) {
							if (errortxt!="") {errortxt = errortxt + "<br>";}
							errorflag = true;
							errortxt = errortxt + "ไม่อนุญาติให้ตัด Stock มากว่ายอด Qty Onhand";
						}
					}
				}
			}
			
			
			if (accept_neg_stock == '0') {
				//get stkm_qty_oh
				var stkm_qty_oh;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {								
					if (xhttp.readyState == 4 && xhttp.status == 200) {								
						stkm_qty_oh = xhttp.responseText;							
					}
				}
				xhttp.open("POST", "../_chk/getstkmqtyoh.php",false);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
				xhttp.setRequestHeader("Pragma", "no-cache");
				xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
				xhttp.send("stkm_mat_code="+stkm_mat_code);
				
				if (stkm_qty_oh - stkm_qty < 0) {
					//ไม่อนุญาติให้ตัด stock จน stock ติดลบ
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ไม่อนุญาติให้ตัด stock จน stock ติดลบ";
				}
			}
			
			if (errorflag) {
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_stkm_iss.submit();
			}
		}
		
		
		function edit_setvallocation(stkm_mat_code,stkm_mat_name_x,stkm_location,pg) {
			var stkm_mat_name = replaceAll(stkm_mat_name_x, '+*+', '"');
			$('#div_location_stkm_mat_code').html("<span style='font-size:10pt'>"+stkm_mat_code+"</span>");
			$('#div_location_stkm_mat_name').html("<span style='font-size:8pt'>"+stkm_mat_name+"</span>");
			document.frm_edit_location.stkm_mat_code.value = stkm_mat_code;
			document.frm_edit_location.stkm_location.value = stkm_location;
			document.frm_edit_location.pg.value = pg;
		}
		function stkmeditlocationpostform() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var stkm_location = document.frm_edit_location.stkm_location.value;
			if (stkm_location == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกประเภทการรับ";
			}
			if (errorflag) {
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_edit_location.submit();
			}
		}
		
		function upload_stkm_postform() {
			var filename = document.frm_upload_stkm.stkm_file.value;
			var ext = filename.substr(filename.lastIndexOf('.') + 1);
			
			if (filename == "" || ext != 'xls') {
				alert('System allow for excel 2003 (*.xls) file format only!!');
				return;
			}
			if(confirm('คุณต้องการ Update Stock จาก Excel File นี้ใช่หรือไม่ ?')) {			
				var result_text="";
				$.ajaxSetup({
					cache: false,
					contentType: false,
					processData: false
				}); 
				var formObj = $('#frm_upload_stkm')[0];
				var formData = new FormData(formObj);
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: "POST",
					url: '../serverside/stkmuploadpost.php',
					data: formData,
					timeout: 600000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(data) {
						//console.log(data);
						
						
						var json = $.parseJSON(data);
						//alert(json.err);
						
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
									$(location).attr('href', 'stkmall.php?pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'stkmall.php?pg='+json.pg);
							}
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
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
		function winpopuprct(prgname) {				
			var w = 400;
			var h = 450;
			var winl = (screen.width-w)/2;
			var wint = (screen.height-h)/2;
			var settings ='height='+h+',';
			settings +='width='+w+',';
			settings +='top='+wint+',';
			settings +='left='+winl+',';
			settings +='scrollbars=no,';
			settings +='toolbar=no,';
			settings +='location=no,'; 
			settings +='resizable=no';		
			var myWindow=window.open(prgname,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
		}
		function showmsg(msg) {
			$("#msghead").html("พบข้อผิดผลาดในการบันทึกข้อมูล");
			$("#msgbody").html(msg);
			$("#myModal").modal("show");
		}
</script>	
</head>
<body>		
	<?php				
	
	//นับจำนวนตาม criteria
	$sql_cnt =  "SELECT * FROM stkm_mstr INNER JOIN material ON mat_code = stkm_mat_code $criteria";
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
						<tr bgcolor="lightgray">
							<td><img src='../_images/inven-icon.png' width=32>
								<span style='font-size:11pt'><b>@คลังตัวอย่าง</b></span>
							</td>
							<td align=right>
								<?php if (inlist($user_role,"ADMIN") || inlist($user_role,"SPT_ROOM")) {?>
								<a href="../masmnt/stkrmnt.php" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
									<div class="btn btn-small btn-danger paddingleftandright10" style="margin:auto;">
										<img src='../_images/setup.png' width=24px>														
										<span>เพิ่มเหตุผลการปรับสต๊อค</span>
									</div>
								</a>
								<?php }?>
								<?php if (inlist($user_role,"ADMIN")) {?>
								<a href="#upload_stkm" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
									<div class="btn btn-small btn-danger paddingleftandright10" style="margin:auto;">
										<img src='../_images/upload-icon.png' width=32px>														
										<span>Upload Stock</span>
									</div>
								</a>
								<?php }?>
								<?php if ($can_mgmt_stock) {?>
								<a href="javascript:void(0)" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
									<div class="btn btn-small btn-success paddingleftandright10" style="margin:auto;" onclick="winpopuprct('stkmrct.php')">
										<img src='../_images/download-icon.png' width=24px>														
										<span>รับสต๊อคสินค้าตัวอย่าง</span>
									</div>
								</a>
								<?php }?>
							</td>
							
							
						</tr>				
						<tr>
							<td valign=top colspan=2>
								<table width="50%" border="0" cellpadding=2 cellspacing=2 bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="stkmall.php">
									<input type="hidden" name="in_stkm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
									<tr>
										<td style="width:80px;" class="f_bk8b">รหัส/ชื่อสินค้า<font color=red><b>*</b></font>:</td>
										<td style="width:100px">
											<input name="in_stkm_mat_code" value="<?php echo $in_stkm_mat_code?>" class="inputtext_s" style='color:blue;width:150px'>												
										</td>
										<td style="width:100px;text-align:right" class="f_bk8b">สถานที่จัดเก็บ<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_stkm_location" value="<?php echo $in_stkm_location?>" class="inputtext_s" style='color:blue'>												
										</td>
										
										<td style="">
											<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
										</td>
									</tr>
									</form>									
								</table>
							</td>
						</tr>
						<tr bgcolor="lightgray">
							<td width=100% colspan=2>
							<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
								<tr>
									<td width=30%>
									(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
									<b>Jump To Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotopage(document.all.jumto.value)">
									</td>
									<td width=40% class="f_bk8" align=right>
									<?php
									if ($start_page > 1) {																				
										echo "<A href='javascript:gotopage(1)' class='paging'>First</a>&nbsp;";
									}														
									for ($pg=$start_page; $pg<=$end_page; $pg++) {											
										if ((int)($currentpage) == (int)($pg)) {											
											echo "<A href='javascript:gotopage(" . $pg . ")' class='pageselected'><u><b>" . $pg . "</b></u></a>";
										} else {											
											echo "<A href='javascript:gotopage(" . $pg . ")' class='paging'>" . $pg . "</a>";
										}									
										if ($pg<>$totalpage) {
											echo "&nbsp;";
										}
									}												
									if ($end_page < $totalpage) {										
										echo "<A href='javascript:gotopage(" . $totalpage . ")' class='paging'>Last</a>";
									}
									?>																		
									</td>
								</tr>
							</table> 							
							</td>						
						</tr>
						<tr>
							<td width=100% colspan=2>									
								<table class="table table-striped table-bordered table-condensed" width="100%" border="0" cellspacing="1" cellpadding="4">
									<thead>
									<tr valign="top" bgcolor="#fecf03">
										<td style="width:30px;text-align:center">No</td>
										<td style="width:65px;text-align:center">รหัสสินค้า</td>
										<td style="width:180px;text-align:center">ชื่อสินค้า</td>
										<td style="width:100px;text-align:center">จำนวนแผ่น/กล่อง</td>
										<td style="width:150px;text-align:center">สถานที่จัดเก็บ</td>
										<td style="width:40px;text-align:center">น้อยที่สุด</td>
										<td style="width:40px;text-align:center">มากที่สุด</td>
										<td style="background:green;color:white;width:40px;text-align:center">Available</td>
										<td style="background:red;color:white;width:40px;text-align:center">Reserve</td>
										<td style="background:blue;color:white;width:40px;text-align:center">Onhand</td>
										<td style="width:100px;text-align:center">Action</td>
										<td style="width:10px;">&nbsp;</td>
									</tr>
									</thead>   
									<tbody>
									<?php
									$n = 0;													
									$sql_stkm = "SELECT stkm.* FROM" .
									" (SELECT ROW_NUMBER() OVER(ORDER BY stkm_mat_code) AS rownumber,* FROM stkm_mstr INNER JOIN material ON mat_code = stkm_mat_code $criteria) as stkm" .
									" WHERE stkm.rownumber > $start_row and stkm.rownumber <= $start_row+$pagesize";																																																														
									
									$result_stkm = sqlsrv_query( $conn, $sql_stkm);
									while($r_stkm = sqlsrv_fetch_array($result_stkm, SQLSRV_FETCH_ASSOC)) {	
										$stkm_mat_code = $r_stkm['stkm_mat_code'];
										$stkm_mat_name = html_quot($r_stkm['mat_th_name']);
										$stkm_unit_code = $r_stkm['stkm_unit_code'];
										$stkm_qty_resv = $r_stkm['stkm_qty_resv'];
										$stkm_qty_oh = $r_stkm['stkm_qty_oh'];
										$stkm_location = html_quot($r_stkm['stkm_location']);
										$stkm_qty_min = $r_stkm['stkm_qty_min'];
										$stkm_qty_max = $r_stkm['stkm_qty_max'];
										$stkm_create_by = $r_stkm['stkm_create_by'];
										$stkm_create_date = $r_stkm['stkm_create_date'];
										
										
										$mat_pcs_per_box = $r_stkm['mat_pcs_per_box'];
										$mat_um_conv = $r_stkm['mat_um_conv'];
										
										$pcs_per_box = "";
										if ($mat_pcs_per_box != "") {
											$pcs_per_box = "";
											$mat_pcs_per_box_array = explode(" ",$mat_pcs_per_box);
											$pcs_pos = sizeof($mat_pcs_per_box_array) - 2;
											$pcs_per_box = $mat_pcs_per_box_array[$pcs_pos];
										}
										$stkm_qty_avai = $stkm_qty_oh - $stkm_qty_resv;
										$n++;																										
										?>	
										<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
											<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
											
											<td class="f_bk8" style="padding-left:5px;"><?php echo $stkm_mat_code?></td>
											<td class="f_bk8" style="padding-left:5px;"><?php echo $stkm_mat_name; ?></td>
											<td><?php echo $mat_pcs_per_box; ?></td>
											<td>
												<?php if ($can_mgmt_stock) {?>
													<a href="#edit_location" onclick='edit_setvallocation("<?php echo $stkm_mat_code?>","<?php echo str_replace('&quot;','+*+',$stkm_mat_name)?>","<?php echo $stkm_location?>","<?php echo $currentpage?>")' data-toggle="modal">
														<?php echo $stkm_location; ?>
													</a>
												<?php } else {?>
													<?php echo $stkm_location; ?>
												<?php }?>
											</td>
											<td style='text-align:center'><?php echo number_format($stkm_qty_min,0,"",","); ?></td>
											<td style='text-align:center'><?php echo number_format($stkm_qty_max,0,"",","); ?></td>
											<td style="text-align:center;<?php if($stkm_qty_avai <= 0) {echo 'color:red;font-weight:bold';}?>"><?php echo number_format($stkm_qty_avai,0,"",","); ?></td>
											<td style='text-align:center'><?php echo number_format($stkm_qty_resv,0,"",","); ?></td>
											<td style='text-align:center'>
												<a href="javascript:void(0)" onclick="winpopup('stkmdet.php?mat_code=<?php echo encrypt($stkm_mat_code, $key)?>')">
													<?php echo number_format($stkm_qty_oh,0,".",","); ?>
												</a>
											</td>
											<td style="text-align:center;">
												<?php if ($can_mgmt_stock) {?>
												<center>
													<img src='../_images/minus-icon.png' width=16px>
													<a href="#edit_stkm_iss" onclick='iss_setvalstkm("<?php echo $stkm_mat_code?>","<?php echo str_replace('&quot;','+*+',$stkm_mat_name)?>","<?php echo number_format($stkm_qty_oh,0)?>","<?php echo $currentpage?>")' data-toggle="modal">
													<span style='color:red'>[ตัดสต๊อค]</span></a>
													<img src='../_images/plus-icon.png' width=16px>
													<a href="#edit_stkm_rct" onclick='rct_setvalstkm("<?php echo $stkm_mat_code?>","<?php echo str_replace('&quot;','+*+',$stkm_mat_name)?>","<?php echo number_format($stkm_qty_oh,0)?>","<?php echo $stkm_unit_code?>","<?php echo $pcs_per_box?>","<?php echo $stkm_location?>","<?php echo $currentpage?>")' data-toggle="modal">
													<span style='color:green'>[รับสต๊อค]</span></a>
												</center>
												<?php }?>
											</td>
											<td style="text-align:center">
												<?php if($activeid==$stkm_mat_code) {echo "<img src='../_images/active-id.png'>";}?>
											</td>
										</tr>
									<?php }?>	
									</tbody>
								</table>  	
							</td>
						</tr>
					</table>
				</td>
			</tr>							
		</table>	
		
	</div>
	
	<div id="edit_stkm_rct" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form name="frm_stkm_rct" autocomplete=OFF method="post" action="../serverside/stkmpost.php">	
			<input type="hidden" name="action" value="stkm_rct">
			<input type="hidden" name="stkm_mat_code">							
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
			<input type="hidden" name="stkm_p_per_box_hidden">
																		
			<div class="modal-header" style='background:green'>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"></h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-condensed table-bordered" border=0 width=100% cellpadding=2 cellspacing=2>	
					<tbody>																																																																				
					<tr><td colspan=2 style="background:white;height:10px"></td></tr>
					<tr>
						<td width=25% style="text-align:right;font-size:10pt;vertical-align: middle;"><b>QTY OH</b></td>
						<td style="font-size:8pt"><span id='div_stkm_qty_oh'></span></td>
					</tr>
					<tr>
						<td width=25% style="text-align:right;font-size:10pt;vertical-align: middle;"><b>รหสัสสินค้า:</b></td>
						<td style="font-size:8pt"><span id='div_stkm_mat_code'></span></td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>ชื่อสินค้า:</b></td>
						<td style="font-size:8pt;color:blue"><span id='div_stkm_mat_name'></span></td>
					</tr>
					
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>ประเภทรับ:</b></td>
						<td>
							<select name="stkm_rct_type" style="color:blue;width:120px;margin:auto">
								<option value="">--เลือกประเภท--</option>
								<option value="RCT-FOC">RCT-FOC</option>
								<option value="RCT-UNP">RCT-UNP</option>
							</select>
							<input type="text" name="stkm_rct_remark" style='margin:auto;color:red' maxlength=255 placeholder="* ระบุเหตุผลการรับแบบ RCT-UNP *">
							<button type="button" class="btn btn-default" style="margin: auto;" 
								OnClick="helppopup('../_help/getstkrmstr.php','frm_stkm_rct','stkm_rct_remark',document.frm_stkm_rct.stkm_rct_remark.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>สถานที่จัดเก็บ:</b></td>
						<td style="font-size:8pt">
							<input type="text" name="stkm_location" id="stkm_location" style='margin:auto;'>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>จำนวนรับ:</b></td>
						<td style="background:white;font-size:12pt;font-weight:bold">
							<input type="text" name="stkm_qty" style='color:green;width:90px;margin:auto;text-align:center;font-size:12pt;font-weight:bold'>
							
							<select name="stkm_rct_unit" style="color:blue;width:120px;margin:auto" onchange="javascript:stkm_rct_unit_change(this.value)">
								<option value="">--เลือกหน่วย--</option>
								<option value="BOX">กล่อง</option>
								<option value="PAN">แผ่น</option>
								<option value="BOD">บอร์ด</option>
							</select>
						</td>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>แผ่น/กล่อง:</b></td>
						<td style="font-size:8pt">
							<input type="text" name="stkm_p_per_box" style='margin:auto;text-align:center;width:90px;color:red;font-weight:bold'>
						</td>
					</tr>
														
					</tbody>
				</table>					
			</div>
		
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="stkmrctpostform()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
			
		</form>																																															
	</div>
	<div id="edit_stkm_iss" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form name="frm_stkm_iss" autocomplete=OFF method="post" action="../serverside/stkmpost.php">	
			<input type="hidden" name="action" value="stkm_iss">
			<input type="hidden" name="stkm_mat_code">	
			<input type="hidden" name="stkm_qty_oh">
			<input type="hidden" name="accept_neg_stock" value="<?php echo $accept_neg_stock?>">
			
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
			
														
			<div class="modal-header" style='background:red'>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="iss_myModalLabel"></h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-condensed table-bordered" border=0 width=100% cellpadding=2 cellspacing=2>	
					<tbody>																																																																				
					<tr><td colspan=2 style="background:white;height:10px"></td></tr>
					<tr>
						<td width=25% style="text-align:right;font-size:10pt;vertical-align: middle;"><b>QTY OH</b></td>
						<td style="font-size:8pt"><span id='div_iss_stkm_qty_oh'></span></td>
					</tr>
					<tr>
						<td width=30% style="text-align:right;font-size:10pt;vertical-align: middle;"><b>รหสัสสินค้า:</b></td>
						<td style="font-size:8pt"><span id='div_iss_stkm_mat_code'></span></td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>ชื่อสินค้า:</b></td>
						<td style="font-size:8pt;color:blue"><span id='div_iss_stkm_mat_name'></span></td>
					</tr>
					<tr>
						<td style="matgin:auto;text-align:right;font-size:10pt;vertical-align: middle;"><b>เหตุผลการตัด:</b></td>
						<td>
							<input type="text" style='margin:auto' name="stkm_iss_remark" placeholder="* ระบุเหตุผลการตัด *">
							<button type="button" class="btn btn-default" style="margin: auto;" 
								OnClick="helppopup('../_help/getstkrmstr.php','frm_stkm_iss','stkm_iss_remark',document.frm_stkm_iss.stkm_iss_remark.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
					
					<tr>
						<td style="text-align:right;color:red;font-size:10pt;vertical-align: middle;"><b>จำนวนตัด:</b></td>
						<td style="background:white;font-size:12pt;font-weight:bold">
							<input type="text" name="stkm_qty" style='color:red;width:90px;margin:auto;text-align:center;font-size:12pt;font-weight:bold'>
							<span style='font-size:10pt;color:red'>แผ่น/บอร์ด</span>
						</td>
					</tr>
					
														
					</tbody>
				</table>					
			</div>
		
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="stkmisspostform()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
			
		</form>																																															
	</div>		
	<div id="edit_location" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form name="frm_edit_location" autocomplete=OFF method="post" action="../serverside/stkmpost.php">	
			<input type="hidden" name="action" value="stkm_edit_location">
			<input type="hidden" name="stkm_mat_code">	
			<input type="hidden" name="pg" value="<?php echo $pg;?>">					
			<div class="modal-header" style='background:red'>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="iss_myModalLabel">แก้ไขสถานที่จัดเก็บ</h3>
			</div>
			<div class="">
				<table class="table table-condensed table-bordered" border=0 width=100% cellpadding=2 cellspacing=2>	
					<tbody>																																																																				
					<tr><td colspan=2 style="background:white;height:10px"></td></tr>
					<tr>
						<td width=30% style="text-align:right;font-size:10pt;vertical-align: middle;"><b>รหสัสสินค้า:</b></td>
						<td style="font-size:8pt"><span id='div_location_stkm_mat_code'></span></td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:10pt;vertical-align: middle;"><b>ชื่อสินค้า:</b></td>
						<td style="font-size:8pt;color:blue"><span id='div_location_stkm_mat_name'></span></td>
					</tr>
					<tr>
						<td style="matgin:auto;text-align:right;font-size:10pt;vertical-align: middle;"><b>สถานที่จัดเก็บ:</b></td>
						<td>
							<input type="text" style='margin:auto' name="stkm_location">
						</td>
					</tr>						
					</tbody>
				</table>					
			</div>
		
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="stkmeditlocationpostform()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
			
		</form>																																															
	</div>
	<div id="upload_stkm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form id="frm_upload_stkm" name="frm_upload_stkm" autocomplete=OFF>		
			<input type="hidden" name="action" value="<?php echo md5('upload_stkm'.$user_login)?>">						
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Stock File</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-c	ondensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>File Excel:</b></td>
						<td colspan=3>
							<input name="stkm_file" type="file">
						</td>
					</tr>																																			
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="upload_stkm_postform()">
					<i class="icon-check icon-white"></i>
					<span>Start Upload</span>
				</button>											
			</div>												
		</form>																																																			
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
	<form name="frmdelete" method="post" action="../serverside/sptmpost.php">
		<input type="hidden" name="action" value="delete">			
		<input type="hidden" name="stkm_nbr">
		<input type="hidden" name="pg">
	</form>		
	</body>
</html>
