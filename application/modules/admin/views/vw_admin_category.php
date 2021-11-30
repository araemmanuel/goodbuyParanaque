    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;" class="active">Categories</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix jsdemo-notification-button">
			
				<!-- CATEGORY -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>CATEGORY</h2>
                        </div>
                        <div class="body">
							<form action = "<?php echo base_url('admin/category/add');?>" id="form-cat" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Category Name</small>
										<div class="form-line success">
											<input type="text" name = "cat_name" id="txt-edit" class="form-control">
										</div>
										<div class='validation-errors' id="cat_error"><?php echo form_error('cat_name');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
									<br>
									<button id="btn-cat" class="btn btn-block bg-green waves-effect" type="submit">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
									<?php if(isset($alert_type)) echo $alert_type; ?>
								</div>
							</div>
							</form>
							
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="dt-cat">
                                    <thead>
                                        <tr class="col-green">
											<th>Code</th>
                                            <th>Category</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($categories as $c):?>
									<tr>
										<td><?=html_escape($c->cat_code)?></td>
										<td><?=html_escape($c->cat_name)?></td>
										<td>
											<button type="button" class="btn btn-xs bg-green waves-effect open-edit-cat" data-cat-id = "<?=html_escape($c->cat_id)?>" data-href = "<?php echo base_url('admin/category/edit');?>" data-toggle="modal" data-target="#modal-edit-cat">Edit</button>
											<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/category/del/'.$c->cat_id);?>">Delete</button>
										</td>
									</tr>
									<?php endforeach;?>		
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# CATEGORY -->
				
				<!-- SUBCATEGORY -->
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>SUBCATEGORY</h2>
                        </div>
                        <div class="body">
							<form id="form-subcat" action = "<?php echo base_url('admin/category/subcat_add');?>" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Category</small>						
									<select name = "cat_name_forsubcat" class="form-control show-tick" data-live-search="true">
									<?php foreach($categories as $c):?>
									<option><?=html_escape($c->cat_name)?></option>
									<?php endforeach;?>
									</select>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Subcategory Name</small>
										<div class="form-line success">
											<input type="text" name = "subcat_name" class="form-control">
										</div>
										<div class='validation-errors' id="subcat_error"><?php echo form_error('subcat_name');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
									<br>
									<button id="btn-subcat" class="btn btn-block bg-green waves-effect" type="button">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
							
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-subcat">
                                    <thead>
                                        <tr class="col-green">
											<th>Code</th>
                                            <th>Subcategory</th>
											<th>Category</th>
											<th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($subcategories as $sc):?>
										<tr>
											<td><?=html_escape($sc->subcat_code)?></td>
											<td><?=html_escape($sc->subcat_name)?></td>
											<td><?=html_escape($sc->cat_name)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-subcat" data-cat-name = "<?=html_escape($sc->cat_name)?>"  data-subcat-id = "<?=html_escape($sc->subcat_id)?>" data-href="<?php echo base_url('admin/category/subcat_edit');?>" data-toggle="modal" data-target="#modal-edit-subcat">Edit</button>
												<button type="submit" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/category/subcat_del/'.$sc->subcat_id);?>">Delete</button>
											</td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# SUBCATEGORY -->
				
            </div>
            <!-- #END# DATA TABLE -->
					
			<!-- EDIT CATEGORY MODAL -->
			<div class="modal fade" id="modal-edit-cat" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form id="form-edit-cat">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Category</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div class="form-line success">
									<input type="hidden" name="modal-cat_id"/>
									<input type="text" name="modal-cat_name" class="form-control"/>
								</div>
								<div class='validation-errors' id = "cat_name_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-green waves-effect" id = "btn-edit-cat"><i class="material-icons">check</i> <span>SAVE</span></button>
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
					<form  id="form-edit-subcat">
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
								<div class='validation-errors' id = "subcat_name_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id ="btn-edit-subcat" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
					</form>
                </div>
            </div>
			<!-- #END# -->
        </div>
    </section>
