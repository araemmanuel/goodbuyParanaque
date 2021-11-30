
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');
		$pdf->setDateFrom(date('M. d, Y'));
		$pdf->setDateTo(date('M. d, Y'));
        // set default header data 
		$pdf->setReportName('Today\'s Expenses');
		
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
								<th>Amount</th>
							   </tr>
							</thead>
EOD;
		$colspan = 1;
        
		$total_amt = 0;
		foreach($today_expenses as $r) {
			$total_amt = $total_amt + $r->exp_amt;
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
		
			$tblContent =$tblContent . $temp ;
        }		
		$total_amt = number_format($total_amt, 2);
		$total_discount = number_format($total_discount, 2);
		$total_exp_amt = number_format($total_exp_amt, 2);
		$total = <<<EOD
			<tr>
				<th colspan="$colspan" class="th2">TOTAL</th>
				<td>PHP $total_amt</td>
			</tr>     
			</tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_expenses.pdf', 'I');
//https://html.com/tables/rowspan-colspan/


