<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Category extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcategory";
    }
	//admin	

	function get_categories(){
		$q = $this->db->query("SELECT * FROM tblcategory ORDER BY cat_name ASC");
       return $q->result();
    }
	function get_scanned_cat()
	{
		$q = $this->db->query("SELECT DISTINCT C.cat_id, C.cat_name FROM tblproduct_variant PV 
							LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
							LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
							LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
							WHERE PV.is_scanned = 1");
       return $q->result();
	}
	function get_categories_for_chart(){
		$q = $this->db->query("SELECT GROUP_CONCAT(cat_name) AS cat FROM tblcategory");
		$data = $q->result_array();
		if(empty($data))
            return null;
        else
            return $data[0];
    }
	function get_categories_for_event_handling(){
		$q = $this->db->query("SELECT CONCAT(cat_id,'-',cat_name) AS cat FROM tblcategory  ORDER BY cat_name ASC");
		 return $q->result();
    }
	function get_names(){
		$q = $this->db->query("SELECT cat_name FROM tblcategory");
       return $q->result();
    }
	function get_cat_id($col, $whereCol, $whereValue)
	{
        $this->db->select($col)->from($this->tableName)->where($whereCol.' = "'.$whereValue.'"');
        $q = $this->db->get();
        $data = $q->result_array();
        if(empty($data))
            return null;
        else
            return $data[0][$col];
	}
	function get_category($cat_id){
		$sql = "SELECT * FROM tblcategory WHERE cat_id = ?";
		$q = $this->db->query($sql, array($cat_id));
       return $q->result();
    }
	function can_delete($cat_id)
	{
		$sql = "SELECT * FROM `tblcategory` C 
								INNER JOIN tblsubcategory SC ON SC.cat_id = C.cat_id
								INNER JOIN tblproduct P ON P.subcat_id = SC.subcat_id
								WHERE C.cat_id = ?";
		$q = $this->db->query($sql, array($cat_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}
	function exists_by_edit($cat_id, $new_cat_code)
	{
		$sql = "SELECT * FROM `tblcategory` WHERE cat_code = ? AND NOT cat_id = ?;";
		$q = $this->db->query($sql, array($new_cat_code, (int)$cat_id));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
}

