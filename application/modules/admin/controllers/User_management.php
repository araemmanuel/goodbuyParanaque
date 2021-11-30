<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_management extends My_Controller {

	public function __construct() 
    {
        parent::__construct();  
        $this->load->model('mdl_user'); 
		$this->load->model('mdl_customer'); 
		$this->load->model('mdl_order'); 
		$this->load->model('mdl_invoice'); 		
		$this->set_user_role("admin");	
		ob_clean();
		
	}
	function index()
    {
		$data['users'] = $this->mdl_user->get_users();
		$content = $this->load->view('vw_admin_users', $data, TRUE);
		$this->load_view($content);
	}

	function add()
	{
		ob_clean();
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('middlename', 'Middle Name', 'trim');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[tbluser.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('re-password', 'Confirm password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbluser.email]');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('role', 'Role', 'trim|callback_role_check');
		if ($this->form_validation->run() == FALSE) 
		{
			$data['lastname'] = form_error('lastname');
			$data['firstname'] = form_error('firstname');
			$data['middlename'] = form_error('middlename');
			$data['username'] = form_error('username');
			$data['password'] = form_error('password');
			$data['re-password'] = form_error('re-password');
			$data['email'] = form_error('email');
			$data['gender'] = form_error('gender');
			$data['role'] = form_error('role');
			$data['error'] = true;
		}
		else
		{
			$user_data = $this->get_user_post();
			$file = $this->single_upload('file', 'user');
			$user_data['profile_pic_path'] = $file['upload_path'];
			$this->log_data("User Add", "Added user ".$user_data['username'].'.');	
			if($this->mdl_user->_insert($user_data))
				$this->session->set_flashdata('alert_msg','User Added Successfully!');
			$data['success'] = true;
		} 
		print json_encode($data);
	}
	function edit($id)
	{
	    $this->form_validation->set_rules('lastname', 'Last Name', 'trim');
        $this->form_validation->set_rules('firstname', 'First Name', 'trim');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('re-password', 'Re-type Password', 'trim|matches[password]|min_length[5]|max_length[15]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$user_id = $this->input->post('user-id');
		if ($this->form_validation->run($this) == FALSE) 
		{
			//$this->edit_form($user_id);	
			$data['lastname'] = form_error('lastname');
			$data['firstname'] = form_error('firstname');
			$data['middlename'] = form_error('middlename');
			$data['username'] = form_error('username');
			$data['password'] = form_error('password');
			$data['re-password'] = form_error('re-password');
			$data['email'] = form_error('email');
			$data['error'] = true;		
		}
		else
		{
			$data = $this->get_user_post('edit');
			if( isset($_FILES['file']) && file_exists($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) 
			{
				$orig_img_path = $this->mdl_user->get_col_where('profile_pic_path', 'id', $user_id);
				$this->del_pic( $orig_img_path);
				$file = $this->single_upload('file', 'user');
				$data['profile_pic_path'] = $file['upload_path'];
				$_SESSION['profile'] = $file['upload_path'];
		
			}
			$orig_role = $this->mdl_user->get_col_where('role', 'id', $user_id);
			if(strcasecmp($data['role'], $orig_role) != 0 && strcasecmp('customer', $orig_role))
			{
				$this->mdl_customer->_delete('user_id', $user_id);
			}
			$username = $this->mdl_user->get_col_where('username', 'id', $user_id);
			$this->log_data("User Edit", "Edited user ".$username.'.');	
			if($this->mdl_user->_update('id', $id, $data))
				$this->session->set_flashdata('alert_msg','User Edited Successfully!');
			$data['success'] = true;
		} 
		print json_encode($data);
	}
	public function del_pic( $img_path)
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . '/GoodBuy1/' . $img_path;
		if(is_file($path))
			unlink($path);
	}
	function del($id)
	{
		$order_cust_id = $this->mdl_order->get_col_where('user_id', 'user_id', $id);
		$invoice_cust_id = $this->mdl_invoice->get_col_where('cust_id', 'cust_id', $id);
		if(!$order_cust_id && !$invoice_cust_id)
		{	
			$username = $this->mdl_user->get_col_where('username', 'id', $id);
			$this->log_data("User Delete", "Deleted user ".$username.'.');	
			if($this->mdl_user->_delete('id', $id))
				$this->session->set_flashdata('alert_msg','User Deleted Successfully!');
							
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: User cannot be deleted.');
		}
		redirect('admin/user_management');
	}
	public function username_edit_check($str)
	{
		
		if($this->mdl_user->exists('username', $str))
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
	public function email_edit_check($str)
	{
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
	public function role_check($str)
	{
		if($str == "0")
		{
			$this->form_validation->set_message(__FUNCTION__, 'The Role field is required.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	//pages
	function add_form()
	{
		$content = $this->load->view('vw_admin_add_user', NULL, TRUE);		
		$this->load_view($content);
	}
	function edit_form($id, $username = null)
	{
		if($username)
			$id = $this->mdl_user->get_col_where('id', 'username', $username);

		$data['user'] = $this->mdl_user->get_user($id); 
		$content = $this->load->view('vw_admin_edit_user', $data, TRUE);		
		$this->load_view($content);
	}
	//Extra functions
	function get_roles()
	{
		$q = $this->mdl_user->get_roles();
	    print json_encode($q);
	}
	function get_user_post($func = null)
	{
		$data['firstname']= ucwords($this->input->post('firstname'));
		$data['lastname']= ucwords($this->input->post('lastname'));
		$data['username']= $this->input->post('username');
		$data['email']= $this->input->post('email');
		$data['role']= $this->input->post('role');
		if(!(empty($this->input->post('password')) && $func == 'edit'))
			$data['password_hash']= password_hash( $this->input->post('password', TRUE), PASSWORD_BCRYPT);	
		return $data;
	}
	
}
