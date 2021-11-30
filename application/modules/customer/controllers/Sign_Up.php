<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sign_Up extends My_Controller {
     
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->model('mdl_user');		
    }

    public function index()
    {	
		$this->form_validation->set_rules('register_username', 'Username', 'trim|required|is_unique[tbluser.username]');
        $this->form_validation->set_rules('register_password', 'Password', 'trim|required|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('register_confirm_password', 'Confirm password', 'trim|required|matches[register_password]');
        $this->form_validation->set_rules('register_email', 'Email', 'trim|required|valid_email|is_unique[tbluser.email]');

        if ($this->form_validation->run() == FALSE)
        { 
            $this->session->set_flashdata('get_user_post', $this->get_user_post(true));		
        }
        else
        {			
			if($this->mdl_user->_insert($this->get_user_post(true)))
				$this->session->set_flashdata('sign_up_success','Successfully signed up!');
			$this->session->set_flashdata('get_user_post', $this->get_user_post(false));

        }
		$this->load->view('vw_cust_login');
	}   
    private function get_user_post($getData)
    {
		if($getData)
		{
			$data['password_hash'] = password_hash($this->input->post('register_password', TRUE), PASSWORD_BCRYPT); 	
			$data['username'] = $this->input->post('register_username');
			$data['email'] = $this->input->post('register_email');   
			$data['role'] = 'customer';   			
		}
		else
		{
			$data['password_hash'] = null; 	
			$data['username'] =null;
			$data['email'] = null;                 		
		}
		return $data;       
    }    
}
