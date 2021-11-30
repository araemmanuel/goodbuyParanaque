<style>
.boxed {
	width: 300px;
	height: 210px;
	border: 3px solid green;
	padding: 10px;
	position: relative;
	top: 0;
	left: 0;
	display: inline-block;
	bottom: 0;
}
.logo-img{
	max-height:65%; 
	max-width:65%;
	margin-top: 20px;
	margin-right: 130px !important;
}

.qr-img2{
	width:  120px;
	height: 120px;
}

.qr{
	position: absolute;
	bottom: 50px;
	right: 18px;
	width:  90;
	height: 90;
}
.caption {
  position: relative;
  width: 100%;
  float: right;
  overflow: hidden;
  font-size: 26px;
  bottom: -5spx;
  margin-right: -70px;
}
.code{
  position: absolute;
  width: 75px;
  float: left;
  overflow:hidden;
  font-size: 16px;
  bottom: 33px;
  margin-left: 0;

}
.name{
  position: absolute;
  width: 100px;
  float: left;
  overflow:hidden;
  font-size: 12px;
  margin-top: 158px;
  margin-left: 10px;
  text-align: left;
}
.date {
  position: absolute;
  width: 75px;
  float: right;
  overflow:hidden;
  font-size: 14px;
  top: 15px;
  right: 23px;
}
</style>
	<section class="content">
        <div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>PRODUCT ADDED SUCCESSFULLY!
							<small>
								<ol class="breadcrumb">
									<?php if($caller == 'prod_add_form'): ?>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
										<li style="background-color:transparent!important;">Inventory</li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/prod_add_form');?>">Add Product</a></li>
										<li style="background-color:transparent!important;" class="active">Print Price Tag</li>
									<?php elseif($caller == 'products'): ?>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
										<li style="background-color:transparent!important;">Inventory</li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
										<li style="background-color:transparent!important;" class="active">Print Price Tag</li>
									<?php elseif($caller == 'var_add_form'): ?>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
										<li style="background-color:transparent!important;">Inventory</li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/products');?>">Products</a></li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/product_variants/'.$prod_id);?>">Product Variants</a></li>
										<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/inventory/variant_add_form/'.$prod_id);?>">Add Variant</a></li>
										<li style="background-color:transparent!important;" class="active">Print Price Tag</li>
									<?php endif;?>
								</ol>
							</small>
						</h2>
					</div>
				</div>
			</div>

            <!-- PRODUCTS -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">	
							<!--
							<div class="row clearfix">
								<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
									<button onclick="window.location.href='<?php echo base_url('admin/inventory/prod_add_form');?>'" class="btn bg-default" >
										<i class="material-icons">arrow_back</i>
										<span>BACK TO PRODUCT ADD FORM</span>
									</button>
								</div>
							</div>
							-->
							<div class="row clearfix">
								<div class="col-lg-4 col-md-4 col-sm-0 col-xs-0"></div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<div class="align-center">
									<?php $date = date('Y-m-d'); 
										foreach($product as $p)
										{								
											echo '<div class="boxed">';
											echo "<div class='date'>$date</div>";
											echo "<div class='code'>$p->sku</div>";
											echo "<div class='name'>$p->name</div>";
											
											echo "<img src='".base_url('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";
											
											echo "<div class = 'qr'>";
												$path = FCPATH.$large_qr_path;
												$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d');
												$params['data'] = 'Code = ' . $p->sku  . '   Name =  '. $p->name  . '   Price = ' . $p->selling_price;
												$params['level'] = 'L';
												$params['size'] = 30;
												$params['savename'] = $path.$filename;
												$this->ciqrcode->generate($params);		
											echo "<img src = '". base_url('assets/qr/large/'.$filename) ."' class = 'qr-img2'>";
											echo "</div><br>";
											echo "<div class='caption'>₱$p->selling_price</div></div>";
									
										}
									?>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-0 col-xs-0"></div>
							</div>
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="align-center" id ="add-result">
										<?php if(isset($sh_qty) && $sh_qty) : ?>
											<button type="button" class="btn btn-sm bg-green waves-effect open-print-window"  data-href="<?php echo base_url('admin/inventory/large_tags/'.$prod_id.'/'.$sku.'/add_result/'.$sh_qty);?>">Large Tags</button>
											<button type="button" class="btn btn-sm bg-light-green waves-effect open-print-window" data-href="<?php echo base_url('admin/inventory/small_tags/'.$prod_id.'/'.$sku.'/add_result/'.$sh_qty);?>" >Small Tags</button>											
											<button type="button" class="btn btn-sm bg-amber waves-effect open-print-window" data-href="<?php echo base_url('admin/inventory/single_tag/'.$prod_id.'/'.$sku.'/add_result/'.$sh_qty);?>" >Single Large Tag</button>
										<?php else : ?>
											<button type="button" class="btn btn-sm bg-green waves-effect open-print-window"  data-href="<?php echo base_url('admin/inventory/large_tags/'.$prod_id.'/'.$sku.'/add_result');?>">Large Tags</button>
											<button type="button" class="btn btn-sm bg-light-green waves-effect open-print-window" data-href="<?php echo base_url('admin/inventory/small_tags/'.$prod_id.'/'.$sku.'/add_result');?>" >Small Tags</button>											
											<button type="button" class="btn btn-sm bg-amber waves-effect open-print-window" data-href="<?php echo base_url('admin/inventory/single_tag/'.$prod_id.'/'.$sku.'/add_result');?>" >Single Large Tag</button>			
										<?php endif;?>
										
										<a href ="<?php echo base_url('admin/inventory/variant_add_form/'.$prod_id.'/prod_add_form');?>" class="btn btn-sm bg-deep-orange waves-effect" >Add Variant</a>				
									
									</div>
								</div>
							</div>	
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# PRODUCTS -->						
        </div>		
    </section>