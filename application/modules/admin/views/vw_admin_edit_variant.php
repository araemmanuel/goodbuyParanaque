	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/product_variants/'.$prod_id);?>">Product Variants</a></li>
							<li style="background-color:transparent!important;" class="active">Edit Product Variant</li>
						</ol>
					</small>
				</h2>
            </div>
			<!-- EDIT PRODUCT -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT PRODUCT VARIANT</h2>
							<small>Edit product variant details</small>
                        </div>
						
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
							<?php endforeach;?>
							
							<br>
							
							<?php foreach($product as $p): ?>
							<div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<button type="button" id="open-cam-var-edit" class="btn btn-xs btn-block bg-green waves-effect">
												<i class="material-icons">camera_alt</i>
												<span>ACCESS CAMERA</span>
											</button>
										</div>
									</div>
									<div class="row clearfix">	
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<?php 
											if (strcasecmp('None', $p->primary_image) == 0): ?>
												<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" class="main-holder upload-preview" style="width:100%;height:100%;">
											<?php else: ?>
												<img src="<?php echo base_url($p->primary_image);?>" class="main-holder upload-preview"style="width:100%;height:100%;">
											<?php endif;?>
											
											<div class="add-prod-other-pics"></div>
											<div class="browse-prod-other-pics"></div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="sizes">
												<form action = "<?php echo base_url('admin/inventory/delete_file/'.$prod_id.'/'.$sku);?>" method="POST" id="del-image">	
													<div class="product-pic-small">
													<?php if ($p->other_images): ?>
														<?php if (strcasecmp('None', $p->primary_image) == 0): ?>
															<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" name = "primary_image" class="other-pics" >
														<?php else: ?>
														<img src="<?php echo base_url($p->primary_image);?>" name = "primary_image" class="other-pics" >
															<input type="hidden" name="img_path" value = "<?=$p->primary_image?>"><br>
															<input type="submit" class="link" value="Delete" />		
														<?php endif;?>	
													<?php endif;?>	
													</div>	
												</form>
												<?php 
											
												$other_pics = explode(',',$p->other_images);				
												if($p->other_images) :
												for($i=0;$i<count($other_pics);$i++): ?>
												<form action = "<?php echo base_url('admin/inventory/delete_file/'.$prod_id.'/'.$sku);?>" method="POST" id="del-image">
													<input type="hidden" name="img_path" value = "<?=$other_pics[$i]?>">
													<div class="product-pic-small">
														<img src="<?php echo base_url($other_pics[$i]);?>" class="other-pics" ><br>
														<input type="submit" class="link" value="Delete" />
													</div>	
												</form>									
												<?php endfor;
													  endif;
												?>	
											</div>
										</div>
										
									</div>	
									
									<form action="<?php echo base_url('admin/inventory/var_edit');?>" id="form-var-edit" method="POST" enctype="multipart/form-data">
									
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<video id="var-player2" width="100%" height = "100%" autoplay></video>
											<div class="align-center">
											<button type="button" id="btn-add-primary3"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE MAIN</button>		
											<button type="button" id="btn-add-other3"  class="btn btn-xs bg-green waves-effect custom-hide">CAPTURE OTHERS</button>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<canvas id = "canvas" width="200" height="150" style="display:none;"> </canvas>	
										</div>
									</div>
								</div>
								
								<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
									
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Main Image: </small>
											<input type="file" name="primary_image" class="primary-img">
											<div class='validation-errors'>
												<?php echo form_error('primary_image');?>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Edit Other Product Images: </small>
											<input type="file" name="other_images[]" class="other-img" multiple>																	
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<small class="form-label col-grey">Add Other Product Images: </small>		
											<input type="file" name="add_other_images[]" class="add-other-img"  accept="image/*" capture="camera" multiple>																								
										</div>
										
									</div>
									
									<div class="row clearfix">
										<input type ="hidden" name="prod_id" value="<?php echo $prod_id;?>">
										<input type ="hidden" name="sku" value="<?php echo $sku;?>">
										<input type ="hidden" name="prod_cat" value="<?=$p->cat_name?>">
										<input type ="hidden" name="prod_subcat" value="<?=$p->subcat_name?>">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<br><br>
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
											<br><br>
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
											<br><br>	
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
															<?php foreach($attrib_types as $og):?>
																<?php 	if(in_array($og->opt_grp_name, $prod_attrib_type)):  ?>
																<td class="col-grey" style="padding:10px;"><?=html_escape(ucfirst($og->opt_grp_name))?></td>
																<?php endif; ?>
																
															<?php endforeach; ?>
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
																$ctr=0;
																foreach($attrib_types as $og):
																$ctr++;
															?>
														<?php 
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
															if(in_array($og->opt_grp_name, $prod_attrib_type)):
														?>
													
															<td style="padding:10px;">
																<div class="form-group form-float">
																	<div class="form-line success">
																		<input type="text" id="var-edit-<?=html_escape($og->opt_grp_id)?>" name = "<?=$og->opt_grp_name?>" class="form-control input-attrib" value="<?php echo $val;?>" />				
																	</div>
																</div>
															</td>
														<?php
															endif;
														?>
														<?php endforeach;?>
														</tr>
													</tbody>
													<tfoot>
														<div class='validation-errors' id="attrib_error"></div>	
													</tfoot>
												</table>
											</div>
										</div>
									</div>	
									
									<!--
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div data-toggle="tooltip" id = "open-attrib-modal" data-placement="bottom" title="Add Attribute">
												<a class="btn btn-xs btn-block bg-light-green waves-effect " data-toggle="modal" data-target="#modal-add-attrib-val">
													<i class="material-icons">add</i>	 
													<span>Add Attribute Value</span>
												</a>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div data-toggle="tooltip" id = "open-attrib-type-modal" data-placement="bottom" title="Add Attribute Type">
												<a class="btn btn-xs btn-block bg-green waves-effect " data-toggle="modal" data-target="#modal-add-attrib-type">
													<i class="material-icons">add</i>
													<span>Add Attribute Type</span>
												</a>
											</div>
										</div>
									</div>	
									
									<div class="row clearfix">
										ATTRIBUTES INPUT FIELD
													<?php 
														$ctr=0;
														foreach($attrib_types as $og):
														$ctr++;
													?>
													
													<?php if($ctr == 7): ?>										
														<div class="row clearfix">
													<?php endif; ?>										
														<?php 
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
															if(in_array($og->opt_grp_name, $prod_attrib_type)):
														?>
														<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
															<div class="form-group demo-tagsinput-area form-float">
																<small class="form-label col-grey"><?=$og->opt_grp_name?></small>
																<div class="form-line success">													
																	<input type="text" name = "<?=$og->opt_grp_name?>" class="form-control" value="<?php echo $val;?>" />				
																</div>
																<div class='validation-errors' id="<?=html_escape($og->opt_grp_name)?>_error">
																</div>
															</div>		
														</div>
														<?php
															endif;
															if($ctr == 7): 
														?>											
														</div>	
														<?php $ctr=0;
															endif; 
														?>										
														<?php endforeach;?>
													END ATTRIBUTES INPUT FIELD
									</div>
									-->
									
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="align-right">
												<button class="btn bg-green waves-effect" type="button" data-href="<?php echo base_url('admin/inventory/var_edit');?>" id="btn-var-edit">
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
									
									</form>
								</div>
							</div>
							<?php endforeach; ?>
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
		</div>
    </section>
