<?php 
function getpjmnbr($type,$conn) {
	//QT-YYMM-0001
	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(pjm_nbr,9,4)) as nbr from pjm_mstr where substring(pjm_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$qtm_nbr = $tym."-".substr("0000{$next_numner}", -4);
	return $qtm_nbr;
}
function Ymd_fr_Txt_Date($strdate) {
	//get from format dd/mm/yyyy to yyyy - mm -dd
	$Y = substr($strdate,0,4);
	$m = substr($strdate,4,2);
	$d = substr($strdate,6,2);
	return $Y."-".$m."-".$d;
}
function day_diff_sign($fromdate,$todate) {
	//para1 yyyymmdd, para2 = yyyymmdd
	$date1 = date_create(substr($fromdate,0,4).'-'.substr($fromdate,4,2).'-'.substr($fromdate,6,2));
	$date2 = date_create(substr($todate,0,4).'-'.substr($todate,4,2).'-'.substr($todate,6,2));
	$interval = date_diff($date1,$date2);
	return $interval->format('%R%a');
}
//ID for sptd_det
function getnewqtddetid2($qtm_nbr,$conn) {
	//QT-2001-0001-001
	$sql = "select max(substring(qtd_id,14,3)) as seq from qtd_det where qtd_qtm_nbr = '$qtm_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $qtm_nbr."-".substr("000{$id}", -3);	
}

?>
