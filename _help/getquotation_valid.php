<?php
	include("../_incs/config.php");	
	include("../_incs/funcserver.php");

	$request =  decrypt($_REQUEST["custnumber"],$key);

	$query_qtm_valid = " SELECT * FROM qtm_mstr WHERE (qtm_pjm_nbr ='' or qtm_pjm_nbr is null) and qtm_is_delete ='0' and qtm_step_code ='90'  and qtm_customer_number ='".$request."'";	
	//echo $query_qtm_valid;
	$result_qtm_valid = sqlsrv_query($conn, $query_qtm_valid);

	$data_qtm_valid = array();
	while($row_qtm_valid = sqlsrv_fetch_array($result_qtm_valid, SQLSRV_FETCH_ASSOC))
		{
			$dataArray_qtm_valid['qtm_nbr'] = $row_qtm_valid['qtm_nbr'];
			$dataArray_qtm_valid['qtm_name'] = $row_qtm_valid['qtm_name'];			
			array_push($data_qtm_valid,$dataArray_qtm_valid);
		}
	echo json_encode($data_qtm_valid);

?>