<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rewards_Card extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("admin");	
		$this->load->model('mdl_user');
		$this->load->model('mdl_customer');
		$this->load->model('mdl_rewards_card');
		$this->load->helper("card_generator");
		$this->load->helper("card_validator");
	}
	public function index()
    {
		$this->load_card_home();
	}
	private function load_card_home()
	{		
		$data['card_holders'] = $this->mdl_rewards_card->get_card_holders();
		$content =  $this->load->view('vw_admin_rewards_card', $data, TRUE);	
		$this->load_view($content);	
	}
	public function test($s)
	{
		$id = sprintf("%05s", $s + 1);
		echo date('Y') . '-'. $id  . '-TG-0';
	}
	public function add_card_holder()
	{
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|callback_letter_space');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|callback_letter_space');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbluser.email]');
		$this->form_validation->set_rules('address', 'Shipping Address', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('zipcode', 'Shipping Zipcode', 'trim|required|integer');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|callback_age_check');
		$this->form_validation->set_rules('contact', 'Contact No.', 'trim|required');
		$this->form_validation->set_rules('register-date', 'Register Date', 'trim|required');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->cust_add_form();
		}
		else
		{		
			$this->process_add();				
			redirect('admin/rewards_card/cust_add_form');
		}
	}
	private function process_add()
	{
		$user_data = $this->get_user_data();
		$cust_data = $this->get_cust_data();
		$card_data = $this->get_card_data();
	
		//Insert to tblUser
		$cust_data['user_id'] = $this->mdl_user->_insert($user_data);
		
		//Insert to tblCustomer
		$this->mdl_customer->_insert($cust_data);
		
		//Insert to tblReward Card
		$card_data['user_id'] = $cust_data['user_id'];
		$this->mdl_rewards_card->_insert($card_data);
		
		//Update Username
		$cust2_data['username'] = $card_data['card_no'];
		$this->mdl_user->_update('id', $cust_data['user_id'], $cust2_data);
		$this->session->set_flashdata('alert_msg','Card Holder Added Successfully!');

	}
	public function edit_card_holder()
	{
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|callback_letter_space');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|callback_letter_space');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_edit_check');
		$this->form_validation->set_rules('address', 'Shipping Address', 'trim|required');
		$this->form_validation->set_rules('zipcode', 'Shipping Zipcode', 'trim|required|integer');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|callback_age_check');
		$this->form_validation->set_rules('contact', 'Contact No.', 'trim|required');
		$this->form_validation->set_rules('register-date', 'Register Date', 'trim|required');
		$card_no = $this->input->post('card-no');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->cust_edit_form($card_no);
		}
		else
		{		
			$this->process_edit($card_no);		
			redirect('admin/rewards_card');
		}
	}
	private function process_edit($card_no)
	{
		$user_data = $this->get_user_data();
		$cust_data = $this->get_cust_data();
		$card_data = $this->get_card_data($card_no);
	
		//Update tblUser
		$user_id = $this->mdl_rewards_card->get_col_where('user_id', 'card_no', $card_no);
		$this->mdl_user->_update('id', $user_id, $user_data);
		
		//Update tblCustomer
		$this->mdl_customer->_update('user_id', $user_id, $cust_data);
		
		//Update tblReward Card
		$this->mdl_rewards_card->_update('card_no', $card_no, $card_data);
		
		//Update Username
		$cust2_data['username'] = $card_no;
		$this->mdl_user->_update('id', $user_id, $cust2_data);
		$this->session->set_flashdata('alert_msg','Card Holder Edited Successfully!');

	}
	public function email_edit_check($str)
	{
		$card_no = $this->input->post('card-no');
		$user_id = $this->mdl_rewards_card->get_col_where('user_id', 'card_no', $card_no);
		if($this->mdl_user->exists('email', $str))
		{
			$email_user_id = $this->mdl_user->get_col_where('id', 'email', $str);
			if($user_id != $email_user_id)
			{
				$this->form_validation->set_message(__FUNCTION__, '{field} already exists.');
				return FALSE;
			}
			else
				return TRUE;
		}
		else
		{
			return TRUE;
		}
	}
	public function age_check($dob)
	{
		$d1 = new DateTime($dob);
		$d2 = new DateTime(date("Y-m-d"));
		$diff = $d2->diff($d1);
		if($diff->y < 15)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid date of birth. Only 15 years old and above can avail the rewards card.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}


	}

	//On delete
	// delete on tblreward_card then on tblUser hopefully this cascades to tblCustomer
	public function del_card_holder($id)
	{
		if($this->mdl_rewards_card->can_delete($id))
		{	
			$user_id = $this->mdl_rewards_card->get_col_where('user_id', 'card_no', $id);
			if($this->mdl_rewards_card->_delete('card_no', $user_id) && $this->mdl_user->_delete('id', $user_id))
				$this->session->set_flashdata('alert_msg','Card Holder Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Card holder cannot be deleted.');
		}
		redirect('admin/rewards_card');
	}
	public function batch_print()
	{
		$this->load->library('PDF');
		$this->load->library('ciqrcode');
		$card_no_list = $this->input->post('chk-reward-card[]');
		$temp = $card_data['batch_card'] = array();
		if(count($card_no_list) >= 1)
		{
			foreach($card_no_list as $i => $val)
			{
				$ods = $this->mdl_rewards_card->get_card_details($val);
				foreach($ods as $od => $o)
				{
					$temp[$od] = $o;
				}
				array_push($card_data['batch_card'], $temp);
			}
		}	
		$card_data['is_batch_print'] = true;
		$this->load->view('vw_admin_print_card', $card_data);
		
	}
	//pages

	public function cust_add_form()
	{
		$content =  $this->load->view('vw_admin_add_cust', NULL, TRUE);	
		$this->load_view($content);	
	}
	public function cust_edit_form($card_no)
	{
		$data['app_details'] = $this->mdl_rewards_card->get_application_details($card_no);
		$content =  $this->load->view('vw_admin_edit_cust', $data, TRUE);	
		$this->load_view($content);	
	}
	public function cust_app_form()
	{
		$data['qty'] = $this->input->post('qty');
		$this->load->view('vw_admin_cust_app_form', $data);	
		
	}
	public function card_details($card_no)
	{
		$data['card_info'] = $this->mdl_rewards_card->get_card_details($card_no);
		$data['card_transactions'] = $this->mdl_rewards_card->get_card_transactions($card_no);		
		$content = $this->load->view('vw_reward_card_details', $data, TRUE);	
		$this->load_view($content);	
	}
	
	public function print_reward_card($card_no, $membership_id)
	{
		$this->load->library('PDF');
		$this->load->library('ciqrcode');
		$data['card_no'] = $card_no;
		$data['membership_id'] = $membership_id;
		$data['card_details'] = $this->mdl_rewards_card->get_card_details($card_no);
		
		$data['membership_id'] = $membership_id;
		$data['is_batch_print'] = false;
		$this->load->view('vw_admin_print_card', $data);		
	}
	//Other functions
	function get_card_holders()
	{  
		   $fetch_data = $this->mdl_rewards_card->make_datatables();  
           $data = array();  
           foreach($fetch_data as $c)  
           {  
                $sub_array = array();   
				$sub_array[] = '<input type="checkbox" name="chk-reward-card[]" value="'.$c->card_no.'"/>';
				$sub_array[] = $c->card_no;
				$sub_array[] = $c->membership_id;
				$sub_array[] = $c->name; 				
                $sub_array[] = date('M d, Y', strtotime(html_escape($c->expiration_date)));				
                $sub_array[] = $c->reward_points;  
				$sub_array[] = $c->status;  
				$sub_array[] = '<a href="'.base_url('admin/rewards_card/card_details/'.$c->card_no).'" class="btn btn-xs bg-default waves-effect">View</a>
								<a href="'.base_url('admin/rewards_card/cust_edit_form/'.$c->card_no).'" class="btn btn-xs bg-green waves-effect" >Edit</a>
								<button type="button" class="btn btn-xs bg-default waves-effect confirm" 
									data-title="Are you sure you want to delete this card holder?" data-msg="This action cannot be undone." 
									data-url="'.base_url('admin/rewards_card/del_card_holder/'.$c->card_no).'" >Delete</button>
								<button type="button" class="btn btn-xs bg-green waves-effect open-print-window" data-href="'.base_url('admin/rewards_card/print_reward_card/'.$c->card_no.'/'.$c->membership_id).'">Print Card</button>';
				 $data[] = $sub_array;  
           }  
		   if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_rewards_card->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_rewards_card->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);  
		   
    } 
	function get_user_data()
	{
		$user_data['firstname'] = ucwords($this->input->post('firstname'));
		$user_data['lastname'] = ucwords($this->input->post('lastname'));
		$user_data['gender'] = $this->input->post('gender');
		$user_data['email'] = $this->input->post('email');
		$user_data['role'] = 'customer';
		return $user_data;
	}
	function get_cust_data()
	{
		$cust_data['shipping_address'] = $this->input->post('address');
		$cust_data['shipping_city'] = $this->input->post('city');
		$cust_data['shipping_state'] = $this->input->post('state');
		$cust_data['shipping_country'] = $this->input->post('country');
		$cust_data['shipping_zipcode'] = $this->input->post('zipcode');
		$cust_data['contact_no'] = $this->input->post('contact');
		$cust_data['DOB'] = $this->input->post('dob');
		return $cust_data;
	}
	function get_card_data($card_no = null)
	{
		
		
		$card_data['date_registered'] = $this->input->post('register-date');
		if($card_no)
		{
			$orig_date_registered = $this->mdl_rewards_card->get_col_where('date_registered', 'card_no', $card_no);
			if($orig_date_registered == $card_data['date_registered'])
			{
				$card_data['membership_id'] = $this->mdl_rewards_card->get_col_where('membership_id', 'card_no', $card_no);
			}
			else
				$card_data['membership_id'] = $this->mdl_rewards_card->get_membership_id($card_data['date_registered']);
		}
		else
		{
			$card_no = new Card_Generator();
			$invalid_card_no = true;
			while($invalid_card_no)
			{
				$card_data['card_no'] = $card_no->single(601, 11);
				if($this->mdl_rewards_card->exists('card_no', $card_data['card_no']))
					$invalid_card_no = true;
				else
					break;
			}
			$card_data['membership_id'] = $this->mdl_rewards_card->get_membership_id($card_data['date_registered']);
		}
		$card_data['expiration_date'] = date('Y-m-d', strtotime('+1 year', strtotime($card_data['date_registered'])));
		return $card_data;
	}
}
