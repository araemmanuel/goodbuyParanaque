<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Courier extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcourier";
    }
	//login

    function get_shipping_fee()
    {
		$q_string = "SELECT shipping_fee FROM tblcourier WHERE is_default = 1";
        $user = $this->db->query($q_string)->row()->shipping_fee;
		return $user;
    }
	
}

