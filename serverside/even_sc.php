<?php
	include("../_incs/funcServer.php");
	//include("../_incs/funcServer_project.php");
	include("../_incs/config.php");	
	
	
	// $pjm_nbr_calendar = "PJ-2002-0002";
	// $pjm_custpj_code_calendar = "CT00000002";
	
	$sql_sc = "SELECT emp_user_id, emp_scg_emp_id, emp_prefix_th_name, emp_th_firstname, ".
	"emp_th_lastname, emp_status_code, emp_th_pos_name, emp_email_bus ".
	"FROM  emp_mstr ".
	"WHERE (emp_status_code = 3)";
	
	$result_sc = sqlsrv_query( $conn, $sql_sc);
	$i =0;
	$event_sc=array();  
  
	while($dbarr_sc = sqlsrv_fetch_array($result_sc, SQLSRV_FETCH_ASSOC))
	{
		$eventArray['sc_emp_user_id']=$dbarr_sc[emp_user_id]; 
        $eventArray['sc_scg_emp_id']=$dbarr_sc[emp_scg_emp_id];
		$eventArray['sc_name']=  $dbarr_sc[emp_prefix_th_name]."".$dbarr_sc[emp_th_firstname]."  ".$dbarr_sc[emp_th_lastname];
		$eventArray['sc_pos_name']=$dbarr_sc[emp_th_pos_name];
		$eventArray['sc_email']=$dbarr_sc[emp_email_bus];
		
		array_push($event_sc,$eventArray);
		$i++;
	
	}
	
	echo json_encode($event_sc);
	
?>