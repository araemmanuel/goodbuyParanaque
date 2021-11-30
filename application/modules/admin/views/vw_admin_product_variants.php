	<section class="content">
        <div class="container-fluid">
		<?php foreach($product_info as $i): ?>
			<div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
							<li style="background-color:transparent!important;" class="active">Product Variants</li>
						</ol>
					</small>
				</h2>
			</div>
			
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="align-center">
						<a href="<?php echo base_url('admin/inventory/variant_add_form/'.$i->prod_id.'/product_variants');?>" class="btn btn-xs bg-green waves-effect col-white" style="text-decoration: none;">
							<i class="material-icons">add</i> <span>ADD PRODUCT VARIANT</span>
						</a>
						<a href="" id="open-edit-prod" class="btn btn-xs bg-green waves-effect col-white" style="text-decoration: none;" data-toggle="modal" data-target='#modal-edit-prod' data-prod-id = '<?=$i->prod_id?>' data-cat-name = '<?=$i->cat_name?>' data-cat-id = '<?=$i->cat_id?>' data-subcat-name = '<?=$i->subcat_name?>' data-sup-id = '<?=$i->sup_id?>' data-sup-name = '<?=$i->sup_name?>'>
							<i class="material-icons">edit</i> <span>EDIT PRODUCT</span>
						</a>
						<a  class="btn btn-xs bg-green waves-effect col-white confirm"  data-title="Are you sure you want to deactivate all variants of this product?" data-url="<?php echo base_url('admin/inventory/deactivate_all/'.$i->prod_id);?>" style="text-decoration: none;" >
							<i class="material-icons">close</i> <span>DEACTIVATE ALL</span>
						</a>
						<a href="" class="btn btn-xs bg-light-green waves-effect col-white" style="text-decoration: none;"  data-toggle="modal" data-target='#modal-discount-all'>
							<i class="material-icons">local_offer</i> <span>TAG ALL FOR SALE</span>
						</a>
						<a  class="btn btn-xs bg-amber waves-effect col-white"  data-title="Are you sure you want to show all variants of this product online?" href="<?php echo base_url('admin/inventory/show_all_online/'.$i->prod_id);?>" style="text-decoration: none;" >
							<i class="material-icons">visibility</i> <span>SHOW ALL ONLINE</span>
						</a>
						<a class="btn btn-xs bg-deep-orange waves-effect col-white"  data-title="Are you sure you want to hide all variants of this product online?" href="<?php echo base_url('admin/inventory/hide_all_online/'.$i->prod_id);?>" style="text-decoration: none;" >
							<i class="material-icons">visibility_off</i> <span>HIDE ALL ONLINE</span>
						</a>
					</div>
				</div>
			</div>
			<br>

            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
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
									<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
										<small>Description</small><br>
										<span class="font-20 col-green"><?=html_escape($i->description)?></span>
									</div>
								</div>
                            </div>
							<br>
							<div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable" id = "dt-prod-var">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Code</th>
											<th>Attributes</th>	
											<th>Stock</th>
											<th>Purchase Price</th>
											<th>Selling Price</th>											
											<th>Date Updated</th>											
											<th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($product_variants as $p):?>
                                        <tr>
                                            <td><?=html_escape($p->sku)?></td>
											<td align="center"><?=html_escape($p->options)?></td>
											<td align="center"><?=html_escape($p->quantity)?></td>
											<td align="center"><?=html_escape($p->purchase_price)?></td>
											<td align="center"><?=html_escape($p->selling_price)?></td>
											<td align="center"><?=html_escape($p->last_updated)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-default waves-effect btn-view-prod" data-prod-id ='<?=$p->prod_id?>' data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#product-info'>View</button>
												<?php if(!(strpos($p->sku, '-') !== false)): ?>
													<button type="button" class="btn btn-xs bg-green waves-effect open-add-qty" data-prod-id ='<?=$p->prod_id?>' data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#modal-add-qty' data-href="<?php echo base_url('admin/inventory/add_qty');?>">Add Qty</button>
												<?php endif; ?>
												
												<button type="button" class="btn btn-xs bg-default waves-effect" onclick="window.location.href='<?php echo base_url('admin/inventory/var_edit_form/'.$p->prod_id. '/' . $p->sku);?>'">Edit</button>
												<button type="button" class="btn btn-xs bg-green waves-effect confirm"  data-title="Are you sure you want to deactivate this product?" data-url="<?php echo base_url('admin/inventory/prod_del/'.$p->prod_id . '/' . $p->sku .'/product_variants');?>">Deactivate</button>
												<button type="button" class="btn btn-xs bg-default waves-effect open-prod-lg2" data-id="<?=$p->prod_id?>" data-sku="<?=$p->sku?>" data-qty="<?=$p->quantity?>">Large Tags</button>
											    <button type="button" class="btn btn-xs bg-green waves-effect open-prod-sm2" data-id="<?=$p->prod_id?>" data-sku="<?=$p->sku?>" data-qty="<?=$p->quantity?>">Small Tags</button>	
											    <button type="button" class="btn btn-xs bg-default waves-effect open-discount" data-prod-id ='<?=$p->prod_id?>'  data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#product-discount'>Tag for sale</button>
												<button type="button" class="btn btn-xs bg-default waves-effect open-print-window" data-href="<?php echo base_url('admin/inventory/single_tag/'.$p->prod_id.'/'.$p->sku);?>" >Single Tag</button>
												<?php if($p->is_customer_viewable == 0): ?>
													<a href="<?php echo base_url('admin/inventory/cust_view/'.$p->prod_id . '/' . $p->sku . '/product_variants');?>" class="btn btn-xs bg-light-green waves-effect col-white" >Show Online</button>
												<?php else: ?>
													<a href="<?php echo base_url('admin/inventory/cust_view/'.$p->prod_id . '/' . $p->sku . '/product_variants');?>" class="btn btn-xs bg-deep-orange waves-effect col-white" >Hide Online</button>
												<?php endif; ?>
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
									<input type="date" name="modal-date-delivered" class="form-control" >
								</div>
								<div class="validation-errors" id = "date_added_error"></div>
								<br>
								<small>Quantity</small>
								<div class="form-line success">
									<input type="number" name="modal-added-qty" min = '1' max = '100' class="form-control" >
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
			<div class="modal fade" id="modal-discount-all" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/tag_all_for_sale');?>" method="POST" id="form-discount-all">
                        <div class="modal-header">
                            <h1 class="modal-title">Tag Product For Sale</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Discount Percent</small>
						 <input type="hidden" name="prod_id"  value="<?=$i->prod_id?>" >
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="number" name="discount_percent" min="1" max="100" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"  class="btn bg-green waves-effect">Set Discount</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->	
			
			
			<!-- PRODUCT VARIANT DISCOUNT MODAL -->
			<div class="modal fade" id="modal-discount" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/tag_for_sale/product_variants');?>" method="POST" id="form-edit-discount">
                        <div class="modal-header">
                            <h1 class="modal-title">Tag Product Variant For Sale</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Discount Percent</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
                                        <input type="hidden" name="modal-prod_id" class="form-control">
										<input type="hidden" name="modal-sku" id ="modal-sku"/>	
										<input type="number" name="modal-discount_percent" min="1"  max="100" class="form-control" required>
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
			<!-- EDIT PRODUCT INFO MODAL -->
			<div class="modal fade js-sweetalert" id="modal-edit-prod" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-default" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								&nbsp;
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
							<b><span>EDIT PRODUCT INFO</span></b>
							<hr>
						  </div>		
                        <div class="modal-body">	
							<form action = "<?php echo base_url('admin/inventory/prod_edit_modal'); ?>" id = "form-edit-prod" method="POST" >
								<div class="row clearfix">
								<input type="hidden" name="modal-prod_id" class="form-control">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Category</small>
										<div class="form-line success">
										<select name = 'modal-cat_name' id = 'modal-edit-prod-cat' class="form-control show-tick selectpicker" data-live-search="true">
										</select>
										</div>
										<div class='validation-errors' id = "cat_name_error"></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Subcategory</small>
										<div class="form-line success">
										<select name = 'modal-subcat_name' id = 'modal-edit-prod-subcat' class="form-control show-tick selectpicker" data-live-search="true">
										</select>
										</div>
										<div class='validation-errors' id = "subcat_name_error"></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Supplier</small>
										<div class="form-line success">
										<select name = 'modal-sup_name' id = 'modal-edit-prod-sup' class="form-control show-tick selectpicker" data-live-search="true">
										</select>
										</div>
										<div class='validation-errors' id = "sup_name_error"></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Name</small>
											<div class="form-line success">
												<input type="text" name="modal-name" class="form-control">
											</div>
											<div class='validation-errors' id = "name_error"></div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Brand</small>
											<div class="form-line success">
												<input type="text" name="modal-brand" class="form-control">
											</div>
											<div class='validation-errors' id = "brand_error"></div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Description</small>
											<div class="form-line success">
												<input name="modal-description" cols="30" rows="2" class="form-control no-resize" >
											</div>
											<div class='validation-errors' id = "description_error"></div>
										</div>
									</div>
								</div>
								</form>
						</div>
						<div class="modal-footer">
                            <button type="button" id = 'btn-edit-prod'  class="btn bg-green waves-effect">SAVE</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
			
		<?php endforeach;?>
		<div class="modal fade" id="modal-sm2-tags" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/small_tags/');?>" method="POST" id="form-print-psm2">
                        <div class="modal-header">
                            <h1 class="modal-title">Print Small Tags</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Stock on Hand</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="number" id="modal-sm2-qty" name="modal-sm-qty" class="form-control" required>
										<input type="hidden" id="modal-sm2-oqty" name="modal-sm-oqty" class="form-control" required>
										<input type="hidden" id="modal-sm2-sku" name="modal-sm-sku" class="form-control">
                                        <input type="hidden" id="modal-sm2-id" name="modal-sm-prod_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-print-psm2" data-href="a" class="btn bg-green waves-effect open-print-window">PRINT</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->				
			<div class="modal fade" id="modal-lg2-tags" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/inventory/large_tags/');?>" method="POST" id="form-print-plg2">
                        <div class="modal-header">
                            <h1 class="modal-title">Print Large Tags</h1>
                        </div>
                        <div class="modal-body">
						 <small class="form-label">Stock on Hand</small>
							<div class="form-group form-float">
                                 <div class="form-line success">
										<input type="number" id="modal-lg2-qty" name="modal-lg-qty" class="form-control" required>
										<input type="hidden" id="modal-lg2-oqty" name="modal-lg-oqty" class="form-control" required>
										<input type="hidden" id="modal-lg2-sku" name="modal-lg-sku" class="form-control">
                                        <input type="hidden" id="modal-lg2-id" name="modal-lg-prod_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-print-plg2" data-href="a" class="btn bg-green waves-effect open-print-window">PRINT</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->	
        </div>		
    </section>