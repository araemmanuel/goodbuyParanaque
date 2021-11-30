<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Non_Saleable extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblnon_saleable_items";
    }	


	function get_items()
	{
		$sql = "SELECT NS.id, NS.date_added, NS.description, NS.qty AS qty, P.name, NS.sku,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
										LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
										WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
				FROM `tblnon_saleable_items` NS 
				LEFT JOIN tblproduct P ON P.prod_id = NS.prod_id";
		$q = $this->db->query($sql);
		return $q->result();
	}	
	function get_return_items($trans_id)
	{
		$sql = "SELECT sku, qty FROM `tblnon_saleable_items` WHERE trans_id = ?";
		$q = $this->db->query($sql, array((int)$trans_id));
		return $q->result();
	}	
}

