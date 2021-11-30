	<section class="content">
        <div class="container-fluid">
		<?php foreach($product_info as $i): ?>
            <div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<?php if($caller == 'products'): ?>
								<li style="background-color:+!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
								<?php $go_back_url = 'admin/inventory/products'; ?>
							<?php elseif($caller == "prod_add_form"): ?>
								<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/prod_add_form');?>">Add Product</a></li>
								<?php $go_back_url = 'admin/inventory/prod_add_form'; ?>
							<?php else : ?>
								<li style="background-color:+!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
								<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/product_variants/'.$i->prod_id);?>">Product Variants</a></li>						
								<?php $go_back_url = 'admin/inventory/product_variants/'.$i->prod_id; ?>
							<?php endif; ?>
							<li style="background-color:transparent!important;" class="active">Add Product Variant</li>
						</ol>
					</small>
				</h2>
            </div>
			
			<!-- ADD PRODUCT -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
								ADD PRODUCT VARIANT <small>Add new products to your inventory</small>
							</h2>
                        </div>
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
									<div class="col-lg-1 col-md-1 col-sm-0 col-xs-0"></div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
										<small>Product Name</small><br>
										<span class="font-20 col-green"><?=html_escape($i->name)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
										<small>Brand</small><br>
										<span class="font-20 col-green"><?=html_escape($i->brand)?></span>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
										<small>Description</small><br>
										<span class="font-20 col-green"><?=html_escape($i->description)?></span>
									</div>
								</div>
                            </div>
							<br>
							
							<?php //$go_back_url = str_replace('/', '-', $go_back_url); ?>
							<!--action="<?php //echo base_url('admin/inventory/prod_var_add/'.$i->prod_id);?>"-->
							<form  id="var-form" method="POST"  enctype="multipart/form-data">
							<input type="hidden" name="prod_cat" value="<?=$i->cat_name?>" />
							<input type="hidden" name="prod_subcat" value="<?=$i->subcat_name?>" />
							<input type="hidden" name="prod_id" value="<?=$i->prod_id?>" />
							
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<button type="button" id="open-cam-var-add" onclick="toggler('divCamera');" class="btn btn-block btn-xs bg-green waves-effect">
												<i class="material-icons">camera_alt</i>
												<span>ACCESS CAMERA</span>
											</button>
										</div>
									</div>
									<div class="row clearfix" ><!-- id="divCamera" style="display:none;"-->
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<img class="upload-preview" style="width:100%;height:100%;" />
											<div class="add-prod-other-pics"></div>
											<div class="browse-prod-other-pics"></div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<video id="var-player" style="width:100%;height:100%;"  autoplay ></video>
											<div class="align-center">
											<button type="button" id="btn-add-primary2"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE MAIN</button>		
											<button type="button" id="btn-add-other2"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE OTHERS</button>
											</div>
										</div>			
										
										<canvas id="canvas" width="200" height="150" style="display:none;"> </canvas>
									</div>
									
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Main Image: </small>
											<input type="file" name="primary_image"  class="primary-img">
											<div class='validation-errors'>
												<?php echo form_error('primary_image');?>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Other Product Images: </small>
											<input type="file" name="other_images[]" class="other-img" multiple>
										</div>
									</div>
									
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
												<table>
													<thead>
														<tr>
															<th style="padding:10px;">
																<a class="col-green" style="text-decoration:none;" id="open-attrib-type-modal" data-toggle="modal" data-target="#modal-add-attrib-type">
																	<span><small>Attribute Type</small> +</span>
																</a>
															</th>
														<?php foreach($attrib_types as $og): ?>
														<?php if(in_array($og->opt_grp_name, $prod_attrib_type)): ?>										
																<td class="col-grey" style="padding:10px;"><?=html_escape(ucfirst($og->opt_grp_name))?></td>
														<?php 
															 endif;
															endforeach; 
														?>
															</tr>
													</thead>
													<tbody>
														<tr>
															<th style="padding:10px;">
																<a class="col-light-green" style="text-decoration:none;" id="open-attrib-modal" data-toggle="modal" data-target="#modal-add-attrib-val">
																	<span><small>Attribute Value</small> +</span>
																</a>
															</th>
															<?php foreach($attrib_types as $og): ?>
														<?php if(in_array($og->opt_grp_name, $prod_attrib_type)): ?>										
															<td style="padding:10px;">
																<div class="form-group form-float">
																	<div class="form-line success">
																		<input type="text" id="var-add-<?=html_escape($og->opt_grp_id)?>" name = "<?=$og->opt_grp_name?>" class="form-control" />				
																	</div>
																</div>
															</td>
															<?php 
															 endif;
															endforeach; 
														?>
														</tr>
													</tbody>
													<tfoot>
														<div class='validation-errors' id="attrib_error"></div>	
													</tfoot>
												</table>
											</div>
										</div>
									</div>				
									<div class="row clearfix">
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<small class="form-label col-grey">Quantity</small>
													<div class="form-line success">
														<input type="number" name="prod_qty" class="form-control" min="1" required>
													</div>
													<div class='validation-errors' id = "quantity_error">
														<?php echo form_error('prod_qty');?>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<small class="form-label col-grey">Purchase Price</small>
													<div class="form-line success">
														<input type="number" name="prod_pprice" id = "purchase_price" class="form-control" min="0.00" step="0.01" required>
													</div>
													<div class='validation-errors' id = "purchase_price_error">
														<?php echo form_error('prod_pprice');?>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<small class="form-label col-grey">Selling Price</small>
													<div class="form-line success">
														<input type="number" name="prod_sprice" id = "selling_price" class="form-control" min="0" step="1" required>
													</div>
													<div class='validation-errors' id = "selling_price_error">
														<?php echo form_error('prod_sprice');?>
													</div>
												</div>
											</div>
									</div>
									
									<div class="row clearfix">
										<?php date_default_timezone_set('Asia/Manila');?>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group form-float">
												<small class="form-label col-grey">Date Delivered</small>
												<div class="form-line success">
													<input type="date" name='date_delivered' id="date_added" class="form-control" value = "<?php echo date("Y-m-d");?>"/>
												</div>
												<div class='validation-errors'>
													<?php echo form_error('date_delivered');?>
												</div>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<br>
											<div class="align-right">
												<button class="btn bg-green waves-effect"  type="button" data-href="<?php echo base_url('admin/inventory/prod_var_add/'.$i->prod_id);?>" id="btn-var-add">
													<i class="material-icons">add</i> 
													<span>ADD</span>
												</button>
												&nbsp;
												<a href="<?php echo base_url($go_back_url);?>" class="btn waves-effect" type="button" style="background-color:#ddd;color:#555!important;text-decoration:none;">
													<i class="material-icons">close</i>
													<span>CANCEL</span>
												</a>
											</div>
										</div>
									</div>
								</div>
								
								
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-condensed table-hover js-basic-example dataTable" id="dt-var-add">
											<thead>
												<th>Product Code</th>
												<th>Product Attribute</th>
												<th>Qty</th>
												<th>Selling Price</th>
												<th>Purchase Price</th>
											</thead>
											<tbody>
												<?php foreach($product_variants as $pv): ?>
												<tr style="cursor: pointer;">
													<td><?=html_escape($pv->sku)?></td>
													<td><?=html_escape($pv->options)?></td>
													<td class="qty"><?=html_escape($pv->quantity)?></td>
													<td class="sprice"><?=html_escape($pv->selling_price)?></td>
													<td class="pprice"><?=html_escape($pv->purchase_price)?></td>
												</tr>
												<?php endforeach;?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							</form>
                        </div>
                    </div>
                </div>
				<?php endforeach;?>
            </div>
            <!-- #END# ADD PRODUCT -->
				<!-- ADD ATTRIBUTE TYPE MODAL -->
			<div class="modal fade" id="modal-add-attrib-type" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
                            <h1 class="modal-title">Add Attribute Type</h1>
                        </div>
                        <div class="modal-body">
							<form action="<?php echo base_url('admin/inventory/modal_attrib_type_add');?>" id="form-attrib-type" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">ATTRIBUTE TYPE</small>
										<div class="form-line success">
											<input type="text" id="txt-edit" name="attrib_type_name" class="form-control">
										</div>
										<div class='validation-errors' id = "attrib_type_name_error"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
									<br>
									<button class="btn btn-block bg-green waves-effect" type="button" id="btn-attrib-type">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="attrib_type_tbl">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Name</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($attrib_types as $og):?>
                                        <tr>
                                            <td><?=$og->opt_grp_name?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-attrib-type" data-attrib-type-id='<?=$og->opt_grp_id?>' data-href="<?php echo base_url('admin/inventory/attrib_type_edit');?>">Edit</button>
												<button type="button" class="btn btn-xs btn-delete bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/inventory/attrib_type_del/'.$og->opt_grp_id);?>">Delete</button>
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
			<!-- #END# -->
			
			<!-- ADD ATTRIBUTE MODAL -->
			<div class="modal fade" id="modal-add-attrib-val" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
                            <h1 class="modal-title">Add Attribute Value</h1>
                        </div>
                        <div class="modal-body">
							<div class="row clearfix">
								<form action="<?php echo base_url('admin/inventory/modal_attrib_val_add');?>" id="form-attrib-val" method="POST">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<small class="form-label col-grey">Type</small>			
										<select class="form-control show-tick" name="attrib_type" data-live-search="true">
											<?php foreach($attrib_types as $og):?>
											<option><?=$og->opt_grp_name?></option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
										<div class="form-group form-float">
											<small class="form-label col-grey">ATTRIBUTE VALUE</small>
											<div class="form-line success">
												<input type="text"  name="attrib_val_name" class="form-control">
											</div>		
											<div class='validation-errors' id = "attrib_val_name_error"></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<br>
										<button class="btn btn-block bg-green waves-effect" id="btn-attrib-val" type="button">
											<i class="material-icons">add</i> 
											<span>ADD</span>
										</button>
									</div>
								</form>
							</div>
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="attrib_value_tbl">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Name</th>
											<th>Type</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($attrib_values as $ov):?>
                                        <tr>
                                            <td><?=$ov->opt_name?></td>
                                            <td><?=$ov->opt_grp_name?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-attrib-value" data-attrib-type="<?=$ov->opt_grp_name?>"  data-attrib-value-id="<?=$ov->opt_id?>" data-href="<?php echo base_url('admin/inventory/attrib_val_edit');?>">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm" data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/inventory/attrib_val_del/'.$ov->opt_id.'/var_add_form/'.$i->prod_id);?>">Delete</button>
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
			<!-- #END# -->
			
			
			<!-- EDIT OPTION TYPE MODAL -->
			<div class="modal fade" id="modal-edit-attrib-type" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form id="form-edit-attrib-type">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Attribute Type</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div class="form-line success">
									<input type="hidden" name="modal-opt_grp_id"/>
									<input type="text" class="form-control" name = "modal-opt_grp_name" >
								</div>
								<div class='validation-errors' id = "opt_grp_name_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-green waves-effect" id = "btn-edit-attrib-type"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
			
			<!-- EDIT OPTIONS MODAL -->
			<div class="modal fade" id="modal-edit-attrib-value" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form id="form-edit-attrib-val">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Attribute Values</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div>
									<select name="attrib-type" class="form-control selectpicker show-tick" id="modal-attrib-type" data-live-search="true">
									</select>
								</div>
								<br>
								<div class="form-line success">
									<input type="hidden" name="modal-opt_id"/>
									<input type="text" class="form-control" name="modal-opt_name">
								</div>
								<div class='validation-errors' id = "opt_name_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-edit-attrib-val" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
    </section>