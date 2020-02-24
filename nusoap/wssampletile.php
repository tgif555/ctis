<?php
//include required class for build nnusoap web service server
	require_once('lib/nusoap.php');
	include("../_incs/funcServer.php");	
	$today = date("Y-m-d H:i:s"); 
  
	// Create server object
	$server = new soap_server();

	// configure  WSDL
	$server->configureWSDL('adjstock', 'urn:adjstockwsdl');
	$server->soap_defencoding = "utf-8";
	$server->decode_utf8 = false;
	
	//1.Parameter For rec_stock_pcs
	$input_rec = array(
		'p_access_code' => "xsd:string",
		'p_rec_by' => "xsd:string",
		'p_tran_type' => "xsd:string",
		'p_tran_remark' => "xsd:string",
		'p_mat_code' => "xsd:string",
		'p_mat_unit' => "xsd:string",
		'p_qty' => "xsd:string"
	);
    $server->register('rec_stock_pcs',			// method
        $input_rec,                 		// input parameters
        array('return' => 'tns:ArrayOfString') 			// documentation
    );
	//2.Parameter For iss_stock_pcs
	$input_iss = array(
		'p_access_code' => "xsd:string",
		'p_iss_by' => "xsd:string",
		'p_iss_type' => "xsd:string",
		'p_iss_remark' => "xsd:string",
		'p_mat_code' => "xsd:string",
		'p_mat_unit' => "xsd:string",
		'p_iss_qty' => "xsd:string"
	);
    $server->register('iss_stock_pcs',			// method
        $input_iss,                 		// input parameters
        array('return' => 'tns:ArrayOfString') 			// documentation
    );
	//3.Parameter For get_mat_info
	$input_get_mat_info = array(
		'p_access_code' => "xsd:string",
		'p_mat_code' => "xsd:string"
	);
    $server->register('get_mat_info',			// method
        $input_get_mat_info,                 		// input parameters
        array('return' => 'tns:ArrayOfString') 			// documentation
    );
	//4.Parameter For is_authorize
	$input_is_authorize = array(
		'p_access_code' => "xsd:string",
		'p_wsu_username' => "xsd:string",
		'p_wsu_password' => "xsd:string"
	);
    $server->register('is_authorize',			// method
        $input_is_authorize,                 		// input parameters
        array('return' => 'tns:ArrayOfString') 			// documentation
    );
	//5.Parameter For get_stock_reason
	$input_get_stock_reason = array(
		'p_access_code' => "xsd:string"
	);
	
    $server->register('get_stock_reason',			// method
        $input_get_stock_reason,                 		// input parameters
        array('return' => 'tns:ArrayOfString') 			// documentation
    );
	
	//Add ComplexType with Array for output
	$server->wsdl->addComplexType("ArrayOfString", 
		"complexType", 
		"array", 
		"", 
		"SOAP-ENC:Array", 
		array(), 
		array(array("ref"=>"SOAP-ENC:arrayType","wsdl:arrayType"=>"xsd:string[]")), 
		"xsd:string"
	);
	//
	
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
							$wsu_aduser_fullname = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$wsu_aduser,$conn);
							$results[0] = "1";
							$results[1] = $wsu_aduser;
							$results[2] = "ยินดีต้อนรับ คุญ".$wsu_aduser_fullname." เข้าใช้งานค่ะ";
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
	function get_stock_reason($p_access_code) {
		$today = date("Y-m-d H:i:s");
		$conn = connectDB();
		if ($conn) {
			$wsauth = accessWS($p_access_code,$conn);
			$results = array();
			if ($wsauth) {
				$i=0;
				$sql_stkr = "select * from stkr_mstr order by stkr_id";											
				$result_stkr = sqlsrv_query($conn,$sql_stkr);
				while($row_stkr = sqlsrv_fetch_array($result_stkr, SQLSRV_FETCH_ASSOC)) {
					$results[$i] = $row_stkr['stkr_text'];
					$i++;
				}
			}
			closeDB($conn); //ปิดการเชื่อมต่อกับฐานข้อมูล
		}
		else {
			$results[0] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้ค่ะ";
		}
		return $results;
		exit();
	}
	function get_mat_info($p_access_code,$p_mat_code) {
		$today = date("Y-m-d H:i:s");
		$conn = connectDB();
		if ($conn) {
			$wsauth = accessWS($p_access_code,$conn);
			$results = array();
			if ($wsauth) {
				$sql_mat = "select * from material where mat_code = '$p_mat_code'";											
				$result_mat = sqlsrv_query($conn,$sql_mat);
				$row_mat = sqlsrv_fetch_array($result_mat, SQLSRV_FETCH_ASSOC);
				if ($row_mat) {
					$mat_pcs_per_box = $row_mat['mat_pcs_per_box'];
					$mat_um_conv = $row_mat['mat_um_conv'];
					$mat_spt_group = $row_mat['mat_spt_group'];
					$mat_qty_oh = findsqlval("stkm_mstr","stkm_qty_oh","stkm_mat_code",$p_mat_code,$conn);
					$mat_location = findsqlval("stkm_mstr","stkm_location","stkm_mat_code",$p_mat_code,$conn);
					
					$mat_qty_oh = (string) $mat_qty_oh;
					$pcs_per_box = "";
					if ($mat_pcs_per_box != "") {
						$pcs_per_box = "";
						$mat_pcs_per_box_array = explode(" ",$mat_pcs_per_box);
						$pcs_pos = sizeof($mat_pcs_per_box_array) - 2;
						$pcs_per_box = $mat_pcs_per_box_array[$pcs_pos];
					}
					
					
					if ($mat_qty_oh != "") {
						$results[0] = "1";
						$results[1] = $row_mat['mat_th_name'];
						$results[2] = $mat_qty_oh;
						$results[3] = $pcs_per_box;
						$results[4] = $mat_spt_group;
						$results[5] = $mat_location;
					}
					else {
						$mat_qty_oh = "0";
						$results[0] = "0";
						$results[1] = "ไม่พบสินค้า:\n" . $row_mat['mat_th_name'] ."\nในข้อมูล Stock";
						$results[2] = "...";
						$results[3] = "";
						$results[4] = "";
						$results[5] = "";
					}
				}
				else {
					$results[0] = "0";
					$results[1] = "ไม่พบรหัสสินค้าในระบบค่ะ!";
					$results[2] = "...";
					$results[3] = "";
					$results[4] = "";
					$results[5] = "";
				}
			}
			else {
				$results[0] = "0";
				$results[1] = "คุณไม่มีสิทธิ์เข้าใช้งานค่ะ";
				$results[2] = "...";
				$results[3] = "";
				$results[4] = "";
				$results[5] = "";
			}
		}
		else {
			$results[0] = "0";
			$results[1] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้ค่ะ";
		}
		closeDB($conn); //ปิดการเชื่อมต่อกับฐานข้อมูล
		return $results;
		exit();
	}
	
    function rec_stock_pcs($p_access_code,$p_rec_by,$p_tran_type,$p_tran_remark,$p_mat_code,$p_mat_unit,$p_qty) {
		$today = date("Y-m-d H:i:s");
		
		$user_login = $p_rec_by;
		$tran_type = $p_tran_type;
		$tran_remark = $p_tran_remark;
		$mat_code = $p_mat_code;
		$mat_unit = $p_mat_unit;
		$qty = $p_qty;
		
		$conn = connectDB();
		if ($conn) {
			$wsauth = accessWS($p_access_code,$conn);
			$results = array();
			if ($wsauth) {
				$sql_mat = "select * from material where mat_code = '$mat_code'";											
				$result_mat = sqlsrv_query($conn,$sql_mat);
				$row_mat = sqlsrv_fetch_array($result_mat, SQLSRV_FETCH_ASSOC);
				if ($row_mat) {
					$mat_spt_group = $row_mat['mat_spt_group'];
					if ($mat_spt_group == "BS") { 
						$mat_unit = "B"; 
					}
					$sql_stkm = "select stkm_mat_code from stkm_mstr where stkm_mat_code = '$mat_code'";
					$result_stkm = sqlsrv_query($conn, $sql_stkm);
					$row_stkm = sqlsrv_fetch_array($result_stkm, SQLSRV_FETCH_ASSOC);
					if (!$row_stkm) {
						//Add New Stock
						$sql_update_stock = " INSERT INTO stkm_mstr (" . 
						" stkm_mat_code,stkm_qty_resv,stkm_qty_oh,stkm_unit_code,stkm_location,stkm_create_by,stkm_create_date)" .		
						" VALUES('$mat_code','0','$qty','$mat_unit','New Stock','$user_login','$today')";	
					}
					else {
						//Update Old Stock
						$sql_update_stock = "UPDATE stkm_mstr " .
						" SET stkm_qty_oh = stkm_qty_oh + $qty," .
						" stkm_update_by = '$user_login'," .
						" stkm_update_date = '$today'" .
						" WHERE stkm_mat_code = '$mat_code'";
					}
					$stkh_id = getnewid("stkh_id", "stkh_hist", $conn);
					$sql_add_stkh = " INSERT INTO stkh_hist (" . 
						" stkh_id,stkh_mat_code,stkh_trantypem_code,stkh_qty,stkh_unit,stkh_sptm_nbr,stkh_sptd_id,stkh_sptbc_id,stkh_remark,stkh_create_by,stkh_create_date)" .		
						" VALUES('$stkh_id','$mat_code','$tran_type','$qty','$mat_unit','','','','$tran_remark','$user_login','$today')";				
						
					$sql_update = $sql_update_stock . " " . $sql_add_stkh;
					$result = sqlsrv_query($conn, $sql_update);
					if ($result) {
						$results[0] = "1";
						$results[1] = "บันทึกรับเรียบร้อยค่ะ";
					 }
					 else {
						$errortxt = "Error: ";
						if( ($errors = sqlsrv_errors() ) != null) {
							foreach( $errors as $error ) {
								$errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
									"code: ".$error[ 'code']."<br />".
									"message: ".$error[ 'message']."<br />";
							}
						}
						$results[0] = "0";
						$results[1] = $errortxt;
					}
				}
				else {
					$results[0] = "0";
					$results[1] = "ไม่พบรหัสสินค้าในระบบค่ะ!";
				}
			}
			else {
				$results[0] = "0";
				$results[1] = "คุณไม่มีสิทธิ์เข้าใช้งานค่ะ";
			}
			closeDB($conn); //ปิดการเชื่อมต่อกับฐานข้อมูล
		}
		else {
			$results[0] = "0";
			$results[1] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้ค่ะ";
		}
		return $results;
		exit();
		
    }
	function iss_stock_pcs($p_access_code,$p_iss_by,$p_tran_type,$p_tran_remark,$p_mat_code,$p_mat_unit,$p_qty) {
		$today = date("Y-m-d H:i:s");
		$user_login = $p_iss_by;
		$tran_type = $p_tran_type;
		$tran_remark = $p_tran_remark;
		$mat_code = $p_mat_code;
		$mat_unit = $p_mat_unit;
		$qty = $p_qty;
		
		$conn = connectDB();
		if ($conn) {
			$wsauth = accessWS($p_access_code,$conn);
			$results = array();
			if ($wsauth) {
				$sql_mat = "select * from material where mat_code = '$mat_code'";											
				$result_mat = sqlsrv_query($conn,$sql_mat);
				$row_mat = sqlsrv_fetch_array($result_mat, SQLSRV_FETCH_ASSOC);
				if ($row_mat) {
					$mat_spt_group = $row_mat['mat_spt_group'];
					if ($mat_spt_group == "BS") { 
						$mat_unit = "B"; 
					}
					$sql_stkm = "select stkm_mat_code,stkm_qty_oh from stkm_mstr where stkm_mat_code = '$mat_code'";
					$result_stkm = sqlsrv_query($conn, $sql_stkm);
					$row_stkm = sqlsrv_fetch_array($result_stkm, SQLSRV_FETCH_ASSOC);
					if ($row_stkm) {
						$stkm_qty_oh = $row_stkm['stkm_qty_oh'];
						if ($stkm_qty_oh >= $qty) {
							$new_oh_qty = $stkm_qty_oh - $qty;
							//Update Stock
							$sql_update_stock = "UPDATE stkm_mstr " .
							" SET stkm_qty_oh = $new_oh_qty," .
							" stkm_update_by = '$user_login'," .
							" stkm_update_date = '$today'" .
							" WHERE stkm_mat_code = '$mat_code'";
						
							$stkh_id = getnewid("stkh_id", "stkh_hist", $conn);
							$sql_add_stkh = " INSERT INTO stkh_hist (" . 
								" stkh_id,stkh_mat_code,stkh_trantypem_code,stkh_qty,stkh_unit,stkh_sptm_nbr,stkh_sptd_id,stkh_sptbc_id,stkh_remark,stkh_create_by,stkh_create_date)" .		
								" VALUES('$stkh_id','$mat_code','$tran_type','$qty','$mat_unit','','','','$tran_remark','$user_login','$today')";				
								
							$sql_update = $sql_update_stock . " " . $sql_add_stkh;
							$result = sqlsrv_query($conn, $sql_update);
							if ($result) {
								$results[0] = "1";
								$results[1] = "บันทึกตัดเรียบร้อยค่ะ";
							 }
							 else {
								$errortxt = "Error: ";
								if( ($errors = sqlsrv_errors() ) != null) {
									foreach( $errors as $error ) {
										$errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
											"code: ".$error[ 'code']."<br />".
											"message: ".$error[ 'message']."<br />";
									}
								}
								$results[0] = "0";
								$results[1] = $errortxt;
							}
						}
						else {
							$results[0] = "0";
							$results[1] = "มียอด Stock = " . $stkm_qty_oh ." ไม่พอตัดค่ะ!";
						}
					}
					else {
						$results[0] = "0";
						$results[1] = "ไม่สามารถตัดได้ ไม่ยอด Stock ในระบบค่ะ!";
					}
				}
				else {
					$results[0] = "0";
					$results[1] = "ไม่พบรหัสสินค้าในระบบค่ะ!";
				}
			}
			else {
				$results[0] = "0";
				$results[1] = "คุณไม่มีสิทธิ์เข้าใช้งานค่ะ";
			}
			closeDB($conn); //ปิดการเชื่อมต่อกับฐานข้อมูล
		}
		else {
			$results[0] = "0";
			$results[1] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้ค่ะ";
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
		//Get dbkey from ini file
		$inipath = php_ini_loaded_file();
		$ini_array = parse_ini_file($inipath , true);
		$dbkey=$ini_array["dbaccess"]["dbkey"];
		
		//
		//$encdbpwd="i6xksERPHoHgnbjBZFuqsK-M457RuaIs8GjMv6mHzOs,"; //for test
		$encdbpwd="7GGTy0cZ0kQH8THukLEfbyKFJxXDV12zqW9BkYJsiPk,"; //for production
		$decdbpwd=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($dbkey), base64_decode(strtr($encdbpwd, '-_,', '+/=')), MCRYPT_MODE_CBC, md5(md5($dbkey))), "\0");
		//
		//$encdbserv="9BJ3TkzAvOX8goVw6k7cPuwyzDiZBHuRTBtYqBR_BjE,"; //for test
		$encdbserv="MgrXkJo-0fVMNv4z3Hj5RdUKg2YxV0iG_kp-5o6XdNo,"; //for production
		$decdbserv=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($dbkey), base64_decode(strtr($encdbserv, '-_,', '+/=')), MCRYPT_MODE_CBC, md5(md5($dbkey))), "\0");

		//
		$dbserver = $decdbserv;
		$dbname = "UAT_Sampletile_R1";
		$dbuser = "sa";
		$dbpwd = $decdbpwd;

		// $dbserver = "L0650EITKOMSUNJ\SQLEXPRESS";
		// $dbname = "Sampletiles2019";
		// $dbuser = "sa";
		// $dbpwd = "root";
		
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
	// Use the request to (try to) invoke the service
    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
    $server->service($HTTP_RAW_POST_DATA);
	