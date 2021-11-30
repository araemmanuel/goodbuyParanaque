<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Clothing_Sizes extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblclothing_sizes";
    }	


	function get_sizes($sku)
	{
		$sql = "SELECT * FROM `tblclothing_sizes` WHERE sku = ?";
		$q = $this->db->query($sql, array($sku));
		return $q->result();
	}	
	function verify_if_shirt($sku)
	{
		$sql = "SELECT * FROM `tblclothing_sizes` 
				WHERE sku = ? AND NOT `chest_min_cm` = 0
				AND NOT `chest_max_cm` = 0";
		$q = $this->db->query($sql, array($sku));
		if ($q->num_rows() >= 1){
           return true;
        }else{ 
           return false;
        }
		
	}		
	function verify_if_pants($sku)
	{
		$sql = "SELECT * FROM `tblclothing_sizes` 
				WHERE sku = ? AND NOT `hip_min_cm` = 0
				AND NOT `hip_max_cm` = 0";
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

