<script type="text/javascript">
document.getElementById('test').innerHTML = 'A simple line';
    
</script>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>GoodBuy | POS</title>
	
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/images/gb-logo.png');?>" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/cashier/fonts/iconfont/material-icons.css'); ?>" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/node-waves/waves.cs'); ?>s" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/cashier/plugins/animate-css/animate.css'); ?>" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/cashier/css/style.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/interface.css');?>" rel="stylesheet" />
</head>

<body class="login-page gradient-green">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Good<b>Buy</b> Enterprises</a>
			<small class="font-24"><b>Point of Sales</b></small>
        </div>
        <div class="card">
            <div class="body">
				<form action = "<?php echo base_url('cashier/login');?>" method = "POST">                   
				<div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line success">
							<input name = "login_username" id="test" class="form-control" required autofocus placeholder="Username"/><br>
                        </div>
							<div class="validation-errors"><?php echo form_error('login_username');  ?></div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line success">
							<input type="password" name = "login_password" class="form-control" required autofocus placeholder="Password"/><br>
                        </div>
						<div class="validation-errors"><?php echo form_error('login_password');  ?></div>
                    </div>
					
					<div class="row">
					<div class="validation-errors" style="color:red;text-align:center;">
						<?php if (isset($error)) echo  'Cannot login. ' . $error; ?>
						<?php echo $this->session->flashdata('login_error'); ?>	
					</div>
						<div class="col-xs-3"></div>
                        <div class="col-xs-6">
							<input type = "submit" class="btn btn-block bg-green waves-effect" name = "login" value = "LOGIN"/>		
                        </div>
						<div class="col-xs-3"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
	
			<!-- SET START DATE MODAL -->
			<div class="modal fade" id="set-start" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">SET START DATE</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-float">
								<div class="form-line success">
									<input type="date" name="start-date" class="datepicker form-control" value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-green waves-effect">SET</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
			<!-- #END# -->

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/bootstrap/js/bootstrap.js'); ?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/node-waves/waves.js'); ?>"></script>
    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/cashier/plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/cashier/js/admin.js'); ?>"></script>
    <script src="<?php echo base_url('assets/cashier/js/pages/examples/sign-in.js'); ?>"></script>
</body>

</html>
