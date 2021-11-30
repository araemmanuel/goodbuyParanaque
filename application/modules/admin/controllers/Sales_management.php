<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_management extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->load->model('mdl_product');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_invoice');
		$this->load->model('mdl_invoice_line');
		$this->load->model('mdl_card_transaction');
		$this->load->model('mdl_rewards_card');
		$this->load->model('mdl_order');
		// 2019/09/28 Sigrid add start ----------------------->
		$this->load->model('mdl_notification');
		$this->load->model('mdl_notification_message');
		// 2019/09/28 Sigrid add end ----------------------->
		
		$this->set_user_role("admin");		
		ob_clean();
		
	}
	function index()
    {
		$this->display(null, date('Y-m-d'));
	}	
	// 2019/09/28 Sigrid add start ----------------------->
	private function get_msg_code($prod_id, $sku=null)
	{
		/*
		Product codes explained
		OS = Out of stock
		VOS = Variant out of stock
		POS = Product out of stock
		
		//Code below not yet implemented as of 09/28/2019
		OSS = Out of stock by subcategory
		*/
		$prod_qty = $this->mdl_product->get_total_qty($prod_id); 
		$sku_qty = $this->mdl_product_variant->get_col_where("quantity", "sku", $sku);
		
		if($prod_qty == 0)
			$code = "POS";		
		else if($sku_qty == 0)
			$code = "VOS";
		else
			$code = null;
		return $code;
	}
	private function add_notif($msg_code, $prod_id, $sku=null)
	{	
		$data['notif_msg_id'] = $this->mdl_notification_message->get_col_where("notif_msg_id", "code", $msg_code); 
		$data['prod_id'] = $prod_id;
		$data['sku'] = $sku;
		$data['datetime_added'] = date('Y-m-d H:i:s');
		$this->mdl_notification->_insert($data);
		$this->log_data("Notification Added", "Notification added for Prod: "+$prod_id+" SKU: "+$sku);										
	}
	private function del_notif($prod_id, $sku=null)
	{	
		$notif_id = $this->mdl_notification->get_notif_id($prod_id, $sku);
		$this->mdl_notification->_delete("notif_id", $notif_id);
		$this->log_data("Notification Deleted", "Notification deleted for Prod: "+$prod_id+" SKU: "+$sku);										
	}
	private function notif_main($func, $prod_id, $sku, $qty)
	{
		if($func == 'add')
		{
			$msg_code = $this->get_msg_code($prod_id, $sku);
			if($msg_code != null)	
				$this->add_notif($msg_code, $prod_id, $sku);
			$this->log_data("Notification Added", "Notification added for Prod: "+$prod_id+" SKU: "+$sku);										
	
		}
		else if($func == 'edit')
		{
			if($qty == 0){
				$msg_code = $this->get_msg_code($prod_id, $sku);
				if($msg_code != null){
					$this->add_notif($msg_code, $prod_id, $sku);				
					$this->log_data("Notification Added", "Notification added for Prod: "+$prod_id+" SKU: "+$sku);										
				}	
			
			}else if($qty > 0){
				$this->del_notif($prod_id, $sku);
				$this->log_data("Notification Deleted", "Notification deleted for Prod: "+$prod_id+" SKU: "+$sku);										
			}
		}
		else if($func == 'void')
		{
			$this->del_notif($prod_id, $sku);
		}
	}
	// 2019/09/28 Sigrid add end ----------------------->
	function change_item($invoice_no)
	{

		$data['orig_invoice_no'] = $invoice_no;
		$data['sales_date'] = $this->mdl_invoice->get_sale_date($invoice_no);
		$data['orig_sale'] = $this->mdl_invoice->get_sale2($invoice_no);
		$content = $this->load->view('vw_admin_change_item', $data, TRUE);
		$this->load_view($content);
	}

	function add($invoice_no_to_void=null)
	{
		
		$this->form_validation->set_rules('prod-code', 'Product Code', 'trim|required|callback_sku_check');
		$this->form_validation->set_rules('prod-qty', 'Quantity', 'trim|required|callback_sale_add_qty_check|integer');
		
		if($this->input->post('prod-discounted-price'))
			$this->form_validation->set_rules('prod-discounted-price', 'Discounted Price', 'trim|numeric|callback_amt_paid_check');
		
		if($this->input->post('card-no'))
			$this->form_validation->set_rules('card-no', 'Membership ID', 'trim|callback_member_check');
		
		if($this->input->post('reward-pts'))
			$this->form_validation->set_rules('reward-pts', 'Reward Points', 'trim|numeric|callback_points_check');
	
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data['prod-code'] = form_error('prod-code');
			$data['prod-qty'] = form_error('prod-qty');
			$data['card-no'] = form_error('card-no');
			$data['reward-pts'] = form_error('reward-pts');
			
			if($this->input->post('prod-discounted-price'))
				$data['prod-discounted-price'] = form_error('prod-discounted-price');
			$data['error'] = true;
		}
		else
		{	
			$this->process_add($invoice_no_to_void);
			$data['success'] = true;	
		}	
		print json_encode($data);
	}
	private function process_add($invoice_no_to_void = null)
	{
		$sales_date = $this->input->post('sales-date');
				
		//Added for change item function		
		if($invoice_no_to_void)
		{
			$sku = $this->mdl_invoice_line->get_col_where('sku', 'invoice_no', $invoice_no_to_void);
			$qty = $this->mdl_invoice_line->get_col_where('qty', 'invoice_no', $invoice_no_to_void);
			$this->del($invoice_no_to_void, $sku, $qty, $sales_date, true);
		}	
				
		if($sales_date || $invoice_no_to_void != null)
		{
			//uncomment if you want to enter sales on invoice line 
			//if(!$this->mdl_invoice->get_col_where('date', 'date', $sales_date))
			
			//insert to tblInvoice
			$inv_data['date'] = $sales_date;
			$inv_data['is_issued_receipt'] = 0;
			$inv_data['is_sold_from_store'] = 1;
			//$inv_data['cash'] = $this->input->post('cash');
										
			$invoice_no = $this->mdl_invoice->_insert($inv_data);
			
			//insert to tblInvoice Line
			$inv_li_data['invoice_no'] = $invoice_no;
			$inv_li_data['line_no'] = $this->mdl_invoice->get_line_no($invoice_no);
			$inv_li_data['sku'] = strtoupper($this->input->post('prod-code'));
			$inv_li_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $inv_li_data['sku']);
			$inv_li_data['qty'] = $this->input->post('prod-qty');
			$inv_li_data['purchase_price'] = $this->mdl_product_variant->get_col_where('purchase_price', 'sku', $inv_li_data['sku']);
			$inv_li_data['selling_price'] = $this->mdl_product_variant->get_col_where('selling_price', 'sku', $inv_li_data['sku']);
			
			if($this->input->post('prod-discounted-price'))
				$inv_li_data['discount'] =($inv_li_data['qty'] * $inv_li_data['selling_price']) - $this->input->post('prod-discounted-price');
			else
				$inv_li_data['discount'] = 0;
			
			$temp_amt = $this->get_total_price($inv_li_data['sku'], $inv_li_data['qty'], $this->input->post('prod-discounted-price'));
			//Gain points	
			
			$this->log_data("Sale Add", "Added sale with invoice no. of " . $invoice_no);	
			$membership_id = $this->input->post('card-no');
			if(!empty($membership_id)) // &&  $temp_amt >= 200
			{
				$this->gain_reward_points($membership_id, $invoice_no, $temp_amt);
			}
			$reward_points = $this->input->post('reward-pts');
			if(!empty($reward_points))
			{
				$temp_amt = $temp_amt - $reward_points;
				$this->use_reward_points($membership_id, $invoice_no, $reward_points);
			}
			$inv_li_data['amt_paid'] = $temp_amt;
			
			
			if($invoice_no != null)
				$add2 = $this->mdl_invoice_line->_insert($inv_li_data);
					
			//Insert to tblInvoice		
			if(isset($add2)){			
				$inv2_data['cash'] = $this->mdl_invoice->get_total_invoice($invoice_no);
				$inv2_data['amt_paid'] = $inv2_data['cash'];
				$edit = $this->mdl_invoice->_update('invoice_no', $invoice_no, $inv2_data);	
			}
			$editQty = $this->process_qty('add', $inv_li_data['invoice_no'], $inv_li_data['sku'], $inv_li_data['qty']);
			//if($invoice_no && $add2 && $edit && $editQty)
			$this->session->set_flashdata('alert_msg','Sale Added Successfully!');	
		
		}
		//else
			//$this->session->set_flashdata('error_msg','Error cannot add sale. Please set sales date.');			
		
		$this->session->set_flashdata('sales_date', $sales_date);
		//redirect('admin/sales_management/display');
	}

	//delete gained points
	private function gain_reward_points($membership_id, $inv_no, $price, $func = null)
	{
		$this->log_data("Reward Points Gain", "Gained points using invoice no. " . $inv_no);										
		$equivalent_points = (float)($price/200);
		$card_no = $this->mdl_rewards_card->get_col_where('card_no', 'membership_id', $membership_id);
		
		if($func == 'edit')
		{
			$gained_receipt_price =  200 * $this->mdl_card_transaction->get_col_where('gained_reward_pts', 'invoice_no', $inv_no);
			$current_receipt_price = $this->mdl_rewards_card->get_col_where('receipt_price', 'card_no', $card_no);
			$current_receipt_price = abs($current_receipt_price - $gained_receipt_price);
			$ct_data['gained_reward_pts'] = $equivalent_points;
			$this->mdl_card_transaction->_update('invoice_no', $inv_no, $ct_data);	
		}
		else
		{
			$current_receipt_price = $this->mdl_rewards_card->get_col_where('receipt_price', 'card_no', $card_no);
			//Insert to tblcard_transaction
			$ct_data['card_no'] = $card_no;
			$ct_data['invoice_no'] = $inv_no;
			$ct_data['gained_reward_pts'] = $equivalent_points;
			$this->mdl_card_transaction->_insert($ct_data);	
		}
		
		
		//Update tblreward_card
		$rc_data['receipt_price'] = $current_receipt_price + $price;
		$this->mdl_rewards_card->_update('card_no', $card_no, $rc_data);

		
		
		
		/*
		$equivalent_points = floor($price/200);
		$card_no = $this->mdl_rewards_card->get_col_where('card_no', 'membership_id', $card_no);
		
		if($func == 'edit')
		{
			$current_pts =  $this->mdl_card_transaction->get_gained_pts($inv_no);
			$current_pts = $current_pts + abs($this->mdl_rewards_card->get_col_where('reward_points', 'card_no', $card_no) - $this->mdl_card_transaction->get_gained_pts($inv_no));
			$this->mdl_card_transaction->del_gained_pts($inv_no);
		}
		else
			$current_pts = $this->mdl_rewards_card->get_col_where('reward_points', 'card_no', $card_no);
		
		//Update tblreward_card
		$rc_data['card_no'] = $card_no;
		$rc_data['reward_points'] = $current_pts + $equivalent_points;
		$this->mdl_rewards_card->_update('card_no', $card_no, $rc_data);

		//Insert to tblcard_transaction
		$ct_data['card_no'] = $card_no;
		$ct_data['invoice_no'] = $inv_no;
		$ct_data['reward_points'] = $equivalent_points;
		$ct_data['transaction_type'] = 'GAINED';
		$this->mdl_card_transaction->_insert($ct_data);	
		*/
	}
	//delete reward points
	private function use_reward_points($membership_id, $inv_no, $use_pts, $func = null)
	{
		$this->log_data("Reward Points Use", "Used ".$use_pts." points for invoice no. " . $inv_no);								
			
		$card_no = $this->mdl_rewards_card->get_col_where('card_no', 'membership_id', $membership_id);	
		if($func == 'edit')
		{
			$used_receipt_price =  200 * $this->mdl_card_transaction->get_col_where('used_reward_pts', 'invoice_no', $inv_no);
			$current_receipt_price = $this->mdl_rewards_card->get_col_where('receipt_price', 'card_no', $card_no);
			$current_receipt_price = $current_receipt_price + $used_receipt_price;
		}
		else
		{
			$current_receipt_price = $this->mdl_rewards_card->get_col_where('receipt_price', 'card_no', $card_no);	
		}
		//Update tblreward_card
		$rc_data['receipt_price'] = $current_receipt_price - ($use_pts * 200);
		$this->mdl_rewards_card->_update('card_no', $card_no, $rc_data);
	
		if($this->mdl_card_transaction->exists('invoice_no', $inv_no))
		{			
			//Insert to tblcard_transaction
			$ct_data['used_reward_pts'] = $use_pts;
			$this->mdl_card_transaction->_update('invoice_no', $inv_no, $ct_data);
		}
		else
		{
			//Insert to tblcard_transaction
			$ct_data['card_no'] = $card_no;
			$ct_data['invoice_no'] = $inv_no;
			
			$ct_data['used_reward_pts'] = $use_pts;
			$this->mdl_card_transaction->_insert($ct_data);	
		}
		/*
		$card_no = $this->mdl_rewards_card->get_col_where('card_no', 'membership_id', $card_no);
		if($func == 'edit')
		{
			$current_pts =  $this->mdl_card_transaction->get_used_pts($inv_no);
			$current_pts = $current_pts + abs($this->mdl_rewards_card->get_col_where('reward_points', 'card_no', $card_no) - $this->mdl_card_transaction->get_gained_pts($inv_no));
			$this->mdl_card_transaction->del_used_pts($inv_no);
		}
		else
			$current_pts = $this->mdl_rewards_card->get_col_where('reward_points', 'card_no', $card_no);
		
		//Update tblreward_card
		$rc_data['card_no'] = $card_no;
		$rc_data['reward_points'] = $current_pts - $entered_pts;
		$this->mdl_rewards_card->_update('card_no', $card_no, $rc_data);
			
		//Insert to tblcard_transaction
		$ct_data['card_no'] = $card_no;
		$ct_data['invoice_no'] = $inv_no;
		$ct_data['reward_points'] = $entered_pts;
		$ct_data['transaction_type'] = 'USED';
		$this->mdl_card_transaction->_insert($ct_data);	
		*/
	}
	
	
	//possible bug: invoice qty doesn't consider invoice line 
	//this only works for sales management where invoice line for each invoice no is only 1
	function edit() 
	{
		$this->form_validation->set_rules('modal-sku', 'Product Code', 'trim|required|callback_sku_check');
		$this->form_validation->set_rules('modal-qty', 'Quantity', 'trim|required|callback_sale_edit_qty_check|integer');
		//$this->form_validation->set_rules('modal-cash', 'Cash', 'trim|required|callback_cash_check');
		if($this->input->post('modal-amt_paid'))
			$this->form_validation->set_rules('modal-amt_paid', 'Discounted Price', 'trim|numeric|callback_amt_paid_check');
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["sku"] = form_error("modal-sku");
			$data["qty"] = form_error("modal-qty");
			//$data["cash"] = form_error("modal-cash");
			$data["amt_paid"] = form_error("modal-amt_paid");
			$data["error"] = true;	
							
		}
		else
		{	
			$this->process_edit();
			$data["success"]= true;
		}	
		print json_encode($data);
	}	
	function process_edit()
	{
	
		$invoice_no = $this->input->post('modal-invoice_no');
		$this->log_data("Sale Edit", "Edited sale with invoice no. of " . $invoice_no);	

		//edit tblinvoice_line
		$inv_li_data['sku'] = strtoupper($this->input->post('modal-sku'));
		$inv_li_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $inv_li_data['sku']);
		$inv_li_data['qty'] = $this->input->post('modal-qty');
		$inv_li_data['purchase_price'] = $this->mdl_product_variant->get_col_where('purchase_price', 'sku', $inv_li_data['sku']);		
		$inv_li_data['selling_price'] = $this->mdl_product_variant->get_col_where('selling_price', 'sku', $inv_li_data['sku']);
	
		if($this->input->post('modal-amt_paid'))
			$inv_li_data['discount'] =($inv_li_data['qty'] * $inv_li_data['selling_price']) - $this->input->post('modal-amt_paid');
		else
			$inv_li_data['discount'] = 0;
		
		$temp_amt = $this->get_total_price($inv_li_data['sku'], $inv_li_data['qty'], $this->input->post('modal-amt_paid'));
		
		//Gain points	
		$membership_id = $this->input->post('modal-membership_id');
		if(!empty($membership_id) &&  $temp_amt >= 200)
			$this->gain_reward_points($membership_id, $invoice_no, $temp_amt, 'edit');
		
		$reward_points = $this->input->post('modal-reward_points');
		if(!empty($reward_points))
		{
			$temp_amt = $temp_amt - $reward_points;
			$this->use_reward_points($membership_id, $invoice_no, $reward_points, 'edit');				
		}
		$inv_li_data['amt_paid'] = $temp_amt;
		
		$editQty = $this->process_qty('edit', $invoice_no, $inv_li_data['sku'], $inv_li_data['qty']);
		$edit1 = $this->mdl_invoice_line->_update('invoice_no', $invoice_no, $inv_li_data);
		
		//$inv_data['total_price'] = $this->mdl_invoice->get_total_invoice($invoice_no);
		$inv_data['cash'] = $this->mdl_invoice->get_total_invoice($invoice_no); //$this->input->post('modal-cash');
		$edit2 = $this->mdl_invoice->_update('invoice_no', $invoice_no, $inv_data );
	

		if($edit1 && $edit2)
			$this->session->set_flashdata('alert_msg','Sale Edited Successfully!');	
		$this->session->set_flashdata('sales_date', $this->input->post('modal-date'));
		//redirect('admin/sales_management');
	}
	private function process_qty($func, $invoice_no, $sku, $user_qty)
	{
		$product_qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$invoice_qty = $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku);
		$prod_id = $this->mdl_product_variant->get_col_where("prod_id", "sku", $sku);
		if($func == 'add')
		{
			$data['quantity'] = $product_qty - $user_qty;
		}
		else if($func == 'edit')
		{
			$data['quantity'] =($product_qty + $invoice_qty) - $user_qty;
		}
		else if($func == 'void')
		{
			$data['quantity'] = $product_qty + $user_qty;
		}
		$prod_id = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku);
		$this->mdl_product_variant->update($prod_id, $sku, $data);
		$this->notif_main($func, $prod_id, $sku, $data['quantity']);
	}
	function del($invoice_no, $sku, $qty, $sales_date, $is_change_item = false)
	{
		$card_no = $this->mdl_card_transaction->get_col_where('card_no', 'invoice_no', $invoice_no);
		//$inv_data['is_void'] = 1;
		if($card_no)
		{			
			//Minus gained, Add used to reverse reward points transaction_type
			$gained = $this->mdl_card_transaction->get_col_where('gained_reward_pts', 'invoice_no', $invoice_no) * 200;
			$used = $this->mdl_card_transaction->get_col_where('used_reward_pts', 'invoice_no', $invoice_no)  * 200;
			$current = $this->mdl_rewards_card->get_col_where('receipt_price', 'card_no', $card_no);
			$data['receipt_price'] = (($current - $gained) + $used);
			$this->mdl_rewards_card->_update('card_no', $card_no, $data);
			$this->mdl_card_transaction->_delete('invoice_no', $invoice_no);
		}
		if($this->mdl_invoice->verify_if_order($invoice_no))
		{
			$order_data['invoice_no'] = null;
			$this->mdl_order->_update('invoice_no', $invoice_no, $order_data);
		}
		$this->process_qty('void', $invoice_no, $sku, $qty);
		$this->mdl_invoice_line->update($invoice_no, $sku);
		if($this->mdl_invoice_line->is_all_void($invoice_no))
		{
			$inv_data['is_void'] = 1;
			$this->mdl_invoice->_update('invoice_no', $invoice_no, $inv_data);
		}	
		$this->log_data("Sale Void", "Voided sale with invoice no. of " . $invoice_no);	

		if($is_change_item == false)
		{
			$this->session->set_flashdata('alert_msg','Transaction Voided Successfully!');
			$this->log_data("Sale Void", "Voided sale with invoice no. of " . $invoice_no);	
			$this->session->set_flashdata('sales_date', $sales_date);
			redirect('admin/sales_management/display');				
		}
		
	}
	//<?php if(isset($sales_date) && strtotime($sales_date) != 0) echo date('Y-m-d',strtotime($sales_date)); 
	public function display($data = null, $dateToday = null)
	{
		if($this->input->post('sales-date'))
			$this->session->set_flashdata('sales_date', $this->input->post('sales-date'));
		
		if($dateToday)
			$this->session->set_flashdata('sales_date', $dateToday);
		
		$data['products'] = $this->mdl_product->get_products();
		$data['all_products'] = $this->mdl_product_variant->get_pvs_for_sales_mng();
		$data['sales'] = $this->mdl_invoice->get_sales($this->session->flashdata('sales_date'));
		$content = $this->load->view('vw_admin_sales', $data, TRUE);
		$this->load_view($content);
	}

	public function member_check($str)
	{
		$str = $this->input->post('card-no');
		if (!$this->mdl_rewards_card->exists('membership_id', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, 'Member doesn\'t exist.');
			return FALSE;
		}
		else if($this->mdl_rewards_card->is_expired($str))
		{
			$this->form_validation->set_message(__FUNCTION__, 'Sorry this card has already expired. Please renew.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}	
	public function points_check($use_points)
	{
		$current_pts = $this->mdl_rewards_card->get_points($this->input->post('card-no'));
		$sku = $this->input->post('prod-code');
		$qty = $this->input->post('prod-qty');
		$orig_selling_price = $this->mdl_product_variant->get_col_where("selling_price", "sku", $sku);
		$sale_price = $sku * $qty;
		
		$discounted_price = $sale_price;
		if($this->input->post('prod-discounted-price'))
			$discounted_price = $this->input->post('prod-discounted-price');
			
		
		if ($current_pts < $use_points)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Member doesn\'t have enough points.');
			return FALSE;
		}
		else if($use_points > $sale_price || $use_points > $discounted_price)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Points is greater than computed price.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function sku_check($str)
	{
		$is_active = $this->mdl_product_variant->get_col_where('is_active', 'sku', $str);
		if (!$str)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if (!$this->mdl_product_variant->exists('sku', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} doesn\'t exist.');
			return FALSE;
		}
		else if($is_active ==  0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Sale cannot be added. Selected product variant is deactivated.');
			return FALSE;			
		}
		else
		{
			return TRUE;
		}
	}
	public function sale_add_qty_check($qty)
	{
		$sku = $this->input->post('prod-code');
	
		$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$prod_exists = $this->mdl_product_variant->get_col_where('sku', 'sku', $sku);
		
		
		if(!$qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if( $qty <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid quantity. Add quantity cannot be less than or equal to zero.');
			return FALSE;
		}			
		else if($prod_exists && $stock_on_hand == 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'No available stock left.');
			return FALSE;
		}//qty check applicable to add sale only
		else if($prod_exists &&  $stock_on_hand < $qty && $this->input->post('prod-code'))
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. It exceeds stock on hand.');
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	public function sale_edit_qty_check($qty)
	{

		$sku = $this->input->post('modal-sku');	
		$invoice_no = $this->input->post('modal-invoice_no');	
		$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$prod_exists = $this->mdl_product_variant->get_col_where('sku', 'sku', $sku);
		if($prod_exists!=null)
		{			
			$invoice_qty = $this->mdl_invoice_line->get_invoice_qty($invoice_no, $sku);
			$sale_edit_qty = $stock_on_hand + $invoice_qty; 

		}
		if(!$qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if($prod_exists &&  $qty <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. Edit quantity cannot be less than or equal to zero.');
			return FALSE;	
		}
		else if($prod_exists && $sale_edit_qty < $qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. It exceeds stock on hand.');
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	
	public function amt_paid_check($s)
	{
		
		if($this->input->post('modal-sku'))
		{
			$sku = $this->input->post('modal-sku');
			$qty = $this->input->post('modal-qty');
	
		}
		else
		{			
			$sku = $this->input->post('prod-code');	
			$qty = $this->input->post('prod-qty');				
		}
			
		$selling_price = $qty * $this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku);
		if ($s <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} cannot be less than or equal to zero.');
			return FALSE;
		}
		else if($s > $selling_price)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Discounted Price cannot be greater than selling price.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	
	
	//if discounted price per item has value  and cash doesn't match total price 
	//if cash != total price
	public function cash_check($str)
	{
		$sku = $this->input->post('prod-code');
		$qty = $this->input->post('prod-qty');
		$discounted_price = $this->input->post('prod-discounted-price');;
		
		if(!$sku)
			$sku = $this->input->post('modal-sku');
		if(!$qty)
			$qty = $this->input->post('modal-qty');
		if(!$discounted_price)
			$discounted_price = $this->input->post('modal-discounted_price');
			
		$total_price = $this->get_total_price($sku, $qty, $discounted_price);
		if ($total_price != $str)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Cash doesn\'t match calculated total price.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function get_total_price($sku, $qty = 1, $discounted_price = 0)
	{
		$price = $this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku);
		if($discounted_price != 0)
		{
			//$total_price = $discounted_price * $qty;
			$total_price = $discounted_price;
		}
		else
		{
			$total_price = $price * $qty;
		}			
		return $total_price;
	}

	public function get_sale($invoice_no)
	{
		print json_encode($this->mdl_invoice->get_sale($invoice_no));  
	}
	public function get_price_per_item($sku)
	{
		print json_encode($this->mdl_product_variant->get_col_where('selling_price', 'sku', $sku));  
	}
	public function get_card_no()
	{
		$srch = $this->input->post('ajax_card');
		print json_encode($this->mdl_rewards_card->get_card_nos($srch));  
	}
	public function get_membership_id()
	{
		$id = $this->input->post('ajax_card');
		print json_encode($this->mdl_rewards_card->get_membership_id2($id));  
	}
	public function get_prod_codes()
	{
		$s = $this->input->post('ajax_prod_code');
		print json_encode($this->mdl_product_variant->get_skus($s));  	
	}
	public function get_sale_prod_info()
	{
		$s = $this->input->post('ajax_sku');
		if($this->mdl_product_variant->exists('sku', $s))
		{
			$is_active = $this->mdl_product_variant->get_col_where('is_active', 'sku', $s);
			$qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $s);
			if($is_active == 0)
			{
				$data = (object) ['error' => 	 "Product is deactivated."];
				print json_encode($data);
			}
			else if($qty == 0)
			{
				$data = (object) ['error' => 	'No available stock.'];
				print json_encode($data);
			}
			else
			{
				print json_encode($this->mdl_product_variant->get_sale_prod_info($s)); 
			}
		}
		else
		{
			
			$data = (object) ['error' => 	 "Product doesn't exist."];
			print json_encode($data);
		}
	}

	public function get_all_products()
	{
		$fetch_data = $this->mdl_product_variant->make_datatables();  
        $data = array();  
        foreach($fetch_data as $p)  
        {  
            $sub_array = array();  
			$sub_array[] = $p->cat_name;
			$sub_array[] = $p->subcat_name;
			$sub_array[] = $p->sku;	
			$above = '<td><div class="row clearfix">';
			if($p->primary_image != 'None')
		    {
				$mid = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<img src="'.base_url($p->primary_image).'" width="100" height="100">
						</div>';
			}
			else
			{
				$mid = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<img src="'. base_url('assets/images/no-photo.jpg').'" width="100" height="100">
						</div>';
			}				
			$below ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
					. '<div class="pull-left"><span>'.$p->name.'</span></div>'
					. '</div></div></td>';
			$sub_array[] = 	$above.$mid.$below;
			$sub_array[] = $p->quantity;
			$sub_array[] = $p->options;
			$sub_array[] = $p->selling_price;			
			$data[] = $sub_array;  

		}
			if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_product_variant->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_product_variant->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);  
	}
}
