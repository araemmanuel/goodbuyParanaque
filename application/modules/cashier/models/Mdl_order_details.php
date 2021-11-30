<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Order_Details extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblorder_details";
    }	
	function get_skus($order_no)
	{
		$sql = "SELECT  `sku`, quantity FROM `tblorder_details` WHERE order_no = ? AND item_order_status = 'On Queue';";
		$q = $this->db->query($sql, array($order_no));
       return $q->result();
    }
}

