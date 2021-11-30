

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap/js/bootstrap.js');?>"></script>
	<script src="<?php echo base_url('assets/customer/js/logged_in.js');?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>
	
	<!-- Dropzone Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>
	
	<!--  Mask Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>

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
	
	<!-- ZoomMaster JS -->
	<script src="<?php echo base_url('assets/customer/js/ZoomMasterJS/zoomove.min.js');?>"></script>
	
	<!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>

    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/js/pages/ui/dialogs.js');?>"></script>
	 <script src="<?php echo base_url('assets/customer/js/product.js');?>"></script>
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/jquery.menu-aim.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/modernizr.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/main.js');?>"></script> <!-- Resource jQuery -->
	<!-- JS for getting sub categs and query results -->
    <script src="<?php echo base_url('assets/customer/js/shop_query.js');?>"></script>
	
	<script type="text/javascript">
		"use strict";
		var customer_details, p_quantity;
		$('#checkout_username').hide();
		check_logged_in("checkout");
		$(document).ready(function(){
			$('#via_pickup').show();
			$('#via_cod').hide();
			$( ".size_options" ).each(function(index) {
				$(this).hover(function(){
					$("#product_pic_holder").attr("src",$(this).data("image-holder"));
				});
			});
			//Date
			var $demoMaskedInput = $('.masked-input'); 
			$demoMaskedInput.find('.date').inputmask('dd-mm-yyyy', { placeholder: '__-__-____' });
			//Credit Card
			$demoMaskedInput.find('.credit-card').inputmask('9999 9999 9999 9999', { placeholder: '____ ____ ____ ____' });
			
			$('#through_pickup').click(function(){
				$('#via_pickup').show();
				$('#via_cod').hide();
			});
			
			$('#through_cod').click(function(){
				$('#via_pickup').hide();
				$('#via_cod').show();
			});
			
			
		});


		$('.masked-input').hide("slow");
		String.prototype.trunc = function(n){
	          return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
	      };


		$(document).ready(function(){
			
			$( ".size_options" ).each(function(index) {
				$(this).hover(function(){
					$("#product_pic_holder").attr("src",$(this).data("image-holder"));
				});
			});


			$('.input_letters').keydown(function(e){ 
			    if (e.ctrlKey || e.altKey) {
				e.preventDefault();
				} else {
				var key = e.keyCode;
				if (key === 9 ) { //TAB was pressed
					return;
				 }
				else if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
				e.preventDefault();
					$(this).parent().parent().find('.small-small').empty();
					$(this).parent().parent().find('.small-small').removeClass('hidden').addClass("small-smaller");
					$(this).parent().parent().find('.small-small').text("Numbers are not allowed!");
				}
				else
				{
					$(this).parent().parent().find('.small-small').empty();
				}
				}
				});
			$('#through_credit_label').click(function(){
				$('.masked-input').show('slow');
			})
			$('#through_cod_label').click(function(){
				$('.masked-input').hide('slow');
			})

			$('.input_numbers').keydown(function(e){
			    if (e.ctrlKey || e.altKey) {
					e.preventDefault();
					}
				else {
					var key = e.keyCode;
					if (key === 9 ) { //TAB was pressed
						return;
					 }
					else if (( (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
					e.preventDefault();
					$(this).parent().parent().find('.small-small').empty();
						$(this).parent().parent().find('.small-small').removeClass('hidden').addClass("small-smaller");
						$(this).parent().parent().find('.small-small').text("Letters are not allowed!");
					}
					else
					{
						$(this).parent().parent().find('.small-small').empty();
					}
				}
				});
			load_order_summary();
			load_cart_items();

			
			
		});
	</script>