    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>Transaction Report
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
								<form action ="<?php echo base_url('admin/reports/detailed_transaction');?>" method="POST" >
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id="rpt-from" data-rpt="transaction" value="<?php if(isset($date_from)) echo $date_from;?>" name="date-from" class="form-control" placeholder="Choose Date From...">
										</div>
										<div class='validation-errors'></div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id="rpt-to" data-rpt="transaction" value="<?php if(isset($date_to)) echo $date_to;?>" name="date-to" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<br>
									<button type="submit" class="btn btn-lg btn-block bg-green waves-effect">GENERATE</button>	
								</div>
								</form>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<br>
									<button id="btn-print-dtrans" data-href='<?php echo base_url("admin/reports/pdf_detailed_transaction/$date_from/$date_to");?>' data-href2='<?php echo base_url("admin/reports/pdf_detailed_transaction/$date_from/$date_to");?>' data-loc="<?php echo base_url("admin/reports/pdf_detailed_transaction/");?>" class="btn btn-block bg-green waves-effect open-print-window btn-rpt-print">
										<i class="material-icons">print</i> <span>PRINT</span>
									</button>												
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									
								 <div class="table-responsive js-sweetalert">
									<table class="table table-condensed table-hover dt-rpt" id="dt-dtrans">
										<thead>
											<tr class="col-green">
												<th>Invoice Number</th>
												<th>Transaction Date</th>
												<th>Total Amount</th>		
												<th>Total Discount Incurred</th>
											</tr>
										</thead>
										<tbody>
										
										<?php 
											$grand_total_amount = $grand_total_discount = 0;
											$trans_ctr = 0;
											foreach($detailed_transaction as $r):
												$trans_ctr++;
												$grand_total_amount += $r->total_amt;
												$grand_total_discount += $r->total_discount;
												 if($r->date)
													$date = date('M d, Y g:iA',strtotime($r->date));
												else
													$date = null; 
												if($r->total_discount)
													$discount = $r->total_discount;
												else
													$discount = 0;	
											?>									
											<tr>
												<td><?=html_escape($r->invoice_no)?></td>
												<td><?=html_escape($date)?></td>
												<td><?=html_escape(number_format($r->total_amt,2))?></td>
												<td><?=html_escape(number_format($discount,2))?></td>   	
											</tr>
										<?php endforeach;?>	
											
										</tbody>
										<tfoot>
											<tr>
												<td><?php echo 'Total No. of Transactions: ' . $trans_ctr;?></td>
												<td align="right">GRAND TOTAL </td>
												<td>₱<?php echo number_format($grand_total_amount, 2);?></td>
												<td>₱<?php echo number_format($grand_total_discount, 2);?></td>
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