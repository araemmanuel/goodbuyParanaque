	<section class="content">
        <div class="container-fluid">
            
			<div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
							<li style="background-color:transparent!important;" class="active">Edit Product</li>
						</ol>
					</small>
				</h2>
            </div>
			
			<!-- EDIT PRODUCT -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT PRODUCT
								<small>Edit product details</small>
							</h2>
                        </div>
						
                        <div class="body">
							<?php foreach($product as $p): ?>
							<div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<button type="button" id="open-cam-prod-edit" class="btn btn-xs btn-block bg-green waves-effect">
										<i class="material-icons">camera_alt</i>
										<span>ACCESS CAMERA</span>
									</button>
									<br><br>
									
									<div>
										<?php 
										if (strcasecmp('None', $p->primary_image) == 0): ?>
											<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" class="main-holder upload-preview" style="width:100%;height:200px;">
										<?php else: ?>
											<img src="<?php echo base_url($p->primary_image);?>" class="main-holder upload-preview" style="width:100%;height:200px;">
										<?php endif;?>
									</div>
									
									<div class="other-product-pics">
										<form action = "<?php echo base_url('admin/inventory/delete_file/'.$prod_id.'/'.$sku.'/prod');?>" method="POST" id="del-image">	
											<div class="product-pic-small">
											<?php if ($p->other_images): ?>
												<?php if (strcasecmp('None', $p->primary_image) == 0): ?>
													<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" name="primary_image" class="other-pics">
												<?php else: ?>
													<img src="<?php echo base_url($p->primary_image);?>" name="primary_image" class="other-pics">
													<input type="hidden" name="img_path" value = "<?=$p->primary_image?>"><br>
													<span class="align-center"><input type="submit" class="link" value="Delete" style="font-size:11px;text-decoration:none;color:#F44336;"/></span>
												<?php endif;?>
											<?php endif;?>	
											</div>	
										</form>
										<?php 
											
										$other_pics = explode(',',$p->other_images);				
										if($p->other_images) :
										for($i=0;$i<count($other_pics);$i++): ?>
										<form action = "<?php echo base_url('admin/inventory/delete_file/'.$prod_id.'/'.$sku.'/prod');?>" method="POST" id="del-image">
											<input type="hidden" name="img_path" value = "<?=$other_pics[$i]?>">
											<div class="product-pic-small">
												<img src="<?php echo base_url($other_pics[$i]);?>" class="other-pics"><br>
												<input type="submit" class="link" value="Delete" style="font-size:11px;text-decoration:none;color:#F44336;"/>
											</div>
										</form>									
										<?php endfor;
											  endif;
										?>	
									</div>
									<div class="add-prod-other-pics"></div>
									<div class="browse-prod-other-pics">
									</div>
									<div>
										<video id="prod-player2" width="100%" height = "100%" autoplay></video>
									</div>
									<div class="align-center">
										<canvas id = "canvas" width="200" height="150" style="display:none;"> </canvas>
											<button type="button" id="btn-add-primary4"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE MAIN</button>		
											<button type="button" id="btn-add-other4"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE OTHERS</button>		
									</div>
								</div>
							
								<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
									<form action="<?php echo base_url('admin/inventory/prod_edit');?>" id="form-edit-prod" method="POST" enctype="multipart/form-data">
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><!-- id="frmFileUpload" class="dropzone" -->
											<small class="form-label col-grey">Main Product Image: </small>
											<input type="file" name="primary_image"  class="primary-img" accept="image/*" capture="camera" />
											<div class='validation-errors'>
												<?php echo form_error('primary_image');?>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Edit Other Product Images: </small>		
											<input type="file" name="other_images[]" class="other-img"  accept="image/*" capture="camera" multiple>																								
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Add Other Product Images: </small>		
											<input type="file" name="add_other_images[]" class="other-img"  accept="image/*" capture="camera" multiple>																								
										</div>
									</div>
							
									<div class="row clearfix">
										<input type ="hidden" name="prod_id" value="<?php echo $prod_id;?>">
										<input type ="hidden" name="sku" value="<?php echo $sku;?>">							
										
										<div class="col-lg-3 col-md-3 col-sm-9 col-xs-9">
												<div class="form-group form-float">
													<small class="form-label col-grey">Category</small>
													<select name = "prod_cat" id="cat_id2" class="show-tick selectpicker form-control" data-selected-subcat="<?=$p->subcat_name?>" data-live-search="true">
														<option>- Please select -</option>
														<?php foreach($categories as $c):?>
															<?php if(strcasecmp($c->cat_name, $p->cat_name)  == 0):?>
																<option value="<?=html_escape($c->cat_id)?>" selected><?=html_escape($c->cat_name)?></option>	
															<?php else:?>
																<option value="<?=html_escape($c->cat_id)?>"><?=html_escape($c->cat_name)?></option>
															<?php endif;?>
														<?php endforeach;?>
													</select>
													<div class='validation-errors'>
														<?php echo form_error('prod_cat');?>
													</div>
												</div>
										</div>
										<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3">
												<div class="align-left" data-toggle="tooltip" data-placement="bottom" title="Add Category">
													<br>
													<a class="btn bg-light-green btn-circle waves-effect waves-circle waves-float" id = "open-cat-modal" data-toggle="modal" data-target="#modal-cat">
														<i class="material-icons">add</i>
													</a>
												</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-9 col-xs-9">
												<div class="form-group form-float">
													<small class="form-label col-grey">Subcategory</small>
													<select name = "prod_subcat" id="subcat_name2"  class="show-tick selectpicker form-control" data-live-search="true">
													<?php foreach($subcategories as $sc):?>
														<?php if(strcasecmp($sc->subcat_name, $p->subcat_name) == 0):?>
															<option selected><?=html_escape($sc->subcat_name)?></option>	
														<?php else:?>
															<option><?=html_escape($sc->subcat_name)?></option>
														<?php endif;?>
													<?php endforeach;?>
													</select>
													<div class='validation-errors'>
														<?php echo form_error('prod_subcat');?>
													</div>
												</div>
										</div>
										<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3">
												<div class="align-left" data-toggle="tooltip" data-placement="bottom" title="Add Subcategory">
												<br>
													<a class="btn bg-green btn-circle waves-effect waves-circle waves-float" id = "open-subcat-modal" data-toggle="modal" data-target="#modal-subcat">
														<i class="material-icons col-green">add</i>
													</a>
												</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-9 col-xs-9">
											<div class="form-group form-float">
												<small class="form-label col-grey">Supplier</small>
												<select name = "prod_sup" id="cat_id" class="show-tick selectpicker form-control" data-live-search="true">
													<option>- Please select -</option>
													<?php foreach($suppliers as $s):?>
														<?php if(strcasecmp($s->name, $p->sup_name) == 0):?>
															<option selected><?=html_escape($s->name)?></option>	
														<?php else:?>
															<option><?=html_escape($s->name)?></option>
														<?php endif;?>
													<?php endforeach;?>
												</select>
												<div class='validation-errors' id = "sup_name_error">
												</div>
											</div>
										</div>
										<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3">
											<div class="align-left" data-toggle="tooltip" data-placement="bottom" title="Add Supplier">
												<br>
												<a class="btn bg-light-green btn-circle waves-effect waves-circle waves-float" id = "open-sup-modal" data-toggle="modal" data-target="#modal-add-sup">
													<i class="material-icons col-green">add</i>
												</a>
											</div>
										</div>
									</div>
										
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<div class="form-line success">
														<label class="form-label">Name</label>
														<input type="text" name="prod_name" class="form-control" value ="<?=html_escape($p->name)?>" required>
													</div>
													<div class='validation-errors' id="name_error">
														<?php echo form_error('prod_name');?>
													</div>
												</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<div class="form-line success">
														<label class="form-label">Brand</label>
														<input type="text" name="prod_brand" value ="<?=html_escape($p->brand)?>" class="form-control">
													</div>
													<div class='validation-errors' id="brand_error">
														<?php echo form_error('prod_brand');?>
													</div>
												</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<div class="form-group form-float">
													<div class="form-line success">
														<label class="form-label">Description</label>
														<input type="text" name="prod_desc" value ="<?=html_escape($p->description)?>" class="form-control no-resize"/>
													</div>
													<div class='validation-errors' id="description_error">
														<?php echo form_error('prod_desc');?>
													</div>
												</div>
										</div>
									</div>
									
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group form-float">
												<div class="form-line success">
													<label class="form-label">Quantity</label>
													<input type="number" name="prod_qty" value ="<?=html_escape($p->quantity)?>" class="form-control" min="0.00" step="0.01" disabled>
												</div>
												<div class='validation-errors' id="quantity_error">
													<?php echo form_error('prod_qty');?>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group form-float">
												<div class="form-line success">
													<label class="form-label">Purchase Price</label>
													<input type="number" name="prod_pprice" value ="<?=html_escape($p->purchase_price)?>" class="form-control" min="0.00" step="0.01" required>
												</div>
												<div class='validation-errors' id="purchase_price_error">
													<?php echo form_error('prod_pprice');?>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group form-float">
												<div class="form-line success">
													<label class="form-label">Selling Price</label>
													<input type="number" name="prod_sprice" value ="<?=html_escape($p->selling_price)?>" class="form-control" min="0" step="1" required>
												</div>
												<div class='validation-errors' id="selling_price_error">
													<?php echo form_error('prod_sprice');?>
												</div>
											</div>
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
															<td class="col-grey" style="padding:10px;">Color</td>
															<td class="col-grey" style="padding:10px;">Size</td>
															<td class="col-grey" style="padding:10px;">Package Type</td>
														</tr>
													</thead>
													<tbody>
														<tr>
															<th style="padding:10px;">
																<a class="col-light-green" style="text-decoration:none;" id="open-attrib-modal" data-toggle="modal" data-target="#modal-add-attrib-val">
																	<span><small>Attribute Value</small> +</span>
																</a>
															</th>
															
														<?php	
															foreach($attrib_types as $og):
														
															foreach($prod_attrib_values as $pav)
															{
																if($og->opt_grp_id == $pav->opt_grp_id)
																{
																	$val = $pav->opt_name;
																	break;
																}
																else
																	$val = null;
															}
														?>													
															<td style="padding:10px;">
																<div class="form-group form-float">
																	<div class="form-line success">
																		<input type="text" id="prod-edit-<?=html_escape($og->opt_grp_id)?>" value="<?php echo $val;?>"  name = "<?=$og->opt_grp_name?>" class="form-control input-attrib" />				
																	</div>
																</div>
															</td>
															<?php endforeach;?>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="align-right">
												<!--  id="btn-prod-edit" -->
												<button class="btn bg-green waves-effect" id="btn-prod-edit" type="button" data-href="<?php echo base_url('admin/inventory/prod_edit');?>">
													<i class="material-icons">check</i> 
													<span>SAVE</span>
												</button>
												&nbsp;
												<button type="button" onclick="window.location.href='<?php echo base_url('admin/inventory/products');?>'" class="btn waves-effect" >
													<i class="material-icons">close</i>
													<span>CANCEL</span>
												</button>
											</div>
										</div>	
									</div>
									<?php endforeach; ?>
									</form>
								</div>
							</div>
							
						</div>
                    </div>
                </div>
            </div>
            <!-- #END# EDIT  PRODUCT -->
			
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
                                <table class="table table-condensed table-hover js-basic-example dataTable">
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
												<input type="text" name="attrib_val_name" class="form-control">
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
                                <table class="table table-condensed table-hover js-basic-example dataTable">
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
												<button type="button" class="btn btn-xs bg-default waves-effect confirm" data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/inventory/attrib_val_del/'.$ov->opt_id.'/prod_add_form');?>">Delete</button>
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
			
			<!-- ADD SUPPLIER MODAL -->
			<div class="modal fade" id="modal-add-sup" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
                            <h1 class="modal-title">Add Supplier</h1>
                        </div>
                        <div class="modal-body">
							<form action ="<?php echo base_url('admin/inventory/add_supplier');?>" id="form-add-sup" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Supplier Name</small>
										<div class="form-line success">
											<input type="text" name = "supplier"  class="form-control">
										</div>
										<div class="validation-errors" id="supplier_error"></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Contact No.</small>
										<div class="form-line success">
											<input type="text" name = "sup-contact"  class="form-control">
										</div>
										<div class="validation-errors" id="sup-contact_error"></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Address</small>
										<div class="form-line success">
											<input type="text" name = "sup-address"  class="form-control">
										</div>
										<div class="validation-errors" id="sup-address_error"></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
									<br>
									<button class="btn btn-block bg-green waves-effect" type="button" id="modal-btn-add-sup">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="dt-modal-sup">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Supplier Name</th>
											<th>Contact No</th>
											<th>Address</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($suppliers as $s) : ?>
                                        <tr>
											<td><?=html_escape($s->name)?></td>
											<td><?=html_escape($s->contact)?></td>
											<td><?=html_escape($s->address)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-sup" data-sup-id = "<?=html_escape($s->id)?>" data-name = "<?=html_escape($s->name)?>" data-contact = "<?=html_escape($s->contact)?>" data-address = "<?=html_escape($s->address)?>" data-toggle="modal" data-target="#modal-edit-sup">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg="This action cannot be undone." data-url="<?php echo base_url('admin/inventory/del_supplier/'.$s->id.'/prod_add_form');?>">Delete</button>
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
			
			<!-- ADD CATEGORY MODAL -->
			<div class="modal fade" id="modal-cat" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
                            <h1 class="modal-title">Add Category</h1>
                        </div>
                        <div class="modal-body">
							<form action = "<?php echo base_url('admin/category/modal_cat_add');?>" id="form-add-cat" method="POST">
							<div class="row clearfix">
								<div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
									<div class="form-group form-float">
										<div class="form-line success">
											<label class="form-label col-grey">Category Name</label>
											<input type="text" name = "cat_name" id="txt-edit" class="form-control">
										</div>
										<div class='validation-errors' id = "cat_name_error"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-5 col-xs-5">
									<button class="btn btn-block bg-green waves-effect" type="button" id='btn-add-cat'>
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
							<br>
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
											<th>Code</th>
                                            <th>Name</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($categories as $c):?>
										<tr>
										<td><?=html_escape($c->cat_code)?></td>
										<td><?=html_escape($c->cat_name)?></td>
										<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-cat" data-cat-id = "<?=html_escape($c->cat_id)?>" data-toggle="modal" data-target="#modal-edit-cat">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/category/del/'.$c->cat_id.'/prod_add_form');?>">Delete</button>
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
			
			<!-- ADD SUBCATEGORY MODAL -->
			<div class="modal fade" id="modal-subcat" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
                            <h1 class="modal-title">Add Subcategory</h1>
                        </div>
                        <div class="modal-body">
							<div class="row clearfix">
							<form action = "<?php echo base_url('admin/category/modal_subcat_add');?>" id="form-add-subcat" method="POST">
                           
								<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Category</small>
									<select name = "cat_name_forsubcat" class="form-control show-tick" data-live-search="true">
									<?php foreach($categories as $c):?>
									<option><?=html_escape($c->cat_name)?></option>
									<?php endforeach;?>
									</select>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
									<div class="form-group form-float">
										<small class="form-label col-grey">Subcategory Name</small>
										<div class="form-line success">
											<input type="text" name = "subcat_name" class="form-control">
										</div>
										<div class='validation-errors' id = "subcat_name_error"></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-5 col-xs-5">
									<br>
									<button type="button" id="btn-add-subcat" class="btn btn-block bg-green waves-effect" >
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
							<br>
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
											<th>Code</th>
                                            <th>Name</th>
											<th>Category</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($subcategories2 as $sc):?>
										<tr>
											<td><?=html_escape($sc->subcat_code)?></td>
											<td><?=html_escape($sc->subcat_name)?></td>
											<td><?=html_escape($sc->cat_name)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-subcat" data-cat-name = "<?=html_escape($sc->cat_name)?>"  data-subcat-id = "<?=html_escape($sc->subcat_id)?>" data-toggle="modal" data-target="#modal-edit-subcat">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/category/subcat_del/'.$sc->subcat_id.'/prod_add_form');?>">Delete</button>
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
								
			<!-- EDIT CATEGORY MODAL -->
			<div class="modal fade" id="modal-edit-cat" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/category/edit/prod_add_form');?>" method="POST" id="form-edit-cat">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Category</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div class="form-line success">
									<input type="hidden" name="modal-cat_id"/>
									<input type="text" name="modal-cat_name" class="form-control"/>
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
					</form>
                </div>
            </div>
			<!-- #END# -->
			
			<!-- EDIT SUBCATEGORY MODAL -->
			<div class="modal fade" id="modal-edit-subcat" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/category/subcat_edit/prod_add_form');?>" method="POST" id="form-edit-subcat">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Subcategory</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div>
									<select class="form-control selectpicker show-tick" name = "modal-categories" id="modal-categories" data-live-search="true">
									</select>
								</div>
								<br>
								<div class="form-line success">
									<input type="hidden" name="modal-subcat_id"/>
									<input type="text" name="modal-subcat_name" class="form-control" >
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
					</form>
                </div>
            </div>
			<!-- #END# -->
			
			<!-- EDIT SUPPLIER MODAL -->
			<div class="modal fade" id="modal-edit-sup" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action="<?php echo base_url('admin/inventory/edit_supplier');?>" id="form-edit-sup" method="POST">
                      <input type="hidden" name="modal-sup-id" id = "modal-sup-id" class="form-control">
								
						<div class="modal-header">
                            <h1 class="modal-title">EDIT SUPPLIER</h1>
                        </div>
                        <div class="modal-body">
                           <div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
									<small class="form-label col-grey">Supplier Names</small>	
									<div class="form-line success">
										<input type="text" name="modal-supplier" id = "modal-sup-name" class="form-control">
									</div>
									<div class="validation-errors" id="name_error">
									</div>	
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
									<small class="form-label col-grey">Contact No</small>	
									<div class="form-line success">
										<input type="text" name="modal-contact" id = "modal-contact" class="form-control">
									</div>
									<div class="validation-errors" id="contact_error">
									</div>	
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
									<small class="form-label col-grey">Address</small>	
									<div class="form-line success">
										<input type="text" name="modal-address" id = "modal-address" class="form-control">
									</div>
									<div class="validation-errors" id="address_error">
									</div>	
								</div>
							</div>
						</div>
						</div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-green waves-effect" id="btn-edit-sup"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
			
		</div>
    </section>
