<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Product_Variant extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblproduct_variant";
    }
	function get_pv_info($sku)
	{
		$sql = "SELECT CONCAT(PV.sku,'#', P.name,'#',PV.selling_price, '#', PV.quantity, '#',
		 (SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) AS options 
		 FROM tblproduct_option PO LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = PV.sku))
				FROM `tblproduct_variant` PV
				LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
				WHERE sku = ?";
		$q = $this->db->query($sql, array($sku));
		return $q->result();
	}	
	function get_count_var($prod_id)
	{
		$sql = "SELECT COUNT(sku) AS m  FROM `tblproduct_variant` WHERE prod_id = ?;";
		$q = $this->db->query($sql, array((int)$prod_id));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}

	function get_min_qty_pvs($reorder_point)
	{
		$sql = "SELECT P.prod_id, PV.sku, P.name, PV.quantity, PV.selling_price,
				(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
										NATURAL JOIN tblproduct_variant PVS 
										WHERE PVS.prod_id = PV.prod_id AND PVS.sku = PV.sku AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
							LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
							WHERE PO.sku = PV.sku ORDER BY O.opt_name) as options
				FROM `tblproduct_variant` PV
				LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id
				WHERE PV.quantity = ? AND PV.is_active = 1;";
		$q = $this->db->query($sql, array((int)$reorder_point));
       return $q->result();
	}
	function get_discount($id, $sku)
	{
		$sql = "SELECT prod_id, sku, discount_percent FROM `tblproduct_variant` WHERE prod_id = ? AND sku = ?";
		$q = $this->db->query($sql, array($id, $sku));
		return $q->result();
	}	
	/*
	,(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
	LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = ? ORDER BY O.opt_name) as options
	
	*/
	function get_sale_prod_info($sku)
	{
		$sql = "SELECT PV.sku, P.name, PV.selling_price,  C.cat_name, PV.quantity
				FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
				NATURAL JOIN tblproduct_option PO
				NATURAL JOIN tblsubcategory SC
				NATURAL JOIN tblcategory C 
				WHERE PV.sku = ? GROUP BY PV.sku;";
		$q = $this->db->query($sql, array($sku));
       return $q->result();
	}
		
	function has_primary_image($prod_id, $sku)
	{

		$sql = "SELECT I.img_id FROM tblimage I
					LEFT JOIN tblproduct_variant PV ON I.prod_id = PV.prod_id AND I.sku = PV.sku
					WHERE PV.prod_id = ? AND PV.sku = ? AND I.img_type = 'primary';";
		$q = $this->db->query($sql, array($prod_id, $sku));
		if($q->num_rows() == 1)
			return true;
		else
			return false;

	}
	function get_skus($s) 
	{
		$sql = "SELECT CONCAT(is_active,'DIV',sku,'DIV',sku) as result FROM `tblproduct_variant` WHERE NOT quantity = 0 AND sku LIKE ?
				UNION 
				SELECT CONCAT(PV.is_active, 'DIV','<b>Name:</b> ', P.name, ', <b>Attributes:</b> ', GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC), 'DIV',PO.sku) name FROM tblproduct_option PO 
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = PO.prod_id AND PV.sku = PO.sku 
					LEFT JOIN tblproduct P ON P.prod_id = PO.prod_id WHERE PV.is_active = 1
					AND NOT PV.quantity = 0 AND P.name LIKE ? GROUP BY PO.SKU";
		$q = $this->db->query($sql, array($s.'%', $s.'%'));
       return $q->result();
	}	
	
	function get_pv_skus($prod_id) // for updating skus when product changes subcategory and category
	{
		$sql = "SELECT sku FROM `tblproduct_variant` WHERE prod_id = ?;";
		$q = $this->db->query($sql, array((int)$prod_id));
       return $q;
	}
	//Reactivate Product Variant
	function is_inactive($prod_id, $sku)
	{
		$sql = "SELECT `prod_id` FROM tblproduct_variant WHERE `prod_id` = ? AND sku = ? AND `is_active` = 0";
		$q = $this->db->query($sql, array($prod_id, $sku));
		if($q->num_rows() == 1)
			return true;
		else
			return false;
	}

	//Used to check whether product ID has given option
	function get_options($prod_id, $option)
	{
		$sql = "SELECT PO.sku FROM tblproduct_option PO
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id
					WHERE PO.prod_id = ? AND O.opt_name = ?";
		$q = $this->db->query($sql, array($prod_id, $option));
		return $q;
	}
	function update_prod_discount($id, $data)
	{
		$table = $this->get_table();
        $this->db->where('prod_id', $id);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	function update($id,$sku,$data)
	{
		$table = $this->get_table();
        $this->db->where('prod_id', $id);
		$this->db->where('sku', $sku);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	function get_batch_by_date($date_from, $date_to)
	{
		$sql = "SELECT PV.sku, P.name, PV.selling_price,
				(SELECT SUM(SHS.qty)  FROM tblstock_history SHS 
				 WHERE SHS.sku = PV.sku AND SHS.date_added >= ? AND SHS.date_added <= ? GROUP BY SHS.sku) as quantity, 

				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)  FROM tblproduct_option PO 
				 LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
				 WHERE PO.sku = PV.sku ORDER BY O.opt_name) as options FROM `tblproduct_variant` PV 
				 
				 LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id 
				 LEFT JOIN tblstock_history SH ON SH.prod_id = PV.prod_id AND SH.sku = PV.sku 
				 WHERE SH.date_added >= ? AND SH.date_added <= ? AND PV.is_active = 1 GROUP BY SH.sku";
		$q = $this->db->query($sql, array($date_from, $date_to, $date_from, $date_to));
		return $q->result();
	}
	function get_tag_info_by_date($date_from, $date_to)
	{
		$sql = "SELECT PV.sku, P.name, PV.selling_price,
				(SELECT SUM(SHS.qty)  FROM tblstock_history SHS 
				 WHERE SHS.sku = PV.sku AND SHS.date_added >= ? AND SHS.date_added <= ? GROUP BY SHS.sku) as quantity, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)  FROM tblproduct_option PO 
				 LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
				 WHERE PO.sku = PV.sku ORDER BY O.opt_name) as options,
				(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
				 NATURAL JOIN tblproduct_variant PVS 
				 WHERE PVS.prod_id = PV.prod_id AND PVS.sku = PV.sku AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image
				 FROM `tblproduct_variant` PV
				 
				 LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id 
				 LEFT JOIN tblstock_history SH ON SH.prod_id = PV.prod_id AND SH.sku = PV.sku 
				 WHERE SH.date_added >= ? AND SH.date_added <= ? AND PV.is_active = 1 GROUP BY SH.sku";
		$q = $this->db->query($sql, array($date_from, $date_to, $date_from, $date_to));
		return $q->result();	
	}	
	function get_tag_info($id, $sku)
	{
		$sql = "SELECT P.prod_id, PV.sku, P.name, PV.selling_price, 
				(SELECT SUM(SHS.qty) FROM tblstock_history SHS 
				WHERE SHS.sku = PV.sku GROUP BY SHS.sku) as quantity, 
				IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path) AS primary_image FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
                NATURAL JOIN tblimage I
				WHERE P.prod_id = ? AND PV.sku = ? AND I.img_size = '349x230' AND I.img_type = 'primary'";
		$q = $this->db->query($sql, array((int)$id, $sku));
		return $q->result();	
	}
	function get_tag_info2($sku)
	{
		$sql = "SELECT P.prod_id, PV.sku, P.name, PV.selling_price, PV.quantity, IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path) AS primary_image FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
                NATURAL JOIN tblimage I
				WHERE PV.sku = ? AND I.img_size = '349x230' AND I.img_type = 'primary'";
		$q = $this->db->query($sql, array($sku));
		return $q->result();	
	}
	function get_pv($id, $sku, $active = 1){
		$sql = "SELECT P.prod_id, PV.sku, PV.is_customer_viewable,
				
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id = ? AND PO.sku = ? ORDER BY O.opt_name) as options, 
				
				(SELECT GROUP_CONCAT(I.img_file_path) FROM tblimage I
						NATURAL JOIN tblproduct_variant PV
						WHERE PV.prod_id = ? AND PV.sku = ? AND I.img_type = 'others' AND I.img_size = '349x230') as other_images,
		  
				(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
						NATURAL JOIN tblproduct_variant PV 
						WHERE PV.prod_id = ? AND PV.sku = ? AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image,

						P.name, P.brand, P.description, PV.selling_price, PV.purchase_price, PV.quantity, SC.subcat_name, C.cat_name, SC.subcat_id,
				C.cat_id FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
				NATURAL JOIN tblproduct_option PO
				NATURAL JOIN tbloption O 
				NATURAL JOIN tblsubcategory SC
				NATURAL JOIN tblcategory C 
				WHERE P.prod_id = ? AND PV.sku = ? AND PV.is_active = ? GROUP BY P.prod_id ";
		$q = $this->db->query($sql, array((int)$id, $sku, (int)$id, $sku, (int)$id, $sku, (int)$id, $sku, (int)$active));
       return $q->result();
    }
	function get_pvs_for_sales_mng()
	{
		$sql =  "SELECT P.prod_id, PV.sku, SC.subcat_name, C.cat_name, P.name, P.brand, PV.selling_price, PV.quantity,
				
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id = PV.prod_id AND PO.sku = PV.sku ORDER BY O.opt_name) as options, 
						  
				(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
						NATURAL JOIN tblproduct_variant PVS 
						WHERE PVS.prod_id = PV.prod_id AND PVS.sku = PV.sku AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image
                FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
				NATURAL JOIN tblsubcategory SC
				NATURAL JOIN tblcategory C 
				WHERE PV.is_active = 1 GROUP BY P.prod_id ";
		$q = $this->db->query($sql);
		return $q->result();		
	}
	function get_pvs(){
		$sql = "SELECT P.prod_id, PV.sku, P.name, P.brand, PV.selling_price, PV.purchase_price, PV.quantity,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id = P.prod_id AND PO.sku =  PV.sku ORDER BY O.opt_name) as options
			    FROM  tblproduct_variant PV
				LEFT JOIN `tblproduct` P  ON P.prod_id = PV.prod_id
				WHERE PV.is_active = 1 ";
		$q = $this->db->query($sql);
		return $q->result();
    }
	
	function get_for_edit($id, $sku){
		$sql = "
	SELECT P.prod_id, PV.sku, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id = ? AND PO.sku = ?) as options, 
				
				(SELECT GROUP_CONCAT(I.img_file_path) FROM tblimage I
						NATURAL JOIN tblproduct_variant PV
						WHERE PV.prod_id = ? AND PV.sku = ? AND I.img_type = 'others' AND I.img_size = '349x230') as other_images,
		  
				(SELECT IF(COUNT(I.img_file_path) = 0,'None', I.img_file_path)  FROM tblimage I
						NATURAL JOIN tblproduct_variant PV 
						WHERE PV.prod_id = ? AND PV.sku = ? AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image,

						P.name, P.brand, P.description, PV.selling_price, PV.purchase_price, PV.quantity, SC.subcat_name, C.cat_name, SC.subcat_id,
				C.cat_id FROM `tblproduct` P 
				NATURAL JOIN tblproduct_variant PV 
				NATURAL JOIN tblproduct_option PO
				NATURAL JOIN tbloption O 
				NATURAL JOIN tblsubcategory SC
				NATURAL JOIN tblcategory C 
				WHERE P.prod_id = ? AND PV.sku = ? GROUP BY P.prod_id";
		$q = $this->db->query($sql, array((int)$id, $sku, (int)$id, $sku, (int)$id, $sku, (int)$id, $sku));
       return $q;
    }
	
	
	function get_by_prod($prod_id, $type=null){
		$sql = "SELECT P.prod_id, PV.sku, P.name, P.brand, PV.quantity, PV.purchase_price, PV.selling_price, PV.is_customer_viewable,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id =  P.prod_id AND PO.sku =  PV.sku) as options
					FROM tblproduct_variant PV 
					JOIN `tblproduct` P ON P.prod_id = PV.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
					WHERE PV.prod_id = ? AND is_active = 1";
		if($type == 'scanned')
			$sql ="SELECT PV.sku, P.name, PV.quantity, PV.scanned_qty, PV.selling_price, PV.date_scanned, 
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.prod_id =  P.prod_id AND PO.sku =  PV.sku) as options
						FROM `tblproduct_variant` PV
						JOIN tblproduct P ON P.prod_id = PV.prod_id
						WHERE `is_scanned` = 1";		
									
		$q = $this->db->query($sql, (int)$prod_id);			
        return $q->result();	
    }
	function get_stock_on_hand($id, $sku)
	{
		$sql = "SELECT quantity AS m FROM `tblproduct_variant` WHERE prod_id = ? AND sku = ?";
		$q = $this->db->query($sql, array($id, $sku));
	    $row = $q->row_array();
		if (isset($row))
			return $row['m'];
	}

	function update_qty($id, $sku, $value)
	{
		
		$sql = "UPDATE `tblproduct_variant` SET quantity = ? WHERE prod_id = ? AND sku = ?";
		if($this->db->query($sql, array($value, (int)$id, $sku)))
			return true;
		else
			return false;
	}
	function get_file_paths($id, $sku, $img_type)
	{			
		$sql = "SELECT I.img_file_path FROM `tblproduct_variant` PV NATURAL JOIN tblimage I
				WHERE PV.prod_id = ? AND PV.sku =  ? AND I.img_type = ?";
		$q = $this->db->query($sql, array((int)$id, $sku, $img_type));			
       return $q;		
	}
	function delete_imgs($id, $sku, $type)
	{
		$sql = "DELETE FROM `tblImage` WHERE prod_id = ? AND sku = ? AND img_type = ?";
		if($this->db->query($sql, array((int)$id, $sku, $type)))
			return true;
		else
			return false;
	}
	function get_product_codes($s){
		$sql = "SELECT CONCAT(PV.is_active,'DIV',PV.sku,'DIV',PV.sku) as result FROM  tbltransferred_items TI 
								LEFT JOIN `tblproduct_variant` PV ON PV.prod_id = TI.prod_id AND PV.sku = TI.sku
								WHERE PV.is_active = 1 AND TI.sku LIKE ? GROUP BY PV.sku
								UNION 
								SELECT CONCAT(PV.is_active, 'DIV','<b>Name:</b> ', P.name, ', <b>Attributes:</b> ', GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC), 'DIV',PO.sku) name 
								FROM  tbltransferred_items TI 
								LEFT JOIN  tblproduct_option PO ON PO.prod_id = TI.prod_id AND PO.sku = TI.sku 
									LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
									LEFT JOIN tblproduct_variant PV ON PV.prod_id = PO.prod_id AND PV.sku = PO.sku 
									LEFT JOIN tblproduct P ON P.prod_id = PO.prod_id WHERE PV.is_active = 1
									AND NOT PV.quantity = 0 AND P.name LIKE ? GROUP BY PO.SKU";			
		
			$q = $this->db->query($sql, array('%'.$s.'%', '%'.$s.'%'));
			return $q->result();
    }
		
	function reset_physical_inventory()
	{
		return $this->db->query("UPDATE `tblproduct_variant` SET `is_scanned`= 0,`scanned_qty`=0,`date_scanned` = NULL WHERE `is_scanned` = 1 ");	
	}
	
}
/*
		$sql = "SELECT PO.prod_id, PO.sku FROM `tblproduct_option` PO
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id
					NATURAL JOIN tblproduct P 
					WHERE P.name = ? $param_str;";	

		$sql = "SELECT PO.sku FROM `tblproduct_option` PO
					LEFT JOIN tblproduct P ON P.prod_id = PO.prod_id
					LEFT JOIN tbloption O ON O.opt_id = PO.option_id
					WHERE PO.prod_ids = ? $param_str";

	private function get_param_array($val, $options)//options must be in string datatype
	{
		$param_array = array();
		array_push($param_array, $val);
		//$options = explode(',', $options);
		foreach($options as $o)
		{
			array_push($param_array, $o);
		}	
		return $param_array;
	}
	private function get_param_str($param_array_size, $options_size)
	{
		$param_str = '';
		for($i=0;$i<$param_array_size - 1;$i++)
		{
			if($options_size >= 1)
				$param_str = $param_str . '#O.opt_name = ?';
		}
		return  str_replace('#', ' AND ', trim($param_str));
	}
	*/

