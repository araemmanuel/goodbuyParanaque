<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cashier extends My_Controller 
{
     
    public function __construct() 
    {
        parent::__construct();   
        $this->load->model('mdl_user');
		$this->load->model('mdl_product_variant');
		$this->load->model('mdl_pos');
		$this->load->model('mdl_invoice');
		$this->load->model('mdl_return_policy');
		$this->load->model('mdl_order');
		$this->load->model('mdl_non_saleable');
		$this->load->model('mdl_expenses');
		$this->load->model('mdl_data_log');	
		$this->load->model('mdl_vat_reg');	
		$this->load->model('mdl_terminal');	
		$this->load->model('mdl_shift');	
		$this->load->model('mdl_report');	
		$this->set_user_role("cashier");	
		ob_clean();
		date_default_timezone_set("Asia/Manila");	
		
    }
    public function index()
    {      
		//!$this->mdl_pos->pos_has_ended() &&
		if (!$this->mdl_pos->pos_has_ended($this->get_terminal()) && $this->mdl_user->verify_user_role($this->get_sess_username(), $this->get_user_role())) 
		{	
			$this->cashier_mode();
		}
		else
		{
			redirect('cashier/login');
		}
    }
	private function get_user_id()
	{
		if($_SESSION['gb_cashier_user_id'] == null)
			redirect('cashier/login');
		else
			return $_SESSION['gb_cashier_user_id'];
	}
	private function load_cash_view($content)
	{
		if ($this->is_allowed_access('cashier')) 
		{
			$data['content']=$content;
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$data['terminal'] = $this->get_terminal();
			$data['pos_has_started'] = $this->mdl_pos->pos_has_started($this->get_terminal());
			$data['prev_batch_ended'] = (strcasecmp($this->mdl_pos->get_last_session('status', $this->get_terminal()), 'open') != 0);
			
			$data['shift_has_started'] = $this->shift_has('started', $this->get_terminal());
			$data['shift_has_ended'] = $this->shift_has('ended', $this->get_terminal());
			
			$data['is_mobile']=$this->agent->is_mobile();
			/*
			var_dump($data['pos_has_started']);
			echo '<br>';
			var_dump($data['shift_has_started']);
			echo '<br>';
			var_dump($data['shift_has_ended']);
			echo '<br>';			
			*/
			//var_dump(!isset($date_from));
			//var_dump($date_from);
			
			$this->load->view('vw_cashier_home', $data);	
		}
	}


	public function encoder($p=null, $str=null)
	{
		if(strcmp($p, 'laurena-20') == 0 && $str)
		{
			$str = str_replace('%20', ' ', $str);
			echo base64_encode ($str);
		}
		else
			show_404();
		
		$path = "C:\goodbuy\\";
		if (!is_dir($path)) 
			mkdir($path, 0777, true);
		/*
			$myfile = fopen(".txt", "w") or die("Unable to open file!");
			$txt = "John Doe\n";
			fwrite($myfile, $txt);
			fclose($myfile);
		*/
	}


	public function test()
	{
		echo ' <script src="'. base_url('assets/cashier/plugins/jquery/jquery.min.js').'" type="text/javascript"></script>';
		echo '<input type="text" id = "test" value = "NEVER GIVE UP SIGRID!">';
		echo '<script type="text/javascript"> (function($){ $("#test").hide();	'.'}(jQuery)); </script>';
		//echo '<script type="text/javascript"> $(document).ready(function () { document.write("With document ready Jquery Hello World!");})';
		//echo '<script type="text/javascript"> document.write("Without Jquery Hello World!");</script>';
			
	}
	public function start_day()
	{
		
			
			$pos_data['terminal_id'] = $this->get_terminal();			
			if(!isset($_SESSION['gb_cashier_user_id']) || $_SESSION['gb_cashier_user_id'] == null)
			{
				$this->form_validation->set_message(__FUNCTION__,'An error occurred. Please refresh the page.');
				return FALSE;
			}
			else if(!$this->input->post('beginning-cash'))
			{
				$data['start-day'] = 'Beginning cash drawer is required.';  
				$data['error'] = true;  	
				
			}
			else if($this->mdl_pos->pos_has_ended($pos_data['terminal_id']))
			{
				$data['start-day'] = 'Batch already ended for this terminal.';  
				$data['error'] = true;  	
				
			}

			else
			{
				if(strcasecmp($this->mdl_pos->get_last_session('status', $pos_data['terminal_id']), 'close') == 0)
				{
					date_default_timezone_set('Asia/Manila');
					$now = new DateTime();
					$pos_data['start_datetime'] = $now->format('Y-m-d H:i:s');
					$pos_data['status'] = 'OPEN';
					$pos_data['beginning_cash_drawer'] = $this->input->post('beginning-cash');
					$this->mdl_pos->_insert($pos_data);
					$this->session->set_flashdata('alert_msg', 'Day and shift has started successfully!');  
					$this->start_shift('start-day');
					$data['start_day_success'] = true;  						
					
				}
				else 
				{
					$data['refresh'] = 'true';  
					$data['error'] = true;  	
					
				}
			}
					
			print json_encode($data);
			
	}
	public function end_day()
	{
			
		if(!$this->mdl_pos->pos_has_ended($this->get_terminal()))
		{
			//End day
			date_default_timezone_set('Asia/Manila');
			$now = new DateTime();
			$date_today = date('Y-m-d');
			$data = $this->mdl_report->get_open_reading($date_today);
			$data['end_datetime'] = $now->format('Y-m-d H:i:s');
			$data['status'] = 'CLOSE';	
			$sess_id = $this->mdl_pos->get_sess_id($this->get_terminal());
				
			$this->mdl_pos->_update('session_id', $sess_id, $data);

			//End shift
			$this->end_shift('end-day');
			
		}	
		else
			echo "Cannot end day.";
		redirect('cashier/end_day_report');
	
	}	
	public function end_shift($caller = null)
	{
		
		date_default_timezone_set('Asia/Manila');
		$now = new DateTime();
		$shift_data['end_time'] = $now->format('Y-m-d H:i:s');
		$user_id = $this->mdl_user->get_col_where('id', 'username', $this->get_sess_username());
		if($this->mdl_shift->_update('user_id', $user_id, $shift_data))
			$this->session->set_flashdata('alert_msg', 'Shift Ended Successfully!');  
		
		if($caller == null)
			redirect('cashier/cashier_mode');

	}
	public function end_day_report()
	{
		//Load contents
		//$data = $this->set_report_data($this->mdl_report->get_closed_reading(date('Y-m-d'), date('Y-m-d')));
		$sess_id = $this->mdl_pos->get_max_sess_id($this->get_terminal());
		$data = $this->mdl_report->get_eod_report($sess_id);
		$data['date_from'] = $data['date_to'] = $this->mdl_pos->get_last_session('close', $this->get_terminal());
		$data['terminal'] = $this->mdl_terminal->get_col_where('name', 'id',$this->get_terminal());
		$data['cashier'] = $this->mdl_user->get_col_where('firstname', 'id', $this->get_user_id()) . ' ' . $this->mdl_user->get_col_where('lastname', 'id', $this->get_user_id());
		
		$content =  $this->load->view('vw_cashier_close_reading', $data, TRUE);			
		$this->load_cash_view($content);
	}

	public function start_shift($caller = null)
	{
		
			//Check if shift already started with same credentials
			
			if($this->shift_has('started', $this->get_terminal())  && !$this->shift_has('ended', $this->get_terminal()))
				$this->session->set_flashdata('error_msg', 'Cannot start shift. Shift already started.');  					
			else
			{
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$shift_data['start_time'] = $now->format('Y-m-d H:i:s');
				$shift_data['user_id'] = $this->mdl_user->get_col_where('id', 'username', $this->get_sess_username());
				$shift_data['pos_id'] = $this->mdl_pos->get_pos_id($this->get_terminal(), 'OPEN');			
				if($this->mdl_shift->_insert($shift_data))
				$this->session->set_flashdata('alert_msg', 'Shift Started Successfully!');  					
			}
			if($caller == null)
				redirect('cashier/cashier_mode');
		
	}
	private function shift_has($func, $terminal)
	{
		$user_id = $this->mdl_user->get_col_where('id', 'username', $this->get_sess_username());
		$pos_id = $this->mdl_pos->get_pos_id($this->get_terminal(), 'OPEN');	
		if($func == 'started')
			return $this->mdl_shift->shift_has_started($pos_id, $user_id);
		else if($func == 'ended')
			return $this->mdl_shift->shift_has_ended($pos_id, $user_id);
		else
			return false;
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
			$data["sku1"] = form_error("sku");
			$data["qty1"] = form_error("qty");
			$data["reason1"] = form_error("reason");
			$data["error"] = true;	
		}		
		else
		{				
			// Insert to non-saleable items
			$data['sku'] = $this->input->post('sku');
			$data['prod_id'] = $this->mdl_product_variant->get_col_where('prod_id', 'sku', $data['sku']);
			$data['qty'] = $this->input->post('qty');
			$data['description'] = $this->input->post('reason');
			$data['date_added'] = date("Y-m-d");
			//$this->log_data("Non-saleable Item Add", "Added ".$data['qty']." items of ".$data['sku']." to non-saleable stock.");
			if($this->mdl_non_saleable->_insert($data))
				$this->session->set_flashdata('alert_msg','Non-saleable Item Added Successfully!');
			
			//Update product variant stock
			$this->update_qty($data['sku'], $data['qty'], 'add');
			$data["success"] = true;	
		}
		print json_encode($data);
	}
	private function update_qty($sku, $user_qty, $func)
	{	
		$product_qty = $this->mdl_product_variant->get_col_where('quantity', 'sku', $sku);
		if($func == 'add')
		{
			$data['quantity'] = $product_qty - $user_qty;
		}
		$this->mdl_product_variant->_update('sku', $sku, $data);	
	}
	function download_mob_app()
	{
		$this->load->helper('download');
		$data = file_get_contents(FCPATH.'assets/gemisos-mob-app (1).apk'); 
		$name = 'gemisos-mob-app (1).apk'; 
		force_download($name, $data);
	}
	//Pages
	public function cashier_mode($invoice_no=null)
	{
		$data['all_products'] = $this->mdl_product_variant->get_pvs_for_sales_mng();
		$data['terminal'] = $this->mdl_terminal->get_col_where('name', 'id', $this->get_terminal());
		$data['shift_has_started'] = $this->shift_has('started', $this->get_terminal());	
		$data['shift_has_ended'] = $this->shift_has('ended', $this->get_terminal());
		$data['is_mobile']=$this->agent->is_mobile();
		$data['date'] = $this->mdl_pos->get_last_session('date', $this->get_terminal());		
		$_SESSION['date'] = $data['date'];
		if($invoice_no)
		{
			$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
			if($is_void == 0)
			{
				$data['invoice_no'] = $invoice_no;
				$cash = $this->mdl_invoice->get_col_where('cash', 'invoice_no', $invoice_no);		
				$details = $this->mdl_invoice->get_receipt($invoice_no);
				$total_price = 0;
				foreach($details as $o)
				{
					if($o->is_void == 0)
						$total_price = $total_price + ($o->amt_paid);	
				}				
				if($cash>$total_price)
					$data['change'] = abs($cash - $total_price);
				else
					$data['change'] = 0;
			}
			else
				$data['msg'] = 'Transaction was voided.';
		}	
		$content = $this->load->view('vw_cashier_mode', $data, TRUE);		
		$this->load_cash_view($content);
	}
	public function reports($report_type = null)
	{
	
		$pos_date = $this->mdl_pos->get_last_session('open', $this->get_terminal());
		
		$data['open_reading'] = $this->set_report_data($this->mdl_report->get_open_reading($pos_date));
		$data['max_close_date'] = $this->mdl_pos->get_max_close_date($this->get_terminal());		
		if($this->input->post('date-from') && $this->input->post('date-from') <= $data['max_close_date'])
		{
			$data['date_from'] = $this->input->post('date-from');
			
		}
		else
			$data['date_from'] = $data['max_close_date'];
		
		if($this->input->post('date-to') && $this->input->post('date-to') <= $data['max_close_date'])
		{
			$data['date_to'] = $this->input->post('date-to');
			
		}
		else
			$data['date_to'] = $data['max_close_date'];
		
		if( $data['date_from'] && $data['date_to'])
		{
			$data['is_eod'] = false;
			$data['close_reading'] = $this->set_report_data($this->mdl_report->get_closed_reading($data['date_from'], $data['date_to']));	
		}
		$data['report_type'] = $report_type;
		$data['terminals'] = $this->mdl_terminal->get_terminals();
		$data['cashiers'] = $this->mdl_shift->get_cashiers();
		$content = $this->load->view('vw_cashier_reports', $data, TRUE);		
		$this->load_cash_view($content);
	}	
	public function print_open_reading()
	{
		$pos_date = $this->mdl_pos->get_last_session('open', $this->get_terminal());
		$data = $this->set_report_data($this->mdl_report->get_open_reading($pos_date));		
		$data['pos_date'] = $pos_date;
		$data['reg_tin'] = $this->mdl_vat_reg->get_reg_tin2();
		$data['terminal'] = $this->mdl_terminal->get_col_where('name', 'id',$this->get_terminal());
		$data['cashier'] = $this->mdl_user->get_col_where('firstname', 'id', $this->get_user_id()) . ' ' . $this->mdl_user->get_col_where('lastname', 'id', $this->get_user_id());
		
		$this->load->view('vw_cashier_print_x_reading', $data);		
		
	}
	public function print_close_reading($date_from, $date_to)
	{
		$data = $this->set_report_data($this->mdl_report->get_closed_reading($date_from, $date_to));		
	
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;				
		$data['reg_tin'] = $this->mdl_vat_reg->get_reg_tin2();
		$data['terminal'] = $this->mdl_terminal->get_col_where('name', 'id',$this->get_terminal());
		$data['cashier'] = $this->mdl_user->get_col_where('firstname', 'id', $this->get_user_id()) . ' ' . $this->mdl_user->get_col_where('lastname', 'id', $this->get_user_id());
		
		$this->load->view('vw_cashier_print_z_reading', $data);				
	}
	
	
	private function set_report_data($data)
	{
		foreach ($data as $key => $value)
		{
			if(($key == 'begin_inv' || $key == 'end_inv') && $data[$key] == null)
			{
				$data[$key] = 'N/A';
			}
			else if($data[$key] == null)
				$data[$key] = number_format(0, 2, '.', '');
		}
		return $data;
	}

	public function pickup_order()
	{
		$data['orders'] = $this->mdl_order->get_orders();
		$content = $this->load->view('vw_cashier_order_pickup', $data, TRUE);		
		$this->load_cash_view($content);
	}
	public function receipt($invoice_no)
	{
		$this->print_receipt($invoice_no);
		/*
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
		if($is_void == 0)
		{
			$data['return_policy'] = $this->mdl_return_policy->get_days();
			$data['is_batch_print'] = false;
			$data['details'] = $this->mdl_invoice->get_receipt($invoice_no);
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['invoice_no'] = $invoice_no;
			$data['cash'] = $this->mdl_invoice->get_col_where('cash', 'invoice_no', $invoice_no);		
			$data['change'] = $this->mdl_invoice->get_col_where('cash', 'invoice_no', $invoice_no) - $this->mdl_invoice->get_col_where('amt_paid', 'invoice_no', $invoice_no);
			$this->load->view('vw_cashier_print_receipt', $data);	//, TRUE	
			//$this->load_cash_view($content);
		}
		else
			redirect('cashier/cashier_mode');
		*/
	}
	public function print_receipt($invoice_no)
	{
		$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
		if($is_void == 0)
		{
			$data['return_policy'] = $this->mdl_return_policy->get_days();
			$data['is_batch_print'] = false;
			$data['details'] = $this->mdl_invoice->get_receipt($invoice_no);
			$data['reg_tin'] = $this->mdl_vat_reg->get_reg_tin2();
			$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
			$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
			$data['calculated_vat_percent'] = ((double)($this->mdl_vat_reg->get_vat_percent()/100)+1);
			$data['vat_percent'] = $this->mdl_vat_reg->get_vat_percent();
			$data['invoice_no'] = $invoice_no;
			$data['cash'] = $this->mdl_invoice->get_col_where('cash', 'invoice_no', $invoice_no);		
			$this->load->view('vw_cashier_print_receipt', $data);	
		}
		else
			echo 'Sorry transaction was voided no receipt could be generated.';
	}
	public function print_daily_sales()
	{
		//Load contents
		$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
		$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());	
		$data['pos_details'] = $this->mdl_pos->get_pos_details();
		$data['sales_amt'] = $this->mdl_pos->get_item_sales();
		$data['void_amt'] = $this->mdl_pos->get_void_transactions();
		$data['payout_amt'] = $this->mdl_expenses->get_total_payout();
		$data['discount_amt'] = $this->mdl_pos->get_discounts();
		$data['void_count'] = $this->mdl_pos->get_void_count();
		$data['hrs'] = $this->mdl_pos->get_hours();
		$this->load->view('vw_cashier_print_daily_sales', $data);
	}
	//Other Functions
	public function login()
    {
		/*
		if($this->mdl_pos->pos_has_ended())
		{
			$data['error'] = 'Batch for today has already ended.';
			$this->load->view('vw_cashier_login', $data);
		}
		else
		{*/	
			//Check if account is logged in before proceeding to login page
			if ($this->session->userdata('gb_cashier_username')) {
				$role = $this->mdl_user->get_col_where('role', 'username', $this->session->userdata('gb_cashier_username'));
				if (strcasecmp('cashier',$role) == 0) 
					redirect('cashier/cashier_mode');
				else
					show_404();					
			}
			//validate login form 
			$this->form_validation->set_rules('login_username', 'Username', 'trim|required');
			$this->form_validation->set_rules('login_password', 'Password', 'trim|required');
			if ($this->form_validation->run() == FALSE) // If username and password is invalid
			{
				$this->load->view('vw_cashier_login');
			}
			else
			{
				/*if($this->mdl_pos->pos_has_started())
					$this->process_login(); 
				else
				{
					*/
				$this->process_login(); 
				//}
			}  
		//}               
    }
    private function process_login()
    {
        $username =  $this->input->post('login_username');
        $password = $this->input->post('login_password'); 
           
        if($this->mdl_user->can_login($username, $password, $this->get_user_role()))  
        {  
		
			$session_data = array(  
							  'gb_cashier_user_id'  => $this->mdl_user->get_col_where('id','username',$username),
							  'gb_cashier_username' => $username  
							  );  
			$this->session->set_userdata($session_data);  
        }
        else  
        {  
            $this->session->set_flashdata('login_error', 'Invalid username or password');  
        } 
		redirect('cashier/login');          
    }
	public function sessiontimeout()
	{

        $lastActivity = $this->session->userdata('last_activity');
        $configtimeout = $this->config->item("sess_expiration");
        $sessonExpireson = $lastActivity+$configtimeout;


        $threshold = $sessonExpireson - 300; //five minutes before session time out

        $current_time = time();

        if($current_time>=$threshold){
            $this->session->set_userdata('last_activity', time()); //THIS LINE DOES THE TRICK
            echo "Session Re-registered";
        }else{
            echo "Not yet time to re-register";
        }

        exit;
    }
	private function get_terminal()
	{
		/*
		$fileData = function() {
			$path = "C:\goodbuy\\";
			$filename="gb-pos.txt";
			
			$file = fopen($path.$filename, 'r');

			if (!$file)
				die('file does not exist or cannot be opened');

			while (($line = fgets($file)) !== false) {
				yield $line;
			}

			fclose($file);
		};
		foreach ($fileData() as $line) {					
			return $this->mdl_terminal->get_col_where('id', 'name', base64_decode ($line));
		}*/
		return $this->mdl_terminal->get_col_where('id', 'name', 'Test Terminal');
	}
	public function get_pv_info()
	{
		$sku = $this->input->post('ajax_sku');
		print json_encode($this->mdl_product_variant->get_pv_info($sku));
	}
	
	public function get_terminals()
	{
		print json_encode($this->mdl_terminal->get_terminals());
	}
	//Only gets cashiers that has a recorded shift
	public function get_cashiers()
	{
		print json_encode($this->mdl_shift->get_cashiers());
	}
	
}
