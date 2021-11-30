<!DOCTYPE html>
<html>
<head>
<title>Return Receipt</title>

<style>
.boxed {
	width: 240px;
	height: auto;
	border: 1px solid green;
	padding: 10px !important;
	position: relative;
	display: inline-block;
	bottom: 0;
	background-color:white;
}

.receipt-header {
	text-align: center;
	margin-top: 5px;
	margin-bottom: 20px;
	font-family: monospace;
	font-size: 11px;
}
.receipt-header img {
	width: 50px;
	height: 50px;
	text-align: center;
}

.receipt-body{
	margin-top: 10px;
	text-align: center;
	font-family: monospace;
	font-size: 11px;
}

.receipt-table {
	border-collapse: separate;
	border-spacing: 3px;
	text-align: center;
	margin-bottom: 20px;
	font-family: monospace;
	font-size: 11px;
}

.tax-table {
	width: 90%;
	border-collapse: separate;
	border-spacing: 3px;
	margin-bottom: 20px;
	font-family: monospace;
	font-size: 11px;
}

.receipt-footer {
	text-align: center;
	margin-bottom: 5px;
	font-family: monospace;
	font-size: 10.5px;
}
</style>
</head>

<body>
	<div>
		<div class="align-center">
			<button data-href="<?php echo base_url('cashier/return_item/print_receipt/'.$trans_id);?>" class="open-print-window btn btn-xs bg-green waves-effect">	
				<i class="material-icons">print</i>
				<span>PRINT</span>
			</button>
			<a href="<?php echo base_url('cashier/return_item/void_transaction/'.$trans_id);?>" class="btn btn-xs bg-grey waves-effect"> 
				<i class="material-icons">close</i>
				<span>VOID TRANSACTION</span>
			</a>
		</div>
		<br>
		
		<center>
		<div class="boxed" id="content">
			<div class="receipt-header">
				<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>">
				<br>
				<span style="font:bold 25px;"><b>Item Return Receipt</b></span><br>
				GOODBUY ENTERPRISES<br>
				2nd Floor D&C Building, Russia St.<br>
				Parañaque City<br>
				VAT REG TIN:  <?php echo $reg_tin;?><br>
				TEL NO: 09328434905
			</div>
			
			<div class="receipt-body">
				<center>
					<table class="receipt-table">
						<thead>
							<tr>
								<th hidden></th>
								<!--<th width="25%">CODE</th>-->
								<th style="text-align:center;" width="30%">NAME</th>
								<th style="text-align:center;" width="10%">QTY</th>
								<th style="text-align:center;" width="15%">AMT</th>
								<th style="text-align:center;" width="20%">REMARKS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$total_sale = $total_cash = 0.00;
							foreach($receipt_items as $r): ?>
							<tr>
								<!--<td><?=html_escape($r->sku)?></td>-->
								<td><?=html_escape($r->name. '('.$r->options.')')?></td>
								<td><?=html_escape($r->qty)?></td>
								<td><?=html_escape($r->total_selling_price)?></td>
								<td><?=html_escape($r->type)?></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<?php 
								if(strcasecmp($r->type, 'return') == 0)
										$total_cash = $total_cash + $r->total_selling_price;
								
								if(strcasecmp($r->type, 'replacement') == 0)
								{
									$total_sale = $total_sale + $r->total_selling_price;					
								}
							?>
							
							<?php endforeach; ?>
						</tbody>
						<tfoot>
						<?php foreach($receipt_details as $r): ?>
							<tr>
								<td colspan="3" style="text-align:right;"><b>Balance </b></td>
								<td><b><?=html_escape($r->balance)?></b></td>
							</tr>
							<tr>
								<td colspan="3" style="text-align:right;"><b>Cash Tendered for Balance Payment </b></td>
								<td><b><?=html_escape($r->cash)?></b></td>
							</tr>
							<?php 
								//echo $r->cash ;
								$change = number_format(abs(($total_cash + $r->cash)-$total_sale), 2); 
							?>
							<tr>
								<td colspan="3" style="text-align:right;"><b>Change </b></td>
								<td><b><?=html_escape($change)?></b></td>
							</tr>
						<?php endforeach; ?>
						</tfoot>
					</table>
				</center>
				
				<center>
					<strong>TAX INFO</strong>
					<table class="tax-table">
						<tr>
							<td style="text-align:left;">Non-Vatable</td>
							<td style="text-align:right;">0.00</td>
						</tr>
						<?php $vatable_sale = number_format($total_sale / 1.12, 2);?>
						<tr>
							<td style="text-align:left;">Vatable</td>
							<td style="text-align:right;"><?php echo $vatable_sale;?></td>
						</tr>
						<tr>
							<td style="text-align:left;">VAT Zero-Rated Sale</td>
							<td style="text-align:right;">0.00</td>
						</tr>
						<tr>
							<td style="text-align:left;">VAT Exempt Sale</td>
							<td style="text-align:right;">0.00</td>
						</tr>
						<tr>
							<td style="text-align:left;">VAT (12%)</td>
							<td style="text-align:right;"><?php echo (number_format($total_sale - $vatable_sale  , 2));?></td>
						</tr>
						<tr>
							<td style="text-align:left;">Total Sales</td>
							<td style="text-align:right;"><?php echo number_format($total_sale, 2);?></td>
						</tr>
					</table>
				</center>
			</div>
			
			<div class="receipt-footer">
				<span>Return and Exchange of items are allowed within <b><?php echo $return_policy?> days </b>
				after the date of purchase indicated in this receipt.<br>
				<?php date_default_timezone_set('Asia/Manila');?>
				<?php echo date('M d, Y h:i A'); ?><br> 
				Cashier : <?php echo ucfirst($firstname) . ' '. ucfirst($lastname); ?><br>
				Receipt No. : <?php echo $invoice_no; ?><br>
				THANK YOU FOR SHOPPING AT GOODBUY ENTERPRISES!
				</span>
			</div>
		</div>
		</center>
		
	</div>
</body>
</html>
