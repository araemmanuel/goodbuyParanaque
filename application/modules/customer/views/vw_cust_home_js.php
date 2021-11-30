

    <!-- Bootstrap Core Js -->
    <script src=" <?php echo base_url('assets/customer/plugins/bootstrap/js/bootstrap.js');?>"></script>
	

    <!-- Select Plugin Js -->
    <script src=" <?php echo base_url('assets/customer/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>
	
	<!-- Dropzone Plugin Js -->
    <script src=" <?php echo base_url('assets/customer/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src=" <?php echo base_url('assets/customer/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src=" <?php echo base_url('assets/customer/plugins/node-waves/waves.js');?>"></script>

    <!-- Custom Js -->
    <script src=" <?php echo base_url('assets/customer/js/admin.js');?>"></script>
	<script src=" <?php echo base_url('assets/customer/js/pages/ui/tooltips-popovers.js');?>"></script>

    <!-- Demo Js -->
    <script src=" <?php echo base_url('assets/customer/js/demo.js');?>"></script>
	
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
	
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/jquery.menu-aim.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/modernizr.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/main.js');?>"></script> <!-- Resource jQuery -->
	<!-- ZoomMaster JS -->

	<!-- JS for getting sub categs and query results -->
    <script src="<?php echo base_url('assets/customer/js/shop_query.js');?>"></script>
	

	<script type="text/javascript">
	"use strict";
	post_categ();
		String.prototype.trunc = function(n){
	          return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
	      };
		$(document).ready(function(){
			
			$( ".size_options" ).each(function(index) {
				$(this).hover(function(){
					$("#product_pic_holder").attr("src",$(this).data("image-holder"));
				});
			});
			showAllProducts();
			load_cart_items();
		});
		
	</script>