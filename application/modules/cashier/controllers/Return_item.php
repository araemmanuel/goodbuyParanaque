<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Return_item extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("cashier");	
		$this->load->model('mdl_invoice_line');
		$this->load->model('mdl_invoice');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_returned_items');
		$this->load->model('mdl_return_transaction');
		$this->load->model('mdl_return_policy');
		$this->load->model('mdl_non_saleable');
		$this->load->model('mdl_card_transaction');
		$this->load->model('mdl_vat_reg');
		$this->load->model('mdl_terminal');		
		$this->load->model('mdl_shift');		
		$this->load->model('mdl_pos');
	}
	
	public function index()
    {
		$this->display();
	}
	private function load_cash_view($content)
	{
		if ($this->is_allowed_access('cashier')) 
		{
			$data['content']=$content;
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$data['terminal'] = $this->get_terminal();
			$data['pos_has_started'] = $this->mdl_pos->pos_has_started($this->get_terminal());
			$data['prev_batch_ended'] = (strcasecmp($this->mdl_pos->get_last_session('status', $this->get_terminal()), 'open') != 0);
			
			$data['shift_has_started'] = $this->shift_has('started', $this->get_terminal());
			$data['shift_has_ended'] = $this->shift_has('ended', $this->get_terminal());
			
			$data['is_mobile']=$this->agent->is_mobile();			
			$this->load->view('vw_cashier_home', $data);	
		}
	}
	private function get_terminal()
	{
		return $this->mdl_terminal->get_col_where('id', 'name', 'Test Terminal');
	}
	private function shift_has($func, $terminal)
	{
		$user_id = $this->mdl_user->get_col_where('id', 'username', $this->get_sess_username());
		$pos_id = $this->mdl_pos->get_pos_id($this->get_terminal(), 'OPEN');	
		if($func == 'started')
			return $this->mdl_shift->shift_has_started($pos_id, $user_id);
		else if($func == 'ended')
			return $this->mdl_shift->shift_has_ended($pos_id, $user_id);
		else
			return false;
	}
	
	private function invalid_receipt($invoice_no)
	{
		if($invoice_no)
		{
			$num_days = $this->mdl_invoice->get_col_where('return_policy', 'invoice_no',$invoice_no);
			$invoice_date = $this->mdl_invoice->get_col_where('date', 'invoice_no',$invoice_no);
			$invalid_date = date('Y-m-d', strtotime($invoice_date . " + $num_days days"));
			$today =  date('Y-m-d');
			if($today > $invalid_date)
			{
				return true;
			}
			else
			{		
				return false;		
			}
		}		
	}
	private function display()
	{
		date_default_timezone_set('Asia/Manila');
		$num_days = $this->mdl_return_policy->get_days();
		$invoice_no = $this->input->post('invoice-no');
		$invoice_date = $this->mdl_invoice->get_col_where('date', 'invoice_no', $invoice_no);
		$invalid_date = date('Y-m-d', strtotime($invoice_date . " + $num_days days"));
		$today =  date('Y-m-d');
		if($this->invalid_receipt($invoice_no))
		{
			$data['return_error'] = 'Error receipt is no longer valid.';	
		}
		else
		{		
			$data['invoice_no'] = $this->input->post('invoice-no');			
			$data['items'] = $this->mdl_invoice_line->get_items($data['invoice_no']);	
		}
		$content = $this->load->view('vw_cashier_return_item', $data, TRUE);	
		$this->load_cash_view($content);
	}

	public function replacement($trans_id, $total_amt)
	{
		$invoice_no = $this->mdl_return_transaction->get_col_where('replace_trans_invoice_no', 'id', $trans_id);
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
		if($this->mdl_return_transaction->exists('id', $trans_id) && $is_void == 0)
		{
			$data['trans_id'] = $trans_id;
			if(!$this->invalid_receipt($invoice_no))
			{			
				$data['total_amt'] = $total_amt;
				$content = $this->load->view('vw_cashier_replacement_item', $data, TRUE);	
				$this->load_cash_view($content);
			}
		}
		else
			redirect('cashier/return_item');
		
	}
	public function sku_check($str)
	{
		if (count($str) <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Select item\s to be returned.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function add()
	{
		$this->form_validation->set_rules('reason', 'Reason', 'trim|required');
		$this->form_validation->set_rules('sku[]', 'Product', 'callback_sku_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data['reason'] = form_error('reason');
			$data['sku'] = form_error('sku[]');
			$data['error'] = true;
			//$this->display();
		}
		else
		{
			$sku = $this->input->post('sku[]');
			$qty = $this->input->post('qty[]');
			$reason = $this->input->post('reason');	
			$amt = $this->input->post('amt[]');				
			$invoice_no = $this->input->post('invoice-no');	
			
			$rt_data['date'] = date('Y-m-d');
			$rt_data['return_trans_invoice_no'] = $invoice_no;
			$rt_data['reason'] = $reason;
			$trans_id = $this->mdl_return_transaction->_insert($rt_data);
			$total_amt = 0;
			$rt_data2['return_amt'] = 0;
			
			for($i=0;$i<count($sku);$i++)
			{
				$line_no = $this->mdl_invoice->get_line_no2($invoice_no, $sku[$i]);
				
				if(count($sku) == 1)
					$index = 0;
				else
					$index = $line_no - 1;
				//Insert to return Item
				
				$ri_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku[$i]);
				$ri_data['sku'] = $sku[$i];
				$ri_data['qty'] = $qty[$index];
				$ri_data['total_selling_price'] = $qty[$index] * $this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku[$i]);				
				$rt_data2['return_amt'] = $rt_data2['return_amt'] + $ri_data['total_selling_price'];
				if($this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku[$i]) == $ri_data['qty'])
					$total_amt = $total_amt + $amt[$line_no - 1];
				else
					$total_amt = $total_amt + ($amt[$line_no - 1]/$ri_data['qty']);
	
				$ri_data['type'] = 'RETURN';
				$ri_data['trans_id'] = $trans_id;
				$this->mdl_returned_items->_insert($ri_data);
				
				//Update invoice 
				$invoice_qty = $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku[$i]);
				$amt_paid = $this->mdl_invoice_line->get_amt_paid($invoice_no, $sku[$i]);
				if($ri_data['qty'] != $invoice_qty)
				{
					$inv_data['qty'] = $invoice_qty - $ri_data['qty'];
					//$inv_data['amt_paid'] = $amt_paid;
				}
				else
				{
					$inv_data['qty'] = $invoice_qty - $ri_data['qty'];
					$inv_data['is_void'] = 1;
				}
				$this->mdl_invoice_line->update($invoice_no, $sku[$i], $inv_data);
				
				//Add to non-saleable if true
				if(strcasecmp($this->input->post('non-saleable'), 'true') == 0)
				{
					$ns_data['sku'] = $sku[$i];
					$ns_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku[$i]);
					$ns_data['qty'] = $ri_data['qty'];
					$ns_data['description'] =  $reason;
					$ns_data['date_added'] = date("Y-m-d");
					$ns_data['trans_id'] = $trans_id;
					$this->mdl_non_saleable->_insert($ns_data);
				}
				else //Add to stock 
					$this->update_qty($sku[$i], $ri_data['qty'], 'add');
			}
			$used_pts = $this->mdl_card_transaction->get_col_where('used_reward_pts', 'invoice_no', $invoice_no);
			//$ri_data['total_selling_price']
			$this->mdl_return_transaction->_update('id', $trans_id, $rt_data2);
			$data['trans-url'] = $trans_id.'/'.$total_amt;
			//redirect('cashier/return_item/replacement/'.$trans_id.'/'.$total_amt);
		}
		print json_encode($data);
	}	
	private function has_invalid_qty($sku, $qty)
	{
		for($i=0;$i<count($sku);$i++)
		{
			$ri_data['qty'] = $qty[$i];
			$in_stock = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku[$i]);
			if($ri_data['qty'] > $in_stock)
			{
				return true;
			}	
		}
		return false;
	}
	public function add_replacement()
	{
		$sku = $this->input->post('sku[]');
		$qty = $this->input->post('qty[]');
		
		$trans_id = $this->input->post('trans-id');
		$cash = $this->input->post('cash');	
		$return_amt = $this->mdl_return_transaction->get_col_where('return_amt', 'id', $trans_id);
		$total_amt = $this->input->post('total-amt');
		
		if($total_amt > ($return_amt+$cash))
		{
			$data['error'] = true;
			$data['error_msg'] = "Cash payment is required because total amount exceeds return amount.";
		}
		else
		{
			//insert to tblInvoice
			date_default_timezone_set('Asia/Manila');
			$inv_data['date'] =  date("Y-m-d H:i:s");
			$inv_data['is_issued_receipt'] = 1;
			$inv_data['is_sold_from_store'] = 1;
			$replacement_invoice_no = $this->mdl_invoice->_insert($inv_data);
			for($i=0;$i<count($sku);$i++)
			{
				//Insert to return item
				$ri_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku[$i]);
				$ri_data['sku'] = $sku[$i];
				$ri_data['qty'] = $qty[$i];
				$ri_data['total_selling_price'] =  $qty[$i] * $this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku[$i]);				
				$ri_data['type'] = 'REPLACEMENT';
				$ri_data['trans_id'] = $trans_id;
				$this->mdl_returned_items->_insert($ri_data);
				
				//Insert to invoice line
				$this->add_sale($replacement_invoice_no, $ri_data['sku'], $ri_data['qty'], $cash);
				
				//Edit qty in product variant
				$this->update_qty($sku[$i], $qty[$i], 'sold');		
			}
			$rt_data2['replacement_amt'] = $total_amt;
			$rt_data2['replace_trans_invoice_no'] = $replacement_invoice_no;
			$rt_data2['cash'] = $cash;
			$this->mdl_return_transaction->_update('id', $trans_id, $rt_data2);	
			
			$inv2_data['cash'] = $total_amt;
			$this->mdl_invoice->_update('invoice_no', $replacement_invoice_no, $inv2_data);	
			
			$data['trans_id'] = $trans_id;			
			$data['success'] = true;
		}
		print json_encode($data);	
	}
	function void_transaction($trans_id)
	{
		//Return replacement items to product variant
		$replacement_invoice_no = $this->mdl_return_transaction->get_col_where('replace_trans_invoice_no', 'id', $trans_id);
		$replacement_items = $this->mdl_returned_items->get_replacement_items($trans_id);
		foreach($replacement_items  as $i)
		{
			$stock = $this->mdl_product_variant->get_col_where('quantity', 'sku', $i->sku);
			$pv_data['quantity'] =  $stock + $i->qty;
			$this->mdl_product_variant->_update('sku', $i->sku, $pv_data);
			
			//Set invoice line item to void so that it won't appear in return item invoice
			$invli2_data['is_void'] = 1;
			$this->mdl_invoice_line->_update('invoice_no', $replacement_invoice_no, $invli2_data);	
		}
		
		$this->cancel_return($trans_id);
		
		//Set invoice as void
		$inv_data['is_void'] = 1;
		$this->mdl_invoice->_update('invoice_no', $replacement_invoice_no, $inv_data);

		$this->session->set_flashdata('alert_msg','Transaction voided Successfully!');
		redirect('cashier/return_item');
	}
	public function test($trans_id)
	{
		//Return returned items to invoice 
		$return_invoice_no = $this->mdl_return_transaction->get_col_where('return_trans_invoice_no', 'id', $trans_id);
		$returned_items = $this->mdl_returned_items->get_returned_items($trans_id);
		foreach($returned_items as $i)
		{
			$inv_qty = $this->mdl_invoice_line->get_invoice_qty($return_invoice_no,  $i->sku);
			$invli_data['qty'] =  $inv_qty + $i->qty;
			$invli_data['is_void'] =  0;
			echo $i->sku.$return_invoice_no;
			$this->mdl_invoice_line->update($return_invoice_no, $i->sku, $invli_data);
			//$this->update_qty($i->sku, $i->qty, 'sold');
			
		}
	}
	public function cancel_return($trans_id)
	{
		//Return returned items to invoice 
		$return_invoice_no = $this->mdl_return_transaction->get_col_where('return_trans_invoice_no', 'id', $trans_id);
		$returned_items = $this->mdl_returned_items->get_returned_items($trans_id);
		foreach($returned_items as $i)
		{
			$inv_qty = $this->mdl_invoice_line->get_invoice_qty($return_invoice_no,  $i->sku);
			$invli_data['qty'] =  $inv_qty + $i->qty;
			$invli_data['is_void'] =  0;
			$this->mdl_invoice_line->update($return_invoice_no, $i->sku, $invli_data);
			$this->update_qty($i->sku, $i->qty, 'sold');
			
		}
		$return_non_saleable = $this->mdl_non_saleable->get_return_items($trans_id);
		foreach($return_non_saleable as $i)
		{
			$this->update_qty($i->sku, $i->qty, 'add');
		}
		$this->mdl_non_saleable->_delete('trans_id', $trans_id);
		
		$this->session->set_flashdata('alert_msg','Return Transaction Canceled Successfully!');
		redirect('cashier/return_item');
	}
	private function add_sale($invoice_no, $sku, $qty, $cash)
	{
		$inv_li_data['invoice_no'] = $invoice_no;
		$inv_li_data['line_no'] = $this->mdl_invoice->get_line_no($invoice_no);
		$inv_li_data['sku'] = $sku;
		$inv_li_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku);
		$inv_li_data['qty'] = $qty;
		$inv_li_data['purchase_price'] = $this->mdl_product_variant->get_col_where('purchase_price', 'sku', $sku);
		$inv_li_data['selling_price'] = $this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku);		
		$inv_li_data['amt_paid'] = $qty * $inv_li_data['selling_price'];					
		$inv_li_data['discount'] = 0;	
		 $this->mdl_invoice_line->_insert($inv_li_data);
	}
	
	private function update_qty($sku, $user_qty, $func)
	{	
		$product_qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		if($func == 'sold')
		{
			$data['quantity'] = $product_qty - $user_qty;
		}
		else if($func == 'add')
		{
			$data['quantity'] = $product_qty + $user_qty;
			
		}
		$this->mdl_product_variant->_update('sku', $sku, $data);	
	}
	public function receipt($trans_id)
	{
		$data['invoice_no'] =  $this->mdl_return_transaction->get_col_where('replace_trans_invoice_no', 'id', $trans_id);
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $data['invoice_no']);
		if($is_void == 0)
		{
			$data['trans_id'] = $trans_id;
			$data['return_policy'] = $this->mdl_return_policy->get_days();
			$data['reg_tin'] = $this->mdl_vat_reg->get_reg_tin2();
			$data['receipt_items'] = $this->mdl_returned_items->get_receipt($trans_id);
			$data['receipt_details'] = $this->mdl_return_transaction->get_receipt_details($trans_id);
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$content = $this->load->view('vw_cashier_return_receipt', $data, TRUE);
			$this->load_cash_view($content);
		}
		else
			redirect('cashier/return_item');
	}
	
	public function print_receipt($trans_id)
	{
		$data['invoice_no'] =  $this->mdl_return_transaction->get_col_where('replace_trans_invoice_no', 'id', $trans_id);
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $data['invoice_no']);
		if($is_void == 0)
		{
			$data['return_policy'] = $this->mdl_return_policy->get_days();
			$data['receipt_items'] = $this->mdl_returned_items->get_receipt($trans_id);
			$data['reg_tin'] = $this->mdl_vat_reg->get_reg_tin2();
			$data['receipt_details'] = $this->mdl_return_transaction->get_receipt_details($trans_id);
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$this->load->view('vw_cashier_print_return_receipt', $data);
		}
	}
	
	
	
}
