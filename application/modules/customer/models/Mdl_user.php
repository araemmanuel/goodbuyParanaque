<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_User extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbluser";
    }
	//login
	
	function get_user_info($user_id)
	{
		$user = $this->db->query("SELECT * FROM tbluser LEFT JOIN tblcustomer ON tbluser.id = tblcustomer.user_id LEFT JOIN tblreward_card ON tblreward_card.user_id = tbluser.id WHERE username = '".$user_id."'")->row();
		return $user;
	}

    function get_col_where($col,$col_con,$val_con)
    {
        $user = $this->db->query("SELECT " . $col. " FROM tbluser WHERE " .$col_con . " = '".$val_con."'")->row()->id;
		return $user;
    }
	function verify_email($email)
	{
		$user = $this->db->query("SELECT * FROM tbluser WHERE email =" . "'" . $email . "'");
		if ($user->num_rows() >= 1){
           return "true";
        }else{ 
           return "false";
        }
	}
	
	function verify_card_details($card_no,$mem_id,$email)
	{
		$user = $this->db->query("SELECT * FROM tblreward_card WHERE card_no = ".$card_no." AND membership_id = '".$mem_id."'");
		if ($user->num_rows() >= 1){
			$user_id = $user->row()->user_id;
			$user_2 = $this->db->query("SELECT * FROM tbluser WHERE email = '".$email."' AND id = ".$user_id."");
			if ($user_2->num_rows() == 1){
			   return "true";
			}else{ 
			   return "false";
			}
        }else{ 
           return "false";
        }
	}
	
	function verify_account_details($card_no,$mem_id,$email)
	{
		$user = $this->db->query("SELECT * FROM tblreward_card WHERE card_no = ".$card_no." AND membership_id = '".$mem_id."'");
		if ($user->num_rows() >= 1){
			$user_id = $user->row()->user_id;
			$user_2 = $this->db->query("SELECT * FROM tbluser WHERE id = ".$user_id."  AND password_hash = ''");
			if ($user_2->num_rows() >= 1){
			   return "true";
			}else{ 
			   return "false";
			}
        }else{ 
           return "false";
        }
	}
	
	function verify_sign_up($username)
	{
		$user = $this->db->query("SELECT * FROM tbluser WHERE username =" . "'" . $username . "'");
		if ($user->num_rows() >= 1){
           return "true";
        }else{ 
           return "false";
        }
	}
	function verify_password_update($input_pass)
	{
		$old_pass = $this->db->query("SELECT password_hash FROM tbluser WHERE id = ".$this->session->userdata('gb_user_id'))->row()->password_hash;
		if(password_verify($input_pass, $old_pass)){  
            return "false";  
        }
		else
		{
			return "true";
		}
	}
	function verify_email_update($email)
	{
		$user = $this->db->query("SELECT * FROM tbluser WHERE email =" . "'" . $email . "'AND id <> ".$this->session->userdata('gb_user_id')."");
		if ($user->num_rows() >= 1){
           return "true";
        }else{ 
           return "false";
        }
	}
	function verify_sign_up_update($username)
	{
		$user = $this->db->query("SELECT * FROM tbluser WHERE username =" . "'" . $username . "' AND id <> ".$this->session->userdata('gb_user_id')."");
		if ($user->num_rows() >= 1){
           return "true";
        }else{ 
           return "false";
        }
	}
	
	function account_verified($username,$email)
	{
		if($this->db->query("SELECT * FROM tbluser WHERE username = ? AND email = ?",array($username,$email))->num_rows() == 1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function verify_account_details_name_and_bday($lname,$fname,$mname,$dob,$username = NULL)
	{
		if($username != NULL)
		{
			if(ctype_space($mname) || $mname == '')
			{
				$users = $this->db->query("SELECT username FROM tbluser U INNER JOIN tblcustomer AS CU ON CU.user_id = U.id WHERE lastname = ? AND firstname = ? AND middlename IS NULL AND DOB = ? AND username != ?",array($lname,$fname,$dob,$username))->result_array();
			}
			else
			{
				
				$users = $this->db->query("SELECT username FROM tbluser U INNER JOIN tblcustomer AS CU ON CU.user_id = U.id WHERE lastname = '".$lname."' AND firstname = '".$fname."' AND middlename = '".$mname."' AND DOB = '".$dob."' AND username != '".$username."'")->result_array();
			}
			if(count($users) == 0)
			{
				//return count($users);
				return "true";
			}else{
				return "false";
			}
		}
		else
		{
			if(ctype_space($mname) || $mname == '')
			{
				$users = $this->db->query("SELECT username FROM tbluser U INNER JOIN tblcustomer AS CU ON CU.user_id = U.id WHERE lastname = ? AND firstname = ? AND middlename IS NULL AND DOB = ?",array($lname,$fname,$dob))->result_array();
			}
			else
			{
				$users = $this->db->query("SELECT username FROM tbluser U INNER JOIN tblcustomer AS CU ON CU.user_id = U.id WHERE lastname = ? AND firstname = ? AND middlename = ? AND DOB = ?",array($lname,$fname,$mname,$dob))->result_array();
			}
			if(count($users) < 1)
			{
				return "true";
			}else{
				return "false";
			}
		}
		
	}
	
	function change_password_func($username,$password)
	{
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
		$this->db->query("UPDATE tbluser SET password_hash = ? WHERE username = ?",array($password_hash,$username));
		
	}
	
	
    function can_login($username, $password, $role)  
    { 
		$query = $this->db->query("SELECT * FROM tbluser WHERE username =" . "'" . $username 
								. "' AND role = " . "'" . $role . "'");
		$row = $query->row_array();
		if(password_verify($password, $row['password_hash'])){  
            return true;  
        }  
        else {  
            return false;       
        }  
    } 

	function register_new_account()
	{
		$card_no = $this->input->post('register_card_no');
		$mem_id = $this->input->post('register_membership_i');
		$user = $this->db->query("SELECT * FROM tblreward_card WHERE card_no = ".$card_no." AND membership_id = '".$mem_id."'");
		$user_id = $user->row()->user_id;
		$uname = $this->input->post('register_uname');
		$password = $this->input->post('register_password');
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
		$this->db->query("UPDATE tbluser SET password_hash = '".$password_hash."' WHERE id = ".$user_id."");
	}
	
    function create_new_account()
    {
        $shippingaddress = $this->input->post('sign_up_address');
        $username = $this->input->post('sign_up_username');
        $lastname = $this->input->post('sign_up_lastname');
        $firstname= $this->input->post('sign_up_firstname');
		$middlename= $this->input->post('sign_up_middlename');
        $password = $this->input->post('sign_up_password');
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $phone = $this->input->post('sign_up_phone');
		$age = $this->input->post('sign_up_dob');
        $gender= $this->input->post('sign_up_gender');
        $email= $this->input->post('sign_up_email');
        $shipping_address= $shippingaddress;
        $shipping_city = $this->input->post('sign_up_city');
		$shipping_state = $this->input->post('sign_up_state');
		$shipping_country = $this->input->post('sign_up_country');
		$shipping_zipcode = $this->input->post('sign_up_zip');
		if( empty($shipping_state) ) {
			$shipping_state = "  ";
		}
        $this->db->trans_start();
        $this->db->query("INSERT INTO tbluser(lastname,middlename, firstname, gender, username, password_hash, email, profile_pic_path, role,is_verified) VALUES('".$lastname."','".$middlename."','".$firstname."','".$gender."','".$username."','".$password_hash."','".$email."',NULL,'customer',0)");
        $table1_id = $this->db->insert_id();
        $query_payment = "INSERT INTO tblcustomer VALUES(".$table1_id.",'".$age."','".$shipping_address."', '".$shipping_city."', '".$shipping_state."', '".$shipping_country."', '".$shipping_zipcode."','".$phone."')";
        $this->db->query($query_payment);
        $this->db->trans_complete(); 
    }
	
	function update_profile()
	{
		$shippingaddress = $this->input->post('profile_address');
        $username = $this->session->userdata('gb_username');
        $lastname = $this->input->post('profile_lname');
        $firstname= $this->input->post('profile_fname');
		$middlename= $this->input->post('profile_mname');
        $phone = $this->input->post('profile_phone');
        $gender= $this->input->post('profile_gender');
        $email= $this->input->post('profile_email');
        $shipping_address= $shippingaddress;
        $shipping_city = $this->input->post('profile_city');
		$shipping_state = $this->input->post('profile_state');
        $shipping_zipcode = $this->input->post('profile_zipcode');
        $this->db->trans_start();
        $this->db->query("UPDATE tbluser SET lastname = '".$lastname."', firstname ='".$firstname."', middlename = '".$middlename."', email = '".$email."' WHERE username = '".$username."'");
		$user = $this->db->query("SELECT id FROM tbluser WHERE username = '".$username."'")->row()->id;
        $query_payment = "UPDATE tblcustomer SET shipping_address = '".$shipping_address."', shipping_zipcode = '".$shipping_zipcode."', contact_no = '".$phone."', shipping_city = '".$shipping_city."',shipping_state='".$shipping_state."' WHERE user_id = ".$user;
        $this->db->query($query_payment);
        $this->db->trans_complete(); 
		return 'accepted';
	}
	
	function update_profile_with_password()
	{
		$shippingaddress = $this->input->post('profile_address');
        $username = $this->session->userdata('gb_username');
		$uname = $this->input->post('profile_uname');
        $lastname = $this->input->post('profile_lname');
        $firstname= $this->input->post('profile_fname');
        $phone = $this->input->post('profile_phone');
        $email= $this->input->post('profile_email');
        $shipping_address= $shippingaddress;
		$shipping_state = $this->input->post('profile_state');
        $shipping_city = $this->input->post('profile_city');
        $shipping_zipcode = $this->input->post('profile_zipcode');
		$user = $this->db->query("SELECT id FROM tbluser WHERE username = '".$username."'")->row()->id;
		$password = $this->input->post('profile_pass_n');
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $this->db->trans_start();
        $this->db->query("UPDATE tbluser SET lastname = '".$lastname."', firstname ='".$firstname."', email = '".$email."',username = '".$uname."',password_hash = '".$password_hash."' WHERE id = '".$user."'");
		$this->session->set_userdata('gb_username', $uname);
        $query_payment = "UPDATE tblcustomer SET shipping_address = '".$shipping_address."', shipping_zipcode = '".$shipping_zipcode."', contact_no = '".$phone."', shipping_city = '".$shipping_city."',shipping_state='".$shipping_state."' WHERE user_id = ".$user;
        $this->db->query($query_payment);
        $this->db->trans_complete(); 
		return 'accepted';
	}
}

