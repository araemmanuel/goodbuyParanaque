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
									<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/banner');?>">Announcement Banner</a></li>
									<li style="background-color:transparent!important;" class="active">Edit Banner</li>
								</ol>
							</small>
						</h2>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="align-right">
						<a  class="btn btn-xs bg-green waves-effect col-white"  data-title="Are you sure you want to show all variants of this product online?" href="" style="text-decoration: none;" >
							<i class="material-icons">visibility</i> <span>SHOW ONLINE</span>
						</a>
						<a class="btn btn-xs bg-light-green waves-effect col-white"  data-title="Are you sure you want to hide all variants of this product online?" href="" style="text-decoration: none;" >
							<i class="material-icons">visibility_off</i> <span>HIDE ONLINE</span>
						</a>
					</div>
				</div>		
			</div>
		
            <div class="row clearfix">
				<!-- ANNOUNCEMENT -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
                            <h2>EDIT ANNOUNCEMENT BANNER</h2>
                        </div>
						
                        <div class="body">
							<?php foreach($banner as $b) : ?>
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<?php if (!$b->img_file_path): ?>
										<img src="<?php echo base_url('assets/images/no-photo.jpg');?>" width="100%" height="80%">
									<?php else: ?>
										<img src="<?php echo base_url($b->img_file_path);?>" width="100%" height="80%">
									<?php endif;?> 
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<form action="<?php echo base_url('admin/banner_edit');?>" method="post" enctype="multipart/form-data">
										<br><br>
										<input type="hidden" value="<?=html_escape($b->ban_id)?>" name="banner-id"  />
										<div>
											<input type="file" name="banner-img"  />
											<div class="validation-errors"><?php echo form_error('banner-img'); ?></div>
										</div>
										<br><br>
										<div>
											<div class="form-group form-float">
											<div class="form-line success">
												<input type="text" name="banner-name" value="<?=html_escape($b->name)?>" class="form-control" required />
												<label class="form-label">Banner Name</label>
											</div>
											<div class="validation-errors"><?php echo form_error('banner-name'); ?></div>
											</div>
										</div>
										<div>
											<input type="submit" class="btn bg-green waves-effect" value="EDIT BANNER" />
										</div>
									</form>
								</div>
							</div>
							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
				<!-- #END# ANNOUNCEMENT -->
            </div>
        </div>
    </section>