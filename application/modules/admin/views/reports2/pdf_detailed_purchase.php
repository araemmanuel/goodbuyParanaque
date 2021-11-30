
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED PURCHASE');
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
		
	
		$grand_total_purchase  = $grand_total_selling = 0;	
		foreach($detailed_purchase as $r) {
			$date = date('m-d-Y', strtotime($r->date_added));
			$grand_total_purchase  = $grand_total_purchase + $r->total_purchase;
			$grand_total_selling  = $grand_total_selling + $r->total_selling;
			$r->purchase_price = number_format($r->purchase_price, 2);
			$r->selling_price = number_format($r->selling_price, 2);
			$r->total_purchase = number_format($r->total_purchase, 2);
			$r->total_selling = number_format($r->total_selling, 2);
			
			if($pdf->getDateFrom() == $pdf->getDateTo())
			{
			        $tblHeader = <<<EOD
									<style> 
									td{
										overflow-wrap: break-word;
										word-wrap: break-word;
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
											<th>Product Code</th>
											<th>Product Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Purchase Price</th>
											<th>Selling Price</th>
											<th>Total Purchase Price</th>
											<th>Total Selling Price</th>
										</tr>
									</thead>
EOD;
  	
				
			$temp = <<<EOD
			<tbody>
				<tr>
					<td>$r->sku</td>
					<td>$r->name</td>
					<td>$r->options</td>
					<td>$r->qty</td>
					<td>$r->purchase_price</td>            
					<td>$r->selling_price</td>
					<td>$r->total_purchase</td>
					<td>$r->total_selling</td>					
			   </tr>      
			
EOD;
			$colspan = 6;
			}
			
			else
			{
			   $tblHeader = <<<EOD
									<style>
									td{
										overflow-wrap: break-word;
										word-wrap: break-word;
										text-align:center;
										font-size:11;
									}
									th{
										overflow-wrap: break-word;
										word-wrap: break-word;
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
											<th>Date</th>
											<th>Product Code</th>
											<th>Product Name</th>
											<th>Attributes</th>
											<th>Quantity</th>
											<th>Purchase Price</th>
											<th>Selling Price</th>
											<th>Total Purchase Price</th>
											<th>Total Selling Price</th>
										</tr>
									</thead>
EOD;
 				
				$temp = <<<EOD
				<tbody>
					<tr>
						<td>$date</td>
						<td>$r->sku</td>
						<td>$r->name</td>
						<td>$r->options</td>
						<td>$r->qty</td>
						<td>$r->purchase_price</td>            
						<td>$r->selling_price</td>
						<td>$r->total_purchase</td>
						<td>$r->total_selling</td>					
				   </tr>      
			
EOD;
				$colspan = 7;
			}	
			
			$tblContent =$tblContent . $temp ;
        }
		$grand_total_purchase = number_format($grand_total_purchase, 2);
		$grand_total_selling = number_format($grand_total_selling, 2);
		$str_total = <<<EOD
			<tr>
				<th class="th2" colspan="$colspan">GRAND TOTAL</th>
				<td>$grand_total_purchase</td>
				<td>$grand_total_selling</td>
           </tr>
		   </tbody>
EOD;
        $tblContent =$tblContent.$str_total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
		if($is_send_email == 1)
		{
			$pdf->Output(FCPATH.'reports/detailed_purchase_'.date('M-d-Y', strtotime($date_from)).'-'.date('M-d-Y', strtotime($date_to)).'.pdf', 'F');
		}
		else
			$pdf->Output('detailed_purchase.pdf', 'I');
		
//https://html.com/tables/rowspan-colspan/

?>
