    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>USER MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/user_management');?>">User Management</a></li>
							<li style="background-color:transparent!important;" class="active">Add User</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- ADD USER -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ADD USER</h2>
                        </div>
						
                        <div class="body">
						<form id="form-user" action = "<?php echo base_url('admin/user_management/add');?>" enctype="multipart/form-data"  method="POST">
                            
						<div class="row clearfix">					
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="file" name="file" />
							</div>
						</div>
                        
						<div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="firstname" class="form-control" required />
										<label class="form-label">First Name</label>
                                    </div>
									<div class="validation-errors" id="firstname_error"><?php echo form_error('firstname');?></div>
                                </div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="middlename" class="form-control" required />
										<label class="form-label">Middle Name</label>
                                    </div>
									<div class="validation-errors" id="middlename_error"><?php echo form_error('middlename');?></div>
                                </div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="lastname" class="form-control" required />
										<label class="form-label">Last Name</label>
                                    </div>
									<div class="validation-errors" id="lastname_error"><?php echo form_error('lastname');  ?></div>
                                </div>
							</div>
						</div>
						<div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Gender</small>
								</div>
								<div class="input-field col-lg-10 col-md-10 col-sm-12 col-xs-12">
									<div class="demo-radio-button">
										<input name="gender" type="radio" class="with-gap radio-col-green" id="male" />
										<label for="male">Male</label>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="female" />
										<label for="female">Female</label>
									</div>
										<div class="validation-errors" id="gender_error"><?php echo form_error('gender');  ?></div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<br>
							<br>
								<div class="form-group form-float">
                                    <div class="form-line success">
										<input type="email" name="email" class="form-control" required />
										<label class="form-label">Email Address</label>
                                    </div>
									<div class="validation-errors" id="email_error"><?php echo form_error('email');  ?></div>
                                </div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<br>
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="username" class="form-control" required />
										<label class="form-label">Username</label>
                                    </div>
									<div class="validation-errors" id="username_error"><?php echo form_error('username');?></div>
                                </div>
							</div>
						    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<br>
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="password" name="password" class="form-control" required />
										<label class="form-label">Password</label>
                                    </div>
									<div class="validation-errors" id="password_error"><?php echo form_error('password');?></div>
                                </div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<br>
								<div class="form-group form-float">
                                    <div class="form-line success">
										<input type="password" name="re-password" class="form-control" required />
										<label class="form-label">Re-enter Password</label>
                                    </div>
									<div class="validation-errors" id="re-password_error"><?php echo form_error('re-password'); ?></div>
                                </div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<small class="form-label col-grey">Role</small>
								<select name='role' class="form-control show-tick" data-live-search="true">
									<option value="0">- Please select -</option>	
									<option value="Admin">Admin</option>
                                       <option value="Cashier">Cashier</option>
                                       <option value="Customer">Customer</option>
                                   </select>
								<div class="validation-errors" id="role_error"><?php echo form_error('role'); ?></div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="align-right">
									<button class="btn bg-green waves-effect" id="btn-add-user" type="submit">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
									&nbsp;
									<button class="btn waves-effect" onclick="window.location.href='<?php echo base_url('admin/user_management');?>'">
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
            <!-- #END# ADD USER -->
			
        </div>
    </section>
