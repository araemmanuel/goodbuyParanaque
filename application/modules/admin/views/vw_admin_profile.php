    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>PROFILE</h2>
            </div>
			
			<!-- Profile -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT PROFILE</h2>
                        </div>
                        <div class="body">
						  <div class="row clearfix">
						  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data" acceptedFiles=".jpg" />
                                <div class="dz-message">
                                    <div class="drag-icon-cph">
                                        <i class="material-icons">wallpaper</i>
                                    </div>
                                    <h3>Upload Photo</h3> or drag it here.
                                </div>
                                <div class="fallback">
                                    <input name="image" type="image"/>
                                </div>
                            </form>
						  </div>
						  <br>
						  <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <form  method="POST">
                                
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" class="form-control" name="firstname" value="Anne" required>
                                        <label class="form-label">First Name</label>
                                    </div>
                                </div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" class="form-control" name="lastname" value="Llanillo" required>
                                        <label class="form-label">Last Name</label>
                                    </div>
                                </div>
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="email" class="form-control" name="email" value="annellanillo@yahoo.com" required>
                                        <label class="form-label">Email</label>
                                    </div>
                                </div>
								</div>
							</div>
							<div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="text" class="form-control" name="username" value="anne" required>
                                        <label class="form-label">Username</label>
                                    </div>
                                </div>
								</div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group form-float">
                                    <div class="form-line success">
                                        <input type="password" class="form-control" name="password" value="anne" required>
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
								</div>
							</div>
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-right">
									<button class="btn bg-green waves-effect" type="submit">
									<i class="material-icons">check</i> 
										<span>SAVE</span>
									</button>
									&nbsp;
									<a href = "<?php echo base_url('admin');?>" class="btn waves-effect" type="submit"><i class="material-icons">close</i>
										<span>CANCEL</span>
									</a>
								</div>
							</div>
							
                            </form>
						  </div>
						  </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Profile -->
        </div>
    </section>