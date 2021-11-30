<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Option_Group extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbloption_group";
    }
	//admin
	function get_opt_type_ctr($prod_id)
	{
		$sql = "SELECT COUNT(DISTINCT OG.opt_grp_name) m FROM tbloption_group OG 
				INNER JOIN tbloption O ON O.opt_grp_id = OG.opt_grp_id
				INNER JOIN tblproduct_option PO ON PO.option_id = O.opt_id
				WHERE PO.prod_id = ?";
		$q = $this->db->query($sql, array($prod_id));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_opt_data_by_prod($id)
	{		
		$sql = "SELECT OG.opt_grp_id, O.opt_name FROM `tblproduct_option` PO
			LEFT JOIN tbloption O ON O.opt_id = PO.option_id
			LEFT JOIN tbloption_group OG ON OG.opt_grp_id = O.opt_grp_id
			WHERE PO.prod_id = ?";
		$q = $this->db->query($sql, array($id));
       return $q;
	}
	function get_option_groups(){
		$q = $this->db->query("SELECT * FROM tbloption_group;");
       return $q->result();
    }
	function get_option_group_names(){
		$q = $this->db->query("SELECT opt_grp_name FROM tbloption_group;");
       return $q->result();
    }
	function get_option_group($id){
		$sql = "SELECT * FROM tbloption_group WHERE opt_grp_id = ?;";
		$q = $this->db->query($sql, array($id));
       return $q->result();
    }
	function get_colors()
	{
		$q = $this->db->query("SELECT OG.opt_grp_id, O.opt_id, O.opt_name FROM `tbloption_group` OG
								JOIN tbloption O ON O.opt_grp_id = OG.opt_grp_id
								WHERE OG.opt_grp_name = 'color' OR OG.opt_grp_name = 'colors';");
       return $q->result();	
	}
	function get_sizes()
	{
		$q = $this->db->query("SELECT OG.opt_grp_id, O.opt_id, O.opt_name FROM `tbloption_group` OG
								JOIN tbloption O ON O.opt_grp_id = OG.opt_grp_id
								WHERE OG.opt_grp_name = 'size' OR OG.opt_grp_name = 'sizes';");
		return $q->result();			
	}	
	function can_delete($opt_grp_id)
	{
		$sql = "SELECT OG.opt_grp_id FROM tbloption_group OG
							NATURAL JOIN tbloption O 
							NATURAL JOIN tblproduct_option PO
							WHERE OG.opt_grp_id = ?";
		$q = $this->db->query($sql, array((int)$opt_grp_id));
		if ($q->num_rows() == 0){
           return true;
        }else{ 
           return false;
        }
	}
}

