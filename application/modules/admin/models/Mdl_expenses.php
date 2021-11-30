<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Expenses extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblexpenses";
    }	
	
	function get_expenses()
	{
		$q = $this->db->query("SELECT * FROM tblexpenses");
       return $q->result();
	}
	function get_expense($id){
		$sql = "SELECT * FROM tblexpenses WHERE exp_id = ?";
		$q = $this->db->query($sql, array($id));
		return $q->result();
    }
	
}

