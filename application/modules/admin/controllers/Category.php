<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
        $this->load->model('mdl_category');   
        $this->load->model('mdl_subcategory');   
		$this->load->model('mdl_product_variant'); 
		$this->set_user_role("admin");			
		ob_clean();
	}
	public function index()
    {
		$this->load_cat_view();
	}
	private function load_cat_view()
	{

		$cat_data['subcategories'] = $this->mdl_subcategory->get_subcategories();
		$cat_data['categories'] = $this->mdl_category->get_categories();
		$content =  $this->load->view('vw_admin_category', $cat_data, TRUE);	
		$this->load_view($content);		
	}
	private function redirect_by_caller($caller_type)
	{
		//get str after /  substr($data, strpos($data, "_") + 1);
		// get str before /
		if($caller_type == 'prod_add_form')
			redirect('admin/inventory/prod_add_form');
		else	
		{
			if (($pos = strpos($caller_type, "/")) !== FALSE) 
			{
				$str = explode('/', $caller_type);
				redirect("admin/inventory/prod_edit_form/$str[1]");
			}
			else
				redirect('admin/category');
		}
	}
	public function add($caller_type = null)
	{
		$this->form_validation->set_rules('cat_name', 'Category', 'trim|required|callback_cat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data['cat'] = form_error("cat_name");
			$data["error"] = true;	
		}
		else
		{
			$cat_data['cat_name']= strtoupper($this->input->post('cat_name'));
			$cat_data['cat_code']= strtoupper($this->get_cat_code($this->input->post('cat_name')));
			$this->log_data("Category Add", "Added category " . $cat_data['cat_name'] . ".");	

			if($this->mdl_category->_insert($cat_data))
				$this->session->set_flashdata('alert_msg','Category Added Successfully!');
			$data["success"] = true;	
		}   
		print json_encode($data);	
	}
	public function modal_cat_add($caller_type = null)
	{
		$this->form_validation->set_rules('cat_name', 'Category', 'trim|required|callback_cat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["modal_cat_name"] = form_error("cat_name");
			$data["error"] = true;	
		}
		else
		{
			$data['cat_name']= strtoupper($this->input->post('cat_name'));
			$data['cat_code']= strtoupper($this->get_cat_code($this->input->post('cat_name')));
			$this->log_data("Category Add", "Added category " . $data['cat_name'] . ".");	

			if($this->mdl_category->_insert($data))
			{
				$data["cat_success"]= true;
				$this->session->set_flashdata('alert_msg','Category Added Successfully!');	
			}
		}  
		print json_encode($data);	
	}
	
	public function modal_subcat_add($caller_type = null)
	{
		$this->form_validation->set_rules('cat_name_forsubcat', 'Category', 'trim|required');
		$this->form_validation->set_rules('subcat_name', 'Subcategory', 'trim|required|callback_subcat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["cat_name_forsubcat"] = form_error("cat_name_forsubcat");
			$data["subcat_name"] = form_error("subcat_name");	
			$data["error"] = true;	
		}
		else
		{
			$data['subcat_name']= strtoupper($this->input->post('subcat_name'));
			$data['cat_id']= $this->mdl_category->get_cat_id('cat_id', 'cat_name',strtoupper($this->input->post('cat_name_forsubcat')));
			$data['subcat_code']= strtoupper($this->get_subcat_code($data['cat_id'], $data['subcat_name']));		
			$this->log_data("Subcategory Add", "Added subcategory " . $data['subcat_name'] . ".");	

			if($this->mdl_subcategory->_insert($data))
			{
				$data["subcat_success"]= true;
				$this->session->set_flashdata('alert_msg','Subcategory added successfully!');
			}	
			
		}  
		print json_encode($data);	
	}
	public function edit($caller_type = null)
	{
		$this->form_validation->set_rules('modal-cat_name', 'Category', 'trim|required|callback_edit_cat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["cat_name"] = form_error("modal-cat_name");
			$data["error"] = true;	
		}
		else
		{
			$this->process_edit('cat', $caller_type);
			$data["success"]= true;
			//$this->redirect_by_caller($caller_type);
		}  
		print json_encode($data);		
		
	}
	public function test($s, $r)
	{
		echo substr_replace($s,$r,0,1);
	}
	//Call update_skus before updating cat_code/subcat_code
	//substr_replace(string,replacement,start,length)

	private function update_skus_by_cat($id, $current_code, $new_code)
	{
		$current_code_len = strlen($current_code);
		$skus = $this->mdl_product_variant->get_skus_by('category', $id);
		foreach($skus as $s)
		{
			$current_sku = $s->sku;
			$data['sku'] = substr_replace($s->sku, $new_code,0,$current_code_len);
			$this->mdl_product_variant->_update('sku', $s->sku, $data);		
		}
	}
	private function update_skus_by_subcat($id, $current_code, $new_code)
	{
		$current_code_len = strlen($current_code);
		$cat_id = $this->mdl_subcategory->get_col_where('cat_id', 'subcat_id', $id);
		$cat_code = $this->mdl_category->get_col_where('cat_code', 'cat_id', $cat_id);
		$skus = $this->mdl_product_variant->get_skus_by('subcategory', $id);
		foreach($skus as $s)
		{
			$current_sku = $s->sku;
			$data['sku'] = substr_replace($s->sku, $new_code, strlen($cat_code),$current_code_len);
			$this->mdl_product_variant->_update('sku', $s->sku, $data);		
		}
	}
	private function process_edit($func, $caller_type)
	{
		if($func == 'cat')
		{
			$cat_id = $this->input->post('modal-cat_id');
			$current_cat_code = $this->mdl_category->get_col_where('cat_code', 'cat_id', $cat_id);
			$data['cat_name'] = strtoupper($this->input->post('modal-cat_name'));
			$data['cat_code'] =  $this->get_cat_code($data['cat_name'], 'edit', $cat_id);
			if(strcmp($current_cat_code, $data['cat_code']) != 0)
				$this->update_skus_by_cat($cat_id, $current_cat_code, $data['cat_code']);
		
			if($this->mdl_category->_update('cat_id', $cat_id, $data))
				$this->session->set_flashdata('alert_msg','Category Edited Successfully!');
			$this->log_data("Category Edit", "Edited category " . $data['cat_name'] . ".");	

		}
		else if($func == 'subcat')
		{
			$subcat_id = $this->input->post('modal-subcat_id');
			$current_subcat_code = $this->mdl_subcategory->get_subcat_code($subcat_id);
			$data['subcat_name'] = strtoupper($this->input->post('modal-subcat_name'));	
			$data['cat_id']= $this->mdl_category->get_cat_id('cat_id', 'cat_name',strtoupper($this->input->post('modal-categories')));	
			$data['subcat_code'] = $this->get_subcat_code($data['cat_id'], $data['subcat_name'], 'edit', $subcat_id); 
			if(strcmp($current_subcat_code, $data['subcat_code']) != 0)
				$this->update_skus_by_subcat($subcat_id, $current_subcat_code, $data['subcat_code']);
			$this->log_data("Subcategory Edit", "Edited subcategory " . $data['subcat_name'] . ".");	

			if($this->mdl_subcategory->_update('subcat_id', $subcat_id, $data))
				$this->session->set_flashdata('alert_msg','Subcategory Edited Successfully!');
		}

	}
	public function del($id, $caller_type = null)
	{
		if($this->mdl_category->can_delete($id))
		{
			$cat_name = $this->mdl_category->get_col_where('cat_name', 'cat_id', $id);
			$this->log_data("Category Delete", "Deleted category " . $cat_name . ".");	

			if($this->mdl_category->_delete('cat_id', $id))
				$this->session->set_flashdata('alert_msg','Category Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Category cannot be deleted.');
		}	
		$this->redirect_by_caller($caller_type);
	}
	public function subcat_add($caller_type = null)
	{
		$this->form_validation->set_rules('cat_name_forsubcat', 'Category', 'trim|required');
		$this->form_validation->set_rules('subcat_name', 'Subcategory', 'trim|required|callback_subcat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["subcat"] = form_error("subcat_name");
			$data["error"] = true;	
		}
		else
		{
			$subcat_data['subcat_name']= strtoupper($this->input->post('subcat_name'));
			$subcat_data['cat_id']= $this->mdl_category->get_cat_id('cat_id', 'cat_name',strtoupper($this->input->post('cat_name_forsubcat')));
			$subcat_data['subcat_code']= strtoupper($this->get_subcat_code($subcat_data['cat_id'], $subcat_data['subcat_name']));		
			$this->log_data("Subcategory Add", "Added subcategory " . $subcat_data['subcat_name'] . ".");	
			if($this->mdl_subcategory->_insert($subcat_data))
				$this->session->set_flashdata('alert_msg','Subcategory added successfully!');
			$data["success"]= true;
		}  
		print json_encode($data);	
	}
	public function subcat_edit($caller_type = null)
	{
		$this->form_validation->set_rules('modal-subcat_name', 'Subcategory', 'trim|required|callback_edit_subcat_check|callback_letter_space');
		if ($this->form_validation->run($this) == FALSE) 
		{
			$data["subcat_name"] = form_error("modal-subcat_name");
			$data["error"] = true;	
		}
		else
		{
			$this->process_edit('subcat', $caller_type);
			$data["success"]= true;
		}  
		print json_encode($data);	
	}
	public function subcat_del($id, $caller_type = null)
	{
		if($this->mdl_subcategory->can_delete($id))
		{	
			$subcat_name = $this->mdl_subcategory->get_col_where('subcat_name', 'subcat_id', $id);
			$this->log_data("Subcategory Delete", "Deleted subcategory " . $subcat_name . ".");	

			if($this->mdl_subcategory->_delete('subcat_id', $id))
				$this->session->set_flashdata('alert_msg','Subcategory Deleted Successfully!');
		}
		else
		{
			$this->session->set_flashdata('error_msg','Error: Subcategory cannot be deleted.');
		}
		$this->redirect_by_caller($caller_type);
	}
	public function cat_check($c)
    {
        if ($this->mdl_category->exists('cat_name', $c))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Category. Category already exists.');
            return FALSE;
        }
		else if (!empty($c) && empty($this->get_cat_code($c)))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Category. Cannot generate category code.');
            return FALSE;
        }
		else if(!empty($c) && $this->mdl_category->exists('cat_code', $this->get_cat_code($c)))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Category. Generated category code already exists.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
	public function edit_cat_check($c)
    {
		$cat_id = $this->input->post('modal-cat_id');
		$orig_cat_name = $this->mdl_category->get_col_where('cat_name', 'cat_id', $cat_id);
        if (strcasecmp($orig_cat_name, $c) != 0 && $this->mdl_category->exists('cat_name', $c))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Category. Category already exists.');
            return FALSE;
        }
		else if(!empty($c) && strcasecmp($c, $orig_cat_name) != 0 &&$this->mdl_category->exists('cat_code', $this->get_cat_code($c)))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Category. No possible category code can be generated.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

	public function subcat_check($subcat_name)
    {
		$cat_id =  $this->mdl_category->get_cat_id('cat_id', 'cat_name',strtoupper($this->input->post('cat_name_forsubcat')));
	    
		if(strlen($subcat_name) < 3)
		{
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Subcategory must at least be 3 characters.');
            return FALSE;				
		}
		else if(!empty($subcat_name) && $this->mdl_subcategory->get_by_cat($cat_id, 'subcat_code', $this->get_subcat_code($cat_id, $subcat_name)))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Generated subcategory code already exists.');
            return FALSE;
        }
		else if($this->mdl_subcategory->get_by_cat2($cat_id, $subcat_name))
		{
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Subcategory already exists.');
            return FALSE;			
		}
		else if (!empty($subcat_name) && empty($this->get_subcat_code($cat_id, $subcat_name)))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Cannot generate subcategory code.');
            return FALSE;
        }	
        else
        {
            return TRUE;
        
		}
    }
	
	public function edit_subcat_check($subcat_name)
    {
		
		$cat_id =  $this->mdl_category->get_cat_id('cat_id', 'cat_name',strtoupper($this->input->post('modal-categories')));
	    $subcat_id = $this->input->post('modal-subcat_id');
		$orig_subcat_name = $this->mdl_subcategory->get_col_where('subcat_name', 'subcat_id', $subcat_id);
		$orig_subcat_code = $this->mdl_subcategory->get_col_where('subcat_code', 'subcat_id', $subcat_id);
		$orig_cat_id = $this->mdl_subcategory->get_col_where('cat_id', 'subcat_id', $subcat_id);
		$generated_subcat_code =  $this->get_subcat_code($cat_id, $subcat_name);
		if(strlen($subcat_name) < 3)
		{
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Subcategory must at least be 3 characters.');
            return FALSE;				
		}
		else if(strcasecmp($orig_subcat_name, $subcat_name) != 0 && $this->mdl_subcategory->get_by_cat2($cat_id, $subcat_name))//$orig_cat_id != $cat_id && 
		{
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Subcategory already exists.');
            return FALSE;			
		}
		else if(!empty($subcat_name) && strcasecmp($orig_subcat_code,  $generated_subcat_code) != 0 && $this->mdl_subcategory->get_by_cat($cat_id, 'subcat_code' , $generated_subcat_code))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Generated subcategory code already exists.');
            return FALSE;
        }
		else if (!empty($subcat_name) && empty($generated_subcat_code))
        {
            $this->form_validation->set_message(__FUNCTION__, 'Invalid Subcategory. Cannot generate subcategory code.');
            return FALSE;
        }	
        else
        {
            return TRUE;
        
		}
    }

	public function get_cat_code($categ, $func = null, $cat_id = null)
	{
		$categ = $this->remove_whitespace($categ);
		$code = array();
		$code[0] = $categ[0];		

			if(strlen($categ) > 1)
			{
				for($i=1;$i<strlen($categ);$i++)
				{
					if(ctype_alpha(str_replace(' ', '', $categ[$i])) === false)
						continue;
					if($func == 'edit')
					{
						if($this->mdl_category->exists_by_edit($cat_id, implode($code)))
							$code[1] = $categ[$i];
						else
							break;
					}
					else
					{
						if($this->mdl_category->exists('cat_code', implode($code)))
							$code[1] = $categ[$i];
						else
							break;
					}
				}
				return implode($code);
				
			}else
			{
				return $categ;
			}		
	}

	public function get_subcat_code($cat_id, $subcateg, $func = null, $subcat_id = null)
	{
		
		$subcateg = $this->remove_whitespace($subcateg);
		if(strlen($subcateg) == 3) 
		{
			return $subcateg;
		}
		else
		{
			if(strlen($subcateg) >= 3)
			{
				$code[0] = $subcateg[0];
				$code[1] = $subcateg[1];
				$code[2] = $subcateg[2];
				for($i=3;$i<strlen($subcateg);$i++)
				{	
					
					if(ctype_alpha(str_replace(' ', '', $subcateg[$i])) === false)
						continue;
					
					if($func == 'edit')
					{
						if($this->mdl_subcategory->exists_by_edit($subcat_id, implode($code), $cat_id))
						{
							$code[2] = $subcateg[$i];
						}	
						else
							break;
					}
					else
					{
						if($this->mdl_subcategory->get_by_cat($cat_id, 'subcat_code', implode($code)))
						{
							$code[2] = $subcateg[$i];
						}	
						else
							break;
					}
					
					
					
				}		
				return implode($code);				
			}
		}
	}
	public function get_category($cat_id)
	{
		print json_encode($this->mdl_category->get_category($cat_id));  
	}
	public function get_subcategory($subcat_id)
	{
		print json_encode($this->mdl_subcategory->get_subcategory($subcat_id));  
	}
	public function get_category_names()
	{
		print json_encode($this->mdl_category->get_names());  
	}

}
