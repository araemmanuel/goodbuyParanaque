<!DOCTYPE html>
<html>
<head>
<style type="text/css">

.boxed {
	width: 320px;
	height: auto;
	border: 1px solid green;
	padding:15px;
	position: relative;
	display: inline-block;
	bottom: 0;	
}

td
{
    padding:0 5px 0 5px;
}
#tblprod {
    border-collapse: collapse;
}
#tblprod th{
   border:0px solid black;
}
#tblprod td{
	width: 100%;
}

#tblcust {
	border-collapse:separate;
	border-spacing:2px;
	font-family: arial; 
	font-size: 11px; 
	text-align:right;
	
}
.cust-details{
  border-bottom: 1px solid black;
  text-align:left;
  width:20%;

}

#signature-field {
	font-family: arial; 
	font-size: 11px; 
	margin-left:40px;
	
}
.label{
	width:34%;
}
h5{
	font-family: arial; 
	text-align:center;
	
}
.header{
	font-family: arial; 
	font-size: 11px; 
}
.agree{
	font-family: arial; 
	font-size: 12px; 
	margin-left:4px;
	text-align:center;
}

</style>
	<title>Rewards Card Application Form</title>
</head>
<body>
<?php 
/*

First name:		Last name:		
Gender:     	Date of Birth:		
Contact No:		Email:

Shipping Address:
Shipping City:
Shipping Zipcode:

*/
for($i=0;$i<$qty;$i++) :
?>
<div class = "boxed" >
	<div id = "content" >
	<div class = "header">
	
		<p>	
		<img src="<?php echo base_url('assets/admin/images/gb-logo.png');?>" width="60px" height="60px" align="left" >	
		<br>
		GOODBUY ENTERPRISES	
		<br>
		2nd Floor D&C Building, Russia St.
		Parañaque City	
		<br>
		TEL NO: 09328434905
		</p>
	</div>	
		<h5>REWARDS CARD APPLICATION FORM</h5>	
		
		<div class='receipt-body'>
			
			
			<div style="text-align:right;">  Date: _____________</div>
			<br>
			<div style="text-align:left;">________________________________________</div>
			<div style="text-align:left;font-size:14px;">
				&nbsp;&nbsp;First name 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Middle name      
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Last name
			</div>
			<br>
			<div style="text-align:left;font-size:14px;">Birth Date: _________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:__________</div>
			<br>
			<div style="text-align:left;font-size:14px;">Contact No.: &nbsp;__________________________________</div>
			<br>
			<div style="text-align:left;font-size:14px;">Email:&nbsp;&nbsp;_______________________________________</div>
			<br>
			<div style="text-align:left;font-size:14px;">Shipping Address: ______________________________</div>
			
			
			<!--
				
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
						<td></td>	
						<td class="label">Date: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>	
						<td class="label">First Name: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>	
						<td class="label">Last Name: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>					
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Birth Date:</td>
						<td class = "cust-details"></td>
						<td>Gender: </td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>						
						<td class="label">Contact No: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Email: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>	
						<td class="label">Shipping Address: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Shipping City: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Shipping Zipcode: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Shipping State: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="label">Shipping Country: </td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
						<td class = "cust-details"></td>
					</tr>
					
					<?php //endforeach;?>
				</tbody>
			</table>
			-->
			<div id ="terms">
				<!--<p style="font-family:arial;font-size:11px;margin-left:20px;"><b>TERMS AND CONDITIONS:</b></p>-->
				<br>
				<div class="agree">
					<input type="checkbox" />
					<span>I hereby agree to the terms <br>
					and conditions of the store.</span>
				</div>
				<div id="signature-field">
				<br><br>
				____________________________________
				<br>
				&nbsp; &nbsp; Customer Signature Over Printed Name
				<br>
				<br>
				</div>
			</div>
			
			
		</div>
	</div>	
</div>
<?php endfor;?>
</body>
</html>
<script>
	window.onload = function() { window.print(); }
</script>