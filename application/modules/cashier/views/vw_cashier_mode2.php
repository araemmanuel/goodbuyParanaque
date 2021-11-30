		<form action = "<?php echo base_url('cashier/sales_management/add');?>" id="form-pos" method = "POST">
			<div class="row clearfix">
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<div class="card scan-div">
                        <div class="body">
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div style="overflow:hidden;">
										<br><br>
										<div class="align-center">
											<video id="player2" width="80%" height="80%" autoplay></video>
											<div class = 'validation-errors' id = "scan-error"></div>
										</div>
										
										<?php date_default_timezone_set('Asia/Manila'); ?>
										<div class="align-center">
											<span id="clock">&nbsp;</span>
											<div id="datestart">
											   <?php echo date('F d, Y'); ?>
											</div>
										</div>
									</div>
									<div class="row clearfix">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<label><b>MEMBERSHIP ID</b></label>
											<div class="input-group input-group-lg">
												<span class="input-group-addon">
													<i class="material-icons">credit_card</i>
												</span>
												<div class="form-line success">
													<input type="text" name="card-no"  class="form-control" id="mem-id" placeholder="Enter Membership ID" />
												</div>
												<div style='color:blue;' id="earned-pts"></div>
												<div class="validation-errors" id="card_no_error"></div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<label><b>REWARD POINTS</b></label>
											<div class="input-group input-group-lg">
												<span class="input-group-addon">
													<i class="material-icons">exposure_plus_1</i>
												</span>
												<div class="form-line success">
													<input type="number" name="reward-pts"  min="0.00" step="1" class="form-control" placeholder="Enter Reward Points"  />
												</div>	
											<div style='color:green;' id="stored-pts"></div>
											
											<div class="validation-errors" id="reward_points_error"></div>
											</div>
										</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label><b> RECEIPT DISCOUNT</b></label><br>
											
											<div class="align-center">
												<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="none" checked> None&nbsp;</label>
												<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="amount"> Discounted Price&nbsp;</label>
												<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="percent" > Percentage&nbsp;</label>
											</div>
											
											<div class="input-group input-group-lg">
												<span class="input-group-addon">
													<i class="material-icons">local_offer</i>
												</span>
												<div class="form-line success">
													<input type="number" name="receipt-discount" id="receipt-discount"  min="0.00" step="0.01" class="form-control" placeholder="Discount Value" />
												</div>
											</div>
										</div>
									</div>
										
								</div>
								
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<br><br>
									<label><b>PRODUCT CODE</b></label>
									<div class="input-group input-group-lg">
                                        <span class="input-group-addon">
                                            <i class="material-icons">select_all</i>
                                        </span>
                                        <div class="form-line success">
                                            <input type="text" id='prod-code' class="form-control" placeholder="Enter Product Code" />
                                        </div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-prod-code" style="width:300px;height:80px;overflow-y: scroll;" >
											<ul class="list-unstyled ul-ajax" id = "ul-prod-code" >
											</ul>
										</div>
                                    </div>
									
									<label><b>QUANTITY</b></label>
									<div class="input-group input-group-lg">
                                        <span class="input-group-addon">
                                            <i class="material-icons">add</i>
                                        </span>
                                        <div class="form-line success">
                                            <input type="number" id='prod-qty' class="form-control" placeholder="Enter Quantity" />
                                        </div>
                                    </div>
									
									<label><b>DISCOUNT</b></label><br>
									<div class="align-center">
										<label class="remove-b"><input type="radio" name="d-type"  class ="d-type" value="none" checked> None&nbsp;</label>
										<label class="remove-b"><input type="radio" name="d-type"  class ="d-type" value="amount"> Discounted Price&nbsp;</label>
										<label class="remove-b"><input type="radio" name="d-type"  class ="d-type" value="percent" > Percentage&nbsp;</label>
									</div>
									<br>
									<div class="input-group input-group-lg">
                                        <span class="input-group-addon">
                                            <i class="material-icons">local_offer</i>
                                        </span>
                                        <div class="form-line success">
                                            <input type="number" min="0.00" id="discount" step="0.01" class="form-control" placeholder="Discount Value" />
                                        </div>
                                    </div>
									<div class="validation-errors" id = 'add-error'>
									</div>
									<div class="align-center">
										<button type="button" id="add-prod" class="btn btn-block bg-green waves-effect">
											<i class="material-icons">add</i>
											<span>ADD</span>
										</button>
									</div>
									<br>
									<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
										<div class="form-group form-group-lg">
											<div class="form-line success">
												<input type="number" name = 'cash' class="form-control" min="0.00" step="0.01" placeholder="Cash" style="height:81px;font-size:28px" />
											</div>
											<div class="validation-errors" id="cash_error"> <?php echo form_error('cash');?></div>
											<div class="validation-errors" id="sku_error"></div>
										</div>
										<?php if(isset($msg)): ?>
										<b>INVOICE DETAILS: </b><p><?php echo $msg;?></p>
										<?php endif; ?>
										
										<?php if(isset($invoice_no)): ?>
										
											<p> <b>CHANGE: </b> <?php echo number_format($change, 2);?>
										</p>
										<?php endif; ?>
									</div>	
								<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
									<button type="button" id="btn-pay" class="btn btn-block bg-green waves-effect" style="height:81px;font-size:28px;">PAY</button>
								<?php if(isset($invoice_no)): ?>
								<br><br>
								
									<button type="button" data-href="<?php echo base_url('cashier/print_receipt/'.$invoice_no);?>" class="open-print-window btn btn-xs bg-green waves-effect">
										<i class="material-icons">print</i>
										<span>PRINT</span>
									</button>									
									<button data-url="<?php echo base_url('cashier/sales_management/void_transaction/'.$invoice_no);?>"  data-title="Are you sure you want to void this transaction?" data-msg="This action cannot be undone." type="button" class="btn btn-xs bg-red waves-effect cashier-confirm">
										<i class="material-icons">close</i>
										<span>VOID</span>
									</button>

									<?php endif; ?>
								</div>
									
								</div>
								<input type="hidden" name="total-amt" id = "totall" />
								<input type="hidden" name="new-total-amt" id = "new-totall" />	
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="body">
							<div class="table-responsive">
								<div id="div-prod-info">
									<table class="prod-info">
										<tbody>
											<tr>
												<td width="50%" class="font-11"><b>SELLING PRICE:</b></td>
												<td><input style="border:none;border-color:transparent;width:100%;" id = 'selling_price' readonly /></td>
											</tr>
											<tr>										
												<td width="50%" class="font-11"><b>DESCRIPTION:</b></td>
												<td><input style="border:none;border-color:transparent;width:100%;" id = 'description' readonly /></td>
											</tr>
											<tr>
												<td width="50%" class="font-11"><b>CATEGORY:</b></td>
												<td><input style="border:none;border-color:transparent;width:100%;" id = 'cat_name' readonly /></td>
											</tr>
											<tr>
												<td width="50%" class="font-11"><b>OPTIONS:</b></td>
												<td><input style="border:none;border-color:transparent;width:100%;" id = 'options' readonly /></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
                        </div>
                    </div>
                </div>
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="card itemlist-div" id="item-list" style="width:510px;height:410px;overflow-y: scroll;">
						<div class="body">
							<div class="table-responsive">
								<h4>ITEM LIST</h4>
								<hr>
								<table class="table table-condensed table-hover table-bordered" id="dt-invoice">
									<thead>
										<tr class="col-green">
											<th>Product Name</th>
											<th>Individual Price</th>
											<th>Discount</th>
											<th>QTY</th>
											<th>Amount</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									
									</tbody>
									<tfoot>
										<tr class="font-17">
											<th colspan="4">Total Item(s)</th>
											<th colspan="2" id="total-qty"></th>
										</tr>
										<tr class="font-20">
											<th colspan="4">TOTAL AMOUNT</th>
											<th colspan="2" id="total-amt"></th>
										</tr>
									</tfoot>
								</table>
							</div>
                        </div>
                    </div>
                </div>
				
            </div>
			<!--
			<div class="row cleafix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
							<div class="row clearfix">
								<div class="col-lg-5 col-md-5 col-sm-0 col-xs-0"></div>
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<label><b>DISCOUNT</b></label><br>
									
									<div class="align-center">
										<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="none" checked> None&nbsp;</label>
										<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="amount"> Discounted Price&nbsp;</label>
										<label class="remove-b"><input type="radio" name="d-type2" class="d-type2" value="percent" > Percentage&nbsp;</label>
									</div>
									
									<div class="input-group input-group-lg">
                                        <span class="input-group-addon">
                                            <i class="material-icons">local_offer</i>
                                        </span>
                                        <div class="form-line success">
                                            <input type="number" name="receipt-discount" id="receipt-discount"  min="0.00" step="0.01" class="form-control" placeholder="Discount Value" />
                                        </div>
                                    </div>
								</div>
								
								<div class="col-lg-2 col-md-2 col-sm-8 col-xs-12">
									<div class="form-group form-group-lg">
										<div class="form-line success">
											<input type="number" name = 'cash' class="form-control" min="0.00" step="0.01" placeholder="Cash" style="height:81px;font-size:28px" />
										</div>
										<div class="validation-errors" id="cash_error"> <?php echo form_error('cash');?></div>
										<div class="validation-errors" id="sku_error"></div>
									</div>
									<?php if(isset($msg)): ?>
									<b>INVOICE DETAILS: </b><p><?php echo $msg;?></p>
									<?php endif; ?>
									
									<?php if(isset($invoice_no)): ?>
									<b>CHANGE FOR PREVIOUS SALE: </b><p>₱ <?php echo number_format($change, 2);?></p>
									<?php endif; ?>
									
								</div>
								<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
									<button type="button" id="btn-pay" class="btn btn-block bg-green waves-effect" style="height:81px;font-size:28px;">PAY</button>
								</div>
									<?php if(isset($invoice_no)): ?>
									<button type="button" data-href="<?php echo base_url('cashier/print_receipt/'.$invoice_no);?>" class="open-print-window btn btn-xs bg-green waves-effect">
										<i class="material-icons">print</i>
										<span>PRINT</span>
									</button>									
									<button data-url="<?php echo base_url('cashier/sales_management/void_transaction/'.$invoice_no);?>"  data-title="Are you sure you want to void this transaction?" data-msg="This action cannot be undone." type="button" class="btn btn-xs bg-red waves-effect cashier-confirm">
										<i class="material-icons">close</i>
										<span>VOID TRANSACTION</span>
									</button>

									<?php endif; ?>
								
								
								<input type="hidden" name="total-amt" id = "totall" />
								<input type="hidden" name="new-total-amt" id = "new-totall" />			
							</div>
						</div>
					</div>
				</div>
			</div>
			-->
		</form>