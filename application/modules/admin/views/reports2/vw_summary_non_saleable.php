    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Non-saleable Items Report
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
								<form action ="<?php echo base_url('admin/reports/summary_non_saleable');?>" method="POST" class='rpt'>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" value="<?php if(isset($date_from)) echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" value="<?php if(isset($date_to)) echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								</form>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button id = "btn-print-snonsale" data-href='<?php echo base_url("admin/reports/pdf_summary_non_saleable/$date_from/$date_to");?>' data-href2='<?php echo base_url("admin/reports/pdf_summary_non_saleable/$date_from/$date_to");?>' data-loc='<?php echo base_url("admin/reports/pdf_summary_inventory/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-snonsale">
										<thead>
											<tr class="col-green">
												<th>Category</th>
												<th>Sub<br>category</th>
												<th>Product Code</th>		
												<th>Product Name</th>	
												<th>Attributes</th>													
												<th>Unit Cost</th>		
												<th>Quantity</th>		
												<th>Total Cost</th>			
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$grand_total_cost = 0;
											foreach($summary_ns as $i):
												$grand_total_cost = $grand_total_cost + $i->total_loss;
											?>									
											<tr>
												<td><?=html_escape($i->cat_name)?></td>
												<td><?=html_escape($i->subcat_name)?></td>
												<td><?=html_escape($i->sku)?></td>		
												<td><?=html_escape($i->name)?></td>
												<td><?=html_escape($i->options)?></td>
												<td><?=html_escape(number_format($i->selling_price,2))?></td>
												<td><?=html_escape($i->qty)?></td>
												<td><?=html_escape(number_format($i->total_loss,2))?></td>	
											</tr>
										<?php endforeach;?>	
											
										</tbody>
										<tfoot>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>GRAND TOTAL</td>
												<td>₱ <?php echo number_format($grand_total_cost, 2);?></td>
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