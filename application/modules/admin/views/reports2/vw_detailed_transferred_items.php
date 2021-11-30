    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Transferred Items Report
							<?php
							
							if(isset($date_from))
								echo ' from ' . date('M. d, Y', strtotime($date_from));

							if(isset($date_to))
								echo ' to ' . date('M. d, Y', strtotime($date_to));
							
							?>		
							</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">		
								<form action ="<?php echo base_url('admin/reports/detailed_transferred');?>" method="POST" class='rpt'>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" data-rpt='dtransferred' value="<?php if(isset($date_from)) echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" data-rpt='dtransferred' value="<?php if(isset($date_to)) echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Filter by Location</small>
										<div class="form-line success">
											<select class="form-control show-tick rpt-cat" name='location' data-live-search="true" required>
												<option>ALL</option>
												<?php foreach($locations as $l):?>
													<?php if(strcasecmp($l->loc_id, $location) == 0):?>
														<option value="<?=html_escape($l->loc_id)?>" selected><?=html_escape($l->location)?></option>
													<?php else: ?>
														<option value="<?=html_escape($l->loc_id)?>"><?=html_escape($l->location)?></option>
													<?php endif; ?>													
												<?php endforeach;?>	
											</select>
										</div>
									</div>
								</div>
								</form>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button id="btn-print-dtransfer" data-href='<?php echo base_url("admin/reports/pdf_detailed_transferred/$date_from/$date_to/$location");?>' data-href2='<?php echo base_url("admin/reports/pdf_detailed_transferred/$date_from/$date_to/$location");?>' data-loc='<?php echo base_url("admin/reports/pdf_detailed_transferred/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-dtransfer">
										<thead>
											<tr class="col-green">
												<th>Date Transferred</th>
												<th>Product Code</th>
												<th>Product Name</th>
												<th>Attributes</th>												
												<th>Quantity</th>	
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$item_ctr = 0;
											foreach($detailed_transferred as $r):
												$date = date('M. d, Y', strtotime($r->date_transferred));
												$item_ctr = $item_ctr + $r->qty_transferred;
											?>									
											<tr>
												<td><?=html_escape($date)?></td>
												<td><?=html_escape($r->sku)?></td>
												<td><?=html_escape($r->name)?></td>	
												<td><?=html_escape($r->options)?></td>													
												<td><?=html_escape($r->qty_transferred)?></td>												
											</tr>
										<?php endforeach;?>	
											
										</tbody>
										<tfoot>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td>Total No. of Transferred Items: </td>
												<td><?php echo $item_ctr;?></td>
											</tr>
										</tfoot>
									</table>
								</div>
								</div>								
							</div>			
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
    </section>