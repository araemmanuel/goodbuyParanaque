<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Min_Qty extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblmin_qty";
    }	
	function get_min_qty(){
		$q = $this->db->query("SELECT * FROM tblmin_qty LIMIT 1");
       return $q->result();
    }
	function get_min_qty2()
	{
		$sql = "SELECT * FROM tblmin_qty WHERE qty_id=(SELECT MAX(qty_id) FROM `tblmin_qty`);";
		$q = $this->db->query($sql);
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['min_qty'];
	}
}

