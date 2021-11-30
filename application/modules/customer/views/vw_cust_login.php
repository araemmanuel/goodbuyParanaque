<!DOCTYPE html>
<html>
<body>
<form action = "<?php echo base_url();?>" method = "POST">

<input name = "login_username" placeholder="Username..."/><br>
<?php echo form_error('login_username');  ?>

<input name = "login_password" placeholder="Password..."/><br>
<?php echo form_error('login_password');  ?>

<input type = "submit" name = "login" value = "LOGIN"/>
<?php echo $this->session->flashdata('login_error'); ?>

</form>
<br>
<br>
<form action = "<?php echo base_url('sign_up');?>" method = "POST">

<?php $reg_data = $this->session->flashdata('get_user_post');?>

<input name = "register_username" placeholder="Username..." value = "<?php echo html_escape($reg_data['username']);?>"/><br>
<?php echo form_error('register_username');  ?>

<input name = "register_password" placeholder="Password..."/><br>
<?php echo form_error('register_password');  ?>

<input name = "register_confirm_password" placeholder="Confirm Password..."/><br>
<?php echo form_error('register_confirm_password');  ?>

<input name = "register_email" placeholder="Email..."  value = "<?php echo html_escape($reg_data['email']);?>" /><br>
<?php echo form_error('register_email');  ?>

<input type = "submit" name = "register" value = "REGISTER"/>
<?php echo $this->session->flashdata('sign_up_success'); ?>

</form>

</body>
</html>
