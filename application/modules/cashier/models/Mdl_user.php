<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_User extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbluser";
    }
	//login
	function verify_sign_up($username)
	{
		$user = $this->db->query("SELECT * FROM tbluser WHERE username =" . "'" . $username . "'");
		if ($user->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
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
	function verify_role($username, $role)
    {   
       $q = $this->db->query("SELECT * FROM tblUser WHERE lower(role) = lower('". $role ."') AND  username =" . "'" . $username . "'");
        if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
    }
}

