<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
$in_focd_tmpsubmit = mssql_escape($_POST["in_focd_tmpsubmit"]);
$in_focd_mat_code = mssql_escape($_POST["in_focd_mat_code"]);
$in_focd_sptm_nbr = mssql_escape($_POST["in_focd_sptm_nbr"]);
$in_focd_notcomp = mssql_escape($_POST["in_focd_notcomp"]);
$in_focd_perpage = mssql_escape($_POST["in_focd_perpage"]);

$in_focm_tmpsubmit = mssql_escape($_POST["in_focm_tmpsubmit"]);
$in_focm_notcomp = mssql_escape($_POST["in_focm_notcomp"]);

If ($in_focd_tmpsubmit == "") {
	$in_focd_tmpsubmit = $_COOKIE['in_focd_tmpsubmit'];	
	$in_focd_mat_code = $_COOKIE['in_focd_mat_code'];
	$in_focd_sptm_nbr = $_COOKIE['in_focd_sptm_nbr'];
	$in_focd_notcomp = $_COOKIE['in_focd_notcomp'];
	$in_focd_perpage = $_COOKIE['in_focd_perpage'];
}
else {		
	setcookie("in_focd_tmpsubmit","",0);
	setcookie("in_focd_mat_code","",0);
	setcookie("in_focd_sptm_nbr","",0);
	setcookie("in_focd_notcomp","",0);
	setcookie("in_focd_perpage","",0);
}

If ($in_focm_tmpsubmit == "") {
	$in_focm_tmpsubmit = $_COOKIE['in_focm_tmpsubmit'];	
	$in_focm_notcomp = $_COOKIE['in_focm_notcomp'];
}
else {		
	setcookie("in_focm_tmpsubmit","",0);
	setcookie("in_focm_notcomp","",0);
}

//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_foc_first_into == "1") { 
	$in_focm_notcomp = "on";
	$in_focd_perpage = 50;
}
setcookie("spt_foc_first_into", "0",$expire, "/");	

if ($in_focd_mat_code != "") {
	if ($focd_criteria != "") { $focd_criteria = $focd_criteria . " AND "; }
	$focd_criteria = $focd_criteria . " focd_mat_code like '%$in_focd_mat_code%'";
}
setcookie("in_focd_mat_code", $in_focd_mat_code,0);	
//
if ($in_focd_sptm_nbr != "") {
	if ($focd_criteria != "") { $focd_criteria = $focd_criteria . " AND "; }
	$focd_criteria = $focd_criteria . " focd_sptm_nbr like '%$in_focd_sptm_nbr%'";
}
setcookie("in_focd_sptm_nbr", $in_focd_sptm_nbr,0);	
//
if ($in_focd_notcomp != "") {
	if ($focd_criteria != "") { $focd_criteria = $focd_criteria . " AND "; }
	$focd_criteria = $focd_criteria . " (focd_status_code <> '90')";	
}
setcookie("in_focd_notcomp", $in_focd_notcomp,0);
//

if ($in_focm_notcomp != "") {
	if ($focm_criteria != "") { $focm_criteria = $focm_criteria . " AND "; }
	$focm_criteria = $focm_criteria . " (focm_status_code <> '90')";	
}
setcookie("in_focm_notcomp", $in_focm_notcomp,0);
//
if (!is_numeric($in_focd_perpage) || $in_focd_perpage < 1) {
	$in_focd_perpage = 50;
}
setcookie("in_focd_perpage", $in_focd_perpage,0);	
//
if ($focd_criteria!= "") { 
	//$focd_criteria = " WHERE focd_status_code = '10' AND " . $focd_criteria; 
	if ($user_cri_focwh!="") {
		$focd_criteria = " WHERE " . $focd_criteria  . " AND " . $user_cri_focwh;
	}
	else {
		$focd_criteria = " WHERE " . $focd_criteria;
	}
}
else {
	//$focd_criteria = "WHERE focd_status_code = '10'";
	if ($user_cri_focwh!="") {
		$focd_criteria = " WHERE " . $user_cri_focwh;
	}
}	

//$focm_criteria = " WHERE " . $focm_criteria; 
//Who can Read
$focm_whocanread_role_access = "";
$focm_whocanread_role_array = explode(",",$user_role);																																							
for ($c=0;$c<count($focm_whocanread_role_array);$c++) {														
	if ($focm_whocanread_role_access != "") { $focm_whocanread_role_access = $focm_whocanread_role_access . " OR "; }
	$focm_whocanread_role_access = $focm_whocanread_role_access . "(focm_whocanread like ". "'%" . $focm_whocanread_role_array[$c]."%')";														
}
if ($focm_criteria != "") { 
	$focm_criteria = "WHERE " . $focm_criteria . " AND ((focm_whocanread like '%$user_login%') OR $focm_whocanread_role_access)"; 
}
else {
	$focm_criteria = "WHERE ((focm_whocanread like '%$user_login%') OR $focm_whocanread_role_access)";
}

$isrolewh = false;
if(inlist($user_role,"WH_HK") || inlist($user_role,"WH_NKIE") || inlist($user_role,"WH_NK1") || inlist($user_role,"WH_NK2") || inlist($user_role,"WH_CENTER")) {
	$isrolewh = true;
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
				$("input[id*='focd_id']:checkbox").prop('checked', $(this).prop('checked'));			
			});			
		});
		
	</script>
	<script language="javascript">
		
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
			setTimeout(function(){ window.location.reload(true); }, 3000);									
		}	
		function gotofocdpage(mypage) {					
			loadresult()
			document.frm_focd.focd_pg.value=mypage;
			document.frm_focd.submit();
		}
		
		function gotopage(mypage) {					
			loadresult()
			document.frm_focm.pg.value=mypage;
			document.frm_focm.submit();
		}	
		function createfocmpost() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var focd_id_list = "";
			var focd_cnt = 0;
			$('input[name^=focd_id_]').each(function() {
				if (this.checked) {
					if (focd_id_list != "") { focd_id_list = focd_id_list + ","; }
					focd_id_list = focd_id_list + this.value;
					focd_cnt++;
				}
			});
			
			if (focd_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกรายการที่ต้องการก่อนค่ะ";
			}
			
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {			
				var confirm_info = '<br><font color=blue>รายการที่เลือกไว้ทั้งหมด ' + focd_cnt+  ' รายการ</font>';
				var confirm_text = '<font color=red>ท่านต้องการทำรายการนี้ต่อ ไช่หรือไม่ ?</font>';
				
				bootbox.confirm({							
					message: "<center><h3>" + confirm_info + "<br>" + confirm_text + "</h3></center>",
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
							document.frm_create_focm.focd_id_list.value = focd_id_list;
							document.frm_create_focm.submit();
						} else {
							return;	
						}										
					}
				});								
			}
		}
		
		function setvalfocm(focm_nbr,focm_status_code,focm_dn_nbr) {	
			ResetRadio(document.frm_edit_focm.focm_status_code);
			var s;
			switch (focm_status_code) {
				case "10": s=0; break;
				case "20": s=1; break;
				case "30": s=2; break;
				case "90": s=3; break;
			}
			$('#div_focm_nbr').html("<h3>หมายเลข: " + focm_nbr + "</h3>");
			document.frm_edit_focm.focm_nbr.value = focm_nbr;
			document.frm_edit_focm.focm_dn_nbr.value = focm_dn_nbr;
			document.frm_edit_focm.focm_status_code[s].checked = true;
			document.frm_edit_focm.focm_status_code[s].style.backgroundColor='#00ff00'
			
		}
		function focmpostform() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			//var focm_dn_nbr = document.frm_edit_focm.focm_dn_nbr.value;
			var focm_status_code = getRadioValue(document.frm_edit_focm.focm_status_code);
			// if (focm_status_code == '30' || focm_status_code == '90') {
				// if (focm_dn_nbr == "") {
					// if (errortxt!="") {errortxt = errortxt + "<br>";}	
					// errorflag = true;
					// errortxt = errortxt + "กรุณาระบุหมายเลข DN ค่ะ";
				// }
			// }
			if (focm_status_code == '') {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกสถานะ ค่ะ";
			}
			if (errorflag) {
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_edit_focm.submit();
			}
		}
		function upload_focm_status_postform() {
			var filename = document.frm_upload_focm_status.focm_status_file.value;
			var ext = filename.substr(filename.lastIndexOf('.') + 1);
			if (filename == "" || ext != 'xls') {
				alert('System allow for excel 2003 (*.xls) file format only!!');
				return;
			}
			if(confirm('คุณต้องการ Update FOC Status จาก Excel File นี้ใช่หรือไม่ ?')) {			
				var result_text="";
				$.ajaxSetup({
					cache: false,
					contentType: false,
					processData: false
				}); 
				var formObj = $('#frm_upload_focm_status')[0];
				var formData = new FormData(formObj);
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: "POST",
					url: '../serverside/focpost.php',
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
									$(location).attr('href', 'focall.php?pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'sptdmnt.php?pg='+json.pg);
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
			var w = 650;
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
		function showmsg(msg) {
			$("#msghead").html("พบข้อผิดผลาดในการบันทึกข้อมูล");
			$("#msgbody").html(msg);
			$("#myModal").modal("show");
		}
</script>	
</head>
<body>		
	<?php
	//Page ของ focd_det
	$sql_cnt = "SELECT * FROM focd_det $focd_criteria";
	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	
	
	$focd_pagesize = $in_focd_perpage;
	$focd_totalrow = $max;
	$focd_totalpage = ($focd_totalrow/$focd_pagesize) - (int)($focd_totalrow/$focd_pagesize);
	if ($focd_totalpage > 0) {
		$focd_totalpage = ((int)($focd_totalrow/$focd_pagesize)) + 1;
	} else {
		$focd_totalpage = (int)$focd_totalrow/$focd_pagesize;
	}					
	if ($_REQUEST["focd_pg"]=="") {
		$focd_currentpage = 1;	
		$focd_end_row = ($focd_currentpage * $focd_pagesize) - 1;
		if ($focd_end_row > ($focd_totalrow - 1)) { $focd_end_row = $focd_totalrow - 1; }
		$focd_start_row = 0;
	} else {
		$focd_currentpage = $_REQUEST["focd_pg"];
		if ((int)$focd_currentpage < 1) { $focd_currentpage = 1; }
		if ((int)$focd_currentpage > (int)$focd_totalpage) { $focd_currentpage = $focd_totalpage; }
		$focd_end_row = ($focd_currentpage * $focd_pagesize) - 1;
		$focd_start_row = $focd_end_row - $focd_pagesize + 1;
		if ($focd_end_row > ($focd_totalrow - 1)) { $focd_end_row = $focd_totalrow - 1; }					
	}

	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($focd_totalpage) <= (int)($maxpage)) {
		$maxpage = $focd_totalpage;
	}		
	if ($focd_currentpage < $maxpage) {
		$focd_start_page = 1;
		$focd_end_page = $maxpage;	
	} else {		
		$focd_start_page = $focd_currentpage - $slidepage;
		$focd_end_page = $focd_currentpage + $slidepage;
		if ($focd_start_page <= 1) {
			$focd_start_page = 1;
			$focd_end_page = $maxpage;
		} 
		if ($focd_end_page >= $focd_totalpage) {
			$focd_start_page = $focd_totalpage - $maxpage + 1;
			$focd_end_page = $focd_totalpage;
		}
	}
	//Page ของ focm_mstr
	$sql_cnt = "SELECT * FROM focm_mstr $focm_criteria";
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
							<td><img src='../_images/foc-icon.png' width=32><span style='font-size:11pt'><b>@รายการที่ต้องเปิด FOC ในระบบ SAP</b></span></td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% colspan=2>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>													
										<td bgcolor="white" width=55% valign=top>
											<fieldset style="background-color:white;border-radius:4px;width:97%">
												<legend style="background-color:red;color:white;border-radius:4px;">ข้อมูลสินค้าที่ต้องทำการเปิด FOC</legend>
												<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
													<form name="frm_focd" method="post" autocomplete=OFF>
													<input type="hidden" name="in_focd_tmpsubmit" value="search">
													<input type="hidden" name="focd_pg">
													<?php if ($isrolewh) {?>
														<tr bgcolor="lightgray">
															<td colspan=7>	
																<button type="button" class="btn btn-mini" onclick="createfocmpost()" style='background:blue;color:white;font-size:8pt'>สร้างรายการรอดำเนินการ FOC</button><br>
															</td>
														</tr>
													<?php }?>
													<tr bgcolor="lightgray">
														<td style="width:160px">
															<input name="in_focd_notcomp" type="checkbox" <?php if ($in_focd_notcomp=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"
															onclick="showfocddata()"> <font color=red><b>แสดงงานที่ยังไม่เสร็จเท่านั้น</b>
														</td>
														<td style="width:90px;text-align:right">
															<b>รายการต่อหน้า:</b>
														</td>
														<td style="width:70px">
															<input type="text" name="in_focd_perpage" class="inputtext_s" style="width:30px;text-align:center" value="<?php echo $in_focd_perpage?>">
														</td>
														<td style="width:70px;text-align:right"><b>Request No:</b></td>
														<td style="width:90px;">
															<input type='text' name="in_focd_sptm_nbr" class="inputtext_s" style='width:80px;font-size:8pt;color:blue'  value="<?php echo $in_focd_sptm_nbr?>">
														</td>
														<td align=right><b>Material:</b></td>
														<td style='width:160px'>
															<input type='text' name="in_focd_mat_code" style='width:110px;font-size:8pt;color:blue' class="inputtext_s" value="<?php echo $in_focd_mat_code?>">
															<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
														</td>
													</tr>
													<tr bgcolor="lightgray">
														<td colspan=2>
															(<font color=red><?php echo $focd_totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $focd_totalrow;?></font>&nbsp;items)								
															<b>Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotofocdpage(document.frm_focd.jumto.value)">
															&nbsp;
														</td>
	
														<td colspan=5 class="f_bk8" align=right>
															<?php
															if ($focd_start_page > 1) {																				
																echo "<A href='javascript:gotofocdpage(1)' class='paging'>First</a>&nbsp;";
															}														
															for ($pg=$focd_start_page; $pg<=$focd_end_page; $pg++) {											
																if ((int)($focd_currentpage) == (int)($pg)) {											
																	echo "<A href='javascript:gotofocdpage(" . $pg . ")' class='pageselected'><u><b>" . $pg . "</b></u></a>";
																} else {											
																	echo "<A href='javascript:gotofocdpage(" . $pg . ")' class='paging'>" . $pg . "</a>";
																}									
																if ($focd_pg<>$focd_totalpage) {
																	echo "&nbsp;";
																}
															}												
															if ($focd_end_page < $focd_totalpage) {										
																echo "<A href='javascript:gotofocdpage(" . $focd_totalpage . ")' class='paging'>Last</a>";
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
														<td style="width:120px;"><input type="checkbox" id='chkall' name="chkall">รหัสสินค้า<br>Request No</td>
														<td style="width:200px;text-align:center">ชื่อสินค้า</td>
														<td style="width:40px;text-align:right">จำนวน</td>
														<td style="width:40px;text-align:center">หน่วย</td>
														<td style="width:40px;text-align:center">วันที่สร้าง<br>ต้องการ</td>
														<td style="width:30px;text-align:center">รอแล้ว<br>เหลือ</td>
													</tr>
													</thead>   
													<tbody>
													<?php
													$thefirst=true;
													$mat_first_of = "";
													$n = 0;													
													$sql_focd = "SELECT focd.* FROM" .
													" (SELECT ROW_NUMBER() OVER(ORDER BY focd_status_code,focd_mat_code) AS rownumber,* FROM focd_det " .
													" INNER JOIN sptm_mstr ON sptm_nbr = focd_sptm_nbr " .
													" INNER JOIN material ON mat_code = focd_mat_code " .
													" $focd_criteria) as focd" .	
													" WHERE focd.rownumber > $focd_start_row and focd.rownumber <= $focd_start_row+$focd_pagesize";
													
													$result_focd = sqlsrv_query( $conn, $sql_focd);
													while($r_focd = sqlsrv_fetch_array($result_focd, SQLSRV_FETCH_ASSOC)) {	
														$focd_id = $r_focd['focd_id'];
														$focd_mat_code = $r_focd['focd_mat_code'];
														$focd_mat_name = html_quot($r_focd['mat_th_name']);
														if ($r_focd['mat_th_name'] != $r_focd['mat_en_name']) {
															$focd_mat_name .= "<br>" . html_quot($r_focd['mat_en_name']);
														}
														$focd_sptm_nbr = $r_focd['focd_sptm_nbr'];
														$focd_sptd_id = $r_focd['focd_sptd_id'];
														$focd_qty = $r_focd['focd_qty'];
														$focd_focm_nbr = $r_focd['focd_focm_nbr'];
														$focd_focm_dn_nbr = html_quot($r_focd['focd_focm_dn_nbr']);
														$focd_unit_code = $r_focd['focd_unit_code'];
														$focd_unit_name = findsqlval("unit_mstr","unit_name","unit_code",$focd_unit_code,$conn);
														$focd_status_code = $r_focd['focd_status_code'];
														$focd_status_date = $r_focd['focd_status_date'];
														$focd_expect_date = $r_focd['focd_expect_date'];
														$focd_create_date = $r_focd['focd_create_date'];
														
														
														if ($focd_status_code < 90 && $isrolewh) {
															$can_editing = true;
														} else {
															$can_editing = false;
															$focd_color = '';
															if ($focd_status_code == '90') {
																$focd_color = 'red';
															}
														}

														$day_wait = "";
														$focd_focm_nbr_text = "";
														$focd_focm_dn_nbr_text = "";
														if (inlist('10,20,30',$focd_status_code)) {
															$day_wait = day_diff(date_format($focd_create_date,'Ymd'),date('Ymd'))+1 . ' วัน';
														}
														if (inlist('90',$focd_status_code)) {
															$day_wait = day_diff(date_format($focd_create_date,'Ymd'),date_format($focd_status_date,'Ymd'))+1 . ' วัน';
															$focd_focm_nbr_text = "<span style='color:blue'>FOC: " .$focd_focm_nbr . "</span>";
															$focd_focm_dn_nbr_text = "<span style='color:blue'>DN: " .$focd_focm_dn_nbr . "</span>";
														}
														$day_expect = day_diff(date('Ymd'),$focd_expect_date)+1 . ' วัน';

														$n++;	
														?>	
														<?php
														$last_of = false;
														$group_cnt = $group_cnt + 1;
														
														if ($focd_mat_code != $mat_first_of) {
															if ($mat_first_of != "") { $thefirst=false; }
															$keep_mat_last = $mat_first_of;
															$mat_first_of = $focd_mat_code;
															$last_of = true;
														}
														if ($last_of) {
															if (!$thefirst) {
																if ($group_cnt > 1) {
																?>
																	<tr>
																		<td colspan=3 style="background:gray;text-align:center;color:white;font-weight:bold">ยอดรวมของ: <?php echo $keep_mat_last?></td>
																		<td style="background:green;color:white;text-align:center;font-size:10pt"><?php echo $group_cnt?></td>
																		<td colspan=3 style="background:gray;"></td>
																	</tr>
																<?php 
																} 
																else {?>
																	<tr><td colspan=7 style="background:#90FEED;text-align:center"></td></tr>
																<?php }
															}
															$group_cnt = 0;
														}?>
														<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $n+($focd_currentpage-1)*$focd_pagesize; ?></td>
															<td class="f_bk8" style="">
																<?php if ($can_editing) {?>
																<input type="checkbox" name="focd_id_<?php echo $focd_id?>" id="focd_id_<?php echo $focd_id?>" value="<?php echo $focd_id?>">
																<?php 
																} else {
																	if ($isrolewh) {
																		?>
																		<img src='../_images/collect_red.png' width=16>
																	<?php }
																}?>
																<span style='color:<?php echo $focd_color?>'>
																	<?php echo $focd_mat_code;?><br>
																	<span style='color:red'><?php echo $focd_sptm_nbr;?></span>
																	<?php if($focd_focm_nbr_text!='') {echo "<br>". $focd_focm_nbr_text;}?>
																</span>
															</td>
															<td class="f_bk8" style="">
																<?php echo $focd_mat_name; ?>
																<?php if($focd_focm_dn_nbr_text!='') {echo "<br>". $focd_focm_dn_nbr_text;}?>
															</td>
															<td class="f_bk8" style="text-align:center;"><?php echo $focd_qty; ?></td>
															<td class="f_bk8" style="text-align:center;"><?php echo $focd_unit_name; ?></td>
															<td class="f_bk8" style="text-align:center;"><?php echo dmydb($focd_create_date,'y'); ?><br><?php echo dmyty($focd_expect_date); ?></td>
															<td class="f_bk8" style="text-align:center;"><?php echo $day_wait; ?><br><?php echo $day_expect; ?></td>
															
														</tr>
													<?php }?>
													<?php
													if ($group_cnt > 1) {
													?>
														<tr>
															<td colspan=3 style="background:gray;text-align:center;color:white;font-weight:bold">ยอดรวมของ: <?php echo $focd_mat_code?></td>
															<td style="background:green;color:white;text-align:center;font-size:10pt"><?php echo $group_cnt+1?></td>
															<td colspan=3 style="background:gray;"></td>
														</tr>
													<?php 
													} 
													else {?>
														<tr><td colspan=7 style="background:#90FEED;text-align:center"></td></tr>
													<?php }
													
													?>
													</tbody>
												</table>  
											</fieldset>
										</td>
										<td valign=top bgcolor="white" width=45% align=right>
											<fieldset style="background-color:white;border-radius:4px;width:95%">
												<legend style="background-color:blue;color:white;border-radius:4px;">
													<?php if ($isrolewh) {?>
													<a href="#upload_focm_status" role="button" style="color:gold; text-decoration:none;" data-toggle="modal"> ( ** Upload Status **)</a> |
													<?php }?>
													รายการที่รอดำเนินการทางด้าน FOC
												</legend>
												<table width=100%>
												<tr bgcolor="lightgray">
													<td width=100%>
													<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
														<form name="frm_focm" method="post" autocomplete=OFF>
														<input type="hidden" name="in_focm_tmpsubmit" value="search">
														<input type="hidden" name="pg">
														<tr>
															<td width=65%>
															(<font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
															<b>Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotopage(document.all.jumto.value)">
															&nbsp;
															
															<input name="in_focm_notcomp" type="checkbox" <?php if ($in_focm_notcomp=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"
																onclick="showfocmdata()"> <font color=red>แสดงเฉพาะงานที่ยังไม่เสร็จเท่านั้น
															</td>
	
															<td width=47% class="f_bk8" align=right>
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
														</form>
													</table> 							
													</td>						
												</tr>
												</table>
												<table class="table table-striped table-bordered table-condensed" width="98%" border="0" cellspacing="1" cellpadding="4">
													<thead>
													<tr valign="top" style="background-color:blue;color:white" height="25" align="center">
														<td style="width:30px;text-align:center">No</td>
														<td style="width:60px;text-align:center">หมายเลข</td>
														<td style="width:40px;text-align:right">จำนวน</td>
														<td style="width:80px;text-align:center">สถานะ</td>
														<td style="width:60px;text-align:center">DN</td>
														<td style="width:60px;text-align:center">วันที่สร้าง</td>
														<td style="width:40px;text-align:center">เวลา</td>
														<td style="width:50px;text-align:center">พิมพ์</td>
														<td style="width:40px;text-align:center">Action</td>
														<td></td>
													</tr>
													</thead>   
													<tbody>
													<?php	
													$n = 0;													
													$sql_focm = "SELECT focm.* FROM" .
													" (SELECT ROW_NUMBER() OVER(ORDER BY focm_status_code,focm_nbr) AS rownumber,* FROM focm_mstr $focm_criteria) as focm" .
													" WHERE focm.rownumber > $start_row and focm.rownumber <= $start_row+$pagesize";																																																														
													
													$result_focm = sqlsrv_query( $conn, $sql_focm);
													while($r_focm = sqlsrv_fetch_array($result_focm, SQLSRV_FETCH_ASSOC)) {	
														$focm_nbr = $r_focm['focm_nbr'];
														$focm_dn_nbr = html_quot($r_focm['focm_dn_nbr']);
														$focm_qty = $r_focm['focm_qty'];
														$focm_status_code = $r_focm['focm_status_code'];
														$focm_status_name = findsqlval("focs_mstr","focs_status_name","focs_status_code",$focm_status_code,$conn);
														$focm_status_by = $r_focm['focm_status_by'];
														$focm_status_date = $r_focm['focm_status_date'];
														$focm_printed = $r_focm['focm_printed'];
														$focm_first_print_date = $r_focm['focm_first_print_date'];
														$focm_first_print_by = $r_focm['focm_first_print_by'];
														$focm_create_by = $r_focm['focm_create_by'];
														$focm_create_date = $r_focm['focm_create_date'];
														$focm_curprocessor = $r_focm['focm_curprocessor'];
														
														//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
														$focm_can_editing = false;
														$focm_curprocessor_role_access = "";
														$focm_curprocessor_role_array = explode(",",$user_role);																										
														for ($c=0;$c<count($focm_curprocessor_role_array);$c++) {
															if (inlist($focm_curprocessor,$focm_curprocessor_role_array[$c])) {
																$focm_can_editing = true;
																break;
															}
														}
													
														switch ($focm_status_code) {
															case "10": $color="red"; break;
															case "20": $color="blue"; break;
															case "30": $color="blue"; break;
															case "90": $color="green"; break;
														}
														
														$day_wait = "";
														if (inlist('10,20,30',$focm_status_code)) {
															$day_wait = day_diff(date_format($focm_create_date,'Ymd'),date('Ymd'))+1 . ' วัน';
														}
														if (inlist('90',$focm_status_code)) {
															$day_wait = day_diff(date_format($focm_create_date,'Ymd'),date_format($focm_status_date,'Ymd'))+1 . ' วัน';
														}
														$n++;	
														?>													
														<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
															<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
															<td class="f_bk8" style="">
																<a href="javascript:void(0)" onclick="winpopup('focmdet.php?focmnumber=<?php echo encrypt($focm_nbr, $key)?>')">
																	<?php echo $focm_nbr; ?>
																</a>
															</td>
															<td class="f_bk8" style="text-align:right;"><?php echo $focm_qty; ?></td>
															<td class="f_bk8" style="color:<?php echo $color?>"><?php echo $focm_status_name?></td>
															<td class="f_bk8"><?php echo $focm_dn_nbr?></td>
															<td class="f_bk8" style="text-align:center;"><?php echo dmydb($focm_create_date,'Y'); ?></td>
															<td class="f_bk8" style="text-align:center;"><?php echo $day_wait; ?></td>
															<td class="f_bk8" style="text-align:center;">
															<?php if ($focm_can_editing) {?>
																<?php if (!$focm_printed) {?>
																	<a href='javascript:void(0);' class='f_red8' onclick="javascript:printform('focform01.php?focmnumber=<?php echo encrypt($focm_nbr, $key); ?>')">(สั่งพิมพ์)</a>
																<?php } else {?>
																	<a href='javascript:void(0);' class='f_red8' onclick="javascript:printform('focform01.php?focmnumber=<?php echo encrypt($focm_nbr, $key); ?>')">
																		<img src='../_images/printed0.png' width=32>
																	</a>
																<?php }?>
															<?php }?>
															</td>
															<td style='text-align:center'>
																<?php if ($focm_can_editing) {?>
																	<a href="#edit_focm" onclick='setvalfocm("<?php echo $focm_nbr?>","<?php echo $focm_status_code?>","<?php echo $focm_dn_nbr?>")' data-toggle="modal">Edit</a>
																<?php }?>
															</td>
															<td></td>
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
	
	<form name="frm_create_focm" autocomplete=OFF method="post" action="../serverside/focpost.php"  enctype="multipart/form-data">
		<input type="hidden" name="action" value="create_focm">
		<input type="hidden" name="focd_id_list">
	</form>
	
	<div id="edit_focm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form name="frm_edit_focm" autocomplete=OFF method="post" action="../serverside/focpost.php">	
			<input type="hidden" name="action" value="chgstatus_focm">
			<input type="hidden" name="focm_nbr">							
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
																		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">แก้ไขข้อมูล FOC</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="" width=100%>	
					<tbody bgcolor=#f5f5ef>																																																																				
					<tr>
						<td align=center colspan=2 style='text-align:center;background:white'>
							<div id='div_focm_nbr'></div>
						</td>
					</tr>
					<tr><td colspan=2 style='height:10px'> </td></tr>
					<tr>
						<td width=30%></td>
						<td style="font-size:9pt">
							<table>
								<?php
								
								$sql_focs = "SELECT focs_status_code,focs_status_name FROM focs_mstr order by focs_seq";												
								$result_focs_list = sqlsrv_query( $conn,$sql_focs);																													
								while($r_focs_list=sqlsrv_fetch_array($result_focs_list, SQLSRV_FETCH_ASSOC)) {
									$focs_status_code = $r_focs_list['focs_status_code'];
									$focs_status_name = $r_focs_list['focs_status_name'];
									?>
										<tr><td><input type="radio" name="focm_status_code" value="<?php echo $focs_status_code;?>"
										onclick="RadioHighLightColor(focm_status_code,'#00ff00')"
										> <span style='font-weight:bold;font-size:10pt'><?php echo $focs_status_name;?></span></td></tr>
									<?php
								}
								?>
								<!--tr><td style='color:red'><b>*** SAP DN *** :: (ระบุเมื่อได้เลย DN)</b></td></tr>
								<tr><td><input type="text" name="focm_dn_nbr" style='color:red;font-size:12pt;font-weight:bold;'></td></tr-->
							</table>
						</td>
					</tr>									
					</tbody>
				</table>					
			</div>
		
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="focmpostform()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>
		</form>																																															
	</div>
	
	<div id="upload_focm_status" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form id="frm_upload_focm_status" name="frm_upload_focm_status" autocomplete=OFF>		
			<input type="hidden" name="action" value="<?php echo md5('upload_focm_status'.$user_login)?>">						
			<input type="hidden" name="pg" value="<?php echo $pg;?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">FOC Status File</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-c	ondensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>File Excel:</b></td>
						<td colspan=3>
							<input name="focm_status_file" type="file">
						</td>
					</tr>																																			
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="upload_focm_status_postform()">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
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
	<form name="frmdelete" method="post" action="../serverside/expensepost.php">
		<input type="hidden" name="action" value="delete">			
		<input type="hidden" name="focd_nbr">
		<input type="hidden" name="pg">
	</form>	
	</body>
</html>
