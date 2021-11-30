<button type="button" class="btn bg-green waves-effect float-button visible-xs" id="verify_my_checkout" data-toggle="modal" data-target="#defaultModalFilter"><i class="material-icons">filter_list</i></button>
<section class="content">
		<div class="owl-banner" id="owl_banner">
			<?php 
				foreach($banners as $banner)
				{
					echo '<div class="sale-banners" style="background:url('.base_url().$banner->img_file_path.') !important; background-position: center !important;"></div>';
				}
			?>
		</div>
	</section>
	
<section class="content">
		 <div class="modal fade" id="defaultModalFilter" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Filter</h4>
                        </div>
                        <div class="modal-body">
                           <div class="card profile_view_card">
                            <div class="row">
                                <div class="col-lg-2 col-sm-4 col-xs-12 col-md-2">
									
                                    <!-- Insert anything here for the content-->
                                    <div class="header">
                                        <h5>
                                            Categories
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <ul class="subcategory-list" id="subcategory-list-subcateg">
												<?php
													foreach($categ as $cat)
													{?>
														<a href="<?php echo base_url('customer/shop_categ/').$cat->cat_name ;?> "><li><?php echo $cat->cat_name; ?></li></a>
													<?php }
												?>
                                    </div>
                                    <div class="header">
                                        <h5>
                                            Filter by Price
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <div id="nouislider_range_example"></div>
                                        <div class="m-t-20 font-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <b>Price From: </b><span class="js-nouislider-value-from"></span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <b>Price To: </b><span class="js-nouislider-value-to"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header">
                                        <h5>
                                            Brands
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <ul class="subcategory-list" id="checkbox-list-brands">
											<?php
												foreach($brand as $br)
												{?>
													<li><input type="checkbox" id="md_checkbox_<?php echo $br->brand;?>_modal" class="filled-in chk-col-green" value="<?php echo $br->brand; ?>"><label for="md_checkbox_<?php echo $br->brand; ?>_modal"><?php echo $br->brand; ?></label></li>
												<?php }
											?>
                                        </ul>
                                    </div>

                                </div>
							</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        <div class="bg-white">
            <div class="profile_view_holder">
                <div class="row">
                    <div class="col-lg-12 profile_view">
                        <div class="card profile_view_card">
                            <div class="row">
                                <div class="col-lg-2 col-sm-4 hidden-xs col-md-2">
									
                                    <!-- Insert anything here for the content-->
                                    <div class="header">
                                        <h5>
                                            Categories
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <ul class="subcategory-list" id="subcategory-list-subcateg">
												<?php
													foreach($categ as $cat)
													{?>
														<a href="<?php echo base_url('customer/shop_categ/').$cat->cat_name ;?> "><li><?php echo $cat->cat_name; ?></li></a>
													<?php }
												?>
                                    </div>
                                    <div class="header">
                                        <h5>
                                            Filter by Price
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <div id="nouislider_range_example_modal"></div>
                                        <div class="m-t-20 font-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <b>Price From: </b><span class="js-nouislider-value-from"></span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <b>Price To: </b><span class="js-nouislider-value-to"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header">
                                        <h5>
                                            Brands
                                        </h5>
                                    </div>
                                    <div class="body">
                                        <br/>
                                        <ul class="subcategory-list" id="checkbox-list-brands">
											<?php
												foreach($brand as $br)
												{?>
													<li><input type="checkbox" id="md_checkbox_<?php echo $br->brand;?>" class="filled-in chk-col-green" value="<?php echo $br->brand; ?>"><label for="md_checkbox_<?php echo $br->brand; ?>"><?php echo $br->brand; ?></label></li>
												<?php }
											?>
                                        </ul>
                                    </div>

                                </div>
								
								<div class="col-lg-10 col-sm-8 col-xs-12 col-md-10 col-xs-12-home">
									<div class="row">
										<div class="col-sm-12">
											<div class="header">
												POPULAR ITEMS
											</div>
											<br/>
											<div class="row">
												<?php
													$counter = 1;
													foreach($popular_items as $pop_items)
													{
														$out = strlen($pop_items->name ) > 17 ? substr($pop_items->name ,0,17)."..." : $pop_items->name;
														if($pop_items->discount_percent == 0 || $pop_items->discount_percent == "0")
														{
															echo '<div class="col-lg-3 col-xs-6 col-md-4 items_filter" data-price="'.$pop_items->selling_price .'" data-brand="'.$pop_items->brand.'" data-is-sale="false"><div class="card card-product"><span class="label-count bg-green" id="cart-list-span">#'.$counter .' Popular Item</span><div class="body"><a href="'.base_url('customer/view_product/').$pop_items->sku .'" class="product_link"><img alt="'.base_url('assets/images/no-photo.jpg').'" onerror="this.onerror=null;this.src=this.alt;" src="'.base_url($pop_items->img_file_path).'"><p>'.$out .'</p><h4>₱'.$pop_items->selling_price .'</h4><span class="font-line-through font-12 col-white">'.$pop_items->selling_price .'</span><h6>Stock Available : '.$pop_items->quantity .'</h6></a></div></div></div>';
														}
														else
														{
															$new_price = $pop_items->selling_price - ($pop_items->selling_price * ($pop_items->discount_percent / 100));
															echo '<div class="col-lg-3 col-lg-3 col-xs-6 col-md-4 items_filter" data-price="'.$pop_items->selling_price .'" data-brand="'.$pop_items->brand.'" data-is-sale="false"><div class="card card-product"><span class="label-count bg-green" id="cart-list-span">#'.$counter .' Popular Item</span><span class="label-count second-line" id="cart-list-span">'.$pop_items->discount_percent .' % OFF</span><div class="body"><a href="'.base_url('customer/view_product/').$pop_items->sku .'" class="product_link"><img alt="'.base_url('assets/images/no-photo.jpg').'" onerror="this.onerror=null;this.src=this.alt;" src="'.base_url().$pop_items->img_file_path .'"><p>'.$out .'</p><h4>₱'.$new_price .'</h4><span class="font-line-through font-12 col-red">'.$pop_items->selling_price .'</span><h6>Stock Available : '.$pop_items->quantity .'</h6></a></div></div></div>';
														}
														$counter += 1;
													}
												?>
											</div>
										</div>
									
									<?php 
										foreach($categ as $cat)
										{
											echo '
												<div class="col-sm-12">
													<div class="header">
														<h4>'. $cat->cat_name . '</h4>
														<a href="'.base_url('customer/shop_categ/').$cat->cat_name.'">
															<button type="button" class="btn right bg-green waves-effect" id="verify_my_checkout" style="margin-top: -35px !important;">SHOW ALL</button>
														</a>
													</div>
													<br/>
													<div class="row">';
											$prod_categ = [];
											
											foreach($prod_per_categ as $to_be_filtered)
											{
												if($to_be_filtered->cat_name == $cat->cat_name)
												{
													if(count($prod_categ) < 20)
													{
														array_push($prod_categ,$to_be_filtered);
														
													}
												}
													
											}
											$dates = [];
											foreach ($prod_categ as $key=> $row) {
												// replace 0 with the field's index/key
												$dates[$key]  = $row->selling_price;
											}

											array_multisort($dates, SORT_ASC, $prod_categ);
											foreach($prod_categ as $p_c)
											{
												
												if($p_c->discount_percent == 0 || $p_c->discount_percent == "0")
													{
														$out = strlen($p_c->name ) > 17 ? substr($p_c->name ,0,17)."..." : $p_c->name;
														echo '<div class="col-lg-3 col-lg-3 col-xs-6 col-md-4 items_filter" data-price="'.$p_c->selling_price .'" data-brand="'.$p_c->brand .'" data-is-sale="false"><div class="card card-product"><div class="body"><a href="'.base_url('customer/view_product/').$p_c->sku .'" class="product_link"><img alt="'.base_url('assets/images/no-photo.jpg').'" onerror="this.onerror=null;this.src=this.alt;" src="'.base_url().$p_c->img_file_path .'"><p>'.$out .'</p><h4>₱'.$p_c->selling_price .'</h4><span class="font-line-through font-12 col-white">'.$p_c->selling_price .'</span><h6>Stock Available : '.$p_c->quantity .'</h6></a></div></div></div>';
													}
													else
													{
														$out = strlen($p_c->name ) > 17 ? substr($p_c->name ,0,17)."..." : $p_c->name;
														$n_price = $p_c->selling_price - ($p_c->selling_price * ($p_c->discount_percent / 100));
														$new_price = number_format((float)$n_price, 2, '.', '');
														echo '<div class="col-lg-3 col-lg-3 col-xs-6 col-md-4 items_filter" data-price="'.$n_price .'" data-brand="'.$p_c->brand.'" data-is-sale="false"><div class="card card-product"><span class="label-count" id="cart-list-span">'.$p_c->discount_percent .' % OFF</span><div class="body"><a href="'.base_url('customer/view_product/').$p_c->sku .'" class="product_link"><img alt="'.base_url('assets/images/no-photo.jpg').'" onerror="this.onerror=null;this.src=this.alt;" src="'.base_url().$p_c->img_file_path .'"><p>'.$out .'</p><h4>₱'.$new_price .'</h4><span class="font-line-through font-12 col-red">₱ '.$p_c->selling_price .'</span><h6>Stock Available : '.$p_c->quantity .'</h6></a></div></div></div>';
													}
											}
													echo '</div>
												</div>';
										}
									?>
								</div>
                                
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>