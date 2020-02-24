<?php
	include("../_incs/acunx_metaheader.php"); 
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$errortxt = "";
	$allow_post = false;
	
	$pg = html_escape($_REQUEST['pg']);
	$action_post = html_escape($_POST['action']);
	
	if ($action_post != "") { //post มาจาก form approve sptdmnt.php
		include("../_incs/chksession.php");
		include "../_incs/acunx_csrf_var.php";
		if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
			if (!matchToken($csrf_key,$user_login)) {
				echo "System detect CSRF attack!!";
				exit;
			}
		}
		
		$action_approve = md5("qtmsaveapprove".$user_login);
		if ($action_post == $action_approve) { //post มาจาก form approve sptdmnt.php
			$approval_from = "PRG";
			//--------------------------------------- 	
			$pg = html_escape($_POST['pg']);
			$qtm_nbr = html_escape($_POST['qtm_nbr']);	
			$qtm_approve_select = html_escape($_POST['qtm_approve_select']);
			$qtm_approve_cmmt = html_escape($_POST['qtm_approve_cmmt']);
		
			//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
			$sql_qtm = "SELECT * from qtm_mstr where qtm_nbr = '$qtm_nbr' and (qtm_step_code = '20')";
			$result_qtm = sqlsrv_query($conn, $sql_qtm);	
			$r_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);
			if ($r_qtm) {
				$qtm_curprocessor = $r_qtm['qtm_curprocessor'];
				$qtm_whocanread = $r_qtm['qtm_whocanread'];
				$qtm_submit_by = $r_qtm['qtm_submit_price_by'];
				$qtm_submit_by_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$qtm_submit_by,$conn);
				$qtm_submit_by_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$qtm_submit_by,$conn);	
				$qtm_curr_step = $r_qtm['qtm_step_code'];
				
				if (inlist($qtm_curprocessor,$user_login)) {
					$allow_post = true;
				}
				else {
					//คุณไม่มีสิทธิ์อนุมัติเอกสารฉบัยนี้ค่ะ
					$r="0";
					$errortxt="<span style='color:red'>**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบัยนี้ค่ะ **</span>";
					echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"'.encrypt($qtm_nbr, $key).'","pg":"'.$pg.'"}';
					die();
				}
			}
			else {
				//เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ
				$r="0";
				$errortxt="<span style='color:red'>**เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ **</span>";
				echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"'.encrypt($qtm_nbr, $key).'","pg":"'.$pg.'"}';
				die();
				
			}
		}
		else {
			echo "System detect CSRF attack!!";
			exit;
		}	
	}
	else { //post มาจาก email
		$qtm_auth_code = mssql_escape($_POST['qtm_auth_code']);
		$qtm_approved_by = decrypt(mssql_escape($_POST['qtm_approved_by']), $dbkey);
		$qtm_nbr = decrypt(mssql_escape($_POST['qtm_nbr']), $dbkey);
		$qtm_approve_select = decrypt(mssql_escape($_POST['qtm_approve_select']), $dbkey);
		$qtm_approve_cmmt = "";
		$csrf_key = "Scgc2018";
		if (!matchToken($csrf_key,$qtm_approved_by)) {
			echo "System detect CSRF attack!!";
			exit;
		}
		if ($qtm_auth_code!="" && $qtm_nbr!="" && $qtm_approve_select!="") {
			if (inlist("10,30,890",$qtm_approve_select)) {
				//Get Current Processor
				$sql_qtm = "SELECT * from qtm_mstr where qtm_nbr = '$qtm_nbr' and (qtm_step_code = '20')";
				$result_qtm = sqlsrv_query($conn, $sql_qtm);	
				$r_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);
				if ($r_qtm) {
					//use on allowpost = true
					$qtm_whocanread = $r_qtm['qtm_whocanread'];
					$qtm_submit_by = $r_qtm['qtm_submit_price_by'];
					$qtm_submit_by_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$qtm_submit_by,$conn);
					$qtm_submit_by_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$qtm_submit_by,$conn);	
					$qtm_curr_step = $r_qtm['qtm_step_code'];
					//
					$qtm_approve_code = $r_qtm['qtm_approve_code'];
					$qtm_curprocessor = $r_qtm['qtm_curprocessor'];
					if ($qtm_approve_code == $qtm_auth_code && inlist($qtm_curprocessor,$qtm_approved_by)) {
						$allow_post = true;
						$approval_from = "MAIL";
						$user_login = $qtm_approved_by;
						
						$sql_emp = "SELECT * from emp_mstr where emp_user_id = '$user_login'";
						$result_emp = sqlsrv_query($conn, $sql_emp);	
						$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
						if ($r_emp) {
							$user_fullname = trim($r_emp["emp_th_firstname"]) . " " . trim($r_emp["emp_th_lastname"]);
							$user_email = $r_emp['emp_email_bus'];
							$user_inform_last_action = $r_emp['emp_inform_last_action'];
							if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
							else {$user_inform_last_action = false;} 
						}
						else {
							$allow_post = false;
							$r="0";
							$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
							echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"'.encrypt($qtm_nbr, $key).'","pg":"'.$pg.'"}';
							die();
						}
					}
					else {
						//คุณไม่มีสิทธิ์อนุมัติเอกสารฉบัยนี้ค่ะ
						$r="0";
						$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
						echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"'.encrypt($qtm_nbr, $key).'","pg":"'.$pg.'"}';
						die();
					}
				}
				else {
					//เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ
					$r="0";
					$errortxt="**เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ **";
					echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"","pg":""}';
					die();
				}
			}
			else {
				//คุณเลือกการอนุมัติไม่ถูกค่ะ
				$r="0";
				$errortxt="**คุณเลือกการอนุมัติไม่ถูกค่ะ **";
				echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"","pg":""}';
				die();
			}
		}
		else {
			$r="0";
			$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
			echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"","pg":""}';
			die();	
		}
	}
	if ($allow_post) {
		if ($qtm_curr_step == "20") {
			//Detect Mail Server is availbale
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			$qtm_next_step = $qtm_approve_select;
			if ($qtm_next_step == "10") { //Return for Revise
				$qtm_next_curprocessor = $qtm_submit_by;
				$qtm_next_curprocessor_email = $qtm_submit_by_email;
				$qta_text = "Return for Revised";
			}
			elseif ($qtm_next_step == "30") { //Approved
				//ADD WHO CAN READ
				if(!inlist($qtm_whocanread,$user_login)) {
					if ($qtm_whocanread != "") { $qtm_whocanread = $qtm_whocanread .","; }
					$qtm_whocanread = $qtm_whocanread . $user_login;
				}
				//Get Config from control file
				$sql = "SELECT TOP 1 * FROM sysc_ctrl where sysc_id = '1'";			
				$result_ctrl = sqlsrv_query($conn, $sql);	
				$r_result_ctrl = sqlsrv_fetch_array($result_ctrl, SQLSRV_FETCH_ASSOC);		
				if ($r_result_ctrl) {
					$qt_price_approver1 =  $r_result_ctrl['sysc_qt_price_approver1'];
					$qt_price_approver2 =  $r_result_ctrl['sysc_qt_price_approver2'];
					if ($qt_price_approver1 !="" && !is_null($qt_price_approver1)) {
						if(!inlist($qtm_whocanread,$qt_price_approver1)) {
							if ($qtm_whocanread != "") { $qtm_whocanread = $qtm_whocanread .","; }
							$qtm_whocanread = $qtm_whocanread . $qt_price_approver1;
						}
					}
					if ($qt_price_approver2 !="" && !is_null($qt_price_approver2)) {
						if(!inlist($qtm_whocanread,$qt_price_approver2)) {
							if ($qtm_whocanread != "") { $qtm_whocanread = $qtm_whocanread .","; }
							$qtm_whocanread = $qtm_whocanread . $qt_price_approver2;
						}
					}
				}
				if(!inlist($qtm_whocanread,"QT_ADMIN")) {
					if ($qtm_whocanread != "") { $qtm_whocanread = $qtm_whocanread .","; }
					$qtm_whocanread = $qtm_whocanread . "QT_ADMIN";
				}
				//
				//ADD CURRENT PROCESSOR
				$qtm_next_curprocessor = "QT_ADMIN";
				
				//ดึงรายชื่อ email ของคนที่มี role QT_ADMIN ทุกคน
				$qtm_next_curprocessor_email = "";
				$sql_aucadmin = "select role_user_login from role_mstr where role_code = 'QT_ADMIN' and role_receive_mail = 1";
				$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin);											
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['role_user_login'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
					if ($aucadmin_user_email!="") {
						if ($qtm_next_curprocessor_email != "") {$qtm_next_curprocessor_email = $qtm_next_curprocessor_email . ",";}
						$qtm_next_curprocessor_email = $qtm_next_curprocessor_email . $aucadmin_user_email;
					}
				}
				$qtap_text = "Price Approved";
			}
			else {
				$qtm_next_curprocessor = "";
				$qtm_next_curprocessor_email = $qtm_submit_by_email;
				$qtap_text = "Price Rejected";
			}
			//เก็บประวัติการดำเนินการ
			$qtap_f_step = $qtm_curr_step;
			$qtap_t_step = $qtm_next_step;
			$qtap_remark = $qtm_approve_cmmt;
			
			$sql_update_qtm = "UPDATE qtm_mstr SET " .	
			" qtm_step_code = '$qtm_next_step'," .
			" qtm_step_by = '$user_login'," .
			" qtm_step_date = '$today'," .
			" qtm_approve_price_by = '$user_login'," .
			" qtm_approve_price_date = '$today'," .
			" qtm_approve_price_cmmt = '$qtm_approve_cmmt'," .
			" qtm_whocanread = '$qtm_whocanread'," .
			" qtm_curprocessor = '$qtm_next_curprocessor'," .
			" qtm_update_by = '$user_login'," .
			" qtm_update_date = '$today'" .		
			" WHERE qtm_nbr = '$qtm_nbr'";
			$result_update_qtm = sqlsrv_query($conn,$sql_update_qtm);
			
			//Add Approval History
			$qtap_id = $qtm_nbr.get_new_qtap_approval_id($qtm_nbr,$conn);
				
			$sql = " INSERT INTO qtap_approval (" . 
			" qtap_id,qtap_qtm_nbr,qtap_f_step_code,qtap_t_step_code,qtap_text,qtap_remark,qtap_active,qtap_create_by,qtap_create_date)" .		
			" VALUES('$qtap_id','$qtm_nbr','$qtap_f_step','$qtap_t_step','$qtap_text','$qtap_remark','1','$user_login','$today')";				
			$result = sqlsrv_query($conn, $sql);
			
			
			//MAIL SECTION
			if ($can_sendmail) {
				if ($qtm_next_step == "10") {
					if ($qtm_next_curprocessor_email !="") {
						//mail next_curprocessor (Step=10 - เจ้าของเอกสาร)
						//แจ้งคนขอเบิกให้ทำการแก้ไขเอกสาร
						$mail_from = "CTIS Admin";
						$mail_from_email = "ctis_admin@scg.com";
						$mail_to = $qtm_next_curprocessor_email;
						$mail_subject = "[CTIS] - Quotation หมายเลข $qtm_nbr รอท่านทำการแก้ไขค่ะ";
						$mail_message = "เรียน คุณ$qtm_submit_by_name,<br><br>" .
							"Quotation หมายเลข $qtm_nbr ถูกส่งกลับมาให้ทำการแก้ไขตามหมายเหตุด้านล่างค่ะ<br>" .
							"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
							" ขอบคุณค่ะ<br>";	
						$mail_message .= $mail_no_reply;
						
						if ($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
					}
					if ($user_inform_last_action) {
						if ($user_email!="") {
							//mail inform last action
							$mail_from = "CTIS Admin";
							$mail_from_email = "ctis_admin@scg.com";
							$mail_to = $user_email;
							$mail_subject = "[CTIS] - คุณได้ส่ง Quotation หมายเลข $qtm_nbr กลับไปให้ผู้สร้างทำการแก้ไข";
							$mail_message = "เรียน คุณ$user_fullname,<br><br>" .
								"คุณได้ส่ง Quotation หมายเลข $qtm_nbr กลับไปให้ผู้สร้างทำการแก้ไขตามหมายเหตุด้านล่างค่ะ<br>" .
								"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
								" ขอบคุณค่ะ<br>";	
							$mail_message .= $mail_no_reply;
							
							if ($mail_to!="") {
								$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
								if (!$sendstatus) {
									$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้<br>";
								}
							} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้<br>";}
						}
					}
				}
				if ($qtm_next_step == "800") {
					if ($qtm_next_curprocessor_email !="") {
						//mail next_curprocessor (Step=890 - เจ้าของเอกสาร)
						//แจ้งคนขอเบิกว่าเอกสารไม่ได้รับการอนุมติ
						$mail_from = "CTIS Admin";
						$mail_from_email = "ctis_admin@scg.com";
						$mail_to = $qtm_next_curprocessor_email;
						$mail_subject = "[CTIS] - Quotation หมายเลข $qtm_nbr ไม่ได้รับการอนุมัติค่ะ";
						$mail_message = "เรียน คุณ$qtm_submit_by_name,<br><br>" .
							"Quotation หมายเลข $qtm_nbr ไม่ได้รับการอนุมัติค่ะ<br>" .
							"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
							" ขอบคุณค่ะ<br>";	
						$mail_message .= $mail_no_reply;
						
						if ($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
					}
					if ($user_inform_last_action) {
						if ($user_email!="") {
							$mail_from = "CTIS Admin";
							$mail_from_email = "ctis_admin@scg.com";
							$mail_to = $user_email;
							$mail_subject = "[CTIS] - คุณไม่อนุมัติ Quotation หมายเลข $qtm_nbr ค่ะ";
							$mail_message = "เรียน คุณ$user_fullname,<br><br>" .
								"คุณไม่อนุมัติ Quotation หมายเลข $qtm_nbr<br>" .
								"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
								" ขอบคุณค่ะ<br>";	
							$mail_message .= $mail_no_reply;
							
							if ($mail_to!="") {
								$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
								if (!$sendstatus) {
									$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
								}
							} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
						}
					}
				}
				if ($qtm_next_step == "30") {
					if ($qtm_submit_by !="") {
						//ระบบจะแจ้งผลการอนุมัติให้ผู้สร้าง Quotation
						$qtm_submit_by_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$qtm_submit_by,$conn);
						$mail_from = "CTIS Admin";
						$mail_from_email = "ctis_admin@scg.com";
						$mail_to = $qtm_submit_by_email;
						$mail_subject = "[CTIS]- Quotation หมายเลข $qtm_nbr ได้รับการอนุมัติแล้วค่ะ";
						$mail_message = "เรียน คุณ$qtm_submit_by_name,<br><br>" .
							"Quotation หมายเลข $qtm_nbr ได้รับการอนุมัติแล้วค่ะ<br>" .
							"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
							" ขอบคุณค่ะ<br>";	
						$mail_message .= $mail_no_reply;
						
						if ($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
					}
					if ($user_inform_last_action) {
						//แจ้งคนอนุมัติว่าได้ทำอะไรไป
						//แต่ถ้ากำหนดว่าไม่ต้องแจ้งระบบก็จะไม่ส่ง mail ไปบอก
						if ($user_email!="") {
							//mail inform last action
							$mail_from = "CTIS Admin";
							$mail_from_email = "ctis_admin@scg.com";
							$mail_to = $user_email;
							$mail_subject = "[CTIS]- คุณได้อนุมัติ Quotation หมายเลข $qtm_nbr ค่ะ";
							$mail_message = "เรียน คุณ$user_fullname,<br><br>" .
								"คุณได้อนุมัติ Quotation หมายเลข $qtm_nbr ค่ะ<br>" .
								"<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$qtm_approve_cmmt . "</span><br><br>" .
								" ขอบคุณค่ะ<br>";	
							$mail_message .= $mail_no_reply;
							
							if ($mail_to!="") {
								$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
								if (!$sendstatus) {
									$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้<br>";
								}
							} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้<br>";}
						}
					}
				}
			}
			
			$r="1";
			echo '{"res":"'.$r.'","err":"'.$errortxt.'","nbr":"'.encrypt($qtm_nbr, $key).'","pg":"'.$pg.'"}';
		}
	}
?>