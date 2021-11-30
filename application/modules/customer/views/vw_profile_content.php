<div class="col-lg-8 col-sm-12">
									<div class="row clearfix">
										<div class="col-sm-12 inline_divs">
											<div class="demo-switch-title col-green font-12">Include Account Fields</div>
											<div class="switch">
												<label><input type="checkbox" name="with_username_password" id="with_username_password_id"><span class="lever switch-col-green"></span></label>
											</div>
										</div>
									</div>
									<br/>
									<form role="form" id="form_edit" method="POST">
										<div class="row clearfix">
											<div class="col-sm-12 col-lg-4">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="text" class="form-control  letters_only" value="" name="profile_fname" placeholder="First Name" />
													</div>
													<span class="small-smaller" id="profile_fname_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control  letters_only" value="" name="profile_mname" placeholder="Middle Name" />
													</div>
													<span class="small-smaller" id="profile_mname_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control  letters_only" value="" name="profile_lname" placeholder="Last Name" />
													</div>
													<span class="small-smaller" id="profile_lname_label"><p></p></span>
												</div>
											</div>
										</div>
										<br/>
										<div class="row clearfix">
											<div class="col-sm-12 col-lg-4">
												<p style="display:inline-block !important;"> Gender </p>
												<input name="profile_gender" type="radio" id="male" class="with-gap radio-col-green"/>
												<label for="male">Male</label>
												<input name="profile_gender" type="radio" id="female" class="with-gap radio-col-green"/>
												<label for="female">Female</label>
												<span class="small-smaller" id="profile_gender_label"><p></p></span>
											</div>
											<div class="col-sm-12 col-lg-2">
												<div class="form-group">
													<div class="form-line">
														<input type="email" class="form-control" value="" name="profile_email" placeholder="Email">
													</div>
													<span class="small-smaller" id="profile_email_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-2">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control"  value="" name="profile_phone" placeholder="Phone Number" />
													</div>
													<span class="small-smaller" id="profile_phone_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">Birthday</label>
                                                        <input type="date" name="profile_dob" id="profile_dob_id" class="form-control" style="display:inline-block !important;" readOnly />
                                                    </div>
                                                    <span class="small-smaller" id="profile_dob_label"></span>
                                                </div>
                                            </div>
										</div>
										<br/>
										<div class="row clearfix">
											<div class="col-sm-6 col-lg-8">
												<div class="form-group">
													<div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">House Number, Street, Barangay</label>
														<input type="text" class="form-control" name="profile_address"/>
													</div>
													<span class="small-smaller" id="profile_address_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-6 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">City</label>
														<input type="text" class="form-control" name="profile_city"/>
													</div>
													<span class="small-smaller" id="profile_city_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-6 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">State</label>
														<input type="text" class="form-control"  name="profile_state"/>
													</div>
													<span class="small-smaller" id="profile_state_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-6 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">Country</label>
														<input type="text" class="form-control"  name="profile_country" disabled/>
													</div>
													<span class="small-smaller" id="profile_country_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-6 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">Zip Code</label>
														<input type="text" class="form-control number_only"  name="profile_zipcode"/>
													</div>
													<span class="small-smaller" id="profile_zipcode_label"><p></p></span>
												</div>
											</div>
										</div>
										<br/>
										<div class="row clearfix" id="with_username_password_div">
											<div class="col-sm-12 col-lg-4">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="text" class="form-control" value="" name="profile_uname" placeholder="Username" />
													</div>
													<span class="small-smaller" id="profile_uname_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-2">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="password" class="form-control" value="" name="profile_pass_n" placeholder="New Password" />
													</div>
													<span class="small-smaller" id="profile_pass_n_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-2">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="password" class="form-control" value="" name="profile_pass_nc" placeholder="Confirm New Password" />
													</div>
													<span class="small-smaller" id="profile_pass_nc_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-4">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="password" class="form-control" value="" name="profile_pass_o" placeholder="Old Password" />
													</div>
													<span class="small-smaller" id="profile_pass_o_label"><p></p></span>
												</div>
											</div>
											
										<br />
										</div>
										<div class="row clearfix">
											<div class="col-sm-12 col-lg-4">
												<button type="button" class="btn btn-success btn-block btn-add-cart waves-effect" id="update_profile_btn">
													Save Changes
												</button>
											</div>
										</div>
									</form>
								</div>