    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ITEM TRANSFER
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Item Transfer</li>
							<li style="background-color:transparent!important;" class="active">Transferred Items</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>TRANSFERRED ITEMS</h2>
                        </div>
                        <div class="body">
						<form action = "<?php echo base_url('admin/transfer/process_receive');?>" method="POST" >
							<div class="row clearfix">
								<div class="col-lg-9 col-md-9 col-sm-0 col-xs-0"></div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<button type="submit" class="btn btn-block bg-green waves-effect" data-toggle="modal" data-target="#price-tags">
										<i class="material-icons">swap_horiz</i> 
										<span>TRANSFER BACK</span>
									</button>
									<div class="validation-errors"><?php echo form_error('sku[]');?></div>
									<div class="validation-errors"><?php echo form_error('qty[]');?></div>
								</div>
							</div>
							<br>
							<!---
							<div class="row clearfix">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Product Code</small>
										<div class="form-line success">
											<input type = "text" name="prod-code" id="receive-prod-code" class="form-control" />
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
											<input type="number" name="transfer-qty" id = "transfer-qty" class="form-control" />
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
							-->
							<br>
							<div class="table-responsive">
								<table class="table table-condensed table-hover js-basic-example dataTable" id="tbl-confirm-items-for-receive">
									<thead>
										<tr class="col-green">
											<th>Product Code</th>
											<th>Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Location</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="tbody-items">
									</tbody>
								</table>
							</div>
							</form>
							<br>				
							<div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable" id = "dt-receive">
                                    <thead>
                                        <tr class="col-green">
                                            <th><input type="checkbox" id='chk-all-receive' class="chk-col-green" name="chk[]" /><label for="chk-all-receive"></label></th>
											<th>Code</th>
                                            <th>Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Price</th>
											<th>Last Transfer Date</th>
											<th>Location</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($products as $p): ?>
                                        <tr>
											<td>
												<input type="checkbox" name="receive_items" class="chk-col-green" id="chk-<?=html_escape($p->sku)?>"  data-prod-code = "<?=html_escape($p->sku)?>" data-qty="<?=html_escape($p->total_qty)?>" data-name="<?=html_escape($p->name)?>" data-location = '<?=html_escape($p->location)?>' data-loc-id = '<?=html_escape($p->loc_id)?>'  data-options="<?=html_escape($p->options)?>"/>
												<label for="chk-<?=html_escape($p->sku)?>"></label>
											</td>
											<td><?=html_escape($p->sku)?></td>
                                            <td><?=html_escape($p->name)?></td>
											<td><?=html_escape($p->options)?></td>
											<td><?=html_escape($p->total_qty)?></td>
                                            <td><?=html_escape($p->selling_price)?></td>
                                            <td><?=html_escape(date('M. d Y', strtotime($p->last_transfer_date)))?></td>
											<td><?=html_escape($p->location)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-default waves-effect btn-view-prod" data-prod-id ='<?=$p->prod_id?>' data-sku = "<?=$p->sku?>" data-toggle="modal" data-target='#product-info'>View</button>
											</td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# PRODUCTS -->
        </div>
    </section>
