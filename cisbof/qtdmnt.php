<?php 
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	clearstatcache();
	$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));
	$qtm_nbr = html_escape(decrypt($_REQUEST['qtmnumber'], $key));
	
	//TAB ACTIVE--
	$ta = html_escape(decrypt($_REQUEST['ta'], $key));
	if ($ta == "tab_qtprod" || $ta == "") { $tab_qtprod = "active"; }
	if ($ta == "tab_qtinfo") { $tab_qtinfo = "active"; }
	$tb = html_escape(decrypt($_REQUEST['tb'], $key));
	if ($tb == "tab_auction" || $tb == "") { $tab_auction = "active"; }
	if ($tb == "tab_custpay") { $tab_custpay = "active"; }
	if ($tb == "tab_conspay") { $tab_conspay = "active"; }
	//
	
	$pg = html_escape($_REQUEST['pg']);
	
	$curdate = date('d/m/Y');
	$filepath_at = "../_fileuploads/at/";
	
	//$qtm_nbr = "QT-2001-0003";
	$params = array($qtm_nbr);
	$sql_qtm = "SELECT * from qtm_mstr where qtm_nbr = ? and qtm_is_delete = 0";
	$result_qtm = sqlsrv_query($conn, $sql_qtm,$params);	
	$rec_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);		
	if ($rec_qtm) {
		$qtm_nbr = html_escape($rec_qtm['qtm_nbr']);
		$qtm_to = html_escape($rec_qtm['qtm_to']);
		$qtm_name = html_escape($rec_qtm['qtm_name']);
		$qtm_customer_number = html_escape($rec_qtm['qtm_customer_number']);
		$qtm_customer_name = html_escape($rec_qtm['qtm_customer_name']);
		if ($qtm_customer_number != "DUMMY") {
			$qtm_customer_name = html_escape($rec_qtm['custpj_name']);
		}
		else {
			$qtm_customer_name = $qtm_to;
		}
													
		$qtm_date = html_escape($rec_qtm['qtm_date']);
		$qtm_expire_date = html_escape($rec_qtm['qtm_expire_date']);
		$qtm_address = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_address']));
		$qtm_amphur = html_escape($rec_qtm['qtm_amphur']);
		$qtm_province = html_escape($rec_qtm['qtm_province']);
		$qtm_zip_code = html_escape($rec_qtm['qtm_zip_code']);
		$qtm_lineid = html_escape($rec_qtm['qtm_lineid']);
		$qtm_email = html_escape($rec_qtm['qtm_email']);
		$qtm_tel_contact = html_escape($rec_qtm['qtm_tel_contact']);
		$qtm_payterm_code = html_escape($rec_qtm['qtm_payterm_code']);
		$qtm_detail = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_detail']));
		$qtm_remark = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_remark']));
		$qtm_disc = html_escape($rec_qtm['qtm_disc']);
		$qtm_disc_unit = html_escape($rec_qtm['qtm_disc_unit']);
		if ($qtm_disc_unit == "P") {
			$qtm_disc_unit_name = "%";
		}
		if ($qtm_disc_unit == "B") {
			$qtm_disc_unit_name = "บาท";
		}
		$qtm_customer_amt = html_escape($rec_qtm['qtm_customer_amt']);
		$qtm_contractor_amt = html_escape($rec_qtm['qtm_contractor_amt']);
		$qtm_auction_amt = html_escape($rec_qtm['qtm_auction_amt']);
		$qtm_sale_code = html_escape($rec_qtm['qtm_sale_code']);
		$qtm_pjm_nbr = html_escape($rec_qtm['qtm_pjm_nbr']);
		$qtm_ref_nbr = html_escape($rec_qtm['qtm_ref_nbr']);
		$qtm_revsion = html_escape($rec_qtm['qtm_revsion']);
		$qtm_tem_code = html_escape($rec_qtm['qtm_tem_code']);
		$qtm_tem_name = findsqlval("tem_mstr","tem_name","tem_code",$qtm_tem_code,$conn);
		$qtm_prepaid_amt = html_escape($rec_qtm['qtm_prepaid_amt']);
		$qtm_prepaid_date = html_escape($rec_qtm['qtm_prepaid_date']);
		$qtm_step_code = html_escape($rec_qtm['qtm_step_code']);
		$qtm_step_name = findsqlval("qtm_step_mstr","qtm_step_name", "qtm_step_code", $qtm_step_code,$conn);
		$qtm_step_by = html_escape($rec_qtm['qtm_step_by']);
		$qtm_step_date = $rec_qtm['qtm_step_date'];
		$qtm_step_cmmt = html_escape($rec_qtm['qtm_step_cmmt']);
		$qtm_whocanread = html_escape($rec_qtm['qtm_whocanread]']);
		$qtm_curprocessor = html_escape($rec_qtm['qtm_curprocessor']);
		$qtm_create_by = html_escape($rec_qtm['qtm_create_by']);	
		$qtm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_create_by,$conn);				
		$qtm_printed_cnt = html_escape($rec_qtm['qtm_printed_cnt']);
		$qtm_approve_price_by = html_escape($rec_qtm['qtm_approve_price_by']);
		$qtm_approve_price_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_approve_price_by,$conn);
		$qtm_approve_price_date = $rec_qtm['qtm_approve_price_date'];
		$qtm_approve_price_cmmt = str_replace("\n","<br>",$rec_qtm['qtm_approve_price_cmmt']);
		if ($qtm_approve_price_cmmt !="") {
			$qtm_approve_price_cmmt = "<br><b><u>Comment:</u></b><br>" . $qtm_approve_price_cmmt;
		}
		$qtm_approve_final_by = html_escape($rec_qtm['qtm_approve_final_by']);
		$qtm_approve_final_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_approve_final_by,$conn);
		$qtm_approve_final_date = $rec_qtm['qtm_approve_price_date'];
		$qtm_approve_final_cmmt = str_replace("\n","<br>",$rec_qtm['qtm_approve_final_cmmt']);
		if ($qtm_approve_final_cmmt !="") {
			$qtm_approve_final_cmmt = "<p>Comment:<br>" . $qtm_approve_final_cmmt."</p>";
		}
	}
	else {
		$path = "authorize.php?msg=เอกสารหมายเลข $qtm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	
	
	$iscurrentprocessor = false;
	$can_editing = false;
	$can_submit = false;
	$can_request_editing = false;
	$can_request_cancel = false;
	$can_auction = false;
	$can_price_approve = false;
	$can_final_approve = false;
	
	//Assign Authorize for CurrentProcessor
	
	if (inlist($qtm_curprocessor,$user_login)) {
		//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
		//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
		$iscurrentprocessor = true;
	}
	else {
		//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
		$qtm_curprocessor_role_access = "";
		$qtm_curprocessor_role_array = explode(",",$user_role);																										
		for ($c=0;$c<count($qtm_curprocessor_role_array);$c++) {
			if (inlist($qtm_curprocessor,$qtm_curprocessor_role_array[$c])) {
				$iscurrentprocessor = true;
				break;
			}
		}
	}
	
	if ($iscurrentprocessor && inlist('0,10',$qtm_step_code)) {
		$can_editing = true;
	}
	if ($iscurrentprocessor && inlist('0,10',$qtm_step_code)) {
		$can_submit = true;
	}
	
	if (($qtm_create_by == $user_login) && inlist('20',$qtm_step_code)) {
		$can_request_editing = true;
		$can_request_cancel = true;
	}
	
	if ($iscurrentprocessor && inlist('20',$qtm_step_code)) {
		$can_price_approve = true;
	}
	if ($iscurrentprocessor && inlist('30,35',$qtm_step_code)) {
		$can_auction = true;
	}
	if ($iscurrentprocessor && inlist('40',$qtm_step_code)) {
		$can_final_approve = true;
	}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title><?php echo TITLE;?></title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <!-- END: Vendor CSS-->
	
	 <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/forms/icheck/custom.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/core/colors/palette-gradient.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/pickers/daterange/daterange.css">
    <!-- END: Page CSS-->
	
	<!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/checkboxes-radios.css">
    <!-- END: Page CSS-->
	
	

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/project_style.css">
	<link href="../_libs/css/cisbof.css" rel="stylesheet">
    <!-- END: Custom CSS-->
</head>
<!-- END: Head-->
<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

	<div id="result"></div>
    <?php include("../cismain/menu_header.php"); ?>	
	<?php include("../cismain/menu_leftsidebar.php"); ?>
	
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row mt-n1">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
								<li class="breadcrumb-item"><a href="#">Quotations</a></li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0"><?php echo $qtm_nbr?></h3>
                </div>
				
                <div class="content-header-right col-md-6 col-12">
					<?php if($can_submit) {?>
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i> Actions</button>
                            <div class="dropdown-menu">
								<a class="dropdown-item small" href="javascript:void(0)" onclick="javascript:submit_price_approve_post();">Send for Price Approval</a>
							</div>						
                        </div><a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="feather icon-mail"></i></a><a class="btn btn-outline-primary" href="timeline-center.html"><i class="feather icon-pie-chart"></i></a>
                    </div>
					<?php }?>
					<?php if($can_auction) {?>
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i> Actions</button>
                            <div class="dropdown-menu">
								<a class="dropdown-item small" href="javascript:void(0)" onclick="javascript:submit_final_approve_post();">Send for Final Approval</a>
							</div>						
                        </div><a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="feather icon-mail"></i></a><a class="btn btn-outline-primary" href="timeline-center.html"><i class="feather icon-pie-chart"></i></a>
                    </div>
					<?php }?>
                </div>
            </div>

            <div class="content-body mt-n1">
                <section>
					<div class="row" id="div_detail">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="card">
								<div class="card-header border-0-bottom">
									<h4 class="card-title"><span class="bg-blue white"><?php echo $qtm_name?></span><br><span class="red small font-weight-bold">Status: <?php echo $qtm_step_name?></span></h4>
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
									<div class="heading-elements">
										<ul class="list-inline mb-0">
											<li>
												<div class="btn btn-sm btn-danger" style="width:70px" onclick="loadresult();window.location.href='qtmall.php?activeid=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
													<i class="feather icon-skip-back mr-25"></i><span>Black</span>
												</div>
											</li>
											<?php if ($can_editing) {?>
											<li>
												<div class="btn btn-sm btn-warning" style="width:70px" onclick="loadresult();window.location.href='qtmedit.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $pg;?>'">
													<i class="feather icon-edit mr-25"></i><span>Edit</span>
												</div>
											</li>
											<?php }?>
											<li>
												<div class="btn btn-sm btn-info" style="width:90px" onclick="printform('qtmform01.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>')">		
													<i class="feather icon-printer mr-25"></i>
													<?php if ($qtm_printed_cnt == 0) {?>
														<span>Print</span>
													<?php } else { ?>
														<span>Re-Print</span>
													<?php }?>
												</div>
												
											</li>
											
										</ul>
									</div>
								</div>
								<div class="card-content small mt-n2">
									<div class="card-body">
										<ul class="nav nav-tabs nav-topline" role="tablist">
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center <?php echo $tab_qtprod?>" id="detail-tab" data-toggle="tab" href="#detail_data" aria-controls="detail_data" role="tab" aria-selected="true">
													<i class="feather icon-shopping-cart mr-25"></i><span class="d-none d-sm-block">รายการสินค้า</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center <?php echo $tab_qtinfo?>" id="header-tab" data-toggle="tab" href="#header_data" aria-controls="header_data" role="tab" aria-selected="true">
													<i class="feather icon-monitor mr-25"></i><span class="d-none d-sm-block">ข้อมูลหลัก</span>
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane <?php echo $tab_qtprod?>" id="detail_data" aria-labelledby="account-tab" role="tabpanel">
												<?php include("../cisbof/qtdmnt_detail.php");?>
											</div>
											<div class="tab-pane <?php echo $tab_qtinfo?>" id="header_data" aria-labelledby="header-tab" role="tabpanel" >
												<?php include("../cisbof/qtdmnt_header.php");?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if($can_price_approve) {?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="card-content bg-white  small mt-n1">
								<div class="card-body">
									<!--ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist"-->
									<ul class="nav nav-tabs nav-topline" role="tablist">
										<li class="nav-item"> 
											<a class="nav-link d-flex align-items-center active" id="base-approval" data-toggle="tab" aria-controls="approval_data"  href="#approval_data" role="tab" aria-selected="true">
												<i class="feather icon-thumbs-up mr-25"></i><span class="d-none d-sm-block">Price Approval</span>
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="approval_data" role="tabpanel" aria-labelledby="base-approval">
											<?php include("../cisbof/qtdmnt_price_apv.php");?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if ($qtm_step_code >= "10" && $qtm_step_code != "20") {?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="card-content small bg-white mt-n1">
								<div class="card-body">
									<!--ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist"-->
									<ul class="nav nav-tabs nav-topline" role="tablist">
										<li class="nav-item">
											<a class="nav-link d-flex align-items-center active" id="base-approval" data-toggle="tab" aria-controls="approval_data"  href="#approval_data" role="tab" aria-selected="true">
												<?php 
												if ($qtm_step_code != "10" && $qtm_step_code != "800") { //Approved
													$price_result_color = "green";
													$price_result_icon = "icon-thumbs-up";
													$price_result_text = "<br><b>Approved By:</b> $qtm_approve_price_by_name, Date: " . dmyhmsdb($qtm_approve_price_date,'Y') . $qtm_approve_price_cmmt;
												}
												if ($qtm_step_code == "10") { //Approved
													$price_result_color = "orange";
													$price_result_icon = "icon-thumbs-up";
													$price_result_text = "<br><span class='blue'><b>Revise By:</b> $qtm_approve_price_by_name, Date: " . dmyhmsdb($qtm_approve_price_date,'Y') . "$qtm_approve_price_cmmt</span>";
												}
												if ($qtm_step_code == "800") { //Approved
													$price_result_color = "red";
													$price_result_icon = "icon-thumbs-down";
													$price_result_text = "<br><span class='red'><b>Reject By:</b> $qtm_approve_price_by_name, Date: " . dmyhmsdb($qtm_approve_price_date,'Y') . "$qtm_approve_price_cmmt</span>";
												}
												?>
												<i class="feather <?php echo $price_result_icon?> mr-25"></i><span class="d-none d-sm-block" style="color:<?php echo $price_result_color?>">Price Approval Result</span>
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="approval_data" role="tabpanel" aria-labelledby="base-approval">
											<?php echo $price_result_text?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if ($qtm_step_code >= "30" && $qtm_step_code != "800") {?>
					<div class="row" id="div_auction">
						<div class="col-lg-12 col-md-12 col-12">	
							<div class="card-content bg-white small mt-1">
								<div class="card-body">
									<ul class="nav nav-tabs nav-topline" role="tablist">
										<li class="nav-item">
											<a class="nav-link d-flex align-items-center <?php echo $tab_auction?>" id="auction-tab" data-toggle="tab" href="#auction_data" aria-controls="detail_data1" role="tab" aria-selected="true">
												<i class="feather icon-shopping-cart mr-25"></i><span class="d-none d-sm-block">Auction</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link d-flex align-items-center <?php echo $tab_custpay?>" id="custpay-tab" data-toggle="tab" href="#custpay_data" aria-controls="header_data1" role="tab" aria-selected="true">
												<i class="feather icon-user-plus mr-25"></i><span class="d-none d-sm-block">Customer Payment</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link d-flex align-items-center <?php echo $tab_conspay?>" id="conspay-tab" data-toggle="tab" href="#conspay_data" aria-controls="header_data2" role="tab" aria-selected="true">
												<i class="feather icon-users mr-25"></i><span class="d-none d-sm-block">Contractor Payment</span>
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane <?php echo $tab_auction?>" id="auction_data" aria-labelledby="auction-ta" role="tabpanel">
											<?php include("../cisbof/qtdmnt_auction.php");?>
										</div>
										<div class="tab-pane <?php echo $tab_custpay?>" id="custpay_data" aria-labelledby="custpay-tab" role="tabpanel" >
											<?php include("../cisbof/qtdmnt_custpay.php");?>
										</div>
										<div class="tab-pane <?php echo $tab_conspay?>" id="conspay_data" aria-labelledby="conspay-tab" role="tabpanel" >
											<?php include("../cisbof/qtdmnt_conspay.php");?>
										</div>
									</div>
								
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if($can_final_approve) {?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="card-content bg-white small mt-n1">
								<div class="card-body">
									<!--ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist"-->
									<ul class="nav nav-tabs nav-topline" role="tablist">
										<li class="nav-item"> 
											<a class="nav-link d-flex align-items-center active" id="base-approval" data-toggle="tab" aria-controls="approval_data"  href="#approval_data" role="tab" aria-selected="true">
												<i class="feather icon-thumbs-up mr-25"></i><span class="d-none d-sm-block">Final Approval</span>
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="approval_data" role="tabpanel" aria-labelledby="base-approval">
											<?php include("../cisbof/qtdmnt_final_apv.php");?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if (inlist("35,890,90",$qtm_step_code)) {?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="card-content small bg-white mt-1">
								<div class="card-body">
									<!--ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist"-->
									<ul class="nav nav-tabs nav-topline" role="tablist">
										<li class="nav-item"> 
											<a class="nav-link d-flex align-items-center active" id="base-approval" data-toggle="tab" aria-controls="approval_data"  href="#approval_data" role="tab" aria-selected="true">
												<?php 
												if ($qtm_step_code == "90") { //Approved
													$final_result_color = "green";
													$final_result_icon = "icon-thumbs-up";
													$final_result_text = "<br><b>Approved By:</b> $qtm_approve_final_by_name, Date: " . dmyhmsdb($qtm_approve_final_date,'Y') . "$qtm_approve_final_cmmt";
												}
												if ($qtm_step_code == "35") { //Approved
													$final_result_color = "orange";
													$final_result_icon = "icon-thumbs-up";
													$final_result_text = "<br><span class='blue'><b>Revise By:</b> $qtm_approve_final_by_name, Date: " . dmyhmsdb($qtm_approve_final_date,'Y') . "$qtm_approve_final_cmmt</span>";
												}
												if ($qtm_step_code == "890") { //Approved
													$final_result_color = "red";
													$final_result_icon = "icon-thumbs-down";
													$final_result_text = "<br><span class='red'><b>Reject By:</b> $qtm_approve_final_by_name, Date: " . dmyhmsdb($qtm_approve_final_date,'Y') . "$qtm_approve_final_cmmt</span>";
												}
												?>
												<i class="feather <?php echo $final_result_icon?> mr-25"></i><span class="d-none d-sm-block">Final Approval Result</span>
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="approval_data" role="tabpanel" aria-labelledby="base-approval">
											<?php echo $final_result_text?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				</section>
                <!-- File export table -->
            </div>
        </div>
    </div>
	<form id="frm_submit_price_approve" name="frm_submit_price_approve" method="post">
		<input type="hidden" name="action" value="<?php echo md5('submit_price_approve'.$user_login)?>">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
	<form id="frm_submit_final_approve" name="frm_submit_final_approve" method="post">
		<input type="hidden" name="action" value="<?php echo md5('submit_final_approve'.$user_login)?>">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
	</form>
    <!-- END: Content-->
	<div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
	<?php include("../cismain/menu_footer.php");?>
	<?php include("../cismain/modal.php");?>

    <!-- BEGIN: Vendor JS-->
    <script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
    
    
	<!-- BEGIN: Page Vendor JS-->
    <script src="../theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
    <!-- END: Page Vendor JS-->
	<!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->
	<!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/pickers/dateTime/bootstrap-datetime.js"></script>
    <script src="../theme/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>
    <!-- END: Page JS-->
	<!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/pages/page-users.js"></script>
    <script src="../theme/app-assets/js/scripts/navs/navs.js"></script>
	<!-- BEGIN: Page Vendor JS-->
    <script src="../theme/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
    <!-- END: Page Vendor JS-->
	<!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/forms/checkbox-radio.js"></script>
    <!-- END: Page JS-->
	<script src="../_libs/js/bootbox.min.js"></script>
	<script type="text/javascript" src="../_libs/js/cisbof.js"></script>
    <!-- END: Page JS-->
	<script type="text/javascript">
		$(document).ready(function () {
			$('input[id*=conspay_pay_date]').datetimepicker( {
				format: 'DD/MM/YYYY'
			});
			$('input[id*=custpay_pay_date]').datetimepicker( {
				format: 'DD/MM/YYYY'
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
		function helppopup_mat(prgname,formname,fieldid,editprice,txtsearch) {
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
			var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&fieldid='+fieldid+'&ep='+editprice,'windowhelp',settings);		
			if (!myWindow.opener) myWindow.opener = self;
		}
		function printform(url) {				
			window.open(url);
			setTimeout(function(){ window.location.reload(true); }, 3000);									
		}	
		function qtd_product_postform(formname) {
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/qtdmnt_detail_post.php',
				data: $('#'+formname).serialize(),
				timeout: 50000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(result) {	
					//console.log(result);
					//alert(result);
					
					var json = $.parseJSON(result);
					if (json.r == '0') {
						clearloadresult();
						showmsg(json.e);
					}
					else {
						clearloadresult();
						$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&ta='+json.ta+'&pg='+json.pg+'/#div_detail');
						//$(location).attr('hash','#div_detail');
						//alert ($(location).attr('hash'));
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		function del_qtd_product(qtd_id) {
			if(confirm('ท่านต้องการลบสินค้านี้ ไช่หรือไม่ ?')) {	
				document.frm_del_qtd_product.qtd_id.value = qtd_id;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/qtdmnt_detail_post.php',
					data: $('#frm_del_qtd_product').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&ta='+json.ta+'&pg='+json.pg+'/#div_detail');
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		function submit_price_approve_post() {	
			var errorflag = false;
			var errortxt = "";
			var qtm_nbr = document.forms["frm_submit_price_approve"].qtm_nbr.value;
			
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
			xhttp.open("POST", "../_chk/chkformpriceuser.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("qtmnumber="+qtm_nbr);	
			
			if (errorflag ) {
				document.getElementById("modal-body").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {								
				if(confirm('ท่านต้องการส่งเอกสารไปยังผู้อนุมัติ ไช่หรือไม่ ?')) {
					var result_text="";
					$.ajax({
						beforeSend: function () {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
							$("#requestOverlay").show();
						},
						type: 'POST',
						url: '../serverside/qtmsubmitpricepost.php',
						data: $('#frm_submit_price_approve').serialize(),
						timeout: 50000,
						error: function(xhr, error){
							showmsg('['+xhr+'] '+ error);
						},
						success: function(result) {
							//alert(result);
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
											$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
										}
									});
								}
								else {
									$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
								}
							}
						},
						complete: function () {
							$("#requestOverlay").remove();
						}
					});
				}
				else {
					return;			
				}										
			}
		}
		function price_approve_postform() {
			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			if (!RadioIsCheck(document.frmapprove.qtm_approve_select)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกผลการอนุมัติ";
			}
			//
			if (errorflag) {
				document.getElementById("modal-body").innerHTML = "<font color=red>" + errortxt + "</font>";
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
						url: '../serverside/qtmapprovepricepost.php',
						data: $('#frmapprove').serialize(),
						timeout: 50000,
						error: function(xhr, error){
							showmsg('['+xhr+'] '+ error);
						},
						success: function(result) {
							//alert(result);
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
										$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
									});
								}
								else {
									$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
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
		function auction_postform(formname) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var aucd_qtd_id_list = "";
			var aucd_qtd_id_cnt = 0;
			$('input[id*='+formname+'_aucd_qtd_id_]').each(function() {
				if (aucd_qtd_id_list != "") { aucd_qtd_id_list = aucd_qtd_id_list + ","; }
				aucd_qtd_id_list = aucd_qtd_id_list + this.value;
				aucd_qtd_id_cnt++;
			});
			
			var aucd_auction_unit_amt_list = "";
			$('input[id*='+formname+'_aucd_auction_unit_amt_]').each(function() {
				if (aucd_auction_unit_amt_list != "") { aucd_auction_unit_amt_list = aucd_auction_unit_amt_list + "*^*"; }
				aucd_auction_unit_amt_list = aucd_auction_unit_amt_list + this.value;
			});
			
			if (aucd_qtd_id_cnt == 0) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}	
				errorflag = true;					
				errortxt = errortxt + "ไม่พบรายการ";
			}
			
			if (errorflag) {			
				document.getElementById("modal-body").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				
				document.forms[formname].aucd_qtd_id_list.value = aucd_qtd_id_list;
				document.forms[formname].aucd_auction_unit_amt_list.value = aucd_auction_unit_amt_list;
				
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();
					},
					type: 'POST',
					url: '../serverside/qtdmnt_auction_post.php',
					data: $('#'+formname).serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {	
						//console.log(result);
						//alert(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							//clearloadresult();
							//location.hash = '#tab12'
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		function del_auction(aucm_nbr) {
			if(confirm('ท่านต้องการลบ Auction นี้ ไช่หรือไม่ ?')) {	
				document.frm_del_auction.aucm_nbr.value = aucm_nbr;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/qtdmnt_auction_post.php',
					data: $('#frm_del_auction').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
							//location.hash = '#tab12'
							//location.reload(true);
							//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg+'#tab12')
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
			
		}
		function auction_assign_win(aucm_nbr) {
			if(confirm('ท่านต้องการกำหนดให้ Auction หมายเลขนี้เป็นผู้ชนะ ไช่หรือไม่ ?')) {	
				document.frm_assign_win_auction.aucm_nbr.value = aucm_nbr;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/qtdmnt_auction_post.php',
					data: $('#frm_assign_win_auction').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						//alert(result);
						//return;
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
							//location.reload(true);
							//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg+'#tab12')
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		
		function custpay_postform(formname) {
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
					$("#requestOverlay").show();
				},
				type: 'POST',
				url: '../serverside/qtdmnt_custpay_post.php',
				data: $('#'+formname).serialize(),
				timeout: 50000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(result) {	
					//console.log(result);
					//alert(result);
					
					var json = $.parseJSON(result);
					if (json.r == '0') {
						clearloadresult();
						showmsg(json.e);
					}
					else {
						clearloadresult();
						var path = "qtdmnt.php?qtmnumber="+json.nb+"&tb="+json.tb+"&pg="+json.pg+"/#div_auction";
						//alert(path);
						//$('head').append( '<meta http-equiv="refresh" content="0;URL="'+path+'>');
						$(location).attr('href', path);
						//echo <meta http-equiv="refresh" content="0;URL="+path>
					}
				},
				complete: function () {
					$("#requestOverlay").remove();
				}
			});
		}
		function del_custpay(custpay_id) {
			if(confirm('ท่านต้องการลบรายการชำระของลูกค้ารายการนี้ ไช่หรือไม่ ?')) {	
				document.frm_del_custpay.custpay_id.value = custpay_id;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
						$("#requestOverlay").show();
					},
					type: 'POST',
					url: '../serverside/qtdmnt_custpay_post.php',
					data: $('#frm_del_custpay').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
						}
					},
					complete: function () {
						$("#requestOverlay").remove();
					}
				});
			}
		}
		function conspay_postform(formname) {
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
					$("#requestOverlay").show();
				},
				type: 'POST',
				url: '../serverside/qtdmnt_conspay_post.php',
				data: $('#'+formname).serialize(),
				timeout: 50000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(result) {	
					//console.log(result);
					//alert(result);
					
					var json = $.parseJSON(result);
					if (json.r == '0') {
						clearloadresult();
						showmsg(json.e);
					}
					else {
						clearloadresult();
						$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
						//$('#div_add_conspay').modal('hide');
						//$('a[href="#custpay-tab"]').click();
						//location.reload(true);
						
						
						//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg)
						// $('#auction-tab').removeClass( "active" );
						// $('#auction_data').removeClass( "active" );
						// $('#conspay-tab').addClass( "active" );
						// $('#conspay_data').addClass( "active" );
					}
				},
				complete: function () {
					$("#requestOverlay").remove();
				}
			});
		}
		function del_conspay(conspay_id) {
			if(confirm('ท่านต้องการลบรายการจ่ายของผู้รับเหมารายการนี้ ไช่หรือไม่ ?')) {	
				document.frm_del_conspay.conspay_id.value = conspay_id;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
						$("#requestOverlay").show();
					},
					type: 'POST',
					url: '../serverside/qtdmnt_conspay_post.php',
					data: $('#frm_del_conspay').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							//location.reload(true);
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&tb='+json.tb+'&pg='+json.pg+'/#div_auction');
						}
					},
					complete: function () {
						$("#requestOverlay").remove();
					}
				});
			}
		}
		
		function submit_final_approve_post() {	
			var errorflag = false;
			var errortxt = "";
			var qtm_nbr = document.forms["frm_submit_final_approve"].qtm_nbr.value;
			
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
			xhttp.open("POST", "../_chk/chkformfinaluser.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("qtmnumber="+qtm_nbr);	
			
			if (errorflag ) {
				document.getElementById("modal-body").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {								
				if(confirm('ท่านต้องการส่งเอกสารไปยังผู้อนุมัติ ไช่หรือไม่ ?')) {
					var result_text="";
					$.ajax({
						beforeSend: function () {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
							$("#requestOverlay").show();
						},
						type: 'POST',
						url: '../serverside/qtmsubmitfinalpost.php',
						data: $('#frm_submit_final_approve').serialize(),
						timeout: 50000,
						error: function(xhr, error){
							showmsg('['+xhr+'] '+ error);
						},
						success: function(result) {
							//alert(result);
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
											$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
										}
									});
								}
								else {
									$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
								}
							}
							
						},
						complete: function () {
							$("#requestOverlay").remove();
						}
					});
				}
				else {
					return;			
				}										
			}
		}
		function final_approve_postform() {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			if (!RadioIsCheck(document.frm_final_approve.qtm_approve_select)) {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกผลการอนุมัติ";
			}
			//
			if (errorflag) {
				document.getElementById("modal-body").innerHTML = "<font color=red>" + errortxt + "</font>";
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
						url: '../serverside/qtmapprovefinalpost.php',
						data: $('#frm_final_approve').serialize(),
						timeout: 50000,
						error: function(xhr, error){
							showmsg('['+xhr+'] '+ error);
						},
						success: function(result) {
							//alert(result);
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
										$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
									});
								}
								else {
									$(location).attr('href', 'qtmall.php?activeid='+json.nbr+'&pg='+json.pg);
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
		function loadresult() {
			$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}
		function clearloadresult() {
			$('#result').html("");
		}
		function showmsg(msg) {
			$("#msghead").html("พบข้อผิดผลาดในการบันทึกข้อมูล");
			$("#modal-body").html(msg);
			$("#myModal").modal("show");
		}
		function toggleDisplay(divId) {
		  var div = document.getElementById(divId);	  
		  div.style.display = (div.style.display=="block" ? "none" : "block");
		}
	</script>
</body>
<!-- END: Body-->
</html>
