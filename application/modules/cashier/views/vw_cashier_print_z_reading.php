<!DOCTYPE html>
<html>
<head>
<title>Print Receipt</title>
	
<style type="text/css">
body {
	font-family: monospace;
	font-size: 12px;
}
.boxed {
	width: 250px;
	height: auto;
	border: 1px solid #888;
	padding: 10px;
	position: relative;
	display: inline-block;
	bottom: 0;
	background-color: white;
}

.x-header {
	text-align: center;
	margin-top: 15px;
}

.x-body{
	margin-top: 8px;
}

.x-table {
	width: 90%;
}

.x-table-categ {
	margin-bottom: 20px;
	width: 90%;
	border-collapse: collapse;
	border: 1px solid black;
}

.x-footer {
	text-align: center;
	margin-bottom: 5px;
	font-size: 10.5px;
}

hr {
	display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #000;
    margin: 0;
    padding: 0; 
}
</style>
</head>

<body>

<center>
<div class="boxed">
	<div id = "content">
		<div class="x-header">
			<span>
			<b style="font-size: 14px;">GOODBUY ENTERPRISES</b><br>
			<i>Point of Sales</i><br>
			Parañaque City<br>
			VAT REG TIN: <?php echo $reg_tin;?><br>
			MIN: XXXXXXXXXXXXX<br>
			S/N: XXXXXXXXXXXXX
			</span>
		</div>
	
		<div class='x-body'>
			<table class="x-table">
				<thead>
					<tr></tr>
				</thead>
				<tbody>
					<tr><td colspan="2" align="center"><hr>Z READING</td></tr>
					<!--<tr><td colspan="2" align="center">X Counter: 1<hr></td></tr>-->
					<?php if($date_from == $date_to): ?>
					<tr>
						<td>Date</td>
						<td align="right"><?php echo $date_from;?></td>
					</tr>
					<?php elseif ($date_from != $date_to): ?>
					<tr>
						<td>Date From</td>
						<td align="right"><?php echo $date_from;?></td>
					</tr>
					<tr>
						<td>Date To </td>
						<td align="right"><?php echo $date_to;?></td>
					</tr>
					<?php else : ?>
					<tr>
						<td>Date</td>
						<td align="right"><?php echo 'No date could be loaded. Contact support.';?></td>
					</tr>
					<?php endif; ?>
					
					<tr>
						<td>Report Time</td>
						<td align="right"><?php echo date("h:i:s A");?></td>
					</tr>
					<!--<tr>
						<td>Register #</td>
						<td align="right">POS-0001</td>
					</tr>
					<tr>
						<td>Location #</td>
						<td align="right"></td>
					</tr>
					<tr>-->
						<td>Terminal #</td>
						<td align="right"><?php echo $terminal;?></td>
					</tr>
					<tr>
						<td>Operator</td>
						<td align="right"><?php echo $cashier;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>Beginning SI #</td>
						<td align="right"><?php echo $begin_inv;?></td>
					</tr>
					<tr>
						<td>Ending SI #</td>
						<td align="right"><?php echo $end_inv;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>Beginning</td>
						<td align="right"><?php echo $begin_amt;?></td>
					</tr>
					<tr>
						<td>Todays</td>
						<td align="right"><?php echo $today_amt;?></td>
					</tr>
					<tr>
						<td>Ending</td>
						<td align="right"><?php echo $end_amt;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>GROSS Sales</td>
						<td align="right"><?php echo $gross_sales;?></td>
					</tr>
					<tr>
						<td>Net Sales</td>
						<td align="right"><?php echo $net_sales;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>Total Cancel</td>
						<td align="right"><?php echo $cancel_ctr;?></td>
					</tr>
					<tr>
						<td>Canceled Sales</td>
						<td align="right"><?php echo $total_cancel_amt;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<?php 
						$vat_sales = $net_sales/1.12;
						$vat_12per =  $net_sales - $vat_sales;
						?>
					<tr>
						<td>Vat Sales</td>
						<td align="right"><?php echo number_format($vat_sales,2);?></td>
					</tr>
					<tr>
						<td>12% VAT</td>
						<td align="right"><?php echo number_format($vat_12per,2);?></td>
					</tr>
					<tr>
						<td>VAT Exempt Sales</td>
						<td align="right">0.00</td>
					</tr>
					<tr>
						<td>VAT Zero Rated</td>
						<td align="right">0.00</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>Discount</td>
						<td align="right"><?php echo $total_discount;?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td>&nbsp;&nbsp;<i>Rewards Card Cash</i></td>
						<td align="right"><?php echo $total_rc_cash;?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;<i>Cash</i></td>
						<td align="right"><?php echo $total_cash;?></td>
					</tr>
					<tr>
						<td>TOTAL CASH</td>
						<td align="right"><?php echo  number_format($total_cash, 2, '.', '');?></td>
					</tr>
					<tr>
						<td>GRAND TOTAL</td>
						<td align="right"><?php echo  number_format($total_rc_cash + $total_cash, 2, '.', '');?></td>
					</tr>
					<tr>
						<td>Total QTY Sold</td>
						<td align="right"><?php echo $total_qty;?></td>
					</tr>
					<tr>
						<td>Transaction count</td>
						<td align="right"><?php echo $transaction_count;?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="x-footer">
			<br>
			<center>
			____________________<br>Prepared by
			</center>
			<br><br>
			<center>
			____________________<br>
			Signed by
			</center>
			<br>
		</div>
	</div>
</div>
</center>

</body>
</html>
<script>
window.onload = function() { window.print(); }
</script>