
<!DOCTYPE html>
<html>
<head>
<style type="text/css">

.boxed {
	width: 220px;
	height: auto;
	border: 1px solid green;
	padding:10px;
	position: relative;
	display: inline-block;
	bottom: 0;
	background-color:white;
	
}
.receipt-body{
	margin-top:10px;
}
td
{
    padding:0 15px 0 15px;
}
</style>
	<title></title>
</head>
<body>
<center>
<br>
<div class = "boxed" >
	<div id = "content" >
		<center>
			<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>" width="75px" height="75px">
		</center>
		<center>
		GOODBUY ENTERPRISES	
		<br>
		2nd Floor D&amp;C Building, Russia St.
		Parañaque City	
		<br>
		VAT REG TIN: XXXXXXXX
		<br>
		TEL NO: 09328434905
		</center>
		
		<br>
		<div class='receipt-body'>
			<center>
			<table border="0"  style="border-collapse:separate; border-spacing:2px;font-family: arial; font-size: 10.5px; text-align:center;">
				<thead>
					<tr>	
						<th width="50%">Name</th>
						<th>QTY</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>	
					<?php 
						$total_price = $total_qty = 0;
						foreach($details as $o):
							$total_price = $total_price + ($o->amt_paid);
							$total_qty =  $total_qty + $o->quantity; 
					?>
					<tr>
						<td width="30%"><?=html_escape($o->prod_name)?></td>
						<td width="10%"><?=html_escape($o->quantity)?></td>
						<td><?=number_format(( $o->amt_paid), 2)?></td>
					</tr>
					<?php endforeach;?>
					<tr></tr>
					<tr></tr>
				</tbody>	
				<tr>
					<td colspan="2" style=" text-align:right;">
						<strong style="font-size: 10.5px;">Total: </strong>
					</td>
					<td>
						<strong style="font-size: 10.5px;"><?php echo number_format(abs($total_price), 2);?></strong>
					</td>
				</tr>
					
				<tr>
					<td colspan="2" style=" text-align:right;">
						<strong style="font-size: 10.5px;">Cash Tendered: </strong>
					</td>
					<td>
						<strong style="font-size: 10.5px;"> <?php echo number_format($cash, 2);?></strong>
					</td>		
					<tr>
						<td colspan="2" style=" text-align:right;">
							<strong style="font-size: 10.5px;">Change: </strong>
						</td>
						<?php 
						
						if($cash>$total_price)
							$change = ($cash - $total_price);
						else
							$change = 0.00;
						
						?>
						<td>
							<strong style="font-size: 10.5px;"><?php echo number_format($change, 2);?></strong>
						</td>
					</tr>
					<tr>
						<td colspan="2" style=" text-align:right;">
							<strong style="font-size: 10.5px;">Item(s): </strong>
						</td>
						<td>
							<strong style="font-size: 10.5px;"><?php echo $total_qty;?></strong>
						</td>
					</tr>
				</tr>	
				</tbody>
			</table>
			</center>
			<br>
			<br>
			<center>
				<STRONG>TAX INFO</STRONG>
				<TABLE border="0"  class="paddingBetweenCols" style="border-collapse:separate; border-spacing:5px;font-family: arial; font-size: 12.5px; text-align:right;">
				<tr>
				<td style=" text-align:left;">Non-Vatable</td>
				<td style=" text-align:left;">0.00</td>
				</tr>
				<tr>
				<td style=" text-align:left;">Vatable</td>
				<td style=" text-align:left;">N/A</td>
				</tr>
				<tr>
				<td style=" text-align:left;">VAT Zero-Rated Sale</td>
				<td style=" text-align:left;">0.00</td>
				</tr>
				<tr>
				<td style=" text-align:left;">VAT Exempt Sale</td>
				<td style=" text-align:left;">0.00</td>
				</tr>
				<tr>
				<td style=" text-align:left;">VAT (12%)</td>
				<td style=" text-align:left;">N/A</td>
				</tr>
				<tr>
				<td style=" text-align:left;">Total Sales</td>
				<td style=" text-align:left;"><?php echo number_format($total_price, 2);?></td>
				</tr>
					
				</TABLE>
			</center>
			<br>
			<br>
			<?php date_default_timezone_set('Asia/Manila');?>
			<?php echo date('M d, Y h:i A'); ?><br> 
			Receipt No. : <?php echo $invoice_no; ?><br>
			Thank you for shopping at Goodbuy Enterprises!
			</center>
		</div>
	</div>	
</div>
</center>
</body>
</html>
<script>
</script>