	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;" class="active">Supplier</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
			
				<!-- SUPPLIER -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>SUPPLIER</h2>
                        </div>
                        <div class="body">
						<form action ="<?php echo base_url('admin/inventory/add_supplier');?>" method="POST" id="form-sup" >
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
									<button class="btn btn-block bg-green waves-effect" id="btn-add-sup" type="button">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</div>
							</form>
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="dt-sup">
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
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg="This action cannot be undone." data-url="<?php echo base_url('admin/inventory/del_supplier/'.$s->id);?>">Delete</button>
											</td>
                                        </tr>
										<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# CATEGORY -->
				
            </div>
            <!-- #END# DATA TABLE -->
						
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