<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Data_Log extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbldata_log";
    }	
	
	
	function get_data_log(){
		$q = $this->db->query("SELECT * FROM tbl_data_log");
       return $q->result();
    }
	
}

