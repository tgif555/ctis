<?php 
//Temp
$user_login = "PISACHAM";
	$gbv_editprice = false;
	//$gbv_auction_type = "PRICE"; //PRICE,SEQ
	$gbv_auction_type = "SEQ";
	
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
	clearstatcache();

	$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));
	$tem_code = html_escape(decrypt($_REQUEST['temnumber'], $key));
	//$pg = html_escape($_REQUEST['pg']);
	
	$curdate = date('d/m/Y');
	$filepath_at = "../_fileuploads/at/";
	
	
	$params = array($tem_code);
	$sql_tem = "SELECT * from tem_mstr where tem_code = ? ";
	$result_tem = sqlsrv_query($conn, $sql_tem,$params);	
	$rec_tem = sqlsrv_fetch_array($result_tem, SQLSRV_FETCH_ASSOC);		
	if ($rec_tem) {
		$tem_code = mssql_escape($rec_tem['tem_code']);
		$tem_name = mssql_escape($rec_tem['tem_name']);
		//$tem_engm_code = mssql_escape($rec_tem['tem_engm_code']);
		//$tem_engm_name = findsqlval("engm_mstr"," engm_name", "engm_code", $tem_engm_code,$conn);
		$tem_leader_name = mssql_escape($rec_tem['tem_leader_name']);	
		$tem_addr = mssql_escape($rec_tem['tem_addr']);
		$tem_district = mssql_escape($rec_tem['tem_district']);
		$tem_amphur = mssql_escape($rec_tem['tem_amphur']);
		$tem_province = mssql_escape($rec_tem['tem_province']);
		$tem_zip = mssql_escape($rec_tem['tem_zipcode']);	
		$tem_tel = mssql_escape($rec_tem['tem_tel']);
		$tem_lineid = mssql_escape($rec_tem['tem_lineid']);
		$tem_email = mssql_escape($rec_tem['tem_email']);	
			
		$tem_active = mssql_escape($rec_tem['tem_active']);
		if ($tem_active == "1") { $tem_active_text = "ACTIVE"; }
		else {$tem_active_text = "NOT ACTIVE"; }	
		
		
		
		$tem_create_by = mssql_escape($rec_tem['tem_create_by']);	
		$tem_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $tem_create_by,$conn);						
	}
	else {
		
		$path = "authorize.php?msg=ทีม $tem_code ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
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
                    <h3 class="content-header-title mb-0">C'TIS Edit Team</h3>
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
                                            <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Edit Team</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="team" aria-labelledby="team-tab" role="tabpanel">
                                        <!-- users edit media object start -->
                                        <!--<div class="media mb-2">
                                            <a class="mr-2" href="#">
                                                <img src="../theme/app-assets/images/portrait/small/avatar-s-26.png" alt="users avatar" class="users-avatar-shadow rounded-circle" height="64" width="64">
                                            </a>
                                            <div class="media-body">
                                                <h4 class="media-heading">Avatar</h4>
                                                <div class="col-12 px-0 d-flex">
                                                    <a href="#" class="btn btn-sm btn-primary mr-25">Change</a>
                                                    <a href="#" class="btn btn-sm btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </div>-->
                                        <!-- users edit media object ends -->
										
                                        <!-- Team add team form start -->
										
                                        <form novalidate name="frm_team_edit" id="frm_team_edit" autocomplete=OFF>
											<input type=hidden name="action" value="teamedit">
											<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
											<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
											<input type="hidden" name="tem_code" value="<?php echo $tem_code?>">
											<div class="form-body">	
												<h4 class="form-section text-primary" ><i class="fa fa-cube"></i>Edit Team Information </h4>
												
												<div class="form-group row">
														<div class="col-md-12">
															<div class="row">
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label class="font-weight-bold">ชื่อทีม</label>
																		<div class="position-relative has-icon-left">
																			 <input type="text" class="form-control" placeholder="ชื่อทีม" id="team_name" name="team_name" value="<?php echo $tem_name?>" required data-validation-required-message="This username field is required">
																			<div class="form-control-position">
																				<i class="fa fa-flag"></i>
																			</div>
																		</div>
																	
																	</div>
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<div class="controls">
																					<label class="font-weight-bold">เบอร์โทร </label>
																					<div class="form-group">
																						<input type="text" class="form-control phone-inputmask" id="team_tel" name="team_tel" value="<?php echo $tem_tel?>"  placeholder="Enter Phone Number" required data-validation-required-message="This Tel field is required" />
																					</div>
																				</div>
																			
																			</div>
																					
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group">
																				<div class="controls">
																					<label class="font-weight-bold">Line id </label>
																					<input type="text" class="form-control " placeholder="Line id " id="tem_lineid" name="tem_lineid" value="<?php echo $tem_lineid ?>" >
																				</div>
																			
																			</div>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label>Status</label>
																		<select class="form-control col-md-3" name="team_active" id="team_active" >
																			<option value="">--Select--</option>
																			<option value="0" <?php if ($tem_active_text == "NOT ACTIVE") {echo "selected";}?>>NOT ACTIVE</option>
																			<option value="1"<?php if ($tem_active_text == "ACTIVE") {echo "selected";}?> >ACTIVE</option>
																		</select>
																	</div>	
																</div>
																
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label class="font-weight-bold">หัวหน้าทีม</label>
																		<div class="position-relative has-icon-left">
																			<input type="text" name="team_leader" id="team_leader"  class="form-control" placeholder="ชื่อ-นามสกุล" value="<?php echo $tem_leader_name ?>" required >
																			<div class="form-control-position">
																				<i class="feather icon-user"></i>
																			</div>
																		</div>
																		
																		
																	</div>
																	<div class="form-group">
																		<div class="controls">
																			<label class="font-weight-bold">Email </label>
																			<div class="form-group">
																					<input type="text" class="form-control email-inputmask" id="team_email" name="team_email" placeholder="Enter E-mail" value="<?php echo $tem_email ?>" />
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
																						<textarea class="form-control" id="team_address" name="team_address"  rows="6" placeholder="กรุณากรอกที่อยู่"  required data-validation-required-message="This name field is required" ><?php echo $tem_addr ?></textarea>
																						
																					</div>
																				</div>
																	</div>
																	
																	<div class="col-12 col-sm-4">
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">ตำบล/แขวง</label>
																				<input type="text" class="form-control"  id="team_district" name="team_district" value="<?php echo $tem_district ?>">
																			</div>
																		</div>
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">จังหวัด</label>
																				<input type="text" name="team_province" id="team_province" name="team_province" class="form-control" value="<?php echo $tem_province ?>" readonly >
																				
																				
																			</div>
																		</div>
																	</div>
																	
																	<div class="col-12 col-sm-4">
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">อำเภอ/เขต</label>
																				<input type="text" class="form-control"  id="team_amphur" name="team_amphur" value="<?php echo $tem_amphur ?>" readonly>
																			</div>
																		</div>
																		<div class="form-group">
																			<div class="controls">
																				<label class="font-weight-bold">รหัสไปรษณี</label>
																				<input type="text" name="team_zip" id="team_zip" class="form-control" placeholder="รหัสไปรษณี" value="<?php echo $tem_zip ?>" readonly >
																			</div>
																		</div>
																		
																	</div>
																	
																	
																	
																</div>
															</div>
												</div>
												
												<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
													<button type="button" id="btnsave" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" >Save Change</button>
													<button type="reset" class="btn btn-light" onclick="document.location.href='temmstrmntall.php'">Cancel</button>
												</div>
												
												
												
												
												
												
												
												
												
										</div>		
												
											
											
										</form> 
										
                                        <!-- Team add form ends -->
											
										
											
                                    </div>
									
                                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                                        <!-- users edit Info form start -->
                                        
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
    <!-- END: Page JS-->
	
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
					data: $('#frm_team_edit').serialize(),
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
			$('#team_district').typeahead({
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