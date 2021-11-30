<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class My_Controller extends MX_Controller
{
    private $user_role;
	protected $large_qr_path = "assets/qr/large/";
	protected $small_qr_path = "assets/qr/small/";
	protected $card_qr_path  = "assets/qr/card/";
        protected $rpt_path  = "reports/";

    function __construct() 
    {
        parent::__construct();
		$this->load->model('mdl_user');
        $this->load->model('mdl_data_log');	
$this->load->model('mdl_notification'); // 2019/10/05 Sigrid added
		$this->load->library('user_agent');
		$this->load->library('form_validation');
		$this->load->helper('cookie');
        //ob_start();
		$this->form_validation->CI =& $this;		
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','256M');
		// ini_set('session.cookie_domain', 'localhost');
		ini_set('session.cookie_domain', '.goodbuy-bolinao.com');
		date_default_timezone_set('Asia/Manila');

set_time_limit(0);

    }
	
	//validation
	public function letter_space($str)
	{
		 if(preg_match('/[#$%^&*()+=\-\[\]\,;.\/{}|":<>?~\\\\0-9]+/', $str))//ctype_alpha(str_replace(' ', '', $str)) === false
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} must only contain alphabetic characters.');
			return FALSE;
		}			
		else
		{
			return TRUE;
		}
		
	}
	
	/* numeric, decimal passes */
	public function decimal($var)
	{	
	
		if(empty($var))
		{
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field is required.');
			return FALSE;
		}			
		
		else if(is_numeric($var) === false)
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} must only contain numbers.');
			return FALSE;
		}			
		elseif($var <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} is invalid.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}	
	}
	
	/* digits only, no dots */
	public function integ($var)
	{
		if(!preg_match ("/[^0-9]/", $var))
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} must only contain integers.');
			return FALSE;
		}
		elseif($var <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, '{field} is invalid.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	protected function remove_whitespace($str)
	{
		return preg_replace('/\s+/', '', $str);
	}
    protected function load_view($content)
	{	
		if ($this->is_allowed_access()) 
		{
			
			// 2019/10/05 Sigrid upd start ----------------------->
			//$data['header'] = $this->load->view('vw_admin_head', NULL, TRUE);
			$header_data['notifs'] = $this->mdl_notification->get_notifs();
			$header_data['sku_count'] = $this->mdl_notification->get_count_sku();
			$data['header'] = $this->load->view('vw_admin_head', $header_data, TRUE);
			// 2019/10/05 Sigrid upd end -------------------------<

			$data['footer'] = $this->load->view('vw_admin_footer', NULL, TRUE);
			$data['page_loader'] =  $this->load->view('vw_admin_page_loader', NULL, TRUE);		
			$data['content'] = $content;  	
			$data['is_mobile']=$this->agent->is_mobile();
			$this->load->view('vw_admin_home', $data);	
			//$this->output->cache($this->cache_minutes);
		}
		//$this->load->view('vw_under_maintenance');
	}
	protected function load_cview($content,$css,$js,$header,$footer)
	{	
		$data['css'] = $css;
		$data['js'] = $js;
		$data['largemodal'] = $this->load->view('vw_cust_modal',NULL,TRUE);
		$data['header'] = $header;
		//$data['footer'] = $this->load->view('vw_cust_footer', NULL, TRUE);
		$data['footer'] = $footer;
		$data['page_loader'] =  $this->load->view('vw_cust_page_loader', NULL, TRUE);	
		$data['content'] = $content;  	
		$this->load->view('vw_cust_home', $data);
	}

	protected function load_cview_management($content,$css,$js,$page_title,$header,$footer)
	{	$data['page_title'] = $page_title;
		$data['css'] = $css;
		$data['js'] = $js;
		$data['largemodal'] = $this->load->view('vw_cust_modal',NULL,TRUE);
		$data['header'] = $header;
		//$data['footer'] = $this->load->view('vw_cust_footer', NULL, TRUE);
		$data['footer'] = $footer;
		$data['page_loader'] =  $this->load->view('vw_cust_page_loader', NULL, TRUE);	
		$data['side'] = $this->load->view('vw_cust_side.php',NULL,TRUE);	
		$data['content'] = $content;  	
		$this->load->view('vw_management', $data);	
	}
	protected function load_home($home_view)
	{
		if ($this->mdl_user->verify_user_role($this->get_sess_username(),$this->get_user_role())) 
		{	
			$this->load->view($home_view);
			//$this->log_data("Log in", $this->get_user_role(), $this->get_sess_username(), $this->get_sess_username() . " logged in");
			//redirect(base_url(). "");
		}
		else
		{
			redirect($this->user_role .'/login');
		}
	}
    protected function set_user_role($role)
    {
        $this->user_role= $role;
    }
    
    protected  function get_user_role()
    {
      return $this->user_role;
    }
	protected function get_sess_username($user=null)
    {
if($user == null)
			$user = $this->get_user_role();
		
		if(strcasecmp($user, 'cashier') == 0)
			isset($_SESSION['gb_cashier_username'])? $sess_username = $_SESSION['gb_cashier_username']:$sess_username = null;	
		else
			isset($_SESSION['gb_username'])? $sess_username = $_SESSION['gb_username']:$sess_username = null;	
		return $sess_username;
    }
	
    protected function is_allowed_access($user=null)
	{
		if ($this->mdl_user->verify_user_role($this->get_sess_username($user),$this->get_user_role())) 
		{	
			return true;
		}
		else
		{		
			//show_404();
			redirect($this->get_user_role());
			return false;
		}		
	}
	
	
	protected function log_data($activity, $details, $username = null)
	{
		date_default_timezone_set('Asia/Manila');
		$data['activity'] = $activity;
		$data['role'] = $this->get_user_role();
		if($this->get_sess_username())
			$data['username'] = $this->get_sess_username();
		else 
			$data['username'] = $username;
		
		$data['details'] = $details;
		$data['datetime'] = date('Y-m-d H:i:s');
		if($data['username'])
			$this->mdl_data_log->_insert($data);
	}
	public function logout($redirectPath = null)  
    {  
	
		if(strcasecmp('cashier', $redirectPath) == 0)
		{
			$user='gb_cashier_user_id';
			$username = $_SESSION['gb_cashier_username'];
			unset($_SESSION['gb_cashier_username']);
		}
		else
		{
			$user='gb_user_id';
			$username = $_SESSION['gb_username'];
			unset($_SESSION['gb_username']);
		}
		$this->log_data("User logged out", "Logged out.", $username);
		unset($_SESSION[$user]);
        $this->load->driver('cache');   
        $user_id = array(
            'name'   => $user,
            'value'  => '',
            'expire' => '0',
            'domain' => '.localhost',
            'prefix' => ''
        );
		($this->get_user_role())?$role = $this->get_user_role():$role = "N/A";
		delete_cookie($user_id);               
		// print_r($_SESSION);
		//$this->session->sess_destroy();
        $this->cache->clean();
        ob_clean();
        (empty($redirectPath)) ? redirect(base_url()) : redirect($redirectPath);   
    }
	
	protected function send_email($data)
	{
		$this->load->library('email');
		
		$this->email->from($data['user_email'], $data['your_name']);
		$this->email->to($data['to']);
		/*
		$this->email->cc($data['cc']);
		$this->email->bcc($data['bcc']);
		*/
		$this->email->subject($data['subject']);
		$this->email->message($data['msg']);
		
		$this->email->attach($data['attach']);
		$this->email->send();
			
	}
	
    //https://stackoverflow.com/questions/19218247/codeigniter-image-resize
    protected function single_upload($fileHTMLName, $uploadsSubFolder)//returns filename with extension
    {
       $uploadPath = implode("/", str_split(substr(uniqid(''),-3)));
       $ultimateUploadPath = 'uploads/'.$uploadsSubFolder.'/'. $uploadPath;
       if (!is_dir($uploadPath)) mkdir($ultimateUploadPath, 0777, true);

        $config['upload_path']          = './'. $ultimateUploadPath;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 0;
        $config['max_width']            = 1024000;
        $config['max_height']           = 7680000;
        $config['encrypt_name']           = TRUE;
        
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($fileHTMLName))
        {
            $error = array('error' => $this->upload->display_errors());
           // echo $error['error'];
        } 
        else
        {
            $data['upload_path'] = $ultimateUploadPath.'/'.$this->upload->data('file_name');
            return $data;
        }
    }
    public function multiple_upload($htmlUploadName, $uploadsSubFolder, $type = null)
	{
		$fileCtr = 0;
		if(isset($_FILES[$htmlUploadName])) 
			$fileCtr = count($_FILES[$htmlUploadName]['name']);
	
		$sizes = array(
						array(
							'width' => 349,
							'height' => 230
						), 
						array(
							'width' => 158,
							'height' => 153
						), 
					    array(
							'width' => 90,
							'height' => 70
						)
						);
		
		$data = array();				
		$img_data = array();
		
		$uploadPath = implode("/", str_split(substr(uniqid(''),-1)));
		if($fileCtr != 0)
		{
			for ($c = 0; $c < $fileCtr; $c++)
			{							
				$this->load->library('upload');
				$this->load->library('image_lib'); 
				for($i=0; $i<count($sizes);$i++)
				{
					
					$ultimateUploadPath = 'uploads/'.$uploadsSubFolder.'/'
												. $sizes[$i]['width'] . 'x' 
												. $sizes[$i]['height'] .'/'. $uploadPath;							
					if (!is_dir($ultimateUploadPath)) 
						mkdir($ultimateUploadPath, 0777, true);	
					$_FILES['userfile']['name']     = $_FILES[$htmlUploadName]['name'][$c];
					$_FILES['userfile']['type']     = $_FILES[$htmlUploadName]['type'][$c];
					$_FILES['userfile']['tmp_name'] = $_FILES[$htmlUploadName]['tmp_name'][$c];
					$_FILES['userfile']['error']    = $_FILES[$htmlUploadName]['error'][$c];
					$_FILES['userfile']['size']     = $_FILES[$htmlUploadName]['size'][$c];
							
					$config['upload_path']          =  './'. $ultimateUploadPath;
					$config['allowed_types']        = '*';//gif|jpg|png
					$config['max_size']             = 0;
					$config['max_width']            = 1024000;
					$config['max_height']           = 7680000;
					$config['encrypt_name']         = TRUE;				
							
					$this->upload->initialize($config);
					
					if($htmlUploadName == 'primary_image' )
					{
						$img_data['img_type'] = 'primary';
						if (!$this->upload->do_upload($htmlUploadName))
						{
							$error = array('error' => $this->upload->display_errors());
							echo $error['error'];
						}			
					}
					else if($htmlUploadName == 'other_images' || $htmlUploadName == 'add_other_images')
					{
						$img_data['img_type'] = 'others';
						if (!$this->upload->do_upload())
						{
							$error = array('error' => $this->upload->display_errors());
							echo $error['error'];
						}	
					}	
						$image_data =   $this->upload->data();
						$configer =  array(
										  'image_library'   => 'gd2',
										  'source_image'    =>  $image_data['full_path'],
										  'maintain_ratio'  =>  TRUE,
										  'width'           =>  $sizes[$i]['width'],
										  'height'          =>  $sizes[$i]['height'],
										);
						$this->image_lib->clear();
						$this->image_lib->initialize($configer);
						$this->image_lib->resize();
						$img_data['img_file_path'] = $ultimateUploadPath.'/'.$this->upload->data('file_name');
						$img_data['img_size'] = $sizes[$i]['width'] . 'x' . $sizes[$i]['height'];					
						$data[] = array_push($data, $img_data);	
				
				}				
			}
		}
		else
		{
			echo 'No files Uploaded.';
		}
		return $data;
	}  
    function get($order_by) 
    {
        $this->load->model($this->get_model(),'MY_Model');
        $query = $this->MY_Model->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by)         
    {
        $this->load->model($this->get_model(),'MY_Model');
        $query = $this->MY_Model->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model($this->get_model(),'MY_Model');
        $query = $this->MY_Model->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model($this->get_model(),'MY_Model');
        $query = $this->MY_Model->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model($this->get_model(),'MY_Model');
        $this->MY_Model->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model($this->get_model(),'MY_Model');
        $this->MY_Model->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model($this->get_model(),'MY_Model');
        $this->MY_Model->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model($this->get_model(),'MY_Model');
        $count = $this->MY_Model->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model($this->get_model(),'MY_Model');
        $max_id = $this->MY_Model->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model($this->get_model(),'MY_Model');
        $query = $this->MY_Model->_custom_query($mysql_query);
        return $query;
    }

}