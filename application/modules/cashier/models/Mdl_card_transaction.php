<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Card_Transaction extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcard_transaction";
    }	


	function get_transactions($card_no)
	{
		$sql = "SELECT I.invoice_no, CT.reward_points, CT.transaction_type, I.date, 
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
	function del_gained_pts($inv_no)
	{
		$sql =  "DELETE FROM tblcard_transaction  WHERE invoice_no = ? AND transaction_type = 'GAINED';";
		if($this->db->query($sql, array((int)$inv_no)))
			return true;
		else
			return false;	
	}
	function del_used_pts($inv_no)
	{
		$sql =  "DELETE FROM tblcard_transaction  WHERE invoice_no = ? AND transaction_type = 'USED';";
		if($this->db->query($sql, array((int)$inv_no)))
			return true;
		else
			return false;	
	}
}

