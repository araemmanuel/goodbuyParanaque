<style>
.box {
	display: inline-block;
	position: relative;
	top: 0;
	left: 0;
	margin-bottom: 236px;
}

.box-front {
	border: 1px solid #888;
	padding: 12px;
	width: 8.5cm;
	height: 5.5cm;
	position: absolute;
	top: 0;
	left: 0;
	background: url("<?php echo base_url('assets/images/GB REWARDS CARD 1.jpg');?>");
	background-size: cover;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	-webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
    color-adjust: exact !important;
}

.box-back {
	border: 1px solid #888;
	padding: 12px;
	width: 8.5cm;
	height: 5.5cm;
	position: absolute;
	top: 0;
	left: 350px;
	background: url("<?php echo base_url('assets/images/GB REWARDS CARD 2.jpg');?>");
	background-size: cover;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	-webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
    color-adjust: exact !important;
}

.qr {
	position: absolute;
	top: 62px;
	left: 25px;
	width:  90;
	height: 90;
}

.qr-img {
	width:  100;
	height: 100;
}

.acc-number {
	position: absolute;
	overflow: hidden;
	font-family: "OCR-A Extended", monospace;
	width: 80%;
	font-size: 30px;
	top: 110px;
	left: 25px;
}

.name {
	position: absolute;
	overflow: hidden;
	font-family: "OCR A Extended", monospace;
	width: 60%;
	font-size: 16px;
	top: 145px;
	left: 25px;
}

.validity {
	position: absolute;
	overflow: hidden;
	font-family: "OCR A Extended", monospace;
	width: 100%;
	font-size: 11px;
	top: 147px;
	left: 260px;
}

.terms {
	position: absolute;
	overflow: hidden;
	font-family: Arial, Helvetica, sans-serif;
	width: 70%;
	font-size: 8px;
	top: 108px;
	left: 116px;
}
</style>

<?php

if($is_batch_print)
{
	foreach($batch_card as $i)
	{
		foreach($i as $c)
		{
			echo "<div class='box'>";
			echo "<div class='box-front'>";
			echo "<div class='validity'>VALID UNTIL <br> ".date('M d, Y', strtotime($c->expiration_date))."</div>";
			echo "<div class='acc-number'>".substr($c->card_no, 0, 5) . ' '. substr($c->card_no, 6, 9) . ' '. substr($c->card_no, 10, 11) ."</div>";
			echo "<div class='name'>".strtoupper($c->name)."</div>";
			echo "</div>";
			if (!is_dir('assets/qr/card')) 
				mkdir('assets/qr/card', 0777, true);
			
			$path = FCPATH.$card_qr_path;
			$filename = 1 . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d');
			$params['data'] = $c->card_no . ','.$c->membership_id;
			$params['level'] = 'L';
			$params['size'] = 30;
			$params['savename'] = $path.$filename;
			$this->ciqrcode->generate($params);		
			
			echo "<div class='box-back'>";
			echo "<div class = 'qr'>";
			echo "<img src = '".base_url('assets/qr/card/'.$filename)."' class = 'qr-img'>";
			echo "</div>";
			
			echo "<div class='terms'>";
			echo "<ul>";
			echo "<li>The use of this card is governed by the terms and conditions embodied in the agreement</li>";
			echo "<li>If found, please return to GoodBuy Enterprises</li>";
			echo "<li>For inquiries, visit <em>www.goodbuy.com</em></li>";
			echo "</ul>";
			echo "</div>";
			echo "</div>";
			
			echo "</div><br>";
		}
		
	}
}
else
{
	foreach($card_details as $c) 
	{
		echo "<div class='box'>";
	
		echo "<div class='box-front'>";
		echo "<div class='validity'>VALID UNTIL <br> ".date('M d, Y', strtotime($c->expiration_date))."</div>";
		echo "<div class='acc-number'>".substr($c->card_no, 0, 5) . ' '. substr($c->card_no, 6, 9) . ' '. substr($c->card_no, 10, 11) ."</div>";
		echo "<div class='name'>".strtoupper($c->name)."</div>";
		echo "</div>";
		if (!is_dir('assets/qr/card')) 
			mkdir('assets/qr/card', 0777, true);
		
		$path = FCPATH.$card_qr_path;
		$filename = 1 . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d');
		$params['data'] = $c->card_no . ','.$c->membership_id;
		$params['level'] = 'L';
		$params['size'] = 30;
		$params['savename'] = $path.$filename;
		$this->ciqrcode->generate($params);		
		
		echo "<div class='box-back'>";
		echo "<div class = 'qr'>";
		echo "<img src = '".base_url('assets/qr/card/'.$filename)."' class = 'qr-img'>";
		echo "</div>";
		
		echo "<div class='terms'>";
		echo "<ul>";
		echo "<li>The use of this card is governed by the terms and conditions embodied in the agreement</li>";
		echo "<li>If found, please return to GoodBuy Enterprises</li>";
		echo "<li>For inquiries, visit <em>www.goodbuy.com</em></li>";
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		
		echo "</div><br>";
	}
}

?>
<script>
window.onload = function() { window.print(); }
</script>