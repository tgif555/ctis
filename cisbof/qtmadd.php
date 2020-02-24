<?php 
//OOOOOOOOOOOOOOOOOOO
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

$activeid = html_escape($_REQUEST['activeid']);
$pg = html_escape($_REQUEST['pg']);
$curdate = date('d/m/Y');
clearstatcache();
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
								<li class="breadcrumb-item"><a href="#">All Quotation</a></li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">New Quotation</h3>
                </div>
                <!--div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i> Actions</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
								<a class="dropdown-item" href="../cisbof/qtmadd.php?pg=<?php echo $currentpage?>">New Quotation</a>
								<a class="dropdown-item" href="component-buttons-extended.html">xxxx</a>
							</div>
                        </div><a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="feather icon-mail"></i></a><a class="btn btn-outline-primary" href="timeline-center.html"><i class="feather icon-pie-chart"></i></a>
                    </div>
                </div-->
            </div>
            <div class="content-body mt-n1">
                <!-- File export table -->
                <section id="file-export">
					<div class="row">
                        <div class="col-12">
                            <div class="card">
								<div class="card-header mt-1 pt-0 pb-0" >
									<div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <!--<li><a data-action="close"><i class="feather icon-x"></i></a></li>-->
                                        </ul>
                                    </div>
                                    
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard" style="margin-top:-20px;">
										<ul class="nav nav-tabs mb-2" role="tablist">
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
													<i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Quotation Data</span>
												</a>
											</li>
											<!--li class="nav-item">
												<a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
													<i class="feather icon-info mr-25"></i><span class="d-none d-sm-block">Customer</span>
												</a>
											</li-->
										</ul>
										<div class="tab-content small">
											<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
												<!-- users edit media object start -->
												<!--div class="media mb-2">
													<a class="mr-2" href="#">
													<img src="../Theme/app-assets/images/portrait/small/avatar-s-26.png" alt="users avatar" class="users-avatar-shadow rounded-circle" height="64" width="64">
													</a>
													<div class="media-body">
														<h4 class="media-heading">Avatar</h4>
														<div class="col-12 px-0 d-flex">
															<a href="#" class="btn btn-sm btn-primary mr-25">Change</a>
															<a href="#" class="btn btn-sm btn-secondary">Reset</a>
														</div>
													</div>
												</div-->
												<!-- users edit media object ends -->
												<!-- users edit account form start -->
												<FORM id="frm_qtm_add" name="frm_qtm_add" autocomplete=OFF>
													<input type=hidden name="action" value="qtmadd">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
													<input type=hidden name="pg" value="<?php echo $pg?>">
													<div class="row">
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Quotation To:</label>
																		<input type="text" name="qtm_to" id="qtm_to" class="form-control form-control-sm" required data-validation-required-message="This username field is required">
																</div>
															</div>
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Quotation Name:</label>
																	<input type="text" name="qtm_name" id="qtm_name" class="form-control form-control-sm">
																</div>
															</div>
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Quotation Detail:</label>
																	<textarea name="qtm_detail" id="qtm_detail" class="form-control form-control-sm" rows="4"></textarea>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Date:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend date">
																				<span class="input-group-text">
																					<span class="fa fa-calendar-o"></span>
																				</span>
																			</div>
																			<input type='text' name="qtm_date" id="qtm_date" class="form-control form-control-sm" placeholder="dd/mm/yyyy" />
																		</div>
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Expire:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend date">
																				<span class="input-group-text">
																					<span class="fa fa-calendar-o"></span>
																				</span>
																			</div>
																			<input type='text' name="qtm_expire_date" id="qtm_expire_date" class="form-control form-control-sm" placeholder="dd/mm/yyyy" />
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold red">Pre-Paid Amount:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="feather icon-bold"></i></span>
																			</div>
																			<input type="text" name="qtm_prepaid_amt" id="qtm_prepaid_amt" class="form-control form-control-sm">
																		</div>
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Pre-Paid Date:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend date">
																				<span class="input-group-text">
																					<span class="fa fa-calendar-o"></span>
																				</span>
																			</div>
																			<input type='text' name="qtm_prepaid_date" id="qtm_prepaid_date" class="form-control form-control-sm" placeholder="dd/mm/yyyy" />
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Discount:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="feather icon-bold"></i></span>
																			</div>
																			<input type="text" name="qtm_disc" id="qtm_disc" class="form-control form-control-sm">
																		</div>
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Unit:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="feather icon-scissors"></i></span>
																			</div>
																			<select name="qtm_disc_unit" id="qtm_disc_unit" class="form-control form-control-sm">
																				<option value="">(select)</option>
																				<option value="P">%</option>
																				<option value="B">บาท</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Remark:</label>
																	<textarea name="qtm_remark" id="qtm_remark" class="form-control form-control-sm" rows="4"></textarea>
																</div>
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label class="font-weight-bold">Customer Code:</label>
																<div class="input-group input-group-sm">
																	<input type="text" name="qtm_customer_number" id="qtm_customer_number" class="form-control">
																	<div class="input-group-append">
																		<span class="input-group-text"
																			OnClick="helppopup('../_help/getcustomer.php','frm_qtm_add','qtm_customer_number','qtm_customer_name',document.frm_qtm_add.qtm_customer_number.value)" data-dismiss="modal">
																			<i class="feather icon-search"></i>
																		</span>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Customer Name:</label>
																	<input type="text" name="qtm_customer_name" id="qtm_customer_name" class="form-control form-control-sm">
																</div>
															</div>
															<div class="form-group">
																<div class="controls">
																	<label class="font-weight-bold">Address:</label>
																		<textarea name="qtm_address" id="qtm_address" class="form-control form-control-sm" rows="4"></textarea>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Amphur:</label>
																		<div class="input-group input-group-sm">
																			<input type="text" name="qtm_amphur" id="qtm_amphur" class="form-control">
																			<div class="input-group-append">
																				<span class="input-group-text"
																					OnClick="helppopup('../_help/getamphur.php','frm_qtm_add','qtm_amphur','qtm_province','')" data-dismiss="modal">
																					<i class="feather icon-search"></i>
																				</span>
																			</div>
																		</div>
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Province:</label>
																		<div class="input-group input-group-sm">
																			<input type="text" name="qtm_province" id="qtm_province" class="form-control">
																			<div class="input-group-append">
																				<span class="input-group-text"
																					OnClick="helppopup('../_help/getprovince.php','frm_qtm_add','qtm_province','','')" data-dismiss="modal">
																					<i class="feather icon-search"></i>
																				</span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Zip Code:</label>
																		<input type="text" name="qtm_zip_code" id="qtm_zip_code" class="form-control form-control-sm">
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Tel Contact:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="feather icon-phone-call"></i></span>
																			</div>
																			<input type="text" name="qtm_tel_contact" id="qtm_tel_contact" class="form-control">
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="row">
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Line ID:</label>
																		<input type="text" name="qtm_lineid" id="qtm_lineid" class="form-control form-control-sm">
																	</div>
																	<div class="col-lg-6">
																		<label class="font-weight-bold">Email Address:</label>
																		<div class="input-group input-group-sm">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="feather icon-mail"></i></span>
																			</div>
																			<input type="email" name="qtm_email" id="qtm_email" class="form-control form-control-sm" placeholder="Email">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                </section>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm grey btn-outline-secondary" onclick="document.location.href='../cisbof/qtmall.php?pg=<?php echo $pg;?>'">Cancel</button>
				<button type="button" id="btnsave" class="btn btn-sm btn-outline-success" >Save changes</button>				
			</div>
        </div>
    </div>
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

	<script type="text/javascript">
		$(document).ready(function () {
			$('#qtm_date').datetimepicker( {
				format: 'DD/MM/YYYY'
			});
			$('#qtm_expire_date').datetimepicker( {
				format: 'DD/MM/YYYY'
			});
			$('#qtm_prepaid_date').datetimepicker( {
				format: 'DD/MM/YYYY'
			});
			$("#btnsave").click(function() {
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/qtmpost.php',
					data: $('#frm_qtm_add').serialize(),
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
							$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg)
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
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

</body>
<!-- END: Body-->
</html>
