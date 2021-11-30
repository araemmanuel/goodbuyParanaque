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
  width: 150px;
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
						$path = FCPATH.$large_qr_path;
						$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
						$params['data'] = $p->sku;
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
					$path = FCPATH.$large_qr_path;
					$filename = $p->sku . '-' .rand(pow(10, 3-1), pow(10, 3)-1).date('Y-m-d').$ctr;
					$params['data'] = $p->sku;
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
	
?>
<script>
window.onload = function() { window.print(); }
</script>