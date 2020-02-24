<?php
	//Temp
	$user_login = "NILUBONP";
	$current_tab = "10";
	
	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/funcServer_project.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack2!!";
			exit;
		}
	}
	clearstatcache();
	$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));
	$pjm_nbr = html_escape(decrypt($_REQUEST['pjmnumber'], $key));
	$pg = html_escape($_REQUEST['pg']);
	
	
	$curdate = date('d/m/Y');
	$filepath_at = "../_fileuploads/at/";
	
	$params = array($pjm_nbr);
	$sql_pjm = "SELECT * from pjm_mstr where pjm_nbr = ? ";
	$result_pjm = sqlsrv_query($conn, $sql_pjm,$params);	
	$rec_pjm = sqlsrv_fetch_array($result_pjm, SQLSRV_FETCH_ASSOC);		
	if ($rec_pjm) {
		$pjm_nbr= html_escape($rec_pjm['pjm_nbr']);
		$pjm_name= html_escape($rec_pjm['pjm_name']);
		$pjm_addr= html_escape($rec_pjm['pjm_addr']);
		$pjm_amphur_id= html_escape($rec_pjm['pjm_amphur_id']);
		$pjm_province_id= html_escape($rec_pjm['pjm_province_id']);
		$pjm_zipcode= html_escape($rec_pjm['pjm_zipcode']);
		$pjm_latitude= html_escape($rec_pjm['pjm_latitude']);
		$pjm_longtitude= html_escape($rec_pjm['pjm_longtitude']);
		$pjm_pjt_code= html_escape($rec_pjm['pjm_pjt_code']);
		$pjm_buy_scg= html_escape($rec_pjm['pjm_buy_scg']);
		$pjm_buy_scg_custcode= html_escape($rec_pjm['pjm_buy_scg_custcode']);
		$pjm_from_channel= html_escape($rec_pjm['pjm_from_channel']);
		$pjm_start_date= html_escape($rec_pjm['pjm_start_date']);
		$pjm_end_date= html_escape($rec_pjm['pjm_end_date']);
		$pjm_budget= html_escape($rec_pjm['pjm_budget']);
		$pjm_per_disc= html_escape($rec_pjm['pjm_per_disc']);
		$pjm_amt_disc= html_escape($rec_pjm['pjm_amt_disc']);
		$pjm_deposit_amt= html_escape($rec_pjm['pjm_deposit_amt']);
		$pjm_custpj_code= html_escape($rec_pjm['pjm_custpj_code']);
		$pjm_contact_name= html_escape($rec_pjm['pjm_contact_name']);
		$pjm_contact_addr= html_escape($rec_pjm['pjm_contact_addr']);
		$pjm_contact_tel= html_escape($rec_pjm['pjm_contact_tel']);
		$pjm_contact_lineid= html_escape($rec_pjm['pjm_contact_lineid']);
		$pjm_contact_email= html_escape($rec_pjm['pjm_contact_email']);
		$pjm_area_size= html_escape($rec_pjm['pjm_area_size']);
		$pjm_work_detail= html_escape($rec_pjm['pjm_work_detail']);
		$pjm_paymth_code= html_escape($rec_pjm['pjm_paymth_code']);
		$pjm_sc_code= html_escape($rec_pjm['pjm_sc_code']);
		$pjm_pjst_code= html_escape($rec_pjm['pjm_pjst_code']);
		$pjm_create_by= html_escape($rec_pjm['pjm_create_by']);
		//$pjm_create_date= html_escape($rec_pjm['pjm_create_date']);

		//Find Val Name
		$pjt_name = findsqlval("pjt_mstr","pjt_name", "pjt_code", $pjm_pjt_code,$conn);		
		$pjst_name = findsqlval("pjst_mstr","pjst_name", "pjst_code", $pjm_pjst_code,$conn);		
		$paymth_name = findsqlval("paymth_mstr","paymth_name", "paymth_code", $pjm_paymth_code,$conn);		
		$buyscg_name = findsqlval("buyscg_mstr","buyscg_name","buyscg_code",$pjm_buy_scg,$conn);	
		$sc_name = findsqlval("sc_mstr","sc_name","sc_code",$pjm_sc_code,$conn);			
		$create_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $pjm_create_by,$conn);	
		$amphur_th_name = findsqlval("amphur_mstr","amphur_th_name", "amphur_id", $pjm_amphur_id,$conn);	
		$province_th_name = findsqlval("province_mstr","province_th_name", "province_id", $pjm_province_id,$conn);	
	
		$sql_customer = "select * from custpj_mstr where custpj_code ='$pjm_custpj_code'";
		$result_customer = sqlsrv_query($conn, $sql_customer,$params);	
		$rec_customer = sqlsrv_fetch_array($result_customer, SQLSRV_FETCH_ASSOC);		
		if ($rec_customer) {
			$custpj_code = $rec_customer['custpj_code'];
			$custpj_name = $rec_customer['custpj_name'];
			$custpj_addr = $rec_customer['custpj_addr'];
			$custpj_tel = $rec_customer['custpj_tel'];
			$custpj_lineid = $rec_customer['custpj_lineid'];
			$custpj_email = $rec_customer['custpj_email'];
			// $custpj_contact_name = $rec_customer['custpj_contact_name'];
			// $custpj_contact_addr = $rec_customer['custpj_contact_addr'];
			// $custpj_contact_tel = $rec_customer['custpj_contact_tel'];
			// $custpj_contact_lineid = $rec_customer['custpj_contact_lineid'];
			// $custpj_contact_email = $rec_customer['custpj_contact_email'];
		}
	}
	else {
		//$path = "authorize.php?msg=เอกสารหมายเลข $pjm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		$path = "pjmall.php?msg=กรุณาสร้างโปรเจคใหม่ โปรเจคที่สร้างไป ไม่สามารถสร้างได้"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	
	
	$iscurrentprocessor = false;
	$can_editing = false;
	$can_submit = false;
	$can_request_editing = false;
	$can_request_cancel = false;
	$can_approve = false;
	
	//Assign Authorize for CurrentProcessor
	if (inlist($pjm_curprocessor,$user_login)) {
		//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
		//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
		$iscurrentprocessor = true;
	}
	else {
		//ดูว่า Role ของ User มีใน List ของ CurrentProcessor หรือไม่ถ้ามีก็จะ Edit เอกสารได้
		$pjm_curprocessor_role_access = "";
		$pjm_curprocessor_role_array = explode(",",$user_role);																										
		for ($c=0;$c<count($pjm_curprocessor_role_array);$c++) {
			if (inlist($pjm_curprocessor,$pjm_curprocessor_role_array[$c])) {
				$iscurrentprocessor = true;
				break;
			}
		}
	}
	if ($iscurrentprocessor && inlist('0,10',$pjm_step_code)) {
		$can_editing = true;
	}
	if ($iscurrentprocessor && inlist('0,10',$pjm_step_code)) {
		$can_submit = true;
	}
	
	if (($pjm_create_by == $user_login) && inlist('20',$pjm_step_code)) {
		$can_request_editing = true;
		$can_request_cancel = true;
	}
	
	if ($iscurrentprocessor && inlist('20',$pjm_step_code)) {
		$can_approve = true;
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
    <title>Striped Row Forms - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/calendars/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/calendars/daygrid.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/calendars/timegrid.min.css">
	<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>-->
	
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
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/calendars/fullcalendar.css">
	
	<!-- form-extended-inputs.html -->
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/extended/form-extended.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/project_style.css">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu 2-columns menu-collapsed   fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

    <div id="result"></div>
    <? include("../cismain/menu_header.php"); ?>	
	<? include("../cismain/menu_leftsidebar.php"); ?>

     <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
           <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="pjmall.php">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="pjmall.php">Project</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="pjdmnt.php?pjmnumber=<?php echo encrypt($pjm_nbr, $key);?>&pg=<?php echo $pg;?>'">Project : <?php echo $pjm_nbr; ?> </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                     <h3 class="content-header-title mb-0">Edit Project : <?php echo $pjm_nbr; ?></h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
							<a class="btn btn-outline-primary" href="pjmadd.php"><i class="fa fa-file-o icon-left"></i>New Project</a>
						</div>
						<a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="fa fa-download"></i></a>
						<a class="btn btn-outline-primary" href="full-calender-events.html"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Start New Project Section -->
                <section class="new-project">
					<div class="row">
                        <div class="col-12">	
							<!-- Start Card -->
							<div class="card">
								<div class="card-header mt-1 pt-0 pb-0" >
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
									<div class="heading-elements">
                                        <ul class="list-inline mb-0">                                           
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
								<div class="card-content collapse show">                                    		
									<div class="card-body" style="margin-top:-20px;">
										<ul class="nav nav-tabs mb-2 mt-0" role="tablist">
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center    active" id="project-tab" data-toggle="tab" href="#project" aria-controls="project" role="tab" aria-selected="true">
													<i class="fa fa-cube mr-25"></i><span class="d-none d-sm-block font-weight-bold">Project Info</span>
												</a>
											</li>											
										</ul>
										<!-- Start Project Tab -->
										<div class="tab-content">
											<div class="tab-pane active" id="project" aria-labelledby="project-tab" role="tabpanel">  
												<FORM id="frm_pjm_edit" name="frm_pjm_edit" autocomplete=OFF>
													<input type=hidden name="action" value="pjmedit">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
													<input type=hidden name="pg" value="<?php echo $pg?>">
													<!-- Form Body -->
													<div class="form-body">												
														<h4 class="form-section text-primary" ><i class="fa fa-cube"></i> Project Information </h4>		
														<div class="form-group row" >
															<div class="col-md-12">
																<div class="row">
																	<div class="col-lg-4 col-md-12 col-sm-12">
																		<div class="form-group">
																			<label class="font-weight-bold">Project Number</label>
																			<input type="text" id="pjm_nbr" name ="pjm_nbr" value="<?php echo $pjm_nbr; ?>" class="form-control " placeholder="Project Number Auto Generate" disabled>
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Project Name</label>
																			<input type="text" id="pjm_name" name ="pjm_name" value="<?php echo $pjm_name; ?>" class="form-control " placeholder="Project Name">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Project Type</label>
																			<select data-placeholder="Select a project type ..." class="select2-icons form-control" id="pjm_pjt_code" name="pjm_pjt_code">
																				<option value="" selected>(Choose Project Type)</option>
																					<?php 
																					$sql_pjt = "SELECT * FROM pjt_mstr order by pjt_seq";
																					$result_pjt = sqlsrv_query( $conn,$sql_pjt);																													
																					while($r_pjt=sqlsrv_fetch_array($result_pjt, SQLSRV_FETCH_ASSOC)) {
																					?>
																						<option value="<?php echo $r_pjt['pjt_code'];?>"
																						<?php if ($pjm_pjt_code ==$r_pjt['pjt_code']) {echo "selected";}?>>
																						<?php echo $r_pjt['pjt_name'];?></option> 
																					<?php } ?>	
																			</select>
																		</div>
																	</div>
																	<div class="col-lg-8 col-md-12 col-sm-12">
																		<div class="form-group row">                                                
																			<div class="col-md-9">
																				<div class="row">
																					<div class="col-md-12">
																						<fieldset class="form-group">
																							<label for="placeTextarea" class="font-weight-bold">Project Address</label>
																							<textarea class="form-control" id="placeTextarea" rows="6" placeholder="Project Address" name="pjm_addr" id="pjm_addr"><?php echo $pjm_addr; ?></textarea>
																						</fieldset>
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-md-4">
																						<div class="form-group">
																							<label class="font-weight-bold">Amphur</label>
																							<div class="input-group input-group">
																								<input type="text" name="pjm_amphur_id" id="pjm_amphur_id" value="<?php echo $amphur_th_name; ?>" class="form-control" placeholder="Amphur">
																								<div class="input-group-append">
																									<span class="input-group-text"
																										OnClick="helppopup('../_help/getamphur_project.php','frm_pjm_add','pjm_amphur_id','pjm_province_id','')" data-dismiss="modal">
																										<i class="feather icon-search"></i>
																									</span>
																								</div>
																							</div>
																						</div>
																					</div>
																					<div class="col-md-4">
																						<div class="form-group">
																							<label class="font-weight-bold">Province</label>
																							<div class="input-group input-group">
																								<input type="text" name="pjm_province_id" id="pjm_province_id"  value="<?php echo $province_th_name; ?>" class="form-control" placeholder="Province">
																								<div class="input-group-append">
																									<span class="input-group-text"
																										OnClick="helppopup('../_help/getprovince_project.php','frm_pjm_add','pjm_province_id','','')" data-dismiss="modal">
																										<i class="feather icon-search"></i>
																									</span>
																								</div>
																							</div>
																						</div>
																					</div>
																					<div class="col-md-4">
																						<div class="form-group">
																							<label class="font-weight-bold">Post Code</label>
																							<input type="text" name="pjm_zipcode" id="pjm_zipcode" value="<?php echo $pjm_zipcode; ?>" class="form-control" placeholder="Post Code">
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="row">																				
																					<div class="col-md-12">														
																						<div class="form-group">
																							<label class="font-weight-bold">Latitude</label>
																							<input type="textarea" name="pjm_latitude" id="pjm_latitude" value="<?php echo $pjm_latitude; ?>" class="form-control" placeholder="Latitude">
																						</div>															
																						<div class="form-group">
																							<label class="font-weight-bold">Longtitude</label>
																							<input type="text" name="pjm_longtitude" id="pjm_longtitude" value="<?php echo $pjm_longtitude; ?>" class="form-control" placeholder="Longtitude">
																						</div>	
																						<div class="form-group">
																							<label class="font-weight-bold" >Google Map</label>
																							<input type="text" name="pjm_google_map" id="pjm_google_map" value="<?php echo $pjm_google_map; ?>" class="form-control" placeholder="Google Map" disabled>
																						</div>			
																					</div>
																				 </div>
																			</div>
																		</div>												
																	</div>
																</div>													
															</div>														
														</div>														
														<div class="form-group row" >
															<div class="col-md-12">
																<div class="row">											
																	<div class="col-lg-6 col-md-12">																
																		<h4 class="form-section text-primary"><i class="feather icon-user"></i> Customer Information</h4>
																		<div class="form-group row">																		
																			<div class="col-md-12">
																				<div class="row">
																					<div class="col-md-5">
																						<div class="form-group">
																							<label class="font-weight-bold">Customer Code: <font class="text text-danger font-weight-bold">*</font></label>
																							<div class="input-group ">
																								<input type="text" name="pjm_custpj_code" id="pjm_custpj_code" value="<?php echo $pjm_custpj_code; ?>" class="form-control"  placeholder="Choose Customer Code">
																								<div class="input-group-append">
																									<span class="input-group-text"
																										OnClick="helppopup('../_help/getcustomer_project.php','frm_pjm_add','pjm_custpj_code','pjm_customer_name',document.frm_pjm_add.pjm_custpj_code.value)" data-dismiss="modal">
																										<i class="feather icon-search"></i>
																									</span>
																								</div>
																							</div>
																						</div>
																					</div>
																					<div class="col-md-7">
																						<div class="form-group">
																							<div class="controls">
																								<label class="font-weight-bold">Customer Name:</label>
																								<input type="text" name="pjm_customer_name" id="pjm_customer_name" value="<?php echo $custpj_name; ?>" class="form-control"  placeholder="Customer Name" disabled>
																							</div>
																						</div>	
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-md-12">
																						<div class="form-group">
																							<fieldset class="form-group">
																								<label for="placeTextarea" class="font-weight-bold">Customer Address</label>
																								<textarea class="form-control" id="placeTextarea" rows="6" placeholder="Customer Address" disabled><?php echo $custpj_addr; ?></textarea>
																							</fieldset>
																						</div>
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-md-5">
																						<div class="form-group">
																							<label class="font-weight-bold" >Email</label>
																							<input type="text" class="form-control" value="<?php echo $custpj_email; ?>" placeholder="Email" disabled>
																						</div>
																					</div>
																					 <div class="col-md-4">
																						<div class="form-group">
																							<label class="font-weight-bold">Tel.</label>
																							<input type="text" class="form-control" value="<?php echo $custpj_tel; ?>" placeholder="Tel." disabled>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label class="font-weight-bold">Line ID.</label>
																							<input type="text" class="form-control" value="<?php echo $custpj_lineid; ?>" placeholder="Line ID" disabled>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-12">
																		<h4 class="form-section text-primary"><i class="fa fa-address-card-o"></i> Contact Information</h4>
																		<div class="form-group row">																		
																			<div class="col-md-12">
																				<div class="row">
																					<div class="col-md-12">
																						<div class="form-group">
																							<label class="font-weight-bold">Contact Name</label>
																							<input type="text" id="pjm_contact_name" name="pjm_contact_name" value="<?php echo $pjm_contact_name; ?>" class="form-control" placeholder="Contact Name">
																						</div>
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-md-12">
																						<div class="form-group">
																							<fieldset class="form-group">
																								<label for="pjm_contact_addr" class="font-weight-bold">Contact Address</label>
																								<textarea class="form-control" id="pjm_contact_addr" name="pjm_contact_addr"  rows="6" placeholder="Contact Address"><?php echo $pjm_contact_addr; ?></textarea>
																							</fieldset>
																						</div>
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-md-5">
																						<div class="form-group">
																							<label class="font-weight-bold" >Email</label>
																							<input type="text" id="pjm_contact_email" name="pjm_contact_email" value="<?php echo $pjm_contact_email; ?>" class="form-control" placeholder="Email">
																						</div>
																					</div>
																					 <div class="col-md-4">
																						<div class="form-group">
																							<label class="font-weight-bold">Tel.</label>
																							<input type="text" id="pjm_contact_tel" name="pjm_contact_tel" value="<?php echo $pjm_contact_tel; ?>" class="form-control" placeholder="Tel.">
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label class="font-weight-bold">Line ID.</label>
																							<input type="text" id="pjm_contact_lineid" name="pjm_contact_lineid" value="<?php echo $pjm_contact_lineid; ?>"  class="form-control" placeholder="Line ID">
																						</div>
																					</div>
																				</div>
																			</div>									
																		</div>
																	</div>
																</div>																
															</div>														
														</div>	
														<h4 class="form-section text-primary" ><i class="fa fa-calendar"></i> Project Detail </h4>		
														<div class="form-group row" >
															<div class="col-md-12">
																<div class="row">
																	<div class="col-lg-4 col-md-4 col-sm-12">	
																		<!--Start Date : Date Picker : ex-component-date-time-picker.html-->
																		<div class="form-group">
																			<label class="font-weight-bold">Start Date</label>
																			<div class="input-group date"  >
																				<input type='text' class="form-control" id="pjm_start_date" name="pjm_start_date" value="<?php echo dmytx($pjm_start_date); ?>"  placeholder="Start Date" data-inputmask="'alias': 'datetime','inputFormat': 'dd/mm/yyyy'"/>
																				<div class="input-group-append">
																					<span class="input-group-text">
																						<span class="fa fa-calendar"></span>
																					</span>
																				</div>
																			</div>
																		</div><!---->
																		<!--Start Date : input-mask : form-extended-inputs.html -->
																		<!-- <fieldset>
																			<h5 class="font-weight-bold">Start Date
																				<small class="text-muted">dd/mm/yyyy</small>
																			</h5>
																			<div class="form-group">
																				<input id="pjm_start_date" name="pjm_start_date"  class="form-control date-inputmask" type="text" placeholder="Enter Date" data-inputmask="'alias': 'datetime','inputFormat': 'dd/mm/yyyy'">
																			</div>
																		</fieldset>-->
																		
																		<div class="form-group">
																			<label class="font-weight-bold">Budget</label>
																			<input type="text" id="pjm_budget" name ="pjm_budget" value="<?php echo $pjm_budget; ?>"  class="form-control " placeholder="Budget">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Payment Term</label>
																			<select data-placeholder="Select a project type ..." class="select2-icons form-control"  id="pjm_paymth_code" name="pjm_paymth_code">
																				<option value="" selected>(Choose Payment Term)</option>
																					<?php 
																					$sql_paymth = "SELECT * FROM paymth_mstr order by paymth_seq";
																					$result_paymth = sqlsrv_query( $conn,$sql_paymth);																													
																					while($r_paymth=sqlsrv_fetch_array($result_paymth, SQLSRV_FETCH_ASSOC)) {
																					?>
																						<option value="<?php echo $r_paymth['paymth_code'];?>"
																						<?php if ($pjm_paymth_code == $r_paymth['paymth_code']) {echo "selected";}?>>
																						<?php echo $r_paymth['paymth_name'];?></option> 
																					<?php } ?>																																	
																			</select>
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Deposit Amount</label>
																			<input type="text" id="pjm_deposit_amt" name ="pjm_deposit_amt" value="<?php echo $pjm_deposit_amt; ?>"  class="form-control " placeholder="Deposit Amount">
																		</div>
																	</div>
																	<div class="col-lg-4 col-md-4 col-sm-12">	
																		<div class="form-group">
																			<label class="font-weight-bold">End Date</label>
																			<div class="input-group date" >
																				<input type='text' class="form-control" id="pjm_end_date" name="pjm_end_date" value="<?php echo dmytx($pjm_end_date); ?>"  placeholder="End Date" data-inputmask="'alias': 'datetime','inputFormat': 'dd/mm/yyyy'"/>
																				<div class="input-group-append">
																					<span class="input-group-text">
																						<span class="fa fa-calendar"></span>
																					</span>
																				</div>
																			</div>
																		</div><!---->
																		<!--<fieldset>
																			<h5 class="font-weight-bold">End Date
																				<small class="text-muted">dd/mm/yyyy</small>
																			</h5>
																			<div class="form-group">
																				<input id="pjm_end_date" name="pjm_end_date" class="form-control date-inputmask" type="text" placeholder="Enter Date" data-inputmask="'alias': 'datetime','inputFormat': 'dd/mm/yyyy'">
																			</div>
																		</fieldset>-->
																		<!--Start Date : Mix (Date Picker,input-mask)-->
																		
																		<div class="form-group">
																			<label class="font-weight-bold">Percent Discount (%)</label>
																			<input type="text" id="pjm_per_disc" name ="pjm_per_disc" value="<?php echo $pjm_per_disc; ?>"  class="form-control " placeholder="Percent Discount">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Buy SCG Product?</label>
																			<select data-placeholder="Select True or False ..." class="select2-icons form-control"  id="pjm_buy_scg" name="pjm_buy_scg">
																				<option value="" selected>(Choose Buy or Not)</option>
																					<?php 
																					$sql_buyscg = "SELECT * FROM buyscg_mstr order by buyscg_seq";
																					$result_buyscg = sqlsrv_query( $conn,$sql_buyscg);																													
																					while($r_buyscg=sqlsrv_fetch_array($result_buyscg, SQLSRV_FETCH_ASSOC)) {
																					?>
																						<option value="<?php echo $r_buyscg['buyscg_code'];?>" 
																						<?php if ($pjm_buy_scg == $r_buyscg['buyscg_code']) {echo "selected";}?>>
																						<?php echo $r_buyscg['buyscg_name'];?></option> 
																					<?php } ?>																																	
																			</select>
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Area Size</label>
																			<input type="text" id="pjm_area_size" name ="pjm_area_size" value="<?php echo $pjm_area_size; ?>"  class="form-control " placeholder="Area Size">
																		</div>
																	</div>
																	<div class="col-lg-4 col-md-4 col-sm-12">	
																		<div class="form-group">
																			<label class="font-weight-bold">Channel</label>
																			<input type="text" id="pjm_from_channel" name ="pjm_from_channel" value="<?php echo $pjm_from_channel; ?>"  class="form-control " placeholder="From Channel (FB, Line, COTTOLife,etc.)">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Amount Discount (Baht)</label>
																			<input type="text" id="pjm_amt_disc" name ="pjm_amt_disc" value="<?php echo $pjm_amt_disc; ?>"  class="form-control " placeholder="Amount Discount">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">SCG Customer Code</label>
																			<input type="text" id="pjm_buy_scg_custcode" name ="pjm_buy_scg_custcode" value="<?php echo $pjm_buy_scg_custcode; ?>"  class="form-control " placeholder="SCG Customer Code">
																		</div>
																		<div class="form-group">
																			<label class="font-weight-bold">Site Consultant</label>
																			<select data-placeholder="Select Site Consultant ..." class="select2-icons form-control"  id="pjm_sc_code" name="pjm_sc_code">
																				<option value="" selected>(Choose SC)</option>
																					<?php 
																					$sql_sc = "SELECT * FROM sc_mstr";
																					$result_sc = sqlsrv_query( $conn,$sql_sc);																													
																					while($r_sc=sqlsrv_fetch_array($result_sc, SQLSRV_FETCH_ASSOC)) {
																					?>
																						<option value="<?php echo $r_sc['sc_code'];?>" 
																						<?php if ($pjm_sc_code == $r_sc['sc_code']) {echo "selected";}?>>
																						<?php echo $r_sc['sc_name'];?></option> 
																					<?php } ?>																																	
																			</select>
																		</div>																		
																	</div>																	
																</div>													
															</div>														
														</div>														
														<!-- Submit Button -->
														<div class="form-group row"> 
															<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																<button type="button"  id="btnsave" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
																	changes</button>
																<button type="reset" class="btn btn-light" onclick="document.location.href='../cisbof/pjdmnt.php?pjmnumber=<?php echo encrypt($pjm_nbr, $key)?>&activeid=<?php echo encrypt($pjm_nbr,$key)?>&pg=<?php echo $pg;?>'">Cancel</button>
															</div>
														</div>
													</div>
													<!-- End Form Body -->
												</form>
											</div>									
										</div>
										<!-- End Project Tab -->
									</div>
								</div>
							</div>
							<!-- End Card -->
						</div>
					</div>
				</section>
                <!-- End New Project Section -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with <i class="feather icon-heart pink"></i></span></p>
    </footer>
    <!-- END: Footer-->
	<?php include("../cismain/pjmmodal.php");?>
	<!-- BEGIN: Vendor JS-->
    <script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
	<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>-->
    <script src="../theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.flash.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/jszip.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/pdfmake.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/vfs_fonts.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.html5.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.print.min.js"></script><!---->
	
    <script src="../theme/app-assets/vendors/js/extensions/moment.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/fullcalendar.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/daygrid.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/timegrid.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/interactions.min.js"></script>
	
	<!-- ex-component-date-time-picker.html -->
	<script src="../theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
	
	<!-- form-extended-inputs.html -->
	<script src="../theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
    <!-- END: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
	
    <!--<script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>
	<script src="../theme/app-assets/js/scripts/extensions/fullcalendar-events.js"></script>
	<script src="../theme/app-assets/js/scripts/extensions/fullcalendar-extra.js"></script>-->
	<!--<script src="../theme/app-assets/js/scripts/pickers/dateTime/bootstrap-datetime.js"></script>
    <script src="../theme/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>-->
    <!-- END: Page JS-->
	
	<!-- BEGIN Nilubonp Custom JS-->
	<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
	<script type="text/javascript">
		(function(window, document, $) {
			'use strict';
			// date picker : ex-component-date-time-picker.html
			$('#pjm_start_date,#pjm_end_date').datetimepicker( {
				format: 'DD/MM/YYYY'
			});
			// input mask : form-extended-inputs.html
			$('#pjm_start_date,#pjm_end_date').inputmask("dd/mm/yyyy");
			
			// Email mask : form-extended-inputs.html
			$('#pjm_contact_email').inputmask({
				mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
				greedy: false,
				onBeforePaste: function (pastedValue, opts) {
					pastedValue = pastedValue.toLowerCase();
					return pastedValue.replace("mailto:", "");
				},
				definitions: {
					'*': {
						validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
						cardinality: 1,
						casing: "lower"
					}
				}
			});
			//Phone mask
			$('#pjm_contact_tel').inputmask("(999) 999-9999");
		})(window, document, jQuery);
		
		$(document).ready(function () {     				                         				
			$("#btnsave").click(function() {
				alert("frm_pjm_edit");
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/pjmpost.php',
					data: $('#frm_pjm_edit').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
						//alert('['+xhr+'] '+ error);
					},
					success: function(result) {	
						//console.log(result);
						alert(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg)
							$(location).attr('href', 'pjdmnt.php?pjmnumber='+json.nb+'&pg='+json.pg)
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});
			$('#team_district').typeahead({
				displayText: function(item) {
					return item.district+" >> อ. "+item.amphoe +"  >> จ. "+item.province+">> รหัสไปรษณีย์ "+item.zipcode
				   // $("#province").val(item.province);
				}, 
				source: function (query, process) {
					jQuery.ajax({
							url: "../_libs/raw_database.json",//even.php",
							data: {query:query},
							dataType: "json",
							type: "POST",
							success: function (data) {
								process(data)
									//$("#province").val(data[0].province);
							}
					})
				}, 
				afterSelect: function(item) {
					$("#team_province").val(item.province);
					$("#team_amphur").val(item.amphoe);
					$("#team_district").val(item.district);
					$("#team_zip").val(item.zipcode);
				}
	  
			});
		});		
	</script>		
	<script language="javascript">	
		function helppopup(prgname,formname,opennerfield_code,opennerfield_code2,txtsearch) {
			//alert("yes yes");
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
	
	<script type="text/javascript" language="javascript" class="init">	
		$(document).ready(function() {
			// /********************************************
			// *				Datatable				*
			// ********************************************/
			$('#pjm_qtm_list').DataTable({});
			
			// /************************************
			// *			Full Calendar json		*
			// ************************************/
			var calendarCTIS = document.getElementById('fc-json');
			var fcJson = new FullCalendar.Calendar(calendarCTIS, {
				header: {
					left: 'prev,next today',
					center: 'title',
					right: "dayGridMonth,timeGridWeek,timeGridDay"
				},
				
				defaultDate: '2020-01-01',
				editable: true,
				plugins: ["dayGrid", "timeGrid", "interaction"],
				eventLimit: true, // allow "more" link when too many events
				events: {
					//url: '../Theme/app-assets/data/fullcalendar/json/events.json',
					url: '../serverside/even.php',
					error: function() {
						$('#script-warning').show();
					}
				},
				loading: function(bool) {
					$('#loading').toggle(bool);
				}
			});

			fcJson.render();

		} );	
	</script>
</body>
<!-- END: Body-->

</html>