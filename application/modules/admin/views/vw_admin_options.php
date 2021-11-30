<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;" class="active">Product Attributes</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix jsdemo-notification-button">
			
				<!-- OPTION TYPE -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ATTRIBUTE TYPE</h2>
                        </div>
                        <div class="body">
							<form id="form-attrib-type" action="<?php echo base_url('admin/inventory/attrib_type_add');?>" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Attribute Type</small>
										<div class="form-line success">
											<input type="text" id="txt-edit" name="attrib_type_name" class="form-control">
										</div>
										<div class="validation-errors" id="attrib_type_error"><?php echo form_error('attrib_type_name');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
									<br>
									<button id="btn-attrib-type" class="btn btn-block bg-green waves-effect" type="submit">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="dt-attrib-type">
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
				<!-- #END# OPTION TYPE -->
				
				<!-- OPTIONS -->
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ATTRIBUTE VALUE</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
								<form id="form-attrib-val" action="<?php echo base_url('admin/inventory/attrib_val_add');?>" method="POST">
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
											<small class="form-label col-grey">Attribute Value</small>
											<div class="form-line success">
												<input type="text" name="attrib_val_name" class="form-control">
											</div>
											<div class="validation-errors"  id="attrib_val_error"><?php echo form_error('attrib_val_name');?></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<br>
										<button id="btn-attrib-val" class="btn btn-block bg-green waves-effect" type="submit">
											<i class="material-icons">add</i> 
											<span>ADD</span>
										</button>
									</div>
								</form>
							</div>
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-attrib-val">
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
												<button type="button" class="btn btn-xs bg-default waves-effect confirm" data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/inventory/attrib_val_del/'.$ov->opt_id);?>">Delete</button>
											</td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# OPTIONS -->
				
            </div>
            <!-- #END# DATA TABLE -->
		
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
