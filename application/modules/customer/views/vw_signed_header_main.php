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
                    <li><a href="javascript:void(0);" class="categ_trigger">Categories</a></li>
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
							<button class="btn btn-circle bg-orange" id="btn_search_items"><i class="material-icons col-white">search</i></button>
							<button type="button" class="btn bg-pink btn-block waves-effect jsdemo-notification-button" data-placement-from="top" data-placement-align="right" data-animate-enter="animated fadeInDown"
                                            data-animate-exit="animated fadeOutDown" data-color-name="bg-green" style="display:none !important;" id="alert_account" data-username="<?php echo $q_name; ?>">
                                        FADE IN/OUT DOWN
		</button>
						</div>
					</li>
                    <!-- #END# Call Search -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
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
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white" data-toggle="tooltip" data-placement="bottom">account_circle</i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-cart">
                            <li class="header">Manage Account</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="<?php echo base_url('customer/view_profile');?>">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Profile Settings</h4>
                                                <p>
                                                    Change username, address, etc.
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('customer/view_orders');?>">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">shopping_basket</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>My Orders</h4>
                                                <p>
                                                    Check previous and ongoing orders.
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('customer/logout');?>">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">power_settings_new</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Logout</h4>
                                                <p>
                                                    Make sure you finished your transaction.
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                </ul>
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