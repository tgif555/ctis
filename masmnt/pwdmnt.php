<?php
include("../_incs/acunx_metaheader.php"); 
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");	
$msg=html_escape($_REQUEST['msg']);
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
    <title>Password Maintenance</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
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
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<!-- END: Head-->
<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

	<div id="result"></div>
    <?php include("../cismain/menu_header.php"); ?>	
	<?php include("../cismain/menu_leftsidebar.php"); ?>
	
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            
            <div class="content-body">
                <!-- File export table -->
                <section id="file-export">
					<div class="row">
						<div class="col-3"></div>
                        <div class="col-6">
                            <div class="card center">
                                <div class="card-header border-0 pb-0">
                                    <div class="card-title text-center">
                                        <img src="../theme/app-assets/images/logo/stack-logo-dark.png" alt="branding logo">
                                    </div>
                                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
										<span>
											Change Your Password<br>
											<?php if ($msg != "") { ?>
												<font color=red>***** <?php echo $msg; ?> *****</font><br>
											<?php }?>
										</span>
									</h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
										<form name="frm_chgpwd" autocomplete=OFF method="post" action="../serverside/pwdpost.php" class="form-horizontal" novalidate>						
											<input type="hidden" name="user_login" value="<?php echo $user_login?>">
											<input type="hidden" name="action" value="chgpwd">
											<fieldset class="form-group position-relative has-icon-left mb-0">
												
												<input type="password" name="old_user_password" id="old_user_password" class="form-control form-control-lg red" placeholder="Old Password">												
												<div class="form-control-position">
                                                    <i class="fa fa-key"></i>
                                                </div>
											</fieldset>	
											<fieldset class="form-group position-relative has-icon-left mb-0">
												<input type="password" name="new_user_password" id="new_user_password" class="form-control form-control-lg blue" placeholder="New Password">												
												<div class="form-control-position">
                                                    <i class="fa fa-key"></i>
                                                </div>
											</fieldset>
											<fieldset class="form-group position-relative has-icon-left mb-0">	
												<input type="password" name="new_user_password1" id="new_user_password1" class="form-control form-control-lg blue" placeholder="Confirm New Password">
												<div class="form-control-position">
                                                    <i class="fa fa-key"></i>
                                                </div>
											</fieldset>
                                            <button type="button" onclick="chgpwdpostform()" class="btn btn-outline-primary btn-lg btn-block"><i class="feather icon-unlock"></i> Save changes</button>
                                        </form>
                                    </div>
                                </div>
                                <!--div class="card-footer border-0">
                                    <p class="float-sm-left text-center"><a href="login-simple.html" class="card-link">Login</a></p>
                                    <p class="float-sm-right text-center">New to Stack ? <a href="register-simple.html" class="card-link">Create Account</a></p>
                                </div-->
                            </div>
                        </div>
						<div class="col-3"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/forms/form-login-register.js"></script>
    <!-- END: Page JS-->
	
	<script language="javascript">
			function chgpwdpostform() {								
				var errorflag = false;
				var errortxt = "";
				
				var user_login = document.frm_chgpwd.user_login.value;				
				var old_user_password = document.frm_chgpwd.old_user_password.value;
				var new_user_password = document.frm_chgpwd.new_user_password.value;
				var new_user_password1 = document.frm_chgpwd.new_user_password1.value;
				
				if (user_login=="") {
					if (errortxt!="") {errortxt = errortxt + "\n";}
					errorflag = true;
					errortxt = errortxt + "กรุณา Login ก่อนค่ะ";				
				}
				if (old_user_password=="") {
					if (errortxt!="") {errortxt = errortxt + "\n";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ Password เดิม";				
				}
				if (new_user_password=="") {
					if (errortxt!="") {errortxt = errortxt + "\n";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ Password ใหม่";				
				}
				if (new_user_password1=="") {
					if (errortxt!="") {errortxt = errortxt + "\n";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ Password ใหม่อีกครั้ง";				
				}
				if (new_user_password != new_user_password1) {
					if (errortxt!="") {errortxt = errortxt + "\n";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ Password ใหม่ให้ตรงกัน";				
				}
				
				if (errorflag ) {							
					alert(errortxt);
				}
				else {	
					if(confirm('ท่านยืนยันการ Change Password ไช่หรือไม่ ?')) {						
						document.frm_chgpwd.submit();
					}					
				}								
			}
		</script>

</body>
<!-- END: Body-->

</html>