<section class="content">
        <div class="container-fluid">
			<div class="block-header">
				<h2>INVENTORY
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Inventory</li>
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
							<li style="background-color:transparent!important;" class="active">Print Price Tags</li>
						</ol>
					</small>
				</h2>
			</div>
		
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>PRINT PRICE TAGS</h2>
                        </div>
                        <div class="body">
							<form id='form-price-tag' action = "<?php echo base_url('admin/inventory/batch_price_tags');?>" method="POST">
							<div class="row clearfix">
								<?php date_default_timezone_set('Asia/Manila');?>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id = "tag-date-from" name="date-from" class="form-control" value = "<?php if(isset($date_from)) echo $date_from;?>" placeholder="Choose Date From...">
										</div>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id = "tag-date-to" name="date-to" class="form-control" value = "<?php if(isset($date_to)) echo $date_to;?>" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group form-float">
											<small class="form-label col-grey">Tag Type</small>
											<div class="form-group form-float">
											<select class="form-control show-tick" name='tag-type' id='tag-type'> 
											<?php 
											$tag_types = array('Small','Single', 'Large');?>
											<?php for($t=0;$t<count($tag_types);$t++): ?>
												<option><?php echo $tag_types[$t];?></option>
											<?php endfor;?>
											</select>
										</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<br>
										<button type="submit" id="btn-print-batch" class="open-print-window btn btn-block bg-green waves-effect">
											<i class="material-icons">print</i> <span>PRINT</span>
										</button>		
										<small><b>NOTE: </b>Check specific products that you want to print otherwise the system will assume that it's all included.</small>
																		
									</div>
								
							</div>
							
									</form>
									<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
									<form id='form-selected-tag' action = "<?php echo base_url("admin/inventory/price_tags/$date_from/$date_to/$tag_type");?>" method="POST">
							
										<table class="table table-condensed table-hover js-basic-example dataTable">
											<thead>
												<tr class="col-green">
													<th><input type="checkbox" id = "chkb-all" name="chk-batch[]" onchange="checkAll(this)" /><label for="chkb-all"></label></th>
													<th>Product Code</th>
													<th>Name</th>
													<th>Attributes</th>
													<th>Quantity</th>
													<!--<th>Action</th>-->
												</tr>
											</thead>
											<tbody>
												<?php foreach($products as $p):?>
												<tr>
													<td><input type="checkbox" id="chkb-<?=html_escape($p->sku)?>" name="chk-batch[]" value = "<?=html_escape($p->sku)?>" class = "chk-batch-print"/><label for="chkb-<?=html_escape($p->sku)?>"></label></td>
													<td><?=html_escape($p->sku)?></td>
													<td><?=html_escape(ucfirst($p->name))?></td>
													<td><?=html_escape($p->options)?></td>
													<td><?=html_escape($p->quantity)?></td>
													<!--<td><input type="number" style="border: none;border-color: transparent;" name = "<?=html_escape($p->sku)?>" value="<?=html_escape($p->quantity)?>"></td>-->
												</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</form>
									</div>
									</div>
									</div>	
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
    </section>