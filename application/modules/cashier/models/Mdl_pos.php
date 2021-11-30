<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Pos extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblpos";
    }
	//login
	//

	function get_pos_id($terminal, $status)
	{
		$sql = "SELECT `session_id` m FROM `tblpos` 
					WHERE status = ? AND terminal_id = ? AND session_id = (SELECT MAX(session_id) FROM `tblpos` WHERE terminal_id = ?);";
		$q = $this->db->query($sql, array((string)$status, (int)$terminal, (int)$terminal));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_last_session($type, $terminal)
	{
		if($type == 'status')
			$sql = "SELECT status m FROM `tblpos` WHERE session_id = (SELECT MAX(session_id) FROM `tblpos`) AND terminal_id = ?;";
		else if($type == 'close')
			$sql = "SELECT DATE_FORMAT(`start_datetime`, '%Y-%m-%d') m FROM `tblpos` WHERE session_id = (SELECT MAX(session_id) FROM `tblpos` WHERE status = 'CLOSE') AND terminal_id = ?;";	
		else
			$sql = "SELECT DATE_FORMAT(`start_datetime`, '%Y-%m-%d') m FROM `tblpos` WHERE session_id = (SELECT MAX(session_id) FROM `tblpos` WHERE status = 'OPEN') AND terminal_id = ?;";	
		$q = $this->db->query($sql, array((int)$terminal));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_max_close_date($terminal)
	{
		$sql = "SELECT DATE_FORMAT(start_datetime, '%Y-%m-%d') m FROM tblpos 
				WHERE session_id = (SELECT MAX(session_id) FROM tblpos WHERE terminal_id = ? AND status = 'CLOSE')";
		$q = $this->db->query($sql, array((int)$terminal));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_item_sales()
	{	
		$sql = "SELECT  SUM(IL.amt_paid) AS m
		FROM tblinvoice_line IL
		LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
		WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = DATE(?);";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	
	function get_void_transactions()
	{	
		$sql = "SELECT  SUM(IL.amt_paid) AS m
		FROM tblinvoice_line IL
		LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
		WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = DATE(?) AND IL.is_void = 1;";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_discounts()
	{	
		$sql = "SELECT  SUM(IL.discount) AS m
		FROM tblinvoice_line IL
		LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
		WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = DATE(?);";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	
	function get_void_count()
	{	
		$sql = "SELECT COUNT(invoice_no) AS m
				FROM tblinvoice
				WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE(?) AND is_void = 1;";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_hours()
	{	
		$sql = " SELECT TIMESTAMPDIFF(HOUR,`start_datetime`,`end_datetime`) AS m
				FROM tblpos WHERE DATE_FORMAT(`start_datetime`, '%Y-%m-%d') = DATE(?);";
		$q = $this->db->query($sql, array(date('Y-m-d')));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}


	function get_pos_details()
	{
		$sql = "SELECT DATE_FORMAT(`start_datetime`, '%Y-%m-%d') AS start_date, 
				DATE_FORMAT(`end_datetime`, '%Y-%m-%d') AS end_date,  
				DATE_FORMAT(`start_datetime`, '%r') AS start_time,
				DATE_FORMAT(`end_datetime`, '%r') AS end_time
				FROM tblpos WHERE DATE_FORMAT(`start_datetime`, '%Y-%m-%d') = DATE(?) OR session_id = (SELECT MAX(session_id) FROM tblpos);";
		$q = $this->db->query($sql, array(date('Y-m-d')));
		return $q->result();
	}	
	
	function pos_has_started($terminal)
	{
		$sql = "SELECT `session_id` FROM `tblpos` 
					WHERE date_format(start_datetime, '%y-%m-%d') = DATE(?)
					AND status = 'OPEN' AND terminal_id = ?;";
		$q = $this->db->query($sql, array(date('Y-m-d'), $terminal));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function pos_has_ended($terminal)
	{
		$sql = "SELECT `session_id` FROM `tblpos` 
					WHERE date_format(start_datetime, '%y-%m-%d') = DATE(?)
					AND status = 'CLOSE' AND terminal_id = ?;";
		$q = $this->db->query($sql, array(date('Y-m-d'), $terminal));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}

	function get_sess_id($terminal)
	{
		$sql = "SELECT session_id AS m FROM `tblpos` WHERE status = 'open' AND terminal_id = ? ;";
		$q = $this->db->query($sql, array($terminal));
        $data = $q->row_array();
		
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_max_sess_id($terminal)
	{
		$sql = "SELECT MAX(session_id) AS m FROM `tblpos` WHERE status = 'CLOSE' AND terminal_id = ?;";
		$q = $this->db->query($sql, array($terminal));
        $data = $q->row_array();
		
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
}

