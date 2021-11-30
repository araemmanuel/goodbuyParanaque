    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Sold Items Report
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
								<form action ="<?php echo base_url('admin/reports/summary_sold');?>" method="POST" class='rpt'>
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
									<button id="btn-print-ssold" data-href='<?php echo base_url("admin/reports/pdf_summary_sold/$date_from/$date_to");?>' data-href2='<?php echo base_url("admin/reports/pdf_summary_sold/$date_from/$date_to");?>' data-loc='<?php echo base_url("admin/reports/pdf_summary_sold/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
							</div>
								
							<div class="row clearfix">
								<form action ="<?php echo base_url("admin/reports/email_summary_sold/$date_from/$date_to");?>" method="POST" >
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Default Email</small>
											<div class="form-line success">
												<input type="text" name="default-email" value="almiranez.gecilie@gmail.com" name="email-add" class="form-control" />
											</div>
											<!-- almiranez.gecilie@gmail.com-->
											<div class='validation-errors'></div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<br>
											<button type="submit" class="btn btn-block bg-green waves-effect">
												<i class="material-icons">email</i> <span>SEND TO EMAIL</span>
											</button>									
									</div>
								</form>
							</div>
							
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-ssold">
										<thead>
											<tr class="col-green">
												<th>Category</th>
												<th>Sub<br>category</th>
												<th>Product Code</th>		
												<th>Product Name</th>		
												<th>Attributes</th>		
												<th>Unit Cost</th>		
												<th>Unit Sold</th>		
												<th>Total Cost</th>	
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$grand_total_cost = 0;
											foreach($summary_sold as $r):
												$grand_total_cost += $r->total_cost;
											?>									
											<tr>
												<td><?=html_escape($r->cat_name)?></td>
												<td><?=html_escape($r->subcat_name)?></td>
												<td><?=html_escape($r->sku)?></td>
												<td><?=html_escape($r->name)?></td>
												<td><?=html_escape($r->options)?></td>												
												<td><?=html_escape(number_format($r->unit_cost,2))?></td>
												<td><?=html_escape($r->unit_sold)?></td>
												<td><?=html_escape(number_format($r->total_cost,2))?></td>	
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