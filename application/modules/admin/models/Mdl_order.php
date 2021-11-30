<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Order extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblorder";
    }	
	function get_amt_paid($order_no)
	{
		$sql = "SELECT SUM(IF(discount_percent = 0, (`selling_price`*`quantity`),
				(quantity*(selling_price - (discount_percent/100 * selling_price))))) AS m
				FROM tblorder_details WHERE order_no = ?";
		$q = $this->db->query($sql, array($order_no));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	function get_orders($id=null){
		
		if($id)
		{
			$sql = "SELECT O.order_no, P.name,  O.order_date, O.delivered_date,  O.order_status, O.order_type, 
						(SELECT SUM(quantity) FROM tblorder_details WHERE order_no = O.order_no GROUP BY order_no) order_qty, 
						SUM(OD.selling_price) total_price FROM `tblorder` O
						JOIN tblorder_details OD ON OD.order_no = O.order_no
						JOIN tblproduct P ON P.prod_id = OD.prod_id WHERE OD.order_no = ?";
			$q = $this->db->query($sql, array($id));
		}
		else
		{
			$q = $this->db->query("SELECT O.order_no, P.name,  O.order_date,  O.delivered_date, O.order_status, O.order_type, 
								(SELECT SUM(quantity) FROM tblorder_details WHERE order_no = O.order_no GROUP BY order_no) order_qty,
								SUM(OD.selling_price) total_price FROM `tblorder` O
								LEFT JOIN tblorder_details OD ON OD.order_no = O.order_no
								LEFT JOIN tblproduct P ON P.prod_id = OD.prod_id
                                GROUP BY O.order_no");		
		}							
       return $q->result();
    }
	function get_order_items($id)
	{
		$sql = "SELECT O.order_no, OD.sku,  OD.prod_name, OD.selling_price, OD.purchase_price, OD.discount_percent, OD.item_order_status,
				(SELECT SUM(quantity) FROM tblorder_details WHERE order_no =  OD.order_no AND sku = OD.sku GROUP BY sku) as quantity	
					FROM tblorder_details OD	
					LEFT JOIN tblorder O ON O.order_no = OD.order_no 
					LEFT JOIN tblimage I ON I.prod_id = OD.prod_id AND I.sku = OD.sku
					WHERE OD.order_no = ? GROUP BY OD.sku";
		$q = $this->db->query($sql, array((int)$id));
		return $q->result();
	}
	function get_order_details($id)
	{
		$sql = "SELECT O.order_no, OD.prod_name, OD.selling_price, OD.item_order_status,
				(SELECT SUM(quantity) FROM tblorder_details WHERE order_no = OD.order_no AND sku = OD.sku GROUP BY sku) as quantity,	
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
						WHERE PO.sku = OD.sku ORDER BY O.opt_name) as options, 
						(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
											NATURAL JOIN tblproduct_variant PVS 
											WHERE PVS.prod_id = OD.prod_id AND PVS.sku = OD.sku AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image
					FROM tblorder_details OD	
					LEFT JOIN tblorder O ON O.order_no = OD.order_no 
					LEFT JOIN tblimage I ON I.prod_id = OD.prod_id AND I.sku = OD.sku
					WHERE OD.order_no = ? GROUP BY OD.sku";
		$q = $this->db->query($sql, array((int)$id));
		return $q->result();
	}
	function get_customer_details($id)
	{
		$sql = "SELECT `order_no`, `order_date`, `lastname`, `firstname`, `email`, `contact_no`, 
				CONCAT(`shipping_address`, ', ',  `shipping_city`, ', ', `shipping_zipcode` ) AS shipping_address
				FROM `tblorder` WHERE order_no = ?";
		$q = $this->db->query($sql, array((int)$id));
		return $q->result();
	}
	function get_canceled_orders()
	{
		$sql = "SELECT OD.order_no, P.name,  OD.selling_price, OD.quantity, OD.`date_canceled`, O.order_date, OD.reason_of_cancellation,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO 
						LEFT JOIN tbloption O ON O.opt_id = PO.option_id 
						WHERE PO.sku = OD.sku ORDER BY O.opt_name) as options, 
						(SELECT IF(COUNT(I.img_file_path) = 0 ,'None', I.img_file_path)  FROM tblimage I
											NATURAL JOIN tblproduct_variant PVS 
											WHERE PVS.prod_id = OD.prod_id AND PVS.sku = OD.sku AND I.img_type = 'primary' AND I.img_size = '349x230') as primary_image
                 FROM `tblorder_details` OD
				JOIN tblorder O ON O.order_no = OD.order_no
				JOIN tblproduct P ON P.prod_id = OD.prod_id
				WHERE OD.`item_order_status` = 'canceled'";
		$q = $this->db->query($sql);
		return $q->result();
	}
}

