
<!-- EDIT -->
<button type="button" class="btn bg-green waves-effect open-edit-cat" data-cat-id = "<?=html_escape($c->cat_id)?>" data-toggle="modal" data-target="#modal-edit-cat">Edit</button>

<!-- DELETE -->
<button type="button" class="btn bg-default waves-effect confirm"  data-title="Are you sure you want to delete this item?" data-msg="This action cannot be undone." data-url="<?php echo base_url('admin///'.$p->prod_id);?>">Delete</button>

<!-- FORM -->											
<form action ="<?php echo base_url('admin/');?>" method="POST" id="form-edit-subcat">
</form>

			<!-- EDIT SUBCATEGORY MODAL -->
			<div class="modal fade" id="modal-edit-subcat" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
					<form action ="<?php echo base_url('admin/category/subcat_edit');?>" method="POST" id="form-edit-subcat">
                        <div class="modal-header">
                            <h1 class="modal-title">Edit Subcategory</h1>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<div>
									<select class="form-control selectpicker show-tick" name = "modal-categories" id="modal-categories" data-live-search="true">
									</select>
								</div>
								<br>
								<div class="form-line success">
									<input type="hidden" name="modal-subcat_id"/>
									<input type="text" name="modal-subcat_name" class="form-control" >
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-green waves-effect"><i class="material-icons">check</i> <span>SAVE</span></button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
					</form>
                </div>
            </div>
			<!-- #END# -->

<div class = 'validation-errors'><?php echo form_error('');?></div>

<?php
		//peso sign
		₱

		$pdf->SetFont('helvetica', '', 14);
		$this->input->post('');
		
		//CHECK VALIDATION 
		$this->form_validation->set_rules('attrib_val_name', 'Attribute Value', 'trim|required|callback_attrib_val_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->product_attributes();
		}
		else
		{		
			$data['opt_grp_id']= $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib_type'));
			$data['opt_name']= $this->remove_whitespace($this->input->post('attrib_val_name'));
			if($this->mdl_option->_insert($data))
				$this->session->set_flashdata('alert_msg','Attribute Value Added Successfully!');		
			redirect('admin/inventory/product_attributes');
		}

		
		//CALLBACK VALIDATION
		public function cat_check()
		{
			$id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib_type'));
			if ($this->mdl_option->get_by_type($attrib_type_id, $attrib_value))
			{
				$this->form_validation->set_message(__FUNCTION__, 'The {field} is required.');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}

		//EDIT
		$data['opt_grp_name'] = $this->input->post('modal-opt_grp_name');
		if($this->mdl_option_group->_update('opt_grp_id',$attrib_type_id, $data))
			$this->session->set_flashdata('alert_msg','Attribute Type Edited Successfully!');
		redirect('admin/inventory/product_attributes');

		//MODAL EDIT

		$this->form_validation->set_rules('modal-sku', 'Product Code', 'trim|required|callback_sku_check');
	
		if ($this->form_validation->run($this) == FALSE) 
		{		

			$data["discounted_price"] = form_error("modal-discounted_price");
			$data["error"] = true;	
							
		}
		else
		{	
			$this->process_edit();
			$data["success"]= true;
		}	
		print json_encode($data);
		
		//DELETE
		if($this->mdl_option_group->_delete('opt_grp_id', $id))
			$this->session->set_flashdata('alert_msg','Attribute Type Deleted Successfully!');
		if($this->mdl_option_group->can_delete($id))
		{	

		}
		else
		{
			$this->session->set_flashdata('alert_msg','Error: Subcategory cannot be deleted.');
		}
		redirect('admin/inventory/product_attributes');
	}	
		//JSON ENCODE
	public function get_category_names()
	{
		print json_encode($this->mdl_category->get_names());  
	}
	
	//Query bindings
	$sql = " ";
	$q = $this->db->query($sql, array($id));
	
	//reports
	$total = number_format($total, 2);
	$total = <<<EOD
			<tr>
				<th class="th2" colspan="6">GRAND TOTAL</th>
				<td>$total</td>
           </tr>
EOD;
	$tblContent =$tblContent.$total."</table>";
	font-size:12;
	 $date = date('M d, Y', strtotime($i->date));
	 $grand_total_ =  + $r->total_;
	
/*
	$inv_data['date'] = $this->input->post('sales-date');
	$invoice_no = $this->mdl_invoice->get_max_invoice_no($inv_data['date']);
	echo 'Date: ' . $inv_data['date'];
	echo '<br>';
	echo 'Invoice No: ' . $invoice_no;
	echo '<br>';
	$line_no = $this->mdl_invoice->get_line_no($invoice_no);
	echo 'Line no.: ' .  $line_no;
	echo '<br>';
	echo 'Product Total Price: '. $this->get_total_price('WSHO1', 100, 1);
	echo '<br>';
	echo 'Invoice of. Total Price: '. $this->mdl_invoice->get_total_invoice($invoice_no);
	
	//process qty test data
			$sku = 'WSHO1';
		$invoice_no = 9;
		$product_qty = $this->mdl_product->get_col_where('quantity', 'sku', $sku);
		$invoice_qty = $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku);
		echo 'Product qty: '. $product_qty;
		echo '<br>';
		echo 'Invoice qty: '. $invoice_qty;
		echo '<br>';
		echo 'Prod Qty if Add 3:' . $this->process_qty('add', $invoice_no, $sku, 3) . '              Invoice Qty:  '. $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku); // prod qty = 0, invoice qty = 3
		echo '<br>';
		echo 'Prod Qty  if Edit 2:' . $this->process_qty('edit', $invoice_no, $sku, 2) . '           Invoice Qty:  '. $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku); // prod qty = 1, invoice qty = 2
		echo '<br>';
		echo 'Prod Qty  if Delete 2:' . $this->process_qty('delete', $invoice_no, $sku, 2). '        Invoice Qty:  '. $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku);// prod qty = 3, invoice qty = 0
		echo '<br>';
		echo 'Results for add: prod qty = 0, invoice qty = 3';
		echo '<br>';
		echo 'Results for edit: prod qty = 1, invoice qty = 2';
		echo '<br>';
		echo 'Results for delete: prod qty = 3, invoice qty = 0';
		public function test()
	{
		/*
		modal-invoice_no:10
		modal-date:2018-03-05
		modal-sku:WSHO1
		modal-qty:2
		modal-cash:6.00
		modal-discounted_price:0.00
		
		$invoice_no = 13;
		$sku = 'WSHO1';
		$product_qty = $this->mdl_product->get_col_where('quantity', 'sku', $sku);
		$invoice_qty = $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku);
		echo 'PROD QTY = ' . $product_qty;
		echo '<br>';
		echo 'INVOICE QTY = ' . $invoice_qty;
		echo '<br>';
		echo 'PROD QTY AFTER EDITING TO 2 = ' . $this->process_qty('edit', $invoice_no, $sku, 2);*/
	}
	
		*/
?>										