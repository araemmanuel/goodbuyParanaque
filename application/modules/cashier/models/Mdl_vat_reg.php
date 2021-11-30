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
	function get_reg_tin2()
	{
		$sql = "SELECT reg_tin AS m FROM tblvat_reg WHERE id = (SELECT MAX(id)  FROM tblvat_reg)";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_vat_percent()
	{
		$sql = "SELECT vat_percent AS m FROM tblvat_reg WHERE id = (SELECT MAX(id)  FROM tblvat_reg)";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
}

