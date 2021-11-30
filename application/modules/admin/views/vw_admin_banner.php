    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="block-header">
						<h2>ADMIN TOOLS
							<small>
								<ol class="breadcrumb">
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
									<li style="background-color:transparent!important;">Admin Tools</li>
									<li style="background-color:transparent!important;" class="active">Announcement Banner</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="align-right">
						<button type="button" id="show-banner" class="btn btn-xs bg-green  waves-effect col-white" style="text-decoration: none;" >
							<i class="material-icons">visibility</i> <span>SHOW ONLINE</span>
						</button>
						<button type="button" id="hide-banner" class="btn btn-xs bg-light-green waves-effect col-white"  style="text-decoration: none;" >
							<i class="material-icons">visibility_off</i> <span>HIDE ONLINE</span>
						</button>
					</div>
				</div>		
			</div>
			
            <div class="row clearfix">
				<!-- ANNOUNCEMENT -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
                            <h2>ONLINE SHOPPING ANNOUNCEMENT BANNER</h2>
                        </div>
                        <div class="body">
							<div class="row clearfix">
								
								<form action="<?php echo base_url('admin/banner_add');?>" method="post" enctype="multipart/form-data">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<input type="file" name="banner-img"  required />
										<div class="validation-errors"><?php echo form_error('banner-img'); ?></div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group form-float">
										<div class="form-line success">
											<input type="text" name="banner-name" class="form-control" required />
											<label class="form-label">Banner Name</label>
										</div>
										<div class="validation-errors"><?php echo form_error('banner-name'); ?></div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<input type="submit" class="btn bg-green waves-effect" value="ADD BANNER" />
									</div>
								</form>
							</div>
							<div class="table-responsive">
                                <form  id = "form-banner-online" method="POST">
									
								<table class="table table-condensed table-hover" id="dt-banner" >
                                   <thead>
                                        <tr class="col-green">
											<th><input type="checkbox" id="chk-banner-head" class="chk-col-green" name="chk-banner[]" onchange="checkAll(this)" /><label for="chk-banner-head"></label></th>		
											<th>Banner Name</th>
											<th>Image</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($banners as $b):?>
										<tr>
											<td><input type="checkbox" id="chk-banner-body" name="chk-banner[]" value = "<?=html_escape($b->ban_id)?>" class = "chk-col-green chk-banner"/><label for="chk-banner-body"></label></td>				
											<td><?=html_escape($b->name)?></td>
											<td>
												<div class="order-column">
													<div class="row ">
														<div class="col-lg-12">
															<div class="product-pic-small-order-view">
																<?php if (!$b->img_file_path): ?>
																	<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" width="350" height="200">
																<?php else: ?>
																	<img src="<?php echo base_url($b->img_file_path);?>" width="350" height="200">
																<?php endif;?> 
															</div>
														</div>
													</div>
												</div>							
											</td>
											<td>
												<a href="<?php echo base_url("admin/banner_edit_form/$b->ban_id");?>" class="btn btn-xs bg-green waves-effect">Edit</a>
												<button type="button" class="btn btn-xs waves-effect confirm"  data-title="Are you sure you want to delete this banner?" data-msg="This action cannot be undone." data-url="<?php echo base_url("admin/banner_del/$b->ban_id");?>">Delete</button>
												<?php if($b->is_customer_viewable == 0): ?>
													<a href="<?php echo base_url("admin/banner_show_online/$b->ban_id");?>" class="btn btn-xs bg-light-green waves-effect">Show Online</a>
												<?php else : ?>
													<a href="<?php echo base_url("admin/banner_hide_online/$b->ban_id");?>" class="btn btn-xs bg-amber waves-effect">Hide Online</a>
												<?php endif; ?>
												
											</td>
										</tr>
										<?php endforeach;?>				
                                    </tbody>
                                </table>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- #END# ANNOUNCEMENT -->
            </div>
        </div>
		<!-- EDIT BANNER MODAL -->
			<div class="modal fade" id="modal-edit-banner" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/banner_edit');?>" id="form-banner" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Banner</h1>
                        </div>
						
                        <div class="modal-body">
						<input type="hidden" name="modal-banner-id" id = "modal-ban-id" class="form-control"/>
									
							<div class="row clearfix">	
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<input type="file" name="modal-banner-img"  required />
									<div class="validation-errors" id="img_file_path_error"></div>
								</div>
							</div>
							<br>
							<div class="row clearfix">									
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label">Banner Name</small>
									<div class="form-line success">
										<input type="text" name="modal-banner-name" id = "modal-banner-name" class="form-control" required />
									
									</div>
									<div class="validation-errors" id="name_error"></div>
									</div>
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="btn-edit-banner" class="btn bg-green waves-effect">
								<i class="material-icons">check</i> 
								<span>SAVE</span>
							</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
					</form>
                </div>
            </div>
		<!-- #END# -->
		
    </section>