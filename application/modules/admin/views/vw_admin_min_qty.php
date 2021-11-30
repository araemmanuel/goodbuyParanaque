	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>DASHBOARD
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Dashboard</a></li>
									<li style="background-color:transparent!important;" class="active">Minimum Quantity</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				
			</div>
            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="header">
                            <h2>ITEMS THAT REACHED MINIMUM QUANTITY
								<small>The following products have reached the minimum quantity. Please order again on or before the reorder point date of each product.</small>
							</h2>
                        </div>
                        <div class="body">		
							<div class="row clearfix">
								<br>
								<form action = "<?php echo base_url('admin/min_qty'); ?>" method = "POST">
									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
										<div class="form-group form-float">
											<div class="form-line success">
												<label class="form-label">Reorder Point</label>
												<input type="number" name="reorder-point" min='0' class="form-control" value = "<?php if(isset($reorder_point)) echo $reorder_point;?>"/>
											</div>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
										<div class="form-group form-float">
											<input type="submit" class="btn bg-green waves-effect" value = "SET REORDER POINT" />	
										</div>
									</div>
								</form>
							</div>
							<div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-min-qty">
                                    <thead>
                                        <tr class="col-green">									   
											<th>Product Code</th>
											<th>Name</th>	
                                            <th>Attributes</th>					
											<th>Selling Price</th>
											<th>Quantity</th>										
											<th>Date Zeroed</th>										
											<th>Action</th>
                                        </tr>
                                    </thead>
                                 <tbody>
										<?php foreach($products as $p):?>
                                        <tr>
											<td><?=html_escape($p->sku)?></td>
											<td><?=html_escape($p->name)?></td>
											<td><?=html_escape($p->options)?></td>
                                            <td><?=html_escape($p->selling_price)?></td>
                                            <td><?=html_escape($p->quantity)?></td>
                                            <td><?=html_escape($p->date_zeroed)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-add-qty" data-prod-id ='<?=$p->prod_id?>' data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#modal-add-qty' data-href="<?php echo base_url('admin/inventory/add_qty');?>">Add Qty</button>
												<button type="button" class="btn btn-xs waves-effect confirm"  data-title="Are you sure you want to deactivate this product?" data-url="<?php echo base_url('admin/inventory/prod_del/'.$p->prod_id . '/' . $p->sku . '/min_qty/'.$reorder_point);?>">Deactivate</button>
											</td>
                                        </tr>
										<?php endforeach;?>
									</tbody> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# PRODUCTS -->
			<!-- ADD QTY MODAL -->
			<div class="modal fade" id="modal-add-qty" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-sm" role="document">
				   <div class="modal-content">
					<form method="POST" id="form-add-qty">
						<div class="modal-header">
							<h3 class="modal-title">Add Quantity</h3>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="modal-prod_id" id ="modal-prod_id"/>						
								<input type="hidden" name="modal-sku" id ="modal-sku"/>	
								<small>Date Delivered</small>
								<div class="form-line success">
									<input type="date" name="modal-date-delivered" id = "modal-date-delivered" class="form-control" >
								</div>
								<div class="validation-errors" id = "date_added_error"></div>
								<br>
								<small>Quantity</small>
								<div class="form-line success">
									<input type="number" name="modal-added-qty" class="form-control" >
								</div>
								<div class="validation-errors" id = "qty_error"></div>
							</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn bg-green waves-effect" id ="btn-add-qty"><i class="material-icons">check</i> <span>ADD QTY</span></button>
								<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
							</div>
					</div>
					</form>
				</div>
			</div>
			<!-- #END# -->
        </div>		
    </section>