<?	
//include("../_incs/chksession.php"); 

include("../_incs/config.php");	
	
		$sql_record = "  SELECT     qtm_mstr.qtm_nbr, qtm_mstr.qtm_name, pjm_mstr.pjm_nbr, pjm_mstr.pjm_name, qtm_mstr.qtm_customer_number, qtm_mstr.qtm_customer_name 
                       ,qtm_mstr.qtm_date, qtm_mstr.qtm_expire_date, qtm_mstr.qtm_customer_price, qtm_mstr.qtm_customer_disc, qtst_mstr.qtst_code, qtst_mstr.qtst_name
 FROM         qtm_mstr INNER JOIN
                       pjm_mstr ON qtm_mstr.qtm_pjm_nbr = pjm_mstr.pjm_nbr INNER JOIN
                       qtst_mstr ON qtm_mstr.qtm_step_code = qtst_mstr.qtst_code where qtm_mstr.qtm_customer_number = ? order by qtm_nbr desc";
		$params = array('CT00000002');
		$result_record = sqlsrv_query( $conn,$sql_record, $params, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result_record);
		
	    //Nilubonp : Check Row of Result Before Create JSON ARRAY
		if ($row_counts == 0) // No Result
		{
			echo "<javascript>alert('Error in retrieveing row count.');</javascript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=../cisbof/pjmall.php\" />";	
			//ต้องวิ่งกลับไปที่เพจก่อนหน้า
			exit();
		}
		else //Nilubonp :  Result = or > 0
		{
			$arrayMain = array();	
			if($row_counts == 0) // Result == 0 row
			{
				$arrayMain['draw'] = 0;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				$arrayMain['data'] = array();
				echo json_encode($arrayMain);	
			}
			else //Nilubonp :  Result > 0 row
			{
				//Nilubonp : Create Array for Build JSON ( $arrayMain)
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				//Nilubonp : Create Array for Build data to push into $arrayMain ($arrayJSON)
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row_record = sqlsrv_fetch_array($result_record, SQLSRV_FETCH_ASSOC))
				{
					$arrayDATA['qtm_nbr'] = $row_record['qtm_nbr'];
					$arrayDATA['qtm_name'] = $row_record['qtm_name'];
					$arrayDATA['pjm_nbr'] = $row_record['pjm_nbr'];
					$arrayDATA['pjm_name'] = $row_record['pjm_name'];
					$arrayDATA['qtm_customer_number'] = $row_record['qtm_customer_number'];
					$arrayDATA['qtm_customer_name'] = $row_record['qtm_customer_name'];
					$arrayDATA['payterm_code'] = $row_record['payterm_code'];
					$arrayDATA['payterm_name'] = $row_record['payterm_name'];
					$arrayDATA['qtm_date'] = $row_record['qtm_date'];
					$arrayDATA['qtm_expire_date'] = $row_record['qtm_expire_date'];
					$arrayDATA['qtm_customer_price'] = $row_record['qtm_customer_price'];
					$arrayDATA['qtm_customer_disc'] = $row_record['qtm_customer_disc'];
					$arrayDATA['qtst_code'] = $row_record['qtst_code'];
					$arrayDATA['qtst_name'] = $row_record['qtst_name'];

					//Nilubonp : Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$arrayDATA);
					
					//Nilubonp : Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Nilubonp : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}
		}				
?>