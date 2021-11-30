<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Report extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "";
    }	
	function z_get_cash($date_from, $date_to, $terminal_id = null, $user_id = null)
	{
		/*LEFT JOIN tblshift S ON S.id = I.shift_id
		LEFT JOIN tblpos P ON P.session_id = I.pos_id
					*/
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array($date_from, $date_to));	
		}
		else if($user_id != null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE  S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$user_id, $date_from, $date_to));			
		}
		else if($user_id == null && $terminal_id != null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE  I.terminal_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, $date_from, $date_to));			
		}
		else
		{
			$sql = "SELECT (IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));			
		}
         $data = $q->row_array();
        
		if(!$data)
            return 0;
        else
            return $data['m'];			
	}
	//copy
	//I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ? 
	function x_get_cash($terminal_id = null, $user_id = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
					
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ? ;";				
			$q = $this->db->query($sql, array(date('Y-m-d')));	
		}
		else
		{
			$sql = "SELECT (IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? 
						AND I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ? ;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date('Y-m-d')));			
		}
         $data = $q->row_array();
        
		if(!$data)
            return 0;
        else
            return $data['m'];			
	}
	function z_get_rc_cash($date_from, $date_to, $terminal_id = null, $user_id = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
						
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(CT.used_reward_pts) m FROM tblcard_transaction CT
						LEFT JOIN tblinvoice I ON I.invoice_no = CT.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array($date_from, $date_to));	
		}
		else
		{
			$sql = "SELECT SUM(CT.used_reward_pts) m FROM tblcard_transaction CT
						LEFT JOIN tblinvoice I ON I.invoice_no = CT.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));			
		}
         $data = $q->row_array();
        
		if(empty($data))
            return 0;
        else
		{
			if($data['m'])
				return $data['m'];
			else
				return 0.00;	
		}	
    }
	function x_get_rc_cash($terminal_id = null, $user_id = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
						
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(CT.used_reward_pts) m FROM tblcard_transaction CT
						LEFT JOIN tblinvoice I ON I.invoice_no = CT.invoice_no
						WHERE I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array(date('Y-m-d')));	
		}
		else
		{
			$sql = "SELECT SUM(CT.used_reward_pts) m FROM tblcard_transaction CT
						LEFT JOIN tblinvoice I ON I.invoice_no = CT.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? 
							AND I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ? ;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date('Y-m-d')));			
		}
         $data = $q->row_array();
        
		if(empty($data))
            return 0;
        else
		{
			if($data['m'])
				return $data['m'];
			else
				return 0.00;	
		}	
    }
	function z_get_begin_inv_no($date_from, $date_to, $terminal_id = null, $user_id  = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
					
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT MIN(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array($date_from, $date_to));	
		}
		else
		{
			$sql = "SELECT MIN(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));
		}
		 $data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function x_get_begin_inv_no($terminal_id = null, $user_id  = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
					
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT MIN(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql,array(date('Y-m-d')));	
		}
		else
		{
			$sql = "SELECT MIN(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? 
						AND I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date('Y-m-d')));
		}
		 $data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	
	
	function z_get_end_inv_no($date_from, $date_to, $terminal_id = null, $user_id = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
					
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT MAX(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array($date_from, $date_to));	
		}
		else
		{
			$sql = "SELECT MAX(I.invoice_no) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";					
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));				
		}
		 $data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}	
	function x_get_end_inv_no($terminal_id = null, $user_id = null)
	{
		//LEFT JOIN tblshift S ON S.id = I.shift_id
					
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT MAX(I.invoice_no) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array(date('Y-m-d')));	
		}
		else
		{
			//		LEFT JOIN tblshift S ON S.id = I.shift_id
			$sql = "SELECT MAX(I.invoice_no) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? 
							AND I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ? ;";					
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date('Y-m-d')));				
		}
		 $data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function z_get_sales_from($date, $terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array($date));
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date));				
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function x_get_sales_from( $terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE I.date <= NOW();";				
			$q = $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND I.date <= NOW();";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id));				
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function z_get_sales_today($terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array(date("Y-m-d")));
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date("Y-m-d")));				
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function x_get_sales_today($terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE I.date <= NOW() AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array(date('Y-m-d')));
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
					LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
					WHERE I.terminal_id = ? AND S.user_id = ? AND I.date <= NOW()
						AND DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, date('Y-m-d')));				
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
		
	function z_get_total_cancel($date_from, $date_to, $terminal_id = null, $user_id = null)
	{
		
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT COUNT(I.invoice_no) m FROM tblinvoice I
					WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? 
					AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND I.is_void = 1;";				
			$q = $this->db->query($sql, array($date_from, $date_to));								
		}
		else
		{
			$sql = "SELECT COUNT(I.invoice_no) m FROM tblinvoice I
					WHERE I.terminal_id = ? AND S.user_id = ? AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? 
					AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND I.is_void = 1;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));									
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function x_get_total_cancel( $terminal_id = null, $user_id = null)
	{
		
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT COUNT(I.invoice_no) m FROM tblinvoice I
					WHERE I.date >= NOW() 
					AND I.date <= NOW() AND I.is_void = 1;";				
			$q = $this->db->query($sql);								
		}
		else
		{
			$sql = "SELECT COUNT(I.invoice_no) m FROM tblinvoice I
					WHERE I.terminal_id = ? AND S.user_id = ? AND I.date >= NOW() 
					AND I.date <= NOW() AND I.is_void = 1;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id));									
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
            return $data['m'];	
	}
	function z_get_cancel_amt($date_from, $date_to, $terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND IL.is_void = 1;";				
			$q = $this->db->query($sql, array($date_from, $date_to));											
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? 
						  AND DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND IL.is_void = 1;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id, $date_from, $date_to));											
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
		{
			if($data['m'])
				return $data['m'];	
			else
				return 0.00;
		}
	}
	function x_get_cancel_amt($terminal_id = null, $user_id = null)
	{
		if($user_id == null && $terminal_id == null)
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE I.date >= NOW() AND I.date <= NOW() AND IL.is_void = 1;";				
			$q = $this->db->query($sql);											
		}
		else
		{
			$sql = "SELECT SUM(IL.amt_paid) m FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						WHERE I.terminal_id = ? AND S.user_id = ? 
						  AND I.date >= NOW() AND I.date <= NOW() AND IL.is_void = 1;";				
			$q = $this->db->query($sql, array((int)$terminal_id, (int)$user_id));											
		}
		$data = $q->row_array();
        if(empty($data))
            return 0;
        else
		{
			if($data['m'])
				return $data['m'];	
			else
				return 0.00;
		}
	}	
}

