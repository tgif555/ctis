					<fieldset>											
					<table width=50% cellpadding=2 cellspacing=1>
						<tr height=20 bgcolor=Moccasin>
							<td class="f_bk8b" width=20%>ขั้นตอน</td>
							<td class="f_bk8b" width=20%>ผู้ดำเนินการ</td>
							<td class="f_bk8b" width=20%>วันที่</td>																			
							<td class="f_bk8b">หมายเหตุ</td>
						</tr>
						<?php
						$sql_stephist = "select * from spta_approval where spta_active = 1 and spta_sptm_nbr = '$sptm_nbr' order by spta_id";
						$result_stephist = sqlsrv_query( $conn, $sql_stephist);																					
						while($r_stephist = sqlsrv_fetch_array($result_stephist, SQLSRV_FETCH_ASSOC)) {	
							$spta_step = $r_stephist['spta_f_step'];
							$spta_process = $r_stephist['spta_t_step'];
							$spta_create_by = $r_stephist['spta_create_by'];
							$spta_text = $r_stephist['spta_text'];
							$spta_remark = $r_stephist['spta_remark'];
				
							$spta_process_name = findsqlval("step_mstr", "step_name", "step_code", $spta_process,$conn);
							$spta_process_date = date_format($r_stephist['spta_create_date'],"d/m/Y H:i:s");
							$spta_process_by = findsqlval("emp_mstr", 	"emp_th_firstname + ' ' + emp_th_lastname", "emp_user_id", $spta_create_by,$conn);																					
							?>
							<tr height=20>
								<td bgcolor=LightSlateGray class="f_wh8" width=20%><?php echo str_replace("Cancel","<font color=red>(Cancelled)</font>",$spta_text);?></td>
								<td bgcolor=LightSlateGray class="f_wh8" width=15%><?php echo $spta_process_by;?></td>
								<td bgcolor=LightSlateGray class="f_wh8" width=15%><?php echo $spta_process_date;?></td>																			
								<td bgcolor=LightSlateGray class="f_wh8"><?php echo $spta_remark;?></td>
							</tr>																		
						<?php } ?>																		
					</table>
					</fieldset>
					


