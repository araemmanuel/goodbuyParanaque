<section class="content">
		<div class="owl-banner" id="owl_banner">
			<?php 
				foreach($banners as $banner)
				{
					echo '<div class="sale-banners" style="background:url('.base_url().$banner->img_file_path.') !important; background-position: center !important;"></div>';
				}
			?>
		</div>
		
		<div class="row main-body-products" id="new_items_categ">
			<div class="col-lg-12 product-row">
				<h3>NEW ITEMS THIS MONTH</h3>
				<h1><?php echo strtoupper(date('M')).' '.date('Y');?></h1>
					<div class="row">
						<div class="owl-carousel owl-theme" id="product_view_holder">
						</div>
					</div>
			</div>
		</div>
	</section>