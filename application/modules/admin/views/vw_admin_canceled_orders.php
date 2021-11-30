	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ORDER MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Order Management</li>
							<li style="background-color:transparent!important;" class="active">Canceled Orders</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>CANCELED ORDERS</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Order No.</th>
											<th>Product</th>
											<th>Attributes</th>
											<th>Qty</th>
											<th>Total Price</th>
                                            <th>Date Ordered</th>
											<th>Date Canceled</th>
											<th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($canceled_orders as $c) : ?>
                                        <tr>
                                            <td><?=html_escape($c->order_no)?></td>
                                            <td>
                                                <div class="order-column">
                                                    <div class="row ">
                                                        <div class="col-lg-4">
                                                            <div class="product-pic-small-order-view">
															<?php if (strcasecmp($c->primary_image, 'none') == 0): ?>
																<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" width="100" height="100">
															<?php else: ?>
																<img src="<?php echo base_url($c->primary_image);?>" width="100" height="100">
															<?php endif;?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="product-pic-small-order-view-details">
                                                                <small><?=html_escape($c->name)?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
											</td>
											<td> <?=html_escape($c->options)?></td>
											<td> <?=html_escape($c->quantity)?></td>
                                            <td>&#8369; <?=html_escape($c->selling_price)?></td>
                                            <td><?=html_escape(date('M d, Y', strtotime($c->order_date)))?></td>
                                            <td><?=html_escape(date('M d, Y', strtotime($c->date_canceled)))?></td>
											<td><?=html_escape($c->reason_of_cancellation)?></td>
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
        </div>
    </section>