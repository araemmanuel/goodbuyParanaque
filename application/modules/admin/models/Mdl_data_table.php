<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Data_Table extends My_Model
{
	var $table = "tblproduct";  
    var $select_column = array("tblproduct.prod_id", "tblproduct_variant.sku",'tblcategory.cat_name',
								"tblsubcategory.subcat_name", 'tblcategory.cat_name',
								'tblproduct.name', "tblproduct.brand",
								'tblproduct.description');  
    var $order_column = array(null, "sku");  
   
    function __construct() 
	{
        parent::__construct();
    }	
	function make_query()  
    {  
	
		
		if(isset($_POST["search"]["value"]))  
        {  
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.quantity, PV.selling_price, PV.purchase_price,
						IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND CONCAT(P.name, SC.subcat_name, C.cat_name, sku) LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC;";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));		   
		} 
		else
		{
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.quantity, PV.selling_price, PV.purchase_price,
						IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1  GROUP by P.prod_id ORDER BY P.prod_id DESC;";
			$q = $this->db->query($sql);	
		}
		return $q->result();	
		
    }  
   function make_datatables()
   {  
        //$this->make_query();  
		
        if($_POST["length"] != -1)  
        {  
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.quantity, PV.selling_price, PV.purchase_price,
						IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
						(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1  GROUP by P.prod_id ORDER BY P.prod_id DESC LIMIT ?, ?";
			$q = $this->db->query($sql, array((int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT P.prod_id, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.quantity, PV.selling_price, PV.purchase_price,
						IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
						(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 GROUP by P.prod_id ORDER BY P.prod_id DESC";
			$q = $this->db->query($sql);
		
		}
        return $q->result();  
    }  
    function get_filtered_data()
	{  
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

