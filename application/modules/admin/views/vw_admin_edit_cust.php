    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<h2>REWARDS CARD
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/rewards_card');?>">Rewards Card</a></li>
							<li style="background-color:transparent!important;" class="active">Edit Card Holder</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- ADD CARD HOLDER -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT REWARDS CARD HOLDER</h2>
                        </div>
                        <div class="body">
						<div class="row clearfix">
						  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action = "<?php echo base_url('admin/rewards_card/edit_card_holder');?>"  method="POST">
                            <?php foreach($app_details as $a): ?>
							<input type="hidden" name="card-no" value="<?=$a->card_no?>" class="form-control" required />
							<div class="row clearfix">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="firstname" value="<?=html_escape($a->firstname)?>" class="form-control" required />
											<label class="form-label">First Name</label>
										</div>
										<div class="validation-errors"><?php echo form_error('firstname');?></div>
									</div>
								</div>
								 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="middlename" value="<?=html_escape($a->middlename)?>" class="form-control" required />
											<label class="form-label">Middle Name</label>
										</div>
										<div class="validation-errors"><?php echo form_error('middlename');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="lastname" value="<?=html_escape($a->lastname)?>" class="form-control" required />
											<label class="form-label">Last Name</label>
										</div>
										<div class="validation-errors"><?php echo form_error('lastname');  ?></div>
									</div>
								</div>
							</div>
							<div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
									<small class="form-label">Birth Date</small>
										<div class="form-line success">
											<input type="date" name="dob" value="<?=html_escape($a->DOB)?>" class="form-control" required />
											
										</div>
										<div class="validation-errors"><?php echo form_error('dob');  ?></div>
									</div>
								</div>
									<?php date_default_timezone_set('Asia/Manila');?>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
									<small class="form-label">Register Date</small>
										<div class="form-line success">
											<input type="date" name="register-date" value="<?=html_escape($a->date_registered)?>" class="form-control" required />
											
										</div>
										<div class="validation-errors"><?php echo form_error('register-date'); ?></div>
									</div>
								</div>
								<br>
                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Gender</small>
								</div>
								<div class="input-field col-lg-5 col-md-5 col-sm-12 col-xs-12">
									<div class="demo-radio-button">
									<?php if(strcasecmp(html_escape($a->gender), 'male') == 0):?>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="male" checked />
										<label for="male">Male</label>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="female" />
										<label for="female">Female</label>
									<?php else :?>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="male" />
										<label for="male">Male</label>
										<input name="gender" type="radio" class="with-gap radio-col-green" id="female"  checked />
										<label for="female">Female</label>
									<?php endif;?>
									</div>
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="contact" class="form-control" value="<?=html_escape($a->contact_no)?>" required />
										<label class="form-label">Contact No</label>
                                    </div>
									<div class="validation-errors"><?php echo form_error('contact');?></div>
                                </div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
										<input type="email" name="email" class="form-control" value="<?=html_escape($a->email)?>" required />
										<label class="form-label">Email Address</label>
                                    </div>
									<div class="validation-errors"><?php echo form_error('email');  ?></div>
                                </div>
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" name="address" class="form-control" value="<?=html_escape($a->shipping_address)?>" required />
										<label class="form-label">Shipping Address</label>
                                    </div>
									<div class="validation-errors"><?php echo form_error('address');?></div>
                                </div>
								</div>			
							</div>
							<div class="row clearfix">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="zipcode" class="form-control" value="<?=html_escape($a->shipping_zipcode)?>" required />
											<label class="form-label">Shipping Zipcode</label>
										</div>
										<div class="validation-errors"><?php echo form_error('zipcode'); ?></div>
									</div>
								</div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="city" class="form-control" value="<?=html_escape($a->shipping_city)?>" required />
											<label class="form-label">Shipping City</label>
										</div>
										<div class="validation-errors"><?php echo form_error('city');?></div>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="state" class="form-control" value="<?=html_escape($a->shipping_state)?>" required />
											<label class="form-label">Shipping State</label>
										</div>
										<div class="validation-errors"><?php echo form_error('state'); ?></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="country" class="form-control" value="<?=html_escape($a->shipping_country)?>" required />
											<label class="form-label">Country</label>
										</div>
										<div class="validation-errors"><?php echo form_error('country'); ?></div>
									</div>
								</div>
							</div>
						  </div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="align-right">
									<button type="submit" class="btn bg-green waves-effect" >
										<i class="material-icons">add</i> 
										<span>EDIT</span>
									</button>
									&nbsp;
									<button type ="button" class="btn waves-effect" onclick="window.location.href='<?php echo base_url('admin/rewards_card');?>'">
										<i class="material-icons">close</i>
										<span>CANCEL</span>
									</button>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						</form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# ADD CARD HOLDER -->		
        </div>
    </section>
