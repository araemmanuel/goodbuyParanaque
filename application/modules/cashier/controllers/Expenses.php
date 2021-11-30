<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Expenses extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("cashier");	
		$this->load->model('mdl_expenses');
		$this->load->model('mdl_pos');
	}
	public function index()
    {
		if (!$this->mdl_pos->pos_has_ended() && $this->is_allowed_access('cashier')) 
		{
			$data['all_products'] = $this->mdl_product_variant->get_pvs_for_sales_mng();
			$data['content'] = $this->load->view('vw_cashier_mode', $data, TRUE);	
			$this->load->view('vw_cashier_home', $data);	
		}
	}
	public function add()
	{
		$this->form_validation->set_rules('payout-desc', 'Description', 'trim|required');
		$this->form_validation->set_rules('payout-amt', 'Amount', 'trim|required|callback_amt_check');
		
		if ($this->form_validation->run($this) == FALSE) 
		{	
			$data["desc"] = form_error("payout-desc");
			$data["amt"] = form_error("payout-amt");
			$data["error"] = true;	
		}
		else
		{		
			$data['exp_date'] = $_SESSION['date'];
			$data['exp_desc'] = $this->input->post('payout-desc');
			$data['exp_amt'] = $this->input->post('payout-amt');	
			if($this->mdl_expenses->_insert($data))
				$this->session->set_flashdata('alert_msg','Payout Added Successfully!');
			$data["success"]= true;	
		}
		print json_encode($data);
	}
	public function amt_check($s)
	{
			if ($s == 0)
			{
				$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
				return FALSE;
			}
			else if ($s < 0)
			{
				$this->form_validation->set_message(__FUNCTION__, 'Invalid amount.');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
	}
}
