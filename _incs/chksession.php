<?php	
	$user_login = html_escape0($_COOKIE['ctis_bof_user_login']);
	$user_scg_emp_id = html_escape0($_COOKIE['ctis_bof_user_emp_code']);
	$user_role = html_escape0($_COOKIE['ctis_bof_user_role']);
	$user_fullname = html_escape0($_COOKIE['ctis_bof_user_fullname']);
	$user_org_name = html_escape0($_COOKIE['ctis_bof_user_org_name']);
	$user_th_pos_name = html_escape0($_COOKIE['ctis_bof_user_th_pos_name']);	
	$user_per_area_code = html_escape0($_COOKIE['ctis_bof_user_per_area_code']);
	$user_email = html_escape0($_COOKIE['ctis_bof_user_email']);
	$user_password_resetcode = html_escape0($_COOKIE['ctis_bof_user_password_resetcode']);
	$user_inform_last_action = html_escape0($_COOKIE['ctis_bof_user_inform_last_action']);
	
	//Control File
	$gbv_com_code = html_escape0($_COOKIE['ctis_bof_com_code']);
	$gbv_qt_price_approver1 =  html_escape0($_COOKIE['ctis_bof_qt_price_approver1']);
	$gbv_qt_price_approver2 =  html_escape0($_COOKIE['ctis_bof_qt_price_approver2']);
	$gbv_qt_final_approver1 =  html_escape0($_COOKIE['ctis_bof_qt_final_approver1']);
	$gbv_qt_final_approver2 =  html_escape0($_COOKIE['ctis_bof_qt_final_approver2']);
	$gbv_pj_approver1 =  html_escape0($_COOKIE['ctis_bof_pj_approver1']);
	$gbv_pj_approver2 =  html_escape0($_COOKIE['ctis_bof_pj_approver2']);
	$gbv_editprice =  html_escape0($_COOKIE['ctis_bof_editprice']);
	$gbv_auction_type = html_escape0($_COOKIE['ctis_bof_auction_type']);
	$gbv_qt_approval =  html_escape0($_COOKIE['ctis_bof_qt_approval']);
	$gbv_inform_approved_to_aucadmin =  html_escape0($_COOKIE['ctis_bof_inform_approved_to_aucadmin']);

	if (!isset($_COOKIE['ctis_bof_user_login']) || $_COOKIE['ctis_bof_user_login'] == "") {	
		echo "System detect CSRF attack!!";
		exit;
	}		

	function html_escape0($value) {
		$v = $value;
		if (is_null($v) || $v == "") {
			return "";
		}
		else {
			return htmlspecialchars($v,ENT_QUOTES);
		}
	}

?>