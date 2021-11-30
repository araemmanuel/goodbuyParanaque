
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('SUMMARY TRANSFERRED ITEMS');
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
            <th>Category</th>
            <th>Sub<br>category</th>
            <th>Product Code</th>		
			<th>Product Name</th>
			<th>Attributes</th>			
			<th>Unit Cost</th>		
			<th>Unit Trans<br>ferred</th>		
			<th>Total Cost</th>					
           </tr>
		</thead>
        
EOD;
        $grand_total_cost = 0;
		foreach($summary_transferred as $r) {
		$grand_total_cost += $r->total_cost;
		$r->unit_cost = number_format($r->unit_cost, 2);
		$r->total_cost = number_format($r->total_cost, 2);
        $temp = <<<EOD
          <tbody>
		  <tr>
				<td>$r->cat_name</td>
				<td>$r->subcat_name</td>
				<td>$r->sku</td>		
				<td>$r->name</td>
				<td>$r->options</td>
				<td>$r->unit_cost</td>
				<td>$r->unit_transferred</td>
				<td>$r->total_cost</td>
           </tr>     

EOD;
        $tblContent =$tblContent . $temp ;
        }						
		$grand_total_cost = number_format($grand_total_cost, 2);
		$total = <<<EOD
           <tr>
				<th class="th2"  colspan="7" >GRAND TOTAL</th>
				<td>$grand_total_cost</td>
           </tr>
		   </tbody>
EOD;
		$tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('summary_transferred_items.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



