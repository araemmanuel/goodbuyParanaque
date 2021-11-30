<?php
	if(!$items_min_qty_ctr)
		$items_min_qty_ctr = 0;

	if(!$todays_store_sales)
		$todays_sales = number_format(0.00, 2);
	
	if(!$todays_online_sales)
		$todays_online_sales = number_format(0.00, 2);
	
	if(!$todays_store_sales)
		$todays_online_sales = number_format(0.00, 2);
			
	if(!$todays_expenses)
		$todays_expenses = number_format(0.00, 2);

	if(!$this_months_sales)
		$this_months_sales = number_format(0.00, 2);

	if(!$this_months_expenses)
		$this_months_expenses = number_format(0.00, 2);
	
	if(!$pending_deliveries)
		$pending_deliveries = number_format(0.00, 2);

?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            <!-- Today Widgets -->
            <div class="row clearfix">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
                    <a href="<?php echo base_url('admin/min_qty');?>" style="text-decoration:none;">
					<div class="info-box bg-deep-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">priority_high</i>
                        </div>
                        <div class="content">
                            <div class="text">ITEMS IN MIN. QTY</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $items_min_qty_ctr;?>" data-speed="1000" data-fresh-interval="20"><?php echo $items_min_qty_ctr;?></div>
                        </div>
                    </div>
					</a>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_today_store_sales');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-amber hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">STORE SALES</div>
                            <div class="number">Php <?php echo $todays_store_sales;?></div>
                        </div>
                    </div>
					</a>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_today_online_sales');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-lime hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">shopping_basket</i>
                        </div>
                        <div class="content">
                            <div class="text">ONLINE SALES</div>
                            <div class="number">Php <?php echo $todays_online_sales;?></div>
                        </div>
                    </div>
					</a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_today_expenses');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">attach_money</i>
                        </div>
                        <div class="content">
                            <div class="text">TODAY'S EXPENSES</div>
                            <div class="number">Php <?php echo $todays_expenses;?></div>
                        </div>
                    </div>
					</a>
                </div>
				
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_pending_deliveries');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">directions_car</i>
                        </div>
                        <div class="content">
                            <div class="text">PENDING DELIVERIES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $pending_deliveries;?>" data-speed="1000" data-fresh-interval="20"><?php echo $pending_deliveries;?></div>
                        </div>
                    </div>
					</a>
                </div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_this_month_sales');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">local_offer</i>
                        </div>
                        <div class="content">
                            <div class="text">THIS MONTH'S SALES</div>
                            <div class="number">Php <?php echo $this_months_sales;?></div>
                        </div>
                    </div>
					</a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a data-href="<?php echo base_url('admin/reports/pdf_this_month_expenses');?>" class='open-print-window' style="text-decoration:none;">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">account_balance</i>
                        </div>
                        <div class="content">
                            <div class="text">THIS MONTH'S EXPENSES</div>
                            <div class="number">Php <?php echo $this_months_expenses;?></div>
                        </div>
                    </div>
					</a>
                </div>
            </div>
            <!-- #END# Today Widgets -->
			
			<!-- LINE CHART -->
			<div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>TOTAL MONTHLY SALES <small><?php echo date('Y');?></small></h2>
						</div>
                        <div class="body">
                            <canvas id="monthly-sales" width = "400" height="180"></canvas>
                        </div>
                    </div>
                </div>
				<?php foreach($categories as $c): ?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="card">
							<div class="header">
								<h2><?=html_escape($c->cat_name)?> <small>MONTHLY SALES  • <?php echo date('Y');?></small></h2>
							</div>
							<div class="body">
								<canvas id="chart-<?=html_escape($c->cat_name)?>" width = "400" height="180"></canvas>
							</div>
						</div>
					</div>
			<?php 
				break;
				endforeach; ?>	
			</div>
            <!-- #END# LINE CHART -->
			<?php foreach($other_categories as $c): 
				$cat = explode('-', $c);
				?>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2><?=html_escape($cat[0])?> <small>MONTHLY SALES • <?php echo date('Y');?></small></h2>
						</div>
                        <div class="body">
                            <canvas id="chart-<?=html_escape($cat[0])?>" width = "400" height="180"></canvas>
                        </div>
                    </div>
                </div>
				<?php if($cat[1] != null):?>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<h2><?=html_escape($cat[1])?> <small>MONTHLY SALES • <?php echo date('Y');?></small></h2>
						</div>
						<div class="body">
							<canvas id="chart-<?=html_escape($cat[1])?>" width = "400" height="180"></canvas>
						</div>
					</div>
				</div>
				<?php endif;?>
			</div>
			<?php endforeach; ?>
        </div>
    </section>
