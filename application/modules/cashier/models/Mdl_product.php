<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Product extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblproduct";
    }
	
	//FIXED QUERIES
	// 2019/09/28 Sigrid add start ----------------------->
	function get_total_qty($prod_id)
	{
		$sql = "SELECT SUM(quantity) total_qty FROM tblproduct_variant WHERE prod_id = ?";
		$q = $this->db->query($sql, array($prod_id));
		$row = $q->row_array();
		if (isset($row))
			return $row['total_qty'];	
	}
	// 2019/09/28 Sigrid end start ----------------------->
	function show_online($value,$id)
	{
		$sql = "UPDATE `tblproduct_variant` SET is_customer_viewable = ? WHERE prod_id = ?";
		if($this->db->query($sql, array($value, (int)$id)))
			return true;
		else
			return false;
	}
	function has_variant($prod_id) 
	{
		
		$sql = "SELECT PO.sku FROM `tblproduct_option` PO WHERE PO.prod_id = ?";
		$q = $this->db->query($sql, $prod_id);
		if($q->num_rows() > 1)
			return true;
		else
			return false;
		
	}	
	function get_prod_info($id)
	{		
		$sql = "SELECT P.prod_id, P.name, P.brand, P.description, SC.subcat_name, C.cat_name, C.cat_id FROM tblproduct P
					NATURAL JOIN tblsubcategory SC
					NATURAL JOIN tblcategory C	
					WHERE prod_id = ?";
		$q = $this->db->query($sql, array($id));
		return $q->result();
	}
	function get_products(){
		$q = $this->db->query("SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.sku, PV.quantity, PV.selling_price, PV.purchase_price,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 GROUP by P.prod_id ORDER BY P.prod_id DESC");							
       return $q->result();
    }	
	function get_product_names($srch, $type = null)
	{
		if($type == 'active')
		{
			$sql = "SELECT CONCAT(PO.sku, 'SKU:', '<b>Name:</b> ', P.name, ', <b>Attributes:</b> ', GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)) name FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = PO.prod_id AND PV.sku = PO.sku
						LEFT JOIN tblproduct P ON P.prod_id = PO.prod_id
						WHERE PV.is_active = 1 AND P.name LIKE ? GROUP BY PO.SKU";
		}
		else
		{
			$sql = "SELECT CONCAT(PV.is_active, 'STATUS:', PO.sku, 'SKU:', '<b>Name:</b> ', P.name, ', <b>Attributes:</b> ', GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)) name FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = PO.prod_id AND PV.sku = PO.sku
						LEFT JOIN tblproduct P ON P.prod_id = PO.prod_id
						WHERE P.name LIKE ? GROUP BY PO.SKU";
		}
		$q = $this->db->query($sql, array('%'.$srch.'%'));
		return $q->result();	
	}
	
	function get_pcode_num($cat_id, $subcat_id, $exempted_sku = null)
	{
		if($exempted_sku)
		{
			$sql = "SELECT MAX(CAST(digits(PV.`sku`) AS INT)) AS m FROM `tblproduct_variant` PV
					JOIN tblproduct P ON P.prod_id = PV.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id	
					WHERE  P.`subcat_id` = ? AND S.cat_id = ? AND NOT PV.sku = ?";
			$q = $this->db->query($sql, array($subcat_id, $cat_id, $exempted_sku));			
		}	
		else
		{
			$sql = "SELECT MAX(CAST(digits(PV.`sku`) AS INT)) AS m FROM `tblproduct_variant` PV
					JOIN tblproduct P ON P.prod_id = PV.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id	
					WHERE  P.`subcat_id` = ? AND S.cat_id = ?";
		
			$q = $this->db->query($sql, array($subcat_id, $cat_id));
		}
		$row = $q->row_array();
		if (isset($row))
			return $row['m'] + 1;
	}

	//UNFIXED


	function get_prod_id($id)
	{
		$sql = "SELECT prod_id FROM tblproduct WHERE prod_id = ?";
		$q = $this->db->query($sql, $id);
		return $q->result();	
	} 
   function make_datatables()
   {  
		
        if(isset($_POST["length"]) && $_POST["length"] != -1)  
        {  
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.sku, PV.quantity, PV.selling_price, PV.purchase_price,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND P.name LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC LIMIT ?, ?";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.sku, PV.quantity, PV.selling_price, PV.purchase_price,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND P.name LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		
		}
        return $q->result();  
    }  
    function get_filtered_data()
	{  
       $sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.sku, PV.quantity, PV.selling_price, PV.purchase_price,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 GROUP by P.prod_id ORDER BY P.prod_id DESC";
		$q = $this->db->query($sql);
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.sku, PV.quantity, PV.selling_price, PV.purchase_price,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 GROUP by P.prod_id ORDER BY P.prod_id DESC";
		$q = $this->db->query($sql);
		return $q->num_rows();
    }  
	
}
/*

/*

//for custom auto complete
SELECT CONCAT(P.name, ' Options:', CONCAT(GROUP_CONCAT(O.opt_name))) AS name FROM tblProduct P
				JOIN tblproduct_option PO ON PO.prod_id = P.prod_id
				JOIN tbloption O ON O.opt_id = PO.option_id
				WHERE P.name LIKE ? GROUP BY P.prod_id

*/
