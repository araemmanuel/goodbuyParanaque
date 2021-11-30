    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/non_saleable');?>">Non-saleable Items</a></li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix jsdemo-notification-button">
			
				<!-- NON SALEABLE -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>NON-SALEABLE ITEMS</h2>
                        </div>
                        <div class="body">
							<form id="form-ns" action = "<?php echo base_url('admin/inventory/ns_add');?>" method="POST">
                            <div class="row clearfix">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Product Code</small>
										<div class="form-line success">
											<input type="text" name = "sku" id="ns-prod-code" class="form-control">
										</div>
										<div class='validation-errors' id="sku_error"><?php echo form_error('sku');?></div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="width:330px;height:80px;overflow-y: scroll;" id="div-prod-code">
										<ul class="list-unstyled ul-ajax" id = "ul-prod-code">
										</ul>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Quantity</small>
										<div class="form-line success">
											<input type="text" name = "qty"  class="form-control">
										</div>
										<div class='validation-errors'  id="qty_error"><?php echo form_error('qty');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Reason</small>
										<div class="form-line success">
											<input type="text" name = "reason"  class="form-control">
										</div>
										<div class='validation-errors'  id="reason_error"><?php echo form_error('reason');?></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button id="btn-ns" class="btn btn-block bg-green waves-effect" type="submit">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
									<?php if(isset($alert_type)) echo $alert_type; ?>
								</div>
							</div>
							</form>
							
                            <div class="table-responsive js-sweetalert">
                                <table class="table table-condensed table-hover js-basic-example dataTable" id="dt-ns">
                                    <thead>
                                        <tr class="col-green">
											<th>Date Added</th>
											<th>Product Code</th>
                                            <th>Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Reason</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($ns_items as $i):?>
									<tr>
										<td><?=html_escape(date('M d, Y', strtotime($i->date_added)))?></td>
										<td><?=html_escape($i->sku)?></td>
										<td><?=html_escape($i->name)?></td>
										<td><?=html_escape($i->options)?></td>
										<td><?=html_escape($i->qty)?></td>
										<td><?=html_escape($i->description)?></td>
										<td>
											<button type="button" class="btn btn-xs bg-green waves-effect open-edit-ns" data-toggle="modal" data-ns-id="<?=html_escape($i->id)?>" data-sku="<?=html_escape($i->sku)?>" data-qty="<?=html_escape($i->qty)?>" data-reason="<?=html_escape($i->description)?>" data-target="#modal-edit-ns">Edit</button>
											<button type="button" class="btn btn-xs bg-default waves-effect confirm"  data-title="Are you sure you want to cancel non-saleable status for this item?" data-url="<?php echo base_url("admin/inventory/ns_cancel/$i->id/$i->sku/$i->qty");?>">Cancel</button>
										</td>
									</tr>
									<?php endforeach;?>		
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# CATEGORY -->
			<!-- EDIT NON-SALEABLE MODAL -->
			<div class="modal fade js-sweetalert" id="modal-edit-ns" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="pull-right">
								&nbsp;
								<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
									<i class="material-icons col-grey">close</i>
								</a>
							</div>
							<b><span>EDIT NON-SALEABLE ITEM</span></b>
							<hr>
						  </div>		
                        <div class="modal-body">	
							<form action = "<?php echo base_url('admin/inventory/ns_edit'); ?>" id = "form-edit-ns" method="POST" >
								<div class="row clearfix">
								<input type="hidden" name="modal-id"  id="modal-id" />
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Product Code</small>
											<div class="form-line success">
												<input type="text" name="modal-sku" id="modal-sku" class="form-control">
											</div>
											<div class='validation-errors' id = "sku_error"></div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Quantity</small>
											<div class="form-line success">
												<input type="number" min="1" name="modal-qty"  id="modal-qty" class="form-control">
											</div>
											<div class='validation-errors' id = "qty_error"></div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Reason</small>
											<div class="form-line success">
												<input name="modal-reason"  id="modal-reason" cols="30" rows="2" class="form-control" >
											</div>
											<div class='validation-errors' id = "reason_error"></div>
										</div>
									</div>
								</div>
								</form>
						</div>
						<div class="modal-footer">
                            <button type="button" id = 'btn-edit-ns' class="btn bg-green waves-effect">SAVE</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </section>
