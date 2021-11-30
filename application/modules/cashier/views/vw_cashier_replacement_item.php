			<div class="row clearfix">
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<div class="card scan-div">
                        <div class="body">
							<h4>REPLACEMENT ITEM</h4>
							<hr>
							<input type="hidden" id="trans-id" value="<?php echo $trans_id;?>"/>
                                       
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div style="overflow:hidden;">
										<br>
										<video id="player2" width="100%" height="100%" autoplay ></video>
										<div class = 'validation-errors' id = "scan-error"></div>
										<br>
										
										<?php date_default_timezone_set('Asia/Manila'); ?>
										<div class="align-center">
											<span id="clock">&nbsp;</span>
											<div id="datestart">
											   <?php echo date('F d, Y'); ?>
											</div>
										</div>
									</div>
								</div>
							
								<input type="hidden" id="return-amt" value="<?php echo $total_amt;?>" />
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<br><br>
									<label><b>PRODUCT CODE</b></label>
									<div class="input-group input-group-lg">
                                        <span class="input-group-addon">
                                            <i class="material-icons">select_all</i>
                                        </span>
                                        <div class="form-line success">
                                            <input type="text" id="prod-code2" class="form-control" placeholder="Enter Product Code" />
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
                                            <input type="text" id="prod-qty2" class="form-control" placeholder="Enter Quantity" />
                                        </div>
                                    </div>
									<div class="validation-errors" id = 'add-error'>
									</div>
									<div class="validation-errors" id="add_error"></div>
									<div class="align-center">
										<button type="button" id="add-prod2" class="btn btn-block bg-green waves-effect">
											<i class="material-icons">add</i>
											<span>ADD</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="card itemlist-div">
						<div class="body">
						<form action="<?php echo base_url('cashier/return_item/add_replacement');?>" method="POST" id="form-replacement">
							<input type="hidden" name='trans-id' id="return-amt" value="<?php echo $trans_id;?>" />
							<div class="table-responsive">
								<h4>ITEM LIST</h4>
								<hr>
								
								<div class="align-center">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<span>Total Amount</span> 
										<h4 class="col-green"><p class="input-total-amt"></p>
											<input type="hidden" style="border: none;border-color: transparent;" class="input-total-amt" readOnly />
										</h4>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
										<span>Balance</span> 
										<h4 class="col-green"><p  class="input-balance"></p>
											<input type="hidden" style="border: none;border-color: transparent;" class="input-balance" readOnly />
										</h4>
									</div>
									
									<div class="input-group input-group-lg">
										<span class="input-group-addon">&#8369;</span>
										<div class="form-line success">
											<input type="number" name="cash" id="cash" class="form-control" min="0.00" step="0.01" placeholder="Cash" />
										</div>
										<div class="validation-errors" id="cash_error"></div>
									</div>
								</div>
								
								<table class="table table-condensed table-hover table-bordered"  id="dt-invoice">
									<thead>
										<tr class="col-green">
											<th>Product Code</th>
											<th>Product Name</th>
											<th>Qty</th>
											<th>Price</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr class="font-20">
											<th colspan="3">TOTAL AMOUNT</th>
											<th colspan="2" id="total-amt"></th>
										</tr>
									</tfoot>
								</table>
							</div>
							
							<div class="align-center">
								<button type="button" id="btn-save" class="btn btn-lg btn-block bg-green waves-effect" >SAVE</button>
							</div>
							<input type="hidden" name="total-amt" id = "totall" />
						</form>
                        </div>
                    </div>
                </div>
            </div>
			
			<!-- SAVE REPLACEMENT MODAL -->
			<div class="modal fade" id="save-replacement" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">CASH</h1>
                        </div>
                        <div class="modal-body">
							<span>Total Amount</span> <h4 class="col-green">&#8369;<input type="number" style="border: none;border-color: transparent;" id="input-total-amt" readOnly /></h4>
							<span>Balance</span> <h4 class="col-green">&#8369;<input type="number" style="border: none;border-color: transparent;" value = "<?php echo number_format($total_amt);?>" id="input-balance"  readOnly /></h4>
							<br>
							<div class="input-group input-group-lg">
                                <span class="input-group-addon">&#8369;</span>
                                <div class="form-line success">
                                    <input type="number" name="cash" id="cash" class="form-control" min="0.00" step="0.01" placeholder="Cash" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
			<!-- #END# -->