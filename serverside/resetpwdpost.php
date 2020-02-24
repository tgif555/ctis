<?php	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");
	
	date_default_timezone_set('Asia/Bangkok');		
	$today = date("Y-m-d H:i:s");  	
	$pg = $_REQUEST['pg'];
	$action = $_POST['action'];
	
	if ($action == "resetpwd") {		
		$emp_user_id = strtoupper(trim($_POST['user_login'])) ;
		$emp_birth_date = str_replace("/",".",trim($_POST['birth_date']));
		$emp_birth_date = md5($emp_birth_date);
		$emp_card_id = md5(trim($_POST['card_id']));	
		$your_reset_code = trim($_POST['your_reset_code']);		
		if ($your_reset_code != "") { $your_reset_code = encrypt($your_reset_code, $emp_user_id); }
				
		$sql = "select emp_th_firstname + ' ' + emp_th_lastname as emp_fullname,emp_email_bus,emp_user_password_resetcode from emp_mstr where emp_user_id = '$emp_user_id' and emp_birth_date = '$emp_birth_date' and emp_card_id = '$emp_card_id'";
		// echo $emp_birth_date . "<br>";
		// echo $emp_card_id . "<br>";
		// echo $sql . "<br>";
		// die();
		
		$result = sqlsrv_query($conn, $sql);		
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if (!$row) {
			//msg คสามยาวของ password ต้องไม่น้อยกว่า 8
			$path = "../pwdreset.php?msg=<font color=red>ข้อมูลที่ระบุไม่ถูกต้อง กรุณาระบุข้อมูลใหม่อีกครั้งค่ะ</font>"; 				
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
		}
		else {
			$emp_fullname = trim($row["emp_fullname"]);
			$emp_email_bus = trim($row["emp_email_bus"]);
			$emp_user_password_resetcode = trim($row["emp_user_password_resetcode"]);
			if (is_null($emp_user_password_resetcode)) { $emp_user_password_resetcode = ""; }
			
			// if ($your_reset_code != $emp_user_password_resetcode) { 
				// $path = "../pwdreset.php?msg=<font color=red>Reset Code ไม่ถูกต้อง</font>"; 				
				// echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			// }
			// else {						
				$newpwd = rand_str(8);					
				$emp_user_password = md5($emp_user_id."+".$newpwd);
				
				$sql = "UPDATE emp_mstr SET ".		
				"emp_user_password='$emp_user_password'," .
				"emp_user_password_date = '$today'," .
				"emp_user_password_by = '$emp_user_id'," .
				"emp_user_password_change_next_signon = '1'" .
				" WHERE emp_user_id = '$emp_user_id'";						
				$result = sqlsrv_query($conn,$sql);	
					
				
				$path = "../pwdreset.php?msg=Password ใหม่ของคุณคือ <b>$newpwd</b>"; 
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			//}
		}		
	}
?>