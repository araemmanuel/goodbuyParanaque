<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Courier extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcourier";
    }	
	public function get_couriers()
	{
		$sql = "SELECT * FROM tblcourier;";
		$q = $this->db->query($sql);
		return $q->result();
	}
	function can_delete($cour_id)
	{
		$sql = "SELECT `order_no` FROM `tblorder` WHERE cour_id = ?";
		$q = $this->db->query($sql, array((int)$cour_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}
}

