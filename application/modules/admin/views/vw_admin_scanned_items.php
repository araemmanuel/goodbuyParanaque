    <section class="content">
        <div class="container-fluid">
		<form action ="<?php echo base_url('admin/inventory/reset_physical');?>" method="POST" >
								
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>INVENTORY
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;">Inventory</li>
									<li style="background-color:transparent!important;" class="active">Scanned Items</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="align-right">
						<button type="submit" class="btn bg-green waves-effect">Reset Physical Inventory</button>
					</div>
				</div>
			</div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>SCANNED ITEMS</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="col-green">
                                            <th><input type="checkbox" id="chk-tally-head" class="chk-col-green" name="chk-tally[]" onchange="checkAll(this)" /><label for="chk-tally-head"></label></th>		
											<th>Code</th>
                                            <th>Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Scanned Quantity</th>
											<th>Selling Price</th>
											<th>Date Scanned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($products as $p):?>
                                        <tr>
											<td><input type="checkbox" id="<?=html_escape($p->sku)?>" class="chk-col-green" name="chk-tally[]" value="<?=html_escape($p->sku)?>" /><label for="<?=html_escape($p->sku)?>"></label></td>				
                                            <td><?=html_escape($p->sku)?></td>
                                            <td><?=html_escape($p->name)?></td>
                                            <td><?=html_escape($p->options)?></td>
											<td><?=html_escape($p->quantity)?></td>
											<td><?=html_escape($p->scanned_qty)?></td>
                                            <td><?=html_escape($p->selling_price)?></td>
											<td><?=html_escape($p->date_scanned)?></td>
                                        </tr>
									<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
		</form>	
        </div>
    </section>