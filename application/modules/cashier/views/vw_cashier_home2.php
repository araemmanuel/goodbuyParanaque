<!--<a href = "<?php echo base_url('cashier/logout/cashier');?>">LOG OUT</a>-->
<!DOCTYPE html>
<html>
<head>
	<title>GoodBuy | Cashier</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" type="image/x-icon">
    <!-- Google Fonts -->
	<link href="<?php echo base_url('assets/cashier/fonts/iconfont/material-icons.css');?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/node-waves/waves.css');?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/animate-css/animate.css');?>" rel="stylesheet" />
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/bootstrap-select/css/bootstrap-select.css');?>" rel="stylesheet" />
    <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css');?>" rel="stylesheet">
	<!-- Sweetalert Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/sweetalert/sweetalert.css');?>" rel="stylesheet" />
	<!-- Custom Css -->
    <link href="<?php echo base_url('assets/cashier/css/style.css');?>" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/cashier/css/themes/theme-green.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/interface.css');?>" rel="stylesheet" />

</head>

<body class="theme-green" onload="updateClock();">
	<!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
<section class="content">
        <div class="container-fluid">
           
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="align-right">
						<div class="btn-group" role="group">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn bg-green btn-xs waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">settings</i>
									<span>SETTINGS</span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="check-return" href="<?php echo base_url('cashier/cashier_mode');?>">Cashier Mode</a></li>
                                    <li><a class="check-return" href="<?php echo base_url('cashier/pickup_order');?>">Pick up Order</a></li>
									<li><a class="check-return" href="<?php echo base_url('cashier/return_item');?>">Return Item</a></li>
									<li><a class="check-return" id="open-payout" data-toggle="modal" data-target="#modal-payout">Pay Outs</a></li>
									<li><a class="check-return" id="open-ns" data-toggle="modal" data-target="#modal-ns">Non-saleable Item</a></li>
                                </ul>
                            </div>
							<button type="button" id="btn-end-day" data-title='You are about to close the batch, print the Z report, and end the day. Do you want to continue?' data-msg="This action cannot be undone." data-url="<?php echo base_url('cashier/end_day');?>" class="btn btn-xs bg-red waves-effect cashier-confirm">
								<i class="material-icons">warning</i>
								<span>END DAY</span>
							</button>
                        </div>
					
						<!--
						<a href="<?php echo base_url('cashier/cashier_mode');?>" type="button" class="btn btn-xs bg-green waves-effect">
							<i class="material-icons">local_atm</i>
							<span>CASHIER MODE</span>
						</a>
						<a href="<?php echo base_url('cashier/pickup_order');?>" type="button" class="btn btn-xs bg-green waves-effect">
							<i class="material-icons">pan_tool</i>
							<span>PICK UP ORDER</span>
						</a>
						<a href="<?php echo base_url('cashier/return_item');?>" type="button" class="btn btn-xs bg-green waves-effect">
							<i class="material-icons">restore</i>
							<span>RETURN ITEM</span>
						</a>
						<button type="button" class="btn btn-xs bg-green waves-effect" id="open-payout" data-toggle="modal" data-target="#modal-payout">
							<i class="material-icons">attach_money</i>
							<span>PAY OUTS</span>
						</button>
						<button type="button" class="btn btn-xs bg-green waves-effect" id="open-non" data-toggle="modal" data-target="#modal-non-saleable">
							<i class="material-icons">broken_image</i>
							<span>NON-SALEABLE ITEM</span>
						</button>
						<button type="button" id="btn-end-day" data-title='You are about to close the batch, print the Z report, and end the day. Do you want to continue?' data-msg="This action cannot be undone." data-url="<?php echo base_url('cashier/end_day');?>" class="btn btn-xs bg-red waves-effect cashier-confirm">
							<i class="material-icons">warning</i>
							<span>END DAY</span>
						</button>
						-->
					</div>
				</div>
			</div>
			<br>
			<?=$content?>
            
        </div>
    </section>
    
	<!-- PAY OUTS MODAL -->
	<div class="modal fade" id="modal-payout" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
			<form action = "<?php echo base_url('cashier/expenses/add');?>" id="form-payout" method="POST">
                <div class="modal-header">
                     <h1 class="modal-title">ADD PAY OUT</h1>
                </div>
                <div class="modal-body">
					<br>
					<div class="form-group form-float">
						<div class="form-line success">
							<label class="form-label">Pay Out Description</label>
							<input type="text" name="payout-desc" class="form-control">
						</div>
						<div class='validation-errors' id = "desc_error">
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line success">
							<label class="form-label">Pay Out Amount</label>
							<input type="number" name="payout-amt" class="form-control" min="0.00" step="0.01">
						</div>
						<div class='validation-errors' id = "amt_error">
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-payout" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SUBMIT</span></button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->

	<!-- NON-SALEABLE MODAL -->
	<div class="modal fade" id="modal-ns" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
			<form action = "<?php echo base_url('cashier/ns_add');?>" id="form-ns" method="POST">
                <div class="modal-header">
                     <h1 class="modal-title">ADD NON-SALEABLE ITEM</h1>
                </div>
                <div class="modal-body">
					<br>
					<div class="form-group form-float">
						<div class="form-line success">
							<label class="form-label col-grey">Product Code</label>
							<input type="text" name="sku" class="form-control">
						</div>
						<div class='validation-errors' id = "sku1_error">
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line success">
							<label class="form-label col-grey">Quantity</label>
							<input type="number" name="qty" class="form-control">
						</div>
						<div class='validation-errors' id = "qty1_error">
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line success">
							<label class="form-label col-grey">Reason</label>
							<input type="text" name="reason" class="form-control">
						</div>
						<div class='validation-errors' id = "reason1_error">
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-ns" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SUBMIT</span></button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->

	
	
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/jquery/jquery.min.js');?>"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/bootstrap/js/bootstrap.js');?>"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/node-waves/waves.js');?>"></script>
    <!-- Jquery DataTable Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/jquery.dataTables.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/buttons.flash.min.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/jszip.min.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/pdfmake.min.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/vfs_fonts.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/buttons.html5.min.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-datatable/extensions/export/buttons.print.min.js');?>"></script>
	<!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/sweetalert/sweetalert.min.js');?>"></script>
	
	<!-- Alert and Notification Js -->
	<script src="<?php echo base_url('assets/admin/plugins/sweetalert/confirm-box.js')?>"></script>
	
	<!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
	
	<input type="hidden" id="is-mobile" value="<?php echo $is_mobile;?>" />
	
	<!---->
	<script src="<?php echo base_url('assets/plugins/qrdecoder/qcode-decoder.min.js').'?sig='.rand();?>"></script>
	
	<!-- QR CODE DECODER 
	<?php if($is_mobile) :?>
		<script src="<?php echo base_url('assets/plugins/qrdecoder3/qcode-decoder.min.js').'?sig='.rand();?>"></script>
	<?php else :?>
		<script src="<?php echo base_url('assets/plugins/qrdecoder2/qcode-decoder.min.js').'?sig='.rand();?>"></script>
	<?php endif; ?>
	-->
    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/cashier/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/cashier/js/pages/tables/jquery-datatable.js');?>"></script>
	<script src="<?php echo base_url('assets/cashier/js/pages/ui/animations.js');?>"></script>
    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/cashier/js/demo.js');?>"></script>
	<!-- Supercustom JS-->
	<script src="<?php echo base_url('assets/js/cashier_interface.js'). "?sig=" . rand(pow(10, 3-1), pow(10, 3)-1); ?>"></script>
	
</body>

</html>
	<?php
		//showNotification(colorName, null, placementFrom, placementAlign, animateEnter, animateExit);
		//$alert_type = $this->session->flashdata('alert_type');
		$alert_msg = $this->session->flashdata('alert_msg');
		$error_msg = $this->session->flashdata('error_msg');
		if($alert_msg) echo "<script>showNotification('bg-black','$alert_msg', 'top', 'right', null, null);</script>";
		if($error_msg) echo "<script>showNotification('bg-red','$error_msg', 'top', 'right', null, null);</script>";

	?>