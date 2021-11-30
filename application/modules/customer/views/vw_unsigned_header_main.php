	<!-- Search Bar -->
    <div class="search-bar" id="search_bar_id">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="I'M LOOKING FOR...">
        <div class="close-search">
            <i class="material-icons" id="search_bar_close">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
	
    <div class=" cd-dropdown-wrapper">
    <nav class="navbar navbar-goodbuy">    
    <div class="container-fluid" id="nav-goodbuy">
            <div class="row" style="padding-left: 2rem !important;padding-right: 5rem !important;">
                <div class="pre-nav-header right">
                    <a class="pre-nav-header-link col-green" href="<?php echo base_url('customer/track_order');?>">Track Orders</a>
                    <a class="pre-nav-header-link col-green" href="<?php echo base_url('customer/help');?>">Help</a>
                </div>
            </div>
            <div class="row main-navbar bg-gradient-green" style="padding-left: 2rem !important;padding-right: 5rem !important;">
                <div class="navbar-header">
					<a class="navbar-brand" href="<?php echo base_url('customer/home');?>">Good<b>Buy</b> Enterprises</a>
                </div>
                <ul class="nav navbar-nav navbar-left show_categ_mob">
                    <li><a href="javascript:void(0);"  class="categ_trigger">Categories</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
					<li class="hide_categ_mob categ_trigger">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white" data-placement="bottom" title="My Cart">menu</i>
                        </a>
					</li>
					<li>
						<div class="search_bar_new">
							<input type="text" placeholder="I'M LOOKING FOR..." id="input_search">
							<button class="btn bg-orange btn-circle" id="btn_search_items"><i class="material-icons col-white">search</i></button>
						</div>
					</li>
                    <!-- #END# Call Search -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white" data-toggle="tooltip" data-placement="bottom" title="My Cart">shopping_cart</i>
                            <span class="label-count" id="cart-list-span">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Cart</li>
                            <li class="body">
                                <ul class="menu tasks" id="cart-list-nav">
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="<?php echo base_url('customer/view_cart')?>" id="cart_go_go">View Shopping Cart</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <li class="dropdown small-small_icon">
                        <a class="dropdown-toggle clear_form" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white clear_form" data-toggle="tooltip" data-placement="bottom" title="Login">account_circle</i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-cart">
                            <li class="header">Login</li>
                            <li class="body">
								<form id="logged_in_form" class="loggin_form">
									<div class="row clearfix">
										<div class="col-sm-12 col-lg-12">
											<div class="form-group">
												<div class="form-line">
													<input type="text" class="form-control" placeholder="Username" name="login_username" />
												</div>
											</div>
										</div>
										<div class="col-sm-12 col-lg-12">
											<div class="form-group">
												<div class="form-line">
													<input type="password" class="form-control" placeholder="Password" name="login_password" />
												</div>
												<a href="<?php echo base_url('customer/forgot_password');?>" class="font-11 col-blue m-t-2">Forgot Password?</a>
											</div>
										</div>
										<span id="login_username_label" class="small-smaller"></span>
										<div class="col-sm-12 col-lg-12">
											<div class="form-group">
												<div class="form-line">
													<button type="submit" id="login_btn" class="btn btn-success btn-block waves-effect">
														Log In
													</button>
												</div>
                                                <div class="form-line">
                                                    <a href="<?php echo base_url('customer/view_sign_up');?>">
                                                        <button type="submit" id="signup_btn" class="btn btn-success btn-block waves-effect">
                                                        Sign Up
                                                        </button>
                                                    </a>
												</div>
											</div>
										</div>
									</div>
								</form>
                            </li>
                            <li class="footer">
								<a href="<?php echo base_url('customer/register_card');?>">Register Reward Card</a>
                            </li>
                        </ul>
                    </li> 
					<!-- #END# Tasks -->
                </ul>
            </div>
			<div class="row" style="padding-left: 2rem !important;padding-right: 5rem !important;">
                <div class="post-nav-bar">
					<div class="row" id="categ_subcateg_nav">
					</div>
                </div>
            </div>
        </div>
    </nav>
</div>