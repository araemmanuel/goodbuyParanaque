<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Supplier extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblsupplier";
    }
	function get_suppliers()
	{
		$sql = "SELECT * FROM tblsupplier;";
		$q = $this->db->query($sql);//, array($id)
		return $q->result();
    }
	function get_supplier_names()
	{
		$sql = "SELECT name FROM tblsupplier;";
		$q = $this->db->query($sql);//, array($id)
		return $q->result();
    }
}

