
<?php
    $pdf = new Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
       //$pdf->SetKeywords('PDF, PDF, example, test, guide');

        // set default header data 
		$pdf->setReportName('DETAILED PROFIT');
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
        <table border="1" cellpadding="3" text-align="center"> 
			<thead>
			   <tr>
				<th>Product Code</th>
				<th>Product Name</th>
				<th>Attributes</th>
				<th>Purchase Price</th>
				<th>Selling Price</th>
				<th>Quantity</th>
				<th>Discounted Price</th>
				<th>Total Purchase Price</th>
				<th>Total Selling Price</th>
				<th>Profit</th>
			   </tr>
			</thead>
EOD;
		 $grand_total_profit = $grand_total_purchase = $grand_total_selling = 0;
		 foreach($detailed_profit as $r) {
			$grand_total_purchase = $grand_total_purchase + $r->total_purchase;
			$grand_total_selling = $grand_total_selling + $r->total_selling;
			$grand_total_profit = $grand_total_profit + $r->profit; 
			$r->purchase_price = number_format($r->purchase_price, 2);
			$r->selling_price = number_format($r->selling_price, 2);
			$r->discount = number_format($r->discount, 2);
			$r->total_purchase = number_format($r->total_purchase, 2);
			$r->total_selling = number_format($r->total_selling, 2);
			$r->profit = number_format($r->profit, 2);
         $temp = <<<EOD
		 <tbody>
			  <tr>
					<td>$r->sku</td>
					<td>$r->name</td>
					<td>$r->options</td>
					<td>$r->purchase_price</td>
					<td>$r->selling_price</td>
					<td>$r->qty</td>           
					<td>$r->discount</td>					
					<td>$r->total_purchase</td>
					<td>$r->total_selling</td>	
					<td>$r->profit</td>				
			   </tr>         
		   
EOD;
        $tblContent =$tblContent . $temp ;
        }
		$grand_total_purchase = number_format($grand_total_purchase, 2);
		$grand_total_selling = number_format($grand_total_selling, 2);
		$grand_total_profit = number_format($grand_total_profit, 2);
		
		$total = <<<EOD
			<tr>
				<th class="th2" colspan="7">GRAND TOTAL</th>
				<td>$grand_total_purchase</td>
				<td>$grand_total_selling</td>
				<td>$grand_total_profit</td>
           </tr>
		   </tbody>
EOD;
        $tblContent =$tblContent.$total."</table>";
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('detailed_profit.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



