
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
		(function() {
	
			function PasswordMeter( element, meter ,eventt) {
				this.element = element;
				if(eventt.keyCode == 8)
				{
					var temp = "";
					var split_input = this.element.value.split("");
					for(var v = 0; v < split_input.length - 1; v++)
					{
						temp = temp + split_input[v] + "";
					}
					this.elementValue = temp;
				}
				else if (!((eventt.keyCode < 91 && eventt.keyCode > 64) || (eventt.keyCode > 97 && eventt.keyCode < 123) || (eventt.keyCode > 47 && eventt.keyCode < 58) || 	eventt.keyCode == 32) === true) 
				{
					event.preventDefault();
					return false;
					this.elementValue = this.element.value;
				}
				else
				{
					this.elementValue = this.element.value + String.fromCharCode(eventt.keyCode);
				}
				
				this.meter = meter;
				this.meterWidth = this.meter.offsetWidth;
				this.meterBar = this.meter.querySelector( "#password-bar" );
				this.elementValueLength = this.elementValue.length;
				this.tokens = {
					letters: "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
					numbers: "0123456789",
					specialChars: "!&%/()=?^*+][#><;:,._-|"	
				};
				
				this.letters = this.tokens.letters.split( "" );
				this.numbers = this.tokens.numbers.split( "" );
				this.specialChars = this.tokens.specialChars.split( "" );
				this.init();
				
			}
			
			PasswordMeter.prototype = {
				init: function() {
					this.check();
				},
				check: function() {
					var self = this;
					var val = self.elementValue;
					var total = self.elementValueLength;
					
					var totalLetters = 0;
					var totalNumbers = 0;
					var totalSpecialChars = 0;
					
					var tokens = val.split( "" );
					var len = tokens.length;
					var i;
					
					for( i = 0; i < len; ++i ) {
						var token = tokens[i];
						if( self._isLetter( token ) ) {
							totalLetters++;
						} else if( self._isNumber( token ) ) {
							totalNumbers++;
						} else if( self._isSpecialChar( token ) ) {
							totalSpecialChars++;
						}
						
					}
					
					
					var result = self._calculate( total, totalLetters, totalNumbers, totalSpecialChars );
					var perc = result * 10;
					if(perc <= 24 && perc >= 0)
					{
						self.meterBar.style.background = "#D80000";
					}
					else if(perc >= 25 && perc <= 49)
					{
						self.meterBar.style.background = "#FF5B00";
					}
					else if(perc >= 50 && perc <= 74)
					{
						self.meterBar.style.background = "#F4EB0B";
					}
					else if(perc >= 75 && perc <= 100)
					{
						self.meterBar.style.background = "#449C4D";
					}
					var percStr = perc.toString();
					self.meterBar.style.width = percStr + "%";	
				},
				_isLetter: function( token ) {
					var self = this;
					if( self.letters.indexOf( token ) == -1 ) {
						return false;
					}
					return true;
				},
				_isNumber: function( token ) {
					var self = this;
					if( self.numbers.indexOf( token ) == -1 ) {
						return false;
					}
					return true;
				},
				_isSpecialChar: function( token ) {
					var self = this;
					if( self.specialChars.indexOf( token ) == -1 ) {
						return false;
					}
					return true;
				},
				_calculate: function( total, letters, numbers, chars ) {
					var level = 0;
					var l = parseInt( letters, 10 );
					var n = parseInt( numbers, 10 );
					var c = parseInt( chars, 10 );
					
					
					
					if( total < 16 ) {
						level += 1;
					}
					if( total >= 16 ) {
						level += 4;
					}
					
					if( l > 0 ) {
						level += 1;
					}
					
					if( n > 0 ) {
						level += 2;
					}
					
					if( c > 0 ) {
						level += 3;
					}
					
					return level;
				}
			};
			
			document.addEventListener( "DOMContentLoaded", function() {
				var password = document.querySelector( "#sign_up_password_id" ),
					meter = document.querySelector( "#password-level" );
					$("#sign_up_password_id").keydown(function (e){
						var pwdMeter = new PasswordMeter( password, meter, e );
					})
				
			});
			
		})();
        
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

            $('#sign_up_username_id').keydown(function(e)
                {
                    if(e.ctrlKey || e.altKey || (e.keyCode == 32) || (e.keyCode == 46))
                    {
                        $('#sign_up_username_label').empty();   
                    }
                    else
                    {
                        var username_typed = "";
                        if(e.keyCode == 8)
                        {
                            var temp = "";
                            var split_input = $(this).val().split("");
                            for(var v = 0; v < split_input.length - 1; v++)
                            {
                                temp = temp + split_input[v] + "";
                                
                            }
                            username_typed = temp;
                        }
						else if (!((e.keyCode < 91 && e.keyCode > 64) || (e.keyCode > 97 && e.keyCode < 123) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 32) === true) 
						{
							event.preventDefault();
							return false;
						}
                        else
                        {
                            username_typed = $(this).val() + "" + String.fromCharCode(e.keyCode);
                        }
                        $.ajax({
                        type: 'ajax',
                        url: base_url+"customer/getUsername/"+username_typed,
                        async: true,
                        dataType: 'json',
                        success: function(data)
                        {
                            if(data == "true")
                            {
                                $('#sign_up_username_label').empty();
                                $('#sign_up_username_label').removeClass('small-small').addClass("small-smaller");
                                $('#sign_up_username_label').append("<p>Username is already taken!</p>");
                            }
                            else if(data == "false")
                            {
                                $('#sign_up_username_label').empty();
                            }
                        }
                    });
                    }   
                }
            );
        });
    </script>