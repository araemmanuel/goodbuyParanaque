<section class="content">
		<div class="common_element_view_banner">
			<h2>Your Cart</h2>
			<ol class="breadcrumb breadcrumb-col-green">
				<li><a href="<?php echo base_url(); ?>" >Home</a></li>
				<li><a href="<?php echo base_url('customer/view_cart'); ?>">Cart</a></li>
			</ol>
		</div>
		<div class="bg-white">
			<div class="profile_view_holder">
				<div class="row">
					<div class="col-lg-12 profile_view">
						<div class="card profile_view_card">
							<div class="row">
								<div class="col-lg-8 col-sm-12">
                                    <div class="header">
                                        <h5>
                                            Manage Cart
                                        </h5>
                                    </div>
									<div class="table-responsive" id="table-of-orders-div">
		                                <table class="table table-hover dataTable">
		                                    <thead>
		                                        <tr>
		                                            <th>Product Details</th>
                                                    <th>Quantity</th>
		                                            <th class="product_cart_price">Subtotal</th>
		                                            <th>Actions</th>
		                                        </tr>
		                                    </thead>
		                                    <tfoot>
		                                        <tr>
		                                            <th>Product Details</th>
                                                    <th>Quantity</th>
		                                            <th class="product_cart_price">Subtotal</th>
		                                            <th>Actions</th>
		                                        </tr>
		                                    </tfoot>
		                                    <tbody class="table-of-orders" id="items_in_cart">
		                                    </tbody>
		                                </table>
		                            </div>
								</div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="header">
                                            <h5>
                                                <b>O</b>rder Summary
                                            </h5>
                                        </div>
                                        <div class="body table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Total Items</td>
                                                            <td class="right" id="total_items_count"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Order Subtotal</td>
                                                            <td class="right" id="order_sub_total">&#8369; 390.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Shipping Fee</td>
                                                            <td class="right" id="order_shopping_fee_td" >&#8369; 390.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Total</b></td>
                                                            <td class="right" id="total_total_total">&#8369; 39,990.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                        </div>
                                        <button type="button" class="btn bg-green btn-block waves-effect" id="checkout_btn_cart">
											 <a href="<?php echo base_url('customer/view_checkout')?>" class="href_link col-white" id="checkout_btn_cart_link">Proceed to Checkout</a>
										</button>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	