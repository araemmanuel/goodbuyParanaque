
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>REWARDS CARD
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/rewards_card');?>">Rewards Card</a></li>
							<li style="background-color:transparent!important;" class="active">Add Card Holder</li>
						</ol>
					</small>
				</h2>
            </div>
			<form id="form-add-cust"  method="POST">			
            <!-- ADD CARD HOLDER -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ADD REWARDS CARD HOLDER</h2>
                        </div>
                        <div class="body">
                        <div class="row clearfix">
						  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
											<input type="text" name="middlename" class="form-control" />
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
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
									<small class="form-label">Birth Date</small>
										<div class="form-line success">
											<input type="date" name="dob" class="form-control"  />
											
										</div>
										<div class="validation-errors" id="dob_error"><?php echo form_error('dob');  ?></div>
									</div>
								</div>
									<?php date_default_timezone_set('Asia/Manila');?>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
									<small class="form-label">Register Date</small>
										<div class="form-line success">
											<input type="date" name="register-date" value = "<?php echo date("Y-m-d");?>" class="form-control" required />
											
										</div>
										<div class="validation-errors" id="register-date_error"><?php echo form_error('register-date');  ?></div>
									</div>
								</div>
								<br>
                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Gender</small>
								</div>
								<div class="input-field col-lg-5 col-md-5 col-sm-12 col-xs-12">
									<div class="demo-radio-button">
										<input name="gender" type="radio" class="with-gap radio-col-green" id="male" />
										<label for="male">Male</label>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="female" />
										<label for="female">Female</label>
									</div>
									<div class="validation-errors" id="gender_error"><?php echo form_error('gender');  ?></div>									
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="contact" class="form-control"  />
										<label class="form-label">Contact No</label>
                                    </div>
									<div class="validation-errors" id="contact_error"><?php echo form_error('contact');?></div>
                                </div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
										<input type="email" name="email" class="form-control"  />
										<label class="form-label">Email Address</label>
                                    </div>
									<div class="validation-errors" id="email_error"><?php echo form_error('email');  ?></div>
                                </div>
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="address" class="form-control" />
										<label class="form-label">Shipping Address</label>
                                    </div>
									<div class="validation-errors" id="address_error"><?php echo form_error('address');?></div>
                                </div>
								</div>			
							</div>
							<div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="zipcode" value="1711" class="form-control" />
											<label class="form-label">Shipping Zipcode</label>
										</div>
										<div class="validation-errors" id="zipcode_error"><?php echo form_error('zipcode'); ?></div>
									</div>
								</div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="city" class="form-control" value="Para&ntilde;aque"  />
											<label class="form-label">Shipping City</label>
										</div>
										<div class="validation-errors" id="city_error"><?php echo form_error('city');?></div>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="state" class="form-control" value="NCR" />
											<label class="form-label">Shipping State</label>
										</div>
										<div class="validation-errors" id="state_error"><?php echo form_error('state'); ?></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="country" class="form-control" value="Philippines"  />
											<label class="form-label">Country</label>
										</div>
										<div class="validation-errors" id="country_error"><?php echo form_error('country'); ?></div>
									</div>
								</div>
							</div>
						  </div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="align-right">
									<button type="button" data-href = "<?php echo base_url('admin/rewards_card/add_card_holder');?>" id="btn-add-cust" class="btn bg-green waves-effect" >
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
									&nbsp;
									<button type="button" class="btn waves-effect" onclick="window.location.href='<?php echo base_url('admin/rewards_card');?>'">
										<i class="material-icons">close</i>
										<span>CANCEL</span>
									</button>
								</div>
							</div>
						</div>		
                        </div>
                    </div>
                </div>
            </div>
			</form>
            <!-- #END# ADD CARD HOLDER -->		
        </div>
    </section>
