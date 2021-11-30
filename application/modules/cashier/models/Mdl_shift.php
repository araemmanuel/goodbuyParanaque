<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Shift extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblshift";
    }
	function get_shift_id($user_id)
	{
		$sql = "SELECT id m FROM `tblshift` 
				WHERE user_id = ? AND id = (SELECT MAX(id) FROM tblshift WHERE user_id = ?)";
		$q = $this->db->query($sql, array((int)$user_id, (int)$user_id));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_cashiers()
	{
		$sql = "SELECT DISTINCT CONCAT(firstname, ' ', lastname) full_name, S.user_id FROM tblshift S 
				LEFT JOIN tbluser U ON U.id = S.user_id;";
		$q = $this->db->query($sql);
		return $q->result();
	}
	function shift_has_started($pos_id, $user_id)
	{
		$sql = "SELECT id FROM `tblshift` 
					WHERE pos_id = ? AND user_id = ?
					AND id = (SELECT MAX(id) FROM tblshift)
					AND end_time IS NULL;";
		$q = $this->db->query($sql, array((int)$pos_id, $user_id));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function shift_has_ended($pos_id, $user_id)
	{
		$sql = "SELECT id m FROM `tblshift` 
					WHERE pos_id = ? AND user_id = ? 
					AND id = (SELECT MAX(id) FROM tblshift)
					AND NOT end_time IS NULL;";
		$q = $this->db->query($sql, array((int)$pos_id, (int)$user_id));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
		{
			if(empty($data['m']))
				return false;
			else
				return true;
		}
	}
}

