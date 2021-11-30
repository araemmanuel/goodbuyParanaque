<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Expenses extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("admin");	
		$this->load->model('mdl_expenses');
		$this->load->helper('file');
		ob_clean();
	}
	public function index()
    {
		$this->show_expenses_page();
	}
	private function show_expenses_page()
	{
		$data['expenses'] = $this->mdl_expenses->get_expenses();
		$content = $this->load->view('vw_admin_expenses', $data, TRUE);
		$this->load_view($content);
	}
	public function add()
	{
		// $data['test'] = $_FILES['receipt']['name'];
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('desc', 'Description', 'trim|required');
		$this->form_validation->set_rules('amt', 'Amount', 'trim|required|callback_amt_check');
		$this->form_validation->set_rules('receipt', '', 'callback_file_check');
		
			if ($this->form_validation->run($this) == FALSE) 
		{		
			$data['date'] = form_error('date');
			$data['desc'] = form_error('desc');
			$data['amt'] = form_error('amt');
			$data['receipt'] = form_error('receipt');
			$data['error'] = true;
		}
		else
		{		
			$uploadedFile = "";
			if($_FILES['receipt']['size'] != 0){
				//upload configuration
				$config['upload_path']   = 'uploads/Receipts/';
				$config['allowed_types'] = 'jpg|png';
				$config['max_size']      = 2048;
				$this->load->library('upload', $config);
				//upload file to directory
				if($this->upload->do_upload('receipt')){
					$uploadData = $this->upload->data();
					$uploadedFile = $uploadData['file_name'];

				}
			}
				/*
				*insert file information into the database
				*.......
				*/
				$this->log_data("Expense Add", $this->input->post('desc') . " expense was added.");	
				if($this->mdl_expenses->_insert($this->get_expense_post($uploadedFile)))
				$this->session->set_flashdata('alert_msg','Expense Added Successfully!');
				$data['success'] = true;
		}
		print json_encode($data);
	}
	public function edit()
	{
		$this->form_validation->set_rules('modal-exp_date', 'Date', 'trim|required');
		$this->form_validation->set_rules('modal-exp_desc', 'Description', 'trim|required');
		$this->form_validation->set_rules('modal-exp_amt', 'Amount', 'trim|required|callback_amt_check');
		$this->form_validation->set_rules('modal-exp_receipt', '', 'callback_file_check_edit');
		
		if ($this->form_validation->run($this) == FALSE) 
		{	
			$data["m_exp_date"] = form_error("modal-exp_date");
			$data["m_exp_desc"] = form_error("modal-exp_desc");
			$data["m_exp_amt"] = form_error("modal-exp_amt");
			$data["m_exp_receipt"] = form_error("modal-exp_receipt");

			$data["error"] = true;	
		}
		else
		{
			$uploadedFile = "";
			if($_FILES['modal-exp_receipt']['size'] != 0){
				//get current image then delete
				$rece = $this->mdl_expenses->get_col_where('exp_receipt', 'exp_id', $this->input->post('modal-exp_id'));
				unlink($rece);
				//upload configuration
				$config['upload_path']   = 'uploads/Receipts/';
				$config['allowed_types'] = 'jpg|png';
				$config['max_size']      = 2048;
				$this->load->library('upload', $config);
				//upload file to directory
				if($this->upload->do_upload('modal-exp_receipt')){
					$uploadData = $this->upload->data();
					$uploadedFile = $uploadData['file_name'];

				}
			}

			$exp_id = $this->input->post('modal-exp_id');
			$value['exp_date'] = $this->input->post('modal-exp_date');
			$value['exp_desc'] = $this->input->post('modal-exp_desc');
			$value['exp_amt'] = $this->input->post('modal-exp_amt');
			if($uploadedFile != "")
				$value['exp_receipt'] = "uploads/Receipts/".$uploadedFile;
			$this->log_data("Expense Edit", $value['exp_desc'] . " expense for ".date('M d, Y', strtotime($value['exp_date']))." was edited.");	
			if($this->mdl_expenses->_update('exp_id', $exp_id, $value))
				$this->session->set_flashdata('alert_msg','Expense Edited Successfully!');
			
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
	public function del($id)
	{
		$desc = $this->mdl_expenses->get_col_where('exp_desc', 'exp_id', $id);
		$date = $this->mdl_expenses->get_col_where('exp_date', 'exp_id', $id);
		$rece = $this->mdl_expenses->get_col_where('exp_receipt', 'exp_id', $id);
		$this->log_data("Expense Delete", $desc . " expense for ".date('M d, Y', strtotime($date))." was deleted.");	
		if($this->mdl_expenses->_delete('exp_id', $id)) {
			unlink($rece);
			$this->session->set_flashdata('alert_msg','Expense Deleted Successfully!');
		}
		redirect('admin/expenses');
	}
	private function get_expense_post($fileName)
	{
		$exp_data['exp_date'] = $this->input->post('date');
		$exp_data['exp_desc'] = $this->input->post('desc');
		$exp_data['exp_amt'] = $this->input->post('amt');
		$exp_data['exp_receipt'] = ($fileName != "") ? 'uploads/Receipts/'.$fileName : "";
		return $exp_data;
	}
	public function get_expense($id)
	{
		print json_encode($this->mdl_expenses->get_expense($id));  
	}

	public function file_check($str){
        $allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png');
		$mime = get_mime_by_extension($_FILES['receipt']['name']);
        if(isset($_FILES['receipt']['name']) && $_FILES['receipt']['name']!=""){
			if(file_exists('uploads/Receipts/'.$_FILES['receipt']['name'])) {
				$this->form_validation->set_message('file_check', 'File name already exists. Please rename.');
            	    return false;
			}
			else {
            	if(in_array($mime, $allowed_mime_type_arr)) {
            	    return true;
            	}else{
            	    $this->form_validation->set_message('file_check', 'Please select only jpg/png file.');
            	    return false;
				}
			}
        }
	}
	
	public function file_check_edit($str){
        $allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png');
		$fileName = $_FILES['modal-exp_receipt']['name'];
		$mime = get_mime_by_extension($fileName);
        if(isset($fileName) && $fileName!=""){
			if(file_exists('uploads/Receipts/'.$fileName)) {
				$this->form_validation->set_message('file_check_edit', 'File name already exists. Please rename.');
            	    return false;
			}
			else {
            	if(in_array($mime, $allowed_mime_type_arr)) {
            	    return true;
            	}else{
            	    $this->form_validation->set_message('file_check_edit', 'Please select only jpg/png file.');
            	    return false;
				}
			}
        }
    }
}
