<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Rewards_Card extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblreward_card";
    }	
	
	function getReward()
	{
		$user_id = $this->session->userdata('gb_user_id');
		$sql =  "SELECT receipt_price FROM tblreward_card WHERE user_id = ?";
		$sql_result = $this->db->query($sql,array($user_id));
		return $sql_result->row_array();
	}
	
	function create_card_for_user($card_num,$membership_id,$registration_date,$expiration_date)
	{
		$user_id = $this->session->userdata('gb_user_id');
		$sql ="INSERT INTO tblreward_card VALUES (?,?,?,?,?,0,0)";
		$this->db->query($sql,array($user_id,$card_num,$membership_id,$registration_date,$expiration_date));
		echo 'accepted';
	}
	
	function get_card_holders()
	{
		$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, RC.expiration_date, RC.reward_points, 
				IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
				FROM `tblreward_card` RC
				LEFT JOIN tbluser U ON U.id = RC.user_id";
		$q = $this->db->query($sql);
		return $q->result();
	}	
	function get_card_details($card_no)
	{
		$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, 
				U.email, C.contact_no,
				RC.expiration_date, RC.reward_points, 
				IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
				FROM `tblreward_card` RC
				LEFT JOIN tbluser U ON U.id = RC.user_id
				LEFT JOIN tblcustomer C ON C.user_id = RC.user_id
				WHERE RC.card_no = ?";
		$q = $this->db->query($sql, array((double)$card_no));
		return $q->result();
	}
		
	function get_application_details($card_no)
	{
		$sql = "SELECT RC.card_no, RC.membership_id, U.firstname, U.lastname, U.gender, 
				U.email, C.contact_no,  RC.date_registered, RC.reward_points, C.shipping_address, C.shipping_city, C.shipping_state, C.shipping_country, C.shipping_zipcode, C.contact_no, C.DOB
				FROM `tblreward_card` RC
				LEFT JOIN tbluser U ON U.id = RC.user_id
				LEFT JOIN tblcustomer C ON C.user_id = RC.user_id
				WHERE RC.card_no = ?";
		$q = $this->db->query($sql, array((double)$card_no));
		return $q->result();
	}
	function get_card_transactions($card_no)
	{
		$sql = "SELECT I.invoice_no, CT.reward_points, CT.transaction_type, I.date, 
			IF(I.is_sold_from_store = 1, 'STORE', 'ONLINE') AS sold_from FROM tblinvoice I 
			LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no WHERE card_no = ?;";
		$q = $this->db->query($sql, array((double)$card_no));
		return $q->result();
	}
	function can_delete($card_no)
	{
		$sql = "SELECT I.invoice_no FROM tblinvoice I 
				LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no WHERE card_no = ?";
		$q = $this->db->query($sql, array((double)$card_no));
		if ($q->num_rows() >= 1)
			return false;
		else
			return true;
	}

	function get_membership_id($date_registered)
	{
		
		$sql = "SELECT MAX(SUBSTR(`membership_id`, 6, 5) + 0) AS m FROM `tblreward_card` 
				WHERE SUBSTR(`membership_id`, 1, 4) = YEAR(?)";
		$q = $this->db->query($sql, array($date_registered));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
		{
			$id = sprintf("%05s", $data['m'] + 1);
			return date('Y', strtotime($date_registered)) . '-'. $id  . '-GB-0';
		}	
	}
	function make_datatables()
	{  
		
        if(isset($_POST["length"]) && $_POST["length"] != -1)  
        {  
			$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, RC.expiration_date, RC.reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					WHERE CONCAT(RC.card_no, RC.membership_id, U.firstname, U.lastname) LIKE ? 
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, CAST(digits(SUBSTR(`membership_id`, 5, 10)) AS INT) DESC LIMIT ?, ?";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, RC.expiration_date, RC.reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					WHERE CONCAT(RC.card_no, RC.membership_id, U.firstname, U.lastname) LIKE ? 
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, CAST(digits(SUBSTR(`membership_id`, 5, 10)) AS INT) DESC";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		
		}
        return $q->result();  
    }  
    function get_filtered_data()
	{  
       $sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, RC.expiration_date, RC.reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, CAST(digits(SUBSTR(`membership_id`, 5, 10)) AS INT) DESC";
		$q = $this->db->query($sql);
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ', U.lastname) AS name, RC.expiration_date, RC.reward_points,
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, CAST(digits(SUBSTR(`membership_id`, 5, 10)) AS INT) DESC";
		$q = $this->db->query($sql);
		return $q->num_rows();
    }  
	
}

