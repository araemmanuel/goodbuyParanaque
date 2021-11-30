
<style>
td {
    border-bottom: 1px solid #ddd;
	padding: 10px;
}
.prod-info {
	width: 100%;
	border: 1px solid #ddd;
}
</style>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>SALES MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/sales_management');?>">Sales Management</a></li>
							<li style="background-color:transparent!important;" class="active">Change Item</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="header">
                            <h2>CHANGE ITEM
								<small>Change item automatically cancels original sale and adds new sale.</small>
							</h2>
                        </div>
					
                        <div class="body">
						<div class="row clearfix" >
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div style="height:150px;">
									<video id="player3" width="100%" height = "100%" autoplay></video>
									<div class = 'validation-errors' id = "scan-error"></div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
								<table class="prod-info2">
								<span><b>ORIGINAL ITEM</b></span>
									<tbody>
										<tr>
											<td  class="font-11 align-left"><b>Product code</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['sku'];?></td>
											<td  class="font-11 align-left"><b>Name</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['name'];?></td>
											<td  class="font-11 align-left"><b>Variant</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['options'];?></td>
											<td  class="font-11 align-left"><b>Selling Price</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['selling_price'];?></td>
											<td  class="font-11 align-left"><b>Membership ID</b></td>
											<td  class="font-11 align-left"><?php echo ($orig_sale['membership_id'] != NULL) ? $orig_sale['membership_id']:"N/A";?></td>
										</tr>
										<tr>
											<td  class="font-11 align-left"><b>Amount Paid</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['amt_paid'];?></td>
											<td  class="font-11 align-left"><b>Quantity</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['qty'];?></td>
											<td  class="font-11 align-left"><b>Discount</b></td>
											<td  class="font-11 align-left"><?php echo $orig_sale['discount'];?></td>
											<td  class="font-11 align-left"><b>Used Points</b></td>
											<td  class="font-11 align-left"><?php echo ($orig_sale['used_pts'] != NULL) ? $orig_sale['used_pts']:"N/A";?></td>
											<td  class="font-11 align-left"><b>Used Points</b></td>
											<td  class="font-11 align-left"><?php echo ($orig_sale['gained_pts'] != NULL) ? $orig_sale['gained_pts']:"N/A";?></td>
											
										</tr>
										
									</tbody>
								</table>
							</div>		
						</div>
						<div class="row clearfix" >
							
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"  >
								<div id="div-prod-info" >
												<table class="prod-info">
													<tbody>
														<tr>
															<td width="50%" class="font-11 align-center"><b>NAME</b></td>
														</tr>
														<tr>
															<td><input style="text-align:center;border:none;border-color:transparent;width:100%;" id = 'name' readonly /></td>
														</tr>
														<tr>
															<td width="50%" class="font-11 align-center"><b>PRICE</b></td>
														</tr>
														<tr>										
															<td><input style="text-align:center;border:none;border-color:transparent;width:100%;" id = 'selling_price' readonly /></td>
														</tr>
													</tbody>
												</table>
											</div>
							</div>	
							<form action ="<?php echo base_url('admin/sales_management/add/'.$orig_invoice_no);?>" method="POST" id="form-change-item" >
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Product Code</small>
											<div class="form-line success">
												<input type="hidden" name="sales-date" value = "<?php echo $sales_date;?>" />
												<input type = "text" name="prod-code" id="prod-code" class="form-control" autocomplete='off' >
											</div>
										<div class = 'validation-errors' id="prod-code_error"><?php echo form_error('prod-code');?></div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-prod-code" style="width:330px;height:80px;overflow-y: scroll;" >
											<ul class="list-unstyled ul-ajax" id = "ul-prod-code" >
											</ul>
									</div>
								</div>
												
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Quantity</small>
											<div class="form-line success">
												<input type="number" name="prod-qty" id = "prod-qty" value="1" class="form-control" min='1' autocomplete="on">
											</div>
										<div class = 'validation-errors' id="prod-qty_error"><?php echo form_error('prod-qty');?></div>
									</div>
								</div>	
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Discounted Price</small>
											<div class="form-line success">
												<input type="number" name="prod-discounted-price" class="form-control" min="0.00" step="1">
											</div>
										<div class = 'validation-errors' id="prod-discounted-price_error"><?php echo form_error('prod-discounted-price');?></div>
									</div>
								</div>
												
							</div>
							<div class="row clearfix">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">		
							</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Membership ID</small>
										<div class="form-line success">
											<input type="text" name="card-no" id = "card-no" class="form-control" autocomplete="on">
										</div>
										<div class = 'validation-errors' id = "card-no_error"><?php echo form_error('card-no');?></div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="width:330px;height:80px;overflow-y: scroll;" id="div-card">
											<ul class="list-unstyled ul-ajax" id = "ul-card">
											</ul>
									</div>
								</div>	
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Use Pts</small>
											<div class="form-line success">
												<input type="number" name="reward-pts"  class="form-control" min="1" step="1">
											</div>
											<div class = 'validation-errors' id = "reward-pts_error"><?php echo form_error('reward-pts');?></div>
												<div id="db-reward-pts" style="color:green;"></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">		
							
								<div class="align-right">
										<button class="btn bg-green waves-effect" id="btn-change-item" type="button">
											<i class="material-icons">check</i> 
											<span>CHANGE ITEM</span>
										</button>
										&nbsp;
										<button class="btn waves-effect" type="button" onclick="window.location.href='<?php echo base_url('admin/sales_management');?>'">
											<i class="material-icons">close</i>
											<span>CANCEL</span>
										</button>
									</div>
								</div>	
						</div>
						
				<!--		
							<div class="row clearfix">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									
									
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div style="height:150px;">
												<video id="player3" width="100%" height = "100%" autoplay></video>
												<div class = 'validation-errors' id = "scan-error"></div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div id="div-prod-info" >
												<table class="prod-info">
													<tbody>
														<tr>
															<td width="50%" class="font-11 align-center"><b>NAME</b></td>
														</tr>
														<tr>
															<td><input style="text-align:center;border:none;border-color:transparent;width:100%;" id = 'name' readonly /></td>
														</tr>
														<tr>
															<td width="50%" class="font-11 align-center"><b>PRICE</b></td>
														</tr>
														<tr>										
															<td><input style="text-align:center;border:none;border-color:transparent;width:100%;" id = 'selling_price' readonly /></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Product Code</small>
											<div class="form-line success">
												<input type="hidden" name="sales-date" value = "<?php //echo $sales_date;?>" />
												<input type = "text" name="prod-code" id="prod-code" class="form-control" autocomplete='off' >
											</div>
										<div class = 'validation-errors' id="prod-code_error"><?php echo form_error('prod-code');?></div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-prod-code" style="width:330px;height:80px;overflow-y: scroll;" >
											<ul class="list-unstyled ul-ajax" id = "ul-prod-code" >
											</ul>
									</div>
								</div>
												
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Quantity</small>
											<div class="form-line success">
												<input type="number" name="prod-qty" id = "prod-qty" value="1" class="form-control" min='1' autocomplete="on">
											</div>
										<div class = 'validation-errors' id="prod-qty_error"><?php echo form_error('prod-qty');?></div>
									</div>
								</div>	
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Discounted Price</small>
											<div class="form-line success">
												<input type="number" name="prod-discounted-price" class="form-control" min="0.00" step="1">
											</div>
										<div class = 'validation-errors' id="prod-discounted-price_error"><?php echo form_error('prod-discounted-price');?></div>
									</div>
								</div>
												
							</div>
							<div class="row clearfix">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">		
							</div>	
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Membership ID</small>
										<div class="form-line success">
											<input type="text" name="card-no" id = "card-no" class="form-control" autocomplete="on">
										</div>
										<div class = 'validation-errors'><?php echo form_error('card-no');?></div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="width:330px;height:80px;overflow-y: scroll;" id="div-card">
											<ul class="list-unstyled ul-ajax" id = "ul-card">
											</ul>
									</div>
								</div>	
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Use Pts</small>
											<div class="form-line success">
												<input type="number" name="reward-pts"  class="form-control" min="1" step="1">
											</div>
											<div class = 'validation-errors'><?php echo form_error('reward-pts');?></div>
												<div id="db-reward-pts" style="color:green;"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									
								</div>
							</div>
						
							</form>
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="align-right">
										<button class="btn bg-green waves-effect" id="btn-change-item" type="button">
											<i class="material-icons">check</i> 
											<span>CHANGE ITEM</span>
										</button>
										&nbsp;
										<button class="btn waves-effect" type="button" onclick="window.location.href='<?php echo base_url('admin/sales_management');?>'">
											<i class="material-icons">close</i>
											<span>CANCEL</span>
										</button>
									</div>
								</div>
							</div>
								-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
		
    </section>
	