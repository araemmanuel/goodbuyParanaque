<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Option extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbloption";
    }
	//admin
	function delete_opt($id, $sku)
	{
		$sql = "DELETE FROM `tblproduct_option` WHERE `prod_id` = ? AND `sku` = ?;";
		if($this->db->query($sql, array((int)$id, $sku)))
			return true;
		else
			return false;
	}
	function get_options(){
		$q = $this->db->query("SELECT * FROM `tbloption` O JOIN tbloption_group OG ON OG.`opt_grp_id` = O.opt_grp_id;");
       return $q->result();
    }
	function get_option($id){
		$sql = "SELECT * FROM tbloption WHERE opt_id = ?";
		$q = $this->db->query($sql, array($id));
       return $q->result();
    }
	function get_option_by_type($opt_name, $opt_grp_id=null)
	{
		$sql = "SELECT DISTINCT opt_name as value, opt_grp_id as label FROM tbloption WHERE opt_name LIKE ? AND opt_grp_id = ?";	//AND opt_grp_id = ?;			
		$q = $this->db->query($sql, array('%'.$opt_name.'%', $opt_grp_id));//, $opt_grp_id
		   return $q->result();
	}
	function get_option_names(){
		$q = $this->db->query("SELECT opt_name FROM tbloption");
		return $q;
    }
	function get_by_type($attrib_type_id, $attrib_value)
	{
		$sql = "SELECT * FROM `tbloption` WHERE `opt_grp_id`= ? AND `opt_name` = ?;";
		$q = $this->db->query($sql, array($attrib_type_id, $attrib_value));
		if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
	function can_delete($opt_id)
	{
		$sql = "SELECT O.opt_id FROM tblproduct_option PO 
							NATURAL JOIN tbloption_group OG
							NATURAL JOIN tbloption O  
							WHERE PO.option_id = ?";
		$q = $this->db->query($sql, array((int)$opt_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}	
}

