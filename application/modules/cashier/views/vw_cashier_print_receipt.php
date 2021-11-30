
<!DOCTYPE html>
<html>
<head>
<title>Print Receipt</title>
	
<style type="text/css">
body {
	font-size: 8px;
	font-family: 'Arial Narrow';
}
.boxed {
	width: 50mm;
	height: auto;
	position: relative;
	display: inline-block;
	bottom: 0;
	background-color: white;
        margin-right: 5px;
}

.receipt-header {
	text-align: center;
	margin-top: 5px;
	margin-bottom: 20px;
}
.receipt-header img {
	width: 50px;
	height: 50px;
	text-align: center;
}

.receipt-body{
	margin-top: 10px;
}

.receipt-table {
	width: 90%;
	border-collapse: separate;
	border-spacing: 0;
	margin-bottom: 20px;
}

.tax-table {
	width: 90%;
	border-collapse: separate;
	border-spacing: 0;
	margin-bottom: 20px;
}

.receipt-footer {
	width: 90%;
	text-align: center;
	margin-bottom: 5px;
}
</style>
</head>

<body>


<div class="boxed">
	<div id = "content">
		<div class="receipt-header">
			<!--<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>">
			<br>-->
			<span><strong>GOODBUY ENTERPRISES</strong></span><br>
			<span>2nd Floor D&C Building, Russia St.<br>
			Parañaque City<br>
			VAT REG TIN: <?php echo $reg_tin;?><br>
			TEL NO: 09328434905
			</span>
		</div>
	
		<center>
		<div class='receipt-body'>
			<table class="receipt-table">
				<thead>
					<tr>	
						<th width="50%" style="text-align:left;">Name</th>
						<th style="text-align:left;">QTY</th>
						<th style="text-align:right;">Amount</th>
					</tr>
				</thead>
				<tbody>	
					<?php 
						$total_paid = $total_selling = $total_discount = $total_qty = 0;
						foreach($details as $o):
							$total_paid = $total_paid + ($o->amt_paid);
							$total_selling = $total_selling + ($o->selling_price);
							$total_discount = $total_discount + abs($o->selling_price - $o->amt_paid);
							$total_qty =  $total_qty + $o->quantity; 
							
					?>
					<tr>
						<td width="30%"><?=html_escape($o->prod_name)?></td>
						<td width="10%"><?=html_escape($o->quantity)?></td>
					<?php if($o->quantity == 1) : ?>	
						<td style="text-align:right;"><?=number_format(( $o->selling_price), 2)?></td>
						<?php $discount_amt = $o->selling_price - $o->amt_paid; ?>

					<?php elseif($o->quantity > 1) : ?>
						<td style="text-align:right;"><?=number_format(( $o->selling_price*$o->quantity), 2)?></td>
						<?php $discount_amt = ($o->selling_price*$o->quantity) - $o->amt_paid; ?>
					<?php endif; ?>
					</tr>
					
					<?php if($o->discount > 0) : ?>
						<tr>
							<td width="30%">Sale discount less <?php echo ceil(($discount_amt/$o->selling_price)*100);?>%</td>
							<td width="10%"></td>
							<td style="text-align:right;">-<?=number_format($discount_amt, 2)?></td>
						</tr>
				<?php endif; ?>
					
					<?php endforeach;?>
					<tr></tr>
					<tr></tr>
					<tr>
						<td colspan="3"><hr></td>
					</tr>
					<tr>
						<td colspan="2">TOTAL:</td>
						<td style="text-align:right;">
							<?php echo number_format(abs($total_paid), 2);?>
						</td>
					</tr>
					<tr>
						<td colspan="2">CASH TENDERED:</td>
						<td style="text-align:right;">
							<?php echo number_format($cash, 2);?>
						</td>
					</tr>
					<tr>
						<td colspan="2">CHANGE:</td>
						<?php 
							$change = abs($cash - $total_paid);	
						?>
						<td style="text-align:right;">
							<?php echo number_format($change, 2);?>
						</td>
					</tr>
					<tr>
						<td colspan="2">ITEM(s):</td>
						<td style="text-align:right;">
							<?php echo $total_qty;?>
						</td>
					</tr>	
				</tbody>
			</table>
			
			<center>
				<strong>TAX INFO</strong>
				<table class="tax-table">
					<tbody>
						<tr>
							<td style=" text-align:left;">Non-Vatable</td>
							<td style=" text-align:right;">0.00</td>
						</tr>
						<?php $vatable = number_format($total_paid/$calculated_vat_percent,2);?>
						<tr>
							<td style=" text-align:left;">Vatable</td>
							<td style=" text-align:right;"><?php echo $vatable;?></td>
						</tr>
						<tr>
							<td style=" text-align:left;">VAT Zero-Rated Sale</td>
							<td style=" text-align:right;">0.00</td>
						</tr>
						<tr>
							<td style=" text-align:left;">VAT Exempt Sale</td>
							<td style=" text-align:right;">0.00</td>
						</tr>
						<tr>
							<td style=" text-align:left;">VAT (<?php echo $vat_percent;?>%)</td>
							<td style=" text-align:right;"><?php echo number_format($total_paid - $vatable,2)?></td>
						</tr>
						<tr>
							<td style=" text-align:left;">Total Sales</td>
							<td style=" text-align:right;"><?php echo number_format($total_paid, 2);?></td>
						</tr>
					</tbody>
				</table>
			</center>
		</div>
		
		<hr style="color:black;">
		<div class="receipt-footer">
			<span>Return and Exchange of items are allowed within <strong> <?php echo $return_policy;?> days </strong> after the date of purchase indicated in this receipt. <br>
			<?php date_default_timezone_set('Asia/Manila');?>
			<?php echo date('M d, Y h:i A'); ?><br> 
			Cashier : <?php echo ucfirst($firstname) . ' '. ucfirst($lastname); ?><br>
			Receipt No. : <?php echo $invoice_no; ?><br>
			THANK YOU FOR SHOPPING AT GOODBUY ENTERPRISES!
			</span>
		</div>
		</center>
	</div>
</div>


</body>
</html>

<script>
window.onload = function() { window.print(); }
</script>