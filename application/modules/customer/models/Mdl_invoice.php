<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Invoice extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblinvoice";
    }	
	//FIXED QUERIES
	// IF(CT.transaction_type = 'USED', CT.reward_points, 0) AS pts_used, I.is_sold_from_store
	
	function get_receipt($inv_no)
	{
		$sql = "SELECT IL.invoice_no, P.name AS prod_name, IL.amt_paid, IL.qty AS quantity,  IL.is_void
					FROM `tblinvoice` I
					LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
					LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id 
					WHERE I.invoice_no = ? AND IL.is_void = 0;";
		$q = $this->db->query($sql, array((int)$inv_no));
		return $q->result();
	}
	
	function get_sales($date)
	{
		$sql = "SELECT I.invoice_no, PV.sku,  P.name, IL.qty, PV.selling_price, IL.discount, I.cash, I.is_sold_from_store, CT.reward_points AS pts_used FROM `tblinvoice` I
				LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
				LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
                LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
                LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no
				WHERE I.date = ? AND CT.transaction_type = 'USED' ORDER BY IL.invoice_no DESC;";
		$q = $this->db->query($sql, array($date));
		return $q->result();
	}	
	function get_line_no($invoice_no)
	{
		$sql = "SELECT MAX((`line_no`)) AS m FROM tblinvoice_line 
								WHERE `invoice_no` = ?;";
		$q = $this->db->query($sql, array($invoice_no));
		$row = $q->row_array();
		if(isset($row) && $row['m'] !=  null)
			return $row['m'] + 1;
		else
			return 1;	
	
	
	}
	function get_line_no2($invoice_no, $sku)
	{
		$sql = "SELECT line_no AS m FROM tblinvoice_line 
								WHERE `invoice_no` = ? AND sku = ?;";
		$q = $this->db->query($sql, array((int)$invoice_no, $sku));
		$row = $q->row_array();
		if(isset($row) && $row['m'] !=  null)
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

	function get_sale($invoice_no)
	{
		$sql = "SELECT I.invoice_no, I.date, IL.sku, IL.qty, IF(IL.discount = 0, null, IL.amt_paid) as amt_paid,
					I.cash, CT.reward_points, RC.membership_id 
					FROM `tblinvoice` I 
					LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no 
					LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no 
					LEFT JOIN tblreward_card RC ON RC.card_no = CT.card_no 
					WHERE I.invoice_no = ? AND CT.transaction_type = 'USED' ";
		$q = $this->db->query($sql, array($invoice_no));
		return $q->result();	
	}
	

}