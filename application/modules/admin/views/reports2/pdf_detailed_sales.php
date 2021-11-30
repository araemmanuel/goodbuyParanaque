<link href="<?php echo base_url('assets/css/interface.css');?>" rel="stylesheet" />
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED SALES');
		$pdf->setDateFrom($date_from);
		$pdf->setDateTo($date_to);
        $pdf->Header();
   
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, $pdf->top_margin, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage('P','Letter');
		$pdf->Ln(8);
      
		$tblHeader = <<<EOD
							<style> 
							td{
								text-align:center;
								font-size:11;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:12;
							}	
							.th2{
								background-color:#5c8a8a;
								text-align:right;
								font-weight:bold;
							}
							.num{
								text-align:right;
							}	
							</style>
							<table border="1" cellpadding="3" text-align = "center">    
							<thead>
							   <tr>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>
								<th>Sales Date</th>		
								<th>Invoice</th>					
								<th>Amount</th>
								<th>Discount Incurred</th>		
							   </tr>
							</thead>
        
EOD;
		$colspan = 6;
	  
        //$pdf->SetFont('helvetica', '', 12);
		$total_invoice =  0.00;
		$total_discount = 0.00;
		foreach($detailed_sales as $s) {
			$total_invoice += $s->amt_paid;
			$total_discount+= $s->discount;
			$date = date('m-d-Y', strtotime($s->date));
			$s->amt_paid = number_format($s->amt_paid, 2);
			$s->discount = number_format($s->discount, 2);
			
			if($pdf->getDateFrom() == $pdf->getDateTo()){
				if($s->is_issued_receipt == 0)
				$inv = 'NA';
				else
					$inv = $s->invoice_no;
				
				$tblHeader = <<<EOD
							<style> 
							td{
								text-align:center;
								font-size:11;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:12;
							}	
							.th2{
								background-color:#5c8a8a;
								text-align:right;
								font-weight:bold;
							}
							.num{
								text-align:right;
							}	
							</style>
							<table border="1" cellpadding="3" text-align = "center">    
							<thead>
							   <tr>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>		
								<th>Invoice</th>					
								<th>Amount</th>
								<th>Discount Incurred</th>		
							   </tr>
							</thead>
        
EOD;
				
				$temp = <<<EOD
				<tbody>
				  <tr>
						<td>$s->sku</td>
						<td>$s->name</td>
						<td>$s->options</td>
						<td>$s->qty</td>
						<td>$inv</td>
						<td class = "num">$s->amt_paid</td>   
						<td class = "num">$s->discount</td>    				
				   </tr> 
				
EOD;
			$colspan = 5;
			}
			
			else {
				if($s->is_issued_receipt == 0)
				$inv = 'NA';
				else
					$inv = $s->invoice_no;
				
				$tblHeader = <<<EOD
							<style> 
							td{
								text-align:center;
								font-size:11;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:12;
							}	
							.th2{
								background-color:#5c8a8a;
								text-align:right;
								font-weight:bold;
							}
							.num{
								text-align:right;
							}	
							</style>
							<table border="1" cellpadding="3" text-align = "center">    
							<thead>
							   <tr>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>
								<th>Sales Date</th>		
								<th>Invoice</th>					
								<th>Amount</th>
								<th>Discount Incurred</th>		
							   </tr>
							</thead>
        
EOD;
				
				$temp = <<<EOD
				<tbody>
				  <tr>
						<td>$s->sku</td>
						<td>$s->name</td>
						<td>$s->options</td>
						<td>$s->qty</td>
						<td>$date</td>
						<td>$inv</td>
						<td class = "num">$s->amt_paid</td>   
						<td class = "num">$s->discount</td>    				
				   </tr> 
				
EOD;
			$colspan = 6;
			}
			
			$tblContent =$tblContent . $temp ;
        }			
		
		$total_invoice = number_format($total_invoice, 2);
		$total_discount = number_format($total_discount, 2);
		$total = <<<EOD
				<tr>
					<th class="th2" colspan="$colspan">TOTAL</th>
					<td class = "num">$total_invoice</td>
					<td class = "num">$total_discount</td>
				</tr>
			</tbody>
EOD;
        $tblContent = $tblContent . $total ."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
		if($is_send_email == 1)
			$pdf->Output(FCPATH.'reports/detailed_sales_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf', 'F');
		else
			$pdf->Output('detailed_sales.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



