<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transfer extends My_Controller {

	public function __construct() 
    {
        parent::__construct();  
		$this->set_user_role("admin");	
		$this->load->model('mdl_product'); 
		$this->load->model('mdl_product_variant'); 
		$this->load->model('mdl_location'); 
		$this->load->model('mdl_transferred_items');		
		ob_clean();
	}
	public function index()
    {
		$this->transfer_item();
	}

	//TRANSFER TO BRANCH
	public function process_transfer()
	{
		$this->form_validation->set_rules('transfer-date', 'Transfer Date', 'trim|required');
		$this->form_validation->set_rules('branch', 'Branch Location', 'trim|required|callback_branch_check');
		$this->form_validation->set_rules('sku[]', 'Product Code', 'trim|required');
		$this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required|callback_transfer_qty_check');
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->transfer_item();
		}
		else	
		{	
			$sku = $this->input->post('sku[]');
			$qty = $this->input->post('qty[]');

			if($sku)
			{
				for($i=0;$i<count($sku);$i++)
				{
					//Insert to tblTransferred_Items
					$data['date_transferred'] = $this->input->post('transfer-date');
					$data['qty_transferred'] = $qty[$i];
					$data['loc_id'] = $this->mdl_location->get_col_where('loc_id', 'location',$this->input->post('branch'));
					$data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku[$i]);
					$data['sku'] =  $sku[$i];
					if($this->mdl_transferred_items->_insert($data))
					{
						//Update tblProduct quantity field
						$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $data['sku']);
						$prod_data['quantity'] =  $stock_on_hand - $data['qty_transferred'];
						$this->mdl_product_variant->update($data['prod_id'],  $data['sku'],$prod_data);
					}
				}
				$this->session->set_flashdata('alert_msg','Item/s Transferred Successfully!');		
				$this->log_data("Items Transfer", "Transferred items to ".$this->input->post('branch').".");	
			}
			else
			{
				$this->session->set_flashdata('alert_msg','Error transfer box is empty.');	
			}
			redirect('admin/transfer');
		}
	
	}

	public function transfer_back($sku, $loc, $transfer_back_qty)
	{
		$transfer_ids = $this->mdl_transferred_items->get_transfer_ids($sku, $loc);
		foreach($transfer_ids->result() as $t)
		{
			if($t->qty_transferred > $transfer_back_qty)
			{
				$data['qty_transferred'] = $t->qty_transferred - $transfer_back_qty;
				$this->mdl_transferred_items->_update('transfer_id', $t->transfer_id, $data);
				break;
			}	
			else if($t->qty_transferred <= $transfer_back_qty)
			{	
				$this->mdl_transferred_items->_delete('transfer_id', $t->transfer_id);
			}
			$transfer_back_qty = $transfer_back_qty - $t->qty_transferred;
		}
		
	}
	//RECEIVE TRANSFERS
	public function process_receive()
	{
		$this->form_validation->set_rules('sku[]', 'Product Code', 'trim|required');
		$this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required|callback_receive_qty_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->receive_item();
		}
		else	
		{
			$sku = $this->input->post('sku[]');
			$qty = $this->input->post('qty[]');
			$loc = $this->input->post('loc[]');
			$loc_name = array();
			if($sku)
			{
				for($i=0;$i<count($sku);$i++)
				{			
					//Update tblProduct_Variant quantity field
					$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku[$i]);
					$prod_data['quantity'] =  $stock_on_hand + $qty[$i];
					$this->mdl_product_variant->_update('sku', $sku[$i], $prod_data);	

					//Update tblTransferred_Items
					$this->transfer_back($sku[$i], $loc[$i], $qty[$i]);
					array_push($loc_name, $this->mdl_location->get_col_where('location', 'loc_id', $loc[$i]));
				}
				$this->session->set_flashdata('alert_msg','Item/s Received Successfully!');	
				$loc_names = implode(',', $loc_name);
				$this->log_data("Items Transfer Back", "Received items from ff. locations: ".$loc_names.".");	
			}
			else
			{
				$this->session->set_flashdata('alert_msg','Error transfer box is empty.');	
			}
			redirect('admin/transfer/receive_item');
		}		
	}
	public function transfer_sku_check($sku)
	{
		for($i=0;$i<count($sku);$i++)
		{	
			
			if(!$this->mdl_product_variant->exists('sku', $sku[$i]))
			{
				$this->form_validation->set_message(__FUNCTION__, "$sku[$i] doesn't exist.");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
			
		}
		
	}
	public function receive_sku_check($sku)
	{
		for($i=0;$i<count($sku);$i++)
		{	
			
			if(!$this->mdl_transferred_items->receive_sku_exists($sku[$i]))
			{
				$this->form_validation->set_message(__FUNCTION__, "$sku[$i] doesn't exist.");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
			
		}
		
	}
	public function transfer_qty_check()
	{
		$qty = $this->input->post('qty[]');
		$sku = $this->input->post('sku[]');
		for($i=0;$i<count($sku);$i++)
		{	
			
			$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku[$i]);
			if($qty[$i] > $stock_on_hand)
			{
				$this->form_validation->set_message(__FUNCTION__, "Transfer quantity for product $sku[$i] exceeds stock. <br> Stock on hand: $stock_on_hand <br> Entered Qty: $qty[$i]");
				return FALSE;
			}
			else if($qty[$i] <= 0)
			{
				$this->form_validation->set_message(__FUNCTION__, "Transfer quantity for product $sku[$i] is invalid.");
				return FALSE;				
			}
			else
			{
				return TRUE;
			}
			
		}

	}		
	
	public function receive_qty_check()
	{
		$qty = $this->input->post('qty[]');
		$sku = $this->input->post('sku[]');
		//$transfer_id = $this->input->post('transfer_id[]');	
		for($i=0;$i<count($sku);$i++)
		{	
			
			$transfer_stock = $this->mdl_transferred_items->get_total_qty($sku[$i]);
			
			if($qty[$i] > $transfer_stock)
			{
				$this->form_validation->set_message(__FUNCTION__, "Transfer quantity for product $sku[$i] exceeds stock. <br> Qty entered: $qty[$i] <br> Stock: $transfer_stock");
				return FALSE;
			}
			else if($qty[$i] <= 0)
			{
				$this->form_validation->set_message(__FUNCTION__, "Transfer quantity for product $sku[$i] is invalid.");
				return FALSE;				
			}
			else
			{
				return TRUE;
			}
		}

	}	

	public function branch_check($str)
	{
		if (!$this->mdl_location->exists('location', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, 'Selected {field} doesn\'t exist.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}		
	//LOCATION MANAGEMENT
	public function add_location()
	{
		$this->form_validation->set_rules('location', 'Location', 'trim|required|is_unique[tbllocation.location]|callback_location_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data['loc'] = form_error('location');
			$data['error'] = true;
		}
		else
		{	
			$loc_data['location'] = $this->input->post('location');
			$this->log_data("Location Add", "Added location ".$loc_data['location'].".");	
			if($this->mdl_location->_insert($loc_data))
				$this->session->set_flashdata('alert_msg','Location Added Successfully!');
			$data['success'] = true;
		}	
		print json_encode($data);
	}
	public function edit_location($id)
	{
		$this->form_validation->set_rules('modal-location', 'Location', 'trim|required|callback_location_edit_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["location"] = form_error("modal-location");
			$data["error"] = true;	
		}
		else
		{	
			$orig_loc = $this->mdl_location->get_col_where('location', 'loc_id', $id);
			$loc_data['location'] = $this->input->post('modal-location');
			$this->log_data("Location Edit", "Edited location from $orig_loc to ".$loc_data['location'].".");	
			if($this->mdl_location->_update('loc_id', $id, $loc_data))
				$this->session->set_flashdata('alert_msg','Location Edited Successfully!');
			$data["success"]= true;
		}		
		print json_encode($data);		
	}
	
	public function del_location($id)
	{
		$can_delete = null;
		$can_delete = $this->mdl_transferred_items->get_col_where('transfer_id', 'loc_id', $id);
		if(!$can_delete)
		{
			$loc_name = $this->mdl_location->get_col_where('location', 'loc_id', $id);
			$this->log_data("Location Delete", "Deleted location ".$loc_name.".");	
			if($this->mdl_location->_delete('loc_id', $id))
			$this->session->set_flashdata('alert_msg','Location Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Location cannot be deleted.');
		}
		redirect('admin/transfer/manage_locations');
	}
		
	private function location_view()
	{
		$data['locations'] = $this->mdl_location->get_locations();
		$content = $this->load->view('vw_admin_locations', $data, TRUE);	
		$this->load_view($content);	
	}
	public function get_location($id)
	{
		print json_encode($this->mdl_location->get_location($id));	
	}
	public function location_check($str)
	{
			
		// match numbers, special characters except comma
		if (preg_match('/[#$%^&*()+=\-\[\]\';.\/{}|":<>?~\\\\0-9]+/', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} must only contain alphabetic characters.');
			return FALSE;
		}
		else if(!preg_match("/[a-zA-Z]/i", $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} doesn\'t contain alphabetic characters.');
			return FALSE;
		}	
		else
		{
			return TRUE;
		}	
	}
	public function location_edit_check()
	{
	
		$str = $this->input->post('modal-location');
		$id = $this->input->post('modal-loc_id');	
		$orig_loc_name = $this->mdl_location->get_col_where('location', 'loc_id', $id);		
			
		// match numbers, special characters except comma
		if (preg_match('/[#$%^&*()+=\-\[\]\';.\/{}|":<>?~\\\\0-9]+/', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} must only contain alphabetic characters.');
			return FALSE;
		}
		else if(!preg_match("/[a-zA-Z]/i", $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} doesn\'t contain alphabetic characters.');
			return FALSE;
		}	
		else if(strcasecmp($orig_loc_name, $str) != 0 && $this->mdl_location->exists('location', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} already exists.');
			return FALSE;	
		}
		else
		{
			return TRUE;
		}	
	}
	
	//END LOCATION MANAGEMENT
	public function get_prod_codes()
	{
		$s = $this->input->post('ajax_prod_code');
		print json_encode($this->mdl_product_variant->get_product_codes($s));  
	}
	public function get_transfer_prod_info()
	{
		$s = $this->input->post('ajax_sku');
		print json_encode($this->mdl_product_variant->get_sale_prod_info($s));  
	}
	public function get_receive_prod_info()
	{
		$s = $this->input->post('ajax_sku');
		print json_encode($this->mdl_transferred_items->get_receive_prod_info($s));  
	}	
	function get_products()
	{  
		   $fetch_data = $this->mdl_product_variant->make_datatables();  
           $data = array();  
           foreach($fetch_data as $p)  
           {  
                $sub_array = array();  
			
				$sub_array[] = $p->prod_id;
				$sub_array[] = '<input type="checkbox" name="items" class="chk-col-green" id = "chk-'.$p->sku.'" data-prod-code = "'.$p->sku.'" data-qty="'.$p->quantity.'" data-name="'.$p->name.'"  data-options="'.$p->options.'"/><label for="chk-'.$p->sku.'"></label>';
				$sub_array[] = $p->sku;
				$sub_array[] = $p->name;
				$sub_array[] = $p->options;
				$sub_array[] = $p->brand;
				$sub_array[] = $p->quantity;
				$sub_array[] = $p->purchase_price;
				$sub_array[] = $p->selling_price;
				$sub_array[] = '<button type="button" class="btn btn-xs bg-default waves-effect btn-view-prod" data-prod-id ="'.$p->prod_id.'" data-sku = "'.$p->sku.'" data-toggle="modal" data-target="#product-info">View</button>';													
					
				$data[] = $sub_array;  
           }  
		   if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_product_variant->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_product_variant->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);  
		   
     }  
	//PAGES
	public function transfer_item()	
	{
		$data['locations'] = $this->mdl_location->get_locations();
		$data['products'] = $this->mdl_product_variant->get_pvs();
		$content = $this->load->view('vw_admin_item_transfer', $data, TRUE);	
		$this->load_view($content);		
	}
	public function receive_item()	
	{
		$data['products'] = $this->mdl_transferred_items->get_transferred_items();
		$content = $this->load->view('vw_admin_receive_transfer', $data, TRUE);	
		$this->load_view($content);		
	}
	public function manage_locations()
	{
		$this->location_view();
	}
	
}
