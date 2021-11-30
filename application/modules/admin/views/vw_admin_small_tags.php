<style>
.boxed {
	width: 80px;
	height: 90px;
	border: 1px solid green;
	padding:10px;
	position: relative;
	top: 0;
	left: 0;
	display: inline-block;
	bottom: 0;
}
.logo-img{
  position: absolute;
  bottom: 85px;
  left:0px;
  width:  15;
  height: 15;
  display: block;
}

.qr-img{
	width:  70;
	height: 70;
	float: left;
	margin-left: 13px;
	margin-top: 13px;
}

.qr{
	position: absolute;
	top:5px;
	right: 25px;
	width:  78;
	height: 78;
}
.caption {
  position: absolute;
  width: 40px;
  overflow: hidden;
  font-size: 10px;
  bottom: 9px;
  float: right;
  margin-left: 50px;
}
.code{
  position: absolute;
  width: 75px;
  float: left;
  overflow:hidden;
  font-size: 9px;
  bottom: 9px;
  margin-left: -5px;

}
.name{
  position: absolute;
  width: 75px;
  float: left;
  overflow:hidden;
  font-size: 6px;
  top: 98px;
  margin-left: -5px;

}
.date {
  position: absolute;
  width: 75px;
  float: left;
  overflow:hidden;
  font-size: 10px;
  margin-left:38px;
  margin-top: 5px;

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
			$ctr = 0;
			/*
			if(isset($is_batch_print) && $is_batch_print)
			{
				foreach($print_qty as $x)
				{
					foreach($x as $key => $val)
					{
						if($key == $p->sku && $val <= $p->quantity)
							$qty = $val;
						else
							$qty = $p->quantity;
					}
				}
			}
			else*/
			$qty = $p->quantity;
			
			
			while($ctr != $qty)
			{
				$ctr++;
				echo '<div class="boxed">';
				echo "<div class='date'>$date</div>";
				echo "<div class='code'>$p->sku</div>";
				echo "<div class='name'>$p->name</div>";
				echo "<img src='".base_url('assets/images/gb-logo.png')."' class = 'logo-img'/>";
					if (!is_dir('assets/qr/small')) 
					mkdir('assets/qr/small', 0777, true);
					$path = FCPATH."assets/qr/small/";
					$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
					$params['data'] = $p->sku;
					$params['level'] = 'L';
					$params['size'] = 30;
					$params['savename'] = $path.$filename;
					$this->ciqrcode->generate($params);		
				echo "<img src = '". base_url('assets/qr/small/'.$filename) ."' class = 'qr-img'>";
				echo "<br>";
				echo "<div class='caption'>₱$p->selling_price</div></div>";
			} 
		}					
	}					
			
}
else
{
	foreach($product as $p):
	$ctr = 0;
	if(isset($sh_qty) && $sh_qty)
		$qty = $sh_qty;
	else
		$qty = $p->quantity;
	while($ctr != $qty)
	{
		$ctr++;
		echo '<div class="boxed">';
		echo "<div class='date'>$date</div>";
		echo "<div class='code'>$p->sku</div>";
		echo "<div class='name'>$p->name</div>";
		echo "<img src='".base_url('assets/images/gb-logo.png')."' class = 'logo-img'/>";
			if (!is_dir('assets/qr/small')) 
			mkdir('assets/qr/small', 0777, true);
			$path = FCPATH.$small_qr_path;
			$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
			$params['data'] = $p->sku;
			$params['level'] = 'L';
			$params['size'] = 30;
			$params['savename'] = $path.$filename;
			$this->ciqrcode->generate($params);		
		echo "<img src = '". base_url('assets/qr/small/'.$filename) ."' class = 'qr-img'>";
		echo "<br>";
		echo "<div class='caption'>₱$p->selling_price</div></div>";
	} 
	endforeach;		
}



?>
<!--
<div class='boxed'>
	<div class='date'>3-3-1000</div>
	<div class='code'>code code</div>
	<div class='name'>name name</div>
	<img src = '<?php echo base_url('assets/images/gb-logo.png');?>' class = 'logo-img'>;
	<img src = '<?php echo base_url('assets/images/test.png');?>' class = 'qr-img'>;
	<br>
	<div class='caption'>100</div>
</div>
-->
<script>
window.onload = function() { window.print(); }
</script>
