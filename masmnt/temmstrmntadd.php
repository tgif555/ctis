<?php 
//Temp
$user_login = "PISACHAM";

include("../_incs/acunx_metaheader.php");
//include("../_incs/chksession.php");  
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

set_time_limit(0);
$curdate = date('Ymd');

$params = array();
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

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
    <title>Users Edit - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/forms/selects/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/forms/toggle/switchery.min.css">
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
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/pages/page-users.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/extended/form-extended.css">
	
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/switch.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
	<!-- BEGIN: Header-->
    <? include("../cismain/menu_header.php"); ?>	
	<!-- END: Header-->
	 <!-- BEGIN: Main Menu-->
	<? include("../cismain/menu_leftsidebar.php"); ?>
	<!-- END: Main Menu-->
	

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Master</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="temmstmntall.php">All Master </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">C'TIS Add Team</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<a class="btn btn-primary white" href="temmstrmntadd.php"><!--i class="feather icon-file-plus icon-left"--> </i>Add New Team</a>
						<? include("menu_mstr.php");	?>
					</div>
					
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center active" id="team-tab" data-toggle="tab" href="#team" aria-controls="account" role="tab" aria-selected="true">
                                            <i class="feather icon-user-plus mr-25"></i><span class="d-none d-sm-block">Add New Team</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="sc-tab" data-toggle="tab" href="#sc_info" aria-controls="sc_info" role="tab" aria-selected="false">
                                            <i class="fa fa-user-secret mr-25"></i><span class="d-none d-sm-block">Add New SC </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="team" aria-labelledby="team-tab" role="tabpanel">
                                        
                                        <!-- Team add team form start -->
										<form novalidate name="frm_team_add" id="frm_team_add" autocomplete=OFF>
											<input type=hidden name="action" value="teamadd">
											<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
											<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
											<div class="form-body">	
												<h4 class="form-section text-primary" ><i class="fa fa-users"></i> Team Information </h4>
												<div class="form-group row">
													<div class="col-md-12">
														<div class="row">
															<div class="col-12 col-sm-6">
																<div class="form-group">
																	<label class="font-weight-bold">ชื่อทีม</label>
																	<div class="position-relative has-icon-left">
																		<input type="text" class="form-control" placeholder="ชื่อทีม" id="team_name" name="team_name" value="" required data-validation-required-message="This username field is required">
																		<div class="form-control-position">
																			<i class="fa fa-flag"></i>
																		</div>
																	</div>
																
																	<!--
																	<div class="controls">
																		<label>ชื่อทีม</label>
																		<input type="text" class="form-control" placeholder="ชื่อทีม" id="team_name" name="team_name" value="" required data-validation-required-message="This username field is required">
																	</div>
																	-->
																</div>
																<div class="row">
																	<div class="col-md-6">
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">เบอร์โทร </label>
																				<div class="form-group">
																					<input type="text" class="form-control phone-inputmask" id="team_tel" name="team_tel" placeholder="Enter Phone Number" required data-validation-required-message="This Tel field is required" />
																				</div>
																			</div>
																		
																		</div>
																				
																	</div>
																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">Line id </label>
																				<input type="text" class="form-control " placeholder="Line id " id="tem_lineid" name="tem_lineid" value="" >
																			</div>
																		
																		</div>
																	</div>
																	
																</div>
																
																<div class="form-group">
																			<label class="font-weight-bold">Status</label>
																			<select class="form-control col-6" name="team_active" id="team_active" >
																				<option value="">--Select--</option>
																				<option value="0">NOT ACTIVE</option>
																				<option value="1">ACTIVE</option>
																			</select> 
																			<!--<input type="checkbox" class="switch" id="switch1" checked="checked" />-->
																</div>	
																
																		
																
																
																
															</div>
															<div class="col-12 col-sm-6">
																<div class="form-group">
																	<label class="font-weight-bold">หัวหน้าทีม</label>
																	<div class="position-relative has-icon-left">
																		<input type="text" name="team_leader" id="team_leader"  class="form-control" placeholder="ชื่อ-นามสกุล" value="" required >
																		<div class="form-control-position">
																			<i class="feather icon-user"></i>
																		</div>
																	</div>
																	<!--
																	<select class="form-control" name="team_leader" id="team_leader">
																					<option value="">(select)</option>
																					<?php /*
																					$sql_teamleader = "SELECT * FROM  engm_mstr order by  engm_code";
																					$result_teamleader = sqlsrv_query( $conn,$sql_teamleader);																													
																					while($r_teamleader=sqlsrv_fetch_array($result_teamleader, SQLSRV_FETCH_ASSOC)) {
																					?>
																						<option value="<?php echo $r_teamleader['engm_code'];?>"><?php echo $r_teamleader['engm_name'];?></option> 
																					<?php } */?>
																	</select>
																	-->
																	
																</div>
																<div class="form-group">
																	<div class="controls">
																		<label class="font-weight-bold">Email </label>
																		<div class="form-group">
																				<input type="text" class="form-control email-inputmask" id="team_email" name="team_email" placeholder="Enter E-mail" required data-validation-required-message="This Tel field is required" />
																		</div>
																	</div>
																</div>
															</div>
															
															
														</div>
														
														
														
														
														
													</div>
												</div>
												
												<div class="form-group row">
														<div class="col-md-12">
															<h4 class="form-section text-primary" ><i class="fa fa-home"></i> Team Address </h4>
															
															<div class="row">													
																<div class="col-12 col-sm-4">
																	<div class="form-group">
																				<div class="controls">
																					<label class="font-weight-bold">ที่อยู่</label>
																					<textarea class="form-control" id="team_address" name="team_address"  rows="6" placeholder="กรุณากรอกที่อยู่" value="" required data-validation-required-message="This name field is required" ></textarea>
																					<!--
																					<input type="text" class="form-control" placeholder="กรุณากรอกที่อยู่ " id="team_address" name="team_address" value="" required data-validation-required-message="This name field is required">
																					-->
																				</div>
																			</div>
																</div>
																
																<div class="col-12 col-sm-4">
																	<div class="form-group">
																		<div class="controls">
																			<label class="font-weight-bold">ตำบล/แขวง</label>
																			<input type="text" class="form-control"  id="team_district" name="team_district" value="">
																		</div>
																	</div>
																	<div class="form-group">
																		<div class="controls">
																			<label class="font-weight-bold">จังหวัด</label>
																			<input type="text" name="team_province" id="team_province" name="team_province" class="form-control"  value="" readonly >
																			
																			
																		</div>
																	</div>
																</div>
																
																<div class="col-12 col-sm-4">
																	<div class="form-group">
																		<div class="controls">
																			<label class="font-weight-bold">อำเภอ/เขต</label>
																			<input type="text" class="form-control"  id="team_amphur" name="team_amphur" value="" readonly>
																		</div>
																	</div>
																	<div class="form-group">
																		<div class="controls">
																			<label class="font-weight-bold">รหัสไปรษณี</label>
																			<input type="text" name="team_zip" id="team_zip" class="form-control" placeholder="รหัสไปรษณี" value="" readonly >
																		</div>
																	</div>
																	
																</div>
																
																
																
															</div>
														</div>
												</div>
												
												<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
													<button type="button" id="btnsave" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" >Save</button>
													<button type="reset" class="btn btn-light" onclick="document.location.href='temmstrmntall.php'">Cancel</button>
												</div>
												
											</div>	
										</form> 		
											<!-- Team add form ends -->
												
									</div>
									
									
                                    <div class="tab-pane" id="sc_info" aria-labelledby="sc-tab" role="tabpanel">
                                        <!-- users edit Info form start -->
                                        <form class="form form-horizontal form-bordered">
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="feather icon-user"></i> Personal Info</h4>
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="projectinput1">First Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="projectinput1" class="form-control" placeholder="First Name" name="fname">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="projectinput2">Last Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="projectinput2" class="form-control" placeholder="Last Name" name="lname">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="projectinput3">E-mail</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="projectinput3" class="form-control" placeholder="E-mail" name="email">
                                                    </div>
                                                </div>

                                                <div class="form-group row last">
                                                    <label class="col-md-3 label-control" for="projectinput4">Contact Number</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="projectinput4" class="form-control" placeholder="Phone" name="phone">
                                                    </div>
                                                </div>
												<div class="form-actions text-right">
													<button type="submit" class="btn btn-primary">
														<i class="fa fa-check-square-o"></i> Save
													</button>
													<button type="button" class="btn btn-warning mr-1">
														<i class="feather icon-x"></i> Cancel
													</button>
												</div>
											</div>	
                                        </form>
                                        <!-- users edit Info form ends -->
                                    
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
    <script src="../theme/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
	<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
	
	<script src="../theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
	
	<script src="../theme/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js"></script>
    <script src="../theme/app-assets/vendors/js/forms/toggle/switchery.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/pages/page-users.js"></script>
    <script src="../theme/app-assets/js/scripts/navs/navs.js"></script>
	<script src="../theme/app-assets/js/scripts/forms/switch.js"></script>
	
    <!--END: Page JS-->
	
	 <!-- BEGIN: Custom JS-->
     <script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
	
    <!-- END: Custom JS-->


	<script type="text/javascript">
		$(document).ready(function () {     				                         				
			$("#btnsave").click(function() {
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/temmstrpost.php',
					data: $('#frm_team_add').serialize(),
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
							$(location).attr('href', 'temmstrmntdet.php?temnumber='+json.nb)
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});//จบส่วนของ BTSAVE
			$('#team_district,#team_amphur,#team_province').typeahead({
				displayText: function(item) {
					return item.district+" >> อ. "+item.amphoe +"  >> จ. "+item.province+">> รหัสไปรษณีย์ "+item.zipcode
				   // $("#province").val(item.province);
				}, 
				source: function (query, process) {
					jQuery.ajax({
							url: "../_libs/thailandjson/raw_database.json",//even.php",
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
			$('.phone-inputmask').inputmask("(999) 999-9999");
			// Email mask
			$('.email-inputmask').inputmask({
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
		});		
	
		function showdata() {													
			document.frm.submit();												
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