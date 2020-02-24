<?php
	include("../_incs/config.php");	
	$request =  $_POST["query"];

	$query_cust_detail = " SELECT * from custpj_mstr ";	
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail);

	$data_cust_detail = array();
	while($row_cust_detail = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC))
		{
			$dataArray['custpj_code'] = $row_cust_detail['custpj_code'];
			$dataArray['custpj_name'] = $row_cust_detail['custpj_name'];
			$dataArray['custpj_addr'] = $row_cust_detail['custpj_addr'];
			$dataArray['custpj_tel'] = $row_cust_detail['custpj_tel'];
			$dataArray['custpj_lineid'] = $row_cust_detail['custpj_lineid'];
			$dataArray['custpj_email'] = $row_cust_detail['custpj_email'];
			$dataArray['custpj_contact_name'] = $row_cust_detail['custpj_contact_name'];
			$dataArray['custpj_contact_addr'] = $row_cust_detail['custpj_contact_addr'];
			$dataArray['custpj_contact_tel'] = $row_cust_detail['custpj_contact_tel'];
			$dataArray['custpj_contact_lineid'] = $row_cust_detail['custpj_contact_lineid'];
			$dataArray['custpj_contact_email'] = $row_cust_detail['custpj_contact_email'];
			array_push($data_cust_detail,$dataArray);
		}
	echo json_encode($data_cust_detail);

?>