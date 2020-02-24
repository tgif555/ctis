<!-- Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content bg-danger">
			<div class="modal-header">
				<h4 class="modal-title" id="msghead">Message</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id='modal-body' class="modal-body text-sm">
				<p></p>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--ADD Quotation Modal-->
<div class="modal fade text-left" id="div_add_qtm_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content p-0">
			<div class="modal-header bg-success white">
				<h4 class="modal-title" id="myModalLabel9"><i class="feather icon-shopping-cart"></i> Add Existing Quotation</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">					
					<form id="frm_add_qtm_project" name="frm_add_qtm_project" autocomplete=OFF>	
						<input type="hidden" name="action" value="add_qtm_project">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">	
						<input type="hidden" name="pjm_nbr" value="<?php echo $pjm_nbr; ?>">						
						<input type="hidden" name="pg" value="<?php echo $pg;?>">
						<div class="row">
							<div class="col-md-12 col-sm-12 small">
								<div class="form-group">
									<div class="controls">
										<div class="form-group text-center">
											<label class="font-weight-bold font-large-1">Search Quotation</label>
											<div class="input-group input-group-sm">
												<input type="text" id="search_qtm"  name="search_qtm"  maxlength="30" class="form-control form-control-sm">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 small">
								<div class="form-group">
									<div class="controls">
										<div class="form-group">
											<label class="font-weight-bold">Quotation Number</label>
											<div class="input-group input-group-sm">
												<input type="text" id="qtm_nbr"  name="qtm_nbr" readonly maxlength="30" class="form-control form-control-sm">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 small">
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">Quotation Name</label>
										<input type="text" name="qtm_name" id="qtm_name"  readonly maxlength="30" class="form-control form-control-sm">
									</div>
								</div>									
							</div>
						</div>
					</form>	
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-sm btn-outline-success" onclick='pjm_qtm_postform("<?php echo 'frm_add_qtm_project';?>")'>Save changes</button>				
			</div>
		</div>					
	</div>
</div>

<div id ="calendar-view" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<h4 class="modal-title">รายละเอียดการนัดหมายลูกค้า</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body">
				
				<div >
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="title">เลขที่ :</label>
                        <label  name="id" id="id" ></label>
                    </div>
                </div>
                <div >
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="title">ชื่อ-นามสกุล :</label>
                        <label  name="title" id="title" ></label>
                    </div>
                </div>
                <div >
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="starts-at">วันที่นัดหมาย :</label>
                        <label type="text" name="starts_at" id="starts-at" ></label>
                    </div>
                </div>
                <div >
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="tel">เบอร์ติดต่อ :</label>
                        <label type="text" name="tel" id="tel"></label>
                    </div>
                </div>
				 <div >
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="address">ที่อยู่ :</label>
                        <label type="text" name="address" id="address"></label>
                    </div>
                </div>
				 <div >
					
                    <div class="col-xs-12">
                        <label class="col-xs-4" for="addressmap">Link แผนที่ :</label>
						<a href=""id="addressmaplink" name="addressmaplink" >click</a>
                    </div>
					  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->