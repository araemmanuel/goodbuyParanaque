<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Shipping_Fee extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblshipping_fee";
    }	
	function get_shipping_fee(){
		$q = $this->db->query("SELECT * FROM tblshipping_fee LIMIT 1");
       return $q->result();
    }
}

