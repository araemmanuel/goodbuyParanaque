<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Return_Transaction extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblreturn_transaction";
    }	
	function get_receipt_details($trans_id)
	{
		$sql = "SELECT IF((return_amt - replacement_amt) < 0, ABS(return_amt - replacement_amt + 0.00), 0.00) AS balance,
				cash, abs(cash-return_amt) AS ch
				FROM `tblreturn_transaction` WHERE id = ? LIMIT 1;";
		$q = $this->db->query($sql, array((int)$trans_id));
		return $q->result();
		
	}
}

