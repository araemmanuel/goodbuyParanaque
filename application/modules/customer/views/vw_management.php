<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>GoodBuy Online Shop</title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" />
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" />
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125162090-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-125162090-1');
	</script>
    <!-- Google Fonts -->
     <link href="<?php echo base_url('assets/admin/fonts/iconfont/material-icons.css');?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/customer/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
     <link href="<?php echo base_url('assets/customer/css/materialize.css');?>" rel="stylesheet">
    <?=$css?>
</head>

<body class="theme-green" onLoad="document.getElementById('alert_account').click();">
    <!-- Page Loader -->
    <?=$page_loader?>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
	<?=$header?> 
	<!-- #Top Bar -->

	<section class="content">
		<div class="common_element_view_banner">
			<h2><?=$page_title?></h2>
		</div>
		<div class="bg-white">
			<div class="profile_view_holder">
				<div class="row">
					<div class="col-lg-12 profile_view">
						<div class="card profile_view_card">
							<div class="row">
								<?=$side?>
								<?=$content?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?=$footer?>
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery/jquery.min.js');?>"></script>
    <!-- Jquery Cookie -->
	<script src="<?php echo base_url('assets/customer/js/JqueryCookie/jquery.cookie.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/js/product.js');?>"></script>
	<script src="<?php echo base_url('assets/customer/js/notifications.js');?>"></script>
    <script type="text/javascript">
        var base_url = "<?php echo base_url();?>";
    </script>
    <script src="<?php echo base_url('assets/customer/js/shop_query.js');?>"></script>
    <script type="text/javascript">
    	var categ_page = window.location.href.substring(window.location.href.indexOf("customer") + 9);
    		$('#'+categ_page).addClass('list-group-active');
		load_cart_items();
    </script>
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <?=$js?>
</body>

</html>