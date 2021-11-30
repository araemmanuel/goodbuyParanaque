<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Country_Sizes extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcountry_sizes";
    }	

	function get_sizes($sku)
	{
		$sql = "SELECT * FROM `tblcountry_sizes` WHERE sku = ?";
		$q = $this->db->query($sql, array($sku));
		return $q->result();
	}	
	function verify_if_footwear($sku)
	{
		$sql = "SELECT * FROM `tblcountry_sizes` 
				WHERE sku = ? AND NOT foot_size IS NULL";
		$q = $this->db->query($sql, array($sku));
		if ($q->num_rows() >= 1){
           return true;
        }else{ 
           return false;
        }
		
	}
	function update($id,$sku,$data)
	{
		$table = $this->get_table();
        $this->db->where('prod_id', $id);
		$this->db->where('sku', $sku);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	
}

