<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/customer/plugins/jquery/jquery.min.js');?>"></script>

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

	<!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/customer/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/customer/js/pages/ui/dialogs.js');?>"></script>
	
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/jquery.menu-aim.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/modernizr.js');?>"></script> <!-- menu aim -->
	<script src="<?php echo base_url('assets/customer/plugins/bootstrap-mega-menu/js/main.js');?>"></script> <!-- Resource jQuery -->
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#edit_btn").click(function(){
				$("#profile-unedit").hide();
				$("#profile-edit").fadeIn("slow");
				$("#save_btn").fadeIn("slow");
				$("#cancel_btn").fadeIn("slow");
				$("#edit_btn").hide();
			});
			
			$("#cancel_btn").click(function(){
				$("#profile-unedit").fadeIn("slow");
				$("#profile-edit").hide();
				$("#save_btn").hide();
				$("#cancel_btn").hide();
				$("#edit_btn").fadeIn("slow");
			});
			
			
			Dropzone.options.myDropzone= {
			url: 'upload.php',
			autoProcessQueue: false,
			uploadMultiple: false,
			parallelUploads: 5,
			maxFiles: 1,
			maxFilesize: 5,
			acceptedFiles: 'image/*',
			addRemoveLinks: true,
			dictDefaultMessage: 'Click here/Drop your picture here',
			init: function() {
				this.on("addedfile", function(file) { fileupload_flag = 1; });
				this.on("complete", function(file) { fileupload_flag = 0; });
			},
			accept: function(file, done) 
		   {
				var image = $(".dz-image img").attr('src');
				$("#myDropzone").css("background-image", "url("+image+")");  
			}
			
		};

		
		
		
			
		});

		function addtoCart(){
                swal({
                  title: 'Are you sure?',
                  text: "Do you want to add item/s to your cart?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes!',
                  cancelButtonText: 'No, cancel!',
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-link m-r-10',
                  buttonsStyling: false,
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {
                    swal(
                      'Added!',
                      'Item/s successfully added to your cart.',
                      'success'
                    )
                  // result.dismiss can be 'cancel', 'overlay',
                  // 'close', and 'timer'
                  } else if (result.dismiss === 'cancel') {
                    swal(
                      'Cancelled',
                      'Your imaginary file is safe :)',
                      'error'
                    )
                  }
                })              }

        function addtoWishList(){
                swal({
                  title: 'Select Wishlist',
                  input: 'select',
                  inputOptions: {
                    'WISH121': 'K-Style',
                    'WISH122': 'Minimalist',
                    'WISH123': 'Pastel'
                  },
                  inputPlaceholder: 'Select Wishlist',
                  showCancelButton: true,
                  inputValidator: (value) => {
                    return new Promise((resolve) => {
                      if (value === 'WISH121') {
                        swal(
                              'Added!',
                              'Item/s successfully added to your wishlist.',
                              'success'
                            )
                      } else {
                        resolve('You need to select Ukraine :)')
                      }
                    })
                  }
              })
            }
	</script>