
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ORDER MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Order Management</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/order_management/customer_order');?>">Customer Orders</a></li>
							<li style="background-color:transparent!important;" class="active">Order Items</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php foreach($orders as $o): ?>
                        <div class="header">
                            <small>ORDER NO.</small>
							<h2><?=html_escape($o->order_no)?></h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
								<div class="align-center">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<small>Date Ordered</small><br>
										<span class="font-20"><?=html_escape(date('M. d, Y', strtotime($o->order_date)))?></span>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<small>Status</small><br>
										<span class="font-20"><?=html_escape($o->order_status)?></span>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<small>Total Price</small><br>
										<span class="font-20">&#8369; <?=html_escape($o->total_price)?></span>
									</div>
								</div>
                            </div>
							
							<br>
							<?php endforeach;?>
							<div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
                                            <th>No.</th>
											<th>Product</th>
											<th>Attributes</th>
											<th>Price</th>
                                            <th>Qty</th>
											<th>Item Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-of-orders">
										<?php 
											$line_no = 0;
											foreach($order_details as $o):
											$line_no++;
										?>
                                        <tr>
                                            <td><?php echo $line_no;?></td>
                                            <td>
                                                <div class="order-column">
                                                    <div class="row ">
                                                        <div class="col-lg-5">
                                                            <div class="product-pic-small-order-view">
															<?php if (strcasecmp($o->primary_image, 'none') == 0): ?>
																<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" width="100" height="100">
															<?php else: ?>
																<img src="<?php echo base_url($o->primary_image);?>" width="100" height="100">
															<?php endif;?> 
															</div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="product-pic-small-order-view-details">
                                                                <small><?=html_escape($o->prod_name)?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
											</td>
											<td><?=html_escape($o->options)?></td>
                                            <td class="product_cart_price">&#8369; <?=html_escape($o->selling_price)?></td>
											<td><?=html_escape($o->quantity)?></td>
											<td><?=html_escape(strtoupper($o->item_order_status))?></td>
											<!--<td>
												<?php  if(strcasecmp('cancelled', $o->item_order_status) != 0): ?>
													<button type="button" class="btn btn-xs bg-orange waves-effect open-print-window" data-href="<?php echo base_url("admin/order_management/receipt/$o->order_no");?>">Cancel Item Order</button>
												<?php  endif; ?>
											</td>-->	
									   </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->