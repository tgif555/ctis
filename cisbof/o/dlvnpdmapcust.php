<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_sptmmap_tmpsubmit = mssql_escape($_POST["in_sptmmap_tmpsubmit"]);
$in_sptmmap_npd_customer = mssql_escape($_POST["in_sptmmap_npd_customer"]);
$in_sptmmap_npd_customer_name = mssql_escape($_POST["in_sptmmap_npd_customer_name"]);
$in_sptmmap_npd_customer_amphur = mssql_escape($_POST["in_sptmmap_npd_customer_amphur"]);
$in_sptmmap_npd_customer_province = mssql_escape($_POST["in_sptmmap_npd_customer_province"]);
$in_sptmmap_showfirstrec = mssql_escape($_POST["in_sptmmap_showfirstrec"]);
$in_sptmmap_notshowmap = mssql_escape($_POST["in_sptmmap_notshowmap"]);

If ($in_sptmmap_tmpsubmit == "") {
	$in_sptmmap_tmpsubmit = $_COOKIE['in_sptmmap_tmpsubmit'];	
	$in_sptmmap_npd_customer = $_COOKIE['in_sptmmap_npd_customer'];
	$in_sptmmap_npd_customer_name = $_COOKIE['in_sptmmap_npd_customer_name'];
	$in_sptmmap_npd_customer_amphur = $_COOKIE['in_sptmmap_npd_customer_amphur'];
	$in_sptmmap_npd_customer_province = $_COOKIE['in_sptmmap_npd_customer_province'];
	$in_sptmmap_showfirstrec = $_COOKIE['in_sptmmap_showfirstrec'];
	$in_sptmmap_notshowmap = $_COOKIE['in_sptmmap_notshowmap'];
}
else {		
	setcookie("in_sptmmap_tmpsubmit","",0);
	setcookie("in_sptmmap_npd_customer","",0);
	setcookie("in_sptmmap_npd_customer_name","",0);
	setcookie("in_sptmmap_npd_customer_amphur","",0);
	setcookie("in_sptmmap_npd_customer_province","",0);
	setcookie("in_sptmmap_showfirstrec","",0);
	setcookie("in_sptmmap_notshowmap","",0);
}
//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_npdmapcust_first_into == "1") { 
	$in_sptmmap_showfirstrec = "on";
	$in_sptmmap_notshowmap = "on";
}
setcookie("spt_npdmapcust_first_into", "0",0, "/");
setcookie("in_sptmmap_showfirstrec", $in_sptmmap_showfirstrec,0);	
setcookie("in_sptmmap_notshowmap", $in_sptmmap_notshowmap,0);	

if ($in_sptmmap_npd_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (sptm_npd_brand IN (SELECT custnpd_brand FROM custnpd_mstr WHERE custnpd_customer_number = '$in_sptmmap_npd_customer' GROUP BY custnpd_brand))";
}
setcookie("in_sptmmap_npd_customer", $in_sptmmap_npd_customer,0);
setcookie("in_sptmmap_npd_customer_name", $in_sptmmap_npd_customer_name,0);
setcookie("in_sptmmap_npd_customer_amphur", $in_sptmmap_npd_customer_amphur,0);
setcookie("in_sptmmap_npd_customer_province", $in_sptmmap_npd_customer_province,0);	

if ($in_sptmmap_notshowmap != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_customer_number = 'NPD_NOCUST'";
}
setcookie("in_sptmmap_notshowmap", $in_sptmmap_notshowmap,0);

//
if ($criteria != "") {
	if (trim($in_sptmmap_npd_customer) == "") {
		$criteria = " WHERE sptm_step_code = '1000' AND sptm_npd_type = 'NPD_NOCUST'";
	}
	else {
		$criteria = " WHERE sptm_step_code >= '30' AND sptm_npd_type = 'NPD_NOCUST' AND " . $criteria; 
	}
}
else {
	$criteria = " WHERE sptm_step_code = '1000' AND sptm_npd_type = 'NPD_NOCUST'";
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
	
	<link href="../_libs/css/sptm.css" rel="stylesheet" type="text/css" />		
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
				$("input[id*='sptm_nbr']:checkbox").prop('checked', $(this).prop('checked'));
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
		function setvalue_sptm_selected() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var in_sptmmap_npd_customer = document.frm.in_sptmmap_npd_customer.value;
			var in_sptmmap_npd_customer_name = document.frm.in_sptmmap_npd_customer_name.value;
			var in_sptmmap_npd_customer_amphur = document.frm.in_sptmmap_npd_customer_amphur.value;
			var in_sptmmap_npd_customer_province = document.frm.in_sptmmap_npd_customer_province.value;
			
			if (in_sptmmap_npd_customer == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกลูกค้าที่ต้องการก่อนค่ะ";
			}
			var sptm_nbr_list = "";
			var sptm_cnt = 0;
			$('input[id*=sptm_nbr_]').each(function() {
				if (this.checked) {
					if (sptm_nbr_list != "") { sptm_nbr_list = sptm_nbr_list + ","; }
					sptm_nbr_list = sptm_nbr_list + this.value;
					sptm_cnt++;
				}
			});
			
			if (sptm_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "กรุณาเลือกรายการที่ต้องการก่อนค่ะ";
			}
			
			if (errorflag) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				document.frm_sptm_npd_nocust_shipment.sptm_nbr_list.value = sptm_nbr_list;
				document.frm_sptm_npd_nocust_shipment.sptm_customer_number.value = in_sptmmap_npd_customer;
				document.frm_sptm_npd_nocust_shipment.sptm_customer_name.value = in_sptmmap_npd_customer_name;
				document.frm_sptm_npd_nocust_shipment.sptm_customer_amphur.value = in_sptmmap_npd_customer_amphur;
				document.frm_sptm_npd_nocust_shipment.sptm_customer_province.value = in_sptmmap_npd_customer_province;
				$("#sptm_npd_nocust_shipment").modal("show");			
			}
		}
		
		
		function sptm_npdmapcustpost() {
			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			
			new_customer_number = document.frm_sptm_npd_nocust_shipment.sptm_customer_number.value;
			new_customer_amphur = document.frm_sptm_npd_nocust_shipment.sptm_customer_amphur.value;
			new_customer_province = document.frm_sptm_npd_nocust_shipment.sptm_customer_province.value;
			new_expect_receiver_name = document.frm_sptm_npd_nocust_shipment.sptm_expect_receiver_name.value;
			new_expect_receiver_tel = document.frm_sptm_npd_nocust_shipment.sptm_expect_receiver_tel.value;
			
			
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
						
			dlvm_transport_tspm_code = document.frm_sptm_npd_nocust_shipment.dlvm_transport_tspm_code.value;
			dlvm_transport_tspm_other = document.frm_sptm_npd_nocust_shipment.dlvm_transport_tspm_other.value;
			dlvm_transport_ref_no = document.frm_sptm_npd_nocust_shipment.dlvm_transport_ref_no.value;
			dlvm_transport_driver_name = document.frm_sptm_npd_nocust_shipment.dlvm_transport_driver_name.value;
			dlvm_transport_car_nbr = document.frm_sptm_npd_nocust_shipment.dlvm_transport_car_nbr.value;
			dlvm_transport_driver_tel = document.frm_sptm_npd_nocust_shipment.dlvm_transport_driver_tel.value;
			
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
					url: '../serverside/dlvnpdmapcustpost.php',
					data: $('#frm_sptm_npd_nocust_shipment').serialize(),
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
									$(location).attr('href', 'dlvnpdmapcust.php?pg='+json.pg);
								});
							}
							else {
								$(location).attr('href', 'dlvnpdmapcust.php?pg='+json.pg);
							}
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
						
					}
				});
			}
		}
		
		function cancelnpdmapcustpost(sptm_nbr) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			if(confirm('ท่านต้องการ ยกเลิกการจับคู่ลูกค้า ใบเบิกหมายเลข  ' + sptm_nbr + ' ใช่หรือไม่ ?')) {
				document.frm_cancel_npdmapcust.sptm_nbr.value = sptm_nbr;
				var result_text="";
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/dlvcancelnpdmapcustpost.php',
					data: $('#frm_cancel_npdmapcust').serialize(),
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
									$(location).attr('href', 'dlvnpdmapcust.php?pg='+json.pg);
									location.reload();
								});
							}
							else {
								$(location).attr('href', 'dlvnpdmapcust.php?pg='+json.pg);
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
		function selectsetno(v) {
			$("input[id*='sptm_nbr_"+v+"']").each(function() {
				if($(this).prop("checked") == true) {
					$(this).prop('checked',false);
					
				}
				else if($(this).prop("checked") == false) {
					$(this).prop('checked',true);
				}
			});
		}
		function loadresult() {
			$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
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
		
		function gotopage(mypage) {					
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
		function showmsg(msg) {
			$("#msgbody").html(msg);
			$("#myModal").modal("show");
		}
	</script>	
</head>
<body>		
	<?php				
	$sql_cnt = "SELECT * FROM sptm_mstr $criteria";
	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	

	$pagesize = 10000;
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
							<td><img src='../_images/delivery-icon.png' width=32><span style='font-size:11pt'><b>@NPD_NOCUST จับคู่ลูกค้าและทะเบียนรถ</b></span></td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% valign=top>
								<table width="100%" border="0" bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="dlvnpdmapcust.php">
									<input type="hidden" name="in_sptmmap_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
									<tr>
										<td style="width:10px"></td>
										<td style="">
											<b>รหัสลูกค้า:</b><br>
											<input name="in_sptmmap_npd_customer" class="inputtext_s" value="<?php echo $in_sptmmap_npd_customer?>" style='color:red;width:140px' readonly>												
											<button type="button" name="sptm_customer_help" id="sptm_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
												OnClick="helppopup_customer('../_help/getcustbrandnpd.php','frm','','',document.frm.in_sptmmap_npd_customer.value)">
												<span class="icon icon-search" aria-hidden="true"></span>
											</button>
											<b>ชื่อลูกค้า:</b>
											<input type="text"  name="in_sptmmap_npd_customer_name" class="inputtext_s" value="<?php echo $in_sptmmap_npd_customer_name?>" style="color:red;width:200px;" readonly>
											
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<input type="text" name="in_sptmmap_npd_customer_amphur" id="in_sptmmap_npd_customer_amphur" value="<?php echo $in_sptmmap_npd_customer_amphur?>" class="inputtext_s" style='color:red;width:140px' maxlength=255 readonly>
											<input name="in_sptmmap_showfirstrec" type="checkbox" <?php if ($in_sptmmap_showfirstrec=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:blue"><b>แสดงเฉพาะรายการแรกเท่านั้น </b></span>
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<input type="text" name="in_sptmmap_npd_customer_province" id="in_sptmmap_npd_customer_province" value="<?php echo $in_sptmmap_npd_customer_province?>" class="inputtext_s"  style='color:red;width:140px' maxlength=255 readonly>
											<input name="in_sptmmap_notshowmap" type="checkbox" <?php if ($in_sptmmap_notshowmap=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>แสดงรายการที่ไม่ได้จับคู่ </b></span>
										</td>
										
									</tr>
									<tr>
										<td></td>
										<td>
											<button type="button" id="btnshowdata" name="btnshowdata"  class="btn btn-mini" onclick="showdata()" style='background:orange;color:white;font-size:10pt'>แสดงรายการที่สามารถจับคู่ได้</button>
										</td>
										<td align=right>
											<?php if (inlist($user_role,"CS")) {?>
											<button type="button" class="btn btn-mini" onclick="setvalue_sptm_selected()" style='background:green;color:white;font-size:8pt'>>> จับคู่ใบเบิกกับลูกค้า</button>
											<?php }?>
										</td>
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
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>													
										<td bgcolor="white">											
											<table class="table table-striped table-bordered table-condensed" width="100%" border="0" cellspacing="1" cellpadding="4">
												<thead>
												<tr valign="top" style="background-color:#80bfff;" height="25" align="center">
													<th style="width:30px;text-align:center">No</th>
													<th style="width:100px;"><input type="checkbox" id='chkall' name="chkall">  Request No</th>
													<th style="width:150px;text-align:center">จำนวน Package</th>
													<th style="width:200px;text-align:center">Set No</th>
													<th style="width:70px;text-align:center">น้ำหนักรวม (KG)</th>
													<th style="text-align:center">ชื่อลูกค้า/อำเภอ/จังหวัด@ทะเบียนรถ</th>
													<th style="text-align:right">Action</th>
												</thead> 
												<tbody>
												<?php
												$firstof = false;
												$firstof_data = "";	
												$sql_sptm = "SELECT sptm.* FROM" .
													" (SELECT ROW_NUMBER() OVER(ORDER BY sptm_npd_setno,sptm_nbr) AS rownumber,* FROM sptm_mstr " .
													" $criteria) as sptm" .
													" WHERE sptm.rownumber > $start_row and sptm.rownumber <= $start_row+$pagesize";
												$result_sptm = sqlsrv_query( $conn, $sql_sptm);
												while($r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC)) {
													$group_setno = $r_sptm['sptm_npd_setno'];
													if ($group_setno != $first_of_data) {
														$first_of = true;
														$first_of_data = $group_setno;
														$n = 0;	
													}
													else {
														$first_of = false;
													}
													$sptm_nbr = $r_sptm['sptm_nbr'];
													$sptm_curprocessor = $r_sptm['sptm_curprocessor'];
													
													$sptm_delivery_mth_name = html_quot(findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn));
													$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
													$sptm_req_by = $r_sptm['sptm_req_by'];
													$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
													$sptm_req_date = $r_sptm['sptm_req_date'];
													//NPD
													$sptm_npd = $r_sptm['sptm_npd'];
													$sptm_npd_com = $r_sptm['sptm_npd_com'];
													$sptm_npd_brand = $r_sptm['sptm_npd_brand'];
													$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name", "brand_code", $sptm_npd_brand,$conn);
													$sptm_npd_setno = $r_sptm['sptm_npd_setno'];
													$sptm_npd_setno_name = substr($sptm_npd_setno,strpos($sptm_npd_setno,'@')+1,strlen($sptm_npd_setno));
																
													$sptm_customer_number = $r_sptm['sptm_customer_number'];
													$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
													
													//$sptm_transport_car_nbr = $r_sptm['sptm_transport_car_nbr'];
													
													$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
													$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
													$sptm_customer = $sptm_customer_name."/".$sptm_customer_amphur."/".$sptm_customer_province;
													$sptm_step_code = $r_sptm['sptm_step_code'];
													
													$package_cnt = 0;
													$package_weight = 0;
													$sql_package = "SELECT count(dlvm_nbr) 'package_cnt',sum(dlvm_packing_weight) 'package_weight'".
														   " FROM dlvm_mstr WHERE dlvm_sptm_nbr = '$sptm_nbr' and dlvm_dlvs_step_code <> '80'";
													
													$result_package = sqlsrv_query($conn, $sql_package); 
													$r_package = sqlsrv_fetch_array($result_package, SQLSRV_FETCH_ASSOC);		
													if ($r_package) {
														$package_cnt = (int)$r_package['package_cnt'];
														$package_weight = (int)$r_package['package_weight'];
													}
													if ($package_cnt == 0) {$package_cnt = "<span style='color:red'>*ยังไม่ได้สร้าง Package*</span>";}
													if ($package_weight == 0) {$package_weight = "<span style='color:red'>".$package_weight."</span>";}
													
													$n++;
													
													$show = true;
													if ($in_sptmmap_showfirstrec) {
														if ($n>1) { $show = false; }
													}
													?> 
													<?php if($first_of) {?>
														<tr>
															<td colspan=8 style="background: #cce6ff"><input type="checkbox" onclick="selectsetno(this.value);" name="<?php echo $group_setno?>" id="<?php echo $group_setno?>" value='<?php echo $group_setno?>'> <b><?php echo $sptm_npd_brand_name. " " .$group_setno?></b></td>
														</tr>
														<?php
														$existing_map = false;
														$sql_check_exist = "select sptm_nbr from sptm_mstr where sptm_customer_number = '$in_sptmmap_npd_customer' and sptm_npd_setno = '$sptm_npd_setno'";
														$result_check_exist = sqlsrv_query($conn, $sql_check_exist);
														$r_check_exist = sqlsrv_fetch_array($result_check_exist, SQLSRV_FETCH_ASSOC);		
														if ($r_check_exist) {
															$existing_map = true;
														}
														?>
													<?php }?>
													<?php if ($show) {?>
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="text-align:center;"><?php echo $n; ?></td>
														<td class="f_bk8" style="">
															<?php if ($sptm_customer_number == "NPD_NOCUST" && !$existing_map && $package_cnt > 0) {?>
																<input type="checkbox" name="sptm_nbr_<?php echo $group_setno.'_'.$sptm_nbr?>" id="sptm_nbr_<?php echo $group_setno.'_'.$sptm_nbr?>" value="<?php echo $sptm_nbr?>">
																<?php $map_color="";?>
															<?php } else {?>
																<?php $map_color="color:green";?>
															<?php }?>
															<span style='<?php echo $map_color?>'><?php echo $sptm_nbr; ?></span></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $package_cnt?></span></td>
														<td style=""><?php echo $sptm_npd_setno_name;?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $package_weight?></td>
														<td><?php if ($sptm_customer_number != "NPD_NOCUST") {echo $sptm_customer;}?></td>
														<td style="text-align:right">
															<?php if ($sptm_customer_number != "NPD_NOCUST" && $sptm_step_code == "30") {?>
																<a href="javascript:void(0)" style="color:red" onclick="cancelnpdmapcustpost('<?php echo $sptm_nbr?>')">ยกเลิกการจับคู่</a>
															<?php }?>
														</td>
													</tr>
													<?php }?>
												<?php }?>
												
												</tbody>
											</table>  
											
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
	
	<div id="sptm_npd_nocust_shipment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																								
		<form id="frm_sptm_npd_nocust_shipment" name="frm_sptm_npd_nocust_shipment"  autocomplete=OFF method="post">
			<input type="hidden" name="action" value="<?php echo md5('selected_sptm_npd_nocust_shipment'.$user_login)?>">
			<input type="hidden" name="sptm_nbr_list">
			
			<input type="hidden" name="pg" value="<?php echo $currentpage?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><img src="../_images/truck-icon.png" style="width:30px;border-radius:50%">จับคู่ลูกค้าและทะเบียนรถ</h3>
			</div>
			
			<div class="">
				<table border="0" class="table-condensed">
				<tbody>				
					<tr height=5px><td></td></tr>
					<tr>
						<td style="width:80px;text-align:right;">รหัสลูกค้า:</td>
						<td style="width:160px">
							<input type="text" name="sptm_customer_number" id="sptm_customer_number" class="inputtext_s" style="width: 110px" maxlength="20" readonly>														
							<!--button type="button" name="sptm_customer_help" id="sptm_customer_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup_customer('../_help/getcustnpdshipmentampv.php','frm_sptm_npd_nocust_shipment','','',document.frm_sptm_npd_nocust_shipment.sptm_customer_number.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button-->				
						</td>
						<td style="text-align:right; width: 60px; vertical-align: middle;"><b>ชื่อลูกค้า:</b></td>
						<td><input type="text"  name="sptm_customer_name" class="inputtext_s" style="width: 200px;" readonly></td>
					</tr>
					<tr>
						<td style="text-align:right;vertical-align: middle;"><b>อำเภอ:</b></td>
						<td>
							<input type="text" name="sptm_customer_amphur" id="sptm_customer_amphur" class="inputtext_s" style='width:110px' maxlength=255>
							<button type="button" name="sptm_amphur_help" id="sptm_amphur_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getamphur.php','frm_sptm_npd_nocust_shipment','sptm_customer_amphur','sptm_customer_province',document.frm_sptm_npd_nocust_shipment.sptm_customer_amphur.value)">
								<span class="icon icon-search" aria-hidden="true"></span>
							</button>
						</td>
						<td style="text-align:right;vertical-align: middle;"><b>จังหวัด:</b></td>
						<td>
							<input type="text" name="sptm_customer_province" id="sptm_customer_province" class="inputtext_s"  style='width:100px' maxlength=255>
							<button type="button" name="sptm_province_help" id="sptm_province_help" class="btn btn-default" style="vertical-align: top;height:25px;margin:auto" 
								OnClick="helppopup('../_help/getprovince.php','frm_sptm_npd_nocust_shipment','sptm_customer_province','',document.frm_sptm_npd_nocust_shipment.sptm_customer_province.value)">
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
				<button id="btn_save_shipment" type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="sptm_npdmapcustpost()">
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
	<form id="frm_cancel_npdmapcust" name="frm_cancel_npdmapcust" autocomplete=OFF method="post">
		<input type="hidden" name="action" value="<?php echo md5('cancel_npdmapcust'.$user_login)?>">
		<input type="hidden" name="sptm_nbr">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	</body>
</html>
