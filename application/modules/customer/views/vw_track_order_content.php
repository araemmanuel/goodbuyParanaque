	<section class="content">
        <div class="common_element_view_banner">
            <h2>Track Order</h2>
        </div>
        <div class="bg-white">
            <div class="profile_view_holder">
                <div class="row">
                    <div class="col-lg-12 profile_view" id="modal_and_others">
                        <div class="card profile_view_card">
                            <div class="row">
								<div class="col-lg-8 col-sm-12">
                                    <i class="material-icons col-green card-message">track_changes</i>
                                    <div class="card-message-content" id="card_message_error_handler">
                                        <br/>
                                        <h4 id="message_1">Hi. It's nice to have you as our customer!</h4>
                                        <p id="message_2">Please, fill up the mini form we have provided to track your order.</p>
                                    </div>
                                    <br/>
									<form role="form" id="form_track_order" method="POST">
										<div class="row clearfix">
											<div class="col-sm-12 col-lg-4">
												<div class="form-group form-float">
													<div class="form-line">
														<input type="text" class="form-control" value="" name="track_order_order_id" placeholder="Order Code" />
													</div>
													<span class="small-smaller" id="track_order_order_id_label"><p></p></span>
												</div>
											</div>
											<div class="col-sm-12 col-lg-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" value="" name="track_order_email" placeholder="Email Used" />
													</div>
													<span class="small-smaller" id="track_order_email_label"><p></p></span>
												</div>
											</div>
										</div>
										<button type="button" class="btn bg-green btn-lg waves-effect" id="track_my_order_btn">Track My Order</button>
									</form>
									<div class="card-message-content" id="card_message_error_handler_2">
                                        <br/>
                                        <p id="message_d_o">Date Ordered  : </p>
                                        <p id="message_s_o">Status      :</p>
										<p id="message_t_c">Total Cost     :</p>
                                    </div>
									
									<div class="card-message-content" id="card_table_of_products">
									<br />
										<table class="table table-hover col-black" id="table_of_orders_product">
		                                    <thead>
		                                        <tr>
		                                            <th>Product Details</th>
		                                            <th class="product_cart_price">Subtotal</th>
		                                            <th>Actions</th>
		                                        </tr>
		                                    </thead>
		                                    <tfoot>
		                                        <tr>
		                                            <th>Product Details</th>
		                                            <th class="product_cart_price">Subtotal</th>
		                                            <th>Actions</th>
		                                        </tr>
		                                    </tfoot>
		                                    <tbody class="table-of-orders" id="table_of_orders_product">
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
