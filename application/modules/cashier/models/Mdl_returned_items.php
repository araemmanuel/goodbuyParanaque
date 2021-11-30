<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Returned_Items extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblreturned_items";
    }	
	
	function get_receipt($trans_id)
	{
		$sql = "SELECT RI.sku, P.name, RI.qty, RI.type, RI.total_selling_price,
		(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) 
		 FROM tblproduct_option PO LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = RI.sku) AS options
				FROM `tblreturned_items` RI 
				LEFT JOIN tblproduct P ON P.prod_id = RI.prod_id
				WHERE RI.trans_id = ?;";
		$q = $this->db->query($sql, array((int)$trans_id));
		return $q->result();
	}
	function get_returned_items($trans_id)
	{
		$sql = "SELECT RI.sku, RI.qty, RI.type,
		(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) 
		 FROM tblproduct_option PO LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = RI.sku) AS options
		FROM `tblreturned_items` RI WHERE RI.trans_id = ? AND RI.type = 'RETURN'";
		$q = $this->db->query($sql, array((int)$trans_id));
		return $q->result();
	}
	function get_replacement_items($trans_id)
	{
		$sql = "SELECT RI.sku, RI.qty, RI.type FROM `tblreturned_items` RI WHERE RI.trans_id = ? AND RI.type = 'REPLACEMENT'";
		$q = $this->db->query($sql, array((int)$trans_id));
		return $q->result();
	}
}

