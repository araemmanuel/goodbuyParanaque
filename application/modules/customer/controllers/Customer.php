<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer extends My_Controller 
{
     //echo edited through notepad++ by Jade//
     public function __construct() 
    {
        parent::__construct(); 
        $this->load->library('encryption');
		$this->load->library('image_lib');		
		$this->load->library('google_analytics',NULL,'GA'); 
        $this->load->model('Mdl_User','Users');
        $this->load->model('Mdl_Product','Products');
        $this->load->model('Mdl_Order','Orders');
		$this->load->model('Mdl_Categories','Categories');
		$this->load->model('Mdl_Customer','Customers');
		$this->load->model('Mdl_Courier','Courier');
		$this->load->model('Mdl_Rewards_Card','Card');
		$this->load->model('Mdl_Invoice','mdl_invoice');
		$this->load->helper("card_generator");
		//$this->set_user_role("customer");			
    }

    public function index()
    {	
		$this->login();
	} 
	/*public function home()
	{
		$data['banners'] = $this->getBanners();
		$content = $this->load->view('vw_cust_main', $data , TRUE);
        $css = $this->load->view('vw_cust_home_css', NULL, TRUE);   
        $js = $this->load->view('vw_cust_home_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}
	
	public function new_home()
	{
		$data['banners'] = $this->getBanners();
		$data['popular_items'] = $this->get_GA_Report_new();
		$data['categ'] = $this->Categories->get_categ_home();
		$data['brand'] = $this->Products->get_product_brands_all();
		$data['prod_per_categ'] = $this->Products->get_data_product_per_categ();
		$data_1['max_price']   = $this->Products->get_max_price_h();
		$data_1['max_price_2'] = $this->Products->get_max_price_h();
		$content = $this->load->view('vw_home_content', $data , TRUE);
        $css = $this->load->view('vw_shop_css', NULL, TRUE);   
        $js = $this->load->view('vw_shop_js', $data_1, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}*/
	
	public function home()
	{
		$data['banners'] = $this->getBanners();
		$data['popular_items'] = $this->get_GA_Report_new();
		$data['categ'] = $this->Categories->get_categ_home();
		$data['brand'] = $this->Products->get_product_brands_all();
		$data['prod_per_categ'] = $this->Products->get_data_product_per_categ();
		$data_1['max_price']   = $this->Products->get_max_price_h();
		$data_1['max_price_2'] = $this->Products->get_max_price_h();
		$content = $this->load->view('vw_home_content', $data , TRUE);
        $css = $this->load->view('vw_shop_css', NULL, TRUE);   
        $js = $this->load->view('vw_shop_js', $data_1, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}
	
	
	public function help()
	{
		$content = $this->load->view('vw_help_content', NULL, TRUE);
        $css = $this->load->view('vw_help_css', NULL, TRUE);   
        $js = $this->load->view('vw_help_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}
	
	public function view_cart()
	{
		//$content = $this->load->view('vw_cart_content', NULL, TRUE);
        $css = $this->load->view('vw_cart_css', NULL, TRUE);   
        $js = $this->load->view('vw_cart_js', NULL, TRUE);
		if(isset($_COOKIE['cartItems'])){
			if($_COOKIE['cartItems'] != "" || $_COOKIE['cartItems'] != NULL)
			{
				$content = $this->load->view('vw_cart_content', NULL, TRUE); 
			}
			else
			{
				$content = $this->load->view('vw_cart_content_else', NULL, TRUE); 
			}
			
		}
		else{
			$content = $this->load->view('vw_cart_content_else', NULL, TRUE); 
		}
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}

    /*Description: Load the page for cancelled items in account management 
      Date       : 2/27/2018*/

    public function view_cancelled()
    {
        $page_title = 'Your Cancellations';
        $content = $this->load->view('vw_cancelled_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_cancelled_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_cancelled_order_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }           
        $this->load_cview($content,$css,$js,$header,$footer);
    }
	
	public function view_voucher($order_code,$order_details,$order_amount)
	{
		
		$config['wm_type'] = 'text';
		
		$config['maintain_ratio'] = TRUE;
		$config['width']         = 800;
		$config['height']       = 600;
		$order_items = 0;
		foreach($order_details as $item_cnt)
		{
			$order_items = intval($item_cnt['quantity']) + $order_items;
		}
		$order_customer = $order_details[0]['customer_name'];
		for($i = 0; $i <= 7; $i++)
		{
			if($i == 0)
			{
				$config['source_image'] = './assets/customer/images/voucher.png';
				$config['wm_font_path'] = './assets/customer/font/bebas.ttf';
				$config['wm_text'] = $order_code;
				$config['wm_font_size'] = '30';
				$config['wm_font_color'] = '00af50';
				$config['wm_vrt_offset'] = '170px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '175px';
			}
			else if($i == 1)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/bebas.ttf';
				$config['wm_text'] = $order_items;
				$config['wm_font_size'] = '30';
				$config['wm_font_color'] = '00af50';
				$config['wm_vrt_offset'] = '90px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '175px';
			}
			else if($i == 2)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/bebas.ttf';
				$config['wm_text'] = 'Php' .number_format($order_amount, 2);
				$config['wm_font_size'] = '30';
				$config['wm_font_color'] = '00af50';
				$config['wm_vrt_offset'] = '20px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '175px';
			}
			else if($i == 3)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/TNR.ttf';
				$config['wm_text'] = $order_customer;
				$config['wm_font_size'] = '10';
				$config['wm_font_color'] = '000';
				$config['wm_vrt_offset'] = '210px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '472px';
			}
			else if($i == 4)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/TNR.ttf';
				$config['wm_text'] = date('M d, Y', strtotime("+7 days"));
				$config['wm_font_size'] = '10';
				$config['wm_font_color'] = '000';
				$config['wm_vrt_offset'] = '170px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '472px';
			}
			else if($i == 5)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/TNR.ttf';
				$config['wm_text'] = $order_code;
				$config['wm_font_size'] = '8';
				$config['wm_font_color'] = '000';
				$config['wm_vrt_offset'] = '90px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '514px';
			}
			else if($i == 6)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/TNR.ttf';
				$config['wm_text'] = $order_items;
				$config['wm_font_size'] = '8';
				$config['wm_font_color'] = '000';
				$config['wm_vrt_offset'] = '90px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '580px';
			}
			else if($i == 7)
			{
				$config['source_image'] = './uploads/resized'.$order_code.'.jpg';
				$config['wm_font_path'] = './assets/customer/font/TNR.ttf';
				$config['wm_text'] = number_format($order_amount, 2);
				$config['wm_font_size'] = '8';
				$config['wm_font_color'] = '000';
				$config['wm_vrt_offset'] = '77px';
				$config['wm_hor_alignment'] = 'left';
				$config['wm_hor_offset'] = '521px';
			}
			$config['new_image'] = './uploads/resized'.$order_code.'.jpg';
			$this->image_lib->initialize($config);
			$this->image_lib->watermark();
		}
		
		echo $this->image_lib->display_errors();
	}
	
	public function print_voucher($order_code)
	{
		$data['img_file'] = $order_code;
		$this->load->view('vw_send_voucher',$data);
	}
    /*Description: Load the page for customer profile in account management 
      Date       : 2/27/2018*/

    public function view_profile()
    {
        $page_title = 'Your Profile';
        $content = $this->load->view('vw_profile_content', NULL, TRUE); 
        $css = $this->load->view('vw_profile_css', NULL, TRUE); 
        $js = $this->load->view('vw_profile_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_profile_content_else', NULL, TRUE); 
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_profile_content', NULL, TRUE); 
        }      
        $this->load_cview_management($content,$css,$js,$page_title,$header,$footer);
    }
	
    public function view_orders()
    {
        $page_title = 'Your Orders';
        $content = $this->load->view('vw_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_order_js', NULL, TRUE);    
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_manage_order_content_else', NULL, TRUE); 
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_order_content', NULL, TRUE); 
        }        
        $this->load_cview_management($content,$css,$js,$page_title,$header,$footer);
    }
    public function manage_order()
    {
        $page_title = 'Order #ORD231';
        $css = $this->load->view('vw_manage_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_manage_order_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_manage_order_content_else', NULL, TRUE); 
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_manage_order_content', NULL, TRUE); 
        }           
        $this->load_cview_management($content,$css,$js,$page_title,$header,$footer);
    }
    public function view_wishlists()
    {
        $page_title = 'Your Wishlist';
        $content = $this->load->view('vw_wishlist_content', NULL, TRUE); 
        $css = $this->load->view('vw_wishlist_css', NULL, TRUE); 
        $js = $this->load->view('vw_wishlist_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);
    }

	
	
	public function view_reward_points()
    {
        $page_title = 'Your Reward Card Points';
        $content = $this->load->view('vw_reward_content', NULL, TRUE); 
        $css = $this->load->view('vw_reward_css', NULL, TRUE); 
        $js = $this->load->view('vw_reward_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview_management($content,$css,$js,$page_title,$header,$footer);
    }
	
    public function manage_wishlist()
    {
        $page_title = 'Wishlist #WIS123';
        $content = $this->load->view('vw_manage_wishlist_content', NULL, TRUE); 
        $css = $this->load->view('vw_manage_wishlist_css', NULL, TRUE); 
        $js = $this->load->view('vw_manage_wishlist_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }           
        $this->load_cview($content,$css,$js,$header,$footer);
    }
	public function forgot_password()
    {
        $content = $this->load->view('vw_forgot_password_content', NULL, TRUE); 
        $css = $this->load->view('vw_forgot_password_css', NULL, TRUE); 
        $js = $this->load->view('vw_forgot_password_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
    public function view_product()
    {
        $content = $this->load->view('vw_product_content', NULL, TRUE); 
        $css = $this->load->view('vw_product_css', NULL, TRUE); 
        $js = $this->load->view('vw_product_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
    public function view_checkout()
    {
        
        $css = $this->load->view('vw_checkout_css', NULL, TRUE); 
        $js = $this->load->view('vw_checkout_js', NULL, TRUE);
		if(isset($_COOKIE['cartItems'])){
			if($_COOKIE['cartItems'] != "" || $_COOKIE['cartItems'] != NULL)
			{
				$content = $this->load->view('vw_checkout_content', NULL, TRUE); 
			}
			else
			{
				$content = $this->load->view('vw_checkout_content_else', NULL, TRUE); 
			}
			
		}
		else{
			$content = $this->load->view('vw_checkout_content_else', NULL, TRUE); 
		}
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
	public function verify_order_function($order_id,$points_gained = NULL,$points_used = NULL)
	{
		$this->Orders->verify_order_function($order_id,$points_gained,$points_used);
		$has_email = $this->db->get_where('tblorder',array('order_no' => $order_id))->row_array();
		$email = $has_email['email'];
		//$this->print_receipt($email,$order_id);
		if($email != '' || $email != NULL)
		{
			$emailConfig = [
				'protocol' => 'smtp', 
				'smtp_host' => 'smtp.googlemail.com', 
				'smtp_port' => 587, 
				'smtp_user' => 'enterprises.goodbuy.ph@gmail.com',
				'mailpath' => 'C:\\xampp\\sendmail',					
				'smtp_pass' => 'gecilie7557', 
				'mailtype' => 'html',
				'starttls' => true,
				'smtp_crypto' =>'tls',
				'smpt_ssl' => 'auto',
				'stream' => [
				'ssl' => [
					'allow_self_signed' => true,
					'verify_peer' => false,
					'verify_peer_name' => false,
				],
				],					
				'charset' => 'iso-8859-1'
			];

				// Set your email information
				$from = [
					'email' => 'somethingnewgemam@gmail.com',
					'name' => 'Goodbuy Boutique'
				];
				$to = array($email);
				$subject = "HERE'S YOUR RECEIPT!";
			  //  $message = 'Type your gmail message here';
			  $is_existing = $this->db->get_where('tblorder',array('order_no' => $order_id))->row_array();
			$is_void = $this->db->get_where('tblinvoice',array('invoice_no' => $is_existing['invoice_no']))->row()->is_void;
			$invoice_no = $is_existing['invoice_no'];
			//$is_void = $this->mdl_invoice->get_col_where('is_void', 'invoice_no', $invoice_no);
			if($is_void == 0)
			{
				//$data['return_policy'] = $this->mdl_return_policy->get_days();
				$data['is_batch_print'] = false;
				$data['details'] = $this->mdl_invoice->get_receipt($invoice_no);
				//$data['lastname'] = $this->mdl_user->get_col_where('lastname', 'username', $this->get_sess_username());
				//$data['firstname'] = $this->mdl_user->get_col_where('firstname', 'username', $this->get_sess_username());
				$data['invoice_no'] = $invoice_no;
				$data['cash'] = $this->mdl_invoice->get_col_where('cash', 'invoice_no', $invoice_no);		
				$message =  $this->load->view('vw_send_receipt', $data,true);	
			}
			else
				$message =  'Sorry transaction was voided no receipt could be generated.';
				//$message =  $this->print_receipt($order_id);
				// Load CodeIgniter Email library
				$this->load->library('email');
				$this->email->initialize($emailConfig);
				// Sometimes you have to set the new line character for better result
				$this->email->set_newline("\r\n");
				// Set email preferences
				$this->email->from($from['email'], $from['name']);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
					if (!$this->email->send()) {
					// Raise error message
					echo 'why?';
					show_error($this->email->print_debugger());
					} else {
						// Show success notification or other things here
						echo json_encode('accepted');
					}
		}

	}
	
	public function search_products($key_words)
	{
		$results = $this->Products->search_products($key_words);
		//echo json_encode($results);
	}
	
	public function search_products_related($key_words,$sku)
	{
		$results = $this->Products->search_products_related($key_words,$sku);
		//echo json_encode($results);
	}
	
	public function search($key_words)
	{
		$content = $this->load->view('vw_search_content', NULL, TRUE); 
        $css = $this->load->view('vw_search_css', NULL, TRUE); 
        $js = $this->load->view('vw_search_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }      
        $this->load_cview($content,$css,$js,$header,$footer);
		
	}
	
	public function verify_checkout($order_id)
    {
		$bool_verified = $this->Orders->is_order_verified($order_id);
		if($bool_verified == true)
		{
			$content = $this->load->view('vw_verify_checkout', NULL, TRUE); 
		}
        else
		{
			$content = $this->load->view('vw_verify_checkout_else', NULL, TRUE); 
		}
        $css = $this->load->view('vw_verify_checkout_css', NULL, TRUE); 
        $js = $this->load->view('vw_verify_checkout_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }      
        $this->load_cview($content,$css,$js,$header,$footer);
    }
	
	public function generateRandomString($length = 16) {
		if($length !== 16){
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		}
		else{
			$characters = '0123456789';
		}
		
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function verify_email_sign_up($email){
		$email_minus = substr($email, 0, -16);
		$email = pack("H*",$email_minus);
		$this->verified_login($email);
		
		$content = $this->load->view('vw_verify_account', NULL, TRUE);
        $css = $this->load->view('vw_help_css', NULL, TRUE);   
        $js = $this->load->view('vw_help_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }   		
		$this->load_cview($content,$css,$js,$header,$footer);
	}
	
	public function send_email_change_password($email,$password)
	{	
		$emailConfig = [
			'protocol' => 'smtp', 
			'smtp_host' => 'smtp.googlemail.com', 
			'smtp_port' => 587, 
			'smtp_user' => 'enterprises.goodbuy.ph@gmail.com',
			'mailpath' => 'C:\\xampp\\sendmail',					
			'smtp_pass' => 'gecilie7557', 
			'mailtype' => 'html',
			'starttls' => true,
			'smtp_crypto' =>'tls',
			'smpt_ssl' => 'auto',
			'stream' => [
			'ssl' => [
				'allow_self_signed' => true,
				'verify_peer' => false,
				'verify_peer_name' => false,
			],
			],					
			'charset' => 'iso-8859-1'
		];

		// Set your email information
		$from = [
			'email' => 'enterprises.goodbuy.ph@gmail.com',
			'name' => 'Goodbuy Enterprises'
		];
		$to = array($email);
		$subject = "FORGOT PASSWORD!";
		$email_hash = bin2hex($email).$this->generateRandomString();
		$message = "<p>Hi, we've noticed that you requested to change your password for our Online Shopping Website, here's your new password: </p><br /><p>***************************</p><p>Password : ".$password."</p><p>***************************</p><br/><p>Too complicated to remember?  Login with your account with your new password and go to your profile.  Change your password with a password that you can always remember.</p>";
		// Load CodeIgniter Email library
		$this->load->library('email');
		$this->email->initialize($emailConfig);
		// Sometimes you have to set the new line character for better result
		$this->email->set_newline("\r\n");
		// Set email preferences
		$this->email->from($from['email'], $from['name']);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if (!$this->email->send()) {
			// Raise error message
			echo 'why?';
			show_error($this->email->print_debugger());
		}
	}
	
	public function send_email_verification($email,$username,$password)
	{	
		$emailConfig = [
			'protocol' => 'smtp', 
			'smtp_host' => 'smtp.googlemail.com', 
			'smtp_port' => 587, 
			'smtp_user' => 'enterprises.goodbuy.ph@gmail.com',
			'mailpath' => 'C:\\xampp\\sendmail',					
			'smtp_pass' => 'gecilie7557', 
			'mailtype' => 'html',
			'starttls' => true,
			'smtp_crypto' =>'tls',
			'smpt_ssl' => 'auto',
			'stream' => [
			'ssl' => [
					'allow_self_signed' => true,
					'verify_peer' => false,
					'verify_peer_name' => false,
				],
			],					
			'charset' => 'iso-8859-1'
		];

		// Set your email information
		$from = [
			'email' => 'enterprises.goodbuy.ph@gmail.com',
			'name' => 'Goodbuy Boutique'
		];
		$to = array($email);
		$subject = "VERIFY YOUR ACCOUNT!";
		$email_hash = bin2hex($email).$this->generateRandomString();
		$message = "<p>Hi, we've noticed that you have signed up for our Online Shopping with the credentials</p><br /><p>***************************</p><p>Username : ".$username."</p><p>Password : ".$password."</p><p>***************************</p><br/><p>Please click the link the below to verify this account you've made: </p><br/><p><a href=".'"'.base_url('customer/verify_email_sign_up').'/'.$email_hash.'"'.">".base_url('customer/verify_email_sign_up/').$email_hash."</a></p>";
		// Load CodeIgniter Email library
		$this->load->library('email');
		$this->email->initialize($emailConfig);
		// Sometimes you have to set the new line character for better result
		$this->email->set_newline("\r\n");
		// Set email preferences
		$this->email->from($from['email'], $from['name']);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if (!$this->email->send()) {
			// Raise error message
			echo 'why?';
			show_error($this->email->print_debugger());
		}
	}
	
	public function track_order()
    {
        $content = $this->load->view('vw_track_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_track_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_track_order_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }      
        $this->load_cview($content,$css,$js,$header,$footer);
    }

    public function view_sign_up()
    {
        
        $css = $this->load->view('vw_sign_up_css', NULL, TRUE); 
        $js = $this->load->view('vw_sign_up_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_sign_up_content', NULL, TRUE); 
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
			$content = $this->load->view('vw_sign_up_else_content',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
	public function register_card()
    {
        $content = $this->load->view('vw_register_card_content', NULL, TRUE); 
        $css = $this->load->view('vw_register_card_css', NULL, TRUE); 
        $js = $this->load->view('vw_register_card_js', NULL, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }            
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
	public function send_email_confirmation($table_id,$points_equivalent = NULL,$points_used = NULL)
	{
		// Set SMTP Configuration
				$emailConfig = [
					'protocol' => 'smtp', 
					'smtp_host' => 'smtp.googlemail.com', 
					'smtp_port' => 587, 
					'smtp_user' => 'enterprises.goodbuy.ph@gmail.com',
					'mailpath' => 'C:\\xampp\\sendmail',					
					'smtp_pass' => 'gecilie7557', 
					'mailtype' => 'html',
					'starttls' => true,
					'smtp_crypto' =>'tls',
					'smpt_ssl' => 'auto',
					'stream' => [
					'ssl' => [
						'allow_self_signed' => true,
						'verify_peer' => false,
						'verify_peer_name' => false,
					],
					],					
					'charset' => 'iso-8859-1'
				];

				// Set your email information
				$from = [
					'email' => 'enterprises.goodbuy.ph@gmail.com',
					'name' => 'Goodbuy Enterprises'
				];
				$to = array($this->input->post('checkout_email'));
				$href_to_verify = $table_id;
				$link_to_verify = base_url('customer/verify_checkout/').$table_id;
				if($points_equivalent != NULL)
				{
					$href_to_verify = $href_to_verify.'/'.$points_equivalent;
					$link_to_verify = $link_to_verify.'/'.$points_equivalent;
				}
				if($points_used != NULL)
				{
					$href_to_verify = $href_to_verify.'/'.$points_used;
					$link_to_verify = $link_to_verify.'/'.$points_used;
				}
				$subject = 'Verify your checkout HERE!';
			  //  $message = 'Type your gmail message here';
				if($this->input->post('payment_method') == 'cod')
				{
					$message =  '<b>Hi! We\'ve noticed that you have currently checked out in our online shopping site.</b><br/><p>Kindly click the link below</p><br/><a href="'.$link_to_verify.'">Click Here!</a><br/><p> We would also like to inform you that you can track your order <a href="'.base_url('customer/track_order/').$table_id.'">here</a></p> Your Order Code is <b>'.$table_id.'</b> ';
				}
				else if($this->input->post('payment_method') == 'pickup')
				{
					$order_details = $this->Orders->get_order_detail_v($table_id);
					$order_amount = $this->Orders->get_order_amount($table_id,'nsf');
					$this->view_voucher($table_id,$order_details,$order_amount);
					$message =  '<b> Hi! We\'ve noticed that you have currently checked out in our online shopping site.</b><Kindly print the image below before claiming your order and present it upong claiming.>'.'<img src="'.base_url().'uploads/resized'.$table_id.'.jpg"><br/><p>Present the stub above when claiming your order.</p><p>Have a nice day!</p>';
					
				}
				// Load CodeIgniter Email library
				$this->load->library('email');
				$this->email->initialize($emailConfig);
				// Sometimes you have to set the new line character for better result
				$this->email->set_newline("\r\n");
				// Set email preferences
				$this->email->from($from['email'], $from['name']);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				// Ready to send email and check whether the email was successfully sent
				if(!$this->input->post('checkout_email'))
				{
					echo 'accepted,'.$href_to_verify;
				}
				else
				{
					if (!$this->email->send()) {
					// Raise error message
					show_error($this->email->print_debugger());
					} else {
						// Show success notification or other things here
						echo 'accepted,'.$href_to_verify;
					}
				}
				
				//echo 'accepted';
	}
	
	public function create_card_for_user()
	{
		$card_no = new Card_Generator();
		$card_num = 0;
			$invalid_card_no = true;
			while($invalid_card_no)
			{
				$card_num = $card_no->single(601, 11);
				if($this->Card->exists('card_no', $card_num))
					$invalid_card_no = true;
				else
					break;
			}
			$registration_date = date('Y-m-d');
			$membership_id = $this->Card->get_membership_id($registration_date);
			$expiration_date = date('Y-m-d', strtotime('+1 year', strtotime($registration_date)));
			echo $this->Card->create_card_for_user($card_num,$membership_id,$registration_date,$expiration_date);
		
	}
	
	public function add_new_order()
	{
		if ($this->input->post('checkout_username') != NULL)
		{
			$table_id = $this->Orders->add_new_order($this->input->post('checkout_username'));
		}
		else
		{
			$table_id = $this->Orders->add_new_order();
		}
		if(is_array($table_id))
		{
			echo json_encode($table_id);
		}
		else
		{
			$this->send_email_confirmation($table_id);
		}
		
				
	}
	
	
	public function add_new_order_with_card_no_points()
	{
		$table_id = $this->Orders->add_new_order_with_card_no_points($this->input->post('checkout_username'));
		$checkout_reward_equivalent = floor($this->input->post('checkout_reward_equivalent')/200);
		if(is_array($table_id))
		{
			echo json_encode($table_id);
		}
		else
		{
			$this->send_email_confirmation($table_id,$checkout_reward_equivalent);
		}
		
				
	}
	
	public function add_new_order_with_card_with_points()
	{
		$table_id = $this->Orders->add_new_order_with_card_with_points($this->input->post('checkout_username'));
		$checkout_rewards_equivalent = floor($this->input->post('checkout_reward_equivalent')/200);
		if(is_array($table_id))
		{
			echo json_encode($table_id);
		}
		else
		{
			$this->send_email_confirmation($table_id,$checkout_rewards_equivalent,$this->input->post('checkout_rewards_used'));
		}		
	}

	public function track_order_func()
	{
        $this->form_validation->set_rules('track_order_order_id', 'Order Code', 'trim|required|numeric');
        $this->form_validation->set_rules('track_order_email', 'Email', 'trim|valid_email');
		if ($this->form_validation->run() == FALSE) 
		{
			$errors = array();
			// Loop through $_POST and get the keys
			foreach ($this->input->post() as $key => $value)
			{
				// Add the error message for this field
				$errors[$key] = form_error($key);
			}
			echo json_encode($errors);
		}
		else
		{
			echo "accepted";
		}
	}
	
	public function track_order_function()
	{
		$result = $this->Orders->get_order_track();
		if($result == '"no result"')
		{
			echo $result;
		}
		else
		{
			echo json_encode($result);
		}
	}
	
    public function place_order()
    {
        $this->form_validation->set_rules('checkout_lname', 'Last Name', 'trim|required|alpha_dash_space|min_length[2]');
		$this->form_validation->set_rules('checkout_mname', 'Middle Name', 'trim|alpha_dash_space|min_length[2]');
        $this->form_validation->set_rules('checkout_fname', 'First Name', 'trim|required|alpha_dash_space|min_length[2]');
        $this->form_validation->set_rules('checkout_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('checkout_phone', 'Phone', 'trim|required|numeric|min_length[9]|max_length[11]');
		if($this->input->post('payment_method') == 'cod')
        {
			$this->form_validation->set_rules('checkout_address', 'House Number, Street, Barangay', 'trim|required');
			$this->form_validation->set_rules('checkout_zipcode', 'Zip Code', 'trim|required|numeric|exact_length[4]');
            if ($this->form_validation->run() == FALSE) 
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                 echo json_encode($errors);
            }
            else
            {
				echo 'accepted';
            }
        }
		else if($this->input->post('payment_method') == 'pickup')
        {
            if ($this->form_validation->run() == FALSE) 
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                 echo json_encode($errors);
            }
            else
            {
				echo 'accepted';
            }
        }
    }
	
	public function getReward()
	{
		$reward_points = $this->Card->getReward();
		echo json_encode($reward_points);
	}

	public function getCategories()
	{
		$categs_subcategs = $this->Categories->get_categ();
		echo json_encode($categs_subcategs);
	}
	
	public function getOptions($prod_id)
	{
		$product_options = $this->Products->getOptions($prod_id);
		echo json_encode($product_options);
	}
	
	public function getProducts_Option($prod_id)
	{
		$has_what = $this->Products->getProducts_Option($prod_id);
		echo json_encode($has_what);
	}
	
    public function shop_categ($query_string)
    {
		$query_string = str_replace('%20', ' ', $query_string);
        $content = $this->load->view('vw_shop_content', NULL, TRUE); 
        $css = $this->load->view('vw_shop_css', NULL, TRUE);
		$data['max_price'] = $this->Products->get_max_price($query_string);
        $js = $this->load->view('vw_shop_js', $data, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }             
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
	public function shop_sub_categ($query_string,$q_string)
    {
		$q_string = str_replace('%20', ' ', $q_string);
        $content = $this->load->view('vw_shop_sub_content', NULL, TRUE); 
        $css = $this->load->view('vw_shop_sub_css', NULL, TRUE); 
		$data['max_price'] = $this->Products->get_max_price_sub($query_string,$q_string);
        $js = $this->load->view('vw_shop_sub_js', $data, TRUE);
        if($this->is_loggedin() == "true")
        {
			$header = $this->load->view('vw_unsigned_header_main',NULL,TRUE);
			$footer = $this->load->view('vw_unsigned_footer_main',NULL,TRUE);
        }
        else
        {
			$data_2['q_name'] = $this->session->userdata('gb_username');
			$header = $this->load->view('vw_signed_header_main',$data_2,TRUE);
			$footer = $this->load->view('vw_signed_footer_main',NULL,TRUE);
        }             
        $this->load_cview($content,$css,$js,$header,$footer);   
    }
	
	public function getBanners()
	{
		//$r = $this->db->select('*')->get_where('tblbanner', array('is_customer_viewable' => '1'));
		//echo json_encode($r->result());
		return $this->db->select('*')->get_where('tblbanner', array('is_customer_viewable' => '1'))->result();
	}
	
	public function get_product_brands($categ)
	{
		$categ = str_replace('%20', ' ', $categ);
		$r = $this->Products->get_product_brands($categ);
		echo json_encode($r);
	}
	
	public function get_product_brands_sub($categ,$subcateg)
	{
		$categ = str_replace('%20', ' ', $categ);
		$subcateg = str_replace('%20', ' ', $subcateg);
		$r = $this->Products->get_product_brands_sub($categ,$subcateg);
		echo json_encode($r);
	}
	
	public function get_sub_categ($categ)
	{
		$r = $this->Categories->get_sub_categ($categ);
		echo json_encode($r);
	}
	
	public function get_option_sizes_with_measures($prod_sku)
	{
		$result = $this->Products->get_option_sizes_with_measures($prod_sku);
		echo json_encode($result);
	}
	
	public function get_stock_on_hand_for_edit ($prod_sku)
	{
		echo $this->Products->get_stock_on_hand_for_edit($prod_sku);
	}
	

    /*Description: The function getProducts() will get all the products from the database
      Date       : 02/28/2018*/
    public function getProducts()
    {
        $products = $this->Products->get_data_product();
        echo json_encode($products);
    }
	
	public function getProducts_Per_Categ()
    {
        $products = $this->Products->get_data_product_per_categ();
        echo json_encode($products);
    }

	public function getStock($sku,$opt_color_id, $opt_size_id = NULL)
	{
		$stock = $this->Products->get_stock($sku,$opt_color_id,$opt_size_id);
		echo json_encode($stock);
	}
	
	public function getStock_Color($sku,$opt_color_id)
	{
		$stock = $this->Products->get_stock_color($sku,$opt_color_id);
		echo json_encode($stock);
	}

    public function getProducts_Categ($category)
    {
		$category = str_replace('%20',' ', $category);
        $products = $this->Products->get_data_product_categ($category);
        echo json_encode($products);
    }
	
	public function getProducts_SubCateg($category,$sub_category)
    {
		$category = str_replace('%20',' ', $category);
		$sub_category = str_replace('%20',' ', $sub_category);
        $products = $this->Products->get_data_product_subcateg($category,$sub_category);
        echo json_encode($products);
    }
	
	public function getProductDetailed($prod_id)
	{
		$product_dets = $this->Products->get_product_detailed($prod_id);
		echo json_encode($product_dets);
	}

    public function getUsername($username_typed)
    {
        $username = $this->Users->verify_sign_up($username_typed);
        echo json_encode($username);
    }

    public function getUsername_validate($username_typed)
    {
        $username = $this->Users->verify_sign_up($username_typed);
        return $username;
    }
	
	public function getEmail_validate($email)
    {
        $username = $this->Users->verify_email($email);
        return $username;
    }
	
	public function getAccount_validate($card_no,$mem_id,$email)
    {
        $result = $this->Users->verify_account_details($card_no,$mem_id,$email);
        return $result;
    }
	
	public function getCard_validate($card_no,$mem_id,$email)
    {
        $result = $this->Users->verify_card_details($card_no,$mem_id,$email);
        return $result;
    }
	
	public function getName()
	{
		echo json_encode($this->session->userdata('gb_username'));
	}
	public function register_validate()
    {
		$this->form_validation->set_rules('register_password', 'Password', 'trim|required|alpha_numeric|min_length[6]');
        $this->form_validation->set_rules('register_confirm_password', 'Confirm Password', 'trim|required|alpha_numeric|min_length[6]');
        $this->form_validation->set_rules('register_card_no', 'Card No.', 'trim|required');
        $this->form_validation->set_rules('register_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('register_membership_i', 'Membership ID', 'trim|required');
        $username = $this->Users->verify_sign_up($this->input->post('register_uname'));
            if ($this->form_validation->run() == FALSE) 
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                 echo json_encode($errors);
            }
            else
            {
                if($this->getCard_validate($this->input->post('register_card_no'),$this->input->post('register_membership_i'),$this->input->post('register_email')) == "false")
				{
					echo 'card_not_accepted';
				}
				else if($this->getAccount_validate($this->input->post('register_card_no'),$this->input->post('register_membership_i'),$this->input->post('register_email')) == "false")
				{
					echo 'card_already_registered';
				}
				else if($this->input->post('register_password') != $this->input->post('register_confirm_password'))
				{
					echo 'password_not_accepted';
				}
                else
                {
                    $this->Users->register_new_account();
                    echo 'accepted';
                }
            }
    }
	
	public function get_GA_Report()
	{
		$views_per_sku = $this->GA->get_views();
		$this->db->trans_start();
		foreach($views_per_sku as $row)
		{
			
			if($this->db->query("SELECT 1 FROM tblproduct_variant_analytics WHERE sku = ? AND month = ? AND year = ?",array($row[0],date('m'),date('Y')))->num_rows() > 0)
			{
				$this->db->set('view_count', $row[1]);
				$this->db->where('sku', $row[0]);
				$this->db->update('tblproduct_variant_analytics');
			}
			else
			{
				$this->db->query("INSERT INTO tblproduct_variant_analytics SELECT prod_id,sku,?,?,? FROM tblproduct_variant WHERE sku = ?",array($row[1],date('m'),date('Y'),$row[0]));
			}
		}
		$this->db->trans_complete();
		echo json_encode($this->Products->get_top_products_of_month());
	}
	
	public function get_GA_Report_new()
	{
		$views_per_sku = $this->GA->get_views();
		$this->db->trans_start();
		foreach($views_per_sku as $row)
		{
			
			if($this->db->query("SELECT 1 FROM tblproduct_variant_analytics WHERE sku = ? AND month = ? AND year = ?",array($row[0],date('m'),date('Y')))->num_rows() > 0)
			{
				$this->db->set('view_count', $row[1]);
				$this->db->where('sku', $row[0]);
				$this->db->update('tblproduct_variant_analytics');
			}
			else
			{
				$this->db->query("INSERT INTO tblproduct_variant_analytics SELECT prod_id,sku,?,?,? FROM tblproduct_variant WHERE sku = ?",array($row[1],date('m'),date('Y'),$row[0]));
			}
		}
		$this->db->trans_complete();
		return $this->Products->get_top_products_of_month();
	}
	
	public function get_GA_Report_new_view()
	{
		$views_per_sku = $this->GA->get_views();
		foreach($views_per_sku as $row)
		{
			echo $row[0];
		}
	}

	public function change_password()
	{
		$this->form_validation->set_rules('sign_up_username', 'Username', 'trim|required');
		$this->form_validation->set_rules('sign_up_email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) 
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                 echo json_encode($errors);
            }
            else
            {
				if($this->Users->account_verified($this->input->post('sign_up_username'),$this->input->post('sign_up_email')) == true)
				{
					echo 'account_not_accepted';
				}
				else
				{
					$password_o_o = $this->generateRandomString(18);
					$this->send_email_change_password($this->input->post('sign_up_email'),$password_o_o);
					$this->Users->change_password_func($this->input->post('sign_up_username'),$password_o_o);
					echo "accepted";
				}
			}
	}
	
    public function sign_up_validate()
    {
        $this->form_validation->set_rules('sign_up_username', 'Username', 'trim|required|alpha_numeric|min_length[6]|pure_numeric|pure_alpha');
		$this->form_validation->set_rules('sign_up_password', 'Password', 'trim|required|alpha_numeric_special_characters|min_length[6]');
		$this->form_validation->set_rules('sign_up_password_c', 'Password', 'trim|required|alpha_numeric_special_characters|min_length[6]');
        $this->form_validation->set_rules('sign_up_lastname', 'Last Name', 'trim|required|alpha_dash_space');
        $this->form_validation->set_rules('sign_up_firstname', 'First Name', 'trim|required|alpha_dash_space');
        $this->form_validation->set_rules('sign_up_gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('sign_up_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('sign_up_phone', 'Phone', 'trim|required|numeric');
        $this->form_validation->set_rules('sign_up_address', 'Address', 'trim|required|min_length[8]');
	$this->form_validation->set_rules('sign_up_state', 'State', 'trim|alpha_dash_space|min_length[3]');
        $this->form_validation->set_rules('sign_up_city', 'City', 'trim|required|alpha|min_length[4]');
		$this->form_validation->set_rules('sign_up_dob', 'Birthday', 'trim|required|legal_age');
        $this->form_validation->set_rules('sign_up_zip', 'Zip Code', 'trim|required|numeric|exact_length[4]');
        $username = $this->Users->verify_sign_up($this->input->post('sign_up_username'));
            if ($this->form_validation->run() == FALSE) 
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                 echo json_encode($errors);
            }
            else
            {
                if($this->getUsername_validate($this->input->post('sign_up_username')) == "true")
                {
                    echo 'username_not_accepted';
                }
				else if($this->getEmail_validate($this->input->post('sign_up_email')) == "true")
				{
					echo 'email_not_accepted';
				}
				else if($this->input->post('sign_up_password') != $this->input->post('sign_up_password_c'))
				{
					echo 'password_not_accepted';
				}
				else if($this->Users->verify_account_details_name_and_bday($this->input->post('sign_up_lastname'),$this->input->post('sign_up_firstname'),$this->input->post('sign_up_middlename'),$this->input->post('sign_up_dob')) == 'false')
				{
					echo 'account_details_not_accepted';
				}
                else
                {
                    $this->Users->create_new_account();
                    echo 'accepted';
					$this->send_email_verification($this->input->post('sign_up_email'),$this->input->post('sign_up_username'),$this->input->post('sign_up_password_c'));
                }
            }
    }

    /*Description: The function of getProductInfo() is to get a certain product information in adding itmes in cart/wishlist
      Date       : 02/28/2018
    */
    public function getProductInfo($prod_id)
    {
        $product_info = $this->Products->get_specific_data_product($prod_id);
        echo json_encode($product_info);
    }

	
	public function get_orders_with_point()
	{
		$orders = $this->Orders->get_orders_with_point($this->session->userdata('gb_username'));
		echo json_encode($orders);
	}
	public function get_orders()
	{
		$orders = $this->Orders->get_orders($this->session->userdata('gb_username'));
		echo json_encode($orders);
	}
	
	public function get_order_detail($order_id)
	{
		$order = $this->Orders->get_order_detail($order_id);
		echo json_encode($order);
	}
	
	public function cancel_item_in_order()
	{
		$q = $this->Orders->cancel_item_in_order($this->session->userdata('gb_username'));
		echo $q;
	}
	public function cancel_item_in_order_track()
	{
		$q = $this->Orders->cancel_item_in_order_track($this->session->userdata('gb_username'));
		echo $q;
	}
	
	public function get_shipping_fee()
	{
		$q = $this->Courier->get_shipping_fee();
		echo $q;
	}
	
    public function login_after($username)
    {
            $session_data = array(  
                              'gb_user_id'  => $this->Users->get_col_where('id','username',$username),
                              'gb_username' => $username  
                              );  
            $this->session->set_userdata($session_data);
            redirect('customer/home');  
    }
	
	
	public function login_after_register($username)
    {
		$gb_user_id = $this->db->query('SELECT user_id FROM tblreward_card WHERE card_no = '.$username)->row()->user_id;
		$uname = $this->db->query('SELECT username FROM tbluser WHERE id = '.$gb_user_id)->row()->username;
            $session_data = array(  
                              'gb_user_id'  => $gb_user_id,
                              'gb_username' => $uname
                              );  
            $this->session->set_userdata($session_data);
		redirect('customer/home');  
    }
	
	public function echo_is_logged()
	{
		if($this->session->userdata('gb_user_id') && $this->session->userdata('gb_username'))
        {
			$user_data = $this->Users->get_user_info($this->session->userdata('gb_username'));
            echo json_encode($user_data);
        }
        else
        {
            echo "false";
        }
	}
	
	public function echo_is_logged_customer()
	{
		if($this->session->userdata('gb_username'))
        {
			$user_data = $this->Customers->get_user($this->session->userdata('gb_username'));
            echo $this->session->userdata('gb_username');
        }
        else
        {
			//echo $this->session->userdata('gb_username');
			//echo $this->session->userdata('gb_user_id');
            echo "false";
        }
	}

    public function is_loggedin()
    {
        if(!$this->session->userdata('gb_user_id') && !$this->session->userdata('gb_username'))
        {
            return "true";
        }
        else
        {
            return "false";
        }
    }
	
	public function getUsername_validate_update($username_typed)
    {
        $username = $this->Users->verify_sign_up_update($username_typed);
        return $username;
    }
	
	public function getEmail_validate_update($email)
    {
        $username = $this->Users->verify_email_update($email);
        return $username;
    }
	
	public function validate_password_update($old_pass)
    {
        $pass = $this->Users->verify_password_update($old_pass);
        return $pass;
    }
	
	public function update_profile_with_password()
	{
		$this->load->helper('security');
		$this->form_validation->set_rules('profile_phone', 'Contact Number', 'trim|required|max_length[11]|numeric');
		$this->form_validation->set_rules('profile_fname', 'First Name', 'trim|required|alpha_dash_space');
		$this->form_validation->set_rules('profile_lname', 'Last Name', 'trim|required|alpha_dash_space');
		$this->form_validation->set_rules('profile_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('profile_address', 'House Number, Street, Barangay', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('profile_city', 'City', 'trim|required|alpha');
	$this->form_validation->set_rules('profile_state', 'State', 'trim|required|alpha_dash_space|min_length[3]');
		$this->form_validation->set_rules('profile_zipcode','Zip Code', 'trim|required|numeric|exact_length[4]');
		$this->form_validation->set_rules('profile_pass_n', 'New Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('profile_pass_o', 'Old Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('profile_uname','Username', 'trim|required|alpha_numeric|min_length[6]');
		if($this->form_validation->run() == FALSE)
		{
			$errors = array();
			// Loop through $_POST and get the keys
			foreach ($this->input->post() as $key => $value)
			{
				$errors[$key] = form_error($key);
			}
			echo json_encode($errors);
		}
		else
		{
			if($this->getUsername_validate_update($this->input->post('profile_uname')) == "true")
			{
				echo 'username_not_accepted';
			}
			else if($this->Users->verify_account_details_name_and_bday($this->input->post('profile_lname'),$this->input->post('profile_fname'),$this->input->post('profile_mname'),$this->input->post('profile_dob'),$this->session->userdata('gb_username')) == 'false')
			{
				echo 'account_details_not_accepted';
			}
			else if($this->getEmail_validate_update($this->input->post('profile_email')) == "true")
			{
				echo 'email_not_accepted';
			}
			else if($this->validate_password_update($this->input->post('profile_pass_o')) == "true")
			{
				echo 'old_password_not_accepted';
			}
			else if($this->input->post('profile_pass_n') != $this->input->post('profile_pass_nc'))
			{
				echo 'password_new_not_match';
			}
			else
			{
				$update = $this->Users->update_profile_with_password();
				echo json_encode($update);	
			}
			
		}
	}
	
	public function update_profile()
	{
		 $this->load->helper('security');
		$this->form_validation->set_rules('profile_phone', 'Contact Number', 'trim|required|max_length[11]|numeric');
		$this->form_validation->set_rules('profile_fname', 'First Name', 'trim|required|alpha_dash_space');
		$this->form_validation->set_rules('profile_lname', 'Last Name', 'trim|required|alpha_dash_space');
		$this->form_validation->set_rules('profile_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('profile_address', 'House Number, Street, Barangay', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('profile_city', 'City', 'trim|required|alpha');
		$this->form_validation->set_rules('profile_state', 'State', 'trim|required|alpha_dash_space|min_length[3]');
		$this->form_validation->set_rules('profile_zipcode','Zip Code', 'trim|required|numeric|exact_length[4]');
		if($this->form_validation->run() == FALSE)
		{
			$errors = array();
			// Loop through $_POST and get the keys
			foreach ($this->input->post() as $key => $value)
			{
				$errors[$key] = form_error($key);
			}
			echo json_encode($errors);
		}
		else
		{
			if($this->getEmail_validate_update($this->input->post('profile_email')) == "true")
			{
				echo 'email_not_accepted';
			}
			else if($this->Users->verify_account_details_name_and_bday($this->input->post('profile_lname'),$this->input->post('profile_fname'),$this->input->post('profile_mname'),$this->input->post('profile_dob'),$this->session->userdata('gb_username')) == 'false')
			{
				echo 'account_details_not_accepted';
			}
			else
			{
				$update = $this->Users->update_profile();
				echo json_encode($update);	
			}
		}
	}
	
	

	public function login()
    {
 		if ($this->session->userdata('gb_username')) {
			$role = $this->Users->get_col_where('role', 'username', $this->session->userdata('gb_username'));
			if (strcasecmp($this->get_user_role(), $role) == 0) 
				redirect('customer/home');
			else
				redirect($role);					
		}      
        $this->form_validation->set_rules('login_username', 'Username', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->home();
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
           
        if($this->Users->can_login($username, $password, 'customer'))  
        {  
		
			$session_data = array(  
							  'gb_user_id'  => $this->Users->get_col_where('id','username',$username),
							  'gb_username' => $username  
							  );  
			$this->session->set_userdata($session_data);			
			echo json_encode("success"); 			
        }
        else  
        {  
            echo json_encode("wrong_unp");  
        }   
    }
	protected function log_data($activity, $details, $username = null)
	{
		date_default_timezone_set('Asia/Manila');
		$data['activity'] = $activity;
		$data['role'] = 'customer';
		if($this->get_sess_username())
		$data['username'] = $this->session->userdata('gb_username');
		$data['details'] = $details;
		$data['datetime'] = date('Y-m-d h:i:sa');
		$this->mdl_data_log->_insert($data);
	}
	public function logout($redirectPath = null)  
    {  
        $this->load->driver('cache');   
        $user_id = array(
            'name'   => 'gb_user_id',
            'value'  => '',
            'expire' => '0',
            'domain' => '.localhost',
            'prefix' => ''
        );
		($this->get_user_role())?$role = $this->get_user_role():$role = "N/A";
		$this->log_data("User logged out", "Logged out.");
		delete_cookie($user_id);               
        $this->session->sess_destroy();
        $this->cache->clean();

        ob_clean();
		
        (empty($redirectPath)) ? redirect(base_url()) : redirect($redirectPath);   
    }
	
	private function verified_login($email)
    {
        $username =  $this->db->query("SELECT username FROM tbluser WHERE email = ?",array($email))->row()->username;
        $password = $this->db->query("SELECT password_hash AS pass_h FROM tbluser WHERE email = ?",array($email))->row()->pass_h; 
           
        if($this->Users->can_login($username, $password, 'customer', 'Verified') == "true_verified")  
        {  
		
			$session_data = array(  
							  'gb_user_id'  => $this->Users->get_col_where('id','username',$username),
							  'gb_username' => $username  
							  );  
			$this->session->set_userdata($session_data);			
        }
        else  
        {  
            echo $this->Users->can_login($username, $password, 'customer', 'Verified');
			echo $password;
        }   
    }
	
}
