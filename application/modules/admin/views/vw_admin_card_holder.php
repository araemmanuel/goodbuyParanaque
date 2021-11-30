	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>MANAGE CARD HOLDERS
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;" class="active">Rewards</li>	
									<li style="background-color:transparent!important;" class="active">Card Holder Management</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="align-right">
						<a href="<?php echo base_url('admin');?>" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" type="button">
							<i class="material-icons">add</i> <span>ADD CUSTOMER</span>
						</a>
						<a href="<?php echo base_url('admin');?>" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" type="button">
							<i class="material-icons">print</i> <span>PRINT REWARD CARDS</span>
						</a>
					</div>
				</div>		
			</div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>LIST OF CARD HOLDERS</h2>
							<ul class="header-dropdown m-r--5">
								<li data-toggle="tooltip" data-placement="bottom" title="Add User">
									
                                </li>
                            </ul>
                        </div>
                        <div class="body">
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
											<th>Card No.</th>
											<th>Membership ID</th>
											<th>Customer Name</th>
											<th>Date Registered</th>
                                            <th>Expiration Date</th>
											<th>Reward Points</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php //foreach($users as $u):?>
                                        <tr>
											<!--<td><?=html_escape($u->username)?></td>-->
											<td>1</td>
											<td>2018-00001-TG-0</td>
											<td>Laurena Sigrid Garcia</td>
											<td>May 3, 2018</td>
											<td>May 3, 2019</td>
											<td>12 points</td>
											<td>Buttons</td>											
                                        </tr>
										<?php //endforeach;?>

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