<?php 
	include("_incs/acunx_metaheader.php");
	include("_incs/acunx_cookie_var.php");
	$msg = $_REQUEST['msg'];													
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Logout --</title>
</head>

<body>

<?php		
	setcookie ("ctis_bof_user_login", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_emp_code", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_role", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_fullname", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_org_name", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_th_pos_name", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_per_area_code", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_email", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_com_code", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_user_password_resetcode", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	
	setcookie ("ctis_bof_qt_approver1", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_qt_approver2", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_pj_approver1", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_pj_approver2", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_editprice", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_auction_type", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("ctis_bof_qt_approval", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?msg=$msg\" />";	
?>
</body>
</html>
