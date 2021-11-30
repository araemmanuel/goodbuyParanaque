	<!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="I'M LOOKING FOR...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
	
    <div class=" cd-dropdown-wrapper">
    <nav class="navbar navbar-goodbuy">    
    <div class="container-fluid" id="nav-goodbuy">
            <div class="row" style="padding-left: 2rem !important;padding-right: 5rem !important;">
                <div class="pre-nav-header right">
                    <a class="pre-nav-header-link col-green" href="../../index.html">Track Orders</a>
                    <a class="pre-nav-header-link col-green" href="../../index.html">Cash on Delivery</a>
                    <a class="pre-nav-header-link col-green" href="../../index.html">Help</a>
                </div>
            </div>
            <div class="row main-navbar bg-gradient-green" style="padding-left: 2rem !important;padding-right: 5rem !important;">
                <div class="navbar-header">
                    <a href="<?php echo base_url()?>"><img class="logo_try" src="<?php echo base_url('assets/images/logo_try.png');?>" width="25%"></a>
                </div>
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="javascript:void(0);" class="cd-dropdown-trigger">Categories</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true" data-toggle="tooltip" data-placement="bottom" title="Search"><i class="material-icons col-white">search</i></a></li>
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
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white" data-toggle="tooltip" data-placement="bottom" title="Wishlist">favorite</i>
                            <span class="label-count" id="wish-list-span"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Wishlist</li>
                            <li class="body">
                                <ul class="menu tasks" id="wish-list-nav">
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- Tasks -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons col-white" data-toggle="tooltip" data-placement="bottom" title="Justine Jade Carlos">account_circle</i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-cart">
                            <li class="header">Manage Account</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Sign Up</h4>
                                                <p>
                                                    Create your account now!
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Log In</h4>
                                                <p>
                                                    Open your account now!
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
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
                                        <a href="<?php echo base_url('customer/view_cancelled');?>">
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
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">stars</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>My Rates</h4>
                                                <p>
                                                    Check product rating you gave.
                                                </p>
                                                
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
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
                            <li class="footer">
                                <a href="javascript:void(0);">View All</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Tasks -->
                </ul>
            </div>
			<div class="row">
                <div class="row">
                    <a class="pre-nav-header-link col-green" href="../../index.html">Track Orders</a>
                    <a class="pre-nav-header-link col-green" href="../../index.html">Cash on Delivery</a>
                    <a class="pre-nav-header-link col-green" href="../../index.html">Help</a>
                </div>
            </div>
        </div>
    </nav>
</div>