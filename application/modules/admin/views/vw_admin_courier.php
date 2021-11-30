	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>COURIER MANAGEMENT
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;">Order Management</li>
									<li style="background-color:transparent!important;" class="active">Courier Management</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				
			</div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>LIST OF COURIERS</h2>
							<ul class="header-dropdown m-r--5">
								<li data-toggle="tooltip" data-placement="bottom" title="Add User">
									
                                </li>
                            </ul>
                        </div>
                        <div class="body">
							<form id="form-courier" action = "<?php echo base_url('admin/order_management/add_courier');?>" method="POST">
								<div class="row clearfix">
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Courier Name</small>
											<div class="form-line success">
												<input type="text" name = "cour-name" id="cour-name" class="form-control">
											</div>
											<div class='validation-errors' id="cour_error"><?php echo form_error('cour-name');?></div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Shipping Fee</small>
											<div class="form-line success">
												<input type="text" name = "shipping-fee" id="shipping-fee" class="form-control">
											</div>
											<div class='validation-errors' id="fee_error"><?php echo form_error('shipping-fee');?></div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<br>
										<button id = "btn-courier" class="btn btn-block bg-green waves-effect" type="submit">
											<i class="material-icons">add</i> 
											<span>ADD</span>
										</button>
										<?php if(isset($alert_type)) echo $alert_type; ?>
									</div>
								</div>
							</form>			
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-courier">
                                    <thead>
                                        <tr class="col-green">
											<th>Courier Name</th>
                                            <th>Shipping Fee</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($couriers as $c):?>
                                        <tr>
											<td><?=html_escape($c->name)?></td>
											<td><?=html_escape($c->shipping_fee)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-courier" data-cour_id="<?=html_escape($c->cour_id)?>" data-name="<?=html_escape($c->name)?>" data-fee="<?=html_escape($c->shipping_fee)?>" data-toggle="modal" data-target="#modal-edit-courier">Edit</button>
												<?php if($c->is_default == 1): ?>
													<p class="btn btn-xs bg-amber" >Default</p>
												<?php else: ?>
													<button type="button" class="btn btn-xs waves-effect confirm" data-url="<?php echo base_url('admin/order_management/delete_courier/'.$c->cour_id);?>" data-title="Are you sure you want to delete this courier?" data-msg="This action cannot be undone." >Delete</button>
													<a href="<?php echo base_url('admin/order_management/set_default_courier/'.$c->cour_id);?>" class="btn btn-xs bg-light-green waves-effect">Set as Default</button>
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
            <!-- #END# DATA TABLE -->
			
			<!-- EDIT USER MODAL -->
			<div class="modal fade" id="modal-edit-courier" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title">Edit Courier</h1>
						</div>
						<div class="modal-body">
							<form id = 'form-courier2' action="<?php echo base_url('admin/order_management/edit_courier');?>" role="form" method = "POST">
							<input type ="hidden" class="form-control" name="modal-cour_id" id = "modal-cour_id" />
								<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Courier Name</small>
											<div class="form-line success">
												<input type ="text" class="form-control" name="modal-name" id = "modal-name" />
											</div>
											<div class='validation-errors' id = "cour_name_error"></div>
										</div>
										</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Shipping Fee</small>
											<div class="form-line success">
												<input type ="text" class="form-control" name="modal-shipping_fee" id = "modal-shipping_fee" />
											</div>
											<div class='validation-errors' id = "fee_error"></div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" id = "btn-edit-courier" class="btn bg-green waves-effect">SUBMIT</button>
							<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
						</div>
					</div>
				</div>
			</div>
			<!-- #END# EDIT USER MODAL -->
		</div>
	<section>