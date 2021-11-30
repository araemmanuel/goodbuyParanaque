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
		$this->load->model('mdl_pos');
		$this->load->model('mdl_shift');
		$this->load->model('mdl_return_policy');
		// 2019/09/28 Sigrid add start ----------------------->
		$this->load->model('mdl_notification');
		$this->load->model('mdl_notification_message');
		// 2019/09/28 Sigrid add end ----------------------->
		$this->set_user_role("cashier");		
	}
	function index()
    {
		if (!$this->mdl_pos->pos_has_ended() && $this->is_allowed_access('cashier')) 
		{
			$data['all_products'] = $this->mdl_product_variant->get_pvs_for_sales_mng();
			$data['content'] = $this->load->view('vw_cashier_mode', $data, TRUE);	
			$this->load->view('vw_cashier_home', $data);	
		}
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
		else if($func == 'void')
		{
			$this->del_notif($prod_id, $sku);
		}
	}
	// 2019/09/28 Sigrid add end ----------------------->
	public function member_check($str)
	{
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
		if ($current_pts < $use_points)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Member doesn\'t have enough points.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	function add()
	{
		$this->form_validation->set_rules('sku[]', 'Product', 'trim|required|callback_sku_check2');
		$this->form_validation->set_rules('cash', 'Cash Paid', 'trim|callback_cash1_check');
		//if($this->input->post('prod-discounted-price'))
		//	$this->form_validation->set_rules('prod-discounted-price', 'Discounted Price', 'trim|numeric|callback_amt_paid_check');
		if($this->input->post('card-no'))
			$this->form_validation->set_rules('card-no', 'Membership ID', 'trim|callback_member_check');
		
		if($this->input->post('reward-pts'))
			$this->form_validation->set_rules('reward-pts', 'Reward Points', 'trim|numeric|callback_points_check');
		
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["sku"] = form_error("sku[]");
			$data["cash"] = form_error("cash");
			$data["card_no"] = form_error("card-no");
			$data["reward_points"] = form_error("reward-pts");
			$data["error"] = true;	
		}
		else
		{
			$data['invoice'] = $this->process_add();
			$data["success"]= true;	
		}	
		print json_encode($data);
	}
	public function sku_check2()
	{
		$sku = $this->input->post('sku[]');
		if (count($sku) <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__,'No products were entered.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function cash1_check($cash)
	{
		$new_total_amt = $this->input->post('new-total-amt');
		$reward_pts = $this->input->post('reward-pts');
		if($new_total_amt)
			$total_amt = $new_total_amt;
		else	
			$total_amt = $this->input->post('total-amt');
				
		$total_cash = $reward_pts + $cash;
		if(!isset($_SESSION['gb_cashier_user_id']) || $_SESSION['gb_cashier_user_id'] == null)
		{
			$this->form_validation->set_message(__FUNCTION__,'An error occurred. Please refresh the page.');
			return FALSE;
		}	
		else if ($total_cash < $total_amt)
		{
			$this->form_validation->set_message(__FUNCTION__,'Invalid cash payment. Cash should be greater than or equal to total amount.');
			return FALSE;
		}
		else if((!$cash || $cash <= 0) && $total_cash < $cash)
		{
			$this->form_validation->set_message(__FUNCTION__,'The Cash field is required');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function test($price=null)
	{
		
		echo (float)(20/200);
		/*
		$discount = $this->input->post('discount[]');
		$reward_points = $this->input->post('reward-pts');
		$receipt_discount = $this->input->post('receipt-discount');
			
		$per_item_only = count($discount) == 1 && $discount[0]!=0;
		$per_item = count($discount) > 0;
		$per_receipt = (strlen($receipt_discount) != 0);
		$has_reward_pts = (strlen($reward_points) != 0);
		echo print_r($discount);
		echo var_dump(count($discount));
		echo 'PER ITEM ONLY: '.var_dump(!$per_item_only);
		echo 'PER RECEIPT ONLY: '.var_dump(!$per_receipt);
		echo 'HAS REWARD POINTS: '.var_dump($has_reward_pts);
		
		
		if($per_item && !$per_receipt && !$has_reward_pts) //check
		{
			echo 'per item only';
		}	//per receipt only									
		else if(!$per_item_only && $per_receipt && !$has_reward_pts)//check
		{	
			echo 'per receipt only';
		}//per item and per receipt
		else if($per_item && $per_receipt && !$has_reward_pts) //check
		{	
			echo 'per item and per receipt';
		}
			//reward points only
		else if(!$per_item_only && !$per_receipt && $has_reward_pts)//check
		{
			echo 'reward points only';
		}// per receipt and has reward points						// per receipt and has reward points //check
		else if((!$per_item && $per_receipt && $has_reward_pts) | ($per_item && !$per_receipt && $has_reward_pts) | ($per_item && $per_receipt && $has_reward_pts))
		{	
			echo 'per receipt and has reward points	or per item and has reward points or all three';
			
		}
		*/
	}
	private function get_terminal()
	{
		return $this->mdl_terminal->get_col_where('id', 'name', 'Test Terminal');
	}
	private function process_add()
	{
			//insert to tblInvoice
			date_default_timezone_set('Asia/Manila');
			$inv_data['date'] =  date("Y-m-d h:i:sa");
			$inv_data['is_issued_receipt'] = 1;
			$inv_data['is_sold_from_store'] = 1;
			$inv_data['cash'] = $this->input->post('cash');
			$inv_data['return_policy'] = $this->mdl_return_policy->get_days();
			$inv_data['shift_id'] = $this->mdl_shift->get_shift_id($_SESSION['gb_cashier_user_id']);
			
			//$inv_data['discount_type'] = 'SALE';
			//$inv_data['shift_id'] = $this->mdl_shift->get_shift_id($_SESSION['gb_cashier_user_id']);
			//$inv_data['pos_id'] = $this->mdl_pos->get_pos_id($this->get_terminal());	
			$invoice_no = $this->mdl_invoice->_insert($inv_data);
			
			$sku = $this->input->post('sku[]');
			$qty = $this->input->post('qty[]');
			
			$amt = $this->input->post('amt[]');
			$discount = $this->input->post('discount[]');
			$membership_id = $this->input->post('card-no');
			$reward_points = $this->input->post('reward-pts');
			if($this->input->post('total-amt'))
			{
				$total_amt = $this->input->post('total-amt');
				$receipt_discount = $this->input->post('receipt-discount');
			
			}
			if($this->input->post('new-total-amt'))
			{
				$total_amt = $this->input->post('new-total-amt');
				$receipt_discount = $this->input->post('receipt-discount');
			}
			
			$num_of_items = (float)count($sku);
			$receipt_discount_distributed = $this->get_discount_by_type($this->input->post('d-type2'), $total_amt, $receipt_discount)/$num_of_items;
			$reward_pts_distributed = $reward_points/$num_of_items;
			
			$per_item_only = count($discount) == 1 && $discount[0]!=0;
			$per_item = count($discount) > 0;
			$per_receipt = (strlen($receipt_discount) != 0);
			$has_reward_pts = (strlen($reward_points) != 0);
			
			
			//insert to tblInvoice Line
			for($i=0;$i<count($sku);$i++)
			{
				$inv_li_data['invoice_no'] = $invoice_no;
				$inv_li_data['line_no'] = $this->mdl_invoice->get_line_no($invoice_no);
				$inv_li_data['sku'] = strtoupper($sku[$i]);
				$inv_li_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $inv_li_data['sku']);
				$inv_li_data['qty'] = $qty[$i];
				$inv_li_data['purchase_price'] = $this->mdl_product_variant->get_col_where('purchase_price', 'sku', $inv_li_data['sku']);
				$inv_li_data['selling_price'] = $this->mdl_product_variant->get_col_where('selling_price', 'sku', $inv_li_data['sku']);
				//per item only
				if($per_item && !$per_receipt && !$has_reward_pts) 
				{
					$inv_li_data['amt_paid'] = $amt[$i];
					$inv_li_data['discount'] = $discount[$i];
				}
				//per receipt only					
				else if(!$per_item_only && $per_receipt && !$has_reward_pts)
				{	
					$inv_li_data['amt_paid'] = $receipt_discount_distributed;
					$inv_li_data['discount'] = ($inv_li_data['selling_price'] * $inv_li_data['qty']) - $inv_li_data['amt_paid'];
				}
				//per item and per receipt
				else if($per_item && $per_receipt && !$has_reward_pts) 
				{	
					$inv_li_data['amt_paid'] = $receipt_discount_distributed;
					$inv_li_data['discount'] = ($inv_li_data['selling_price'] * $inv_li_data['qty']) - $inv_li_data['amt_paid'];
				}
				//reward points only
				else if(!$per_item_only && !$per_receipt && $has_reward_pts)
				{
					if($num_of_items == 1)
						$inv_li_data['amt_paid'] = $amt[$i] - $reward_points;
					else
						$inv_li_data['amt_paid'] = $amt[$i] - $reward_pts_distributed;
					$inv_li_data['discount'] = 0;
				}
				//per item and has reward points
				else if($per_item && !$per_receipt && $has_reward_pts)
				{
					$inv_li_data['amt_paid'] = $amt[$i] - $reward_pts_distributed;
					$inv_li_data['discount'] = $discount[$i];
					//($inv_li_data['selling_price'] * $inv_li_data['qty']) - $inv_li_data['amt_paid']
				}
				// per receipt and has reward points	
				else if((!$per_item && $per_receipt && $has_reward_pts))
				{	
					$inv_li_data['amt_paid'] = $receipt_discount_distributed - $reward_pts_distributed;		
					$inv_li_data['discount'] = (($inv_li_data['selling_price'] * $inv_li_data['qty']) - $inv_li_data['amt_paid']);
				}
				// per item and per receipt and has reward points
				else if($per_item && $per_receipt && $has_reward_pts)
				{
					$inv_li_data['amt_paid'] = $receipt_discount_distributed - $reward_pts_distributed;
					
					$inv_li_data['discount'] = (($inv_li_data['selling_price'] * $inv_li_data['qty']) - $inv_li_data['amt_paid'])- $reward_pts_distributed;
					// - $reward_points
				}
				else
				{
					$inv_li_data['amt_paid'] = $amt[$i];
					$inv_li_data['discount'] = 0;
				}	
				$inv_li_data['amt_paid'] = abs($inv_li_data['amt_paid']);
				$add2 = $this->mdl_invoice_line->_insert($inv_li_data);		
			    $this->process_qty('add', $invoice_no, $sku[$i], $qty[$i]);	
			}
			
			//Gain points			
			if(!empty($membership_id))// && $total_amt >= 200
				$this->gain_reward_points($membership_id, $invoice_no, $total_amt);
						
			if(!empty($reward_points))
			{
				$this->use_reward_points($membership_id, $invoice_no, $reward_points);	
			}	
			
			//Insert to tblInvoice		
			$inv2_data['cash'] = $this->input->post('cash');
			$edit = $this->mdl_invoice->_update('invoice_no', $invoice_no, $inv2_data);	
			return $invoice_no;
	}
	private function get_discount_by_type($d_type, $total_amt, $receipt_discount)
	{
		if(strcasecmp($d_type, 'percent') == 0)	
		{
			return $total_amt - ($total_amt*($receipt_discount/100));
		}
		else if(strcasecmp($d_type, 'amount') == 0)
		{
			return $receipt_discount; 
		}
		else
		{
			return $total_amt;
		}
	}
	function void_transaction($invoice_no)
	{
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
		if($is_void == 0)
		{
			$card_no = $this->mdl_card_transaction->get_col_where('card_no', 'invoice_no', $invoice_no);
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
			$this->process_qty('void', $invoice_no, null, null);
			$items = $this->mdl_invoice_line->get_items($invoice_no);
			foreach($items as $i)
			{
				$inv_data['is_void'] = 1;
				$this->mdl_invoice_line->update($invoice_no, $i->sku, $inv_data);
			}
			$this->mdl_invoice->_update('invoice_no', $invoice_no, $inv_data);
			$this->session->set_flashdata('alert_msg','Transaction voided Successfully!');
		}
		else
			$this->session->set_flashdata('error_msg','Error: Transaction is already voided');
		
		redirect('cashier/cashier_mode');
	}
	

	private function gain_reward_points($membership_id, $inv_no, $price, $func = null)
	{	
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

	}
	private function use_reward_points($membership_id, $inv_no, $use_pts, $func = null)
	{
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
	}
	
	public function process_qty($func, $invoice_no, $sku, $user_qty)
	{
		if($func == 'add')
		{
			$product_qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
			$data['quantity'] = $product_qty - $user_qty;
			$prod_id = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku);
			$this->mdl_product_variant->update($prod_id, $sku, $data);
			$this->notif_main($func, $prod_id, $sku, $data['quantity']);
			
		}
		else if($func == 'void')
		{
			$items = $this->mdl_invoice_line->get_items($invoice_no);
			foreach($items as $i)
			{
				$stock = $this->mdl_product_variant->get_col_where('quantity', 'sku', $i->sku);
				$pv_data['quantity'] =  $stock + $i->qty;
				$prod_id = $this->mdl_product_variant->get_col_where("prod_id", "sku", $i->sku);
				$this->mdl_product_variant->_update('sku', $i->sku, $pv_data);
				$this->notif_main($func, $prod_id, $i->sku, $i->qty);
			}
		}
	
	}
	//<?php if(isset($sales_date) && strtotime($sales_date) != 0) echo date('Y-m-d',strtotime($sales_date));
	
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
			$this->form_validation->set_message(__FUNCTION__, 'Invalid discounted price. Discounted Price cannot be greater than selling price.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	
	
	//if discounted price per item has value  and cash doesn't match total price 
	//if cash != total price
	
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
	public function get_points()
	{
		$id = $this->input->post('ajax_membership_id');
		$data['points'] = $this->mdl_rewards_card->get_points($id);
		print json_encode($data);  
	}
	public function get_prod_codes()
	{
		$s = $this->input->post('ajax_prod_code');
		print json_encode($this->mdl_product_variant->get_skus($s));  
		/*
		if($this->mdl_product_variant->exists('sku', $s))
		{
			
			$qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
			if($qty > 0)
				print json_encode($this->mdl_product_variant->get_sale_prod_info($s));  
			else
			{
				$data['error'] = 'No available stock.';
				print json_encode($data); 
			}
		}	
		else
		{
			$data['error'] = "Product code doesn't exist.";
			print json_encode($data); 
		}
*/		
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
	public function check_qty()
	{
		$sku = $this->input->post('ajax_sku');
		$user_qty = $this->input->post('ajax_qty');
		$stock = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		
		if($user_qty > $stock)
		{
			$data['error'] = true;	
			$data['msg'] = 'Invalid Quantity. Quantity exceeds stock.';
		}
		else
			$data['error'] = false;
		//ob_clean();		
		print json_encode($data);
		//die();
	}
	
}
