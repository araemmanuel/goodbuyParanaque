<?php $sales_date = $this->session->flashdata('sales_date');

	if($this->session->flashdata('sales_date'))
		$this->session->set_flashdata('sales_date', $sales_date);

?>

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
							<li style="background-color:transparent!important;" class="active">Sales Management</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="header">
							<h2>SALES
								<input type="text" id="selected-sales-date" style="border: none;width:500px;border-color: transparent; background-color:transparent;" value = "<?php if(isset($sales_date) && strtotime($sales_date) != 0) echo ' FOR ' . strtoupper(date ('F d, Y', strtotime($sales_date)));?>" readonly>
							</h2>
                        </div>
					
                        <div class="body">
							<div class="row clearfix">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<form action ="<?php echo base_url('admin/sales_management/display');?>" method="POST" id="form-sales-date">
											<div class="form-group form-float">
												<small class="form-label col-grey">Enter Sales Date</small>
												<div class="form-line success">
													<input type="date" name="sales-date" id = "sales-date" class="form-control" value = "<?php if(isset($sales_date) && strtotime($sales_date) != 0) echo date('Y-m-d',strtotime($sales_date)); ?>"/>
												</div>
											</div>
											</form>
										</div>
									</div>
									
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div style="height:150px;">
												<video id="player2" width="100%" height = "100%" autoplay></video>
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
									
									<div class="row clearfix">
										<form action ="<?php echo base_url('admin/sales_management/add');?>" method="POST" id="form-add-sales" >
										<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
											<div class="row clearfix">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<div class="form-group form-float">
														<small class="form-label col-grey">Product Code</small>
														<div class="form-line success">
															<input type="hidden" name="sales-date" value = "<?php echo $sales_date;?>" />	
															<input type = "text" name="prod-code" id="prod-code" class="form-control" autocomplete='off' autofocus>
														</div>
														<div class = 'validation-errors' id="prod-code_error"><?php echo form_error('prod-code');?></div>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-prod-code" style="width:330px;height:80px;overflow-y: scroll;" >
														<ul class="list-unstyled ul-ajax" id = "ul-prod-code" >
														</ul>
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
													<div class="form-group form-float">
														<small class="form-label col-grey">Quantity</small>
														<div class="form-line success">
															<input type="number" name="prod-qty" id = "prod-qty" value="1" class="form-control" min='1' autocomplete="on">
														</div>
														<div class = 'validation-errors' id="prod-qty_error"><?php echo form_error('prod-qty');?></div>
													</div>
												</div>	
												<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
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
												<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
													<div class="form-group form-float">
														<small class="form-label col-grey">Membership ID</small>
														<div class="form-line success">
															<input type="text" name="card-no" id = "card-no" class="form-control" autocomplete="on">
														</div>
														<div class = 'validation-errors' id = "card-no_error"><?php echo form_error('card-no');?></div>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="width:330px;height:80px;overflow-y: scroll;" id="div-card">
														<ul class="list-unstyled ul-ajax" id = "ul-card">
														<!--<li class="li-prod-name">Wacc3  Attributes: Blue</li>-->
														</ul>
													</div>
												</div>	
												<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
													<div class="form-group form-float">
													<small class="form-label col-grey">Use Pts</small>
														<div class="form-line success">
															<input type="number" name="reward-pts"  class="form-control" min="1" step="1">
														</div>
														<div class = 'validation-errors' id = "reward-pts_error"><?php echo form_error('reward-pts');?></div>
														<div id="db-reward-pts" style="color:green;"></div>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<button type="button" id="btn-add-sale" class="btn btn-block bg-green waves-effect">
														<i class="material-icons">add</i> 
														<span>ADD</span>
													</button>
												</div>
												</div>
										</div>
										</form>
									</div>
								</div>
								
								
								<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs tab-col-green tab-nav-right" role="tablist">
										<li role="presentation" class="active" style="background:transparent!important;"><a href="#sales" data-toggle="tab">SALES</a></li>
										<li role="presentation" style="background:transparent!important;"><a href="#products" data-toggle="tab">PRODUCTS</a></li>
									</ul>
									<!-- js-basic-example dataTable -->
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane fade in active" id="sales">									
											<div class="table-responsive">
												<table class="table table-condensed table-hover" id='dt-sales'>
													<thead>
														<tr class="col-green"> 
															<th>Invoice No.</th>							
															<th>Code</th>
															<th>Name</th>
															<th>Qty</th>
															<th>Individual Price</th>
															<th>Points Used</th>
															<th>Discount</th>
															<th>Amount Paid</th>
															<th>Sold From</th>
															<th>Action</th>
														</tr>
													</thead>
													<?php $sale_total = $discount_total = $pts_total = 0; ?>
													<tbody>
														<?php foreach($sales as $s): ?>
														<tr>
															<td><?=html_escape($s->invoice_no)?></td>
															<td><?=html_escape($s->sku)?></td>
															<td><?=html_escape($s->name)?></td>
															<td><?=html_escape($s->qty)?></td>
															<td><?=html_escape($s->selling_price)?></td>
															
															<?php if($s->pts_used): ?>
																<?php $s->pts_used = $s->pts_used;?>
															<?php else: ?>
																<?php $s->pts_used = 0;?>
															<?php endif; ?>
															
															<td><?=html_escape($s->pts_used)?></td>
															<td><?=html_escape($s->discount)?></td>		
															<td><?=html_escape($s->amt_paid)?></td>
															<?php if($s->is_sold_from_store == 1):?>
																<td>STORE</td>
															<?php else: ?>
																<td>ONLINE</td>
															<?php endif; ?>	
															<td>
																<button type="button" class="btn btn-xs bg-green waves-effect open-edit-sale"  data-invoice-no = "<?=$s->invoice_no?>" data-href="<?php echo base_url('admin/sales_management/edit/'.$s->invoice_no);?>"  data-toggle="modal" data-target="#modal-edit-sale">Edit</button>
																<button type="button" class="btn btn-xs bg-red waves-effect confirm"  data-title="Are you sure you want to delete this sale record?" data-url="<?php echo base_url('admin/sales_management/del/'.$s->invoice_no. '/'. $s->sku . '/'. $s->qty  . '/'. $sales_date);?>">Void</button>
																<a href ="<?php echo base_url("admin/sales_management/change_item/$s->invoice_no");?>" class="btn btn-xs bg-default waves-effect" style="background-color:#ddd;color:#555!important;">Change Item</a>											
															</td>
														</tr>
														<?php 
																$sale_total += $s->amt_paid;
																$discount_total += $s->discount;
																$pts_total += $s->pts_used;
																
																//$amt_total += $s->cash;									
															endforeach; 
														?>
													</tbody>
													<tfoot>
														<tr>
															<th>Total</th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th>₱<?php echo number_format($pts_total, 2);?></th>
															<th>₱<?php echo number_format($discount_total, 2);?></th>
															<th>₱<?php echo number_format($sale_total, 2);?></th>
															<!--<th><?php echo number_format($amt_total, 2);?></th>-->
															<th></th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
										
										<div role="tabpanel" class="tab-pane fade" id="products">
											<div class="table-responsive">
												<table class="table table-condensed table-hover" id="dt-all-products"> <!-- id='dt-products-sales'-->
													<thead>
														<tr class="col-green">	
															<th width="10%">Category</th>
															<th>Subcategory</th>
															<th>Code</th>
															<th>Product</th>
															<th>Qty</th>
															<th>Attributes</th>
															<th>Selling Price</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
			
			<!-- EDIT SALES MODAL -->
			<div class="modal fade" id="modal-edit-sale" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
						<form method="POST" id="form-edit-sale">
							<div class="modal-header">
								<h1 class="modal-title">Edit Sale</h1>
							</div>
							<div class="modal-body">
								<div class="form-group">
								<input type="hidden" name="modal-invoice_no" class="form-control" />
								<input type="hidden" name="modal-date" class="form-control" />
								<small class="form-label">Product Code</small>
									<div class="form-line success">		
										<input type="text" name="modal-sku" class="form-control"  readonly>
									</div>	
									<div class="validation-errors" id = "sku_error"></div>
									<br>
									<small class="form-label">Quantity</small>
									<div class="form-line success">
										<input type="number" name="modal-qty" min='1' class="form-control" >
									</div>
									<div class="validation-errors" id = "qty_error"></div>
									<br>		
									<small class="form-label">Discounted Price</small>
									<div class="form-line success">
										<input type="number" name="modal-amt_paid" class="form-control" />
									</div>
									<input type="hidden" name ='sales-date' value ="<?php echo $sales_date;?>"/>
									<div class="validation-errors" id = "amt_paid_error"></div>
									<br>	
									
									<small class="form-label">Membership ID</small>
									<div class="form-line success">
										<input type="text" name="modal-membership_id" class="form-control" />
									</div>
									<div class="validation-errors" id = "membership_id_error"></div>
									<br>		
									<small class="form-label">Reward Points</small>
									<div class="form-line success">
										<input type="number" name="modal-reward_points" class="form-control" />
									</div>
									<div class="validation-errors" id = "reward_points_error"></div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" id = 'btn-edit-sale' class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
								<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cancel</button>
							</div>
						</form>
					</div>
                </div>
            </div>
			<!-- #END# -->
    </section>
	