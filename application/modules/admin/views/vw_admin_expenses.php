	<section class="content">
	<style>
/* The Modal (background) */
#receiptModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
#receipt-modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Add Animation */
#receipt-modal-content{  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  #receipt-modal-content {
    width: 100%;
  }
}
</style>
        <div class="container-fluid">
            <div class="block-header">
                <h2>EXPENSES
					<small>
						<ol class="breadcrumb">
							<li style="background-color:transparent!important;"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
							<li style="background-color:transparent!important;" class="active">Expenses</li>
						</ol>
					</small>
				</h2>
            </div>

            <!-- DATA TABLE -->
            <div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EXPENSES</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
							<form id="form-exp" action ="<?php echo base_url('admin/expenses/add');?>" method="POST" enctype="multipart/form-data">
								<?php date_default_timezone_set('Asia/Manila');?>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Date</small>
										<div class="form-line success">				
											<input type="date" name="date"  value = "<?php echo date("Y-m-d");?>" class="form-control">
										</div>
										<div class = 'validation-errors' id="date_error"><?php echo form_error('date');?></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Description</small>
										<div class="form-line success">
											<input type="text" name="desc" class="form-control">
										</div>
										<div class = 'validation-errors' id="desc_error"><?php echo form_error('desc');?></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Amount</small>
										<div class="form-line success">
											<input type="number" name="amt" class="form-control" min="0.00" step="0.01">
										</div>
										<div class = 'validation-errors' id="amt_error"><?php echo form_error('amt');?></div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<div class="form-group form-float">
										<small class="form-label col-grey">Receipt</small>
											<input type="file" name="receipt" class="form-control">
										<div class = 'validation-errors' id="receipt_error"><?php echo form_error('receipt');?></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<br>
									<button id="btn-exp" class="btn btn-block bg-green waves-effect" type="button">
										<i class="material-icons">add</i> 
										<span>ADD</span>
									</button>
								</div>
							</form>
							</div>
						
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="dt-expenses">
                                    <thead>
                                        <tr class="col-green">
											<th></th>
                                            <th>Date</th>
                                            <th>Description</th>
											<th>Amount</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($expenses as $e): ?>
                                        <tr>
											<td><?=html_escape($e->exp_id)?></td>
                                            <td><?=html_escape(date('M. d, Y', strtotime($e->exp_date)))?></td>
                                            <td><?=html_escape($e->exp_desc)?></td>
                                            <td><?=html_escape('₱ ' . $e->exp_amt)?></td>
											<td>
												<button type="button" class="btn btn-xs bg-green waves-effect open-edit-expense" data-exp-id="<?=$e->exp_id?>" data-href="<?php echo base_url('admin/expenses/edit');?>" data-toggle="modal" data-target="#modal-edit-expense">Edit</button>
												<button type="button" class="btn btn-xs bg-red waves-effect confirm"  data-title="Are you sure you want to delete this expense?" data-url="<?php echo base_url('admin/expenses/del/'.$e->exp_id);?>">Delete</button>
												<?php if($e->exp_receipt != "") { ?>
													<button type="button" name="viewbtn" class="btn btn-xs bg-blue waves-effect" onclick="testing()" chatter="Rezin" imgPath="<?= base_url($e->exp_receipt); ?>" alter="nice one">View</button>
												<?php } ?>
											</td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# DATA TABLE -->
			
			<!-- EDIT EXPENSE MODAL -->
			<div class="modal fade" id="modal-edit-expense" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
					<form id="form-edit-expense">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Expense</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-float">
								<small class="form-label col-grey">Date</small>
								<div class="form-line success">
									<input type="hidden" name="modal-exp_id" class="form-control" />
									<input type="date" name="modal-exp_date" class="form-control" />
								</div>
								<div class="validation-errors" id="m_exp_date_error"><?php echo form_error('m_exp_date');?></div>
							</div>
							<div class="form-group form-float">
								<small class="form-label col-grey">Description</small>
								<div class="form-line success">
									<input type="text" name="modal-exp_desc" class="form-control" />
								</div>
								<div class="validation-errors" id="m_exp_desc_error"><?php echo form_error('m_exp_desc');?></div>
							</div>
							<div class="form-group form-float">
								<small class="form-label col-grey">Amount</small>
								<div class="form-line success">
									<input type="number" name="modal-exp_amt" class="form-control" min="0.00" step="0.01"/>
								</div>
								<div class="validation-errors" id="m_exp_amt_error"><?php echo form_error('m_exp_amt');?></div>
							</div>
								<div class="form-group form-float">
									<small class="form-label col-grey">Receipt</small>
										<input type="file" name="modal-exp_receipt" class="form-control">
									<div class = 'validation-errors' id="m_exp_receipt_error"><?php echo form_error('m_exp_receipt');?></div>
								</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-edit-expense" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
					</form>
                    </div>
                </div>
            </div>
			<!-- #END# -->
        </div>
		<!-- Image Modal -->
		<div id="receiptModal" class="modal">
		  <span class="close">&times;</span>
		  <img class="receipt-modal-content" id="img01">
		</div>

		<script>
			// Get the modal								
			var modal = document.getElementById("receiptModal");
		var getImageName = function() {
			document.onclick = function(e) {
		    	if (e.target.name == 'viewbtn') {
					// Get the image and insert it inside the modal - use its "alt" text as a caption
					var img = document.getElementById("myImg");
					var modalImg = document.getElementById("img01");
					var captionText = document.getElementById("caption");
						modal.style.display = "block";
						modalImg.src = e.target.getAttribute("imgPath");
						captionText.innerHTML = e.target.getAttribute("alter");
		    	}
			}
		}
		getImageName();

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() { 
			  modal.style.display = "none";
			}
		</script>
    </section>