<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Transferred_Items extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tbltransferred_items";
    }	

	function get_transfer_ids($sku, $loc)
	{
		$sql = "SELECT T.transfer_id, T.qty_transferred FROM `tbltransferred_items` T 
				LEFT JOIN tbllocation L ON L.loc_id = T.loc_id
				WHERE T.sku = ? AND L.loc_id = ? ORDER BY T.transfer_id ASC";				
		$q = $this->db->query($sql, array($sku, $loc));
		return $q;
	}	
	function get_total_qty($sku)
	{
		$sql = "SELECT SUM(qty_transferred) qty FROM `tbltransferred_items` WHERE sku = ?;";
		$q = $this->db->query($sql, array($sku));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['qty'];
	}
	function get_transferred_items(){
		$q = $this->db->query("SELECT TI.transfer_id, PV.prod_id, PV.sku, P.name, SUM(TI.qty_transferred) AS total_qty, 
								PV.selling_price, MAX(TI.date_transferred) AS last_transfer_date, L.location, L.loc_id,
								(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
										LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id = P.prod_id AND PO.sku =  PV.sku ORDER BY O.opt_name) as options
								FROM `tbltransferred_items` TI 
								LEFT JOIN tblproduct_variant PV ON PV.prod_id = TI.prod_id AND PV.sku = TI.sku
								LEFT JOIN tblproduct P ON P.prod_id = TI.prod_id
								LEFT JOIN tbllocation L ON L.loc_id = TI.loc_id GROUP BY PV.sku, TI.loc_id");
       return $q->result();
    }
	/* Ungrouped transferred items
	SELECT TI.transfer_id, P.sku, P.name, TI.qty_transferred, P.selling_price, TI.date_transferred, L.location 
								FROM `tbltransferred_items` TI
								JOIN tblproduct P ON P.prod_id = TI.prod_id
								JOIN tbllocation L ON L.loc_id = TI.loc_id;
	*/
	function receive_sku_exists($sku)
	{		
		$sql = "SELECT P.sku FROM `tbltransferred_items` TI
				JOIN tblproduct P ON P.prod_id = TI.prod_id
				WHERE P.sku = ?";
		$q = $this->db->query($sql, array($sku));
		if($q->num_rows() == 1)
			return true;
		else
			return false;
	}
	function get_receive_prod_info($sku)
	{
		$sql = "SELECT PV.sku, SUM( TI.qty_transferred) AS total_qty,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
							LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = ? ORDER BY O.opt_name) as options
					FROM tbltransferred_items TI
                    LEFT JOIN tblproduct P ON P.prod_id = TI.prod_id 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = TI.prod_id AND PV.sku = TI.sku 
					LEFT JOIN tblproduct_option PO ON PO.prod_id = TI.prod_id AND PO.sku = TI.sku
					WHERE PV.sku = ? GROUP BY PV.sku;";				
		$q = $this->db->query($sql, array($sku, $sku));
		return $q->result();
	}


	function dt_product_qry()
	{
		
		if($_POST["length"] != -1)  
        {  
			$sql = "SELECT P.prod_id,PV.sku sku2, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable, PV.quantity, PV.selling_price, PV.purchase_price,
								IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND CONCAT(P.name, SC.subcat_name, C.cat_name, sku) LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC LIMIT ?, ?";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT P.prod_id,PV.sku sku2, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable,  PV.quantity, PV.selling_price, PV.purchase_price,
									IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND CONCAT(P.name, SC.subcat_name, C.cat_name, sku) LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC";
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		}
		return $q;
	}
   function make_datatables()
   {  
		$q = $this->dt_product_qry();
        return $q->result();  
    }  
    function get_filtered_data()
	{  
		$sql = "SELECT P.prod_id,PV.sku sku2, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable,  PV.quantity, PV.selling_price, PV.purchase_price,
									IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
								(SELECT  IF( COUNT(sku) = 1, sku, 'has_multiple_var') FROM tblproduct_variant WHERE prod_id = P.prod_id AND is_active = 1) AS prod_var
								FROM `tblproduct` P 
								NATURAL JOIN tblsubcategory SC
								NATURAL JOIN tblcategory C	
								NATURAL JOIN tblproduct_variant PV
                                WHERE PV.is_active = 1 AND CONCAT(P.name, SC.subcat_name, C.cat_name, sku) LIKE ? GROUP by P.prod_id ORDER BY P.prod_id DESC";
		$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT P.prod_id, PV.sku sku2, P.name, P.description, P.brand, SC.subcat_name, C.cat_name, PV.is_customer_viewable,  PV.quantity, PV.selling_price, PV.purchase_price,
					IF(CHAR_LENGTH(C.cat_code) = 1, SUBSTR(PV.`sku`, 1, 4), SUBSTR(PV.`sku`, 1, 5)) AS sku,		
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

