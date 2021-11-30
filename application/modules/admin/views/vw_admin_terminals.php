	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>TERMINALS
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;" class="active">Terminals</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>TERMINALS</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
							<form id="form-terminal" action ="<?php echo base_url('admin/terminal/add');?>" method="POST">
								<?php date_default_timezone_set('Asia/Manila');?>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Terminal</small>
										<div class="form-line success">
											<input type="text" name="terminal" class="form-control">
										</div>
										<div class = 'validation-errors' id="terminal_error"><?php echo form_error('terminal');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<div class="form-group form-float">
										<small class="form-label col-grey">Device</small>
										<div class="form-line success">
											<input type="text" name="device" class="form-control" >
										</div>
										<div class = 'validation-errors' id="device_error"><?php echo form_error('device');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<br>
									<button id="btn-terminal" class="btn btn-block bg-green waves-effect" type="button">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</form>
							</div>
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-terminals">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
                                            <th>Terminal Name</th>
                                            <th>Device</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($terminals as $t): ?>
                                        <tr>
											<td><?=html_escape($t->id)?></td>
                                            <td><?=html_escape($t->name)?></td>
                                            <td><?=html_escape($t->device_name)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-terminal" data-ter-id="<?=$t->id?>" data-terminal="<?=$t->name?>" data-device="<?=$t->device_name?>" data-href="<?php echo base_url('admin/terminal/edit');?>" data-toggle="modal" data-target="#modal-edit-terminal">Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this terminal?" data-url="<?php echo base_url('admin/terminal/del/'.$t->id);?>">Delete</button>
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
			
			<!-- EDIT EXPENSE MODAL -->
			<div class="modal fade" id="modal-edit-terminal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
					<form id="form-edit-terminal" method="POST">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Terminal</h1>
                        </div>
                        <div class="modal-body">
								<input type="hidden" id="modal-ter-id" name="modal-ter-id" class="form-control" />
							<div class="form-group form-float">
								<small class="form-label col-grey">Terminal Name</small>
								<div class="form-line success">
									<input type="text" id="modal-terminal" name="modal-terminal" class="form-control" />
								</div>
								<div class="validation-errors" id="modal-terminal_error"></div>
							</div>
							<div class="form-group form-float">
								<small class="form-label col-grey">Device</small>
								<div class="form-line success">
									<input type="text" id="modal-device" name="modal-device" class="form-control" />
								</div>
								<div class="validation-errors" id="modal-device_error"></div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-edit-terminal" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
        </div>
    </section>