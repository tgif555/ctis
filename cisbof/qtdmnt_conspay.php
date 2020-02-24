<div class="table-responsive">
	<table class="table-sm table-bordered">		
		<thead>
		
		<tr bgcolor="paleturquoise">	
			<th style="width:50px;text-align:center;">งวดที่</th>
			<th style="width:100px;text-align:center;">วันที่</th>
			<th style="width:250px;text-align:center;">รายละเอียด</th>
			<th style="width:100px;text-align:center;">จำนวนเงิน</th>
			<th colspan=2 class="text-right">
				<?php if ($can_auction) {?>
				<a href="#div_add_conspay" data-toggle="modal">
					<div class="btn btn-sm white" style="background:red">
						<i class="feather icon-file-plus mr-25"></i><span>Create Payment</span>
					</div>
				</a>
				<?php }?>
			</th>
		</tr>
		</thead>									
		<?php
		$n = 0;
		$params = array($qtm_nbr);
		$sql_conspay = "SELECT * FROM conspay_det where conspay_qtm_nbr = ?";
															
		$result_conspay = sqlsrv_query($conn,$sql_conspay,$params);											
		while($rec_conspay = sqlsrv_fetch_array($result_conspay, SQLSRV_FETCH_ASSOC)) {	
			$conspay_id = html_escape($rec_conspay['conspay_id']);
			$conspay_qtm_nbr = html_escape($rec_conspay['conspay_qtm_nbr']);
			$conspay_pay_seq = html_escape($rec_conspay['conspay_pay_seq']);
			$conspay_pay_date = html_escape($rec_conspay['conspay_pay_date']);
			$conspay_pay_desc = html_escape($rec_conspay['conspay_pay_desc']);
			$conspay_pay_amt = html_escape($rec_conspay['conspay_pay_amt']);
			$conspay_pay_cmmt = html_escape($rec_conspay['conspay_pay_cmmt']);
			$n++;																			
			?>
			<tr id="trqtd_<?php echo $qtd_id?>">                                          
				<td style="background-color: lightcyan"><?php echo $conspay_pay_seq; ?></td>
				<td style="background-color: lightcyan"><?php echo dmytx($conspay_pay_date); ?></td>
				<td style="background-color: lightcyan"><?php echo $conspay_pay_desc; ?></td>
				<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($conspay_pay_amt); ?></td>
				<td style="background-color: lightcyan;text-align:right">
					<?php if ($can_auction) {?>
					<center>
						<button type="button" class="btn btn-sm btn-default" onclick="del_conspay('<?php echo $conspay_id;?>')">
							Del
						</button>
						<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#div_edit_conspay<?php echo $conspay_id; ?>">
							Edit
						</button>
					</center>
					<?php }?>
					<?php if ($can_auction) {?>
						<div class="modal fade text-left" id="div_edit_conspay<?php echo $conspay_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content p-0">
									<div class="modal-header bg-success white">
										<h4 class="modal-title" id="myModalLabel9"><i class="feather icon-shopping-cart"></i> แก้ไขงวดการชำระของลูกค้า</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									
									<div class="modal-body">
										<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">					
											<form id="frm_edit_conspay<?php echo $conspay_id;?>" name="frm_edit_conspay<?php echo $conspay_id;?>" autocomplete=OFF>
												<input type="hidden" name="action" value="edit_conspay">	
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">
												<input type="hidden" name="conspay_id" value="<?php echo $conspay_id;?>">																
												<input type="hidden" name="pg" value="<?php echo $pg;?>">
											
												<div class="row">
													<div class="col-12 col-sm-12">
														<div class="form-group">
															<div class="controls">
																<div class="form-group">
																	<label class="font-weight-bold">งวดชำระ:</label>
																	<input type="text" name="conspay_pay_seq" value="<?php echo $conspay_pay_seq?>" maxlength="30" class="form-control form-control-sm">
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<label class="font-weight-bold">รายการชำระ:</label>
																<input type="text" name="conspay_pay_desc" value="<?php echo $conspay_pay_desc?>" maxlength="100" class="form-control form-control-sm">
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<!--label class="font-weight-bold">วันที่ชำระ:</label>
																<input type="text" name="conspay_pay_date" value="<?php echo dmytx($conspay_pay_date)?>" maxlength="100" class="form-control form-control-sm"-->
																<label class="font-weight-bold">วันที่ชำระ:</label>
																<div class="input-group input-group-sm">
																	<div class="input-group-prepend date">
																		<span class="input-group-text">
																			<span class="fa fa-calendar-o"></span>
																		</span>
																	</div>
																	<input type='text' name="conspay_pay_date" id="conspay_pay_date" value="<?php echo dmytx($conspay_pay_date)?>" class="form-control form-control-sm" placeholder="dd/mm/yyyy" />
																</div>	
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<div class="form-group">
																	<label class="font-weight-bold">จำนวนเงิน:</label>
																	<input type="text"  name="conspay_pay_amt" value="<?php echo $conspay_pay_amt?>" class="form-control form-control-sm">
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<label class="font-weight-bold">Remarks:</label>
																<input type="text" name="conspay_pay_cmmt" value="<?php echo $conspay_pay_cmmt?>" class="form-control form-control-sm" maxlength="255">
															</div>
														</div>
													</div>
												</div>
											</form>	
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-sm btn-outline-success" onclick='conspay_postform("<?php echo 'frm_edit_conspay'.$conspay_id;?>")'>Save changes</button>				
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
					<?php }?>
				</td>
				<td></td>
			</tr>
		<?php }?>
	</table>
</div>
<!--ADD Material Modal-->
<?php if ($can_auction) {?>
<div class="modal fade text-left" id="div_add_conspay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content p-0">
			<div class="modal-header bg-success white">
				<h4 class="modal-title" id="myModalLabel9"><i class="feather icon-shopping-cart"></i> เพิ่มงวดการชำระของลูกค้า</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">					
					<form id="frm_add_conspay" name="frm_add_conspay" autocomplete=OFF>	
						<input type="hidden" name="action" value="add_conspay">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">		
						<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">
						<input type="hidden" name="pg" value="<?php echo $pg;?>">
						<div class="row">
							<div class="col-12 col-sm-12">
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">รายการชำระ:</label>
										<input type="text" name="conspay_pay_desc" maxlength="100" class="form-control form-control-sm">
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">วันที่ชำระ:</label>
										<div class="input-group input-group-sm">
											<div class="input-group-prepend date">
												<span class="input-group-text">
													<span class="fa fa-calendar-o"></span>
												</span>
											</div>
											<input type='text' name="conspay_pay_date" id="conspay_pay_date" class="form-control form-control-sm" placeholder="dd/mm/yyyy" />
										</div>								
									</div>		
								</div>
								<div class="form-group">
									<div class="controls">
										<div class="form-group">
											<label class="font-weight-bold">จำนวนเงิน:</label>
											<input type="text"  name="conspay_pay_amt" class="form-control form-control-sm">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">Remarks:</label>
										<input type="text" name="conspay_pay_cmmt" class="form-control form-control-sm" maxlength="255">
									</div>
								</div>
							</div>
						</div>
					</form>	
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-sm btn-outline-success" onclick='conspay_postform("<?php echo 'frm_add_conspay';?>")'>Save changes</button>				
			</div>
		</div>					
	</div>
</div>
<form name="frm_del_conspay" id="frm_del_conspay">
	<input type="hidden" name="action" value="del_conspay">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">		
	<input type="hidden" name="conspay_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
<?php }?>