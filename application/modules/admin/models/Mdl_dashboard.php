<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Dashboard extends My_Model{

    function __construct() {
        parent::__construct();
    }	
	function drop_tables()
	{	
		$sql = "SELECT concat('DROP TABLE IF EXISTS `', table_name, '`;') AS qry
		FROM information_schema.tables
		WHERE table_schema = 'db_goodbuy';";
		$q = $this->db->query($sql);
		$this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
		foreach($q->result() as $tbl)
		{
			$this->db->query($tbl->qry);
		}
		$this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
	}
	function get_monthly_sale_chart()
	{
		$q = $this->db->query("SELECT CONCAT(SUM(IL.amt_paid), '-', MONTH(I.date)) AS data FROM `tblinvoice_line` IL
								JOIN tblinvoice I ON I.invoice_no = IL.invoice_no  
								WHERE YEAR(I.date) = YEAR(CURRENT_TIMESTAMP) AND IL.is_void = 0 GROUP BY MONTH(I.date)");
       return $q->result();		
	}
	function get_monthly_sale_by_cat($cat)
	{
		$sql = "SELECT CONCAT(SUM(IL.amt_paid), '-', MONTH(I.date)) as data FROM tblinvoice_line IL 
				LEFT JOIN tblinvoice I ON I.invoice_no = IL.invoice_no 
				LEFT JOIN tblproduct_variant PV ON PV.prod_id = IL.prod_id AND PV.sku = IL.sku 
				LEFT JOIN tblproduct P ON P.prod_id = PV.prod_id 
				LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id 
				LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id 
				WHERE C.cat_id = ? AND YEAR(I.date) = YEAR(CURRENT_TIMESTAMP) AND IL.is_void = 0 GROUP BY MONTH(I.date)";
		$q = $this->db->query($sql, array((int)$cat));
		return $q->result();
	}
	function get_table_count()
	{
		$sql = "SELECT COUNT(*) AS m FROM information_schema.tables WHERE table_schema = 'db_goodbuy';";
		$q = $this->db->query($sql);
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }

	function get_pending_deliveries()
	{
		$sql = "SELECT COUNT(order_no) AS m FROM `tblorder` WHERE order_status = 'PENDING' AND order_type = 'COD';";
		$q = $this->db->query($sql);
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	
	function get_items_min_qty()
	{
		$sql = "SELECT COUNT(sku) AS m FROM tblproduct_variant WHERE quantity <= (SELECT min_qty FROM `tblmin_qty`
				WHERE qty_id=(SELECT MAX(qty_id) FROM `tblmin_qty`));";
		$q = $this->db->query($sql);
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }	
	function get_todays_sales()
	{
		$sql = "SELECT SUM(IL.amt_paid) AS m FROM `tblinvoice_line` IL
				JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ?;";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_todays_store_sales()
	{
		$sql = "SELECT SUM(IL.amt_paid) AS m FROM `tblinvoice_line` IL
				JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ? AND I.is_sold_from_store = 1;";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	
	function get_todays_online_sales()
	{
		$sql = "SELECT SUM(IL.amt_paid) AS m FROM `tblinvoice_line` IL
				JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
				WHERE DATE_FORMAT(I.date, '%Y-%m-%d') = ? AND I.is_sold_from_store = 0;";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	
	
	function get_todays_expenses()
	{
		$sql = "SELECT SUM(exp_amt) AS m FROM `tblexpenses` WHERE exp_date = ?;";
		$q = $this->db->query($sql, date("Y-m-d"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	function get_month_sales()
	{
		$sql = "SELECT SUM(IL.amt_paid) as m FROM tblinvoice_line IL
			JOIN tblinvoice I ON IL.invoice_no = I.invoice_no
			WHERE MONTH(I.date) = ? AND YEAR(I.date) = YEAR(CURRENT_TIMESTAMP) AND IL.is_void = 0 ORDER BY I.date DESC;";
		// $sql = "SELECT SUM(IL.selling_price * IL.qty) AS m FROM `tblinvoice_line` IL
		// 		JOIN tblinvoice I ON I.invoice_no = IL.invoice_no
		// 		WHERE MONTH(I.date) = ? AND YEAR(I.date) = YEAR(CURRENT_TIMESTAMP) AND I.is_void = 0;";
		$q = $this->db->query($sql, date("n"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	
	function get_month_expenses()
	{
		$sql = "SELECT SUM(exp_amt) AS m FROM `tblexpenses` WHERE  MONTH(exp_date) =  ? AND YEAR(exp_date) = YEAR(CURRENT_TIMESTAMP);";
		$q = $this->db->query($sql, date("n"));
        $data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
    }
	
}

