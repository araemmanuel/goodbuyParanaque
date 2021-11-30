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
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/main.js');?>"></script> <!-- Resource jQuery -->

    <!-- noUISlider Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/nouislider/nouislider.js');?>"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>
    <!-- JS for getting sub categs and query results -->
    <script src="<?php echo base_url('assets/customer/js/shop_query.js');?>"></script>

    <script type="text/javascript">
"use strict";

$(document).ready(function () {
    setInterval(function () {
        $('#owl_banner > div:first').fadeOut(1000).next().fadeIn(1000).end().appendTo('#owl_banner');
    }, 10000);
    load_cart_items();
    var categ_page = window.location.href.substring(window.location.href.indexOf("shop_categ") + 11);
    load_products_categ(categ_page, "<?php echo base_url();?>");

    var rangeSlider = document.getElementById('nouislider_range_example');
    noUiSlider.create(rangeSlider, {
        start: [0, 1000],
        connect: true,
        range: {
            'min': 0,
            'max': <?php echo $max_price; ?>
        }
    });
    getNoUISliderValue(rangeSlider, false);

    var rangeSlider_1 = document.getElementById('nouislider_range_example_modal');
    noUiSlider.create(rangeSlider_1, {
        start: [0, 1000],
        connect: true,
        range: {
            'min': 0,
            'max': 500
        }
    });
    getNoUISliderValue(rangeSlider_1, false);
});

function getNoUISliderValue(slider, percentage) {
    slider.noUiSlider.on('update', function () {
        var val = slider.noUiSlider.get();
        if (percentage) {
            val = parseInt(val);
            val += '%';
        }
        var val_string = new String(val);
        var val_stringed = val_string.split(",");
        $(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);
        $(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);
        filter(val_stringed[0], val_stringed[1]);
    });
}
    </script>