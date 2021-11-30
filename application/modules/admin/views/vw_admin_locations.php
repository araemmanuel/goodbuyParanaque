    <section class="content jsdemo-notification-button">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ITEM TRANSFER
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Item Transfer</li>
							<li style="background-color:transparent!important;" class="active">Manage Branch Locations</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
			
				<!-- LOCATION -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE LOCATION</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
							<form id="form-loc" action ="<?php echo base_url('admin/transfer/add_location');?>" method = "POST">
								<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
									<div class="form-group form-float">
										<small class="form-label col-grey">Location</small>
										<div class="form-line success">
											<input type="text" name="location"  class="form-control">
										</div>
										<div class="validation-errors" id="loc_error"><?php echo form_error('location');?></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
									<br>
									<button id="btn-loc" class="btn btn-block bg-green waves-effect" type="submit">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</form>
							</div>
							
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover" id="dt-loc">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Location</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($locations as $l): ?>
                                        <tr>
											<td><?=$l->location?></td>
                            				<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-loc" data-loc-id = "<?=html_escape($l->loc_id)?>" data-href="<?php echo base_url('admin/transfer/edit_location/'.$l->loc_id);?>" data-toggle="modal" data-target="#modal-edit-loc">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg="This action cannot be undone." data-url="<?php echo base_url('admin/transfer/del_location/'.$l->loc_id);?>">Delete</button>
											</td>
                                        </tr>
										<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# LOCATION -->
            </div>
            <!-- #END# DATA TABLE -->
					
			<!-- EDIT LOCATION MODAL -->
			<div class="modal fade" id="modal-edit-loc" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form id="form-edit-loc">
                        <div class="modal-header">
                            <h1 class="modal-title">EDIT LOCATION</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div class="form-line success">
									<input type="hidden" name="modal-loc_id" class="form-control" />
									<input type="text" name="modal-location" class="form-control" />
								</div>
								<div class='validation-errors' id = "location_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id = "btn-edit-loc" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
        </div>
    </section>