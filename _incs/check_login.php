<?php
include("config.php");
include("funcServer.php");
include("acunx_cookie_var.php");
include("showloading.php");

$user_login = strtoupper($_POST['user_login']);
$user_enter_password = $_POST['user_passwd'];
$auth = decrypt($_POST['auth'], $dbkey); //return value is approver

$user_enter_encrypt_password = md5($user_login."+".$user_enter_password);

$params = array($user_login);
$sql = "SELECT * FROM emp_mstr WHERE emp_user_id = ?";
$result = sqlsrv_query($conn, $sql,$params);
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
if (!$row) {
	$path = "../index.php?doc=".$_POST['qtm_nbr']."&auth=".$_POST['auth']."&msg=<font color=red>User not found!!</font>";
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
} 
else {	
	$user_scg_emp_id = $row["emp_scg_emp_id"];
	$db_user_password = $row["emp_user_password"];
	$user_fullname = trim($row["emp_th_firstname"]) . " " . trim($row["emp_th_lastname"]);
	$user_org_name = trim($row["emp_th_div"]) . "/" . trim($row["emp_th_dept"]) . "/" . trim($row["emp_th_sec"]);
	$user_status = $row["emp_status_code"]; //-> 3 = active,0=denine
	$user_th_pos_name = $row["emp_th_pos_name"];
	$user_email = $row["emp_email_bus"];
	
	$user_com_code = $row["emp_com_code"];
	$user_password_date = $row["emp_user_password_date"];
	$user_password_change_next_signon = $row["emp_user_password_change_next_signon"];
	$user_password_ldap = $row["emp_user_password_ldap"];
	$user_password_resetcode = $row["emp_user_password_resetcode"];
	if (is_null($user_password_resetcode)) {$user_password_resetcode = "";}
	
	if ($row["emp_inform_last_action"] == '1') {$user_inform_last_action = true;}
	else {$user_inform_last_action = false;}
	
	if ($user_password_ldap) { $user_use_password_from = "LDAP"; }
	else { $user_use_password_from = "LOCAL"; }
	
	$allow_access = false;
	if ($user_use_password_from == "LOCAL") {
		if ($db_user_password != $user_enter_encrypt_password) {				
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=(LOCAL PWD) Invalid Username or Password!!\" />";			
		}
		else {
			if ($user_status != '3') {
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=(LOCAL PWD) User not active\" />";
			}
			else {
				if ($user_password_date == "") {
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=(LOCAL PWD) Contact admin for reset your password!!\" />";
				}
				else {
					//check เรื่องอายุและ change pass at next logon
					$datetime1 = new DateTime(date_format($user_password_date,"Y-m-d"));														
					$datetime2 = new DateTime(date("Y-m-d"));														             														
					$user_password_date = date_format($user_password_date,"d/m/Y H:i:s");
					$user_password_age = $datetime1->diff($datetime2)->d;
					if ($user_password_change_next_signon == true || $user_password_age > $maxagepwd) {
						if ($user_password_age > $maxagepwd) {
							$msg = "คุณต้องเปลี่ยนรหัสผ่าน เนื่องจากรหัสผ่านของคุณมีอายุมากกว่า $maxagepwd วันค่ะ";
						}
						else {
							//$msg = "คุณต้องเปลี่ยนรหัสผ่าน เนื่องจากรหัสผ่านของคุณถูก RESET จากผู้ดูแลระบบ";
							$msg = "มีการ RESET Password คุณต้องเปลี่ยนรหัสผ่านใหม่ค่ะ ";
						}
						
						setcookie("ctis_bof_user_login", $user_login,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
						setcookie("ctis_bof_user_fullname", $user_login,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);				
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=../masmnt/pwdmnt.php?user_login=$user_login&msg=$msg\" />";	
					}
					else {
						$allow_access = true;
					}
				}	
			}		
		}	
	}
	else {
		if (trim($user_enter_password) != "") { 
			/**
			การใช้งานในอนาคตต้องลง extionsion ldap ที่ server และต้องเช็ควิธีการ connect ldap scg อีกครั้ง
			เปิดใช้งาน LDAP แล้วแต่จะมีผลกับคนที่ใช้ password LDAP เท่านั้น
			**/
			$aduser = 'CEMENTHAI' . "\\" . $user_login;
			//$server = 'ldap://172.30.53.91'; //Ip นี้ก็ใช้ได้แต่ใช้ชื่อจะดีกว่า
			$server = 'cementhai.com';
			$ldap = ldap_connect($server);
			if(!$ldap) {
				die("Not connect to LDAP ".$server);
				echo "Not connect Ldap server ";
				exit();
			} else {	
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				$bind = @ldap_bind($ldap, $aduser, $user_enter_password);		
				if(!$bind) {			
					$errors = "(".ldap_errno($ldap).") " . ldap_error($ldap); 
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=(LDAP) $errors\" />";
				} 
				else {
					$allow_access = true;
					ldap_unbind($ldap);
				}
			}
		} else {
			$errors = "กรุณาระบุ Password ค่ะ"; 
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=(LDAP) $errors\" />";
		}
	}
	if ($allow_access) {					
		$sql = "SELECT TOP 1 * FROM sysc_ctrl";			
		$result_ctrl = sqlsrv_query($conn, $sql);	
		$r_result_ctrl = sqlsrv_fetch_array($result_ctrl, SQLSRV_FETCH_ASSOC);		
		if ($r_result_ctrl) {
			$sysc_com_code = trim($r_result_ctrl["sysc_com_code"]);
			$sysc_com_name = trim($r_result_ctrl["sysc_com_name"]);
			$sysc_com_address = trim($r_result_ctrl["sysc_com_address"]);
			$sysc_com_tel = trim($r_result_ctrl["sysc_com_tel"]);
			$sysc_com_fax = trim($r_result_ctrl["sysc_com_fax"]);
			$sysc_com_email = trim($r_result_ctrl["sysc_com_email"]);
			$sysc_com_lineid = trim($r_result_ctrl["sysc_com_lineid"]);
			$sysc_com_taxid = trim($r_result_ctrl["sysc_com_taxid"]);
			$sysc_qt_price_approver1 = trim($r_result_ctrl["sysc_qt_price_approver1"]);
			$sysc_qt_price_approver2 = trim($r_result_ctrl["sysc_qt_price_approver2"]);
			$sysc_qt_final_approver1 = trim($r_result_ctrl["sysc_qt_final_approver1"]);
			$sysc_qt_final_approver2 = trim($r_result_ctrl["sysc_qt_final_approver2"]);
			$sysc_pj_approver1 = trim($r_result_ctrl["sysc_pj_approver1"]);
			$sysc_pj_approver2 = trim($r_result_ctrl["sysc_pj_approver2"]);
			$sysc_editprice = trim($r_result_ctrl["sysc_editprice"]);
			$sysc_auction_type = trim($r_result_ctrl["sysc_auction_type"]);
			$sysc_qt_approval = trim($r_result_ctrl["sysc_qt_approval"]);
			$sysc_inform_approved_to_aucadmin = trim($r_result_ctrl["sysc_inform_approved_to_aucadmin"]);
		}
		else {
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php?msg=<font color=red>ไม่พบ Control File กรุณาติดต่อ System Administrator!!</font>\" />";			
		}		
		
		$user_role = "";										
		$sql = "SELECT role_code FROM role_mstr WHERE role_user_login='$user_login'";
		$result = sqlsrv_query($conn, $sql);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			if ($user_role != "") { $user_role = $user_role . ",";}
			$user_role = $user_role . $row['role_code'];
		}
		if ($user_role == "") { $user_role = "NORMAL_USER"; }
		
		$user_home = "../cisbof/qtmall.php";

		$expire=0;
		
		setcookie ("ctis_bof_user_login", $user_login,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_emp_code", $user_scg_emp_id,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_role", $user_role,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_fullname", $user_fullname,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_org_name", $user_org_name,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_th_pos_name", $user_th_pos_name,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_per_area_code", $user_per_area_code,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_email", $user_email,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_com_code", $sysc_com_code,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_password_resetcode", $user_password_resetcode,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_user_inform_last_action", $user_inform_last_action,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		
		setcookie ("ctis_bof_qt_price_approver1", $sysc_qt_price_approver1,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_qt_price_approver2", $sysc_qt_price_approver2,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_qt_final_approver1", $sysc_qt_final_approver1,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_qt_final_approver2", $sysc_qt_final_approver2,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_pj_approver1", $sysc_pj_approver1,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_pj_approver2", $sysc_pj_approver2,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_editprice", $sysc_editprice,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_auction_type", $sysc_auction_type,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_qt_approval", $sysc_qt_approval,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie ("ctis_bof_inform_approved_to_aucadmin", $sysc_inform_approved_to_aucadmin,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		
		//เช็คว่าเปิดเอกสารมาจาก email ใช่หรือไม่
		$qtm_nbr = $_POST['qtm_nbr'];
		if ($qtm_nbr=="") {
			//เป็นการ login มาจากหน้า login
			$path = $user_home;
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
		}
		else {
			//เป็นการ login มาจาก email
			if ($user_login == $auth) {
				$qtm_nbr = decrypt($_POST['qtm_nbr'], $auth);
				$params = array($qtm_nbr);
				$sql = "SELECT * from qtm_mstr where qtm_nbr = ? and qtm_is_delete = 0 and qtm_step_code='20'";
				$result = sqlsrv_query($conn, $sql,$params);	
				$r_qtm = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
				if ($r_qtm) {
					$qtm_curprocessor = $r_qtm['qtm_curprocessor'];
					if (inlist($qtm_curprocessor,$user_login)) {
						$path = "../cisbof/qtdmnt.php?qtmnumber=".encrypt($qtm_nbr, $dbkey);
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
					}
					else {
						$path = "../index.php?doc=".$_POST['qtm_nbr']."&auth=".$_POST['auth']."&msg=<font color=red>คุณไม่มีสิทธืเข้าถึงเอกสารหมายเลขนี้!!";
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
					}
				}
				else {
					$path = "../index.php?doc=".$_POST['qtm_nbr']."&auth=".$_POST['auth']."&msg=<font color=red>ไม่พบเอกสารในสถานะรออนุมัติค่ะ!!</font>";
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
				}
			}
			else {
				$path = "../index.php?doc=".$_POST['qtm_nbr']."&auth=".$_POST['auth']."&msg=<font color=red>ท่านไม่ใช้ผู้อนุมัติเอกสารฉบับนี้!!</font>";
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			}
		}
	}	
}
?>