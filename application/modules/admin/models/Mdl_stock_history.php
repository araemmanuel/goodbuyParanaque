<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Stock_History extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblstock_history";
    }	
	function get_stock_history($prod_id)
	{
		$sql = "SELECT SH.sh_id, SH.date_added, SH.sku, SH.qty, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = SH.sku  ORDER BY O.opt_name) as options
				FROM `tblstock_history` SH WHERE SH.prod_id = ?";
		$q = $this->db->query($sql, array($prod_id));
		return $q->result();
	}
	function get_total_qty($sku)
	{
		$sql = "SELECT SUM(qty) qty FROM `tblstock_history` WHERE sku = ?;";
		$q = $this->db->query($sql, array($sku));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['qty'];
	}
}

