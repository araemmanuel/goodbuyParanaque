<section class="content">
        <div class="common_element_view_banner">
            <h2>Sign Up</h2>
        </div>
        <div class="bg-white">
            <div class="profile_view_holder">
                <div class="row">
                    <div class="col-lg-12 profile_view">
                        <div class="card profile_view_card">
                            <form name="sign_up_form" id="submit_sign_up_form" autocomplete="off">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12">
                                    <div class="row">
										<div class="header">
                                            <h5>
                                                Account Information
                                            </h5>
                                        </div>
                                        <br/>
                                        <div class="row clearfix">
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" placeholder="Username" name="sign_up_username" id="sign_up_username_id" autocomplete="off" />
                                                    </div>
                                                    <span id="sign_up_username_label" class="small-smaller"><p></p></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" class="form-control password_strength" placeholder="Password" name="sign_up_password" id="sign_up_password_id" autocomplete="new-password" />
                                                    </div>
													<div id="password-level">
														<div id="password-bar"></div>
													</div>
                                                    <span id="sign_up_password_label" class="small-smaller"><p></p></span>
													
                                                </div>
                                            </div>
											<div class="col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" class="form-control" placeholder="Confirm Password" name="sign_up_password_c" id="sign_up_password_c_id" autocomplete="new-password" />
                                                    </div>
                                                    <span id="sign_up_password_c_label" class="small-smaller"><p></p></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="header">
                                            <h5>
                                                General Information
                                            </h5>
                                        </div>
                                        <br/>
                                        <div class="row clearfix">
                                            <div class="col-sm-6 col-xs-12 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control input_letters" placeholder="First Name" name="sign_up_firstname" id="sign_up_firstname_id"/>
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_firstname_label"></span>
                                                </div>
                                            </div>
											<div class="col-sm-6 col-xs-12 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control input_letters" placeholder="Middle Name" name="sign_up_middlename" id="sign_up_middlename_id"/>
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_middlename_label"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control input_letters" placeholder="Last Name" name="sign_up_lastname" id="sign_up_lastname_id"/>
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_lastname_label"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12 col-lg-3">
												<div class="form-group">
													<input type="hidden" name="sign_up_gender" value="">
													<p style="display:inline-block !important;"> Gender </p>
													<input name="gender"  value="male" type="radio" id="male" style="opacity: 100 !important; left: 0px !important; position: relative !important;" />
													<p style="display:inline-block !important;padding: 0.01rem !important;"> Male </p>
													<input  name="gender" value="female" type="radio" id="female" style="opacity: 100 !important;left: 0px !important; position: relative !important;"/>
													<p style="display:inline-block !important; padding: 0.01rem !important;"> Female </p>
													<span class="small-smaller" id="sign_up_gender_label"></span>
												</div>
                                            </div>
                                            <div class="col-sm-12  col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="email" name="sign_up_email" id="sign_up_email_id" class="form-control" placeholder="Email" />
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_email_label"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="sign_up_phone" id="sign_up_phone_id" class="form-control" placeholder="Phone Number" />
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_phone_label"></span>
                                                </div>
                                            </div>
											<div class="col-sm-12 col-lg-3">
                                                <div class="form-group">
                                                    <div class="form-line">
														<label class="form-label" style="top: -10px;font-size: 12px !important;">Birthday</label>
                                                        <input type="date" name="sign_up_dob" id="sign_up_dob_id" class="form-control" style="display:inline-block !important;" />
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_dob_label"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="header">
                                            <h5>
                                                Address Information
                                            </h5>
                                        </div>
                                        <br/>
                                        <div class="row clearfix">
                                            <div class="col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="sign_up_address" id="sign_up_address_id" class="form-control" placeholder="House Number, Street, Barangay" />
                                                    </div>
                                                    <span class="small-smaller" id="sign_up_address_label"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="col-sm-6 col-xs-6 col-lg-6">
													<div class="form-group">
														<div class="form-line">
															<input type="text" name="sign_up_city" id="sign_up_city_id" class="form-control" placeholder="City" />
														</div>
														<span class="small-smaller" id="sign_up_city_label"></span>
													</div>
												</div>
												<div class="col-sm-6 col-xs-6 col-lg-6">
													<div class="form-group">
														<div class="form-line">
															<input type="text" name="sign_up_state" id="sign_up_state_id" class="form-control" placeholder="State" />
														</div>
														<span class="small-smaller" id="sign_up_state_label"></span>
													</div>
												</div>
                                            </div>
											
											<div class="col-sm-6 col-lg-3">
                                                <div class="col-sm-6 col-xs-6 col-lg-6">
													<div class="form-group">
														<div class="form-line">
															<input type="hidden" name="sign_up_country" value="Philippines" />
															<input type="text"  id="sign_up_country_id" class="form-control" placeholder="Country" disabled value="Philippines"/>
														</div>
														<span class="small-smaller" id="sign_up_country_label"></span>
													</div>
												</div>
												<div class="col-sm-6 col-xs-6 col-lg-6">
													<div class="form-group">
														<div class="form-line">
															<input type="text" name="sign_up_zip" id="sign_up_zip_id" class="form-control number_only" maxlength="4" placeholder="Zip Code" />
														</div>
														<span class="small-smaller" id="sign_up_zip_label"></span>
													</div>
												</div>
                                            </div>
											
                                        </div>
                                        <br/>
                                            <button id="btn_submit_sign" class="btn btn-success btn-lg btn-add-cart waves-effect">Submit Information
                                            </button>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <!-- Insert anything here for the content-->
                                </div>
                            </div>
                        
							</form>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>