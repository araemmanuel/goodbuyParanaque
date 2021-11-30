
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED TRANSFERRED ITEMS');
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
								font-size:8;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:10;
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
								<th>Date Transferred</th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>			
							   </tr>
							</thead>
        
EOD;
		$colspan = 4;
		
		$item_ctr = 0;
		foreach($detailed_transferred as $r) {
			$date = date('m-d-Y', strtotime($r->date_transferred));
			$item_ctr = $item_ctr + $r->qty_transferred;
		 
			if($pdf->getDateFrom() == $pdf->getDateTo())
			{
				$tblHeader = <<<EOD
							<style> 
							td{
								text-align:center;
								font-size:8;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:10;
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
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>			
							   </tr>
							</thead>
     
EOD;

				$temp = <<<EOD
						<tbody> 
							<tr>
								<td>$r->sku</td>
								<td>$r->name</td>
								<td>$r->options</td>								
								<td>$r->qty_transferred</td>
							</tr>     
  
EOD;
			$colspan = 3;
			}
			
			else
			{
				$tblHeader = <<<EOD
							<style> 
							td{
								text-align:center;
								font-size:8;
							}
							th{
									text-align:center;
									color:white;
									background-color: #00B050;
									font-weight:bold;
									font-size:10;
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
								<th>Date Transferred</th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Attributes</th>
								<th>Quantity</th>			
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
								<td>$r->qty_transferred</td>
							</tr>     
  
EOD;
			$colspan = 4;
			}	 
			$tblContent =$tblContent . $temp ;
		 }
		$total = <<<EOD
           <tr>
				<th class="th2"  colspan="$colspan" >Total No. of Transferred Items: </th>
				<td>$item_ctr</td>
		   </tr>      
			</tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_transferred_items.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



