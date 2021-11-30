<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Product extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblproduct";
    }
	
	function get_max_price_h(){
		$q_string ="
		SELECT MAX(selling_price) as MAX_SELL_P
		FROM tblproduct_variant PV
			INNER JOIN tblproduct PROD ON PROD.prod_id = PV.prod_id
			INNER JOIN tblsubcategory SU ON SU.subcat_id = PROD.subcat_id
			INNER JOIN tblcategory CAT ON SU.cat_id = CAT.cat_id
		WHERE is_customer_viewable = 1
			AND is_active = 1
			AND quantity > 0
		";
		$q = $this->db->query($q_string,array())->row()->MAX_SELL_P;
		return $q;
	}

	function get_max_price_sub($categ,$subcateg){
		$q_string ="
		SELECT MAX(selling_price) as MAX_SELL_P
		FROM tblproduct_variant PV
			INNER JOIN tblproduct PROD ON PROD.prod_id = PV.prod_id
			INNER JOIN tblsubcategory SU ON SU.subcat_id = PROD.subcat_id
			INNER JOIN tblcategory CAT ON SU.cat_id = CAT.cat_id
		WHERE CAT.cat_name = ?
			AND SU.subcat_name = ?
			AND is_customer_viewable = 1
			AND is_active = 1
			AND quantity > 0
		";
		$q = $this->db->query($q_string,array($categ,$subcateg))->row()->MAX_SELL_P;
		return $q;
	}
	
	function get_max_price($categ){
		$q_string ="
		SELECT MAX(selling_price) as MAX_SELL_P
		FROM tblproduct_variant PV
			INNER JOIN tblproduct PROD ON PROD.prod_id = PV.prod_id
			INNER JOIN tblsubcategory SU ON SU.subcat_id = PROD.subcat_id
			INNER JOIN tblcategory CAT ON SU.cat_id = CAT.cat_id
		WHERE CAT.cat_name = ?
			AND is_customer_viewable = 1
			AND is_active = 1
			AND quantity > 0
		";
		$q = $this->db->query($q_string,array($categ))->row()->MAX_SELL_P;
		return $q;
	}
	
	function get_top_products_of_month()
	{
		$q_string = "
		SELECT DISTINCT P.prod_id, P.name ,P.description ,P.brand,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent , PV.is_active , PV.is_customer_viewable ,PV.sku, PV.is_scanned, IM.img_id, IM.img_file_path, IM.img_size, IM.img_type FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku
			INNER JOIN tblproduct_variant_analytics PVA ON PVA.sku = PV.sku
		WHERE PV.is_customer_viewable = 1 
			AND is_active = 1 
			AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL)
			AND (img_size = '158x153' OR img_size IS NULL)
			AND PVA.month = ? AND PVA.year = ?
		ORDER BY PVA.view_count DESC LIMIT 20";
		$q = $this->db->query($q_string,array(date('m'),date('Y')));
		return $q->result();
	}
	
	function get_product_brands_all()
	{
		$q_string = "
		SELECT DISTINCT brand FROM tblproduct AS P 
			INNER JOIN tblproduct_variant AS PV ON P.prod_id = PV.prod_id 
		WHERE PV.is_customer_viewable = 1
			AND PV.is_active = 1
			AND quantity >= 1
			AND brand <> 'No Brand'";
		$q = $this->db->query($q_string);
		return $q->result();
	}
	
	function get_product_brands($categ)
	{
		$q_string = "
		SELECT DISTINCT brand FROM tblproduct AS P 
			INNER JOIN tblproduct_variant AS PV ON P.prod_id = PV.prod_id 
			INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
		WHERE PV.is_customer_viewable = 1
			AND brand <> 'No Brand' 
			AND cat_name = ?";
		$q = $this->db->query($q_string,array($categ));
		return $q->result();
	}
	
	function get_stock_on_hand_for_edit($prod_sku)
	{
		$q_string = "
		SELECT quantity FROM tblproduct_variant
		WHERE sku = ?
		";
		return $this->db->query($q_string,array($prod_sku))->row()->quantity;
	}
	
	function get_product_brands_sub($categ,$subcateg)
	{
		$q_string = "
		SELECT DISTINCT brand
		FROM tblproduct AS P
		INNER JOIN tblproduct_variant AS PV ON P.prod_id = PV.prod_id
		INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id
		INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id
		WHERE PV.is_customer_viewable =1
		AND brand <>  'No Brand'
		AND subcat_name =  ?
		AND cat_name = ?";
		$q = $this->db->query($q_string,array($subcateg,$categ));
		return $q->result();
	}
	
	
	function get_data_product(){
		$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t');
		$q = $this->db->query("
		SELECT DISTINCT P.prod_id, P.name ,P.description ,P.brand,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent , PV.is_active , PV.is_customer_viewable ,PV.sku, PV.is_scanned, IM.img_id, IM.img_file_path, IM.img_size, IM.img_type FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE PV.is_customer_viewable = 1 
			AND is_active = 1 
			AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL)
			AND (img_size = '158x153' OR img_size IS NULL)
			AND PV.date_added BETWEEN ? AND ?
		ORDER BY RAND() LIMIT 20",array($first_day_this_month,$last_day_this_month));
		//echo $first_day_this_month." ".$last_day_this_month;
       return $q->result();
    }
	
	function get_data_product_per_categ(){
		$q = $this->db->query("
		SELECT DISTINCT  P.prod_id, P.name ,P.description ,P.brand ,P.subcat_id ,SU.subcat_code ,SU.subcat_name ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,cat_code, cat_name FROM tblproduct_variant AS PV
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
			INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
		WHERE PV.is_customer_viewable = 1 
			AND is_active = 1 
			AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '158x153' OR img_size IS NULL) 
		ORDER BY CA.cat_id");
       return $q->result();
    }

    function get_data_product_categ($category){
		$q_string = "
		SELECT DISTINCT P.prod_id, P.name ,P.description ,P.brand ,P.subcat_id ,SU.subcat_code ,SU.subcat_name ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,cat_code, cat_name FROM tblproduct AS P 
			INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
			INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id 
		WHERE CA.cat_name = ? 
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '158x153' OR img_size IS NULL) 
			AND is_customer_viewable = '1'
AND quantity > 0
			AND is_active = '1'";
        $q = $this->db->query($q_string,array($category));
       return $q->result();
    }
	
	function get_data_product_subcateg($category,$sub_category){
		$q_string = "
		SELECT P.prod_id, P.name ,P.description ,P.brand ,P.subcat_id ,SU.subcat_code ,SU.subcat_name ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,cat_code, cat_name FROM tblproduct AS P 
			INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblproduct_variant AS PV ON PV.prod_id = P.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
			INNER JOIN tblcategory AS CA ON SU.cat_id = CA.cat_id 
		WHERE SU.subcat_name = ? 
			AND CA.cat_name = ?
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '158x153' OR img_size IS NULL) 
			AND is_customer_viewable = '1' 
			AND quantity > 0
			AND is_active = '1'";
        $q = $this->db->query($q_string,array($sub_category,$category));
       return $q->result();
    }
	
	function getOptions($prod_id)
	{
		$q_string = "SELECT prod_id FROM tblproduct_variant WHERE sku = ?";
		$q = $this->db->query($q_string,array($prod_id));
		$q_res = $q->row();
		$q_string_1 = "
		SELECT PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,opt_name, opt_grp_name, O.opt_id, OG.opt_grp_id 
		FROM tblproduct_option AS PO
			INNER JOIN tblproduct_variant AS PV ON PO.sku = PV.sku 
			INNER JOIN tbloption AS O ON O.opt_id = PO.option_id 
			INNER JOIN tbloption_group AS OG ON OG.opt_grp_id = O.opt_grp_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PO.sku
		WHERE PO.prod_id = ? 
			AND (img_size = '349x230' OR img_size IS NULL) 
			AND (img_type = 'primary' OR img_type IS NULL) 
AND quantity > 0
			AND is_active = 1 AND is_customer_viewable = 1 
		ORDER BY OG.opt_grp_id";
		$next_q = $this->db->query($q_string_1,array($q_res->prod_id))->result();
		return $next_q;
	}
	
	function getProducts_Option($prod_id)
	{
		$q_string = "
		SELECT * FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct_option AS PO ON PV.prod_id = PO.prod_id 
			INNER JOIN tbloption AS O ON O.opt_id = PO.option_id 
			INNER JOIN tbloption_group AS OG ON OG.opt_grp_id = O.opt_grp_id 
		WHERE PV.sku = ? 
			AND opt_grp_name = 'Color' 
			AND is_active = 1 
			AND is_customer_viewable = 1";
		$q = $this->db->query($q_string,array($prod_id));
		$q_string_1 = "
		SELECT * FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct_option AS PO ON PV.prod_id = PO.prod_id 
			INNER JOIN tbloption AS O ON O.opt_id = PO.option_id 
			INNER JOIN tbloption_group AS OG ON OG.opt_grp_id = O.opt_grp_id 
		WHERE PV.sku = ? 
			AND opt_grp_name = 'Size' 
			AND is_active = 1 
AND quantity > 0
			AND is_customer_viewable = 1";
		$r = $this->db->query($q_string_1,array($prod_id));
		if($q->num_rows() > 1 && $r->num_rows() >= 1)
		{
			return "has_colors_sizes";
		}
		else if($q->num_rows > 1)
		{
			return "has_colors";
		}
		else if($r->num_rows() >= 1)
		{
			return "has_sizes";
		}
		else
		{
			return "no_options";
		}
	}
	
	function get_stock($sku,$opt_color_id,$opt_size_id)
	{
		$q_string = "SELECT prod_id FROM tblproduct_variant WHERE sku = ?";
		$q = $this->db->query($q_string,array($sku))->row()->prod_id;
		if($opt_size_id == NULL)
		{
			$q_string_1 = "SELECT DISTINCT * FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct_option AS PO ON PV.prod_id = PO.prod_id INNER JOIN tblproduct AS PROD ON PROD.prod_id = PV.prod_id
			WHERE option_id = ?";
			$sku = $this->db->query($q_string_1,array($opt_color_id));
			return $sku->row();
		}
		else
		{
			//$sku = $this->db->query("SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct_option AS PO ON PV.prod_id = PO.prod_id WHERE PO.prod_id = ".$q." AND option_id IN (".$opt_color_id.",".$opt_size_id.")");
			$q_string_2 = "SELECT * FROM tblproduct_option WHERE prod_id = ? AND option_id = ?";
			$sku = $this->db->query($q_string_2,array($q,$opt_color_id));
			if($sku->num_rows() > 1)
			{
				$cntrr = 0;
				foreach($sku->result_array() as $row)
				{
					$sku0 = $row['sku'];
					$q_string_3 = "SELECT DISTINCT * FROM tblproduct_option WHERE prod_id = ? AND option_id = ? AND sku = ?";
					$sku1 = $this->db->query($q_string_3,array($q,$opt_size_id,$sku0));
						if($sku1->num_rows() == 1)
						{
							$cntrr++;
							$sku_f = $sku1->row()->sku;
							$q_string_4 = "SELECT PV.prod_id,PV.sku, PV.quantity, PV.selling_price, PV.discount_percent,brand, (SELECT GROUP_CONCAT(DISTINCT OPT.opt_name SEPARATOR ', ') AS Properties
							FROM tbloption AS OPT INNER JOIN tbloption_group AS OPTG ON OPT.opt_grp_id = OPTG.opt_grp_id INNER JOIN tblproduct_option AS PROO ON PROO.option_id = OPT.opt_id INNER JOIN tblproduct_variant AS PV ON PV.sku = PROO.sku WHERE PV.sku = ? GROUP BY PROO.sku) AS Properties FROM tblproduct_variant AS PV INNER JOIN tblproduct AS PROD ON PROD.prod_id = PV.prod_id WHERE sku = ?";
							$product_details = $this->db->query($q_string_4,array($sku_f,$sku_f));
							return $product_details->result();
						}
				}
				if($cntrr == 0)
				{
					return "No Such Product";
				}
			}
			else
			{
				$sku0 = $sku->row()->sku;
				$q_string_5 = "SELECT * FROM tblproduct_option WHERE prod_id = ? AND option_id = ? AND sku = ?";
				$sku1 = $this->db->query($q_string_5,array($q,$opt_size_id,$sku0));
					if($sku1->num_rows() == 1)
					{
						$sku_f = $sku1->row()->sku;
						$q_string_6 = "SELECT PV.prod_id,PV.sku, PV.quantity, PV.selling_price, PV.discount_percent,brand, (SELECT GROUP_CONCAT(DISTINCT OPT.opt_name SEPARATOR ', ') AS Properties
						FROM tbloption AS OPT INNER JOIN tbloption_group AS OPTG ON OPT.opt_grp_id = OPTG.opt_grp_id INNER JOIN tblproduct_option AS PROO ON PROO.option_id = OPT.opt_id INNER JOIN tblproduct_variant AS PV ON PV.sku = PROO.sku WHERE PV.sku = ? GROUP BY PROO.sku) AS Properties FROM tblproduct_variant AS PV INNER JOIN tblproduct AS PROD ON PROD.prod_id = PV.prod_id WHERE sku = ?";
						$product_details = $this->db->query($q_string_6,array($sku_f,$sku_f));
						return $product_details->result();
					}
					else if($sku1->num_rows() == 0)
					{
						return "No Such Product";
					}
			}
		}
		
	}

	function get_stock_color($sku,$opt_color_id)
	{
		$q_string = "SELECT prod_id FROM tblproduct_variant WHERE sku = ?";
		$q = $this->db->query($q_string,array($sku))->row()->prod_id;
			//$sku = $this->db->query("SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct_option AS PO ON PV.prod_id = PO.prod_id WHERE PO.prod_id = ".$q." AND option_id IN (".$opt_color_id.",".$opt_size_id.")");
			$q_string_2 = "SELECT * FROM tblproduct_option WHERE prod_id = ? AND option_id = ?";
			$sku = $this->db->query($q_string_2,array($q,$opt_color_id));
			if($sku->num_rows() < 1)
			{
				return "No Such Product";
			}
			else
			{
				$q_string_6 = "SELECT PV.prod_id,PV.sku, PV.quantity, PV.selling_price, PV.discount_percent,brand, (SELECT GROUP_CONCAT(DISTINCT OPT.opt_name SEPARATOR ', ') AS Properties
						FROM tbloption AS OPT INNER JOIN tbloption_group AS OPTG ON OPT.opt_grp_id = OPTG.opt_grp_id INNER JOIN tblproduct_option AS PROO ON PROO.option_id = OPT.opt_id INNER JOIN tblproduct_variant AS PV ON PV.sku = PROO.sku WHERE PV.sku = ? GROUP BY PROO.sku) AS Properties FROM tblproduct_variant AS PV INNER JOIN tblproduct AS PROD ON PROD.prod_id = PV.prod_id WHERE sku = ?";
				$product_details = $this->db->query($q_string_6,array($sku->row()->sku,$sku->row()->sku));
				return $product_details->row();
			}
		
	}

	
    function get_specific_data_product($prod_id)
    {
		$q_string = "
		SELECT P.prod_id, P.name ,P.description ,P.brand ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE PV.sku = ?";
        $q = $this->db->query($q_string,array($prod_id));
        //echo 'SELECT * FROM tblproduct WHERE = "' + $prod_id+'"';
       return $q->result();
    }
	
	function get_product_detailed($prod_id)
	{
		$q_string = "
		SELECT P.name, P.brand, P.description, PV.prod_id, PV.sku, PV.quantity, subcat_name, cat_name,img_file_path, PV.selling_price, PV.discount_percent, (SELECT GROUP_CONCAT(DISTINCT OPT.opt_name SEPARATOR ', ') AS Properties
							FROM tbloption AS OPT INNER JOIN tbloption_group AS OPTG ON OPT.opt_grp_id = OPTG.opt_grp_id INNER JOIN tblproduct_option AS PROO ON PROO.option_id = OPT.opt_id INNER JOIN tblproduct_variant AS PV ON PV.sku = PROO.sku WHERE PV.sku = ? GROUP BY PROO.sku) AS Properties FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON PV.sku =  IM.sku 
			INNER JOIN tblsubcategory AS SU ON P.subcat_id = SU.subcat_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
			LEFT JOIN tblclothing_sizes AS CL ON CL.sku = PV.sku
			LEFT JOIN tblcountry_sizes AS CN ON CN.sku = PV.sku
		WHERE PV.is_customer_viewable = 1 
			AND PV.sku = ? 
AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '349x230'OR img_size IS NULL)";
		$q = $this->db->query($q_string,array($prod_id,$prod_id))->row();
		return $q;
	}
	
	
	public function get_option_sizes_with_measures($prod_sku)
	{
		$q_string = "
		SELECT prod_id FROM tblproduct_variant
		WHERE sku = ?";
		$prod_id = $this->db->query($q_string,array($prod_sku))->row()->prod_id;
		$q_string_1 = "
		SELECT DISTINCT CL.prod_id,CL.sku,hip_max_cm,hip_min_cm,waist_max_cm,waist_min_cm,chest_max_cm,chest_min_cm,inseam_max_cm,inseam_min_cm,CN.country_acronym,CN.bra_size,CN.foot_size,body_length_min_cm,body_length_max_cm,shoulder_length_min_cm, shoulder_length_max_cm, shoulder_hole_min_cm, shoulder_hole_max_cm FROM tblproduct_variant AS PV
			LEFT JOIN tblclothing_sizes AS CL
				ON CL.prod_id = PV.prod_id
			LEFT JOIN tblcountry_sizes AS CN 
				ON CN.prod_id = PV.prod_id
		WHERE CL.prod_id = ?";
		$q = $this->db->query($q_string_1,array($prod_id))->result();
		return $q;
	}
	
	private function get_param_array($words)
	{
		$param_array = array();
		$words = explode('%20',$words);
		foreach($words as $word)
		{
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".$word."%");
			array_push($param_array,$word);
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,soundex($word));
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".soundex($word)."%");
		}
		return $param_array;
	}
	
	private function get_param_array_rel($sku,$words)
	{
		$param_array = array();
		$words = explode('%20',$words);
		array_push($param_array,$sku);
		foreach($words as $word)
		{
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".$word."%");
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".soundex($word)."%");
		}
		return $param_array;
	}
	
	private function get_param_array_soundex($words)
	{
		$param_array = array();
		$words = explode('%20',$words);
		foreach($words as $word)
		{
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,soundex($word));
			array_push($param_array,"%".soundex($word)."%");
			array_push($param_array,"%".soundex($word)."%");
		}
		return $param_array;
	}
	
	function search_products($keywords)
	{
		$array_words = array();
		$query = "
		SELECT DISTINCT P.prod_id, P.name ,P.description ,P.brand ,P.subcat_id ,SU.subcat_code ,SU.subcat_name ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,cat_code, cat_name FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			INNER JOIN tblproduct_option AS PO ON PO.sku = PV.sku 
			INNER JOIN tbloption AS O ON PO.option_id = O.opt_id 
			INNER JOIN tbloption_group AS OG ON OG.opt_grp_id = O.opt_grp_id 
			INNER JOIN tblsubcategory AS SU ON SU.subcat_id = P.subcat_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
			LEFT JOIN tblimage AS IM ON PV.sku = IM.sku 
		WHERE PV.is_customer_viewable = 1 
AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '349x230' OR img_size IS NULL) 
			AND ( ";
		
		$words_to_search = explode("%20", $keywords);
		for($cntr = 0; $cntr < count($words_to_search); $cntr++)
		{
			if($cntr > 0)
			{
				$query .= " OR ";
			}
			$query .=  "P.name LIKE ? OR P.description LIKE ? OR CA.cat_name = ? OR opt_grp_name LIKE ? OR opt_name LIKE ? OR soundex(P.name) LIKE ? OR soundex(P.description) LIKE ? OR  soundex(CA.cat_name) = ? OR soundex(opt_grp_name) LIKE ? OR soundex(opt_name) LIKE ? OR SU.subcat_name LIKE ? OR soundex(SU.subcat_name) LIKE ?";
		}
		$query .= " ) ORDER BY PV.sku";
		$search_result = $this->db->query($query,$this->get_param_array($keywords));
		$soundex = "";
		if($search_result->num_rows() < 1)
		{
				echo '"no"';
		}
		else
		{
			echo json_encode($search_result->result());
		}
	}

	function search_products_related($keywords,$sku)
	{
		$array_words = array();
		$query = "
		SELECT DISTINCT P.prod_id, P.name ,P.description ,P.brand ,P.subcat_id ,SU.subcat_code ,SU.subcat_name ,PV.quantity ,PV.selling_price ,PV.purchase_price ,PV.discount_percent ,is_active ,is_customer_viewable ,PV.sku, is_scanned ,img_id ,img_file_path ,img_size ,img_type ,cat_code, cat_name FROM tblproduct_variant AS PV 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			INNER JOIN tblproduct_option AS PO ON PO.sku = PV.sku 
			INNER JOIN tbloption AS O ON PO.option_id = O.opt_id 
			INNER JOIN tbloption_group AS OG ON OG.opt_grp_id = O.opt_grp_id 
			INNER JOIN tblsubcategory AS SU ON SU.subcat_id = P.subcat_id 
			INNER JOIN tblcategory AS CA ON CA.cat_id = SU.cat_id 
			LEFT JOIN tblimage AS IM ON PV.sku = IM.sku 
		WHERE PV.is_customer_viewable = 1 
			AND PV.sku <>  ?
			AND quantity > 0
			AND (img_type = 'primary' OR img_type IS NULL) 
			AND (img_size = '349x230' OR img_size IS NULL) 
			AND ( ";
		
		$words_to_search = explode("%20", $keywords);
		for($cntr = 0; $cntr < count($words_to_search); $cntr++)
		{
			if($cntr > 0)
			{
				$query .= " OR ";
			}
			$query .=  "P.name LIKE ? OR P.description LIKE ? OR soundex(P.name) LIKE ? OR soundex(P.description) LIKE ? ";
		}
		$query .= " )  LIMIT 24";
		$search_result = $this->db->query($query,$this->get_param_array_rel($sku,$keywords));
		$soundex = "";
		if($search_result->num_rows() < 1)
		{
				echo '"no"';
		}
		else
		{
			echo json_encode($search_result->result());
		}
	}

	
}

