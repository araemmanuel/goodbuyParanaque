<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_Management extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("cashier");
		$this->load->model('mdl_order');
		$this->load->model('mdl_order_details');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_return_policy');
		$this->load->model('mdl_invoice');
		$this->load->model('mdl_invoice_line');
		$this->load->model('mdl_user');
		$this->load->model('mdl_pos');	
		$this->load->model('mdl_terminal');	
		$this->load->model('mdl_shift');
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
		$inv_data['date'] = $_SESSION['date'];
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
		$invoice_no = $this->mdl_order->get_col_where('invoice_no', 'order_no', $order_no);
		//$this->update_qty($order_no, true);
		if($this->mdl_order->_update('order_no', $order_no, $order_data) )//&& $this->mdl_invoice->_delete('invoice_no', $invoice_no) 
			$this->session->set_flashdata('alert_msg','Delivered status canceled successfully!');
		redirect('cashier/pickup_order');
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
		$this->load->view('vw_order_receipt', $order_data);
	}
	//PAGES
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
	//END NECESSARY FUNCTIONS
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
		$content = $this->load->view('vw_cashier_order_items', $data, TRUE);	
		$this->load_cash_view($content);	
	}
	public function receipt($order_no)
	{
		$data['order_details'] = $this->mdl_order->get_order_details($order_no);
		$data['cust_details'] = $this->mdl_order->get_customer_details($order_no);
		$data['return_policy'] = $this->mdl_return_policy->get_days();
		$data['is_batch_print'] = false;
		//$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
		//$data['lastname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
		$this->load->view('vw_order_receipt', $data);			
	}
	
	public function manage_courier()
	{
		//$data['orders'] = $this->mdl_order->get_orders($id);
		//$data['order_details'] = $this->mdl_order->get_order_details($id);
		$content = $this->load->view('vw_admin_courier', NULL, TRUE);	
		$this->load_view($content);			
	}
}
