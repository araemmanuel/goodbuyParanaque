<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Subcategory extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblsubcategory";
    }
	//admin
	function exist($cat_id, $code)
	{
		$sql = "SELECT * FROM `tblsubcategory` SC WHERE `cat_id` = ? AND subcat_code = ?";
		$q = $this->db->query($sql, array((int)$cat_id,  $code));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function exists_by_edit($subcat_id, $new_subcat_code, $cat_id)
	{
		$sql = "SELECT * FROM `tblsubcategory` WHERE subcat_code = ? AND NOT subcat_id = ? AND cat_id = ?;";
		$q = $this->db->query($sql, array($new_subcat_code, (int)$subcat_id, (int)$cat_id));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function get_cat_id($subcat_id)
	{
		$sql = "SELECT cat_id AS m FROM `tblsubcategory` WHERE subcat_id = ?;";
		$q = $this->db->query($sql, array($subcat_id));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_subcat_code($subcat_id)
	{
		$sql = "SELECT subcat_code AS m FROM `tblsubcategory` WHERE subcat_id = ?;";
		$q = $this->db->query($sql, array($subcat_id));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_subcat_id($cat_id, $subcat_id)
	{
		$sql = "SELECT subcat_id AS m FROM `tblsubcategory` WHERE subcat_name = ? AND cat_id = ?;";
		$q = $this->db->query($sql, array($subcat_id, $cat_id));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_subcat_id2($cat_name, $subcat_id)
	{
		$sql = "SELECT SC.subcat_id AS m FROM tblsubcategory SC 
				LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE SC.subcat_name = ? AND C.cat_name = ?;";
		$q = $this->db->query($sql, array($subcat_id, $cat_name));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_subcat_id3($cat_id, $subcat_code)
	{
		$sql = "SELECT SC.subcat_id AS m FROM tblsubcategory SC 
				LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE SC.subcat_code = ? AND C.cat_id = ?;";
		$q = $this->db->query($sql, array($subcat_code, $cat_id));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_subcategories($cat_id=null){
		
		
		if(empty($cat_id))
			$q = $this->db->query("SELECT * FROM `tblsubcategory` SC JOIN tblcategory C ON C.cat_id = SC.cat_id;");
		else
		{
			$sql = "SELECT SC.subcat_name FROM `tblsubcategory` SC JOIN tblcategory C ON C.cat_id = SC.cat_id WHERE C.cat_id = ?;";
			$q = $this->db->query($sql, array($cat_id));			
		}
		return $q->result();
    }
	
	function get_by_cat($cat_id, $col_name, $col_value)
	{
		$sql = "SELECT * FROM `tblsubcategory` SC WHERE `cat_id` = ? AND subcat_code = ?";
		$q = $this->db->query($sql, array($cat_id,  $col_value));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function get_by_cat2($cat_id, $col_value)
	{
		$sql = "SELECT * FROM `tblsubcategory` SC WHERE `cat_id` = ? AND subcat_name = ?";
		$q = $this->db->query($sql, array($cat_id,  $col_value));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function can_delete($subcat_id)
	{
		$sql = "SELECT * FROM `tblcategory` C 
					INNER JOIN tblsubcategory SC ON SC.cat_id = C.cat_id
					INNER JOIN tblproduct P ON P.subcat_id = SC.subcat_id
					WHERE SC.subcat_id = ?";
		$q = $this->db->query($sql, array($subcat_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}
	function get_subcategory($cat_id){
		$sql = "SELECT * FROM tblsubcategory WHERE subcat_id = ?";
		$q = $this->db->query($sql, array($cat_id));
		return $q->result();
    }
	
}

