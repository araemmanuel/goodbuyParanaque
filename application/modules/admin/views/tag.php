<style> 
		td{
			text-align:center;
		}
		th{
				text-align:center;
				color:white;
				background-color: #00B050;
				font-weight:bold;
		}	
		.th2{
			background-color:#5c8a8a;
			text-align:right;
			font-weight:bold;
		}
		.boxed {
			width: 210px;
			height: 120px;
			border: 1px solid green;
			padding:10px;
			position: relative;
			top: 0;
			left: 0;
			display: inline-block;
			bottom: 0;
		}
		.logo-img{
			width:  100;
			height: 100;
		}

		.qr-img{
			width:  90;
			height: 90;
		}

		.qr{
			position: absolute;
			bottom: 37px;
			right:25px;
			width:  78;
			height: 78;
		}
		.caption {
		  position: relative;
		  width: 85px;
		  float: right;
		  overflow: hidden;
		  font-size: 22px;
		  bottom: 1px;

		}
		.code{
		  position: absolute;
		  width: 75px;
		  float: left;
		  overflow:hidden;
		  font-size: 12px;
		  bottom: 18px;
		  margin-left: 15px;

		}
		.name{
		  position: absolute;
		  width: 75px;
		  float: left;
		  overflow:hidden;
		  font-size: 12px;
		  bottom: 3px;
		  margin-left: 15px;

		}
		.date {
		  position: absolute;
		  width: 75px;
		  float: left;
		  overflow:hidden;
		  font-size: 12px;
		  margin-left: 0px;
		  bottom: 122px;

		}
</style>
<?php
    $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 

'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
 
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 

PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 

PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 60, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage('P','Letter');
		$pdf->Ln(8);
		$date = date('Y-m-d'); 
		$temp = $tag = null;
		foreach($product as $p):
		$ctr = 0;
		//while($ctr != $p->quantity)
		//{
			
			if(isset($p->primary_image) && $p->primary_image != 

'None')
				$prod_img = "<img src='".base_url($p-

>primary_image)."' class = 'logo-img'/>";
			else
				$prod_img =  "<img src='".base_url

('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";
			$path = FCPATH."assets\qr\large\\";
			$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 

3)-1).date('Y-m-d').$ctr;
			$params['data'] = 'Code = ' . $p->sku  . '   Name =  

'. $p->name  . '   Price = ' . $p->selling_price;
			$params['level'] = 'L';
			$params['size'] = 30;
			$params['savename'] = $path.$filename;
			$this->ciqrcode->generate($params);		
			$qr_img = "<img src = '". base_url

('assets/qr/large/'.$filename) ."' class = 'qr-img'>";
			
			$temp = <<<EOD
			<div class="boxed">
				<div class='date'>$date</div>
				<div class='code'>$p->sku</div>
				<div class='name'>$p->name</div>
				$prod_img
				<div class = 'qr'>
				$qr_img
				</div>
				<br>
				<div class='caption'>₱$p->selling_price</div>
			</div>
EOD;
			$ctr++;
		//}
			$tag = $tag . $temp;
		endforeach;
		
		
//heredoc style no white spaces after eod; and must be aligned with php tag
        $pdf->writeHTML($tag, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('Challenge_report.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



