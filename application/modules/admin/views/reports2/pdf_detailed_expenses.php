
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED EXPENSES');
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
								text-align:left;
								font-weight:bold;
							}
							</style>
							<table border="1" cellpadding="3" text-align = "center">  
							<thead>		
							   <tr>
								<th>Expense</th>
								<th>Date</th>
								<th>Amount</th>		
							   </tr>
							</thead>
EOD;
		$colspan = 2;
        
		$exp_ctr = $total_exp_amt = 0;
		foreach($detailed_expenses as $r) {
			$date = date('m-d-Y', strtotime($r->exp_date));
			$exp_ctr++;
			$total_exp_amt += $r->exp_amt;
			$r->exp_amt = number_format($r->exp_amt, 2);
			if($pdf->getDateFrom() == $pdf->getDateTo())
			{
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
								<th>Expense</th>
								<th>Amount</th>		
							   </tr>
							</thead>
EOD;

				$temp = <<<EOD
						<tbody>
							<tr>
								<td>$r->exp_desc</td>
								<td>$r->exp_amt</td>				
						   </tr>  		
EOD;
			$colspan = 1;
			}
			
			else
			{
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
								<th>Expense</th>
								<th>Date</th>
								<th>Amount</th>		
							   </tr>
							</thead>
EOD;

				$temp = <<<EOD
						<tbody>
							<tr>
								<td>$r->exp_desc</td>
								<td>$date</td>
								<td>$r->exp_amt</td>				
						   </tr>
EOD;
			$colspan = 2;
			}
			$tblContent =$tblContent . $temp ;
        }		
		$total_exp_amt = number_format($total_exp_amt, 2);
		$total = <<<EOD
			<tr>
				<th colspan="$colspan" class="th2">Total No. of Expenses: $exp_ctr</th>
				<td>$total_exp_amt</td>
			</tr>     
			</tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_expenses.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



