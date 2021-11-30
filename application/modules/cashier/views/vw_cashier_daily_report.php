<!DOCTYPE html>
<html>
<head>
	<!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" type="image/x-icon">
    <!-- Google Fonts -->
	<link href="<?php echo base_url('assets/cashier/fonts/iconfont/material-icons.css');?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
   
	<!-- Custom Css -->
    <link href="<?php echo base_url('assets/cashier/css/style.css');?>" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/cashier/css/themes/theme-green.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/interface.css');?>" rel="stylesheet" />

	<style type="text/css">
	#content {
		font-size: 12.5px;
	}
	table {
		width: 80%;
	}
	td {
		padding: 8px;
	}
	</style>
</head>

<body>
    <!-- Page Content -->
    <div class="container">
		<div class="col-lg-12">
			<center>
			<div class="width:50%;">
				<div class="row clearfix">
					<div class="pull-right" style="margin-top:20px;margin-bottom:5px;">
						<button data-href="<?php echo base_url('cashier/print_daily_sales');?>" class="open-print-window btn btn-xs bg-green waves-effect">
							<i class="material-icons">print</i>
							<span>PRINT SALES REPORT</span>
						</button>
					</div>
				</div>
			</div>
			
			<div style="width:80%;background:white;border:1px solid green;">
				<div id = "content">
					<div class="row clearfix" style="margin-top:15px; margin-bottom:15px;">
						<center>
						<img src="<?php echo base_url('assets/images/gb-logo.png');?>" width="75px" height="75px">
						<H4>GoodBuy Enterprises
						<br>Z REPORT</H4>
						</center>
					</div>
			  
					<div class="row clearfix">
						<div class="col-lg-4">
							<table>
								<tr>
									<td>Report Date</td>
									<td style="text-align:right;"><?php echo date('F d, Y');?></td>
								</tr>
								<tr>
									<td>Report Time</td>
									<td style="text-align:right;"><?php echo date('h:i:s a', time());?></td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>Register</td>
									<td style="text-align:right;">GBPOS</td>
								</tr>
								<tr>
									<td>Batch Profile</td>
									<td style="text-align:right;"><?php echo ucwords($firstname . ' '. $lastname);?></td>
								</tr>
								<tr>
									<td>Batch Status</td>
									<td style="text-align:right;">Closed</td>		
								</tr>
								<?php foreach($pos_details as $pd): ?>
								<?php
								$start_time = $pd->start_time;
								$end_time = $pd->end_time;
								?>
								<tr>
									<td>Start Date</td>
									<td style="text-align:right;"><?php echo date('M d, Y', strtotime($pd->start_date));?></td>
								</tr>
								<tr>
									<td>Start Time</td>
									<td style="text-align:right;"><?php echo $pd->start_time;?></td>		
								</tr>
								<tr>
									<td>End Date</td>
									<td style="text-align:right;"><?php echo date('M d, Y', strtotime($pd->end_date));?></td>
								</tr>
								<tr>
									<td>End Time</td>
									<td style="text-align:right;"><?php echo $pd->end_time;?></td>
								</tr>
							</table>
						</div>
						<div class="col-lg-4">
							<table>
								<?php endforeach; ?>
								<?php $sale = ($sales_amt-$void_amt) - (($sales_amt-$void_amt) * 0.12);?>
								<?php $tax = $sales_amt * 0.12;?>
								<?php $total = $sale + $tax;?>
								<tr>
									<td>Item Sales</td>
									<td style="text-align:right;"><?php echo number_format($sale,2);?></td>
								</tr>
								<tr>
									<td>Void Transactions</td>
									<td style="text-align:right;"><?php echo number_format($void_amt,2);?></td>   
								</tr>
								<tr>
									<td>Tax</td>
									<td style="text-align:right;"><?php echo number_format($tax,2);?></td>	
								</tr>
								<tr>
									<td>Total</td>
									<td style="text-align:right;"><?php echo number_format($total, 2);?></td>		
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>Paid Out</td>
									<td style="text-align:right;"><?php echo number_format($payout_amt,2);?></td>
								</tr>
								<tr>
									<td>Total</td>
									<td style="text-align:right;"><?php echo number_format($sale - $payout_amt,2);?></td>		
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>Cash Drawer Expected</td>
									<td style="text-align:right;"><?php echo $sales_amt;?></td>
								</tr>
							</table>
						</div>
						<div class="col-lg-4">
							<table>
								<tr>
									<td>Discounts</td>
									<td style="text-align:right;"><?php echo number_format($discount_amt,2);?></td>
								</tr>
								<tr>
									<td>Void Count</td>
									<td style="text-align:right;"><?php  if($void_count) 
											echo $void_count;
										  else 
											echo 0;?>
									</td>	
								</tr>
								<tr>
									<td>Tax Collected</td>
									<td style="text-align:right;"></td>
								</tr>
								<tr>
									<td>Sales Tax</td>
									<td style="text-align:right;"><?php  echo number_format(($sales_amt* 0.12), 2);?></td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>Cash</td>
									<td style="text-align:right;"><?php echo $sales_amt;?></td>		  
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>Hourly Sales <br><?php echo date ('H',strtotime($start_time)) . ':00-'. date ('H',strtotime($end_time)).':00' ;?></td>
									<td style="text-align:right;"><?php if($hrs != 0) echo number_format($sales_amt/$hrs, 2);?></td>
								</tr>  
							</table>
						</div>
					</div>
					
					<div class="row clearfix">
						<br><br>
						<center style="font-size:11px;">*Details and totals are calculated at the time of printing and <br> may have been adjusted since the batch closing date.</center>
						<br>
					</div>
				</div>
			</div>
			</center>
		</div>
	</div>

	<!-- Jquery Core Js -->
	<script src="<?php echo base_url('assets/cashier/plugins/jquery/jquery.min.js');?>"></script>
 
	<script>
	(function($){
		$('.open-print-window').on('click', function() {
		window.location = "<?php echo base_url('cashier/logout/cashier');?>";
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});	
	}(jQuery));	
	</script>
</body>
</html>