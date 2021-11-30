<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migrate extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->load->model('mdl_subcategory');
		$this->load->model('mdl_category');
		$this->load->model('mdl_product');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_stock_history');
		$this->load->model('mdl_prod_options');
		$this->load->model('mdl_option');
		$this->set_user_role("admin");			
	}
	public function index()
    {	 
		if ($this->mdl_user->verify_user_role($this->get_sess_username(),$this->get_user_role())) 
		{
			
		}
	}
	function insert_product()
	{
		
		$fileData = function() {
			$filename="product.txt";
			
			$file = fopen('C:/Users/Maryloid/Desktop/'.$filename, 'r');

			if (!$file)
				die('file does not exist or cannot be opened');

			while (($line = fgets($file)) !== false) {
				yield $line;
			}

			fclose($file);
		};
		foreach ($fileData() as $line) {			
			$data = explode('##', $line);
			
			$sku = explode('-', $data[5]);
			
			if($this->mdl_product->exists('name', $data[0]))
			{
				$pv_data['prod_id'] = $this->mdl_product->get_col_where('prod_id', 'name', $data[0]);
			}
			else
			{
				$p_data['name'] = $data[0];
				$p_data['description'] = $data[1];
				$p_data['brand'] = $data[2];
				$cat_id = $this->mdl_category->get_col_where('cat_id', 'cat_code', $data[4]);
				$p_data['subcat_id'] = $this->mdl_subcategory->get_subcat_id3($cat_id, $data[3]);
				
				$pv_data['prod_id'] = $this->mdl_product->_insert($p_data);				
			}
			
			$pv_data['sku'] = $data[5];
			$pv_data['quantity'] = 1;
			$pv_data['selling_price'] = $data[6];
			$pv_data['purchase_price'] = $data[7];
			$pv_data['date_added'] = str_replace('\r\n', '', $data[8]);
			$pv_data['is_active'] = 1;
			$this->mdl_product_variant->_insert($pv_data);
			
			$sh_data['date_added'] = $pv_data['date_added'];
			$sh_data['qty'] = 1;
			$sh_data['prod_id'] = $pv_data['prod_id'];
			$sh_data['sku'] = $pv_data['sku'];
			$this->mdl_stock_history->_insert($sh_data);
			
			$opt_id = $this->mdl_option->get_col_where('opt_id','opt_name', 'Unknown');
			$opt_data['prod_id'] = $pv_data['prod_id'];
			$opt_data['sku'] = $pv_data['sku'];
			$opt_data['option_id'] = $opt_id;
			$this->mdl_prod_options->_insert($opt_data);			
		}
		echo 'Products Added Successfully!';
	}
	
	function insert_subcats()
	{
		$fileData = function() {
			$filename="subcat.txt";
			
			$file = fopen('C:/Users/Maryloid/Desktop/'.$filename, 'r');

			if (!$file)
				die('file does not exist or cannot be opened');

			while (($line = fgets($file)) !== false) {
				yield $line;
			}

			fclose($file);
		};
		foreach ($fileData() as $line) {			
				$qry = explode('@', $line);
				$key = explode(',', $qry[0]);
				if(!$this->mdl_subcategory->exist($key[1], $key[0]) && $key[1] != 0)
					$this->db->query($qry[1]);	
		}
	}
}
