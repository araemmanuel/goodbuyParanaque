<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_Management extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("admin");	
		$this->load->model('mdl_order');
		$this->load->model('mdl_order_details');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_return_policy');
		$this->load->model('mdl_invoice');
		$this->load->model('mdl_invoice_line');
		$this->load->model('mdl_courier');
		$this->load->model('mdl_user');
		ob_clean();
	}
	public function index()
    {
		$this->customer_order();
	}

	public function deliver_order()
	{
		$this->form_validation->set_rules('modal-date-delivered', 'Date Delivered', 'trim|required');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["delivered_date"] = form_error("modal-date-delivered");
			$data["error"] = true;				
		}
		else
		{	
			$order_no = $this->input->post('modal-order-no');
			$order_data['delivered_date'] = $this->input->post('modal-date-delivered');
			$order_type = $this->mdl_order->get_col_where('order_type', 'order_no', $order_no);
			//$inv_data['']
			
			//$this->add_sale($order_data['delivered_date'], $order_no);
			
			if(strcasecmp($order_type, 'cod') == 0)
				$order_data['order_status'] = 'DELIVERED';			
			else
				$order_data['order_status'] = 'PICKED UP';
			//$this->update_qty($order_no);
			if($this->mdl_order->_update('order_no', $order_no, $order_data) )
				$this->session->set_flashdata('alert_msg',ucfirst($order_data['order_status']).' date was set successfully!');
			$this->log_data("Order Set Status", "Order No. ".$order_no." was set to ".$order_data['order_status'] . " on ".date('M d, Y', strtotime($order_data['delivered_date'])).".");	
			$data["success"]= true;
		}	
		print json_encode($data);		
	}
	public function test($price, $percent)
	{
		$discount = ($price * ((double)$percent/100));
		echo $discount;
		//get discount
		//subtract discount to original price to get discounted price
	}
	private function add_sale($deliver_date, $order_no)
	{
		//Insert to tblInvoice
		$inv_data['date'] = $deliver_date;
		$inv_data['is_issued_receipt'] = 1;
		$inv_data['is_sold_from_store'] = 0;
		//$inv_data['cash'] = $this->input->post('cash');
				
		$invoice_no = $this->mdl_invoice->_insert($inv_data);
		
		//Update tblOrder
		$order_data['invoice_no'] = $invoice_no;
		$this->mdl_order->_update('order_no', $order_no, $order_data);
		
		$order_items = $this->mdl_order->get_order_items($order_no);
		foreach($order_items as $o)
		{			
			//Insert to tblInvoice Line
			$inv_li_data['invoice_no'] = $invoice_no;
			$inv_li_data['line_no'] = $this->mdl_invoice->get_line_no($invoice_no);
			$inv_li_data['sku'] = strtoupper($o->sku);
			$inv_li_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $o->sku);
			$inv_li_data['qty'] = $o->quantity;
			$inv_li_data['purchase_price'] = $o->purchase_price;
			$inv_li_data['selling_price'] = $o->selling_price;
			if($o->discount_percent > 0)
				$inv_li_data['discount'] =($inv_li_data['qty'] * $inv_li_data['selling_price']) - ($price * ((double)$o->discount_percent/100));	
			else
				$inv_li_data['discount'] = 0;
			$inv_li_data['amt_paid'] = $this->mdl_order->get_amt_paid($order_no);
			$add2 = $this->mdl_invoice_line->_insert($inv_li_data);
			
		}
			
		//insert to tblInvoice		
		$inv2_data['cash'] = $this->mdl_invoice->get_total_invoice($invoice_no);
		$edit = $this->mdl_invoice->_update('invoice_no', $invoice_no, $inv2_data);			
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
	public function cancel_delivered_status($order_no)
	{
		$order_data['invoice_no'] = null;
		$order_data['delivered_date'] = null;
		$order_data['order_status'] = 'PENDING';
		$this->log_data("Cancel Order Status", "Canceled order status for order no. ".$order_no .".");	
		//$invoice_no = $this->mdl_order->get_col_where('invoice_no', 'order_no', $order_no);
		//$this->update_qty($order_no, true);
		if($this->mdl_order->_update('order_no', $order_no, $order_data) )//&& $this->mdl_invoice->_delete('invoice_no', $invoice_no) 
			$this->session->set_flashdata('alert_msg','Delivered status canceled successfully!');
		redirect('admin/order_management');
	}
	
	private function update_qty($order_no, $is_canceled = false)
	{
		$skus = $this->mdl_order_details->get_skus($order_no);
		foreach($skus as $s)
		{
			$stock = $this->mdl_product_variant->get_col_where('quantity', 'sku', $s->sku);
			if($is_canceled)
				$data['quantity'] = $stock + $s->quantity;
			else	
				$data['quantity'] = $stock - $s->quantity;
			
			$this->mdl_product_variant->_update('sku', $s->sku, $data);
		}
	}
	public function batch_print()
	{
		$order_no_list = $this->input->post('chk-deliver[]');
		$temp = $ctemp = $order_data['batch_orders'] = $order_data['batch_cust'] = array();
		if(count($order_no_list) >= 1)
		{
			foreach($order_no_list as $i => $val)
			{
				$ods = $this->mdl_order->get_order_details($val);
				foreach($ods as $od => $o)
				{
					$temp[$od] = $o;
				}
				array_push($order_data['batch_orders'], $temp);
				
				$cds =  $this->mdl_order->get_customer_details($val);
				foreach($cds as $cd => $c)
				{
					$ctemp[$cd] = $c;
				}
				array_push($order_data['batch_cust'], $ctemp);
			}
		}
		
		$order_data['is_batch_print'] = true;
		$this->log_data("Order Receipts Batch Print", "Generated delivery receipts for batch print.");	
		$this->load->view('vw_order_receipt', $order_data);
	}
	public function add_courier()
	{
		ob_clean();
		$this->form_validation->set_rules('cour-name', 'Courier Name', 'trim|required|callback_letter_space|is_unique[tblcourier.name]');
		$this->form_validation->set_rules('shipping-fee', 'Shipping Fee', 'trim|required|callback_fee_check');
		
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data['cour'] = form_error('cour-name');
			$data['fee'] = form_error('shipping-fee');
			$data['error'] = true;
		}
		else
		{					
			$cour_data['name'] = $this->input->post('cour-name');
			$this->log_data("Courier Add", $cour_data['name']. " courier was added.");	
			$cour_data['shipping_fee'] = $this->input->post('shipping-fee');
			if($this->mdl_courier->_insert($cour_data))
				$this->session->set_flashdata('alert_msg','Courier Added Successfully!');		
			$data['success'] = true;
		}
		print json_encode($data);
	}
	public function edit_courier()
	{
		$this->form_validation->set_rules('modal-name', 'Courier Name', 'trim|required|callback_letter_space|callback_cour_name_check');
		//validate if zero 
		$this->form_validation->set_rules('modal-shipping_fee', 'Shipping Fee', 'trim|required|callback_fee_check');
		$cour_id = $this->input->post('modal-cour_id');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["cour_name"] = form_error("modal-name");
			$data["fee"] = form_error("modal-shipping_fee");
			$data["error"] = true;	
		}
		else
		{					
			$data['name'] = $this->input->post('modal-name');
			$data['shipping_fee'] = $this->input->post('modal-shipping_fee');
			$this->log_data("Courier Edit", $data['name']. " courier was edited.");	
			if($this->mdl_courier->_update('cour_id', $cour_id ,$data))
				$this->session->set_flashdata('alert_msg','Courier Edited Successfully!');		
			$data["success"]= true;
		}	
		print json_encode($data);
	}
	public function cour_name_check($cour)
	{
		$input_cour_id = $this->mdl_courier->get_col_where('cour_id', 'name', $cour);
		$orig_cour_id = $this->input->post('modal-cour_id');
		if ($input_cour_id != $orig_cour_id && $this->mdl_courier->exists('name', $cour))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function fee_check($fee)
	{
		if ($fee <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} cannot be less than or equal to zero.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function delete_courier($cour_id)
	{
		/*$is_default = $this->mdl_courier->get_col_where('is_default', 'cour_id', $cour_id);
		//if($is_default == 0)
		{*/
			if($this->mdl_courier->can_delete($cour_id))
			{	
				$name = $this->mdl_courier->get_col_where('name', 'cour_id', $cour_id);
				$this->log_data("Courier Delete", $name. " courier was deleted.");	
				if($this->mdl_courier->_delete('cour_id', $cour_id))
				$this->session->set_flashdata('alert_msg','Courier Deleted Successfully!');
			}
			else
			{
				$this->session->set_flashdata('alert_msg','Error: Courier cannot be deleted.');
			}	
		/*}
		//else
			$this->session->set_flashdata('error_msg','Error: Courier cannot be deleted. It\'s set to default.');
		*/
		redirect('admin/order_management/manage_courier');
	}
	public function set_default_courier($cour_id)
	{
		$no_default_data['is_default'] = 0;
		$this->mdl_courier->_update('is_default', 1, $no_default_data);
		
		$yes_default_data['is_default'] = 1;
		$this->mdl_courier->_update('cour_id', $cour_id, $yes_default_data);
		$name = $this->mdl_courier->get_col_where('name', 'cour_id', $cour_id);
				$this->log_data("Set Default Courier", $name. " courier was set as default.");	
		$this->session->set_flashdata('alert_msg','Selected courier was set to default.');
		redirect('admin/order_management/manage_courier');
	}

	//PAGES
	public function customer_order()
	{
		$data['orders'] = $this->mdl_order->get_orders();
		$content = $this->load->view('vw_admin_cust_order', $data, TRUE);	
		$this->load_view($content);	
	}
	public function canceled_order()
	{
		$data['canceled_orders'] = $this->mdl_order->get_canceled_orders();
		$content = $this->load->view('vw_admin_canceled_orders', $data, TRUE);	
		$this->load_view($content);	
	}	
	public function order_items($id)
	{
		$data['orders'] = $this->mdl_order->get_orders($id);
		$data['order_details'] = $this->mdl_order->get_order_details($id);
		if(is_numeric($id))
			$this->log_data("View Order Items", "View ordered items for order no. ".$id .".");	
		$content = $this->load->view('vw_admin_order_items', $data, TRUE);	
		$this->load_view($content);			
	}
	public function receipt($order_no)
	{
		$data['order_details'] = $this->mdl_order->get_order_details($order_no);
		$data['cust_details'] = $this->mdl_order->get_customer_details($order_no);
		$data['return_policy'] = $this->mdl_return_policy->get_days();
		$data['is_batch_print'] = false;
		$this->log_data("Order Receipt Print", "Generated delivery receipt for order no ".$order_no.".");	
		//$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
		//$data['lastname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
		$this->load->view('vw_order_receipt', $data);			
	}
	public function manage_courier()
	{
		$data['couriers'] = $this->mdl_courier->get_couriers();
		$content = $this->load->view('vw_admin_courier', $data, TRUE);	
		$this->load_view($content);			
	}
}
