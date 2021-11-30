	<!-- Top Bar -->
    <nav class="navbar gradient-green">
        <div class="container-fluid">
            <div class="navbar-header" style="height:50px!important;margin-bottom:-5px!important;">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?php echo base_url('admin');?>">Good<b>Buy</b> Enterprises</a>
            </div>
			
            <div class="collapse navbar-collapse gradient-green" id="navbar-collapse" style="height:50px!important;">
                <ul class="nav navbar-nav navbar-right">
					<!-- Notifications -->
                    <li class="dropdown" style="background-color:transparent!important;" data-toggle="tooltip" data-placement="bottom" title="Notifications">
                        <a href="javascript:void(0);" id="notif-link" class="dropdown-toggle" data-toggle="dropdown" >
                            <i class="material-icons">notifications</i>
							<?php if($sku_count > 0): ?>
                            <span class="label-count" id="notif-bell"  style="background-color:red!important;"><?=$sku_count?></span>
							<?php endif;?>
						</a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
								<?php if(count($notifs) == 0): ?>	
                                    <li>
										<br>
                                        <p class="align-center">
											<?php echo "No new notifications.";?>
										</p>
									</li>
								<?php endif;?>
									<?php 
									//$ctr = 0;
									foreach($notifs as $n):?>
									
									<li>
                                        <a href="javascript:void(0);">
                                            <!--
											<a href="javascript:void(0);">
											<div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
											-->
                                            <div class="menu-info">
                                                <h4 style="font-weight:400!important;"><?=str_ireplace("(*)",$n->sku . "-" .$n->name . " (" .$n->options.")", $n->message)?></h4>
                                                
												<?php 
												/*
													$ctr++;
													if($ctr == 8)
														break;
												*/
												?>
												<?php 
												
												if($n->days_ago > 0)
												{
													if($n->days_ago == 1)
														$datetime_ago = " 1 day ago";
													else 
														$datetime_ago = " ". $n->days_ago . " days ago";
												}
												else if($n->hrs_ago > 0)
												{
													if($n->hrs_ago == 1)
														$datetime_ago = " 1 hour ago";
													else 
														$datetime_ago = " " . $n->hrs_ago . " hours ago";
												}
												else if($n->mins_ago > 0)
												{
													if($n->mins_ago == 1)
														$datetime_ago = " 1 minute ago";
													else 
														$datetime_ago = " " . $n->mins_ago . " minutes ago";
												}
												else
													$datetime_ago = " Just now";
												
												if(!isset($datetime_ago))
													$datetime_ago = null;
												?>
												<p>
                                                    <i class="material-icons">access_time</i><?=$datetime_ago?>
                                                </p>  
											</div>
                                        </a>
                                    </li>
									<?php endforeach;?>
                                </ul>
                            </li>
                           <!--<li class="footer">
								<?php if($sku_count > 8):?>
									<a href="javascript:void(0);">View All Notifications</a>
								<?php endif; ?>    
							</li>-->
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
					<li style="background-color:transparent!important;" data-toggle="tooltip" data-placement="bottom" title="Add Product">
						<a href="<?php echo base_url('admin/inventory/prod_add_form');?>">
							<i class="material-icons">add</i>
							<!--<span>Add Product</span>-->
						</a>
					</li>
					<li style="background-color:transparent!important;" data-toggle="tooltip" data-placement="bottom" title="Profile"><a href="<?php echo base_url('admin/user_management/edit_form/123/'.$_SESSION['gb_username']);?>"><i class="material-icons">person</i></a></li>
                    <li style="background-color:transparent!important;" class="pull-right" data-toggle="tooltip" data-placement="bottom" title="Log Out">
					<a href="<?php echo base_url('admin/logout/admin');?>"><i class="material-icons">exit_to_app</i></a></li>
				</ul>
            </div>
        </div>
    </nav>