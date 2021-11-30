    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Tally Report	
							</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">		
								<form action ="<?php echo base_url('admin/reports/tally');?>" method="POST" id="form-tally">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Filter by category</small>
										<div class="form-line success">
											<select class="show-tick selectpicker form-control" name="cat-name" id="filter-tally" data-live-search="true">
												<option>ALL</option>
												<?php foreach($categories as $c):?>
													<?php if(strcasecmp($cat_name, $c->cat_id) == 0) :?>
														<option value="<?=html_escape($c->cat_id)?>" selected><?=html_escape($c->cat_name)?></option>
													<?php else:?>
														<option value="<?=html_escape($c->cat_id)?>" ><?=html_escape($c->cat_name)?></option>
													<?php endif;?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
								</form>
								<form action ="<?php echo base_url('admin/reports/reset_physical');?>" id="form-selected-tally" method="POST" >
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button type="submit" id = "btn-reset" class="btn btn-block bg-green waves-effect">Reset Physical Inventory</button>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button type="button" id = "btn-print-tally" data-href='<?php echo base_url("admin/reports/pdf_tally/$cat_name");?>' data-href2='<?php echo base_url("admin/reports/pdf_tally/$cat_name");?>'  class="btn btn-block bg-green waves-effect">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>	
									<div id="btn-print-tally-error" class = "validation-errors"></div>
								</div>
								<br>
								<input type="hidden" name="srch" id="srch-tally">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									 <div class="table-responsive js-sweetalert">
										<table class="table table-condensed table-hover dt-rpt" id="dt-tally">
											<thead>
												<tr class="col-green">
													<th><input type="checkbox" name="chk-tally[]" id="chk-tally-head" class="chk-tally chk-col-green" onchange="checkAll(this)" /><label for="chk-tally-head"></label></th>		
													<th>Category</th>
													<th>Sub<br>category</th>
													<th>Product Code</th>		
													<th>Product Name</th>	
													<th>Attributes</th>												
													<th>Running Inventory</th>		
													<th>Unit Sold</th>				
													<th>Unit Transferred</th>		
													<th>Stock on Hand</th>
													<th>Physical Inventory</th>				
												   <th>Missing</th>	
												</tr>
											</thead>
											<tbody>
											
											<?php foreach($tally as $r): ?>									
												<tr>
													<td><input type="checkbox" name="chk-tally[]" id="<?=html_escape($r->sku)?>" class="chk-tally chk-col-green" value="<?=html_escape($r->sku)?>" /><label for="<?=html_escape($r->sku)?>"></label></td>
													<td><?=html_escape($r->cat_name)?></td>
													<td><?=html_escape($r->subcat_name)?></td>
													<td><?=html_escape($r->sku)?></td>		
													<td><?=html_escape($r->name)?></td>
													<td><?=html_escape($r->options)?></td>
													<td><?=html_escape($r->run_inventory)?></td>
													<?php if($r->unit_sold):?>
														<td><?=html_escape($r->unit_sold)?></td>
													<?php else: ?>
														<td>0</td>
													<?php endif; ?>
													<?php if($r->qty_transferred):?>
														<td><?=html_escape($r->qty_transferred)?></td>
													<?php else: ?>
														<td>0</td>
													<?php endif; ?>
													<td><?=html_escape($r->stock)?></td>
													<td><?=html_escape($r->scanned_qty)?></td>
													<td><?=html_escape($r->missing)?></td>
												</tr>
											<?php endforeach;?>	
											</tbody>
										</table>
									</div>
								
								</div>	
								</form>		
							</div>			
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
    </section>