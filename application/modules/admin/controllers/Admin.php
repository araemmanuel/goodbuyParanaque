<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
        $this->load->model('mdl_user');
		$this->load->model('mdl_return_policy');
		$this->load->model('mdl_min_qty');	
		$this->load->model('mdl_shipping_fee');		
		$this->load->model('mdl_dashboard');
		$this->load->model('mdl_category');
		$this->load->model('mdl_banner');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_data_log');
		$this->load->model('mdl_vat_reg');
		$this->load->dbutil();
		$this->set_user_role("admin");	
		ob_clean();		
		date_default_timezone_set("Asia/Manila");
	}
	public function index()
    {	 
		//$this->load->view('vw_under_maintenance');		
		if ($this->mdl_user->verify_user_role($this->get_sess_username(),$this->get_user_role())) 
		{	
			$this->dashboard();
		}
		else
		{
			redirect('admin/login');
		}
	}
	
	public function repair_x_stock()
	{
		$this->load->model('mdl_report');
		$d = $this->mdl_report->inconsistent_stock();
		var_dump($d);
		
		echo '<table>
			<thead>
				<th>SKU</th>
				<th>RUNNING INVENTORY</th>
				<th>UNIT SOLD</th>
				<th>STOCK</th>
				<th>UPDATE TO ... </th>
			</thead>
			<tbody>';
		foreach ($d as $i)
		{
			echo '<td>'. $i->sku . '</td>';
			echo '<td>'. $i->run_inventory . '</td>';
			echo '<td>'. $i->unit_sold . '</td>';
			echo '<td>'. $i->stock . '</td>';
			echo '<td>'. $i->run_inventory - $i->unit_sold . '</td>';
		}
		echo '</tbody>
			</table>';
		
	}
	
	private function migrate_cat()
	{
		$this->load->model('mdl_category');
		$cat_data = array(
				array("cat_code" => "M", "cat_name" => "MEN'S " ),
				array("cat_code" => "W", "cat_name" => "WOMEN'S " ),
				array("cat_code" => "K", "cat_name" => "KIDS' " ),
				array("cat_code" => "U", "cat_name" => "UNISEX" ),
				array("cat_code" => "T", "cat_name" => "TEST" ),
				array("cat_code" => "H", "cat_name" => "HOUSEHOLD" ),
				array("cat_code" => "P", "cat_name" => "PHONE" ),
				array("cat_code" => "Ki", "cat_name" => "KITCHENWARES" ),
				array("cat_code" => "S", "cat_name" => "SCHOOL SUPPLIES" ),
				array("cat_code" => "A", "cat_name" => "APPLIANCE" ),
				array("cat_code" => "C", "cat_name" => "COMPUTER" )
			);
		foreach($cat_data as $cd)
		{
			foreach($cd as $key => $val)
			{
				//$data[] = $val;  
				$this->mdl_category->_insert();
			}
		}	
	}

	function insert_subcats()
	{
		$this->load->model('mdl_subcategory');
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
				//echo $line;
				if(!$this->mdl_subcategory->exist($key[1], $key[0]) && $key[1] != 0)
					$this->db->query($qry[1]);
			
			echo '<br>';		
		}
	}
	
	/*
		SELECT DISTINCT IL.invoice_no FROM tblinvoice_line IL 
		LEFT JOIN tblproduct P ON P.prod_id = IL.prod_id 
		LEFT JOIN tblproduct_variant PV ON PV.sku = IL.sku 
		LEFT JOIN tblsubcategory SC ON SC.subcat_id = P.subcat_id 
		LEFT JOIN tblcategory C ON C.cat_id = SC.cat_id 
		WHERE C.cat_name = 'ABTEST';
	*/

	public function banner_add()
	{
		
		$this->form_validation->set_rules('banner-img', 'Banner Image', 'trim');
		$this->form_validation->set_rules('banner-name', 'Banner Name', 'trim|required|is_unique[tblbanner.name]');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->banner();		
		}
		else
		{	
		
			$file = $this->single_upload('banner-img', 'banner');
			$data['img_file_path'] = $file['upload_path'];
			$data['name'] = $this->input->post('banner-name');
			$this->log_data("Banner Add", "Added banner ".$data['name'].".");
			if($this->mdl_banner->_insert($data))
				$this->session->set_flashdata('alert_msg','Banner Added Successfully!');	
			redirect('admin/banner');
		}		
	}
	public function banner_edit()
	{
		$ban_id = $this->input->post('banner-id');	
			
		$this->form_validation->set_rules('banner-img', 'Banner Image', 'trim');
		$this->form_validation->set_rules('banner-name', 'Banner Name', 'trim|required|callback_banner_check');
		if ($this->form_validation->run($this) == FALSE) 
		{		
			$this->banner_edit_form($ban_id);		
		}
		else
		{	
			if( file_exists($_FILES['banner-img']['tmp_name']) && is_uploaded_file($_FILES['banner-img']['tmp_name'])) 
			{
				$orig_img_path = $this->mdl_banner->get_col_where('img_file_path', 'ban_id', $ban_id);
				$this->del_pic($orig_img_path);	
				$file = $this->single_upload('banner-img', 'banner');
				$data['img_file_path'] = $file['upload_path'];
			}
			$orig_name = $this->mdl_banner->get_col_where('name', 'ban_id', $ban_id);
			$data['name'] = $this->input->post('banner-name');
			if(strcasecmp($data['name'], $orig_name) != 0)
				$this->log_data("Banner Edit", "Edited banner from ".$orig_name ." to " .$data['name'].".");
			else
				$this->log_data("Banner Edit", "Edited banner ".$data['name'].".");
	
			if($this->mdl_banner->_update('ban_id', $ban_id, $data))
				$this->session->set_flashdata('alert_msg','Banner Edited Successfully!');	
			redirect('admin/banner');
		}	
		
	}
		public function banner_check($s)
		{
			$ban_id = $this->input->post('banner-id');	
			$orig_name = $this->mdl_banner->get_col_where('name', 'ban_id', $ban_id);
			if (strcasecmp($orig_name, $s) != 0 && $this->mdl_banner->exists('name', $s))
			{
				$this->form_validation->set_message(__FUNCTION__, 'Banner name already exists.');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	public function del_pic( $img_path)
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . '/GoodBuy1/' . $img_path;
		if(is_file($path))
			unlink($path);
	}
	public function banner_del($ban_id)
	{
		$img_path = $this->mdl_banner->get_col_where('img_file_path', 'ban_id', $ban_id);
		$path = $_SERVER['DOCUMENT_ROOT'] . '/GoodBuy1/' . $img_path;
		if(is_file($path))
			unlink($path);
		$banner_name = $this->mdl_banner->get_col_where('name', 'ban_id', $ban_id);
		$this->log_data("Banner Delete", "Deleted banner ".$banner_name .".");
			
		if($this->mdl_banner->_delete('ban_id', $ban_id))
			$this->session->set_flashdata('alert_msg','Banner Deleted Successfully!');	
		redirect('admin/banner');	
	}
	public function banner_selected_online($status)
	{
		$success = true;
		$ban_ids = $this->input->post('chk-banner[]');
		
		foreach($ban_ids as $key => $id)
		{
			$data['is_customer_viewable'] = $status;
			if(!$this->mdl_banner->_update('ban_id', $id, $data))
				$success = false;
		}
	
		if($success)
		{
			if($status == 0)
			{
				$this->log_data("Banner Hide Online", "Selected banners to be hidden online.");
				$this->session->set_flashdata('alert_msg','Selected banners are now hidden online.');
			}
			else
			{
				$this->log_data("Banner Show Online", "Selected banners to be shown online.");
				$this->session->set_flashdata('alert_msg','Selected banners are now shown online.');
			}
		}
		redirect('admin/banner');	
	}
	public function banner_show_online($ban_id)
	{
		$banner_name = $this->mdl_banner->get_col_where('name', 'ban_id', $ban_id);
		$this->log_data("Banner Show Online", "Banner ".$banner_name ." was shown online.");		
		$data['is_customer_viewable'] = 1;
		if($this->mdl_banner->_update('ban_id', $ban_id, $data))	
			$this->session->set_flashdata('alert_msg','Banner shown online.');	
		redirect('admin/banner');	
	}	
	public function banner_hide_online($ban_id)
	{
		$banner_name = $this->mdl_banner->get_col_where('name', 'ban_id', $ban_id);
		$this->log_data("Banner Hide Online", "Banner ".$banner_name ." was hidden online.");	
		$data['is_customer_viewable'] = 0;
		if($this->mdl_banner->_update('ban_id', $ban_id, $data))	
			$this->session->set_flashdata('alert_msg','Banner hidden online.');	
		redirect('admin/banner');	
	}
	public function backup_db()
	{	
		// Load the DB utility class
		$backup_name = 'db_goodbuy-'. date("Y-m-d-H-i-s") .'.sql';
	
		$tables = array(	
		
			'tblcategory',
			'tblsubcategory',	
			'tblsupplier',	
			'tbloption_group',
			'tbloption',
		
		
			'tblproduct',
			'tblproduct_variant',
			'tblstock_history',
			'tblproduct_option',
			'tblimage',
			'tblproduct_variant_analytics',

			
			'tbluser',
			'tblcustomer',
			
			'tblexpenses',
			'tblreturn_policy',
			'tbldata_log',
			'tbllocation',
			'tblmin_qty',
			'tblpos',
			'tblshipping_fee',
				
			'tblinvoice',
			'tblinvoice_line',
			'tblorder',
			'tblorder_details',
			'tblpayment_details',
			'tblcourier',
		
			'tblclothing_sizes',
			'tblcountry_sizes',
			'tblbanner',
	
			'tbltransferred_items',
			'tblreturn_transaction',
			'tblreturned_items',
			'tblnon_saleable_items',
			'tblreward_card',
			'tblcard_transaction'
		);
		
		$prefs = array(
        'tables'        => $tables,   		// Array of tables to backup.
		'format'        => 'txt',          	// gzip, zip, txt
        'filename'      => $backup_name,    // File name - NEEDED ONLY WITH ZIP FILES
        'add_insert'    => TRUE,            // Whether to add INSERT data to backup file
        'newline'       => "\n"             // Newline character used in backup file
		);		
		
		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup($prefs);
		if (!is_dir('db/backup')) 
			mkdir('db/backup', 0777, true);
		
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$path = FCPATH."db/backup/";
		write_file($path.$backup_name, $backup);

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download($backup_name, $backup);
		$this->log_data("Database Backed up", "Database backed up.");	
		
		$this->session->set_flashdata('alert_msg','Database Backup Created Successfully!');
		redirect('admin/backup_recovery');	
	}
	
	function restore_db()
	{
		$this->mdl_dashboard->drop_tables();
		if(!empty($_FILES['backup-file']['tmp_name']) && file_exists($_FILES['backup-file']['tmp_name']))
			$backup_file = file_get_contents($_FILES['backup-file']['tmp_name']);
		$string_query = rtrim($backup_file, '\n;' );
		$array_query = explode(';', $backup_file);
		 for($i=0;$i<count($array_query)-1;$i++)
		{
			$this->db->query($array_query[$i]);
		}	
		$this->log_data("Database Restored", "Restored database.");			
		$this->session->set_flashdata('alert_msg','Database Restored Successfully!');
		redirect('admin/backup_recovery');
	}
	//http://www.cumacoder.com/2016/01/backup-restore-database-codeigniter-3.html
	
	function download_mob_app($type)
	{
		$this->load->helper('download');
		$data = file_get_contents(FCPATH.'assets/gemisos-mob-app (1).apk'); 
		$name = 'gemisos-mob-app (1).apk'; 
		force_download($name, $data);
	}
	function download_user_guide()
	{
		$this->load->helper('download');
		$data = file_get_contents(FCPATH.'assets/YB 5.15.docx'); 
		$name = 'YB 5.15.docx'; 
		force_download($name, $data);
	}
	//public functions
	//PAGES
	public function dashboard()
	{
		$this_month = date('m');
		//$this->load->library('GoogleAnalytics');
		//$data['users_ctr'] = $this->googleanalytics->get_total('users');
		//echo $data['users_ctr'];
		$data['items_min_qty_ctr'] = $this->mdl_dashboard->get_items_min_qty();
		$data['todays_sales'] = $this->mdl_dashboard->get_todays_sales();
		$data['todays_store_sales'] = $this->mdl_dashboard->get_todays_store_sales();
		$data['todays_online_sales'] = $this->mdl_dashboard->get_todays_online_sales();
		$data['todays_expenses'] = $this->mdl_dashboard->get_todays_expenses();
		$data['this_months_sales'] = $this->mdl_dashboard->get_month_sales();
		$data['this_months_expenses'] = $this->mdl_dashboard->get_month_expenses();
		$data['pending_deliveries'] = $this->mdl_dashboard->get_pending_deliveries();
		$data['categories'] = $this->mdl_category->get_categories();
		$data['other_categories'] = $this->get_other_categories();
		$content = $this->load->view('vw_admin_dashboard', $data, TRUE);				
		$this->load_view($content);
	}	
	public function profile()
	{
		$content = $this->load->view('vw_admin_profile', NULL, TRUE);		
		$this->load_view($content);
	}
	public function update_return_policy()
	{	
		$policy_id = $this->input->post('modal-policy_id');
		$data['days'] = $this->input->post('modal-days');	
		$this->log_data("Return Policy Edit", "Return policy was edited to ".$data['days'].".");	
		
		if($this->mdl_return_policy->_update('policy_id',$policy_id, $data))
			$this->session->set_flashdata('alert_msg','Return Policy Edited Successfully!');
		redirect('admin/dashboard');
	}
	public function update_min_qty()
	{
		$qty_id = $this->input->post('modal-qty_id');
		$data['min_qty'] = $this->input->post('modal-min_qty');	
		$this->log_data("Min. Quantity Edit", "Minimum quantity was edited to ".$data['min_qty'].".");	
		
		if($this->mdl_min_qty->_update('qty_id', $qty_id, $data))
			$this->session->set_flashdata('alert_msg','Minimum Quantity Edited Successfully!');
		redirect('admin/dashboard');
	}
	public function update_shipping_fee()
	{
		
		$id = $this->input->post('modal-id');
		$data['shipping_fee'] = $this->input->post('modal-shipping_fee');	
		if($this->mdl_shipping_fee->_update('id', $id, $data))
			$this->session->set_flashdata('alert_msg','Shipping Fee Edited Successfully!');
		redirect('admin/dashboard');
	}	
	public function update_vat_reg()
	{
		$id = $this->input->post('modal-id');
		$data['reg_tin'] = $this->input->post('modal-reg_tin');	
		if($this->mdl_vat_reg->_update('id', $id, $data))
			$this->session->set_flashdata('alert_msg','VAT Reg Tin Edited Successfully!');
		redirect('admin/dashboard');
	}	
	public function update_vat_perc()
	{
		$id = $this->input->post('modal-id');
		$data['vat_percent'] = $this->input->post('modal-vat_percent');	
		if($this->mdl_vat_reg->_update('id', $id, $data))
			$this->session->set_flashdata('alert_msg','VAT Percent Edited Successfully!');
		redirect('admin/dashboard');
	}	
	
	public function activity_log()
	{
		$data['data_log'] = $this->mdl_data_log->get_data_log();
		$content = $this->load->view('vw_admin_act_log', $data, TRUE);
		$this->load_view($content);
	}
	public function banner()
	{
		$data['banners'] = $this->mdl_banner->get_banners();
		$content = $this->load->view('vw_admin_banner', $data, TRUE);
		$this->load_view($content);
	}
	public function banner_edit_form($id)
	{
		$data['banner'] = $this->mdl_banner->get_banner($id);
		$content = $this->load->view('vw_admin_edit_banner', $data, TRUE);
		$this->load_view($content);
	}
	public function min_qty($reorder_point = null)
	{
		if($reorder_point == null)
		{			
			if($this->input->post('reorder-point'))
			{
				$data['reorder_point'] = $this->input->post('reorder-point');
				$this->log_data("Set Reorder Point", "Reorder point filter was set to ".$data['reorder_point'].".");	
			}
			else	
				$data['reorder_point'] = $this->mdl_min_qty->get_min_qty2();	
		}
		else
		{
			$data['reorder_point'] = $reorder_point;	
			$this->log_data("Set Reorder Point", "Reorder point was set to $reorder_point.");	
		}
		$data['products'] = $this->mdl_product_variant->get_min_qty_pvs($data['reorder_point']);
		$content = $this->load->view('vw_admin_min_qty',$data, TRUE);
		$this->load_view($content);
	}

	public function backup_recovery()
	{
	
		$data['db_ctr'] = $this->mdl_dashboard->get_table_count();
		$content = $this->load->view('vw_admin_backup_recovery',$data, TRUE);
		$this->load_view($content);
		
	}
	
	//login functions

	public function login()
    { 
		//Check if account is logged in before proceeding to login page
		if ($this->session->userdata('gb_username')) {
			$role = $this->mdl_user->get_col_where('role', 'username', $this->session->userdata('gb_username'));
			if (strcasecmp('admin',$role) == 0) 
				redirect('admin/dashboard');
			else
				show_404();			
		}    	
		//validate login form 
		$this->form_validation->set_rules('login_username', 'Username', 'trim|required');
		$this->form_validation->set_rules('login_password', 'Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) 
		{
			$this->load->view('vw_admin_login');
		}
		else
		{
			$this->process_login();     
			
		}   

    }
    private function process_login()
    {
        $username =  $this->input->post('login_username');
        $password = $this->input->post('login_password'); 
           
        if($this->mdl_user->can_login($username, $password, $this->get_user_role()))  
        {  
		
			$profile = $this->mdl_user->get_col_where('profile_pic_path', 'username', $username);
			if($profile)
			{
				$session_data = array(  
							  'gb_user_id'  => $this->mdl_user->get_col_where('id','username',$username),
							  'gb_username' => $username,
							  'profile' => $this->mdl_user->get_col_where('profile_pic_path','username',$username)
							  );  
			}
			else
			{
				$session_data = array(  
							  'gb_user_id'  => $this->mdl_user->get_col_where('id','username',$username),
							  'gb_username' => $username  
							  );  
			}
			
			$this->session->set_userdata($session_data);  
			$this->log_data("User logged in", "Logged in.");	
        }
        else  
        {  
            $this->session->set_flashdata('login_error', 'Invalid username or password');   
			$this->log_data("Invalid login", "Someone tried to login using the username '$username'.", 'Unknown User');	
        } 
		redirect('admin/login');        
    }
	//Extra funcitons
	public function get_other_categories()
	{
		$cat = $this->mdl_category->get_categories_for_chart();
		$new_cat = array();
		$cat = explode(',', $cat['cat']);
		if(count($cat)%2 == 0)
			array_push($cat, null);		
		for($i=0;$i<count($cat);$i+=2)
		{
			if($i != 0)
			{
				//echo $cat[$i-1] . ' ' . $cat[$i];
				array_push($new_cat, $cat[$i-1] . '-' . $cat[$i]);		
			}
		}
		return $new_cat;
	}
	private function user_is_verified($username, $colVerifyType)
	{
		$user_id = $this->mdl_user->get_col_where('user_id', 'user_username', $username);
		if($this->mdl_user->get_col_where($colVerifyType, 'user_id', $user_id) == 1)
		{	
			return true;
		}
		else
			return false;
	}
	public function get_return_policy()
	{
		print json_encode($this->mdl_return_policy->get_return_policy());
	}
	public function get_min_qty()
	{
		print json_encode($this->mdl_min_qty->get_min_qty());		
	}
	public function get_shipping_fee()
	{
		print json_encode($this->mdl_shipping_fee->get_shipping_fee());		
	}
	public function get_reg_tin()
	{
		print json_encode($this->mdl_vat_reg->get_reg_tin());
	}

	public function get_monthly_sale_chart()
	{
		print json_encode($this->mdl_dashboard->get_monthly_sale_chart());		
	}
	public function get_categories()
	{
		print json_encode($this->mdl_dashboard->get_monthly_sale_chart($this->mdl_category->get_categories()));		
	}
	public function get_categories_for_event_handling()
	{
		print json_encode($this->mdl_category->get_categories_for_event_handling());		
	}
	public function get_cat_chart_data($cat_id)
	{	
		print json_encode($this->mdl_dashboard->get_monthly_sale_by_cat($cat_id));
	}
	public function get_log_data()
	{  
		   $fetch_data = $this->mdl_data_log->make_datatables();  
           $data = array();  
           foreach($fetch_data as $d)  
           {  
                $sub_array = array();  
				
				$sub_array[] = $d->id;
                $sub_array[] = date('M d, Y h:i A', strtotime($d->datetime));
                $sub_array[] = $d->details;
				$sub_array[] = $d->username;
				$sub_array[] = ucfirst($d->role); 
				$data[] = $sub_array;  
           }
		   if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_data_log->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_data_log->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);     
		
	}
}