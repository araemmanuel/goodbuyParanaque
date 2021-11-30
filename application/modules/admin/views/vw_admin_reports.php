
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>GENERATE REPORT</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
							<!--<form action ="<?php echo base_url('admin/reports');?>" method="POST" >-->
								<!--<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
									<div class="form-group form-float">
										<div class="form-line success">
										<label class="form-label">Report Type</label>
										<br>
											<select name="rptType" id="rpt-type" name="rptType" class="show-tick selectpicker form-control" data-live-search="true">
											<option>- Please Select -</option>
											<option>Detailed</option>
											<option>Summary</option>
											<option>Tally</option>
											</select>
										</div>
									</div>
								</div>-->
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Report Name</small>
										<div class="form-line success">
											<select name="rptName" id = "rpt-name" class="show-tick selectpicker form-control" data-live-search="true">
												<option>Tally</option>
												<option>Detailed Purchase</option>
												<option>Detailed Profit</option>
												<option>Detailed Transactions</option>
												<option>Detailed Sales</option>
												<option>Detailed Inventory Items</option>
												<option>Detailed Expenses</option>
												<option>Detailed Transferred Items</option>
												<option>Summary Purchase</option>
												<option>Summary Sold Items</option>
												<option>Summary Inventory Items</option>
												<option>Summary Transferred Items</option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date From</small>
										<div class="form-line success">
											<input type="date" id = "date-from" name="dateFrom" class="form-control" placeholder="Choose Date From...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date To</small>
										<div class="form-line success">
											<input type="date" id = "date-to" name="dateTo" class="form-control" placeholder="Choose Date To...">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Search</small>
										<div class="form-line success">
											<input type="text" id = "report-search"   class="form-control" placeholder="Enter keywords...">
										</div>
									</div>
								</div>
								<br>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table class="table table-condensed table-hover js-basic-example dataTable" id ="dt-report">
                                    <thead id = 'th-report'>
                                       
                                    </thead>
                                    <tbody>
									<tr>
									</tr>
                                    </tbody>
                                </table>
								</div>
								<!--<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<button type ="button" id = "btn-rpt-search">
											<i class="material-icons">search</i>
										</button>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">						
										<br>
										<button type="submit" class="btn bg-green waves-effect">
											GENERATE
										</button>											
								</div>-->
									<!--<iframe src="<?php echo base_url('admin/reports/display/'.$report_name.'/'.$date_from.'/'.$date_to);?>" id="rpt-holder" style="width:100%;height:1200px;"></iframe>	-->					
								<!--</form>-->
								
								
								
							</div>			
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
    </section>