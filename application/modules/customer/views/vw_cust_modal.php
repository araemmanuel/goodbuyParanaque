<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
						<div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">SHOPPING CART SUMMARY</h4>
                        </div>
                        <div class="modal-body">
							<div class="row">
								<div class="col-lg-8">
									<div class="header">
											<h5>
												<b>I</b>tem Summary
											</h5>
									</div>
									<div class="body table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Product</th>
												<th class="product_cart_price">Price</th>
												<th>Quantity</th>
												<th class="product_cart_price">Subtotal</th>
											</tr>
										</thead>
										<tbody id="order_modal_items">
											
										</tbody>
									</table>
									</div>
								</div>
								<div class="col-lg-4">
									    <div class="header">
											<h5>
												<b>O</b>rder Summary
											</h5>
										</div>
										<div class="body">
											<div class="row clearfix">
												<table class="table">
													<tbody id="order_summary_chart">
														<tr>
															<td>Total Items</td>
															<td id="total_items_count">3</td>
														</tr>
														<tr>
															<td>Order Subtotal</td>
															<td class="product_cart_price" id="order_sub_total">&#8369; 390.00</td>
														</tr>
														<tr>
															<td>Shipping Fee</td>
															<td class="product_cart_price" id="shipping_fee_td_id">&#8369; 399.00</td>
														</tr>
														<tr>
															<td><b>Total</b></td>
															<td class="product_cart_price" id="total_total_total"></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
                            <a href="<?php echo base_url('customer/view_checkout')?>" style="text-decoration: none;"><button type="button" class="btn bg-green waves-effect">PROCEED TO CHECKOUT</button></a>
                            <a href="<?php echo base_url(); ?>"><button type="button" class="btn btn-link waves-effect">CONTINUE SHOPPING</button></a>
                        </div>
					</div>
                </div>
            </div>
			
			
			<div class="modal fade" id="largeModal_exceed_quantity" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
						<div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">ITEMS EXCEEDING QUANTITY</h4>
							<h6>Why does this happen? <a href="<?echo base_url('customer/help'); ?>">Click here.</a></h6>
                        </div>
                        <div class="modal-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="header">
											<h5>
												<b>I</b>tem Summary
											</h5>
									</div>
									<div class="body table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Product</th>
												<th class="product_cart_price">Price</th>
												<th>Quantity (Cart)</th>
												<th>Quantity (Stock)</th>
											</tr>
										</thead>
										<tbody id="order_modal_exceed_items">
											
										</tbody>
									</table>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
                            <a href="<?php echo base_url('customer/view_cart')?>" style="text-decoration: none;"><button type="button" class="btn bg-green waves-effect">EDIT CART</button></a>
                            <a href="<?php echo base_url(); ?>"><button type="button" class="btn btn-link waves-effect">CONTINUE SHOPPING</button></a>
                        </div>
					</div>
                </div>
            </div>