    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Expense Report
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
								<form action ="<?php echo base_url('admin/reports/detailed_expenses');?>" method="POST" class='rpt'>
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
									<button id="btn-print-dexpense" data-href='<?php echo base_url("admin/reports/pdf_detailed_expenses/$date_from/$date_to");?>' data-href2='<?php echo base_url("admin/reports/pdf_detailed_expenses/$date_from/$date_to");?>' data-loc='<?php echo base_url("admin/reports/pdf_detailed_expenses/");?>' class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-dexpense">
										<thead>
											<tr class="col-green">
												<th>Expense</th>
												<th>Date</th>
												<th>Amount</th>	
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$exp_ctr = $total_exp_amt = 0;
											foreach($detailed_expenses as $r):
												$date = date('M. d, Y', strtotime($r->exp_date));
												$exp_ctr++;
												$total_exp_amt += $r->exp_amt;
											?>									
											<tr>
												<td><?=html_escape($r->exp_desc)?></td>
												<td><?=html_escape($date)?></td>
												<td><?=html_escape(number_format($r->exp_amt, 2))?></td>													
											</tr>
										<?php endforeach;?>	
										</tbody>
										<tfoot>
											<tr>
												<td></td>
												<td>Total No. of Expenses: <?php echo $exp_ctr; ?></td>
												<td><?php echo number_format($total_exp_amt, 2); ?></td>
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