
    <section class="content">
        <div class="container-fluid">
		  <form action="<?php echo base_url('admin/order_management/batch_print');?>" target="_blank" id='form-order-bprint' method="POST">
			
			<div class="row clearfix">
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="block-header">
						 <h2>ORDER MANAGEMENT
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Order Management</li>
							<li style="background-color:transparent!important;" class="active">Customer Orders</li>
						</ol>
					</small>
					</h2>
					</div>
				</div>
			
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="pull-right">
						<button type="submit" class="btn btn-xs bg-green waves-effect col-white"  style="text-decoration: none;">
							<i class="material-icons">print</i> <span>BATCH PRINT DELIVERY RECEIPTS</span>
						</button>
						<div class="validation-errors" id="batch-delivery-receipt"></div>
					</div>
				</div>
			</div>	
				
           

            <!-- DATA TABLE -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ORDERS</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id = "dt-orders">
                                    <thead>
                                        <tr class="col-green">
											<th><input type="checkbox" name="chk-deliver[]" id='chk-all-deliver' class="chk-col-green" onchange="checkAll(this)" /><label for="chk-all-deliver"></label></th>
                                            <th>Order No.</th>
											<th>Qty</th>
                                            <th>Total</th>
                                            <th>Date Ordered</th>
											<th>Date Delivered/Picked Up</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-of-orders">
										<?php foreach($orders as $o): ?>
										<?php  if($o->order_no): ?>
                                        <tr>
										<?php if(strcasecmp($o->order_type, 'cod') == 0): ?>
											<td><input type="checkbox" name="chk-deliver[]" class="chk-col-green" id = "chk-<?=html_escape($o->order_no)?>" value="<?=html_escape($o->order_no)?>" /><label for="chk-<?=html_escape($o->order_no)?>"></label></td>	
										<?php else: ?>
										<td> &nbsp </td>
										<?php endif;?>	
										 <td><?=html_escape($o->order_no)?></td>
                                         <!--   <td>
                                                <div class="order-column">
                                                    <div class="row ">
                                                        <div class="col-lg-8">
                                                            <div class="product-pic-small-order-view-details">
                                                                <small><?=html_escape($o->name)?></small><br/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
											</td>-->
											<td><?=html_escape($o->order_qty)?></td>
                                            <td class="product_cart_price">&#8369; <?=html_escape($o->total_price)?></td>
                                            <td> <?=html_escape(date('M. d, Y', strtotime($o->order_date)))?></td>
                                            <?php if($o->delivered_date): ?>
												<td> <?=html_escape(date('M. d, Y', strtotime(($o->delivered_date))))?></td>
                                            <?php else: ?>
												<td> <?=html_escape('N/A')?></td>
											<?php endif;?>
											<td> <?=html_escape($o->order_status)?></td>
                                            <td>
												<a href="<?php echo base_url('admin/order_management/order_items/'.$o->order_no);?>" class="btn btn-xs waves-effect" style="background-color:#ddd;color:#555!important;">View Items</a>
												<?php if(strcasecmp($o->order_type, 'cod') == 0): ?>
												<?php $btnName = 'Set as Delivered'; 
													  $btnCancel = 'Cancel Delivered Status';?>	
												<?php else: ?>
													<?php $btnName = 'Set as Picked up'; 
													  $btnCancel = 'Cancel Picked Up Status';?>	
												<?php endif;?>
												<?php if(strcasecmp($o->order_status, 'pending') == 0): ?>
														<button type="button" class="btn btn-xs bg-green waves-effect open-deliver-date"  data-order-no="<?=html_escape($o->order_no)?>" data-href="<?php echo base_url('admin/order_management/deliver_order');?>" data-toggle="modal" data-target='#modal-deliver-date'><?php echo $btnName;?></button>			
												<?php else: ?>
													<button type="button" class="btn btn-xs bg-orange waves-effect confirm" data-title="Are you sure you want to cancel delivered status for this order?" data-msg="This action will remove delivery date and order status." data-url="<?php echo base_url('admin/order_management/cancel_delivered_status/'.$o->order_no);?>"><?php echo $btnCancel;?></button>
												<?php endif;?>
												<?php if(strcasecmp($o->order_type, 'cod') == 0): ?>
													<button type="button" class="btn btn-xs bg-default waves-effect open-print-window" data-href="<?php echo base_url("admin/order_management/receipt/$o->order_no");?>">Print Delivery Receipt</button>
												<?php endif;?>
											</td>
                                        </tr>
										<?php endif;?>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						</form>
						<!--  -->
						<div class="modal fade" id="modal-deliver-date" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-sm" role="document">
							   <div class="modal-content">
								<form method="POST" id="form-set-deliver-date">
									<div class="modal-header">
										<h3 class="modal-title">Set Date</h3>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<small>Date </small>
											<input type="hidden" name="modal-order-no" id = "modal-order-no" class="form-control" >
											<div class="form-line success">
												<input type="date" name="modal-date-delivered" value="<?php echo date('Y-m-d');?>" class="form-control" >
											</div>
											<div class="validation-errors" id = "delivered_date_error"></div>
										</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn bg-green waves-effect" id ="btn-set-deliver-date"><i class="material-icons">check</i> <span>CONFIRM</span></button>
											<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
										</div>
								</div>
								</form>
							</div>
						</div>
						<!-- #END# -->	
						
						
                    </div>
                </div>
            </div>
			
            <!-- #END# DATA TABLE -->
        </div>
    </section>