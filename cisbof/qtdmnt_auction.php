<div class="table-responsive">
	<table class="table-sm table-bordered" style="width:100%">	
		<thead>
			<tr bgcolor="paleturquoise">
				<th style="width:50px;text-align:center;">No.</th>
				<th style="width:150px;text-align:center;">Auction No</th>
				<th style="width:150px;text-align:center;">Contractor</th>
				<th style="width:150px;text-align:center;">Date</th>
				<th style="width:150px;text-align:center;background:yellow">ราคากลางผู้รับเหมา</th>
				<th style="width:150px;text-align:center;background:#80ff80;color:black">Auction Price</th>
				<th style="width:150px;text-align:center;">Status</th>
				<th style="width:150px;text-align:center;">Result</th>
				<th colspan=2 class="text-right">
					<?php if ($can_auction) {?>
					<a href="#div_create_auction" data-toggle="modal">
						<div class="btn btn-sm white" style="background:red">		
							<i class="feather icon-file-plus mr-25"></i><span>Create Auction</span>
						</div>
					</a>
					<?php }?>
				</th>
			</tr>
		</thead>   
		<tbody>
		<?php
		$n = 0;
		$params_aucm = array($qtm_nbr);																	
		$sql_aucm = "SELECT * FROM aucm_mstr where aucm_qtm_nbr = ?";
		$result_aucm = sqlsrv_query( $conn, $sql_aucm,$params_aucm);
															
		while($rec_aucm = sqlsrv_fetch_array($result_aucm, SQLSRV_FETCH_ASSOC)) {
			$aucm_nbr = html_escape($rec_aucm['aucm_nbr']);
			$aucm_qtm_nbr = html_escape($rec_aucm['aucm_qtm_nbr']);
			$aucm_tem_code = html_escape($rec_aucm['aucm_tem_code']);
			$aucm_tem_name = findsqlval("tem_mstr","tem_name", "tem_code",$aucm_tem_code,$conn);
			$aucm_auction_date = $rec_aucm['aucm_auction_date'];
			$aucm_auction_price = $rec_aucm['aucm_auction_price'];
																		
			$aucm_step_code	= html_escape($rec_aucm['aucm_step_code']);
			$aucm_result = html_escape($rec_aucm['aucm_result']);
			$aucm_create_by = html_escape($rec_aucm['aucm_create_by']);
			$aucm_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_create_by,$conn);																		
			$aucm_create_date = $rec_aucm['aucm_create_date'];
																							
			$n++;																										
			?>
			<tr class="text-center">
				<td class="pl-0 pr-0"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
				<td class="pl-0 pr-0"><?php echo $aucm_nbr; ?></td>
				<td class="pl-0 pr-0"><?php echo $aucm_tem_name; ?></td>	
				<td class="pl-0 pr-0"><?php echo dmydb($aucm_auction_date,"Y"); ?></td>
				<td class="pl-0 pr-0"><?php echo number_fmt($qtm_contractor_amt,2); ?></td>
				<td class="pl-0 pr-0"><?php echo number_fmt($aucm_auction_price,2); ?></td>
				<td class="pl-0 pr-0"><?php echo $aucm_step_code; ?></td>
				<td class="pl-0 pr-0"><div class="badge badge-pill badge-success badge-sm"><?php echo $aucm_result; ?></div></td>
				<td>
					<?php if ($can_auction) {?>
					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle mr-1 mb-1 btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
						
						<div class="dropdown-menu">
							<a class="dropdown-item small red" href="javascript:void(0);" onclick="auction_assign_win('<?php echo $aucm_nbr?>')"><i class="fa fa-pencil-square-o"></i> Assign WIN</a>
							<a class="dropdown-item small blue" href="#div_edit_auction<?php echo $aucm_nbr?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Edit</a>
							<a class="dropdown-item small red" href="javascript:void(0)" onclick="del_auction('<?php echo $aucm_nbr?>')"><i class="fa fa-trash-o"></i> Delete</a>
						</div>
					</div>
					<?php }?>
				</td>
				<td class="pl-0 pr-0" style="width:20px;">
					<?php if($aucm_activeid==$aucm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
					<?php if ($can_auction) {?>
					<div class="modal fade text-left" id="div_edit_auction<?php echo $aucm_nbr?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel17"><i class="feather icon-pie-chart"></i> Edit Auction</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">		
									<form id="frm_edit_auction<?php echo $aucm_nbr?>" name="frm_edit_auction<?php echo $aucm_nbr?>" autocomplete=OFF>	
										<input type="hidden" name="action" value="edit_auction">
										<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
										<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
										<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">
										<input type="hidden" name="aucm_nbr" value="<?php echo $aucm_nbr;?>">
										<input type="hidden" name="pg" value="<?php echo $pg;?>">
										<input type="hidden" name="aucd_qtd_id_list">
										<input type="hidden" name="aucd_auction_unit_amt_list">
										<div class="table-responsive">
											<table class="table-sm table-bordered">		
												<thead>
												<tr>
													<th colspan=5 class="text-right text-muted">Contractor:</th>
													<th colspan=2>
														<select name="aucd_tem_code" class="form-control form-control-sm" style="color:red" >
															<option value="">-Select-</option>
															<?php 
															$sql_tem = "SELECT tem_code,tem_name FROM tem_mstr WHERE tem_active = 1 order by tem_name";
															$result_tem_list = sqlsrv_query( $conn,$sql_tem);																													
															while($r_tem_list=sqlsrv_fetch_array($result_tem_list, SQLSRV_FETCH_ASSOC)) {
															?>
																<option  style="color:black" value="<?php echo $r_tem_list['tem_code'];?>"
																	<?php if ($aucm_tem_code == $r_tem_list['tem_code']) {echo "selected";}?>> 
																	<?php echo $r_tem_list['tem_name'];?>
																</option> 
															<?php } ?>
														</select>
													</th>
												</tr>
												<tr bgcolor="paleturquoise" class="text-muted">
													<th class="text-muted" style="width:20px">ลำดับ</th>			
													<th class="text-muted" style="width:100px;text-align:center;">รหัสสินค้า</th>
													<th class="text-muted" style="width:200px;text-align:center;">ชื่อสินค้า</th>
													<th class="text-muted" style="width:80px;text-align:center;">จำนวน</th>
													<th class="text-muted" style="width:80px;text-align:center;">หน่วย</th>
													<th class="text-muted" style="width:80px;text-align:center;">ราคากลาง/หน่วย</th>
													<th class="text-muted" style="width:80px;text-align:center;">ราคาเสนอ/หน่วย</th>
												</tr>
												</thead>
												<?php
												$n = 0;
												$params_edit_auction = array($aucm_nbr);
												$sql_edit_auction = "SELECT * FROM aucd_det" .
													" INNER JOIN mat_mstr ON mat_code = aucd_mat_code" .
													" INNER JOIN unit_mstr ON unit_code = aucd_contractor_unit_code" .
													" where aucd_aucm_nbr = ?";	
												
												$result_edit_auction = sqlsrv_query( $conn, $sql_edit_auction,$params_edit_auction );											
												while($rec_edit_auction = sqlsrv_fetch_array($result_edit_auction, SQLSRV_FETCH_ASSOC)) {	
													$aucd_id = html_escape($rec_edit_auction['aucd_id']);
													$aucd_qtd_id = html_escape($rec_edit_auction['aucd_qtd_id']);
													$aucd_qtm_nbr = html_escape($rec_edit_auction['aucd_qtm_nbr']);
													$aucd_mat_code = html_escape($rec_edit_auction['aucd_mat_code']);
													$aucd_mat_name = html_escape($rec_edit_auction['aucd_mat_name']);
													$aucd_contractor_qty = html_escape($rec_edit_auction['aucd_contractor_qty']);
													$aucd_contractor_unit_code = html_escape($rec_edit_auction['aucd_contractor_unit_code']);
													$aucd_unit_name = html_escape($rec_edit_auction['unit_name']);
													$aucd_contractor_price = html_escape($rec_edit_auction['aucd_contractor_price']);
													$aucd_contractor_disc = html_escape($rec_edit_auction['aucd_contractor_disc']);
													$aucd_contractor_disc_unit = html_escape($rec_edit_auction['aucd_contractor_disc_unit']);
													$aucd_auction_unit_amt = $rec_edit_auction['aucd_auction_unit_amt'];
													$aucd_contractor_amt = $aucd_contractor_price;
													$aucd_contractor_unit_amt = 0;
													$aucd_contractor_disc_amt = 0;
													if ((double)$aucd_contractor_disc > 0) {
														if ($aucd_contractor_disc_unit == "P") {
															$aucd_contractor_disc_amt = $aucd_contractor_price * $aucd_contractor_disc /100;
															$aucd_contractor_unit_amt = $aucd_contractor_price - $aucd_contractor_disc_amt;
														}
														if ($aucd_contractor_disc_unit == "B") {
															$aucd_contractor_disc_amt = $aucd_contractor_disc;
															$aucd_contractor_unit_amt = $aucd_contractor_amt - $aucd_contractor_disc_amt;
														}
													}
													else {
														$aucd_contractor_unit_amt = $aucd_contractor_amt;	
													}
													$n++;																			
													?>
													<tr>
														<input type="hidden" name="aucd_qtd_id" id="frm_edit_auction<?php echo $aucm_nbr?>_aucd_qtd_id_<?php echo $aucd_qtd_id;?>" value="<?php echo $aucd_id;?>">
														<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
														<td style="background-color: lightcyan"><?php echo $aucd_mat_code; ?></td>
														<td style="background-color: lightcyan"><?php echo $aucd_mat_name; ?></td>
														<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($aucd_contractor_qty); ?></td>
														<td style="background-color: lightcyan"><?php echo $aucd_unit_name; ?></td>
														<td style="text-align:right"><?php echo number_fmt($aucd_contractor_unit_amt); ?></td>
														<td style="text-align:right">
															<input type="text" class="form-control form-control-sm text-right" name="aucd_auction_unit_amt" id="frm_edit_auction<?php echo $aucm_nbr?>_aucd_auction_unit_amt_<?php echo $aucd_qtd_id?>" value="<?php echo number_fmt($aucd_auction_unit_amt,2,""); ?>">
														</td>
													</tr>
												<?php }?>
											</table>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
									<?php if ($can_auction) {?>
									<button type="button" class="btn btn-sm btn-outline-success" onclick='auction_postform("<?php echo 'frm_edit_auction'.$aucm_nbr;?>")'>Save changes</button>	
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				</td>	
			</tr>
		<?php }?>	
		</tbody>
	</table>
</div>

<!--Create Auction Modal-->
<div class="modal fade text-left" id="div_create_auction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel17"><i class="feather icon-pie-chart"></i> Create Auction</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">		
				<form id="frm_add_auction" name="frm_add_auction" autocomplete=OFF>	
					<input type="hidden" name="action" value="add_auction">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
					<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">	
					<input type="hidden" name="pg" value="<?php echo $pg;?>">
					<input type="hidden" name="aucd_qtd_id_list">
					<input type="hidden" name="aucd_auction_unit_amt_list">
					<div class="table-responsive">
						<table class="table-sm table-bordered">		
							<thead>
							<tr>
								<th colspan=5 class="text-right">Contractor:</th>
								<th colspan=2>
									<select name="aucd_tem_code" class="form-control form-control-sm" style="color:red" >
										<option value="">-Select-</option>
										<?php 
										$sql_tem = "SELECT tem_code,tem_name FROM tem_mstr WHERE tem_active = 1 order by tem_name";
										$result_tem_list = sqlsrv_query( $conn,$sql_tem);																													
										while($r_tem_list=sqlsrv_fetch_array($result_tem_list, SQLSRV_FETCH_ASSOC)) {
										?>
											<option  style="color:black" value="<?php echo $r_tem_list['tem_code'];?>"> 
												<?php echo $r_tem_list['tem_name'];?>
											</option> 
										<?php } ?>
									</select>
								</th>
							</tr>
							<tr bgcolor="paleturquoise">
								<th style="width:20px">ลำดับ</th>			
								<th style="width:100px;text-align:center;">รหัสสินค้า</th>
								<th style="width:250px;text-align:center;">ชื่อสินค้า</th>
								<th style="width:80px;text-align:center;">จำนวน</th>
								<th style="width:80px;text-align:center;">หน่วย</th>
								<th style="width:80px;text-align:center;">ราคากลาง/หน่วย</th>
								<th style="width:80px;text-align:center;">ราคาเสนอ/หน่วย</th>
							</tr>
							</thead>
							<?php
							$n = 0;
							$params_create_auction = array($qtm_nbr);
							$sql_create_auction = "SELECT * FROM qtd_det" .
								" INNER JOIN mat_mstr ON mat_code = qtd_mat_code" .
								" INNER JOIN unit_mstr ON unit_code = qtd_unit_code" .
								" where qtd_qtm_nbr = ?";													
							$result_create_auction = sqlsrv_query( $conn, $sql_create_auction,$params_create_auction );											
							while($rec_create_auction = sqlsrv_fetch_array($result_create_auction, SQLSRV_FETCH_ASSOC)) {	
								$aucd_qtd_id = html_escape($rec_create_auction['qtd_id']);
								$aucd_qtm_nbr = html_escape($rec_create_auction['qtd_qtm_nbr']);
								$aucd_mat_code = html_escape($rec_create_auction['qtd_mat_code']);
								$aucd_mat_name = html_escape($rec_create_auction['qtd_mat_name']);
								$aucd_contractor_qty = html_escape($rec_create_auction['qtd_qty']);
								$aucd_contractor_unit_code = html_escape($rec_create_auction['qtd_unit_code']);
								$aucd_unit_name = html_escape($rec_create_auction['unit_name']);
								$aucd_contractor_price = html_escape($rec_create_auction['qtd_contractor_price']);
								$aucd_contractor_disc = html_escape($rec_create_auction['qtd_contractor_disc']);
								$aucd_contractor_disc_unit = html_escape($rec_create_auction['qtd_contractor_disc_unit']);
							
								$aucd_contractor_amt = $aucd_contractor_price;
								$aucd_contractor_unit_amt = 0;
								$aucd_contractor_disc_amt = 0;
								if ((double)$aucd_contractor_disc > 0) {
									if ($aucd_contractor_disc_unit == "P") {
										$aucd_contractor_disc_amt = $aucd_contractor_price * $aucd_contractor_disc /100;
										$aucd_contractor_unit_amt = $aucd_contractor_price - $aucd_contractor_disc_amt;
									}
									if ($aucd_contractor_disc_unit == "B") {
										$aucd_contractor_disc_amt = $aucd_contractor_disc;
										$aucd_contractor_unit_amt = $aucd_contractor_amt - $aucd_contractor_disc_amt;
									}
								}
								else {
									$aucd_contractor_unit_amt = $aucd_contractor_amt;	
								}
								$n++;																			
								?>
								<tr id="traucd_<?php echo $aucd_qtd_id?>">
									<input type="hidden" name="aucd_qtd_id" id="frm_add_auction_aucd_qtd_id_<?php echo $aucd_qtd_id;?>" value="<?php echo $aucd_qtd_id;?>">
									<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
									<td style="background-color: lightcyan"><?php echo $aucd_mat_code; ?></td>
									<td style="background-color: lightcyan"><?php echo $aucd_mat_name; ?></td>
									<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($aucd_contractor_qty); ?></td>
									<td style="background-color: lightcyan"><?php echo $aucd_unit_name; ?></td>
									<td style="text-align:right"><?php echo number_fmt($aucd_contractor_unit_amt); ?></td>
									<td style="text-align:right">
										<input type="text" class="form-control form-control-sm text-right" name="aucd_auction_unit_amt" id="frm_add_auction_aucd_auction_unit_amt_<?php echo $aucd_qtd_id?>" value="<?php echo number_fmt($aucd_contractor_unit_amt,2,""); ?>">
									</td>
								</tr>
							<?php }?>
						</table>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
				<?php if ($can_auction) {?>
				<button type="button" class="btn btn-sm btn-outline-success" onclick='auction_postform("<?php echo 'frm_add_auction';?>")'>Save changes</button>	
				<?php }?>
			</div>
		</div>
	</div>
</div>
<form name="frm_del_auction" id="frm_del_auction">
	<input type="hidden" name="action" value="del_auction">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">		
	<input type="hidden" name="aucm_nbr">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
<form name="frm_assign_win_auction" id="frm_assign_win_auction">
	<input type="hidden" name="action" value="assign_win_auction">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">		
	<input type="hidden" name="aucm_nbr">
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
