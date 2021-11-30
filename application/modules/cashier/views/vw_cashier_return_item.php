			<div class="row clearfix">
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
					<div class="card">
                        <div class="body">
							<h3>RETURN ITEM</h3>
							<hr>
							
							<div class="row clearfix">
								<form action ="<?php echo base_url('cashier/return_item');?>" method="POST">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label><b>RECEIPT NUMBER</b></label>
										<div class="input-group input-group-lg">
											<span class="input-group-addon">#</span>
											<div class="form-line success">
												<input type="text" name="invoice-no" class="form-control" placeholder="Enter Invoice Number" />
											</div>
										</div>
										<div class="validation-errors"><?php if(isset($return_error)) echo $return_error;?></div>
										<div class="align-center">
											<button type="submit" class="btn bg-green btn-block waves-effect">
												<i class="material-icons">check</i>
												<span>CHECK</span>
											</button>
										</div>
									</div>
								</form>
							</div>
							<br>
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php date_default_timezone_set('Asia/Manila'); ?>
									<div class="align-center">
										<span id="clock">&nbsp;</span>
										<div id="datestart">
										   <?php echo date('F d, Y'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
				<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
					<form action="<?php echo base_url('cashier/return_item/add');?>" id="form-return" method="POST">
					<input type="hidden" name="invoice-no" value="<?php if(isset($invoice_no)) echo $invoice_no;?>" />
						<div class="card" style="overflow-y:scroll;max-height:500px;height:398px;">
							<div class="body">
								<div class="table-responsive">
									<h3>ITEM LIST FOR INVOICE <?php if(isset($invoice_no)) echo $invoice_no;?></h3>
									<hr>
									<table class="table table-condensed table-hover table-bordered">
										<thead>
											<tr class="col-green">
												<th><input type="checkbox" name="chk-deliver[]" id='chk-all-deliver' onchange="checkAll(this)" /></th>
												<th>QR Code</th>
												<th>Product Name</th>
												<th>Quantity</th>
												<th>Original Price</th>
												<th>Discounted Price</th>
												<th>Discount</th>
												<th>Amount</th>
											</tr>
											<div class="validation-errors" id="sku_error"> <?php echo form_error('sku[]');?></div>

										</thead>
										<tbody>
											
											<?php 
												if(isset($items)) :
													foreach($items as $i): ?>
														<tr class="rowCont">
															<td><input type="checkbox" class="chk-sku" name="sku[]" value="<?=html_escape($i->sku)?>"/></td>
															<td><?=html_escape($i->sku)?></td>
															<td><?=html_escape($i->name)?></td>
															<td><input type="number" name="qty[]" id="<?=html_escape($i->sku)?>-qty" max="<?=html_escape($i->qty)?>" min="1" style="border: none;border-color: transparent;" value="<?=html_escape($i->qty)?>"/></td>
															<td><?=html_escape($i->selling_price)?></td>
															<td><?=html_escape($i->discounted_price)?></td>
															<td><?=html_escape($i->discount)?></td>
															<td><p><?=html_escape($i->amt_paid)?></p><input type="hidden" name="amt[]"  max="<?=html_escape($i->amt_paid)?>" min="<?=html_escape($i->amt_paid)?>" style="border: none;border-color: transparent;" value="<?=html_escape($i->amt_paid)?>" readonly /></td>
														</tr>
											<?php 
													endforeach;
												endif;
											?>
										</tbody>
									</table>
								</div>		
							</div>
						</div>
					
					<div class="card">
                        <div class="body">
							<div class="form-group">
								<label><b>REASON FOR RETURNING</b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<label><input type="checkbox" name="non-saleable" value="true" /> Add to Non-saleable</label>
								
                                <div class="form-line success">
                                    <textarea rows="2" name="reason" class="form-control no-resize" placeholder="Type the reason for returning the item here..."></textarea>
								</div>
								<div class="validation-errors" id="reason_error"> <?php echo form_error('reason');?></div>
                            </div>
							<div class="align-right">
								<button type="button" id="btn-submit-return" class="btn bg-green waves-effect">SUBMIT</button>
							</div>
						</div>
					</div>
					</form>
                </div>
            </div>
    