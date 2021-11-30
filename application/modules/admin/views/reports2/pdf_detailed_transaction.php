
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED TRANSACTION');
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
		$pdf->SetFont('helvetica', '', 14);
		
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
					</style>
					<table border="1" cellpadding="3" text-align = "center">      
					<thead>
					   <tr>
						<th>Invoice Number</th>
						<th>Transaction Date</th>
						<th>Total Amount</th>		
						<th>Total Discount Incurred</th>			
					   </tr>
					</thead>
        
EOD;
		$colspan = 2;
        
		$grand_total_amount = $grand_total_discount = 0;
		foreach($detailed_transaction as $r) {
		
			$grand_total_amount += $r->total_amt;
			$grand_total_discount += $r->total_discount;
			$r->total_amt = number_format($r->total_amt, 2);
			if($pdf->getDateFrom() == $pdf->getDateTo())
			{
				if($r->date)
				$date = date('M d, Y g:iA',strtotime($r->date));
				else
				$date = null; 
			 
				if($r->total_discount)
					$discount = number_format($r->total_discount, 2);
				else
					$discount = number_format(0, 2);	
			
				
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
								</style>
								<table border="1" cellpadding="3" text-align = "center">      
								<thead>
								   <tr>
									<th>Invoice Number</th>
									<th>Total Amount</th>		
									<th>Total Discount Incurred</th>			
								   </tr>
								</thead>
EOD;

				$temp = <<<EOD
						<tbody>
						<tr>
							<td>$r->invoice_no</td>
							<td>$r->total_amt</td>
							<td>$discount</td>           					
						</tr>        
EOD;
			$colspan = 1;
			}
		 
			else
			{
				if($r->date)
				$date = date('M d, Y g:iA',strtotime($r->date));
				else
				$date = null; 
			 
			 if($r->total_discount)
					$discount = number_format($r->total_discount, 2);
				else
					$discount = number_format(0, 2);	
			
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
						</style>
						<table border="1" cellpadding="3" text-align = "center">      
						<thead>
						   <tr>
							<th>Invoice Number</th>
							<th>Transaction Date</th>
							<th>Total Amount</th>		
							<th>Total Discount Incurred</th>			
						   </tr>
						</thead>
        
EOD;

					$temp = <<<EOD
							<tbody>
							  <tr>
								<td>$r->invoice_no</td>
								<td>$date</td>
								<td>$r->total_amt</td>
								<td>$discount</td>           					
							   </tr>        
EOD;
			$colspan = 2;
			}
			$tblContent =$tblContent . $temp ;
        }		
		
		$grand_total_amount = number_format($grand_total_amount, 2);
		$grand_total_discount = number_format($grand_total_discount, 2);
			$total = <<<EOD
           <tr>
				<th class="th2" colspan="$colspan">GRAND TOTAL</th>
				<td>$grand_total_amount</td>
				<td>$grand_total_discount</td>
           </tr>  
		   </tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
		
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_transaction.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



