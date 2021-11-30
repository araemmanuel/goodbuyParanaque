<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports extends My_Controller {

	public function __construct() 
    {
        parent::__construct();
		$this->set_user_role("admin");	
		$this->load->library('PDF');		
		$this->load->library('Report');		
		$this->load->model('mdl_report');
		$this->load->model('mdl_category');
		$this->load->model('mdl_location');
		$this->load->model('mdl_product_variant');
	}
	public function index()
    {
		show_404();
		/*
		$data['report_name'] = $this->input->post('rptName');
		$data['date_from'] = $this->input->post('dateFrom');
		$data['date_to'] = $this->input->post('dateTo');
		$content = $this->load->view('vw_admin_reports', $data, TRUE);
		$this->load_view($content);
		*/
	}
	function test()
	{
		echo base_url();
	}
	public function send_rpt_email($filename, $email_to)
	{
		$em_data['user_email'] = 'garcia.sigrid17@gmail.com';
		$em_data['your_name'] = 'GOODBUY ENTERPRISES';
		$em_data['to'] = $email_to; 
		$em_data['subject'] = 'GOODBUY ENTERPRISES REPORT';
		$em_data['msg'] = $filename . ' report was sent.';
		$em_data['attach'] = FCPATH .$this->rpt_path. $filename;
		$this->send_email($em_data);
	}
	public function pdf_today_expenses()
	{
		$this->log_data("Generate report", "Generated today's expenses report.");	
		$data['today_expenses'] = $this->mdl_report->today_expenses();	
		$this->load->view('reports/pdf_today_expenses', $data);		
	}
	public function pdf_today_store_sales()
	{
		$this->log_data("Generate report", "Generated today's store sales report.");	
		$data['today_store_sales'] = $this->mdl_report->today_store_sales();	
		$this->load->view('reports/pdf_today_store_sales', $data);		
	}
	public function pdf_today_online_sales()
	{
		$this->log_data("Generate report", "Generated today's online sales report.");	
		$data['today_online_sales'] = $this->mdl_report->today_online_sales();	
		$this->load->view('reports/pdf_today_online_sales', $data);		
	}	
	public function pdf_pending_deliveries()
	{
		$this->log_data("Generate report", "Generated pending deliveries report.");	
		$data['pending_deliveries'] = $this->mdl_report->pending_deliveries();	
		$this->load->view('reports/pdf_pending_deliveries', $data);		
	}
	public function pdf_this_month_expenses()
	{
		$this->log_data("Generate report", "Generated this month's expenses report.");	
		$data['this_month_expenses'] = $this->mdl_report->this_month_expenses();	
		$this->load->view('reports/pdf_this_month_expenses', $data);		
	}	
	public function pdf_this_month_sales()
	{
		$this->log_data("Generate report", "Generated this month's sales report.");	
		$data['this_month_sales'] = $this->mdl_report->this_month_sales();	
		$this->load->view('reports/pdf_this_month_sales', $data);		
	}	
	public function detailed_purchase()
	{
		$data['date_from'] = $data['date_to'] = $data['cat_name'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($this->input->post('cat-name') == 'ALL')
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $this->input->post('cat-name');
		
		$data['categories'] = $this->mdl_category->get_categories();
		
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}		
		$data['detailed_purchase'] = $this->mdl_report->detailed_purchase($data['date_from'], $data['date_to'], $data['cat_name']);
		$content = $this->load->view('reports/vw_detailed_purchase', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_purchase($date_from, $date_to, $cat_name = null, $srch = null, $is_email = null)
	{
		$this->log_data("Generate report", "Printed detailed purchase report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['is_send_email'] = $is_email;
		
		$cat_name = $this->get_filter($cat_name);
		$data['detailed_purchase'] = $this->mdl_report->detailed_purchase($date_from, $date_to, $cat_name, $srch);	
		$this->load->view('reports/pdf_detailed_purchase', $data);	
	}	
	public function detailed_profit()
	{
		$data['date_from'] = $data['date_to'] = $data['cat_name'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($this->input->post('cat-name') == 'ALL')
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $this->input->post('cat-name');
		
		$data['categories'] = $this->mdl_category->get_categories();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_profit'] = $this->mdl_report->detailed_profit($data['date_from'], $data['date_to'], $data['cat_name']);
		$content = $this->load->view('reports/vw_detailed_profit', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_profit($date_from, $date_to, $cat_name = null, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed profit report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$cat_name = $this->get_filter($cat_name);
		$data['detailed_profit'] = $this->mdl_report->detailed_profit($date_from, $date_to, $cat_name, $srch);	
		$this->load->view('reports/pdf_detailed_profit', $data);	
	}		
	public function detailed_transaction()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_transaction'] = $this->mdl_report->detailed_transactions($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_detailed_transaction', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_transaction($date_from, $date_to, $srch=null)
	{
		$this->log_data("Generate report", "Printed detailed transaction report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		
		$data['detailed_transaction'] = $this->mdl_report->detailed_transactions($date_from, $date_to, $srch);	
		$this->load->view('reports/pdf_detailed_transaction', $data);	
	}	
	public function detailed_sales()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if(!($this->input->post('date-from') && $this->input->post('date-to')))
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		if($this->input->post('cat-name') == 'ALL')
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $this->input->post('cat-name');
		
		if($this->input->post('subcat-name') == 'ALL')
			$data['subcat_name'] = null;
		else
			$data['subcat_name'] = $this->input->post('subcat-name');
		
		$data['categories'] = $this->mdl_category->get_categories();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_sales'] = $this->mdl_report->detailed_sales($data['date_from'], $data['date_to'], $data['cat_name'], $data['subcat_name']);
		$content = $this->load->view('reports/vw_detailed_sales', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_sales($date_from, $date_to, $cat = null, $subcat = null, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed sales report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		
		$cat = $this->get_filter($cat);
		$subcat = $this->get_filter($subcat);
		if(strcasecmp($subcat, 'ALL') == 0 || strcasecmp($subcat, 'null') == 0)
			$subcat = null;
		$data['is_send_email'] = 0;	
		$data['detailed_sales'] = $this->mdl_report->detailed_sales($date_from, $date_to, $cat, $subcat, $srch);	
		$this->load->view('reports/pdf_detailed_sales', $data);	
	}
	public function email_detailed_sales($date_from, $date_to, $cat = null, $subcat = null, $srch = null)
	{
		$this->log_data("Email report to ".$this->input->post('default-email'), "Emailed detailed sales report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		
		$cat = $this->get_filter($cat);
		$subcat = $this->get_filter($subcat);
		if(strcasecmp($subcat, 'ALL') == 0)
			$subcat = null;
		if(strcasecmp($srch, 'null') == 0)
			$srch = null;
			
		$data['is_send_email'] = 1;	
		$data['detailed_sales'] = $this->mdl_report->detailed_sales($date_from, $date_to, $cat, $subcat, $srch);	
		$this->load->view('reports/pdf_detailed_sales', $data);	
		
		$this->session->set_flashdata('alert_msg','Email was sent to '.$this->input->post('default-email'));
		$filename = 'detailed_sales_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf';
		$this->send_rpt_email($filename, $this->input->post('default-email'));
		redirect('admin/reports/detailed_sales');
	}	
	public function email_detailed_purchase($date_from, $date_to, $cat_name = null, $srch = null)
	{
		$this->log_data("Email report to ".$this->input->post('default-email'), "Emailed detailed purchase report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['is_send_email'] = 1;
		
		$cat_name = $this->get_filter($cat_name);
		$data['detailed_purchase'] = $this->mdl_report->detailed_purchase($date_from, $date_to, $cat_name, $srch);	
		
		$this->load->view('reports/pdf_detailed_purchase', $data);

		$this->session->set_flashdata('alert_msg','Email was sent to '.$this->input->post('default-email'));
		$filename = 'detailed_purchase_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf';
		$this->send_rpt_email($filename, $this->input->post('default-email'));
		redirect('admin/reports/detailed_purchase');
			
	}
	public function email_summary_purchase($date_from, $date_to, $cat_name = null, $srch = null)
	{
		$this->log_data("Email report to ".$this->input->post('default-email'), "Emailed summary purchase report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$cat_name = $this->get_filter($cat_name);
		$data['is_send_email'] = 1;	
		$data['summary_purchase'] = $this->mdl_report->summary_purchase($date_from, $date_to, $cat_name, $srch);	
		$this->load->view('reports/pdf_summary_purchase', $data);	
		$this->session->set_flashdata('alert_msg','Email was sent to '.$this->input->post('default-email'));
		$filename = 'summary_purchase_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf';
		$this->send_rpt_email($filename, $this->input->post('default-email'));
		redirect('admin/reports/summary_purchase');	
	}
	public function email_summary_sold($date_from, $date_to, $srch = null)
	{
		$this->log_data("Email report to ".$this->input->post('default-email'), "Emailed summary sold items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['is_send_email'] = 1;	
		$data['summary_sold'] = $this->mdl_report->summary_sold($date_from, $date_to, $srch);	
		$this->load->view('reports/pdf_summary_sold_items', $data);	
		$this->session->set_flashdata('alert_msg','Email was sent to '.$this->input->post('default-email'));
		$filename = 'summary_sold_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf';
		$this->send_rpt_email($filename, $this->input->post('default-email'));
		redirect('admin/reports/summary_sold');	
	}
	public function detailed_inventory()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		
		if($this->input->post('cat-name') == 'ALL')
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $this->input->post('cat-name');
		
		if($this->input->post('subcat-name') == 'ALL')
			$data['subcat_name'] = null;
		else
			$data['subcat_name'] = $this->input->post('subcat-name');
		$data['categories'] = $this->mdl_category->get_categories();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_inventory'] = $this->mdl_report->detailed_inventory($data['date_from'], $data['date_to'], $data['cat_name'], $data['subcat_name']);
		$content = $this->load->view('reports/vw_detailed_inventory_items', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_inventory($date_from, $date_to, $cat = null, $subcat = null, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed inventory report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$cat = $this->get_filter($cat);
		$subcat = $this->get_filter($subcat);
		if(strcasecmp($subcat, 'ALL') == 0 || strcasecmp($subcat, 'null') == 0)
			$subcat = null;
		$data['detailed_inventory'] = $this->mdl_report->detailed_inventory($date_from, $date_to, $cat, $subcat, $srch);	
		$this->load->view('reports/pdf_detailed_inventory_items', $data);	
	}
	public function detailed_non_saleable()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		$cat = $this->input->post('cat-name');
		$subcat = $this->input->post('subcat-name');
		if(strcasecmp($cat, 'ALL') == 0 && strcasecmp($cat, 'null') == 0 )
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $cat;
		
		if(strcasecmp($subcat, 'ALL') == 0 && strcasecmp($subcat, 'null') == 0 )
			$data['subcat_name'] = null;
		else
			$data['subcat_name'] = $subcat;
		
		$data['categories'] = $this->mdl_category->get_categories();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_ns'] = $this->mdl_report->detailed_non_saleable($data['date_from'], $data['date_to'], $data['cat_name'], $data['subcat_name']);
		$content = $this->load->view('reports/vw_detailed_non_saleable', $data, TRUE);
		$this->load_view($content);			
	}	
	public function pdf_detailed_non_saleable($date_from, $date_to, $cat = null, $subcat = null, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed non-saleable items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		if(strcasecmp($cat, 'ALL') == 0 && strcasecmp($cat, 'null') == 0 )
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $cat;
		
		if(strcasecmp($subcat, 'ALL') == 0 && strcasecmp($subcat, 'null') == 0 )
			$data['subcat_name'] = null;
		else
			$data['subcat_name'] = $subcat;
	
		$cat = $this->get_filter($cat);
		$subcat = $this->get_filter($subcat);		
		$data['detailed_ns'] = $this->mdl_report->detailed_non_saleable($data['date_from'], $data['date_to'], $cat, $subcat, $srch);
		$this->load->view('reports/pdf_detailed_non_saleable', $data);	
	}
	
	
	public function detailed_expenses()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_expenses'] = $this->mdl_report->detailed_expenses($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_detailed_expenses', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_expenses($date_from, $date_to, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed expenses report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['detailed_expenses'] = $this->mdl_report->detailed_expenses($date_from, $date_to, $srch);	
		$this->load->view('reports/pdf_detailed_expenses', $data);	
	}
	public function detailed_transferred()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($this->input->post('location') == 'ALL')
			$data['location'] = null;
		else
			$data['location'] = $this->input->post('location');
		
		$data['locations'] = $this->mdl_location->get_locations();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['detailed_transferred'] = $this->mdl_report->detailed_transferred($data['date_from'], $data['date_to'], $data['location']);
		$content = $this->load->view('reports/vw_detailed_transferred_items', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_detailed_transferred($date_from, $date_to, $location=null, $srch = null)
	{
		$this->log_data("Generate report", "Printed detailed transferred report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		if($this->input->post('location') == 'ALL')
			$data['location'] = null;
		else
			$data['location'] = $this->input->post('location');
		$location = $this->get_filter($location);
		$data['detailed_transferred'] = $this->mdl_report->detailed_transferred($date_from, $date_to, $location, $srch);	
		$this->load->view('reports/pdf_detailed_transferred_items', $data);	
	}
	public function summary_purchase()
	{
		$data['date_from'] = $data['date_to'] = $data['cat_name'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['summary_purchase'] = $this->mdl_report->summary_purchase($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_summary_purchase', $data, TRUE);
		$this->load_view($content);	
		
	}		
	public function pdf_summary_purchase($date_from, $date_to, $cat_name = null, $srch = null)
	{
		$this->log_data("Generate report", "Printed summary purchase report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$cat_name = $this->get_filter($cat_name);
		$data['is_send_email'] = 0;	
		$data['summary_purchase'] = $this->mdl_report->summary_purchase($date_from, $date_to, $cat_name, $srch);	
		$this->load->view('reports/pdf_summary_purchase', $data);	
	}
	public function summary_sold()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['summary_sold'] = $this->mdl_report->summary_sold($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_summary_sold_items', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_summary_sold($date_from, $date_to, $srch = null)
	{
		$this->log_data("Generate report", "Printed summary sold items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['is_send_email'] = 0;	
		$data['summary_sold'] = $this->mdl_report->summary_sold($date_from, $date_to, $srch);	
		$this->load->view('reports/pdf_summary_sold_items', $data);	
	}
	public function summary_inventory()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['summary_inventory'] = $this->mdl_report->summary_inventory($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_summary_inventory_items', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_summary_inventory($date_from, $date_to, $srch = null)
	{
		$this->log_data("Generate report", "Printed summary inventory items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['summary_inventory'] = $this->mdl_report->summary_inventory($date_from, $date_to, $srch);	
		$this->load->view('reports/pdf_summary_inventory_items', $data);	
	}
	
	public function summary_transferred()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($this->input->post('location') == 'ALL')
			$data['location'] = null;
		else
			$data['location'] = $this->input->post('location');
		$data['locations'] = $this->mdl_location->get_locations();
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['summary_transferred'] = $this->mdl_report->summary_transferred($data['date_from'], $data['date_to'], $data['location']);
		$content = $this->load->view('reports/vw_summary_transferred_items', $data, TRUE);
		$this->load_view($content);	
		
	}	
	public function pdf_summary_transferred($date_from, $date_to, $location=null, $srch = null)
	{
		$this->log_data("Generate report", "Printed summary transferred items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");	
		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		if($this->input->post('location') == 'ALL')
			$data['location'] = null;
		else
			$data['location'] = $this->input->post('location');
		$location = $this->get_filter($location);
		$data['summary_transferred'] = $this->mdl_report->summary_transferred($date_from, $date_to, $location, $srch);	
		$this->load->view('reports/pdf_summary_transferred_items', $data);	
	}
	public function summary_non_saleable()
	{
		$data['date_from'] = $data['date_to'] = null;
		$data['date_from'] = $this->input->post('date-from');
		$data['date_to'] = $this->input->post('date-to');
		if($data['date_from'] == null && $data['date_to'] == null)
		{
			$data['date_from'] = date('Y-m-d');
			$data['date_to'] = date('Y-m-d');
		}
		$data['summary_ns'] = $this->mdl_report->summary_non_saleable($data['date_from'], $data['date_to']);
		$content = $this->load->view('reports/vw_summary_non_saleable', $data, TRUE);
		$this->load_view($content);	
		
	}
	public function pdf_summary_non_saleable($date_from, $date_to, $srch = null)
	{
		$this->log_data("Generate report", "Printed summary non-saleable items report from ".date('M d, Y', strtotime($date_from))." to ".date('M d, Y', strtotime($date_to)).".");		
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['summary_ns'] = $this->mdl_report->summary_non_saleable($data['date_from'], $data['date_to'], $srch);
		$this->load->view('reports/pdf_summary_non_saleable', $data);	
	}
	public function tally()
	{
		if($this->input->post('cat-name') == 'ALL')
			$data['cat_name'] = null;
		else
			$data['cat_name'] = $this->input->post('cat-name');
		$data['categories'] = $this->mdl_category->get_scanned_cat();
		//$data['tally'] = $this->mdl_report->make_datatables($data['cat_name']);
		$content = $this->load->view('reports/vw_tally', $data, TRUE);
		$this->load_view($content);	
		
	}	
	private function get_filter($cat)
	{
		if (strpos($cat, '--') !== false)
			$cat = substr_replace($cat,"",-2);
		else if(strlen($cat) == 0 || strcasecmp($cat, 'ALL') == 0)
			$cat = null;	
		return $cat;
	}
	public function pdf_tally($cat='--', $srch = null)
	{
		$skus = $this->input->post('chk-tally[]');
		if(isset($skus[0]) && $skus[0])
		{
			$data['tally'] = array();		
			$modified_prod_list = $temp_list = array();
			foreach($skus as $index => $s)
			{
				$sku =  $this->mdl_report->get_tally_by_sku($s);
				foreach($sku as $key => $val)
				{
					$temp_list[$key] = $val;
				}
				array_push($data['tally'], $temp_list);
			}
			$data['is_selected'] = true;
		}
		else
		{
			$cat = $this->get_filter($cat);
			$data['tally'] = $this->mdl_report->tally($cat, $srch);						
		}
		$this->log_data("Generate report", "Printed tally report.");	
		$this->load->view('reports/pdf_tally', $data);	
	}
	public function reset_physical()
	{
		if(!$this->mdl_product_variant->get_col_where('sku', 'is_scanned', 1))
		{
			$this->session->set_flashdata('error_msg','Notice: Physical inventory is empty. Nothing to reset.');
		}
		else
		{
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
		redirect('admin/reports/tally');	
	}	
	function get_products()
	{  
		   $fetch_data = $this->mdl_report->make_datatables();  
           $data = array();  
           foreach($fetch_data as $r)  
           {  
                $sub_array = array();
				$sub_array[] = '<input type="checkbox" id="chk-t-'.$r->sku.'" 		name="chk-tally[]"  value="'.$r->sku.'" 	class="chk-col-green chk-tally" /><label for="chk-t-'.$r->sku.'"></label>';		
				$sub_array[] = $r->cat_name; 				
                $sub_array[] = $r->subcat_name;				
                $sub_array[] = $r->sku;  
				$sub_array[] = $r->name; 				
                $sub_array[] = $r->options;  	
				$sub_array[] = $r->run_inventory;  	
				
				if($r->unit_sold)
					$sub_array[] = $r->unit_sold; 			
				else
					$sub_array[] = 0;
				if($r->qty_transferred)
					$sub_array[] = $r->qty_transferred;
				else
					$sub_array[] = 0;
				$sub_array[] = $r->stock;
				$sub_array[] = $r->scanned_qty;
				$sub_array[] = $r->missing;
				$data[] = $sub_array;  			
           }
			// data-toggle="modal" data-target="#modal-sm-tags" 		   
		   if(isset($_POST["draw"]))
			   $draw =  intval($_POST["draw"]);
		   else
			   $draw = 0;
           $output = array(  
                "draw"            =>     $draw,  
                "recordsTotal"    =>     $this->mdl_report->get_all_data(),  
                "recordsFiltered" =>     $this->mdl_report->get_filtered_data(),  
                "data"            =>     $data  
           );
           echo json_encode($output);  
     }  
	
	
	/*
	public function display($report_name=' ', $date_from='...', $date_to='...', $srch = '')
	{
		$rpt_view_name = $this->choose_rpt_view($report_name);
		$data['report_name'] = $report_name;
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;	
		$data['srch'] = $srch;
		$data = $this->get_rpt_data($data);	
		$this->load->view('reports/'.$rpt_view_name, $data);			
	}	
	private function get_rpt_data($data)
	{
		$data['report_name'] = strtolower($data['report_name']);
		$data['srch'] = '%' . str_replace('%20', ' ', $data['srch']) . '%';
		switch($data['report_name'])
		{
			case 'detailed%20purchase':
				$data['detailed_purchase'] = $this->mdl_report->detailed_purchase($data['date_from'],$data['date_to'], $data['srch']);
				break;
			
			case 'detailed%20profit':
				$data['detailed_profit'] = $this->mdl_report->detailed_profit($data['date_from'],$data['date_to'], $data['srch']);
				break;

			case 'detailed%20transactions':
				$data['detailed_transactions'] = $this->mdl_report->detailed_transactions($data['date_from'],$data['date_to'], $data['srch']);
				break;				
	
			case 'detailed%20sales':
				$data['detailed_sales'] = $this->mdl_report->detailed_sales($data['date_from'],$data['date_to'], $data['srch']);
				break;	
				
			case 'detailed%20inventory%20items':
				$data['detailed_inventory'] = $this->mdl_report->detailed_inventory($data['date_from'],$data['date_to'], $data['srch']);
				break;	
			
			case 'detailed%20expenses':
				$data['detailed_expenses'] = $this->mdl_report->detailed_expenses($data['date_from'],$data['date_to'], $data['srch']);
				break;				
	
			case 'detailed%20transferred%20items':
				$data['detailed_transferred'] = $this->mdl_report->detailed_transferred($data['date_from'],$data['date_to'], $data['srch']);
				break;	
				
			case 'summary%20purchase':
				$data['summary_purchase'] = $this->mdl_report->summary_purchase($data['date_from'],$data['date_to'], $data['srch']);
				break;	
				
			case 'summary%20sold%20items':
				$data['summary_sold'] = $this->mdl_report->summary_sold($data['date_from'],$data['date_to'], $data['srch']);
				break;				
	
			case 'summary%20inventory%20items':
				$data['summary_inventory'] = $this->mdl_report->summary_inventory($data['date_from'],$data['date_to'], $data['srch']);	
				break;	
				
			case 'summary%20transferred%20items':
				$data['summary_transferred'] = $this->mdl_report->summary_transferred($data['date_from'],$data['date_to'], $data['srch']);
				break;	
				
			case 'tally':
				$data['tally'] = $this->mdl_report->tally($data['date_from'],$data['date_to'], $data['srch']);
				break;	
			
			default:
				$data = null;		
		}
		return $data;
	}
	private function choose_rpt_view($report_name)
	{
		$view_name = null;

		if(strcasecmp($report_name, 'detailed%20purchase') == 0)
			$view_name = "vw_detailed_purchase";
		elseif(strcasecmp($report_name, 'detailed%20profit') == 0)
			$view_name = "vw_detailed_profit";
		elseif(strcasecmp($report_name, 'detailed%20transactions') == 0)
			$view_name = "vw_detailed_transaction";
		elseif(strcasecmp($report_name, 'detailed%20sales') == 0)
			$view_name = "vw_detailed_sales";
		elseif(strcasecmp($report_name, 'detailed%20inventory%20items') == 0)
			$view_name = "vw_detailed_inventory_items";
		elseif(strcasecmp($report_name, 'detailed%20expenses') == 0)
			$view_name = "vw_detailed_expenses";
		elseif(strcasecmp($report_name, 'detailed%20transferred%20items') == 0)
			$view_name = "vw_detailed_transferred_items";
		elseif(strcasecmp($report_name, 'summary%20purchase') == 0)
			$view_name = "vw_summary_purchase";
		elseif(strcasecmp($report_name, 'summary%20sold%20items') == 0)
			$view_name = "vw_summary_sold_items";
		elseif(strcasecmp($report_name, 'summary%20inventory%20items') == 0)
			$view_name = "vw_summary_inventory_items";
		elseif(strcasecmp($report_name, 'summary%20transferred%20items') == 0)
			$view_name = "vw_summary_transferred_items";
		elseif(strcasecmp($report_name, 'tally') == 0)
			$view_name = "vw_tally";
		else
			$view_name = "vw_nothing";
		
		return $view_name;
	}
		Purchase
		Profit
		Transaction
		Sales
		Inventory Items
		Expenses
		Transferred Items

		Purchase
		Sold Items
		Inventory Items
		Transferrred Items
	
		if(strcasecmp($data['report_name'], 'detailed%20purchase') == 0)
			$data['detailed_purchase'] = $this->mdl_report->detailed_purchase($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20profit') == 0)
			$data['detailed_profit'] = $this->mdl_report->detailed_profit($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20transactions') == 0)
			$data['detailed_transactions'] = $this->mdl_report->detailed_transactions($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20sales') == 0)
			$data['detailed_sales'] = $this->mdl_report->detailed_sales($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20inventory%20items') == 0)
			$data['detailed_inventory'] =  $this->mdl_report->detailed_inventory($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20expenses') == 0)
			$data['detailed_expenses'] = $this->mdl_report->detailed_expenses($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'detailed%20transferred%20items') == 0)
			$data['detailed_transferred'] = $this->mdl_report->detailed_transferred($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'summary%20purchase') == 0)
			$data['summary_purchase'] = $this->mdl_report->summary_purchase($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'summary%20sold%20items') == 0)
			$data['summary_sold'] = $this->mdl_report->summary_sold($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'summary%20inventory%20items') == 0)
			$data['summary_inventory'] = $this->mdl_report->summary_inventory($data['date_from'],$data['date_to'], $data['srch']);	
		elseif(strcasecmp($data['report_name'], 'summary%20transferred%20items') == 0)
			$data['summary_transferred'] = $this->mdl_report->summary_transferred($data['date_from'],$data['date_to'], $data['srch']);
		elseif(strcasecmp($data['report_name'], 'tally') == 0)
			$data['tally'] = $this->mdl_report->tally($data['date_from'],$data['date_to'], $data['srch']);
		else
			$data = null;
	*/
}
