<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>GoodBuy Online Shopping</title>
    <!-- Favicon-->

    <link rel="shortcut icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" />
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" />
<script src="<?php echo base_url('assets/customer/js/product.js');?>"></script>
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
    <link href="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/css/reset.css');?>" rel="stylesheet">
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
    <?=$content?>
	<?=$footer?>
    <?=$largemodal?>
    <!-- Jquery Core Js -->
	<script type="text/javascript">
	"use strict";
	</script>
    <script src="<?php echo base_url('assets/customer/plugins/jquery/jquery.js');?>"></script>
    <!-- Jquery Cookie -->
    <script src="<?php echo base_url('assets/customer/js/JqueryCookie/jquery.cookie.js');?>"></script>
    <!-- Jquery Cookie Plugged In Product -->
    <script src="<?php echo base_url('assets/customer/js/product.js');?>"></script>
	<script src="<?php echo base_url('assets/customer/js/notifications.js');?>"></script>
    <script type="text/javascript">
        var base_url = "<?php echo base_url();?>";
		var categ_page = window.location.href.substring(window.location.href.indexOf("view_product") + 13);
	</script>
    <?=$js?>
	<script type="text/javascript">
	function getName()
	{
		$.ajax({
			type: 'ajax',
			url: base_url + 'customer/getName',
			async: true,
			dataType: 'json',
			success: function(data)
			{
				if(data != null)
				{
					$('#name_of_user').attr('title', data);
				}
			},
			error: function(data)
			{
						
			}
		});
	}
	
	getName();
	</script>
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
</body>

</html>