<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Card_Transaction extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcard_transaction";
    }	


	function get_transactions($card_no)
	{
		$sql = "SELECT I.invoice_no, FLOOR(receipt_price/200)  CT.reward_points, CT.transaction_type, I.date, 
			IF(I.is_sold_from_store = 1, 'STORE', 'ONLINE') AS sold_from FROM tblinvoice I 
			LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no WHERE card_no = ?;";
		$q = $this->db->query($sql, array((double)$card_no));
		return $q->result();
	}
	function get_gained_pts($inv_no)
	{
		$sql = "SELECT reward_points AS m FROM tblcard_transaction WHERE invoice_no = ? AND transaction_type = 'GAINED';";
		$q = $this->db->query($sql, array($inv_no));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_used_pts($inv_no)
	{
		$sql = "SELECT reward_points AS m FROM tblcard_transaction  WHERE invoice_no = ? AND transaction_type = 'USED';";
		$q = $this->db->query($sql, array($inv_no));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function edit_gained_pts($inv_no)
	{
		$sql =  "UPDATE tblcard_transaction SET gained_reward_pts = 0 WHERE invoice_no = ?;";
		if($this->db->query($sql, array((int)$inv_no)))
			return true;
		else
			return false;	
	}
	function edit_used_pts($inv_no)
	{
		$sql =  "UPDATE tblcard_transaction SET used_reward_pts = 0 WHERE invoice_no = ?;";
		if($this->db->query($sql, array((int)$inv_no)))
			return true;
		else
			return false;	
	}
}

