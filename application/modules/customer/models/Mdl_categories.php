<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Categories extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcategory";
    }
	//login
	
	private function get_param_array($subcategs)
	{
		$param_array = array();
		foreach($subcategs as $subcateg)
		{
			array_push($param_array,$subcateg);
		}
		return $param_array;
	}
	
	private function get_param_array_2($categ,$subcategs)
	{
		$param_array = array();
		array_push($param_array,$categ);
		foreach($subcategs as $subcateg)
		{
			array_push($param_array,$subcateg);
		}
		return $param_array;
	}
	
	function get_categ_home()
    {
		$temp_arr = [];
		$cntr = 0;
		/*$q = $this->db->query("
		SELECT DISTINCT SU.subcat_name, CA.cat_name
		FROM tblsubcategory AS SU 
			INNER JOIN tblproduct AS P ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id
			INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id
		WHERE PV.is_active = 1 
			AND PV.is_customer_viewable = 1 
			AND PV.quantity >= 1
		ORDER BY CA.cat_id");*/
		$q = $this->db->query("SELECT DISTINCT CA.cat_name
		FROM tblsubcategory AS SU 
			INNER JOIN tblproduct AS P ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id
			INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id
		WHERE PV.is_active = 1 
			AND PV.is_customer_viewable = 1 
			AND PV.quantity >= 1
		ORDER BY CA.cat_id");
		return $q->result();
    }

    function get_categ()
    {
		$temp_arr = [];
		$cntr = 0;
		$q = $this->db->query("
		SELECT DISTINCT SU.subcat_name, CA.cat_name
		FROM tblsubcategory AS SU 
			INNER JOIN tblproduct AS P ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id
			INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE PV.is_active = 1 
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '158x153' OR img_size IS NULL) 
			AND PV.is_customer_viewable = 1 
			AND PV.quantity > 0
		ORDER BY CA.cat_id");
		return $q->result();
    }
	
	function get_sub_categ($categ)
	{
		$temp_arr = [];
		$cntr = 0;
		$q = $this->db->query("
		SELECT DISTINCT SU.subcat_name 
		FROM tblsubcategory AS SU 
			INNER JOIN tblproduct AS P ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE PV.is_active = 1 
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '158x153' OR img_size IS NULL)
			AND PV.is_customer_viewable = 1 
			AND cat_name = ?
		ORDER BY subcat_name",array($categ));
		return $q->result();
	}
	
}

