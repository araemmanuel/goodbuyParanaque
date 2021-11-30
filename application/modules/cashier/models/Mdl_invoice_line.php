<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Invoice_Line extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblinvoice_line";
    }	
	function update($id, $sku, $data)
	{
		$table = $this->get_table();
        $this->db->where('invoice_no', $id);
		$this->db->where('sku', $sku);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
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
	function get_amt_paid($invoice_no, $sku)
	{		
		$sql = "SELECT amt_paid FROM `tblinvoice_line` 
								WHERE `invoice_no` = ? AND `sku` = ?;";
		$q = $this->db->query($sql, array($invoice_no, $sku));
		$row = $q->row_array();
		if (isset($row))
			return $row['amt_paid'];
	}
	function get_selling_price($invoice_no, $sku)
	{		
		$sql = "SELECT (selling_price) FROM `tblinvoice_line` 
								WHERE `invoice_no` = ? AND `sku` = ?;";
		$q = $this->db->query($sql, array($invoice_no, $sku));
		$row = $q->row_array();
		if (isset($row))
			return $row['amt_paid'];
	}
	function get_items($invoice_no)
	{		
		$sql = "SELECT IL.sku, P.name, IL.selling_price, IL.amt_paid, IL.discount, IL.qty, IF(IL.discount = 0, 0, IL.selling_price - IL.discount) AS discounted_price  FROM `tblinvoice_line` IL
				LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
				WHERE IL.invoice_no = ? AND IL.is_void = 0";
		$q = $this->db->query($sql, array((int)$invoice_no));
		return $q->result();
	}
}

