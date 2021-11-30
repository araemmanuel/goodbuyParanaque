<!DOCTYPE html>
<html>

<head>
	<title>GoodBuy Enterprises | Admin</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" type="image/x-icon">
    <link href="<?php echo base_url('assets/admin/fonts/iconfont/material-icons.css');?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/admin/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/admin/plugins/node-waves/waves.css');?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/admin/plugins/animate-css/animate.css');?>" rel="stylesheet" />
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/admin/plugins/bootstrap-select/css/bootstrap-select.css');?>" rel="stylesheet" />
	<!-- Bootstrap Tagsinput Css -->
    <link href="<?php echo base_url('assets/admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css');?>" rel="stylesheet">
    <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/admin/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css');?>" rel="stylesheet">
	<!-- Dropzone Css -->
    <link href="<?php echo base_url('assets/admin/plugins/dropzone/dropzone.css');?>" rel="stylesheet">
	<!-- Wait Me Css -->
    <link href="<?php echo base_url('assets/admin/plugins/waitme/waitMe.css');?>" rel="stylesheet" />
	<!-- Sweetalert Css -->
    <link href="<?php echo base_url('assets/admin/plugins/sweetalert/sweetalert.css');?>" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/admin/css/style.css');?>" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/admin/css/themes/theme-green.css');?>" rel="stylesheet" />
	<!-- DATETIME PICKER https://fonts.googleapis.com/icon?family=Material+Icons-->
    <link href="<?php echo base_url('assets/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css');?>" rel="stylesheet" />	
	
	<!-- AUTOCOMPLETE CSS file -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplete/easy-autocomplete.min.css');?>"> 

	<!-- Additional CSS Themes file - not required-->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplete/easy-autocomplete.themes.min.css');?>"> 
	<link href="<?php echo base_url('assets/css/interface.css');?>" rel="stylesheet" />

	
</head>
<body class="theme-green">
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
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    
	<?=$header?>    

	<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
		
            <!-- User Info -->
            <div class="user-info">
				<div class="row clearfix">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<div class="image">
						<?php 
						if(isset($_SESSION['profile'])) 
							$profile = base_url($_SESSION['profile']);
						else 
							$profile = base_url('assets/admin/images/gb-logo.png');
						?>
							<img src="<?php echo $profile;?>" width="48" height="48" alt="User" />
						</div>
					</div>
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
						<div class="info-container">
							<div class="welcome" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome, <?php echo html_escape(ucfirst($_SESSION['gb_username']));?>!<br>
							</div>
							Admin
						</div>
					</div>
				</div>
            </div>
            <!-- #User Info -->
			
            <!-- Menu -->
            <div class="menu">
				<ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active">
                        <a href="<?php echo base_url('admin/dashboard');?>">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">playlist_add_check</i>
                            <span>Inventory</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('admin/category');?>">Categories</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('admin/inventory/product_attributes');?>">Attributes</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/inventory/suppliers');?>">Suppliers</a>
							</li>
                            <li>
                                <a href="<?php echo base_url('admin/inventory/products');?>">Products</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/inventory/prod_add_form');?>">Add Product</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/inventory/batch_price_tags');?>" >Batch Print</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/inventory/non_saleable');?>">Non-saleable Items</a>
                            </li>	
							<li>
                                <a href="<?php echo base_url('admin/inventory/scanned_items');?>">Scanned Items</a>
                            </li>		
                        </ul>
                    </li>
					<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">content_paste</i>
                            <span>Reports</span>
                        </a>
						<ul class="ml-menu">
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">Detailed</a>
								<ul class="ml-menu">
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_purchase');?>">Purchase</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_profit');?>">Profit</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_transaction');?>">Transaction</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_sales');?>">Sales</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_inventory');?>">Inventory Items</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_expenses');?>">Expenses</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_transferred');?>">Transferred items</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/detailed_non_saleable');?>">Non-saleable items</a>
									</li>
								</ul>
							</li>
							
                            </li>
							<li>
                                <a href="javascript:void(0);" class="menu-toggle">Summary</a>
								<ul class="ml-menu">
									<li>
										<a href="<?php echo base_url('admin/reports/summary_purchase');?>">Purchase</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/summary_sold');?>">Sold</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/summary_inventory');?>">Inventory Items</a>
									</li>						
									<li>
										<a href="<?php echo base_url('admin/reports/summary_transferred');?>">Transferred items</a>
									</li>
									<li>
										<a href="<?php echo base_url('admin/reports/summary_non_saleable');?>">Non-saleable items</a>
									</li>
								</ul>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/reports/tally');?>">Tally</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo base_url('admin/sales_management');?>">
                            <i class="material-icons">trending_up</i>
                            <span>Sales Management</span>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">shopping_cart</i>
                            <span>Order Management</span>
                        </a>
						<ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('admin/order_management/customer_order');?>">Customer Orders</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/order_management/canceled_order');?>">Canceled Orders</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/order_management/manage_courier');?>">Manage Couriers</a>
                            </li>
                        </ul>
                    </li>
					<li>
							<a href="<?php echo base_url('admin/rewards_card');?>">
                            <i class="material-icons">card_membership</i>
                            <span>Rewards Card</span>
                        </a>
                    </li>
					<li>
                        <a href="<?php echo base_url('admin/expenses');?>">
                            <i class="material-icons">payment</i>
                            <span>Expenses</span>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">swap_horiz</i>
                            <span>Item Transfer</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('admin/transfer/transfer_item');?>">Transfer Items</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/transfer/receive_item');?>">Transferred Items</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/transfer/manage_locations');?>">Manage Branch Locations</a>
                            </li>							
                        </ul>
                    </li>
					<li>
                        <a href="<?php echo base_url('admin/user_management');?>">
                            <i class="material-icons">people</i>
                            <span>User Management</span>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">build</i>
                            <span>Admin Tools</span>
                        </a>
						<ul class="ml-menu">
						    <li>
                                <a href="<?php echo base_url('admin/activity_log');?>">Activity Log</a>
                            </li>
							<li>
                                <a href="<?php echo base_url('admin/banner');?>" >Announcement Banner</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('admin/backup_recovery');?>">Backup & Recovery</a>
                            </li>
							<li>
                                <a id = "open-min-qty" data-toggle="modal" data-target="#modal-min-qty">Update Minimum Quantity</a>
                            </li>
							<li>
                                <a id = "open-return-policy" data-toggle="modal" data-target="#modal-return-policy">Update Return Policy</a>
                            </li>					
							<li>
                                <a id = "open-vat-reg" data-toggle="modal" data-target="#modal-vat-reg">Update VAT Reg Tin</a>
                            </li>
							<li>
                                <a id = "open-vat-perc" data-toggle="modal" data-target="#modal-vat-perc">Update VAT Percent</a>
                            </li>
							
                        </ul>
                    </li>
					<li class="header">DOWNLOADS</li>
                    
					 <li>
                        <a href="<?php echo base_url('admin/download_mob_app/pos');?>">
                            <i class="material-icons">phone_iphone</i>
                            <span>POS & Physical Inventory App</span>
                        </a>
                    </li>
					<li>
                        <a href="<?php echo base_url('admin/download_user_guide');?>">
                            <i class="material-icons">help</i>
                            <span>User Manual</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            
		<?=$footer?>
			
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
	<?=$content?>

	<!-- RETURN POLICY MODAL -->
	<div class="modal fade" id="modal-return-policy" tabindex="-1" role="dialog">
       <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
			<form action="<?php echo base_url('admin/update_return_policy');?>" id = "form-return-policy" method="POST">
				<div class="modal-header">
                    <h1 class="modal-title">Update Return Policy</h1>
                </div>
                <div class="modal-body">
				<small class="form-label">Number of days</small>
                    <div class="form-group form-float">
                        <div class="form-line success">
							<input type="hidden" class="form-control" name = "modal-policy_id">
                            <input type="number" min='0' class="form-control" name = "modal-days">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
					<button type="submit" class="btn bg-green waves-effect">SUBMIT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->
	<!-- MINIMUM QUANTITY MODAL -->
	<div class="modal fade" id="modal-min-qty" tabindex="-1" role="dialog">
       <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
			<form action="<?php echo base_url('admin/update_min_qty');?>" id = "form-min-qty" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title">Update Minimum Quantity</h1>
                </div>
                <div class="modal-body">
				    <small class="form-label">Minimum Quantity</small>
                    <div class="form-group form-float">
                        <div class="form-line success">
							<input type="hidden" class="form-control" name = "modal-qty_id">                            
                            <input type="number" min='0' class="form-control" name = "modal-min_qty">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-green waves-effect">SUBMIT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->	
	<!-- SHIPPING FEE MODAL -->
	<div class="modal fade" id="modal-shipping-fee" tabindex="-1" role="dialog">
       <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
			<form action="<?php echo base_url('admin/update_shipping_fee');?>" id = "form-shipping-fee" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title">Update Shipping Fee</h1>
                </div>
                <div class="modal-body">
				    <small class="form-label">Shipping Fee</small>
                    <div class="form-group form-float">
                        <div class="form-line success">
							<input type="hidden" class="form-control" name = "modal-id">                            
                            <input type="number" min='0' class="form-control" name = "modal-shipping_fee">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-green waves-effect">SUBMIT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->	
	<!-- VAT REG TIN MODAL -->
	<div class="modal fade" id="modal-vat-reg" tabindex="-1" role="dialog">
       <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
			<form action="<?php echo base_url('admin/update_vat_reg');?>" id = "form-vat-reg" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title">Update VAT Reg Tin</h1>
                </div>
                <div class="modal-body">
				    <small class="form-label">VAT Reg Tin</small>
                    <div class="form-group form-float">
                        <div class="form-line success">
							<input type="hidden" class="form-control" name = "modal-id">                            
                            <input type="number" min='0' class="form-control" name = "modal-reg_tin">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-green waves-effect">SUBMIT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->	
	<!-- VAT PERCENT MODAL -->
	<div class="modal fade" id="modal-vat-perc" tabindex="-1" role="dialog">
       <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
			<form action="<?php echo base_url('admin/update_vat_perc');?>" id = "form-vat-perc" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title">Update VAT Percent</h1>
                </div>
                <div class="modal-body">
				    <small class="form-label">VAT Percent</small>
                    <div class="form-group form-float">
                        <div class="form-line success">
							<input type="hidden" class="form-control" name = "modal-id">                            
                            <input type="number" min='0' class="form-control" name = "modal-vat_percent">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-green waves-effect">SUBMIT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
			</form>
            </div>
        </div>
    </div>
	<!-- #END# -->	
							
	<!-- PRODUCT INFO MODAL -->
	<div class="modal fade js-sweetalert" id="product-info" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">								
				<div class="modal-header">
					<small>PRODUCT INFO</small>
					<div class="pull-right">
					&nbsp;
						<a data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Close">
							<i class="material-icons col-grey">close</i>
						</a>
					</div>
					<br>
					<input style="border: none;border-color: transparent;"class="modal-title" id="largeModalLabel" name="modal-sku"/>
				</div>
				<hr>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="product-pic">
								<img  class="main-holder"  name = "primary_image" style="width:100%;height:100%; image-rendering: -moz-crisp-edges; -ms-interpolation-mode: bicubic;"/>
							</div>
							<br>					
							<div class="other-pics">
							</div>
						</div>								 
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<form class = "form-prod-view" method="POST" >
								<div class="row clearfix">
									<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Category</small>
											<input type="text" name="modal-cat_name" class="form-control" disabled>

										</div>
									</div>
									<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Subcategory</small>
											<input type="text" name="modal-subcat_name" class="form-control" disabled>
										</div>
									</div>
									<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Supplier</small>
											<input type="text" name="modal-sup_name" class="form-control" disabled>
										</div>
									</div>		
								</div>
								<div class="row clearfix">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Name</small>
											<div class="form-line success">
												<input type="text" name="modal-name" class="form-control" autocomplete="name" disabled>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Brand</small>
											<div class="form-line success">
												<input type="text" name="modal-brand" class="form-control" disabled>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Description</small>
											<div class="form-line success">
												<input name="modal-description" cols="30" rows="2" class="form-control no-resize" disabled>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<div class="form-line success">
												<label class="form-label">Purchase Price</label>
												<input type="number" name="modal-purchase_price" class="form-control" min="0.00" step="0.01" value="180.00" disabled>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<div class="form-line success">
												<label class="form-label">Selling Price</label>
												<input type="number" name="modal-selling_price" class="form-control" min="0.00" step="0.01" value="180.00" disabled>
											</div>
										</div>
									</div>					
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<div class="form-line success">
												<label class="form-label">Quantity</label>
												<input type="number" name="modal-quantity" class="form-control" min="0" step="1" value="11" disabled>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group demo-tagsinput-area form-float">
											<small class="form-label col-grey">Product Attributes</small>
											<div class="form-line success"><!-- data-role="tagsinput" -->
												<input type="text" name="modal-options" class="form-control"  disabled />
											</div>
										</div>
									</div>
								</div>
							</form>
						 </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# -->
	
	<!-- Jquery Core Js -->
	<script src="<?php echo base_url('assets/admin/plugins/jquery/jquery.min.js');?>"></script>
	<!--<script src="http://code.jquery.com/jquery-3.3.1.js" 
	integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" 
	crossorigin="anonymous"></script>
	<script src="http://code.jquery.com/jquery-2.2.4.js"
			  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
			  crossorigin="anonymous"></script>-->
    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery-countto/jquery.countTo.js');?>"></script>
	<!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap/js/bootstrap.js');?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/node-waves/waves.js');?>"></script>
	<!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>
	<!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>
	<!-- Bootstrap Tags Input Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js');?>"></script>
	<!-- Bootstrap Typeahead Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/typeahead.js');?>"></script>
	<!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <!-- Jquery DataTable Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/jquery.dataTables.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/buttons.flash.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/jszip.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/pdfmake.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/vfs_fonts.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/buttons.html5.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/jquery-datatable/extensions/export/buttons.print.min.js');?>"></script>
	<!-- Dropzone Plugin Js https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/dropzone.js --> 
    <script src="<?php echo base_url('assets/admin/plugins/dropzone/dropzone.js');?>"></script>
	
	<!-- Moment Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/momentjs/moment.js');?>"></script>
	<!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/sweetalert/sweetalert.min.js');?>"></script>
	<!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery-validation/jquery.validate.js');?>"></script>
   	<?php if(($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') || $this->uri->segment(2) == 'dashboard'):?>  
   	<!-- Chart Plugins Js -->
    <script src="<?php echo base_url('assets/admin/plugins/chartjs/Chart.bundle.js')?>"></script>
	<script src="<?php echo base_url('assets/admin/js/pages/charts/chartjs.js'). "?sig=" . rand(pow(10, 3-1), pow(10, 3)-1); ?>"?>"></script>	
	
	<!-- Flot Charts Plugin Js 
    <script src="<?php echo base_url('assets/admin/plugins/flot-charts/jquery.flot.js')?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/flot-charts/jquery.flot.resize.js')?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/flot-charts/jquery.flot.pie.js')?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/flot-charts/jquery.flot.categories.js')?>"></script>
    <script src="<?php echo base_url('assets/admin/plugins/flot-charts/jquery.flot.time.js')?>"></script>-->
	<?php endif;?>

	<!-- Alert and Notification Js -->
	<script src="<?php echo base_url('assets/admin/plugins/sweetalert/confirm-box.js')?>"></script>
	<!-- Custom Js -->
    <script src="<?php echo base_url('assets/admin/js/admin.js'). "?sig=" . rand(pow(10, 3-1), pow(10, 3)-1);?>"></script>
    <script src="<?php echo base_url('assets/admin/js/pages/tables/jquery-datatable.js');?>"></script>
	<script src="<?php echo base_url('assets/admin/js/pages/charts/chartjs.js'). "?sig=" . rand(pow(10, 3-1), pow(10, 3)-1); ?>"></script>
	
	<!--
	<script src="<?php echo base_url('assets/admin/js/pages/index.js')?>"></script>
	<script src="<?php echo base_url('assets/admin/js/pages/forms/basic-form-elements.js')?>"></script>-->
    <!-- Demo Js -->
	<!--<script src="<?php echo base_url('assets/admin/js/demo.js');?>"></script>-->

    <!-- Autocomplete -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.js"></script>-->
	
	<!-- Datetime picker -->
	<script src="<?php echo base_url('assets/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')?>"></script>
	
	<!-- Autocomplete JS file -->
	<script src="<?php echo base_url('assets/plugins/autocomplete/jquery.easy-autocomplete.min.js');?>"></script> 
	<script src="<?php echo base_url('assets/plugins/jquery-autocomplete/jquery.autocomplete.js');?>"></script> 
	
	<?php if(isset($is_mobile)): ?>
		<input type="hidden" id="is-mobile" value="<?php echo $is_mobile;?>" />
	<?php endif; ?>
	
	<!-- QR CODE DECODER -->		
	<script src="<?php echo base_url('assets/plugins/qrdecoder/qcode-decoder.min.js').'?sig='.rand();?>"></script>

	 

	<!-- QR CODE DECODER 	
	<?php if($is_mobile) :?>
		<script src="<?php echo base_url('assets/plugins/qrdecoder3/qcode-decoder.min.js').'?sig='.rand();?>"></script>
	<?php else :?>
		<script src="<?php echo base_url('assets/plugins/qrdecoder2/qcode-decoder.min.js').'?sig='.rand();?>"></script>
	<?php endif; ?>	
	 -->
	 
	<!-- Supercustom JS-->
	<script src="<?php echo base_url('assets/js/interface.js'). "?sig=" . rand(pow(10, 3-1), pow(10, 3)-1); ?>"></script>
	<?php
		//showNotification(colorName, null, placementFrom, placementAlign, animateEnter, animateExit);
		//$alert_type = $this->session->flashdata('alert_type');
		$alert_msg = $this->session->flashdata('alert_msg');
		$error_msg = $this->session->flashdata('error_msg');
		if($alert_msg) echo "<script>showNotification('bg-black','$alert_msg', 'top', 'right', null, null);</script>";
		if($error_msg) echo "<script>showNotification('bg-red','$error_msg', 'top', 'right', null, null);</script>";

	?>

	<script>
(function($){
}(jQuery));	
	</script>
</body>
</html>