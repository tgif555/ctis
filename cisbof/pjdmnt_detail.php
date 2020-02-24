<form class="form">
	<div class="form-body">	
		<div class="form-group row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="pjm_pjm_list" class="table table-sm table-hover compact nowrap"  style="width:100%;" > <!--dt-responsive nowrap-->
						<thead >
							<tr class="bg-primary white text-center" style="width:100%; line-height:30px;" >	
								<th>Quotation No.</th>
								<th>Quotation Name</th>
								<th>Project No.</th>
								<th>Customer</th>					
								<th>Start</th>
								<th>End</th>
								<th>Prepaid Amount</th>
								<th>Customer Amount</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>	
						<?php							
							$sql_record  = "SELECT     qtm_mstr.qtm_nbr, qtm_mstr.qtm_name, pjm_mstr.pjm_nbr, pjm_mstr.pjm_name, qtm_mstr.qtm_customer_number, qtm_mstr.qtm_customer_name, qtm_mstr.qtm_date, qtm_mstr.qtm_expire_date, pjst_mstr.pjst_code, pjst_mstr.pjst_name, qtm_step_mstr.qtm_step_name, qtm_step_mstr.qtm_step_code,pjm_mstr.pjm_deposit_amt
FROM         qtm_mstr INNER JOIN
                      pjm_mstr ON qtm_mstr.qtm_pjm_nbr = pjm_mstr.pjm_nbr INNER JOIN
                      pjst_mstr ON pjm_mstr.pjm_pjst_code = pjst_mstr.pjst_code INNER JOIN
                      qtm_step_mstr ON qtm_mstr.qtm_step_code = qtm_step_mstr.qtm_step_code  where  qtm_pjm_nbr ='$pjm_nbr'";
							
							$result_record = sqlsrv_query( $conn,$sql_record, $params, array( "Scrollable" => 'keyset' ));	
							$row_counts = sqlsrv_num_rows($result_record);

							if ($row_counts == 0){
								echo "<tr class='bg-warning white text-center' style='line-height:35px; width:100%;' >";
								echo "<td colspan='11'><a  href='#div_add_qtm_project' data-toggle='modal'><i class='fa fa-plus'></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a></td>";
								echo "</tr>";
							}
							else {
								while($row_record = sqlsrv_fetch_array($result_record, SQLSRV_FETCH_ASSOC))
								{
									$qtm_nbr =   $row_record['qtm_nbr'];
									$qtm_name =   $row_record['qtm_name'];
									$pjm_nbr =   $row_record['pjm_nbr'];
									$pjm_name =   $row_record['pjm_name'];
									$qtm_customer_number =   $row_record['qtm_customer_number'];
									$qtm_customer_name =   $row_record['qtm_customer_name'];
									$qtm_date =   $row_record['qtm_date'];
									$qtm_expire_date =   $row_record['qtm_expire_date'];
									$qtm_customer_price =   $row_record['qtm_customer_price'];		
									$pjm_deposit_amt =   $row_record['pjm_deposit_amt'];		
									$pjst_code =   $row_record['pjst_code'];
									$pjst_name =   $row_record['pjst_name'];									
									$qtm_step_code =   $row_record['qtm_step_code'];
									$qtm_step_name =   $row_record['qtm_step_name'];
									
									echo "<tr class='text-center' style='width:100%;' >";																				
										echo "<td>".$qtm_nbr."</td>";
										echo "<td>".$qtm_name."</td>";
										echo "<td>".$pjm_nbr."<br>".substr($pjm_name,0,15)."...</td>";									
										echo "<td>".$qtm_customer_number."<br>".substr($qtm_customer_name,0,15)."...</td>";										
										echo "<td>".dmytx($qtm_date)."</td>";
										echo "<td>".dmytx($qtm_expire_date)."</td>";
										echo "<td>".$pjm_deposit_amt."</td>";		
										echo "<td>".$qtm_customer_price."</td>";															
										echo "<td>".$qtm_step_name."</td>";
										echo "<td>";
										
											echo "<div class='btn-group cus-dropdown-action' >";
											echo "<button type='button' class='btn btn-success btn-md dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button>";
												echo "<div class='dropdown-menu'>";
													echo "<a class='dropdown-item' href='qtdmnt.php?qtmnumber=".encrypt($qtm_nbr, $key)."&pg=".$pg."'><i class='fa fa-search-plus'></i> View</a>
															<a class='dropdown-item' href='qtmedit.php?qtmnumber=".encrypt($qtm_nbr, $key)."&pg=".$pg."'><i class='fa fa-pencil-square-o'></i> Edit</a>															
															<div class='dropdown-divider'></div>
																<a class='dropdown-item' id='btdel' data-qtmnumber='$qtm_nbr' data-pjmnumber='$pjm_nbr' href='javascript:void(0)'>											
																	<i class='fa fa-trash-o fa-sm '></i>
																		Delete From This Project
																</a>
															</div>
														</div>";
										echo "</td>";
										echo "</tr>";
								}
							}				
							?>
						</tbody>														
					</table> 
				</div>
				<!-- End Datatable -->													
			</div>                                               
		</div>		
	</div>
</form>
<form name="frm_del_qtm_project" id="frm_del_qtm_project">
	<input type="hidden" name="action" value="del_qtm_project">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">		
	<input type="hidden" name="pjm_nbr" value="<?php echo $pjm_nbr;?>">		
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>

