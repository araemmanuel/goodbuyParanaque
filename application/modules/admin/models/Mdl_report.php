<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Report extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "";
    }	
	function today_expenses()
	{
		$sql = "SELECT exp_desc, exp_amt FROM `tblexpenses` WHERE exp_date = ?;";
		$q = $this->db->query($sql, array(date('Y-m-d')));	
		return $q->result();
	}
	function inconsistent_stock()
	{
		
		$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
		( SELECT GROUP_CONCAT( DISTINCT O.opt_name
				ORDER BY O.opt_grp_id ASC ) 
				FROM tblproduct_option POT
				LEFT JOIN tbloption O ON O.opt_id = POT.option_id
				WHERE POT.prod_id = PV.prod_id
				AND POT.sku = PV.sku
				ORDER BY O.opt_name
				) AS options, (

		 SELECT SUM(  `qty` ) 
				FROM tblstock_history
				WHERE prod_id = PV.prod_id
				AND sku = PV.sku
				) AS run_inventory, (

		 SELECT SUM( qty ) 
				FROM tblinvoice_line
				WHERE prod_id = PV.prod_id
				AND sku = PV.sku
				AND is_void =0
				) AS unit_sold, (

			SELECT SUM( qty_transferred ) 
			FROM tbltransferred_items
			WHERE prod_id = PV.prod_id
			AND sku = PV.sku
			) AS qty_transferred, PV.quantity AS stock, PV.scanned_qty, ABS( PV.quantity - PV.scanned_qty ) AS missing
			FROM  `tblproduct` P
			JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
			JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
			JOIN tblcategory C ON C.cat_id = S.cat_id
			WHERE PV.is_scanned =1
			AND (

			SELECT SUM(  `qty` ) 
			FROM tblstock_history
			WHERE prod_id = PV.prod_id
			AND sku = PV.sku
			) - ( 
			SELECT SUM( qty ) 
			FROM tblinvoice_line
			WHERE prod_id = PV.prod_id
			AND sku = PV.sku
			AND is_void =0 ) != PV.quantity
			GROUP BY PV.sku
			LIMIT 0 , 1000";
			$q = $this->db->query($sql);	
			return $q->result();
	}
	function today_store_sales()
	{
		$sql = "SELECT IL.sku, P.name, SUM(IL.amt_paid) AS amt_paid, SUM(IL.discount) AS discount, 
				IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, 
				(SELECT SUM(ILS.qty) FROM tblinvoice_line ILS 
					LEFT JOIN tblinvoice IT ON IT.invoice_no = ILS.invoice_no 
					WHERE ILS.sku = IL.sku AND IT.date = ?  AND IT.is_sold_from_store = 1 GROUP BY ILS.sku) AS qty,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT 
					LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
					WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options 	
				FROM `tblinvoice_line` IL LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no 
				LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id 
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ? AND I.is_sold_from_store = 1 GROUP BY IL.sku";
		$q = $this->db->query($sql, array(date('Y-m-d'), date('Y-m-d')));	
		return $q->result();
	}
	function today_online_sales()
	{
		$sql = "SELECT IL.sku, P.name, SUM(IL.amt_paid) AS amt_paid, SUM(IL.discount) AS discount, 
				IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, 
				(SELECT SUM(ILS.qty) FROM tblinvoice_line ILS 
					LEFT JOIN tblinvoice IT ON IT.invoice_no = ILS.invoice_no 
					WHERE ILS.sku = IL.sku AND IT.date = ?  AND IT.is_sold_from_store = 0 GROUP BY ILS.sku) AS qty,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT 
					LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
					WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options 	
				FROM `tblinvoice_line` IL LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no 
				LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id 
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ? AND I.is_sold_from_store = 0 GROUP BY IL.sku";
		$q = $this->db->query($sql, array(date('Y-m-d'), date('Y-m-d')));	
		return $q->result();
	}
	function pending_deliveries()
	{
		$sql = "SELECT O.order_no, O.order_date, CONCAT(O.firstname,' ', O.lastname) AS name,  O.email, 
				IF(O.contact_no IS NULL, 'None', O.contact_no) AS contact_no, 
               	IF(O.shipping_address IS NULL, 'None', O.shipping_address) AS address, 
                CONCAT(O.shipping_address, ' ,', O.shipping_city, ', ', O.shipping_zipcode) AS address2,
				(SELECT SUM(quantity) FROM tblorder_details WHERE order_no = O.order_no) AS qty FROM `tblorder` O
				LEFT JOIN tblorder_details OD ON O.order_no = OD.order_no
				WHERE order_status = 'PENDING' AND order_type = 'COD' GROUP BY O.order_no;";
		$q = $this->db->query($sql);	
		return $q->result();
		
	}
	function this_month_expenses()
	{
		$sql = "SELECT exp_date, exp_desc, exp_amt FROM `tblexpenses` WHERE MONTH(exp_date) =  ? AND YEAR(I.date) = YEAR(CURRENT_TIMESTAMP);";
		$q = $this->db->query($sql, array(date('n')));	
		return $q->result();
	}
	function this_month_sales()
	{
		$sql = "SELECT I.date, IL.sku, P.name, SUM(IL.amt_paid) AS amt_paid, 
				SUM(IL.discount) AS discount, IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice,
				IL.qty,
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
				 LEFT JOIN tbloption O ON O.opt_id = POT.option_id
				 WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku
				 ORDER BY O.opt_name) as options 
				 FROM `tblinvoice_line` IL
				 LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no 
				 LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
				 WHERE MONTH(I.date) = ? AND YEAR(I.date) = YEAR(CURRENT_TIMESTAMP) GROUP BY I.date, IL.sku;";
		$q = $this->db->query($sql, array(date('n')));	
		return $q->result();
	}
	function get_tally_by_sku($sku)
	{
		$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = P.prod_id AND sku = PV.sku) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = P.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					ABS(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					LEFT JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					LEFT JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND PV.sku = ?";	
		$q = $this->db->query($sql, $sku);			
		return $q->result();
	}
	function tally($cat = null, $srch=null)
	{
		if($cat)
		{
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = P.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = P.prod_id AND sku = PV.sku) AS qty_transferred, 
					(SELECT SUM(quantity) FROM tblproduct_variant WHERE prod_id = P.prod_id AND sku = PV.sku) AS stock, 		 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					LEFT JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					LEFT JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND C.cat_id = ? AND P.name LIKE ? GROUP BY PV.sku";	
			$q = $this->db->query($sql, array( $cat,'%'. $srch . '%'));			
		}
		else
		{
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku";	
			$q = $this->db->query($sql, array('%'.$srch . '%'));				
		}
		return $q->result();
	}
	function detailed_purchase($dateFrom, $dateTo, $cat=null, $srch = null)
	{
		if($cat)
		{
			$sql = "SELECT SH.date_added, PV.sku, P.name, 
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
						(SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) AS qty, PV.purchase_price, PV.selling_price, 
						((SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) * PV.purchase_price) AS total_purchase, 
						((SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) * PV.selling_price) AS total_selling  
						FROM `tblstock_history` SH
						LEFT JOIN tblproduct P ON P.prod_id = SH.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = SH.prod_id AND PV.sku = SH.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
						LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE SH.date_added >= ? AND SH.date_added <= ? AND SH.qty > 0 AND C.cat_name = ? 
							AND CONCAT(SH.date_added, PV.sku, P.name) LIKE ? GROUP BY PV.sku ORDER BY SH.date_added DESC";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo, $cat, '%'. $srch . '%'));							
		}
		else
		{
			$sql = "SELECT SH.date_added, PV.sku, P.name, 
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
						(SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) AS qty, PV.purchase_price, PV.selling_price, 
						((SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) * PV.purchase_price) AS total_purchase, 
						((SELECT SUM(qty) FROM tblstock_history WHERE sku = PV.sku AND date_added >= ? AND date_added <= ?) * PV.selling_price) AS total_selling  
						FROM `tblstock_history` SH
						LEFT JOIN tblproduct P ON P.prod_id = SH.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = SH.prod_id AND PV.sku = SH.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
						LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE SH.date_added >= ? AND SH.date_added <= ? AND SH.qty > 0 
							AND CONCAT(SH.date_added, PV.sku, P.name) LIKE ?  GROUP BY PV.sku ORDER BY SH.date_added DESC";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo, '%'. $srch . '%'));							
		}
		return $q->result();
	}	
	function detailed_profit($dateFrom, $dateTo, $cat=null, $srch = null)
	{
		if($cat)
		{
			$sql = "SELECT IL.`sku`, P.`name`, IL.`purchase_price`, IL.`selling_price`, IL.`qty`, IL.discount,
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
						(IL.`qty`  * IL.`purchase_price`) AS total_purchase, (IL.`qty`  * IL.`selling_price`) AS total_selling, 
						((IL.`qty` * IL.`selling_price`) - (IL.`qty`  * IL.`purchase_price`) - IL.discount) AS profit
						FROM `tblinvoice_line` IL
						LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
						LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
                        WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND IL.is_void = 0 AND C.cat_name = ? AND CONCAT(IL.`sku`, P.`name`) LIKE ? ORDER BY I.date DESC;";				
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $cat, '%'.$srch.'%'));			
		}else
		{
			$sql = "SELECT IL.`sku`, P.`name`, IL.`purchase_price`, IL.`selling_price`, IL.`qty`, IL.discount,
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
						(IL.`qty`  * IL.`purchase_price`) AS total_purchase, (IL.`qty`  * IL.`selling_price`) AS total_selling, 
						((IL.`qty` * IL.`selling_price`) - (IL.`qty`  * IL.`purchase_price`) - IL.discount) AS profit
						FROM `tblinvoice_line` IL
						JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
						JOIN tblproduct P ON P.prod_id = IL.prod_id
					    JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND IL.is_void = 0 AND CONCAT(IL.`sku`, P.`name`) LIKE ? ORDER BY I.date DESC;";				
			$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'.$srch.'%'));
		}
		return $q->result();
	}	
	
	function detailed_transactions($dateFrom, $dateTo, $srch = null)
	{
		$sql = "SELECT I.invoice_no, I.date, I.cash as total_amt, SUM(IL.discount) AS total_discount FROM tblinvoice I 
					JOIN tblinvoice_line IL ON IL.invoice_no = I.invoice_no 
					WHERE I.is_issued_receipt = 1 AND  DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND I.invoice_no LIKE ?
					UNION 
				SELECT RT.return_trans_invoice_no, RT.date, RT.return_amt, NULL FROM tblreturn_transaction RT
					WHERE date  >= ? AND date <= ? AND RT.return_trans_invoice_no LIKE ? ORDER BY date DESC;";
		$q = $this->db->query($sql, array($dateFrom, $dateTo,'%'.$srch.'%', $dateFrom, $dateTo,'%'.$srch.'%'));
		return $q->result();
	}
	function detailed_sales($dateFrom, $dateTo, $cat=null, $subcat=null, $srch=null)
	{	
		
		if($cat && $subcat)
		{		
			$sql = "SELECT IL.sku, P.name, I.date, PV.quantity stock, IL.qty, IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, IL.amt_paid, IL.discount,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON IL.invoice_no = I.invoice_no
						LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					  	LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND C.cat_id = ? AND IL.is_void = 0 
							  AND SC.subcat_name = ? AND CONCAT(IL.sku, P.name) LIKE ?
							ORDER BY I.date DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $cat, $subcat, '%'.$srch.'%'));
		}
		else if($cat && !$subcat)
		{
			
			$sql = "SELECT IL.sku, P.name, I.date, PV.quantity stock, IL.qty, IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, IL.amt_paid, IL.discount,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON IL.invoice_no = I.invoice_no
						LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					  	LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?  AND C.cat_id = ?  AND IL.is_void = 0
							  AND CONCAT(IL.sku, P.name) LIKE ?
							ORDER BY I.date DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $cat, '%'.$srch.'%'));
		}
		else if(!$cat && $subcat)
		{
			
			$sql = "SELECT IL.sku, P.name, I.date, PV.quantity stock, IL.qty, IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, IL.amt_paid, IL.discount,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON IL.invoice_no = I.invoice_no
						LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					  	LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND SC.subcat_name = ?  AND IL.is_void = 0
							  AND CONCAT(IL.sku, P.name) LIKE ?
							ORDER BY I.date DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $subcat, '%'.$srch.'%'));
		}
		else
		{
		
			$sql = "SELECT IL.sku, P.name, I.date, PV.quantity stock, IL.qty, IF(I.is_issued_receipt = 1, I.invoice_no, 'NA') AS invoice, IL.amt_paid, IL.discount,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = IL.prod_id AND POT.sku = IL.sku ORDER BY O.opt_name) as options
						FROM tblinvoice_line IL
						LEFT JOIN tblinvoice I ON IL.invoice_no = I.invoice_no
						LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id
						LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku
						LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					  	LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id
						WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ?  AND IL.is_void = 0 
							  AND CONCAT(IL.sku, P.name) LIKE ?
						 ORDER BY I.date DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'.$srch.'%'));			
		}
		return $q->result();
	}
	function detailed_inventory($dateFrom, $dateTo, $cat=null, $subcat=null, $srch = null)
	{
		if($cat && $subcat)
		{		
			$sql = "SELECT PV.date_added as prod_date, PV.sku, P.name, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
				LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options,
				IF((SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1) IS NULL,
				0, (SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1)) AS beginning_invent,
				PV.quantity,  PV.selling_price, (PV.quantity * PV.selling_price) AS total_cost 
				FROM `tblproduct_variant` PV
				JOIN tblproduct P ON P.prod_id = PV.prod_id
                JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
				JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE PV.date_added >= ? AND PV.date_added <= ? AND C.cat_id = ? AND SC.subcat_name = ?
					  AND CONCAT(PV.sku, P.name) LIKE ?
				ORDER BY PV.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateFrom, $dateFrom, $dateTo, $cat, $subcat, '%'.$srch.'%'));
		}
		else if($cat && !$subcat)
		{
			
			$sql = "SELECT PV.date_added as prod_date, PV.sku, P.name, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
				LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options,
				IF((SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1) IS NULL,
				0, (SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1)) AS beginning_invent,
				PV.quantity,  PV.selling_price, (PV.quantity * PV.selling_price) AS total_cost 
				FROM `tblproduct_variant` PV
				JOIN tblproduct P ON P.prod_id = PV.prod_id
                JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
				JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE PV.date_added >= ? AND PV.date_added <= ? AND C.cat_id = ?
					 AND CONCAT(PV.sku, P.name) LIKE ?
				ORDER BY PV.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateFrom, $dateFrom, $dateTo, $cat, '%'.$srch.'%'));
		}
		else if(!$cat && $subcat)
		{
			
			$sql = "SELECT PV.date_added as prod_date, PV.sku, P.name, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
				LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options,
				IF((SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1) IS NULL,
				0, (SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1)) AS beginning_invent,
				PV.quantity,  PV.selling_price, (PV.quantity * PV.selling_price) AS total_cost 
				FROM `tblproduct_variant` PV
				JOIN tblproduct P ON P.prod_id = PV.prod_id
                JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
				JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE PV.date_added >= ? AND PV.date_added <= ? AND  SC.subcat_name = ?
					AND CONCAT(PV.sku, P.name) LIKE ?
				ORDER BY PV.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateFrom, $dateFrom, $dateTo,  $subcat, '%'.$srch.'%'));
		}
		else
		{
		
			$sql = "SELECT PV.date_added as prod_date, PV.sku, P.name, 
				(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
				LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options,
				IF((SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1) IS NULL,
				0, (SELECT qty FROM tblstock_history WHERE date_added < ?  AND prod_id = P.prod_id LIMIT 1)) AS beginning_invent,
				PV.quantity,  PV.selling_price, (PV.quantity * PV.selling_price) AS total_cost 
				FROM `tblproduct_variant` PV
				JOIN tblproduct P ON P.prod_id = PV.prod_id
                JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
				JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE PV.date_added >= ? AND PV.date_added <= ?
					AND CONCAT(PV.sku, P.name) LIKE ?
				ORDER BY PV.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateFrom, $dateFrom, $dateTo, '%'.$srch.'%'));	
		}
		return $q->result();
	}
	function detailed_non_saleable($dateFrom, $dateTo, $cat=null, $subcat=null, $srch = null)
	{
		if($cat && $subcat)
		{		
			$sql = "SELECT NS.date_added, NS.sku, P.name,  NS.qty, NS.description, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
											LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
											WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
					FROM `tblnon_saleable_items` NS
					JOIN tblproduct P ON P.prod_id = NS.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE NS.date_added >= ? AND NS.date_added <= ? AND C.cat_id = ? AND SC.subcat_name = ?
					  AND CONCAT(NS.sku, P.name) LIKE ?
				ORDER BY NS.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $cat, $subcat, '%'. $srch .'%'));
		}
		else if($cat && !$subcat)
		{
			
			$sql = "SELECT NS.date_added, NS.sku, P.name,  NS.qty, NS.description, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
											LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
											WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
					FROM `tblnon_saleable_items` NS
					JOIN tblproduct P ON P.prod_id = NS.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE NS.date_added >= ? AND NS.date_added <= ? AND C.cat_id = ?
					   AND CONCAT(NS.sku, P.name) LIKE ?
				ORDER BY NS.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $cat, '%'. $srch .'%'));
		}
		else if(!$cat && $subcat)
		{
			
			$sql = "SELECT NS.date_added, NS.sku, P.name,  NS.qty, NS.description, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
											LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
											WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
					FROM `tblnon_saleable_items` NS
					JOIN tblproduct P ON P.prod_id = NS.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE NS.date_added >= ? AND NS.date_added <= ? AND  SC.subcat_name = ?
					   AND CONCAT(NS.sku, P.name) LIKE ?
				ORDER BY NS.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo,  $subcat, '%'. $srch .'%'));
		}
		else
		{
		
			$sql = "SELECT NS.date_added, NS.sku, P.name,  NS.qty, NS.description, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
											LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
											WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
					FROM `tblnon_saleable_items` NS
					JOIN tblproduct P ON P.prod_id = NS.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
				WHERE NS.date_added >= ? AND NS.date_added <= ?
					 AND CONCAT(NS.sku, P.name) LIKE ?
				ORDER BY NS.date_added DESC;";	
			$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'. $srch .'%'));	
		}
		return $q->result();
	}
	
	
	function detailed_expenses($dateFrom, $dateTo, $srch = null)
	{
		$sql = "SELECT * FROM `tblexpenses` WHERE  exp_date >= ? AND exp_date <= ? AND exp_desc LIKE ?
				ORDER BY exp_date DESC, exp_id DESC";
		$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'.$srch.'%'));						
		return $q->result();		
	}

	function detailed_transferred($dateFrom, $dateTo, $location = null, $srch = null)
	{
		if($location)
		{
			$sql = "SELECT TI.date_transferred, PV.sku, P.name, TI.qty_transferred, L.location, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT 
					 LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
					WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options 		 
					FROM `tbltransferred_items` TI
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = TI.prod_id AND PV.sku = TI.sku
					LEFT JOIN tblproduct P ON P.prod_id = TI.prod_id 
					LEFT JOIN tbllocation L ON L.loc_id = TI.loc_id 
					WHERE  TI.date_transferred >= ? AND TI.date_transferred <= ? AND L.loc_id = ?
						AND CONCAT(PV.sku, P.name, L.location) LIKE ?
					ORDER BY TI.date_transferred DESC;";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $location, '%'.$srch.'%'));											
		}
		else
		{
			$sql = "SELECT TI.date_transferred, PV.sku, P.name, TI.qty_transferred, L.location,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT 
					LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
					WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options 		 
					FROM `tbltransferred_items` TI
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = TI.prod_id AND PV.sku = TI.sku
					LEFT JOIN tblproduct P ON P.prod_id = TI.prod_id 
					LEFT JOIN tbllocation L ON L.loc_id = TI.loc_id 
					WHERE TI.date_transferred >= ? AND TI.date_transferred <= ?
						AND CONCAT(PV.sku, P.name, L.location) LIKE ?
					ORDER BY TI.date_transferred DESC;";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'.$srch.'%'));										
		}
		return $q->result();	
	}
	function summary_purchase($dateFrom, $dateTo)
	{
			$sql = "SELECT date_added, SUM(total_purchase) as total_purchase FROM (SELECT SH.date_added, (SUM(SH.qty) * PV.purchase_price) AS total_purchase 
				FROM `tblstock_history` SH 
				JOIN tblproduct_variant PV ON SH.sku = PV.sku 
				WHERE SH.date_added >= ? AND SH.date_added <= ? 
				GROUP BY SH.date_added, PV.sku) t1 GROUP BY date_added";
			$q = $this->db->query($sql, array($dateFrom, $dateTo));	
		return $q->result();	
	}

	function summary_sold($dateFrom, $dateTo)
	{
		$sql = "SELECT date, SUM(amt_paid) as total_sold FROM (
			SELECT I.date, IL.amt_paid FROM tblinvoice_line IL 
			LEFT JOIN tblinvoice I ON IL.invoice_no = I.invoice_no 
			WHERE DATE_FORMAT(I.date, '%Y-%m-%d') >= ? AND DATE_FORMAT(I.date, '%Y-%m-%d') <= ? AND IL.is_void = 0 
			ORDER BY I.date DESC) t1 GROUP BY date";
		$q = $this->db->query($sql, array($dateFrom, $dateTo));
		return $q->result();	
	}	

	
	function summary_inventory($dateFrom, $dateTo, $srch = null)
	{	
		$sql = "SELECT  C.cat_name, SC.subcat_name, PV.`sku`, P.`name`,  PV.`purchase_price`, PV.`quantity`, 
					IF((PV.`quantity` * PV.`purchase_price`) = 0, 0, (PV.`quantity` * PV.`purchase_price`)) AS total_cost,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
							LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options
					FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = SC.cat_id
					WHERE PV.date_added >= ? AND PV.date_added <= ?
						AND CONCAT(C.cat_name, SC.subcat_name, PV.sku, P.name) LIKE ?
						 ORDER BY PV.date_added DESC;";
		$q = $this->db->query($sql, array($dateFrom, $dateTo,'%'.$srch.'%'));			
		return $q->result();
	}

	function summary_non_saleable($dateFrom, $dateTo, $srch = null)
	{	
		$sql = "SELECT C.cat_name, SC.subcat_name, NS.sku, P.name, PV.selling_price,  SUM(NS.qty) qty, PV.selling_price * SUM(NS.qty) total_loss,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
											LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
											WHERE PO.sku = NS.sku ORDER BY O.opt_name) as options
					FROM `tblnon_saleable_items` NS
					LEFT JOIN tblproduct P ON P.prod_id = NS.prod_id
                    LEFT JOIN tblproduct_variant PV ON PV.sku = NS.sku
					LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id
					LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id 
					WHERE NS.date_added >= ? AND NS.date_added <= ?
						AND CONCAT(C.cat_name, SC.subcat_name, NS.sku, P.name) LIKE ?
						GROUP BY NS.sku ORDER BY NS.date_added DESC;";
		$q = $this->db->query($sql, array($dateFrom, $dateTo,'%'.$srch.'%'));			
		return $q->result();
	}
	function summary_transferred($dateFrom, $dateTo, $location = null, $srch = null)
	{
		if($location)
		{
			
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, PV.purchase_price AS unit_cost, 
						SUM(T.qty_transferred) AS unit_transferred, (PV.purchase_price * SUM(T.qty_transferred)) AS total_cost,
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)
						 FROM tblproduct_option POT
						 LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
						 WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options 
						 
						 FROM tbltransferred_items T JOIN tblproduct P ON P.prod_id = T.prod_id
						 JOIN tblproduct_variant PV ON PV.prod_id = T.prod_id AND PV.sku = T.sku 
						 JOIN tblsubcategory S ON S.subcat_id = P.subcat_id 
						 JOIN tblcategory C ON C.cat_id = S.cat_id JOIN tbllocation L ON L.loc_id = T.loc_id
						WHERE T.date_transferred >= ? AND T.date_transferred <= ?   AND L.loc_id = ? 
							AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ?
						GROUP BY PV.sku ORDER BY T.date_transferred DESC;";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, $location, '%'.$srch.'%'));	
		}
		else
		{
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, PV.purchase_price AS unit_cost, 
						SUM(T.qty_transferred) AS unit_transferred, (PV.purchase_price * SUM(T.qty_transferred)) AS total_cost,
						(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC)
						 FROM tblproduct_option POT
						 LEFT JOIN tbloption O ON O.opt_id = POT.option_id 
						 WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options 
						 
						 FROM tbltransferred_items T JOIN tblproduct P ON P.prod_id = T.prod_id
						 JOIN tblproduct_variant PV ON PV.prod_id = T.prod_id AND PV.sku = T.sku 
						 JOIN tblsubcategory S ON S.subcat_id = P.subcat_id 
						 JOIN tblcategory C ON C.cat_id = S.cat_id JOIN tbllocation L ON L.loc_id = T.loc_id
						WHERE T.date_transferred >= ? AND T.date_transferred <= ?  
							AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ?
						GROUP BY PV.sku ORDER BY T.date_transferred DESC;";
			$q = $this->db->query($sql, array($dateFrom, $dateTo, '%'.$srch.'%'));	
		}
		return $q->result();	
	}		
	//Tally datatable
	
	function dt_product_qry()
	{
		
		if(isset($_POST["search"]) && isset($_POST["length"]) && $_POST["length"] != -1)  
        {
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = P.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = P.prod_id AND sku = PV.sku) AS qty_transferred, 
					(SELECT SUM(quantity) FROM tblproduct_variant WHERE prod_id = P.prod_id AND sku = PV.sku) AS stock, 		 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					LEFT JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					LEFT JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku ORDER BY PV.date_scanned DESC LIMIT ?, ?";	
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku";	
			$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		}
		
		return $q;
	}
	function dt_product_qry_with_cat($cat)
	{
		
		if($_POST["length"] != -1)  
        {
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = P.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = P.prod_id AND sku = PV.sku) AS qty_transferred, 
					(SELECT SUM(quantity) FROM tblproduct_variant WHERE prod_id = P.prod_id AND sku = PV.sku) AS stock, 		 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					LEFT JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					LEFT JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					LEFT JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1  AND C.cat_id = ? AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku ORDER BY PV.date_scanned DESC LIMIT ?, ?";	
			$q = $this->db->query($sql, array((int)$cat, '%'.$_POST["search"]["value"].'%' ,(int)$_POST['start'], (int)$_POST['length']));
		
        }  
		else
		{
			$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1  AND C.cat_id = ?  AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku";	
			$q = $this->db->query($sql, array((int)$cat, '%'.$_POST["search"]["value"].'%'));
		}
		return $q;
	}
   function make_datatables($cat = null)
   {  
		if($cat != null)
			$q = $this->dt_product_qry_with_cat($cat);
        else
			$q = $this->dt_product_qry();
		return $q->result();  
    }  
    function get_filtered_data()
	{  
		$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 AND CONCAT(C.cat_name, S.subcat_name, PV.sku, P.name) LIKE ? GROUP BY PV.sku";	
		$q = $this->db->query($sql, array('%'.$_POST["search"]["value"].'%'));
		return $q->num_rows();
    }     
    function get_all_data()  
    {  
		$sql = "SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option POT
						LEFT JOIN tbloption O ON O.opt_id = POT.option_id WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name) as options, 
					(SELECT SUM(`qty`) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku) AS run_inventory,
					(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0) AS unit_sold,
					(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku) AS qty_transferred, 
					 PV.quantity AS stock, 
					 PV.scanned_qty, 
					(PV.quantity - PV.scanned_qty) AS missing FROM `tblproduct` P 
					JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id
					JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
					JOIN tblcategory C ON C.cat_id = S.cat_id
					WHERE PV.is_scanned = 1 GROUP BY PV.sku";	
		$q = $this->db->query($sql);
		return $q->num_rows();
    }  
	
	
}
/*
SELECT C.cat_name, S.subcat_name, PV.sku, P.name, 
( SELECT GROUP_CONCAT( DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC ) FROM tblproduct_option POT 
		LEFT JOIN tbloption O ON O.opt_id = POT.option_id
		WHERE POT.prod_id = PV.prod_id AND POT.sku = PV.sku ORDER BY O.opt_name ) AS options, 
( SELECT SUM( `qty` ) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku ) AS run_inventory, 
( SELECT SUM( qty ) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku AND is_void = 0 ) AS unit_sold, 
( SELECT SUM( qty_transferred ) FROM tbltransferred_items WHERE prod_id = PV.prod_id AND sku = PV.sku ) AS qty_transferred, 
PV.quantity AS stock, PV.scanned_qty,
 ABS( PV.quantity - PV.scanned_qty ) AS missing FROM `tblproduct` P 
 JOIN tblproduct_variant PV ON PV.prod_id = P.prod_id 
 JOIN tblsubcategory S ON S.subcat_id = P.subcat_id 
 JOIN tblcategory C ON C.cat_id = S.cat_id 
 WHERE 
 ABS(
		( SELECT SUM( `qty` ) FROM tblstock_history WHERE prod_id = PV.prod_id AND sku = PV.sku ) 
 - ( 
		IF(
			(SELECT SUM( qty ) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku) IS NULL, 0, (SELECT SUM( qty ) FROM tblinvoice_line WHERE prod_id = PV.prod_id AND sku = PV.sku)) 
	 
	)
)
!= PV.quantity GROUP BY PV.sku LIMIT 0 , 1000;


PV.is_scanned = 1
 AND


SELECT C.cat_name, S.subcat_name, P.sku, P.name,
(SELECT `qty` FROM tblstock_history WHERE prod_id = P.prod_id) run_inventory,
(SELECT SUM(qty) FROM tblinvoice_line WHERE prod_id = P.prod_id) AS unit_sold,
(SELECT SUM(qty_transferred) FROM tbltransferred_items WHERE prod_id = P.prod_id) AS qty_transferred, 
P.quantity AS stock, 
P.scanned_qty, 0 AS missing 
FROM `tblproduct` P 
JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
JOIN tblcategory C ON C.cat_id = S.cat_id
WHERE P.is_scanned = 0 AND CONCAT(P.sku, P.name) LIKE '%%' GROUP BY P.sku


******************************************
* NOTES:
* Incorrect query yields inaccurate results 
* even though join is much faster compared 
* to subquery solution
*******************************************
SELECT C.cat_name, S.subcat_name, P.sku, P.name, 0 run_inventory,
SUM(IL.qty) AS unit_sold, 
SUM(TI.qty_transferred) AS transferred, 
P.quantity AS stock, 
P.scanned_qty, 0 AS missing 
FROM `tblproduct` P 
LEFT JOIN tblsubcategory S ON S.subcat_id = P.subcat_id
LEFT JOIN tblcategory C ON C.cat_id = S.cat_id
LEFT JOIN tblinvoice_line IL ON IL.prod_id = P.prod_id
LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
LEFT JOIN tbltransferred_items TI ON TI.prod_id = P.prod_id
WHERE P.is_scanned = 0 AND CONCAT(P.sku, P.name) LIKE '%%' GROUP BY P.sku

******************************************
* NOTES:
* Original query in the system
*******************************************
SELECT a.QRCode as QRCode, a.isReset as reset, a.ProductName as name, c.SubcategoryName as sub, d.CategoryName as cat,
 (SELECT count(tblprodinvent.id) FROM tblprodinvent WHERE tblprodinvent.prodid = a.ProductCode AND tblprodinvent.status != 'MISSING') as running,
 (SELECT count(tblprodinvent.id) FROM tblprodinvent WHERE tblprodinvent.prodid = a.ProductCode AND tblprodinvent.status = 'SOLD') as sold,
 (SELECT count(tblprodinvent.id) FROM tblprodinvent WHERE tblprodinvent.prodid = a.ProductCode AND tblprodinvent.status = 'TRANSFERRED') as transferred,
 (SELECT count(tblprodinvent.id) FROM tblprodinvent WHERE tblprodinvent.prodid = a.ProductCode AND tblprodinvent.counter = '1' AND tblprodinvent.status = 'AVAILABLE')
 as phy FROM tblproductmain a INNER JOIN tblprodinvent b ON a.ProductCode = b.prodid INNER JOIN tblsubcategory c ON a.Subcat_ID = c.SubcategoryID 
INNER JOIN tblcategory d ON a.Cat_ID = d.CategoryID GROUP BY a.QRCode






*/