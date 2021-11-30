
    <section class="content">
        <div class="container-fluid">
			<div class="block-header">
                <h2>ADMIN TOOLS
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
		<!--	<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/test');?>">test</a></li>-->
							<li style="background-color:transparent!important;">Admin Tools</li>
							<li style="background-color:transparent!important;" class="active">Activity Log</li>
						</ol>
					</small>
				</h2>
            </div>
			
            <!-- DATA TABLE -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>ACTIVITY LOG</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id = "dt-datalog">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
                                            <th>Timestamp</th>
											<th>Activity</th>
											<th>Username</th>
											<th>Role</th>                       
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->			
        </div>
    </section>
