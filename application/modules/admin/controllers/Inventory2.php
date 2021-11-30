<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends My_Controller {

	public function __construct() 
    {
        parent::__construct();   
		$this->load->model('mdl_category');   
        $this->load->model('mdl_subcategory'); 
        $this->load->model('mdl_option');         
		$this->load->model('mdl_product'); 
		$this->load->model('mdl_image'); 
		$this->load->model('mdl_option_group'); 
		$this->load->model('mdl_prod_options');
		$this->load->model('mdl_stock_history');		
		$this->load->model('mdl_product_variant');	
		$this->load->model('mdl_non_saleable');		
		$this->load->model('mdl_supplier');
		$this->load->model('mdl_invoice_line');
		$this->load->model('mdl_transferred_items');
		
		$this->set_user_role("admin");			
		ob_clean();
	}
	public function index()
    {
		$this->products();
	}	
	public function add_supplier()
	{
		$this->form_validation->set_rules('supplier', 'Supplier Name', 'trim|required|callback_add_supplier_check|callback_letter_space');		
		$this->form_validation->set_rules('sup-contact', 'Contact No.', 'trim|required|callback_contact_supplier_check');		
		$this->form_validation->set_rules('sup-address', 'Address', 'trim|required|callback_address_supplier_check');		
		if ($this->form_validation->run($this) == FALSE) 
		{	
			$data['supplier'] = form_error('supplier');			
			$data['sup-contact'] = form_error('sup-contact');			
			$data['sup-address'] = form_error('sup-address');			
			$data['error'] = true;			
		}
		else
		{	
			$sup_data['name'] = $this->input->post('supplier');
			$sup_data['contact'] = $this->input->post('sup-contact');
			$sup_data['address'] = $this->input->post('sup-address');
			$this->log_data("Supplier Add", "Added supplier ".$sup_data['name'].".");	
			if($this->mdl_supplier->_insert($sup_data))
			{
				$data['success'] = true;
				$this->session->set_flashdata('alert_msg',' Supplier Added Successfully!');	
			}
		}
		print json_encode($data);
	}
	public function add_supplier_check($s)
	{
		if ($this->mdl_supplier->exists('name', $s))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function contact_supplier_check($s)
	{
		if ($this->mdl_supplier->exists('contact', $s))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} is the same as another supplier.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function address_supplier_check($s)
	{
		if ($this->mdl_supplier->exists('address', $s))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} is the same as another supplier.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function edit_supplier()
	{
		$this->form_validation->set_rules('modal-supplier', 'Supplier Name', 'trim|required|callback_edit_supplier_check|callback_letter_space');
		$this->form_validation->set_rules('modal-contact', 'Contact No.', 'trim|required');		
		$this->form_validation->set_rules('modal-address', 'Address', 'trim|required');		
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["name"] = form_error("modal-supplier");
			$data["contact"] = form_error("modal-contact");
			$data["address"] = form_error("modal-address");	
			$data["error"] = true;	
		}
		else
		{	
			$id = $this->input->post('modal-sup-id');
			$orig_loc = $this->mdl_supplier->get_col_where('name', 'id', $id);
			$loc_data['name'] = $this->input->post('modal-supplier');
			$loc_data['contact'] = $this->input->post('modal-contact');
			$loc_data['address'] = $this->input->post('modal-address');
			$this->log_data("Supplier Edit", "Edited supplier from $orig_loc to ".$loc_data['name'].".");	
			if($this->mdl_supplier->_update('id', $id, $loc_data))
				$this->session->set_flashdata('alert_msg','Supplier Edited Successfully!');
			$data["success"]= true;
		}		
		print json_encode($data);		
	}
	public function edit_supplier_check($s)
	{
		$orig_id = $this->input->post('modal-sup-id');
		$orig_name = $this->mdl_supplier->get_col_where('name', 'id', $orig_id);
		if (strcasecmp($orig_name, $s) != 0 && $this->mdl_supplier->exists('name', $s))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function del_supplier($id, $redirect= null)
	{
		$can_delete = null;
		$can_delete = $this->mdl_product->get_col_where('supplier_id', 'supplier_id', $id);
		if(!$can_delete)
		{
			$name = $this->mdl_supplier->get_col_where('name', 'id', $id);
			$this->log_data("Supplier Delete", "Deleted supplier ".$name.".");	
			if($this->mdl_supplier->_delete('id', $id))
			$this->session->set_flashdata('alert_msg','Supplier Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Supplier cannot be deleted.');
		}
		if (strcasecmp($redirect, 'prod_add_form') == 0)
			redirect('admin/inventory/prod_add_form');	
		else
			redirect('admin/inventory/suppliers');
	}

	public function add_qty()
	{
		$this->form_validation->set_rules('modal-date-delivered', 'Delivery Date', 'trim|required');		
		$this->form_validation->set_rules('modal-added-qty', 'Quantity', 'trim|required');
		if ($this->form_validation->run($this) == FALSE) 
		{	
			$data["qty"] = form_error("modal-added-qty");
			$data["date_added"] = form_error("modal-date-delivered");
			$data["error"] = true;	
							
		}
		else
		{	
			$prod_id = $this->input->post('modal-prod_id');
			$sku = $this->input->post('modal-sku');
			
			//Add to stock history
			$sh_data['date_added'] = $this->input->post('modal-date-delivered');
			$sh_data['qty'] = $this->input->post('modal-added-qty'); 
			$sh_data['prod_id'] = $prod_id; 
			$sh_data['sku'] = $sku; 
			$this->log_data("Product Add Quantity", "Added quantity ".$sh_data['qty']." to product $sku.");	

			//Update quantity in tblProduct
			//$this->mdl_product_variant->get_stock_on_hand($prod_id, $sku)
			$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
			$prod_data['quantity'] = $stock_on_hand + $sh_data['qty'];
			
			if($this->mdl_product_variant->update($prod_id, $sku, $prod_data) && $this->mdl_stock_history->_insert($sh_data) )
				$this->session->set_flashdata('alert_msg','Quantity Added Successfully!');		
			else
				$this->session->set_flashdata('alert_msg','Error we encountered a problem. Please refresh and try again.');			
			$data['base_url'] = base_url();
			$data['prod_id'] = $prod_id; 
			$data['sku'] = $sku; 
			$data['qty'] = $sh_data['qty'];
			$data["add_qty_success"]= true;	
			$data['hmm'] = $prod_data['quantity'];
		}	
		print json_encode($data);
	}
	function edit_sh_qty()
	{
		$this->form_validation->set_rules('modal-sh-qty', 'Quantity', 'trim|required|callback_sh_qty_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["qty"] = form_error("modal-sh-qty");
			$data["error"] = true;	
		}
		else
		{	
			$sku = $this->input->post('modal-sku');
		
			$sh_data['sh_id'] = $this->input->post('modal-sh-id');
			$sh_data['qty'] = $this->input->post('modal-sh-qty');
			$orig_qty = $this->mdl_stock_history->get_col_where('qty', 'sh_id', $sh_data['sh_id']);
			
			$currentQty  = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku) - $orig_qty;
	
			$pv_data['quantity'] = $currentQty + $sh_data['qty'];	
			$this->mdl_stock_history->_update('sh_id', $sh_data['sh_id'],  $sh_data);
			$this->mdl_product_variant->_update('sku', $sku, $pv_data);
			$this->session->set_flashdata('alert_msg','Quantity Edited Successfully!');				
			$this->log_data("Stock History Edit Quantity", "Edited quantity from ".$orig_qty.' to '.$sh_data['qty']." of product $sku.");		
			$data["success"]= true;		
		}
		print json_encode($data);
	}				
	public function del_sh_qty($sh_id, $sku)
	{
		
		$orig_qty = $this->mdl_stock_history->get_col_where('qty', 'sh_id', $sh_id);
		$prod_id = 	$this->mdl_stock_history->get_col_where('prod_id', 'sh_id', $sh_id);
		$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$no_stock_left = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku) - $orig_qty;	
		
		
		$totalSoldQty = $this->mdl_invoice_line->get_total_qty($sku);
		$totalTransferred = $this->mdl_transferred_items->get_total_qty($sku);
		$totalNonSaleable = $this->mdl_non_saleable->get_total_qty($sku);
		
		$currentQty  = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku) - $orig_qty;
		
		$total_shQty = ($this->mdl_stock_history->get_total_qty($sku) - $orig_qty);
		$total_uneditableQty = ($totalSoldQty + $totalTransferred + $totalNonSaleable );
			
		
		if($total_shQty < $total_uneditableQty)
		{
			$this->session->set_flashdata('error_msg', 'Error: Quantity is not allowed to be deleted.');		
		}
		else if ($no_stock_left <= 0)
		{
			$this->session->set_flashdata('error_msg', 'Error: Cannot delete quantity. Stock on hand cannot be equal or less than zero.');				
		}
		else
		{
			$data['quantity'] = $stock_on_hand - $orig_qty;		
			if($this->mdl_stock_history->_delete('sh_id', $sh_id) && $this->mdl_product_variant->_update('sku', $sku, $data))
				$this->session->set_flashdata('alert_msg', 'Quantity Deleted Successfully!');		
			
		}	
		redirect('admin/inventory/stock_history/'.$prod_id);
	}
	public function sh_qty_check()
	{
		$sku = $this->input->post('modal-sku');
		$qty = $this->input->post('modal-sh-qty');
		$sh_id = $this->input->post('modal-sh-id');
		$orig_qty = $this->mdl_stock_history->get_col_where('qty', 'sh_id', $sh_id);
				
		$totalSoldQty = $this->mdl_invoice_line->get_total_qty($sku);
		$totalTransferred = $this->mdl_transferred_items->get_total_qty($sku);
		$totalNonSaleable = $this->mdl_non_saleable->get_total_qty($sku);
		
		$currentQty  = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku) - $orig_qty;
	
		
		$total_shQty = ($this->mdl_stock_history->get_total_qty($sku) - $orig_qty) + $qty;
		$total_uneditableQty = ($totalSoldQty + $totalTransferred + $totalNonSaleable );
			
		if ($qty <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} cannot be equal or less than zero.');
			return FALSE;
		}
		else if($total_shQty < $total_uneditableQty)
		{
			$this->form_validation->set_message(__FUNCTION__, "Invalid quantity. Quantity is greater than total uneditable quantity.");
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function selected_online($status)
	{
		$prod_ids = $this->input->post('chk-prod[]');
		if(count($prod_ids) > 0)
		{		
			$success = true;
			foreach($prod_ids as $key => $id)
			{
				if(!$this->mdl_product->show_online($status, $id))	
					$success = false;
			}
			if($success)
			{
				if($status == 0)
				{
					$this->log_data("Products Show Online", "Selected products to be hidden online.");	
					$this->session->set_flashdata('alert_msg','Products are now hidden online.');
				}
				else
				{
					$this->log_data("Products Hide Online", "Selected products to be shown online.");	
					$this->session->set_flashdata('alert_msg','Products are now viewable online.');
				}
			}	
		}
		else
		{
			if($status == 0)
				$this->session->set_flashdata('error_msg','Error: Select products to hide online.');	
			else	
				$this->session->set_flashdata('error_msg','Error: Select products to show online.');
		}	
		redirect('admin/inventory/products');
	}
	//ASDF $this->log_data("Product Add Quantity", "Added quantity to product $sku.");
				
	public function show_all_online($prod_id)
	{
		$name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
		$this->log_data("Product Variants Online", "Shown all variants of product $name online.");
		$data['is_customer_viewable'] = 1;
		if($this->mdl_product_variant->_update('prod_id', $prod_id, $data))	
			$this->session->set_flashdata('alert_msg','Variants are now viewable online.');	
		redirect('admin/inventory/product_variants/'.$prod_id);
	}
	public function hide_all_online($prod_id)
	{
		$name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
		$this->log_data("Product Variants Online", "Hidden all variants of product $name online.");
		$data['is_customer_viewable'] = 0;
		if($this->mdl_product_variant->_update('prod_id', $prod_id, $data))	
			$this->session->set_flashdata('alert_msg','Variants are now hidden online.');	
		redirect('admin/inventory/product_variants/'.$prod_id);
	}
	public function deactivate_all($prod_id)
	{
		$name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
		$this->log_data("Product Variants Deactivate", "Deactivated all variants of product $name.");
		$data['is_active'] = 0;
		if($this->mdl_product_variant->_update('prod_id', $prod_id, $data))	
			$this->session->set_flashdata('alert_msg','Product Deactivated Successfully!');	
		redirect('admin/inventory/products');
	}
	public function prod_var_reactivate($prod_id, $sku)
	{
		$entered_qty = $this->input->post('prod_qty');
		$name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
		$this->log_data("Product Variant Reactivate", "Reactivated product variant $sku of $name.");
	
		if(!$entered_qty)
			$entered_qty = 0;
		
			$stock = $this->mdl_product_variant->get_stock_on_hand($prod_id, $sku);
			$prod_data['is_active'] = 1;
			$prod_data['quantity'] = $stock + $entered_qty;
				
			$stock_data['date_added'] = $this->input->post('date_delivered');
			$stock_data['qty'] = $entered_qty;
			$stock_data['prod_id'] = $prod_id;
			$stock_data['sku'] = $sku;
			$was_inactive = $this->mdl_product_variant->is_inactive($prod_id, $sku);
			
			if($this->mdl_product_variant->update($prod_id, $sku, $prod_data) &&  $this->mdl_stock_history->_insert($stock_data))
			{
				if($was_inactive)
					$this->session->set_flashdata('alert_msg','Product Variant Reactivated Successfully!');		
				else
					$this->session->set_flashdata('alert_msg','Product Variant Quantity Added Successfully!');			
					
			}
			$data["prod_id"]= $stock_data['prod_id'];
			$data["sku"]= $stock_data['sku'];	
			$data["success_reactivated"]= true;
			print json_encode($data);	
			//redirect('admin/inventory/prod_add_form');	
	}
	public function prod_var_add($prod_id)
	{
	
		$this->form_validation->set_rules('prod_pprice', 'Product Purchasing Price', 'trim|required|callback_decimal');
		$this->form_validation->set_rules('prod_sprice', 'Product Selling Price', 'trim|required|callback_decimal');
		$this->form_validation->set_rules('prod_qty', 'Product Quantity', 'trim|required');
		$this->form_validation->set_rules('Color', 'Color', 'trim|required');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["purchase_price"] = form_error("prod_pprice");
			$data["selling_price"] = form_error("prod_sprice");				
			$data["quantity"] = form_error("prod_qty");
			$data["Color"] = form_error("Color");			
			$data["error"] = true;	
		}
		else
		{	
			$attrib_types = $this->mdl_option_group->get_option_groups();
			$opt_grp_array = $options = array();
			foreach($attrib_types as $o)
			{
				if($this->input->post($o->opt_grp_name))
					array_push($opt_grp_array, $o->opt_grp_id);         				
				array_push($options,  $this->input->post(str_replace(' ', '_', $o->opt_grp_name)));     
			}
			$data['attrib'] = $this->prod_opt_check($options, $opt_grp_array);
			if(!$data['attrib'])
			{			
				$prod_var_data = $this->get_prod_variant_post();
				$this->insert_prod_var($prod_id, $prod_var_data);
				$data['prod_id'] = $prod_id;
				$data['sku'] = $prod_var_data['sku'];
				$data["success"]= true;
			}
		}
		print json_encode($data);						
	}
	private function attrib_val_exists($attrib_type, $attrib_val)
	{
		$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $attrib_type);
		if ($this->mdl_option->get_by_type($attrib_type_id, $attrib_val))
			return 1;
        else
			return 0;
	}
	private function insert_prod_var($prod_id, $prod_var_data)
	{
		$this->log_data("Product Variant Add", "Added product variant ". $prod_var_data['sku'] . ".");
		//Insert to tblProduct Variant
		$prod_var_data['prod_id'] = $prod_id;
		$prod_variant_insert = $this->mdl_product_variant->_insert($prod_var_data);
		
		//$prod_id, $prod_var_data['sku'], $opt_id
		
		$attrib_types = $this->mdl_option_group->get_option_groups();
		foreach($attrib_types as $o)
		{
			//If attribute value exists 
			$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $o->opt_grp_name);
			$attrib_val = $this->input->post($o->opt_grp_name);
			if(!$this->mdl_option->get_by_type($attrib_type_id, $attrib_val) && $attrib_val)
			{
				$data['opt_grp_id']= $attrib_type_id;
				$data['opt_name']= $attrib_val;
				$opt_grp_name = $o->opt_grp_name;
				$this->log_data("Attribute Value Add", "Added attribute value ".$data['opt_name']." to attribute type " . $o->opt_grp_name . ".");	
				$this->mdl_option->_insert($data);
			}
			if($attrib_val)
				$opt_id = $this->mdl_option->get_col_where('opt_id','opt_name', trim($attrib_val));
			
			$opt_data = array(
							'prod_id' => $prod_id,
							'sku' =>  $prod_var_data['sku'],
							'option_id' => $opt_id
			);
			//Insert to tblProduct Options
			$prod_opt_insert = $this->mdl_prod_options->_insert($opt_data);
		}
		
		//Insert Resized Images
		if(isset($_FILES['primary_image']) && $_FILES['primary_image']['size'] > 0)
			$this->insert_pics('primary_image', $prod_id, $prod_var_data['sku'], 'primary');
	
		if(isset($_FILES['other_images']['name'][0]) && $_FILES['other_images']['name'][0])
			$this->insert_pics('other_images', $prod_id, $prod_var_data['sku'], 'others');
		
		if($this->input->post('has_other'))
		{
			$this->insert_pics('add_other_images', $prod_id, $prod_var_data['sku'], 'others', 0);	
		}				

		//Record product addition to stock history
		$sh_data['date_added'] = date("Y-m-d");
		$sh_data['qty'] = $this->input->post('prod_qty');
		$sh_data['prod_id'] = $prod_id;
		$sh_data['sku'] = $prod_var_data['sku'];
		$this->mdl_stock_history->_insert($sh_data);		
	}
	public function prod_add()
	{	
		$prod_id = $this->input->post('prod_id');
		$sku = $this->input->post('sku');
	
		if($prod_id && $sku)
		{
			$this->prod_var_reactivate($prod_id, $sku);
			
		}	
		else
		{
			
			$this->form_validation->set_rules('prod_cat', 'Product Category', 'trim|required|callback_cat_check');
			$this->form_validation->set_rules('prod_subcat', 'Product Subcategory', 'trim|required');
			$this->form_validation->set_rules('prod_sup', 'Product Supplier', 'trim|required|callback_cat_check');
			$this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|is_unique[tblproduct.name]');
			$this->form_validation->set_rules('prod_brand', 'Product Brand', 'trim|required');
			$this->form_validation->set_rules('prod_desc', 'Product Description', 'trim');
			$this->form_validation->set_rules('prod_pprice', 'Product Purchasing Price', 'trim|required|callback_decimal');
			$this->form_validation->set_rules('prod_sprice', 'Product Selling Price', 'trim|required|callback_decimal');
			$this->form_validation->set_rules('prod_qty', 'Product Quantity', 'trim|required');
			$this->form_validation->set_rules('Color', 'Color', 'trim|required');
		
			if ($this->form_validation->run($this) == FALSE) 
			{		
				//$this->prod_add_form();				
				$data["cat_name"] = form_error("prod_cat");
				$data["subcat_name"] = form_error("prod_subcat");
				$data["name"] = form_error("prod_name");
				$data["brand"] = form_error("prod_brand");
				$data["description"] = form_error("prod_desc");
				$data["purchase_price"] = form_error("prod_pprice");
				$data["selling_price"] = form_error("prod_sprice");				
				$data["quantity"] = form_error("prod_qty");
				$data["sup_name"] = form_error("prod_sup");
				$data["Color"] = form_error("Color");			
		
				$data["error"] = true;	
			}
			else
			{				
				$data['prod_id'] = $this->insert_prod();
				$data['sku'] = $this->mdl_product_variant->get_col_where('sku', 'prod_id', $data['prod_id']);
				$data["success"]= true;
				//redirect('admin/inventory/add_result/'.$prod_id.'/'.$sku.'/prod_add_form');		
			}
			print json_encode($data);				
		}
	}	
	private function insert_prod()
	{
		$prod_data = $this->get_prod_post(); 
		$prod_var_data = $this->get_prod_variant_post();
		
		$this->log_data("Product Variant Add", "Added product variant ". $prod_var_data['sku'] . ".");
	
		//Insert to tblProduct
		$prod_insert_id = $this->mdl_product->_insert($prod_data);
		
		//Insert to tblProduct Variant
		$prod_id = $this->mdl_product->get_col_where('prod_id', 'name', $prod_data['name']);
		$prod_var_data['prod_id'] = $prod_id;
		$prod_variant_insert = $this->mdl_product_variant->_insert($prod_var_data);
		
		$attrib_types = $this->mdl_option_group->get_option_groups();
		foreach($attrib_types as $o)
		{
			//If attribute value exists 
			$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $o->opt_grp_name);
			$attrib_val = $this->input->post($o->opt_grp_name);
			if(!$this->mdl_option->get_by_type($attrib_type_id, $attrib_val) && $attrib_val)
			{
				$data['opt_grp_id']= $attrib_type_id;
				$data['opt_name']= $attrib_val;
				$opt_grp_name = $o->opt_grp_name;
				$this->log_data("Attribute Value Add", "Added attribute value ".$data['opt_name']." to attribute type " . $o->opt_grp_name . ".");	
				$this->mdl_option->_insert($data);
			}
			if($attrib_val)
				$opt_id = $this->mdl_option->get_col_where('opt_id','opt_name', trim($attrib_val));
			
			$opt_data = array(
							'prod_id' => $prod_id,
							'sku' =>  $prod_var_data['sku'],
							'option_id' => $opt_id
			);
			//Insert to tblProduct Options
			$prod_opt_insert = $this->mdl_prod_options->_insert($opt_data);
		}
		
		//Insert Resized Images
		if(isset($_FILES['primary_image']) && $_FILES['primary_image']['size'] > 0)
			$this->insert_pics('primary_image', $prod_id, $prod_var_data['sku'], 'primary');
		
		if((isset($_FILES['other_images']['name'][0]) && $_FILES['other_images']['name'][0]))
		{
			$this->delete_files($prod_id,  $prod_var_data['sku'], 'others');
			$this->insert_pics('other_images', $prod_id, $prod_var_data['sku'], 'others');	
		}
		
		if($this->input->post('has_other'))
		{
			$this->insert_pics('add_other_images', $prod_id, $prod_var_data['sku'], 'others', 0);	
		}			
		
		//Record product addition to stock history
		$sh_data['date_added'] = date("Y-m-d");
		$sh_data['qty'] = $this->input->post('prod_qty');
		$sh_data['prod_id'] = $prod_id;
		$sh_data['sku'] = $prod_var_data['sku'];
		$this->mdl_stock_history->_insert($sh_data);	
		
		if($prod_insert_id)
			return $prod_insert_id;
	}

	private function insert_pics($htmlElement, $prod_id, $sku, $type = null, $startIndex = 0)
	{
		$files = $this->multiple_upload($htmlElement, $this->input->post('prod_subcat'), $type); 		
		for($i=$startIndex; $i<count($files);$i++)
		{
			if($files[$i]['img_file_path'] &&  $files[$i]['img_size'] && $files[$i]['img_type'])
			{
					$img_data = array(
							'prod_id' => $prod_id,
							'sku' =>  $sku,
							'img_file_path' => $files[$i]['img_file_path'],
							'img_size' => $files[$i]['img_size'],
							'img_type' => $files[$i]['img_type']							
						);		
				$this->mdl_image->_insert($img_data);			
			}
		}
	}

	private function get_prod_post($type = null)
	{	
		if($type == 'modal')
		{
			$data['subcat_id'] = $this->mdl_subcategory->get_subcat_id2($this->input->post('modal-cat_name'), $this->input->post('modal-subcat_name'));			
			$data['name'] = $this->input->post('modal-name');
			$data['brand'] = $this->input->post('modal-brand');
			$data['description'] = $this->input->post('modal-description');			
			$data['supplier_id'] = $this->mdl_supplier->get_col_where('id', 'name', $this->input->post('modal-sup_name'));					
		}
		else
		{
			$data['subcat_id'] = $this->mdl_subcategory->get_subcat_id($this->input->post('prod_cat'), $this->input->post('prod_subcat'));			
			$data['name'] = $this->input->post('prod_name');
			$data['brand'] = $this->input->post('prod_brand');
			$data['description'] = $this->input->post('prod_desc');				
			$data['supplier_id'] = $this->mdl_supplier->get_col_where('id', 'name', $this->input->post('prod_sup'));					
		}
		return $data;	
	}

	private function get_prod_variant_post($sku = null, $tblName = null, $func = null)
	{
		if(is_numeric($this->input->post('prod_cat')))
		{
			$cat_id =  $this->input->post('prod_cat');
		}
		else
		{
			$cat_id =  $this->mdl_category->get_col_where('cat_id', 'cat_name', $this->input->post('prod_cat'));	
		}
		$subcat_id =  $this->mdl_subcategory->get_subcat_id($cat_id, $this->input->post('prod_subcat'));
		$cat_code = $this->mdl_category->get_col_where('cat_code', 'cat_id', $cat_id);
		$subcat_code = $this->mdl_subcategory->get_subcat_code($subcat_id);	
		
		$data['purchase_price'] = $this->input->post('prod_pprice');
		$data['selling_price'] = $this->input->post('prod_sprice');
		if($this->input->post('prod_qty') && $func == null)
			$data['quantity'] = $this->input->post('prod_qty');
		
		
		if($sku)
		{
			$prod_id = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku);
			$orig_subcat_id = $this->mdl_product->get_col_where('subcat_id', 'prod_id', $prod_id);
			 // if different subcategory
			if($orig_subcat_id != $subcat_id && $tblName == null)
			{	
				$data['sku'] = $cat_code . $subcat_code . $this->mdl_product->get_pcode_num($cat_id, $subcat_id);
			}
			else
				$data['sku'] = $this->input->post('sku');
		}
		else
		{
			$data['sku'] = $cat_code . $subcat_code . $this->mdl_product->get_pcode_num($cat_id, $subcat_id);
			$data['date_added'] =  $this->input->post('date_delivered');
		}				
		
		return $data;
	}

	private function prod_opt_check($options, $opt_grp_array, $is_edit = false)
	{
		$option_exists = true;
		$invalid_option = '';
		$prod_id = $this->input->post('prod_id');
		$orig_opt_grp_array = array();
		if($prod_id)
		{
			$og = $this->mdl_option_group->get_opt_data_by_prod($prod_id);
			foreach($og->result() as $o)
			{
				array_push($orig_opt_grp_array, $o->opt_grp_id);
			}
		}
		
		//Validation starts here downwards.
		if(strlen(trim(implode($options))) == 0)
		{
			return 'Attribute field is required.'.implode($options);
		}
		else if($prod_id)
		{
			$orig_grp_minus_input_grp = array_diff($orig_opt_grp_array, $opt_grp_array);
			$input_grp_minus_orig_grp = array_diff($opt_grp_array, $orig_opt_grp_array);
			$input_opt_variant_sku = $this->get_var_sku($prod_id, $options);
			$orig_sku = $this->input->post('sku');
			$variants_count = $this->mdl_product_variant->get_count_var($prod_id); 
			$note = null;
				if($this->mdl_product_variant->is_inactive($prod_id, $input_opt_variant_sku ))
					$note = '<b>NOTE:</b> '. $input_opt_variant_sku . " is deactivated. You can reactivate it in product add form.";	
	
			// x2 to accommodate the two arrays since the result would be the intersection or same attributes only
			if($variants_count > 1 && (count($orig_grp_minus_input_grp) > 0 || count($input_grp_minus_orig_grp) > 0))
			{
				return 'Invalid Attribute Type. Your attribute type does not match other variants of this product.';	
			}
			// if add and has sku with given attribs 
			else if(!$is_edit &&  $this->var_exists($prod_id, $options))
			{
				return 'Cannot be added. Variant already exists with a product code of '. $input_opt_variant_sku 
						. '.<br>' . $note;	
			}
			// if edit and not its own attrib and has variant (sku has value if product variant already exists)
			else if($is_edit &&  strcasecmp($input_opt_variant_sku, $orig_sku) != 0 && $input_opt_variant_sku)
			{		
				return 'Cannot be edited.' 	. ' Variant already exists with a product code of '
											. $input_opt_variant_sku . '.<br>' . $note;
			}
			else
			{
				return null;
			}			
		}
		else
		{
			return null;
		}
	}

	private function get_var_sku($prod_id, $options)
	{
		$sku_array = array();
		foreach($options as $o)
		{
			$db_options = $this->mdl_product_variant->get_options($prod_id, $o);
			foreach($db_options->result() as $d)
			{
				//echo '   SKU: ' . $d->sku. '  Options: ' . $o;
				//echo '<br>';
				array_push($sku_array, $d->sku);
			}
		}
		
		$values = array_count_values($sku_array);
		arsort($values);
		$popular = array_slice(array_keys($values), 0, 1, true);		
		//echo 'popular count: '. count($popular);
		if(count($popular) >= 1)
			return $popular[0];		
		else
			return null;
	}
	
	public function prod_edit()
	{
		$this->form_validation->set_rules('prod_cat', 'Product Category', 'trim|required|callback_cat_check');
		$this->form_validation->set_rules('prod_subcat', 'Product Subcategory', 'trim|required');
		$this->form_validation->set_rules('prod_sup', 'Product Supplier', 'trim|required|callback_cat_check');
		$this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|callback_name_check');
		$this->form_validation->set_rules('prod_brand', 'Product Brand', 'trim|required');
		$this->form_validation->set_rules('prod_desc', 'Product Description', 'trim');
		$this->form_validation->set_rules('prod_pprice', 'Product Purchasing Price', 'trim|required');
		$this->form_validation->set_rules('prod_sprice', 'Product Selling Price', 'trim|required');
		$this->form_validation->set_rules('Color', 'Color', 'trim|required');
		
		$prod_id = $this->input->post('prod_id');
		$sku = $this->input->post('sku');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["cat_name"] = form_error("prod_cat");
			$data["subcat_name"] = form_error("prod_subcat");
			$data["name"] = form_error("prod_name");
			$data["brand"] = form_error("prod_brand");
			$data["description"] = form_error("prod_desc");
			$data["purchase_price"] = form_error("prod_pprice");
			$data["selling_price"] = form_error("prod_sprice");				
			$data["sup_name"] = form_error("prod_sup");
			$data["Color"] = form_error("Color");			
			$data["error"]= true;
		}
		else
		{	
			$attrib_types = $this->mdl_option_group->get_option_groups();
			$opt_grp_array = $options = array();
			foreach($attrib_types as $o)
			{
				if($this->input->post($o->opt_grp_name))
					array_push($opt_grp_array, $o->opt_grp_id);         				
				array_push($options,  $this->input->post(str_replace(' ', '_', $o->opt_grp_name)));     
			}
			$data['attrib'] = $this->prod_opt_check($options, $opt_grp_array, true);
			if(!$data['attrib'])
			{
				//siggy
				$this->edit_prod($prod_id, $sku, 'tblproduct');
				$cat_name = $this->mdl_category->get_col_where('cat_name', 'cat_id', $this->input->post('prod_cat'));
				//$this->update_skus($prod_id,  $cat_name, $this->input->post('prod_subcat'));
			}
			$data["success"]= true;
			
		} 
		print json_encode($data);	
	}
	public function prod_edit_modal()
	{
		$this->form_validation->set_rules('modal-cat_name', 'Product Category', 'trim|required|callback_cat_check');
		$this->form_validation->set_rules('modal-subcat_name', 'Product Subcategory', 'trim|required');
		$this->form_validation->set_rules('modal-name', 'Product Name', 'trim|required|callback_name_check');
		$this->form_validation->set_rules('modal-brand', 'Product Brand', 'trim|required');
		$this->form_validation->set_rules('modal-description', 'Product Description', 'trim');
		$this->form_validation->set_rules('modal-sup_name', 'Product Supplier', 'trim|required|callback_letter_space');
		$prod_id = $this->input->post('modal-prod_id');
		
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["cat_name"] = form_error("modal-cat_name");
			$data["subcat_name"] = form_error("modal-subcat_name");
			$data["name"] = form_error("modal-name");
			$data["brand"] = form_error("modal-brand");
			$data["description"] = form_error("modal-description");
			$data["sup_name"] = form_error("modal-sup_name'");
			$data["error"] = true;	
		}
		else
		{
			
			$this->edit_prod_modal($prod_id,  $this->input->post('modal-cat_name'), $this->input->post('modal-subcat_name'));	
			$data["success"]= true;
		}  
		print json_encode($data);				
	}
	public function name_check($s)
	{
		$prod_id = null;
		if($this->input->post('prod_id'))
			$prod_id = $this->input->post('prod_id');
		else
			$prod_id = $this->input->post('modal-prod_id');
		$orig_prod_name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
		
		if (strcasecmp($orig_prod_name, $s) != 0 && $this->mdl_product->exists('name', $s))
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function var_edit()
	{
		$this->form_validation->set_rules('prod_pprice', 'Product Purchasing Price', 'trim|required');
		$this->form_validation->set_rules('prod_sprice', 'Product Selling Price', 'trim|required');
		$this->form_validation->set_rules('Color', 'Color', 'trim|required');
		
		$prod_id = $this->input->post('prod_id');
		$sku = $this->input->post('sku');
		//$this->size_validation();
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["purchase_price"] = form_error("prod_pprice");
			$data["selling_price"] = form_error("prod_sprice");				
			$data["options"] = form_error("prod_options");
			$data["Color"] = form_error("Color");			
					
			$data["error"] = true;	
		}
		else
		{				
			$attrib_types = $this->mdl_option_group->get_option_groups();
			$opt_grp_array = $options = array();
			foreach($attrib_types as $o)
			{
				if($this->input->post($o->opt_grp_name))
					array_push($opt_grp_array, $o->opt_grp_id);         				
				array_push($options,  $this->input->post(str_replace(' ', '_', $o->opt_grp_name)));     
			}
			$data['attrib'] = $this->prod_opt_check($options, $opt_grp_array, true);
			if($data['attrib'] == null)
			{
				$this->edit_prod($prod_id, $sku);
				$data['prod_id'] = $prod_id;
			}
			$data["success"]= true;
			
		}
		print json_encode($data);						
	}
	

	private function edit_prod_modal($prod_id, $cat, $subcat)
	{
		$prod_data = $this->get_prod_post('modal'); 
		$prod_name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
			
		$this->log_data("Product Variant Edit", "Edited product ". $prod_name  . ".");
	
		$this->update_skus($prod_id, $cat, $subcat);
		$prod_edit = $this->mdl_product->_update('prod_id', $prod_id, $prod_data);	

		if($prod_edit)
			$this->session->set_flashdata('alert_msg','Product Edited Successfully!');		
	}
	private function edit_prod($prod_id, $sku, $tblName = null)
	{
		
		$cat_name = $this->mdl_category->get_col_where('cat_name', 'cat_id', $this->input->post('prod_cat'));
		
		//Update tblProduct Variant
		$prod_var_data = $this->get_prod_variant_post($sku, null, 'edit');
		//print json_encode($prod_var_data);
		
		$this->mdl_product_variant->update($prod_id, $sku, $prod_var_data);		
		$sku = $prod_var_data['sku'];
		$this->log_data("Product Variant Edit", "Edited product variant ". $prod_var_data['sku'] . ".");
		
		if($tblName == 'tblproduct')
		{
			//Update tblProduct
			$prod_data = $this->get_prod_post(); 
			$prod_edit = $this->mdl_product->_update('prod_id', $prod_id, $prod_data);			
		}

		$this->mdl_option->delete_opt($prod_id, $sku);
		$attrib_types = $this->mdl_option_group->get_option_groups();
		foreach($attrib_types as $o)
		{
			//If attribute value exists 
			$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $o->opt_grp_name);
			$attrib_val = $this->input->post($o->opt_grp_name);
			if(!$this->mdl_option->get_by_type($attrib_type_id, $attrib_val) && $attrib_val)
			{
				$data['opt_grp_id']= $attrib_type_id;
				$data['opt_name']= $attrib_val;
				$opt_grp_name = $o->opt_grp_name;
				$this->log_data("Attribute Value Add", "Added attribute value ".$data['opt_name']." to attribute type " . $o->opt_grp_name . ".");	
				$this->mdl_option->_insert($data);
			}
			if($attrib_val)
				$opt_id = $this->mdl_option->get_col_where('opt_id','opt_name', trim($attrib_val));
			
			$opt_data = array(
							'prod_id' => $prod_id,
							'sku' =>  $prod_var_data['sku'],
							'option_id' => $opt_id
			);
			//Insert to tblProduct Options
			$prod_opt_insert = $this->mdl_prod_options->_insert($opt_data);
		}
		
		//Insert Resized Images	
		if(isset($_FILES['primary_image']) && $_FILES['primary_image']['size'] > 0)
		{
			$this->delete_files($prod_id, $sku, 'primary');
			$this->insert_pics('primary_image', $prod_id, $sku, 'primary');
		}
				
		if(isset($_FILES['other_images']['name'][0]) && $_FILES['other_images']['name'][0])
		{
			$this->delete_files($prod_id, $sku, 'others');
			$this->insert_pics('other_images',  $prod_id, $sku, 'others');		
		}
		if((isset($_FILES['add_other_images']['name'][0]) && $_FILES['add_other_images']['name'][0]) || $this->input->post('has_other'))
		{
			if((isset($_FILES['add_other_images']['name'][0]) && $_FILES['add_other_images']['name'][0]))
				$this->insert_pics('add_other_images', $prod_id, $prod_var_data['sku'], 'others', 0);	
			else
				$this->insert_pics('add_other_images', $prod_id, $prod_var_data['sku'], 'others', 1);	
				
		}			
		
		//if($prod_variant_edit && $prod_opt_insert)
		$this->session->set_flashdata('alert_msg','Product Variant Edited Successfully!');
		//print json_encode($prod_var_data)	
	}
	
	public function delete_file($id, $sku, $caller = null)
	{
		
		$img_path = $this->input->post('img_path');
		echo $img_path;
		$path = $_SERVER['DOCUMENT_ROOT'] . '/GoodBuy1/' . $img_path;
		if(is_file($path))
			unlink($path);
		$this->mdl_image->_delete('img_file_path', $img_path);
		echo $id . '    ' . $sku;
		
		if($caller == 'prod')
			redirect('admin/inventory/prod_edit_form/'.$id.'/'.$sku);
		else
			redirect('admin/inventory/var_edit_form/'.$id.'/'.$sku); 

	}
	function delete_files($id, $sku, $img_type)	
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . '/GoodBuy1/';
		$files = $this->mdl_product_variant->get_file_paths($id, $sku, $img_type);
		foreach($files->result() as $file)
		{ 
			$path = $path . $file->img_file_path;
			if(is_file($path))
				unlink($path);
			//echo $path. '<br>';
			$path = $_SERVER['DOCUMENT_ROOT']. '/GoodBuy1/';
		}  
		$this->mdl_product_variant->delete_imgs($id, $sku, $img_type);
	}

	public function prod_del($id, $sku, $caller, $reorder_point = null)
	{
		$data['is_active'] = 0;
		if($this->mdl_product_variant->update($id, $sku, $data))
		{
			$this->session->set_flashdata('alert_msg','Product Deactivated Successfully!');
			$this->log_data("Product Variant Deactivate", "Deactivated product variant ". $sku . ".");
		}
		if($caller == 'product_variants')
			redirect("admin/inventory/product_variants/$id");		
		else if($caller == 'min_qty')
			redirect("admin/min_qty/$reorder_point");					
		else
			redirect('admin/inventory/products');
	
	}
	public function cust_view($id, $sku, $caller)
	{
		$current_view_status = $this->mdl_product_variant->get_col_where('is_customer_viewable', 'sku', $sku);
		if($current_view_status == 0)
		{
			$data['is_customer_viewable'] = 1;
			$msg = 'Product Variant is now viewable online.';
			$this->log_data("Product Variant Show Online", "Product variant $sku was shown online.");

		}
		else
		{
			$data['is_customer_viewable'] = 0;
			$msg = 'Product Variant is now hidden online.';
			$this->log_data("Product Variant Hide Online", "Product variant $sku was hidden online.");

		}
		
		if($this->mdl_product_variant->update($id, $sku, $data))
			$this->session->set_flashdata('alert_msg', $msg);

		if($caller == 'product_variants')
			redirect("admin/inventory/product_variants/$id");		
		else
			redirect('admin/inventory/products');
	
	}
	public function tag_all_for_sale()
	{
		$prod_id = $this->input->post('prod_id');	
		$data['discount_percent'] = $this->input->post('discount_percent');		
		if($this->mdl_product_variant->update_prod_discount($prod_id, $data));
		{
			$this->session->set_flashdata('alert_msg','Product Discount Set Successfully!');
			$prod_name = $this->mdl_product->get_col_where('name', 'prod_id', $prod_id);
			$this->log_data("Tag Product For Sale", "Tagged all variants of $prod_name as ".$data['discount_percent']." percent off.");
		}
		redirect('admin/inventory/product_variants/'.$prod_id);	
	}
	public function tag_for_sale($caller)
	{
			$id = $this->input->post('modal-prod_id');	
			$sku = $this->input->post('modal-sku');	
			$data['discount_percent'] = $this->input->post('modal-discount_percent');	
			
			if($this->mdl_product_variant->update($id, $sku, $data))
			{
				$this->session->set_flashdata('alert_msg','Product Discount Set Successfully!');
				if($data['discount_percent'] > 0)
					$this->log_data("Tag Product Variant For Sale", "Tagged variant $sku as ".$data['discount_percent']." percent off.");
				else
					$this->log_data("Tagged Product Remove", "Removed percent off for $sku.");
	
			}
			if($caller == 'products')
				redirect('admin/inventory/products');			
			else
				redirect('admin/inventory/product_variants/'.$id);			
	}
	private function redirect_attrib_by_caller($caller_type, $id = null)
	{
		echo $caller_type;
		if($caller_type == 'prod_add_form')
			redirect('admin/inventory/prod_add_form');
		else if($caller_type == 'var_add_form')
		{
			redirect("admin/inventory/variant_add_form/$id");
		}
		else	
		{
			if (($pos = strpos($caller_type, "/")) !== FALSE) 
			{
				
				$str = explode('/', $caller_type);
				print_r($str);
				/*
				if($str[0] == 'prod_edit_form')
					redirect("admin/inventory/prod_edit_form/$str[1]");
				else if($str[0] == 'var_add_form')
					redirect("admin/inventory/var_add_form/$str[1]");
				*/
			}
			else
				redirect('admin/inventory/product_attributes');
		}
	}
	public function attrib_type_add($caller_type = null)
	{
		$this->form_validation->set_rules('attrib_type_name', 'Attribute Type', 'trim|required|is_unique[tbloption_group.opt_grp_name]|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["attrib_type"] = form_error("attrib_type_name");
			$data["error"] = true;	
		}
		else
		{				
			$at_data['opt_grp_name']= $this->input->post('attrib_type_name');
			$this->log_data("Attribute Type Add", "Added attribute type ".$at_data['opt_grp_name'].".");
			
			if($this->mdl_option_group->_insert($at_data))
				$this->session->set_flashdata('alert_msg','Attribute Type Added Successfully!');
			$data["success"]= true;
		}  
		print json_encode($data);	 		
	}
	public function modal_attrib_type_add()
	{
		$this->form_validation->set_rules('attrib_type_name', 'Attribute Type', 'trim|required|is_unique[tbloption_group.opt_grp_name]|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["attrib_type_name"] = form_error("attrib_type_name");
			$data["error"] = true;	
		}
		else
		{
			$data['opt_grp_name']= $this->remove_whitespace($this->input->post('attrib_type_name'));
			$this->log_data("Attribute Type Add", "Added attribute type ".$data['opt_grp_name'].".");
			
			if($this->mdl_option_group->_insert($data))
				$this->session->set_flashdata('alert_msg','Attribute Type Added Successfully!');
			$data["cat_success"]= true;
		}  
		print json_encode($data);	
	}	
	public function attrib_val_add()
	{
		$this->form_validation->set_rules('attrib_val_name', 'Attribute Value', 'trim|required|callback_attrib_val_check');//|callback_letter_space
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["attrib_val"] = form_error("attrib_val_name");
			$data["error"] = true;		
		}
		else
		{		
			$av_data['opt_grp_id']= $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib_type'));
			$av_data['opt_name']= $this->input->post('attrib_val_name');
			$opt_grp_name = $this->mdl_option_group->get_col_where('opt_grp_name', 'opt_grp_id', $av_data['opt_grp_id']);
			$this->log_data("Attribute Value Add", "Added attribute value ".$av_data['opt_name']." to attribute type " . $opt_grp_name . ".");
			
			if($this->mdl_option->_insert($av_data))
				$this->session->set_flashdata('alert_msg','Attribute Value Added Successfully!');		
			$data["success"]= true;
		}  
		print json_encode($data);	
	}	
	public function modal_attrib_val_add()
	{
		$this->form_validation->set_rules('attrib_val_name', 'Attribute Value', 'trim|required|callback_attrib_val_check');//|callback_letter_space
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["attrib_val_name"] = form_error("attrib_val_name");
			$data["error"] = true;	
		}
		else
		{
			$data['opt_grp_id']= $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib_type'));
			$data['opt_name']= $this->input->post('attrib_val_name');
			$opt_grp_name = $this->mdl_option_group->get_col_where('opt_grp_name', 'opt_grp_id', $data['opt_grp_id']);
			$this->log_data("Attribute Value Add", "Added attribute value ".$data['opt_name']." to attribute type " . $opt_grp_name . ".");
			
			$this->mdl_option->_insert($data);
				//$this->session->set_flashdata('alert_msg','Attribute Value Added Successfully!');
			$data["cat_success"]= true;
		}  
		print json_encode($data);	
	}	

	public function attrib_type_edit()
	{
		$this->form_validation->set_rules('modal-opt_grp_name', 'Attribute Type', 'trim|required|callback_edit_type_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["opt_grp_name"] = form_error("modal-opt_grp_name");
			$data["error"] = true;	
		}		
		else
		{				
			$this->process_attrib_edit('type');
			$data["success"]= true;
		} 	
		print json_encode($data);
	}
	public function attrib_val_edit()
	{
		$this->form_validation->set_rules('modal-opt_name', 'Attribute Value', 'trim|required|callback_edit_val_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$data["opt_name"] = form_error("modal-opt_name");
			$data["error"] = true;	
		}
		else
		{		
			$this->process_attrib_edit('value');
			$data["success"]= true;
		} 	
		print json_encode($data);	
	}
	private function process_attrib_edit($caller)
	{
		if($caller == 'type')
		{
			$attrib_type_id = $this->input->post('modal-opt_grp_id');
			$data['opt_grp_name'] = $this->input->post('modal-opt_grp_name');
			$this->log_data("Attribute Type Edit", "Edited attribute type ".$data['opt_grp_name'].".");
			
			if($this->mdl_option_group->_update('opt_grp_id',$attrib_type_id, $data))
				$this->session->set_flashdata('alert_msg','Attribute Type Edited Successfully!');
		}
		elseif($caller == 'value')
		{
			$attrib_value_id = $this->input->post('modal-opt_id');
			$data['opt_name'] = $this->input->post('modal-opt_name');
			$data['opt_grp_id'] = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib-type'));
			$opt_grp_name = $this->mdl_option_group->get_col_where('opt_grp_name', 'opt_grp_id', $data['opt_grp_id']);
			$this->log_data("Attribute Value Edit", "Edited attribute value ".$data['opt_name']." of attribute type " . $opt_grp_name . ".");
			
			if($this->mdl_option->_update('opt_id', $attrib_value_id, $data))
				$this->session->set_flashdata('alert_msg','Attribute Value Edited Successfully!');
		}
		
	}
	//[{"opt_id":"11","opt_grp_id":"1","opt_name":"Black"}]
	public function attrib_type_del($id , $caller_type = null)
	{
		if($this->mdl_option_group->can_delete($id))
		{	
			$name = $this->mdl_option_group->get_col_where('opt_grp_name', 'opt_grp_id', $id);
			$this->log_data("Attribute Type Delete", "Deleted attribute type $name.");
			if($this->mdl_option_group->_delete('opt_grp_id', $id))
				$this->session->set_flashdata('alert_msg','Attribute Type Deleted Successfully!');	
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Attribute Type cannot be deleted.');
		}
		$this->redirect_attrib_by_caller($caller_type);
	}	
	public function attrib_val_del($id, $caller_type = null, $var_id = null)
	{

		if($this->mdl_option->can_delete($id))
		{	
			$name = $this->mdl_option->get_col_where('opt_name', 'opt_id', $id);
			$this->log_data("Attribute Type Value", "Deleted attribute value $name.");
			if($this->mdl_option->_delete('opt_id', $id))
				$this->session->set_flashdata('alert_msg','Attribute Value Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Attribute Value cannot be deleted.');
		}	
		$this->redirect_attrib_by_caller($caller_type, $var_id);
	}	
	public function ns_add()
	{		
		//sku exists, active, required
		//qty less than equal to stock, required
		//description required
		$this->form_validation->set_rules('sku', 'Product Code', 'trim|callback_sku_check');
		$this->form_validation->set_rules('qty', 'Quantity', 'trim|callback_ns_add_qty_check');
		$this->form_validation->set_rules('reason', 'Reason', 'trim|required|callback_letter_space');
		
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data['sku'] = form_error('sku');
			$data['qty'] = form_error('qty');
			$data['reason'] = form_error('reason');
			$data['error'] = true;
		}		
		else
		{				
			// Insert to non-saleable items
			$ns_data['sku'] = $this->input->post('sku');
			$ns_data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $ns_data['sku']);
			$ns_data['qty'] = $this->input->post('qty');
			$ns_data['description'] = $this->input->post('reason');
			$ns_data['date_added'] = date("Y-m-d");
			$this->log_data("Non-saleable Item Add", "Added ".$ns_data['qty']." items of ".$ns_data['sku']." to non-saleable stock.");
			if($this->mdl_non_saleable->_insert($ns_data))
				$this->session->set_flashdata('alert_msg','Non-saleable Item Added Successfully!');
			
			//Update product variant stock
			$this->update_qty($ns_data['sku'], $ns_data['qty'], 'add');
			$data['success'] = true;
		} 			
		print json_encode($data);
	}
	public function ns_edit()
	{	

		$this->form_validation->set_rules('modal-sku', 'Product Code', 'trim|callback_sku_check');
		$this->form_validation->set_rules('modal-qty', 'Quantity', 'trim|callback_ns_edit_qty_check');
		$this->form_validation->set_rules('modal-reason', 'Reason', 'trim|required|callback_letter_space');
		
		if ($this->form_validation->run($this) == FALSE) 
		{
			$ajax_data["sku"] = form_error("modal-sku");
			$ajax_data["qty"] = form_error("modal-qty");
			$ajax_data["reason"] = form_error("modal-reason");
			$ajax_data["error"] = true;	
		}		
		else
		{				
			
			// Update non-saleable items
			$id = $this->input->post('modal-id');
			$data['sku'] = $this->input->post('modal-sku');
			$data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $data['sku']);
			$data['qty'] = $this->input->post('modal-qty');
			$data['description'] = $this->input->post('modal-reason');
			$data['date_added'] = date("Y-m-d");
			//Update product variant stock
			$this->update_qty($data['sku'], $data['qty'], 'edit', $id);
			
			$orig_sku = $this->mdl_non_saleable->get_col_where('sku', 'id', $id);
			$this->log_data("Non-saleable Item Edit", "Edited non-saleable item ".$data['sku']);
			
			if(strcasecmp($data['sku'], $orig_sku) != 0)
			{
				$orig_qty = $this->mdl_non_saleable->get_col_where('qty', 'id', $id);
				$this->update_qty($orig_sku, $orig_qty, 'return', $id);		
				$this->log_data("Non-saleable Item Edit", "Returned $orig_qty items of non-saleable ".$orig_sku." to inventory stock.");
				$this->update_qty($data['sku'], $data['qty'], 'add', $id);		
				$this->log_data("Non-saleable Item Edit", "Added ".$data['qty']." items of ".$data['sku']." to non-saleable stock.");
				
			}
			
			
			if($this->mdl_non_saleable->_update('id', $id, $data))
				$this->session->set_flashdata('alert_msg','Non-saleable Item Edited Successfully!');
			
			
			$ajax_data["success"]= true;
		} 	
		print json_encode($ajax_data);		
	}
	public function ns_cancel($ns_id, $sku, $user_qty)
	{		
		$this->log_data("Non-saleable Item Status Cancel",  "Returned $user_qty items of non-saleable ".$sku." to inventory stock.");
		if($this->update_qty($sku, $user_qty, 'delete', $ns_id))
			$this->session->set_flashdata('alert_msg','Non-saleable Item Status Canceled Successfully!');
		redirect('admin/inventory/non_saleable');		
	}
	private function update_qty($sku, $user_qty, $func, $ns_id = null)
	{	
		$product_qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$ns_qty = $this->mdl_non_saleable->get_col_where('qty','id', $ns_id);
		if($func == 'add')
		{
			$data['quantity'] = $product_qty - $user_qty;
		}
		else if($func == 'edit')
		{
			$data['quantity'] =($product_qty + $ns_qty) - $user_qty;
		}
		else if($func == 'delete')
		{
			$data['quantity'] = $product_qty + $ns_qty;
			if($ns_qty == $user_qty)
				$this->mdl_non_saleable->_delete('id', $ns_id);
		}
		else if($func == 'return')//for returning non-saleable item
		{
			$data['quantity'] = $product_qty + $ns_qty;	
		}
		 $this->mdl_product_variant->_update('sku', $sku, $data);	
		//echo '<br>';
		//echo $data['quantity'];
	}

	public function sku_check($str)
	{
		$is_active = $this->mdl_product_variant->get_col_where('is_active', 'sku', $str);
		if (!$str)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if (!$this->mdl_product_variant->exists('sku', $str))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} doesn\'t exist.');
			return FALSE;
		}
		else if($is_active ==  0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Cannot be added. Selected product variant is deactivated.');
			return FALSE;			
		}
		else
		{
			return TRUE;
		}
	}
	/*
	- Get number of product attrib types
	- Match if option exists for each type
	*/
	public function var_exists($prod_id, $options)
	{
		$type_ctr = $this->mdl_option_group->get_opt_type_ctr($prod_id);
		$ctr = 0;
		foreach($options as $o)
		{
			if($this->mdl_prod_options->get_by_prod($prod_id, $o))
				$ctr++;
		}
		if($type_ctr == $ctr)
			return true;
		else
			return false;
	}
	public function ns_add_qty_check($qty)
	{
		$sku = $this->input->post('sku');
	
		$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$prod_exists = $this->mdl_product_variant->get_col_where('sku', 'sku', $sku);
		
		
		if(!$qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if( $qty <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid quantity. Add quantity cannot be less than or equal to zero.');
			return FALSE;
		}			
		else if($prod_exists && $stock_on_hand == 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'No available stock left.');
			return FALSE;
		}//qty check applicable to add sale only
		else if($prod_exists &&  $stock_on_hand < $qty && $this->input->post('sku'))
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. It exceeds stock on hand.');
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	public function ns_edit_qty_check($qty)
	{

		$sku = $this->input->post('modal-sku');	
		$stock_on_hand = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		$prod_exists = $this->mdl_product_variant->get_col_where('is_active', 'sku', $sku);
		if($prod_exists == 1)
		{			
			$ns_id = $this->input->post('modal-id');
			$ns_qty = $this->mdl_non_saleable->get_col_where('qty', 'id', $ns_id);
			$ns_edit_qty = $stock_on_hand + $ns_qty; 

		}
		if(!$qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}
		else if($prod_exists == 1 &&  $qty <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. Edit quantity cannot be less than or equal to zero.');
			return FALSE;	
		}
		else if($prod_exists == 1 && $ns_edit_qty < $qty)
		{
			$this->form_validation->set_message(__FUNCTION__, 'Invalid Quantity. It exceeds stock on hand.');
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	public function reset_physical()
	{
		if(!$this->mdl_product_variant->get_col_where('sku', 'is_scanned', 1))
		{
			$this->session->set_flashdata('error_msg','Notice: Physical inventory is empty. Nothing to reset.');
		}
		else
		{
			
			$this->log_data("Physical Inventory Reset", "Reset physical inventory.");
			$skus = $this->input->post('chk-tally[]');
			foreach($skus as $s)
			{
				$data['is_scanned'] = 0;
				$data['scanned_qty'] = 0;
				$data['date_scanned'] = null;
				$this->mdl_product_variant->_update('sku', $s, $data);
			}
			$this->log_data("Physical Inventory Reset", "Physical inventory reset");	
			$this->session->set_flashdata('alert_msg','Physical Inventory Reset Successful!');
		}
		redirect('admin/inventory/scanned_items');	
	}
	//Validation Functions
	public function cat_check($str)
	{
		$str = $this->remove_whitespace($str);
		if (strcasecmp($str, '-PleaseSelect-') == 0)
        {
            $this->form_validation->set_message(__FUNCTION__, 'The {field} is required.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	public function attrib_val_check($attrib_value)
	{
		$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib_type'));
		if ($this->mdl_option->get_by_type($attrib_type_id, $attrib_value))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Attribute Value. Value already exists.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	public function edit_type_check($attrib_type)
	{
		$attrib_type_id = $this->input->post('modal-opt_grp_id');
		$orig_type_name = $this->mdl_option_group->get_col_where('opt_grp_name', 'opt_grp_id', $attrib_type_id);
		if (strcasecmp($orig_type_name, $attrib_type) != 0 && $this->mdl_option_group->exists('opt_grp_name', $attrib_type))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Attribute Type. Type already exists.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	public function edit_val_check($attrib_value)
	{
		$attrib_type_id = $this->mdl_option_group->get_col_where('opt_grp_id', 'opt_grp_name', $this->input->post('attrib-type'));
		$attrib_value = $this->input->post('modal-opt_name'); 
		$orig_attrib_value = $this->mdl_option->get_col_where('opt_name', 'opt_id', $this->input->post('modal-opt_id')); 
		if (strcasecmp($orig_attrib_value, $attrib_value) != 0 && $this->mdl_option->get_by_type($attrib_type_id, $attrib_value))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Attribute Value. Value already exists.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	
	private function update_skus($prod_id, $cat_name, $subcat_name)
	{
		$orig_subcat_id = $this->mdl_product->get_col_where('subcat_id', 'prod_id', $prod_id);
		$subcat_id =  $this->mdl_subcategory->get_subcat_id2($cat_name, $subcat_name);
		if($orig_subcat_id != $subcat_id)
		{
			$cat_id =  $this->mdl_category->get_col_where('cat_id', 'cat_name', $cat_name);
			$subcat_id =  $this->mdl_subcategory->get_subcat_id2($cat_name, $subcat_name);
			$cat_code = $this->mdl_category->get_col_where('cat_code', 'cat_id', $cat_id);
			$subcat_code = $this->mdl_subcategory->get_col_where('subcat_code', 'subcat_id', $subcat_id);	
			$second_iteration = $prod_updated = $invalid_sku =  false;
			$q = $this->mdl_product_variant->get_pv_skus($prod_id);
			foreach($q->result() as $s)
			{
				if($second_iteration && !$prod_updated)
				{
					$prod_data['subcat_id'] = $subcat_id;	
					$this->mdl_product->_update('prod_id', $prod_id, $prod_data);		
					$prod_updated = true;
				}
				$pcode_num =  $this->mdl_product->get_pcode_num((int)$cat_id , (int)$subcat_id,  $s->sku);
				$data['sku'] = $cat_code . $subcat_code . $pcode_num;
				if($this->mdl_product_variant->exists('sku', 'sku', $data['sku']))
				{
					for($ctr=$pcode_num;;$ctr++)
					{
						$temp_sku = $cat_code . $subcat_code . $ctr;
						if(!$this->mdl_product_variant->exists('sku', 'sku', $temp_sku))
						{
							$data['sku'] = $temp_sku;
							break;
						}
					}
				}
				//echo '<b>FROM: </b> ' . $s->sku . '  <b>TO: </b>'. $data['sku'] . '<br>';
				$this->mdl_product_variant->update($prod_id, $s->sku, $data);	
				$second_iteration = true;
			}		
		}
	}
		
	//pages
	
	public function products()
	{
		$data['subcategories'] = $this->mdl_subcategory->get_subcategories();
		$data['categories'] = $this->mdl_category->get_categories();
		$data['products'] = $this->mdl_product->get_products();
		$content = $this->load->view('vw_admin_products', $data, TRUE);		
		$this->load_view($content);
	}
	public function stock_history($prod_id)
	{
		$data['product_info'] = $this->mdl_product->get_prod_info($prod_id);
		$data['history'] = $this->mdl_stock_history->get_stock_history($prod_id);
		$data['prod_id'] = $prod_id;
		$content = $this->load->view('vw_admin_stock_history', $data, TRUE);		
		$this->load_view($content);
	}
	public function suppliers()
	{
		$data['suppliers'] = $this->mdl_supplier->get_suppliers();
		$content = $this->load->view('vw_admin_supplier', $data, TRUE);		
		$this->load_view($content);
	}
	
	public function product_variants($id=null, $id2=null, $sku=null, $caller = null, $qty = null)
	{
		
		if($id === 'large_tags')
		{
			$this->large_tags($id2, $sku, $caller, $qty);
		}
		else if($id === 'small_tags')
		{
			$this->small_tags($id2, $sku, $caller, $qty);
		}
		else
		{
			$data['product_info'] = $this->mdl_product->get_prod_info($id);
			$data['product_variants'] = $this->mdl_product_variant->get_by_prod($id);
			$content = $this->load->view('vw_admin_product_variants', $data, TRUE);		
			$this->load_view($content);			
		}

	}
	public function non_saleable()
	{
		$data['ns_items'] = $this->mdl_non_saleable->get_items();
		$content = $this->load->view('vw_admin_non_saleable', $data, TRUE);		
		$this->load_view($content);
	}
	public function scanned_items()
	{
		$data['products'] = $this->mdl_product_variant->get_by_prod(1,'scanned');
		$content = $this->load->view('vw_admin_scanned_items', $data, TRUE);		
		$this->load_view($content);
	}
	public function variant_add_form($id=null, $caller = null, $get_options = null, $opt_grp_id = null)
	{	
		if($get_options && $caller)
		{
			$opt_grp_id = $get_options;
			$this->get_options2($opt_grp_id);
		}
		else
		{
			$data['caller'] = $caller;
			$data['attrib_types'] = $this->mdl_option_group->get_option_groups();
			$data['attrib_values'] = $this->mdl_option->get_options();	
			$data['product_info'] = $this->mdl_product->get_prod_info($id);
			$data['product_variants'] = $this->mdl_product_variant->get_by_prod($id);
			$data['prod_attrib_type'] = array();
			foreach($this->mdl_prod_options->get_prod_option($id)->result() as $o)
				array_push($data['prod_attrib_type'], $o->opt_grp_name);		
			echo $caller;
			$content = $this->load->view('vw_admin_add_variant', $data, TRUE);		
			$this->load_view($content);
		}	
	}
	public function prod_add_form()
	{
		/*
		if($origin == 1)
			$data['origin'] = "admin";
		else
			$data['origin'] = "admin/inventory/products";*/
		//$this->load->view('vw_admin_add_product');
		$data['attrib_types'] = $this->mdl_option_group->get_option_groups();
		$data['attrib_values'] = $this->mdl_option->get_options();	
		$data['subcategories'] = $this->mdl_subcategory->get_subcategories();
		$data['categories'] = $this->mdl_category->get_categories();
		$data['suppliers'] = $this->mdl_supplier->get_suppliers();
		$content = $this->load->view('vw_admin_add_product', $data, TRUE);		
		$this->load_view($content);
		
	}

	public function add_result($prod_id=null, $sku=null, $caller=null, $qty = null)
	{
		$data['prod_id'] = $prod_id;
		$data['sku'] = $sku;
		$data['product'] = $this->mdl_product_variant->get_tag_info($prod_id, $sku);
		$data['large_qr_path'] = $this->large_qr_path;
		$data['caller'] = $caller;	
		$data['sh_qty'] = $qty;
		$this->load->library('ciqrcode');
		$content = $this->load->view('vw_admin_add_result', $data, TRUE);		
		$this->load_view($content);		
	}
	

	public function prod_edit_form($id=null, $sku=null, $get_options=null, $opt_grp_id=null)
	{
		if($get_options && $sku)
		{
			$opt_grp_id = $get_options;
			$this->get_options2($opt_grp_id);
		}
		else
		{
			$product = $this->mdl_product_variant->get_for_edit($id, $sku);
			foreach($product->result() as $p)
				$data['subcategories'] = $this->mdl_subcategory->get_subcategories($p->cat_id);
			$data['subcategories2'] = $this->mdl_subcategory->get_subcategories();
			$data['categories'] = $this->mdl_category->get_categories();
			$data['attrib_types'] = $this->mdl_option_group->get_option_groups();
			$data['attrib_values'] = $this->mdl_option->get_options();	
			$data['product'] = $this->mdl_product_variant->get_pv($id, $sku);
			$data['categories'] = $this->mdl_category->get_categories();
			$data['prod_attrib_values'] = $this->mdl_product_variant->get_options_by_sku($sku);	
			$data['suppliers'] = $this->mdl_supplier->get_suppliers();
			
			$data['prod_id'] = $id;
			$data['sku'] = $sku;
			$content = $this->load->view('vw_admin_edit_product', $data, TRUE);		
			$this->load_view($content);
		}
	}
	public function var_edit_form($id=null, $sku=null, $get_options=null, $opt_grp_id=null)
	{
		if($get_options && $sku)
		{
			$opt_grp_id = $get_options;
			$this->get_options2($opt_grp_id);
		}
		else
		{
			$product = $this->mdl_product_variant->get_for_edit($id, $sku);
			$data['attrib_types'] = $this->mdl_option_group->get_option_groups();
			$data['attrib_values'] = $this->mdl_option->get_options();	
			$data['product_info'] = $this->mdl_product->get_prod_info($id);
			$data['product'] = $this->mdl_product_variant->get_pv($id, $sku);
			$data['prod_attrib_values'] = $this->mdl_product_variant->get_options_by_sku($sku);
			$data['prod_id'] = $id;
			$data['sku'] = $sku;
			$data['prod_attrib_type'] = array();
			foreach($this->mdl_prod_options->get_prod_option($id)->result() as $o)
				array_push($data['prod_attrib_type'], $o->opt_grp_name);
			
			$content = $this->load->view('vw_admin_edit_variant', $data, TRUE);		
			$this->load_view($content);
		
		}	
	}
	public function large_tags($id=null, $sku=null, $caller = null, $qty = null)
	{
		if($sku)
			$this->log_data("Generate Large Tags", "Generated large tags for $sku.");
		$data['large_qr_path'] = $this->large_qr_path;
		$data['sh_qty'] = $qty;
		$data['product'] = $this->mdl_product_variant->get_tag_info($id, $sku);
		$this->load->library('ciqrcode');
		$this->load->view('vw_admin_large_tags', $data);
	}
	public function small_tags($id=null, $sku=null, $caller = null, $qty = null)
	{ 
		if($sku)
			$this->log_data("Generate Small Tags", "Generated small tags for $sku.");
		$data['small_qr_path'] = $this->small_qr_path;
		$data['sh_qty'] = $qty;
		$data['product'] = $this->mdl_product_variant->get_tag_info($id, $sku);
		$this->load->library('ciqrcode');
		$this->load->view('vw_admin_small_tags', $data);
	}
	public function single_tag($id=null, $sku=null, $caller = null)
	{ 	
		if($sku)
			$this->log_data("Generate Single Tags", "Generated single tags for $sku.");
		$data['large_qr_path'] = $this->large_qr_path;
		
		$data['product'] = $this->mdl_product_variant->get_tag_info($id, $sku);
		$this->load->library('ciqrcode');
		$this->load->view('vw_admin_single_large_tag', $data);
	}
	public function price_tags($date_from, $date_to, $tag_type = 'Small')
	{	
		$this->load->library('PDF');
		$this->load->library('ciqrcode');
		$skus = $this->input->post('chk-batch[]');
		$data['large_qr_path'] = $this->large_qr_path;
		$data['small_qr_path'] = $this->small_qr_path;
					
		$data['product'] = $data['print_qty'] =  array();
		
		$this->log_data("Generate Batch Price Tags", "Generated price tags by batch.");
		$data['is_batch_print'] = true;	
			
		if(isset($skus[0]) && $skus[0])
		{		
			$modified_prod_list = $temp_list = $qty_list = array();
			foreach($skus as $index => $s)
			{
				$sku =  $this->mdl_product_variant->get_tag_info_by_date_sku($date_from, $date_to, $s);
				foreach($sku as $key => $val)
				{
					$temp_list[$key] = $val;
				}
				array_push($data['product'], $temp_list);
				$qty_list[$s] = $this->input->post($s);
				array_push($data['print_qty'], $qty_list);
			}
			$data['is_selected'] = true;	
			if(strcasecmp($tag_type, 'single') == 0)
			{
				$this->load->view('vw_admin_single_large_tag', $data);	
			}
			else if(strcasecmp($tag_type, 'large') == 0)
			{
				$this->load->view('vw_admin_large_tags', $data);		
			}
			else if(strcasecmp($tag_type, 'small') == 0)
			{
				$this->load->view('vw_admin_small_tags', $data);		
			}	
		}
		else
		{
			$data['is_selected'] = false;
			if(strcasecmp($tag_type, 'single') == 0)
			{
				$data['product'] = $this->mdl_product_variant->get_tag_info_by_date($date_from, $date_to);		
				$this->load->view('vw_admin_single_large_tag', $data);	
			}
			else if(strcasecmp($tag_type, 'large') == 0)
			{
				$data['product'] = $this->mdl_product_variant->get_tag_info_by_date($date_from, $date_to);		
				$this->load->view('vw_admin_large_tags', $data);		
			}
			else if(strcasecmp($tag_type, 'small') == 0)
			{
				$data['product'] = $this->mdl_product_variant->get_tag_info_by_date($date_from, $date_to);		
				$this->load->view('vw_admin_small_tags', $data);		
			}
		}
	}
	public function batch_price_tags()
	{	
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		$data['tag_type'] = $this->input->post('tag-type');
		if(!$this->input->post('date-from') && !$this->input->post('date-to'))
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['products'] = $this->mdl_product_variant->get_batch_by_date($data['date_from'], $data['date_to']);
		$content = $this->load->view('vw_admin_batch_price_tags', $data, TRUE);
		$this->load_view($content);
	}

	public function product_attributes()
	{
		$data['attrib_types'] = $this->mdl_option_group->get_option_groups();
		$data['attrib_values'] = $this->mdl_option->get_options();		
		$content = $this->load->view('vw_admin_options', $data, TRUE);
		$this->load_view($content);
	}
	// public functions
	public function get_product_variant($id, $sku)
	{		
		$q = $this->mdl_product_variant->get_pv($id, $sku);
	    print json_encode($q);
    }
	public function get_option_groups()
	{
		$q = $this->mdl_option_group->get_option_groups();
	    print json_encode($q);
	}
	public function get_options2($opt_grp_id = null)
	{
		$opt_name = $this->input->get('query');
		$qry = $this->mdl_option->get_option_by_type($opt_name, $opt_grp_id);
		$obj = new stdClass();
		$obj->suggestions = $qry;
		
		print json_encode($obj);  
	}
	public function get_options($opt_grp_id = null)
	{
		$opt = $this->mdl_option->get_option_names($opt_grp_id, $opt_name);
		$arr = array();
		foreach($opt->result() as $o)
		{
			array_push($arr,$o->opt_name);
		}		
		print json_encode($arr);  
	}
	public function get_subcategories($cat_id)
	{
		if (!is_numeric($cat_id))
		{
			$cat_id = $this->mdl_category->get_col_where('cat_id', 'cat_name', $cat_id);
			print json_encode($this->mdl_subcategory->get_subcategories($cat_id));  
			
		}
		else if (is_numeric($cat_id))
			print json_encode($this->mdl_subcategory->get_subcategories($cat_id));  
		else
			print json_encode('');  		
	}
	//for Option value editing
	public function get_attrib_types()
	{
		print json_encode($this->mdl_option_group->get_option_group_names());  
	}	

	public function get_attrib_type_detail($id)
	{
		print json_encode($this->mdl_option_group->get_option_group($id));  
	}
	public function get_attrib_value_detail($id)
	{
		print json_encode($this->mdl_option->get_option($id));  
	}
	public function get_discount($id, $sku)
	{
		print json_encode($this->mdl_product_variant->get_discount($id, $sku)); 	
	}
	public function get_prod_names($type=null)
	{
		$srch_name = $this->input->post('ajax_prod_name');
		print json_encode($this->mdl_product->get_product_names($srch_name, $type)); 
	}
	public function get_prod()
	{
		$sku = $this->input->post('ajax_sku');
		$prod_id = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $sku);
		if($this->mdl_product_variant->is_inactive($prod_id, $sku))
			print json_encode($this->mdl_product_variant->get_pv($prod_id, $sku, 0)); 	
		else
			print json_encode($this->mdl_product_variant->get_pv($prod_id, $sku, 1)); 		
	}
	public function get_prod_id($id)
	{
		print json_encode($this->mdl_product->get_prod_id($id));
	}
	
	public function get_prod_info($id)
	{
		print json_encode($this->mdl_product->get_prod_info($id));
	}
	public function get_suppliers()
	{
		print json_encode($this->mdl_supplier->get_suppliers());
	}
	public function get_supplier_names()
	{
		print json_encode($this->mdl_supplier->get_supplier_names());
	}
	

	
	function get_products()
	{  
		   $fetch_data = $this->mdl_product->make_datatables();  
           $data = array();  
           foreach($fetch_data as $p)  
           {  
                $sub_array = array();  
               // $sub_array[] = '<img src="'.base_url().'upload/'.$row->image.'" class="img-thumbnail" width="50" height="35" />';  
               
				$sub_array[] = $p->prod_id;
				$sub_array[] = '<input type="checkbox" id="chk-p-'.$p->prod_id.'" value="'.$p->prod_id.'" class="chk-col-green chk-prod" name="chk-prod[]" /><label for="chk-p-'.$p->prod_id.'"></label>';		
				$sub_array[] = $p->cat_name; 				
                $sub_array[] = $p->subcat_name;				
                $sub_array[] = $p->name;  
				$sub_array[] = $p->brand; 				
                if($p->description)
					$sub_array[] = $p->description;  
                else
					$sub_array[] = 'N/A';  	
				$sub_array[] = $p->selling_price;  	
				$sub_array[] = $p->stock;  	
				$sub_array[] = $p->last_updated;  	
				if(strcasecmp($p->prod_var, 'has_multiple_var') == 0)
				{
					$sub_array[] = '<a href = "'.base_url('admin/inventory/product_variants/'.$p->prod_id).'" class="btn btn-xs bg-default waves-effect" style="background-color:#ddd;color:#555!important;">View</a>'
					. '<a href ="' .base_url('admin/inventory/variant_add_form/'.$p->prod_id.'/products'). '" class="btn btn-xs bg-green waves-effect" >Add Variant</a>'
						. '<a href ="' .base_url('admin/inventory/stock_history/'.$p->prod_id). '" class="btn btn-xs bg-default waves-effect"  style="background-color:#ddd;color:#555!important;" >Stock History</a>';			
				}
				else
				{
					if($p->is_customer_viewable == 0)
						$btnCustViewable = 	'<a href="'.base_url('admin/inventory/cust_view/'.$p->prod_id . '/' . $p->prod_var . '/products').'" class="btn btn-xs bg-light-green waves-effect col-white" >Show Online</button>';
					else
						$btnCustViewable = 	'<a href="'. base_url('admin/inventory/cust_view/'.$p->prod_id . '/' . $p->prod_var . '/products').'" class="btn btn-xs bg-deep-orange waves-effect col-white" >Hide Online</button>';
					
					if(strpos($p->sku2, '-') !== false)
						$btnAddQty = null;
					else
						$btnAddQty = '<button type="button" class="btn btn-xs bg-green waves-effect open-add-qty-prod" data-prod-id ="'.$p->prod_id.'" data-sku = "'.$p->prod_var .'" data-toggle="modal" data-target="#modal-add-qty" data-href="'.base_url('admin/inventory/add_qty').'">Add Qty</button>';
					
					if($p->stock <= 0)
						$btnDeactivate = null;
					else
						$btnDeactivate =  '<button type="button" class="btn btn-xs bg-green waves-effect confirm"  data-title="Are you sure you want to deactivate this product?" data-url="'. base_url('admin/inventory/prod_del/'.$p->prod_id . '/' . $p->sku2 . '/products').'">Deactivate</button>';	
						
					
					$sub_array[] = '<button type="button" class="btn btn-xs bg-default waves-effect btn-view-prod" data-prod-id ="'.$p->prod_id.'" data-sku = "'.$p->prod_var .'" data-toggle="modal" data-target="#product-info">View</button>'
					. $btnAddQty
					. '<button type="button" class="btn btn-xs bg-default waves-effect" onclick="window.location.href='."'".base_url('admin/inventory/prod_edit_form/'.$p->prod_id. '/' .$p->prod_var )."'".'">Edit</button>'
					. $btnDeactivate
					. '<button type="button" class="btn btn-xs bg-default waves-effect open-prod-lg"  data-id="'.$p->prod_id.'" data-sku="'.$p->prod_var.'" data-qty="'.$p->quantity.'">Large Tags</button>'
					. '<button type="button" class="btn btn-xs bg-green waves-effect open-prod-sm" data-id="'.$p->prod_id.'" data-sku="'.$p->prod_var.'" data-qty="'.$p->quantity.'">Small Tags</button>'
					. '<button type="button" class="btn btn-xs bg-default waves-effect open-print-window" data-href="'.base_url('admin/inventory/single_tag/'.$p->prod_id.'/'.$p->prod_var ).'" >Single Tag</button>'												
					. '<button type="button" class="btn btn-xs bg-green waves-effect open-discount" data-prod-id ="'.$p->prod_id.'" data-sku = "'.$p->prod_var .'" data-toggle="modal" data-target="#product-discount" >Tag for sale</button>'																								  
					. $btnCustViewable
					. '<a href ="' .base_url('admin/inventory/variant_add_form/'.$p->prod_id.'/products'). '" class="btn btn-xs bg-green waves-effect"  >Add Variant</a>'
						. '<a href ="' .base_url('admin/inventory/stock_history/'.$p->prod_id). '" class="btn btn-xs bg-default waves-effect"  style="background-color:#ddd;color:#555!important;" >Stock History</a>';		
				}
				$data[] = $sub_array;  
           }
			// data-toggle="modal" data-target="#modal-sm-tags" 		   
		   if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_product->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_product->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);  
     }  
}