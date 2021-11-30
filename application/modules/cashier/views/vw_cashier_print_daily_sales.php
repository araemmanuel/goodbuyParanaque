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
    font-size: 18.5px;
    font-weight: bold;
  }
.center{
	text-align:center;
}

</style>
</head>
    <!-- Page Content -->
    <div class="container">
     <div class="col-lg-12">
<div class="span10">
  <div id = "content" style="margin: 0 auto; padding: 20px; width: 900px; font-weight: normal;">
 
   <br>
   <br>
  <center><img src="<?php echo base_url('assets/images/gb-logo.png');?>" width="75px" height="75px"></center>
  <center>
    Z REPORT  <br>
  </center>

  
  <BR>
  <br>
  <BR>
  <br>
  <div style="margin-top:-70px;">
  <center>
  
    <TABLE border="0"  style="border-collapse:separate; border-spacing:5px;font-family: arial; font-size: 18.5px; text-align:center; margin-left: 125px;" width="50%">
    <tr>
    <td style=" text-align:left;">Report Date</td>
    <td style=" text-align:left;"><?php echo date('F d, Y');?></td>
    </tr>
    
    <tr>
    <td style=" text-align:left;">Report Time</td>
    <td style=" text-align:left;"><?php echo date('h:i:s a', time());?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Register</td>
    <td style=" text-align:left;">GBPOS</td>
    </tr>
    <tr>
    <td style=" text-align:left;">Batch Profile</td>
    <td style=" text-align:left;"><?php echo ucwords($firstname . ' '. $lastname);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Batch Status</td>
    <td style=" text-align:left;">Closed</td>
    </tr>
    <?php foreach($pos_details as $pd): ?>
	<tr>
    <td style=" text-align:left;">Start Date</td>
    <td style=" text-align:left;"><?php echo date('M d, Y', strtotime($pd->start_date));?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Start Time</td>
    <td style=" text-align:left;"><?php echo $pd->start_time;?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">End Date</td>
    <td style=" text-align:left;"><?php echo date('M d, Y', strtotime($pd->end_date));?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">End Time</td>
    <td style=" text-align:left;"><?php echo $pd->end_time;?></td>
    </tr>
	 <?php endforeach; ?>
	
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Item Sales</td>
    <td style=" text-align:left;"><?php echo number_format($sales_amt,2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Void Transactions</td>
    <td style=" text-align:left;"><?php echo number_format($void_amt,2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Tax</td>
    <td style=" text-align:left;"><?php echo number_format($sales_amt * 0.12,2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Total</td>
    <td style=" text-align:left;"><?php echo number_format(($sales_amt + $void_amt + ($sales_amt * 0.12)), 2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Paid Out</td>
    <td style=" text-align:left;"><?php echo number_format($payout_amt,2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Total</td>
    <td style=" text-align:left;"><?php echo '0.00';?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Cash Drawer Expected</td>
    <td style=" text-align:left;"><?php echo $sales_amt;?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Discounts</td>
    <td style=" text-align:left;"><?php echo number_format($discount_amt,2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Void Count</td>
    <td style=" text-align:left;"><?php echo $void_count;?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Tax Collected</td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Sales Tax</td>
    <td style=" text-align:left;"><?php  echo number_format(($sales_amt* 0.12), 2);?></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Cash</td>
    <td style=" text-align:left;"><?php echo $sales_amt;?></td>
    </tr>
    <tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;"></td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
    <td style=" text-align:left;">Hourly Sales</td>
    <td style=" text-align:left;"></td>
    </tr>
    <tr>
	<td style=" text-align:left;"><?php echo $hrs;?></td>
    <td style=" text-align:left;"><?php if($hrs != 0) echo number_format($sales_amt/$hrs, 2);?></td>
    </tr>
      
    </TABLE>
  </center>
  <br>
  <br>
  <center>*Details and totals are calculated at the time of printing and <br> may have been adjusted since the batch closing date. <br>
  
  </center>
  </div>
  </div>

  </div>
  </div>


</div>
</div>
<script>
window.onload = function() { window.print(); }
</script>