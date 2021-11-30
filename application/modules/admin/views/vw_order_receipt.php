
<!DOCTYPE html>
<html>
<head>
<style type="text/css">

.boxed {
	width: 600px;
	height: auto;
	border: 1px solid green;
	padding:15px;
	position: relative;
	display: inline-block;
	bottom: 0;
	
}
.receipt-body{
	margin-top:10px;
}
td
{
    padding:0 15px 0 15px;
}
#tblprod {
    border-collapse: collapse;
	font-family: arial; 
	font-size:12px;
}
#tblprod th{
   border:1px solid black;
   font-size: 14px; 
}
#tblprod td{
   border:1px solid black;
	width: 100%;
}

#tblcust {
	border-collapse:separate;
	border-spacing:2px;
	font-family: arial; 
	font-size: 12px; 
	text-align:right;
	
}
.cust-details{
  border-bottom: 1px solid black;
  text-align:left;
	font-family: arial; 
}

#signature-field {
	margin-left:350px;
	font-family: arial; 
	font-size:12px;
	
}
h4{
	font-family: arial; 
	text-align:center;
	
}

</style>
	<title></title>
</head>
<body>
<?php 
if($is_batch_print): ?>
<?php foreach($batch_cust as $i) :
		foreach($i as $o): 
	$currentOrderNo = $o->order_no;
		?>

<div class = "boxed" >
	<div id = "content" >
	<div class = "header">
	
		<p>	
		<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>" width="80px" height="80px" align="left" >	
		<br>
		GOODBUY ENTERPRISES	
		<br>
		2nd Floor D&C Building, Russia St.
		Parañaque City	
		<br>
		TEL NO: 09328434905
		</p>
	</div>
		<h4>DELIVERY RECEIPT</h4>
		<div class='receipt-body'>
			
			<table id='tblcust'>
				<thead>
					<tr>	
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php //foreach($cust_details as $o):?>
					<tr>	
						<td>Customer Name: </td>
						<td class = "cust-details"><?php echo html_escape($o->firstname . ' '. $o->lastname);?></td>
						<td>Date Delivered: </td>
						<td class = "cust-details" width="90"></td>
					</tr>
					<tr>	
						<td>Shipping Address: </td>
						<td class = "cust-details"><?=html_escape($o->shipping_address)?></td>
						
					</tr>
					<tr>	
						<td>Contact No: </td>
						<td class = "cust-details"><?=html_escape($o->contact_no)?></td>
					</tr>
					<tr>	
						<td>Email: </td>
						<td class = "cust-details"><?=html_escape($o->email)?></td>
					</tr>
					<?php //endforeach;?>
				</tbody>
			</table>
			
			<br><br>
			<table  id="tblprod">
				<thead>
					<tr>	
						<th>Product Name</th>
						<th>Attribute</th>
						<th>Quantity</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>	
					<?php 
						$total_price = $total_qty = 0;
						foreach($batch_orders as $i):
						foreach($i as $order) :
							if($order->order_no  == $currentOrderNo):
							$total_price = $total_price + ($order->quantity * $order->selling_price);
							$total_qty =  $total_qty + $order->quantity; 
					?>
						<tr>
							<td><?=html_escape($order->prod_name)?></td>
							<td><?=html_escape($order->options)?></td>
							<td style ="text-align:center;padding:5px;"><?=html_escape($order->quantity)?></td>
							<td style ="text-align:right;padding:5px;"><?=number_format(($order->quantity * $order->selling_price), 2)?></td>
						</tr>
					<?php 
							endif;
							endforeach;
						   endforeach;?>
					<tr></tr>
					<tr></tr>
				</tbody>	
				<tfoot>
					<tr>
					
					<th colspan="2" style ="text-align:right;padding:5px;">TOTAL</th>
					<th style ="text-align:center;padding:5px;"><?php echo $total_qty;?></th>
					<th style ="text-align:right;padding:5px;">₱<?php echo number_format($total_price, 2);?></th>
					</tr>							
				</tfoot>
					
			</table>
			
			<div id="signature-field">
			<br><br><br>
			____________________________________
			<br>
			&nbsp &nbsp  Customer Signature Over Printed Name
		
			
			</div>
		</div>
	</div>	
</div>
<?php endforeach; 
	   endforeach;?>
<?php else: ?>
<div class = "boxed" >
	<div id = "content" >
	<div class = "header">
	
		<p>	
		<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>" width="80px" height="80px" align="left" >	
		<br>
		GOODBUY ENTERPRISES	
		<br>
		2nd Floor D&C Building, Russia St.
		Parañaque City	
		<br>
		TEL NO: 09328434905
		</p>
	</div>	
		<h4>DELIVERY RECEIPT</h4>
		<div class='receipt-body'>
			
			<table id='tblcust'>
				<thead>
					<tr>	
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($cust_details as $o):?>
					<tr>	
						<td>Customer Name: </td>
						<td class = "cust-details"><?php echo html_escape($o->firstname . ' '. $o->lastname);?></td>
						<td>Date Delivered: </td>
						<td class = "cust-details" width="90"></td>
					</tr>
					<tr>	
						<td>Shipping Address: </td>
						<td class = "cust-details"><?=html_escape($o->shipping_address)?></td>
						
					</tr>
					<tr>	
						<td>Contact No: </td>
						<td class = "cust-details"><?=html_escape($o->contact_no)?></td>
					</tr>
					<tr>	
						<td>Email: </td>
						<td class = "cust-details"><?=html_escape($o->email)?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<br><br>
			<table  id="tblprod">
				<thead>
					<tr>	
						<th>Product Name</th>
						<th>Attribute</th>
						<th>Quantity</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>	
					<?php 
						$total_price = $total_qty = 0;
						foreach($order_details as $o):
							$total_price = $total_price + ($o->quantity * $o->selling_price);
							$total_qty =  $total_qty + $o->quantity; 
					?>
					<tr>
						<td><?=html_escape($o->prod_name)?></td>
						<td><?=html_escape($o->options)?></td>
						<td style ="text-align:center;padding:5px;"><?=html_escape($o->quantity)?></td>
						<td style ="text-align:right;padding:5px;"><?=number_format(($o->quantity * $o->selling_price), 2)?></td>
					</tr>
					<?php endforeach;?>
					<tr></tr>
					<tr></tr>
				</tbody>	
				<tfoot>
					<tr>
					
					<th colspan="2" style ="text-align:right;padding:5px;">TOTAL</th>
					<th style ="text-align:center;padding:5px;"><?php echo $total_qty;?></th>
					<th style ="text-align:right;padding:5px;">₱<?php echo number_format($total_price, 2);?></th>
					</tr>							
				</tfoot>
					
			</table>
			<br>
			<br>
			<div id="signature-field">
			<br>
			____________________________________
			<br>
			&nbsp &nbsp  Customer Signature Over Printed Name
			<br>
			<br>
			</div>
		</div>
	</div>	
</div>
<?php
	endif;	?>
</body>
</html>
<script>
window.onload = function() { window.print(); }
</script>