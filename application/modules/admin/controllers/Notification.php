<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->load->model('mdl_notification');
		$this->load->model('mdl_notification_message');
	}
	public function index()
    {	 
		if ($this->mdl_user->verify_user_role($this->get_sess_username(),$this->get_user_role())) 
		{	
			$this->dashboard();
		}
		else
		{
			redirect('admin/login');
		}
	}
	
	
	
	
}