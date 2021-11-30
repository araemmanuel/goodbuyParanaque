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
								<form action ="<?php echo base_url('admin/reports/detailed_non_saleable');?>" method="POST" >
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" data-rpt="dsales" value="<?php if(isset($date_from)) echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" data-rpt="dsales" value="<?php if(isset($date_to)) echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
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
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
								<div class="form-group form-float">
										<small class="form-label col-grey">Filter by Subcategory</small>
										<div class="form-line success">
											<select name = "subcat-name" id="rpt-subcat_name" class="show-tick selectpicker form-control" data-live-search="true">
											</select>
										</div>	
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
								<br>
									<button type="submit" class="btn btn-lg btn-block bg-green waves-effect">GENERATE</button>	
								</div>
								</form>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<br>
									<button id = "btn-print-dnonsale" data-href='<?php echo base_url("admin/reports/pdf_detailed_non_saleable/$date_from/$date_to/$cat_name/$subcat_name");?>' data-href2='<?php echo base_url("admin/reports/pdf_detailed_non_saleable/$date_from/$date_to/$cat_name/$subcat_name");?>' data-subcat="<?php echo $subcat_name;?>" data-loc='<?php echo base_url("admin/reports/pdf_detailed_inventory/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-dnonsale">
										<thead>
											<tr class="col-green">
												<th>Date</th>
												<th>Product Code</th>
												<th>Product Name</th>
												<th>Attributes</th>												
												<th>Quantity</th>
												<th>Reason</th>	
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$total = 0;
											foreach($detailed_ns as $i):
												$date = date('M d, Y', strtotime($i->date_added));
											?>									
											<tr>
												<td><?=html_escape($date)?></td>
												<td><?=html_escape($i->sku)?></td>
												<td><?=html_escape($i->name)?></td>
												<td><?=html_escape($i->options)?></td>
												<td><?=html_escape($i->qty)?></td>         
												<td><?=html_escape($i->description)?></td>
											</tr>
										<?php endforeach;?>	
										</tbody>
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