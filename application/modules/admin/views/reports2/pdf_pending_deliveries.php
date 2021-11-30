
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');
		$pdf->setDateFrom('month');
		$pdf->setDateTo('month');
        // set default header data 
		$pdf->setReportName('PENDING DELIVERIES');
		
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
								text-align:left;
								font-weight:bold;
							}
							</style>
							<table border="1" cellpadding="3" text-align = "center">  
							<thead>		
							   <tr>
								<th>Order No.</th>
								<th>Date Ordered</th>
								<th>Name</th>
								<th>Email</th>	
								<th>Shipping Address</th>	
								<th>No. of Items</th>	
							   </tr>
							</thead>
EOD;
		$colspan = 6;
        
		$total_qty = 0;
		foreach($pending_deliveries as $r) {
			$total_qty = $total_qty + $r->qty;
			$date = date('m-d-Y', strtotime($r->order_date));
			if($r->address != 'None')
				$r->address = $r->address2;
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
								text-align:left;
								font-weight:bold;
							}
							</style>
							<table border="1" cellpadding="3" text-align = "center">  
							<thead>		
							   <tr>
								<th>Order No.</th>
								<th>Date Ordered</th>
								<th>Name</th>
								<th>Email</th>	
								<th>Contact No.</th>	
								<th>Shipping Address</th>	
								<th>No. of Items</th>	
							   </tr>
							</thead>
EOD;

				$temp = <<<EOD
						<tbody>
							<tr>
								<td>$r->order_no</td>
								<td>$date</td>
								<td>$r->name</td>
								<td>$r->email</td>
								<td>$r->contact_num</td>
								<td>$r->address</td>
								<td>$r->qty</td>	
						   </tr>
EOD;
			$colspan = 6;
		
			$tblContent =$tblContent . $temp ;
        }		

		$total = <<<EOD
			<tr>
				<th colspan="$colspan" class="th2">TOTAL</th>
				<td>$total_qty</td>
			</tr>     
			</tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_expenses.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



