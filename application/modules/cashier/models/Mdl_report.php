<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Report extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "";
    }	
function get_closed_reading($date_from, $date_to)
	{	
		$sql = "SELECT MIN(begin_inv) begin_inv, MAX(end_inv) end_inv, SUM(begin_amt) begin_amt, 
				SUM(today_amt) today_amt, SUM(end_amt) end_amt, SUM(cancel_ctr) cancel_ctr, 
				SUM(total_cancel_amt) total_cancel_amt, SUM(gross_sales) gross_sales, 
				SUM(net_sales) net_sales, SUM(total_discount) total_discount, 
				SUM(total_rc_cash) total_rc_cash, SUM(total_cash) total_cash, 
				SUM(total_qty) total_qty, SUM(transaction_count) transaction_count
				FROM `tblpos` WHERE DATE_FORMAT(start_datetime, '%Y-%m-%d') >= ? AND DATE_FORMAT(end_datetime, '%Y-%m-%d') <= ? 
				AND status = 'CLOSE'";				
		 $q = $this->db->query($sql, array($date_from, $date_to));	
		 $data = $q->row_array();
			if(!$data)
				return 0;
			else
				return $data;	
	}
	
	
	/*
	function get_closed_reading($date_from, $date_to)
	{	
		$sql = "SELECT MIN(I.invoice_no) begin_inv, MAX(I.invoice_no) end_inv, 
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') <= ?  AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS begin_amt,
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?  AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS today_amt,
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') <= ?  AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS end_amt,

					(SELECT COUNT(I.invoice_no)
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? 
						AND I.is_void = 1 AND I.shift_id IS NOT NULL) AS cancel_ctr,
						
					(SELECT SUM(IL.amt_paid)
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? 
						AND I.is_void = 1 AND I.shift_id IS NOT NULL) AS total_cancel_amt,
						
					(SUM(IL.amt_paid) + SUM(CT.used_reward_pts)) gross_sales, SUM(IL.discount) total_discount, SUM(CT.used_reward_pts) total_rc_cash,
					SUM(I.cash) total_cash, SUM(IL.qty) total_qty, COUNT(IL.invoice_no) transaction_count  
					
				FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND
					  I.shift_id IS NOT NULL AND I.is_void = 0;";				
		 $q = $this->db->query($sql, array($date_from, date('Y-m-d'), $date_to,$date_from, $date_to,$date_from, $date_to,$date_from, $date_to));	
		 $data = $q->row_array();
			if(!$data)
				return 0;
			else
				return $data;	
	}
	*/
	function get_eod_report($session_id)
	{	
		$sql = "SELECT * FROM `tblpos` WHERE session_id = ?";
		$q = $this->db->query($sql, array($session_id));	
		$data = $q->row_array();
		if(!$data)
				return 0;
			else
				return $data;	
	}
	
	function get_open_reading($date)
	{	
		$sql = "SELECT MIN(I.invoice_no) begin_inv, MAX(I.invoice_no) end_inv, 
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') < ? AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS begin_amt,
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?  AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS today_amt,
					
					(SELECT SUM(IL.amt_paid) FROM tblinvoice I
						LEFT JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') <= ?  AND I.is_void = 0 AND I.shift_id IS NOT NULL) AS end_amt,

					(SELECT COUNT(I.invoice_no)
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?
						AND I.is_void = 1 AND I.shift_id IS NOT NULL) AS cancel_ctr,
						
					(SELECT SUM(IL.amt_paid)
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?
						AND I.is_void = 1 AND I.shift_id IS NOT NULL) AS total_cancel_amt,
						
					(SUM(IL.selling_price)) gross_sales, (SUM(IL.amt_paid)) net_sales, SUM(IL.discount) total_discount, SUM(CT.used_reward_pts) total_rc_cash,
					SUM(I.cash) total_cash, SUM(IL.qty) total_qty, COUNT(IL.invoice_no) transaction_count  
						
				FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				LEFT JOIN tblcard_transaction CT ON CT.invoice_no = I.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ? AND I.shift_id IS NOT NULL AND I.is_void = 0;";				
		 $q = $this->db->query($sql, array($date, $date, $date, $date, $date, $date));	
		 $data = $q->row_array();
			if(!$data)
				return 0;
			else
				return $data;	
	}
	
	
}

