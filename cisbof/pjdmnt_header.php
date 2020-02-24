<FORM >
	<h4 class="form-section text-primary" ><i class="fa fa-cube"></i> Project Information </h4>		
	<div class="row ml-1 mr-1 pb-2"><!-- border border-success rounded round-lg  -->
		<div class="col-lg-10 offset-lg-1 col-md-12  pl-2 pr-2">
			<div class="row">
				<div class="col-md-4  pt-1 font-weight-bold">Project Number :</div>
				<div class="col-md-8  pt-1 border-bottom"><? echo $pjm_nbr; ?></div>
			</div>
			<div class="row">
				<div class="col-md-4 pt-1 font-weight-bold">Project Name :</div>
				<div class="col-md-8 pt-1 border-bottom"><?php echo $pjm_name; ?></div>
			</div>
			<div class="row">
				<div class="col-md-4 pt-1 font-weight-bold">Project Type :</div>
				<div class="col-md-8 pt-1 border-bottom"><?php echo $pjt_name; ?></div>
			</div>
			<div class="row">
				<div class="col-md-4 pt-1 font-weight-bold">Project Address :</div>
				<div class="col-md-8 pt-1 border-bottom"><?php echo $pjm_addr; ?></div>
			</div>
			
			<div class="row">
				<div class="col-md-2 pt-1 font-weight-bold">District:</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_district; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Amphur :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_amphur; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Province :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_province; ?></div>
				
			</div>
			<div class="row">
				<div class="col-md-2 pt-1 font-weight-bold">Zip Code :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_zipcode; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Lat,Long :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_latitude.",".$pjm_longtitude; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Google Map :</div>
				<div class="col-md-2 pt-1 border-bottom">
					<?php if($pjm_latitude != "" and $pjm_longtitude !="")
					{ ?>
						<a href="https://www.google.com/maps/place/<? echo $pjm_latitude.",".$pjm_longtitude; ?>" target="_blank">Google Map</a>
					<?php 
					}
					else echo "-";
					?>
				</div>
				
			</div>
		</div>
	</div>	
	<div class="row ml-1 mr-1 p-1">
		<div class="col-lg-6 border-right ">
			<div class="row  p-1"> <!-- border border-success rounded round-lg -->
				<div class="col-lg-12">
					<h4 class="form-section text-primary" ><i class="fa fa-cube"></i> Customer Information </h4>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Customer Number :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $custpj_code; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Customer Name :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $custpj_name; ?></div>
					</div>		
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Customer Address :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $custpj_addr; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Line ID :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $custpj_lineid; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Email :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo$custpj_email; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Tel. :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $custpj_tel; ?></div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-lg-6 ">
			<div class="row p-1">
				<div class="col-lg-12">
					<h4 class="form-section text-primary" ><i class="fa fa-cube"></i> Contact Information </h4>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Contact Name :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $pjm_contact_name; ?></div>
					</div>		
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Contact Address :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $pjm_contact_addr; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Line ID :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $pjm_contact_lineid; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Email :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $pjm_contact_email; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Tel. :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $pjm_contact_tel; ?></div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	<h4 class="form-section text-primary" ><i class="fa fa-cube"></i> Project Detail</h4>		
	<div class="row  ml-1 mr-1 p-1">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-2 font-weight-bold">Start Date :</div>
				<div class="col-md-2 border-bottom"><?php echo dmytx($pjm_start_date); ?></div>
				<div class="col-md-2 font-weight-bold">End Date :</div>
				<div class="col-md-2 border-bottom"><?php echo dmytx($pjm_end_date); ?></div>
				<div class="col-md-2 font-weight-bold">Channel :</div>
				<div class="col-md-2 border-bottom"><?php echo $pjm_from_channel; ?></div>
			</div>
			<div class="row">
				<div class="col-md-2 pt-1 font-weight-bold">Budget :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_budget; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Discount (%) :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_per_disc; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Discount (Baht) :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo number_fmt($pjm_amt_disc); ?></div>
			</div>
			<div class="row">
				<div class="col-md-2 pt-1 font-weight-bold">Payment Term :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $paymth_name; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Buy SCG Product :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $buyscg_name; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">SCG Customer Code :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_buy_scg_custcode; ?></div>
			</div>
			<div class="row">
				<div class="col-md-2 pt-1 font-weight-bold">Deposit:</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo number_fmt($pjm_deposit_amt); ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Area Size :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $pjm_area_size; ?></div>
				<div class="col-md-2 pt-1 font-weight-bold">Site Consultant :</div>
				<div class="col-md-2 pt-1 border-bottom"><?php echo $sc_name; ?></div>
			</div>
		</div>
	</div>
</form>