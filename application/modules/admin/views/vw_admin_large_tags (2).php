<style>
.boxed {
	width: 155px;
	height: 100px;
	border: 1px solid green;
	padding:10px;
	position: relative;
	top: 0;
	left: 0;
	display: inline-block;
	bottom: 0;
}

.logo-img{
	margin-top: 12px;
	left: 15;
	width:  70;
	height: 65;
}

.qr-img{
	width:  70;
	height: 70;
}

.qr{
	position: absolute;
	top: 20;
	right: 5;
	width:  78;
	height: 78;
}

.caption {
	position: relative;
	width: 85px;
	float: right;
	overflow: hidden;
	font-size: 18px;
	top: 3;
	right: -17;
}

.code{
	position: absolute;
	width: 75px;
	float: left;
	overflow:hidden;
	font-size: 12px;
	bottom: 17px;
	left: 10;
}

.name{
	position: absolute;
	width: 90px;
	float: left;
	overflow:hidden;
	font-size: 9px;
	top: 100px;
	left: 10;
}

.date {
	position: absolute;
	width: 75px;
	float: right;
	overflow:hidden;
	font-size: 12px;
	top: 5;
	right: -5;
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
			while($ctr != $p->quantity)
			{
				$ctr++;
				echo '<div class="boxed">';
				echo "<div class='date'>$date</div>";
				echo "<div class='code'>$p->sku</div>";
				echo "<div class='name'>$p->name</div>";
				//if(isset($p->primary_image) && $p->primary_image != 'None')
					//echo "<img src='".base_url($p->primary_image)."' class = 'logo-img'/>";
				//else
				echo "<img src='".base_url('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";
				echo "<div class = 'qr'>";
					$path = FCPATH.$large_qr_path;
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
			
}
else
{
	foreach($product as $p):
	$ctr = 0;
	while($ctr != $p->quantity)
	{
		$ctr++;
		echo '<div class="boxed">';
		echo "<div class='date'>$date</div>";
		echo "<div class='code'>$p->sku</div>";
		echo "<div class='name'>$p->name</div>";
		//if(isset($p->primary_image) && $p->primary_image != 'None')
			//echo "<img src='".base_url($p->primary_image)."' class = 'logo-img'/>";
		//else
		echo "<img src='".base_url('assets/admin/images/gb-logo.png')."' class = 'logo-img'/>";
		echo "<div class = 'qr'>";
			$path = FCPATH.$large_qr_path;
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
	endforeach;		
}
?>
<script>
window.onload = function() { window.print(); }
</script>