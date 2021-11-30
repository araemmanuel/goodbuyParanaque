<style> 
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
	margin-top: 8;
	margin-left: 8;
	width:  90;
	height: 85;
}

.qr-img{
	width:  90;
	height: 90;
}

.qr{
	position: absolute;
	bottom: 47px;
	right: 25px;
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
  bottom: 21px;
  margin-left: 5px;

}
.name{
  position: absolute;
  width: 115px;
  float: left;
  overflow:hidden;
  font-size: 8px;
  top: 116px;
  margin-left: 5px;

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
	$date = date('Y-m-d'); 
		
		if(isset($is_selected) && $is_selected)
		{
			foreach($product as $i)
			{
				
				$ctr = 0;
				foreach($i as $p)
				{
					
					$ctr++;
					echo '<div class="boxed">';
					echo "<div class='date'>$date</div>";
					echo "<div class='code'>$p->sku</div>";
					echo "<div class='name'>$p->name</div>";
					if(isset($p->primary_image) && $p->primary_image != 'None')
						echo "<img src='".base_url($p->primary_image)."' class = 'logo-img'/>";
					else
						echo "<img src='".base_url('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";	echo "<div class = 'qr'>";
						$path = FCPATH."assets\qr\large\\";
						$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
						$params['data'] = 'Code = ' . $p->sku  . '   Name =  '. $p->name  . '   Price = ' . $p->selling_price;
						$params['level'] = 'L';
						$params['size'] = 30;
						$params['savename'] = $path.$filename;
						$this->ciqrcode->generate($params);		
					echo "<img src = '". base_url('assets/qr/large/'.$filename) ."' class = 'qr-img'>";
					echo "</div><br>";
					echo "<div class='caption'>₱$p->selling_price</div></div>";
				}					
			}					
			
		}
		else
		{
			foreach($product as $p):
			$ctr = 0;
			//while($ctr != $p->quantity)
			//{
				
				$ctr++;
				echo '<div class="boxed">';
				echo "<div class='date'>$date</div>";
				echo "<div class='code'>$p->sku</div>";
				echo "<div class='name'>$p->name</div>";
				if(isset($p->primary_image) && $p->primary_image != 'None')
					echo "<img src='".base_url($p->primary_image)."' class = 'logo-img'/>";
				else
					echo "<img src='".base_url('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";	echo "<div class = 'qr'>";
					$path = FCPATH."assets\qr\large\\";
					$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
					$params['data'] = 'Code = ' . $p->sku  . '   Name =  '. $p->name  . '   Price = ' . $p->selling_price;
					$params['level'] = 'L';
					$params['size'] = 30;
					$params['savename'] = $path.$filename;
					$this->ciqrcode->generate($params);		
				echo "<img src = '". base_url('assets/qr/large/'.$filename) ."' class = 'qr-img'>";
				echo "</div><br>";
				echo "<div class='caption'>₱$p->selling_price</div></div>";
			//} 
			endforeach;			
		}
	
		
		
//heredoc style no white spaces after eod; and must be aligned with php tag
       // $pdf->writeHTML($tblHeader.$tblContent, true, false, true, false, '');
      // ob_end_clean();
        //$pdf->Output('Challenge_report.pdf', 'I');
//https://html.com/tables/rowspan-colspan/



/*
    $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetTitle('GOODBUY REPORT');
       $pdf->SetSubject('GOODBUY REPORT');
 
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
*/
?>

<script>
window.onload = function() { window.print(); }
</script>