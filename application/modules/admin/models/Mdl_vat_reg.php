<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Vat_Reg extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblvat_reg";
    }	
	function get_reg_tin(){
		$q = $this->db->query("SELECT * FROM tblvat_reg LIMIT 1");
       return $q->result();
    }
}

