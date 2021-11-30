<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Location extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbllocation";
    }	
	function get_locations(){
		$q = $this->db->query("SELECT * FROM tbllocation");
       return $q->result();
    }
	function get_location($id){
		$sql = "SELECT * FROM tbllocation WHERE `loc_id` = ?;";
		$q = $this->db->query($sql, array($id));
       return $q->result();
    }

}

