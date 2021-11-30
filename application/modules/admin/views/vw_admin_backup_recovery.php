
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ADMIN TOOLS
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;">Admin Tools</li>
							<li style="background-color:transparent!important;" class="active">Backup & Recovery</li>
						</ol>
					</small>
				</h2>
            </div>

            <div class="row clearfix">
				<!-- BACKUP -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
                            <h2>BACKUP DATABASE</h2>
							<small>Download and have a backup file of the current database into your computer.</small>
                        </div>
                        <div class="body">
                            <p><b>Database Name: </b>db_goodbuy</p>
							<p><b>No. of Tables: </b><?php echo $db_ctr; ?></p>
							<a href="<?php echo base_url('admin/backup_db');?>" class="btn bg-green btn-block waves-effect">
                                <i class="material-icons">file_download</i>
                                <span>BACKUP</span>
                            </a>
                        </div>
                    </div>
                </div>
				<!-- #END# BACKUP -->
				
				<!-- RESTORE -->
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
                            <h2>RESTORE DATABASE</h2>
							<small>Restore the database through the backup file.</small>
                        </div>
                        <div class="body">
                            <p><b>Note:</b> File must be in <span class="col-green">.sql</span> format only.</p>
							<form action="<?php echo base_url('admin/restore_db');?>"  method="post" enctype="multipart/form-data">
                                <input type="file" name="backup-file" required accept=".sql" />
							<br>
							<button type="submit" class="btn btn-block bg-green waves-effect">
                                <span>RESTORE</span>
                            </button>
							</form>
                        </div>
                    </div>
                </div>
				<!-- #END# RESTORE-->
            </div>
        </div>
    </section>