<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Return_Policy extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblreturn_policy";
    }	
	function get_return_policy(){
		$q = $this->db->query("SELECT * FROM tblreturn_policy LIMIT 1");
       return $q->result();
    }
	function get_days()
	{
		$sql= "SELECT `days` AS m FROM tblreturn_policy WHERE policy_id=(SELECT MAX(policy_id) FROM `tblreturn_policy`)";
        $q = $this->db->query($sql);
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
}

