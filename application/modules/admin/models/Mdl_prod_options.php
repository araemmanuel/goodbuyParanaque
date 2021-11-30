<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Prod_Options extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblproduct_option";
    }
	function get_prod_option($id)
	{
		$sql = "SELECT DISTINCT PO.prod_id, OG.opt_grp_name FROM `tblproduct_option` PO 
				JOIN tbloption O ON O.opt_id = PO.option_id 
				JOIN tbloption_group OG ON OG.opt_grp_id = O.opt_grp_id
				WHERE PO.prod_id = ?";
		return $this->db->query($sql, array($id));		
	}
	function get_by_prod($prod_id, $opt_name)
	{		
		$sql = "SELECT DISTINCT O.opt_name FROM `tblproduct_option` PO 
				INNER JOIN tbloption O ON O.opt_id = PO.option_id 
				INNER JOIN tbloption_group OG ON OG.opt_grp_id = O.opt_grp_id
				WHERE PO.prod_id = ? AND O.opt_name = ?;";
		$q = $this->db->query($sql, array($prod_id, $opt_name));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }		
	}
	function update($prod_id, $sku, $opt_id, $data)
	{
		$table = $this->get_table();
        $this->db->where('prod_id', $prod_id);
		$this->db->where('sku', $opt_id);
		$this->db->where('option_id', $opt_id);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	
	
}

