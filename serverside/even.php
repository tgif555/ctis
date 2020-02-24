<?php
	include("../_incs/funcServer.php");
	include("../_incs/config.php");	
	$pjm_nbr_calendar = html_escape(decrypt($_REQUEST['pjmnumber'],$key));
	$pjm_custpj_code_calendar = html_escape(decrypt($_REQUEST['custnumber'],$key));
	
	// $pjm_nbr_calendar = "PJ-2002-0002";
	// $pjm_custpj_code_calendar = "CT00000002";
	
	$sql_calendar = "SELECT     pjm_mstr.pjm_nbr, pjm_mstr.pjm_name, pjm_mstr.pjm_start_date, pjm_mstr.pjm_end_date, pjm_mstr.pjm_pjt_code, qtm_mstr.qtm_nbr, qtm_mstr.qtm_to, qtm_mstr.qtm_name, 
                      qtm_mstr.qtm_customer_number, qtm_mstr.qtm_customer_name, qtm_mstr.qtm_date, qtm_mstr.qtm_expire_date, qtm_mstr.qtm_address, qtm_mstr.qtm_amphur, qtm_mstr.qtm_province, 
                      qtm_mstr.qtm_zip_code, qtm_mstr.qtm_lineid, qtm_mstr.qtm_email, qtm_mstr.qtm_tel_contact, qtm_mstr.qtm_remark
FROM         pjm_mstr INNER JOIN
                      qtm_mstr ON pjm_mstr.pjm_nbr = qtm_mstr.qtm_pjm_nbr where qtm_mstr.qtm_customer_number = '".$pjm_custpj_code_calendar."' and pjm_mstr.pjm_nbr  ='".$pjm_nbr_calendar."' order by qtm_nbr desc";
	$result_calendar = sqlsrv_query( $conn, $sql_calendar);
	$i =0;
	$event_calendar=array();  
  
	while($dbarr_calendar = sqlsrv_fetch_array($result_calendar, SQLSRV_FETCH_ASSOC))
	{
		$eventArray['id']=$dbarr_calendar[pjm_nbr]; 
        $eventArray['title']=$dbarr_calendar[qtm_nbr]." : ".$dbarr_calendar[qtm_name]." - ".$dbarr_calendar[qtm_customer_name];
       // $eventArray['start']=  $dbarr[qtm_date]->format('Y-m-d');
       // $eventArray['end']= $dbarr[qtm_expire_date]->format('Y-m-d');
	    $eventArray['start']=  Ymd_fr_Txt_Date($dbarr_calendar[qtm_date]);
		$eventArray['end']= Ymd_fr_Txt_Date($dbarr_calendar[qtm_expire_date]);
		//$eventArray['tel']= $dbarr[pjm_nbr];
       $eventArray['url']='../cisbof/qtdmnt.php?qtmnumber='.encrypt($dbarr_calendar[qtm_nbr],$key);
		//$eventArray['address']= $dbarr[qtm_nbr];
		//$eventArray['rendering']='background';
		$eventArray['color'] = '#DA4453';
		array_push($event_calendar,$eventArray);
		$i++;
	
	}
	
	echo json_encode($event_calendar);
	
?>