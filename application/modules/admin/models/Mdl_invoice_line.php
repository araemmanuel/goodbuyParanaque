<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Invoice_Line extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblinvoice_line";
    }	
	function is_all_void($invoice_no)
	{
		
		$sql = "SELECT COUNT(sku) FROM tblinvoice_line WHERE invoice_no = ?;";
		$q = $this->db->query($sql, array((int)$invoice_no));
		$all_pv_ctr = $q->num_rows();
		
		$sql = "SELECT COUNT(sku) FROM tblinvoice_line WHERE invoice_no = ? AND is_void = 0;";
		$q = $this->db->query($sql, array((int)$invoice_no));
		$not_void_ctr = $q->num_rows();
		
		if ($all_pv_ctr == $not_void_ctr){
           return true;
        }else{ 
           return false;
        }
	}
	function get_total_qty($sku)
	{
		$sql = "SELECT SUM(qty) qty FROM `tblinvoice_line` WHERE sku = ? AND is_void = 0;";
		$q = $this->db->query($sql, array($sku));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['qty'];
	}
	function update($invoice_no, $sku)
	{
		$sql = "UPDATE tblinvoice_line SET is_void = 1 WHERE invoice_no = ? AND sku = ?";
		if($this->db->query($sql, array((int)$invoice_no, $sku)))
			return true;
		else
			return false;
	}
	function get_items($invoice_no)
	{		
		$sql = "SELECT IL.sku, P.name, IL.selling_price, IL.amt_paid, IL.discount, IL.qty  FROM `tblinvoice_line` IL
				LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
				WHERE IL.invoice_no = ?";
		$q = $this->db->query($sql, array((int)$invoice_no));
		return $q->result();
	}
	function get_invoice_qty($invoice_no, $sku)
	{		
		$sql = "SELECT qty FROM `tblinvoice_line` 
								WHERE `invoice_no` = ? AND `sku` = ?;";
		$q = $this->db->query($sql, array($invoice_no, $sku));
		$row = $q->row_array();
		if (isset($row))
			return $row['qty'];
	}
	function del($inv_no, $sku)
	{
		$sql =  "DELETE FROM tblinvoice_line  WHERE invoice_no = ? AND sku = ?;";
		$q = $this->db->query($sql, array((int)$inv_no, $sku));

	}
	function get_amt_paid($invoice_no)
	{		
		$sql = "SELECT SUM(amt_paid) as amt_paid FROM `tblinvoice_line` WHERE invoice_no = ?;";
		$q = $this->db->query($sql, array((int)$invoice_no));
		$row = $q->row_array();
		if (isset($row))
			return $row['amt_paid'];
	}
	function get_max_inv_line($invoice_no)
	{		
		$sql = "SELECT MAX(line_no) as amt_paid FROM `tblinvoice_line` WHERE invoice_no = ?;";
		$q = $this->db->query($sql, array((int)$invoice_no));
		$row = $q->row_array();
		if (isset($row))
			return $row['amt_paid'];
	}
}

