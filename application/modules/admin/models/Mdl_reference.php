<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Reference extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "";
    }	

	function get_return_policy(){
		$q = $this->db->query("SELECT MAX(policy_id) as days FROM tblreturn_policy LIMIT 1");
       return $q->result();
    }
	function get_min_qty(){
		$q = $this->db->query("SELECT MAX(qty_id) as min_qty FROM tblmin_qty LIMIT 1");
       return $q->result();
    }
}

