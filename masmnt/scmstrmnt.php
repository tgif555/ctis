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
	if (!matchToken($csrf_key, $user_login)) {
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
	<title>Site Consultant</title>
	<link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
	<link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

	<!-- BEGIN: Vendor CSS-->
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/extensions/sweetalert2.min.css">

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
	<!--<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/simple-line-icons/style.min.css">-->
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/validation/form-validation.css">

	<!-- END: Page CSS-->

	<!-- BEGIN: Custom CSS-->
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/project_style.css">
	<link href="../_libs/css/cisbof.css" rel="stylesheet">
	<!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

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
								<li class="breadcrumb-item"><a href="index.html">Home</a>
								</li>
								<li class="breadcrumb-item"><a href="#">Master</a>
								</li>
								<li class="breadcrumb-item active"><a href="scmstrmnt.php">All Master </a>
								</li>
							</ol>
						</div>
					</div>
					<h3 class="content-header-title mb-0">C'TIS Master</h3>
				</div>
				<div class="content-header-right col-md-6 col-12">
					<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<a class="btn btn-primary white" data-toggle="modal" data-target="#div_frm_sc_add">
							<!--i class="feather icon-file-plus icon-left"--> </i>Add New SC</a>
						<? include("menu_mstr.php");	?>
					</div>

				</div>
			</div>

			<div class="content-body">
				<!-- File export table -->
				<section id="file-export">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header ">

									<h4 class="card-title form-section text-primary "><i class="fa fa-users"></i> Master Site Consultant</h4>
									<!--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a> -->
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
										<div class="table-responsive">
											<!--class="table-responsive" -->

											<table id="teamdatatable" class="table table-striped">
												<thead>
													<tr class="bg-primary font-weight-bold white text-center ">
														<th>No.</th>
														<th>Code</th>
														<th>NAME</th>
														<th>Tel</th>
														<th>Line ID</th>
														<th>E-mail</th>
														<th>user</th>
														<th>Action</th>

													</tr>
												</thead>
												<tbody>
													<?php
													$n = 0;
													//  sc_code, sc_name, sc_addr, sc_tel, sc_lineid, sc_email, sc_create_by, sc_create_date, sc_update_by, sc_update_date

													$sql_sc = "SELECT * FROM sc_mstr";

													$result_sc = sqlsrv_query($conn, $sql_sc);
													while ($r_sc = sqlsrv_fetch_array($result_sc, SQLSRV_FETCH_ASSOC)) {
														$sc_code = html_escape($r_sc['sc_code']);
														$sc_name = html_escape($r_sc['sc_name']);
														$sc_tel = html_escape($r_sc['sc_tel']);
														$sc_lineid = html_escape($r_sc['sc_lineid']);
														$sc_email = html_escape($r_sc['sc_email']);
														$sc_emp_user_id = html_escape($r_sc['sc_emp_user_id']);

														$n++;
													?>
														<tr>

															<td class="text-center"><?php echo $n + ($currentpage - 1) * $pagesize; ?></td>
															<td><?php echo $sc_code; ?></td>
															<td><?php echo $sc_name; ?></td>
															<td><?php echo $sc_tel; ?></td>
															<td class="text-center"><?php echo $sc_lineid; ?></td>
															<td><?php echo $sc_email; ?></td>
															<td><?php echo $sc_emp_user_id; ?></td>
															<td>
																<!-- Start btn-group -->
																<div>
																	<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																	<div class="dropdown-menu">
																		<a class="dropdown-item" href="#div_frm_mat_edit<?php echo $sc_code ?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Edit/Update</a>
																		<a class="dropdown-item" id="btdel" data-id="<?php echo $sc_code; ?>" href="javascript:void(0)"><i class="fa fa-trash-o"></i> Delete</a>
																	</div>
																</div>
																<!-- /btn-group -->

															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- File export table -->

			</div>
		</div>

	</div>

	<!-- END: Content-->
	<!-- Modal add Mat-->
	<div class="modal fade" id="div_frm_sc_add">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-primary white">
					<h4 class="modal-title"><i class="fa fa-file-text-o"></i> New sc</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form class="form form-horizontal form-bordered">
						<input type="hidden" name="action" value="scadd">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">

						<div class="form-body">
							<h4 class="form-section"><i class="feather icon-user"></i> Site Consultant Info</h4>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="projectinput1">ID</label>
								<div class="col-md-9">
									<input type="text" id="sc_code" class="form-control" placeholder="" name="sc_code">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="projectinput1">Name</label>
								<div class="col-md-9">
									<input type="text" id="sc_name" class="form-control" placeholder="First Name" name="sc_name">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="projectinput2">Tel</label>
								<div class="col-md-9">
									<input type="text" class="form-control phone-inputmask" id="sc_tel" name="sc_tel" placeholder="Enter Phone Number" required data-validation-required-message="This Tel field is required" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="projectinput3">E-mail</label>
								<div class="col-md-9">
									<input type="text" class="form-control email-inputmask" id="sc_email" name="sc_email" placeholder="Enter E-mail" required data-validation-required-message="This Tel field is required" />
								</div>
							</div>
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="matpostform('<?php echo "frm_mat_add"; ?>')">
						<i class="icon-check icon-white"></i>
						<span>Save</span>
					</button>
					<button type="button" class="btn btn-warning mr-1">
						<i class="feather icon-x"></i> Cancel
					</button>
				</div>

			</div>
		</div>
	</div>
	<!--End Modal add Mat-->

	<form name="frmdelete" id="frmdelete" method="post" action="">
		<input type="hidden" name="action" value="scdel">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
		<input type="hidden" name="sc_code">
		<input type="hidden" name="pg">
	</form>

	<div class="sidenav-overlay"></div>
	<div class="drag-target"></div>

	<!-- BEGIN: Footer-->
	<footer class="footer footer-static footer-light navbar-border">
		<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with <i class="feather icon-heart pink"></i></span></p>
	</footer>
	<!-- END: Footer-->

	<?php include("../cismain/modal.php"); ?>

	<!-- BEGIN: Vendor JS-->
	<script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
	<!-- BEGIN Vendor JS-->



	<!-- BEGIN: Page Vendor JS-->
	<script src="../theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/buttons.flash.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/jszip.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/pdfmake.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/vfs_fonts.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/buttons.html5.min.js"></script>
	<script src="../theme/app-assets/vendors/js/tables/buttons.print.min.js"></script>
	<script src="../theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
	<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
	<script src="../theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>

	<!-- END: Page Vendor JS-->

	<!-- BEGIN: Theme JS-->
	<script src="../theme/app-assets/js/core/app-menu.js"></script>
	<script src="../theme/app-assets/js/core/app.js"></script>
	<!-- END: Theme JS-->

	<!-- BEGIN: Page JS-->

	<script src="../theme/app-assets/js/scripts/pages/page-users.js"></script>
	<!-- END: Page JS-->

	<!-- BEGIN: Custom JS-->
	<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>

	<!-- END: Custom JS-->

	<script>
		$(document).ready(function() {
			$('#teamdatatable').DataTable({

			});

			$(document).on('click', '#btdel', function(e) {

				var tem_code = $(this).data('id');

				SwalDelete(tem_code);
				e.preventDefault();
			});

			$('#sc_name,#sc_code').typeahead({
				displayText: function(item) {
					return item.sc_scg_emp_id + " >>  " + item.sc_name
					// $("#province").val(item.province);
				},
				source: function(query, process) {
					jQuery.ajax({
						url: "../serverside/even_sc.php", //even.php",
						data: {
							query: query
						},
						dataType: "json",
						type: "POST",
						success: function(data) {
							process(data)
							//$("#province").val(data[0].province);
						}
					})
				},
				afterSelect: function(item) {

					$("#sc_code").val(item.sc_scg_emp_id);
					$("#sc_name").val(item.sc_name);
					$("#sc_tel").val(item.sc_tel);
					$("#sc_email").val(item.sc_email);

				}

			});


			$('.phone-inputmask').inputmask("(999) 999-9999");
			// Email mask
			$('.email-inputmask').inputmask({
				mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
				greedy: false,
				onBeforePaste: function(pastedValue, opts) {
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

		function SwalDelete(tem_code) {
			Swal.fire({
				title: "Are you sure?",
				text: "คุณต้องการลบข้อมูลนี้ใช่หรือไหม่ !!!! ",
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
						document.frmdelete.tem_code.value = tem_code;
						$.ajax({
							type: 'POST',
							url: '../serverside/scmstrpost.php',
							data: $('#frmdelete').serialize(),
							//data: 'delete='+tem_code,
							//dataType: 'json'
							success: function(result) {
								var json = $.parseJSON(result);
								if (json.r == '0') {
									clearloadresult();
									Swal.fire({
										title: "Error!",
										text: json.e,
										type: "error",
										confirmButtonClass: "btn btn-danger",
										buttonsStyling: false
									});
								} else {
									clearloadresult();
									Swal.fire({
										position: "top-end",
										type: "success",
										title: "ลบเรียบร้อยค่ะ",
										showConfirmButton: false,
										timer: 1500,
										confirmButtonClass: "btn btn-primary",
										buttonsStyling: false
									});
									//$(location).attr('href', 'temmstrmntall.php?pg='+json.pg)
									window.location.reload();
								}
							},
							complete: function() {
								$("#requestOverlay").remove(); /*Remove overlay*/
							}
						})

					});
				},
				allowOutsideClick: false
			});

		}

		function readProducts() {
			$('#load-products').load('temmstrmntall.php'); //loads เฉพพราะdiv นี้
		}
	</script>

	<script language="javascript">
		function loadresult() {
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
		}

		function showdata() {
			document.frm.submit();
		}

		function gotopage(mypage) {
			document.frm.pg.value = mypage;
			document.frm.submit();
		}
		//function loadresult() {
		//	$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		//}
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