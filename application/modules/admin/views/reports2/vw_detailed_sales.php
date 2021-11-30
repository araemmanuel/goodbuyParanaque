    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Sales Report
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
								<form action ="<?php echo base_url('admin/reports/detailed_sales');?>" method="POST" class='rpt'>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" data-rpt="dsales" value="<?php echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" data-rpt="dsales" value="<?php echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Filter by Category</small>
										<div class="form-line success">
											<select name = "cat-name" id="rpt-cat_id" class="show-tick selectpicker form-control" data-live-search="true">
												<option value="" selected>ALL</option>
												<?php foreach($categories as $c):?>
													<?php if($c->cat_id == $cat_name):?>
														<option value = "<?=html_escape($c->cat_id)?>" selected><?=html_escape($c->cat_name)?></option>
													<?php else:?>
														<option value = "<?=html_escape($c->cat_id)?>"><?=html_escape($c->cat_name)?></option>
													<?php endif;?>
												<?php endforeach;?>
												</select>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group form-float">
										<small class="form-label col-grey">Filter by Subcategory</small>
										<div class="form-line success">
											<select name = "subcat-name" id="rpt-subcat_name" class="show-tick selectpicker form-control" data-live-search="true">
											</select>
										</div>	
									</div>
								</div>
								</form>
								<?php 
									if($subcat_name == "")
										$subcat_name = 'ALL';
									if($cat_name == "")
										$cat_name = 'ALL';
								?>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<br>
									<button id="btn-print-dsales" data-href='<?php echo base_url("admin/reports/pdf_detailed_sales/$date_from/$date_to/$cat_name/$subcat_name");?>' data-href2='<?php echo base_url("admin/reports/pdf_detailed_sales/$date_from/$date_to/$cat_name/$subcat_name");?>' data-subcat="<?php echo $subcat_name;?>" data-loc='<?php echo base_url("admin/reports/pdf_detailed_sales/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<form action ="<?php echo base_url("admin/reports/email_detailed_sales/$date_from/$date_to/$cat_name/$subcat_name/");?>" method="POST" >
								
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
									<table class="table table-condensed table-hover dt-rpt" id="dt-dsales">
										<thead>
											<tr class="col-green">
												<th>Product Code</th>
												<th>Product Name</th>
												<th>Attributes</th>
												<th>Quantity</th>
												<th>Sales Date</th>		
												<th>Invoice</th>					
												<th>Amount</th>
												<th>Discount Incurred</th>	
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$total_invoice =  0.00;
											$total_discount = 0.00;
											foreach($detailed_sales as $s):
												$total_invoice += $s->amt_paid;
												$total_discount+= $s->discount;
												$date = date('m-d-Y', strtotime($s->date));
											?>									
											<tr>
												<td><?=$s->sku?></td>
												<td><?=$s->name?></td>
												<td><?=$s->options?></td>
												<td><?=$s->qty?></td>
												<td><?=$date?></td>
												<td><?=$s->invoice?></td>
												<td><?=number_format($s->amt_paid, 2)?></td>   
												<td><?=number_format($s->discount, 2)?></td>  	
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
												<td>GRAND TOTAL</td>
												<td>₱ <?=number_format($total_invoice,2)?></td>
												<td>₱ <?=number_format($total_discount,2)?></td>
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