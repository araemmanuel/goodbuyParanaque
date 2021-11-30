<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_User extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbluser";
    }
	//admin
	function get_user($id){
		$sql = "SELECT * FROM tbluser WHERE id = ?";
		$q = $this->db->query($sql, array($id));
       return $q->row();
    }
	function get_users(){
		$q = $this->db->query("SELECT id, role, username, CONCAT(firstname, ' ', lastname) AS name FROM tbluser ORDER BY role ASC, username ASC");
       return $q->result();
    }
	function get_roles(){
		$q = $this->db->query("SELECT DISTINCT role FROM tbluser WHERE NOT lower(role) = 'customer'");
       return $q->result();
    }
	function can_delete($user_id)
	{
		$sql = "SELECT * FROM tblcustomer WHERE user_id = ?";
		$q = $this->db->query($sql, array($user_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}
	function exists2($fname, $mname, $lname, $dob)
	{
		if($mname=='' || empty($mname) || !$mname)
		{
			$sql = "SELECT U.id 
				FROM tbluser U 
				INNER JOIN tblcustomer C ON C.user_id = U.id
				WHERE CONCAT(U.firstname, U.lastname, C.DOB) = CONCAT(?,?,?) AND U.middlename IS NULL;";
			$q = $this->db->query($sql, array($fname, $lname, $dob));
		}
		else
		{
			$sql = "SELECT U.id 
				FROM tbluser U 
				INNER JOIN tblcustomer C ON C.user_id = U.id
				WHERE CONCAT(U.firstname, U.middlename, U.lastname, C.DOB) = CONCAT(?,?,?,?)";
			$q = $this->db->query($sql, array($fname, $mname, $lname, $dob));
		}	
		if ($q->num_rows() >= 1){
           return true;
        }else{ 
           return false;
        }
	}
	//login
	function verify_sign_up($username)
	{
		$sql = "SELECT * FROM tbluser WHERE username = ?";
		$q = $this->db->query($sql, array($username));
		if ($user->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
    function can_login($username, $password, $role)  
    { 
		$sql = "SELECT * FROM tbluser WHERE username = BINARY ? AND role =  ?";
		$query = $this->db->query($sql, array($username, $role));
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
		$sql = "SELECT * FROM tbluser WHERE lower(role) = lower(?) AND  username = ?";
		$q = $this->db->query($sql, array($role, $username));
        if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
    }
    
    function get_role($username)
    {   
		$sql = "SELECT R.role_name FROM tbl_user_role UR".
					" JOIN tbl_user U ON U.user_id = UR.user_id".
                    " JOIN tbl_role R ON R.role_id = UR.role_id".
                    " WHERE U.user_username = ?";
		$q = $this->db->query($sql, array($username));
        $data = $q->result_array();
        if(empty($data))
            return null;
        else
            return $data[0];
    }	
	function username_edit_check($id, $str)
	{
		$sql = "SELECT * FROM tbluser WHERE NOT id = ? AND username = ?";
		$q = $this->db->query($sql, array($id, $str));
        if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}	
	function email_exists($id, $str)
	{
		$sql = "SELECT * FROM tbluser WHERE NOT id = ? AND email = ?";
		$q = $this->db->query($sql, array($id, $str));
        if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }		
	}		
}

