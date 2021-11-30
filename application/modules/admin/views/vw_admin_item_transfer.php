    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>ITEM TRANSFER
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;">Item Transfer</li>
									<li style="background-color:transparent!important;" class="active">Transfer Items</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<!--<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="pull-right">
						<button type="button" class="btn btn-xs bg-green waves-effect">
							<a href="add-product.php" class="col-white" style="text-decoration: none;">
								<i class="material-icons">add</i> <span>ADD PRODUCT</span>
							</a>
						</button>
					</div>
				</div>-->
			</div>

            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>PRODUCTS</h2>
                        </div>
                        <div class="body">
						<form action = "<?php echo base_url('admin/transfer/process_transfer');?>" method="POST">
							<div class="row clearfix">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<?php date_default_timezone_set('Asia/Manila');?>
										<small class="form-label col-grey">Transfer Date</small>
										<div class="form-line success">
											<input type="date" name="transfer-date" class="form-control" value = "<?php echo date("Y-m-d");?>">
										</div>
										<div class='validation-errors'><?php echo form_error('transfer-date');?></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<small class="form-label col-grey">Branch Location</small>
									<select class="form-control show-tick" name='branch' data-live-search="true" required>
										<option> - Please Select -</option>
										<?php foreach($locations as $l):?>
											<option><?=html_escape($l->location)?></option>
										<?php endforeach;?>	
									</select>
									<div class='validation-errors'><?php echo form_error('branch');?></div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<br>
									<button type="submit" class="btn btn-block bg-green waves-effect">
										<i class="material-icons">swap_horiz</i> 
										<span>TRANSFER</span>
									</button>
									<div class="validation-errors"><?php echo form_error('sku[]');?></div>
									<div class="validation-errors"><?php echo form_error('qty[]');?></div>
								</div>
							</div>
							<br>
							<!--
							<div class="row clearfix">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Product Code</small>
										<div class="form-line success">
											<input type = "text" name="prod-code" id="prod-code" class="form-control" />
										</div>
									</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="width:330px;height:80px;overflow-y: scroll;" id="div-prod-code">
											<ul class="list-unstyled ul-ajax" id = "ul-prod-code">
											<!--<li class="li-prod-name">Wacc3  Attributes: Blue</li>
											</ul>
										</div>	
								</div>
								
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Quantity</small>
										<div class="form-line success">
											<input type="number" name="transfer-qty" id = "quantity" class="form-control" />
											</div>
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button type="button" id ="btn-transfer-box" class="btn btn-block bg-green waves-effect">
										<i class="material-icons">add</i> 
										<span>ADD TO TRANSFER BOX</span>
									</button>
								</div>
							</div>
							<br>
							-->
							<div class="table-responsive">
								<table class="table table-condensed table-hover js-basic-example dataTable" id="tbl-confirm-items">
									<thead>
										<tr class="col-green">
											<th>Product Code</th>
											<th>Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="tbody-items">
									</tbody>
								</table>
							</div>
							<br>
						</form>
							<br>
							<div class="table-responsive">
                                <table class="table table-condensed table-hover" id = "dt-transfer">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
                                            <th><input type="checkbox" class="chk-col-green" id='chk-all-transfer' name="chk[]" /><label for="chk-all-transfer"></label></th>
											<th>Code</th>
                                            <th>Name</th>
											<th>Attributes</th>
											<th>Brand</th>
											<th>Stock</th>
											<th>Purchase Price</th>
											<th>Selling Price</th>		
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items">
                                        <!---
										<?php foreach($products as $p):?>
										<?php if($p->quantity != 0): ?>
											<tr>
											
												<td><input type="checkbox" name="items" id = "chk-<?=html_escape($p->sku)?>" data-prod-code = "<?=html_escape($p->sku)?>" data-qty="<?=html_escape($p->quantity)?>" data-name="<?=html_escape($p->name)?>"  data-options="<?=html_escape($p->options)?>"/></td>
												<td><?=html_escape($p->sku)?></td>
												<td><?=html_escape($p->name)?></td>
												<td><?=html_escape($p->options)?></td>
												<td><?=html_escape($p->brand)?></td>
												<td align="center"><?=html_escape($p->quantity)?></td>
												<td align="center"><?=html_escape($p->purchase_price)?></td>
												<td align="center"><?=html_escape($p->selling_price)?></td>
												<td>
													<button type="button" class="btn btn-xs bg-default waves-effect btn-view-prod" data-prod-id ='<?=$p->prod_id?>' data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#product-info'>View</button>
												</td>
											</tr>
										<?php endif; ?>
										<?php endforeach;?>
										-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</form>
                    </div>
                </div>
            </div>
            <!-- #END# PRODUCTS -->
			
			<!-- CONFIRM TRANSFERRED ITEMS MODAL -->
			<div class="modal fade" id="modal-confirm-items" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<form action ="<?php echo base_url('admin/');?>" method="POST" id="form-edit-subcat">
							<div class="modal-header">
								<h1 class="modal-title">Confirm Items</h1>
							</div>
							<div class="modal-body">					
									<table class="table table-condensed table-hover js-basic-example dataTable" id="tbl-confirm-items">
										<tbody id="tbody-items">
										</tbody>
									</table>
						   </div>
							<div class="modal-footer">
								<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>CONFIRM</span></button>
								<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
							</div>
						</form>
					</div>

				</div>
			</div>
			<!-- #END# -->

    </section>