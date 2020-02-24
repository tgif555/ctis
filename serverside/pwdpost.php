<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s");  	
	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	
	if ($action == "chgpwd") {		
		$user_login = strtoupper(mssql_escape($_POST['user_login'])) ;
		$old_user_password = mssql_escape($_POST['old_user_password']);
		$new_user_password = mssql_escape($_POST['new_user_password']);
		$new_user_password1 = mssql_escape($_POST['new_user_password1']);
				
		$old_user_password_md5 = md5($user_login."+".$old_user_password);
					
		// $sql = "select emp_user_password from emp_mstr where emp_user_id = '$user_login'";
		// $result = sqlsrv_query($conn, $sql);		
		$params = array($user_login);
		$sql = "select emp_user_password from emp_mstr where emp_user_id = ?";
		$result = sqlsrv_query($conn, $sql,$params);
		
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if (!$row) {
			//msg user not found
		}
		else {
			$db_old_user_password = $row["emp_user_password"];
			
			if ($old_user_password_md5 != $db_old_user_password) {
				//msg คสามยาวของ password ต้องไม่น้อยกว่า 8
				$path = "../masmnt/pwdmnt.php?user_login=$uer_login&msg=<font color=red>ระบุ Password เก่าไม่ถูกต้อง</font>"; 				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";	
			}
			else {
				if ($new_user_password != $new_user_password1) {
					//msg คสามยาวของ password ต้องไม่น้อยกว่า 8
					$path = "../masmnt/pwdmnt.php?user_login=$user_login&msg=<font color=red>ระบุ Password ใหม่ไม่เหมือนกัน</font>"; 
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";//msg new password not match
				}
				else {
					if (strlen($new_user_password) < $lengthpwd) {
						//msg คสามยาวของ password ต้องไม่น้อยกว่า 8
						$path = "../masmnt/pwdmnt.php?user_login=$user_login&msg=<font color=red>ความยาวของ Password ต้องมากกว่าหรือเท่ากับ $lengthpwd</font>"; 
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";   
					}
					else {
						$new_user_password_md5 = md5($user_login."+".$new_user_password);
						// $sql = "UPDATE emp_mstr SET ".		
						// "emp_user_password='$new_user_password_md5'," .
						// "emp_user_password_date = '$today'," .
						// "emp_user_password_by = '$user_login'," .
						// "emp_user_password_change_next_signon = '0'" .
						// " WHERE emp_user_id = '$user_login'";								
						// $result = sqlsrv_query($conn,$sql);		
						$params = array($user_login);
						$sql = "UPDATE emp_mstr SET ".		
						"emp_user_password='$new_user_password_md5'," .
						"emp_user_password_date = '$today'," .
						"emp_user_password_by = '$user_login'," .
						"emp_user_password_change_next_signon = '0'" .
						" WHERE emp_user_id = ?";								
						$result = sqlsrv_query($conn,$sql,$params);
						$path = "../logout.php?msg=Password was change successed"; 
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
					}
				}
			}
		}
	}	
	if ($action == "chgresetcode") {	
		$user_login = strtoupper(mssql_escape($_POST['user_login'])) ;
		$your_password = mssql_escape($_POST['your_password']);
		$your_reset_code = mssql_escape($_POST['your_reset_code']);
						
		$your_password_md5 = md5($user_login."+".$your_password);
					
		// $sql = "select emp_user_password from emp_mstr where emp_user_id = '$user_login'";
		// $result = sqlsrv_query($conn, $sql);
		$params = array($user_login);
		$sql = "select emp_user_password from emp_mstr where emp_user_id = ?";
		$result = sqlsrv_query($conn, $sql, $params);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if (!$row) {
			$path = "../masmnt/resetcodemnt.php?msg=<font color=red>ไม่พบ User ที่ต้องการ Change Reset Code</font>"; 				
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";	
		}
		else {
			$db_emp_user_password = $row["emp_user_password"];
			if ($your_password_md5 != $db_emp_user_password) {
				//ระบุ Password ไม่ถูกต้อง
				$path = "../masmnt/resetcodemnt.php?msg=<font color=red>ระบุ Password เก่าไม่ถูกต้อง</font>"; 				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";	
			}
			else {				
				$en_your_reset_code = encrypt($your_reset_code, $user_login);							
				// $sql = "UPDATE emp_mstr SET ".		
				// "emp_user_password_resetcode='$en_your_reset_code'" .				
				// " WHERE emp_user_id = '$user_login'";								
				// $result = sqlsrv_query($conn,$sql);		
				$params = array($user_login);
				$sql = "UPDATE emp_mstr SET ".		
				"emp_user_password_resetcode='$en_your_reset_code'" .				
				" WHERE emp_user_id = ?";								
				$result = sqlsrv_query($conn,$sql,$params);
				$path = "../masmnt/resetcodemnt.php?msg=<font color=green>Change Reset Code Success</font>"; 
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			}
		}
	}
?>