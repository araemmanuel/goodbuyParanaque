<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Data_Log extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbldata_log";
    }	
	
	
	function get_data_log(){
		$q = $this->db->query("SELECT * FROM tbldata_log ORDER BY id DESC");
       return $q->result();
    }
	function dt_qry()
	{
		if($_POST["length"] != -1)  
        {  
			$sql = "SELECT * FROM tbldata_log	WHERE CONCAT(activity, role, username, datetime)
						LIKE ?  ORDER BY id DESC LIMIT ?, ?;";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));		
        }  
		else
		{
			$sql = "SELECT * FROM tbldata_log	WHERE CONCAT(activity, role, username, datetime)
						LIKE ?  ORDER BY id DESC";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		}
		return $q;
	}
   function make_datatables()
   {  
		$q = $this->dt_qry();
        return $q->result();  
    }  
    function get_filtered_data()
	{  
		$sql = "SELECT * FROM tbldata_log	WHERE CONCAT(activity, role, username, datetime)
						LIKE ?  ORDER BY id DESC";
		$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT * FROM tbldata_log ORDER BY id DESC";
		$q = $this->db->query($sql);
		return $q->num_rows();
    }  
	
	
}

