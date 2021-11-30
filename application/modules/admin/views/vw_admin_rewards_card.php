	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>REWARDS CARD 
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;" class="active">Rewards Card</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<div class="align-right">
						<a href="<?php echo base_url('admin/rewards_card/cust_add_form');?>" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" type="button">
							<i class="material-icons">add</i> <span>ADD CARD HOLDER</span>
						</a>
						<button type="button" id="btn-batch-card" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" >
							<i class="material-icons">print</i> <span>BATCH PRINT REWARD CARDS</span>
						</button>
						<div class="validation-errors" id="batch-reward-card"></div>
					</div>
					
				</div>			
			</div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>LIST OF REWARD CARD HOLDERS</h2>
					    </div>
                        <div class="body">
						<div class="row clearfix">
							<form action="<?php echo base_url('admin/rewards_card/cust_app_form');?>" id="form-app-print" method = "POST">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<div class="form-line success">
												<input type="text" name="qty" class="form-control" value = "1" required />
												<label class="form-label">No. of Forms</label>
											</div>
											<div class="validation-errors"><?php echo form_error('qty');  ?></div>
										</div>
								</div>
								<button type="submit" class="btn btn-xs bg-green waves-effect" style="text-decoration:none;" >
									<i class="material-icons">print</i> <span>PRINT APPLICATION FORMS</span>
								</button>
							</form>							
						</div>
						
                            <div class="table-responsive">
							<form action="<?php echo base_url('admin/rewards_card/batch_print');?>" id="form-card-batch-print" method = "POST">
							
							<input type="checkbox" id="chk-card-head" class="chk-col-green" name="chk-reward-card[]" onchange="checkAll(this)" /> <label for="chk-card-head">Check All</label>
                                <table class="table table-condensed table-hover" id ="dt-card-holders">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
											<th>Card No.</th>
											<th>Membership ID</th>
											<th>Customer Name</th>
											<th>Expiration Date</th>
											<th>Reward Points</th>
											<th>Status</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php //foreach($card_holders as $c):?>
                                        <!--<tr>
											<td><input type="checkbox" name="chk-reward-card[]" /></td>	
											<td><?=html_escape($c->card_no)?></td>
											<td><?=html_escape($c->membership_id)?></td>
											<td><?=html_escape($c->name)?></td>
											<td><?=date('M d, Y', strtotime(html_escape($c->expiration_date)))?></td>
											<td><?=html_escape($c->reward_points)?></td>
											<td><?=html_escape($c->status)?></td>	
											<td>
												<a href="<?php echo base_url('admin/rewards_card/card_details/'.$c->card_no);?>" class="btn btn-xs bg-default waves-effect">View</a>
												<button type="button" class="btn btn-xs bg-green waves-effect" >Edit</button>
												<button type="button" class="btn btn-xs bg-default waves-effect" >Delete</button>
												<button type="button" class="btn btn-xs bg-green waves-effect open-print-window" data-href="<?php echo base_url('admin/rewards_card/print_reward_card');?>">Print Card</button>
												<a class="btn btn-xs bg-default waves-effect" >Set as printed</a>		
											</td>											
                                        </tr>-->
										<?php //endforeach;?>

                                    </tbody>
                                </table>
							</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->

		</div>
	<section>