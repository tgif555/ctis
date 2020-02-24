<?php
include("../_incs/funcServer.php");	
	echo is_authorize("wsexecute","338","337")[2];
	function is_authorize($p_access_code,$p_wsu_username,$p_wsu_password) {
		$today = date("Y-m-d H:i:s");
		$conn = connectDB();
		if ($conn) {
			$wsauth = accessWS($p_access_code,$conn);
			$results = array();
			if ($wsauth) {	
				if ($p_wsu_username != "") {
					$sql_wsu = "select * from wsu_map where wsu_username = '$p_wsu_username'";						
					$result_wsu = sqlsrv_query($conn,$sql_wsu);
					$row_wsu = sqlsrv_fetch_array($result_wsu, SQLSRV_FETCH_ASSOC);
					if ($row_wsu) {
						$wsu_password = trim($row_wsu['wsu_password']);
						$wsu_aduser = strtoupper(trim($row_wsu['wsu_aduser']));
						if ($wsu_password == trim($p_wsu_password)) {
							$results[0] = "1";
							$results[1] = $wsu_aduser;
							$results[2] = "ยินดีต้อนรับ คุญ$$wsu_aduser เข้าใช้งานค่ะ";
						}
						else {
							$results[0] = "0";
							$results[1] = "";
							$results[2] = "Password ไม่ถูกค่ะ";
						}
					}
					else {
						$results[0] = "0";
						$results[1] = "";
						$results[2] = "ไม่พบรหัสผู้เข้าใช้นี้ในระบบค่ะ";
					}
				}
				else {
					$results[0] = "0";
					$results[1] = "";
					$results[2] = "กรุณาระบุรหัสผู้เข้าใช้";
				}
			}
			else {
				$results[0] = "0";
				$results[1] = "";
				$results[2] = "คุณไม่มีสิทธิ์เข้าใช้งานค่ะ";
			}
		}
		else {
			$results[0] = "0";
			$results[1] = "";
			$results[2] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้ค่ะ";
		}
		return $results;
		exit();		
	}
	function accessWS($p_access_code,$conn) {
		$sql = "select count(*) as access_allow from wsa_mstr where wsa_access_code = '$p_access_code'";
		$result_exec = sqlsrv_query($conn,$sql);
		$row_auth = sqlsrv_fetch_array($result_exec, SQLSRV_FETCH_ASSOC);
		if ($row_auth['access_allow'] > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	function connectDB() {//ฟังก์ชั่นเชื่อมต่อกับฐานข้อมูล
		$dbserver = "L0650EITKOMSUNJ\SQLEXPRESS";
		$dbname = "Sampletiles2019";
		$dbuser = "sa";
		$dbpwd = "root";
		$connectionInfo = array('Database' => $dbname ,"UID" => $dbuser, "PWD" => $dbpwd, "CharacterSet"  => 'UTF-8');
		$conn = sqlsrv_connect($dbserver, $connectionInfo);

		if(!$conn){
			echo"error connection";
			die(print_r(sqlsrv_errors(),true));	
		}
		else {
			return $conn;
		}
	}
	function closeDB($connection) {//ฟังก์ชั่นปิดการเชื่อมต่อกับฐานข้อมูล
		sqlsrv_close($connection);
	}
?>