\<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Invoice extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblinvoice";
    }	
	//FIXED QUERIES
	// IF(CT.transaction_type = 'USED', CT.reward_points, 0) AS pts_used, I.is_sold_from_store
	function verify_if_order($invoice_no)
	{
		$sql = "SELECT * FROM tblorder WHERE invoice_no;";
		$q = $this->db->query($sql, array($invoice_no));
		if($q->num_rows() >= 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_sales($date)
	{
		$sql = "SELECT I.invoice_no, PV.sku,  P.name, IL.qty, IL.selling_price, IL.discount, IL.amt_paid, I.is_sold_from_store, 
		(SELECT  CEIL(CTT.used_reward_pts/(SELECT COUNT(ILL.line_no) FROM tblinvoice_line ILL WHERE ILL.invoice_no = IL.invoice_no))  
				FROM tblcard_transaction CTT
				WHERE CTT.invoice_no =  IL.invoice_no) AS pts_used FROM `tblinvoice` I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
						WHERE DATE_FORMAT(I.date, '%y-%m-%d') = DATE(?) AND IL.is_void = 0 ORDER BY IL.invoice_no ASC;";
		$q = $this->db->query($sql, array($date));
		return $q->result();
	}	
	function get_line_no($invoice_no)
	{
		$sql = "SELECT MAX(`line_no`) AS m FROM tblinvoice_line 
								WHERE `invoice_no` = ?;";
		$q = $this->db->query($sql, array($invoice_no));
		$row = $q->row_array();
		if(isset($row) && $row['m'] !=  null)
			return $row['m'] + 1;
		else
			return 1;	
	}
	
	function get_sale_date($invoice_no)
	{
		$sql = "SELECT DATE_FORMAT(date, '%y-%m-%d') m FROM `tblinvoice` WHERE invoice_no = ?;";
		$q = $this->db->query($sql, array($invoice_no));
		$row = $q->row_array();
		if (isset($row))
			return $row['m'];

	}	
	
	
	//END

	
	function get_total_invoice($invoice_no)
	{
		$sql = "SELECT SUM(IV.amt_paid) AS m FROM `tblinvoice` I 
				JOIN tblinvoice_line IV ON IV.invoice_no = I.invoice_no
				WHERE I.invoice_no = ?";
		$q = $this->db->query($sql, array($invoice_no));
		$row = $q->row_array();
		if (isset($row))
			return $row['m'];
	}
	function get_max_invoice_no($date)
	{
		$sql = "SELECT MAX(`invoice_no`) AS m FROM `tblinvoice` WHERE `date` = ? AND cust_id IS NULL";
		$q = $this->db->query($sql, array($date));
		$row = $q->row_array();
		if (isset($row))
			return $row['m'];

	}

	function get_sale2($invoice_no)
	{
		$sql = "SELECT I.invoice_no, I.date, IL.sku, IL.qty, IL.amt_paid, I.cash, IL.discount, IL.selling_price,
				(SELECT  used_reward_pts FROM tblcard_transaction WHERE invoice_no = IL.invoice_no) AS used_pts,
				
				(SELECT  gained_reward_pts FROM tblcard_transaction WHERE invoice_no = IL.invoice_no) AS gained_pts,

				
				(SELECT RC.membership_id FROM tblcard_transaction CT
				LEFT JOIN tblreward_card RC ON RC.card_no = CT.card_no
				WHERE CT.invoice_no = IL.invoice_no) AS membership_id,
 
  
			    (SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)  FROM tblproduct_option PO 
				 LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
				 WHERE PO.sku = IL.sku ORDER BY O.opt_name) as options, 
  
				  (SELECT P.name FROM `tblproduct_variant` PV
				LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
				WHERE PV.sku = IL.sku) as name
  
				FROM `tblinvoice` I 
				LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no 
				WHERE I.invoice_no = ?";
		$q = $this->db->query($sql, array($invoice_no));
		return $q->row_array();	
	}

	function get_sale($invoice_no)
	{
		$sql = "SELECT I.invoice_no, I.date, IL.sku, IL.qty, IF(IL.discount = 0, null, IL.amt_paid) as amt_paid, I.cash, 
	
				(SELECT  used_reward_pts FROM tblcard_transaction WHERE invoice_no = IL.invoice_no) AS reward_points,
				(SELECT RC.membership_id FROM tblcard_transaction CT
				LEFT JOIN tblreward_card RC ON RC.card_no = CT.card_no
				WHERE CT.invoice_no = IL.invoice_no) AS membership_id
  
				FROM `tblinvoice` I 
				LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no 
				WHERE I.invoice_no = ?";
		$q = $this->db->query($sql, array($invoice_no));
		return $q->result();	
	}

	

}