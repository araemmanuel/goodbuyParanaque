	<section class="content">
        <div class="container-fluid">
			<form  id='form-prod-online' method="POST">
				
			<div class="row clearfix">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>STOCK HISTORY 
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
									<li style="background-color:transparent!important;" class="active">Stock History</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<!--
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<button type="button" class="btn btn-xs bg-green waves-effect open-add-qty" data-prod-id ='<?=$prod_id?>' data-toggle="modal" data-target='#modal-add-qty' data-href="<?php echo base_url('admin/inventory/add_qty');?>">Add Qty</button>
					</div>
				</div>
				-->
			</div>
            <!-- stock history -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
						<?php foreach($product_info as $i): ?>
						<div class="row clearfix" style="background:#eee;">
								<br>
								<div class="align-center">
									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
										<small>Category</small><br>
										<span class="font-20"><?=html_escape($i->cat_name)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
										<small>Subcategory</small><br>
										<span class="font-20"><?=html_escape($i->subcat_name)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
										<small>Product Name</small><br>
										<span class="font-20 col-green"><?=html_escape($i->name)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
										<small>Brand</small><br>
										<span class="font-20 col-green"><?=html_escape($i->brand)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
										<small>Supplier</small><br>
										<span class="font-20 col-green"><?=html_escape($i->sup_name)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
										<small>Description</small><br>
										<span class="font-20 col-green"><?=html_escape($i->description)?></span>
									</div>
								</div>
                            </div>
							<?php endforeach; ?>
							<br>
							<div class="table-responsive">
							   <table class="table table-condensed table-hover" id="dt-history">
                                    <thead>
                                        <tr class="col-green">		
                                            <th></th>			
											<th>Date Purchased</th>			
											<th>Product Code</th>
											<th>Attributes</th>
											<th>Quantity</th>		
											<th>Action</th>													
										</tr>
                                    </thead>
									<tbody>
										<?php foreach($history as $h) : ?>
										<tr>
											<td><?=html_escape($h->sh_id)?></td>
											<td><?=html_escape(date("M d, Y", strtotime($h->date_added)))?></td>
											<td><?=html_escape($h->sku)?></td>
											<td><?=html_escape($h->options)?></td>
											<td><?=html_escape($h->qty)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-sh-qty" data-toggle="modal" data-target="#modal-sh-qty" data-sh-qty = "<?=html_escape($h->qty)?>" data-sku = "<?=html_escape($h->sku)?>" data-sh-id="<?=html_escape($h->sh_id)?>" data-href="<?php echo base_url('admin/inventory/edit_sh_qty');?>">Edit</button>	
												<button type="button" class="btn btn-xs bg-default waves-effect confirm" data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/inventory/del_sh_qty/'.$h->sh_id.'/'.$h->sku);?>">Delete</button>
											</td>
										</tr>
										<?php endforeach; ?>
								
									</tbody> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			</form>
            <!-- #END# PRODUCTS -->
			
        </div>	
			<!-- QTY MODAL -->
			<div class="modal fade" id="modal-sh-qty" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-sm" role="document">
				   <div class="modal-content">
					<form method="POST" id="form-sh-qty">
						<div class="modal-header">
							<h3 class="modal-title">Edit Quantity</h3>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="modal-sh-id" id ="modal-sh-id"/>						
								<input type="hidden" name="modal-sku" id ="modal-sku"/>	
								<small>Quantity</small>
								<div class="form-line success">
									<input type="number" name="modal-sh-qty" id="sh-qty" class="form-control" >
								</div>
								<div class="validation-errors" id = "qty_error"></div>
							</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn bg-green waves-effect" id ="btn-sh-qty"><i class="material-icons">check</i> <span>SAVE</span></button>
								<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
			<!-- #END# -->		
    </section>