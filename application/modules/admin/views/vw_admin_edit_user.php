    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>USER MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/user_management');?>">User Management</a></li>
							<li style="background-color:transparent!important;" class="active">Edit User</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- ADD USER -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT USER</h2>
                        </div>
                        <div class="body">
						<form action = "<?php echo base_url('admin/user_management/edit/'.$user->id);?>" id = "form-user2" enctype="multipart/form-data"  method="POST">
                            
						<div class="row clearfix">		
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<?php if($user->profile_pic_path): ?>
									<img src="<?php echo base_url($user->profile_pic_path);?>"  style="width:100%;height:100%;">
								<?php else: ?>
									<img src="<?php echo base_url('assets/images/no-photo.jpg');?>"  style="width:100%;height:100%;">
								<?php endif; ?>	
								<br><br>
								<input type="file" name="file" />
							</div>
							
							<input type="hidden" name="user-id" value="<?php echo $user->id?>" class="form-control" required />
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								
								<div class="row clearfix">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
										
											<input type="text" name="firstname" value="<?php echo $user->firstname;?>" class="form-control" required />
											<label class="form-label">First Name</label>
										</div>
										<div class="validation-errors" id="firstname_error"><?php echo form_error('firstname');?></div>
									</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="lastname" value="<?php echo $user->lastname;?>" class="form-control" required />
											<label class="form-label">Last Name</label>
										</div>
										<div class="validation-errors" id="lastname_error"><?php echo form_error('lastname');  ?></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
										<small class="form-label col-grey">Gender</small>
									</div>
									<div class="input-field col-lg-10 col-md-10 col-sm-12 col-xs-12">
										<div class="demo-radio-button">
											<input name="gender" type="radio" class="with-gap radio-col-green" id="male" />
											<label for="male">Male</label>
											<input name="gender" type="radio" class="with-gap radio-col-green" id="female" />
											<label for="female">Female</label>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="username" value="<?php echo $user->username;?>" class="form-control" required />
											<label class="form-label">Username</label>
										</div>
										<div class="validation-errors" id="username_error"><?php echo form_error('username');?></div>
									</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="email" name="email" value="<?php echo $user->email;?>"  class="form-control" required />
											<label class="form-label">Email Address</label>
										</div>
										<div class="validation-errors" id="email_error"><?php echo form_error('email');  ?></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="password" name="password" class="form-control" />
											<label class="form-label">Password</label>
										</div>
										<div class="validation-errors" id="password_error"><?php echo form_error('password');?></div>
									</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="password" name="re-password" class="form-control"  />
											<label class="form-label">Re-enter Password</label>
										</div>
										<div class="validation-errors" id="re-password_error"><?php echo form_error('re-password'); ?></div>
									</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<small class="form-label col-grey">Role</small>
										<select class="form-control show-tick" name = "role" data-live-search="true">
											<?php
													if(strcasecmp($user->role, 'admin') == 0)
													{	
														echo '<option value="Admin" selected>Admin</option>';
														echo '<option value="Cashier">Cashier</option>';
														echo '<option value="Customer">Customer</option>';
													}
													elseif(strcasecmp($user->role, 'cashier') == 0)
													{
														echo '<option value="Admin">Admin</option>';
														echo '<option value="Cashier" selected>Cashier</option>';
														echo '<option value="Customer">Customer</option>';
													}
													else
													{
														echo '<option value="Admin">Admin</option>';
														echo '<option value="Cashier">Cashier</option>';
														echo '<option value="Customer" selected>Customer</option>';
													}
											?>
										</select>
										<div class="validation-errors"><?php echo form_error('role'); ?></div>
									</div>
								</div>
							</div>
						</div>
                        
						  	<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="align-right">
									<button class="btn bg-green waves-effect" id="btn-edit-user" type="button">
										<i class="material-icons">check</i> 
										<span>SAVE</span>
									</button>
									&nbsp;
									<button class="btn waves-effect" type="button" onclick="window.location.href='<?php echo base_url('admin/user_management');?>'">
										<i class="material-icons">close</i>
										<span>CANCEL</span>
									</button>
								</div>
							</div>
						</div>
						</form>
						</div>
						
					
						
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# ADD USER -->
			
        </div>
    </section>
