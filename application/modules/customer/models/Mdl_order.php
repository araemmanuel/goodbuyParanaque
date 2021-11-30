<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Order extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblorder";
    }	
	
	function get_order_track()
	{
		$order_id = $this->input->post('track_order_order_id');
		$email = $this->input->post('track_order_email');
		$q_string="
		SELECT ORD.shipping_fee, ORD.order_date, user_id, ORDD.sku, ORDD.discount_percent, ORDD.prod_id, ORDD.quantity, ORDD.order_no, ORDD.selling_price, img_file_path,P.name,ORDD.item_order_status, ORDD.reason_of_cancellation, order_status 
		FROM tblorder AS ORD 
			INNER JOIN tblorder_details AS ORDD ON ORDD.order_no = ORD.order_no 
			INNER JOIN tblproduct_variant AS PV ON PV.sku = ORDD.sku 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE ORD.order_no = ? 
			AND ORD.email = ?
			AND 
				(img_size = '349x230' OR img_size IS NULL)
			AND 
				(img_type = 'primary' OR img_type IS NULL)";
		$q = $this->db->query($q_string,array($order_id,$email));
		if($q->num_rows() > 0)
		{
			return $q->result();
		}
		else
		{
			return 'no result';
		}
	}
	
	function get_orders($user_id)
	{
		$q_string = "
		SELECT ORD.shipping_fee, ORD.order_no, order_date, order_status ,user_id, ORDD.selling_price, ORDD.quantity, ORDD.item_order_status, ORDD.discount_percent 
		FROM tblorder AS ORD 
			INNER JOIN tblorder_details AS ORDD ON ORDD.order_no = ORD.order_no 
			INNER JOIN tbluser AS US ON US.id = ORD.user_id  
		WHERE item_order_status <> 'Cancelled' 
			AND username = ? 
		ORDER BY order_no";
		$q = $this->db->query($q_string,array($user_id))->result();
		return $q;
	}
	
	function get_orders_with_point($user_id)
	{
		$q_string = "
		SELECT ORD.shipping_fee, ORD.order_no, order_date, order_status ,user_id, ORDD.selling_price, ORDD.quantity, ORDD.item_order_status, ORDD.discount_percent,  CAT.used_reward_pts AS points_used, CAT.gained_reward_pts AS points_equivalent 
		FROM tblorder AS ORD 
			INNER JOIN tblorder_details AS ORDD ON ORDD.order_no = ORD.order_no 
			INNER JOIN tbluser AS US ON US.id = ORD.user_id
			INNER JOIN tblcard_transaction AS CAT ON ORD.invoice_no = CAT.invoice_no
		WHERE username = ?
		ORDER BY order_no ASC";
		$q = $this->db->query($q_string,array($user_id))->result();
		return $q;
	}
	function get_order_detail_v($order_id)
	{
		$q_string = "
		SELECT DISTINCT CONCAT(ORD.firstname, ' ', SUBSTRING(middlename,1,1), '. ', ORD.lastname) AS customer_name ,ORDD.sku, ORDD.discount_percent, ORDD.prod_id, ORDD.quantity, ORDD.order_no, ORDD.selling_price, img_file_path,P.name,ORDD.item_order_status, ORDD.reason_of_cancellation, order_status FROM tblorder_details AS ORDD
			INNER JOIN tblorder AS ORD ON ORD.order_no = ORDD.order_no 
			INNER JOIN tblproduct_variant AS PV ON PV.sku = ORDD.sku 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE ORDD.order_no = ? 
			AND 
				(img_size = '349x230' OR img_size IS NULL)
			AND 
				(img_type = 'primary' OR img_type IS NULL)";
		$q = $this->db->query($q_string,array($order_id))->result_array();
		return $q;
	}
	function get_order_detail($order_id)
	{
		$q_string = "
		SELECT DISTINCT ORDD.sku, ORDD.discount_percent, ORDD.prod_id, ORDD.quantity, ORDD.order_no, ORDD.selling_price, img_file_path,P.name,ORDD.item_order_status, ORDD.reason_of_cancellation, order_status FROM tblorder_details AS ORDD
			INNER JOIN tblorder AS ORD ON ORD.order_no = ORDD.order_no 
			INNER JOIN tblproduct_variant AS PV ON PV.sku = ORDD.sku 
			INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id 
			LEFT JOIN tblimage AS IM ON IM.sku = PV.sku 
		WHERE ORDD.order_no = ? 
			AND 
				(img_size = '349x230' OR img_size IS NULL)
			AND 
				(img_type = 'primary' OR img_type IS NULL)";
		$q = $this->db->query($q_string,array($order_id))->result();
		return $q;
	}
	
	function cancel_item_in_order($user_id)
	{
		$order_id = $this->input->post('order_id');
		$prod_id = $this->input->post('order_prod_id');
		$prod_sku = $this->input->post('order_prod_sku');
		$reason = $this->input->post('reason_1');
		$reason = $reason . $this->input->post('reason_2');
			$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ?";
			$q_0_res = $this->db->query($q_string_o,array($order_id));
			if($q_0_res->num_rows() == 1)
			{
				$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
				$this->db->query($q_string_0_1,array($order_id));
			}
			else if($q_0_res->num_rows > 1)
			{
				$order_items = $q_0_res->num_rows();
				$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ? AND item_order_status = 'Cancelled'";
				$q_o_res = $this->db->query($q_string_o,array($order_id));
				if($q_o_res->num_rows() == ($order_items - 1))
				{
					$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
					$this->db->query($q_string_0_1,array($order_id));
				}
			}
		$q_string = "UPDATE tblorder_details AS ORDD INNER JOIN tblorder AS ORD ON ORDD.order_no = ORD.order_no INNER JOIN tbluser AS US ON US.id = ORD.user_id SET item_order_status = 'Cancelled', reason_of_cancellation = ?, date_canceled = ? WHERE ORDD.order_no = ? AND ORDD.prod_id = ? AND US.username = ? AND ORDD.sku = ?";
		$this->db->query($q_string, array($reason,date('Y-m-d'),$order_id,$prod_id,$user_id,$prod_sku));
		$q_string_1 = "SELECT quantity FROM tblproduct_variant WHERE sku = ?";
		$old_q_1 = $this->db->query($q_string_1,array($prod_sku))->row()->quantity;
		$q_string_2 = "SELECT quantity FROM tblorder_details WHERE sku = ? AND order_no = ?";
		$old_q_2 = $this->db->query($q_string_2,array($prod_sku,$order_id));
		$new_qnty_of_pv = intval($old_q_1) + intval($old_q_2->row()->quantity);
		$q_string_3 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
		$this->db->query($q_string_3,array($new_qnty_of_pv,$prod_sku));
		
		//echo "new point : ".$new_receipt_price.", points_used : ".$points_used." points_equivalent : ".$points_equivalent.", old_points = ".$q_str_i_2;
		return 'accepted';
	}
	
	function cancel_item_in_order_track($user_id = null)
	{
		if($user_id == null)
		{
			$order_id = $this->input->post('order_id');
			$prod_id = $this->input->post('order_prod_id');
			$prod_sku = $this->input->post('order_prod_sku');
			$reason = $this->input->post('reason_1');
			$reason = $reason . $this->input->post('reason_2');
			$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ?";
			$q_0_res = $this->db->query($q_string_o,array($order_id));
			if($q_0_res->num_rows() == 1)
			{
				$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
				$this->db->query($q_string_0_1,array($order_id));
			}
			else if($q_0_res->num_rows > 1)
			{
				$order_items = $q_0_res->num_rows();
				$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ? AND item_order_status = 'Cancelled'";
				$q_o_res = $this->db->query($q_string_o,array($order_id));
				if($q_o_res->num_rows() == ($order_items - 1))
				{
					$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
					$this->db->query($q_string_0_1,array($order_id));
				}
			}
			$q_string = "UPDATE tblorder_details AS ORDD INNER JOIN tblorder AS ORD ON ORDD.order_no = ORD.order_no LEFT JOIN tbluser AS US ON US.id = ORD.user_id SET item_order_status = 'Cancelled', reason_of_cancellation = ?, date_canceled = ?  WHERE ORDD.order_no = ? AND ORDD.prod_id = ? AND ORDD.sku = ?";
			$this->db->query($q_string, array($reason,date('Y-m-d'),$order_id,$prod_id,$prod_sku));
			$q_string_1 = "SELECT quantity FROM tblproduct_variant WHERE sku = ?";
			$old_q_1 = $this->db->query($q_string_1,array($prod_sku))->row()->quantity;
			$q_string_2 = "SELECT quantity FROM tblorder_details WHERE sku = ? AND order_no = ?";
			$old_q_2 = $this->db->query($q_string_2,array($prod_sku,$order_id));
			$new_qnty_of_pv = intval($old_q_1) + intval($old_q_2->row()->quantity);
			$q_string_3 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
			$this->db->query($q_string_3,array($new_qnty_of_pv,$prod_sku));
			return 'accepted';
		}
		else
		{
			$order_id = $this->input->post('order_id');
			$prod_id = $this->input->post('order_prod_id');
			$prod_sku = $this->input->post('order_prod_sku');
			$reason = $this->input->post('reason_1');
			$reason = $reason . $this->input->post('reason_2');
			$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ?";
			$q_0_res = $this->db->query($q_string_o,array($order_id));
			if($q_0_res->num_rows() == 1)
			{
				$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
				$this->db->query($q_string_0_1,array($order_id));
			}
			else if($q_0_res->num_rows > 1)
			{
				$order_items = $q_0_res->num_rows();
				$q_string_o = "SELECT * FROM tblorder_details WHERE order_no = ? AND item_order_status = 'Cancelled'";
				$q_o_res = $this->db->query($q_string_o,array($order_id));
				if($q_o_res->num_rows() == ($order_items - 1))
				{
					$q_string_0_1 = "UPDATE tblorder AS ORD SET ORD.order_status = 'Cancelled' WHERE ORD.order_no = ?";
					$this->db->query($q_string_0_1,array($order_id));
				}
			}
			$q_string = "UPDATE tblorder_details AS ORDD INNER JOIN tblorder AS ORD ON ORDD.order_no = ORD.order_no LEFT JOIN tbluser AS US ON US.id = ORD.user_id SET item_order_status = 'Cancelled', reason_of_cancellation = ?, date_canceled = ? WHERE ORDD.order_no = ? AND ORDD.prod_id = ? AND US.username = ? AND ORDD.sku = ?";
			$this->db->query($q_string, array($reason,date('Y-m-d'),$order_id,$prod_id,$user_id,$prod_sku));
			$q_string_1 = "SELECT quantity FROM tblproduct_variant WHERE sku = ?";
			$old_q_1 = $this->db->query($q_string_1,array($prod_sku))->row()->quantity;
			$q_string_2 = "SELECT quantity FROM tblorder_details WHERE sku = ? AND order_no = ?";
			$old_q_2 = $this->db->query($q_string_2,array($prod_sku,$order_id));
			$new_qnty_of_pv = intval($old_q_1) + intval($old_q_2->row()->quantity);
			$q_string_3 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
			$this->db->query($q_string_3,array($new_qnty_of_pv,$prod_sku));
			return 'accepted';
		}
		
	}

    function add_new_order($user_name = NULL)
    {
        $shippingaddress = $this->input->post('checkout_address');
        $order_date = date('Y-m-d H:i:s');
		$phone = $this->input->post('checkout_phone');
        $lastname = $this->input->post('checkout_lname');
        $firstname= $this->input->post('checkout_fname');
		$middlename = $this->input->post('checkout_mname');
		if (ctype_space($middlename) || $middlename == '') {
			$middlename = NULL;
		}
        $email= $this->input->post('checkout_email');
        $shipping_address= $shippingaddress;
        $shipping_city = 'Parañaque';
        $shipping_state= 'Luzon';
        $shipping_country = 'Philippines';
        $shipping_zipcode = $this->input->post('checkout_zipcode');
		$order_type = '';
        $this->db->trans_start();
		if($user_name != NULL)
		{
			$q_string_1 = "SELECT * FROM tbluser WHERE username = ?";
			$user_id = $this->db->query($q_string_1,array($user_name))->row()->id;
		}
		else
		{
			$user_id = NULL;
		}
		$shipping_fee = $this->db->query("SELECT * FROM tblcourier WHERE is_default = 1");
		$courier_id = $shipping_fee->row()->cour_id;
		$courier_fee = $shipping_fee->row()->shipping_fee;
		
		$this->load->helper('cookie');
        $cookie_items = $this->input->cookie('cartItems', TRUE);
        $items = explode(",",$cookie_items);
		$num_of_items = 0;
		$num_of_items_available = 0;
		$items_exceed = array();
        foreach ($items as $value) {
			$num_of_items = $num_of_items + 1;
            $item_det = explode(":",$value);
			$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
            $price_and_name = $this->db->query($q_string_4,array($item_det[0]));
            foreach ($price_and_name->result_array() as $row)
            {
					if((intval($row['quantity']) - $item_det[1]) <= 0 )
					{
						array_push($items_exceed,$item_det[0].':'.$item_det[1]);
					}
					else
					{
						$num_of_items_available = $num_of_items_available + 1;
					}
            }
        }
		
		if($num_of_items == $num_of_items_available)
		{
			if($this->input->post('payment_method') == 'cod')
			{
				$q_string_2 = "
				INSERT INTO tblorder (order_date,lastname,firstname,middlename,email,shipping_address,shipping_city,shipping_state,shipping_country,shipping_zipcode,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,?,?,?,?,?,'PENDING',?,'00-00-0000','COD',?,?,?)";
				$this->db->query($q_string_2, array($order_date,$lastname,$firstname,$middlename,$email,$shipping_address,$shipping_city,$shipping_state,$shipping_country,$shipping_zipcode,$user_id,$phone,$courier_id,$courier_fee));
				$table1_id = $this->db->insert_id();
				$q_string_3 = "INSERT INTO tblpayment_details VALUES(?,0,'---','','','')";
				$this->db->query($q_string_3,array($table1_id));
			}
			else if($this->input->post('payment_method') == 'pickup')
			{
				$q_string_2 = "
				INSERT INTO tblorder(order_date,lastname,firstname,middlename,email,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,'PENDING',?,'00-00-0000','Pickup',?,NULL,00.00)";
				$this->db->query($q_string_2,array($order_date,$lastname,$firstname,$middlename,$email,$user_id,$phone));
				$table1_id = $this->db->insert_id();
				
				$q_string_3 = "INSERT INTO tblpayment_details VALUES(?,0,'---','','','')";
				$this->db->query($q_string_3,array($table1_id));
			}
			echo $table1_id;
			
			$this->load->helper('cookie');
			$cookie_items = $this->input->cookie('cartItems', TRUE);
			$items = explode(",",$cookie_items);
			foreach ($items as $value) {
				$item_det = explode(":",$value);
				$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
				$price_and_name = $this->db->query($q_string_4,array($item_det[0]));
				foreach ($price_and_name->result_array() as $row)
				{
						$price_times_quantity = intval($row['selling_price']) * round($item_det[1],2);
						$discount_price = $row['selling_price'] * ($row['discount_percent'] / 100); 
						$q_string_5 = "INSERT INTO tblorder_details VALUES(?,?,?,?,?,?,'On Queue',NULL,NULL,?,?)";
						$this->db->query($q_string_5,array($table1_id,$row['name'], $item_det[1],$row['selling_price'],$row['purchase_price'],$row['discount_percent'],$row['prod_id'],$item_det[0]));
						$new_qnty_of_pv = intval($row['quantity']) - $item_det[1];
						$q_string_6 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
						$this->db->query($q_string_6,array($new_qnty_of_pv,$item_det[0]));
				}
			}
		}
		else{
			$table1_id = $items_exceed;
		}
		
        $this->db->trans_complete(); 
		return $table1_id;
    }
	
	function add_new_order_with_card_no_points($user_name)
    {
        $shippingaddress = $this->input->post('checkout_address');
        $order_date = date('Y-m-d H:i:s');
		$phone = $this->input->post('checkout_phone');
        $lastname = $this->input->post('checkout_lname');
        $firstname= $this->input->post('checkout_fname');
		$middlename = $this->input->post('checkout_mname');
		if (ctype_space($middlename) || $middlename == '') {
			$middlename = NULL;
		}
        $email= $this->input->post('checkout_email');
        $shipping_address= $shippingaddress;
        $shipping_city = 'Parañaque';
        $shipping_state= 'Luzon';
        $shipping_country = 'Philippines';
        $shipping_zipcode = $this->input->post('checkout_zipcode');
		$points_equivalent = $this->input->post('checkout_reward_equivalent');
		$point_equi_int = intval($points_equivalent / 200);
		$order_type = '';
        $this->db->trans_start();
		$q_string_1 = "SELECT * FROM tbluser WHERE username = ?";
		$user_id = $this->db->query($q_string_1,array($user_name))->row()->id;
		$shipping_fee = $this->db->query("SELECT * FROM tblcourier WHERE is_default = 1");
		$courier_id = $shipping_fee->row()->cour_id;
		$courier_fee = $shipping_fee->row()->shipping_fee;
		
		$this->load->helper('cookie');
        $cookie_items = $this->input->cookie('cartItems', TRUE);
        $items = explode(",",$cookie_items);
		$num_of_items = 0;
		$num_of_items_available = 0;
		$items_exceed = array();
        foreach ($items as $value) {
			$num_of_items = $num_of_items + 1;
            $item_det = explode(":",$value);
			$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
            $price_and_name = $this->db->query($q_string_4,array($item_det[0]));
            foreach ($price_and_name->result_array() as $row)
            {
					if((intval($row['quantity']) - $item_det[1]) <= 0 )
					{
						array_push($items_exceed,$item_det[0].':'.$item_det[1]);
					}
					else
					{
						$num_of_items_available = $num_of_items_available + 1;
					}
            }
        }
		
		if($num_of_items == $num_of_items_available)
		{
			if($this->input->post('payment_method') == 'cod')
			{
				$q_string_2 = "
				INSERT INTO tblorder (order_date,lastname,firstname,middlename,email,shipping_address,shipping_city,shipping_state,shipping_country,shipping_zipcode,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,?,?,?,?,?,'PENDING',?,'00-00-0000','COD',?,?,?)";
				$this->db->query($q_string_2, array($order_date,$lastname,$firstname,$middlename,$email,$shipping_address,$shipping_city,$shipping_state,$shipping_country,$shipping_zipcode,$user_id,$phone,$courier_id,$courier_fee));
				$table1_id = $this->db->insert_id();
			}
			else if($this->input->post('payment_method') == 'pickup')
			{
				$q_string_2 = "
				INSERT INTO tblorder(order_date,lastname,firstname,middlename,email,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,'PENDING',?,'00-00-0000','Pickup',?,NULL,00.00)";
				$this->db->query($q_string_2,array($order_date,$lastname,$firstname,$middlename,$email,$user_id,$phone));
				$table1_id = $this->db->insert_id();
			}
			
			$q_string_3 = "INSERT INTO tblpayment_details VALUES(?,0,'---','','','')";
			$this->db->query($q_string_3,array($table1_id));
			$this->load->helper('cookie');
			$cookie_items = $this->input->cookie('cartItems', TRUE);
			$items = explode(",",$cookie_items);
			$price_of_order = 0.0;
			foreach ($items as $value) {
				$item_det = explode(":",$value);
				$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
				$price_and_name = $this->db->query($q_string_4,array($item_det[0]));
				foreach ($price_and_name->result_array() as $row)
				{
						$price_times_quantity = intval($row['selling_price']) * round($item_det[1],2);
						$discount_price = $row['selling_price'] * ($row['discount_percent'] / 100); 
						$q_string_5 = "INSERT INTO tblorder_details VALUES(?,?,?,?,?,?,'On Queue',NULL,NULL,?,?)";
						$this->db->query($q_string_5,array($table1_id,$row['name'], $item_det[1],$row['selling_price'],$row['purchase_price'],$row['discount_percent'],$row['prod_id'],$item_det[0]));
						$new_qnty_of_pv = intval($row['quantity']) - $item_det[1];
						$q_string_6 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
						$this->db->query($q_string_6,array($new_qnty_of_pv,$item_det[0]));
						if($row['discount_percent'] != 0.0)
						{
							$new_selling_price = floatval($row['selling_price'] * ($row['discount_percent']/100));
							$price_of_order = round($price_of_order + ($new_selling_price * $item_det[1]),2);
						}
						else
						{
							$price_of_order = round($price_of_order + $price_times_quantity);
						}
				}
			}
		}
		else{
			$table1_id = $items_exceed;
		}
		
		
        $this->db->trans_complete(); 
		return $table1_id;
    }
	
	function add_new_order_with_card_with_points($user_name)
    {
        $shippingaddress = $this->input->post('checkout_address');
        $order_date = date('Y-m-d H:i:s');
		$phone = $this->input->post('checkout_phone');
        $lastname = $this->input->post('checkout_lname');
        $firstname= $this->input->post('checkout_fname');
		$middlename = $this->input->post('checkout_mname');
		if (ctype_space($middlename) || $middlename == '') {
			$middlename = NULL;
		}
        $email= $this->input->post('checkout_email');
        $shipping_address= $shippingaddress;
        $shipping_city = 'Parañaque';
        $shipping_state= 'Luzon';
        $shipping_country = 'Philippines';
        $shipping_zipcode = $this->input->post('checkout_zip_code');
		$points_equivalent = $this->input->post('checkout_reward_equivalent');
		$points_equi_int = intval($points_equivalent/200);
		$points_used = $this->input->post('checkout_rewards_used');
		$order_type = '';
        $this->db->trans_start();
		$q_string_1 = "SELECT * FROM tbluser WHERE username = ?";
		$user_id = $this->db->query($q_string_1,array($user_name))->row()->id;
		$shipping_fee = $this->db->query("SELECT * FROM tblcourier WHERE is_default = 1");
		$courier_id = $shipping_fee->row()->cour_id;
		$courier_fee = $shipping_fee->row()->shipping_fee;
		
		$this->load->helper('cookie');
        $cookie_items = $this->input->cookie('cartItems', TRUE);
        $items = explode(",",$cookie_items);
		$num_of_items = 0;
		$num_of_items_available = 0;
		$items_exceed = array();
        foreach ($items as $value) {
			$num_of_items = $num_of_items + 1;
            $item_det = explode(":",$value);
			$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
            $price_and_name = $this->db->query($q_string_4,array($item_det[0]));
            foreach ($price_and_name->result_array() as $row)
            {
					if((intval($row['quantity']) - $item_det[1]) <= 0 )
					{
						array_push($items_exceed,$item_det[0].':'.$item_det[1]);
					}
					else
					{
						$num_of_items_available = $num_of_items_available + 1;
					}
            }
        }
		
		if($num_of_items == $num_of_items_available){
			if($this->input->post('payment_method') == 'cod')
			{
				$q_string_2 = "
				INSERT INTO tblorder (order_date,lastname,firstname,middlename,email,shipping_address,shipping_city,shipping_state,shipping_country,shipping_zipcode,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,?,?,?,?,?,'PENDING',?,'00-00-0000','COD',?,?,?)";
				$this->db->query($q_string_2, array($order_date,$lastname,$firstname,$middlename,$email,$shipping_address,$shipping_city,$shipping_state,$shipping_country,$shipping_zipcode,$user_id,$phone,$courier_id,$courier_fee));
				$table1_id = $this->db->insert_id();
			}
			else if($this->input->post('payment_method') == 'pickup')
			{
				$q_string_2 = "
				INSERT INTO tblorder(order_date,lastname,firstname,middlename,email,order_status,user_id,confirmation_date,order_type,contact_no,cour_id,shipping_fee) VALUES(?,?,?,?,?,'PENDING',?,'00-00-0000','Pickup',?,NULL,00.00)";
				$this->db->query($q_string_2,array($order_date,$lastname,$firstname,$middlename,$email,$user_id,$phone));
				$table1_id = $this->db->insert_id();
			}
			/*$q_string_1_2 = "
			SELECT * FROM tbluser AS US
			INNER JOIN tblreward_card AS CAR
				ON US.id = CAR.user_id
			WHERE US.id = ? ";
			$get_user = $this->db->query($q_string_1_2,array($user_id));
			if($get_user->num_rows() == 1)
			{
				$current_point = $get_user->row()->receipt_price;
				$new_point = ($current_point - ($points_used * 200)) + $points_equivalent;
				$q_string_2_2 = "UPDATE tblreward_card SET receipt_price = ? WHERE user_id = ?";
				$this->db->query($q_string_2_2,array($new_point,$user_id));
			}*/
			
			$q_string_3 = "INSERT INTO tblpayment_details VALUES(?,0,'---','','','')";
			$this->db->query($q_string_3,array($table1_id));
			$this->load->helper('cookie');
			$cookie_items = $this->input->cookie('cartItems', TRUE);
			$items = explode(",",$cookie_items);
			foreach ($items as $value) {
				$item_det = explode(":",$value);
				$q_string_4 = "SELECT * FROM tblproduct_variant AS PV INNER JOIN tblproduct AS P ON P.prod_id = PV.prod_id WHERE sku = ?";
				$price_and_name = $this->db->query($q_string_4,array($item_det[0]));
				foreach ($price_and_name->result_array() as $row)
				{
						$price_times_quantity = intval($row['selling_price']) * round($item_det[1],2);
						$discount_price = $row['selling_price'] * ($row['discount_percent'] / 100); 
						$q_string_5 = "INSERT INTO tblorder_details VALUES(?,?,?,?,?,?,'On Queue',NULL,NULL,?,?)";
						$this->db->query($q_string_5,array($table1_id,$row['name'], $item_det[1],$row['selling_price'],$row['purchase_price'],$row['discount_percent'],$row['prod_id'],$item_det[0]));
						$new_qnty_of_pv = intval($row['quantity']) - $item_det[1];
						$q_string_6 = "UPDATE tblproduct_variant AS PV SET PV.quantity = ? WHERE sku = ?";
						$this->db->query($q_string_6,array($new_qnty_of_pv,$item_det[0]));
				}
			}
		}
		else{
			$table1_id = $items_exceed;
		}
		
        $this->db->trans_complete(); 
		return $table1_id;
    }
	
	function is_order_verified($order_no)
	{
		if($this->db->query("SELECT * FROM tblorder WHERE order_no = ? AND (order_status <> 'Verified' && order_status <> 'Cancelled' && order_status <> 'Delivered')",array($order_no))->num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function get_order_amount($order_id,$type)
	{
		$price_of_shipping_fee = floatval($this->db->query("SELECT * FROM tblorder WHERE order_no = ?",array($order_id))->row()->shipping_fee);
		$price_of_order = floatval($this->db->query("SELECT SUM(IF(discount_percent = 0, (selling_price*quantity),
				(quantity*(selling_price - (discount_percent/100 * selling_price))))) AS m
				FROM tblorder_details WHERE order_no = ?",array($order_id))->row()->m);
		if($type = "nsf")
		{
			return $price_of_order;
		}
		else
		{
			return $price_of_order + $price_of_shipping_fee;
		}
	}
	
	function insert_items($invoice_id,$order_no)
	{
		$this->db->trans_start();
		$this->db->query("UPDATE tblorder SET invoice_no = ? WHERE order_no = ?",array($invoice_id,$order_no));
		$this->db->query("SET @cnt = 1");
		$q_string_1 = "
		INSERT INTO tblinvoice_line (invoice_no,line_no,qty,selling_price,purchase_price,amt_paid,discount,prod_id,sku)
		SELECT ?,@cnt := @cnt+1, quantity, selling_price, purchase_price, IF(discount_percent = 0.00, (selling_price*quantity),
				(quantity*(selling_price - (discount_percent/100 * selling_price)))) AS m,IF(discount_percent = 0.00,(0),(quantity*(discount_percent/100 * selling_price))) AS discount, prod_id,sku FROM tblorder_details
			WHERE order_no = ? AND item_order_status <> 'Cancelled';
		";
		$this->db->query($q_string_1,array($invoice_id,$order_no));
		$this->db->trans_complete();
	}
	
	function insert_card_transaction($card_no,$invoice_no,$points_equivalent = NULL,$points_used = NULL)
	{
		if($points_equivalent == NULL)
		{
			$points_equivalent = 0;
		}
		if($points_used == NULL)
		{
			$points_used = 0;
		}
		$this->db->query("INSERT INTO tblcard_transaction (card_no,invoice_no,gained_reward_pts,used_reward_pts) VALUES (?,?,?,?)",array($card_no,$invoice_no,$points_equivalent,$points_used));
	}
	
	function update_receipt_price($card_no,$receipt_price,$price_of_order_w_o_sf,$points_used)
	{
		if($points_used == NULL)
		{
			$points_used = 0;
		}
		$new_price = ($receipt_price - $points_used) + $price_of_order_w_o_sf;
		$this->db->query("UPDATE tblreward_card SET receipt_price = ? WHERE card_no = ? ", array($new_price,$card_no));
	}

	function verify_order_function($order_id,$points_gained = NULL,$points_used = NULL)
	{
		$q_string = "UPDATE tblorder SET order_status = 'Verified' WHERE order_no = ?";
		$this->db->query($q_string,array($order_id));
		$price_of_order = $this->get_order_amount($order_id,"nsf");
		$price_of_order_w_o_sf = $this->get_order_amount($order_id,"sf");
		$points_equivalent = floor($price_of_order/200);
		$q_string_1 = "SELECT user_id FROM tblorder WHERE order_no = ?";
		$user_id = $this->db->query($q_string_1,array($order_id))->row()->user_id;
		$q_string_7 = "INSERT INTO tblinvoice (cust_id, date, cash, vat, is_issued_receipt, is_sold_from_store) VALUES(?,?,?,0.00,0,0)";
		$this->db->query($q_string_7,array($user_id,date('Y-m-d'),$price_of_order));
		$table2_id = $this->db->insert_id();
		$this->insert_items($table2_id,$order_id);
		if($user_id != NULL)
		{
			$q_string_2 = $this->db->query("SELECT card_no,receipt_price FROM tblreward_card WHERE user_id = ?",array($user_id));
			if($q_string_2->num_rows() == 1)
			{
				$points_times_th = $points_used * 200;
				$this->insert_card_transaction($q_string_2->row()->card_no,$table2_id,$points_equivalent, $points_used);
				$this->update_receipt_price($q_string_2->row()->card_no,$q_string_2->row()->receipt_price,$price_of_order_w_o_sf,$points_times_th);
			}
		}
		return "accepted";
	}
	
    function add_new_order_credit()
    {
        $shippingaddress = $this->input->post('checkout_house').$this->input->post('checkout_street');
        $order_date = date('Y-m-d H:i:s');
        $lastname = $this->input->post('checkout_lname');
        $firstname= $this->input->post('checkout_fname');
        $email= $this->input->post('checkout_email');
        $shipping_address= $shippingaddress;
        $shipping_city = $this->input->post('checkout_city_province');
        $shipping_state= 'Luzon';
        $shipping_country = 'Philippines';
        $shipping_zipcode = $this->input->post('checkout_zip_code');
        $card_name = $this->input->post('checkout_card_name');
        $card_expiration = $this->input->post('checkout_card_expiration');
        $card_number = $this->input->post('checkout_card_number');
        $card_expiration = $this->input->post('checkout_card_expiration');
        $card_cvc = $this->input->post('checkout_card_cvc');
        $card_zip = $this->input->post('checkout_card_zip');

        $this->db->trans_start();
        $this->db->query("INSERT INTO tblorder(order_date,lastname,firstname,email,shipping_address,shipping_city,shipping_state,shipping_country,shipping_zipcode,user_id) VALUES('".$order_date."','".$lastname."','".$firstname."','".$email."','".$shipping_address."','".$shipping_city."','".$shipping_state."','".$shipping_country."',".$shipping_zipcode.",NULL)");
        $table1_id = $this->db->insert_id();
        $query_payment = "INSERT INTO tblpayment_details VALUES(".$table1_id.",0,'Credit Card','".$card_name."','".$card_number."',STR_TO_DATE('".$card_expiration."','%d-%m-%Y'))";
        $this->db->query($query_payment);
        //echo $query_payment;
        $this->load->helper('cookie');
        $cookie_items = $this->input->cookie('cartItems', TRUE);
        $items = explode(",",$cookie_items);
        foreach ($items as $value) {
            $item_det = explode(":",$value);
            $price_and_name = $this->db->query("SELECT * FROM tblproduct WHERE prod_id = '".$item_det[0]."'");
            foreach ($price_and_name->result_array() as $row)
            {
                    $price_times_quantity = intval($row['selling_price']) * round($item_det[1],2);
                    $this->db->query("INSERT INTO tblorder_details VALUES(". $table1_id .",'".$item_det[0]."','".$row['name']."',".$item_det[1].",'".$price_times_quantity."')");
            }
        }
        

        $this->db->trans_complete(); 
    }
	
}

