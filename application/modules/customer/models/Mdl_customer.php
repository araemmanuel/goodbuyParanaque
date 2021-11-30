<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Customer extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcustomer";
    }
	//login

    function get_user($user_id)
    {
		$q_string = "SELECT shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, contact_no FROM tblcustomer INNER JOIN tbluser ON tblcustomer.user_id = tbluser.id WHERE username = ?";
        $user = $this->db->query($q_string, array($user_id))->row();
		return $user;
    }
	
}

