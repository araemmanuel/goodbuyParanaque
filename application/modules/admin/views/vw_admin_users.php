	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>USER MANAGEMENT
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;" class="active">User Management</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="align-right">
						<a href="<?php echo base_url('admin/user_management/add_form');?>" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" type="button">
							<i class="material-icons">add</i> <span>ADD USER</span>
						</a>
					</div>
				</div>		
			</div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>LIST OF USERS</h2>
							<ul class="header-dropdown m-r--5">
								<li data-toggle="tooltip" data-placement="bottom" title="Add User">
									
                                </li>
                            </ul>
                        </div>
                        <div class="body">
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-users">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
											<th>Username</th>
											<th>Role</th>
                                            <th>Name</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($users as $u):?>
                                        <tr>
											<td><?=html_escape($u->id)?></td>
											<td><?=html_escape($u->username)?></td>
											<td><?=html_escape(ucfirst($u->role))?></td>
											<?php if(empty($u->name)):?>
											<td>None</td>
											<?php else:?>
                                            <td><?=html_escape($u->name)?></td>
											<?php endif;?>
											<td>
											    <a href = "<?php echo base_url('admin/user_management/edit_form/' . $u->id);?>" class="btn btn-xs bg-green waves-effect col-white">Edit</a></button>
												<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg=" This action cannot be undone"  data-url="<?php echo base_url('admin/user_management/del/'.$u->id);?>">Delete</button>
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
			<div class="modal fade" id="edit-user-role-modal" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title">Edit User Role</h1>
						</div>
						<div class="modal-body">
							<small class="form-label col-grey">Role</small>
							<form id = 'form-user-role' role="form" method = "POST">
							<select id='user-role' name='user-role' class="form-control">
								<option value = "admin">admin</option>
								<option value = "cashier">cashier</option>
							</select>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" id = "btn-edit-user-role" class="btn bg-green waves-effect">SUBMIT</button>
							<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
						</div>
					</div>
				</div>
			</div>
			<!-- #END# EDIT USER MODAL -->
		</div>
	<section>