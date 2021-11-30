<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Shop_Customer extends My_Controller 
{
     
     public function __construct() 
    {
        parent::__construct();   
        $this->load->model('Mdl_User');
        $this->load->model('Mdl_Product','Products');
        $this->load->model('Mdl_Order','Orders');
        //$this->set_user_role("customer");         
    }

    public function index()
    {   
        $this->login();
    } 
    public function home()
    {
        $content = $this->load->view('vw_cust_main', NULL, TRUE);
        $css = $this->load->view('vw_cust_home_css', NULL, TRUE);   
        $js = $this->load->view('vw_cust_home_js', NULL, TRUE);         
        $this->load_cview($content,$css,$js);
    }

    /*Description: Load the page for cancelled items in account management 
      Date       : 2/27/2018*/

    public function view_cancelled()
    {
        $page_title = 'Your Cancellations';
        $content = $this->load->view('vw_cancelled_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_cancelled_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_cancelled_order_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }


    /*Description: Load the page for customer profile in account management 
      Date       : 2/27/2018*/

    public function view_profile()
    {
        $page_title = 'Your Profile';
        $content = $this->load->view('vw_profile_content', NULL, TRUE); 
        $css = $this->load->view('vw_profile_css', NULL, TRUE); 
        $js = $this->load->view('vw_profile_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }
    public function view_orders()
    {
        $page_title = 'Your Orders';
        $content = $this->load->view('vw_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_order_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }
    public function manage_orders()
    {
        $page_title = 'Order #ORD231';
        $content = $this->load->view('vw_manage_order_content', NULL, TRUE); 
        $css = $this->load->view('vw_manage_order_css', NULL, TRUE); 
        $js = $this->load->view('vw_manage_order_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }
    public function view_wishlists()
    {
        $page_title = 'Your Wishlist';
        $content = $this->load->view('vw_wishlist_content', NULL, TRUE); 
        $css = $this->load->view('vw_wishlist_css', NULL, TRUE); 
        $js = $this->load->view('vw_wishlist_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }

    public function manage_wishlist()
    {
        $page_title = 'Wishlist #WIS123';
        $content = $this->load->view('vw_manage_wishlist_content', NULL, TRUE); 
        $css = $this->load->view('vw_manage_wishlist_css', NULL, TRUE); 
        $js = $this->load->view('vw_manage_wishlist_js', NULL, TRUE);         
        $this->load_cview_management($content,$css,$js,$page_title);
    }
    public function view_product()
    {
        $content = $this->load->view('vw_product_content', NULL, TRUE); 
        $css = $this->load->view('vw_product_css', NULL, TRUE); 
        $js = $this->load->view('vw_product_js', NULL, TRUE);         
        $this->load_cview($content,$css,$js);   
    }
    public function view_checkout()
    {
        $content = $this->load->view('vw_checkout_content', NULL, TRUE); 
        $css = $this->load->view('vw_checkout_css', NULL, TRUE); 
        $js = $this->load->view('vw_checkout_js', NULL, TRUE);         
        $this->load_cview($content,$css,$js);   
    }
    public function place_order()
    {
        $this->form_validation->set_rules('checkout_lname', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('checkout_fname', 'First Name', 'trim|required');
        $this->form_validation->set_rules('checkout_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('checkout_phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('checkout_house', 'House Number', 'trim|required');
        $this->form_validation->set_rules('checkout_street', 'Street Name', 'trim|required');
        $this->form_validation->set_rules('checkout_city_province', 'City|Province', 'trim|required');
        $this->form_validation->set_rules('checkout_zip_code', 'Zip Code', 'trim|required');
        if($this->input->post('payment_method') == 'credit')
        {
            $this->form_validation->set_rules('checkout_card_name', 'Card Name', 'trim|required');
            $this->form_validation->set_rules('checkout_card_number', 'Card Number', 'trim|required');
            $this->form_validation->set_rules('checkout_card_expiration', 'Card Expiration', 'trim|required');
            $this->form_validation->set_rules('checkout_card_zip', 'Card Zip', 'trim|required');
            $this->form_validation->set_rules('checkout_card_cvc', 'CVC | CVV', 'trim|required');
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
                $this->Orders->add_new_order_credit();
                echo 'accepted';
            }
        }
        else if($this->input->post('payment_method') == 'cod')
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
                $this->Orders->add_new_order();
                echo 'accepted';
            }
        }
    }


    /*Description: The function getProducts() will get all the products from the database
      Date       : 02/28/2018*/
    public function getProducts()
    {
        $products = $this->Products->get_data_product();
        echo json_encode($products);
    }

    /*Description: The function of getProductInfo() is to get a certain product information in adding itmes in cart/wishlist
      Date       : 02/28/2018
    */
    public function getProductInfo($prod_id)
    {
        $product_info = $this->Products->get_specific_data_product($prod_id);
        echo json_encode($product_info);
    }


    public function login()
    {
        if ($this->session->userdata('gb_username')) {
            $role = $this->Mdl_User->get_col_where('role', 'username', $this->session->userdata('gb_username'));
            if (strcasecmp($this->get_user_role(), $role) == 0) 
                redirect('customer/home');
            else
                redirect($role);                    
        }      
        $this->form_validation->set_rules('login_username', 'Username', 'trim|required');
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
           
        if($this->Mdl_User->can_login($username, $password, $this->get_user_role()))  
        {  
        
            $session_data = array(  
                              'gb_user_id'  => $this->Mdl_User->get_col_where('id','username',$username),
                              'gb_username' => $username  
                              );  
            $this->session->set_userdata($session_data);  
        }
        else  
        {  
            $this->session->set_flashdata('login_error', 'Invalid username or password');  
        } 
        redirect(base_url());  
    }
    
}
