	<section class="content">
        <div class="container-fluid">
			<form  id='form-prod-online' method="POST">
				
			<div class="row clearfix">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>INVENTORY
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;">Inventory</li>
									<li style="background-color:transparent!important;" class="active">Products</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="pull-right">				
						<button type="button" class="btn btn-xs bg-green waves-effect col-white"  id='show-prod' style="text-decoration: none;">
							<i class="material-icons">visibility</i> <span>SHOW ONLINE</span>
						</button>
						<button type="button" class="btn btn-xs bg-light-green waves-effect col-white" id='hide-prod' style="text-decoration: none;">
							<i class="material-icons">visibility_off</i> <span>HIDE ONLINE</span>
						</button>
					</div>
				</div>
			</div>

            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
							<div class="table-responsive">
							   <table class="table table-condensed table-hover" id="tblproducts">
                                    <thead>
                                        <tr class="col-green">		
											<th></th>
											<th><input type="checkbox" class="chk-col-green" id='chk-all-prod'/><label for="chk-all-prod"></label></th>
											<th>Category</th>
											<th>Subcategory</th>	
                                            <th>Name</th>					
											<th>Brand</th>
											<th>Description</th>			
											<th>Selling Price</th>			
											<th>Stock</th>			
											<th>Date Updated</th>				
											<th width="25%">Action</th>
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
			</form>
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
									<input type="date" name="modal-date-delivered" class="form-control" >
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
			<!-- PRODUCT DISCOUNT MODAL -->
			<div class="modal fade" id="modal-discount" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/tag_for_sale/products');?>" method="POST" id="form-edit-discount">
                        <div class="modal-header">
                            <h1 class="modal-title">Tag Product For Sale</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Discount Percent</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="hidden" name="modal-sku" class="form-control">
                                        <input type="hidden" name="modal-prod_id" class="form-control">
										<input type="number" name="modal-discount_percent" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-green waves-effect">Set Discount</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->	
			<!--
			data-href="'.base_url('admin/inventory/small_tags/'.$p->prod_id.'/'.$p->prod_var ).'"
			-->
			<div class="modal fade" id="modal-sm-tags" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/small_tags/');?>" method="POST" id="form-print-psm">
                        <div class="modal-header">
                            <h1 class="modal-title">Print Small Tags</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Stock on Hand</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="number" id="modal-sm-qty" name="modal-sm-qty" class="form-control" required>
										<input type="hidden" id="modal-sm-oqty" name="modal-sm-oqty" class="form-control" required>
										<input type="hidden" id="modal-sm-sku" name="modal-sm-sku" class="form-control">
                                        <input type="hidden" id="modal-sm-id" name="modal-sm-prod_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-print-psm" data-href="a" class="btn bg-green waves-effect open-print-window">PRINT</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->				
			<div class="modal fade" id="modal-lg-tags" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/large_tags/');?>" method="POST" id="form-print-plg">
                        <div class="modal-header">
                            <h1 class="modal-title">Print Large Tags</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Stock on Hand</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="number" id="modal-lg-qty" name="modal-lg-qty" class="form-control" required>
										<input type="hidden" id="modal-lg-oqty" name="modal-lg-oqty" class="form-control" required>
										<input type="hidden" id="modal-lg-sku" name="modal-lg-sku" class="form-control">
                                        <input type="hidden" id="modal-lg-id" name="modal-lg-prod_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-print-plg" data-href="a" class="btn bg-green waves-effect open-print-window">PRINT</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->	
			
        </div>		
    </section>