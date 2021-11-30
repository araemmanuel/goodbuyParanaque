			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="body">
						<div class="row clearfix">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							</div>	
							<center>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">			
								<span>
									<h4>GOODBUY ENTERPRISES POS</b></h4>
									<i><?php  ?> Report</i><br>
									Parañaque City<br>
									Date: <?php echo date('M. d, Y');?><br>
									Terminal: <?php ?>
								</span>
							</div>
							</center>
						</div>
						<!-- Nav tabs -->
						<ul class="nav nav-tabs tab-col-green tab-nav-right" role="tablist">
							<?php if($report_type == 'close'): ?>
								<li role="presentation"  style="background:transparent!important;"><a href="#open" data-toggle="tab">OPEN</a></li>
								<li role="presentation" class="active" style="background:transparent!important;"><a href="#close" data-toggle="tab">CLOSE</a></li>							
							<?php else: ?>
								<li role="presentation" class="active" style="background:transparent!important;"><a href="#open" data-toggle="tab">OPEN</a></li>
								<li role="presentation" style="background:transparent!important;"><a href="#close" data-toggle="tab">CLOSE</a></li>			
							<?php endif;?>
							
						</ul>
							<!--START OPEN READING -->
							<div class="tab-content">
							
							<?php if($report_type == 'close'): ?>
								<div role="tabpanel" class="tab-pane fade" id="open">											
							<?php else: ?>
								<div role="tabpanel" class="tab-pane fade in active" id="open">																		
							<?php endif;?>
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-2 col-xs-12">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-2 col-xs-12">
											<br>
											<button type="button" class="open-print-window btn bg-green btn-block waves-effect" data-href="<?php echo base_url('cashier/print_open_reading');?>" >
												<i class="material-icons">print</i> 
												<span>PRINT</span>
											</button>
										</div>
										
										<!--  onclick="showPrint()"-->
									</div>
								
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-2 col-xs-12">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<div class="table-responsive">
												<table class="table table-hover table-condensed" style="font-size:12px;">
													<thead>
														<tr>
															<th colspan="2" style="text-align:center;" class="col-green">REPORT BREAKDOWN</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>&nbsp;&nbsp;<i>Cash Rewards Card</i></td>
															<td align="right"><?php echo $open_reading['total_rc_cash'];?></td>
														</tr>
														<tr>
															<td>&nbsp;&nbsp;<i>Cash</i></td>
															<td align="right"><?php echo $open_reading['total_cash'];?></td>
														</tr>

														<tr>
															<td><b>GRAND TOTAL</b></td>
															<td align="right"><?php  echo number_format($open_reading['total_rc_cash'] + $open_reading['total_cash'], 2, '.', '');?></td>
														</tr>
														<tr><td colspan=""2></td></tr>
														<tr>
															<td width="50%">Beginning OR #</td>
															<td width="50%" align="right"><?php echo $open_reading['begin_inv'];?></td>
														</tr>
														<tr>
															<td>Ending OR #</td>
															<td align="right"><?php  echo $open_reading['end_inv'];?></td>
														</tr>
														<tr>
															<td>Beginning</td>
															<td align="right"><?php echo $open_reading['begin_amt'];?></td>
														</tr>
														<tr>
															<td>Todays</td>
															<td align="right"><?php echo $open_reading['today_amt'];?></td>
														</tr>
														<tr>
															<td>Ending</td>
															<td align="right"><?php echo $open_reading['end_amt'];?></td>
														</tr>
														<tr><td colspan=""2></td></tr>
														<tr>
															<td width="50%">Total Cancel</td>
															<td width="50%" align="right"><?php echo $open_reading['cancel_ctr'];?></td>
														</tr>
														<tr>
															<td>Canceled Sales</td>
															<td align="right"><?php echo $open_reading['total_cancel_amt'];?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							
							<!-- END OPEN READING-->
							<!--CLOSE READING -->
							<?php if($report_type == 'close'): ?>
								<div role="tabpanel" class="tab-pane fade in active" id="close">
							<?php else: ?>
								<div role="tabpanel" class="tab-pane fade " id="close">																		
							<?php endif;?>
							
							
									<div class="row clearfix">
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										</div>
										<br>
										<form action="<?php echo base_url('cashier/reports/close');?>" method="POST">
						
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<div class="form-group form-float">
												<small class="form-label col-grey">Date From</small>
												<div class="form-line success">
													<input type="date" id="date-from" name="date-from" value="<?php echo $date_from;?>"  max="<?php echo $max_close_date;?>" class="form-control rpt-date"/>
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<div class="form-group form-float">
												<small class="form-label col-grey">Date To</small>
												<div class="form-line success">
													<input type="date" id="date-to" name="date-to" value="<?php echo $date_to;?>"  max="<?php echo $max_close_date;?>" class="form-control rpt-date"/>
												</div>
											</div>
										</div>
										
										<!--
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<small class="form-label col-grey">Cashier</small>
											<select name='cashier' id="cashier" class="form-control show-tick" data-live-search="true">
												<option value="ALL">ALL</option>
												<?php foreach($cashiers as $c): ?>
													<?php if($c->user_id == $cashier) : ?>
														<option value="<?=html_escape($c->user_id)?>" selected><?=html_escape($c->full_name)?></option>
													<?php else : ?>
														<option value="<?=html_escape($c->user_id)?>"><?=html_escape($c->full_name)?></option>
													<?php endif; ?>
												<?php endforeach;?>
											</select>
										</div>-->
										<!--
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<small class="form-label col-grey">Terminal</small>
											<select name='terminal' id="terminal" class="form-control show-tick" data-live-search="true">
												<option value="ALL">ALL</option>
												<?php foreach($terminals as $t): ?>
													<?php if($t->id == $terminal_id) : ?>
														<option value="<?=html_escape($t->id)?>" selected><?=html_escape($t->name)?></option>
													<?php else : ?>
														<option value="<?=html_escape($t->id)?>"><?=html_escape($t->name)?></option>
													<?php endif; ?>
													
												<?php endforeach;?>
											</select>
										</div>
										-->
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<br>
											<button class="btn bg-green btn-block waves-effect" type="submit">
												<i class="material-icons">add</i> 
												<span>GENERATE</span>
											</button>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<br>
											<button class="btn bg-green btn-block waves-effect open-print-window" data-href="<?php echo base_url("cashier/print_close_reading/$date_from/$date_to");?>" type="button">
												<i class="material-icons">print</i> 
												<span>PRINT</span>
											</button>
										</div>
										</form>
									</div>
									
									<div class="row clearfix">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<!--
											<div style="margin-top:10%;text-align:center;">
												<i class="material-icons" style="font-size:100px;color:#ccc;">touch_app</i><br>
												<h4 class="col-green">Report unavailable.</h4>
												<span class="col-grey">To start, choose report type, date, cashier, <br>and terminal. Click generate.</span>
											</div>
											-->
											
											<div class="table-responsive">
												<table class="table table-hover table-condensed" style="font-size:12px;">
													<thead>
														<tr>
															<th colspan="2" style="text-align:center;" class="col-green">REPORT BREAKDOWN</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>&nbsp;&nbsp;<i>Cash Rewards Card</i></td>
															<td align="right"><?php echo $close_reading['total_rc_cash'];?></td>
														</tr>
														<tr>
															<td>&nbsp;&nbsp;<i>Cash</i></td>
															<td align="right"><?php echo $close_reading['total_cash']?></td>
														</tr>
														<tr>
															<td><b>GRAND TOTAL</b></td>
															<td align="right"><?php echo number_format($close_reading['total_rc_cash'] + $close_reading['total_cash'], 2, '.', ''); ?></td>
														</tr>
														<tr><td colspan=""2></td></tr>
														<tr>
															<td width="50%">Beginning OR #</td>
															<td width="50%" align="right"><?php echo $close_reading['begin_inv'];?></td>
														</tr>
														<tr>
															<td>Ending OR #</td>
															<td align="right"><?php echo $close_reading['end_inv']; ?></td>
														</tr>
														<tr>
															<td>Beginning</td>
															<td align="right"><?php echo $close_reading['begin_amt'];?></td>
														</tr>
														<tr>
															<td>Todays</td>
															<td align="right"><?php echo $close_reading['today_amt']; ?></td>
														</tr>
														<tr>
															<td>Ending</td>
															<td align="right"><?php echo $close_reading['end_amt']; ?></td>
														</tr>
														<tr><td colspan=""2></td></tr>
														<tr>
															<td width="50%">Total Cancel</td>
															<td width="50%" align="right"><?php echo $close_reading['cancel_ctr'];?></td>
														</tr>
														<tr>
															<td>Canceled Sales</td>
															<td align="right"><?php echo $close_reading['total_cancel_amt'];?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>	
							<!-- END CLOSE READING-->
							</div>	
						</div>
					</div>					
				</div>
            </div>