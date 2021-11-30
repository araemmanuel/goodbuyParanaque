<section class="content">
		<div class="product_view_banner">
			<h2>Checkout</h2>
			<ol class="breadcrumb breadcrumb-col-green">
				<li><a href="<?php echo base_url(); ?>" >Home</a></li>
				<li><a href="<?php echo base_url('customer/view_cart'); ?>">Cart</a></li>
				<li><a href="<?php echo base_url('customer/view_checkout'); ?>">Checkout</a></li>
			</ol>
		</div>
		<div class="bg-white">
			<div class="product_view_holder">
				<div class="row">
					<div class="col-lg-12 product_view">
						<div class="card product_view_card">
							<div class="row">
								<div class="col-lg-8">
									<div class="row">
											<form id="place_order_frm" method="POST">
												
												<div class="tab-content">
													<div class="header">
															<h5>Basic Information</h5>
															<input type="text" name="checkout_username" style="position: absolute !important; left: -10000px !important;" id="checkout_username" />
															<input type="text" name="checkout_reward_equivalent" style="position: absolute !important; left: -10000px !important;" id="checkout_reward_equivalent" />
													</div>
													<br/>
													<div class="row clearfix">
															<div class="col-sm-4 col-lg-4">
																<div class="form-group">
																	<div class="form-line">
																		<input type="text" class="form-control input_letters" placeholder="First Name" name="checkout_fname" />
																	</div>
																	<span id="checkout_fname_label" class="small-smaller"></span>
																</div>
															</div>
															<div class="col-sm-4 col-lg-4">
																<div class="form-group">
																	<div class="form-line">
																		<input type="text" class="form-control input_letters" placeholder="Middle Name" name="checkout_mname" />
																	</div>
																	<span id="checkout_mname_label" class="small-smaller"></span>
																</div>
															</div>
															<div class="col-sm-4 col-lg-4">
																<div class="form-group">
																	<div class="form-line">
																		<input type="text" class="form-control input_letters" placeholder="Last Name" name="checkout_lname" />
																	</div>
																	<span id="checkout_lname_label" class="small-smaller"></span>
																</div>
															</div>
													</div>
													<div class="row clearfix" id="before_reward">
															<div class="col-sm-8">
																<div class="form-group">
																	<div class="form-line">
																		<input type="email" class="form-control" placeholder="Email" name="checkout_email" />
																	</div>
																	<span id="checkout_email_label" class="small-smaller"></span>
																</div>
															</div>
															<div class="col-sm-12 col-lg-4">
																<div class="form-group">
																	<div class="form-line">
																		<input type="text" class="form-control input_numbers" placeholder="Phone Number" name="checkout_phone" />
																	</div>
																	<span id="checkout_phone_label" class="small-smaller"></span>
																</div>

															</div>
														</div>
														<br/>
														<div class="header">
															<h5>Payment Method</h5>
														</div>
														<br/>
														<div class="row">
															<div class="col-lg-6 demo-radio-button" id="through_cod_label">
																<input name="payment_method" type="radio" id="through_pickup" class="with-gap radio-col-green" value="pickup" checked />
																<label for="through_pickup">Via Pickup</label>
															</div>
															<div class="col-lg-6 demo-radio-button" id="through_cod_label">
																<input name="payment_method" type="radio" id="through_cod" class="with-gap radio-col-green" value="cod" />
																<label for="through_cod">Via Cash-On-Delivery</label>
															</div>
														</div>
														<br />
														<div class="row clearfix" id="via_cod">
															<div class="header header_delivery_address">
																<h5>Delivery Address</h5>
																<input type="checkbox" id="md_checkbox_30" class="filled-in chk-col-green" checked name="use_home_address" id="use_home_address_input"/>
																<label for="md_checkbox_30" id="use_home_address_label">Use Home Address</label>
															</div>
															<p>Note: Cash-On-Delivery is only available for places located at Parañaque</p>
															<br/>
															<div class="row clearfix">
															<div class="col-sm-6 col-lg-6">
																<div class="form-group">
																	<div class="form-line">
																		<label class="form-label" style="top: -10px;font-size: 12px !important;">House Number, Street, Barangay</label>
																		<input type="text" class="form-control" name="checkout_address" />
																	</div>
																	<span id="checkout_address_label" class="small-smaller"></span>
																</div>
															</div>
															<div class="col-sm-6 col-lg-6">
																<div class="form-group">
																	<div class="form-line">
																		<label class="form-label" style="top: -10px;font-size: 12px !important;">City</label>
																		<input type="hidden" name="checkout_city" value="Parañaque" />
																		<input type="text" class="form-control" placeholder="City" value="Parañaque" disabled/>
																	</div>
																	<span id="checkout_city_label" class="small-smaller"></span>
																</div>
															</div>
															</div>
															<div class="row clearfix">
															<div class="col-sm-6 col-lg-6">
																<div class="form-group">
																	<div class="form-line">
																		<label class="form-label" style="top: -10px;font-size: 12px !important;">State</label>
																		<input type="hidden" name="checkout_state" value="Luzon" />
																		<input type="text" class="form-control" placeholder="City" value="Luzon" disabled/>
																	</div>
																	<span id="checkout_state_label" class="small-smaller"></span>
																</div>
															</div>
															<div class="col-sm-6 col-lg-6">
																<div class="form-group">
																	<div class="form-line">
																		<label class="form-label" style="top: -10px;font-size: 12px !important;">Zip Code</label>
																		<input type="text" name="checkout_zipcode" class="form-control number_only"/>
																	</div>
																	<span id="checkout_zipcode_label" class="small-smaller"></span>
																</div>
															</div>
														</div>
														</div>
														<br />
														<div class="row clearfix" id="via_pickup">
															<h5>*Note: You can only pick up your order from the store on or before 3 days upon order confirmation</h5>
														</div>
													<br/>
															<br/>
														<div id="avail_reward_card">
													</div>
													<div class="row clearfix">
															<div class="col-sm-6 col-lg-6">
																<a href="<?php echo base_url();?>"><button type="button" class="btn btn-success btn-block btn-add-cart waves-effect">
																	Continue Shopping
																</button>
																</a>
															</div>
															<div class="col-sm-6 col-lg-6">
																<button type="button" class="btn btn-success btn-block btn-add-cart waves-effect" id="place_order_btn" type="submit">
																	Place order
																</button>
															</div>
														</div>
											</div>
										</form>
									</div>
								</div>
								<div class="col-lg-4">
										<div class="header">
											<h5>
												<b>I</b>tem Summary
											</h5>
										</div>
										<div class="body table-responsive" style="max-height: 200px !important; height: 200px !important;overflow-y: scroll !important;">
											<table class="table">
												<thead>
													<tr>
														<th>Product</th>
														<th class="product_cart_price">Price</th>
														<th>Quantity</th>
													</tr>
												</thead>
												<tbody id="order_cart_checkout_items" >
													
												</tbody>
											</table>
										</div>
										<div class="header">
                                            <h5>
                                                <b>O</b>rder Summary
                                            </h5>
                                        </div>
                                        <div class="body table-responsive">
                                                <table class="table">
													<tbody id="order_summary_chart">
														<tr>
															<td>Total Items</td>
															<td id="total_items_count_checkout"></td>
														</tr>
														<tr>
															<td>Order Subtotal</td>
															<td class="product_cart_price" id="order_sub_total_checkout">&#8369; 390.00</td>
														</tr>
														<tr>
															<td>Shipping Fee</td>
															<td class="product_cart_price" id="shipping_fee_td">&#8369; 399.00</td>
														</tr>
														<tr id="before_equivalent">
															<td><b>Total</b></td>
															<td class="product_cart_price" id="total_total_total_checkout"></td>
														</tr>
														
													</tbody>
												</table>
                                        </div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
		</div>
	</section>