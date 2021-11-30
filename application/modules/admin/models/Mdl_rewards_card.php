<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Rewards_Card extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblreward_card";
    }	
	function is_expired($membership_id)
	{
		$sql = "SELECT membership_id FROM `tblreward_card` RC WHERE CURDATE() > RC.expiration_date AND membership_id = ?";
		$q = $this->db->query($sql, array($membership_id));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function get_points($membership_id)
	{
		$sql = "SELECT FLOOR(receipt_price/200) AS pts FROM `tblreward_card` 
					WHERE membership_id = ? ";
		$q = $this->db->query($sql, array($membership_id));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
			return $data['pts'];
			
	}
	function get_card_nos($srch)
	{
			$sql = "
				SELECT 
				IF(U.middlename IS NULL, 
				   CONCAT(R.card_no, ',', FLOOR(R.receipt_price/200), ',', U.firstname, ' ', U.lastname, ' <b>Bday:</b> ', DATE_FORMAT(C.DOB, '%b %e %Y')), 
				   CONCAT(R.card_no, ',', FLOOR(R.receipt_price/200), ',', U.firstname, ' ', U.middlename, ' ', U.lastname, ' <b>Bday:</b> ', DATE_FORMAT(C.DOB, '%b %e %Y'))) FROM `tblreward_card` R 
					INNER JOIN tbluser U ON U.id = R.user_id 
                    INNER JOIN tblcustomer C ON C.user_id = R.user_id
					WHERE U.lastname LIKE ? OR U.firstname LIKE ? OR U.middlename LIKE ? 
				UNION
				SELECT CONCAT(membership_id, ',', FLOOR(receipt_price/200)) FROM `tblreward_card` 
					WHERE membership_id LIKE ? 
				UNION
				SELECT CONCAT(card_no, ',', FLOOR(receipt_price/200)) FROM `tblreward_card` 
					WHERE card_no LIKE ?
				";
		$q = $this->db->query($sql, array('%'.$srch.'%', '%'.$srch.'%', '%'.$srch.'%', '%'.$srch.'%', '%'.$srch.'%'));
		return $q->result();
	}	
	function get_membership_id2($id)
	{
		$sql = "SELECT CONCAT(membership_id, ',', FLOOR(receipt_price/200)) FROM `tblreward_card` WHERE card_no = ?";
		$q = $this->db->query($sql, array($id));
		return $q->result();
	}
	function get_card_holders()
	{
		$sql = "SELECT RC.card_no, RC.membership_id, CONCAT(U.firstname, ' ',U.middlename, ' ', U.lastname) AS name, RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
				IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
				FROM `tblreward_card` RC
				LEFT JOIN tbluser U ON U.id = RC.user_id";
		$q = $this->db->query($sql);
		return $q->result();
	}	
	function get_card_details($card_no)
	{
		$sql = "SELECT RC.card_no, RC.membership_id, IF(U.middlename IS NULL, CONCAT(U.firstname, ' ',  U.lastname),CONCAT(U.firstname, ' ', U.middlename, ' ', U.lastname)) AS name, 
				U.email, C.contact_no,
				RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
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
		$sql = "SELECT RC.card_no, RC.membership_id, U.firstname, U.lastname, U.middlename, U.gender, 
				U.email, C.contact_no,  RC.date_registered,  FLOOR(RC.receipt_price/200) AS reward_points, C.shipping_address, C.shipping_city, C.shipping_state, C.shipping_country, C.shipping_zipcode, C.contact_no, C.DOB
				FROM `tblreward_card` RC
				LEFT JOIN tbluser U ON U.id = RC.user_id
				LEFT JOIN tblcustomer C ON C.user_id = RC.user_id
				WHERE RC.card_no = ?";
		$q = $this->db->query($sql, array((double)$card_no));
		return $q->result();
	}
	function get_card_transactions($card_no)
	{
		$sql = "SELECT I.invoice_no, CT.gained_reward_pts, CT.used_reward_pts, I.date, 
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
	function can_delete2($card_no)
	{
		$sql = "SELECT I.invoice_no FROM tblinvoice I 
				WHERE  I.cust_id = ?;";
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
	function get_card_holders_qry()
	{
		if(isset($_POST["length"]) && $_POST["length"] != -1)  
        {  
			$sql = "SELECT RC.card_no, RC.membership_id, IF(U.middlename IS NULL, CONCAT(U.firstname, ' ', U.lastname), CONCAT(U.firstname, ' ',U.middlename,' ', U.lastname) )  AS name, RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					WHERE CONCAT(RC.card_no, RC.membership_id, U.firstname, U.lastname) LIKE ? 
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, (SUBSTR(`membership_id`, 6, 5) + 0) DESC LIMIT ?, ?";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT RC.card_no, RC.membership_id, IF(U.middlename IS NULL, CONCAT(U.firstname, ' ', U.lastname), CONCAT(U.firstname, ' ',U.middlename,' ', U.lastname) )  AS name, RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					WHERE CONCAT(RC.card_no, RC.membership_id, U.firstname, U.lastname) LIKE ? 
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, (SUBSTR(`membership_id`, 6, 5) + 0) DESC";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		
		}
		return $q;
	}
	function make_datatables()
	{  
		$q = $this->get_card_holders_qry();
        return $q->result();  
    }  
    function get_filtered_data()
	{  
       $sql = "SELECT RC.card_no, RC.membership_id, IF(U.middlename IS NULL, CONCAT(U.firstname, ' ', U.lastname), CONCAT(U.firstname, ' ',U.middlename,' ', U.lastname) )  AS name, RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					WHERE CONCAT(RC.card_no, RC.membership_id, U.firstname, U.lastname) LIKE ? 
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, (SUBSTR(`membership_id`, 6, 5) + 0) DESC";
		$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT RC.card_no, RC.membership_id, IF(U.middlename IS NULL, CONCAT(U.firstname, ' ', U.lastname), CONCAT(U.firstname, ' ',U.middlename,' ', U.lastname) )  AS name, RC.expiration_date,  FLOOR(RC.receipt_price/200) AS reward_points, 
					IF(CURDATE() > RC.expiration_date, 'EXPIRED', 'VALID') AS status
					FROM `tblreward_card` RC
					LEFT JOIN tbluser U ON U.id = RC.user_id
					ORDER BY SUBSTR(`membership_id`, 1, 4) DESC, (SUBSTR(`membership_id`, 6, 5) + 0) DESC";
		$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		return $q->num_rows();
    }  
	
}

