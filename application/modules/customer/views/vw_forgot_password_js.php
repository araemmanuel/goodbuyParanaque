
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap/js/bootstrap.js');?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>
    
    <!-- Dropzone Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/node-waves/waves.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/customer/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/js/pages/ui/tooltips-popovers.js');?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/customer/js/demo.js');?>"></script>
    
    <!-- Owl Carousel Js -->
    <script src="<?php echo base_url('assets/customer/js/OwlCarousel/dist/owl.carousel.js');?>"></script>
    
    <!-- Owl Carousel Js -->
    <script src="<?php echo base_url('assets/customer/js/MagnifyJs/jquery.elevatezoom.js');?>"></script>
    
    <!-- Auto Size Js -->
    <script src="<?php echo base_url('assets/customer/plugins/autosize/autosize.js');?>"></script>
    
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/jquery.menu-aim.js');?>"></script> <!-- menu aim -->
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/modernizr.js');?>"></script> <!-- menu aim -->

    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/js/pages/ui/dialogs.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/main.js');?>"></script> <!-- Resource jQuery -->

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>

    <script src="<?php echo base_url('assets/customer/js/shop_query.js');?>"></script>

    <script type="text/javascript">
	"use strict";
        $(document).ready(function()
        {
                $('.input_letters').keydown(function(e){ 
                if (e.ctrlKey || e.altKey) {
                e.preventDefault();
                } else {
                var key = e.keyCode;
                if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                e.preventDefault();
                    $(this).parent().parent().find('.small-small').empty();
                    $(this).parent().parent().find('.small-small').addClass("small-smaller");
                    $(this).parent().parent().find('.small-small').append("<p>Numbers are not allowed!</p>");
                }
                else
                {
                    $(this).parent().parent().find('.small-small').empty();
                }
                }
                
			});
        });
    </script>