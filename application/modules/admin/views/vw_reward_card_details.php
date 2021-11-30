	<section class="content">
        <div class="container-fluid">
		<?php foreach($card_info as $i): ?>
			<div class="block-header">
				<h2>REWARD CARD DETAILS
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/rewards_card');?>">Rewards Card</a></li>
							<li style="background-color:transparent!important;" class="active">Details</li>
							
						</ol>
					</small>
				</h2>
			</div>
			<br>
            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
							<div class="row clearfix" style="background:#eee;">
								<br>
								<div class="align-center">
									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
										<small>Card No.</small><br>
										<span class="font-20"><?=html_escape($i->card_no)?></span>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
										<small>Membership ID</small><br>
										<span class="font-20"><?=html_escape($i->membership_id)?></span>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
										<small>Customer Details</small><br>
										<span class="font-20 col-green"><?=html_escape($i->name)?></span>
										<br>
										<span class="font-14 col-green"><?=html_escape($i->email)?></span>
										<br>
										<span class="font-14 col-green"><?=html_escape($i->contact_no)?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
										<small>Expiration Date</small><br>
										<span class="font-20 col-green"><?=date('M d, Y', strtotime(html_escape($i->expiration_date)))?></span>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
										<small>Status</small><br>
										<span class="font-20 col-green"><?=html_escape($i->status)?></span>
									</div>
								</div>
                            </div>
							<br>
							<div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable" id = "dt-prod-var">
                                    <thead>
                                        <tr class="col-green">
                                            <th>Invoice No</th>
											<th>Date</th>
											<th>Sold From</th>
											<th>Gained Reward Points</th>	
											<th>Used Reward Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($card_transactions as $c):?>
                                        <tr>
                                            <td><?=html_escape($c->invoice_no)?></td>
											 <td><?=html_escape(date('M d, Y',strtotime($c->date)))?></td>
											<td><?=html_escape($c->sold_from)?></td>
											<td><?=html_escape($c->gained_reward_pts)?></td>
											<td><?=html_escape($c->used_reward_pts)?></td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# PRODUCTS -->

		<?php endforeach;?>
        </div>		
    </section>