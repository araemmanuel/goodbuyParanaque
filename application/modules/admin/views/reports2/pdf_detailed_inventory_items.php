
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED INVENTORY ITEMS');
		if(isset($date_from))
			$pdf->setDateFrom($date_from);
		if(isset($date_to))
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
						</style>
						<table border="1" cellpadding="3" text-align = "center">      
							<thead>
								<tr>
									<th>Date</th>
									<th>Product Code</th>
									<th>Product Name</th>
									<th>Attributes</th>									
									<th>Begin<br>ning Inventory</th>
									<th>Stock on Hand</th>		
									<th>Selling Price</th>
									<th>Total Cost</th>			
								</tr>
							</thead>
EOD;
		$colspan = 7;
		
		$total = 0;
		foreach($detailed_inventory as $i) {
			 $date = date('m-d-Y', strtotime($i->prod_date));
			 $total = $total + $i->total_cost;
			 $i->selling_price = number_format($i->selling_price, 2);
			 $i->total_cost = number_format($i->total_cost, 2);
			 
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
									<th>Begin<br>ning Inventory</th>
									<th>Stock on Hand</th>		
									<th>Selling Price</th>
									<th>Total Cost</th>			
								</tr>
							</thead>        
EOD;

				$temp = <<<EOD
						<tbody>
							<tr>
								<td>$i->sku</td>
								<td>$i->name</td>
								<td>$i->options</td>
								<td>$i->beginning_invent</td>         
								<td>$i->quantity</td>
								<td>$i->selling_price</td> 		
								<td>$i->total_cost</td> 				
							</tr>      
EOD;
			$colspan = 6;
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
									<th>Begin<br>ning Inventory</th>
									<th>Stock on Hand</th>		
									<th>Selling Price</th>
									<th>Total Cost</th>			
								</tr>
							</thead>
EOD;

				$temp = <<<EOD
						<tbody>
							<tr>
								<td>$date</td>
								<td>$i->sku</td>
								<td>$i->name</td>
								<td>$i->options</td>
								<td>$i->beginning_invent</td>         
								<td>$i->quantity</td>
								<td>$i->selling_price</td> 		
								<td>$i->total_cost</td> 				
							</tr>      
EOD;
			$colspan = 7;
			}
			$tblContent =$tblContent . $temp ;
        }	
		$total = number_format($total, 2);
		$total = <<<EOD
			<tr>
				<th class="th2" colspan="$colspan">GRAND TOTAL</th>
				<td>$total</td>
           </tr>
		</tbody>
EOD;
		//$pdf->SetFont('helvetica', '', 12);
        $tblContent =$tblContent.$total ."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_inventory_items.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



