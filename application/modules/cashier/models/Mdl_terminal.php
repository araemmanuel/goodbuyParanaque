<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Terminal extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblterminal";
    }	
	function get_terminals()
	{
		$sql = "SELECT * FROM tblterminal;";
		$q = $this->db->query($sql);
		return $q->result();
	}	

}

