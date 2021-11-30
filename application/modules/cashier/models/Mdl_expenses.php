<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Expenses extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblexpenses";
    }	
	
	function get_total_payout()
	{	
		$sql = "SELECT  SUM(`exp_amt`) AS m
				FROM tblexpenses
				WHERE DATE_FORMAT(`exp_date`, '%Y-%m-%d') = DATE(?);";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
}

