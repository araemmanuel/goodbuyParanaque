
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetFont('helvetica', '', 14);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('TALLY');
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
        $pdf->AddPage('L','Letter');
		$pdf->Ln(8);
		//	UNIT TRANSFERRED		PHYSICAL INVENTORY	MISSING			
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
            <th>Category</th>
            <th>Sub<br>category</th>
            <th>Product Code</th>		
			<th>Product Name</th>
			<th>Attributes</th>			
			<th>Running Inventory</th>		
			<th>Unit Sold</th>				
			<th>Unit Transferred</th>		
			<th>Stock on Hand</th>
			<th>Physical Inventory</th>				
           <th>Missing</th>				
		   </tr>
		</thead>
EOD;
		
		if(isset($is_selected) && $is_selected)
		{
			foreach($tally as $p)
			{
				$ctr = 0;
				foreach($p as $r)
				{
					if(!$r->unit_sold)
						$r->unit_sold  = 0;
					if(!$r->qty_transferred)
						$r->qty_transferred  = 0;
				
					$temp = <<<EOD
				  <tbody>
				  <tr>
						<td>$r->cat_name</td>
						<td>$r->subcat_name</td>
						<td>$r->sku</td>		
						<td>$r->name</td>
						<td>$r->options</td>
						<td>$r->run_inventory</td>
						<td>$r->unit_sold</td>
						<td>$r->qty_transferred</td>
						<td>$r->stock</td>
						<td>$r->scanned_qty</td>
						<td>$r->missing</td>
				   </tr> 
EOD;
					$tblContent =$tblContent . $temp ;					
				}	
			}
		}
		else
		{
			foreach($tally as $r) 
			{
				if(!$r->unit_sold)
					$r->unit_sold  = 0;
				if(!$r->qty_transferred)
					$r->qty_transferred  = 0;
				
				 $temp = <<<EOD
				  <tbody>
				  <tr>
						<td>$r->cat_name</td>
						<td>$r->subcat_name</td>
						<td>$r->sku</td>		
						<td>$r->name</td>
						<td>$r->options</td>
						<td>$r->run_inventory</td>
						<td>$r->unit_sold</td>
						<td>$r->qty_transferred</td>
						<td>$r->stock</td>
						<td>$r->scanned_qty</td>
						<td>$r->missing</td>
				   </tr> 
EOD;
				$tblContent =$tblContent . $temp ;
			}
		}						
        $tblContent =$tblContent."</tbody></table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('tally.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



