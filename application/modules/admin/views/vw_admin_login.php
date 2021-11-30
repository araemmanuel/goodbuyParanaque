<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>GoodBuy | Login</title>
	
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/admin/fonts/iconfont/material-icons.css');?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/admin/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/admin/plugins/node-waves/waves.css');?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/admin/plugins/animate-css/animate.css');?>" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/admin/css/style.css');?>" rel="stylesheet">
</head>

<body class="login-page gradient-green">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Good<b>Buy</b> Enterprises</a>
        </div>
        <div class="card">
            <div class="body">
                <form action = "<?php echo base_url('admin/login');?>" id="sign_in"  method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line success">
                            <input type="text" class="form-control" name = "login_username" placeholder="Username" required autofocus>
							<?php echo form_error('login_username');  ?>
						</div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line success">
                            <input type="password" class="form-control" name = "login_password" placeholder="Password" required>
							<?php echo form_error('login_password');  ?>
                        </div>
                    </div>
					<div style="text-align:center;color:red;"><?php echo $this->session->flashdata('login_error'); ?></div>
                    <div class="row">
						<div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <button class="btn btn-block bg-green waves-effect" type="submit">SIGN IN</button>
                        </div>
						<div class="col-xs-3"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery/jquery.min.js');?>"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/admin/plugins/bootstrap/js/bootstrap.js');?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/node-waves/waves.js');?>"></script>
    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/admin/plugins/jquery-validation/jquery.validate.js');?>"></script>
    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/admin/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/js/pages/examples/sign-in.js');?>"></script>
</body>

</html>