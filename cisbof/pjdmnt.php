<?php
	//Temp
	$default_current_tab="10";
	$request_tab = $_REQUEST['current_tab'];
	if($request_tab !="")
	{ $current_tab = $request_tab; }
	else
	{ $current_tab= $default_current_tab; }
	
	$gbv_auction_type = "SEQ";
	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
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
	$sql_pjm = "SELECT * from pjm_mstr where pjm_nbr = '$pjm_nbr'";
	$result_pjm = sqlsrv_query($conn, $sql_pjm,$params);	
	$rec_pjm = sqlsrv_fetch_array($result_pjm, SQLSRV_FETCH_ASSOC);		
	if ($rec_pjm) {
		$pjm_nbr= html_escape($rec_pjm['pjm_nbr']);
		$pjm_name= html_escape($rec_pjm['pjm_name']);
		$pjm_addr= html_escape($rec_pjm['pjm_addr']);
		$pjm_district= html_escape($rec_pjm['pjm_district']);
		$pjm_amphur= html_escape($rec_pjm['pjm_amphur']);
		$pjm_province= html_escape($rec_pjm['pjm_province']);
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
		//$amphur_th_name = findsqlval("amphur_mstr","amphur_th_name", "amphur_id", $pjm_amphur,$conn);	
		//$province_th_name = findsqlval("province_mstr","province_th_name", "province_id", $pjm_province,$conn);	
	
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
			$custpj_contact_name = $rec_customer['custpj_contact_name'];
			$custpj_contact_addr = $rec_customer['custpj_contact_addr'];
			$custpj_contact_tel = $rec_customer['custpj_contact_tel'];
			$custpj_contact_lineid = $rec_customer['custpj_contact_lineid'];
			$custpj_contact_email = $rec_customer['custpj_contact_email'];
			}
	}
	else {
		//$path = "authorize.php?msg=เอกสารหมายเลข $pjm_nbr ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		$path = "pjdmnt.php?pjmnumber=".$_REQUEST['pjmnumber']."&current_tab=30"; 
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

	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
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
	<!--Test Sync From Local (Nilubonp)-->

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
                    <h3 class="content-header-title mb-0"><?php echo $pjm_nbr; ?></h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">                           
							<a class="btn btn-outline-primary" href="pjdedit.php?pjmnumber=<?php echo encrypt($pjm_nbr, $key);?>&pg=<?php echo $pg;?>"><i class="fa fa-pencil-square-o icon-left"></i>Edit Project</a>
						</div>
						<a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="fa fa-download"></i></a>
						<a class="btn btn-outline-primary" href="full-calender-events.html"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="new-project">
					<div class="row">
                        <div class="col-12">							
							<div class="card">
								<div class="card-header mt-1 pt-0 pb-0" >
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
									<div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a  href='#div_add_qtm_project' data-toggle='modal'><i class='fa fa-plus'></i> Add Quotation</a></li>
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <!--<li><a data-action="close"><i class="feather icon-x"></i></a></li>-->
                                        </ul>
                                    </div>
                                </div>
								<div class="card-content collapse show">                                    		
									<div class="card-body" style="margin-top:-20px;">
										<ul class="nav nav-tabs mb-2 mt-0" role="tablist">										
											
											<?php if ($current_tab == "10"){ ?> 
													<?php $active = 'active'; ?>
												<?php } else { ?>
													<?php $active = ''; ?>
												<?php } ?>
												<li class="nav-item">
													<a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="project-tab" data-toggle="tab" href="#project" aria-controls="project" role="tab" aria-selected="true">
														<i class="fa fa-cube mr-25"></i><span class="d-none d-sm-block font-weight-bold">Project Info</span>
													</a>
												</li>
											
												<?php if ($current_tab == "20"){ ?>
													<?php $active = 'active'; ?>
												<?php } else { ?>
													<?php $active = ''; ?>
												<?php } ?>
												<li class="nav-item">
													<a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="customer-tab" data-toggle="tab" href="#customer" aria-controls="customer" role="tab" aria-selected="false">
														<i class="fa fa-user-o mr-25"></i><span class="d-none d-sm-block font-weight-bold">Customer</span>
													</a>
												</li>
												
												<?php if ($current_tab == "30"){ ?>
													<?php $active = 'active'; ?>
												<?php } else { ?>
													<?php $active = ''; ?>
												<?php } ?>
												<li class="nav-item">
													<a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="quotation-tab" data-toggle="tab" href="#quotation" aria-controls="quotation" role="tab" aria-selected="false">
														<i class="fa fa-file-text-o mr-25"></i><span class="d-none d-sm-block font-weight-bold">Quatation List</span>
													</a>
												</li>	
												
												<?php if ($current_tab == "40"){ ?>
													<?php $active = 'active'; ?>
												<?php } else { ?>
													<?php $active = ''; ?>
												<?php } ?>
												<li class="nav-item">
													<a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="timeline-tab" data-toggle="tab" href="#timeline" aria-controls="timeline" role="tab" aria-selected="false">
														<i class="fa fa-calendar mr-25"></i><span class="d-none d-sm-block font-weight-bold">Timeline</span>
													</a>
												</li>
										</ul>
										<!-- Start Project Tab -->
										<div class="tab-content">
											<?php 
												if ($current_tab == "10"){ 
													$active = 'active';
												} else { 
													$active = ''; 
												} 
											?>
											<div class="tab-pane <?php echo $active; ?>" id="project" aria-labelledby="project-tab" role="tabpanel">
												<?php include("pjdmnt_header.php"); ?>
											</div>
											<?php 
												if ($current_tab == "20"){ 
													$active = 'active';
												} else { 
													$active = ''; 
												} 
											?>
											<div class="tab-pane <?php echo $active; ?>" id="customer" aria-labelledby="customer-tab" role="tabpanel">									
												<?php include("pjdmnt_customer.php"); ?>
												
											</div>
											<?php 
												if ($current_tab == "30"){ 
													$active = 'active';
												} else { 
													$active = ''; 
												} 
											?>
											<div class="tab-pane <?php echo $active; ?>" id="quotation" aria-labelledby="quotation-tab" role="tabpanel">
												<?php include("pjdmnt_detail.php"); ?>
											</div>
											<?php 
												if ($current_tab == "40"){ 
													$active = 'active';
												} else { 
													$active = ''; 
												} 
											?>
											<div class="tab-pane <?php echo $active; ?>" id="timeline" aria-labelledby="timeline-tab" role="tabpanel">									
											<div class="row">
													<div class="col-12">
														<div class="card">                               
															<div class="card-content collapse show">
																<div class="card-body">
																	<div id='fc-json'></div>
																</div>
															</div>
														</div>
													</div>
												</div>    
											</div>
										
										</div>
										<!-- End Project Tab -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
                <!-- users edit ends -->
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
	<?php include("../cismain/modal.php");?>
	
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
	

	
	<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!--<script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>
	<script src="../theme/app-assets/js/scripts/extensions/fullcalendar-events.js"></script>
	<script src="../theme/app-assets/js/scripts/extensions/fullcalendar-extra.js"></script>-->
    <!-- END: Page JS-->
	
	<!-- BEGIN Nilubonp Custom JS-->
	<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {  
			$('#search_qtm').typeahead({	
				displayText: function(item) {
					return item.qtm_nbr+" "+item.qtm_name;
				}, 
				source: function (query, process) {
					jQuery.ajax({
							url: "../_help/getquotation_valid.php?custnumber=<?php echo encrypt($pjm_custpj_code,$key); ?>",//even.php",
							data: {query:query},
							dataType: "json",
							type: "POST",
							success: function (data) {
								process(data)
									//$("#province").val(data[0].province);
							},
							error: function(xhr, error){
								showmsg('['+xhr+'] '+ error);
							}
						})
				}, 
				afterSelect: function(item) {				
					$("#qtm_nbr").val(item.qtm_nbr);					
					$("#qtm_name").val(item.qtm_name);
					$("#search_qtm").val("");	
					//$('#qtm_name').attr('readonly', 'true');
				}			  
			});
			$("#btnsave").click(function() {
				//alert("frm_pjm_add");
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/pjmpost.php',
					data: $('#frm_pjm_add').serialize(),
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
							//$(location).attr('href', 'qtdmnt.php?pjmnumber='+json.nb+'&pg='+json.pg)
							$(location).attr('href', 'pjmadd.php?pjmnumber='+json.nb+'&pg='+json.pg)
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});
			
			$(document).on('click', '#btdel', function(e){
				var qtmnumber = $(this).data('qtmnumber');
				var pjmnumber = $(this).data('pjmnumber');				
				SwalDelete(qtmnumber,pjmnumber);
				e.preventDefault();
			});
			
		});		
	</script>		
	<script language="javascript">	
		function SwalDelete(qtmnumber,pjmnumber){
			Swal.fire({
				title: "Are you sure?",
				html: "คุณต้องการลบรหัส  " + qtmnumber + "<br> จาก Project : " + pjmnumber + " นี้ใช่หรือไหม่ !!!! ",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!",
				confirmButtonClass: "btn btn-primary",
				cancelButtonClass: "btn btn-danger ml-1",
				buttonsStyling: false,
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						document.frm_del_qtm_project.pjm_nbr.value = pjmnumber;  
						document.frm_del_qtm_project.qtm_nbr.value = qtmnumber;  
						
						$.ajax({
							beforeSend: function () {
								$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
								$("#requestOverlay").show();/*Show overlay*/
							},
							type: 'POST',
							url: '../serverside/pjdmnt_detail_post.php',
							data: $('#frm_del_qtm_project').serialize(),
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
									Swal.fire({
									  title: "Error!",
									  html: json.e,
									  type: "error",
									  confirmButtonClass: "btn btn-danger",
									  buttonsStyling: false
									});
								}
								else {
									clearloadresult();
									//location.reload(true);
									Swal.fire({
											position: "top-end",
											type: "success",
											title: "Delete Successful",
											showConfirmButton: false,
											timer: 1500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
									location.reload(true);
									//$(location).attr('href', 'pjdmnt.php?pjmnumber='+json.nb+'&pg='+json.pg+'&current_tab=30')
								}
							},
							complete: function () {
								$("#requestOverlay").remove();/*Remove overlay*/
							}
						});				 
					});
				},
			allowOutsideClick: false			  
			});			
		}
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
			//alert(txtsearch);
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
		function pjm_qtm_postform(formname) { // Use in pjdmnt_detail.php 
			//alert("pjm_qtm_postform");
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/pjdmnt_detail_post.php',
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
						//location.reload(true);
						$(location).attr('href', 'pjdmnt.php?pjmnumber='+json.nb+'&pg='+json.pg+'&current_tab=30')
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		function del_qtm_project(formname) {
			if(confirm('ท่านต้องการลบ Quotation หมายเลขนี้ จากโปรเจคไช่หรือไม่ ?')) {	
				//var pjm_nbr = document.forms[formname].pjm_nbr.value;
				//alert(pjm_nbr);
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/pjdmnt_detail_post.php',
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
							//location.reload(true);
							$(location).attr('href', 'pjdmnt.php?pjmnumber='+json.nb+'&pg='+json.pg+'&current_tab=30')
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		
	</script>
	
	<script type="text/javascript" language="javascript" class="init">	
		$(document).ready(function() {
			// /********************************************
			// *				Datatable				*
			// ********************************************/
			$('#pjm_pjm_list').DataTable({});
			
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
					url: '../serverside/even.php?pjmnumber=<?php echo encrypt($pjm_nbr,$key); ?>&custnumber=<?php echo encrypt($pjm_custpj_code,$key); ?>',
					error: function() {
						$('#script-warning').show();
					}
				},
				// eventClick: function(event) {
					// if (event.url) {
						// window.open(event.url, "_blank");
						// return false;
					// }
				// },
				// eventClick: function(event) { 
					//If extern url/domain 
					// if (event.url.indexOf(document.location.hostname) === -1) {
					  // Open url in new window
					   // window.open(event.url, "_blank");
					  // Deactivate original link
					   // return false;
					// }
				// },
				  eventClick: function(info) {
					info.jsEvent.preventDefault(); // don't let the browser navigate

					if (info.event.url) {
					  window.open(info.event.url);
					}
				  },
				loading: function(bool) {
					$('#loading').toggle(bool);
				}
			});

			fcJson.render();

		} );
		jQuery('#fc-json').on( 'click', '#fc-json', function(e){
			e.preventDefault();
			window.open( jQuery(this).attr('href'), '_blank' );
		});		
	</script>
</body>
<!-- END: Body-->

</html>