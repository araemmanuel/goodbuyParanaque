    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Purchase Report
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
								<form action ="<?php echo base_url('admin/reports/summary_purchase');?>" method="POST" class='rpt'>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" data-rpt='dpurchase' value="<?php if(isset($date_from)) echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" data-rpt='dpurchase' value="<?php if(isset($date_to)) echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Filter by category</small>
										<div class="form-line success">
											<select class="show-tick selectpicker form-control rpt-cat" name="cat-name" data-live-search="true">
												<option>ALL</option>
												<?php foreach($categories as $c):?>
													<?php if(strcasecmp($cat_name, $c->cat_name) == 0) :?>
														<option selected><?=html_escape($c->cat_name)?></option>
													<?php else:?>
														<option><?=html_escape($c->cat_name)?></option>
													<?php endif;?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
								</form>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button id="btn-print-spurchase" data-href='<?php echo base_url("admin/reports/pdf_summary_purchase/$date_from/$date_to/$cat_name");?>' data-href2='<?php echo base_url("admin/reports/pdf_summary_purchase/$date_from/$date_to/$cat_name");?>' data-loc='<?php echo base_url("admin/reports/pdf_summary_purchase/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<form action ="<?php echo base_url("admin/reports/email_summary_purchase/$date_from/$date_to/$cat_name");?>" method="POST" >
								
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
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-spurchase">
										<thead>
											<tr class="col-green">
												<th>Product Code</th>
												<th>Product Name</th>
												<th>Attributes</th>
												<th>Purchase Quantity</th>		
												<th>Purchase Price</th>		
												<th>Total Cost</th>
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$grand_total_cost = 0;
											foreach($summary_purchase as $r):
												$grand_total_cost += $r->total_cost;
											?>									
											<tr>
												<td><?=html_escape($r->sku)?></td>
												<td><?=html_escape($r->name)?></td>
												<td><?=html_escape($r->options)?></td>
												<td><?=html_escape($r->quantity)?></td>		
												<td><?=html_escape(number_format($r->purchase_price, 2))?></td>
												<td><?=html_escape(number_format($r->total_cost, 2))?></td>	
											</tr>
										<?php endforeach;?>	
											
										</tbody>
										<tfoot>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>GRAND TOTAL</td>
												<td>₱ <?=html_escape(number_format($grand_total_cost,2))?></td>
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